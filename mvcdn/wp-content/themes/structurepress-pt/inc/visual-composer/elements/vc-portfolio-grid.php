<?php

/**
 * Portfolio Grid content element for the Visual Composer editor
 */

if ( ! class_exists( 'PT_VC_Portfolio_Grid' ) ) {
	class PT_VC_Portfolio_Grid extends PT_VC_Shortcode {

		// Basic shortcode settings
		function shortcode_name() { return 'pt_vc_portfolio_grid'; }

		// Initialize the shortcode by calling the parent constructor
		public function __construct() {
			parent::__construct();
		}

		// Overwrite the register_shortcode function from the parent class
		public function register_shortcode( $atts, $content = null ) {
			$atts = shortcode_atts( array(
				'title'          => esc_html__( 'All Projects', 'structurepress-pt' ),
				'layout'         => 'grid',
				'posts_per_page' => -1,
				'orderby'        => 'ID',
				'order'          => 'ASC',
				'add_cta'        => '',
				'cta_text'       => '',
				'cta_btn'        => '',
				'cta_link'       => '',
				), $atts );

			$instance            = $atts;
			$instance['add_cta'] = ( 'true' == $instance['add_cta'] ) ? 'yes' : '';
			$args['widget_id']   = uniqid( 'widget-' );

			ob_start();
			the_widget( 'PW_Portfolio_Grid', $instance, $args );
			return ob_get_clean();
		}

		// Overwrite the vc_map_shortcode function from the parent class
		public function vc_map_shortcode() {
			vc_map( array(
				'name'     => _x( 'Portfolio Grid', 'backend', 'structurepress-pt' ),
				'base'     => $this->shortcode_name(),
				'category' => _x( 'Content', 'backend', 'structurepress-pt' ),
				'icon'     => get_template_directory_uri() . '/vendor/proteusthemes/visual-composer-elements/assets/images/pt.svg',
				'params'   => array(
					array(
						'type'       => 'textfield',
						'holder'     => 'div',
						'heading'    => _x( 'Label for all items', 'backend', 'structurepress-pt' ),
						'param_name' => 'title',
						'value'      => _x( 'All Projects', 'backend', 'structurepress-pt' ),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => _x( 'Layout', 'backend', 'structurepress-pt' ),
						'param_name' => 'layout',
						'value'      => array(
							_x( 'Display all the items in grid (4 in a row)', 'backend', 'structurepress-pt' )             => 'grid',
							_x( 'Display only one row of items, with arrows to see more', 'backend', 'structurepress-pt' ) => 'slider',
						),
					),
					array(
						'type'        => 'input_number',
						'heading'     => _x( 'Maximum number of items', 'backend', 'structurepress-pt' ),
						'description' => _x( 'Set -1 to show all.', 'backend', 'structurepress-pt' ),
						'param_name'  => 'posts_per_page',
						'min'         => -1,
						'max'         => 1000,
						'value'       => -1,
					),
					array(
						'type'       => 'dropdown',
						'heading'    => _x( 'Order items by', 'backend', 'structurepress-pt' ),
						'param_name' => 'orderby',
						'value'      => array(
							_x( 'Post ID', 'backend', 'structurepress-pt' )            => 'ID',
							_x( 'Title', 'backend', 'structurepress-pt' )              => 'title',
							_x( 'Date', 'backend', 'structurepress-pt' )               => 'date',
							_x( 'Last modified date', 'backend', 'structurepress-pt' ) => 'modified',
							_x( 'Random Order', 'backend', 'structurepress-pt' )       => 'rand',
						),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => _x( 'Order', 'backend', 'structurepress-pt' ),
						'param_name' => 'order',
						'value'      => array(
							_x( 'Ascending', 'backend', 'structurepress-pt' )  => 'ASC',
							_x( 'Descending', 'backend', 'structurepress-pt' ) => 'DESC',
						),
					),
					array(
						'type'       => 'checkbox',
						'heading'    => _x( 'Add CTA (click to action) project.', 'backend', 'structurepress-pt' ),
						'param_name' => 'add_cta',
					),
					array(
						'type'       => 'textfield',
						'heading'    => _x( 'CTA text', 'backend', 'structurepress-pt' ),
						'param_name' => 'cta_text',
						'dependency' => array(
							'element'     => 'add_cta',
							'not_empty'   => true,
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => _x( 'CTA button text', 'backend', 'structurepress-pt' ),
						'param_name' => 'cta_btn',
						'dependency' => array(
							'element'     => 'add_cta',
							'not_empty'   => true,
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => _x( 'CTA link', 'backend', 'structurepress-pt' ),
						'param_name' => 'cta_link',
						'dependency' => array(
							'element'     => 'add_cta',
							'not_empty'   => true,
						),
					),
				)
			) );
		}
	}

	// Initialize the class
	new PT_VC_Portfolio_Grid;
}