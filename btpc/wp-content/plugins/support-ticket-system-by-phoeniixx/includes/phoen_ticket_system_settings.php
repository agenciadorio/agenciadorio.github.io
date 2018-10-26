<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! empty( $_POST ) && check_admin_referer( 'phoen_ticket_function', 'phoen_ticket_nonce_field' ) ) {
	
	if(isset($_POST['phoen_set_ticket']))
	{
		
		if(isset($_POST['phoen_ticket_enable'])){
			
			$phoen_tickets_enable = sanitize_text_field($_POST['phoen_ticket_enable']);
			
			$phoen_ticket_enable_editor = sanitize_text_field($_POST['phoen_ticket_enable_editor']);
			
			$phoen_ticket_login_urls = sanitize_text_field($_POST['phoen_ticket_login_url']);
			
			$phoen_ticket_enable_editor_create = sanitize_text_field($_POST['phoen_ticket_enable_editor_create']);
			
			$phoen_advance_reporting_order = sanitize_text_field($_POST['phoen_advance_reporting_order']);
			
			$phoen_ticket_loging_url=isset($phoen_ticket_login_urls)?$phoen_ticket_login_urls:'';
			
			$phoen_ticket_register_url = sanitize_text_field($_POST['phoen_ticket_register_url']);
			
			$phoen_tickets_register_url = isset($phoen_ticket_register_url)?$phoen_ticket_register_url:'';
			
			
		}else{
			
			$phoen_tickets_enable = '';
		}
		
		update_option('phoen_ticket_system_enable',$phoen_tickets_enable);	
		
		update_option('phoen_ticket_enable_editor',$phoen_ticket_enable_editor);	
		
		update_option('phoen_ticket_enable_editor_create',$phoen_ticket_enable_editor_create);	
		
		update_option('_phoen_ticket_login_urls',$phoen_ticket_loging_url);	
		
		update_option('_phoen_ticket_register_urls',$phoen_tickets_register_url);	
		
		update_option('phoen_advance_reporting_order',$phoen_advance_reporting_order);	
	}
	
}
		
$phoen_ticket_enable_settings = get_option('phoen_ticket_system_enable');
$phoen_ticket_enable_editor = get_option('phoen_ticket_enable_editor');
$phoen_ticket_enable_editor_create = get_option('phoen_ticket_enable_editor_create');
$phoen_advance_reporting_order = get_option('phoen_advance_reporting_order');

		
?>
		
<form method="post">

	<?php wp_nonce_field( 'phoen_ticket_function', 'phoen_ticket_nonce_field' ); ?>

	<h2 class="phoen_ticket_general_settings"><?php _e("General Settings",'support-ticket-system-by-phoeniixx'); ?></h2>
	
	<table class="form-table">
	
		<tbody>
		
			<tr class="phoen-user-user-login-wrap">
			
				<th><label for="phoen_advance_reporting"><?php _e("Enable Ticket System Plugin",'support-ticket-system-by-phoeniixx'); ?></label></th>
			
				<td>
				
					<input type="checkbox" value="1" <?php echo(isset($phoen_ticket_enable_settings) && $phoen_ticket_enable_settings == '1')?'checked':'';?> name="phoen_ticket_enable">
					
				</td>
			
			</tr>
			
			<tr class="phoen-user-user-login-wrap">
			
				<th><label for="phoen_advance_reporting"><?php _e("Enable Textarea & Disable Editor on Create ticket",'support-ticket-system-by-phoeniixx'); ?></label></th>
			
				<td>
				
					<input type="checkbox" value="1" <?php echo(isset($phoen_ticket_enable_editor_create) && $phoen_ticket_enable_editor_create == '1')?'checked':'';?> name="phoen_ticket_enable_editor_create">
					
				</td>
			
			</tr>
			
			
			<tr class="phoen-user-user-login-wrap">
			
				<th><label for="phoen_advance_reporting"><?php _e("Enable Textarea & Disable Editor On Comment",'support-ticket-system-by-phoeniixx'); ?></label></th>
			
				<td>
				
					<input type="checkbox" value="1" <?php echo(isset($phoen_ticket_enable_editor) && $phoen_ticket_enable_editor == '1')?'checked':'';?> name="phoen_ticket_enable_editor">
					
				</td>
			
			</tr>
			
			<tr class="phoen-user-user-login-wrap">
			
				<th><label for="phoen_advance_reporting_order"><?php _e("Enable Create Ticket Button On My Order Page",'support-ticket-system-by-phoeniixx'); ?></label></th>
			
				<td>
				
					<input type="checkbox" value="1" <?php echo(isset($phoen_advance_reporting_order) && $phoen_advance_reporting_order == '1')?'checked':'';?> name="phoen_advance_reporting_order">
					
				</td>
			
			</tr>
			
			
			<tr class="phoen-user-user-login-wrap">
			
				<th><label for="phoen_advance_reporting"><?php _e("Login Url",'support-ticket-system-by-phoeniixx'); ?></label></th>
			
				<td>
					<?php
				
					$phoen_ticket_login_url_data = get_option('_phoen_ticket_login_urls');
					?>
					<input type="text" name="phoen_ticket_login_url" value="<?php echo $phoen_ticket_login_url_data ;?>">
					
				</td>
			
			</tr>
			
			<tr class="phoen-user-user-login-wrap">
			
				<th><label for="phoen_advance_reporting"><?php _e("Register Url",'support-ticket-system-by-phoeniixx'); ?></label></th>
			
				<td>
					<?php $phoen_tickets_register_urls = get_option('_phoen_ticket_register_urls'); ?>
				
					<input type="text" name="phoen_ticket_register_url" value="<?php echo $phoen_tickets_register_urls; ?>">
					
				</td>
			
			</tr>
			
			<tr class="phoen-user-user-login-wrap">
			
				<th><label for="phoen_advance_reporting"><?php _e("Company Name",'support-ticket-system-by-phoeniixx'); ?></label></th>
			
				<td>
				
					<input type="text" name="phoen_ticket_logout_url" value="<?php echo $blog_title = get_bloginfo( 'name' ); ?>">
					
				</td>
			
			</tr>
			
			
		</tbody>
		
	</table>
	<br />
	<input type="submit" value="<?php _e("Save changes",'support-ticket-system-by-phoeniixx'); ?>" class="button-primary" name="phoen_set_ticket">
	
</form>
		
<style>

	.form-table th {
	
		width: 270px;
		
		padding: 25px;
		
	}
	
	.form-table td {
	
		padding: 20px 10px;
	}
	
	.form-table {
	
		background-color: #fff;
	}
	
	h3 {
	
		padding: 10px;
		
	}

</style>
<?php
?>