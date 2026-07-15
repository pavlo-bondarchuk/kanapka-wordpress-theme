<?php
/**
 * Product search results layout.
 *
 * @package Kanapka_Theme
 */

$search_term = trim( get_search_query( false ) );
?>
<header class="product-search-page__header container">
	<?php kanapka_theme_breadcrumb(); ?>
	<div class="product-search-page__intro">
		<div>
			<p class="product-search-page__eyebrow"><?php esc_html_e( 'Product search', 'kanapka-theme' ); ?></p>
			<h1><?php esc_html_e( 'Search results', 'kanapka-theme' ); ?></h1>
			<?php if ( $search_term ) : ?>
				<p><?php echo esc_html( sprintf( __( 'Results for “%s”', 'kanapka-theme' ), $search_term ) ); ?></p>
			<?php endif; ?>
		</div>
		<form class="product-search-page__form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<label class="screen-reader-text" for="product-search-page-input"><?php esc_html_e( 'Product search', 'kanapka-theme' ); ?></label>
			<input id="product-search-page-input" type="search" name="s" value="<?php echo esc_attr( $search_term ); ?>" placeholder="<?php esc_attr_e( 'Search products', 'kanapka-theme' ); ?>" required minlength="2">
			<input type="hidden" name="post_type" value="product">
			<button class="button" type="submit"><?php esc_html_e( 'Search', 'kanapka-theme' ); ?></button>
		</form>
	</div>
</header>

<section class="product-search-page__results container" aria-label="<?php esc_attr_e( 'Search results', 'kanapka-theme' ); ?>">
	<?php get_template_part( 'template-parts/shop/product-loop' ); ?>
</section>
