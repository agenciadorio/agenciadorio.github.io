<?php  

   add_action( 'vc_before_init', 'qt_vc_gridstack_slideshow' );
   if(!function_exists('qt_vc_gridstack_slideshow')){
   function qt_vc_gridstack_slideshow() {
      vc_map( array(
         "name" => __( "Gridstack Slideshow", "qt-extensions-suite" ),
         "base" => "qt-slideshow",
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
            ,array(
               "type" => "dropdown",
               "value" => array ('widescreen','thumbnail', 'qantumthemes_medium-thumb', 'medium'  , 'large', 'full'),
               "heading" => __( "Images size", "qt-extensions-suite" ),
               "param_name" => "size"
            )
            ,array(
               "type" => "dropdown",
               "value" => array ( 'original', 'widescreen', 'fullscreen'),
               "heading" => __( "Slider proportion", "qt-extensions-suite" ),
               "param_name" => "proportion"
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
               "param_name" => "transition",
               "description" => __(" Default: 500", "qt-extensions-suite" )
            )
         ,array(
               "type" => "textfield",
               "heading" => __( "Interval time between slides", "qt-extensions-suite" ),
               "param_name" => "interval",
               "description" => __(" Default: 5000", "qt-extensions-suite" )
            ) 
         ,array(
               "type" => "checkbox",
               "heading" => __( "Show indicators", "qt-extensions-suite" ),
               "param_name" => "indicators",
               "description" => __("Display the navigation dots.", "qt-extensions-suite" )
            )
         ,array(
               "type" => "checkbox",
               "heading" => __( "Show arrows", "qt-extensions-suite" ),
               "param_name" => "arrows",
               "description" => __("Display control arrows.", "qt-extensions-suite" )
            )
         ,array(
               "type" => "checkbox",
               "heading" => __( "Is this full width?", "qt-extensions-suite" ),
               "param_name" => "full_width",
               "description" => __("If checked, the arrows layout will be adjusted to display the icon in the inner part", "qt-extensions-suite" )
            )
         ,array(
               "type" => "checkbox",
               "heading" => __( "Show excerpt", "qt-extensions-suite" ),
               "param_name" => "excerpt",
               "description" => __("Display a short introduction of the content. Attention: shortcodes are not supported.", "qt-extensions-suite" )
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