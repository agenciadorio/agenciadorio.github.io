<?php
/*
Plugin Name: Medusa Multifunctional
Author: WP Local SEO
Plugin URI: https://www.wplocalseo.com
Author URI: https://www.wplocalseo.com
Description: Speed Improvement, Security, Tracking, Advanced Tools and more.
Text Domain: wp-speed-grades-pro
Version: 3.0.3
License: GPL2
*/

// DO NOT ALLOW DIRECT ACCESS
if ( !defined( 'ABSPATH' ) ) exit;

define( 'WP_SPEED_GRADES_PATH', plugin_dir_path( __FILE__ ) );					// Defining plugin dir path
define( 'WP_SPEED_GRADES_VERSION', '3.0.3');										// Defining plugin version
define( 'WP_SPEED_GRADES_NAME', 'Medusa');					// Defining plugin name
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
  //remove_action( 'wp_head', 'wp_generator' );
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
    add_menu_page('Medusa', 'Medusa', 'administrator', __FILE__, 'wp_sp_gr_pro_settings_page' , plugins_url('/images/booster.png', __FILE__) );

//	add_submenu_page('Optimization', 'Optimization', 'administrator', __FILE__, 'medusa_render_optimization_page' , plugins_url('/images/booster.png', __FILE__) );	
//	add_submenu_page('Scripts', 'Scripts', 'administrator', __FILE__, 'medusa_render_scripts_page' , plugins_url('/images/booster.png', __FILE__) );
//	add_submenu_page('Specials', 'Specials', 'administrator', __FILE__, 'medusa_render_specials_page' , plugins_url('/images/booster.png', __FILE__) );
	
	add_submenu_page(__FILE__, 'Medusa', 'Dashboard', 'manage_options', __FILE__,'wp_sp_gr_pro_settings_page');
	add_submenu_page(__FILE__, 'Medusa', 'Optimization', 'manage_options', __FILE__.'/optimization', 'medusa_render_optimization_page');
	add_submenu_page(__FILE__, 'Medusa', 'Security', 'manage_options', __FILE__.'/security', 'medusa_render_security_page');
	add_submenu_page(__FILE__, 'Medusa', 'Scripts - Tracking', 'manage_options', __FILE__.'/scripts', 'medusa_render_scripts_page');
	add_submenu_page(__FILE__, 'Medusa', 'Tools - Specials', 'manage_options', __FILE__.'/specials', 'medusa_render_specials_page');
	add_action( 'admin_init', 'register_wp_sp_gr_pro_settings' );
	
}


function register_wp_sp_gr_pro_settings() {
	//register our settings
	register_setting( 'wp_sp_gr_pro-settings-group1', 'a_emojis' );
	register_setting( 'wp_sp_gr_pro-settings-group1', 'a_queries' );
	register_setting( 'wp_sp_gr_pro-settings-group1', 'a_queriesrev' );
	register_setting( 'wp_sp_gr_pro-settings-group1', 'a_super' );
	register_setting( 'wp_sp_gr_pro-settings-group1', 'a_headclean' );
	register_setting( 'wp_sp_gr_pro-settings-group1', 'a_fontawesome' );
    register_setting( 'wp_sp_gr_pro-settings-group1', 'a_useglibraries' );
	register_setting( 'wp_sp_gr_pro-settings-group1', 'a_htaccess' );
	register_setting( 'wp_sp_gr_pro-settings-group1', 'wpspgrprostatus' );
	register_setting( 'wp_sp_gr_pro-settings-group1', 'a_wpspgrprohtml' );
	register_setting( 'wp_sp_gr_pro-settings-group1', 'a_lazyload' );
	register_setting( 'wp_sp_gr_pro-settings-group1', 'a_asyncdefer' );
	register_setting( 'wp_sp_gr_pro-settings-group1', 'a_pagecache' );
	register_setting( 'wp_sp_gr_pro-settings-group1', 'medusa_jquery' );
	register_setting( 'wp_sp_gr_pro-settings-group4', 'a_wpspgrproheart' );
	register_setting( 'wp_sp_gr_pro-settings-group3', 'a_analytics' );
	register_setting( 'wp_sp_gr_pro-settings-group3', 'a_analyticstracking' );
	register_setting( 'wp_sp_gr_pro-settings-group3', 'a_anonymizeip' );
	register_setting( 'wp_sp_gr_pro-settings-group3', 'medusa_scriptsfooter' );
	register_setting( 'wp_sp_gr_pro-settings-group3', 'medusa_addscriptsfooter' );
	register_setting( 'wp_sp_gr_pro-settings-group4', 'a_norss' );
	register_setting( 'wp_sp_gr_pro-settings-group1', 'a_jpgquality' );
	register_setting( 'wp_sp_gr_pro-settings-group1', 'a_thejpgquality' );
	register_setting( 'wp_sp_gr_pro-settings-group1', 'a_imagesdims' );
	register_setting( 'wp_sp_gr_pro-settings-group4', 'a_aupd' );
	register_setting( 'wp_sp_gr_pro-settings-group4', 'a_autoembeds' );
	register_setting( 'wp_sp_gr_pro-settings-group3', 'a_gtaglogin' );
	register_setting( 'wp_sp_gr_pro-settings-group7', 'medusa_tracking' );
	register_setting( 'wp_sp_gr_pro-settings-group7', 'medusa_key' );
	register_setting( 'wp_sp_gr_pro-settings-group2', 'medusa_wpversion' );
	register_setting( 'wp_sp_gr_pro-settings-group2', 'medusa_xmlrpc' );
	register_setting( 'wp_sp_gr_pro-settings-group2', 'medusa_loginerrors' );
	register_setting( 'wp_sp_gr_pro-settings-group2', 'medusa_fileeditor' );
	}

	$wpspgr_choice1 = get_option( 'a_emojis' );
	$wpspgr_choice2 = get_option( 'a_queries');
	$wpspgr_choice3 = get_option( 'a_queriesrev');
	$wpspgr_choice6 = get_option( 'a_headclean');
	$wpspgr_choice8 = get_option( 'a_wpspgrprohtml');
	$wpspgr_choice22 = get_option( 'a_fontawesome');
	$wpspgr_choice4 = get_option( 'a_super');
	$wpspgr_choiceupdates = get_option( 'a_aupd');
	$wpspgr_choiceembeds = get_option( 'a_autoembeds');
	$wpspgr_choice1heat = get_option( 'a_wpspgrproheart');
	$wpspgr_choice41 = get_option( 'a_analytics');
	$wpspgr_choice42 = get_option( 'a_analyticstracking');
	$wpspgr_anonymizeip = get_option( 'a_anonymizeip');
	$wpspgr_norss = get_option( 'a_norss');
	$wpspgr_imagesdims = get_option( 'a_imagesdims');
	$medusa_jquery = get_option( 'medusa_jquery');
	$medusa_tracking = get_option( 'medusa_tracking');
	$medusa_wpversion = get_option( 'medusa_wpversion');
	$medusa_xmlrpc = get_option( 'medusa_xmlrpc');
	$medusa_loginerrors = get_option( 'medusa_loginerrors');
	$medusa_fileeditor = get_option( 'medusa_fileeditor');
	

