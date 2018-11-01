<?php 

    /*

    Plugin Name: Custom Title 

    Plugin URI: http://dyuthichandran.com

    Description:  A new plugin to customize title of a page/post  is easier than ever! 

    Author: Dyuthi chandran

    Version: 2.1

     */

add_action( 'admin_head-post.php', 'tinymce_title_js');

add_action( 'admin_head-post-new.php', 'tinymce_title_js');



add_filter('mce_buttons_2', 'add_font_family_row_2' );

function add_font_family_row_2( $mce_buttons ) {
        $pastetext = array_search( 'pastetext', $mce_buttons );
        $pasteword = array_search( 'pasteword', $mce_buttons );
        $removeformat = array_search( 'removeformat', $mce_buttons );

        unset( $mce_buttons[ $pastetext ] );
        unset( $mce_buttons[ $pasteword ] );
        unset( $mce_buttons[ $removeformat ] );
        array_splice( $mce_buttons, $pastetext, 0, 'fontselect' );
        return $mce_buttons;
}
function add_font_family_row_3( $mce_buttons ) {
        $mce_buttons[] = 'fontselect';
        return $mce_buttons;
}
add_filter('tiny_mce_before_init', 'font_choices' );
function font_choices( $init ) {
        $init['theme_advanced_fonts'] = 
                'Andale Mono=andale mono,times;'.
                'Arial=arial,helvetica,sans-serif;'.
                'Arial Black=arial black,avant garde;'.
                'Book Antiqua=book antiqua,palatino;'.
                'Comic Sans MS=comic sans ms,sans-serif;'.
                'Courier New=courier new,courier;'.
                'Georgia=georgia,palatino;'.
                'Helvetica=helvetica;'.
                'Impact=impact,chicago;'.
                'Tahoma=tahoma,arial,helvetica,sans-serif;'.
                'Terminal=terminal,monaco;'.
                'Times New Roman=times new roman,times;'.
                'Trebuchet MS=trebuchet ms,geneva;'.
                'Verdana=verdana,geneva;'.
                'Webdings=webdings;'.
                'Wingdings=wingdings,zapf dingbats'.
                '';
        return $init;
}


function tinymce_title_js(){ ?>

<script type="text/javascript">
    
jQuery( document ).ready(function() {

 jQuery("#title").addClass("mceEditor");

tinyMCE.execCommand("mceAddEditor", true, "title");

});

jQuery("#title-prompt-text").click(function(){

jQuery("label").css("display","none");

});

</script>


<style type='text/css'>

#titlewrap{border:solid 1px #e5e5e5 !important;}

tr.mceLast{display:none;}

#title_ifr{height:50px !important;}

#title-prompt-text{

	z-index: 999;

	padding:74px 10px !important;

}

</style>

<?php }?>