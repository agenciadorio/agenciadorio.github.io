<?php if ( ! defined( 'ABSPATH' ) ) exit;
class FooEvents_Checkout_Helper {
  
    private $Config;

    public function __construct($Config) {
        
        $this->Config = $Config;
        
        add_action('woocommerce_after_order_notes', array( $this, 'attendee_checkout'));
        add_action('woocommerce_checkout_process', array( $this, 'attendee_checkout_process'));
        add_action('woocommerce_checkout_update_order_meta', array( $this, 'woocommerce_events_process'));
        
    }
    
    /**
     * Displays attendee checkout forms on the checkout page
     * 
     */
    public function attendee_checkout($checkout) {
        
        global $woocommerce;

        $events = $this->get_order_events($woocommerce);
        
        $x = 1;
        
        foreach($events as $event => $tickets) {

            $captureAttendees = $this->check_tickets_for_capture_attendees($tickets);
            
            if($captureAttendees) {
            
                echo '<h2>' . __($event) . '</h2>';

                $y = 1;
                foreach($tickets as $ticket) {

                    $ticketType = '';
                    if(!empty($ticket['attribute_ticket-type'])) {

                        $ticketType = ' - '.$ticket['attribute_ticket-type'];

                    }
                    
                    if(!empty($ticket['attribute_pa_ticket-type'])) {

                        $ticketType = ' - '.$ticket['attribute_pa_ticket-type'];

                    }
                    

                    $WooCommerceEventsCaptureAttendeeDetails        = get_post_meta($ticket['product_id'], 'WooCommerceEventsCaptureAttendeeDetails', true);
                    $WooCommerceEventsCaptureAttendeeTelephone      = get_post_meta($ticket['product_id'], 'WooCommerceEventsCaptureAttendeeTelephone', true);
                    $WooCommerceEventsCaptureAttendeeCompany        = get_post_meta($ticket['product_id'], 'WooCommerceEventsCaptureAttendeeCompany', true);
                    $WooCommerceEventsCaptureAttendeeDesignation    = get_post_meta($ticket['product_id'], 'WooCommerceEventsCaptureAttendeeDesignation', true);
                    
                    if(!empty($ticket['variations'])) {
                        
                        foreach($ticket['variations'] as $key => $variation) {

                            $variationNameOutput = str_replace('attribute_', '', $key);
                            $variationNameOutput = str_replace('pa_', '', $variationNameOutput);
                            $variationNameOutput = str_replace('_', ' ', $variationNameOutput);
                            $variationNameOutput = str_replace('-', ' ', $variationNameOutput);
                            $variationNameOutput = str_replace('Pa_', '', $variationNameOutput);
                            $variationNameOutput = ucwords($variationNameOutput);

                            echo '<div class="fooevents-variation-desc"><strong>'.$variationNameOutput.':</strong> '.$variation.'</div>';

                        }
                        
                    }
                    
                    if($WooCommerceEventsCaptureAttendeeDetails === 'on') {
                        
                        $globalWooCommerceUsePlaceHolders = get_option('globalWooCommerceUsePlaceHolders', true);
                        
                        $firstNameLabel = sprintf(__('Attendee %d First Name', 'woocommerce-events'), $y);
                        
                        $firstNameParams = array(
                        'type'          => 'text',
                        'class'         => array('attendee-class form-row-wide'),
                        'label'         => $firstNameLabel,
                        'placeholder'   => '',
                        'required'      => true,    
                        );

                        if($globalWooCommerceUsePlaceHolders === 'yes') {
                            
                            $firstNameParams['placeholder'] = $firstNameLabel;
                            
                        }
                        
                        woocommerce_form_field($ticket['product_id'].'_attendee_'.$x.'__'.$y, $firstNameParams , $checkout->get_value( $ticket['product_id'].'_attendee_'.$x.'__'.$y ));
                        
                        
                        $lastNameLabel = sprintf(__('Attendee %d Last Name', 'woocommerce-events'), $y);
                        
                        $lastNameParams = array(
                        'type'          => 'text',
                        'class'         => array('attendee-class form-row-wide'),
                        'label'         => $lastNameLabel,
                        'placeholder'   => '',
                        'required'      => true,    
                        );
                        
                        if($globalWooCommerceUsePlaceHolders == 'yes') {
                            
                            $lastNameParams['placeholder'] = $lastNameLabel;
                            
                        }
                        
                        woocommerce_form_field( $ticket['product_id'].'_attendeelastname_'.$x.'__'.$y, $lastNameParams, $checkout->get_value( $ticket['product_id'].'_attendeelastname_'.$x.'__'.$y ));

                        $emailLabel = sprintf(__('Attendee %d Email', 'woocommerce-events'), $y);
                        
                        $emailParams = array(
                        'type'          => 'text',
                        'class'         => array('attendee-class form-row-wide'),
                        'label'         => $emailLabel,
                        'placeholder'   => '',
                        'required'      => true,    
                        );
                        
                        if($globalWooCommerceUsePlaceHolders == 'yes') {
                            
                            $emailParams['placeholder'] = $emailLabel;
                            
                        }
                        
                        woocommerce_form_field( $ticket['product_id'].'_attendeeemail_'.$x.'__'.$y, $emailParams, $checkout->get_value( $ticket['product_id'].'_attendeeemail_'.$x.'__'.$y ));
                        
                        if($WooCommerceEventsCaptureAttendeeTelephone === 'on') {
                            
                            $telehponeLabel = sprintf(__('Attendee %d Telephone', 'woocommerce-events'), $y);

                            $telephoneParams = array(
                            'type'          => 'text',
                            'class'         => array('attendee-class form-row-wide'),
                            'label'         => $telehponeLabel,
                            'placeholder'   => '',
                            'required'      => true,    
                            );

                            if($globalWooCommerceUsePlaceHolders == 'yes') {

                                $telephoneParams['placeholder'] = $telehponeLabel;

                            }
                            
                            woocommerce_form_field( $ticket['product_id'].'_attendeetelephone_'.$x.'__'.$y, $telephoneParams, $checkout->get_value( $ticket['product_id'].'_attendeetelephone_'.$x.'__'.$y ));
                            
                        }
                        
                        if($WooCommerceEventsCaptureAttendeeCompany === 'on') {
                            
                            $companyLabel = sprintf(__('Attendee %d Company', 'woocommerce-events'), $y);

                            $companyParams = array(
                            'type'          => 'text',
                            'class'         => array('attendee-class form-row-wide'),
                            'label'         => $companyLabel,
                            'placeholder'   => '',
                            'required'      => true,    
                            );

                            if($globalWooCommerceUsePlaceHolders == 'yes') {

                                $companyParams['placeholder'] = $companyLabel;

                            }

                            woocommerce_form_field( $ticket['product_id'].'_attendeecompany_'.$x.'__'.$y, $companyParams, $checkout->get_value( $ticket['product_id'].'_attendeecompany_'.$x.'__'.$y ));
                            
                        }
                        
                        if($WooCommerceEventsCaptureAttendeeDesignation === 'on') {
                            
                            $designationLabel = sprintf(__('Attendee %d Designation', 'woocommerce-events'), $y);

                            $designationParams = array(
                            'type'          => 'text',
                            'class'         => array('attendee-class form-row-wide'),
                            'label'         => $designationLabel,
                            'placeholder'   => '',
                            'required'      => true,    
                            );

                            if($globalWooCommerceUsePlaceHolders == 'yes') {

                                $designationParams['placeholder'] = $designationLabel;

                            }
                            
                            woocommerce_form_field( $ticket['product_id'].'_attendeedesignation_'.$x.'__'.$y, $designationParams, $checkout->get_value( $ticket['product_id'].'_attendeedesignation_'.$x.'__'.$y ));
                            
                        }
                        
                        if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
                            
                            require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
                            
                        }
                        
                        if ( $this->is_plugin_active( 'fooevents_custom_attendee_fields/fooevents-custom-attendee-fields.php' ) || is_plugin_active_for_network('fooevents_custom_attendee_fields/fooevents-custom-attendee-fields.php')) {

                            $Fooevents_Custom_Attendee_Fields = new Fooevents_Custom_Attendee_Fields();
                            $Fooevents_Custom_Attendee_Fields->output_attendee_fields($ticket['product_id'], $x, $y, $ticket, $checkout);
                            
                        }
                        
                       

                    }
                    
                    $y++;
                    
                }
                
            }
            
            $x++;
            
        }

        
    }
    
    
    /**
     * Check if attendee details should be captured
     * 
     * @param array $tickets
     * 
     */
    public function check_tickets_for_capture_attendees($tickets) {
        
        foreach($tickets as $ticket) {
            
            $WooCommerceEventsCaptureAttendeeDetails    = get_post_meta($ticket['product_id'], 'WooCommerceEventsCaptureAttendeeDetails', true);
            
            if($WooCommerceEventsCaptureAttendeeDetails === 'on') {
                
                return true;
                
            }
            
        }
        
        return false;
        
    }
    
    /**
     * Processes the attendee details on Checkout
     * 
     * 
     */
    public function attendee_checkout_process() {
        
        global $woocommerce;
        
        $events = $this->get_order_events($woocommerce);
        $x = 1;
        foreach($events as $event => $tickets) {
            
            $y = 1;
            foreach($tickets as $ticket) {
                
                $WooCommerceEventsCaptureAttendeeDetails        = get_post_meta($ticket['product_id'], 'WooCommerceEventsCaptureAttendeeDetails', true);
                $WooCommerceEventsCaptureAttendeeTelephone      = get_post_meta($ticket['product_id'], 'WooCommerceEventsCaptureAttendeeTelephone', true);
                $WooCommerceEventsCaptureAttendeeCompany        = get_post_meta($ticket['product_id'], 'WooCommerceEventsCaptureAttendeeCompany', true);
                $WooCommerceEventsCaptureAttendeeDesignation    = get_post_meta($ticket['product_id'], 'WooCommerceEventsCaptureAttendeeDesignation', true);
                
                if($WooCommerceEventsCaptureAttendeeDetails === 'on') {
                
                    if ( ! $_POST[$ticket['product_id'].'_attendee_'.$x.'__'.$y] ) {
                        
                        $notice = sprintf(__( 'Name is required for %s attendee %d', 'woocommerce-events' ), $event, $y );
                        wc_add_notice( $notice, 'error' );

                    }  
                    
                    if ( ! $_POST[$ticket['product_id'].'_attendeelastname_'.$x.'__'.$y] ) {
                        
                        $notice = sprintf(__( 'Last name is required for %s attendee %d', 'woocommerce-events' ), $event, $y );
                        wc_add_notice( $notice, 'error' );

                    }

                    if ( ! $_POST[$ticket['product_id'].'_attendeeemail_'.$x.'__'.$y] ) {
                        
                        $notice = sprintf(__( 'Email is required for %s attendee %d', 'woocommerce-events' ), $event, $y);
                        wc_add_notice( $notice, 'error' );

                    }
                    
                    if($WooCommerceEventsCaptureAttendeeTelephone === 'on') {
                        if ( ! $_POST[$ticket['product_id'].'_attendeetelephone_'.$x.'__'.$y] ) {

                            $notice = sprintf(__( 'Telephone is required for %s attendee %d', 'woocommerce-events' ), $event, $y);
                            wc_add_notice( $notice, 'error' );

                        }
                    }
                    
                    if($WooCommerceEventsCaptureAttendeeCompany === 'on') {
                        if ( ! $_POST[$ticket['product_id'].'_attendeecompany_'.$x.'__'.$y] ) {

                            $notice = sprintf(__( 'Company is required for %s attendee %d', 'woocommerce-events' ), $event, $y);
                            wc_add_notice( $notice, 'error' );

                        }
                    }
                    
                    if($WooCommerceEventsCaptureAttendeeDesignation === 'on') {
                        if ( ! $_POST[$ticket['product_id'].'_attendeedesignation_'.$x.'__'.$y] ) {

                            $notice = sprintf(__( 'Designation is required for %s attendee %d', 'woocommerce-events' ), $event, $y);
                            wc_add_notice( $notice, 'error' );

                        }
                    }
                    
                    if (!$this->is_email_valid($_POST[$ticket['product_id'].'_attendeeemail_'.$x.'__'.$y])) {
                        
                        $notice = sprintf(__( 'Email is not valid for %s attendee %d', 'woocommerce-events' ), $event, $y);
                        wc_add_notice( $notice, 'error' );
                        
                    }
                    
                    if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
                        
                        require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
                        
                    }

                    if ( $this->is_plugin_active( 'fooevents_custom_attendee_fields/fooevents-custom-attendee-fields.php' ) || is_plugin_active_for_network('fooevents_custom_attendee_fields/fooevents-custom-attendee-fields.php')) {
                        
                        $Fooevents_Custom_Attendee_Fields = new Fooevents_Custom_Attendee_Fields();
                        $Fooevents_Custom_Attendee_Fields->check_required_fields($ticket, $event, $x, $y);
                        
                        
                    }
                    
                }
                
                $y++;

            }
            
            $x++;
            
        }

    }
    
    /**
     * Creates tickets and assigns attendees
     */
    public function woocommerce_events_process($order_id) {
        
        set_time_limit(0);
        
        global $woocommerce;
        
        $events = $this->get_order_events($woocommerce);
        
        /*echo "<pre>";
            print_r($events);
        echo "</pre>";
        echo "<pre>";
            print_r($_POST);
        echo "</pre>";
        
        exit();*/

        $totalTickets = array();
        $orderTickets = array();
        $x = 1;
        foreach($events as $event => $tickets) {
            
            $y = 1;
            foreach($tickets as $ticket) {
                
                $WooCommerceEventsCaptureAttendeeDetails        = get_post_meta($ticket['product_id'], 'WooCommerceEventsCaptureAttendeeDetails', true);
                $WooCommerceEventsCaptureAttendeeTelephone      = get_post_meta($ticket['product_id'], 'WooCommerceEventsCaptureAttendeeTelephone', true);
                $WooCommerceEventsCaptureAttendeeCompany        = get_post_meta($ticket['product_id'], 'WooCommerceEventsCaptureAttendeeCompany', true);
                $WooCommerceEventsCaptureAttendeeDesignation    = get_post_meta($ticket['product_id'], 'WooCommerceEventsCaptureAttendeeDesignation', true);
                
                $customer = get_post_meta($order_id, '_customer_user', true);
                
                $customerDetails = array(
                            'customerID' => $customer
                        );
                
                if(empty($customerDetails['customerID'])) {

                    $customerDetails['customerID'] = 0;

                }
                
                if(empty($ticket['variations'])) {
                    
                    $ticket['variations'] = '';
                    
                }
                
                if($WooCommerceEventsCaptureAttendeeDetails === 'on') {
                    
                    $attendeeName           = $_POST[$ticket['product_id'].'_attendee_'.$x.'__'.$y];
                    $attendeeLastName       = $_POST[$ticket['product_id'].'_attendeelastname_'.$x.'__'.$y];
                    $attendeeEmail          = $_POST[$ticket['product_id'].'_attendeeemail_'.$x.'__'.$y];
                    $attendeeTelephone      = '';
                    $attendeeCompany        = '';
                    $attendeeDesignation    = '';
                    
                    if($WooCommerceEventsCaptureAttendeeTelephone === 'on') {
                        $attendeeTelephone      = $_POST[$ticket['product_id'].'_attendeetelephone_'.$x.'__'.$y];
                    }
                    
                    if($WooCommerceEventsCaptureAttendeeCompany === 'on') {
                        $attendeeCompany        = $_POST[$ticket['product_id'].'_attendeecompany_'.$x.'__'.$y];
                    }
                    
                    if($WooCommerceEventsCaptureAttendeeDesignation === 'on') {
                        $attendeeDesignation    = $_POST[$ticket['product_id'].'_attendeedesignation_'.$x.'__'.$y];
                    }
                    
                    if(empty($ticket['variation_id'])) {
                        
                        $ticket['variation_id'] = '';
                        
                    }
                    
                    //create ticket
                    $orderTickets[$x][$y] = $this->create_order_ticket($customerDetails['customerID'], $ticket['product_id'], $order_id, $ticket['attribute_ticket-type'], $ticket['variations'], $ticket['variation_id'], $x, $y, $attendeeName, $attendeeLastName, $attendeeEmail, $attendeeTelephone, $attendeeCompany, $attendeeDesignation);
                    
                } else {
                    
                    if(empty($ticket['variation_id'])) {
                        
                        $ticket['variation_id'] = '';
                        
                    }
                    
                    $orderTickets[$x][$y] = $this->create_order_ticket($customerDetails['customerID'], $ticket['product_id'], $order_id, $ticket['attribute_ticket-type'], $ticket['variations'], $ticket['variation_id'], $x, $y);
                    
                }

                if(empty($ticket['product_id'])) {
                    
                    $totalTickets[$ticket['product_id']] = 1;
                    
                } else {
                    
                    if(isset($totalTickets[$ticket['product_id']])) {
                        
                        $totalTickets[$ticket['product_id']]++;
                        
                    } else {
                        
                        $totalTickets[$ticket['product_id']] = 1;
                        
                    }
                    
                }
                
                $y++;
                
            }
            
            $x++;
            //$totalTickets++;
            
        }

        update_post_meta($order_id, 'WooCommerceEventsOrderTickets', $orderTickets);
        update_post_meta($order_id, 'WooCommerceEventsTicketsPurchased', $totalTickets);

    }
    
    /**
     * Checks a string for valid email address
     * 
     * @param string $email
     * @return bool
     */
    private function is_email_valid($email) {
        
        return filter_var($email, FILTER_VALIDATE_EMAIL) 
            && preg_match('/@.+\./', $email);
        
    }
    
    private function get_order_events($woocommerce) {
        
        $products = $woocommerce->cart->get_cart();

        $events = array();
        foreach($products as $cart_item_key => $product) {

            for($x = 0; $x < $product['quantity']; $x++) {
                
                $WooCommerceEventsEvent = get_post_meta($product['product_id'], 'WooCommerceEventsEvent', true);
                
                if($WooCommerceEventsEvent == 'Event') {
                    
                    $product_data = get_post($product['product_id']);
                    
                    $ticket = array();
                    $ticket['product_id']               = $product['product_id'];
                    $ticket['attribute_ticket-type']    = '';
                    $ticket['event_name']               = $product_data->post_title;

                    if(!empty($product['variation']['attribute_ticket-type'])) {

                        $ticket['attribute_ticket-type'] = $product['variation']['attribute_ticket-type'];

                    }

                    if(!empty($product['variation'])) {

                        $ticket['variations'] = $product['variation'];
                        $ticket['variation_id'] = $product['variation_id'];

                    }

                    $events[$product_data->post_title][] = $ticket;
                
                }
                
            }
            
        }

        return $events;
        
    }

     /**
     * Creates a new ticket
     * 
     */
    public function create_order_ticket($customerID, $product_id, $order_id, $ticketType, $variations, $variationID, $x, $y, $attendeeName = '', $attendeeLastName = '', $attendeeEmail = '', $attendeeTelephone = '', $attendeeCompany = '', $attendeeDesignation = '') {
        
        $order = new WC_Order( $order_id );
        
        $ticket = array();
        
        $ticket['WooCommerceEventsProductID'] = $product_id;
        $ticket['WooCommerceEventsOrderID'] = $order_id;
        $ticket['WooCommerceEventsTicketType'] = $ticketType;
        $ticket['WooCommerceEventsStatus'] = 'Unpaid';
        $ticket['WooCommerceEventsCustomerID'] = $customerID;
        $ticket['WooCommerceEventsAttendeeName'] = $attendeeName;
        $ticket['WooCommerceEventsAttendeeLastName'] = $attendeeLastName;
        $ticket['WooCommerceEventsAttendeeEmail'] = $attendeeEmail;
        $ticket['WooCommerceEventsAttendeeTelephone'] = $attendeeTelephone;
        $ticket['WooCommerceEventsAttendeeCompany'] = $attendeeCompany;
        $ticket['WooCommerceEventsAttendeeDesignation'] = $attendeeDesignation;
        $ticket['WooCommerceEventsVariations'] = $variations;
        $ticket['WooCommerceEventsVariationID'] = $variationID;

        $WooCommerceEventsPurchaserFirstName = $order->get_billing_first_name();
        $WooCommerceEventsPurchaserLastName = $order->get_billing_last_name();
        $WooCommerceEventsPurchaserEmail = $order->get_billing_email();
        
        $ticket['WooCommerceEventsPurchaserFirstName'] = $WooCommerceEventsPurchaserFirstName;
        $ticket['WooCommerceEventsPurchaserLastName'] = $WooCommerceEventsPurchaserLastName;
        $ticket['WooCommerceEventsPurchaserEmail'] = $WooCommerceEventsPurchaserEmail;
        
        $WooCommerceEventsCustomAttendeeFields = '';
        if ( ! function_exists( 'is_plugin_active_for_network' ) ) {

            require_once( ABSPATH . '/wp-admin/includes/plugin.php' );

        }
        if ( $this->is_plugin_active( 'fooevents_custom_attendee_fields/fooevents-custom-attendee-fields.php' ) || is_plugin_active_for_network('fooevents_custom_attendee_fields/fooevents-custom-attendee-fields.php') ) {

            $Fooevents_Custom_Attendee_Fields = new Fooevents_Custom_Attendee_Fields();
            $WooCommerceEventsCustomAttendeeFields = $Fooevents_Custom_Attendee_Fields->capture_custom_attendee_options($product_id, $x, $y);
            
        }
        
        $ticket['WooCommerceEventsCustomAttendeeFields'] = $WooCommerceEventsCustomAttendeeFields;

        return $ticket;
        
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