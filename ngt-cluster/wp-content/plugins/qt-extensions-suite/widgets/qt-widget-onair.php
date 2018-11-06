<?php
/**
 * Now On Air widget
 * Author: QantumThemes
*/

add_action( 'widgets_init', 'qantumthemes_onair_widget' );
function qantumthemes_onair_widget() {
	register_widget( 'qantumthemes_Onair_Widget' );
}

class qantumthemes_Onair_Widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array( 'classname' => 'onairwidget', 'description' => __('A widget that displays on air show', 'qt-extension-suite') );
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'onairwidget-widget' );
		parent::__construct( 'onairwidget-widget', __('QT Onair Widget', 'qt-extension-suite'), $widget_ops, $control_ops );
	}
	public function widget( $args, $instance ) {
		extract( $args );
		echo $before_widget;
		if(array_key_exists("title",$instance)){
			echo $before_title.$instance['title'].$after_title; 
		}
		
		
		



		/*
		*
		*
		*	DISPLAY "ON AIR" SHOW ============================================!
		*
		*/


		/*
		*
		*
		*	Identify current date and day
		*
		*/

		$date = current_time("Y-m-d");
		//echo $date;

		$current_dayweek = current_time("D");
		//echo $dayweek;


		/* How the comparison is made:
		1. id the current day is like a specific day of the schedule, that one is the current. 
		2. if no schedule has exact full date like today, we check if one has the day of the week of today
		3. Else, we give up
		*/

		if(!function_exists('qantumthemes_get_group')){
		function qantumthemes_get_group( $group_name , $post_id = NULL ){
		  	global $post; 	  
		 	if(!$post_id){ $post_id = $post->ID; }
		  	$post_meta_data = get_post_meta($post_id, $group_name, true);  
		  	return $post_meta_data;
		}}


		
		$args           = array(

			'post_type' => 'schedule',
			 'posts_per_page' => 31,
			 'post_status' => 'publish',
	 		 'orderby' => 'menu_order',
	 		 'order'   => 'ASC'
		);
		$the_query_meta = new WP_Query( $args );
		global $post;
		$total = 0;
		$tabsArray = array();

		
		
		$id_of_currentday = 0;
		//$foundit = 0;

		while ( $the_query_meta->have_posts() && $id_of_currentday == 0 ):

			$active = '';
			$maincolor = '';
			$the_query_meta->the_post();
			$total++;
			setup_postdata( $post );
			
			/*
			*
			*	Create the array for making the content
			*
			**/

			$tab = array('name' => $post->post_name,
			                     'title' => $post->post_title,
			                     'id' => $post->ID);

			/*
			*
			*
			*	Find out if is a day of the calendar
			*
			*/

			$schedule_date = get_post_meta($post->ID, 'specific_day', true);
			$schedule_week_day = get_post_meta($post->ID, 'week_day', true);

			//echo 'Looking'.$post->ID.'<br>';
			/*
			1. Find which is the current day, otherwise random shows will be shown
			*/
			if($schedule_date == $date){
				$id_of_currentday = $post->ID;
				
			} else {
				/*
				2. check if is this day of the week
				*/
				
				if(is_array($schedule_week_day)){
					foreach($schedule_week_day as $day){ // each schedule can fit multiple days
						if(strtolower($day) == strtolower($current_dayweek)){
							//echo 'Found'.$post->ID;
							$id_of_currentday = $post->ID;

						}
					}
				}
			}
			
			
		endwhile;

		if($id_of_currentday != 0){
			?>
			<div class="negative qt-onair-widget">
				<?php
			   	$events= qantumthemes_get_group('track_repeatable',$tab['id']);   
			    if(is_array($events)){
			    	$maximum = 6;
				    $total = 0;
				    $found = 0;
			      	foreach($events as $event){ 
				      	$neededEvents = array('show_id','show_time','show_time_end');
				      	foreach($neededEvents as $n){
				          if(!array_key_exists($n,$events)){
				              $events[$n] = '';
				          }
				      	}
				      	$show_id = $event['show_id'][0];
				      	global $post;
				      	$post = get_post($show_id); 
				      	$show_time = $event['show_time'];
				      	$show_time_end = $event['show_time_end'];
				      	$now = current_time("H:i");

				      	if($show_time_end == "00:00"){
				      		$show_time_end = "24:00";
				      	}

				      	if($show_time < $now && $now < $show_time_end  /**/ && $found == 0){
				      		$found = 1;
				          	if(has_post_thumbnail($post->ID)){
				          	?>
				          		<div class="qw-relative qw-fancyborder bottom"><?php
						          	$attr = array(
										'class' => "img-responsive qw-relative",
										'alt'   => trim( esc_attr($tab['title'])),
									);
									?>
									<a class="accentcolor" href="<?php echo get_permalink($post->ID); ?>" >
									<?php the_post_thumbnail( array("420","420"), $attr ); ?>
									</a>

								</div>
							<?php } ?>
				          	<div class="contents">
		          				<h4><?php echo  esc_attr($post->post_title); ?></h4>	
					          	<p>
					          	<?php
								$custom_desc = get_post_meta($post->ID, 'show_incipit', true);
								if($custom_desc == ''){
									$excerpt = $post->post_content;
									$custom_desc = $excerpt;
									$charlength = 120;
									if ( mb_strlen( $excerpt ) > $charlength ) {
										$subex = mb_substr( $excerpt, 0, $charlength - 5 );
										$exwords = explode( ' ', $subex );
										$excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
										if ( $excut < 0 ) {
											$custom_desc = mb_substr( $subex, 0, $excut );
										} else {
											$custom_desc = $subex;
										}
										
									} else {
										$custom_desc = $excerpt;
									}
									$custom_desc .= '[...]';
								}
								echo wp_kses_post($custom_desc);
								?>		
								</p>	
								<p class="text-center">						      
					          	<a class="btn btn-large waves-effect waves-light accentcolor" href="<?php echo get_permalink($post->ID); ?>" ><?php echo esc_attr__("Discover More","qt-extensions-suite") ?></a>
					          	</p>
					        </div>
							<?php
						}
						wp_reset_postdata();
					}//foreach
				} else {
					echo esc_attr__("Sorry, there are no shows scheduled on this day","qt-extensions-suite");
				}
				?>
			</div>	
		<?php
		} 
		wp_reset_postdata();

		/*
		!============END=====================!
		*/



		// L'OUTPUT ///////////////////////
		echo $after_widget;
	}

	//Update the widget 
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		//Strip tags from title and name to remove HTML 

		$attarray = array(
				'title'
		);
		foreach ($attarray as $a){
			$instance[$a] = strip_tags( $new_instance[$a] );
		}
		return $instance;
	}

	public function form( $instance ) {
		//Set up some default widget settings.
		$defaults = array( 'title' => __('Now On Air', "qt-extensions-suite"));
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
	 	<h2>General options</h2>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php echo esc_attr__('Title:', "qt-extensions-suite"); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>
		

	<?php

	}
}

