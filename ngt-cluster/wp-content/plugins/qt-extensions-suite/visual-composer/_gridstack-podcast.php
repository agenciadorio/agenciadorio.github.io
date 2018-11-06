<?php  

   add_action( 'vc_before_init', 'qt_vc_gridstack_podcast' );
   if(!function_exists('qt_vc_gridstack_podcast')){
   function qt_vc_gridstack_podcast() {
      vc_map( array(
         "name" => __( "Gridstack Podcast", "qt-extensions-suite" ),
         "base" => "qt-podcast",
         "icon" => plugins_url( '/img/qt-logo.png' , __FILE__ ),
         "category" => __( "QT Gridstacks", "qt-extensions-suite"),
         "params" => array(

         array(
               "type" => "textfield",
               "heading" => __( "Quantity (number)", "qt-extensions-suite" ),
               "param_name" => "quantity",
               'value' => '3',
               "description" => __( "Total number of items", "qt-extensions-suite" )
            ),
          array(
               "type" => "textfield",
               "heading" => __( "Filter", "qt-extensions-suite" ),
               "param_name" => "term_ids",
               'value' => '',
               "description" => __( "ID of podcast Filter taxonomy to extract podcasts from a single category", "qt-extensions-suite" )
            )
         
       
        )

      ) );
   }}
?>