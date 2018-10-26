MONSTER( 'MOIP.Components.Authorize', function(Model, $, utils) {

	var errorClass = utils.addPrefix( 'field-error' );

	Model.fn.start = function() {
		this.btnText = this.elements.button.text();
		this.init();
	};

	Model.fn.init = function() {
		this.addEventListener();
	};

	Model.fn.addEventListener = function() {
		this.on( 'submit', 'form' );
		this.click( 'close' );
	};

	Model.fn._onSubmitForm = function(event) {
		event.preventDefault();

		this.elements.message.hide();
		this.elements.closeAfter.hide();

		this.loading();

		if ( !this.isCheckedMode() ) {
			this.clear();
			return;
		}

		this._request( event );
	};

	Model.fn.isCheckedMode = function(element) {
		var mode = this.elements.mode;

		if ( mode.is( ':checked' ) ) {
			mode.removeClass( errorClass );
			return true;
		}

		mode.addClass( errorClass );

		return false;
	};

	Model.fn.loading = function() {
		var button = this.elements.button;

		button.text( button.data( 'text-waiting' ) ).prop( 'disabled', true );
		button.append( utils.getSpinner() );
	};

	Model.fn.clear = function() {
		this.elements.button.text( this.btnText ).prop( 'disabled', false );
	};

	Model.fn._request = function(event) {
		this.ajax({
			data : {
				action : 'GAb1rv70dV',
				mode   : this.elements.mode.filter( ':checked' ).val()
			}
		});
	};

	Model.fn._done = function(response) {
		var alert = this.elements.message.find( 'span' );

		if ( response.success ) {
			window.location = response.data;
			return;
		}

		this.clear();

		alert.attr( 'class', '' );
		alert.addClass( 'alert alert-danger' );
		alert.text( response.data );

		this.elements.message.slideToggle();
	};

	Model.fn._fail = function() {
		this.clear();
	};

	Model.fn._onClickClose = function(event) {
		event.preventDefault();
		this.body.find( '#app-overlay' ).slideToggle();
	};

});