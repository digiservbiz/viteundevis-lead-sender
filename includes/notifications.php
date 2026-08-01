<?php
if (!defined('ABSPATH')) exit;

/**
 * Extension points for future CRM integration, called from api-handler.php
 * right after each submission is logged locally:
 *   - vud_maybe_send_webhook(): a no-code webhook URL (Zapier/Make/n8n/most
 *     CRM inbound webhooks)
 *   - vud_maybe_send_email_notification(): an email copy
 *   - the 'vud_after_submission' action: for a custom mu-plugin/snippet to
 *     hook a specific CRM's PHP SDK in later without touching this plugin
 */

function vud_maybe_send_email_notification($post, $status, $devis_id) {
    if (!get_option('vud_notify_email', 0)) return;

    $to = get_option('vud_notify_email_address', '') ?: get_bloginfo('admin_email');
    if (!is_email($to)) return;

    $status_labels = array('success' => 'Envoyée', 'test' => 'Test', 'error' => 'Erreur');
    $label = isset($status_labels[$status]) ? $status_labels[$status] : $status;

    $subject = sprintf('[%s] Nouveau lead ViteUnDevis (%s) — %s %s', get_bloginfo('name'), $label, $post['prenom'], $post['nom']);

    $lines = array(
        "Statut : $label",
        $devis_id ? "Devis ID : $devis_id" : null,
        "Nom : {$post['prenom']} {$post['nom']}",
        "Email : {$post['email']}",
        "Téléphone : {$post['tel']}" . (!empty($post['mobile']) ? " / {$post['mobile']}" : ''),
        "Ville : {$post['ville']}",
        "Catégorie : {$post['cat_id']}",
        '',
        'Description :',
        $post['description'],
        '',
        'Voir toutes les demandes : ' . admin_url('admin.php?page=vud-dashboard'),
    );
    $body = implode("\n", array_filter($lines, function ($l) { return $l !== null; }));

    wp_mail($to, $subject, $body);
}

/**
 * Posts the lead as JSON to an admin-configured webhook URL. Failures are
 * swallowed (the lead is already safely stored locally either way) but the
 * outcome is returned so the caller can log it for debugging.
 */
function vud_maybe_send_webhook($post, $status, $devis_id, $response_body) {
    $webhook_url = get_option('vud_webhook_url', '');
    if (empty($webhook_url)) return null;

    $payload = array(
        'status'         => $status,
        'devis_id'       => $devis_id,
        'submitted_at'   => current_time('mysql'),
        'site'           => home_url('/'),
        'lead'           => $post,
        'viteundevis_response' => $response_body,
    );

    $result = wp_remote_post($webhook_url, array(
        'timeout' => 8, // kept short: a slow/unreachable CRM must never delay the visitor's redirect noticeably
        'headers' => array('Content-Type' => 'application/json'),
        'body'    => wp_json_encode($payload),
    ));

    if (is_wp_error($result)) {
        return 'failed';
    }
    $code = wp_remote_retrieve_response_code($result);
    return ($code >= 200 && $code < 300) ? 'sent' : 'failed';
}
