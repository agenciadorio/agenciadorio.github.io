<?php
/**
 * Submenu page for in admin area
 * General Settings Page
 *
 * @package   Go Portfolio - WordPress Responsive Portfolio 
 * @author    Granth <granthweb@gmail.com>
 * @link      http://granthweb.com
 * @copyright 2016 Granth
 */

$screen = get_current_screen();

/* Get general settings db data */
$general_settings = get_option( self::$plugin_prefix . '_general_settings' );

/* Get cpts db data */
$custom_post_types = get_option( self::$plugin_prefix . '_cpts' );
$portfolio_cpts = array();
if ( isset ( $custom_post_types ) && !empty( $custom_post_types ) ) {
	foreach ( $custom_post_types as $cpt_key => $custom_post_type ) {
		$portfolio_cpts[$cpt_key] = $custom_post_type['slug'];
	}
}

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

	$new_general_settings = $_POST;
			
	/* Save data to db */
	if ( !isset( $response['result'] ) || $response['result'] != 'error' ) {
		if ( $general_settings != $new_general_settings ) { 
			update_option ( self::$plugin_prefix . '_general_settings', $new_general_settings );
		}

		/* Set the reponse message */
		$response['result'] = 'success';
		$response['message'][] = __( 'General settings has been successfully updated.', 'go_portfolio_textdomain' );
		set_transient( md5($screen->id . '-response' ), $response, 30 );
	}
	
	/* Redirect */
	wp_redirect( admin_url( 'admin.php?page=' . $_GET['page'] . '&updated=true' ) );
	exit;
}

/**
 *
 * Content
 *
 */

