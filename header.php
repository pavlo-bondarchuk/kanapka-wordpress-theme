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
	<div class="header-top-bar">
		<div class="header-top-bar__inner container">
			<?php get_template_part( 'template-parts/header/language-switcher', null, array( 'variant' => 'top-bar' ) ); ?>
			<nav class="header-top-navigation" aria-label="<?php esc_attr_e( 'Secondary navigation', 'kanapka-theme' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'top-bar',
						'menu'           => 'Top Horizontal Menu',
						'container'      => false,
						'menu_class'     => 'header-top-navigation__menu',
						'fallback_cb'    => false,
						'depth'          => 1,
					)
				);
				?>
			</nav>
		</div>
	</div>
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
					'walker'         => new Kanapka_Theme_Nav_Walker(),
				)
			);
			?>
		</nav>

		<div class="header-actions">
			<div class="header-contact">
				<span><?php echo esc_html( kanapka_theme_get_option( 'kanapka_header_order_label', __( 'Order reception', 'kanapka-theme' ) ) ); ?></span>
				<?php $phone_one = kanapka_theme_get_option( 'kanapka_header_phone_one', '(066) 691-72-72' ); ?>
				<?php $phone_two = kanapka_theme_get_option( 'kanapka_header_phone_two', '(093) 691-72-72' ); ?>
				<a href="tel:<?php echo esc_attr( preg_replace( '/[^+0-9]/', '', $phone_one ) ); ?>"><?php echo esc_html( $phone_one ); ?></a>
				<a href="tel:<?php echo esc_attr( preg_replace( '/[^+0-9]/', '', $phone_two ) ); ?>"><?php echo esc_html( $phone_two ); ?></a>
				<?php $callback_form_id = absint( kanapka_theme_get_option( 'kanapka_header_callback_form', 0 ) ); ?>
				<?php $has_callback_form = $callback_form_id && 'wpcf7_contact_form' === get_post_type( $callback_form_id ) && shortcode_exists( 'contact-form-7' ); ?>
				<a class="header-contact__callback" href="#callback-form"<?php echo $has_callback_form ? ' data-callback-modal-open aria-haspopup="dialog" aria-controls="header-callback-modal"' : ''; ?>><?php esc_html_e( 'Could not reach us…', 'kanapka-theme' ); ?></a>
			</div>
			<?php $order_info = (string) kanapka_theme_get_option( 'kanapka_header_work_hours', '' ); ?>
			<?php if ( $order_info ) : ?>
				<div class="header-order-info">
					<span><?php echo esc_html( kanapka_theme_get_option( 'kanapka_header_order_info_label', __( 'Order reception details', 'kanapka-theme' ) ) ); ?></span>
					<div class="header-order-info__content"><?php echo wp_kses_post( wpautop( $order_info ) ); ?></div>
				</div>
			<?php endif; ?>
			<?php get_template_part( 'template-parts/header/cart-link' ); ?>
			<?php get_template_part( 'template-parts/header/search-popup' ); ?>
		</div>
	</div>
</header>
<?php if ( ! empty( $has_callback_form ) ) : ?>
	<?php get_template_part( 'template-parts/header/callback-modal', null, array( 'form_id' => $callback_form_id, 'title' => __( 'Could not reach us…', 'kanapka-theme' ) ) ); ?>
<?php endif; ?>
