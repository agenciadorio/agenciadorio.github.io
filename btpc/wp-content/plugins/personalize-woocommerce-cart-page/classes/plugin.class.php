<?php
/*
 * this is main plugin class
*/


/* ======= the model main class =========== */
if(!class_exists('NM_Framwork_V1_woostore')){
	$_framework = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'nm-framework.php';
	if( file_exists($_framework))
		include_once($_framework);
	else
		die('Reen, Reen, BUMP! not found '.$_framework);
}


/*
 * [1]
 * TODO: change the class name of your plugin
 */
class NM_PLUGIN_WooStore extends NM_Framwork_V1_woostore{

	static $tbl_list = 'nm_lists';

	var $actions, $filters, $product_type;
	/*
	 * plugin constructur
	 */
	function __construct(){
		
		//setting plugin meta saved in config.php
		$this -> plugin_meta = get_plugin_meta_woostore();

		//getting saved settings
		$this -> plugin_settings = get_option($this->plugin_meta['shortname'].'_settings');



		/*
		 * [2]
		 * TODO: update scripts array for SHIPPED scripts
		 * only use handlers
		 */
		//setting shipped scripts
		$this -> wp_shipped_scripts = array('jquery');
		
		
		/*
		 * [3]
		* TODO: update scripts array for custom scripts/styles
		*/
		//setting plugin settings
		$this -> plugin_scripts =  array(array(	'script_name'	=> 'scripts',
												'script_source'	=> '/js/script.js',
												'localized'		=> true,
												'type'			=> 'js'
										),
												array(	'script_name'	=> 'styles',
														'script_source'	=> '/plugin.styles.css',
														'localized'		=> false,
														'type'			=> 'style'
												),
										);
		
		/*
		 * [4]
		* TODO: localized array that will be used in JS files
		* Localized object will always be your pluginshortname_vars
		* e.g: pluginshortname_vars.ajaxurl
		*/
		$this -> localized_vars = array('ajaxurl' => admin_url( 'admin-ajax.php' ),
				'plugin_url' 		=> $this->plugin_meta['url'],
				'settings'			=> $this -> plugin_settings);
		
		
		/*
		 * [5]
		 * TODO: this array will grow as plugin grow
		 * all functions which need to be called back MUST be in this array
		 * setting callbacks
		 */
		//following array are functions name and ajax callback handlers
		$this -> ajax_callbacks = array('save_settings',		//do not change this action, is for admin
										'save_file',
										'download_file');
		
		/*
		 * plugin localization being initiated here
		 */
		add_action('init', array($this, 'wpp_textdomain'));
		
		
		/**
		* Lets add the woocommerce relation hooks
		* actions
		* filters
		* =============== woocommerce action hooks =============================
		*/

		$this -> the_actions = array('woocommerce_before_cart_table'		=> '_beforecarttable',
								'woocommerce_before_cart_contents'	=> '_beforecartcontents',
								'woocommerce_cart_contents'	=> '_cartcontents',
								'woocommerce_after_cart_contents'	=> '_aftercartcontents',
								'woocommerce_after_cart_table'	=> '_aftercarttable',
								'woocommerce_after_cart'	=> '_aftercart',
								'woocommerce_proceed_to_checkout'	=> '_proceedtocheckout',
								'woocommerce_cart_coupon'	=> '_cartcoupon',
								'woocommerce_after_cart_totals'	=> '_aftercarttotals',
								'woocommerce_before_cart_totals'	=> '_beforecart_totals',
								'woocommerce_cart_is_empty'	=> '_cartis_empty',
								'woocommerce_before_mini_cart'	=> '_beforeminicart',
								'woocommerce_widget_shopping_cart_before_buttons'	=> '_widgetshoppingcartbeforebuttons',
								'woocommerce_after_mini_cart'	=> '_afterminicart',
								'woocommerce_cart_totals_before_shipping'	=> '_carttotalsbeforeshipping',
								'woocommerce_cart_totals_after_shipping'	=> '_carttotalsaftershipping',
								'woocommerce_cart_totals_before_order_total'	=> '_carttotalsbeforeordertotal',
								'woocommerce_cart_totals_after_order_total'	=> '_carttotalsafterordertotal',
								'woocommerce_before_shipping_calculator'	=> '_beforeshippingcalculator',
								'woocommerce_after_shipping_calculator'	=> '_aftershippingcalculator',
								);

		foreach ($this -> the_actions as $filter => $key) {
			add_action($filter, array($this, 'render_text'));
		}


		/* =============== woocommerce filter hooks ============================= */

		$this -> filters =  array(	'woocommerce_product_add_to_cart_text'			=> array(	'simple'	=> $this -> get_option('_addtocartsimple'),
																								'variable'	=> $this -> get_option('_addtocartvariable'),
																								'grouped'	=> $this -> get_option('_addtocartgrouped'),
																								'external'	=> $this -> get_option('_addtocartexternal'),
																								),

									'woocommerce_product_single_add_to_cart_text'	=> $this -> get_option('_addtocartsingle'),
									'woocommerce_get_availability'					=> $this -> get_option('_addtocartout'),
									'woocommerce_order_button_text'					=> $this -> get_option('_orderbuttontext'),
									);

		

		foreach ($this -> filters as $filter => $label) {
			
			switch ($filter) {
				case 'woocommerce_product_add_to_cart_text':
					add_filter($filter, array($this, 'loop_product_add_to_cart_text'), 10, 2);
					break;

				case 'woocommerce_product_single_add_to_cart_text':
					if($label != '')
						add_filter($filter, array($this, 'product_single_add_to_cart_text'));
					break;

				case 'woocommerce_get_availability':
					if($label != '')
						add_filter($filter, array($this, 'check_if_out_of_stock'), 10, 2);
					break;
				
				case 'woocommerce_order_button_text':
					if($label != '')
						add_filter($filter, array($this, 'order_button_text'));
					break;
			}
			
		}
		

		/* =============== woocommerce hooks ============================= */
		
		
		/*
		 * hooking up scripts for front-end
		*/
		add_action('wp_enqueue_scripts', array($this, 'load_scripts'));
		
		/*
		 * registering callbacks
		*/
		$this -> do_callbacks();
	}
	
	
	
