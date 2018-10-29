<?php

    /**
     * ReduxFramework Barebones Sample Config File
     * For full documentation, please visit: http://docs.reduxframework.com/
     */

    if ( ! class_exists( 'Redux' ) ) {
        return;
    }

    // This is your option name where all the Redux data is stored.
    $opt_name = "construction_theme_options";
    
    /**
     * ---> SET ARGUMENTS
     * All the possible arguments for Redux.
     * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
     * */

    $theme = wp_get_theme(); // For use with some settings. Not necessary.

    $args = array(
        // TYPICAL -> Change these values as you need/desire
        'opt_name'             => $opt_name,
        // This is where your data is stored in the database and also becomes your global variable name.
        'display_name'         => $theme->get( 'Name' ),
        // Name that appears at the top of your panel
        'display_version'      => $theme->get( 'Version' ),
        // Version that appears at the top of your panel
        'menu_type'            => 'menu',
        //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
        'allow_sub_menu'       => true,
        // Show the sections below the admin menu item or not
        'menu_title'           => esc_html__( 'Construction Options', 'construction' ),
        'page_title'           => esc_html__( 'Construction Options', 'construction' ),
        // You will need to generate a Google API key to use this feature.
        // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
        'google_api_key'       => 'AIzaSyBM9vxebWLN3bq4Urobnr6tEtn7zM06rEw',
        // Set it you want google fonts to update weekly. A google_api_key value is required.
        'google_update_weekly' => false,
        // Must be defined to add google fonts to the typography module
        'async_typography'     => true,
        // Use a asynchronous font on the front end or font string
        //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
        'admin_bar'            => true,
        // Show the panel pages on the admin bar
        'admin_bar_icon'       => 'dashicons-admin-generic',
        // Choose an icon for the admin bar menu
        'admin_bar_priority'   => 50,
        // Choose an priority for the admin bar menu
        'global_variable'      => '',
        // Set a different name for your global variable other than the opt_name
        'dev_mode'             => false,
        // Show the time the page took to load, etc
        'update_notice'        => false,
        // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
        'customizer'           => true,
        // Enable basic customizer support
        //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
        //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

        // OPTIONAL -> Give you extra features
        'page_priority'        => null,
        // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
        'page_parent'          => 'themes.php',
        // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
        'page_permissions'     => 'manage_options',
        // Permissions needed to access the options panel.
        'menu_icon'            => '',
        // Specify a custom URL to an icon
        'last_tab'             => '',
        // Force your panel to always open to a specific tab (by id)
        'page_icon'            => 'icon-themes',
        // Icon displayed in the admin panel next to your menu_title
        'page_slug'            => '',
        // Page slug used to denote the panel, will be based off page title then menu title then opt_name if not provided
        'save_defaults'        => true,
        // On load save the defaults to DB before user clicks save or not
        'default_show'         => false,
        // If true, shows the default value next to each field that is not the default value.
        'default_mark'         => '',
        // What to print by the field's title if the value shown is default. Suggested: *
        'show_import_export'   => true,
        // Shows the Import/Export panel when not used as a field.

        // CAREFUL -> These options are for advanced use only
        'transient_time'       => 60 * MINUTE_IN_SECONDS,
        'output'               => true,
        // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
        'output_tag'           => true,
        // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
        // 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.

        // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
        'database'             => '',
        // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
        'system_info'          => false,
        // REMOVE

        //'compiler'             => true,

        // HINTS
        'hints'                => array(
            'icon'          => 'el el-question-sign',
            'icon_position' => 'right',
            'icon_color'    => 'lightgray',
            'icon_size'     => 'normal',
            'tip_style'     => array(
                'color'   => 'light',
                'shadow'  => true,
                'rounded' => false,
                'style'   => '',
            ),
            'tip_position'  => array(
                'my' => 'top left',
                'at' => 'bottom right',
            ),
            'tip_effect'    => array(
                'show' => array(
                    'effect'   => 'slide',
                    'duration' => '500',
                    'event'    => 'mouseover',
                ),
                'hide' => array(
                    'effect'   => 'slide',
                    'duration' => '500',
                    'event'    => 'click mouseleave',
                ),
            ),
        )
    );

    // Panel Intro text -> before the form
    if ( ! isset( $args['global_variable'] ) || $args['global_variable'] !== false ) {
        if ( ! empty( $args['global_variable'] ) ) {
            $v = $args['global_variable'];
        } else {
            $v = str_replace( '-', '_', $args['opt_name'] );
        }
        $args['intro_text'] = sprintf( esc_html__( '<p>Did you know that Redux sets a global variable for you? To access any of your saved options from within your code you can use your global variable: <strong>$%1$s</strong></p>', 'construction' ), $v );
    } else {
        $args['intro_text'] = esc_html__( '<p>This text is displayed above the options panel. It isn\'t required, but more info is always better! The intro_text field accepts all HTML.</p>', 'construction' );
    }

    // Add content after the form.
    $args['footer_text'] = esc_html__( '<p>This text is displayed below the options panel. It isn\'t required, but more info is always better! The footer_text field accepts all HTML.</p>', 'construction' );

    Redux::setArgs( $opt_name, $args );

    /*
     * ---> END ARGUMENTS
     */

    /*
     * ---> START HELP TABS
     */

    $tabs = array(
        array(
            'id'      => 'redux-help-tab-1',
            'title'   => esc_html__( 'Theme Information 1', 'construction' ),
            'content' => esc_html__( '<p>This is the tab content, HTML is allowed.</p>', 'construction' )
        ),
        array(
            'id'      => 'redux-help-tab-2',
            'title'   => esc_html__( 'Theme Information 2', 'construction' ),
            'content' => esc_html__( '<p>This is the tab content, HTML is allowed.</p>', 'construction' )
        )
    );
    Redux::setHelpTab( $opt_name, $tabs );

    // Set the help sidebar
    $content = esc_html__( '<p>This is the sidebar content, HTML is allowed.</p>', 'construction' );
    Redux::setHelpSidebar( $opt_name, $content );


    /*
     * <--- END HELP TABS
     */
    
    
    /*
     *
     * ---> START SECTIONS
     *
     */

    /*

        As of Redux 3.5+, there is an extensive API. This API can be used in a mix/match mode allowing for


     */
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
    $lists_page = $lists_nav = array();
    foreach($pages as $p){
      $lists_page[$p->ID] = $p->post_title;
    }
    $args = array(
        'post_type' =>'nav_menu_item',
        'posts_per_page' => -1
    );
    $lists_nav = array(
        'primary'   => esc_html__( 'Main Navigation','construction' ),
        'blog_nav'   => esc_html__( 'Single Blog Menu', 'construction' ),
        'project_nav'   => esc_html__( 'Single Project Menu', 'construction' ),
        'sub_nav'   => esc_html__( 'Sub Page Menu', 'construction' )
    );

    // -> START Basic Fields
    Redux::setSection( $opt_name, array(
        'title'  => esc_html__( 'General Settings - w_p_l_o_c_k_e_r_._c_o_m', 'construction' ),
        'id'     => 'general',
        'desc'   => '',
        'icon'   => 'el el-icon-cogs',
        'fields' => array(
            array(
                'id'       => construction_get_prefix('show_preloader'),
                'type'     => 'switch',
                'title'    => esc_html__( 'Enable Preloader', 'construction' ),
                'subtitle' => '',
                'desc'     => '',
                'default'  => true
            ),
            array(
                'id' => construction_get_prefix('favicon'),
                'type' => 'media',
                'url' => true,
                'title' => esc_html__('Custom Favicon', 'construction'),
                'compiler' => 'true',
                //'mode' => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                'desc' => esc_html__('Upload your Favicon.', 'construction'),
                'subtitle' => '',
                'default' => array('url' => ''),
            ),
            array(
                'id' => construction_get_prefix('logo'),
                'type' => 'media',
                'url' => true,
                'title' => esc_html__('Logo', 'construction'),
                'compiler' => 'true',
                //'mode' => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                'desc' => esc_html__('Upload your logo.', 'construction'),
                'subtitle' => '',
                'default' => array('url' => get_template_directory_uri().'/assets/images/logo1.png'),
            ),

            array(
                'id' => construction_get_prefix('apple_icon'),
                'type' => 'media',
                'url' => true,
                'title' => esc_html__('Apple Touch Icon', 'construction'),
                'compiler' => 'true',
                //'mode' => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                'desc' => esc_html__('Upload your Apple touch icon 57x57.', 'construction'),
                'subtitle' => '',
                'default' => array('url' => ''),
            ),
            array(
                'id' => construction_get_prefix('apple_icon_57'),
                'type' => 'media',
                'url' => true,
                'title' => esc_html__('Apple Touch Icon 57x57', 'construction'),
                'compiler' => 'true',
                //'mode' => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                'desc' => esc_html__('Upload your Apple touch icon 57x57.', 'construction'),
                'subtitle' => '',
                'default' => array('url' => ''),
            ),
            array(
                'id' => construction_get_prefix('apple_icon_72'),
                'type' => 'media',
                'url' => true,
                'title' => esc_html__('Apple Touch Icon 72x72', 'construction'),
                'compiler' => 'true',
                //'mode' => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                'desc' => esc_html__('Upload your Apple touch icon 72x72.', 'construction'),
                'subtitle' => '',
                'default' => array('url' => ''),
            ),
            array(
                'id' => construction_get_prefix('apple_icon_114'),
                'type' => 'media',
                'url' => true,
                'title' => esc_html__('Apple Touch Icon 114x114', 'construction'),
                'compiler' => 'true',
                //'mode' => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                'desc' => esc_html__('Upload your Apple touch icon 114x114.', 'construction'),
                'subtitle' => '',
                'default' => array('url' => ''),
            ),
            array(
                'id' => construction_get_prefix('seo_description'),
                'type' => 'textarea',
                'title' => esc_html__('SEO Description', 'construction'),
                'subtitle' => esc_html__('', 'construction'),
                'desc' => esc_html__('', 'construction'),
                'default' => ''
            ),
            array(
                'id' => construction_get_prefix('seo_keywords'),
                'type' => 'textarea',
                'title' => esc_html__('SEO Keywords', 'construction'),
                'subtitle' => '',
                'desc' =>'',
                'default' => ''
            ),
            
        )
    ) );

    // -> START Basic Fields
    Redux::setSection( $opt_name, array(
        'title'  => esc_html__( 'Header Settings', 'construction' ),
        'id'     => 'header',
        'desc'   => '',
        'icon'   => 'el el-icon-cogs',
        'fields' => array(
            array(
                'id'       => construction_get_prefix('top_nav'),
                'type'     => 'switch',
                'title'    => esc_html__( 'Show or Hide Top Nav', 'construction' ),
                'subtitle' => '',
                'desc'     => '',
                //Must provide key => value pairs for select options
                'options'  => $lists_nav,   
                'default'  => true
            ),
            
        )
    ) );
    
    Redux::setSection( $opt_name,array(
        'icon'      => 'el el-magic',
        'title'     => esc_html__('Styling Options', 'construction'),
        'fields'    => array(
            array(
                'id' => construction_get_prefix('body-main-font'),
                'type' => 'typography',
                'output' => array('body'),
                'title' => esc_html__('Body Font', 'construction'),
                'subtitle' => esc_html__('Specify the body font properties.', 'construction'),
                'google' => true,
                'line-height' => false,
                'default' => array(
                    'color' => '#333',
                    'font-size' => '14px',
                    'font-family' => "Open Sans",
                    'font-weight' => '400',
                    'background-color' => '#ffffff'
                ),
            ),
            
            array(
                'id' => construction_get_prefix('main-color'),
                'type' => 'color',
                'title' => esc_html__('Theme main Color', 'construction'),
                'subtitle' => esc_html__('Pick theme main color (default: #ffe000).', 'construction'),
                'default' => '#ffe000',
                'validate' => 'color',
            ),
            
             array(
                'id'        => construction_get_prefix('custom-css' ),
                'type'      => 'ace_editor',
                'title'     => esc_html__('Custom CSS Code', 'construction'),
                'subtitle'  => esc_html__('Paste your CSS code here.', 'construction'),
                'mode'      => 'css',
                'theme'     => 'monokai',
                'desc'      => 'Possible modes can be found at <a href="http://ace.c9.io" target="_blank">http://ace.c9.io/</a>.',
                'default'   => "#header{\nmargin: 0 auto;\n}"
            ),
                 
           
        )
    ));
	/*Title Bar Setting*/
    Redux::setSection( $opt_name, array(
        'title'  => esc_html__( 'Page Title Bar', 'construction' ),
        'desc'   => esc_html__( '', 'construction' ),
        'icon'   => 'el-icon-livejournal',
        'fields' => array(
            array(
                'id'       => construction_get_prefix('show-title-bar'),
                'type'     => 'switch',
                'title'    => esc_html__( 'Show Title Bar', 'construction' ),
                'subtitle' => esc_html__( 'Show title bar in page.', 'construction' ),
                'default'  => true
            ),
            array(
                'id' => construction_get_prefix('title-bar-img'),
                'type' => 'media',
                'url' => true,
                'title' => esc_html__('Choose background image for title bar', 'construction'),
                'compiler' => 'true',
                //'mode' => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                'desc' => esc_html__('Upload your background image.', 'construction'),
                'subtitle' => '',
                'default' => array('url' => CONSTRUCTION_URI_ASSETS.'/images/default/default-title-bar.jpg')
            ),
            array(
                'id'       => construction_get_prefix('hide_search'),
                'type'     => 'switch',
                'title'    => esc_html__( 'Hide Search Box', 'construction' ),
                'subtitle' => esc_html__( 'Hide Search Box on right sidebar menu.', 'construction' ),
                'default'  => false,
            ),
        )
    ));
    Redux::setSection( $opt_name, array(
        'title'  => esc_html__( 'Title Bar', 'construction' ),
        'desc'   => esc_html__( '', 'construction' ),
        'icon'   => 'el-icon-livejournal',
        'subsection' => true,
        'fields' => array(
            array(
                'id'       => construction_get_prefix('show_page_title'),
                'type'     => 'switch',
                'title'    => esc_html__( 'Show Page Title', 'construction' ),
                'subtitle' => esc_html__( 'Show page title in page title bar.', 'construction' ),
                'default'  => true,
            ),
            array(
                'id'       => construction_get_prefix('show_page_breadcrumb'),
                'type'     => 'switch',
                'title'    => esc_html__( 'Show Page Breadcrumb', 'construction' ),
                'subtitle' => esc_html__( 'Show page breadcrumb in page title bar.', 'construction' ),
                'default'  => false
            ),            
            array(
                'id'       => construction_get_prefix('page_breadcrumb_delimiter'),
                'type'     => 'text',
                'title'    => esc_html__('Delimiter', 'construction'),
                'subtitle' => esc_html__('Please, Enter Delimiter of page breadcrumb in title bar.', 'construction'),
                'default'  => '/',
                'required' => array( construction_get_prefix('show_page_breadcrumb'),'=',true)
            )
        )
    ));
    Redux::setSection( $opt_name, array(
        'title'  => esc_html__( 'Single', 'construction' ),
        'desc'   => esc_html__( '', 'construction' ),
        'icon'   => 'el el-file',
        'fields' => array(
            array(
                'id'       => construction_get_prefix('show_post_info'),
                'type'     => 'switch',
                'title'    => esc_html__( 'Show Post Info (Share + Tags)', 'construction' ),
                'subtitle' => esc_html__( 'Show or not post info on your single blog.', 'construction' ),
                'default'  => true,
            ),
            array(
                'id'       => construction_get_prefix('show_post_comment'),
                'type'     => 'switch',
                'title'    => esc_html__( 'Show Post Comment', 'construction' ),
                'subtitle' => esc_html__( 'Show or not post comment on your single blog.', 'construction' ),
                'default'  => true,
            ),
            array(
                'id'       => construction_get_prefix('post_show_related'),
                'type'     => 'switch',
                'title'    => esc_html__( 'Show Related Projects', 'construction' ),
                'subtitle' => esc_html__( 'Show product related products in page single project.', 'construction' ),
                'default'  => true
            ),
            array(
                'id'       => construction_get_prefix('post-related-size'),
                'type'     => 'text',
                'title'    => esc_html__( 'Thumbnail Size', 'construction' ),
                'subtitle' => esc_html__( 'Enter thumbnail size','construction'),
                'default'  => '600x450',
                'required' => array(construction_get_prefix('post_show_related'),'=',true)
            ),
            array(
                'id'       => construction_get_prefix('post_no_related'),
                'type'     => 'text',
                'title'    => esc_html__( 'Number article on Related', 'construction' ),
                'subtitle' => esc_html__( 'Enter number projects related on your single project.', 'construction' ),
                'default'  => 3,
                'required' => array(construction_get_prefix('post_show_related'),'=',true)
            )
        )
        )
    );

    Redux::setSection( $opt_name, array(
        'title'  => esc_html__( 'Project', 'construction' ),
        'desc'   => esc_html__( '', 'construction' ),
        'icon'   => 'el el-folder'
        )
    );

    Redux::setSection( $opt_name, array(
        'title'  => esc_html__( 'Archive Project', 'construction' ),
        'desc'   => esc_html__( '', 'construction' ),
        'icon'   => 'el el-file',
        'subsection' => true,
        'fields' => array(
            array(
                'id'       => construction_get_prefix('pj-no-items'),
                'type'     => 'text',
                'title'    => esc_html__( 'Number Projects', 'construction' ),
                'subtitle' => esc_html__( 'Enter number projects related on your archive project.', 'construction' ),
                'default'  => 6
            ),
            array(
                'id'       => construction_get_prefix('pj-thumb-size'),
                'type'     => 'text',
                'title'    => esc_html__( 'Thumbnail Size', 'construction' ),
                'subtitle' => esc_html__( 'Enter thumbnail size','construction' ),
                'default'  => '600x450'
            )
        )
        )
    );

    Redux::setSection( $opt_name, array(
        'title'  => esc_html__( 'Single Project', 'construction' ),
        'desc'   => esc_html__( '', 'construction' ),
        'icon'   => 'el el-file',
        'subsection' => true,
        'fields' => array(
            array(
                'id'       => construction_get_prefix('pj_show_related'),
                'type'     => 'switch',
                'title'    => esc_html__( 'Show Related Projects', 'construction' ),
                'subtitle' => esc_html__( 'Show product related products in page single project.', 'construction' ),
                'default'  => true
            ),
            array(
                'id'       => construction_get_prefix('pj_no_related_products'),
                'type'     => 'text',
                'title'    => esc_html__( 'Number Projects on Related', 'construction' ),
                'subtitle' => esc_html__( 'Enter number projects related on your single project.', 'construction' ),
                'default'  => 3,
                'required' => array(construction_get_prefix('pj_show_related'),'=',true)
            )
        )
        )
    );

    Redux::setSection( $opt_name, array(
        'title'  => esc_html__( 'Service', 'construction' ),
        'desc'   => esc_html__( '', 'construction' ),
        'icon'   => 'el el-folder'
        )
    );

    Redux::setSection( $opt_name, array(
        'title'  => esc_html__( 'Archive Service', 'construction' ),
        'desc'   => esc_html__( '', 'construction' ),
        'icon'   => 'el el-file',
        'subsection' => true,
        'fields' => array(
            array(
                'id'       => construction_get_prefix('sv-no-items'),
                'type'     => 'text',
                'title'    => esc_html__( 'Number Services', 'construction' ),
                'subtitle' => esc_html__( 'Enter number projects related on your archive project.', 'construction' ),
                'default'  => 6
            ),
            array(
                'id'       => construction_get_prefix('sv-thumb-size'),
                'type'     => 'text',
                'title'    => esc_html__( 'Thumbnail Size', 'construction' ),
                'subtitle' => esc_html__( 'Enter thumbnail size','construction' ),
                'default'  => '600x450'
            )
        )
        )
    );

    Redux::setSection( $opt_name, array(
        'title'  => esc_html__( 'Single Service', 'construction' ),
        'desc'   => esc_html__( '', 'construction' ),
        'icon'   => 'el el-file',
        'subsection' => true,
        'fields' => array(
            array(
                'id'       => construction_get_prefix('sv_show_related'),
                'type'     => 'switch',
                'title'    => esc_html__( 'Show Related Services', 'construction' ),
                'subtitle' => esc_html__( 'Show product related products in page single project.', 'construction' ),
                'default'  => true
            ),
            array(
                'id'       => construction_get_prefix('sv_no_related'),
                'type'     => 'text',
                'title'    => esc_html__( 'Number Services on Related', 'construction' ),
                'subtitle' => esc_html__( 'Enter number projects related on your single project.', 'construction' ),
                'default'  => 5,
                'required' => array(construction_get_prefix('sv_show_related'),'=',true)
            )
        )
        )
    );

    Redux::setSection( $opt_name, array(
        'title'  => esc_html__( 'Footer Settings', 'construction' ),
        'id'     => 'footer',
        'desc'   => '',
        'icon'   => 'el el-th',
        'fields' => array(
            array(
                'id'       => construction_get_prefix('footer-widgets'),
                'type'     => 'switch',
                'title'    => esc_html__( 'Turn ON footer widget area', 'construction' ),
                'default'  => true
            ),
            array(
                'id'       => construction_get_prefix('footer_bg'),
                'type'     => 'background',
                'title'    => esc_html__('Background', 'construction'),
                'subtitle' => esc_html__('background with image, color, etc.', 'construction'),
                 'default'  => array(
                    'background-color' => '#093f9c'
                ),
                'output' => array('.wrap-footer'),
            ),
            array(
                'id' => construction_get_prefix('foter_margin'),
                'title' => 'Footer Margin',
                'subtitle' => esc_html__('Please, Enter margin of Footer.', 'construction'),
                'type' => 'spacing',
                'mode' => 'margin',
                'units' => array('px'),
                'output' => array('.footer-top'),
                'default' => array(
                    'margin-top'     => '0', 
                    'margin-right'   => '0', 
                    'margin-bottom'  => '0', 
                    'margin-left'    => '0',
                    'units'          => 'px', 
                )
            ),
            array(
                'id' => construction_get_prefix('footer_padding'),
                'title' => 'Footer Padding',
                'subtitle' => esc_html__('Please, Enter padding of Footer.', 'construction'),
                'type' => 'spacing',
                'units' => array('px'),
                'output' => array('.footer-top'),
                'default' => array(
                    'padding-top'     => '45', 
                    'padding-right'   => '0', 
                    'padding-bottom'  => '45', 
                    'padding-left'    => '0',
                    'units'          => 'px', 
                )
            ),
        )
    ) );

    Redux::setSection( $opt_name, array(
        'title'  => esc_html__( 'Footer Bottom', 'construction' ),
        'id'     => 'footer_bottom',
        'desc'   => '',
        'subsection' => true,
        'fields' => array(
            array(
                'id' => construction_get_prefix('copy_right'),
                'type' => 'editor',
                'title' => esc_html__('Footer Text', 'construction'),
                'subtitle' => esc_html__('Copyright Text', 'construction'),
                'default' => esc_html__('&copy; Construction 2016 All Rights Reserved','construction'),
            ),
            array(
                'id'       => construction_get_prefix('footer_bottom_bg'),
                'type'     => 'background',
                'title'    => esc_html__('Background Footer Copyright', 'construction'),
                'subtitle' => esc_html__('background with image, color, etc.', 'construction'),
                'default'  => array(
                    'background-color' => '#093584'
                ),
                'output' => array('.footer-bt'),
            ),
            array(
                'id' => construction_get_prefix('footer_bottom_margin'),
                'title' => 'Footer Margin',
                'subtitle' => esc_html__('Please, Enter margin of Footer.', 'construction'),
                'type' => 'spacing',
                'mode' => 'margin',
                'units' => array('px'),
                'output' => array('.footer-b'),
                'default' => array(
                    'margin-top'     => '0', 
                    'margin-right'   => '0', 
                    'margin-bottom'  => '0', 
                    'margin-left'    => '0',
                    'units'          => 'px', 
                )
            ),
            array(
                'id' => construction_get_prefix('footer_bottom_padding'),
                'title' => 'Footer Padding',
                'subtitle' => esc_html__('Please, Enter padding of Footer.', 'construction'),
                'type' => 'spacing',
                'units' => array('px'),
                'output' => array('.footer-b'),
                'default' => array(
                    'padding-top'     => '0', 
                    'padding-right'   => '0', 
                    'padding-bottom'  => '0', 
                    'padding-left'    => '0',
                    'units'          => 'px', 
                )
            ),
        )
    ) );

    // add_action("redux/options/$opt_name/settings/", "construction_remove_cache_style");

    // function construction_remove_cache_style(){

    // }
    
    /*
     * <--- END SECTIONS
     */
    

    ?>