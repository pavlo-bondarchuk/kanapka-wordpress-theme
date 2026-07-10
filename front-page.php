<?php
/**
 * Front page template.
 *
 * @package Kanapka_Theme
 */

get_header();
?>
<main id="main-content" class="site-main">
	<?php get_template_part( 'template-parts/front-page/hero' ); ?>
	<?php get_template_part( 'template-parts/front-page/category-strip' ); ?>
	<?php get_template_part( 'template-parts/front-page/category-catalogue' ); ?>
	<?php get_template_part( 'template-parts/front-page/delivery-intro' ); ?>
	<?php get_template_part( 'template-parts/front-page/popular-products' ); ?>
	<?php get_template_part( 'template-parts/front-page/services' ); ?>
	<?php get_template_part( 'template-parts/front-page/benefits' ); ?>
</main>
<?php
get_footer();

