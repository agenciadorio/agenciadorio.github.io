<?php
namespace Woocommerce\Moip\Controller;

if ( ! function_exists( 'add_action' ) ) {
	exit( 0 );
}

use Woocommerce\Moip\Core;
use Woocommerce\Moip\Helper\Utils;
use Woocommerce\Moip\Model\Checkout;
use Woocommerce\Moip\Model\Moip_SDK;
use Woocommerce\Moip\Model\Order;
use Woocommerce\Moip\Model\Setting;
use Woocommerce\Moip\View\Checkouts as Checkouts_View;

// WooCommerce
use WC_Order;

class Checkouts
{
	public function __construct()
	{
		$this->settings = Setting::get_instance();

		add_action( 'wp_ajax_RmSLgKecpN', array( $this, 'process_checkout_moip' ) );
		add_action( 'wp_ajax_nopriv_RmSLgKecpN', array( $this, 'process_checkout_moip' ) );
		add_action( 'wp_ajax_N7yAgMU7JJ', array( $this, 'process_checkout_default' ) );
		add_action( 'wp_ajax_nopriv_N7yAgMU7JJ', array( $this, 'process_checkout_default' ) );
		add_action( 'woocommerce_before_checkout_form', array( $this, 'render_moip_discount' ), 30 );
		add_action( 'woocommerce_checkout_process', array( $this, 'remove_moip_discount' ), 30 );
		add_action( 'woocommerce_review_order_after_order_total', array( $this, 'card_text_total_discount' ), 30 );

		if ( ! class_exists( 'Extra_Checkout_Fields_For_Brazil' ) ) {
			add_filter( 'woocommerce_billing_fields', array( $this, 'add_custom_billing_fields' ), 20 );
			add_action( 'woocommerce_checkout_process', array( $this, 'valid_checkout_fields' ) );
		}
	}

	public function card_text_total_discount() 
	{
		if ( WC()->cart->coupon_discount_totals != null && $this->settings->is_active_credit_card() ) {
			return Checkouts_View::render_title_total_discount();
		}
	}

	public function remove_moip_discount()
	{
		$order_id = Utils::post( 'order', 0, 'intval' );
		$fields   = Utils::post( 'moip_fields', false );

		$checkout       = new Checkout( $order_id );
		$order          = $checkout->get_order();
		$sdk            = Moip_SDK::get_instance();
		$payment_method = Utils::get_value_by( $fields, 'payment_method' );

		foreach ( WC()->cart->get_coupons() as $code => $coupon ) {

			if ( $payment_method == 'payCreditCard' && $code == 'moip_official' ) {
				WC()->cart->remove_coupon( $code );			
			}
			
		}
		
	}

	public function render_moip_discount()
	{
		if ( ! $this->settings->is_enabled() ) {
			return;
		}
		
		$new_cust_coupon_code = 'moip_official';
    	$has_apply_coupon     = false;

		foreach ( WC()->cart->get_coupons() as $code => $coupon ) {
	        if ( $code == $new_cust_coupon_code ) {
	            $has_apply_coupon = true;
	        }
	    }

		if ( ! $has_apply_coupon ) {			
			return Checkouts_View::add_moip_discount_coupon();			
		}
		
	}

	public function process_checkout_moip()
	{
		if ( ! Utils::is_request_ajax() ) {
			exit( 0 );
		}

		if ( ! Utils::verify_nonce_post( 'security', 'checkout' ) ) {
			wp_send_json_error( __( 'Invalid nonce', Core::TEXTDOMAIN ) );
		}

		$return_url = Utils::post( 'returnUrl', '', 'esc_url' );
		$order_id   = Utils::post( 'order', 0, 'intval' );

		if ( empty( $return_url ) || ! $order_id ) {
			wp_send_json_error();
		}

		$model = new Order( $order_id );

		$model->payment_type = Utils::post( 'paymentType' );

		wp_send_json_success( array( 'redirectUrl' => $return_url ) );
	}

