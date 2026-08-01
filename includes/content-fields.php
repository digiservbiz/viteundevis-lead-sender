<?php
if (!defined('ABSPATH')) exit;

/**
 * Registers the two per-page content fields as proper post meta (not just
 * ad-hoc get_post_meta calls) so page builders' Dynamic Content pickers —
 * including Divi 5 — can discover and bind to them.
 */
add_action('init', 'vud_register_content_meta');
function vud_register_content_meta() {
    $args = array(
        'show_in_rest'  => true,
        'single'        => true,
        'type'          => 'string',
        'auth_callback' => function () { return current_user_can('edit_pages'); },
    );
    register_post_meta('page', 'vud_intro_text', $args);
    register_post_meta('page', 'vud_faq_text', $args);
}

add_action('admin_menu', 'vud_content_editor_menu');
function vud_content_editor_menu() {
    add_submenu_page('vud-dashboard', 'Contenu des pages', 'Contenu des pages', 'manage_options', 'vud-content-editor', 'vud_content_editor_page_html');
}

add_action('admin_post_vud_save_content', 'vud_handle_save_content');
function vud_handle_save_content() {
    if (!current_user_can('manage_options') || !isset($_POST['vud_content_nonce']) || !wp_verify_nonce($_POST['vud_content_nonce'], 'vud_save_content')) {
        wp_die('Accès refusé.');
    }

    $post_id = isset($_POST['post_id']) ? absint($_POST['post_id']) : 0;
    if ($post_id && get_post_type($post_id) === 'page') {
        update_post_meta($post_id, 'vud_intro_text', isset($_POST['vud_intro_text']) ? sanitize_textarea_field(wp_unslash($_POST['vud_intro_text'])) : '');
        update_post_meta($post_id, 'vud_faq_text', isset($_POST['vud_faq_text']) ? sanitize_textarea_field(wp_unslash($_POST['vud_faq_text'])) : '');
    }

    wp_safe_redirect(admin_url('admin.php?page=vud-content-editor&saved=' . $post_id));
    exit;
}

function vud_content_editor_page_html() {
    if (!current_user_can('manage_options')) return;

    $pages = get_posts(array(
        'post_type'   => 'page',
        'meta_key'    => '_vud_generated_cat_id',
        'post_status' => array('publish', 'draft', 'pending'),
        'numberposts' => -1,
        'orderby'     => 'title',
        'order'       => 'ASC',
    ));

    $editing_id = isset($_GET['edit']) ? absint($_GET['edit']) : 0;

    if ($editing_id) {
        $post = get_post($editing_id);
        if (!$post || $post->post_type !== 'page') { $editing_id = 0; $post = null; }
    }
    ?>
    <div class="wrap">
        <h1>Contenu des pages générées</h1>
        <p>Rédigez ici l'introduction et la FAQ de chaque page — pas besoin d'ouvrir Divi pour ça. Une fois écrit, ce contenu apparaît automatiquement dans votre template Divi Theme Builder via Dynamic Content (champs personnalisés <code>vud_intro_text</code> et <code>vud_faq_text</code>).</p>

        <?php if (!empty($_GET['saved'])): ?>
            <div class="notice notice-success"><p>Contenu enregistré.</p></div>
        <?php endif; ?>

        <?php if ($editing_id && $post): ?>
            <?php
            $intro = get_post_meta($editing_id, 'vud_intro_text', true);
            $faq   = get_post_meta($editing_id, 'vud_faq_text', true);
            ?>
            <h2><?php echo esc_html($post->post_title); ?></h2>
            <p><a href="<?php echo esc_url(get_edit_post_link($editing_id)); ?>">Modifier la page</a> · <a href="<?php echo esc_url(get_permalink($editing_id)); ?>">Voir la page</a></p>
            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <?php wp_nonce_field('vud_save_content', 'vud_content_nonce'); ?>
                <input type="hidden" name="action" value="vud_save_content">
                <input type="hidden" name="post_id" value="<?php echo esc_attr($editing_id); ?>">

                <h3>Introduction (150 à 300 mots)</h3>
                <textarea name="vud_intro_text" rows="8" class="large-text"><?php echo esc_textarea($intro); ?></textarea>

                <h3>FAQ</h3>
                <textarea name="vud_faq_text" rows="10" class="large-text"><?php echo esc_textarea($faq); ?></textarea>

                <?php submit_button('Enregistrer'); ?>
            </form>
            <p><a href="<?php echo esc_url(admin_url('admin.php?page=vud-content-editor')); ?>">&larr; Retour à la liste</a></p>

        <?php else: ?>
            <table class="wp-list-table widefat fixed striped" style="margin-top:16px;">
                <thead><tr><th>Page</th><th>Statut</th><th>Intro</th><th>FAQ</th><th></th></tr></thead>
                <tbody>
                <?php if (empty($pages)): ?>
                    <tr><td colspan="5">Aucune page générée pour le moment. Utilisez "Générer les pages" d'abord.</td></tr>
                <?php else: foreach ($pages as $p):
                    $intro = get_post_meta($p->ID, 'vud_intro_text', true);
                    $faq   = get_post_meta($p->ID, 'vud_faq_text', true);
                    $intro_done = $intro && strpos($intro, 'À COMPLÉTER') === false;
                    $faq_done   = $faq && strpos($faq, 'À COMPLÉTER') === false;
                    ?>
                    <tr>
                        <td><?php echo esc_html($p->post_title); ?></td>
                        <td><?php echo esc_html(ucfirst($p->post_status)); ?></td>
                        <td><?php echo $intro_done ? '✅' : '⏳ à écrire'; ?></td>
                        <td><?php echo $faq_done ? '✅' : '⏳ à écrire'; ?></td>
                        <td><a href="<?php echo esc_url(admin_url('admin.php?page=vud-content-editor&edit=' . $p->ID)); ?>" class="button">Rédiger</a></td>
                    </tr>
                <?php endforeach; endif; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    <?php
}
