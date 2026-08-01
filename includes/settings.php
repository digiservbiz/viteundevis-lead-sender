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
    register_setting('vud_settings_group', 'vud_construction_cat_ids', 'vud_sanitize_construction_ids');
    register_setting('vud_settings_group', 'vud_consent_text', 'sanitize_textarea_field');
    register_setting('vud_settings_group', 'vud_privacy_url', 'esc_url_raw');
    register_setting('vud_settings_group', 'vud_notify_email', 'absint');
    register_setting('vud_settings_group', 'vud_notify_email_address', 'sanitize_email');
    register_setting('vud_settings_group', 'vud_webhook_url', 'esc_url_raw');

    add_settings_section('vud_main', 'Paramètres API', null, 'vud-settings');
    add_settings_field('vud_api_key', 'Clé API ViteUnDevis', 'vud_field_api_key', 'vud-settings', 'vud_main');
    add_settings_field('vud_test_mode', 'Mode test', 'vud_field_test_mode', 'vud-settings', 'vud_main');
    add_settings_field('vud_redirect_url', 'URL de redirection (merci)', 'vud_field_redirect', 'vud-settings', 'vud_main');
    add_settings_field('vud_callback_url', 'URL de callback (optionnel)', 'vud_field_callback', 'vud-settings', 'vud_main');
    add_settings_field('vud_site_name', 'Nom du site (site_name)', 'vud_field_site_name', 'vud-settings', 'vud_main');

    add_settings_section('vud_categories', 'Catégories de devis', 'vud_categories_help', 'vud-settings');
    add_settings_field('vud_categories_raw', 'Liste des catégories', 'vud_field_categories', 'vud-settings', 'vud_categories');
    add_settings_field('vud_construction_cat_ids', 'Catégories "construction"', 'vud_field_construction_ids', 'vud-settings', 'vud_categories');

    add_settings_section('vud_consent', 'Consentement au démarchage téléphonique (obligatoire depuis l\'API v1.6)', 'vud_consent_help', 'vud-settings');
    add_settings_field('vud_consent_text', 'Texte affiché à côté de la case à cocher', 'vud_field_consent_text', 'vud-settings', 'vud_consent');
    add_settings_field('vud_privacy_url', 'URL de la politique de confidentialité', 'vud_field_privacy_url', 'vud-settings', 'vud_consent');

    add_settings_section('vud_crm', 'Notifications & CRM', 'vud_crm_help', 'vud-settings');
    add_settings_field('vud_notify_email', 'Notification par email', 'vud_field_notify_email', 'vud-settings', 'vud_crm');
    add_settings_field('vud_notify_email_address', 'Adresse de notification', 'vud_field_notify_email_address', 'vud-settings', 'vud_crm');
    add_settings_field('vud_webhook_url', 'URL Webhook / CRM', 'vud_field_webhook_url', 'vud-settings', 'vud_crm');

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
    $val = get_option('vud_categories_raw', "46|Abattage d\'arbres\n91|Abris de jardin\n107|Abris de piscine\n154|Adoucisseur d\'eau\n33|Alarme\n34|Alarme incendie\n138|Allées et chemins\n121|Aménagement de placard\n75|Aménagement des combles\n32|Antenne TV\n1|Architecte - construction de maison\n78|Architecture d\'intèrieur\n48|Arrosage automatique\n134|Ascenseur\n21|Aspiration centralisée\n172|Assurance emprunteur\n56|Audit d\'amiante / plomb\n109|Automatisme d\'éclairage\n155|Baignoire à porte\n102|Bardage\n127|Béton ciré\n25|Carrelage\n146|Changement de vitre\n8|Charpente\n64|Chaudière bois - granulés\n88|Chaudière fioul\n65|Chaudière gaz\n156|Chauffage\n93|Chauffage géothermique\n86|Chauffage solaire\n15|Chauffage électrique\n139|Chauffe-eau\n42|Chauffe-eau solaire\n104|Chauffe-eau thermodynamique\n130|Chemin d\'accès\n18|Cheminée\n19|Climatisation\n11|Cloison\n94|Clôture\n4|Constructeur de maisons\n6|Construction\n132|Construction garage\n80|Couverture\n76|Création dressing\n22|Cuisine\n126|Câblage informatique\n148|DPE\n173|Dommage ouvrage\n69|Domotique\n160|Douche sénior\n131|Douche à l\'italienne\n31|Décorateur\n81|Décrassage ou démoussage de toiture\n123|Démolition\n145|Déménagement\n171|Dépannage pompe à chaleur / climatisation\n122|Ebéniste\n125|Eclairage\n90|Elagage - taille d\'arbre\n13|Electricité (Travaux électriques)\n97|Enrobée\n87|Entretien chaudière\n54|Entretien jardin\n55|Entretien piscine\n162|Entretien pompe à chaleur\n117|Equipement piscine\n16|Escalier\n83|Etanchéité toit terrasse\n163|Etude de sol\n105|Etude thermique\n124|Expert en bâtiment\n5|Extension de maison\n62|Facades - enduits\n77|Faux plafonds - plafonds tendus\n72|Fenêtre\n118|Fondations\n115|Garde corps\n151|Gazon\n150|Géomètre\n129|Home cinéma\n73|Interphone\n12|Isolation\n153|Isolation des combles\n103|Isolation par l\'exterieur\n157|Isolation phonique\n111|Lambris\n112|Linos\n60|Maison bois\n7|Maçonnerie\n2|Maître d\'oeuvre\n10|Menuiserie\n120|Mezzanine\n106|Micro station d\'épuration\n144|Monte escalier\n113|Moquette\n50|Motorisation pour fermeture de portes et portails\n37|Panneaux photovoltaïques\n26|Parquet\n47|Paysagiste\n28|Peinture\n133|Pergola - carport\n3|Permis de construire\n41|Petites éoliennes\n44|Piscine\n63|Piscine coque\n159|Piscine en dur\n92|Piscine en kit\n79|Plan de maison\n89|Plancher chauffant (eau chaude)\n68|Plancher chauffant rayonnant\n14|Plomberie\n70|Poele\n36|Pompe à chaleur\n40|Pompe à chaleur air/air\n158|Pompe à chaleur air/eau\n71|Portail\n96|Porte blindée\n128|Porte d\'entrée\n108|Porte de garage\n137|Portes intérieures\n164|Pose de borne de recharge\n84|Pose de gouttières\n167|Pose de prise de recharge\n169|Punaise de lit\n136|Ragréage\n53|Ramonage\n43|Récupération des eaux de pluie\n66|Rénovation\n20|Rénovation intérieure\n170|Rénovation énergétique\n149|SPA\n23|Salles de bains\n165|Serrurerie\n95|Store banne\n116|Sur élévation de toiture\n29|Tapisserie - Papier peint\n52|Termites\n142|Terrasse bois\n141|Terrasse béton\n9|Terrassement\n49|Terrasses\n143|Toiture\n114|Traitement contre l\'humidité\n161|Travaux d\'architecture\n110|Télésurveillance - vidéosurveillance\n98|VMC\n61|VRD / Fosse septique\n166|Velux - fenêtre de toit\n135|Verrière - cloison atelier\n168|Vidange fosse septique\n74|Visiophone (fourniture et pose)\n147|Volet roulant\n45|Véranda\n24|WC\n152|chauffage piscine");
    echo '<textarea name="vud_categories_raw" rows="12" class="large-text code" placeholder="121|Aménagement de placard">' . esc_textarea($val) . '</textarea>';
    echo '<p class="description">Liste complète fournie par ViteUnDevis (155 catégories). Mettez à jour ici si ViteUnDevis vous envoie un CSV plus récent.</p>';
}

