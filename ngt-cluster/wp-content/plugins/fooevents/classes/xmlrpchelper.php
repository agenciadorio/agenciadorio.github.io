<?php if ( ! defined( 'ABSPATH' ) ) exit;

class FooEvents_XMLRPC_Helper {

    

    public $Config;

    

    public function __construct($Config) {

    

        $this->Config = $Config;

            

        $this->check_xmlrpc_enabled();

        

    }

    

    public function check_xmlrpc_enabled() {

        

        if(!$this->is_xmlrpc_enabled()) {

        

            $this->output_notices(array("XMLRPC is not enabled."));

            

        }

        

    }

    

    public function is_xmlrpc_enabled() {

        

        $returnBool = false; 

        $enabled = get_option('enable_xmlrpc');



        if($enabled) {

            $returnBool = true;

        }

        else {

            global $wp_version;

            if (version_compare($wp_version, '3.5', '>=')) {

                $returnBool = true; 

            }

            else {

                $returnBool = false;

            }  

        }

        return $returnBool;

    }

    

    

    private function output_notices($notices) {



        foreach ($notices as $notice) {



                echo "<div class='updated'><p>$notice</p></div>";



        }



    }



    

}



/**

 * Get all events as an array

 * 

 * @return array eventsArray

 */

function getAllEvents()

{

    $eventsArray = array();

    

    $args = array(

            'post_type' => 'product',

            'order' => 'ASC',

            'posts_per_page' => -1,

            'meta_query' => array(

                    array(

                            'key' => 'WooCommerceEventsEvent',

                            'value' => 'Event',

                            'compare' => '=',

                    ),

            ),

    );

    

    $query = new WP_Query($args);

    $events = $query->get_posts();

    

    foreach ( $events as &$event ) {

        

        $tempEvent = array();

        

        $tempEvent['WooCommerceEventsProductID'] = (string)$event->ID;

        $tempEvent['WooCommerceEventsName'] = (string)$event->post_title;

        $tempEvent['WooCommerceEventsDate'] = (string)get_post_meta($event->ID, 'WooCommerceEventsDate', true);

        $tempEvent['WooCommerceEventsHour'] = (string)get_post_meta($event->ID, 'WooCommerceEventsHour', true);

        $tempEvent['WooCommerceEventsMinutes'] = (string)get_post_meta($event->ID, 'WooCommerceEventsMinutes', true);

        $tempEvent['WooCommerceEventsTicketLogo'] = (string)get_post_meta($event->ID, 'WooCommerceEventsTicketLogo', true);

        $tempEvent['WooCommerceEventsHourEnd'] = (string)get_post_meta($event->ID, 'WooCommerceEventsHourEnd', true);

        $tempEvent['WooCommerceEventsMinutesEnd'] = (string)get_post_meta($event->ID, 'WooCommerceEventsMinutesEnd', true);

        $tempEvent['WooCommerceEventsLocation'] = (string)get_post_meta($event->ID, 'WooCommerceEventsLocation', true);

        $tempEvent['WooCommerceEventsSupportContact'] = (string)get_post_meta($event->ID, 'WooCommerceEventsSupportContact', true);

        $tempEvent['WooCommerceEventsEmail'] = (string)get_post_meta($event->ID, 'WooCommerceEventsEmail', true);

        $tempEvent['WooCommerceEventsGPS'] = (string)get_post_meta($event->ID, 'WooCommerceEventsGPS', true);

        $tempEvent['WooCommerceEventsGoogleMaps'] = (string)get_post_meta($event->ID, 'WooCommerceEventsGoogleMaps', true);

        $tempEvent['WooCommerceEventsDirections'] = (string)get_post_meta($event->ID, 'WooCommerceEventsDirections', true);
        
        $tempEvent['WooCommerceEventsNumDays'] = (string)get_post_meta($event->ID, 'WooCommerceEventsNumDays', true);

        

        $eventsArray[] = $tempEvent;

        

        unset($tempEvent);

    }

    

    return $eventsArray;

}



/**

 * Get all tickets for an event as an array

 * 

 * @param string $eventID

 * @return array ticketsArray

 */

function getEventTickets($eventID)

