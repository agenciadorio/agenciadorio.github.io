<?php
/**
 *
 *	The shortcodes tinymce buttons
 *
 * 
 */

// from https://generatewp.com/take-shortcodes-ultimate-level/
// https://github.com/bainternet/bs3_panel_shortcode

// require plugin_dir_path( __FILE__ ) . '/gridstackeditor/qt_gridstacks_shortcode.php';

/**
 *
 *	Adding the shortcode to the PHP
 *
 * 
 */
if(!function_exists('qt_enqueue_plugin_scripts_tinymce')){
function qt_enqueue_plugin_scripts_tinymce($plugin_array)
{
    ?>
    <script type="text/javascript">
    		var qt_gallery_shortcodes = [
		    	<?php 
		    	$posts = get_posts( array( 'post_type' => 'mediagallery', 'posts_per_page' => -1 ,'suppress_filters' => false ) );
		    	$r = '';
				foreach ( $posts as $item ) {
					$r .= '{ text : "'.esc_attr($item->post_title).'" , value : "'.esc_attr($item->ID).'" }' ;
					if(!(end($posts) == $item) ){
						$r .= ',';
					}
				}
				echo $r;
		    	?> 
	    	];
	</script>
    <?php
    $plugin_array["qt_shortcodes_plugin"] =  plugins_url( '' , __FILE__ )  . '/assets/min/qt-js-tinymce-min.js';
    return $plugin_array;
}}

add_filter("mce_external_plugins", "qt_enqueue_plugin_scripts_tinymce");


/**
 *
 *	Adding the button to the editor
 *
 * 
 */
if(!function_exists('qt_register_buttons_editor')){
	function qt_register_buttons_editor($buttons)
	{
	    //register buttons with their id.
	    array_push($buttons, "qtgallery");
	    array_push($buttons, "qtGridstacks");
	    array_push($buttons, "qtIcons");
	    array_push($buttons, "qtrelease");
	    return $buttons;
	}
	add_filter("mce_buttons", "qt_register_buttons_editor");
}