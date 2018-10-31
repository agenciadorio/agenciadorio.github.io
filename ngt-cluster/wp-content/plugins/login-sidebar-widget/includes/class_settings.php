<?php
class login_settings {

	public $default_style = '.log_forms { width: 98%; padding: 5px; border: 1px solid #CCC; margin: 2px; box-sizing:border-box; } .log-form-group{ margin: 0px; width: 100%; padding:5px; } .log_forms input[type=text], input[type=password] { width: 100%; padding: 7px 0 7px 4px !important; border: 1px solid #E3E3E3; margin:0px !important; } .log_forms input[type=submit] { width: 100%; padding: 7px; border: 1px solid #7ac9b7; } .log_forms input[type=text]:focus, input[type=password]:focus { border-color: #4697e4; } .lw-error{ color:#ff0000; } input.lw-error{ border:1px solid #ff0000 !important; }';
	
	public function __construct() {
		$this->load_settings();
	}
	
	public function login_widget_afo_save_settings(){
		global $lsw_default_options_data;
		
		if(isset($_POST['option']) and $_POST['option'] == "login_widget_afo_save_settings"){
			
			if ( ! isset( $_POST['login_widget_afo_field'] )  || ! wp_verify_nonce( $_POST['login_widget_afo_field'], 'login_widget_afo_action' ) ) {
			   wp_die( 'Sorry, your nonce did not verify.' );
			   exit;
			} 
			$lmc = new login_message_class;
			
			if( is_array($lsw_default_options_data) ){
				foreach( $lsw_default_options_data as $key => $value ){
					if ( !empty( $_REQUEST[$key] ) ) {
						if( $value['sanitization'] == 'sanitize_text_field' ){
							update_option( $key, sanitize_text_field($_REQUEST[$key]) );
						} elseif( $value['sanitization'] == 'esc_html' ){
							update_option( $key, esc_html($_REQUEST[$key]) );
						} elseif( $value['sanitization'] == 'esc_textarea' ){
							update_option( $key, esc_textarea($_REQUEST[$key]) );
						} else {
							update_option( $key, sanitize_text_field($_REQUEST[$key]) );
						}
					} else {
						delete_option( $key );
					}
				}
			}
			
			if(isset( $_POST['load_default_style'] ) and sanitize_text_field($_POST['load_default_style']) == "Yes"){
				update_option( 'custom_style_afo', sanitize_text_field($this->default_style) );
			} else {
				update_option( 'custom_style_afo',  sanitize_text_field($_POST['custom_style_afo']) );
			}
			
			$lmc->add_message('Settings updated successfully.','updated');
		}
	}
	
