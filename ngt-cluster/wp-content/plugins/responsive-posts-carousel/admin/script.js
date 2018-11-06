jQuery(document).ready( function(){

    jQuery('.tab-content:nth-child(2)').addClass('firstelement');
    
    jQuery('#wcp-loader').hide();
    jQuery('#wcp-saved').hide();
    var sCounter = jQuery('#accordion div:last-child').find('button.fullshortcode').attr('id');
    var icons = {
        header: "dashicons dashicons-arrow-right-alt2",
        activeHeader: "dashicons dashicons-arrow-down-alt2"
    };    

    jQuery( "#accordion" ).accordion({
      collapsible: true,
      icons: icons,
      // header: '.ui-accordion-header-icon'
    });   

    jQuery( "#accordion" ).on('change', '.wcp-taxonomy', function(event) {
        event.preventDefault();
        var element = jQuery(this);
        element.closest('.form-table').find('.append-terms').html('<img src="'+wcpAjax.path+'images/ajax-loader.gif">');
        jQuery.post(wcpAjax.url, {action: 'wcp_get_terms' , taxonomy: element.val()}, function(resp) {
          element.closest('.form-table').find('.append-terms').html(resp);
        });
        
    });

    jQuery('.colorpicker').wpColorPicker();
    
    jQuery('#photo-book').on('click', '.save-pages', function(event) {
        event.preventDefault();
        jQuery('#wcp-saved').hide();
        jQuery('#wcp-loader').show();

        var allCarousels = [];

        jQuery('#accordion > div').each(function(index) {
            var carousel = {};

            carousel.taxonomy = jQuery(this).find('.wcp-taxonomy').val();
            carousel.term = jQuery(this).find('.wcp-term').val();
            carousel.exclude_ids = jQuery(this).find('.exclude-ids').val();
            carousel.bgcolor = jQuery(this).find('.colorpicker').val();
            carousel.shortcode = jQuery(this).find('.fullshortcode').attr('id');
            carousel.counter = jQuery(this).find('.fullshortcode').attr('id');

            carousel.wcpposttype = jQuery(this).find('.wcpposttype').val();
            carousel.post_ids = jQuery(this).find('.posttypeids').val();
            
            carousel.width = jQuery(this).find('.itemwidth').val();
            carousel.slideshowSpeed = jQuery(this).find('.slideshowSpeed').val();
            carousel.animationSpeed = jQuery(this).find('.animationSpeed').val();
            carousel.carouselname = jQuery(this).find('.carouselname').val();

            if (jQuery(this).find('.looping').is(":checked")){ carousel.looping = true; } else { carousel.looping = false; }
            if (jQuery(this).find('.playpause').is(":checked")){ carousel.playpause = true; } else { carousel.playpause = false; }
            if (jQuery(this).find('.slideshow').is(":checked")){ carousel.slideshow = true; } else { carousel.slideshow = false; }
            if (jQuery(this).find('.showtitles').is(":checked")){ carousel.showtitles = true; } else { carousel.showtitles = false; }
            if (jQuery(this).find('.smoothHeight').is(":checked")){ carousel.smoothHeight = true; } else { carousel.smoothHeight = false; }
            if (jQuery(this).find('.showtime').is(":checked")){ carousel.showtime = true; } else { carousel.showtime = false; }
            if (jQuery(this).find('.controlnav').is(":checked")){ carousel.controlnav = true; } else { carousel.controlnav = false; }
            if (jQuery(this).find('.directionnav').is(":checked")){ carousel.directionnav = true; } else { carousel.directionnav = false; }

            

            allCarousels.push(carousel);

        });

        // console.log(allbooks);
        var data = {
            action: 'wcp_save_posts_carousel_slides',
            carousels: allCarousels,
        }

        jQuery.post(wcpAjax.url, data, function(resp) {
            jQuery('#wcp-loader').hide();
            jQuery('#wcp-saved').show();
        });

    });
  

    jQuery('#accordion .btnadd').click(function(event) {
        event.preventDefault();
        sCounter++;
        jQuery( "#accordion" ).append('<h3>Posts Carousel</h3>');
        // jQuery(this).closest('.ui-accordion-content').clone(true).removeClass('firstelement').appendTo('#accordion').find('.shortcode').text(sCounter).closest('.tab-content').find('.wp-picker-container').remove().
        var parent_newly = jQuery(this).closest('.ui-accordion-content').clone(true).removeClass('firstelement').appendTo('#accordion').find('button.fullshortcode').attr('id', sCounter).closest('.tab-content');
        parent_newly.find('.wp-picker-container').remove();
        parent_newly.find('.insert-picker').append('<input type="text" class="colorpicker" value="#000000" />');
        jQuery("#accordion").accordion('refresh');
        parent_newly.find('.colorpicker').wpColorPicker();
    });
    jQuery('#accordion .btndelete').click(function(event) {
        event.preventDefault();
        if (jQuery(this).closest('.ui-accordion-content').hasClass('firstelement')) {
            alert('You can not delete it as it is first element!');
        } else {
            var head = jQuery(this).closest('.ui-accordion-content').prev();
            var body = jQuery(this).closest('.ui-accordion-content');
            head.remove();
            body.remove();
            jQuery("#accordion").accordion('refresh');
        }
    });

    jQuery('button.fullshortcode').click(function(event) {
        event.preventDefault();
        prompt("Copy and use this Shortcode", '[posts-carousel id="'+jQuery(this).attr('id')+'"]');
    });
});    