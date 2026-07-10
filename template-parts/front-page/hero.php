<?php
/**
 * Homepage hero.
 *
 * @package Kanapka_Theme
 */

$image_id = kanapka_theme_get_hero_image_id();
$sets_url = get_term_link( 'gotovye-nabory', 'product_cat' );
$sets_url = is_wp_error( $sets_url ) ? ( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' ) ) : $sets_url;
?>
<section class="home-hero" aria-labelledby="home-hero-title">
	<div class="home-hero__inner container">
		<div class="home-hero__content">
			<p class="eyebrow"><?php esc_html_e( 'Catering and delivery in Kyiv', 'kanapka-theme' ); ?></p>
			<h1 id="home-hero-title"><?php esc_html_e( 'Ready-made sets for every celebration', 'kanapka-theme' ); ?></h1>
			<p><?php esc_html_e( 'Canapés, appetizers and buffet sets made from fresh ingredients and delivered at the right time.', 'kanapka-theme' ); ?></p>
			<div class="home-hero__actions">
				<a class="button" href="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' ) ); ?>"><?php esc_html_e( 'Browse catalogue', 'kanapka-theme' ); ?></a>
				<a class="button button--outline" href="<?php echo esc_url( $sets_url ); ?>"><?php esc_html_e( 'Ready-made sets', 'kanapka-theme' ); ?></a>
			</div>
		</div>
		<div class="home-hero__media">
			<?php
			if ( $image_id ) {
				echo wp_get_attachment_image(
					$image_id,
					'kanapka-hero',
					false,
					array(
						'fetchpriority' => 'high',
						'loading'       => 'eager',
						'sizes'         => '(max-width: 760px) 100vw, 55vw',
					)
				);
			}
			?>
		</div>
	</div>
	<div class="home-hero__benefits container" aria-label="<?php esc_attr_e( 'Delivery benefits', 'kanapka-theme' ); ?>">
		<span><?php esc_html_e( 'Free delivery for qualifying orders', 'kanapka-theme' ); ?></span>
		<span><?php esc_html_e( 'Same-day delivery', 'kanapka-theme' ); ?></span>
		<span><?php esc_html_e( 'Fresh products and careful preparation', 'kanapka-theme' ); ?></span>
	</div>
</section>
