(function ($) {

    function Controller() {
        this.$captionSettigsDialog = $('.bx-caption-settings-editor');
        this.init();
    }

    Controller.prototype.init = (function () {
        this.initSliderSettings();
        this.toggleCaption();
        this.initCaptionSettingsDialog();
        this.initCaptionSettings();

        this.initNavigation();

        this.hexToRgb = function(hex, opacity) {
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
    });

    Controller.prototype.initNavigation = (function() {
        var selectElement = '[name="general[navigation]"]';
        var rowsElement = '#general-rows';
        var generalShowArrowsForThumbs = '#general-thumb-hide-arrows';

        var hideOrShow = function(value) {
            if(value == 1) {
                $(rowsElement).show();
                $(generalShowArrowsForThumbs).show();
            } else {
                $(rowsElement).hide();
                $(generalShowArrowsForThumbs).hide();
            }
        }

        hideOrShow($(selectElement).find('option:selected').val());
        $(document).on('change', selectElement, function(event) {
            var element = $(event.target);
            var option = element.find('option:selected');

            hideOrShow(option.val());
        });
    });

    Controller.prototype.initSliderSettings = (function () {
        var pager = $('input[name="pager[pagerEnabled]"]');
        var navigation = $('select[name="general[navigation]"]');
        var pagerToggle = function(p) {
            if(p.val() == "true" && $('select[name="general[navigation]"] :selected').text() == 'thumbnails') {
                navigation.attr('disabled', 'disabled');
                $('select[name="general[navigation]"] :contains("standart")').attr("selected", "selected");
                $.jGrowl('It\'s impossible to use options of thumbnails navigation and enabled pagination.');
            } else {
                if(navigation.is(':disabled')) {
                    navigation.removeAttr('disabled');
                }
            }
        };
        pagerToggle($('input[name="pager[pagerEnabled]"] :checked'));
        pager.on('click', function() {
            pagerToggle($(this));
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
        var self = this;

        this.$captionSettigsDialog.dialog({
            autoOpen: false,
            modal:    true,
            width:    500,
            buttons:  {
                Cancel: function () {
                    $(this).dialog('close');
                },
                Save: function () {
                    var $settingsContainer = $('.caption');

                    self.$captionSettigsDialog.find('input, select').each(function() {
                        var name = $(this).attr('name'), value = $(this).val(), val;

                        if(name == 'caption[background-color-hex]') {
                            val = self.hexToRgb(value,
                                self.$captionSettigsDialog.find('[name="caption[background-opacity]"]').val()
                            );
                            $settingsContainer.find('[name="caption[background-color]"]').attr('value', val);
                        }
                        $settingsContainer.find('[name="' + name + '"]').attr('value', value);
                    });

                    $(this).dialog('close');
                }
            }
        });

        $('#show-caption-settings').on('click', function(e) {
            e.preventDefault();
            self.$captionSettigsDialog.dialog('open');
        });
    };

    $(document).ready(function () {
        return new Controller();
    });

}(jQuery));