<?php
/**
 * Product archives.
 *
 * @package Kanapka_Theme
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="main-content" class="site-main catalogue-page">
	<?php get_template_part( 'template-parts/shop/hero' ); ?>
	<?php get_template_part( 'template-parts/shop/category-rail' ); ?>

	<section class="catalogue-layout container" aria-label="<?php esc_attr_e( 'Каталог товарів', 'kanapka-theme' ); ?>">
		<?php get_template_part( 'template-parts/shop/sidebar' ); ?>
		<div class="catalogue-content">
			<?php
			if ( is_shop() ) {
				get_template_part( 'template-parts/shop/category-grid' );
			} else {
				get_template_part( 'template-parts/shop/product-loop' );
			}
			?>
		</div>
	</section>

	<?php get_template_part( 'template-parts/shop/seo-content' ); ?>
	<?php get_template_part( 'template-parts/front-page/popular-products' ); ?>
	<?php get_template_part( 'template-parts/front-page/services' ); ?>
	<?php get_template_part( 'template-parts/front-page/client-brands' ); ?>
	<?php get_template_part( 'template-parts/front-page/benefits' ); ?>
</main>
<?php
get_footer();
