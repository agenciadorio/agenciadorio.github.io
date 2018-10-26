$wcfm_products_table = '';
var $wcfm_is_valid_form = true;

var tinyMce_toolbar = 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify |  bullist numlist outdent indent | link image | ltr rtl';
if ( wcfm_params.wcfm_allow_tinymce_options ) {
	tinyMce_toolbar = wcfm_params.wcfm_allow_tinymce_options;
}

$popup_width = '50%';
if( jQuery(window).width() <= 768 ) {
	$popup_width = '95%';
}

function initiateTip() {
  jQuery('.img_tip, .text_tip').each(function() {
		jQuery(this).qtip({
			content: jQuery(this).attr('data-tip'),
			position: {
				my: 'top center',
				at: 'bottom center',
				viewport: jQuery(window)
			},
			show: {
				event: 'mouseover',
				solo: true,
			},
			hide: {
				inactive: 6000,
				fixed: true
			},
			style: {
				classes: 'qtip-dark qtip-shadow qtip-rounded qtip-wcfm-css qtip-wcfm-core-css'
			}
		});
	});
}

function GetURLParameter(sParam) {
	var sPageURL = window.location.search.substring(1);
	var sURLVariables = sPageURL.split('&');
	for (var i = 0; i < sURLVariables.length; i++) {
		var sParameterName = sURLVariables[i].split('=');
		if (sParameterName[0] == sParam) {
			return sParameterName[1];
		}
	}
}

