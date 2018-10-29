<?php
/*
Plugin Name: DNS Prefetch
Plugin URI: http://www.jimmyscode.com/wordpress/dns-prefetch/
Description: Add DNS prefetching meta tags to your site.
Version: 0.1.0
Author: Jimmy Pe&ntilde;a
Author URI: http://www.jimmyscode.com/
License: GPLv2 or later
*/
if (!defined('DPF_PLUGIN_NAME')) {
	// plugin constants
	define('DPF_PLUGIN_NAME', 'DNS Prefetch');
	define('DPF_VERSION', '0.1.0');
	define('DPF_SLUG', 'dns-prefetch');
	define('DPF_LOCAL', 'dpf');
	define('DPF_OPTION', 'dpf');
	define('DPF_OPTIONS_NAME', 'dpf_options');
	define('DPF_PERMISSIONS_LEVEL', 'manage_options');
	define('DPF_PATH', plugin_basename(dirname(__FILE__)));
	/* default values */
	define('DPF_DEFAULT_ENABLED', true);
	define('DPF_DEFAULT_TEXT', '');
	/* option array member names */
	define('DPF_DEFAULT_ENABLED_NAME', 'enabled');
	define('DPF_DEFAULT_TEXT_NAME', 'domainstoadd');
}
	// oh no you don't
	if (!defined('ABSPATH')) {
		wp_die(__('Do not access this file directly.', dpf_get_local()));
	}

	// localization to allow for translations
	add_action('init', 'dpf_translation_file');
	function dpf_translation_file() {
		$plugin_path = dpf_get_path() . '/translations';
		load_plugin_textdomain(dpf_get_local(), '', $plugin_path);
	}
	// tell WP that we are going to use new options
	// also, register the admin CSS file for later inclusion
	add_action('admin_init', 'dpf_options_init');
	function dpf_options_init() {
		register_setting(DPF_OPTIONS_NAME, dpf_get_option(), 'dpf_validation');
		register_dpf_admin_style();
	}
	// validation function
	function dpf_validation($input) {
		// validate all form fields
		if (!empty($input)) {
			$input[DPF_DEFAULT_ENABLED_NAME] = (bool)$input[DPF_DEFAULT_ENABLED_NAME];
			$input[DPF_DEFAULT_TEXT_NAME] = wp_kses_post($input[DPF_DEFAULT_TEXT_NAME]);
		}
		return $input;
	} 

	// add Settings sub-menu
	add_action('admin_menu', 'dpf_plugin_menu');
	function dpf_plugin_menu() {
		add_options_page(DPF_PLUGIN_NAME, DPF_PLUGIN_NAME, DPF_PERMISSIONS_LEVEL, dpf_get_slug(), 'dpf_page');
	}
	// plugin settings page
	// http://planetozh.com/blog/2009/05/handling-plugins-options-in-wordpress-28-with-register_setting/
	function dpf_page() {
		// check perms
		if (!current_user_can(DPF_PERMISSIONS_LEVEL)) {
			wp_die(__('You do not have sufficient permission to access this page', dpf_get_local()));
		}
		?>
		<div class="wrap">
			<h2 id="plugintitle"><img src="<?php echo dpf_getimagefilename('globe.png'); ?>" title="" alt="" height="64" width="64" align="absmiddle" /> <?php echo DPF_PLUGIN_NAME; _e(' by ', dpf_get_local()); ?><a href="http://www.jimmyscode.com/">Jimmy Pe&ntilde;a</a></h2>
			<div><?php _e('You are running plugin version', dpf_get_local()); ?> <strong><?php echo DPF_VERSION; ?></strong>.</div>

			<?php /* http://code.tutsplus.com/tutorials/the-complete-guide-to-the-wordpress-settings-api-part-5-tabbed-navigation-for-your-settings-page--wp-24971 */ ?>
			<?php $active_tab = (isset($_GET['tab']) ? $_GET['tab'] : 'settings'); ?>
			<h2 class="nav-tab-wrapper">
			  <a href="?page=<?php echo dpf_get_slug(); ?>&tab=settings" class="nav-tab <?php echo $active_tab == 'settings' ? 'nav-tab-active' : ''; ?>"><?php _e('Settings', dpf_get_local()); ?></a>
				<a href="?page=<?php echo dpf_get_slug(); ?>&tab=support" class="nav-tab <?php echo $active_tab == 'support' ? 'nav-tab-active' : ''; ?>"><?php _e('Support', dpf_get_local()); ?></a>
			</h2>
			
			<form method="post" action="options.php">
				<?php settings_fields(DPF_OPTIONS_NAME); ?>
				<?php $options = dpf_getpluginoptions(); ?>
				<?php update_option(dpf_get_option(), $options); ?>
				<?php if ($active_tab == 'settings') { ?>
					<h3 id="settings"><img src="<?php echo dpf_getimagefilename('settings.png'); ?>" title="" alt="" height="61" width="64" align="absmiddle" /> <?php _e('Plugin Settings', dpf_get_local()); ?></h3>
					<table class="form-table" id="theme-options-wrap">
						<tr valign="top"><th scope="row"><strong><label title="<?php _e('Is plugin enabled? Uncheck this to turn it off temporarily.', dpf_get_local()); ?>" for="<?php echo dpf_get_option(); ?>[<?php echo DPF_DEFAULT_ENABLED_NAME; ?>]"><?php _e('Plugin enabled?', dpf_get_local()); ?></label></strong></th>
							<td><input type="checkbox" id="<?php echo dpf_get_option(); ?>[<?php echo DPF_DEFAULT_ENABLED_NAME; ?>]" name="<?php echo dpf_get_option(); ?>[<?php echo DPF_DEFAULT_ENABLED_NAME; ?>]" value="1" <?php checked('1', dpf_checkifset(DPF_DEFAULT_ENABLED_NAME, DPF_DEFAULT_ENABLED, $options)); ?> /></td>
						</tr>
						<?php dpf_explanationrow(__('Is plugin enabled? Uncheck this to turn it off temporarily.', dpf_get_local())); ?>
						<?php dpf_getlinebreak(); ?>
						<tr valign="top"><th scope="row"><strong><label title="<?php _e('Enter URLs to be prefetched', dpf_get_local()); ?>" for="<?php echo dpf_get_option(); ?>[<?php echo DPF_DEFAULT_TEXT_NAME; ?>]"><?php _e('Enter URLs to be prefetched', dpf_get_local()); ?></label></strong></th>
							<td><textarea rows="12" cols="75" id="<?php echo dpf_get_option(); ?>[<?php echo DPF_DEFAULT_TEXT_NAME; ?>]" name="<?php echo dpf_get_option(); ?>[<?php echo DPF_DEFAULT_TEXT_NAME; ?>]"><?php echo dpf_checkifset(DPF_DEFAULT_TEXT_NAME, DPF_DEFAULT_TEXT, $options); ?></textarea></td>
						</tr>
						<?php dpf_explanationrow(__('Type the URLs you want to be prefetched by visitors\' browsers. <strong>One URL per line.</strong> Include prefix (such as <strong>http://</strong>) <br /><strong>These domains will be prefetched in addition to the domains already linked on your pages.</strong>', dpf_get_local())); ?>
					</table>
					<?php submit_button(); ?>
				<?php } else { ?>
					<h3 id="support"><img src="<?php echo dpf_getimagefilename('support.png'); ?>" title="" alt="" height="64" width="64" align="absmiddle" /> <?php _e('Support', dpf_get_local()); ?></h3>
					<div class="support">
						<?php echo dpf_getsupportinfo(dpf_get_slug(), dpf_get_local()); ?>
						<small><?php _e('Disclaimer: This plugin is not affiliated with or endorsed by Mozilla.', dpf_get_local()); ?></small>
					</div>
				<?php } ?>
			</form>
		</div>
		<?php }

	// main function and filter
	add_action('wp_head', 'dpf_prefetch', 1);
	function dpf_prefetch() {
		$options = dpf_getpluginoptions();
		if (!empty($options)) {
			$enabled = (bool)$options[DPF_DEFAULT_ENABLED_NAME];
		} else {
			$enabled = DPF_DEFAULT_ENABLED;
		}
		$result = '';
		
		if ($enabled) {
			// https://developer.mozilla.org/en-US/docs/Controlling_DNS_prefetching
			$result = '<meta http-equiv="x-dns-prefetch-control" content="on">';
		
			$tta = explode("\n", $options[DPF_DEFAULT_TEXT_NAME]);
			if (!empty($tta)) {
				$tta = array_map('esc_url', $tta); // Chapter 6 Pro WordPress Plugin Development
				foreach ($tta as $dpfdomain) {
					$result .= '<link rel="dns-prefetch" href="' . $dpfdomain . '" />';
				}
			}
			echo $result;
		} // end enabled check
	} // end function
	
	// show admin messages to plugin user
	add_action('admin_notices', 'dpf_showAdminMessages');
	function dpf_showAdminMessages() {
		// http://wptheming.com/2011/08/admin-notices-in-wordpress/
		global $pagenow;
		if (current_user_can(DPF_PERMISSIONS_LEVEL)) { // user has privilege
			if ($pagenow == 'options-general.php') { // we are on Settings menu
				if (isset($_GET['page'])) {
					if ($_GET['page'] == dpf_get_slug()) { // we are on this plugin's settings page
						$options = dpf_getpluginoptions();
						if (!empty($options)) {
							$enabled = (bool)$options[DPF_DEFAULT_ENABLED_NAME];
							if (!$enabled) {
								echo '<div id="message" class="error">' . DPF_PLUGIN_NAME . ' ' . __('is currently disabled.', dpf_get_local()) . '</div>';
							}
						}
					}
				}
			} // end page check
		} // end privilege check
	} // end admin msgs function
	// enqueue admin CSS if we are on the plugin options page
	add_action('admin_head', 'insert_dpf_admin_css');
	function insert_dpf_admin_css() {
		global $pagenow;
		if (current_user_can(DPF_PERMISSIONS_LEVEL)) { // user has privilege
			if ($pagenow == 'options-general.php') { // we are on Settings menu
				if (isset($_GET['page'])) {
					if ($_GET['page'] == dpf_get_slug()) { // we are on this plugin's settings page
						dpf_admin_styles();
					}
				}
			}
		}
	}
	// add helpful links to plugin page next to plugin name
	// http://bavotasan.com/2009/a-settings-link-for-your-wordpress-plugins/
	// http://wpengineer.com/1295/meta-links-for-wordpress-plugins/
	add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'dpf_plugin_settings_link');
	add_filter('plugin_row_meta', 'dpf_meta_links', 10, 2);
	
	function dpf_plugin_settings_link($links) {
		return dpf_settingslink($links, dpf_get_slug(), dpf_get_local());
	}
	function dpf_meta_links($links, $file) {
		if ($file == plugin_basename(__FILE__)) {
			$links = array_merge($links,
			array(
				sprintf(__('<a href="http://wordpress.org/support/plugin/%s">Support</a>', dpf_get_local()), dpf_get_slug()),
				sprintf(__('<a href="http://wordpress.org/extend/plugins/%s/">Documentation</a>', dpf_get_local()), dpf_get_slug()),
				sprintf(__('<a href="http://wordpress.org/plugins/%s/faq/">FAQ</a>', dpf_get_local()), dpf_get_slug())
			));
		}
		return $links;	
	}
	// enqueue/register the admin CSS file
	function dpf_admin_styles() {
		wp_enqueue_style('dpf_admin_style');
	}
	function register_dpf_admin_style() {
		wp_register_style('dpf_admin_style',
			plugins_url(dpf_get_path() . '/css/admin.css'),
			array(),
			DPF_VERSION . "_" . date('njYHis', filemtime(dirname(__FILE__) . '/css/admin.css')),
			'all');
	}
	// when plugin is activated, create options array and populate with defaults
	register_activation_hook(__FILE__, 'dpf_activate');
	function dpf_activate() {
		$options = dpf_getpluginoptions();
		update_option(dpf_get_option(), $options);
		
		// delete option when plugin is uninstalled
		register_uninstall_hook(__FILE__, 'uninstall_dpf_plugin');
	}
	function uninstall_dpf_plugin() {
		delete_option(dpf_get_option());
	}
		
	// generic function that returns plugin options from DB
	// if option does not exist, returns plugin defaults
	function dpf_getpluginoptions() {
		return get_option(dpf_get_option(), 
			array(
				DPF_DEFAULT_ENABLED_NAME => DPF_DEFAULT_ENABLED, 
				DPF_DEFAULT_TEXT_NAME => DPF_DEFAULT_TEXT
			));
	}
	
	// encapsulate these and call them throughout the plugin instead of hardcoding the constants everywhere
	function dpf_get_slug() { return DPF_SLUG; }
	function dpf_get_local() { return DPF_LOCAL; }
	function dpf_get_option() { return DPF_OPTION; }
	function dpf_get_path() { return DPF_PATH; }

	function dpf_settingslink($linklist, $slugname = '', $localname = '') {
		$settings_link = sprintf( __('<a href="options-general.php?page=%s">Settings</a>', $localname), $slugname);
		array_unshift($linklist, $settings_link);
		return $linklist;
	}
	function dpf_getsupportinfo($slugname = '', $localname = '') {
		$output = __('Do you need help with this plugin? Check out the following resources:', $localname);
		$output .= '<ol>';
		$output .= '<li>' . sprintf( __('<a href="http://wordpress.org/extend/plugins/%s/">Documentation</a>', $localname), $slugname) . '</li>';
		$output .= '<li>' . sprintf( __('<a href="http://wordpress.org/plugins/%s/faq/">FAQ</a><br />', $localname), $slugname) . '</li>';
		$output .= '<li>' . sprintf( __('<a href="http://wordpress.org/support/plugin/%s">Support Forum</a><br />', $localname), $slugname) . '</li>';
		$output .= '<li>' . sprintf( __('<a href="http://www.jimmyscode.com/wordpress/%s">Plugin Homepage / Demo</a><br />', $localname), $slugname) . '</li>';
		$output .= '<li>' . sprintf( __('<a href="http://wordpress.org/extend/plugins/%s/developers/">Development</a><br />', $localname), $slugname) . '</li>';
		$output .= '<li>' . sprintf( __('<a href="http://wordpress.org/plugins/%s/changelog/">Changelog</a><br />', $localname), $slugname) . '</li>';
		$output .= '</ol>';
		
		$output .= sprintf( __('If you like this plugin, please <a href="http://wordpress.org/support/view/plugin-reviews/%s/">rate it on WordPress.org</a>', $localname), $slugname);
		$output .= sprintf( __(' and click the <a href="http://wordpress.org/plugins/%s/#compatibility">Works</a> button. ', $localname), $slugname);
		$output .= '<br /><br /><br />';
		$output .= __('Your donations encourage further development and support. ', $localname);
		$output .= '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7EX9NB9TLFHVW"><img src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" alt="Donate with PayPal" title="Support this plugin" width="92" height="26" /></a>';
		$output .= '<br /><br />';
		return $output;		
	}
	function dpf_checkifset($optionname, $optiondefault, $optionsarr) {
		return (isset($optionsarr[$optionname]) ? $optionsarr[$optionname] : $optiondefault);
	}
	function dpf_getlinebreak() {
	  echo '<tr valign="top"><td colspan="2"></td></tr>';
	}
	function dpf_explanationrow($msg = '') {
		echo '<tr valign="top"><td></td><td><em>' . $msg . '</em></td></tr>';
	}
	function dpf_getimagefilename($fname = '') {
		return plugins_url(dpf_get_path() . '/images/' . $fname);
	}
?>