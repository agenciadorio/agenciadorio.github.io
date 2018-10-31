<?php if ( ! defined( 'ABSPATH' ) ) exit; 

class FooEvents_Express_Check_In_Config {
    
    public $classPath;
    public $templatePath; 
    public $scriptsPath;
    public $stylesPath;

    /**
     * Initialize configuration variables to be used as object.
     * 
     */
    public function __construct() {

        $this->classPath = plugin_dir_path( __FILE__ ).'classes/';
        $this->templatePath = plugin_dir_path( __FILE__ ).'templates/';
        $this->scriptsPath = plugin_dir_url(__FILE__) .'js/';
        $this->stylesPath = plugin_dir_url(__FILE__) .'css/';

    }
    
}