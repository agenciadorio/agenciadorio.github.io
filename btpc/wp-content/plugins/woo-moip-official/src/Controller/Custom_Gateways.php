<?php
namespace Woocommerce\Moip\Controller;

if ( ! function_exists( 'add_action' ) ) {
	exit( 0 );
}

//WooCommerce
use WC_Payment_Gateway;
use WC_Order;
use WC_Payment_Gateway_CC;

use Woocommerce\Moip\Core;
use Woocommerce\Moip\Model\Setting;
use Woocommerce\Moip\Helper\Utils;
use Woocommerce\Moip\Model\Custom_Gateway;
use Woocommerce\Moip\Model\Checkout;
use Woocommerce\Moip\Model\Order;
use Woocommerce\Moip\Model\Moip_SDK;

//Views
use Woocommerce\Moip\View;

class Custom_Gateways extends WC_Payment_Gateway
{
	/**
	 * @var Object
	 */
	public $model;

	/**
	 * @var Object
	 */
	public $moip_order;

	public function __construct()
	{
		$this->model = new Custom_Gateway();

		$this->id                 = Core::SLUG;
		$this->method_title       = __( 'Moip Official', Core::TEXTDOMAIN );
		$this->method_description = __( 'Payment Gateway Moip', Core::TEXTDOMAIN );
		$this->has_fields         = false;
		$this->icon               = Core::plugins_url( 'assets/images/icons/logo.png' );

		if ( $this->model->settings->is_checkout_transparent() ) {
			$this->order_button_text  = __( 'Proceed to payment', Core::TEXTDOMAIN );
		}

		$this->_set_webhook();
		$this->init_form_fields();
		$this->init_settings();
		$this->_check_errors();

		$this->enabled     = $this->get_option( 'enabled', 'no' );
		$this->title       = $this->get_option( 'title' );
		$this->description = $this->get_option( 'description' );

		if ( is_admin() ) {
			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
		}

		add_action( 'woocommerce_receipt_' . $this->id, array( $this, 'receipt_page' ) );
		add_action( 'woocommerce_thankyou_' . $this->id, array( $this, 'thank_you_page' ) );
		add_action( 'admin_notices', array( $this, 'display_errors' ) );

	}

	public function payment_fields()
	{
		if ( $description = $this->get_description() ) {
			echo wpautop( wptexturize( $description ) );
		}

		$cart_total    = $this->get_order_total();
		$cart_subtotal = WC()->cart->subtotal;

		$public_key    = $this->model->settings->public_key;
		$model         = $this->model;

		if ( $this->model->settings->is_checkout_transparent() ) {
			Utils::template_include( 'templates/checkout-transparent',
				compact( 'cart_total', 'public_key', 'model', 'cart_subtotal' )
			);
		}
	}

	/**
	 * Output the admin options table.
	 *
	 * @since 1.0
	 * @param null
	 * @return Void
	 */
	public function admin_options()
	{
		printf(
			'<h3>%s</h3>
			 <div %s>
			 	<table class="form-table">%s</table>
			 </div>',
			Core::get_name(),
			Utils::get_component( 'settings' ),
			$this->generate_settings_html( $this->get_form_fields(), false )
		);
	}

	/**
	 * Return the name of the option in the WP DB.
	 * @since 1.0
	 * @return string
	 */
	public function get_option_key()
	{
		return $this->model->settings->get_option_key();
	}

	public function is_available() {
		return ( $this->model->settings->is_enabled() && ! $this->get_errors() && $this->model->supported_currency() );
	}

