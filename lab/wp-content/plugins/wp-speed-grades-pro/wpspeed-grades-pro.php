<?php
/*
Plugin Name: WP Speed Grades Lite
Author: WP Local SEO
Plugin URI: https://www.wplocalseo.com/solutions/wp-speed-grades-lite-plugin/
Author URI: https://www.wplocalseo.com
Description: Improves your Page Speed Grades for Google Insights, GT Metrix and Pingdom Tools. Reduces Loading Time. Easily add Google Analytics Tracking Code.
Text Domain: wp-speed-grades-pro
Version: 2.5.1
License: GPL2
*/

// DO NOT ALLOW DIRECT ACCESS
if ( !defined( 'ABSPATH' ) ) exit;

define( 'WP_SPEED_GRADES_PATH', plugin_dir_path( __FILE__ ) );					// Defining plugin dir path
define( 'WP_SPEED_GRADES_VERSION', 'v2.5.1');										// Defining plugin version
define( 'WP_SPEED_GRADES_NAME', 'WP Speed Grades Lite');					// Defining plugin name
STATIC $wpspgr_status;


/*--------------------------------------------------------------------------------------------------------
    Minify Html
---------------------------------------------------------------------------------------------------------*/

function wpspgrpro_init_minify_html() {
   ob_start('wpspgrpro_minify_html_output');
}

function wpspgrpro_minify_html_output($buffer) {
     
	$amp_detected = 0;
	if( strpos( $buffer, 'ampproject.org' ) !== false )  $amp_detected = 1;
	// Exclude AMP Pages
	if( $amp_detected == 0 ) {
        require( WP_SPEED_GRADES_PATH . 'lib/HTML.php' );
		$html_options = '';
		$buffer = Minify_HTML::minify( $buffer, $html_options );
		 }
	return ($buffer);	
}


/*--------------------------------------------------------------------------------------------------------
   START Header Clean up
---------------------------------------------------------------------------------------------------------*/

function wp_sp_gr_pro_start_cleanup() {
  add_action('init', 'wp_sp_gr_pro_cleanup_head');
} 

function wp_sp_gr_pro_cleanup_head() {
  remove_action( 'wp_head', 'rsd_link' );
  remove_action( 'wp_head', 'feed_links_extra', 3 );
  remove_action( 'wp_head', 'feed_links', 2 );
  remove_action( 'wp_head', 'wlwmanifest_link' );
  remove_action( 'wp_head', 'index_rel_link' );
  remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
  remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
  remove_action( 'wp_head', 'rel_canonical', 10, 0 );
  remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
  remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
  remove_action( 'wp_head', 'wp_generator' );
}


/*--------------------------------------------------------------------------------------------------------
   END Header Clean up
---------------------------------------------------------------------------------------------------------*/


/*--------------------------------------------------------------------------------------------------------
	Dequeue extra Font Awesome stylesheet
---------------------------------------------------------------------------------------------------------*/

function wpspgrpro_no_more_fontawesome() {
	global $wp_styles;

	 if ( ( !is_admin() ) ) {
	$patterns = array(
		'font-awesome.css',
		'font-awesome.min.css'
		);
	//	multiple patterns hook
	$regex = '/(' .implode('|', $patterns) .')/i';
	foreach( $wp_styles -> registered as $registered ) {
		if( !is_admin() and preg_match( $regex, $registered->src)  ) {
			wp_dequeue_style( $registered->handle );
			// FA was dequeued, so here we need to enqueue it again from CDN
			wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css' );
		}
     }		
	}	
}	//	End function wpspgrpro_no_more_fontawesome


/**
 * Create Settings Page
 */


// create custom plugin settings menu
add_action('admin_menu', 'wp_sp_gr_pro_create_menu');

function wp_sp_gr_pro_create_menu() {

	//create new top-level menu
    add_menu_page('WP Speed Grades', 'WP Speed Grades', 'administrator', __FILE__, 'wp_sp_gr_pro_settings_page' , plugins_url('/images/booster.png', __FILE__) );	
	add_action( 'admin_init', 'register_wp_sp_gr_pro_settings' );
	
}


