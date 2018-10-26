<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if(is_user_logged_in()){ 

global $wpdb, $current_user;

/*************** Get Ticket Data ********************/

$phoen_ticket_get_messages_details = array(

    'post_type'  => 'phoen_ticket_systems',
	
	'posts_per_page' => -1,
	
	'post_status'   => 'publish',
    
);

$phoen_get_messages_details_data = get_posts( $phoen_ticket_get_messages_details );

/********************* Count Open And Closed Tickets************************/

$phoen_ticket_count_open='0';

$phoen_ticket_closed_count='0';

$phoen_ticket_count_opens='0';

$phoen_ticket_closed_counts='0';

$phoen_ticket_count_open_customer='0';

$phoen_ticket_closed_count_customer='0';

for($i=0; $i<count($phoen_get_messages_details_data); $i++)
{
	
	$phoen_ticket_id=$phoen_get_messages_details_data[$i]->ID;
	
	$phoen_current_ticket=$phoen_get_messages_details_data[$i]->post_date;
	
	$phoen_ticket_post_meta = get_post_meta($phoen_ticket_id);
	
	$phoen_ticket_change_email =isset($phoen_ticket_post_meta['phoen_ticket_email_asigne_change'][0])?$phoen_ticket_post_meta['phoen_ticket_email_asigne_change'][0]:'';
	
	$phoen_ticket_status = isset($phoen_ticket_post_meta['_phoen_ticket_status'][0])?$phoen_ticket_post_meta['_phoen_ticket_status'][0]:'';
	
	$phoen_ticket_check_asigne = isset($phoen_ticket_post_meta['_phoen_ticket_agent_email'][0])?$phoen_ticket_post_meta['_phoen_ticket_agent_email'][0]:'';	
	
	$phoen_ticket_created_by = isset($phoen_ticket_post_meta['phoen_ticket_created_by'][0])?$phoen_ticket_post_meta['phoen_ticket_created_by'][0]:'';
			
	$phoen_ticket_current_user = wp_get_current_user();
	
	$phoen_ticket_curren_user = $phoen_ticket_current_user->user_email;
	
	$current_user = wp_get_current_user();
	
	$phoen_ticket_role = $current_user->roles[0];
	
	if($phoen_ticket_change_email == $phoen_ticket_curren_user)
	{	
	
		if($phoen_ticket_status == 'open')
		{
			
			$phoen_ticket_count_open+=count($phoen_ticket_status);
		
		}
		if($phoen_ticket_status == 'closed')
		{
			
			$phoen_ticket_closed_count+=count($phoen_ticket_status);
			
		}
	}
	
	if($phoen_ticket_curren_user == $phoen_ticket_created_by)
	{
	
		if($phoen_ticket_status == 'open')
		{
		
			$phoen_ticket_count_open_customer+=count($phoen_ticket_status);
		
		}
		
		if($phoen_ticket_status == 'closed')
		{
			$phoen_ticket_closed_count_customer+=count($phoen_ticket_status);
		}
	}
	
	
		
	if($phoen_ticket_status == 'open')
	{
		
		$phoen_ticket_count_opens+=count($phoen_ticket_status);
	
	}
	if($phoen_ticket_status == 'closed')
	{
		
		$phoen_ticket_closed_counts+=count($phoen_ticket_status);
		
	}
		
		
}

$phoen_view_id = $_GET['view_id'];

$phoen_get_customer_periority = get_post_meta($phoen_view_id, 'phoen_ticket_priority', true);

$time = current_time('mysql');

/**************** Insert Comment Data In Data Base ***************/

if(isset($_POST['comment_submit']))
{
	
	$phoen_ticket_comment_data = $_POST['comments_content'];
	
	$phoen_ticket_time = current_time('mysql');
	
	global $wpdb, $current_user;
	
	$phoen_ticket_user_ids = $current_user->data->ID;
	
	$user_logins = $current_user->data->user_login;
	
	$user_emails = $current_user->data->user_email;

	$post_id = $phoen_view_id;

	$phoen_ticket_system_data = array( 
	
		'comment_content' => $phoen_ticket_comment_data,
		
		'comment_type' => 'phoen_ticket_comment',
		
		'comment_approved' => 1,
		
		'comment_post_ID' => $post_id,
		
		'comment_date' => $phoen_ticket_time,
		
		'user_id' => $phoen_ticket_user_ids,
		
		'comment_author' => $user_logins,
		
        'comment_author_email' => $user_emails,
		
	);

	$comment_id = wp_insert_comment($phoen_ticket_system_data);
	
	update_post_meta($post_id, '_phoen_ticket_user_from', $phoen_ticket_user_ids);	
	
/************ There are uploading the image.**********************/
	
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
		
		require_once( ABSPATH . 'wp-admin/includes/media.php' );

		$phoen_support_ticket_files = isset($_FILES["my_file_upload"])?$_FILES["my_file_upload"]:'';
		
		foreach ($phoen_support_ticket_files['name'] as $key => $value) {
			
			if ($phoen_support_ticket_files['name'][$key]) {
				
				$phoen_ticket_system_file = array(
				
					'name' => $phoen_support_ticket_files['name'][$key],
					
					'type' => $phoen_support_ticket_files['type'][$key],
					
					'tmp_name' => $phoen_support_ticket_files['tmp_name'][$key],
					
					'error' => $phoen_support_ticket_files['error'][$key],
					
					'size' => $phoen_support_ticket_files['size'][$key]
					
				);
				
				$_FILES = array("upload_file" => $phoen_ticket_system_file);
				
				$attachment_id = media_handle_upload("upload_file", 0);

				if (is_wp_error($attachment_id)) {
					
					echo "Error adding file";
				} else {
					
					$phoen_ticket_ufiles[] = isset($attachment_id)?$attachment_id:'';
				
				}
			}
		}
	} 
	
	if(isset($phoen_ticket_ufiles))
	{
		update_comment_meta( $comment_id, '_phoe_ticket_attachments', $phoen_ticket_ufiles );
	}
	
}

