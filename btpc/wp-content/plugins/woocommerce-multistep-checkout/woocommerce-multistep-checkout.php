<?php
/**
 * Plugin Name: WooCommerce MultiStep Checkout
 * Description: WooCommerce Multi-Step-Checkout enable multi-step-checkout functionality on WooCommerce checkout page.
 * Version: 2.3.0
 * Author: Mubashir Iqbal
 * Author URI: http://www.mubashir09.com
 * Text Domain: woocommerce-multistep-checkout
 * Domain Path: /languages/
 */
if (!defined('ABSPATH'))
    die();

function dependentplugin_activate() {

    if (!is_plugin_active('woocommerce/woocommerce.php')) {
        // deactivate dependent plugin
        deactivate_plugins(plugin_basename(__FILE__));

        exit('<strong>WooCommerce Multistep Checkout</strong> requires <a target="_blank" href="http://wordpress.org/plugins/woocommerce/">WooCommerce</a> Plugin to be installed first.');
    }else{
        update_option('wmc_merge_order_payment_tabs', 'true');
    }
    
}

register_activation_hook(__FILE__, 'dependentplugin_activate');


load_plugin_textdomain('woocommerce-multistep-checkout', false, dirname(plugin_basename(__FILE__)) . '/languages/');

add_filter('woocommerce_locate_template', 'wcmultichecout_woocommerce_locate_template', 1, 3);

function wcmultichecout_woocommerce_locate_template($template, $template_name, $plugin_path) {

    $plugin_path = untrailingslashit(plugin_dir_path(__FILE__)) . '/woocommerce/';
    if (file_exists($plugin_path . $template_name)) {
        $template = $plugin_path . $template_name;
        return $template;
    }

    return $template;
}

function enque_woocommerce_multistep_checkout_scripts() {
    $wizard_type = get_option('wmc_wizard_type');
    wp_register_script('jquery-steps', plugins_url('/js/jquery.steps.js', __FILE__), array('jquery'), null, true);
    wp_register_script('jquery-validate', plugins_url('/js/jquery.validate.js', __FILE__), array('jquery'), null, true);

    if ($wizard_type == '' || $wizard_type == 'elegant') {
        wp_register_style('jquery-steps', plugins_url('/css/jquery.steps-elegant.css', __FILE__));
    } elseif ($wizard_type == 'classic') {
        wp_register_style('jquery-steps', plugins_url('/css/jquery.steps-classic.css', __FILE__));
    } else {
        wp_register_style('jquery-steps', plugins_url('/css/jquery.steps-modern.css', __FILE__));
    }
    wp_register_style('jquery-steps-main', plugins_url('/css/main.css', __FILE__));

    /*     * *Only add on WooCommerce checkout page * */
    if (is_checkout() || defined('ICL_LANGUAGE_CODE')) {
        wp_enqueue_script('jquery-steps');
        wp_enqueue_script('jquery-validate');
        wp_enqueue_style('jquery-steps');
        wp_enqueue_style('jquery-steps-main');
    }
}

add_action('wp_enqueue_scripts', 'enque_woocommerce_multistep_checkout_scripts');

/* * *Loading variables to wizard file ** */

