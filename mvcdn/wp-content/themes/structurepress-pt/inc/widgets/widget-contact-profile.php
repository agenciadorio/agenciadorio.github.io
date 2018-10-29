<?php
/**
 * Contact Profile Widget
 *
 */


if ( ! class_exists( 'PW_Contact_Profile' ) ) {
	class PW_Contact_Profile extends WP_Widget {

		private $current_widget_id;
		private $font_awesome_icons_list;
		private $font_awesome_social_icons_list;

		// Basic widget settings
		function widget_id_base() { return 'contact_profile'; }
		function widget_name() { return esc_html__( 'Contact Profile', 'structurepress-pt' ); }
		function widget_description() { return esc_html__( 'Widget displaying contact info.', 'structurepress-pt' ); }
		function widget_class() { return 'widget-contact-profile'; }

		/**
		 * Register widget with WordPress.
		 */
		public function __construct() {
			parent::__construct(
				'pw_' . $this->widget_id_base(),
				sprintf( 'ProteusThemes: %s', $this->widget_name() ), // Name
				array(
					'description' => $this->widget_description(),
					'classname'   => $this->widget_class(),
				)
			);
			// A list of icons to choose from in the widget backend
			$this->font_awesome_icons_list = apply_filters(
				'pw/contact_profile_fa_icons_list',
				array(
					'fa-home',
					'fa-phone',
					'fa-envelope-o',
					'fa-envelope',
					'fa-map-marker',
					'fa-users',
					'fa-female',
					'fa-male',
					'fa-inbox',
					'fa-compass',
					'fa-laptop',
					'fa-money',
					'fa-suitcase',
					'fa-warning',
				)
			);

			// A list of icons to choose from in the widget backend for social icons
			$this->font_awesome_social_icons_list = apply_filters(
				'pw/social_icons_fa_icons_list',
				array(
					'fa-facebook',
					'fa-twitter',
					'fa-youtube',
					'fa-skype',
					'fa-google-plus',
					'fa-pinterest',
					'fa-instagram',
					'fa-vine',
					'fa-tumblr',
					'fa-foursquare',
					'fa-xing',
					'fa-flickr',
					'fa-vimeo',
					'fa-linkedin',
					'fa-dribble',
					'fa-wordpress',
					'fa-rss',
					'fa-github',
					'fa-bitbucket',
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
			// Prepare data
			$items               = isset( $instance['items'] ) ? $instance['items'] : array();
			$social_icons        = isset( $instance['social_icons'] ) ? $instance['social_icons'] : array();
			$instance['new_tab'] = ! empty ( $instance['new_tab'] ) ? '_blank' : '_self';

			echo $args['before_widget'];
			?>
				<div class="card  contact-profile">
					<?php if ( ! empty( $instance['image'] ) ) : ?>
						<img class="contact-profile__image  wp-post-image" src="<?php echo esc_url( $instance['image'] ); ?>" alt="<?php printf( '%s %s', esc_html__( 'Picture of', 'structurepress-pt' ), $instance['name'] ); ?>">
					<?php endif; ?>
					<div class="card-block  contact-profile__container">
						<?php if ( ! empty( $social_icons ) ) : ?>
							<div class="contact-profile__social-icons">
								<?php foreach ( $social_icons as $social_icon ) : ?>
									<a class="contact-profile__social-icon" href="<?php echo esc_url( $social_icon['link'] ); ?>" target="<?php echo esc_attr( $instance['new_tab'] ); ?>"><i class="fa  <?php echo esc_attr( $social_icon['icon'] ); ?>"></i></a>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
						<?php if ( ! empty( $items ) ) : ?>
							<div class="contact-profile__items">
								<?php foreach ( $items as $item ) : ?>
									<div class="contact-profile__item">
										<div class="contact-profile__icon">
											<i class="fa  <?php echo esc_attr( $item['icon'] ); ?>"></i>
										</div>
										<p class="contact-profile__text">
											<?php echo wp_kses_post( $item['text'] ); ?>
										</p>
									</div>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
						<div class="contact-profile__content">
							<?php if ( ! empty( $instance['name'] ) ) : ?>
								<span class="contact-profile__name"><?php echo esc_html( $instance['name'] ); ?></span>
							<?php endif; ?>
						</div>
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

			$instance['name']    = sanitize_text_field( $new_instance['name'] );
			$instance['image']   = sanitize_text_field( $new_instance['image'] );
			$instance['new_tab'] = ! empty ( $new_instance['new_tab'] ) ? sanitize_key( $new_instance['new_tab'] ) : '';

			if ( ! empty( $new_instance['social_icons'] )  ) {
				foreach ( $new_instance['social_icons'] as $key => $social_icon ) {
					$instance['social_icons'][ $key ]['id']   = sanitize_key( $social_icon['id'] );
					$instance['social_icons'][ $key ]['icon'] = sanitize_html_class( $social_icon['icon'] );
					$instance['social_icons'][ $key ]['link'] = esc_url_raw( $social_icon['link'] );
				}
			}

			if ( ! empty( $new_instance['items'] )  ) {
				foreach ( $new_instance['items'] as $key => $item ) {
					$instance['items'][ $key ]['id']   = sanitize_key( $item['id'] );
					$instance['items'][ $key ]['icon'] = sanitize_html_class( $item['icon'] );
					$instance['items'][ $key ]['text'] = wp_kses_post( $item['text'] );
				}
			}

			return $instance;
		}

		/**
		 * Back-end widget form.
		 *
		 * @param array $instance The widget options
		 */
		public function form( $instance ) {

			$name         = empty( $instance['name'] ) ? '' : $instance['name'];
			$image        = empty( $instance['image'] ) ? '' : $instance['image'];
			$items        = isset( $instance['items'] ) ? $instance['items'] : array();
			$social_icons = isset( $instance['social_icons'] ) ? $instance['social_icons'] : array();
			$new_tab      = empty( $instance['new_tab'] ) ? '' : $instance['new_tab'];

			// Page Builder fix when using repeating fields
			if ( 'temp' === $this->id ) {
				$this->current_widget_id = $this->number;
			}
			else {
				$this->current_widget_id = $this->id;
			}

			?>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'name' ) ); ?>"><?php _ex( 'Name:', 'backend', 'structurepress-pt' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'name' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'name' ) ); ?>" type="text" value="<?php echo esc_attr( $name ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>"><?php _ex( 'Picture URL:', 'backend', 'structurepress-pt' ); ?></label>
				<input class="widefat" style="margin-bottom: 6px;" id="<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'image' ) ); ?>" type="text" value="<?php echo esc_attr( $image ); ?>" />
				<input type="button" onclick="ProteusWidgetsUploader.imageUploader.openFileFrame('<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>');" class="button button-secondary" value="Upload Image" />
			</p>

			<p>
				<input class="checkbox" type="checkbox" <?php checked( $new_tab, 'on' ); ?> id="<?php echo esc_attr( $this->get_field_id( 'new_tab' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'new_tab' ) ); ?>" value="on" />
				<label for="<?php echo esc_attr( $this->get_field_id( 'new_tab' ) ); ?>"><?php _ex( 'Open social links in new tab', 'backend', 'structurepress-pt' ); ?></label>
			</p>

			<hr>

			<h3><?php esc_html_e( 'Items:', 'structurepress-pt' ); ?></h3>

			<script type="text/template" id="js-pt-contact-profile-item-<?php echo esc_attr( $this->current_widget_id ); ?>">
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'items' ) ); ?>-{{id}}-text"><?php _ex( 'Text:', 'backend', 'structurepress-pt' ); ?></label>
					<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'items' ) ); ?>-{{id}}-text" name="<?php echo esc_attr( $this->get_field_name( 'items' ) ); ?>[{{id}}][text]" type="text" value="{{text}}" />
				</p>

				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'items' ) ); ?>-{{id}}-icon"><?php _ex( 'Select icon:', 'backend', 'structurepress-pt' ); ?></label> <br />
					<small><?php printf( esc_html__( 'Click on the icon below or manually select from the %s website', 'structurepress-pt' ), '<a href="http://fontawesome.io/icons/" target="_blank">FontAwesome</a>' ); ?>.</small>
					<input id="<?php echo esc_attr( $this->get_field_id( 'items' ) ); ?>-{{id}}-icon" name="<?php echo esc_attr( $this->get_field_name( 'items' ) ); ?>[{{id}}][icon]" type="text" value="{{icon}}" class="widefat  js-icon-input" /> <br><br>
					<?php foreach ( $this->font_awesome_icons_list as $icon ) : ?>
						<a class="js-selectable-icon  icon-widget" href="#" data-iconname="<?php echo esc_attr( $icon ); ?>"><i class="fa fa-lg <?php echo esc_attr( $icon ); ?>"></i></a>
					<?php endforeach; ?>
				</p>

				<p>
					<input name="<?php echo esc_attr( $this->get_field_name( 'items' ) ); ?>[{{id}}][id]" type="hidden" value="{{id}}" />
					<a href="#" class="pt-remove-contact-profile-item  js-pt-remove-contact-profile-item"><span class="dashicons dashicons-dismiss"></span> <?php _ex( 'Remove item', 'backend', 'structurepress-pt' ); ?></a>
				</p>
			</script>
			<div class="pt-widget-contact-profile-items" id="contact-profile-items-<?php echo esc_attr( $this->current_widget_id ); ?>">
				<div class="contact-profile-items"></div>
				<p>
					<a href="#" class="button  js-pt-add-contact-profile-item"><?php _ex( 'Add New Item', 'backend', 'structurepress-pt' ); ?></a>
				</p>
			</div>

			<hr>

			<h3><?php esc_html_e( 'Social Icons:', 'structurepress-pt' ); ?></h3>

			<script type="text/template" id="js-pt-social-icon-<?php echo esc_attr( $this->current_widget_id ); ?>">
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'social_icons' ) ); ?>-{{id}}-link"><?php _ex( 'Link:', 'backend', 'structurepress-pt' ); ?></label>
					<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'social_icons' ) ); ?>-{{id}}-link" name="<?php echo esc_attr( $this->get_field_name( 'social_icons' ) ); ?>[{{id}}][link]" type="text" value="{{link}}" />
				</p>

				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'social_icons' ) ); ?>-{{id}}-icon"><?php _ex( 'Select social icon:', 'backend', 'structurepress-pt' ); ?></label> <br />
					<small><?php printf( esc_html__( 'Click on the icon below or manually select from the %s website', 'structurepress-pt' ), '<a href="http://fontawesome.io/icons/" target="_blank">FontAwesome</a>' ); ?>.</small>
					<input id="<?php echo esc_attr( $this->get_field_id( 'social_icons' ) ); ?>-{{id}}-icon" name="<?php echo esc_attr( $this->get_field_name( 'social_icons' ) ); ?>[{{id}}][icon]" type="text" value="{{icon}}" class="widefat  js-icon-input" /> <br><br>
					<?php foreach ( $this->font_awesome_social_icons_list as $icon ) : ?>
						<a class="js-selectable-icon  icon-widget" href="#" data-iconname="<?php echo esc_attr( $icon ); ?>"><i class="fa fa-lg <?php echo esc_attr( $icon ); ?>"></i></a>
					<?php endforeach; ?>
				</p>

				<p>
					<input name="<?php echo esc_attr( $this->get_field_name( 'social_icons' ) ); ?>[{{id}}][id]" type="hidden" value="{{id}}" />
					<a href="#" class="pt-remove-social-icon  js-pt-remove-social-icon"><span class="dashicons dashicons-dismiss"></span> <?php _ex( 'Remove social icon', 'backend', 'structurepress-pt' ); ?></a>
				</p>
			</script>
			<div class="pt-widget-social-icons" id="social-icons-<?php echo esc_attr( $this->current_widget_id ); ?>">
				<div class="social-icons"></div>
				<p>
					<a href="#" class="button  js-pt-add-social-icon"><?php _ex( 'Add New Social Icon', 'backend', 'structurepress-pt' ); ?></a>
				</p>
			</div>

			<script type="text/javascript">
				(function() {
					// repopulate the form - items
					var contactProfileItemJSON = <?php echo wp_json_encode( $items ) ?>;

					// get the right widget id and remove the added < > characters at the start and at the end.
					var widgetId = '<<?php echo esc_js( $this->current_widget_id ); ?>>'.slice( 1, -1 );

					if ( _.isFunction( StructurePress.Utils.repopulateContactProfileItems ) ) {
						StructurePress.Utils.repopulateContactProfileItems( contactProfileItemJSON, widgetId );
					}

					// repopulate the form - social icons
					var socialIconsJSON = <?php echo wp_json_encode( $social_icons ) ?>;

					if ( _.isFunction( ProteusWidgets.Utils.repopulateSocialIcons ) ) {
						ProteusWidgets.Utils.repopulateSocialIcons( socialIconsJSON, widgetId );
					}
				})();
			</script>

			<?php
		}

	}
	register_widget( 'PW_Contact_Profile' );
}