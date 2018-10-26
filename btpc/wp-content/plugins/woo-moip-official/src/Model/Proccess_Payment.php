<?php
namespace Woocommerce\Moip\Model;

if ( ! function_exists( 'add_action' ) ) {
	exit( 0 );
}

// Moip SDK
use Moip\Resource\Orders;
use Moip\Resource\Payment;

use Woocommerce\Moip\Core;
use Woocommerce\Moip\Helper\Utils;
use Woocommerce\Moip\Model\Order;
use Woocommerce\Moip\Model\Setting;

class Proccess_Payment
{
	protected $wc_order;

	protected $order;

	protected $sdk;

	protected $setting;

	public function __construct( $order_id, Order $order )
	{
		if ( ! is_numeric( $order_id ) ) {
			throw new Exception( __( 'Invalid order id', Core::TEXTDOMAIN ) );
		}

		$this->setting = Setting::get_instance();

		$this->_set_orders( $order_id, $order );
		$this->set_sdk();
	}

	private function _set_orders( $order_id, $order )
	{
		$this->wc_order = wc_get_order( $order_id );
		$this->order    = $order;
	}

	public function set_sdk()
	{
		$this->sdk = Moip_SDK::get_instance();
	}

	public function get_order()
	{
		return $this->wc_order;
	}

	public function order_created( $response )
	{
		if ( ! $this->order->processed ) {
			$this->order->processed = 1;
			$this->order->payment_on_hold();

			if ( $this->setting->is_enabled_logs() ) {
				$this->setting->log()->add( 'moip-official', 'WEBHOOK: ORDER CREATED' );
			}
		}
	}

	public function order_paid( $response )
	{
		$this->wc_order->add_order_note( __( 'Moip: Payment has already been confirmed.', Core::TEXTDOMAIN ) );
		$this->wc_order->payment_complete();

		if ( $this->setting->is_enabled_logs() ) {
			$this->setting->log()->add( 'moip-official', 'WEBHOOK: ORDER PAID' );
		}
	}

	public function order_waiting( $response )
	{
		$order_status = $this->wc_order->get_status();

		$status = Utils::get_formatted_status( $this->order->payment_status );

		if ( $order_status != 'processing' || $order_status != 'completed' ) {
			$this->wc_order->add_order_note( sprintf( __( 'Moip: Order status: %s', Core::TEXTDOMAIN ), $status ) );		
		}

		if ( $this->setting->is_enabled_logs() ) {
			$this->setting->log()->add( 'moip-official', 'WEBHOOK: ORDER WAITING' );
		}
	}

	public function order_reverted( $response )
	{
		$this->wc_order->add_order_note( __( 'Order reverted.', Core::TEXTDOMAIN ) );

		if ( $this->setting->is_enabled_logs() ) {
			$this->setting->log()->add( 'moip-official', 'WEBHOOK: ORDER REVERTED' );
		}
	}

	public function order_not_paid( $response )
	{
		$status = Utils::get_formatted_status( $this->order->payment_status );

		$this->wc_order->add_order_note( sprintf( __( 'Moip: Order status: %s', Core::TEXTDOMAIN ), $status ) );

		if ( $this->setting->is_enabled_logs() ) {
			$this->setting->log()->add( 'moip-official', 'WEBHOOK: ORDER NOT PAID' );
		}

		$this->wc_order->update_status( 'cancelled', __( 'Moip: Order cancelled.', Core::TEXTDOMAIN ) );
	}	

	public function payment_authorized( $response )
	{
		$this->_set_payment_meta( $response );
		
		$this->wc_order->add_order_note( __( 'Moip: Payment authorized.', Core::TEXTDOMAIN ) );

		//$this->wc_order->update_status( 'processing', __( 'Moip: Payment authorized.', Core::TEXTDOMAIN ) );

		if ( $this->setting->is_enabled_logs() ) {
			$this->setting->log()->add( 'moip-official', 'WEBHOOK: PAYMENT AUTHORIZED' );
		}
	}

	public function payment_waiting( $response )
	{
		$this->_set_payment_meta( $response );

		$payment    = $this->_get_moip_payment( $response );
		$instrument = $payment->getFundingInstrument();
		$status     = Utils::get_formatted_status( $this->order->payment_status );

		$this->wc_order->add_order_note( sprintf( __( 'Moip: Awaiting payment via: %s', Core::TEXTDOMAIN ), $instrument->method ) );
		$this->wc_order->add_order_note( sprintf( __( 'Moip: Payment status: %s', Core::TEXTDOMAIN ), $status ) );
	}

	public function payment_in_analysis( $response )
	{
		$this->_set_payment_meta( $response );

		$status = Utils::get_formatted_status( $this->order->payment_status );

		$this->wc_order->add_order_note( __( 'Moip: Payment under review.', Core::TEXTDOMAIN ) );
		$this->wc_order->add_order_note( sprintf( __( 'Moip: Payment status: %s', Core::TEXTDOMAIN ), $status ) );
	}

	public function payment_cancelled( $response )
	{
		$this->_set_payment_meta( $response );

		$status = Utils::get_formatted_status( $this->order->payment_status );

		$this->wc_order->add_order_note( __( 'Moip: Payment cancelled.', Core::TEXTDOMAIN ) );
		$this->wc_order->add_order_note( sprintf( __( 'Moip: Payment status: %s', Core::TEXTDOMAIN ), $status ) );
		$this->wc_order->update_status( 'cancelled', __( 'Moip: Order cancelled.', Core::TEXTDOMAIN ) );
	}

	public function payment_refunded( $response )
	{
		$this->wc_order->update_status( 'refunded', __( 'Payment refunded.', Core::TEXTDOMAIN ) );
	}

	private function _get_moip_order( $response )
	{
		$orders = new Orders( $this->sdk->moip );

		return $orders->get( $response->resource->order->id );
	}

	private function _get_moip_payment( $response )
	{
		$payment = new Payment( $this->sdk->moip );

		return $payment->get( $response->resource->payment->id );
	}

	private function _set_payment_meta( $response )
	{
		if ( $this->order->payment_id !== $response->resource->payment->id ) {
			$this->order->payment_id = $response->resource->payment->id;
		}
	}
}