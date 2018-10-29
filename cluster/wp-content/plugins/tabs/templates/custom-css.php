<?php

/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 


		$html.= '<style type="text/css">';
		
		$html.= $tabs_custom_css;
		
		$html.= '
		
		#responsiveTabs-'.$post_id.'{
			
			padding:'.$tabs_container_padding.';
			margin:'.$tabs_container_margin.';			
			
			}		
		';
		
		
		

			
		$html.= '#responsiveTabs-'.$post_id.' ul.tabs-nav li.tabs-nav-items,
		#responsiveTabs-'.$post_id.'  .r-tabs-accordion-title{
			
			background: '.$tabs_default_bg_color.';
			padding: '.$tabs_items_title_padding.';
			margin: '.$tabs_items_title_margin.';					
			
			}';
		
		
		$html.= '#responsiveTabs-'.$post_id.' ul.tabs-nav li.r-tabs-state-active ,
		#responsiveTabs-'.$post_id.' div.r-tabs-state-active ,
		#responsiveTabs-'.$post_id.' .r-tabs-panel.r-tabs-state-active{
			
			background: '.$tabs_active_bg_color.';
			}';
			
			
		
		$html.= '#responsiveTabs-'.$post_id.' .r-tabs-panel{
			
			background: '.$tabs_active_bg_color.';
			padding: '.$tabs_items_content_padding.';
			margin: '.$tabs_items_content_margin.';			
			
			}		
		
		
		#responsiveTabs-'.$post_id.'  .r-tabs-accordion-title a{
			
			color:'.$tabs_items_title_color.';
			
			}
		
		
		
		
		
		</style>';	