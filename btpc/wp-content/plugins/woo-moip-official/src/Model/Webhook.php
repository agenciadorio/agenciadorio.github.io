<?php
namespace Woocommerce\Moip\Model;

if ( ! function_exists( 'add_action' ) ) {
	exit( 0 );
}

//Moip SDK
use Requests;

//Create objects
use stdClass;

use Woocommerce\Moip\Core;
use Woocommerce\Moip\Helper\Utils;

class Webhook extends Resource
{
    const PATH_NOTIFICATIONS = 'preferences/notifications';

    const PATH_CONSULT = 'webhooks';

    public function create( $args = array() )
    {
    	$defaults = array(
			'media'  => 'WEBHOOK',
			'target' => Core::get_webhook_url(),
			'events' => array(
				'ORDER.*',
				'PAYMENT.*',
    		),
    	);

        $response = $this->httpRequest(
            $this->get_endpoint( self::PATH_NOTIFICATIONS ),
            Requests::POST,
            array_merge( $defaults, $args )
        );

        $this->populate( (object) $response );

        return $this->data;
    }

    public function delete( $notification_id )
    {
        $path = sprintf( '%s/%s', self::PATH_NOTIFICATIONS, $notification_id );

        $response = $this->httpRequest(
            $this->get_endpoint( $path ),
            Requests::DELETE
        );

        $this->populate( (object) $response );

        return $this->data;
    }

    public function get( $notification_id = '' )
    {
        if ( ! empty( $notification_id ) ) {
            $notification_id = sprintf( '/%s', $notification_id );
        }

        $response = $this->httpRequest(
            $this->get_endpoint( self::PATH_NOTIFICATIONS . $notification_id ),
            Requests::GET
        );

        $this->populate( (object) $response );

        return $this->data;
    }

    public function get_sent( $resource_id = '' )
    {
        if ( ! empty( $resource_id ) ) {
            $resource_id = sprintf( '?resourceId=%s', $resource_id );
        }

        $response = $this->httpRequest(
            $this->get_endpoint( self::PATH_CONSULT . $resource_id ),
            Requests::GET
        );

        $this->populate( (object) $response );

        return $this->data;
    }

    public function sent_notification( $args = array() )
    {
        if ( ! isset( $args['resourceId'] ) ) {
            return false;
        }

        $defaults = array(
            'resourceId' => null,
            'event'      => 'ORDER.CREATED',
        );

        $response = $this->httpRequest(
            $this->get_endpoint( self::PATH_CONSULT ),
            Requests::POST,
            array_merge( $defaults, $args )
        );

        $this->populate( (object) $response );

        return $this->data;
    }
}