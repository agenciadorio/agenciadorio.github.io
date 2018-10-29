<?php
/**
 * Submenu page for in admin area
 * Template & Style Editor Page 
 *
 * @package   Go Portfolio - WordPress Responsive Portfolio 
 * @author    Granth <granthweb@gmail.com>
 * @link      http://granthweb.com
 * @copyright 2016 Granth
 */
 
$screen = get_current_screen();

/* Get templates & styles db data */
$templates = get_option( self::$plugin_prefix . '_templates' );
$styles = get_option( self::$plugin_prefix . '_styles' );

/* Handle post */
if ( !empty( $_POST ) && check_admin_referer( $this->plugin_slug . basename( __FILE__ ), $this->plugin_slug . '-nonce' ) ) {

	$reponse = array();
	$referrer=$_POST['_wp_http_referer'];
	 
	/* Clean post fields */
	$_POST = go_portfolio_clean_input( $_POST, 
		array( 
			'item-data'
		),
		array(
			'go-portfolio-nonce',
			'_wp_http_referer',
		)
	);

	/* Default Page POST */
	if ( isset( $_POST['action-type'] ) ) {

		/* Import action */
		if ( $_POST['action-type'] == 'import' ) {
			
			$new=0;
			
			/* Load the template files and save to db if it's new */
			$imported_templates = $templates;				
			$new_templates = self::load_templates();

			if ( isset( $new_templates ) && !empty ( $new_templates ) ) {
				foreach ( $new_templates as  $ntkey => $new_template ) {
					if ( !isset( $imported_templates[$ntkey] ) ) {	
						$imported_templates[$ntkey] = $new_template;
					}
				}
			}
			if ( $imported_templates != $templates ) { 
				$new++; 
				update_option( self::$plugin_prefix . '_templates', $imported_templates );
			}
		
			/* Load the style files and save to db if it's new  */
			$imported_styles = $styles;
			$new_styles = self::load_styles();
			if ( isset( $new_styles ) && !empty ( $new_styles ) ) {
				foreach ( $new_styles as  $nskey => $new_style ) {
					if ( !isset( $imported_styles ) ) {	
						$imported_styles[$ntkey] = $new_style;
					}
				}
			}

			if ( $imported_styles != $styles ) { 
				$new++; 
				update_option( self::$plugin_prefix . '_styles', $imported_styles );
			}
	
			/* Set the reponse message */
			if ( $new>0 ) {
				$response['result'] = 'success';
				$response['message'][] = __( 'New Templates & Styles has been successfully imported.', 'go_portfolio_textdomain' );
			} else {
				$response['result'] = 'error';
				$response['message'][] = __( 'No new templates or styles has been found.', 'go_portfolio_textdomain' );				
			}

			set_transient( md5( $screen->id . '-response' ), $response, 30 );			
			
			/* Redirect */
			wp_redirect( admin_url( 'admin.php?page=' . $_GET['page'] . '&updated=true' ) );
			exit;			

		/* Reset action */
		} elseif ( $_POST['action-type'] == 'reset' ) {

			/* Load the template files and save to db */
			$new_templates = self::load_templates();
			if ( $new_templates ) { 
				update_option( self::$plugin_prefix . '_templates', $new_templates );
			}
	
			/* Load the style files and save to db */
			$new_styles = self::load_styles();
			if ( $new_styles ) { 
				update_option( self::$plugin_prefix . '_styles', $new_styles );
				self::generate_styles();
			}	

			/* Set the reponse message */
			$response['result'] = 'success';
			$response['message'][] = __( 'Templates & Styles has been successfully reseted.', 'go_portfolio_textdomain' );
			set_transient( md5( $screen->id . '-response' ), $response, 30 );
			
			/* Redirect */
			wp_redirect( admin_url( 'admin.php?page=' . $_GET['page'] . '&updated=true' ) );
			exit;			

		/* Edit action - redirect */
		} elseif ( $_POST['action-type'] == 'edit' ) {
			if ( isset( $_POST['item'] ) && !empty ( $_POST['item'] ) ) {
				$type = explode( '[', $_POST['item'] );
				$item_type = isset( $type[0] ) ? $type[0] : null;
				$item_type = $item_type && $item_type == 'template' ? 'template' : 'style';
				$item_id = isset( $type[1] ) ? trim( $type[1], ']' ) : null;
				wp_redirect( admin_url( 'admin.php?page=' . $_GET['page'] . '&edit=' . $item_type . '&item=' . $item_id ) );
				exit;
			}			
		
		/* Edit item action */
		} elseif ( $_POST['action-type'] == 'edit-item' ) {
			if ( isset( $_POST['item-type'] ) && ! empty( $_POST['item-type'] ) && isset( $_POST['item-id'] ) && ! empty( $_POST['item-id'] ) ) {
				if ( $_POST['item-type'] == 'template' ) {

					/* Load the templates */
					$new_templates = self::load_templates();
					
					/* Save the new */
					$templates[$_POST['item-id']]['data'] = $_POST['item-data'];
					if ( $new_templates != $templates ) {
						update_option( self::$plugin_prefix . '_templates', $templates );
					}
					
					/* Set the reponse message */
					$response['result'] = 'success';
					$response['message'][] = sprintf( __( '"%1$s" template has been successfully updated.', 'go_portfolio_textdomain' ), $templates[$_POST['item-id']]['name'] );
					
				} else {
					
					/* Load the styles */
					$new_styles = self::load_styles();
					$styles[$_POST['item-id']]['data'] = $_POST['item-data'];
					
					/* Save the new */
					if ( $new_styles != $styles ) {
						update_option( self::$plugin_prefix . '_styles', $styles );
						self::generate_styles();
					}
					
					/* Set the reponse message */
					$response['result'] = 'success';
					$response['message'][] = sprintf( __( '"%1$s" style has been successfully updated.', 'go_portfolio_textdomain' ), $styles[$_POST['item-id']]['name'] );				

				}
				set_transient( md5( $screen->id . '-response' ), $response, 30 );
				
				/* Redirect */
				$referrer = preg_match( '/&updated=true$/', $referrer ) ? $referrer : $referrer. '&updated=true';
				wp_redirect( $referrer );
				exit;	

			}

		/* Reset item action */
		} elseif ( $_POST['action-type'] == 'reset-item' ) {
			
			if ( isset( $_POST['item-type'] ) && ! empty( $_POST['item-type'] ) && isset( $_POST['item-id'] ) && ! empty( $_POST['item-id'] ) ) {
				if ( $_POST['item-type'] == 'template' ) {

					/* Load the templates */
					$new_templates = self::load_templates();
					
					/* Save the new */
					if ( $new_templates != $templates ) {
						$templates[$_POST['item-id']] = $new_templates[$_POST['item-id']];
						update_option( self::$plugin_prefix . '_templates', $templates );
					}
					
					/* Set the reponse message */
					if ( !isset( $new_templates[$_POST['item-id']]['data'] ) ) {
						$response['result'] = 'error';
						$response['message'][] = sprintf( __( '"%1$s" template file is missing.', 'go_portfolio_textdomain' ), $templates[$_POST['item-id']]['tpl_file'] );
					} else {
						$response['result'] = 'success';
						$response['message'][] = sprintf( __( '"%1$s" template has been successfully reseted.', 'go_portfolio_textdomain' ), $templates[$_POST['item-id']]['name'] );
					}
					
				} else {
					
					/* Load the styles */
					$new_styles = self::load_styles();
					
					/* Save the new */
					if ( $new_styles != $styles ) {
						$styles[$_POST['item-id']] = $new_styles[$_POST['item-id']];
						update_option( self::$plugin_prefix . '_styles', $styles );	
						self::generate_styles();					
					}
					
					/* Set the reponse message */
					if ( !isset( $new_styles[$_POST['item-id']]['data'] ) ) {
						$response['result'] = 'error';
						$response['message'][] = sprintf( __( '"%1$s" stylesheet file is missing.', 'go_portfolio_textdomain' ), $styles[$_POST['item-id']]['css_file'] );
					} else {
						$response['result'] = 'success';
						$response['message'][] = sprintf( __( '"%1$s" style has been successfully reseted.', 'go_portfolio_textdomain' ), $styles[$_POST['item-id']]['name'] );							
					}

				}
				set_transient( md5( $screen->id . '-response' ), $response, 30 );
				
				/* Redirect */
				$referrer = preg_match( '/&updated=true$/', $referrer ) ? $referrer : $referrer. '&updated=true';
				wp_redirect( $referrer );				
				exit;	

			}

		}

		wp_redirect( admin_url( 'admin.php?page=' . $_GET['page'] ) );
		exit;		
		
	}
	
}

