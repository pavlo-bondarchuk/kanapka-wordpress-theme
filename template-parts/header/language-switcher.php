<?php
/**
 * Language switcher with graceful fallback.
 *
 * @package Kanapka_Theme
 */

$weglot_switcher = shortcode_exists( 'weglot_switcher' ) ? do_shortcode( '[weglot_switcher]' ) : '';
$languages       = apply_filters( 'wpml_active_languages', null, array( 'skip_missing' => 0 ) );
?>
<div class="language-switcher" aria-label="<?php esc_attr_e( 'Language', 'kanapka-theme' ); ?>">
	<?php if ( $weglot_switcher ) : ?>
		<?php echo $weglot_switcher; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Trusted Weglot shortcode markup. ?>
	<?php elseif ( is_array( $languages ) && $languages ) : ?>
		<?php foreach ( $languages as $language ) : ?>
			<a href="<?php echo esc_url( $language['url'] ); ?>" lang="<?php echo esc_attr( $language['language_code'] ); ?>" <?php echo ! empty( $language['active'] ) ? 'aria-current="page"' : ''; ?>>
				<?php echo esc_html( strtoupper( $language['language_code'] ) ); ?>
			</a>
		<?php endforeach; ?>
	<?php else : ?>
		<span aria-current="page"><?php echo esc_html( strtoupper( substr( get_locale(), 0, 2 ) ) ); ?></span>
	<?php endif; ?>
</div>
