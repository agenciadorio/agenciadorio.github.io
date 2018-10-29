<?php

/**
 * Contact Detail Item element for the Visual Composer editor,
 * that can only be used in the Contact Profile container
 */

if ( ! class_exists( 'PT_VC_Contact_Detail_Item' ) ) {
	class PT_VC_Contact_Detail_Item extends PT_VC_Shortcode {

		// Basic shortcode settings
		function shortcode_name() { return 'pt_vc_contact_detail_item'; }

		// Initialize the shortcode by calling the parent constructor
		public function __construct() {
			parent::__construct();
		}

		// Overwrite the register_shortcode function from the parent class
		public function register_shortcode( $atts, $content = null ) {
			$atts = shortcode_atts( array(
				'text' => '',
				'icon' => 'fa fa-map-marker',
				), $atts );

			// The PHP_EOL is added so that it can be used as a separator between multiple contact detail items
			return PHP_EOL . json_encode( $atts );
		}

		// Overwrite the vc_map_shortcode function from the parent class
		public function vc_map_shortcode() {
			vc_map( array(
				'name'     => _x( 'Contact Detail Item', 'backend', 'structurepress-pt' ),
				'base'     => $this->shortcode_name(),
				'category' => _x( 'Content', 'backend', 'structurepress-pt' ),
				'icon'     => get_template_directory_uri() . '/vendor/proteusthemes/visual-composer-elements/assets/images/pt.svg',
				'as_child' => array( 'only' => 'pt_vc_container_contact_profile' ),
				'params'   => array(
					array(
						'type'       => 'textfield',
						'holder'     => 'div',
						'heading'    => _x( 'Text', 'backend', 'structurepress-pt' ),
						'param_name' => 'text',
					),
					array(
						'type'        => 'iconpicker',
						'heading'     => _x( 'Icon', 'backend', 'structurepress-pt' ),
						'param_name'  => 'icon',
						'value'       => 'fa fa-map-marker',
						'description' => _x( 'Select icon from library.', 'backend', 'structurepress-pt' ),
						'settings'    => array(
							'emptyIcon'    => false,
							'iconsPerPage' => 50,
						),
					),
				)
			) );
		}
	}

	// Initialize the class
	new PT_VC_Contact_Detail_Item;
}