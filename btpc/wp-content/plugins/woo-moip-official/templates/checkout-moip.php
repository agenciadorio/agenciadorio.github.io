<?php
if ( ! function_exists( 'add_action' ) ) {
	exit( 0 );
}

use Woocommerce\Moip\Core;
use Woocommerce\Moip\Helper\Utils;

?>

<form method="post"
	  id="wc-moip-payment-form"
	  data-return-url="<?php echo esc_url( $this->get_return_url( $wc_order ) ); ?>"
	  data-nonce="<?php echo esc_attr( wp_create_nonce( 'checkout' ) ); ?>"
	  data-order="<?php echo intval( $wc_order->get_order_number() ); ?>"
	  <?php echo Utils::get_component( 'checkout-moip' ); ?>>

	<div class="product">

		<div class="woocommerce-tabs">

			<ul class="tabs"
				id="payment-type"
				data-element="tabs">

				<?php if ( $this->model->settings->is_active_credit_card() ) : ?>

					<li>
						<a href="<?php echo esc_url( $payment_links->payCreditCard->redirectHref ); ?>"
						   class="button alt"
						   data-action="payment-link"
						   data-payment-type="payCreditCard">
							<?php _e( 'Pay with credit card', Core::TEXTDOMAIN ); ?>
						</a>
					</li>

				<?php endif; ?>

				<?php if ( $this->model->settings->is_active_banking_debit() ) : ?>

					<li>
						<a href="<?php echo esc_url( $payment_links->payOnlineBankDebitItau->redirectHref ); ?>"
						   class="button alt"
						   data-action="payment-link"
						   data-payment-type="payOnlineBankDebitItau">
							<?php _e( 'Pay with banking debit (Itaú)', Core::TEXTDOMAIN ); ?>
						</a>
					</li>

				<?php endif; ?>

				<?php if ( $this->model->settings->is_active_billet_banking() ) : ?>

					<li>
						<a href="<?php echo esc_url( $payment_links->payBoleto->redirectHref ); ?>"
						   class="button alt"
						   data-action="payment-link"
						   data-payment-type="payBoleto">
							<?php _e( 'Pay with billet banking', Core::TEXTDOMAIN ); ?>
						</a>
					</li>

				<?php endif; ?>
			</ul>

		</div>

	</div>

	<p>
		<a class="button cancel" href="<?php echo esc_url( $wc_order->get_cancel_order_url() ); ?>">
			<?php _e( 'Cancel order e restore cart', Core::TEXTDOMAIN ); ?>
		</a>
	</p>

</form>
