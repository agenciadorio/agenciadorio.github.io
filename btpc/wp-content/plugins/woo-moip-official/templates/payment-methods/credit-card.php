<?php
if ( ! function_exists( 'add_action' ) ) {
	exit(0);
}

use Woocommerce\Moip\Core;
use Woocommerce\Moip\Model\Customer;
use Woocommerce\Moip\Model\Setting;
use Woocommerce\Moip\View\Checkouts as Checkouts_View;

if ( ! $model->settings->is_active_credit_card() ) {
	return;
}

$brand            = strtolower( $customer->credit_card_brand );
$setting          = Setting::get_instance();
$min_installments = str_replace( ',', '.', $setting->installments_minimum );

?>

<li>
	<div id="tab-credit-card" class="payment_box panel entry-content">
		<fieldset class="wc-credit-card-form wc-payment-form">
			<div class="wc-moip-store-cc-content wc-moip-hide-field"
				 data-element="stored-cc-info">

	            <p class="form-row form-row-wide">
	                <label>
	                    <?php _e( 'Registered credit card', Core::TEXTDOMAIN ); ?>
	                </label>

					<input class="input-text wc-credit-card-form-card-number <?php echo $brand; ?>"
						   inputmode="numeric"
						   type="tel"
						   placeholder="•••• •••• •••• <?php echo $customer->credit_card_last_numbers; ?>">
	            </p>

	            <p class="form-row form-row-first">

	                <a href="javascript:void(0);"
	                   class="wc-moip-change-cc"
	                   data-type="new"
	                   data-action="change-cc">

	                	<?php _e( 'Use new credit card', Core::TEXTDOMAIN ); ?>
                	</a>

	            </p>
	        </div>

			<div class="wc-credit-card-info"
				 data-element="fields-cc-data">

				<p class="form-row form-row-wide">

					<label for="card-number"><?php _e( 'Card number', Core::TEXTDOMAIN ); ?> <span class="required">*</span></label>

					<input id="card-number" data-element="card-number"
						   class="input-text wc-credit-card-form-card-number"
						   inputmode="numeric"
						   type="tel"
						   placeholder="•••• •••• •••• ••••" name="card_number">
				</p>

				<p class="form-row form-row-first">

					<label for="card-expiry">
						<?php _e( 'Expiry (MM/YYYY)', Core::TEXTDOMAIN ); ?>
						<span class="required">*</span>
					</label>

					<input id="card-expiry" data-element="card-expiry"
						   class="input-text wc-credit-card-form-card-expiry"
						   inputmode="numeric"
						   type="tel"
						   placeholder="<?php _e( 'MM / YYYY', Core::TEXTDOMAIN ); ?>"
						   name="card_expiry">
				</p>

				<p class="form-row form-row-last">

					<label for="card-cvc">
						<?php _e( 'Card code', Core::TEXTDOMAIN ); ?> <span class="required">*</span>
					</label>

					<input id="card-cvc"
					       data-element="card-cvc"
						   class="input-text wc-credit-card-form-card-cvc"
						   inputmode="numeric"
						   type="tel"
						   maxlength="4"
						   placeholder="CVC"
						   name="card_cvc"
						   style="width:100px">
				</p>

	            <p class="form-row form-row-wide">

	                <a href="javascript:void(0);"
	                   class="wc-moip-change-cc wc-moip-hide-field"
	                   data-type="old"
	                   data-element="old-cc-info"
	                   data-action="change-cc">

	                	<?php _e( 'Use old credit card', Core::TEXTDOMAIN ); ?>
                	</a>

	            </p>
			</div>

			<p class="form-row form-row-first">

				<label for="installments">
					<?php _e( 'Installments quantity', Core::TEXTDOMAIN ); ?><span class="required">*</span>
				</label>


				<select id="installments"
				        data-action="select2"
					    data-element="installments"
					    name="installments">

					<option value="1">
						<?php echo __( 'At sight', Core::TEXTDOMAIN ) . ' ('. wc_price( $wc_order->get_total() ) . ')'; ?>
					</option>

					<?php Checkouts_View::render_installments( $wc_order->get_total() ); ?>

				</select>
			</p>

			<?php if ( $min_installments && $setting->is_active_installments() ) : ?>
				<p class="form-row form-row-wide">
					<label for="installments">
						<?php _e( 'Minimum installment R$' . $min_installments , Core::TEXTDOMAIN ); ?><span class="required">*</span>
					</label>
				</p>
			<?php endif; ?>

			<p class="form-row form-row-first">
				<label for="store-credit-card">

					<input type="checkbox"
					       id="store-credit-card"
					       name="store_credit_card"
					       value="1"
					       <?php checked( $customer->stored_credit_card, true ); ?>>

					<?php _e( 'Save this card for future purchases', Core::TEXTDOMAIN ); ?>
				</label>
			</p>			

			<textarea style="display: none;" data-element="public-key">
				<?php echo $public_key; ?>
			</textarea>

			<input style="display:none;"
			       data-element="credit-card"
			       type="radio"
			       name="payment_method"
			       value="payCreditCard">

			<div class="clear"></div>

			<input type="hidden" data-element="hash" name="hash">

		</fieldset>
	</div>
</li>
