<?php

/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 


	if(empty($tabs_active)){$tabs_active = 0;}
	if(empty($tabs_items_collapsible)){$tabs_items_collapsible = 'true';}
	if(empty($tabs_items_animation)){$tabs_items_animation = 'slide';}
	if(empty($tabs_items_animation_duration)){$tabs_items_animation_duration = 500;}


		
	$html.= "<script type='text/javascript'>
	jQuery(document).ready(function ($) {
		$('#responsiveTabs-".$post_id."').responsiveTabs({
			collapsible: ".$tabs_items_collapsible.",
			animation: '".$tabs_items_animation."',
			duration: ".$tabs_items_animation_duration.",
			active:".$tabs_active.",			
			});
	
	
		});
	</script>";	