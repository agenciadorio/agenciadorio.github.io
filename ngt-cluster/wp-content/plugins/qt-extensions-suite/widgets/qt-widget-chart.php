<?php
/**
 * NChart widget
 * Author: QantumThemes
*/

add_action( 'widgets_init', 'qantumthemes_charts_widget' );
if(!function_exists('qantumthemes_charts_widget')){
function qantumthemes_charts_widget() {
	register_widget( 'qantumthemes_Charts_Widget' );
}}

class qantumthemes_Charts_Widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array( 'classname' => 'chartswidget', 'description' => __('A widget that displays chart tracks', 'qt-extension-suite') );
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'chartswidget-widget' );
		parent::__construct( 'chartswidget-widget', __('QT Top 10 Chart Widget', 'qt-extension-suite'), $widget_ops, $control_ops );
	}
	public function widget( $args, $instance ) {
		extract( $args );


		$attarray = array(
				'title',
				'showcover',
				'showtitle',
				'number',
				'archivelink',
				'archivelink_text',
				'chartid'
		);
		foreach ($attarray as $a){
			if(!array_key_exists($a, $instance)){
				$instance[$a] = '';
			}
		}


		echo $before_widget;

		if(array_key_exists("title",$instance)){
			echo $before_title.$instance['title'].$after_title; 
		}
		

		$chartid = '';
		if(array_key_exists('chartid', $instance)) {
			$chartid = $instance['chartid'];
		}

		

		if(!is_numeric($chartid)){
			$args = array(
				'post_type' => 'chart',
				'posts_per_page' => 1,
				'post_status' => 'publish',
		 		'orderby' => 'date',
		 	 	'order'   => 'DESC'
			);
		} else {
			$args = array('p' => $chartid,
						'post_type' => 'chart' );
		}
		

		$the_query_meta = new WP_Query( $args );
		global $post;
		$id_of_currentday = 0;
		while ( $the_query_meta->have_posts() && $id_of_currentday == 0 ):
			$the_query_meta->the_post();
			setup_postdata( $post );
			$permalink = get_the_permalink();
			$quantity = 10;
			if(array_key_exists('number', $instance)) {
				$quantity = $instance['number'];
			}
			$post->tracksquantity = $quantity;

			$showtitle = 'true';
			if(array_key_exists('showtitle', $instance)) {
				$showtitle = $instance['showtitle'];
			}

			if($showtitle == 'true'){
				?>
				<h5><?php the_title(); ?></h5>
				<?php
			}
			if($instance['showcover'] == 'true'){
				the_post_thumbnail( 'large', array("class"=>"img-responsive") );
			}

			get_template_part ('part-chart-compact');
		

			if($instance['archivelink'] == 'show'){
				if($instance['archivelink_text']==''){$instance['archivelink_text'] = esc_attr__('See all',"qt-extensions-suite");};
		 	 	echo '
		 	 	<a href="'.esc_attr($permalink).'" class="btn btn-large waves-effect waves-light accentcolor qt-fullwidth"><i class="icon-chevron-right animated"></i> <h5>'.esc_attr($instance['archivelink_text']).'</h5></a>
		 	 	';
		 	} 
		
		endwhile;



		wp_reset_postdata();
		wp_reset_query();
		echo $after_widget;
	}

	//Update the widget 
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		//Strip tags from title and name to remove HTML 

		$attarray = array(
				'title',
				'showcover',
				'showtitle',
				'number',
				'archivelink',
				'archivelink_text',
				'chartid'
		);
		foreach ($attarray as $a){
			$instance[$a] = strip_tags( $new_instance[$a] );
		}
		return $instance;
	}

	public function form( $instance ) {
		//Set up some default widget settings.

		$defaults = array( 'title' => esc_attr__('Chart', "qt-extensions-suite"),
							'showcover'=> 'true',
							'showtitle'=> 'true',
							'number'=> '5',
							'archivelink'=> 'show',
							'chartid'=> '',
							'archivelink_text'=> esc_attr__('See all',"qt-extensions-suite")
							);


		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
	 	<h2>General options</h2>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php echo esc_attr__('Title:', "qt-extensions-suite"); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>
		 <p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php echo esc_attr__('Number of tracks to display:', "qt-extensions-suite"); ?></label>
			<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" value="<?php echo $instance['number']; ?>" style="width:100%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'showtitle' ); ?>"><?php echo esc_attr__('Show original chart title', "qt-extensions-suite"); ?></label><br />			
           True   <input type="radio" name="<?php echo $this->get_field_name( 'showtitle' ); ?>" value="true" <?php if($instance['showtitle'] == 'true'){ echo ' checked= "checked" '; } ?> />  
           False  <input type="radio" name="<?php echo $this->get_field_name( 'showtitle' ); ?>" value="false" <?php if($instance['showtitle'] == 'false'){ echo ' checked= "checked" '; } ?> />  
		</p> 
      	<p>
			<label for="<?php echo $this->get_field_id( 'showcover' ); ?>"><?php echo esc_attr__('Show cover', "qt-extensions-suite"); ?></label><br />			
           True   <input type="radio" name="<?php echo $this->get_field_name( 'showcover' ); ?>" value="true" <?php if($instance['showcover'] == 'true'){ echo ' checked= "checked" '; } ?> />  
           False  <input type="radio" name="<?php echo $this->get_field_name( 'showcover' ); ?>" value="false" <?php if($instance['showcover'] == 'false'){ echo ' checked= "checked" '; } ?> />  
		</p> 
		<p>
			<label for="<?php echo $this->get_field_id( 'chartid' ); ?>"><?php echo esc_attr__('Chart id: if empty latest chart will be used', "qt-extensions-suite"); ?></label>
			<input id="<?php echo $this->get_field_id( 'chartid' ); ?>" name="<?php echo $this->get_field_name( 'chartid' ); ?>" value="<?php echo $instance['chartid']; ?>" style="width:100%;" />
		</p> 
		<p>
			<label for="<?php echo $this->get_field_id( 'archivelink' ); ?>"><?php echo esc_attr__('Show link to chart page', "qt-extensions-suite"); ?></label><br />			
           Show   <input type="radio" name="<?php echo $this->get_field_name( 'archivelink' ); ?>" value="show" <?php if($instance['archivelink'] == 'show'){ echo ' checked= "checked" '; } ?> />  
           Hide  <input type="radio" name="<?php echo $this->get_field_name( 'archivelink' ); ?>" value="hide" <?php if($instance['archivelink'] == 'hide'){ echo ' checked= "checked" '; } ?> />  
		</p>
        <p>
			<label for="<?php echo $this->get_field_id( 'archivelink_text' ); ?>"><?php echo esc_attr__('Link text:', "qt-extensions-suite"); ?></label>
			<input id="<?php echo $this->get_field_id( 'archivelink_text' ); ?>" name="<?php echo $this->get_field_name( 'archivelink_text' ); ?>" value="<?php echo $instance['archivelink_text']; ?>" style="width:100%;" />
		</p> 
		

	<?php

	}
}

