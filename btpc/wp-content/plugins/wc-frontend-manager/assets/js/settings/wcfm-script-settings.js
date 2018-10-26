jQuery(document).ready( function($) {
	// Collapsible
  $('.page_collapsible:not(.page_collapsible_dummy)').collapsible({
		defaultOpen: 'wcfm_settings_dashboard_head',
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
			if( $('.wcfm_dokan_store_settings').length > 0 ) {
				$('.wcfm-tabWrap').find('.page_collapsible:first').click();
			} else {
				$('.wcfm-tabWrap').find(window.location.hash).click();
			}
		} else {
			$('.wcfm-tabWrap').find('.page_collapsible:first').click();
		}
	}, 500 );
	
	// Tabheight  
	$('.page_collapsible').each(function() {
		if( !$(this).hasClass('wcfm_head_hide') ) {
			collapsHeight += $(this).height() + 50;
		}
	});
  
	if( $(".country_select").length > 0 ) {
		$(".country_select").select2({
			placeholder: wcfm_dashboard_messages.choose_select2 + ' ...'
		});
	}
	
	if( $("#timezone").length > 0 ) {
		$("#timezone").select2({
			placeholder: wcfm_dashboard_messages.choose_select2 + ' ...'
		});
	}
	
	if( $(".wcfm_product_type_categories").length > 0 ) {
		$(".wcfm_product_type_categories").select2({
			placeholder: wcfm_dashboard_messages.choose_select2 + ' ...'
		});
	}
	
	if( $(".wcfm_product_type_toolset_fields").length > 0 ) {
		$(".wcfm_product_type_toolset_fields").select2({
			placeholder: wcfm_dashboard_messages.choose_select2 + ' ...'
		});
	}
	
	if( $(".exclude_product_types").length > 0 ) {
		$(".exclude_product_types").select2({
			placeholder: wcfm_dashboard_messages.choose_select2 + ' ...'
		});
	}
	
	// WC Vendors MangoPay paymode settings options
	if( $('#vendor_account_type').length > 0 ) {
		$('#vendor_account_type').change(function() {
			$payment_mode = $(this).val();
			$('.mangopay_acc_type').addClass('wcfm_ele_hide');
			$('.mangopay_acc_type_' + $payment_mode).removeClass('wcfm_ele_hide');
			resetCollapsHeight($('#vendor_account_type'));
		}).change();
	}
	
	// TinyMCE intialize - Description
	if( $('#shop_description').length > 0 ) {
		if( $('#shop_description').hasClass('rich_editor') ) {
			if( typeof tinymce != 'undefined' ) {
				var descTinyMCE = tinymce.init({
																			selector: '#shop_description',
																			height: 75,
																			menubar: false,
																			plugins: [
																				'advlist autolink lists link charmap print preview anchor',
																				'searchreplace visualblocks code fullscreen',
																				'insertdatetime image media table paste code directionality',
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
	
	// Style Settings Reset to Default
	if( $('#wcfm_color_setting_reset_button').length > 0 ) {
		$('#wcfm_color_setting_reset_button').click(function(event) {
			event.preventDefault();
			$.each(wcfm_color_setting_options, function( wcfm_color_setting_option, wcfm_color_setting_option_values ) {
				//$('#' + wcfm_color_setting_option_values.name).val( wcfm_color_setting_option_values.default );	
				$('#' + wcfm_color_setting_option_values.name).iris( 'color', wcfm_color_setting_option_values.default );
			} );
			$('#wcfm_settings_save_button').click();
		});
	}
	
	// Save Settings
	$('#wcfm_settings_save_button').click(function(event) {
	  event.preventDefault();
	  
	  var profile = '';
	  if( $('#shop_description').length > 0 ) {
			if( $('#shop_description').hasClass('rich_editor') || $('#shop_description').hasClass('wcfm_wpeditor') ) {
				if( typeof tinymce != 'undefined' ) {
					if( tinymce.get('shop_description') != null ) profile = tinymce.get('shop_description').getContent({format: 'raw'});
					else profile = $('#shop_description').val();
				} else { 
					profile = $('#shop_description').val();
				}
			} else {
				profile = $('#shop_description').val();
			}
		}
  
	  // Validations
	  $('.wcfm-message').html('').removeClass('wcfm-error').removeClass('wcfm-success').slideUp();
	  $wcfm_is_valid_form = true;
	  $( document.body ).trigger( 'wcfm_form_validate', $('#wcfm_settings_form') );
	  $is_valid = $wcfm_is_valid_form;
	  
	  if($is_valid) {
			$('#wcfm_settings_form').block({
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			});
			var data = {
				action             : 'wcfm_ajax_controller',
				controller         : 'wcfm-settings',
				wcfm_settings_form : $('#wcfm_settings_form').serialize(),
				profile            : profile
			}	
			$.post(wcfm_params.ajax_url, data, function(response) {
				if(response) {
					$response_json = $.parseJSON(response);
					$('.wcfm-message').html('').removeClass('wcfm-error').removeClass('wcfm-success').slideUp();
					if($response_json.status) {
						audio.play();
						$('#wcfm_settings_form .wcfm-message').html('<span class="wcicon-status-completed"></span>' + $response_json.message).addClass('wcfm-success').slideDown();
						if( $response_json.file ) $('#wcfm_custom_css-css').attr( 'href', $response_json.file );
					} else {
						audio.play();
						$('#wcfm_settings_form .wcfm-message').html('<span class="wcicon-status-cancelled"></span>' + $response_json.message).addClass('wcfm-error').slideDown();
					}
					$('#wcfm_settings_form').unblock();
				}
			});	
		}
	});
});