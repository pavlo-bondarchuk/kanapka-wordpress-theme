<?php
/**
 * WooCommerce integration.
 *
 * @package Kanapka_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Keep WooCommerce markup, but let the theme own all frontend presentation.
 *
 * @param array $styles Registered WooCommerce frontend styles.
 * @return array
 */
function kanapka_theme_disable_woocommerce_styles( $styles ) {
	unset( $styles['woocommerce-general'], $styles['woocommerce-layout'], $styles['woocommerce-smallscreen'] );

	return $styles;
}
add_filter( 'woocommerce_enqueue_styles', 'kanapka_theme_disable_woocommerce_styles' );

/**
 * Remove WooCommerce styles that may still be enqueued by extensions.
 */
function kanapka_theme_dequeue_woocommerce_styles() {
	wp_dequeue_style( 'woocommerce-general' );
	wp_dequeue_style( 'woocommerce-layout' );
	wp_dequeue_style( 'woocommerce-smallscreen' );
	wp_dequeue_style( 'woocommerce-inline' );
}
add_action( 'wp_enqueue_scripts', 'kanapka_theme_dequeue_woocommerce_styles', 100 );

/**
 * Remove address details that are not used by the local checkout flow.
 *
 * @param array $fields Checkout field groups.
 * @return array
 */
function kanapka_theme_remove_unused_checkout_address_fields( $fields ) {
	$unused_fields = array( 'country', 'state', 'city', 'postcode' );

	foreach ( array( 'billing', 'shipping' ) as $section ) {
		foreach ( $unused_fields as $field_name ) {
			unset( $fields[ $section ][ $section . '_' . $field_name ] );
		}

		if ( isset( $fields[ $section ] ) && ! $fields[ $section ] ) {
			unset( $fields[ $section ] );
		}
	}

	return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'kanapka_theme_remove_unused_checkout_address_fields', 999 );

/**
 * Set image dimensions that match the design card ratio.
 *
 * @param array $size Existing image size.
 * @return array
 */
function kanapka_theme_product_thumbnail_size( $size ) {
	$size['width']  = 640;
	$size['height'] = 480;
	$size['crop']   = 1;

	return $size;
}
add_filter( 'woocommerce_get_image_size_thumbnail', 'kanapka_theme_product_thumbnail_size' );

/**
 * Render one side of the shared visible quantity control.
 *
 * @param string $direction Either decrease or increase.
 * @param int    $icon_size Icon size in pixels.
 * @return string
 */
function kanapka_theme_quantity_step_button( $direction, $icon_size = 16 ) {
	$is_increase = 'increase' === $direction;
	$label       = $is_increase ? __( 'Increase quantity', 'kanapka-theme' ) : __( 'Decrease quantity', 'kanapka-theme' );
	$attribute   = $is_increase ? 'data-quantity-increase' : 'data-quantity-decrease';
	$icon        = $is_increase ? 'plus' : 'minus';

	return sprintf(
		'<button type="button" aria-label="%1$s" %2$s>%3$s</button>',
		esc_attr( $label ),
		$attribute,
		kanapka_theme_ui_icon( $icon, $icon_size )
	);
}

