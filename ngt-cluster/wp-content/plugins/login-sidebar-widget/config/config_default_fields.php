<?php

// plugin default option data 
$lsw_default_options_data = array(

// general
'redirect_page' => array( 'sanitization' => 'sanitize_text_field' ),
'redirect_page_url' => array( 'sanitization' => 'sanitize_text_field' ),
'logout_redirect_page' => array( 'sanitization' => 'sanitize_text_field' ),
'link_in_username' => array( 'sanitization' => 'sanitize_text_field' ),
'login_afo_rem' => array( 'sanitization' => 'sanitize_text_field' ),
'login_afo_forgot_pass_link' => array( 'sanitization' => 'sanitize_text_field' ),
'login_afo_register_link' => array( 'sanitization' => 'sanitize_text_field' ),

// messages
'lafo_invalid_username' => array( 'sanitization' => 'sanitize_text_field' ),
'lafo_invalid_email' => array( 'sanitization' => 'sanitize_text_field' ),
'lafo_invalid_password' => array( 'sanitization' => 'sanitize_text_field' ),

// security 
'captcha_on_admin_login' => array( 'sanitization' => 'sanitize_text_field' ),
'captcha_on_user_login' => array( 'sanitization' => 'sanitize_text_field' ),

// emails
'login_sidebar_widget_from_email' => array( 'sanitization' => 'sanitize_text_field' ),
'forgot_password_link_mail_subject' => array( 'sanitization' => 'sanitize_text_field' ),
'forgot_password_link_mail_body' => array( 'sanitization' => 'esc_html' ),
'new_password_mail_subject' => array( 'sanitization' => 'sanitize_text_field' ),
'new_password_mail_body' => array( 'sanitization' => 'esc_html' ),

);