<?php
/**
 * Template Name: Simple information page
 * Template Post Type: page
 *
 * @package Kanapka_Theme
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) {
	the_post();

	$page_id          = get_the_ID();
	$field            = static fn( $name, $default = '' ) => function_exists( 'get_field' ) ? ( get_field( $name, $page_id ) ?? $default ) : $default;
	$hero_image_id    = absint( $field( 'kanapka_simple_page_hero_image', 0 ) );
	$intro            = wp_kses_post( $field( 'kanapka_simple_page_intro', '' ) );
	$content          = wp_kses_post( $field( 'kanapka_simple_page_content', '' ) );
	$gallery_enabled  = (bool) $field( 'kanapka_simple_page_gallery_enabled', false );
	$gallery_title    = sanitize_text_field( $field( 'kanapka_simple_page_gallery_title', __( 'Event photos', 'kanapka-theme' ) ) );
	$gallery          = array_values( array_filter( array_map( 'absint', (array) $field( 'kanapka_simple_page_gallery', array() ) ) ) );
	$cta_enabled      = (bool) $field( 'kanapka_simple_page_cta_enabled', false );
	$cta_title        = sanitize_text_field( $field( 'kanapka_simple_page_cta_title', '' ) );
	$cta_text         = wp_kses_post( $field( 'kanapka_simple_page_cta_text', '' ) );
	$cta_buttons      = (array) $field( 'kanapka_simple_page_cta_buttons', array() );
	$products_enabled = (bool) $field( 'kanapka_simple_page_products_enabled', false );
	$products_title   = sanitize_text_field( $field( 'kanapka_simple_page_products_title', __( 'Frequently ordered', 'kanapka-theme' ) ) );
	$products_source  = sanitize_key( $field( 'kanapka_simple_page_products_source', 'manual' ) );
	$products_count   = min( 20, max( 1, absint( $field( 'kanapka_simple_page_products_count', 8 ) ) ) );
	$product_ids      = array_values( array_filter( array_map( 'absint', (array) $field( 'kanapka_simple_page_products', array() ) ) ) );
	$products         = array();
	$products_link    = $field( 'kanapka_simple_page_products_link', array() );
	$benefits_enabled = (bool) $field( 'kanapka_simple_page_benefits_enabled', true );

	if ( $products_enabled && function_exists( 'wc_get_products' ) ) {
		$product_query = array(
			'limit'   => $products_count,
			'status'  => 'publish',
			'orderby' => 'date',
			'order'   => 'DESC',
			'return'  => 'objects',
		);

		if ( 'manual' === $products_source ) {
			$product_query['include'] = $product_ids ?: array( 0 );
			$product_query['orderby'] = 'include';
		} elseif ( 'category' === $products_source ) {
			$category = get_term( absint( $field( 'kanapka_simple_page_products_category', 0 ) ), 'product_cat' );
			$product_query['category'] = $category instanceof WP_Term ? array( $category->slug ) : array( '__missing__' );
		} elseif ( 'featured' === $products_source ) {
			$product_query['featured'] = true;
		} elseif ( 'related' === $products_source ) {
			$related_product_id = absint( $field( 'kanapka_simple_page_related_product', 0 ) );
			$related_ids        = $related_product_id && function_exists( 'wc_get_related_products' ) ? wc_get_related_products( $related_product_id, $products_count ) : array();
			$product_query['include'] = $related_ids ?: array( 0 );
			$product_query['orderby'] = 'include';
		}

		$products = array_values(
			array_filter(
				wc_get_products( $product_query ),
				static fn( $product ) => $product instanceof WC_Product && $product->is_visible()
			)
		);
	}
	?>
	<main id="main-content" class="site-main simple-information-page">
		<div class="container simple-information-page__breadcrumbs"><?php kanapka_theme_breadcrumb(); ?></div>

		<header class="container simple-information-page__header">
			<h1><?php the_title(); ?></h1>
		</header>

		<?php if ( $hero_image_id ) : ?>
			<div class="container simple-information-page__hero">
				<?php
				echo wp_get_attachment_image(
					$hero_image_id,
					'large',
					false,
					array(
						'fetchpriority' => 'high',
						'loading'       => 'eager',
						'sizes'         => '(max-width: 25rem) calc(100vw - 2rem), (max-width: 50rem) 92vw, (max-width: 78rem) calc(100vw - 4rem), 74rem',
					)
				);
				?>
			</div>
		<?php endif; ?>

		<?php if ( $intro || $content ) : ?>
			<section class="simple-information-section section"><div class="container">
				<div class="simple-information-content">
					<?php if ( $intro ) : ?><div class="simple-information-content__intro"><?php echo $intro; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div><?php endif; ?>
					<?php if ( $content ) : ?><div class="simple-information-content__body"><?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div><?php endif; ?>
				</div>
			</div></section>
		<?php endif; ?>

		<?php if ( $gallery_enabled && $gallery ) : ?>
			<section class="simple-information-section simple-information-section--soft section" aria-labelledby="simple-gallery-title-<?php echo esc_attr( $page_id ); ?>"><div class="container">
				<h2 id="simple-gallery-title-<?php echo esc_attr( $page_id ); ?>" class="simple-information-section__title"><?php echo esc_html( $gallery_title ); ?></h2>
				<div class="simple-information-gallery" data-catering-gallery>
					<?php foreach ( $gallery as $gallery_index => $image_id ) : ?>
						<?php $large_image_url = wp_get_attachment_image_url( $image_id, 'full' ); ?>
						<figure><button type="button" data-catering-gallery-item data-gallery-src="<?php echo esc_url( $large_image_url ); ?>" data-gallery-alt="<?php echo esc_attr( get_post_meta( $image_id, '_wp_attachment_image_alt', true ) ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Open image %1$d of %2$d', 'kanapka-theme' ), $gallery_index + 1, count( $gallery ) ) ); ?>"><?php echo wp_get_attachment_image( $image_id, 'kanapka-catering-gallery', false, array( 'loading' => 'lazy', 'sizes' => '(max-width: 40rem) 50vw, (max-width: 64rem) 50vw, 33vw' ) ); ?></button></figure>
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

		<?php if ( $cta_enabled && ( $cta_title || $cta_text || $cta_buttons ) ) : ?>
			<section class="simple-information-section section"><div class="container">
				<div class="simple-information-cta">
					<?php if ( $cta_title ) : ?><h2><?php echo esc_html( $cta_title ); ?></h2><?php endif; ?>
					<?php if ( $cta_text ) : ?><div class="simple-information-cta__text"><?php echo $cta_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div><?php endif; ?>
					<?php if ( $cta_buttons ) : ?><div class="simple-information-cta__buttons">
						<?php foreach ( $cta_buttons as $button ) : ?>
							<?php $button_link = is_array( $button['link'] ?? null ) ? $button['link'] : array(); ?>
							<?php if ( ! empty( $button_link['url'] ) ) : ?><a class="button<?php echo 'secondary' === ( $button['style'] ?? '' ) ? ' button--secondary' : ''; ?>" href="<?php echo esc_url( $button_link['url'] ); ?>"<?php echo '_blank' === ( $button_link['target'] ?? '' ) ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>><?php echo esc_html( $button_link['title'] ?: __( 'Learn more', 'kanapka-theme' ) ); ?></a><?php endif; ?>
						<?php endforeach; ?>
					</div><?php endif; ?>
				</div>
			</div></section>
		<?php endif; ?>

		<?php if ( $products_enabled && $products ) : ?>
			<section class="home-section section popular-products simple-information-products" aria-labelledby="simple-products-title-<?php echo esc_attr( $page_id ); ?>" data-product-slider><div class="container">
				<div class="section-heading simple-information-products__heading">
					<h2 id="simple-products-title-<?php echo esc_attr( $page_id ); ?>"><?php echo esc_html( $products_title ); ?></h2>
					<?php if ( is_array( $products_link ) && ! empty( $products_link['url'] ) ) : ?><a href="<?php echo esc_url( $products_link['url'] ); ?>"><?php echo esc_html( $products_link['title'] ?: __( 'Learn more', 'kanapka-theme' ) ); ?></a><?php endif; ?>
				</div>
				<div class="popular-products__carousel">
					<button class="popular-products__arrow popular-products__arrow--previous" type="button" aria-label="<?php esc_attr_e( 'Previous products', 'kanapka-theme' ); ?>" data-product-slider-previous><?php echo kanapka_theme_ui_icon( 'chevron-left', 34 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
					<div class="popular-products__viewport"><div class="product-card-grid" data-product-slider-track><?php foreach ( $products as $product ) : ?><?php get_template_part( 'template-parts/components/product-card', null, array( 'product' => $product, 'show_quantity' => true ) ); ?><?php endforeach; ?></div></div>
					<button class="popular-products__arrow popular-products__arrow--next" type="button" aria-label="<?php esc_attr_e( 'Next products', 'kanapka-theme' ); ?>" data-product-slider-next><?php echo kanapka_theme_ui_icon( 'chevron-right', 34 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
				</div>
			</div></section>
		<?php endif; ?>

		<?php if ( $benefits_enabled ) : ?><?php get_template_part( 'template-parts/front-page/benefits' ); ?><?php endif; ?>
	</main>
	<?php
}

get_footer();