	public function init_form_fields()
	{
		$this->form_fields = array(
			'auth'                     => $this->section_auth(),
			'section_settings'         => $this->section_settings(),
			'enabled'                  => $this->field_enabled(),
			'title'                    => $this->field_title(),
			'description'              => $this->field_description(),
			'invoice_name'             => $this->field_invoice_name(),
			'invoice_prefix'           => $this->field_invoice_prefix(),
			'section_payment_settings' => $this->section_payment_settings(),
			'public_key'               => $this->field_public_key(),
			'payment_api'              => $this->field_payment_type(),
			'billet_banking'           => $this->field_billet_banking(),
			'credit_card'              => $this->field_credit_card(),
			'banking_debit'            => $this->field_banking_debit(),
			'section_installments'     => $this->section_installments(),
			'installments_enabled'     => $this->field_credit_card_installments( 'enabled' ),
			'installments_minimum'     => $this->field_credit_card_installments( 'installment' ),
			'installments_maximum'     => $this->field_credit_card_installments( 'maximum' ),
			'installments'             => $this->field_credit_card_installments( 'general' ),
			'section_discount_settings'=> $this->section_discount_settings(),
			'billet_number_discount'   => $this->billet_number_discount(),
			'section_billet_settings'  => $this->section_billet_settings(),
			'billet_deadline_days'     => $this->field_billet_deadline_days(),
			'billet_instruction_line1' => $this->field_billet_instructions( 1 ),
			'billet_instruction_line2' => $this->field_billet_instructions( 2 ),
			'billet_instruction_line3' => $this->field_billet_instructions( 3 ),
			'billet_logo'              => $this->field_billet_logo_url(),
			'section_tools'            => $this->section_tools(),
			'field_enable_acesstoken'  => $this->field_enable_acesstoken(),
			'field_number_acesstoken'  => $this->field_number_acesstoken(),
			'enable_logs'              => $this->field_enabled_logs(),
			'section_webhook'          => $this->section_webhook(),
			//'section_restore_settings' => $this->section_restore_settings(),
			'section_notification'     => $this->section_notification()
		);
	}

	public function section_auth()
	{
		return array(
			'title'       => __( 'APP authorization', Core::TEXTDOMAIN ),
			'type'        => 'title',
			'description' => $this->_get_app_status(),
		);
	}

	private function _get_app_status()
	{
		$class       = 'app-not-authorized';
		$status      = __( 'Not authorized', Core::TEXTDOMAIN );
		$description = __( 'To make your sales, you must authorize this application on Moip.', Core::TEXTDOMAIN );
		$via         = '';

		if ( $this->model->settings->authorize_data ) {
			$class         = 'app-authorized';
			$status        = __( 'Authorized', Core::TEXTDOMAIN );
			$via           = sprintf( 'via %s', strtoupper( $this->model->settings->authorize_mode ) );
		}

		return sprintf(
			'%s<br><strong>Status: </strong><span class="%s">%s</span> %s %s',
			$description,
			$class,
			$status,
			$via,
			$this->_get_authorized_app_btn()
		);
	}

	public function section_payment_settings()
	{
		return array(
			'title' => __( 'Payment settings', Core::TEXTDOMAIN ),
			'type'  => 'title',
		);
	}

	public function section_settings()
	{
		return array(
			'title' => __( 'Settings', Core::TEXTDOMAIN ),
			'type'  => 'title',
		);
	}

	public function section_installments()
	{
		return array(
			'title' => __( 'Credit Card Installments Settings', Core::TEXTDOMAIN ),
			'type'  => 'title',
		);
	}

	public function section_discount_settings()
	{
		return array(
			'title' => __( 'Discount Settings', Core::TEXTDOMAIN ),
			'type'  => 'title',
		);
	}


	public function section_billet_settings()
	{
		return array(
			'title' => __( 'Billet Settings', Core::TEXTDOMAIN ),
			'type'  => 'title',
		);
	}

	public function section_tools()
	{
		return array(
			'title' => __( 'Tools', Core::TEXTDOMAIN ),
			'type'  => 'title',
		);
	}

	public function field_enabled_logs()
	{
		return array(
			'title'       => __( 'Logs', Core::TEXTDOMAIN ),
			'type'        => 'checkbox',
			'label'       => __( 'Enable', Core::TEXTDOMAIN ),
			'default'     => 'no',
			'description' => sprintf( __( 'To View the logs click the link: %s' , Core::TEXTDOMAIN ), $this->moip_oficial_log_view() ),
		);
	}

	public function section_webhook()
	{
		return array(
			'title'       => __( 'Webhooks', Core::TEXTDOMAIN ),
			'type'        => 'title',
			'description' => $this->send_webhook()
		);
	}

	public function send_webhook()
	{
		$button_notification = View\Custom_Gateways::render_button_webhook_send( $this->model->settings );

		return $button_notification;
	}

	public function section_restore_settings()
	{
		return array(
			'title'       => __( 'Restore Settings', Core::TEXTDOMAIN ),
			'type'        => 'title',
			'description' => $this->restore_settings()
		);
	}

