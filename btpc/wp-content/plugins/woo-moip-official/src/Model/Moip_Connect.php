<?php
namespace Woocommerce\Moip\Model;

if ( ! function_exists( 'add_action' ) ) {
    exit( 0 );
}

// Moip SDK
use Moip\Moip;
use Moip\Auth\Connect;

//PHP Native
use stdClass;
use Exception;

use Woocommerce\Moip\Core;
use Woocommerce\Moip\Helper\Utils;
use Woocommerce\Moip\Model\Setting;

if ( ! defined( 'MOIP_API_URI' ) ) {
    define( 'MOIP_API_URI', 'https://moip.apiki.com' );
}

class Moip_Connect
{
    const AUTHORIZE_APP_API = 'moip-authorize-app';

    public function __construct()
    {
    }

    public function get_authorize_url( $mode )
    {
        $response = wp_remote_post(
            MOIP_API_URI,
            [
                'timeout'     => 60,
                'httpversion' => '1.1',
                'body'        => json_encode([
                    'redirect_uri' => $this->get_redirect_url(),
                    'mode'         => $mode,
                    'token'        => $this->get_hash(),
                ]),
                'headers'     => [
                    'Content-Type: application/json',
                    'Accept: application/json',
                ],
            ]
        );

        if ( is_wp_error( $response ) ) {
            return $response->get_error_message();
        }

        return json_decode( wp_remote_retrieve_body( $response ) );
    }

    public function get_redirect_url()
    {
        return sprintf( '%s/wc-api/%s/', Utils::get_site_url(), self::AUTHORIZE_APP_API );
    }

    public function get_hash()
    {
        $hash = sha1( uniqid( rand(), true ) );

        Setting::get_instance()->set( 'hash_token', $hash );

        return $hash;
    }
}
