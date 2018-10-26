<?php
/*
 * Plugin Name: WooCommerce Moip Official
 * Plugin URI:  https://apiki.com/moip
 * Version:     1.2.1
 * Author:      Apiki WordPress
 * Author URI:  https://apiki.com
 * Text Domain: woo-moip-official
 * Domain Path: /languages
 * License:     GPLv3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Description: Official Moip plugin built with the best development practices. Based on V2, new REST Moip’s API, providing more speed, safety and sales conversion.
 */

if ( ! function_exists( 'add_action' ) ) {
	exit( 0 );
}

define( 'APIKI_MOIP_TEXTDOMAIN', 'woo-moip-official' );
define( 'APIKI_MOIP_OPTION_ACTIVATE', 'apiki_moip_official_activate' );
define( 'APIKI_MOIP_ROOT_FILE', __FILE__ );
define( 'APIKI_MOIP_VERSION', '1.2.1' );

function apiki_moip_render_admin_notice_html( $message, $type = 'error' )
{
?>
	<div class="<?php echo $type; ?> notice is-dismissible">
		<p>
			<strong><?php _e( 'WooCommerce Moip Official', APIKI_MOIP_TEXTDOMAIN ); ?>: </strong>

			<?php echo $message; ?>
		</p>
	</div>
<?php
}

if ( version_compare( PHP_VERSION, '5.6', '<' ) ) {

	function apiki_moip_admin_notice_php_version()
	{
		apiki_moip_render_admin_notice_html(
			__( 'Your PHP version is not supported. Required >= 5.6.', APIKI_MOIP_TEXTDOMAIN )
		);
	}

	_apiki_moip_load_notice( 'admin_notice_php_version' );
	return;
}

function apiki_moip_admin_notice_error()
{
	apiki_moip_render_admin_notice_html(
		__( 'WooCoomerce plugin is required.', APIKI_MOIP_TEXTDOMAIN )
	);
}

function _apiki_moip_load_notice( $name )
{
	add_action( 'admin_notices', "apiki_moip_{$name}" );
}

function _apiki_moip_load_instances()
{
	require_once __DIR__ . '/vendor/autoload.php';

	Woocommerce\Moip\Core::instance();

	do_action( 'apiki_moip_init' );
}

function apiki_moip_plugins_loaded_check()
{
	class_exists( 'WooCommerce' ) ? _apiki_moip_load_instances() : _apiki_moip_load_notice( 'admin_notice_error' );
}
add_action( 'plugins_loaded', 'apiki_moip_plugins_loaded_check', 0 );

function apiki_moip_on_activation()
{
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	add_option( APIKI_MOIP_OPTION_ACTIVATE, true );

	register_uninstall_hook( __FILE__, 'apiki_moip_on_uninstall' );
}

function apiki_moip_on_deactivation()
{

}

function apiki_moip_on_uninstall()
{

}

register_activation_hook( __FILE__, 'apiki_moip_on_activation' );
register_deactivation_hook( __FILE__, 'apiki_moip_on_deactivation' );
