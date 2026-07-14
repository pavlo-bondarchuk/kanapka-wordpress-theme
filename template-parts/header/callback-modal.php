<?php
/**
 * Header callback dialog.
 *
 * @package Kanapka_Theme
 */

defined( 'ABSPATH' ) || exit;

$form_id = absint( $args['form_id'] ?? 0 );
$title   = sanitize_text_field( $args['title'] ?? __( 'Request a callback', 'kanapka-theme' ) );

if ( ! $form_id || 'wpcf7_contact_form' !== get_post_type( $form_id ) || ! shortcode_exists( 'contact-form-7' ) ) {
	return;
}

$form_markup = do_shortcode( '[contact-form-7 id="' . $form_id . '"]' );
$form_markup = preg_replace( '/<h[1-6][^>]*>/i', '<div class="callback-modal__form-heading">', $form_markup );
$form_markup = preg_replace( '/<\/h[1-6]>/i', '</div>', $form_markup );
?>
<div class="callback-modal" id="header-callback-modal" hidden data-callback-modal>
	<div class="callback-modal__backdrop" data-callback-modal-close></div>
	<div class="callback-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="header-callback-modal-title" tabindex="-1" data-callback-modal-dialog>
		<div class="callback-modal__header">
			<p class="callback-modal__title" id="header-callback-modal-title"><?php echo esc_html( $title ); ?></p>
			<button class="callback-modal__close" type="button" data-callback-modal-close aria-label="<?php esc_attr_e( 'Close dialog', 'kanapka-theme' ); ?>">
				<?php echo kanapka_theme_ui_icon( 'x', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</button>
		</div>
		<div class="callback-modal__content" id="callback-form">
			<?php echo $form_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
	</div>
</div>
