<?php  

   add_action( 'vc_before_init', 'qt_vc_gridstack_carousel' );
   if(!function_exists('qt_vc_gridstack_carousel')){
   function qt_vc_gridstack_carousel() {
      vc_map( array(
         "name" => __( "Gridstack 3D Carousel", "qt-extensions-suite" ),
         "base" => "qt-carousel",
         "icon" => plugins_url( '/img/qt-logo.png' , __FILE__ ),
         "category" => __( "QT Gridstacks", "qt-extensions-suite"),
         "params" => array(

            array(
               "type" => "textfield",
               "heading" => __( "Quantity (number)", "qt-extensions-suite" ),
               "param_name" => "quantity",
               'value' => '5',
               "description" => __( "Total number of items", "qt-extensions-suite" )
            )
            , array(
               "type" => "dropdown",
               "heading" => __( "Post type", "qt-extensions-suite" ),
               "param_name" => "posttype",
               'value' =>array( "post", "artist", "release", "shows", "podcast", "product", "event", "mediagallery", "radiochannel", "chart", "place", "schedule", "members", "qtvideo" ),
            )
         , array(
               "type" => "dropdown",
               "heading" => __( "Filter by taxonomy", "qt-extensions-suite" ),
               "param_name" => "taxonomy",
               'value' => array("", "artistgenre", "category", "chartcategory","eventtype","genre","podcastfilter","pcategory","product_cat","showgenre","schedule_cat","tag", "membertype",),
               "description" => __( "Filter contents by a specific taxonomy. You have to add a taxonomy ID below too.", "qt-extensions-suite" )
            )
         ,array(
               "type" => "textfield",
               "heading" => __( "Term id", "qt-extensions-suite" ),
               "param_name" => "term_ids",
               "description" => __( "Numeric ID of the taxonomy you want to show", "qt-extensions-suite" )
            )
         ,array(
               "type" => "textfield",
               "heading" => __( "Transition duration (milliseconds)", "qt-extensions-suite" ),
               "param_name" => "time_constant",
               "value" => "200",
               "description" => __(" Default: 200", "qt-extensions-suite" )
            )
         ,array(
               "type" => "textfield",
               "heading" => __( "Vertical padding", "qt-extensions-suite" ),
               "param_name" => "vpadding",
               "value" => "50px",
               "description" => __(" Default: 50px", "qt-extensions-suite" )
            )

         ,array(
               "type" => "textfield",
               "heading" => __( "Distance between elements", "qt-extensions-suite" ),
               "param_name" => "dist",
               "value" => "-30",
               "description" => __(" Default: -30", "qt-extensions-suite" )
            )
         ,array(
               "type" => "textfield",
               "heading" => __( "Elements padding", "qt-extensions-suite" ),
               "param_name" => "padding",
               "value" => "10",
               "description" => __(" Default: 10", "qt-extensions-suite" )
            )
        
        
         ,array(
               "type" => "checkbox",
               "heading" => __( "Show arrows", "qt-extensions-suite" ),
               "param_name" => "arrows",
               "description" => __("Display control arrows.", "qt-extensions-suite" )
            )

         ,array(
               "type" => "iconpicker",
               "heading" => __( "Custom icon", "qt-extensions-suite" ),
               "param_name" => "icon",
               "description" => __("Custom icon", "qt-extensions-suite" )
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