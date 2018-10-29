<?php

if ( ! defined('ABSPATH')) exit;  // if direct access 

class class_pricingtable_functions{
	
	public function __construct(){
		
		
	}
	
	
	public function pricingtable_themes($themes = array()){

			$themes = array( 
							'flat'=>'Flat',
							'sonnet'=>'Sonnet',	
							'rounded'=>'Rounded',
											
							);
			
/*

			foreach(apply_filters( 'pricingtable_themes', $themes ) as $theme_key=> $theme_name){
				
					$theme_list[$theme_key] = $theme_name;
				}

*/

			$themes  = apply_filters('pricingtable_themes', $themes);


			
			return $themes;

	}
	
	
	
	
	
	
}

//new class_accordions_functions();