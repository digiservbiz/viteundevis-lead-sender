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
- Implements the mandatory phone-consent proof required by API v1.6
  (`consent_date`, `consent_ip`, `consent_texte`, `consent_url`), per
  loi n° 2025-594 — an unchecked-by-default checkbox, distinct from any
  other consent, with an admin-editable consent text (single source of
  truth used both on the form and in what's sent to the API) and an
  optional privacy-policy link next to it.
- Shows a summary widget right on the WordPress admin **Home** dashboard
  (total/success/test/error counts, leads this week, last 5 leads) so you
  don't have to visit a submenu to check in.
- Every submission attempt — success, test, or failed — is always logged
  locally first, independent of whatever ViteUnDevis's API returns; that's
  your permanent copy of every lead generated from the site.
- Optional email copy of each new lead, and an optional webhook URL that
  POSTs every lead as JSON — works with Zapier/Make/n8n or most CRMs'
  inbound-webhook endpoints today, without committing to one CRM. A
  `vud_after_submission` PHP action hook is also fired for a future
  custom/native CRM integration to hook into directly.

## Requirements

- WordPress 5.x or later
- PHP 7.4+
- A ViteUnDevis API key (from ForumConstruire SARL)

## File structure

```
viteundevis-lead-sender/
├── viteundevis-lead-sender.php   # Plugin bootstrap, activation hook, versioned DB upgrades
├── includes/
│   ├── settings.php               # Admin settings page, category list, field visibility
│   ├── frontend.php                # [vud_lead_form] shortcode and form markup
│   ├── api-handler.php             # Validates, calls the ViteUnDevis API, stores results
│   ├── dashboard.php                # Admin submissions list + CSV export
│   ├── widget.php                   # WP-admin Home dashboard summary widget
│   └── notifications.php            # Email copy, webhook/CRM POST, action hook
└── assets/css/frontend-style.css   # Public form styling
```

## SEO: one landing page per category

`[vud_lead_form]` alone shows the full category dropdown. To build one
dedicated, indexable page per category — the same structural pattern
ViteUnDevis itself uses (`/devis-0-13-...php`, `/devis-0-1-...php`, etc.,
one URL per trade) — pass a `cat_id`:

```
[vud_lead_form cat_id="13"]
```

This locks the category (it's shown as a fixed label instead of a
dropdown, sent as a hidden field) and, if that category is one of the 5
flagged "construction" ones, automatically shows/requires
`cp_projet`/`ville_projet` too — no JavaScript toggle needed since the
category can't change.

Create one WordPress page per category you want to rank for (e.g.
`/devis-toiture/`, `/devis-electricite/`), each with its own unique
`<title>`, H1, and real written content about that trade — then drop in
the shortcode with that category's ID. A thin page that's just the form
with no unique content around it won't rank; the form should sit inside
genuine, category-specific content, the same way ViteUnDevis's own pages
work.

Category IDs are the numbers on the left in the **Catégories de devis**
list in Settings (e.g. `13|Electricité (Travaux électriques)` → `cat_id="13"`).

### Bulk-creating the pages

Go to **ViteUnDevis → Générer les pages**, tick the categories you want a
landing page for, and click "Générer les pages sélectionnées". This
creates one WordPress page per category, as a **draft**, with:

- A title/slug following the same pattern ViteUnDevis uses on their own
  category pages
- The `[vud_lead_form cat_id="X"]` shortcode already inserted
- `[À COMPLÉTER ...]` placeholder blocks marking exactly where real,
  unique written content needs to go

Nothing is published automatically — each draft needs a human pass to
replace the placeholders with genuine content before you publish it.
Running the generator again skips categories that already have a
generated page, so it's safe to re-run for just the new categories you
pick each time.

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
   - **Catégories de devis** ships with the real, complete ViteUnDevis
     list (155 categories) — no need to paste anything unless ViteUnDevis
     sends you an updated CSV later.
   - Check any optional fields (société, budget, surface, permis, etc.) you
     want visible on the public form. The fields the API always requires are
     shown automatically.
   - Under **Consentement au démarchage téléphonique**, review/edit the
     consent checkbox text (must accurately name who will contact the
     visitor) and set a privacy-policy URL. This is legally required
     (loi n° 2025-594) and enforced by the API itself as of v1.6.
   - Under **Notifications & CRM**, optionally turn on an email copy of
     every lead, and/or set a webhook URL (Zapier/Make/n8n/CRM inbound
     webhook) to receive every lead as JSON in real time.
4. Place the shortcode `[vud_lead_form]` on any page or post.
5. Check **ViteUnDevis → Demandes** to see submissions and export to CSV.

## Notes / known limitations

- At least one of phone / mobile is required, both client-side and
  server-side, per the API spec.
- `cp_projet` / `ville_projet` are now shown and required automatically
  only for the 5 categories ViteUnDevis flags as "construction" in their
  official CSV — no more guessing. If ViteUnDevis adds/changes which
  categories count as construction, update the comma-separated ID list
  under **Catégories "construction"** in Settings.
- No spam protection (honeypot/CAPTCHA/rate limiting) is included yet — add
  one before putting a public form live on a high-traffic site.
- Not yet tested against the live API with a real key — verify a successful
  submission on staging before disabling test mode in production.
- All submissions (successful, test, or failed) are logged in a
  `wp_vud_submissions` table, viewable in the dashboard. This table and the
  plugin's options are not removed on uninstall.

## API reference

Built against ViteUnDevis's "API dépôt de devis" documentation — v1.5
(27 January 2025) and updated for v1.6 (22 July 2026, adds mandatory
phone-consent fields), both from ForumConstruire SARL.

## Changelog

- **2.7** — Added **ViteUnDevis → Générer les pages**: bulk-creates one
  draft WordPress page per selected category, pre-filled with the
  `[vud_lead_form cat_id="X"]` shortcode, a ViteUnDevis-style title, and
  placeholder blocks marking where real content needs to be written.
  Skips categories that already have a generated page on re-run.
- **2.6** — `[vud_lead_form cat_id="13"]` now locks the category, for
  building one dedicated SEO landing page per category (mirroring
  ViteUnDevis's own one-URL-per-trade structure) instead of a single
  generic page. Construction-field requirement is resolved server-side
  when locked, no JS toggle needed.
- **2.5** — Added the WP-admin Home dashboard widget (summary counts +
  last 5 leads), an optional email copy per lead, an optional webhook URL
  (JSON POST per lead) for no-code CRM/automation integration, and a
  `vud_after_submission` action hook for future custom CRM code. Fixed
  `VUD_VERSION` having silently drifted from the plugin header since 2.1.
- **2.3** — Replaced the sample category list with the real, complete list
  from ViteUnDevis (155 categories, shipped as the default so it works
  out of the box). Construction categories are now auto-detected from the
  official CSV's flag rather than guessed: `cp_projet`/`ville_projet`
  appear on the form and become required automatically only when the
  visitor selects one of the 5 categories ViteUnDevis marks as
  "construction" (currently: Architecte - construction de maison,
  Maître d'oeuvre, Constructeur de maisons, Construction, Maison bois).
  Enforced both client-side (JS) and server-side.
- **2.2** — Split the frontend form into a 3-step wizard (coordonnées →
  projet → consentement/envoi) with a progress indicator, to reduce the
  perceived length. Still a single submission, no backend changes.
- **2.1** — Added support for API v1.6's mandatory consent_date /
  consent_ip / consent_texte / consent_url fields and the corresponding
  frontend consent checkbox, plus error codes 110–112.
- **2.0** — Initial full build: form, API integration, admin dashboard,
  CSV export.
