<?php
/**
 * Button X
 *
 * This file is used to register export/import functionality of the plugin.
 *
 * @package Buttons X
 * @since 0.1
 */

// Make sure we don't expose any info if called directly
if ( !defined( 'ABSPATH' ) )
	exit;

if( !class_exists( 'BtnsxPortation' ) ) {
	class BtnsxPortation {
		private static $instance;

		/**
		 * Initiator
		 * @since 0.1
		 */
		public static function init() {
			return self::$instance;
		}

		/**
		 * Constructor
		 * @since 0.1
		 */
		public function __construct() {
			add_action( 'admin_head', array( $this, 'import_page_styles' ) );
    		add_action( 'wp_ajax_one_click_import', array( $this, 'one_click_import' ) );
			add_action( 'admin_footer', array( $this, 'import_jquery' ) );
		}

		/**
		 * Function to output CSS on import page.
		 * @since  0.1
		 * @return string
		 */
		public function import_page_styles() {
			$current_color = get_user_option( 'admin_color' );
			global $_wp_admin_css_colors;
			?>
			<style type="text/css">
		      	.col-pad-settings {
					padding: 20px 10px 20px !important;
		      	}
		      	.btn-save {
					background-color: <?php echo $_wp_admin_css_colors[$current_color]->colors[3]; ?> !important;
					background-image: none !important;
		      	}
		      	.btn-import {
					background-color: <?php echo $_wp_admin_css_colors[$current_color]->colors[2]; ?> !important;
					background-image: none !important;
		      	}
		      	.btn-settings {
		      		/*width: 100% !important;*/
					border: 0 !important;
					color: #fff !important;
		      	}
		      	.btn-settings:disabled {
		      		opacity: 0.4;
		      	}
	      	</style>
	      	<?php
		}

		/**
		 * Function to import pre-made buttons
		 * @since  0.1
		 * @param  string    $file
		 * @param  string    $type
		 * @return string
		 */
		public function one_click_import( $file ) {
			// check for nonce
			check_ajax_referer( 'btnsx-import', 'security' );

			$local_file = BTNSX__PLUGIN_DIR . 'assets/buttons.json';

			$predefined = array();
			if( $file === '' ) {
				$file = $local_file;
				$predefined = isset($_POST['buttons']) ? $_POST['buttons'] : array();
			}

			$buttons = self::parse( $file );

			$newButtons = array();
			if( is_array( $buttons ) ) {
				$newButtons = $buttons;
				$buttons = array();
				foreach ( $newButtons as $key => $value ) {
					if( !empty($predefined) ){
						foreach ( $value as $k => $v ) {
							if( in_array($k, array_filter($predefined)) ){
								$buttons[$k] = $v;
							}
						}
					} 
					if( $file != $local_file ) {
						foreach ( $value as $k => $v ) {
							$buttons[$k] = $v;
						}
					}
				}
			}
			
			if( !empty($buttons) ){
				$data = array(); $title = array(); $taxonomies = array();
				// store the json data in proper format
				foreach ( $buttons as $key => $value ) {
					$val = (array) $value->{ 'data' };
					$id = $val['btnsx_id'];
					$title[ $id ] = $value->{ 'title' };
					$tags = (array) $value->{ 'tags' };
					$packs = (array) $value->{ 'packs' };
					$post_type = $value->{ 'type' };
					// store tags as an array
					foreach ( $tags as $t => $g ) {
						if( is_object( $g ) ){ //  && $g->object_id == $id
							$taxonomies[ $id ][ 'btnsx_tag' ][] = $g->name;
						}
					}
					// store packs as an array
					foreach ( $packs as $p => $k ) {
						if( is_object( $k ) ){
							$taxonomies[ $id ][ 'btnsx_pack' ][] = $k->name;
						}
					}
					$d = array();
					// convert deep object values to array
					foreach ( $val as $k => $v ) {
						if( is_object( $v ) ){
							$v = (array) $v;
						}
						$d[ $k ] = $v;
					}
					$data[ $post_type ][ $val['btnsx_id'] ] = $d;
				}
				$buttons_args = array(
					'post_type' => 'buttons-x',
					'posts_per_page' => -1
				);
				// The Query to get all currently stored button titles
				$title_query = new WP_Query( $buttons_args );
				$title_array = array();
				// The Loop
				if ( $title_query->have_posts() ) {
					while ( $title_query->have_posts() ) {
						$title_query->the_post();
						$title_array[] = get_the_title();
					}
				} else {
					// no posts found
				}
				/* Restore original Post Data */
				wp_reset_postdata();
				global $btnsx_settings;
				foreach ( $data as $type => $value ) {
					foreach( $value as $id => $val ) {
						$title[ $id ] = isset( $title[ $id ] ) ? $title[ $id ] : __( 'No Title', 'buttons-x' );
						if( !in_array( $title[ $id ], ( $type === 'buttons-x' ? $title_array : '' ) ) ) {
							$args = array(
								'post_title'	=> $title[ $id ], // The title of post.
								'post_status'	=> 'publish',
								'post_type'		=> $type, // Our custom post type.
							);
							$pack = 'btnsx_pack';
							$tag = 'btnsx_tag';
							$post_id 		= wp_insert_post( $args );
							if( isset( $taxonomies[ $id ][ 'btnsx_pack' ] ) ) {
								$packs 		= wp_set_object_terms( $post_id, $taxonomies[ $id ][ 'btnsx_pack' ], $pack );
							}
							if( isset( $taxonomies[ $id ][ 'btnsx_tag' ] ) ) {
								$tags 		= wp_set_object_terms( $post_id, $taxonomies[ $id ][ 'btnsx_tag' ], $tag );
							}
							if ( is_wp_error( $packs ) ) {
								echo sprintf( __( 'Error assigning packs for button %d.', 'buttons-x' ), $post_id );
							}
							if ( is_wp_error( $tags ) ) {
								echo sprintf( __( 'Error assigning tags for button %d.', 'buttons-x' ), $post_id );
							}
							if( $type == 'buttons-x' ){
								update_post_meta( $post_id, 'btnsx', $val );
							}
							echo sprintf( __( 'Button "%s" imported successfully.', 'buttons-x' ), $title[ $id ] ) . '|';
						} else {
							echo sprintf( __( 'Button with same name "%s" already exists.', 'buttons-x' ), $title[ $id ] ) . '|';
						}
					}
				}
			} else {
				echo __( 'No button selected.', 'buttons-x' );
			}

			wp_die();
		}

		/**
		 * Function to output script on settings page
		 * @since  0.1
		 * @return string
		 */
		public function import_jquery() {
			// create nonce
			$import_nonce = wp_create_nonce( 'btnsx-import' );
			$screen = get_current_screen();
		    if ( $screen->id == 'buttons-x_page_buttons-x-import' ) {
				?>
			    <script type="text/javascript">
					jQuery( document ).ready( function( $ ) {
						var json = '';
						$( '#btnsx-click-import' ).on( 'click', function( e ) {
							e.preventDefault();
							var buttons = [];
							$('input[name="btnsx_opt_predefined_style[]"]').each(function(){
								if($(this).is(':checked')){
									buttons.push($(this).val());
								}
							});
							btnText = '<?php _e( "Import", 'buttons-x' ); ?>';
							// disble the button to avoid multiple clicks
						  	$( this ).attr( 'disabled', 'disabled' ).html( '<i class="fa fa-refresh fa-spin"></i>' );
						  	var data = {
								'action'	: 'one_click_import',							// wp ajax action
								'security'	: '<?php echo $import_nonce; ?>',			// nonce value created earlier
								'buttons'	: buttons
							};
							// fire ajax
						  	$.post( ajaxurl, data, function( response ) {
						  		// console.log( response );
						  		var split = response.split( '|' );
						  		// if nonce fails then not authorized else settings saved
						  		if( response === '-1' ){
							  		toast( '<?php _e( "Not Authorized!", 'buttons-x' ); ?>', '#btnsx-click-import', btnText );
						  		} else {
						  			$.each( split, function( i, v ){
						  				if( v != '' ){
						  					setTimeout(function(){ 
						  						toast( v, '#btnsx-click-import', btnText );
						  					}, 800*i );
							  			}
						  			});
						  		}
						  	});
						});
						var btnText = '<?php _e( "Import", 'buttons-x' ); ?>';
						function optimizedImport( json, start, finish ) {
							var data = {
								'action'	: 'upload',									// wp ajax action
								'security'	: '<?php echo $import_nonce; ?>',			// nonce value created earlier
								'data'		: json
							};
							if( start == 0 ){
				  				console.log('Import started!');
				  			}
							// fire ajax
						  	$.post( ajaxurl, data, function( response ) {
						  		// var_dump(response);
						  		// if nonce fails then not authorized else settings saved
						  		if( response === '-1' ){
							  		toast( '<?php _e( "Not Authorized!", "btnsx" ); ?>', '#btnsx-submit-import', btnText );
						  		} else {
						  			var timeOut = + start * 800;
						  			response = response.replace( '|', '' );
						  			console.log( response );
						  			setTimeout(function(){ 
				  						Materialize.toast( response, 2000 );
				  						if( start == finish ){
				  							$( '#btnsx-submit-import' ).removeAttr( 'disabled' ).html( btnText );
							  				console.log('Import finished!');
							  			}
				  					}, timeOut );
						  		}
						  	});
						}

						/**
					  	 * Dialog box
					  	 * @since  0.1
					  	 * @param  {string}  dialogText text to be displayed inside dialog
					  	 * @param  {string}  buttonText text to be displayed inside button
					  	 * @return {string}
					  	 */
					  	function toast( dlgText, btnId, btnText ){
					  		Materialize.toast( dlgText, 2000, '', function(){
					  			$( btnId ).removeAttr( 'disabled' ).html( btnText );
					  		});
					  	}

					});
				</script>
				<?php
			}
		}

		/**
		 * Check and make sure data is json format
		 * @since  0.1
		 * @param  string    $string
		 * @return boolean
		 */
		public static function isJson( $string ) {
	        json_decode( $string );
	        return ( json_last_error() == JSON_ERROR_NONE );
	    }

	    /**
	     * Check and return decoded json data
	     * @since  0.1
	     * @param  string    $file
	     * @return mixed
	     */
	    public static function parse( $file ) {
	        // Load the json file
	        if( is_file( $file ) ){
	        	$json = file_get_contents( $file );
	        } else {
	        	$json = $file;
	        }
	        $decoded = json_decode( $json );

	        $format = self::isJson( $json );
	        // Is it of json format?
	        if ( $format == false ){
	            wp_die( __( 'There was an error importing the buttons. File content should be in valid "JSON" format.', 'buttons-x' ) );
	        } else {
	            return $decoded;
	        }
	    }
	} // Portation Class
}

/**
 *  Kicking this off
 */

$btn_options = new BtnsxPortation();
$btn_options->init();