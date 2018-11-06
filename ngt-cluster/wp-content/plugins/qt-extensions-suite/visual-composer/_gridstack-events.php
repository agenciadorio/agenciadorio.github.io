<?php  

   add_action( 'vc_before_init', 'qt_vc_gridstack_events_carousel' );
   if(!function_exists('qt_vc_gridstack_events_carousel')){
   function qt_vc_gridstack_events_carousel() {
      vc_map( array(
         "name" => __( "Gridstack Events", "qt-extensions-suite" ),
         "base" => "qt-eventcarousel",
         "icon" => plugins_url( '/img/qt-logo.png' , __FILE__ ),
         "category" => __( "QT Gridstacks", "qt-extensions-suite"),
         "params" => array(

         array(
               "type" => "textfield",
               "heading" => __( "Quantity (number)", "qt-extensions-suite" ),
               "param_name" => "quantity",
               'value' => '12',
               "description" => __( "Total number of items", "qt-extensions-suite" )
            )
          ,array(
               "type" => "textfield",
               "heading" => __( "Event type (category)", "qt-extensions-suite" ),
               "param_name" => "eventtype",
               'value' => false,
               "description" => __( "Use the SLUG of the eventtype to display", "qt-extensions-suite" )
            )
         ,array(
               "type" => "textfield",
               "heading" => __( "Items per row (number)", "qt-extensions-suite" ),
               "param_name" => "items",
               'value' => '3'
            )
         
         ,array(
               "type" => "checkbox",
               "heading" => __( "Autoplay", "qt-extensions-suite" ),
               "param_name" => "autoplay"
            )

         ,array(
               "type" => "textfield",
               "heading" => __( "Autoplay timeout", "qt-extensions-suite" ),
               "param_name" => "autoplaytimeout",
               'value' => '3000',
               "description" => __( "(Milliseconds) Default: ", "qt-extensions-suite" ).'3000'
            )


         ,array(
               "type" => "checkbox",
               "heading" => __( "Arrows", "qt-extensions-suite" ),
               "param_name" => "nav"
            )

          ,array(
               "type" => "checkbox",
               "heading" => __( "Navigation dots", "qt-extensions-suite" ),
               "param_name" => "dots"
            )

         
          ,array(
               "type" => "checkbox",
               "heading" => __( "Pause autoplay if mouse hover", "qt-extensions-suite" ),
               "param_name" => "autoplayHoverPause"
            )

          ,array(
               "type" => "textfield",
               "heading" => __( "Margin", "qt-extensions-suite" ),
               "param_name" => "margin",
               'value' => '30',
               "description" => __( "Default: ", "qt-extensions-suite" )."30"
            )
          , array(
               "type" => "textfield",
               "heading" => __( "Advanced: Container ID", "qt-extensions-suite" ),
               "param_name" => "stackid",
               "description" => __( "Add a optional text ID to the gridstack for CSS styling (only letters)", "qt-extensions-suite" )
            )
         
       
        )

      ) );
   }}
?>