function register_wp_sp_gr_pro_settings() {
	//register our settings
	register_setting( 'wp_sp_gr_pro-settings-group', 'a_emojis' );
	register_setting( 'wp_sp_gr_pro-settings-group', 'a_queries' );
	register_setting( 'wp_sp_gr_pro-settings-group', 'a_queriesrev' );
	register_setting( 'wp_sp_gr_pro-settings-group', 'a_super' );
	register_setting( 'wp_sp_gr_pro-settings-group', 'a_headclean' );
	register_setting( 'wp_sp_gr_pro-settings-group', 'a_fontawesome' );
    register_setting( 'wp_sp_gr_pro-settings-group', 'a_useglibraries' );
	register_setting( 'wp_sp_gr_pro-settings-group', 'a_htaccess' );
	register_setting( 'wp_sp_gr_pro-settings-group', 'wpspgrprostatus' );
	register_setting( 'wp_sp_gr_pro-settings-group', 'a_wpspgrprohtml' );
	register_setting( 'wp_sp_gr_pro-settings-group', 'a_lazyload' );
	register_setting( 'wp_sp_gr_pro-settings-group', 'a_asyncdefer' );
	register_setting( 'wp_sp_gr_pro-settings-group', 'a_pagecache' );
	register_setting( 'wp_sp_gr_pro-settings-group', 'a_wpspgrproheart' );
	register_setting( 'wp_sp_gr_pro-settings-group', 'a_analytics' );
	register_setting( 'wp_sp_gr_pro-settings-group', 'a_analyticstracking' );
	register_setting( 'wp_sp_gr_pro-settings-group', 'a_anonymizeip' );
	register_setting( 'wp_sp_gr_pro-settings-group', 'a_norss' );
	register_setting( 'wp_sp_gr_pro-settings-group', 'a_jpgquality' );
	register_setting( 'wp_sp_gr_pro-settings-group', 'a_thejpgquality' );
	register_setting( 'wp_sp_gr_pro-settings-group', 'a_imagesdims' );
}

	$wpspgr_choice1 = get_option( 'a_emojis' );
	$wpspgr_choice2 = get_option( 'a_queries');
	$wpspgr_choice3 = get_option( 'a_queriesrev');
	$wpspgr_choice6 = get_option( 'a_headclean');
	$wpspgr_choice8 = get_option( 'a_wpspgrprohtml');
	$wpspgr_choice22 = get_option( 'a_fontawesome');
	$wpspgr_choice4 = get_option( 'a_super');
	$wpspgr_choice1heat = get_option( 'a_wpspgrproheart');
	$wpspgr_choice41 = get_option( 'a_analytics');
	$wpspgr_choice42 = get_option( 'a_analyticstracking');
	$wpspgr_anonymizeip = get_option( 'a_anonymizeip');
	$wpspgr_norss = get_option( 'a_norss');
	$wpspgr_imagesdims = get_option( 'a_imagesdims');

