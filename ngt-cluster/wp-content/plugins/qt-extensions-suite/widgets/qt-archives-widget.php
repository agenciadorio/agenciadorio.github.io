<?php  
/**
 * 
 * qt-archives-widget.php
 * Creates custom archive widgets
 * 
 */
?>
<?php

add_action( 'widgets_init', 'qantumthemes_archives_widget' );
function qantumthemes_archives_widget() {
	register_widget( 'qantumthemes_archives_widget' );
}

class qantumthemes_archives_widget extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'archiveswidget', 'description' => esc_attr__('A widget that displays archives ', "qt-extensions-suite") );
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'qantumthemes_archives_widget' );
		parent::__construct( 'qantumthemes_archives_widget', esc_attr__('QT Archives Widget', "qt-extensions-suite"), $widget_ops, $control_ops );
	}


	function widget( $args, $instance ) {
		extract( $args );
		echo $before_widget;
		echo $before_title.$instance['title'].$after_title; 
		$query = new WP_Query();

		//Send our widget options to the query
		
		$queryArray =  array(
			'post_type' => $instance['posttype'],
			'posts_per_page' => $instance['number'],
			'ignore_sticky_posts' => 1,
			'order' => 'ASC'
		   );
		
		if($instance['specificid'] != ''){
			$posts = explode(',',$instance['specificid']);
			$finalarr = array();
			foreach($posts as $p){
				if(is_numeric($p)){
					$finalarr[] = $p;
				}
			};
			$queryArray['post__in'] = $finalarr;
		}
		
		$queryArray['orderby'] = $instance['order'];

		if($queryArray['orderby'] == 'date') {
			$queryArray['order'] = 'DESC';
		}

		if(!post_type_exists( $instance['posttype'] )) {
			echo esc_attr__("ALERT: This is a custom post type. Please install QT Extension Suite plugin to correctly visualize the contents.", "qt-extensions-suite");
			return;
		}
		


		// ========== EVENTS ONLY QUERY =================
		// 
		if ($instance['posttype'] === 'event') {
			$queryArray['orderby'] = 'meta_value';
			$queryArray['order']   = 'ASC';
			$queryArray['meta_key'] = EVENT_PREFIX.'date';
			if(get_theme_mod( 'qt_events_hideold', 0 ) == '1'){
			    $queryArray['meta_query'] = array(
	            array(
	                'key' => EVENT_PREFIX.'date',
	                'value' => date('Y-m-d'),
	                'compare' => '>=',
	                'type' => 'date'
	                 )
	           	);
			}
		}
		// ========== END OF EVENTS ONLY QUERY =================
		 
		

		// ========== POSTS ONLY QUERY =================
		// 
		if ($instance['posttype'] === 'post') {
			$queryArray['orderby'] = 'date';
			$queryArray['order']   = 'DESC';
			
		}
		// ========== END OF POSTS ONLY QUERY =================
		 
		
		// ========== CHOOSE CORRECT TAXONOMY =================
		// 
		switch($instance['posttype']){
			case "shows":
				$taxonomy = "showgenre";
				break;
			case "schedule":
				$taxonomy = "schedule_cat";
				break;
			case "chart":
				$taxonomy = "chartcategory";
				break;
			case "members":
				$taxonomy = "membertype";
				break;
			case "artist":
				$taxonomy = "artistgenre";
				break;
			case "podcast":
				$taxonomy = "podcastfilter";
				break;
			case "event":
				$taxonomy = "eventtype";
				break;
			case "mediagallery":
				$taxonomy = false;
				break;
			case "release":
				$taxonomy = "genre";
				break;
			case "radiochannel":
				$taxonomy = false;
				break;
			case "post":
			default:
				$taxonomy = "category";
		}

		// echo '<pre>';
		// print_r($queryArray);
		// echo '</pre>';
		
		$query = new WP_Query($queryArray);
		?>
		<ul class="qt-archives-widget">
		<?php
		if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post();
			global $post;
			?>
			<li>
				<?php 
				if($instance['showcover']){ 
			   		if(has_post_thumbnail())  { ?>
			   		<a class="hide-on-med-and-down" href="<?php esc_url(the_permalink()); ?>">
						<?php  the_post_thumbnail('qantumthemes_minithumbnail'); ?>
					</a>
					<?php 
					}
				} ?>
				<h6 class="tit"><a class="" href="<?php esc_url(the_permalink()); ?>"><?php the_title(); ?></a></h6>
				<p class="qt-ellipsis">
				<?php  echo strip_tags (get_the_term_list( $post->ID, $taxonomy, '', __("&nbsp;/&nbsp;", "qt-extensions-suite"), '' ));  ?>
				</p>
			</li>
			<?php endwhile; endif;  ?>
		
		</ul>
		<?php 
		 // if(isset($instance['archivelink_url']) && isset($instance['archivelink_text'])){
			if($instance['archivelink_url'] != ''){
				if($instance['archivelink_text']==''){$instance['archivelink_text'] = esc_attr__('See all',"qt-extensions-suite");};
				echo '<h6 class="qt-border top readalllink clearfix"><i class="qticons-chevron-right"></i><a href="'.esc_url($instance['archivelink_url']).'" class="">'.esc_attr($instance['archivelink_text']).'</a></h6>';
			} 
		 // }
		?>
		<?php
		wp_reset_postdata();
		// L'OUTPUT ///////////////////////
		echo $after_widget;
	}

	//Update the widget 
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		//Strip tags from title and name to remove HTML 

		$attarray = array(
				'title',
				'showcover',
				'number',
				'specificid',
				'order',
				'archivelink',
				'archivelink_text',
				'posttype',
				'archivelink_url'
		);

		if(!is_numeric($new_instance['number'])){
			$new_instance['number'] = 5;
		}

		$new_instance['archivelink_url'] = esc_url($new_instance['archivelink_url']);

		foreach ($attarray as $a){
			$instance[$a] = esc_attr(strip_tags( $new_instance[$a] ));
		}
		return $instance;
	}

	function form ( $instance ) {
		//Set up some default widget settings.
		$defaults = array( 'title' => "",
							'showcover'=> '1',
							'number'=> '5',
							'specificid'=> '',
							'order'=> 'Page Order',
							'archivelink'=> 'show',
							'posttype'=> 'post',
							'archivelink_text'=> esc_attr__('See all',"qt-extensions-suite"),
							'archivelink_url' => ''
							);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<h2>General options</h2>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php echo esc_attr__('Title:', "qt-extensions-suite"); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'posttype' ); ?>"><?php echo esc_attr__('Post type', "qt-extensions-suite"); ?></label><br>
			<?php  
			$args = array(
			   'public'   => true,
			   '_builtin' => false
			);
			$post_types = get_post_types( $args ); 
			$post_types[] = 'post';
			?>
			<select id="<?php echo $this->get_field_id( 'posttype' ); ?>" name="<?php echo $this->get_field_name( 'posttype' ); ?>">
			<?php foreach ( $post_types as $post_type ) { ?>
					<option value="<?php echo esc_attr($post_type); ?>" <?php if($instance['posttype'] === $post_type): ?> selected="selected" <?php endif; ?>><?php echo esc_attr($post_type); ?></option>
			<?php } ?>
			</select>



		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'specificid' ); ?>"><?php echo esc_attr__('Add only specific ids (comma separated line 23,46,94):', "qt-extensions-suite"); ?></label>
			<input id="<?php echo $this->get_field_id( 'specificid' ); ?>" name="<?php echo $this->get_field_name( 'specificid' ); ?>" value="<?php echo $instance['specificid']; ?>" style="width:100%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php echo esc_attr__('Quantity:', "qt-extensions-suite"); ?></label>
			<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" value="<?php echo $instance['number']; ?>" style="width:100%;" />
		</p>
	  <p>
		<label for="<?php echo $this->get_field_id( 'showcover' ); ?>"><?php echo esc_attr__('Show thumbnail', "qt-extensions-suite"); ?></label><br />			
		   <?php echo esc_attr__("Yes","qt-extensions-suite"); ?>   <input type="radio" name="<?php echo $this->get_field_name( 'showcover' ); ?>" value="1" <?php if($instance['showcover'] == '1'){ echo ' checked= "checked" '; } ?> />
		   <?php echo esc_attr__("No","qt-extensions-suite"); ?>  <input type="radio" name="<?php echo $this->get_field_name( 'showcover' ); ?>" value="0" <?php if($instance['showcover'] != '1'){ echo ' checked= "checked" '; } ?> />  
		</p>  
		<p>
		<label for="<?php echo $this->get_field_id( 'showcover' ); ?>"><?php echo esc_attr__('Order', "qt-extensions-suite"); ?></label><br />			
			<?php echo esc_attr__("Page order","qt-extensions-suite"); ?>  <input type="radio" name="<?php echo $this->get_field_name( 'order' ); ?>" value="menu_order" <?php if($instance['order'] == 'menu_order'){ echo ' checked= "checked" '; } ?> />
		   	<?php echo esc_attr__("Date","qt-extensions-suite"); ?>  <input type="radio" name="<?php echo $this->get_field_name( 'order' ); ?>" value="date" <?php if($instance['order'] == 'date'){ echo ' checked= "checked" '; } ?> /> 
		   	<?php echo esc_attr__("Random","qt-extensions-suite"); ?>   <input type="radio" name="<?php echo $this->get_field_name( 'order' ); ?>" value="rand" <?php if($instance['order'] == 'Random'){ echo ' checked= "checked" '; } ?> />  
		</p>  
		<p>
			<label for="<?php echo $this->get_field_id( 'archivelink' ); ?>"><?php echo esc_attr__('Show link to archive', "qt-extensions-suite"); ?></label><br />			
			<?php echo esc_attr__("Show","qt-extensions-suite"); ?>   <input type="radio" name="<?php echo $this->get_field_name( 'archivelink' ); ?>" value="show" <?php if($instance['archivelink'] == 'show'){ echo ' checked= "checked" '; } ?> />  
			<?php echo esc_attr__("Hide","qt-extensions-suite"); ?>  <input type="radio" name="<?php echo $this->get_field_name( 'archivelink' ); ?>" value="hide" <?php if($instance['archivelink'] == 'hide'){ echo ' checked= "checked" '; } ?> />  
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'archivelink_text' ); ?>"><?php echo esc_attr__('Link to archive text:', "qt-extensions-suite"); ?></label>
			<input id="<?php echo $this->get_field_id( 'archivelink_text' ); ?>" name="<?php echo $this->get_field_name( 'archivelink_text' ); ?>" value="<?php echo $instance['archivelink_text']; ?>" style="width:100%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'archivelink_url' ); ?>"><?php echo esc_attr__('Link to archive URL:', "qt-extensions-suite"); ?></label>
			<input id="<?php echo $this->get_field_id( 'archivelink_url' ); ?>" name="<?php echo $this->get_field_name( 'archivelink_url' ); ?>" value="<?php echo $instance['archivelink_url']; ?>" style="width:100%;" />
		</p>
	<?php

	}
}
?>