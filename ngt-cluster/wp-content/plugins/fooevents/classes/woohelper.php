<?php if ( ! defined( 'ABSPATH' ) ) exit;
class FooEvents_Woo_Helper {
	
    public  $Config;
    public  $TicketHelper;
    private $BarcodeHelper;
    public  $MailHelper;

    public function __construct($config) {

        $this->check_woocommerce_exists();
        $this->Config = $config;

        //TicketHelper
        require_once($this->Config->classPath.'tickethelper.php');
        $this->TicketHelper = new FooEvents_Ticket_Helper($this->Config);

        //BarcodeHelper
        require_once($this->Config->classPath.'barcodehelper.php');
        $this->BarcodeHelper = new FooEvents_Barcode_Helper($this->Config);
        
        //MailHelper
        require_once($this->Config->classPath.'mailhelper.php');
        $this->MailHelper = new FooEvents_Mail_Helper($this->Config);
        
        add_action('woocommerce_product_tabs', array(&$this, 'add_front_end_tab'), 10, 2);
        add_action('woocommerce_order_status_completed', array(&$this, 'send_ticket_email'), 10, 1);
        add_action( 'woocommerce_product_write_panel_tabs', array( $this, 'add_product_options_tab' ) );
        add_action( 'woocommerce_product_data_panels', array( $this, 'add_product_options_tab_options' ) );
        add_action( 'woocommerce_process_product_meta', array( $this, 'process_meta_box' ) );
        add_action( 'wp_ajax_woocommerce_events_csv', array( $this, 'woocommerce_events_csv' ) );
        add_action( 'wp_ajax_nopriv_woocommerce_events_csv', array( $this, 'woocommerce_events_csv' ) );
        add_action( 'wp_ajax_nopriv_woocommerce_events_csv', array( $this, 'woocommerce_events_csv' ) );
        add_action('woocommerce_thankyou_order_received_text', array( $this, 'display_thank_you_text' ));
        add_action('woocommerce_order_status_cancelled', array($this, 'order_status_cancelled'));
        add_action('woocommerce_order_status_completed', array(&$this, 'order_status_completed_cancelled'), 10, 1);
        
        add_filter( 'woocommerce_events_meta_format', 'wptexturize');
        add_filter( 'woocommerce_events_meta_format', 'convert_smilies');
        add_filter( 'woocommerce_events_meta_format', 'convert_chars');
        add_filter( 'woocommerce_events_meta_format', 'wpautop');
        add_filter( 'woocommerce_events_meta_format', 'shortcode_unautop');
        add_filter( 'woocommerce_events_meta_format', 'prepend_attachment');

    }

    /**
     * Checks if the WooCommerce plugin exists
     * 
     */
    public function check_woocommerce_exists() {

        if ( !class_exists( 'WooCommerce' ) ) {

                $this->output_notices(array(__( 'WooCommerce is required for FooEvents. Please install and activate the latest version of WooCommerce.', 'woocommerce-events' )));

        } 

    }

    /**
     * Initializes the WooCommerce meta box
     * 
     */
    public function add_product_options_tab() {

        echo '<li class="custom_tab_fooevents"><a href="#woocommerce_events_data">'.__( ' Event', 'woocommerce-events' ).'</a></li>';

    }


    /**
     * Displays the event form 
     * 
     * @param object $post
     */
    public function add_product_options_tab_options() {

        global $post;

        $WooCommerceEventsEvent                     = get_post_meta($post->ID, 'WooCommerceEventsEvent', true);
        $WooCommerceEventsDate                      = get_post_meta($post->ID, 'WooCommerceEventsDate', true);
        $WooCommerceEventsHour                      = get_post_meta($post->ID, 'WooCommerceEventsHour', true);
        $WooCommerceEventsPeriod                    = get_post_meta($post->ID, 'WooCommerceEventsPeriod', true);
        $WooCommerceEventsMinutes                   = get_post_meta($post->ID, 'WooCommerceEventsMinutes', true);
        $WooCommerceEventsHourEnd                   = get_post_meta($post->ID, 'WooCommerceEventsHourEnd', true);
        $WooCommerceEventsMinutesEnd                = get_post_meta($post->ID, 'WooCommerceEventsMinutesEnd', true);
        $WooCommerceEventsEndPeriod                 = get_post_meta($post->ID, 'WooCommerceEventsEndPeriod', true);
        $WooCommerceEventsLocation                  = get_post_meta($post->ID, 'WooCommerceEventsLocation', true);
        $WooCommerceEventsTicketLogo                = get_post_meta($post->ID, 'WooCommerceEventsTicketLogo', true);
        $WooCommerceEventsSupportContact            = get_post_meta($post->ID, 'WooCommerceEventsSupportContact', true);
        $WooCommerceEventsGPS                       = get_post_meta($post->ID, 'WooCommerceEventsGPS', true);
        $WooCommerceEventsGoogleMaps                = get_post_meta($post->ID, 'WooCommerceEventsGoogleMaps', true);
        $WooCommerceEventsDirections                = get_post_meta($post->ID, 'WooCommerceEventsDirections', true);
        $WooCommerceEventsEmail                     = get_post_meta($post->ID, 'WooCommerceEventsEmail', true);
        $WooCommerceEventsTicketBackgroundColor     = get_post_meta($post->ID, 'WooCommerceEventsTicketBackgroundColor', true);
        $WooCommerceEventsTicketButtonColor         = get_post_meta($post->ID, 'WooCommerceEventsTicketButtonColor', true);
        $WooCommerceEventsTicketTextColor           = get_post_meta($post->ID, 'WooCommerceEventsTicketTextColor', true);
        $WooCommerceEventsTicketPurchaserDetails    = get_post_meta($post->ID, 'WooCommerceEventsTicketPurchaserDetails', true);
        $WooCommerceEventsTicketAddCalendar         = get_post_meta($post->ID, 'WooCommerceEventsTicketAddCalendar', true);
        $WooCommerceEventsTicketDisplayDateTime     = get_post_meta($post->ID, 'WooCommerceEventsTicketDisplayDateTime', true);
        $WooCommerceEventsTicketDisplayBarcode      = get_post_meta($post->ID, 'WooCommerceEventsTicketDisplayBarcode', true);
        $WooCommerceEventsTicketDisplayPrice            = get_post_meta($post->ID, 'WooCommerceEventsTicketDisplayPrice', true);
        $WooCommerceEventsTicketText                    = get_post_meta($post->ID, 'WooCommerceEventsTicketText', true);
        $WooCommerceEventsThankYouText                  = get_post_meta($post->ID, 'WooCommerceEventsThankYouText', true);
        $WooCommerceEventsCaptureAttendeeDetails        = get_post_meta($post->ID, 'WooCommerceEventsCaptureAttendeeDetails', true);
        $WooCommerceEventsSendEmailTickets              = get_post_meta($post->ID, 'WooCommerceEventsSendEmailTickets', true);
        $WooCommerceEventsCaptureAttendeeTelephone      = get_post_meta($post->ID, 'WooCommerceEventsCaptureAttendeeTelephone', true);
        $WooCommerceEventsCaptureAttendeeCompany        = get_post_meta($post->ID, 'WooCommerceEventsCaptureAttendeeCompany', true);
        $WooCommerceEventsCaptureAttendeeDesignation    = get_post_meta($post->ID, 'WooCommerceEventsCaptureAttendeeDesignation', true);

        $WooCommerceEventsExportUnpaidTickets           = get_post_meta($post->ID, 'WooCommerceEventsExportUnpaidTickets', true);
        $WooCommerceEventsExportBillingDetails        = get_post_meta($post->ID, 'WooCommerceEventsExportBillingDetails', true);

        $WooCommerceEventsEmailSubjectSingle            = get_post_meta($post->ID, 'WooCommerceEventsEmailSubjectSingle', true);

        if(empty($WooCommerceEventsEmailSubjectSingle)) {

            $WooCommerceEventsEmailSubjectSingle = __('{OrderNumber} Ticket', 'woocommerce-events');

        }

        $globalWooCommerceEventsTicketBackgroundColor   = get_option('globalWooCommerceEventsTicketBackgroundColor', true);
        $globalWooCommerceEventsTicketButtonColor       = get_option('globalWooCommerceEventsTicketButtonColor', true);
        $globalWooCommerceEventsTicketTextColor         = get_option('globalWooCommerceEventsTicketTextColor', true);
        $globalWooCommerceEventsTicketLogo              = get_option('globalWooCommerceEventsTicketLogo', true);
        
        $endDate = '';
        $numDays = '';
        if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
            require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
        }
        
