<?php
/*
   Plugin Name: wuk.ch DNS-Prefetch / PreRender
   Plugin URI: http://www.wuk.ch/
   Description: DNS-Prefetch, HTM5 Prefetch and Google Prerender implemented over internal statistics, no options needed.
   Version: 1.1.4
   Author: web updates kmu GmbH
   Author URI: http://www.wuk.ch

   This program is distributed under the GNU General Public License, Version 2,
   June 1991. Copyright (C) 1989, 1991 Free Software Foundation, Inc., 51 Franklin
   St, Fifth Floor, Boston, MA 02110, USA

   THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
   ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
   WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
   DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
   ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
   (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
   LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
   ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
   (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
   SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

if (!class_exists('WUKPRERENDER')) {
	class WUKPRERENDER {
		function __construct() {
			if (!is_admin()) {
				add_action( 'wp_print_scripts', array(&$this, 'fetchrender') );
			}
		}
		private function cleanup($url) {
			$url = str_replace(WP_SITEURL,"",$url);
			if(stristr($url,"comment-page-")) {
				$url = explode("comment-page-",$url);
				$url = $url[0];
			}
			if(stristr($url,"wp-admin") OR stristr($url,"wp-login")) { $url = ''; };
			return trim($url);
		}
		function fetchrender() {
			global $wpdb, $wp_scripts, $wp_styles;
						
			// Custom Statistics for PreRender Functionality
			$ref = $this->cleanup(wp_get_referer());
			$uri = $this->cleanup($_SERVER['REQUEST_URI']);
			$time = time();
			
			if($uri != '' AND $ref != '' AND !is_user_logged_in() AND $ref != $uri) {
				$sql = $wpdb->prepare("SELECT count(id) as `menge` FROM `".$wpdb->prefix."wukstats` WHERE `referal`='%s' AND `path`='%s' AND `time`>='%s' LIMIT 1;", $ref, $uri, $time-30);
				$response = $wpdb->get_var($sql);
				if($response == '0') {
					$sql = $wpdb->prepare("INSERT INTO `".$wpdb->prefix."wukstats` (`time`, `referal`, `path`) VALUES ('%s', '%s', '%s');", $time, $ref, $uri);
					$wpdb->get_var($sql);
				}
			}
			$output = wp_cache_get('prerender'.md5($uri) , 'wuk');
			if ($output == false OR is_user_logged_in()) {
				// DNS Prefetch Function
				$setting = get_option('wukprerender');
				if ($time > $setting['time'] OR $setting['out'] == '') {
					foreach( $wp_scripts->registered as $handle => $value ) {
						if (substr($value->src,0,4) == "http") {
							unset($treffer);
							preg_match("/\/\/(.*?)\//",$value->src,$treffer);
							if ($list[$treffer[1]] != '1' AND $treffer[1] != '') {
								$list[$treffer[1]] = '1';
							}
						}
					}
					foreach( $wp_styles->registered as $handle => $value ) {
						if (substr($value->src,0,4) == "http") {
							unset($treffer);
							preg_match("/\/\/(.*?)\//",$value->src,$treffer);
							if ($list[$treffer[1]] != '1' AND $treffer[1] != '') {
								$list[$treffer[1]] = '1';
							}
						}
					}
					$ddone = array();
					
					$setting['out'] = '';
					foreach( $list as $name => $value ) {
						if ($_SERVER['HTTP_HOST'] != $name) {
							if ($ddone[$name] != '1') {
								$setting['out'] .= "<link rel=\"dns-prefetch\" href=\"//".$name."\">".PHP_EOL;
								$ddone[$name] = '1';
							}
						}
					}
					
					// Delete old unused data in stats db
					$setting['time'] = $time + (24*3600);
					
					$wpdb->get_row($wpdb->prepare("DELETE FROM `".$wpdb->prefix."wukstats` WHERE `time`<'%s';"), $time - (60*24*3600));
					update_option('wukprerender', $setting);
				}
				$output = "<!-- wuk.ch DNS-Prefetch / Prerender START -->".PHP_EOL;
				$output .= $setting['out'];
			
				// PreRender Function
				if ($this->is_blog() AND is_home()) {
					$out = "<link rel=\"prefetch\" href=\"".get_permalink()."\">".PHP_EOL
					."<link rel=\"prerender\" href=\"".get_permalink()."\">".PHP_EOL;
				}
				elseif ($uri != '') {
					$sql = $wpdb->prepare("SELECT `path` FROM `".$wpdb->prefix."wukstats` WHERE `referal`='%s' AND `path`!='%s' GROUP BY `path` ORDER BY count(id) DESC LIMIT 1;", $uri, $uri);
					$response = $wpdb->get_var($sql);
					if($response != '') {
						$out = "<link rel=\"prefetch\" href=\"".WP_SITEURL.$response."\">".PHP_EOL
						."<link rel=\"prerender\" href=\"".WP_SITEURL.$response."\">".PHP_EOL;
					}

				}
				$output .= $out;
				$output .= "<!-- wuk.ch DNS-Prefetch / Prerender END -->".PHP_EOL;
				$output = apply_filters('wuk_prerender', $output);
				wp_cache_set( 'prerender'.md5($uri) , $output, 'wuk', 86400);
			}
			echo $output;
			
		}
		function is_blog () {
			return ( is_archive() || is_author() || is_category() || is_home() || is_single() || is_tag()) && 'post' == get_post_type();
		}
		function activate() {
			global $wpdb;
			$qry = $wpdb->get_row("SHOW CREATE TABLE `".$wpdb->prefix."wukstats`;");
			if (count($qry) < '1') {
				$wpdb->get_row("CREATE TABLE `".$wpdb->prefix."wukstats` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `time` int(11) DEFAULT NULL,
				  `referal` varchar(120) DEFAULT NULL,
				  `path` varchar(120) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				);");
			}
		}
		function uninstall() {
			global $wpdb;
			$wpdb->get_row("DROP TABLE `".$wpdb->prefix."wukstats`;");
		}
	}
	add_action('init', 'WUKPRERENDER1');
	function WUKPRERENDER1() {
		global $WUKPRERENDER;
		$WUKPRERENDER = new WUKPRERENDER();
	}
}

if (function_exists('register_activation_hook')) { register_activation_hook(__FILE__, array('WUKPRERENDER', 'activate')); }
if (function_exists('register_uninstall_hook')) { register_uninstall_hook(__FILE__, array('WUKPRERENDER', 'uninstall')); }

?>