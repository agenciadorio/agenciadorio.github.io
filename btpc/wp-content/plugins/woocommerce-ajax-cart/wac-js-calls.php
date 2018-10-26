<?php

// when render cart table page
add_action('woocommerce_before_cart_table', 'wac_cart_table');

function wac_cart_table() {
    // enqueue style
    wp_enqueue_style('wooajaxcart', plugins_url('wooajaxcart.css', WAC_PLUGIN));

    // enqueue js
    wp_enqueue_script('wooajaxcart', plugins_url('wooajaxcart.js', WAC_PLUGIN));

    $translation_array = array(
	    'updating_text' => __( 'Updating...', 'woocommerce-ajax-cart' ),
	    'warn_remove_text' => __( 'Are you sure you want to remove this item from cart?', 'woocommerce-ajax-cart' )
    );
    wp_localize_script( 'wooajaxcart', 'wooajaxcart_localization', $translation_array );

    //
    wac_zero_quantity_confirmation();
}

// check user confirmation when "quantity = 0" setting
// when disabled, override the JS function to always return TRUE
function wac_zero_quantity_confirmation() {
    if ( get_option('wac_confirmation_zero_qty') == 'no' ) {
        wp_add_inline_script( 'wooajaxcart', "jQuery(document).ready(function(jQuery){
            wacZeroQuantityCheck = function(el_qty) {
                return true;
            };
        });" );
    }
}
