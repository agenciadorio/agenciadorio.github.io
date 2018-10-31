(function($) {
	
	if ( $( "#WooCommerceEventsEvent" ).length ) {
	
		checkEventForm();
		
		$('#WooCommerceEventsEvent').change(function() {

			checkEventForm();
			
		})
                
                if( (typeof localObj === "object") && (localObj !== null) )
                {
                
                    jQuery('#WooCommerceEventsDate').datepicker({

                        showButtonPanel: true,
                        closeText: localObj.closeText,
                        currentText: localObj.currentText,
                        monthNames: localObj.monthNames,
                        monthNamesShort: localObj.monthNamesShort,
                        dayNames: localObj.dayNames,
                        dayNamesShort: localObj.dayNamesShort,
                        dayNamesMin: localObj.dayNamesMin,
                        dateFormat: localObj.dateFormat,
                        firstDay: localObj.firstDay,
                        isRTL: localObj.isRTL,

                    });

                } else {
                    
                    jQuery('#WooCommerceEventsDate').datepicker();

                }
                
                if( (typeof localObj === "object") && (localObj !== null) )
                {
                
                    jQuery('#WooCommerceEventsEndDate').datepicker({

                        showButtonPanel: true,
                        closeText: localObj.closeText,
                        currentText: localObj.currentText,
                        monthNames: localObj.monthNames,
                        monthNamesShort: localObj.monthNamesShort,
                        dayNames: localObj.dayNames,
                        dayNamesShort: localObj.dayNamesShort,
                        dayNamesMin: localObj.dayNamesMin,
                        dateFormat: localObj.dateFormat,
                        firstDay: localObj.firstDay,
                        isRTL: localObj.isRTL,

                    });

                } else {
                    
                    jQuery('#WooCommerceEventsEndDate').datepicker();
                    
                }
                
		var fileInput = '';

		jQuery('.wrap').on('click', '.upload_image_button_woocommerce_events', function(e) {
			e.preventDefault();

			var button = jQuery(this);
			var id = jQuery(this).parent().prev('input.uploadfield');
			wp.media.editor.send.attachment = function(props, attachment) {
				id.val(attachment.url);
			};
			wp.media.editor.open(button);
			return false;
		});

		jQuery('.upload_reset').click(function() {
				jQuery(this).parent().prev('input.uploadfield').val('');
		});

		// user inserts file into post. only run custom if user started process using the above process
		// window.send_to_editor(html) is how wp would normally handle the received data

		window.original_send_to_editor = window.send_to_editor;
		window.send_to_editor = function(html){

			window.original_send_to_editor(html);

		};
                
                jQuery('.wrap').on('change', '#WooCommerceEventsExportUnpaidTickets', function(e) {
                    
                    jQuery('#WooCommerceEventsExportMessage').html('Update product for export options to take affect.');
                    
                });
                
                jQuery('.wrap').on('change', '#WooCommerceEventsExportBillingDetails', function(e) {
                    
                    jQuery('#WooCommerceEventsExportMessage').html('Update product for export options to take affect.');
                    
                });
                

	}
	
	// Start functions 
		function checkEventForm() {
			
			var WooCommerceEventsEvent = $('#WooCommerceEventsEvent').val();
			
			if(WooCommerceEventsEvent == 'Event') {

				jQuery('#WooCommerceEventsForm').show();

			} else {
				
				jQuery('#WooCommerceEventsForm').hide();
				
			}

		} 
	
})(jQuery);


(function( $ ) {
    
    jQuery('.color-field').wpColorPicker();
    
})( jQuery );

