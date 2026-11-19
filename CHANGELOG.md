# Changelog

## 3.1 — 2026-11-07

- Allow sizes (width/height) larger than 999 pixels
- Cope with RTL
- Enforces some verification before registering module

## 3.0 — 2026-08-03

- Better this way to check property existence
- Avoid using magic _get(), _set() methods of MetaRecord (2.39+)
- Avoid using magic methods for user preferences and blog settings (2.39+)
- Use MetaRecord::strField(), MetaRecord::intField() and MetaRecord::boolField() whenever is possible
- getPosts needs an array, not an ArrayObject
- Use dotclear.ToolBar rather than old jsToolBar, DC 2.39+

## 2.9 — 2026-05-10

- Add Apple podcasts provider endpoint

## 2.8 — 2026-04-15

- DA -&gt; DC

## 2.7 — 2025-11-28

- PHP 8.5 compliance

## 2.6 — 2025-10-11

- Don't do anything if given url is empty

## 2.5 — 2025-09-22

- Use backend services rather than static calls

## 2.4 — 2025-09-15

- Use legacyEditor groups management

## 2.3 — 2025-09-15

- Add spacer around toolbar button (waiting for group management)

## 2.2 — 2025-09-12

- Replace static call to Ctx by context() — thanks JcDenis for advice

## 2.1.1 — 2025-09-09

- Fix context checking for popup

## 2.1 — 2025-09-07

- DC 2.36 compliance

## 2.0 — 2025-09-06

- DC 2.36 compliance

## 1.15 — 2025-08-05

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 1.14 — 2025-07-22

- Add a waiting indicator for legacyEditor (during waiting for oEmbed provider response)
- oEmbed API entrypoint is only enabled on entry pages (post/page). The given response will include only entry content and some metadata (author, title, link)

## 1.13 — 2025-07-20

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 1.12 — 2025-07-20

- Add an option to set the blog as an oEmbed provider (disabled by default) Basic one: will return an iframe with the full content of the requested page

## 1.11 — 2025-07-17

- Adjust types

## 1.10 — 2025-07-16

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 1.9 — 2025-07-16

- Screencast no longer provide oEmbed API entrypoint

## 1.8 — 2025-07-14

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 1.7 — 2025-07-12

- Add an optional Caption (if given the media will be embed in a figure+figcaption instead of div)

## 1.6 — 2025-07-12

- Add Codepen, Framatube, Peertube and Pinterest in known oEmbed entrypoints

## 1.5 — 2025-07-11

- Remove old alignment styles, not more used

## 1.4 — 2025-07-11

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 1.3 — 2025-07-11

- Use image alignment setting (from blog parameters) as default alignment

## 1.2 — 2025-07-11

- Add maxwidth and maxheight management with CKEditor

## 1.1 — 2025-07-11

- Add blog activation setting in blog parameters (true by default)

## 1.0 — 2025-07-10

- Avoid event propagation in script
- Minify scripts
- Add max width/height to legacyEditor form insertion
- Use only one step in legacyEditor (as for CKEditor)
- Add legacyEditor management
- Don't wait for dotclear ready.
- Adjust required width and height if one of them, or both, are not provided Using video sizes defined in blog parameters
- Fix fetch
- Autres correctifs et améliorations.
