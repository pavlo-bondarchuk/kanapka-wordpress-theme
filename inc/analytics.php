<?php
/**
 * Theme analytics and advertising integrations.
 *
 * @package Kanapka_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Read a raw SCF option while preserving a deliberately disabled false value.
 *
 * @param string $name    Field name.
 * @param mixed  $default Fallback used before the option is saved.
 * @return mixed
 */
function kanapka_theme_analytics_option( $name, $default = '' ) {
	$value = get_option( 'options_' . $name, null );

	return null === $value ? $default : $value;
}

/**
 * Read and validate analytics settings from the theme options page.
 *
 * @return array<string, string|bool>
 */
function kanapka_theme_analytics_settings() {
	$settings = array(
		'gtm_enabled'        => (bool) kanapka_theme_analytics_option( 'kanapka_gtm_enabled', false ),
		'gtm_id'             => strtoupper( trim( (string) kanapka_theme_analytics_option( 'kanapka_gtm_id', '' ) ) ),
		'ga4_enabled'        => (bool) kanapka_theme_analytics_option( 'kanapka_ga4_enabled', false ),
		'ga4_id'             => strtoupper( trim( (string) kanapka_theme_analytics_option( 'kanapka_ga4_id', '' ) ) ),
		'ua_enabled'         => (bool) kanapka_theme_analytics_option( 'kanapka_ua_enabled', true ),
		'ua_id'              => strtoupper( trim( (string) kanapka_theme_analytics_option( 'kanapka_ua_id', 'UA-58514353-1' ) ) ),
		'google_ads_enabled' => (bool) kanapka_theme_analytics_option( 'kanapka_google_ads_enabled', true ),
		'google_ads_id'      => trim( (string) kanapka_theme_analytics_option( 'kanapka_google_ads_id', '967972312' ) ),
		'meta_enabled'       => (bool) kanapka_theme_analytics_option( 'kanapka_meta_pixel_enabled', true ),
		'meta_id'            => trim( (string) kanapka_theme_analytics_option( 'kanapka_meta_pixel_id', '1730975970565201' ) ),
		'yandex_enabled'     => (bool) kanapka_theme_analytics_option( 'kanapka_yandex_metrika_enabled', true ),
		'yandex_id'          => trim( (string) kanapka_theme_analytics_option( 'kanapka_yandex_metrika_id', '27891162' ) ),
	);

	$settings['gtm_id']        = preg_match( '/^GTM-[A-Z0-9]+$/', $settings['gtm_id'] ) ? $settings['gtm_id'] : '';
	$settings['ga4_id']        = preg_match( '/^G-[A-Z0-9]+$/', $settings['ga4_id'] ) ? $settings['ga4_id'] : '';
	$settings['ua_id']         = preg_match( '/^UA-[0-9]+-[0-9]+$/', $settings['ua_id'] ) ? $settings['ua_id'] : '';
	$settings['google_ads_id'] = preg_match( '/^[0-9]+$/', $settings['google_ads_id'] ) ? $settings['google_ads_id'] : '';
	$settings['meta_id']       = preg_match( '/^[0-9]+$/', $settings['meta_id'] ) ? $settings['meta_id'] : '';
	$settings['yandex_id']     = preg_match( '/^[0-9]+$/', $settings['yandex_id'] ) ? $settings['yandex_id'] : '';

	return apply_filters( 'kanapka_theme_analytics_settings', $settings );
}

/**
 * Remove obsolete per-product remarketing scripts stored in product content.
 *
 * @param string $content Product content.
 * @return string
 */
function kanapka_theme_strip_legacy_product_tracking( $content ) {
	if ( false === stripos( $content, 'google_tag_params' ) ) {
		return $content;
	}

	$content = preg_replace( '#<script\b[^>]*>\s*var\s+google_tag_params\s*=\s*\{.*?\};?\s*</script>#is', '', $content );
	$content = preg_replace( '#(?:<p\b[^>]*>\s*)?var\s+google_tag_params\s*=\s*\{.*?\};?\s*(?:</p>)?#is', '', $content );

	return is_string( $content ) ? $content : '';
}
add_filter( 'the_content', 'kanapka_theme_strip_legacy_product_tracking', 1 );
add_filter( 'woocommerce_short_description', 'kanapka_theme_strip_legacy_product_tracking', 1 );

/**
 * Build the legacy Google Ads dynamic remarketing payload.
 *
 * @return array<string, mixed>
 */
