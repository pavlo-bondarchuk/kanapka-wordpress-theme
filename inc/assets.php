<?php
/**
 * Asset loading.
 *
 * @package Kanapka_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Return a cache-safe asset version.
 *
 * @param string $relative_path Theme-relative file path.
 * @return string
 */
function kanapka_theme_asset_version( $relative_path ) {
	$file = get_theme_file_path( $relative_path );

	return file_exists( $file ) ? (string) filemtime( $file ) : wp_get_theme()->get( 'Version' );
}

/**
 * Preload the responsive image used by the first homepage hero slide.
 *
 * @param array $resources Preload resources.
 * @return array
 */
function kanapka_theme_preload_home_hero_image( $resources ) {
	if ( ! is_front_page() ) {
		return $resources;
	}

	$slides   = kanapka_theme_get_home_hero_slides();
	$image_id = absint( $slides[0]['image_id'] ?? 0 );

	if ( ! $image_id ) {
		return $resources;
	}

	$mobile_image   = wp_get_attachment_image_src( $image_id, 'kanapka-hero-mobile' );
	$mobile_srcset  = wp_get_attachment_image_srcset( $image_id, 'kanapka-hero-mobile' );
	$desktop_image  = wp_get_attachment_image_src( $image_id, 'kanapka-hero' );
	$desktop_srcset = wp_get_attachment_image_srcset( $image_id, 'kanapka-hero' );

	if ( $mobile_image ) {
		$mobile_resource = array(
			'href'          => $mobile_image[0],
			'as'            => 'image',
			'media'         => '(max-width: 48rem)',
			'fetchpriority' => 'high',
		);

		if ( $mobile_srcset ) {
			$mobile_resource['imagesrcset'] = $mobile_srcset;
			$mobile_resource['imagesizes']  = '100vw';
		}

		$resources[] = $mobile_resource;
	}

	if ( $desktop_image ) {
		$desktop_resource = array(
			'href'          => $desktop_image[0],
			'as'            => 'image',
			'media'         => '(min-width: 48.0625rem)',
			'fetchpriority' => 'high',
		);

		if ( $desktop_srcset ) {
			$desktop_resource['imagesrcset'] = $desktop_srcset;
			$desktop_resource['imagesizes']  = '100vw';
		}

		$resources[] = $desktop_resource;
	}

	return $resources;
}
add_filter( 'wp_preload_resources', 'kanapka_theme_preload_home_hero_image' );

/**
 * Enqueue only the assets required by the current view.
 */
