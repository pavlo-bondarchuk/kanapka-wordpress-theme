<?php
/**
 * Header search popup.
 *
 * @package Kanapka_Theme
 */
?>
<div class="header-popup header-search" data-header-popup="search">
	<button class="icon-button" type="button" aria-expanded="false" aria-controls="header-search-panel" aria-label="<?php esc_attr_e( 'Search', 'kanapka-theme' ); ?>" data-header-popup-button>
		<svg aria-hidden="true" viewBox="0 0 24 24" width="22" height="22"><path d="m21 21-4.35-4.35m2.35-5.65a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
	</button>
	<div id="header-search-panel" class="header-popover header-search__panel" hidden data-header-popup-panel>
		<div class="header-popover__heading">
			<strong><?php esc_html_e( 'Search products', 'kanapka-theme' ); ?></strong>
			<button type="button" aria-label="<?php esc_attr_e( 'Close search', 'kanapka-theme' ); ?>" data-header-popup-close>&times;</button>
		</div>
		<form class="header-search__form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<label class="screen-reader-text" for="header-product-search"><?php esc_html_e( 'Search products', 'kanapka-theme' ); ?></label>
			<input id="header-product-search" type="search" name="s" placeholder="<?php esc_attr_e( 'What are you looking for?', 'kanapka-theme' ); ?>" autocomplete="off">
			<input type="hidden" name="post_type" value="product">
			<button class="button" type="submit"><?php esc_html_e( 'Search', 'kanapka-theme' ); ?></button>
		</form>
	</div>
</div>
