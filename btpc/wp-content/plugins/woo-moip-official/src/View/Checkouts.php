<?php
namespace Woocommerce\Moip\View;

if ( ! function_exists( 'add_action' ) ) {
	exit( 0 );
}

use Woocommerce\Moip\Core;
use Woocommerce\Moip\Helper\Utils;
use Woocommerce\Moip\Model\Order;
use Woocommerce\Moip\Model\Setting;

class Checkouts
{
	protected static function message_before()
	{
		return __( 'Your transaction has been processed by Moip.', Core::TEXTDOMAIN ) . '<br />';
	}

	protected static function message_after()
	{
		return __( 'If you have any questions regarding the transaction, please contact us or the Moip.', Core::TEXTDOMAIN );
	}

	public static function handle_messages( Order $order )
	{
		switch ( $order->payment_type ) {

			case 'payBoleto' :
				return self::billet_message( $order );

			case 'payCreditCard' :
				return self::credit_cart_message( $order );

			case 'payOnlineBankDebitItau' :
				return self::debit_message( $order );
		}
	}

	public static function credit_cart_message( $order )
	{
		$message = self::message_before();

		$message .= sprintf(
			__( 'The status of your transaction is %s and the Moip code is %s.', Core::TEXTDOMAIN ),
			'<strong>' . $order->get_moip_status_translate() . '</strong>',
			'<strong>' . $order->payment_id . '</strong>'
		) . '<br />';

		$message .= self::message_after();

		return $message;
	}

	public static function debit_message( $order )
	{
		$message = self::message_before();

		$message .= __( 'If you have not made ​​the payment, please click the button below to pay.', Core::TEXTDOMAIN ) . '<br />';

		$message .= sprintf(
			'<a href="%s" target="_blank" class="payment-link">%s</a></br>',
			$order->payment_links->payOnlineBankDebitItau->redirectHref,
			__( 'Pay now', Core::TEXTDOMAIN )
		);

		$message .= self::message_after();

		return $message;
	}

	public static function billet_message( $order )
	{
		$message = self::message_before();

		if ( ! empty( $order->payment_billet_linecode ) ) {
			$message .= sprintf(
				'<span>%s <span id="linecode">%s</span></span><br/>',
				__( 'Line Code: ', Core::TEXTDOMAIN ),
				$order->payment_billet_linecode
			);
		}

		$message .= sprintf(
			'<button id="clipboard-linecode-btn"
			         class="clipboard-btn"
			         data-success-text="%s"
			         data-clipboard-target="#linecode">
			    %s
			 </button><br/>',
			__( 'Copied!', Core::TEXTDOMAIN ),
			__( 'Copy barcode', Core::TEXTDOMAIN )
		);

		$message .= __( 'If you have not yet received the billet, please click the button below to print.', Core::TEXTDOMAIN ) . '<br />';

		$message .= self::message_after();

		$message .= sprintf(
			'<a href="%s" target="_blank" class="payment-link">%s</a><br/>',
			$order->payment_links->payBoleto->redirectHref . '/print',
			__( 'Print', Core::TEXTDOMAIN )
		);



		return $message;
	}

	public static function render_installments( $total )
	{
		$setting = Setting::get_instance();

		if ( ! $setting->is_active_installments() ) {
			return;
		}

		$min_installments = str_replace( ',', '.', $setting->installments_minimum );
		$max_installments = intval( $setting->installments_maximum );

		for ( $times = 2; $times <= $max_installments; $times++ ) {
			$amount = $total;

			if ( isset( $setting->installments['interest'][ $times ] ) ) {
				$per_installment = (float) str_replace( ',', '.', $setting->installments['interest'][$times] );
				$amount         += ( $per_installment / 100 ) * $amount;
			}

			$price = ceil( $amount / $times * 100 ) / 100;

			if ( $min_installments <= $price ) {
				$text  = sprintf( __( '%dx of %s (%s)', Core::TEXTDOMAIN ),
					$times,
					wc_price( $price ),
					wc_price( $price * $times )
				);

				printf( '<option value="%1$s">%2$s</option>', $times, $text );				
			}
		}
	}

	public static function add_moip_discount_coupon()
	{
		$setting = Setting::get_instance();

		$coupon_code    = 'moip_official';
		$amount         = (float) str_replace( ',', '.', $setting->billet_number_discount );
		$discount_type  = 'percent';

		if ( ! $setting->is_active_billet_banking() || $setting->payment_api != 'transparent_checkout' || ! $amount ) {
			$coupons_code = wc_format_coupon_code( $coupon_code );
			$position     = array_search( $coupons_code, WC()->cart->get_applied_coupons(), true );
			
			if ( false !== $position ) {
		      unset( WC()->cart->applied_coupons[$position] );
		    }

			WC()->session->set( 'refresh_totals', true );

			do_action( 'woocommerce_removed_coupon', $coupon_code );

			return true;
		}

		$coupon = array(
			'post_title'   => $coupon_code,
			'post_content' => '',
			'post_status'  => 'publish',
			'post_author'  => 1,
			'post_type'	   => 'shop_coupon'
		);

		$new_coupon_id = wp_insert_post( $coupon );						
							
		// Add meta
		update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
		update_post_meta( $new_coupon_id, 'coupon_amount', $amount );

		if ( ! WC()->cart->add_discount( sanitize_text_field( $coupon_code ) ) )
    		wc_add_notice( __( 'Billet discount added.', Core::TEXTDOMAIN ) );
		
	}

	public static function render_title_total_discount()
	{

		$discount_total = str_replace( ',', '.', WC()->cart->get_coupon_discount_totals() );

		if ( $discount_total['moip_official'] != '' ) {
			$price_total    = WC()->cart->total;
			$cc_total       = wc_price( $price_total + $discount_total['moip_official'] );
			
			$message = '';

			$message .= sprintf(
				'<tr class="moip-order-total-cc">
					<th>%s</th>
					<td><strong>%s</strong></td>
				</tr>',
				__( 'Total on Credit Card', Core::TEXTDOMAIN ),
				$cc_total
			);

			echo $message;
		}

	}

}
