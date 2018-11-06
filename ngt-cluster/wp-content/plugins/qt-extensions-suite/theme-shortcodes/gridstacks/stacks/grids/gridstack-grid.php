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

 
if(!function_exists("qt_gridstack_grid")){
	function qt_gridstack_grid($atts){
		/**
		 *	Defaults
		 * 	All parameters can be bypassed by same attribute in the shortcode
		 */
		extract( shortcode_atts( array(

			/* PHp and Query parameters */
			'stackid' => '',
			'quantity' => 4,
			'posttype' => 'post',
			'orderby' => 'post_date',
			'offset' => 0,
			'taxonomy' => false,
			'term_ids' => false,

			
			'coll' => '4',
			'colm' => '3',
			'cols' => '2',

			'titletag' => 'h3',
			'showthumbnail' => 'true',
			'showtitle' => 'true',
			'showexcerpt' => 'true',
			'showmeta' => 'true',
			'showlink' => 'true',
			'textalign' => 'left',
			'picturesize' => '',
			'preview' => false,
			'masonry' => '0',
			'card' => 'true'
			
		), $atts ) );


		if(!is_numeric($offset)) {
			$offset = 0;
		}


		
		if($card == 'true' || $card == '1' ) {
			$card = true;
		} else {
			$card = false;
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
				        'post_type' => esc_attr($posttype),
				        'post__not_in' => get_option( 'sticky_posts' ),
				        'posts_per_page' => esc_attr(intval($quantity)),
				        'orderby' => array(  'menu_order' => 'ASC' ,	'post_date' => 'DESC'),
				       
				        'post_status' => 'publish',
				        'offset' => esc_attr(intval($offset))
				     );  
		if ($taxonomy && $term_ids) {
			$argsList[ 'tax_query'] = array(
            		array(
                    'taxonomy' => esc_attr($taxonomy),
                    'field' => 'id',
                    'terms' => esc_attr(intval($term_ids)),
                    'operator'=> 'IN' //Or 'AND' or 'NOT IN'
             	)
            );
		}
		$the_query = new WP_Query($argsList);


		/**
		 *	Column Width Check
		 *
		 * 
		 */
		
		$columnsizes = array($coll, $colm, $cols);

		for ($n = 0; $n < count($columnsizes); $n++){

			
			$columnsizes[$n] = intval($columnsizes[$n]);
			if(!is_numeric($columnsizes[$n])){
				$columnsizes[$n] = 4;
			} else {
				if($columnsizes[$n] == 5 || $columnsizes[$n] >= 7){
					$columnsizes[$n] = 6;
				}
			}
		}








		/**
		 *	Output the HTML of the gridstack 
		 */
		ob_start();
		?>


		<div class="qt-gridstackGrid row <?php if($masonry == '1'){ ?> masonrycontainer <?php } ?>" id="<?php echo esc_attr($stackid); ?>">
			
				
				<?php if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post(); ?> 

						<?php 

						//Actually not used, maybe later for another type of grid
						//
						/*
						$bg = '';
						if(has_post_thumbnail()){ 
							$img =  wp_get_attachment_image_src(get_post_thumbnail_id( $post->ID ),'large');
							$bg = $img [0];
						} 
						*/
						?>

						<div class="qt-grid-item  <?php if($masonry == '1'){ ?> ms-item negative <?php } ?> col s<?php echo esc_attr($cols); ?> m<?php echo esc_attr($colm); ?> l<?php echo esc_attr($coll); ?>">
							<div class="content qt-border top thick <?php if($card){ ?>negative<?php } ?> <?php echo esc_attr($textalign.'-align'); ?> qt-border-accent">

								<?php if(has_post_thumbnail() && $showthumbnail == "true"){ ?>
									<a href="<?php the_permalink(); ?>"  class="qw-imgfx <?php if($preview == 'true' || $preview == '1' ) { ?>qwjquerycontent<?php } ?>">
										<?php 
										$thumbsize = "qantumthemes_medium-thumb";
										$type = get_post_type();
										if($type === 'release' || $type === 'podcast') {
											$thumbsize = 'qantumthemes_gridthumb';
										}
										if($picturesize !== '') {
											 $thumbsize = $picturesize;
										}
										the_post_thumbnail( $thumbsize , array("class" => "responsive-img")); 

										?>
									</a>
								<?php } ?>

								<?php if($card){ ?><div class="qt-padded"><?php } ?>
								

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
								<?php if($card){ ?></div><?php } ?>

								<?php if($showlink == "true"){  ?>
									<a class="btn qt-fullwidth <?php if($preview == 'true' || $preview == '1' ) { ?>qwjquerycontent<?php } ?>" href="<?php the_permalink(); ?>"><i class="fa fa-link"></i></a>
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
add_shortcode( "qt-grid", "qt_gridstack_grid");









