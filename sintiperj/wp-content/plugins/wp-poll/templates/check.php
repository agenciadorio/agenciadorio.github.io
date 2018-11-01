<?php
/*
* @Author 		ParaTheme
* @Folder	 	wp-resume-builder/templates

* Copyright: 	2015 ParaTheme
*/

if ( ! defined('ABSPATH')) exit; // if direct access 

$woocommerce_table_rates = get_option( 'woocommerce_table_rates' );
$woo_rates = get_option( 'woo_rates' );


$arr_rates = explode( PHP_EOL, $woo_rates );

$new_rates = array();
foreach( $arr_rates as $rates ){
	
	$arr_single 	= explode( "\t", $rates);
	$state_title 	= trim( $arr_single[0] );
	
	$new_rates[ $state_title ][200] = $arr_single[1];
	$new_rates[ $state_title ][300] = $arr_single[2];
	$new_rates[ $state_title ][500] = $arr_single[3];
	$new_rates[ $state_title ][700] = $arr_single[4];
}

$woc_new_rates = array();

$missed = array();
foreach( $woocommerce_table_rates as $rates ){
	
	if( array_key_exists( trim( $rates['title'] ), $new_rates)) {
		
		$cost = $new_rates[ $rates['title'] ][ $rates['max'] ];
	
		$woc_new_rates[] = array(
			'title' 		=> $rates['title'],
			'identifier' 	=> $rates['identifier'],
			'zone' 			=> $rates['zone'],
			'zone_order' 	=> $rates['zone_order'],
			'class' 		=> $rates['class'],
			'class_priority'=> $rates['class_priority'],
			'cond' 			=> $rates['cond'],
			'min' 			=> $rates['min'],
			'max' 			=> $rates['max'],
			'shiptype' 		=> $rates['shiptype'],
			'cost' 			=> $cost,
			'bundle_qty' 	=> $rates['bundle_qty'],
			'bundle_cost' 	=> $rates['bundle_cost'],
			'default' 		=> $rates['default'],
		);
	}
	else {

		$missed[] = array(
			'title' 		=> $rates['title'],
			'identifier' 	=> $rates['identifier'],
			'zone' 			=> $rates['zone'],
			'zone_order' 	=> $rates['zone_order'],
			'class' 		=> $rates['class'],
			'class_priority'=> $rates['class_priority'],
			'cond' 			=> $rates['cond'],
			'min' 			=> $rates['min'],
			'max' 			=> $rates['max'],
			'shiptype' 		=> $rates['shiptype'],
			'cost' 			=> $rates['cost'],
			'bundle_qty' 	=> $rates['bundle_qty'],
			'bundle_cost' 	=> $rates['bundle_cost'],
			'default' 		=> $rates['default'],
		);
	}
    
}


echo '<pre>'; print_r( $missed ); echo '</pre>';

$serialize = serialize( $woc_new_rates );

echo '<pre>'; print_r( $serialize ); echo '</pre>';