/**
 *
 * Content
 *
 */

?>
<div id="gwa-gopf-admin-wrap" class="wrap">
	<div id="gwa-gopf-admin-icon" class="icon32"></div>
    <h2><?php _e( 'Template & Style Editor', 'go_portfolio_textdomain' ); ?></h2>	
	<p></p>
	<?php

	/* Print message */
	if ( isset( $_GET['updated'] ) && $_GET['updated'] == 'true' && $response = get_transient( md5( $screen->id . '-response' ) ) ) : 
	?>
	<div id="result" class="<?php echo $response['result'] == 'error' ? 'error' : 'updated'; ?>">
	<?php foreach ( $response['message'] as $error_msg ) : ?>
		<p><strong><?php echo $error_msg; ?></strong></p>
	<?php endforeach;  $response = array(); ?>
	</div>
	<?php 	
	delete_transient( md5( $screen->id . '-response' ) );
	endif;
	/* /Print message */

	?>

	<?php
	
	/**
	 *
	 * Default Page content
	 *
	 */

	if ( empty( $_POST ) && !isset( $_GET['edit'] ) || ( isset( $_GET['edit'] ) && empty ( $_GET['edit'] ) ) ) : 
	?>
	<!-- form -->
	<form id="gwa-gopf-editor-form" name="gwa-gopf-editor-form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>&noheader=true">
		<input id="gwa-gopf-action-type" name="action-type" type="hidden" value="edit" />
		<?php wp_nonce_field( $this->plugin_slug . basename( __FILE__ ), $this->plugin_slug . '-nonce' ); ?>

		<!-- postbox -->
		<div class="postbox">
			<h3 class="hndle"><?php _e( 'Template & Style Editor', 'go_portfolio_textdomain' ); ?><span class="gwwpa-toggle"></span></h3>
			<div class="inside">
				<table class="form-table">
					<tr>
						<th class="gwa-gopf-w150"><div><?php _e( 'Select template or style', 'go_portfolio_textdomain' ); ?></div></th>
						<td class="gwa-gopf-w300">
							<select name="item" class="gwa-gopf-w250">
								<!-- Templates -->
								<?php if ( isset( $templates ) && !empty( $templates ) ) : ?>
								<optgroup label="<?php _e( 'Templates', 'go_portfolio_textdomain' ); ?>"></optgroup>
								<?php foreach( $templates as $tkey => $template ) : ?>
								<option value="template[<?php echo $tkey; ?>]"><?php echo $template['name']; echo !isset( $template['data'] ) ? ' ' . __( '(broken)', 'go_portfolio_textdomain' ) : ''; ?></option>
								<?php 
								endforeach;
								endif;
								?>
								<!-- /Templates -->
								
								<!-- Styles -->
								<?php if ( isset( $styles ) && !empty( $styles ) ) : ?>
								<optgroup label="<?php _e( 'Styles', 'go_portfolio_textdomain' ); ?>"></optgroup>
								<?php foreach( $styles as $skey => $style ) : ?>
								<option value="style[<?php echo $skey; ?>]"><?php echo $style['name']; echo !isset( $style['data'] ) ? ' ' . __( '(broken)', 'go_portfolio_textdomain' ) : ''; ?></option>
								<?php 
								endforeach;
								endif;
								?>								
								<!-- /Styles -->
							</select>
						</td>
						<td><p class="description"><?php _e( 'Select template or style to edit.', 'go_portfolio_textdomain' ); ?></p></td>
					</tr>					

				</table>
			</div> 				
		</div> 
		<!-- /postbox -->	

		<p class="submit">
			<input type="submit" class="button-primary gwa-gopf-edit" value="<?php esc_attr_e( 'Edit', 'go_portfolio_textdomain' ); ?>" />
			<input type="button" class="button-secondary gwa-gopf-reset" value="<?php esc_attr_e( 'Reset All', 'go_portfolio_textdomain' ); ?>" />
			<input type="button" class="button-secondary gwa-gopf-import" value="<?php esc_attr_e( 'Import New', 'go_portfolio_textdomain' ); ?>" />
		</p>

	</form>
	<!-- /form -->
	
	<?php endif; ?>
	
	<?php
		
	/**
	 *
	 * Edit Page content
	 *
	 */

	if ( empty( $_POST ) && isset( $_GET['edit'] ) && ( $_GET['edit'] == 'template' || $_GET['edit'] == 'style' ) ) : 
	if ( $_GET['edit'] == 'template' ) {
		if ( !isset( $templates[sanitize_key( $_GET['item'] )] ) ) {
			?>
			<div id="result" class="error">
			<p><strong><?php _e( 'No template found!', 'go_portfolio_textdomain' ); ?> <a href="<?php echo esc_attr( admin_url( 'admin.php?page=' . $_GET['page'] ) ) ?>"><?php _e( 'Click here', 'go_portfolio_textdomain' ); ?></a> <?php _e( 'for Template & Style Editor', 'go_portfolio_textdomain' ); ?></strong></p>
			</div>
			<?php
			exit;		 
		} else {
			$item_data = $templates[sanitize_key( $_GET['item'] )];
			$item_id = sanitize_key( $_GET['item'] );
			$item_type = 'template';
		}
	}
	if ( $_GET['edit'] == 'style' ) {
		if ( !isset( $styles[sanitize_key( $_GET['item'] )] ) ) {
			?>
			<div id="result" class="error">
			<p><strong><?php _e( 'No style found!', 'go_portfolio_textdomain' ); ?> <a href="<?php echo esc_attr( admin_url( 'admin.php?page=' . $_GET['page'] ) ) ?>"><?php _e( 'Click here', 'go_portfolio_textdomain' ); ?></a> <?php _e( 'for Template & Style Editor', 'go_portfolio_textdomain' ); ?></strong></p>
			</div>
			<?php
			exit;	 
		} else {
			$item_data = $styles[sanitize_key( $_GET['item'] )];	
			$item_id = sanitize_key( $_GET['item'] );
			$item_type = 'style';
		}
	}	
	?>
	<!-- form -->
	<form id="gwa-gopf-editor-form" name="gwa-gopf-editor-form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>&noheader=true">
		<input id="gwa-gopf-action-type" name="action-type" type="hidden" value="edit-item" />
		<input name="item-type" type="hidden" value="<?php echo esc_attr( $item_type ); ?>" />
		<input name="item-id" type="hidden" value="<?php echo esc_attr( $item_id ); ?>" />
		<?php wp_nonce_field( $this->plugin_slug . basename( __FILE__ ), $this->plugin_slug . '-nonce' ); ?>

		<!-- postbox -->
		<div class="postbox">
			<h3 class="hndle"><?php echo $item_type == 'template' ? sprintf( __( 'Edit Template: "%1$s"', 'go_portfolio_textdomain' ), $item_data['name'] ) :  sprintf( __( 'Edit style : "%1$s"', 'go_portfolio_textdomain' ), $item_data['name'] ) ; ?> <span class="gwwpa-toggle"></span></h3>
			<div class="inside">
				<table class="form-table">
					<tr>
						<?php if ( $item_type == 'template' ) : ?>
						<th colspan="3"><?php _e( 'You can modify the default structure of the selected template. These changes will affect all portfolio use this template. 
You can make further modications for each portfolio when creating a portfolio.', 'go_portfolio_textdomain' ); ?></th>
						<?php else : ?>
						<th colspan="3"><?php _e( 'You can modify the default code of the selected style. These changes will affect all portfolio use this style. 
You can make further modications for each portfolio when creating a portfolio.', 'go_portfolio_textdomain' ); ?></th>						
						<?php endif; ?>
					</tr>				
					<tr>
						<th colspan="3">
						<?php if ( isset( $item_data['data'] ) ) : ?>
						<textarea name="item-data" style="width:100%;" rows="10"><?php echo $item_data['data']; ?></textarea>
						<?php else : 
						$req_file = $item_type == 'template' ? $item_data['tpl_file'] : $item_data['css_file'];
						_e( '<p>This template or style seems to be broken.</p>', 'go_portfolio_textdomain' );
						printf( __('<p>The following file is missing: "<strong>%1$s</strong>".</p><p> Please check if the reqired files doest exist and click to "Reset".</p>', 'go_portfolio_textdomain' ), GW_GO_PORTFOLIO_DIR . 'templates/templates/' .$req_file ); 
						endif;
						?>
						</th>
				    </tr>
				</table>
			</div> 				
		</div> 
		<!-- /postbox -->

		<p class="submit">
			<?php if ( isset( $item_data['data'] ) ) : ?><input type="submit" class="button-primary gwa-gopf-edit-item" value="<?php esc_attr_e( 'Save', 'go_portfolio_textdomain' ); ?>" /><?php endif; ?>
			<input type="button" class="button-secondary gwa-gopf-reset-item" value="<?php esc_attr_e( 'Reset', 'go_portfolio_textdomain' ); ?>" />
		</p>

	</form>
	<!-- /form -->	
	
	<?php endif; ?>	
	
</div>	