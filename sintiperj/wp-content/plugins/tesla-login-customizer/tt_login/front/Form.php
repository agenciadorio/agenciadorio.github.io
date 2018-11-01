<?php
/**
 * Class for login form controls
 */

namespace tt_login\front;

class Form extends Front
{

    function __construct($selector = '')
    {
        parent::__construct();

        $this->animation();

        if( get_option('tt_login_form_heading') )
            add_filter('login_message', array( $this, 'form_heading' ) );
        if( get_option('tt_login_form_captcha') ) {
	        $reCaptcha_url = '//www.google.com/recaptcha/api.js';
	        if(get_option('tt_login_form_captcha_lang'))
		        $reCaptcha_url = add_query_arg('hl',get_option('tt_login_form_captcha_lang'),$reCaptcha_url);
            $this->add_script(array('tt-g-recaptcha' , $reCaptcha_url));
            add_action('login_form', array($this, 'reCaptcha'));
            add_action('register_form', array($this, 'reCaptcha'));
            add_action('lostpassword_form', array($this, 'reCaptcha'));
            $this->add_script(array('tt-check-recaptcha' , $this->get_plugin_uri() . '/js/reCaptcha.js' , array( 'jquery' ) ));
        }

        if(get_option('tt_login_form_no_shake'))
            add_action('login_head', array( $this, 'remove_shake' ) );

        $this->redirects();
        add_action( 'login_enqueue_scripts' , array( $this , 'custom_code' ) , 11);
    }

    function reCaptcha(){
        if(get_option('tt_login_form_captcha_key'))
            echo '<div class="g-recaptcha"
                data-sitekey="'.get_option('tt_login_form_captcha_key').'"
                data-theme="'.get_option('tt_login_form_captcha_theme').'"
                data-type="'.get_option('tt_login_form_captcha_type').'"
                data-error-text="'.__('Please validate reCaptcha','tt_login').'"

                ></div>';
        else
            _e("Missing Recaptcha sitekey",'tt_login');
    }

    function redirects(){
        if(get_option('tt_login_redirect_register'))
            add_filter( 'registration_redirect' , array( $this , 'registrationRedirect' ) );
        if(get_option('tt_login_redirect_login'))
            add_filter( 'login_redirect' , array( $this , 'loginRedirect' ) );
    }

    function registrationRedirect(){
        return get_option('tt_login_redirect_register');
    }

    function loginRedirect(){
        return get_option('tt_login_redirect_login');
    }

    function custom_code(){
        self::$css .= get_option('tt_login_custom_css');
        self::$js .= get_option('tt_login_custom_js');
    }

    function animation(){
        if( get_option('tt_login_form_animation_in') || get_option('tt_login_form_animation_out') ) {
            $this->add_style(array('tt-animate', $this->get_plugin_uri() . '/css/animate.css'));
            if (get_option('tt_login_form_animation_in')) {
                self::$body_classes[] = 'tt-form-animated-in';
                self::$js .= "var ttAnimationIn = '" . get_option('tt_login_form_animation_in') . "';" ;
            }
            if (get_option('tt_login_form_animation_out')) {
                self::$body_classes[] = 'tt-form-animated-out';
                self::$js .= "var ttAnimationOut = '" . get_option('tt_login_form_animation_out') . "';" ;
            }
            if (get_option('tt_login_form_animation_error')) {
                self::$body_classes[] = 'tt-form-animated-error';
                self::$js .= "var ttAnimationError = '" . get_option('tt_login_form_animation_error') . "';" ;
            }
        }
    }

    function remove_shake(){
        remove_action('login_head', 'wp_shake_js', 12);
    }

    function form_heading(){
        return '<h2 class="tt-form-title">' . get_option('tt_login_form_heading') . '</h2>';
    }
}