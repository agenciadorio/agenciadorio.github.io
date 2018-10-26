<?php
namespace Woocommerce\Moip\Model;

if ( ! function_exists( 'add_action' ) ) {
	exit( 0 );
}

//Moip SDK
use Moip\Resource\MoipResource;

//Create objects
use stdClass;

abstract class Resource extends MoipResource
{
    protected function initialize()
    {
		$this->data              = new stdClass();
		$this->data->type        = null;
		$this->data->createdAt   = null;
		$this->data->description = null;
    }

    protected function getType()
    {
        return $this->data->type;
    }

    protected function getDescription()
    {
        return $this->data->description;
    }

    protected function getCreatedAt()
    {
        return $this->getIfSetDateTime( 'createdAt' );
    }

    protected function populate( stdClass $response )
    {
        $this->data = $response;
    }

    protected function get_endpoint( $path )
    {
        return sprintf( '/%s/%s', self::VERSION, $path );
    }
}