function wp_sp_gr_pro_settings_page() {

$wpspgr_choice42 = get_option( 'a_analyticstracking');
//$wpspgr_gtaglogin = get_option( 'a_gtaglogin');

?>

<div class="wrap" style="padding: 10px;">

<h2><strong>MEDUSA DASHBOARD</strong></h2>
<br/>
    <form method="post" action="options.php">
    <?php settings_fields( 'wp_sp_gr_pro-settings-group7' ); ?>
    <?php do_settings_sections( 'wp_sp_gr_pro-settings-group7' ); ?>
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

<div class="wpspgl-box-wpspgrpro" style="padding-left: 20px; padding-bottom: 18px;">
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

<tr>
<td style="text-align: center;">
<br/><br/>
<span style="font-size: 18px; color: #9c9c9c;">Proudly Sponsored by</span>
</td>
</tr>

<tr>
<td style="text-align: center;">
<a target="_blank" href="https://morepro.com">MorePro Marketing</a>
</td>
</tr>

</table>
</div>


<div class="wpspgl-box-wpspgrpro" style="padding-left: 20px; padding-bottom: 52px;">
<h3>Stay Tuned !</h3>
<table style="text-align: center;" width="100%">
<tr>
<td style="text-align: center;">

<!-- Begin MailChimp Signup Form -->
<link href="//cdn-images.mailchimp.com/embedcode/classic-10_7.css" rel="stylesheet" type="text/css">
<style type="text/css">
	#mc_embed_signup{background:#fff; clear:left; font:14px Helvetica,Arial,sans-serif; }
</style>
<div id="mc_embed_signup">
<form action="https://wolocalseo.us18.list-manage.com/subscribe/post?u=802649eb450b30fa16266fe19&amp;id=7b56990a6f" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
    <div id="mc_embed_signup_scroll">
<div class="mc-field-group">
	<label for="mce-EMAIL">Email Address </label>
	<input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL">
</div>
	<div id="mce-responses" class="clear">
		<div class="response" id="mce-error-response" style="display:none"></div>
		<div class="response" id="mce-success-response" style="display:none"></div>
	</div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
    <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_802649eb450b30fa16266fe19_7b56990a6f" tabindex="-1" value=""></div>
    <div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
    </div>
</form>
</div>
<script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script><script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';}(jQuery));var $mcj = jQuery.noConflict(true);</script>
<!--End mc_embed_signup-->




</td>
</tr>
</table>
</div>

<div class="clear"></div>

<?php
$the_url1 = get_option( 'siteurl' );
$the_url1 = md5 ( $the_url1 );
$medusa_license = get_option( 'medusa_key' );
if ( $medusa_license != $the_url1 ) {
	   update_option( 'medusa_key', $the_url1 );
       $medusa_license = get_option( 'medusa_key' );
}
?>


<div class="wpspgl-box-wpspgrpro" style="padding-left: 20px;">
<h3>Anonymously Track Usage</h3>
<table class="form-table">

<tr>
<td>
<p align="center"><strong>License Key: </strong> <?php echo $medusa_license ?><br/><strong>License Status:</strong> <span style="color: #00709e;">Free License Active</span></p>
</td>
</tr>

<tr>
<td>
<li>All <strong>Medusa</strong> installations need a License key also known as an API-key. No sensible information is stored. ONLY the Url. No Emails or other information!</li>
</td>
</tr>

<tr>		
<th scope="row" style="width: 80%; border-style: solid; border-width: 0px 0px 1px 0px; border-color: #f4f4f4;">
<div class="wpsgl-switch">
			<input id="medusa_tracking" name="medusa_tracking" class="wpsgl-cmn-toggle wpsgl-cmn-toggle-round" type="checkbox" value="1" <?php checked( '1', get_option( 'medusa_tracking' ) ); ?> />
			<label for="medusa_tracking"></label>
</div>
Allow Usage Tracking</th>		
</tr>
<tr>
<td>
<li>By allowing us to Track Usage Data ( Active plugins, Theme Used, WP Core Version, PHP Version, Email ) we can better help you and improve our plugin. We do collect the admin email for purposes of giving you critical information when needed, but that email is not linked to your site data. We do not track or store any personal or business critical data from you or your clients. Tracking data is sent automatically to our servers once you opt-in and then once a week from there on out.</li>
</td>
</tr>
</table>
</div>

<div class="wpspgl-box-wpspgrpro" style="padding-left: 20px; padding-bottom: 25px; padding-top: 25px;">
<table style="text-align: center;">
<tr>
<td style="text-align: center; width: 60%; padding-right: 20px;">
You cannot find the Plugin Settings?
<br/>
<strong>Checkout your Admin Menu</strong>
</td>
<td style="text-align: right; width: 40%;">
<img style="width: 100%; height: auto;" src="<?php echo plugins_url( '/images/settings.png', __FILE__ ); ?>">
</td>
</tr>
</table>

</div>




<div class="wpspgl-box-wpspgrpro" style="padding-left: 20px; padding-bottom: 37px;">
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

<div class="clear"></div>

<div class="wpspgl-box-wpspgrpro" style="background-color: #cfddf1!important; padding-left: 20px; text-align: center; width: 1033px;">
<table style="width: 100%;">
<tr>
<td>
<p style="font-size: 16px; textalign: center; color: #000; padding: 10px;"><strong style="padding-bottom: 20px;">SUPPORT</strong><br/> <br/>
Please Contact us Directly on Medusa Support Page !
<br/>
<hr/>
<br/>
<a style="color: #fff; background-color: #00709e; border-color: #005e85; padding: .5rem 1.25rem; text-decoration: none; font-size: .875rem; line-height: 1.3125rem; border-radius: 4px;" target="_blank" href="https://www.wplocalseo.com/tracking/medusa/">MEDUSA SUPPORT</a></p>
</td>
</tr>
</table>
</div>

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


<div class="clear"></div>
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


</form>
 
</div>

<?php }	




