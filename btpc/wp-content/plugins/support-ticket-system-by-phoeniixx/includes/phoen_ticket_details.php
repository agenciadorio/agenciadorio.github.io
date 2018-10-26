<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/******************** Get Ticket Post Data *****************/

$phoen_get_ticket = array(

    'post_type'  => 'phoen_ticket_systems',

	'posts_per_page' => -1,

	'post_status'   => 'publish',

);

$phoen_ticket_data = get_posts( $phoen_get_ticket );

/********************* Count Open And Closed Tickets************************/

$phoen_ticket_count_open='0';

$phoen_ticket_closed_count='0';

$phoen_current_ticket='0';

$phoen_ticket_count_opens='0';

$phoen_ticket_closed_counts='0';

$phoen_ticket_count_open_customer='0';

$phoen_ticket_closed_count_customer='0';

for($i=0; $i<count($phoen_ticket_data); $i++)
{

	$phoen_ticket_id=$phoen_ticket_data[$i]->ID;

	$phoen_ticket_post_meta = get_post_meta($phoen_ticket_id);

	$phoen_ticket_change_email =isset($phoen_ticket_post_meta['phoen_ticket_email_asigne_change'][0])?$phoen_ticket_post_meta['phoen_ticket_email_asigne_change'][0]:'';

	$phoen_ticket_status = isset($phoen_ticket_post_meta['_phoen_ticket_status'][0])?$phoen_ticket_post_meta['_phoen_ticket_status'][0]:'';

	$phoen_ticket_check_asigne = isset($phoen_ticket_post_meta['_phoen_ticket_agent_email'][0])?$phoen_ticket_post_meta['_phoen_ticket_agent_email'][0]:'';

	$phoen_ticket_created_by = isset($phoen_ticket_post_meta['phoen_ticket_created_by'][0])?$phoen_ticket_post_meta['phoen_ticket_created_by'][0]:'';

	$current_user = wp_get_current_user();

	$phoen_curren_user = $current_user->user_email;

	$phoen_ticket_role = $current_user->roles[0];	

	if($phoen_curren_user == $phoen_ticket_change_email)
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

	if($phoen_curren_user == $phoen_ticket_created_by)
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

$phoen_get_new_ticket =isset($_GET['phoen_new_ticket'])?$_GET['phoen_new_ticket']:'';

$phoen_get_closed_tickets =isset($_GET['phoeni_closed_tickets'])? $_GET['phoeni_closed_tickets']:'';

$phoen_ticket_current_user = wp_get_current_user();

/********************* End OF Get Post ********************/

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

					  <input type="search" placeholder="<?php _e( 'Procurar chamados', 'support-ticket-system-by-phoeniixx' ); ?>" class="phoen_filter_messege"/>

					</form>

				</div>

			</div>

		</div> <!-- col end -->

	</div> <!-- container-fluid end -->

</div>

<div class="pho-tik-system">

	<div class="container-fluid pst-container">

		<div class="row">
		
			<?php 

			$current_user = wp_get_current_user();

			$phoen_ticket_role = $current_user->roles[0];	
			
			if ($_SERVER['QUERY_STRING']){
				
				global $wp;
				
				$queryString = $_SERVER['QUERY_STRING'];
				
				if (strpos($queryString, '&') !== false) {
					$queryString = substr($queryString, 0, strpos($queryString, "&"));
				}
				
				$site_url_avls = home_url(add_query_arg(array(),$wp->request));
				
				$available_url_create = $site_url_avls."/?".$queryString."&create_id=1";
				
			}else{ 
				
				global $wp;
				
				$site_url_avls = home_url(add_query_arg(array(),$wp->request));
				
				$available_url_create = $site_url_avls."/?create_id=1";
				
			}
			
		 if ($_SERVER['QUERY_STRING']){
				global $wp;
				
				$queryString_cl = $_SERVER['QUERY_STRING'];
				
				if (strpos($queryString_cl, '&') !== false) {
					$queryString_cl = substr($queryString_cl, 0, strpos($queryString_cl, "&"));
				}			
				
				$site_url_avl_bs = home_url(add_query_arg(array(),$wp->request));
			
				$available_url_cl = $site_url_avl_bs."/?".$queryString_cl."&phoeni_closed_tickets=2";
				
			}else{ 
				global $wp;
				$site_url_avls_cl = home_url(add_query_arg(array(),$wp->request));
				$available_url_cl = $site_url_avls_cl."/?phoeni_closed_tickets=2";
				
				
			}
			
			if ($_SERVER['QUERY_STRING'] ){
				
				global $wp;
				
				$queryString_bs = $_SERVER['QUERY_STRING'];
				
				if (strpos($queryString_bs, '&') !== false) {
					$queryString_bs = substr($queryString_bs, 0, strpos($queryString_bs, "&"));
				}			
				
				$site_url_avl_bs = home_url(add_query_arg(array(),$wp->request));
			
				$available_url_op = $site_url_avl_bs."/?".$queryString_bs."&phoen_new_ticket=1";
				
			
			}else{ 
			
				global $wp;
				$site_url_avl_bs = home_url(add_query_arg(array(),$wp->request));
				$available_url_b_back = $site_url_avl_bs."/?phoeni_closed_tickets=2&view_id=1";
			}
			
			if($current_user->roles[0] == 'administrator')
			{ ?>

				<div class="col-lg-3 col-md-3 col-sm-3 pst-user-panel-wrap">

					<div class="pst-user-panel">

						<div class="pst-user-list">
							
							
							<a href="<?php echo $available_url_op ; ?>"> <h3> <span class="glyphicon glyphicon-folder-open phoeni_pending_tickets"></span><?php _e( 'Tickets abertos', 'support-ticket-system-by-phoeniixx' ); ?> <span class="ticket-number"><?php echo $phoen_ticket_count_opens; ?></span> </h3></a>
							
							<!--<p> <h3 class="phoen_open_tck" data-index="<?php //echo $available_url_op ; ?>"> <span class="glyphicon glyphicon-folder-open phoeni_pending_tickets"></span><?php //_e( 'Open Tickets', 'support-ticket-system-by-phoeniixx' ); ?> <span class="ticket-number"><?php //echo $phoen_ticket_count_opens; ?></span> </h3></p>-->

						</div>

						<div class="pst-user-list">
							
							<a href="<?php echo $available_url_cl ; ?>"> <h3> <span class="glyphicon glyphicon-ok phoeni_closed_ticket"></span><?php _e( 'Tickets solucionados', 'support-ticket-system-by-phoeniixx' ); ?> <span class="ticket-number"><?php echo $phoen_ticket_closed_counts; ?></span> </h3></a>
							
							<!--<p> <h3 class ="phoen_close_tck" data-index="<?php// echo $available_url_cl ; ?>"> <span class="glyphicon glyphicon-ok phoeni_closed_ticket"></span><?php// _e( 'Closed Tickets', 'support-ticket-system-by-phoeniixx' ); ?> <span class="ticket-number"><?php //echo $phoen_ticket_closed_counts; ?></span> </h3></p>-->

						</div>

						<div class="pnt-submit-new-ticket">

							<a title="New Ticket" href="<?php echo $available_url_create ; ?>"><?php _e( 'Abrir chamado', 'support-ticket-system-by-phoeniixx' ); ?></a>

						</div>

					</div> <!-- pst-user-panel end -->

				</div>

			<?php 

			}else if($current_user->roles[0] == 'customer' || $current_user->roles[0] == 'subscriber'){ 
				
				?>
				
				<div class="col-lg-3 col-md-3 col-sm-3 pst-user-panel-wrap">

					<div class="pst-user-panel">

						<div class="pst-user-list">

							<a href="<?php echo $available_url_op ; ?>"> <h3> <span class="glyphicon glyphicon-folder-open phoeni_pending_tickets"></span><?php _e( 'Tickets abertos', 'support-ticket-system-by-phoeniixx' ); ?> <span class="ticket-number"><?php echo $phoen_ticket_count_open_customer; ?></span> </h3></a>

						</div>

						<div class="pst-user-list">

							<a href="<?php echo $available_url_cl ; ?>"> <h3> <span class="glyphicon glyphicon-ok phoeni_closed_ticket"></span><?php _e( 'Closed Tickets', 'support-ticket-system-by-phoeniixx' ); ?> <span class="ticket-number"><?php echo $phoen_ticket_closed_count_customer; ?></span> </h3></a>

						</div>

						<div class="pnt-submit-new-ticket">

							<a href="<?php echo $available_url_create ; ?>"><?php _e( 'Create New Ticket', 'support-ticket-system-by-phoeniixx' ); ?></a>

						</div>

					</div> <!-- pst-user-panel end -->

				</div>

				<?php

			}else if($current_user->roles[0] == 'phoen_agent'){
				?>

				<div class="col-lg-3 col-md-3 col-sm-3 pst-user-panel-wrap">

					<div class="pst-user-panel">

						<div class="pst-user-list">

							<a href="<?php echo $available_url_op ; ?>"> <h3> <span class="glyphicon glyphicon-folder-open phoeni_pending_tickets"></span><?php _e( 'Open Tickets', 'support-ticket-system-by-phoeniixx' ); ?> <span class="ticket-number"><?php echo $phoen_ticket_count_open; ?></span> </h3></a>

						</div>

						<div class="pst-user-list">

							<a href="<?php echo $available_url_cl ; ?>"> <h3> <span class="glyphicon glyphicon-ok phoeni_closed_ticket"></span><?php _e( 'Closed Tickets', 'support-ticket-system-by-phoeniixx' ); ?> <span class="ticket-number"><?php echo $phoen_ticket_closed_count; ?></span> </h3></a>

						</div>

						<div class="pnt-submit-new-ticket">

							<a href="<?php echo $available_url_create ; ?>"><?php _e( 'Create New Ticket', 'support-ticket-system-by-phoeniixx' ); ?></a>

						</div>

					</div> <!-- pst-user-panel end -->

				</div>

				<?php

			}

			?>
		 <!-- ............. message body starts from here ................ -->  

			<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 pst-ticket-system-wrap-full-width">

				<div class="pst-ticket-system-wrap">

				<?php

					if(is_array($phoen_ticket_data))
					{
						foreach($phoen_ticket_data as $phoe_ticket)
						{
							$phoen_post_id = $phoe_ticket->ID;
							
							if ($_SERVER['QUERY_STRING']){
					
								global $wp;
								$queryString_p = $_SERVER['QUERY_STRING'];
								if (strpos($queryString_p, '&') !== false) {
									$queryString_p = substr($queryString_p, 0, strpos($queryString_p, "&"));
								}
								
								$site_url_avl_ps = home_url(add_query_arg(array(),$wp->request));
								$available_url_p_view = $site_url_avl_ps."/?".$queryString_p."&view_id=".$phoe_ticket->ID;
								
							}else{
								global $wp;
								$site_url_avl_ps = home_url(add_query_arg(array(),$wp->request));
								$available_url_p_view = $site_url_avl_ps."/?phoeni_closed_tickets=2&view_id=".$phoe_ticket->ID;
							
							}
							
							$phoen_mets_data = get_post_meta($phoen_post_id);

							$phoen_ticket_check_asigne = isset($phoen_mets_data['_phoen_ticket_agent_email'][0])?$phoen_mets_data['_phoen_ticket_agent_email'][0]:'';

							$phoen_ticket_change_email = isset($phoen_mets_data['phoen_ticket_email_asigne_change'][0])?$phoen_mets_data['phoen_ticket_email_asigne_change'][0]:'';

							$phoen_ticket_created_by = isset($phoen_mets_data['phoen_ticket_created_by'][0])?$phoen_mets_data['phoen_ticket_created_by'][0]:'';

							$phoen_ticket_agent_email = isset($phoen_mets_data['_phoen_ticket_agent_email'][0])?$phoen_mets_data['_phoen_ticket_agent_email'][0]:'';

							$phoen_access_staus = isset($phoen_mets_data['_phoen_ticket_access_status'][0])?$phoen_mets_data['_phoen_ticket_access_status'][0]:'';

							$phoen_post_datatus = isset($phoen_mets_data['_phoen_ticket_status'][0])?$phoen_mets_data['_phoen_ticket_status'][0]:'';

							$phoen_user_email_id = isset($phoen_mets_data['phoen_ticket_email'][0])?$phoen_mets_data['phoen_ticket_email'][0]:'';

							$phoen_ticket_admin_status = isset($phoen_mets_data['_phoen_ticket_admin_status'][0])?$phoen_mets_data['_phoen_ticket_admin_status'][0]:'';

							$phoen_ticket_customer_status = isset($phoen_mets_data['_phoen_ticket_customer_status'][0])?$phoen_mets_data['_phoen_ticket_customer_status'][0]:'';

							$current_user = wp_get_current_user();

							$phoen_curren_user = $current_user->user_email;

							$phoen_ticket_content = $phoe_ticket->post_content; 

							$phoen_ticket_contents = strip_tags($phoen_ticket_content);

							if($current_user->roles[0] == 'administrator')
							{

								if(($phoen_get_new_ticket == '')  && ($phoen_get_closed_tickets ==''))
								{

									if($phoen_post_datatus=='open')
									{
										
										$phoen_ticketid=$phoe_ticket->ID;

										?>			

										<div class="pst-inbox-msg">

											<a href="<?php echo $available_url_p_view; ?>">
											
												<?php 

												if($phoen_ticket_admin_status == 'unread')
												{
													?>

													<div class="pst-inbox-icon new-tikt phoen_read">

														<span class="glyphicon glyphicon-folder-open"></span>

													</div>

													<?php

												}else{

													?>
														<div class="pst-inbox-icon new-tikt">

															<span class="glyphicon glyphicon-folder-open"></span>

														</div>

														<?php
													} 	?>

													<div class="pst-inbox-title">

														<h5><?php echo $phoe_ticket->post_title; ?></h5>

														<span class="phoen_id" style="display:none"><?php echo $phoen_ticketid ; ?><span>

													</div>

													<div class="pst-single-msg">

														<div class="pst-msg-wrap">

															<div class="mail-subject"><strong><?php echo $phoe_ticket->post_excerpt; ?>&nbsp</strong></div>

															<span class="pst-msg-body"><?php echo $phoen_ticket_contents ;?></span>

														</div>

													</div>

											</a>
													<!-- pst-single-msg -->
													
											<div class="pst-hover-element">

												<div class="pst-hover-icons correct-icon" title="Close Ticket">

													<span class="glyphicon glyphicon-ok phoeni_complet" date_ticket_id="<?php echo $phoen_ticketid; ?>"></span>

												</div>

												<div class="pst-hover-icons del-icon" title="Delete Ticket">

													<span class="glyphicon glyphicon-trash phoeni_delete" date_id_del="<?php echo $phoen_ticketid; ?>"></span>

												</div>

											</div> <!-- pst-hover-element end -->

											</div> <!-- pst-inbox-msg end -->

											<?php	

									}

								}else if($phoen_get_new_ticket == '1')
								{

									if($phoen_post_datatus=='open')
									{

										$phoen_ticketid=$phoe_ticket->ID;

											?>			

										<div class="pst-inbox-msg">

											<a href="<?php echo $available_url_p_view ; ?>">

												<?php 

												if($phoen_ticket_admin_status == 'unread')
												{

													?>

													<div class="pst-inbox-icon new-tikt phoen_read">

														<span class="glyphicon glyphicon-folder-open"></span>

													</div>

													<?php 
													
												}else
												{ ?>

													<div class="pst-inbox-icon new-tikt">

														<span class="glyphicon glyphicon-folder-open"></span>

													</div>

													<?php

												} ?>

												<div class="pst-inbox-title">

													<h5><?php echo $phoe_ticket->post_title; ?></h5>

													<span class="phoen_id" style="display:none"><?php echo $phoen_ticketid ; ?></span>

												</div>

												<div class="pst-single-msg">

													<div class="pst-msg-wrap">

														<div class="mail-subject"><strong><?php echo $phoe_ticket->post_excerpt; ?>&nbsp </strong></div>

														<span class="pst-msg-body"><?php echo $phoen_ticket_contents ;?></span>

													</div>

												</div>

											</a>

											<!-- pst-single-msg -->

											<div class="pst-hover-element">

												<div class="pst-hover-icons correct-icon" title="Close Ticket">

													<span class="glyphicon glyphicon-ok phoeni_complet" date_ticket_id="<?php echo $phoen_ticketid; ?>"></span>

												</div>

												<div class="pst-hover-icons del-icon" title="Delete Ticket">

													<span class="glyphicon glyphicon-trash phoeni_delete" date_id_del="<?php echo $phoen_ticketid; ?>"></span>

												</div>

											</div> <!-- pst-hover-element end -->

										</div> <!-- pst-inbox-msg end -->

										<?php	

									}

								}else if($phoen_get_closed_tickets =='2')
								{
									if($phoen_post_datatus=='closed')
									{

										$phoen_ticketid=$phoe_ticket->ID;

										?>			
										<div class="pst-inbox-msg">

											<a href="<?php echo $available_url_p_view; ?>">

												<div class="pst-inbox-icon compl-tikt">

													<span class="glyphicon glyphicon-ok"></span>

												</div>

												<div class="pst-inbox-title">

													<h5><?php echo $phoe_ticket->post_title; ?></h5>

													<span class="phoen_id" style="display:none"><?php echo $phoen_ticketid ; ?></span>

												</div>

												<div class="pst-single-msg">

													<div class="pst-msg-wrap">

														<div class="mail-subject"><strong><?php echo $phoe_ticket->post_excerpt; ?>&nbsp </strong></div>

														<span class="pst-msg-body"><?php echo $phoen_ticket_contents ;?></span>

													</div>

												</div>

											</a>

											<!-- pst-single-msg -->

											<div class="pst-hover-element">	

												<div class="pst-hover-icons correct-icon" title="Open Ticket">

													<span class="glyphicon glyphicon-folder-open phoeni_complet_data" date_ticket_ids="<?php echo $phoen_ticketid; ?>"></span>

												</div>

												<div class="pst-hover-icons del-icon" title="Delete Ticket">

													<span class="glyphicon glyphicon-trash phoeni_delete" date_id_del="<?php echo $phoen_ticketid; ?>"></span>

												</div>

											</div> <!-- pst-hover-element end -->

										</div> <!-- pst-inbox-msg end -->

									

										<?php	

									}

								}

								

								

							}else if($current_user->roles[0] == 'customer' || $current_user->roles[0] == 'subscriber')
							{
								if($phoen_ticket_created_by == $phoen_curren_user)
								{

									if(($phoen_get_new_ticket == '')  && ($phoen_get_closed_tickets ==''))
									{

										if($phoen_post_datatus=='open')
										{

											$phoen_ticketid=$phoe_ticket->ID;

											?>			
											<div class="pst-inbox-msg">

												<a href="<?php echo $available_url_p_view; ?>">

													<?php 

													if($phoen_ticket_customer_status == 'unread')
													{

														?>
														<div class="pst-inbox-icon new-tikt phoen_read">

															<span class="glyphicon glyphicon-folder-open"></span>

														</div>

														<?php

													}else{
															?>

														<div class="pst-inbox-icon new-tikt">

															<span class="glyphicon glyphicon-folder-open"></span>

														</div>

														<?php

													} 	?>

													<div class="pst-inbox-title">

														<h5><?php echo $phoe_ticket->post_title; ?></h5>

														<span class="phoen_id" style="display:none"><?php echo $phoen_ticketid ; ?></span>

													</div>

													<div class="pst-single-msg">

														<div class="pst-msg-wrap">

															<div class="mail-subject"><strong><?php echo $phoe_ticket->post_excerpt; ?>&nbsp </strong></div>

															<span class="pst-msg-body"><?php echo $phoen_ticket_contents; ?></span>

														</div>

													</div>

												</a>

													<!-- pst-single-msg -->
													
												<div class="pst-hover-element">

													<div class="pst-hover-icons correct-icon" title="Close Ticket">

														<span class="glyphicon glyphicon-ok phoeni_complet" date_ticket_id="<?php echo $phoen_ticketid; ?>"></span>

													</div>

													<div class="pst-hover-icons del-icon" title="Delete Ticket">

														<span class="glyphicon glyphicon-trash phoeni_delete" date_id_del="<?php echo $phoen_ticketid; ?>"></span>

													</div>

												</div> <!-- pst-hover-element end -->

											</div> <!-- pst-inbox-msg end -->

											<?php	

									

										}

									}else if($phoen_get_new_ticket == '1')
									{
										if($phoen_post_datatus=='open')
										{

											$phoen_ticketid=$phoe_ticket->ID;

											?>			
											<div class="pst-inbox-msg">

												<a href="<?php echo $available_url_p_view; ?>">

													<?php 

													if($phoen_ticket_customer_status == 'unread')
													{

														?>
														<div class="pst-inbox-icon new-tikt phoen_read">

															<span class="glyphicon glyphicon-folder-open"></span>

														</div>

														<?php 

													}else
													{ ?>

														<div class="pst-inbox-icon new-tikt">

															<span class="glyphicon glyphicon-folder-open"></span>

														</div>

														<?php

													} ?>

													<div class="pst-inbox-title">

														<h5><?php echo $phoe_ticket->post_title; ?></h5>

														<span class="phoen_id" style="display:none"><?php echo $phoen_ticketid ; ?></span>

													</div>

													<div class="pst-single-msg">

														<div class="pst-msg-wrap">

															<div class="mail-subject"><strong><?php echo $phoe_ticket->post_excerpt; ?>&nbsp </strong></div>

															<span class="pst-msg-body"><?php echo $phoen_ticket_contents;?></span>

														</div>

													</div>

												</a>

													<!-- pst-single-msg -->

												<div class="pst-hover-element">

													<div class="pst-hover-icons correct-icon" title="Close Ticket">

														<span class="glyphicon glyphicon-ok phoeni_complet" date_ticket_id="<?php echo $phoen_ticketid; ?>"></span>

													</div>

													<div class="pst-hover-icons del-icon" title="Delete Ticket">

														<span class="glyphicon glyphicon-trash phoeni_delete" date_id_del="<?php echo $phoen_ticketid; ?>"></span>

													</div>

												</div> <!-- pst-hover-element end -->

											</div> <!-- pst-inbox-msg end -->

											<?php	

										}

									}else if($phoen_get_closed_tickets =='2')
									{
										
										if($phoen_post_datatus=='closed')
										{

											$phoen_ticketid=$phoe_ticket->ID;

											?>			

												<div class="pst-inbox-msg">

													<a href="<?php echo $available_url_p_view; ?>">

														<div class="pst-inbox-icon compl-tikt">

															<span class="glyphicon glyphicon-ok"></span>

														</div>

														<div class="pst-inbox-title">

															<h5><?php echo $phoe_ticket->post_title; ?></h5>

															<span class="phoen_id" style="display:none"><?php echo $phoen_ticketid ; ?></span>

														</div>

														<div class="pst-single-msg">

															<div class="pst-msg-wrap">

																<div class="mail-subject"><strong><?php echo $phoe_ticket->post_excerpt; ?>&nbsp </strong></div>

																<span class="pst-msg-body"><?php echo $phoen_ticket_contents;?></span>

															</div>

														</div>

													</a>

													<!-- pst-single-msg -->

													<div class="pst-hover-element">	

														<div class="pst-hover-icons correct-icon" title="Open Ticket">

															<span class="glyphicon glyphicon-folder-open phoeni_complet_data" date_ticket_ids="<?php echo $phoen_ticketid; ?>"></span>

														</div>

														<div class="pst-hover-icons del-icon" title="Delete Ticket">

															<span class="glyphicon glyphicon-trash phoeni_delete" date_id_del="<?php echo $phoen_ticketid; ?>"></span>

														</div>

													</div> <!-- pst-hover-element end -->

												</div> <!-- pst-inbox-msg end -->

												<?php	
										}

									}

								

								}

							}else{
								if($phoen_curren_user == $phoen_ticket_change_email) 
								{
										
									if(($phoen_get_new_ticket == '')  && ($phoen_get_closed_tickets ==''))
									{

										if($phoen_post_datatus=='open')
										{
											
											$phoen_ticketid=$phoe_ticket->ID;

											?>			
											<div class="pst-inbox-msg">

												<a href="<?php echo $available_url_p_view; ?>">

													<?php 
													if($phoen_access_staus == 'unread')
													{
														?>

														<div class="pst-inbox-icon new-tikt phoen_read">

															<span class="glyphicon glyphicon-folder-open"></span>

														</div>

														<?php
														
													}else{
															?>

														<div class="pst-inbox-icon new-tikt">

															<span class="glyphicon glyphicon-folder-open"></span>

														</div>

														<?php

													} 	?>

													<div class="pst-inbox-title">

														<h5><?php echo $phoe_ticket->post_title; ?></h5>

														<span class="phoen_id" style="display:none"><?php echo $phoen_ticketid ; ?></span>

													</div>

													<div class="pst-single-msg">

														<div class="pst-msg-wrap">

															<div class="mail-subject"><strong><?php echo $phoe_ticket->post_excerpt; ?>&nbsp </strong></div>

															<span class="pst-msg-body"><?php echo $phoen_ticket_contents; ?></span>

														</div>

													</div>

												</a>

													<!-- pst-single-msg -->

												<div class="pst-hover-element">

													<div class="pst-hover-icons correct-icon" title="Close Ticket">

														<span class="glyphicon glyphicon-ok phoeni_complet" date_ticket_id="<?php echo $phoen_ticketid; ?>"></span>

													</div>

													<div class="pst-hover-icons del-icon" title="Delete Ticket">

														<span class="glyphicon glyphicon-trash phoeni_delete" date_id_del="<?php echo $phoen_ticketid; ?>"></span>

													</div>

												</div> <!-- pst-hover-element end -->

											</div> <!-- pst-inbox-msg end -->

											<?php	

										}

									}else if($phoen_get_new_ticket == '1')
									{
										if($phoen_post_datatus=='open')
										{

											$phoen_ticketid=$phoe_ticket->ID;

											?>			
											<div class="pst-inbox-msg">

												<a href="<?php echo $available_url_p_view; ?>">

													<?php 
													if($phoen_access_staus == 'unread')
													{
														?>
														<div class="pst-inbox-icon new-tikt phoen_read">

															<span class="glyphicon glyphicon-folder-open"></span>

														</div>

														<?php 

													}else
													{ ?>

														<div class="pst-inbox-icon new-tikt">

															<span class="glyphicon glyphicon-folder-open"></span>

														</div>

														<?php

													} ?>

													<div class="pst-inbox-title">

														<h5><?php echo $phoe_ticket->post_title; ?></h5>

														<span class="phoen_id" style="display:none"><?php echo $phoen_ticketid ; ?></span>

													</div>

													<div class="pst-single-msg">

														<div class="pst-msg-wrap">

															<div class="mail-subject"><strong><?php echo $phoe_ticket->post_excerpt; ?>&nbsp </strong></div>

															<span class="pst-msg-body"><?php echo $phoen_ticket_contents;?></span>

														</div>

													</div>

												</a>

													<!-- pst-single-msg -->

												<div class="pst-hover-element">

													<div class="pst-hover-icons correct-icon" title="Close Ticket">

														<span class="glyphicon glyphicon-ok phoeni_complet" date_ticket_id="<?php echo $phoen_ticketid; ?>"></span>

													</div>

													<div class="pst-hover-icons del-icon" title="Delete Ticket">

														<span class="glyphicon glyphicon-trash phoeni_delete" date_id_del="<?php echo $phoen_ticketid; ?>"></span>

													</div>

												</div> <!-- pst-hover-element end -->

											</div> <!-- pst-inbox-msg end -->

											<?php	

										}

									}else if($phoen_get_closed_tickets =='2')
									{
										if($phoen_post_datatus=='closed')
										{

											$phoen_ticketid=$phoe_ticket->ID;

											?>			

											<div class="pst-inbox-msg">

												<a href="<?php echo $available_url_p_view; ?>">

													<div class="pst-inbox-icon compl-tikt">

														<span class="glyphicon glyphicon-ok"></span>

													</div>

													<div class="pst-inbox-title">

														<h5><?php echo $phoe_ticket->post_title; ?></h5>

														<span class="phoen_id" style="display:none"><?php echo $phoen_ticketid ; ?></span>

													</div>

													<div class="pst-single-msg">

														<div class="pst-msg-wrap">

															<div class="mail-subject"><strong><?php echo $phoe_ticket->post_excerpt; ?>&nbsp </strong></div>

															<span class="pst-msg-body"><?php echo $phoen_ticket_contents;?></span>

														</div>

													</div>

												</a>

												<!-- pst-single-msg -->

												<div class="pst-hover-element">	

													<div class="pst-hover-icons correct-icon" title="Open Ticket">

														<span class="glyphicon glyphicon-folder-open phoeni_complet_data" date_ticket_ids="<?php echo $phoen_ticketid; ?>"></span>

													</div>

													<div class="pst-hover-icons del-icon" title="Delete Ticket">

														<span class="glyphicon glyphicon-trash phoeni_delete" date_id_del="<?php echo $phoen_ticketid; ?>"></span>

													</div>

												</div> <!-- pst-hover-element end -->

											</div> <!-- pst-inbox-msg end -->

												<?php	

										}

									}

								}

							}

						}	

					

					}

					?>

				</div> <!-- pst-ticket-system-wrap end -->

			</div> <!-- col-lg-9 col-md-9 ..... end -->

		</div><!-- row class end -->

	</div> <!-- container-fluid pst-container -->

</div>  

<script>

	jQuery(document).ready(function(){

		jQuery('.phoeni_complet').on("click",function(){

			var phoen_camplete_id = jQuery(this).attr('date_ticket_id');

			var phoen_ticket_campleteds="closed";

			var phoen_ticket_newurl = '<?php echo admin_url('admin-ajax.php') ;?>';

			jQuery.post(
			
				phoen_ticket_newurl,
				{
					'action'	:  'phoe_ticket_completed_data',

					'data'		:	phoen_ticket_campleteds,

					'camplet_id':	phoen_camplete_id

				},

				function(response){

					if(response =='completed')
					{
						location.reload(); 
					}  

				}

			); 

		}); 

		jQuery('.phoeni_delete').on("click",function(){

			var phoen_deleted_id = jQuery(this).attr('date_id_del');

			var phoen_ticket_newurl = '<?php echo admin_url('admin-ajax.php') ;?>';

			jQuery.post(

				phoen_ticket_newurl,
				{
					'action'	:  'phoe_ticket_delete',

					'data'		:	phoen_deleted_id,

				},
				function(response){
					
					if(response == 'deleted'){
						
						location.reload(); 
						
					}

				}

			); 

		});  

		jQuery('.phoeni_complet_data').on("click",function(){

			var phoen_campletes_id = jQuery(this).attr('date_ticket_ids');

			var phoen_tickets_campleteds="open";

			var phoen_ticket_newurl = '<?php echo admin_url('admin-ajax.php') ;?>';

			jQuery.post(

				phoen_ticket_newurl,
				{
					'action'	:  'phoe_ticket_open_data_agan',

					'data'		:	phoen_tickets_campleteds,

					'camplet_ids':	phoen_campletes_id
					
				},
				function(response){
					
					if(response =='open')
					{

						location.reload(); 

					}

				}

			); 

		}); 
		
		
		jQuery(".phoen_close_tck").on("click",function()
		{
		
			var op = jQuery(".phoen_open_tck").attr("data-index");
			
			var close =  jQuery(".phoen_close_tck").attr("data-index");
			
			window.location.href = close;
			
		});
		
		
		jQuery(".phoen_open_tck").on("click",function()
		{
			
			var clo = jQuery(".phoen_close_tck").attr("data-index");
			
			var open =  jQuery(".phoen_open_tck").attr("data-index");
			
			window.location.href = open; 
			
			
			
		});
		
		

	});

</script>