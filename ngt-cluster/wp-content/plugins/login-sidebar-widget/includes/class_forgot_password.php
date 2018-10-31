<?php

class afo_forgot_pass_class {
	
	public function ForgotPassForm(){
		$this->load_script();
		if(!is_user_logged_in()){
		?>
        <div class="forgot-pass-form">
        
		<?php do_action('lwws_before_forgot_password_form_start');?>
        
        <?php $this->error_message();?>
        
		<form name="forgot" id="forgot" method="post" action="" autocomplete="off" <?php do_action( 'lwws_forgot_password_form_tag' );?>>
        <?php wp_nonce_field( 'login_widget_action', 'login_widget_field' ); ?>
		<input type="hidden" name="option" value="afo_forgot_pass" />
			<div class="forgot-pass-form-group">
			<label for="userusername"><?php _e('Email','login-sidebar-widget');?> </label>
			<input type="email" name="userusername" id="userusername" required <?php do_action( 'lwws_fp_userusername_field' );?>/>
			</div>
			
			<div class="forgot-pass-form-group"><input name="forgot" type="submit" value="<?php _e('Submit','login-sidebar-widget');?>" <?php do_action( 'lwws_forgot_password_form_submit_tag' );?>/></div>
			
			<div class="forgot-pass-form-group">
				<div class="forgot-text">
					<?php _e('Please enter your email. The password reset link will be provided in your email.','login-sidebar-widget');?>
				</div>
			</div>
		</form>
        
        <?php do_action('lwws_after_forgot_password_form_end');?>
        
        </div>
		<?php 
		}
	}
	
	public function message_close_button(){
		$cb = '<span href="javascript:void(0);" onclick="closeMessageFp();" class="close_button_afo"></span>';
		return $cb;
	}
	
	public function load_script(){?>
		<script>
			jQuery(document).ready(function () {
				jQuery('#forgot').validate();
			});
			function closeMessageFp(){jQuery('.error_wid_login').hide();}
		</script>
	<?php }
	
	public function error_message(){
		global $aperror;
		if ( is_wp_error( $aperror ) ) {
			$errors = $aperror->get_error_messages();
			echo '<div class="'.$errors[0].'">'.$errors[1].$this->message_close_button().'</div>';
		}
	}
} 
