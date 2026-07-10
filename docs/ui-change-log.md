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
