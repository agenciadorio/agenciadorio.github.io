<?php
/**
 * View class for keeping markup
 */

namespace tt_login;

class View extends PLUGIN_FW{

    private $path;
    private $data = array();
    private $is_admin;

    function __construct($template_path, $vars = array(), $is_admin = false, $load_now = false){
        $this->data = $vars;
        $this->is_admin = $is_admin;
        $this->path = $this->get_view_path($template_path);
        if($load_now)
            $this->load();
    }

    function get_view_path($view_slug){
        if( $this->is_admin )
            return $this->get_plugin_dir() . "templates/admin/$view_slug.php";
        return $this->get_plugin_dir() . "templates/$view_slug.php";
    }

    function set_view($view_name){
        $this->path = $this->get_view_path($view_name);
    }

    public function load(){
        if(!empty($this->data))
            extract($this->data);
        try {
            if ( ! file_exists($this->path) )
                throw new \Exception("View $this->path not found !");
            include $this->path;
            return true;
        }catch (\Exception $e){
            echo $e->getMessage(), "\n";
            return false;
        }
    }
}