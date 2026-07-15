<?php
/**
 * Template Name: Catering service
 * Template Post Type: page
 *
 * @package Kanapka_Theme
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) {
	the_post();

	$page_id = get_the_ID();
	$field   = static function ( $name, $default = '' ) use ( $page_id ) {
		if ( ! function_exists( 'get_field' ) ) {
			return $default;
		}

		$value = get_field( $name, $page_id );

		if ( ! metadata_exists( 'post', $page_id, $name ) && function_exists( 'acf_get_field' ) ) {
			$definition = acf_get_field( $name );

			if ( is_array( $definition ) && array_key_exists( 'default_value', $definition ) ) {
				$value = $definition['default_value'];
			}
		}

		return null === $value || '' === $value ? $default : $value;
	};
	$first_content_image = static function ( $post_id ) {
		if ( get_post_thumbnail_id( $post_id ) ) {
			return get_post_thumbnail_id( $post_id );
		}

		$content = (string) get_post_field( 'post_content', $post_id );
		preg_match( '/wp-image-(\d+)/', $content, $matches );

		return absint( $matches[1] ?? 0 );
	};
	$plain_intro = static function ( $post_id ) {
		$excerpt = get_the_excerpt( $post_id );
		$content = $excerpt ?: strip_shortcodes( (string) get_post_field( 'post_content', $post_id ) );

		return wp_trim_words( wp_strip_all_tags( $content ), 42 );
	};

	$hero_enabled     = (bool) $field( 'kanapka_catering_hero_enabled', true );
	$hero_subtitle    = sanitize_text_field( $field( 'kanapka_catering_hero_subtitle', '' ) );
	$hero_text        = $field( 'kanapka_catering_hero_text', '' );
	$hero_text        = $hero_text ?: '<p>' . esc_html( $plain_intro( $page_id ) ) . '</p>';
	$hero_images      = $field( 'kanapka_catering_hero_image', array() );
	$hero_images      = is_array( $hero_images ) ? $hero_images : array( $hero_images );
	$hero_images      = array_values( array_unique( array_filter( array_map( 'absint', $hero_images ) ) ) );
	$hero_images      = $hero_images ?: array_filter( array( $first_content_image( $page_id ) ) );
	$has_hero_slider  = count( $hero_images ) > 1;
	$hero_points      = (array) $field( 'kanapka_catering_hero_points', array() );
	$features         = (array) $field( 'kanapka_catering_features', array() );
	$feature_title    = sanitize_text_field( $field( 'kanapka_catering_features_title', '' ) );
	$children         = get_pages( array( 'parent' => $page_id, 'sort_column' => 'menu_order,post_title', 'post_status' => 'publish' ) );
	$service_cards    = array();
	$manual_services  = (array) $field( 'kanapka_catering_services', array() );
	$automatic_cards  = (bool) $field( 'kanapka_catering_services_auto', true );

	if ( $automatic_cards && $children ) {
		foreach ( $children as $child ) {
			$service_cards[] = array(
				'page'  => $child->ID,
				'title' => get_the_title( $child ),
				'text'  => $plain_intro( $child->ID ),
				'image' => $first_content_image( $child->ID ),
			);
		}
	} else {
		$service_cards = $manual_services;
	}

	$content_body = $field( 'kanapka_catering_content_body', '' );
	$legacy_body  = (string) get_the_content();
	$legacy_body  = preg_replace( '/<!-- wp:heading[^>]*-->\s*<h1[^>]*>.*?<\/h1>\s*<!-- \/wp:heading -->/is', '', $legacy_body );
	$legacy_body  = preg_replace( '/<h1[^>]*>.*?<\/h1>/is', '', $legacy_body );
	$legacy_body  = preg_replace( '/\[(?:ngg|nggallery)[^\]]*\]/i', '', $legacy_body );
	$gallery             = array_filter( array_map( 'absint', (array) $field( 'kanapka_catering_gallery_images', array() ) ) );
	$recommended_ids     = array_filter( array_map( 'absint', (array) $field( 'kanapka_catering_recommended_products', array() ) ) );
	$recommended_products = function_exists( 'wc_get_product' ) ? array_values(
		array_filter(
			array_map( 'wc_get_product', $recommended_ids ),
			static fn( $product ) => $product instanceof WC_Product && $product->is_visible()
		)
	) : array();
	$cta_link            = $field( 'kanapka_catering_cta_link', array() );
	$cta_form_id         = absint( $field( 'kanapka_catering_cta_form', 0 ) );
	?>
	<main id="main-content" class="site-main catering-page">
		<div class="container catering-page__breadcrumbs">
			<?php kanapka_theme_breadcrumb(); ?>
		</div>

		<?php if ( $hero_enabled ) : ?>
			<section class="catering-hero section">
				<div class="container catering-hero__grid<?php echo $hero_images ? '' : ' catering-hero__grid--no-image'; ?>">
					<div class="catering-hero__content">
						<h1><?php the_title(); ?></h1>
						<?php if ( $hero_subtitle ) : ?><p class="catering-hero__subtitle"><?php echo esc_html( $hero_subtitle ); ?></p><?php endif; ?>
						<div class="catering-hero__text"><?php echo wp_kses_post( $hero_text ); ?></div>
						<?php if ( $hero_points ) : ?>
							<ul class="catering-checklist">
								<?php foreach ( $hero_points as $point ) : ?>
									<?php if ( ! empty( $point['text'] ) ) : ?><li><?php echo esc_html( $point['text'] ); ?></li><?php endif; ?>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</div>
					<?php if ( $hero_images ) : ?>
						<div class="catering-hero__media<?php echo $has_hero_slider ? ' has-slider' : ''; ?>"<?php echo $has_hero_slider ? ' data-catering-hero-slider' : ''; ?>>
							<div class="catering-hero__track" data-catering-hero-track>
								<?php foreach ( $hero_images as $hero_index => $hero_image_id ) : ?>
									<figure class="catering-hero__slide" data-catering-hero-slide aria-hidden="<?php echo 0 === $hero_index ? 'false' : 'true'; ?>">
										<?php echo wp_get_attachment_image( $hero_image_id, 'kanapka-catering-hero', false, array( 'loading' => 0 === $hero_index ? 'eager' : 'lazy', 'fetchpriority' => 0 === $hero_index ? 'high' : 'auto', 'sizes' => '(max-width: 780px) 100vw, 45vw' ) ); ?>
									</figure>
								<?php endforeach; ?>
							</div>
							<?php if ( $has_hero_slider ) : ?>
								<button class="catering-hero__arrow catering-hero__arrow--previous" type="button" aria-label="<?php esc_attr_e( 'Previous image', 'kanapka-theme' ); ?>" data-catering-hero-previous><?php echo kanapka_theme_ui_icon( 'chevron-left', 24 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
								<button class="catering-hero__arrow catering-hero__arrow--next" type="button" aria-label="<?php esc_attr_e( 'Next image', 'kanapka-theme' ); ?>" data-catering-hero-next><?php echo kanapka_theme_ui_icon( 'chevron-right', 24 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
								<div class="catering-hero__dots" role="group" aria-label="<?php esc_attr_e( 'Event photos', 'kanapka-theme' ); ?>">
									<?php foreach ( $hero_images as $hero_index => $hero_image_id ) : ?>
										<button type="button" aria-label="<?php echo esc_attr( sprintf( __( 'Open image %1$d of %2$d', 'kanapka-theme' ), $hero_index + 1, count( $hero_images ) ) ); ?>" aria-current="<?php echo 0 === $hero_index ? 'true' : 'false'; ?>" data-catering-hero-dot="<?php echo esc_attr( $hero_index ); ?>"></button>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			</section>
		<?php endif; ?>

		<?php if ( $field( 'kanapka_catering_features_enabled', true ) && $features ) : ?>
			<section class="catering-section section"><div class="container">
				<?php if ( $feature_title ) : ?><h2 class="catering-section__title"><?php echo esc_html( $feature_title ); ?></h2><?php endif; ?>
				<div class="catering-feature-grid">
					<?php foreach ( $features as $feature ) : ?>
						<article class="catering-feature-card"><span><?php echo kanapka_theme_ui_icon( sanitize_key( $feature['icon'] ?? 'circle-check' ), 28 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span><h3><?php echo esc_html( $feature['title'] ?? '' ); ?></h3><?php if ( ! empty( $feature['text'] ) ) : ?><p><?php echo esc_html( $feature['text'] ); ?></p><?php endif; ?></article>
					<?php endforeach; ?>
				</div>
			</div></section>
		<?php endif; ?>

		<?php if ( $field( 'kanapka_catering_services_enabled', true ) && $service_cards ) : ?>
			<section class="catering-section section catering-section--soft"><div class="container">
				<h2 class="catering-section__title"><?php echo esc_html( $field( 'kanapka_catering_services_title', __( 'Our catering services', 'kanapka-theme' ) ) ); ?></h2>
				<div class="catering-service-grid">
					<?php foreach ( $service_cards as $service ) : ?>
						<?php $service_page = absint( $service['page'] ?? 0 ); $service_image = absint( $service['image'] ?? 0 ) ?: ( $service_page ? $first_content_image( $service_page ) : 0 ); ?>
						<article class="catering-service-card">
							<?php if ( $service_image ) : ?><a class="catering-service-card__media" href="<?php echo esc_url( $service_page ? get_permalink( $service_page ) : '#' ); ?>"><?php echo wp_get_attachment_image( $service_image, 'kanapka-service', false, array( 'loading' => 'lazy', 'sizes' => '(max-width: 640px) 100vw, 25vw' ) ); ?></a><?php endif; ?>
							<div class="catering-service-card__content"><h3><?php echo esc_html( ( $service['title'] ?? '' ) ?: ( $service_page ? get_the_title( $service_page ) : '' ) ); ?></h3><?php if ( ! empty( $service['text'] ) ) : ?><p><?php echo esc_html( $service['text'] ); ?></p><?php endif; ?><?php if ( $service_page ) : ?><a href="<?php echo esc_url( get_permalink( $service_page ) ); ?>"><?php esc_html_e( 'Learn more', 'kanapka-theme' ); ?> <?php echo kanapka_theme_ui_icon( 'chevron-right', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a><?php endif; ?></div>
						</article>
					<?php endforeach; ?>
				</div>
			</div></section>
		<?php endif; ?>

		<?php if ( $field( 'kanapka_catering_content_enabled', true ) && ( $content_body || ( ! $children && trim( wp_strip_all_tags( $legacy_body ) ) ) ) ) : ?>
			<section class="catering-section section"><div class="container catering-content-card">
				<?php if ( $field( 'kanapka_catering_content_title', '' ) ) : ?><h2><?php echo esc_html( $field( 'kanapka_catering_content_title' ) ); ?></h2><?php endif; ?>
				<div class="catering-rich-text"><?php echo $content_body ? wp_kses_post( $content_body ) : apply_filters( 'the_content', $legacy_body ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			</div></section>
		<?php endif; ?>

		<?php if ( $field( 'kanapka_catering_gallery_enabled', false ) && $gallery ) : ?>
			<section class="catering-section section catering-section--soft"><div class="container">
				<?php if ( $field( 'kanapka_catering_gallery_title', '' ) ) : ?><h2 class="catering-section__title"><?php echo esc_html( $field( 'kanapka_catering_gallery_title' ) ); ?></h2><?php endif; ?>
				<div class="catering-gallery" data-catering-gallery>
					<?php foreach ( $gallery as $gallery_index => $image_id ) : ?>
						<?php $large_image_url = wp_get_attachment_image_url( $image_id, 'large' ); $image_alt = (string) get_post_meta( $image_id, '_wp_attachment_image_alt', true ); ?>
						<figure>
							<button type="button" data-catering-gallery-item data-gallery-src="<?php echo esc_url( $large_image_url ); ?>" data-gallery-alt="<?php echo esc_attr( $image_alt ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Open image %1$d of %2$d', 'kanapka-theme' ), $gallery_index + 1, count( $gallery ) ) ); ?>">
								<?php echo wp_get_attachment_image( $image_id, 'kanapka-catering-gallery', false, array( 'loading' => 'lazy', 'sizes' => '(max-width: 640px) 50vw, (max-width: 1024px) 50vw, 25vw', 'alt' => $image_alt ) ); ?>
							</button>
						</figure>
					<?php endforeach; ?>
				</div>
				<div class="catering-lightbox" hidden data-catering-lightbox>
					<div class="catering-lightbox__backdrop" data-catering-lightbox-close></div>
					<div class="catering-lightbox__dialog" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Event photos', 'kanapka-theme' ); ?>" tabindex="-1" data-catering-lightbox-dialog>
						<button class="catering-lightbox__close" type="button" aria-label="<?php esc_attr_e( 'Close gallery', 'kanapka-theme' ); ?>" data-catering-lightbox-close><?php echo kanapka_theme_ui_icon( 'x', 24 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
						<button class="catering-lightbox__arrow catering-lightbox__arrow--previous" type="button" aria-label="<?php esc_attr_e( 'Previous image', 'kanapka-theme' ); ?>" data-catering-lightbox-previous><?php echo kanapka_theme_ui_icon( 'chevron-left', 30 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
						<figure><img src="" alt="" data-catering-lightbox-image><figcaption data-catering-lightbox-counter></figcaption></figure>
						<button class="catering-lightbox__arrow catering-lightbox__arrow--next" type="button" aria-label="<?php esc_attr_e( 'Next image', 'kanapka-theme' ); ?>" data-catering-lightbox-next><?php echo kanapka_theme_ui_icon( 'chevron-right', 30 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
					</div>
				</div>
			</div></section>
		<?php endif; ?>

		<?php if ( $field( 'kanapka_catering_recommended_enabled', false ) && $recommended_products ) : ?>
			<?php $recommended_title_id = 'catering-recommended-title-' . $page_id; ?>
			<section class="home-section section popular-products catering-recommended-products" aria-labelledby="<?php echo esc_attr( $recommended_title_id ); ?>" data-product-slider>
				<div class="container">
					<div class="section-heading">
						<h2 id="<?php echo esc_attr( $recommended_title_id ); ?>"><?php echo esc_html( $field( 'kanapka_catering_recommended_title', __( 'Recommended', 'woocommerce' ) ) ); ?></h2>
					</div>
					<div class="popular-products__carousel">
						<button class="popular-products__arrow popular-products__arrow--previous" type="button" aria-label="<?php esc_attr_e( 'Previous products', 'kanapka-theme' ); ?>" data-product-slider-previous><?php echo kanapka_theme_ui_icon( 'chevron-left', 34 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
						<div class="popular-products__viewport">
							<div class="product-card-grid" data-product-slider-track>
								<?php foreach ( $recommended_products as $recommended_product ) : ?>
									<?php get_template_part( 'template-parts/components/product-card', null, array( 'product' => $recommended_product, 'show_quantity' => true ) ); ?>
								<?php endforeach; ?>
							</div>
						</div>
						<button class="popular-products__arrow popular-products__arrow--next" type="button" aria-label="<?php esc_attr_e( 'Next products', 'kanapka-theme' ); ?>" data-product-slider-next><?php echo kanapka_theme_ui_icon( 'chevron-right', 34 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
					</div>
				</div>
			</section>
		<?php endif; ?>

		<?php if ( $field( 'kanapka_catering_cta_enabled', true ) ) : ?>
			<section class="catering-section section"><div class="container catering-cta">
				<h2><?php echo esc_html( $field( 'kanapka_catering_cta_title', __( 'Ready to organise your perfect event?', 'kanapka-theme' ) ) ); ?></h2>
				<?php if ( $field( 'kanapka_catering_cta_text', '' ) ) : ?><p><?php echo esc_html( $field( 'kanapka_catering_cta_text' ) ); ?></p><?php endif; ?>
				<?php if ( $cta_form_id && shortcode_exists( 'contact-form-7' ) ) : ?><div class="catering-cta__form"><?php echo do_shortcode( '[contact-form-7 id="' . $cta_form_id . '"]' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div><?php elseif ( is_array( $cta_link ) && ! empty( $cta_link['url'] ) ) : ?><a class="button" href="<?php echo esc_url( $cta_link['url'] ); ?>"><?php echo esc_html( $cta_link['title'] ?: __( 'Send request', 'kanapka-theme' ) ); ?></a><?php endif; ?>
			</div></section>
		<?php endif; ?>

		<?php if ( $field( 'kanapka_catering_benefits_enabled', true ) ) : ?><?php get_template_part( 'template-parts/front-page/benefits' ); ?><?php endif; ?>
	</main>
	<?php
}

get_footer();
