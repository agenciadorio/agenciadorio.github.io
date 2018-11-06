<?php  
/**
 * Create customizer fields for the kirki framework.
 * @package Kirki
 */

/* = Maps
=============================================*/
Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'toggle',
    'settings'    => 'qt_maps_include',
    'label'       => esc_attr__( 'Enable google maps script', "qt-extensions-suite" ),
    'section'     => 'qt_maps_section',
    'default'     => '0',
    'priority'    => 10
));
Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'text',
    'settings'    => 'qt_maps_api',
    'label'       => esc_attr__( 'Google maps api key', "qt-extensions-suite" ),
    'description'       => esc_attr__( 'Create your api key', "qt-extensions-suite" ).' <strong><a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">'.esc_attr__( 'CREATE KEY', "qt-extensions-suite" ).'</a></strong>',
    'section'     => 'qt_maps_section'
));



/* = Related contents
=============================================*/

Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'toggle',
    'settings'    => 'qt_related_artist',
    'label'       => esc_attr__( 'Display related artist', "qt-extensions-suite" ),
    'section'     => 'qt_related_section',
    'default'     => '1',
    'priority'    => 10
));


Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'toggle',
    'settings'    => 'qt_related_charts',
    'label'       => esc_attr__( 'Display related charts', "qt-extensions-suite" ),
    'section'     => 'qt_related_section',
    'default'     => '1',
    'priority'    => 10
));

Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'toggle',
    'settings'    => 'qt_related_members',
    'label'       => esc_attr__( 'Display related members', "qt-extensions-suite" ),
    'section'     => 'qt_related_section',
    'default'     => '1',
    'priority'    => 10
));

Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'toggle',
    'settings'    => 'qt_related_podcast',
    'label'       => esc_attr__( 'Display related podcast', "qt-extensions-suite" ),
    'section'     => 'qt_related_section',
    'default'     => '1',
    'priority'    => 10
));

Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'toggle',
    'settings'    => 'qt_related_release',
    'label'       => esc_attr__( 'Display related album releases', "qt-extensions-suite" ),
    'section'     => 'qt_related_section',
    'default'     => '1',
    'priority'    => 10
));
Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'toggle',
    'settings'    => 'qt_related_shows',
    'label'       => esc_attr__( 'Display related shows', "qt-extensions-suite" ),
    'section'     => 'qt_related_section',
    'default'     => '1',
    'priority'    => 10
));
Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'toggle',
    'settings'    => 'qt_related_schedule',
    'label'       => esc_attr__( 'Display related schedule', "qt-extensions-suite" ),
    'section'     => 'qt_related_section',
    'default'     => '1',
    'priority'    => 10
));

