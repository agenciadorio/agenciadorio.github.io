<?php

/*
 *  StructurePress Home Page Template for Visual Composer
 */

add_action( 'vc_load_default_templates_action','structurepress_home_page_template_for_vc' );

function structurepress_home_page_template_for_vc() {
	$data               = array();
	$data['name']       = _x( 'StructurePress: Home Page', 'backend' , 'structurepress-pt' );
	$data['image_path'] = preg_replace( '/\s/', '%20', get_template_directory_uri() . '/vendor/proteusthemes/visual-composer-elements/assets/images/pt.svg' );
	$data['custom_class'] = 'structurepress_home_page_template_for_vc_custom_template';
	$data['content']    = <<<CONTENT
		[vc_row css=".vc_custom_1448282056996{margin-bottom: 20px !important;}"][vc_column width="1/3"][pt_vc_featured_page page="479" layout="block" read_more_text="Read more"][/vc_column][vc_column width="1/3"][pt_vc_featured_page page="484" layout="block" read_more_text="Read more"][/vc_column][vc_column width="1/3"][pt_vc_featured_page page="477" layout="inline" read_more_text="Read more"][pt_vc_featured_page page="473" layout="inline" read_more_text="Read more"][pt_vc_featured_page page="475" layout="inline" read_more_text="Read more"][pt_vc_featured_page page="707" layout="inline" read_more_text="Read more"][/vc_column][/vc_row][vc_row css=".vc_custom_1448282006206{margin-bottom: 60px !important;padding-top: 30px !important;padding-bottom: 30px !important;background-color: #f2f2f2 !important;}" full_width="stretch_row"][vc_column width="1/1"][pt_vc_portfolio_grid title="All Projects" layout="slider" posts_per_page="-1" orderby="date" order="ASC" cta_text="Place reserved for your next project!" cta_btn="REQUEST A QUOTE" cta_link="http://www.proteusthemes.com/" add_cta="true"][/vc_column][/vc_row][vc_row css=".vc_custom_1448282360133{margin-bottom: 90px !important;}"][vc_column width="1/3"][pt_vc_latest_news layout="block" order_number="1" order_number_from="1" order_number_to="3" show_more_link=""][/vc_column][vc_column width="1/3"][pt_vc_latest_news layout="block" order_number="2" order_number_from="1" order_number_to="3" show_more_link=""][/vc_column][vc_column width="1/3"][pt_vc_latest_news layout="inline" order_number="1" order_number_from="3" order_number_to="5" show_more_link="true"][/vc_column][/vc_row][vc_row full_width="stretch_row" css=".vc_custom_1448282478767{margin-bottom: 0px !important;padding-top: 30px !important;padding-bottom: 30px !important;background-color: #f2f2f2 !important;}"][vc_column width="1/1"][pt_vc_call_to_action title="Do you need Professionals to project and build your dream home?" subtitle="We offer the best engineers and builders to make your dreams come true."]

	[button]CONTACT US[/button] [button style="secondary"]READ MORE[/button]

	[/pt_vc_call_to_action][/vc_column][/vc_row][vc_row full_width="stretch_row" css=".vc_custom_1448282573693{margin-bottom: 0px !important;padding-top: 90px !important;padding-bottom: 90px !important;background-image: url(http://xml-io.proteusthemes.com/structurepress/wp-content/uploads/sites/28/2015/10/promise_values.jpg) !important;}"][vc_column width="1/6"][vc_column_text]
	[/vc_column_text][/vc_column][vc_column width="2/3"][vc_column_text]
	<h3 style="text-align: center; font-size: 2.5rem; margin-bottom: 1.5rem;">Our Promise and Values</h3>
	<p style="text-align: center;"><span style="color: #333333;">We aim to eliminate the task of dividing your project between different architecture and construction company. We are a company that offers design and build services for you from initial sketches to the final construction example.</span></p>
	<p style="text-align: center; margin-bottom: 0;"><img class="alignnone size-full wp-image-283" style="margin-bottom: 0;" src="http://xml-io.proteusthemes.com/structurepress/wp-content/uploads/sites/28/2015/10/signature.png" alt="signature" width="232" height="58" /></p>

	[/vc_column_text][/vc_column][vc_column width="1/6"][vc_column_text]
	[/vc_column_text][/vc_column][/vc_row][vc_row full_width="stretch_row" css=".vc_custom_1448282784540{margin-bottom: 80px !important;padding-top: 80px !important;padding-bottom: 80px !important;background-color: #f2f2f2 !important;}"][vc_column width="1/1"][pt_vc_container_testimonials title="Testimonials" autocycle="no" interval="5000"][pt_vc_testimonial quote="The StructurePress team was very sufficient in maintaining the integrity of this project in terms of planning, scheduling, cost and quality, and their team's ability to work in person with owners, architects, designers and planner makes them a leader in their field." author="Alan Owens" author_description="OWN Inc." author_avatar="627"][pt_vc_testimonial quote="StructurePress company has performed in a consistent, demanding and professional manner. They have got my project on time with the competition with a highly skilled, well-organized and experienced team of professional construction managers. Our company is looking forward to hire them again." author="Rebecca Watson" author_description="R. W. Construction Inc." author_avatar="631"][pt_vc_testimonial quote="As the architect for major projects I really like to collaborte with StructurePress company. We are particularly organized through the construction process. My own project was a great example of contractors and architects working as a team for the good of the project and its users." author="John Weller" author_description="Welling Design &amp; Engineering" author_avatar="628"][/pt_vc_container_testimonials][/vc_column][/vc_row][vc_row css=".vc_custom_1448283308591{margin-bottom: 85px !important;}"][vc_column width="1/1"][vc_column_text]
	<h3 class="widget-title"><span class="widget-title__inline">Partners</span></h3>
	<div class="logo-panel">
	<div class="row">
	<div class="col-xs-12 col-sm-2"><img src="http://xml-io.proteusthemes.com/structurepress/wp-content/uploads/sites/28/2015/11/client_01.jpg" alt="Client logo" /></div>
	<div class="col-xs-12 col-sm-2"><img src="http://xml-io.proteusthemes.com/structurepress/wp-content/uploads/sites/28/2015/11/client_02.jpg" alt="Client logo" /></div>
	<div class="col-xs-12 col-sm-2"><img src="http://xml-io.proteusthemes.com/structurepress/wp-content/uploads/sites/28/2015/11/client_03.jpg" alt="Client logo" /></div>
	<div class="col-xs-12 col-sm-2"><img src="http://xml-io.proteusthemes.com/structurepress/wp-content/uploads/sites/28/2015/11/client_04.jpg" alt="Client logo" /></div>
	<div class="col-xs-12 col-sm-2"><img src="http://xml-io.proteusthemes.com/structurepress/wp-content/uploads/sites/28/2015/11/client_05.jpg" alt="Client logo" /></div>
	<div class="col-xs-12 col-sm-2"><img src="http://xml-io.proteusthemes.com/structurepress/wp-content/uploads/sites/28/2015/11/client_06.jpg" alt="Client logo" /></div>
	</div>
	</div>
	[/vc_column_text][/vc_column][/vc_row]
CONTENT;

	vc_add_default_templates( $data );
}