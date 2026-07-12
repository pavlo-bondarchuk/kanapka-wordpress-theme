<?php
/**
 * Site footer.
 *
 * @package Kanapka_Theme
 */

?>
<footer class="site-footer">
	<div class="site-footer__inner container">
		<div class="site-footer__brand">
			<?php $footer_logo_id = absint( kanapka_theme_get_option( 'kanapka_footer_logo', 0 ) ); ?>
			<?php if ( $footer_logo_id ) : ?>
				<?php echo wp_get_attachment_image( $footer_logo_id, 'medium', false, array( 'alt' => get_bloginfo( 'name' ), 'loading' => 'lazy', 'class' => 'site-footer__logo' ) ); ?>
			<?php else : ?>
				<strong><?php bloginfo( 'name' ); ?></strong>
			<?php endif; ?>
			<p><?php bloginfo( 'description' ); ?></p>
		</div>
		<?php
		$footer_menus = array(
			'footer-1' => array( 'title' => __( 'Компанія', 'kanapka-theme' ), 'menu' => 'Footer menu 1' ),
			'footer-2' => array( 'title' => __( 'Каталог', 'kanapka-theme' ), 'menu' => 'Footer menu 2' ),
			'footer-3' => array( 'title' => __( 'Покупцям', 'kanapka-theme' ), 'menu' => 'Footer menu 3' ),
		);
		foreach ( $footer_menus as $location => $footer_menu ) :
			?>
			<nav class="footer-navigation" aria-label="<?php echo esc_attr( $footer_menu['title'] ); ?>">
				<h2><?php echo esc_html( $footer_menu['title'] ); ?></h2>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => $location,
						'menu'           => $footer_menu['menu'],
						'container'      => false,
						'fallback_cb'    => false,
						'depth'          => 1,
					)
				);
				?>
			</nav>
		<?php endforeach; ?>
		<div class="site-footer__contact">
			<h2><?php esc_html_e( 'Контакти', 'kanapka-theme' ); ?></h2>
			<?php $footer_phone_one = kanapka_theme_get_option( 'kanapka_header_phone_one', '(066) 691-72-72' ); ?>
			<?php $footer_phone_two = kanapka_theme_get_option( 'kanapka_header_phone_two', '(093) 691-72-72' ); ?>
			<?php $contact_email = kanapka_theme_get_option( 'kanapka_contact_email', get_option( 'admin_email' ) ); ?>
			<a href="tel:<?php echo esc_attr( preg_replace( '/[^+0-9]/', '', $footer_phone_one ) ); ?>"><?php echo esc_html( $footer_phone_one ); ?></a>
			<a href="tel:<?php echo esc_attr( preg_replace( '/[^+0-9]/', '', $footer_phone_two ) ); ?>"><?php echo esc_html( $footer_phone_two ); ?></a>
			<a href="mailto:<?php echo esc_attr( antispambot( $contact_email ) ); ?>"><?php echo esc_html( antispambot( $contact_email ) ); ?></a>
		</div>
	</div>
	<div class="site-footer__legal container">
		<p>&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?></p>
		<?php $payment_logos_id = absint( kanapka_theme_get_option( 'kanapka_footer_payment_logos', 0 ) ); ?>
		<?php if ( $payment_logos_id ) : ?>
			<?php echo wp_get_attachment_image( $payment_logos_id, 'medium', false, array( 'alt' => __( 'Платіжні системи', 'kanapka-theme' ), 'loading' => 'lazy', 'class' => 'site-footer__payment-logos' ) ); ?>
		<?php endif; ?>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
