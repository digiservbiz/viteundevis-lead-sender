<?php
/**
 * Plugin Name:       ViteUnDevis Lead Sender
 * Description:       Collects project leads via a frontend form and submits them to the ViteUnDevis "dépôt de devis" API (v1.5/v1.6), with an admin dashboard, WP dashboard widget, CSV export, and an optional CRM/webhook hook.
 * Version:           2.5
 * Requires PHP:      7.4
 * Text Domain:       vud
 */

if (!defined('ABSPATH')) {
    exit; // No direct access.
}

define('VUD_VERSION', '2.5');
define('VUD_DB_VERSION', '2'); // bump when the table schema changes
define('VUD_PATH', plugin_dir_path(__FILE__));
define('VUD_URL', plugin_dir_url(__FILE__));
define('VUD_TABLE', 'vud_submissions');

require_once VUD_PATH . 'includes/settings.php';
require_once VUD_PATH . 'includes/frontend.php';
require_once VUD_PATH . 'includes/dashboard.php';
require_once VUD_PATH . 'includes/widget.php';
require_once VUD_PATH . 'includes/notifications.php';
require_once VUD_PATH . 'includes/api-handler.php';

/**
 * Creates/updates the submissions table. Safe to call repeatedly — dbDelta()
 * only applies the diff. Called on activation AND on admin_init (guarded by
 * a version check) so upgrades from an older zip pick up schema changes
 * without needing a deactivate/reactivate cycle.
 */
function vud_run_db_upgrade() {
    global $wpdb;
    $table_name      = $wpdb->prefix . VUD_TABLE;
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        submission_time datetime DEFAULT CURRENT_TIMESTAMP,
        form_type varchar(20) NOT NULL DEFAULT 'lead',
        status varchar(20) NOT NULL,
        devis_id varchar(50) DEFAULT NULL,
        cat_id varchar(20) DEFAULT NULL,
        nom varchar(255) DEFAULT NULL,
        email varchar(255) DEFAULT NULL,
        submitted_data longtext NOT NULL,
        response longtext,
        webhook_status varchar(20) DEFAULT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);

    update_option('vud_db_version', VUD_DB_VERSION);
}

register_activation_hook(__FILE__, 'vud_activate_plugin');
function vud_activate_plugin() {
    vud_run_db_upgrade();

    // Sensible defaults so the plugin isn't fully blank on first activation.
    if (get_option('vud_test_mode', null) === null) {
        update_option('vud_test_mode', 1); // Default to test mode until the admin turns it off deliberately.
    }
}

/**
 * Catches upgrades where the plugin files were replaced (e.g. re-uploading
 * a new zip over FTP) without a deactivate/activate cycle, which wouldn't
 * otherwise fire register_activation_hook.
 */
add_action('admin_init', 'vud_maybe_upgrade_db');
function vud_maybe_upgrade_db() {
    if (get_option('vud_db_version') !== VUD_DB_VERSION) {
        vud_run_db_upgrade();
    }
}