	public function login_widget_afo_options () {
	echo '<div class="wrap">';
	
	global $wpdb;
	$lmc = new login_message_class;
	
	$redirect_page = get_option('redirect_page');
	$redirect_page_url = get_option('redirect_page_url');
	$logout_redirect_page = get_option('logout_redirect_page');
	$link_in_username = get_option('link_in_username');
	$login_afo_rem = get_option('login_afo_rem');
	$login_afo_forgot_pass_link = get_option('login_afo_forgot_pass_link');
	$login_afo_register_link = get_option('login_afo_register_link');
	
	$lafo_invalid_username = get_option('lafo_invalid_username');
	$lafo_invalid_email = get_option('lafo_invalid_email');
	$lafo_invalid_password = get_option('lafo_invalid_password');
	
	$captcha_on_admin_login = get_option('captcha_on_admin_login');
	$captcha_on_user_login = get_option('captcha_on_user_login');
	
	$custom_style_afo = stripslashes(get_option('custom_style_afo'));
	
	$login_sidebar_widget_from_email = get_option('login_sidebar_widget_from_email');
	$forgot_password_link_mail_subject = get_option('forgot_password_link_mail_subject');
	$forgot_password_link_mail_body = get_option('forgot_password_link_mail_body');
	$new_password_mail_subject = get_option('new_password_mail_subject');
	$new_password_mail_body = get_option('new_password_mail_body');
	
	$lmc->show_message();
	
	self :: fb_login_pro_add();
	self :: social_login_so_setup_add();
	self :: help_support();
	self :: wp_register_profile_add();
	
	form_class::form_open();
	wp_nonce_field('login_widget_afo_action','login_widget_afo_field');
    form_class::form_input('hidden','option','','login_widget_afo_save_settings');
    ?>
	<table width="100%" class="ap-table">
	  <tr>
		<td colspan="2"><h3><?php _e('Login Widget Settings','login-sidebar-widget');?></h3></td>
	  </tr>
      <tr>
		<td colspan="2">
        <div class="ap-tabs">
            <div class="ap-tab"><?php _e('General','login-sidebar-widget');?></div>
            <div class="ap-tab"><?php _e('Security','login-sidebar-widget');?></div>
            <div class="ap-tab"><?php _e('Error Message','login-sidebar-widget');?></div>
            <div class="ap-tab"><?php _e('Styling','login-sidebar-widget');?></div>
            <div class="ap-tab"><?php _e('Email Settings','login-sidebar-widget');?></div>
            <div class="ap-tab"><?php _e('Shortcode','login-sidebar-widget');?></div>
            <?php do_action('lwws_custom_settings_tab');?>
        </div>

        <div class="ap-tabs-content">
            <div class="ap-tab-content">
            <table width="100%">
              <tr>
                <td colspan="2"><h3><?php _e('General','login-sidebar-widget');?></h3></td>
              </tr>
              <tr>
                <td width="300"><strong><?php _e('Login Redirect Page','login-sidebar-widget');?>:</strong></td>
                <td><?php
                        $args = array(
                        'depth'            => 0,
                        'selected'         => $redirect_page,
                        'echo'             => 1,
                        'show_option_none' => '-',
                        'id' 			   => 'redirect_page',
                        'name'             => 'redirect_page'
                        );
                        wp_dropdown_pages( $args ); 
                    ?> <?php _e('Or','login-sidebar-widget');?> 
                     <?php form_class::form_input('text','redirect_page_url','',esc_url( $redirect_page_url ),'','','','','','',false,'URL');?>
                    </td>
              </tr>
              <tr>
                <td><strong><?php _e('Logout Redirect Page','login-sidebar-widget');?>:</strong></td>
                 <td><?php
                        $args1 = array(
                        'depth'            => 0,
                        'selected'         => $logout_redirect_page,
                        'echo'             => 1,
                        'show_option_none' => '-',
                        'id' 			   => 'logout_redirect_page',
                        'name'             => 'logout_redirect_page'
                        );
                        wp_dropdown_pages( $args1 ); 
                    ?></td>
              </tr>
              <tr>
                <td><strong><?php _e('Link in Username','login-sidebar-widget');?></strong></td>
                <td><?php
                        $args2 = array(
                        'depth'            => 0,
                        'selected'         => $link_in_username,
                        'echo'             => 1,
                        'show_option_none' => '-',
                        'id' 			   => 'link_in_username',
                        'name'             => 'link_in_username'
                        );
                        wp_dropdown_pages( $args2 ); 
                    ?></td>
              </tr>
              <tr>
                <td><strong><?php _e('Add Remember Me','login-sidebar-widget');?></strong></td>
                <td>
                <?php 
                $login_afo_rem_status = ($login_afo_rem == 'Yes'?true:false);
                form_class::form_checkbox('login_afo_rem','',"Yes",'','','',$login_afo_rem_status);
                ?>
                </td>
              </tr>
              <tr>
                <td><strong><?php _e('Forgot Password Link','login-sidebar-widget');?></strong></td>
                <td>
                    <?php
                        $args3 = array(
                        'depth'            => 0,
                        'selected'         => $login_afo_forgot_pass_link,
                        'echo'             => 1,
                        'show_option_none' => '-',
                        'id' 			   => 'login_afo_forgot_pass_link',
                        'name'             => 'login_afo_forgot_pass_link'
                        );
                        wp_dropdown_pages( $args3 ); 
                    ?>
                    <i><?php _e('Leave blank to not include the link','login-sidebar-widget');?></i>
                    </td>
              </tr>
              <tr>
                <td><strong><?php _e('Register Link','login-sidebar-widget');?></strong></td>
                <td>
                    <?php
                        $args4 = array(
                        'depth'            => 0,
                        'selected'         => $login_afo_register_link,
                        'echo'             => 1,
                        'show_option_none' => '-',
                        'id' 			   => 'login_afo_register_link',
                        'name'             => 'login_afo_register_link'
                        );
                        wp_dropdown_pages( $args4 ); 
                    ?>
                    <i><?php _e('Leave blank to not include the link','login-sidebar-widget');?></i>
                    </td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td><?php form_class::form_input('submit','submit','',__('Save','login-sidebar-widget'),'button button-primary button-large','','','','','',false,'');?>
                </td>
              </tr>
              </table>
        	</div>
            <div class="ap-tab-content">
            <table width="100%">
            <tr>
            <td colspan="2"><h3><?php _e('Security','login-sidebar-widget');?></h3></td>
            </tr>
            <tr>
            <td width="300"><strong><?php _e('Captcha on Admin Login','login-sidebar-widget');?></strong></td>
            <td>
            <?php 
            $captcha_on_admin_login_status = ($captcha_on_admin_login == 'Yes'?true:false);
            form_class::form_checkbox('captcha_on_admin_login','',"Yes",'','','',$captcha_on_admin_login_status);
            ?>
            <i><?php _e('Check to enable captcha on admin login form','login-sidebar-widget');?></i></td>
            </tr>
            <tr>
            <td><strong><?php _e('Captcha on User Login','login-sidebar-widget');?></strong></td>
            <td>
            <?php 
            $captcha_on_user_login_status = ($captcha_on_user_login == 'Yes'?true:false);
            form_class::form_checkbox('captcha_on_user_login','',"Yes",'','','',$captcha_on_user_login_status);
            ?>
            <i><?php _e('Check to enable captcha on user login form','login-sidebar-widget');?></i></td>
            </tr>
            <tr>
            <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
            <td colspan="2">
            <div style="border:1px solid #AEAE00; width:94%; background-color:#FFF; margin:0px auto; padding:10px;">
            Click <a href="admin.php?page=login_log_afo">here</a> to check the user <strong>Login Log</strong>. Use <strong><a href="https://www.aviplugins.com/fb-login-widget-pro/" target="_blank">PRO</a></strong> version that has added security with <strong>Blocking IP</strong> after 5 wrong login attempts. <strong>Blocked IPs</strong> can be <strong>Whitelisted</strong> from admin panel or the <strong>Block</strong> gets automatically removed after <strong>1 Day</strong>.
            </div>
            </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
            <tr>
                <td>&nbsp;</td>
                <td><?php form_class::form_input('submit','submit','',__('Save','login-sidebar-widget'),'button button-primary button-large','','','','','',false,'');?>
                </td>
              </tr>
            </table>
            </div>
            <div class="ap-tab-content">
            <table width="100%">
              <tr>
                <td colspan="2"><h3><?php _e('Error Message','login-sidebar-widget');?></h3></td>
              </tr>
              <tr>
                <td valign="top" width="300"><strong><?php _e('Invalid Username Message','login-sidebar-widget');?></strong></td>
                <td><?php form_class::form_input('text','lafo_invalid_username','',$lafo_invalid_username,'','','','','50','',false,__('Error: Invalid Username','login-sidebar-widget'));?>
                <i><?php _e('Error message for wrong Username','login-sidebar-widget');?></i></td>
              </tr>
              <tr>
                <td valign="top"><strong><?php _e('Invalid Email Message','login-sidebar-widget');?></strong></td>
                <td><?php form_class::form_input('text','lafo_invalid_email','',$lafo_invalid_email,'','','','','50','',false,__('Error: Invalid email address','login-sidebar-widget'));?>
                <i><?php _e('Error message for wrong Email address','login-sidebar-widget');?></i></td>
              </tr>
              <tr>
                <td valign="top"><strong><?php _e('Invalid Password Message','login-sidebar-widget');?></strong></td>
                <td><?php form_class::form_input('text','lafo_invalid_password','',$lafo_invalid_password,'','','','','50','',false,__('Error: Invalid Username & Password','login-sidebar-widget'));?>
                <i><?php _e('Error message for wrong Password','login-sidebar-widget');?></i></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td><?php form_class::form_input('submit','submit','',__('Save','login-sidebar-widget'),'button button-primary button-large','','','','','',false,'');?>
                </td>
              </tr>
              </table>
            </div>
            <div class="ap-tab-content">
            <table width="100%">
               <tr>
                    <td colspan="2"><h3><?php _e('Styling','login-sidebar-widget');?></h3></td>
                  </tr>
               <tr>
                    <td valign="top" width="300">
                    <?php 
                    form_class::form_checkbox('load_default_style','',"Yes",'','','','');
                    ?>
                    <strong> <?php _e('Load Default Styles','login-sidebar-widget');?></strong><br />
                    <?php _e('Check this and hit the save button to go back to default styling.','login-sidebar-widget');?>
                    </td>
                    <td><?php form_class::form_textarea('custom_style_afo','',$custom_style_afo,'','','','','','','','','height:200px; width:80%;');?></td>
                  </tr>
                  <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
                  <tr>
                <td>&nbsp;</td>
                <td><?php form_class::form_input('submit','submit','',__('Save','login-sidebar-widget'),'button button-primary button-large','','','','','',false,'');?>
                </td>
              </tr>
              </table>
            </div>
            <div class="ap-tab-content">
            <table width="100%">
            <tr>
            <td colspan="2"><h3><?php _e('Email Settings','login-sidebar-widget');?></h3></td>
            </tr>
            <tr>
            <td valign="top" width="300"><strong><?php _e('From Email','login-sidebar-widget');?></strong></td>
            <td><?php form_class::form_input('text','login_sidebar_widget_from_email','',$login_sidebar_widget_from_email,'','','','','50','',false,'no-reply@example.com');?>
            <i><?php _e('This will be the from email address in the emails. This will make sure that the emails do not go to a spam folder.','login-sidebar-widget');?></i>
            </td>
            </tr>
            <tr>
            <td><strong><?php _e('Reset Password Link Mail Subject','login-sidebar-widget');?></strong></td>
            <td>
            <?php form_class::form_input('text','forgot_password_link_mail_subject','',$forgot_password_link_mail_subject,'','','','','50','',false,'');?>
            </td>
            </tr>
            <tr>
            <td valign="top"><strong><?php _e('Reset Password Link Mail Body','login-sidebar-widget');?></strong>
            <p><i><?php _e('This mail will fire when a user request for a new password.','login-sidebar-widget');?></i></p>
            </td>
            <td><?php form_class::form_textarea('forgot_password_link_mail_body','',$forgot_password_link_mail_body,'','','','','','50','','','height:200px; width:100%;');?>
            <p>Shortcodes: #site_url#, #user_name#, #resetlink#</p>
            </td>
            </tr>
            <tr>
            <td><strong><?php _e('New Password Mail Subject','login-sidebar-widget');?></strong></td>
            <td>
            <?php form_class::form_input('text','new_password_mail_subject','',$new_password_mail_subject,'','','','','50','',false,'');?>
            </td>
            </tr>
            <tr>
            <td valign="top"><strong><?php _e('New Password Mail Subject Body','login-sidebar-widget');?></strong>
            <p><i><?php _e('This mail will fire when a user clicks on the password reset link provided in the above mail.','login-sidebar-widget');?></i></p>
            </td>
            <td><?php form_class::form_textarea('new_password_mail_body','',$new_password_mail_body,'','','','','','','','','height:200px; width:100%;');?>
            <p>Shortcodes: #site_url#, #user_name#, #user_password#</p>
            </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
            <tr>
                <td>&nbsp;</td>
                <td><?php form_class::form_input('submit','submit','',__('Save','login-sidebar-widget'),'button button-primary button-large','','','','','',false,'');?>
                </td>
              </tr>
            </table>
            </div>
            <div class="ap-tab-content">
            <table width="100%">
             <tr>
        <td colspan="2"><h3><?php _e('Shortcode','login-sidebar-widget');?></h3></td>
      </tr>
              <tr>
                <td colspan="2">Use <span style="color:#000066;">[login_widget]</span> shortcode to display login form in post or page.<br />
                 Example: <span style="color:#000066;">[login_widget title="Login Here"]</span></td>
              </tr>
              <tr>
                <td colspan="2">Use <span style="color:#000066;">[forgot_password]</span> shortcode to display forgot password form in post or page.<br />
                 Example: <span style="color:#000066;">[forgot_password title="Forgot Password?"]</span></td>
              </tr>
            </table>
            </div>
            <?php do_action('lwws_custom_settings_tab_content');?>
        </div>
        </td>
	  </tr>
      <tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	  </tr>
	</table>
	<?php
    form_class::form_close();
	self :: donate(); 
	echo '</div>';
	}
		
