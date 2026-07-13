<?php
/**
 * Shared product quick view dialog.
 *
 * @package Kanapka_Theme
 */
?>
<div class="product-quick-view" data-product-quick-view-modal hidden>
	<div class="product-quick-view__backdrop" data-product-quick-view-close></div>
	<div class="product-quick-view__dialog" role="dialog" aria-modal="true" aria-labelledby="product-quick-view-title" tabindex="-1" data-product-quick-view-dialog>
		<button class="product-quick-view__close" type="button" aria-label="<?php esc_attr_e( 'Close product preview', 'kanapka-theme' ); ?>" data-product-quick-view-close>&times;</button>
		<div class="product-quick-view__status" role="status" data-product-quick-view-status><?php esc_html_e( 'Loading…', 'kanapka-theme' ); ?></div>
		<div class="product-quick-view__content" data-product-quick-view-content></div>
	</div>
</div>
