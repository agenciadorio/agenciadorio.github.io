jQuery(document).on('click', '#tabMoipCreditCard', function(event, tabName, payMethod) {
    var tabName   = 'moip-payment-method-credit-card'
      , payMethod = 'payCreditCard'
      , i
      , tabcontent 
      , tablinks;

    tabcontent = document.getElementsByClassName( "tabcontent" );

    for ( i = 0; i < tabcontent.length; i++ ) {
        tabcontent[i].style.display = "none";
    }

    tablinks = document.getElementsByClassName( "tablinks" );

    for ( i = 0; i < tablinks.length; i++ ) {
        tablinks[i].className = tablinks[i].className.replace( " active", "" );
    }

    document.getElementById(tabName).style.display = "block";
    event.currentTarget.className += " active";

    jQuery('body').trigger('moip_checkout_payment_method', [event, payMethod]);

});

jQuery(document).on('click', '#tabMoipBillet', function(event, tabName, payMethod) {
    var tabName   = 'moip-payment-method-billet'
      , payMethod = 'payBoleto'
      , i
      , tabcontent
      , tablinks;

    tabcontent = document.getElementsByClassName( "tabcontent" );

    for ( i = 0; i < tabcontent.length; i++ ) {
        tabcontent[i].style.display = "none";
    }

    tablinks = document.getElementsByClassName( "tablinks" );

    for ( i = 0; i < tablinks.length; i++ ) {
        tablinks[i].className = tablinks[i].className.replace( " active", "" );
    }

    document.getElementById(tabName).style.display = "block";
    event.currentTarget.className += " active";

    jQuery('body').trigger('moip_checkout_payment_method', [event, payMethod]);

});

function render_title_coupon() {
    var titleCoupon,
        titleTotal;

    if (jQuery('tr').hasClass('coupon-moip_official')) {
        titleCoupon = document.getElementsByClassName('coupon-moip_official')[0].cells[0];
        titleTotal  = document.getElementsByClassName('order-total')[0].cells[0];
        
        titleCoupon.innerHTML = 'Desconto no Boleto';
        titleTotal.innerHTML  = 'Total no Boleto';
    }
}

jQuery(document).on( 'updated_checkout', function() {
    
    render_title_coupon();

    jQuery('input[type=radio][name=payment_method]').change(function() {
        if (this.value == 'woo-moip-official') {
            jQuery(document.body).trigger('update_checkout');
            render_title_coupon();
        } else {
            titleTotal  = document.getElementsByClassName('order-total')[0].cells[0];
            titleCoupon = document.getElementsByClassName('coupon-moip_official')[0].cells[0];
            titleTotal.innerHTML  = 'Total';
            titleCoupon.innerHTML = 'Desconto Moip Oficial';
            jQuery('.moip-order-total-cc').remove();
        }
    });
    
});