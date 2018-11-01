(function ($) {

    function Controller() {
        this.$previewWindow = $('#previewWindow');
        this.$triggers = $('.changeEffect');
        this.$optInProWnd = $('#rsOptInProWnd');
        this.$captionSettigsDialog = $('.coin-caption-settings-editor');

        this.init();
    }

    Controller.prototype.init = (function () {
        this.initPreviewWindow();
        this.initSlider();
        this.initTriggers();
        this.initProOptionDialog();
        this.toggleCaption();
        this.initCaptionSettingsDialog();
        this.initCaptionSettings();
    });

    Controller.prototype.initPreviewWindow = (function () {
        this.$previewWindow.dialog({
            modal:    true,
            width:    428,
            autoOpen: false,
            buttons:  {
                Select: function () {
                    $('#effectName').text(function () {
                        var text = $('.changeEffect')
                            .filter(':checked')
                            .val(),
                            f = text.charAt(0).toUpperCase();

                        return f + text.substr(1, text.length - 1);
                    });

                    $('[name="effects[effect]"]').val($('.changeEffect').filter(':checked').val());

                    $(this).dialog('close');
                },
                Cancel: function () {
                    $(this).dialog('close');
                }
            }
        });

        $('#showEffectsPreview').on('click', $.proxy(function (e) {
            e.preventDefault();
            this.$previewWindow.dialog('open');
        }, this));
    });

    Controller.prototype.initTriggers = (function () {
        this.$triggers
            .on('click', $.proxy(function (e) {
                    this.$previewWindow
                        .find('.effectPreview')
                        .hide();

                    this.$previewWindow
                        .find('[data-effect="' + e.currentTarget.value + '"]')
                        .show();
                }, this)
            )
            .filter(':checked')
            .trigger('click');
    });

    Controller.prototype.initSlider = (function () {
        this.$previewWindow
            .find('.effectPreview')
            .each(function () {
                var $container = $(this);

                $container.coinslider({
                    width: 400,
                    height: 150,
                    effect: $container.data('effect'),
                    navigation: false,
                    links: false
                });
            });
    });

    Controller.prototype.initProOptionDialog = (function () {
        this.$optInProWnd.dialog({
            modal:    true
            ,	autoOpen: false
            ,	width: 540
            ,	height: 200
        });
        jQuery('#rsProOpt').click(function () {
            jQuery('#rsOptInProWnd').dialog('open');
        });
    });

    Controller.prototype.toggleCaption = (function() {
        var captionOn = $('#generalCaptionsTrue')
            , captionOff = $('#generalCaptionsFalse')
            , captionOpt = $('button#show-caption-settings');

        captionOn.on('click', function() {
            captionOpt.show();
        });
        captionOff.on('click', function() {
            captionOpt.hide();
        });
    });

    Controller.prototype.initCaptionSettings = (function() {
        var co = $('#captionOpacity');
        $("#caption-opacity").slider({
            min: 0.0,
            max: 1.0,
            step: 0.1,
            value: co.val(),
            stop: function(event, ui) {
                co.val($("#caption-opacity").slider("value"));
            },
            slide: function(event, ui) {
                co.val($("#caption-opacity").slider("value"));
            }
        });
        co.change(function() {
            var curVal = $(this).val(), min = 0.0, max = 1.0;
            if(curVal < min) curVal = min;
            if(curVal > max) curVal = max;
            $("#caption-opacity").slider("value", curVal);
            $(this).val(curVal);
        });
    });

    Controller.prototype.initCaptionSettingsDialog = function() {
        var self = this, $settingsContainer = $('.coin-caption');

        this.$captionSettigsDialog.dialog({
            autoOpen: false,
            modal:    true,
            width:    500,
            buttons:  {
                Cancel: function () {
                    $(this).dialog('close');
                },
                Save: function () {
                    self.$captionSettigsDialog.find('input, select').each(function() {
                        var name = $(this).attr('name'), value = $(this).val(), val;

                        if(name == 'caption[background-color-hex]') {
                            val = self.hexToRgb(value,
                                self.$captionSettigsDialog.find('[name="effects[opacity]"]').val()
                            );
                            $settingsContainer.find('[name="post[caption_bg]"]').attr('value', val);
                        }
                        $settingsContainer.find('[name="' + name + '"]').attr('value', value);
                    });

                    $(this).dialog('close');
                }
            }
        });

        var caption_bg_old = $settingsContainer.find('[name="post[caption_bg]"]')
            , caption_hex_new = $settingsContainer.find('[name="caption[background-color-hex]"]')
            , caption_hex_old;
        if($settingsContainer.length > 0 && caption_bg_old.val() != "" && caption_hex_new.val() != "") {
            caption_hex_old = self.rgbToHex(caption_bg_old.val());
            if(caption_hex_old != caption_hex_new.val())
                caption_hex_new.val(caption_hex_old);
        }

        $('#show-caption-settings').on('click', function(e) {
            e.preventDefault();
            self.$captionSettigsDialog.dialog('open');
        });
    };

    Controller.prototype.rgbToHex = function(rgb) {
        rgb = rgb.match(/^rgba?[\s+]?\([\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?/i);
        return (rgb && rgb.length === 4) ? "#" +
        ("0" + parseInt(rgb[1],10).toString(16)).slice(-2) +
        ("0" + parseInt(rgb[2],10).toString(16)).slice(-2) +
        ("0" + parseInt(rgb[3],10).toString(16)).slice(-2) : '';
    };

    Controller.prototype.hexToRgb = function(hex, opacity) {
        var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
        hex = hex.replace(shorthandRegex, function(m, r, g, b) {
            return r + r + g + g + b + b;
        });

        var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
        return result ? 'rgba('
        + parseInt(result[1], 16) + ', '
        + parseInt(result[2], 16) + ', '
        + parseInt(result[3], 16) + ', '
        + (opacity ? opacity : 0)
        + ')' : null;
    };

    $(document).ready(function () {
        return new Controller();
    });

}(jQuery));