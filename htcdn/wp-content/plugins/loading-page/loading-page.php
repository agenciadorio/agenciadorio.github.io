<?php
/*
Plugin Name: Loading Page
Plugin URI: http://wordpress.dwbooster.com/content-tools/loading-page
Description: Loading Page plugin performs a pre-loading of images on your website and displays a loading progress screen with percentage of completion. Once everything is loaded, the screen disappears.
Version: 1.0.28
Author: CodePeople
Author URI: http://wordpress.dwbooster.com/content-tools/loading-page
License: GPLv2
Text Domain: loading_page_text_domain
*/

require_once 'banner.php';
$codepeople_promote_banner_plugins[ 'codepeople-loading-page' ] = array(
	'plugin_name' => 'Loading Page',
	'plugin_url'  => 'https://wordpress.org/support/plugin/loading-page/reviews/#new-post'
);

// CONST
define('LOADING_PAGE_PLUGIN_DIR', dirname(__FILE__));
define('LOADING_PAGE_PLUGIN_URL', plugins_url('', __FILE__));
define('LOADING_PAGE_TD', 'loading_page_text_domain');

include LOADING_PAGE_PLUGIN_DIR.'/includes/admin_functions.php';

/**
* Plugin activation
*/
register_activation_hook( __FILE__, 'loading_page_install' );
if(!function_exists('loading_page_install')){
	function _loading_page_options()
	{
		$loading_page_options = get_option('loading_page_options', array());
		if( !empty($loading_page_options) ) return;

		// Set the default options here
        $loading_page_options = array(
            'foregroundColor'           => '#FFFFFF',
            'backgroundColor'           => '#000000',
            'enabled_loading_screen'    => true,
            'remove_in_on_load'    		=> false,
            'loading_screen'            => 'bar',
            'lp_loading_screen_display_in'  => 'all',
			'once_per_session'  		=> 'always',
			'lp_loading_screen_display_in_pages' => '',
			'lp_loading_screen_exclude_from_pages' => '',
            'displayPercent'            => true,
            'backgroundImage'           => '',
            'backgroundImageRepeat'     => 'repeat',
            'fullscreen'                => 0,
            'pageEffect'                => 'none',
			'deepSearch'				=> true
        );

        update_option('loading_page_options', $loading_page_options);
    }
	function loading_page_install( $networkwide ) {
		global $wpdb;

		if (function_exists('is_multisite') && is_multisite()) {
			if ($networkwide) {
	            $old_blog = $wpdb->blogid;
				// Get all blog ids
				$blogids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
				foreach ($blogids as $blog_id) {
					switch_to_blog($blog_id);
					_loading_page_options();
				}
				switch_to_blog($old_blog);
				return;
			}
		}
		_loading_page_options();
	} // End loading_page_install
} // End plugin activation

/**
*	A new blog has been created in a multisite WordPress
*/
add_action( 'wpmu_new_blog', 'loading_page_new_blog', 10, 6);
function loading_page_new_blog($blog_id, $user_id, $domain, $path, $site_id, $meta ) {
    global $wpdb;
	if ( is_plugin_active_for_network() )
	{
        $current_blog = $wpdb->blogid;
        switch_to_blog( $blog_id );
		_loading_page_options();
        switch_to_blog( $current_blog );
    }
} // End loading_page_new_blog

/*
*   Plugin initializing
*/
add_action( 'init', 'loading_page_init');
if(!function_exists('loading_page_init')){
    function loading_page_init(){
        if(!is_admin()){
            $op = get_option('loading_page_options');
            if($op &&  $op['enabled_loading_screen'])
			{
                add_action('wp_enqueue_scripts', 'loading_page_enqueue_scripts', 1);
			}
        }
    } // End loading_page_init
}

