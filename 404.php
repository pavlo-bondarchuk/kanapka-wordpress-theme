<?php
/**
 * The template for displaying 404 pages.
 *
 * @package Kanapka_Theme
 */

defined( 'ABSPATH' ) || exit;

$shop_url    = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
$hero_id     = function_exists( 'kanapka_theme_get_hero_image_id' ) ? absint( kanapka_theme_get_hero_image_id() ) : 0;
$phone_one   = kanapka_theme_get_option( 'kanapka_header_phone_one', '(066) 691-72-72' );
$phone_two   = kanapka_theme_get_option( 'kanapka_header_phone_two', '(093) 691-72-72' );
$email       = kanapka_theme_get_option( 'kanapka_contact_email', get_option( 'admin_email' ) );
$contact_url = kanapka_theme_get_option( 'kanapka_contact_button_url', home_url( '/contacts/' ) );

$quick_links = array(
	array(
		'icon'  => 'salad',
		'title' => __( 'Ready-made sets', 'kanapka-theme' ),
		'text'  => __( 'Selected solutions for any event', 'kanapka-theme' ),
		'url'   => $shop_url,
	),
	array(
		'icon'  => 'calendar',
		'title' => __( 'Turnkey catering', 'kanapka-theme' ),
		'text'  => __( 'Event organization in any format', 'kanapka-theme' ),
		'url'   => home_url( '/catering/' ),
	),
	array(
		'icon'  => 'percent',
		'title' => __( 'Offers and news', 'kanapka-theme' ),
		'text'  => __( 'Special offers and new flavours', 'kanapka-theme' ),
		'url'   => home_url( '/promotions/' ),
	),
	array(
		'icon'  => 'headphones',
		'title' => __( 'Need help?', 'kanapka-theme' ),
		'text'  => __( 'We are here to help with your order', 'kanapka-theme' ),
		'url'   => $contact_url,
	),
);

get_header();
?>
<main id="main-content" class="site-main error-page">
	<section class="error-hero">
		<?php if ( $hero_id ) : ?>
			<div class="error-hero__media" aria-hidden="true">
				<?php echo wp_get_attachment_image( $hero_id, 'kanapka-hero', false, array( 'loading' => 'eager', 'fetchpriority' => 'high', 'sizes' => '(max-width: 768px) 100vw, 62vw' ) ); ?>
			</div>
		<?php endif; ?>
		<div class="error-hero__inner container">
			<div class="error-hero__content">
				<h1><?php esc_html_e( '404 — Page not found', 'kanapka-theme' ); ?></h1>
				<p><?php esc_html_e( 'It looks like the page was moved or the link is outdated. We still have plenty of delicious ideas for your event!', 'kanapka-theme' ); ?></p>
				<p><?php esc_html_e( 'We suggest returning to the home page or browsing the catalogue.', 'kanapka-theme' ); ?></p>
				<div class="error-hero__actions">
					<a class="button" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo kanapka_theme_ui_icon( 'home', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php esc_html_e( 'Back to home', 'kanapka-theme' ); ?></span></a>
					<a class="button button--outline" href="<?php echo esc_url( $shop_url ); ?>"><?php echo kanapka_theme_ui_icon( 'view-grid', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php esc_html_e( 'Browse catalogue', 'kanapka-theme' ); ?></span></a>
				</div>
			</div>
		</div>
	</section>

	<div class="error-page__content container">
		<nav class="error-links" aria-label="<?php esc_attr_e( 'Helpful links', 'kanapka-theme' ); ?>">
			<?php foreach ( $quick_links as $quick_link ) : ?>
				<a class="error-link" href="<?php echo esc_url( $quick_link['url'] ); ?>">
					<span class="error-link__icon"><?php echo kanapka_theme_ui_icon( $quick_link['icon'], 28 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<span><strong><?php echo esc_html( $quick_link['title'] ); ?></strong><small><?php echo esc_html( $quick_link['text'] ); ?></small></span>
					<?php echo kanapka_theme_ui_icon( 'chevron-right', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</a>
			<?php endforeach; ?>
		</nav>

		<section class="error-categories" aria-labelledby="error-categories-title">
			<div class="error-section-heading">
				<h2 id="error-categories-title"><?php esc_html_e( 'Popular categories', 'kanapka-theme' ); ?></h2>
				<a href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'Browse catalogue', 'kanapka-theme' ); ?> <?php echo kanapka_theme_ui_icon( 'chevron-right', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
			</div>
			<?php get_template_part( 'template-parts/front-page/category-strip' ); ?>
		</section>

		<section class="error-contact" aria-labelledby="error-contact-title">
			<span class="error-contact__icon"><?php echo kanapka_theme_ui_icon( 'headphones', 32 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			<div class="error-contact__intro">
				<h2 id="error-contact-title"><?php esc_html_e( 'Did not find what you need?', 'kanapka-theme' ); ?></h2>
				<p><?php esc_html_e( 'Tell us about your event and we will help you choose the perfect menu.', 'kanapka-theme' ); ?></p>
			</div>
			<div class="error-contact__phones">
				<?php echo kanapka_theme_ui_icon( 'phone', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<span><a href="tel:<?php echo esc_attr( preg_replace( '/[^+0-9]/', '', $phone_one ) ); ?>"><?php echo esc_html( $phone_one ); ?></a><a href="tel:<?php echo esc_attr( preg_replace( '/[^+0-9]/', '', $phone_two ) ); ?>"><?php echo esc_html( $phone_two ); ?></a></span>
			</div>
			<a class="error-contact__email" href="mailto:<?php echo esc_attr( antispambot( $email ) ); ?>"><?php echo kanapka_theme_ui_icon( 'mail', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php echo esc_html( antispambot( $email ) ); ?></span></a>
			<a class="button" href="<?php echo esc_url( $contact_url ); ?>"><?php esc_html_e( 'Contact us', 'kanapka-theme' ); ?></a>
		</section>
	</div>

	<section class="error-assurances" aria-label="<?php esc_attr_e( 'Our advantages', 'kanapka-theme' ); ?>">
		<div class="error-assurances__inner container">
			<?php foreach ( kanapka_theme_get_home_hero_benefits() as $benefit ) : ?>
				<div class="error-assurance"><span><?php echo kanapka_theme_ui_icon( $benefit['icon'], 27 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span><div><strong><?php echo esc_html( $benefit['title'] ); ?></strong><small><?php echo esc_html( $benefit['text'] ); ?></small></div></div>
			<?php endforeach; ?>
			<div class="error-assurance"><span><?php echo kanapka_theme_ui_icon( 'shield-check', 27 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span><div><strong><?php esc_html_e( 'Quality guarantee', 'kanapka-theme' ); ?></strong><small><?php esc_html_e( 'Controlled at every stage', 'kanapka-theme' ); ?></small></div></div>
		</div>
	</section>
</main>
<?php
get_footer();
