<?php

/*
 *  StructurePress Contact Template for Visual Composer
 */

add_action( 'vc_load_default_templates_action','structurepress_contact_template_for_vc' );

function structurepress_contact_template_for_vc() {
	$data               = array();
	$data['name']       = _x( 'StructurePress: Contact', 'backend' , 'structurepress-pt' );
	$data['image_path'] = preg_replace( '/\s/', '%20', get_template_directory_uri() . '/vendor/proteusthemes/visual-composer-elements/assets/images/pt.svg' );
	$data['custom_class'] = 'structurepress_contact_template_for_vc_custom_template';
	$data['content']    = <<<CONTENT
		[vc_row full_width="stretch_row_content_no_spaces" css=".vc_custom_1448366155918{margin-top: -63px !important;margin-bottom: 60px !important;}"][vc_column width="1/1"][vc_gmaps link="#E-8_JTNDaWZyYW1lJTIwc3JjJTNEJTIyaHR0cHMlM0ElMkYlMkZ3d3cuZ29vZ2xlLmNvbSUyRm1hcHMlMkZlbWJlZCUzRnBiJTNEJTIxMW0xOCUyMTFtMTIlMjExbTMlMjExZDc4ODA0LjI2NTkzODgzOTIlMjEyZDE0LjQ2NTQ4NDc5MzMyNjI4MSUyMTNkNDYuMDUwOTY1OTc0OTUxNjE2JTIxMm0zJTIxMWYwJTIxMmYwJTIxM2YwJTIxM20yJTIxMWkxMDI0JTIxMmk3NjglMjE0ZjEzLjElMjEzbTMlMjExbTIlMjExczB4NDc2NTMyOWI4NzRhYzE1YiUyNTNBMHhjZGFkYjZkYjJmMzdjZTAzJTIxMnNManVibGphbmElMjE1ZTAlMjEzbTIlMjExc2VuJTIxMnNzaSUyMTR2MTQ0ODM2NTAwNDI2MyUyMiUyMHdpZHRoJTNEJTIyNjAwJTIyJTIwaGVpZ2h0JTNEJTIyNDUwJTIyJTIwZnJhbWVib3JkZXIlM0QlMjIwJTIyJTIwc3R5bGUlM0QlMjJib3JkZXIlM0EwJTIyJTIwYWxsb3dmdWxsc2NyZWVuJTNFJTNDJTJGaWZyYW1lJTNF" size="480"][/vc_column][/vc_row][vc_row css=".vc_custom_1448366201836{margin-bottom: 65px !important;}"][vc_column width="1/3"][pt_vc_container_contact_profile image="http://xml-io.proteusthemes.com/structurepress/wp-content/uploads/sites/28/2015/10/contact-logo.png" social_icons="https://www.facebook.com/ProteusThemes|fa-facebook-square
	https://twitter.com/ProteusThemes|fa-twitter-square
	https://www.youtube.com/user/ProteusNetCompany|fa-youtube-square
	https://github.com/ProteusThemes|fa-github-square" new_tab=""][pt_vc_contact_detail_item icon="fa fa-map-marker" text="227 Marion Street Avenue
	Columbia SC 29201
	United Kingdom"][pt_vc_contact_detail_item icon="fa fa-phone" text="1-888-123-4567"][pt_vc_contact_detail_item icon="fa fa-envelope" text="info@structure.com"][pt_vc_contact_detail_item icon="fa fa-compass" text="www.structure.com"][/pt_vc_container_contact_profile][/vc_column][vc_column width="2/3"][vc_column_text]

	[contact-form-7 id="811" title="Contact Us"]

	[/vc_column_text][/vc_column][/vc_row][vc_row css=".vc_custom_1448366168512{margin-bottom: 0px !important;}"][vc_column width="1/4"][pt_vc_container_contact_profile name="Tokyo Branch" image="http://xml-io.proteusthemes.com/structurepress/wp-content/uploads/sites/28/2015/11/14.jpg" new_tab=""][pt_vc_contact_detail_item icon="fa fa-map-marker" text="227 Marion Street Avenue
	Columbia SC 29201
	United Kingdom"][pt_vc_contact_detail_item icon="fa fa-phone" text="1-888-123-4567"][pt_vc_contact_detail_item icon="fa fa-envelope" text="info@structure.com"][/pt_vc_container_contact_profile][/vc_column][vc_column width="1/4"][pt_vc_container_contact_profile name="London Branch" image="http://xml-io.proteusthemes.com/structurepress/wp-content/uploads/sites/28/2015/11/68.jpg" new_tab=""][pt_vc_contact_detail_item icon="fa fa-map-marker" text="227 Marion Street Avenue
	Columbia SC 29201
	United Kingdom"][pt_vc_contact_detail_item icon="fa fa-phone" text="1-888-123-4567"][pt_vc_contact_detail_item icon="fa fa-envelope" text="info@structure.com"][/pt_vc_container_contact_profile][/vc_column][vc_column width="1/4"][pt_vc_container_contact_profile name="Paris Branch" image="http://xml-io.proteusthemes.com/structurepress/wp-content/uploads/sites/28/2015/11/62.jpg" new_tab=""][pt_vc_contact_detail_item icon="fa fa-map-marker" text="227 Marion Street Avenue
	Columbia SC 29201
	United Kingdom"][pt_vc_contact_detail_item icon="fa fa-phone" text="1-888-123-4567"][pt_vc_contact_detail_item icon="fa fa-envelope" text="info@structure.com"][/pt_vc_container_contact_profile][/vc_column][vc_column width="1/4"][pt_vc_opening_time days_hours="opened|8:00|16:00
	opened|11:00|19:00
	opened|8:00|16:00
	closed
	opened|11:00|19:00
	closed
	closed" separator=" - " closed="CLOSED" title="Opening time"][/vc_column][/vc_row]
CONTENT;

	vc_add_default_templates( $data );
}