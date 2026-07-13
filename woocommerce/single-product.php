<?php
/**
 * Single product page.
 *
 * @package Kanapka_Theme
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="main-content" class="site-main single-product-page">
	<?php while ( have_posts() ) : ?>
		<?php the_post(); ?>
		<?php get_template_part( 'template-parts/single-product/content' ); ?>
	<?php endwhile; ?>

	<?php get_template_part( 'template-parts/single-product/related-products' ); ?>
	<?php get_template_part( 'template-parts/front-page/services' ); ?>
	<?php get_template_part( 'template-parts/front-page/client-brands' ); ?>
	<?php get_template_part( 'template-parts/front-page/benefits' ); ?>
</main>
<?php
get_footer();
