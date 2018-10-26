<?php
/*
** Plugin Name: Support Ticket System By Phoeniixx
** Plugin URI: www.phoeniixx.com
** Version:1.9
** Author: phoeniixx
** Text Domain: support-ticket-system-by-phoeniixx
** Domain Path: /languages/
** Author URI: http://www.phoeniixx.com/
** Description: What does your plugin do and what features does it offer...
** WC requires at least: 2.6.0
** WC tested up to: 3.3.5
*/

if ( ! defined( 'ABSPATH' ) ) exit;

function phoen_ticket_add_roles_on_plugin_activation() {
	
   add_role( 'phoen_agent', 'Agent', array( 'read' => true, 'level_0' => true ) );
  
   if (get_page_by_title('ticket_system') == NULL) 
	{
	   
		$phoen_ticket_post = array(
		
			'comment_status' => 'open',
			
			'ping_status' => 'closed',
			
			'post_content' => '[phoen_ticket_system]',
			
			'post_date' => date('Y-m-d H:i:s'),
			
			'post_name' => 'ticket_system',
			
			'post_status' => 'publish',
			
			'post_title' => 'Ticket system',
			
			'post_type' => 'page',
			
		);
			
		//insert page and save the id
		
		$phoen_ticket_new_value = wp_insert_post($phoen_ticket_post, false);
		
		//save the id in the database
		
		update_option('ticket_system', $phoen_ticket_new_value);
		
	}
	
	$phoen_ticket_enable_settings = get_option('phoen_ticket_system_enable');
		
	if($phoen_ticket_enable_settings == ''){
		
		update_option('phoen_ticket_system_enable',1);
		
	}
	
}

register_activation_hook( __FILE__, 'phoen_ticket_add_roles_on_plugin_activation' );

	global $woocommerce;

function phoen_ticket_my_account_order_actions( $actions, $order ) {

// $phoen_create_ticket_by_order=site_url()."/my-account/support-ticket/?create_id=1&order_id=".$order->id;
$phoen_create_ticket_by_order=get_permalink( get_option('woocommerce_myaccount_page_id') )."/support-ticket/?create_id=1&order_id=".$order->id;

$phoen_order_ticket_created = get_post_meta($order->id,'phoen_create_ticket_by_order',true);
	
	if($phoen_order_ticket_created=='')
	{

		$actions['ticket'] = array(
			'url'=>$phoen_create_ticket_by_order,
			'name' => __( 'Create Ticket', 'support-ticket-system-by-phoeniixx' ),
		);
		
	}else{
		
		$phoen_created_ticket_by_order=site_url()."/my-account/support-ticket/?phoeni_closed_tickets=2&view_id=".$phoen_order_ticket_created;
		$actions['ticket'] = array(
			'url'=>$phoen_created_ticket_by_order,
			'name' => __( 'View Ticket', 'support-ticket-system-by-phoeniixx' ),
		);
		
	}

	?>
	<style>
	.woocommerce-button.button.ticket {
		margin: 0 0 0 6px;
	}
	</style>
	<?php	
	
    return $actions;
}

$phoen_advance_reporting_order = get_option('phoen_advance_reporting_order');
if($phoen_advance_reporting_order=='1')
{
	add_action( 'woocommerce_order_details_after_order_table', 'phoen_ticket_add_notes_to_order',10,1 );
	add_filter( 'woocommerce_my_account_my_orders_actions', 'phoen_ticket_my_account_order_actions', 10, 2 );
}


function phoen_ticket_add_notes_to_order($order){
	
	
	$phoen_create_ticket_by_order=site_url()."/my-account/support-ticket/?create_id=1&order_id=".$order->id;
	
	$phoen_order_ticket_created = get_post_meta($order->id,'phoen_create_ticket_by_order',true);
	
	if($phoen_order_ticket_created=='')
	{
	   ?>
		<a class="button phoen_ticket_create_order" href="<?php echo $phoen_create_ticket_by_order ;?>" data-index="<?php echo $order->id ; ?>"><?php _e( 'Create Ticket', 'support-ticket-system-by-phoeniixx' ); ?></a>
		<?php
	}else{
		$phoen_created_ticket_by_order=site_url()."/my-account/support-ticket/?phoeni_closed_tickets=2&view_id=".$phoen_order_ticket_created;
		?>
		<a class="button phoen_ticket_create_order" href="<?php echo $phoen_created_ticket_by_order ;?>" data-index="<?php echo $order->id ; ?>"><?php _e( 'View Ticket', 'support-ticket-system-by-phoeniixx' ); ?></a>
		<?php
	}
	?>
	<style>
	.button.phoen_ticket_create_order {
		margin: 10px 0 !important;
	}
	.woocommerce-button.button.ticket {
		margin: 0 0 0 6px;
	}
	</style>
	<?php
}





