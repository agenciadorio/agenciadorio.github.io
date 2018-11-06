<?php
class BunnyCDN 
{


	/**
		Returns the array of all the options with their default values in case they are not set
	*/
	public static function getOptions() {
        return wp_parse_args(
			get_option('bunnycdn'),
			array(
				"advanced_edit" => 		0,
				"pull_zone" => 			"",
				"cdn_domain_name" => 	"",
				"excluded" => 			BUNNYCDN_DEFAULT_EXCLUDED,
				"directories" => 		BUNNYCDN_DEFAULT_DIRECTORIES,
				"site_url" =>			get_option('home')
			)
		);
    }	

	/**
		Returns the option value for the given option name. If the value is not set, the default is returned.
	*/
	public static function getOption($option)
	{
		$options = BunnyCDN::getOption();
		return $options[$option];
	}

		
	
	public static function validateSettings($data)
	{
		$cdn_domain_name = BunnyCDN::cleanHostname($data['cdn_domain_name']);
		$pull_zone = BunnyCDN::cleanHostname($data['pull_zone']);

		if(strlen($cdn_domain_name) > 0 && BunnyCDN::endsWith($cdn_domain_name, BUNNYCDN_PULLZONEDOMAIN))
		{
			$pull_zone = substr($cdn_domain_name, 0, strlen($cdn_domain_name) - strlen(BUNNYCDN_PULLZONEDOMAIN) - 1);
		}
		else {
			$pull_zone = "";
		}
		
		$siteUrl = $data["site_url"];
		while(substr($siteUrl, -1) == '/') {
			$siteUrl = substr($siteUrl, 0, -1);
		}

		return array(
				"advanced_edit" => 		(int)($data['advanced_edit']),
				"pull_zone" => 			$pull_zone,
				"cdn_domain_name" => 	$cdn_domain_name,
				"excluded" => 			esc_attr($data['excluded']),
				"directories" => 		esc_attr($data['directories']),
				"site_url" =>			$siteUrl
			);
	}

	public static function cleanHostname($hostname)
	{
		$hostname = str_replace("http://", "", $hostname);
		$hostname = str_replace("https://", "", $hostname);

		return str_replace("/", "", $hostname);
	}

	public static function startsWith($haystack, $needle)
	{
		$length = strlen($needle);
		return (substr($haystack, 0, $length) === $needle);
	}

	public static function endsWith($haystack, $needle)
	{
		$length = strlen($needle);
		if ($length == 0) {
			return true;
		}

		return (substr($haystack, -$length) === $needle);
	}
}


class BunnyCDNSettings
{
	// Initialize the settigs page
	public static function initialize()
	{
		add_menu_page(
			"BunnyCDN", 				//$page_title
			"BunnyCDN", 				//$menu_title
			"manage_options", 			//$capability
			"bunnycdn", 				//$menu_slug
			array(						//$function 
				'BunnyCDNSettings',
				'outputSettingsPage'
			), 
			"dashicons-location");		//$icon_url
		
		register_setting('bunnycdn', 'bunnycdn', array("BunnyCDN", "validateSettings"));
	}

