<?php
/**
 * WooCommerce category mega-menu.
 *
 * @package Kanapka_Theme
 */

$categories = $args['categories'] ?? array();
$show_image = ! empty( $args['show_image'] );
?>
<div class="category-mega-menu" aria-label="<?php esc_attr_e( 'Категорії товарів', 'kanapka-theme' ); ?>">
	<div class="category-mega-menu__grid">
		<?php foreach ( $categories as $category ) : ?>
			<?php
			$category_url = get_term_link( $category );
			if ( is_wp_error( $category_url ) ) {
				continue;
			}
			?>
			<a class="category-mega-menu__item" href="<?php echo esc_url( $category_url ); ?>">
				<?php if ( $show_image ) : ?>
					<?php $thumbnail_id = absint( get_term_meta( $category->term_id, 'thumbnail_id', true ) ); ?>
					<?php if ( $thumbnail_id ) : ?>
						<?php echo wp_get_attachment_image( $thumbnail_id, 'thumbnail', false, array( 'loading' => 'lazy', 'sizes' => '64px' ) ); ?>
					<?php endif; ?>
				<?php endif; ?>
				<span><?php echo esc_html( $category->name ); ?></span>
			</a>
		<?php endforeach; ?>
	</div>
</div>
