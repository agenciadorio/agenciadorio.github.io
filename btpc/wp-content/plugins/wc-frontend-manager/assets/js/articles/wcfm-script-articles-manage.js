var article_form_is_valid = true;
jQuery( document ).ready( function( $ ) {
	
	if( $("#article_cats").length > 0 ) {
		$("#article_cats").select2({
			placeholder: wcfm_dashboard_messages.choose_category_select2,
			maximumSelectionLength: $("#article_cats").data('catlimit')
		});
	}
	
	if( $('.article_taxonomies').length > 0 ) {
		$('.article_taxonomies').each(function() {
			$("#" + $(this).attr('id')).select2({
				placeholder: wcfm_dashboard_messages.choose_select2 + $(this).attr('id') + " ..."
			});
		});
	}
	
	if( $("#wcfm_associate_vendor").length > 0 ) {
		$("#wcfm_associate_vendor").select2({
			placeholder: wcfm_dashboard_messages.choose_vendor_select2
		});
	}
	
	if( $('#article_cats_checklist').length > 0 ) {
		$('.sub_checklist_toggler').each(function() {
			if( $(this).parent().find('.article_taxonomy_sub_checklist').length > 0 ) { $(this).css( 'visibility', 'visible' ); }
		  $(this).click(function() {
		    $(this).toggleClass('fa-arrow-circle-down');
		    $(this).parent().find('.article_taxonomy_sub_checklist').toggleClass('article_taxonomy_sub_checklist_visible');
		  });
		});
		$('.article_cats_checklist_item_hide_by_cap').attr( 'disabled', true );
	}
	
	// Tag Cloud
	if( $('.wcfm_fetch_tag_cloud').length > 0 ) {
		$wcfm_tag_cloud_fetched = false;
		$('.wcfm_fetch_tag_cloud').click(function() {
		  if( !$wcfm_tag_cloud_fetched ) {
				var data = {
					action : 'get-tagcloud',
					tax    : 'post_tag'
				}	
				$.post(wcfm_params.ajax_url, data, function(response) {
					if(response) {
						$('.wcfm_fetch_tag_cloud').html(response);
						$wcfm_tag_cloud_fetched = true;
						
						$('.tag-cloud-link').each(function() {
						  $(this).click(function(event) {
						  	event.preventDefault();
						  	$tag = $(this).text();
						  	$tags = $('#article_tags').val();
						  	if( $tags.length > 0 ) {
						  		$tags += ',' + $tag;
						  	} else {
						  		$tags = $tag;
						  	}
						  	$('#article_tags').val($tags);
						  });
						});
					}
				});
			}
		});
	}
	
	if( typeof gmw_forms != 'undefined' ) {
		// Geo my WP Support
		if( typeof tinymce != 'undefined' ) {
			tinymce.PluginManager.add('geomywp', function(editor, url) {
				// Add a button that opens a window
				editor.addButton('geomywp', {
					text: 'GMW Form',
					icon: false,
					onclick: function() {
						// Open window
						editor.windowManager.open({
							title: 'GMW Form',
							body: [
								{type: 'listbox', name: 'form_type', label: 'Form Type', values: [{text: 'Form', value: 'form'}, {text: 'Map', value: 'map'}, {text: 'Results', value: 'results'}]},
								{type: 'listbox', name: 'gmw_forms', label: 'Select Form', values: gmw_forms}
							],
							onsubmit: function(e) {
								// Insert content when the window form is submitted
								if(e.data.form_type == 'results') {
									editor.insertContent('[gmw form="results"]');
								} else if(e.data.form_type == 'map') {
									editor.insertContent('[gmw map="' + e.data.gmw_forms + '"]');
								} else {
									editor.insertContent('[gmw form="' + e.data.gmw_forms + '"]');
								}
							}
						});
					}
				});
			});
		}
		
		tinyMce_toolbar += ' | geomywp';
		// TinyMCE intialize - Short description
		if( $('#excerpt').hasClass('rich_editor') ) {
			if( typeof tinymce != 'undefined' ) {
				var shdescTinyMCE = tinymce.init({
																			selector: '#excerpt',
																			height: 75,
																			menubar: false,
																			plugins: [
																				'advlist autolink lists link charmap print preview anchor',
																				'searchreplace visualblocks code fullscreen',
																				'insertdatetime image media table paste code geomywp directionality'
																			],
																			toolbar: tinyMce_toolbar,
																			content_css: '//www.tinymce.com/css/codepen.min.css',
																			statusbar: false,
																			browser_spellcheck: true,
																			entity_encoding: "raw"
																		});
			}
		}
		
		// TinyMCE intialize - Description
		if( $('#description').hasClass('rich_editor') ) {
			if( typeof tinymce != 'undefined' ) {
				var descTinyMCE = tinymce.init({
																			selector: '#description',
																			height: 75,
																			menubar: false,
																			plugins: [
																				'advlist autolink lists link charmap print preview anchor',
																				'searchreplace visualblocks code fullscreen',
																				'insertdatetime image media table paste code geomywp directionality',
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
	} else {
		// TinyMCE intialize - Short description
		if( $('#excerpt').hasClass('rich_editor') ) {
			if( typeof tinymce != 'undefined' ) {
				var shdescTinyMCE = tinymce.init({
																			selector: '#excerpt',
																			height: 75,
																			menubar: false,
																			plugins: [
																				'advlist autolink lists link charmap print preview anchor',
																				'searchreplace visualblocks code fullscreen',
																				'insertdatetime image media table paste code directionality'
																			],
																			toolbar: tinyMce_toolbar,
																			content_css: '//www.tinymce.com/css/codepen.min.css',
																			statusbar: false,
																			browser_spellcheck: true,
																			entity_encoding: "raw"
																		});
			}
		}
		
		// TinyMCE intialize - Description
		if( $('#description').hasClass('rich_editor') ) {
			if( typeof tinymce != 'undefined' ) {
				var descTinyMCE = tinymce.init({
																			selector: '#description',
																			//height: 75,
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
	
	function wcfm_articles_manage_form_validate() {
		article_form_is_valid = true;
		$('.wcfm-message').html('').removeClass('wcfm-error').removeClass('wcfm-success').slideUp();
		var title = $.trim($('#wcfm_articles_manage_form').find('#title').val());
		$('#wcfm_articles_manage_form').find('#title').removeClass('wcfm_validation_failed').addClass('wcfm_validation_success');
		if(title.length == 0) {
			$('#wcfm_articles_manage_form').find('#title').removeClass('wcfm_validation_success').addClass('wcfm_validation_failed');
			article_form_is_valid = false;
			$('#wcfm_articles_manage_form .wcfm-message').html('<span class="wcicon-status-cancelled"></span>' + wcfm_articles_manage_messages.no_title).addClass('wcfm-error').slideDown();
			audio.play();
		}
		
		$( document.body ).trigger( 'wcfm_articles_manage_form_validate' );
		
		$wcfm_is_valid_form = article_form_is_valid;
		$( document.body ).trigger( 'wcfm_form_validate', $('#wcfm_articles_manage_form') );
		article_form_is_valid = $wcfm_is_valid_form;
		
		return article_form_is_valid;
	}
	
	// Draft Article
	$('#wcfm_articles_simple_draft_button').click(function(event) {
	  event.preventDefault();
	  
	  // Validations
	  $is_valid = wcfm_articles_manage_form_validate();
	  
	  if($is_valid) {
			$('#wcfm-content').block({
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			});
			
			var excerpt = '';
			if( $('#excerpt').hasClass('rich_editor') || $('#excerpt').hasClass('wp-editor-area') ) {
				if( typeof tinymce != 'undefined' ) {
					if( tinymce.get('excerpt') != null ) excerpt = tinymce.get('excerpt').getContent({format: 'raw'});
					else excerpt = $('#excerpt').val();
				} else {
					excerpt = $('#excerpt').val();	
				}
			} else {
				excerpt = $('#excerpt').val();
			}
			
			var description = '';
			if( $('#description').hasClass('rich_editor') || $('#description').hasClass('wp-editor-area') ) {
				if( typeof tinymce != 'undefined' ) {
					if( tinymce.get('description') != null ) description = tinymce.get('description').getContent({format: 'raw'});
					else description = $('#description').val();
				} else {
					description = $('#description').val();
				}
			} else {
				description = $('#description').val();
			}
			
			var data = {
				action : 'wcfm_ajax_controller',
				controller : 'wcfm-articles-manage', 
				wcfm_articles_manage_form : $('#wcfm_articles_manage_form').serialize(),
				excerpt     : excerpt,
				description : description,
				status : 'draft'
			}	
			$.post(wcfm_params.ajax_url, data, function(response) {
				if(response) {
					$response_json = $.parseJSON(response);
					$('.wcfm-message').html('').removeClass('wcfm-error').removeClass('wcfm-success').slideUp();
					if($response_json.status) {
						audio.play();
						$('#wcfm_articles_manage_form .wcfm-message').html('<span class="wcicon-status-completed"></span>' + $response_json.message).addClass('wcfm-success').slideDown( "slow", function() {
							if( $response_json.redirect ) window.location = $response_json.redirect;	
						} );
					} else {
						audio.play();
						$('#wcfm_articles_manage_form .wcfm-message').html('<span class="wcicon-status-cancelled"></span>' + $response_json.message).addClass('wcfm-error').slideDown();
					}
					if($response_json.id) $('#article_id').val($response_json.id);
					$('#wcfm-content').unblock();
				}
			});	
		}
	});
	
	// Submit Article
	$('#wcfm_articles_simple_submit_button').click(function(event) {
	  event.preventDefault();
	  
	  // Validations
	  $is_valid = wcfm_articles_manage_form_validate();
	  
	  if($is_valid) {
			$('#wcfm-content').block({
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			});
			
			var excerpt = '';
			if( $('#excerpt').hasClass('rich_editor') || $('#excerpt').hasClass('wp-editor-area') ) {
				if( typeof tinymce != 'undefined' ) {
					if( tinymce.get('excerpt') != null ) excerpt = tinymce.get('excerpt').getContent({format: 'raw'});
					else excerpt = $('#excerpt').val();
				} else {
					excerpt = $('#excerpt').val();	
				}
			} else {
				excerpt = $('#excerpt').val();
			}
			
			var description = '';
			if( $('#description').hasClass('rich_editor') || $('#description').hasClass('wp-editor-area') ) {
				if( typeof tinymce != 'undefined' ) {
					if( tinymce.get('description') != null ) description = tinymce.get('description').getContent({format: 'raw'});
					else description = $('#description').val();
				} else {
					description = $('#description').val();	
				}
			} else {
				description = $('#description').val();
			}
			
			var data = {
				action : 'wcfm_ajax_controller',
				controller : 'wcfm-articles-manage',
				wcfm_articles_manage_form : $('#wcfm_articles_manage_form').serialize(),
				excerpt     : excerpt,
				description : description,
				status : 'submit'
			}	
			$.post(wcfm_params.ajax_url, data, function(response) {
				if(response) {
					$response_json = $.parseJSON(response);
					$('.wcfm-message').html('').removeClass('wcfm-error').removeClass('wcfm-success').slideUp();
					if($response_json.status) {
						audio.play();
						$('#wcfm_articles_manage_form .wcfm-message').html('<span class="wcicon-status-completed"></span>' + $response_json.message).addClass('wcfm-success').slideDown( "slow", function() {
						  if( $response_json.redirect ) window.location = $response_json.redirect;	
						} );
					} else {
						audio.play();
						$('#wcfm_articles_manage_form .wcfm-message').html('<span class="wcicon-status-cancelled"></span>' + $response_json.message).addClass('wcfm-error').slideDown();
					}
					if($response_json.id) $('#article_id').val($response_json.id);
					$('#wcfm-content').unblock();
				}
			});
		}
	});
	
	function playSound(filename) {
	  document.getElementById("sound").innerHTML='<audio autoplay="autoplay"><source src="' + filename + '.mp3" type="audio/mpeg" /><source src="' + filename + '.ogg" type="audio/ogg" /><embed hidden="true" autostart="true" loop="false" src="' + filename +'.mp3" /></audio>';
	}
	
	function jsUcfirst(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
  }
} );