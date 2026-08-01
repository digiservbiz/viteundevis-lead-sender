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

### Building the shared design in Divi Theme Builder

Rather than binding individual Divi modules to custom fields via Dynamic
Content (which requires trusting Divi's field picker to discover our
custom meta — not guaranteed across versions), this plugin renders the
**entire page body as one shortcode**:

```
[vud_category_page_body]
```

It reads whichever page it's rendered on automatically (title, category,
intro, FAQ) — so this one shortcode produces different content on every
one of the 155 pages, even though you only set it up once. It includes:
hero (title + subheading), intro text (if written), the lead form already
locked to the right category, a static "Comment ça marche" trust section,
FAQ (if written, as a simple accordion), and 6 random links to other
category pages for internal linking.

**Setup — one-time, in Divi 5:**

1. Go to **Divi → Theme Builder**
2. Click **Add New Template** → **Build New Template**
3. Assign it to your generated category pages (see note below)
4. In the new template, **leave Header and Footer alone** so the site's
   normal global header/footer keep showing
5. Click **Add Custom Body** → **Build Custom Body**
6. Add a single **Code module** (or Text module), and paste
   `[vud_category_page_body]` into it — nothing else needed
7. Save and exit

Don't also add a separate page-title/hero module or a "Post Content"
module to the same template — this shortcode already renders its own
`<h1>` and already includes the form, so either would duplicate content.

**On assignment (step 3):** Divi lets you assign a template to "All
Pages," to individually-picked pages, or to a taxonomy/category grouping
depending on your Divi version. Check what your Template Settings popup
actually offers — if it's individual-page selection only, you'll need to
multi-select the generated pages there.

### Writing the content

Go to **ViteUnDevis → Contenu des pages** for a checklist of every
generated page with its intro/FAQ completion status. Each page gets a
starter draft (seeded from ViteUnDevis's own category data) marked
`[À COMPLÉTER]` — replace those with real, unique writing before
publishing. This is the single most important thing for these pages to
actually rank; the shared Divi layout won't do that on its own. FAQ text
should follow a `Q : question` / `R : answer` pattern, one pair per blank
line, so the shortcode can parse it into an accordion.

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

- **3.0** — Added `[vud_category_page_body]`: renders the entire page
  body (hero, intro, locked lead form, trust section, FAQ accordion,
  related-category links) from a single shortcode, reading whichever page
  it's on automatically. Replaces the earlier per-module Divi Dynamic
  Content approach — paste it once into one Code module in the Theme
  Builder Custom Body, and it works across all 155 pages with no
  per-page Divi setup and no dependency on Divi's field picker finding
  our custom meta.
- **2.9** — Added `vud_intro_text`/`vud_faq_text` as properly registered
  post meta (with `show_in_rest`) on generated pages, so Divi 5's Dynamic
  Content picker (and other block-based builders) can bind to them
  directly. Added **ViteUnDevis → Contenu des pages**, a checklist screen
  to write/track intro and FAQ text per page without opening Divi.
  Page generator now leaves `post_content` as just the shortcode (for use
  inside a Theme Builder "Post Content" module) instead of embedding
  placeholder paragraphs directly in the content.
- **2.8** — Fixed a real bug (confirmed live on 3devisgratuit.com):
  `cp_projet`/`ville_projet` were showing for *every* category, not just
  construction ones, because our own `.form-row { display: flex }` CSS
  rule had the same specificity as the browser's `[hidden]` rule and won
  by being declared later — silently overriding the hide. Added
  `.vud-form [hidden] { display: none !important; }` so `hidden` always
  wins regardless of what other display rules exist on the same element.
  Also fixed the required-field asterisk showing even when the fields
  weren't actually required, and changed the page generator's heading
  from H1 to H2 (Divi/most themes already render an H1 from the page
  title — the generator's own H1 was creating a duplicate).
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