//add menu in my account 

/* function bbloomer_add_premium_support_endpoint() {
    add_rewrite_endpoint( 'premium-support', EP_ROOT | EP_PAGES );
}
 
add_action( 'init', 'bbloomer_add_premium_support_endpoint' );

function bbloomer_premium_support_query_vars( $vars ) {
    $vars[] = 'premium-support';
    return $vars;
}
 
add_filter( 'query_vars', 'bbloomer_premium_support_query_vars', 0 );

// 3. Insert the new endpoint into the My Account menu
 
function bbloomer_add_premium_support_link_my_account( $items ) {
    $items['premium-support'] = 'Premium Support';
    return $items;
}
 
add_filter( 'woocommerce_account_menu_items', 'bbloomer_add_premium_support_link_my_account' );
 
 
// ------------------
// 4. Add content to the new endpoint
 
function bbloomer_premium_support_content() {

 echo do_shortcode('[phoen_ticket_system]' ); 
}
 
add_action( 'woocommerce_account_premium-support_endpoint', 'bbloomer_premium_support_content' ); */










function phoen_bbloomer_add_premium_support_endpoint() {
    add_rewrite_endpoint( 'support-ticket', EP_ROOT | EP_PAGES );
}
 
add_action( 'init', 'phoen_bbloomer_add_premium_support_endpoint' );

function phoen_bbloomer_premium_support_query_vars( $vars ) {
    $vars[] = 'support-ticket';
    return $vars;
}
 
add_filter( 'query_vars', 'phoen_bbloomer_premium_support_query_vars', 0 );

// 3. Insert the new endpoint into the My Account menu
 
function phoen_bbloomer_add_premium_support_link_my_account( $items ) {
    $items['support-ticket'] = 'Chamados';
    return $items;
}
 
add_filter( 'woocommerce_account_menu_items', 'phoen_bbloomer_add_premium_support_link_my_account' );
 
 
// ------------------
// 4. Add content to the new endpoint
 
function phoen_bbloomer_premium_support_content() {

 echo do_shortcode('[phoen_ticket_system]' ); 
}
 
add_action( 'woocommerce_account_support-ticket_endpoint', 'phoen_bbloomer_premium_support_content' );




/* add_action('init','phoen_ticket_create_order');
function phoen_ticket_create_order(){
	?>
	<script>
	jQuery(".ticket").on("click", function () {
		alert();
		
		jQuery.cookie('ticket_order_id', '1', { expires: 1 });
		
	});
	</script>
	<?php
} */



/***************** Change Status Function ******************************/

add_action( 'wp_ajax_phoe_ticket_status', 'phoen_ticket_message_status' );

add_action( 'wp_ajax_nopriv_phoe_ticket_status', 'phoen_ticket_message_status' );

function phoen_ticket_message_status()
{
	
	$phoen_tickets_priority  = sanitize_text_field($_POST['data']);
	
	$phoen_post_ticket_id = sanitize_text_field($_POST['get_val']);
	
	echo $_POST['data'];
	
	update_post_meta($phoen_post_ticket_id,'phoen_ticket_priority',$phoen_tickets_priority);
	
	die();

}


/***************** End Change Status Function **************************/

/***************** Assigned Agent Function ******************************/

add_action( 'wp_ajax_phoe_ticket_assine', 'phoen_ticket_message_assine' );

add_action( 'wp_ajax_nopriv_phoe_ticket_assine', 'phoen_ticket_message_assine' );

