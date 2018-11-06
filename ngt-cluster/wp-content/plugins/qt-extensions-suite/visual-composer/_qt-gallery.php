<?php  
   add_action( 'vc_before_init', 'qt_vc_qtgallery' );
   if(!function_exists('qt_vc_qtgallery')){
   function qt_vc_qtgallery() {
      vc_map( array(
         "name" => __( "Embed gallery", "qt-extensions-suite" ),
         "base" => "qtgallery",
         "icon" => plugins_url( '/img/qt-logo.png' , __FILE__ ),
         "category" => __( "QT Gridstacks", "qt-extensions-suite"),
         "params" => array(

            array(
               "type" => "textfield",
               "heading" => __( "Media Gallery", "qt-extensions-suite" ),
               "param_name" => "id",
               'value' => '',
               "description" => __( "Requires the numeric ID of the media gallery to add", "qt-extensions-suite" )
            )
         )
           
      ) );
   }}
?>