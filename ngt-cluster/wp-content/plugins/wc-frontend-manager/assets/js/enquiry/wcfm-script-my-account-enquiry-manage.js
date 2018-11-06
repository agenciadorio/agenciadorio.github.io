jQuery(document).ready( function($) {
	
	$('#wcfm_inquiry_reply_send_button').click(function(event) {
	  event.preventDefault();
	
	  var inquiry_reply = getWCFMEditorContent( 'inquiry_reply' );
	  
	  // Validations
	  $is_valid = true;
	  
	  $('.wcfm-message').html('').removeClass('wcfm-error').removeClass('wcfm-success').slideUp();
		if( wcfmstripHtml(inquiry_reply).length <= 1) {
			$is_valid = false;
			$('#wcfm_inquiry_reply_form .wcfm-message').html('<span class="wcicon-status-cancelled"></span>' + wcfm_enquiry_manage_messages.no_reply).addClass('wcfm-error').slideDown();
			wcfm_notification_sound.play();
		}
	  
	  if($is_valid) {
			$('#wcfm_inquiry_reply_form').block({
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			});
			var data = {
				action                         : 'wcfm_ajax_controller',
				controller                     : 'wcfm-my-account-enquiry-manage',
				inquiry_reply                  : inquiry_reply,
				wcfm_inquiry_reply_form : jQuery('#wcfm_inquiry_reply_form').serialize()
			}	
			$.post(wcfm_params.ajax_url, data, function(response) {
				if(response) {
					$response_json = $.parseJSON(response);
					$('.wcfm-message').html('').removeClass('wcfm-error').removeClass('wcfm-success').slideUp();
					wcfm_notification_sound.play();
					if($response_json.status) {
						$('#wcfm_inquiry_reply_form .wcfm-message').html('<span class="wcicon-status-completed"></span>' + $response_json.message).addClass('wcfm-success').slideDown( "slow" , function() {
						  if( $response_json.redirect ) window.location = $response_json.redirect;	
						} );
					} else {
						$('#wcfm_inquiry_reply_form .wcfm-message').html('<span class="wcicon-status-cancelled"></span>' + $response_json.message).addClass('wcfm-error').slideDown();
					}
					$('#wcfm_inquiry_reply_form').unblock();
				}
			});	
		}
	});
});