(function( $ ) {
    
    var captureAttendee = true;
    
    jQuery('#WooCommerceEventsEvent').on("change", function(){
        
        var productID = jQuery(this).val();
        
        var dataVariations = {
			'action': 'fetch_woocommerce_variations',
			'productID': productID
		};
                
        jQuery.post(ajaxurl, dataVariations, function(response) {
            
            if(response) {
                
                $('#woocommerce_events_variations').html(response);
            
            }
            
        });
        
        var dataAttendeeCapture = {
			'action': 'fetch_capture_attendee_details',
			'productID': productID
		};
        
        jQuery.post(ajaxurl, dataAttendeeCapture, function(response) {
            
            var details = JSON.parse(response);
            
            if(details.capture == 'off') {
                
                captureAttendee = false;
                
            }
            
        });
        
    });
    
    jQuery('#WooCommerceEventsClientID').on("change", function(){
        
        var userID = jQuery(this).val();
        
        $('#WooCommerceEventsPurchaserFirstName').val('');
        $("#WooCommerceEventsPurchaserFirstName").removeAttr("readonly"); 
        $('#WooCommerceEventsPurchaserEmail').val('');
        $("#WooCommerceEventsPurchaserEmail").removeAttr("readonly"); 
        $('#WooCommerceEventsPurchaserUserName').val('');
        $("#WooCommerceEventsPurchaserUserName").removeAttr("readonly"); 
        
        if(userID) {
            
            var data = {
                            'action': 'fetch_wordpress_user',
                            'userID': userID
                    };
            
            jQuery.post(ajaxurl, data, function(response) {
               
                var user = JSON.parse(response);

                if(user.ID) {
                    
                    $('#WooCommerceEventsPurchaserUserName').val(user.data.user_login);
                    $("#WooCommerceEventsPurchaserUserName").prop('readonly', true);
                    $('#WooCommerceEventsPurchaserFirstName').val(user.data.display_name);
                    $("#WooCommerceEventsPurchaserFirstName").prop('readonly', true);
                    $('#WooCommerceEventsPurchaserEmail').val(user.data.user_email);
                    $("#WooCommerceEventsPurchaserEmail").prop('readonly', true);
                    
                } 
                
            });
                    
        }
        
        
        
    });
    
    
    jQuery('#post').submit(function() {
        
            var error = false;
            var addTicket = jQuery('#add_ticket').val();
            
            if(addTicket) {
            
                if(!addTicket) {

                    error = true;

                }

                if(!jQuery('#WooCommerceEventsEvent').val()) {

                    error = true;

                }

                if(!jQuery('#WooCommerceEventsPurchaserFirstName').val()) {

                    error = true;

                }

                if(!jQuery('#WooCommerceEventsPurchaserUserName').val()) {

                    error = true;

                }

                if(!jQuery('#WooCommerceEventsPurchaserEmail').val()) {

                    error = true;

                }

                if(error) {

                    alert('All fields are required');
                    return false;

                }
            
            }
        
    });
    
})( jQuery );


(function($) {
    
    var postID = getParameter('post');

    jQuery('#WooCommerceEventsResendTicket').on("click", function(){    
        
        jQuery('#WooCommerceEventsResendTicketMessage').html("<div class='notice notice-info'>Sending...</div>");
        var WooCommerceEventsResendTicketEmail = jQuery('#WooCommerceEventsResendTicketEmail').val();
        if(!WooCommerceEventsResendTicketEmail) {
            
            jQuery('#WooCommerceEventsResendTicketMessage').html("<div class='notice notice-error'>Email address required.</div>");
            
        } else {
            
            var data = {
                            'action': 'resend_ticket',
                            'WooCommerceEventsResendTicketEmail': WooCommerceEventsResendTicketEmail,
                            'postID': postID
                    };
            
            jQuery.post(ajaxurl, data, function(response) {
               
                 var email = JSON.parse(response);
                 jQuery('#WooCommerceEventsResendTicketMessage').html("<div class='notice notice-success'>"+email.message+"</div>");
                
            });
            
        }
        
        return false;
    });
    
    
    function getParameter(name){
        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
        if (results==null){
           return null;
        }
        else{
           return results[1] || 0;
        }
    }
    
})( jQuery );