/****************** Get Comment Data In Data Base **************************/

	$ticket_info = get_post($_GET['view_id']);	
	
	
	$phoen_ticket_comments_args = array(
	
		'post_id' => $phoen_view_id,
		
		'orderby' => 'comment_date_gmt',
		
		'order' => 'ASC',
		
	);
	
	$phoen_ticket_data=get_post_meta($phoen_view_id);
	
	$current_user = wp_get_current_user();
	
	$phoen_ticket_role = $current_user->roles[0];
	
	if($phoen_ticket_role =='administrator')
	{
		update_post_meta($phoen_view_id,'_phoen_ticket_admin_status','read');
		
	}else if($phoen_ticket_role =='phoen_agent'){
		
		update_post_meta($phoen_view_id,'_phoen_ticket_access_status','read');
		
	}else if($phoen_ticket_role =='customer' || $phoen_ticket_role =='subscriber'){
		
		update_post_meta($phoen_view_id,'_phoen_ticket_customer_status','read');
	}
	$ticket_comments_array = get_comments( $phoen_ticket_comments_args );
	
	$current_user=wp_get_current_user();
	
	$phoen_agentss=get_users();
	
	$phoen_agents = get_users(array('role'=>'phoen_agent'));
	
	$phoen_get_new_ticket =isset($_GET['phoen_new_ticket'])?$_GET['phoen_new_ticket']:'';

	$phoen_get_closed_tickets =isset($_GET['phoeni_closed_tickets'])? $_GET['phoeni_closed_tickets']:'';	

	$phoen_comment_contents='';

	foreach($ticket_comments_array as $key => $phoen_comment_conent)
	{
		$phoen_comment_content= $phoen_comment_conent->comment_content;
		if($phoen_comment_content!='')
		{
			$phoen_comment_contents=$phoen_comment_content;
		}
	}
	
	$phoen_view_id = $_GET['view_id'];
	
	$phoen_assign_agent=get_post_meta($phoen_view_id);

	$phoen_user_id=isset($phoen_assign_agent['_phoen_ticket_assine_id'][0])?$phoen_assign_agent['_phoen_ticket_assine_id'][0]:'';
	
	$phoen_ticket_subject = get_post($phoen_view_id);
	
	$phoen_agent_subject = $phoen_ticket_subject->post_excerpt;
	
	$phoen_agent_data = get_userdata( $phoen_user_id );
	
	$phoe_agent_email = isset($phoen_agent_data->data->user_email)?$phoen_agent_data->data->user_email:'';
	$phoen_customer_email = get_post_meta($phoen_view_id,'phoen_ticket_email',true);
	
	if($phoen_user_id == '')
	{
		$admin_mail = get_option('admin_email');
		
		$headers = array('Content-Type: text/html; charset=UTF-8');
	
		$subject =   "[".$phoen_agent_subject." "."Ticket ID:".$phoen_view_id."]";
		
		
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
																<p>'.$phoen_comment_contents.'</p>
																
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
			
		if($comment_id!='' && $phoen_comment_contents!='')
		{
	
			wp_mail($admin_mail,$subject, $msg,$headers);
			
			wp_mail($phoen_customer_email,$subject, $msg,$headers);
		
		}
		
	}else{
		
		$headers = array('Content-Type: text/html; charset=UTF-8');
	
		$subject = "[".$phoen_agent_subject." "."Ticket ID:".$phoen_view_id."]";
		
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
																<p>'.$phoen_comment_contents.'</p>
																
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
		
		if($comment_id!='' && $phoen_comment_contents!='')
		{
			wp_mail($phoe_agent_email,$subject, $msg,$headers);
			wp_mail($phoen_customer_email,$subject, $msg,$headers);
		}
	
	}
	