{

    

    global $woocommerce;

    

    $ticketsArray = array();

    

    $ticketStatusOptions = array();

    $globalWooCommerceHideUnpaidTicketsApp = get_option('globalWooCommerceHideUnpaidTicketsApp', true);

    

    if ( $globalWooCommerceHideUnpaidTicketsApp == 'yes' ) {

        

        $ticketStatusOptions = array('key' => 'WooCommerceEventsStatus', 'compare' => '!=', 'value' => 'Unpaid');

        

    }

    

    $events_query = new WP_Query( array('post_type' => array('event_magic_tickets'), 'posts_per_page' => -1, 'meta_query' => array( array( 'key' => 'WooCommerceEventsProductID', 'value' => $eventID ), $ticketStatusOptions )) );

    $tickets = $events_query->get_posts();

    

    foreach ( $tickets as &$ticket ) {

        

        $tempTicket = array();

    

        $order_id = get_post_meta($ticket->ID, 'WooCommerceEventsOrderID', true);

        

        try {

            $order = new WC_Order( $order_id );

            

            $tempTicket['customerFirstName'] = (string)$order->billing_first_name;

            $tempTicket['customerLastName'] = (string)$order->billing_last_name;

            $tempTicket['customerEmail'] = (string)$order->billing_email;

            $tempTicket['customerPhone'] = (string)$order->billing_phone;

            

            $tempTicket['WooCommerceEventsAttendeeName'] = (string)get_post_meta($ticket->ID, 'WooCommerceEventsAttendeeName', true);

            $tempTicket['WooCommerceEventsAttendeeLastName'] = (string)get_post_meta($ticket->ID, 'WooCommerceEventsAttendeeLastName', true);

            $tempTicket['WooCommerceEventsAttendeeEmail'] = (string)get_post_meta($ticket->ID, 'WooCommerceEventsAttendeeEmail', true);

            $tempTicket['WooCommerceEventsTicketID'] = (string)get_post_meta($ticket->ID, 'WooCommerceEventsTicketID', true);

            $tempTicket['WooCommerceEventsStatus'] = (string)get_post_meta($ticket->ID, 'WooCommerceEventsStatus', true);
            
            $tempTicket['WooCommerceEventsMultidayStatus'] = (string)get_post_meta($ticket->ID, 'WooCommerceEventsMultidayStatus', true);

            $tempTicket['WooCommerceEventsTicketType'] = (string)get_post_meta($ticket->ID, 'WooCommerceEventsTicketType', true);

            

            $tempTicket['WooCommerceEventsVariationID'] = (string)get_post_meta($ticket->ID, 'WooCommerceEventsVariationID', true);

            $tempTicket['WooCommerceEventsProductID'] = (string)get_post_meta($ticket->ID, 'WooCommerceEventsProductID', true);



            $price = get_post_meta( $tempTicket['WooCommerceEventsVariationID'], '_regular_price', true);

        

            if(empty($price)) {



                $price = get_post_meta( $ticket->WooCommerceEventsVariationID, '_sale_price', true);



            }

            

            $currencySymbol = get_woocommerce_currency_symbol();

        

            if(!empty($price)) {



                $price = $currencySymbol.''.$price;



            } else {



                $_product   = wc_get_product($tempTicket['WooCommerceEventsProductID']);

                $price      = $_product->get_price_html();



            }

            

            $tempTicket['WooCommerceEventsTicketPrice'] = utf8_encode(html_entity_decode(strip_tags((string)$price)));

            

            $WooCommerceEventsVariations = get_post_meta($ticket->ID, 'WooCommerceEventsVariations', true);

        

            $WooCommerceEventsVariationsOutput = array();

        

            if ( !empty($WooCommerceEventsVariations) ) {

            

                foreach ( $WooCommerceEventsVariations as $variationName => $variationValue ) {

                

                    $variationNameOutput = str_replace('attribute_', '', $variationName);

                    $variationNameOutput = str_replace('pa_', '', $variationNameOutput);

                    $variationNameOutput = str_replace('_', ' ', $variationNameOutput);

                    $variationNameOutput = str_replace('-', ' ', $variationNameOutput);

                    $variationNameOutput = str_replace('Pa_', '', $variationNameOutput);

                    $variationNameOutput = ucwords($variationNameOutput);

                    

                    $variationValueOutput = str_replace('_', ' ', $variationValue);

                    $variationValueOutput = str_replace('-', ' ', $variationValueOutput);

                    $variationValueOutput = ucwords($variationValueOutput);

                    

                    $WooCommerceEventsVariationsOutput[$variationNameOutput] = (string)$variationValueOutput;

                    

                }

                

            }

            

            $tempTicket['WooCommerceEventsVariations'] = $WooCommerceEventsVariationsOutput;

            

            $post_meta = get_post_meta($ticket->ID); 

            $custom_values = array();



            foreach($post_meta as $key => $meta) {



               if (strpos($key, 'fooevents_custom_') === 0) {



                    $custom_values[$key] = $meta[0];



               }



            }

            

            $custom_values_output = array();

            foreach($custom_values as $key => $value) {



                $custom_values_output[fooevents_output_custom_field_name($key)] = $value;



            }

            

            $tempTicket['WooCommerceEventsCustomAttendeeFields'] = $custom_values_output;

            

            $ticketsArray[] = $tempTicket;

            

            unset($tempTicket);

        }

        catch ( Exception $e ) {

            // do nothing

        }

    }

    

    return $ticketsArray;

}



