<?php
/**
 * Page template.
 *
 * @package Kanapka_Theme
 */

get_header();
?>
<main id="main-content" class="site-main<?php echo function_exists( 'is_cart' ) && is_cart() ? ' cart-page' : ' container'; ?>">
	<?php while ( have_posts() ) : ?>
		<?php the_post(); ?>
		<?php if ( function_exists( 'is_cart' ) && is_cart() ) : ?>
			<header class="cart-page__header container">
				<?php woocommerce_breadcrumb( array( 'delimiter' => '<span aria-hidden="true">/</span>' ) ); ?>
				<h1><?php the_title(); ?></h1>
			</header>
			<div class="cart-page__content container">
				<?php the_content(); ?>
			</div>
			<?php if ( function_exists( 'WC' ) && WC()->cart && ! WC()->cart->is_empty() ) : ?>
				<?php get_template_part( 'template-parts/cart/benefits' ); ?>
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
