<?php  
   add_action( 'vc_before_init', 'qt_vc_embedchart' );
   if(!function_exists('qt_vc_embedchart')){
   function qt_vc_embedchart() {
      vc_map( array(
         "name" => __( "Embed chart", "qt-extensions-suite" ),
         "base" => "qt-embedchart",
         "icon" => plugins_url( '/img/qt-logo.png' , __FILE__ ),
         "category" => __( "QT Chart Top 10", "qt-extensions-suite"),
         "params" => array(

            array(
               "type" => "textfield",
               "heading" => __( "Chart ID (optional)", "qt-extensions-suite" ),
               "param_name" => "id",
               'value' => '',
               "description" => __( "Add the numeric ID of the chart or the latest published will be used", "qt-extensions-suite" )
            )
         )
           
      ) );
   }}
?>