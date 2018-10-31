<?php if ( ! defined( 'ABSPATH' ) ) exit; 
/**
 * Plugin Name: FooEvents PDF Tickets
 * Description: Attach tickets as .pdf files
 * Version: 1.2.9
 * Author: FooEvents
 * Plugin URI: https://www.fooevents.com/
 * Author URI: https://www.fooevents.com/
 * Developer: FooEvents
 * Developer URI: https://www.fooevents.com/
 * Text Domain: fooevents-pdf-tickets
 *
 * Copyright: © 2009-2016 FooEvents.
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

//include config
require(WP_PLUGIN_DIR.'/fooevents_pdf_tickets/config.php');
require(WP_PLUGIN_DIR.'/fooevents_pdf_tickets/vendors/WordPress-Plugin-Update-Notifier/update-notifier.php');
require(WP_PLUGIN_DIR.'/fooevents_pdf_tickets/vendors/autoload.php');

// reference the Dompdf namespace
use Dompdf\Dompdf;

class FooEvents_PDF_Tickets {

    public $Config;
    public $PDFHelper;
    public $TicketHelper;
    
    public function __construct() {
        
        //error_reporting(E_ALL); ini_set('display_errors', '1');
        
        add_action( 'admin_notices', array( $this, 'check_fooevents' ) );
        add_action( 'admin_notices', array( $this, 'check_gd' ) );
        add_action( 'init', array( $this, 'plugin_init' ) );
        add_action( 'woocommerce_settings_tabs_settings_woocommerce_events', array( $this, 'add_settings_tab_settings' ) );
        add_action( 'woocommerce_update_options_settings_woocommerce_events', array( $this, 'update_settings_tab_settings' ) );
        add_action( 'woocommerce_product_write_panel_tabs', array( $this, 'add_product_pdf_tickets_options_tab' ) );
        add_action( 'woocommerce_product_data_panels', array( $this, 'add_product_pdf_tickets_options_tab_options' ) );
        add_action( 'woocommerce_process_product_meta', array( $this, 'process_meta_box' ) );
        add_action( 'admin_init', array( $this, 'register_scripts_and_styles' ) );
        add_action( 'plugins_loaded', array( $this, 'load_text_domain' ) );
        
        add_action('init', array($this, 'fooevents_endpoints'));
        add_filter('woocommerce_account_menu_items', array($this, 'add_tickets_account_menu_item'));
        add_filter('query_vars', array($this, 'fooevents_query_vars'), 0);
        add_action('after_switch_theme', array($this, 'fooevents_flush_rewrite_rules'));
        add_action('woocommerce_account_fooevents-tickets_endpoint', array($this, 'fooevents_custom_endpoint_content'));

        $this->plugin_init();
    }
    
    /**
     * Register JavaScript and CSS file in Wordpress admin
     * 
     */
    public function register_scripts_and_styles() {
   
        wp_enqueue_style( 'fooevents-pdf-tickets-admin-style',  $this->Config->stylesPath . 'pdf-tickets-admin.css', array(), '1.0.0' );

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
        
        if(isset($_POST['FooEventsPDFTicketsEmailText'])) {

            update_post_meta($post_id, 'FooEventsPDFTicketsEmailText', $_POST['FooEventsPDFTicketsEmailText']);

        }
        
        if(isset($_POST['FooEventsTicketFooterText'])) {

            update_post_meta($post_id, 'FooEventsTicketFooterText', $_POST['FooEventsTicketFooterText']);

        }
        
    }
    
    
    /**
     * Checks if FooEvents is installed
     * 
     */
    public function check_fooevents() {
        
        if ( !is_plugin_active( 'fooevents/fooevents.php' ) ) {

                $this->output_notices(array(__( 'The FooEvents Express Check-in plugin requires FooEvents for WooCommerce to be installed.', 'fooevents-pdf-tickets' )));

        } 
        
    }
    
    /**
     * Checks if GD libraries is enabled
     * 
     */
    public function check_gd() {
        
        if(!extension_loaded('gd')){
            
            $this->output_notices(array(__( 'GD libraries is not enabled on your server. This is a requirement for FooEvents PDF tickets. Please contact your host to enable this.', 'fooevents-pdf-tickets' )));
            
        }
        
        if(!ini_get('allow_url_fopen')) {
            
            $this->output_notices(array(__( 'The setting allow_url_fopen is not enabled on your server. This is a requirement for FooEvents PDF tickets. Please contact your host to enable this.', 'fooevents-pdf-tickets' )));
            
        }
        
        $tickets_directory = __DIR__.'/pdftickets/';
        if(!is_writable($tickets_directory)) {
        
            $this->output_notices(array(sprintf(__( 'Directory %s is not writeable', 'fooevents-pdf-tickets' ), $tickets_directory)));
        
        }
            
        
    }
    
    /**
     * Initializes plugin
     * 
     */
    public function plugin_init() {
        
        //Main config
        $this->Config = new FooEvents_PDF_Tickets_Config();
        
        //PDFHelper
        require_once($this->Config->classPath.'pdfhelper.php');
        $this->PDFHelper = new FooEvents_PDF_helper($this->Config);

    }

    /**
     * Initializes the WooCommerce meta box
     * 
     */
    public function add_product_pdf_tickets_options_tab() {

        echo '<li class="custom_tab_pdf_tickets"><a href="#fooevents_pdf_ticket_settings">'.__( 'PDF Ticket Settings', 'fooevents-pdf-tickets' ).'</a></li>';

    }
    
    public function add_product_pdf_tickets_options_tab_options() {
        
        global $post;
        
        $FooEventsPDFTicketsEmailText   = get_post_meta($post->ID, 'FooEventsPDFTicketsEmailText', true);
        $FooEventsTicketFooterText      = get_post_meta($post->ID, 'FooEventsTicketFooterText', true);
        
        if(empty($FooEventsPDFTicketsEmailText)) {

            $FooEventsPDFTicketsEmailText = __('Your tickets are attached. Please print them and bring them to the event. ', 'fooevents-pdf-tickets');

        }
        
        if(empty($FooEventsTicketFooterText)) {

            $FooEventsTicketFooterText = __("Cut out the tickets or keep them together. Don't forget to take them to the event. When printing please use a standard A4 portrait size. Incorrect sizing could effect the reading of the barcode.", 'fooevents-pdf-tickets');

        }

        require($this->Config->templatePath.'pdf-ticket-options.php');
        
    }
    
    /**
     * Adds the WooCommerce tab settings
     * 
     */
    public function add_settings_tab_settings() {
        
        woocommerce_admin_fields( $this->get_tab_settings() );
        
    }
    
    /**
     * Saves the WooCommerce tab settings
     * 
     */
    public function update_settings_tab_settings() {

        woocommerce_update_options( $this->get_tab_settings() );

    }
    
    public function get_tab_settings() {
        
        $settings = array(
            'section_title' => array(
                'name'      => __( 'PDF Ticket Settings', 'fooevents-pdf-tickets' ),
                'type'      => 'title',
                'desc'      => '',
                'id'        => 'wc_settings_fooevents_pdf_tickets_settings_title'
            ),
            'globalFooEventsPDFTicketsDownloads' => array(
                'name'  => __( 'Enable PDF ticket downloads', 'fooevents-pdf-tickets' ),
                'type'  => 'checkbox',
                'id'    => 'globalFooEventsPDFTicketsDownloads',
                'value' => 'yes',
                'desc'  => __( 'Lets purchasers download tickets from the my-account page.', 'fooevents-pdf-tickets' ),
                'class' => 'text uploadfield'
            ),
            'globalFooEventsPDFTicketsEnable' => array(
                'name'  => __( 'Enable PDF tickets', 'fooevents-pdf-tickets' ),
                'type'  => 'checkbox',
                'id'    => 'globalFooEventsPDFTicketsEnable',
                'value' => 'yes',
                'desc'  => __( 'Adds PDF ticket attachments to ticket emails.', 'fooevents-pdf-tickets' ),
                'class' => 'text uploadfield'
            ),
            'globalFooEventsPDFTicketsLayout' => array(
                'name'  => __( 'PDF Layout', 'fooevents-pdf-tickets' ),
                'type'  => 'select',
                'options' => array(
                            'single' => __('Single', 'fooevents-pdf-tickets' ),
                            'multiple' => __('Multiple', 'fooevents-pdf-tickets' )
                            ),
                'id'    => 'globalFooEventsPDFTicketsLayout',
                'desc'  => __( 'Choose between one or multiple tickets per page.', 'fooevents-pdf-tickets' ),
                'class' => 'text uploadfield'
            ));
        
        $settings['section_end'] = array(
            'type' => 'sectionend',
            'id' => 'wc_settings_fooevents_pdf_tickets_settings_end'
        );
        return $settings;
        
    }
    
    /**
     * Builds a ticket per page pdf
     * 
     * @param array $ticket
     * @param string $eventPluginURL
     * @param string $eventPluginPath
     */
    public function generate_ticket($tickets, $eventPluginURL, $eventPluginPath) {
        
        /*error_reporting(E_ALL);
        ini_set('display_errors', 1);*/
        
        $ticket_output = '';
        $fileName = '';
        $x = 1;
        $numTickets = count($tickets);
        
        foreach($tickets as $ticket) {

            $ticket_output .= $this->PDFHelper->parse_ticket_template($ticket, 'pdf-ticket-template-single.php', $eventPluginURL, $eventPluginPath);
            
            if($x < $numTickets) {
                
                $ticket_output .= '<div style="page-break-before: always;"></div>';

            }
            
            if($x == 1) {
                
                $fileName .= $ticket['WooCommerceEventsTicketID'];
                
            }
            
            if($x == $numTickets) {
                
                $fileName .= '-'.$ticket['WooCommerceEventsTicketID'];
                
            }
            
            $x++;
        }
        
        $dompdf = new Dompdf();
        $dompdf->loadHtml($ticket_output);
        $dompdf->set_option('enable_remote', TRUE);
        $dompdf->setPaper('A4');

        $dompdf->render();

        $output = $dompdf->output();
        $path = $this->Config->pdfTicketPath.''.$fileName.'.pdf';
        file_put_contents($path, $output);
        
        return $path;
        
        exit();
    }

    /**
     * Build multiple tickets per page pdf
     * 
     * @param array $ticket
     * @param string $eventPluginURL
     * @param string $eventPluginPath
     */
    public function generate_multiple_ticket($tickets, $eventPluginURL, $eventPluginPath) {
        
        $ticket_output = '';
        $fileName = '';
        $x = 1;
        $numTickets = count($tickets);
        $sortedTickets = array();
        
        foreach($tickets as $ticket) {
            
            $sortedTickets[$ticket['name']][] = $ticket;
            
        }

        foreach($tickets as $ticket) {
            
            if($x == 1) {
                
                $fileName .= $ticket['WooCommerceEventsTicketID'];
                
            }
            
            if($x == $numTickets) {
                
                $fileName .= '-'.$ticket['WooCommerceEventsTicketID'];
                
            }
            
            $x++;
            
        }
        
        foreach($sortedTickets as $tickets) {
        
            $ticket_output .= $this->PDFHelper->parse_multiple_ticket_template($tickets, 'pdf-ticket-template-multiple.php', $eventPluginURL, $eventPluginPath);
        
        }

        $dompdf = new Dompdf();
        $dompdf->loadHtml($ticket_output);
        $dompdf->set_option('enable_remote', TRUE);
        $dompdf->setPaper('A4');

        $dompdf->render();

        $output = $dompdf->output();
        $path = $this->Config->pdfTicketPath.''.$fileName.'.pdf';
        file_put_contents($path, $output);
        
        return $path;
        
        exit();
    }
    
    /**
     * Includes email template and parses PHP.
     * 
     */
    public function parse_email_template($template) {

        ob_start();
        
        //Check theme directory for template first
        if(file_exists($this->Config->templatePathTheme.$template) ) {

             include($this->Config->templatePathTheme.$template);

        }else {

            include($this->Config->templatePath.$template); 

        }

        return ob_get_clean();

    }
    
    public function load_text_domain() {

        $path = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
        $loaded = load_plugin_textdomain( 'fooevents-pdf-tickets', false, $path);
        
        /*if ( ! $loaded )
        {
            print "File not found: $path"; 
            exit;
        }*/
        
    }
    
    public function add_tickets_account_menu_item($items) {
        
        $globalFooEventsPDFTicketsDownloads = get_option( 'globalFooEventsPDFTicketsDownloads' );
        
        if($globalFooEventsPDFTicketsDownloads == "yes") {

            $logout = $items['customer-logout'];
            unset( $items['customer-logout'] );

            $items['fooevents-tickets'] = __( 'Tickets', 'fooevents-pdf-tickets' );

            $items['customer-logout'] = $logout;
        
        }

        return $items;
        
    }
    
    public function fooevents_endpoints() {
        
        add_rewrite_endpoint( 'fooevents-tickets', EP_ROOT | EP_PAGES );
        
    }
    
    public function fooevents_query_vars( $vars ) {
        
        $vars[] = 'fooevents-tickets';

        return $vars;
        
    }
    
    public function fooevents_flush_rewrite_rules() {
        
        flush_rewrite_rules();
        
    }
    
    public function fooevents_custom_endpoint_content() {
        
        $user = wp_get_current_user();
        
        $tickets = new WP_Query( array('post_type' => array('event_magic_tickets'), 'posts_per_page' => -1, 'meta_query' => array( array( 'key' => 'WooCommerceEventsCustomerID', 'value' => $user->ID ) )) );
        $tickets = $tickets->get_posts();
        
        //generate tickets if no exists
        foreach ($tickets as $ticket) {

            $WooCommerceEventsTicketID = get_post_meta($ticket->ID, 'WooCommerceEventsTicketID', true);
            $fileName = $WooCommerceEventsTicketID.'-'.$WooCommerceEventsTicketID;
            $path = $this->Config->pdfTicketPath.''.$fileName.'.pdf';
            $jason = $ticket->ID;
            
            if(!file_exists($path)) {

                $ticket_gen = array();
                $FooEvents = new FooEvents();
                
                $ticket_data = $FooEvents->get_ticket_data($ticket->ID);
                $ticket_gen[] = $ticket_data;
                
                $eventPluginPath = $FooEvents->get_plugin_path();
                $eventPluginURL = $FooEvents->get_plugin_url();

                $this->generate_ticket($ticket_gen, $eventPluginPath, $eventPluginPath);
                
            } 
            
        }
        
        include $this->Config->path.'templates/ticket-list.php'; 
        
    }
    
    public function display_ticket_download($postID, $WooCommerceEventsTicketID, $eventPluginURL, $eventPluginPath) {
        
        $fileName = $WooCommerceEventsTicketID.'-'.$WooCommerceEventsTicketID;
        $urlPath = $this->Config->eventPluginURL.'pdftickets/'.$fileName.'.pdf';
        $filePath = $this->Config->pdfTicketPath.$fileName.'.pdf';

        if(!file_exists($filePath)) {
            
            $ticket = array();
            $FooEvents = new FooEvents();
            $ticket_data = $FooEvents->get_ticket_data($postID);
            $ticket[] = $ticket_data;
            
            $this->generate_ticket($ticket, $eventPluginPath, $eventPluginURL);
           
        }

        include $this->Config->path.'templates/download-ticket-admin.php'; 
        
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

$FooEvents_PDF_Tickets = new FooEvents_PDF_Tickets();