	public static function outputSettingsPage()
	{
		$options = BunnyCdn::getOptions();
		
		?> 
		<div class="tead" style="width: 550px; padding-top: 20px; margin-left: auto; margin-right: auto;">
			<a href="https://bunnycdn.com" target="_blank"><img width="250" src="<?php echo plugins_url('bunnycdn-logo.png', __FILE__ ); ?>"></img></a>
			<?php
				if(strlen(trim($options["cdn_domain_name"])) == 0)
				{
					echo '<h2>Enable BunnyCDN Content Delivery Network</h2>';
				}
				else 
				{
					echo '<h2>Configure BunnyCDN Content Delivery Network</h2>';
				}
			?>
			
			<form id="bunnycdn_options_form" method="post" action="options.php">
				<?php settings_fields('bunnycdn') ?>
				
				<input type="hidden" name="bunnycdn[advanced_edit]" id="bunnycdn_advanced_edit" value="<?php echo $options['advanced_edit']; ?>" />

				<!-- Simple settings -->
				<div id="bunnycdn-simple-settings" <?php if($options["advanced_edit"]) { echo 'style="display: none;"'; }?>>
					<p>To set up, enter the name of your Pull Zone that you have created on your BunnyCDN dashboard. If you haven't done that, you can <a href="https://bunnycdn.com/dashboard/pullzones/add?originUrl=<?php echo urlencode(get_option('home')); ?>" target="_blank">create a new pull zone now</a>. It should only take a minute. After that, just click on the Enable BunnyCDN button and enjoy a faster website.</p>
					<table class="form-table">
						<tr valign="top">
							<th scope="row">
								Pull Zone Name:
							</th>
							<td>
								<input type="text" maxlength="20" placeholder="mypullzone" name="bunnycdn[pull_zone]" id="bunnycdn_pull_zone" value="<?php echo $options['pull_zone']; ?>" size="64" class="regular-text code" />
								<p class="description">The name of the pull zone that you have created for this site. <strong>Do not include the .<?php echo BUNNYCDN_PULLZONEDOMAIN; ?></strong>. Leave empty to disable CDN integration.</p>
							</td>
						</tr>
					</table>
				</div>
				
				
				<!-- Advanced settings -->
				<table id="bunnycdn-advanced-settings" class="form-table" <?php if(!$options["advanced_edit"]) { echo 'style="display: none;"'; }?>>
					<tr valign="top">
						<th scope="row">
							CDN Domain Name:
						</th>
						<td>
							<input type="text" name="bunnycdn[cdn_domain_name]" placeholder="cdn.mysite.com" id="bunnycdn_cdn_domain_name" value="<?php echo $options['cdn_domain_name']; ?>" size="64" class="regular-text code" />
							<p class="description">The CDN domain that you you wish to use to rewrite your links to. This must be a fully qualified domain name and not the name of your pull zone. Leave empty to disable CDN integration.</p>
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row">
							Site URL:
						</th>
						<td>
							<input type="text" name="bunnycdn[site_url]" id="bunnycdn_site_url" value="<?php echo $options['site_url']; ?>" size="64" class="regular-text code" />
							<p class="description">The public URL where your website is accessible. Default for your configuration <code><?php echo get_option('home'); ?></code>.
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row">
							Excluded:
						</th>
						<td>
							<input type="text" name="bunnycdn[excluded]" id="bunnycdn_excluded" value="<?php echo $options['excluded']; ?>" size="64" class="regular-text code" />
							<p class="description">The links containing the listed phrases will be excluded from the CDN. Enter a <code>,</code> separated list without spaces.<br/><br/>Default value: <code><?php echo BUNNYCDN_DEFAULT_EXCLUDED; ?></code></p>
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row">
							Included Directories:
						</th>
						<td>
							<input type="text" name="bunnycdn[directories]" id="bunnycdn_directories" value="<?php echo $options['directories']; ?>" size="64" class="regular-text code" />
							<p class="description">Only the files linking inside of this directory will be pointed to their CDN url. Enter a <code>,</code> separated list without spaces.<br/><br/>Default value: <code><?php echo BUNNYCDN_DEFAULT_DIRECTORIES; ?></code></p>
						</td>
					</tr>
				</table>
				<?php 
					if(strlen(trim($options["cdn_domain_name"])) == 0)
					{
						submit_button("Enable BunnyCDN", "submit", "bunnycdn-save-button");
					}
					else 
					{
						submit_button("Update CDN Settings", "submit", "bunnycdn-save-button");
					}
				?>
				<a id="advanced-switch-url" href="#">Switch To Advanced View</a>
				<script>
					jQuery("#bunnycdn_pull_zone").keydown(function (e) {
						// Allow: backspace, delete, tab, escape, enter
						if (jQuery.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
							 // Allow: Ctrl+A, Command+A
							(e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
							 // Allow: home, end, left, right, down, up
							(e.keyCode >= 35 && e.keyCode <= 40) || 
							(e.keyCode >= 65 && e.keyCode <= 90)) {
								 // let it happen, don't do anything
								 return;
						}
						// Ensure that it is a number and stop the keypress
						if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
							e.preventDefault();
						}
					});

					jQuery("#bunnycdn_cdn_domain_name").keydown(function (e) {
						// Allow: backspace, delete, tab, escape, enter
						if (jQuery.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
							 // Allow: Ctrl+A, Command+A
							(e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
							 // Allow: home, end, left, right, down, up
							(e.keyCode >= 35 && e.keyCode <= 40) || 
							(e.keyCode >= 65 && e.keyCode <= 90)) {
								 // let it happen, don't do anything
								 return;
						}
						// Ensure that it is a number and stop the keypress
						if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
							e.preventDefault();
						}
					});
				
					jQuery("#advanced-switch-url").click(function(event) {
						if(jQuery('#bunnycdn-advanced-settings').css("display") == "none")
						{
							jQuery('#bunnycdn-advanced-settings').fadeIn("fast");
							jQuery('#bunnycdn-simple-settings').fadeOut("fast");
							jQuery("#advanced-switch-url").text("Switch To Simple View");
							jQuery("#bunnycdn_cdn_domain_name").focus();
							jQuery("#bunnycdn_advanced_edit").val("1");
						}
						else
						{
							jQuery('#bunnycdn-advanced-settings').fadeOut("fast");
							jQuery('#bunnycdn-simple-settings').fadeIn("fast");
							jQuery("#advanced-switch-url").text("Switch To Advanced View");
							jQuery("#bunnycdn_cdn_domain_name").focus();
							jQuery("#bunnycdn_advanced_edit").val("0");
						}
					});
					
					jQuery("#bunnycdn_pull_zone").change(function(event) {
						var name = jQuery("#bunnycdn_pull_zone").val();
						if(name.length > 0) 
						{
							var hostname = name + ".<?php echo BUNNYCDN_PULLZONEDOMAIN; ?>";
							jQuery("#bunnycdn_cdn_domain_name").val(hostname);
						}
						else {
							jQuery("#bunnycdn_cdn_domain_name").val("");
						}
					});
				</script>
			</form>
		</div><?php
	}
}

?>