function wmc_load_scripts() {
    $vars = array(
        'transitionEffect' => get_option('wmc_animation') ? get_option('wmc_animation') : 'fade',
        'stepsOrientation' => get_option('wmc_orientation') ? get_option('wmc_orientation') : 'horizontal',
        'enableAllSteps' => get_option('wmc_enable_all_steps') ? get_option('wmc_enable_all_steps') : 'false',
        'enablePagination' => get_option('wmc_enable_pagination') ? get_option('wmc_enable_pagination') : 'true',
        'next' => get_option('wmc_btn_next') ? __(get_option('wmc_btn_next'), 'woocommerce-multistep-checkout') : __('Next', 'woocommerce-multistep-checkout'),
        'previous' => get_option('wmc_btn_prev') ? __(get_option('wmc_btn_prev'), 'woocommerce-multistep-checkout') : __('Previous', 'woocommerce-multistep-checkout'),
        'finish' => get_option('wmc_btn_finish') ? __(get_option('wmc_btn_finish'), 'woocommerce-multistep-checkout') : __('Place Order', 'woocommerce-multistep-checkout'),
        'error_msg' => get_option('wmc_empty_error') ? __(get_option('wmc_empty_error'), 'woocommerce-multistep-checkout') : __('This field is required', 'woocommerce-multistep-checkout'),
        'email_error_msg' => get_option('wmc_email_error') ? __(get_option('wmc_email_error'), 'woocommerce-multistep-checkout') : __('Please enter a valid email address', 'woocommerce-multistep-checkout'),
        'phone_error_msg' => get_option('wmc_phone_error') ? __(get_option('wmc_phone_error'), 'woocommerce-multistep-checkout') : __('Invalid phone number', 'woocommerce-multistep-checkout'),
        'remove_numbers' => get_option('wmc_remove_numbers'),
        'isAuthorizedUser' => isAuthorizedUser(),
        'loading_img' => plugins_url('images/animatedEllipse.gif', __FILE__),
        'ajaxurl' => admin_url('admin-ajax.php'),
        'wmc_remove_numbers' => get_option('wmc_remove_numbers'),
        'include_login' => get_option('wmc_add_login_form'),
        'include_coupon_form' => get_option('wmc_add_coupon_form'),
        'woo_include_login' => get_option('woocommerce_enable_checkout_login_reminder'),
        'no_account_btn' => get_option('wmc_no_account_btn') ? __(stripslashes(get_option('wmc_no_account_btn')), 'woocommerce-multistep-checkout') : __("I don't have an account", 'woocommerce-multistep-checkout'),
        'login_nonce' => wp_create_nonce('wmc-login-nonce')
    );
    if (is_checkout()) {
        wp_register_script('wmc-wizard', plugins_url('/js/wizard.js', __FILE__), array('jquery-steps'), null, true);
        wp_enqueue_script('wmc-wizard');
        wp_localize_script('wmc-wizard', 'wmc_wizard', $vars);
    }
}

add_action('wp_enqueue_scripts', 'wmc_load_scripts');


/* * **********Plugin Options Page ** */
add_action('admin_menu', 'woocommercemultichekout_menu_page');

function woocommercemultichekout_menu_page() {
    add_submenu_page('woocommerce', 'WooCommerce MultiStepCheckout', 'Checkout Wizard', 'manage_options', 'wcmultichekout', 'wcmultichekout_options');
}

/* * * Add Color Picker * */
add_action('admin_enqueue_scripts', 'wmc_enqueue_color_picker');

function wmc_enqueue_color_picker() {
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('wp-color-picker-script', plugins_url('js/script.js', __FILE__), array('wp-color-picker'), false, true);
}

