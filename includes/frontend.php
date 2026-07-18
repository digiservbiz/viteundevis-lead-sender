<?php
if (!defined('ABSPATH')) exit;

function vud_enqueue_frontend() {
    wp_enqueue_style('vud-frontend', VUD_URL . 'assets/css/frontend-style.css', array(), VUD_VERSION);
}
add_action('wp_enqueue_scripts', 'vud_enqueue_frontend');

/**
 * Shortcode: [vud_lead_form]
 */
function vud_lead_form_shortcode() {
    $api_key = get_option('vud_api_key', '');
    if (empty($api_key)) {
        return current_user_can('manage_options')
            ? '<p>ViteUnDevis&nbsp;: clé API non configurée. <a href="' . esc_url(admin_url('admin.php?page=vud-settings')) . '">Configurer maintenant</a>.</p>'
            : '';
    }

    $visible = get_option('vud_settings', array());
    $show    = function ($field) use ($visible) { return !empty($visible[$field]); };
    $categories = vud_get_categories();

    // Success / error messages passed back after a redirect from the handler.
    $notice_html = '';
    if (!empty($_GET['vud_notice'])) {
        $data = get_transient('vud_notice_' . sanitize_key(wp_unslash($_GET['vud_notice'])));
        if ($data) {
            $type = $data['type'] === 'success' ? 'vud-notice-success' : 'vud-notice-error';
            $notice_html .= '<div class="vud-notice ' . $type . '">';
            foreach ((array) $data['messages'] as $m) {
                $notice_html .= '<p>' . esc_html($m) . '</p>';
            }
            $notice_html .= '</div>';
        }
    }

    // If the last submission for this form on this page succeeded and no redirect URL
    // is configured, hide the form and just show the thank-you message.
    $hide_form = (strpos($notice_html, 'vud-notice-success') !== false && empty(get_option('vud_redirect_url', '')));

    ob_start(); ?>
    <?php echo $notice_html; ?>
    <?php if (!$hide_form): ?>
    <form id="vud-lead-form" class="vud-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <?php wp_nonce_field('vud_submit_lead', 'vud_nonce'); ?>
        <input type="hidden" name="action" value="vud_submit_lead">
        <input type="hidden" name="vud_redirect_back" value="<?php echo esc_url(get_permalink() ?: home_url('/')); ?>">

        <div class="form-group">
            <label for="vud_nom">Nom *</label>
            <input type="text" id="vud_nom" name="nom" required>
        </div>

        <div class="form-group">
            <label for="vud_prenom">Prénom *</label>
            <input type="text" id="vud_prenom" name="prenom" required>
        </div>

        <?php if ($show('societe')): ?>
        <div class="form-group">
            <label for="vud_societe">Société</label>
            <input type="text" id="vud_societe" name="societe">
        </div>
        <?php endif; ?>

        <div class="form-group">
            <label for="vud_email">Email *</label>
            <input type="email" id="vud_email" name="email" required>
        </div>

        <div class="form-group">
            <label for="vud_tel">Téléphone <span class="vud-hint">(fixe ou mobile obligatoire)</span></label>
            <input type="tel" id="vud_tel" name="tel">
        </div>

        <?php if ($show('mobile')): ?>
        <div class="form-group">
            <label for="vud_mobile">Mobile</label>
            <input type="tel" id="vud_mobile" name="mobile">
        </div>
        <?php endif; ?>

        <div class="form-group">
            <label for="vud_adresse1">Adresse *</label>
            <input type="text" id="vud_adresse1" name="adresse1" required>
        </div>

        <?php if ($show('adresse2')): ?>
        <div class="form-group">
            <label for="vud_adresse2">Complément d'adresse</label>
            <input type="text" id="vud_adresse2" name="adresse2">
        </div>
        <?php endif; ?>

        <div class="form-group form-row">
            <div>
                <label for="vud_cp">Code postal *</label>
                <input type="text" id="vud_cp" name="cp" maxlength="5" required>
            </div>
            <div>
                <label for="vud_ville">Ville *</label>
                <input type="text" id="vud_ville" name="ville" required>
            </div>
        </div>

        <?php if ($show('cp_projet') || $show('ville_projet')): ?>
        <div class="form-group form-row">
            <?php if ($show('cp_projet')): ?>
            <div>
                <label for="vud_cp_projet">Code postal du projet</label>
                <input type="text" id="vud_cp_projet" name="cp_projet" maxlength="5">
            </div>
            <?php endif; ?>
            <?php if ($show('ville_projet')): ?>
            <div>
                <label for="vud_ville_projet">Ville du projet</label>
                <input type="text" id="vud_ville_projet" name="ville_projet">
            </div>
            <?php endif; ?>
        </div>
        <p class="vud-hint">Obligatoire uniquement pour les catégories de construction.</p>
        <?php endif; ?>

        <?php if ($show('pays')): ?>
        <div class="form-group">
            <label for="vud_pays">Pays (code ISO, ex: FR)</label>
            <input type="text" id="vud_pays" name="pays" maxlength="2" placeholder="FR">
        </div>
        <?php endif; ?>

        <div class="form-group">
            <label for="vud_cat_id">Type de projet *</label>
            <select id="vud_cat_id" name="cat_id" required>
                <option value="">-- Sélectionner --</option>
                <?php foreach ($categories as $id => $name): ?>
                    <option value="<?php echo esc_attr($id); ?>"><?php echo esc_html($name); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="vud_type_bien">Type de bien *</label>
            <select id="vud_type_bien" name="type_bien" required>
                <option value="">-- Sélectionner --</option>
                <option value="1">Appartement</option>
                <option value="2">Maison</option>
                <option value="3">Immeuble</option>
                <option value="4">Bureau</option>
                <option value="5">Terrain</option>
                <option value="6">Autre</option>
            </select>
        </div>

        <div class="form-group">
            <label for="vud_situation">Vous êtes *</label>
            <select id="vud_situation" name="situation" required>
                <option value="">-- Sélectionner --</option>
                <option value="1">Propriétaire / Futur propriétaire</option>
                <option value="2">Locataire / Futur locataire</option>
                <option value="3">Administrateur</option>
                <option value="4">Autre</option>
            </select>
        </div>

        <div class="form-group">
            <label for="vud_tp">Type de personne *</label>
            <select id="vud_tp" name="tp" required>
                <option value="">-- Sélectionner --</option>
                <option value="1">Particulier</option>
                <option value="2">Professionnel</option>
                <option value="3">Syndicat de co-propriété</option>
                <option value="4">Autre</option>
            </select>
        </div>

        <div class="form-group">
            <label for="vud_delais">Délais du projet *</label>
            <select id="vud_delais" name="delais" required>
                <option value="">-- Sélectionner --</option>
                <option value="1">Urgent</option>
                <option value="2">Dans les 6 mois</option>
                <option value="3">Dans l'année</option>
                <option value="4">Dans plus d'un an</option>
            </select>
        </div>

        <?php if ($show('permis')): ?>
        <div class="form-group">
            <label for="vud_permis">Permis de construire</label>
            <select id="vud_permis" name="permis">
                <option value="3">Non</option>
                <option value="1">Oui, accepté</option>
                <option value="2">Oui, en attente</option>
            </select>
        </div>
        <?php endif; ?>

        <?php if ($show('terrain')): ?>
        <div class="form-group">
            <label><input type="checkbox" name="terrain" value="1"> Je possède déjà un terrain</label>
        </div>
        <?php endif; ?>

        <?php if ($show('surface')): ?>
        <div class="form-group">
            <label for="vud_surface">Surface (m²)</label>
            <input type="number" id="vud_surface" name="surface" min="0">
        </div>
        <?php endif; ?>

        <?php if ($show('budget')): ?>
        <div class="form-group">
            <label for="vud_budget">Budget (€)</label>
            <input type="number" id="vud_budget" name="budget" min="0">
        </div>
        <?php endif; ?>

        <div class="form-group">
            <label for="vud_description">Description du projet *</label>
            <textarea id="vud_description" name="description" rows="5" required placeholder="Décrivez votre projet le plus précisément possible..."></textarea>
        </div>

        <?php if ($show('matin') || $show('midi') || $show('soir') || $show('we')): ?>
        <div class="form-group">
            <label>Disponibilité pour être contacté</label>
            <div class="vud-checkbox-row">
                <?php if ($show('matin')): ?><label><input type="checkbox" name="matin" value="1"> Matin</label><?php endif; ?>
                <?php if ($show('midi')): ?><label><input type="checkbox" name="midi" value="1"> Midi</label><?php endif; ?>
                <?php if ($show('soir')): ?><label><input type="checkbox" name="soir" value="1"> Soir</label><?php endif; ?>
                <?php if ($show('we')): ?><label><input type="checkbox" name="we" value="1"> Week-end</label><?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <button type="submit">Envoyer ma demande de devis</button>
    </form>

    <script>
    (function () {
        var form = document.getElementById('vud-lead-form');
        if (!form) return;
        form.addEventListener('submit', function (e) {
            var tel = form.querySelector('[name="tel"]');
            var mobile = form.querySelector('[name="mobile"]');
            var telVal = tel ? tel.value.trim() : '';
            var mobileVal = mobile ? mobile.value.trim() : '';
            if (!telVal && !mobileVal) {
                e.preventDefault();
                alert('Merci de renseigner au moins un numéro de téléphone (fixe ou mobile).');
            }
        });
    })();
    </script>
    <?php endif; ?>
    <?php
    return ob_get_clean();
}
add_shortcode('vud_lead_form', 'vud_lead_form_shortcode');