/**

 * Gets all data for all events for offline mode

 * 

 * @global object $wp_xmlrpc_server

 * @param array $args

 */

function fooevents_get_all_data($args) {

    

    error_reporting(0);

    ini_set('display_errors', 0);

    

    set_time_limit(0);

    

    $memory_limit = ini_get('memory_limit');

    

    ini_set('memory_limit', '-1');

    

    global $wp_xmlrpc_server;

    $wp_xmlrpc_server->escape( $args );

    

    $username = $args[0];

    $password = $args[1];

    

    if ( ! $user = $wp_xmlrpc_server->login( $username, $password ) ) {

        

        return $wp_xmlrpc_server->error;

        exit();

        

    }



    if(!fooevents_checkroles($user->roles)) {

        

        $output['message'] = false;

        echo json_encode($output);

        exit();

        

    }

    

    $dataOutput = getAllEvents();

    

    foreach ( $dataOutput as &$event )

    {

        $event['eventTickets'] = getEventTickets($event['WooCommerceEventsProductID']);

    }

    

    echo json_encode($dataOutput);

    

    ini_set('memory_limit', $memory_limit);

    

    exit();

}



/**

 * Gets all events

 * 

 * @global object $wp_xmlrpc_server

 * @param array $args

 */

function fooevents_get_list_of_events($args) {

    error_reporting(0);

    ini_set('display_errors', 0);

    

    set_time_limit(0);

    

    $memory_limit = ini_get('memory_limit');

    

    ini_set('memory_limit', '-1');

    

    global $wp_xmlrpc_server;

    $wp_xmlrpc_server->escape( $args );

    

    $username = $args[0];

    $password = $args[1];

    

    if ( ! $user = $wp_xmlrpc_server->login( $username, $password ) ) {

        

        return $wp_xmlrpc_server->error;

        exit();

        

    }



    if(!fooevents_checkroles($user->roles)) {

        

        $output['message'] = false;

        echo json_encode($output);

        exit();

        

    }

    

    echo json_encode(getAllEvents());

    

    ini_set('memory_limit', $memory_limit);

    

    exit();



}



/**

 * Gets an event

 *

 * Note: Legacy method

 * Newer versions of the app do not use this method anymore, since all event information is retrieved when the event list is fetched

 * 

 * @global object $wp_xmlrpc_server

 * @param type $args

 * @return type

 */

