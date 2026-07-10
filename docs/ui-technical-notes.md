# UI technical notes

## Navigation

- Desktop submenus must never affect header height. Position them absolutely relative to the top-level parent and hide them with opacity, visibility and pointer-events.
- Support both pointer and keyboard navigation using `:hover` together with `:focus-within`.
- Add a small invisible hover bridge between the parent link and dropdown so the menu does not close while the pointer crosses the gap.
- At the mobile breakpoint, reset dropdown positioning and visibility so child links stay in the normal document flow.
- Keep WordPress menu hierarchy and theme-generated mega-menu content independent: admin submenus come from `wp_nav_menu()`, while the mega-menu is appended only to the SCF-selected top-level page.
- Generate mega-menu category links from live `product_cat` terms instead of storing duplicated category lists in options.
- Use the selected menu page object ID rather than a fragile menu-item database ID so the configuration survives menu reconstruction.
- Never leave a non-interactive vertical gap between a top-level item and a hover submenu; align the popup directly to the trigger boundary so users can move into and click its links.
- Header cart and search icons are disclosure buttons, not navigation links. Keep destinations inside their popups and synchronize mini-cart markup with WooCommerce fragments.
- Auto-open the cart only in response to a successful add-to-cart event; retain Escape, outside-click and explicit close behavior for every header popup.
- When mirroring a legacy mega-menu whose sequence is read left-to-right across rows, use CSS Grid with explicit `grid-auto-flow: row`; CSS multi-column layout fills down each column and changes the visible order.