function vud_field_construction_ids() {
    $val = get_option('vud_construction_cat_ids', "1,2,4,6,60");
    echo '<input type="text" name="vud_construction_cat_ids" value="' . esc_attr($val) . '" class="regular-text">';
    echo '<p class="description">IDs des catégories marquées "Catégorie Construction" dans le CSV ViteUnDevis, séparés par des virgules. Pour ces catégories, cp_projet et ville_projet deviennent obligatoires sur le formulaire (l\'API les exige dans ce cas).</p>';
}

function vud_sanitize_construction_ids($input) {
    $ids = array_filter(array_map('intval', explode(',', (string) $input)));
    return implode(',', $ids);
}

function vud_get_construction_category_ids() {
    $raw = get_option('vud_construction_cat_ids', "1,2,4,6,60");
    return array_filter(array_map('intval', explode(',', $raw)));
}

function vud_consent_help() {
    echo '<p>Depuis le 11 août 2026, tout devis comportant un numéro de téléphone doit être accompagné d\'une preuve de consentement (loi n° 2025-594). ';
    echo 'Le texte ci-dessous doit être <strong>exact, mot pour mot</strong> — c\'est celui qui sera à la fois affiché sur le formulaire et transmis à ViteUnDevis comme preuve (<code>consent_texte</code>). ';
    echo 'Il doit identifier clairement qui contactera le dépositaire.</p>';
}

