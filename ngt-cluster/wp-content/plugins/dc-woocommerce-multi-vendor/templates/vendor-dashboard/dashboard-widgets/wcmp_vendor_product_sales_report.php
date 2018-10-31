<?php

/*
 * The template for displaying vendor pending shipping table dashboard widget
 * Override this template by copying it to yourtheme/dc-product-vendor/vendor-dashboard/dashboard-widgets/wcmp_vendor_product_sales_report.php
 *
 * @author 	WC Marketplace
 * @package 	WCMp/Templates
 * @version   3.0.0
 */
if (!defined('ABSPATH')) {
    // Exit if accessed directly
    exit;
}
?>
<table class="table table-striped product_sold_last_week table-bordered <?php echo $sold_product_list_sorted ? 'responsive-table' : 'blank-responsive-table'; ?>" id="product_sold_last_week_id">
    <thead>
        <tr>
            <th><?php _e('Product', 'dc-woocommerce-multi-vendor'); ?></th>
            <th><?php _e('Revenue', 'dc-woocommerce-multi-vendor'); ?></th>
            <th><?php _e('Unique Purchases', 'dc-woocommerce-multi-vendor'); ?></th>
        </tr>
    </thead>
    <tbody align="center"><?php
if ($sold_product_list_sorted) {
    foreach ($sold_product_list_sorted as $key => $value) {
        echo "<tr>";
        if ($value['exists'] == '0') {
            echo "<td><span>" . $value['name'] . " (" . __('This product does not exists', 'dc-woocommerce-multi-vendor') . ")</span></td>";
        } else {
            // echo "<td>" . $value['image'] . "</td>";
            echo "<td class='product_sold_last_week_name_class'><span><a href='" . $value['permalink'] . "'>" . $value['image'] . " " . wp_trim_words( $value['name'], 60, '...' ) . "</a></span></td>";
        }
        echo "<td><span>". wc_price($value['price']*$value['qty']) ."</span></td>";
        echo "<td><span>" . $value['qty'] . "</span></td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='3'><p class='wcmp_no-data'>" . __('Not enough data.', 'dc-woocommerce-multi-vendor') . "</p></td></tr>";
}
?>
    </tbody>
</table>