	public function restore_settings()
	{
		$description = __( 'Click the button below to reset the plugin settings.', Core::TEXTDOMAIN );

		return sprintf(
			'%s %s',
			$description,
			$this->_restore_plugin_settings()
		);
	}

	public function section_notification()
	{
		return array(
			'title'       => __( 'Notifications', Core::TEXTDOMAIN ),
			'type'        => 'title',
			'description' => $this->webhook_fields_notifications()
		);
	}

	public function webhook_fields_notifications()
	{
		$all_token  = '';

		if ( $this->model->settings->authorize_data ) {
			$all_token    .= View\Custom_Gateways::render_notification_webhook( $this->model->settings );
		}

		return $all_token;
	}

	public function field_enabled()
	{
		return array(
			'title'   => __( 'Enable', Core::TEXTDOMAIN ),
			'type'    => 'checkbox',
			'label'   => __( 'Enable payment', Core::TEXTDOMAIN ),
			'default' => 'no',
		);
	}

	public function field_title()
	{
		return array(
			'title'       => __( 'Title', Core::TEXTDOMAIN ),
			'description' => __( 'This the title which the user sees during checkout.', Core::TEXTDOMAIN ),
			'desc_tip'    => true,
			'default'     => __( 'Moip', Core::TEXTDOMAIN ),
		);
	}

	public function field_description()
	{
		return array(
			'title'   => __( 'Description', Core::TEXTDOMAIN ),
			'default' => __( 'Pay with Moip', Core::TEXTDOMAIN ),
		);
	}

	public function field_invoice_name()
	{
		return array(
			'title'             => __( 'Invoice name', Core::TEXTDOMAIN ),
			'desc_tip'          => true,
			'placeholder'       => __( 'Maximum of 13 characters', Core::TEXTDOMAIN ),
			'description'       => __( 'It allows the shopkeeper to send a text of up to 13 characters that will be printed on the bearer\'s invoice, next to the shop identification, respecting the length of the flags.', Core::TEXTDOMAIN ),
			'custom_attributes' => array(
				'data-action'    => 'invoice-name',
				'data-element'   => 'validate',
				'maxlength'      => 13,
				'data-error-msg' => __( 'This field is required.', Core::TEXTDOMAIN ),
			),
		);
	}

	public function field_invoice_prefix()
	{
		return array(
			'title'       => __( 'Invoice prefix', Core::TEXTDOMAIN ),
			'default'     => 'WC-',
			'desc_tip'    => true,
			'description' => __( 'Enter a prefix for your invoice numbers. If you use your Moip account for multiple stores, make sure this prefix is unique because Moip will not allow orders with the same invoice number.', Core::TEXTDOMAIN ),
		);
	}

	public function field_enable_acesstoken()
	{
		return array(
			'title'       => __( 'Access Token:', Core::TEXTDOMAIN ),
			'type'        => 'checkbox',
			'label'       => __( 'Enable Access Token', Core::TEXTDOMAIN ),
			'desc_tip'    => true,
			'description' => __( 'Enable access token if connecting more than 1 site and same Moip account.', Core::TEXTDOMAIN ),
			'default'     => '',
			'custom_attributes' => array(
				'data-field' => 'enable-accesstoken',
			),
		);
	}

	public function field_number_acesstoken()
	{
		return array(
			'title'       => __( 'Number Access Token', Core::TEXTDOMAIN ),
			'type'        => 'text',
			'description' => __( 'Copy and paste the access token number of the main site that is enabled for the Moip account.', Core::TEXTDOMAIN ),
			'default'     => '',
			'custom_attributes' => array(
				'data-field' => 'number-accesstoken',
				'maxlength'      => 35,
			),
		);
	}

	public function field_payment_type()
	{
		return array(
			'type'              => 'select',
			'title'             => __( 'Payment Type', Core::TEXTDOMAIN ),
			'class'             => 'wc-enhanced-select',
			'default'           => 'default_checkout',
			'custom_attributes' => array(
				'data-element'  => 'checkout',
				'data-action'   => 'checkout-type',
			),
			'options' => array(
				'default_checkout'     => __( 'Default Checkout', Core::TEXTDOMAIN ),
				'transparent_checkout' => __( 'Transparent Checkout', Core::TEXTDOMAIN ),
				'moip_checkout'        => __( 'Moip Checkout', Core::TEXTDOMAIN ),
			),
		);

	}

