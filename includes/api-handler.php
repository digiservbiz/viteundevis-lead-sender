<?php
if (!defined('ABSPATH')) exit;

define('VUD_API_URL_PROD', 'https://www.viteundevis.com/api/get.php');
define('VUD_API_URL_TEST', 'https://www.viteundevis.com/api/get.php?test=1');

// ViteUnDevis error codes -> friendly French messages (from API doc v1.5).
function vud_error_messages() {
    return array(
        '401' => "Pas d'ID de catégorie.",
        '402' => "Catégorie introuvable.",
        '403' => "Clé d'API absente ou incorrecte.",
        '404' => "Aucune donnée envoyée.",
        '405' => "URL de callback incorrecte.",
        '004' => "Code postal du projet absent ou incorrect.",
        '005' => "Ville du projet absente ou incorrecte.",
        '006' => "Champ terrain incorrect.",
        '007' => "Permis de construire incorrect.",
        '010' => "Type de personne incorrect.",
        '011' => "Type de bien incorrect.",
        '012' => "Merci de renseigner une description du projet.",
        '013' => "Merci de préciser votre situation.",
        '101' => "Nom incorrect ou manquant.",
        '102' => "Prénom incorrect ou manquant.",
        '103' => "Email invalide.",
        '104' => "Adresse incorrecte ou manquante.",
        '105' => "Code postal incorrect ou manquant.",
        '106' => "Ville incorrecte ou manquante.",
        '107' => "Téléphone invalide (fixe ou mobile requis).",
        '108' => "Délais du projet invalide.",
        '109' => "Cette demande a déjà été envoyée récemment.",
        '888' => "Une erreur est survenue, merci de contacter contact@viteundevis.com.",
    );
}

add_action('admin_post_vud_submit_lead', 'vud_handle_submission');
add_action('admin_post_nopriv_vud_submit_lead', 'vud_handle_submission');

