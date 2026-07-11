<?php
/**
 * Homepage client brand slider.
 *
 * @package Kanapka_Theme
 */

$brands = kanapka_theme_get_home_client_brands( 24 );

if ( ! $brands ) {
	return;
}
?>
<section class="home-section section client-brands" aria-labelledby="client-brands-title" data-logo-slider>
	<div class="container">
		<div class="section-heading">
			<h2 id="client-brands-title"><?php esc_html_e( 'Наші клієнти', 'kanapka-theme' ); ?></h2>
		</div>
		<div class="client-brands__carousel">
			<button class="client-brands__arrow client-brands__arrow--previous" type="button" aria-label="<?php esc_attr_e( 'Попередні клієнти', 'kanapka-theme' ); ?>" data-logo-slider-previous>
				<?php echo kanapka_theme_ui_icon( 'chevron-left', 34 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Theme-owned SVG. ?>
			</button>
			<div class="client-brands__viewport">
				<ul class="client-brands__track" data-logo-slider-track>
					<?php foreach ( $brands as $brand ) : ?>
						<li class="client-brands__item">
							<?php if ( $brand['url'] ) : ?>
								<a class="client-brands__link" href="<?php echo esc_url( $brand['url'] ); ?>" aria-label="<?php echo esc_attr( $brand['name'] ); ?>">
							<?php else : ?>
								<span class="client-brands__link">
							<?php endif; ?>
								<?php echo wp_get_attachment_image( $brand['image_id'], 'medium', false, array( 'loading' => 'lazy', 'sizes' => '(max-width: 780px) 33vw, 180px', 'alt' => $brand['name'] ) ); ?>
							<?php if ( $brand['url'] ) : ?>
								</a>
							<?php else : ?>
								</span>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
			<button class="client-brands__arrow client-brands__arrow--next" type="button" aria-label="<?php esc_attr_e( 'Наступні клієнти', 'kanapka-theme' ); ?>" data-logo-slider-next>
				<?php echo kanapka_theme_ui_icon( 'chevron-right', 34 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Theme-owned SVG. ?>
			</button>
		</div>
	</div>
</section>
