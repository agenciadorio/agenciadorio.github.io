<?php

class login_wid extends WP_Widget {
	
	public function __construct() {
		add_action( 'wp_head', array( $this, 'custom_styles_afo' ) );
		parent::__construct(
	 		'login_wid',
			'Login Widget',
			array( 'description' => __( 'This is a simple login form in the widget.', 'login-sidebar-widget' ), )
		);
	 }

	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		if ( ! empty( $instance['wid_title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['wid_title'] ) . $args['after_title'];
		}
		$this->loginForm( $args['widget_id'] );
		echo $args['after_widget'];
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['wid_title'] = strip_tags( $new_instance['wid_title'] );
		return $instance;
	}


	public function form( $instance ) {
		$wid_title = '';
		if(!empty($instance[ 'wid_title' ])){
			$wid_title = esc_html($instance[ 'wid_title' ]);
		}
		?>
		<p><label for="<?php echo $this->get_field_id('wid_title'); ?>"><?php _e('Title','login-sidebar-widget'); ?> </label>
        <?php form_class::form_input('text',$this->get_field_name('wid_title'),$this->get_field_id('wid_title'),$wid_title,'widefat');?>
		</p>
		<?php 
	}
	
	public function add_remember_me(){
		$login_afo_rem = get_option('login_afo_rem');
		if($login_afo_rem == 'Yes'){
			echo '<div class="log-form-group"><label for="remember"> '.__('Remember Me','login-sidebar-widget').'</label>  '.form_class::form_checkbox('remember','remember','Yes','','','','',false,'',false).'</div>';
		}
	}
	
	public function add_extra_links(){
		$login_afo_forgot_pass_link = get_option('login_afo_forgot_pass_link');
		$login_afo_register_link = get_option('login_afo_register_link');
		
		do_action( 'lwws_extra_links_start' );
		
		if($login_afo_forgot_pass_link){
			echo '<a href="'. esc_url( get_permalink($login_afo_forgot_pass_link) ).'" ' . apply_filters( 'lwws_lost_password_link_a_tag', get_permalink($login_afo_forgot_pass_link) ) . '>'.__('Lost Password?','login-sidebar-widget').'</a>';
		}
		
		if( $login_afo_forgot_pass_link and $login_afo_register_link ){
			echo apply_filters( 'lwws_extra_links_separator', ' | ' );
		}
		
		if($login_afo_register_link){
			echo '<a href="'. esc_url( get_permalink($login_afo_register_link) ) .'" ' . apply_filters( 'lwws_register_link_a_tag', get_permalink($login_afo_register_link) ) . '>'.__('Register','login-sidebar-widget').'</a>';
		}
		
		do_action( 'lwws_extra_links_end' );
		
	}
	
	public static function curPageURL() {
	 $pageURL = 'http';
	 if (isset($_SERVER["HTTPS"]) and $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	 $pageURL .= "://";
	 if (isset($_SERVER["SERVER_PORT"]) and $_SERVER["SERVER_PORT"] != "80") {
	  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	 } else {
	  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	 }
	 return $pageURL;
	}

	public function gen_redirect_url(){
		$redirect_page = get_option('redirect_page');
		$redirect_page_url = get_option('redirect_page_url');
		
		if(isset($_REQUEST['redirect'])){
			$redirect = sanitize_text_field($_REQUEST['redirect']);
		} elseif(isset($_REQUEST['redirect_to'])){
			$redirect = sanitize_text_field($_REQUEST['redirect_to']);
		} else {
			if($redirect_page_url){
				$redirect = $redirect_page_url;
			} else {
				if($redirect_page){
					$redirect = get_permalink($redirect_page);
				} else {
					$redirect = $this->curPageURL();
				}
			}
		}
		return esc_url( $redirect );
	}
	
	public function loginForm( $wid_id = '' ){
		$this->load_script();
		if(!is_user_logged_in()){		
		?>
		<div id="log_forms" class="log_forms <?php echo $wid_id;?>">
        
		<?php do_action('lwws_before_login_form_start');?>
		
        <?php $this->error_message();?>
                
		<form name="login" id="login" method="post" action="" autocomplete="off" <?php do_action( 'lwws_login_form_tag' );?>>
		<input type="hidden" name="option" value="afo_user_login" />
		<input type="hidden" name="redirect" value="<?php echo $this->gen_redirect_url(); ?>" />
		<div class="log-form-group">
			<label for="userusername"><?php _e('Username','login-sidebar-widget');?> </label>
			<input type="text" name="userusername" id="userusername" required <?php do_action( 'lwws_userusername_field' );?>/>
		</div>
		<div class="log-form-group">
			<label for="userpassword"><?php _e('Password','login-sidebar-widget');?> </label>
			<input type="password" name="userpassword" id="userpassword" required <?php do_action( 'lwws_userpassword_field' );?>/>
		</div>
        
        <?php do_action('login_afo_form');?>
        
        <?php do_action('login_form');?>
		
		<?php $this->add_remember_me();?>

		<div class="log-form-group"><input name="login" type="submit" value="<?php _e('Login','login-sidebar-widget');?>" <?php do_action( 'lwws_login_form_submit_tag' );?>/></div>
		<div class="log-form-group extra-links">
			<?php $this->add_extra_links();?>
		</div>
		</form>
        
        <?php do_action('lwws_after_login_form_end');?>
        
		</div>
		<?php 
		} else {
		$logout_redirect_page = get_option('logout_redirect_page');
		$link_in_username = get_option('link_in_username');
		if($logout_redirect_page){
			$logout_redirect_page = get_permalink($logout_redirect_page);
		} else {
			$logout_redirect_page = $this->curPageURL();
		}
		$current_user = wp_get_current_user();
     	
		if($link_in_username){
			$link_with_username = '<a href="' . esc_url( get_permalink($link_in_username) ) . '" ' . apply_filters( 'lwws_username_link_a_tag', get_permalink($link_in_username) ) . '>' . apply_filters( 'lwws_welcome_text', __('Howdy,','login-sidebar-widget') ) . ' ' . $current_user->display_name . '</a>';
		} else {
			$link_with_username = apply_filters( 'lwws_welcome_text', __('Bem vindo,','login-sidebar-widget') ) . ' ' . $current_user->display_name;
		}
		?> 
		
        <div class="logged-in"><?php echo $link_with_username;?> | <a href="<?php echo wp_logout_url( apply_filters( 'lwws_logout_redirect', $logout_redirect_page, $current_user->ID ) ); ?>" title="<?php _e('Logout','login-sidebar-widget');?>" <?php do_action( 'lwws_logout_link_a_tag' ); ?>><?php _e('Logout','login-sidebar-widget');?></a></div>
	<br/> <b> Para completar seu acesso.  Digite abaixo o codigo PIN.   </b> <br/> <br/> 
	<?php echo do_shortcode("[sform]1[/sform]"); ?>
	 
	<?php 
		}
	}
	
	public function error_message(){
		global $aperror;
		if ( is_wp_error( $aperror ) ) {
			$errors = $aperror->get_error_messages();
			echo '<div class="'.$errors[0].'">'.$errors[1].$this->message_close_button().'</div>';
		}
	}
	
	public function message_close_button(){
		$cb = '<span href="javascript:void(0);" onclick="closeMessage();" class="close_button_afo"></span>';
		return $cb;
	}
	
	public function custom_styles_afo(){
		echo '<style>';
		echo stripslashes(get_option('custom_style_afo'));
		echo '</style>';
	}
	
	public function load_script(){?>
		<script type="text/javascript">
			function closeMessage(){jQuery('.error_wid_login').hide();}
			jQuery(document).ready(function () {
				jQuery('#login').validate({ errorClass: "lw-error" });
			});
		</script>
	<?php }
	
} 