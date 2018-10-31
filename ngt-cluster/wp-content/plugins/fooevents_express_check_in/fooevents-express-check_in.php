<?php if ( ! defined( 'ABSPATH' ) ) exit; 
/**
 * Plugin Name: FooEvents Express Check-in
 * Description: Adds admin check-in screen to FooEvents
 * Version: 1.2.3
 * Author: FooEvents
 * Plugin URI: https://www.fooevents.com/
 * Author URI: https://www.fooevents.com/
 * Developer: FooEvents
 * Developer URI: https://www.fooevents.com/
 * Text Domain: fooevents-express-check-in
 *
 * Copyright: © 2009-2017 FooEvents.
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

//include config
require(WP_PLUGIN_DIR.'/fooevents_express_check_in/config.php');
require(WP_PLUGIN_DIR.'/fooevents_express_check_in/vendors/WordPress-Plugin-Update-Notifier/update-notifier.php');

class FooEvents_Express_Check_in {
    
    private $Config;
    private $TicketHelper;
    
    public function __construct() {

        add_action( 'admin_notices', array( $this, 'check_fooevents' ) );
        add_action( 'plugins_loaded', array( $this, 'load_text_domain' ) );
        add_action( 'init', array( $this, 'plugin_init' ) );
        add_action( 'admin_init', array( $this, 'register_scripts_and_styles' ) );
        
    }
    
    /**
     * Checks if FooEvents is installed
     * 
     */
    public function check_fooevents() {
        
        if ( !is_plugin_active( 'fooevents/fooevents.php' ) ) {

                $this->output_notices(array(__( 'The FooEvents Express Check-in plugin requires FooEvents for WooCommerce to be installed.', 'fooevents-express-check-in' )));

        } 
        
    }
    
    /**
     * Loads text domain and readies translations
     * 
     */
    public function load_text_domain() {

        $path = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
        $loaded = load_plugin_textdomain( 'fooevents-express-check-in', false, $path);
        
        
    }
    
    /**
     * Initializes plugin
     * 
     */
    public function plugin_init() {
        
        //Main config
        $this->Config = new FooEvents_Express_Check_In_Config();
        
        //TicketHelper
        require_once($this->Config->classPath.'tickethelper.php');
        $this->TicketHelper = new FooEvents_Express_Check_In_Ticket_Helper($this->Config);
        
    }
    
    /**
     * Register JavaScript and CSS file in Wordpress admin
     * 
     */
    public function register_scripts_and_styles() {
        
        if(!empty($_GET['page'])) {
            
            if ($_GET['page'] == 'fooevents-express-checkin-page') {

                wp_enqueue_script( 'fooevents-express-check-in-admin-script',  $this->Config->scriptsPath . 'check-in-admin.js', array(), '1.0.0', true  );
                wp_enqueue_style( 'fooevents-express-check-in-admin-style',  $this->Config->stylesPath . 'check-in-admin.css', array(), '1.0.0' );

            }
            
        }
        
    }
    
    /**
     * Outputs notices to screen.
     * 
     * @param array $notices
     */
    private function output_notices($notices) {

        foreach ($notices as $notice) {

                echo "<div class='updated'><p>$notice</p></div>";

        }

    }
    
}

$FooEvents_Express_Check_in = new FooEvents_Express_Check_in();