	public function field_public_key()
	{
		return array(
			'title'             => __( 'Public Key', Core::TEXTDOMAIN ),
			'type'              => 'textarea',
			'css'               => 'height: 200px',
			'custom_attributes' => array(
				'data-element'   => 'validate',
				'data-field'     => 'public-key',
				'data-error-msg' => __( 'This field is required.', Core::TEXTDOMAIN ),
			),
			'description' => __( 'Allows credit card data to be sent encrypted, generating more security in your transactions.', Core::TEXTDOMAIN )
		);
	}

	public function field_billet_banking()
	{
		return array(
			'title'   => __( 'Billet Banking', Core::TEXTDOMAIN ),
			'type'    => 'checkbox',
			'label'   => __( 'Enable Billet Banking', Core::TEXTDOMAIN ),
			'default' => 'yes',
		);
	}

	public function field_credit_card()
	{
		return array(
			'title'   => __( 'Credit Card', Core::TEXTDOMAIN ),
			'type'    => 'checkbox',
			'label'   => __( 'Enable Credit Card', Core::TEXTDOMAIN ),
			'default' => 'yes',
		);
	}

	public function field_banking_debit()
	{
		return array(
			'title'   => __( 'Banking Debit', Core::TEXTDOMAIN ),
			'type'    => 'checkbox',
			'label'   => __( 'Enable Banking Debit', Core::TEXTDOMAIN ),
			'default' => 'yes',
			'custom_attributes' => array(
				'data-field' => 'debit',
			),
		);
	}

    public function billet_number_discount()
    {
    	return array(
			'title'             => __( 'Billet Discount', Core::TEXTDOMAIN ),
			'description'       => __( 'Enter the discount amount for the bank slip (Example: 4,99).', Core::TEXTDOMAIN ),
			'desc_tip'          => true,
			'type'              => 'text',
			'placeholder'       => '0,00',
			'custom_attributes' => array(
				'data-mask'         => '##0,00%',
				'data-mask-reverse' => true,
				'data-field'        => 'billet-discount',
			),
		);
    }


	public function field_billet_deadline_days()
	{
		return array(
			'title'             => __( 'Number of Days', Core::TEXTDOMAIN ),
			'description'       => __( 'Days of expiry of the billet after printed.', Core::TEXTDOMAIN ),
			'desc_tip'          => true,
			'placeholder'       => 5,
			'default'           => 5,
			'custom_attributes' => array(
				'data-field' => 'billet',
			),
		);
	}

	public function field_billet_instructions( $line )
	{
		$instructions = array(
			1 => array(
				'title'       => __( 'Instruction Line 1', Core::TEXTDOMAIN ),
				'type'        => 'text',
				'description' => __( 'First line instruction for the billet.', Core::TEXTDOMAIN ),
				'desc_tip'    => true,
			),
			2 => array(
				'title'       => __( 'Instruction Line 2', Core::TEXTDOMAIN ),
				'type'        => 'text',
				'description' => __( 'Second line instruction for the billet.', Core::TEXTDOMAIN ),
				'desc_tip'    => true,
			),
			3 => array(
				'title'       => __( 'Instruction Line 3', Core::TEXTDOMAIN ),
				'type'        => 'text',
				'description' => __( 'Third line instruction for the billet.', Core::TEXTDOMAIN ),
				'desc_tip'    => true,
			),
		);

		return $instructions[ $line ];
	}

	public function field_billet_logo_url()
	{
		return array(
			'title'       => __( 'Custom Logo URL', Core::TEXTDOMAIN ),
			'type'        => 'text',
			'description' => __( 'URL of the logo image to be shown on the billet.', Core::TEXTDOMAIN ),
			'desc_tip'    => true,
			'default'     => ''
		);
	}

