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

## 2026-07-11 — Add client brand slider

- Added a "Наші клієнти" slider below the turnkey services section using the existing `brands` taxonomy data.
- Registered the legacy `brands` taxonomy in the new theme so the existing brand records remain available.
- Reused legacy brand thumbnail storage with term-meta fallbacks so logos render without adding new admin fields.
- Added lightweight front-page logo slider controls and responsive logo track styling.

## 2026-07-11 — Replace homepage order benefits

- Replaced the static numbered "Чому клієнти обирають Kanapka" section with an editable homepage benefits section after "Під ключ".
- Added an SCF "Переваги" tab with title and repeater fields for icon, color, title and text.
- Styled the block as six inline icon/text benefits per desktop row, tablet grid rows and one-item scroll-snap slides on mobile.

## 2026-07-12 — Make mobile navigation scrollable

- Locked the page with a body class while the mobile menu is open and restored the previous scroll position on close.
- Changed the mobile navigation panel to a fixed-height viewport panel with its own vertical scrolling.
- Added mobile submenu toggle buttons so standard submenus, category mega-menu and contacts mega-menu open only by clicking their arrow control.

## 2026-07-13 — Stabilize the mobile header and catalogue list cards

- Allowed the header logo column to shrink while preserving the navigation, cart and search control sizes at 320–414px widths.
- Reduced only the narrowest header gaps and icon-button boxes instead of hiding a header action.
- Reworked catalogue list cards so their purchase controls use a structured grid on tablets and stack below a full-width product card on mobile.

## 2026-07-13 — Add visible quantity steppers everywhere

- Added reusable, always-visible minus and plus controls to product cards, quick view, the header mini-cart and standard WooCommerce quantity inputs.
- Reused the theme SVG icon helper and matched each stepper size to its card, modal, mini-cart or cart context.
- Added one shared delegated script that respects input minimum, maximum and step values and triggers the existing cart synchronization flows.

## 2026-07-13 — Rebuild the WooCommerce cart body

- Rebuilt the cart body as a responsive product table and sticky totals card while leaving the existing header and footer unchanged.
- Preserved WooCommerce cart, shipping, fee, tax, coupon and plugin hooks so installed commerce extensions continue to integrate through standard output.
- Reused the shared quantity control, SVG icon helper, homepage benefit data, theme buttons and container system.
- Added responsive product cards, compact checkout assurances, cart clearing, update actions and empty-cart presentation.

## 2026-07-13 — Refine cart actions, icons and benefits

- Moved the clear-cart and update-cart actions to opposite edges of the cart table footer.
- Replaced cart action and sidebar assurance artwork with the requested Lucide `trash-2`, `refresh-cw`, `clock-arrow-up`, `hand-coins` and `salad` paths.
- Made action and assurance SVGs non-shrinking and expanded the shipping row across the complete totals card width.
- Removed the duplicate cart-benefits component and rendered the existing catalogue `order-benefits` section on the cart page.
- Kept the cart action row and `td.actions` in the table layout at full width, moving flex alignment into an inner action container so the buttons sit at the left and right edges.
- Removed notice styling from the outer WooCommerce notices wrapper so each message has only one blue left border.

## 2026-07-13 — Update cart quantities without reloading

- Added debounced AJAX cart updates for manual quantity edits and the shared minus/plus controls.
- Replaced the cart body with the recalculated WooCommerce response and refreshed header cart fragments after each successful update.
- Hid the fallback update button only after the AJAX behavior initializes, keeping the standard form action available if JavaScript fails.

## 2026-07-13 — Remove the duplicate empty-cart message

- Removed the clear-cart success notice because the standard WooCommerce empty-cart state already communicates the result and provides the return-to-shop action.

## 2026-07-13 — Refine cart totals and add recommendations

- Expanded every shipping method across the full totals-card width and changed sidebar assurance icons to borderless circles with a soft grey background.
- Made cart quantity AJAX explicitly request and replace WooCommerce fragments so the complete header mini-cart updates with the cart body.
- Added a four-card-wide recommendation slider below the cart by reusing the existing product card and product slider components while excluding products already in the cart.
- Confirmed that the cart questions block uses the two current phone values from Theme Settings.
