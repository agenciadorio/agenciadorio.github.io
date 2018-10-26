<?php
/*
Plugin Name: WP Speed Grades Lite
Plugin URI: https://www.wp-pegasus.com/
Description: Improves your Page Speed Grades for Google Insights, GT Metrix and Pingdom Tools. Reduces Loading Time. Easily add Google Analytics Tracking Code.
Version: 2.2
Author: Bestseogr
Author URI: http://www.wp-speed.com/
License: GPL2
*/

// DO NOT ALLOW DIRECT ACCESS
if ( !defined( 'ABSPATH' ) ) exit;


define( 'WP_SPEED_GRADES_PATH', plugin_dir_path( __FILE__ ) );					// Defining plugin dir path
define( 'WP_SPEED_GRADES_VERSION', 'v2.2');										// Defining plugin version
define( 'WP_SPEED_GRADES_NAME', 'WP Speed Grades Lite Plugin');					// Defining plugin name
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
	
	if( $amp_detected == 0 ) {
	
	$buffer = str_replace(array (chr(13) . chr(10), chr(9)), array (chr(10), ''), $buffer);
	
	
	$buffer = str_ireplace(array ('<!--[if', 'endif]-->','<!-- ngg_resource_', 'manager_marker -->', '<script', '/script>', '<pre', '/pre>', '<textarea', '/textarea>', '<style', '/style>'), array ('WPSPGRPROSTA<!--[if', 'endif]-->WPSPGRPROEND','WPSPGRPROSTA<!-- ngg_resource_', 'manager_marker -->WPSPGRPROEND', 'WPSPGRPROSTA<script', '/script>WPSPGRPROEND', 'WPSPGRPROSTA<pre', '/pre>WPSPGRPROEND', 'WPSPGRPROSTA<textarea', '/textarea>WPSPGRPROEND', 'WPSPGRPROSTA<style', '/style>WPSPGRPROEND'), $buffer);
	$wpspgrpro_split = explode('WPSPGRPROEND', $buffer);
	$buffer = '';
	for ($i=0; $i<count($wpspgrpro_split); $i++) {
		$ii = strpos($wpspgrpro_split[$i], 'WPSPGRPROSTA');
		if ($ii !== false) {
			$process = substr($wpspgrpro_split[$i], 0, $ii);
			$wpspgrpro_asis = substr($wpspgrpro_split[$i], $ii + 12);
			if (substr($wpspgrpro_asis, 0, 7) == '<script') {
				$wpspgrpro_split2 = explode(chr(10), $wpspgrpro_asis);
				$wpspgrpro_asis = '';
				for ($iii = 0; $iii < count($wpspgrpro_split2); $iii ++) {
					if ($wpspgrpro_split2[$iii]) $wpspgrpro_asis .= trim($wpspgrpro_split2[$iii]) . chr(10);
				}
				if ($wpspgrpro_asis) $wpspgrpro_asis = substr($wpspgrpro_asis, 0, -1);
				$wpspgrpro_asis = str_replace(array (';' . chr(10), '>' . chr(10), '{' . chr(10), '}' . chr(10), ',' . chr(10)), array(';', '>', '{', '}', ','), $wpspgrpro_asis);
			}
			if (substr($wpspgrpro_asis, 0, 6) == '<style') {
				$wpspgrpro_asis = preg_replace(array ('/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s'), array('>', '<', '\\1'), $wpspgrpro_asis);
				$wpspgrpro_asis = str_replace(array (chr(10), ' {', '{ ', ' }', '} ', ' (', '( ', ' )', ') ', ' :', ': ', ' ;', '; ', ' ,', ', ', ';}'), array('', '{', '{', '}', '}', '(', '(', ')', ')', ':', ':', ';', ';', ',', ',', '}'), $wpspgrpro_asis);
			}
		} else {
			$process = $wpspgrpro_split[$i];
			$wpspgrpro_asis = '';
		}
		$process = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $process);
		$process = preg_replace(array ('/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s'), array('>', '<', '\\1'), $process);
		$buffer .= $process.$wpspgrpro_asis;
	}
	$buffer = str_replace(array (chr(10) . '<script', chr(10) . '<style', '*/' . chr(10), 'WPSPGRPROSTA'), array('<script', '<style', '*/', ''), $buffer);
	
         
		$buffer .= "\xA" . '<!-- WP Speed Grades is Activated. Compression Active | See the Pegasus project http://www.wp-speed.com -->';  
	}
    
    else $buffer .= "\xA" . '<!-- WP Speed Grades is Activated. AMP Page Detected | See the Pegasus project http://www.wp-speed.com -->';
	
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
			wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css' );
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
	//add_menu_page('WP Speed Grades', 'WP Speed Grades', 'administrator', 'wp-speed-grades-pro', 'wp_sp_gr_pro_settings_page' , '' , plugins_url('images/booster.png', __FILE__) );
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