        if ($this->is_plugin_active('fooevents_multi_day/fooevents-multi-day.php') || is_plugin_active_for_network('fooevents_multi_day/fooevents-multi-day.php')) {

            $Fooevents_Multiday_Events = new Fooevents_Multiday_Events();
            $endDate = $Fooevents_Multiday_Events->generate_end_date_option($post);
            $numDays = $Fooevents_Multiday_Events->generate_num_days_option($post);

        }
        
        require($this->Config->templatePath.'eventmetaoptions.php');

    }

    /**
     * Processes the meta box form once the plubish / update button is clicked.
     * 
     * @global object $woocommerce_errors
     * @param int $post_id
     * @param object $post
     */
    public function process_meta_box($post_id) {

        global $woocommerce_errors;
        global $wp_locale;

        if(isset($_POST['WooCommerceEventsEvent'])) {

            update_post_meta($post_id, 'WooCommerceEventsEvent', $_POST['WooCommerceEventsEvent']);

        }

        if(isset($_POST['WooCommerceEventsDate'])) {

            update_post_meta($post_id, 'WooCommerceEventsDate', $_POST['WooCommerceEventsDate']);

            $format = get_option( 'date_format' );
            $dtime = DateTime::createFromFormat($format, $_POST['WooCommerceEventsDate']);

            $timestamp = '';
            if ($timestamp instanceof DateTime) {

                $timestamp = $dtime->getTimestamp();

            } else {

                $timestamp = 0;

            }

            update_post_meta($post_id, 'WooCommerceEventsDateTimestamp', $timestamp);

        }
        
        if(isset($_POST['WooCommerceEventsEndDate'])) {

            update_post_meta($post_id, 'WooCommerceEventsEndDate', $_POST['WooCommerceEventsEndDate']);

            $format = get_option( 'date_format' );
            $dtime = DateTime::createFromFormat($format, $_POST['WooCommerceEventsEndDate']);

            $timestamp = '';
            if ($var instanceof DateTime) {

                $timestamp = $dtime->getTimestamp();

            } else {

                $timestamp = 0;

            }

            update_post_meta($post_id, 'WooCommerceEventsEndDateTimestamp', $timestamp);

        }
        
        if(isset($_POST['WooCommerceEventsNumDays'])) {
            
            update_post_meta($post_id, 'WooCommerceEventsNumDays', $_POST['WooCommerceEventsNumDays']);
            
        }
        
        if(isset($_POST['WooCommerceEventsHour'])) {

            update_post_meta($post_id, 'WooCommerceEventsHour', $_POST['WooCommerceEventsHour']);

        }

        if(isset($_POST['WooCommerceEventsMinutes'])) {

            update_post_meta($post_id, 'WooCommerceEventsMinutes', $_POST['WooCommerceEventsMinutes']);

        }

        if(isset($_POST['WooCommerceEventsPeriod'])) {

            update_post_meta($post_id, 'WooCommerceEventsPeriod', $_POST['WooCommerceEventsPeriod']);

        }

        if(isset($_POST['WooCommerceEventsLocation'])) {

            $WooCommerceEventsLocation = htmlentities(stripslashes($_POST['WooCommerceEventsLocation']));

            update_post_meta($post_id, 'WooCommerceEventsLocation', $WooCommerceEventsLocation);

        }

        if(isset($_POST['WooCommerceEventsTicketLogo'])) {

            update_post_meta($post_id, 'WooCommerceEventsTicketLogo', $_POST['WooCommerceEventsTicketLogo']);

        }

        if(isset($_POST['WooCommerceEventsTicketText'])) {

            update_post_meta($post_id, 'WooCommerceEventsTicketText', $_POST['WooCommerceEventsTicketText']);

        }

        if(isset($_POST['WooCommerceEventsThankYouText'])) {

            update_post_meta($post_id, 'WooCommerceEventsThankYouText', $_POST['WooCommerceEventsThankYouText']);

        }

        if(isset($_POST['WooCommerceEventsSupportContact'])) {

            $WooCommerceEventsSupportContact = htmlentities(stripslashes($_POST['WooCommerceEventsSupportContact']));

            update_post_meta($post_id, 'WooCommerceEventsSupportContact', $WooCommerceEventsSupportContact);

        }

        if(isset($_POST['WooCommerceEventsHourEnd'])) {

            update_post_meta($post_id, 'WooCommerceEventsHourEnd', $_POST['WooCommerceEventsHourEnd']);

        }

        if(isset($_POST['WooCommerceEventsMinutesEnd'])) {

            update_post_meta($post_id, 'WooCommerceEventsMinutesEnd', $_POST['WooCommerceEventsMinutesEnd']);

        }

        if(isset($_POST['WooCommerceEventsEndPeriod'])) {

            update_post_meta($post_id, 'WooCommerceEventsEndPeriod', $_POST['WooCommerceEventsEndPeriod']);

        }

        if(isset($_POST['WooCommerceEventsGPS'])) {

            $WooCommerceEventsGPS = htmlentities(stripslashes($_POST['WooCommerceEventsGPS']));

            update_post_meta($post_id, 'WooCommerceEventsGPS',  htmlentities(stripslashes($_POST['WooCommerceEventsGPS'])));

        }

        if(isset($_POST['WooCommerceEventsDirections'])) {

            $WooCommerceEventsDirections = htmlentities(stripslashes($_POST['WooCommerceEventsDirections']));

            update_post_meta($post_id, 'WooCommerceEventsDirections', $WooCommerceEventsDirections);

        }

        if(isset($_POST['WooCommerceEventsEmail'])) {

            $WooCommerceEventsEmail = esc_textarea($_POST['WooCommerceEventsEmail']);

            update_post_meta($post_id, 'WooCommerceEventsEmail', $WooCommerceEventsEmail);

        }

        if(isset($_POST['WooCommerceEventsTicketBackgroundColor'])) {

            update_post_meta($post_id, 'WooCommerceEventsTicketBackgroundColor', $_POST['WooCommerceEventsTicketBackgroundColor']);

        }

        if(isset($_POST['WooCommerceEventsTicketButtonColor'])) {

            update_post_meta($post_id, 'WooCommerceEventsTicketButtonColor', $_POST['WooCommerceEventsTicketButtonColor']);

        }

        if(isset($_POST['WooCommerceEventsTicketTextColor'])) {

            update_post_meta($post_id, 'WooCommerceEventsTicketTextColor', $_POST['WooCommerceEventsTicketTextColor']);

        }

        if(isset($_POST['WooCommerceEventsGoogleMaps'])) {

            update_post_meta($post_id, 'WooCommerceEventsGoogleMaps', $_POST['WooCommerceEventsGoogleMaps']);

        }

        if(isset($_POST['WooCommerceEventsTicketPurchaserDetails'])) {

            update_post_meta($post_id, 'WooCommerceEventsTicketPurchaserDetails', $_POST['WooCommerceEventsTicketPurchaserDetails']);

        } else {

            update_post_meta($post_id, 'WooCommerceEventsTicketPurchaserDetails', 'off');

        }

        if(isset($_POST['WooCommerceEventsTicketAddCalendar'])) {

            update_post_meta($post_id, 'WooCommerceEventsTicketAddCalendar', $_POST['WooCommerceEventsTicketAddCalendar']);

        } else {

            update_post_meta($post_id, 'WooCommerceEventsTicketAddCalendar', 'off');

        }

        if(isset($_POST['WooCommerceEventsTicketDisplayDateTime'])) {

            update_post_meta($post_id, 'WooCommerceEventsTicketDisplayDateTime', $_POST['WooCommerceEventsTicketDisplayDateTime']);

        } else {

            update_post_meta($post_id, 'WooCommerceEventsTicketDisplayDateTime', 'off');

        }

        if(isset($_POST['WooCommerceEventsTicketDisplayBarcode'])) {

            update_post_meta($post_id, 'WooCommerceEventsTicketDisplayBarcode', $_POST['WooCommerceEventsTicketDisplayBarcode']);

        } else {

            update_post_meta($post_id, 'WooCommerceEventsTicketDisplayBarcode', 'off');

        }

        if(isset($_POST['WooCommerceEventsTicketDisplayPrice'])) {

            update_post_meta($post_id, 'WooCommerceEventsTicketDisplayPrice', $_POST['WooCommerceEventsTicketDisplayPrice']);

        } else {

            update_post_meta($post_id, 'WooCommerceEventsTicketDisplayPrice', 'off');

        }

        if(isset($_POST['WooCommerceEventsCaptureAttendeeDetails'])) {

            update_post_meta($post_id, 'WooCommerceEventsCaptureAttendeeDetails', $_POST['WooCommerceEventsCaptureAttendeeDetails']);

        } else {

            update_post_meta($post_id, 'WooCommerceEventsCaptureAttendeeDetails', 'off');

        }

        if(isset($_POST['WooCommerceEventsCaptureAttendeeTelephone'])) {

            update_post_meta($post_id, 'WooCommerceEventsCaptureAttendeeTelephone', $_POST['WooCommerceEventsCaptureAttendeeTelephone']);

        } else {

            update_post_meta($post_id, 'WooCommerceEventsCaptureAttendeeTelephone', 'off');

        }

        if(isset($_POST['WooCommerceEventsCaptureAttendeeCompany'])) {

            update_post_meta($post_id, 'WooCommerceEventsCaptureAttendeeCompany', $_POST['WooCommerceEventsCaptureAttendeeCompany']);

        } else {

            update_post_meta($post_id, 'WooCommerceEventsCaptureAttendeeCompany', 'off');

        }

        if(isset($_POST['WooCommerceEventsCaptureAttendeeDesignation'])) {

            update_post_meta($post_id, 'WooCommerceEventsCaptureAttendeeDesignation', $_POST['WooCommerceEventsCaptureAttendeeDesignation']);

        } else {

            update_post_meta($post_id, 'WooCommerceEventsCaptureAttendeeDesignation', 'off');

        }

        if(isset($_POST['WooCommerceEventsSendEmailTickets'])) {

            update_post_meta($post_id, 'WooCommerceEventsSendEmailTickets', $_POST['WooCommerceEventsSendEmailTickets']);

        } else {

            update_post_meta($post_id, 'WooCommerceEventsSendEmailTickets', 'off');

        }

        if(isset($_POST['WooCommerceEventsEmailSubjectSingle'])) {

            update_post_meta($post_id, 'WooCommerceEventsEmailSubjectSingle', $_POST['WooCommerceEventsEmailSubjectSingle']);

        } else {

            update_post_meta($post_id, 'WooCommerceEventsEmailSubjectSingle', '{OrderNumber} Ticket');

        }

        if(isset($_POST['WooCommerceEventsExportUnpaidTickets'])) {

            update_post_meta($post_id, 'WooCommerceEventsExportUnpaidTickets', $_POST['WooCommerceEventsExportUnpaidTickets']);

        } else {

            update_post_meta($post_id, 'WooCommerceEventsExportUnpaidTickets', 'off');

        }

        if(isset($_POST['WooCommerceEventsExportBillingDetails'])) {

            update_post_meta($post_id, 'WooCommerceEventsExportBillingDetails', $_POST['WooCommerceEventsExportBillingDetails']);

        } else {

            update_post_meta($post_id, 'WooCommerceEventsExportBillingDetails', 'off');

        }

    }

    /**
     * Displays the event details on the front end template. Before WooCommerce Displays content.
     * 
     * @param array $tabs
     * @global object $post
     * @return array $tabs
     */
    public function add_front_end_tab($tabs) {

        global $post;

        $WooCommerceEventsEvent = get_post_meta($post->ID, 'WooCommerceEventsEvent', true);

        $WooCommerceEventsGoogleMaps = get_post_meta($post->ID, 'WooCommerceEventsGoogleMaps', true);

        $globalWooCommerceHideEventDetailsTab   = get_option('globalWooCommerceHideEventDetailsTab', true);

        if($WooCommerceEventsEvent == 'Event') {

            if($globalWooCommerceHideEventDetailsTab !== 'yes') {

                $tabs['woocommerce_events'] = array(
                    'title'     => __('Event Details', 'woocommerce-events'),
                    'priority'  => 30,
                    'callback'  => 'fooevents_displayEventTab'
                );

            }

            if(!empty($WooCommerceEventsGoogleMaps)) {

                $tabs['description'] = array(
                    'title'     => __('Description', 'woocommerce-events'),
                    'priority' => 1,
                    'callback'  => 'fooevents_displayEventTabMap'
                );

            }

        }
        return $tabs;

    }

