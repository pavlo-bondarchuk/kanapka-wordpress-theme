<?php
/**
 * Page template.
 *
 * @package Kanapka_Theme
 */

get_header();
?>
<?php
$is_order_received = function_exists( 'is_wc_endpoint_url' ) && is_wc_endpoint_url( 'order-received' );
$is_checkout_page  = function_exists( 'is_checkout' ) && is_checkout() && ! $is_order_received && ( ! function_exists( 'is_wc_endpoint_url' ) || ! is_wc_endpoint_url() );
?>
<main id="main-content" class="site-main<?php echo function_exists( 'is_cart' ) && is_cart() ? ' cart-page' : ( $is_checkout_page ? ' checkout-page' : ( $is_order_received ? ' order-received-shell' : ' container' ) ); ?>">
	<?php while ( have_posts() ) : ?>
		<?php the_post(); ?>
		<?php if ( $is_order_received ) : ?>
			<div class="order-received-page">
				<?php the_content(); ?>
			</div>
			<?php get_template_part( 'template-parts/front-page/benefits' ); ?>
		<?php elseif ( $is_checkout_page ) : ?>
			<header class="checkout-page__header container">
				<?php woocommerce_breadcrumb( array( 'delimiter' => '<span aria-hidden="true">/</span>' ) ); ?>
				<h1><?php the_title(); ?></h1>
				<p><?php esc_html_e( 'Complete your details and confirm your order. We will contact you to clarify the details.', 'kanapka-theme' ); ?></p>
			</header>
			<div class="checkout-page__content container">
				<?php the_content(); ?>
			</div>
			<?php if ( function_exists( 'WC' ) && WC()->cart && ! WC()->cart->is_empty() ) : ?>
				<?php get_template_part( 'template-parts/front-page/benefits' ); ?>
			<?php endif; ?>
		<?php elseif ( function_exists( 'is_cart' ) && is_cart() ) : ?>
			<header class="cart-page__header container">
				<?php woocommerce_breadcrumb( array( 'delimiter' => '<span aria-hidden="true">/</span>' ) ); ?>
				<h1><?php the_title(); ?></h1>
			</header>
			<div class="cart-page__content container">
				<?php the_content(); ?>
			</div>
			<?php if ( function_exists( 'WC' ) && WC()->cart && ! WC()->cart->is_empty() ) : ?>
				<?php get_template_part( 'template-parts/cart/recommendations' ); ?>
				<?php get_template_part( 'template-parts/front-page/benefits' ); ?>
				<?php get_template_part( 'template-parts/cart/assurance' ); ?>
			<?php endif; ?>
		<?php else : ?>
			<article <?php post_class( 'content-entry' ); ?>>
				<h1><?php the_title(); ?></h1>
				<?php the_content(); ?>
			</article>
		<?php endif; ?>
	<?php endwhile; ?>
</main>
<?php
get_footer();