?>
<div id="gwa-gopf-admin-wrap" class="wrap">
	<div id="gwa-gopf-admin-icon" class="icon32"></div>
    <h2><?php _e( 'General Settings', 'go_portfolio_textdomain' ); ?></h2>	
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

	<!-- form -->
	<form id="gwa-gopf-settings-form" name="gwa-gopf-settings-form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>&noheader=true">
		<?php wp_nonce_field( $this->plugin_slug . basename( __FILE__ ), $this->plugin_slug . '-nonce' ); ?>

		<!-- postbox -->
		<div class="postbox">
			<h3 class="hndle"><?php _e( 'Admin Settings', 'go_portfolio_textdomain' ); ?><span class="gwwpa-toggle"></span></h3>
			<div class="inside">
				<table class="form-table"> 
					<tr>
						<th class="gwa-gopf-w150"><strong><?php _e( 'Disable AJAX in admin?', 'go_portfolio_textdomain' ); ?></strong></th>
						<td class="gwa-gopf-w100"><label><input type="checkbox" name="disable-ajax" value="1"<?php echo isset( $general_settings['disable-ajax'] ) ? 'value="1" checked="checked"' : '' ; ?> /> <?php _e( 'Yes', 'go_portfolio_textdomain' ); ?></label></td>
						<td colspan="3"><p class="description"><?php _e( 'Whether to disable AJAX in then plugin admin area when you edit a portfolio?', 'go_portfolio_textdomain' ); ?></p></td>						
					</tr>
				</table>
				<?php if ( current_user_can( 'manage_options' ) ) : ?>
				<div class="gwa-gopf-separator"></div>
				<table class="form-table"> 
					<tr>
						<th class="gwa-gopf-w150"><strong><?php _e( 'Set Role', 'go_portfolio_textdomain' ); ?></strong></th>
						<td class="gwa-gopf-w100">
							<select name="capability" class="gwa-gopf-w250">
								<option value="manage_options" <?php echo isset( $general_settings['capability'] ) && $general_settings['capability'] == 'manage_options' ? 'selected="selected"' : ''; ?>><?php _e( 'Administrator', 'go_portfolio_textdomain' ); ?></option>
								<option value="edit_private_posts" <?php echo isset( $general_settings['capability'] ) && $general_settings['capability'] == 'edit_private_posts' ? 'selected="selected"' : ''; ?>><?php _e( 'Editor', 'go_portfolio_textdomain' ); ?></option>
								<option value="publish_posts" <?php echo isset( $general_settings['capability'] ) && $general_settings['capability'] == 'publish_posts' ? 'selected="selected"' : ''; ?>><?php _e( 'Author', 'go_portfolio_textdomain' ); ?></option>
								<option value="edit_posts" <?php echo isset( $general_settings['capability'] ) && $general_settings['capability'] == 'edit_posts' ? 'selected="selected"' : ''; ?>><?php _e( 'Contributor', 'go_portfolio_textdomain' ); ?></option>
							</select>						
						</td>
						<td colspan="3"><p class="description"><?php _e( 'Set user access to the plugin.', 'go_portfolio_textdomain' ); ?></p></td>
					</tr>                   
				</table>
				<?php endif; ?>			
			</div>
		</div> 
		<!-- /postbox --> 	        

		<p class="submit">
			<input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save', 'go_portfolio_textdomain' ); ?>" />
		</p>

		<!-- postbox -->
		<div class="postbox">
			<h3 class="hndle"><?php _e( 'Enable Post Types', 'go_portfolio_textdomain' ); ?><span class="gwwpa-toggle"></span></h3>
			<div class="inside">
				<table class="form-table">
					<?php
					$args = array(
					   'public'   => true,
					   '_builtin' => true
					);
								
					$output = 'objects';
					$operator = 'and';
					$post_types = get_post_types( $args, $output, $operator ); 
					if ( !empty( $post_types ) ) {
						foreach ( $post_types  as $post_type_key => $post_type ) {
							if ( $post_type_key == 'attachment' ) {
								$post_type->labels->name .= ' (Attachments) for Gallery';
							}
						}
					}
					if ( !empty( $post_types ) ) :
					?>
					<tr>
						<th class="gwa-gopf-w150"><strong><?php _e( 'Built-in post types', 'go_portfolio_textdomain' ); ?></strong></th>
						<td class="gwa-gopf-w300">
						<?php foreach ( $post_types  as $post_type_key => $post_type ) : ?>
							<label><input type="checkbox" name="enable_post_type[<?php echo $post_type_key; ?>]" value="<?php echo $post_type_key; ?>"<?php echo isset( $general_settings['enable_post_type'][$post_type_key] ) && $general_settings['enable_post_type'][$post_type_key] == $post_type_key ? ' checked="checked"' : ''; ?> /> <?php echo $post_type->labels->name; ?></label><br>
						<?php endforeach; ?>
						</td>
						<td>
							<p class="description"><?php _e( 'Select the Wordpress built-in post types to use in the plugin.', 'go_portfolio_textdomain' ); ?></p>
						</td>
					</tr>
					<?php endif; ?>

					<?php
					$args = array(
					   'public'   => true,
					   '_builtin' => false,  
					);
								
					$output = 'objects';
					$operator = 'and';
					$post_types = get_post_types( $args, $output, $operator ); 
					if ( !empty( $post_types ) ) {
						foreach ( $post_types  as $post_type_key => $post_type ) {
							if ( !post_type_supports( $post_type_key, 'thumbnail' ) ) {
								unset($post_types[$post_type_key]);
							}
							if ( in_array( $post_type_key, $portfolio_cpts ) ) {
								unset($post_types[$post_type_key]);
							}
						}
					}
					if ( !empty( $post_types ) ) :
					?>										
					<tr>
						<th class="gwa-gopf-w150"><strong><?php _e( 'Custom post types', 'go_portfolio_textdomain' ); ?></strong></th>
						<td class="gwa-gopf-w300">
						<?php foreach ( $post_types  as $post_type_key => $post_type ) : ?>
							<label><input type="checkbox" name="enable_post_type[<?php echo $post_type_key; ?>]" value="<?php echo $post_type_key; ?>"<?php echo isset( $general_settings['enable_post_type'][$post_type_key] ) && $general_settings['enable_post_type'][$post_type_key] == $post_type_key ? ' checked="checked"' : ''; ?> /> <?php echo $post_type->labels->name; ?></label><br>
						<?php endforeach; ?>
						</td>
						<td>
							<p class="description"><?php _e( 'Select the custom post types to use in the plugin.', 'go_portfolio_textdomain' ); ?></p>
							<p class="description"><?php _e( 'Enabling means adding meta boxes to post for extra features (video, audio, thumbnail).', 'go_portfolio_textdomain' ); ?></p>
							<p class="description"><?php _e( '<strong>Important:</strong> Custom post types defined by the plugin not listed here.', 'go_portfolio_textdomain' ); ?></p>
						</td>	
					</tr>
					<?php endif; ?>					                                               
				</table>				
				<div class="gwa-gopf-separator"></div>
				<table class="form-table">
					<tr>
						<th></th>
						<td colspan="2">
							<p class="description"><?php _e( '<strong>Important:</strong> You can use the plugin with any built-in post types and other (plugin or theme defined) custom post types.', 'go_portfolio_textdomain' ); ?>
							<p class="description"><?php _e( 'Enabling means adding "Go Portfolio Options" meta box to the selected post type posts for the extra features (e.g. video thumbnail). Post types can be used to create a portfolio without enabling them, but the features are limited.', 'go_portfolio_textdomain' ); ?></p>
						</td>				
					</tr>                                               
				</table>						
			</div>
		</div> 
		<!-- /postbox --> 

		<!-- postbox -->
		<div class="postbox">
			<h3 class="hndle"><?php _e( 'Styling Settings', 'go_portfolio_textdomain' ); ?><span class="gwwpa-toggle"></span></h3>
			<div class="inside">
				<table class="form-table">
					<tr>
						<th class="gwa-gopf-w150"><label for="gwa-gopf-primary-font"><strong><?php _e( 'Primary font', 'go_portfolio_textdomain' ); ?></strong></label></th>
						<td class="gwa-gopf-w300"><input type="text" name="primary-font" id="gwa-gopf-primary-font" value="<?php echo esc_attr( isset( $general_settings['primary-font'] ) ? $general_settings['primary-font'] : '' ); ?>" class="gwa-gopf-w250" /></td>
						<td colspan="3"><p class="description"><?php _e( 'Primary font family (e.g. Arial, Helvetica, sans-serif).', 'go_portfolio_textdomain' ); ?></p></td>
					</tr>                        
					<tr>
						<th class="gwa-gopf-w150"><label for="gwa-gopf-primary-font-css"><strong><?php _e( 'Primary font CSS', 'go_portfolio_textdomain' ); ?></strong></label></th>
						<td class="gwa-gopf-w300"><input type="text" name="primary-font-css" id="gwa-gopf-primary-font-css" value="<?php echo esc_attr( isset( $general_settings['primary-font-css'] ) ? $general_settings['primary-font-css'] : '' ); ?>" class="gwa-gopf-w250" /></td>
						<td colspan="3"><p class="description"><?php _e( 'Primary font external CSS file for Google (or other) fonts', 'go_portfolio_textdomain' ); ?></p></td>
					</tr>                        
					<tr>
						<th class="gwa-gopf-w150"><label for="gwa-gopf-secondary-font"><strong><?php _e( 'Secondary font', 'go_portfolio_textdomain' ); ?></strong></label></th>
						<td class="gwa-gopf-w300"><input type="text" name="secondary-font" id="gwa-gopf-secondary-font" value="<?php echo esc_attr( isset( $general_settings['secondary-font'] ) ? $general_settings['secondary-font'] : '' ); ?>" class="gwa-gopf-w250" /></td>
						<td colspan="3"><p class="description"><?php _e( 'Secondary font family (e.g. Verdana, Geneva, sans-serif).', 'go_portfolio_textdomain' ); ?></p></td>
					</tr>                        
					<tr>
						<th class="gwa-gopf-w150"><label for="gwa-gopf-secondary-font-css"><strong><?php _e( 'Secondary font CSS', 'go_portfolio_textdomain' ); ?></strong></label></th>
						<td class="gwa-gopf-w300"><input type="text" name="secondary-font-css" id="gwa-gopf-secondary-font-css" value="<?php echo esc_attr( isset( $general_settings['secondary-font-css'] ) ? $general_settings['secondary-font-css'] : '' ); ?>" class="gwa-gopf-w250" /></td>
						<td colspan="3"><p class="description"><?php _e( 'Secondary font external CSS file for Google (or other) fonts', 'go_portfolio_textdomain' ); ?></p></td>
					</tr>                        
				</table>			
				<div class="gwa-gopf-separator"></div>
				<table class="form-table">     
					<tr>
						<th class="gwa-gopf-w150"><strong><?php _e( 'Enable responsivity', 'go_portfolio_textdomain' ); ?></strong></th>
						<td class="gwa-gopf-w100" colspan="4"><label><input type="checkbox" name="responsivity" value="1"<?php echo isset( $general_settings['responsivity'] ) ? 'value="1" checked="checked"' : '' ; ?> /> <?php _e( 'Yes', 'go_portfolio_textdomain' ); ?></label></td>
					</tr>					                       
					<tr>
						<th class="gwa-gopf-w150"><strong><?php _e( 'Tablet (landscape) media query', 'go_portfolio_textdomain' ); ?></strong></th>
						<td class="gwa-gopf-w100"><label for="gwa-gopf-size1-min"><?php _e( 'Minimum width', 'go_portfolio_textdomain' ); ?></label></th>
						<td class="gwa-gopf-w100"><input type="text" name="size1-min" id="gwa-gopf-size1-min" value="<?php echo esc_attr( isset( $general_settings['size1-min'] ) ? $general_settings['size1-min'] : '' ); ?>" class="gwa-gopf-w80" /></td>
						<td class="gwa-gopf-w100"><label for="gwa-gopf-size1-max"><?php _e( 'Maximum width', 'go_portfolio_textdomain' ); ?></label></td>
						<td colspan="2"><input type="text" name="size1-max" id="gwa-gopf-size1-max" value="<?php echo esc_attr( isset( $general_settings['size1-max'] ) ? $general_settings['size1-max'] : '' ); ?>" class="gwa-gopf-w80" /></td>
					</tr>
					<tr>
						<th class="gwa-gopf-w100"><strong><?php _e( 'Mobile (landscape) media query', 'go_portfolio_textdomain' ); ?></strong></th>
						<td class="gwa-gopf-w100"><label for="gwa-gopf-size2-min"><?php _e( 'Minimum width', 'go_portfolio_textdomain' ); ?></label></th>
						<td class="gwa-gopf-w100"><input type="text" name="size2-min" id="gwa-gopf-size2-min" value="<?php echo esc_attr ( isset( $general_settings['size2-min'] ) ? $general_settings['size2-min'] : '' ); ?>" class="gwa-gopf-w80" /></td>
						<td class="gwa-gopf-w100"><label for="gwa-gopf-size2-max"><?php _e( 'Maximum width', 'go_portfolio_textdomain' ); ?></label></td>
						<td colspan="2"><input type="text" name="size2-max" id="gwa-gopf-size2-max" value="<?php echo esc_attr( isset( $general_settings['size2-max'] ) ? $general_settings['size2-max'] : '' ); ?>" class="gwa-gopf-w80" /></td>
					</tr>
					<tr>
						<th class="gwa-gopf-w100"><strong><?php _e( 'Mobile (portrait) media query', 'go_portfolio_textdomain' ); ?></strong></th>
						<td class="gwa-gopf-w100"><label for="gwa-gopf-size3-min"><?php _e( 'Minimum width', 'go_portfolio_textdomain' ); ?></label></th>
						<td class="gwa-gopf-w100"><input type="text" name="size3-min" id="gwa-gopf-size3-min" value="<?php echo esc_attr( isset( $general_settings['size3-min'] ) ? $general_settings['size3-min'] : '' ); ?>" class="gwa-gopf-w80" /></td>
						<td class="gwa-gopf-w100"><label for="gwa-gopf-size3-max"><?php _e( 'Maximum width', 'go_portfolio_textdomain' ); ?></label></td>
						<td colspan="2"><input type="text" name="size3-max" id="gwa-gopf-size3-max" value="<?php echo esc_attr( isset( $general_settings['size3-max'] ) ? $general_settings['size3-max'] : '' ); ?>" class="gwa-gopf-w80" /></td>
					</tr>
				</table>			
				<div class="gwa-gopf-separator"></div>
				<table class="form-table">  					
					<tr>
						<th class="gwa-gopf-w150"><label for="gwa-gopf-max-width3"><strong><?php _e( 'Tablet (landscape) view max width', 'go_portfolio_textdomain' ); ?></strong></label></th>
						<td class="gwa-gopf-w100"><input type="text" name="max-width3" id="gwa-gopf-max-width3" value="<?php echo esc_attr( isset( $general_settings['max-width3'] ) ? $general_settings['max-width3'] : '' ); ?>" class="gwa-gopf-w80" /></td>
						<td colspan="3"><p class="description"><?php _e( 'Maximum width of portfolio in tablet (landscape) view.', 'go_portfolio_textdomain' ); ?></p></td>
					</tr>
					<tr>
						<th class="gwa-gopf-w150"><label for="gwa-gopf-max-width2"><strong><?php _e( 'Mobile (landscape) view max width', 'go_portfolio_textdomain' ); ?></strong></label></th>
						<td class="gwa-gopf-w100"><input type="text" name="max-width2" id="gwa-gopf-max-width2" value="<?php echo esc_attr( isset( $general_settings['max-width2'] ) ? $general_settings['max-width2'] : '' ); ?>" class="gwa-gopf-w80" /></td>
						<td colspan="3"><p class="description"><?php _e( 'Maximum width of portfolio in mobile (landscape) view.', 'go_portfolio_textdomain' ); ?></p></td>
					</tr>					
					<tr>
						<th class="gwa-gopf-w150"><label for="gwa-gopf-max-width"><strong><?php _e( 'Mobile (portrait) view max width', 'go_portfolio_textdomain' ); ?></strong></label></th>
						<td class="gwa-gopf-w100"><input type="text" name="max-width" id="gwa-gopf-max-width" value="<?php echo esc_attr( isset( $general_settings['max-width'] ) ? $general_settings['max-width'] : '' ); ?>" class="gwa-gopf-w80" /></td>
						<td colspan="3"><p class="description"><?php _e( 'Maximum width of portfolio in mobile (portrait) view.', 'go_portfolio_textdomain' ); ?></p></td>
					</tr>															                                                        
				</table>
				<div class="gwa-gopf-separator"></div>
				<table class="form-table"> 
					<tr>
						<th class="gwa-gopf-w150"><strong><?php _e( 'Disable transitions on mobile devices?', 'go_portfolio_textdomain' ); ?></strong></th>
						<td class="gwa-gopf-w100" colspan="4"><label><input type="checkbox" name="disable-mobile-trans" value="1"<?php echo isset( $general_settings['disable-mobile-trans'] ) ? 'value="1" checked="checked"' : '' ; ?> /> <?php _e( 'Yes', 'go_portfolio_textdomain' ); ?></label></td>
					</tr>
				</table>
				<div class="gwa-gopf-separator"></div>
				<table class="form-table"> 
					<tr>
						<th class="gwa-gopf-w150"><strong><label for="gwa-gopf-lb-zindex"><strong><?php _e( 'Lightbox z-index', 'go_portfolio_textdomain' ); ?></strong></label></th>
						<td class="gwa-gopf-w100"><input type="text" name="lb-zindex" id="gwa-gopf-lb-zindex" value="<?php echo esc_attr( isset( $general_settings['lb-zindex'] ) ? $general_settings['lb-zindex'] : '' ); ?>" class="gwa-gopf-w80" /></td>
						<td colspan="3"><p class="description"><?php _e( 'Z-index of the lightbox', 'go_portfolio_textdomain' ); ?></p></td>						
					</tr>
				</table>											
			</div>
		</div> 
		<!-- /postbox --> 
		
		<!-- postbox -->
		<div class="postbox">
			<h3 class="hndle"><?php _e( 'Plugin Assets', 'go_portfolio_textdomain' ); ?><span class="gwwpa-toggle"></span></h3>
			<div class="inside">
				<table class="form-table">
                    <tr>
                        <th class="gwa-gopf-w150"><label><?php _e( 'Plugin Page(s) Restriction', 'go_portfolio_textdomain' ); ?></label></th>
                        <td class="gwa-gopf-w100">
                            <select name="plugin-pages-rule" class="gwa-gopf-w250">
                                <option value="in" <?php echo isset( $general_settings['plugin-pages-rule'] ) && $general_settings['plugin-pages-rule'] == 'in' ? 'selected="selected"' : ''; ?>><?php _e( 'Include', 'go_portfolio_textdomain' ); ?></option>
                                <option value="not_in" <?php echo isset( $general_settings['plugin-pages-rule'] ) && $general_settings['plugin-pages-rule'] == 'not_in' ? 'selected="selected"' : ''; ?>><?php _e( 'Exclude', 'go_portfolio_textdomain' ); ?></option>
                            </select>								
                        </td>
                        <td colspan="3"><p class="description"><?php _e( 'Specify the rule of the restriction. Include: pages/posts where to load plugin assets (JavaScript & CSS files). Exlude: pages/posts where NOT to load plugin assets.', 'go_portfolio_textdomain' ); ?></p></td>						
                    </tr>                
                    <tr>
                        <th><label><?php _e( 'Plugin Page(s)', 'go_portfolio_textdomain' ); ?></label></th>
                        <td><input type="text" class="gwa-gopf-w250" name="plugin-pages" value="<?php echo esc_attr( isset( $general_settings['plugin-pages'] ) ? $general_settings['plugin-pages'] : '' ); ?>"></td>
                        <td colspan="3"><p class="description"><?php _e( 'Comma separated list of page/post IDs (e.g. 13, 54, 126). Use to restrict the plugin to load or NOT to load JavaScript & CSS files (depending of the restriction rule) for the selected pages/posts only improving site performance. Leave blank if you don\'t want any restriction.', 'go_portfolio_textdomain' ); ?></p></td>
                    </tr>
                    <tr>
                        <th><label><?php _e( 'Load JavaScript In Header', 'go_portfolio_textdomain' ); ?></label></th>
                        <td class="gwa-gopf-w100"><label><input type="checkbox" name="js-in-header" value="1"<?php echo isset( $general_settings['js-in-header'] ) ? 'value="1" checked="checked"' : '' ; ?> /> <?php _e( 'Yes', 'go_portfolio_textdomain' ); ?></label></td>
						<td colspan="3"><p class="description"><?php _e( 'Whether to load plugin JavaScript in header section of the website. Disable it to load it in the page footer (recommended).', 'go_portfolio_textdomain' ); ?></p></td>
                    </tr>                    
				</table>		
			</div>
		</div> 
		<!-- /postbox --> 					    

		<p class="submit">
			<input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save', 'go_portfolio_textdomain' ); ?>" />
		</p>

	</form>
	<!-- /form -->
	
</div>