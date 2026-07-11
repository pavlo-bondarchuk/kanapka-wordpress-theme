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
?>
<section class="home-hero" aria-roledescription="<?php esc_attr_e( 'карусель', 'kanapka-theme' ); ?>" aria-label="<?php esc_attr_e( 'Рекомендовані пропозиції', 'kanapka-theme' ); ?>" data-hero-slider data-autoplay-delay="<?php echo esc_attr( $autoplay_delay ); ?>">
	<div class="home-hero__viewport">
		<div class="home-hero__track">
			<?php foreach ( $slides as $index => $slide ) : ?>
				<?php
				$is_active          = 0 === $index;
				$is_primary_heading = 0 === $index;
				?>
				<article class="home-hero__slide<?php echo $is_active ? ' is-active' : ''; ?>" aria-roledescription="<?php esc_attr_e( 'слайд', 'kanapka-theme' ); ?>" aria-label="<?php echo esc_attr( sprintf( __( '%1$d з %2$d', 'kanapka-theme' ), $index + 1, $slide_count ) ); ?>" aria-hidden="<?php echo $is_active ? 'false' : 'true'; ?>" data-hero-slide>
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
							<?php if ( $is_primary_heading ) : ?>
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
			<button class="home-hero__arrow home-hero__arrow--previous" type="button" aria-label="<?php esc_attr_e( 'Попередній слайд', 'kanapka-theme' ); ?>" data-hero-previous>
				<?php echo kanapka_theme_ui_icon( 'chevron-left', 28 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Theme-owned SVG. ?>
			</button>
			<button class="home-hero__arrow home-hero__arrow--next" type="button" aria-label="<?php esc_attr_e( 'Наступний слайд', 'kanapka-theme' ); ?>" data-hero-next>
				<?php echo kanapka_theme_ui_icon( 'chevron-right', 28 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Theme-owned SVG. ?>
			</button>
		<?php endif; ?>

		<div class="home-hero__benefits container" aria-label="<?php esc_attr_e( 'Переваги доставки', 'kanapka-theme' ); ?>">
			<?php foreach ( $benefits as $benefit ) : ?>
				<div class="home-hero__benefit">
					<span class="home-hero__benefit-icon"><?php echo kanapka_theme_ui_icon( $benefit['icon'], 36 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Theme-owned SVG. ?></span>
					<span class="home-hero__benefit-copy">
						<strong><?php echo esc_html( $benefit['title'] ); ?></strong>
						<?php if ( $benefit['text'] ) : ?><small><?php echo esc_html( $benefit['text'] ); ?></small><?php endif; ?>
					</span>
				</div>
			<?php endforeach; ?>
		</div>

		<?php if ( $slide_count > 1 ) : ?>
			<div class="home-hero__pagination" aria-label="<?php esc_attr_e( 'Вибір слайда', 'kanapka-theme' ); ?>" data-hero-pagination>
				<?php foreach ( $slides as $index => $slide ) : ?>
					<button type="button" aria-label="<?php echo esc_attr( sprintf( __( 'Перейти до слайда %d', 'kanapka-theme' ), $index + 1 ) ); ?>" aria-current="<?php echo 0 === $index ? 'true' : 'false'; ?>" data-hero-dot="<?php echo esc_attr( $index ); ?>"></button>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
