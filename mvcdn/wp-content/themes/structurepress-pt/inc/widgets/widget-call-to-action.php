<?php
/**
 * Call to Action Widget
 */

if ( ! class_exists( 'PW_Call_To_Action' ) ) {
	class PW_Call_To_Action extends WP_Widget {

		// Basic widget settings
		function widget_id_base() { return 'call_to_action'; }
		function widget_name() { return esc_html__( 'Call to Action', 'structurepress-pt' ); }
		function widget_description() { return esc_html__( 'Call to Action widget for Page Builder.', 'structurepress-pt' ); }
		function widget_class() { return 'widget-call-to-action'; }

		public function __construct() {
			parent::__construct(
				'pw_' . $this->widget_id_base(),
				sprintf( 'ProteusThemes: %s', $this->widget_name() ), // Name
				array(
					'description' => $this->widget_description(),
					'classname'   => $this->widget_class(),
				)
			);
		}

		/**
		 * Front-end display of widget.
		 *
		 * @see WP_Widget::widget()
		 *
		 * @param array $args
		 * @param array $instance
		 */
		public function widget( $args, $instance ) {
			echo $args['before_widget'];
			?>
				<div class="call-to-action">
					<div class="call-to-action__text">
						<h4 class="call-to-action__title">
							<?php echo esc_html( apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) ); ?>
						</h4>
						<?php if ( ! empty( $instance['subtitle'] ) ) : ?>
						<p class="call-to-action__subtitle">
							<?php echo esc_html( $instance['subtitle'] ); ?>
						</p>
						<?php endif; ?>
					</div>
					<div class="call-to-action__button">
						<?php echo do_shortcode( $instance['button_text'] ); ?>
					</div>
				</div>
			<?php
			echo $args['after_widget'];
		}

		/**
		 * Sanitize widget form values as they are saved.
		 *
		 * @param array $new_instance The new options
		 * @param array $old_instance The previous options
		 */
		public function update( $new_instance, $old_instance ) {
			$instance = array();

			$instance['title']       = wp_kses_post( $new_instance['title'] );
			$instance['subtitle']    = wp_kses_post( $new_instance['subtitle'] );
			$instance['button_text'] = wp_kses_post( $new_instance['button_text'] );

			return $instance;
		}

		/**
		 * Back-end widget form.
		 *
		 * @param array $instance The widget options
		 */
		public function form( $instance ) {
			$title       = ! empty( $instance['title'] ) ? $instance['title'] : '';
			$subtitle    = ! empty( $instance['subtitle'] ) ? $instance['subtitle'] : '';
			$button_text = ! empty( $instance['button_text'] ) ? $instance['button_text'] : '';
			?>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _ex( 'Title:', 'backend', 'structurepress-pt' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'subtitle' ) ); ?>"><?php _ex( 'Subtitle:', 'backend', 'structurepress-pt' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'subtitle' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'subtitle' ) ); ?>" type="text" value="<?php echo esc_attr( $subtitle ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>"><?php _ex( 'Button Area:', 'backend', 'structurepress-pt' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'button_text' ) ); ?>" type="text" value="<?php echo esc_attr( $button_text ); ?>" /><br><br>
				<span class="button-shortcodes">
					<?php printf( _x( 'Input a button shortcode in the above field. Please take a look at the <a href="%1$s" target="_blank">Buttons Shortcode documentation</a> to learn more on how to write button shortcodes.', 'backend', 'structurepress-pt' ), 'https://www.proteusthemes.com/docs/structurepress-pt/#buttons' ); ?><br>
				</span>
			</p>

			<?php
		}

	}
	register_widget( 'PW_Call_To_Action' );
}