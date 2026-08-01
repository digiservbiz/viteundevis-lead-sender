<?php
if (!defined('ABSPATH')) exit;

/**
 * [vud_category_page_body]
 *
 * Renders the full body of a generated category landing page: hero,
 * intro text, the lead form (already locked to the right category), a
 * trust/how-it-works block, FAQ, and related-category links — all pulled
 * from the CURRENT page automatically (title, _vud_generated_cat_id,
 * vud_intro_text, vud_faq_text).
 *
 * Meant to be pasted once into a Divi Theme Builder Custom Body (in a
 * Code or Text module) and assigned to all generated category pages —
 * no per-page Divi setup needed, no reliance on Divi's Dynamic Content
 * picker discovering custom fields. Because it reads get_the_ID() at
 * render time, it produces different content on every page even though
 * the template itself is shared.
 *
 * Include this ONCE in the template body. Don't also add a separate
 * page-title/hero module elsewhere in the same template — this shortcode
 * already renders its own <h1>.
 */
add_shortcode('vud_category_page_body', 'vud_render_category_page_body');
function vud_render_category_page_body() {
    $post_id = get_the_ID();
    if (!$post_id) return '';

    $cat_id = get_post_meta($post_id, '_vud_generated_cat_id', true);
    if (!$cat_id) {
        // Not a generated category page — render nothing rather than a broken block.
        return '';
    }

    $categories = vud_get_categories();
    $cat_name   = isset($categories[$cat_id]) ? $categories[$cat_id] : '';
    $title      = get_the_title($post_id);
    $intro      = get_post_meta($post_id, 'vud_intro_text', true);
    $faq_raw    = get_post_meta($post_id, 'vud_faq_text', true);

    $has_real_intro = $intro && strpos($intro, 'À COMPLÉTER') === false;
    $has_real_faq   = $faq_raw && strpos($faq_raw, 'À COMPLÉTER') === false;

    ob_start();
    ?>
    <div class="vud-page-body">
        <section class="vud-hero">
            <h1><?php echo esc_html($title); ?></h1>
            <p class="vud-hero-sub">En quelques minutes, recevez jusqu'à 3 devis gratuits et comparez sans engagement.</p>
        </section>

        <?php if ($has_real_intro): ?>
        <section class="vud-intro">
            <?php echo wpautop(esc_html($intro)); ?>
        </section>
        <?php endif; ?>

        <section class="vud-form-wrap">
            <?php echo do_shortcode('[vud_lead_form cat_id="' . esc_attr($cat_id) . '"]'); ?>
        </section>

        <section class="vud-how-it-works">
            <h2>Comment ça marche</h2>
            <div class="vud-steps">
                <div class="vud-step-card">
                    <div class="vud-step-num">1</div>
                    <h3>Remplissez le formulaire</h3>
                    <p>Décrivez votre projet en quelques minutes.</p>
                </div>
                <div class="vud-step-card">
                    <div class="vud-step-num">2</div>
                    <h3>Recevez des devis</h3>
                    <p>Des professionnels qualifiés vous contactent.</p>
                </div>
                <div class="vud-step-card">
                    <div class="vud-step-num">3</div>
                    <h3>Comparez et choisissez</h3>
                    <p>Sans engagement, gratuitement.</p>
                </div>
            </div>
        </section>

        <?php if ($has_real_faq): ?>
        <section class="vud-faq">
            <h2>Questions fréquentes</h2>
            <?php echo vud_render_faq_html($faq_raw); ?>
        </section>
        <?php endif; ?>

        <?php echo vud_render_related_categories_html($post_id, $cat_id); ?>
    </div>

    <style>
        .vud-page-body { max-width: 800px; margin: 0 auto; padding: 20px; }
        .vud-hero { text-align: center; margin-bottom: 32px; }
        .vud-hero h1 { font-size: 32px; margin-bottom: 8px; }
        .vud-hero-sub { font-size: 17px; color: #555; }
        .vud-intro { margin-bottom: 32px; line-height: 1.7; }
        .vud-form-wrap { margin-bottom: 40px; }
        .vud-how-it-works { text-align: center; margin-bottom: 40px; }
        .vud-how-it-works h2 { margin-bottom: 20px; }
        .vud-steps { display: flex; gap: 16px; flex-wrap: wrap; justify-content: center; }
        .vud-step-card { flex: 1; min-width: 180px; background: #f6f7f7; border-radius: 8px; padding: 20px; }
        .vud-step-num { width: 32px; height: 32px; border-radius: 50%; background: #0073aa; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 600; margin: 0 auto 10px; }
        .vud-step-card h3 { font-size: 16px; margin: 0 0 6px; }
        .vud-step-card p { font-size: 14px; color: #666; margin: 0; }
        .vud-faq { margin-bottom: 40px; }
        .vud-faq h2 { margin-bottom: 16px; }
        .vud-faq-item { border-bottom: 1px solid #e2e6e9; padding: 14px 0; }
        .vud-faq-item summary { font-weight: 600; cursor: pointer; }
        .vud-faq-item p { margin: 10px 0 0; color: #555; }
        .vud-related { margin-top: 20px; }
        .vud-related h2 { margin-bottom: 12px; font-size: 18px; }
        .vud-related-links { display: flex; flex-wrap: wrap; gap: 8px; }
        .vud-related-links a { display: inline-block; background: #f0f6fa; color: #0073aa; padding: 6px 14px; border-radius: 20px; text-decoration: none; font-size: 14px; }
        .vud-related-links a:hover { background: #dceaf3; }
    </style>
    <?php
    return ob_get_clean();
}

/**
 * Parses "Q : ...\nR : ..." pairs from the FAQ textarea into simple,
 * dependency-free <details>/<summary> accordion items.
 */
function vud_render_faq_html($raw) {
    $blocks = preg_split('/\n\s*\n/', trim($raw));
    $html = '';
    foreach ($blocks as $block) {
        if (stripos($block, 'Q') !== 0 && stripos($block, 'Q :') === false && stripos($block, 'Q:') === false) continue;
        $q = '';
        $a = '';
        foreach (explode("\n", $block) as $line) {
            $line = trim($line);
            if (preg_match('/^Q\s*:\s*(.+)/i', $line, $m)) {
                $q = $m[1];
            } elseif (preg_match('/^R\s*:\s*(.+)/i', $line, $m)) {
                $a = $m[1];
            }
        }
        if ($q && $a) {
            $html .= '<details class="vud-faq-item"><summary>' . esc_html($q) . '</summary><p>' . esc_html($a) . '</p></details>';
        }
    }
    return $html;
}

/**
 * A handful of links to other generated category pages, for internal
 * linking. Picks a small random sample each time (varies naturally page
 * to page rather than a static identical block everywhere).
 */
function vud_render_related_categories_html($current_post_id, $current_cat_id) {
    $others = get_posts(array(
        'post_type'      => 'page',
        'post_status'    => 'publish',
        'meta_key'       => '_vud_generated_cat_id',
        'exclude'        => array($current_post_id),
        'numberposts'    => 6,
        'orderby'        => 'rand',
    ));
    if (empty($others)) return '';

    $html = '<section class="vud-related"><h2>Autres catégories de devis</h2><div class="vud-related-links">';
    foreach ($others as $p) {
        $html .= '<a href="' . esc_url(get_permalink($p)) . '">' . esc_html(get_the_title($p)) . '</a>';
    }
    $html .= '</div></section>';
    return $html;
}
