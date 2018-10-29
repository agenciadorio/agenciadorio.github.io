<?php

/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins
*/

if ( ! defined('ABSPATH')) exit;  // if direct access

class class_tabs_settings{

    public function __construct(){

		add_action( 'admin_menu', array( $this, 'tabs_menu_init' ), 12 );

		}



	
	public function tabs_help(){
		include('menu/help.php');	
	}
	

	
	public	function tabs_menu_init(){

		add_submenu_page('edit.php?post_type=tabs', __('Help','tabs'), __('Help','tabs'), 'manage_options', 'tabs__help', array( $this, 'tabs_help' ));
		
		

		}


	}
	
new class_tabs_settings();