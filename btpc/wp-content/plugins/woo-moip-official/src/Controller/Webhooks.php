<?php
namespace Woocommerce\Moip\Controller;

if ( ! function_exists( 'add_action' ) ) {
	exit( 0 );
}

// Native exception
use Exception;

use Woocommerce\Moip\Core;
use Woocommerce\Moip\Helper\Utils;
use Woocommerce\Moip\Model\Setting;
use Woocommerce\Moip\Model\Webhook;
use Woocommerce\Moip\Model\Moip_SDK;
use Woocommerce\Moip\Model\Proccess_Payment;
use Woocommerce\Moip\Model\Order;

class Webhooks
{
	public $setting;

	public $moip_order_id;

	public $resource_type;

	public function __construct()
	{
		$this->setting = Setting::get_instance();

		add_action( 'woocommerce_api_' . Core::get_webhook_name(), array( $this, 'handle' ) );
        add_action( 'wp_ajax_zGdGIuifWc', array( $this, 'admin_ipn_test' ) );
	}

	public function sanitize_event_name( $event )
	{
		return str_replace( '.', '_', strtolower( $event ) );
	}

	public function handle()
	{
		$body = Utils::get_json_post_data();

		if ( $this->setting->is_enabled_logs() ) {
			$this->setting->log()->add( 'moip-official', 'WEBHOOK BODY: ' . print_r( $body, true ) );
		}

		try {
			$this->_proccess_event( $body );
			$this->_action_handle( $body );
		} catch ( Exception $e ) {
			if ( $this->setting->is_enabled_logs() ) {
				$this->setting->log()->add( 'moip-official', 'WEBHOOK HANDLE ERROR: ' . $e->__toString() );
			}
			error_log( $e->__toString() );
		}
	}

	private function _proccess_event( $body )
	{
		if ( ! isset( $body->event ) || empty( $body->event ) || ! $this->_is_valid_token() ) {
			if ( $this->setting->is_enabled_logs() ) {
				$this->setting->log()->add( 'moip-official', 'INVALID WEBHOOK' );
			}
			throw new Exception( __( 'Invalid webhook.', Core::TEXTDOMAIN ) );
		}

		$this->_set_moip_order_id( $body );
	}

	private function _set_moip_order_id( $body )
	{
		if ( Utils::indexof( $body->event, 'ORDER.' ) ) {
			$this->moip_order_id = $body->resource->order->id;
			$this->resource_type = 'order';

		} else if ( Utils::indexof( $body->event, 'PAYMENT.' ) ) {
			$this->moip_order_id = $body->resource->payment->_links->order->title;
			$this->resource_type = 'payment';
		}
	}

	private function _is_valid_token()
	{
		$setting = Setting::get_instance();

		return ( $setting->hash_token === Utils::get( 'token' ) );
	}

	private function _action_handle( $body )
	{
		$order_id = Utils::get_order_by_meta_value( $this->moip_order_id );
		$action   = $this->sanitize_event_name( $body->event );

		if ( ! $order_id ) {
			return;
		}

		$order            = new Order( $order_id );
		$proccess_payment = new Proccess_Payment( $order_id, $order );

		if ( method_exists( $proccess_payment, $action ) ) {
			$order->payment_status = $body->resource->{$this->resource_type}->status;

			$proccess_payment->{$action}( $body );
		}

		unset( $order );
		unset( $proccess_payment );

		do_action( Core::tag_name( $action ), $order_id, $body );
	}

	public static function set_token( $custom_gateway )
	{
		if ( ! Utils::is_settings_page() ) {
			return;
		}

		$setting = Setting::get_instance();

		if ( $setting->is_valid_webhook() ) {
			return;
		}

		$moip_sdk = Moip_SDK::get_instance();

		if ( ! $moip_sdk->is_valid() ) {
			$custom_gateway->add_error( __( 'Authentication error.', Core::TEXTDOMAIN ) );
			$setting->delete( 'enabled' );
			return;
		}

		try {
			$webhook  = new Webhook( $moip_sdk->moip );
			$response = $webhook->create();

			if ( $response->token ) {
				$setting->set( 'webhook_token', $response->token );
				$setting->set( 'webhook_id', $response->id );
			}

			unset( $webhook );

		} catch( Exception $e ) {
			$custom_gateway->add_error( __( 'Enable payment in the field below.', Core::TEXTDOMAIN ) );

			$setting->delete( 'enabled' );
		}
	}

	public function delete_token()
	{
		$setting = Setting::get_instance();

		if ( empty( $setting->webhook_id ) ) {
			return;
		}

		try {
			$sdk   = Moip_SDK::get_instance();
			$model = new Webhook( $sdk->moip );

			$model->delete( $setting->webhook_id );

			$setting->delete( 'webhook_token' );
			$setting->delete( 'webhook_id' );

			unset( $model );

		} catch ( Exception $e ) {
			error_log( $e->__toString() );
		}
	}

	public function admin_ipn_test()
	{
		if ( ! Utils::is_request_ajax() ) {
			exit( 0 );
		}

		if ( ! current_user_can( 'manage_woocommerce'  ) ) {
			wp_send_json_error(
				array(
	               'text'  => __( 'You are not allowed to do this.', Core::TEXTDOMAIN ),
	               'class' => 'alert-danger',
				)
			);
		}

		$resource_id = Utils::post( 'resourceId' );

		if ( empty( $resource_id ) ) {
			wp_send_json_error(
				array(
	               'text'  => __( 'This resource id field is required.', Core::TEXTDOMAIN ),
	               'class' => 'alert-warning',
				)
			);
		}

		try {
			$sdk           = Moip_SDK::get_instance();
			$model         = new Webhook( $sdk->moip );
			$notifications = $model->get_sent( $resource_id  );

		} catch ( Exception $e ) {
			error_log( $e->__toString() );
			$notifications = new stdClass();
		}

		if ( empty( $notifications->webhooks ) ) {
			wp_send_json_success(
				array(
		           'text'  => __( 'This invalid resource id.', Core::TEXTDOMAIN ),
		           'class' => 'alert-danger',
				)
			);
		}

		$notifications_sent = array();
		$not_sent_args      = array(
           'text'  => __( 'No notifications to send.', Core::TEXTDOMAIN ),
           'class' => 'alert-info',
		);

		foreach ( $notifications->webhooks as $notification ) {
			if ( isset( $notifications_sent[ $notification->event ] ) ) {
				continue;
			}

			try {
				$sent = $model->sent_notification( array(
		            'resourceId' => $notification->resourceId,
		            'event'      => $notification->event,
				) );

			} catch ( Exception $e ) {
				error_log( $e->__toString() );
				wp_send_json_success( $not_sent_args );
			}

			if ( strtolower( $sent->status ) === 'failed' ) {
				continue;
			}

			$event                              = preg_replace( '/(.+)\./', '', $sent->event );
			$notifications_sent[ $sent->event ] = strtolower( $event );
		}

		if ( empty( $notifications_sent ) ) {
			wp_send_json_success( $not_sent_args );
		}

		wp_send_json_success(
			array(
	           'class' => 'alert-success',
	           'text'  => sprintf(
		           	'%s<br><strong>%s:</strong> %s',
		           	__( 'Webhook notification successfully sent.', Core::TEXTDOMAIN ),
		           	__( 'Events', Core::TEXTDOMAIN ),
		           	implode( ', ', $notifications_sent )
	           	),
			)
		);
	}
}