function phoen_ticket_message_assine()
{
		
	$header = "MIME-Version: 1.0\r\n";
	
	$header .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

	$phoen_ticket_assigns_data = sanitize_text_field($_POST['data']);
	
	echo $_POST['phoen_ticket_agent_email'];
	 
	$phoen_ticket_id = sanitize_text_field($_POST['get_val']);
	 
	$phoen_ticket_agent_ids= sanitize_text_field($_POST['phoen_agent_id']);
	
	$phoen_ticket_get_post = get_post($phoen_ticket_id);
	 
	$phoen_ticket_subject = $phoen_ticket_get_post->post_excerpt;
	
	$phoen_ticket_agent = sanitize_text_field($_POST['ticket_agent_email']);
	
	update_post_meta($phoen_ticket_id,'_phoen_ticket_assine',$phoen_ticket_assigns_data);
	
	update_post_meta($phoen_ticket_id,'_phoen_ticket_assine_id',$phoen_ticket_agent_ids);
	
	$current_user = wp_get_current_user();
	
	$phoen_ticket_role = $current_user->roles[0];
	
	update_post_meta($phoen_ticket_id,'_phoen_ticket_access_status','unread');
	
	$phoen_ticket_agents = get_userdata( $phoen_ticket_agent_ids );
	
	$phoen_ticket_system_agent = $phoen_ticket_agents->data->user_email;
	
	update_post_meta($phoen_ticket_id,'phoen_ticket_email_asigne_change',$phoen_ticket_system_agent);
	
	update_post_meta($phoen_ticket_id,'_phoen_ticket_agent_email',$phoen_ticket_system_agent);
	
	$phoen_ticket_url = '<a href="'.site_url().'/my-account/support-ticket/?phoeni_closed_tickets=2&view_id='.$phoen_ticket_id.'">click here </a>';
	
	$phoen_ticket_link= 'You have been assigned Ticket '.$phoen_ticket_id.'<br />'.$phoen_ticket_url;
	
	
	
	$headers = array('Content-Type: text/html; charset=UTF-8');
	
	$subject ="[".$phoen_ticket_subject." "."Ticket ID:".$phoen_ticket_id." "."Assigned"."]";
	
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
															<p>'.$phoen_ticket_link.'</p>
															
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
	
	wp_mail($phoen_ticket_system_agent,$subject , $msg,$headers);
	
	die();
	
}

/***************** End Assigned Agent Function ******************************/

/***************** Completed Ticket Function ******************************/

add_action( 'wp_ajax_phoe_ticket_completed', 'phoen_ticket_message_completed' );

add_action( 'wp_ajax_nopriv_phoe_ticket_completed', 'phoen_ticket_message_completed' );

function phoen_ticket_message_completed()
{

	$phoen_ticket_complets = sanitize_text_field($_POST['data']);
	
	$phoen_ticket_complet_id = sanitize_text_field($_POST['get_values']);
	 
	$phoen_ticket_completed = update_post_meta($phoen_ticket_complet_id,'_phoen_ticket_status',$phoen_ticket_complets);
	
	$phoen_ticket_get_post = get_post($phoen_ticket_complet_id);
	
	$phoen_ticket_subject = $phoen_ticket_get_post->post_excerpt;
	
	$phoen_ticket_current_user = wp_get_current_user();
	
	$phoen_ticket_user_role = $phoen_ticket_current_user->roles[0];
	
	$phoen_ticket_admin_mail = get_option('admin_email');
	
	
	$headers = array('Content-Type: text/html; charset=UTF-8');
	
	$subject = "[".$phoen_ticket_subject." "."Ticket ID:".$phoen_ticket_complet_id." "."Closed"."]" ;
	
	$phoen_ticket_closed = "Ticket closed";
	
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
															<p>'.$phoen_ticket_closed.'</p>
															
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
	
	
	wp_mail($phoen_ticket_admin_mail,$subject,$msg,$headers);
	
	$phoen_ticket_data=get_post_meta($phoen_ticket_complet_id);

	$phoen_customer_mail = $phoen_ticket_data['phoen_ticket_email'][0];
	
	wp_mail($phoen_customer_mail,$subject, $msg,$headers);
	
	if($phoen_ticket_completed == 1)
	{
		echo "ticket_completed";
	}  
	
	die();
	
}

add_action( 'wp_ajax_phoe_ticket_completed_data', 'phoen_ticket_message_completed_data' );

add_action( 'wp_ajax_nopriv_phoe_ticket_completed_data', 'phoen_ticket_message_completed_data' );

