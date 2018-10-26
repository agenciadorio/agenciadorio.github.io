<?php
/**
 * electro engine room
 *
 * @package electro
 */

/**
 * Initialize all the things.
 */
require get_template_directory() . '/inc/init.php';

/**
 * Note: Do not add any custom code here. Please use a child theme so that your customizations aren't lost during updates.
 * http://codex.wordpress.org/Child_Themes
 */

add_filter( 'woocommerce_get_price_html', 'custom_variable_price_html', 10, 2 );
function custom_variable_price_html( $price, $product ) {
	if ( ! $product->is_type( 'variable' ) || $product->get_price() === '') return $price;

	$result = '';
	$prices = $product->get_variation_prices( true );

	if ( ! empty( $prices['price'] ) ) {
		$min_price = current( $prices['price'] );
		$max_price = end( $prices['price'] );
		if ( ( ! $min_price ) || $min_price !== $max_price ) {
			$result .= '<span class="from">' . __( '<small>A partir de</small>', 'prefix' ) . ' </span>';
		}
		$result .= woocommerce_price( $min_price );
	}

	return $result;
}


function alterar_admin_bar( $admin_bar ) {

// Remove o logotipo
$admin_bar->remove_menu( 'wp-logo' );



return $admin_bar;

}
// Adicionamos a chamada à action hook com um valor de posição alto
// de maneira a não correr o risco desta função processar-se antes
// dos elementos estarem completamente carregados.
add_action( 'admin_bar_menu', 'alterar_admin_bar', 99 );




function bbloomer_hide_admin_bar_if_non_admin( $show ) {
    if ( ! current_user_can( 'administrator' ) ) $show = false;
    return $show;
}
 
add_filter( 'show_admin_bar', 'bbloomer_hide_admin_bar_if_non_admin', 20, 1 );
 
function remove_footer_admin () {
  echo 'Plataforma Best Peças MarketPlace.';
}
add_filter('admin_footer_text', 'remove_footer_admin');

function hide_help() {
    echo '<style type="text/css">
            #contextual-help-link-wrap { display: none !important; }
          </style>';
}
add_action('admin_head', 'hide_help');


function wpb_image_editor_default_to_gd( $editors ) {
    $gd_editor = 'WP_Image_Editor_GD';
    $editors = array_diff( $editors, array( $gd_editor ) );
    array_unshift( $editors, $gd_editor );
    return $editors;
}
add_filter( 'wp_image_editors', 'wpb_image_editor_default_to_gd' );


function iconic_remove_password_strength() {
    wp_dequeue_script( 'wc-password-strength-meter' );
}
add_action( 'wp_print_scripts', 'iconic_remove_password_strength', 10 );


add_action('wp_ajax_nopriv_ppom_ajax_validation', 'ppom_woocommerce_ajax_validate');