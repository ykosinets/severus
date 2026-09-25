# Severus Noir

Dark corporate theme for severus.co. The home page renders the ACF content
already in the database; everything else is core Gutenberg blocks styled through
`theme.json`.

## Build

```bash
npm install
npm run build     # one pass, minified
npm run dev       # rebuild on change
```

`build.mjs` treats the folders as the manifest — there is no asset registry to
keep in sync:

```
components/<name>/<name>.pcss  ->  assets/dist/main.css
components/<name>/<name>.js    ->  assets/dist/main.js
```

Add a component directory and the next build picks it up. Globals load first, in
the order listed at the top of `build.mjs`.

Dependencies come from npm and are bundled: `three` and `gsap` for the orbit —
the only two the theme has. esbuild code-splits, so they land in
`assets/dist/chunks/` and only load when the impact section asks for them.

## Layout

```
components/<name>/    template + styles + script, one component per folder
src/styles/global/    tokens, reset, typography, layout, motion, vendor
src/scripts/          shared helpers, the snake arrow, vendored crystal slider
inc/                  setup, assets, helpers, svg inlining, block registration
patterns/             core-block patterns
theme.json            palette, type scale, spacing, layout for the editor
```

## Style rules

`.pcss` files are compiled by PostCSS with `postcss-nesting`:

- nested syntax, `&` for self-references
- media queries **inside** the selector, never a selector inside a media query
- nesting no deeper than three levels
- one declaration per line

Values come from `src/styles/global/tokens.pcss` — no raw colours or spacing in
component sheets.

## Blocks

No custom block types. Editorial content is built from core blocks, styled by
`theme.json` (palette, fluid type, spacing scale, layout widths) plus the block
style variations registered in `inc/blocks.php`: gradient-edge group and columns,
emerald rule, quiet button, display heading, framed image, card quote. Patterns
live in `patterns/`.

## Content

The home page and the inner templates read the existing ACF fields — nothing
was migrated. Field names match the previous theme, so the two render the same
content.

| Template | Renders |
| --- | --- |
| `front-page.php` | the home page sections |
| `single-service.php` | `service_*` fields: hero, what it is, when you need it, approach, what you get, what changes, more services, FAQ, CTA |
| `single-industry.php` | `industry_*` hero and the `cs_*` case studies |
| `single-case.php` | thumbnail, client / categories / country, the content, `case_testimonial`, more cases |
| `archive-case.php` | every case, or one `case_category`; heading copy from the ACF options page (`case-list-*`) |
| `page-about-us.php` | `heading_h1/h2`, `description`, `services-section`, `faq-section`, `case-list` |
| `page-contact-us.php` | the `contact_form` group beside the theme's form |
| `page-industries.php` | native page title/content and a tile per published industry, without a section heading or page-level ACF fields |
| `page-services.php` | native page title/content and all published services, ordered by `menu_order`, then title; no page-level ACF fields |
| `page.php` | the content, then any of `services_list`, `case-list`, `review_list`, `insights_list`, `contact_form` that are filled in |
| `single.php` | block editor content only |

Services, cases and reviews are registered by ACF. The `industry` post type was
registered by the previous theme, so `inc/post-types.php` registers it again
with the same slug; rewrite rules are flushed once per `SEVERUS_REWRITE_VERSION`.

Cases are grouped by the theme's `case_category` taxonomy (Cases → Categories;
term archives at `/cases/category/<slug>/`, rendered by `archive-case.php`,
which also shows the category filter). It replaces the free-text Industry
field of `cases_fields.case_info`; that field is hidden from the editor in
`inc/acf.php` and can be deleted from the "Cases page" group in ACF.

Home components (`results`, `services`, `faq`, `voices`, `journal`, `talk`) take a `$data`
array; anything left out falls back to the front page field, so the home page
calls them with no arguments. `results` shows three cases; `services` shows
the top-level services in their native `menu_order` and every service it is given elsewhere, with
`service_short_descr` as the excerpt, `service_card_points` as the ticked
list and `service_card_image` behind the card. Those fields are
registered in code (`inc/acf.php`), so they ship with the theme.

On the Services page (`page-services.php` passes `feature`), each top-level
service gets a highlighted card with its children alongside it. Grouping reads
WordPress `post_parent`; parent and order are edited in native Page Attributes.
Inner pages add `page-hero`, `brief`, `points`,
`gains`, `shift`, `tiles`, `callout` and `case-facts`, which only take `$data`.

The field values mark accents with `<span>` (and `<b>` for weight) rather than
`<em>`, so `typography.pcss` scopes the accent styling to both, inside a fixed
list of containers. A bare `span` rule would repaint plugin output — the cookie
notice and the contact form.

Service glyphs are inlined by `inc/svg.php` rather than referenced with `<img>`:
the card draws their strokes on with CSS, which neither `<img>` nor `<use>` can
do. The backplate is stripped, ids are namespaced, the source palette is mapped
onto tokens and every stroked path gets `pathLength="1"`. Results are cached in a
transient for a week.

## Settings this theme expects

