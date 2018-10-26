<?php
namespace Woocommerce\Moip\Model;

if ( ! function_exists( 'add_action' ) ) {
	exit( 0 );
}

use Woocommerce\Moip\Core;
use Woocommerce\Moip\Helper\Utils;

class Customer extends Meta
{
	protected $stored_credit_card;
	protected $credit_card_hash;
	protected $credit_card_last_numbers;
	protected $credit_card_brand;

	public $type = 'user';

	public $with_prefix = array(
		'stored_credit_card'       => 1,
		'credit_card_hash'         => 1,
		'credit_card_last_numbers' => 1,
		'credit_card_brand'        => 1,
	);

	public function is_stored_credit_card()
	{
		return ( $this->__get( 'stored_credit_card' ) === '1' );
	}
}