///// Text //////////////////////////

Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'text',
    'settings'    => 'qt_related_artist_text',
    'label'       => esc_attr__( 'Title related artist', "qt-extensions-suite" ),
    'section'     => 'qt_related_section',
    'default'     => __('You may also like',"qt-extensions-suite"),
    'priority'    => 11
));

Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'text',
    'settings'    => 'qt_related_charts_text',
    'label'       => esc_attr__( 'Title related charts', "qt-extensions-suite" ),
    'section'     => 'qt_related_section',
    'default'     => __('You may also like',"qt-extensions-suite"),
    'priority'    => 11
));


Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'text',
    'settings'    => 'qt_related_members_text',
    'label'       => esc_attr__( 'Title related members', "qt-extensions-suite" ),
    'section'     => 'qt_related_section',
    'default'     => __('You may also like',"qt-extensions-suite"),
    'priority'    => 11
));

Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'text',
    'settings'    => 'qt_related_podcast_text',
    'label'       => esc_attr__( 'Title related podcast', "qt-extensions-suite" ),
    'section'     => 'qt_related_section',
    'default'     =>  __('You may also like',"qt-extensions-suite"),
    'priority'    => 11
));

Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'text',
    'settings'    => 'qt_related_release_text',
    'label'       => esc_attr__( 'Title related album releases', "qt-extensions-suite" ),
    'section'     => 'qt_related_section',
    'default'     => __('You may also like',"qt-extensions-suite"),
    'priority'    => 11
));
Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'text',
    'settings'    => 'qt_related_shows_text',
    'label'       => esc_attr__( 'Title related shows', "qt-extensions-suite" ),
    'section'     => 'qt_related_section',
    'default'     => __('You may also like',"qt-extensions-suite"),
    'priority'    => 11
));
Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'text',
    'settings'    => 'qt_related_schedule_text',
    'label'       => esc_attr__( 'Title related schedule', "qt-extensions-suite" ),
    'section'     => 'qt_related_section',
    'default'     => __('You may also like',"qt-extensions-suite"),
    'priority'    => 11
));
/* = Typography section
=============================================*/


Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'typography',
    'settings'    => 'qt_typography_text',
    'label'       => esc_attr__( 'Basic ', "qt-extensions-suite" ),
    'section'     => 'qt_typography',
    'default'     => array(
        'font-family'    => 'Open Sans',
        'variant'        => 'regular',
        'font-size'      => '18px',
      /*  'line-height'    => '1.5',*/
        /*'letter-spacing' => '0.01em',*/
        'subsets'        => array( 'latin-ext' ),
    ),
    'priority'    => 10,
    'output'      => array(
        array(
            'element' => 'body',
            'property' => 'font-family'
        ),
    ),
) );

Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'typography',
    'settings'    => 'qt_typography_text_bold',
    'label'       => esc_attr__( 'Bold texts ', "qt-extensions-suite" ),
    'section'     => 'qt_typography',
    'default'     => array(
        'font-family'    => 'Open Sans',
        'variant'        => '700',
        // 'font-size'      => '18px',
      /*  'line-height'    => '1.5',*/
        /*'letter-spacing' => '0.01em',*/
        'subsets'        => array( 'latin-ext' ),
    ),
    'priority'    => 10
    // ,
    // 'output'      => array(
    //     array(
    //         'element' => ' .tagcloud, .qt-tags, .widget .tagcloud',
    //         'property' => 'font-family'
    //     ),
    // ),
) );



Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'typography',
    'settings'    => 'qt_typography_headings',
    'label'       => esc_attr__( 'Headings', "qt-extensions-suite" ),
    'section'     => 'qt_typography',
    'default'     => array(
        'font-family'    => 'Montserrat',
        'variant'        => '700',
        'letter-spacing' => '0.05em',
        'subsets'        => array( 'latin-ext' ),
        'text-transform' => 'uppercase'
    ),
    'priority'    => 10,
    'output'      => array(
        array(
            'element' => 'h1, h2, h3, h4, h5, h6',
            'property' => 'font-family'
        ),
    ),
) );


Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'typography',
    'settings'    => 'qt_typography_menu',
    'label'       => esc_attr__( 'Menu', "qt-extensions-suite" ),
    'section'     => 'qt_typography',
    'default'     => array(
        'font-family'    => 'Montserrat',
        'variant'        => '700',
        'font-size'      => '14px',
        'letter-spacing' => '0.2em',
        'subsets'        => array( 'latin-ext' ),
        'text-transform' => 'uppercase',
    ),
    'priority'    => 10,
    'output'      => array(
        array(
            'element' => '.dropDownMenu, #mega-menu-wrap-primary #mega-menu-primary > li.mega-menu-item > a.mega-menu-link, nav ul a, nav .qw-nav-desktop ul a',
            'property' => 'font-family'
        ),
    ),
) );




/* = Colors section
=============================================*/





Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'color',
    'settings'    => 'qt_color_main_dark',
    'label'       => esc_attr__( 'Page background', "qt-extensions-suite" ),
    'section'     => 'qt_colors_section',
    'default'     => '#24242D',
    'priority'    => 0
));

Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'color',
    'settings'    => 'qt_menu_background',
    'label'       => esc_attr__( 'Menu background', "qt-extensions-suite" ),
    'section'     => 'qt_colors_section',
    'default'     => 'transparent',
    'priority'    => 0,
    'alpha'       => true
));


Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'color',
    'settings'    => 'qt_color_main_light',
    'label'       => esc_attr__( 'Secondary color', "qt-extensions-suite" ),
    'section'     => 'qt_colors_section',
    'default'     => '#979eb2',
    'priority'    => 10
));


