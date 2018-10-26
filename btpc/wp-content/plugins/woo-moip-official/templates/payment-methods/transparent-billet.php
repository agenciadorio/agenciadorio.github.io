<?php
if ( ! function_exists( 'add_action' ) ) {
	exit(0);
}

use Woocommerce\Moip\Core;

if ( ! $model->settings->is_active_billet_banking() ) {
	return;
}

foreach ( WC()->cart->applied_coupons as $coupon_name ) {

	if ( WC()->cart->coupon_discount_totals != null && $coupon_name === 'moip_official' ) {
		$discount_message = printf( 
			'<div id="discount-price-text">%s R$%s</div></br>',
			__( 'The total price of this billet:', Core::TEXTDOMAIN ),
			number_format( $cart_total, 2, ',', ' ' )
		);
	}
}

?>

<div id="tab-billet" class="entry-content">
	<ul class="moip-tab-billet">
		<li>
			<label>
				<?php
					echo __( 'The order will be confirmed only after confirmation of payment.', Core::TEXTDOMAIN );
					printf( '<img src="%1$s" alt="%2$s" title="%2$s" />',
						Core::plugins_url( 'assets/images/barcode.svg' ),
						__( 'Bank Billet', Core::TEXTDOMAIN )
					);
				?>
			</label>
		</li>
	</ul>
</div>