public function create_tickets($order_id) {
    
    $WooCommerceEventsOrderTickets = get_post_meta($order_id, 'WooCommerceEventsOrderTickets', true);
    
    $x = 1;
    foreach($WooCommerceEventsOrderTickets as $event => $tickets) {
        
        $y = 1;
        foreach($tickets as $ticket) {
        
            $rand = rand(111111,999999);

            $post = array(

                    'post_author' => $ticket['WooCommerceEventsCustomerID'],
                    'post_content' => "Ticket",
                    'post_status' => "publish",
                    'post_title' => 'Assigned Ticket',
                    'post_type' => "event_magic_tickets"

            );

            $post['ID'] = wp_insert_post( $post );
            $ticketID = $post['ID'].$rand;
            $post['post_title'] = '#'.$ticketID;
            $postID = wp_update_post( $post );

            update_post_meta($postID, 'WooCommerceEventsTicketID', $ticketID);
            update_post_meta($postID, 'WooCommerceEventsProductID', $ticket['WooCommerceEventsProductID']);
            update_post_meta($postID, 'WooCommerceEventsOrderID', $ticket['WooCommerceEventsOrderID']);
            update_post_meta($postID, 'WooCommerceEventsTicketType', $ticket['WooCommerceEventsTicketType']);
            update_post_meta($postID, 'WooCommerceEventsStatus', 'Unpaid');
            update_post_meta($postID, 'WooCommerceEventsCustomerID', $ticket['WooCommerceEventsCustomerID']);
            update_post_meta($postID, 'WooCommerceEventsAttendeeName', $ticket['WooCommerceEventsAttendeeName']);
            update_post_meta($postID, 'WooCommerceEventsAttendeeLastName', $ticket['WooCommerceEventsAttendeeLastName']);
            update_post_meta($postID, 'WooCommerceEventsAttendeeEmail', $ticket['WooCommerceEventsAttendeeEmail']);
            update_post_meta($postID, 'WooCommerceEventsAttendeeTelephone', $ticket['WooCommerceEventsAttendeeTelephone']);
            update_post_meta($postID, 'WooCommerceEventsAttendeeCompany', $ticket['WooCommerceEventsAttendeeCompany']);
            update_post_meta($postID, 'WooCommerceEventsAttendeeDesignation', $ticket['WooCommerceEventsAttendeeDesignation']);
            update_post_meta($postID, 'WooCommerceEventsVariations', $ticket['WooCommerceEventsVariations']);
            update_post_meta($postID, 'WooCommerceEventsVariationID', $ticket['WooCommerceEventsVariationID']);

            update_post_meta($postID, 'WooCommerceEventsPurchaserFirstName', $ticket['WooCommerceEventsPurchaserFirstName']);
            update_post_meta($postID, 'WooCommerceEventsPurchaserLastName', $ticket['WooCommerceEventsPurchaserLastName']);
            update_post_meta($postID, 'WooCommerceEventsPurchaserEmail', $ticket['WooCommerceEventsPurchaserEmail']);

            if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
                    require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
            }

            if ( $this->is_plugin_active( 'fooevents_custom_attendee_fields/fooevents-custom-attendee-fields.php' ) || is_plugin_active_for_network('fooevents_custom_attendee_fields/fooevents-custom-attendee-fields.php') ) {

                $Fooevents_Custom_Attendee_Fields = new Fooevents_Custom_Attendee_Fields();
                $WooCommerceEventsCustomAttendeeFields = $Fooevents_Custom_Attendee_Fields->process_capture_custom_attendee_options($postID, $ticket['WooCommerceEventsCustomAttendeeFields']);

            }

            $product = get_post($ticket['WooCommerceEventsProductID']);

            update_post_meta($postID, 'WooCommerceEventsProductName', $product->post_title);

            $y++;
            
        }
        
        $x++;
        
    }

}    
    