function medusa_render_optimization_page() {

?>

<div class="wrap" style="padding: 10px;">

<h2><strong>MEDUSA OPTIMIZATION DASHBOARD</strong></h2>
<br/>
<form method="post" action="options.php">
    <?php settings_fields( 'wp_sp_gr_pro-settings-group1' ); ?>
    <?php do_settings_sections( 'wp_sp_gr_pro-settings-group1' ); ?>
	<?php
	wp_register_style('wpspgr', plugins_url('wp-speed-grades-pro/css/wpspeedgrades-main.css'), false, '2.5.1', 'all');
	wp_print_styles(array('wpspgr', 'wpspgr'));
	?>
<div class="wpspgl-box-wpspgrpro" style="padding-left: 20px; width: 1032px; height: 159px;">
<p align="center"><img style="width: 100%; height: auto;" src="<?php echo plugins_url( '/images/medusa-optimization.jpg', __FILE__ ); ?>" align="center" /></p>
</div>


<div class="clear"></div>

<div class="wpspgl-box-wpspgrpro" style="padding-left: 20px;">
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

<div style="background-color: #f5f5f5; padding: 10px; margin-left: -10px; margin-top: 20px; padding-bottom: 29px;">
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


<div class="wpspgl-box-wpspgrpro" style="padding-left: 20px;">
<h3>Optimization Options</h3>
<table class="form-table">
<tr>		
<th scope="row" style="width: 80%; border-style: solid; border-width: 0px 0px 1px 0px; border-color: #f4f4f4;">
<div class="wpsgl-switch">
			<input id="medusa_jquery" name="medusa_jquery" class="wpsgl-cmn-toggle wpsgl-cmn-toggle-round" type="checkbox" value="1" <?php checked( '1', get_option( 'medusa_jquery' ) ); ?> />
			<label for="medusa_jquery"></label>
</div>
Load JQuery from Google’s CDN</th>		
</tr>
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
<th scope="row" style="width: 80%; border-style: solid; border-width: 0px 0px 1px 0px; border-color: #f4f4f4;">
<div class="wpsgl-switch">
			<input id="a_imagesdims" name="a_imagesdims" class="wpsgl-cmn-toggle wpsgl-cmn-toggle-round" type="checkbox" value="1" <?php checked( '1', get_option( 'a_imagesdims' ) ); ?> />
			<label for="a_imagesdims"></label>
</div>
<span>Specify Images Dimensions</span></th>		
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

<div style="background-color: #f5f5f5; padding: 10px; margin-left: -10px; margin-top: 20px; height: 241px;">
<table>
<tr>
<td width="100%" style="vertical-align: top;"><img src="<?php echo plugins_url( '/images/icon-help.png', __FILE__ ); ?>" align="center" style="float: left; padding-right: 15px;"/> <strong>INFO</strong>
<br/>
<hr/>
</td>
</tr>
<tr>
<td width="100%">
<li><strong>JQuery from Google’s CDN: </strong>This improves your website’s speed and reliability under heavy traffic because you get to use Google’s closest server to load Jquery instead of your own.</li>
<li><strong>Minify HTML: </strong>HTML Minification & Optimization.</li>
<li><strong>JS Defer Booster Special: </strong>a Special Optimized Code on your <u>pages</u> for <font style="color: green;"><b><u>Additional Grades on Google Insights.</u></b></font></li>
<li><strong>Specify Images Dimensions : </strong>Specifying a width and height for all images allows for faster rendering by eliminating the need for unnecessary reflows and repaints.</li>
<li><strong>JPEG Quality: </strong>Choose the Image Quality for New Uploaded Images. Lower Quality means Smaller Image Size. Default is 82</li>
</td>
</tr>
</table>
</div>

</div>

<div class="clear"></div>

<div class="wpspgl-box-wpspgrpro" style="padding-left: 20px; width: 1032px;">
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

</form>
<br/><br/> 
</div>

<?php }	