function vud_field_consent_text() {
    $val = vud_get_consent_text();
    echo '<textarea name="vud_consent_text" rows="3" class="large-text">' . esc_textarea($val) . '</textarea>';
}

function vud_field_privacy_url() {
    $val = get_option('vud_privacy_url', '');
    echo '<input type="url" name="vud_privacy_url" value="' . esc_attr($val) . '" class="regular-text" placeholder="https://votresite.com/confidentialite/">';
    echo '<p class="description">Un lien vers cette page sera affiché à côté de la case à cocher (accès facile requis par la loi).</p>';
}

/**
 * The single source of truth for the consent checkbox text — used both when
 * rendering the form and when building the consent_texte sent to the API,
 * so the two can never drift out of sync.
 */
function vud_get_consent_text() {
    $default = "J'accepte d'être contacté(e) par téléphone par " . (get_option('vud_site_name') ?: get_bloginfo('name')) . " et ses partenaires afin de qualifier ma demande de devis.";
    return get_option('vud_consent_text', $default) ?: $default;
}

function vud_crm_help() {
    echo '<p>Chaque demande est <strong>toujours</strong> enregistrée dans la base WordPress (page Demandes), quel que soit le résultat de l\'envoi à ViteUnDevis — c\'est votre copie de secours locale.</p>';
    echo '<p>Vous pouvez en plus recevoir une copie par email et/ou envoyer chaque lead vers un CRM ou un outil d\'automatisation (Zapier, Make, n8n, HubSpot, Pipedrive...) via une URL de webhook — pratique en attendant de choisir un CRM définitif.</p>';
}

function vud_field_notify_email() {
    $val = get_option('vud_notify_email', 0);
    echo '<label><input type="checkbox" name="vud_notify_email" value="1" ' . checked(1, $val, false) . '> Envoyer un email à chaque nouvelle demande (succès, test, ou erreur)</label>';
}

function vud_field_notify_email_address() {
    $val = get_option('vud_notify_email_address', get_bloginfo('admin_email'));
    echo '<input type="email" name="vud_notify_email_address" value="' . esc_attr($val) . '" class="regular-text">';
}

function vud_field_webhook_url() {
    $val = get_option('vud_webhook_url', '');
    echo '<input type="url" name="vud_webhook_url" value="' . esc_attr($val) . '" class="regular-text" placeholder="https://hooks.zapier.com/... ou https://votrecrm.com/api/leads">';
    echo '<p class="description">Si renseignée, chaque demande (succès, test, ou erreur) est envoyée en POST JSON à cette URL, en plus de l\'enregistrement local. Laisser vide pour désactiver.</p>';
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
        <div class="vud-shortcode-box" style="background:#fff;border:1px solid #dcdcde;border-left:4px solid #0073aa;padding:12px 16px;margin:16px 0;display:flex;align-items:center;gap:12px;">
            <strong>Shortcode à utiliser&nbsp;:</strong>
            <code id="vud-shortcode-value" style="background:#f0f0f1;padding:4px 10px;border-radius:3px;">[vud_lead_form]</code>
            <button type="button" class="button" onclick="navigator.clipboard.writeText('[vud_lead_form]');this.textContent='Copié ✓';setTimeout(()=>this.textContent='Copier',1500);">Copier</button>
            <span style="color:#666;">Collez-le dans n'importe quelle page ou article.</span>
        </div>
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