function fooevents_get_event($args) {

    

    error_reporting(0);

    ini_set('display_errors', 0);

    

    global $wp_xmlrpc_server;

    $wp_xmlrpc_server->escape( $args );

    

    $username   = $args[0];

    $password   = $args[1];

    $eventID    = $args[2];

    

    if ( ! $user = $wp_xmlrpc_server->login( $username, $password ) ) {

        

        return $wp_xmlrpc_server->error;

        exit();

        

    }

    

    if(!fooevents_checkroles($user->roles)) {

        

        $output['message'] = false;

        echo json_encode($output);

        exit();

        

    }

    

    $event = get_post( $eventID );

    

    $eventOutput  = array();

    $x = 0;

    

    $eventOutput['WooCommerceEventsProductID']        = (string)$event->ID;

    $eventOutput['WooCommerceEventsName']             = (string)$event->post_title;

    $eventOutput['WooCommerceEventsDate']             = (string)get_post_meta($event->ID, 'WooCommerceEventsDate', true);

    $eventOutput['WooCommerceEventsHour']             = (string)get_post_meta($event->ID, 'WooCommerceEventsHour', true);

    $eventOutput['WooCommerceEventsHourEnd']          = (string)get_post_meta($event->ID, 'WooCommerceEventsHourEnd', true);

    $eventOutput['WooCommerceEventsMinutes']          = (string)get_post_meta($event->ID, 'WooCommerceEventsMinutes', true);

    $eventOutput['WooCommerceEventsMinutesEnd']       = (string)get_post_meta($event->ID, 'WooCommerceEventsMinutesEnd', true);

    $eventOutput['WooCommerceEventsLocation']         = (string)get_post_meta($event->ID, 'WooCommerceEventsLocation', true);

    $eventOutput['WooCommerceEventsTicketLogo']       = (string)get_post_meta($event->ID, 'WooCommerceEventsTicketLogo', true);

    $eventOutput['WooCommerceEventsSupportContact']   = (string)get_post_meta($event->ID, 'WooCommerceEventsSupportContact', true);

    $eventOutput['WooCommerceEventsEmail']            = (string)get_post_meta($event->ID, 'WooCommerceEventsEmail', true);

    $eventOutput['WooCommerceEventsGPS']              = (string)get_post_meta($event->ID, 'WooCommerceEventsGPS', true);

    $eventOutput['WooCommerceEventsGoogleMaps']       = (string)get_post_meta($event->ID, 'WooCommerceEventsGoogleMaps', true);

    $eventOutput['WooCommerceEventsDirections']       = (string)get_post_meta($event->ID, 'WooCommerceEventsDirections', true);

    

    $eventOutput = json_encode($eventOutput);

    

    echo $eventOutput;

    

    exit();

}



/**

 * Gets a list of tickets belonging to an event

 * 

 * @global object $wp_xmlrpc_server

 * @param array $args

 */

function fooevents_get_tickets_in_event($args) {

    

    /*error_reporting(E_ALL);

    ini_set('display_errors', '1');*/

    

    error_reporting(0);

    ini_set('display_errors', 0);

    

    set_time_limit(0);

    

    $memory_limit = ini_get('memory_limit');

    

    ini_set('memory_limit', '-1');

    

    global $woocommerce;

    global $wp_xmlrpc_server;

    $wp_xmlrpc_server->escape( $args );

    

    $username   = $args[0];

    $password   = $args[1];

    $eventID    = $args[2];

    

    if ( ! $user = $wp_xmlrpc_server->login( $username, $password ) ) {

        

        return $wp_xmlrpc_server->error;

        exit();

        

    }

    

    if(!fooevents_checkroles($user->roles)) {

        

        $output['message'] = false;

        echo json_encode($output);

        exit();

        

    }

    

    echo json_encode(getEventTickets($eventID));

    

    ini_set('memory_limit', $memory_limit);

    

    exit();

    

}



/**

 * Gets a ticket

 * 

 * Note: Legacy method

 * Newer versions of the app do not use this method anymore, since all ticket information is retrieved when the ticket list is fetched

 *

 * @global object $wp_xmlrpc_server

 * @param array $args

 */

