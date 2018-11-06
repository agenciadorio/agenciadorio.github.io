<?php
/**
* Main Class for Responsive Posts Carousel
*/
class WCP_Responsive_Posts_Carousel
{
	
	function __construct()
	{
		add_action( 'init', array($this, 'register_carousels') );
		add_action( 'add_meta_boxes', array($this, 'carousel_metaboxes' ), 10, 2 );
		add_action( 'admin_enqueue_scripts', array($this, 'admin_scripts' ) );
		add_shortcode( 'wcp-carousel', array($this, 'render_shortcode') );
		add_action( 'wp_ajax_rpc_get_posts', array($this, 'rpc_get_posts') );
		add_action( 'wp_ajax_rpc_get_terms', array($this, 'rpc_get_terms') );
		add_action( 'save_post', array($this, 'save_carousel' ) );

		add_action( 'rpc_carousel_thumbnail', array($this, 'render_carousel_thumbnail' ), 10, 4 );
		add_action( 'rpc_carousel_title', array($this, 'render_carousel_title' ), 10, 2 );
		add_action( 'rpc_carousel_desc', array($this, 'render_carousel_desc' ), 10, 2 );
		add_action( 'rpc_read_more_btn', array($this, 'render_read_more_btn' ), 10, 2 );

        add_filter('manage_wcp_carousel_posts_columns', array($this, 'wcp_carousel_column_head'));
        add_action('manage_wcp_carousel_posts_custom_column', array($this, 'wcp_carousel_column_content'), 10, 2);		
        add_action( 'plugins_loaded', array($this, 'wcp_load_plugin_textdomain' ) );
	}

	/**
	 * Register a carousels post type.
	 * since 1.0
	 */
	function register_carousels() {
		include RPC_PATH.'/inc/register-carousel.php';
	}

	/**
	 * Returns carousel settings fields.
	 * since 1.0
	 */
	function rpc_settings_fields() {
		$fields = array();
		include RPC_PATH.'/inc/settings-fields.php';
		return $fields;
	}

    function wcp_load_plugin_textdomain(){
        load_plugin_textdomain( 'responsive-posts-carousel', FALSE, basename( RPC_PATH ) . '/languages/' );
    }

	/**
	 * Returns carousel settings tabs.
	 * since 1.0
	 */
	function rpc_settings_tabs() {
		$tabs = array(
			'contents' => __( 'Contents', 'responsive-posts-carousel' ),
			'slider' => __( 'Slider', 'responsive-posts-carousel' ),
			'colors' => __( 'Colors', 'responsive-posts-carousel' ),
			'typography' => __( 'Typography', 'responsive-posts-carousel' ),
			'arrows' => __( 'Arrows', 'responsive-posts-carousel' ),
			'advanced' => __( 'Advanced', 'responsive-posts-carousel' ),
		);
		
		return $tabs;
	}

	function carousel_metaboxes( $post_type, $post ) {
	    add_meta_box( 'rpc-settings', 'Carousel Settings', array($this, 'render_carousel_settings'), 'wcp_carousel', 'normal');
	    add_meta_box( 'wcp-shortcode', 'Shortcode', array($this, 'render_sc_box'), 'wcp_carousel', 'side');
	    add_meta_box( 'wcp-help', 'Pro Version / Help', array($this, 'render_help_box'), 'wcp_carousel', 'side');
	}

	/**
	 * Renders Carousel Settings.
	 * since 1.0
	 */
	function render_carousel_settings() {
		global $post;
		$carousel_id = '';
		if (isset($post->ID)) {
			$carousel_id = $post->ID;
		}
		$carousel_meta = get_post_meta( $carousel_id, 'carousel_meta', true );
		$fields = $this->rpc_settings_fields();
		$tabs = $this->rpc_settings_tabs();
		wp_nonce_field( plugin_basename( __FILE__ ), 'rpc_carousel_nonce' );
		include RPC_PATH.'/inc/render-settings.php';
	}

