<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

class DGWT_WCAS_Result_Details {

	function __construct() {

		// Searched result details ajax action
		if ( DGWT_WCAS_WC_AJAX_ENDPOINT ) {
			add_action( 'wc_ajax_' . DGWT_WCAS_RESULT_DETAILS_ACTION, array( $this, 'get_result_details' ) );
		} else {
			add_action( 'wp_ajax_nopriv_' . DGWT_WCAS_RESULT_DETAILS_ACTION, array( $this, 'get_result_details' ) );
			add_action( 'wp_ajax_' . DGWT_WCAS_RESULT_DETAILS_ACTION, array( $this, 'get_result_details' ) );
		}
	}

	/*
	 * Get searched result details
	 */

	public function get_result_details() {

		$output		 = array();
		$html		 = '';
		$suggestion	 = '';

		// Sugestion value
		if ( isset( $_REQUEST[ 'value' ] ) && !empty( $_REQUEST[ 'value' ] ) ) {
			$suggestion = sanitize_text_field( $_REQUEST[ 'value' ] );
		}

		// Get product details
		if ( !empty( $_REQUEST[ 'post_id' ] ) && is_numeric( $_REQUEST[ 'post_id' ] ) ) {

			$product_id = absint( $_REQUEST[ 'post_id' ] );
			if ( DGWT_WCAS_WOO_PRODUCT_POST_TYPE === get_post_type( $product_id ) ) {
				$html = $this->get_product_details( $product_id );
			}
		}

		// Get taxonomy details
		if ( isset( $_REQUEST[ 'term_id' ] ) && isset( $_REQUEST[ 'taxonomy' ] ) ) {

			$term_id = is_numeric( $_REQUEST[ 'term_id' ] ) && $_REQUEST[ 'term_id' ] > 0 ? absint( $_REQUEST[ 'term_id' ] ) : false;

			if ( $term_id !== false ) {

				$html = '';

				switch ( $_REQUEST[ 'taxonomy' ] ) {
					case DGWT_WCAS_WOO_PRODUCT_CATEGORY:
						$html	 = $this->get_taxonomy_details( $term_id, DGWT_WCAS_WOO_PRODUCT_CATEGORY, $suggestion );
						break;
					case DGWT_WCAS_WOO_PRODUCT_TAG:
						$html	 = $this->get_taxonomy_details( $term_id, DGWT_WCAS_WOO_PRODUCT_TAG, $suggestion );
						break;
				}
			}
		}



		$output[ 'details' ] = $html;

		echo json_encode( $output );
		die();
	}

	/*
	 * Prepare products details to the ajax output
	 * 
	 * @param int $product_id
	 * @param string $value Suggestion value
	 * 
	 * @return string HTML
	 */

	private function get_product_details( $product_id ) {

		$html = '';

		$product = wc_get_product( $product_id );
	
		if ( empty( $product) ) {
			return;
		}

		$details = array(
			'id'	 => $product->get_id(),
			'desc'	 => '',
		);


		// Get product desc
		$details[ 'desc' ] = dgwt_wcas_get_product_desc( $product, 500 );


		ob_start();
		include_once DGWT_WCAS_DIR . 'includes/tmpl/single-product.php';
		$html = ob_get_clean();


		return $html;
	}

	/*
	 * Prepare category details to the ajax output
	 * 
	 * @param int $term_id
	 * @param string taxonomy 
	 * @param string $suggestion Suggestion value 
	 * 
	 * @return string HTML
	 */

	private function get_taxonomy_details( $term_id, $taxonomy, $suggestion ) {

		$html	 = '';
		$title	 = '';

		ob_start();

		$query_args = $this->get_taxonomy_query_args();

		// Serach with specific category
		$query_args[ 'tax_query' ][] = array(
			'taxonomy'			 => $taxonomy,
			'field'				 => 'id',
			'terms'				 => $term_id,
			'include_children'	 => true,
		);

		$products = new WP_Query( $query_args );

		if ( $products->have_posts() ) {



			// Details box title
			$title .= '<span class="dgwt-wcas-datails-title">';
			$title .= '<span class="dgwt-wcas-details-title-tax">';
			if ( DGWT_WCAS_WOO_PRODUCT_CATEGORY === $taxonomy ) {
				$title .= __( 'Category', 'ajax-search-for-woocommerce' ) . ': ';
			} else {
				$title .= __( 'Tag', 'ajax-search-for-woocommerce' ) . ': ';
			}
			$title .= '</span>';
			$title .= $suggestion;
			$title .= '</span>';


			echo '<div class="dgwt-wcas-details-inner">';
			echo '<div class="dgwt-wcas-products-in-cat">';

			echo!empty( $title ) ? $title : '';

			while ( $products->have_posts() ) {
				$products->the_post();

				$product = new WC_Product( get_the_ID() );

				include DGWT_WCAS_DIR . 'includes/tmpl/single-product-tax.php';
			}

			echo '</div>';
			echo '</div>';
		}

		wp_reset_postdata();


		$html = ob_get_clean();


		return $html;
	}

	/*
	 * Taxonomy query args
	 * Get vars from settings
	 */

	private function get_taxonomy_query_args() {

		$show	 = sanitize_title( DGWT_WCAS()->settings->get_opt( 'show_for_tax' ) );
		$orderby = sanitize_title( DGWT_WCAS()->settings->get_opt( 'orderby_for_tax' ) );
		$order	 = sanitize_title( DGWT_WCAS()->settings->get_opt( 'order_for_tax' ) );

		$query_args = array(
			'posts_per_page' => 4,
			'post_status'	 => 'publish',
			'post_type'		 => 'product',
			'no_found_rows'	 => 1,
			'order'			 => $order,
			'meta_query'	 => array()
		);

		// @TODO Impelement show_hide and hide_free options
		// 
//		if ( empty( $instance[ 'show_hidden' ] ) ) {
//			$query_args[ 'meta_query' ][]	 = WC()->query->visibility_meta_query();
//			$query_args[ 'post_parent' ]	 = 0;
//		}
//
//		if ( !empty( $instance[ 'hide_free' ] ) ) {
//			$query_args[ 'meta_query' ][] = array(
//				'key'		 => '_price',
//				'value'		 => 0,
//				'compare'	 => '>',
//				'type'		 => 'DECIMAL',
//			);
//		}

		$query_args[ 'meta_query' ][]	 = WC()->query->stock_status_meta_query();
		$query_args[ 'meta_query' ]		 = array_filter( $query_args[ 'meta_query' ] );

		switch ( $show ) {
			case 'featured' :
				$query_args[ 'meta_query' ][]	 = array(
					'key'	 => '_featured',
					'value'	 => 'yes'
				);
				break;
			case 'onsale' :
				$product_ids_on_sale			 = wc_get_product_ids_on_sale();
				$product_ids_on_sale[]			 = 0;
				$query_args[ 'post__in' ]		 = $product_ids_on_sale;
				break;
		}

		switch ( $orderby ) {
			case 'price' :
				$query_args[ 'meta_key' ]	 = '_price';
				$query_args[ 'orderby' ]	 = 'meta_value_num';
				break;
			case 'rand' :
				$query_args[ 'orderby' ]	 = 'rand';
				break;
			case 'sales' :
				$query_args[ 'meta_key' ]	 = 'total_sales';
				$query_args[ 'orderby' ]	 = 'meta_value_num';
				break;
			default :
				$query_args[ 'orderby' ]	 = 'date';
		}


		return $query_args;
	}

}

?>