function fooevents_get_ticket($args) {

    

    /*error_reporting(E_ALL);

    ini_set('display_errors', '1');*/

    

    error_reporting(0);

    ini_set('display_errors', 0);

    

    global $woocommerce;

    global $wp_xmlrpc_server;

    $wp_xmlrpc_server->escape( $args );

    

    $username    = $args[0];

    $password    = $args[1];

    $ticketID    = $args[2];



    if ( ! $user = $wp_xmlrpc_server->login( $username, $password ) ) {

        

        return $wp_xmlrpc_server->error;

        exit();

        

    }

    

    if(!fooevents_checkroles($user->roles)) {

        

        $output['message'] = false;

        echo json_encode($output);

        exit();

        

    }

    

    $events_query = new WP_Query( array('post_type' => array('event_magic_tickets'), 'meta_query' => array( array( 'key' => 'WooCommerceEventsTicketID', 'value' => $ticketID ) )) );

    $ticket = $events_query->get_posts();

    $ticket = $ticket[0];

    

    $ticketOutput = array();

    

    $order_id                              = get_post_meta($ticket->ID, 'WooCommerceEventsOrderID', true);

    $order                                 = new WC_Order( $order_id );

    

    $ticketOutput['customerFirstName']                 = (string)$order->billing_first_name;

    $ticketOutput['customerLastName']                  = (string)$order->billing_last_name;

    $ticketOutput['customerEmail']                     = (string)$order->billing_email;

    $ticketOutput['customerPhone']                     = (string)$order->billing_phone;

    $ticketOutput['WooCommerceEventsAttendeeName']     = (string)get_post_meta($ticket->ID, 'WooCommerceEventsAttendeeName', true);

    $ticketOutput['WooCommerceEventsAttendeeLastName'] = (string)get_post_meta($ticket->ID, 'WooCommerceEventsAttendeeLastName', true);

    $ticketOutput['WooCommerceEventsAttendeeEmail']    = (string)get_post_meta($ticket->ID, 'WooCommerceEventsAttendeeEmail', true);

    $ticketOutput['WooCommerceEventsTicketID']         = (string)get_post_meta($ticket->ID, 'WooCommerceEventsTicketID', true);

    $ticketOutput['WooCommerceEventsTicketType']       = (string)get_post_meta($ticket->ID, 'WooCommerceEventsTicketType', true);

    $ticketOutput['WooCommerceEventsStatus']           = (string)get_post_meta($ticket->ID, 'WooCommerceEventsStatus', true);

    $WooCommerceEventsVariations                       = get_post_meta($ticket->ID, 'WooCommerceEventsVariations', true);

    

    $WooCommerceEventsVariationsOutput = array();

    

    if(!empty($WooCommerceEventsVariations)) {

        foreach($WooCommerceEventsVariations as $variationName => $variationValue) {





            $variationNameOutput = str_replace('attribute_', '', $variationName);

            $variationNameOutput = str_replace('pa_', '', $variationNameOutput);

            $variationNameOutput = str_replace('_', ' ', $variationNameOutput);

            $variationNameOutput = str_replace('-', ' ', $variationNameOutput);

            $variationNameOutput = str_replace('Pa_', '', $variationNameOutput);

            $variationNameOutput = ucwords($variationNameOutput);



            $variationValueOutput = str_replace('_', ' ', $variationValue);

            $variationValueOutput = str_replace('-', ' ', $variationValueOutput);

            $variationValueOutput = ucwords($variationValueOutput);

            

            $WooCommerceEventsVariationsOutput[$variationNameOutput] = (string)$variationValueOutput;



        }

    }

    

    $ticketOutput['WooCommerceEventsVariations'] = $WooCommerceEventsVariationsOutput;



    $post_meta = get_post_meta($ticket->ID); 

    $custom_values = array();



    foreach($post_meta as $key => $meta) {



       if (strpos($key, 'fooevents_custom_') === 0) {



            $custom_values[$key] = $meta[0];



       }



    }

    

    $custom_values_output = array();

    foreach($custom_values as $key => $value) {



        $custom_values_output[fooevents_output_custom_field_name($key)] = $value;



    }

    

    $ticketOutput['WooCommerceEventsCustomAttendeeFields'] = $custom_values_output;



    $ticketOutput = json_encode($ticketOutput);

    

    echo $ticketOutput;

    

    exit();

}



/**

 * Updates a tickets status

 * 

 */

function fooevents_update_ticket_status($args) {

    

    /*error_reporting(E_ALL);

    ini_set('display_errors', '1');*/

    

    error_reporting(0);

    ini_set('display_errors', 0);

    

    global $wp_xmlrpc_server;

    $wp_xmlrpc_server->escape( $args );

    

    $username           = $args[0];

    $password           = $args[1];

    $ticketPostID       = $args[2];

    $status             = $args[3];

    

    if ( ! $user = $wp_xmlrpc_server->login( $username, $password ) ) {

        

        return $wp_xmlrpc_server->error;

        exit();

        

    }

    

    if(!fooevents_checkroles($user->roles)) {

        

        $output['message'] = false;

        echo json_encode($output);

        exit();

        

    }

    

    $events_query = new WP_Query( array('post_type' => array('event_magic_tickets'), 'meta_query' => array( array( 'key' => 'WooCommerceEventsTicketID', 'value' => $ticketPostID ) )) );

    $ticket = $events_query->get_posts();

    $ticket = $ticket[0];

    

    $output = array();

    

    if(!empty($status)) {

        if(update_post_meta( $ticket->ID, 'WooCommerceEventsStatus', strip_tags( $status ))) {



            $output['message'] = 'Status updated';



        } 

    } else {

        

        $output['message'] = 'Status is required';

        

    }

    

    echo json_encode($output);

    

    exit();

    

}



