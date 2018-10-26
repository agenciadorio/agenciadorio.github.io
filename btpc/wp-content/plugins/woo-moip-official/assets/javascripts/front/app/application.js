MONSTER( 'MOIP.Application', function(Model, $, utils) {

	var createNames = [
		'HandleCheckoutFields'
	];

	Model.init = function(container) {
		MOIP.BuildComponents.create( container );
		MOIP.BuildCreate.init( container, createNames );
		Model.onLoad(container);
		Model.setTabs();
		Model.copyBilletLinecode();
	};

	Model.setTabs = function() {
		$( '.woocommerce-tabs .panel' ).not( ':eq(0)' ).attr( 'style', 'display: none;' );
		$( '.woocommerce-tabs .panel:eq(0)' ).attr( 'style', 'display: block !important;' );

		$( '.woocommerce-tabs ul.tabs li a' ).on( 'click', function( e ) {
			e.preventDefault();

			var tab 		 = $( this )
			  , tabs_wrapper = tab.closest( '.woocommerce-tabs' )
			;

			$( 'ul.tabs li', tabs_wrapper).removeClass( 'active' );
			$( 'div.panel', tabs_wrapper).attr( 'style', 'display: none;' );
			$( 'div' + tab.attr( 'href' ) ).attr( 'style', 'display: block !important;' );
			tab.parent().addClass( 'active' );
		});
	};

	Model.copyBilletLinecode = function() {
	    var clipboard = new Clipboard( '#clipboard-linecode-btn' );

	    clipboard.on( 'success', function(e) {
	    	var element = $( e.trigger );

	        element.addClass( 'success' ).text( element.data( 'success-text' ) );
	    });

	    clipboard.on( 'error', function(e) {

	    });
	};

	Model.onLoad = function(container) {
		container.on('updated_checkout', function() {
			MOIP.BuildComponents.create(container.find('.payment_method_woo-moip-official'));
		});
	};
});