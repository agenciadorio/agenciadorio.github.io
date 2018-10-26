<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/************ Submit Ticket Data ****************/

//if ( !current_user_can( 'manage_options' ) ) {  die(); }

if ( ! empty( $_POST ) && check_admin_referer( 'phoen_ticket_system_function', 'phoen_ticket_system_nonce_field' ) )
{
	
	if(isset($_POST['customer_detail_submit']))
	{
		
		$phoen_ticket_customer_name = sanitize_text_field($_POST['customer_name']);
		
		$phoen_ticket_customer_email_id = sanitize_text_field($_POST['customer_email_id']);
		
		$phoen_ticket_custome_subject_detail = sanitize_text_field($_POST['custome_subject_detail']);
		
		$phoen_ticket_customer_messages = $_POST['content'];
		
		$customer_priority_set= sanitize_text_field($_POST['customer_priority_set']); 
		
		
		$phoen_post_ticket_data = array(
		
		  'post_title'    => $phoen_ticket_customer_name,
		  
		  'post_content'  => $phoen_ticket_customer_messages,
		  
		  'post_status'   => 'publish',
		  
		  'post_author'   => 1,
		  
		  'post_type'     =>'phoen_ticket_systems',
		  
		  'comment_status' => 'open',
		  
		  'post_excerpt'  =>$phoen_ticket_custome_subject_detail
	  
	  
		);
		
		register_post_type( 'phoen_ticket_systems', $phoen_post_ticket_data );
		
		$phoen_ticket_id = wp_insert_post( $phoen_post_ticket_data );
		 
		update_post_meta($phoen_ticket_id,'phoen_ticket_email',$phoen_ticket_customer_email_id);
		
		update_post_meta($phoen_ticket_id,'phoen_ticket_email_asigne_change',$phoen_ticket_customer_email_id);
		
		update_post_meta($phoen_ticket_id,'phoen_ticket_priority',$customer_priority_set);
		$phoen_order_id = isset($_GET['order_id'])?$_GET['order_id']:'';
		update_post_meta($phoen_order_id,'phoen_create_ticket_by_order',$phoen_ticket_id);
		
		$current_user = wp_get_current_user();
				
		$phoen_ticket_role = $current_user->roles[0];	

		if($phoen_ticket_role == 'customer' || $phoen_ticket_role == 'subscriber'){
			
			update_post_meta($phoen_ticket_id,'phoen_ticket_created_by',$phoen_ticket_customer_email_id);
		}
		
		$phoen_ticket_complets="open";
		
		update_post_meta($phoen_ticket_id,'_phoen_ticket_status',$phoen_ticket_complets);
		
		update_post_meta($phoen_ticket_id,'_phoen_ticket_access_status','unread');
		
		update_post_meta($phoen_ticket_id,'_phoen_ticket_admin_status','unread');
		
		update_post_meta($phoen_ticket_id,'_phoen_ticket_customer_status','unread');
		
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			
			require_once( ABSPATH . 'wp-admin/includes/image.php' );
			
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
			
			require_once( ABSPATH . 'wp-admin/includes/media.php' );

			$phoen_ticket_support_files = is_array($_FILES["my_file_upload"])?$_FILES["my_file_upload"]:'';

			foreach ($phoen_ticket_support_files['name'] as $key => $value) {
				
				if ($phoen_ticket_support_files['name'][$key]) {
					
					$phoen_ticket_atteched_file = array(
					
						'name' => $phoen_ticket_support_files['name'][$key],
						
						'type' => $phoen_ticket_support_files['type'][$key],
						
						'tmp_name' => $phoen_ticket_support_files['tmp_name'][$key],
						
						'error' => $phoen_ticket_support_files['error'][$key],
						
						'size' => $phoen_ticket_support_files['size'][$key]
						
					);
					
					$_FILES = array("upload_file" => $phoen_ticket_atteched_file);
					
					$attachment_id = media_handle_upload("upload_file", 0);

					if (is_wp_error($attachment_id)) {
						// There was an error uploading the image.
						echo "Error adding file";
					} else {
						// The image was uploaded successfully!
						$phoen_ticket_ufiles[] = $attachment_id ;
					
						
					}
				}
			}
		} 
			
		if(isset($phoen_ticket_ufiles)){
			
			update_post_meta( $phoen_ticket_id, '_phoen_new_ticket_file', $phoen_ticket_ufiles );
		}	
		
		
	/***************** Mail Functiom *************************/
	
		$phoen_ticket_admin_mail = get_option('admin_email');
		
		$hoen_ticket_email_to = $phoen_ticket_customer_email_id;
		
		$phoen_ticket_subject = $phoen_ticket_custome_subject_detail;
		
		$current_user = wp_get_current_user();
		
		$phoen_curren_user = $current_user->user_email;
		
		$headers = array('Content-Type: text/html; charset=UTF-8');
		
		$subject = "[".$phoen_ticket_subject." "."Ticket ID:".$phoen_ticket_id."]";
		
		$msg = '<div style="background-color:#f5f5f5;width:100%;margin:0;padding:70px 0 70px 0">
			<table width="100%" height="100%" cellspacing="0" cellpadding="0" border="0">
			<tbody>
				<tr>
					<td valign="top" align="center">
					<div></div>
					<table width="600" cellspacing="0" cellpadding="0" border="0" style="border-radius:6px!important;background-color:#fdfdfd;border:1px solid #dcdcdc;border-radius:6px!important">
					<tbody>
						<tr>
							<td valign="top" align="center">
								
								<table width="600" cellspacing="0" cellpadding="0" border="0" bgcolor="#557da1" style="background-color:#557da1;color:#ffffff;border-top-left-radius:6px!important;border-top-right-radius:6px!important;border-bottom:0;font-family:Arial;font-weight:bold;line-height:100%;vertical-align:middle">
									<tbody>
										<tr>
											<td>
												<h1 style="color:#ffffff;margin:0;padding:28px 24px;display:block;font-family:Arial;font-size:30px;font-weight:bold;text-align:left;line-height:150%">'.
												$subject.
												
												'</h1>
											</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
						
						<tr>
							<td valign="top" align="center">
								<table width="600" cellspacing="0" cellpadding="10" border="0" style="border-top:0">
									<tbody>
										<tr>
											<td valign="top">
												<table width="100%" cellspacing="0" cellpadding="10" border="0">
													<tbody>
														<tr>
															<td valign="middle" style="border:0;color:#99b1c7;font-family:Arial;font-size:12px;line-height:125%;text-align:center" colspan="2">
																<h3>Support Ticket</h3>
																<p>'.$phoen_ticket_customer_messages.'</p>
															</td>
														</tr>
													</tbody>
												</table>
											</td>
										</tr>
									</tbody>
								</table>
							</td>
						 </tr>
						
					 </tbody>
					</table>
				  </td>
				</tr>
			</tbody>
			</table>
			</div>';  
		
		
		
		if($phoen_ticket_admin_mail == $phoen_curren_user )
		{

			//wp_mail($phoen_ticket_admin_mail, "[".$phoen_ticket_subject." "."Ticket ID:".$phoen_ticket_id."]", $phoen_ticket_customer_messages);
			
			wp_mail( $phoen_ticket_admin_mail, $subject,$msg,$headers);
			
			
			
		}else{
			
			wp_mail( $phoen_ticket_admin_mail, $subject,$msg,$headers);
			wp_mail( $hoen_ticket_email_to, $subject,$msg,$headers);
			
			//wp_mail($phoen_ticket_admin_mail, "[".$phoen_ticket_subject." "."Ticket ID:".$phoen_ticket_id."]", $phoen_ticket_customer_messages);
			
			//wp_mail($hoen_ticket_email_to, "[".$phoen_ticket_subject." "."Ticket ID:".$phoen_ticket_id."]", $phoen_ticket_customer_messages);
		} 

	}
}

