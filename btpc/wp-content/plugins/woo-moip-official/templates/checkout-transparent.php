<?php
if ( ! function_exists( 'add_action' ) ) {
  exit( 0 );
}

use Woocommerce\Moip\Core;
use Woocommerce\Moip\Helper\Utils;
use Woocommerce\Moip\Model\Customer;
use Woocommerce\Moip\View\Checkouts as Checkouts_View;

$wc_order     = new WC_Order();
$customer     = new Customer( get_current_user_id() );

$pay_value      = 'payCreditCard';
$card_display   = 'display: block;';
$billet_display = 'display: none;';
$pay_active     = '';

if ( $model->settings->is_active_credit_card() === false ) {
    $pay_value      = 'payBoleto';
    $pay_active     = 'active';
    $billet_display = 'display: block;';
    $card_display   = 'display: none;';
}

?>

<div class="woo-moip-official" data-moip-container>
<div <?php echo Utils::get_component( 'checkout-transparent' ); ?>
  id="wc-moip-payment-checkout-form"
  data-encrypt="1"
  data-store-credit-card="<?php echo $customer->stored_credit_card ? 1 : 0; ?>">

  <div class="product">

    <div class="woocommerce-tabs">

    <input 
      id="moip-payment-method-field" 
      data-element="moip-payment-method"
      type="hidden"
      name="moip_fields[payment_method]"
      value="<?php echo $pay_value; ?>">

    <input type="hidden" name="encrypt" value="1">

        <div class="tab">

            <?php if ( $model->settings->is_active_credit_card() ) : ?>
                <span class="credit-card tablinks active" id="tabMoipCreditCard">
                    <label class="moip-tab">
                        <?php _e( 'Credit Card', Core::TEXTDOMAIN ); ?>
                    </label>
                </span>
            <?php endif; ?>

            <?php if ( $model->settings->is_active_billet_banking() ) : ?>
                <span class="billet tablinks <?php echo $pay_active; ?>" id="tabMoipBillet">
                    <label class="moip-tab">
                        <?php _e( 'Billet Banking', Core::TEXTDOMAIN ); ?>
                    </label>
                </span>
            <?php endif; ?>
                  
        </div>

        <div id="moip-payment-method-credit-card" class="tabcontent" style="<?php echo $card_display; ?>">
            <div class="table-items">
                <?php 
                    Utils::template_include(
                        'templates/payment-methods/transparent-credit-card',
                        compact( 'public_key', 'customer', 'wc_order', 'model', 'cart_total', 'cart_subtotal' )
                    );
                ?>
            </div>
        </div>

        <div id="moip-payment-method-billet" class="tabcontent" style="<?php echo $billet_display; ?>">
            <div class="table-items">
                <?php 
                    Utils::template_include(
                        'templates/payment-methods/transparent-billet',
                        compact( 'public_key', 'customer', 'wc_order', 'model', 'cart_total', 'cart_subtotal' )
                    );
                ?>
            </div>
        </div>

    </div>
    

  </div>
</div>
</div>