/*
*   Admin initionalizing
*/
add_action('admin_init', 'loading_page_admin_init');
if(!function_exists('loading_page_admin_init')){
    function loading_page_admin_init(){
        // Load the associated text domain
        load_plugin_textdomain( LOADING_PAGE_TD, false, LOADING_PAGE_PLUGIN_DIR . '/languages/' );

        // Set plugin links
        $plugin = plugin_basename(__FILE__);
        add_filter('plugin_action_links_'.$plugin, 'loading_page_links');

        // Load resources
        add_action('admin_enqueue_scripts', 'loading_page_admin_resources');

    } // End loading_page_admin_init
}

if(!function_exists('loading_page_links')){
    function loading_page_links($links){
        // Custom link
        $custom_link = '<a href="http://wordpress.dwbooster.com/contact-us" target="_blank">'.__('Request custom changes', LOADING_PAGE_TD).'</a>';
		array_unshift($links, $custom_link);

        // Settings link
        $settings_link = '<a href="options-general.php?page=loading-page.php">'.__('Settings').'</a>';
		array_unshift($links, $settings_link);

		return $links;
    } // End loading_page_customization_link
}

add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'loading_page_customizationLink');
if(!function_exists('loading_page_customizationLink'))
{
	function loading_page_customizationLink($links)
	{
		array_unshift($links, '<a href="https://wordpress.org/support/plugin/loading-page/#new-post" target="_blank">'.__('Help').'</a>');
		return $links;
	}
}

// Set the settings menu option
add_action('admin_menu', 'loading_page_settings_menu');
if(!function_exists('loading_page_settings_menu')){
    function loading_page_settings_menu(){
        // Add to admin_menu
		add_options_page('Loading Page', 'Loading Page', 'edit_posts', basename(__FILE__), 'loading_page_settings_page');
    } // End loading_page_settings_menu
}

if(!function_exists('loading_page_replace_the_header')){
    function loading_page_replace_the_header($the_header){
            echo '<style>body{visibility:hidden;}</style>';
    }
}

if(!function_exists('loadin_page_add_codeBlock')){
	function loadin_page_add_codeBlock()
	{
		$codeblock = '';
		$op = get_option('loading_page_options');
		if( !empty($op) && !empty($op['codeBlock']) )
		{
			$codeblock = '<div id="loadin_page_codeBlock">'.$op['codeBlock'].'</div>';
		}
		return $codeblock;
	}
}

if(!function_exists('loading_page_admin_resources')){
    function loading_page_admin_resources($hook){
        if(strpos($hook, "loading-page") !== false){
			wp_enqueue_media();
            wp_enqueue_style( 'farbtastic' );
            wp_enqueue_script( 'farbtastic' );
		    wp_enqueue_style( 'thickbox' );
            wp_enqueue_script( 'thickbox' );

            wp_enqueue_script('lp-admin-script', LOADING_PAGE_PLUGIN_URL.'/js/loading-page-admin.js', array('jquery', 'thickbox', 'farbtastic'), 'free-1.0.28');
        }
    } // End loading_page_admin_resources
}

