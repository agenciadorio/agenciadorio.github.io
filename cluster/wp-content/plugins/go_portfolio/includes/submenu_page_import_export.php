<?php
/**
 * Submenu page for in admin area
 * Import & Export Page
 *
 * @package   Go Portfolio - WordPress Responsive Portfolio 
 * @author    Granth <granthweb@gmail.com>
 * @link      http://granthweb.com
 * @copyright 2016 Granth
 */
 
$screen = get_current_screen();

/* Get cpts & portfolios db data */
$custom_post_types = get_option( self::$plugin_prefix . '_cpts' );
$portfolios = get_option( self::$plugin_prefix . '_portfolios', array() );

/* Handle post */
if ( !empty( $_POST ) && check_admin_referer( $this->plugin_slug . basename( __FILE__ ), $this->plugin_slug . '-nonce' ) ) {

	$reponse = array();
	$referrer=$_POST['_wp_http_referer'];

	/* Clean post fields */
	$_POST = go_portfolio_clean_input( $_POST, array(),
		array(
			'go-portfolio-nonce',
			'_wp_http_referer',
		)
	);

	/* Default Page POST */
	if ( isset( $_POST['action-type'] ) ) {
		
		/* Export action - validate & redirect */
		if ( $_POST['action-type'] == 'export' ) {
					
			if ( isset( $_POST['export'] ) ) {
				
				/* Set temporary POST data */
				set_transient( md5( $screen->id . '-data' ), $_POST, 30 );
				
				/* Redirect */
				wp_redirect( admin_url( 'admin.php?page=' . $_GET['page'] . '&action=export' ) );
				exit;
			} else {
				
				/* Set the reponse message */
				$response['result'] = 'error';
				$response['message'][] = __( 'There is nothing to export!', 'go_portfolio_textdomain' );
				set_transient( md5( $screen->id . '-response' ), $response, 30 );
				
				/* Redirect */
				$referrer = preg_match( '/&updated=true$/', $referrer ) ? $referrer : $referrer. '&updated=true';
				wp_redirect( $referrer );
				exit;
			}
			
		/* Import action - validate & redirect */
		} elseif ( $_POST['action-type'] == 'import' ) {
			
			if ( isset( $_POST['raw-import'] ) && $_POST['raw-import'] != '' ) {
				$import_data = !empty( $_POST['raw-import'] ) ?  @unserialize( base64_decode( $_POST['raw-import'] ) ) : '';

				/* Validate import data */
				
				if ( !is_array( $import_data ) ) {
					
					/* Set the reponse message */
					$response['result'] = 'error';
					$response['message'][] = __( 'Invalid import data!', 'go_portfolio_textdomain' );
					set_transient( md5( $screen->id . '-response' ), $response, 30 );

					/* Redirect */
					$referrer = preg_match( '/&updated=true$/', $referrer ) ? $referrer : $referrer. '&updated=true';
					wp_redirect( $referrer );
					exit;
					
				} else {

					/* Set temporary POST data */
					set_transient( md5( $screen->id . '-data' ), $import_data, 60 );

					/* Redirect */
					wp_redirect( admin_url( 'admin.php?page=' . $_GET['page'] . '&action=import' ) );
					exit;					
				}
			
			} else {

				/* Set the reponse message */
				$response['result'] = 'error';
				$response['message'][] = __( 'There is nothing to import!', 'go_portfolio_textdomain' );
				set_transient( md5( $screen->id . '-response' ), $response, 30 );
				
				/* Redirect */
				$referrer = preg_match( '/&updated=true$/', $referrer ) ? $referrer : $referrer. '&updated=true';
				wp_redirect( $referrer );
				exit;
			}					
		}
	
	/* Import Page POST */
	} elseif( isset( $_POST['import'] ) ) {
		
		/* Get temporary POST data */
		$temp_post_data = get_transient( md5( $screen->id . '-data' ) );
		
		/* If temporary POST data missing */
		if ( !$temp_post_data ) { 
			wp_redirect( admin_url( 'admin.php?page=' . $_GET['page'] ) );
			exit;
		} else {
			delete_transient( md5( $screen->id . '-data' ) );	
		}
		
		/* Import cpt */
		if ( isset( $_POST['import']['cpt'] ) && !empty( $_POST['import']['cpt'] ) ) {
			$slug_list = array();
			
			/* If 'all' option has been selected */
			if ( isset( $_POST['import']['cpt']['all'] ) ) {
				$all_cpts = explode (',', $_POST['import']['cpt']['all']);
				foreach( $all_cpts as $cpts ) { $_POST['import']['cpt'][$cpts]=''; }
				unset( $_POST['import']['cpt']['all'] );
			}
			
			$imported_cpt_cnt=0;
			$replaced_slug_cnt=0;
			foreach( $_POST['import']['cpt'] as $import_cpt_key => $import_custom_post_type ) {
				$imported_cpt_cnt++;
				$_POST['import']['cpt'][$import_cpt_key]=$temp_post_data['cpt'][$import_cpt_key];				
				$slug_list[$import_cpt_key] = $_POST['import']['cpt'][$import_cpt_key]['slug'];
				if ( isset( $custom_post_types ) && !empty( $custom_post_types ) ) {
					foreach( $custom_post_types as $cpt_key => $custom_post_type ) {
						if ( $cpt_key ==  $import_cpt_key ) {
							if ( isset( $_POST['replace'] ) ) {
								unset( $custom_post_types[$import_cpt_key] );
							} else {
								$uniqid=uniqid();
								$_POST['import']['cpt'][$uniqid] = $_POST['import']['cpt'][$import_cpt_key];
								$_POST['import']['cpt'][$uniqid]['uniqid'] = $uniqid;
								$_POST['import']['cpt'][$uniqid]['slug'] = substr( $custom_post_types[$import_cpt_key]['slug'], 0 ,6 ) . '_' . $uniqid;
								$_POST['import']['cpt'][$uniqid]['name'] = $custom_post_types[$import_cpt_key]['name'] . ' copy ' . $uniqid;
								$_POST['import']['cpt'][$uniqid]['singular_name'] = $custom_post_types[$import_cpt_key]['singular_name'] . ' copy ' . $uniqid;
								$slug_list[$uniqid]=$_POST['import']['cpt'][$uniqid]['slug'];
								unset( $slug_list[$import_cpt_key] );
								unset( $_POST['import']['cpt'][$import_cpt_key] );
							}
						}
					}
					
					foreach( $custom_post_types as $cpt_key => $custom_post_type ) {
						$key = array_search( $custom_post_type['slug'], $slug_list );
						if ( $key && isset( $_POST['import']['cpt'][$key]['slug'] ) ) {
							$replaced_slug_cnt++; 
							$_POST['import']['cpt'][$key]['slug'] = substr( $_POST['import']['cpt'][$key]['slug'], 0 ,6 ) . '_' . $key;
						}
					}					
				}
			}
			
			if ( isset( $custom_post_types ) && empty( $custom_post_types ) ) { $custom_post_types = array(); } 
			$new_custom_post_types = array_merge( $custom_post_types, $_POST['import']['cpt'] );
			
			/* Save to db */
			update_option( self::$plugin_prefix . '_cpts', $new_custom_post_types );

			/* Set the reponse message */
			$response['result'] = 'success';
			$response['message'][] = sprintf( __( '%1$d custom post type item(s) has been imported.', 'go_portfolio_textdomain' ), $imported_cpt_cnt );
		}

		/* Import portfolio */
		if ( isset( $_POST['import']['portfolio'] ) && !empty( $_POST['import']['portfolio'] ) ) {
			$id_list = array();

			/* If 'all' option has been selected */
			if ( isset( $_POST['import']['portfolio']['all'] ) ) {
				$all_pfs  = explode (',', $_POST['import']['portfolio']['all']);
				foreach( $all_pfs as $pfs ) { $_POST['import']['portfolio'][$pfs]=''; }
				unset( $_POST['import']['portfolio']['all'] );			
			}
			
			$imported_pf_cnt=0;
			$replaced_pf_cnt=0;
			foreach( $_POST['import']['portfolio'] as $import_portfolio_key => $import_portfolio ) {
				$imported_pf_cnt++;
				$_POST['import']['portfolio'][$import_portfolio_key]=$temp_post_data['portfolio'][$import_portfolio_key];				
				$id_list[$import_portfolio_key] = $_POST['import']['portfolio'][$import_portfolio_key]['id'];
				if ( isset( $portfolios ) && !empty( $portfolios ) ) {
					foreach( $portfolios as $portfolio_key => $portfolio ) {
						if ( $portfolio_key ==  $import_portfolio_key ) {
							if ( isset( $_POST['replace'] ) ) {
								unset( $portfolios[$import_portfolio_key] );
							} else {
								$uniqid=uniqid();
								$_POST['import']['portfolio'][$uniqid] = $_POST['import']['portfolio'][$import_portfolio_key];
								$_POST['import']['portfolio'][$uniqid]['uniqid'] = $uniqid;
								$_POST['import']['portfolio'][$uniqid]['id'] = $portfolios[$import_portfolio_key]['id'] . '_copy_' . $uniqid;
								$_POST['import']['portfolio'][$uniqid]['name'] = $portfolios[$import_portfolio_key]['name'] . ' copy ' . $uniqid;
								$id_list[$uniqid]=$_POST['import']['portfolio'][$uniqid]['id'];								
								unset( $id_list[$import_portfolio_key] );
								unset( $_POST['import']['portfolio'][$import_portfolio_key] );
							}
						}
					}
					foreach( $portfolios as $portfolio_key => $portfolio ) {
						$key = array_search( $portfolio['id'], $id_list );
						if ( $key && isset( $_POST['import']['portfolio'][$key]['id'] ) ) {
							$replaced_pf_cnt++; 
							$_POST['import']['portfolio'][$key]['id'] = $_POST['import']['portfolio'][$key]['id'] . '_copy_' . $key;
						}						
					}					
				}
			}
			if ( isset( $portfolios ) && empty( $portfolios ) ) { $portfolios = array(); } 
			$new_portfolios = array_merge( $portfolios, $_POST['import']['portfolio'] );
			
			/* Save to db */
			update_option( self::$plugin_prefix . '_portfolios', $new_portfolios );
			
			/* Set the reponse message */
			$response['result'] = 'success';
			$response['message'][] = sprintf( __( '%1$d portfolio item(s) has been imported.', 'go_portfolio_textdomain' ), $imported_pf_cnt );
		}
		
		/* Redirect */
		set_transient( md5( $screen->id . '-response' ), $response, 30 );
		wp_redirect( admin_url( 'admin.php?page=' . $_GET['page'] . '&updated=true' ) );
		exit;
		
	} else {	
	
		/* User didn't select anything */
		
		/* Set the reponse message */
		$response['result'] = 'error';
		$response['message'][] = __( 'There is nothing to import!', 'go_portfolio_textdomain' );
		set_transient( md5( $screen->id . '-response' ), $response, 30 );
		
		/* Redirect */
		$referrer = preg_match( '/&updated=true$/', $referrer) ? $referrer : $referrer. '&updated=true';
		wp_redirect( $referrer );
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
    <h2><?php _e( 'Import & Export', 'go_portfolio_textdomain' ); ?></h2>	
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
	 
	if ( empty( $_POST ) && !isset( $_GET['action'] )  || ( isset( $_GET['action'] ) && empty ( $_GET['action'] ) ) ) : 
	?>
	<!-- form -->
	<form id="gwa-gopf-import-form" name="gwa-gopf-import-form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>&noheader=true">
		<?php wp_nonce_field( $this->plugin_slug . basename( __FILE__ ), $this->plugin_slug . '-nonce' ); ?>

		<!-- postbox -->
		<div class="postbox">
			<h3 class="hndle"><?php _e( 'Import & Export Data', 'go_portfolio_textdomain' ); ?><span class="gwwpa-toggle"></span></h3>
			<div class="inside">
				<table class="form-table">
					<tr>
						<th class="gwa-gopf-w150"><div><?php _e( 'Select action', 'go_portfolio_textdomain' ); ?></div></th>
						<td class="gwa-gopf-w300">
							<select id="gwa-gopf-select" name="action-type" class="gwa-gopf-w250" data-parent="import-export">
								<option data-children="import" value="import"><?php _e( 'Import data', 'go_portfolio_textdomain' ); ?></option>
								<option data-children="export" value="export"><?php _e( 'Export data', 'go_portfolio_textdomain' ); ?></option>
							</select>
						</td>
						<td><p class="description"><?php _e( 'Import or export data.', 'go_portfolio_textdomain' ); ?></p></td>
					</tr>					

					<!-- import -->
					<tr class="gwa-gopf-group" data-parent="import-export" data-children="import">
						<th colspan="3"><?php _e( 'To import data open the file that contains demodata and copy its content to the textarea below and click to the "Save" button.', 'go_portfolio_textdomain' ); ?></th>
					</tr>
					<tr class="gwa-gopf-group" data-parent="import-export" data-children="import">
						<th colspan="3"><textarea name="raw-import" style="width:100%;" rows="10"><?php echo !empty( $temp_post_data ) ? base64_encode( serialize( $temp_post_data ) ) : ''; ?></textarea></th>
				    </tr>										
					<!-- /import -->
		
					<!-- export -->
					<?php if ( isset( $custom_post_types ) && !empty( $custom_post_types ) ) : ?>
					<tr class="gwa-gopf-group" data-parent="import-export" data-children="export">
						<th class="gwa-gopf-w150"><div><?php _e( 'Custom post type', 'go_portfolio_textdomain' ); ?></div></th>
						<td class="gwa-gopf-w300">
							<ul class="gwa-gopf-checkbox-list">
								<li><label><input type="checkbox" name="export[cpt][]" value="all" class="gwa-gopf-checkbox-parent"> <?php _e( 'All custom post types', 'go_portfolio_textdomain' ); ?> [&nbsp;.&nbsp;]<span></span></label>
									<ul class="gwa-gopf-checkbox-list">
										<?php foreach( $custom_post_types as $cpt_key => $custom_post_type ) : ?>
										<li><label><input type="checkbox" name="export[cpt][]" value="<?php echo esc_attr( $cpt_key ); ?>" /> <?php echo $custom_post_type['name']; ?></label></li>
										<?php endforeach; ?>
									</ul>
								</li>	
							</ul>
						</td>
						<td><p class="description"><?php _e( 'Select the custom post types you would like to export.', 'go_portfolio_textdomain' ); ?></p></td>
					</tr>
					<?php endif; ?>
					<?php if ( isset( $portfolios ) && !empty( $portfolios ) ) : ?>
					<tr class="gwa-gopf-group" data-parent="import-export" data-children="export">
						<th class="gwa-gopf-w150"><div><?php _e( 'Portfolio', 'go_portfolio_textdomain' ); ?></div></th>
						<td class="gwa-gopf-w300">
							<ul class="gwa-gopf-checkbox-list">
								<li><label><input type="checkbox" name="export[portfolio][]" value="all" class="gwa-gopf-checkbox-parent"> <?php _e( 'All portfolios', 'go_portfolio_textdomain' ); ?> [&nbsp;.&nbsp;]<span></span></label>
									<ul class="gwa-gopf-checkbox-list">
										<?php foreach( $portfolios as $portfolio_key => $portfolio ) : ?>
										<li><label><input type="checkbox" name="export[portfolio][]" value="<?php echo esc_attr( $portfolio_key ); ?>" /> <?php echo $portfolio['name']; ?></label></li>
										<?php endforeach; ?>
									</ul>
								</li>	
							</ul>
						</td>
						<td><p class="description"><?php _e( 'Select the portfolios you would like to export.', 'go_portfolio_textdomain' ); ?></p></td>
					</tr>
					<?php endif; ?>
					<!-- /export -->
					
				</table>
			</div> 				
		</div> 
		<!-- /postbox -->	

		<p class="submit">
			<input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save', 'go_portfolio_textdomain' ); ?>" />
		</p>

	</form>
	<!-- /form -->
	
	<?php endif; ?>
	
	<?php
		
	/**
	 *
	 * Import Page content
	 *
	 */

	if ( empty( $_POST ) && isset( $_GET['action'] ) && ( $_GET['action'] == 'import' ) ) : 
	$temp_post_data = get_transient( md5( $screen->id . '-data' ) );
	if ( !$temp_post_data ) {
		?>
		<div id="result" class="error">
		<p><strong><?php _e( 'There is nothing to import!', 'go_portfolio_textdomain' ); ?> <a href="<?php echo esc_attr( admin_url( 'admin.php?page=' . $_GET['page'] ) ) ?>"><?php _e( 'Click here', 'go_portfolio_textdomain' ); ?></a> <?php _e( 'for Import & Export', 'go_portfolio_textdomain' ); ?></strong></p>
		</div>
		<?php
		exit;	
	}
	?>
	<!-- form -->
	<form id="gwa-gopf-import-form" name="gwa-gopf-import-form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>&noheader=true">
		<?php wp_nonce_field( $this->plugin_slug . basename( __FILE__ ), $this->plugin_slug . '-nonce' ); ?>
		
		<!-- postbox -->
		<div class="postbox">
			<h3 class="hndle"><?php _e( 'Import Data', 'go_portfolio_textdomain' ); ?><span class="gwwpa-toggle"></span></h3>
			<div class="inside">
				<table class="form-table">
					<tr>
						<th colspan="3"><?php _e( 'Select the data to be imported and click to "Save" button.', 'go_portfolio_textdomain' ); ?></th>
					</tr>
					<?php if ( isset( $temp_post_data['cpt'] ) && !empty( $temp_post_data['cpt'] ) ) : ?>
					<tr>
						<th class="gwa-gopf-w150"><div><?php _e( 'Custom post type', 'go_portfolio_textdomain' ); ?></div></th>
						<td class="gwa-gopf-w300">
							<ul class="gwa-gopf-checkbox-list">
								<li><label><input type="checkbox" name="import[cpt][all]" value="<?php echo implode( ',', array_keys( $temp_post_data['cpt'] ) ); ?>" class="gwa-gopf-checkbox-parent"> <?php _e( 'All custom post types', 'go_portfolio_textdomain' ); ?> [&nbsp;.&nbsp;]<span></span></label>
									<ul class="gwa-gopf-checkbox-list">
										<?php foreach( $temp_post_data['cpt'] as $cpt_key => $custom_post_type ) : ?>
										<li><label><input type="checkbox" name="import[cpt][<?php echo esc_attr( $cpt_key ); ?>]" value="<?php echo esc_attr( $cpt_key ); ?>" /> <?php echo $custom_post_type['name']; ?></label></li>
										<?php endforeach; ?>
									</ul>
								</li>	
							</ul>
						</td>
						<td><p class="description"><?php _e( 'Select the custom post types you would like to export.', 'go_portfolio_textdomain' ); ?></p></td>
					</tr>
					<?php endif; ?>
					<?php if ( isset( $temp_post_data['portfolio'] ) && !empty( $temp_post_data['portfolio'] ) ) : ?>
					<tr>
						<th class="gwa-gopf-w150"><div><?php _e( 'Portfolio', 'go_portfolio_textdomain' ); ?></div></th>
						<td class="gwa-gopf-w300">
							<ul class="gwa-gopf-checkbox-list">
								<li><label><input type="checkbox" name="import[portfolio][all]" value="<?php echo implode( ',', array_keys( $temp_post_data['portfolio'] ) ); ?>" class="gwa-gopf-checkbox-parent"> <?php _e( 'All Portfolios', 'go_portfolio_textdomain' ); ?> [&nbsp;.&nbsp;]<span></span></label>
									<ul class="gwa-gopf-checkbox-list">
										<?php foreach( $temp_post_data['portfolio'] as $portfolio_key => $portfolio ) : ?>
										<li><label><input type="checkbox" name="import[portfolio][<?php echo esc_attr( $portfolio_key ); ?>]" value="<?php echo esc_attr( $portfolio_key ); ?>" /> <?php echo $portfolio['name']; ?></label></li>
										<?php endforeach; ?>
									</ul>
								</li>	
							</ul>
						</td>
						<td><p class="description"><?php _e( 'Select the portfolios you would like to export.', 'go_portfolio_textdomain' ); ?></p></td>
					</tr>
					<tr>
						<th class="gwa-gopf-w150"><div><?php _e( 'Replace existing items?', 'go_portfolio_textdomain' ); ?></div></th>
						<th><label><input type="checkbox" name="replace" value="1" > <?php _e( 'Yes', 'go_portfolio_textdomain' ); ?></label></th>
				    	<td><p class="description"><?php _e( 'The existing items with same ids or slugs will be replaced with the imported ones if set, else a new copy will be created.', 'go_portfolio_textdomain' ); ?></p></td>
					</tr>					
					<?php endif; ?>
				</table>
			</div> 				
		</div> 
		<!-- /postbox -->

		<p class="submit">
			<input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save', 'go_portfolio_textdomain' ); ?>" />
		</p>

	</form>
	<!-- /form -->	
	
	<?php endif; ?>	

	<?php	
	/**
	 *
	 * Export Page content
	 *
	 */

	if ( empty( $_POST ) && isset( $_GET['action'] ) && ( $_GET['action'] == 'export' ) ) : 
	$temp_post_data = get_transient( md5( $screen->id . '-data' ) );
	if ( $temp_post_data ) {
		delete_transient( md5( $screen->id . '-data' ) );
		
		/* Get selected cpt data */
		if ( isset( $temp_post_data['export']['cpt'] ) && !empty( $temp_post_data['export']['cpt'] ) ) {
			if ( in_array( 'all', $temp_post_data['export']['cpt'] ) ) {
				$export_data['cpt'] = $custom_post_types;
			} else {
				if ( isset( $custom_post_types ) && !empty( $custom_post_types ) ) {
					foreach( $custom_post_types as $cpt_key => $custom_post_type ) {
						if ( in_array( $cpt_key, $temp_post_data['export']['cpt'] ) ) {
							$export_data['cpt'][$cpt_key] = $custom_post_type;
						}
					}
				
				}
			}
			
			/* Remove enabled option from exported data */
			if ( isset( $export_data['cpt'] ) && !empty( $export_data['cpt'] ) ) { 
				foreach( $export_data['cpt'] as $exp_cpt_key => $exp_custom_post_type ) {
					if ( isset( $export_data['cpt'][$exp_cpt_key]['enabled'] ) ) {
						unset( $export_data['cpt'][$exp_cpt_key]['enabled'] );
					}
				}			
			}
			
		}
		
		/* Get selected portfolio */
		if ( isset( $temp_post_data['export']['portfolio'] ) && !empty( $temp_post_data['export']['portfolio'] ) ) {
			if ( in_array( 'all', $temp_post_data['export']['portfolio'] ) ) {
				$export_data['portfolio'] = $portfolios;
			} else {
				if ( isset( $portfolios ) && !empty( $portfolios ) ) {
					foreach( $portfolios as $portfolio_key => $portfolio ) {
						if ( in_array( $portfolio_key, $temp_post_data['export']['portfolio'] ) ) {
							$export_data['portfolio'][$portfolio_key] = $portfolio;
						}
					}
				
				}
			}
			
			/* Remove enabled option from exported data */
			if ( isset( $export_data['portfolio'] ) && !empty( $export_data['portfolio'] ) ) { 
				foreach( $export_data['portfolio'] as $exp_portfolio_key => $exp_portfolio ) {
					if ( isset( $export_data['portfolio'][$exp_portfolio_key]['enabled'] ) ) {
						unset( $export_data['portfolio'][$exp_portfolio_key]['enabled'] );
					}
				}			
			}

		}
	} else {
		?>
		<div id="result" class="error">
		<p><strong><?php _e( 'There is nothing to export!', 'go_portfolio_textdomain' ); ?> <a href="<?php echo esc_attr( admin_url( 'admin.php?page=' . $_GET['page'] ) ) ?>"><?php _e( 'Click here', 'go_portfolio_textdomain' ); ?></a> <?php _e( 'for Import & Export', 'go_portfolio_textdomain' ); ?></strong></p>
		</div>
		<?php
		exit;
	}
	?>
		
	<!-- postbox -->
	<div class="postbox">
		<h3 class="hndle"><?php _e( 'Export Data', 'go_portfolio_textdomain' ); ?><span class="gwwpa-toggle"></span></h3>
		<div class="inside">
			<table class="form-table">
				<tr>
					<th><?php _e( 'Copy the content of the textarea below and save into file on your hard drive.', 'go_portfolio_textdomain' ); ?></th>
				</tr>
				<tr>
					<th><textarea id="gwa-gopf-db-data" name="db-data" style="width:100%;" rows="10"><?php echo !empty( $export_data ) ? base64_encode( serialize( $export_data ) ) : ''; ?></textarea></th>
			   </tr>
			</table>
		</div> 				
	</div> 
	<!-- /postbox -->
	
	<?php endif; ?>	
	
</div>	