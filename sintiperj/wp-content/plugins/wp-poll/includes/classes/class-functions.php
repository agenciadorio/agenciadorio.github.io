<?php

/*
* @Author 		Jaed Mosharraf
* Copyright: 	2015 Jaed Mosharraf
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

class WPP_Functions{
	
	public function wpp_poll_template_sections(){
		
		$template_sections = array(
			'wpp_poll_notice' => array(
				'label' => __('Poll Notice', WPP_TEXT_DOMAIN),
				'callable' => 'notice',
				'priority' => 90,
			),
			'wpp_poll_title' => array(
				'label' => __('Poll Title', WPP_TEXT_DOMAIN),
				'callable' => 'title',
				'priority' => 70,
			),
			'wpp_poll_message' => array(
				'label' => __('Message Section', WPP_TEXT_DOMAIN),
				'callable' => 'message',
				'priority' => 30,
			),
			'wpp_poll_thumb' => array(
				'label' => __('Poll Thumbnail', WPP_TEXT_DOMAIN),
				'callable' => 'thumb',
				'priority' => 50,
			),
			'wpp_poll_content' => array(
				'label' => __('Content', WPP_TEXT_DOMAIN),
				'callable' => 'content',
				'priority' => 30,
			),
			'wpp_poll_options' => array(
				'label' => __('Poll Options', WPP_TEXT_DOMAIN),
				'callable' => 'options',
				'priority' => 90,
			),
			'wpp_poll_results' => array(
				'label' => __('Results', WPP_TEXT_DOMAIN),
				'callable' => 'results',
				'priority' => 80,
			),
			'wpp_poll_buttons' => array(
				'label' => __('Buttons', WPP_TEXT_DOMAIN),
				'callable' => 'buttons',
				'priority' => 90,
			),
			'wpp_poll_comments' => array(
				'label' => __('Comments', WPP_TEXT_DOMAIN),
				'callable' => 'comments',
				'priority' => 30,
			),
			
		);
		
		return apply_filters( 'wpp_filters_poll_template_sections', $template_sections );
	}

		
} new WPP_Functions();