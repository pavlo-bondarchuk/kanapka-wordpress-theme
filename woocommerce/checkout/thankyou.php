<?php
/**
 * Order received page.
 *
 * @package Kanapka_Theme
 * @version 8.1.0
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="woocommerce-order order-received container">
	<?php if ( $order ) : ?>
		<?php do_action( 'woocommerce_before_thankyou', $order->get_id() ); ?>

		<?php if ( $order->has_status( 'failed' ) ) : ?>
			<div class="order-received__failed card">
				<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ); ?></p>
				<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
					<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php esc_html_e( 'Pay', 'woocommerce' ); ?></a>
					<?php if ( is_user_logged_in() ) : ?>
						<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button"><?php esc_html_e( 'My account', 'woocommerce' ); ?></a>
					<?php endif; ?>
				</p>
			</div>
		<?php else : ?>
			<h1 class="order-received__title"><?php esc_html_e( 'Order received', 'woocommerce' ); ?></h1>

			<div class="order-received__success" role="status">
				<span class="order-received__success-icon"><?php echo kanapka_theme_ui_icon( 'circle-check', 28 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<div>
					<strong><?php esc_html_e( 'Thank you. Your order has been received.', 'woocommerce' ); ?></strong>
					<p><?php esc_html_e( 'We will contact you shortly to confirm the details.', 'kanapka-theme' ); ?></p>
				</div>
			</div>

			<div class="order-received__layout">
				<div class="order-received__main">
					<section class="order-received__overview card" aria-labelledby="order-information-title">
						<h2 id="order-information-title"><?php esc_html_e( 'Order information', 'kanapka-theme' ); ?></h2>
						<dl>
							<div><dt><?php echo kanapka_theme_ui_icon( 'info', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php esc_html_e( 'Order number:', 'woocommerce' ); ?></span></dt><dd><?php echo esc_html( $order->get_order_number() ); ?></dd></div>
							<div><dt><?php echo kanapka_theme_ui_icon( 'calendar', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php esc_html_e( 'Date:', 'woocommerce' ); ?></span></dt><dd><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></dd></div>
							<?php if ( $order->get_billing_email() ) : ?><div><dt><?php echo kanapka_theme_ui_icon( 'mail', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php esc_html_e( 'Email:', 'woocommerce' ); ?></span></dt><dd><?php echo esc_html( $order->get_billing_email() ); ?></dd></div><?php endif; ?>
							<div><dt><?php echo kanapka_theme_ui_icon( 'wallet', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php esc_html_e( 'Total:', 'woocommerce' ); ?></span></dt><dd><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></dd></div>
							<?php if ( $order->get_payment_method_title() ) : ?><div><dt><?php echo kanapka_theme_ui_icon( 'credit-card', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php esc_html_e( 'Payment method:', 'woocommerce' ); ?></span></dt><dd><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></dd></div><?php endif; ?>
						</dl>
					</section>

					<?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
					<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>
				</div>

				<aside class="order-received__aside card" aria-label="<?php esc_attr_e( 'Next steps', 'kanapka-theme' ); ?>">
					<div class="order-received__assurance"><span><?php echo kanapka_theme_ui_icon( 'headphones', 28 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span><div><strong><?php esc_html_e( 'A manager will confirm your order', 'kanapka-theme' ); ?></strong><p><?php esc_html_e( 'We will contact you shortly to clarify the details.', 'kanapka-theme' ); ?></p></div></div>
					<div class="order-received__assurance"><span><?php echo kanapka_theme_ui_icon( 'shield-check', 28 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span><div><strong><?php esc_html_e( 'Secure payment', 'kanapka-theme' ); ?></strong><p><?php esc_html_e( 'Pay for your order using the selected payment method.', 'kanapka-theme' ); ?></p></div></div>
					<div class="order-received__assurance"><span><?php echo kanapka_theme_ui_icon( 'leaf', 28 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span><div><strong><?php esc_html_e( 'Fresh products every day', 'kanapka-theme' ); ?></strong><p><?php esc_html_e( 'We prepare your order using fresh ingredients.', 'kanapka-theme' ); ?></p></div></div>
					<a class="button order-received__shop" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php echo kanapka_theme_ui_icon( 'view-grid', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( 'Return to shop', 'woocommerce' ); ?></a>
					<a class="button button--outline order-received__home" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo kanapka_theme_ui_icon( 'home', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( 'Go to homepage', 'kanapka-theme' ); ?></a>
					<?php if ( $order->get_billing_email() ) : ?><p class="order-received__email-note"><?php echo kanapka_theme_ui_icon( 'mail', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php esc_html_e( 'A confirmation email was sent to', 'kanapka-theme' ); ?> <strong><?php echo esc_html( $order->get_billing_email() ); ?></strong></span></p><?php endif; ?>
				</aside>
			</div>
		<?php endif; ?>
	<?php else : ?>
		<?php wc_get_template( 'checkout/order-received.php', array( 'order' => false ) ); ?>
	<?php endif; ?>
</div>