/*
Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'color',
    'settings'    => 'qt_color_titles',
    'label'       => esc_attr__( 'Titles', "qt-extensions-suite" ),
    'section'     => 'qt_colors_section',
    'default'     => '#ffffff',
    'priority'    => 10,
     'output'      => array(
        array(
            'element' => 'h1, h2, h3, h4, h5, h6, h2 > a',
            'property' => 'color'
        ),
    ),
));*/

Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'color',
    'settings'    => 'qt_color_text',
    'label'       => esc_attr__( 'Text', "qt-extensions-suite" ),
    'section'     => 'qt_colors_section',
    'default'     => '#ccd0d1',
    'priority'    => 10,

));



Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'color',
    'settings'    => 'qt_color_accent',
    'label'       => esc_attr__( 'Accent color', "qt-extensions-suite" ),
    'section'     => 'qt_colors_section',
    'default'     => '#de2341',
    'priority'    => 10
   
));

Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'color',
    'settings'    => 'qt_color_accent_dark',
    'label'       => esc_attr__( 'Accent color hover', "qt-extensions-suite" ),
    'section'     => 'qt_colors_section',
    'default'     => '#ca002a',
    'priority'    => 10
));


Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'color',
    'settings'    => 'qt_color_on_accent',
    'label'       => esc_attr__( 'Text color on accent background', "qt-extensions-suite" ),
    'section'     => 'qt_colors_section',
    'default'     => '#ffffff',
    'priority'    => 10
));

Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'color',
    'settings'    => 'qt_color_darker_mid',
    'label'       => esc_attr__( 'Background dark (submenu)', "qt-extensions-suite" ),
    'section'     => 'qt_colors_section',
    'default'     => '#30343d',
    'priority'    => 10
));


Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'color',
    'settings'    => 'qt_color_darker',
    'label'       => esc_attr__( 'Background darker', "qt-extensions-suite" ),
    'section'     => 'qt_colors_section',
    'default'     => '#121212',
    'priority'    => 10
));



Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'color',
    'settings'    => 'qt_bg_footer_widgets',
    'label'       => esc_attr__( 'Footer widgets background', "qt-extensions-suite" ),
    'section'     => 'qt_colors_section',
    'default'     => '#121212',
    'priority'    => 10
));

Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'color',
    'settings'    => 'qt_color_footer_widgets',
    'label'       => esc_attr__( 'Footer widgets text', "qt-extensions-suite" ),
    'section'     => 'qt_colors_section',
    'default'     => '#cdcdcd',
    'priority'    => 10
));

Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'color',
    'settings'    => 'footer_bg_color',
    'label'       => esc_attr__( 'Footer background', "qt-extensions-suite" ),
    'section'     => 'qt_colors_section',
    'default'     => '#101010',
    'priority'    => 10
));


Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'color',
    'settings'    => 'qt_color_footer',
    'label'       => esc_attr__( 'Footer text', "qt-extensions-suite" ),
    'section'     => 'qt_colors_section',
    'default'     => '#dedede',
    'priority'    => 10
));


Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'color',
    'settings'    => 'qt_bg_preloader',
    'label'       => esc_attr__( 'Preloader background', "qt-extensions-suite" ),
    'section'     => 'qt_colors_section',
    'default'     => '#00c489',
    'priority'    => 10
   
));

Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'color',
    'settings'    => 'qt_background_related',
    'label'       => esc_attr__( 'Related section background', "qt-extensions-suite" ),
    'section'     => 'qt_colors_section',
    'default'     => '#0c0c0c',
    'priority'    => 10
   
));

/* = Header section
=============================================*/
Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'toggle',
    'settings'    => 'qt_sticky_menu',
    'label'       => esc_attr__( 'Sticky menu', "qt-extensions-suite" ),
    'section'     => 'qt_header_section',
    'default'     => '0',
    'priority'    => 10
));
Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'toggle',
    'settings'    => 'qt_menu_container',
    'label'       => esc_attr__( 'Menu container', "qt-extensions-suite" ),
    'section'     => 'qt_header_section',
    'default'     => '0',
    'priority'    => 10
));

Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'image',
    'settings'    => 'qt_logo_header',
    'label'       => esc_attr__( 'Logo header', "qt-extensions-suite" ),
    'section'     => 'qt_header_section',
    'description' => esc_attr__( 'Upload logo hi res (height: 140px)', "qt-extensions-suite" ),
    'priority'    => 10
));



Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'toggle',
    'settings'    => 'qt_breadcrumb',
    'label'       => esc_attr__( 'Display navigation breadcrumb', "qt-extensions-suite" ),
    'section'     => 'qt_header_section',
    'default'     => '1',
    'priority'    => 10
));

Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'toggle',
    'settings'    => 'qt_socialicons',
    'label'       => esc_attr__( 'Display social icons', "qt-extensions-suite" ),
    'section'     => 'qt_header_section',
    'default'     => '1',
    'priority'    => 10
));


Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'image',
    'settings'    => 'qt_header_backgroundimage',
    'label'       => esc_attr__( 'Default header background image', "qt-extensions-suite" ),
    'section'     => 'qt_header_section',
    'description' => esc_attr__( 'JPG 1600x500px', "qt-extensions-suite" ),
    'priority'    => 11
));

Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'slider',
    'settings'    => 'qt_header_backgroundimage_opacity',
    'label'       => esc_attr__( 'Header background image opaticy', "qt-extensions-suite" ),
    'section'     => 'qt_header_section',
    'default'     => '60',
    'priority'    => 12,
    'choices'     => array(
        'min'  => '0',
        'max'  => '100',
        'step' => '1',
    ),
));