function phoen_ticket_message_completed_data()
{	
	$phoen_ticket_completed_id= sanitize_text_field($_POST['camplet_id']);
	
	$phoen_ticket_campleted_data= sanitize_text_field($_POST['data']);
	
	$phoen_ticket_meta_updatede = update_post_meta($phoen_ticket_completed_id,'_phoen_ticket_status',$phoen_ticket_campleted_data);
	
	$phoen_ticket_get_post = get_post($phoen_ticket_completed_id);
	
	
	$phoen_ticket_subject = $phoen_ticket_get_post->post_excerpt;
	
	$phoen_ticket_current_user = wp_get_current_user();
	
	$phoen_ticket_user_role = $phoen_ticket_current_user->roles[0];

	$phoen_ticket_admin_mail = get_option('admin_email');
	
	$headers = array('Content-Type: text/html; charset=UTF-8');
	
	$subject =  "[".$phoen_ticket_subject." "."Ticket ID:".$phoen_ticket_completed_id." "."Closed"."]";
	
	$phoen_ticket_closed = "Ticket closed";
	
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
															<p>'.$phoen_ticket_closed.'</p>
															
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
	
	
	
	
	wp_mail($phoen_ticket_admin_mail,$subject,$msg,$headers);

	$phoen_ticket_data=get_post_meta($phoen_ticket_completed_id);

	$phoen_customer_mail = $phoen_ticket_data['phoen_ticket_email'][0];
	
	wp_mail($phoen_customer_mail,$subject,$msg,$headers);
	
	
	if($phoen_ticket_meta_updatede == 1)
	{
		echo "completed";
	} 
	
	die();
	
}


/***************** End Completed Ticket Function ******************************/

/***************** Delete Ticket Function ******************************/

add_action( 'wp_ajax_phoe_ticket_delete', 'phoen_ticket_message_delete' );

add_action( 'wp_ajax_nopriv_phoe_ticket_delete', 'phoen_ticket_message_delete' );

function phoen_ticket_message_delete()
{

	$phoen_ticket_delete_post = sanitize_text_field($_POST['data']);
	 
	$phoen_ticket_post_deleted = wp_delete_post( $phoen_ticket_delete_post, true );
	
	 
	if($phoen_ticket_post_deleted == 1){
		 
		 echo "deleted";
	 
	}
	die();
	
}

/***************** End Delete Ticket Function ******************************/

add_action( 'wp_ajax_phoe_ticket_open_data_agan', 'phoe_ticket_open_data_agans' );

add_action( 'wp_ajax_nopriv_phoe_ticket_open_data_agan', 'phoe_ticket_open_data_agans' );