jQuery(document).ready(function($) {
	initiateTip();
	
	// Delete Product
	$('.wcfm_delete_product').each(function() {
		$(this).click(function(event) {
			event.preventDefault();
			var rconfirm = confirm(wcfm_core_dashboard_messages.product_delete_confirm);
			if(rconfirm) deleteWCFMProduct($(this));
			return false;
		});
	});
	
	function deleteWCFMProduct(item) {
		jQuery('.products').block({
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
		});
		var data = {
			action : 'delete_wcfm_product',
			proid : item.data('proid')
		}	
		jQuery.ajax({
			type:		'POST',
			url: wcfm_params.ajax_url,
			data: data,
			success:	function(response) {
				window.location = wcfm_params.shop_url;
			}
		});
	}
	
	// WCFM Form Global Validation
	$( document.body ).on( 'wcfm_form_validate', function( event, validating_form ) {
		$proccessed_required_fileds = [];
		if( validating_form ) {
			$form = $(validating_form);
			$form.find('[data-required="1"]').each(function() {
				$data_name = $(this).attr('name');
				if( $.inArray( $data_name, $proccessed_required_fileds ) === -1 ) {
					$proccessed_required_fileds.push($data_name);
					if( !$(this).parents('.wcfm-container').hasClass('wcfm_block_hide') && !$(this).parents('.wcfm-container').hasClass('wcfm_custom_hide') && !$(this).parent().parent().hasClass('wcfm_block_hide') && !$(this).parent().parent().hasClass('wcfm_custom_hide') && !$(this).parent().hasClass('wcfm_block_hide') && !$(this).parent().hasClass('wcfm_custom_hide') && !$(this).hasClass('wcfm_ele_hide') && !$(this).hasClass('wcfm_custom_hide') ) {
						if ( $(this).is( 'input[type="checkbox"]' ) || $(this).is( 'input[type="radio"]' ) ) {
							$('[name="'+$data_name+'"]').removeClass('wcfm_validation_failed').addClass('wcfm_validation_success');
							if( !$('[name="'+$data_name+'"]').is( ":checked" ) ) {
								if( $wcfm_is_valid_form ) 
									$('#' + $form.attr('id') + ' .wcfm-message').html( '<span class="wcicon-status-cancelled"></span>' + $(this).data('required_message') ).addClass('wcfm-error').slideDown();
								else
									$('#' + $form.attr('id') + ' .wcfm-message').append( '<br /><span class="wcicon-status-cancelled"></span>' + $(this).data('required_message') );
								
								$wcfm_is_valid_form = false;
								$('[name="'+$data_name+'"]').removeClass('wcfm_validation_success').addClass('wcfm_validation_failed');
							}
						} else {
							$(this).removeClass('wcfm_validation_failed').addClass('wcfm_validation_success');
							$data_val = $(this).val();
							if( !$data_val ) {
								if( $wcfm_is_valid_form ) 
									$('#' + $form.attr('id') + ' .wcfm-message').html( '<span class="wcicon-status-cancelled"></span>' + $(this).data('required_message') ).addClass('wcfm-error').slideDown();
								else
									$('#' + $form.attr('id') + ' .wcfm-message').append( '<br /><span class="wcicon-status-cancelled"></span>' + $(this).data('required_message') );
								
								$wcfm_is_valid_form = false;
								$(this).removeClass('wcfm_validation_success').addClass('wcfm_validation_failed');
							}
						}
					}
				}
			});
		
			// WP-editor validation check
			$form.find('.wcfm_wpeditor_required').each(function() {
				$data_id = $(this).attr('id');
				$data_name = $(this).attr('name');
				$form = $(this).parents('form');
				if( $.inArray( $data_name, $proccessed_required_fileds ) === -1 ) {
					$proccessed_required_fileds.push($data_name);
					if( !$(this).parents('.wcfm-container').hasClass('wcfm_block_hide') && !$(this).parents('.wcfm-container').hasClass('wcfm_custom_hide') && !$(this).parent().parent().hasClass('wcfm_block_hide') && !$(this).parent().parent().hasClass('wcfm_custom_hide') && !$(this).parent().hasClass('wcfm_block_hide') && !$(this).parent().hasClass('wcfm_custom_hide') && !$(this).hasClass('wcfm_ele_hide') && !$(this).hasClass('wcfm_custom_hide') ) {
						$data_val = wcfmstripHtml(tinymce.get($data_id).getContent({format: 'raw'}));
						if( $data_val.length == 1 ) {
							if( $wcfm_is_valid_form ) 
								$('#' + $form.attr('id') + ' .wcfm-message').html( '<span class="wcicon-status-cancelled"></span>' + wcfmcapitalizeFirstLetter($data_name) + ": " + wcfm_core_dashboard_messages.required_message ).addClass('wcfm-error').slideDown();
							else
								$('#' + $form.attr('id') + ' .wcfm-message').append( '<br /><span class="wcicon-status-cancelled"></span>' + wcfmcapitalizeFirstLetter($data_name) + ": " + wcfm_core_dashboard_messages.required_message );
							
							$wcfm_is_valid_form = false;
						}
					}
				}
			});
		}
	});
	
	// Message Counter auto Refresher
	var messageCountRefrsherTime = '';
	function messageCountRefrsher() {
		clearTimeout(messageCountRefrsherTime);
		messageCountRefrsherTime = setTimeout(function() {
			var data = {
				action : 'wcfm_message_count'
			}	
			jQuery.ajax({
				type:		'POST',
				url: wcfm_params.ajax_url,
				data: data,
				success:	function(response) {
					$response_json = $.parseJSON(response);
					if( $response_json.status ) {
						if($response_json.notice) {
							$('.notice_count').text($response_json.notice);
						}
						if($response_json.message) {
							$('.message_count').text($response_json.message);
							getNewMessageNotification($response_json.message);
						}
						if($response_json.enquiry) {
							$('.enquiry_count').text($response_json.enquiry);
						}
					} else {
						if( $response_json.redirect ) {
							window.location = $response_json.redirect;
						}
					}
				}
			});
			messageCountRefrsher();
		}, 59000 );
	}
	if( wcfm_params.wcfm_is_allow_wcfm ) {
		messageCountRefrsher();
	}
	
	// Fetching new Message Notifications
	var notificationRefrsherTime = '';
	function getNewMessageNotification(message_count) {
		message_count = parseInt(message_count);
		var unread_message = parseInt(wcfm_params.unread_message);
		
		if( message_count > unread_message ) {
			clearTimeout(notificationRefrsherTime);
			$('.wcfm_notification_wrapper').slideDown(function() { $('.wcfm_notification_wrapper').remove(); } );
			
			var data = {
				action : 'wcfm_message_notification',
				limit  : (message_count - unread_message)
			}	
			jQuery.ajax({
				type:		'POST',
				url: wcfm_params.ajax_url,
				data: data,
				success:	function(response) {
					if( response ) {
						wcfm_params.unread_message = message_count;
						$('body').append(response);
						//initiateTip();
						wcfm_desktop_notification_sound.play();
						notificationRefrsherTime = setTimeout(function() {
						  $('.wcfm_notification_wrapper').slideDown(function() { $('.wcfm_notification_wrapper').remove(); } );
						}, 60000 );
						$('.wcfm_notification_close').click(function() {
							clearTimeout(notificationRefrsherTime);
							$('.wcfm_notification_wrapper').slideDown(function() { $('.wcfm_notification_wrapper').remove(); } );
						});
					}
				}
			});
		}
	}
	
	// WCfM Video Tutorials
	$(".wcfm_tutorials").colorbox({iframe:true, width: $popup_width, innerHeight:390});
});

function wcfmstripHtml(html){
	// Create a new div element
	var temporalDivElement = document.createElement("div");
	// Set the HTML content with the providen
	temporalDivElement.innerHTML = html;
	// Retrieve the text property of the element (cross-browser support)
	return temporalDivElement.textContent || temporalDivElement.innerText || "";
}

function wcfmcapitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

var audio = new Audio(wcfm_notification_sound);
var wcfm_notification_sound = new Audio(wcfm_notification_sound);
var wcfm_desktop_notification_sound = new Audio(wcfm_desktop_notification_sound);