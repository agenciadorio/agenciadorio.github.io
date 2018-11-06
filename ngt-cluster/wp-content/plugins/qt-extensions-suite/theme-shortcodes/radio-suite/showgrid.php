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
<div class="tabs-selector qw-pushpin hide-on-med-and-down">
      <ul class="tabs" id="qwShowSelector">
        <?php
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

			while ( $the_query_meta->have_posts() ):

				$active = '';
				$maincolor = '';
				$the_query_meta->the_post();
				$total++;
				setup_postdata( $post );
				$tabsArrayTemp = array('name' => $post->post_name,
				                     'title' => $post->post_title,
				                     'id' => $post->ID,
				                     'post' => $post,
				                     'active' => '');
				$schedule_date = get_post_meta($post->ID, 'specific_day', true);
				$schedule_week_day = get_post_meta($post->ID, 'week_day', true);


				/*
				1. check if is a precide date
				*/
				if($schedule_date == $date){
					$id_of_currentday = $post->ID;
					$active = ' active';
					$tabsArrayTemp["active"] = 'active';
					$maincolor = ' maincolor';
				} else {
					/*
					2. check if is this day of the week
					*/
					if(is_array($schedule_week_day)){
						foreach($schedule_week_day as $day){ // each schedule can fit multiple days
							if(strtolower($day) == strtolower($current_dayweek)){
								$id_of_currentday = $post->ID;
								$active = ' active';
								$maincolor = ' maincolor';
							}
							
						}
					}

				}
				$tabsArray[] = $tabsArrayTemp;
				?>
				 <li class="tab col qwFlexwidth ">
				 <a href="#<?php echo esc_js(esc_attr($post->post_name)); ?>" id="optionSchedule<?php echo esc_js(esc_attr($post->post_name)); ?>" class="<?php echo esc_attr($active.$maincolor);?>"><?php echo esc_attr($post->post_title); ?>
				 </a></li>

				 <?php
			endwhile;

			wp_reset_postdata();	
        ?>
      </ul>

   
</div>


<?php
	if($total > 0){
		echo '<div data-target=".qwFlexwidth" data-dynamicwidth="'.esc_js(esc_attr(100/$total)).'"></div>';
}
?>


<?php


/*
*
*	For mobile // options select instead of tabs // driven by js to click on hidden tabs
*
**

*/
?>
<div class="hide-on-large-only ">

	<h4><?php echo esc_attr__("Choose a day","qt-extensions-suite"); ?></h4>
	<select id="qwShowDropdown">
	        <?php
				wp_reset_postdata();
				$result         = '';
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
				//$tabsArray = array();
				//$id_of_currentday = 0;

				while ( $the_query_meta->have_posts() ):

					$active = '';
					$maincolor = '';
					$the_query_meta->the_post();
					$total++;
					setup_postdata( $post );
					/*$tabsArray[] = array('name' => $post->post_name,
					                     'title' => $post->post_title,
					                     'id' => $post->ID,
					                     'post' => $post);*/
					?>
					 <option value="optionSchedule<?php echo esc_js(esc_attr($post->post_name)); ?>"><?php echo esc_attr($post->post_title); ?></option>
					 <?php
				endwhile;
				
				wp_reset_postdata();
	        ?>
	 </select>
</div>


<?php
/*
*	======================================================================
*
*	CONTENT OF THE TABS
*
*
*/

$gridlayout = get_theme_mod("QT_schedule_default_layout","grid");

foreach($tabsArray as $tab){ 
	?>
	<div class="tabcontent x <?php echo esc_attr($tab["active"]); ?>" id="<?php echo esc_js(esc_attr($tab['name'])); ?>">
   		<h2 class="tabtitle"><?php echo esc_attr($tab['title']); ?></h2>
		<?php
			global $post;
			$post = $tab["post"];
			get_template_part("part-schedule-day");
		?>
	</div>
<?php 
	$active = '';
} ?>
<div class="canc" id="qwShowgridEnd"></div>








    