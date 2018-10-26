jQuery(document).ready(function($){

    wacChange = function(el_qty) {

        // ask user if they really want to remove this product
        if ( !wacZeroQuantityCheck(el_qty) ) {
            return false;
        }

        // when qty is set to zero, then fires default woocommerce remove link
        if ( el_qty.val() == 0 ) {
            removeLink = el_qty.closest('.cart_item').find('.product-remove a');
            removeLink.click();

            return false;
        }

        // deal with update cart button
        updateButton = $("button[name='update_cart'],input[name='update_cart']");
        updateButton.removeAttr('disabled')
                    .click()
                    .val( wooajaxcart_localization.updating_text )
                    .prop('disabled', true);
        
        // change the Update cart button
        $("a.checkout-button.wc-forward").addClass('disabled')
                                         .html( wooajaxcart_localization.updating_text );

        return true;
    };

    wacPostCallback = function(resp) {
        // ajax response
        $('.cart-collaterals').html(resp.html);

        el_qty.closest('.cart_item').find('.product-subtotal').html(resp.price);

        $('#update_cart').remove();
        $('#is_wac_ajax').remove();
        $('#cart_item_key').remove();

        $("button[name='update_cart'],input[name='update_cart']").val(resp.update_label).prop('disabled', false);

        $("a.checkout-button.wc-forward").removeClass('disabled').html(resp.checkout_label);

        // when changes to 0, remove the product from cart
        if ( el_qty.val() == 0 ) {
            el_qty.closest('tr.cart_item').remove();
        }

        // hide or show the "+" button based on max stock limit (snippet based on @evtihii idea)
        maxStock = el_qty.attr('max');
        if ( maxStock > 0 ) {
            incrementButton = el_qty.parent().find('.wac-btn-inc').parent().parent();

            exceded = ( parseInt( el_qty.val() ) >= parseInt( maxStock ) );
            exceded ? incrementButton.hide() : incrementButton.show();
        }

        // fix to update "Your order" totals when cart is inside Checkout page (thanks @vritzka)
        if ( $( '.woocommerce-checkout' ).length ) {
            $( document.body ).trigger( 'update_checkout' );
        }

        $( document.body ).trigger( 'updated_cart_totals' );
        $( document.body ).trigger( 'wc_fragment_refresh' );
    };

    // overrided by wac-js-calls.php
    wacZeroQuantityCheck = function(el_qty) {
        if ( el_qty.val() == 0 ) {

            if ( !confirm(wooajaxcart_localization.warn_remove_text) ) {
                el_qty.val(1);
                return false;
            }
        }

        return true;
    };

    wacListenChange = function() {
        $(".qty").unbind('change').change(function(e) {

            // prevent to set invalid quantity on select
            if ( $(this).is('select') && ( $(this).attr('max') > 0 ) && ( $(this).val() > $(this).attr('max') ) ) {
                $(this).val( $(this).attr('max') );

                e.preventDefault();
                return false;
            }

            return wacChange( $(this) );
        });
    };

    wacQtyButtons = function() {
        $(document).on('click','.wac-btn-inc', {} ,function(e){
            inputQty = $(this).parent().parent().parent().find('.qty');
            inputQty.val( function(i, oldval) { return ++oldval; });
            inputQty.change();
            return false;
        });

        $(document).on('click','.wac-btn-sub', {} ,function(e){
            inputQty = $(this).parent().parent().parent().find('.qty');
            inputQty.val( function(i, oldval) { return oldval > 0 ? --oldval : 0; });
            inputQty.change();
            return false;
        });
    };

    //
    // onload calls
    //
    wacListenChange();
    wacQtyButtons();

    // listen when ajax cart has updated
    $(document).on('updated_wc_div', function(){
        wacListenChange();
    });
});
