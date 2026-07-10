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
			<strong><?php bloginfo( 'name' ); ?></strong>
			<p><?php bloginfo( 'description' ); ?></p>
		</div>
		<?php
		$footer_menus = array(
			'footer-1' => array( 'title' => __( 'Company', 'kanapka-theme' ), 'menu' => 'Footer menu 1' ),
			'footer-2' => array( 'title' => __( 'Catalogue', 'kanapka-theme' ), 'menu' => 'Footer menu 2' ),
			'footer-3' => array( 'title' => __( 'Customers', 'kanapka-theme' ), 'menu' => 'Footer menu 3' ),
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
			<h2><?php esc_html_e( 'Contacts', 'kanapka-theme' ); ?></h2>
			<a href="tel:+380666917272">(066) 691-72-72</a>
			<a href="tel:+380936917272">(093) 691-72-72</a>
			<a href="mailto:<?php echo esc_attr( antispambot( get_option( 'admin_email' ) ) ); ?>"><?php echo esc_html( antispambot( get_option( 'admin_email' ) ) ); ?></a>
		</div>
	</div>
	<div class="site-footer__legal container">
		<p>&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?></p>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
