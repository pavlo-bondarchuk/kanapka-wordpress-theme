<?php
/**
 * Product category card.
 *
 * @package Kanapka_Theme
 */

$category = $args['category'] ?? null;
$compact  = ! empty( $args['compact'] );

if ( ! $category instanceof WP_Term ) {
	return;
}

$thumbnail_id = (int) get_term_meta( $category->term_id, 'thumbnail_id', true );
$link         = get_term_link( $category );

if ( is_wp_error( $link ) ) {
	return;
}
?>
<a class="category-card<?php echo $compact ? ' category-card--compact' : ''; ?>" href="<?php echo esc_url( $link ); ?>">
	<span class="category-card__media">
		<?php
		if ( $thumbnail_id ) {
			$image_alt = get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true );
			$image_alt = '' !== $image_alt ? $image_alt : $category->name;

			echo wp_get_attachment_image(
				$thumbnail_id,
				'kanapka-category',
				false,
				array(
					'alt'     => $image_alt,
					'loading' => 'lazy',
					'sizes'   => $compact ? '140px' : '(max-width: 640px) 50vw, 200px',
				)
			);
		}
		?>
	</span>
	<span class="category-card__title"><?php echo esc_html( $category->name ); ?></span>
</a>
