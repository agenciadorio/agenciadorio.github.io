<?php

/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

		$html.= '<ul  id="tabs-'.$post_id.'" class="tabs-nav">';
		
		if(!empty($tabs_content_title))
		foreach ($tabs_content_title as $index => $tabs_title){

			
			if(empty($tabs_content_title_icon_custom[$index]))
				{
					$icon_fa = $tabs_content_title_icon[$index];

					$icon = '<i class="fa fa-'.$icon_fa.'"></i>';
				}
			else
				{
					$icon_custom = $tabs_content_title_icon_custom[$index];
					$icon = '<span style="background-image:url('.$icon_custom.');width:'.$tabs_items_title_font_size.';height:'.$tabs_items_title_font_size.';" class="fa-custom"></span>';
				}
			
				$html.= '<li class="tabs-nav-items">';		
				$html.= '<a style="color:'.$tabs_items_title_color.';font-size:'.$tabs_items_title_font_size.'" href="#tab-'.$post_id.'-'.$index.'">'.$icon.$tabs_title.'</a>';	
				$html.= '</li>';
			}					
		$html.= '</ul>';