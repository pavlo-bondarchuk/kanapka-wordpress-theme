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

## Page field ownership

- Store page-specific section content on that WordPress page through a dedicated SCF field group; reserve Theme Settings options for values shared across the site.

## CTA field structure

- Use one SCF Link field for each CTA so its label, URL and target remain atomic; normalize legacy label and URL keys until existing content is resaved.

## Page heading order

- A homepage carousel may contain only one `h1`, owned by its first slide; later slide titles use `h2`.
- Decorative labels inside header dropdowns must use neutral elements and dedicated classes, not heading tags that appear before the page heading.

## Theme icon and language ownership

- Keep theme UI icons behind one PHP helper so header and homepage components do not duplicate inline SVG paths.
- Use Ukrainian as the source language for visible theme fallback text; translation files may still contain legacy English keys, but new template strings should not rely on runtime translation for Ukrainian output.

## Compact category strip

- Do not let compact category strips auto-fit every item into one desktop row; cap the visible desktop count and scroll the remaining cards with a simple control.
- Category thumbnails with white product cutouts should sit on white square media boxes, not grey placeholders that show around the image edges.

## Homepage SEO text

- Keep long SEO copy fully present in the DOM, then visually collapse it with CSS and an explicit read-more control so the user gets a compact layout without losing crawlable text.
- Store homepage-only marketing copy in the homepage SCF group, not global theme options, and keep the block modular under the catalogue section.
- Do not reuse generic `$title` or `$text` variables inside nested repeater normalization when the parent section already uses the same variable names; benefit rows must not overwrite the section heading or body.
- The homepage SEO benefit strip should expose five visible desktop items and rely on horizontal scrolling for additional benefits instead of wrapping into a second row.
- Horizontal benefit strips with hidden overflow need a non-interactive edge fade so users can see that more items continue off-canvas.

## Homepage product sliders

- Product sliders should use lightweight native horizontal scrolling with snap points and explicit arrow controls instead of adding a carousel dependency.
- Keep the frequent-orders carousel at five visible desktop cards, then reduce visible cards through `grid-auto-columns` at breakpoints.

## Homepage turnkey services

- Homepage-only promo service cards belong in the homepage SCF group as a repeater; do not hardcode card content in the template.
- Repeater-driven sections should return before printing the section wrapper when no validated rows exist.
- Turnkey service cards should use image-backed promo tiles in a three-column desktop grid that wraps naturally for additional rows.

## Homepage client brands

- Client logos should come from the existing `brands` taxonomy and legacy brand thumbnail storage; do not add duplicate SCF fields for this data.
- The new theme must register the legacy `brands` taxonomy because the previous theme owned that registration.
- Brand logo sections should filter out terms without images and return before rendering the section wrapper when no usable logos exist.
- Use a native horizontal logo slider with explicit arrows so the brand list stays lightweight and consistent with other homepage sliders.

## Homepage order benefits

- Homepage order benefits belong in the page-level SCF group after turnkey services and should replace the old numbered "Чому клієнти" grid.
- Render six compact icon/text benefits per desktop row, 3-4 per tablet row, and one benefit per mobile scroll-snap slide.
- Keep fallback benefit content Ukrainian and render icons through the shared PHP SVG helper.

## Mobile navigation

- Mobile menus with nested content should lock the page through a body class, keep the panel fixed between the header and viewport bottom, and scroll inside the menu.
- Do not show all mobile submenus by default; add explicit arrow toggle buttons and reveal each submenu or generated mega-menu only when its parent has an open state.

## Narrow header and catalogue list cards

- In a compact header grid, make the branding track `minmax(0, 1fr)` and its image fluid; keep navigation and action controls as intrinsic fixed-size columns so they never leave the viewport.
- Do not hide cart or search merely to compensate for a non-shrinking logo; first reduce branding width, gaps and only the smallest control dimensions at the narrowest breakpoint.
- Catalogue list cards may stay horizontal on tablets, but must become one-column cards before their price, quantity and purchase action begin to compress or overflow.

## Quantity controls

- Every customer-facing product quantity input must have persistent visible minus and plus buttons; browser-native number spinners are not an acceptable primary control.
- Keep one shared quantity-step behavior that clamps against `min`, `max` and `step`, then dispatches normal input and change events so WooCommerce and custom AJAX handlers remain synchronized.
- Scale the same three-part control for product cards, quick view, mini-cart and cart forms instead of inventing unrelated quantity UI in each surface.

## WooCommerce cart ownership

- Keep shipping rates, minimum-order validation, coupons, fees and taxes in WooCommerce or their owning plugins; the theme owns only cart presentation and theme-specific convenience actions.
- Preserve the standard WooCommerce hooks and field names in cart template overrides so plugin notices, shipping methods and recalculation continue to work.
- Reuse shared theme data and components for cart benefits, icons, buttons and quantity controls instead of duplicating homepage or product UI.
- Before 768px, replace the tabular cart row with a one-column-friendly product card and move totals below the cart instead of allowing horizontal overflow.
- Cart footer actions must occupy opposite edges on desktop, and action/assurance SVGs must use the shared Lucide-backed icon helper with `flex-shrink: 0`.
- Reuse the catalogue `order-benefits` template on the cart page rather than maintaining parallel benefit markup and data mapping.
- Do not apply flex directly to a colspan cart-table cell: keep the row and cell in table layout at full width, then use a nested flex container for edge-aligned actions.
- Style the individual WooCommerce notice, not both `.woocommerce-notices-wrapper` and its child, to avoid duplicated borders and padding.
- Cart quantity changes should submit the standard cart form through debounced AJAX, replace the WooCommerce cart wrapper from the server response and refresh cart fragments. Hide `Update cart` only while this enhancement is active so the native POST remains the no-JavaScript fallback.
- After a deliberate full-cart clear, show only the standard WooCommerce empty-cart state; do not add a second success notice that repeats the same information.
- Cart shipping rows must make their table body, row and method list full-width; replacing only the `th` and `td` display values can leave the row constrained by table sizing.
- After cart AJAX succeeds, request `get_refreshed_fragments` directly and replace every returned selector so the theme header count and mini-cart markup cannot remain stale.
- Cart recommendations should reuse the shared product-card markup and slider behavior, exclude current cart products and show four cards per desktop viewport.
- Reuse the exact classic WooCommerce `You may also like&hellip;` msgid with the `woocommerce` text domain; punctuation and HTML entities are part of the translation key.

## WooCommerce checkout ownership

- Preserve the standard checkout field, order-review and payment hooks because delivery-date, minimum-order and gateway plugins depend on them.
- A theme may move the existing `.woocommerce-checkout-payment` node into a visual slot, but it must keep the same node and listen for `updated_checkout` so WooCommerce can continue replacing it through AJAX.
- Checkout review overrides must retain cart visibility/name/thumbnail/subtotal filters and all review-order hooks around products, shipping, fees, taxes and totals.
- Treat `order-received` as a separate checkout endpoint with its own page shell and assets; preserve both payment-specific and general thank-you hooks inside the redesigned content column.
