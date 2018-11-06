<?php

/*
*
*	Old function
*	===============================================================
*
*/


function get_event_list( $eventcategory = ''){
//wp_reset_postdata();
	$result   = '';
	global $paged;
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	
	$args  = array(
		'post_type' => 'event',
		'meta_key' => EVENT_PREFIX.'date',
		'orderby' => 'meta_value',
        'order' => 'ASC',
		'paged' => $paged
		/*'meta_compare' => 'LIKE',
		'meta_value' => $name */
	);
	
/*

	$args['post_type'] = 'event';
	$args['meta_key'] = EVENT_PREFIX.'date';
	$args['orderby'] = 'meta_value';
    $args['order'] = 'ASC';
	$args['paged'] = $paged;*/




	if($eventcategory != ''){
		$args ['eventtype'] = $eventcategory;
	}
	
	$the_query_meta = new WP_Query( $args );
	global $post;
	$resarray = array();
	while ( $the_query_meta->have_posts() ):
		$the_query_meta->the_post();
		setup_postdata( $post );
		$url = '';
		if ( $thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), array(60,60)  ) ) 	
		{
			$url       = $thumb['0'];
		} 
		$resarray[] = array(
			'id' =>  $post->ID,
			'date' =>  get_post_meta($post->ID,EVENT_PREFIX.'date',true),
			'location' =>  get_post_meta($post->ID,EVENT_PREFIX.'location',true),
			'street' =>  get_post_meta($post->ID,EVENT_PREFIX.'street',true),
			'city' =>  get_post_meta($post->ID,EVENT_PREFIX.'city',true),
			'permalink' =>  esc_url(get_permalink($post->ID)),
			'title' =>  $post->post_title,
			'thumb' => $url
		);
	endwhile;
	wp_reset_postdata();
	wp_reset_query();
	return $resarray;
}







/*
*
*	New function with parameters in args
*	===============================================================
*	NOT USED ACTUALLY
*/

function get_event_list_new( $newargs = array()){

	wp_reset_postdata();
	wp_reset_query();


	$result   = '';	
	
	$args  = array(
		'post_type' => 'event',
		'meta_key' => EVENT_PREFIX.'date',
		'orderby' => 'meta_value',
        'order' => 'ASC',
        'posts_per_page'=>'1'

	);

	//array_merge($args,$newargs);


	$query = new WP_Query( $args );
/*
	function pgp(){
		$query->set( 'posts_per_page','2' );
	}
	add_action( 'pre_get_posts', 'pgp' , 0 );

*/
	//global $post;
	$resarray = array();
	$query->set( 'posts_per_page','2' );
	while ( $query->have_posts() ):
		$query->the_post();
		setup_postdata( $post );
		$url = '';
		if ( $thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), array(60,60)  ) ) 	
		{
			$url       = $thumb['0'];
		} 
		$resarray[] = array(
			'id' =>  $post->ID,
			'date' =>  get_post_meta($post->ID,EVENT_PREFIX.'date',true),
			'location' =>  get_post_meta($post->ID,EVENT_PREFIX.'location',true),
			'street' =>  get_post_meta($post->ID,EVENT_PREFIX.'street',true),
			'city' =>  get_post_meta($post->ID,EVENT_PREFIX.'city',true),
			'permalink' =>  get_permalink($post->ID),
			'title' =>  $post->post_title,
			'thumb' => $url
		);
	endwhile;
	wp_reset_postdata();
	wp_reset_query();
	return $resarray;
}


?>