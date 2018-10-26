MONSTER( 'MOIP.Components.CheckoutDefault', function(Model, $, utils) {

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
		this.$el.on( 'submit', this._onSubmit.bind(this) );
		this.click( 'tab' );
		this.click( 'change-cc' );

		if ( typeof $().select2 === 'function' ) {
			this._select2();
		}
	};

	Model.fn._select2 = function() {
		this.$el.byAction( 'select2' ).select2( {
			width                   : '200px',
			minimumResultsForSearch : 20
		});
	};

	Model.fn.setCreditCard = function() {
		if ( this.elements.cardNumber ) {
			this.elements.cardNumber.payment( 'formatCardNumber' );
			this.elements.cardExpiry.payment( 'formatCardExpiry' );
			this.elements.cardCvc.payment( 'formatCardCVC' );
		}
	};

	Model.fn.checkCreditCardData = function() {
		this.expiry     = this.elements.cardExpiry.val();
		this.cardNumber = this.elements.cardNumber.val();
		this.cardCvc    = this.elements.cardCvc.val();

		if ( $.trim( this.expiry ) && $.trim( this.cardNumber ) && $.trim( this.cardCvc ) ) {
			return true;
		}

		return false;
	};

	Model.fn.setCreditCardHash = function() {
		if ( this.isStoredCC() && !this.elements.fieldsCcData.is( ':visible' ) ) {
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
			pubKey   : this.elements.publicKey.val()
		});

		if ( ! cc.isValid() ) {
			this.failMessage( this.messages.failDefaultTextCreditCard );
			return false;
		}

		if ( this.data.encrypt ) {
			this.elements.hash.val( cc.hash() );
		}

		return true;
	};

	Model.fn._onSubmit = function(e) {
		e.preventDefault();

		var paymentMethod = $( '[name=payment_method]:checked' ).val();

		if ( paymentMethod == 'payCreditCard' ) {
			if ( ! this.setCreditCardHash() ) {
				return;
			}
		}

		var self = this;

		swal({
			title: this.messages.processingTitle,
			text: this.messages.processingTextWaiting,
			allowOutsideClick : false,
			onOpen: function () {
		    	swal.showLoading();
			    self.ajax({
					data: {
						action  : 'N7yAgMU7JJ',
						order   : self.data.order,
						encrypt : self.data.encrypt,
						fields  : self.$el.serializeArray()
					}
				});
			}
		});
	};

	Model.fn._onClickTab = function(e) {
		this.elements[ e.currentTarget.dataset.ref ].click();
	};

	Model.fn._onClickChangeCc = function(event) {
		this.elements.storedCcInfo.toggleClass( CLASS_HIDE );
		this.elements.fieldsCcData.toggleClass( CLASS_HIDE );
	};

	Model.fn._done = function(response) {
		if ( ! response.success ) {
			this.failMessage( response.data );
		} else {
			this.successMessage();
		}
	};

	Model.fn._fail = function(jqXHR, textStatus, errorThrown) {
		this.failMessage();
	};

	Model.fn.failMessage = function(message) {
		var defaultText = this.messages.failDefaultText;

		swal({
			type : 'error',
			html : message || defaultText
		});
	};

	Model.fn.successMessage = function() {
		var self = this;

		swal({
			type : 'success',
			html: this.messages.processingTextCompleted,
			allowOutsideClick : false
		}).then(function(){
			window.location.href = self.data.returnUrl;
		});
	};

	Model.fn.isStoredCC = function() {
		return ( this.data.storeCreditCard === 1 );
	};

	Model.fn.checkStoredCC = function() {
		if ( this.isStoredCC() && this.elements.cardNumber ) {
			this.elements.storedCcInfo.removeClass( CLASS_HIDE );
			this.elements.fieldsCcData.addClass( CLASS_HIDE );
			this.elements.oldCcInfo.removeClass( CLASS_HIDE );
		}
	};
});
