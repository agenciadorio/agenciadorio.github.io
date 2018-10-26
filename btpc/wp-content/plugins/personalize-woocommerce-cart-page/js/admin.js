jQuery(function(){

	jQuery('#filemanager-tabs').easytabs();
});

function updateOptions(options){
	
	var opt = jQuery.parseJSON(options);
	

	/*
	 * getting action from object
	 */
	
	
	/*
	 * extractElementData
	 * defined in nm-globals.js
	 */
	var data = extractElementData(opt);
	
	
	if (data.bug) {
		//jQuery("#reply_err").html('Red are required');
		alert('bug here');
	} else {

		/*
		 * [1]
		 * TODO: change action name below with prefix plugin shortname_action_name
		 */
		data.action = 'nm_todolist_save_settings';

		jQuery.post(ajaxurl, data, function(resp) {

			//jQuery("#reply_err").html(resp);
			alert(resp);
			window.location.reload(true);

		});
	}
	
	/*jQuery.each(res, function(i, item){
		
		alert(i);
		
	});*/
}

function update_options(options) {

	var opt = jQuery.parseJSON(options);

	jQuery("#filemanager-settigns-saving").html('<img src="'+nm_woostore_vars.doing+'" />');
	/*
	 * extractElementData defined in nm-globals.js
	 */
	var data = extractElementData(opt);

	if (data.bug) {
		// jQuery("#reply_err").html('Red are required');
		alert('bug here');
	} else {

		/*
		 * [1]
		 */
		data.action = 'nm_woostore_save_settings';

		jQuery.post(ajaxurl, data, function(resp) {

			jQuery("#filemanager-settigns-saving").html(resp);
			window.location.reload(true);

		});
	}

}