	/**
	 * Renders Input Field for settings.
	 * since 1.0
	 */
	function render_input_field($field, $carousel_meta) {

		$input_name = 'carousel_data';
		$value = '';

		if (is_array($field['key'])) {
			foreach ($field['key'] as $key) {
				$input_name .= '['.$key.']';
			}
			$value = (isset($carousel_meta[$field['key'][0]][$field['key'][1]])) ? $carousel_meta[$field['key'][0]][$field['key'][1]] : '' ;
		} else {
			$input_name .= '['.$field['key'].']';
			$value = (isset($carousel_meta[$field['key']])) ? $carousel_meta[$field['key']] : '' ;
		}
		if (is_array($field['key']) && $field['key'][1] == '') {
			$value = (isset($carousel_meta[$field['key'][0]])) ? $carousel_meta[$field['key'][0]] : '' ;
		}
		switch ($field['type']) {
			case 'text': ?>
				<input type="text" name="<?php echo $input_name; ?>" value="<?php echo $value; ?>" class="widefat">
				<?php break;

			case 'color': ?>
				<input type="text" data-alpha="true" name="<?php echo $input_name; ?>" value="<?php echo $value; ?>" class="colorpicker">
				<?php break;
				
			case 'number': ?>
				<input type="number" name="<?php echo $input_name; ?>" value="<?php echo $value; ?>" class="widefat">
				<?php break;
				
			case 'textarea': ?>
				<textarea name="<?php echo $input_name; ?>" class="widefat"><?php echo $value; ?></textarea>
				<?php break;

			case 'select': ?>
				<select name="<?php echo $input_name; ?>" class="widefat">
					<?php foreach ($field['options'] as $key => $title) {
						echo '<option value="'.$key.'" '.selected( $value, $key ).'>'.$title.'</option>';
					} ?>
				</select>
				<?php break;

			case 'image_size': ?>
				<select name="<?php echo $input_name; ?>">
					<option value="" <?php echo ($value == '') ? 'selected' : '' ; ?>>
						<?php _e( 'Default', 'responsive-posts-carousel' ); ?>
					</option>
					<?php
						$image_sizes = get_intermediate_image_sizes();
						foreach ($image_sizes as $img) {
							$selected = ($value == $img) ? 'selected' : '';
							echo '<option '.$selected.' value="'.$img.'">'.$img.'</option>';
						}
					?>
				</select>
				<?php break;

			case 'taxonomy': ?>
				<select class="widefat" name="<?php echo $input_name; ?>"> 
					<option value=""><?php echo $field['title']; ?></option> 
					<?php 
						$taxonomies = get_taxonomies(array('public'   => true));
						foreach ($taxonomies as $tax) { 
							echo '<option value="'.$tax.'" '.selected( $value, $tax ).'>'.$tax.'</option>';
						}
					?>
				</select>
				<?php break;

			case 'term': ?>
			<?php
				if (isset($carousel_meta['taxonomy']) && $carousel_meta['taxonomy'] != '') {
					$terms = get_terms( $carousel_meta['taxonomy'] );
					if (empty($terms)) {
						echo __( 'Sorry! this Taxonomy has no Terms.', 'responsive-posts-carousel' );
					} else {
						echo '<select class="widefat" multiple name="'.$input_name.'">';
						foreach ($terms as $key => $value) {
							if (isset($carousel_meta['term']) && is_array($carousel_meta['term'])) {
								$selected = (isset($carousel_meta['term']) && in_array($value->term_id, $carousel_meta['term'])) ? 'selected' : '' ;
							} else {
								$selected = (isset($carousel_meta['term']) && $carousel_meta['term'] == $value->term_id) ? 'selected' : '' ;
							}
							echo '<option value="'.$value->term_id.'" '.$selected.'>'.$value->name.'('.$value->count.')</option>';
						}
						echo '</select>';			
					}					
				} else { ?>
					<p class="description"><?php _e( 'Please select any taxonomy first', 'responsive-posts-carousel' ); ?>.</p>
				<?php }
			?>
				<?php break;

			case 'post_type': ?>
				<select name="<?php echo $input_name; ?>" class="widefat">
					<option value=""><?php echo $field['title']; ?></option>
						<?php $post_types = get_post_types( array( 'public' => true, ) );
							foreach ($post_types as $name => $label) {
								$selected = (isset($carousel_meta['post_type']) && $carousel_meta['post_type'] == $name) ? 'selected' : '' ;
								echo '<option value="'.$name.'" '.$selected.'>'.$label.'</option>';
							}
						?>
				</select>
				<?php break;

			case 'posts': ?>
				<select name="<?php echo $input_name; ?>" class="widefat" multiple>
					<?php
						if (isset($carousel_meta['post_type']) && $carousel_meta['post_type'] != '') {
							$all_posts = get_posts( array('post_type' => $carousel_meta['post_type'], 'posts_per_page' => -1 ) );
							$selc = (is_array($value) && in_array('all', $value)) ? 'selected' : '' ;
							echo '<option value="all" '.$selc.'> All '.$carousel_meta['post_type'].'s</option>';
							foreach ($all_posts as $key => $post_obj) {
								$selected = '';
								if ($value != '' && is_array($value)) {
									$selected = (in_array($post_obj->ID, $value)) ? 'selected' : '' ;
								}
								echo '<option value="'.$post_obj->ID.'" '.$selected.'>'.$post_obj->post_title.'</option>';
							}
						}
					?>				
				</select>
				<?php break;

			case 'carousel_styles': ?>
				<select name="<?php echo $input_name; ?>" class="widefat">
					<?php
					$free_ihovers = $this->get_ihover_effects();
					foreach ($free_ihovers as $className) {
					    $selected = ($value == $className) ? 'selected' : '' ; ?>
					    <option value="<?php echo $className; ?>" <?php echo $selected; ?>><?php echo ucwords(str_replace("_"," ",$className)); ?></option>
					<?php }
					?>
				</select>
				<?php break;

			case 'checkbox': ?>
				<label><input <?php checked( $value, 'on', true ); ?> type="checkbox" name="<?php echo $input_name; ?>"><?php _e( 'Enable', 'responsive-posts-carousel' ); ?></label>
				<?php break;
			
			default:
				# code...
				break;
		}
	}

