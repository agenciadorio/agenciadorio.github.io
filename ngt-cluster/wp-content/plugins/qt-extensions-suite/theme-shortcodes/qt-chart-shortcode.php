<?php  

/*
*
*	Chart shortcode
*
*/

if(!function_exists('qw_chart_shortcode')) {
	function qw_chart_shortcode($atts){
		extract( shortcode_atts( array(
			'id' => ""
		), $atts ) );


	
		if(!is_numeric($id)) {
			$args = array(
				'post_type' => 'chart',
				'posts_per_page' => 1,
				'post_status' => 'publish',
				'orderby' =>  array ( 'menu_order' => 'ASC', 'date' => 'DESC'),
				'suppress_filters' => false,
				'paged' => 1
			);
			$wp_query = new WP_Query( $args );
			if ( $wp_query->have_posts() ) : while ( $wp_query->have_posts() ) : $wp_query->the_post();
				$id = $wp_query->post->ID;
			endwhile; endif;
		}

		
		$events= qp_get_group('track_repeatable', $id);   
		if(is_array($events)){
			?>
			<div class="qw-chart-container ">
			<?php 
				
			
				$pos = 1;
				foreach($events as $event){ 
					$neededEvents = array('releasetrack_track_title','releasetrack_scurl','releasetrack_buyurl','releasetrack_artist_name','releasetrack_img');
					foreach($neededEvents as $n){
						if(!array_key_exists($n,$events)){
							$events[$n] = '';
						}
					}
				   
					?>
					

					<div class="qw-chart-row qt-color-darker clearfix" id="chartItem<?php echo esc_attr($pos); ?>" data-100p-top="opacity:0;margin-top:50px" data-80p-top="opacity:0;" data-60p-top="opacity:1;margin-top:0px">
						<?php 
						if($event['releasetrack_img'] != ''){
							$img = wp_get_attachment_image_src($event['releasetrack_img'],'small');
							$imglarge = wp_get_attachment_image_src($event['releasetrack_img'],'large');
						}   
						?>
						<div class="maincolor qw-chart-position qt-fade-to-paper" data-bgimage="<?php echo esc_url($img[0]); ?>">
							<h2>
								<?php  if ($event['releasetrack_img'] != ''){ ?><a href="<?php echo esc_attr($imglarge[0]);?>"><?php } ?>
									<?php echo esc_attr($pos); ?>
								<?php  if ($event['releasetrack_img'] != ''){ ?></a><?php } ?>
							</h2>
						</div>
						




						<?php  
		                // Single track buy link
		                if(isset($event['releasetrack_buyurl'])){
			                if($event['releasetrack_buyurl']!=''){
			                    $buylink = $event['releasetrack_buyurl'];
			                    $wc = false;
			                    if(is_numeric($buylink)) {
			                        $wc = true;
			                        $prodid = $buylink;
			                        $buylink = add_query_arg("add-to-cart" ,   $buylink, get_the_permalink());
			                    }
			                    ?>
			                    <a href="<?php echo esc_url($buylink); ?>" <?php if($wc){ ?>data-quantity="1" data-product_id="<?php echo esc_attr($prodid); ?>" <?php } ?> target="_blank" 
			                    class="qw-disableembedding qw-chart-buy <?php if($wc){ ?> product_type_simple add_to_cart_button ajax_add_to_cart <?php } ?> qw-track-buylink btn transparent button">
			                        <?php if(array_key_exists("releasetrack_price",$event)) { echo  esc_attr($event['releasetrack_price']); } ?>
			                       <i class="mdi-action-add-shopping-cart"></i>
			                    </a>
			                    <?php
			                }
		                }
		                ?>




						<?php 
						if($event['releasetrack_scurl'] != ''){
							$regex_mp3 = "/.mp3/";
							if(preg_match ( $regex_mp3 , $event['releasetrack_scurl'] )){
								?>
								<a href="#" class="qw-chart-play btn accentcolor tooltipped playable-mp3-link " data-state="0" data-mp3url="<?php echo esc_url($event['releasetrack_scurl']); ?>" data-position="right" data-tooltip="<?php echo esc_attr__("Play","sonik") ?>">
								 <i class="mdi-av-play-arrow"></i>
								</a>
								<?php
							} else {
								?>
								<a href="#" class="qw-chart-play btn accentcolor tooltipped" data-qtswitch="open" data-target="#chartPlayer<?php echo esc_attr($pos); ?>" data-position="right" data-tooltip="<?php echo esc_attr__("Listen","sonik") ?>">
								 <i class="fa fa-play-circle"></i>
								</a>
								<?php

							}
						}   
						?>

						 <?php 
						if($event['releasetrack_img'] != ''){
							$img = wp_get_attachment_image_src($event['releasetrack_img'],'small');
							$imglarge = wp_get_attachment_image_src($event['releasetrack_img'],'large');
						}   
						?>
							<h3 class="qt-ellipsis"><?php echo esc_attr($event['releasetrack_track_title']); ?></h3>
							<p class="qt-small">
								<?php 
								$aname = $event['releasetrack_artist_name'];
								$nar=explode(',',$aname);
								$n=0;
								foreach($nar as $aname){
									$n++;
									$aname = trim($aname);
									$link = qantumthemes_permalink_by_name($aname);
									if($link!=''){
									 echo '<a href="'.esc_url($link).'" class="qw-artistname-title">'.esc_attr($aname).'</a>';  
									}else{
									  echo esc_attr($aname);  
									};
									if($n<count($nar))
									echo ', ';
								}
								?>
							</p>
						<?php if($event['releasetrack_scurl'] != ''){ ?>
						<div class="qw-music-player clearfix" id="chartPlayer<?php echo esc_attr($pos); ?>">
							<?php  
								/**
								 * 
								 * If is an external media we embed it, otherwise the player already plays the track
								 */
								$regex_media = "/(soundcloud.com|youtube.com|vimeo.com)/";                       
								$regex_mp3 = "/.mp3/";

								if(preg_match ( $regex_media , $event['releasetrack_scurl'] )){
									echo '<a href="'.esc_url($event['releasetrack_scurl']).'">'.esc_url($event['releasetrack_scurl']).'</a>';
								} else if(preg_match ( $regex_mp3 , $event['releasetrack_scurl'] )) {
									// echo do_shortcode('[audio src="'.esc_url($event['releasetrack_scurl']).'"]');
									//  We use our player this is not needed
								}
							
							?>
						</div>
						<?php } ?>
					</div>

					<?php 
					$pos = $pos+1;
				}//foreach
			?>
			</div>
			<?php 
		}//end debuf if
		wp_reset_query();
	}
}

add_shortcode("embedchart","qw_chart_shortcode");
add_shortcode("qt-embedchart","qw_chart_shortcode");