function wp_sp_gr_pro_settings_page() {

$wpspgr_choice42 = get_option( 'a_analyticstracking');
?>

<div class="wrap" style="padding: 10px;">

<h2><strong>WP SPEED GRADES LITE DASHBOARD</strong></h2>
<br/>
<form method="post" action="options.php">
    <?php settings_fields( 'wp_sp_gr_pro-settings-group' ); ?>
    <?php do_settings_sections( 'wp_sp_gr_pro-settings-group' ); ?>
	<?php
	wp_register_style('wpspgr', plugins_url('wp-speed-grades-pro/css/wpspeedgrades-main.css'), false, '2.5.1', 'all');
	wp_print_styles(array('wpspgr', 'wpspgr'));
	?>
<div class="wpspgl-box-wpspgrpro" style="padding-left: 20px; height: 149px;">
<p align="center"><img style="width: 100%; height: auto;" src="<?php echo plugins_url( '/images/banner-wpspeedgrades.jpg', __FILE__ ); ?>" align="center" /></p>
</div>

<div class="wpspgl-box-wpspgrpro" style="padding-left: 20px; text-align: center; height: 149px; width: 500px;">
<h3>Rate us on wordpress.org</h3>
<table style="width: 100%;">
<tr>
<td>
<p style="font-size: 16px; textalign: center; color: #000; padding: 10px;"><a style="margin-left: 10px; color: #fff; background-color: #ef5146; border-color: #bf2c22; padding: .6rem 1.45rem; text-decoration: none; font-size: 1.115rem; line-height: 1.5125rem; border-radius: 4px;" target="_blank" href="https://wordpress.org/support/plugin/wp-speed-grades-pro/reviews/?rate=5#new-post">GIVE US A 5 STAR REVIEW</a>
</td>
</tr>
</table>
</div>

<div class="clear"></div>

<div class="wpspgl-box-wpspgrpro" style="padding-left: 20px; height: 625px;">
<h3>Clean UP Options</h3>
<table class="form-table">

<tr>		
<th scope="row" style="width: 80%; border-style: solid; border-width: 0px 0px 1px 0px; border-color: #f4f4f4;">
<div class="wpsgl-switch">
			<input id="a_headclean" name="a_headclean" class="wpsgl-cmn-toggle wpsgl-cmn-toggle-round" type="checkbox" value="1" <?php checked( '1', get_option( 'a_headclean' ) ); ?> />
			<label for="a_headclean"></label>
</div>
Header Cleanup</th>		
</tr>

<tr>		
<th scope="row" style="width: 80%; border-style: solid; border-width: 0px 0px 1px 0px; border-color: #f4f4f4;">
<div class="wpsgl-switch">
			<input id="a_emojis" name="a_emojis" class="wpsgl-cmn-toggle wpsgl-cmn-toggle-round" type="checkbox" value="1" <?php checked( '1', get_option( 'a_emojis' ) ); ?> />
			<label for="a_emojis"></label>
</div>
Remove Emojis</th>		
</tr>

<tr>		
<th scope="row" style="width: 80%; border-style: solid; border-width: 0px 0px 1px 0px; border-color: #f4f4f4;">
<div class="wpsgl-switch">
			<input id="a_queries" name="a_queries" class="wpsgl-cmn-toggle wpsgl-cmn-toggle-round" type="checkbox" value="1" <?php checked( '1', get_option( 'a_queries' ) ); ?> />
			<label for="a_queries"></label>
</div>
Remove Query Strings</th>		
</tr>

<tr>		
<th scope="row" style="width: 80%; border-style: solid; border-width: 0px 0px 1px 0px; border-color: #f4f4f4;">
<div class="wpsgl-switch">
			<input id="a_queriesrev" name="a_queriesrev" class="wpsgl-cmn-toggle wpsgl-cmn-toggle-round" type="checkbox" value="1" <?php checked( '1', get_option( 'a_queriesrev' ) ); ?> />
			<label for="a_queriesrev"></label>
</div>
Remove Query Strings (RevSlider)</th>		
</tr>

<tr>		
<th scope="row" style="width: 80%; border-style: solid; border-width: 0px 0px 1px 0px; border-color: #f4f4f4;">
<div class="wpsgl-switch">
			<input id="a_fontawesome" name="a_fontawesome" class="wpsgl-cmn-toggle wpsgl-cmn-toggle-round" type="checkbox" value="1" <?php checked( '1', get_option( 'a_fontawesome' ) ); ?> />
			<label for="a_fontawesome"></label>
</div>
Remove Extra Font Awesome</th>		
</tr>
</table>

<div style="background-color: #f5f5f5; padding: 10px; margin-left: -10px;">
<table>
<tr>
<td width="100%" style="vertical-align: top;"><img src="<?php echo plugins_url( '/images/icon-help.png', __FILE__ ); ?>" align="center" style="float: left; padding-right: 15px;"/> <strong>INFO</strong>
<br/>
<hr/>
</td>
</tr>
<tr>
<td width="100%">
<li><strong>Header Cleanup: </strong>Removes Feed Links, WlWmanifest links, Shortlinks, WP Generator, RSD Links etc.</li>
<li><strong>Remove Emojis: </strong>This option disables the Emoji functionality.</li>
<li><strong>Remove Query Strings: </strong>This option will remove query strings from static resources like CSS & JS files, and will improve your speed scores in services like PageSpeed, YSlow, Pingdom and GTmetrix.</li>
<li><strong>Remove Query Strings (RevSlider): </strong>This option will additionaly remove query strings from RevSlider.</li>
<li><strong>Font Awesome: </strong>Removes extra Font Awesome stylesheet.</li>
</td>
</tr>
</table>
</div>

</div>


<div class="wpspgl-box-wpspgrpro" style="padding-left: 20px; height: 625px;">
<h3>Tracking Options</h3>
<table class="form-table">


<tr>		
<th scope="row" style="width: 100%; border-style: solid; border-width: 0px 0px 1px 0px; border-color: #f4f4f4;">
<div class="wpsgl-switch">
			<input id="a_analytics" name="a_analytics" class="wpsgl-cmn-toggle wpsgl-cmn-toggle-round" type="checkbox" value="1" <?php checked( '1', get_option( 'a_analytics' ) ); ?> />
			<label for="a_analytics"></label>
</div>
<span style="font-size: 16px;">Activate Google Analytics Tracking Code</span></th>		
</tr>

<tr>		
<th scope="row" style="width: 100%; border-style: solid; border-width: 0px 0px 1px 0px; border-color: #f4f4f4; text-align: center;">
<b>Your Tracking ID : </b><input type="text" name="a_analyticstracking" style="width: 130px;" value="<?php echo $wpspgr_choice42; ?>" placeholder="ex. UA-XXXXXXX-X">
</th>		
</tr>
<tr>		
<th scope="row" style="width: 100%; border-style: solid; border-width: 0px 0px 1px 0px; border-color: #f4f4f4;">
<div class="wpsgl-switch">
			<input id="a_anonymizeip" name="a_anonymizeip" class="wpsgl-cmn-toggle wpsgl-cmn-toggle-round" type="checkbox" value="1" <?php checked( '1', get_option( 'a_anonymizeip' ) ); ?> />
			<label for="a_anonymizeip"></label>
</div>
<span>Anonymize IP Addresses</span></th>		
</tr>
</table>

<div style="background-color: #f5f5f5; padding: 10px; margin-left: -10px; margin-top: 120px; height: 225px;">
<table>
<tr>
<td width="100%" style="vertical-align: top;"><img src="<?php echo plugins_url( '/images/icon-help.png', __FILE__ ); ?>" align="center" style="float: left; padding-right: 15px;"/> <strong>INFO</strong>
<br/>
<hr/>
</td>
</tr>
<tr>
<td width="100%">
<li>Enables Google Analytics for your entire WordPress site. Just Add your Tracking ID ( ex. UA-XXXXXXX-X )</li>
<li>Remove any other instances of your tracking code. ( ex. your Theme, or another plugin )</li>
<li>We are using <strong>Global Site Tag</strong> (gtag.js). A JavaScript tagging framework and API that allows you to send event data to Google Analytics, AdWords, and Doubleclick.</li>
<li><strong>Anonymize IP Addresses :</strong> In some cases, you might need to anonymize the IP addresses of hits sent to Google Analytics. ( Example: GDPR Compliance )
</td>
</tr>
</table>
</div>

</div>

<div class="clear"></div>


<div class="wpspgl-box-wpspgrpro" style="padding-left: 20px;">
<h3>Optimization Options</h3>
<table class="form-table">
<tr>		
<th scope="row" style="width: 80%; border-style: solid; border-width: 0px 0px 1px 0px; border-color: #f4f4f4;">
<div class="wpsgl-switch">
			<input id="a_wpspgrprohtml" name="a_wpspgrprohtml" class="wpsgl-cmn-toggle wpsgl-cmn-toggle-round" type="checkbox" value="1" <?php checked( '1', get_option( 'a_wpspgrprohtml' ) ); ?> />
			<label for="a_wpspgrprohtml"></label>
</div>
Minify HTML</th>		
</tr>

<tr>		
<th scope="row" style="width: 80%; border-style: solid; border-width: 0px 0px 1px 0px; border-color: #f4f4f4;">
<div class="wpsgl-switch">
			<input id="a_super" name="a_super" class="wpsgl-cmn-toggle wpsgl-cmn-toggle-round" type="checkbox" value="1" <?php checked( '1', get_option( 'a_super' ) ); ?> />
			<label for="a_super"></label>
</div>
<span style="font-weight: 600;">JS Defer Booster Special</span></th>		
</tr>

<tr>

<th scope="row" style="width: 80%; border-style: solid; border-width: 0px 0px 1px 0px; border-color: #f4f4f4; padding: 0px;">
<table>
<tr>
<td width="70%" style="padding: 0px;">
<div class="wpsgl-switch">
			<input id="a_jpgquality" name="a_jpgquality" class="wpsgl-cmn-toggle wpsgl-cmn-toggle-round" type="checkbox" value="1" <?php checked( '1', get_option( 'a_jpgquality' ) ); ?> />
			<label for="a_jpgquality"></label>
</div>
<span>JPG Compression</span>
</td>

<td width="30%">
<?php $jpqual = get_option( 'a_thejpgquality' ) ?>
			<select name="a_thejpgquality">
			<option value="100" <?php if ( $jpqual == 100 ) { ?>selected <?php } ?>>Quality 100</option>
			<option value="90" <?php if ( $jpqual == 90 ) { ?>selected <?php } ?>>Quality 90</option>
			<option value="80" <?php if ( $jpqual == 80 ) { ?>selected <?php } ?>>Quality 80</option>
			<option value="70" <?php if ( $jpqual == 70 ) { ?>selected <?php } ?>>Quality 70</option>
			<option value="60" <?php if ( $jpqual == 60 ) { ?>selected <?php } ?>>Quality 60</option>
			</select>
</td>
</tr>
</table>
</th>		
</tr>
</table>

<div style="background-color: #f5f5f5; padding: 10px; margin-left: -10px; margin-top: 7px; height: 180px;">
<table>
<tr>
<td width="100%" style="vertical-align: top;"><img src="<?php echo plugins_url( '/images/icon-help.png', __FILE__ ); ?>" align="center" style="float: left; padding-right: 15px;"/> <strong>INFO</strong>
<br/>
<hr/>
</td>
</tr>
<tr>
<td width="100%">
<li><strong>Minify HTML: </strong>HTML Minification & Optimization.</li>
<li><strong>JS Defer Booster Special: </strong>a Special Optimized Code on your <u>pages</u> for <font style="color: green;"><b><u>Additional Grades on Google Insights.</u></b></font></li>
<li><strong>JPEG Quality: </strong>Choose the Image Quality for New Uploaded Images. Lower Quality means Smaller Image Size. Default is 82</li>
</td>
</tr>
</table>
</div>

</div>


<div class="wpspgl-box-wpspgrpro" style="padding-left: 20px;">
<h3>Specials</h3>
<table class="form-table">

<tr>		
<th scope="row" style="width: 80%; border-style: solid; border-width: 0px 0px 1px 0px; border-color: #f4f4f4;">
<div class="wpsgl-switch">
			<input id="a_wpspgrproheart" name="a_wpspgrproheart" class="wpsgl-cmn-toggle wpsgl-cmn-toggle-round" type="checkbox" value="1" <?php checked( '1', get_option( 'a_wpspgrproheart' ) ); ?> />
			<label for="a_wpspgrproheart"></label>
</div>
<span>Change Heartbeat Interval</span></th>		
</tr>

<tr>		
<th scope="row" style="width: 80%; border-style: solid; border-width: 0px 0px 1px 0px; border-color: #f4f4f4;">
<div class="wpsgl-switch">
			<input id="a_norss" name="a_norss" class="wpsgl-cmn-toggle wpsgl-cmn-toggle-round" type="checkbox" value="1" <?php checked( '1', get_option( 'a_norss' ) ); ?> />
			<label for="a_norss"></label>
</div>
<span>Disable RSS Feeds</span></th>		
</tr>

<tr>		
<th scope="row" style="width: 80%; border-style: solid; border-width: 0px 0px 1px 0px; border-color: #f4f4f4;">
<div class="wpsgl-switch">
			<input id="a_imagesdims" name="a_imagesdims" class="wpsgl-cmn-toggle wpsgl-cmn-toggle-round" type="checkbox" value="1" <?php checked( '1', get_option( 'a_imagesdims' ) ); ?> />
			<label for="a_imagesdims"></label>
</div>
<span>Specify Images Dimensions</span></th>		
</tr>

</table>		

<div style="background-color: #f5f5f5; padding: 10px; margin-left: -10px; margin-top: 7px; height: 180px;">
<table>
<tr>
<td width="100%" style="vertical-align: top;"><img src="<?php echo plugins_url( '/images/icon-help.png', __FILE__ ); ?>" align="center" style="float: left; padding-right: 15px;"/> <strong>INFO</strong>
<br/>
<hr/>
</td>
</tr>
<tr>
<td width="100%">
<li><strong>Change Heartbeat Interval: </strong>Changes default HeartBeat Pulse to 60 secs, saving server resources</li>
<li><strong>Disable RSS Feeds : </strong>RSS feeds allow users to subscribe to your blog posts. However when building small static websites, you may want to turn off the RSS feeds</li>
<li><strong>Specify Images Dimensions : </strong>Specifying a width and height for all images allows for faster rendering by eliminating the need for unnecessary reflows and repaints.</li>

</td>
</tr>
</table>

</div>
</div>

<div class="clear"></div>

<div class="wpspgl-box-wpspgrpro" style="padding-left: 20px;">
<h3>Ready to Save Settings?</h3>
<style>
p.submit {
    text-align: center;
    max-width: 100%;
    margin-top: 20px;
    padding-top: 10px;
}
</style>
<table class="form-table" style="text-align: center;">
<tr><td style="text-align: center;">
<p align="center" style="text-align: center; width: 100%;"><?php submit_button(); ?></p>
</td></tr>
</table>
</div>

<div class="wpspgl-box-wpspgrpro" style="padding-left: 20px; padding-bottom: 25px;">
<h3>WP LOCAL SEO</h3>
<table style="text-align: center;" width="100%">
<tr>
<td style="text-align: center;">
<span style="font-size: 18px; color: #9c9c9c;">Speed Optimization - Local SEO - Web Design</span>
</td>
</tr>
<tr>
<td style="text-align: center; padding-top: 30px;">
<a target="_blank" style="color: #fff; background-color: #00709e; border-color: #005e85; padding: .5rem 1.25rem; text-decoration: none; font-size: .875rem; line-height: 1.3125rem; border-radius: 4px;" href="https://www.wplocalseo.com">VISIT OUR WEBSITE</a>
</td>
</tr>
</table>
</div>

</form>

<br/><br/>


<div class="wpspgl-box-wpspgrpro" style="padding-left: 20px; text-align: center; height: 110px; width: 1033px;">
<table style="width: 100%;">
<tr>
<td>
<p style="font-size: 16px; textalign: center; color: #000; padding: 10px;"><strong style="padding-bottom: 20px;">OTHER PLUGINS</strong><br/> <br/>
<a style="color: #fff; background-color: #00709e; border-color: #005e85; padding: .5rem 1.25rem; text-decoration: none; font-size: .875rem; line-height: 1.3125rem; border-radius: 4px;" target="_blank" href="https://wordpress.org/plugins/wpspeed-localbusiness-schema/">JSON-LD SCHEMA</a>  <a style="color: #fff; background-color: #00709e; border-color: #005e85; padding: .5rem 1.25rem; text-decoration: none; font-size: .875rem; line-height: 1.3125rem; border-radius: 4px;" target="_blank" href="https://wordpress.org/plugins/cache-cleaner/">CACHE CLEANER</a></p>
</td>
</tr>
</table>
</div>


<div class="wpspgl-box-wpspgrpro" style="padding-left: 20px; text-align: center; width: 1033px;">
<table style="width: 100%;">
<tr>
<td>
<p style="font-size: 17px; textalign: center; background-color: #f5ae42; color: #fff; padding: 10px;">SOLUTIONS</p>
</td>
</tr>
</table>
<table style="width: 100%;">
<tr>
<td style="width:33%">
<p align="center"><a target="_blank" href="https://www.wirelocal.com"><img style="width: 100%; height: auto;" src="<?php echo plugins_url( '/images/wirelocal-300.jpg', __FILE__ ); ?>" align="center" /></a></p>
</td>
<td style="width:33%">
<p align="center"><a target="_blank" href="https://www.wplocalseo.com/solutions/"><img style="width: 100%; height: auto;" src="<?php echo plugins_url( '/images/speed.jpg', __FILE__ ); ?>" align="center" /></a></p>
</td>
<td style="width:33%">
<p align="center"><a target="_blank" href="https://www.wplocalseo.com/solutions/"><img style="width: 100%; height: auto;" src="<?php echo plugins_url( '/images/json-ld.jpg', __FILE__ ); ?>" align="center" /></a></p>
</td>
</tr>
</table>
</div>
 
</div>

<?php }	
	
