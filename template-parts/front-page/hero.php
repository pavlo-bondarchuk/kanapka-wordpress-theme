<?php
/**
 * Homepage hero slider.
 *
 * @package Kanapka_Theme
 */

$slides         = kanapka_theme_get_home_hero_slides();
$benefits       = kanapka_theme_get_home_hero_benefits();
$slide_count    = count( $slides );
$autoplay_delay = max( 3000, min( 15000, absint( kanapka_theme_get_home_field( 'kanapka_home_hero_delay', 6000 ) ) ) );

if ( ! $slides ) {
	return;
}

/**
 * Render a local hero benefit icon.
 *
 * @param string $icon Icon key.
 */
$render_benefit_icon = static function ( $icon ) {
	$paths = array(
		'delivery' => '<path d="M3 6h11v10H3zM14 10h4l3 3v3h-7zM7 19a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm10 0a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"/>',
		'clock'    => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
		'leaf'     => '<path d="M20 4C11 4 5 8 5 15c0 3 2 5 5 5 7 0 10-7 10-16Z"/><path d="M4 21c3-6 7-9 12-12"/>',
	);
	?>
	<svg aria-hidden="true" viewBox="0 0 24 24" width="36" height="36" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
		<?php echo $paths[ $icon ] ?? $paths['delivery']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Theme-owned SVG paths. ?>
	</svg>
	<?php
};
?>
<section class="home-hero" aria-roledescription="<?php esc_attr_e( 'carousel', 'kanapka-theme' ); ?>" aria-label="<?php esc_attr_e( 'Featured offers', 'kanapka-theme' ); ?>" data-hero-slider data-autoplay-delay="<?php echo esc_attr( $autoplay_delay ); ?>">
	<div class="home-hero__viewport">
		<div class="home-hero__track">
			<?php foreach ( $slides as $index => $slide ) : ?>
				<?php $is_active = 0 === $index; ?>
				<article class="home-hero__slide<?php echo $is_active ? ' is-active' : ''; ?>" aria-roledescription="<?php esc_attr_e( 'slide', 'kanapka-theme' ); ?>" aria-label="<?php echo esc_attr( sprintf( __( '%1$d of %2$d', 'kanapka-theme' ), $index + 1, $slide_count ) ); ?>" aria-hidden="<?php echo $is_active ? 'false' : 'true'; ?>" data-hero-slide>
					<?php
					if ( $slide['image_id'] ) {
						echo wp_get_attachment_image(
							$slide['image_id'],
							'kanapka-hero',
							false,
							array(
								'class'         => 'home-hero__image',
								'alt'           => '',
								'fetchpriority' => $is_active ? 'high' : 'auto',
								'loading'       => $is_active ? 'eager' : 'lazy',
								'sizes'         => '100vw',
							)
						);
					}
					?>
					<div class="home-hero__content container">
						<div class="home-hero__copy">
							<?php if ( 0 === $index ) : ?>
								<h1 id="home-hero-title"><?php echo esc_html( $slide['title'] ); ?></h1>
							<?php else : ?>
								<h2><?php echo esc_html( $slide['title'] ); ?></h2>
							<?php endif; ?>
							<?php if ( $slide['text'] ) : ?>
								<div class="home-hero__text"><?php echo wp_kses_post( wpautop( $slide['text'] ) ); ?></div>
							<?php endif; ?>
							<?php if ( $slide['button_label'] && $slide['button_url'] ) : ?>
								<a class="button home-hero__button" href="<?php echo esc_url( $slide['button_url'] ); ?>"<?php if ( '_blank' === $slide['button_target'] ) : ?> target="_blank" rel="noopener noreferrer"<?php endif; ?>><?php echo esc_html( $slide['button_label'] ); ?></a>
							<?php endif; ?>
						</div>
					</div>
				</article>
			<?php endforeach; ?>
		</div>

		<?php if ( $slide_count > 1 ) : ?>
			<button class="home-hero__arrow home-hero__arrow--previous" type="button" aria-label="<?php esc_attr_e( 'Previous slide', 'kanapka-theme' ); ?>" data-hero-previous>
				<svg aria-hidden="true" viewBox="0 0 24 24" width="28" height="28"><path d="m15 5-7 7 7 7" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
			</button>
			<button class="home-hero__arrow home-hero__arrow--next" type="button" aria-label="<?php esc_attr_e( 'Next slide', 'kanapka-theme' ); ?>" data-hero-next>
				<svg aria-hidden="true" viewBox="0 0 24 24" width="28" height="28"><path d="m9 5 7 7-7 7" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
			</button>
		<?php endif; ?>

		<div class="home-hero__benefits container" aria-label="<?php esc_attr_e( 'Delivery benefits', 'kanapka-theme' ); ?>">
			<?php foreach ( $benefits as $benefit ) : ?>
				<div class="home-hero__benefit">
					<span class="home-hero__benefit-icon"><?php $render_benefit_icon( $benefit['icon'] ); ?></span>
					<span class="home-hero__benefit-copy">
						<strong><?php echo esc_html( $benefit['title'] ); ?></strong>
						<?php if ( $benefit['text'] ) : ?><small><?php echo esc_html( $benefit['text'] ); ?></small><?php endif; ?>
					</span>
				</div>
			<?php endforeach; ?>
		</div>

		<?php if ( $slide_count > 1 ) : ?>
			<div class="home-hero__pagination" aria-label="<?php esc_attr_e( 'Choose slide', 'kanapka-theme' ); ?>" data-hero-pagination>
				<?php foreach ( $slides as $index => $slide ) : ?>
					<button type="button" aria-label="<?php echo esc_attr( sprintf( __( 'Go to slide %d', 'kanapka-theme' ), $index + 1 ) ); ?>" aria-current="<?php echo 0 === $index ? 'true' : 'false'; ?>" data-hero-dot="<?php echo esc_attr( $index ); ?>"></button>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
