<?php
/**
 * Site header.
 *
 * @package Kanapka_Theme
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main-content"><?php esc_html_e( 'Перейти до вмісту', 'kanapka-theme' ); ?></a>
<header class="site-header" data-site-header>
	<div class="site-header__inner container">
		<div class="site-branding">
			<?php $header_logo_id = absint( kanapka_theme_get_option( 'kanapka_header_logo', 0 ) ); ?>
			<?php if ( $header_logo_id ) : ?>
				<a class="site-branding__custom" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
					<?php echo wp_get_attachment_image( $header_logo_id, 'medium', false, array( 'alt' => get_bloginfo( 'name' ), 'loading' => 'eager', 'fetchpriority' => 'high' ) ); ?>
				</a>
			<?php elseif ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<a class="site-branding__fallback" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
					<img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/kanapka-logo.png' ) ); ?>" width="257" height="64" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
				</a>
			<?php endif; ?>
		</div>

		<button class="navigation-toggle" type="button" aria-expanded="false" aria-controls="site-navigation" data-navigation-toggle>
			<span class="screen-reader-text"><?php esc_html_e( 'Перемкнути навігацію', 'kanapka-theme' ); ?></span>
			<span aria-hidden="true"></span>
			<span aria-hidden="true"></span>
			<span aria-hidden="true"></span>
		</button>

		<nav id="site-navigation" class="primary-navigation" data-navigation>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'menu'           => 'main menu',
					'container'      => false,
					'menu_class'     => 'primary-navigation__menu',
					'fallback_cb'    => false,
					'depth'          => 2,
					'walker'         => new Kanapka_Theme_Nav_Walker(),
				)
			);
			?>
		</nav>

		<div class="header-actions">
			<div class="header-contact">
				<span><?php echo esc_html( kanapka_theme_get_option( 'kanapka_header_order_label', __( 'Прийом замовлень', 'kanapka-theme' ) ) ); ?></span>
				<?php $phone_one = kanapka_theme_get_option( 'kanapka_header_phone_one', '(066) 691-72-72' ); ?>
				<?php $phone_two = kanapka_theme_get_option( 'kanapka_header_phone_two', '(093) 691-72-72' ); ?>
				<a href="tel:<?php echo esc_attr( preg_replace( '/[^+0-9]/', '', $phone_one ) ); ?>"><?php echo esc_html( $phone_one ); ?></a>
				<a href="tel:<?php echo esc_attr( preg_replace( '/[^+0-9]/', '', $phone_two ) ); ?>"><?php echo esc_html( $phone_two ); ?></a>
				<?php $work_hours = kanapka_theme_get_multiline_option( 'kanapka_header_work_hours', '' ); ?>
				<?php if ( $work_hours ) : ?>
					<small class="header-contact__hours"><?php echo nl2br( esc_html( $work_hours ) ); ?></small>
				<?php endif; ?>
			</div>
			<?php get_template_part( 'template-parts/header/language-switcher' ); ?>
			<?php get_template_part( 'template-parts/header/cart-link' ); ?>
			<?php get_template_part( 'template-parts/header/search-popup' ); ?>
		</div>
	</div>
</header>
