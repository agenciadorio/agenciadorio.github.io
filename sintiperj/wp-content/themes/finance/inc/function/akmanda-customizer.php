<?php 

	/*
	*
	*	Theme Customizer Options
	*	------------------------------------------------
	*	Themes Awesome Framework
	* 	Copyright Themes Awesome 2013 - http://www.themesawesome.com
	*
	*	finance_customize_register()
	*	finance_customize_preview()
	*
	*/
	
	if (!function_exists('finance_customize_register')) {
		function finance_customize_register($wp_customize) {
		
			$wp_customize->get_setting('blogname')->transport='postMessage';
			$wp_customize->get_setting('blogdescription')->transport='postMessage';
			$wp_customize->get_setting('header_textcolor')->transport='postMessage';

			/* HEADER STYLING
			================================================== */
			
			$wp_customize->add_section( 'header_styling', array(
				'title'		=>	esc_html__( 'Header Section', 'finance' ),
				'priority'	=>	200,
			) );
			
			//SECTION

			$wp_customize->add_setting( 'top_bar_bg', array(
				'default'		=> 	'#034153',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'top_bar_text', array(
				'default'		=> 	'#ffffff',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'top_bar_icon', array(
				'default'		=> 	'#fed100',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'top_bar_btn_bg', array(
				'default'		=> 	'#fed100',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'top_bar_btn_text', array(
				'default'		=> 	'#034153',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'top_head_bg', array(
				'default'		=> 	'#ffffff',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'icon_info', array(
				'default'		=> 	'#023669',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'text_info', array(
				'default'		=> 	'#333333',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'border_nav', array(
				'default'		=> 	'#d9e3e5',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'nav_menu', array(
				'default'		=> 	'#034153',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'sub_menu_bg', array(
				'default'		=> 	'#034153',
				'type'			=> 	'option',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'sub_menu_bg_hov', array(
				'default'		=> 	'#fed100',
				'type'			=> 	'option',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'sub_menu_text', array(
				'default'		=> 	'#ffffff',
				'type'			=> 	'option',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'sub_menu_text_hov', array(
				'default'		=> 	'#034153',
				'type'			=> 	'option',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			//CONTROL
			
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'top_bar_bg', array(
				'label'		=>	esc_html__( 'Header Top Bar Background Color', 'finance' ),
				'section'	=>	'header_styling',
				'settings'	=>	'top_bar_bg',
				'priority'	=>	1,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'top_bar_text', array(
				'label'		=>	esc_html__( 'Header Top Bar Text Color', 'finance' ),
				'section'	=>	'header_styling',
				'settings'	=>	'top_bar_text',
				'priority'	=>	2,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'top_bar_icon', array(
				'label'		=>	esc_html__( 'Header Top Bar Map Icon Color', 'finance' ),
				'section'	=>	'header_styling',
				'settings'	=>	'top_bar_icon',
				'priority'	=>	3,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'top_bar_btn_bg', array(
				'label'		=>	esc_html__( 'Button Request a Quote Background Color', 'finance' ),
				'section'	=>	'header_styling',
				'settings'	=>	'top_bar_btn_bg',
				'priority'	=>	4,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'top_bar_btn_text', array(
				'label'		=>	esc_html__( 'Button Request a Quote Text Color', 'finance' ),
				'section'	=>	'header_styling',
				'settings'	=>	'top_bar_btn_text',
				'priority'	=>	5,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'top_head_bg', array(
				'label'		=>	esc_html__( 'Top Header Background Color', 'finance' ),
				'section'	=>	'header_styling',
				'settings'	=>	'top_head_bg',
				'priority'	=>	6,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'icon_info', array(
				'label'		=>	esc_html__( 'Icon Info Color', 'finance' ),
				'section'	=>	'header_styling',
				'settings'	=>	'icon_info',
				'priority'	=>	7,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'text_info', array(
				'label'		=>	esc_html__( 'Text Info Color', 'finance' ),
				'section'	=>	'header_styling',
				'settings'	=>	'text_info',
				'priority'	=>	8,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'border_nav', array(
				'label'		=>	esc_html__( 'Border Top & Bottom Color', 'finance' ),
				'section'	=>	'header_styling',
				'settings'	=>	'border_nav',
				'priority'	=>	9,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'nav_menu', array(
				'label'		=>	esc_html__( 'Navigation menu Color', 'finance' ),
				'section'	=>	'header_styling',
				'settings'	=>	'nav_menu',
				'priority'	=>	10,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sub_menu_bg', array(
				'label'		=>	esc_html__( 'Navigation SubMenu Background Color', 'finance' ),
				'section'	=>	'header_styling',
				'settings'	=>	'sub_menu_bg',
				'priority'	=>	11,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sub_menu_bg_hov', array(
				'label'		=>	esc_html__( 'Navigation SubMenu Background Hover Color', 'finance' ),
				'section'	=>	'header_styling',
				'settings'	=>	'sub_menu_bg_hov',
				'priority'	=>	12,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sub_menu_text', array(
				'label'		=>	esc_html__( 'Navigation SubMenu Text Color', 'finance' ),
				'section'	=>	'header_styling',
				'settings'	=>	'sub_menu_text',
				'priority'	=>	13,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sub_menu_text_hov', array(
				'label'		=>	esc_html__( 'Navigation SubMenu Text Hover Color', 'finance' ),
				'section'	=>	'header_styling',
				'settings'	=>	'sub_menu_text_hov',
				'priority'	=>	14,
			) ) );




			/* CONTENT STYLING
			================================================== */
			
			$wp_customize->add_section( 'content_styling', array(
				'title'		=>	esc_html__( 'Content Section', 'finance' ),
				'priority'	=>	200,
			) );
			
			//SECTION

			$wp_customize->add_setting( 'title_section', array(
				'default'		=> 	'#333333',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'title_section2', array(
				'default'		=> 	'#ffffff',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'about_section_text', array(
				'default'		=> 	'#333333',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'about_section_icon', array(
				'default'		=> 	'#034153',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'review_section_text', array(
				'default'		=> 	'#ffffff',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'bg_icon_counter', array(
				'default'		=> 	'#ffffff',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'bg_icon_counter_hover', array(
				'default'		=> 	'#fed100',
				'type'			=> 	'option',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'counter_icon', array(
				'default'		=> 	'#333333',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'counter_value', array(
				'default'		=> 	'#ffffff',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'counter_title', array(
				'default'		=> 	'#ffffff',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'testi_icon', array(
				'default'		=> 	'#f2f2f2',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'testi_bg', array(
				'default'		=> 	'#ffffff',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'testi_text', array(
				'default'		=> 	'#333333',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'testi_author', array(
				'default'		=> 	'#333333',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'author_job', array(
				'default'		=> 	'#333333',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'arrow_btn', array(
				'default'		=> 	'#fed100',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'arrow_icon', array(
				'default'		=> 	'#034153',
				'type'			=> 	'option',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'feat_bord', array(
				'default'		=> 	'#034153',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'feat_bord_hov', array(
				'default'		=> 	'#fed100',
				'type'			=> 	'option',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'feat_icon', array(
				'default'		=> 	'#034153',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'feat_title', array(
				'default'		=> 	'#333333',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'feat_desc', array(
				'default'		=> 	'#333333',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'service_title', array(
				'default'		=> 	'#333333',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'service_desc', array(
				'default'		=> 	'#333333',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'service_bg', array(
				'default'		=> 	'#ffffff',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'form_section_text', array(
				'default'		=> 	'#ffffff',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'service_title_bord', array(
				'default'		=> 	'#fed100',
				'type'			=> 	'option',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'bg_page_title', array(
				'default'		=> 	'#fed100',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'page_title', array(
				'default'		=> 	'#034153',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'faq_border', array(
				'default'		=> 	'#dddddd',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'team_name', array(
				'default'		=> 	'#ffffff',
				'type'			=> 	'option',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'team_bg', array(
				'default'		=> 	'#034153',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'team_social_bord', array(
				'default'		=> 	'#ffffff',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'team_social_hover', array(
				'default'		=> 	'#034153',
				'type'			=> 	'option',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'team_social_bgbord_hover', array(
				'default'		=> 	'#fed100',
				'type'			=> 	'option',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'team_social_bord2', array(
				'default'		=> 	'#333333',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'slogan_title', array(
				'default'		=> 	'#333333',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			//CONTROL
			
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'title_section', array(
				'label'		=>	esc_html__( 'Title Section Black Color', 'finance' ),
				'section'	=>	'content_styling',
				'settings'	=>	'title_section',
				'priority'	=>	1,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'title_section2', array(
				'label'		=>	esc_html__( 'Title Section White Color', 'finance' ),
				'section'	=>	'content_styling',
				'settings'	=>	'title_section2',
				'priority'	=>	2,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'about_section_text', array(
				'label'		=>	esc_html__( 'About Section Text and Other Text/Description colored Black', 'finance' ),
				'section'	=>	'content_styling',
				'settings'	=>	'about_section_text',
				'priority'	=>	3,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'about_section_icon', array(
				'label'		=>	esc_html__( 'About Section Icon Check List Color', 'finance' ),
				'section'	=>	'content_styling',
				'settings'	=>	'about_section_icon',
				'priority'	=>	4,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'review_section_text', array(
				'label'		=>	esc_html__( 'Review Section Text and Other Text/Description colored White', 'finance' ),
				'section'	=>	'content_styling',
				'settings'	=>	'review_section_text',
				'priority'	=>	5,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bg_icon_counter', array(
				'label'		=>	esc_html__( 'Counter Icon Background color', 'finance' ),
				'section'	=>	'content_styling',
				'settings'	=>	'bg_icon_counter',
				'priority'	=>	6,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bg_icon_counter_hover', array(
				'label'		=>	esc_html__( 'Counter Icon Background Hover color', 'finance' ),
				'section'	=>	'content_styling',
				'settings'	=>	'bg_icon_counter_hover',
				'priority'	=>	7,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'counter_icon', array(
				'label'		=>	esc_html__( 'Counter Icon color', 'finance' ),
				'section'	=>	'content_styling',
				'settings'	=>	'counter_icon',
				'priority'	=>	8,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'counter_value', array(
				'label'		=>	esc_html__( 'Counter Value color', 'finance' ),
				'section'	=>	'content_styling',
				'settings'	=>	'counter_value',
				'priority'	=>	9,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'counter_title', array(
				'label'		=>	esc_html__( 'Counter Title color', 'finance' ),
				'section'	=>	'content_styling',
				'settings'	=>	'counter_title',
				'priority'	=>	10,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'testi_icon', array(
				'label'		=>	esc_html__( 'Testi Icon color', 'finance' ),
				'section'	=>	'content_styling',
				'settings'	=>	'testi_icon',
				'priority'	=>	11,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'testi_bg', array(
				'label'		=>	esc_html__( 'Testimonial Background color', 'finance' ),
				'section'	=>	'content_styling',
				'settings'	=>	'testi_bg',
				'priority'	=>	12,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'testi_text', array(
				'label'		=>	esc_html__( 'Testimonial Text color', 'finance' ),
				'section'	=>	'content_styling',
				'settings'	=>	'testi_text',
				'priority'	=>	13,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'testi_author', array(
				'label'		=>	esc_html__( 'Testimonial Author Name color', 'finance' ),
				'section'	=>	'content_styling',
				'settings'	=>	'testi_author',
				'priority'	=>	14,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'author_job', array(
				'label'		=>	esc_html__( 'Testimonial Author Job color', 'finance' ),
				'section'	=>	'content_styling',
				'settings'	=>	'author_job',
				'priority'	=>	15,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'arrow_btn', array(
				'label'		=>	esc_html__( 'Testimonial Arrow Button Background color', 'finance' ),
				'section'	=>	'content_styling',
				'settings'	=>	'arrow_btn',
				'priority'	=>	16,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'arrow_icon', array(
				'label'		=>	esc_html__( 'Testimonial Arrow Icon color', 'finance' ),
				'section'	=>	'content_styling',
				'settings'	=>	'arrow_icon',
				'priority'	=>	17,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'feat_bord', array(
				'label'		=>	esc_html__( 'Feature Section Border color', 'finance' ),
				'section'	=>	'content_styling',
				'settings'	=>	'feat_bord',
				'priority'	=>	18,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'feat_bord_hov', array(
				'label'		=>	esc_html__( 'Feature Section Border & Background Hover color', 'finance' ),
				'section'	=>	'content_styling',
				'settings'	=>	'feat_bord_hov',
				'priority'	=>	19,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'feat_icon', array(
				'label'		=>	esc_html__( 'Feature Section Icon color', 'finance' ),
				'section'	=>	'content_styling',
				'settings'	=>	'feat_icon',
				'priority'	=>	20,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'feat_title', array(
				'label'		=>	esc_html__( 'Feature Section Title color', 'finance' ),
				'section'	=>	'content_styling',
				'settings'	=>	'feat_title',
				'priority'	=>	21,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'feat_desc', array(
				'label'		=>	esc_html__( 'Feature Section Description color', 'finance' ),
				'section'	=>	'content_styling',
				'settings'	=>	'feat_desc',
				'priority'	=>	22,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'service_title', array(
				'label'		=>	esc_html__( 'Service Title color', 'finance' ),
				'section'	=>	'content_styling',
				'settings'	=>	'service_title',
				'priority'	=>	23,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'service_desc', array(
				'label'		=>	esc_html__( 'Service Description color', 'finance' ),
				'section'	=>	'content_styling',
				'settings'	=>	'service_desc',
				'priority'	=>	24,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'service_bg', array(
				'label'		=>	esc_html__( 'Service Post Background color', 'finance' ),
				'section'	=>	'content_styling',
				'settings'	=>	'service_bg',
				'priority'	=>	25,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'form_section_text', array(
				'label'		=>	esc_html__( 'Form Section Description color', 'finance' ),
				'section'	=>	'content_styling',
				'settings'	=>	'form_section_text',
				'priority'	=>	26,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'service_title_bord', array(
				'label'		=>	esc_html__( 'Service Title Border & Team Email (Single Team) color', 'finance' ),
				'section'	=>	'content_styling',
				'settings'	=>	'service_title_bord',
				'priority'	=>	27,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bg_page_title', array(
				'label'		=>	esc_html__( 'Page Title Background color', 'finance' ),
				'section'	=>	'content_styling',
				'settings'	=>	'bg_page_title',
				'priority'	=>	28,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'page_title', array(
				'label'		=>	esc_html__( 'Page Title color', 'finance' ),
				'section'	=>	'content_styling',
				'settings'	=>	'page_title',
				'priority'	=>	29,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'faq_border', array(
				'label'		=>	esc_html__( 'FAQ Border color', 'finance' ),
				'section'	=>	'content_styling',
				'settings'	=>	'faq_border',
				'priority'	=>	30,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'team_name', array(
				'label'		=>	esc_html__( 'Team Name, Job, Description, arrow icon color (About Page & Single Project)', 'finance' ),
				'section'	=>	'content_styling',
				'settings'	=>	'team_name',
				'priority'	=>	31,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'team_bg', array(
				'label'		=>	esc_html__( 'Team Background color (About Page)', 'finance' ),
				'section'	=>	'content_styling',
				'settings'	=>	'team_bg',
				'priority'	=>	32,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'team_social_bord', array(
				'label'		=>	esc_html__( 'Team Social media icon and border color (About Page)', 'finance' ),
				'section'	=>	'content_styling',
				'settings'	=>	'team_social_bord',
				'priority'	=>	33,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'team_social_hover', array(
				'label'		=>	esc_html__( 'Team Social media icon Hover color (About Page)', 'finance' ),
				'section'	=>	'content_styling',
				'settings'	=>	'team_social_hover',
				'priority'	=>	34,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'team_social_bgbord_hover', array(
				'label'		=>	esc_html__( 'Team Social media icon Hover color (About Page)', 'finance' ),
				'section'	=>	'content_styling',
				'settings'	=>	'team_social_bgbord_hover',
				'priority'	=>	35,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'team_social_bord2', array(
				'label'		=>	esc_html__( 'Team Social media icon and border color (Single Team)', 'finance' ),
				'section'	=>	'content_styling',
				'settings'	=>	'team_social_bord2',
				'priority'	=>	36,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'slogan_title', array(
				'label'		=>	esc_html__( 'Slogan Title color', 'finance' ),
				'section'	=>	'content_styling',
				'settings'	=>	'slogan_title',
				'priority'	=>	37,
			) ) );


			

			/* BLOG STYLING
			================================================== */
			
			$wp_customize->add_section( 'blog_styling', array(
				'title'		=>	esc_html__( 'Blog Section', 'finance' ),
				'priority'	=>	200,
			) );

			//SECTION

			$wp_customize->add_setting( 'title_blog', array(
				'default'		=> 	'#034153',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'author_name', array(
				'default'		=> 	'#034153',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'post_date', array(
				'default'		=> 	'#cfd5dd',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'post_text', array(
				'default'		=> 	'#333333',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'post_meta', array(
				'default'		=> 	'#333333',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'post_meta_link', array(
				'default'		=> 	'#333333',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'post_meta_link_hov', array(
				'default'		=> 	'#034153',
				'type'			=> 	'option',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			//CONTROL
			
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'title_blog', array(
				'label'		=>	esc_html__( 'Title Blog Post Color', 'finance' ),
				'section'	=>	'blog_styling',
				'settings'	=>	'title_blog',
				'priority'	=>	1,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'author_name', array(
				'label'		=>	esc_html__( 'Post Author Name Color', 'finance' ),
				'section'	=>	'blog_styling',
				'settings'	=>	'author_name',
				'priority'	=>	2,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'post_date', array(
				'label'		=>	esc_html__( 'Post Date Color', 'finance' ),
				'section'	=>	'blog_styling',
				'settings'	=>	'post_date',
				'priority'	=>	3,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'post_text', array(
				'label'		=>	esc_html__( 'Post Text Content Color (Single Blog, Service, Project, Team)', 'finance' ),
				'section'	=>	'blog_styling',
				'settings'	=>	'post_text',
				'priority'	=>	4,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'post_meta', array(
				'label'		=>	esc_html__( 'Post Meta Color', 'finance' ),
				'section'	=>	'blog_styling',
				'settings'	=>	'post_meta',
				'priority'	=>	5,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'post_meta_link', array(
				'label'		=>	esc_html__( 'Post Meta Link and Other Text Link Color', 'finance' ),
				'section'	=>	'blog_styling',
				'settings'	=>	'post_meta_link',
				'priority'	=>	6,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'post_meta_link_hov', array(
				'label'		=>	esc_html__( 'Post Meta Link and Other Text Link Hover Color', 'finance' ),
				'section'	=>	'blog_styling',
				'settings'	=>	'post_meta_link_hov',
				'priority'	=>	7,
			) ) );



			/* SIDEBAR STYLING
			================================================== */
			
			$wp_customize->add_section( 'sidebar_styling', array(
				'title'		=>	esc_html__( 'Sidebar Section', 'finance' ),
				'priority'	=>	200,
			) );

			//SECTION

			$wp_customize->add_setting( 'title_side', array(
				'default'		=> 	'#333333',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'title_latest', array(
				'default'		=> 	'#333333',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'meta_latest', array(
				'default'		=> 	'#afafaf',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'bord_bottom', array(
				'default'		=> 	'#edf1fa',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'bg_side', array(
				'default'		=> 	'#f2f2f2',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'bg_bord', array(
				'default'		=> 	'#ffffff',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'bord_left', array(
				'default'		=> 	'#034153',
				'type'			=> 	'option',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'arc_text', array(
				'default'		=> 	'#333333',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'side_contact_bg', array(
				'default'		=> 	'#034153',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'side_contact_text', array(
				'default'		=> 	'#ffffff',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'border_tags', array(
				'default'		=> 	'#034153',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			//CONTROL
			
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'title_side', array(
				'label'		=>	esc_html__( 'Title Heading Sidebar Color', 'finance' ),
				'section'	=>	'sidebar_styling',
				'settings'	=>	'title_side',
				'priority'	=>	1,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'title_latest', array(
				'label'		=>	esc_html__( 'Title latest Post & Team Name (single team) Color', 'finance' ),
				'section'	=>	'sidebar_styling',
				'settings'	=>	'title_latest',
				'priority'	=>	2,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'meta_latest', array(
				'label'		=>	esc_html__( 'Meta latest Post & Team Job (single team) Color', 'finance' ),
				'section'	=>	'sidebar_styling',
				'settings'	=>	'meta_latest',
				'priority'	=>	3,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bord_bottom', array(
				'label'		=>	esc_html__( 'Border Bottom Color', 'finance' ),
				'section'	=>	'sidebar_styling',
				'settings'	=>	'bord_bottom',
				'priority'	=>	4,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bg_side', array(
				'label'		=>	esc_html__( 'Categories, archives, recent service, recent project, recent team Background Color', 'finance' ),
				'section'	=>	'sidebar_styling',
				'settings'	=>	'bg_side',
				'priority'	=>	5,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bg_bord', array(
				'label'		=>	esc_html__( 'Categories, archives, recent service, recent project, recent team Border Color', 'finance' ),
				'section'	=>	'sidebar_styling',
				'settings'	=>	'bg_bord',
				'priority'	=>	6,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bord_left', array(
				'label'		=>	esc_html__( 'Categories, archives, recent service, recent project, recent team Border Left Hover Color', 'finance' ),
				'section'	=>	'sidebar_styling',
				'settings'	=>	'bord_left',
				'priority'	=>	7,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'arc_text', array(
				'label'		=>	esc_html__( 'Categories, archives, recent service, recent project, recent team Text Color', 'finance' ),
				'section'	=>	'sidebar_styling',
				'settings'	=>	'arc_text',
				'priority'	=>	8,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'side_contact_bg', array(
				'label'		=>	esc_html__( 'Sidebar Contact Background Color (single Service)', 'finance' ),
				'section'	=>	'sidebar_styling',
				'settings'	=>	'side_contact_bg',
				'priority'	=>	9,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'side_contact_text', array(
				'label'		=>	esc_html__( 'Sidebar Contact Text Color (single Service)', 'finance' ),
				'section'	=>	'sidebar_styling',
				'settings'	=>	'side_contact_text',
				'priority'	=>	10,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'border_tags', array(
				'label'		=>	esc_html__( 'Tags Border Color (single Post)', 'finance' ),
				'section'	=>	'sidebar_styling',
				'settings'	=>	'border_tags',
				'priority'	=>	11,
			) ) );



			/* BUTOON STYLING
			================================================== */
			
			$wp_customize->add_section( 'button_styling', array(
				'title'		=>	esc_html__( 'Button Style Color', 'finance' ),
				'priority'	=>	200,
			) );

			//SECTION

			$wp_customize->add_setting( 'blue_btn_bg', array(
				'default'		=> 	'#034153',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'blue_btn_bg_hover', array(
				'default'		=> 	'#fed100',
				'type'			=> 	'option',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'blue_btn_text', array(
				'default'		=> 	'#ffffff',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'blue_btn_text_hover', array(
				'default'		=> 	'#034153',
				'type'			=> 	'option',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'white_btn_bord', array(
				'default'		=> 	'#ffffff',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'white_btn_bordbg_hov', array(
				'default'		=> 	'#fed100',
				'type'			=> 	'option',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'white_btn_text', array(
				'default'		=> 	'#ffffff',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'white_btn_text_hov', array(
				'default'		=> 	'#034153',
				'type'			=> 	'option',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'service_btn_bg', array(
				'default'		=> 	'#034153',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'service_btn_bg_hover', array(
				'default'		=> 	'#fed100',
				'type'			=> 	'option',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'service_btn_text', array(
				'default'		=> 	'#ffffff',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'service_btn_text_hover', array(
				'default'		=> 	'#034153',
				'type'			=> 	'option',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'service_plus_bg', array(
				'default'		=> 	'#023544',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'service_plus_icon', array(
				'default'		=> 	'#ffffff',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			//CONTROL
			
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'blue_btn_bg', array(
				'label'		=>	esc_html__( 'Blue Button Background Color', 'finance' ),
				'section'	=>	'button_styling',
				'settings'	=>	'blue_btn_bg',
				'priority'	=>	1,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'blue_btn_bg_hover', array(
				'label'		=>	esc_html__( 'Blue Button & Tags (post sidebar) Background Hover Color', 'finance' ),
				'section'	=>	'button_styling',
				'settings'	=>	'blue_btn_bg_hover',
				'priority'	=>	2,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'blue_btn_text', array(
				'label'		=>	esc_html__( 'Blue Button Text Color', 'finance' ),
				'section'	=>	'button_styling',
				'settings'	=>	'blue_btn_text',
				'priority'	=>	3,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'blue_btn_text_hover', array(
				'label'		=>	esc_html__( 'Blue Button Text Hover Color', 'finance' ),
				'section'	=>	'button_styling',
				'settings'	=>	'blue_btn_text_hover',
				'priority'	=>	4,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'white_btn_bord', array(
				'label'		=>	esc_html__( 'White Button Border Color', 'finance' ),
				'section'	=>	'button_styling',
				'settings'	=>	'white_btn_bord',
				'priority'	=>	5,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'white_btn_bordbg_hov', array(
				'label'		=>	esc_html__( 'White Button Border and Background Hover Color', 'finance' ),
				'section'	=>	'button_styling',
				'settings'	=>	'white_btn_bordbg_hov',
				'priority'	=>	6,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'white_btn_text', array(
				'label'		=>	esc_html__( 'White Button Text Color', 'finance' ),
				'section'	=>	'button_styling',
				'settings'	=>	'white_btn_text',
				'priority'	=>	7,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'white_btn_text_hov', array(
				'label'		=>	esc_html__( 'White Button Text Hover Color', 'finance' ),
				'section'	=>	'button_styling',
				'settings'	=>	'white_btn_text_hov',
				'priority'	=>	8,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'service_btn_bg', array(
				'label'		=>	esc_html__( 'Service Button Background Color', 'finance' ),
				'section'	=>	'button_styling',
				'settings'	=>	'service_btn_bg',
				'priority'	=>	9,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'service_btn_bg_hover', array(
				'label'		=>	esc_html__( 'Service Button Background Hover Color', 'finance' ),
				'section'	=>	'button_styling',
				'settings'	=>	'service_btn_bg_hover',
				'priority'	=>	10,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'service_btn_text', array(
				'label'		=>	esc_html__( 'Service Button Text Color', 'finance' ),
				'section'	=>	'button_styling',
				'settings'	=>	'service_btn_text',
				'priority'	=>	11,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'service_btn_text_hover', array(
				'label'		=>	esc_html__( 'Service Button Text Hover Color', 'finance' ),
				'section'	=>	'button_styling',
				'settings'	=>	'service_btn_text_hover',
				'priority'	=>	12,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'service_plus_bg', array(
				'label'		=>	esc_html__( 'Service Button Plus Background Color', 'finance' ),
				'section'	=>	'button_styling',
				'settings'	=>	'service_plus_bg',
				'priority'	=>	13,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'service_plus_icon', array(
				'label'		=>	esc_html__( 'Service Button Plus Icon Color', 'finance' ),
				'section'	=>	'button_styling',
				'settings'	=>	'service_plus_icon',
				'priority'	=>	14,
			) ) );


			/* FOOTER STYLING
			================================================== */
			
			$wp_customize->add_section( 'footer_styling', array(
				'title'		=>	esc_html__( 'Footer Color', 'finance' ),
				'priority'	=>	200,
			) );

			//SECTION

			$wp_customize->add_setting( 'bg_footer', array(
				'default'		=> 	'#034153',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'bord_footer', array(
				'default'		=> 	'#045971',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'title_widget', array(
				'default'		=> 	'#ffffff',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'content_widget', array(
				'default'		=> 	'#ffffff',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'bg_sosmed', array(
				'default'		=> 	'#045971',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'bg_sosmed_hover', array(
				'default'		=> 	'#fed100',
				'type'			=> 	'option',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'text_sosmed', array(
				'default'		=> 	'#ffffff',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'text_sosmed_hover', array(
				'default'		=> 	'#034153',
				'type'			=> 	'option',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_setting( 'copyright_text', array(
				'default'		=> 	'#ffffff',
				'type'			=> 	'option',
				'transport'		=> 	'postMessage',
				'capability'	=>	'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			//CONTROL
			
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bg_footer', array(
				'label'		=>	esc_html__( 'Footer Background Color', 'finance' ),
				'section'	=>	'footer_styling',
				'settings'	=>	'bg_footer',
				'priority'	=>	1,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bord_footer', array(
				'label'		=>	esc_html__( 'Footer Border Color', 'finance' ),
				'section'	=>	'footer_styling',
				'settings'	=>	'bord_footer',
				'priority'	=>	2,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'title_widget', array(
				'label'		=>	esc_html__( 'Title Widget Color', 'finance' ),
				'section'	=>	'footer_styling',
				'settings'	=>	'title_widget',
				'priority'	=>	3,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'content_widget', array(
				'label'		=>	esc_html__( 'Content Widget Color', 'finance' ),
				'section'	=>	'footer_styling',
				'settings'	=>	'content_widget',
				'priority'	=>	4,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bg_sosmed', array(
				'label'		=>	esc_html__( 'Social Share Background Color', 'finance' ),
				'section'	=>	'footer_styling',
				'settings'	=>	'bg_sosmed',
				'priority'	=>	5,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bg_sosmed_hover', array(
				'label'		=>	esc_html__( 'Social Share Background Hover Color', 'finance' ),
				'section'	=>	'footer_styling',
				'settings'	=>	'bg_sosmed_hover',
				'priority'	=>	6,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'text_sosmed', array(
				'label'		=>	esc_html__( 'Social Share Text Color', 'finance' ),
				'section'	=>	'footer_styling',
				'settings'	=>	'text_sosmed',
				'priority'	=>	7,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'text_sosmed_hover', array(
				'label'		=>	esc_html__( 'Social Share Text Hover Color', 'finance' ),
				'section'	=>	'footer_styling',
				'settings'	=>	'text_sosmed_hover',
				'priority'	=>	8,
			) ) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'copyright_text', array(
				'label'		=>	esc_html__( 'Copyright Text Color', 'finance' ),
				'section'	=>	'footer_styling',
				'settings'	=>	'copyright_text',
				'priority'	=>	9,
			) ) );


		}
		add_action( 'customize_register', 'finance_customize_register' );

	}
	
	
	function finance_customizer_live_preview() {
		wp_enqueue_script( 'akmanda-customizer',	get_template_directory_uri().'/js/akmanda-customizer.js', array( 'jquery','customize-preview' ), NULL, true);
	}
	add_action( 'customize_preview_init', 'finance_customizer_live_preview' );
	


	function finance_customizer_header_output() {	

	//header styling
	$top_bar_bg						=	get_option('top_bar_bg', '#034153');
	$top_bar_text					=	get_option('top_bar_text', '#ffffff');
	$top_bar_icon					=	get_option('top_bar_icon', '#fed100');
	$top_bar_btn_bg					=	get_option('top_bar_btn_bg', '#fed100');
	$top_bar_btn_text				=	get_option('top_bar_btn_text', '#034153');
	$top_head_bg					=	get_option('top_head_bg', '#ffffff');
	$icon_info						=	get_option('icon_info', '#023669');
	$text_info						=	get_option('text_info', '#333333');
	$border_nav						=	get_option('border_nav', '#d9e3e5');
	$nav_menu						=	get_option('nav_menu', '#034153');
	$sub_menu_bg					=	get_option('sub_menu_bg', '#034153');
	$sub_menu_bg_hov				=	get_option('sub_menu_bg_hov', '#fed100');
	$sub_menu_text					=	get_option('sub_menu_text', '#ffffff');
	$sub_menu_text_hov				=	get_option('sub_menu_text_hov', '#034153');

	//content styling
	$title_section					=	get_option('title_section', '#333333');
	$title_section2					=	get_option('title_section2', '#ffffff');
	$about_section_text				=	get_option('about_section_text', '#333333');
	$about_section_icon				=	get_option('about_section_icon', '#034153');
	$review_section_text			=	get_option('review_section_text', '#ffffff');
	$bg_icon_counter				=	get_option('bg_icon_counter', '#ffffff');
	$bg_icon_counter_hover			=	get_option('bg_icon_counter_hover', '#fed100');
	$counter_icon					=	get_option('counter_icon', '#333333');
	$counter_value					=	get_option('counter_value', '#ffffff');
	$counter_title					=	get_option('counter_title', '#ffffff');
	$testi_icon						=	get_option('testi_icon', '#f2f2f2');
	$testi_bg						=	get_option('testi_bg', '#ffffff');
	$testi_text						=	get_option('testi_text', '#333333');
	$testi_author					=	get_option('testi_author', '#333333');
	$author_job						=	get_option('author_job', '#333333');
	$arrow_btn						=	get_option('arrow_btn', '#fed100');
	$arrow_icon						=	get_option('arrow_icon', '#034153');
	$feat_bord						=	get_option('feat_bord', '#034153');
	$feat_bord_hov					=	get_option('feat_bord_hov', '#fed100');
	$feat_icon						=	get_option('feat_icon', '#034153');
	$feat_title						=	get_option('feat_title', '#333333');
	$feat_desc						=	get_option('feat_desc', '#333333');
	$service_title					=	get_option('service_title', '#333333');
	$service_desc					=	get_option('service_desc', '#333333');
	$service_bg						=	get_option('service_bg', '#ffffff');
	$form_section_text				=	get_option('form_section_text', '#ffffff');
	$service_title_bord				=	get_option('service_title_bord', '#fed100');
	$bg_page_title					=	get_option('bg_page_title', '#fed100');
	$page_title						=	get_option('page_title', '#034153');
	$faq_border						=	get_option('faq_border', '#dddddd');
	$team_name						=	get_option('team_name', '#ffffff');
	$team_bg						=	get_option('team_bg', '#034153');
	$team_social_bord				=	get_option('team_social_bord', '#ffffff');
	$team_social_hover				=	get_option('team_social_hover', '#034153');
	$team_social_bgbord_hover		=	get_option('team_social_bgbord_hover', '#fed100');
	$team_social_bord2				=	get_option('team_social_bord2', '#333333');
	$slogan_title					=	get_option('slogan_title', '#333333');

	//blog styling
	$title_blog						=	get_option('title_blog', '#034153');
	$author_name					=	get_option('author_name', '#034153');
	$post_date						=	get_option('post_date', '#cfd5dd');
	$post_text						=	get_option('post_text', '#333333');
	$post_meta						=	get_option('post_meta', '#333333');
	$post_meta_link					=	get_option('post_meta_link', '#333333');
	$post_meta_link_hov				=	get_option('post_meta_link_hov', '#034153');

	//sidebar styling
	$title_side						=	get_option('title_side', '#333333');
	$title_latest					=	get_option('title_latest', '#333333');
	$meta_latest					=	get_option('meta_latest', '#afafaf');
	$bord_bottom					=	get_option('bord_bottom', '#edf1fa');
	$bg_side						=	get_option('bg_side', '#f2f2f2');
	$bg_bord						=	get_option('bg_bord', '#ffffff');
	$bord_left						=	get_option('bord_left', '#034153');
	$arc_text						=	get_option('arc_text', '#333333');
	$side_contact_bg				=	get_option('side_contact_bg', '#034153');
	$side_contact_text				=	get_option('side_contact_text', '#ffffff');
	$border_tags					=	get_option('border_tags', '#034153');

	//button styling
	$blue_btn_bg					=	get_option('blue_btn_bg', '#034153');
	$blue_btn_bg_hover				=	get_option('blue_btn_bg_hover', '#fed100');
	$blue_btn_text					=	get_option('blue_btn_text', '#ffffff');
	$blue_btn_text_hover			=	get_option('blue_btn_text_hover', '#034153');
	$white_btn_bord					=	get_option('white_btn_bord', '#ffffff');
	$white_btn_bordbg_hov			=	get_option('white_btn_bordbg_hov', '#fed100');
	$white_btn_text					=	get_option('white_btn_text', '#ffffff');
	$white_btn_text_hov				=	get_option('white_btn_text_hov', '#034153');
	$service_btn_bg					=	get_option('service_btn_bg', '#034153');
	$service_btn_bg_hover			=	get_option('service_btn_bg_hover', '#fed100');
	$service_btn_text				=	get_option('service_btn_text', '#ffffff');
	$service_btn_text_hover			=	get_option('service_btn_text_hover', '#034153');
	$service_plus_bg				=	get_option('service_plus_bg', '#023544');
	$service_plus_icon				=	get_option('service_plus_icon', '#ffffff');


	//footer styling
	$bg_footer						=	get_option('bg_footer', '#034153');
	$bord_footer					=	get_option('bord_footer', '#045971');
	$title_widget					=	get_option('title_widget', '#ffffff');
	$content_widget					=	get_option('content_widget', '#ffffff');
	$bg_sosmed						=	get_option('bg_sosmed', '#045971');
	$bg_sosmed_hover				=	get_option('bg_sosmed_hover', '#fed100');
	$text_sosmed					=	get_option('text_sosmed', '#ffffff');
	$text_sosmed_hover				=	get_option('text_sosmed_hover', '#034153');
	$copyright_text					=	get_option('copyright_text', '#ffffff');
		

	echo '<style type="text/css">';

	//=========HEADER STYLING======//

	echo '.top-bar{background-color:'.$top_bar_bg.'}' ;
	echo '.address-bar p{color:'.$top_bar_text.'}' ;
	echo '.address-bar i{color:'.$top_bar_icon.'}' ;
	echo '.quote-link a{background-color:'.$top_bar_btn_bg.'}' ;
	echo '.quote-link a{color:'.$top_bar_btn_text.'}' ;
	echo '.top-header, .c-menu{background-color:'.$top_head_bg.'}' ;
	echo '.top-header ul li i, #slide-buttons{color:'.$icon_info.'}' ;
	echo '.top-header ul li p, .c-menu--slide-right ul.menus li a, .c-menu__close{color:'.$text_info.'}' ;
	echo '.site-header .navigation{border-bottom-color:'.$border_nav.'}' ;
	echo '.main-menu > ul > li > a{color:'.$nav_menu.'}' ;
	echo '.main-menu ul ul li a{background-color:'.$sub_menu_bg.'}' ;
	echo '.main-menu ul ul li:hover > a{background-color:'.$sub_menu_bg_hov.'}' ;
	echo '.main-menu ul ul li a{color:'.$sub_menu_text.'}' ;
	echo '.main-menu ul ul li:hover > a{color:'.$sub_menu_text_hov.'}' ;

	//=========CONTENT STYLING======//

	echo 'h2.section-title{color:'.$title_section.'}' ;
	echo 'h2.section-title.white{color:'.$title_section2.'}' ;
	echo '.about-text strong, .about-text p, .about-text ul, .section-text p, .panel-body p, .contact-details, .contact-details p, .contact-text p{color:'.$about_section_text.'}' ;
	echo '.about-text ul li i{color:'.$about_section_icon.'}' ;
	echo '.review-text p{color:'.$review_section_text.'}' ;
	echo '.counter-pic{background-color:'.$bg_icon_counter.'}' ;
	echo '.counter-item:hover .counter-pic{background-color:'.$bg_icon_counter_hover.'}' ;
	echo '.counter-pic i{color:'.$counter_icon.'}' ;
	echo '.counter-value{color:'.$counter_value.'}' ;
	echo '.counter-text .counter-title{color:'.$counter_title.'}' ;
	echo '.testimonial-text i{color:'.$testi_icon.'}' ;
	echo '.testimonial-content .testimonial-text{background-color:'.$testi_bg.'}' ;
	echo '.testi-author-img{border-color:'.$testi_bg.'}' ;
	echo '.testimonial-text p{color:'.$testi_text.'}' ;
	echo '.testimonial-text h4{color:'.$testi_author.'}' ;
	echo '.testi-job{color:'.$author_job.'}' ;
	echo '.testimonial-slider .flex-direction-nav a, .faq-gallery .flex-direction-nav a{background-color:'.$arrow_btn.'}' ;
	echo '.flex-direction-nav a:before, .faq-gallery .flex-direction-nav a{color:'.$arrow_icon.'}' ;
	echo '.feature .feature-content, .feature .feature-pic{border-color:'.$feat_bord.'}' ;
	echo '.feature:hover .feature-pic{border-color:'.$feat_bord_hov.'}' ;
	echo '.feature:hover .feature-pic{background-color:'.$feat_bord_hov.'}' ;
	echo '.feature .feature-pic i{color:'.$feat_icon.'}' ;
	echo '.feature .feature-desc h5{color:'.$feat_title.'}' ;
	echo '.feature .feature-desc p{color:'.$feat_desc.'}' ;
	echo '.service-post-wrap h4{color:'.$service_title.'}' ;
	echo '.service-post-wrap p{color:'.$service_desc.'}' ;
	echo '.service-post-wrap{background-color:'.$service_bg.'}' ;
	echo '.form-html p, .form-html h2{color:'.$form_section_text.'}' ;
	echo '.project-post-wrap .title:before{background-color:'.$service_title_bord.'}' ;
	echo '.contact-detail-info .email{color:'.$service_title_bord.'}' ;
	echo '.page-title{background-color:'.$bg_page_title.'}' ;
	echo '.page-title h3, .page-title p{color:'.$page_title.'}' ;
	echo '.panel-default{border-color:'.$faq_border.'}' ;
	echo '.team-name h4.name a, .team-name .job, .team-img .team-detail .team-desc, .team-text, .team-text h4, .team-slider .flex-direction-nav a:before{color:'.$team_name.'}' ;
	echo '.team-name, .team-text{background-color:'.$team_bg.'}' ;
	echo '.team-social li a{color:'.$team_social_bord.'}' ;
	echo '.team-social li a{border-color:'.$team_social_bord.'}' ;
	echo '.team-social li a:hover{color:'.$team_social_hover.'}' ;
	echo '.team-social li a:hover{background-color:'.$team_social_bgbord_hover.'}' ;
	echo '.team-social li a:hover{border-color:'.$team_social_bgbord_hover.'}' ;
	echo '.team-profile ul li a{color:'.$team_social_bord2.'}' ;
	echo '.team-profile ul li a{border-color:'.$team_social_bord2.'}' ;
	echo '.slogan-title{color:'.$slogan_title.'}' ;

	//=========BLOG STYLING======//

	echo '.post-title{color:'.$title_blog.'}' ;
	echo '.author-name a{color:'.$author_name.'}' ;
	echo '.post-date a{color:'.$post_date.'}' ;
	echo '.excerpt p, .post-text p, .author-content p, p.comment-form-comment, .inner-content p, .inner-content h4{color:'.$post_text.'}' ;
	echo '.single-content .post-meta li{color:'.$post_meta.'}' ;
	echo '.single-content .post-meta li a, .tag-wrapper a, .logged-in-as a, .breadcrumbs a, h4.panel-title, .pdf-download ul li a, .tagcloud a, .user-social a{color:'.$post_meta_link.'}' ;
	echo '.single-content .post-meta li a:hover, .tag-wrapper a:hover, .logged-in-as a:hover, .breadcrumbs a:hover, h4.panel-title:hover , .pdf-download ul li a:hover, .tagcloud a:hover, .user-social a:hover{color:'.$post_meta_link_hov.'}' ;

	//=========SIDEBAR STYLING======//

	echo '.blog-sidebar .widget .heading-block h4{color:'.$title_side.'}' ;
	echo '.widget.recent-post .post-content h4, h3.single-team-name, .contact-detail-info .phone{color:'.$title_latest.'}' ;
	echo '.widget.recent-post .post-content .meta span, .contact-team .job{color:'.$meta_latest.'}' ;
	echo '.widget.recent-post .post-item, .single-content .post-meta{border-bottom-color:'.$bord_bottom.'}' ;
	echo '.widget.widget_categories ul li, .widget.widget_archive ul li, .widget.widget_meta ul li, .widget.widget_nav_menu ul#menu-service-menu li, .widget.widget_nav_menu ul#menu-project li, .widget.widget_nav_menu ul#menu-team li, .blog-sidebar .widget ul li, .tag-wrapper{background-color:'.$bg_side.'}' ;
	echo '.widget.widget_categories ul li, .widget.widget_archive ul li, .widget.widget_meta ul li, .widget.widget_nav_menu ul#menu-service-menu li, .widget.widget_nav_menu ul#menu-project li, .widget.widget_nav_menu ul#menu-team li, .blog-sidebar .widget ul li{border-bottom-color:'.$bg_bord.'}' ;
	echo '.widget.widget_categories ul li:hover, .widget.widget_archive ul li:hover, .widget.widget_meta ul li:hover, .widget.widget_nav_menu ul#menu-service-menu li:hover, .widget.widget_nav_menu ul#menu-project li:hover, .widget.widget_nav_menu ul#menu-team li:hover, .tag-wrapper{border-left-color:'.$bord_left.'}' ;
	echo '.widget.widget_categories ul li a, .widget.widget_archive ul li a, .widget.widget_meta ul li a, .widget.widget_nav_menu ul#menu-service-menu li a, .widget.widget_nav_menu ul#menu-project li a, .widget.widget_nav_menu ul#menu-team li a{color:'.$arc_text.'}' ;
	echo '.sidebar-contact{background-color:'.$side_contact_bg.'}' ;
	echo '.sidebar-contact h3, .sidebar-contact p{color:'.$side_contact_text.'}' ;
	echo '.tagcloud a, .pagination span.current, .pagination a{border-color:'.$border_tags.'}' ;
	echo '.pagination span.current{color:'.$border_tags.'}' ;
	echo '.pagination a{background-color:'.$border_tags.'}' ;


	//=========BUTTON STYLING======//

	echo 'a.button-normal, .form-submit .submit, .project-post-wrap .view-more a, .single-team .the-form form p.submit input{background-color:'.$blue_btn_bg.'}' ;
	echo 'a.button-normal, .single-team .the-form form p.submit input{border-color:'.$blue_btn_bg.'}' ;
	echo 'a.button-normal:hover, .form-submit .submit:hover, .project-post-wrap .view-more a:hover, .single-team .the-form form p.submit input:hover, .tagcloud a:hover, .pagination a:hover{background-color:'.$blue_btn_bg_hover.'}' ;
	echo 'a.button-normal:hover, .single-team .the-form form p.submit input:hover, .tagcloud a:hover, .pagination a:hover{border-color:'.$blue_btn_bg_hover.'}' ;
	echo 'a.button-normal, .form-submit .submit, .project-post-wrap .view-more a, .single-team .the-form form p.submit input, .pagination a{color:'.$blue_btn_text.'}' ;
	echo 'a.button-normal:hover, .form-submit .submit:hover, .project-post-wrap .view-more a:hover, .single-team .the-form form p.submit input:hover, .pagination a:hover{color:'.$blue_btn_text_hover.'}' ;
	echo '.button-white, .the-form form p.submit input, .widget_mc4wp_form_widget form p input[type="submit"]{border-color:'.$white_btn_bord.'}' ;
	echo '.button-white:hover, .the-form form p.submit input:hover, .widget_mc4wp_form_widget form p input[type="submit"]:hover{border-color:'.$white_btn_bordbg_hov.'}' ;
	echo '.button-white:hover, .the-form form p.submit input:hover, .widget_mc4wp_form_widget form p input[type="submit"]:hover{background-color:'.$white_btn_bordbg_hov.'}' ;
	echo '.button-white, .the-form form p.submit input, .widget_mc4wp_form_widget form p input[type="submit"]{color:'.$white_btn_text.'}' ;
	echo '.button-white:hover, .the-form form p.submit input:hover, .widget_mc4wp_form_widget form p input[type="submit"]:hover{color:'.$white_btn_text_hov.'}' ;
	echo '.service-item .view-more a{background-color:'.$service_btn_bg.'}' ;
	echo '.view-more a:hover{background-color:'.$service_btn_bg_hover.'}' ;
	echo '.service-item .view-more a{color:'.$service_btn_text.'}' ;
	echo '.view-more a:hover{color:'.$service_btn_text_hover.'}' ;
	echo '.service-item .view-more i{background-color:'.$service_plus_bg.'}' ;
	echo '.service-item .view-more i{color:'.$service_plus_icon.'}' ;

	//=========BUTTON STYLING======//

	echo '.site-footer{background-color:'.$bg_footer.'}' ;
	echo '.footer-widget-areas{border-bottom-color:'.$bord_footer.'}' ;
	echo '.socialbox-widget{border-top-color:'.$bord_footer.'}' ;
	echo '.footer-widget h2{color:'.$title_widget.'}' ;
	echo '.footer-widget-areas .widget-footer, .widget-footer.widget_nav_menu ul li a, .widget_mc4wp_form_widget form p, .widget-footer .contact-section .text-section p{color:'.$content_widget.'}' ;
	echo '.socialbox-widget ul li a{background-color:'.$bg_sosmed.'}' ;
	echo '.socialbox-widget ul li a:hover{background-color:'.$bg_sosmed_hover.'}' ;
	echo '.socialbox-widget ul li a{color:'.$text_sosmed.'}' ;
	echo '.socialbox-widget ul li a:hover{color:'.$text_sosmed_hover.'}' ;
	echo '.copyright-text, .copyright-text a{color:'.$copyright_text.'}' ;


	echo '</style> ';

	}

	add_action( 'wp_head', 'finance_customizer_header_output');