<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://ptheme.com/
 * @since      1.0.0
 *
 * @package    Wp_Sticky_Menu
 * @subpackage Wp_Sticky_Menu/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Sticky_Menu
 * @subpackage Wp_Sticky_Menu/public
 * @author     PTHEME <support@ptheme.com>
 */
class Wp_Sticky_Menu_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Sticky_Menu_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Sticky_Menu_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( 'FontAwesome', plugin_dir_url( __FILE__ ) . 'css/fa/css/font-awesome.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-sticky-menu-public.css', array(), $this->version, 'all' );
		$css = '';
		if ( get_option( 'wpsm_wrap_width' ) ) {
			$css .= '
				.wp-sticky-menu-wrap { max-width: '. get_option( 'wpsm_wrap_width' ) . 'px; }
			';
		}
		if ( get_option( 'wpsm_background' ) ) {
			$css .= '
				.wp-sticky-menu, .wpsm-navigation ul ul li { background: '. get_option( 'wpsm_background' ) . '; }
			';
		}
		if ( get_option( 'wpsm_background_hover' ) ) {
			$css .= '
				.wpsm-navigation li:hover, .wpsm-navigation li.current-menu-item { background: '. get_option( 'wpsm_background_hover' ) . '; }
			';
		}
		if ( get_option( 'wpsm_font_color' ) ) {
			$css .= '
				.wpsm-navigation a, .wpsm-menu-toggle { color: '. get_option( 'wpsm_font_color' ) . '; }
			';
		}
		if ( get_option( 'wpsm_font_color_hover' ) ) {
			$css .= '
				.wpsm-navigation a:hover, .wpsm-navigation li.current-menu-item a { color: '. get_option( 'wpsm_font_color_hover' ) . '; }
			';
		}
		if ( get_option( 'wpsm_logo' ) ) {
			$css .= '
				.wpsm-logo { background: url("'. get_option( 'wpsm_logo' ) . '") no-repeat 0 0; background-size: contain; }
				.wpsm-logo a { text-indent: -9999px; }
			';
		}
		wp_add_inline_style( $this->plugin_name, $css );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Sticky_Menu_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Sticky_Menu_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( 'headroom', plugin_dir_url( __FILE__ ) . 'js/headroom.min.js', array( 'jquery' ), '0.7.0', true );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-sticky-menu-public.js', array( 'jquery' ), $this->version, true );
			wp_localize_script( $this->plugin_name, 'screenReaderText', array(
			'expand'   => __( 'expand child menu', $this->plugin_name ),
			'collapse' => __( 'collapse child menu', $this->plugin_name ),
		) );

	}

	/**
	 * Add WP Sticky Menu tags to footer
	 *
	 * @since    1.0.0
	 */
	public function WPSPM_wp_footer() { ?>

		<?php if ( has_nav_menu( 'wpsm' ) ) : ?>
			<div id="wp-sticky-menu" class="wp-sticky-menu headroom">
				<div id="wp-sticky-menu-wrap" class="wp-sticky-menu-wrap">

					<div id="wpsm-logo" class="wpsm-logo <?php if ( get_option('wpsm_logo') ) { echo 'image-logo'; }?>">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
					</div>

					<button id="wpsm-menu-toggle" class="wpsm-menu-toggle"><?php _e( 'Menu', $this->plugin_name ); ?></button>

					<div id="wpsm-inner" class="wpsm-inner">
							
						<nav id="wpsm-navigation" class="wpsm-navigation" role="navigation" aria-label='<?php _e( 'Sticky Menu', $this->plugin_name ); ?>'>
							<?php
								wp_nav_menu( array(
									'theme_location' => 'wpsm',
									'menu_class'     => 'wpsm-menu',
								) );
							?>
						</nav><!-- .main-navigation -->

						<?php if ( get_option( 'wpsm_social_btns' ) ) : ?>
							<div class="wpsm-social-buttons">
	                            <?php if ( get_option( 'wpsm_facebook' ) ) : ?>
	                            	<div class="wpsm-social-button wpsm-social-button--facebook">
		                                <a href="<?php echo esc_url( get_option( 'wpsm_facebook' ) ); ?>" target="_blank"><i class="fa fa-facebook"></i>
		                                	<span class="social-text">Like</span>
		                                </a>
		                            </div>
		                        <?php endif; ?>

		                        <?php if ( get_option( 'wpsm_twitter' ) ) : ?>
		                            <div class="wpsm-social-button wpsm-social-button--twitter">
		                                <a href="<?php echo esc_url( get_option( 'wpsm_twitter' ) ); ?>" target="_blank"><i class="fa fa-twitter"></i>
		                                	<span class="social-text">Follow</span>
		                                </a>
		                            </div>
	                            <?php endif; ?>
	                        </div>
	                    <?php endif; ?>

					</div><!-- .wpsm-inner -->

				</div><!-- .wp-sticky-menu-wrap -->
			</div><!-- .wp-sticky-menu -->
		<?php endif; ?>
			
	<?php }

}