	public function field_credit_card_installments( $field )
	{
		$installments = array();

		$installments['enabled'] = array(
			'title'             => __( 'Installments settings', Core::TEXTDOMAIN ),
			'type'              => 'checkbox',
			'label'             => __( 'Enable Installments settings', Core::TEXTDOMAIN ),
			'default'           => 'no',
			'custom_attributes' => array(
				'data-field' => 'installments',
			),
		);

		$installments['installment'] = array(
			'title'       => __( 'Minimum installment', Core::TEXTDOMAIN ),
			'type'        => 'text',
			'description' => __( 'Amount of the minimum installment to be applied to the card.', Core::TEXTDOMAIN ),
			'desc_tip'    => true,
			'placeholder' => '0.00',
			'custom_attributes' => array(
				'data-mask'         => '##0,00',
				'data-mask-reverse' => true,
			),
		);

		$installments['maximum'] = array(
			'title'       => __( 'Maximum installments number', Core::TEXTDOMAIN ),
			'type'        => 'select',
			'description' => __( 'Force a maximum number of installments for payment.', Core::TEXTDOMAIN ),
			'desc_tip'    => true,
			'default'     => 12,
			'options'     => $this->model->get_installment_options(),
			'custom_attributes' => array(
				'data-action' => 'installments-maximum',
			),
		);

		$installments['general'] = array(
			'title'       => __( 'Interest per installment', Core::TEXTDOMAIN ),
			'type'        => 'installments',
			'description' => __( 'Define interest for each installment.', Core::TEXTDOMAIN ),
			'desc_tip'    => true,
		);

		$installments['interest'] = array(
			'title'       => __( 'Interest', Core::TEXTDOMAIN ),
			'type'        => 'text',
			'description' => __( 'Interest to be applied to the installment.', Core::TEXTDOMAIN ),
			'desc_tip'    => true,
			'placeholder' => '0.00',
		);

		return $installments[ $field ];
	}

	public function process_payment( $order_id )
	{
		$wc_order = new WC_Order( $order_id );

		if ( $this->model->settings->is_checkout_transparent() ) {
			return $this->_process_checkout_transparent( $wc_order );
		}

		return array(
			'result'   => 'success',
			'redirect' => $wc_order->get_checkout_payment_url( true ),
		);
	}

	private function _process_checkout_transparent( $wc_order )
	{
		$payment = Checkouts::process_checkout_transparent( $wc_order );

		if ( ! $payment ) {
			return array(
				'result'   => 'fail',
				'redirect' => '',
			);
		}

		wc_reduce_stock_levels( $wc_order );
		WC()->cart->empty_cart();

		return array(
			'result'   => 'success',
			'redirect' => $this->get_return_url( $wc_order ),
		);
	}

	public function receipt_page( $order_id )
	{
		if ( $this->model->settings->is_checkout_default() ) {
			$this->checkout_default( $order_id );
		} else {
			$this->checkout_moip( $order_id );
		}
	}

	public function thank_you_page( $order_id )
	{
		$order = new WC_Order( $order_id );

		if ( $this->model->settings->is_checkout_transparent() ) {
			require_once Core::get_file_path( 'thank-you-page-transparent.php', 'templates/' );
			return;
		}

		require_once Core::get_file_path( 'thank-you-page.php', 'templates/' );
	}

	public function checkout_moip( $order_id )
	{
		$order         = new Order( $order_id );
		$wc_order      = new WC_Order( $order_id );
		$payment_links = $order->payment_links;

		if ( ! is_object( $payment_links ) ) {
			$moip_sdk         = Moip_SDK::get_instance();
			$this->moip_order = $moip_sdk->create_order( $wc_order );

			if ( ! $this->moip_order ) {
				return;
			}

			$payment_links = $this->moip_order['response']->_links->checkout;
		}

		require_once( Core::get_file_path( 'checkout-moip.php', 'templates/' ) );

		unset( $order );
		unset( $wc_order );
	}

	public function checkout_default( $order_id )
	{
		$wc_order = new WC_Order( $order_id );

		require_once( Core::get_file_path( 'checkout-default.php', 'templates/' ) );
	}

	private function _set_webhook() {
		Webhooks::set_token( $this );
	}

	private function _check_errors()
	{
		if ( ! $this->model->settings->authorize_data ) {
			$this->add_error( __( 'Application not authorized.', Core::TEXTDOMAIN ) );
		}

		if ( ! $this->model->settings->invoice_name ) {
			$this->add_error( __( 'Invoice name is required.', Core::TEXTDOMAIN ) );
		}

		if ( ! $this->model->settings->public_key ) {
			$this->add_error( __( 'Not informed public key.', Core::TEXTDOMAIN ) );
		}

		return $this->errors;
	}

