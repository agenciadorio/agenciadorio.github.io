// Admin Scripts for QantumThemes Functions
(function() {

    /**
     *
     *  Gallery shortcode
     *  @param {string} [button name] [description] value = qtgallery 
     *  IMPORTANT: In php:  array_push($buttons, "qtgallery");
     * 
     */
    tinymce.create("tinymce.plugins.gallery_button_plugin", {

        init : function(ed, url) {


            /**
             * 
             *
             *
             * Gallery
             * @qt_gallery_shortcodes [array created by PHP]
             */
            var my_options = qt_gallery_shortcodes;

            ed.addButton('qtgallery', {
                type: 'listbox',
                text: 'Choose a gallery',
                icon: false,
                tooltip: 'Insert QantumGallery',
                fixedWidth: false,
                values: my_options,
                onselect: function(e)
                {
                    var value = this.value();
                    var selection = ed.selection;
                    var rng = selection.getRng();
                    ed.focus();
                    var return_text = '[qtgallery id="'+value+'"]';
                    ed.execCommand("mceInsertContent", 0, return_text);  
                },
                
                onPostRender: function() 
                {
                    ed.my_control = this; // ui control element
                }
            });


            /**
             * 
             *
             *
             * qtGridstacks
             * @qt_gallery_shortcodes [array created by PHP]
             */

            var qt_gridstack_list = [
                {   text : "3D Carousel", value :       '[qt-carousel preview="true" stackid="" quantity="5" posttype="post" orderby="post_date" taxonomy="" term_ids="" arrows="true" vpadding="50px" time_constant="200" dist="-30" shift="0" padding="10"]' },
                {   text : "Diamonds", value :          '[qt-diamonds preview="true" stackid="" quantity="5" posttype="post" orderby="post_date" taxonomy="" term_ids="" archivelink="true"]' },
                // {   text : "Grid", value :              '[qt-grid preview="true" stackid="" quantity="4" posttype="post" orderby="post_date" taxonomy="" term_ids="" offset="0" taxonomy="" term_ids="" coll="3" colm="6" cols="12" titletag="" titletag="h3" showthumbnail="true" picturesize="" showtitle="true" showexcerpt="true" showmeta="true" showlink="true" textalign="left" masonry="0" card="true"]' },
                // {   text : "List", value :              '[qt-list preview="true" stackid="" quantity="4" posttype="post" orderby="post_date" taxonomy="" term_ids="" offset="0" taxonomy="" term_ids="" titletag="" titletag="h3" showthumbnail="true" showtitle="true" showexcerpt="true" showmeta="true" showlink="true" textalign="left"]' },
                {   text : "Owl Carousel", value :      '[qt-owlcarousel preview="true" stackid="" currentid="" quantity="12" posttype="post" type=""  items=4 orderby="post_date" taxonomy="category" containerid="" autoplay="false" autoplayTimeout="5000 nav="true" dots="true" navRewind="true" autoplayHoverPause="true" margin="30" loop="true" center="false" mouseDrag="true" touchDrag="true" pullDrag="true" freeDrag="false" stagepadding="0" startPosition="0"]' },
                {   text : "Owl Carousel Row", value :  '[qt-owlcarousel-row preview="true" stackid="" currentid="" quantity="12" posttype="post" type=""  items=4 orderby="post_date" taxonomy="category" containerid="" autoplay="false" autoplayTimeout="5000 nav="true" dots="false" navRewind="true" autoplayHoverPause="true" margin="30" loop="true" center="false" mouseDrag="true" touchDrag="true" pullDrag="true" freeDrag="false" stagepadding="0" startPosition="0"]' },
                {   text : "Skywheel", value :          '[qt-skywheel preview="true" stackid="" quantity="5" posttype="post" orderby="post_date" taxonomy="" term_ids="" archivelink="true" height="400px" width="100%"]' },
                {   text : "Slideshow", value :         '[qt-slideshow excerpt="" preview="true" stackid="" quantity="5" posttype="post" orderby="post_date" taxonomy="" term_ids="" transition="500" interval="6000" indicators="true" align="center" arrows="true" full_width="false" widescreen="false"]'}
            ];

            ed.addButton('qtGridstacks', {
                type: 'listbox',
                text: 'QT GridStack Full',
                icon: false,
                tooltip: 'Insert GridStack',
                fixedWidth: false,
                values: qt_gridstack_list,
                onselect: function(e)
                {
                    var value = this.value();
                    var selection = ed.selection;
                    var rng = selection.getRng();
                    ed.focus();
                    var return_text = value;
                    ed.execCommand("mceInsertContent", 0, return_text);  
                },
               
                onPostRender: function() 
                {
                    ed.my_control = this; // ui control element
                }
            });


            ed.addButton('qtIcons', {
                type: 'button',
                text: 'QT Icons',
                icon: ' wp-menu-image dashicons-before dashicons-welcome-view-site',
                tooltip: 'Add Icon',
                fixedWidth: false,
                onclick: function(e) {
                    ed.focus();
                    document.getElementById("qtaddicons").click();
                },
                onPostRender: function() {
                    ed.my_control = this; // ui control element
                }
            });


            ed.addButton('qtrelease', {
                type: 'button',
                text: 'QT Release',
                icon: ' wp-menu-image dashicons-before dashicons-format-audio',
                tooltip: 'Embed single release',
                fixedWidth: false,
                onclick: function(e) {
                    ed.focus();
                    ed.execCommand("mceInsertContent", 0, '[qtrelease id=""]');  
                },
                onPostRender: function() {
                    ed.my_control = this; // ui control element
                }
            });

        },

        createControl : function(n, cm) {
            return null;
        },

        getInfo : function() {
            return {
                longname : "Extra Buttons",
                author : "QantumThemes",
                version : "1"
            };
        }
    });

    tinymce.PluginManager.add("qt_shortcodes_plugin", tinymce.plugins.gallery_button_plugin);
})();
