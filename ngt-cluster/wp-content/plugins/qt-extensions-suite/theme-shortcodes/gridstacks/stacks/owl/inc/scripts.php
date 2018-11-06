<?php  

/* = Styles and scripts
=============================================*/

if(!function_exists('qt_gridstack_files_inclusion')){
function qt_gridstack_files_inclusion() {
	/**
	 * [$ver CSS version. Change with theme version in production.]
	 * @var [progressive number]
	 */
	$ver = '1.0.0';
	wp_enqueue_style( 'qt-gristack-owl'
		, GRIDSTACK_URL . 'stacks/owl/assets/assets/owl.carousel.min.css'
		, ""
		, $ver
		, "all"); 
	/*wp_enqueue_style( 'qt-gristack-owl-theme'
		, GRIDSTACK_URL . 'stacks/owl/assets/assets/owl.theme.default.css'
		, $deps = array('qt-gristack-owl')
		, $ver
		, "all");*/
	
	
	wp_enqueue_script( 'jquery');

	wp_enqueue_script( 'qt-gristack-owl-script'
		, GRIDSTACK_URL .  'stacks/owl/assets/owl.carousel.min.js'
		, array('jquery'), $ver, true );

	/**
	 *
	 *
	 *
	 *	Initialization already included in main.js
	 * 
	 */
	/*
		wp_enqueue_script( 'qt-init-gristack-owl-script'
			,	GRIDSTACK_URL .  'stacks/owl/assets/qantumthemes.gridstack.owl.js'
			, array('jquery', 'qt-gristack-owl-script'), $ver, true );
	*/
	
}}
add_action( 'wp_enqueue_scripts', 'qt_gridstack_files_inclusion' );