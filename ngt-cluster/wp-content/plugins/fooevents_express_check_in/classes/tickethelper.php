<?php if ( ! defined( 'ABSPATH' ) ) exit; 

class FooEvents_Express_Check_In_Ticket_Helper {
    
    private $Config;
    
    public function __construct($Config) {
        
        $this->Config = $Config;
        add_action( 'admin_menu',  array( $this, 'add_menu_item' ));
        add_action( 'wp_ajax_perform_search', array( $this, 'perform_search' ) );
        add_action( 'wp_ajax_change_ticket_status', array( $this, 'change_ticket_status' ) ); 
        add_action( 'wp_ajax_change_ticket_status_auto_complete', array( $this, 'change_ticket_status_auto_complete' ) );
        add_action( 'wp_ajax_undo_check_in', array( $this, 'undo_check_in' ) );
        
    } 
    
    /**
     * Adds Express Check-ins to the Tickets menu.
     * 
     */
    public function add_menu_item() {
        
        add_submenu_page( 'edit.php?post_type=event_magic_tickets', 'Express Check-in', 'Express Check-in', 'edit_posts', 'fooevents-express-checkin-page', array( $this, 'display_page' ) );
        
    }
    
    /**
     * Displays Express Check-ins admin page
     * 
     */
    public function display_page() {
        
        
        $multiday_options = '';
        if ($this->is_plugin_active('fooevents_multi_day/fooevents-multi-day.php') || is_plugin_active_for_network('fooevents_multi_day/fooevents-multi-day.php')) {

            $Fooevents_Multiday_Events = new Fooevents_Multiday_Events();
            $multiday_options = $Fooevents_Multiday_Events->display_multiday_express_check_in_options();
            
        }
        
        include($this->Config->templatePath.'express-check-in.php'); 
        
    }
    
    /**
     * Processes search from the Express Check-ins page
     * 
     */
    public function perform_search() {
        
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        
        $custom_fields = array(
                "WooCommerceEventsAttendeeName",
                "WooCommerceEventsAttendeeLastName",
                "WooCommerceEventsAttendeeEmail",
                "WooCommerceEventsCustomerID",
                "WooCommerceEventsVariations",
                "WooCommerceEventsPurchaserFirstName",
                "WooCommerceEventsPurchaserLastName",
                "WooCommerceEventsPurchaserEmail",
                "WooCommerceEventsStatus",
                "WooCommerceEventsTicketID",
                "WooCommerceEventsOrderID",
                "WooCommerceEventsProductName"
            );

        $args['post_type'] = 'event_magic_tickets';
        $args['posts_per_page'] = '-1';
        $args['meta_query']['relation'] = 'OR';
        
        foreach($custom_fields as $field) {
            
            $args['meta_query'][] = array(
                'key' => $field,
                'value' => $_POST['value'],
                'compare' => 'LIKE'
            );
            
        }
        
        $tickets = new WP_Query( $args );
        $tickets_data = '';
        
        $multiday = $_POST['multiday'];
        $day = $_POST['day'];
        
        foreach($tickets->posts as $ticket) {

            $ticket_status = '';
            $WooCommerceEventsMultidayStatus = '';
            $WooCommerceEventsMultidayStatusDay = '';
            
            if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
                require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
            }
            
            if ($this->is_plugin_active('fooevents_multi_day/fooevents-multi-day.php') || is_plugin_active_for_network('fooevents_multi_day/fooevents-multi-day.php')) {

                $Fooevents_Multiday_Events = new Fooevents_Multiday_Events();
                $WooCommerceEventsMultidayStatus = $Fooevents_Multiday_Events->display_multiday_status_ticket_meta($ticket->ID, $multiday, $day);
                $WooCommerceEventsMultidayStatusDay = $Fooevents_Multiday_Events->display_multiday_status_ticket_meta_day($ticket->ID, $multiday, $day);

            }

            if(empty($WooCommerceEventsMultidayStatus) || $ticket_status == 'Unpaid' || $ticket_status == 'Canceled' || $ticket_status == 'Cancelled') {

                $ticket_status = get_post_meta($ticket->ID, 'WooCommerceEventsStatus', true);

            } else {

                $ticket_status = $WooCommerceEventsMultidayStatus;

            }
            
            
            $product_id = get_post_meta($ticket->ID, 'WooCommerceEventsProductID', true);
            $event = get_post($product_id);
            
            $ticket_status_class = '';
            if(!empty($WooCommerceEventsMultidayStatus)) {

                $ticket_status_class = preg_replace('#[ -]+#', '-', strtolower($WooCommerceEventsMultidayStatusDay));
                
            } else {
            
                $ticket_status_class = preg_replace('#[ -]+#', '-', strtolower(get_post_meta($ticket->ID, 'WooCommerceEventsStatus', true)));
            
            }

            ob_start();

            include($this->Config->templatePath.'tickets-data.php');

            $tickets_data .= ob_get_clean();
                
        } 