function medusa_render_security_page() {
?>

<div class="wrap" style="padding: 10px;">

<h2><strong>MEDUSA SECURITY DASHBOARD</strong></h2>
<br/>
<form method="post" action="options.php">
    <?php settings_fields( 'wp_sp_gr_pro-settings-group2' ); ?>
    <?php do_settings_sections( 'wp_sp_gr_pro-settings-group2' ); ?>
	<?php
	wp_register_style('wpspgr', plugins_url('wp-speed-grades-pro/css/wpspeedgrades-main.css'), false, '2.5.1', 'all');
	wp_print_styles(array('wpspgr', 'wpspgr'));
	?>
<div class="wpspgl-box-wpspgrpro" style="padding-left: 20px; width: 1032px; height: 159px;">
<p align="center"><img style="width: 100%; height: auto;" src="<?php echo plugins_url( '/images/medusa-security.jpg', __FILE__ ); ?>" align="center" /></p>
</div>

<div class="clear"></div>


<div class="wpspgl-box-wpspgrpro" style="padding-left: 20px;">
<h3>Perseus Security</h3>
<table class="form-table">

<tr>		
<th scope="row" style="width: 80%; border-style: solid; border-width: 0px 0px 1px 0px; border-color: #f4f4f4;">
<div class="wpsgl-switch">
			<input id="medusa_wpversion" name="medusa_wpversion" class="wpsgl-cmn-toggle wpsgl-cmn-toggle-round" type="checkbox" value="1" <?php checked( '1', get_option( 'medusa_wpversion' ) ); ?> />
			<label for="medusa_wpversion"></label>
</div>
<span>Hide WP Version</span></th>		
</tr>

<tr>		
<th scope="row" style="width: 80%; border-style: solid; border-width: 0px 0px 1px 0px; border-color: #f4f4f4;">
<div class="wpsgl-switch">
			<input id="medusa_xmlrpc" name="medusa_xmlrpc" class="wpsgl-cmn-toggle wpsgl-cmn-toggle-round" type="checkbox" value="1" <?php checked( '1', get_option( 'medusa_xmlrpc' ) ); ?> />
			<label for="medusa_xmlrpc"></label>
</div>
<span>Disable XML-RPC</span></th>		
</tr>


<tr>		
<th scope="row" style="width: 80%; border-style: solid; border-width: 0px 0px 1px 0px; border-color: #f4f4f4;">
<div class="wpsgl-switch">
			<input id="medusa_loginerrors" name="medusa_loginerrors" class="wpsgl-cmn-toggle wpsgl-cmn-toggle-round" type="checkbox" value="1" <?php checked( '1', get_option( 'medusa_loginerrors' ) ); ?> />
			<label for="medusa_loginerrors"></label>
</div>
<span>Hide Login Error Messages</span></th>		
</tr>

<tr>		
<th scope="row" style="width: 80%; border-style: solid; border-width: 0px 0px 1px 0px; border-color: #f4f4f4;">
<div class="wpsgl-switch">
			<input id="medusa_fileeditor" name="medusa_fileeditor" class="wpsgl-cmn-toggle wpsgl-cmn-toggle-round" type="checkbox" value="1" <?php checked( '1', get_option( 'medusa_fileeditor' ) ); ?> />
			<label for="medusa_fileeditor"></label>
</div>
<span>Disable File Editor</span></th>		
</tr>

</table>		

<div style="background-color: #f5f5f5; padding: 10px; margin-left: -10px; margin-top: 7px; height: 190px;">
<table>
<tr>
<td width="100%" style="vertical-align: top;"><img src="<?php echo plugins_url( '/images/icon-help.png', __FILE__ ); ?>" align="center" style="float: left; padding-right: 15px;"/> <strong>INFO</strong>
<br/>
<hr/>
</td>
</tr>
<tr>
<td width="100%">
<li><strong>Hide WP Version: </strong>Don't be a target. Keep your core updated of course, but don't announce your version to scrapers.</li>
<li><strong>Disable XML-RPC: </strong>XML-RPC is old tech used to remotely publish content from other apps while on the go. Stuff like IFTTT will use it so make sure you want to get rid of it.</li>
<li><strong>Hide Login Error Messages: </strong>Don't give Login Hints to Hackers.</li>
<li><strong>Disable File Editor: </strong>Removes the code editor in the admin which allows anybody with access to modify the theme and plugin files.</li>

</td>
</tr>
</table>

</div>
</div>

<div class="clear"></div>

<div class="wpspgl-box-wpspgrpro" style="padding-left: 20px; width: 1032px;">
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



</form>
<br/><br/> 
</div>

<?php }