?>
<div class="pho-tik-system">  

	<div class="container-fluid pst-container pst-head-wrap">

		<div class="col-lg-12 col-md-12 col-sm-12">
		
			<div class="row">
				
				<div class="nav_icon_mobile">
				
					<span class="glyphicon glyphicon-menu-hamburger"></span>
					
				</div>
				
				<div class="pst-search-bar">
				
					<form class="pst-navbar-form" role="search">
					
					  <span class="glyphicon glyphicon-search"></span>
					  
					  <input type="search" placeholder="<?php _e( 'Procurar chamado', 'support-ticket-system-by-phoeniixx' ); ?>" class="phoen_filter_messege"/>
					  
					</form>
					
				</div>
				
			</div>
		
		</div>
		
	</div>
</div> <!-- pho-tik-system end -->
 
	
<div class="pho-tik-system">
<div class="container-fluid pst-container">
<div class="row">

   <div class="col-lg-3 col-md-3 col-sm-3 pst-user-panel-wrap">
		
		<?php
	
		$phoen_curren_user = $current_user->user_email;
		
		$current_user = wp_get_current_user();
		
		$phoen_ticket_role = $current_user->roles[0];
		
		if ($_SERVER['QUERY_STRING']){
			global $wp;
			$queryStrings = $_SERVER['QUERY_STRING'];
			if (strpos($queryStrings, '&') !== false) {
				$queryStrings = substr($queryStrings, 0, strpos($queryStrings, "&"));
			}
			
			$site_url_avlss = home_url(add_query_arg(array(),$wp->request));
			$available_url_creates = $site_url_avlss."/?".$queryStrings."&create_id=1";
			
		}else{ 
			global $wp;
			$site_url_avlss = home_url(add_query_arg(array(),$wp->request));
			$available_url_creates = $site_url_avlss."/?create_id=1";
		 }
		 
		 
		if ($_SERVER['QUERY_STRING']){
			global $wp;
			$queryString_cl = $_SERVER['QUERY_STRING'];
			if (strpos($queryString_cl, '&') !== false) {
				$queryString_cl = substr($queryString_cl, 0, strpos($queryString_cl, "&"));
			}
			
			$site_url_avls_cl = home_url(add_query_arg(array(),$wp->request));
			$available_url_cl = $site_url_avls_cl."/?".$queryString_cl."&phoeni_closed_tickets=2";
			
			
		}else{ 
			global $wp;
			$site_url_avls_cl = home_url(add_query_arg(array(),$wp->request));
			$available_url_cl = $site_url_avls_cl."/?phoeni_closed_tickets=2";
			
			
		}
			
		if ($_SERVER['QUERY_STRING']){
			global $wp;
			$queryString_op = $_SERVER['QUERY_STRING'];
			if (strpos($queryString_op, '&') !== false) {
				$queryString_op = substr($queryString_op, 0, strpos($queryString_op, "&"));
			}
			
			$site_url_avls_op = home_url(add_query_arg(array(),$wp->request));
			$available_url_op = $site_url_avls_op."/?".$queryString_op."&phoen_new_ticket=1";
			
		}else{ 
		
			global $wp;
			$site_url_avls_op = home_url(add_query_arg(array(),$wp->request));
			$available_url_op = $site_url_avls_op."/?phoen_new_ticket=1";
		
		} 
		
		if($current_user->roles[0] == 'administrator')
		{
			?>
			<div class="pst-user-panel">
				
			   <div class="pst-user-list">
				
					<a href="<?php echo $available_url_op ; ?>"> <h3> <span class="glyphicon glyphicon-folder-open"></span><?php _e( 'Tickets abertos', 'support-ticket-system-by-phoeniixx' ); ?>  <span class="ticket-number"><?php echo $phoen_ticket_count_opens; ?></span> </h3></a>
				
				</div>
				
			   <div class="pst-user-list">
			   
					<a href="<?php echo $available_url_cl ; ?>"> <h3> <span class="glyphicon glyphicon-ok"></span><?php _e( 'Tickets resolvidos', 'support-ticket-system-by-phoeniixx' ); ?>  <span class="ticket-number"><?php echo $phoen_ticket_closed_counts;?></span> </h3></a>
			   
				</div>
				
				<div class="pnt-submit-new-ticket">
				
					<a href="<?php echo $available_url_creates ; ?>"><?php _e( 'Create New Ticket', 'support-ticket-system-by-phoeniixx' ); ?></a>
				
				</div>
				
			</div><!-- pst-user-panel end -->
			<?php
		}else if($current_user->roles[0] == 'customer' || $current_user->roles[0] == 'subscriber'){
			
			?>
			<div class="pst-user-panel">
				
			   <div class="pst-user-list">
				
					<a href="<?php echo $available_url_op ; ?>"> <h3> <span class="glyphicon glyphicon-folder-open"></span><?php _e( 'Open Tickets', 'support-ticket-system-by-phoeniixx' ); ?>  <span class="ticket-number"><?php echo $phoen_ticket_count_open_customer; ?></span> </h3></a>
				
				</div>
				
			   <div class="pst-user-list">
			   
					<a href="<?php echo $available_url_cl ; ?>"> <h3> <span class="glyphicon glyphicon-ok"></span><?php _e( 'Closed Tickets', 'support-ticket-system-by-phoeniixx' ); ?>  <span class="ticket-number"><?php echo $phoen_ticket_closed_count_customer;?></span> </h3></a>
			   
				</div>
				
				<div class="pnt-submit-new-ticket">
				
					<a href="<?php echo $available_url_creates; ?>"><?php _e( 'Create New Ticket', 'support-ticket-system-by-phoeniixx' ); ?></a>
				
				</div>
				
			</div><!-- pst-user-panel end -->
			<?php
		}else if($current_user->roles[0] == 'phoen_agent'){
			?>
			<div class="pst-user-panel">
				
			   <div class="pst-user-list">
				
					<a href="<?php echo $available_url_op ; ?>"> <h3> <span class="glyphicon glyphicon-folder-open"></span><?php _e( 'Open Tickets', 'support-ticket-system-by-phoeniixx' ); ?>  <span class="ticket-number"><?php echo $phoen_ticket_count_open; ?></span> </h3></a>
				
				</div>
				
			   <div class="pst-user-list">
			   
					<a href="<?php echo $available_url_cl ; ?>"> <h3> <span class="glyphicon glyphicon-ok"></span><?php _e( 'Closed Tickets', 'support-ticket-system-by-phoeniixx' ); ?>  <span class="ticket-number"><?php echo $phoen_ticket_closed_count;?></span> </h3></a>
			   
				</div>
				
				<div class="pnt-submit-new-ticket">
				
					<a href="<?php echo $available_url_creates; ?>"><?php _e( 'Create New Ticket', 'support-ticket-system-by-phoeniixx' ); ?></a>
				
				</div>
				
			</div><!-- pst-user-panel end -->
			<?php
		} 
		?>
		
    </div>
    
    <div class="col-lg-9 col-md-9 col-sm-12 pnt-main">
    <div class="row">
        <div class="pnt-new-tik">
        
            <div class="pnt-left-icon">
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
                <!-- icon for back button -->
				<a href="<?php echo $available_url_b_back ; ?>">
				
					<div class="pnt-icons back-icon">
					   
					   <span class="glyphicon glyphicon-arrow-left"></span>
				  
					</div> 
			   
			   </a>
			   
			   <!-- form for open and assign user -->
			     
				   <div class="pho-user-asign">
						
						<div class="form-group">
							<span class="caret pho_dropdown_arrow"></span>
							<select class="form-control"  name="phoe_select_status">
							  
								<option <?php echo ($phoen_get_customer_periority == 'LOW')?'selected':'';?>><?php _e( 'Baixa', 'support-ticket-system-by-phoeniixx' ); ?></option>
								<option <?php echo ($phoen_get_customer_periority == 'Normal')?'selected':'';?> ><?php _e( 'Normal', 'support-ticket-system-by-phoeniixx' ); ?></option>
								<option <?php echo ($phoen_get_customer_periority == 'High')?'selected':'';?>><?php _e( 'Alta', 'support-ticket-system-by-phoeniixx' ); ?></option>
								<option <?php echo ($phoen_get_customer_periority == 'Urgent')?'selected':'';?>><?php _e( 'Urgente', 'support-ticket-system-by-phoeniixx' ); ?></option>
								
							</select>
							  
						</div>
						<?php 
						$current_user=wp_get_current_user();
						
						if(($current_user->roles[0]== "administrator") || ($current_user->roles[0]== "phoen_agent"))
						{
						
							?>
							<div class="form-group assign-user">
							
								<span class="caret pho_dropdown_arrow"></span>
								  <select class="form-control-assine">
								  
								  <option><?php _e( 'select agent', 'support-ticket-system-by-phoeniixx' ); ?></option>
									  <?php foreach($phoen_agents as $keys=> $phoe_agents_data)
									  {
										 
										  $phoen_datass=$phoe_agents_data->data->user_login;
										  
										   $phoen_agent_email = $phoe_agents_data->data->user_email;
										
										  ?>
										  <option agent_email="<?php echo $phoen_agent_email ; ?> " <?php echo (isset($phoen_user_id) && ($phoen_user_id == $phoe_agents_data->data->ID ))?'selected':'';?> value="<?php echo $phoe_agents_data->data->ID ; ?>"> <?php echo $phoen_datas= $phoe_agents_data->data->user_login; ?> </option>
										  <?php
										
									  }
									  ?>
					
								  </select>
								  
							</div>
				<?php   } ?>
						</div>
					   
					   <?php
             
			   ?>
            </div> <!-- pnt-left-icon end -->
            
			<div class="pnt-right-icon">
			
				<div title="Close Ticket">
				
					<span class="glyphicon glyphicon-ok close_tiket" data-ids="<?php echo $phoen_view_id; ?>"></span>
				
				</div>
				
			</div> <!-- pnt-right-icon end -->
            
        </div> <!-- pnt-new-tik end -->
		
		<div class="pnt-ticket-detail-wrap">
        <div class="pnt-ticket-detail-main">
        <div class="ticket-user-profile"> 
		
				<div class="user-detail">
				
					<h5><?php _e( 'Email ID', 'support-ticket-system-by-phoeniixx' ); ?> </h5>	
					
					   <span class="user-email">
						 
							<?php $user_emails = $current_user->data->user_email;

								echo $user_emails;
							?>
							
						</span>
					
				</div>
        
		</div>

			<div class="pnt-ticket-detail">
			
				<h5><?php _e( 'Ticket ID', 'support-ticket-system-by-phoeniixx' ); ?>
				
					<span>
					
						<?php 
						
						$phoen_view_id= $_GET['view_id'];
						
						echo "#".$phoen_view_id;
						
						?>
					
					</span>
				
				</h5>
				
				<h5><?php _e( 'Status', 'support-ticket-system-by-phoeniixx' ); ?>
					
						<?php
						
						$phoen_ticket_system_status=array();
						
						foreach($phoen_get_messages_details_data as $key => $phoen_tickets_status)
						{
							$phoen_ticket_idss = $phoen_tickets_status->ID;
							
							$phoen_ticket_status=get_post_meta($phoen_ticket_idss);
							
							$phoen_view_id= $_GET['view_id']; 
							
							if($phoen_ticket_idss == $phoen_view_id)
							{
								$phoen_ticket_system_status = $phoen_ticket_status['_phoen_ticket_status'][0];
							
							}
						}
						
					if($phoen_ticket_system_status=="open")	
					{
						?>
						<span class="phoen_open_status">
						
							<?php echo $phoen_ticket_system_status  ; ?> 
							
						</span>
						<?php
					}else if($phoen_ticket_system_status =='closed'){
						?>
						<span class="phoen_close_status">
						
							<?php echo $phoen_ticket_system_status  ; ?> 
							
						</span>
						<?php
					}
						?>
						
					
					
				
				</h5> 
				
				<h5><?php _e( 'Prioridade', 'support-ticket-system-by-phoeniixx' ); ?> 
				
					<span class="phoen_preiority">
						
						<?php 
						
						if ($phoen_get_customer_periority =='')	
						{
							echo '-' ;
							
						}else{
							
							echo $phoen_get_customer_periority ;
						}
						?>
						
					</span>
					
				</h5>
           
			</div>
			
        </div> <!-- pnt-ticket-main end -->
		
    </div> <!-- pnt-ticket-wrap end -->
        
		<?php
		$phoen_comment_satatus='0';
		
		$phoen_post_id='0';
		
		for($i=0; $i<count($phoen_get_messages_details_data); $i++)
		{
			
			$phoen_view_id= $_GET['view_id'];
			
			$phoen_post_id=$phoen_get_messages_details_data[$i]->ID;
						
			if($phoen_post_id==$phoen_view_id)
			{
			?>
			
			<div class="pnt-msg-box">
			
				<div class="user-image"><?php echo get_avatar( $user_emails, 60 ); ?></div>
			
				<div class="msg-body">
				
						<div class="ticket-details">
					
							<div class="customer-name"><?php echo $phoen_get_messages_details_data[$i]->post_title;?></div>
							
							<div class="msg-time"><?php echo $phoen_get_messages_details_data[$i]->post_date; ?></div>
						
							<div class="msg_subject"><?php echo $phoen_get_messages_details_data[$i]->post_excerpt ;?> </div>
							
						</div>
					
						<div class="msg-description">
						
							<p><?php echo $phoen_get_messages_details_data[$i]->post_content; ?></p>
                     
						</div>
						
						<?php
					
						$phoen_new_mesg_attechedd = get_post_meta( $phoen_post_id, '_phoen_new_ticket_file', true );
						
						if(is_array($phoen_new_mesg_attechedd)) {
							
							foreach($phoen_new_mesg_attechedd as $keys=> $phoen_new_mesg_atteched)
							{
								?>
								<a class="phoen_ticket_files" target="_blank" href="<?php echo wp_get_attachment_url($phoen_new_mesg_atteched); ?>" > <?php echo wp_get_attachment_image($phoen_new_mesg_atteched, array(50, 50)); ?> </a>
								<?php
							}
    
						}
					
					?>
					
				</div>
				
			</div> <!-- pnt-msg-box end -->
			<?php
			
			}
		}
		
		for($i=0; $i<count($ticket_comments_array); $i++)
		{
		
			$ticket_comments_array[$i]->comment_post_ID;
			
			$comment_ids=$ticket_comments_array[$i]->comment_ID;
		
	?>
			<div class="pnt-notes-sec">
			
				<div class="notes-wrap">
				
					<div class="user-img"><?php echo get_avatar( $user_emails, 40 ); ?></div>
					
					<div class="user-note">
					
						<div class="ticket-details">
						
							<div class="customer-name"><?php echo $ticket_comments_array[$i]->comment_author ;?></div>
							
							<div class="msg-time"><?php echo $ticket_comments_array[$i]->comment_date;?></div>
							
						</div>
						
						<div class="notes-msg-body">
						
							<p><?php echo $ticket_comments_array[$i]->comment_content; ?></p>
						
							<?php
							
							$comment_attachment_url = get_comment_meta($comment_ids, '_phoe_ticket_attachments', true);
							 
							if(is_array($comment_attachment_url))
							{
								foreach($comment_attachment_url as $key => $comment_attachment_urls )
								{
								
									?>
									
									<a  class="phoen_ticket_files" target="_blank" href="<?php echo wp_get_attachment_url($comment_attachment_urls); ?>" > <?php echo wp_get_attachment_image($comment_attachment_urls, array(50, 50)); ?> </a>
									
									<?php
								
								}
								
							}
							?>
							
							
						
						</div>
						
					</div>
					
				</div>
				
			</div> <!-- pnt-notes-sec end -->
			
	<?php } ?>	
		<form method="POST" enctype="multipart/form-data">
		
			<div class="pnt-add-note-sec">
			
				<div class="pnt-add-note-wrap">
				
					<div class="add-note-text">
					
					<?php
						$phoen_ticket_enable_editor = get_option('phoen_ticket_enable_editor');
						if($phoen_ticket_enable_editor=='1')
						{
							?>
							<div class="phoen_ticket_aria">
						
								<textarea name="comments_content" rows="3" cols="41"></textarea>
						
							</div>
							<?php
							
						}else{
							
							
							?>
							<div class="phoen_ticket_editor">
						
								<div class="summernote" name="summernote"></div> 
							
								<textarea type="text" class="hidden" name="comments_content" id="phoen_comment_content"><?php echo $_POST['comments_content'];?></textarea>
						
							</div>
							
							<?php
							
						}
						?>
					
						<div class="pnt-note-text-foot">
						
							<div class="note-text-left">
								
								 <input type="file" name="my_file_upload[]" multiple="multiple">
							
							</div>
							
						</div> <!-- pnt-note-text-foot end -->
						
					</div> <!-- add-note-text end -->
					
					<input type="submit" name="comment_submit" value="ENVIAR">
					
				</div> <!-- pnt-add-note-wrap end -->
				
			</div> <!-- pnt-add-note-sec end -->
			
        </form>
		<style>
		.note-btn.btn.btn-default.btn-sm.btn-codeview{
			dispaly:none!important;
		}
		</style>
    </div> <!-- row end -->
	
    </div> <!-- col end -->
    
