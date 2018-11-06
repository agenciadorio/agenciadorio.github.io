<?php
/*
*
*	QantumThemes Radio Suite
*	Shortcodes Functions
*
*/


/*
*
*	Shows tab shortcode
*
*/

if(!function_exists('qw_radiosuite_tab')) {
	function qw_radiosuite_tab($atts){
		extract( shortcode_atts( array(
			'schedule' => "0",
			'class' => ""
		), $atts ) );
		wp_reset_postdata();
		wp_reset_query();
		ob_start();
		include 'showgrid.php';
		return ob_get_clean();
		wp_reset_postdata();
		wp_reset_query();
	}

}
add_shortcode("showgrid","qw_radiosuite_tab");
add_shortcode("qt-schedule","qw_radiosuite_tab");



/*
*
*	Slideshow shortcode
*
*/

if(!function_exists('qw_radiosuite_slide')) {
	function qw_radiosuite_slide($atts){
		extract( shortcode_atts( array(
			'quantity' => "5"
		), $atts ) );
		include 'showslide.php';
	}
}

add_shortcode("showslider","qw_radiosuite_slide");


/**
 *
 *	Radio player shortcode (creates a single radio player)
 * 
 */

if(!function_exists('qw_radioplayer_shortcode')){
function qw_radioplayer_shortcode($atts){
	extract( shortcode_atts( array(
		'id' => false
	), $atts ) );
	/**
	 * Custom query to extract the first radio if no ID is specified
	 */
	if(!$id){
		$args = array(
				'post_type' => 'radiochannel',
				'posts_per_page' => 1,
				'post_status' => 'publish',
				'orderby' => array ( 'menu_order' => 'ASC', 'date' => 'DESC'),
				'suppress_filters' => false
			    );
	} else {
		$args = array(
			'p' => $id, // id of a page, post, or custom type
			'post_type' => 'radiochannel'
		);
	}
	$wp_query = new WP_Query( $args );
	if ( $wp_query->have_posts() ) : while ( $wp_query->have_posts() ) : $wp_query->the_post();
		$featuredplayer_used = true;
		global $featuredplayer_used;
		ob_start();
		global $post;
		setup_postdata( $post );
			/**
			 * [$randomid string ID for the player to associate player and playlist]
			 */
			$randomid =  substr(str_shuffle(str_repeat("abcdefghijklmnopqrstuvwxyz", 5)), 0, 5);
			$post->playerid = "threesixtyplayer".$randomid; // used in part-tracklist.php
			$radiourl = trim(get_post_meta($post->ID, "mp3_stream_url", true));
			if($radiourl) {
			?>
				<div class="ui360 ui360-vis qt-releaseplayer" id="<?php echo esc_attr($post->playerid); ?>">
					<a href="<?php echo esc_attr(esc_url($radiourl));  ?>" class="qt-header-play">
					</a>
				</div>
			<?php }
		$featuredplayer_used = true;
		wp_reset_postdata();
		return ob_get_clean();
	endwhile; else:
	echo 'No radio stations found';
	endif;		
}
}

add_shortcode("qt-radioplayer","qw_radioplayer_shortcode");
