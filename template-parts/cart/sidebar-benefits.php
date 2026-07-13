<?php
/**
 * Compact assurances below cart totals.
 *
 * @package Kanapka_Theme
 */

$items = array(
	array(
		'icon'  => 'clock',
		'title' => __( 'Швидке підтвердження замовлення', 'kanapka-theme' ),
		'text'  => __( 'Менеджер зв’яжеться з вами для уточнення деталей', 'kanapka-theme' ),
	),
	array(
		'icon'  => 'briefcase',
		'title' => __( 'Безпечна оплата', 'kanapka-theme' ),
		'text'  => __( 'Оплата готівкою, карткою або безготівково', 'kanapka-theme' ),
	),
	array(
		'icon'  => 'badge-check',
		'title' => __( 'Свіжі продукти щодня', 'kanapka-theme' ),
		'text'  => __( 'Гарантуємо свіжість та високу якість страв', 'kanapka-theme' ),
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
