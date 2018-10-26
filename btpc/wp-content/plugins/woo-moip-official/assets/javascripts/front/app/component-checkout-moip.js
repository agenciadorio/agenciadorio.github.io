MONSTER( 'MOIP.Components.CheckoutMoip', function(Model, $, utils) {

	Model.fn.start = function() {
		this.init();
	};

	Model.fn.init = function() {
		this.addEventListener();
	};

	Model.fn.addEventListener = function() {
		this.click( 'payment-link' );
	};

	Model.fn._onClickPaymentLink = function(event) {
		this.elements.tabs.html( utils.getSpinner() );

		window.open( event.currentTarget.href, '_blank' );

		this.ajax({
			data : {
				action      : 'RmSLgKecpN',
				security    : this.data.nonce,
				returnUrl   : this.data.returnUrl,
				paymentType : event.currentTarget.dataset.paymentType,
				order       : this.data.order
			}
		});

		event.preventDefault();
	};

	Model.fn._done = function(response) {
		if ( !response.success ) {
			return;
		}

		window.location.href = response.data.redirectUrl;
	};

	Model.fn._fail = function(jqXHR, textStatus, errorThrown) {

	};

});