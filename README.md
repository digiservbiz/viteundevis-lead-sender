# ViteUnDevis Lead Sender

## Installation
1. Copy this whole folder into `wp-content/plugins/`.
2. Activate it under **Plugins** in wp-admin.
3. Go to **ViteUnDevis → Paramètres**:
   - Enter your ViteUnDevis API key.
   - Leave **Mode test** checked while you're testing — it hits the `?test=1`
     endpoint, so nothing gets written to ViteUnDevis's database. Uncheck it
     to go live.
   - Optionally set a "thank you" redirect URL, a callback URL, and your
     `site_name`.
   - **Replace the sample category list** in the "Catégories de devis" box
     with the full list from ViteUnDevis (they provide this as a CSV — one
     `id|Nom de la catégorie` per line). The sample list is just a few
     entries to prove the form works out of the box.
   - Check any optional fields (société, budget, surface, permis, etc.) you
     want visible on the public form. The fields the API always requires are
     shown automatically.
4. Place the shortcode `[vud_lead_form]` on any page or post.
5. Check **ViteUnDevis → Demandes** to see submissions and export to CSV.

## Notes
- At least one of phone / mobile is required, both client-side and
  server-side, per the API spec.
- `cp_projet` / `ville_projet` are only strictly required by ViteUnDevis for
  "construction" categories — this plugin sends them if filled in but
  doesn't force them, since that construction-category flag isn't exposed in
  the API doc's field list. If a submission errors out with code 004/005,
  the visitor will see a friendly message and can retry with those fields
  filled in — turn on those optional fields in Settings if you sell into
  construction categories.
- All submissions (successful, test, or failed) are logged in a
  `wp_vud_submissions` table, viewable in the dashboard.
