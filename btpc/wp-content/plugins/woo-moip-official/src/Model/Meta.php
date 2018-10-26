<?php
namespace Woocommerce\Moip\Model;

if ( ! function_exists( 'add_action' ) ) {
	exit( 0 );
}

use Woocommerce\Moip\Core;
use Woocommerce\Moip\Helper\Utils;

abstract class Meta
{
	public $ID;

	protected $type = 'post';

	protected $with_prefix = array();

	public function __construct( $ID = false )
	{
		if ( is_numeric( $ID ) ) {
			$this->ID = absint( $ID );
		}
	}

	public function __get( $prop_name )
	{
		if ( isset( $this->{$prop_name} ) ) {
			return $this->{$prop_name};
		}

		return $this->_get_property( $prop_name );
	}

	public function __set( $prop_name, $value )
	{
		$this->update_meta( $this->_get_meta_key( $prop_name ), $value );
	}

	public function __isset( $prop_name )
	{
		return $this->__get( $prop_name );
	}

	private function _get_property( $prop_name )
	{
		$this->{$prop_name} = $this->get_meta( $prop_name );

		return $this->{$prop_name};
	}

	public function get_meta( $meta_key, $sanitize = 'rm_tags' )
	{
		$value = get_metadata( $this->type, $this->ID, $this->_get_meta_key( $meta_key ), true );

		return Utils::sanitize( $value, $sanitize );
	}

	public function update_meta( $key, $value )
	{
		update_metadata( $this->type, $this->ID, $key, Utils::rm_tags( $value ) );
	}

	private function _get_meta_key( $prop_name )
	{
		return isset( $this->with_prefix[ $prop_name ] ) ? "_moip_{$prop_name}" : "_{$prop_name}";
	}
}