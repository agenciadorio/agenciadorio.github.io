<?php

/*
 * The template for displaying vendor pending shipping table dashboard widget
 * Override this template by copying it to yourtheme/dc-product-vendor/vendor-dashboard/dashboard-widgets/wcmp_vendor_pending_shipping.php
 *
 * @author 	WC Marketplace
 * @package 	WCMp/Templates
 * @version   3.0.0
 */
if (!defined('ABSPATH')) {
    // Exit if accessed directly
    exit;
}
global $WCMp;
$vendor = get_current_vendor();
do_action('before_wcmp_vendor_pending_shipping');
?>
<table class="table table-bordered <?php echo $pending_shippings ? 'responsive-table' : 'blank-responsive-table'; ?>">
<?php if($default_headers){ ?>
    <thead>
        <tr>
            <?php 
                foreach ($default_headers as $key => $value) {
                    echo '<th>'.$value.'</th>';
                }
            ?>
        </tr>
    </thead>
    <tbody>
    <?php 
    if($pending_shippings){
        foreach ($pending_shippings as $pending_orders_item) { 
            try {
                echo '<tr>';
                $order = wc_get_order($pending_orders_item->order_id);
                $pending_shipping_products = get_wcmp_vendor_orders(array('vendor_id' => $vendor->id, 'order_id' => $order->get_id(), 'shipping_status' => 0, 'is_trashed' => ''));
                $pending_shipping_amount = get_wcmp_vendor_order_amount(array('vendor_id' => $vendor->id, 'order_id' => $order->get_id(), 'shipping_status' => 0));
                $product_sku = array();
                $product_name = array();
                //$product_dimention = array();
                foreach ($pending_shipping_products as $pending_shipping_product) {
                    $product = wc_get_product($pending_shipping_product->product_id);
                    if ($product && $product->needs_shipping()) {
                        $product_sku[] = $product->get_sku() ? $product->get_sku() : '<span class="na">&ndash;</span>';
                        $product_name[] = $product->get_title();
                        if ($pending_shipping_product->variation_id != 0) {
                            $product = wc_get_product($pending_shipping_product->variation_id);
                        }
                    }
                }
                if(empty($product_name))                                
                    continue;  
                
                $action_html = '';
                if ($vendor->is_shipping_enable()) {
                    $is_shipped = (array) get_post_meta($order->get_id(), 'dc_pv_shipped', true);
                    if (!in_array($vendor->id, $is_shipped)) {
                        $action_html .= '<a href="javascript:void(0)" title="' . __('Mark as shipped', 'dc-woocommerce-multi-vendor') . '" onclick="wcmpMarkeAsShip(this,' . $order->get_id() . ')"><i class="wcmp-font ico-shippingnew-icon action-icon"></i></a> ';
                    } else {
                        $action_html .= '<i title="' . __('Shipped', 'dc-woocommerce-multi-vendor') . '" class="wcmp-font ico-shipping-icon"></i> ';
                    }
                }
                $action_html = apply_filters('wcmp_dashboard_pending_shipping_widget_data_actions', $action_html, $order->get_id());
                foreach ($default_headers as $key => $value) {
                    echo '<td><span class="'.$key.'">';
                    switch ($key) {
                        case 'order_id': 
                            echo '<a href="'.esc_url(wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_vendor_orders_endpoint', 'vendor', 'general', 'vendor-orders'), $order->get_id())).'">#'.$order->get_id().'</a>';
                            break;
                        case 'products_name': 
                            echo implode(' , ', $product_name);
                            break;
                        case 'order_date': 
                            echo wcmp_date($order->get_date_created());
                            break;
                        case 'shipping_address': 
                            echo $order->get_formatted_shipping_address();
                            break;
                        case 'shipping_amount': 
                            echo wc_price($pending_shipping_amount['shipping_amount']);
                            break;
                        case 'action':
                            echo $action_html;
                            break;
                    }
                    do_action('wcmp_vendor_pending_shipping_table_row', $key, $pending_orders_item, $order);
                    do_action('wcmp_vendor_pending_shipping_table_row_column_data', $key, $pending_orders_item, $order);
                    echo '</span></td>';
                }
                echo '</tr>';

            } catch (Exception $ex) {

            }
        }
    }else{
        echo '<tr><td colspan="'.count($default_headers).'" align="center"><span>'.__('You have no pending shipping!', 'dc-woocommerce-multi-vendor').'</span></td></tr>';
    }
    ?>
    </tbody>
<?php } ?>
</table>
 <!-- Modal -->
<div id="marke-as-ship-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <form method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?php _e('Shipment Tracking Details', 'dc-woocommerce-multi-vendor'); ?></h4>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="tracking_url"><?php _e('Enter Tracking Url', 'dc-woocommerce-multi-vendor'); ?> *</label>
                        <input type="url" class="form-control" id="email" name="tracking_url" required="">
                    </div>
                    <div class="form-group">
                        <label for="tracking_id"><?php _e('Enter Tracking ID', 'dc-woocommerce-multi-vendor'); ?> *</label>
                        <input type="text" class="form-control" id="pwd" name="tracking_id" required="">
                    </div>
                </div>
                <input type="hidden" name="order_id" id="wcmp-marke-ship-order-id" />
                <?php if (isset($_POST['wcmp_start_date_order'])) : ?>
                    <input type="hidden" name="wcmp_start_date_order" value="<?php echo $_POST['wcmp_start_date_order']; ?>" />
                <?php endif; ?>
                <?php if (isset($_POST['wcmp_end_date_order'])) : ?>
                    <input type="hidden" name="wcmp_end_date_order" value="<?php echo $_POST['wcmp_end_date_order']; ?>" />
                <?php endif; ?>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" name="wcmp-submit-mark-as-ship"><?php _e('Submit', 'dc-woocommerce-multi-vendor'); ?></button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    function wcmpMarkeAsShip(self, order_id) {
        jQuery('#wcmp-marke-ship-order-id').val(order_id);
        jQuery('#marke-as-ship-modal').modal('show');
    }
</script>
<?php 
do_action('after_wcmp_vendor_pending_shipping');