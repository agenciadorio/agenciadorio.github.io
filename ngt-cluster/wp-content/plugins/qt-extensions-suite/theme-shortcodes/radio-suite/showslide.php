<?php


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

?>
<?php
if(!function_exists('qp_get_group')){
function qp_get_group( $group_name , $post_id = NULL ){
  	global $post; 	  
 	if(!$post_id){ $post_id = $post->ID; }
  	$post_meta_data = get_post_meta($post_id, $group_name, true);  
  	return $post_meta_data;
}}
?>

<div class="slider">
<?php


	
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

		//echo 'Taking shows of schedue '.$id_of_currentday;
		/* create query to choose shows of today */
		?>
			<ul class="slides z-depth-1">
					<?php
				   	$events= qp_get_group('track_repeatable',$tab['id']);   
				    if(is_array($events)){

				    	$maximum = 6;
					    $total = 0;


					    /*
					    *
					    *	Debug
					    */
					   // print_r($events);

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



					      	

					      	// We start looking from the actual moment

					      	if($now < $show_time_end){
					          	/* No shows without picture!! */
					          	if(has_post_thumbnail($post->ID) && $total < $maximum){
					          		
					          		
							    	$total ++;
					          		?>
						          	<li>
						          		<?php
						          		$attr = array(
											'class' => "img-responsive qw-slideimage",
											'alt'   => trim( strip_tags(esc_attr($tab['title']))),
										);
						          		the_post_thumbnail( 'full', $attr );
						          		?>


						          		<?php 
							          	$show_time_d = $show_time;
								      	$show_time_end_d = $show_time_end;

								      	// 12 hours format
								      	if(get_theme_mod('QT_timing_settings', '12') == '12'){
								      		$show_time_d = date("g:i a", strtotime($show_time_d));
								      		$show_time_end_d = date("g:i a", strtotime($show_time_end_d));
								      	}

								      	
								      	?>



							          	<div class="caption left-align">
								          <h3><?php echo  esc_attr($post->post_title); ?></h3>
								          <h5 class="light white-text text-lighten-3 maincolor"><?php echo esc_attr($tab['title']); ?> <?php echo esc_attr($show_time_d); ?> <i class="mdi-av-fast-forward"></i> <?php echo esc_attr($show_time_end_d); ?></h5>
								          <div class="canc"></div>
								          <?php if($now > $show_time && $now < $show_time_end){ ?>
								          	<h6 class="red white-text">
								          		<?php echo esc_attr__("Now On Air","qt-extensions-suite"); ?>
								          	</h6>
								          	<?php } else { ?>
								          		<h6 class="grey white-text">
								          			<?php echo esc_attr("Upcoming","qt-extensions-suite");  ?>
								          	</h6>
								          	<?php } ?>
								          	<a class="btn-floating btn-large waves-effect waves-light accentcolor qw-tooltipped" data-position="left" data-tooltip="<?php echo esc_attr__("Discover More", "qt-extensions-suite"); ?>" href="<?php echo get_permalink($post->ID); ?>" ><i class="mdi-content-add"></i></a>
								        </div>

						          	</li>
								<?php
								} //if(has_post_thumbnail($post->ID))
							}
							wp_reset_postdata();
						}//foreach
					} else {
						echo esc_attr__("Sorry, there are no shows scheduled on this day","qt-extensions-suite");
					}
					?>
				</ul>
		<?php

	} else {
		/* create a query to choose shows bu order */

	}


	wp_reset_postdata();
?>
</div>










    