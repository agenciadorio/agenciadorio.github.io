<?php

/**
 * Plugin Name: Print Post and Page
 * Plugin URI: https://wordpress.org/plugins/print-post-and-page/
 * Description: Add a Print Friendly Button to Posts and Pages.
 * Version: 1.62
 * Author: HTML5andBeyond
 * Author URI: http://www.html5andbeyond.com/
 * License: GPLv2 or Later
 */

	if ( ! defined( 'ABSPATH' ) ) exit;

	define( 'H5AB_PRINT_PLUGIN_DIR', plugin_dir_path( __FILE__ ));
	define('H5AB_PRINT_PLUGIN_URL', plugin_dir_url( __FILE__ ));

    include_once( H5AB_PRINT_PLUGIN_DIR . 'includes/h5ab-print-functions.php');

	if(!class_exists('H5AB_Print')) {

			class H5AB_Print {

			    private $formResponse = '';

				public function __construct() {

					add_action('admin_menu', array($this, 'add_menu'));

					add_action('wp_enqueue_scripts', array($this, 'load_scripts'), 1);
                    add_action('init', array($this, 'validate_form_callback'), 2);
                    add_action('admin_enqueue_scripts', array($this, 'admin_init'), 3);

                    add_shortcode( 'printicon', array($this, 'h5ab_print_shortcode'), 4);

                    add_filter('the_content', array($this, 'h5ab_meta_add_print_shortcode'), 5);

                    add_action( 'load-post.php', array($this, 'h5ab_printed_meta_construct'), 1);
                    add_action( 'load-post-new.php', array($this, 'h5ab_printed_meta_construct'), 2);

                    add_action('wp_footer', array($this, 'on_page_scripts'), 100);

				}

				public function add_menu() {

					add_menu_page('Print', 'Print','administrator', 'print-settings',
					array($this, 'plugin_settings_page'), H5AB_PRINT_PLUGIN_URL . 'images/icon.png');

				}

                public function admin_init() {
                    wp_enqueue_script('h5ab-print-spectrum-js', H5AB_PRINT_PLUGIN_URL . 'js/spectrum.js', array('jquery'), '', true);
                    wp_enqueue_style('h5ab-print-admin-css', H5AB_PRINT_PLUGIN_URL . 'css/h5ab-print-admin.css');

                    wp_enqueue_style('h5ab-print-spectrum-css', H5AB_PRINT_PLUGIN_URL . 'css/spectrum.css');
                }

				public function plugin_settings_page() {

					if(!current_user_can('administrator')) {
						  wp_die('You do not have sufficient permissions to access this page.');
					}

					include_once(sprintf("%s/templates/h5ab-print-settings.php", H5AB_PRINT_PLUGIN_DIR));

				}

				public function load_scripts() {

					wp_enqueue_style('h5ab-print-font-awesome', H5AB_PRINT_PLUGIN_URL . 'css/font-awesome.min.css');
                    wp_enqueue_style('h5ab-print-css', H5AB_PRINT_PLUGIN_URL . 'css/h5ab-print.min.css');

                    wp_enqueue_script('h5ab-print-js', H5AB_PRINT_PLUGIN_URL . 'js/h5ab-print.min.js', array('jquery'), '', true);

                    $h5abPrintCSS = get_option('h5abPrintCSS');
					$printSettings = (! empty($h5abPrintCSS)) ? $h5abPrintCSS : '';

                    $translation_array = array(
                        'customCSS' => wp_kses_post( $printSettings )
                    );

                    wp_localize_script( 'h5ab-print-js', 'h5abPrintSettings', $translation_array);

				}

                public function on_page_scripts() {
					include_once(sprintf("%s/js/h5ab-on-page-script.php", H5AB_PRINT_PLUGIN_DIR));
				}

				public function setFormResponse($response) {
					$class = ($response['success']) ? 'updated' : 'error';
				    $this->formResponse =  '<div = class="' . $class . '"><p>' . $response['message'] . '</p></div>';
				}

				public function getFormResponse() {
				    $fr = $this->formResponse;
				    echo $fr;
				}

                public function validate_form_callback() {

					if (isset($_POST['h5ab_print_settings_nonce'])) {

							if(wp_verify_nonce( $_POST['h5ab_print_settings_nonce'], 'h5ab_print_settings_n' )) {

								$response = h5ab_print_settings();

								$this->setFormResponse($response);

								add_action('admin_notices',  array($this, 'getFormResponse'));

							} else {
								wp_die("You do not have access to this page");
							}

					}

				}

                public function h5ab_printed_add_post_meta() {
                    
                    $screens = array( 'post' );

                    $post_types = get_post_types( array ( '_builtin' => FALSE ), 'objects' );

                    foreach($post_types as $post_type) {
                        array_push($screens, $post_type->name);
                    }

                    foreach ( $screens as $screen ) {
                        
                        add_meta_box(
                            'h5ab-printed-meta-disable',
                            esc_html__( 'Print Post Button', 'example' ),
                            array($this, 'h5ab_printed_post_meta_box'),
                            $screen,
                            'side',
                            'default'
                        );

                    }

                }

                public function h5ab_printed_post_meta_box( $object, $box ) { ?>

                  <?php wp_nonce_field( 'h5ab_print_post_n', 'h5ab_print_post_nonce' );
                    $h5ab_meta_key_print_data = get_post_meta( $object->ID, 'h5abMetaPrintData', true );
					$h5ab_options_print_array = get_option('h5abPrintData');
					$display_meta_info = ($h5ab_options_print_array['h5abPrintActive'] == true) ? false: true;
                  ?>

                  <p>
                    <label>Disable Print:</label>
                    <br/>
                    <input class="widefat" type="checkbox" name="h5ab-printed-meta-disable" id="h5ab-printed-meta-disable" value="true" size="30" <?php if (esc_attr($h5ab_meta_key_print_data) == 'true') { echo 'checked'; } ?> />
					<?php if($display_meta_info) { ?> <p class="h5ab-print-meta-info">Print is not currently active</p> <?php }  ?>
                  </p>

                <?php }


                public function h5ab_printed_meta_construct() {
					add_action( 'add_meta_boxes', array($this, 'h5ab_printed_add_post_meta') );
					add_action( 'save_post', array($this, 'h5ab_printed_save_post_meta'), 10, 2 );
                }


				 public function h5ab_printed_save_post_meta( $post_id, $post ) {

				   global $post;
                     
                   if (isset($_POST['h5ab_print_post_nonce'])) {

                   if(wp_verify_nonce($_POST['h5ab_print_post_nonce'], 'h5ab_print_post_n' ) && is_admin()) {
                        
                        if (isset($_POST['h5ab-printed-meta-disable'])) {
                            $new_print_post_disabled_value = ( isset( $_POST['h5ab-printed-meta-disable'] ) ? sanitize_text_field( $_POST['h5ab-printed-meta-disable'] ) : '' );
                        } else {
                            $new_print_post_disabled_value = '';
                        }
                        
                        if(is_null($_POST['h5ab-printed-meta-disable'])) {
                            delete_post_meta( $post_id, 'h5abMetaPrintData' );
                        } else {
                            update_post_meta( $post_id, 'h5abMetaPrintData', $new_print_post_disabled_value );
                        }

                    }
                       
                  }

				 }


                public function h5ab_meta_add_print_shortcode($content) {

				$postID = $GLOBALS['post']->ID;

                $h5ab_meta_key_print_data = get_post_meta( $postID, 'h5abMetaPrintData', true );
                $h5ab_options_print_array = get_option('h5abPrintData');

                   // if(!is_feed() && !is_home()) {
					if(is_single($postID)) {
							if ($h5ab_options_print_array['h5abPrintActive'] == 'true') {
								if (isset($h5ab_meta_key_print_data) && $h5ab_meta_key_print_data == 'true') {} else {
									if (esc_attr($h5ab_options_print_array['h5abPrintPlacement']) == 'before') {
										$content = '[printicon align="' . esc_attr($h5ab_options_print_array['h5abPrintAlignment']) . '"]' . $content;
									} else if (esc_attr($h5ab_options_print_array['h5abPrintPlacement']) == 'after') {
										$content .= '[printicon align="' . esc_attr($h5ab_options_print_array['h5abPrintAlignment']) . '"]';
									}
								}
							} else {

							}
                    }

					return $content;
                }

                public function h5ab_print_shortcode( $atts ) {

                    $h5abPrintArray = get_option('h5abPrintData');
					if($h5abPrintArray['h5abPrintActive'] == true){

						extract( shortcode_atts( array(
							'align' => 'right'
						), $atts ) );

						if ($align == 'right' || $align == 'Right') {
                            
							return '<div class="h5ab-print-button-container"><div class="h5ab-print-button h5ab-print-button-right" style="cursor: pointer; color: ' . esc_attr($h5abPrintArray["h5abPrintIconColor"]) . '"><i class="fa fa-print ' . esc_attr($h5abPrintArray["h5abPrintIconSize"]) . '"></i>
							<span>' . esc_attr($h5abPrintArray["h5abPrintLabel"]) . '</span></div></div>';
							} else if ($align == 'left' || $align == 'Left') {
							return '<div class="h5ab-print-button-container"><div class="h5ab-print-button h5ab-print-button-left" style="cursor: pointer; color: ' . esc_attr($h5abPrintArray["h5abPrintIconColor"]) . '"><i class="fa fa-print ' . esc_attr($h5abPrintArray["h5abPrintIconSize"]) . '"></i>
							<span>' . esc_attr($h5abPrintArray["h5abPrintLabel"]) . '</span></div></div>';
							}

						}

				}

                public static function activate() {

					$printActive = sanitize_text_field('true');
                    $printLabel = sanitize_text_field('print');
                    $printIconColor = sanitize_text_field('#555');
                    $printIconSize = sanitize_text_field('fa-lg');
                    $printPlacement = sanitize_text_field('after');
                    $printAlignment = sanitize_text_field('right');

                    $h5abPrintActivationArray = array (
                        "h5abPrintActive" => $printActive,
                        "h5abPrintLabel" => $printLabel,
                        "h5abPrintIconColor" => $printIconColor,
                        "h5abPrintIconSize" => $printIconSize,
                        "h5abPrintPlacement" => $printPlacement,
                        "h5abPrintAlignment" => $printAlignment
                    );

                    $updated = update_option( 'h5abPrintData', $h5abPrintActivationArray);

				}

                public static function deactivate() {
                    delete_option( 'h5abPrintData' );
					delete_post_meta_by_key( 'h5abMetaPrintData' );
				}

            }

	}

	if(class_exists('H5AB_Print')) {

        register_activation_hook( __FILE__, array('H5AB_Print' , 'activate'));
        register_deactivation_hook( __FILE__, array('H5AB_Print' , 'deactivate'));

		$H5AB_Print = new H5AB_Print();

	}


?>