        //echo $tickets_data;
        include($this->Config->templatePath.'tickets.php'); 

        exit();
    }
    
    /**
     * Changes the ticket status from the Express Check-ins page
     * 
     */
    public function change_ticket_status() {
        
        $accepted_responses = array('reset', 'cancel', 'confirm');
        
        $selected = explode("-", $_POST['value']);
        $multiday = $_POST['multiday'];
        $day = $_POST['day'];
        
        if (in_array($selected[4], $accepted_responses)) {
            
            $update_value = '';
            
            if($selected[4] == 'reset') {
                
                $update_value = 'Not Checked In';
                
            } elseif($selected[4] == 'cancel') {
                
                $update_value = 'Canceled';
                
            } elseif($selected[4] == 'confirm') {
                
                $update_value = 'Checked In';
                
            }
            
            $post_ID = (int)$selected[5];
            
            if(!empty($update_value)) {

                if(is_numeric($post_ID) && $post_ID > 0) {
             
                    $ticketID = get_post_meta($post_ID, 'WooCommerceEventsTicketID', true);
                   
                    if($multiday === true && $update_value != 'Canceled') {

                        $WooCommerceEventsMultidayStatus = '';
                        
                        if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
                            
                            require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
                            
                        }

                        if ($this->is_plugin_active('fooevents_multi_day/fooevents-multi-day.php') || is_plugin_active_for_network('fooevents_multi_day/fooevents-multi-day.php')) {

                            $Fooevents_Multiday_Events = new Fooevents_Multiday_Events();
                            $WooCommerceEventsMultidayStatus = $Fooevents_Multiday_Events->update_express_check_in_status($post_ID, $update_value, $multiday, $day);
                            update_post_meta($post_ID, 'WooCommerceEventsStatus', $update_value);
                            
                            echo json_encode(array('status' => 'success', 'ID' => $_POST['value'], 'ticket' => $selected[5], 'message' => $update_value, 'ticketID' => $ticketID));
                            exit();

                        }
                        
                    } else {

                        update_post_meta($post_ID, 'WooCommerceEventsStatus', $update_value);
                        echo json_encode(array('status' => 'success', 'ID' => $_POST['value'], 'ticket' => $selected[5], 'message' => $update_value, 'ticketID' => $ticketID));
                        exit();
                        
                    }

                }
                
            }
            
        }
        
        echo json_encode(array('status' => 'error', 'status_message' => 'There was an error processing ticket matching "'.$_POST['value'].'"'));
        exit();
    }
    
    /**
     * Changes the status automatically when auto complete is enabled
     * 
     */
    public function change_ticket_status_auto_complete() {
        
        $multiday = $_POST['multiday'];
        $day = $_POST['day'];
        
        $custom_fields = array(
                "WooCommerceEventsTicketID"
            );

        $args['post_type'] = 'event_magic_tickets';
        $args['posts_per_page'] = '-1';
        $args['meta_query']['relation'] = 'AND';
        
        foreach($custom_fields as $field) {
            
            $args['meta_query'][] = array(
                'key' => $field,
                'value' => $_POST['value'],
                'compare' => '='
            );
            
        }
        
        $tickets = new WP_Query( $args );
        
        $count = $tickets->found_posts;

        if($count == 0) {
            
            echo json_encode(array('status' => 'error', 'status_message' => 'ERROR: No tickets where found matching "'.$_POST['value'].'" for auto check-in'));
            exit();
            
        } elseif($count > 1) {
            
            echo json_encode(array('status' => 'error', 'status_message' => 'ERROR: Multiple tickets found matching "'.$_POST['value'].'" for auto check-in'));
            exit();
            
        } elseif($count == 1) {

            $ticket_final = '';
            foreach($tickets->posts as $ticket) {
                
                $ticket_final = $ticket;
                
            }
            
            $ticket_status = get_post_meta($ticket_final->ID, 'WooCommerceEventsStatus', true);
            
            if ( ! function_exists( 'is_plugin_active_for_network' ) ) {

                require_once( ABSPATH . '/wp-admin/includes/plugin.php' );

            }

            if ($this->is_plugin_active('fooevents_multi_day/fooevents-multi-day.php') || is_plugin_active_for_network('fooevents_multi_day/fooevents-multi-day.php')) {

                if($ticket_status == 'Canceled'){

                    echo json_encode(array('status' => 'error', 'status_message' => 'ERROR: Unable to check-in #'.$_POST['value'].' ticket has been marked canceled.'));
                    exit();

                }

                $Fooevents_Multiday_Events = new Fooevents_Multiday_Events();
                $multiday_response = $Fooevents_Multiday_Events->update_express_check_in_status_auto_complete($ticket_final->ID, 'Checked In', $multiday, $day);

                if($multiday_response) {

                    echo json_encode(array('status' => 'success', 'ticket' => $ticket_final->ID, 'message' => 'Checked In', 'status_message' => ' SUCCESS: #'.$_POST['value'].' has been checked-in on Day '.$day.'. <a href="#" class="fooevents-express-check-in-undo" id="fooevents-express-check-in-undo-'.$ticket_final->ID.'">Undo</a>'));
                    exit();

                } else {

                    echo json_encode(array('status' => 'error', 'status_message' => 'WARNING: #'.$_POST['value'].' has already been checked-in.'));
                    exit();

                }

            }
            
            if($ticket_status == 'Checked In') {
                
                echo json_encode(array('status' => 'error', 'status_message' => 'WARNING: #'.$_POST['value'].' has already been checked-in.'));
                
                exit();
                
            }elseif($ticket_status == 'Canceled'){
                
                echo json_encode(array('status' => 'error', 'status_message' => 'ERROR: Unable to check-in #'.$_POST['value'].' ticket has been marked canceled.'));
                
                exit();
                
            }elseif($ticket_status == 'Not Checked In') {

                update_post_meta($ticket_final->ID, 'WooCommerceEventsStatus', 'Checked In');
                echo json_encode(array('status' => 'success', 'ticket' => $ticket_final->ID, 'message' => 'Checked In', 'status_message' => 'SUCCESS: #'.$_POST['value'].' has been checked-in. <a href="#" class="fooevents-express-check-in-undo" id="fooevents-express-check-in-undo-'.$ticket_final->ID.'">Undo</a>'));
                exit();
                
            }    
            
        }
        
        echo json_encode(array('status' => 'error', 'status_message' => 'ERROR: Unknown error for "'.$_POST['value'].'"'));
        exit();
        
    }
    
    /**
     * Ticket check-in undo
     * 
     */
    public function undo_check_in() {
        
        $multiday = $_POST['multiday'];
        $day = $_POST['day'];
        
        $selected = explode("-", $_POST['value']);
        $accepted_responses = array('undo');

        if (in_array($selected[4], $accepted_responses)) {

            $post_ID = (int)$selected[5];
            
            if ( ! function_exists( 'is_plugin_active_for_network' ) ) {

                require_once( ABSPATH . '/wp-admin/includes/plugin.php' );

            }

            if ($this->is_plugin_active('fooevents_multi_day/fooevents-multi-day.php') || is_plugin_active_for_network('fooevents_multi_day/fooevents-multi-day.php')) {
   
                $Fooevents_Multiday_Events = new Fooevents_Multiday_Events();
                $multiday_response = $Fooevents_Multiday_Events->undo_express_check_in_status_auto_complete($post_ID, $multiday, $day);
                
                echo json_encode(array('status' => 'success', 'status_message' => 'SUCCESS: #'.$ticketID.' checked-in has been undone.'));
                exit();

            }
            
            $ticket_status = get_post_meta($post_ID, 'WooCommerceEventsStatus', true);

            if(!empty($ticket_status)) {
                
                if(is_numeric($post_ID) && $post_ID > 0) {
                    
                    if($ticket_status == 'Checked In') {
                        
                        $ticketID = get_post_meta($post_ID, 'WooCommerceEventsTicketID', true);
                        update_post_meta($post_ID, 'WooCommerceEventsStatus', 'Not Checked In');
                        
                        echo json_encode(array('status' => 'success', 'status_message' => 'SUCCESS: #'.$ticketID.' checked-in has been undone.'));
                        exit();
                        
                    }
                    
                }
                
            }
            
        }
        
        exit();
        
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