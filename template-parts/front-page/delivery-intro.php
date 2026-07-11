<?php
/**
 * SEO-friendly homepage intro.
 *
 * @package Kanapka_Theme
 */

$section = kanapka_theme_get_home_seo_section();

if ( ! $section['title'] && ! $section['text'] ) {
	return;
}
?>
<section class="home-section section home-seo" aria-labelledby="home-seo-title" data-home-seo>
	<div class="home-seo__card container">
		<div class="home-seo__content">
			<h2 id="home-seo-title"><?php echo esc_html( $section['title'] ); ?></h2>
			<?php if ( $section['text'] ) : ?>
				<div class="home-seo__text" id="home-seo-text" data-home-seo-text>
					<?php echo wp_kses_post( wpautop( $section['text'] ) ); ?>
				</div>
				<button class="home-seo__toggle" type="button" aria-expanded="false" aria-controls="home-seo-text" data-home-seo-toggle>
					<span data-home-seo-toggle-label><?php esc_html_e( 'Показати більше', 'kanapka-theme' ); ?></span>
					<?php echo kanapka_theme_ui_icon( 'chevron-right', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Theme-owned SVG. ?>
				</button>
			<?php endif; ?>
		</div>
		<?php if ( $section['image_id'] ) : ?>
			<figure class="home-seo__media">
				<?php echo wp_get_attachment_image( $section['image_id'], 'large', false, array( 'loading' => 'lazy', 'sizes' => '(max-width: 900px) 90vw, 34vw' ) ); ?>
			</figure>
		<?php endif; ?>
		<?php if ( $section['benefits'] ) : ?>
			<ul class="home-seo__benefits">
				<?php foreach ( $section['benefits'] as $benefit ) : ?>
					<li>
						<span class="home-seo__benefit-icon"><?php echo kanapka_theme_ui_icon( $benefit['icon'], 34 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Theme-owned SVG. ?></span>
						<span>
							<strong><?php echo esc_html( $benefit['title'] ); ?></strong>
							<?php if ( $benefit['text'] ) : ?><small><?php echo esc_html( $benefit['text'] ); ?></small><?php endif; ?>
						</span>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
</section>