	function render_sc_box($carousel){
		if (isset($carousel->ID)) { ?>
			<p style="text-align:center;">
				<b><?php _e( 'Default Shortcode', 'responsive-posts-carousel' ); ?></b><br>
				[wcp-carousel id="<?php echo $carousel->ID; ?>"]
			</p>
			<hr>
			<p style="text-align:center;">
				<b><?php _e( 'Display 10 Latest Posted', 'responsive-posts-carousel' ); ?></b><br>
				[wcp-carousel id="<?php echo $carousel->ID; ?>" order="DESC" orderby="date" count="10"] <br>
			</p>
			<hr>
			<p style="text-align:center;">
				<b><?php _e( 'Order by Ascending Titles', 'responsive-posts-carousel' ); ?></b><br>
				[wcp-carousel id="<?php echo $carousel->ID; ?>" order="ASC" orderby="title"] <br>
			</p>
		<?php }
	}

	function render_help_box(){
		?>
			<p><?php _e( 'You can contact us', 'responsive-posts-carousel' ); ?>
				<a target="_blank" href="https://webcodingplace.com/contact-us"><?php _e( 'here', 'responsive-posts-carousel' ); ?></a>
				<?php _e( 'for help', 'responsive-posts-carousel' ); ?> <br>
				<?php _e( 'Purchase Premium version and unlock', 'responsive-posts-carousel' ); ?>
			</p>
			<ol>
				<li><a target="_blank" href="https://demos.webcodingplace.com/responsive-posts-carousel-pro-wordpress-plugin/"><?php _e( '20+ Premium Templates', 'responsive-posts-carousel' ); ?></a></li>
				<li><?php _e( '15+ Networks for Social Sharing', 'responsive-posts-carousel' ); ?></li>
				<li><?php _e( 'Access to Premium Support Forum', 'responsive-posts-carousel' ); ?></li>
				<li><?php _e( 'Lifetime Free Updates', 'responsive-posts-carousel' ); ?></li>
			</ol>
			<p style="text-align:center;">
				<a href="https://webcodingplace.com/responsive-posts-carousel-wordpress-plugin/" target="_blank" class="button button-primary button-hero">
					<?php _e( 'Purchase Pro Version', 'responsive-posts-carousel' ); ?>
				</a>
			</p>
		<?php
	}

