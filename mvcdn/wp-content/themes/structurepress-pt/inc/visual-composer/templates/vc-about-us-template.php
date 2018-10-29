<?php

/*
 *  StructurePress About Template for Visual Composer
 */

add_action( 'vc_load_default_templates_action','structurepress_about_template_for_vc' );

function structurepress_about_template_for_vc() {
	$data               = array();
	$data['name']       = _x( 'StructurePress: About', 'backend' , 'structurepress-pt' );
	$data['image_path'] = preg_replace( '/\s/', '%20', get_template_directory_uri() . '/vendor/proteusthemes/visual-composer-elements/assets/images/pt.svg' );
	$data['custom_class'] = 'structurepress_about_template_for_vc_custom_template';
	$data['content']    = <<<CONTENT
		[vc_row css=".vc_custom_1448360382560{margin-bottom: 85px !important;}"][vc_column width="1/4"][vc_column_text]
	<h4><a href="http://xml-io.proteusthemes.com/structurepress/wp-content/uploads/sites/28/2015/11/64.jpg"><img class="alignnone size-medium wp-image-153" src="http://xml-io.proteusthemes.com/structurepress/wp-content/uploads/sites/28/2015/11/64-300x150.jpg" alt="64" width="300" height="150" />
	</a>Quality</h4>
	<p style="text-align: left; font-size: 14px;">Our aim is to continuously exceed the expectations of our client to deliver quality construction. Our team members verify all features of work.</p>

	[/vc_column_text][vc_column_text]
	<h4><a href="http://xml-io.proteusthemes.com/structurepress/wp-content/uploads/sites/28/2015/11/9.jpg"><img class="alignnone size-medium wp-image-207" src="http://xml-io.proteusthemes.com/structurepress/wp-content/uploads/sites/28/2015/11/9-300x150.jpg" alt="9" width="300" height="150" />
	</a>Safety</h4>
	<p style="text-align: left; font-size: 14px;">Proactive safety planning helps us provide a safe working environment for everyone working on the project, people visiting the job site or working.</p>

	[/vc_column_text][/vc_column][vc_column width="1/4"][vc_column_text]
	<h4><a href="http://xml-io.proteusthemes.com/structurepress/wp-content/uploads/sites/28/2015/11/22.jpg"><img class="alignnone size-medium wp-image-192" src="http://xml-io.proteusthemes.com/structurepress/wp-content/uploads/sites/28/2015/11/22-300x150.jpg" alt="22" width="300" height="150" />
	</a>Integrity</h4>
	<p style="text-align: left; font-size: 14px;">Our partnership with our clients is based on mutual trust and we do what is best for our clients following our company’s values and methods.</p>

	[/vc_column_text][vc_column_text]
	<h4><a href="http://xml-io.proteusthemes.com/structurepress/wp-content/uploads/sites/28/2015/11/33.jpg"><img class="alignnone size-medium wp-image-181" src="http://xml-io.proteusthemes.com/structurepress/wp-content/uploads/sites/28/2015/11/33-300x150.jpg" alt="33" width="300" height="150" />
	</a>Teamwork</h4>
	<p style="text-align: left; font-size: 14px;">To become an industry leader, it is important for us to encourage team work in order to solve any construction challenges and to achieve results.</p>

	[/vc_column_text][/vc_column][vc_column width="2/4"][vc_single_image image="190" alignment="center" border_color="grey" img_link_large="" img_link_target="_self" img_size="full" css=".vc_custom_1448359996320{margin-top: 5px !important;}"][vc_single_image image="197" alignment="center" border_color="grey" img_link_large="" img_link_target="_self" img_size="full"][/vc_column][/vc_row][vc_row css=".vc_custom_1448360861233{margin-bottom: 60px !important;}"][vc_column width="1/4"][pt_vc_person_profile name="Alex Schultz" title="CEO" image_url="http://xml-io.proteusthemes.com/structurepress/wp-content/uploads/sites/28/2015/11/310.jpg" introduction="He started out as a small contractor, undertaking and construction of small projects. In mid 30s and led the company from the front." social_links="https://www.facebook.com/ProteusThemes|fa-facebook-square
	https://twitter.com/proteusthemes|fa-twitter-square
	https://www.youtube.com/user/ProteusNetCompany|fa-youtube-square
	https://github.com/ProteusThemes|fa-github-square" new_tab="true"][/vc_column][vc_column width="1/4"][pt_vc_person_profile name="Gregory Schajz" title="Project Manager" image_url="http://xml-io.proteusthemes.com/structurepress/wp-content/uploads/sites/28/2015/11/410.jpg" introduction="The company is a symbol of his values, ideas and integrity that he has managed to bring to the company as well as the construction industry." social_links="https://www.facebook.com/ProteusThemes|fa-facebook-square
	https://twitter.com/proteusthemes|fa-twitter-square
	https://www.youtube.com/user/ProteusNetCompany|fa-youtube-square
	https://github.com/ProteusThemes|fa-github-square" new_tab="true"][/vc_column][vc_column width="1/4"][pt_vc_person_profile name="Bryan Bell" title="Main Assistent" image_url="http://xml-io.proteusthemes.com/structurepress/wp-content/uploads/sites/28/2015/11/1-1.jpg" introduction="Bryan also believed in giving back to the community and has worked tirelessly to help the needy. He has undertaken multiple." social_links="https://www.facebook.com/ProteusThemes|fa-facebook-square
	https://twitter.com/proteusthemes|fa-twitter-square
	https://www.youtube.com/user/ProteusNetCompany|fa-youtube-square
	https://github.com/ProteusThemes|fa-github-square" new_tab="true"][/vc_column][vc_column width="1/4"][pt_vc_person_profile name="Alen Howell" title="Engineer" image_url="http://xml-io.proteusthemes.com/structurepress/wp-content/uploads/sites/28/2015/11/210.jpg" introduction="Alen’s vision to transform the construction experience by building smart buildings is in our roots. Irrespective of the size of the project." social_links="https://www.facebook.com/ProteusThemes|fa-facebook-square
	https://twitter.com/proteusthemes|fa-twitter-square
	https://www.youtube.com/user/ProteusNetCompany|fa-youtube-square
	https://github.com/ProteusThemes|fa-github-square" new_tab="true"][/vc_column][/vc_row][vc_row full_width="" parallax="" parallax_image=""][vc_column width="1/1"][vc_column_text]
	<h2>Open Positions</h2>
	[/vc_column_text][/vc_column][/vc_row][vc_row full_width="" parallax="" parallax_image=""][vc_column width="1/1"][pt_vc_open_position title="Architect" date="20 Nov 2015" details_title="Contact Details" detail_items="S.E.M. International|fa-university
	info@structure.com|fa-envelope
	1-888-123-4567|fa-phone"]<p>Another effective tip to control construction dust is to hang plastic drop cloths known as zipwalls around the area of renovation. But excess of movement should be avoided as it allows the dust to potentially escape and settlement. Little preparation will land you with a much smoother, better and convenient house built perfect and completed on time.</p>
	<!--more-->
	<p>The first decision is whether you would reside in the same house during renovation or temporarily shift out. If renovation is just for one place like bathroom or kitchen, its possible to remain staying there with a few adjustments. But if the entire house needs a renovation, it could stretch to a few months and its best to shift to any temporary abode like a rented apartment or a hotel. While remodeling, opt for materials with mass appeal like stainless steel appliances of high quality rather than the professional-grades ones.</p>[/pt_vc_open_position][/vc_column][/vc_row][vc_row full_width="" parallax="" parallax_image=""][vc_column width="1/1"][pt_vc_open_position title="Project Leader" date="20 Oct 2015" details_title="Contact Details" detail_items="S.E.M. International|fa-university
	info@structure.com|fa-envelope
	1-888-123-4567|fa-phone"]<p>While remodeling, opt for materials with mass appeal like stainless steel appliances of high quality rather than the professional-grades ones. Another effective tip to control construction dust is to hang plastic drop cloths known as zipwalls around the area of renovation. But excess of movement should be avoided as it allows the dust to potentially escape and settlement. Little preparation will land you with a much smoother, better and convenient house built perfect and completed on time. While remodeling, opt for materials with mass appeal like stainless steel appliances of high quality rather than the professional-grades ones.</p>
	<!--more-->
	<p>Little preparation will land you with a much smoother, better and convenient house built perfect and completed on time. The first decision is whether you would reside in the same house during renovation or temporarily shift out. If renovation is just for one place like bathroom or kitchen, its possible to remain staying there with a few adjustments. But if the entire house needs a renovation, it could stretch to a few months and its best to shift to any temporary abode like a rented apartment or a hotel. While remodeling, opt for materials with mass appeal like stainless steel appliances of high quality rather than the professional-grades ones.</p>[/pt_vc_open_position][/vc_column][/vc_row]
CONTENT;

	vc_add_default_templates( $data );
}