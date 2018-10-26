<?php

/*
 * Register Woo Ajax Search shortcode
 * 
 * @param array $atts       bool show_details_box
 */
function dgwt_wcas_ajax_search_form_shortcode($atts) {

	$search_args = shortcode_atts( array(
        'class' => '',
        'bar' => 'something else',
		'details_box' => 'show'
    ), $atts );

	$search_args[ 'class' ] .= empty( $search_args[ 'class' ] ) ? 'woocommerce' : ' woocommerce';

	$search_args = apply_filters( 'dgwt_wcas_ajax_search_shortcode_args', $search_args );

	return dgwt_wcas_get_search_form( $search_args );
}

add_shortcode( 'wcas-search-form', 'dgwt_wcas_ajax_search_form_shortcode' );
