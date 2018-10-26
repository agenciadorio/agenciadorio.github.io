jQuery(document).ready(function($){
	
	jQuery('.pst-inbox-msg').each(function(){
		
		jQuery(this).attr('data-search-term', jQuery(this).text().toLowerCase());
		
	});

	jQuery('.phoen_filter_messege').on('keyup', function(){

		var searchTerm = $(this).val().toLowerCase();

		jQuery('.pst-inbox-msg').each(function(){

			if (jQuery(this).filter('[data-search-term *= ' + searchTerm + ']').length > 0 || searchTerm.length < 1) {
				
				jQuery(this).show();
				
			} else {
				
				jQuery(this).hide();
				
			}

		});

	});

	jQuery('.form-control').change(function(){
		
		var phoen_status = jQuery(".form-control :selected").text();
		
			jQuery.post(
						newurl, 
						{
							'action' : 'phoe_ticket_status',
							'data'   :  phoen_status,
							'get_val':  get_val
						}, 
						function(response){
							
							jQuery('.phoen_preiority').text(response);
						}
			);
	
	});
	
	jQuery('.form-control-assine').change(function(){
		
		var phoen_assine_user = jQuery(".form-control-assine :selected").text();
		
		var phoen_agent_id = jQuery(this).val();
		
		var phoen_ticket_agent_email = jQuery('option:selected', this).attr('agent_email');
		
			jQuery.post(
						newurl, 
						{
							'action'		 		  : 'phoe_ticket_assine',
							'data'			 		  :  phoen_assine_user,
							'get_val'		 		  :	 get_val,
							'phoen_agent_id' 		  :  phoen_agent_id,
							'phoen_ticket_agent_email':  phoen_ticket_agent_email
							
						}, 
						function(response){
							
						}
			);
		
	}); 
	
	// for sidebar menu on mobile version
	
	jQuery('.pst-head-wrap .nav_icon_mobile').click(function(){
		
		jQuery('.pho-tik-system .pst-container .pst-user-panel-wrap').slideToggle();
		
	});
	
	
});