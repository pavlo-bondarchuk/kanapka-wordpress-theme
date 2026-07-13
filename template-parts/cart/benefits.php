<?php
/**
 * Reuse homepage order benefits on the cart page.
 *
 * @package Kanapka_Theme
 */

$section = kanapka_theme_get_home_order_benefits();

if ( empty( $section['items'] ) ) {
	return;
}
?>
<section class="cart-benefits container card" aria-label="<?php esc_attr_e( 'Переваги замовлення', 'kanapka-theme' ); ?>">
	<ul class="cart-benefits__grid">
		<?php foreach ( array_slice( $section['items'], 0, 6 ) as $benefit ) : ?>
			<li class="cart-benefits__item cart-benefits__item--<?php echo esc_attr( $benefit['color'] ); ?>">
				<span class="cart-benefits__icon"><?php echo kanapka_theme_ui_icon( $benefit['icon'], 30 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<?php if ( ! empty( $benefit['title'] ) ) : ?><strong><?php echo esc_html( $benefit['title'] ); ?></strong><?php endif; ?>
				<?php if ( ! empty( $benefit['text'] ) ) : ?><small><?php echo esc_html( $benefit['text'] ); ?></small><?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ul>
</section>
