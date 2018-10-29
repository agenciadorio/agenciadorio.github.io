/**
 * Live-update changed settings in real time in the Customizer preview.
 */

( function( $ ) {
	var api = wp.customize;

	// Site title.
	api( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.wpsm-logo a' ).text( to );
		} );
	} );

	// Menu wrap width.
	api( 'wpsm_wrap_width', function( value ) {
		value.bind( function( to ) {
			$( '.wp-sticky-menu-wrap' ).css( 'max-width', to + 'px' );
		} );
	} );

	// Background color.
	api( 'wpsm_background', function( value ) {
		value.bind( function( to ) {
			$( '.wp-sticky-menu, .wpsm-navigation ul ul li' ).css( 'background', to );
		} );
	} );

	// Menu color.
	api( 'wpsm_font_color', function( value ) {
		value.bind( function( to ) {
			$( '.wpsm-navigation a, .wpsm-menu-toggle' ).css( {
				'color': to 
			} );
		} );
	} );

	// Log image.
	api( 'wpsm_logo', function( value ) {
		value.bind( function( to ) {
			if ( to ) {
				$( '.wpsm-logo' ).css( {
					'background': 'url("'+to+'") no-repeat 0 0',
					'background-size': 'contain',
					'margin': '0'
				} );

				$( '.wpsm-logo a' ).css( {
					'text-indent': '-9999px'
				} );
			} else {
				$( '.wpsm-logo' ).css( {
					'background': 'none',
				} );
				$( '.wpsm-logo a' ).css( {
					'text-indent': '0'
				} );
			}
		} );
	} );

} )( jQuery );