<?php
if ( ! function_exists( 'electro_vc_products_carousel_block' ) ) :

function electro_vc_products_carousel_block( $atts, $content = null ) {

	extract( shortcode_atts( array(
		'title'					=> '',
		'shortcode_tag'			=> 'recent_products',
		'limit' 				=> 10,
		'orderby' 				=> 'date',
		'order' 				=> 'desc',
		'category'				=> '',
		'product_id'			=> '',
		'show_custom_nav'		=> false,
		'items' 				=> 4,
		'items_0' 				=> 1,
		'items_480' 			=> 3,
		'items_768' 			=> 2,
		'items_992' 			=> 3,
		'is_nav' 				=> false,
		'is_dots' 				=> false,
		'is_touchdrag' 			=> false,
		'nav_next' 				=> '',
		'nav_prev' 				=> '',
		'margin' 				=> 0,
	), $atts ) );

	$products_html = electro_do_shortcode( $shortcode_tag, array(
		'per_page' => $limit,
		'columns' => $items,
		'orderby' => $orderby,
		'order' => $order,
		'ids' => $product_id,
		'category' => $category
	) );

	$args = apply_filters( 'electro_products_carousel_widget_args', array(
		'section_args' 	=> array(
			'products_html'		=> $products_html,
			'section_title'		=> $title,
			'show_custom_nav'	=> $show_custom_nav
		),
		'carousel_args'	=> array(
			'items'				=> $items,
			'nav'				=> $is_nav,
			'dots'				=> $is_dots,
			'touchDrag'			=> $is_touchdrag,
			'navText'			=> array( $nav_next, $nav_prev ),
			'margin'			=> intval( $margin ),
			'responsive'		=> array(
				'0'		=> array( 'items'	=> $items_0 ),
				'480'	=> array( 'items'	=> $items_480 ),
				'768'	=> array( 'items'	=> $items_768 ),
				'992'	=> array( 'items'	=> $items_992 ),
				'1200'	=> array( 'items'	=> $items ),
			)
		)
	) );

	$html = '';
	if( function_exists( 'electro_products_carousel' ) ) {
		ob_start();
		electro_products_carousel( $args['section_args'], $args['carousel_args'] );
		$html = ob_get_clean();
	}

	return $html;
}

add_shortcode( 'electro_vc_products_carousel' , 'electro_vc_products_carousel_block' );

endif;
