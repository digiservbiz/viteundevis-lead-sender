<?php
if (!defined('ABSPATH')) exit;

add_action('admin_menu', 'vud_admin_menu');
function vud_admin_menu() {
    add_menu_page('ViteUnDevis', 'ViteUnDevis', 'manage_options', 'vud-dashboard', 'vud_dashboard_page_html', 'dashicons-clipboard', 58);
    add_submenu_page('vud-dashboard', 'Demandes', 'Demandes', 'manage_options', 'vud-dashboard', 'vud_dashboard_page_html');
    add_submenu_page('vud-dashboard', 'Paramètres', 'Paramètres', 'manage_options', 'vud-settings', 'vud_settings_page_html');
}

add_action('admin_post_vud_export_csv', 'vud_export_csv');
function vud_export_csv() {
    if (!current_user_can('manage_options') || !isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'vud_export_csv')) {
        wp_die('Accès refusé.');
    }

    global $wpdb;
    $table = $wpdb->prefix . VUD_TABLE;
    $rows = $wpdb->get_results("SELECT id, submission_time, status, devis_id, cat_id, nom, email, submitted_data FROM $table ORDER BY submission_time DESC", ARRAY_A);

    nocache_headers();
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=viteundevis-leads-' . gmdate('Y-m-d') . '.csv');

    $out = fopen('php://output', 'w');
    fputs($out, "\xEF\xBB\xBF"); // UTF-8 BOM for Excel.
    fputcsv($out, array('ID', 'Date', 'Statut', 'Devis ID', 'Catégorie', 'Nom', 'Email', 'Téléphone', 'Ville', 'Description'));

    foreach ($rows as $row) {
        $data = json_decode($row['submitted_data'], true) ?: array();
        fputcsv($out, array(
            $row['id'],
            $row['submission_time'],
            $row['status'],
            $row['devis_id'],
            $row['cat_id'],
            $row['nom'],
            $row['email'],
            isset($data['tel']) ? $data['tel'] : '',
            isset($data['ville']) ? $data['ville'] : '',
            isset($data['description']) ? $data['description'] : '',
        ));
    }
    fclose($out);
    exit;
}

function vud_dashboard_page_html() {
    if (!current_user_can('manage_options')) return;

    global $wpdb;
    $table = $wpdb->prefix . VUD_TABLE;

    $per_page = 20;
    $paged    = isset($_GET['paged']) ? max(1, (int) $_GET['paged']) : 1;
    $offset   = ($paged - 1) * $per_page;

    $status_filter = isset($_GET['status']) ? sanitize_key($_GET['status']) : '';
    $where = '';
    if (in_array($status_filter, array('success', 'error', 'test'), true)) {
        $where = $wpdb->prepare(' WHERE status = %s', $status_filter);
    }

    $total = (int) $wpdb->get_var("SELECT COUNT(*) FROM $table" . $where);
    $rows  = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table$where ORDER BY submission_time DESC LIMIT %d OFFSET %d", $per_page, $offset));

    $export_url = wp_nonce_url(admin_url('admin-post.php?action=vud_export_csv'), 'vud_export_csv');
    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline">Demandes ViteUnDevis</h1>
        <a href="<?php echo esc_url($export_url); ?>" class="page-title-action">Exporter en CSV</a>

        <ul class="subsubsub">
            <li><a href="<?php echo esc_url(remove_query_arg('status')); ?>" class="<?php echo $status_filter === '' ? 'current' : ''; ?>">Toutes</a> |</li>
            <li><a href="<?php echo esc_url(add_query_arg('status', 'success')); ?>" class="<?php echo $status_filter === 'success' ? 'current' : ''; ?>">Envoyées</a> |</li>
            <li><a href="<?php echo esc_url(add_query_arg('status', 'test')); ?>" class="<?php echo $status_filter === 'test' ? 'current' : ''; ?>">Test</a> |</li>
            <li><a href="<?php echo esc_url(add_query_arg('status', 'error')); ?>" class="<?php echo $status_filter === 'error' ? 'current' : ''; ?>">Erreurs</a></li>
        </ul>

        <table class="wp-list-table widefat fixed striped" style="margin-top:10px;">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Statut</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Catégorie</th>
                    <th>Devis ID</th>
                    <th>Détails</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($rows)): ?>
                    <tr><td colspan="7">Aucune demande pour le moment.</td></tr>
                <?php else: foreach ($rows as $row):
                    $data = json_decode($row->submitted_data, true) ?: array();
                    $badge_class = 'vud-badge-' . esc_attr($row->status);
                ?>
                    <tr>
                        <td><?php echo esc_html(mysql2date('d/m/Y H:i', $row->submission_time)); ?></td>
                        <td><span class="vud-badge <?php echo $badge_class; ?>"><?php echo esc_html(ucfirst($row->status)); ?></span></td>
                        <td><?php echo esc_html($row->nom); ?></td>
                        <td><?php echo esc_html($row->email); ?></td>
                        <td><?php echo esc_html($row->cat_id); ?></td>
                        <td><?php echo esc_html($row->devis_id ?: '—'); ?></td>
                        <td>
                            <details>
                                <summary>Voir</summary>
                                <p><strong>Téléphone:</strong> <?php echo esc_html($data['tel'] ?? ''); ?> <?php echo !empty($data['mobile']) ? '/ ' . esc_html($data['mobile']) : ''; ?></p>
                                <p><strong>Ville:</strong> <?php echo esc_html($data['ville'] ?? ''); ?></p>
                                <p><strong>Description:</strong> <?php echo esc_html($data['description'] ?? ''); ?></p>
                                <?php if ($row->status === 'error'): ?>
                                    <p><strong>Réponse API:</strong> <code><?php echo esc_html($row->response); ?></code></p>
                                <?php endif; ?>
                            </details>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>

        <?php
        $total_pages = (int) ceil($total / $per_page);
        if ($total_pages > 1): ?>
            <div class="tablenav"><div class="tablenav-pages">
                <?php echo paginate_links(array(
                    'base'      => add_query_arg('paged', '%#%'),
                    'format'    => '',
                    'current'   => $paged,
                    'total'     => $total_pages,
                )); ?>
            </div></div>
        <?php endif; ?>
    </div>
    <style>
        .vud-badge { padding: 2px 8px; border-radius: 3px; font-size: 12px; color: #fff; }
        .vud-badge-success { background: #46b450; }
        .vud-badge-test { background: #ffb900; color:#222; }
        .vud-badge-error { background: #dc3232; }
    </style>
    <?php
}
