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
