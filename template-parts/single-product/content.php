<?php
/**
 * Product details layout.
 *
 * @package Kanapka_Theme
 */

global $product;

if ( ! $product instanceof WC_Product ) {
	$product = wc_get_product( get_the_ID() );
}

if ( ! $product instanceof WC_Product ) {
	return;
}

$image_ids = array_values( array_unique( array_filter( array_merge( array( $product->get_image_id() ), $product->get_gallery_image_ids() ) ) ) );
$main_image_id = $image_ids ? $image_ids[0] : 0;
$description = $product->get_description() ?: $product->get_short_description();
$categories = wc_get_product_category_list( $product->get_id(), ', ' );
?>
<article <?php wc_product_class( 'single-product-detail container', $product ); ?> data-single-product>
	<div class="single-product-detail__breadcrumbs">
		<?php woocommerce_breadcrumb( array( 'delimiter' => '<span aria-hidden="true">/</span>' ) ); ?>
	</div>
	<h1 class="single-product-detail__title"><?php the_title(); ?></h1>

	<div class="single-product-detail__grid">
		<section class="product-gallery card" aria-label="<?php esc_attr_e( 'Product gallery', 'kanapka-theme' ); ?>">
			<div class="product-gallery__stage" data-product-gallery-stage>
				<?php if ( $main_image_id ) : ?>
					<?php echo wp_get_attachment_image( $main_image_id, 'full', false, array( 'class' => 'product-gallery__main-image', 'data-product-gallery-main' => '', 'sizes' => '(max-width: 780px) 92vw, 50vw', 'alt' => $product->get_name() ) ); ?>
				<?php else : ?>
					<?php echo wc_placeholder_img( 'woocommerce_single', array( 'class' => 'product-gallery__main-image' ) ); ?>
				<?php endif; ?>
			</div>

			<?php if ( count( $image_ids ) > 1 ) : ?>
				<div class="product-gallery__thumbs" aria-label="<?php esc_attr_e( 'Product images', 'kanapka-theme' ); ?>">
					<?php foreach ( $image_ids as $index => $image_id ) : ?>
						<?php $full = wp_get_attachment_image_src( $image_id, 'full' ); ?>
						<button class="product-gallery__thumb<?php echo 0 === $index ? ' is-active' : ''; ?>" type="button" data-product-gallery-thumb data-full-src="<?php echo esc_url( $full ? $full[0] : '' ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'View product image %d', 'kanapka-theme' ), $index + 1 ) ); ?>" aria-pressed="<?php echo 0 === $index ? 'true' : 'false'; ?>">
							<?php echo wp_get_attachment_image( $image_id, 'thumbnail', false, array( 'loading' => 'lazy', 'alt' => '' ) ); ?>
						</button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</section>

		<section class="product-summary-card card">
			<p class="product-summary-card__stock <?php echo $product->is_in_stock() ? 'is-in-stock' : 'is-out-of-stock'; ?>">
				<?php echo esc_html( $product->is_in_stock() ? __( 'In stock', 'kanapka-theme' ) : __( 'Out of stock', 'kanapka-theme' ) ); ?>
			</p>
			<div class="product-summary-card__price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>

			<?php if ( $product->get_short_description() ) : ?>
				<div class="product-summary-card__intro"><?php echo wp_kses_post( wpautop( $product->get_short_description() ) ); ?></div>
			<?php endif; ?>

			<div class="product-summary-card__divider"></div>

			<?php if ( $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() ) : ?>
				<div class="product-summary-card__cart">
					<div class="product-quantity" data-product-quantity>
						<button type="button" aria-label="<?php esc_attr_e( 'Decrease quantity', 'kanapka-theme' ); ?>" data-product-quantity-minus><?php echo kanapka_theme_ui_icon( 'minus', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
						<input type="number" min="1" step="1" value="1" inputmode="numeric" aria-label="<?php esc_attr_e( 'Product quantity', 'kanapka-theme' ); ?>" data-product-quantity-input>
						<button type="button" aria-label="<?php esc_attr_e( 'Increase quantity', 'kanapka-theme' ); ?>" data-product-quantity-plus><?php echo kanapka_theme_ui_icon( 'plus', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
					</div>
					<a class="button product-summary-card__button add_to_cart_button product_type_simple" href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" data-quantity="1" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>" data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>" data-kanapka-add-to-cart rel="nofollow">
						<?php echo kanapka_theme_ui_icon( 'cart', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<span><?php echo esc_html( $product->add_to_cart_text() ); ?></span>
					</a>
				</div>
			<?php else : ?>
				<div class="product-summary-card__native-cart"><?php woocommerce_template_single_add_to_cart(); ?></div>
			<?php endif; ?>

			<div class="product-summary-card__actions">
				<button type="button" class="product-summary-card__share" data-product-share data-share-title="<?php echo esc_attr( $product->get_name() ); ?>" data-share-url="<?php echo esc_url( $product->get_permalink() ); ?>">
					<?php echo kanapka_theme_ui_icon( 'share', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<span><?php esc_html_e( 'Share', 'kanapka-theme' ); ?></span>
				</button>
			</div>

			<?php if ( $categories ) : ?>
				<div class="product-summary-card__categories"><strong><?php esc_html_e( 'Categories:', 'kanapka-theme' ); ?></strong> <?php echo wp_kses_post( $categories ); ?></div>
			<?php endif; ?>
		</section>
	</div>

	<?php if ( $description ) : ?>
		<section class="product-description card" aria-labelledby="product-description-title">
			<h2 id="product-description-title">
				<span><?php echo kanapka_theme_ui_icon( 'info', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<?php esc_html_e( 'Description', 'kanapka-theme' ); ?>
			</h2>
			<div class="product-description__content"><?php echo wp_kses_post( apply_filters( 'the_content', $description ) ); ?></div>
		</section>
	<?php endif; ?>
</article>
