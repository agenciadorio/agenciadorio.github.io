MONSTER( 'MOIP.HandleCheckoutFields', function(Model, $, utils) {

	Model.create = function() {
		if ( ! window.wcbcf_public_params ) {
			this.init();
			this.addEventListener();

			this.personType.trigger( 'change' );
		}
	};

	Model.init = function() {
		this.company    = $( '#billing_company_field' );
		this.cnpj       = $( '#billing_cnpj_field' );
		this.cpf        = $( '#billing_cpf_field' );
		this.personType = $( '#billing_persontype' );

		this.setSelect2();
		this.hideElements();
		this.setRequired();
		this.setMask();
	};

	Model.addEventListener = function() {
		this.personType.on( 'change', this.onChange.bind(this) );
	};

	Model.onChange = function(e) {
		var selectedOption = e.currentTarget.value;

		this.company.hide();
		this.cnpj.hide();
		this.cpf.hide();

		if ( selectedOption == 1 ) {
			this.cpf.show();
		}

		if ( selectedOption == 2 ) {
			this.company.show();
			this.cnpj.show();
		}
	};

	Model.hideElements = function() {
		this.company.hide();
		this.cnpj.hide();
		this.cpf.hide();
	};

	Model.setRequired = function() {
		$( '.person-type-field' ).addClass( 'validate-required' );
		$( '.person-type-field label' ).append( ' <abbr class="required">*</abbr>' );
	};

	Model.setMask = function() {
		$( '#billing_cpf' ).mask( '000.000.000-00' );
		$( '#billing_cnpj' ).mask( '00.000.000/0000-00' );
		$( '#billing_birthdate' ).mask( '00/00/0000', { placeholder: '__/__/____' } );
	};

	Model.setSelect2 = function() {
		this.personType.select2();
	};

});