Kirki::add_field( 'qt_config', array(
    'type'        => 'switch',
    'settings'    => 'qt_headerbutton_radio',
    'label'       => __( 'Volume controls and radio channels', "qt-extensions-suite" ),
    'section'     => 'qt_header_section',
    'default'     => '0',
    'priority'    => 90,
    'choices'     => array(
        '1'  => esc_attr__( 'Enable', "qt-extensions-suite" ),
        '0' => esc_attr__( 'Disable', "qt-extensions-suite" )
    )
) );


Kirki::add_field( 'qt_config', array(
    'type'        => 'switch',
    'settings'    => 'qt_headerbutton_widgets',
    'label'       => __( 'Widgets button', "qt-extensions-suite" ),
    'section'     => 'qt_header_section',
    'default'     => '0',
    'priority'    => 90,
    'choices'     => array(
        '1'  => esc_attr__( 'Enable', "qt-extensions-suite" ),
        '0' => esc_attr__( 'Disable', "qt-extensions-suite" )
    )
) );

Kirki::add_field( 'qt_config', array(
    'type'        => 'switch',
    'settings'    => 'qt_headerbutton_share',
    'label'       => __( 'Share button', "qt-extensions-suite" ),
    'section'     => 'qt_header_section',
    'default'     => '1',
    'priority'    => 90,
    'choices'     => array(
        '1'  => esc_attr__( 'Enable', "qt-extensions-suite" ),
        '0' => esc_attr__( 'Disable', "qt-extensions-suite" )
    )
) );

Kirki::add_field( 'qt_config', array(
    'type'        => 'switch',
    'settings'    => 'qt_headerbutton_search',
    'label'       => __( 'Search button', "qt-extensions-suite" ),
    'section'     => 'qt_header_section',
    'default'     => '1',
    'priority'    => 90,
    'choices'     => array(
        '1'  => esc_attr__( 'Enable', "qt-extensions-suite" ),
        '0' => esc_attr__( 'Disable', "qt-extensions-suite" )
    )
) );

Kirki::add_field( 'qt_config', array(
    'type'        => 'switch',
    'settings'    => 'qt_headerbutton_cart',
    'label'       => __( 'Cart button', "qt-extensions-suite" ),
    'section'     => 'qt_header_section',
    'default'     => '1',
    'priority'    => 90,
    'choices'     => array(
        '1'  => esc_attr__( 'Enable', "qt-extensions-suite" ),
        '0' => esc_attr__( 'Disable', "qt-extensions-suite" )
    )
) );


/* = Radio player section
=============================================*/
Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'toggle',
    'settings'    => 'qt_radioplayer_autoplay',
    'label'       => esc_attr__( 'Autoplay', "qt-extensions-suite" ),
    'section'     => 'qt_radioplayer_section',
    'default'     => '1',
    'priority'    => 10
));


Kirki::add_field( 'qt_config', array(
    'type'        => 'radio',
    'settings'    => 'QT_timing_settings',
    'label'       => __( 'Time format for schedule', "qt-extensions-suite" ),
    'section'     => 'qt_radioplayer_section',
    'default'     => '12',
    'priority'    => 10,
    'choices'     => array(
        '24'  => esc_attr__( '24h format', 'qthemestd' ),
        '12' => esc_attr__( '12h format', 'qthemestd' )
    )
) );










/* = Footer section
=============================================*/
Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'text',
    'settings'    => 'qt_footer_text',
    'label'       => esc_attr__( 'Footer text', "qt-extensions-suite" ),
    'section'     => 'qt_footer_section',
    'default'     => 'Copyright 2016 <a href="http://qantumthemes.com">QantumThemes.com</a>',
    'priority'    => 10
));
Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'toggle',
    'settings'    => 'qt_footer_arrow',
    'label'       => esc_attr__( 'Show top arrow', "qt-extensions-suite" ),
    'section'     => 'qt_footer_section',
    'default'     => '1',
    'priority'    => 10
));
Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'toggle',
    'settings'    => 'qt_footer_widgets',
    'label'       => esc_attr__( 'Show footer widgets', "qt-extensions-suite" ),
    'section'     => 'qt_footer_section',
    'default'     => '1',
    'priority'    => 11
));


