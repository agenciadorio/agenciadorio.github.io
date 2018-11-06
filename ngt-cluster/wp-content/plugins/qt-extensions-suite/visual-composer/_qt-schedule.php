<?php  
   add_action( 'vc_before_init', 'qt_vc_schedule' );
   if(!function_exists('qt_vc_schedule')){
   function qt_vc_schedule() {
      vc_map( array(
         "name" => __( "Schedule", "qt-extensions-suite" ),
         "base" => "qt-schedule",
         "icon" => plugins_url( '/img/qt-logo.png' , __FILE__ ),
         "category" => __( "QT Gridstacks", "qt-extensions-suite"),
         
           
      ) );
   }}
?>