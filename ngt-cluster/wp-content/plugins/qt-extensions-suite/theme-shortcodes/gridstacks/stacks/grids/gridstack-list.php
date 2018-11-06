<?php  

/**
 *
 *	Filename: gridstack-grid.php
 *	Function: adds automatic slideshow templates to the theme
 *	Based on materialize slideshow
 *
 *
 *
 **/

 
if(!function_exists("qt_gridstack_list")){
	function qt_gridstack_list($atts){
		/**
		 *	Defaults
		 * 	All parameters can be bypassed by same attribute in the shortcode
		 */
		extract( shortcode_atts( array(

			/* PHp and Query parameters */
			'stackid' => '',
			'quantity' => 4,
			'posttype' => 'post',
			'offset' => 0,
			'taxonomy' => false,
			'term_ids' => false,
			'archivelink' => true,
			'preview' => false,

			'titletag' => 'h3',
			'showthumbnail' => 'true',
			'showtitle' => 'true',
			'showexcerpt' => 'true',
			'showmeta' => 'true',
			'showlink' => 'true',
			'textalign' => 'left'
			
		), $atts ) );


		if(!is_numeric($offset)) {
			$offset = 0;
		}


		if($textalign != 'left' && $textalign != 'center' && $textalign != 'right' ) {
			$textalign = 'left';
		} 

		if($titletag != 'h1' && $titletag != 'h2' && $titletag != 'h3'  && $titletag != 'h4'  && $titletag != 'h5') {
			$titletag = 'h3';
		}



		/**
		 *	Query preparation
		 */
		$argsList = array(
				        'post_per_page' => $quantity,
				        'post_type' => $posttype,
				        'post__not_in' => get_option( 'sticky_posts' ),
				        'posts_per_page' => $quantity,
				        'orderby' => array(  'menu_order' => 'ASC' ,	'post_date' => 'DESC'),
				        'post_status' => 'publish',
				        'offset' => $offset
				     );  
		if ($taxonomy && $term_ids) {
			$argsList[ 'tax_query'] = array(
            		array(
                    'taxonomy' => $taxonomy,
                    'field' => 'id',
                    'terms' => $term_ids,
                    'operator'=> 'IN' //Or 'AND' or 'NOT IN'
             	)
            );
		}
		$the_query = new WP_Query($argsList);






		/**
		 *	Output the HTML of the gridstack 
		 */
		ob_start();
		?>
		<div class="qt-gridstackList" id="<?php echo esc_attr($stackid); ?>">
			<?php if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post(); ?> 
				<div class="row qt-gridstackList-item qt-border qt-border-left thick">
					<div class="col m10 ">
						<?php if($showtitle == "true"){  ?>
								<a href="<?php the_permalink(); ?>" class="qt-imgfx <?php if($preview == 'true' || $preview == '1' ) { ?>qwjquerycontent<?php } ?>">
									<<?php echo esc_attr($titletag); ?> class="title"><?php the_title(); ?></<?php echo esc_attr($titletag); ?>>
								</a>
							<?php } ?>
							<?php if($showmeta == "true"){
								get_template_part('part', 'meta' );
							} ?>
							<?php if($showexcerpt == "true"){  ?>
								<div class="qt-small">
									<?php the_excerpt(); ?>
								</div>
							<?php } ?>
							<?php if($showlink == "true"){  ?>
								<a class="btn <?php if($preview == 'true' || $preview == '1' ) { ?>qwjquerycontent<?php } ?>" href="<?php the_permalink(); ?>"><i class="fa fa-link"></i></a>
							<?php } ?>
					</div>
					<div class="col m2">
						<?php if(has_post_thumbnail() && $showthumbnail == "true"){ ?>
							<a href="<?php the_permalink(); ?>" class="qw-imgfx <?php if($preview == 'true' || $preview == '1' ) { ?>qwjquerycontent<?php } ?>">
								<?php  the_post_thumbnail( 'qantumthemes_gridthumb' , array("class" => "responsive-img"));  ?>
							</a>
						<?php } ?>
					</div>
				</div>
			<?php endwhile; endif; ?> 
		</div>
		<?php
		wp_reset_postdata();
		return ob_get_clean();
	}
}
add_shortcode( "qt-list", "qt_gridstack_list");









