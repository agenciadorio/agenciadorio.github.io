<?php
// Exit if accessed directly
if ( !defined( 'DGWT_WCAS_FILE' ) ) {
	exit;
}

$submit_text = DGWT_WCAS()->settings->get_opt( 'search_submit_text' );
$has_submit = DGWT_WCAS()->settings->get_opt( 'show_submit_button' );
?>

<div class="dgwt-wcas-search-wrapp <?php echo dgwt_wcas_search_css_classes( $args ); ?>">
    <form class="dgwt-wcas-search-form" role="search" action="<?php echo esc_url( home_url( '/' ) ) ?>" method="get">
        <div class="dgwt-wcas-sf-wrapp">
			<?php 
			if($has_submit !== 'on'){
			dgwt_wcas_print_ico_loupe();
			}
			?>	
            <label class="screen-reader-text" for="dgwt-wcas-search"><?php _e( 'Products search', 'ajax-search-for-woocommerce' ) ?></label>
			
            <input 
				type="search"
				class="dgwt-wcas-search-input"
				name="s"
				value="<?php echo get_search_query() ?>"
				placeholder="<?php echo DGWT_WCAS()->settings->get_opt( 'search_placeholder', __( 'Search for products...', 'ajax-search-for-woocommerce' ) ) ?>"
				/>
			<div class="dgwt-wcas-preloader"></div>
			
			<?php if($has_submit === 'on'): ?>
			<button type="submit" class="dgwt-wcas-search-submit"><?php echo empty( $submit_text ) ? dgwt_wcas_print_ico_loupe() : esc_html( $submit_text ); ?></button>
			<?php endif; ?>
			
			<input type="hidden" name="post_type" value="product" />
			<input type="hidden" name="dgwt_wcas" value="1" />

			<?php
// WPML compatible
			if ( defined( 'ICL_LANGUAGE_CODE' ) ):
				?>
				<input type="hidden" name="lang" value="<?php echo( ICL_LANGUAGE_CODE ); ?>" />
			<?php endif ?>

        </div>
    </form>
</div>