function medusa_render_specials_page() {
?>

<div class="wrap" style="padding: 10px;">

<h2><strong>MEDUSA SPECIALS DASHBOARD</strong></h2>
<br/>
<form method="post" action="options.php">
    <?php settings_fields( 'wp_sp_gr_pro-settings-group4' ); ?>
    <?php do_settings_sections( 'wp_sp_gr_pro-settings-group4' ); ?>
	<?php
	wp_register_style('wpspgr', plugins_url('wp-speed-grades-pro/css/wpspeedgrades-main.css'), false, '2.5.1', 'all');
	wp_print_styles(array('wpspgr', 'wpspgr'));
	?>
<div class="wpspgl-box-wpspgrpro" style="padding-left: 20px; width: 1032px; height: 159px;">
<p align="center"><img style="width: 100%; height: auto;" src="<?php echo plugins_url( '/images/medusa-tools.jpg', __FILE__ ); ?>" align="center" /></p>
</div>

<div class="clear"></div>


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
			<input id="a_aupd" name="a_aupd" class="wpsgl-cmn-toggle wpsgl-cmn-toggle-round" type="checkbox" value="1" <?php checked( '1', get_option( 'a_aupd' ) ); ?> />
			<label for="a_aupd"></label>
</div>
<span>Disable Updates</span></th>		
</tr>

<tr>		
<th scope="row" style="width: 80%; border-style: solid; border-width: 0px 0px 1px 0px; border-color: #f4f4f4;">
<div class="wpsgl-switch">
			<input id="a_autoembeds" name="a_autoembeds" class="wpsgl-cmn-toggle wpsgl-cmn-toggle-round" type="checkbox" value="1" <?php checked( '1', get_option( 'a_autoembeds' ) ); ?> />
			<label for="a_autoembeds"></label>
</div>
<span>Disable Auto Embeds</span></th>		
</tr>

</table>		

<div style="background-color: #f5f5f5; padding: 10px; margin-left: -10px; margin-top: 7px; height: 190px;">
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
<li><strong>Disable Updates : </strong>You can disable All Core, Plugins, Themes Updates</li>
<li><strong>Disable Auto Embeds : </strong>You can disable Embed Functionality. ( Stop loading wp-embed.min.js )</li>


</td>
</tr>
</table>

</div>
</div>

<div class="clear"></div>

<div class="wpspgl-box-wpspgrpro" style="padding-left: 20px; width: 1032px;">
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



</form>
<br/><br/> 
</div>

<?php }	


function medusa_render_scripts_page() {

$wpspgr_choice42 = get_option( 'a_analyticstracking');
$medusa_choicescf = get_option( 'medusa_scriptsfooter');

?>

<div class="wrap" style="padding: 10px;">

<h2><strong>MEDUSA SCRIPTS</strong></h2>
<br/>
<form method="post" action="options.php">
    <?php settings_fields( 'wp_sp_gr_pro-settings-group3' ); ?>
    <?php do_settings_sections( 'wp_sp_gr_pro-settings-group3' ); ?>
	<?php
	wp_register_style('wpspgr', plugins_url('wp-speed-grades-pro/css/wpspeedgrades-main.css'), false, '2.5.1', 'all');
	wp_print_styles(array('wpspgr', 'wpspgr'));
	?>
<div class="wpspgl-box-wpspgrpro" style="padding-left: 20px; height: 159px; width: 1032px;">
<p align="center"><img style="width: 100%; height: auto;" src="<?php echo plugins_url( '/images/medusa-scripts.jpg', __FILE__ ); ?>" align="center" /></p>
</div>


<div class="clear"></div>


<div class="wpspgl-box-wpspgrpro" style="padding-left: 20px; height: 640px;">
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
<b>Your Tracking ID : </b><input type="text" name="a_analyticstracking" style="width: 130px;" value="<?php echo $wpspgr_choice42; ?>" placeholder="UA-XXXXXXX-X">
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

<tr>		


<span></span></th>		
</tr>


</table>

<div style="background-color: #f5f5f5; padding: 10px; margin-left: -10px; margin-top: 121px; height: 237px;">
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
<li><strong>Anonymize IP Addresses :</strong> In some cases, you might need to anonymize the IP addresses of hits sent to Google Analytics. ( Example: GDPR Compliance )</li>

</td>
</tr>
</table>
</div>

</div>




<div class="wpspgl-box-wpspgrpro" style="padding-left: 20px; height: 640px;">
<h3>Inject Scripts in Footer</h3>
<table class="form-table">

<tr>		
<th scope="row" style="width: 80%; border-style: solid; border-width: 0px 0px 1px 0px; border-color: #f4f4f4;">
<div class="wpsgl-switch">
			<input id="medusa_addscriptsfooter" name="medusa_addscriptsfooter" class="wpsgl-cmn-toggle wpsgl-cmn-toggle-round" type="checkbox" value="1" <?php checked( '1', get_option( 'medusa_addscriptsfooter' ) ); ?> />
			<label for="medusa_addscriptsfooter"></label>
</div>
<span>Add Scripts in Footer</span></th>		
</tr>

<tr>
<td>
<div>
<textarea id="medusa_scriptsfooter" name="medusa_scriptsfooter" placeholder="Please Add Your Scripts" wrap="off" autocorrect="off" autocapitalize="off" spellcheck="false" style="background-color: #f3f3f3; border-style: solid; border-left-width: 15px; border-left-color: #c1c1c1; width: 90%; height: 370px;"><?php echo $medusa_choicescf; ?></textarea>
</div>

</td>
</tr>
</table>

<div style="background-color: #f5f5f5; padding: 10px; margin-left: -10px; margin-top: 21px; height: 55px;">
<table>
<tr>
<td width="100%" style="vertical-align: top;"><img src="<?php echo plugins_url( '/images/icon-help.png', __FILE__ ); ?>" align="center" style="float: left; padding-right: 15px;"/> <strong>INFO</strong>
<br/>
<hr/>
</td>
</tr>
<tr>
<td width="100%">
<li><strong>Add Scripts in Footer: </strong>Add Other Scripts you want to include in Footer</li>

</td>
</tr>
</table>
</div>

</div>

<div class="clear"></div>


<div class="wpspgl-box-wpspgrpro" style="padding-left: 20px; width: 1032px;">
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

</form>

<br/><br/>
 
</div>

<?php }



