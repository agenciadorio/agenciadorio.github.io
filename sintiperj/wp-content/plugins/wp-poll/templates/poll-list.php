<?php
/*
* @Author 		ParaTheme
* @Folder	 	wp-resume-builder/templates

* Copyright: 	2015 ParaTheme
*/

if ( ! defined('ABSPATH')) exit; // if direct access 
	
	$wpp_list_per_page = get_option( 'wpp_list_per_page' );
	if( empty( $wpp_list_per_page ) ) $wpp_list_per_page = 10;
	
	if ( get_query_var('paged') ) { $paged = get_query_var('paged');} 
	elseif ( get_query_var('page') ) { $paged = get_query_var('page'); } 
	else { $paged = 1; }
	
	$WPP_Poll_query = new WP_Query( array (
		'post_type' => 'poll',
		'post_status' => array( 'publish' ),
		'order' => 'DESC',
		'orderby' => 'date',
		'posts_per_page' => $wpp_list_per_page,
		'paged' => $paged,
	) );
			

?>

<div class="wpp_list_container">
	
	<?php 
	if ( $WPP_Poll_query->have_posts() ) : 
	while ( $WPP_Poll_query->have_posts() ) : $WPP_Poll_query->the_post();
		
		$poll_title 	= get_the_title();
		$poll_permalink = get_the_permalink();
		$polled_data	= get_post_meta( get_the_ID(), 'polled_data', true );
		$poller 		= empty($polled_data) ? 0 : count( $polled_data );
		$time_ago 		= human_time_diff( get_the_time('U'), current_time('timestamp') ) .__(' ago',WPP_TEXT_DOMAIN); 
		$terms 			= get_the_terms( get_the_ID(), 'poll_cat' );
		$terms_html 	= '';
		$count 			= 0;
		
		foreach( $terms as $term ){
			$terms_html .= $term->name;
			
			if( ++$count < count($terms) ) $terms_html .= ', ';
		}

		echo "
		<div class='wpp_list_single'>
			<div class='wpp_left'>
				<div class='wpp_icon dashicons-before dashicons-chart-bar'></div>
			</div>
			<div class='wpp_right'>
				<a class='wpp_title' href='$poll_permalink'>$poll_title</a>
				<a class='inline wpp_categories'><i class='fa fa-folder-open'></i> $terms_html</a>
				<a class='inline wpp_report'><i class='fa fa-paper-plane'></i> $poller ".__('Response(s)',WPP_TEXT_DOMAIN)."</a>
				<a class='inline wpp_published'><i class='fa fa-clock-o'></i> $time_ago</a>
			</div>
		</div>";
	
	endwhile;
	
	$big = 999999999;
	$paginate = array(
		'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
		'format' => '?paged=%#%',
		'current' => max( 1, $paged ),
		'total' => $WPP_Poll_query->max_num_pages
	);
			
	?><div class="paginate"> <?php echo paginate_links($paginate); ?> </div> <?php		
	
	wp_reset_query();
	
	else: ?><span><?php echo __('No Poll found', WPP_TEXT_DOMAIN); ?></span> <?php 
	endif; ?>
	
	
</div>