<?php
/**
 * Compact assurances below cart totals.
 *
 * @package Kanapka_Theme
 */

$items = array(
	array(
		'icon'  => 'clock-arrow-up',
		'title' => __( 'Fast order confirmation', 'kanapka-theme' ),
		'text'  => __( 'A manager will contact you to confirm the details', 'kanapka-theme' ),
	),
	array(
		'icon'  => 'hand-coins',
		'title' => __( 'Secure payment', 'kanapka-theme' ),
		'text'  => __( 'Pay by cash, card or bank transfer', 'kanapka-theme' ),
	),
	array(
		'icon'  => 'salad',
		'title' => __( 'Fresh products every day', 'kanapka-theme' ),
		'text'  => __( 'We guarantee fresh, high-quality dishes', 'kanapka-theme' ),
	),
);
?>
<ul class="cart-sidebar-benefits">
	<?php foreach ( $items as $item ) : ?>
		<li>
			<span class="cart-sidebar-benefits__icon"><?php echo kanapka_theme_ui_icon( $item['icon'], 22 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			<span><strong><?php echo esc_html( $item['title'] ); ?></strong><small><?php echo esc_html( $item['text'] ); ?></small></span>
		</li>
	<?php endforeach; ?>
</ul>
