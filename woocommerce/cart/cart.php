<?php
/**
 * Cart page.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 10.8.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' );
?>
<div class="kanapka-cart-layout">
	<form class="woocommerce-cart-form card" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
		<?php do_action( 'woocommerce_before_cart_table' ); ?>

		<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
			<thead>
				<tr>
					<th class="product-remove"><span class="screen-reader-text"><?php esc_html_e( 'Remove item', 'woocommerce' ); ?></span></th>
					<th class="product-thumbnail"><span class="screen-reader-text"><?php esc_html_e( 'Thumbnail image', 'woocommerce' ); ?></span></th>
					<th scope="col" class="product-name"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
					<th scope="col" class="product-price"><?php esc_html_e( 'Price', 'woocommerce' ); ?></th>
					<th scope="col" class="product-quantity"><?php esc_html_e( 'Quantity', 'woocommerce' ); ?></th>
					<th scope="col" class="product-subtotal"><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php do_action( 'woocommerce_before_cart_contents' ); ?>

				<?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) : ?>
					<?php
					$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
					$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
					$visible    = apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key );

					if ( ! $_product instanceof WC_Product || ! $_product->exists() || $cart_item['quantity'] <= 0 || ! $visible ) {
						continue;
					}

					$product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
					$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
					?>
					<tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
						<td class="product-remove">
							<?php
							echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								'woocommerce_cart_item_remove_link',
								sprintf(
									'<a role="button" href="%1$s" class="remove" aria-label="%2$s" data-product_id="%3$s" data-product_sku="%4$s">&times;</a>',
									esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
									esc_attr( sprintf( __( 'Remove %s from cart', 'woocommerce' ), wp_strip_all_tags( $product_name ) ) ),
									esc_attr( $product_id ),
									esc_attr( $_product->get_sku() )
								),
								$cart_item_key
							);
							?>
						</td>
						<td class="product-thumbnail">
							<?php $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( 'woocommerce_thumbnail', array( 'class' => 'cart-product-image' ) ), $cart_item, $cart_item_key ); ?>
							<?php if ( $product_permalink ) : ?>
								<a href="<?php echo esc_url( $product_permalink ); ?>"><?php echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
							<?php else : ?>
								<?php echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<?php endif; ?>
						</td>
						<td scope="row" role="rowheader" class="product-name" data-title="<?php esc_attr_e( 'Product', 'woocommerce' ); ?>">
							<?php if ( $product_permalink ) : ?>
								<?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) ); ?>
							<?php else : ?>
								<?php echo wp_kses_post( $product_name ); ?>
							<?php endif; ?>
							<?php do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key ); ?>
							<?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<?php if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) : ?>
								<p class="backorder_notification"><?php esc_html_e( 'Available on backorder', 'woocommerce' ); ?></p>
							<?php endif; ?>
						</td>
						<td class="product-price" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
							<?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</td>
						<td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>">
							<?php
							$product_quantity = woocommerce_quantity_input(
								array(
									'input_name'   => "cart[{$cart_item_key}][qty]",
									'input_value'  => $cart_item['quantity'],
									'max_value'    => $_product->is_sold_individually() ? 1 : $_product->get_max_purchase_quantity(),
									'min_value'    => $_product->is_sold_individually() ? 1 : 0,
									'product_name' => $product_name,
								),
								$_product,
								false
							);
							$product_quantity = preg_replace( '/<label\b[^>]*class="screen-reader-text"[^>]*>.*?<\/label>/s', '', $product_quantity );
							echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							?>
						</td>
						<td class="product-subtotal" data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>">
							<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</td>
					</tr>
				<?php endforeach; ?>

				<?php do_action( 'woocommerce_cart_contents' ); ?>
				<tr class="cart-actions-row">
					<td colspan="6" class="actions">
						<?php if ( wc_coupons_enabled() ) : ?>
							<div class="coupon">
								<label for="coupon_code" class="screen-reader-text"><?php esc_html_e( 'Coupon:', 'woocommerce' ); ?></label>
								<input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>">
								<button type="submit" class="button button--outline" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php esc_html_e( 'Apply coupon', 'woocommerce' ); ?></button>
								<?php do_action( 'woocommerce_cart_coupon' ); ?>
							</div>
						<?php endif; ?>
						<div class="cart-actions">
							<button type="submit" class="button cart-action cart-action--empty" name="kanapka_empty_cart" value="1">
								<?php echo kanapka_theme_ui_icon( 'trash', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<span><?php esc_html_e( 'Clear cart', 'kanapka-theme' ); ?></span>
							</button>
							<?php do_action( 'woocommerce_cart_actions' ); ?>
							<button type="submit" class="button cart-action cart-action--update" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>">
								<?php echo kanapka_theme_ui_icon( 'refresh', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<span><?php esc_html_e( 'Update cart', 'woocommerce' ); ?></span>
							</button>
						</div>
						<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
					</td>
				</tr>
				<?php do_action( 'woocommerce_after_cart_contents' ); ?>
			</tbody>
		</table>
		<?php do_action( 'woocommerce_after_cart_table' ); ?>
	</form>

	<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>
	<aside class="cart-collaterals card" aria-label="<?php esc_attr_e( 'Cart totals', 'woocommerce' ); ?>">
		<?php do_action( 'woocommerce_cart_collaterals' ); ?>
		<?php get_template_part( 'template-parts/cart/sidebar-benefits' ); ?>
	</aside>
</div>
<?php do_action( 'woocommerce_after_cart' ); ?>
