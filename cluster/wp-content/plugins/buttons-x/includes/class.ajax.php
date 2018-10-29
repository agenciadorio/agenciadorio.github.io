<?php
/**
 * Button X
 *
 * This file is used to register ajax related functionality of the plugin.
 *
 * @package Buttons X
 * @since 0.1
 */

// Make sure we don't expose any info if called directly
if ( !defined( 'ABSPATH' ) )
	exit;

if( !class_exists( 'BtnsxAjax' ) ) {
	class BtnsxAjax {

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
			add_action( 'wp_ajax_settings_save', array( $this, 'settings_save_ajax' ) );
			add_action( 'wp_ajax_settings_reset', array( $this, 'settings_reset_ajax' ) );
			add_action( 'admin_footer', array( $this, 'settings_jquery' ) );
			add_action( 'wp_ajax_reset_button', array( $this, 'reset_button_ajax' ) );
			add_action( 'admin_footer', array( $this, 'reset_button' ) );
		}

		/**
		 * Function to reset button using ajax
		 * @since  1.2
		 * @return string
		 */
		public function reset_button_ajax() {
			// check for nonce
			check_ajax_referer( 'btnsx-reset-button', 'security' );

			if( $_POST['post_type'] == 'buttons-x' ){
				if( update_post_meta( $_POST['post_id'], 'btnsx', ''  ) ) {
					wp_die( __( 'Reset Successful', 'buttons-x' ) );
				} 
			} else {
				wp_die( __( 'Setting fields are already at their default values. Reset not required.', 'buttons-x' ) );
			}
		}
 		
		/**
		 * Function to reset a button
		 * @since  0.1
		 * @return string
		 */
		public function reset_button() {
			// create nonce
			$reset_button_nonce = wp_create_nonce( 'btnsx-reset-button' );
			// global $post; var_dump($post);
		    // if ( $post->post_type == 'buttons-x' ) {
				?>
			    <script type="text/javascript">
					jQuery( document ).ready( function( $ ) {
						$( '#btnsx_options_reset' ).on( 'click', function( ev ) {
							ev.preventDefault();
							// disble the button to avoid multiple clicks
							$( this ).attr( 'disabled', 'disabled' );
							// spin the icon
							if( confirm( '<?php _e( "Are you sure?", "btnsx" ); ?>' ) ) {
								$( '#btnsx_options_reset_icon' ).addClass( 'fa-spin' );
							  	var data = {
									'action'	: 'reset_button',							// wp ajax action
									'security'	: '<?php echo $reset_button_nonce; ?>',		// nonce value created earlier
									'post_id'	: $('#post_ID').val(),	// button ID
									'post_type'	: $('#post_type').val()	// button type
								};
								// fire ajax
							  	$.post( ajaxurl, data, function( response ) {
							  		// if nonce fails then not authorized else settings saved
							  		if( response === '-1' ){
								  		// do nothing
							  		} else {
							  			confirm( response );
							  			location.reload();
							  		}
							  	});
							}
						});  
					});
				</script>
				<?php
			// }
		}

		/**
		 * Function to check difference in multi-dimensional array - http://stackoverflow.com/a/16359538/2430413
		 * @since  0.1
		 * @param  array    $aArray1
		 * @param  array    $aArray2
		 * @return array
		 */
		public function multi_array_diff( $aArray1, $aArray2 ) {
		  	$aReturn = array();
		  	foreach ( $aArray1 as $mKey => $mValue ) {
		    	if ( array_key_exists( $mKey, $aArray2 ) ) {
		      		if ( is_array( $mValue ) ) {
		        		$aRecursiveDiff = $this->multi_array_diff( $mValue, $aArray2[ $mKey ] );
		        	if ( count( $aRecursiveDiff ) ) { $aReturn[ $mKey ] = $aRecursiveDiff; }
		      	} else {
		        	if ( $mValue != $aArray2[ $mKey ] ) {
		          	$aReturn[ $mKey ] = $mValue;
		        	}
		      	}
		    } else {
		      $aReturn[ $mKey ] = $mValue;
		    }
		  }
		  return $aReturn;
		}

		/**
		 * Function to save settings using ajax
		 * @since  0.1
		 * @return string
		 */
		public function settings_save_ajax() {
			// check for nonce
			check_ajax_referer( 'btnsx-settings', 'security' );
			// store form data
			parse_str( $_POST['value'], $form);
			// unset not required values 
			unset( $form['option_page'], $form['action'], $form['_wpnonce'], $form['_wp_http_referer'] );

			$form['tab'] = $_POST['tab'];
			
			if( !is_array( get_option('btnsx_settings') ) ) {
				$options = array();
			} else {
				$options = get_option( 'btnsx_settings' );
			}
			// 
			if( !empty( $form ) ) {

				$diff = $this->multi_array_diff( $options, $form );
				$diff2 = $this->multi_array_diff( $form, $options );
				$diff = array_merge( $diff, $diff2 );
			} else {
				$diff = array();
			}
			// if the value are changed update our options else do nothing
			if( !empty( $diff ) ) {	
				if( update_option( 'btnsx_settings', $form ) ) {
					wp_die( __( 'Settings Saved', 'buttons-x' ) );
				} else {
					wp_die( __( 'Settings Not Saved', 'buttons-x' ) );
				}
			} else {
				wp_die( __( 'No change detected', 'buttons-x' ) );
			}
		}

		/**
		 * Function to reset settings using ajax
		 * @since  0.1
		 * @return string
		 */
		public function settings_reset_ajax() {
			// check for nonce
			check_ajax_referer( 'btnsx-settings', 'security' );

			if( update_option( 'btnsx_settings', array() ) ) {
				wp_die( __( 'Reset Successful', 'buttons-x' ) );
			} else {
				wp_die( __( 'Setting fields are already at their default values. Reset not required.', 'buttons-x' ) );
			}
		}

		/**
		 * Function to output script on settings page
		 * @since  0.1
		 * @return string
		 */
		public function settings_jquery() {
			// create nonce
			$settings_nonce = wp_create_nonce( 'btnsx-settings' );
			$screen = get_current_screen();
		    if ( $screen->id == 'buttons-x_page_buttons-x-settings' ) {
				?>
			    <script type="text/javascript">
					jQuery( document ).ready( function( $ ) {
						$( '#btnsx-settings-reset' ).on( 'click', function( e ) {
							e.preventDefault();
							// $( '#btnsx-settings-form' ).clearForm();
							btnText = '<?php _e( "Reset", "btnsx" ); ?>';
							// disble the button to avoid multiple clicks
						  	$( this ).attr( 'disabled', 'disabled' ).html( '<i class="fa fa-refresh fa-spin"></i>' );
						  	var data = {
								'action'	: 'settings_reset',							// wp ajax action
								'security'	: '<?php echo $settings_nonce; ?>',			// nonce value created earlier
								'value'		: ''	// fetch form data
							};
							// fire ajax
						  	$.post( ajaxurl, data, function( response ) {
						  		// if nonce fails then not authorized else settings saved
						  		if( response === '-1' ){
							  		toast( '<?php _e( "Not Authorized!", "btnsx" ); ?>', '#btnsx-settings-reset', btnText );
						  		} else {
						  			toast( response, '#btnsx-settings-reset', btnText );
						  			location.reload();
						  			// console.log( response );
						  		}
						  	});
						});
						/**
						 * Function to clear all form data
						 * @since  0.1
						 * @return {}
						 */
						$.fn.clearForm = function() {
						  	return this.each( function() {
						    	var type = this.type, tag = this.tagName.toLowerCase();
						    	if (tag == 'form')
						      		return $( ':input', this).clearForm();
						    	if ( type == 'text' || type == 'password' || tag == 'textarea' )
						      		this.value = '';
						    	else if ( type == 'checkbox' || type == 'radio' )
						      		this.checked = false;
						    	else if ( tag == 'select' )
						      		this.selectedIndex = -1;
						  	});
						};

						$( '#btnsx-settings-submit' ).on( 'click', function( e ) {
							e.preventDefault();
							btnText = '<?php _e( "Save", "btnsx" ); ?>';
							css = $('input[name=css]:checked').val();
							var spinText = '';
							if( css == 'external' ){
								spinText = '<?php _e( "Generating External CSS..", "btnsx" ); ?>';
							}
							// disble the button to avoid multiple clicks
						  	$( this ).attr( 'disabled', 'disabled' ).html( '<i class="fa fa-refresh fa-spin"></i><span>'  + spinText + '</span>' );
						  	$( this ).after( '<span style="font-size:10px;color:#ababab;">'  + spinText + '</span>' );
							var data = {
								'action'	: 'settings_save',							// wp ajax action
								'security'	: '<?php echo $settings_nonce; ?>',			// nonce value created earlier
								'value'		: $( '#btnsx-settings-form' ).serialize(),		// fetch form data
								'tab'		: $( '#btnsx-tabs-0' ).find('nav ul .tab-current').index()
							};
							// fire ajax
						  	$.post( ajaxurl, data, function( response ) {
						  		// if nonce fails then not authorized else settings saved
						  		if( response === '-1' ){
							  		toast( '<?php _e( "Not Authorized!", "btnsx" ); ?>', '#btnsx-settings-submit', btnText );
						  		} else {
						  			toast( response, '#btnsx-settings-submit', btnText );
						  			location.reload(); // reload page
						  		}
						  	});
						});
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

		

		
	} // Ajax Class
}

/**
 *  Kicking this off
 */

$btn_options = new BtnsxAjax();
$btn_options->init();