</div> <!-- row end -->

</div> <!-- container-fluid end -->

</div>
  
    <script>
	
		jQuery(document).ready(function(){
			
			jQuery(".close_tiket").click(function(){
		
				var phoen_ticket_completed = "closed"; 
				
				var phoen_camp_id = jQuery(this).attr('data-ids');
				
				var phoen_ticket_newurl = '<?php echo admin_url('admin-ajax.php') ;?>';
				
				jQuery.post(
					
					phoen_ticket_newurl,
					{
						'action'	:  'phoe_ticket_completed',
						'data'		:	phoen_ticket_completed,
						'get_values':	phoen_camp_id
						
					},
					function(response){
						
						if(response == 'ticket_completed')
						{
							window.location.href = '<?php echo site_url()."/ticket_system/";?>';
							
						} 
					
					}
					
					
				);
			
			});
			
			jQuery('.summernote').summernote();
			
			jQuery('.panel-body').on("mouseleave",function(){
				
				var phoen_comment_contents = jQuery(this).html();
				
				jQuery('#phoen_comment_content').text(phoen_comment_contents);
				
			});
		
		});	
 
    </script>
	
<?php
}else{
		$phoen_ticket_login_url = get_option('_phoen_ticket_login_urls');
		
		if($phoen_ticket_login_url == '')
		{
			 ?> 
			<a href="<?php echo wp_login_url(). '?redirect_to=' . esc_url($_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);?>" title="Login"><?php _e( 'Login', 'support-ticket-system-by-phoeniixx' ); ?></a>
			<?php 
			
		}else{
			?>
			<a href="<?php echo $phoen_ticket_login_url; ?>" title="Login"><?php _e( 'Login', 'support-ticket-system-by-phoeniixx' ); ?></a>
			<?php
		}
		$phoen_tickets_register_urls = get_option('_phoen_ticket_register_urls');
		
		if($phoen_tickets_register_urls=='')
		{ 
			echo apply_filters( 'register', sprintf( '<a href="%s">%s</a>', esc_url( wp_registration_url()), __( 'Register' ) ) ); 
			
		}else{
			
			?>
			<a href="<?php echo $phoen_tickets_register_urls; ?>"><?php _e( 'Register', 'support-ticket-system-by-phoeniixx' ); ?></a>
			
			<?php
		}
	  
  }?>