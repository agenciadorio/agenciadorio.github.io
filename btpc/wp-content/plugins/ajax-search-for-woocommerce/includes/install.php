<?php

/**
 * Installation related functions and actions.
 *
 */
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

class DGWT_WCAS_Install {

	/**
	 * Hook in tabs.
	 */
	public static function init() {

		add_action( 'admin_init', array( __CLASS__, 'check_version' ), 5 );
	}

	/**
	 * Install 
	 */
	public static function install() {


		if ( !defined( 'DGWT_WCAS_INSTALLING' ) ) {
			define( 'DGWT_WCAS_INSTALLING', true );
		}

		self::save_activation_date();

		self::create_options();
		self::create_cron_jobs();
		
		// Update plugin version
		update_option( 'dgwt_wcas_version', DGWT_WCAS_VERSION );

	}
	
	/**
	 * Default options
	 */
	private static function create_options() {

		global $dgwt_wcas_settings;

		$sections = DGWT_WCAS()->settings->settings_fields();

		$settings = array();

		if ( is_array( $sections ) && !empty( $sections ) ) {
			foreach ( $sections as $options ) {

				if ( is_array( $options ) && !empty( $options ) ) {

					foreach ( $options as $option ) {

						if ( isset( $option[ 'name' ] ) && !isset( $dgwt_wcas_settings[ $option[ 'name' ] ] ) ) {

							$settings[ $option[ 'name' ] ] = isset( $option[ 'default' ] ) ? $option[ 'default' ] : '';
						}
					}
				}
			}
		}

		$update_options = array_merge( $settings, $dgwt_wcas_settings );

		update_option( DGWT_WCAS_SETTINGS_KEY, $update_options );
	}

	/**
	 * Save activation timestamp
	 * Used to display notice, asking for a feedback
	 *
	 * @return null
	 */
	private static function save_activation_date() {

		$date = get_option( 'dgwt_wcas_activation_date' );

		if ( empty( $date ) ) {
			update_option( 'dgwt_wcas_activation_date', time() );
		}

	}

	/**
	 * Create cron jobs (clear them first)
	 */
	private static function create_cron_jobs() {
		//@todo clear and init schedule

	}
	
	/**
	 * Check version
	 */
	public static function check_version() {

		if ( !defined( 'IFRAME_REQUEST' ) ) {

			if ( get_option( 'dgwt_wcas_version' ) != DGWT_WCAS_VERSION ) {
				self::install();
			}
		}
	}

}


DGWT_WCAS_Install::init();
