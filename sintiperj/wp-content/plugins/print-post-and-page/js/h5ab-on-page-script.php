<?php

if ( ! defined( 'ABSPATH' ) ) exit;

//http://stackoverflow.com/questions/25461277/store-the-content-into-a-javascript-jquery-variable

ob_start();
the_content();
$printcontent = ob_get_contents();
ob_end_clean();
?>

<script>

jQuery(document).ready(function($){

    sessionStorage.setItem('h5ab-print-article', '<div id="h5ab-print-content"><h1><?php esc_attr(the_title()); ?></h1>' + <?php echo json_encode( $printcontent ); ?> + '</div>');

    $.strRemove = function(theTarget, theString) {
        return $("<div/>").append(
            $(theTarget, theString).remove().end()
        ).html();
    };

    var articleStr = sessionStorage.getItem('h5ab-print-article');
    var removeArr = ['video','audio','script','iframe'];

    $.each(removeArr, function(index, value){
        var processedCode = $.strRemove(value, articleStr);
        articleStr = processedCode;
    });
    
    var fullPrintContent = articleStr;
    sessionStorage.setItem('h5ab-print-article', fullPrintContent);
    
});

</script>