	public function process_checkout_default()
	{
		if ( ! Utils::is_request_ajax() ) {
			exit( 0 );
		}

		$order_id = Utils::post( 'order', 0, 'intval' );

		if ( ! $order_id ) {
			wp_send_json_error( __( 'Invalid order', Core::TEXTDOMAIN ) );
		}

		if ( $this->settings->is_enabled_logs() ) {
			$this->settings->log()->add( 'moip-official', 'WC ORDER CREATED: ' . $order_id );
		}

		$checkout       = new Checkout( $order_id );
		$order          = $checkout->get_order();
		$fields         = $checkout->prepare_fields( $_POST['fields'] );
		$sdk            = Moip_SDK::get_instance();
		$payment_method = Utils::get_value_by( $fields, 'payment_method' );

		if ( empty( $fields ) ) {
			wp_send_json_error( __( 'Empty fields', Core::TEXTDOMAIN ) );
		}

		$moip_order = $order->ct_cache;

		if ( empty( $moip_order ) ) {

			$moip_order = $sdk->create_order( new WC_Order( $order_id ), $fields );

			if ( ! $moip_order['order'] ) {
				wp_send_json_error( __( 'Could not create order. Try again.', Core::TEXTDOMAIN ) );
			}

			$order->ct_cache = $moip_order;
		}

		if ( $this->settings->is_enabled_logs() ) {
			$this->settings->log()->add( 'moip-official', 'MOIP ORDER CREATED: ' . print_r( $moip_order, true ) );
		}

		$created_payment = $sdk->create_payment( $moip_order['order'], $moip_order['customer'], $fields );

		if ( is_string( $created_payment ) ) {
			
			if ( $this->settings->is_enabled_logs() ) {
				$this->settings->log()->add( 'moip-official', 'MOIP PAYMENT ERROR: ' . $created_payment );
			}

			wp_send_json_error( $created_payment );
		}

		$data = $created_payment->jsonSerialize();

		if ( $this->settings->is_enabled_logs() ) {
			$this->settings->log()->add( 'moip-official', 'MOIP PAYMENT CREATED: ' . print_r( $data, true ) );
		}

		$order->payment_id     = $data->id;
		$order->payment_type   = $fields['payment_method'];
		$order->payment_status = $data->status;
		$order->payment_links  = $data->_links;

		if ( $payment_method == 'payBoleto' ) {
			$order->payment_billet_linecode = $data->fundingInstrument->boleto->lineCode;
		}

		if ( $payment_method == 'payCreditCard' ) {
			$order->installments = intval( $fields['installments'] );
			if ( $storage_card = Utils::get_value_by( $fields, 'store_credit_card' ) ) {
				$sdk->customer->credit_card_last_numbers = $data->fundingInstrument->creditCard->last4;
				$sdk->customer->credit_card_brand        = $data->fundingInstrument->creditCard->brand;
			}
		}

		wp_send_json_success( $created_payment );
	}

	public static function process_checkout_transparent( $wc_order )
	{
		if ( ! method_exists( $wc_order, 'get_id' ) ) {
			wc_add_notice( __( 'Invalid order', Core::TEXTDOMAIN ), 'error' );
			return false;
		}

		$settings = Setting::get_instance();

		if ( $settings->is_enabled_logs() ) {
			$settings->log()->add( 'moip-official', 'WC ORDER CREATED: ' . $wc_order->get_id() );
		}

		$fields = Utils::post( 'moip_fields', false );

		if ( empty( $fields ) ) {
			wc_add_notice( __( 'Empty fields', Core::TEXTDOMAIN ), 'error' );
			return false;
		}

		$checkout       = new Checkout( $wc_order->get_id() );
		$order          = $checkout->get_order();
		$sdk            = Moip_SDK::get_instance();
		$payment_method = Utils::get_value_by( $fields, 'payment_method' );
		$moip_order     = $order->ct_cache;

		if ( empty( $moip_order ) ) {
			$moip_order = $sdk->create_order( $wc_order, $fields );

			if ( ! $moip_order['order'] ) {
				wc_add_notice( __( 'Could not create order. Try again.', Core::TEXTDOMAIN ), 'error' );
				return false;
			}

			$order->ct_cache = $moip_order;
		}

		if ( $settings->is_enabled_logs() ) {
			$settings->log()->add( 'moip-official', 'MOIP ORDER CREATED: ' . print_r( $moip_order, true ) );
		}

		$created_payment = $sdk->create_payment( $moip_order['order'], $moip_order['customer'], $fields );

		if ( is_string( $created_payment ) ) {
			
			if ( $settings->is_enabled_logs() ) {
				$settings->log()->add( 'moip-official', 'MOIP PAYMENT ERROR: ' . $created_payment );
			}

			wc_add_notice( str_replace( "\n", '<br>', $created_payment ), 'error' );

			return false;
		}

		$data = $created_payment->jsonSerialize();

		if ( $settings->is_enabled_logs() ) {
			$settings->log()->add( 'moip-official', 'MOIP PAYMENT CREATED: ' . print_r( $data, true ) );
		}

		$order->payment_id     = $data->id;
		$order->payment_type   = $fields['payment_method'];
		$order->payment_status = $data->status;
		$order->payment_links  = $data->_links;

		if ( $payment_method == 'payBoleto' ) {
			$order->payment_billet_linecode = $data->fundingInstrument->boleto->lineCode;
		}

		if ( $payment_method == 'payCreditCard' ) {
			$order->installments = intval( $fields['installments'] );
			if ( $storage_card = Utils::get_value_by( $fields, 'store_credit_card' ) ) {
				$sdk->customer->credit_card_last_numbers = $data->fundingInstrument->creditCard->last4;
				$sdk->customer->credit_card_brand        = $data->fundingInstrument->creditCard->brand;
			}
		}

		return $created_payment;
	}