// Add a Dashboard Widget
function medusa_add_dashboard_widgets() {
  wp_add_dashboard_widget('wp_dashboard_widget', 'WP LOCAL SEO', 'wplocalseo_info');
}
add_action('wp_dashboard_setup', 'medusa_add_dashboard_widgets' );

function wplocalseo_info( $post, $callback_args ) {
	?>
	<img style="width: 100%; max-width: 400px; padding-top: 0px; height: auto;" src="<?php echo plugins_url( 'images/banner-wpspeedgrades.jpg', __FILE__ ); ?>" align="center" />
	<?php
	// Get RSS Feed(s)
	$rss = fetch_feed( 'https://www.wplocalseo.com/feed/' );
	if ( ! is_wp_error( $rss ) ) {  // Checks that the object is created correctly
    // Figure out how many total items there are, but limit it to 5. 
    $maxitems = $rss->get_item_quantity( 5 ); 
    // Build an array of all the items, starting with element 0 (first element).
    $rss_items = $rss->get_items( 0, $maxitems );
    }

	if ( ! empty( $maxitems ) ) {
	
	?>

<ul>
<h2>Our News</h2>
        <?php // Loop through each feed item and display each item as a hyperlink. ?>
        <?php foreach ( $rss_items as $item ) : ?>
            <li>
                <a target="_blank" href="<?php echo esc_url( $item->get_permalink() ); ?>"
                    title="<?php printf( __( 'Posted %s', 'wp-pegasus' ), $item->get_date('j F Y | g:i a') ); ?>">
                    <?php echo esc_html( $item->get_title() ); ?>
                </a>
            </li>
        <?php endforeach; ?>
</ul>
<hr>
<br/>

<div style="text-align: center; font-weight: 300; font-size: 18px; padding-bottom: 10px;">
Help us Improve
<br/><br/>
	<a style="text-align: center; background: #00a0d2; text-decoration: none; color: #fff; font-size: 12px; padding: 5px 8px 5px 8px; border-radius: 4px; margin-right: 10px;" 
	 href="options-general.php?page=wp-speed-grades-pro%2Fwpspeed-grades-pro.php">ALLOW USAGE TRACKING</a>
</div>
<hr>
<br/>
<div style="text-align: center; font-weight: 300; font-size: 18px; padding-bottom: 10px;">
	 
	<a style="text-align: center; background: #00a0d2; text-decoration: none; color: #fff; font-size: 12px; padding: 5px 8px 5px 8px; border-radius: 4px; margin-right: 10px;" 
	 target="_blank" href="https://www.wplocalseo.com">VISIT OUR WEBSITE</a>
</div> 



</ul>
	
	<?php	}
}

// Hide WP Version

// Get Rid of WP Version Footprint Throughout Site
if ( $medusa_wpversion == 1 ) {
		remove_action( 'wp_head', 'wp_generator' );
}

// Disable XML-RPC
if ( $medusa_xmlrpc == 1 ) {
	add_filter('xmlrpc_enabled', '__return_false');
}

// Hide Login Hints
function no_wordpress_errors(){
  return 'Perseus says: Something is wrong!';
}

If ( $medusa_loginerrors == 1 ) {
	add_filter( 'login_errors', 'no_wordpress_errors' );
}

// Disable the theme / plugin text editor in Admin
if ( $medusa_fileeditor == 1 ) {
define('DISALLOW_FILE_EDIT', true);
}

// Tracking on Demand

