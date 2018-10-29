<?php
/**
 * Buttons X Form Elements
 *
 * This file is used to output form elements.
 *
 * @package Buttons X
 * @since 0.1
 */
// Make sure we don't expose any info if called directly
if ( !defined( 'ABSPATH' ) )
	exit;

if( !class_exists( 'BtnsxFormDesign' ) ) {
	
	class BtnsxFormDesign {

		/**
		 * Generate tabs
		 * @since  0.1
		 * @param  array     $args 	existing options array
		 * @return array 	all standard options
		 */
		public function tabs( $args = array() ) {

			$defaults = array(
				'id'				=>	NULL,
				'show_group'		=>	false,
				'outer_group'		=>	array(
					array(
						'icon_class'	=>	NULL,
						'text'			=>	NULL,
						'id'			=>	NULL,
						'group'			=>	'advanced', // beginner, advanced, expert
						'inner_group'	=>	array(
							array(
								'text'				=>	NULL,
								'icon_class'		=>	NULL,
								'copy'				=>	FALSE,
								'copy_text'			=>	NULL,
								'elements'		=>	array(
									array(
										'type'			=>	'text',
										'id'			=>	'btnsx_opt_id',
										'placeholder'	=>	'',
										'label'			=>	'ID'
									)
								)
							)
						)
					)
				)
			);

			// Merge arguments together 
			$tabs = wp_parse_args( $args, $defaults );

			$tabs_prop = array();
			$tabs_prop['id'] = ( $tabs['id'] != NULL ) ? 'id="' . esc_attr( $tabs['id'] ) . '"' : NULL;

			?>
			
			<div <?php echo $tabs_prop['id']; ?> class="btnsx btnsx-tabs" style="display:none;">
				<div class="row btnsx-bg-gray">
					<div class="col s3 no-padding full-bg-color">
						<nav>
							<ul>
								<?php 
									global $post; $current_tab = ''; $current_tab_content = ''; $meta = ''; $display_1 = 'display:block;'; $display_2 = 'display:block;'; $display_3 = 'display:block;'; $display_4 = 'display:block;';
									if( isset( $post ) ){
										$meta = get_post_meta( $post->ID, 'btnsx', true );
										if( $meta != '' ){
											$current_tab = $meta['btnsx_tab'];
											// $current_tab_content = $meta['btnsx_tab_content'];
											$content_val = $meta['btnsx_tab_group_content'];
											$style_val = $meta['btnsx_tab_group_style'];
											$adv_val = $meta['btnsx_tab_group_advanced'];
											$exp_val = $meta['btnsx_tab_group_expert'];
											if( $content_val == '1' ) {
												$display_1 = 'display:none;';
											}
											$style_val = $meta['btnsx_tab_group_style'];
											if( $style_val == '1' ) {
												$display_2 = 'display:none;';
											}
											$adv_val = $meta['btnsx_tab_group_advanced'];
											if( $adv_val == '1' ) {
												$display_3 = 'display:none;';
											}
											$exp_val = $meta['btnsx_tab_group_expert'];
											if( $exp_val == '1' ) {
												$display_4 = 'display:none;';
											}
										}
									}
									function btnsx_nav_sort( array $sorted, $tabs_id, $i, $group_display ) {
										$output = ''; $j = 0;
										foreach ( $sorted as $og ) {
											if( isset( $og ) && $og != NULL ){
												$og['id'] = $tabs_id . '-' . $i . '-' . $j;
												$tab_id = $og['id'] . '-' . esc_attr( str_replace( " ", "-", $og['text'] ) );
												$output .= '<li id="' . esc_attr( $tab_id ) . '" style="'.$group_display.'">';
												$output .= '<a href="' . esc_attr( $og['id'] ) . '" class="' . esc_attr( $og['icon_class'] ) . '"><span>' . sanitize_text_field( $og['text'] ) . '</span></a>';
												$output .= '</li>';
												$j++; // increase tab id by 1
											}
										}
										echo $output;
									}
									function btnsx_content_sort( array $sorted, $tabs_id, $i ) {
										$output = ''; $j = 0;
										foreach ( $sorted as $og ) {
											$og['id'] = $tabs_id . '-' . $i . '-' . $j;
											echo '<section id="' . esc_attr( $og['id'] ) . '">';
											echo '<ul id="" class="collapsible" data-collapsible="custom">';
											// proceed only if elements are not empty
											if( isset( $og['elements'] ) && $og['elements'] != NULL ){
												echo '<div class="row btnsx-collapsible-content-padding">';
												// for each element in outer group generate form fields (outside collapsibles)
												foreach ( $og['elements'] as $el ) {
													$btnsx_form = new BtnsxFormElements();
											 		$btnsx_form->input(
														array(
															'type'			=>	( isset( $el['type'] ) ) ? $el['type'] : NULL,
															'cpt'			=>	( isset( $el['cpt'] ) ) ? $el['cpt'] : NULL,
															'id'			=>	( isset( $el['id'] ) ) ? $el['id'] : NULL,
															'placeholder'	=>	( isset( $el['placeholder'] ) ) ? $el['placeholder'] : NULL,
															'label'			=>	( isset( $el['label'] ) ) ? $el['label'] : NULL,
															'tooltip'		=>	( isset( $el['tooltip'] ) ) ? $el['tooltip'] : NULL,
															'name'			=>	( isset( $el['name'] ) ) ? $el['name'] : NULL,
															'class'			=>	( isset( $el['class'] ) ) ? $el['class'] : NULL,
															'multiselect'	=>	( isset( $el['multiselect'] ) ) ? $el['multiselect'] : NULL,
															'title'			=>	( isset( $el['title'] ) ) ? $el['title'] : NULL,
															'min'			=>	( isset( $el['min'] ) ) ? $el['min'] : NULL,
															'max'			=>	( isset( $el['max'] ) ) ? $el['max'] : NULL,
															'step'			=>	( isset( $el['step'] ) ) ? $el['step'] : NULL,
															'on_text'		=>	( isset( $el['on_text'] ) ) ? $el['on_text'] : __( 'On', 'buttons-x' ),
															'off_text'		=>	( isset( $el['off_text'] ) ) ? $el['off_text'] : __( 'Off', 'buttons-x' ),
															'data'			=>	( isset( $el['data'] ) ) ? $el['data'] : NULL,
															'value'			=>	( isset( $el['value'] ) ) ? $el['value'] : NULL,
															'options'		=>	( isset( $el['options'] ) ) ? $el['options'] : array(),
															'copy'			=>	( isset( $el['copy'] ) ) ? $el['copy'] : NULL,
															'copy_text'		=>	( isset( $el['copy_text'] ) ) ? $el['copy_text'] : NULL,
															'copy_ids'		=>	( isset( $el['copy_ids'] ) ) ? $el['copy_ids'] : array(),
															'pro'			=>	( isset( $el['pro'] ) ) ? $el['pro'] : FALSE,
														)
													);
												}
												echo '</div>';
											}
											// proceed only if elements are not empty
											if( isset( $og['inner_group'] ) && $og['inner_group'] != NULL ){
												// generate form fields inside collapsibles
												foreach ( $og['inner_group'] as $ig ) {
													$ig['id'] = isset( $ig['id'] ) ? $ig['id'] : '';
													$collapsible_header = isset( $ig['id'] ) ? $ig['id'] . '_header' : '';
													$collapsible_body = isset( $ig['id'] ) ? $ig['id'] . '_body' : '';
													$copy_btn = isset( $ig['id'] ) ? $ig['id'] . '_copy_btn' : '';
													$multiple = isset( $ig['multiple'] ) && $ig['multiple'] === true ? true : false;
													$multiple_btn_class = isset( $ig['multiple_btn_class'] ) ? $ig['multiple_btn_class'] : '';
													$copy = isset( $ig['copy'] ) && $ig['copy'] === true ? true : false;
													?>
													<li id="<?php echo esc_attr( $ig['id'] ); ?>" class="<?php echo isset( $ig['clone_class'] ) ? esc_attr( $ig['clone_class'] ) : ''; ?>">
														<?php if( $multiple === true && $copy === false || $multiple === false && $copy === true ): ?>

														<div class="row">
															<div class="col m10" style="padding:0;">
																<div id="<?php echo esc_attr( $collapsible_header ); ?>" id="<?php echo esc_attr( $ig['elements'][0]['id'] ) . '_collapsible_header'; ?>" class="collapsible-header" style=""><i class=""></i><?php echo sanitize_text_field( $ig['text'] ); ?>
																</div>
															</div>
															<div class="col m2" style="padding:0;">
																<button id="<?php echo esc_attr( $copy_btn ); ?>" class="<?php echo isset( $ig['multiple'] ) ? 'btnsx-duplicate btnsx-btn-add' : ''; echo isset( $ig['copy'] ) ? ' btnsx-copy ' : ''; echo isset( $ig['clone_class'] ) ? ' btn-' . esc_attr( $ig['clone_class'] ) : ''; ?>" data-highlight="<?php echo isset( $ig['copy_ids']['highlight'] ) ? $ig['copy_ids']['highlight'] : ''; ?>" data-old-input="<?php echo isset( $ig['copy_ids']['old_input'] ) ? $ig['copy_ids']['old_input'] : ''; ?>" data-new-input="<?php echo isset( $ig['copy_ids']['new_input'] ) ? $ig['copy_ids']['new_input'] : ''; ?>" data-old-select="<?php echo isset( $ig['copy_ids']['old_select'] ) ? $ig['copy_ids']['old_select'] : ''; ?>" data-new-select="<?php echo isset( $ig['copy_ids']['new_select'] ) ? $ig['copy_ids']['new_select'] : ''; ?>" data-old-color="<?php echo isset( $ig['copy_ids']['old_color'] ) ? $ig['copy_ids']['old_color'] : ''; ?>" data-new-color="<?php echo isset( $ig['copy_ids']['new_color'] ) ? $ig['copy_ids']['new_color'] : ''; ?>"><?php echo isset( $ig['multiple'] ) ? __( 'Add More', 'buttons-x' ) : ''; echo isset( $ig['copy_text'] ) && $ig['copy_text'] === 'normal' ? __( 'Copy to normal', 'buttons-x' ) : ''; echo isset( $ig['copy_text'] ) && $ig['copy_text'] === 'hover' ? __( 'Copy to hover', 'buttons-x' ) : ''; ?></button>
															</div>
														</div>

														<?php elseif( $multiple === true && $copy === true ): ?>

														<div class="row">
															<div class="col m8" style="padding:0;">
																<div id="<?php echo esc_attr( $collapsible_header ); ?>" id="<?php echo esc_attr( $ig['elements'][0]['id'] ) . '_collapsible_header'; ?>" class="collapsible-header" style=""><i class=""></i><?php echo sanitize_text_field( $ig['text'] ); ?>
																</div>
															</div>
															<div class="col m2" style="padding:0;">
																<button id="<?php echo esc_attr( $copy_btn ); ?>" class="<?php echo isset( $ig['copy'] ) ? ' btnsx-copy ' : ''; ?>" data-highlight="<?php echo isset( $ig['copy_ids']['highlight'] ) ? $ig['copy_ids']['highlight'] : ''; ?>" data-old-input="<?php echo isset( $ig['copy_ids']['old_input'] ) ? $ig['copy_ids']['old_input'] : ''; ?>" data-new-input="<?php echo isset( $ig['copy_ids']['new_input'] ) ? $ig['copy_ids']['new_input'] : ''; ?>" data-old-select="<?php echo isset( $ig['copy_ids']['old_select'] ) ? $ig['copy_ids']['old_select'] : ''; ?>" data-new-select="<?php echo isset( $ig['copy_ids']['new_select'] ) ? $ig['copy_ids']['new_select'] : ''; ?>" data-old-color="<?php echo isset( $ig['copy_ids']['old_color'] ) ? $ig['copy_ids']['old_color'] : ''; ?>" data-new-color="<?php echo isset( $ig['copy_ids']['new_color'] ) ? $ig['copy_ids']['new_color'] : ''; ?>"><?php echo isset( $ig['copy_text'] ) && $ig['copy_text'] === 'normal' ? __( 'Copy to normal', 'buttons-x' ) : ''; echo isset( $ig['copy_text'] ) && $ig['copy_text'] === 'hover' ? __( 'Copy to hover', 'buttons-x' ) : ''; ?></button>
															</div>
															<div class="col m2" style="padding:0;">
																<button id="<?php echo esc_attr( $copy_btn ); ?>" class="<?php echo isset( $ig['multiple'] ) ? 'btnsx-duplicate btnsx-btn-add' : ''; echo isset( $ig['clone_class'] ) ? ' btn-' . esc_attr( $ig['clone_class'] ) : ''; ?>"><?php echo isset( $ig['multiple'] ) ? __( 'Add More', 'buttons-x' ) : ''; ?></button>
															</div>
														</div>
														
														<?php else: ?>
															<div id="<?php echo esc_attr( $collapsible_header ); ?>" class="collapsible-header"><i class=""></i><?php echo sanitize_text_field( $ig['text'] ); ?>
																</div>
														<?php endif; ?>
														<div id="<?php echo esc_attr( $collapsible_body ); ?>" class="collapsible-body">
															<div class="row btnsx-collapsible-content-padding">
															<?php 
															if( isset($ig['elements']) && $ig['elements'] != NULL ){
																foreach ( $ig['elements'] as $el ) {
																	$btnsx_form = new BtnsxFormElements();
																	$btnsx_form->input(
																		array(
																			'type'			=>	( isset( $el['type'] ) ) ? $el['type'] : NULL,
																			'cpt'			=>	( isset( $el['cpt'] ) ) ? $el['cpt'] : NULL,
																			'id'			=>	( isset( $el['id'] ) ) ? $el['id'] : NULL,
																			'placeholder'	=>	( isset( $el['placeholder'] ) ) ? $el['placeholder'] : NULL,
																			'label'			=>	( isset( $el['label'] ) ) ? $el['label'] : NULL,
																			'tooltip'		=>	( isset( $el['tooltip'] ) ) ? $el['tooltip'] : NULL,
																			'name'			=>	( isset( $el['name'] ) ) ? $el['name'] : NULL,
																			'class'			=>	( isset( $el['class'] ) ) ? $el['class'] : NULL,
																			'multiselect'	=>	( isset( $el['multiselect'] ) ) ? $el['multiselect'] : NULL,
																			'title'			=>	( isset( $el['title'] ) ) ? $el['title'] : NULL,
																			'min'			=>	( isset( $el['min'] ) ) ? $el['min'] : NULL,
																			'max'			=>	( isset( $el['max'] ) ) ? $el['max'] : NULL,
																			'step'			=>	( isset( $el['step'] ) ) ? $el['step'] : NULL,
																			'on_text'		=>	( isset( $el['on_text'] ) ) ? $el['on_text'] : __( 'On', 'buttons-x' ),
																			'off_text'		=>	( isset( $el['off_text'] ) ) ? $el['off_text'] : __( 'Off', 'buttons-x' ),
																			'data'			=>	( isset( $el['data'] ) ) ? $el['data'] : NULL,
																			'value'			=>	( isset( $el['value'] ) ) ? $el['value'] : NULL,
																			'options'		=>	( isset( $el['options'] ) ) ? $el['options'] : array(),
																			'copy'			=>	( isset( $el['copy'] ) ) ? $el['copy'] : NULL,
																			'copy_text'		=>	( isset( $el['copy_text'] ) ) ? $el['copy_text'] : NULL,
																			'copy_ids'		=>	( isset( $el['copy_ids'] ) ) ? $el['copy_ids'] : array(),
																			'pro'			=>	( isset( $el['pro'] ) ) ? $el['pro'] : FALSE
																		)
																	);
																}
															}
															?>
															</div>
														</div>
													</li>
														
											<?php
												}
											}
											echo '</ul></section>';
											$j++;
										}
									}
									$difficulty = array();
									foreach ( $tabs['outer_group'] as $ease ) {
										if( !isset( $ease['group'] ) ) {
											$ease['group'] = 'advanced';
										}
										$difficulty[ $ease['group'] ][] = $ease;
									}
									// beginner
									$i = 0;
									foreach( $difficulty as $k => $v ){
										if( $k === 'content' ){
											if( $tabs['show_group'] === true ) {
												echo '<span id="btnsx-content-options" class="btnsx-tabs-group"><a href="javascript:void()" class=""><span>' . __( 'Content', 'buttons-x' ) . '</span></a></span>';
											}
											$sorted = array();
											// Let's make our array ready for sorting
											foreach ( $v as $sort ) {
												$sorted[ $sort['text'] ] = $sort;
											}
											// sort array based on key
											ksort( $sorted );
											// Output tab heads
											btnsx_nav_sort( $sorted, $tabs['id'], $i, $display_1 );
											$i++;
										}
									}
									// style
									foreach( $difficulty as $k => $v ){
										if( $k === 'style' ){
											if( $tabs['show_group'] === true ) {
												echo '<span id="btnsx-style-options" class="btnsx-tabs-group"><a href="javascript:void()" class=""><span>' . __( 'Style', 'buttons-x' ) . '</span></a></span>';
											}
											$sorted = array();
											// Let's make our array ready for sorting
											foreach ( $v as $sort ) {
												$sorted[ $sort['text'] ] = $sort;
											}
											// sort array based on key
											ksort( $sorted );
											// Output tab heads
											btnsx_nav_sort( $sorted, $tabs['id'], $i, $display_2 );
											$i++;
										}
									}
									// advanced
									foreach( $difficulty as $k => $v ){
										if( $k === 'advanced' ){
											if( $tabs['show_group'] === true ) {
												echo '<span id="btnsx-advanced-options" class="btnsx-tabs-group"><a href="javascript:void()" class=""><span>' . __( 'Advanced', 'buttons-x' ) . '</span></a></span>';
											}
											$sorted = array();
											// Let's make our array ready for sorting
											foreach ( $v as $sort ) {
												$sorted[ $sort['text'] ] = $sort;
											}
											// sort array based on key
											ksort( $sorted );
											// Output tab heads
											btnsx_nav_sort( $sorted, $tabs['id'], $i, $display_3 );
											$i++;
										}
									}
									// expert
									foreach( $difficulty as $k => $v ){
										if( $k === 'expert' ){
											if( $tabs['show_group'] === true ) {
												echo '<span id="btnsx-expert-options" class="btnsx-tabs-group"><a href="javascript:void()" class=""><span>' . __( 'Expert', 'buttons-x' ) . '</span></a></span>';
											}
											$sorted = array();
											// Let's make our array ready for sorting
											foreach ( $v as $sort ) {
												$sorted[ $sort['text'] ] = $sort;
											}
											// sort array based on key
											ksort( $sorted );
											// Output tab heads
											btnsx_nav_sort( $sorted, $tabs['id'], $i, $display_4 );
											$i++;
										}
									}
								?>
							</ul>
						</nav>
					</div>
					<div class="col s9 no-padding">
						<div class="content">
						<?php
							$difficulty = array();
							foreach( $tabs['outer_group'] as $ease ) {
								if( !isset( $ease['group'] ) ) {
									$ease['group'] = 'advanced';
								}
								$difficulty[ $ease['group'] ][] = $ease;
							}
							$i = 0; 
							// beginner
							foreach( $difficulty as $k => $v ){
								if( $k === 'content' ){
									$sorted = array();
									// Let's make our array ready for sorting
									foreach ( $v as $sort ) {
										$sorted[$sort['text']] = $sort;
									}
									// sort array based on key
									ksort( $sorted );
									// for each outer group value create option tabs content
									btnsx_content_sort( $sorted, $tabs['id'], $i );
									$i++;
								}
							}
							// style
							foreach( $difficulty as $k => $v ){
								if( $k === 'style' ){
									$sorted = array();
									// Let's make our array ready for sorting
									foreach ( $v as $sort ) {
										$sorted[$sort['text']] = $sort;
									}
									// sort array based on key
									ksort( $sorted );
									// for each outer group value create option tabs content
									btnsx_content_sort( $sorted, $tabs['id'], $i );
									$i++;
								}
							}
							// advanced
							foreach( $difficulty as $k => $v ){
								if( $k === 'advanced' ){
									$sorted = array();
									// Let's make our array ready for sorting
									foreach ( $v as $sort ) {
										$sorted[$sort['text']] = $sort;
									}
									// sort array based on key
									ksort( $sorted );
									// for each outer group value create option tabs content
									btnsx_content_sort( $sorted, $tabs['id'], $i );
									$i++;
								}
							}
							// expert
							foreach( $difficulty as $k => $v ){
								if( $k === 'expert' ){
									$sorted = array();
									// Let's make our array ready for sorting
									foreach ( $v as $sort ) {
										$sorted[$sort['text']] = $sort;
									}
									// sort array based on key
									ksort( $sorted );
									// for each outer group value create option tabs content
									btnsx_content_sort( $sorted, $tabs['id'], $i );
									$i++;
								}
							}
						?>
						</div><!--  /content -->
					</div>	
				</div>
			</div><!-- /tabs -->

			<?php

				if( $tabs['id'] != NULL ) { ?>
					<script type="text/javascript">
						// initialize option tabs
						new CBPFWTabs( document.getElementById( <?php echo '"' . esc_attr( $tabs['id'] ) . '"'; ?> ) );
						// jQuery function for media uploader
					    var btnsxUpload = function (id) {
					        // Uploading files
					        var file_frame;

					        //event.preventDefault();

					        // If the media frame already exists, reopen it.
					        if (file_frame) {
					            // Open frame
					            file_frame.open();
					            return;
					        }

					        // Create the media frame.
					        file_frame = wp.media.frames.file_frame = wp.media({
					            title: jQuery(this).data('uploader_title'),
					            button: {
					                text: jQuery(this).data('uploader_button_text')
					            },
					            multiple: false  // Set to true to allow multiple files to be selected
					        });

					        // When an image is selected, run a callback.
					        file_frame.on('select', function () {
					            // We set multiple to false so only get one image from the uploader
					            var attachment = file_frame.state().get('selection').first().toJSON();

					            // Do something with attachment.id and/or attachment.url here
					            // console.log(jQuery(id).length);
					            jQuery(id).val(attachment.url).trigger('propertychange','keyup','input');

					            // jQuery(document).find('body').addClass('modal-open');
					        });

					        // Finally, open the modal
					        file_frame.open();
					    };
						jQuery(document).ready(function($) {
							
							// initialize select fields
							$('.btnsx-select').select2({
								placeholder: {
								    id: '-1',
								    placeholder: '<?php _e( "Select an option", "btnsx" ); ?>'
								}
							});
							function formatState (icon) {
								if (!icon.id) { return icon.text; }
									var $state = $(
										'<i class="'+ icon.text.toLowerCase() + '" title="' + icon.text + '" style="font-size:1.2rem;">'
									);
								return $state;
							};
							$("#btnsx_opt_icon,#btnsx_opt_dual_divider_icon").select2({
								templateResult: formatState,
								templateSelection: formatState,
								escapeMarkup: function(m) { return m; }
							});
							// initiate file upload
							$('#btnsx_opt_icon_image_upload').on('click',function(e){
								e.preventDefault();
								btnsxUpload('#btnsx_opt_icon_image');
							});
							$('#btnsx_opt_icon_image_hover_upload').on('click',function(e){
								e.preventDefault();
								btnsxUpload('#btnsx_opt_icon_image_hover');
							});
							// initiate file upload
							$('#btnsx_opt_background_image_normal_upload').on('click',function(e){
								e.preventDefault();
								btnsxUpload('#btnsx_opt_background_image_normal');
							});
							// initiate file upload
							$('#btnsx_opt_background_image_hover_upload').on('click',function(e){
								e.preventDefault();
								btnsxUpload('#btnsx_opt_background_image_hover');
							});
							// initiate file upload
							$('#btnsx_opt_preview_background_image_image_upload').on('click',function(e){
								e.preventDefault();
								btnsxUpload('#btnsx_opt_preview_background_image_image');
							});
							// initiate file upload
							$('#btnsx_opt_dual_preview_background_image_image_upload').on('click',function(e){
								e.preventDefault();
								btnsxUpload('#btnsx_opt_dual_preview_background_image_image');
							});
							// Checkbox Values
							$('.btnsx-checkbox').each(function(){
								$(this).on('change',function(){
									if( $(this).is(':checked') ){
							    		$(this).val(1);
							    	} else {
							    		$(this).val(0);
							    	}
							    });
							});
						});
					</script>
				<?php }

		}

	} // Form Design Class

}