function phoe_ticket_open_data_agans()
{
	$phoen_ticket_completed_ids = sanitize_text_field($_POST['camplet_ids']);
	
	$phoen_ticket_completed_datas = sanitize_text_field($_POST['data']);
	
	$phoen_ticket_meta_update = update_post_meta($phoen_ticket_completed_ids,'_phoen_ticket_status',$phoen_ticket_completed_datas);
	$phoen_ticket_get_post = get_post($phoen_ticket_completed_ids);
	
	$phoen_ticket_admin_mail = get_option('admin_email');
	
	$phoen_ticket_subject = $phoen_ticket_get_post->post_excerpt;
	
	$phoen_ticket_data=get_post_meta($phoen_ticket_completed_ids);

	$phoen_customer_mail = $phoen_ticket_data['phoen_ticket_email'][0];
	
	$phoen_ticket_email_asigne = isset($phoen_ticket_data['_phoen_ticket_agent_email'][0])?$phoen_ticket_data['_phoen_ticket_agent_email'][0]:'';
	
	$headers = array('Content-Type: text/html; charset=UTF-8');
	
	$subject =  "[".$phoen_ticket_subject." "."Ticket ID:".$phoen_ticket_completed_ids." "."Reopen Ticket "."]";
	
	$phoen_ticket_closed = "Reopen Ticket ";
	
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
															<p>'.$phoen_ticket_closed.'</p>
															
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
	
	wp_mail($phoen_ticket_admin_mail,$subject,$msg,$headers);
	
	wp_mail($phoen_customer_mail,$subject,$msg,$headers);
	
	wp_mail($phoen_ticket_email_asigne,$subject,$msg,$headers);
	
	
	update_post_meta($phoen_ticket_completed_ids,'_phoen_ticket_access_status','unread');
	
	update_post_meta($phoen_ticket_completed_ids,'_phoen_ticket_admin_status','unread');
	
	update_post_meta($phoen_ticket_completed_ids,'_phoen_ticket_customer_status','unread');
	
	if($phoen_ticket_meta_update == 1)
	{
		echo "open";
	}
	
	die();

}
												
	add_action('wp_head','phoen_ticket_frontends_assest');
		
	function phoen_ticket_frontends_assest(){
		
		global $wp_scripts;
	
		$phoen_ticket_bootstrap_array = implode(' ',$wp_scripts->queue);
		
		$phoen_ticket_no_exist = strpos($phoen_ticket_bootstrap_array, 'bootstrap');
		
		wp_enqueue_style( 'phoen-ticket-style-bootstrapk', plugin_dir_url(__FILE__).'assets/css/bootstrap-iso.css' );
		
		if(empty($phoen_ticket_no_exist)) {
			
			wp_enqueue_script( 'phoen-ticket-bootstrapc', plugin_dir_url(__FILE__)."assets/js/phoe_tiket.min.js" , array( 'jquery' ),true );
		} 
	
		wp_enqueue_script( 'phoen-ticket-scripts', plugin_dir_url(__FILE__)."assets/js/select2.js" , array( 'jquery' ),true );
		
		wp_enqueue_script( 'phoen-ticket-system-scripts', plugin_dir_url(__FILE__)."assets/js/phoen_filter_messages.js" , array( 'jquery' ),true );
		
		wp_enqueue_script( 'phoen-ticket-summernote', plugin_dir_url(__FILE__)."assets/js/summernote.js" , array( 'jquery' ),true );
		
		wp_enqueue_style( 'style-ticket-system-summernote',plugin_dir_url(__FILE__).'assets/css/summernote.css' );
		
		?>
		<script>
		
			var newurl 	= '<?php echo admin_url('admin-ajax.php') ;?>';
			
			var get_val = '<?php echo (!empty($_GET['view_id']))?$_GET['view_id']:""; ?>';
			
		</script>
		
		<?php	
	}

	add_action('admin_menu', 'phoen_ticket_system_menu');
	
	function phoen_ticket_system_menu() {
	   
		add_menu_page('phoen_ticket_system', __('Ticket System','support-ticket-system-by-phoeniixx') ,'manage_options','phoen_ticket_system',NUll, plugin_dir_url( __FILE__ ).'assets/images/aaa2.png');
		
		add_submenu_page( 'phoen_ticket_system', 'phoen_support_ticket_settings', __('Settings','support-ticket-system-by-phoeniixx'),'manage_options', 'phoen_ticket_settings', 'phoen_support_ticket_system_settings');
		
		unset($GLOBALS['submenu']['phoen_ticket_system'][0]); 
		
	}

	function phoen_support_ticket_system_settings()
	{
		include_once(plugin_dir_path(__FILE__).'includes/phoen_ticket_system_settings.php');
		
	}
	
	/***************** Add Ticket Shortcode Function ******************************/
	
	add_shortcode('phoen_ticket_system', 'phoen_tickets_systems');
	
	function phoen_tickets_systems(){
		
		$phoen_ticket_enable_settings = get_option('phoen_ticket_system_enable');
		
		if(isset($phoen_ticket_enable_settings) && $phoen_ticket_enable_settings == 1)
		{
			if(is_user_logged_in() && !is_admin()){
				
				if(!isset($_GET['view_id'] ) && !isset($_GET['create_id'])){
					
					include_once(plugin_dir_path(__FILE__).'includes/phoen_ticket_details.php');
				
				}
				if(isset($_GET['create_id'])){
					
					include_once(plugin_dir_path(__FILE__).'includes/phoen_new_ticket_create.php');
					
				}
				
				if(isset($_GET['view_id'])){
					
					include_once(plugin_dir_path(__FILE__).'includes/phoen_message_details.php');
					
				}
				
			}/* else{
				
				include_once(plugin_dir_path(__FILE__).'includes/phoen_message_details.php');
				
			} */
		
		}
	
	}
	
?>
