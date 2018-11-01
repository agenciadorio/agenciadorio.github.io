<?php
/**
 * FrontEnd controller class
 */

namespace tt_login\front;


use tt_login\PLUGIN_FW;

class Front extends PLUGIN_FW {

    public static $styles = array();
    public static $scripts = array();
    public static $css = '';
    public static $js = '';
    public static $body_classes = array('tt-login');

    function __construct(){}

    static function init(){
        add_filter( 'login_body_class' , array( 'tt_login\front\Front' , 'body_class' ) );
        add_action( 'login_enqueue_scripts' , array( 'tt_login\front\Front' , 'add_css' ) , 12);
        add_action( 'login_enqueue_scripts' , array( 'tt_login\front\Front' , 'load_scripts' ) );

        //Temporary fix wo not adding css to head https://wordpress.org/support/topic/wp_enqueue_style-adding-css-to-the-footer-on-the-login-page
        if ( ! has_action( 'login_enqueue_scripts', 'wp_print_styles' ) )
            add_action( 'login_enqueue_scripts', 'wp_print_styles', 11 );

        self::$styles[] = array('main-front-css',self::$plugin_uri . '/css/main-front-style.css');
        self::$scripts[] = array('main-front-js',self::$plugin_uri . '/js/main-front-script.js',array('jquery'),true);
    }

    /**
     * @param array|$args wp_enqueue_style args written in an array style
     */
    function add_style(array $args){
        self::$styles[] = $args;
    }

    /**
     * @param array $args wp_enqueue_script args written in an array form
     */
    function add_script(array $args){
        self::$scripts[] = $args;
    }

    function open_selector($selector){
        self::$css .= " $selector {";
    }

    function close_selector(){
        self::$css .= "}";
    }

    public static function load_scripts(){
        foreach(self::$styles as $style_args){
            call_user_func_array('wp_enqueue_style', $style_args);
        }
        foreach(self::$scripts as $script_args){
            call_user_func_array('wp_enqueue_script', $script_args);
        }
        if (!empty(self::$js))
            echo "<script type='text/javascript'>" . self::$js . "</script>";
    }

    /**
     * if any style file was added , prepend the custom css to the last one else just echo the style tag in header
     */
    public static function add_css(){
        $css = self::$css;
        echo "<style type='text/css'>$css</style>" ;
    }

    public static function body_class($classes){
        $classes = array_merge($classes,self::$body_classes);
        return $classes;
    }

}