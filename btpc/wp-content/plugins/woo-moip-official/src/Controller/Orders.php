<?php
namespace Woocommerce\Moip\Controller;

if ( ! function_exists( 'add_action' ) ) {
	exit( 0 );
}

use Woocommerce\Moip\Core;
use Woocommerce\Moip\Model\Customer;
use Woocommerce\Moip\Model\Setting;
use Woocommerce\Moip\Helper\Utils;
use Woocommerce\Moip\Model\Order;
use Woocommerce\Moip\View\Orders as Orders_View;


class Orders
{
    public function __construct()
    {
        add_action( 'woocommerce_view_order', array( $this, 'add_billet_page' ), 20 );
        add_filter('woocommerce_my_account_my_orders_actions', array( $this, 'billet_button_to_list' ), 10, 2 );
        add_action( 'woocommerce_admin_order_data_after_shipping_address', array( $this, 'display_order_data_in_admin' ) );
    }

    public function add_billet_page( $order_id )
    { 
        Utils::template_include( 'templates/orders', array(
            'order' => new Order( $order_id ),
        ) );
    }

    public function billet_button_to_list( $actions, $wc_order )
    { 
        $order = new Order( $wc_order->get_order_number() );

        if ( $order->payment_links->payBoleto->redirectHref ) {
            $actions['moip'] = array(
                'url'  => $order->payment_links->payBoleto->redirectHref . '/print',
                'name' => __( 'Billet Moip', Core::TEXTDOMAIN ),
            );            
        }

        return $actions;
    }

    public function display_order_data_in_admin( $order ) 
    {
        $order_id        = $order->get_order_number();
        $moip_pay_method = get_post_meta( $order_id, '_moip_payment_type', true );
        $purchase        = '<span style="color:red">' . __( 'Credit Card', Core::TEXTDOMAIN ) . '</span>';
    
        if ( $moip_pay_method == '' ) {
          return;
        }
    ?>
        </div></div>
        <div class="clear"></div>
        <div class="order_data_column_container">
            <div class="order_data_column_wide">
                <h4><?php _e( 'Moip Oficial' ); ?></h4>
    <?php

        if ( $moip_pay_method == 'payBoleto' ) {
            $purchase = '<span style="color:red">' . __( 'Bank Billet', Core::TEXTDOMAIN ) . '</span>';
        }

        echo '<p><strong>'. __( 'Payment by' ) . ':</strong> ' . $purchase . '</p>';
    }
}