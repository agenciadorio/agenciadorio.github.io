MONSTER( 'MOIP.Components.CheckoutTransparent', function(Model, $, utils) {

	var CLASS_HIDE = 'wc-moip-hide-field';

	Model.fn.start = function() {
		this.init();
	};

	Model.fn.init = function() {
		this.setCreditCard();
		this.addEventListener();
		this.checkStoredCC();

		this.messages = MOIPGlobalVars.messages;
	};

	Model.fn.addEventListener = function() {
		this.click( 'change-cc' );
		$('body').on('moip_checkout_payment_method', this._onChangePaymentMethod.bind(this));
		$('#place_order').on('click', this._onClickSubmitCheckout.bind(this));
		//$('body').on('init_checkout', this._onInitCheckout.bind(this));
	};

	Model.fn._onClickSubmitCheckout = function(event) {
		if ($('[data-element="moip-payment-method"]').val() === 'payCreditCard') {
			this.setCreditCardHash();
		}
	};

	Model.fn._onClickChangeCc = function(event) {
		this.elements.storedCcInfo.toggleClass( CLASS_HIDE );
		this.elements.fieldsCcData.toggleClass( CLASS_HIDE );
	};

	Model.fn._onChangePaymentMethod = function(event, target, payMethod) {
		$('[data-element="moip-payment-method"]').val(payMethod);
	}

	Model.fn.setCreditCard = function() {
		if ( $('[data-element="card-number"]') ) {
			$('[data-element="card-number"]').payment( 'formatCardNumber' );
			$('[data-element="card-expiry"]').payment( 'formatCardExpiry' );
			$('[data-element="card-cvc"]').payment( 'formatCardCVC' );
		}
	};

	Model.fn.checkCreditCardData = function() {
		this.expiry     = $('[data-element="card-expiry"]').val();
		this.cardNumber = $('[data-element="card-number"]').val();
		this.cardCvc    = $('[data-element="card-cvc"]').val();

		if ( $.trim( this.expiry ) && $.trim( this.cardNumber ) && $.trim( this.cardCvc ) ) {
			return true;
		}

		return false;
	};

	Model.fn.setCreditCardHash = function() {
		if ( this.isStoredCC() && !$('[data-element="fields-cc-data"]').is( ':visible' ) ) {
			return true;
		}

		if ( !this.checkCreditCardData() ) {
			this.failMessage( this.messages.failRequiredFieldsCreditCard );
		}

		var expPieces = this.expiry.split( '/' )
		  , month  	  = expPieces[0]
		  , year      = expPieces[1]
		;

		var cc = new Moip.CreditCard({
			number   : this.cardNumber,
			cvc      : this.cardCvc,
			expMonth : month ? month.trim() : false,
			expYear  : year ? year.trim() : false,
			pubKey   : $('[data-element="public-key"]').val()
		});

		if ( ! cc.isValid() ) {
			this.failMessage( this.messages.failDefaultTextCreditCard );
			return false;
		}

		if ( parseInt(this.data.encrypt) === 1 ) {
			$('[data-element="hash"]').val( cc.hash() );
		}

		return true;
	};

	Model.fn.isStoredCC = function() {
		return ( parseInt(this.data.storeCreditCard) === 1 );
	};

	Model.fn.checkStoredCC = function() {
		if ( this.isStoredCC() && $('[data-element="card-number"]') ) {
			$('[data-element="stored-cc-info"]').removeClass( CLASS_HIDE );
			$('[data-element="fields-cc-data"]').addClass( CLASS_HIDE );
			$('[data-element="old-cc-info"]').removeClass( CLASS_HIDE );
		}
	};
});
