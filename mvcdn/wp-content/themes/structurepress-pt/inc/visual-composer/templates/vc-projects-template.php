<?php

/*
 *  StructurePress Projects Template for Visual Composer
 */

add_action( 'vc_load_default_templates_action','structurepress_projects_template_for_vc' );

function structurepress_projects_template_for_vc() {
	$data               = array();
	$data['name']       = _x( 'StructurePress: Projects', 'backend' , 'structurepress-pt' );
	$data['image_path'] = preg_replace( '/\s/', '%20', get_template_directory_uri() . '/vendor/proteusthemes/visual-composer-elements/assets/images/pt.svg' );
	$data['custom_class'] = 'structurepress_projects_template_for_vc_custom_template';
	$data['content']    = <<<CONTENT
		[vc_row css=".vc_custom_1448364290177{margin-bottom: 0px !important;}"][vc_column width="1/1"][pt_vc_portfolio_grid title="All Projects" layout="grid" posts_per_page="-1" orderby="date" order="ASC" cta_text="Place reserved for your next project!" cta_btn="REQUEST A QUOTE" cta_link="http://www.proteusthemes.com/" add_cta="true"][/vc_column][/vc_row]
CONTENT;

	vc_add_default_templates( $data );
}