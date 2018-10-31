<?php if ( ! defined( 'ABSPATH' ) ) exit;
class FooEvents_Mail_Helper {
    
    public $Config;
    
    public function __construct($config) {
        
        $this->Config = $config;
        add_filter( 'wp_mail_content_type', array($this, 'wpdocs_set_html_mail_content_type'));

    }
    
    /**
     * Includes email template and parses PHP.
     * 
     */
    public function parse_email_template($template, $customerDetails) {

        ob_start();
        
        $eventPluginURL = $this->Config->eventPluginURL;
        
        //Check theme directory for template first
        if(file_exists($this->Config->emailTemplatePathThemeEmail.$template) ) {

             include($this->Config->emailTemplatePathThemeEmail.$template);

        }else {

            include($this->Config->emailTemplatePath.$template); 

        }

        return ob_get_clean();

    }
    
    
    /**
     * Includes the ticket template and parses PHP.
     * 
     * @param array $tickets
     */
    public function parse_ticket_template($ticket) {

        ob_start();
        
        $eventPluginURL = $this->Config->eventPluginURL;

        if(file_exists($this->Config->emailTemplatePathThemeEmail.'tickets.php') ) {

            include($this->Config->emailTemplatePathThemeEmail.'tickets.php');

        } else {

            include($this->Config->emailTemplatePath.'tickets.php'); 

        }

        return ob_get_clean();

    }
    
    /**
     * Sends ticket
     * 
     * @param string $to
     * @param string $subject
     * @param string $body
     * @param string $headers
     */
    public function send_ticket($to, $subject, $body, $headers, $attachment) {

        $sendMail = wp_mail($to, $subject, $body, $headers, $attachment);
        
        if($sendMail) {

            return true;
            
        } else {

            return false;
            
        }
        
    }
    
    public function wpdocs_set_html_mail_content_type() {
        return 'text/html';
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