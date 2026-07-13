<?php
/**
 * Cart privacy and contact assurance.
 *
 * @package Kanapka_Theme
 */

$phone_one = kanapka_theme_get_option( 'kanapka_header_phone_one', '(066) 691-72-72' );
$phone_two = kanapka_theme_get_option( 'kanapka_header_phone_two', '(093) 691-72-72' );
?>
<div class="cart-assurance container">
	<p><?php echo kanapka_theme_ui_icon( 'shield-check', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php esc_html_e( 'Ваші дані захищені та не передаються третім особам', 'kanapka-theme' ); ?></span></p>
	<p>
		<?php esc_html_e( 'Є питання? Зателефонуйте', 'kanapka-theme' ); ?>
		<a href="tel:<?php echo esc_attr( preg_replace( '/[^+0-9]/', '', $phone_one ) ); ?>"><?php echo esc_html( $phone_one ); ?></a>
		<?php esc_html_e( 'або', 'kanapka-theme' ); ?>
		<a href="tel:<?php echo esc_attr( preg_replace( '/[^+0-9]/', '', $phone_two ) ); ?>"><?php echo esc_html( $phone_two ); ?></a>
	</p>
</div>
