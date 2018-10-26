<?php
if ( ! function_exists( 'add_action' ) ) {
	exit( 0 );
}

use Woocommerce\Moip\Core;
use Woocommerce\Moip\Helper\Utils;
use Woocommerce\Moip\Model\Order;
use Woocommerce\Moip\Model\Setting;
use Woocommerce\Moip\View\Checkouts as Checkouts_View;

$model        = new Order( $order_id );
$setting      = Setting::get_instance();
$payment_link = $model->get_link_by_type( $model->payment_type );

?>

<div class="woocommerce-message">

	<p class="thank-you-description">
	<?php
		if ( ! $setting->is_checkout_default() ) {
			if ( ! is_null( $payment_link ) ) {
				printf(
					'%s <a href="%s" target="_blank" class="payment-link">%s</a>',
					__( 'Your order has been processed successfully, click the link below to make the payment.', Core::TEXTDOMAIN ),
					$payment_link,
					$model->is_bankslip_payment() ? __( 'Print', Core::TEXTDOMAIN ) : __( 'Pay now', Core::TEXTDOMAIN )
				);
			}
		} else {
			echo Checkouts_View::handle_messages( $model );
		}
	?>
	</p>

</div>