(function() {
	tinymce.PluginManager.add('btnsx_mce_button', function( editor, url ) {
	   	function getValues() {
	    	return editor.settings.btnsxButtonsList;
	   	}
	   	function getSocialValues() {
	    	return editor.settings.btnsxSocialButtonsList;
	   	}
	   	function getDualValues() {
	    	return editor.settings.btnsxDualButtonsList;
	   	}
	   	var dualBtns = [{
			text: 'Buttons',
			onclick: function() {
				editor.windowManager.open( {
					title: 'Insert Button',
					width: 400,
					height: 100,
					body: [
						{
							type: 'listbox',
							name: 'listboxName',
							label: 'Buttons',
							'values': getValues()
						}
					],
					onsubmit: function( e ) {
						editor.insertContent( '[btnsx id="' + e.data.listboxName + '"]');
					}
				});
			}
		}];
		editor.addButton('btnsx_mce_button', {
			icon: 'btnsx-logo dashicons-btnsx-logo',
			tooltip: 'Buttons X',
			type: 'menubutton',
			menu: dualBtns
		});
	});
})();