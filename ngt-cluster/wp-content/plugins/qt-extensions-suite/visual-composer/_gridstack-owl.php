<?php  

   add_action( 'vc_before_init', 'qt_vc_gridstack_owl' );
   if(!function_exists('qt_vc_gridstack_owl')){
   function qt_vc_gridstack_owl() {
      vc_map( array(
         "name" => __( "Gridstack Owl Carowsel", "qt-extensions-suite" ),
         "base" => "qt-owlcarousel",
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
               "heading" => __( "Items per row (number)", "qt-extensions-suite" ),
               "param_name" => "items",
               'value' => '4'
            )
         , array(
               "type" => "dropdown",
               "heading" => __( "Post type", "qt-extensions-suite" ),
               "param_name" => "posttype",
               'value' =>array( "post", "artist", "release", "shows", "podcast", "product", "event", "mediagallery", "radiochannel", "chart", "place", "schedule", "members","qtvideo" ),
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
               "type" => "checkbox",
               "heading" => __( "Add fade in effect on scroll", "qt-extensions-suite" ),
               "param_name" => "fadein"
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