/**
 * Disable Emojis
 */


function wpspgr_disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );	
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );	
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'tiny_mce_plugins', 'wpspgr_disable_emojis_tinymce' );
	
	
}


if ( $wpspgr_choice8== 1 ) {
  if ( !is_admin() ) add_action( 'template_redirect', 'wpspgrpro_init_minify_html' , 1 );
}

if ( $wpspgr_choice22=='1' ) {

 //	Extra Font Awesome
			if ( !is_admin() ) {
				add_action( 'wp_enqueue_scripts', 'wpspgrpro_no_more_fontawesome', 9999 );
			}
}

if ( $wpspgr_choice1=='1' ) {
add_action( 'init', 'wpspgr_disable_emojis' );

}

function wpspgr_disable_emojis_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	} else {
		return array();
	}
}


/**
 * Add Remove query strings from static resources
 */

function remove_query7( $src ){	
	$rqs = explode( '?ver', $src );
        return $rqs[0];
}

     
		if ( is_admin() ) { }
		else {

if ( $wpspgr_choice2=='1' ) {
add_filter( 'script_loader_src', 'remove_query7', 15, 1 );
add_filter( 'style_loader_src', 'remove_query7', 15, 1 );
                      }
}

function remove_query8( $src ){
	$rqs = explode( '&ver', $src );
        return $rqs[0];
}
		if ( is_admin() ) { }
		else {
		
if ( $wpspgr_choice2=='1' ) { 		
add_filter( 'script_loader_src', 'remove_query8', 15, 1 );
add_filter( 'style_loader_src', 'remove_query8', 15, 1 );
                      }
}
 