	public function add_custom_billing_fields( $fields )
	{
		$custom_fields = array();

		$custom_fields['billing_persontype'] = array(
			'type'     => 'select',
			'label'    => __( 'Person type', Core::TEXTDOMAIN ),
			'class'    => array( 'form-row-wide', 'person-type-field' ),
			'required' => true,
			'options'  => array(
				'0' => __( 'Select an option', Core::TEXTDOMAIN ),
				'1' => __( 'Individuals', Core::TEXTDOMAIN ),
				'2' => __( 'Legal Person', Core::TEXTDOMAIN ),
			),
			'priority' => 22,
		);

		$custom_fields['billing_cpf'] = array(
			'label'       => __( 'CPF', Core::TEXTDOMAIN ),
			'placeholder' => _x( 'CPF', 'placeholder', Core::TEXTDOMAIN ),
			'class'       => array( 'form-row-wide', 'person-type-field' ),
			'required'    => false,
			'type'        => 'tel',
			'priority'    => 23,
		);

		$custom_fields['billing_cnpj'] = array(
			'label'       => __( 'CNPJ', Core::TEXTDOMAIN ),
			'placeholder' => _x( 'CNPJ', 'placeholder', Core::TEXTDOMAIN ),
			'class'       => array( 'form-row-wide', 'person-type-field' ),
			'required'    => false,
			'type'        => 'tel',
			'priority'    => 24,
		);

		$custom_fields['billing_number'] = array(
			'label'       => __( 'Number', Core::TEXTDOMAIN ),
			'placeholder' => _x( 'Number', 'placeholder', Core::TEXTDOMAIN ),
			'class'       => array( 'form-row-first', 'address-field' ),
			'clear'       => true,
			'required'    => true,
			'priority'    => 55,
		);

		$custom_fields['billing_neighborhood'] = array(
			'label'       => __( 'Neighborhood', Core::TEXTDOMAIN ),
			'placeholder' => _x( 'Neighborhood', 'placeholder', Core::TEXTDOMAIN ),
			'class'       => array( 'form-row-last', 'address-field' ),
			'clear'       => true,
			'required'    => true,
			'priority'    => 56,
		);

		$fields = wp_parse_args( $custom_fields, $fields );

		return apply_filters( Core::tag_name( 'checkout_fields' ), $fields );
	}

	public function valid_checkout_fields()
	{
		if ( apply_filters( Core::tag_name( 'disable_checkout_fields_validation' ), false ) ) {
			return;
		}

		$person_type = Utils::post( 'billing_persontype', 0, 'intval' );
		$cpf         = Utils::post( 'billing_cpf' );
		$cnpj        = Utils::post( 'billing_cnpj' );

		if ( empty( $person_type ) ) {
			wc_add_notice(
				sprintf( '<strong>%s</strong> %s.',
					__( 'Person type', Core::TEXTDOMAIN ),
					__( 'is a required field', Core::TEXTDOMAIN )
				),
				'error'
			);
		}

		if ( $person_type === 1 ) {
			$this->_check_single_field( 'cpf', $cpf );
		}

		if ( $person_type === 2 ) {
			$this->_check_single_field( 'cnpj', $cnpj );
		}
	}

	private function _check_single_field( $type, $value )
	{
		$name     = strtoupper( $type );
		$callback = 'is_' . $type;

		if ( empty( $value ) ) {
			wc_add_notice(
				sprintf( '<strong>%s</strong> %s.',
					__( $name, Core::TEXTDOMAIN ),
					__( 'is a required field', Core::TEXTDOMAIN )
				),
				'error'
			);
		}

		if ( ! Utils::$callback( $value ) ) {
			wc_add_notice(
				sprintf( '<strong>%s</strong> %s.',
					__( $name, Core::TEXTDOMAIN ),
					__( 'is not valid', Core::TEXTDOMAIN )
				),
				'error'
			);
		}
	}
}
