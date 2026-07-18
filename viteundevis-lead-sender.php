<?php
/**
 * Plugin Name:       ViteUnDevis Lead Sender
 * Description:       Collects project leads via a frontend form and submits them to the ViteUnDevis "dépôt de devis" API (v1.5), with an admin dashboard and CSV export.
 * Version:           2.0
 * Requires PHP:      7.4
 * Text Domain:       vud
 */

if (!defined('ABSPATH')) {
    exit; // No direct access.
}

define('VUD_VERSION', '2.0');
define('VUD_PATH', plugin_dir_path(__FILE__));
define('VUD_URL', plugin_dir_url(__FILE__));
define('VUD_TABLE', 'vud_submissions');

require_once VUD_PATH . 'includes/settings.php';
require_once VUD_PATH . 'includes/frontend.php';
require_once VUD_PATH . 'includes/dashboard.php';
require_once VUD_PATH . 'includes/api-handler.php';

/**
 * Activation: create the submissions table.
 */
register_activation_hook(__FILE__, 'vud_activate_plugin');
function vud_activate_plugin() {
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
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);

    // Sensible defaults so the plugin isn't fully blank on first activation.
    if (get_option('vud_test_mode', null) === null) {
        update_option('vud_test_mode', 1); // Default to test mode until the admin turns it off deliberately.
    }
}
