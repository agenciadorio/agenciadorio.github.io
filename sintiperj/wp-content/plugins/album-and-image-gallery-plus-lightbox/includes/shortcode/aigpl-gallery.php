<?php
/**
 * 'aigpl-gallery' Shortcode
 * 
 * @package Album and Image Gallery Plus Lightbox
 * @since 1.0.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

function aigpl_gallery_grid( $atts, $content = null ) {
	
	// Shortcode Parameter
	extract(shortcode_atts(array(
		'id'				=> array(),
		'grid'    			=> '3',
		'design' 			=> 'design-1',
		'link_target'		=> 'self',
		'gallery_height'	=> '',
		'show_title'		=> 'false',
		'show_description'	=> 'false',
		'show_caption'		=> 'true',
		'image_size'		=> 'full',
		'popup'				=> 'true',
		), $atts));
	
	$shortcode_designs 	= aigpl_designs();
	$post_ids			= !empty($id)						? explode(',', $id) 				: array();
	$grid 				= (!empty($grid) && $grid <= 12) 	? $grid 							: '3';
	$design 			= ($design && (array_key_exists(trim($design), $shortcode_designs))) ? trim($design) : 'design-1';
	$link_target 		= ($link_target == 'blank') 		? '_blank' 							: '_self';
	$gallery_height		= !empty($gallery_height)			? $gallery_height 					: '';
	$show_title			= ($show_title == 'true')			? 'true'							: 'false';
	$show_description	= ($show_description == 'true')		? 'true'							: 'false';
	$show_caption		= ($show_caption == 'false')		? 'false'							: 'true';
	$popup				= ($popup == 'false')				? 'false'							: 'true';
	$image_size 		= !empty($image_size)				? $image_size						: $image_size;
	$height_css 		= '';

	// Height
	if( $gallery_height == 'auto' ) {
		$height_css = "height:auto;";
	} elseif ( !empty($gallery_height) ) {
		$height_css = "height:{$gallery_height}px;";
	}

	// If no id is passed then return
	if( empty($post_ids) ) {
		return $content;
	}

	// Enqueue required script
	if( $popup == 'true' ) {
		wp_enqueue_script('wpos-magnific-script');
		wp_enqueue_script('aigpl-public-js');
	}

	// Shortcode file
	$design_file_path 	= AIGPL_DIR . '/templates/' . $design . '.php';
	$design_file 		= (file_exists($design_file_path)) ? $design_file_path : '';
	
	// Taking some global
	global $post;

	// Taking some variables
	$prefix 		= AIGPL_META_PREFIX;
	$unique			= aigpl_get_unique();
	$loop_count		= 1;
	$popup_cls 		= ($popup == 'true') ? 'aigpl-popup-gallery' 	: '';
	$main_cls 		= "aigpl-cnt-wrp aigpl-col-{$grid} aigpl-columns";
	
	// WP Query Parameters
	$args = array (
		'post_type'     	 	=> AIGPL_POST_TYPE,
		'post_status' 			=> array( 'publish' ),
		'post__in'		 		=> $post_ids,
		'ignore_sticky_posts'	=> true,
	);

	// WP Query Parameters
	$query = new WP_Query($args);

	ob_start();

	// If post is there
	if ( $query->have_posts() ) { ?>

		<div class="aigpl-gallery aigpl-gallery-wrp aigpl-gallery-grid aigpl-clearfix aigpl-<?php echo $design.' '.$popup_cls; ?>" id="aigpl-gallery-<?php echo $unique; ?>">

		<?php while ( $query->have_posts() ) : $query->the_post();

				$gallery_imgs = get_post_meta( $post->ID, $prefix.'gallery_imgs', true );

				if( !empty($gallery_imgs) ) {
					foreach ($gallery_imgs as $img_key => $img_data) {
						
						$gallery_post		= get_post( $img_data );
						$wrpper_cls			= ($loop_count == 1) ? $main_cls.' aigpl-first' : $main_cls;
						$gallery_img_src 	= aigpl_get_image_src( $img_data, $image_size );
						$image_alt_text		= get_post_meta( $img_data, '_wp_attachment_image_alt', true );

						if( $popup == 'true' ) {
							$image_link	= aigpl_get_image_src( $img_data, 'full' );
						} else {
							$image_link = get_post_meta( $img_data, $prefix.'attachment_link', true );
						}

						// Include shortcode html file
						if( $gallery_post && $design_file && $gallery_img_src ) {
							include( $design_file );

							$loop_count++; // Increment loop count

							// Reset loop count
							if( $loop_count == $grid ){
								$loop_count = 0;
							}
						}
					} // End of for each
				}

		endwhile;
		?>

		</div><!-- end .aigpl-gallery-wrp -->

	<?php }

	wp_reset_query(); // Reset WP Query

	$content .= ob_get_clean();
	return $content;
}

// 'aigpl-gallery' shortcode
add_shortcode('aigpl-gallery', 'aigpl_gallery_grid');