/*
*
*	Live Customiser Script
*	------------------------------------------------
	*	Akmanda Framework
	* 	Copyright Themes Awesome 2013 - http://www.themesawesome.com
*
*/
( function( $ ){		
	
	// HEADER STYLING

	wp.customize('top_bar_bg',function( value ) {
		value.bind(function(to) {
			$('.top-bar').css('background-color', to ? to : '' );
		});
	});

	wp.customize('top_bar_text',function( value ) {
		value.bind(function(to) {
			$('.address-bar p').css('color', to ? to : '' );
		});
	});

	wp.customize('top_bar_icon',function( value ) {
		value.bind(function(to) {
			$('.address-bar i').css('color', to ? to : '' );
		});
	});

	wp.customize('top_bar_btn_bg',function( value ) {
		value.bind(function(to) {
			$('.quote-link a').css('background-color', to ? to : '' );
		});
	});

	wp.customize('top_bar_btn_text',function( value ) {
		value.bind(function(to) {
			$('.quote-link a').css('color', to ? to : '' );
		});
	});

	wp.customize('top_head_bg',function( value ) {
		value.bind(function(to) {
			$('.top-header, .c-menu').css('background-color', to ? to : '' );
		});
	});

	wp.customize('icon_info',function( value ) {
		value.bind(function(to) {
			$('.top-header ul li i, #slide-buttons').css('color', to ? to : '' );
		});
	});

	wp.customize('text_info',function( value ) {
		value.bind(function(to) {
			$('.top-header ul li p, .c-menu--slide-right ul.menus li a, .c-menu__close').css('color', to ? to : '' );
		});
	});

	wp.customize('border_nav',function( value ) {
		value.bind(function(to) {
			$('.site-header .navigation').css('border-top-color', to ? to : '' );
			$('.site-header .navigation').css('border-bottom-color', to ? to : '' );
		});
	});

	wp.customize('nav_menu',function( value ) {
		value.bind(function(to) {
			$('.main-menu > ul > li > a').css('color', to ? to : '' );
		});
	});

	wp.customize('sub_menu_bg',function( value ) {
		value.bind(function(to) {
			$('.main-menu ul ul li a').css('background-color', to ? to : '' );
		});
	});

	wp.customize('sub_menu_bg_hov',function( value ) {
		value.bind(function(to) {
			$('.main-menu ul ul li:hover > a').css('background-color', to ? to : '' );
		});
	});

	wp.customize('sub_menu_text',function( value ) {
		value.bind(function(to) {
			$('.main-menu ul ul li a').css('color', to ? to : '' );
		});
	});

	wp.customize('sub_menu_text_hov',function( value ) {
		value.bind(function(to) {
			$('.main-menu ul ul li:hover > a').css('color', to ? to : '' );
		});
	});


	// CONTENT STYLING

	wp.customize('title_section',function( value ) {
		value.bind(function(to) {
			$('h2.section-title').css('color', to ? to : '' );
		});
	});

	wp.customize('title_section2',function( value ) {
		value.bind(function(to) {
			$('h2.section-title.white').css('color', to ? to : '' );
		});
	});

	wp.customize('about_section_text',function( value ) {
		value.bind(function(to) {
			$('.about-text strong, .about-text p, .about-text ul, .section-text p, .panel-body p, .contact-details, .contact-details p, .contact-text p').css('color', to ? to : '' );
		});
	});

	wp.customize('about_section_icon',function( value ) {
		value.bind(function(to) {
			$('.about-text ul li i').css('color', to ? to : '' );
		});
	});

	wp.customize('review_section_text',function( value ) {
		value.bind(function(to) {
			$('.review-text p').css('color', to ? to : '' );
		});
	});

	wp.customize('bg_icon_counter',function( value ) {
		value.bind(function(to) {
			$('.counter-pic').css('background-color', to ? to : '' );
		});
	});

	wp.customize('bg_icon_counter_hover',function( value ) {
		value.bind(function(to) {
			$('.counter-item:hover .counter-pic').css('background-color', to ? to : '' );
		});
	});

	wp.customize('counter_icon',function( value ) {
		value.bind(function(to) {
			$('.counter-pic i').css('color', to ? to : '' );
		});
	});

	wp.customize('counter_value',function( value ) {
		value.bind(function(to) {
			$('.counter-value').css('color', to ? to : '' );
		});
	});

	wp.customize('counter_title',function( value ) {
		value.bind(function(to) {
			$('.counter-text .counter-title').css('color', to ? to : '' );
		});
	});

	wp.customize('testi_icon',function( value ) {
		value.bind(function(to) {
			$('.testimonial-text i').css('color', to ? to : '' );
		});
	});

	wp.customize('testi_bg',function( value ) {
		value.bind(function(to) {
			$('.testimonial-content .testimonial-text').css('background-color', to ? to : '' );
			$('.testi-author-img').css('border-color', to ? to : '' );
		});
	});

	wp.customize('testi_text',function( value ) {
		value.bind(function(to) {
			$('.testimonial-text p').css('color', to ? to : '' );
		});
	});

	wp.customize('testi_author',function( value ) {
		value.bind(function(to) {
			$('.testimonial-text h4').css('color', to ? to : '' );
		});
	});

	wp.customize('author_job',function( value ) {
		value.bind(function(to) {
			$('.testi-job').css('color', to ? to : '' );
		});
	});

	wp.customize('arrow_btn',function( value ) {
		value.bind(function(to) {
			$('.testimonial-slider .flex-direction-nav a, .faq-gallery .flex-direction-nav a').css('background-color', to ? to : '' );
		});
	});

	wp.customize('arrow_icon',function( value ) {
		value.bind(function(to) {
			$('.flex-direction-nav a:before, .faq-gallery .flex-direction-nav a').css('color', to ? to : '' );
		});
	});

	wp.customize('feat_bord',function( value ) {
		value.bind(function(to) {
			$('.feature .feature-content, .feature .feature-pic').css('border-color', to ? to : '' );
		});
	});

	wp.customize('feat_bord_hov',function( value ) {
		value.bind(function(to) {
			$('.feature:hover .feature-pic').css('border-color', to ? to : '' );
			$('.feature:hover .feature-pic').css('background-color', to ? to : '' );
		});
	});

	wp.customize('feat_icon',function( value ) {
		value.bind(function(to) {
			$('.feature .feature-pic i').css('color', to ? to : '' );
		});
	});

	wp.customize('feat_title',function( value ) {
		value.bind(function(to) {
			$('.feature .feature-desc h5').css('color', to ? to : '' );
		});
	});

	wp.customize('feat_desc',function( value ) {
		value.bind(function(to) {
			$('.feature .feature-desc p').css('color', to ? to : '' );
		});
	});

	wp.customize('service_title',function( value ) {
		value.bind(function(to) {
			$('.service-post-wrap h4').css('color', to ? to : '' );
		});
	});

	wp.customize('service_desc',function( value ) {
		value.bind(function(to) {
			$('.service-post-wrap p').css('color', to ? to : '' );
		});
	});

	wp.customize('service_bg',function( value ) {
		value.bind(function(to) {
			$('.service-post-wrap').css('background-color', to ? to : '' );
		});
	});

	wp.customize('form_section_text',function( value ) {
		value.bind(function(to) {
			$('.form-html p, .form-html h2').css('color', to ? to : '' );
		});
	});

	wp.customize('service_title_bord',function( value ) {
		value.bind(function(to) {
			$('.project-post-wrap .title:before').css('background-color', to ? to : '' );
			$('.contact-detail-info .email').css('color', to ? to : '' );
		});
	});

	wp.customize('bg_page_title',function( value ) {
		value.bind(function(to) {
			$('.page-title').css('background-color', to ? to : '' );
		});
	});

	wp.customize('page_title',function( value ) {
		value.bind(function(to) {
			$('.page-title h3, .page-title p').css('color', to ? to : '' );
		});
	});

	wp.customize('faq_border',function( value ) {
		value.bind(function(to) {
			$('.panel-default').css('border-color', to ? to : '' );
		});
	});

	wp.customize('team_name',function( value ) {
		value.bind(function(to) {
			$('.team-name h4.name a, .team-name .job, .team-img .team-detail .team-desc, .team-text, .team-text h4, .team-slider .flex-direction-nav a:before').css('color', to ? to : '' );
		});
	});

	wp.customize('team_bg',function( value ) {
		value.bind(function(to) {
			$('.team-name, .team-text').css('background-color', to ? to : '' );
		});
	});

	wp.customize('team_social_bord',function( value ) {
		value.bind(function(to) {
			$('.team-social li a').css('color', to ? to : '' );
			$('.team-social li a').css('border-color', to ? to : '' );
		});
	});

	wp.customize('team_social_hover',function( value ) {
		value.bind(function(to) {
			$('.team-social li a:hover').css('color', to ? to : '' );
		});
	});

	wp.customize('team_social_bgbord_bord',function( value ) {
		value.bind(function(to) {
			$('.team-social li a:hover').css('background-color', to ? to : '' );
			$('.team-social li a:hover').css('border-color', to ? to : '' );
		});
	});

	wp.customize('team_social_bord2',function( value ) {
		value.bind(function(to) {
			$('.team-profile ul li a').css('color', to ? to : '' );
			$('.team-profile ul li a').css('border-color', to ? to : '' );
		});
	});

	wp.customize('slogan_title',function( value ) {
		value.bind(function(to) {
			$('.slogan-title').css('color', to ? to : '' );
		});
	});



	// BLOG STYLING

	wp.customize('title_blog',function( value ) {
		value.bind(function(to) {
			$('.post-title').css('color', to ? to : '' );
		});
	});

	wp.customize('author_name',function( value ) {
		value.bind(function(to) {
			$('.author-name a').css('color', to ? to : '' );
		});
	});

	wp.customize('post_date',function( value ) {
		value.bind(function(to) {
			$('.post-date a').css('color', to ? to : '' );
		});
	});

	wp.customize('post_text',function( value ) {
		value.bind(function(to) {
			$('.excerpt p, .post-text p, .author-content p, p.comment-form-comment, .inner-content p, .inner-content h4').css('color', to ? to : '' );
		});
	});

	wp.customize('post_meta',function( value ) {
		value.bind(function(to) {
			$('.single-content .post-meta li').css('color', to ? to : '' );
		});
	});

	wp.customize('post_meta_link',function( value ) {
		value.bind(function(to) {
			$('.single-content .post-meta li a, .tag-wrapper a, .logged-in-as a, .breadcrumbs a, h4.panel-title, .pdf-download ul li a, .tagcloud a, .user-social a').css('color', to ? to : '' );
		});
	});

	wp.customize('post_meta_link_hov',function( value ) {
		value.bind(function(to) {
			$('.single-content .post-meta li a:hover, .tag-wrapper a:hover, .logged-in-as a:hover, .breadcrumbs a:hover, h4.panel-title:hover, .pdf-download ul li a:hover, .tagcloud a:hover, .user-social a:hover').css('color', to ? to : '' );
		});
	});


	// SIDEBAR STYLING

	wp.customize('title_side',function( value ) {
		value.bind(function(to) {
			$('.blog-sidebar .widget .heading-block h4').css('color', to ? to : '' );
		});
	});

	wp.customize('title_latest',function( value ) {
		value.bind(function(to) {
			$('.widget.recent-post .post-content h4, h3.single-team-name, .contact-detail-info .phone').css('color', to ? to : '' );
		});
	});

	wp.customize('meta_latest',function( value ) {
		value.bind(function(to) {
			$('.widget.recent-post .post-content .meta span, .contact-team .job').css('color', to ? to : '' );
		});
	});

	wp.customize('bord_bottom',function( value ) {
		value.bind(function(to) {
			$('.widget.recent-post .post-item, .single-content .post-meta').css('border-bottom-color', to ? to : '' );
		});
	});

	wp.customize('bg_side',function( value ) {
		value.bind(function(to) {
			$('.widget.widget_categories ul li, .widget.widget_archive ul li, .widget.widget_meta ul li, .widget.widget_nav_menu ul#menu-service-menu li, .widget.widget_nav_menu ul#menu-project li, .widget.widget_nav_menu ul#menu-team li, .blog-sidebar .widget ul li, .tag-wrapper').css('background-color', to ? to : '' );
		});
	});

	wp.customize('bg_bord',function( value ) {
		value.bind(function(to) {
			$('.widget.widget_categories ul li, .widget.widget_archive ul li, .widget.widget_meta ul li, .widget.widget_nav_menu ul#menu-service-menu li, .widget.widget_nav_menu ul#menu-project li, .widget.widget_nav_menu ul#menu-team li, .blog-sidebar .widget ul li').css('border-bottom-color', to ? to : '' );
		});
	});

	wp.customize('bord_left',function( value ) {
		value.bind(function(to) {
			$('.widget.widget_categories ul li:hover, .widget.widget_archive ul li:hover, .widget.widget_meta ul li:hover, .widget.widget_nav_menu ul#menu-service-menu li:hover, .widget.widget_nav_menu ul#menu-project li:hover, .widget.widget_nav_menu ul#menu-team li:hover, .tag-wrapper').css('border-left-color', to ? to : '' );
		});
	});

	wp.customize('arc_text',function( value ) {
		value.bind(function(to) {
			$('.widget.widget_categories ul li a, .widget.widget_archive ul li a, .widget.widget_meta ul li a, .widget.widget_nav_menu ul#menu-service-menu li a, .widget.widget_nav_menu ul#menu-project li a, .widget.widget_nav_menu ul#menu-team li a').css('color', to ? to : '' );
		});
	});

	wp.customize('side_contact_bg',function( value ) {
		value.bind(function(to) {
			$('.sidebar-contact').css('background-color', to ? to : '' );
		});
	});

	wp.customize('side_contact_text',function( value ) {
		value.bind(function(to) {
			$('.sidebar-contact h3, .sidebar-contact p').css('color', to ? to : '' );
		});
	});

	wp.customize('border_tags',function( value ) {
		value.bind(function(to) {
			$('.tagcloud a, .pagination span.current, .pagination a').css('border-color', to ? to : '' );
			$('.pagination span.current').css('color', to ? to : '' );
			$('.pagination a').css('background-color', to ? to : '' );
		});
	});


	// BUTTON STYLING

	wp.customize('blue_btn_bg',function( value ) {
		value.bind(function(to) {
			$('a.button-normal, .form-submit .submit, .project-post-wrap .view-more a, .single-team .the-form form p.submit input').css('background-color', to ? to : '' );
			$('a.button-normal, .single-team .the-form form p.submit input').css('border-color', to ? to : '' );
		});
	});

	wp.customize('blue_btn_bg_hover',function( value ) {
		value.bind(function(to) {
			$('a.button-normal:hover, .form-submit .submit:hover, .project-post-wrap .view-more a:hover, .single-team .the-form form p.submit input:hover, .tagcloud a:hover, .pagination a:hover').css('background-color', to ? to : '' );
			$('a.button-normal:hover, .single-team .the-form form p.submit input:hover, .tagcloud a:hover, .pagination a:hover').css('border-color', to ? to : '' );
		});
	});

	wp.customize('blue_btn_text',function( value ) {
		value.bind(function(to) {
			$('a.button-normal, .form-submit .submit, .project-post-wrap .view-more a, .single-team .the-form form p.submit input, .pagination a').css('color', to ? to : '' );
		});
	});

	wp.customize('blue_btn_text_hover',function( value ) {
		value.bind(function(to) {
			$('a.button-normal:hover, .form-submit .submit:hover, .project-post-wrap .view-more a:hover, .single-team .the-form form p.submit input:hover, .pagination a:hover').css('color', to ? to : '' );
		});
	});

	wp.customize('white_btn_bord',function( value ) {
		value.bind(function(to) {
			$('.button-white, .widget_mc4wp_form_widget form p input[type="submit"]').css('border-color', to ? to : '' );
		});
	});

	wp.customize('white_btn_bordbg_hov',function( value ) {
		value.bind(function(to) {
			$('.button-white:hover, .widget_mc4wp_form_widget form p input[type="submit"]:hover').css('border-color', to ? to : '' );
			$('.button-white:hover, .widget_mc4wp_form_widget form p input[type="submit"]:hover').css('background-color', to ? to : '' );
		});
	});

	wp.customize('white_btn_text',function( value ) {
		value.bind(function(to) {
			$('.button-white, .widget_mc4wp_form_widget form p input[type="submit"]').css('color', to ? to : '' );
		});
	});

	wp.customize('white_btn_text_hov',function( value ) {
		value.bind(function(to) {
			$('.button-white:hover, .widget_mc4wp_form_widget form p input[type="submit"]:hover').css('color', to ? to : '' );
		});
	});

	wp.customize('service_btn_bg',function( value ) {
		value.bind(function(to) {
			$('.service-item .view-more a').css('background-color', to ? to : '' );
		});
	});

	wp.customize('service_btn_bg_hover',function( value ) {
		value.bind(function(to) {
			$('.view-more a:hover').css('background-color', to ? to : '' );
		});
	});

	wp.customize('service_btn_text',function( value ) {
		value.bind(function(to) {
			$('.service-item .view-more a').css('color', to ? to : '' );
		});
	});

	wp.customize('service_btn_text_hover',function( value ) {
		value.bind(function(to) {
			$('.view-more a:hover').css('color', to ? to : '' );
		});
	});

	wp.customize('service_plus_bg',function( value ) {
		value.bind(function(to) {
			$('.service-item .view-more i').css('background-color', to ? to : '' );
		});
	});

	wp.customize('service_plus_icon',function( value ) {
		value.bind(function(to) {
			$('.service-item .view-more i').css('color', to ? to : '' );
		});
	});


	//FOOTER STYLING

	wp.customize('bg_footer',function( value ) {
		value.bind(function(to) {
			$('.site-footer').css('background-color', to ? to : '' );
		});
	});

	wp.customize('bord_footer',function( value ) {
		value.bind(function(to) {
			$('.footer-widget-areas').css('border-bottom-color', to ? to : '' );
			$('.socialbox-widget').css('border-top-color', to ? to : '' );
		});
	});

	wp.customize('title_widget',function( value ) {
		value.bind(function(to) {
			$('.footer-widget h2').css('color', to ? to : '' );
		});
	});

	wp.customize('content_widget',function( value ) {
		value.bind(function(to) {
			$('.footer-widget-areas .widget-footer, .widget-footer.widget_nav_menu ul li a, .widget_mc4wp_form_widget form p, .widget-footer .contact-section .text-section p').css('color', to ? to : '' );
		});
	});

	wp.customize('bg_sosmed',function( value ) {
		value.bind(function(to) {
			$('.socialbox-widget ul li a').css('background-color', to ? to : '' );
		});
	});

	wp.customize('bg_sosmed_hover',function( value ) {
		value.bind(function(to) {
			$('.socialbox-widget ul li a:hover').css('background-color', to ? to : '' );
		});
	});

	wp.customize('text_sosmed',function( value ) {
		value.bind(function(to) {
			$('.socialbox-widget ul li a').css('color', to ? to : '' );
		});
	});

	wp.customize('text_sosmed_hover',function( value ) {
		value.bind(function(to) {
			$('.socialbox-widget ul li a:hover').css('color', to ? to : '' );
		});
	});

	wp.customize('copyright_text',function( value ) {
		value.bind(function(to) {
			$('.copyright-text, .copyright-text a').css('color', to ? to : '' );
		});
	});

	


} )( jQuery );