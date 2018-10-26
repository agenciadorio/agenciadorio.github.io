<?php

/*
Plugin Name: Hide Widgets
Plugin URI: https://wordpress.org/plugins/hide-widgets
Description: <strong>** Important : This plugin is now merged with <a href="https://wordpress.org/plugins/widget-options/">https://wordpress.org/plugins/widget-options/</a> with additional device and visibility options. Please use this instead, any updates will be added there. Thanks! **</strong>. Add options to hide specific widgets on desktop and/or mobile & iPad screen sizes.
Version: 1.1.1
Author: phpbits
Author URI: http://codecanyon.net/user/phpbits/portfolio?ref=phpbits
License: GPL2
*/

//avoid direct calls to this file
if (!function_exists('add_action')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

// Load translation (by JP)
load_plugin_textdomain( 'hide_widgets', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );

class PHPBITS_hideWidgets {
    public function __construct() {
        add_filter('dynamic_sidebar_params', array( &$this,'add_classes_to_widget')); 
        add_action('admin_print_styles-widgets.php', array( &$this,'print_style'));
        add_action( 'wp_enqueue_scripts', array( &$this,'enqueue_scripts') );
        if ( is_admin() ){
            add_action( 'sidebar_admin_setup', array( &$this,'expand_control') );
            add_filter( 'widget_update_callback', array( &$this,'ajax_update_callback'), 10, 3);   
        }
    }

    function enqueue_scripts(){
        wp_enqueue_style( 'hide-widgets-css', plugins_url( 'css/hide-widgets.css' , __FILE__ ) , array(), null );
    }
    /**
     * Called on 'sidebar_admin_setup'
     * adds in the admin control per widget, but also processes import/export
     */
    function expand_control()
    {   
        global $wp_registered_widgets, $wp_registered_widget_controls;

        /*
         * Add fields to each widgets
         * pop the widget id on the params array (as it's not in the main params so not provided to the callback)
         */
        foreach ( $wp_registered_widgets as $id => $widget )
        {   // controll-less widgets need an empty function so the callback function is called.
            if (!$wp_registered_widget_controls[$id])
                wp_register_widget_control($id,$widget['name'], array( &$this,'empty_control'));
            $wp_registered_widget_controls[$id]['callback_hw_redirect'] = $wp_registered_widget_controls[$id]['callback'];
            $wp_registered_widget_controls[$id]['callback'] = array( &$this,'extra_control');
            array_push( $wp_registered_widget_controls[$id]['params'], $id );   
        }

    }

    /*
     * added to widget functionality in 'expand_control'
     */
    function empty_control() {}

    /*
     * added to widget functionality in 'expand_control'
     */
    function extra_control()
    {   
        global $wp_registered_widget_controls;

        $params = func_get_args();
        $id = array_pop($params);

        // go to the original control function
        $callback = $wp_registered_widget_controls[$id]['callback_hw_redirect'];
        if ( is_callable($callback) )
            call_user_func_array($callback, $params);       


        // dealing with multiple widgets - get the number. if -1 this is the 'template' for the admin interface
        $number=$params[0]['number'];
        if ($number==-1) {
            $number="%i%"; 
            $value="";
        }

        if ( isset($number) ) 
            $id_disp = $wp_registered_widget_controls[$id]['id_base'].'-'.$number;

        $desktop = get_option('hide-widget-' .$id_disp .'-desktop');
        $mobile = get_option('hide-widget-' .$id_disp .'-mobile');


        // output our extra widget logic field
            echo '<input type="hidden" name="hide_widget_getname" value="hide-widget-'. $id_disp .'">';
            echo "<p class='hide-widget-p'><strong>". __('Hide Widget On:', 'hide_widgets') ."</strong><br />
            <input type='checkbox' name='hide_widget_desktop' id='hide-widget-".$id_disp."-desktop' value='1' ". ( (!empty($desktop)) ? 'checked="checked"' : '' ) ."> <label for='hide-widget-".$id_disp."-desktop'>". __('Desktop Size <small>(greater than 768px)</small>', 'hide_widgets') ."</label><br />
            <input type='checkbox' name='hide_widget_mobile' id='hide-widget-".$id_disp."-mobile' value='1' ". ( (!empty($mobile)) ? 'checked="checked"' : '' ) ."> <label for='hide-widget-".$id_disp."-mobile'>". __('Mobile & Tablet Size <small>(less than 769px)</small>', 'hide_widgets') ."</label>
            </p>";
    }

    /*
     * Hide Options to Other Widgets
     */
    function print_style(){
        ?>
        <style type="text/css">
        /* Less specific rule for all widgets */
            div.widgets-sortables#footr-sidebar .hide-widget-p{
               display:none;
            }
        </style>
        <?php
    }
    
    /*
     * Update Options
     */
    function ajax_update_callback($instance, $new_instance, $this_widget){   
        $widget_id=$this_widget->id;
        if(isset($_POST['hide_widget_getname'])){
            $name = strip_tags($_POST['hide_widget_getname']);
            update_option($name . '-desktop', strip_tags($_POST['hide_widget_desktop']) );
            update_option($name . '-mobile', strip_tags($_POST['hide_widget_mobile']) );
        }
        return $instance;
    }
    function add_classes_to_widget($params){
        if ($params[0]['id'] != "footr-sidebar"){ //make sure its your widget id here
            // its your widget so you add  your classes
            
            $classe_to_add = '';
            $desktop = get_option('hide-widget-'. $params[0]['widget_id'] .'-desktop');
            $mobile = get_option('hide-widget-'. $params[0]['widget_id'] .'-mobile');

            if(!empty($desktop)){
                $classe_to_add .= 'widget-no-desktop ';
            }
            if(!empty($mobile)){
                $classe_to_add .= 'widget-no-mobile ';
            }
            $classes = 'class="'.$classe_to_add;
            $params[0]['before_widget'] = str_replace('class="',$classes,$params[0]['before_widget']);
        }
        return $params;
    } 
}
new PHPBITS_hideWidgets();