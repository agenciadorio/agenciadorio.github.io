<?php
namespace Woocommerce\Moip\Model;

if ( ! function_exists( 'add_action' ) ) {
	exit( 0 );
}

use Woocommerce\Moip\Core;
use Woocommerce\Moip\Helper\Utils;

class Checkout
{
	private $ID;

	public function __construct( $ID = false )
	{
		if ( false !== $ID ) {
			$this->ID = absint( $ID );
		}
	}

	public function get_order()
	{
		return new Order( $this->ID );
	}

	public function prepare_fields( $form_data )
	{
		if ( empty( $form_data ) ) {
			return false;
		}

		$fields = array();

		foreach ( $form_data as $data ) {

			if ( ! isset( $data['name'] ) || ! isset( $data['value'] ) ) {
				continue;
			}

			if ( empty( $data['value'] ) ) {
				continue;
			}

			$fields[ $data['name'] ] = Utils::rm_tags( $data['value'], true );

			if ( $data['name'] == 'card_number' ) {
				$fields[ $data['name'] ] = Utils::format_document( $data['value'] );
			}

			if ( $data['name'] == 'card_expiry' ) {
				$expiry_pieces               = explode( '/',  $data['value'] );
				$fields['card_expiry_month'] = trim( $expiry_pieces[0] );
				$fields['card_expiry_year']  = trim( $expiry_pieces[1] );
			}
		}

		return $fields;
	}
}