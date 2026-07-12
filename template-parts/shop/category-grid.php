<?php

/**
 * Product category grid for the main shop page.
 *
 * @package Kanapka_Theme
 */

$categories = kanapka_theme_get_shop_categories();

if (! $categories) {
	return;
}
?>
<div class="catalogue-toolbar">
	<p><?php echo esc_html(sprintf(_n('Показано %s категорію', 'Показано %s категорій', count($categories), 'kanapka-theme'), number_format_i18n(count($categories)))); ?></p>
</div>
<div class="catalogue-category-grid">
	<?php foreach ($categories as $category) : ?>
		<?php
		$category_url = get_term_link($category);
		$thumbnail_id = absint(get_term_meta($category->term_id, 'thumbnail_id', true));

		if (is_wp_error($category_url)) {
			continue;
		}
		?>
		<article class="catalogue-category-card card">
			<a href="<?php echo esc_url($category_url); ?>">
				<span class="catalogue-category-card__media">
					<?php if ($thumbnail_id) : ?>
						<?php echo wp_get_attachment_image($thumbnail_id, 'kanapka-category', false, array('alt' => $category->name, 'loading' => 'lazy', 'sizes' => '(max-width: 640px) 46vw, 220px')); ?>
					<?php endif; ?>
				</span>
				<span class="catalogue-category-card__body">
					<strong><?php echo esc_html($category->name); ?></strong>
					<small><?php echo $category->count; ?></small>
				</span>
			</a>
		</article>
	<?php endforeach; ?>
</div>