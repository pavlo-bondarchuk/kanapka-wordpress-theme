<?php
/**
 * Header search popup.
 *
 * @package Kanapka_Theme
 */
?>
<div class="header-popup header-search" data-header-popup="search">
	<button class="icon-button" type="button" aria-expanded="false" aria-controls="header-search-panel" aria-label="<?php esc_attr_e( 'Search', 'kanapka-theme' ); ?>" data-header-popup-button>
		<?php echo kanapka_theme_ui_icon( 'search', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Theme-owned SVG. ?>
	</button>
	<div id="header-search-panel" class="header-popover header-search__panel" hidden data-header-popup-panel>
		<div class="header-popover__heading">
			<strong><?php esc_html_e( 'Product search', 'kanapka-theme' ); ?></strong>
			<button type="button" aria-label="<?php esc_attr_e( 'Close search', 'kanapka-theme' ); ?>" data-header-popup-close>&times;</button>
		</div>
		<form class="header-search__form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" data-header-search-form>
			<label class="screen-reader-text" for="header-product-search"><?php esc_html_e( 'Product search', 'kanapka-theme' ); ?></label>
			<input id="header-product-search" type="search" name="s" placeholder="<?php esc_attr_e( 'What are you looking for?', 'kanapka-theme' ); ?>" autocomplete="off" required minlength="2" data-header-search-input>
			<input type="hidden" name="post_type" value="product">
			<button class="button" type="submit" data-header-search-submit><?php esc_html_e( 'Search', 'kanapka-theme' ); ?></button>
		</form>
		<div class="header-search__suggestions" hidden aria-live="polite" data-header-search-suggestions></div>
	</div>
</div>
