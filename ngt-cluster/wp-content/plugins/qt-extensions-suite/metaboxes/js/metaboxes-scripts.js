jQuery(function($) {

	$(document).ready(function() {
	    $("form").attr('novalidate', 'novalidate');
	});

	jQuery(document).ready(function($){
	    $('.meta_box_color').wpColorPicker();
	});

	function addUploaderFunctionToButton(upButt){
		upButt.click(function(e) {
			formID = $(this).attr('rel');
			formfield = $(this).siblings('.meta_box_upload_file');
			preview = $(this).siblings('.meta_box_filename');
			icon = $(this).siblings('.meta_box_file');
			tb_show('Choose File', 'media-upload.php?post_id=' + formID + '&type=file&TB_iframe=1');
			window.orig_send_to_editor = window.send_to_editor;
			window.send_to_editor = function(html) {
				fileurl = $(html).attr('href');
				//filename = $(html).text();
				formfield.val(fileurl);
				preview.text(fileurl);
				icon.addClass('checked');
				tb_remove();
				window.send_to_editor = window.orig_send_to_editor;
			}
			//return false;
		});
	}

/*
	function addImageuploaderFunctionToButton(clndbtnimg){
		clndbtnimg.click(function() {
				formID = $(this).attr('rel');
				formfield = $(this).siblings('.meta_box_upload_image');
				preview = $(this).siblings('.meta_box_preview_image');
				tb_show('Choose Image', 'media-upload.php?post_id=' + formID + '&type=image&TB_iframe=1');
				window.orig_send_to_editor = window.send_to_editor;
				window.send_to_editor = function(html) {
					imgurl = html.match(/<img.*?src="(.*?)"/);
					id = html.match(/wp-image-(.*?)"/, '');
					formfield.val(id[1]);
					preview.attr('src', imgurl[1]);
					tb_remove();
					window.send_to_editor = window.orig_send_to_editor;
				}
				//return false;
			});
	}
*/
/* Igor: new uploader */
	function addImageuploaderFunctionToButton(clndbtnimg){
		clndbtnimg.click(function() {
			var file_frame, image_data;
		    if ( undefined !== file_frame ) {
		        file_frame.open();
		        return;
		    }
		    file_frame = wp.media.frames.file_frame = wp.media({
		        frame:    'post',
		        state:    'insert',
		        multiple: false
		    });
     		var formID = $(this).attr('rel');
				formfield = $(this).siblings('.meta_box_upload_image');
				preview = $(this).siblings('.meta_box_preview_image');
		    file_frame.on( 'insert', function() {
		         json = file_frame.state().get( 'selection' ).first().toJSON();
		         console.log('imgurl:'+json.url);
		         console.log('id:'+json.id);
		         imgurl = json.url;
		         id = json.id;
		         preview.attr('src', imgurl);
		         formfield.val(id);
		    });
		    file_frame.open();
		});
	}


	function clearImgField(btn){
		btn.click(function() {
				var defaultImage = $(this).parent().siblings('.meta_box_default_image').text();
				$(this).parent().siblings('.meta_box_upload_image').val('');
				$(this).parent().siblings('.meta_box_preview_image').attr('src', defaultImage);
				return false;
			});
	}



	function conditionalFieldReveal(){

		//$(".qw-conditional-fields")



		$(".qw-conditional-fields").each(function () {
		    var str = "";
		    $(this).find( "option:selected" ).each(function() {

		    	if( $(this).attr('data-tohide')){
			    	$.toHideArray = $(this).attr('data-tohide').split("[+]");
			      	if($.toHideArray.length > 0){
			      		$.each($.toHideArray,function(i,v){
			      			if($(v).not("qw-hidden")){
				      			$(v).addClass("qw-hidden");
				      		}
			      		});
			      	}
			    }

			    if( $(this).attr('data-toreveal')){
			      	$.toRevealArray = $(this).attr('data-toreveal').split("[+]");
			      	if($.toRevealArray.length > 0){
			      		$.each($.toRevealArray,function(i,v){
			      			$(v).removeClass("qw-hidden");
			      		});
			      	}
			    }
		    });
		   // console.log(str);
		 });
	}
	conditionalFieldReveal();
	$(".qw-conditional-fields").change(function () {
		conditionalFieldReveal();
	});






	/* = initialization
	=============================================================*/
	
	function addNewFunctionsBtn(){
			// the upload image button, saves the id and outputs a preview of the image

			addImageuploaderFunctionToButton($('.meta_box_upload_image_button'));

			
			// the remove image link, removes the image id from the hidden field and replaces the image preview
			
			
			clearImgField($('.meta_box_clear_image_button'));
			
			
			// the remove image link, removes the image id from the hidden field and replaces the image preview
			$('.meta_box_clear_file_button').click(function() {
				$(this).parent().siblings('.meta_box_upload_file').val('');
				$(this).parent().siblings('.meta_box_filename').text('');
				$(this).parent().siblings('.meta_box_file').removeClass('checked');
				return false;
			});
			
			// function to create an array of input values
			function ids(inputs) {
				var a = [];
				for (var i = 0; i < inputs.length; i++) {
					a.push(inputs[i].val);
				}
				//$("span").text(a.join(" "));
			}
			
			
			$('.meta_box_repeatable_remove').live('click', function(){
				$(this).closest('tr').remove();
				return false;
			});
				
			$('.meta_box_repeatable tbody').sortable({
				opacity: 0.6,
				revert: true,
				cursor: 'move',
				handle: '.hndle'
			});
			
			// post_drop_sort	
			$('.sort_list').sortable({
				connectWith: '.sort_list',
				opacity: 0.6,
				revert: true,
				cursor: 'move',
				cancel: '.post_drop_sort_area_name',
				items: 'li:not(.post_drop_sort_area_name)',
				update: function(event, ui) {
					var result = $(this).sortable('toArray');
					var thisID = $(this).attr('id');
					$('.store-' + thisID).val(result) 
				}
			});
		
			$('.sort_list').disableSelection();
		
			// turn select boxes into something magical
			if (!!$.prototype.chosen)
				$('.chosen').chosen({ allow_single_deselect: true });
				
	}
	 addNewFunctionsBtn();
	 
	 
	 // the file image button, saves the id and outputs the file name
			
			
			
			
	
	
	
	//var upButt = $('.meta_box_upload_file_button');
	addUploaderFunctionToButton($('.meta_box_upload_file_button'));
	
			
			
	// repeatable fields
	$('.meta_box_repeatable_add').live('click', function(e) {
		// clone
		e.preventDefault();
		var row = $(this).closest('.meta_box_repeatable').find('tbody tr:last-child');
		var clone = row.clone();
		clone.find('select.chosen').removeAttr('style', '').removeAttr('id', '').removeClass('chzn-done').data('chosen', null).next().remove();
		clone.find('input.regular-text, textarea, select, .meta_box_upload_file ').val('');
		clone.find('input[type=checkbox], input[type=radio]').attr('checked', false);
		clone.find('span.meta_box_filename').html('');
		
		/** = i have to restore functionality of buttons
		*/
		var clndbtn = clone.find('.meta_box_upload_file_button');
		var clndbtnimg = clone.find('.meta_box_upload_image_button');
		var cleanImgFieldBtn = clone.find('.meta_box_clear_image_button');

		clone.find('img.meta_box_preview_image').attr('src','');

		addUploaderFunctionToButton(clndbtn);
		addImageuploaderFunctionToButton(clndbtnimg);
		clearImgField(cleanImgFieldBtn);
		
		 
		row.after(clone);
		// increment name and id
		clone.find('input, textarea, select')
			.attr('name', function(index, name) {
				if("undefined" === typeof(name)) {
					return '';
				}
				return name.replace(/(\d+)/, function(fullMatch, n) {
					return Number(n) + 1;
				});
		});

		var arr = [];

		$('input.repeatable_id:text').each(function(){ arr.push($(this).val()); }); 
		clone.find('input.repeatable_id')
			.val(Number(Math.max.apply( Math, arr )) + 1);
		if (!!$.prototype.chosen) {
			clone.find('select.chosen')
				.chosen({allow_single_deselect: true});
		}

		 
		
		//
		return false;
	});




	/**
	 *
	 *	Add icons modal window fontawesome and material icons
	 *
	 * 
	 */
	$("a.qw-iconreference-open").click(function(e){
		e.preventDefault();
		$("body").addClass("qwModalFormOpen");
		$("#qwModalForm").height($(window).height());
		$.iconTarget = $(this).attr("data-target");
		$("#adminmenuwrap").css({"z-index":"10"});
	});
	$("#qw-closemodal").on("click",function(e){
		$("body").removeClass("qwModalFormOpen");
		$("#adminmenuwrap").css({"z-index":"1000"});
	});
	$("#qwiconsMarket").on("click",".btn",function(e){
		e.preventDefault();
		var theclass = $(this).attr("data-icon");
		if($.iconTarget != undefined){
			var target = $.iconTarget;
			console.log(target);
			if(target !== 'tinymce'){
				$("#"+$.iconTarget).val(theclass);
				$("#theIcon"+$.iconTarget).removeClass().addClass(theclass+" bigicon");
			} else {
				//var ed = tinyMCE.activeEditor;
				//ed.execCommand('mceInsertContent', false, '<a id="_mce_temp_rob" href="http://robubu.com">robubu.com</a>');

				tinymce.activeEditor.execCommand('mceInsertContent', false, '[qticon class="'+theclass+'" size="s|m|l|xl|xxl"]');

			}


			$("body").removeClass("qwModalFormOpen");
			$("#adminmenuwrap").css({"z-index":"1000"});


		}

	});

	$(".qw_hider").click(function(i,c){

		var that = $(this);
		$(".qw_hiddenable").addClass("qw-hide").promise().done(function(){
			$(".qw_hiddenable .qw_hider").addClass("dashicons-hidden").removeClass("dashicons-visibility");// hide all the tabs
			that.closest(".qw_hiddenable").removeClass("qw-hide"); // show only the right one
			that.removeClass("dashicons-hidden").addClass("dashicons-visibility"); //change icon of the right one
		});

	});




	/**
	 *
	 *	Geocoding function
	 * 
	 */
	
	$(".geocodefunction").click(function(i,c){
		var that = $(this),
			id = that.attr("data-target"),
			address = $("#address-"+id).attr("value"),
			resultsfield = $("#results-"+id),
			geocoder = new google.maps.Geocoder(),
			mapcontainer =  $("#map-"+id);

		geocoder.geocode({'address': address}, function(results, status) {
		    if (status === google.maps.GeocoderStatus.OK) {

		    	
				// just creating map randomly centered
				mapcontainer.height("180px");
				var map = new google.maps.Map(document.getElementById("map-"+id), {
				    zoom: 10,
				    center: {lat: results[0].geometry.location.lat(), lng: results[0].geometry.location.lng()}
				});

		      map.setCenter(results[0].geometry.location);
		      var marker = new google.maps.Marker({
		        map: map,
		        position: results[0].geometry.location
		      });
		      var results = results[0].geometry.location.lat()+","+results[0].geometry.location.lng();
		      resultsfield.html('');
		      $("#"+id).attr("value",results);
		    } else {
		    	resultsfield.html('Geocode was not successful for the following reason: ' + status);
		    }
		});


	});


	/**
	 *
	 *	Admin tabs (used for example for icons choice)
	 * 
	 */

	$(".qt-tabs .qt-tabnav a").click(function(e){
		e.preventDefault();
		var that = $(this),
			selectedId = that.attr("href");
		console.log(selectedId);

		that.closest(".qt-tabs").find(".qt-tab.active").removeClass("active");
		$(selectedId).addClass("active");
	});

	//$("body").append('');
});









