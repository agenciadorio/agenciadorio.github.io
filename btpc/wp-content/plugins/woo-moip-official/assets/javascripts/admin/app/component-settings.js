MONSTER( 'MOIP.Components.Settings', function(Model, $, utils) {

	var errorClass = utils.addPrefix( 'field-error' );

	Model.fn.start = function() {
		this.init();
	};

	Model.fn.init = function() {
		this.publicKey    = $( '[data-field="public-key"]' );
		this.installments = $( '[data-field="installments"]' );
		this.billet       = $( '[data-field="billet"]' );
		this.debit		  = $( '[data-field="debit"]' );
		this.bDiscount    = $( '[data-field="billet-discount"]' );
		this.eAccessToken = $( '[data-field="enable-accesstoken"]' );
		this.nAccessToken = $( '[data-field="number-accesstoken"]' );

		this.handleElementsVisibility( this.elements.checkout.val() );
		this.hideInstallments( $( '[data-action=installments-maximum]' ).val() );
		this.addEventListener();
		this.addAccessToken();
	};

	Model.fn.addEventListener = function() {
		this.on( 'keyup', 'invoice-name' );
		this.on( 'change', 'checkout-type' );
		this.on( 'change', 'installments-maximum' );

		$( '#oauth-app-btn' ).on( 'click', this._onClickOauthApp.bind( this ) );
		$( '#mainform' ).on( 'submit', this._onSubmitForm.bind( this ) );
	};

	Model.fn._onKeyupInvoiceName = function(event) {
		if ( event.currentTarget.value.length > 13 ) {
			$( event.currentTarget ).addClass( errorClass );
			return;
		}

		$( event.currentTarget ).removeClass( errorClass );
	};

	Model.fn._onSubmitForm = function(event) {
		this.toTop = false;
		this.items = [];

		this.elements.validate.each( this._eachValidate.bind( this ) );

		return !~this.items.indexOf( true );
	};

	Model.fn._onChangeCheckoutType = function(event) {
		this.handleElementsVisibility( event.currentTarget.value );
	};

	Model.fn._onChangeInstallmentsMaximum = function(event) {
		this.hideInstallments( event.currentTarget.value );
	};

	Model.fn.hideInstallments = function(max) {
		var installments = $( '[data-installment]' );

		installments.each(function(index, item) {
			var installment = $( item );
			if ( parseInt( item.dataset.installment ) > parseInt( max ) ) {
				installment.hide();
			} else {
				installments.show();
			}
		});
	};

	Model.fn._onClickOauthApp = function(event) {
		this.body.find( '#app-overlay' ).slideToggle();
	};

	Model.fn._eachValidate = function(index, field) {
		var rect;
		var element = $( field )
		  , empty   = element.isEmptyValue()
		  , func    = empty ? 'addClass' : 'removeClass'
		;

		if ( !element.is( ':visible' ) ) {
			return;
		}

		element[func]( errorClass );

		this.items[index] = empty;

		if ( !empty ) {
			return;
		}

		field.placeholder = field.dataset.errorMsg;

		if ( !this.toTop ) {
			this.toTop = true;
			rect       = field.getBoundingClientRect();
			window.scrollTo( 0, ( rect.top + window.scrollY ) - 40 );
		}
	};

	Model.fn.addAccessToken = function() {
		var nAccessToken  = this.nAccessToken.closest( 'tr');

		if ( this.eAccessToken.prop('checked') === true ) {
 			nAccessToken.show();
 		} else {
 			nAccessToken.hide();
 		}

		$( '#woocommerce_woo-moip-official_field_enable_acesstoken' ).on( 'click', this._onClickAccessToken.bind( this ) );
	}

	Model.fn._onClickAccessToken = function() {
		var nAccessToken  = this.nAccessToken.closest( 'tr');

 		if ( this.eAccessToken.prop('checked') === true ) {
 			nAccessToken.show();
 		} else {
 			nAccessToken.hide();
 		}
	}

	Model.fn.handleElementsVisibility = function(checkoutValue) {
		var publicKeyContainer 	    = this.publicKey.closest( 'tr' )
		  , installmentsContainer   = this.installments.closest( '.form-table' )
		  , installmentsTitle 	    = installmentsContainer.prev()
		  , billetContainer 	    = this.billet.closest( '.form-table' )
		  , billetTitle 		    = billetContainer.prev()
		  , debitContainer  	    = this.debit.closest( 'tr' )
		  , billetDiscountContainer = this.bDiscount.closest( '.form-table' )
		  , billetDiscountTitle     = billetDiscountContainer.prev()
		;

		if ( checkoutValue == 'default_checkout' ) {
			publicKeyContainer.show();
			installmentsContainer.show();
			installmentsTitle.show();
			billetContainer.show();
			billetTitle.show();
			debitContainer.hide();
			billetDiscountTitle.hide();
			billetDiscountContainer.hide();
		} 

		if ( checkoutValue == 'transparent_checkout' ) {
			publicKeyContainer.show();
			installmentsContainer.show();
			installmentsTitle.show();
			billetContainer.show();
			billetTitle.show();
			debitContainer.hide();
			billetDiscountTitle.show();
			billetDiscountContainer.show();
		}

		if ( checkoutValue == 'moip_checkout' ) {
			publicKeyContainer.hide();
			installmentsContainer.hide();
			installmentsTitle.hide();
			billetContainer.hide();
			billetTitle.hide();
			debitContainer.show();
			billetDiscountTitle.hide();
			billetDiscountContainer.hide();
		}

	};

});
