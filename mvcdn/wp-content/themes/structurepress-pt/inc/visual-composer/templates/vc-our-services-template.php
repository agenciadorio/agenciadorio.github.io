<?php

/*
 *  StructurePress Services Template for Visual Composer
 */

add_action( 'vc_load_default_templates_action','structurepress_services_template_for_vc' );

function structurepress_services_template_for_vc() {
	$data               = array();
	$data['name']       = _x( 'StructurePress: Services', 'backend' , 'structurepress-pt' );
	$data['image_path'] = preg_replace( '/\s/', '%20', get_template_directory_uri() . '/vendor/proteusthemes/visual-composer-elements/assets/images/pt.svg' );
	$data['custom_class'] = 'structurepress_services_template_for_vc_custom_template';
	$data['content']    = <<<CONTENT
		[vc_row full_width="" parallax="" parallax_image=""][vc_column width="1/3"][pt_vc_featured_page page="482" layout="block" read_more_text="Read more"][/vc_column][vc_column width="1/3"][pt_vc_featured_page page="479" layout="block" read_more_text="Read more"][/vc_column][vc_column width="1/3"][pt_vc_featured_page page="477" layout="block" read_more_text="Read more"][/vc_column][/vc_row][vc_row css=".vc_custom_1448284387187{margin-bottom: 0px !important;}"][vc_column width="1/3"][pt_vc_featured_page page="475" layout="block" read_more_text="Read more"][/vc_column][vc_column width="1/3"][pt_vc_featured_page page="473" layout="block" read_more_text="Read more"][/vc_column][vc_column width="1/3"][pt_vc_featured_page page="484" layout="block" read_more_text="Read more"][/vc_column][/vc_row]
CONTENT;

	vc_add_default_templates( $data );
}