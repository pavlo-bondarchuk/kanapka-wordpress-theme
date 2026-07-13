<?php
/**
 * Order benefits.
 *
 * @package Kanapka_Theme
 */

$section = kanapka_theme_get_home_order_benefits();

if ( empty( $section['title'] ) && empty( $section['items'] ) ) {
	return;
}
?>
<section class="home-section section order-benefits" aria-labelledby="order-benefits-title" data-order-benefits>
	<div class="container">
		<?php if ( ! empty( $section['title'] ) ) : ?>
			<div class="section-heading">
				<h2 id="order-benefits-title"><?php echo esc_html( $section['title'] ); ?></h2>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $section['items'] ) ) : ?>
			<div class="order-benefits__viewport">
				<ul class="order-benefits__grid" data-order-benefits-track>
					<?php foreach ( $section['items'] as $benefit ) : ?>
						<li class="order-benefits__item order-benefits__item--<?php echo esc_attr( $benefit['color'] ); ?>">
							<span class="order-benefits__icon" aria-hidden="true">
								<?php echo kanapka_theme_ui_icon( $benefit['icon'], 26 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</span>
							<span class="order-benefits__copy">
								<?php if ( ! empty( $benefit['title'] ) ) : ?>
									<strong><?php echo esc_html( $benefit['title'] ); ?></strong>
								<?php endif; ?>
								<?php if ( ! empty( $benefit['text'] ) ) : ?>
									<small><?php echo esc_html( $benefit['text'] ); ?></small>
								<?php endif; ?>
							</span>
						</li>
					<?php endforeach; ?>
				</ul>
				<button class="order-benefits__next" type="button" aria-label="<?php esc_attr_e( 'Show next benefit', 'kanapka-theme' ); ?>" data-order-benefits-next>
					<?php echo kanapka_theme_ui_icon( 'chevron-right', 28 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Theme-owned SVG. ?>
				</button>
			</div>
		<?php endif; ?>
	</div>
</section>
