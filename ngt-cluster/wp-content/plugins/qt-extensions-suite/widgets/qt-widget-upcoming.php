<?php
/**
 * Upcoming Show Air widget
 * Author: QantumThemes
*/

add_action( 'widgets_init', 'qantumthemes_upcoming_widget' );
function qantumthemes_upcoming_widget() {
	register_widget( 'qantumthemes_Upcoming_Widget' );
}

class qantumthemes_Upcoming_Widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array( 'classname' => 'upcomingwidget', 'description' => __('A widget that displays on air show', 'qt-extension-suite') );
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'upcomingwidget-widget' );
		parent::__construct( 'upcomingwidget-widget', __('QT Upcoming Widget', 'qt-extension-suite'), $widget_ops, $control_ops );
	}
	public function widget( $args, $instance ) {
		extract( $args );
		echo $before_widget;


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

		if(!array_key_exists("title", $instance)){
			$instance['title'] = 'missing title';
		} 
		echo $before_title.$instance['title'].$after_title; 
		
		



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

		wp_reset_postdata();
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
		?>
		<ul class="qt-archives-widget">
		<?php
		while ( $the_query_meta->have_posts() ):
			
				$active = '';
				$maincolor = '';
				$the_query_meta->the_post();
				$total++;
				setup_postdata( $post );
				
				$schedule_date = get_post_meta($post->ID, 'specific_day', true);
				$schedule_week_day = get_post_meta($post->ID, 'week_day', true);




				/*
				1. check if is today
				*/
				

				if($schedule_date == $date){
					
					
					$tabsArray[] = array('name' => $post->post_name,
				                     'title' => $post->post_title,
				                     'id' => $post->ID);


					$id_of_currentday = $post->ID;
					$active = ' active';
					$maincolor = ' maincolor';

				} else {
					/*
					2. check if is this day of the week
					*/
					
					if(is_array($schedule_week_day)){
						foreach($schedule_week_day as $day){ // each schedule can fit multiple days
							if(strtolower($day) == strtolower($current_dayweek)){
								//echo 'Found'.$post->ID;
								$id_of_currentday = $post->ID;


								$tabsArray[] = array('name' => $post->post_name,
				                     'title' => $post->post_title,
				                     'id' => $post->ID);


								$id_of_currentday = $post->ID;
								$active = ' active';
								$maincolor = ' maincolor';
							}
						}
					}
				}
		endwhile;

		wp_reset_postdata();	

			if(!array_key_exists('quantity', $instance)){
				$instance['quantity'] = 4;
			}
			$max = $instance['quantity'];
			foreach($tabsArray as $tab){ 

			   	$events= qantumthemes_get_group('track_repeatable',$tab['id']);   
			    if(is_array($events) ){
			    	$n = 0;
			    	$maxItems = 0;
			      	foreach($events as $event){ 

			      		if(array_key_exists('show_id', $event) ){

			      			if(array_key_exists(0, $event['show_id'])){
			      				if(is_numeric($event['show_id'][0])){

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

						      	/*
						      	*
						      	*	Time Control
						      	*
						      	*
						      	*
						      	*/
						      	if($show_time_end == "00:00"){
						      		$show_time_end = "24:00";
						      	}


						      	$found = 0;
						      	$now = current_time("H:i");

							      	if((($show_time < $now && $now < $show_time_end) || $show_time > $now) && $maxItems < $instance['quantity']){

											$found = 1;
											$maxItems = $maxItems+1;
								        ?>
								          <li class="qw-upcoming-item">
								          	<?php
								          	if(has_post_thumbnail($post->ID)){
								          		$attr = array(
													'class' => "img-responsive",
													'alt'   => trim( esc_attr($tab['title'])),
												);
												?><a class="cbp-vm-image" href="<?php echo esc_url(esc_attr(get_permalink($post->ID))); ?>" title="<?php echo esc_attr__("Read More about ","qt-extensions-suite").esc_attr($post->post_title); ?>"><?php
								          		the_post_thumbnail( 'thumbnail', $attr );
								          		?></a><?php
										    }
								          	?>
								          	<h6 class="tit">
									          	<a href="<?php echo esc_url(esc_attr(get_permalink($post->ID))); ?>" title="<?php echo esc_attr__("Read More about ","qt-extensions-suite").esc_attr($post->post_title); ?>">
									          	<?php echo  esc_attr($post->post_title); ?>
									          	</a>
								          	</h6>


								          	<?php 
								          	$show_time_d = $show_time;
									      	$show_time_end_d = $show_time_end;


									      	// 12 hours format
									      	if(array_key_exists('timeformat', $instance)){
									      		if($instance['timeformat'] == '12h'){
									      			$show_time_d = date("g:i a", strtotime($show_time_d));
									      			$show_time_end_d = date("g:i a", strtotime($show_time_end_d));
									      		}
									      	}
									      
									      	?>

								          	<span><i class="mdi-image-timer"></i> <?php echo esc_attr($show_time_d);?></span> <span><i class="mdi-image-timer-off"></i> <?php echo esc_attr($show_time_end_d);?></span>
								          	
								          </li>
										<?php
									}
								}	      				
							}
						wp_reset_postdata();
						}
					}//foreach
				} else {
					echo esc_attr__("Sorry, there are no shows scheduled on this day","qt-extensions-suite");
				}
			} // foreach end

			?></ul><?php
		// L'OUTPUT ///////////////////////
		echo $after_widget;
	}

	//Update the widget 
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		//Strip tags from title and name to remove HTML 

		$attarray = array(
				'title',
				'timeformat',
				'quantity'
		);
		foreach ($attarray as $a){
			$instance[$a] = strip_tags( $new_instance[$a] );
		}
		return $instance;
	}

	public function form( $instance ) {
		//Set up some default widget settings.
		$defaults = array( 'title' =>"", 
							'timeformat' => '24h',
							'quantity' => '4');
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
	 	<h2>General options</h2>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'qt-extension-suite'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>
		<!-- ITEMS NUMBER ========================= -->
		<p>
			<label for="<?php echo $this->get_field_id( 'quantity' ); ?>"><?php echo esc_attr__('Number of Items:', "qt-extensions-suite"); ?></label>
			<input id="<?php echo $this->get_field_id( 'quantity' ); ?>" name="<?php echo $this->get_field_name( 'quantity' ); ?>" value="<?php echo $instance['quantity']; ?>" style="width:100%;" />
		</p>

		<!-- TIME FORMAT ========================= -->
		<p>
			<label for="<?php echo $this->get_field_id( 'timeformat' ); ?>"><?php esc_attr__('Time Format', "qt-extensions-suite"); ?></label><br />			
           12h   <input type="radio" name="<?php echo $this->get_field_name( 'timeformat' ); ?>" value="12h" <?php if($instance['timeformat'] == '12h'){ echo ' checked= "checked" '; } ?> />  
           24h  <input type="radio" name="<?php echo $this->get_field_name( 'timeformat' ); ?>" value="24h" <?php if($instance['timeformat'] == '24h'){ echo ' checked= "checked" '; } ?> />  
		</p>
     
     

	<?php

	}
}