	function render_shortcode($attrs){
		if (isset($attrs['id']) && $attrs['id'] != '') {
			wp_enqueue_style( 'font-awesome', RPC_URL.'/css/css/font-awesome.min.css' );
			wp_enqueue_style( 'slick-css', RPC_URL.'/assets/slick.css' );
			wp_enqueue_style( 'slick-theme-css', RPC_URL.'/assets/slick-theme.css' );
			wp_enqueue_style( 'ihover-css', RPC_URL.'/assets/ihover.min.css' );
			wp_enqueue_script( 'slick-js', RPC_URL.'/assets/slick.min.js', array('jquery') );
			$carousel_settings = get_post_meta( $attrs['id'], 'carousel_meta', true );

			if ($carousel_settings['hover_effect'] == '3d hover box') {
				wp_enqueue_style( 'vue-css', RPC_URL.'/hover-box/hoverbox.css' );
				wp_enqueue_script( 'vue-js', RPC_URL.'/hover-box/vue.js' );
				wp_enqueue_script( 'trigger-vue-js', RPC_URL.'/hover-box/hoverbox.js' );
			} elseif (isset($carousel_settings['equal_height_mode'])) {
				wp_enqueue_script( 'images-loaded', RPC_URL.'/assets/imagesloaded.js', array('jquery') );
				wp_enqueue_script( 'images-fill', RPC_URL.'/assets/jquery-imagefill.js', array('jquery') );
			}

			wp_enqueue_script( 'custom-crsl-js', RPC_URL.'/assets/custom.js', array('jquery') );

			ob_start();

				include RPC_PATH.'/inc/render.php';

			return ob_get_clean();
		} else {
			echo __( 'Please provide carousel id in shortcode', 'responsive-posts-carousel' );
		}
	}

	function admin_scripts($slug){
		global $post;
        if ( $slug == 'post-new.php' || $slug == 'post.php') {
            if (isset($post->post_type) && 'wcp_carousel' === $post->post_type) {
            	wp_enqueue_style( 'wp-color-picker' );
				wp_enqueue_script( 'ui-block', RPC_URL.'/js/jquery.blockUI.js', array('jquery') );
				wp_enqueue_script( 'colorpicker-alpha', RPC_URL.'/js/wp-color-picker-alpha.min.js', array('jquery') );
				wp_enqueue_style( 'select2-css', RPC_URL.'/css/select2.min.css' );
				wp_enqueue_style( 'wcp-admin-css', RPC_URL.'/css/admin.css' );
				wp_enqueue_script( 'select2-js', RPC_URL.'/js/select2.min.js', array('jquery') );
				wp_enqueue_script( 'carousel-admin', RPC_URL.'/js/admin.js', array('jquery', 'wp-color-picker') );
            }
        }		
	}

	function rpc_get_posts(){
		$all_posts = get_posts( array('post_type' => $_REQUEST['post_type'], 'posts_per_page' => -1 ) );
		echo '<option value="all">'.__( 'All', 'responsive-posts-carousel' ).' '.$_REQUEST['post_type'].'s</option>';
		foreach ($all_posts as $key => $post_obj) {
			echo '<option value="'.$post_obj->ID.'">'.$post_obj->post_title.'</option>';
		}
		// var_dump($all_posts);
		die(0);
	}

	function rpc_get_terms(){
		$terms = get_terms( $_REQUEST['taxonomy'] );
		if (empty($terms) || $_REQUEST['taxonomy'] == '') {
			echo __( 'Sorry! this Taxonomy has no Terms.', 'wcp-carousel' );
		} else {
			echo '<select class="wcp-term widefat" multiple name="carousel_data[term][]">';
			foreach ($terms as $key => $value) {
				echo '<option value="'.$value->term_id.'">'.$value->name.'('.$value->count.')</option>';
			}
			echo '</select>';			
		}
		die(0);
	}	

