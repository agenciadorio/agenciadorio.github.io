/*global jQuery*/
var g_sslYoutubeAPIReady = false;
(function ($, app, debug, undefined) {

    app.enableTextAnimation = (function($part, properties) {
        if(properties['text-animation'] == 'enable' && !$('.ui-dialog').length) {
            $part.children('span').textillate({
                initialDelay: 1000,
                loop: true,
                in: {
                    effect: properties['text-effect-in'],
                    delay: 100,
                    sync: true
                },
                out: {
                    effect: properties['text-effect-out'],
                    shuffle: true
                }
            });
        }
    });

    app.isControls = (function($element) {
        return $element.hasClass('bx-controls-direction');
    });

    app.setArrows = (function($element, properties) {
        if(this.isControls($element)) {
            $element.find('a').each(function() {
                $(this).attr('id', properties['background']);
            });
        }
    });

    var initVisualSettings = function($container, self) {
        // Apply visual editor styles.
        if ($.isPlainObject($container.data('settings'))) {
            var settings = $container.data('settings');

            if ('__veditor__' in settings && settings['__veditor__']) {
                $.each(settings['__veditor__'], function (selector, properties) {
                    var $part = $(selector, $container);

                    self.setArrows($part, properties);
                    $.each(properties, function (key, value) {
                        $part.css(key, value);
                    });
                    if($part.is('.bx-caption')) {
                        self.enableTextAnimation($part, properties);
                    }
                });
            }
        }
    };

    app.init = (function(selector) {
        var $container, defaultSelector = '.supsystic-slider';
        var self = this;

        $container = (selector == undefined) ? $(defaultSelector) : $(selector);

        if (!$container.length) {
            if (debug) {
                console.log('Selector "' + selector + '" does not exists.');
            }

            return false;
        }

        if ($.isEmptyObject(app.plugins)) {
            if (debug) {
                console.log('There are no registered plugins.');
            }

            return false;
        }

        $.each(app.plugins, function (plugin, callback) {
            if (debug) {
                console.log('Plugin initialization: ' + plugin);
            }

            if (!$.isFunction(callback)) {
                if (debug) {
                    console.log('The callback for the ' + plugin + ' is not a function.');
                }
            }

            callback($container);

            $.each($container, function(index, value) {
                initVisualSettings($(value), self);
            });


        });

        return true;
    });

    $(document).ready(function() {
		//if General Mode equals Fade, script hides the Easing input
		function disabledIfFade() {
			var $generalMode = $('#generalMode').val();

			if ($generalMode == 'fade') {
				$('#generalEasing').prop( "disabled", true );
			} else {
				$('#generalEasing').prop( "disabled", false );
			}
		}

        function initVerticalModeElements() {
            var $generalMode = $('#generalMode').val();

            if ($generalMode == 'vertical') {
                $('#generalNumberOfSlides, #generalDistanceBetweenSlides, #generalVerticalArrowsMode').prop("disabled", false);
                $('#general-number-of-slides, #general-distance-between-slides, #general-vertical-arrows-mode').show();
            } else {
                $('#generalNumberOfSlides, #generalDistanceBetweenSlides, #generalVerticalArrowsMode').prop("disabled", true);
                $('#general-number-of-slides, #general-distance-between-slides, #general-vertical-arrows-mode').hide();
            }
        }

		disabledIfFade();

        initVerticalModeElements();
		$('#generalMode').click(function() {
			disabledIfFade();
            initVerticalModeElements();
		});

        app.init();
        // Show sliders (Bx & Coin) only after initialization
        $('.supsystic-slider').css('visibility', 'visible');

		window.onYouTubeIframeAPIReady = function() {
			g_sslYoutubeAPIReady = true;
		};

    }).ajaxComplete(function() {
        $('.supsystic-slider').css('visibility', 'visible');
        //app.init();
    });

}(jQuery, window.SupsysticSlider = window.SupsysticSlider || {}, document.location.hash == '#debug'));
