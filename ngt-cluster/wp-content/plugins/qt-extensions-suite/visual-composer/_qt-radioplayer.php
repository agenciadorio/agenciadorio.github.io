<?php  
   add_action( 'vc_before_init', 'qt_vc_radioplayer' );
   if(!function_exists('qt_vc_radioplayer')){
   function qt_vc_radioplayer() {
      vc_map( array(
         "name" => __( "Radio player", "qt-extensions-suite" ),
         "base" => "qt-radioplayer",
         "icon" => plugins_url( '/img/qt-logo.png' , __FILE__ ),
         "category" => __( "QT Gridstacks", "qt-extensions-suite"),
         "params" => array(

            array(
               "type" => "textfield",
               "heading" => __( "Radio channel ID", "qt-extensions-suite" ),
               "param_name" => "id",
               'value' => '',
               "description" => __( "Leave empty to load the first channel in order of menu order", "qt-extensions-suite" )
            )
         )
           
      ) );
   }}
?>