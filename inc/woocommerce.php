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
 * Keep catalogue sorting labels short and scannable.
 *
 * @param array $options Available sorting options.
 * @return array
 */
function kanapka_theme_catalogue_orderby_labels( $options ) {
	$labels = array(
		'menu_order' => __( 'За замовчуванням', 'kanapka-theme' ),
		'popularity' => __( 'За популярністю', 'kanapka-theme' ),
		'rating'     => __( 'За рейтингом', 'kanapka-theme' ),
		'date'       => __( 'За новизною', 'kanapka-theme' ),
		'price'      => __( 'За зростанням ціни', 'kanapka-theme' ),
		'price-desc' => __( 'За спаданням ціни', 'kanapka-theme' ),
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
		wp_send_json_error( array( 'message' => __( 'Товар не знайдено.', 'kanapka-theme' ) ), 404 );
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
					<input class="product-quick-view__quantity" type="number" min="1" step="1" value="1" aria-label="<?php esc_attr_e( 'Кількість товару', 'kanapka-theme' ); ?>" data-quick-view-quantity>
					<a class="button add_to_cart_button ajax_add_to_cart product_type_simple" href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" data-quantity="1" data-product_id="<?php echo esc_attr( $product_id ); ?>" data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>" rel="nofollow" data-quick-view-add-to-cart><?php echo esc_html( $product->add_to_cart_text() ); ?></a>
				</div>
			<?php else : ?>
				<a class="button" href="<?php echo esc_url( $product->get_permalink() ); ?>"><?php esc_html_e( 'Обрати варіант', 'kanapka-theme' ); ?></a>
			<?php endif; ?>
			<?php if ( $categories ) : ?>
				<div class="product-quick-view__categories"><strong><?php esc_html_e( 'Категорії:', 'kanapka-theme' ); ?></strong> <?php echo wp_kses_post( $categories ); ?></div>
			<?php endif; ?>
			<a class="product-quick-view__details" href="<?php echo esc_url( $product->get_permalink() ); ?>"><?php esc_html_e( 'Детальніше про товар', 'kanapka-theme' ); ?></a>
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
	if ( ! is_front_page() && ( ! function_exists( 'kanapka_theme_is_catalogue_archive' ) || ! kanapka_theme_is_catalogue_archive() ) ) {
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
 * @return string
 */
function kanapka_theme_mini_cart_item_quantity( $quantity_html, $cart_item ) {
	$product  = isset( $cart_item['data'] ) ? $cart_item['data'] : null;
	$quantity = isset( $cart_item['quantity'] ) ? (int) $cart_item['quantity'] : 0;

	if ( ! $product instanceof WC_Product || ! WC()->cart || $quantity < 1 ) {
		return $quantity_html;
	}

	$unit_price = WC()->cart->get_product_price( $product );
	$line_total = WC()->cart->get_product_subtotal( $product, $quantity );

	return sprintf(
		'<span class="header-mini-cart__item-meta"><span class="quantity">%1$s &times; %2$s</span><span class="header-mini-cart__line-total">%3$s</span></span>',
		esc_html( number_format_i18n( $quantity ) ),
		wp_kses_post( $unit_price ),
		wp_kses_post( $line_total )
	);
}
add_filter( 'woocommerce_widget_cart_item_quantity', 'kanapka_theme_mini_cart_item_quantity', 10, 2 );

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
		esc_html__( 'Видалити', 'kanapka-theme' )
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
