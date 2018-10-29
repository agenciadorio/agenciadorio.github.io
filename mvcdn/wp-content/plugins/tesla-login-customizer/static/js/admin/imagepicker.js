/**
 *  JS for admin imagepicker, included to Pages that has imagepicker
 */
function ValidUrl(str) {
    var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
        '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // domain name
        '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
        '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
        '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
        '(\\#[-a-z\\d_]*)?$','i'); // fragment locator
    if(!pattern.test(str)) {
        return false;
    } else {
        return true;
    }
}

(function( $ ) {

    // Add Color Picker to all inputs that have 'tt-colorpicker' class
    $(function() {
        $('#wpbody-content').on('click','.tt-image-picker-button,.tt-image-picker-preview', function(e){
            e.preventDefault();
            // Uploading files
            var file_frame;
            var button = $(this);

            // Create the media frame.
            file_frame = wp.media.frames.file_frame = wp.media({
                title: button.data( 'uploader-title' ),
                /*button: {
                    text: button.data( 'uploader-button-text' )
                },*/
                editing : true,
                displaySettings: true,
                displayUserSettings: true,
                multiple: false  // Set to true to allow multiple files to be selected
            });

            // When an image is selected, run a callback.
            file_frame.on( 'select', function() {
                // We set multiple to false so only get one image from the uploader
                var attachment = file_frame.state().get('selection').first().toJSON();

                // Do something with attachment.id and/or attachment.url here or do console.log(attachment) to get the list
                if(button.hasClass('tt-image-picker-button')) //if clicked on image or button
                    button.siblings('.tt-image-remove-button').removeClass('tt-hide');
                else
                    button.parent().siblings('tt-image-picker-nav').find('.tt-image-remove-button').removeClass('tt-hide');

                button.parent()
                        .siblings('.tt-image-picker-input').attr('value',attachment.url)
                        .siblings('span.tt-image-box').removeClass('tt-hide')
                        .find('.tt-image-picker-preview').attr('src',attachment.url);
                tt_dependencies();
            });

            // Finally, open the modal
            file_frame.open();
        });

        //---------------REMOVE UPLOADED IMAGE--------------------------------
        $('.tt-image-remove-button').on('click',function( event ){
            event.preventDefault();
            $(this).addClass('tt-hide');
            $(this).parent()
                .siblings('input[type=url]').attr('value','')
                .siblings('span.tt-image-box').addClass('tt-hide')
                .find('img.tt-image-picker-preview').attr('src','');
            tt_dependencies();
        });

        //On link direct insertion/paste in input
        var image;
        $('#wpbody-content').on('change input paste','.tt-image-picker-input', function(e) {
            var input = $(this);
            var spinner = input.next('span.spinner');
            var image_box = input.prev('span.tt-image-box');
            var image_url = $(this).val();

            if( image_url !== '' && image_url.indexOf('http') > -1 && ValidUrl( image_url ) ) {
                image_box.addClass('tt-image-loading');
                spinner.addClass('is-active');
                image = new Image();
                image.onload = function(){
                    console.log('image loaded');
                    input.siblings('.tt-image-picker-nav').find('.tt-image-remove-button').removeClass('tt-hide');
                    image_box.removeClass('tt-hide').find('img.tt-image-picker-preview').attr('src',image_url);
                    spinner.removeClass('is-active');
                    image_box.removeClass('tt-image-loading');
                    tt_dependencies();
                }
                image.onerror = function(err){
                    console.error(err);
                    image_box.removeClass('tt-image-loading');
                    spinner.removeClass('is-active');
                }
                image.src = image_url;
            }else{
                input.siblings('.tt-image-picker-nav').find('.tt-image-remove-button').addClass('tt-hide');
                image_box.addClass('tt-hide')
                    .find('img.tt-image-picker-preview').attr('src','');
                tt_dependencies();
            }
        });
    });

})( jQuery );