function remove_query9( $src ){
	$rqs = explode( '?rev', $src );
        return $rqs[0];
}
    
 
		if ( is_admin() ) { }
		else {
		
if ( $wpspgr_choice3==true ) {		
add_filter( 'script_loader_src', 'remove_query9', 15, 1 );
add_filter( 'style_loader_src', 'remove_query9', 15, 1 );	
                      }
} 
 

/**
 * Add Defer Parsing
 */ 
function wp_sp_gr_pro_wpse33108() {
?>
    <script defer="defer" type="text/javascript">
    window.onload=function(){
        var pegasusmycode;
        pegasusmycode=document.createElement("script");
        pegasusmycode.type="text/javascript";
        pegasusmycode.src="<?php echo base_url(TRUE); ?>";
        document.getElementsByTagName("head")[0].appendChild(pegasusmycode);
    }
</script>
<?php 
}

if ( ( $wpspgr_choice4 == 1 ) ) {
add_action( 'wp_footer', 'wp_sp_gr_pro_wpse33108', 9999999 );
} 

/**
 * Add Analytics Code
 */
function wp_sp_gr_pro_googleanalytics() {
	$wpspgr_choice42 = get_option( 'a_analyticstracking');
	$wpspgr_anonymizeip = get_option( 'a_anonymizeip');
?>
<!-- Google Analytics by WP Speed Grades Lite -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $wpspgr_choice42; ?>"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date()); <?php if ( $wpspgr_anonymizeip == 1 ) { ?> 
  gtag('config', '<?php echo $wpspgr_choice42; ?>', { 'anonymize_ip': true }); <?php } else { ?>
  gtag('config', '<?php echo $wpspgr_choice42; ?>'); <?php } ?>
