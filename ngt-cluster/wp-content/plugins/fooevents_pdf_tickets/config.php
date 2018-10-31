<?php if ( ! defined( 'ABSPATH' ) ) exit; 

class FooEvents_PDF_Tickets_Config {
    
    public $classPath;
    public $pluginURL;
    public $pluginDirectory;
    public $templatePath; 
    public $barcodePath;
    public $pdfTicketPath;
    public $stylesPath;
    public $templatePathTheme;
    public $path;
    
    /**
     * Initialize configuration variables to be used as object.
     * 
     */
    public function __construct() {

        $this->classPath = plugin_dir_path( __FILE__ ).'classes/';
        $this->pluginDirectory = 'fooevents_pdf_tickets';
        $this->eventPluginURL = plugins_url().'/'.$this->pluginDirectory.'/';
        $this->templatePath = plugin_dir_path( __FILE__ ).'templates/';
        $this->pdfTicketPath = plugin_dir_path( __FILE__ ).'pdftickets/'; 
        $this->stylesPath = plugin_dir_url(__FILE__) .'css/';
        $this->templatePathTheme = get_stylesheet_directory().'/'.$this->pluginDirectory.'/templates/';
        $this->path = plugin_dir_path( __FILE__ );

    }
    
}