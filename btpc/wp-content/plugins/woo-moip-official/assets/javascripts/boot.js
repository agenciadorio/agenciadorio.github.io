jQuery(function($) {
	var context = $( 'body' );

	MOIP.vars = {
		body : context
	};

	MOIP.Application.init.apply( null, [context] );
});