</script>
<!-- END Google Analytics by WP Speed Grades Lite -->
<?php 
} 
 

if ( ( $wpspgr_choice41 == 1 ) ) {
add_action( 'wp_head', 'wp_sp_gr_pro_googleanalytics' );
} 
 
 
if (!function_exists('base_url')) {
    function base_url($atRoot=FALSE, $atCore=FALSE, $parse=FALSE){
        if (isset($_SERVER['HTTP_HOST'])) {
            $http = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
            $hostname = $_SERVER['HTTP_HOST'];
            $dir =  str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);

            $core = preg_split('@/@', str_replace($_SERVER['DOCUMENT_ROOT'], '', realpath(dirname(__FILE__))), NULL, PREG_SPLIT_NO_EMPTY);
            $core = $core[0];

            $tmplt = $atRoot ? ($atCore ? "%s://%s/%s/" : "%s://%s/") : ($atCore ? "%s://%s/%s/" : "%s://%s%s");
            $end = $atRoot ? ($atCore ? $core : $hostname) : ($atCore ? $core : $dir);
            $base_url = sprintf( $tmplt, $http, $hostname, $end );
        }
        else $base_url = 'http://localhost/';

        if ($parse) {
            $base_url = parse_url($base_url);
            if (isset($base_url['path'])) if ($base_url['path'] == '/') $base_url['path'] = '';
        }
        return $base_url;
    }
}
 

