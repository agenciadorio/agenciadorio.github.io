<?php

class finance_socialbox extends WP_Widget
{
  function finance_socialbox(){
    $widget_ops = array('classname' => 'socialbox-widget', 'description' => '');

		$control_ops = array('id_base' => 'social_box-widget');

		parent::__construct('social_box-widget', 'Finance Social Box', $widget_ops, $control_ops);
  }
 
  function form($instance){

    $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
    

    $title 			= !empty($instance['title']) ? $instance['title'] : '';
    $facebook 		= !empty($instance['facebook']) ? $instance['facebook'] : '';
    $twitter 		= !empty($instance['twitter']) ? $instance['twitter'] : '';
    $googleplus 	= !empty($instance['googleplus']) ? $instance['googleplus'] : '';
    $linkedin 		= !empty($instance['linkedin']) ? $instance['linkedin'] : '';
    $youtube 		= !empty($instance['youtube']) ? $instance['youtube'] : '';
    $pinterest 		= !empty($instance['pinterest']) ? $instance['pinterest'] : '';
    $dribbble 		= !empty($instance['dribbble']) ? $instance['dribbble'] : '';
    $instagram 		= !empty($instance['instagram']) ? $instance['instagram'] : '';
	?>
	<p>
		<label for="<?php echo sanitize_text_field( $this->get_field_id('title') ); ?>"><?php esc_html_e( 'Title', 'finance' ); ?></label><br>
		<input style="width:216px" id="<?php echo sanitize_text_field( $this->get_field_id('title') ); ?>" name="<?php echo sanitize_text_field( $this->get_field_name('title') ); ?>" value="<?php echo sanitize_text_field( $title ); ?>">
	</p>
	<hr style="border:none;height:1px;background:#BFBFBF">
	<p><?php esc_html_e( 'Insert the URLs to your social networks', 'finance' ); ?></p>
	<p>
		<label for="<?php echo sanitize_text_field( $this->get_field_id('facebook') ); ?>"><?php esc_html_e( 'Facebook', 'finance' ); ?></label><br>
		<input style="width:216px" id="<?php echo sanitize_text_field( $this->get_field_id('facebook') ); ?>" name="<?php echo sanitize_text_field( $this->get_field_name('facebook') ); ?>" value="<?php echo sanitize_text_field( $facebook ); ?>">
	</p>
	<p>
		<label for="<?php echo sanitize_text_field( $this->get_field_id('twitter') ); ?>"><?php esc_html_e( 'Twitter', 'finance' ); ?></label><br>
		<input style="width:216px" id="<?php echo sanitize_text_field( $this->get_field_id('twitter') ); ?>" name="<?php echo sanitize_text_field( $this->get_field_name('twitter')); ?>" value="<?php echo sanitize_text_field( $twitter ); ?>">
	</p>
	<p>
		<label for="<?php echo sanitize_text_field( $this->get_field_id('googleplus') ); ?>"><?php esc_html_e( 'Google+', 'finance' ); ?></label><br>
		<input style="width:216px" id="<?php echo sanitize_text_field( $this->get_field_id('googleplus') ); ?>" name="<?php echo sanitize_text_field( $this->get_field_name('googleplus') ); ?>" value="<?php echo sanitize_text_field( $googleplus ); ?>">
	</p>
	<p>
		<label for="<?php echo sanitize_text_field( $this->get_field_id('linkedin') ); ?>"><?php esc_html_e( 'Linkedin', 'finance' ); ?></label><br>
		<input style="width:216px" id="<?php echo sanitize_text_field( $this->get_field_id('linkedin') ); ?>" name="<?php echo sanitize_text_field( $this->get_field_name('linkedin') ); ?>" value="<?php echo sanitize_text_field( $linkedin ); ?>">
	</p>
	<p>
		<label for="<?php echo sanitize_text_field( $this->get_field_id('youtube') ); ?>"><?php esc_html_e( 'Youtube', 'finance' ); ?></label><br>
		<input style="width:216px" id="<?php echo sanitize_text_field( $this->get_field_id('youtube') ); ?>" name="<?php echo sanitize_text_field( $this->get_field_name('youtube') ); ?>" value="<?php echo sanitize_text_field( $youtube ); ?>">
	</p>

	<p>
		<label for="<?php echo sanitize_text_field( $this->get_field_id('pinterest') ); ?>"><?php esc_html_e( 'Pinterest', 'finance' ); ?></label><br>
		<input style="width:216px" id="<?php echo sanitize_text_field( $this->get_field_id('pinterest') ); ?>" name="<?php echo sanitize_text_field( $this->get_field_name('pinterest') ); ?>" value="<?php echo sanitize_text_field( $pinterest ); ?>">
	</p>
	<p>
		<label for="<?php echo sanitize_text_field( $this->get_field_id('dribbble') ); ?>"><?php esc_html_e( 'Dribbble', 'finance' ); ?></label><br>
		<input style="width:216px" id="<?php echo sanitize_text_field( $this->get_field_id('dribbble') ); ?>" name="<?php echo sanitize_text_field( $this->get_field_name('dribbble') ); ?>" value="<?php echo sanitize_text_field( $dribbble ); ?>">
	</p>
	<p>
		<label for="<?php echo sanitize_text_field( $this->get_field_id('instagram') ); ?>"><?php esc_html_e( 'Instagram', 'finance' ); ?></label><br>
		<input style="width:216px" id="<?php echo sanitize_text_field( $this->get_field_id('instagram') ); ?>" name="<?php echo sanitize_text_field( $this->get_field_name('instagram') ); ?>" value="<?php echo sanitize_text_field( $instagram ); ?>">
	</p>


	<?php
  }
 
