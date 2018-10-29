<?php

// Only require these files, if the Visual Composer plugin is activated
if ( defined( 'WPB_VC_VERSION' ) ) {

	// Require Visual Composer classes
	require_once get_template_directory() . '/vendor/proteusthemes/visual-composer-elements/vc-shortcodes/class-vc-shortcode.php';
	require_once get_template_directory() . '/vendor/proteusthemes/visual-composer-elements/vc-shortcodes/class-vc-custom-param-types.php';
	require_once get_template_directory() . '/vendor/proteusthemes/visual-composer-elements/vc-shortcodes/class-vc-helpers.php';

	// Require Visual Composer StructurePress front page template
	StructurePressHelpers::load_file( '/inc/visual-composer/templates/vc-home-page-template.php' );
	StructurePressHelpers::load_file( '/inc/visual-composer/templates/vc-our-services-template.php' );
	StructurePressHelpers::load_file( '/inc/visual-composer/templates/vc-about-us-template.php' );
	StructurePressHelpers::load_file( '/inc/visual-composer/templates/vc-projects-template.php' );
	StructurePressHelpers::load_file( '/inc/visual-composer/templates/vc-contact-us-template.php' );

	// Require custom VC elements for StructurePress theme
	StructurePressHelpers::load_file( '/inc/visual-composer/elements/vc-call-to-action.php' );
	StructurePressHelpers::load_file( '/inc/visual-composer/elements/vc-counter.php' );
	StructurePressHelpers::load_file( '/inc/visual-composer/elements/vc-portfolio-grid.php' );
	StructurePressHelpers::load_file( '/inc/visual-composer/elements/vc-contact-detail-item.php' );
	StructurePressHelpers::load_file( '/inc/visual-composer/elements/vc-container-contact-profile.php' );
	StructurePressHelpers::load_file( '/inc/visual-composer/elements/vc-open-position.php' );

	// Visual Composer shortcodes for the theme from the Visual Composer Elements (PHP Composer package)
	$structurepress_custom_vc_shortcodes = array(
		'brochure-box',
		'facebook',
		'featured-page',
		'icon-box',
		'latest-news',
		'skype',
		'opening-time',
		'social-icon',
		'container-social-icons',
		'testimonial',
		'container-testimonials',
		'container-number-counter',
		'accordion-item',
		'container-accordion',
		'step',
		'container-steps',
		'person-profile',
		// 'call-to-action',        -> VC element is not compatible with the widget used in StructurePress theme (because of subtitle field)
		// 'location',              -> using the VC Google map element instead
		// 'container-google-maps', -> using the VC Google map element instead
		// 'counter',               -> in StructurePress theme the counter is a bit different (no icon is used), so we created a custom counter
	);

	foreach ( $structurepress_custom_vc_shortcodes as $file ) {
		StructurePressHelpers::load_file( sprintf( '/vendor/proteusthemes/visual-composer-elements/vc-shortcodes/shortcodes/vc-%s.php', $file ) );
	}
}