function vud_handle_submission() {
    $redirect_back = !empty($_POST['vud_redirect_back']) ? esc_url_raw(wp_unslash($_POST['vud_redirect_back'])) : home_url('/');

    if (!isset($_POST['vud_nonce']) || !wp_verify_nonce($_POST['vud_nonce'], 'vud_submit_lead')) {
        vud_redirect_with_notice($redirect_back, 'error', array('Session expirée, merci de réessayer.'));
    }

    $api_key = get_option('vud_api_key', '');
    if (empty($api_key)) {
        vud_redirect_with_notice($redirect_back, 'error', array('Formulaire mal configuré (clé API manquante).'));
    }

    // --- Collect + sanitize ---
    $f = function ($key) {
        return isset($_POST[$key]) ? sanitize_text_field(wp_unslash($_POST[$key])) : '';
    };
    $checkbox = function ($key) {
        return !empty($_POST[$key]) ? 1 : 0;
    };

    $description = isset($_POST['description']) ? sanitize_textarea_field(wp_unslash($_POST['description'])) : '';

    $tel    = $f('tel');
    $mobile = $f('mobile');

    // --- Minimal server-side validation before we even call the API ---
    $errors = array();
    if ($f('nom') === '')          $errors[] = 'Le nom est requis.';
    if ($f('prenom') === '')       $errors[] = 'Le prénom est requis.';
    if (!is_email($f('email')))    $errors[] = 'Email invalide.';
    if ($tel === '' && $mobile === '') $errors[] = 'Merci de renseigner au moins un numéro de téléphone.';
    if ($f('adresse1') === '')     $errors[] = "L'adresse est requise.";
    if ($f('cp') === '')           $errors[] = 'Le code postal est requis.';
    if ($f('ville') === '')        $errors[] = 'La ville est requise.';
    if ($f('cat_id') === '')       $errors[] = 'Merci de sélectionner un type de projet.';
    if ($f('type_bien') === '')    $errors[] = 'Merci de sélectionner un type de bien.';
    if ($f('situation') === '')    $errors[] = 'Merci de préciser votre situation.';
    if ($f('tp') === '')           $errors[] = 'Merci de préciser le type de personne.';
    if ($f('delais') === '')       $errors[] = 'Merci de préciser les délais du projet.';
    if ($description === '')       $errors[] = 'La description du projet est requise.';

    if (!empty($errors)) {
        vud_redirect_with_notice($redirect_back, 'error', $errors);
    }

    // --- Build the POST payload exactly per the API spec ---
    $post = array(
        'nom'          => $f('nom'),
        'prenom'       => $f('prenom'),
        'societe'      => $f('societe'),
        'email'        => $f('email'),
        'adresse1'     => $f('adresse1'),
        'adresse2'     => $f('adresse2'),
        'cp'           => $f('cp'),
        'ville'        => $f('ville'),
        'cp_projet'    => $f('cp_projet'),
        'ville_projet' => $f('ville_projet'),
        'pays'         => $f('pays') ?: 'fr',
        'surface'      => $f('surface'),
        'budget'       => $f('budget'),
        'terrain'      => $checkbox('terrain'),
        'permis'       => $f('permis') ?: 3,
        'tp'           => $f('tp'),
        'matin'        => $checkbox('matin'),
        'midi'         => $checkbox('midi'),
        'soir'         => $checkbox('soir'),
        'we'           => $checkbox('we'),
        'delais'       => $f('delais'),
        'tel'          => $tel,
        'mobile'       => $mobile,
        'cat_id'       => $f('cat_id'),
        'type_bien'    => $f('type_bien'),
        'situation'    => $f('situation'),
        'description'  => $description,
        'format_return'=> 'json',
        'site_name'    => get_option('vud_site_name', ''),
        'key'          => $api_key,
    );

    $callback_url = get_option('vud_callback_url', '');
    if (!empty($callback_url)) {
        $post['callback'] = $callback_url;
    }

    $test_mode = (bool) get_option('vud_test_mode', 1);
    $url = $test_mode ? VUD_API_URL_TEST : VUD_API_URL_PROD;

    // --- Call the API ---
    $response = wp_remote_post($url, array(
        'timeout'    => 20,
        'user-agent' => 'partenaire-apivud-' . $api_key,
        'body'       => $post,
    ));

    global $wpdb;
    $table = $wpdb->prefix . VUD_TABLE;

    if (is_wp_error($response)) {
        $wpdb->insert($table, array(
            'form_type'       => 'lead',
            'status'          => 'error',
            'cat_id'          => $post['cat_id'],
            'nom'             => $post['nom'],
            'email'           => $post['email'],
            'submitted_data'  => wp_json_encode($post),
            'response'        => $response->get_error_message(),
        ));
        vud_redirect_with_notice($redirect_back, 'error', array("Impossible de contacter ViteUnDevis pour le moment, merci de réessayer plus tard."));
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    // Fall back to PHP serialize format if json_decode fails (older API behavior).
    if ($data === null) {
        $maybe = @unserialize($body);
        if ($maybe !== false) $data = $maybe;
    }

    $codes = isset($data['code_retour']) ? (array) $data['code_retour'] : array();
    $first_code = isset($codes[0]['code']) ? (string) $codes[0]['code'] : '';
    $is_success = ($first_code === '200');

    $devis_id = $is_success && isset($data['devis_data']['devis_id']) ? $data['devis_data']['devis_id'] : null;

    $wpdb->insert($table, array(
        'form_type'      => 'lead',
        'status'         => $is_success ? ($test_mode ? 'test' : 'success') : 'error',
        'devis_id'       => $devis_id,
        'cat_id'         => $post['cat_id'],
        'nom'            => $post['nom'],
        'email'          => $post['email'],
        'submitted_data' => wp_json_encode($post),
        'response'       => $body,
    ));

    if ($is_success) {
        $redirect_url = get_option('vud_redirect_url', '');
        if (!empty($redirect_url)) {
            $redirect_url = add_query_arg('devis_id', $devis_id, $redirect_url);
            wp_safe_redirect($redirect_url);
            exit;
        }
        $msg = $test_mode
            ? array('Test réussi (mode test actif — rien n\'a été enregistré chez ViteUnDevis).')
            : array('Votre demande de devis a bien été envoyée. Vous serez contacté(e) rapidement.');
        vud_redirect_with_notice($redirect_back, 'success', $msg);
    }

    // Build friendly error messages from returned codes.
    $messages = array();
    $known = vud_error_messages();
    foreach ($codes as $c) {
        $code = isset($c['code']) ? (string) $c['code'] : '';
        $messages[] = isset($known[$code]) ? $known[$code] : (isset($c['code_texte']) ? $c['code_texte'] : 'Erreur inconnue.');
    }
    if (empty($messages)) {
        $messages[] = "L'envoi a échoué, merci de vérifier vos informations et de réessayer.";
    }
    vud_redirect_with_notice($redirect_back, 'error', $messages);
}

/**
 * Stores a short-lived notice and redirects back to the form with a reference token.
 */
function vud_redirect_with_notice($url, $type, $messages) {
    $token = wp_generate_password(12, false);
    set_transient('vud_notice_' . $token, array('type' => $type, 'messages' => $messages), 5 * MINUTE_IN_SECONDS);
    $url = add_query_arg('vud_notice', $token, $url);
    wp_safe_redirect($url);
    exit;
}
