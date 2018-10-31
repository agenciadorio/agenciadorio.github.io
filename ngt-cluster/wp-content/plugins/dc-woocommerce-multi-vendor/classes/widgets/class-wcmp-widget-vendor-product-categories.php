<?php

if (!defined('ABSPATH')) {
    exit;
}

class WCMp_Widget_Vendor_Product_Categories extends WC_Widget {

    public $vendor_term_id;

    public function __construct() {
        $this->widget_cssclass = 'wcmp woocommerce wcmp_widget_vendor_product_categories widget_product_categories';
        $this->widget_description = __('Displays a list of product categories added by the vendor on the vendor shop page.', 'dc-woocommerce-multi-vendor');
        $this->widget_id = 'wcmp_vendor_product_categories';
        $this->widget_name = __('WCMp: Vendor\'s Product Categories', 'dc-woocommerce-multi-vendor');
        $this->settings = array(
            'title' => array(
                'type' => 'text',
                'std' => __('Vendor Product categories', 'dc-woocommerce-multi-vendor'),
                'label' => __('Title', 'dc-woocommerce-multi-vendor'),
            ),
            'count' => array(
                'type' => 'checkbox',
                'std' => 1,
                'label' => __('Show product counts', 'dc-woocommerce-multi-vendor'),
            ),
			'hierarchical'       => array(
				'type'  => 'checkbox',
				'std'   => 1,
				'label' => __('Show hierarchy', 'dc-woocommerce-multi-vendor'),
			),
        );
        parent::__construct();
    }

    public function widget($args, $instance) {
        global $wp_query, $WCMp;
        if (!is_tax($WCMp->taxonomy->taxonomy_name)) {
            return;
        }
        $count = isset($instance['count']) ? $instance['count'] : $this->settings['count']['std'];
        $hierarchical = isset( $instance['hierarchical'] ) ? $instance['hierarchical'] : $this->settings['hierarchical']['std'];

        $this->vendor_term_id = $wp_query->queried_object->term_id;
        $this->widget_start($args, $instance);
        $vendor = get_wcmp_vendor_by_term($this->vendor_term_id);
        $vendor_products = $vendor->get_products();
        $product_ids = wp_list_pluck($vendor_products, 'ID');
        $associated_terms = array();
        foreach ($product_ids as $product_id) {
            $product_categories = get_the_terms($product_id, 'product_cat');
            if ($product_categories) {
                $term_ids = wp_list_pluck($product_categories, 'term_id');
                if ($term_ids) {
                    foreach ($term_ids as $term_id) {
                        $associated_terms[$term_id][] = $product_id;
                    }
                }
            }
        }
        $list_args = array('taxonomy' => 'product_cat');
        $product_cats = get_terms($list_args);
        if ($product_cats) {
            echo '<ul class="product-categories">';
            
            if($hierarchical) {
            	echo $this->get_hierarchical_categories($product_cats, $associated_terms, $count);
            } else {
				foreach ($product_cats as $product_cat) {
					$term_count = isset($associated_terms[$product_cat->term_id]) ? count(array_unique($associated_terms[$product_cat->term_id])) : 0;
					if ($term_count) {
						echo '<li class="cat-item cat-item-' . $product_cat->term_id . '"><a href="' . get_term_link( $product_cat->term_id, 'product_cat' ) . '">' . $product_cat->name . '</a>';
						if ($count) {
							echo '<span class="count">(' . $term_count . ')</span>';
						}
						echo '</li>';
					}
				}
			}
			
            echo '</ul>';
        }
        $this->widget_end($args);
    }
    
    function get_hierarchical_categories($terms, $associated_terms, $show_count, $parent_id = 0) {
    	$itemOutput = '';
    	foreach ($terms as $term_key => $term) {
			if ($parent_id == $term->parent) {
				$output_inner = $this->get_hierarchical_categories($terms, $associated_terms, $show_count, $term->term_id);
				$term_count = isset($associated_terms[$term->term_id]) ? count(array_unique($associated_terms[$term->term_id])) : 0;
				if ($term_count) {
					if($output_inner != '') $parent_class = ' cat-parent';
					else $parent_class = '';
					$itemOutput .= '<li class="cat-item cat-item-' . $term->term_id . $parent_class . '"><a href="' . get_term_link( $term->term_id, 'product_cat' ) . '">' . $term->name . '</a>';
					if ($show_count) {
						$itemOutput .= '<span class="count">(' . $term_count . ')</span>';
					}
					if($output_inner != '') {
						$itemOutput .=  '<ul class="children">' . $output_inner . '</ul>';
						$output_inner = '';
					}
					$itemOutput .= '</li>';
				} else {
					
				}
			}
		}
		
		return $itemOutput;
    }
}