	function save_carousel($post_id){
        // verify if this is an auto save routine. 
        // If it is our form has not been submitted, so we dont want to do anything
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
            return;

        // verify this came from the our screen and with proper authorization,
        // because save_post can be triggered at other times
        if ( !isset( $_POST['rpc_carousel_nonce'] ) )
            return;

        if ( !wp_verify_nonce( $_POST['rpc_carousel_nonce'], plugin_basename( __FILE__ ) ) )
            return;

        // OK, we're authenticated: we need to find and save the data
        if (isset($_POST['carousel_data']) && $_POST['carousel_data'] != '') {
            update_post_meta( $post_id, 'carousel_meta', $_POST['carousel_data'] );
        }		
	}

	function render_carousel_title($post_id, $carousel_settings){
		$title_key = ($carousel_settings['title'] != '') ? $carousel_settings['title'] : 'post_title' ;
		$this->get_display_data($post_id, $title_key, '');
	}

	function get_display_data($post_id, $key, $length){
		if (strpos($key, ',')) {
			$exc_arr = explode(',', $key);
			$more = (isset($exc_arr[2])) ? $exc_arr[2] : '...' ;
	        $esc_text = get_the_excerpt();
	        echo wp_trim_words( $esc_text, $exc_arr[1], $more );
		} else {
	        switch ($key) {
	            case 'post_date':
	                echo get_the_date();
	                break;
	            case 'post_title':
	                echo get_the_title($post_id);
	                break;
	            case 'content':
	            	if ($length != '') {
	            		echo wp_trim_words( get_the_content(), $length );
	            	} else {
	            		the_content();
	            	}
	                break;
                case 'post_author':
                    echo get_the_author();
                    break;
	            case 'excerpt':
	            	if ($length != '') {
	            		echo wp_trim_words( get_the_excerpt(), $length );
	            	} else {
	            		the_excerpt();
	            	}
	                break;
	            case 'none':
	                break;
	            
	            default:
	            	if ($words != '') {
	            		$meta = get_post_meta( $post_id, $key, true );
	            		echo wp_trim_words( $meta, $words );
	            	} else {
	            		echo get_post_meta( $post_id, $key, true );
	            	}	            
	                
	                break;
	        }
		}
		
	}

	function render_carousel_desc($post_id, $carousel_settings){
		$title_key = ($carousel_settings['desc'] != '') ? $carousel_settings['desc'] : 'post_date' ;
		$words = ($carousel_settings['words'] != '') ? $carousel_settings['words'] : '' ;
		$this->get_display_data($post_id, $title_key, $words);
	}

	function render_read_more_btn($post_id, $carousel_settings){
        if (isset($carousel_settings['read_more_txt']) && $carousel_settings['read_more_txt'] != '') { ?>
                <br>
                <a href="<?php echo get_permalink($post_id); ?>" target="<?php echo $carousel_settings['read_more_target']; ?>" class="<?php echo $carousel_settings['read_more_classes']; ?>"><?php echo $carousel_settings['read_more_txt']; ?></a>
        <?php }
	}

	function render_carousel_thumbnail($post_id, $carousel_settings, $img_args = array(), $only_url = false){
		$images_size = (isset($carousel_settings['images_size'])) ? $carousel_settings['images_size'] : 'full' ;
		if (isset($carousel_settings['equal_height_mode'])) {
			echo '<div class="fixed-height-image">';
		}
		if ($only_url) {
			
		} else {
			if (has_post_thumbnail( $post_id )) {
				echo get_the_post_thumbnail( $post_id, $images_size, $img_args );
			} else {
				if (isset($carousel_settings['placeholder_image']) && $carousel_settings['placeholder_image'] != '') {
					echo '<img src="'.$carousel_settings['placeholder_image'].'">';
				}
			}
		}
		if (isset($carousel_settings['equal_height_mode'])) {
			echo '</div>';
		}
	}
	
