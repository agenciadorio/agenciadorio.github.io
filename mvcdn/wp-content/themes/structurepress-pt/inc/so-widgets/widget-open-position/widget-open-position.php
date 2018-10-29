<?php

/*
Widget Name: Open Position
Description: Widget for displaying job offers in Page Builder.
Author: ProteusThemes
Author URI: https://www.proteusthemes.com
*/

class PW_Open_Position extends SiteOrigin_Widget {

	function __construct() {

		// Call the parent constructor with the required arguments.
		parent::__construct(
			// The unique id for your widget.
			'pw_open_position',
			// The name of the widget for display purposes.
			sprintf( 'ProteusThemes: %s', esc_html__( 'Open Position', 'structurepress-pt' ) ),
			// The $widget_options array, which is passed through to WP_Widget.
			array(
				'description' => esc_html__( 'Widget for displaying job offers in Page Builder.', 'structurepress-pt' ),
				'classname'   => 'widget-open-position',
			),
			// The $control_options array, which is passed through to WP_Widget
			array(),
			// The $form_options array, which describes the form fields used to configure SiteOrigin widgets.
			array(
				'title' => array(
					'type' => 'text',
					'label' => _x( 'Title', 'backend', 'structurepress-pt' ),
				),
				'date' => array(
					'type' => 'text',
					'label' => _x( 'Date', 'backend', 'structurepress-pt' ),
				),
				'content' => array(
					'type' => 'tinymce',
					'label' => _x( 'Content', 'backend', 'structurepress-pt' ),
				),
				'details_title' => array(
					'type' => 'text',
					'label' => _x( 'Details Title', 'backend', 'structurepress-pt' ),
				),
				'detail_items' => array(
					'type' => 'repeater',
					'label' => _x( 'Detail Items', 'backend', 'structurepress-pt' ),
					'item_name' => _x( 'Item', 'backend', 'structurepress-pt' ),
					'fields' => array(
						'icon' => array(
							'type' => 'icon',
							'label' => _x( 'Icon', 'backend', 'structurepress-pt' ),
						),
						'text' => array(
							'type' => 'text',
							'label' => _x( 'Text', 'backend', 'structurepress-pt' ),
						),
					),
				),
			),
			// The basedir
			plugin_dir_path( __FILE__ )
		);
	}

	function get_template_name( $instance ) {
		return 'open-position-template';
	}

	function get_style_name( $instance ) {
		return '';
	}
}

siteorigin_widget_register( 'open-position', __FILE__, 'PW_Open_Position' );