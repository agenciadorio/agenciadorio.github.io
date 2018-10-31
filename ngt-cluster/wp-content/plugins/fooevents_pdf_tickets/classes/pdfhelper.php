<?php

class FooEvents_PDF_helper {
    
    private $Config;
    
    public function __construct($Config) {
    
        $this->Config = $Config;
        
    }

    /**
     * Includes the ticket template and parses PHP.
     * 
     * @param array $ticket
     * @param string $template_name
     */
    public function parse_ticket_template($ticket, $template, $eventPluginURL) {
        
        ob_start();
        
        $plugins_url = plugins_url();
        
        //Check theme directory for template first
        if(file_exists($this->Config->templatePathTheme.$template) ) {

             include($this->Config->templatePathTheme.$template);

        } else {

            include($this->Config->templatePath.$template); 

        }

        return ob_get_clean();
        
    }
    
    /**
     * Includes the ticket template and parses PHP.
     * 
     * @param array $tickets
     * @param string $template_name
     */
    public function parse_multiple_ticket_template($tickets, $template, $eventPluginURL) {
        
        ob_start();
        
        $plugins_url = plugins_url();
        
        //Check theme directory for template first
        if(file_exists($this->Config->templatePathTheme.$template) ) {

             include($this->Config->templatePathTheme.$template);

        }else {

            include($this->Config->templatePath.$template); 

        }

        return ob_get_clean();
        
    }
    
}
