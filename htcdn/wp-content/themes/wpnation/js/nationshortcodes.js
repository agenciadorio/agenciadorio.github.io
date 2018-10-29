(function() {
 
	tinymce.create('tinymce.plugins.nationShortcodeMce', {  
        init : function(editor, url) { 

			editor.addButton( 'nation_shortcodes', {
				type: 'listbox',
				icon: 'nation-shortcode-icon',
				text: 'Shortcodes',
				onselect: function(e) {}, 
				values: [
					{text:'Colums', menu:[
						{text:'One Half', onclick : function() {
							tinymce.execCommand('mceInsertContent', false, '[two_columns] Sample Content [/two_columns]');
						}},
						{text:'One Third', onclick : function() {
							tinymce.execCommand('mceInsertContent', false, '[three_columns] Sample Content [/three_columns]');
						}},
						{text:'One Fourth', onclick : function() {
							tinymce.execCommand('mceInsertContent', false, '[four_columns] Sample Content [/four_columns]');
						}},
						{text: '-'},
						{text:'One Half Last', onclick : function() {
							tinymce.execCommand('mceInsertContent', false, '[two_columns_last] Sample Content [/two_columns_last]');
						}},
						{text:'One Third Last', onclick : function() {
							tinymce.execCommand('mceInsertContent', false, '[three_columns_last] Sample Content [/three_columns_last]');
						}},
						{text:'One Fourth Last', onclick : function() {
							tinymce.execCommand('mceInsertContent', false, '[four_columns_last] Sample Content [/four_columns_last]');
						}}
					]},
					{text:'Typography', menu:[
						{text:'Header 1', onclick : function() {
							tinymce.execCommand('mceInsertContent', false, '[header type="h1"] Sample Content [/header]');
						}},
						{text:'Header 2', onclick : function() {
							tinymce.execCommand('mceInsertContent', false, '[header type="h2"] Sample Content [/header]');
						}},
						{text:'Header 3', onclick : function() {
							tinymce.execCommand('mceInsertContent', false, '[header type="h3"] Sample Content [/header]');
						}},
						{text:'Header 4', onclick : function() {
							tinymce.execCommand('mceInsertContent', false, '[header type="h4"] Sample Content [/header]');
						}},
						{text:'Header 5', onclick : function() {
							tinymce.execCommand('mceInsertContent', false, '[header type="h5"] Sample Content [/header]');
						}},
						{text:'Header 6', onclick : function() {
							tinymce.execCommand('mceInsertContent', false, '[header type="h6"] Sample Content [/header]');
						}},
						{text: '-'},
						{text:'Dropcap', onclick : function() {
							tinymce.execCommand('mceInsertContent', false, '[dropcap] Sample Content [/dropcap]');
						}},
						{text:'Hightlight black', onclick : function() {
							tinymce.execCommand('mceInsertContent', false, '[highlight] Sample Content [/highlight]');
						}},
						{text:'Hightlight color', onclick : function() {
							tinymce.execCommand('mceInsertContent', false, '[highlight color="on"] Sample Content [/highlight]');
						}},
						{text: '-'},
						{text:'Table', onclick : function() {
							tinymce.execCommand('mceInsertContent', false, '[table cols="Table header,Table header,Table header" data="Row 1 Column 1,Row 1 Column 2,Row 1 Column 3,Row 2 Column 1,Row 2 Column 2,Row 2 Column 3,Row 3 Column 1,Row 3 Column 2,Row 3 Column 3"]');
						}},
						{text: '-'},
						{text:'Blockquote', onclick : function() {
							tinymce.execCommand('mceInsertContent', false, '[blockquote] Sample Content [/blockquote]');
						}},
						{text:'Blockquote left', onclick : function() {
							tinymce.execCommand('mceInsertContent', false, '[blockquote align="left"] Sample Content [/blockquote]');
						}},
						{text:'Blockquote right', onclick : function() {
							tinymce.execCommand('mceInsertContent', false, '[blockquote align="right"] Sample Content [/blockquote]');
						}}
					]},
					{text:'Lists', menu:[
						{text:'List type 1', onclick : function() {
							tinymce.execCommand('mceInsertContent', false, '[list divider="0" painted="0" icon="1" elements="First element,Second element,Third element,Fourth element"]');
						}},
						{text:'List type 2', onclick : function() {
							tinymce.execCommand('mceInsertContent', false, '[list divider="0" painted="1" icon="1" elements="First element,Second element,Third element,Fourth element"]');
						}},
						{text:'List type 3', onclick : function() {
							tinymce.execCommand('mceInsertContent', false, '[list divider="1" painted="1" icon="1" elements="First element,Second element,Third element,Fourth element"]');
						}},
						{text: '-'},
						{text:'List icon 1', onclick : function() {
							tinymce.execCommand('mceInsertContent', false, '[list divider="0" painted="1" icon="1" elements="First element,Second element,Third element,Fourth element"]');
						}},
						{text:'List icon 2', onclick : function() {
							tinymce.execCommand('mceInsertContent', false, '[list divider="0" painted="1" icon="2" elements="First element,Second element,Third element,Fourth element"]');
						}},
						{text:'List icon 3', onclick : function() {
							tinymce.execCommand('mceInsertContent', false, '[list divider="0" painted="1" icon="3" elements="First element,Second element,Third element,Fourth element"]');
						}},
						{text:'List icon 4', onclick : function() {
							tinymce.execCommand('mceInsertContent', false, '[list divider="0" painted="1" icon="4" elements="First element,Second element,Third element,Fourth element"]');
						}},
						{text:'List icon 5', onclick : function() {
							tinymce.execCommand('mceInsertContent', false, '[list divider="0" painted="1" icon="5" elements="First element,Second element,Third element,Fourth element"]');
						}},
						{text:'List icon 6', onclick : function() {
							tinymce.execCommand('mceInsertContent', false, '[list divider="0" painted="1" icon="6" elements="First element,Second element,Third element,Fourth element"]');
						}},
						{text:'Without Icon', onclick : function() {
							tinymce.execCommand('mceInsertContent', false, '[list divider="0" painted="1" icon="0" elements="First element,Second element,Third element,Fourth element"]');
						}},
					]},
					{text:'Buttons', menu:[
						{text:'Button Medium', onclick : function() {
							tinymce.execCommand('mceInsertContent', false, '[button href="#" size="medium" icon="icon-suitcase"] Sample Content [/button]');
						}},
						{text:'Button Small', onclick : function() {
							tinymce.execCommand('mceInsertContent', false, '[button href="#" size="small" icon="icon-suitcase"] Sample Content [/button]');
						}},
						{text:'Button Large', onclick : function() {
							tinymce.execCommand('mceInsertContent', false, '[button href="#" size="large" icon="icon-suitcase"] Sample Content [/button]');
						}},
						{text: '-'},
						{text:'Button Black', onclick : function() {
							tinymce.execCommand('mceInsertContent', false, '[button href="#" size="medium" color="black" icon="icon-suitcase"] Sample Content [/button]');
						}},
						{text:'Button Alternative', onclick : function() {
							tinymce.execCommand('mceInsertContent', false, '[button href="#" type="alt" size="medium" icon="icon-suitcase"] Sample Content [/button]');
						}}
					]},
					{text:'Messages&Icons', menu:[
						{text:'Info', onclick : function() {
							tinymce.execCommand('mceInsertContent', false, '[info icon=""] Sample Content [/info]');
						}},
						{text:'Error', onclick : function() {
							tinymce.execCommand('mceInsertContent', false, '[error icon=""] Sample Content [/error]');
						}},
						{text:'Warning', onclick : function() {
							tinymce.execCommand('mceInsertContent', false, '[warning icon=""] Sample Content [/warning]');
						}},
						{text:'Success', onclick : function() {
							tinymce.execCommand('mceInsertContent', false, '[success icon=""] Sample Content [/success]');
						}},
						{text: '-'},
						{text:'Icon', onclick : function() {
							tinymce.execCommand('mceInsertContent', false, '[icon name=""]');
						}}
					]},
					{text:'Dividers', menu:[
						{text:'Divider 1', onclick : function() {
							tinymce.execCommand('mceInsertContent', false, '[divider]');
						}},
						{text:'Divider 2', onclick : function() {
							tinymce.execCommand('mceInsertContent', false, '[divider type="2"]');
						}},
						{text:'Clear floats', onclick : function() {
							tinymce.execCommand('mceInsertContent', false, '[clear]');
						}},
						{text:'Spacing',onclick : function() {
							tinymce.execCommand('mceInsertContent', false, '[spacing size="60px"]');
						}}
					]},
					{text:'Widgets', menu:[
						{text:'Accordion', onclick : function() {
							tinymce.execCommand('mceInsertContent', false, '[accordion]<br>[section title="Section 1" show="show"] Sample Content [/section]<br>[section title="Section 2"] Sample Content [/section]<br>[section title="Section 3"] Sample Content [/section]<br>[/accordion]');
						}},
						{text:'Tabs', onclick : function() {
							tinymce.execCommand('mceInsertContent', false, '[tabs]<br>[tab title="First Tab" icon=""] Sample Content [/tab]<br>[tab title="Second Tab"] Sample Content [/tab]<br>[tab title="Third Tab"] Sample Content [/tab]<br>[/tabs]');
						}},
						{text:'Toggle', onclick : function() {
							tinymce.execCommand('mceInsertContent', false, '[toggle]<br>[section title="Section 1"] Sample Content [/section]<br>[section title="Section 2"] Sample Content [/section]<br> [section title="Section 3"] Sample Content [/section]<br>[/toggle]');
						}}
					]},
				]
			});	
			
		}
    }); 
	
	tinymce.PluginManager.add('nation_button', tinymce.plugins.nationShortcodeMce);
})();