/** Add a visible decrease button to standard WooCommerce quantity fields. */
function kanapka_theme_before_woocommerce_quantity_input() {
	echo kanapka_theme_quantity_step_button( 'decrease', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Controlled theme markup.
}
add_action( 'woocommerce_before_quantity_input_field', 'kanapka_theme_before_woocommerce_quantity_input' );

/** Add a visible increase button to standard WooCommerce quantity fields. */
function kanapka_theme_after_woocommerce_quantity_input() {
	echo kanapka_theme_quantity_step_button( 'increase', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Controlled theme markup.
}
add_action( 'woocommerce_after_quantity_input_field', 'kanapka_theme_after_woocommerce_quantity_input' );

/**
 * Keep catalogue sorting labels short and scannable.
 *
 * @param array $options Available sorting options.
 * @return array
 */
function kanapka_theme_catalogue_orderby_labels( $options ) {
	$labels = array(
		'menu_order' => __( 'Default sorting', 'kanapka-theme' ),
		'popularity' => __( 'Most popular', 'kanapka-theme' ),
		'rating'     => __( 'Top rated', 'kanapka-theme' ),
		'date'       => __( 'Newest first', 'kanapka-theme' ),
		'price'      => __( 'Price: low to high', 'kanapka-theme' ),
		'price-desc' => __( 'Price: high to low', 'kanapka-theme' ),
	);

	foreach ( $options as $key => $label ) {
		if ( isset( $labels[ $key ] ) ) {
			$options[ $key ] = $labels[ $key ];
		}
	}

	return $options;
}
add_filter( 'woocommerce_catalog_orderby', 'kanapka_theme_catalogue_orderby_labels' );
add_filter( 'woocommerce_default_catalog_orderby_options', 'kanapka_theme_catalogue_orderby_labels' );

/**
 * Return the current product markup for the shared quick view dialog.
 */
function kanapka_theme_product_quick_view() {
	check_ajax_referer( 'kanapka_product_quick_view', 'nonce' );

	$product_id = isset( $_POST['product_id'] ) ? absint( wp_unslash( $_POST['product_id'] ) ) : 0;
	$product    = wc_get_product( $product_id );

	if ( ! $product instanceof WC_Product || ! $product->is_visible() ) {
		wp_send_json_error( array( 'message' => __( 'Product not found.', 'kanapka-theme' ) ), 404 );
	}

	$categories = wc_get_product_category_list( $product_id, ', ' );
	$description = $product->get_short_description();

	ob_start();
	?>
	<div class="product-quick-view__layout">
		<a class="product-quick-view__media" href="<?php echo esc_url( $product->get_permalink() ); ?>">
			<?php echo $product->get_image( 'large', array( 'alt' => $product->get_name(), 'sizes' => '(max-width: 640px) 92vw, 560px' ) ); ?>
		</a>
		<div class="product-quick-view__summary">
			<h2 id="product-quick-view-title"><?php echo esc_html( $product->get_name() ); ?></h2>
			<div class="product-quick-view__price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>
			<?php if ( $description ) : ?>
				<div class="product-quick-view__description"><?php echo wp_kses_post( wpautop( $description ) ); ?></div>
			<?php endif; ?>
			<?php if ( $product->is_purchasable() && $product->is_in_stock() && $product->is_type( 'simple' ) ) : ?>
				<div class="product-quick-view__cart">
					<div class="quantity-control quantity-control--quick-view" data-quantity-control>
						<?php echo kanapka_theme_quantity_step_button( 'decrease', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Controlled theme markup. ?>
						<input class="product-quick-view__quantity" type="number" min="1" step="1" value="1" aria-label="<?php esc_attr_e( 'Product quantity', 'kanapka-theme' ); ?>" data-quick-view-quantity>
						<?php echo kanapka_theme_quantity_step_button( 'increase', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Controlled theme markup. ?>
					</div>
					<a class="button add_to_cart_button product_type_simple" href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" data-quantity="1" data-product_id="<?php echo esc_attr( $product_id ); ?>" data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>" rel="nofollow" data-kanapka-add-to-cart data-quick-view-add-to-cart><span><?php echo esc_html( $product->add_to_cart_text() ); ?></span></a>
				</div>
			<?php else : ?>
				<a class="button" href="<?php echo esc_url( $product->get_permalink() ); ?>"><?php esc_html_e( 'Select options', 'kanapka-theme' ); ?></a>
			<?php endif; ?>
			<?php if ( $categories ) : ?>
				<div class="product-quick-view__categories"><strong><?php esc_html_e( 'Categories:', 'kanapka-theme' ); ?></strong> <?php echo wp_kses_post( $categories ); ?></div>
			<?php endif; ?>
			<a class="product-quick-view__details" href="<?php echo esc_url( $product->get_permalink() ); ?>"><?php esc_html_e( 'Product details', 'kanapka-theme' ); ?></a>
		</div>
	</div>
	<?php

	wp_send_json_success( array( 'html' => (string) ob_get_clean() ) );
}
add_action( 'wp_ajax_kanapka_product_quick_view', 'kanapka_theme_product_quick_view' );
add_action( 'wp_ajax_nopriv_kanapka_product_quick_view', 'kanapka_theme_product_quick_view' );

/**
 * Render one reusable quick view dialog on product listing pages.
 */
function kanapka_theme_product_quick_view_dialog() {
	if ( ! is_front_page() && ! is_product() && ( ! function_exists( 'kanapka_theme_is_catalogue_archive' ) || ! kanapka_theme_is_catalogue_archive() ) ) {
		return;
	}

	get_template_part( 'template-parts/components/product-quick-view' );
}
add_action( 'wp_footer', 'kanapka_theme_product_quick_view_dialog' );

/**
 * Add a line total beside the quantity in mini-cart items.
 *
 * @param string $quantity_html Existing quantity markup.
 * @param array  $cart_item     Cart item data.
 * @param string $cart_item_key Cart item key.
 * @return string
 */
function kanapka_theme_mini_cart_item_quantity( $quantity_html, $cart_item, $cart_item_key ) {
	$product  = isset( $cart_item['data'] ) ? $cart_item['data'] : null;
	$quantity = isset( $cart_item['quantity'] ) ? (int) $cart_item['quantity'] : 0;

	if ( ! $product instanceof WC_Product || ! WC()->cart || $quantity < 1 ) {
		return $quantity_html;
	}

	$unit_price = WC()->cart->get_product_price( $product );
	$line_total = WC()->cart->get_product_subtotal( $product, $quantity );
	$max_quantity = $product->get_max_purchase_quantity();
	$max_attribute = $max_quantity > 0 ? sprintf( ' max="%d"', $max_quantity ) : '';

	return sprintf(
		'<span class="header-mini-cart__item-meta"><span class="quantity-control quantity-control--mini-cart" data-quantity-control>%1$s<label><span class="screen-reader-text">%2$s</span><input class="header-mini-cart__quantity" type="number" min="1" step="1" value="%3$d"%4$s inputmode="numeric" data-mini-cart-quantity data-cart-item-key="%5$s" data-previous-quantity="%3$d"></label>%6$s</span><span class="header-mini-cart__unit-price">&times; %7$s</span><span class="header-mini-cart__line-total">%8$s</span></span>',
		kanapka_theme_quantity_step_button( 'decrease', 14 ),
		esc_html( sprintf( __( 'Product quantity: %s', 'kanapka-theme' ), $product->get_name() ) ),
		$quantity,
		$max_attribute,
		esc_attr( $cart_item_key ),
		kanapka_theme_quantity_step_button( 'increase', 14 ),
		wp_kses_post( $unit_price ),
		wp_kses_post( $line_total )
	);
}
add_filter( 'woocommerce_widget_cart_item_quantity', 'kanapka_theme_mini_cart_item_quantity', 10, 3 );

/**
 * Update one mini-cart item and return recalculated cart fragments.
 */
function kanapka_theme_update_mini_cart_quantity() {
	check_ajax_referer( 'kanapka_mini_cart_quantity', 'nonce' );

	$cart_item_key = isset( $_POST['cart_item_key'] ) ? wc_clean( wp_unslash( $_POST['cart_item_key'] ) ) : '';
	$quantity      = isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : 0;
	$cart_item     = WC()->cart ? WC()->cart->get_cart_item( $cart_item_key ) : null;
	$product       = is_array( $cart_item ) && isset( $cart_item['data'] ) ? $cart_item['data'] : null;

	if ( ! $product instanceof WC_Product || $quantity < 1 ) {
		wp_send_json_error( array( 'message' => __( 'Could not update the product quantity.', 'kanapka-theme' ) ), 400 );
	}

	$max_quantity = $product->get_max_purchase_quantity();

	if ( $max_quantity > 0 && $quantity > $max_quantity ) {
		$quantity = $max_quantity;
	}

	if ( ! WC()->cart->set_quantity( $cart_item_key, $quantity, true ) ) {
		wp_send_json_error( array( 'message' => __( 'Could not update the product quantity.', 'kanapka-theme' ) ), 400 );
	}

	ob_start();
	woocommerce_mini_cart();
	$mini_cart = ob_get_clean();
	$fragments = apply_filters(
		'woocommerce_add_to_cart_fragments',
		array( 'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>' )
	);

	wp_send_json_success(
		array(
			'cart_item_key' => $cart_item_key,
			'quantity'      => $quantity,
			'fragments'     => $fragments,
		)
	);
}
add_action( 'wp_ajax_kanapka_update_mini_cart_quantity', 'kanapka_theme_update_mini_cart_quantity' );
add_action( 'wp_ajax_nopriv_kanapka_update_mini_cart_quantity', 'kanapka_theme_update_mini_cart_quantity' );

/** Empty the cart from the dedicated cart action. */
function kanapka_theme_maybe_empty_cart() {
	if ( empty( $_POST['kanapka_empty_cart'] ) || empty( $_POST['woocommerce-cart-nonce'] ) ) {
		return;
	}

	$nonce = sanitize_text_field( wp_unslash( $_POST['woocommerce-cart-nonce'] ) );

	if ( ! wp_verify_nonce( $nonce, 'woocommerce-cart' ) || ! function_exists( 'WC' ) || ! WC()->cart ) {
		return;
	}

	WC()->cart->empty_cart();
	wp_safe_redirect( wc_get_cart_url() );
	exit;
}
add_action( 'wp_loaded', 'kanapka_theme_maybe_empty_cart', 20 );

/** Keep the cart body focused on products and totals from the approved layout. */
function kanapka_theme_remove_cart_cross_sells() {
	remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
}
add_action( 'wp', 'kanapka_theme_remove_cart_cross_sells' );

/**
 * Wrap a mini-cart product name for the header grid.
 *
 * @param string $product_name Product name markup.
 * @return string
 */
function kanapka_theme_mini_cart_product_name( $product_name ) {
	return '<span class="header-mini-cart__product-name">' . wp_kses_post( $product_name ) . '</span>';
}

/**
 * Add a visible label to the header mini-cart remove control.
 *
 * @param string $remove_link Remove-link markup.
 * @return string
 */
function kanapka_theme_mini_cart_remove_link( $remove_link ) {
	$remove_content = sprintf(
		'<span class="header-mini-cart__remove-label">%1$s</span><span aria-hidden="true">&times;</span>',
		esc_html__( 'Remove', 'kanapka-theme' )
	);

	return str_replace( '&times;</a>', $remove_content . '</a>', $remove_link );
}

/**
 * Keep the complete mini-cart popup synchronized after AJAX cart updates.
 *
 * @param array $fragments WooCommerce fragments.
 * @return array
 */
function kanapka_theme_mini_cart_fragments( $fragments ) {
	ob_start();
	get_template_part( 'template-parts/header/cart-link' );
	$fragments['div.kanapka-mini-cart-fragment'] = (string) ob_get_clean();

	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'kanapka_theme_mini_cart_fragments' );