function wp_sp_gr_pro_add_signature() { ?>
<!-- WP Speed Grades Activated. | See the Pegasus project http://www.wp-speed.com -->
<?php }
// add_action( 'wp_footer', 'wp_sp_gr_pro_add_signature', 10000000 );


if ( $wpspgr_choice6=='1' ) {

 //	Header Clean up
			if ( !is_admin() ) {
				wp_sp_gr_pro_start_cleanup();		
			}
			else { }
}			

// Change HeartBeat

function wpspgrpro_heartbeat_settings( $settings ) {
    $settings['interval'] = 60;
    return $settings;
}

$wpspgr_choice1heart = get_option( 'a_wpspgrproheart');
if ( $wpspgr_choice1heart == 1  ) {
				add_filter( 'heartbeat_settings', 'wpspgrpro_heartbeat_settings' );
}


// Disable RSS

function wpb_disable_feed() {
wp_die( __('No feed available, Please visit our <a href="'. get_bloginfo('url') .'">homepage</a>!') );
}
 
if ( $wpspgr_norss == 1 ) {
	add_action('do_feed', 'wpb_disable_feed', 1);
	add_action('do_feed_rdf', 'wpb_disable_feed', 1);
	add_action('do_feed_rss', 'wpb_disable_feed', 1);
	add_action('do_feed_rss2', 'wpb_disable_feed', 1);
	add_action('do_feed_atom', 'wpb_disable_feed', 1);
	add_action('do_feed_rss2_comments', 'wpb_disable_feed', 1);
	add_action('do_feed_atom_comments', 'wpb_disable_feed', 1);	
}

