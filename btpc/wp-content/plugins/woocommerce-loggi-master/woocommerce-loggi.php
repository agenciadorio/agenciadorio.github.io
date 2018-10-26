<?php
/**
 * Plugin Name: WooCommerce Loggi
 * Plugin URI:  https://github.com/rodrigoterminus/woocommerce-loggi
 * Description: Add integration to Loggi.com shipment
 * Author:      Rodrigo Duarte
 * Author URI:  http://rodrigoterminus.com
 * Version:     1.0
 * License:     GPLv2 or later
 * Text Domain: woocommerce-loggi
 * Domain Path: /languages
 *
 * WooCommerce Loggi is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * WooCommerce Loggi is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with WooCommerce PagSeguro. If not, see
 * <https://www.gnu.org/licenses/gpl-2.0.txt>.
 *
 * @package WooCommerce_Loggi
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! class_exists( 'WC_Loggi' ) ) :
    include_once ABSPATH .'wp-content/plugins/woocommerce/includes/abstracts/abstract-wc-settings-api.php';
    include_once ABSPATH .'wp-content/plugins/woocommerce/includes/abstracts/abstract-wc-shipping-method.php';

    /**
     * WooCommerce Loggi main class.
     */
    class WC_Loggi extends WC_Shipping_Method {

        /**
         * Plugin version.
         *
         * @var string
         */
        const VERSION = '1.0';

        private $loggiHost;

        /**
         * Constructor for your shipping class
         *
         * @access public
         */
        public function __construct() {
            $this->id                 = 'woocommerce_loggi';
            $this->title       = __( 'Loggi' );
            $this->method_description = __( 'Shipment by Loggi' ); //
            $this->enabled            = "yes"; // This can be added as an setting but for this example its forced enabled
            $this->loggiBaseUrl = (in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1')))
                ? 'https://staging.loggi.com/api/v1' // Sandbox
                : 'https://www.loggi.com/api/v1';    // Production

            $this->init();
        }

        /**
         * Init your settings
         *
         * @access public
         * @return void
         */
        function init() {
            // Load the settings API
            $this->init_form_fields(); // This is part of the settings API. Override the method to add your own settings
            $this->init_settings(); // This is part of the settings API. Loads settings you previously init.

            // Save settings in admin if you have any defined,
            add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
            add_action('init', 'registerSession', 1);
            add_action('wp_logout', 'finishSession');
            add_action('wp_login', 'finishSession');
        }

        // @todo Criar método para testar se as credenciais informadas são válidas e alertar o adminstrador quando negativo

        private function registerSession() {
            if (!session_id()) {
                session_start();
            }
        }

        private function finishSession() {
            session_destroy ();
        }

        private function authenticate() {
            // @todo mover para configurações
            $params = array(
                'email' => 'contato@churrascopontocerto.com.br',
                'password' => '1234'
            );

            $payload = json_encode($params);

            $ch = curl_init($this->loggiBaseUrl .'/usuarios/login/');
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
            curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
            $result = curl_exec($ch);
            $response = json_decode($result);
            curl_close($ch);
        }

        /**
         * calculate_shipping function.
         *
         * @access public
         * @param mixed $package
         * @return void
         */
        public function calculate_shipping($package = array()) {
//            $this->authenticate();

            $cep = str_replace(array('.', '-'), '', $package['destination']['postcode']);
            $payload = json_encode(array(
                'transport_type' => 1,
                'city' => 3,
                'addresses' => array(
                    array(
                        'by' => 'address',
                        'query' => array(
                            'address' => 'Araújo Ribeiro',
                            'category' => 'Rua',
                            'number' => 69
                        )
                    ),
                    array(
                        'by' => 'cep',
                        'query' => array(
                            'cep' => $cep,
                            'number' => 1
                        )
                    )
                )
            ));

            $ch = curl_init($this->loggiBaseUrl .'/endereco/estimativa/');
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
            curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
                'Content-Type:application/json',
                'Authorization:ApiKey contato@churrascopontocerto.com.br:51bb5c99b9770197c2174ce8ff2efbfc74cb89dc',

            ));
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
            $result = curl_exec($ch);
            $response = json_decode($result, true);
            curl_close($ch);

            // @todo tratar cep inválido
            // @todo tratar exceptions
            // @todo remover método quando cep informado não for aceito pela API

            if (is_array($response) && !isset($response['error'])) {
                $rate = array(
                    'id' => $this->id,
                    'label' => $this->title,
                    'cost' => $response['normal']['estimated_cost'],
                    'calc_tax' => 'per_item'
                );

                // Register the rate
                $this->add_rate( $rate );
            }
        }
    }

    function addLoggiShippingMethod( $methods ) {
        $methods['woocommerce_loggi'] = 'WC_Loggi';
        return $methods;
    }

    add_filter('woocommerce_shipping_methods', 'addLoggiShippingMethod' );

endif;
