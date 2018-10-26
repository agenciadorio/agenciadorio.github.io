<?php

if ( class_exists( 'WC_Widget' ) ) {


	add_action( 'widgets_init', function() {
		register_widget( 'DGWT_WCAS_Search_Widget' );
	} );

	class DGWT_WCAS_Search_Widget extends WC_Widget {

		/**
		 * Constructor
		 */
		public function __construct() {

			
			$this->widget_cssclass		 = 'woocommerce widget_search dgwt_wcas_ajax_search';
			$this->widget_description	 = __( 'Ajax (live) search form for WooCommerce', 'ajax-search-for-woocommerce' );
			$this->widget_id			 = 'dgwt_wcas_ajax_search';
			$this->widget_name			 = __( 'Woo Ajax Search', 'ajax-search-for-woocommerce' );
			$this->settings				 = array(
				'title'			 => array(
					'type'	 => 'text',
					'std'	 => '',
					'label'	 => __( 'Title', 'ajax-search-for-woocommerce' )
				)
			);


			parent::__construct();
		}

		/**
		 * Outputs the content of the widget
		 *
		 * @param array $args
		 * @param array $instance
		 */
		public function widget( $args, $instance ) {

			$this->widget_start( $args, $instance );

			echo dgwt_wcas_get_search_form();

			$this->widget_end( $args );
		}

	}

}