| Where | What |
| --- | --- |
| Menus | `primary`, `footer_pages`, `footer_services` — same slugs as before, so existing assignments carry over |
| Customizer → Severus — contact | public email, LinkedIn URL, contact form shortcode |
| Site identity | custom logo |

## The orbit

`components/orbit/` owns the 3D orbit mark: `scene.js` (three + gsap, split
into its own chunk) and `orbit.js`, which mounts every `[data-orbit]` host the
first time it nears the viewport. `severus_orbit( $class, $scroll )` prints a
host with the still preview inside; the still stays when motion is reduced or
WebGL fails, and a host hidden at a breakpoint never loads the scene.

- `impact` places one behind the slider and turns it with its section.
- Inner pages use it where the previous theme had its rotating `img.round`:
  `orbit--deco` hangs it half above the block at one side of the shell
  (`orbit--left` / `orbit--right`) and turns it with the whole page. The page
  hero adds it on the left unless the hero has an image; `faq`, `results` and
  `talk` add it when passed `orbit`.

The callout panel (`components/callout/`) carries three flat-shaded
icosahedra of different sizes in one WebGL scene (`shapes.js`),
mounted the first time a callout nears the viewport. They turn slowly and drift
with the scroll — the bigger, the further — only while the panel is on
screen; they stand still with reduced motion and keep to the corners on
narrow panels. three is split into a shared chunk used by both scenes.

## The hero video

No lightbox library. `components/media-lightbox/` creates a `<dialog>` holding
one `<video>` and flies it from the thumbnail's exact box to its final one —
position, size and corner radius animated together — so it reads as the poster
growing rather than a panel appearing.

- Desktop lands at `height: 70vh`, width from the clip's ratio, centred.
  Under 900px it goes full width.
- Playback starts when the flight lands and stops the moment it is dismissed.
  Native controls throughout; the clip does not loop.
- Dismissed by clicking past the video, by Escape (the dialog's own `cancel`),
  or on touch by dragging up or down more than 60px. A click on the video or its
  controls is stopped before it reaches the backdrop.
- The resting geometry is set to the destination and the flight is a keyframe
  away from it, so a throttled or interrupted animation still leaves the video
  the right size. A timer backs up the `finished` promise for the same reason.

PhotoSwipe was removed along with its stylesheet and chunk.

## The contact form

The contact component renders Forminator form **82** with the existing Noir
styling. Forminator must remain active; it loads its own frontend dependencies.

- `[severus_contact_form]` renders the same component on any page.
- Forminator owns field definitions, AJAX validation, reCAPTCHA, submissions
  and email notifications. Edit recipients in Forminator, not the Customizer.
- `inc/contact.php` adds theme presentation classes without replacing plugin
  field names, nonces or submission handlers.
- The former `severus/v1/contact` endpoint is removed. Existing `severus_lead`
  records remain available under Enquiries; new submissions belong to Forminator.
- If the plugin is unavailable, the component displays a contact email link.
- The saved reCAPTCHA key rejects `severus.ddev.site`. Authorize that hostname
  in the key settings before testing successful submissions locally; do not
  disable production CAPTCHA protection to work around the local restriction.

## Fonts

Outfit is **self-hosted** in `assets/fonts/` and preloaded from `wp_head`. The
Google Fonts stylesheet is gone: it cost a blocking request to one origin plus a
second hop to `fonts.gstatic.com` before the face was even named.

Three things keep the swap from moving the page:

- **Preload.** Without it the browser only learns about the file after parsing
  the CSS, which is long enough to lay the page out in the fallback and then do
  it again.
- **A metric-matched fallback.** `Outfit Fallback` maps to Arial with
  `size-adjust: 95.14%`, `ascent-override: 105.1%` and `descent-override: 27.3%`
  — measured in the browser, where Outfit is 2856.22 wide with ascent 100 and
  descent 26 at 100px against Arial's 3002.05 / 91 / 21. Retune these if the
  display face changes.
- **Measures in `em`, never `ch`.** `ch` is the width of the font's own zero:
  0.657em in Outfit, 0.556em in Arial. Every `ch`-based `max-width` therefore
  jumped 19% at the swap and rewrapped the copy. The `em` values render
  identically in Outfit and do not move.

What remains is a line's worth of reflow on two blocks, because a metric-matched
fallback still can't reproduce per-glyph advances. If a page that never shifts
matters more than always seeing Outfit, change the two Outfit faces to
`font-display: optional` — the trade is that a slow first visit renders in the
fallback for that page load.

The site is set in Outfit throughout, as the previous theme was. The
e-Ukraine files are kept in `assets/fonts/`, but `severus_local_font_faces()`
in `inc/assets.php` is not hooked and the family is not in `--font` /
`--font-display`, so browsers never request them — nor use a copy installed
on the visitor's machine. Re-add both to switch to e-Ukraine, and retune the
fallback metrics above for it.

## Styleguide

`/styleguide/` uses `page-styleguide.php`, also available as the Styleguide page
template. Its layout and interactions live in `components/styleguide/`.
The examples reuse theme tokens and component classes; displayed token values
come from the computed stylesheet. Form fields are a local preview only.
Run `npm run build` after changing the component styles or script.
