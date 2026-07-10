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
<a class="skip-link" href="#main-content"><?php esc_html_e( 'Skip to content', 'kanapka-theme' ); ?></a>
<header class="site-header" data-site-header>
	<div class="site-header__inner container">
		<div class="site-branding">
			<?php if ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<a class="site-branding__fallback" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
					<img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/kanapka-logo.png' ) ); ?>" width="257" height="64" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
				</a>
			<?php endif; ?>
		</div>

		<button class="navigation-toggle" type="button" aria-expanded="false" aria-controls="site-navigation" data-navigation-toggle>
			<span class="screen-reader-text"><?php esc_html_e( 'Toggle navigation', 'kanapka-theme' ); ?></span>
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
				)
			);
			?>
		</nav>

		<div class="header-actions">
			<div class="header-contact">
				<span><?php esc_html_e( 'Order support', 'kanapka-theme' ); ?></span>
				<a href="tel:+380666917272">(066) 691-72-72</a>
				<a href="tel:+380936917272">(093) 691-72-72</a>
			</div>
			<?php get_template_part( 'template-parts/header/language-switcher' ); ?>
			<?php get_template_part( 'template-parts/header/cart-link' ); ?>
			<a class="icon-button" href="<?php echo esc_url( home_url( '/?s=' ) ); ?>" aria-label="<?php esc_attr_e( 'Search', 'kanapka-theme' ); ?>">
				<svg aria-hidden="true" viewBox="0 0 24 24" width="22" height="22"><path d="m21 21-4.35-4.35m2.35-5.65a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
			</a>
		</div>
	</div>
</header>
