<?php
if ( ! function_exists( 'electro_vc_product_tabs' ) ) :

function electro_vc_product_tabs( $atts, $content = null ) {

	extract( shortcode_atts( array(
		'tab_title_1'		=> '',
		'tab_content_1'		=> '',
		'product_id_1'		=> '',
		'category_1'		=> '',
		'tab_title_2'		=> '',
		'tab_content_2'		=> '',
		'product_id_2'		=> '',
		'category_2'		=> '',
		'tab_title_3'		=> '',
		'tab_content_3'		=> '',
		'product_id_3'		=> '',
		'category_3'		=> '',
		'product_items'		=> 3,
		'product_columns'	=> 3
	), $atts ) );

	$args = array(
		'tabs' 		=> array(
			array(
				'title'			=> $tab_title_1,
				'shortcode_tag'	=> $tab_content_1,
				'atts'			=> function_exists( 'electro_get_atts_for_shortcode' ) ? electro_get_atts_for_shortcode( array( 'shortcode' => $tab_content_1, 'product_category_slug' => $category_1, 'products_choice' => 'ids', 'products_ids_skus' => $product_id_1 ) ) : array()
			),
			array(
				'title'			=> $tab_title_2,
				'shortcode_tag'	=> $tab_content_2,
				'atts'			=> function_exists( 'electro_get_atts_for_shortcode' ) ? electro_get_atts_for_shortcode( array( 'shortcode' => $tab_content_2, 'product_category_slug' => $category_2, 'products_choice' => 'ids', 'products_ids_skus' => $product_id_2 ) ) : array()
			),
			array(
				'title'			=> $tab_title_3,
				'shortcode_tag'	=> $tab_content_3,
				'atts'			=> function_exists( 'electro_get_atts_for_shortcode' ) ? electro_get_atts_for_shortcode( array( 'shortcode' => $tab_content_3, 'product_category_slug' => $category_3, 'products_choice' => 'ids', 'products_ids_skus' => $product_id_3 ) ) : array()
			)
		),
		'limit'		=> $product_items,
		'columns'	=> $product_columns,
	);

	$html = '';
	if( function_exists( 'electro_products_tabs' ) ) {
		ob_start();
		electro_products_tabs( $args );
		$html = ob_get_clean();
	}

	return $html;
}

add_shortcode( 'electro_product_tabs' , 'electro_vc_product_tabs' );

endif;