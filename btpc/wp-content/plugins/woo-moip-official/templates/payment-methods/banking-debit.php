<?php
if ( ! function_exists( 'add_action' ) ) {
	exit(0);
}

use Woocommerce\Moip\Core;

if ( ! $model->settings->is_active_banking_debit() ) {
	return;
}

?>

<li>
	<div id="tab-banking-debit" class="panel entry-content">
		<ul>
			<li>
				<label>
					<?php
						printf( '<img style="max-height:40px; margin-top: -8px;" src="%1$s" alt="%2$s" title="%2$s" />',
							Core::plugins_url( 'assets/images/itau.png' ),
							'Itaú'
						);
					?>
					<input data-element="debit-itau"
					       type="radio"
					       name="payment_method"
					       value="payOnlineBankDebitItau">
				</label>
			</li>
		</ul>
	</div>
<li>
