<?php  

   add_action( 'vc_before_init', 'qt_vc_gridstack_diamonds' );
   if(!function_exists('qt_vc_gridstack_diamonds')){
   function qt_vc_gridstack_diamonds() {
      vc_map( array(
         "name" => __( "Gridstack Diamonds", "qt-extensions-suite" ),
         "base" => "qt-diamonds",
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
               'value' =>array( "post", "artist", "release", "shows", "podcast", "product", "event", "mediagallery", "radiochannel", "chart", "place", "schedule", "members" ),
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
               "heading" => __( "Add link to archive page", "qt-extensions-suite" ),
               "param_name" => "archivelink"
            )
        ,array(
               "type" => "textfield",
               "heading" => __( "Custom archive link", "qt-extensions-suite" ),
               "param_name" => "archiveurl",
               "description" => __( "You can set a custom url, otherwise a link to the post type archive will be set.", "qt-extensions-suite" )
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
         
         , array(
               "type" => "hidden",
               "param_name" => "slidertype",
               'value' => 'custom'
            )
         )
      ) );
   }}
?>