/*
Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'toggle',
    'settings'    => 'qt_footer_particles',
    'label'       => esc_attr__( 'Particles animation', "qt-extensions-suite" ),
    'section'     => 'qt_footer_section',
    'default'     => '0',
    'priority'    => 11
));
Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'toggle',
    'settings'    => 'qt_footer_particles_connection',
    'label'       => esc_attr__( 'Particles line connections', "qt-extensions-suite" ),
    'section'     => 'qt_footer_section',
    'default'     => '1',
    'priority'    => 11
));


Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'color',
    'settings'    => 'qt_footer_particles_color',
    'label'       => esc_attr__( 'Particles color', "qt-extensions-suite" ),
    'section'     => 'qt_footer_section',
    'default'     => '#FFFFFF',
    'priority'    => 11
));

Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'text',
    'settings'    => 'qt_footer_particles_opacity',
    'label'       => esc_attr__( 'Particles opacity', "qt-extensions-suite" ),
    'description' => 'decimal number',
    'section'     => 'qt_footer_section',
    'default'     => '0.2',
    'priority'    => 11
));
*/



/* = Posts section
=============================================*/

Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'radio',
    'settings'    => 'qt_archive_layout',
    'label'       => esc_attr__( 'Default archives layout', "qt-extensions-suite" ),
    'section'     => 'qt_post_section',
    'default'     => 'full',
    'priority'    => 10,
    'choices'     => array(
            'full' => esc_attr__( 'One Column', "qt-extensions-suite" ),
            'list' => esc_attr__( 'Post List', "qt-extensions-suite" ),
            'masonry' => esc_attr__( 'Masonry Grid', "qt-extensions-suite" )
        )
    
));



Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'toggle',
    'settings'    => 'qt_display_postauthor',
    'label'       => esc_attr__( 'Display post author data', "qt-extensions-suite" ),
    'section'     => 'qt_post_section',
    'default'     => '1',
    'priority'    => 10
));


$default_image = get_option("qt_display_postauthor", get_template_directory_uri() . '/assets/img/default.jpg' );
Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'image',
    'settings'    => 'qt_default_post_image',
    'label'       => esc_attr__( 'Single post default picture', "qt-extensions-suite" ),
    'section'     => 'qt_post_section',
    'description' => __( 'Upload a default image for posts', "qt-extensions-suite" ),
    'default'     => esc_attr($default_image),
    'priority'    => 10
));

$default_image_archive = get_option("qt_display_postauthor", get_template_directory_uri() . '/assets/img/default_archive.jpg' );
Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'image',
    'settings'    => 'qt_default_archive_image',
    'label'       => esc_attr__( 'Archives default picture', "qt-extensions-suite" ),
    'section'     => 'qt_post_section',
    'description' => __( 'Upload a default image for archives headers', "qt-extensions-suite" ),
    'default'     => esc_attr($default_image),
    'priority'    => 10
));

/* = Social section
=============================================*/


Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'text',
    'settings'    => 'qt_twitter_username', // Used for sharing functions
    'label'       => esc_attr__( 'Twitter username (e.g. QantumThemes)', "qt-extensions-suite" ),
    'section'     => 'qt_social_section',
    'default'     => 'QantumThemes',
    'priority'    => 10
));


