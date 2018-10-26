<?php
namespace Woocommerce\Moip\View;

if ( ! function_exists( 'add_action' ) ) {
	exit( 0 );
}

// Native exception
use Exception;

use Woocommerce\Moip\Core;
use Woocommerce\Moip\Model\Setting;
use Woocommerce\Moip\Model\Webhook;
use Woocommerce\Moip\Model\Moip_SDK;
use Woocommerce\Moip\Helper\Utils;
use Woocommerce\Moip\Model\Custom_Gateway;

class Custom_Gateways 
{
	public static function render_notification_webhook( $settings )
	{
		$link             = Core::get_webhook_url();
		$notification_url = 'https://api.moip.com.br/v2/preferences/notifications';

		if ( $settings->authorize_mode == 'sandbox' ) {
			$notification_url = 'https://sandbox.moip.com.br/v2/preferences/notifications';
		}

		ob_start();

		?>
		<table class='form-table webhooks'>
			<tr>
		 		<th>Access Token:</th>
		 		<td class='forminp'>
		 			<?php echo $settings->authorize_data->accessToken; ?>
		 		</td>
		 	</tr>
	  	 	<tr>
	  	 		<th>URL:</th>
	  	 		<td class='forminp'>
	  	 			<?php echo $link; ?>
	  	 		</td>
	  	 	</tr>
	  	 	<tr>
	  	 		<th>URL de Notificação:</th>
	  	 		<td class='forminp'>
	  	 			<?php echo $notification_url; ?>
	  	 		</td>
	  	 	</tr>
		</table>
		<?php

		return ob_get_clean();
	}

	public static function render_button_webhook_send( $settings )
	{
		$moip_sdk = Moip_SDK::get_instance();

		ob_start();
		?>
		<table class='form-table'>
			<tr valign="top">
		 		<th><?php echo __( 'Status', Core::TEXTDOMAIN ); ?></th>
		 		<td class='forminp'>
		 			<form method="post">
		 				<button class="webhook button" name="moip_send_webhook"><?php echo __( 'Send Notifications', Core::TEXTDOMAIN ); ?></button>
		 			</form>
		 			<p class="description "><?php echo __( 'To solve the Woocommerce/Moip status problem.', Core::TEXTDOMAIN ); ?></p>
		 		</td>
		 	</tr>
		</table>

	<?php

		if ( isset( $_POST['moip_send_webhook'] ) ) {
			try {
				$webhook  = new Webhook( $moip_sdk->moip );
				$response = $webhook->create();

				unset( $webhook );

			} catch( Exception $e ) {
				$custom_gateway->add_error( __( 'Notifications not sent.', Core::TEXTDOMAIN ) );
			}
		}
		return ob_get_clean();
	}
}