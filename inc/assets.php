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
		'kanapka-theme-footer',
		get_theme_file_uri( '/assets/css/components/footer.css' ),
		array( 'kanapka-theme-main' ),
		kanapka_theme_asset_version( '/assets/css/components/footer.css' )
	);

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

	wp_enqueue_script(
		'kanapka-theme-quantity-control',
		get_theme_file_uri( '/assets/js/components/quantity-control.js' ),
		array(),
		kanapka_theme_asset_version( '/assets/js/components/quantity-control.js' ),
		true
	);

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