	public function display_errors()
	{
		if ( ! $this->get_errors() ) {
			return;
		}

		echo '<div id="woocommerce_errors" class="error notice is-dismissible">';

		printf(
			'<p><strong>%s:</strong> %s <a href="%s">%s</a></p>',
			Core::get_name(),
			__( 'You need to set up your Moip data to use the payment method.', Core::TEXTDOMAIN ),
			Core::get_page_link(),
			__( 'Go to setup', Core::TEXTDOMAIN )
		);

		echo '<ol>';

		foreach ( $this->get_errors() as $error ) {
			printf(
				'<li><strong>%s</strong></li> ',
				wp_kses_post( $error )
			);
		}

		echo '</ol>';
		echo '</div>';
	}

	/**
	 * Get HTML for descriptions.
	 *
	 * @param  array $data
	 * @return string
	 */
	public function get_description_html( $data )
	{
		if ( $data['desc_tip'] === true ) {
			return;
		} elseif ( ! empty( $data['desc_tip'] ) ) {
			$description = $data['description'];
		} elseif ( ! empty( $data['description'] ) ) {
			$description = $data['description'];
		} else {
			return;
		}

		return sprintf(
			'<p class="description %s">%s</p>',
			sanitize_html_class( Utils::get_value_by( $data, 'class_p' ) ),
			strip_tags( $description, '<a><span>' )
		);
	}

	public function generate_installments_html( $key, $data )
	{
		$field_key = $this->get_field_key( $key );
		$defaults  = array(
			'title'             => '',
			'disabled'          => false,
			'class'             => '',
			'css'               => '',
			'placeholder'       => '',
			'type'              => 'text',
			'desc_tip'          => false,
			'description'       => '',
			'custom_attributes' => array(),
		);

		$data  = wp_parse_args( $data, $defaults );
		$value = (array) $this->get_option( $key, array() );

		ob_start();

		?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<?php echo $this->get_tooltip_html( $data ); ?>
				<label for="<?php echo esc_attr( $field_key ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></label>
			</th>
			<td class="forminp">
				<fieldset>
					<?php
						for ( $i = 2; $i <= 12; $i++ ) :
							$interest = isset( $value['interest'][ $i ] ) ? $value['interest'][ $i ] : '';
					?>
					<p data-installment="<?php echo $i; ?>">
						<input class="small-input" type="text" value="<?php echo $i; ?>"
							   <?php disabled( 1, true ); ?> />
						<input class="small-input" type="text"
							   placeholder="0,00"
							   data-mask="##0,00" data-mask-reverse="true"
							   name="<?php echo esc_attr( $field_key ); ?>[interest][<?php echo $i; ?>]"
							   id="<?php echo esc_attr( $field_key ); ?>" value="<?php echo wc_format_localized_price( $interest ) ?>" />%
					</p>
					<?php endfor; ?>

					<?php echo $this->get_description_html( $data ); ?>
				</fieldset>
			</td>
		</tr>
		<?php

		return ob_get_clean();
	}

	public function validate_installments_field( $key, $value )
	{
		return $value;
	}

	private function _get_authorized_app_btn()
	{
		$title = __( 'Authorize App', Core::TEXTDOMAIN );
		$class = 'button-primary';

		if ( $this->model->settings->authorize_data ) {
			$title = __( 'New authorize', Core::TEXTDOMAIN );
			$class = '';
		}

		return sprintf(
			'<p>
				<a href="#"
				   class="button %s"
				   id="oauth-app-btn">
					%s
				</a>
			</p>',
			$class,
			$title
		);
	}

	private function _restore_plugin_settings()
	{
		$title = __( 'Restore Configurations', Core::TEXTDOMAIN );
		$class = 'button-primary';

		return sprintf(
			'<p>
				<a href="#"
				   class="button %s"
				   id="restore-app-btn">
					%s
				</a>
			</p>',
			$class,
			$title
		);
	}

	private function moip_oficial_log_view() {
		return '<a href="' . esc_url( admin_url( 'admin.php?page=wc-status&tab=logs&log_file=' . esc_attr( $this->id ) . '-' . sanitize_file_name( wp_hash( $this->id ) ) . '.log' ) ) . '">' . __( 'System Status &gt; Logs', Core::TEXTDOMAIN ) . '</a>';
	}
}