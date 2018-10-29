<?php

/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

		
	foreach ($tabs_content_body as $index => $tabs_content)
		{
			$html.= '<div style="color:'.$tabs_items_content_color.';font-size:'.$tabs_items_content_font_size.'" id="tab-'.$post_id.'-'.$index.'" class="tabs-content">';
			$html.= wpautop($tabs_content);		
			$html.= '</div>';
		
		}