    function get_ihover_effects(){
        $hoverEffects = array(
            'none',
            'mini post',
            'post card',
            'no thumbnails',
            'post display style 1',
            'post display style 2',
            'post display style 3',
            'square effect1 left_and_right',	
            'square effect1 top_to_bottom',	
            'square effect1 bottom_to_top',	
            'square effect2',				
            'square effect3 bottom_to_top',	
            'square effect3 top_to_bottom',	
            'square effect4',				
            'square effect5 left_to_right',	
            'square effect5 right_to_left',	
            'square effect6 from_top_and_bottom',	
            'square effect6 from_left_and_right',	
            'square effect6 top_to_bottom',	
            'square effect6 bottom_to_top',	
            'square effect7',				
            'square effect8 scale_up',		
            'square effect8 scale_down',		
            'square effect9 bottom_to_top',	
            'square effect9 left_to_right',	
            'square effect9 right_to_left',	
            'square effect9 top_to_bottom',	
            'square effect10 left_to_right',	
            'square effect10 right_to_left',	
            'square effect10 top_to_bottom',	
            'square effect10 bottom_to_top',	
            'square effect11 left_to_right',	
            'square effect11 right_to_left',	
            'square effect11 top_to_bottom',	
            'square effect11 bottom_to_top',	
            'square effect12 left_to_right',	
            'square effect12 right_to_left',	
            'square effect12 top_to_bottom',	
            'square effect12 bottom_to_top',	
            'square effect13 left_to_right',	
            'square effect13 right_to_left',	
            'square effect13 top_to_bottom',	
            'square effect13 bottom_to_top',	
            'square effect14 left_to_right',	
            'square effect14 right_to_left',	
            'square effect14 top_to_bottom',	
            'square effect14 bottom_to_top',	
            'square effect15 left_to_right',	
            'square effect15 right_to_left',	
            'square effect15 top_to_bottom',	
            'square effect15 bottom_to_top',	
        );

        return $hoverEffects;
    }

    function wcp_carousel_column_head($defaults){
        $defaults['wcp_rpc'] = 'Shortcode';
        return $defaults;       
    }

    function wcp_carousel_column_content($column_name, $carousel_id){
        if ($column_name == 'wcp_rpc') {
            echo '[wcp-carousel id="'.$carousel_id.'"]';
        }
    }

    function get_arrow_codes($fa){
    	switch ($fa) {
    		case 'circle':
    			$resp = array('\f0a9', '\f0a8');
    			break;
    		case 'circleinverted':
    			$resp = array('\f18e', '\f190');
    			break;
    		case 'simple':
    			$resp = array('\f061', '\f060');
    			break;
    		case 'long':
    			$resp = array('\f178', '\f177');
    			break;
    		case 'angle':
    			$resp = array('\f105', '\f104');
    			break;
    		case 'doubleangle':
    			$resp = array('\f101', '\f100');
    			break;
    		case 'caret':
    			$resp = array('\f0da', '\f0d9');
    			break;
    		case 'caretsquare':
    			$resp = array('\f152', '\f191');
    			break;
    		case 'hand':
    			$resp = array('\f0a4', '\f0a5');
    			break;
    		case 'chevron':
    			$resp = array('\f054', '\f053');
    			break;
    		
    		default:
    			$resp = array('\f0a9', '\f0a8');
    			break;
    	}

    	return $resp;
    }

    function load_post_template($style, $post_id, $carousel_settings){
		if (strpos($style, 'square') !== false) {
			$filename = 'squares';   
		} else {
			$filename = str_replace(" ", "-", $style);
		}
		$in_theme = get_template_directory().'/rpc/'.$filename.'.php';
		$in_plugin = RPC_PATH.'/inc/templates/'.$filename.'.php';
		// var_dump($in_plugin);
		if (file_exists($in_theme)) {
			include $in_theme;
		} elseif (file_exists($in_plugin)) {
			include $in_plugin;
		} else {
			echo __( 'Template not Found', 'responsive-posts-carousel' ).' '.$in_plugin;
		}
    }

}
?>