function add_jquery_steps_options() {
    if (is_checkout() || defined('ICL_LANGUAGE_CODE')):
        $wizard_type = get_option('wmc_wizard_type');
        ?>

        <?php if ($wizard_type == 'classic' || $wizard_type == 'elegant' || $wizard_type == '') { //if this is a classic wizard type       ?>
            <style>

                .wizard > .steps .current a, .wizard > .steps .current a:hover{
                    background: <?php echo get_option('wmc_tabs_color') ?>;
                    color: <?php echo get_option('wmc_font_color') ?>;
                }

                .wizard > .steps .disabled a{
                    background: <?php echo get_option('wmc_inactive_tabs_color') ?>;
                }

                .wizard > .actions a, .wizard > .actions a:hover, .wizard > .actions a:active, #wizard form.login input.button, #wizard .checkout_coupon .button{
                    background: <?php echo get_option('wmc_buttons_bg_color') ?>;
                    color: <?php echo get_option('wmc_buttons_font_color') ?>;
                }
                .wizard > .steps .done a{
                    background: <?php echo get_option('wmc_completed_tabs_color') ?>;
                }
                .wizard > .content{
                    background: <?php echo get_option('wmc_wrapper_bg') ?>;
                }

                .woocommerce form .form-row label, .woocommerce-page form .form-row label, .woocommerce-checkout .shop_table, .woocommerce table.shop_table tfoot th,
                .woocommerce table.shop_table th, .woocommerce-page table.shop_table th, #ship-to-different-address
                {
                    color: <?php echo get_option('wmc_form_labels_color') ?>;
                }

            </style>

        <?php } else { //if modern wizard
            ?>
            <style>
            <?php if (get_option('wmc_tabs_color')): ?>
                    .wizard > .steps li.current a:before{
                        border-bottom: 30px solid <?php echo get_option('wmc_tabs_color') ?>;
                        border-top: 30px solid <?php echo get_option('wmc_tabs_color') ?>
                    }
                    .wizard > .steps li.current a:after{
                        border-left: 20px solid <?php echo get_option('wmc_tabs_color') ?>
                    }                 
                    .wizard > .steps li.current a{
                        background-color: <?php echo get_option('wmc_tabs_color') ?>
                    }
            <?php endif; ?>
                                                                                                                                                                                                                                                                                                        
            <?php if (get_option('wmc_buttons_bg_color')): ?>
                    .wizard > .actions a, .wizard > .actions a:hover, .wizard > .actions a:active, .login .form-row .button{
                        background: <?php echo get_option('wmc_buttons_bg_color') ?>;
                    }
            <?php endif; ?>
                                                                                                                                                                                                                                                                                
            <?php if (get_option('wmc_buttons_font_color')): ?>
                    .wizard > .actions a, .wizard > .actions a:hover, .wizard > .actions a:active, #wizard form.login input.button{
                        color: <?php echo get_option('wmc_buttons_font_color') ?>;
                    }
            <?php endif; ?>
                                                                                                                                                                                                                                                                                
                                                                                                                                                                                                                                                                                                    
            <?php if (get_option('wmc_inactive_tabs_color')): ?>
                    .wizard > .actions .disabled a{
                        background: <?php echo get_option('wmc_inactive_tabs_color') ?>
                    }

                    .wizard > .steps a:before {
                        border-bottom: 30px solid <?php echo get_option('wmc_inactive_tabs_color') ?>;
                        border-top: 30px solid <?php echo get_option('wmc_inactive_tabs_color') ?>;
                    }

                    .wizard > .steps a:after{
                        border-left: 20px solid <?php echo get_option('wmc_inactive_tabs_color') ?>;
                    }

                    .wizard > .steps a{
                        background-color: <?php echo get_option('wmc_inactive_tabs_color') ?>;
                    }
            <?php endif; ?>
                                                                                                                                                                                                                                                                                        
            <?php if (get_option('wmc_font_color')): ?> 
                    .wizard > .steps li.current a{
                        color: <?php echo get_option('wmc_font_color') ?>
                    }
            <?php endif; ?>
                                                                                                                                                                                                                                                                                        
            <?php if (get_option('wmc_buttons_bg_color')): ?> 
                    .wizard > .actions a, #wizard .checkout_coupon .button, #wizard .checkout_coupon .button, #wizard form.login input.button{
                        background-color: <?php echo get_option('wmc_buttons_bg_color') ?>
                    }
                                                                                                                                                                                                                                                                                                                                                                                
            <?php endif; ?>
                                                                                                                                                                                                                                                                                        
            <?php if (get_option('wmc_completed_tabs_color')): ?> 
                                                                                                                                                                                                                                                                                                                                                                    
                    .wizard > .steps li.done a:before {
                        border-bottom: 30px solid <?php echo get_option('wmc_completed_tabs_color') ?>;
                        border-top: 30px solid <?php echo get_option('wmc_completed_tabs_color') ?>;
                    }
                                                                                                                                                                                                                                                                                                                                    
                    .wizard > .steps li.done a:after{
                        border-left: 20px solid <?php echo get_option('wmc_completed_tabs_color') ?>;
                    }
                                                                                                                                                                                                                                                                                                                                    
                    .wizard > .steps li.done a{
                        background-color: <?php echo get_option('wmc_completed_tabs_color') ?>;
                    }
            <?php endif; ?>
                                                                                                                                                                                                                                                        
            <?php if (get_option('wmc_form_labels_color')): ?> 
                    .woocommerce form .form-row label, .woocommerce-page form .form-row label, .woocommerce-checkout .shop_table, .woocommerce table.shop_table tfoot th,
                    .woocommerce table.shop_table th, .woocommerce-page table.shop_table th, #ship-to-different-address
                    {
                        color: <?php echo get_option('wmc_form_labels_color') ?>;
                    }
            <?php endif; ?>
            </style>
            <?php
        }
    endif;
}

add_action('wp_head', 'add_jquery_steps_options');

