<?php if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Plugin Name: FooEvents for WooCommerce
 * Description: Adds event and ticketing features to WooCommerce
 * Version: 1.5.23 
 * Author: FooEvents
 * Plugin URI: https://www.fooevents.com/
 * Author URI: https://www.fooevents.com/
 * Developer: FooEvents
 * Developer URI: https://www.fooevents.com/
 * Text Domain: woocommerce-events
 *
 * Copyright: © 2009-2017 FooEvents.
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

//include config
require(WP_PLUGIN_DIR.'/fooevents/config.php');
require(WP_PLUGIN_DIR.'/fooevents/vendors/WordPress-Plugin-Update-Notifier/update-notifier.php');

class FooEvents {

    private $WooHelper;
    private $ICSHelper;
    private $Config;
    private $XMLRPCHelper;
    private $CommsHelper;
    private $CheckoutHelper;
    private $TicketHelper;
    private $Salt;

    public function __construct() {

        $plugin = plugin_basename(__FILE__); 

        add_action( 'init', array( $this, 'plugin_init' ) );
        add_action( 'admin_init', array( $this, 'register_scripts' ) );
        add_action( 'admin_notices', array( $this, 'check_woocommerce_events' ) );
        add_action( 'admin_notices', array( $this, 'check_fooevents_errors' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts_frontend' ) );
        add_action( 'admin_init', array( $this, 'register_styles' ) );
        add_action( 'admin_menu', array( $this, 'add_woocommerce_submenu' ) );
        add_action( 'woocommerce_settings_tabs_settings_woocommerce_events', array( $this, 'add_settings_tab_settings' ) );
        add_action( 'woocommerce_update_options_settings_woocommerce_events', array( $this, 'update_settings_tab_settings' ) );
        add_action( 'wp_ajax_fooevents_ics', array( $this, 'fooevents_ics' ) );
        add_action( 'wp_ajax_nopriv_fooevents_ics', array( $this, 'fooevents_ics' ) );
        add_action( 'plugins_loaded', array( $this, 'load_text_domain' ) );
        
        add_action( 'activated_plugin', array($this, 'activate_plugin') );
        
        add_action( 'wp_ajax_woocommerce_events_cancel', array( $this, 'woocommerce_events_cancel' ) );
        add_action( 'wp_ajax_nopriv_woocommerce_events_cancel', array( $this, 'woocommerce_events_cancel' ) );
        
        add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_tab' ) );
        add_filter( 'plugin_action_links_'.$plugin, array( $this, 'add_plugin_links' ) );
        add_filter( 'add_to_cart_text', array( $this, 'woo_custom_cart_button_text' ) );
        add_filter( 'woocommerce_product_single_add_to_cart_text', array( $this, 'woo_custom_cart_button_text' ) );
        add_filter( 'woocommerce_product_add_to_cart_text', array( $this, 'woo_custom_cart_button_text' ) );
        add_filter( 'wp_footer', array( $this, 'thickbox' ) );
        
        
    }
    
    /**
     * Basic checks to see if FooEvents will run correctly. 
     * 
     */
    public function check_woocommerce_events() {

        if ( $this->is_plugin_active( 'woocommerce_events/woocommerce-events.php' ) ) {

                $this->output_notices(array(__( 'WooCommerce Events has re-branded to FooEvents. Please disable and remove the older WooCommerce Events plugin.', 'woocommerce-events' )));

        } 
        
        $barcodesDirectory = __DIR__.'/barcodes/';
        if(!is_writable($barcodesDirectory)) {

            $this->output_notices(array(sprintf(__( 'Directory %s is not writeable', 'woocommerce-events' ), $barcodesDirectory)));

        }

        
    }
    
    
    /**
     * Checks for and displays FooEvents errors. 
     * 
     */
    public function check_fooevents_errors() {
        
        $errorCodes = array(
            '1' => __('Purchaser username already used. Ticket was not created', 'woocommerce-events'),
            '2' => __('An error occured. Ticket was not created', 'woocommerce-events'),
            '3' => __('Purchaser email address already used. Ticket was not created', 'woocommerce-events'),
        );
        
        if(!empty($_GET['fooevents_error'])) {
            
            $this->output_notices(array($errorCodes[$_GET['fooevents_error']]));
            
        }
        
    }

    /**
     *  Initialize events plugin and helpers.
     * 
     */
    public function plugin_init() {

        //Main config
        $this->Config = new FooEvents_Config();

        //WooHelper
        require_once($this->Config->classPath.'woohelper.php');
        $this->WooHelper = new FooEvents_Woo_Helper($this->Config);
        
        //ICSHelper
        require_once($this->Config->classPath.'icshelper.php');
        $this->ICSHelper = new FooEvents_ICS_helper($this->Config);
        
        //CommsHelper
        require_once($this->Config->classPath.'commshelper.php');
        $this->CommsHelper = new FooEvents_Comms_Helper($this->Config);
        
        //XMLRPCHelper
        require_once($this->Config->classPath.'xmlrpchelper.php');
        $this->XMLRPCHelper = new FooEvents_XMLRPC_Helper($this->Config);
        
        //AttendeeHelper
        require_once($this->Config->classPath.'checkouthelper.php');
        $this->CheckoutHelper = new FooEvents_Checkout_Helper($this->Config);
        
        $this->Salt = $this->Config->salt;
        
        if(empty($this->Salt)) {
            
            $salt = rand(111111,999999); 
            update_option('woocommerce_events_do_salt', $salt);
            $this->Salt = $salt;
            $this->Config->salt = $salt;
        }
        
    }
    
    /**
     * Register plugin scripts.
     * 
     */
    public function register_scripts() {
        
        global $wp_locale;

        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_script( 'wp-color-picker');
        wp_enqueue_script( 'woocommerce-events-admin-script', $this->Config->scriptsPath . 'events-admin.js', array( 'jquery-ui-datepicker', 'wp-color-picker' ), '1.0.0', true );

        $localArgs = array(
            'closeText'         => __( 'Done', 'woocommerce-events' ),
            'currentText'       => __( 'Today', 'woocommerce-events' ),
            'monthNames'        => $this->_strip_array_indices( $wp_locale->month ),
            'monthNamesShort'   => $this->_strip_array_indices( $wp_locale->month_abbrev ),
            'monthStatus'       => __( 'Show a different month', 'woocommerce-events' ),
            'dayNames'          => $this->_strip_array_indices( $wp_locale->weekday ),
            'dayNamesShort'     => $this->_strip_array_indices( $wp_locale->weekday_abbrev ),
            'dayNamesMin'       => $this->_strip_array_indices( $wp_locale->weekday_initial ),
            // set the date format to match the WP general date settings
            'dateFormat'        => $this->_date_format_php_to_js( get_option( 'date_format' ) ),
            // get the start of week from WP general setting
            'firstDay'          => get_option( 'start_of_week' ),
            // is Right to left language? default is false
            'isRTL'             => $wp_locale->is_rtl(),
        );
        
        wp_localize_script( 'woocommerce-events-admin-script', 'localObj', $localArgs );
        
    }
    
    /**
     * Registers scripts on the Wordpress frontend.
     * 
     */
    public function register_scripts_frontend() {

        wp_enqueue_script( 'woocommerce-events-front-script',  $this->Config->scriptsPath . 'events-frontend.js', array(), '1.0.0', true  );
        
    }

    /**
     * Register plugin styles.
     * 
     */
    public function register_styles() {

        wp_enqueue_style( 'woocommerce-events-admin-script',  $this->Config->stylesPath . 'events-admin.css', array(), '1.0.0'  );
        wp_enqueue_style('jquery-style', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');

        wp_enqueue_style( 'wp-color-picker');

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

    /**
     * Adds option to for redirect.
     * 
     */
    static function activate_plugin($plugin) {
        
        $salt = rand(111111,999999); 
        update_option('woocommerce_events_do_salt', $salt);
 
        if( $plugin == plugin_basename( __FILE__ ) ) {

            wp_redirect('admin.php?page=woocommerce-events-help'); exit;
            
        }    

    }

    /**
     * Adds a settings tab to WooCommerce
     * 
     * @param array $settings_tabs
     */
    public function add_settings_tab($settings_tabs) {

        $settings_tabs['settings_woocommerce_events'] = __( 'Events', 'woocommerce-settings-woocommerce-events' );
        return $settings_tabs;


    }

    /**
     * Adds the WooCommerce tab settings
     * 
     */
    public function add_settings_tab_settings() {

        woocommerce_admin_fields( $this->get_tab_settings() );

    }

    /**
     * Gets the WooCommerce tab settings
     * 
     * @return array $settings
     */
    public function get_tab_settings() {
        
        $settings = array(
            'section_start' => array(
                'type' => 'sectionstart',
                'id' => 'wc_settings_tab_demo_section_start'
            ),
            'section_title' => array(
                'name'      => __( 'Event Settings', 'woocommerce-events' ),
                'type'      => 'title',
                'desc'      => '',
                'id'        => 'wc_settings_fooevents_settings_title'
            ),
            'globalWooCommerceEventsTicketBackgroundColor' => array(
                'name'      => __( 'Global ticket border', 'woocommerce-events' ),
                'type'      => 'text',
                'id'        => 'globalWooCommerceEventsTicketBackgroundColor',
                'class'     => 'color-field'
            ),
            'globalWooCommerceEventsTicketButtonColor' => array(
                'name'      => __( 'Global ticket button', 'woocommerce-events' ),
                'type'      => 'text',
                'id'        => 'globalWooCommerceEventsTicketButtonColor',
                'class'     => 'color-field'
            ),
            'globalWooCommerceEventsTicketTextColor' => array(
                'name'      => __( 'Global ticket button text', 'woocommerce-events' ),
                'type'      => 'text',
                'id'        => 'globalWooCommerceEventsTicketTextColor',
                'class'     => 'color-field'
            ),
            'globalWooCommerceEventsTicketLogo' => array(
                'name'      => __( 'Global ticket logo', 'woocommerce-events' ),
                'type'      => 'text',
                'id'        => 'globalWooCommerceEventsTicketLogo',
                'desc'      => __( 'URL to ticket logo file', 'woocommerce-events' ),
                'class' => 'text uploadfield'
            ),
            'globalWooCommerceEventsChangeAddToCart' => array(
                'name'  => __( 'Change add to cart text', 'woocommerce-events' ),
                'type'  => 'checkbox',
                'id'    => 'globalWooCommerceEventsChangeAddToCart',
                'desc'  => __( 'Changes "Add to cart" to "Book ticket" for event products', 'woocommerce-events' ),
                'class' => 'text uploadfield'
            ),
            'globalWooCommerceHideEventDetailsTab' => array(
                'name'      => __( 'Hide event details tab', 'woocommerce-events' ),
                'type'      => 'checkbox',
                'id'        => 'globalWooCommerceHideEventDetailsTab',
                'desc'      => __( 'Hides the event details tab on the product page', 'woocommerce-events' ),
                'class'     => 'text uploadfield' 
           ),
            'globalWooCommerceUsePlaceHolders' => array(
                'name'      => __( 'Use placeholders on checkout form', 'woocommerce-events' ),
                'type'      => 'checkbox',
                'id'        => 'globalWooCommerceUsePlaceHolders',
                'desc'      => __( "Displays the form place holders. Useful for themes that don't support form labels.", 'woocommerce-events' ),
                'class'     => 'text uploadfield' 
           ),
           'globalWooCommerceHideUnpaidTicketsApp' => array(
                'name'      => __( 'Hide unpaid tickets in app', 'woocommerce-events' ),
                'type'      => 'checkbox',
                'id'        => 'globalWooCommerceHideUnpaidTicketsApp',
                'desc'      => __( 'Hides the unpaid tickets in the iOS and Android apps', 'woocommerce-events' ),
                'class'     => 'text uploadfield' 
           ), 
            'globalWooCommerceEventsEmailAttendees' => array(
                'name'  => __( 'Email tickets to attendees', 'woocommerce-events' ),
                'type'  => 'checkbox',
                'id'    => 'globalWooCommerceEventsEmailAttendees',
                'desc'  => __( 'Sends tickets to attendees rather than the purchaser. Make sure "Capture individual attendee details?" is enabled in your products', 'woocommerce-events' )
            ),
            'globalWooCommerceEventsHideUnpaidTickets' => array(
                'name'  => __( 'Hide unpaid tickets', 'woocommerce-events' ),
                'type'  => 'checkbox',
                'id'    => 'globalWooCommerceEventsHideUnpaidTickets',
                'desc'  => __( 'Hides unpaid tickets in ticket admin', 'woocommerce-events' )
            ),
            'globalWooCommerceEventsGoogleMapsAPIKey' => array(
                'name'  => __( 'Google Maps API key', 'woocommerce-events' ),
                'type'  => 'text',
                'id'    => 'globalWooCommerceEventsGoogleMapsAPIKey',
                'desc'  => __( 'Enable JavaScript API on Google Maps and copy the API key here', 'woocommerce-events' )
            ),
            'section_end' => array(
                'type' => 'sectionend',
                'id' => 'wc_settings_tab_demo_section_end'
            ),
            'app_settings_section_start' => array(
                'type' => 'sectionstart',
                'id' => 'wc_settings_tab_app_settings_section_start'
            ),
            'app_settings_section_title' => array(
                'name' => __( 'Pro App Settings', 'woocommerce-events' ),
                'type' => 'title',
                'desc' => 'The following settings can be used to customize the appearance of the FooEvents Check-ins Pro app once you have logged in. You will find more information on the pro app and a download link here: <a href="http://www.fooevents.com/apps/pro" target="_blank">http://www.fooevents.com/apps/pro</a>',
                'id' => 'wc_settings_woocommerce_events_app_settings_section_title'
            ),
            'globalWooCommerceEventsAppLogo' => array(
                'name' => __( 'App logo', 'woocommerce-events' ),
                'type' => 'text',
                'id' => 'globalWooCommerceEventsAppLogo',
                'desc' => __( 'URL to the image that will display on the app\'s sign-in screen. A PNG image with transparency and a width of around 940px is recommended', 'woocommerce-events' ),
                'class' => 'text uploadfield'
            ),
            'globalWooCommerceEventsAppColor' => array(
                'name' => __( 'Color', 'woocommerce-events' ),
                'type' => 'text',
                'id' => 'globalWooCommerceEventsAppColor',
                'desc' => __( 'Used for the app\'s top navigation bar and sign-in button', 'woocommerce-events' ),
                'class' => 'color-field'
            ),
            'globalWooCommerceEventsAppTextColor' => array(
                'name' => __( 'Text color', 'woocommerce-events' ),
                'type' => 'text',
                'id' => 'globalWooCommerceEventsAppTextColor',
                'desc' => __( 'Used for the text in the app\'s top navigation bar and sign-in button', 'woocommerce-events' ),
                'class' => 'color-field'
            ),
            'globalWooCommerceEventsAppBackgroundColor' => array(
                'name' => __( 'Background color', 'woocommerce-events' ),
                'type' => 'text',
                'id' => 'globalWooCommerceEventsAppBackgroundColor',
                'desc' => __( 'Used for the sign-in screen\'s background', 'woocommerce-events' ),
                'class' => 'color-field'
            ),
            'globalWooCommerceEventsAppSignInTextColor' => array(
                'name' => __( 'Sign-in screen text color', 'woocommerce-events' ),
                'type' => 'text',
                'id' => 'globalWooCommerceEventsAppSignInTextColor',
                'desc' => __( 'Used for the text beneath the logo on the app\'s sign-in screen', 'woocommerce-events' ),
                'class' => 'color-field'
            ),
            'app_settings_section_end' => array(
                'type' => 'sectionend',
                'id' => 'wc_settings_tab_app_settings_section_end'
            )
            
        );
        
        if($this->Config->clientMode === true) {
            $settings['globalWooCommerceEventsChangeCanceledMessage'] = array(
                'name'  => __( 'Canceled ticket message', 'woocommerce-events' ),
                'type'  => 'textarea',
                'id'    => 'globalWooCommerceEventsCanceledTicketMessage',
                'default' => __( 'Your ticket has been canceled.', 'woocommerce-events' ),
                'class' => 'text uploadfield'
            );
        }

        $settings['section_end'] = array(
            'type' => 'sectionend',
            'id' => 'wc_settings_fooevents_settings_end'
        );

        return $settings;
    }

    /**
     * Saves the WooCommerce tab settings
     * 
     */
    public function update_settings_tab_settings() {

        woocommerce_update_options( $this->get_tab_settings() );

    }
    
    /**
     * Adds the WooCommerce sub menu
     * 
     */
    public function add_woocommerce_submenu() {

        add_submenu_page( 'null',__( 'FooEvents Introduction', 'woocommerce-events' ), __( 'FooEvents Introduction', 'woocommerce-events' ), 'manage_options', 'woocommerce-events-help', array($this, 'add_woocommerce_submenu_page') ); 

    }
    
    /**
     * Adds the WooCommerce sub menu page
     * 
     */
    public function add_woocommerce_submenu_page() {
        
        require($this->Config->templatePath.'pluginintroduction.php');

    }
    
    /**
     * Adds plugin links to the plugins page
     * 
     * @param array $links
     * @return array $links
     */
    public function add_plugin_links($links) {
        
        $linkSettings = '<a href="admin.php?page=wc-settings&tab=settings_woocommerce_events">'.__( 'Settings', 'woocommerce-events' ).'</a>'; 
        array_unshift($links, $linkSettings); 
        
        $linkIntroduction = '<a href="admin.php?page=woocommerce-events-help">'.__( 'Introduction', 'woocommerce-events' ).'</a>'; 
        array_unshift($links, $linkIntroduction); 
        
        return $links;
        
    }
    
    /**
     * Builds the calendar ICS file
     * 
     */
    public function fooevents_ics() {
        
        /*error_reporting(E_ALL);
        ini_set('display_errors', '1');*/
        
        $event = (int)$_GET['event'];
        
        $post = get_post($event);
        
        $WooCommerceEventsEvent         = get_post_meta($event, 'WooCommerceEventsEvent', true);
        $WooCommerceEventsDate          = get_post_meta($event, 'WooCommerceEventsDate', true);
        $WooCommerceEventsHour          = get_post_meta($event, 'WooCommerceEventsHour', true);
        $WooCommerceEventsMinutes       = get_post_meta($event, 'WooCommerceEventsMinutes', true);
        $WooCommerceEventsHourEnd       = get_post_meta($event, 'WooCommerceEventsHourEnd', true);
        $WooCommerceEventsMinutesEnd    = get_post_meta($event, 'WooCommerceEventsMinutesEnd', true);
        $WooCommerceEventsLocation      = get_post_meta($event, 'WooCommerceEventsLocation', true);

        $startDate = date("Y-m-d H:i", strtotime($WooCommerceEventsDate." ".$WooCommerceEventsHour.':'.$WooCommerceEventsMinutes));
        $endDate = date("Y-m-d H:i", strtotime($WooCommerceEventsDate." ".$WooCommerceEventsHourEnd.':'.$WooCommerceEventsMinutesEnd));
        
        $this->ICSHelper->build_ICS($startDate, $endDate,$post->post_title, get_bloginfo('name'), $WooCommerceEventsLocation);
        $this->ICSHelper->show();
        
        exit();
    }
    
    /**
     * TODO: Attendee cancel ticket.
     * 
     */
    public function woocommerce_events_cancel() {
        
        $ticketID   = (int)$_GET['id'];
        $timestamp  = (int)$_GET['t'];
        $key        = $_GET['k'];
        
        $serial = md5($ticketID + $timestamp + $this->Salt);

        //echo $serial;
        //echo '-->'.$this->Salt; exit();
        
        if($serial != $key) {

            echo "Error!";
            exit();
            
        } else {
            
            $tickets = new WP_Query( array('post_type' => array('event_magic_tickets'), 'meta_query' => array( array( 'key' => 'WooCommerceEventsTicketID', 'value' => $ticketID ) )) );
            $tickets = $tickets->get_posts();
            
            /*echo "<pre>";
                print_r($tickets);
            echo "</pre>";*/
            
            $ticket = $tickets[0];
            
            update_post_meta($ticket->ID, 'WooCommerceEventsStatus', 'Canceled');
            wp_redirect( home_url().'?wc_events=canceled' ); exit();
            
        }
        
        exit();
    }
    
    /**
     * Changes the WooCommerce 'Add to cart' text
     * 
     */
    public function woo_custom_cart_button_text($text) {
        
        global $post;
        
        $WooCommerceEventsEvent                         = get_post_meta($post->ID, 'WooCommerceEventsEvent', true);
        $globalWooCommerceEventsChangeAddToCart         = get_option('globalWooCommerceEventsChangeAddToCart', true);
        
        if($WooCommerceEventsEvent == 'Event' && $globalWooCommerceEventsChangeAddToCart === 'yes') {
        
            return __( 'Book ticket', 'woocommerce-events' );
        
        } else {
            
            return $text;
            
        }
        
    }
    
    /**
     * External access to ticket data
     * 
     * @param int $ticketID
     * @return array
     */
    public function get_ticket_data($ticketID) {
        
        //Main config
        $this->Config = new FooEvents_Config();
        
        //TicketHelper
        require_once($this->Config->classPath.'tickethelper.php');
        $this->TicketHelper = new FooEvents_Ticket_Helper($this->Config);
        
        $ticket_data = $this->TicketHelper->get_ticket_data($ticketID);
        
        return $ticket_data;
        
    }
    
    public function get_plugin_path() {
        
        return $this->Config->path;
        
    }
    
    public function get_plugin_url() {
        
        return $this->Config->eventPluginURL;
        
    }
    
    /**
     * Loads text-domain for localization
     * 
     */
    public function load_text_domain() {
        
        $path = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
        $loaded = load_plugin_textdomain( 'woocommerce-events', false, $path);
        
        /*if ( ! $loaded )
        {
            print "File not found: $path"; 
            exit;
        }*/
        
    }
    
    /**
     * Adds thickbox for notifications
     * 
     */
    public function thickbox() {

        
        /*if(!empty($_GET['wc_events'])) {
            
            if($_GET['wc_events'] == 'canceled') {
                
                $globalWooCommerceEventsCanceledTicketMessage = get_option('globalWooCommerceEventsCanceledTicketMessage', true);
                
                if(empty($globalWooCommerceEventsCanceledTicketMessage) || $globalWooCommerceEventsCanceledTicketMessage == '1') {
                    
                    $globalWooCommerceEventsCanceledTicketMessage = "Your ticket has been canceled.";
                    
                }
                
                add_thickbox();
                echo '
                    <div id="woocommerce-events-cancel-box" style="display:none;">
                        <p>
                             '.$globalWooCommerceEventsCanceledTicketMessage.'
                        </p>
                    </div>
                ';

            }
        }*/
        
    }

    /**
    * Format array for the datepicker
    *
    * WordPress stores the locale information in an array with a alphanumeric index, and
    * the datepicker wants a numerical index. This function replaces the index with a number
    */
    private function _strip_array_indices( $ArrayToStrip ) {
        
        foreach( $ArrayToStrip as $objArrayItem) {
            $NewArray[] =  $objArrayItem;
        }

        return( $NewArray );
        
    }
    
    /**
    * Convert the php date format string to a js date format
    */
   private function _date_format_php_to_js( $sFormat ) {
       
        switch( $sFormat ) {
            //Predefined WP date formats
            case 'jS F Y':
            return( 'd MM, yy' );
            break;
            case 'F j, Y':
            return( 'MM dd, yy' );
            break;
            case 'Y/m/d':
            return( 'yy/mm/dd' );
            break;
            case 'm/d/Y':
            return( 'mm/dd/yy' );
            break;
            case 'd/m/Y':
            return( 'dd/mm/yy' );
            break;
            default:
            return( 'yy-mm-dd' );
        }
        
    }
   
   private function is_plugin_active( $plugin ) {

        return in_array( $plugin, (array) get_option( 'active_plugins', array() ) );

    }

}

$FooEvents = new FooEvents();

//TODO: move this function into WooHelper
function fooevents_displayEventTab() {
    
    global $post;
    $Config = new FooEvents_Config();
    
    $WooCommerceEventsEvent             = get_post_meta($post->ID, 'WooCommerceEventsEvent', true);
    $WooCommerceEventsDate              = get_post_meta($post->ID, 'WooCommerceEventsDate', true);
    $WooCommerceEventsEndDate           = get_post_meta($post->ID, 'WooCommerceEventsEndDate', true);
    $WooCommerceEventsHour              = get_post_meta($post->ID, 'WooCommerceEventsHour', true);
    $WooCommerceEventsMinutes           = get_post_meta($post->ID, 'WooCommerceEventsMinutes', true);
    $WooCommerceEventsPeriod            = get_post_meta($post->ID, 'WooCommerceEventsPeriod', true);
    $WooCommerceEventsHourEnd           = get_post_meta($post->ID, 'WooCommerceEventsHourEnd', true);
    $WooCommerceEventsMinutesEnd        = get_post_meta($post->ID, 'WooCommerceEventsMinutesEnd', true);
    $WooCommerceEventsEndPeriod         = get_post_meta($post->ID, 'WooCommerceEventsEndPeriod', true);
    $WooCommerceEventsLocation          = get_post_meta($post->ID, 'WooCommerceEventsLocation', true);
    $WooCommerceEventsTicketLogo        = get_post_meta($post->ID, 'WooCommerceEventsTicketLogo', true);
    $WooCommerceEventsSupportContact    = get_post_meta($post->ID, 'WooCommerceEventsSupportContact', true);
    $WooCommerceEventsGPS               = get_post_meta($post->ID, 'WooCommerceEventsGPS', true);
    $WooCommerceEventsDirections        = get_post_meta($post->ID, 'WooCommerceEventsDirections', true);
    $WooCommerceEventsEmail             = get_post_meta($post->ID, 'WooCommerceEventsEmail', true);
    
    $multiDayEvent = false;
    
    if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
        require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
    }

    if (fooevents_check_plugin_active('fooevents_multi_day/fooevents-multi-day.php') || is_plugin_active_for_network('fooevents_multi_day/fooevents-multi-day.php')) {
        
        $multiDayEvent = true;
        
    }
    
    if(file_exists($Config->emailTemplatePathTheme.'eventtab.php') ) {
        
        require($Config->emailTemplatePathTheme.'eventtab.php');
    
    } else {
        
        require($Config->templatePath.'eventtab.php');
        
    }
    
}

function fooevents_displayEventTabMap() {
    
    global $post;
    $Config = new FooEvents_Config();
    
    $WooCommerceEventsGoogleMaps = get_post_meta($post->ID, 'WooCommerceEventsGoogleMaps', true);
    $globalWooCommerceEventsGoogleMapsAPIKey = get_option('globalWooCommerceEventsGoogleMapsAPIKey', true);
    
    $eventContent = $post->post_content;
    
    $eventContent = apply_filters( 'the_content', $eventContent );
    
    if(!empty($WooCommerceEventsGoogleMaps) && !empty($globalWooCommerceEventsGoogleMapsAPIKey)) {
        
        if(file_exists($Config->emailTemplatePathTheme.'eventtabmap.php') ) {

            require($Config->emailTemplatePathTheme.'eventtabmap.php');

        } else {

            require($Config->templatePath.'eventtabmap.php');

        }
        
    }
    
}

function fooevents_ics() {
    
    $Config = new FooEvents_Config();
    
    
}

add_action( 'wp_dashboard_setup', 'fooevents_dashboard_widget' );

function fooevents_dashboard_widget() {
    
    wp_add_dashboard_widget(
        'fooevents_widget',
        'FooEvents',
        'fooevents_widget_display'
    ); 
    
    
}

function fooevents_widget_display() {
    
    $events_query = new WP_Query( array('post_type' => array('event_magic_tickets'), 'posts_per_page' => -1, 'meta_query' => array( array( 'key' => 'WooCommerceEventsStatus', 'value' => 'Unpaid', 'compare' => '!=' ) )) );
    $events = $events_query->get_posts();
    $ticket_count = $events_query->found_posts;
    
    $events_query = new WP_Query( array('post_type' => array('event_magic_tickets'), 'posts_per_page' => -1, 'meta_query' => array( array( 'key' => 'WooCommerceEventsStatus', 'value' => 'Not Checked In', 'compare' => '=' ) )) );
    $events = $events_query->get_posts();
    $not_checked_in_count = $events_query->found_posts;
    
    $events_query = new WP_Query( array('post_type' => array('event_magic_tickets'), 'posts_per_page' => -1, 'meta_query' => array( array( 'key' => 'WooCommerceEventsStatus', 'value' => 'Checked In', 'compare' => '=' ) )) );
    $events = $events_query->get_posts();
    $checked_in_count = $events_query->found_posts;
    
    $events_query = new WP_Query( array('post_type' => array('event_magic_tickets'), 'posts_per_page' => -1, 'meta_query' => array( array( 'key' => 'WooCommerceEventsStatus', 'value' => 'Checked In', 'compare' => '=' ) )) );
    $events = $events_query->get_posts();
    $checked_in_count = $events_query->found_posts;
    
    $events_query = new WP_Query( array('post_type' => array('product'), 'posts_per_page' => -1, 'meta_query' => array( array( 'key' => 'WooCommerceEventsEvent', 'value' => 'Event', 'compare' => '=' ) )) );
    $events = $events_query->get_posts();
    $event_count = $events_query->found_posts;
    
    $fooevents = get_plugin_data(WP_PLUGIN_DIR.'/fooevents/fooevents.php');
    $woocommerce = get_plugin_data(WP_PLUGIN_DIR.'/woocommerce/woocommerce.php');

    echo "<div class='fooevents_widget_item'>"."Total tickets: ".$ticket_count."</div>";
    
    echo "<div class='fooevents_widget_item'>"."Total events: ".$event_count."</div>";

    echo "<div class='fooevents_widget_item'>"."Tickets 'Not Checked In': ".$not_checked_in_count."</div>";
    
    echo "<div class='fooevents_widget_item'>"."Tickets 'Checked In': ".$checked_in_count."</div>";

    echo "<p><a href='".$fooevents['PluginURI']."' target='_BLANK'>FooEvents</a> ".$fooevents['Version']. " running on <a href='".$woocommerce['PluginURI']."' target='_BLANK'>WooCommerce</a> ".$woocommerce['Version']."</p>";

    if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
        require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
    }
    
    $fooevents_pdf_tickets_active = 'No';
    $fooevents_pdf_tickets = array('Version' => '');
    if ( fooevents_check_plugin_active('fooevents_pdf_tickets/fooevents-pdf-tickets.php') || is_plugin_active_for_network('fooevents_pdf_tickets/fooevents-pdf-tickets.php')) {
        
        $fooevents_pdf_tickets_active = 'Yes';
        $fooevents_pdf_tickets = get_plugin_data(WP_PLUGIN_DIR.'/fooevents_pdf_tickets/fooevents-pdf-tickets.php');
        
    }
    
    echo "<div class='fooevents_widget_item'><a href='https://www.fooevents.com/fooevents-pdf-tickets/' target='_BLANK'>FooEvents PDF tickets</a>: "."<b>".$fooevents_pdf_tickets_active."</b> ".$fooevents_pdf_tickets['Version']."</div>";
    
    $fooevents_express_check_in_active = 'No';
    $fooevents_express_check_in = array('Version' => '');
    if ( fooevents_check_plugin_active('fooevents_express_check_in/fooevents-express-check_in.php') || is_plugin_active_for_network('fooevents_express_check_in/fooevents-express-check_in.php')) {
        
        $fooevents_express_check_in_active = 'Yes';
        $fooevents_express_check_in = get_plugin_data(WP_PLUGIN_DIR.'/fooevents_express_check_in/fooevents-express-check_in.php');
        
    }
    
    echo "<div class='fooevents_widget_item'><a href='https://www.fooevents.com/fooevents-express-check-in/' target='_BLANK'>FooEvents Express Check-in</a>: "."<b>".$fooevents_express_check_in_active."</b> ".$fooevents_express_check_in['Version']."</div>";
   
    $fooevents_calendar_active = 'No';
    $fooevents_calendar = array('Version' => '');
    if ( fooevents_check_plugin_active('fooevents_calendar/fooevents-calendar.php') || is_plugin_active_for_network('fooevents_calendar/fooevents-calendar.php')) {
        
        $fooevents_calendar_active = 'Yes';
        $fooevents_calendar = get_plugin_data(WP_PLUGIN_DIR.'/fooevents_calendar/fooevents-calendar.php');
        
    }
    
    echo "<div class='fooevents_widget_item'><a href='https://www.fooevents.com/fooevents-calendar/' target='_BLANK'>FooEvents Calendar</a>: "."<b>".$fooevents_calendar_active."</b> ".$fooevents_calendar['Version']."</div>";
    
    $fooevents_custom_attendee_fields_active = 'No';
    $fooevents_custom_attendee_fields = array('Version' => '');
    if ( fooevents_check_plugin_active('fooevents_custom_attendee_fields/fooevents-custom-attendee-fields.php') || is_plugin_active_for_network('fooevents_custom_attendee_fields/fooevents-custom-attendee-fields.php')) {
        
        $fooevents_custom_attendee_fields_active = 'Yes';
        $fooevents_custom_attendee_fields = get_plugin_data(WP_PLUGIN_DIR.'/fooevents_custom_attendee_fields/fooevents-custom-attendee-fields.php');
        
    }
    
    echo "<div class='fooevents_widget_item'><a href='https://www.fooevents.com/fooevents-custom-attendee-fields/' target='_BLANK'>FooEvents Custom Attendee Fields</a>: "."<b>".$fooevents_custom_attendee_fields_active."</b> ".$fooevents_custom_attendee_fields['Version']."</div>";
    
}

function fooevents_check_plugin_active( $plugin ) {

    return in_array( $plugin, (array) get_option( 'active_plugins', array() ) );

}