function fooevents_update_ticket_status_m($args) {

    

    /*error_reporting(E_ALL);

    ini_set('display_errors', '1');*/

    

    error_reporting(0);

    ini_set('display_errors', 0);

    

    set_time_limit(0);

    

    $memory_limit = ini_get('memory_limit');

    

    ini_set('memory_limit', '-1');

    

    global $wp_xmlrpc_server;

    $wp_xmlrpc_server->escape( $args );

    

    $username           = $args[0];

    $password           = $args[1];

    $ticketsStatus      = stripslashes(($args[2]));

    

    if ( ! $user = $wp_xmlrpc_server->login( $username, $password ) ) {

        

        return $wp_xmlrpc_server->error;

        exit();

        

    }

    

    if(!fooevents_checkroles($user->roles)) {

        

        $output['message'] = false;

        echo json_encode($output);

        exit();

        

    }

    

    /*$arr = array("765713890" => "Unpaid", "2" => "Unpaid");

    $arr = json_encode($arr);*/

    

    $ticketsStatus      = json_decode($ticketsStatus, true);

    

    

    if(!empty($ticketsStatus)) {

        

        foreach($ticketsStatus as $tempTicketID => $status) {

            if ( strpos($tempTicketID, "_") !== false )
            {
                $tempTicketArray = explode("_", $tempTicketID);
                
                $ticketID = $tempTicketArray[0];
                $day = $tempTicketArray[1];
                
                $output['message'][$ticketID] = updateTicketMultidayStatus($ticketID, $status, $day);
            }
            else
            {
                $ticketID = $tempTicketID;
                
                $events_query = new WP_Query( array('post_type' => array('event_magic_tickets'), 'meta_query' => array( array( 'key' => 'WooCommerceEventsTicketID', 'value' => $ticketID ) )) );

                $ticket = $events_query->get_posts();

                $ticket = $ticket[0];

                

                if(update_post_meta( $ticket->ID, 'WooCommerceEventsStatus', strip_tags( $status ))) {

      

                    $output['message'][$ticketID] = 'Status updated';



                } else {

                    

                    $output['message'][$ticketID] = 'Status unchanged';

                    

                }
            }
        }

        

    } else {

        

        $output['message'] = 'Status is required';

        

    }

    

    echo json_encode($output);

    

    ini_set('memory_limit', $memory_limit);

    

    exit();

    

}

function updateTicketMultidayStatus($ticketID, $status, $day)
{
    $events_query = new WP_Query( array('post_type' => array('event_magic_tickets'), 'meta_query' => array( array( 'key' => 'WooCommerceEventsTicketID', 'value' => $ticketID ) )) );
    $ticket = $events_query->get_posts();

    if(!empty($ticket)) {
        
        $ticket = $ticket[0];
        
        $WooCommerceEventsMultidayStatus = get_post_meta($ticket->ID, "WooCommerceEventsMultidayStatus", true);
        $WooCommerceEventsMultidayStatus = json_decode($WooCommerceEventsMultidayStatus, true);
        
        $WooCommerceEventsMultidayStatus[$day] = $status;

        $WooCommerceEventsMultidayStatus = json_encode($WooCommerceEventsMultidayStatus);
        
        update_post_meta($ticket->ID, 'WooCommerceEventsMultidayStatus', strip_tags($WooCommerceEventsMultidayStatus));
        update_post_meta($ticket->ID, 'WooCommerceEventsStatus', strip_tags($status));
        
        return 'Status updated';
    }
    else
    {
        return 'Status not updated';
    }
}