	/**
	 * =============== Woocommerce hooks callbacks ===========================
	 */

	function render_text(){


		$current_filter = current_filter();
		$the_option_key = $this -> the_actions[$current_filter];
		$message = $this -> get_option( $the_option_key );

		if($message){
			printf( __("%s", 'nm-woostore'), $message);
		}
	}

	/**
	 * =============== Woocommerce filters callbacks ===========================
	 */


	/**
	* to render labels on loop
	*/

	function loop_product_add_to_cart_text($text, $product) {

		
		$product_type = $product->get_type();

		if($this -> filters['woocommerce_product_add_to_cart_text'][$product_type] != '')
			return sprintf( __("%s", 'nm-woostore'), $this -> filters['woocommerce_product_add_to_cart_text'][$product_type]);
		else
			return $text;
	}

	/*
	 * render label on single product page
	 */

	function product_single_add_to_cart_text(){

		return sprintf( __("%s", 'nm-woostore'), $this -> filters['woocommerce_product_single_add_to_cart_text']);	
	}


	/*
	 * render label on single product page when out of stock
	 */


	function check_if_out_of_stock($availability, $_product) {
  
	    if ( !$_product->is_in_stock() ) {

	    	$availability['availability'] = sprintf( __("%s", 'nm-woostore'), $this -> filters['woocommerce_get_availability']);	        					
	    	return $availability;
	    	
	    }
	}

	
	/*
	 * render label on place order button on checkout page
	 */


	function order_button_text(){

		return sprintf( __("%s", 'nm-woostore'), $this -> filters['woocommerce_order_button_text']);
	}
	
	
	/*
	 * saving admin setting in wp option data table
	 */
	function save_settings(){
	
		// print_r($_REQUEST);
		
		update_option($this->plugin_meta['shortname'].'_settings', $_REQUEST);
		_e('All options are updated', $this->plugin_meta['shortname']);
		die(0);
	}


	



	// ================================ SOME HELPER FUNCTIONS =========================================

	
	
	function activate_plugin(){

		//do nothing
	}

	function deactivate_plugin(){

		//do nothing so far.
	}
}