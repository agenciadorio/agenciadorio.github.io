<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class Automatic_DNS_Prefetch {

	/**
	 * The single instance of Automatic_DNS_Prefetch.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * The version number.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $_version;

	/**
	 * The token.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $_token;

	/**
	 * The main plugin file.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $file;

	/**
	 * The main plugin directory.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $dir;

	/**
	 * Suffix for Javascripts.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $script_suffix;

	/**
	 * Constructor function.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function __construct ( $file = '', $version = '1.0.0' ) {
		$this->_version = $version;
		$this->_token = 'automatic_dns_prefetch';

		// Load plugin environment variables
		$this->file = $file;
		$this->dir = dirname( $this->file );

		register_activation_hook( $this->file, array( $this, 'install' ) );

		// add svg file to head
		add_action('wp_head', array($this, 'automatic_dns_prefetch'), 1);

	} // End __construct ()

	/**
	 * Parse through WordPress scripts and styles and create
	 * DNS prefetch statements for external resources.
	 * @return echo out DNS prefetch statements
	 */
	public function automatic_dns_prefetch () {

		global $wp_scripts;
		global $wp_styles;

		// maintain an array of unique external hosts
		$unique_hosts = array();

		// scripts loop
		foreach ($wp_scripts->registered as $registered_script)
		{
			$this_host = parse_url($registered_script->src, PHP_URL_HOST);
			if ( !in_array($this_host, $unique_hosts) && !empty($this_host) && $this_host !== $_SERVER['SERVER_NAME'] )
			{
				$unique_hosts[] = $this_host;
			}
		}

		// styles loop
		foreach ($wp_styles->registered as $registered_style)
		{
			$this_host = parse_url($registered_style->src, PHP_URL_HOST);
			if ( !in_array($this_host, $unique_hosts) && !empty($this_host) && $this_host !== $_SERVER['SERVER_NAME'] )
			{
				$unique_hosts[] = $this_host;
			}
		}

		// echo out external DNS prefetches
		if (!empty($unique_hosts))
		{
			echo "<!-- Automatic DNS Prefetch by mabujo - https://mabujo.com/blog/automatic-dns-prefetch-wordpress-plugin/ --> \n";
			echo '<meta http-equiv="x-dns-prefetch-control" content="on">' . "\n";
			foreach ($unique_hosts as $key => $value)
			{
				echo '<link rel="dns-prefetch" href="//' . $value . '">'  . "\n";
			}
			echo "<!-- / Automatic DNS Prefetch -->";
		}
	}
	/**
	 * Main Automatic_DNS_Prefetch Instance
	 *
	 * Ensures only one instance of Automatic_DNS_Prefetch is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see Automatic_DNS_Prefetch()
	 * @return Main Automatic_DNS_Prefetch instance
	 */
	public static function instance ( $file = '', $version = '1.0.0' ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $file, $version );
		}
		return self::$_instance;
	} // End instance ()

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->_version );
	} // End __clone ()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->_version );
	} // End __wakeup ()

	/**
	 * Installation. Runs on activation.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function install () {
		$this->_log_version_number();
	} // End install ()

	/**
	 * Log the plugin version number.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	private function _log_version_number () {
		update_option( $this->_token . '_version', $this->_version );
	} // End _log_version_number ()

}