function fooevents_update_ticket_status_multiday($args) {
    
    /*error_reporting(E_ALL);

    ini_set('display_errors', '1');*/
    

    global $wp_xmlrpc_server;
    $wp_xmlrpc_server->escape( $args );

    
    $username           = $args[0];
    $password           = $args[1];
    $ticketID           = $args[2];
    $status             = $args[3];
    $day                = $args[4];
    
    $output = '';
    if ( ! $user = $wp_xmlrpc_server->login( $username, $password ) ) {

        return $wp_xmlrpc_server->error;
        exit();

    }
    
    if(!empty($ticketID) && !empty($status) && !empty($day)) {
        
       $output['message'] = updateTicketMultidayStatus($ticketID, $status, $day);

    } else {
        
        $output['message'] = 'All fields are required.';
        
    }
    
    echo json_encode($output);
    exit();
    
}


/**

 * Checks a users login details

 * 

 * @global object $wp_xmlrpc_server

 * @param type $args

 */

function fooevents_login_status($args) {

    

    /*error_reporting(E_ALL);

    ini_set('display_errors', '1');*/

    

    error_reporting(0);

    ini_set('display_errors', 0);

    

    global $wp_xmlrpc_server;

    $wp_xmlrpc_server->escape( $args );

    

    $username           = $args[0];

    $password           = $args[1];

    $user               = '';



    if ( ! $user = $wp_xmlrpc_server->login( $username, $password ) ) {

        

        $output['message'] = false;

        

    } else {

        

        $output['message'] = true;

        $output['data']    = json_decode(json_encode($user->data), true);

        

    }

    

    if(!fooevents_checkroles($user->roles)) {

        

        $output['message'] = false;

        echo json_encode($output);

        exit();

        

    }
    

    //include config for plugin version

    require_once(WP_PLUGIN_DIR.'/fooevents/config.php');

    

    $tempConfig = new FooEvents_Config();

    

    $output['data']['plugin_version'] = (string)$tempConfig->pluginVersion; 

    

    // Get app settings

    $output['data']['app_logo'] = (string)get_option('globalWooCommerceEventsAppLogo', '');

    $output['data']['app_color'] = (string)get_option('globalWooCommerceEventsAppColor', '');

    $output['data']['app_text_color'] = (string)get_option('globalWooCommerceEventsAppTextColor', '');

    $output['data']['app_background_color'] = (string)get_option('globalWooCommerceEventsAppBackgroundColor', '');

    $output['data']['app_signin_text_color'] = (string)get_option('globalWooCommerceEventsAppSignInTextColor', '');

    // Check if multiday event plugin is enabled
    if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
        require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
    }

    if (fooevents_check_plugin_active('fooevents_multi_day/fooevents-multi-day.php') || is_plugin_active_for_network('fooevents_multi_day/fooevents-multi-day.php')) {
        
        $output['data']['multiday_enabled'] = 'Yes';

    } else {
        
        $output['data']['multiday_enabled'] = 'No';
        
    }

    $output = json_encode($output);

    

    echo $output;



    exit();

    

}





function fooevents_new_xmlrpc_methods( $methods ) {

    

    error_reporting(0);

    ini_set('display_errors', 0);

    

    $methods['fooevents.get_all_data'] = 'fooevents_get_all_data';
    $methods['fooevents.get_list_of_events'] = 'fooevents_get_list_of_events';
    $methods['fooevents.get_event'] = 'fooevents_get_event';
    $methods['fooevents.get_ticket'] = 'fooevents_get_ticket';
    $methods['fooevents.get_tickets_in_event'] = 'fooevents_get_tickets_in_event';
    $methods['fooevents.update_ticket_status'] = 'fooevents_update_ticket_status';
    $methods['fooevents.login_status'] = 'fooevents_login_status';
    $methods['fooevents.update_ticket_status_m'] = 'fooevents_update_ticket_status_m';
    $methods['fooevents.update_ticket_status_multiday'] = 'fooevents_update_ticket_status_multiday';
    

    return $methods;   

    

}

add_filter( 'xmlrpc_methods', 'fooevents_new_xmlrpc_methods');



function fooevents_checkroles($roles) {

    

    $acceptableRoles = array('administrator', 'editor', 'author', 'contributor', 'shop_manager');

    

    foreach($roles as $key => $role) {

        

        if(in_array($role, $acceptableRoles)) {

            

            return true;

            

        }

        

        

    }

    

    

}



function fooevents_output_custom_field_name($field_name) {

        

        $field_name = str_replace('fooevents_custom_', "", $field_name);

        $field_name = str_replace('_', " ", $field_name);

        $field_name = ucwords($field_name);

        

        return $field_name;

        

    }
    
    
    
