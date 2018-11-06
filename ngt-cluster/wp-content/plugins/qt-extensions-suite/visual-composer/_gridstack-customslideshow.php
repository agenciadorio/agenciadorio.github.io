<?php  

   add_action( 'vc_before_init', 'qt_vc_gridstack_customslideshow' );
   if(!function_exists('qt_vc_gridstack_customslideshow')){
   function qt_vc_gridstack_customslideshow() {
      vc_map( array (
         "name" => __( "Gridstack custom slideshow", "qt-extensions-suite" ),
         "base" => "qt-customslideshow",
         "icon" => plugins_url( '/img/qt-logo.png' , __FILE__ ),
         "category" => __( "QT Gridstacks", "qt-extensions-suite"),
         "params" => array (
            array(
               "type" => "attach_images",
               "heading" => __( "Images", "qt-extensions-suite" ),
               "param_name" => "images",
               "description" => __(" Default: 500", "qt-extensions-suite" )
            )
            ,array(
               "type" => "dropdown",
               "value" => array ('widescreen', 'thumbnail', 'qantumthemes_medium-thumb', 'medium' , 'large', 'full'),
               "heading" => __( "Images size", "qt-extensions-suite" ),
               "param_name" => "size"
            )
             ,array(
               "type" => "dropdown",
               "value" => array ( 'original', 'widescreen', 'fullscreen'),
               "heading" => __( "Slider proportion", "qt-extensions-suite" ),
               "param_name" => "proportion"
            )
            ,array(
               "type" => "exploded_textarea",
               "heading" => __( "Links (one per row)", "qt-extensions-suite" ),
               "param_name" => "links",
               "description" => __("Each image will automatically link to the respective email", "qt-extensions-suite" )
            )
            ,array(
               "type" => "exploded_textarea",
               "heading" => __( "Captions (one per row)", "qt-extensions-suite" ),
               "param_name" => "captions",
               "description" => __("Avoid double quotes", "qt-extensions-suite" )
            )
            ,array(
               "type" => "textfield",
               "heading" => __( "Transition duration (milliseconds)", "qt-extensions-suite" ),
               "param_name" => "transition",
               "description" => __(" Default: 500", "qt-extensions-suite" )
            )
            ,array(
               "type" => "textfield",
               "heading" => __( "Interval time between slides", "qt-extensions-suite" ),
               "param_name" => "interval",
               "description" => __(" Default: 5000", "qt-extensions-suite" )
            ) 
            ,array(
               "type" => "checkbox",
               "heading" => __( "Show indicators", "qt-extensions-suite" ),
               "param_name" => "indicators",
               "description" => __("Display the navigation dots.", "qt-extensions-suite" )
            )
            ,array(
               "type" => "checkbox",
               "heading" => __( "Show arrows", "qt-extensions-suite" ),
               "param_name" => "arrows",
               "description" => __("Display control arrows.", "qt-extensions-suite" )
            )
            ,array(
               "type" => "checkbox",
               "heading" => __( "Adjust arrows for full width", "qt-extensions-suite" ),
               "param_name" => "full_width",
               "description" => __("If checked, the arrows layout will be adjusted to display the icon in the inner part", "qt-extensions-suite" )
            )
            /*,array(
               "type" => "checkbox",
               "heading" => __( "Widescreen proportion", "qt-extensions-suite" ),
               "param_name" => "widescreen",
               "description" => __("Make it fixed proportion (16/7) instead of respecting the original pictures size.", "qt-extensions-suite" )
            )*/
           
         )
      ) );
   }}
?>