function kanapka_theme_enqueue_assets() {
	wp_enqueue_style(
		'kanapka-theme-main',
		get_theme_file_uri( '/assets/css/main.css' ),
		array(),
		kanapka_theme_asset_version( '/assets/css/main.css' )
	);

	wp_enqueue_style(
		'kanapka-theme-header',
		get_theme_file_uri( '/assets/css/components/header.css' ),
		array( 'kanapka-theme-main' ),
		kanapka_theme_asset_version( '/assets/css/components/header.css' )
	);

	wp_enqueue_style(
		'kanapka-theme-contact-form-7',
		get_theme_file_uri( '/assets/css/components/contact-form-7.css' ),
		array( 'kanapka-theme-main' ),
		kanapka_theme_asset_version( '/assets/css/components/contact-form-7.css' )
	);

	wp_enqueue_style(
		'kanapka-theme-footer',
		get_theme_file_uri( '/assets/css/components/footer.css' ),
		array( 'kanapka-theme-main' ),
		kanapka_theme_asset_version( '/assets/css/components/footer.css' )
	);

	if ( is_404() ) {
		wp_enqueue_style(
			'kanapka-theme-front-page',
			get_theme_file_uri( '/assets/css/pages/front-page.css' ),
			array( 'kanapka-theme-main' ),
			kanapka_theme_asset_version( '/assets/css/pages/front-page.css' )
		);

		wp_enqueue_style(
			'kanapka-theme-404',
			get_theme_file_uri( '/assets/css/pages/404.css' ),
			array( 'kanapka-theme-front-page' ),
			kanapka_theme_asset_version( '/assets/css/pages/404.css' )
		);
	}

	wp_enqueue_script(
		'kanapka-theme-navigation',
		get_theme_file_uri( '/assets/js/components/navigation.js' ),
		array(),
		kanapka_theme_asset_version( '/assets/js/components/navigation.js' ),
		true
	);

	wp_localize_script(
		'kanapka-theme-navigation',
		'kanapkaNavigation',
		array(
			'openSubmenuLabel'  => __( 'Open submenu', 'kanapka-theme' ),
			'closeSubmenuLabel' => __( 'Close submenu', 'kanapka-theme' ),
		)
	);

	wp_enqueue_script(
		'kanapka-theme-mega-menu',
		get_theme_file_uri( '/assets/js/components/mega-menu.js' ),
		array(),
		kanapka_theme_asset_version( '/assets/js/components/mega-menu.js' ),
		true
	);

	wp_enqueue_script(
		'kanapka-theme-header-actions',
		get_theme_file_uri( '/assets/js/components/header-actions.js' ),
		array(),
		kanapka_theme_asset_version( '/assets/js/components/header-actions.js' ),
		true
	);

	$callback_form_id = absint( kanapka_theme_get_option( 'kanapka_header_callback_form', 0 ) );

	if ( $callback_form_id && 'wpcf7_contact_form' === get_post_type( $callback_form_id ) && shortcode_exists( 'contact-form-7' ) ) {
		wp_enqueue_script(
			'kanapka-theme-callback-modal',
			get_theme_file_uri( '/assets/js/components/callback-modal.js' ),
			array(),
			kanapka_theme_asset_version( '/assets/js/components/callback-modal.js' ),
			true
		);
	}

	wp_enqueue_script(
		'kanapka-theme-quantity-control',
		get_theme_file_uri( '/assets/js/components/quantity-control.js' ),
		array(),
		kanapka_theme_asset_version( '/assets/js/components/quantity-control.js' ),
		true
	);

	if ( is_page() && ! is_page_template() ) {
		wp_enqueue_style(
			'kanapka-theme-default-page',
			get_theme_file_uri( '/assets/css/pages/default-page.css' ),
			array( 'kanapka-theme-main' ),
			kanapka_theme_asset_version( '/assets/css/pages/default-page.css' )
		);
	}

	wp_localize_script(
		'kanapka-theme-header-actions',
		'kanapkaCartActions',
		array(
			'ajaxUrl'      => admin_url( 'admin-ajax.php' ),
			'wcAjaxUrl'    => class_exists( 'WC_AJAX' ) ? WC_AJAX::get_endpoint( '%%endpoint%%' ) : '',
			'quantityNonce' => wp_create_nonce( 'kanapka_mini_cart_quantity' ),
			'loaderIcon'   => kanapka_theme_ui_icon( 'loader-circle', 16 ),
		)
	);

	wp_localize_script(
		'kanapka-theme-header-actions',
		'kanapkaHeaderSearch',
		array(
			'ajaxUrl'        => admin_url( 'admin-ajax.php' ),
			'nonce'          => wp_create_nonce( 'kanapka_product_search' ),
			'minimumLength'  => 2,
			'noResultsLabel' => __( 'No matching products found', 'kanapka-theme' ),
		)
	);

	if ( is_front_page() ) {
		wp_enqueue_style(
			'kanapka-theme-front-page',
			get_theme_file_uri( '/assets/css/pages/front-page.css' ),
			array( 'kanapka-theme-main' ),
			kanapka_theme_asset_version( '/assets/css/pages/front-page.css' )
		);

		wp_enqueue_style(
			'kanapka-theme-product-quick-view',
			get_theme_file_uri( '/assets/css/components/product-quick-view.css' ),
			array( 'kanapka-theme-front-page' ),
			kanapka_theme_asset_version( '/assets/css/components/product-quick-view.css' )
		);

		wp_enqueue_script(
			'kanapka-theme-hero-slider',
			get_theme_file_uri( '/assets/js/components/hero-slider.js' ),
			array(),
			kanapka_theme_asset_version( '/assets/js/components/hero-slider.js' ),
			true
		);

		wp_enqueue_script(
			'kanapka-theme-category-strip',
			get_theme_file_uri( '/assets/js/components/category-strip.js' ),
			array(),
			kanapka_theme_asset_version( '/assets/js/components/category-strip.js' ),
			true
		);

		wp_enqueue_script(
			'kanapka-theme-home-seo',
			get_theme_file_uri( '/assets/js/components/home-seo.js' ),
			array(),
			kanapka_theme_asset_version( '/assets/js/components/home-seo.js' ),
			true
		);

		wp_localize_script(
			'kanapka-theme-home-seo',
			'kanapkaHomeSeo',
			array(
				'showMoreLabel' => __( 'Show more', 'kanapka-theme' ),
				'showLessLabel' => __( 'Show less', 'kanapka-theme' ),
			)
		);

		wp_enqueue_script(
			'kanapka-theme-product-slider',
			get_theme_file_uri( '/assets/js/components/product-slider.js' ),
			array(),
			kanapka_theme_asset_version( '/assets/js/components/product-slider.js' ),
			true
		);

		wp_enqueue_script(
			'kanapka-theme-logo-slider',
			get_theme_file_uri( '/assets/js/components/logo-slider.js' ),
			array(),
			kanapka_theme_asset_version( '/assets/js/components/logo-slider.js' ),
			true
		);

		wp_enqueue_script(
			'kanapka-theme-order-benefits',
			get_theme_file_uri( '/assets/js/components/order-benefits.js' ),
			array(),
			kanapka_theme_asset_version( '/assets/js/components/order-benefits.js' ),
			true
		);

		wp_enqueue_script(
			'kanapka-theme-product-quick-view',
			get_theme_file_uri( '/assets/js/components/product-quick-view.js' ),
			array(),
			kanapka_theme_asset_version( '/assets/js/components/product-quick-view.js' ),
			true
		);

		wp_localize_script( 'kanapka-theme-product-quick-view', 'kanapkaQuickView', kanapka_theme_quick_view_script_data() );
	}

	if ( function_exists( 'is_woocommerce' ) && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) {
		wp_enqueue_style(
			'kanapka-theme-woocommerce',
			get_theme_file_uri( '/assets/css/woocommerce/base.css' ),
			array( 'kanapka-theme-main' ),
			kanapka_theme_asset_version( '/assets/css/woocommerce/base.css' )
		);
	}

	if ( function_exists( 'is_cart' ) && is_cart() ) {
		wp_enqueue_style(
			'kanapka-theme-front-page',
			get_theme_file_uri( '/assets/css/pages/front-page.css' ),
			array( 'kanapka-theme-main', 'kanapka-theme-woocommerce' ),
			kanapka_theme_asset_version( '/assets/css/pages/front-page.css' )
		);

		wp_enqueue_style(
			'kanapka-theme-cart',
			get_theme_file_uri( '/assets/css/woocommerce/cart.css' ),
			array( 'kanapka-theme-front-page' ),
			kanapka_theme_asset_version( '/assets/css/woocommerce/cart.css' )
		);

		wp_enqueue_style(
			'kanapka-theme-order-benefits',
			get_theme_file_uri( '/assets/css/components/order-benefits.css' ),
			array( 'kanapka-theme-main' ),
			kanapka_theme_asset_version( '/assets/css/components/order-benefits.css' )
		);

		wp_enqueue_script(
			'kanapka-theme-order-benefits',
			get_theme_file_uri( '/assets/js/components/order-benefits.js' ),
			array(),
			kanapka_theme_asset_version( '/assets/js/components/order-benefits.js' ),
			true
		);

		wp_enqueue_script(
			'kanapka-theme-product-slider',
			get_theme_file_uri( '/assets/js/components/product-slider.js' ),
			array(),
			kanapka_theme_asset_version( '/assets/js/components/product-slider.js' ),
			true
		);

		wp_enqueue_script(
			'kanapka-theme-cart',
			get_theme_file_uri( '/assets/js/components/cart.js' ),
			array( 'kanapka-theme-quantity-control' ),
			kanapka_theme_asset_version( '/assets/js/components/cart.js' ),
			true
		);

	}

	if ( function_exists( 'is_checkout' ) && is_checkout() && ( ! function_exists( 'is_wc_endpoint_url' ) || ! is_wc_endpoint_url() ) ) {
		wp_enqueue_style(
			'kanapka-theme-front-page',
			get_theme_file_uri( '/assets/css/pages/front-page.css' ),
			array( 'kanapka-theme-main', 'kanapka-theme-woocommerce' ),
			kanapka_theme_asset_version( '/assets/css/pages/front-page.css' )
		);

		wp_enqueue_style(
			'kanapka-theme-checkout',
			get_theme_file_uri( '/assets/css/woocommerce/checkout.css' ),
			array( 'kanapka-theme-front-page' ),
			kanapka_theme_asset_version( '/assets/css/woocommerce/checkout.css' )
		);

		wp_enqueue_style(
			'kanapka-theme-order-benefits',
			get_theme_file_uri( '/assets/css/components/order-benefits.css' ),
			array( 'kanapka-theme-checkout' ),
			kanapka_theme_asset_version( '/assets/css/components/order-benefits.css' )
		);

		wp_enqueue_script(
			'kanapka-theme-order-benefits',
			get_theme_file_uri( '/assets/js/components/order-benefits.js' ),
			array(),
			kanapka_theme_asset_version( '/assets/js/components/order-benefits.js' ),
			true
		);

		wp_enqueue_script(
			'kanapka-theme-checkout',
			get_theme_file_uri( '/assets/js/components/checkout.js' ),
			array( 'jquery', 'wc-checkout' ),
			kanapka_theme_asset_version( '/assets/js/components/checkout.js' ),
			true
		);

	}

	if ( function_exists( 'is_wc_endpoint_url' ) && is_wc_endpoint_url( 'order-received' ) ) {
		wp_enqueue_style(
			'kanapka-theme-order-benefits',
			get_theme_file_uri( '/assets/css/components/order-benefits.css' ),
			array( 'kanapka-theme-main' ),
			kanapka_theme_asset_version( '/assets/css/components/order-benefits.css' )
		);

		wp_enqueue_style(
			'kanapka-theme-order-received',
			get_theme_file_uri( '/assets/css/woocommerce/order-received.css' ),
			array( 'kanapka-theme-main', 'kanapka-theme-woocommerce', 'kanapka-theme-order-benefits' ),
			kanapka_theme_asset_version( '/assets/css/woocommerce/order-received.css' )
		);

		wp_enqueue_script(
			'kanapka-theme-order-benefits',
			get_theme_file_uri( '/assets/js/components/order-benefits.js' ),
			array(),
			kanapka_theme_asset_version( '/assets/js/components/order-benefits.js' ),
			true
		);

	}

	if ( function_exists( 'kanapka_theme_is_catalogue_archive' ) && kanapka_theme_is_catalogue_archive() ) {
		wp_enqueue_style(
			'kanapka-theme-shop',
			get_theme_file_uri( '/assets/css/woocommerce/shop.css' ),
			array( 'kanapka-theme-main', 'kanapka-theme-woocommerce' ),
			kanapka_theme_asset_version( '/assets/css/woocommerce/shop.css' )
		);

		wp_enqueue_style(
			'kanapka-theme-product-quick-view',
			get_theme_file_uri( '/assets/css/components/product-quick-view.css' ),
			array( 'kanapka-theme-shop' ),
			kanapka_theme_asset_version( '/assets/css/components/product-quick-view.css' )
		);

		wp_enqueue_script(
			'kanapka-theme-category-strip',
			get_theme_file_uri( '/assets/js/components/category-strip.js' ),
			array(),
			kanapka_theme_asset_version( '/assets/js/components/category-strip.js' ),
			true
		);

		wp_enqueue_script(
			'kanapka-theme-product-slider',
			get_theme_file_uri( '/assets/js/components/product-slider.js' ),
			array(),
			kanapka_theme_asset_version( '/assets/js/components/product-slider.js' ),
			true
		);

		wp_enqueue_script(
			'kanapka-theme-logo-slider',
			get_theme_file_uri( '/assets/js/components/logo-slider.js' ),
			array(),
			kanapka_theme_asset_version( '/assets/js/components/logo-slider.js' ),
			true
		);

		wp_enqueue_script(
			'kanapka-theme-order-benefits',
			get_theme_file_uri( '/assets/js/components/order-benefits.js' ),
			array(),
			kanapka_theme_asset_version( '/assets/js/components/order-benefits.js' ),
			true
		);

		wp_enqueue_script(
			'kanapka-theme-product-quick-view',
			get_theme_file_uri( '/assets/js/components/product-quick-view.js' ),
			array(),
			kanapka_theme_asset_version( '/assets/js/components/product-quick-view.js' ),
			true
		);

		wp_localize_script( 'kanapka-theme-product-quick-view', 'kanapkaQuickView', kanapka_theme_quick_view_script_data() );

		wp_enqueue_script(
			'kanapka-theme-catalogue-sidebar',
			get_theme_file_uri( '/assets/js/components/catalogue-sidebar.js' ),
			array(),
			kanapka_theme_asset_version( '/assets/js/components/catalogue-sidebar.js' ),
			true
		);

		wp_enqueue_script(
			'kanapka-theme-catalogue-view',
			get_theme_file_uri( '/assets/js/components/catalogue-view.js' ),
			array(),
			kanapka_theme_asset_version( '/assets/js/components/catalogue-view.js' ),
			true
		);
	}

	if ( function_exists( 'kanapka_theme_is_product_search' ) && kanapka_theme_is_product_search() ) {
		wp_enqueue_style(
			'kanapka-theme-product-search',
			get_theme_file_uri( '/assets/css/pages/product-search.css' ),
			array( 'kanapka-theme-main', 'kanapka-theme-shop' ),
			kanapka_theme_asset_version( '/assets/css/pages/product-search.css' )
		);
	}

	if ( is_page_template( 'page-templates/delivery-payment.php' ) ) {
		wp_enqueue_style(
			'kanapka-theme-woocommerce',
			get_theme_file_uri( '/assets/css/woocommerce/base.css' ),
			array( 'kanapka-theme-main' ),
			kanapka_theme_asset_version( '/assets/css/woocommerce/base.css' )
		);

		wp_enqueue_style(
			'kanapka-theme-shop',
			get_theme_file_uri( '/assets/css/woocommerce/shop.css' ),
			array( 'kanapka-theme-main', 'kanapka-theme-woocommerce' ),
			kanapka_theme_asset_version( '/assets/css/woocommerce/shop.css' )
		);

		wp_enqueue_style(
			'kanapka-theme-order-benefits',
			get_theme_file_uri( '/assets/css/components/order-benefits.css' ),
			array( 'kanapka-theme-main' ),
			kanapka_theme_asset_version( '/assets/css/components/order-benefits.css' )
		);

		wp_enqueue_style(
			'kanapka-theme-delivery-payment',
			get_theme_file_uri( '/assets/css/pages/delivery-payment.css' ),
			array( 'kanapka-theme-shop', 'kanapka-theme-order-benefits' ),
			kanapka_theme_asset_version( '/assets/css/pages/delivery-payment.css' )
		);

		wp_enqueue_script(
			'kanapka-theme-order-benefits',
			get_theme_file_uri( '/assets/js/components/order-benefits.js' ),
			array(),
			kanapka_theme_asset_version( '/assets/js/components/order-benefits.js' ),
			true
		);

		wp_enqueue_script(
			'kanapka-theme-catalogue-sidebar',
			get_theme_file_uri( '/assets/js/components/catalogue-sidebar.js' ),
			array(),
			kanapka_theme_asset_version( '/assets/js/components/catalogue-sidebar.js' ),
			true
		);
	}

	if ( is_page_template( 'page-templates/promotions.php' ) ) {
		wp_enqueue_style(
			'kanapka-theme-order-benefits',
			get_theme_file_uri( '/assets/css/components/order-benefits.css' ),
			array( 'kanapka-theme-main' ),
			kanapka_theme_asset_version( '/assets/css/components/order-benefits.css' )
		);

		wp_enqueue_style(
			'kanapka-theme-promotions',
			get_theme_file_uri( '/assets/css/pages/promotions.css' ),
			array( 'kanapka-theme-order-benefits' ),
			kanapka_theme_asset_version( '/assets/css/pages/promotions.css' )
		);

		wp_enqueue_script(
			'kanapka-theme-order-benefits',
			get_theme_file_uri( '/assets/js/components/order-benefits.js' ),
			array(),
			kanapka_theme_asset_version( '/assets/js/components/order-benefits.js' ),
			true
		);
	}

	if ( is_page_template( 'page-templates/simple-information.php' ) ) {
		$has_simple_products = function_exists( 'get_field' ) && get_field( 'kanapka_simple_page_products_enabled' );

		wp_enqueue_style(
			'kanapka-theme-order-benefits',
			get_theme_file_uri( '/assets/css/components/order-benefits.css' ),
			array( 'kanapka-theme-main' ),
			kanapka_theme_asset_version( '/assets/css/components/order-benefits.css' )
		);

		if ( $has_simple_products ) {
			wp_enqueue_style(
				'kanapka-theme-front-page',
				get_theme_file_uri( '/assets/css/pages/front-page.css' ),
				array( 'kanapka-theme-main' ),
				kanapka_theme_asset_version( '/assets/css/pages/front-page.css' )
			);

			wp_enqueue_style(
				'kanapka-theme-product-quick-view',
				get_theme_file_uri( '/assets/css/components/product-quick-view.css' ),
				array( 'kanapka-theme-front-page' ),
				kanapka_theme_asset_version( '/assets/css/components/product-quick-view.css' )
			);
		}

		wp_enqueue_style(
			'kanapka-theme-simple-information',
			get_theme_file_uri( '/assets/css/pages/simple-information.css' ),
			array( 'kanapka-theme-order-benefits' ),
			kanapka_theme_asset_version( '/assets/css/pages/simple-information.css' )
		);

		wp_enqueue_script( 'kanapka-theme-order-benefits', get_theme_file_uri( '/assets/js/components/order-benefits.js' ), array(), kanapka_theme_asset_version( '/assets/js/components/order-benefits.js' ), true );
		wp_enqueue_script( 'kanapka-theme-catering-gallery', get_theme_file_uri( '/assets/js/components/catering-gallery.js' ), array(), kanapka_theme_asset_version( '/assets/js/components/catering-gallery.js' ), true );

		if ( $has_simple_products ) {
			wp_enqueue_script( 'kanapka-theme-product-slider', get_theme_file_uri( '/assets/js/components/product-slider.js' ), array(), kanapka_theme_asset_version( '/assets/js/components/product-slider.js' ), true );
			wp_enqueue_script( 'kanapka-theme-product-quick-view', get_theme_file_uri( '/assets/js/components/product-quick-view.js' ), array(), kanapka_theme_asset_version( '/assets/js/components/product-quick-view.js' ), true );
			wp_localize_script( 'kanapka-theme-product-quick-view', 'kanapkaQuickView', kanapka_theme_quick_view_script_data() );
		}
	}

	if ( is_page_template( 'page-templates/reviews.php' ) ) {
		wp_enqueue_style( 'kanapka-theme-order-benefits', get_theme_file_uri( '/assets/css/components/order-benefits.css' ), array( 'kanapka-theme-main' ), kanapka_theme_asset_version( '/assets/css/components/order-benefits.css' ) );
		wp_enqueue_style( 'kanapka-theme-reviews', get_theme_file_uri( '/assets/css/pages/reviews.css' ), array( 'kanapka-theme-order-benefits' ), kanapka_theme_asset_version( '/assets/css/pages/reviews.css' ) );
		wp_enqueue_script( 'comment-reply' );
		wp_enqueue_script( 'kanapka-theme-reviews', get_theme_file_uri( '/assets/js/components/reviews.js' ), array( 'comment-reply' ), kanapka_theme_asset_version( '/assets/js/components/reviews.js' ), true );
		wp_enqueue_script( 'kanapka-theme-order-benefits', get_theme_file_uri( '/assets/js/components/order-benefits.js' ), array(), kanapka_theme_asset_version( '/assets/js/components/order-benefits.js' ), true );
	}

	if ( is_page_template( 'page-templates/catering-service.php' ) ) {
		$has_catering_products = function_exists( 'get_field' ) && get_field( 'kanapka_catering_recommended_enabled' ) && get_field( 'kanapka_catering_recommended_products' );

		if ( $has_catering_products ) {
			wp_enqueue_style(
				'kanapka-theme-front-page',
				get_theme_file_uri( '/assets/css/pages/front-page.css' ),
				array( 'kanapka-theme-main' ),
				kanapka_theme_asset_version( '/assets/css/pages/front-page.css' )
			);

			wp_enqueue_style(
				'kanapka-theme-product-quick-view',
				get_theme_file_uri( '/assets/css/components/product-quick-view.css' ),
				array( 'kanapka-theme-front-page' ),
				kanapka_theme_asset_version( '/assets/css/components/product-quick-view.css' )
			);
		}

		wp_enqueue_style(
			'kanapka-theme-order-benefits',
			get_theme_file_uri( '/assets/css/components/order-benefits.css' ),
			array( 'kanapka-theme-main' ),
			kanapka_theme_asset_version( '/assets/css/components/order-benefits.css' )
		);

		wp_enqueue_style(
			'kanapka-theme-catering',
			get_theme_file_uri( '/assets/css/pages/catering.css' ),
			array( 'kanapka-theme-order-benefits' ),
			kanapka_theme_asset_version( '/assets/css/pages/catering.css' )
		);

		wp_enqueue_script(
			'kanapka-theme-order-benefits',
			get_theme_file_uri( '/assets/js/components/order-benefits.js' ),
			array(),
			kanapka_theme_asset_version( '/assets/js/components/order-benefits.js' ),
			true
		);

		wp_enqueue_script(
			'kanapka-theme-catering-gallery',
			get_theme_file_uri( '/assets/js/components/catering-gallery.js' ),
			array(),
			kanapka_theme_asset_version( '/assets/js/components/catering-gallery.js' ),
			true
		);

		wp_enqueue_script(
			'kanapka-theme-catering-hero-slider',
			get_theme_file_uri( '/assets/js/components/catering-hero-slider.js' ),
			array(),
			kanapka_theme_asset_version( '/assets/js/components/catering-hero-slider.js' ),
			true
		);

		if ( $has_catering_products ) {
			wp_enqueue_script(
				'kanapka-theme-product-slider',
				get_theme_file_uri( '/assets/js/components/product-slider.js' ),
				array(),
				kanapka_theme_asset_version( '/assets/js/components/product-slider.js' ),
				true
			);

			wp_enqueue_script(
				'kanapka-theme-product-quick-view',
				get_theme_file_uri( '/assets/js/components/product-quick-view.js' ),
				array(),
				kanapka_theme_asset_version( '/assets/js/components/product-quick-view.js' ),
				true
			);
			wp_localize_script( 'kanapka-theme-product-quick-view', 'kanapkaQuickView', kanapka_theme_quick_view_script_data() );
		}
	}

	if ( function_exists( 'is_product' ) && is_product() ) {
		wp_enqueue_style(
			'kanapka-theme-front-page',
			get_theme_file_uri( '/assets/css/pages/front-page.css' ),
			array( 'kanapka-theme-main', 'kanapka-theme-woocommerce' ),
			kanapka_theme_asset_version( '/assets/css/pages/front-page.css' )
		);

		wp_enqueue_style(
			'kanapka-theme-single-product',
			get_theme_file_uri( '/assets/css/woocommerce/single-product.css' ),
			array( 'kanapka-theme-front-page' ),
			kanapka_theme_asset_version( '/assets/css/woocommerce/single-product.css' )
		);

		wp_enqueue_style(
			'kanapka-theme-product-quick-view',
			get_theme_file_uri( '/assets/css/components/product-quick-view.css' ),
			array( 'kanapka-theme-single-product' ),
			kanapka_theme_asset_version( '/assets/css/components/product-quick-view.css' )
		);

		foreach ( array( 'product-slider', 'logo-slider', 'order-benefits' ) as $component ) {
			wp_enqueue_script(
				'kanapka-theme-' . $component,
				get_theme_file_uri( '/assets/js/components/' . $component . '.js' ),
				array(),
				kanapka_theme_asset_version( '/assets/js/components/' . $component . '.js' ),
				true
			);
		}

		wp_enqueue_script(
			'kanapka-theme-product-quick-view',
			get_theme_file_uri( '/assets/js/components/product-quick-view.js' ),
			array(),
			kanapka_theme_asset_version( '/assets/js/components/product-quick-view.js' ),
			true
		);
		wp_localize_script( 'kanapka-theme-product-quick-view', 'kanapkaQuickView', kanapka_theme_quick_view_script_data() );

		wp_enqueue_script(
			'kanapka-theme-single-product',
			get_theme_file_uri( '/assets/js/components/single-product.js' ),
			array(),
			kanapka_theme_asset_version( '/assets/js/components/single-product.js' ),
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', 'kanapka_theme_enqueue_assets' );

/**
 * Data shared by the quick view script on every product listing surface.
 *
 * @return array
 */
function kanapka_theme_quick_view_script_data() {
	return array(
		'ajaxUrl'      => admin_url( 'admin-ajax.php' ),
		'wcAjaxUrl'    => class_exists( 'WC_AJAX' ) ? WC_AJAX::get_endpoint( '%%endpoint%%' ) : '',
		'nonce'        => wp_create_nonce( 'kanapka_product_quick_view' ),
		'loadingLabel' => __( 'Loading…', 'kanapka-theme' ),
		'errorLabel'   => __( 'Could not load the product.', 'kanapka-theme' ),
		'addedLabel'   => __( 'Added to cart', 'kanapka-theme' ),
		'loadingIcon'  => kanapka_theme_ui_icon( 'loader-circle', 18 ),
		'successIcon'  => kanapka_theme_ui_icon( 'circle-check', 18 ),
	);
}
