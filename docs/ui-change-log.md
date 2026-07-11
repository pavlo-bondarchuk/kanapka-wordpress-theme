# UI change log

## 2026-07-10

- Changed desktop primary-navigation submenus to remain hidden by default and open only when the parent item is hovered or contains keyboard focus.
- Positioned desktop submenus outside the header flow with a solid surface, border, shadow and readable item states.
- Kept mobile submenus in the navigation flow so child links remain available without hover.
- Added a second SCF-driven category mega-menu mode alongside standard WordPress admin submenus.
- Matched the legacy OXY category menu structure with a wide multi-column desktop panel and compact in-flow mobile category list.
- Removed the hover gap from desktop dropdowns so submenu links remain reachable and clickable.
- Added an SCF-configurable contacts submenu using the legacy OXY contact, address and working-hours data.
- Consolidated logo, order label, phones, working hours, email and both generated menus into the first Header and navigation settings tab.
- Replaced direct cart and search navigation with accessible click popups; the WooCommerce mini-cart refreshes through fragments and opens after AJAX add-to-cart.
- Changed category mega-menu ordering to a row-flowing three-column grid: left to right across each row, then top to bottom.
- Bound the category mega-menu to its own parent menu item so the pointer never crosses a gap before reaching a category link.

# 2026-07-10 — Center category mega menu

- Centered the desktop category mega menu against the full site header.
- Kept the panel available while the pointer crosses the gap below the navigation.

## 2026-07-10 — Align mega-menu items in flex rows

- Replaced the category grid with a wrapping flex row while retaining three equal desktop columns and one mobile column.

## 2026-07-10 — Consolidate mega-menu styles

- Moved all category mega-menu rules into the header stylesheet and removed the duplicate stylesheet enqueue.
- Increased category-card selector specificity so the general navigation-link rule no longer overrides its flex layout.

## 2026-07-10 — Refine mini-cart presentation

- Styled the WooCommerce mini-cart as a responsive header panel with product thumbnails, readable item controls, subtotal hierarchy and distinct cart and checkout actions.
- Added the live cart item count below the panel title without overriding the WooCommerce mini-cart template.

## 2026-07-10 — Match mini-cart item pricing layout

- Placed quantity and unit price directly below each product title and added the WooCommerce-calculated line total at the right edge of the same row.

## 2026-07-10 — Connect header language switcher to Weglot

- Rendered the active Weglot switcher in the header while retaining WPML and locale fallbacks when Weglot is unavailable.

## 2026-07-10 — Shorten header language labels

- Kept the standard Weglot switcher output and shortened only its Russian and Ukrainian labels to `RU` and `UA`.

## 2026-07-10 — Widen header container

- Increased only the header inner container from the shared `74rem` width to a dedicated responsive `86.25rem` maximum.

## 2026-07-11 — Build three-column mini-cart items

- Arranged each mini-cart item into image, product-information and action/line-total columns, with the quantity row directly below the linked title.
- Added a visible translated remove label only inside the header mini-cart without overriding the WooCommerce template.

## 2026-07-11 — Normalize circular button backgrounds

- Set an explicit `#efefef` background and native-appearance reset for header icon buttons and circular popover close buttons.

## 2026-07-11 — Build configurable homepage hero slider

- Replaced the static hero with a full-width responsive image slider, container-aligned content, arrows, pagination, autoplay and hover/focus pause behavior.
- Added an SCF hero tab with slide and shared-benefit repeaters; the benefit row is rendered once over the complete slider.

## 2026-07-11 — Move hero fields to the homepage

- Moved the hero SCF tab out of global Theme Settings into a dedicated field group shown only on the page assigned as the WordPress homepage.

## 2026-07-11 — Combine the hero button fields

- Replaced separate button label and URL inputs with one SCF Link field that keeps the label, destination and target together.
- Updated the hero CTA markup to support safe new-tab links while retaining existing saved button values during migration.

## 2026-07-11 — Correct homepage heading hierarchy

- Set hero slide headings to exactly `62px` on desktop while retaining the existing responsive mobile size.
- Kept the first slide as the page's only `h1` and rendered every subsequent slide title as `h2`.
- Replaced contact submenu heading tags with neutral styled labels so submenu content cannot precede the page `h1` in the document outline.

## 2026-07-11 — Centralize theme SVG icons and Ukrainian UI text

- Added a shared `kanapka_theme_ui_icon()` helper for theme-owned SVG icons.
- Replaced local inline SVG markup in the header cart, search popup and homepage hero with the shared icon helper.
- Converted visible theme fallback labels and front-page section copy to Ukrainian source strings.

## 2026-07-11 — Refine homepage category strip

- Limited the desktop category strip to five visible compact cards with horizontal overflow for the remaining categories.
- Made compact category media square with a white background so product images keep their intended clean white canvas.
- Added a lightweight right-arrow control that scrolls the category strip and loops back to the beginning at the end.

## 2026-07-11 — Add editable homepage SEO section

- Added a homepage SCF tab for the SEO section with title, long text, image and benefit repeater fields.
- Reworked the post-catalogue SEO text as a styled responsive card with image, benefit icons and a collapsed text preview.
- Added a lightweight read-more control that expands the full SEO text and can collapse it again.

## 2026-07-11 — Refine homepage SEO card layout

- Fixed the SEO section data mapping so benefit rows no longer overwrite the main section title or text.
- Anchored the SEO card image to the bottom-right of its column and limited the collapsed copy to four lines.
- Changed the benefit row to show five visible items on desktop and scroll horizontally when more items are added.
- Localized the SEO section SCF field labels and instructions to Ukrainian.

## 2026-07-11 — Add SEO benefit scroll hint

- Preserved the manually adjusted homepage SEO card styles and added a right-side fade over the benefits strip to make horizontal scrolling more discoverable.

## 2026-07-11 — Turn frequent orders into a slider

- Reworked the "Часто замовляють" block from a fixed product grid into a horizontally scrollable product slider.
- Added previous and next controls around the product carousel and loaded a small front-page-only slider script.
- Tuned the product card spacing, media area and responsive column widths so five cards are visible on desktop.

## 2026-07-11 — Make turnkey services editable

- Moved the "Організуємо все під ключ" cards into a homepage SCF repeater with title, text, link button and image fields.
- Reworked the front-end section so empty service data renders no section markup.
- Styled the service cards as image-backed promo tiles with three cards per desktop row and responsive wrapping.