Kirki2_Kirki::add_field( 'qt_config', array( 'settings' => 'qt_beatport', 'type' => 'text', 'label' => esc_attr__( 'Beatport', "qt-extensions-suite" ), 'section' => 'qt_social_section',));
Kirki2_Kirki::add_field( 'qt_config', array( 'settings' => 'qt_amazon', 'type' => 'text', 'label' => esc_attr__( 'Amazon', "qt-extensions-suite" ), 'section' => 'qt_social_section',));
Kirki2_Kirki::add_field( 'qt_config', array( 'settings' => 'qt_facebook', 'type' => 'text', 'label' => esc_attr__( 'Facebook', "qt-extensions-suite" ), 'section' => 'qt_social_section',));
Kirki2_Kirki::add_field( 'qt_config', array( 'settings' => 'qt_blogger', 'type' => 'text', 'label' => esc_attr__( 'Blogger', "qt-extensions-suite" ), 'section' => 'qt_social_section',));
Kirki2_Kirki::add_field( 'qt_config', array( 'settings' => 'qt_behance', 'type' => 'text', 'label' => esc_attr__( 'Behance', "qt-extensions-suite" ), 'section' => 'qt_social_section',));
Kirki2_Kirki::add_field( 'qt_config', array( 'settings' => 'qt_bebo', 'type' => 'text', 'label' => esc_attr__( 'Bebo', "qt-extensions-suite" ), 'section' => 'qt_social_section',));
Kirki2_Kirki::add_field( 'qt_config', array( 'settings' => 'qt_flickr', 'type' => 'text', 'label' => esc_attr__( 'Flickr', "qt-extensions-suite" ), 'section' => 'qt_social_section',));
Kirki2_Kirki::add_field( 'qt_config', array( 'settings' => 'qt_pinterest', 'type' => 'text', 'label' => esc_attr__( 'Pinterest', "qt-extensions-suite" ), 'section' => 'qt_social_section',));
Kirki2_Kirki::add_field( 'qt_config', array( 'settings' => 'qt_rss', 'type' => 'text', 'label' => esc_attr__( 'RSS', "qt-extensions-suite" ), 'section' => 'qt_social_section',));
Kirki2_Kirki::add_field( 'qt_config', array( 'settings' => 'qt_triplevision', 'type' => 'text', 'label' => esc_attr__( 'Triplevision', "qt-extensions-suite" ), 'section' => 'qt_social_section',));
Kirki2_Kirki::add_field( 'qt_config', array( 'settings' => 'qt_tumblr', 'type' => 'text', 'label' => esc_attr__( 'Tumblr', "qt-extensions-suite" ), 'section' => 'qt_social_section',));
Kirki2_Kirki::add_field( 'qt_config', array( 'settings' => 'qt_twitter', 'type' => 'text', 'label' => esc_attr__( 'Twitter', "qt-extensions-suite" ), 'section' => 'qt_social_section',));
Kirki2_Kirki::add_field( 'qt_config', array( 'settings' => 'qt_vimeo', 'type' => 'text', 'label' => esc_attr__( 'Vimeo', "qt-extensions-suite" ), 'section' => 'qt_social_section',));
Kirki2_Kirki::add_field( 'qt_config', array( 'settings' => 'qt_wordpress', 'type' => 'text', 'label' => esc_attr__( 'Wordpress', "qt-extensions-suite" ), 'section' => 'qt_social_section',));
Kirki2_Kirki::add_field( 'qt_config', array( 'settings' => 'qt_whatpeopleplay', 'type' => 'text', 'label' => esc_attr__( 'Whatpeopleplay', "qt-extensions-suite" ), 'section' => 'qt_social_section',));
Kirki2_Kirki::add_field( 'qt_config', array( 'settings' => 'qt_youtube', 'type' => 'text', 'label' => esc_attr__( 'Youtube', "qt-extensions-suite" ), 'section' => 'qt_social_section',));
Kirki2_Kirki::add_field( 'qt_config', array( 'settings' => 'qt_instagram', 'type' => 'text', 'label' => esc_attr__( 'Instagram', "qt-extensions-suite" ), 'section' => 'qt_social_section',));
Kirki2_Kirki::add_field( 'qt_config', array( 'settings' => 'qt_soundcloud', 'type' => 'text', 'label' => esc_attr__( 'Soundcloud', "qt-extensions-suite" ), 'section' => 'qt_social_section',));
Kirki2_Kirki::add_field( 'qt_config', array( 'settings' => 'qt_space', 'type' => 'text', 'label' => esc_attr__( 'Myspace', "qt-extensions-suite" ), 'section' => 'qt_social_section',));
Kirki2_Kirki::add_field( 'qt_config', array( 'settings' => 'qt_googleplus', 'type' => 'text', 'label' => esc_attr__( 'Googleplus', "qt-extensions-suite" ), 'section' => 'qt_social_section',));
Kirki2_Kirki::add_field( 'qt_config', array( 'settings' => 'qt_itunes', 'type' => 'text', 'label' => esc_attr__( 'Itunes', "qt-extensions-suite" ), 'section' => 'qt_social_section',));
Kirki2_Kirki::add_field( 'qt_config', array( 'settings' => 'qt_juno', 'type' => 'text', 'label' => esc_attr__( 'Juno', "qt-extensions-suite" ), 'section' => 'qt_social_section',));
Kirki2_Kirki::add_field( 'qt_config', array( 'settings' => 'qt_lastfm', 'type' => 'text', 'label' => esc_attr__( 'Lastfm', "qt-extensions-suite" ), 'section' => 'qt_social_section',));
Kirki2_Kirki::add_field( 'qt_config', array( 'settings' => 'qt_linkedin', 'type' => 'text', 'label' => esc_attr__( 'Linkedin', "qt-extensions-suite" ), 'section' => 'qt_social_section',));
Kirki2_Kirki::add_field( 'qt_config', array( 'settings' => 'qt_mixcloud', 'type' => 'text', 'label' => esc_attr__( 'Mixcloud', "qt-extensions-suite" ), 'section' => 'qt_social_section',));
Kirki2_Kirki::add_field( 'qt_config', array( 'settings' => 'qt_resident-advisor', 'type' => 'text', 'label' => esc_attr__( 'Resident-advisor', "qt-extensions-suite" ), 'section' => 'qt_social_section',));
Kirki2_Kirki::add_field( 'qt_config', array( 'settings' => 'qt_reverbnation', 'type' => 'text', 'label' => esc_attr__( 'Reverbnation', "qt-extensions-suite" ), 'section' => 'qt_social_section',));