if( !function_exists('loading_page_loading_screen') ){
    function loading_page_loading_screen(){
		if( session_id() == "" ) @session_start();
        global $post;
		$op = get_option('loading_page_options');
        $loadingScreen = 0;
        if(
			!isset( $_SERVER['HTTP_USER_AGENT'] ) ||
			preg_match( '/bot|crawl|slurp|spider/i', $_SERVER[ 'HTTP_USER_AGENT' ] )
		)
		{
			return $loadingScreen;
		}

        if(
			!empty( $op['enabled_loading_screen'] )
		)
        {
			$_permalink = md5(get_permalink());
			if(
				empty( $op[ 'once_per_session' ] ) ||
				$op[ 'once_per_session' ] == 'always' ||
				empty( $_SESSION[ 'loading_page_once_per_session' ] ) ||
				(
					$op[ 'once_per_session' ] == 'page' &&
					is_array($_SESSION[ 'loading_page_once_per_session' ]) &&
					empty($_SESSION[ 'loading_page_once_per_session' ][$_permalink])
				)
			)
			{
				if(empty($_SESSION[ 'loading_page_once_per_session' ]) || !is_array($_SESSION[ 'loading_page_once_per_session' ])) $_SESSION[ 'loading_page_once_per_session' ] = array();
				$_SESSION[ 'loading_page_once_per_session' ][$_permalink] = 1;

				$pages = ( !empty( $op[ 'lp_loading_screen_display_in_pages' ] ) ) ? $op[ 'lp_loading_screen_display_in_pages' ] : '';
				$pages = str_replace( ' ', '', $pages );
				$pages = explode( ',', $pages );

				$exclude_pages = ( !empty( $op[ 'lp_loading_screen_exclude_from_pages' ] ) ) ? $op[ 'lp_loading_screen_exclude_from_pages' ] : '';
				$exclude_pages = str_replace( ' ', '', $exclude_pages );
				$exclude_pages = explode( ',', $exclude_pages );

				if(
					(
						empty( $op[ 'lp_loading_screen_display_in' ] ) ||
						$op[ 'lp_loading_screen_display_in' ] == 'all' ||
						( $op[ 'lp_loading_screen_display_in' ] == 'home' && ( is_home() || is_front_page() ) ) ||
						( $op[ 'lp_loading_screen_display_in' ] == 'pages' && isset( $post ) && in_array( $post->ID, $pages )  )
					) &&
					(
						empty( $post ) ||
						empty( $post->ID ) ||
						empty( $exclude_pages ) ||
						!in_array( $post->ID, $exclude_pages )
					)
				)
				{
					$loadingScreen = 1;
					add_action( 'wp_head',  'loading_page_replace_the_header', 99 );
				}
			}
        }
        return $loadingScreen;
    }
}

if(!function_exists('loading_page_enqueue_scripts')){
    function loading_page_enqueue_scripts(){
		global $post;

        $op = get_option('loading_page_options');
        wp_enqueue_style('codepeople-loading-page-style', LOADING_PAGE_PLUGIN_URL.'/css/loading-page.css', array(), 'free-1.0.28');
        wp_enqueue_style('codepeople-loading-page-style-effect', LOADING_PAGE_PLUGIN_URL.'/css/loading-page'.(($op['pageEffect'] != 'none') ? '-'.$op['pageEffect'] : '').'.css', array(), 'free-1.0.28');
        $required = array('jquery');

        $loadingScreen = loading_page_loading_screen();

        if( $loadingScreen ){
            $s = loading_page_get_screen($op['loading_screen']);
            if($s){
                if(!empty($s['style'])){
                    wp_enqueue_style('codepeople-loading-page-style-'.$s['id'], $s['style'], array(), 'free-1.0.28');
                }

                if(!empty($s['script'])){
                    wp_enqueue_script('codepeople-loading-page-script-'.$s['id'], $s['script'], array('jquery'), 'free-1.0.28');
                    $required[] = 'codepeople-loading-page-script-'.$s['id'];
                }
            }
            wp_enqueue_script('codepeople-loading-page-script', LOADING_PAGE_PLUGIN_URL.'/js/loading-page.js', $required, 'free-1.0.28');

            $loading_page_settings = array(
				'loadingScreen'   => $loadingScreen,
				'removeInOnLoad'  => (!empty($op['remove_in_on_load'])) ? $op['remove_in_on_load'] : false,
				'codeblock'		  => loadin_page_add_codeBlock(),
                'backgroundColor' => $op['backgroundColor'],
                'foregroundColor' => $op['foregroundColor'],
                'backgroundImage' => $op['backgroundImage'],
				'additionalSeconds' => (!empty($op['additionalSeconds'])) ? $op['additionalSeconds'] : 0,
                'pageEffect'      => $op['pageEffect'],
                'backgroundRepeat'=> $op['backgroundImageRepeat'],
                'fullscreen'      => (( !empty( $op[ 'fullscreen' ] ) ) ? 1 : 0),
                'graphic'         => $op['loading_screen'],
                'text'            => ((!empty($op['displayPercent'])) ? $op['displayPercent'] : 0),
				'lp_ls' 		  => ((!empty($op['lp_ls'])) ? $op[ 'lp_ls' ] : 0),
				'screen_size'	  => ((!empty($op['screen_size']))  ? $op[ 'screen_size' ]  : 'all'),
				'screen_width'	  => ((!empty($op['screen_width'])) ? $op[ 'screen_width' ] : 0),
				'deepSearch'	  => (( !isset( $op[ 'deepSearch' ] ) || !empty( $op[ 'deepSearch' ] ) ) ? 1 : 0)
			);

			wp_localize_script('codepeople-loading-page-script', 'loading_page_settings', $loading_page_settings );
        }
    } // End loading_page_enqueue_scripts
}

