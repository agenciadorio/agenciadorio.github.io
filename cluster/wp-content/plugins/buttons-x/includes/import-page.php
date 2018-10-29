<?php

// Make sure we don't expose any info if called directly
if ( !defined( 'ABSPATH' ) )
	exit;

	include_once 'page-header.php';
	$form = new BtnsxFormElements();
?>
	<div class="" style="margin-right: 20px;background-color:#fcfcfc;">
		<!-- Page Content goes here -->
		<div class="row">
			<?php
			ob_start();
			?>
				<div class="col s12">
					<h6><?php _e('Select the predefined button styles to import','buttons-x'); ?></h6>
				</div>
			<?php
			$html_head = ob_get_clean();
	    	ob_start();
			?>
				<div class="col s12">
					<br>
					<button id="btnsx-click-import" class="btn btn-import btn-settings"><?php _e( 'Import', 'buttons-x' ); ?></button>
				</div>
			<?php
			$html = ob_get_clean();
			?>
			<div class="col l9 m12" style="padding:0;">
				<?php
					$btnsx_default_options = array(
						array(
		        			'icon_class'	=>	'fa fa-star',
		        			'text'			=>	__( 'Demo Buttons', 'buttons-x' ),
		        			'elements'		=> array(
		        				array(
									'type'			=>	'html',
									'value'			=>	$html_head
								),
		        				array(
		        					'type'			=>	'checkbox',
		        					'id'			=>	'btnsx_opt_predefined_style_classic',
		        					'name'			=>	'btnsx_opt_predefined_style[]',
		        					'label'			=>	__( 'Classic', 'buttons-x' ),
		        					'value'			=>	'3208'
		        				),
		        				array(
		        					'type'			=>	'checkbox',
		        					'id'			=>	'btnsx_opt_predefined_style_flat',
		        					'name'			=>	'btnsx_opt_predefined_style[]',
		        					'label'			=>	__( 'Flat', 'buttons-x' ),
		        					'value'			=>	'1259'
		        				),
		        				array(
		        					'type'			=>	'checkbox',
		        					'id'			=>	'btnsx_opt_predefined_style_gradient',
		        					'name'			=>	'btnsx_opt_predefined_style[]',
		        					'label'			=>	__( 'Gradient', 'buttons-x' ),
		        					'value'			=>	'3804'
		        				),
		        				array(
		        					'type'			=>	'checkbox',
		        					'id'			=>	'btnsx_opt_predefined_style_outlined',
		        					'name'			=>	'btnsx_opt_predefined_style[]',
		        					'label'			=>	__( 'Outlined', 'buttons-x' ),
		        					'value'			=>	'3770'
		        				),
		        				array(
									'type'			=>	'html',
									'value'			=>	$html
								)
		        			)
		        		),
		        		array(
		        			'icon_class'	=>	'fa fa-upload',
		        			'text'			=>	__( 'Manual Import', 'buttons-x' ),
		        			'elements'		=> array(
								array(
									'type'			=>	'pro-banner'
			    				)
							),
						)
			        );
					// filter to add custom options
		        	$btnsx_filtered_options = apply_filters( 'btnsx_import_filter', array() );
		        	$btnsx_options = wp_parse_args( $btnsx_filtered_options, $btnsx_default_options );
		        	$btnsx_form_design->tabs(
		        		array(
		        			'id'			=>	'btnsx-tabs-0',
		        			'outer_group'	=>	$btnsx_options
		        		)
		        	);
				?>
			</div>
			<div class="col l3 m12">
				<div class="help-links" style="padding: 10px;">
		      		<p><?php _e( 'Helpful Links:', 'buttons-x' ); ?></p>
			        <ul>
			        	<li><a href="https://www.button.sx/product-category/add-ons/"><?php _e( 'Button Add-ons', 'buttons-x' ); ?></a></li>
			        	<li><a target="_blank" href="https://www.button.sx/product-category/packs/"><?php _e( 'Button Packs', 'buttons-x' ); ?></a></li>
			        	<li><a target="_blank" href="https://gautamthapar.atlassian.net/wiki/display/BX/"><?php _e( 'Documentation', 'buttons-x' ); ?></a></li>
			        	<li><a target="_blank" href="http://gautamthapar.ticksy.com"><?php _e( 'Pro Support', 'buttons-x' ); ?></a></li>
			        	<li><a target="_blank" href="https://www.button.sx/"><?php _e('Official Website','buttons-x'); ?></a></li>
			        	<li><a target="_blank" href="https://twitter.com/Gautam_Thapar"><?php _e('Twitter','buttons-x'); ?></a></li>
			        </ul>
			        <br>
				    <a href="https://codecanyon.net/item/buttons-x-powerful-button-builder-for-wordpress/12710619?ref=GautamThapar" style="font-weight:700;"><?php _e('GET PRO VERSION','btnsx'); ?></a>
				   	<br><br>
				   	<p style="color:#555d66;font-size:13px;"><?php echo sprintf( wp_kses(__( 'Give us a <a href="%s">*****</a> rating!', 'buttons-x'), array( 'a' => array( 'href' => array() ) ) ), 'https://wordpress.org/support/plugin/buttons-x/reviews/?rate=5#new-post' ); ?></p>
			    </div>
			</div>
		</div>
	</div>

</div>