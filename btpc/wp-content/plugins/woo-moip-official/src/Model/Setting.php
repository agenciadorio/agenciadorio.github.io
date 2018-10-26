<?php
namespace Woocommerce\Moip\Model;

if ( ! function_exists( 'add_action' ) ) {
	exit( 0 );
}

use Woocommerce\Moip\Core;
use Woocommerce\Moip\Helper\Utils;
use Woocommerce\Moip\Model\Custom_Gateway;

class Setting
{
	public static $_instance = null;

	private $_settings;

	private $_fields = array(
		'enabled'                  => array(),
		'title'                    => array(),
		'description'              => array(),
		'invoice_name'             => array(),
		'invoice_prefix'           => array(),
		'payment_api'              => array(),
		'public_key'               => array(),
		'billet_banking'           => array(),
		'credit_card'              => array(),
		'field_enable_acesstoken'  => array(),
		'field_number_acesstoken'  => array(),
		'banking_debit'            => array(),
		'installments_enabled'     => array(),
		'installments_minimum'     => array(),
		'installments_maximum'     => array(),
		'installments'             => array(),
		'billet_number_discount'   => array(),
		'billet_deadline_days'     => array(),
		'billet_instruction_line1' => array(),
		'billet_instruction_line2' => array(),
		'billet_instruction_line3' => array(),
		'billet_logo'              => array(),
		'webhook_token'            => array(),
		'webhook_id'               => array(),
		'authorize_data'           => array(),
		'hash_token'               => array(),
		'authorize_mode'           => array(),
		'enable_logs'              => array(),
	);

	private function __construct( $settings )
	{
		$this->set_settings( $settings );
	}

	public function __get( $key )
	{
		if ( isset( $this->{$key} ) ) {
			return $this->{$key};
		}

		return $this->_get_property( $key );
	}

	public function set( $key, $value )
	{
		if ( ! $this->is_valid_key( $key ) ) {
			return;
		}

		$settings = $this->get_settings();

		$settings[ $key ] = Utils::rm_tags( $value );

		$this->update_settings( $settings );
	}

	public function delete( $key )
	{
		$settings = $this->get_settings();

		if ( ! isset( $settings[ $key ] ) ) {
			return;
		}

		unset( $settings[ $key ] );

		$this->update_settings( $settings );
	}

	private function _get_property( $key )
	{
		if ( ! $this->is_valid_key( $key ) ) {
			return false;
		}

		$sanitize     = Utils::get_value_by( $this->_fields[ $key ], 'sanitize' );
		$value        = Utils::get_value_by( $this->get_settings(), $key );
		$this->{$key} = Utils::sanitize( $value, $sanitize );

		return $this->{$key};
	}

	public function get_option_key()
	{
		return Core::tag_name( 'settings' );
	}

	public function set_settings( $settings )
	{
		$this->_settings = $settings ? $settings : get_option( $this->get_option_key() );
	}

	public function get_settings()
	{
		return $this->_settings;
	}

	public function update_settings( array $settings )
	{
		$this->_settings = null;

		update_option( $this->get_option_key(), $settings );

		$this->set_settings( $settings );
	}

	public function log()
	{
		return new \WC_Logger();
	}

	public function is_enabled_logs()
	{
		return ( 'yes' === $this->__get( 'enable_logs' ) );
	}

	public function is_enabled()
	{
		return ( 'yes' === $this->__get( 'enabled' ) );
	}

	public function get_transation_id( $order_id )
	{
		return $this->__get( 'invoice_prefix' ) . $order_id;
	}

	public function is_active_billet_banking()
	{
		return ( 'yes' === $this->__get( 'billet_banking' ) );
	}

	public function is_active_banking_debit()
	{
		return ( 'yes' === $this->__get( 'banking_debit' ) );
	}

	public function is_active_credit_card()
	{
		return ( 'yes' === $this->__get( 'credit_card' ) );
	}

	public function is_active_access_token()
	{
		return ( 'yes' === $this->__get( 'field_enable_acesstoken' ) );
	}

	public function is_sandbox()
	{
		return ( 'production' !== $this->__get( 'authorize_mode' ) || ! $this->is_enabled() );
	}

	public function is_checkout_transparent()
	{
		return ( 'transparent_checkout' === $this->__get( 'payment_api' ) );
	}

	public function is_checkout_default()
	{
		return ( 'default_checkout' === $this->__get( 'payment_api' ) );
	}

	public function is_active_installments()
	{
		return ( 'yes' === $this->__get( 'installments_enabled' ) );
	}

	public function is_valid_key( $key )
	{
		return isset( $this->_fields[ $key ] );
	}

	public function is_valid_webhook()
	{
		if ( ! $this->__get( 'hash_token' ) ) {
			return false;
		}

		if ( $this->__get( 'webhook_id' ) && $this->__get( 'webhook_token' ) ) {
			return true;
		}

		return false;
	}

	public static function get_instance( $settings = false )
	{
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $settings );
		}

		return self::$_instance;
	}
}
