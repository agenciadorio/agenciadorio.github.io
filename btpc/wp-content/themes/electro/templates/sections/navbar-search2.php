<?php
/**
 * Search Bar
 *
 * @author  Transvelo
 * @package Electro/Templates
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if( is_rtl() ) {
	$dir_value = 'rtl';
} else {
	$dir_value = 'ltr';
}

$navbar_search_text 		 = apply_filters( 'electro_navbar_search_placeholder', esc_html__( 'Search for products', 'electro' ) );
$navbar_search_dropdown_text = apply_filters( 'electro_navbar_search_dropdown_text', esc_html__( 'All Categories', 'electro' ) );
?> 

<?php if( is_woocommerce_activated() ) : ?>
<form class="navbar-search" method="get" action="<?php echo esc_url( home_url( '/'  ) ); ?>">
	<label class="sr-only screen-reader-text" for="search"><?php  echo esc_html__( 'Search for:', 'electro' );?></label>
	<div class="input-group">
   <?php echo do_shortcode("[wcas-search-form]"); ?>
    	<?php if( apply_filters( 'electro_enable_search_categories_filter', true ) ) : ?>
	
    
		
		<?php endif; ?>
		<div class="input-group-btn">
			
		</div>
	</div>
</form>
<?php else : ?>
<form class="navbar-search" method="get" action="<?php echo esc_url( home_url( '/'  ) ); ?>">
	<div class="input-group">
		<label class="sr-only screen-reader-text" for="search"><?php  echo esc_html__( 'Search for:', 'electro' );?></label>
    	<input type="text" id="search" class="search-field form-control" dir="<?php echo esc_attr( $dir_value ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" placeholder="<?php echo esc_attr( esc_html__( 'Search', 'electro' ) ); ?>" />
		<div class="input-group-btn">
			<button type="submit" class="btn btn-secondary"><i class="ec ec-search"></i></button>
		</div>
	</div>
</form>
<?php endif; ?>