/**
 * Sends a ticket email once an order is completed.
 * 
 * @param int $order_id
 * @global $woocommerce, $evotx;
 */
 public function send_ticket_email($order_id) {

        /*error_reporting(E_ALL);
        ini_set('display_errors', '1');*/
        
        $this->create_tickets($order_id);
     
        set_time_limit(0);

        global $woocommerce;

        $order = new WC_Order( $order_id );
        $tickets = $order->get_items();

        $WooCommerceEventsTicketsPurchased = get_post_meta($order_id, 'WooCommerceEventsTicketsPurchased', true);
        
        $customer = get_post_meta($order_id, '_customer_user', true);
        $usermeta = get_user_meta($customer);

        $WooCommerceEventsSentTicket        =  get_post_meta($order_id, 'WooCommerceEventsSentTicket', true);


        $customerDetails = array(
                    'customerID'        => $customer
        );

        $customerDetails['customerFirstName']   = $order->billing_first_name;
        $customerDetails['customerLastName']    = $order->billing_last_name;
        $customerDetails['customerEmail']       = $order->billing_email;


        $tickets = new WP_Query( array('post_type' => array('event_magic_tickets'), 'posts_per_page' => -1, 'meta_query' => array( array( 'key' => 'WooCommerceEventsOrderID', 'value' => $order_id ) )) );
        $tickets = $tickets->get_posts();


        $body = '';
        $header = $this->MailHelper->parse_email_template('header.php', $customerDetails); 
        $footer = $this->MailHelper->parse_email_template('footer.php', $customerDetails); 
        $ticketBody = '';

        $globalWooCommerceEventsEmailAttendees = get_option('globalWooCommerceEventsEmailAttendees', true);

        $alltickets = array();

        $x = 1;
        foreach ($tickets as $ticketItem) {

            $ticket = $this->TicketHelper->get_ticket_data($ticketItem->ID);
            $WooCommerceEventsProductID = get_post_meta($ticketItem->ID, 'WooCommerceEventsProductID', true);
            $WooCommerceEventsStatus = get_post_meta($ticketItem->ID, 'WooCommerceEventsStatus', true);

            $WooCommerceEventsEvent = get_post_meta($WooCommerceEventsProductID, 'WooCommerceEventsEvent', true);
            $WooCommerceEventsSendEmailTickets = get_post_meta($WooCommerceEventsProductID, 'WooCommerceEventsSendEmailTickets', true);

            $WooCommerceEventsEmailSubjectSingle = get_post_meta($WooCommerceEventsProductID, 'WooCommerceEventsEmailSubjectSingle', true);
            if(empty($WooCommerceEventsEmailSubjectSingle)) {

                $WooCommerceEventsEmailSubjectSingle  = __('{OrderNumber} Ticket', 'woocommerce-events');

            }

            if($WooCommerceEventsTicketsPurchased[$WooCommerceEventsProductID] > 0) {

                if($WooCommerceEventsEvent == 'Event') {

                    if($WooCommerceEventsStatus == 'Unpaid') {

                        update_post_meta($ticketItem->ID, 'WooCommerceEventsStatus', 'Not Checked In');

                    }

                    $WooCommerceEventsAttendeeEmail = get_post_meta($ticketItem->ID, 'WooCommerceEventsAttendeeEmail', true);

                    //generate barcode
                    if (!file_exists($this->Config->barcodePath.$ticket['WooCommerceEventsTicketID'].'.png')) {

                        $this->BarcodeHelper->generate_barcode($ticket['WooCommerceEventsTicketID']);

                    }

                    if($WooCommerceEventsSentTicket != 'Yes' && $globalWooCommerceEventsEmailAttendees === 'yes') {

                        //email attendee
                        $ticketBody = $this->MailHelper->parse_ticket_template($ticket);

                        if(!empty($ticketBody)) {

                            $subject = str_replace('{OrderNumber}', '[#'.$order_id.']', $WooCommerceEventsEmailSubjectSingle);

                            $body       = $header.$ticketBody.$footer;
                            $from       = get_option( 'woocommerce_email_from_name' ).' <'.sanitize_email( get_option( 'woocommerce_email_from_address' ) ).'>';
                            $to         = $WooCommerceEventsAttendeeEmail;

                            $headers  = 'Content-type: text/html;charset=utf-8' . "\r\n";
                            $headers .= 'From: '.$from;
                            $attachment = '';

                            if(!empty($ticketBody) && $WooCommerceEventsSendEmailTickets != 'off') {

                                if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
                                    require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
                                }

                                if ( $this->is_plugin_active('fooevents_pdf_tickets/fooevents-pdf-tickets.php') || is_plugin_active_for_network('fooevents_pdf_tickets/fooevents-pdf-tickets.php')) {

                                    $globalFooEventsPDFTicketsEnable = get_option( 'globalFooEventsPDFTicketsEnable' );

                                    if($globalFooEventsPDFTicketsEnable == 'yes') {

                                        $FooEvents_PDF_Tickets = new FooEvents_PDF_Tickets();
                                        $attachment = $FooEvents_PDF_Tickets->generate_ticket(array($ticket), $this->Config->path, $this->Config->path);
                                        $FooEventsPDFTicketsEmailText = get_post_meta($WooCommerceEventsProductID, 'FooEventsPDFTicketsEmailText', true);

                                        $header = $FooEvents_PDF_Tickets->parse_email_template('email-header.php');
                                        $footer = $FooEvents_PDF_Tickets->parse_email_template('email-footer.php');

                                        $body = $header.$FooEventsPDFTicketsEmailText.$footer;

                                        if(empty($body)) {

                                            $body = __('Your tickets are attached. Please print them and bring them to the event.', 'fooevents-pdf-tickets');

                                        }

                                    }

                                }

                                $mailStatus = $this->MailHelper->send_ticket($to, $subject, $body, $headers, $attachment);

                                if(!$mailStatus) {

                                    $mailStatus = $this->MailHelper->send_ticket($to, $subject, $body, $headers, $attachment);

                                }

                            }

                        }

                    } else {

                        //email tickets to purchaser later
                        $ticketBody .= $this->MailHelper->parse_ticket_template($ticket);

                    }

                    $WooCommerceEventsTicketsPurchased[$WooCommerceEventsProductID] = $WooCommerceEventsTicketsPurchased[$WooCommerceEventsProductID] - 1;

                }

            }

            $alltickets[] = $ticket;
            unset($ticket);

            $x++;
        }

        if($WooCommerceEventsSentTicket != 'Yes' && $globalWooCommerceEventsEmailAttendees !== 'yes') {

            //email purchaser

            $subject = str_replace('{OrderNumber}', '[#'.$order_id.']', $WooCommerceEventsEmailSubjectSingle);

            $body       = $header.$ticketBody.$footer;
            $from       = get_option( 'woocommerce_email_from_name' ).' <'.sanitize_email( get_option( 'woocommerce_email_from_address' ) ).'>';
            $to         = $customerDetails['customerEmail'];

            $headers  = 'Content-type: text/html;charset=utf-8' . "\r\n";
            $headers .= 'From: '.$from;

            $attachment = '';
            if(!empty($ticketBody) && $WooCommerceEventsSendEmailTickets != 'off') {

                if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
                    require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
                }

                if ( $this->is_plugin_active('fooevents_pdf_tickets/fooevents-pdf-tickets.php') || is_plugin_active_for_network('fooevents_pdf_tickets/fooevents-pdf-tickets.php')) {

                    $globalFooEventsPDFTicketsEnable = get_option( 'globalFooEventsPDFTicketsEnable' );
                    $globalFooEventsPDFTicketsLayout = get_option( 'globalFooEventsPDFTicketsLayout' );

                    if(empty($globalFooEventsPDFTicketsLayout)) {

                        $globalFooEventsPDFTicketsLayout = 'single';

                    }

                    if($globalFooEventsPDFTicketsEnable == 'yes') {

                        $FooEvents_PDF_Tickets = new FooEvents_PDF_Tickets();

                        if($globalFooEventsPDFTicketsLayout == 'single') {

                            $attachment = $FooEvents_PDF_Tickets->generate_ticket($alltickets, $this->Config->path, $this->Config->path);

                        } else {

                            $attachment = $FooEvents_PDF_Tickets->generate_multiple_ticket($alltickets, $this->Config->path, $this->Config->path);

                        }

                        $FooEventsPDFTicketsEmailText = get_post_meta($WooCommerceEventsProductID, 'FooEventsPDFTicketsEmailText', true);

                        $header = $FooEvents_PDF_Tickets->parse_email_template('email-header.php');
                        $footer = $FooEvents_PDF_Tickets->parse_email_template('email-footer.php');
                        $body = $header.$FooEventsPDFTicketsEmailText.$footer;

                        if(empty($body)) {

                            $body = __('Your tickets are attached. Please print them and bring them to the event.', 'fooevents-pdf-tickets');

                        }

                    }

                }

                $mailStatus = $this->MailHelper->send_ticket($to, $subject, $body, $headers, $attachment);

                if(!$mailStatus) { 

                    $mailStatus = $this->MailHelper->send_ticket($to, $subject, $body, $headers, $attachment);

                }

            }

        }

        update_post_meta($order_id, 'WooCommerceEventsSentTicket', 'Yes');

    }

    /**
     * Displays thank you text on order completion page.
     * 
     * @param type $thankYouText
     * @return type
     */
    public function display_thank_you_text($thankYouText) {

        /*error_reporting(0);
        ini_set('display_errors', 0);*/

        global $woocommerce;
        global $post;

        //$paged = get_query_var();

        $actualLink = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $segments = array_reverse(explode('/', $actualLink));

        $orderID = $segments[1];
        $order = new WC_Order($orderID);
        $items = $order->get_items();

        $products = array();

        foreach($items as $item) {

            $products[$item['product_id']] = $item['product_id'];

        }

        foreach($products as $key => $productID) {

            $WooCommerceEventsThankYouText = get_post_meta($productID, 'WooCommerceEventsThankYouText', true);

            if(!empty($WooCommerceEventsThankYouText)) {

                echo $WooCommerceEventsThankYouText."<br/><br/>";

            }

        }

        return $thankYouText;

    }

    /**
     * Cancels ticket when order is canceled.
     * 
     * @param int $order_id
     */
    public function order_status_cancelled($order_id) {

        $tickets = new WP_Query( array('post_type' => array('event_magic_tickets'), 'posts_per_page' => -1, 'meta_query' => array( array( 'key' => 'WooCommerceEventsOrderID', 'value' => $order_id ) )) );
        $tickets = $tickets->get_posts();

        foreach ($tickets as $ticket) {

            update_post_meta($ticket->ID, 'WooCommerceEventsStatus', 'Canceled');

        }

    }

    public function order_status_completed_cancelled($order_id) {

        $tickets = new WP_Query( array('post_type' => array('event_magic_tickets'), 'posts_per_page' => -1, 'meta_query' => array( array( 'key' => 'WooCommerceEventsOrderID', 'value' => $order_id ) )) );
        $tickets = $tickets->get_posts();

        foreach ($tickets as $ticket) {

            $ticketStatus = get_post_meta($ticket->ID, 'WooCommerceEventsStatus', true);

            if($ticketStatus == 'Canceled') {

                update_post_meta($ticket->ID, 'WooCommerceEventsStatus', 'Not Checked In');

            }

        }

    }

    /**
     * Generates attendee CSV export.
     * 
     */
    public function woocommerce_events_csv() {

        /*error_reporting(E_ALL);
        ini_set('display_errors', '1');*/

        error_reporting(0);
        ini_set('display_errors', 0);

        global $woocommerce;

        $event = $_GET['event'];
        $includeUnpaidTickets = $_GET['exportunpaidtickets'];
        $exportbillingdetails = $_GET['exportbillingdetails'];
        
        if($includeUnpaidTickets) {

            $statuses = array('wc-processing', 'wc-on-hold' );
            $order_ids = $this->get_orders_ids_by_product_id( $event, $statuses );

            $x = 0;
            $unpaidTickets = array();
            foreach($order_ids as $order_id) {
                
                $unpaid_order = '';
                try {
                    
                    $unpaid_order = new WC_Order($order_id);
                    
                } catch (Exception $e) {

                } 

                $WooCommerceEventsOrderTickets = get_post_meta($order_id, 'WooCommerceEventsOrderTickets', true);

                foreach ($WooCommerceEventsOrderTickets as $order => $unpaidOrderTickets) {
                    
                    foreach($unpaidOrderTickets as $unpaidOrderTicket) {
   
                        $UnpaidWooCommerceEventsAttendeeName = '';
                        if(empty($unpaidOrderTicket['WooCommerceEventsAttendeeName'])) {
                            
                            $UnpaidWooCommerceEventsAttendeeName = $unpaidOrderTicket['WooCommerceEventsPurchaserFirstName'];
                            
                        } else {
                            
                            $UnpaidWooCommerceEventsAttendeeName = $unpaidOrderTicket['WooCommerceEventsAttendeeName'];
                            
                        }
                        
                        $UnpaidWooCommerceEventsAttendeeLastName = '';
                        if(empty($unpaidOrderTicket['WooCommerceEventsAttendeeLastName'])) {
                            
                            $UnpaidWooCommerceEventsAttendeeLastName = $unpaidOrderTicket['WooCommerceEventsPurchaserLastName'];
                            
                        } else {
                            
                            $UnpaidWooCommerceEventsAttendeeLastName = $unpaidOrderTicket['WooCommerceEventsAttendeeLastName'];
                            
                        }
                        
                        $UnpaidWooCommerceEventsAttendeeEmail = '';
                        if(empty($unpaidOrderTicket['WooCommerceEventsAttendeeEmail'])) {
                            
                            $UnpaidWooCommerceEventsAttendeeEmail = $unpaidOrderTicket['WooCommerceEventsPurchaserEmail'];
                            
                        } else {
                            
                            $UnpaidWooCommerceEventsAttendeeEmail = $unpaidOrderTicket['WooCommerceEventsAttendeeEmail'];
                            
                        }
                        
                        
                        $unpaidTickets[$x][0] = 'NA';
                        $unpaidTickets[$x][1] = $unpaidOrderTicket['WooCommerceEventsOrderID'];
                        $unpaidTickets[$x][2] = $UnpaidWooCommerceEventsAttendeeName;
                        $unpaidTickets[$x][3] = $UnpaidWooCommerceEventsAttendeeLastName;
                        $unpaidTickets[$x][4] = $UnpaidWooCommerceEventsAttendeeEmail;
                        $unpaidTickets[$x][5] = $unpaidOrderTicket['WooCommerceEventsStatus'];
                        $unpaidTickets[$x][6] = $unpaidOrderTicket['WooCommerceEventsTicketType'];
                        $unpaidTickets[$x][7] = $unpaidOrderTicket['WooCommerceEventsVariations'];
                        $unpaidTickets[$x][8] = $unpaidOrderTicket['WooCommerceEventsAttendeeTelephone'];
                        $unpaidTickets[$x][9] = $unpaidOrderTicket['WooCommerceEventsAttendeeCompany'];
                        $unpaidTickets[$x][10] = $unpaidOrderTicket['WooCommerceEventsAttendeeDesignation'];
                        $unpaidTickets[$x][11] = $unpaidOrderTicket['WooCommerceEventsPurchaserFirstName'];
                        $unpaidTickets[$x][12] = $unpaidOrderTicket['WooCommerceEventsPurchaserLastName'];
                        $unpaidTickets[$x][13] = $unpaidOrderTicket['WooCommerceEventsPurchaserEmail'];
                        $unpaidTickets[$x][14] = $unpaid_order->billing_company;
                        
                        if(!empty($exportbillingdetails)) {
                            
                            $unpaidTickets[$x][15] = $unpaid_order->get_billing_address_1();
                            $unpaidTickets[$x][16] = $unpaid_order->get_billing_address_2();
                            $unpaidTickets[$x][17] = $unpaid_order->get_billing_city();
                            $unpaidTickets[$x][18] = $unpaid_order->get_billing_postcode();
                            $unpaidTickets[$x][19] = $unpaid_order->get_billing_country();
                            $unpaidTickets[$x][20] = $unpaid_order->get_billing_state();
                            $unpaidTickets[$x][21] = $unpaid_order->get_billing_phone();
                            
                        }
                        
                        if ( ! function_exists( 'is_plugin_active_for_network' ) ) {

                            require_once( ABSPATH . '/wp-admin/includes/plugin.php' );

                        }

                        if ($this->is_plugin_active( 'fooevents_custom_attendee_fields/fooevents-custom-attendee-fields.php') || is_plugin_active_for_network('fooevents_custom_attendee_fields/fooevents-custom-attendee-fields.php')) {
                            
                            $y = 15;
                            if(!empty($unpaidOrderTicket['WooCommerceEventsCustomAttendeeFields'])) {
                                
                                foreach($unpaidOrderTicket['WooCommerceEventsCustomAttendeeFields'] as $unpaidCustomField => $unpaidCustomValue) {

                                    array_push($unpaidTickets[$x], $unpaidCustomValue);
              
                                }
                                
                            }

                        }
                        
                    
                    }

                }

                $x++;
            }

            //exit();
        }

        
        $events_query = new WP_Query( array('post_type' => array('event_magic_tickets'), 'posts_per_page' => -1, 'meta_query' => array( array( 'key' => 'WooCommerceEventsProductID', 'value' => $event ) )) );
        $events = $events_query->get_posts();
        
        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename="'.date("Ymdhis").'.csv"');

        $csvOutput = array();
        $fields_headings = array();
        foreach($events as $eventItem) {

            $id = $eventItem->ID;

            $order_id                           = get_post_meta($id, 'WooCommerceEventsOrderID', true);
            $customer_id                        = get_post_meta($id, 'WooCommerceEventsCustomerID', true);
            $WooCommerceEventsStatus            = get_post_meta($id, 'WooCommerceEventsStatus', true);
            $WooCommerceEventsVariations        = get_post_meta($id, 'WooCommerceEventsVariations', true);
            
            if(!empty($WooCommerceEventsVariations) && !is_array($WooCommerceEventsVariations)) {
                
                $WooCommerceEventsVariations = json_decode($WooCommerceEventsVariations);
                
            }
            
            try {
                $order = new WC_Order( $order_id );
            } catch (Exception $e) {

            } 
            
            $ticket = get_post($id);
            $ticketID = $ticket->post_title;
            $ticketType = get_post_meta($ticket->ID, 'WooCommerceEventsTicketType', true);

            if(empty($customer_id)) {

                $customer_id = $ticket->post_author;

            }

            $purchaser = get_user_meta($customer_id);
            $orderID = get_post_meta($ticket->ID, 'WooCommerceEventsOrderID', true);

            try {
                $order = new WC_Order( $order_id );
            } catch (Exception $e) {

            }
            $fp = fopen('php://output', 'w');

            $csvListName = '';
            $csvListLastName = '';
            $WooCommerceEventsAttendeeName                      = get_post_meta($ticket->ID, 'WooCommerceEventsAttendeeName', true);
            $WooCommerceEventsAttendeeLastName                  = get_post_meta($ticket->ID, 'WooCommerceEventsAttendeeLastName', true);
            $WooCommerceEventsCaptureAttendeeTelephone          = get_post_meta($ticket->ID, 'WooCommerceEventsAttendeeTelephone', true);
            $WooCommerceEventsCaptureAttendeeCompany            = get_post_meta($ticket->ID, 'WooCommerceEventsAttendeeCompany', true);
            $WooCommerceEventsCaptureAttendeeDesignation        = get_post_meta($ticket->ID, 'WooCommerceEventsAttendeeDesignation', true);

            $WooCommerceEventsPurchaserFirstName                = get_post_meta($ticket->ID, 'WooCommerceEventsPurchaserFirstName', true);
            $WooCommerceEventsPurchaserLastName                 = get_post_meta($ticket->ID, 'WooCommerceEventsPurchaserLastName', true);
            $WooCommerceEventsPurchaserEmail                    = get_post_meta($ticket->ID, 'WooCommerceEventsPurchaserEmail', true);

            if(empty($WooCommerceEventsAttendeeName)) {

                $csvListName = $order->billing_first_name;

            } else {

                $csvListName = $WooCommerceEventsAttendeeName;

            }    

            if(empty($WooCommerceEventsAttendeeLastName)) {

                $csvListLastName = $order->billing_last_name;

            } else {

                 $csvListLastName = $WooCommerceEventsAttendeeLastName;

            }

            $csvListEmail = '';
            $WooCommerceEventsAttendeeEmail = get_post_meta($ticket->ID, 'WooCommerceEventsAttendeeEmail', true);

            if(empty($WooCommerceEventsAttendeeEmail)) {

                $csvListEmail = $order->billing_email;

            } else {

                $csvListEmail = $WooCommerceEventsAttendeeEmail;

            } 

            if(!empty($order->post->post_status)) {

                if($includeUnpaidTickets != true) {

                    if($WooCommerceEventsStatus == 'Unpaid') {

                        continue;

                    }

                }

                $variationOutput = '';
                $i = 0;
                if(!empty($WooCommerceEventsVariations)) {
                    foreach($WooCommerceEventsVariations as $variationName => $variationValue) {

                        if($i > 0) {

                            $variationOutput .= ' | ';

                        }

                        $variationNameOutput = str_replace('attribute_', '', $variationName);
                        $variationNameOutput = str_replace('pa_', '', $variationNameOutput);
                        $variationNameOutput = str_replace('_', ' ', $variationNameOutput);
                        $variationNameOutput = str_replace('-', ' ', $variationNameOutput);
                        $variationNameOutput = str_replace('Pa_', '', $variationNameOutput);
                        $variationNameOutput = ucwords($variationNameOutput);

                        $variationValueOutput = str_replace('_', ' ', $variationValue);
                        $variationValueOutput = str_replace('-', ' ', $variationValueOutput);
                        $variationValueOutput = ucwords($variationValueOutput);

                        $variationOutput .= $variationNameOutput.': '.$variationValueOutput;

                        $i++;
                    }
                }

                $fooevents_custom_attendee_fields_options = array();

                if ( ! function_exists( 'is_plugin_active_for_network' ) ) {

                    require_once( ABSPATH . '/wp-admin/includes/plugin.php' );

                }

                if ($this->is_plugin_active( 'fooevents_custom_attendee_fields/fooevents-custom-attendee-fields.php') || is_plugin_active_for_network('fooevents_custom_attendee_fields/fooevents-custom-attendee-fields.php')) {

                    $Fooevents_Custom_Attendee_Fields = new Fooevents_Custom_Attendee_Fields();
                    $fooevents_custom_attendee_fields_options = $Fooevents_Custom_Attendee_Fields->display_tickets_meta_custom_options_array($id);

                }

                $billing_fields = array();
                if(!empty($exportbillingdetails)) {

                    $billing_fields = array("Billing Address 1" => $order->get_billing_address_1(), "Billing Address 2" => $order->get_billing_address_2(), "Billing City" => $order->get_billing_city(), "Billing Postal Code" => $order->get_billing_postcode(), "Billing Country" => $order->get_billing_country(), "Billing State" => $order->get_billing_state() , "Billing Phone Number" => $order->get_billing_phone());

                }

                $fields = array("TicketID" => $ticketID, "OrderID" => $order_id, "Attendee First Name" => $csvListName, "Attendee Last Name" => $csvListLastName, "Attendee Email" => $csvListEmail, "Ticket Status" => $WooCommerceEventsStatus, "Ticket Type" => $ticketType, "Variation" => $variationOutput, "Attendee Telephone" => $WooCommerceEventsCaptureAttendeeTelephone, "Attendee Company" => $WooCommerceEventsCaptureAttendeeCompany, "Attendee Designation" => $WooCommerceEventsCaptureAttendeeDesignation, "Purchaser First Name" => $WooCommerceEventsPurchaserFirstName, "Purchaser Last Name" => $WooCommerceEventsPurchaserLastName, "Purchaser Email" => $WooCommerceEventsPurchaserEmail,  "Purchaser Company" => $order->billing_company);
                $fields_values = array_merge(array_values($fields), $billing_fields);
                $fields_values = array_merge($fields_values, $fooevents_custom_attendee_fields_options);
                $billing_fields_headings = array_keys($billing_fields);
                $fooevents_custom_attendee_fields_options_headings = array_keys($fooevents_custom_attendee_fields_options);

                $fields_headings_process = array_keys($fields);
                $csvOutput[] = $fields_values;

                $fields_headings_process = array_merge($fields_headings_process, $billing_fields_headings); 
                $fields_headings_process = array_merge($fields_headings_process, $fooevents_custom_attendee_fields_options_headings); 

                if(count($fields_headings_process) > count($fields_headings)) {

                    $fields_headings = $fields_headings_process;

                }

            }

        }

        if($includeUnpaidTickets) {
            
            $csvOutput = array_merge($csvOutput, $unpaidTickets);
            
        }
        
        /*echo "<pre>";
            print_r($csvOutput);
        echo "</pre>";
        exit();*/
        
        if(empty($csvOutput)) {

            $csvOutput[] = array(__('No tickets found.', 'woocommerce-events'));

        } else {

            fputcsv($fp, $fields_headings);

        }

        foreach ($csvOutput as $fields) {

            fputcsv($fp, $fields);

        }

        fclose($fp);

        exit();
    }
    
    private function get_orders_ids_by_product_id( $product_id, $order_status = array( 'wc-completed' ) ){
        global $wpdb;

        $results = $wpdb->get_col("
            SELECT order_items.order_id
            FROM {$wpdb->prefix}woocommerce_order_items as order_items
            LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
            LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID
            WHERE posts.post_type = 'shop_order'
            AND posts.post_status IN ( '" . implode( "','", $order_status ) . "' )
            AND order_items.order_item_type = 'line_item'
            AND order_item_meta.meta_key = '_product_id'
            AND order_item_meta.meta_value = '$product_id'
        ");

        return $results;
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
    * Checks if a plugin is active.
    * 
    * @param string $plugin
    * @return boolean
    */
    private function is_plugin_active( $plugin ) {

        return in_array( $plugin, (array) get_option( 'active_plugins', array() ) );

    }

}