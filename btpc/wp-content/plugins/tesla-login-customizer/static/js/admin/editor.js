/**
 * JS for admin editor, included to Pages that has it
 */
(function( $ ) {

    // Add Color Picker to all inputs that have 'tt-colorpicker' class
    $(function() {
        $('.tt-editor').each(function(index,editor){
            var editor_codemirror = CodeMirror.fromTextArea(editor, {
                lineNumbers: true,
                mode: $(editor).data('mode'),
                theme: "mdn-like",
                matchBrackets: true,
                autoCloseBrackets: true
            });
        });
    });

})( jQuery );