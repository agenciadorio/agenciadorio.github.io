/**
 * JS for admin fontpicker, included to Pages that has it
 */
(function( $ ) {

    // Add Color Picker to all inputs that have 'tt-colorpicker' class
    $(function() {
        $('.tt-font-picker').select2({
            placeholder : "Select a font",
            allowClear  : true
        });
    });

})( jQuery );