/*
 * NOTE: all actions are prefixed by plugin shortnam_action_name
 */

jQuery(function(){
	

});


function doPost(){
	
	//alert(nm_list_vars.ajaxurl);
	/*
	 * TODO: change shortname
	 */
	var data = {action: 'nm_woostore_save_file', user_name: 'admin'};
	
	jQuery.post(nm_todolist_vars.ajaxurl, data, function(resp){
		
		console.log(resp);
	});
}


function get_option(key){
	
	/*
	 * TODO: change plugin shortname
	 */
	var keyprefix = 'nm_woostore';
	
	key = keyprefix + key;
	
	var req_option = '';
	
	jQuery.each(googlerabwah_vars.settings, function(k, option){
		
		//console.log(k);
		
		if (k == key)
			req_option = option;		
	});
	
	//console.log(req_option);
	return req_option;
	
}