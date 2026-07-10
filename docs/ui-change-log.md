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
