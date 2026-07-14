<?php
/**
 * Template Name: Delivery and payment
 * Template Post Type: page
 *
 * @package Kanapka_Theme
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) {
	the_post();

	$sections = function_exists( 'get_field' ) ? get_field( 'kanapka_delivery_sections', get_the_ID() ) : array();
	$intro_title = function_exists( 'get_field' ) ? get_field( 'kanapka_delivery_intro_title', get_the_ID() ) : '';
	$intro_text = function_exists( 'get_field' ) ? get_field( 'kanapka_delivery_intro_text', get_the_ID() ) : '';
	$intro_image_id = function_exists( 'get_field' ) ? absint( get_field( 'kanapka_delivery_intro_image', get_the_ID() ) ) : 0;

	if ( function_exists( 'acf_get_field' ) ) {
		if ( empty( $sections ) ) {
			$field    = acf_get_field( 'field_kanapka_delivery_sections' );
			$sections = $field['default_value'] ?? array();
		}
		if ( ! $intro_title ) {
			$field       = acf_get_field( 'field_kanapka_delivery_intro_title' );
			$intro_title = $field['default_value'] ?? '';
		}
		if ( ! $intro_text ) {
			$field      = acf_get_field( 'field_kanapka_delivery_intro_text' );
			$intro_text = $field['default_value'] ?? '';
		}
	}
	?>
	<main id="main-content" class="site-main delivery-payment-page">
		<header class="delivery-payment-page__header container">
			<?php kanapka_theme_breadcrumb(); ?>
			<h1><?php the_title(); ?></h1>
		</header>

		<div class="delivery-payment-layout container">
			<?php get_template_part( 'template-parts/shop/sidebar', null, array( 'categories_only' => true, 'mobile_category_toggle' => true ) ); ?>

			<div class="delivery-payment-content">
				<?php if ( is_array( $sections ) && $sections ) : ?>
					<div class="delivery-payment-cards">
						<?php foreach ( $sections as $section ) : ?>
							<?php
							$title = sanitize_text_field( $section['title'] ?? '' );
							$icon  = sanitize_key( $section['icon'] ?? 'info' );
							$text  = wp_kses_post( $section['content'] ?? '' );
							?>
							<section class="delivery-payment-card">
								<header class="delivery-payment-card__header">
									<span class="delivery-payment-card__icon"><?php echo kanapka_theme_ui_icon( $icon, 28 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
									<?php if ( $title ) : ?><h2><?php echo esc_html( $title ); ?></h2><?php endif; ?>
								</header>
								<div class="delivery-payment-card__content"><?php echo $text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
							</section>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<?php if ( $intro_title || $intro_text || $intro_image_id ) : ?>
					<section class="delivery-payment-intro<?php echo $intro_image_id ? '' : ' delivery-payment-intro--no-image'; ?>">
						<?php if ( $intro_image_id ) : ?>
							<div class="delivery-payment-intro__image">
								<?php echo wp_get_attachment_image( $intro_image_id, 'kanapka-service', false, array( 'loading' => 'lazy', 'sizes' => '(max-width: 780px) 100vw, 36vw', 'alt' => '' ) ); ?>
							</div>
						<?php endif; ?>
						<div class="delivery-payment-intro__content">
							<?php if ( $intro_title ) : ?><h2><?php echo esc_html( $intro_title ); ?></h2><?php endif; ?>
							<?php echo wp_kses_post( $intro_text ); ?>
						</div>
					</section>
				<?php endif; ?>
			</div>
		</div>

		<?php get_template_part( 'template-parts/front-page/benefits' ); ?>
	</main>
	<?php
}

get_footer();
