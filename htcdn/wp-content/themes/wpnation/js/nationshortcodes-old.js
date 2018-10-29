(function() {  
    tinymce.create('tinymce.plugins.nationShortcodeMce', {  
        init : function(ed, url) {  
			tinymce.plugins.nationShortcodeMce.theurl = url;
        },  
        createControl : function(btn, e) {
			if ( btn == "nation_button" ) {
				var a = this;	
				var btn = e.createSplitButton('nation_button', {
	                title: "Nation Shortcodes",
					image: tinymce.plugins.nationShortcodeMce.theurl +"/../images/nation-shortcode.png",
					icons: false,
	            });
				
	            btn.onRenderMenu.add(function (c, b) {
					b.add({title : 'Nation Shortcodes', 'class' : 'mceMenuItemTitle'}).setDisabled(1);
					
					// Columns
					c = b.addMenu({title:"Columns"});
					
						a.render( c, "One Half", "one-half" );
						a.render( c, "One Third", "one-third" );
						a.render( c, "One Fourth", "one-fourth" );
						
						c.addSeparator();
						
						a.render( c, "One Half Last", "one-half-last" );
						a.render( c, "One Third Last", "one-third-last" );
						a.render( c, "One Fourth Last", "one-fourth-last" );
					
					b.addSeparator();
					
					
					// Typography
					c = b.addMenu({title:"Typography"});
									
						a.render( c, "Header 1", "h1" );
						a.render( c, "Header 2", "h2" );
						a.render( c, "Header 3", "h3" );
						a.render( c, "Header 4", "h4" );
						a.render( c, "Header 5", "h5" );
						a.render( c, "Header 6", "h6" );
						
						c.addSeparator();
						
						a.render( c, "Dropcap", "dropcap" );
						a.render( c, "Highlight black", "highlight_black" );
						a.render( c, "Highlight color", "highlight_color" );
						
						c.addSeparator();
						
						a.render( c, "Table", "table" );
						
						c.addSeparator();
						
						a.render( c, "Blockquote", "blockquote" );
						a.render( c, "Blockquote left", "blockquote-left" );	
						a.render( c, "Blockquote right", "blockquote-right" );
					
					b.addSeparator();
					
					// Lists
					c = b.addMenu({title:"Lists"});
					
						a.render( c, "List Type 1", "list-type1" );
						a.render( c, "List Type 2", "list-type2" );
						a.render( c, "List Type 3", "list-type3" );
						
						c.addSeparator();
						
						a.render( c, "List Icon 1", "list-icon1" );
						a.render( c, "List Icon 2", "list-icon2" );
						a.render( c, "List Icon 3", "list-icon3" );
						a.render( c, "List Icon 4", "list-icon4" );
						a.render( c, "List Icon 5", "list-icon5" );
						a.render( c, "List Icon 6", "list-icon6" );
						a.render( c, "Without Icon", "list-icon0" );
						
					b.addSeparator();
					
					// Buttons
					c = b.addMenu({title:"Buttons"});
					
						a.render( c, "Button Medium", "button-size1" );
						a.render( c, "Button Small", "button-size2" );
						a.render( c, "Button Large", "button-size3" );
						
						c.addSeparator();
						
						a.render( c, "Button Black", "button-black" );
						a.render( c, "Button Alternative", "button-alt" );
						
					b.addSeparator();
					
					// Messages & Icons
					c = b.addMenu({title:"Messages&Icons"});
					
						a.render( c, "Info", "info" );
						a.render( c, "Error", "error" );
						a.render( c, "Warning", "warning" );
						a.render( c, "Success", "success" );
						
						c.addSeparator();
						
						a.render( c, "Icon", "icon" );
						
						
						
					b.addSeparator();
					
					// Dividers
					c = b.addMenu({title:"Dividers"});
					
						a.render( c, "Divider 1", "divider1" );
						a.render( c, "Divider 2", "divider2" );
						a.render( c, "Clear floats", "clear" );
						a.render( c, "Spacing", "spacing" );
						
					b.addSeparator();
					
					// Widgets
					c = b.addMenu({title:"Widgets"});
					
						a.render( c, "Accordion", "accordion" );
						a.render( c, "Tabs", "tabs" );
						a.render( c, "Toggle", "toggle" );
					
					b.addSeparator();
					
				});
	            
	          return btn;
			}
			
            return null;  
        }, 

		render : function(ed, title, id) {
			ed.add({
				title: title,
				onclick: function () {
					
					// Selected content
					var mceSelected = tinyMCE.activeEditor.selection.getContent();
					
					// Add highlighted content inside the shortcode when possible - yay!
					if ( mceSelected ) {
						var dummyContent = mceSelected;
					} else {
						var dummyContent = 'Sample Content';
					}
					
					// Columns
					if(id == "one-half") {
						tinyMCE.activeEditor.selection.setContent('[two_columns]<br />' + dummyContent + '<br />[/two_columns]');
					}
					if(id == "one-third") {
						tinyMCE.activeEditor.selection.setContent('[three_columns]<br />' + dummyContent + '<br />[/three_columns]');
					}
					if(id == "one-fourth") {
						tinyMCE.activeEditor.selection.setContent('[four_columns]<br />' + dummyContent + '<br />[/four_columns]');
					}
					
					if(id == "one-half-last") {
						tinyMCE.activeEditor.selection.setContent('[two_columns_last]<br />' + dummyContent + '<br />[/two_columns_last]');
					}
					if(id == "one-third-last") {
						tinyMCE.activeEditor.selection.setContent('[three_columns_last]<br />' + dummyContent + '<br />[/three_columns_last]');
					}
					if(id == "one-fourth-last") {
						tinyMCE.activeEditor.selection.setContent('[four_columns_last]<br />' + dummyContent + '<br />[/four_columns_last]');
					}	
					
					// Headers
					if(id == "h1") {
						tinyMCE.activeEditor.selection.setContent('[header type="h1"] ' + dummyContent + ' [/header]');
					} 
					if(id == "h2") {
						tinyMCE.activeEditor.selection.setContent('[header type="h2"] ' + dummyContent + ' [/header]');
					}
					if(id == "h3") {
						tinyMCE.activeEditor.selection.setContent('[header type="h3"] ' + dummyContent + ' [/header]');
					}
					if(id == "h4") {
						tinyMCE.activeEditor.selection.setContent('[header type="h4"] ' + dummyContent + ' [/header]');
					}
					if(id == "h5") {
						tinyMCE.activeEditor.selection.setContent('[header type="h5"] ' + dummyContent + ' [/header]');
					}
					if(id == "h6") {
						tinyMCE.activeEditor.selection.setContent('[header type="h6"] ' + dummyContent + ' [/header]');
					}
					
					// Dropcap
					if(id == "dropcap") {
						tinyMCE.activeEditor.selection.setContent('[dropcap] ' + dummyContent + ' [/dropcap]');
					}
					
					// Highlight
					if(id == "highlight_black") {
						tinyMCE.activeEditor.selection.setContent('[highlight]' + dummyContent + '[/highlight]');
					}
					if(id == "highlight_color") {
						tinyMCE.activeEditor.selection.setContent('[highlight color="on"]' + dummyContent + '[/highlight]');
					}
					
					if(id == "highlight_color") {
						tinyMCE.activeEditor.selection.setContent('[highlight color="on"]' + dummyContent + '[/highlight]');
					}
					
					// Table
					if(id == "table") {
						tinyMCE.activeEditor.selection.setContent('[table cols="Table header,Table header,Table header" data="Row 1 Column 1,Row 1 Column 2,Row 1 Column 3,Row 2 Column 1,Row 2 Column 2,Row 2 Column 3,Row 3 Column 1,Row 3 Column 2,Row 3 Column 3"]');
					}
					
					// Blockquotes
					if(id == "blockquote") {
						tinyMCE.activeEditor.selection.setContent('[blockquote]' + dummyContent + '[/blockquote]');
					}
					if(id == "blockquote-left") {
						tinyMCE.activeEditor.selection.setContent('[blockquote align="left"]' + dummyContent + '[/blockquote]');
					}
					if(id == "blockquote-right") {
						tinyMCE.activeEditor.selection.setContent('[blockquote align="right"]' + dummyContent + '[/blockquote]');
					}
					
					// Dividers
					if(id == "divider1") {
						tinyMCE.activeEditor.selection.setContent('[divider]');
					}
					if(id == "divider2") {
						tinyMCE.activeEditor.selection.setContent('[divider type="2"]');
					}
					
					// Clear Floats
					if(id == "clear") {
						tinyMCE.activeEditor.selection.setContent('[clear]');
					}
					
					//Spacing
					if(id == "spacing") {
						tinyMCE.activeEditor.selection.setContent('[spacing size="60px"]');
					}
					
					//Lists
					if(id == "list-type1") {
						tinyMCE.activeEditor.selection.setContent('[list divider="0" painted="0" icon="1" elements="First element,Second element,Third element,Fourth element"]');
					}
					if(id == "list-type2") {
						tinyMCE.activeEditor.selection.setContent('[list divider="0" painted="1" icon="1" elements="First element,Second element,Third element,Fourth element"]');
					}
					if(id == "list-type3") {
						tinyMCE.activeEditor.selection.setContent('[list divider="1" painted="1" icon="1" elements="First element,Second element,Third element,Fourth element"]');
					}
					if(id == "list-icon1") {
						tinyMCE.activeEditor.selection.setContent('[list divider="0" painted="1" icon="1" elements="First element,Second element,Third element,Fourth element"]');
					}
					if(id == "list-icon2") {
						tinyMCE.activeEditor.selection.setContent('[list divider="0" painted="1" icon="2" elements="First element,Second element,Third element,Fourth element"]');
					}
					if(id == "list-icon3") {
						tinyMCE.activeEditor.selection.setContent('[list divider="0" painted="1" icon="3" elements="First element,Second element,Third element,Fourth element"]');
					}
					if(id == "list-icon4") {
						tinyMCE.activeEditor.selection.setContent('[list divider="0" painted="1" icon="4" elements="First element,Second element,Third element,Fourth element"]');
					}
					if(id == "list-icon5") {
						tinyMCE.activeEditor.selection.setContent('[list divider="0" painted="1" icon="5" elements="First element,Second element,Third element,Fourth element"]');
					}
					if(id == "list-icon6") {
						tinyMCE.activeEditor.selection.setContent('[list divider="0" painted="1" icon="6" elements="First element,Second element,Third element,Fourth element"]');
					}
					if(id == "list-icon0") {
						tinyMCE.activeEditor.selection.setContent('[list divider="0" painted="1" icon="0" elements="First element,Second element,Third element,Fourth element"]');
					}
					
					//Buttons
					if(id == "button-size1") {
						tinyMCE.activeEditor.selection.setContent('[button href="#" size="medium" icon="icon-suitcase"] ' + dummyContent + ' [/button]');
					}
					if(id == "button-size2") {
						tinyMCE.activeEditor.selection.setContent('[button href="#" size="small" icon="icon-suitcase"] ' + dummyContent + ' [/button]');
					}
					if(id == "button-size3") {
						tinyMCE.activeEditor.selection.setContent('[button href="#" size="large" icon="icon-suitcase"] ' + dummyContent + ' [/button]');
					}
					if(id == "button-color") {
						tinyMCE.activeEditor.selection.setContent('[button href="#" size="medium" icon="icon-suitcase"] ' + dummyContent + ' [/button]');
					}
					
					if(id == "button-black") {
						tinyMCE.activeEditor.selection.setContent('[button href="#" size="medium" color="black" icon="icon-suitcase"] ' + dummyContent + ' [/button]');
					}
					if(id == "button-alt") {
						tinyMCE.activeEditor.selection.setContent('[button href="#" type="alt" size="medium" icon="icon-suitcase"] ' + dummyContent + ' [/button]');
					}
					
					// Accordion
					if(id == "accordion") {
						tinyMCE.activeEditor.selection.setContent('[accordion]<br>[section title="Section 1" show="show"]<br>' + dummyContent + '<br>[/section]<br>[section title="Section 2"]<br>' + dummyContent + '<br>[/section]<br>[section title="Section 3"]<br>' + dummyContent + '<br>[/section]<br>[/accordion]');
					}
					
					// Toggle
					if(id == "toggle") {
						tinyMCE.activeEditor.selection.setContent('[toggle]<br>[section title="Section 1"]<br>' + dummyContent + '<br>[/section]<br>[section title="Section 2"]<br>' + dummyContent + '<br>[/section]<br>[section title="Section 3"]<br>' + dummyContent + '<br>[/section]<br>[/toggle]');
					}
					
					//Tabs
					if(id == "tabs") {
						tinyMCE.activeEditor.selection.setContent('[tabs]<br>[tab title="First Tab" icon=""]<br>' + dummyContent + '<br>[/tab]<br>[tab title="Second Tab"]<br>' + dummyContent + '<br>[/tab]<br>[tab title="Third Tab"]<br>' + dummyContent + '<br>[/tab]<br>[/tabs]');
					}
					
					
					// Messages
					if(id == "success") {
						tinyMCE.activeEditor.selection.setContent('[success icon=""] ' + dummyContent + ' [/success]');
					}
					if(id == "error") {
						tinyMCE.activeEditor.selection.setContent('[error icon=""] ' + dummyContent + ' [/error]');
					}
					if(id == "warning") {
						tinyMCE.activeEditor.selection.setContent('[warning icon=""] ' + dummyContent + ' [/warning]');
					}
					if(id == "info") {
						tinyMCE.activeEditor.selection.setContent('[info icon=""] ' + dummyContent + ' [/info]');
					}

					
					// Icons
					if(id == "icon") {
						tinyMCE.activeEditor.selection.setContent('[icon name=""]');
					}
					
					
	
											
					
					// Google Map
					if(id == "googlemap") {
						tinyMCE.activeEditor.selection.setContent('[symple_googlemap title="Envato Office" location="2 Elizabeth St, Melbourne Victoria 3000 Australia" zoom="10" height=250]');
					}
								

					
					//Skillbar
					if(id == "skillbar") {
						tinyMCE.activeEditor.selection.setContent('[symple_skillbar title="' + dummyContent + '" percentage="100" color="#6adcfa"]');
					}
					
					//Testimonial
					if(id == "testimonial") {
						tinyMCE.activeEditor.selection.setContent('[symple_testimonial by="WPExplorer"]' + dummyContent + '[/symple_testimonial]');
					}

					
					return false;
				}
			})
		}
		
    });  
    tinymce.PluginManager.add('nation_button', tinymce.plugins.nationShortcodeMce);  
})();  