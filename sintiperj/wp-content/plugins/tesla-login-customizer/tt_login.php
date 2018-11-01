<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * Plugin Name: Tesla Login Customizer
 * Plugin URI: https://teslathemes.com/wp-plugins/tesla-login-customizer/
 * Description: Customize your login/register/forgot password page with ease.
 * Version: 1.3.4
 * Author: TeslaThemes
 * Author URI: http://teslathemes.com/
 * License: GPL2
 * Domain Path: /languages
 * Text Domain: tt_login
 */

use tt_login\TT_Plugin;

/*init plugin on plugins_loaded action*/
if( is_admin( ) || tt_is_login_page( ) )
    add_action( 'plugins_loaded', 'tt_login_init' );

if(!function_exists('tt_login_init'));
function tt_login_init()
{
    require_once 'autoloader.php';
    /*Creating an instance of TT_Plugin , main object*/
    $P = new TT_Plugin('Tesla Login', 'tt_login');

    /*Creating Menus/Pages*/
    $main_page = $P->add_page(array('Tesla Login Customizer', 'Tesla Login'), null, 'dashicons-lock');
    //$submenu_page   = $P->add_subpage( array('SubTitle', 'SubMenu Title', 'manage_options','tesla_login_submenu') , $main_page );

    /*Adding CSS files to pages*/
    $main_page->add_style(array('main-admin-css', $P->get_plugin_uri() . '/css/admin/admin-style.css'));

    /*Adding JS files to pages*/
    $main_page->add_script(array('main-admin-js', $P->get_plugin_uri() . '/js/admin/admin-script.js'));

    /*Adding view to page*/
    $main_page->set_page_view('main');
    //$submenu_page->set_page_view('subpage');

    /*Adding tabs*/
    $general_tab = $main_page->add_tab(__('General', 'tt_login'), null, 'dashicons-admin-generic');
    $templates_tab = $main_page->add_tab(__('Templates', 'tt_login'), null, 'dashicons-images-alt2');
    $logo_tab = $main_page->add_tab(__('Logo', 'tt_login'), null, 'dashicons-admin-appearance');
    $form_tab = $main_page->add_tab(__('Form', 'tt_login'), null, 'dashicons-feedback');
    //$social_tab     = $main_page->add_tab( 'Socials' , 'Nice tab for social settings' );
    $advanced_tab = $main_page->add_tab(__('Advanced', 'tt_login'), null, 'dashicons-editor-code');

    /* Registering options for page depending on which tab needed */
    //==========================General Tab============================================//
    $general_tab->add_option(array('primary_color', __('Primary Color', 'tt_login'), 'color', false, __('Affects various elements across the page depending on the selected template.', 'tt_login')));
    $general_tab->add_option(array('bg_image', __('Background Image', 'tt_login'), 'image', null,
        __('Pick image for login page background.', 'tt_login') .
        ' <span class="dashicons dashicons-info"></span>' . __('Accepts external link.', 'tt_login')));
    $general_tab->add_option(array('bg_image_repeat', __('Background Image Repeat', 'tt_login'), 'select', 'no-repeat', __('Provide image for login page background', 'tt_login'),
        array(
            'no-repeat' => __('No Repeat', 'tt_login'),
            'repeat' => __('Repeat', 'tt_login'),
            'repeat-x' => __('Repeat-X', 'tt_login'),
            'repeat-y' => __('Repeat-Y', 'tt_login'),
            'round' => __('Round', 'tt_login'),
            'space' => __('Space', 'tt_login')
        )
    ), array(), array('show' => array('bg_image' => 1)));
    $general_tab->add_option(array(
        'bg_image_size_type',
        __('Background Size Type', 'tt_login'),
        'select',
        'cover',
        __('How the image should fill the Background area', 'tt_login'),
        array('contain' => __('Contain', 'tt_login'), 'cover' => __('Cover', 'tt_login'), 'initial' => __('Initial', 'tt_login'))
    ), array(), array('show' => array('bg_image' => 1)));

    $general_tab->add_option(array('bg_color', __('Background Color', 'tt_login'), 'color'));
    $general_tab->add_option(array('font_family', __('Font', 'tt_login'), 'font'));
    $general_tab->add_option(array('font_variations', __('Font Variations', 'tt_login'), 'select_multiple',null,null,
        array(
            '100' => '100','100italic'=>'100 Italic','300'=>'300','400'=>'400','italic'=>'Italic','700'=>'700',
            '700italic'=>'700 Italic', '900'=>'900','900italic'=>'900 Italic'
        ), __('Select variations')
    ),array(),array('show' => array('font_family' => 1)));
    $general_tab->add_option(array('font_subset', __('Font Subset', 'tt_login'), 'select_multiple', null, null,
        array(
            'latin' => __('Latin', 'tt_login'), 'latin-ext' => __('Latin Ext', 'tt_login'), 'cyrillic' => __('Cyrillic', 'tt_login'), 'cyrillic-ext' => __('Cyrillic Ext', 'tt_login'),
            'vietnamese' => __('Vietnamese', 'tt_login'), 'greek' => __('Greek', 'tt_login'), 'greek-ext' => __('Greek Ext', 'tt_login')
        ),
        __('Select Subsets', 'tt_login')), array(), array('show' => array('font_family' => 1)));
    $general_tab->add_option(array('font_size', __('Font Size', 'tt_login'), 'number'), array('min' => 10, 'max' => '100', 'label' => 'px'));
    $general_tab->add_option(array('font_color', __('Font Color', 'tt_login'), 'color'));
    $general_tab->add_option(array('redirect_login', __('Redirect after Login', 'tt_login'), 'url', false, false, false, 'http://yourdomain.com/custom-page/'));
    $general_tab->add_option(array('redirect_register', __('Redirect after Register', 'tt_login'), 'url', false, false, false, 'http://yourdomain.com/custom-page/'));
    $general_tab->add_option(array('custom_login_url', __('Custom Login Url', 'tt_login'), 'url', false, __('If you changed the default login url & our plugin doesn\'t automatically pick it up insert your custom url here','tt_login'), false, 'http://yourdomain.com/?your-custom-login-url'));

    //==========================Templates Tab============================================//
    $templates_tab->hide_submit = true;
    $templates_tab->add_option(array('template', null, 'template', 'default', __('Scheme for colors, layout and effects.', 'tt_login')
        . ' <span class="dashicons dashicons-warning"></span><b>' .
        __('Changing this may overwrite your custom settings , but you will have a chance to revert them back', 'tt_login') . '</b>.',
        array(
            'default' => array(__('Default', 'tt_login')),
            'mercury' => array(__('Mercury', 'tt_login'), null, __('Premium', 'tt_login')),
            'venus' => array(__('Venus', 'tt_login')),
            'terra' => array(__('Terra', 'tt_login')),
            'mars' => array(__('Mars', 'tt_login')),
            'jupiter' => array(__('Jupiter', 'tt_login'))
        )
    ), array('alert_message' => __('Switching templates changes the required settings, but you will get a chance to revert back to current settings. Are you sure you want to do it ?', 'tt_login'))
    );
    //==========================Logo Tab============================================//
    $logo_tab->add_option(array('logo_hide', __('Hide Logo', 'tt_login'), 'checkbox', false, '<span class="dashicons dashicons-info"></span>' . __('Hides default WP logo too.', 'tt_login')),
        array('label' => __('Don\'t show any logo image at all.', 'tt_login')));
    $logo_tab->add_option(array('logo_image', __('Logo Image', 'tt_login'), 'image'), array(), array('hide' => array('logo_hide' => 1)));
    $logo_tab->add_option(array(
        'logo_bg_size_type',
        __('Logo Size Type', 'tt_login'),
        'select',
        'cover',
        __('How the image should fill the logo area', 'tt_login'),
        array('contain' => __('Contain', 'tt_login'), 'cover' => __('Cover', 'tt_login'), 'initial' => __('Initial', 'tt_login'))
    ), array(), array('show' => array('logo_image' => 1), 'hide' => array('logo_hide' => 1)));
    $logo_tab->add_option(array('logo_width', __('Logo Width', 'tt_login'), 'number'), array('min' => 20, 'max' => 2000, 'label' => 'px'), array('hide' => array('logo_hide' => 1)));
    $logo_tab->add_option(array('logo_height', __('Logo Height', 'tt_login'), 'number'), array('min' => 20, 'max' => 2000, 'label' => 'px'), array('hide' => array('logo_hide' => 1)));
    $logo_tab->add_option(array('logo_link', __('Logo Link', 'tt_login'), 'url', false, false, false, 'http://yourdomain.com/'), array(), array('hide' => array('logo_hide' => 1)));
    $logo_tab->add_option(array('logo_title', __('Logo image title', 'tt_login'), 'text', null, __('Title of the image that appears on logo hover', 'tt_login'), false, __('Our Logo', 'tt_login')), array(), array('hide' => array('logo_hide' => 1)));

    //==========================Form Tab============================================//
    $form_tab->add_option(array('form_placement', __('Form Position', 'tt_login'), 'select', null, __('Placement of the form on the login screen', 'tt_login'),
        array(
            '' => __('Select position', 'tt_login'), 'center' => __('Center', 'tt_login'), 'left' => __('Left', 'tt_login'), 'right' => __('Right', 'tt_login'), 'top' => __('Top', 'tt_login'), 'bottom' => __('Bottom', 'tt_login'), 'top-left' => __('Top Left', 'tt_login'),
            'top-right' => __('Top Right', 'tt_login'), 'bottom-left' => __('Bottom Left', 'tt_login'), 'bottom-right' => __('Bottom Right', 'tt_login')
        ),
    ));
    $form_tab->add_option(array('form_heading', __('Form Heading', 'tt_login'), 'text', false, __('Title on the top of the form', 'tt_login')));
    $form_tab->add_option(array('form_bg_image', __('Form BG Image', 'tt_login'), 'image'));
    $form_tab->add_option(array(
        'form_bg_size_type',
        __('Form BG Size Type', 'tt_login'),
        'select',
        'cover',
        __('How the image should fill the form area', 'tt_login'),
        array('contain' => __('Contain', 'tt_login'), 'cover' => __('Cover', 'tt_login'), 'inherit' => __('Inherit', 'tt_login'), 'initial' => __('Initial', 'tt_login'))
    ), array(), array('show' => array('form_bg_image' => 1)));
    $form_tab->add_option(array('form_bg_image_repeat', __('Form BG Image Repeat', 'tt_login'), 'select', 'no-repeat', null,
        array(
            'inherit' => __('Inherit', 'tt_login'),
            'initial' => __('Initial', 'tt_login'),
            'no-repeat' => __('No Repeat', 'tt_login'),
            'repeat' => __('Repeat', 'tt_login'),
            'repeat-x' => __('Repeat-X', 'tt_login'),
            'repeat-y' => __('Repeat-Y', 'tt_login'),
            'round' => __('Round', 'tt_login'),
            'space' => __('Space', 'tt_login')
        )
    ), array(), array('show' => array('form_bg_image' => 1)));
    $form_tab->add_option(array('form_bg_color', __('Form BG Color', 'tt_login'), 'color'));
    $form_tab->add_option(array('form_padding', __('Form Padding', 'tt_login'), 'number_group', null, __('Set form\'s padding in', 'tt_login') . ' <em>px</em>', null, '10', array('tt-number-group-4')),
        array('min' => '0', 'max' => 500, 'label' => 'px',
            'group' => array(
                'top',
                'right',
                'bottom',
                'left'
            )
        ));
    $form_tab->add_option(array('form_no_shake', __('Form Shake'), 'checkbox', null, __('Login form will not shake anymore on errors', 'tt_login')), array('label' => 'Disable'));
    $form_tab->add_option(array('form_animation_in', __('Form animation in', 'tt_login'), 'select', null, null,
        array(
            '' => __('No In Animation', 'tt_login'),
            'bounce' => __('bounce', 'tt_login'),
            'flash' => __('flash', 'tt_login'),
            'pulse' => __('pulse', 'tt_login'),
            'rubberBand' => __('rubberBand', 'tt_login'),
            'shake' => __('shake', 'tt_login'),
            'swing' => __('swing', 'tt_login'),
            'tada' => __('tada', 'tt_login'),
            'wobble' => __('wobble', 'tt_login'),
            'jello' => __('jello', 'tt_login'),
            'bounceIn' => __('bounceIn', 'tt_login'),
            'bounceInDown' => __('bounceInDown', 'tt_login'),
            'bounceInLeft' => __('bounceInLeft', 'tt_login'),
            'bounceInRight' => __('bounceInRight', 'tt_login'),
            'bounceInUp' => __('bounceInUp', 'tt_login'),
            'fadeIn' => __('fadeIn', 'tt_login'),
            'fadeInDown' => __('fadeInDown', 'tt_login'),
            'fadeInDownBig' => __('fadeInDownBig', 'tt_login'),
            'fadeInLeft' => __('fadeInLeft', 'tt_login'),
            'fadeInLeftBig' => __('fadeInLeftBig', 'tt_login'),
            'fadeInRight' => __('fadeInRight', 'tt_login'),
            'fadeInRightBig' => __('fadeInRightBig', 'tt_login'),
            'fadeInUp' => __('fadeInUp', 'tt_login'),
            'fadeInUpBig' => __('fadeInUpBig', 'tt_login'),
            'flipInX' => __('flipInX', 'tt_login'),
            'flipInY' => __('flipInY', 'tt_login'),
            'lightSpeedIn' => __('lightSpeedIn', 'tt_login'),
            'rotateIn' => __('rotateIn', 'tt_login'),
            'rotateInDownLeft' => __('rotateInDownLeft', 'tt_login'),
            'rotateInDownRight' => __('rotateInDownRight', 'tt_login'),
            'rotateInUpLeft' => __('rotateInUpLeft', 'tt_login'),
            'rotateInUpRight' => __('rotateInUpRight', 'tt_login'),
            'hinge' => __('hinge', 'tt_login'),
            'rollIn' => __('rollIn', 'tt_login'),
            'zoomIn' => __('zoomIn', 'tt_login'),
            'zoomInDown' => __('zoomInDown', 'tt_login'),
            'zoomInLeft' => __('zoomInLeft', 'tt_login'),
            'zoomInRight' => __('zoomInRight', 'tt_login'),
            'zoomInUp' => __('zoomInUp', 'tt_login'),
            'slideInDown' => __('slideInDown', 'tt_login'),
            'slideInLeft' => __('slideInLeft', 'tt_login'),
            'slideInRight' => __('slideInRight', 'tt_login'),
            'slideInUp' => __('slideInUp', 'tt_login'),
        )
    ));
    $form_tab->add_option(array('form_animation_out', __('Form animation Out', 'tt_login'), 'select', null, __('Animation on form submission event', 'tt_login'),
        array(
            '' => __('No Out Animation', 'tt_login'),
            'bounceOut' => __('bounceOut', 'tt_login'),
            'bounceOutDown' => __('bounceOutDown', 'tt_login'),
            'bounceOutLeft' => __('bounceOutLeft', 'tt_login'),
            'bounceOutRight' => __('bounceOutRight', 'tt_login'),
            'bounceOutUp' => __('bounceOutUp', 'tt_login'),
            'fadeOut' => __('fadeOut', 'tt_login'),
            'fadeOutDown' => __('fadeOutDown', 'tt_login'),
            'fadeOutDownBig' => __('fadeOutDownBig', 'tt_login'),
            'fadeOutLeft' => __('fadeOutLeft', 'tt_login'),
            'fadeOutLeftBig' => __('fadeOutLeftBig', 'tt_login'),
            'fadeOutRight' => __('fadeOutRight', 'tt_login'),
            'fadeOutRightBig' => __('fadeOutRightBig', 'tt_login'),
            'fadeOutUp' => __('fadeOutUp', 'tt_login'),
            'fadeOutUpBig' => __('fadeOutUpBig', 'tt_login'),
            'flipOutX' => __('flipOutX', 'tt_login'),
            'flipOutY' => __('flipOutY', 'tt_login'),
            'lightSpeedOut' => __('lightSpeedOut', 'tt_login'),
            'rotateOut' => __('rotateOut', 'tt_login'),
            'rotateOutDownLeft' => __('rotateOutDownLeft', 'tt_login'),
            'rotateOutDownRight' => __('rotateOutDownRight', 'tt_login'),
            'rotateOutUpLeft' => __('rotateOutUpLeft', 'tt_login'),
            'rotateOutUpRight' => __('rotateOutUpRight', 'tt_login'),
            'rollOut' => __('rollOut', 'tt_login'),
            'zoomOut' => __('zoomOut', 'tt_login'),
            'zoomOutDown' => __('zoomOutDown', 'tt_login'),
            'zoomOutLeft' => __('zoomOutLeft', 'tt_login'),
            'zoomOutRight' => __('zoomOutRight', 'tt_login'),
            'zoomOutUp' => __('zoomOutUp', 'tt_login'),
            'slideOutDown' => __('slideOutDown', 'tt_login'),
            'slideOutLeft' => __('slideOutLeft', 'tt_login'),
            'slideOutRight' => __('slideOutRight', 'tt_login'),
            'slideOutUp' => __('slideOutUp', 'tt_login'),
        )
    ));
    $form_tab->add_option(array('form_animation_error', __('Form animation Error', 'tt_login'), 'select', null, __('Animation on form error event', 'tt_login'),
        array(
            '' => __('No Error Animation', 'tt_login'),
            'bounce' => __('bounce', 'tt_login'),
            'flash' => __('flash', 'tt_login'),
            'pulse' => __('pulse', 'tt_login'),
            'rubberBand' => __('rubberBand', 'tt_login'),
            'shake' => __('shake', 'tt_login'),
            'swing' => __('swing', 'tt_login'),
            'tada' => __('tada', 'tt_login'),
            'wobble' => __('wobble', 'tt_login'),
            'jello' => __('jello', 'tt_login'),
        )
    ));
    $form_tab->add_option(array('form_button_color', __('Button Text Color', 'tt_login'), 'color'));
    $form_tab->add_option(array('form_button_bg_color', __('Button BG Color', 'tt_login'), 'color'));

    $form_tab->add_option(array('form_captcha', __('reCAPTCHA', 'tt_login'), 'checkbox'), array('label' => 'Enable'));
    $form_tab->add_option(array('form_captcha_key', __('reCAPTCHA Site Key', 'tt_login'), 'text', null,
        __('Get it from ', 'tt_login') . "<a target='_blank' href='https://www.google.com/recaptcha/admin'>Google</a>"), array(),
        array('show' => array('form_captcha' => 1))
    );
    $form_tab->add_option(array('form_captcha_theme', __('reCAPTCHA Theme', 'tt_login'), 'radio', 'light', null,
        array(
            'light' => __('Light', 'tt_login'),
            'dark' => __('Dark', 'tt_login')
        )
    ), array(),
        array('show' => array('form_captcha' => 1))
    );
    $form_tab->add_option(array('form_captcha_type', __('reCaptcha Challenge Type', 'tt_login'), 'radio', 'image', null,
        array(
            'image' => __('Image', 'tt_login'),
            'audio' => __('Audio', 'tt_login')
        )
    ), array(),
        array('show' => array('form_captcha' => 1))
    );
	$form_tab->add_option(array('form_captcha_lang', __('reCaptcha Language', 'tt_login'), 'text', null,
		__('Insert language code. See list of language abbreviations in ', 'tt_login') . "<a target='_blank' href='https://developers.google.com/recaptcha/docs/language'>Google Docs</a>"), array(),
		array('show' => array('form_captcha' => 1))
	);


    /*
    //==========================Social Tab============================================//
    $social_tab->add_option(array('form_social','Use Social Login','checkbox'));
    $social_tab->add_option(array('form_fb','FB','text',false,false,false,false,array('hidden')),array(),
        array(
            'show' => array( 'form_social' => 1 , 'form_template' => 'material' ),
            'hide' => array( 'form_social' => 0 , 'form_template' => 'material' )
        )
    );

    $social_tab->add_option(array('socials','Enable Socials Login','checkbox'));
    $social_tab->add_option(array('social_fb','Facebook','checkbox'),array(),array('show' => array( 'socials' => 1 ) ));
    $social_tab->add_option(array('social_fb_api','FB API Key','text'),array(),array('show' => array( 'social_fb' => 1 ) ));
    $social_tab->add_option(array('social_gplus','Google+','checkbox'),array(),array('show' => array( 'socials' => 1 ) ));
    $social_tab->add_option(array('social_gplus_api','Google API Key','text'),array(),array('show' => array( 'social_gplus' => 1 ) ));*/

    //==========================Advanced Tab============================================//
    $advanced_tab->add_option(array('custom_css', __('Custom CSS', 'tt_login'), 'editor'), array('mode' => 'css'));
    $advanced_tab->add_option(array('custom_js', __('Custom JS', 'tt_login'), 'editor'), array('mode' => 'javascript'));
}


/**
 * Checks if on wp-login.php page
 *
 * @since 1.0.3
 *
 * @return bool
 */
function tt_is_login_page() {
    /** @updated 1.0.5 */
    $login_url = parse_url( get_option('tt_login_custom_login_url') ? get_option('tt_login_custom_login_url') : wp_login_url() );
    $current_url = parse_url($_SERVER['REQUEST_URI']);
    if(!empty($login_url['query']) && !empty($current_url['query'])) {
        return $login_url['path'] == $current_url['path'] && strpos($current_url['query'], $login_url['query']) !== false;
    }else{
        return $login_url['path'] == $current_url['path'];
    }
}