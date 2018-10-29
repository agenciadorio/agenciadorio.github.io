/* -------------------------------------------------------------------------------- /

	Plugin Name: Go Portfolio - WordPress Responsive Portfolio
	Author: Granth
	Version: 1.6.4

	+----------------------------------------------------+
		TABLE OF CONTENTS
	+----------------------------------------------------+

    [1] SETUP & COMMON
    [2] MAIN PAGE
    [3] SUBMENU PAGE - TEMPLATE & STYLE EDITOR
    [4] SUBMENU PAGE - CUSTOM POST TYPES

/ -------------------------------------------------------------------------------- */
(function ($, undefined) {
	"use strict";
	$(function () {
		
	/* ---------------------------------------------------------------------- /
		[1] SETUP & COMMON
	/ ---------------------------------------------------------------------- */			
		
		/* Detect IE */
		var isIE = document.documentMode != undefined && document.documentMode >5 ? document.documentMode : false;
				
		var $goPortfolio=$('#gwa-gopf-admin-wrap');
		
		var $gallery = $('.gwa-gopf-gallery');
		var $button = $('.gwa-gopf-thumb-add, .gwa-gopf-thumb-add-new');
		var $galleryAdd = $('<div class="inside postbox gwa-gopf-thumb-add"><a href="#"><span></span></a></div>');
		$goPortfolio.delegate('.gwa-gopf-thumb-add, .gwa-gopf-thumb-add-new', 'click', function(e){
			e.preventDefault();
			
			/* New media uploader wp3.5+ */
			if ( typeof wp.media != 'undefined' ) {
				var file_frame = wp.media({
					title: 'Select an Image',
					library: {
						type: 'image'
					},
					button: {
						text: 'Use Image'
					},
					multiple: true,
				});
			
				file_frame.on('select', function() {
					var selected = [];
					var selection = file_frame.state().get('selection');
					
					if ( selection.length>1 ) {
						selection.map(function(file) {
							selected.push(file.toJSON());
						});
					} else {
						selected.push(file_frame.state().get('selection').first().toJSON());
					};
					
					$gallery.find('.gwa-gopf-thumb-add').remove();
						$('.gwa-gopf-thumb-delete-all').removeClass('gwa-gopf-hidden');	
						for (var i in selected) {
							var newThumb = $('<div class="inside postbox gwa-gopf-thumb"><input type="hidden" name="inquery-items[attachment]['+selected[i].id+']" value="'+selected[i].url+'" /><div class="gwa-gopf-thumb-inner"><a href="#"><img src="'+selected[i].url+'"></a></div></div>');
							$gallery.append(newThumb);
					};
					
					$goPortfolio.find('.gwa-gopf-gallery').sortable({
						items:'.gwa-gopf-thumb',
						opacity:0.8,
						placeholder:'gwa-gopf-thumb gwa-gopf-thumb-placeholder'
					});				
				});
				
				file_frame.open();
			
			} else {
				/* Old media uploader */
				tb_show('', 'media-upload.php?post_id=0&amp;type=image&amp;TB_iframe=true');
				window.send_to_editor = function(html) {
					$gallery.find('.gwa-gopf-thumb-add').remove();
					$('.gwa-gopf-thumb-delete-all').removeClass('gwa-gopf-hidden');	
					var $html=$('<div />', { 'class':'media-html', 'html': html });
						var imgClass = $html.find('img').attr('class');
						var imgID = imgClass.split('wp-image-')[1];
						if (imgID == undefined) { return false; }
						var imgSrc = $html.find('img')[0].src;
						var newThumb = $('<div class="inside postbox gwa-gopf-thumb"><input type="hidden" name="inquery-items[attachment]['+imgID+']" value="'+imgSrc+'" /><div class="gwa-gopf-thumb-inner"><a href="#"><img src="'+imgSrc+'"></a></div></div>');							
						$gallery.append(newThumb);
						tb_remove();			
				};			
			}			
		});
		
		$goPortfolio.delegate('.gwa-gopf-thumb-delete-all', 'click', function(){	
			$gallery.html($galleryAdd);
			$('.gwa-gopf-thumb-delete-all').addClass('gwa-gopf-hidden');
			$('.gwa-gopf-thumb-delete-selected').addClass('gwa-gopf-hidden');
		});

		$goPortfolio.delegate('.gwa-gopf-thumb-delete-selected', 'click', function(){	
			$goPortfolio.find('.gwa-gopf-thumb.gwa-gopf-current').remove();
			$('.gwa-gopf-thumb-delete-selected').addClass('gwa-gopf-hidden');
			if ( !$goPortfolio.find('.gwa-gopf-thumb').length ) {
				$('.gwa-gopf-thumb-delete-all').trigger('click');
			};
		});		

		$goPortfolio.find('.gwa-gopf-gallery').sortable({
			items:'.gwa-gopf-thumb',
			opacity:0.8,
			placeholder:'gwa-gopf-thumb gwa-gopf-thumb-placeholder'
		});	

		var shiftPressed = false;
		var ctrlPressed = false;
		var lastIndex = null;
		$goPortfolio.delegate('.gwa-gopf-thumb a', 'click', function(e){	
			var $this = $(this), $thumbnail = $this.closest('.gwa-gopf-thumb');
			e.preventDefault();
			var minIndex;
			var maxIndex;
			
			var currentIndex = $goPortfolio.find('.gwa-gopf-thumb').index( $thumbnail );
			if (!ctrlPressed && !shiftPressed) {
				$thumbnail.siblings().removeClass('gwa-gopf-current');
			}
			
			if ( !$goPortfolio.find('.gwa-gopf-thumb.gwa-gopf-current').length ) {
				lastIndex = $goPortfolio.find('.gwa-gopf-thumb').index( $thumbnail );
			} 
			
			if (!$thumbnail.hasClass('gwa-gopf-current')) {
				$thumbnail.addClass('gwa-gopf-current');
				$('.gwa-gopf-thumb-delete-selected').removeClass('gwa-gopf-hidden');					
			} else {
				$thumbnail.removeClass('gwa-gopf-current');
				$('.gwa-gopf-thumb-delete-selected').addClass('gwa-gopf-hidden');	
			}
			
			if (shiftPressed) {
				var minIndex = Math.min(currentIndex, lastIndex),
					maxIndex = Math.max(currentIndex, lastIndex);			
				
				for (var i=minIndex; i<maxIndex+1; i++) {
					$goPortfolio.find('.gwa-gopf-thumb').eq(i).addClass('gwa-gopf-current');
				};
				
			};
		});
		
		$(document).delegate(this, 'keydown keyup', function(e) {
			var code = e.keyCode || e.which;			
			if (e.type == 'keydown') {
				if(code == 17) { ctrlPressed = true; }
				if(code == 16) { shiftPressed = true; }				
			} else {
				if(code == 17) { ctrlPressed = false; }
				if(code == 16) { shiftPressed = false; }							
			};
		});
		
		/* open close panels */
		$goPortfolio.delegate('h3.hndle', 'click', function(){	
			var $this=$(this);
			
			if ($this.next('.inside').is(':visible')) {
				$this.next('.inside').slideUp().end().find('span').addClass('gwa-gwa-gopf-closed');
			} else {
				$this.next('.inside').slideDown().end().find('span').removeClass('gwa-gwa-gopf-closed');
			};
		});		

		
		/* Set up Colorpicker */
		var $colorPickerInput=$goPortfolio.find('.gwa-gopf-colorpicker-input');
		
		if ($colorPickerInput.length) {

			if ($.fn.wpColorPicker) {
			  /* New colorpicker wp3.5+ */
			  $colorPickerInput.wpColorPicker();
			} else {
			   /* Old colorpicker */
				$colorPickerInput.each(function(index, element) {
					var $this = $(this);
					$this.wrap($('<div class="gwa-gopf-colorpicker-wrap" />'))
					.closest('.gwa-gopf-colorpicker-wrap').append($('<div class="gwa-gopf-colorpicker" />').css('display','none'));
					$this.closest('.gwa-gopf-colorpicker-wrap').find('.gwa-gopf-colorpicker').farbtastic(function(color) { $this.val(color).css({'background-color':color}); });
				});
			};
			
			/* Show or hide 'old colorpicker' */
			$colorPickerInput.delegate(this, 'focus blur', function(e) {
				var $this = $(this);
				if (e.type=='focus') {
					$this.closest('.gwa-gopf-colorpicker-wrap').find('.gwa-gopf-colorpicker').css('display','block');
				} else {
					$this.closest('.gwa-gopf-colorpicker-wrap').find('.gwa-gopf-colorpicker').css('display','none');	
				};
			});
			
		};
		
		/* Show & Hide data groups */
		$goPortfolio.delegate('select[data-parent]', 'change', function(e) {
			var $this=$(this);
			$goPortfolio.find('.gwa-gopf-group[data-parent~="'+$this.data('parent')+'"]').hide();
			$goPortfolio.find('.gwa-gopf-group[data-parent~="'+$this.data('parent')+'"][data-children~="'+$this.find(':selected').data('children')+'"]').show();
			$goPortfolio.find('.gwa-gopf-group[data-parent~="'+$this.data('parent')+'"][data-children~="'+$this.find(':selected').data('children')+'"]').find('select').trigger('change');
		});
		
		/* Builder */
		$goPortfolio.delegate('select[data-parent="post-type"]', 'change', function(e) {
			var $this=$(this);
			$goPortfolio.find('.gwa-gopf-builder-bt').hide();
			if ($this.find(':selected').data('children') != undefined) {
				if ($this.find(':selected').data('children') == 'attachment') {
					$goPortfolio.find('.gwa-gopf-bt-gallery').show().find('select').trigger('change','demo');
				} else {
					$goPortfolio.find('.gwa-gopf-bt-cpt').show().find('select').trigger('change','demo');
					
				};
			};
		});	

		$goPortfolio.delegate('.gwa-gopf-builder-bt select', 'change', function(e, demo) {
			var $this=$(this);
			if ($this.find(':selected').data('children') != undefined && $this.closest('.gwa-gopf-builder-bt').css('display')!='none') {
				$goPortfolio.find('.gwa-gopf-builder').hide();
				$goPortfolio.find('.gwa-gopf-builder[data-children~="'+$this.find(':selected').data('children')+'"]').show();
			};
		});		
		
		$goPortfolio.delegate('.gwa-gopf-group-btn-select', 'change', function(e, triggered) {
			var $this=$(this),
				$btn=$this.next('.gwa-gopf-group-btn');
			
			$btn.data('children', $this.val());
			if (!triggered) {
				if ($btn.val()==$btn.data('label-m')) $btn.trigger('click');
			}
		});
		
		$goPortfolio.find('.gwa-gopf-group-btn-select').trigger('change');
		
		$goPortfolio.delegate('.gwa-gopf-group-btn', 'click', function(e) {
			var $this=$(this);
			$goPortfolio.find('.gwa-gopf-group[data-parent~="'+$this.data('parent')+'"]:visible').hide();
			if ($this.val()==$this.data('label-o')) {
				$goPortfolio.find('.gwa-gopf-group[data-parent~="'+$this.data('parent')+'"][data-children~="'+$this.data('children')+'"]').show();
				$this.val($this.data('label-m'));
			} else {
				$this.val($this.data('label-o'));	
			}
			
			
		});
		
		/* checkbox list - open if child checked */
		$goPortfolio.delegate('.gwa-gopf-checkbox-parent', 'click', function(){
			var $this=$(this);
			if ($this.is(':checked')) {
				$this.closest('li').find('ul input[type="checkbox"]').removeAttr('checked');
			};		
		});
		
		$goPortfolio.find('.gwa-gopf-checkbox-list').each(function(index, element) {
			var $this=$(this);
			if ($this.find('input[type="checkbox"]:checked').length) {
				$this.prev().find('>span').addClass('gwa-gopf-closed').end().closest('li').find('>ul').show();
			};
		});

		/* check & uncheck all checkbox */
		$goPortfolio.delegate('.gwa-gopf-check-all, .gwa-gopf-uncheck-all', 'click', function(e){	
			var $this=$(this);
			e.preventDefault();
			if ($this.hasClass('gwa-gopf-check-all')) {
				$this.closest('li').siblings().find('>label input[type="checkbox"]').not(':checked').each(function(index, element) {
                    $(this).attr('checked','checked').trigger('click').attr('checked','checked');
                });
			} else {
				$this.closest('li').siblings().find('>label input[type="checkbox"]').removeAttr('checked');
			};
		});
		
		/* checkbox list event */
		$goPortfolio.delegate('.gwa-gopf-checkbox-list input[type="checkbox"]', 'click', function(){
			var $this=$(this);
			if ($this.is(':checked')) {
				if ($this.parents('.gwa-gopf-checkbox-list').length>1) {
					$this.parents('.gwa-gopf-checkbox-list').each(function(index, element) {
						var $obj=$(this);
						$obj.closest('.gwa-gopf-checkbox-list').prev('label').find('.gwa-gopf-checkbox-parent:first').removeAttr('checked');	
					});
				};
			};			
		});
		
		$goPortfolio.delegate('.gwa-gopf-checkbox-list label span', 'click', function(){
			var $this=$(this);
			if ($this.closest('label').find('input[type="checkbox"]').hasClass('gwa-gopf-checkbox-parent')) { 
				if (!$this.hasClass('gwa-gopf-closed')) {
				$this.addClass('gwa-gopf-closed')
				.closest('li').find('.gwa-gopf-checkbox-list:first').slideDown(200);
				} else {
					$this.removeClass('gwa-gopf-closed')
					.closest('li').find('.gwa-gopf-checkbox-list:first').slideUp(200);
				};
			};
			return false;
		});

		/* checkbox list - set opacity if no child */
		$goPortfolio.find('.gwa-gopf-checkbox-list').each(function(index, element) {
			var $this=$(this);
			if ($this.find('li').length==0) {
				$this.closest('li').find('input[type="checkbox"]').removeClass('gwa-gopf-checkbox-parent').next('span').css('opacity',0.5);
			};
		});		
		
		$goPortfolio.delegate('#gwa-gopf-select', 'change', function() {
			var $this=$(this),
				$form=$this.closest('form'),
				$actionType=$form.find('#gwa-gopf-action-type'),
				$btnEdit=$form.find('.gwa-gopf-edit'),
				$btnClone=$form.find('.gwa-gopf-clone'),
				$btnDelete=$form.find('.gwa-gopf-delete'),
				editLabelOrig=$btnEdit.data('label-o'),
				editLabelMod=$btnEdit.data('label-m');
								
			if ($this.val()=='') {
				$btnEdit.val(editLabelOrig);
				$btnClone.hide();
				$btnDelete.hide();
			} else {
				$btnEdit.val(editLabelMod);				
				$btnClone.show();
				$btnDelete.show();
			};
		});

		$goPortfolio.find('#gwa-gopf-select').trigger('change');
		
		
	/* ---------------------------------------------------------------------- /
		[2] MAIN PAGE
	/ ---------------------------------------------------------------------- */	

		var $pfForm = $goPortfolio.find('#gwa-gopf-form');		
		$pfForm.delegate('.gwa-gopf-edit, .gwa-gopf-clone, .gwa-gopf-delete', 'click', function() {
			var $this=$(this), 
				$actionType=$pfForm.find('#gwa-gopf-action-type');
			if ($this.hasClass('gwa-gopf-edit')) {
				$actionType.val('edit');
			} else if ($this.hasClass('gwa-gopf-clone')) {
				var confirmQuestion = confirm($this.data('confirm'));
				if (confirmQuestion){
					$actionType.val('clone');
					$pfForm.submit();	
				};								
			} else if ($this.hasClass('gwa-gopf-delete')) {
				var confirmQuestion = confirm($this.data('confirm'));
				if (confirmQuestion){
					$actionType.val('delete');
					$pfForm.submit();	
				};				
			};
		});
		
		/* Submit */
		$pfForm.submit(function(){
			$goPortfolio.find('.gwa-gopf-group-btn-select').trigger('change', true);
		})
		
		/* form ajax submit */
		$pfForm.submit(function(){
			var $this=$(this);
			if ( typeof $this.data('ajax') != 'undefined' &&  $this.data('ajax')===true ) {
				if ($this.data('ajaxerrormsg')!=undefined) {
					$.ajax({  
						type: 'post', 
						url: ajaxurl,
						data: jQuery.param({ action: 'go_portfolio_plugin_menu_page', ajax: 'true' })+'&'+$this.serialize(),
						beforeSend: function () {
							$pfForm.find('input[type=submit]').attr('disabled', 'disabled');
							$pfForm.find('.submit .ajax-loading').css('visibility','visible');
						}
					}).always(function() {
							$pfForm.find('input[type=submit]').removeAttr('disabled');
							$pfForm.find('.submit .ajax-loading').css('visibility','hidden');
							$pfForm.prev('#result').remove();
					}).fail(function(jqXHR, textStatus) {
							$this.before('<div id="result" class="error"><p><strong>'+$this.data('ajaxerrormsg')+'</p></div>')
					}).done(function(data) {
						var $ajaxResponse=$('<div />', { 'class':'ajax-response', 'html' : data }),
							$ajaxResult=$ajaxResponse.find('#result').wrap('<div class="temp">');
							if ($ajaxResponse.find('#redirect').length) {
								if (!window.history.pushState) {
									window.location=$ajaxResponse.find('#redirect').html().replace(/amp;/g, '');
								} else {
									window.history.pushState('', '', $ajaxResponse.find('#redirect').html().replace(/amp;/g, ''));							
								};
							};
							$pfForm.before($ajaxResult.closest('.temp').html());
							$pfForm.prev('#result');
					});
					return false;
				};
			};
		});	
		
		$pfForm.delegate('select[name="template"]', 'change', function() {
			var $this=$(this);
			$pfForm.find('[name="template-data"]').val($pfForm.find('[data="template-code['+$this.val()+']"]').val());
		});
		
		$pfForm.delegate('select[name="style"]', 'change', function() {
			var $this=$(this);
			$pfForm.find('[name="style-data"]').val($pfForm.find('[data="style-code['+$this.val()+']"]').val());
			if ($pfForm.find('select[data="style-effect['+$this.val()+']"]').length) {
				$pfForm.delegate('select[data="style-effect['+$this.val()+']"]').trigger('change');
			} else {
				$pfForm.find('[name="effect-data"]').val('');
			};
		});
		
		$pfForm.delegate('select[data*="style-effect"]', 'change', function() {
			var $this=$(this);
			$pfForm.find('[name="effect-data"]').val($this.val());
		});				
		
		$pfForm.delegate('.gwa-gopf-reset-template, .gwa-gopf-reset-style', 'click', function() {
			var $this=$(this);
			
			if ($this.hasClass('gwa-gopf-reset-template')) {
				var actionType = 'template',
					item = 'template='+$this.closest('td').find('select').val();
			} else {
				var actionType = 'style',
					item = 'style='+$this.closest('td').find('select').val();
			};
			
			$.ajax({  
				type: 'get', 
				url: ajaxurl,
				data: jQuery.param({ action: 'go_portfolio_reset_template_style' })+'&'+item,
				beforeSend: function () {
					$this.attr('disabled', 'disabled');
					$pfForm.find('[data="'+actionType+'-code['+$this.closest('td').find('select').val()+']"]').attr('disabled', 'disabled');
					$this.next('.ajax-loading').css('visibility','visible');
				}
			}).always(function() {
					$this.removeAttr('disabled');
					$pfForm.find('[data="'+actionType+'-code['+$this.closest('td').find('select').val()+']"]').removeAttr('disabled');
					$this.next('.ajax-loading').css('visibility','hidden');
			}).fail(function(jqXHR, textStatus) {
					$this.before('<div id="result" class="error"><p><strong>'+$this.data('ajaxerrormsg')+'</p></div>').delay(3000).slideUp(function(){ $(this).remove(); });
			}).done(function(data) {
				$pfForm.find('[data="'+actionType+'-code['+$this.closest('td').find('select').val()+']"]').val(data)
			});
		});				
		
	/* ---------------------------------------------------------------------- /
		[3] SUBMENU PAGE - TEMPLATE & STYLE EDITOR
	/ ---------------------------------------------------------------------- */			
		
		/* Template & Style editor */
		var $editorForm = $goPortfolio.find('#gwa-gopf-editor-form');
		$editorForm.delegate('.gwa-gopf-edit, .gwa-gopf-reset, .gwa-gopf-import, .gwa-gopf-edit-item, .gwa-gopf-reset-item', 'click', function() {
			var $this=$(this);
			if ($this.hasClass('gwa-gopf-edit')) {
				$editorForm.find('#gwa-gopf-action-type').val('edit');
				$editorForm.submit();
			} else if ($this.hasClass('gwa-gopf-reset')) {
				$editorForm.find('#gwa-gopf-action-type').val('reset');
				$editorForm.submit();
			} else if ($this.hasClass('gwa-gopf-import')) {
				$editorForm.find('#gwa-gopf-action-type').val('import');
				$editorForm.submit();								
			} else if ($this.hasClass('gwa-gopf-edit-item')) {
				$editorForm.find('#gwa-gopf-action-type').val('edit-item');
				$editorForm.submit();				
			} else if ($this.hasClass('gwa-gopf-reset-item')) {
				$editorForm.find('#gwa-gopf-action-type').val('reset-item');
				$editorForm.submit();				
			};
		});
		
		/* Trigger change on select elements */
		$goPortfolio.find('select:visible').trigger('change');		
		
	/* ---------------------------------------------------------------------- /
		[4] SUBMENU PAGE - CUSTOM POST TYPES
	/ ---------------------------------------------------------------------- */	
		
		var $cptPageForm = $goPortfolio.find('#gwa-gopf-cpt-form');

		$cptPageForm.delegate('.gwa-gopf-edit, .gwa-gopf-clone, .gwa-gopf-delete', 'click', function() {
			var $this=$(this), 
				$actionType=$cptPageForm.find('#gwa-gopf-action-type');
			if ($this.hasClass('gwa-gopf-edit')) {
				$actionType.val('edit');
			} else if ($this.hasClass('gwa-gopf-clone')) {
				var confirmQuestion = confirm($this.data('confirm'));
				if (confirmQuestion){
					$actionType.val('clone');
					$cptPageForm.submit();	
				};							
			} else if ($this.hasClass('gwa-gopf-delete')) {
				var confirmQuestion = confirm($this.data('confirm'));
				if (confirmQuestion){
					$actionType.val('delete');
					$cptPageForm.submit();	
				};				
			};
		});
	
		
	});
}(jQuery));

