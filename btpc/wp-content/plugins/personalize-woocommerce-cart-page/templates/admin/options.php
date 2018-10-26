<?php

$meat_cart = array(
					'before-cart-table'	=> array(	'label'		=> __('Before cart table', 'nm-woostore'),
					 							'desc'		=> __('It will show your text before cart table on cart page', 'nm-woostore'),
					 							'id'			=> 'nm_woostore_'.'beforecarttable',
					 							'type'			=> 'textarea',
					 							'default'		=> '',
					 							'help'			=> __('You can use HTML tags', 'nm-woostore')
					 							),

					'before-cart-contents'	=> array(	'label'		=> __('Before cart contents', 'nm-woostore'),
					 							'desc'		=> __('It will show your text before cart contents on cart page', 'nm-woostore'),
					 							'id'			=> 'nm_woostore_'.'beforecartcontents',
					 							'type'			=> 'textarea',
					 							'default'		=> '&lt;tr&gt;&lt;td&gt;Text here&lt;/td&gt;&lt;/tr&gt;',
					 							'help'			=> __('Do not remove &lt;tr&gt;&lt;td&gt; tags, add more td for columns', 'nm-woostore')
					 							),

					'cart-contents'	=> array(	'label'		=> __('After products', 'nm-woostore'),
					 							'desc'		=> __('It will show your text before start of cart main content on cart page', 'nm-woostore'),
					 							'id'			=> 'nm_woostore_'.'cartcontents',
					 							'type'			=> 'textarea',
					 							'default'		=> '&lt;tr&gt;&lt;td&gt;Text here&lt;/td&gt;&lt;/tr&gt;',
					 							'help'			=> __('Do not remove &lt;tr&gt;&lt;td&gt; tags, add more td for columns', 'nm-woostore')
					 							),
					
					'after-cart-contents'	=> array(	'label'		=> __('After cart contents', 'nm-woostore'),
					 							'desc'		=> __('It will show your text after start of cart main content on cart page', 'nm-woostore'),
					 							'id'			=> 'nm_woostore_'.'aftercartcontents',
					 							'type'			=> 'textarea',
					 							'default'		=> '&lt;tr&gt;&lt;td&gt;Text here&lt;/td&gt;&lt;/tr&gt;',
					 							'help'			=> __('Do not remove &lt;tr&gt;&lt;td&gt; tags, add more td for columns', 'nm-woostore')
					 							),

					'after-cart-table'	=> array(	'label'		=> __('After cart table', 'nm-woostore'),
					 							'desc'		=> __('It will show your text after cart table on cart page', 'nm-woostore'),
					 							'id'			=> 'nm_woostore_'.'aftercarttable',
					 							'type'			=> 'textarea',
					 							'default'		=> '',
					 							'help'			=> __('You can use HTML tags', 'nm-woostore')
					 							),

					'after-cart'	=> array(	'label'		=> __('At the bottom of page', 'nm-woostore'),
					 							'desc'		=> __('It will show your text at the bottom on cart page', 'nm-woostore'),
					 							'id'			=> 'nm_woostore_'.'aftercart',
					 							'type'			=> 'textarea',
					 							'default'		=> '',
					 							'help'			=> __('You can use HTML tags', 'nm-woostore')
					 							),

					'proceed-to-checkout'	=> array(	'label'		=> __('After proceed to checkout button', 'nm-woostore'),
					 							'desc'		=> __('It will show your text after proceed to checkout button on cart page', 'nm-woostore'),
					 							'id'			=> 'nm_woostore_'.'proceedtocheckout',
					 							'type'			=> 'textarea',
					 							'default'		=> '',
					 							'help'			=> __('You can use HTML tags', 'nm-woostore')
					 							),

					'cart-coupon'	=> array(	'label'		=> __('After coupon button', 'nm-woostore'),
					 							'desc'		=> __('It will show your text after coupon button on cart page', 'nm-woostore'),
					 							'id'			=> 'nm_woostore_'.'cartcoupon',
					 							'type'			=> 'textarea',
					 							'default'		=> '',
					 							'help'			=> __('You can use HTML tags', 'nm-woostore')
					 							),

					'before-cart-totals'	=> array(	'label'		=> __('Before cart totals', 'nm-woostore'),
					 							'desc'		=> __('It will show your text before cart totals on cart page', 'nm-woostore'),
					 							'id'			=> 'nm_woostore_'.'beforecarttotals',
					 							'type'			=> 'textarea',
					 							'default'		=> '',
					 							'help'			=> __('You can use HTML tags', 'nm-woostore')
					 							),

					'after-cart-totals'	=> array(	'label'		=> __('After cart totals', 'nm-woostore'),
					 							'desc'		=> __('It will show your text after cart totals on cart page', 'nm-woostore'),
					 							'id'			=> 'nm_woostore_'.'aftercarttotals',
					 							'type'			=> 'textarea',
					 							'default'		=> '',
					 							'help'			=> __('You can use HTML tags', 'nm-woostore')
					 							),

					'cart-is-empty'	=> array(	'label'		=> __('When cart empty', 'nm-woostore'),
					 							'desc'		=> __('It will show your text when cart is empty', 'nm-woostore'),
					 							'id'			=> 'nm_woostore_'.'cartisempty',
					 							'type'			=> 'textarea',
					 							'default'		=> '',
					 							'help'			=> __('You can use HTML tags', 'nm-woostore')
					 							),

					'before-mini-cart'	=> array(	'label'		=> __('Before mini cart', 'nm-woostore'),
					 							'desc'		=> __('It will show your text before mini cart', 'nm-woostore'),
					 							'id'			=> 'nm_woostore_'.'beforeminicart',
					 							'type'			=> 'textarea',
					 							'default'		=> '',
					 							'help'			=> __('You can use HTML tags', 'nm-woostore')
					 							),

					'widget-shopping-cart-before-buttons'	=> array(	'label'		=> __('Widget cart before buttons', 'nm-woostore'),
					 							'desc'		=> __('It will show your text before button of widget shoping cart', 'nm-woostore'),
					 							'id'			=> 'nm_woostore_'.'widgetshoppingcartbeforebuttons',
					 							'type'			=> 'textarea',
					 							'default'		=> '',
					 							'help'			=> __('You can use HTML tags', 'nm-woostore')
					 							),

					'after-mini-cart'	=> array(	'label'		=> __('After mini cart', 'nm-woostore'),
					 							'desc'		=> __('It will show your text after mini cart', 'nm-woostore'),
					 							'id'			=> 'nm_woostore_'.'afterminicart',
					 							'type'			=> 'textarea',
					 							'default'		=> '',
					 							'help'			=> __('You can use HTML tags', 'nm-woostore')
					 							),

					'cart-totals-before-shipping'	=> array(	'label'		=> __('Cart totals before shipping', 'nm-woostore'),
					 							'desc'		=> __('It will show your text before shipping cart totals', 'nm-woostore'),
					 							'id'			=> 'nm_woostore_'.'carttotalsbeforeshipping',
					 							'type'			=> 'textarea',
					 							'default'		=> '&lt;tr&gt;&lt;td&gt;Text here&lt;/td&gt;&lt;/tr&gt;',
					 							'help'			=> __('Do not remove &lt;tr&gt;&lt;td&gt; tags, add more td for columns', 'nm-woostore')
					 							),

					'cart-totals-after-shipping'	=> array(	'label'		=> __('Cart totals after shipping', 'nm-woostore'),
					 							'desc'		=> __('It will show your text after shipping cart totals', 'nm-woostore'),
					 							'id'			=> 'nm_woostore_'.'carttotalsaftershipping',
					 							'type'			=> 'textarea',
					 							'default'		=> '&lt;tr&gt;&lt;td&gt;Text here&lt;/td&gt;&lt;/tr&gt;',
					 							'help'			=> __('Do not remove &lt;tr&gt;&lt;td&gt; tags, add more td for columns', 'nm-woostore')
					 							),

					'cart-totals-before-order-total'	=> array(	'label'		=> __('Before order total', 'nm-woostore'),
					 							'desc'		=> __('It will show your text before order totals', 'nm-woostore'),
					 							'id'			=> 'nm_woostore_'.'carttotalsbeforeordertotal',
					 							'type'			=> 'textarea',
					 							'default'		=> '&lt;tr&gt;&lt;td&gt;Text here&lt;/td&gt;&lt;/tr&gt;',
					 							'help'			=> __('Do not remove &lt;tr&gt;&lt;td&gt; tags, add more td for columns', 'nm-woostore')
					 							),
					
					'cart-totals-after-order-total'	=> array(	'label'		=> __('After order total', 'nm-woostore'),
					 							'desc'		=> __('It will show your text after order totals', 'nm-woostore'),
					 							'id'			=> 'nm_woostore_'.'carttotalsafterordertotal',
					 							'type'			=> 'textarea',
					 							'default'		=> '&lt;tr&gt;&lt;td&gt;Text here&lt;/td&gt;&lt;/tr&gt;',
					 							'help'			=> __('Do not remove &lt;tr&gt;&lt;td&gt; tags, add more td for columns', 'nm-woostore')
					 							),
					
					'before-shipping-calculator'	=> array(	'label'		=> __('Before shipping calculator', 'nm-woostore'),
					 							'desc'		=> __('It will show your text before shipping calculator', 'nm-woostore'),
					 							'id'			=> 'nm_woostore_'.'beforeshippingcalculator',
					 							'type'			=> 'textarea',
					 							'default'		=> '',
					 							'help'			=> __('You can use HTML tags', 'nm-woostore')
					 							),
					
					'after-shipping-calculator'	=> array(	'label'		=> __('After shipping calculator', 'nm-woostore'),
					 							'desc'		=> __('It will show your text after shipping calculator', 'nm-woostore'),
					 							'id'			=> 'nm_woostore_'.'aftershippingcalculator',
					 							'type'			=> 'textarea',
					 							'default'		=> '',
					 							'help'			=> __('You can use HTML tags', 'nm-woostore')
					 							),
					
					);

