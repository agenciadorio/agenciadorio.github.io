<?php
/**
 * Go Portfolio - WordPress Responsive Portfolio 
 *
 * @package   Go Portfolio - WordPress Responsive Portfolio 
 * @author    Granth <granthweb@gmail.com>
 * @link      http://granthweb.com
 * @copyright 2016 Granth
 */

/**
 * Class for creating meta boxes
 *
 * @package   Go Portfolio
 * @author    Granth <granthweb@gmail.com>
 */
 
class GW_Meta_Box {
	
	protected $id;
	protected $meta_box;

	/**
	 * Initialize the class
	 */
	
	public function __construct( $id=NULL, $title=NULL, $fields=NULL, $post_types=NULL, $context = 'normal', $priority = 'default', $autosave = true ) {		

		if ( $id == NULL || $title == NULL || $fields == NULL || $post_types == NULL ) return;
		$this->id=$id;
		$this->meta_box = array( 
			'id' => $id . '_options',
			'title' => $title,
			'page' => $post_types,
			'context' => $context,
			'priority' => $priority,
			'autosave' => $autosave,
			'fields' => $fields	
		);
		
		add_action( 'admin_menu', array( $this, 'add_custom_meta_box' ) );
		add_action( 'save_post', array( $this, 'save_custom_meta_box_data' ) );
		add_action( 'edit_attachment', array( $this, 'save_custom_meta_box_data' ) );

	}


	/**
	 * Add metabox
	 */	
	
	public function add_custom_meta_box() {

		if ( isset( $this->meta_box ) && !empty( $this->meta_box ) ) { 
			$meta_box = $this->meta_box;
			if ( is_array( $this->meta_box['page'] ) ) {
				foreach ( $this->meta_box['page'] as $meta_box_page ) {
					add_meta_box( $this->meta_box['id'], $this->meta_box['title'], array( $this, 'print_custom_meta_box' ), $meta_box_page, $this->meta_box['context'], $this->meta_box['priority'], array( 'id' => $this->meta_box['id'] ) );
				}
			} else {
				add_meta_box( $this->meta_box['id'], $this->meta_box['title'], array( $this, 'print_custom_meta_box' ), $this->meta_box['page'], $this->meta_box['context'], $this->meta_box['priority'], array( 'id' => $this->meta_box['id'] ) );
			}
		}
		
	}


	/**
	 * Print meta box
	 */

