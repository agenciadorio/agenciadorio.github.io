<?php

/**
 * The template for displaying vendor tools
 *
 * Override this template by copying it to yourtheme/dc-product-vendor/vendor-dashboard/vendor-tools.php
 *
 * @author 	WC Marketplace
 * @package 	WCMp/Templates
 * @version   3.1.5
 */
if (!defined('ABSPATH')) {
    // Exit if accessed directly
    exit;
}

do_action( 'before_wcmp_vendor_tools_content' );
?>
<div class="col-md-12">
    <div class="panel panel-default panel-pading">
        <div class="wcmp-vendor-tools panel-body">
            <div class="tools-item">
                <label class="control-label col-md-9 col-sm-6">
                    <?php _e( 'Vendor Dashboard Transients', 'dc-woocommerce-multi-vendor' ); ?>
                    <p class="description"><?php _e( 'This tool will clear the dashboard widget transients cache.', 'dc-woocommerce-multi-vendor' ); ?></p>
                </label>
                <div class="col-md-3 col-sm-6">
                    <a class="wcmp_vendor_clear_transients btn btn-default" href="<?php echo esc_url( wp_nonce_url( add_query_arg( array( 'tools_action' => 'clear_all_transients' ), wcmp_get_vendor_dashboard_endpoint_url( get_wcmp_vendor_settings( 'wcmp_clear_cache_endpoint', 'vendor', 'general', 'vendor-tools' ) ) ), 'wcmp_clear_vendor_transients' ) ); ?>"><?php _e( 'Clear transients', 'dc-woocommerce-multi-vendor' ) ?></a>
                </div>
            </div>
            <?php do_action( 'wcmp_vendor_dashboard_tools_item' ); ?>
        </div>
    </div>
</div>
<?php
do_action( 'after_wcmp_vendor_tools_content' );