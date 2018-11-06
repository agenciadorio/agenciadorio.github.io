<?php  
   add_action( 'vc_before_init', 'qt_vc_release' );
   if(!function_exists('qt_vc_release')){
   function qt_vc_release() {
      vc_map( array(
         "name" => __( "Embed single release", "qt-extensions-suite" ),
         "base" => "qt-release",
         "icon" => plugins_url( '/img/qt-logo.png' , __FILE__ ),
         "category" => __( "QT Gridstacks", "qt-extensions-suite"),
         "params" => array(

            array(
               "type" => "textfield",
               "heading" => __( "Single release", "qt-extensions-suite" ),
               "param_name" => "id",
               'value' => '',
               "description" => __( "Leave empty to load the first release in order of menu order", "qt-extensions-suite" )
            )
            ,array(
               "type" => "checkbox",
               "heading" => __( "Add featured player", "qt-extensions-suite" ),
               "param_name" => "featuredplayer",
               'value' => "1",
               "description" => __("You can have only 1 featured player per page. The setting of the single release are bypassed. Every release embedded after the first with featured player will be without it.", "qt-extensions-suite" )
            )
            ,array(
               "type" => "checkbox",
               "heading" => __( "Hide full content", "qt-extensions-suite" ),
               "param_name" => "hidefullcontent",
               'value' => "0",
               "description" => __("Check if you only want to show featured player. Enable Add Featured Player.", "qt-extensions-suite" )
            )
         )
           
      ) );
   }}
?>