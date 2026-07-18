# ViteUnDevis Lead Sender

A WordPress plugin that collects home-improvement / construction project
leads through a frontend form and submits them directly to the
**ViteUnDevis "dépôt de devis" API (v1.5)**, operated by ForumConstruire
SARL. Every submission — successful, test, or failed — is logged locally so
you have your own record independent of ViteUnDevis's system.

## What it does

- Renders a public lead-capture form via the `[vud_lead_form]` shortcode,
  covering every field the ViteUnDevis API accepts (required and optional).
- Validates input client-side and server-side before calling the API
  (required fields, valid email, at least one phone number).
- Submits the lead to ViteUnDevis over HTTPS, using either the test endpoint
  (`?test=1`, no data written on their end) or the production endpoint,
  toggled from Settings.
- Parses the JSON response, maps every documented error code (401–888) to a
  plain-language message, and shows it to the visitor.
- Logs every attempt — successful, test-mode, or failed — to a dedicated
  database table (`wp_vud_submissions`), with the raw API response kept for
  debugging.
- Provides an admin dashboard to browse submissions (filterable by status,
  paginated) and export everything to CSV.
- Lets the admin choose which optional fields (société, budget, surface,
  permis, availability windows, etc.) appear on the public form; the fields
  the API always requires are shown automatically and can't be hidden.
- Keeps the category list (`cat_id` → name) admin-editable as plain text
  rather than hardcoded in code, since ViteUnDevis maintains that list
  separately as a CSV that changes over time.

## Requirements

- WordPress 5.x or later
- PHP 7.4+
- A ViteUnDevis API key (from ForumConstruire SARL)

## File structure

```
viteundevis-lead-sender/
├── viteundevis-lead-sender.php   # Plugin bootstrap, activation hook (creates DB table)
├── includes/
│   ├── settings.php               # Admin settings page, category list, field visibility
│   ├── frontend.php                # [vud_lead_form] shortcode and form markup
│   ├── api-handler.php             # Validates, calls the ViteUnDevis API, stores results
│   └── dashboard.php                # Admin submissions list + CSV export
└── assets/css/frontend-style.css   # Public form styling
```

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

## Notes / known limitations

- At least one of phone / mobile is required, both client-side and
  server-side, per the API spec.
- `cp_projet` / `ville_projet` are only strictly required by ViteUnDevis for
  "construction" categories — this plugin sends them if filled in but
  doesn't force them, since that construction-category flag isn't exposed in
  the API doc's field list. If a submission errors out with code 004/005,
  the visitor will see a friendly message and can retry with those fields
  filled in — turn on those optional fields in Settings if you sell into
  construction categories.
- No spam protection (honeypot/CAPTCHA/rate limiting) is included yet — add
  one before putting a public form live on a high-traffic site.
- Not yet tested against the live API with a real key — verify a successful
  submission on staging before disabling test mode in production.
- All submissions (successful, test, or failed) are logged in a
  `wp_vud_submissions` table, viewable in the dashboard. This table and the
  plugin's options are not removed on uninstall.

## API reference

Built against ViteUnDevis's "API dépôt de devis v1.5" documentation
(27 January 2025, ForumConstruire SARL).
