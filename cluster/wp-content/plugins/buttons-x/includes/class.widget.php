<?php

/**
 * Button X
 *
 * This file is used to add Buttons X widget.
 *
 * @package Buttons X
 * @since 0.1
 */

// Make sure we don't expose any info if called directly
if ( !defined( 'ABSPATH' ) )
	exit;

class Btnsx_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'btnsx_widget', // Base ID
			__( 'Buttons X', 'buttons-x' ), // Name
			array( 'description' => __( 'Beautiful buttons.', 'buttons-x' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', isset( $instance['title'] ) ? $instance['title'] : '' );
		$select = isset( $instance['select'] ) ? $instance['select'] : '';

		echo $args['before_widget'];
		if ( ! empty( $title ) ){
			echo $args['before_title'] . $title . $args['after_title'];
		}
		if ( ! empty( $select ) ){
			echo do_shortcode('[btnsx id="' . $select . '"]');
		}
		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'Buttons X', 'buttons-x' );
		}
		if ( isset( $instance[ 'select' ] ) ) {
			$select = esc_attr($instance['select']); // Added
		}
		else {
			$select = '';
		}
		?>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'buttons-x' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'select' ) ); ?>"><?php _e( 'Button:', 'buttons-x' ); ?></label>
			<?php 

				global $wpdb;
				$btnsx_post = 'buttons-x';
				$btnsx_post_status = 'publish';
		        $btnsx = $wpdb->get_results( $wpdb->prepare(
	                "SELECT ID, post_title
	                    FROM $wpdb->posts 
	                    WHERE $wpdb->posts.post_type = %s
	                    AND $wpdb->posts.post_status = %s
	                    ORDER BY ID DESC",
	                $btnsx_post,
	                $btnsx_post_status
	            ) );

		    ?>

		   <select id="<?php echo esc_attr( $this->get_field_id( 'select' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'select' ) ); ?>"  class="widefat">

			<?php 

				if( $btnsx == false ){
			?>

				<option value=""><?php _e( 'No Buttons Found!', 'buttons-x' ); ?></option>

			<?php
				} else {

			?>

				<option value=""><?php _e( 'None', 'buttons-x' ); ?></option>

			<?php

					foreach ( $btnsx as $btn ) {
						$btn_id 	= $btn->ID;
						$btn_name 	= $btn->post_title;
						// $btn_type 	= $btn->name;

						echo '<option value="' . esc_attr( $btn_id ) . '" id="' . esc_attr( $btn_name ) . '"', $select == $btn_id ? ' selected="selected"' : '', '>', sanitize_text_field( $btn_name ), '</option>';

			} } ?>

			</select>

		</p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['select'] = ( ! empty( $new_instance['select'] ) ) ? strip_tags( $new_instance['select'] ) : '';
		return $instance;
	}

} // class btnsx_Widget

function btnsx_widget() {
	register_widget( 'btnsx_widget' );
}
add_action( 'widgets_init', 'btnsx_widget' );