<?php
/**
 *
 *	TiniMce Gridstack Easy Editor
 * 
 *	Adding the tinimce button for the easy gridstack editor
 * 	The shortcode is in the theme, so it works without the plugin
 * 	in includes/frontend/gridstacks/gridstacks.php
 *
 *
 * 
 */




class QT_qt_gridstackshortcode_shortcode{
	/**
	 * $shortcode_tag 
	 * holds the name of the shortcode tag
	 * @var string
	 */
	public $shortcode_tag = 'qt_gridstackshortcode';

	/**
	 * __construct 
	 * class constructor will set the needed filter and action hooks
	 * 
	 * @param array $args 
	 */
	function __construct($args = array()){
		//add shortcode
		
		if ( is_admin() ){
			add_action('admin_head', array( $this, 'admin_head') );
			add_action( 'admin_enqueue_scripts', array($this , 'admin_enqueue_scripts' ) );
		}
	}

	

	/**
	 * admin_head
	 * calls your functions into the correct filters
	 * @return void
	 */
	function admin_head() {
		// check user permissions
		if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
			return;
		}
		
		// check if WYSIWYG is enabled
		if ( 'true' == get_user_option( 'rich_editing' ) ) {
			add_filter( 'mce_external_plugins', array( $this ,'mce_external_plugins' ) );
			add_filter( 'mce_buttons', array($this, 'mce_buttons' ) );
		}
	}

	/**
	 * mce_external_plugins 
	 * Adds our tinymce plugin
	 * @param  array $plugin_array 
	 * @return array
	 */
	function mce_external_plugins( $plugin_array ) {
		$plugin_array[$this->shortcode_tag] = plugins_url( 'js/mce-button.js' , __FILE__ );
		return $plugin_array;
	}

	/**
	 * mce_buttons 
	 * Adds our tinymce button
	 * @param  array $buttons 
	 * @return array
	 */
	function mce_buttons( $buttons ) {
		array_push( $buttons, $this->shortcode_tag );
		return $buttons;
	}

	/**
	 * admin_enqueue_scripts 
	 * Used to enqueue custom styles
	 * @return void
	 */
	function admin_enqueue_scripts(){
		 wp_enqueue_style('qt_gridstackshortcode_shortcode', plugins_url( 'css/mce-button.css' , __FILE__ ) );
	}
}//end class

new QT_qt_gridstackshortcode_shortcode();





/**
 *
 *	Print the list of post types for the options which are in mce-buttons.js
 *
 * 
 */

add_action( 'edit_form_after_editor', 'qt_gridstack_edtor_types' );
if(!function_exists('qt_gridstack_edtor_types')){
function qt_gridstack_edtor_types() {
	?><script type="text/javascript">
	var qtposttypes = [
	<?php
	$args = array(
	   'public'   => true,
	   '_builtin' => false
	);
	$post_types = get_post_types( $args, 'names' ); 
	$post_types[] = 'post';
	$post_types[] = 'page';
	foreach ( $post_types as $post_type ) {
		?>{text: '<?php echo esc_attr($post_type); ?>', value: '<?php echo esc_attr($post_type); ?>'},<?php	   
	}
	?>
	]
	</script><?php  
}}