$meat_labels = array(
										'add-to-cart-text-simple'	=> array(	'label'		=> __('Add to cart', 'nm-woostore'),
					 							'desc'		=> __('It will replace default button label for single products in loop', 'nm-woostore'),
					 							'id'			=> 'nm_woostore_'.'addtocartsimple',
					 							'type'			=> 'textarea',
					 							'default'		=> '',
					 							'help'			=> __('', 'nm-woostore')
					 							),

										'add-to-cart-text-single'	=> array(	'label'		=> __('Single product add to cart', 'nm-woostore'),
					 							'desc'		=> __('It will replace default add to cart button label on single product page', 'nm-woostore'),
					 							'id'			=> 'nm_woostore_'.'addtocartsingle',
					 							'type'			=> 'textarea',
					 							'default'		=> '',
					 							'help'			=> __('', 'nm-woostore')
					 							),

										'add-to-cart-text-grouped'	=> array(	'label'		=> __('View products', 'nm-woostore'),
					 							'desc'		=> __('It will replace default button label for grouped products in loop', 'nm-woostore'),
					 							'id'			=> 'nm_woostore_'.'addtocartgrouped',
					 							'type'			=> 'textarea',
					 							'default'		=> '',
					 							'help'			=> __('', 'nm-woostore')
					 							),

										'add-to-cart-text-variable'	=> array(	'label'		=> __('Select options', 'nm-woostore'),
					 							'desc'		=> __('It will replace default button label for variable products in loop', 'nm-woostore'),
					 							'id'			=> 'nm_woostore_'.'addtocartvariable',
					 							'type'			=> 'textarea',
					 							'default'		=> '',
					 							'help'			=> __('', 'nm-woostore')
					 							),

										'add-to-cart-text-external'	=> array(	'label'		=> __('Buy product', 'nm-woostore'),
					 							'desc'		=> __('It will replace default button label for external products in loop', 'nm-woostore'),
					 							'id'			=> 'nm_woostore_'.'addtocartexternal',
					 							'type'			=> 'textarea',
					 							'default'		=> '',
					 							'help'			=> __('', 'nm-woostore')
					 							),

										'add-to-cart-text-out'	=> array(	'label'		=> __('Single product out of stock', 'nm-woostore'),
					 							'desc'		=> __('It will replace default out of stock text on single product page', 'nm-woostore'),
					 							'id'			=> 'nm_woostore_'.'addtocartout',
					 							'type'			=> 'textarea',
					 							'default'		=> '',
					 							'help'			=> __('', 'nm-woostore')
					 							),

										'order-button-text'	=> array(	'label'		=> __('Order button text', 'nm-woostore'),
					 							'desc'		=> __('It will replace default order button label on checkout page', 'nm-woostore'),
					 							'id'			=> 'nm_woostore_'.'orderbuttontext',
					 							'type'			=> 'textarea',
					 							'default'		=> '',
					 							'help'			=> __('', 'nm-woostore')
					 							),
					
					);
					

