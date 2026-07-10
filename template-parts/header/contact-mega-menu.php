<?php
/**
 * Header contact mega-menu.
 *
 * @package Kanapka_Theme
 */

$phone_one   = kanapka_theme_get_option( 'kanapka_header_phone_one', '(066) 691-72-72' );
$phone_two   = kanapka_theme_get_option( 'kanapka_header_phone_two', '(093) 691-72-72' );
$email       = kanapka_theme_get_option( 'kanapka_contact_email', get_option( 'admin_email' ) );
$address_one = kanapka_theme_get_option( 'kanapka_contact_address_one', __( 'Kyiv', 'kanapka-theme' ) );
$address_two = kanapka_theme_get_option( 'kanapka_contact_address_two', '' );
$hours       = kanapka_theme_get_multiline_option( 'kanapka_header_work_hours', '' );
$button_url  = kanapka_theme_get_option( 'kanapka_contact_button_url', '' );
?>
<div class="contact-mega-menu" aria-label="<?php esc_attr_e( 'Contact information', 'kanapka-theme' ); ?>">
	<div class="contact-mega-menu__column">
		<h2><?php echo esc_html( kanapka_theme_get_option( 'kanapka_contact_column_title', __( 'Contacts', 'kanapka-theme' ) ) ); ?></h2>
		<a href="tel:<?php echo esc_attr( preg_replace( '/[^+0-9]/', '', $phone_one ) ); ?>"><?php echo esc_html( $phone_one ); ?></a>
		<a href="tel:<?php echo esc_attr( preg_replace( '/[^+0-9]/', '', $phone_two ) ); ?>"><?php echo esc_html( $phone_two ); ?></a>
		<a href="mailto:<?php echo esc_attr( antispambot( $email ) ); ?>"><?php echo esc_html( antispambot( $email ) ); ?></a>
	</div>
	<div class="contact-mega-menu__column">
		<h2><?php esc_html_e( 'Address', 'kanapka-theme' ); ?></h2>
		<address><?php echo esc_html( $address_one ); ?><br><?php echo esc_html( $address_two ); ?></address>
		<?php if ( $hours ) : ?>
			<h2><?php echo esc_html( kanapka_theme_get_option( 'kanapka_contact_hours_title', __( 'Orders are accepted', 'kanapka-theme' ) ) ); ?></h2>
			<p><?php echo nl2br( esc_html( $hours ) ); ?></p>
		<?php endif; ?>
		<?php if ( $button_url ) : ?>
			<a class="button" href="<?php echo esc_url( $button_url ); ?>"><?php echo esc_html( kanapka_theme_get_option( 'kanapka_contact_button_label', __( 'Contact form', 'kanapka-theme' ) ) ); ?></a>
		<?php endif; ?>
	</div>
</div>
