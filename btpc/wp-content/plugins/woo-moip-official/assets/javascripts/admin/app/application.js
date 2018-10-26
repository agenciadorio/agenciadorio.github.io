MONSTER( 'MOIP.Application', function(Model, $, utils) {

	var createNames = [
		// Name for instance method create() if not component
	];

	Model.init = function(container) {
		MOIP.BuildComponents.create( container );
		MOIP.BuildCreate.init( container, createNames );
	};

});