if(!function_exists('loading_page_settings_page')){
    function loading_page_settings_page(){
        if(isset($_POST['loading_page_nonce']) && wp_verify_nonce($_POST['loading_page_nonce'], __FILE__)){

			$additionalSeconds = trim($_POST['lp_additionalSeconds']);
			$codeBlock = stripcslashes(trim($_POST['lp_codeBlock']));

            // Set the default options here
            $loading_page_options = array(
                'foregroundColor'           => (!empty($_POST['lp_foregroundColor'])) ? $_POST['lp_foregroundColor'] : '#FFFFFF',
                'backgroundColor'           => (!empty($_POST['lp_backgroundColor'])) ? $_POST['lp_backgroundColor'] : '#000000',
                'backgroundImage'           => $_POST['lp_backgroundImage'],
                'backgroundImageRepeat'     => $_POST['lp_backgroundRepeat'],
                'additionalSeconds'     	=> (is_numeric($additionalSeconds)) ? intval($additionalSeconds) : 0,
                'codeBlock'     			=> $codeBlock,
                'fullscreen'                => ( isset( $_POST['lp_fullscreen'] ) ) ? 1 : 0,
                'enabled_loading_screen'    => (isset($_POST['lp_enabled_loading_screen'])) ? true : false,
                'remove_in_on_load'    		=> (isset($_POST['lp_remove_in_on_load'])) ? true : false,
				'screen_size'				=> (isset($_POST['lp_screen_size']) && in_array($_POST['lp_screen_size'], array('all', 'greater', 'lesser'))) ? $_POST['lp_screen_size'] : 'all',
				'screen_width'				=> (!empty($_POST['lp_screen_width']) && is_numeric( ($lp_screen_width = preg_replace('/[^\\d\\.]/', '', $_POST['lp_screen_width']) ) ) ) ? $lp_screen_width : '',
                'lp_loading_screen_display_in'  	 => ( isset( $_POST[ 'lp_loading_screen_display_in' ] ) ) ? $_POST[ 'lp_loading_screen_display_in' ] : 'all',
				'once_per_session'			=> (
													!isset( $_POST[ 'once_per_session' ] ) ||
													!in_array($_POST[ 'once_per_session' ], array('always','site','page'))
												) ? 'always' : $_POST[ 'once_per_session' ],
				'lp_loading_screen_display_in_pages' => $_POST[ 'lp_loading_screen_display_in_pages' ],
				'lp_loading_screen_exclude_from_pages' => $_POST[ 'lp_loading_screen_exclude_from_pages' ],
				'deepSearch'				=> (isset($_POST['lp_deactivateDeepSearch'])) ? false : true,
				'loading_screen'            => $_POST['lp_loading_screen'],
                'displayPercent'            => (isset($_POST['lp_displayPercent'])) ? true : false,
                'pageEffect'                => $_POST['lp_pageEffect']
            );

			if( isset( $_POST[ 'lp_ls' ] ) )
			{
				$loading_page_options[ 'lp_ls' ] = $_POST[ 'lp_ls' ];
			}

            if(update_option('loading_page_options', $loading_page_options)){
                print '<div class="updated">'.__('The Loading Page has been stored successfully', LOADING_PAGE_TD).'</div>';
            }else{
                print '<div class="error">'.__('The Loading Page settings could not be stored', LOADING_PAGE_TD).'</div>';
            }
        }

        $loading_page_options = get_option('loading_page_options');
?>
        <div class="wrap">
            <h2><?php _e('Loading Page Settings', LOADING_PAGE_TD); ?></h2>
            <form method="post">
                <input type="hidden" name="loading_page_nonce" value="<?php print(wp_create_nonce(__FILE__)); ?>" />
                <div class="postbox" style="min-width:760px;">
                    <h3 class='hndle' style="padding:5px;"><span><?php _e('Loading Screen', LOADING_PAGE_TD); ?></span></h3>
                    <div class="inside">
                        <p  style="border:1px solid #E6DB55;margin-bottom:10px;padding:5px;background-color: #FFFFE0;">If you want test the premium version of Loading Page go to the following links:<br/> <a href="http://demos.net-factor.com/loading-page/wp-login.php" target="_blank">Administration area: Click to access the administration area demo</a><br/>
						<a href="http://demos.net-factor.com/loading-page/" target="_blank">Public page: Click to access the Loading Page</a><br />
						<a href="https://wordpress.org/support/plugin/loading-page/#new-post" target="_blank">If you need additional help</a>
						</p>
                        <p><?php
                            print(
                                _e("Displays a loading screen until the webpage is ready, the screen shows the loading percent.",
                                LOADING_PAGE_TD)
                            );
                        ?></p>
                        <table class="form-table">
                            <tr>
                                <th><?php _e('Enable loading screen', LOADING_PAGE_TD); ?></th>
                                <td><input type="checkbox" name="lp_enabled_loading_screen" <?php echo((!empty($loading_page_options['enabled_loading_screen'])) ? 'CHECKED' : '' ); ?> /></td>
                            </tr>
							<tr>
                                <th><?php _e('Remove the loading screen in the window onload event', LOADING_PAGE_TD); ?></th>
                                <td><input type="checkbox" name="lp_remove_in_on_load" <?php echo((!empty($loading_page_options['remove_in_on_load'])) ? 'CHECKED' : '' ); ?> /><br><span><?php _e('If the checkbox is ticked the loading screen is removed in the onload event of window, but if it is unchecked the loading screen is removed as soon as possible.',LOADING_PAGE_TD); ?></span></td>
                            </tr>
							<tr>
								<th><?php _e('Display the loading screen on', LOADING_PAGE_TD); ?></th>
								<td>
									<select name="lp_screen_size" style="float:left;">
										<option value="all" <?php
										if(
											isset($loading_page_options['screen_size']) &&
											$loading_page_options['screen_size'] == 'all'
										) print "SELECTED";
										?> ><?php _e( 'All Screens', LOADING_PAGE_TD ) ?></option>
										<option value="greater" <?php
										if(
											isset($loading_page_options['screen_size']) &&
											$loading_page_options['screen_size'] == 'greater'
										) print "SELECTED";
										?> ><?php _e( 'Greater Than', LOADING_PAGE_TD ) ?></option>
										<option value="lesser" <?php
										if(
											isset($loading_page_options['screen_size']) &&
											$loading_page_options['screen_size'] == 'lesser'
										) print "SELECTED";
										?> ><?php _e( 'Lesser Than', LOADING_PAGE_TD ) ?></option>
									</select>
									<div id="lp_width_container" style="float:left; padding-left:10px;<?php
										if( !isset($loading_page_options['screen_size']) || $loading_page_options['screen_size'] == 'all') echo 'display:none;';
										else echo 'display:block;';
									?>">
										<?php _e( 'Width', LOADING_PAGE_TD ); ?>:
										<input type="text" name="lp_screen_width" value="<?php
											if(isset( $loading_page_options['screen_width'] )) echo esc_attr($loading_page_options['screen_width']);
										?>" /> px
									</div>
									<script>
										jQuery('[name="lp_screen_size"]').change(function(){
											jQuery( '#lp_width_container' )[ ( this.value == 'all' ) ? 'hide' : 'show' ]();
										})
									</script>
								</td>
							</tr>
							<tr>
								<th><?php _e('Display the loading screen', LOADING_PAGE_TD); ?></th>
								<td>
									<?php
										if(
											!isset( $loading_page_options['once_per_session'] ) ||
											$loading_page_options['once_per_session'] === false
										)
										{
											$loading_page_options['once_per_session'] = 'always';
										}
										elseif( $loading_page_options['once_per_session'] === true )
										{
											$loading_page_options['once_per_session'] = 'site';
										}
									?>
									<input type="radio" value="always" name="once_per_session" <?php echo(($loading_page_options['once_per_session'] == 'always') ? 'CHECKED' : '' ); ?> /> <span><?php _e( 'always', LOADING_PAGE_TD ); ?></span><br />
									<input type="radio" value="site" name="once_per_session" <?php echo(($loading_page_options['once_per_session'] == 'site') ? 'CHECKED' : '' ); ?> /> <span><?php _e( 'once per session', LOADING_PAGE_TD ); ?></span><br />
									<input type="radio" value="page" name="once_per_session" <?php echo(($loading_page_options['once_per_session'] == 'page') ? 'CHECKED' : '' ); ?> /> <span><?php _e( 'once per page', LOADING_PAGE_TD ); ?></span>

								</td>
							</tr>
                            <tr>
                                <th><?php _e('Display loading screen in', LOADING_PAGE_TD); ?></th>
                                <td>
									<div><input type="radio" name="lp_loading_screen_display_in" value="home" <?php echo(( isset( $loading_page_options['lp_loading_screen_display_in'] ) && $loading_page_options['lp_loading_screen_display_in'] == 'home' ) ? 'CHECKED' : '' ); ?> /> homepage only</div>
									<div><input type="radio" name="lp_loading_screen_display_in" value="all" <?php echo(( isset( $loading_page_options['lp_loading_screen_display_in'] ) && $loading_page_options['lp_loading_screen_display_in'] == 'all' ) ? 'CHECKED' : '' ); ?> /> all pages</div>
									<div><input type="radio" name="lp_loading_screen_display_in" value="pages" <?php echo(( isset( $loading_page_options['lp_loading_screen_display_in'] ) && $loading_page_options['lp_loading_screen_display_in'] == 'pages' ) ? 'CHECKED' : '' ); ?> /> the specific pages
									<input type="text" name="lp_loading_screen_display_in_pages" value="<?php if( !empty( $loading_page_options['lp_loading_screen_display_in_pages'] ) ) print $loading_page_options['lp_loading_screen_display_in_pages']; ?>"> <span>In this case should be typed one, or more IDs for posts or pages, separated by the comma symbol ","</span></div>

								</td>
                            </tr>
							<tr>
								<th><?php _e( 'Exclude loading screen from' ); ?></th>
								<td><input type="text" name="lp_loading_screen_exclude_from_pages" value="<?php if( !empty( $loading_page_options['lp_loading_screen_exclude_from_pages'] ) ) print $loading_page_options['lp_loading_screen_exclude_from_pages']; ?>"> <span>In this case should be typed one, or more IDs for posts or pages, separated by the comma symbol ","</span></td>
							</tr>
                            <tr>
                                <?php $loading_screens = loading_page_get_screen_list();?>
                                <th><?php _e('Select the loading screen', LOADING_PAGE_TD); ?></th>
                                <td>
                                    <select name="lp_loading_screen">
                                        <?php
                                            foreach($loading_screens as $screen){
                                                print '<option value="'.$screen['id'].'" '.((isset($loading_page_options['loading_screen']) && $loading_page_options['loading_screen'] == $screen['id']) ? 'SELECTED' : '').' title="'.((!empty($screen['tips'])) ? esc_attr($screen['tips']) : '' ).'" >'.$screen['name'].'</option>';
                                            }
                                        ?>
                                    </select>
                                    <span style="color:#FF0000;">
                                        To display a different loading screen you require the commercial version of plugin <a href="http://wordpress.dwbooster.com/content-tools/loading-page" target="_blank">CLICK HERE</a>
                                    </span>
                                </td>
                            </tr>
							<?php
							foreach( $loading_screens as $screen )
							{
								if( !empty( $screen[ 'adminsection' ] ) ) include_once $screen[ 'adminsection' ];
								if( !empty( $screen[ 'adminscript' ] ) ) print '<script src="'.$screen[ 'adminscript' ].'"></script>';
							}
							?>
                            <tr>
                                <th><?php _e('Select background color', LOADING_PAGE_TD); ?></th>
                                <td><input type="text" name="lp_backgroundColor" id="lp_backgroundColor" value="<?php if(isset($loading_page_options['backgroundColor'])) print(esc_attr($loading_page_options['backgroundColor'])); ?>" /><div id="lp_backgroundColor_picker"></div></td>
                            </tr>
                            <tr>
                                <th><?php _e('Select image as background', LOADING_PAGE_TD); ?></th>
                                <td>
                                    <input type="text" name="lp_backgroundImage" id="lp_backgroundImage" value="<?php if(isset($loading_page_options['backgroundImage'])) print(esc_attr($loading_page_options['backgroundImage'])); ?>" />
                                    <input type="button" value="Browse" onclick="loading_page_selected_image('lp_backgroundImage');" />
                                    <select id="lp_backgroundRepeat" name="lp_backgroundRepeat">
                                        <option value="repeat" <?php if( isset($loading_page_options[ 'backgroundImageRepeat' ]) && $loading_page_options[ 'backgroundImageRepeat' ] == 'repeat' ) echo "SELECTED"; ?> >Tile</option>
                                        <option value="no-repeat" <?php if( isset($loading_page_options[ 'backgroundImageRepeat' ]) && $loading_page_options[ 'backgroundImageRepeat' ] == 'no-repeat' ) echo "SELECTED"; ?> >Center</option>
                                    </select>

                                </td>
                            </tr>
                            <tr>
                                <th><?php _e('Display image in fullscreen', LOADING_PAGE_TD); ?></th>
                                <td>
                                    <input type="checkbox" name="lp_fullscreen" id="lp_fullscreen" <?php echo (( isset( $loading_page_options[ 'fullscreen' ] ) && $loading_page_options[ 'fullscreen' ] )   ? 'CHECKED' : '' );?> />
                                    <?php _e('(The fullscreen attribute can fail in some browsers)', LOADING_PAGE_TD); ?>
                                </td>
                            </tr>
                            <tr>
                                <th><?php _e('Select foreground color', LOADING_PAGE_TD); ?></th>
                                <td><input type="text" name="lp_foregroundColor" id="lp_foregroundColor" value="<?php if(isset($loading_page_options['foregroundColor'])) print(esc_attr($loading_page_options['foregroundColor'])); ?>" /><div id="lp_foregroundColor_picker"></div></td>
                            </tr>
                            <tr>
                                <th><?php _e('Additional seconds', LOADING_PAGE_TD); ?></th>
                                <td>
									<input type="text" name="lp_additionalSeconds" id="lp_additionalSeconds" value="<?php if(isset($loading_page_options['additionalSeconds'])) print(esc_attr($loading_page_options['additionalSeconds'])); ?>" />
									<div><?php _e( 'Show the loading screen some few seconds after loading the page.', LOADING_PAGE_TD ); ?></div>
								</td>
                            </tr>
                            <tr>
                                <th><?php _e('Include an ad, or your own block of code', LOADING_PAGE_TD); ?></th>
                                <td>
									<textarea name="lp_codeBlock" id="lp_codeBlock" rows="6" style="width:80%;"><?php if(isset($loading_page_options['codeBlock'])) print(esc_textarea($loading_page_options['codeBlock'])); ?></textarea>
								</td>
                            </tr>
                            <tr>
                                <th><?php _e('Apply the effect on page', LOADING_PAGE_TD); ?></th>
                                <td>
                                <select name="lp_pageEffect">
                                <?php
                                    $pageEffects = array('none', 'rotateInLeft');

                                    foreach($pageEffects as $value){
                                        print '<option value="'.$value.'" '.((isset($loading_page_options['pageEffect']) && $loading_page_options['pageEffect'] == $value) ? 'SELECTED' : '').'>'.$value.'</option>';
                                    }
                                ?>

                                </select>
                                <div>The premium version of plugin add the following effects: collapseIn, risingFromBottom, expandIn, fadeIn, fallFromTop, rotateInLeft, rotateInRight, rotateInRightWithoutToKeyframe, slideInSkew, tumbleIn, whirlIn</div>
                                </td>
                            </tr>
                            <tr>
                                <th><?php _e('Display loading percent', LOADING_PAGE_TD); ?></th>
                                <td><input type="checkbox" name="lp_displayPercent" <?php echo((!empty($loading_page_options['displayPercent'])) ? 'CHECKED' : '' ); ?> /></td>
                            </tr>
                        </table>
						<div style="border: 1px solid #DADADA; padding:10px;">
							<h3><?php _e( 'Troubleshoot Area - Loading Screen', LOADING_PAGE_TD ); ?></h3>
							<table class="form-table">
								<tr>
									<th><?php _e( 'Disable the search in deep', LOADING_PAGE_TD ); ?></th>
									<td>
										<input type="checkbox" name="lp_deactivateDeepSearch" <?php echo ((empty($loading_page_options['deepSearch'])) ? 'CHECKED' : ''); ?>/><br />
										<p><?php _e( 'If your loading screen is stopping in some percentage, and the page is not loaded, tick the checkbox: "Disable the search in deep"', LOADING_PAGE_TD ); ?></p>
									</td>
								</tr>
							</table>
						</div>
                    </div>
                </div>
                <div class="postbox" style="min-width:760px;">
                    <h3 class='hndle' style="padding:5px;"><span><?php _e('Lazy Loading', LOADING_PAGE_TD); ?></span></h3>
                    <div class="inside">
                        <p><?php
                            print(
                                _e("To load only the images visible in the viewport to improve the loading rate of your website and reduce the bandwidth consumption.",
                                LOADING_PAGE_TD)
                            );
                        ?></p>
                        <p>
                            <span style="color:#FF0000;">
                                The lazy loading of images is available only in the commercial version of plugin <a href="http://wordpress.dwbooster.com/content-tools/loading-page" target="_blank">CLICK HERE</a>
                            </span>
                        </p>
                        <p><img src="<?php print(LOADING_PAGE_PLUGIN_URL.'/images/consumption_graph.png'); ?>" /></p>
                        <table class="form-table">
                            <tr>
                                <th><?php _e('Enable lazy loading', LOADING_PAGE_TD); ?></th>
                                <td><input type="checkbox" DISABLED /></td>
                            </tr>
                            <tr>
                                <th><?php _e('Select the image to load by default', LOADING_PAGE_TD); ?></th>
                                <td>
                                    <input type="text" DISABLED /><input type="button" value="Browse" DISABLED />
                                </td>
                            </tr>
							<tr>
								<th><?php _e( 'Exclude lazy loading from' ); ?></th>
								<td><input type="text" DISABLED /> <span>In this case should be typed one, or more IDs for posts or pages, separated by the comma symbol ","</span></td>
							</tr>
                        </table>
						<div style="border: 1px solid #DADADA; padding:10px;">
							<h3><?php _e( 'Troubleshoot Area - Lazy Loading', LOADING_PAGE_TD ); ?></h3>
							<table class="form-table">
								<tr>
									<th><?php _e( 'Exclude images whose tag includes the class or attribute', LOADING_PAGE_TD ); ?></th>
									<td>
										<input type="text" style="width:100%;" disabled /><br />
										<p><?php _e( 'Don\'t apply the lazy loading to the images with the classes or attributes (separate them by comma symbol ",")', LOADING_PAGE_TD ); ?></p>
									</td>
								</tr>
							</table>
						</div>
                    </div>
                </div>
                <div><input type="submit" value="Update Settings" class="button-primary" /></div>
            </form>
        </div>
<?php
    } // End loading_page_settings_page
}
?>