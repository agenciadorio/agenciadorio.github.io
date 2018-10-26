<?php
if ( ! function_exists( 'add_action' ) ) {
	exit( 0 );
}

use Woocommerce\Moip\Core;
use Woocommerce\Moip\Helper\Utils;
use Woocommerce\Moip\Model\Customer;
use Woocommerce\Moip\View\Checkouts as Checkouts_View;

$public_key = $this->model->settings->public_key;
$customer   = new Customer( get_current_user_id() );
$model      = $this->model;

?>

<form <?php echo Utils::get_component( 'checkout-default' ); ?>
      method="post"
	  id="wc-moip-payment-checkout-form"
	  data-return-url="<?php echo esc_url( $this->get_return_url( $wc_order ) ); ?>"
	  data-encrypt="<?php echo empty( $public_key ) ? false : true; ?>"
	  data-store-credit-card="<?php echo $customer->stored_credit_card ? 1 : 0; ?>"
	  data-order="<?php echo $wc_order->get_order_number(); ?>">

	<div class="product">

		<div class="woocommerce-tabs">

			<ul class="tabs">

				<?php if ( $this->model->settings->is_active_credit_card() ) : ?>

					<li class="active">
						<a data-action="tab" data-ref="creditCard" href="#tab-credit-card">
							<?php _e( 'Pay with credit card', Core::TEXTDOMAIN ); ?>
						</a>
					</li>

				<?php endif; ?>

				<?php if ( $this->model->settings->is_active_billet_banking() ) : ?>

					<li>
						<a data-action="tab" data-ref="boleto" href="#tab-billet">
							<?php _e( 'Pay with billet banking', Core::TEXTDOMAIN ); ?>
						</a>
					</li>

				<?php endif; ?>

			</ul>

			<div id="payment">
				<ul class="wc_payment_methods payment_methods methods">
				<?php
					Utils::template_include(
						'templates/payment-methods/credit-card',
						compact( 'public_key', 'customer', 'wc_order', 'model' )
					);

					Utils::template_include(
						'templates/payment-methods/billet',
						compact( 'model' )
					);
				?>
				</ul>
			</div>
		</div>
	</div>

	<p>
		<a class="button cancel" href="<?php echo esc_url( $wc_order->get_cancel_order_url() ) ?>">
			<?php _e( 'Cancel order &amp; restore cart', Core::TEXTDOMAIN ) ?>
		</a>
		<span></span>
		<button type="submit" class="button alt" id="woocommerce-moip-submit">
			<?php _e( 'Pay order', Core::TEXTDOMAIN ); ?>
		</button>
	</p>

</form>
<?php

unset( $customer );
