jQuery(document).ready(function($) {
	
	tinymce.create('tinymce.plugins.myclients', {  
		init : function(ed, url) {  
			ed.addButton('myclients', {  
				title : 'Clients List',  
				image : url+'/icons/clients_button.png',  
				onclick : function() { 
					
					if($('#shortcode_controle').length) {
						$('#shortcode_controle').remove();
					}
					else
					{
						$('body').append('<div id="divMyClientsEditorOverlay"><div id="divMyClientsEditorPopup"></div></div>');
						
						$('#divMyClientsEditorPopup').load(url+'/editor_popup.php');
						
						$('#divMyClientsEditorPopup').css('margin-top', $(window).height()/2 - $('#divMyClientsEditorPopup').height()/2);
						
					}
				}  
			});  
		},  
		createControl : function(n, cm) {  
			return null;  
		}
	}); 
	tinymce.PluginManager.add('myclients', tinymce.plugins.myclients); 
	
	
	
					
});