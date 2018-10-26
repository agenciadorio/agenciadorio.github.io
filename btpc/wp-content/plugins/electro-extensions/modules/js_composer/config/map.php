<?php
/**
 * WPBakery Visual Composer Shortcodes settings
 *
 * @package electro
 *
 */

if ( function_exists( 'vc_map' ) ) :

	#-----------------------------------------------------------------
	# Electro Ad Element
	#-----------------------------------------------------------------
	vc_map(
		array(
			'name'			=> esc_html__( 'Ad Block', 'electro-extensions' ),
			'base'  		=> 'electro_ad_block',
			'description'	=> esc_html__( 'Add Ad Block to your page.', 'electro-extensions' ),
			'category'		=> esc_html__( 'Electro Elements', 'electro-extensions' ),
			'icon' 			=> '',
			'params' 		=> array(
				array(
					'type' 			=> 'attach_image',
					'heading' 		=> esc_html__( 'Image', 'electro-extensions' ),
					'param_name' 	=> 'image',
				),
				array(
					'type'			=> 'textarea',
					'heading'		=> esc_html__('Caption Text', 'electro-extensions' ),
					'param_name'	=> 'caption_text',
				),
				array(
					'type'			=> 'textarea',
					'heading'		=> esc_html__('Action Text', 'electro-extensions' ),
					'param_name'	=> 'action_text',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Action Link', 'electro-extensions' ),
					'param_name'	=> 'action_link',
				),
			),
		)
	);

	#-----------------------------------------------------------------
	# Electro Feature Element
	#-----------------------------------------------------------------
	vc_map(
		array(
			'name'			=> esc_html__( 'Feature Block', 'electro-extensions' ),
			'base'  		=> 'electro_feature_block',
			'description'	=> esc_html__( 'Add Feature Block to your page.', 'electro-extensions' ),
			'category'		=> esc_html__( 'Electro Elements', 'electro-extensions' ),
			'icon' 			=> '',
			'params' 		=> array(
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Icon 1', 'electro-extensions' ),
					'param_name'	=> 'icon_1',
				),
				array(
					'type'			=> 'textarea',
					'heading'		=> esc_html__('Text 1', 'electro-extensions' ),
					'param_name'	=> 'text_1',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Icon 2', 'electro-extensions' ),
					'param_name'	=> 'icon_2',
				),
				array(
					'type'			=> 'textarea',
					'heading'		=> esc_html__('Text 2', 'electro-extensions' ),
					'param_name'	=> 'text_2',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Icon 3', 'electro-extensions' ),
					'param_name'	=> 'icon_3',
				),
				array(
					'type'			=> 'textarea',
					'heading'		=> esc_html__('Text 3', 'electro-extensions' ),
					'param_name'	=> 'text_3',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Icon 4', 'electro-extensions' ),
					'param_name'	=> 'icon_4',
				),
				array(
					'type'			=> 'textarea',
					'heading'		=> esc_html__('Text 4', 'electro-extensions' ),
					'param_name'	=> 'text_4',
				),
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Icon 5', 'electro-extensions' ),
					'param_name'	=> 'icon_5',
				),
				array(
					'type'			=> 'textarea',
					'heading'		=> esc_html__('Text 5', 'electro-extensions' ),
					'param_name'	=> 'text_5',
				),
			),
		)
	);

	#-----------------------------------------------------------------
	# Electro Jumbotron Element
	#-----------------------------------------------------------------
	vc_map(
		array(
			'name'			=> esc_html__( 'Jumbotron', 'electro-extensions' ),
			'base'  		=> 'electro_jumbotron',
			'description'	=> esc_html__( 'Add Jumbotron to your page.', 'electro-extensions' ),
			'category'		=> esc_html__( 'Electro Elements', 'electro-extensions' ),
			'icon' 			=> '',
			'params' 		=> array(
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Title', 'electro-extensions' ),
					'param_name'	=> 'title',
				),
				array(
					'type'			=> 'textarea',
					'heading'		=> esc_html__('Sub Title', 'electro-extensions' ),
					'param_name'	=> 'sub_title',
				),
				array(
					'type' 			=> 'attach_image',
					'heading' 		=> esc_html__( 'Image', 'electro-extensions' ),
					'param_name' 	=> 'image',
				),
			),
		)
	);

	#-----------------------------------------------------------------
	# Electro Product Tabs Element
	#-----------------------------------------------------------------
	vc_map(
		array(
			'name'			=> esc_html__( 'Product Tabs', 'electro-extensions' ),
			'base'  		=> 'electro_product_tabs',
			'description'	=> esc_html__( 'Add Product Tabs to your page.', 'electro-extensions' ),
			'category'		=> esc_html__( 'Electro Elements', 'electro-extensions' ),
			'icon' 			=> '',
			'params' 		=> array(
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Tab #1 title', 'electro-extensions' ),
					'param_name'	=> 'tab_title_1',
				),

				array(
					'type'			=> 'dropdown',
					'heading'		=> esc_html__( 'Tab #1 Content, Show :', 'electro-extensions' ),
					'param_name'	=> 'tab_content_1',
					'value'			=> array(
						esc_html__( 'Select', 'electro-extensions' )				=> '',
						esc_html__( 'Featured Products', 'electro-extensions' )		=> 'featured_products' ,
						esc_html__( 'On Sale Products', 'electro-extensions' )		=> 'sale_products' 	,
						esc_html__( 'Top Rated Products', 'electro-extensions' )	=> 'top_rated_products' ,
						esc_html__( 'Recent Products', 'electro-extensions' )		=> 'recent_products' 	,
						esc_html__( 'Best Selling Products', 'electro-extensions' )	=> 'best_selling_products',
						esc_html__( 'Products', 'electro-extensions' )				=> 'products' ,
						esc_html__( 'Product Category', 'electro-extensions' )		=> 'product_category' ,
					),
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Enter Product IDs', 'electro-extensions' ),
					'description' => esc_html__( 'This will only for Products Shortcode', 'electro-extensions' ),
					'param_name' => 'product_id_1',
					'holder' => 'div'
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Enter Category Slug', 'electro-extensions' ),
					'description' => esc_html__( 'This will only for Product Category Shortcode', 'electro-extensions' ),
					'param_name' => 'category_1',
					'holder' => 'div'
				),

				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Tab #2 title', 'electro-extensions' ),
					'param_name'	=> 'tab_title_2',
				),

				array(
					'type'			=> 'dropdown',
					'heading'		=> esc_html__( 'Tab #2 Content, Show :', 'electro-extensions' ),
					'param_name'	=> 'tab_content_2',
					'value'			=> array(
						esc_html__( 'Select', 'electro-extensions' ) 				=> '',
						esc_html__( 'Featured Products', 'electro-extensions' )		=> 'featured_products' ,
						esc_html__( 'On Sale Products', 'electro-extensions' )		=> 'sale_products' 	,
						esc_html__( 'Top Rated Products', 'electro-extensions' )	=> 'top_rated_products' ,
						esc_html__( 'Recent Products', 'electro-extensions' )		=> 'recent_products' 	,
						esc_html__( 'Best Selling Products', 'electro-extensions' )	=> 'best_selling_products',
						esc_html__( 'Products', 'electro-extensions' )				=> 'products' ,
						esc_html__( 'Product Category', 'electro-extensions' )		=> 'product_category' ,
					),
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Enter Product IDs', 'electro-extensions' ),
					'description' => esc_html__( 'This will only for Products Shortcode', 'electro-extensions' ),
					'param_name' => 'product_id_2',
					'holder' => 'div'
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Enter Category Slug', 'electro-extensions' ),
					'description' => esc_html__( 'This will only for Product Category Shortcode', 'electro-extensions' ),
					'param_name' => 'category_2',
					'holder' => 'div'
				),

				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Tab #3 title', 'electro-extensions' ),
					'param_name'	=> 'tab_title_3',
				),

				array(
					'type'			=> 'dropdown',
					'heading'		=> esc_html__( 'Tab #3 Content, Show :', 'electro-extensions' ),
					'param_name'	=> 'tab_content_3',
					'value'			=> array(
						esc_html__( 'Select', 'electro-extensions' ) 				=> '',
						esc_html__( 'Featured Products', 'electro-extensions' )		=> 'featured_products' ,
						esc_html__( 'On Sale Products', 'electro-extensions' )		=> 'sale_products' 	,
						esc_html__( 'Top Rated Products', 'electro-extensions' )	=> 'top_rated_products' ,
						esc_html__( 'Recent Products', 'electro-extensions' )		=> 'recent_products' 	,
						esc_html__( 'Best Selling Products', 'electro-extensions' )	=> 'best_selling_products',
						esc_html__( 'Products', 'electro-extensions' )				=> 'products' ,
						esc_html__( 'Product Category', 'electro-extensions' )		=> 'product_category' ,
					),
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Enter Product IDs', 'electro-extensions' ),
					'description' => esc_html__( 'This will only for Products Shortcode', 'electro-extensions' ),
					'param_name' => 'product_id_3',
					'holder' => 'div'
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Enter Category Slug', 'electro-extensions' ),
					'description' => esc_html__( 'This will only for Product Category Shortcode', 'electro-extensions' ),
					'param_name' => 'category_3',
					'holder' => 'div'
				),

				array(
					'type' => 'textfield',
			        'heading' => esc_html__( 'Enter Product Items', 'electro-extensions' ),
			        'param_name' => 'product_items',
			        'holder' => 'div'
		      	),

		      	array(
					'type' => 'textfield',
			        'heading' => esc_html__( 'Enter Product Columns', 'electro-extensions' ),
			        'param_name' => 'product_columns',
			        'holder' => 'div'
		      	),
			),
		)
	);

	#-----------------------------------------------------------------
	# Electro Product Carousel Tabs Element
	#-----------------------------------------------------------------
	vc_map(
		array(
			'name'			=> esc_html__( 'Product Carousel Tabs', 'electro-extensions' ),
			'base'  		=> 'electro_products_carousel_tabs',
			'description'	=> esc_html__( 'Add Product Carousel Tabs to your page.', 'electro-extensions' ),
			'category'		=> esc_html__( 'Electro Elements', 'electro-extensions' ),
			'icon' 			=> '',
			'params' 		=> array(
				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Tab #1 title', 'electro-extensions' ),
					'param_name'	=> 'tab_title_1',
				),

				array(
					'type'			=> 'dropdown',
					'heading'		=> esc_html__( 'Tab #1 Content, Show :', 'electro-extensions' ),
					'param_name'	=> 'tab_content_1',
					'value'			=> array(
						esc_html__( 'Select', 'electro-extensions' )				=> '',
						esc_html__( 'Featured Products', 'electro-extensions' )		=> 'featured_products' ,
						esc_html__( 'On Sale Products', 'electro-extensions' )		=> 'sale_products' 	,
						esc_html__( 'Top Rated Products', 'electro-extensions' )	=> 'top_rated_products' ,
						esc_html__( 'Recent Products', 'electro-extensions' )		=> 'recent_products' 	,
						esc_html__( 'Best Selling Products', 'electro-extensions' )	=> 'best_selling_products',
						esc_html__( 'Products', 'electro-extensions' )				=> 'products' ,
						esc_html__( 'Product Category', 'electro-extensions' )		=> 'product_category' ,
					),
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Enter Product IDs', 'electro-extensions' ),
					'description' => esc_html__( 'This will only for Products Shortcode', 'electro-extensions' ),
					'param_name' => 'product_id_1',
					'holder' => 'div'
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Enter Category Slug', 'electro-extensions' ),
					'description' => esc_html__( 'This will only for Product Category Shortcode', 'electro-extensions' ),
					'param_name' => 'category_1',
					'holder' => 'div'
				),

				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Tab #2 title', 'electro-extensions' ),
					'param_name'	=> 'tab_title_2',
				),

				array(
					'type'			=> 'dropdown',
					'heading'		=> esc_html__( 'Tab #2 Content, Show :', 'electro-extensions' ),
					'param_name'	=> 'tab_content_2',
					'value'			=> array(
						esc_html__( 'Select', 'electro-extensions' ) 				=> '',
						esc_html__( 'Featured Products', 'electro-extensions' )		=> 'featured_products' ,
						esc_html__( 'On Sale Products', 'electro-extensions' )		=> 'sale_products' 	,
						esc_html__( 'Top Rated Products', 'electro-extensions' )	=> 'top_rated_products' ,
						esc_html__( 'Recent Products', 'electro-extensions' )		=> 'recent_products' 	,
						esc_html__( 'Best Selling Products', 'electro-extensions' )	=> 'best_selling_products',
						esc_html__( 'Products', 'electro-extensions' )				=> 'products' ,
						esc_html__( 'Product Category', 'electro-extensions' )		=> 'product_category' ,
					),
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Enter Product IDs', 'electro-extensions' ),
					'description' => esc_html__( 'This will only for Products Shortcode', 'electro-extensions' ),
					'param_name' => 'product_id_2',
					'holder' => 'div'
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Enter Category Slug', 'electro-extensions' ),
					'description' => esc_html__( 'This will only for Product Category Shortcode', 'electro-extensions' ),
					'param_name' => 'category_2',
					'holder' => 'div'
				),

				array(
					'type'			=> 'textfield',
					'heading'		=> esc_html__('Tab #3 title', 'electro-extensions' ),
					'param_name'	=> 'tab_title_3',
				),

				array(
					'type'			=> 'dropdown',
					'heading'		=> esc_html__( 'Tab #3 Content, Show :', 'electro-extensions' ),
					'param_name'	=> 'tab_content_3',
					'value'			=> array(
						esc_html__( 'Select', 'electro-extensions' ) 				=> '',
						esc_html__( 'Featured Products', 'electro-extensions' )		=> 'featured_products' ,
						esc_html__( 'On Sale Products', 'electro-extensions' )		=> 'sale_products' 	,
						esc_html__( 'Top Rated Products', 'electro-extensions' )	=> 'top_rated_products' ,
						esc_html__( 'Recent Products', 'electro-extensions' )		=> 'recent_products' 	,
						esc_html__( 'Best Selling Products', 'electro-extensions' )	=> 'best_selling_products',
						esc_html__( 'Products', 'electro-extensions' )				=> 'products' ,
						esc_html__( 'Product Category', 'electro-extensions' )		=> 'product_category' ,
					),
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Enter Product IDs', 'electro-extensions' ),
					'description' => esc_html__( 'This will only for Products Shortcode', 'electro-extensions' ),
					'param_name' => 'product_id_3',
					'holder' => 'div'
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Enter Category Slug', 'electro-extensions' ),
					'description' => esc_html__( 'This will only for Product Category Shortcode', 'electro-extensions' ),
					'param_name' => 'category_3',
					'holder' => 'div'
				),

				array(
					'type' => 'textfield',
			        'heading' => esc_html__( 'Enter Product Items', 'electro-extensions' ),
			        'param_name' => 'product_items',
			        'holder' => 'div'
		      	),

		      	array(
					'type' => 'textfield',
			        'heading' => esc_html__( 'Enter Product Columns', 'electro-extensions' ),
			        'param_name' => 'product_columns',
			        'holder' => 'div'
		      	),

		      	array(
					'type'			=> 'dropdown',
					'heading'		=> esc_html__( 'Header Align', 'electro-extensions' ),
					'param_name'	=> 'nav_align',
					'value'			=> array(
						esc_html__( 'Select', 'electro-extensions' ) 	=> '',
						esc_html__( 'Center', 'electro-extensions' )	=> 'center',
						esc_html__( 'Left', 'electro-extensions' )		=> 'left',
						esc_html__( 'Right', 'electro-extensions' )		=> 'right',
					),
				),
			),
		)
	);

	#-----------------------------------------------------------------
	# Electro Products Cards Carousel
	#-----------------------------------------------------------------
	vc_map(
		array(
			'name' => esc_html__( 'Electro Products Cards Carousel', 'electro-extensions' ),
			'base' => 'electro_vc_products_cards_carousel',
			'description' => esc_html__( 'Add products cards carousel to your page.', 'electro-extensions' ),
			'class'		=> '',
			'controls' => 'full',
			'icon' => '',
			'category' => esc_html__( 'Electro Elements', 'electro-extensions' ),
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Enter title', 'electro-extensions' ),
					'param_name' => 'title',
					'holder' => 'div'
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Rows', 'electro-extensions' ),
					'param_name' => 'rows',
					'holder' => 'div'
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Columns', 'electro-extensions' ),
					'param_name' => 'columns',
					'holder' => 'div'
				),

				array(
					'type' => 'checkbox',
					'param_name' => 'show_carousel_nav',
					'heading' => esc_html__( 'Show Carousel Navigation', 'electro-extensions' ),
					'value' => array(
						esc_html__( 'Allow', 'electro-extensions' ) => 'true'
					)
				),

				array(
					'type' => 'checkbox',
					'param_name' => 'show_top_text',
					'heading' => esc_html__( 'Show Top Text', 'electro-extensions' ),
					'value' => array(
						esc_html__( 'Allow', 'electro-extensions' ) => 'true'
					)
				),

				array(
					'type' => 'checkbox',
					'param_name' => 'show_categories',
					'heading' => esc_html__( 'Show Categories', 'electro-extensions' ),
					'value' => array(
						esc_html__( 'Allow', 'electro-extensions' ) => 'true'
					)
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Limit', 'electro-extensions' ),
					'param_name' => 'limit',
					'holder' => 'div'
				),

				array(
					'type'			=> 'dropdown',
					'heading'		=> esc_html__( 'Product Shortcode', 'electro-extensions' ),
					'param_name'	=> 'shortcode_tag',
					'value'			=> array(
						esc_html__( 'Select', 'electro-extensions' ) 				=> '',
						esc_html__( 'Featured Products', 'electro-extensions' )		=> 'featured_products' ,
						esc_html__( 'On Sale Products', 'electro-extensions' )		=> 'sale_products' 	,
						esc_html__( 'Top Rated Products', 'electro-extensions' )	=> 'top_rated_products' ,
						esc_html__( 'Recent Products', 'electro-extensions' )		=> 'recent_products' 	,
						esc_html__( 'Best Selling Products', 'electro-extensions' )	=> 'best_selling_products',
						esc_html__( 'Products', 'electro-extensions' )				=> 'products' ,
						esc_html__( 'Product Category', 'electro-extensions' )		=> 'product_category' ,
					),
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Order by', 'electro-extensions' ),
					'param_name' => 'orderby',
					'description' => esc_html__( ' Sort retrieved posts by parameter. Defaults to \'date\'. One or more options can be passed', 'electro-extensions' ),
					'value' => 'date',
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Order', 'electro-extensions' ),
					'param_name' => 'order',
					'description' => esc_html__( 'Designates the ascending or descending order of the \'orderby\' parameter. Defaults to \'DESC\'.', 'electro-extensions' ),
					'value' => 'DESC',
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Enter Product IDs', 'electro-extensions' ),
					'description' => esc_html__( 'This will only for Products Shortcode', 'electro-extensions' ),
					'param_name' => 'product_id',
					'holder' => 'div'
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Enter Category Slug', 'electro-extensions' ),
					'description' => esc_html__( 'This will only for Product Category Shortcode', 'electro-extensions' ),
					'param_name' => 'category',
					'holder' => 'div'
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Number of Categories to display', 'electro-extensions' ),
					'param_name' => 'cat_limit',
					'holder' => 'div'
				),

				array(
					'type' => 'checkbox',
					'param_name' => 'cat_has_no_products',
					'heading' => esc_html__( 'Have no products', 'electro-extensions' ),
					'description' => esc_html__( 'Show Categories does not have products', 'electro-extensions' ),
					'value' => array(
						esc_html__( 'Allow', 'electro-extensions' ) => 'true'
					)
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Order by', 'electro-extensions' ),
					'param_name' => 'cat_orderby',
					'description' => esc_html__( ' Sort retrieved posts by parameter. Defaults to \'date\'. One or more options can be passed', 'electro-extensions' ),
					'value' => 'date',
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Order', 'electro-extensions' ),
					'param_name' => 'cat_order',
					'description' => esc_html__( 'Designates the ascending or descending order of the \'orderby\' parameter. Defaults to \'DESC\'.', 'electro-extensions' ),
					'value' => 'DESC',
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Include ID\'s', 'electro-extensions' ),
					'param_name' => 'cat_include',
					'holder' => 'div'
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Include slug\'s', 'electro-extensions' ),
					'param_name' => 'cat_slugs',
					'holder' => 'div'
				),

			)
		)
	);

	#-----------------------------------------------------------------
	# Electro Products Carousel
	#-----------------------------------------------------------------
	vc_map(
		array(
			'name' => esc_html__( 'Electro Products Carousel', 'electro-extensions' ),
			'base' => 'electro_vc_products_carousel',
			'description' => esc_html__( 'Add products carousel to your page.', 'electro-extensions' ),
			'class'		=> '',
			'controls' => 'full',
			'icon' => '',
			'category' => esc_html__( 'Electro Elements', 'electro-extensions' ),
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Enter title', 'electro-extensions' ),
					'param_name' => 'title',
					'holder' => 'div'
				),

				array(
					'type'			=> 'dropdown',
					'heading'		=> esc_html__( 'Product Shortcode', 'electro-extensions' ),
					'param_name'	=> 'shortcode_tag',
					'value'			=> array(
						esc_html__( 'Select', 'electro-extensions' ) 				=> '',
						esc_html__( 'Featured Products', 'electro-extensions' )		=> 'featured_products' ,
						esc_html__( 'On Sale Products', 'electro-extensions' )		=> 'sale_products' 	,
						esc_html__( 'Top Rated Products', 'electro-extensions' )	=> 'top_rated_products' ,
						esc_html__( 'Recent Products', 'electro-extensions' )		=> 'recent_products' 	,
						esc_html__( 'Best Selling Products', 'electro-extensions' )	=> 'best_selling_products',
						esc_html__( 'Products', 'electro-extensions' )				=> 'products' ,
						esc_html__( 'Product Category', 'electro-extensions' )		=> 'product_category' ,
					),
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Number of products to display', 'electro-extensions' ),
					'param_name' => 'limit',
					'holder' => 'div'
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Order by', 'electro-extensions' ),
					'param_name' => 'orderby',
					'description' => esc_html__( ' Sort retrieved posts by parameter. Defaults to \'date\'. One or more options can be passed', 'electro-extensions' ),
					'value' => 'date',
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Order', 'electro-extensions' ),
					'param_name' => 'order',
					'description' => esc_html__( 'Designates the ascending or descending order of the \'orderby\' parameter. Defaults to \'DESC\'.', 'electro-extensions' ),
					'value' => 'DESC',
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Enter Product IDs', 'electro-extensions' ),
					'description' => esc_html__( 'This will only for Products Shortcode', 'electro-extensions' ),
					'param_name' => 'product_id',
					'holder' => 'div'
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Enter Category Slug', 'electro-extensions' ),
					'description' => esc_html__( 'This will only for Product Category Shortcode', 'electro-extensions' ),
					'param_name' => 'category',
					'holder' => 'div'
				),

				array(
					'type' => 'checkbox',
					'param_name' => 'show_custom_nav',
					'heading' => esc_html__( 'Show Custom Navigation', 'electro-extensions' ),
					'value' => array(
						esc_html__( 'Allow', 'electro-extensions' ) => 'true'
					)
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Carousel: Items', 'electro-extensions' ),
					'param_name' => 'items',
					'holder' => 'div'
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Carousel: Items(0 - 479)', 'electro-extensions' ),
					'param_name' => 'items_0',
					'holder' => 'div'
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Carousel: Items(480 - 767)', 'electro-extensions' ),
					'param_name' => 'items_480',
					'holder' => 'div'
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Carousel: Items(768 - 991)', 'electro-extensions' ),
					'param_name' => 'items_768',
					'holder' => 'div'
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Carousel: Items(992 - 1199)', 'electro-extensions' ),
					'param_name' => 'items_992',
					'holder' => 'div'
				),

				array(
					'type' => 'checkbox',
					'param_name' => 'is_nav',
					'heading' => esc_html__( 'Carousel: Show Navigation', 'electro-extensions' ),
					'value' => array(
						esc_html__( 'Allow', 'electro-extensions' ) => 'true'
					)
				),

				array(
					'type' => 'checkbox',
					'param_name' => 'is_dots',
					'heading' => esc_html__( 'Carousel: Show Dots', 'electro-extensions' ),
					'value' => array(
						esc_html__( 'Allow', 'electro-extensions' ) => 'true'
					)
				),

				array(
					'type' => 'checkbox',
					'param_name' => 'is_touchdrag',
					'heading' => esc_html__( 'Carousel: Enable Touch Drag', 'electro-extensions' ),
					'value' => array(
						esc_html__( 'Allow', 'electro-extensions' ) => 'true'
					)
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Carousel: Nav Next Text', 'electro-extensions' ),
					'param_name' => 'nav_next',
					'holder' => 'div'
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Carousel: Nav Prev Text', 'electro-extensions' ),
					'param_name' => 'nav_prev',
					'holder' => 'div'
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Carousel: Margin', 'electro-extensions' ),
					'param_name' => 'margin',
					'holder' => 'div'
				),
			)
		)
	);

	#-----------------------------------------------------------------
	# Electro Brands Carousel
	#-----------------------------------------------------------------
	vc_map(
		array(
			'name' => esc_html__( 'Electro Brands Carousel', 'electro-extensions' ),
			'base' => 'electro_brands_carousel',
			'description' => esc_html__( 'Add brands carousel to your page.', 'electro-extensions' ),
			'class'		=> '',
			'controls' => 'full',
			'icon' => '',
			'category' => esc_html__( 'Electro Elements', 'electro-extensions' ),
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Enter title', 'electro-extensions' ),
					'param_name' => 'title',
					'holder' => 'div'
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Number of Brands to display', 'electro-extensions' ),
					'param_name' => 'limit',
					'holder' => 'div'
				),

				array(
					'type' => 'checkbox',
					'param_name' => 'has_no_products',
					'heading' => esc_html__( 'Have no products', 'electro-extensions' ),
					'description' => esc_html__( 'Show Brands does not have products', 'electro-extensions' ),
					'value' => array(
						esc_html__( 'Allow', 'electro-extensions' ) => 'true'
					)
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Order by', 'electro-extensions' ),
					'param_name' => 'orderby',
					'description' => esc_html__( ' Sort retrieved posts by parameter. Defaults to \'date\'. One or more options can be passed', 'electro-extensions' ),
					'value' => 'date',
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Order', 'electro-extensions' ),
					'param_name' => 'order',
					'description' => esc_html__( 'Designates the ascending or descending order of the \'orderby\' parameter. Defaults to \'DESC\'.', 'electro-extensions' ),
					'value' => 'DESC',
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Include ID\'s', 'electro-extensions' ),
					'param_name' => 'include',
					'holder' => 'div'
				),

				array(
					'type' => 'checkbox',
					'param_name' => 'is_touchdrag',
					'heading' => esc_html__( 'Carousel: Enable Touch Drag', 'electro-extensions' ),
					'value' => array(
						esc_html__( 'Allow', 'electro-extensions' ) => 'true'
					)
				),
			)
		)
	);

	#-----------------------------------------------------------------
	# Electro Product List Categories
	#-----------------------------------------------------------------
	vc_map(
		array(
			'name' => esc_html__( 'Product List Categories', 'electro-extensions' ),
			'base' => 'electro_product_list_categories',
			'description' => esc_html__( 'Add product categories to your page.', 'electro-extensions' ),
			'class'		=> '',
			'controls' => 'full',
			'icon' => '',
			'category' => esc_html__( 'Electro Elements', 'electro-extensions' ),
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Enter title', 'electro-extensions' ),
					'param_name' => 'title',
					'holder' => 'div'
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Number of Categories to display', 'electro-extensions' ),
					'param_name' => 'limit',
					'holder' => 'div'
				),

				array(
					'type' => 'checkbox',
					'param_name' => 'has_no_products',
					'heading' => esc_html__( 'Have no products', 'electro-extensions' ),
					'description' => esc_html__( 'Show Categories does not have products', 'electro-extensions' ),
					'value' => array(
						esc_html__( 'Allow', 'electro-extensions' ) => 'true'
					)
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Order by', 'electro-extensions' ),
					'param_name' => 'orderby',
					'description' => esc_html__( ' Sort retrieved posts by parameter. Defaults to \'name\'. One or more options can be passed', 'electro-extensions' ),
					'value' => 'date',
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Order', 'electro-extensions' ),
					'param_name' => 'order',
					'description' => esc_html__( 'Designates the ascending or descending order of the \'orderby\' parameter. Defaults to \'ASC\'.', 'electro-extensions' ),
					'value' => 'DESC',
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Include ID\'s', 'electro-extensions' ),
					'param_name' => 'include',
					'holder' => 'div'
				),
			)
		)
	);

	#-----------------------------------------------------------------
	# Electro Product 2-1-2 Grid
	#-----------------------------------------------------------------
	vc_map(
		array(
			'name' => esc_html__( 'Product 2-1-2 Grid', 'electro-extensions' ),
			'base' => 'electro_products_2_1_2',
			'description' => esc_html__( 'Add products to your page.', 'electro-extensions' ),
			'class'		=> '',
			'controls' => 'full',
			'icon' => '',
			'category' => esc_html__( 'Electro Elements', 'electro-extensions' ),
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Enter title', 'electro-extensions' ),
					'param_name' => 'title',
					'holder' => 'div'
				),

				array(
					'type'			=> 'dropdown',
					'heading'		=> esc_html__( 'Product Shortcode', 'electro-extensions' ),
					'param_name'	=> 'shortcode_tag',
					'value'			=> array(
						esc_html__( 'Select', 'electro-extensions' ) 				=> '',
						esc_html__( 'Featured Products', 'electro-extensions' )		=> 'featured_products' ,
						esc_html__( 'On Sale Products', 'electro-extensions' )		=> 'sale_products' 	,
						esc_html__( 'Top Rated Products', 'electro-extensions' )	=> 'top_rated_products' ,
						esc_html__( 'Recent Products', 'electro-extensions' )		=> 'recent_products' 	,
						esc_html__( 'Best Selling Products', 'electro-extensions' )	=> 'best_selling_products',
						esc_html__( 'Products', 'electro-extensions' )				=> 'products' ,
						esc_html__( 'Product Category', 'electro-extensions' )		=> 'product_category' ,
					),
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Order by', 'electro-extensions' ),
					'param_name' => 'orderby',
					'description' => esc_html__( ' Sort retrieved posts by parameter. Defaults to \'date\'. One or more options can be passed', 'electro-extensions' ),
					'value' => 'date',
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Order', 'electro-extensions' ),
					'param_name' => 'order',
					'description' => esc_html__( 'Designates the ascending or descending order of the \'orderby\' parameter. Defaults to \'DESC\'.', 'electro-extensions' ),
					'value' => 'DESC',
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Enter Product IDs', 'electro-extensions' ),
					'description' => esc_html__( 'This will only for Products Shortcode', 'electro-extensions' ),
					'param_name' => 'product_id',
					'holder' => 'div'
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Enter Category Slug', 'electro-extensions' ),
					'description' => esc_html__( 'This will only for Product Category Shortcode', 'electro-extensions' ),
					'param_name' => 'category',
					'holder' => 'div'
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Number of Categories to display', 'electro-extensions' ),
					'param_name' => 'cat_limit',
					'holder' => 'div'
				),

				array(
					'type' => 'checkbox',
					'param_name' => 'cat_has_no_products',
					'heading' => esc_html__( 'Have no products', 'electro-extensions' ),
					'description' => esc_html__( 'Show Categories does not have products', 'electro-extensions' ),
					'value' => array(
						esc_html__( 'Allow', 'electro-extensions' ) => 'true'
					)
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Order by', 'electro-extensions' ),
					'param_name' => 'cat_orderby',
					'description' => esc_html__( ' Sort retrieved posts by parameter. Defaults to \'date\'. One or more options can be passed', 'electro-extensions' ),
					'value' => 'date',
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Order', 'electro-extensions' ),
					'param_name' => 'cat_order',
					'description' => esc_html__( 'Designates the ascending or descending order of the \'orderby\' parameter. Defaults to \'DESC\'.', 'electro-extensions' ),
					'value' => 'DESC',
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Include ID\'s', 'electro-extensions' ),
					'param_name' => 'cat_include',
					'holder' => 'div'
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Include slug\'s', 'electro-extensions' ),
					'param_name' => 'cat_slugs',
					'holder' => 'div'
				),
			)
		)
	);

	#-----------------------------------------------------------------
	# Electro Product 6-1 Grid
	#-----------------------------------------------------------------
	vc_map(
		array(
			'name' => esc_html__( 'Product 6-1 Grid', 'electro-extensions' ),
			'base' => 'electro_vc_products_6_1',
			'description' => esc_html__( 'Add products to your page.', 'electro-extensions' ),
			'class'		=> '',
			'controls' => 'full',
			'icon' => '',
			'category' => esc_html__( 'Electro Elements', 'electro-extensions' ),
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Enter title', 'electro-extensions' ),
					'param_name' => 'title',
					'holder' => 'div'
				),

				array(
					'type'			=> 'dropdown',
					'heading'		=> esc_html__( 'Product Shortcode', 'electro-extensions' ),
					'param_name'	=> 'shortcode_tag',
					'value'			=> array(
						esc_html__( 'Select', 'electro-extensions' ) 				=> '',
						esc_html__( 'Featured Products', 'electro-extensions' )		=> 'featured_products' ,
						esc_html__( 'On Sale Products', 'electro-extensions' )		=> 'sale_products' 	,
						esc_html__( 'Top Rated Products', 'electro-extensions' )	=> 'top_rated_products' ,
						esc_html__( 'Recent Products', 'electro-extensions' )		=> 'recent_products' 	,
						esc_html__( 'Best Selling Products', 'electro-extensions' )	=> 'best_selling_products',
						esc_html__( 'Products', 'electro-extensions' )				=> 'products' ,
						esc_html__( 'Product Category', 'electro-extensions' )		=> 'product_category' ,
					),
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Order by', 'electro-extensions' ),
					'param_name' => 'orderby',
					'description' => esc_html__( ' Sort retrieved posts by parameter. Defaults to \'date\'. One or more options can be passed', 'electro-extensions' ),
					'value' => 'date',
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Order', 'electro-extensions' ),
					'param_name' => 'order',
					'description' => esc_html__( 'Designates the ascending or descending order of the \'orderby\' parameter. Defaults to \'DESC\'.', 'electro-extensions' ),
					'value' => 'DESC',
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Enter Product IDs', 'electro-extensions' ),
					'description' => esc_html__( 'This will only for Products Shortcode', 'electro-extensions' ),
					'param_name' => 'product_id',
					'holder' => 'div'
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Enter Category Slug', 'electro-extensions' ),
					'description' => esc_html__( 'This will only for Product Category Shortcode', 'electro-extensions' ),
					'param_name' => 'category',
					'holder' => 'div'
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Number of Categories to display', 'electro-extensions' ),
					'param_name' => 'cat_limit',
					'holder' => 'div'
				),

				array(
					'type' => 'checkbox',
					'param_name' => 'cat_has_no_products',
					'heading' => esc_html__( 'Have no products', 'electro-extensions' ),
					'description' => esc_html__( 'Show Categories does not have products', 'electro-extensions' ),
					'value' => array(
						esc_html__( 'Allow', 'electro-extensions' ) => 'true'
					)
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Order by', 'electro-extensions' ),
					'param_name' => 'cat_orderby',
					'description' => esc_html__( ' Sort retrieved posts by parameter. Defaults to \'date\'. One or more options can be passed', 'electro-extensions' ),
					'value' => 'date',
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Order', 'electro-extensions' ),
					'param_name' => 'cat_order',
					'description' => esc_html__( 'Designates the ascending or descending order of the \'orderby\' parameter. Defaults to \'DESC\'.', 'electro-extensions' ),
					'value' => 'DESC',
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Include ID\'s', 'electro-extensions' ),
					'param_name' => 'cat_include',
					'holder' => 'div'
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Include slug\'s', 'electro-extensions' ),
					'param_name' => 'cat_slugs',
					'holder' => 'div'
				),
			)
		)
	);

	#-----------------------------------------------------------------
	# Electro Onsale Product
	#-----------------------------------------------------------------
	vc_map(
		array(
			'name' => esc_html__( 'Electro Onsale Product', 'electro-extensions' ),
			'base' => 'electro_vc_product_onsale',
			'description' => esc_html__( 'Add onsale product to your page.', 'electro-extensions' ),
			'class'		=> '',
			'controls' => 'full',
			'icon' => '',
			'category' => esc_html__( 'Electro Elements', 'electro-extensions' ),
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Enter title', 'electro-extensions' ),
					'param_name' => 'title',
					'holder' => 'div'
				),
				array(
					'type' => 'checkbox',
					'param_name' => 'show_savings',
					'heading' => esc_html__( 'Show Savings Details', 'electro-extensions' ),
					'value' => array(
						esc_html__( 'Show Savings', 'electro-extensions' ) => 'true'
					)
				),
				array(
					'type' 			=> 'dropdown',
					'heading'		=> esc_html__( 'Savings in', 'electro-extensions' ),
					'value' 		=> array(
						esc_html__( 'Amount', 'electro-extensions' ) => 'amount',
						esc_html__( 'Percentage', 'electro-extensions' ) => 'percentage'
					),
					'param_name'	=> 'savings_in',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Savings Text', 'electro-extensions' ),
					'param_name' => 'savings_text',
					'holder' => 'div'
				),
				array(
					'type' 			=> 'dropdown',
					'heading'		=> esc_html__( 'Product Choice', 'electro-extensions' ),
					'value' 		=> array(
						esc_html__( 'Recent', 'electro-extensions' ) => 'recent',
						esc_html__( 'Random', 'electro-extensions' ) => 'random',
						esc_html__( 'Specific', 'electro-extensions' ) => 'specific'
					),
					'param_name'	=> 'product_choice',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Product ID', 'electro-extensions' ),
					'param_name' => 'product_id',
					'holder' => 'div'
				),
			)
		)
	);

	#-----------------------------------------------------------------
	# Electro Onsale Product Carousel
	#-----------------------------------------------------------------
	vc_map(
		array(
			'name' => esc_html__( 'Electro Onsale Products Carousel', 'electro-extensions' ),
			'base' => 'electro_vc_products_onsale_carousel',
			'description' => esc_html__( 'Add onsale products carousel to your page.', 'electro-extensions' ),
			'class'		=> '',
			'controls' => 'full',
			'icon' => '',
			'category' => esc_html__( 'Electro Elements', 'electro-extensions' ),
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Enter title', 'electro-extensions' ),
					'param_name' => 'title',
					'holder' => 'div'
				),

				array(
					'type' => 'checkbox',
					'param_name' => 'show_savings',
					'heading' => esc_html__( 'Show Savings Details', 'electro-extensions' ),
					'value' => array(
						esc_html__( 'Show Savings', 'electro-extensions' ) => 'true'
					)
				),

				array(
					'type' 			=> 'dropdown',
					'heading'		=> esc_html__( 'Savings in', 'electro-extensions' ),
					'value' 		=> array(
						esc_html__( 'Amount', 'electro-extensions' ) => 'amount',
						esc_html__( 'Percentage', 'electro-extensions' ) => 'percentage'
					),
					'param_name'	=> 'savings_in',
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Savings Text', 'electro-extensions' ),
					'param_name' => 'savings_text',
					'holder' => 'div'
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Number of Products to display', 'electro-extensions' ),
					'param_name' => 'limit',
					'holder' => 'div'
				),

				array(
					'type' 			=> 'dropdown',
					'heading'		=> esc_html__( 'Product Choice', 'electro-extensions' ),
					'value' 		=> array(
						esc_html__( 'Recent', 'electro-extensions' ) => 'recent',
						esc_html__( 'Random', 'electro-extensions' ) => 'random',
						esc_html__( 'Specific', 'electro-extensions' ) => 'specific'
					),
					'param_name'	=> 'product_choice',
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Product ID', 'electro-extensions' ),
					'param_name' => 'product_id',
					'holder' => 'div'
				),

				array(
					'type' => 'checkbox',
					'param_name' => 'show_custom_nav',
					'heading' => esc_html__( 'Show Custom Navigation', 'electro-extensions' ),
					'value' => array(
						esc_html__( 'Allow', 'electro-extensions' ) => 'true'
					)
				),

				array(
					'type' => 'checkbox',
					'param_name' => 'show_progress',
					'heading' => esc_html__( 'Show Progress', 'electro-extensions' ),
					'value' => array(
						esc_html__( 'Allow', 'electro-extensions' ) => 'true'
					)
				),

				array(
					'type' => 'checkbox',
					'param_name' => 'show_timer',
					'heading' => esc_html__( 'Show Timer', 'electro-extensions' ),
					'value' => array(
						esc_html__( 'Allow', 'electro-extensions' ) => 'true'
					)
				),

				array(
					'type' => 'checkbox',
					'param_name' => 'show_cart_btn',
					'heading' => esc_html__( 'Show Cart Button', 'electro-extensions' ),
					'value' => array(
						esc_html__( 'Allow', 'electro-extensions' ) => 'true'
					)
				),

				array(
					'type' => 'checkbox',
					'param_name' => 'is_dots',
					'heading' => esc_html__( 'Carousel: Show Dots', 'electro-extensions' ),
					'value' => array(
						esc_html__( 'Allow', 'electro-extensions' ) => 'true'
					)
				),

				array(
					'type' => 'checkbox',
					'param_name' => 'is_touchdrag',
					'heading' => esc_html__( 'Carousel: Enable Touch Drag', 'electro-extensions' ),
					'value' => array(
						esc_html__( 'Allow', 'electro-extensions' ) => 'true'
					)
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Carousel: Nav Next Text', 'electro-extensions' ),
					'param_name' => 'nav_next',
					'holder' => 'div'
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Carousel: Nav Prev Text', 'electro-extensions' ),
					'param_name' => 'nav_prev',
					'holder' => 'div'
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Carousel: Margin', 'electro-extensions' ),
					'param_name' => 'margin',
					'holder' => 'div'
				),
			)
		)
	);

	#-----------------------------------------------------------------
	# Electro Team Member
	#-----------------------------------------------------------------
	vc_map(
		array(
			'name' => esc_html__( 'Electro Team Member', 'electro-extensions' ),
			'base' => 'electro_team_member',
			'description' => esc_html__( 'Add a team member profile to your page.', 'electro-extensions' ),
			'class'		=> '',
			'controls' => 'full',
			'icon' => '',
			'category' => esc_html__( 'Electro Elements', 'electro-extensions' ),
			'params' => array(
				array(
					 'type' 		=> 'textfield',
			         'heading' 		=> esc_html__( 'Full Name', 'electro-extensions' ),
			         'param_name' 	=> 'name',
			         'description' 	=> esc_html__( 'Enter team member full name', 'electro-extensions' ),
			         'holder' 		=> 'div'
		      	),
		      	array(
					 'type' 		=> 'textfield',
			         'heading' 		=> esc_html__( 'Designation', 'electro-extensions' ),
			         'param_name' 	=> 'designation',
			         'description' 	=> esc_html__( 'Enter designation of team member', 'electro-extensions'),
		      	),
		      	array(
					'type' 			=> 'attach_image',
		         	'heading' 		=> esc_html__( 'Profile Pic', 'electro-extensions' ),
		         	'param_name' 	=> 'profile_pic',
		      	),
		      	array(
		      		'type' 			=> 'dropdown',
		      		'heading'		=> esc_html__( 'Display Style', 'electro-extensions' ),
		      		'value' 		=> array(
		      			esc_html__( 'Square', 'electro-extensions' ) => 'square',
		      			esc_html__( 'Circle', 'electro-extensions' ) => 'circle'
	      			),
	      			'param_name'	=> 'display_style',
	      		),
	      		array(
	      			'type' 			=> 'textfield',
		         	'class' 		=> '',
		         	'heading' 		=> esc_html__( 'Link', 'electro-extensions' ),
		         	'param_name' 	=> 'link',
		         	'description' 	=> esc_html__( 'Add link to the team member. Leave blank if there aren\'t any', 'electro-extensions' )
	  			),
		      	array(
					'type' 			=> 'textfield',
		         	'class' 		=> '',
		         	'heading' 		=> esc_html__( 'Extra Class', 'electro-extensions' ),
		         	'param_name' 	=> 'el_class',
		         	'description' 	=> esc_html__( 'Add your extra classes here.', 'electro-extensions' )
		      	),
		    )
		)
	);

	#-----------------------------------------------------------------
	# Electro Terms
	#-----------------------------------------------------------------
	vc_map(
		array(
			'name'        => esc_html__( 'Electro Terms', 'electro-extensions' ),
			'base'        => 'electro_terms',
			'description' => esc_html__( 'Adds a shortcode for get_terms. Used to get terms including categories, product categories, etc.', 'electro-extensions' ),
			'class'		  => '',
			'controls'    => 'full',
			'icon'        => '',
			'category'    => esc_html__( 'Electro Elements', 'electro-extensions' ),
			'params'      => array(
				array(
					'type'         => 'textfield',
					'heading'      => esc_html__( 'Taxonomy', 'electro-extensions' ),
					'param_name'   => 'taxonomy',
					'description'  => esc_html__( 'Taxonomy name, or comma-separated taxonomies, to which results should be limited.', 'electro-extensions' ),
					'value'        => 'category',
					'holder'       => 'div'
				),
				array(
					'type'         => 'textfield',
					'heading'      => esc_html__( 'Order By', 'electro-extensions' ),
					'param_name'   => 'orderby',
					'description'  => esc_html__( 'Field(s) to order terms by. Accepts term fields (\'name\', \'slug\', \'term_group\', \'term_id\', \'id\', \'description\'). Defaults to \'name\'.', 'electro-extensions' ),
					'value'        => 'name'
				),
				array(
					'type'         => 'textfield',
					'heading'      => esc_html__( 'Order', 'electro-extensions' ),
					'param_name'   => 'order',
					'description'  => esc_html__( 'Whether to order terms in ascending or descending order. Accepts \'ASC\' (ascending) or \'DESC\' (descending). Default \'ASC\'.', 'electro-extensions' ),
					'value'        => 'ASC'
				),
				array(
					'type'         => 'textfield',
					'heading'      => esc_html__( 'Hide Empty ?', 'electro-extensions' ),
					'param_name'   => 'hide_empty',
					'description'  => esc_html__( 'Whether to hide terms not assigned to any posts. Accepts 1 or 0. Default 0.', 'electro-extensions' ),
					'value'        => '0'
				),
				array(
					'type'         => 'textfield',
					'heading'      => esc_html__( 'Include IDs', 'electro-extensions' ),
					'param_name'   => 'include',
					'description'  => esc_html__( 'Comma-separated string of term ids to include.', 'electro-extensions' ),
				),
				array(
					'type'         => 'textfield',
					'heading'      => esc_html__( 'Exclude IDs', 'electro-extensions' ),
					'param_name'   => 'exclude',
					'description'  => esc_html__( 'Comma-separated string of term ids to exclude. If Include is non-empty, Exclude is ignored.', 'electro-extensions' ),
				),
				array(
					'type'         => 'textfield',
					'heading'      => esc_html__( 'Number', 'electro-extensions' ),
					'param_name'   => 'number',
					'description'  => esc_html__( 'Maximum number of terms to return. Accepts 0 (all) or any positive number. Default 0 (all).', 'electro-extensions' ),
					'value'        => '0',
				),
				array(
					'type'         => 'textfield',
					'heading'      => esc_html__( 'Offset', 'electro-extensions' ),
					'param_name'   => 'offset',
					'description'  => esc_html__( 'The number by which to offset the terms query.', 'electro-extensions' ),
					'value'        => '0',
				),
				array(
					'type'         => 'textfield',
					'heading'      => esc_html__( 'Name', 'electro-extensions' ),
					'param_name'   => 'name',
					'description'  => esc_html__( 'Name or comma-separated string of names to return term(s) for.', 'electro-extensions' ),
				),
				array(
					'type'         => 'textfield',
					'heading'      => esc_html__( 'Slug', 'electro-extensions' ),
					'param_name'   => 'slug',
					'description'  => esc_html__( 'Slug or comma-separated string of slugs to return term(s) for.', 'electro-extensions' ),
				),
				array(
					'type'         => 'textfield',
					'heading'      => esc_html__( 'Hierarchical', 'electro-extensions' ),
					'param_name'   => 'hierarchical',
					'description'  => esc_html__( 'Whether to include terms that have non-empty descendants. Accepts 1 (true) or 0 (false). Default 1 (true)', 'electro-extensions' ),
					'value'        => '1',
				),
				array(
					'type'         => 'textfield',
					'heading'      => esc_html__( 'Child Of', 'electro-extensions' ),
					'param_name'   => 'child_of',
					'description'  => esc_html__( 'Term ID to retrieve child terms of. If multiple taxonomies are passed, child_of is ignored. Default 0.', 'electro-extensions' ),
					'value'        => '0',
				),
				array(
					'type'         => 'textfield',
					'heading'      => esc_html__( 'Include "Child Of" term ?', 'electro-extensions' ),
					'param_name'   => 'include_parent',
					'description'  => esc_html__( 'Include "Child Of" term in the terms list. Accepts 1 (yes) or 0 (no). Default 1.', 'electro-extensions' ),
					'value'        => '1',
				),
				array(
					'type'         => 'textfield',
					'heading'      => esc_html__( 'Parent', 'electro-extensions' ),
					'param_name'   => 'parent',
					'description'  => esc_html__( 'Parent term ID to retrieve direct-child terms of.', 'electro-extensions' ),
					'value'        => '',
				)
			)
		)
	);


endif;
