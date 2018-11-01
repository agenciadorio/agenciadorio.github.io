/*global jQuery*/

/**
 * Slider by Supsystic Wordpress Plugin
 * Coin-Slider module.
 */
(function ($, app) {
	/**
	*Try to find the showPreview parametr it the location.href.
	*If it successfull it'll show the modal form with slider preview
	*/
	$(document).ready(function () { 
		$.urlParam = function(name){
			var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
			return results[1] || 0;
		};
		try {
			if ($.urlParam('showPreview') == "show"){
				setTimeout(function(){
					$('#preview-trigger').trigger('click');
				}, 1000);
			}
		} catch(err) {
		}
    });

    /**
     * Converts string true or false to the real boolean values.
     * If value isn't equals to true or false then returns raw value.
     * @param value
     * @returns {*}
     */
    var stringToBoolean = function (value) {
        if (value == 'true') {
            return true;
        } else if (value == 'false') {
            return false;
        } else if(parseFloat(value)) {
            return parseFloat(value);
        } else {
            return value;
        }
    };

    var loadFont = function(fontName) {
        if(fontName) {
            if(fontName.indexOf(',') + 1) {
                fontName = fontName.split(',')[0];
            }
            WebFont.load({
                google: {
                    families: [fontName.split(' ').join('+')]
                }
            });
        }
    };

    var initSlider = function() {
        var $sliders = $('.supsystic-slider.supsystic-slider-coin');

        if ($sliders.length < 1) {
            return false;
        }

        $.each($sliders, function (index, slider) {
			
            var $slider  = $(slider),
                settings = $slider.data('settings'),
                config   = {};

            $.each(settings, function (category, opts) {
                if(opts && typeof opts == 'object') {
                    $.each(opts, function (key, value) {
                        config[key] = stringToBoolean(value);
                    });
                }
            });

            $.each(settings, function (category, opts) {
                if(category != '__veditor__' && typeof opts == 'object') {
                    $.each(opts, function (key, value) {
                        if (key !== 'enabled') {
                            config[key] = stringToBoolean(value);
                        }
                    });
                }
            });

			$sliders.find('.pseudo-link').each(function() {
				var element = $(this);
				var href = element.data('href');
				var text = element.text();
				var style = element.attr('style');

				element.replaceWith(function(){
					return '<a href="' + href + '" style="' + style + '">' + text + '</a>';
				});
			});

            $slider.coinslider(config);
			
			var initResponsive = function(slider) {
				var resizeTo = function(slider, toWidth, toHeight) {
					var csColumns = 7
					,	csRows = 5
					,	imgWidth = toWidth
					,	imgHeight = toHeight
					,	cellWidth = imgWidth/csColumns
					,	cellHeight = imgHeight/csRows
					,	sliderId = slider.attr('id')
					,	sliderNav = $('#cs-navigation-' + sliderId)
					,	sliderBtns = $('#cs-buttons-' + sliderId)
					,	sliderBtnsLeft = ''
					,	sliderBtnsMarginLeft = ''
					,	sliderBtnsMarginBottom = '';

					slider.css({
						'width': imgWidth,
						'height': imgHeight
					});

					$('.cs-' + sliderId).css({
						'width': (Math.ceil(cellWidth) + 'px'),
						'height': (cellHeight + 'px'),
						'background-size': (imgWidth + 'px ' + imgHeight + 'px')
					}).each(function() {
						var cellOffsets = $(this).attr('id').replace('cs-' + sliderId, '')
						,	hOffSet = cellHeight * (Math.floor(parseInt(cellOffsets[0]) - 1) % csRows)
						,	wOffSet = cellWidth * (parseInt(cellOffsets[1]) - 1);

						$(this).css({
							'left': (wOffSet + 'px')
						,	'top': (hOffSet + 'px')
						,	'background-position': ((-wOffSet) + 'px ' + (-hOffSet) + 'px')
						});
					});

					if(sliderBtns[0].offsetWidth > slider.width()) {
						sliderBtns.width(imgWidth);
						sliderBtnsLeft = '0';
						sliderBtnsMarginLeft = '0';
						sliderBtnsMarginBottom = '5';
					} else {
						sliderBtns.width('auto');
						sliderBtnsLeft = '50%';
						sliderBtnsMarginLeft = (slider.width() / 2) - ((slider.width() - sliderBtns[0].offsetWidth) / 2) + 2.5;
						sliderBtnsMarginBottom = '0';
					}

					sliderBtns.css({
						'left': sliderBtnsLeft
					,	'margin-left': '-' + sliderBtnsMarginLeft + 'px'
					});

					sliderBtns.children('a').css({
						'top': ((imgHeight / 2) - 15) + 'px'
					,	'margin-bottom': sliderBtnsMarginBottom + 'px'
					});

					sliderNav.find('#cs-prev-' + sliderId).css('top', ((toHeight / 2) - 15) + 'px');
					sliderNav.find('#cs-next-' + sliderId).css('top', ((toHeight / 2) - 15) + 'px');
				};

				var setSliderSizes = function(slider) {
					var sliderContainer = slider.parent()
					,	newSize = {}
					,	baseWidth = parseInt(slider.width())
					,	baseHeight = parseInt(slider.height());

					newSize.width = Math.floor(parseInt(sliderContainer.parent().width()));
					newSize.height = Math.floor(baseHeight * newSize.width / baseWidth);

					return newSize;
				};

				var checkForResize = function() {
					var newSize = setSliderSizes(slider);

					resizeTo(slider, newSize.width, newSize.height);
				};

				checkForResize();
				$(window).bind('resize', checkForResize);
				$(window).bind('orientationchange', checkForResize);
			};

            $slider.parent().css('float', config['position']);

            if(settings.caption && settings.caption['font-family']) {
                loadFont(settings.caption['font-family']);
            }

			if(config.responsive) {
				initResponsive($slider);
			}

			$slider.css('visibility', 'visible');
        });
    };

    /*$(document).ready(function () {
        initSlider();
        console.log('Yes');
    });

    $(document).ajaxComplete(function() {
        initSlider();
        console.log('Yes');
    });*/

    app.plugins = app.plugins || {};
    app.plugins.coin = initSlider;

}(jQuery, window.SupsysticSlider = window.SupsysticSlider || {}));