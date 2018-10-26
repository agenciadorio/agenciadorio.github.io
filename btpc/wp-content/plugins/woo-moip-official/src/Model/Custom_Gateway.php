<?php
namespace Woocommerce\Moip\Model;

if ( ! function_exists( 'add_action' ) ) {
	exit( 0 );
}

use Woocommerce\Moip\Core;
use Woocommerce\Moip\Helper\Utils;
use Woocommerce\Moip\Model\Setting;

class Custom_Gateway
{
	public $settings;

	public function __construct()
	{
		$this->settings = Setting::get_instance();
	}

	public function supported_currency() {
		return ( get_woocommerce_currency() === 'BRL' );
	}

	public function get_installment_options()
	{
		return array(
			2  => 2,
			3  => 3,
			4  => 4,
			5  => 5,
			6  => 6,
			7  => 7,
			8  => 8,
			9  => 9,
			10 => 10,
			11 => 11,
			12 => 12,
		);
	}
}