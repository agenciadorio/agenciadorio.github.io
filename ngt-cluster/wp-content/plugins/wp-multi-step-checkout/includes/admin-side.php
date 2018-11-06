<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class WPMultiStepCheckout_Settings {

    public $messages = array();
    private $tab = 'general';
    private $settings = array();

    private $slug = 'wmsc-settings';

    /**
     * Constructor
     */
    public function __construct() {

        require_once 'settings-array.php';
        $this->settings = get_wmsc_settings();
        /*
        $this->defaults = array(
          'main_color' => array('text', __('Main color', 'wp-multi-step-checkout'), '#1e85be'),
          'show_back_to_cart_button' => array('checkbox', __('Show the "Back to Cart" button', 'wp-multi-step-checkout'), true),
          'show_shipping_step' => array('checkbox', __('Show the Shipping step', 'wp-multi-step-checkout'), true),
          'unite_billing_shipping' => array('checkbox', __('Show the Billing and the Shipping steps together', 'wp-multi-step-checkout'), false),
          'unite_order_payment' => array('checkbox', __('Show the Order and the Payment steps together', 'wp-multi-step-checkout'), false),
        );
         */
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
        add_action( 'admin_enqueue_scripts', array($this, 'admin_enqueue_scripts') );
    }

    /**
     * Create the menu link
     */
    function admin_menu() {
        add_submenu_page(
            'woocommerce', 
            'Multi-Step Checkout', 
            'Multi-Step Checkout', 
            'manage_options', 
            $this->slug, 
            array($this, 'admin_settings_page')
//            array($this, 'admin_contents')
        );
    }

    /**
     * Enqueue the scripts and styles 
     */
    function admin_enqueue_scripts() {
        $page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_URL);
        if ( $page != 'wmsc-settings' ) return false;

        // Color picker
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script('wp-color-picker');

        $u = plugins_url('/', WMSC_PLUGIN_FILE) . 'assets/';     // assets url
        $v = WMSC_VERSION;                          // version
        $d = array('jquery');                       // dependency
        $w = true;                                  // where? in the footer?

        // Load scripts
        wp_enqueue_script( 'wmsc-bootstrap', $u.'js/bootstrap.3.2.0.min.js', $d, $v, $w);
        wp_enqueue_script( 'wmsc-admin-script', $u.'js/admin-script.js', $d, $v, $w);

        // Load styles
        wp_enqueue_style ( 'wmsc-bootstrap',   $u.'css/bootstrap.min.css', array(), $v);
        wp_enqueue_style ( 'wmsc-admin-style', $u.'css/admin-style.css', array(), $v);
    }

    /**
     * Save the data on update
     */
    function admin_update() {
      if ( !isset($_POST) || !is_array($_POST) || count($_POST) == 0 )
        return;

      check_admin_referer( $this->slug );

      foreach( $this->defaults as $_id => $_field ) {
        if ( isset($_POST[$_id]) ) $this->defaults[$_id][2] = $_POST[$_id];
        if ( isset($_POST[$_id]) && $_field[0] === 'checkbox') $this->defaults[$_id][2] = true;
        if ( !isset($_POST[$_id]) && $_field[0] === 'checkbox') $this->defaults[$_id][2] = false;
      }

      update_option($this->slug, $this->defaults);

    }

    /**
     * Helper function: build a field
     */
    function field($id, $field) {
      switch( $field[0]) {
        case 'checkbox' :
          $checked = ($field[2]) ? ' checked' : '';
          return '<input type="checkbox" name="'.$id.'" id="wpmc-'.$id.'" value="1"'.$checked.' />';
        case 'text' :
          return '<input type="text" name="'.$id.'" id="wpmc-'.$id.'" value="'.esc_attr( $field[2] ) .'" />';
      }
    }

    /**
     * Show the contents of the admin settings page
     */
    function admin_contents() {
        //must check that the user has the required capability
        if (!current_user_can('manage_options')) {
            wp_die(('You do not have sufficient permissions to access this page.'));
        }

        $this->admin_update();

        $fields = get_option($this->slug);

        if ( $fields === false ) $fields = $this->defaults;

        ?>
        <div class="wrap">
        <h1><?php _e('WooCommerce Multi-Step Checkout', 'Plugin title', 'wp-multi-step-checkout'); ?></h1>

        <form method="post" action="admin.php?page=wpmc-settings">
            <?php settings_fields( $this->slug ); ?>
            <?php do_settings_sections( $this->slug ); ?>
            <table class="form-table">
              <?php
              foreach( $fields as $_id => $_field ) : ?>
                <tr valign="top">
                <th scope="row"><?php echo (isset($this->defaults[$_id])) ? $this->defaults[$_id][1] : $_field[1]; ?></th>
                <td><?php echo $this->field($_id, $_field); ?></td>
                </tr>
              <?php endforeach; ?>
            </table>

            <?php wp_nonce_field( $this->slug ); ?>
            <?php submit_button(); ?>

        </form>
        </div>
        <?php
    }


    /**
     * Output the admin page
     * @access public
     */
    public function admin_settings_page() {

        // Get the tab name
        $allowed_tabs = array(
            'general'   => __('General Settings', 'wp-multi-step-checkout'),
            'titles'    => __('Text on Steps and Buttons', 'wp-multi-step-checkout'), 
        );

        $tab = (isset($_GET['tab'])) ? $_GET['tab'] : 'general';

        if ( !isset($allowed_tabs[$tab])) $tab = 'general';

        // Get the messages
        $messages = $this->show_messages();

        // Which options to load
        switch( $tab ) {
            case 'titles' : 
                $form_label_class = 'col-sm-4 control-label';
                $general_settings = array(
                    't_login', 't_billing', 't_shipping', 't_order', 't_payment', 't_back_to_cart', 't_skip_login', 't_previous', 't_next',  
                );
                break;
            case 'general' : 
                $form_label_class = 'col-sm-5 control-label';
                $general_settings = array(
                    'main_color', 'show_back_to_cart_button', 'show_shipping_step', 'show_login_step', 'unite_billing_shipping', 'unite_order_payment', 'keyboard_nav',    
                );
                break;
        }

        // Get the saved options
        $settings_values = get_option('wmsc_options');

        // Save the options
        if ( ! empty( $_POST ) ) {
            check_admin_referer('wmsc_'. $tab);
            $new_values = $this->validate( $_POST, $general_settings );
            if ( $settings_values == false ) {
                $default_settings = get_wmsc_settings();
                foreach($default_settings as $_key => $_value ) $default_settings[$_key] = $_value['value'];
                $settings_values = array_merge( $default_settings, $new_values );
            } else {
                $settings_values = array_merge( $settings_values, $new_values );
            }

            if ( isset($settings_values['show_login_step'] ) ) {
                unset($settings_values['show_login_step'] );
            }

            update_option( 'wmsc_options', $settings_values );
            self::add_message( 'success', '<b>'.__('Your settings have been saved.') . '</b>' );
            $messages = $this->show_messages();
        }

        // Show the options
        require_once 'forms-helper.php';
        $forms_helper = new WMSC_FormsHelper;
        ob_start();
        $forms_helper->label_class = $form_label_class; 
        foreach( $general_settings as $_field ) {
            $field_settings = $this->get_settings( $_field);
            if ( isset($settings_values[$_field])) {
                $field_settings['value'] = stripslashes($settings_values[$_field]);
            }
            $forms_helper->input($field_settings['input_form'], $field_settings); 
        }
        $contents = ob_get_contents(); ob_end_clean();

        // Show the page
        include_once 'admin-template.php';
        echo str_replace('{$content}', $contents, $template);

    }


    /**
     * Validate the $_POST values
     */
    private function validate( $post, $fields ) {

        // filter only the allowed fields
        $fields = array_fill_keys( $fields, '' );
        $post = array_intersect_key( $post, $fields );

        foreach($fields as $_key => $_value ) {
            // Add the unchecked checkboxes
            if ( !isset($post[$_key])) {
                $post[$_key] = false;
            }

            // Get the defaults
            $settings = $this->get_settings( $_key );

            // Validate the checkboxes
            if ( $settings['input_form'] == 'checkbox' && $post[$_key] == 'on' ) {
                if ($post[$_key] == 'on') $post[$_key] = true;
                if ( !is_bool($post[$_key])) $post[$_key] = $settings['value'];
            }

            // Validate colors
            if ( $settings['input_form'] == 'input_color' && !preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $post[$_key]) ) {
                $post[$_key] = $settings['value'];
                $this->add_message('info', __('Unrecognized <b>'.$settings['label'].'</b>. The value was reset to <b>'.$settings['value'] . '</b>') );
            }

            // Sanitize text
            if ( $settings['input_form'] == 'input_text' ) {
                $post[$_key] = filter_var($post[$_key], FILTER_SANITIZE_STRING);
            }

            // Validate against a values set 
            if ( in_array( $settings['input_form'], array('button', 'radio')) ) {
                if ( !array_key_exists($post[$_key], $settings['values']) ) {
                    $value = $settings['value'];
                    $this->add_message('info', __('Unrecognized <b>'.$settings['label'].'</b>. The value was reset to <b>'.$settings['value'] . '</b>') );
                }
            }

            if ( isset($settings['validate'])) {
                if ($settings['validate']['type'] == 'int') {
                    $post[$_key] = (int)$post[$_key];
                }
                if ($settings['validate']['type'] == 'float') {
                    $post[$_key] = (float)$post[$_key];
                }
                $min = $settings['validate']['range'][0];
                $max = $settings['validate']['range'][1];

                if ( !is_numeric($post[$_key]) || $post[$_key] < $min || $post[$_key] > $max ) {
                    $post[$_key] = $settings['value'];
                    $this->add_message('info', __('<b>'.$settings['label'].'</b> accepts values between '.$min.' and '.$max .'. Your value was reset to <b>' . $settings['value'] .'</b>') );
                }

            }
        }

        return $post;
    }


    /**
     * Build an array with settings that will be used in the form
     * @access public
     */
    public function get_settings( $id  = '' ) {

        if ( isset( $this->settings[$id] ) ) {
            $this->settings[$id]['name'] = $id;
            return $this->settings[$id];
        } 

        return $this->settings;
    }


    /**
     * Add a message to the $this->messages array
     * @type    accepted types: success, error, info, block
     * @access private
     */
    private function add_message( $type = 'success', $text ) {
        global $comment;
        $messages = $this->messages;
        $messages[] = array('type' => $type, 'text' => $text);
        $comment[] = array('type' => $type, 'text' => $text);
        $this->messages = $messages;
    }

    /**
     * Output the form messages
     * @access public
     */
    public function show_messages() {
        global $comment;
        if ( !$comment || sizeof( $comment ) == 0 ) return;
        $output = '<div class="col-lg-12">';
        foreach ( $comment as $message ) {
            $output .= '<div class="alert alert-'.$message['type'].'">
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                  '. $message['text'] .'</div>';
        }
        $output .= '</div>';
        return $output;
    }


}

new WPMultiStepCheckout_Settings();
