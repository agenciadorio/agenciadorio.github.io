<?php 
/**
 * Initialize the options before anything else. 
 */
add_action( 'admin_init', 'custom_theme_options', 1 );

function custom_theme_options() {
  /**
   * Get a copy of the saved settings array. 
   */
  $saved_settings = get_option( 'option_tree_settings', array() );

  /**
   * Create your own custom array that will be passes to the 
   * OptionTree Settings API Class.
   */
  $custom_settings = array(
    'contextual_help' => array(
      'content'       => array( 
        array(
          'id'        => 'general_help',
          'title'     => 'General',
          'content'   => '<p>Help content goes here!</p>'
        )
      ),
      'sidebar'       => '<p>Sidebar content goes here!</p>',
    ),
    'sections'        => array(
		array(
			'id'		  => 'general',
			'title' 	  => 'General'
		),
		array(
			'id'		  => 'header',
			'title' 	  => 'Header'
		),
		array(
			'id'          => 'gmaps',
			'title'       => 'Google Maps'
		),
		array(
			'id'          => 'home_page',
			'title'       => 'Home Page'
		),
		array(
			'id'          => 'error_page',
			'title'       => '404 Page'
		),
		array(
			'id'          => 'reservation_page',
			'title'       => 'Reservation Page'
		),
		array(
			'id'          => 'footer',
			'title'       => 'Footer'
		),
		array(
			'id'          => 'reservation_widget',
			'title'       => 'Reservation Widget'
		)
    ),
		'settings'        => array(
			
			array(
				'label'       => "Theme layout",
				'id'          => 'theme_layout',
				'type'        => 'select',
				'desc'        => '',
				'choices'     => array(
					array(
						'label'       => 'Fullwidth',
						'value'       => 'fullwidth'
					),
					array(
						'label'       => 'Boxed',
						'value'       => 'boxed'
					),
				),
				'std'         => 'fullwidth',
				'rows'        => '',
				'post_type'   => '',
				'taxonomy'    => '',
				'class'       => '',
				'section'     => 'general'
			),
			
			array(
				'label'       => "Background Type",
				'id'          => 'background_type',
				'type'        => 'select',
				'desc'        => "What kind of background to use (pattern - small image that repeats to create background, image - big background image that don't repeat)",
				'choices'     => array(
					array(
						'label'       => 'Image',
						'value'       => 'image'
					),
					array(
						'label'       => 'Pattern',
						'value'       => 'pattern'
					),
				),
				'std'         => 'image',
				'rows'        => '',
				'post_type'   => '',
				'taxonomy'    => '',
				'class'       => '',
				'section'     => 'general'
			), 
			
			array(
				'label'       => 'Background',
				'id'          => 'background',
				'type'        => 'background',
				'desc'        => 'Link on background image or pattern (depend on previous options)',
				'std'         => '',
				'rows'        => '',
				'post_type'   => '',
				'taxonomy'    => '',
				'class'       => '',
				'section'     => 'general'
			),
			array(
				'label'       => 'Primary color',
				'id'          => 'primary_color',
				'type'        => 'colorpicker',
				'desc'        => 'Set color for various elements in page content.',
				'std'         => '#de543e',
				'rows'        => '',
				'post_type'   => '',
				'taxonomy'    => '',
				'class'       => '',
				'section'     => 'general'
			),
			array(
				'label'       => 'Secondary color',
				'id'          => 'secondary_color',
				'type'        => 'colorpicker',
				'desc'        => 'Set color for header and footer.',
				'std'         => '#677c8b',
				'rows'        => '',
				'post_type'   => '',
				'taxonomy'    => '',
				'class'       => '',
				'section'     => 'general'
			),
			
			array(
				'id'          => 'rooms_list_count',
				'label'       => "Number of rooms on the room list page",
				'desc'        => "Enter -1 if you want that all rooms from the Dasbhoard > Rooms shows on the room list page",
				'std'         => '9',
				'type'        => 'text',
				'section'     => 'general',
				'class'       => '',
			),
			
			
			array(
				'id'          => 'gallery_image_count',
				'label'       => "Number of image in gallery",
				'desc'        => "Set number of image that will shows in gallery",
				'std'         => '9',
				'type'        => 'text',
				'section'     => 'general',
				'class'       => '',
			),
			
			array(
				'id'          => 'logo_url',
				'label'       => 'Logo Url',
				'desc'        => 'Logo should be 170x57px',
				'std'         => '',
				'type'        => 'background',
				'section'     => 'header',
				'class'       => '',
			), 
			array(
				'id'          => 'small_logo_url',
				'label'       => 'Mobile Logo Url',
				'desc'        => 'Mobile logo should be 145x50px',
				'std'         => '',
				'type'        => 'background',
				'section'     => 'header',
				'class'       => '',
			), 
			array(
				'label'       => 'Top header bar',
				'id'          => 'top_header_bar',
				'type'        => 'checkbox',
				'desc'        => 'Specify does top bar in header should shows or not',
				'choices'     => array(
					array (
						'label'       => 'Off',
						'value'       => 'off'
					)
				),
				'std'         => '',
				'rows'        => '',
				'post_type'   => '',
				'taxonomy'    => '',
				'class'       => '',
				'section'     => 'header'
			),
			array(
				'id'          => 'email',
				'label'       => "Email",
				'desc'        => "",
				'std'         => '',
				'type'        => 'text',
				'section'     => 'header',
			),
			array(
				'id'          => 'telephone',
				'label'       => "Telephone",
				'desc'        => "",
				'std'         => '',
				'type'        => 'text',
				'section'     => 'header',
			),
			array(
				'id'          => 'general_address',
				'label'       => "General address",
				'desc'        => "",
				'std'         => '',
				'type'        => 'text',
				'section'     => 'header',
			),
			array(
				'id'          => 'header_reservation_link',
				'label'       => "Header Link on Reservation Page",
				'desc'        => "",
				'std'         => '',
				'type'        => 'text',
				'section'     => 'header',
				'class'       => '',
			),
			array(
				'label'       => 'Language selector',
				'id'          => 'language_selector',
				'type'        => 'checkbox',
				'desc'        => 'Specify does language selector in header should shows or not',
				'choices'     => array(
					array (
						'label'       => 'Off',
						'value'       => 'off'
					)
				),
				'std'         => '',
				'rows'        => '',
				'post_type'   => '',
				'taxonomy'    => '',
				'class'       => '',
				'section'     => 'header'
			),
			array(
				'id'          => 'website_language',
				'label'       => 'Current Website Language',
				'desc'        => "",
				'std'         => 'English',
				'type'        => 'text',
				'section'     => 'header',
				'class'       => '',
			),
			array(
				'id'          => 'country_flag',
				'label'       => 'Current Language Flag',
				'desc'        => "",
				'std'         => '/images/languages/gb.png',
				'type'        => 'text',
				'section'     => 'header',
			),
			array(
				'label'       => '404 Error Page Background',
				'id'          => 'error_text_background',
				'type'        => 'background',
				'desc'        => '',
				'std'         => '',
				'post_type'   => '',
				'taxonomy'    => '',
				'class'       => '',
				'section'     => 'error_page'
			),		
			array(
				'label'       => '404 Error Code',
				'id'          => 'error_code',
				'type'        => 'text',
				'desc'        => '',
				'std'         => '404',
				'post_type'   => '',
				'taxonomy'    => '',
				'class'       => '',
				'section'     => 'error_page'
			),
			array(
				'label'       => '404 Error Code Explanations',
				'id'          => 'error_code_explanation',
				'type'        => 'text',
				'desc'        => '',
				'std'         => 'page not found',
				'post_type'   => '',
				'taxonomy'    => '',
				'class'       => '',
				'section'     => 'error_page'
			),	
			array(
				'label'       => '404 Error Text',
				'id'          => 'error_text',
				'type'        => 'textarea',
				'desc'        => '',
				'std'         => '<p>Sorry, but the page your are looking for has not been found. Try checking URL for error, then hit refresh button in your browser or return to <a href="">home page</a></p>',
				'rows'        => '5',
				'post_type'   => '',
				'taxonomy'    => '',
				'class'       => '',
				'section'     => 'error_page'
			),
			array(
				'id'          => 'gmaps_key',
				'label'       => 'Google Maps API Key',
				'desc'        => 'To learn more about obtaining API key you should visit: <a href="https://developers.google.com/maps/documentation/javascript/tutorial#api_key">Obtaining an API Key</a>',
				'std'         => '',
				'type'        => 'text',
				'section'     => 'gmaps',
				'class'       => '',
			),
			array(
				'id'          => 'map_center',
				'label'       => "Coordinates of Map Center",
				'desc'        => 'Enter in following format: latitude,longitude.',
				'std'         => '',
				'type'        => 'text',
				'section'     => 'gmaps',
				'class'       => '',
			),
			array(
				'label'       => "Map Type",
				'id'          => 'home_map_type',
				'type'        => 'select',
				'desc'        => 'ROADMAP - displays the normal, default 2D tiles of Google Maps.<br />SATELLITE - displays photographic tiles.<br />HYBRID - displays a mix of photographic tiles and a tile layer for prominent features (roads, city names).<br />TERRAIN - displays physical relief tiles for displaying elevation and water features (mountains, rivers, etc.).',
				'choices'     => array(
					array(
						'label'       => 'HYBRID',
						'value'       => 'HYBRID'
					),
					array(
						'label'       => 'SATELLITE',
						'value'       => 'SATELLITE'
					),
					array(
						'label'       => 'ROADMAP',
						'value'       => 'ROADMAP'
					),
					array(
						'label'       => 'TERRAIN',
						'value'       => 'TERRAIN'
					)
				),
				'std'         => 'HYBRID',
				'rows'        => '',
				'post_type'   => '',
				'taxonomy'    => '',
				'class'       => '',
				'section'     => 'gmaps'
			),
			array(
				'id'          => 'home_map_zoom',
				'label'       => "Map Zoom Level",
				'desc'        => 'Zoom levels may be between 0 (the lowest zoom level, in which the entire world can be seen) to 21+ (individual buildings).',
				'std'         => '',
				'type'        => 'text',
				'section'     => 'gmaps',
				'class'       => '',
			),	
			array(
				'id'          => 'home_page_marker',
				'label'       => "Coordinates of Map Market",
				'desc'        => 'Enter in following format: latitude1, longitude1;second_marker_latitude2, second_marker_longitude2;',
				'std'         => '',
				'type'        => 'text',
				'section'     => 'gmaps',
				'class'       => '',
			),
			array(
				'id'          => 'where_to_find_us_title',
				'label'       => 'Hotel name',
				'type'        => 'text',
				'desc'        => 'Set hotel names if you have several hotel branch separate their name by semicolon (;)',
				'std'         => '',
				'class'       => '',
				'section'     => 'gmaps'
			),
			array(
				'id'          => 'where_to_find_us_address',
				'label'       => 'Hotel address',
				'type'        => 'text',
				'desc'        => 'Set hotel address if you have several hotel branch separate their addresses by semicolon (;)',
				'std'         => '',
				'class'       => '',
				'section'     => 'gmaps'
			),
									
			array(
				'id'          => 'main_slider',
				'label'       => 'Main Slider',
				'desc'        => "By default theme will use first slider that they find. If you want to change default slider please enter it's alias in this field.",
				'std'         => '',
				'type'        => 'text',
				'section'     => 'home_page',
				'class'       => '',
			),
			
			array(
				'label'       => 'Room Section',
				'id'          => 'room_section',
				'type'        => 'checkbox',
				'desc'        => 'Hide room section from main page',
				'choices'     => array(
					array (
						'label'       => 'Off',
						'value'       => 'off'
					)
				),
				'std'         => '',
				'rows'        => '',
				'post_type'   => '',
				'taxonomy'    => '',
				'class'       => '',
				'section'     => 'home_page'
			),
			array(
				'label'       => 'About Us Section',
				'id'          => 'aboutus_section',
				'type'        => 'checkbox',
				'desc'        => 'Hide about us section from main page (section with about us and testimonials content)',
				'choices'     => array(
					array (
						'label'       => 'Off',
						'value'       => 'off'
					)
				),
				'std'         => '',
				'rows'        => '',
				'post_type'   => '',
				'taxonomy'    => '',
				'class'       => '',
				'section'     => 'home_page'
			),
			array(
				'label'       => 'Info Section',
				'id'          => 'info_section',
				'type'        => 'checkbox',
				'desc'        => 'Hide info section from main page (section with our restaurant, our latest news and where to find us)',
				'choices'     => array(
					array (
						'label'       => 'Off',
						'value'       => 'off'
					)
				),
				'std'         => '',
				'rows'        => '',
				'post_type'   => '',
				'taxonomy'    => '',
				'class'       => '',
				'section'     => 'home_page'
			),	  
			array(
				'id'          => 'main_room_header',
				'label'       => 'Room Section Header',
				'desc'        => "Set header for room section",
				'std'         => 'Check our comfortable rooms',
				'type'        => 'text',
				'section'     => 'home_page',
				'class'       => '',
			),
			array(
				'id'          => 'main_room_description',
				'label'       => 'Room Section Description',
				'type'        => 'textarea',
				'rows'        => '2',
				'desc'        => 'Set secription for room section',
				'std'         => "Hover your mouse on one of images below and then click on calendar icon to check room's availability or magnifier icon to learn more about this room or reserve it online",
				'class'       => '',
				'section'     => 'home_page'
			),
			array(
				'id'          => 'link_all_room',
				'label'       => 'Link on All Rooms Page',
				'type'        => 'text',
				'desc'        => "Set link on all rooms list (leave this field blank if you don't want to show link on all rooms list",
				'std'         => "",
				'class'       => '',
				'section'     => 'home_page'
			),
			array(
				'id'          => 'aboutus_header',
				'label'       => 'About us Header',
				'desc'        => "Set header for about us section",
				'std'         => '<span class="icon-book"></span> About nation hotel',
				'type'        => 'text',
				'section'     => 'home_page',
				'class'       => '',
			),
			array(
				'id'          => 'aboutus_image',
				'label'       => 'About us Image',
				'desc'        => "Set image for about us section",
				'std'         => '',
				'type'        => 'background',
				'section'     => 'home_page',
				'class'       => '',
			),
			array(
				'label'       => 'About us Text',
				'id'          => 'aboutus_text',
				'type'        => 'textarea',
				'desc'        => 'Set text for about us section',
				'std'         => '<div>Lorem ipsum dolor sit amet, consectetur adipiscing elit donec porttitor lectus at neque sollicitudin. Lorem ipsum dolor sitatu amet, consectetur adipiscing elit fusce ut donec. </div>
							<ul>
								<li>Check-in: 02:00 P.M.; Check-out: 12:00 A.M.</li>
								<li>Free High Speed Wi-Fi Internet in Every Room</li>
								<li>In Room Dining Available from 06:00 P.M. to 10:30 P.M.</li>
								<li>Free Local Self Parking Available</li>
							</ul>
							<a href="">Online Reservations</a>',
				'rows'        => '8',
				'post_type'   => '',
				'taxonomy'    => '',
				'class'       => '',
				'section'     => 'home_page'
			),
			array(
				'id'          => 'testimonials_header',
				'label'       => 'Testimonials header',
				'type'        => 'text',
				'desc'        => "Set header for testimonials",
				'std'         => "<span class='icon-comments-alt'></span> Testimonials",
				'class'       => '',
				'section'     => 'home_page'
			),
			array(
				'id'          => 'testimonials_count',
				'label'       => 'Testimonials count',
				'type'        => 'text',
				'desc'        => "Set how much testimonials should shows in testimonials section",
				'std'         => "2",
				'class'       => '',
				'section'     => 'home_page'
			),	
			array(
				'id'          => 'text_section_header',
				'label'       => 'Text section header',
				'type'        => 'text',
				'desc'        => 'Set header for test section',
				'std'         => '<span class="icon-food"></span> Our restaurant',
				'class'       => '',
				'section'     => 'home_page'
			),
			array(
				'id'          => 'text_section_content',
				'label'       => 'Content of text section',
				'type'        => 'textarea',
				'desc'        => 'Set content for text section',
				'std'         => '<p>Curabitur erat nisl, ultrices non velit fermentum at, blandit pretium nisi. Sed auctor mi eu ornare auctor. Aenean cursus lacinia odio, sed viverra ligula varius at.</p>
						<p>Phasellus scelerisque lacus vel orci feugiat, non sagittis orci sollicitudin. Ut ac lectus eu velit etecit.</p>',
				'rows'        => '4',
				'class'       => '',
				'section'     => 'home_page'
			),
			array(
				'id'          => 'latest_news',
				'label'       => 'Latest news header',
				'type'        => 'text',
				'desc'        => 'Set latest news header',
				'std'         => '<span class="icon-pencil"></span> Our latest news',
				'class'       => '',
				'section'     => 'home_page'
			),	
			array(
				'id'          => 'latest_news_count',
				'label'       => 'Latest news count',
				'type'        => 'text',
				'desc'        => 'Set how much news should shows in latest news sectionss',
				'std'         => '3',
				'class'       => '',
				'section'     => 'home_page'
			), 
			array(
				'id'          => 'where_to_find_us_header',
				'label'       => 'Where to find us header',
				'type'        => 'text',
				'desc'        => 'Set header for where to find us section',
				'std'         => '<span class="icon-globe"></span> Where to find us',
				'class'       => '',
				'section'     => 'home_page'
			),
			array(
				'id'          => 'currency_symbol',
				'label'       => 'Currency symbol',
				'desc'        => 'Available Room Header that shows on Step2 of reservation form',
				'std'         => 'Available rooms on specified date',
				'type'        => 'text',
				'section'     => 'reservation_page',
				'class'       => '',
			),
			array(
				'id'          => 'max_room_rent',
				'label'       => 'Max Number of Room to Rent',
				'desc'        => 'Maximum number of room that can be rent in one reservation request',
				'std'         => '8',
				'type'        => 'text',
				'section'     => 'reservation_page',
				'class'       => '',
			), 
			array(
				'id'          => 'max_adult_room',
				'label'       => 'Max Number of Adult in one Room',
				'desc'        => 'Max number of adult (18+) that can be in one rented room',
				'std'         => '7',
				'type'        => 'text',
				'section'     => 'reservation_page',
				'class'       => '',
			), 
			array(
				'id'          => 'max_children_room',
				'label'       => 'Max Number of Children in one Room',
				'desc'        => 'Max number of children (0-17) that can be in one rented room',
				'std'         => '7',
				'type'        => 'text',
				'section'     => 'reservation_page',
				'class'       => '',
			), 
			array(
				'id'          => 'available_room_header',
				'label'       => 'Available Room Header',
				'desc'        => 'Available Room Header that shows on Step2 of reservation form',
				'std'         => 'Available rooms on specified date',
				'type'        => 'text',
				'section'     => 'reservation_page',
				'class'       => '',
			), 
			array(
				'id'          => 'tax_note',
				'label'       => 'Tax Note',
				'desc'        => "Tax note that shows in Step3 and Step4 above total sum (leave this field blank if you don't want to show tax note).",
				'std'         => 'Tax (7%) not included',
				'type'        => 'text',
				'section'     => 'reservation_page',
				'class'       => '',
			), 
			array(
				'id'          => 'personal_info_header',
				'label'       => 'Personal Info Header',
				'desc'        => 'Personal Info Header that shows on Step3 of reservation form',
				'std'         => 'Personal Info',
				'type'        => 'text',
				'section'     => 'reservation_page',
				'class'       => '',
			), 
			array(
				'id'          => 'personal_info_description',
				'label'       => 'Personal Info Description',
				'desc'        => 'Personal Info Description that shows on Step3 of reservation form',
				'std'         => '<span class="main-reservation-form-asterisk">*</span> Indicated required fields.',
				'type'        => 'text',
				'section'     => 'reservation_page',
				'class'       => '',
			), 
			array(
				'id'          => 'payment_info_header',
				'label'       => 'Payment Info Header',
				'desc'        => 'Payment Info Header that shows on Step3 of reservation form',
				'std'         => 'Payment Info',
				'type'        => 'text',
				'section'     => 'reservation_page',
				'class'       => '',
			), 
			array(
				'id'          => 'payment_info_description',
				'label'       => 'Payment Info Description',
				'desc'        => 'Payment Info Description that shows on Step3 of reservation form',
				'std'         => "To guarantee your booking, we need a valid credit card. We have the right to cancel your booking if you're credit card is not valid. Your credit cart will be charged after we review your booking request.",
				'type'        => 'textarea',
				'rows'		  => '4',
				'section'     => 'reservation_page',
				'class'       => '',
			), 
			array(
				'id'          => 'before_confirm_info',
				'label'       => 'Confirmation Info',
				'desc'        => 'Confirmation Info that shows on Step3 of reservation form immediately before confirm reservation button',
				'std'         => "<p><b>Cancellations:</b> You may cancel your reservation the day prior to arrival</p> <p><b>Credit Card:</b> We will not accept any reservations without proper credit card guarantee.</p>",
				'type'        => 'textarea',
				'rows' 		  => '5',
				'section'     => 'reservation_page',
				'class'       => '',
			), 
			array(
				'id'          => 'reservation_complete_header',
				'label'       => 'Reservation Completed Header',
				'desc'        => 'Header that shows on Step4 when reservation completed successfully',
				'std'         => "Your reservation was completed!",
				'type'        => 'text',
				'section'     => 'reservation_page',
				'class'       => '',
			), 
			array(
				'id'          => 'reservation_complete_description',
				'label'       => 'Reservation Completed Description',
				'desc'        => 'Description that shows on Step4 when reservation completed successfully',
				'std'         => "We need some time to review your request (usually it takes only a few hours). After we do this we'll send booking confirmation on your email. In the meantime if you have any question feel free to contact us.",
				'type'        => 'textarea',
				'rows'		  => '4',
				'section'     => 'reservation_page',
				'class'       => '',
			), 
			array(
				'id'          => 'reservation_complete_header_paypal',
				'label'       => 'Reservation Completed Header PayPal',
				'desc'        => 'Header that shows on Step4 when reservation completed successfully if user completed payment with PayPal',
				'std'         => "Your reservation was completed!",
				'type'        => 'text',
				'section'     => 'reservation_page',
				'class'       => '',
			), 
			array(
				'id'          => 'reservation_complete_description_paypal',
				'label'       => 'Reservation Completed Description PayPal',
				'desc'        => 'Description that shows on Step4 when reservation completed successfully if user completed payment with PayPal',
				'std'         => "We need some time to review your request (usually it takes only a few hours). After we do this we'll send booking confirmation on your email. In the meantime if you have any question feel free to contact us.",
				'type'        => 'textarea',
				'rows'		  => '4',
				'section'     => 'reservation_page',
				'class'       => '',
			), 
			array(
				'label'       => 'Copyright text',
				'id'          => 'copyright_text',
				'type'        => 'textarea',
				'desc'        => '',
				'std'         => 'Copyright &copy; 2013 Nation HOTEL theme. All right reserved.',
				'rows'        => '4',
				'post_type'   => '',
				'taxonomy'    => '',
				'class'       => '',
				'section'     => 'footer'
			),
			array(
				'label'       => 'Show Reservation Widget',
				'id'          => 'show_res_widget',
				'type'        => 'checkbox',
				'desc'        => 'Should the reservation widget shows or not?',
				'choices'     => array(
					array (
						'label'       => 'Show',
						'value'       => 'show'
					)
				),
				'std'         => '',
				'rows'        => '',
				'post_type'   => '',
				'taxonomy'    => '',
				'class'       => '',
				'section'     => 'reservation_widget'
			),
						
			array(
				'label'       => 'Link on the Reservation Form',
				'id'          => 'booking_link',
				'type'        => 'text',
				'desc'        => 'Link on the reservation form',
				'std'         => '',
				'taxonomy'    => '',
				'class'       => '',
				'section'     => 'reservation_widget'
			),
						
			array(
				'label'       => 'Widget header text',
				'id'          => 'widget_header_text',
				'type'        => 'text',
				'desc'        => 'The text that will shows at the widget header.',
				'std'         => '',
				'taxonomy'    => '',
				'class'       => '',
				'section'     => 'reservation_widget'
			),
			
			array(
				'label'       => 'Max adult number',
				'id'          => 'widget_max_adult',
				'type'        => 'text',
				'desc'        => 'Maximum number of adults that user can select in the reservation widget.',
				'std'         => '2',
				'taxonomy'    => '',
				'class'       => '',
				'section'     => 'reservation_widget'
			),			
			
			array(
				'label'       => 'Max children number',
				'id'          => 'widget_max_children',
				'type'        => 'text',
				'desc'        => 'Maximum number of children that user can select in the reservation widget.',
				'std'         => '2',
				'taxonomy'    => '',
				'class'       => '',
				'section'     => 'reservation_widget'
			),
			
			array(
				'label'       => 'Max room number',
				'id'          => 'widget_room_number',
				'type'        => 'text',
				'desc'        => 'Maximum number of room that user can select in the reservation widget.',
				'std'         => '2',
				'taxonomy'    => '',
				'class'       => '',
				'section'     => 'reservation_widget'
			)
		)
	);
  
  /* settings are not the same update the DB */
  if ( $saved_settings !== $custom_settings ) {
    update_option( 'option_tree_settings', $custom_settings ); 
  }
  
}

?>