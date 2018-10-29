<?php

if ( ! class_exists( 'Better_Menu_Widget' ) ) {

	add_action( 'widgets_init', 'load_better_menu_widget' );

	function load_better_menu_widget() {
		register_widget( 'Better_Menu_Widget' );
	}

	class Better_Menu_Widget extends WP_Widget {

		function __construct() {

			$widget_details = array(
				'classname'   => 'better-menu-widget',
				'description' => 'Add one of your custom menus as a widget.'
			);

			parent::__construct( 'better-menu-widget', __( 'ThemeMove Menu', 'infinity' ), $widget_details );

		}

		function widget( $args, $instance ) {

			$nav_menu = wp_get_nav_menu_object( $instance['nav_menu'] ); // Get menu

			if ( ! $nav_menu ) {
				return;
			}

			$instance['title'] = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

			echo $args['before_widget'];

			if ( ! empty( $instance['title'] ) && ! empty( $instance['title_url'] ) ) {
				echo $args['before_title'] . '<a href="' . esc_url( $instance['title_url'] ) . '">' . esc_html( $instance['title'] ) . '</a>' . $args['after_title'];
			}

			if ( ! empty( $instance['title'] ) && empty( $instance['title_url'] ) ) {
				echo $args['before_title'] . esc_html( $instance['title'] ) . $args['after_title'];
			}

			wp_nav_menu( array( 'fallback_cb' => '', 'menu' => $nav_menu, ) );

			echo $args['after_widget'];
		}

		// widget admin

		function update( $new_instance, $old_instance ) {
			$instance['title']    = sanitize_text_field( $new_instance['title'] );
			$instance['nav_menu'] = $new_instance['nav_menu'];

			return $instance;
		}

		function form( $instance ) {
			$title    = isset( $instance['title'] ) ? $instance['title'] : '';
			$nav_menu = isset( $instance['nav_menu'] ) ? $instance['nav_menu'] : '';

			// Get menus
			$menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );

			// If no menus exists, direct the user to create some.
			if ( ! $menus ) {
				echo '<p>' . sprintf( __( 'No menus have been created yet. <a href="%s">Create some</a>.', 'better-menu-widget' ), admin_url( 'nav-menus.php' ) ) . '</p>';

				return;
			}
			?>
			<p><label
					for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'better-menu-widget' ) ?></label><input
					type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
					name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_html( $title ); ?>"/>
			</p>
			<p><label
					for="<?php echo $this->get_field_id( 'nav_menu' ); ?>"><?php _e( 'Select Menu:', 'better-menu-widget' ); ?></label>
				<select id="<?php echo $this->get_field_id( 'nav_menu' ); ?>"
				        name="<?php echo $this->get_field_name( 'nav_menu' ); ?>">
					<?php
					foreach ( $menus as $menu ) {
						$selected = $nav_menu == $menu->slug ? ' selected="selected"' : '';
						echo '<option' . $selected . ' value="' . $menu->slug . '">' . $menu->name . '</option>';
					}
					?>
				</select></p>
			<?php
		}

	} // end class

} // end if
?>