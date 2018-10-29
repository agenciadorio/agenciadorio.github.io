<?php

/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

		
	$tabs_bg_img = get_post_meta( $post_id, 'tabs_bg_img', true );
	$tabs_container_padding = get_post_meta( $post_id, 'tabs_container_padding', true );
	$tabs_container_margin = get_post_meta( $post_id, 'tabs_container_margin', true );	
	
	$tabs_themes = get_post_meta( $post_id, 'tabs_themes', true );

	$tabs_default_bg_color = get_post_meta( $post_id, 'tabs_default_bg_color', true );	
	$tabs_active_bg_color = get_post_meta( $post_id, 'tabs_active_bg_color', true );
	
	
	
	$tabs_items_title_color = get_post_meta( $post_id, 'tabs_items_title_color', true );	
	$tabs_items_title_font_size = get_post_meta( $post_id, 'tabs_items_title_font_size', true );
	$tabs_items_title_padding = get_post_meta( $post_id, 'tabs_items_title_padding', true );	
	$tabs_items_title_margin = get_post_meta( $post_id, 'tabs_items_title_margin', true );		
	
	
	$tabs_items_content_color = get_post_meta( $post_id, 'tabs_items_content_color', true );	
	$tabs_items_content_font_size = get_post_meta( $post_id, 'tabs_items_content_font_size', true );
	$tabs_items_content_padding = get_post_meta( $post_id, 'tabs_items_content_padding', true );
	$tabs_items_content_margin = get_post_meta( $post_id, 'tabs_items_content_margin', true );	
	
	$tabs_content_title = get_post_meta( $post_id, 'tabs_content_title', true );
	$tabs_content_title_icon = get_post_meta( $post_id, 'tabs_content_title_icon', true );
	
	$tabs_content_title_icon_custom = get_post_meta( $post_id, 'tabs_content_title_icon_custom', true );	
	$tabs_content_body = get_post_meta( $post_id, 'tabs_content_body', true );
	
	$tabs_active = get_post_meta( $post_id, 'tabs_active', true );	
 
	$tabs_items_collapsible = get_post_meta( $post_id, 'tabs_items_collapsible', true );		
	$tabs_items_animation = get_post_meta( $post_id, 'tabs_items_animation', true );
	
	$tabs_items_animation_duration = get_post_meta( $post_id, 'tabs_items_animation_duration', true );

	$tabs_custom_css = get_post_meta( $post_id, 'tabs_custom_css', true );
		
		
