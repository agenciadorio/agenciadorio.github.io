<?php
$args = array(
  'posts_per_page'   => -1,
  'offset'           => 0,
  'category'         => '',
  'category_name'    => '',
  'orderby'          => 'post_title',
  'order'            => 'ASC',
  'include'          => '',
  'exclude'          => '',
  'meta_key'         => '',
  'meta_value'       => '',
  'post_type'        => 'page',
  'post_mime_type'   => '',
  'post_parent'      => '',
  'post_status'      => 'publish',
  'suppress_filters' => true 
);
$pages = get_posts( $args );
$lists_page = array();
foreach($pages as $p){
  $lists_page[$p->post_title] = $p->ID;
}

$main_color = construction_get_option('main-color');


// Title shortcode

if(function_exists('vc_map')){

  vc_map( array(
     "name" => esc_html__("QK Header Title","construction"),
     "base" => "qk_header",
     "class" => "",
     "category" => esc_html__("QK", "construction"),
     "icon" => "icon-qk",
     "params" => array(
      array(
        "type" => "textfield",
        "holder" => "div",
        "class" => "",
        "heading" => esc_html__("Title","construction"),
        "param_name" => "title",
        "value" => "",
        "description" => esc_html__("Enter custom title or leave blank for default title", "construction")
      ),
      array(
        "type" => "textfield",
        "holder" => "div",
        "class" => "",
        "heading" => esc_html__("Sub Title","construction"),
        "param_name" => "sub_title",
        "value" => "",
        "description" => esc_html__("Enter custom sub title", "construction")
      ),
      array(
        "type" => "checkbox",
        "class" => "",
        "heading" => esc_html__("Show Breadcrumb", 'construction'),
        "param_name" => "show_breadcrumb",
        "value" => array (
          esc_html__( "Yes, please", 'construction' ) => true
        ),
        "description" => esc_html__("Show or not breadcrumb.", 'construction')
      ),
      array(
        "type" => "dropdown",
        "class" => "",
        "heading" => esc_html__("Text align", 'construction'),
        "param_name" => "el_align",
        "value" => array(
          esc_html__("Choose align text","construction") => '',
          esc_html__("Center","construction") => "text-center",
          esc_html__("Left","construction") => "text-left",
          esc_html__("Right","construction") => "text-right",
        ),
        "description" => esc_html__('Choose text align in this element.', 'construction')
      ),
      array(
        'type' => 'css_editor',
        'heading' => esc_html__( 'Css', 'construction' ),
        'param_name' => 'css',
        'group' => esc_html__( 'Design options', 'construction' ),
      ),
      array(
           "type" => "textfield",
           "holder" => "div",
           "class" => "",
           "heading" => esc_html__("Extra class name","construction"),
           "param_name" => "el_class",
           "value" => "",
           "description" => esc_html__('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'construction')
      ),
     )

  ) );
}

class WPBakeryShortCode_qk_header extends WPBakeryShortCode {}

if(function_exists('vc_map')){

  vc_map( array(
     "name" => esc_html__("QK Banner","construction"),
     "base" => "qk_banner",
     "class" => "",
     "category" => esc_html__("QK", "construction"),
     "icon" => "icon-qk",
     "params" => array(
      array(
        "type" => "textfield",
        "holder" => "div",
        "class" => "",
        "heading" => esc_html__("Title","construction"),
        "param_name" => "title",
        "value" => "",
        "description" => esc_html__("Enter custom title or leave blank for default title", "construction")
      ),
      array(
        "type" => "textfield",
        "holder" => "div",
        "class" => "",
        "heading" => esc_html__("Button Name","construction"),
        "param_name" => "btn_name",
        "value" => "",
        "description" => esc_html__("Enter Button name", "construction")
      ),
      array(
        "type" => "textfield",
        "holder" => "div",
        "class" => "",
        "heading" => esc_html__("Button Link","construction"),
        "param_name" => "btn_link",
        "value" => "",
        "description" => esc_html__("Enter Button Link", "construction")
      ),
      array(
        'type' => 'css_editor',
        'heading' => esc_html__( 'Css', 'construction' ),
        'param_name' => 'css',
        'group' => esc_html__( 'Design options', 'construction' ),
      ),
      array(
           "type" => "textfield",
           "holder" => "div",
           "class" => "",
           "heading" => esc_html__("Extra class name","construction"),
           "param_name" => "el_class",
           "value" => "",
           "description" => esc_html__('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'construction')
      ),
     )

  ) );
}

class WPBakeryShortCode_qk_banner extends WPBakeryShortCode {}


// qk_brands
if(function_exists('vc_map')){
  vc_map( array(
     "name" => esc_html__("QK Brands","construction"),
     "base" => "qk_brands",
     "class" => "",
     "category" => esc_html__("QK", "construction"),
     "icon" => "icon-qk",
     "params" => array(
        array(
          "type" => "attach_images",
          "holder" => "div",
          "class" => "",
          "heading" => esc_html__("Choose Gallery Images","construction"),
          "param_name" => "brands",
          "value" => '',
          "description" => ''
        ),
        array(
          'type' => 'textfield',
          'heading' => esc_html__( 'Slides per view', 'construction' ),
          'param_name' => 'perview',
          'value' => '1',
          'description' => esc_html__( 'Enter number of slides to display at the same time.', 'construction' ),
        ),
        array(
          'type' => 'textfield',
          'heading' => esc_html__( 'Image size', 'construction' ),
          'param_name' => 'thumb_size',
          'description' => esc_html__( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).', 'construction' )
        ),
        array(
          'type' => 'exploded_textarea',
          'heading' => esc_html__( 'Link', 'construction' ),
          'param_name' => 'links',
          'description' => esc_html__( 'Enter Links( enter new line to add more link).', 'construction' )
        ),
        array(
          "type" => "textfield",
          "holder" => "div",
          "class" => "",
          "heading" => esc_html__("Extra class name","construction"),
          "param_name" => "el_class",
          "value" => "",
          "description" => esc_html__('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'construction')
        )
     )
  ) );
}

class WPBakeryShortCode_qk_brands extends WPBakeryShortCode {
}

//qk_team
if(function_exists('vc_map')){
vc_map( array(
   "name" => esc_html__("QK Team","construction"),
   "base" => "qk_team",
   "class" => "",
   "category" => esc_html__("QK", "construction"),
   "icon" => "icon-qk",
   "params" => array(
      array(
           "type" => "textfield",
           "holder" => "div",
           "class" => "",
           "heading" => esc_html__("Name","construction"),
           "param_name" => "title",
           "value" => "",
           "description" => ""
        ),
        array(
           "type" => "textfield",
           "holder" => "div",
           "class" => "",
           "heading" => esc_html__("Job","construction"),
           "param_name" => "job",
           "value" => "",
           "description" => ""
        ),
        array(
           "type" => "attach_image",
           "holder" => "div",
           "class" => "",
           "heading" => esc_html__("Images file","construction"),
           "param_name" => "img_url",
           "value" => "",
           "description" => esc_html__('Set source images', 'construction')
        ),
        array(
         "type" => "textarea_html",
         "holder" => "div",
         "class" => "",
         "heading" => esc_html__("Description","construction"),
         "param_name" => "content",
         "value" => '',
         "description" => esc_html__("Description", 'construction')
      ),
      array(
           "type" => "textfield",
           "holder" => "div",
           "class" => "",
           "heading" => esc_html__("Extra class name","construction"),
           "param_name" => "el_class",
           "value" => "",
           "description" => esc_html__('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'construction')
      )
    
   )
) );

}
class WPBakeryShortCode_qk_team extends WPBakeryShortCode {
}


// qk_portfolio
if(function_exists('vc_map')){

vc_map( array(
   "name" => esc_html__("QK Projects","construction"),
   "base" => "qk_portfolio",
   "class" => "",
   "category" => esc_html__("QK", "construction"),
   "icon" => "icon-qk",
   "params" => array(
      array(
        "type" => "dropdown",
        "class" => "",
        "heading" => esc_html__("Choose Type", 'construction'),
        "param_name" => "tpl",
        "value" => array(
          esc_html__("Grid Type",'construction') => "grid",
          esc_html__("Slider Type",'construction') => "slider",
        ),
        "description" => esc_html__('Select Type Service Display.', 'construction')
      ),
      array(
        'type' => 'textfield',
        'heading' => esc_html__( 'Image size', 'construction' ),
        'param_name' => 'thumb_size',
        'description' => esc_html__( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).', 'construction' )
      ),
      array(
        "type" => "dropdown",
        "class" => "",
        "heading" => esc_html__("Columns", 'construction'),
        "param_name" => "columns",
        "value" => array(
            esc_html__("2 Columns",'construction') => "2",
            esc_html__("3 Columns",'construction') => "3",
            esc_html__("4 Columns",'construction') => "4",
            esc_html__("5 Columns",'construction') => "5",
        ),
        "group" => esc_html__("Build Query", 'construction'),
        "description" => esc_html__('Select columns in this elment.', 'construction')
      ),
      array(
        "type" => "checkbox",
        "class" => "",
        "heading" => esc_html__("Show Pagination", 'construction'),
        "param_name" => "show_pagination",
        "value" => array (
          esc_html__( "Yes, please", 'construction' ) => true
        ),
        "description" => esc_html__("Show or not show pagination in this element.", 'construction')
      ),
      array(
        "type" => "checkbox",
        "class" => "",
        "heading" => esc_html__("Show Button", 'construction'),
        "param_name" => "show_button",
        "value" => array (
          esc_html__( "Yes, please", 'construction' ) => true
        ),
        "std" => true,
        "dependency" => array(
          'construction' => "tpl",
          "value" => array('slider'),
        ),
        "description" => esc_html__("Show or not show pagination in this element.", 'construction')
      ),
      array(
        "type" => "checkbox",
        "heading" => esc_html__('Show Caption', 'construction'),
        "param_name" => "show_title",
        "value" => array(
          esc_html__("Yes, please", 'construction') => 1
        ),
        "description" => esc_html__('Show or hide caption.', 'construction')
      ),
      array(
        "type" => "textfield",
        "class" => "",
        "heading" => esc_html__("Posts per page", 'construction'),
        "param_name" => "posts_per_page",
        "value" => "",
        "description" => esc_html__ ( "Posts per page", 'construction' ),
      "group" => esc_html__("Build Query", 'construction'),
      ),
      array(
        "type" => "dropdown",    
        "heading" => esc_html__( 'Order by', 'construction' ),    
        "param_name" => "orderby",    
        "value" => array (
          esc_html__( "None",'construction') => "none",
          esc_html__("Title",'construction') => "title",
          esc_html__("Date",'construction') => "date",
          esc_html__("ID" ,'construction')=> "ID"
        ),
        "group" => esc_html__("Build Query", 'construction'),    
        "description" => esc_html__( 'Order by ("none", "title", "date", "ID").', 'construction' )
      ),
      array(
        "type" => "dropdown",    
        "heading" => esc_html__( 'Order', 'construction' ),    
        "param_name" => "order",    
        "value" => Array (
            esc_html__("None",'construction') => "none",        
            esc_html__("ASC" ,'construction')=> "ASC",        
            esc_html__("DESC",'construction') => "DESC"
        ),
        "group" => esc_html__("Build Query", 'construction'),    
        "description" => esc_html__( 'Order ("None", "Asc", "Desc").', 'construction' )
      ),
      array(
        "type" => "textfield",
        "class" => "",
        "heading" => esc_html__("Extra Class", 'construction'),
        "param_name" => "el_class",
        "value" => "",
        "description" => esc_html__ ( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'construction' )
      )
    )

) );

}
class WPBakeryShortCode_qk_portfolio extends WPBakeryShortCode {
}

// qk_service

if(function_exists('vc_map')){
vc_map( array(
   "name" => esc_html__("QK Icons","construction"),
   "base" => "qk_icons",
   "class" => "",
   "category" => esc_html__("QK", "construction"),
   "icon" => "icon-qk",
   "params" => array(
      array(
        "type" => "dropdown",
        "class" => "",
        "heading" => esc_html__("Template", 'construction'),
        "param_name" => "tpl",
        "value" => array(
          esc_html__("Normal Feature","construction") => "tpl1",
          esc_html__("Blue background Feature","construction") => "tpl2"
        ),
        "description" => esc_html__('Select template in this elment.', 'construction')
      ),
      array(
        "type" => "textfield",
        "holder" => "div",
        "class" => "",
        "heading" => esc_html__("Font icon","construction"),
        "param_name" => "icon",
        "value" => "",
        "description" => esc_html__('Set font icon', 'construction')
      ),
      array(
        "type" => "textfield",
        "holder" => "div",
        "class" => "",
        "heading" => esc_html__("Title","construction"),
        "param_name" => "title",
        "value" => "",
        "description" => esc_html__('Enter title','construction')
      ),
      array(
        "type" => "textarea_html",
        "holder" => "div",
        "class" => "",
        "heading" => esc_html__("Description","construction"),
        "param_name" => "content",
        "value" => '',
        "description" => ''
      ),
      array(
        "type" => "textfield",
        "class" => "",
        "heading" => esc_html__("Extra Class", "construction"),
        "param_name" => "el_class",
        "value" => "",
        "description" => esc_html__("Extra Class.", "construction")
      ),
      array(
        'type' => 'css_editor',
        'heading' => esc_html__( 'Css', 'construction' ),
        'param_name' => 'css',
        'group' => esc_html__( 'Design options', 'construction' ),
      )
   )
) );

}

class WPBakeryShortCode_qk_icons extends WPBakeryShortCode {
}

// qk_skill

if(function_exists('vc_map')){
vc_map( array(
   "name" => esc_html__("QK Search Form","construction"),
   "base" => "qk_searchform",
   "class" => "",
   "category" => esc_html__("QK", "construction"),
   "icon" => "icon-qk",
   "params" => array(
      array(
        "type" => "textfield",
        "holder" => "div",
        "class" => "",
        "heading" => esc_html__("Font icon","construction"),
        "param_name" => "icon",
        "value" => "",
        "description" => esc_html__('Set font icon', 'construction')
      ),
      array(
           "type" => "textfield",
           "holder" => "div",
           "class" => "",
           "heading" => esc_html__("Label Submit","construction"),
           "param_name" => "label",
           "value" => "",
           "description" => esc_html__('Enter label for submmit','construction')
      ),
      array(
           "type" => "textfield",
           "holder" => "div",
           "class" => "",
           "heading" => esc_html__("Place Holder","construction"),
           "param_name" => "placeholder",
           "value" => "",
           "description" => esc_html__('Enter placeholder for search form','construction')
      ),
      array(
           "type" => "textfield",
           "holder" => "div",
           "class" => "",
           "heading" => esc_html__("Extra class name","construction"),
           "param_name" => "el_class",
           "value" => "",
           "description" => esc_html__('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'construction')
      )
   )
) );

}

class WPBakeryShortCode_qk_searchform extends WPBakeryShortCode {
}

// Title shortcode

if(function_exists('vc_map')){

vc_map( array(
   "name" => esc_html__("QK Title","construction"),
   "base" => "qk_title",
   "class" => "",
   "category" => esc_html__("QK", "construction"),
   "icon" => "icon-qk",
   "params" => array(
    array(
       "type" => "textfield",
       "holder" => "div",
       "class" => "",
       "heading" => esc_html__("Title","construction"),
       "param_name" => "title",
       "value" => "",
       "description" => ''
    ),
    array(
      "type" => "colorpicker",
      "class" => "",
      "heading" => esc_html__( "Title Color", "construction" ),
      "param_name" => "title_color",
      "value" => '', //Default Red color
      "dependency" => array(
        'construction' => "title",
        "not_empty" => true,
      ),
      "description" => esc_html__( "Choose text color, main color", "construction" )
    ),
    array(
      "type" => "textarea_html",
      "holder" => "div",
      "class" => "",
      "heading" => esc_html__("Description","construction"),
      "param_name" => "content",
      "value" => '',
      "description" => ''
    ),
    array(
      'type' => 'css_editor',
      'heading' => esc_html__( 'Css', 'construction' ),
      'param_name' => 'css',
      'group' => esc_html__( 'Design options', 'construction' ),
    ),
    array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "",
         "heading" => esc_html__("Extra class name","construction"),
         "param_name" => "el_class",
         "value" => "",
         "description" => esc_html__('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'construction')
    ),
   )

) );



}

class WPBakeryShortCode_qk_title extends WPBakeryShortCode {}

// qk_countup

if(function_exists('vc_map')){

vc_map( array(
   "name" => esc_html__("QK Count Up","construction"),
   "base" => "qk_countup",
   "class" => "",
   "category" => esc_html__("QK", "construction"),
   "icon" => "icon-qk",
   "params" => array(
        array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => esc_html__("Number Count To","construction"),
            "param_name" => "to",
            "value" => "",
            "group" => esc_html__("Template", 'construction'),
            "description" => ''
        ),
        array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => esc_html__("Speed Count To","construction"),
            "param_name" => "speed",
            "value" => "",
            "group" => esc_html__("Template", 'construction'),
            "description" => esc_html__("Duration animate counup time (ms), default 10000",'construction')
        ),
        array(
          "type" => "colorpicker",
          "holder" => "div",
          "class" => "",
          "heading" => esc_html__("Color for count timer","construction"),
          "param_name" => "timer_color",
          "value" => "",
          "group" => esc_html__("Template", 'construction'),
          "description" => esc_html__("Choose color for count timer, main color","construction")
        ),
        array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => esc_html__("Title","construction"),
            "param_name" => "title",
            "value" => "",
            "group" => esc_html__("Template", 'construction'),
            "description" => ''
        ),
        array(
          "type" => "colorpicker",
          "holder" => "div",
          "class" => "",
          "heading" => esc_html__("Color for title","construction"),
          "param_name" => "title_color",
          "value" => "",
          "dependency" => array(
            'construction' => "title",
            "not_empty" => true
          ),
          "group" => esc_html__("Template", 'construction'),
          "description" => esc_html__("Choose color for title, main color","construction")
        ),
        array(
            "type" => "textfield",
            "class" => "",
            "heading" => esc_html__("Extra Class", "construction"),
            "param_name" => "el_class",
            "value" => "",
            "description" => esc_html__("Extra Class.", "construction")
        )
   )

) );

}

class WPBakeryShortCode_qk_countup extends WPBakeryShortCode {}

// qk_testimonial

if(function_exists('vc_map')){

vc_map( array(
   "name" => esc_html__("QK Testimonial","construction"),
   "base" => "qk_testimonial",
   "class" => "",
   "category" => esc_html__("QK", "construction"),
   "icon" => "icon-qk",
   "params" => array(
    array(
     "type" => "textfield",
     "holder" => "div",
     "class" => "",
     "heading" => esc_html__("Total item","construction"),
     "param_name" => "order",
     "value" => "-1",
     "description" => esc_html__('Set max limit for items in loop or enter -1 to display all (limited to 1000).', 'construction')
    ),
    array(
      "type" => "dropdown",
      "class" => "",
      "heading" => esc_html__("Columns", 'construction'),
      "param_name" => "columns",
      "value" => array(
        esc_html__("Please choose option ...","construction") =>"",
        esc_html__("1 Column",'construction') => "1",
        esc_html__("2 Columns",'construction') => "2",
        esc_html__("3 Columns",'construction') => "3",
        esc_html__("4 Columns",'construction') => "4"
      ),
      "description" => esc_html__('Select columns in this elment.', 'construction')
    ),
    array(
        "type" => "checkbox",
        "class" => "",
        "heading" => esc_html__("Hide Avatar", 'construction'),
        "param_name" => "hide_avatar",
        "value" => array (
          esc_html__( "Yes, please", 'construction' ) => true
        ),
        "description" => esc_html__("Show or hide avatar in this element.", 'construction')
    ),
    array(
     "type" => "textfield",
     "holder" => "div",
     "class" => "",
     "heading" => esc_html__("Extra class name","construction"),
     "param_name" => "el_class",
     "value" => "",
     "description" => esc_html__('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'construction')
    )
   )

) );

}

class WPBakeryShortCode_qk_testimonial extends WPBakeryShortCode {
}

// qk_blog
if(function_exists('vc_map')){
  vc_map( array(
     "name" => esc_html__("QK Blog Slider","construction"),
     "base" => "qk_blog",
     "class" => "",
     "category" => esc_html__("QK", "construction"),
     "icon" => "icon-qk",
     "params" => array(
  	   array(
  			"type" => "dropdown",
  			"class" => "",
  			"heading" => esc_html__("Template", 'construction'),
  			"param_name" => "tpl",
  			"value" => array(
  				__("Normal Blog","construction") => "",
  				__("Slider Blog","construction") => "slider"
  			),
  			"description" => esc_html__('Select template in this elment.', 'construction')
  		),
  		array(
  			"type" => "checkbox",
  			"class" => "",
  			"heading" => esc_html__("Show Pagination", 'construction'),
  			"param_name" => "show_pagination",
  			"value" => array (
  				__( "Yes, please", 'construction' ) => true
  			),
  			"group" => esc_html__("Template", 'construction'),
  			"description" => esc_html__("Show or not show pagination in this element.", 'construction')
  		),
      array(
        "type" => "dropdown",
        "class" => "",
        "heading" => esc_html__("Columns", 'construction'),
        "param_name" => "columns",
        "value" => array(
            esc_html__("Please choose option ...","construction") =>"2",
            esc_html__("1 Column",'construction') => "1",
            esc_html__("2 Columns",'construction') => "2",
            esc_html__("3 Columns",'construction') => "3",
            esc_html__("4 Columns",'construction') => "4"
          )
        ),
        array(
          "type" => "textfield",
          "holder" => "div",
          "class" => "",
          "heading" => esc_html__("Total item","construction"),
          "param_name" => "posts_per_page",
          "group" => esc_html__("Build Query", 'construction'),
          "value" => "",
          "description" => esc_html__('Set max limit for items in loop or enter -1 to display all (limited to 1000).', 'construction')
        ),
        array(
          "type" => "checkbox",
          "heading" => esc_html__('Show Readmore', 'construction'),
          "param_name" => "show_readmore",
          "value" => array(
            esc_html__("Yes, please", 'construction') => true
          ),
  		    "group" => esc_html__("Template", 'construction'),
          "description" => esc_html__('Show or hide readmore of post on your category.', 'construction')
        ),
        array(
          "type" => "textfield",
          "heading" => esc_html__('Read More Text', 'construction'),
          "param_name" => "readmore_text",
          "dependency" => array(
            'construction' => "show_readmore",
            "not_empty" => true
          ),
  		    "group" => esc_html__("Template", 'construction'),
          "description" => esc_html__('Enter readmore text.', 'construction')
        ),
        array(
          "type" => "dropdown",    
          "heading" => esc_html__( 'Order by', 'construction' ),    
          "param_name" => "orderby",    
          "value" => array (
            esc_html__( "None",'construction') => "none",
            esc_html__("Title",'construction') => "title",
            esc_html__("Date",'construction') => "date",
            esc_html__("ID" ,'construction')=> "ID"
          ),
          "group" => esc_html__("Build Query", 'construction'),    
          "description" => esc_html__( 'Order by ("none", "title", "date", "ID").', 'construction' )
        ),
        array(
          'type' => 'textfield',
          'heading' => esc_html__( 'Image size', 'construction' ),
          'param_name' => 'thumb_size',
          'description' => esc_html__( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).', 'construction' )
        ),
        array(
          "type" => "dropdown",    
          "heading" => esc_html__( 'Order', 'construction' ),    
          "param_name" => "order",    
          "value" => Array (
              esc_html__("None",'construction') => "none",        
              esc_html__("ASC" ,'construction')=> "ASC",        
              esc_html__("DESC",'construction') => "DESC"
          ),
          "group" => esc_html__("Build Query", 'construction'),    
          "description" => esc_html__( 'Order ("None", "Asc", "Desc").', 'construction' )
        ),
        array(
          "type" => "textfield",
          "heading" => esc_html__('Excerpt Length', 'construction'),
          "param_name" => "excerpt_length",
          "group" => esc_html__("Template", 'construction'),
          "description" => esc_html__('Enter excerpt length.', 'construction')
        )
      )
  ) );
}

class WPBakeryShortCode_qk_blog extends WPBakeryShortCode {}

// qk_blog
if(function_exists('vc_map')){
  vc_map( array(
     "name" => esc_html__("QK Blog List","construction"),
     "base" => "qk_blog_list",
     "class" => "",
     "category" => esc_html__("QK", "construction"),
     "icon" => "icon-qk",
     "params" => array(
      array(
        "type" => "dropdown",
        "class" => "",
        "heading" => esc_html__("Template", 'construction'),
        "param_name" => "tpl",
        "value" => array(
          esc_html__("List FullWidth","construction") => "tpl1",
          esc_html__("List Thumb Type","construction") => "tpl2",
          esc_html__("List Column Type","construction") => "tpl3"
        ),
        "description" => esc_html__('Select template in this elment.', 'construction')
      ),
        array(
          "type" => "textfield",
          "holder" => "div",
          "class" => "",
          "heading" => esc_html__("Total item","construction"),
          "param_name" => "posts_per_page",
          "group" => esc_html__("Build Query", 'construction'),
          "value" => "",
          "description" => esc_html__('Set max limit for items in loop or enter -1 to display all (limited to 1000).', 'construction')
        ),
        array(
          "type" => "dropdown",
          "class" => "",
          "heading" => esc_html__("Columns", 'construction'),
          "param_name" => "columns",
          "value" => array(
              esc_html__("Please choose option ...","construction") =>"2",
              esc_html__("1 Column",'construction') => "1",
              esc_html__("2 Columns",'construction') => "2",
              esc_html__("3 Columns",'construction') => "3",
              esc_html__("4 Columns",'construction') => "4"
            ),
          "dependency" => array(
            'construction' => "tpl",
            "value" => array("tpl3")
          ),
          ),
        array(
          'type' => 'textfield',
          'heading' => esc_html__( 'Image size', 'construction' ),
          'param_name' => 'thumb_size',
          'description' => esc_html__( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).', 'construction' )
        ),
        array(
          "type" => "checkbox",
          "heading" => esc_html__('Show Read More', 'construction'),
          "param_name" => "show_readmore",
          "value" => array(
            esc_html__("Yes, please", 'construction') => true
          ),
          "description" => esc_html__('Show or hide view more button of post on your category.', 'construction')
        ),
        array(
          "type" => "checkbox",
          "class" => "",
          "heading" => esc_html__("Show Pagination", 'construction'),
          "param_name" => "show_pagination",
          "value" => array (
            esc_html__( "Yes, please", 'construction' ) => true
          ),
          "description" => esc_html__("Show or not show pagination in this element.", 'construction')
        ),
        array(
          "type" => "textfield",
          "heading" => esc_html__('Excerpt Length', 'construction'),
          "param_name" => "excerpt_length",
          "description" => esc_html__('Enter excerpt length.', 'construction')
        ),
        array(
          "type" => "textfield",
          "heading" => esc_html__('Read More Text', 'construction'),
          "param_name" => "readmore_text",
          "dependency" => array(
            'construction' => "show_readmore",
            "not_empty" => true
          ),
          "description" => esc_html__('Enter View more text.', 'construction')
        ),
        array(
          "type" => "dropdown",    
          "heading" => esc_html__( 'Order by', 'construction' ),    
          "param_name" => "orderby",    
          "value" => array (
            esc_html__( "None",'construction') => "none",
            esc_html__("Title",'construction') => "title",
            esc_html__("Date",'construction') => "date",
            esc_html__("ID" ,'construction')=> "ID"
          ),
          "group" => esc_html__("Build Query", 'construction'),    
          "description" => esc_html__( 'Order by ("none", "title", "date", "ID").', 'construction' )
        ),
        array(
          "type" => "dropdown",    
          "heading" => esc_html__( 'Order', 'construction' ),    
          "param_name" => "order",    
          "value" => Array (
              esc_html__("None",'construction') => "none",        
              esc_html__("ASC" ,'construction')=> "ASC",        
              esc_html__("DESC",'construction') => "DESC"
          ),
          "group" => esc_html__("Build Query", 'construction'),    
          "description" => esc_html__( 'Order ("None", "Asc", "Desc").', 'construction' )
        ),
        array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "",
         "heading" => esc_html__("Extra class name","construction"),
         "param_name" => "el_class",
         "value" => "",
         "description" => esc_html__('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'construction')
        )
      )
  ) );
}

class WPBakeryShortCode_qk_blog_list extends WPBakeryShortCode {}


// qk_blog
if(function_exists('vc_map')){
  vc_map( array(
     "name" => esc_html__("QK Services","construction"),
     "base" => "qk_services",
     "class" => "",
     "category" => esc_html__("QK", "construction"),
     "icon" => "icon-qk",
     "params" => array(
       array(
        "type" => "dropdown",
        "class" => "",
        "heading" => esc_html__("Template", 'construction'),
        "param_name" => "tpl",
        "value" => array(
          esc_html__("Grid Type","construction") => "grid",
          esc_html__("Slider Type","construction") => "slider",
          esc_html__("Tabs Type","construction") => "tabs"
        ),
        "description" => esc_html__('Select template in this elment.', 'construction')
      ),
      array(
        "type" => "checkbox",
        "class" => "",
        "heading" => esc_html__("Show Pagination", 'construction'),
        "param_name" => "show_pagination",
        "value" => array (
          esc_html__( "Yes, please", 'construction' ) => true
        ),
        "group" => esc_html__("Template", 'construction'),
        "description" => esc_html__("Show or not show pagination in this element.", 'construction')
      ),
      array(
          'type' => 'textfield',
          'heading' => esc_html__( 'Image size', 'construction' ),
          'param_name' => 'size',
          'description' => esc_html__( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).', 'construction' )
        ),
       array(
          "type" => "textfield",
          "holder" => "div",
          "class" => "",
          "heading" => esc_html__("Total item","construction"),
          "param_name" => "posts_per_page",
          "group" => esc_html__("Build Query", 'construction'),
          "value" => "",
          "description" => esc_html__('Set max limit for items in loop or enter -1 to display all (limited to 1000).', 'construction')
        ),
      array(
        "type" => "dropdown",
        "class" => "",
        "heading" => esc_html__("Columns", 'construction'),
        "param_name" => "columns",
        "value" => array(
            esc_html__("Please choose option ...","construction") =>"2",
            esc_html__("1 Column",'construction') => "1",
            esc_html__("2 Columns",'construction') => "2",
            esc_html__("3 Columns",'construction') => "3",
            esc_html__("4 Columns",'construction') => "4"
          )
        ),
        array(
          "type" => "checkbox",
          "heading" => esc_html__('Show Readmore', 'construction'),
          "param_name" => "show_readmore",
          "value" => array(
            esc_html__("Yes, please", 'construction') => true
          ),
          "group" => esc_html__("Template", 'construction'),
          "description" => esc_html__('Show or hide readmore of post on your category.', 'construction')
        ),
        array(
          "type" => "textfield",
          "heading" => esc_html__('Read More Text', 'construction'),
          "param_name" => "readmore_text",
          "dependency" => array(
            'construction' => "show_readmore",
            "not_empty" => true
          ),
          "group" => esc_html__("Template", 'construction'),
          "description" => esc_html__('Enter readmore text.', 'construction')
        ),
        array(
          "type" => "dropdown",    
          "heading" => esc_html__( 'Order by', 'construction' ),    
          "param_name" => "orderby",    
          "value" => array (
            esc_html__( "None",'construction') => "none",
            esc_html__("Title",'construction') => "title",
            esc_html__("Date",'construction') => "date",
            esc_html__("ID" ,'construction')=> "ID"
          ),
          "group" => esc_html__("Build Query", 'construction'),    
          "description" => esc_html__( 'Order by ("none", "title", "date", "ID").', 'construction' )
        ),
        array(
          "type" => "dropdown",    
          "heading" => esc_html__( 'Order', 'construction' ),    
          "param_name" => "order",    
          "value" => Array (
              esc_html__("None",'construction') => "none",        
              esc_html__("ASC" ,'construction')=> "ASC",        
              esc_html__("DESC",'construction') => "DESC"
          ),
          "group" => esc_html__("Build Query", 'construction'),    
          "description" => esc_html__( 'Order ("None", "Asc", "Desc").', 'construction' )
        ),
        array(
          "type" => "textfield",
          "heading" => esc_html__('Excerpt Length', 'construction'),
          "param_name" => "excerpt_length",
          "group" => esc_html__("Template", 'construction'),
          "description" => esc_html__('Enter excerpt length.', 'construction')
        )
      )
  ) );
}

class WPBakeryShortCode_qk_services extends WPBakeryShortCode {}

if(function_exists('vc_map')){
  vc_map( array(
     "name" => esc_html__("QK Link Section","construction"),
     "base" => "qk_link_section",
     "class" => "",
     "category" => esc_html__("QK", "construction"),
     "icon" => "icon-qk",
     "params" => array(
      array(
        "type" => "textfield",
       "holder" => "div",
       "class" => "",
       "heading" => esc_html__("Button name","construction"),
       "param_name" => "button_name",
        "description" =>''
      ),
      array(
        "type" => "textfield",
        "holder" => "div",
        "class" => "",
        "heading" => esc_html__("Button link","construction"),
        "param_name" => "button_link",
        "value" => "#",
        "description" =>''
      ),
      array(
        "type" => "textfield",
        "holder" => "div",
        "class" => "",
        "heading" => esc_html__("Extra class name","construction"),
        "param_name" => "el_class",
        "value" => "",
        "description" => esc_html__('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'construction')
        )
      )
  ) );
}

class WPBakeryShortCode_qk_link_section extends WPBakeryShortCode {}

if(function_exists('vc_map')){

vc_map( array(
   "name" => esc_html__("QK Button",'construction'),
   "base" => "qk_button",
   "class" => "",
   "category" => esc_html__("QK", 'construction'),
   "icon" => "icon-qk",
   "params" => array(    
    array(
       "type" => "textfield",
       "holder" => "div",
       "class" => "",
       "heading" => esc_html__("Title","construction"),
       "param_name" => "title",
       "value" => "",
       "description" => ''
    ),
    array(
       "type" => "dropdown",
       "holder" => "div",
       "class" => "",
       "heading" => esc_html__("Choose Size","construction"),
       "param_name" => "size",
       "value" => "",
       "value" => array(
        esc_html__("Please choose icon size ...","construction") =>"",
        esc_html__("Small", 'construction') => "btn-sm",
        esc_html__("Medium", 'construction') => "btn-md",
        esc_html__("Large",'construction') => "btn-lg"
      ),
      "description" => esc_html__('Choose button size or custom in Design options, default normal','construction')
    ),
    array(
      "type" => "dropdown",
      "holder" => "div",
      "class" => "",
      "heading" => esc_html__("Choose Background","construction"),
      "param_name" => "bg",
      "value" => "",
      "value" => array(
        esc_html__("Please choose icon size ...","construction") =>"",
        esc_html__("Background 1", 'construction') => "btn-bg-1",
        esc_html__("Background 2", 'construction') => "btn-bg-2",
        esc_html__("Background 3", 'construction') => "btn-bg-3",
        esc_html__("Background 4", 'construction') => "btn-bg-4"
      ),
      "description" => esc_html__('Choose button size or custom in Design options, default normal','construction')
    ),
    array(
      "type" => "textfield",
      "holder" => "div",
      "class" => "",
      "heading" => esc_html__("Link Button","construction"),
      "param_name" => "link",
      "value" => '#',
      "description" => esc_html__('Enter link','construction')
    ),
    array(
      "type" => "textfield",
      "class" => "",
      "heading" => esc_html__("Extra Class", 'construction'),
      "param_name" => "el_class",
      "value" => "",
      "description" => esc_html__("Extra Class.", 'construction')
    ),
    array(
      'type' => 'css_editor',
      'heading' => esc_html__( 'Css', 'construction' ),
      'param_name' => 'css',
      'group' => esc_html__( 'Design options', 'construction' ),
    )
   )
) );

}
class WPBakeryShortCode_qk_button extends WPBakeryShortCode {}


// qk_owlcarousel

if(function_exists('vc_map')){
vc_map( array(
   "name" => esc_html__("QK OwlCarousel","construction"),
   "base" => "qk_owlcarousel",
   "class" => "",
   "category" => esc_html__("QK", "construction"),
   "icon" => "icon-qk",
   "params" => array(
    array(
         "type" => "attach_images",
         "holder" => "div",
         "class" => "",
         "heading" => esc_html__("Attach Images","construction"),
         "param_name" => "images",
         "value" => '',
         "description" => ''
      ),
     array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "",
         "heading" => esc_html__("ID","construction"),
         "param_name" => "tb_id",
         "value" => "",
         "description" => ''
      ),
    array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "",
         "heading" => esc_html__("ID 1","construction"),
         "param_name" => "tb_id1",
         "value" => "",
         "description" => esc_html__('Carusel Thumb Items.', 'construction')
      ),
  array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "",
         "heading" => esc_html__("Image size","construction"),
         "param_name" => "img_size",
         "value" => "",
         "description" => ''
      ),
   )
) );

}
class WPBakeryShortCode_qk_owlcarousel extends WPBakeryShortCode {
}
