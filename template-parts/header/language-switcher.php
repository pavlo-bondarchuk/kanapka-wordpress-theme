<?php
/**
 * Language switcher with graceful fallback.
 *
 * @package Kanapka_Theme
 */

$variant = isset( $args['variant'] ) ? sanitize_html_class( $args['variant'] ) : 'default';

$language_labels = array(
	'ru' => 'Русский',
	'uk' => 'Українська',
);

$shorten_weglot_name = static function ( $name, $language ) use ( $language_labels ) {
	if ( ! is_object( $language ) || ! method_exists( $language, 'getExternalCode' ) ) {
		return $name;
	}

	$language_code = strtolower( $language->getExternalCode() );

	return $language_labels[ $language_code ] ?? strtoupper( $language_code );
};

$weglot_switcher = '';

if ( shortcode_exists( 'weglot_switcher' ) ) {
	add_filter( 'weglot_get_name_with_language_entry', $shorten_weglot_name, 10, 2 );
	$weglot_switcher = do_shortcode( '[weglot_switcher]' );
	remove_filter( 'weglot_get_name_with_language_entry', $shorten_weglot_name, 10 );
}

$languages = apply_filters( 'wpml_active_languages', null, array( 'skip_missing' => 0 ) );
?>
<div class="language-switcher language-switcher--<?php echo esc_attr( $variant ); ?>" aria-label="<?php esc_attr_e( 'Language', 'kanapka-theme' ); ?>">
	<?php if ( $weglot_switcher ) : ?>
		<?php echo $weglot_switcher; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Trusted Weglot shortcode markup. ?>
	<?php elseif ( is_array( $languages ) && $languages ) : ?>
		<?php foreach ( $languages as $language ) : ?>
			<?php $language_code = strtolower( $language['language_code'] ); ?>
			<a href="<?php echo esc_url( $language['url'] ); ?>" lang="<?php echo esc_attr( $language_code ); ?>" <?php echo ! empty( $language['active'] ) ? 'aria-current="page"' : ''; ?>>
				<?php echo esc_html( $language_labels[ $language_code ] ?? strtoupper( $language_code ) ); ?>
			</a>
		<?php endforeach; ?>
	<?php else : ?>
		<?php $locale_code = strtolower( substr( get_locale(), 0, 2 ) ); ?>
		<span aria-current="page"><?php echo esc_html( $language_labels[ $locale_code ] ?? strtoupper( $locale_code ) ); ?></span>
	<?php endif; ?>
</div>
