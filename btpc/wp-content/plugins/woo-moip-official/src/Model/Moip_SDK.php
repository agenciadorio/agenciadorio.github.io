<?php
namespace Woocommerce\Moip\Model;

if ( ! function_exists( 'add_action' ) ) {
	exit( 0 );
}

//Moip SDK
use Moip\Moip;
use Moip\Auth\OAuth;
use Moip\Resource\Orders;
use Moip\Resource\Payment;
use Moip\Resource\Customer as Moip_Customer;

// Objects Native
use Exception;
use DateTime;

// WooCommerce
use WC_Order;

// Config
use Woocommerce\Moip\Core;

// Model
use Woocommerce\Moip\Model\Setting;
use Woocommerce\Moip\Model\Order;
use Woocommerce\Moip\Model\Customer;

// Helper
use Woocommerce\Moip\Helper\Utils;

//Exceptions
use Woocommerce\Moip\Exceptions\Parse_Exception;

class Moip_SDK
{
	private static $_instance = null;

	public $setting;

	public $customer;

	public $default_date_string = '31-12-1800';

	public $moip = false;

	private function __construct()
	{
		$this->_set_customer();
		$this->_set_setting();
		$this->_set_moip();
	}

	private function _set_customer()
	{
		$this->customer = new Customer( get_current_user_id() );
	}

	private function _set_setting()
	{
		$this->setting = Setting::get_instance();
	}

	public function is_valid()
	{
		return ( $this->moip instanceof Moip );
	}

	public function get_endpoint()
	{
		return $this->setting->is_sandbox() ? Moip::ENDPOINT_SANDBOX : Moip::ENDPOINT_PRODUCTION;
	}

	private function _set_moip()
	{
		if ( $this->setting->is_active_access_token() === true ) {
			$authorize_data = $this->setting->authorize_data;

			if ( $this->setting->field_number_acesstoken != '' ) {
				$authorize_data->accessToken = $this->setting->field_number_acesstoken;
			}

			if ( $authorize_data && $authorize_data->accessToken ) {
				try {
					$oauth      = new OAuth( $authorize_data->accessToken );
					$this->moip = new Moip( $oauth, $this->get_endpoint() );

					$http_sess = $this->moip->getSession();

					$http_sess->options['timeout']         = 60.0;
					$http_sess->options['connect_timeout'] = 60.0;

					$this->moip->setSession( $http_sess );

				} catch ( Exception $e ) {
					error_log( $e->__toString() );
				}
			}

		} else {
			$authorize_data = $this->setting->authorize_data;

			if ( $authorize_data && $authorize_data->accessToken ) {
				try {
					$oauth      = new OAuth( $authorize_data->accessToken );
					$this->moip = new Moip( $oauth, $this->get_endpoint() );

					$http_sess = $this->moip->getSession();

					$http_sess->options['timeout']         = 60.0;
					$http_sess->options['connect_timeout'] = 60.0;

					$this->moip->setSession( $http_sess );

				} catch ( Exception $e ) {
					error_log( $e->__toString() );
				}
			}
		}
	}

	public function create_customer_by_order( WC_Order $wc_order )
	{
		if ( ! $this->is_valid() ) {
			return null;
		}

		try {
			$model         = new Order( $wc_order->get_order_number() );
			$moip_customer = $this->moip->customers();
			$person_type   = $model->billing_persontype;

			if ( empty( $person_type ) || $person_type == 1 ) {
				$document      = $model->billing_cpf;
				$document_type = 'CPF';
			}

			if ( $person_type == 2 ) {
				$document      = $model->billing_cnpj;
				$document_type = 'CNPJ';
			}

			// if ( $model->billing_birthdate ) {
			// 	$default_date_string = $model->billing_birthdate;
			// } else {
			// 	$default_date_string = '31-12-1969';
			// }

			$moip_customer->setOwnId( $this->setting->get_transation_id( $model->ID ) );
			$moip_customer->setFullname( "{$model->billing_first_name} {$model->billing_last_name}" );
			$moip_customer->setEmail( $model->billing_email );
			$moip_customer->setBirthDate( Utils::convert_date( $this->default_date_string ) );
			$moip_customer->setTaxDocument( Utils::format_document( $document ), $document_type );

			if ( $phone = Utils::format_phone_number( $model->billing_phone ) ) {
				$moip_customer->setPhone( $phone[0], $phone[1] );
			}

			$moip_customer->addAddress(
				Moip_Customer::ADDRESS_SHIPPING,
				$model->billing_address_1,
				$model->billing_number,
				$model->billing_neighborhood,
				$model->billing_city,
				$model->billing_state,
				preg_replace( '/[^\d]+/', '', $model->billing_postcode ),
				$model->billing_address_2
			);

			$moip_customer->create();

			return $moip_customer;

		} catch ( Exception $e ) {
			error_log( $e->__toString() );
			if ( $this->setting->is_enabled_logs() ) {
				$this->setting->log()->add( 'moip-official', 'MOIP CREATE CUSTOMER ERROR: ' . $e->__toString() );
			}
			$this->_show_cancel_button( $wc_order );
		    return null;
		}
	}