  function update($new_instance, $old_instance){

    $instance = $old_instance;
    $instance['title'] 			= strip_tags($new_instance['title']);
    $instance['facebook'] 		= esc_url($new_instance['facebook']);
    $instance['twitter'] 		= esc_url($new_instance['twitter']);
    $instance['googleplus'] 	= esc_url($new_instance['googleplus']);
    $instance['linkedin'] 		= esc_url($new_instance['linkedin']);
    $instance['youtube'] 		= esc_url($new_instance['youtube']);
    $instance['pinterest'] 		= esc_url($new_instance['pinterest']);
    $instance['dribbble'] 		= esc_url($new_instance['dribbble']);
    $instance['youtube'] 		= esc_url($new_instance['youtube']);
    $instance['instagram'] 		= esc_url($new_instance['instagram']);
    return $instance;
  }
 
  function widget($args, $instance){

    extract($args, EXTR_SKIP);

    echo balancetags( $before_widget ); 
    ?>
  		<?php
  		if($instance['title']) {
			echo  balancetags( $before_title ). sanitize_text_field( $instance['title'] ).balancetags( $after_title );
		} ?>

    		<ul class="clearfix">
	    	<?php echo !empty($instance['facebook']) 	? '<li><a target="_blank" data-title="Facebook" class="socialbox-item facebook" href="'.esc_url( $instance['facebook'] ).'"><i class="icon-facebook"></i></a></li>' : '' ?>
	    	<?php echo !empty($instance['twitter']) 	? '<li><a target="_blank" data-title="Twitter" class="socialbox-item twitter" href="'.esc_url( $instance['twitter'] ).'"><i class="icon-twitter"></i></a></li>' : '' ?>
	    	<?php echo !empty($instance['googleplus']) 	? '<li><a target="_blank" data-title="Google+" class="socialbox-item googleplus" href="'.esc_url( $instance['googleplus'] ).'"><i class="icon-google-plus"></i></a></li>' : '' ?>
	    	<?php echo !empty($instance['linkedin']) 	? '<li><a target="_blank" data-title="Linkedin" class="socialbox-item linkedin" href="'.esc_url( $instance['linkedin'] ).'"><i class="icon-linkedin"></i></a></li>' : '' ?>
	    	<?php echo !empty($instance['pinterest']) 	? '<li><a target="_blank" data-title="Pinterest" class="socialbox-item pinterest" href="'.esc_url( $instance['pinterest'] ).'"><i class="icon-pinterest"></i></a></li>' : '' ?>
	    	<?php echo !empty($instance['dribbble']) 	? '<li><a target="_blank" data-title="Dribbble" class="socialbox-item dribbble" href="'.esc_url( $instance['dribbble'] ).'"><i class="icon-dribbble"></i></a></li>' : '' ?>
	    	<?php echo !empty($instance['youtube']) 	? '<li><a target="_blank" data-title="Youtube" class="socialbox-item youtube" href="'.esc_url( $instance['youtube'] ).'"><i class="icon-youtube"></i></a></li>' : '' ?>
	    	<?php echo !empty($instance['instagram']) 	? '<li><a target="_blank" data-title="Youtube" class="socialbox-item instagram" href="'.esc_url( $instance['instagram'] ).'"><i class="icon-instagram"></i></a></li>' : '' ?>

    		</ul>

    <?php
	echo balancetags( $after_widget );
  }
}
add_action( 'widgets_init', create_function('', 'return register_widget("finance_socialbox");') );