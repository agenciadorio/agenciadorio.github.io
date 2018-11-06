(function() {
	tinymce.PluginManager.add('qt_gridstackshortcode', function( editor, url ) {
		var sh_tag = 'qt_gridstackshortcode';

		//helper functions 
		function getAttr(s, n) {
			n = new RegExp(n + '=\"([^\"]+)\"', 'g').exec(s);
			return n ?  window.decodeURIComponent(n[1]) : '';
		};

		function html( cls, data ,con) {
			var placeholder = url + '/img/' + getAttr(data,'gridstack') + '.jpg';
			gridstack = getAttr(data,'gridstack');
			posttype = getAttr(data,'posttype');// window.encodeURIComponent( posttype );
			quantity = getAttr(data,'quantity');
			taxonomy = getAttr(data,'taxonomy');
			archivelink = getAttr(data,'archivelink');
			term_ids = getAttr(data,'term_ids');

			return '<img src="' + placeholder + '" class="mceItem ' + cls + '" ' + 'data-gridstack="' + gridstack + '" data-posttype="'+ posttype+'" \
			data-mce-resize="false" data-mce-placeholder="1" data-quantity="'+quantity+'"  data-taxonomy="'+taxonomy+'" data-term_ids="'+term_ids+'" data-archivelink="'+archivelink+'" />';
		}

		function replaceShortcodes( content ) {
			console.log(content);
			return content.replace( /\[qt_gridstackshortcode ([^\]]*)\]/g, 
				function( all,attr,con) {
					return html( 'wp-qt_gridstackshortcode', attr , con);
				}
			);

			            // return content.replace( /\[bs3_panel([^\]]*)\]([^\]]*)\[\/bs3_panel\]/g, function( all,attr,con) {

		}

		function restoreShortcodes( content ) {
			return content.replace( /(?:<p(?: [^>]+)?>)*(<img [^>]+>)(?:<\/p>)*/g, function( match, image ) {
				var gridstack = getAttr( image, 'data-gridstack' );
				var posttype = getAttr( image, 'data-posttype' );
				var quantity = getAttr( image, 'data-quantity' );
				var taxonomy = getAttr( image, 'data-taxonomy' );
				var archivelink = getAttr( image, 'data-archivelink' );
				var term_ids = getAttr( image, 'data-term_ids' );

			

				var shortcode_str = '[' + sh_tag ;


					if (typeof gridstack != 'undefined' && gridstack.length)
						shortcode_str += ' gridstack="' + gridstack + '"';

					if (typeof posttype != 'undefined' && posttype.length)
						shortcode_str += ' posttype="' + posttype + '"';

					if (typeof quantity != 'undefined' && quantity.length)
						shortcode_str += ' quantity="' + quantity + '"';

					if (typeof taxonomy != 'undefined' && taxonomy.length)
						shortcode_str += ' taxonomy="' + taxonomy + '"';

					if (typeof archivelink != 'undefined' && archivelink.length)
						shortcode_str += ' archivelink="' + archivelink + '"';

					if (typeof term_ids != 'undefined' && term_ids.length)
						shortcode_str += ' term_ids="' + term_ids + '"';
					shortcode_str += ']';
				return shortcode_str;

				//return match;
			});
		}

		//add popup
		//// http://stackoverflow.com/questions/24871792/tinymce-api-v4-windowmanager-open-what-widgets-can-i-configure-for-the-body-op
		editor.addCommand('qt_gridstackshortcode_popup', function(ui, v) {
			//setup defaults
			var gridstack = '';
			if (v.gridstack)
				gridstack = v.gridstack;

			var quantity = '';
			if (v.quantity)
				quantity = v.quantity;

			var posttype = '';
			if (v.posttype)
				posttype = v.posttype;

			var taxonomy = '';
			if (v.taxonomy)
				taxonomy = v.taxonomy;

			var archivelink = '';
			if (v.archivelink)
				archivelink = v.archivelink;

			var term_ids = '';
			if (v.term_ids)
				term_ids = v.term_ids;
			

			editor.windowManager.open( {
				title: 'Gridstack editor',
				body: [

					
					{
						type: 'listbox',
						name: 'gridstack',
						label: 'Gridstack',
						value: gridstack,
						'values': [
						{ text : "Carousel" , value : "qt-carousel" },
						{ text : "Diamonds" , value : "qt-diamonds" },
						// { text : "Grid" , value : "qt-grid" },
						// { text : "List" , value : "qt-list" },
						{ text : "Owlcarousel" , value : "qt-owlcarousel" },
						{ text : "Owlcarousel-row" , value : "qt-owlcarousel-row" },
						{ text : "Skywheel" , value : "qt-skywheel" },
						{ text : "Slideshow" , value : "qt-slideshow" }
						],
						tooltip: 'Select the type of gridstack you want'
					},


					{
						type: 'listbox',
						name: 'posttype',
						label: 'Post-type',
						value: posttype,
						'values': qtposttypes,
						tooltip: 'Select the post type to use'
					},




					{
						type: 'listbox',
						name: 'quantity',
						label: 'Quantity',
						value: quantity,
						'values': [
							{ text : "1" , value : "1" },
							{ text : "2" , value : "2" },
							{ text : "3" , value : "3" },
							{ text : "4" , value : "4" },
							{ text : "5" , value : "5" },
							{ text : "6" , value : "6" },
							{ text : "7" , value : "7" },
							{ text : "8" , value : "8" },
							{ text : "9" , value : "9" },
							{ text : "10" , value : "10" },
							{ text : "11" , value : "11" },
							{ text : "12" , value : "12" },
							{ text : "16" , value : "16" },
							{ text : "18" , value : "18" }
						],
						tooltip: 'Keep low for performance.'
					},



					{
						type: 'textbox',
						name: 'taxonomy',
						label: 'Taxonomy',
						value: taxonomy,
						tooltip: 'Leave blank for none, or use taxonomy slug, like categories. Attention: each post type has its own taxonomies!'
					},

					{
						type: 'textbox',
						name: 'term_ids',
						label: 'Term IDs',
						value: term_ids,
						tooltip: 'Leave blank for none, or use comma separated numbers'
					},

					{
						type: 'listbox',
						name: 'archivelink',
						label: 'Link to archive',
						value: archivelink,
						'values': [
							{ text : "Hide" , value : "0" },
							{ text : "Show" , value : "1" }
						],
						tooltip: 'Display a link to the full archive. Not all the gridstacks supports this'
					}
				],
				onsubmit: function( e ) {
					var shortcode_str = '[' + sh_tag ;
					
					if (typeof e.data.gridstack != 'undefined' && e.data.gridstack.length)
						shortcode_str += ' gridstack="' + e.data.gridstack + '"';

					if (typeof e.data.posttype != 'undefined' && e.data.posttype.length)
						shortcode_str += ' posttype="' + e.data.posttype + '"';
					

					if (typeof e.data.quantity != 'undefined' && e.data.quantity.length)
						shortcode_str += ' quantity="' + e.data.quantity + '"';

					if (typeof e.data.taxonomy != 'undefined' && e.data.taxonomy.length)
						shortcode_str += ' taxonomy="' + e.data.taxonomy + '"';

					if (typeof e.data.archivelink != 'undefined' && e.data.archivelink.length)
						shortcode_str += ' archivelink="' + e.data.archivelink + '"';

					if (typeof e.data.term_ids != 'undefined' && e.data.term_ids.length)
						shortcode_str += ' term_ids="' + e.data.term_ids + '"';

					shortcode_str += ']';
					//insert shortcode to tinymce
					editor.insertContent( shortcode_str);
				}
			});
	    });

		//add button
		editor.addButton('qt_gridstackshortcode', {
			icon: 'qt_gridstackshortcode',
			tooltip: 'Gridstack Editor',
			text: ' QT Gridstack Easymode',
			onclick: function() {
				editor.execCommand('qt_gridstackshortcode_popup','',{
					gridstack : 'qt-carousel',
					posttype : 'post',
					quantity : '4',
					taxonomy : '',
					term_ids : '',
					archivelink : '0'
				});
			}
		});

		//replace from shortcode to an image placeholder
		editor.on('BeforeSetcontent', function(event){ 
			// console.log("BeforeSetcontent");
			event.content = replaceShortcodes( event.content );
		});

		//replace from image placeholder to shortcode
		editor.on('GetContent', function(event){
			event.content = restoreShortcodes(event.content);
		});

		//open popup on placeholder double click
		editor.on('DblClick',function(e) {
			var cls  = e.target.className.indexOf('wp-qt_gridstackshortcode');
			if ( e.target.nodeName == 'IMG' && e.target.className.indexOf('wp-qt_gridstackshortcode') > -1 ) {
				var gridstack =  e.target.attributes['data-gridstack'].value;
				var posttype =  e.target.attributes['data-posttype'].value;
				var quantity =  e.target.attributes['data-quantity'].value;
				var taxonomy =  e.target.attributes['data-taxonomy'].value;
				var archivelink =  e.target.attributes['data-archivelink'].value;
				var term_ids =  e.target.attributes['data-term_ids'].value;
				console.log(gridstack+' '+posttype+' '+quantity+' '+taxonomy+' archivelink:'+archivelink+' term_ids:'+term_ids);
				editor.execCommand('qt_gridstackshortcode_popup','',{
					gridstack : gridstack,
					posttype : posttype,
					quantity : quantity,
					taxonomy : taxonomy,
					term_ids: term_ids,
					archivelink : archivelink
				});
			}
		});
	});
})();