function wcmultichekout_options() {

    //must check that the user has the required capability 
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }


    //Submit form
    if (isset($_POST['send_form']) && $_POST['send_form'] == 'Y') {

        $do_not_save = array('send_form', 'submit', 'wmc_restore_default');
        foreach ($_POST as $option_name => $option_value) {
            if (in_array($option_name, $do_not_save))
                continue;

            // Save the posted value in the database
            update_option($option_name, $option_value);
        }

        // If restore to default
        if (isset($_POST['wmc_restore_default']) && $_POST['wmc_restore_default']) {
            $do_not_save[] = 'wmc_merge_order_payment_tabs';
            foreach ($_POST as $option_name => $option_value) {
                if (in_array($option_name, $do_not_save))
                    continue;
                delete_option($option_name);
            }
        }
        ?>
        <div class="updated"><p><strong><?php _e('settings saved.', 'woocommerce-multistep-checkout'); ?></strong></p></div>
        <?php
    }
    ?>
    <div class="wrapper">
        <div id="icon-edit" class="icon32"></div><h2><?php _e('WooCommerce MultiStep-Checkout', 'woocommerce-multistep-checkout') ?></h2>
        <form name="wccheckout_options" method="post" action="">
            <input type="hidden" name="send_form" value="Y">
            <table class="form-table">

                <tr>
                    <td><?php _e('Wizard Type', 'woocommerce-multistep-checkout') ?></td>
                    <td><select name="wmc_wizard_type">
                            <option value="elegant" <?php selected(get_option('wmc_wizard_type'), 'elegant', true); ?>><?php _e('Elegant', 'woocommerce-multistep-checkout') ?></option>
                            <option value="classic" <?php selected(get_option('wmc_wizard_type'), 'classic', true); ?>><?php _e('Classic', 'woocommerce-multistep-checkout') ?></option>
                            <option value="modern" <?php selected(get_option('wmc_wizard_type'), 'modern', true); ?>><?php _e('Modern', 'woocommerce-multistep-checkout') ?></option>
                        </select>
                        <span class="description"><?php _e('Select the type of Wizard', 'woocommerce-multistep-checkout') ?></span></td>
                </tr>

                <tr>
                    <td width="200"><?php _e('Add Login to Wizard', 'woocommerce-multistep-checkout') ?></td>
                    <td><select name="wmc_add_login_form">
                            <option value="true" <?php selected(get_option('wmc_add_login_form'), 'true', true); ?>><?php _e('Yes', 'woocommerce-multistep-checkout') ?></option>
                            <option value="false" <?php selected(get_option('wmc_add_login_form'), 'false', true); ?>><?php _e('No', 'woocommerce-multistep-checkout') ?></option>
                        </select>
                        <span class="description"><?php _e('Add Login form to wizard', 'woocommerce-multistep-checkout') ?></span></td>
                </tr>

                <tr>
                    <td width="200"><?php _e('Add Coupon to Wizard', 'woocommerce-multistep-checkout') ?></td>
                    <td><select name="wmc_add_coupon_form">
                            <option value="true" <?php selected(get_option('wmc_add_coupon_form'), 'true', true); ?>><?php _e('Yes', 'woocommerce-multistep-checkout') ?></option>                                                        
                            <option value="false" <?php selected(get_option('wmc_add_coupon_form'), 'false', true); ?>><?php _e('No', 'woocommerce-multistep-checkout') ?></option>
                        </select>
                        <span class="description"><?php _e('Add Coupon form to wizard', 'woocommerce-multistep-checkout') ?></span></td>
                </tr>

                <tr>
                    <td width="200"><?php _e('Combine order Infomation and Payment tabs', 'woocommerce-multistep-checkout') ?></td>
                    <td><select name="wmc_merge_order_payment_tabs">
                            <option value="true" <?php selected(get_option('wmc_merge_order_payment_tabs'), 'true', true); ?>><?php _e('Yes', 'woocommerce-multistep-checkout') ?></option>
                            <option value="false" <?php selected(get_option('wmc_merge_order_payment_tabs'), 'false', true); ?>><?php _e('No', 'woocommerce-multistep-checkout') ?></option>
                        </select>
                        <span class="description"><?php _e('If you want to combine Order information and Payment tabs then set this to "Yes"', 'woocommerce-multistep-checkout') ?></span></td>
                </tr>

                <tr>
                    <td width="200"><?php _e('Tabs Color', 'woocommerce-multistep-checkout') ?></td>
                    <td><input name="wmc_tabs_color" id="tabs_color" type="text" value="<?php echo get_option('wmc_tabs_color') ?>" class="regular-text" /><br /><span class="description"><?php _e('Select background color for active tabs', 'woocommerce-multistep-checkout') ?></span></td>
                </tr>

                <tr>
                    <td><?php _e('Tabs Color for inactive tab', 'woocommerce-multistep-checkout') ?></td>
                    <td><input name="wmc_inactive_tabs_color" id="inactive_tabs_color" type="text" value="<?php echo get_option('wmc_inactive_tabs_color') ?>" class="regular-text" /><br /><span class="description"><?php _e('Select background color for inactive tabs', 'woocommerce-multistep-checkout') ?></span></td>
                </tr>

                <tr>
                    <td><?php _e('Completed tabs color', 'woocommerce-multistep-checkout') ?></td>
                    <td><input name="wmc_completed_tabs_color" id="completed_tabs_color" type="text" value="<?php echo get_option('wmc_completed_tabs_color') ?>" class="regular-text" /><br /><span class="description"><?php _e('Select background color for completed tabs', 'woocommerce-multistep-checkout') ?></span></td>
                </tr>

                <tr>
                    <td><?php _e('Active Tabs Font Color', 'woocommerce-multistep-checkout') ?></td>
                    <td><input name="wmc_font_color" id="font_color" type="text" value="<?php echo get_option('wmc_font_color') ?>" class="regular-text" /><br />
                        <span class="description"><?php _e('Select Tabs Font Color', '') ?></span></td>
                </tr>

                <tr>
                    <td><?php _e('Buttons Color', 'woocommerce-multistep-checkout') ?></td>
                    <td><input name="wmc_buttons_bg_color" id="buttons_bg_color" type="text" value="<?php echo get_option('wmc_buttons_bg_color') ?>" class="regular-text" /><br />
                        <span class="description"><?php _e('Next/Previous/Login buttons color', 'woocommerce-multistep-checkout') ?></span></td>
                </tr>

                <tr>
                    <td><?php _e('Buttons Font color', 'woocommerce-multistep-checkout') ?></td>
                    <td><input name="wmc_buttons_font_color" id="buttons_font_color" type="text" value="<?php echo get_option('wmc_buttons_font_color') ?>" class="regular-text" /><br />
                        <span class="description"><?php _e('Next/Previous/Login/Coupon button font color', 'woocommerce-multistep-checkout') ?></span></td>
                </tr>


                <tr>
                    <td><?php _e('Checkout form Labels', 'woocommerce-multistep-checkout') ?></td>
                    <td><input name="wmc_form_labels_color" id="form_labels_color" type="text" value="<?php echo get_option('wmc_form_labels_color') ?>" class="regular-text" /><br />
                        <span class="description"><?php _e('Set Form Labels color', 'woocommerce-multistep-checkout') ?></span></td>
                </tr>


                <tr>
                    <td><?php _e('Animation', 'woocommerce-multistep-checkout') ?></td>
                    <td><select name="wmc_animation">
                            <option value="fade" <?php selected(get_option('wmc_animation'), 'fade', true); ?>><?php _e('Fade', 'woocommerce-multistep-checkout') ?></option>
                            <option value="slide" <?php selected(get_option('wmc_animation'), 'slide', true); ?>><?php _e('Slide', 'woocommerce-multistep-checkout') ?></option>
                        </select>
                        <span class="description"><?php _e('Select the type of animation', 'woocommerce-multistep-checkout') ?></span></td>
                </tr>

                <?php
                $wizard_type = get_option('wmc_wizard_type');
                if ($wizard_type == '' || $wizard_type == 'classic'):
                    ?>
                    <tr>
                        <td><?php _e('Orientation', 'woocommerce-multistep-checkout') ?></td>
                        <td><select name="wmc_orientation">
                                <option value="horizontal" <?php selected(get_option('wmc_orientation'), 'horizontal', true); ?>><?php _e('Horizontal', 'woocommerce-multistep-checkout') ?></option>
                                <option value="vertical" <?php selected(get_option('wmc_orientation'), 'vertical', true); ?>><?php _e('Vertical', 'woocommerce-multistep-checkout') ?></option>
                            </select>
                            <span class="description"><?php _e('Select Tabs Orientation', 'woocommerce-multistep-checkout') ?></span></td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td><?php _e('Enable Pagination', 'woocommerce-multistep-checkout') ?></td>
                    <td><select name="wmc_enable_pagination">
                            <option value="true" <?php selected(get_option('wmc_enable_pagination'), 'true', true); ?>><?php _e('Yes', 'woocommerce-multistep-checkout') ?></option>
                            <option value="false" <?php selected(get_option('wmc_enable_pagination'), 'false', true); ?>><?php _e('No', 'woocommerce-multistep-checkout') ?></option>
                        </select>
                        <span class="description"><?php _e('Enable/Disable Pagination', 'woocommerce-multistep-checkout') ?></span></td>
                </tr>

                <tr>
                    <td><?php _e('Next Button', 'woocommerce-multistep-checkout') ?></td>
                    <td>
                        <input type="text" name="wmc_btn_next" value="<?php echo get_option('wmc_btn_next') ? get_option('wmc_btn_next') : "Next" ?>" />
                        <span class="description"><?php _e('Enter text for Next button', 'woocommerce-multistep-checkout') ?></span></td>
                </tr>

                <tr>
                    <td><?php _e('Previous Button', 'woocommerce-multistep-checkout') ?></td>
                    <td>
                        <input type="text" name="wmc_btn_prev" value="<?php echo get_option('wmc_btn_prev') ? get_option('wmc_btn_prev') : "Previous" ?>" />
                        <span class="description"><?php _e('Enter text for Previous button', 'woocommerce-multistep-checkout') ?></span></td>
                </tr>

                <tr>
                    <td><?php _e('Place Order Button', 'woocommerce-multistep-checkout') ?></td>
                    <td>
                        <input type="text" name="wmc_btn_finish" value="<?php echo get_option('wmc_btn_finish') ? get_option('wmc_btn_finish') : "Place Order" ?>" />
                        <span class="description"><?php _e('Enter text for Place Order Button', 'woocommerce-multistep-checkout') ?></span></td>
                </tr>

                <tr>
                    <td><?php _e('No Account Button', 'woocommerce-multistep-checkout') ?></td>
                    <td>
                        <input type="text" name="wmc_no_account_btn" value="<?php echo get_option('wmc_no_account_btn') ? stripslashes(get_option('wmc_no_account_btn')) : "I don't have an account" ?>" />
                        <span class="description"><?php _e('Enter text for No Account Button', 'woocommerce-multistep-checkout') ?></span></td>
                </tr>


                <tr>
                    <td><?php _e('Remove Numbers', 'woocommerce-multistep-checkout') ?></td>
                    <td><select name="wmc_remove_numbers">
                            <option value="false" <?php selected(get_option('wmc_remove_numbers'), 'false', true); ?>><?php _e('No', 'woocommerce-multistep-checkout') ?></option>
                            <option value="true" <?php selected(get_option('wmc_remove_numbers'), 'true', true); ?>><?php _e('Yes', 'woocommerce-multistep-checkout') ?></option>
                        </select>
                        <span class="description"><?php _e('Remove Numbers From Steps', 'woocommerce-multistep-checkout') ?></span></td>
                </tr>

                <tr>
                    <td colspan="2"><h3 style="margin: 0;padding: 0"><?php _e('Tabs Labels', 'woocommerce-multistep-checkout') ?></h3></td>
                </tr>
                
                <tr>
                    <td><?php _e('Coupon', 'woocommerce-multistep-checkout') ?></td>
                    <td>
                        <input type="text" name="wmc_coupon_label" value="<?php echo get_option('wmc_coupon_label') ? get_option('wmc_coupon_label') : "Coupon" ?>" />
                        <span class="description"><?php _e('Enter text for Coupon label', 'woocommerce-multistep-checkout') ?></span></td>
                </tr>
                
                <tr>
                    <td><?php _e('Billing', 'woocommerce-multistep-checkout') ?></td>
                    <td>
                        <input type="text" name="wmc_billing_label" value="<?php echo get_option('wmc_billing_label') ? get_option('wmc_billing_label') : "Billing" ?>" />
                        <span class="description"><?php _e('Enter text for Billing label', 'woocommerce-multistep-checkout') ?></span></td>
                </tr>

                <tr>
                    <td><?php _e('Shipping', 'woocommerce-multistep-checkout') ?></td>
                    <td>
                        <input type="text" name="wmc_shipping_label" value="<?php echo get_option('wmc_shipping_label') ? get_option('wmc_shipping_label') : "Shipping" ?>" />
                        <span class="description"><?php _e('Enter text for Shipping label', 'woocommerce-multistep-checkout') ?></span></td>
                </tr>

                <tr>
                    <td><?php _e('Order Information', 'woocommerce-multistep-checkout') ?></td>
                    <td>
                        <input type="text" name="wmc_orderinfo_label" value="<?php echo get_option('wmc_orderinfo_label') ? get_option('wmc_orderinfo_label') : "Order Information" ?>" />
                        <span class="description"><?php _e('Enter text for Order Information label', 'woocommerce-multistep-checkout') ?></span></td>
                </tr>

                <tr>
                    <td><?php _e('Payment Info', 'woocommerce-multistep-checkout') ?></td>
                    <td>
                        <input type="text" name="wmc_paymentinfo_label" value="<?php echo get_option('wmc_paymentinfo_label') ? get_option('wmc_paymentinfo_label') : "Payment Info" ?>" />
                        <span class="description"><?php _e('Enter text for Payment Info label', 'woocommerce-multistep-checkout') ?></span></td>
                </tr>

                <tr>
                    <td colspan="2"><h3 style="margin: 0;padding: 0"><?php _e('Error Messages', 'woocommerce-multistep-checkout') ?></h3></td>
                </tr>
                <tr>
                    <td><?php _e('Empty Fields', 'woocommerce-multistep-checkout') ?></td>
                    <td>
                        <input type="text" name="wmc_empty_error" value="<?php echo get_option('wmc_empty_error') ? get_option('wmc_empty_error') : "This field is required" ?>" />
                        <span class="description"><?php _e('Enter text for empty field error', 'woocommerce-multistep-checkout') ?></span></td>
                </tr>

                <tr>
                    <td><?php _e('Invalid E-mail', 'woocommerce-multistep-checkout') ?></td>
                    <td>
                        <input type="text" name="wmc_email_error" value="<?php echo get_option('wmc_email_error') ? get_option('wmc_email_error') : "Please enter a valid email address" ?>" />
                        <span class="description"><?php _e('Enter text for invalid email error', 'woocommerce-multistep-checkout') ?></span></td>
                </tr>

                <tr>
                    <td><?php _e('Invalid Phone', 'woocommerce-multistep-checkout') ?></td>
                    <td>
                        <input type="text" name="wmc_phone_error" value="<?php echo get_option('wmc_phone_error') ? get_option('wmc_phone_error') : "Invalid phone number" ?>" />
                        <span class="description"><?php _e('Enter text for invalid phone number error', 'woocommerce-multistep-checkout') ?></span></td>
                </tr>

                <tr>
                    <td><?php _e('Restore Plugin Defaults', 'woocommerce-multistep-checkout') ?></td>
                    <td><input type="checkbox" name="wmc_restore_default" value="yes" /></td>
                </tr>

            </table>


            <p class="submit">
                <input type="submit" name="submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
            </p>

        </form>

    </div>        

    <?php
}