// Specify Images Dimensions

function wpspeedgrades_images_dimensions ( $buffer ) {

	// Get all images without width or height attribute
	preg_match_all( '/<img(?:[^>](?!(height|width)=))*+>/i' , $buffer, $images_match );
	foreach ( $images_match[0] as $image ) {
		// Compatibility with Lazy-load images or Photon
		if ( strpos( $image, 'data-original' ) || strpos( $image, 'data-no-image-dimensions' ) || strpos( $image, '.wp.com' ) ) {
			continue;
		}
		$tmp = $image;
		// Get link of the file
        preg_match( '/src=[\'"]([^\'"]+)/', $image, $src_match );
			// Get infos of the URL
			$image_url = parse_url( $src_match[1] );
			// Get image attributes
			$sizes = getimagesize( ABSPATH . $image_url['path'] );
		 
		if ( ! empty( $sizes ) ) {
			// Add width and width attribute
			$image = str_replace( '<img', '<img ' . $sizes[3], $image );
			// Replace image with new attributes
	        $buffer = str_replace( $tmp, $image, $buffer );
		}
	}
	return $buffer;
}

function wpspgrpro_specify_images() {
   ob_start('wpspeedgrades_images_dimensions');
}

// Images Dimensions
if ( $wpspgr_imagesdims == 1  ) {				
  if ( !is_admin() ) add_action( 'template_redirect', 'wpspgrpro_specify_images', 2 );
}


// JPG Quality
function wpspeedgrades_jpeg_quality( $quality ) { 
    $jpgqual = get_option( 'a_thejpgquality' );
    return $jpgqual;
}

function wpspeedgrades_change_jpg_quality () {
 //	JPG Quality Filter
			add_filter( 'jpeg_quality', 'wpspeedgrades_jpeg_quality' );
}

// Proceed to JPG Quality Filter
$wpspgr_dojpgquality = get_option( 'a_jpgquality');
if ( $wpspgr_dojpgquality == 1 ) {	
	wpspeedgrades_change_jpg_quality ();
}