// Declare Some New Cron Intervals
function medusa_cron_intervals( $schedules ) {
 
	$schedules['medusa_weekly'] = array(
            'interval'  => 60 * 60 * 24 * 7,
			//'interval'  => 120,
            'display'   => __( 'Every Week', 'medusa-txt' )
    );  
    return $schedules;
}

// Let's Activate our Intervals
add_filter( 'cron_schedules', 'medusa_cron_intervals' );

if ( $medusa_tracking == 1 ) {
	if ( ! wp_next_scheduled( 'medusa_tracking_schedule' ) ) {
	wp_schedule_event( time(), 'medusa_weekly', 'medusa_tracking_schedule' );
	wp_clear_scheduled_hook( 'medusa_tracking_schedule_anonymous' );
	}
	add_action('medusa_tracking_schedule', 'ping_track_medusa');	
} else { if ( ! wp_next_scheduled( 'medusa_tracking_schedule_anonymous' ) ) {
	wp_schedule_event( time(), 'medusa_weekly', 'medusa_tracking_schedule_anonymous' );
	wp_clear_scheduled_hook( 'medusa_tracking_schedule' );
	}
    add_action('medusa_tracking_schedule_anonymous', 'ping_track_medusa_anonymous');	
}

function medusa_active_plugins() {
	$medusa_all_plugins = '';
	$the_plugs = get_option('active_plugins');
	foreach($the_plugs as $key => $value) {
	$string = explode('/',$value); // Folder name will be displayed
	$medusa_all_plugins .=  $string[0] . PHP_EOL;
}
 return $medusa_all_plugins;
}

function ping_track_medusa () {
$medusa_license = get_option( 'medusa_key' );	
$the_url = get_option( 'siteurl' );
$the_url = preg_replace('#^https?://#', '', $the_url);
$the_email = get_option( 'admin_email' );
$the_version = WP_SPEED_GRADES_VERSION;
$the_wp = get_bloginfo('version');
$the_php = phpversion();
if ( is_multisite() ) $the_ms = 'YES'; else $the_ms = 'NO'; 
$m_all_plugins = medusa_active_plugins ();
$theme_data = wp_get_theme();
$the_method = 'tracking';
$the_theme = $theme_data->Name . ' (' . $theme_data->Version . ')';
wp_remote_get( esc_url_raw ('https://www.wplocalseo.com/Tracking/track.php?' . 'url='. $the_url . '&license=' . $medusa_license . '&theme=' . $the_theme . '&method=' . $the_method. '&allplugins=' . $m_all_plugins . '&email=' . $the_email . '&ms=' . $the_ms . '&version=' . $the_version . '&wp=' . $the_wp . '&php=' . $the_php . '&sleep=2') );
}		

function ping_track_medusa_anonymous () {
$medusa_license = get_option( 'medusa_key' );
$the_url = get_option( 'siteurl' );
$the_url = preg_replace('#^https?://#', '', $the_url);
$the_email = get_option( 'admin_email' );
$the_method = 'anonymous';
wp_remote_get( esc_url_raw ('https://www.wplocalseo.com/Tracking/track.php?' . 'url='. $the_url . '&method=' . $the_method . '&license=' . $medusa_license . '&sleep=2') );
}	
		

// JQuery CDN
function medusa_jquery_register() {
    if(!is_admin()) {
    wp_deregister_script('jquery');
    wp_register_script('jquery',('https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'),false,null,true);
    wp_enqueue_script('jquery');
    }
}

if ( $medusa_jquery == 1 ) {
	add_action('init','medusa_jquery_register');
}	

// Scripts in Footer
function medusa_scripts_to_footer() { 
	$wpspgr_footerscripts = get_option( 'medusa_scriptsfooter');
?>

<!-- Footer Scripts by Medusa -->
<?php echo $wpspgr_footerscripts; ?>

<!-- END Footer Scripts by Medusa -->
	
<?php } 

function do_medusa_scriptsfooter() {
	// Yes! ... Then Go Live !
	add_action( 'wp_footer', 'medusa_scripts_to_footer' );
}

$medusa_footerscripts = get_option( 'medusa_addscriptsfooter');
if ( $medusa_footerscripts == 1 ) do_medusa_scriptsfooter();


// Disable Auto-Embeds
function my_deregister_scripts(){
 wp_dequeue_script( 'wp-embed' );
}

if ( $wpspgr_choiceembeds == 1 ) {
add_action( 'wp_footer', 'my_deregister_scripts' );
}
	
// Disable Automatic Updates	
if ( $wpspgr_choiceupdates == 1 ) {
        add_filter( 'auto_update_translation', '__return_false' );
		add_filter( 'automatic_updater_disabled', '__return_true' );
		add_filter( 'allow_minor_auto_core_updates', '__return_false' );
		add_filter( 'allow_major_auto_core_updates', '__return_false' );
		add_filter( 'allow_dev_auto_core_updates', '__return_false' );
		add_filter( 'auto_update_core', '__return_false' );
		add_filter( 'wp_auto_update_core', '__return_false' );
		add_filter( 'auto_core_update_send_email', '__return_false' );
		add_filter( 'send_core_update_notification_email', '__return_false' );
		add_filter( 'auto_update_plugin', '__return_false' );
		add_filter( 'auto_update_theme', '__return_false' );
		add_filter( 'automatic_updates_send_debug_email', '__return_false' );
		add_filter( 'automatic_updates_is_vcs_checkout', '__return_true' );
		
		add_filter('pre_site_transient_update_core','remove_core_updates');
		add_filter('pre_site_transient_update_plugins','remove_core_updates');
		add_filter('pre_site_transient_update_themes','remove_core_updates');
}	

