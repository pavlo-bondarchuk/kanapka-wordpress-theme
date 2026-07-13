<?php
/**
 * Checkout form.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.4.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_checkout_form', $checkout );

if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
	return;
}
?>
<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data" aria-label="<?php echo esc_attr__( 'Checkout', 'woocommerce' ); ?>">
	<div class="checkout-layout">
		<section class="checkout-details card">
			<?php if ( $checkout->get_checkout_fields() ) : ?>
				<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>
				<div class="col2-set" id="customer_details">
					<div class="col-1"><?php do_action( 'woocommerce_checkout_billing' ); ?></div>
					<div class="col-2"><?php do_action( 'woocommerce_checkout_shipping' ); ?></div>
				</div>
				<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
			<?php endif; ?>
			<div class="checkout-payment-slot" data-checkout-payment-slot>
				<div class="checkout-payment-slot__heading">
					<h2><?php esc_html_e( 'Payment', 'woocommerce' ); ?></h2>
					<p><?php esc_html_e( 'Choose a convenient payment method', 'kanapka-theme' ); ?></p>
				</div>
			</div>
		</section>

		<aside class="checkout-summary card">
			<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
			<div class="checkout-summary__heading">
				<h2 id="order_review_heading"><?php esc_html_e( 'Your order', 'woocommerce' ); ?></h2>
				<a href="<?php echo esc_url( wc_get_cart_url() ); ?>"><?php echo kanapka_theme_ui_icon( 'edit', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php esc_html_e( 'Edit cart', 'woocommerce' ); ?></span></a>
			</div>
			<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>
			<div id="order_review" class="woocommerce-checkout-review-order">
				<?php do_action( 'woocommerce_checkout_order_review' ); ?>
			</div>
			<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
		</aside>
	</div>
</form>
<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