function kanapka_theme_remarketing_params() {
	$params = array(
		'local_pagetype' => 'other',
		'ecomm_pagetype' => 'other',
	);

	if ( function_exists( 'is_product' ) && is_product() ) {
		$product = wc_get_product( get_queried_object_id() );
		if ( $product ) {
			$params = array(
				'local_id'         => (string) $product->get_id(),
				'local_pagetype'   => 'offerdetail',
				'ecomm_prodid'     => (string) $product->get_id(),
				'ecomm_pagetype'   => 'product',
				'ecomm_totalvalue' => (float) wc_get_price_to_display( $product ),
			);
		}
	} elseif ( function_exists( 'is_cart' ) && is_cart() && function_exists( 'WC' ) && WC()->cart ) {
		$product_ids = array();
		foreach ( WC()->cart->get_cart() as $item ) {
			$product_ids[] = (string) $item['product_id'];
		}
		$params = array(
			'local_id'         => $product_ids,
			'local_pagetype'   => 'cart',
			'ecomm_prodid'     => $product_ids,
			'ecomm_pagetype'   => 'cart',
			'ecomm_totalvalue' => (float) WC()->cart->get_cart_contents_total(),
		);
	} elseif ( function_exists( 'is_shop' ) && ( is_shop() || is_product_category() || is_product_tag() ) ) {
		$params['local_pagetype'] = 'category';
		$params['ecomm_pagetype'] = 'category';
	} elseif ( is_front_page() ) {
		$params['local_pagetype'] = 'home';
		$params['ecomm_pagetype'] = 'home';
	} elseif ( is_search() ) {
		$params['local_pagetype'] = 'searchresults';
		$params['ecomm_pagetype'] = 'searchresults';
	}

	return apply_filters( 'kanapka_theme_remarketing_params', $params );
}

/** Output analytics loaders and the page-specific data layer. */
function kanapka_theme_analytics_head() {
	$settings = kanapka_theme_analytics_settings();
	$params   = kanapka_theme_remarketing_params();
	?>
	<script>window.dataLayer=window.dataLayer||[];window.google_tag_params=<?php echo wp_json_encode( $params ); ?>;</script>
	<?php if ( $settings['gtm_enabled'] && $settings['gtm_id'] ) : ?>
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer',<?php echo wp_json_encode( $settings['gtm_id'] ); ?>);</script>
	<?php endif; ?>
	<?php if ( $settings['ga4_enabled'] && $settings['ga4_id'] ) : ?>
		<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr( $settings['ga4_id'] ); ?>"></script>
		<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config',<?php echo wp_json_encode( $settings['ga4_id'] ); ?>);</script>
	<?php elseif ( $settings['ua_enabled'] && $settings['ua_id'] ) : ?>
		<script>(function(i,s,o,g,r,a,m){i.GoogleAnalyticsObject=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments);},i[r].l=1*new Date();a=s.createElement(o);m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m);})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');ga('create',<?php echo wp_json_encode( $settings['ua_id'] ); ?>,'auto');ga('require','displayfeatures');ga('send','pageview');</script>
	<?php endif; ?>
	<?php if ( $settings['meta_enabled'] && $settings['meta_id'] ) : ?>
		<script>!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments);};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=true;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=true;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s);}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');fbq('init',<?php echo wp_json_encode( $settings['meta_id'] ); ?>);fbq('track','PageView');</script>
	<?php endif; ?>
	<?php
}
add_action( 'wp_head', 'kanapka_theme_analytics_head', 5 );

/** Output non-JavaScript tracking fallbacks immediately after the body opens. */
function kanapka_theme_analytics_body_open() {
	$settings = kanapka_theme_analytics_settings();

	if ( $settings['gtm_enabled'] && $settings['gtm_id'] ) {
		?>
		<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo esc_attr( $settings['gtm_id'] ); ?>" height="0" width="0" style="display:none;visibility:hidden" title="Google Tag Manager"></iframe></noscript>
		<?php
	}
	if ( $settings['meta_enabled'] && $settings['meta_id'] ) {
		?>
		<noscript><img height="1" width="1" style="display:none" alt="" src="https://www.facebook.com/tr?id=<?php echo rawurlencode( $settings['meta_id'] ); ?>&amp;ev=PageView&amp;noscript=1"></noscript>
		<?php
	}
	if ( $settings['yandex_enabled'] && $settings['yandex_id'] ) {
		?>
		<noscript><div><img src="https://mc.yandex.ru/watch/<?php echo rawurlencode( $settings['yandex_id'] ); ?>" style="position:absolute;left:-9999px" alt=""></div></noscript>
		<?php
	}
}
add_action( 'wp_body_open', 'kanapka_theme_analytics_body_open' );

/** Output advertising counters in one controlled location. */
function kanapka_theme_analytics_footer() {
	$settings = kanapka_theme_analytics_settings();

	if ( $settings['yandex_enabled'] && $settings['yandex_id'] ) {
		?>
		<script>(function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments);};m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a);})(window,document,'script','https://mc.yandex.ru/metrika/tag.js','ym');ym(<?php echo wp_json_encode( (int) $settings['yandex_id'] ); ?>,'init',{clickmap:true,trackLinks:true,accurateTrackBounce:true,webvisor:true});</script>
		<?php
	}
	if ( $settings['google_ads_enabled'] && $settings['google_ads_id'] ) {
		?>
		<script>var google_conversion_id=<?php echo wp_json_encode( (int) $settings['google_ads_id'] ); ?>;var google_custom_params=window.google_tag_params;var google_remarketing_only=true;</script>
		<script async src="https://www.googleadservices.com/pagead/conversion.js"></script>
		<?php
	}
}
add_action( 'wp_footer', 'kanapka_theme_analytics_footer', 20 );