add_action('woocommerce_checkout_order_review', 'update_shipping_info');

function update_shipping_info() {
    ?>
    <?php if (get_option('wmc_merge_order_payment_tabs') != "true" || get_option('wmc_merge_order_payment_tabs') == ""): ?>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                jQuery(".shipping-tab .shop_table").empty();
                jQuery(".shop_table").appendTo(".shipping-tab");

            })
        </script>
        <?php
    endif;
}

add_action('after_setup_theme', 'avada_checkoutfix');

function avada_checkoutfix() {
    if (function_exists('avada_woocommerce_checkout_after_customer_details')) {
        remove_action('woocommerce_checkout_after_customer_details', 'avada_woocommerce_checkout_after_customer_details');
    }

    if (function_exists('avada_woocommerce_checkout_before_customer_details')) {
        remove_action('woocommerce_checkout_before_customer_details', 'avada_woocommerce_checkout_before_customer_details');
    }
}

/* * * Add login tow wizard * */
$add_login = get_option('wmc_add_login_form');
if ($add_login == 'true' || $add_login == "") {
    add_action('after_setup_theme', 'wmc_add_checkout_login_form');

    function wmc_add_checkout_login_form() {
        if (!has_action('woocommerce_before_checkout_form')) {
            add_action('woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10);
        }
    }

//add login form to wizard
    add_action('woocommerce_multistep_checkout_before', 'add_login_to_wizard');

    function add_login_to_wizard() {
        if (is_user_logged_in() || 'no' === get_option('woocommerce_enable_checkout_login_reminder')) {
            return;
        }
        ?>
        <script>
            jQuery(function () {
                jQuery(".woocommerce-info a.showlogin").parent().detach();
                jQuery("form.login").appendTo('.login-step');
                jQuery(".login-step form.login").show();
            });</script>    
        <h1 class="title-login-wizard"><?php _e('Login', 'woocommerce') ?></h1>
        <div class="login-step">


        </div>
        <?php
    }

}

/* * ***************Add Coupon form to wizard * */
$add_coupon_form = get_option('wmc_add_coupon_form');
if ($add_coupon_form == 'true' || $add_login == "") {
    /*     * Check if coupons are enabled. */
    if (get_option('woocommerce_enable_coupons') != "yes") {
        return;
    }
    add_action('woocommerce_multistep_checkout_before', 'wmc_add_coupon_form', 20);

    function wmc_add_coupon_form() {
        ?>
        <script>
            jQuery(function () {
                jQuery(".woocommerce-info a.showcoupon").parent().detach();
                jQuery("form.checkout_coupon").appendTo('.coupon-step');
                jQuery(".coupon-step form.checkout_coupon").show();
            });</script>
        <style>
            form.checkout_coupon{
                display: block !important
            }
        </style>

        <h1 class="title-coupon-wizard"><?php echo get_option('wmc_coupon_label') ? __(get_option('wmc_coupon_label'), 'woocommerce-multistep-checkout') : __('Coupon', 'woocommerce-multistep-checkout'); ?></h1>
        <div class="coupon-step">


        </div>
    <?php
    }

}

function isAuthorizedUser() {
    return get_current_user_id();
}

add_action('wp_ajax_valid_post_code', 'wmc_validate_post_code');
add_action('wp_ajax_nopriv_valid_post_code', 'wmc_validate_post_code');

//validate PostCode
function wmc_validate_post_code() {
    $country = $_POST['country'];

    $postCode = $_POST['postCode'];
    echo WC_Validation::is_postcode($postCode, $country);

    exit();
}

add_action('wp_ajax_validate_phone', 'wmc_validate_phone_number');
add_action('wp_ajax_nopriv_validate_phone', 'wmc_validate_phone_number');

function wmc_validate_phone_number() {
    $phone = $_POST['phone'];
    echo WC_Validation::is_phone($phone);

    exit();
}

//Handle Login form

add_action('wp_ajax_wmc_check_user_login', 'wmc_authenticate_user');
add_action('wp_ajax_nopriv_wmc_check_user_login', 'wmc_authenticate_user');

function wmc_authenticate_user() {
    check_ajax_referer('wmc-login-nonce');
    if (is_email($_POST['username']) && apply_filters('woocommerce_get_username_from_email', true)) {
        $user = get_user_by('email', $_POST['username']);

        if (isset($user->user_login)) {
            $creds['user_login'] = $user->user_login;
        }
    } else {
        $creds['user_login'] = $_POST['username'];
    }

    $creds['user_password'] = $_POST['password'];
    $creds['remember'] = isset($_POST['rememberme']);
    $secure_cookie = is_ssl() ? true : false;
    $user = wp_signon(apply_filters('woocommerce_login_credentials', $creds), $secure_cookie);


    if (wmc_is_eruser_authenticate($user)) {
        echo '<p class="error-msg">' . __('Incorrect username/password.', 'woocommerce-multistep-checkout') . ' </p>';
    } else {
        echo 'successfully';
    }

    exit();
}

function wmc_is_eruser_authenticate($result) {
    return is_wp_error($result);
}

/* * ************* Add plugin info to the plugin listing page * */
if (isset($_GET['page']) && $_GET['page'] == "wcmultichekout") {
    add_filter('admin_footer_text', 'wmc_admin_footer_text');

    function wmc_admin_footer_text() {

        echo sprintf(__('If you like <strong>WooCommerce MultiStep Checkout</strong> please leave us a %s&#9733;&#9733;&#9733;&#9733;&#9733;%s rating.'), '<a href="http://codecanyon.net/item/woocommerce-multistep-checkout/8125187" target="_blank" class="wc-rating-link" data-rated="' . __('Thanks :)', 'woocommerce') . '">', '</a>');
    }

}

add_filter('plugin_row_meta', 'wmc_Register_Plugins_Links', 10, 2);

function wmc_Register_Plugins_Links($links, $file) {
    $base = plugin_basename(__FILE__);
    if ($file == $base) {
        $links[] = '<a href="http://woocommerce-multistep-checkout.mubashir09.com/documentation/">' . __('Documentation') . '</a>';
        $links[] = '<a href="http://woocommerce-multistep-checkout.mubashir09.com/faq/">' . __('FAQ') . '</a>';
    }
    return $links;
}

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'wmc_link_action_on_plugin');

function wmc_link_action_on_plugin($links) {

    return array_merge(array('settings' => '<a href="' . admin_url('admin.php?page=wcmultichekout') . '">' . __('Settings', 'domain') . '</a>'), $links);
}