function wp_sp_gr_pro_settings_page() {

$wpspgr_choice42 = get_option( 'a_analyticstracking');
?>

<div class="wrap" style="padding: 10px;">

<h2>WP SPEED GRADES LITE SETTINGS:</h2>
<br/>

<div class="box-region-middle">

<div class="box-wpspgrpro">
<p align="center"><img src="<?php echo plugins_url( '/images/banner-wpspeedgrades.jpg', __FILE__ ); ?>" align="center" /></p>
</div>
	<?php
	wp_register_style('wpspgr', plugins_url('wp-speed-grades-pro/css/wpspeedgrades-main.css'), false, '1.2', 'all');
	wp_print_styles(array('wpspgr', 'wpspgr'));
	?>
<hr/>


<form method="post" action="options.php">
    <?php settings_fields( 'wp_sp_gr_pro-settings-group' ); ?>
    <?php do_settings_sections( 'wp_sp_gr_pro-settings-group' ); ?>
<p align="right"><?php submit_button(); ?></p>	
<div class="box-wpspgrpro">
<h3>Lite Version Options</h3>
<table class="form-table">
<tr>		
<th scope="row" style="width: 60%; border-style: solid; border-width: 0px 0px 1px 0px; border-color: #f4f4f4;">Remove Emojis</th><td><input name="a_emojis" type="checkbox" value="1" <?php checked( '1', get_option( 'a_emojis' ) ); ?> /></td>		
</tr>
<tr>
<th scope="row" style="width: 60%; border-style: solid; border-width: 0px 0px 1px 0px; border-color: #f4f4f4;">Remove Query Strings</th><td><input name="a_queries" type="checkbox" value="1" <?php checked( '1', get_option( 'a_queries' ) ); ?> /></td>
</tr>
<tr>
<th scope="row" style="width: 60%; border-style: solid; border-width: 0px 0px 1px 0px; border-color: #f4f4f4;">Remove Query Strings (RevSlider)</th>
<td>
<input name="a_queriesrev" type="checkbox" value="1" <?php checked( '1', get_option( 'a_queriesrev' ) ); ?> /> 
</td>
</tr>
<tr>
<th scope="row" style="width: 60%; border-style: solid; border-width: 0px 0px 1px 0px; border-color: #f4f4f4;">Header Cleanup</th>
<td>
<input name="a_headclean" type="checkbox" value="1" <?php checked( '1', get_option( 'a_headclean' ) ); ?> />
</td>
</tr>
<th scope="row" style="width: 60%; border-style: solid; border-width: 0px 0px 1px 0px; border-color: #f4f4f4;">Remove Extra Font Awesome</th>
<td>
<input name="a_fontawesome" type="checkbox" value="1" <?php checked( '1', get_option( 'a_fontawesome' ) ); ?> /> 
</td>
</tr>
<tr>
<th scope="row" style="width: 60%; border-style: solid; border-width: 0px 0px 1px 0px; border-color: #f4f4f4;"><b>Minify HTML</b></th>
<td>
<input name="a_wpspgrprohtml" type="checkbox" value="1" <?php checked( '1', get_option( 'a_wpspgrprohtml' ) ); ?> />
</td>
</tr>
<tr style="background-color: #fff5e6;">
<th scope="row" style="width: 60%; border-style: solid; border-width: 0px 0px 1px 0px; border-color: #f4f4f4; color: red;"><b>JS Defer Booster Special</b></th>
<td>
<input name="a_super" type="checkbox" value="1" <?php checked( '1', get_option( 'a_super' ) ); ?> />
</td>
</tr>
<tr>
<th scope="row" style="width: 60%; border-style: solid; border-width: 0px 0px 1px 0px; border-color: #f4f4f4;"><b>Change Heartbeat Interval</b></th>
<td>
<input name="a_wpspgrproheart" type="checkbox" value="1" <?php checked( '1', get_option( 'a_wpspgrproheart' ) ); ?> />
</td>
</tr>
<tr style="background-color: #faffd7;">
<th scope="row" style="width: 60%; border-style: solid; border-width: 0px 0px 1px 0px; border-color: #f4f4f4;"><b>Activate Google Analytics Tracking Code</b></th>
<td>
<input name="a_analytics" type="checkbox" value="1" <?php checked( '1', get_option( 'a_analytics' ) ); ?> />
</td>
</tr>
<tr style="background-color: #faffd7;">		
<th scope="row" style="width: 60%; border-style: solid; border-width: 0px 0px 1px 0px; border-color: #f4f4f4;"><b>Your Tracking ID</b></th><td><input type="text" name="a_analyticstracking" style="width:100%;" value="<?php echo $wpspgr_choice42; ?>" placeholder="ex. UA-XXXXXXX-X"></td>		
</tr>
</table>		
</div>



<div class="box-wpspgrpro">
<h3>Info for Lite version</h3>
<table class="form-table">


<li><strong>Remove Emojis: </strong>This option disables the Emoji functionality.</li>
<li><strong>Remove Query Strings: </strong>This option will remove query strings from static resources like CSS & JS files, and will improve your speed scores in services like PageSpeed, YSlow, Pingdom and GTmetrix.</li>
<li><strong>Remove Query Strings (RevSlider): </strong>This option will additionaly remove query strings from RevSlider.</li>
<li><strong>Header Cleanup: </strong>Removes Feed Links, WlWmanifest links, Shortlinks, WP Generator, RSD Links etc.</li>
<li><strong>Minify HTML: </strong>HTML Minification & Optimization.</li>
<li><strong>Font Awesome: </strong>Removes extra Font Awesome stylesheet.</li>
<li><strong>Change Heartbeat Interval: </strong>Changes default HeartBeat Pulse to 30 secs <b>*</b>, saving server resources</li>
<li><strong>JS Defer Booster Special: </strong>a Special Optimized Code on your <u>pages</u> for <font style="color: green;"><b><u>Additional Grades on Google Insights.</u></b></font> <b>*</b></li>
<li><strong>You Can Add your Analytics Tracking Code</strong>. Remove any other instances of your tracking code. ( ex. your Theme, or another plugin ) </li> 
<br/>
<br/>
* Checkout the Pegasus Project ( PRO Version ) for even more improved options!  
</table>	
</div>
    
    <?php submit_button(); ?>

</form>

<br/><br/>

</div>

  


<div class="box-region-right">

<div class="box-wpspgrpro">
<h3>Do you Like the FREE Version?</h3>
<li>Give Us a <strong>5 Star</strong> Review. <a target="_blank" href="https://wordpress.org/support/plugin/wp-speed-grades-pro/reviews/">WP Speed Grades Lite Reviews</a></li>
</div>	

<div class="box-wpspgrpro">
<h4>PRO version</h4>

<table>
<tr>
<td width="10%"><img src="<?php echo plugins_url( '/images/icon-arne.gif', __FILE__ ); ?>" align="center" /></td>
<td width="90%"><a target="_blank"  href="https://www.wp-pegasus.com">WP PEGASUS</a></td>
</tr>
<tr>
<p>According to Greek mythology legends, everywhere the winged horse, Pegasus, struck his hoof to the earth, flowers would burst from the ground. This Pegasus software will bring amazing improvements everywhere it's installed.</p>
</tr>
</table>
</div>	 


<div class="box-wpspgrpro">
<p><a target="_blank" href="https://www.wp-pegasus.com"><img style="width: 100%; height: auto;" src="<?php echo plugins_url( '/images/meet-wp-pegasus.jpg', __FILE__ ); ?>" align="center" /></a></p>
</div>



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



if ( $wpspgr_choice8=='1' ) {
		
//if ( (defined( 'AMP_QUERY_VAR' )) && (function_exists( 'is_amp_endpoint' ))
// (is_amp_endpoint()) ) {	 }	
//		
  if ( !is_admin() ) add_action( 'init', 'wpspgrpro_init_minify_html'
 , 1 );

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
?>
<!-- Google Analytics by WP Speed Grades Lite -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $wpspgr_choice42; ?>"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', '<?php echo $wpspgr_choice42; ?>');
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
    $settings['interval'] = 30;
    return $settings;
}

$wpspgr_choice1heart = get_option( 'a_wpspgrproheart');
if ( $wpspgr_choice1heart == 1  ) {
				add_filter( 'heartbeat_settings', 'wpspgrpro_heartbeat_settings' );
}		
		
?>