$phoen_ticket_current_user = wp_get_current_user();
/**************** End Of Submit Data **********************/
	
?>

<div class="pho-tik-system"> 
 
<div class="container-fluid pst-container">

	<div class="row">
	
		<div class="col-lg-12 col-md-12 col-sm-12 pnt-main">
		
			<div class="row">
			
				<div class="pnt-new-tik">
				
					<div class="pnt-left-icon">
						<!-- icon for back button -->
						<?php
							if ($_SERVER['QUERY_STRING'] ){
								global $wp;
								$queryString_bs = $_SERVER['QUERY_STRING'];
								$queryString_bs = substr($queryString_bs, 0, strpos($queryString_bs, "&"));
								$site_url_avl_bs = home_url(add_query_arg(array(),$wp->request));
								$available_url_b_back = $site_url_avl_bs."/?".$queryString_bs;
								
							}else{ 
								global $wp;
								$site_url_avl_bs = home_url(add_query_arg(array(),$wp->request));
								$available_url_b_back = $site_url_avl_bs."/?view_id=1";
							}
						
						?>
						
						<a href="<?php echo $available_url_b_back ; ?>"> 
						   <div class="pnt-icons back-icon">
								<span class="glyphicon glyphicon-arrow-left"></span>
						   </div>   
						</a>
					</div> <!-- pnt-left-icon end -->
					
				</div> <!-- pnt-new-tik end -->
				
				<div class="pnt-new-tik-form-wrap">
				
					<form role="form" id="support-tik-form" class="support-ticket" method="POST" enctype="multipart/form-data">
					
					<?php wp_nonce_field( 'phoen_ticket_system_function', 'phoen_ticket_system_nonce_field' ); ?>
					
						<div class="form-group">
						
						  <label for="usr"><?php _e( 'Nome', 'support-ticket-system-by-phoeniixx' ); ?></label>
						  
						  <input readonly type="text" value="<?php echo $phoen_ticket_current_user->display_name;?>" class="form-control phoen_ticket_user_data" id="usr" placeholder="<?php _e( 'Customer Name', 'support-ticket-system-by-phoeniixx' ); ?>"  name="customer_name"/>
						
						</div>
						
						<div class="form-group">
						
							<label for="usr"><?php _e( 'Email', 'support-ticket-system-by-phoeniixx' ); ?></label>
						
						  <input readonly type="email" value="<?php echo $phoen_ticket_current_user->user_email;?>" class="form-control phoen_ticket_user_email" id="email" placeholder="customer@example.com" name="customer_email_id"/>
						
						</div>
						
						<div class="form-group">
						
						  <label for="usr"><?php _e( 'ASSUNTO', 'support-ticket-system-by-phoeniixx' ); ?></label>
						  
						  <input type="text" class="form-control phoen_ticket_subject_data" id="usr" placeholder="<?php _e( 'Insira o assunto do seu chamado. Ex: Trocas/Devolução', 'support-ticket-system-by-phoeniixx' ); ?>" name="custome_subject_detail"/>
						
						</div>
						
						<div class="form-group">
						
						  <label for="priority"><?php _e( 'Prioridade', 'support-ticket-system-by-phoeniixx' ); ?></label>
						  
						  <select class="form-control phoen_ticket_priority_data" name="customer_priority_set">
								<option></option>
								<option><?php _e( 'Baixa', 'support-ticket-system-by-phoeniixx' ); ?></option>
								<option><?php _e( 'Normal', 'support-ticket-system-by-phoeniixx' ); ?></option>
								<option><?php _e( 'Alta', 'support-ticket-system-by-phoeniixx' ); ?></option>
								<option><?php _e( 'Urgente', 'support-ticket-system-by-phoeniixx' ); ?></option>
						  </select>
						
						</div>
						
						<label for="usr"><?php _e( 'Informações (especifique seu problema ou solicitação)', 'support-ticket-system-by-phoeniixx' ); ?></label>
						
						<?php
						$phoen_ticket_enable_editor_create = get_option('phoen_ticket_enable_editor_create');
						if($phoen_ticket_enable_editor_create=='')
						{
						?>
							
						<div class="phoen_ticket_editor">
						
							<div class="summernote" name="summernote"></div> 
						
							<textarea type="text" class="hidden" name="content" id="phoen_content"><?php echo $_POST['content'];?></textarea>
						
						</div>
						
						<?php
						}else{
							?>
							<div class="phoen_ticket_aria">
						
								<textarea name="content" rows="3" cols="41"></textarea>
						
							</div>
							<?php
						}
						?>
						
						<div class="form-group">
						
							<input type="file" name="my_file_upload[]" multiple="multiple">
						
							<input type="submit" name="customer_detail_submit" value="<?php _e( 'ENVIAR', 'support-ticket-system-by-phoeniixx' ); ?>" class="phoen_ticket_submit">
							
						</div>
						
					</form>
				 
				</div> <!-- new-tik-form end -->
				
			</div> <!-- row end -->
		
		</div> <!-- col end -->
		
	</div> <!-- row end  -->
	
</div> <!-- container-fluid end -->

</div>

<script>

	jQuery(document).ready( function() {
		
		jQuery('.summernote').summernote();
		
		jQuery(".panel-body").on("mouseleave", function() {
			
			var phoen_ticket_datas = jQuery(this).html();
			
			jQuery('#phoen_content').text(phoen_ticket_datas); 
			
		});
	
	});

</script>