<?php
if (!defined('ABSPATH')) exit;

/**
 * Fields that CAN be shown/hidden on the frontend form.
 * (Fields the API always requires — nom, prenom, email, tel, adresse1, cp, ville,
 * tp, delais, cat_id, type_bien, situation, description — are always shown and
 * are excluded from this list.)
 */
function vud_optional_fields() {
    return array(
        'societe'      => 'Société',
        'adresse2'     => "Complément d'adresse",
        'mobile'       => 'Mobile',
        'cp_projet'    => 'Code postal du projet',
        'ville_projet' => 'Ville du projet',
        'pays'         => 'Pays',
        'surface'      => 'Surface',
        'budget'       => 'Budget',
        'terrain'      => 'Possède un terrain',
        'permis'       => 'Permis de construire',
        'matin'        => 'Disponible le matin',
        'midi'         => 'Disponible le midi',
        'soir'         => 'Disponible le soir',
        'we'           => 'Disponible le week-end',
    );
}

function vud_register_settings() {
    register_setting('vud_settings_group', 'vud_api_key', 'sanitize_text_field');
    register_setting('vud_settings_group', 'vud_redirect_url', 'esc_url_raw');
    register_setting('vud_settings_group', 'vud_callback_url', 'esc_url_raw');
    register_setting('vud_settings_group', 'vud_site_name', 'sanitize_text_field');
    register_setting('vud_settings_group', 'vud_test_mode', 'absint');
    register_setting('vud_settings_group', 'vud_settings', 'vud_sanitize_visibility');
    register_setting('vud_settings_group', 'vud_categories_raw', 'vud_sanitize_categories');

    add_settings_section('vud_main', 'Paramètres API', null, 'vud-settings');
    add_settings_field('vud_api_key', 'Clé API ViteUnDevis', 'vud_field_api_key', 'vud-settings', 'vud_main');
    add_settings_field('vud_test_mode', 'Mode test', 'vud_field_test_mode', 'vud-settings', 'vud_main');
    add_settings_field('vud_redirect_url', 'URL de redirection (merci)', 'vud_field_redirect', 'vud-settings', 'vud_main');
    add_settings_field('vud_callback_url', 'URL de callback (optionnel)', 'vud_field_callback', 'vud-settings', 'vud_main');
    add_settings_field('vud_site_name', 'Nom du site (site_name)', 'vud_field_site_name', 'vud-settings', 'vud_main');

    add_settings_section('vud_categories', 'Catégories de devis', 'vud_categories_help', 'vud-settings');
    add_settings_field('vud_categories_raw', 'Liste des catégories', 'vud_field_categories', 'vud-settings', 'vud_categories');

    add_settings_section('vud_visibility', 'Champs optionnels affichés sur le formulaire', 'vud_visibility_help', 'vud-settings');
    foreach (vud_optional_fields() as $key => $label) {
        add_settings_field('field_' . $key, $label, 'vud_visibility_field', 'vud-settings', 'vud_visibility', array('field' => $key));
    }
}
add_action('admin_init', 'vud_register_settings');

function vud_sanitize_visibility($input) {
    $clean = array();
    if (is_array($input)) {
        foreach ($input as $k => $v) {
            $clean[sanitize_key($k)] = 1;
        }
    }
    return $clean;
}

/**
 * Categories are stored as raw admin-entered text, one per line: id|Nom de la catégorie
 * This keeps the list fully admin-editable instead of hardcoding it in code,
 * since ViteUnDevis maintains the authoritative list as a separate CSV.
 */
function vud_sanitize_categories($input) {
    return is_string($input) ? trim($input) : '';
}

function vud_field_api_key() {
    $val = get_option('vud_api_key', '');
    echo '<input type="text" name="vud_api_key" value="' . esc_attr($val) . '" class="regular-text" required>';
    echo '<p class="description">Votre clé d\'API fournie par ViteUnDevis / ForumConstruire.</p>';
}

function vud_field_test_mode() {
    $val = get_option('vud_test_mode', 1);
    echo '<label><input type="checkbox" name="vud_test_mode" value="1" ' . checked(1, $val, false) . '> ';
    echo 'Envoyer les leads vers l\'URL de test (aucune entrée en base ViteUnDevis)</label>';
}

function vud_field_redirect() {
    $val = get_option('vud_redirect_url', '');
    echo '<input type="url" name="vud_redirect_url" value="' . esc_attr($val) . '" class="regular-text" placeholder="https://votresite.com/merci/">';
    echo '<p class="description">Laisser vide pour rester sur la page du formulaire avec un message de succès.</p>';
}

function vud_field_callback() {
    $val = get_option('vud_callback_url', '');
    echo '<input type="url" name="vud_callback_url" value="' . esc_attr($val) . '" class="regular-text">';
}

function vud_field_site_name() {
    $val = get_option('vud_site_name', '');
    echo '<input type="text" name="vud_site_name" value="' . esc_attr($val) . '" class="regular-text" placeholder="votresite.com">';
}

function vud_categories_help() {
    echo '<p>Une catégorie par ligne, au format <code>id|Nom de la catégorie</code>. Exemple : <code>96|Porte blindée</code>.<br>';
    echo 'ViteUnDevis fournit un CSV complet des catégories (avec la colonne indiquant si une catégorie est une "catégorie construction", qui rend cp_projet/ville_projet obligatoires) — collez son contenu ici, converti au format ci-dessus.</p>';
}

function vud_field_categories() {
    $val = get_option('vud_categories_raw', "121|Aménagement de placard\n46|Abattage d'arbres\n91|Abris de jardin\n107|Abris de piscine\n154|Adoucisseur d'eau\n33|Alarme\n1|Architecte - construction de maison\n96|Porte blindée\n24|WC");
    echo '<textarea name="vud_categories_raw" rows="12" class="large-text code" placeholder="121|Aménagement de placard">' . esc_textarea($val) . '</textarea>';
    echo '<p class="description">Liste de démarrage fournie à titre d\'exemple seulement — remplacez-la par la liste complète de ViteUnDevis avant de passer en production.</p>';
}

function vud_visibility_help() {
    echo '<p>Les champs obligatoires selon l\'API (nom, prénom, email, téléphone, adresse, code postal, ville, type de personne, délais, catégorie, type de bien, situation, description) sont toujours affichés. Cochez ici les champs optionnels à afficher également.</p>';
}

function vud_visibility_field($args) {
    $field    = $args['field'];
    $settings = get_option('vud_settings', array());
    $checked  = !empty($settings[$field]) ? 'checked' : '';
    echo '<input type="checkbox" name="vud_settings[' . esc_attr($field) . ']" value="1" ' . $checked . '>';
}

function vud_settings_page_html() {
    if (!current_user_can('manage_options')) return;
    ?>
    <div class="wrap">
        <h1>Paramètres ViteUnDevis</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('vud_settings_group');
            do_settings_sections('vud-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

/**
 * Parses the admin-entered category text into an id => name array.
 */
function vud_get_categories() {
    $raw = get_option('vud_categories_raw', '');
    $out = array();
    foreach (preg_split('/\r\n|\r|\n/', (string) $raw) as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, '|') === false) continue;
        list($id, $name) = array_map('trim', explode('|', $line, 2));
        if ($id !== '' && $name !== '') {
            $out[(int) $id] = sanitize_text_field($name);
        }
    }
    return $out;
}
