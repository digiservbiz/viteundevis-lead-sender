<?php
if (!defined('ABSPATH')) exit;

add_action('wp_dashboard_setup', 'vud_register_dashboard_widget');
function vud_register_dashboard_widget() {
    if (!current_user_can('manage_options')) return;
    wp_add_dashboard_widget('vud_home_widget', 'ViteUnDevis — Aperçu des demandes', 'vud_render_dashboard_widget');
}

function vud_render_dashboard_widget() {
    global $wpdb;
    $table = $wpdb->prefix . VUD_TABLE;

    $total   = (int) $wpdb->get_var("SELECT COUNT(*) FROM $table");
    $success = (int) $wpdb->get_var("SELECT COUNT(*) FROM $table WHERE status = 'success'");
    $test    = (int) $wpdb->get_var("SELECT COUNT(*) FROM $table WHERE status = 'test'");
    $errors  = (int) $wpdb->get_var("SELECT COUNT(*) FROM $table WHERE status = 'error'");
    $week    = (int) $wpdb->get_var("SELECT COUNT(*) FROM $table WHERE submission_time >= DATE_SUB(NOW(), INTERVAL 7 DAY)");

    $recent = $wpdb->get_results("SELECT submission_time, status, nom, cat_id FROM $table ORDER BY submission_time DESC LIMIT 5");

    $test_mode = (bool) get_option('vud_test_mode', 1);
    ?>
    <?php if ($test_mode): ?>
        <p style="background:#fcf9e8;border-left:4px solid #dba617;padding:8px 12px;margin-top:0;">
            <strong>Mode test actif</strong> — les demandes ne sont pas encore transmises réellement à ViteUnDevis.
            <a href="<?php echo esc_url(admin_url('admin.php?page=vud-settings')); ?>">Changer</a>
        </p>
    <?php endif; ?>

    <div style="display:flex;gap:12px;margin-bottom:16px;flex-wrap:wrap;">
        <div style="flex:1;min-width:90px;background:#f6f7f7;border-radius:4px;padding:10px;text-align:center;">
            <div style="font-size:22px;font-weight:600;"><?php echo esc_html($total); ?></div>
            <div style="font-size:12px;color:#666;">Total</div>
        </div>
        <div style="flex:1;min-width:90px;background:#edf9ee;border-radius:4px;padding:10px;text-align:center;">
            <div style="font-size:22px;font-weight:600;color:#235a26;"><?php echo esc_html($success); ?></div>
            <div style="font-size:12px;color:#666;">Envoyées</div>
        </div>
        <div style="flex:1;min-width:90px;background:#fdf6e3;border-radius:4px;padding:10px;text-align:center;">
            <div style="font-size:22px;font-weight:600;color:#8a6d1a;"><?php echo esc_html($test); ?></div>
            <div style="font-size:12px;color:#666;">Test</div>
        </div>
        <div style="flex:1;min-width:90px;background:#fdeeee;border-radius:4px;padding:10px;text-align:center;">
            <div style="font-size:22px;font-weight:600;color:#7a1c1c;"><?php echo esc_html($errors); ?></div>
            <div style="font-size:12px;color:#666;">Erreurs</div>
        </div>
    </div>

    <p style="color:#666;font-size:13px;">
        <?php echo esc_html($week); ?> demande(s) au cours des 7 derniers jours.
    </p>

    <?php if (!empty($recent)): ?>
        <table class="widefat" style="margin-top:8px;">
            <thead><tr><th>Date</th><th>Statut</th><th>Nom</th><th>Catégorie</th></tr></thead>
            <tbody>
                <?php foreach ($recent as $row): ?>
                    <tr>
                        <td><?php echo esc_html(mysql2date('d/m H:i', $row->submission_time)); ?></td>
                        <td><?php echo esc_html(ucfirst($row->status)); ?></td>
                        <td><?php echo esc_html($row->nom); ?></td>
                        <td><?php echo esc_html($row->cat_id); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucune demande pour le moment.</p>
    <?php endif; ?>

    <p class="vud-widget-links" style="margin-top:12px;">
        <a href="<?php echo esc_url(admin_url('admin.php?page=vud-dashboard')); ?>" class="button button-primary">Voir toutes les demandes</a>
        <a href="<?php echo esc_url(admin_url('admin.php?page=vud-settings')); ?>" class="button">Paramètres</a>
    </p>
    <?php
}