$meat_pro_features = array('file-meta'	=> array(	
									'desc'		=> '',
									'type'		=> 'file',
									'id'		=> 'get-pro.php',
									),
								);
								
$meat_ppom_features = array('file-meta'	=> array(	
									'desc'		=> '',
									'type'		=> 'file',
									'id'		=> 'get-ppom.php',
									),
								);



$this -> the_options = array('label-page'	=> array(	'name'		=> __('Button Labels', 'nm-woostore'),
														'type'	=> 'tab',
														'desc'	=> __('Here you can customize all buttons labels. <a href="http://www.najeebmedia.com/personalized-woostore-guide/#buttons" target="_blank">How it works!</a>', 'nm-woostore'),
														'meat'	=> $meat_labels,
													),

							'cart-page'	=> array(	'name'		=> __('Cart Page', 'nm-woostore'),
														'type'	=> 'tab',
														'desc'	=> __('Here you can customize cart page areas. <a href="http://www.najeebmedia.com/personalized-woostore-guide/#cart" target="_blank">How it works!</a>', 'nm-woostore'),
														'meat'	=> $meat_cart,
													),
													
							'get-pro'		=> array(	'name'		=> __('Pro Features', 'nm-woostore'),
															'type'	=> 'tab',
															'desc'	=> __('Get PRO version and enjoy following features', 'nm-woostore'),
															'meat'	=> $meat_pro_features,
														
														),
							'get-ppom'		=> array(	'name'		=> __('Product Addon', 'nm-woostore'),
															'type'	=> 'tab',
															'desc'	=> __('WooCommerce Product Addon', 'nm-woostore'),
															'meat'	=> $meat_ppom_features,
														
														),
	
							);

//print_r($repo_options);