/* = Pagination section
=============================================*/

Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'radio',
    'settings'    => 'qt_archive_pagination',
    'label'       => esc_attr__( 'Post archives pagination', "qt-extensions-suite" ),
    'section'     => 'qt_pagination_section',
    'default'     => 'loadmore',
    'priority'    => 0,
    'choices'     => array(
            'loadmore' => esc_attr__( 'Load more button', "qt-extensions-suite" ),
            'pagination' => esc_attr__( 'Pagination links', "qt-extensions-suite" )
        )
    
));

Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'radio',
    'settings'    => 'qt_masonry_pagination',
    'label'       => esc_attr__( 'Masonry archives pagination', "qt-extensions-suite" ),
    'section'     => 'qt_pagination_section',
    'description'  => 'Overrides pagination choice for all masonry-style archives',
    'default'     => 'loadmore',
    'priority'    => 0,
    'choices'     => array(
            'loadmore' => esc_attr__( 'Load more button', "qt-extensions-suite" ),
            'pagination' => esc_attr__( 'Pagination links', "qt-extensions-suite" )
        )
    
));


Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'radio',
    'settings'    => 'qt_artist_pagination',
    'label'       => esc_attr__( 'Artists pagination', "qt-extensions-suite" ),
    'section'     => 'qt_pagination_section',
    'default'     => 'loadmore',
    'priority'    => 10,
    'choices'     => array(
            'loadmore' => esc_attr__( 'Load more button', "qt-extensions-suite" ),
            'pagination' => esc_attr__( 'Pagination links', "qt-extensions-suite" )
        )
    
));

Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'radio',
    'settings'    => 'qt_release_pagination',
    'label'       => esc_attr__( 'Album releases pagination', "qt-extensions-suite" ),
    'section'     => 'qt_pagination_section',
    'default'     => 'loadmore',
    'priority'    => 10,
    'choices'     => array(
            'loadmore' => esc_attr__( 'Load more button', "qt-extensions-suite" ),
            'pagination' => esc_attr__( 'Pagination links', "qt-extensions-suite" )
        )
));

Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'radio',
    'settings'    => 'qt_podcast_pagination',
    'label'       => esc_attr__( 'Podcast pagination', "qt-extensions-suite" ),
    'section'     => 'qt_pagination_section',
    'default'     => 'loadmore',
    'priority'    => 10,
    'choices'     => array(
            'loadmore' => esc_attr__( 'Load more button', "qt-extensions-suite" ),
            'pagination' => esc_attr__( 'Pagination links', "qt-extensions-suite" )
        )
));

Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'radio',
    'settings'    => 'qt_event_pagination',
    'label'       => esc_attr__( 'Events pagination', "qt-extensions-suite" ),
    'section'     => 'qt_pagination_section',
    'default'     => 'loadmore',
    'priority'    => 10,
    'choices'     => array(
            'loadmore' => esc_attr__( 'Load more button', "qt-extensions-suite" ),
            'pagination' => esc_attr__( 'Pagination links', "qt-extensions-suite" )
        )
));

Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'radio',
    'settings'    => 'qt_members_pagination',
    'label'       => esc_attr__( 'Members pagination', "qt-extensions-suite" ),
    'section'     => 'qt_pagination_section',
    'default'     => 'loadmore',
    'priority'    => 10,
    'choices'     => array(
            'loadmore' => esc_attr__( 'Load more button', "qt-extensions-suite" ),
            'pagination' => esc_attr__( 'Pagination links', "qt-extensions-suite" )
        )
));

Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'radio',
    'settings'    => 'qt_mediagallery_pagination',
    'label'       => esc_attr__( 'Galleries pagination', "qt-extensions-suite" ),
    'section'     => 'qt_pagination_section',
    'default'     => 'loadmore',
    'priority'    => 10,
    'choices'     => array(
            'loadmore' => esc_attr__( 'Load more button', "qt-extensions-suite" ),
            'pagination' => esc_attr__( 'Pagination links', "qt-extensions-suite" )
        )
));

Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'radio',
    'settings'    => 'qt_place_pagination',
    'label'       => esc_attr__( 'Places pagination', "qt-extensions-suite" ),
    'section'     => 'qt_pagination_section',
    'default'     => 'loadmore',
    'priority'    => 10,
    'choices'     => array(
            'loadmore' => esc_attr__( 'Load more button', "qt-extensions-suite" ),
            'pagination' => esc_attr__( 'Pagination links', "qt-extensions-suite" )
        )
));


Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'radio',
    'settings'    => 'qt_radiochannel_pagination',
    'label'       => esc_attr__( 'Radio channels pagination', "qt-extensions-suite" ),
    'section'     => 'qt_pagination_section',
    'default'     => 'loadmore',
    'priority'    => 10,
    'choices'     => array(
            'loadmore' => esc_attr__( 'Load more button', "qt-extensions-suite" ),
            'pagination' => esc_attr__( 'Pagination links', "qt-extensions-suite" )
        )
));

Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'radio',
    'settings'    => 'qt_schedule_pagination',
    'label'       => esc_attr__( 'Radio schedule pagination', "qt-extensions-suite" ),
    'section'     => 'qt_pagination_section',
    'default'     => 'loadmore',
    'priority'    => 10,
    'choices'     => array(
            'loadmore' => esc_attr__( 'Load more button', "qt-extensions-suite" ),
            'pagination' => esc_attr__( 'Pagination links', "qt-extensions-suite" )
        )
));

