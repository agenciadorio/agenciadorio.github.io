(function ($) {
    'use strict';
    $(".rt-tpg-container").each(function () {
        var $isotopeHolder = $(this).find('.tpg-isotope');
        var $isotope = $isotopeHolder.find('.rt-tpg-isotope');
        if ($isotope.length) {
            var isotope = $isotope.imagesLoaded(function () {
                $.when(tgpHeightResize()).done(function () {
                    isotope.isotope({
                        itemSelector: '.isotope-item',
                    });
                    setTimeout(function () {
                        isotope.isotope();
                    }, 100);
                });
            });
            var $isotopeButtonGroup = $isotopeHolder.find('.rt-tpg-isotope-buttons');
            $isotopeButtonGroup.on('click', 'button', function (e) {
                e.preventDefault();
                var filterValue = $(this).attr('data-filter');
                isotope.isotope({filter: filterValue});
                $(this).parent().find('.selected').removeClass('selected');
                $(this).addClass('selected');
            });
        }
    });


    $(window).on('load resize', function(){
        tgpHeightResize();
        overlayIconResizeTpg();
    });

    function tgpHeightResize() {
        var wWidth = $(window).width();
        if(wWidth > 767) {
            $(".rt-tpg-container").each(function () {
                var self = $(this),
                    rtMaxH = 0;
                self.imagesLoaded(function () {
                    self.children('.row').children(".rt-equal-height:not(.rt-col-md-12)").height("auto");
                    self.children('.row').children('.rt-equal-height:not(.rt-col-md-12)').each(function () {
                        var $thisH = $(this).actual('outerHeight');
                        if ($thisH > rtMaxH) {
                            rtMaxH = $thisH;
                        }
                    });
                    self.children('.row').children(".rt-equal-height:not(.rt-col-md-12)").css('height', rtMaxH + "px");

                });
            });
        }else{
            $(".rt-tpg-container").find(".rt-equal-height").height('auto');
        }
    }
    
    function overlayIconResizeTpg() {
        $('.overlay').each(function () {
            var holder_height = $(this).height();
            var target = $(this).children('.link-holder');
            var targetd = $(this).children('.view-details');
            var a_height = target.height();
            var ad_height = targetd.height();
            var h = (holder_height - a_height) / 2;
            var hd = (holder_height - ad_height) / 2;
            target.css('top', h + 'px');
            targetd.css('margin-top', hd + 'px');
        });
    }


})(jQuery);
