<?php
if ( ! function_exists( 'add_action' ) ) {
	exit(0);
}

use Woocommerce\Moip\Core;

if ( ! $model->settings->is_active_billet_banking() ) {
	return;
}

?>

<li>
	<div id="tab-billet" class="panel entry-content">
		<ul>
			<li>
				<label>
					<?php
						printf( '<img src="%1$s" alt="%2$s" title="%2$s" />',
							Core::plugins_url( 'assets/images/barcode.svg' ),
							__( 'Bank Billet', Core::TEXTDOMAIN )
						);
					?>
					<input data-element="boleto"
					       type="radio"
					       name="payment_method"
					       value="payBoleto">
				</label>
			</li>
		</ul>
	</div>
</li>
