/* global*/

// config
require.config( {
	paths: {
		jquery:     'assets/js/fix.jquery',
		underscore: 'assets/js/fix.underscore',
		util:       'bower_components/bootstrap/dist/js/umd/util',
		alert:      'bower_components/bootstrap/dist/js/umd/alert',
		button:     'bower_components/bootstrap/dist/js/umd/button',
		carousel:   'bower_components/bootstrap/dist/js/umd/carousel',
		collapse:   'bower_components/bootstrap/dist/js/umd/collapse',
		dropdown:   'bower_components/bootstrap/dist/js/umd/dropdown',
		modal:      'bower_components/bootstrap/dist/js/umd/modal',
		scrollspy:  'bower_components/bootstrap/dist/js/umd/scrollspy',
		tab:        'bower_components/bootstrap/dist/js/umd/tab',
		tooltip:    'bower_components/bootstrap/dist/js/umd/tooltip',
		popover:    'bower_components/bootstrap/dist/js/umd/popover',
		stampit:    'assets/js/vendor/stampit',
	},
} );

require.config( {
	baseUrl: StructurePressVars.pathToTheme
} );

require( [
		'jquery',
		'underscore',
		'assets/js/portfolio-grid-filter/gridFilter',
		'assets/js/portfolio-grid-filter/sliderFilter',
		'assets/js/utils/isElementInView',
		'assets/js/utils/easeInOutQuad',
		'vendor/proteusthemes/proteuswidgets/assets/js/NumberCounter',
		'assets/js/StickyNavbar',
		'assets/js/TouchDropdown',
		'carousel',
		'collapse',
], function ( $, _, gridFilter, sliderFilter, isElementInView, easeInOutQuad, NumberCounter ) {
	'use strict';


	/**
	 * Footer widgets fix
	 */
	$( '.col-md-__col-num__' ).removeClass( 'col-md-__col-num__' ).addClass( 'col-md-3' );


	/**
	 * Number Counter Widget JS code
	 */
	// Get all number counter widgets
	var $counterWidgets = $( '.number-counters' );

	if ( $counterWidgets.length ) {

		// jQuery easing function: easeInOutQuad, for use in NumberCounter
		easeInOutQuad();

		$counterWidgets.each( function () {
			new NumberCounter( $( this ) );
		} );
	}

	/**
	 * Portfolio grid filtering
	 */
	$('.portfolio-grid').each(function () {
		var hash = window.location.hash,
			portfolioGrid;

		if ('slider' === $(this).data('type')) {
			portfolioGrid = sliderFilter({
				$container: $(this),
			});
		}
		else {
			portfolioGrid = gridFilter({
				$container: $(this),
			});
		}

		// Getting on visit or if "All" nav button is disabled.
		if ( new RegExp('^#' + portfolioGrid.hashPrefix).test(hash) ) {
			$(this).find('a[href="' + hash.replace(portfolioGrid.hashPrefix, '') + '"]').trigger('click');
		}
		else if ( $(this).find('.portfolio-grid__nav-item').first().hasClass('is-disabled') ) {

			// Trigger click for the second nav grid item.
			$(this).find('.portfolio-grid__nav-item:nth-child(2)').children('.portfolio-grid__nav-link').trigger('click');
		}

		// Recalculate the mobile nav height, if the "All" nav button is disabled. Fix for both cases above.
		if ( ! portfolioGrid.isDesktopLayout() && $(this).find('.portfolio-grid__nav-item').first().hasClass('is-disabled') ) {
			portfolioGrid.initNavHolderHeight();
		}
	});

	/**
	 * Pause the carousel if it's not visible
	 */
	(function () {
		var $slider = $('.js-jumbotron-slider');

		if ($slider.length) {
			$(document).on('scroll', _.throttle(function () {
				if (isElementInView($slider)) {
					$slider.carousel('cycle');
				}
				else {
					$slider.carousel('pause');
				}
			}, 1000, {leading: false}));
		}
	}());

} );