	public function create_order( WC_Order $wc_order, $fields = false )
	{
		$moip_customer = $this->create_customer_by_order( $wc_order );
		$moip_cupom    = WC()->cart->get_coupon_discount_totals();

		if ( is_null( $moip_customer ) ) {
			return null;
		}

		try {
			$moip_order = $this->moip->orders();
			$order_id   = intval( $wc_order->get_order_number() );

		    $moip_order->setOwnId( $this->setting->get_transation_id( $order_id ) );

		    foreach ( $wc_order->get_items() as $item ) {
				$product = $wc_order->get_product_from_item( $item );
				$qty     = absint( $item['qty'] );
				$title   = sanitize_title( $item['name'] ) . ' x ' . $qty;
				$price   = Utils::format_order_price( $product->get_price() );

		    	$moip_order->addItem( $title, $qty, $product->get_sku(), $price );
			}

			$this->_set_installments_interest( $moip_order, $fields, $wc_order );
			$this->_set_shipping( $moip_order, $wc_order );

			if ( $fields['payment_method'] == 'payBoleto' && $moip_cupom['moip_official'] != null ) {
				$this->_set_discount( $moip_order, $wc_order );
			} else {
				$this->_set_discount( $moip_order, $wc_order );
			}

	        $moip_order->setCustomer( $moip_customer );

	        // Add Filter Marketplace
	        $moip_order =  apply_filters( 'apiki_moip_create_order', $moip_order, $wc_order );

			$moip_order->create();

			$response = $moip_order->jsonSerialize();

			$order                = new Order( $order_id );
			$order->payment_links = $response->_links->checkout;
			$order->resource_id   = $response->id;

			unset( $order );

	        return array(
				'order'    => $moip_order,
				'customer' => $moip_customer,
				'response' => $response,
	        );

		} catch ( Exception $e ) {
			error_log( $e->__toString() );
			if ( $this->setting->is_enabled_logs() ) {
				$this->setting->log()->add( 'moip-official', 'MOIP CREATE ORDER ERROR: ' . $e->__toString() );
			}
			$this->_show_cancel_button( $wc_order );
			return null;
		}
	}

	public function create_payment( $moip_order, $moip_customer, $fields )
	{
		try {
			$payment        = $moip_order->payments();
			$payment_method = $fields['payment_method'];

		    if ( ! method_exists( $this, $payment_method ) ) {
		    	wp_send_json_error( __( 'Payment method not found!', Core::TEXTDOMAIN ) );
		    }

		    $this->{$payment_method}( $payment, $moip_customer, $fields );

		    $payment->execute();

		    return $payment;

		} catch ( Exception $e ) {
			if ( $this->setting->is_enabled_logs() ) {
				$this->setting->log()->add( 'moip-official', 'MOIP CREATE PAYMENT: ' . $e->__toString() );
			}
			$parse_exception = new Parse_Exception( $e );
		    return $parse_exception->get_errors();
		}
	}

	public function get_order( $resource_id )
	{
		$orders = new Orders( $this->moip );

		return $orders->get( $resource_id );
	}