	public function login_widget_afo_menu () {
		add_menu_page( 'Login Widget', 'Login Widget Settings', 'activate_plugins', 'login_widget_afo', array( $this,'login_widget_afo_options' ));
	}
	
	public function load_login_admin_style(){
		wp_register_style( 'style_login_admin', plugins_url( LSW_DIR_NAME. '/css/style_login_admin.css' ) );
		wp_enqueue_style( 'style_login_admin' );
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery.cookie', plugins_url( LSW_DIR_NAME . '/js/jquery.cookie.js' ) );
		wp_enqueue_script( 'ap-tabs', plugins_url( LSW_DIR_NAME . '/js/ap-tabs.js' ) );
	}
	
	public function load_login_front_style() {
		wp_enqueue_style( 'style_login_widget', plugins_url( LSW_DIR_NAME . '/css/style_login_widget.css' ) );
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery.validate.min', plugins_url( LSW_DIR_NAME . '/js/jquery.validate.min.js' ) );
		wp_enqueue_script( 'additional-methods', plugins_url( LSW_DIR_NAME . '/js/additional-methods.js' ) );
	}
	
	public function load_settings(){
		add_action( 'admin_menu' , array( $this, 'login_widget_afo_menu' ) );
		add_action( 'admin_init', array( $this, 'login_widget_afo_save_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'load_login_admin_style' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'load_login_front_style' ) );
	}
	
	private static function wp_register_profile_add(){ ?>
	<table width="100%" border="0" class="ap-table">
  <tr>
    <td><p><strong>Login Widget With Shortcode</strong> recommends you to download and activate <a href="https://wordpress.org/plugins/wp-register-profile-with-shortcode/" target="_blank">WP Register Profile With Shortcode</a> from <a href="https://wordpress.org/" target="_blank">wordpress.org</a>, so that users can register in your site.</p></td>
  </tr>
</table>
	<?php }
	
	public static function help_support(){ ?>
	<table width="100%" border="0" class="ap-table">
	  <tr>
		<td align="right"><a href="https://www.aviplugins.com/support.php" target="_blank">Help and Support</a> <a href="https://www.aviplugins.com/rss/news.xml" target="_blank"><img src="<?php echo plugins_url( LSW_DIR_NAME . '/images/rss.png' ) ;?>" style="vertical-align: middle;" alt="RSS"></a></td>
	  </tr>
	</table>
	<?php
	}
	
	private static function fb_login_pro_add(){ ?>
	<table width="100%" border="0" class="ap-table">
  <tr>
    <td><p>The <strong>PRO</strong> version <a href="https://www.aviplugins.com/fb-login-widget-pro/" target="_blank"><strong>Facebook Login Widget (PRO)</strong></a> supports login with <strong>Facebook, Google, Twitter, LinkedIn, Amazon</strong> and <strong>Instagram</strong> accounts. <a href="https://www.aviplugins.com/fb-login-addon-pack/" target="_blank">Addon Pack</a> is available for logging in with <strong>Microsoft</strong>, <strong>Yahoo</strong> accounts. You can get it <a href="https://www.aviplugins.com/fb-login-widget-pro/" target="_blank">here</a> in <strong>USD 4.00</strong> </p></td>
  </tr>
</table>
	<?php }
	
	private static function social_login_so_setup_add(){ ?>
	<table width="100%" border="0" class="ap-table">
  <tr>
    <td><p>Check out the <a href="https://www.aviplugins.com/social-login-no-setup/" target="_blank"><strong>Social Login No Setup</strong></a> plugin that supports login with <strong>Facebook, Google, Twitter, LinkedIn, Microsoft, WordPress, Amazon, Github, Tumblr, Vimeo, Reddit, Dribbble, Twitch</strong> and <strong>Instagram</strong> accounts. It requires no Setups, no Maintanance, no need to create any APPs, APIs, Client Ids, Client Secrets or anything ( Everythins is maintained by aviplugins.com ). Just Install and users will start logging in with their social networking accounts right away. <a href="https://www.aviplugins.com/social-login-no-setup/" target="_blank">More Details</a>.</p></td>
  </tr>
</table>
	<?php }
	
	public static function donate(){	?>
	<table width="100%" border="0" class="ap-table">
	 <tr>
	 <td align="right"><a href="https://www.aviplugins.com/donate/" target="_blank">Donate</a> <img src="<?php echo plugins_url( LSW_DIR_NAME . '/images/paypal.png' );?>" style="vertical-align: middle;" alt="PayPal"></td>
	  </tr>
	</table>
	<?php
	}
}