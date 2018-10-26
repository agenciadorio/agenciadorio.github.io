jQuery(document).ready( function($) {
	// Collapsible
	$('.page_collapsible').collapsible({
		defaultOpen: 'wcfm_profile_personal_head',
		speed: 'slow',
		loadOpen: function (elem) { //replace the standard open state with custom function
				elem.next().show();
		},
		loadClose: function (elem, opts) { //replace the close state with custom function
				elem.next().hide();
		},
		animateOpen: function(elem, opts) {
			$('.collapse-open').addClass('collapse-close').removeClass('collapse-open');
			elem.addClass('collapse-open');
			$('.collapse-close').find('span').removeClass('fa-arrow-circle-o-right block-indicator');
			elem.find('span').addClass('fa-arrow-circle-o-right block-indicator');
			$('.wcfm-tabWrap').find('.wcfm-container').stop(true, true).slideUp(opts.speed);
			elem.next().stop(true, true).slideDown(opts.speed);
		},
		animateClose: function(elem, opts) {
			elem.find('span').removeClass('fa-arrow-circle-o-up block-indicator');
			elem.next().stop(true, true).slideUp(opts.speed);
		}
	});
	$('.page_collapsible').each(function() {
		$(this).html('<div class="page_collapsible_content_holder">' + $(this).html() + '</div>');
		$(this).find('.page_collapsible_content_holder').after( $(this).find('span') );
	});
	$('.page_collapsible').find('span').addClass('fa');
	$('.collapse-open').addClass('collapse-close').removeClass('collapse-open');
	$('.wcfm-tabWrap').find('.wcfm-container').hide();
	setTimeout(function() {
		if(window.location.hash) {
			$('.wcfm-tabWrap').find(window.location.hash).click();
		} else {
			$('.wcfm-tabWrap').find('.page_collapsible:first').click();
		}
	}, 500 );
	
	// Tabheight  
	$('.page_collapsible').each(function() {
		if( !$(this).hasClass('wcfm_head_hide') ) {
			collapsHeight += $(this).height() + 100;
		}
	});  
	
	$('.wcfm_datepicker').each(function() {
	  $(this).datepicker({
      dateFormat : $(this).data('date_format'),
      changeMonth: true,
      changeYear: true
    });
  });
  
  $('.wcfm_datetimepicker').each(function() {
	  $(this).datetimepicker({
      dateFormat : $(this).data('date_format'),
      timeFormat: 'h:mm tt',
      changeMonth: true,
      changeYear: true
    });
  });
  
  $('.wcfm_timepicker').each(function() {
	  $(this).timepicker({
      timeFormat: 'h:mm tt',
    });
  });
	
	// Email Verification
	if( $('.wcfm_email_verified_button').length > 0 ) {
		$('#email').on( 'blur', function() {
			if( $(this).hasClass( 'wcfm_verification_code_sender' ) ) {
				sendEmailVerificationCode();
			}
		});
		$('.wcfm_email_verified_button').on( 'click', function(e) {
			e.preventDefault();
			sendEmailVerificationCode();
			return false;
		});
	}
	
	function sendEmailVerificationCode() {
		
		$('#email').block({
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
		});
		
	  $is_valid = true;
	  if( $is_valid ) {
	  	$user_email = $('#email').val();
	  	if( !$user_email ) {
	  		$('#email').removeClass('wcfm_validation_success').addClass('wcfm_validation_failed');
	  		$('#wcfm_profile_form .wcfm-message').html( '<span class="wcicon-status-cancelled"></span>' + $('#email').data('required_message') ).addClass('wcfm-error').slideDown();
	  		$is_valid = false;
	  	} else {
	  		$('#email').addClass('wcfm_validation_success').removeClass('wcfm_validation_failed');
	  	}
	  }
	  
		if( $is_valid ) {
			var data = {
				action                             : 'wcfm_email_verification_code',
				user_email                         : $('#email').val()
			}	
			$.post(wcfm_params.ajax_url, data, function(response) {
				if(response) {
					$response_json = $.parseJSON(response);
					if($response_json.status) {
						$('.wcfm-message').html('').removeClass('wcfm-error').slideUp();
						$('#wcfm_profile_form .wcfm-message').html('<span class="wcicon-status-completed"></span>' + $response_json.message).addClass('wcfm-success').slideDown();
					} else {
						$('.wcfm-message').html('').removeClass('wcfm-success').slideUp();
						$('#wcfm_profile_form .wcfm-message').html('<span class="wcicon-status-cancelled"></span>' + $response_json.message).addClass('wcfm-error').slideDown();
					}
					$('#email').unblock();
				}
			});
		} else {
			$('#email').unblock();
		}
	}
	
	// TinyMCE intialize - About
	if( $('#about').length > 0 ) {
		if( $('#about').hasClass('rich_editor') ) {
			if( typeof tinymce != 'undefined' ) {
				var descTinyMCE = tinymce.init({
																			selector: '#about',
																			height: 75,
																			menubar: false,
																			plugins: [
																				'advlist autolink lists link charmap print preview anchor',
																				'searchreplace visualblocks code fullscreen',
																				'insertdatetime table paste code directionality',
																				'autoresize'
																			],
																			toolbar: tinyMce_toolbar,
																			content_css: '//www.tinymce.com/css/codepen.min.css',
																			statusbar: false,
																			browser_spellcheck: true,
																			entity_encoding: "raw"
																		});
			}
		}
	}
		
	if( $(".country_select").length > 0 ) {
		$(".country_select").select2({
			placeholder: wcfm_dashboard_messages.choose_select2 + ' ...'
		});
	}
	
	$('#same_as_billing').change(function() {
	  if( $('#same_as_billing').is(':checked') ) {
	  	$('.same_as_billing_ele').addClass('wcfm_ele_hide');
	  	$('.same_as_billing_ele').next('.select2').addClass('wcfm_ele_hide');
	  } else {
	  	$('.same_as_billing_ele').removeClass('wcfm_ele_hide');
	  	$('.same_as_billing_ele').next('.select2').removeClass('wcfm_ele_hide');
	  	resetCollapsHeight($('.collapse-open').next('.wcfm-container').find('.wcfm_ele:not(.wcfm_title):first'));
	  }
	}).change();
	
	// Save Profile
	$('#wcfmprofile_save_button').click(function(event) {
	  event.preventDefault();
	  
	  var about = '';
	  if( $('#about').hasClass('rich_editor') || $('#about').hasClass('wcfm_wpeditor') ) {
			if( typeof tinymce != 'undefined' ) {
				if( tinymce.get('about') != null ) about = tinymce.get('about').getContent({format: 'raw'});
				else about = $('#about').val();
			} else {
				about = $('#about').val();
			}
		} else {
			about = $('#about').val();
		}
  
	  // Validations
	  $('.wcfm-message').html('').removeClass('wcfm-error').removeClass('wcfm-success').slideUp();
	  $wcfm_is_valid_form = true;
	  $( document.body ).trigger( 'wcfm_form_validate', $('#wcfm_profile_form') );
	  $is_valid = $wcfm_is_valid_form;
	  
	  if($is_valid) {
			$('#wcfm_profile_form').block({
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			});
			var data = {
				action             : 'wcfm_ajax_controller',
				controller         : 'wcfm-profile',
				wcfm_profile_form  : $('#wcfm_profile_form').serialize(),
				user_email         : $('#email').val(),
				about              : about
			}	
			$.post(wcfm_params.ajax_url, data, function(response) {
				if(response) {
					$response_json = $.parseJSON(response);
					$('.wcfm-message').html('').removeClass('wcfm-error').removeClass('wcfm-success').slideUp();
					if($response_json.status) {
						audio.play();
						$('#wcfm_profile_form .wcfm-message').html('<span class="wcicon-status-completed"></span>' + $response_json.message).addClass('wcfm-success').slideDown();
					} else {
						audio.play();
						$('#wcfm_profile_form .wcfm-message').html('<span class="wcicon-status-cancelled"></span>' + $response_json.message).addClass('wcfm-error').slideDown();
					}
					$('#wcfm_profile_form').unblock();
				}
			});	
		}
	});
});