    public function payCreditCard( Payment $payment, Moip_Customer $moip_customer, array $fields )
    {
    	$hash = $this->_get_payment_hash( $fields );

    	if ( $hash ) {
	    	$payment->setCreditCardHash( $hash, $moip_customer );
	    	$this->_save_credit_card_hash( $hash, $fields );

    	} else {
    		$payment->setCreditCard(
    			$fields['card_expiry_month'],
    			$fields['card_expiry_year'],
    			$fields['card_number'],
    			$fields['card_cvc'],
    			$moip_customer
    		);
    	}

    	$payment->setInstallmentCount( $fields['installments'] );
    	$payment->setStatementDescriptor( $this->setting->invoice_name );
    }

    public function payOnlineBankDebitItau( Payment $payment, $moip_customer = null, $fields = null )
    {
    	$expiration_date = new DateTime();

    	$expiration_date->modify( '+1 day' );
    	$payment->setOnlineBankDebit( 341, $expiration_date->format( 'Y-m-d' ), null );
    }

    public function payBoleto( Payment $payment, $moip_customer = null, $fields = null )
    {
    	$expiration_date = new DateTime();

    	if ( $days = (int)$this->setting->billet_deadline_days ) {
    		$expiration_date->modify( "+{$days} day" );
    	}

    	$payment->setBoleto(
    		$expiration_date->format( 'Y-m-d' ),
    		$this->setting->billet_logo,
    		array(
    			$this->setting->billet_instruction_line1,
    			$this->setting->billet_instruction_line2,
    			$this->setting->billet_instruction_line3
    		)
    	);
    }

    private function _show_cancel_button( WC_Order $wc_order )
	{
		if ( ! Utils::is_request_ajax() ) {
			printf(
				'<a class="button cancel" href="%s">%s</a>',
				esc_url( $wc_order->get_cancel_order_url() ),
				__( 'An error occurred while processing. Click to try again.', Core::TEXTDOMAIN )
			);
		}
	}

    private function _get_payment_hash( array $fields )
    {
		if ( ! Utils::post( 'encrypt', 0, 'intval' ) ) {
			return false;
		}

		$field_hash = Utils::get_value_by( $fields, 'hash' );

		return $field_hash ? $field_hash : $this->customer->credit_card_hash;
    }

    private function _save_credit_card_hash( $hash, $fields )
    {
		$saved = Utils::get_value_by( $fields, 'store_credit_card' );

		if ( $hash && $saved === '1' ) {
			$this->customer->stored_credit_card = '1';
			$this->customer->credit_card_hash   = $hash;
		}
    }

    private function _set_installments_interest( $moip_order, $fields, $wc_order )
    {
   		if ( ! $this->setting->is_active_installments() ) {
    		return;
    	}

    	if ( ! $fields ) {
    		return;
    	}

    	$discount_total = str_replace( ',', '.', WC()->cart->get_coupon_discount_totals() );
    	$moip_cupom     = WC()->cart->get_coupon_discount_totals();
		$installments   = $fields['installments'];

		if ( $fields['payment_method'] == 'payCreditCard' ) {
			$price_total = WC()->cart->total;
			$total       = $price_total + $discount_total['moip_official'];
		} else {
			$total = $wc_order->get_total();
		}

    	if ( $fields['payment_method'] != 'payCreditCard' || $installments == 1 ) {
    		return;
    	}

    	if ( ! isset( $this->setting->installments['interest'][ $installments ] ) ) {
    		return;
    	}

		$per_installment = str_replace( ',', '.', $this->setting->installments['interest'][ $installments ] );
		$interest        = (  $per_installment / 100 ) * $total;

		$moip_order->setAddition( Utils::format_order_price( $interest ) );
    }

    private function _set_shipping( $moip_order, WC_Order $wc_order )
    {
    	if ( ! $shipping = $wc_order->get_total_shipping() ) {
    		return;
    	}

    	$moip_order->setShippingAmount( Utils::format_order_price( $shipping ) );
    }

    private function _set_discount( $moip_order, WC_Order $wc_order )
    {
		if ( ! $discount = $wc_order->get_total_discount() ) {
    		return;
    	}

    	$moip_order->setDiscount( Utils::format_order_price( $discount ) );
    }

	public static function get_instance()
	{
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self;
		}

		return self::$_instance;
	}
}
