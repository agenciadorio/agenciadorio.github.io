<?php
/**
 * Class to hold all the info about plugin and be extended by all other classes in the plugin
 */
namespace tt_login;
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
class PLUGIN_FW {
    public static $plugin_name;
    public static $plugin_slug;
    public static $plugin_dir;
    public static $plugin_uri;
    public static $plugin_options = array();
    public static $plugin_prefix;

    public function get_plugin_name(){
        return self::$plugin_name;
    }

    public function set_plugin_name($name){
        self::$plugin_name = $name;
    }

    public function get_plugin_slug(){
        return self::$plugin_slug;
    }

    public function set_plugin_slug($slug){
        self::$plugin_slug = $slug;
        self::$plugin_prefix = $slug . '_';
    }

    public function get_plugin_prefix(){
        return self::$plugin_prefix;
    }

    public function get_plugin_dir(){
        return self::$plugin_dir;
    }

    public function set_plugin_dir($dir){
        self::$plugin_dir = $dir;
    }

    public function get_plugin_uri(){
        return self::$plugin_uri;
    }

    public function set_plugin_uri($uri){
        self::$plugin_uri = $uri;
    }

    public function add_plugin_option($option_id){
        self::$plugin_options[] = $option_id;
    }

    public function get_plugin_options(){
        return self::$plugin_options;
    }

    public function unprefix($to_unprefix){
        return substr($to_unprefix, strlen($this->get_plugin_prefix()));
    }

    public function prefix($to_prefix){
        return $this->get_plugin_prefix() . $to_prefix;
    }
}