	public function print_custom_meta_box( $post, $metabox ) {

		if ( isset( $metabox ) && !empty( $metabox ) ) {		

			global $wp_version;
			
			if ( version_compare( $wp_version, 3.5, ">=" ) ) {
				wp_enqueue_media();
			} else {
				wp_enqueue_style( 'thickbox' );	
				wp_enqueue_script( 'thickbox' );
				wp_enqueue_script( 'media-upload' );
			}
						
			?>
			<script>
			(function ($, undefined) {
				"use strict";
				$(function () {
					
					/* Show & Hide data groups */
					var $gopfMetabox = $('#<?php echo $this->meta_box['id']; ?>');
					
					$gopfMetabox.find('.<?php echo $this->meta_box['id']; ?>-group').hide();
					$gopfMetabox.on('change', 'select[data-parent]:visible', function(e) {
						var $this=$(this);
						$gopfMetabox.find('.<?php echo $this->meta_box['id']; ?>-group[data-parent~="'+$this.data('parent')+'"]').hide();
						$gopfMetabox.find('.<?php echo $this->meta_box['id']; ?>-group[data-parent~="'+$this.data('parent')+'"][data-children~="'+$this.find(':selected').data('children')+'"]').show();
						$gopfMetabox.find('.<?php echo $this->meta_box['id']; ?>-group[data-parent~="'+$this.data('parent')+'"][data-children~="'+$this.find(':selected').data('children')+'"]:visible').find('select').trigger('change');
					});
					$gopfMetabox.find('select').trigger('change');
					
					/* Image upload */
					
					$gopfMetabox.on('click', '.img-upload', function(e) {
						var $this = $(this);
						e.preventDefault();
						
						/* New media uploader wp3.5+ */
						if ( typeof wp.media != 'undefined' ) {
						
							var file_frame = wp.media({
								title: 'Select an Image',
								library: {
									type: 'image'
								},
								button: {
									text: 'Insert Image'
								},
								multiple: false,
							});
						
							file_frame.on('select', function() {
								var selected = [];
								var selection = file_frame.state().get('selection');
																
								selected.push(file_frame.state().get('selection').first().toJSON());
								
								$this.closest('td').find('input').val(selected[0].url);			
							});
							
							file_frame.open();
						
						} else {
							/* Old media uploader */
							tb_show('', 'media-upload.php?post_id=0&amp;type=image&amp;TB_iframe=true');
							window.send_to_editor = function(html) {
								var $html=$('<div />', { 'class':'media-html', 'html': html });
									$this.closest('td').find('input').val($html.find('img').attr('src'));	
									tb_remove();			
							};			
						} 						
						
					});
					
				});
			}(jQuery));
			</script>
			<?php
		
			if ( $this->meta_box['id']==$metabox['args']['id'] ) {
				echo '<input type="hidden" name="' . $this->meta_box['id'] . '_nonce" value="' . wp_create_nonce( basename( __FILE__ ) ) . '" />',
					 '<table class="form-table">';
	
				foreach ( $this->meta_box['fields'] as $field ) {
					$meta = get_post_meta( $post->ID, $field['id'], true );
					echo '<tr' . ( isset( $field['wrapper-data-parent'] ) && !empty( $field['wrapper-data-parent'] ) && isset( $field['wrapper-data-children'] ) && !empty( $field['wrapper-data-children'] ) ? ' class="' . $this->meta_box['id'] . '-group" data-parent="' . $field['wrapper-data-parent'] . '" data-children="' . $field['wrapper-data-children'] . '"' : '' ) . '>';
					echo '<th style="width:25%"><label for="'. $field['id']. '">'. $field['name']. '</label></th>';
					echo '<td  style="width:300px" valign="top">';
					
					switch ( $field['type'] ) {
						case 'text':
							echo '<input type="text" name="' . $field['id'] . '" id="' . $field['id']. '" value="' . ( $meta ? $meta : $field['default'] ) . '" class="' . $field['class'] . '" />';
							break;
						
						case 'textarea':
							echo '<textarea name="' . $field['id'] . '" id="' . $field['id'] . '" cols="60" rows="4" style="width:25em;">' . ( $meta ? $meta : $field['default'] ) . '</textarea>';
							break;
						
						case 'select':
							echo '<select name="' . $field['id'] . '" id="' . $field['id'] . '" class="'.$field['class'].'"' . ( isset( $field['data-parent'] ) && !empty( $field['data-parent'] ) ? ' data-parent="' . $field['data-parent'] . '"' : '' ) . '>';
							foreach ( $field['options'] as $option ) {
								echo '<option ' . ( isset( $option['data-children'] ) && !empty( $option['data-children'] ) ? ' data-children="' . $option['data-children'] . '"' : '' ) . ' value="' . $option['value'] . '"' . ( $meta == $option['value'] ? ' selected="selected"' : '' ) . '>' . $option['name'] . '</option>';
							}
							echo '</select>';
							break;
						
						case 'radio':
							foreach ( $field['options'] as $option ) {
								echo '<input type="radio" name="' . $field['id'] . '" value="' . $option['value'] . '"' . ( $meta == $option['value'] ? ' checked="checked"' : '' ) . ' />' . $option['name'];
							}
							break;
						
						case 'checkbox':
							echo '<input type="checkbox" name="' . $field['id'] . '" id="' . $field['id'] . '"' . ( $meta ? ' checked="checked"' : '' ) . ' />';
							break;
							
						case 'img-upload':
						 	if ( is_array( $meta ) ) {
								foreach ( $meta as $meta_item ) {
									echo '<input type="text" name="' . $field['id'] . '[]" id="' . $field['id']. '" value="' . ( $meta_item ? $meta_item : $field['default'] ) . '" class="' . $field['class'] . '" /><a href="#" class="img-upload button-secondary">' . __( 'Upload', 'go_portfolio_textdomain' ) . '</a>';
								}
							} else {
								echo '<input type="text" name="' . $field['id'] . '[]" id="' . $field['id']. '" value="' . ( $meta ? $meta : $field['default'] ) . '" class="' . $field['class'] . '" /><a href="#" class="img-upload button-secondary">' . __( 'Upload', 'go_portfolio_textdomain' ) . '</a>';
							}
							break;			
							
					}
					
					echo '</td>';
					echo '<td valign="top"><span class="description">' . $field['desc'] . '</span></td>';
					echo '</tr>';
				}
	
				echo '<tr><th></th><td colspan="3" valign="top" align="left"><input name="save" type="submit" class="button-primary" value="' . esc_attr__( 'Update', 'go_portfolio_textdomain' ) . '"></td></tr></table>';
			}
			
		}
		
	}


	/**
	 * Save meta box
	 */

	public function save_custom_meta_box_data( $post_id ) {
	
		if ( isset( $this->meta_box ) && !empty( $this->meta_box ) ) {
			if ( isset( $_POST[$this->meta_box['id'] . '_nonce'] ) ) {
				if ( !wp_verify_nonce( $_POST[$this->meta_box['id'] . '_nonce'], basename( __FILE__ ) ) ) { return $post_id; }
			 
				if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return $post_id; }
				
				if ( 'page' == $_POST['post_type'] ) {
					if ( !current_user_can( 'edit_page', $post_id ) ) {
						return $post_id;
					}
				} elseif ( !current_user_can( 'edit_post', $post_id ) ) {
					return $post_id;
				}
				
				foreach ( $this->meta_box['fields'] as $field ) {
					$old = get_post_meta( $post_id, $field['id'], true );
					$new = isset( $_POST[$field['id']] ) ? $_POST[$field['id']] : NULL;
					if ( $new && $new != $old ) {
						update_post_meta( $post_id, $field['id'], $new );
					} elseif ( '' == $new && $old ) {
						delete_post_meta( $post_id, $field['id'], $old );
					}
				}
			}
		}
		
	}
	
	
}