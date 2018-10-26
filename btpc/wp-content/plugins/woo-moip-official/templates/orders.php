<?php
if ( ! function_exists( 'add_action' ) ) {
	exit( 0 );
}

use Woocommerce\Moip\Core;
use Woocommerce\Moip\Model\Customer;
use Woocommerce\Moip\Model\Setting;
use Woocommerce\Moip\Helper\Utils;
use Woocommerce\Moip\Model\Order;

if ( $order->payment_type === 'payBoleto' ) { ?>
	<section class="woocommerce-order-moip">
		<h2>MOIP</h2>
		<table class="woocommerce-table shop_table gift_info">
		    <tbody>
		    	<tr>
		            <p><?php echo __( 'If you have not yet received the billet, please click the button below to print.', Core::TEXTDOMAIN ); ?></p>	    		
		    	</tr>
		        <tr>
		            <p><a href="<?php echo esc_url( $order->payment_links->payBoleto->redirectHref ); ?>/print" class="button alt" title="Imprimir" target="_blank">
		            	<?php echo __( 'Print', Core::TEXTDOMAIN ); ?></a></p>
		        </tr>
		    </tbody>
		</table>
	</section>
<?php }