/*
Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'radio',
    'settings'    => 'qt_chart_pagination',
    'label'       => esc_attr__( 'Charts pagination', "qt-extensions-suite" ),
    'section'     => 'qt_pagination_section',
    'default'     => 'loadmore',
    'priority'    => 10,
    'choices'     => array(
            'loadmore' => esc_attr__( 'Load more button', "qt-extensions-suite" ),
            'pagination' => esc_attr__( 'Pagination links', "qt-extensions-suite" )
        )
));*/



/* = Releases section
=============================================*/


$default_image = get_option("qt_display_postauthor", get_template_directory_uri() . '/assets/img/default-release.jpg' );
Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'image',
    'settings'    => 'qt_default_release_image',
    'label'       => esc_attr__( 'Default featured image for releases', "qt-extensions-suite" ),
    'section'     => 'qt_release_section',
    'description' => esc_attr__( 'Upload a default image for releases (600x600px)', "qt-extensions-suite" ),
    'default'     => esc_attr($default_image),
    'priority'    => 10
));


/* = Podcast section
=============================================*/


$default_image = get_option("qt_display_postauthor", get_template_directory_uri() . '/assets/img/default-podcast.jpg' );
Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'image',
    'settings'    => 'qt_default_podcast_image',
    'label'       => esc_attr__( 'Default featured image for podcast', "qt-extensions-suite" ),
    'section'     => 'qt_podcast_section',
    'description' => esc_attr__( 'Upload a default image for podcast (600x600px)', "qt-extensions-suite" ),
    'default'     => esc_attr($default_image),
    'priority'    => 10
));


/* = Events section
=============================================*/

Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'radio',
    'settings'    => 'qt_events_hideold',
    'label'       => esc_attr__( 'Hide past events', "qt-extensions-suite" ),
    'section'     => 'qt_revent_section',
    'description' => esc_attr__( 'Based on the event date attribute', "qt-extensions-suite" ),
    'default'     => '0',
    'priority'    => 10,
    'choices'     => array(
            '0' => esc_attr__( 'No: show past events', "qt-extensions-suite" ),
            '1' => esc_attr__( 'Yes: hide past events', "qt-extensions-suite" )
        )
    
));


/* = Advanced settings
=============================================*/


Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'text',
    'settings'    => 'qt_container_width', // Used for sharing functions
    'label'       => esc_attr__( 'Container max width', "qt-extensions-suite" ),
    'section'     => 'qt_advanced_section',
    'description' => esc_attr__( 'Maximum width of the container. Specify measure unit like 1250px or 80%', "qt-extensions-suite" ),
    'default'     => '1250px',
    'priority'    => 0
));

Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'toggle',
    'settings'    => 'qt_enable_debug',
    'label'       => esc_attr__( 'Enable debug settings', "qt-extensions-suite" ),
    'description' => esc_attr__( 'Load separated JS instead of minified version and enable console output for javascript. Use in case of issues or custom theme versions', "qt-extensions-suite" ),
    'section'     => 'qt_advanced_section',
    'default'     => 0,
    'priority'    => 100
));

Kirki2_Kirki::add_field( 'qt_config', array(
    'type'        => 'toggle',
    'settings'    => 'qt_page_preloader',
    'label'       => esc_attr__( 'Preloader', "qt-extensions-suite" ),
    'description' => esc_attr__( 'Preload and unload the page with fading. Useful if your pages appear smashed when opening', "qt-extensions-suite" ),
    'section'     => 'qt_advanced_section',
    'default'     => "1",
    'priority'    => 10
));