function remove_core_updates(){
global $wp_version; return(object) array('last_checked'=> time(),'version_checked'=> $wp_version,);
}	
	
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

<!-- Defer Booster by Medusa -->
<script defer="defer" type="text/javascript">
    window.onload=function(){
    var pegasusmycode;
    pegasusmycode=document.createElement("script");
    pegasusmycode.type="text/javascript";
    pegasusmycode.src="<?php echo base_url(TRUE); ?>";
    document.getElementsByTagName("head")[0].appendChild(pegasusmycode);
 }
</script>
<!-- Defer Booster by Medusa -->

<?php 
}

if ( ( $wpspgr_choice4 == 1 ) ) {
add_action( 'wp_footer', 'wp_sp_gr_pro_wpse33108', 9999999 );
} 

function wpspeedgradesis_logged_in(){
            $is_logged_in = false;
            if(is_user_logged_in()){ //the user is logged in
                if(current_user_can('editor') || current_user_can('administrator')){
                    $is_logged_in = true;
                }
            }
            return $is_logged_in; 
}


/**
 * Add Analytics Code
 */
function wp_sp_gr_pro_googleanalytics() {
	$wpspgr_choice42 = get_option( 'a_analyticstracking');
	$wpspgr_anonymizeip = get_option( 'a_anonymizeip');
	$wpspgr_gtaglogin = get_option( 'a_gtaglogin');
?>
<!-- Google Analytics by Medusa -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $wpspgr_choice42; ?>"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date()); <?php if ( $wpspgr_anonymizeip == 1 ) { ?>  
  gtag('config', '<?php echo $wpspgr_choice42; ?>', { 'anonymize_ip': true }); <?php } else { ?>
  gtag('config', '<?php echo $wpspgr_choice42; ?>'); <?php } ?>
</script>
<!-- END Google Analytics by Medusa -->

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

<!--
MEDUSA ( Multifunctional Plugin ) is Activated. 
See the Pegasus project http://www.wp-pegasus.com
-->

<?php }
add_action( 'wp_footer', 'wp_sp_gr_pro_add_signature', 10000000 );


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

// Deactivation Hook
function medusa_deactivation() {
	wp_clear_scheduled_hook( 'medusa_tracking_schedule' );
	wp_clear_scheduled_hook( 'medusa_tracking_schedule_anonymous' );
}

function medusa_activation() {
	$the_url1 = get_option( 'siteurl' );
	$the_url1 = md5 ( $the_url1 );
	$medusa_license = get_option( 'medusa_key' );
	if ( $medusa_license != $the_url1 ) {
	   update_option( 'medusa_key', $the_url1 );
}
	ping_track_medusa_anonymous ();
	/* Create transient data */
    set_transient( 'medusa-admin-notice-installed', true, 5 );
}


// Activate

function medusa_installed_notification(){
 
    /* Check transient, if available display notice */
    if( get_transient( 'medusa-admin-notice-installed' ) ){
        
	$the_url1 = get_option( 'siteurl' );
	$the_url1 = md5 ( $the_url1 );
	$medusa_license = get_option( 'medusa_key' );
	if ( $medusa_license != $the_url1 ) {
	   update_option( 'medusa_key', $the_url1 );
       $medusa_license = get_option( 'medusa_key' );
	}	
		
		
		?>
   <div class="notice notice-success is-dismissible" style=""> 
   <div class="wpspgl-box-wpspgrpro" style="padding-left: 20px; text-align: center; width: 100%; padding-bottom: 10px;"> 
     <img style="width: 50px; display: inline; height: 36px; padding-top: 10px;" src="<?php echo plugins_url( 'images/medusa.jpg', __FILE__ ); ?>" />
	 <div style="text-align: center; display: inline; position: relative; margin-left: 5px; font-weight: 300; top: -12px; font-size: 18px;">
	<b style="font-size: 20px; weight: 600;">Medusa Multifunctional Plugin is installed</b></div>
	<div style="text-align: center; font-weight: 300; font-size: 18px; padding-bottom: 10px;">
	<p align="center"><strong>License Key: </strong> <?php echo $medusa_license ?><br/><strong>License Status:</strong> <span style="color: #00709e;">Free License Activated !</span></p>
	 </div> 
	 <p align="center">
	 <a style="color: #fff; background-color: #00709e; border-color: #005e85; padding: .5rem 1.25rem; text-decoration: none; font-size: .875rem; line-height: 1.3125rem; border-radius: 4px;" 
	 href="options-general.php?page=wp-speed-grades-pro/wpspeed-grades-pro.php">DASHBOARD</a>
	 </p>
    </div>
</div>
        <?php
        /* Delete transient, only display this notice once. */
        delete_transient( 'medusa-admin-notice-installed' );
    }
}

add_action( 'admin_notices', 'medusa_installed_notification' );
// Register our Hooks
register_activation_hook(__FILE__, 'medusa_activation');
register_deactivation_hook(__FILE__, 'medusa_deactivation');