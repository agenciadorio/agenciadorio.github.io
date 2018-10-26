<?php
namespace Woocommerce\Moip\Model;

if ( ! function_exists( 'add_action' ) ) {
	exit( 0 );
}

//Moip SDK
use Requests;

use Woocommerce\Moip\Core;
use Woocommerce\Moip\Helper\Utils;

class Moip_Account extends Resource
{
	const KEYS = 'keys';

	public function __construct()
	{
		parent::__construct( $this->_moip() );
	}

	private function _moip()
	{
        $sdk = Moip_SDK::get_instance();

        return $sdk->moip;
	}

	public function get_keys()
	{
        $response = $this->httpRequest(
            $this->get_endpoint( self::KEYS ),
            Requests::GET
        );

        $this->populate( (object) $response );

        return $this->data;
	}

    public function get_public_key()
    {
        $data = $this->get_keys();

        if ( isset( $data->keys ) ) {
            return $data->keys->encryption;
        }

        return false;
    }
}