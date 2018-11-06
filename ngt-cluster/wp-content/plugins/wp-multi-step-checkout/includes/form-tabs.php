<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$steps = apply_filters( 'wp-multi-step-checkout-timeline', array(
  'login' => array(
    'order' => 0,
    'label' => $t_login,
  ),
  'billing' => array(
    'order' => 1,
    'label' => $t_billing,
  ),
  'shipping' => array(
    'order' => 2,
    'label' => $t_shipping,
  ),
  'order' => array(
    'order' => 3,
    'label' => $t_order,
  ),
  'payment' => array(
    'order' => 4,
    'label' => $t_payment,
  ),
));

if ( !$show_shipping_step) unset($steps['shipping']);
if ( !$show_login_step) unset($steps['login']);

if ( $unite_billing_shipping ) {
    $steps['billing']['label'] = $t_billing . ' & ' . $t_shipping; 
    unset($steps['shipping']);
}
if ( $unite_order_payment) {
    $steps['order']['label'] = $t_order . ' & ' . $t_payment; 
    unset($steps['payment']);
}

?>

<!-- The steps tabs -->
<div class="wpmc-tabs-wrapper">
  <ul class="wpmc-tabs-list wpmc-<?php echo count($steps); ?>-tabs">
    <?php
    $i = 0;
    foreach( $steps as $_id => $_step ) :
      $class = ( $i == 0 ) ? ' current' : '';
      ?>
              <li class="wpmc-tab-item<?php echo $class; ?> wpmc-<?php echo $_id; ?>">
							<div class="wpmc-tab-number"><?php echo $i = $i + 1; ?></div>
							<div class="wpmc-tab-text"><?php echo $_step['label']; ?></div>
				</li>
      <?php endforeach; ?>
		</ul>
</div>
