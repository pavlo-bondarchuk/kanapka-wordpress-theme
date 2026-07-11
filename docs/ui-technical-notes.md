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
- When mirroring a legacy mega-menu whose sequence is read left-to-right across rows, use a wrapping flex row; CSS multi-column layout fills down each column and changes the visible order.
- A mega-menu positioned against the entire header can create a dead hover area when its trigger is vertically centered; preserve header-relative alignment and bridge that gap with a short delayed-close state.

# Category mega menu alignment

- Center wide mega-menu panels against the shared header container, not the narrow trigger item.
- Bridge physical pointer gaps with a short hover-intent state so links remain reachable without changing panel alignment.

## Category mega-menu item flow

- Use a wrapping flex row with an explicit calculated basis when category cards must remain aligned in equal horizontal rows.

## Header stylesheet ownership

- Keep header mega-menu rules in the header component stylesheet and verify them against later generic navigation-link selectors using computed styles.

## Mini-cart styling

- Style the current WooCommerce mini-cart DOM instead of copying its plugin template; keep fragment replacement, remove links and standard button hooks intact.
- On small screens, constrain the entire cart panel to the viewport and stack its primary actions instead of compressing labels.
- When a mini-cart design needs both unit price and line total, extend `woocommerce_widget_cart_item_quantity` and calculate the total through the WooCommerce cart API rather than duplicating the displayed unit price.

## Language switcher integration

- Prefer the active translation plugin's public switcher output so language URLs, current state and exclusions stay synchronized; retain a non-plugin fallback for safe theme activation.
- When only compact labels are required, scope `weglot_get_name_with_language_entry` around the header shortcode instead of rebuilding Weglot URLs or changing its ordering.

## Header container width

- Use a dedicated design token when the header needs more horizontal room than page content; keep the same responsive gutters and avoid inline max-width overrides.

## Mini-cart item grid

- Scope product-name and remove-link filters to the header mini-cart render, then use those semantic wrappers as stable CSS Grid children; do not affect cart-page markup.

## Native button normalization

- Explicitly set appearance, border, radius and background on circular buttons so Safari, Chrome and other browsers do not supply visually different native surfaces.

## Homepage hero slider

- Render full-width hero artwork as responsive images with `srcset`, not CSS backgrounds, then layer container-aligned copy and one shared benefit row above it.
- Autoplay must pause on pointer hover, keyboard focus, hidden documents and reduced-motion preference; shared slider content must live outside the per-slide loop.
