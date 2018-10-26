<?php
namespace Woocommerce\Moip\Model;

if ( ! function_exists( 'add_action' ) ) {
	exit( 0 );
}

use Woocommerce\Moip\Core;
use Woocommerce\Moip\Helper\Utils;

// WooCommerce
use WC_Order;

class Order extends Meta
{
	protected $payment_type;
	protected $payment_links;
	protected $payment_status;
	protected $resource_id;
	protected $payment_id;
	protected $processed;
	protected $payment_billet_linecode;
	protected $installments;

	// Checkout transparent order cache
	protected $ct_cache;

	// == BEGIN WC ORDER ==
	protected $billing_persontype;
	protected $billing_cnpj;
	protected $billing_first_name;
	protected $billing_last_name;
	protected $billing_email;
	protected $billing_birthdate;
	protected $billing_phone;
	protected $billing_address_1;
	protected $billing_number;
	protected $billing_neighborhood;
	protected $billing_city;
	protected $billing_state;
	protected $billing_postcode;
	protected $billing_address_2;
	protected $billing_cpf;
	// == END WC ORDER ==

	public $with_prefix = array(
		'payment_type'            => 1,
		'payment_links'           => 1,
		'payment_id'              => 1,
		'payment_status'          => 1,
		'payment_billet_linecode' => 1,
		'resource_id'             => 1,
		'ct_cache'                => 1,
		'processed'               => 1,
		'installments'            => 1
	);

	public function get_link_by_type( $type )
	{
		if ( ! $links = $this->__get( 'payment_links' ) ) {
			return null;
		}

		return isset( $links->{$type} ) ? esc_url( $links->{$type}->redirectHref ) : null;
	}

	public function payment_on_hold()
	{
		$order = new WC_Order( $this->ID );

		$order->update_status( 'on-hold', __( 'Moip: Awaiting payment confirmation.', Core::TEXTDOMAIN ) );
		//$order->reduce_order_stock();

		WC()->cart->empty_cart();
	}

	public function get_moip_status_translate()
	{
		return Utils::get_formatted_status( $this->__get( 'payment_status' ) );
	}

	public function is_bankslip_payment()
	{
		return ( $this->__get( 'payment_type' ) === 'payBoleto' );
	}
}