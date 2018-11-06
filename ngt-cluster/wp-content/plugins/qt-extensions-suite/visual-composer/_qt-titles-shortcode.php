<?php  

   add_action( 'vc_before_init', 'qt_titles_shortcode' );
   if(!function_exists('qt_titles_shortcode')){
   function qt_titles_shortcode() {
      vc_map( array(
         "name" => __( "Title", "qt-extensions-suite" ),
         "base" => "qt-special-titles",
         "icon" => plugins_url( '/img/qt-logo.png' , __FILE__ ),
         "category" => __( "QT Gridstacks", "qt-extensions-suite"),
         "params" => array(

            array(
               "type" => "textfield",
               "heading" => __( "Title text", "qt-extensions-suite" ),
               "param_name" => "title"
            )
            , array(
               "type" => "dropdown",
               "heading" => __( "Tag", "qt-extensions-suite" ),
               "param_name" => "tag",
               'value' =>array( "h1","h2","h3", "h4" ),
            )
            , array(
               "type" => "textfield",
               "heading" => __( "Extra class", "qt-extensions-suite" ),
               "param_name" => "class"
            )
         )
           
      ) );
   }}
?>