<?php
/**
* Plugin Main Class
*/
class WCP_Posts_Carousel
{
	
	function __construct()
	{
		add_action( 'admin_menu', array( $this, 'posts_carousel_admin_options' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_options_page_scripts' ) );
		add_action('wp_ajax_wcp_save_posts_carousel_slides', array($this, 'save_carousels'));
		add_action('wp_ajax_wcp_get_terms', array($this, 'get_terms'));
		add_action( 'wp_enqueue_scripts', array($this, 'adding_styles') );
		add_shortcode( 'posts-carousel', array( $this, 'render_all_shortcodes' ) );
		add_action( 'plugins_loaded', array($this, 'wcp_load_plugin_textdomain' ) );
	}

	function adding_styles(){
		wp_register_style( 'carousel-css', plugins_url( 'css/flexslider.css' , __FILE__ ));
		wp_register_script( 'wcp-posts-carousel', plugins_url( 'js/script.js' , __FILE__ ), array('jquery') );
	}

	function admin_options_page_scripts($slug){
		// var_dump($slug);
		if ($slug == 'wcp_carousel_page_posts_carousel') {
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'photo-book-admin-js', plugins_url( 'admin/script.js' , __FILE__ ), array('jquery', 'jquery-ui-accordion', 'wp-color-picker') );
			wp_enqueue_style( 'photo-book-admin-css', plugins_url( 'admin/style.css' , __FILE__ ));
			wp_localize_script( 'photo-book-admin-js', 'wcpAjax', array( 'url' => admin_url( 'admin-ajax.php' ), 'path' => plugin_dir_url( __FILE__ )));
		}
	}

	function wcp_load_plugin_textdomain(){
		load_plugin_textdomain( 'wcp-carousel', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
	}

	function save_carousels(){
		if (isset($_REQUEST)) {
			update_option( 'wcp_posts_carousel', $_REQUEST );
		}

		die(0);
	}

	function posts_carousel_admin_options(){
		$allCarousels = get_option('wcp_posts_carousel');
		if ($allCarousels != '') {
			add_submenu_page( 'edit.php?post_type=wcp_carousel', 'Responsive Posts Carousel', 'Old Data', 'manage_options', 'posts_carousel', array($this, 'render_menu_page'), 'dashicons-editor-insertmore' );
		}
	}

	function render_menu_page(){
		$allCarousels = get_option('wcp_posts_carousel');
		?>
			<div class="wrap" id="photo-book">
				<h2><?php _e( 'Responsive Posts Carousel', 'wcp-carousel' ); ?> <a title="<?php _e( 'Need Help', 'wcp-carousel' ); ?>?" target="_blank" href="http://webcodingplace.com/responsive-posts-carousel/"><span class="dashicons dashicons-editor-help"></span></a></h2>

				<div id="accordion">
				<?php if (isset($allCarousels['carousels'])) { ?>
				
					<?php foreach ($allCarousels['carousels'] as $key => $data) {
			  			include 'inc/saved_options.php';	
					} ?>
				<?php } else {
			  			include 'inc/first_options.php';
				} ?>
				</div>

				<hr style="clear: both;">
				<button class="button-primary save-pages"><?php _e( 'Save Changes', 'wcp-carousel' ); ?></button>
				<span id="wcp-loader"><img src="<?php echo plugin_dir_url( __FILE__ ); ?>images/ajax-loader.gif"></span>
				<span id="wcp-saved"><strong><?php _e( 'Changes Saved', 'wcp-carousel' ); ?>!</strong></span>				
			</div>
		<?php
	}

	function get_terms(){
		extract($_REQUEST);
		$terms = get_terms( $taxonomy );
		if (empty($terms) || $taxonomy == '') {
			echo __( 'Sorry! this Taxonomy has no Terms.', 'wcp-carousel' );
		} else {
			echo '<select class="wcp-term widefat" multiple name="car[term]">';
			foreach ($terms as $key => $value) {
				echo '<option value="'.$value->term_id.'">'.$value->name.'('.$value->count.')</option>';
			}
			echo '</select>';			
		}
		die(0);
	}

	function render_all_shortcodes($atts, $content, $the_shortcode){

		$allCarousels = get_option('wcp_posts_carousel');

		if (isset($allCarousels['carousels'])) {
			foreach ($allCarousels['carousels'] as $key => $data) {

				if ($atts['id'] == $data['counter']) {

					wp_enqueue_script( 'carousel-js', plugins_url( 'js/jquery.flexslider.min.js' , __FILE__ ), array('jquery') );
					wp_enqueue_style( 'carousel-css');
					
					wp_localize_script( 'wcp-posts-carousel', 'carousel', array(
										'width' 			=> $data['width'],
										// 'margin' 			=> $data['margin'],
										'slideshowSpeed' 	=> $data['slideshowSpeed'],
										'animationSpeed' 	=> $data['animationSpeed'],
										'looping' 			=> $data['looping'],
										'playpause' 		=> $data['playpause'],
										'slideshow' 		=> $data['slideshow'],
										'smoothHeight' 		=> $data['smoothHeight'],
										'controlnav' 		=> $data['controlnav'],
										'directionnav' 		=> $data['directionnav'],
									));

					
					wp_enqueue_script( 'wcp-posts-carousel');

					$carouselContents = '<style>.flexslider{background: '.$data['bgcolor'].' !important; border: 4px solid '.$data['bgcolor'].' !important;}</style>';
					$carouselContents .= '<div class="flexslider carousel">';
					$carouselContents .= '<ul class="slides">';
					$exclude_ids = $data['exclude_ids'];

					$posttype = (isset($data['wcpposttype'])) ? $data['wcpposttype'] : 'post' ;

					$exclude_ids_arr = explode(",",$exclude_ids);

					if (isset($data['post_ids']) && $data['post_ids'] != '') {
						$include_ids_arr = explode(',', $data['post_ids']);
						$args = array(
							'post_type' 		=>  $posttype,
							'posts_per_page' 	=> -1,
							'post__in'			=> $include_ids_arr,
						);
					} else {
						$args = array(
							// 'cat' 				=>  $data['category'],
							'posts_per_page' 	=> -1,
							'post__not_in'		=> $exclude_ids_arr,
							'tax_query' 		=> array(
								array(
									'taxonomy'         => $data['taxonomy'],
									'terms'            => array( $data['term'] ),
									'include_children' => true,
								),
							),
						);
					}
						// The Query
						$the_query = new WP_Query( $args );
						// The Loop
						if ( $the_query->have_posts() ) {
							while ( $the_query->have_posts() ) {
								$the_query->the_post();

								if ( has_post_thumbnail() ) {
									$post_thumbnail = get_the_post_thumbnail( get_the_id(), 'medium' );
								}
								else {
									$post_thumbnail = '<img src="'.plugin_dir_url( __FILE__ ).'images/placeholder.png">';
								}

								$carouselContents .= '<li id="post-'.get_the_id().'"><a href="'.get_the_permalink().'">';
									$carouselContents .= $post_thumbnail;
									if ($data['showtitles'] == 'true') {
										$carouselContents .= '<h3 style="margin: 5px 0 0 0;" class="text-center">'.get_the_title().'</h3>';
									}
								$carouselContents .= '</a>';
								if ($data['showtime'] == 'true') {
									$carouselContents .= '<p style="margin: 5px 0 0 0;" class="text-center">Posted: <i>'.human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago</i></p>';
								}
								$carouselContents .= '</li>';
							}
						} else {
							$carouselContents = "<div><h1>404 - No Posts Found!</h1></div>";
						}
						/* Restore original Post Data */
						wp_reset_postdata();
					$carouselContents .= '</ul>';		
					$carouselContents .= '</div>';		

					return $carouselContents;
				}
				
			}
		}		
	}
}

?>