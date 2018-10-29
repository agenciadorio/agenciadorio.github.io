"use strict";
var __extends = (this && this.__extends) || (function () {
    var extendStatics = function (d, b) {
        extendStatics = Object.setPrototypeOf ||
            ({ __proto__: [] } instanceof Array && function (d, b) { d.__proto__ = b; }) ||
            function (d, b) { for (var p in b) if (b.hasOwnProperty(p)) d[p] = b[p]; };
        return extendStatics(d, b);
    }
    return function (d, b) {
        extendStatics(d, b);
        function __() { this.constructor = d; }
        d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
    };
})();
Object.defineProperty(exports, "__esModule", { value: true });
var ElementPropertiesBase_1 = require("./ElementPropertiesBase");
var DatePickerProperty = /** @class */ (function (_super) {
    __extends(DatePickerProperty, _super);
    function DatePickerProperty(formelement, propertiesObject, propertyName, propertyTitle, additionalInformation) {
        var _this = _super.call(this, formelement, propertiesObject, propertyName, propertyTitle, additionalInformation) || this;
        _this.AllowRelativeDates = false;
        _this.SetTooltip("<p style=\"margin:0;padding:0;\">Permitted values:</p>\n                    <p style=\"margin:0;padding:0;font-weight: normal;\"><strong>A date:</strong> In the format of MM/DD/YYYYY</p>\n                    <p style=\"margin:0;padding:0;font-weight: normal;\"><strong>A formula:</strong> The formula must return a date</p>\n                    <p style=\"margin:0;padding:0;font-weight: normal;\"><strong>A number:</strong> Number of days after today or before for negative numbers</p>\n                    <p style=\"margin:0;padding:0;font-weight: normal;\"><strong>An string:</strong>Example: +1w +1d for one week plus one day after today. Available strings: w (week), m(month), d(day), y(year)</p>\n                    \n                ");
        return _this;
    }
    DatePickerProperty.prototype.InternalGenerateHtml = function ($fieldContainer) {
        var _this = this;
        var $input = rnJQuery("<input class=\"form-control\" type=\"text\" />");
        $fieldContainer.append($input);
        $input.datepicker({
            beforeShow: function () {
                rnJQuery('#ui-datepicker-div').wrap('<div class="smartFormsSlider"></div>');
                $input.addClass('is-focused');
            },
            onClose: function () {
                rnJQuery('#ui-datepicker-div').unwrap();
                $input.removeClass('is-focused');
            },
            changeMonth: true,
            changeYear: true,
            yearRange: "-200:+200"
        });
        var currentValue = this.GetPropertyCurrentValue();
        if (currentValue != null && currentValue != '')
            if (this.IsRelativeDate(currentValue))
                $input.val(currentValue);
            else
                $input.datepicker('setDate', new Date(parseInt(currentValue)));
        if (this.AllowRelativeDates)
            $input.unbind('keypress');
        $input.change(function () {
            var value = $input.val();
            if (!_this.IsRelativeDate(value))
                value = $input.datepicker('getDate').getTime();
            _this.Manipulator.SetValue(_this.PropertiesObject, _this.PropertyName, value, _this.AdditionalInformation);
        });
    };
    DatePickerProperty.prototype.SetAllowRelativeDates = function () {
        this.AllowRelativeDates = true;
        return this;
    };
    DatePickerProperty.prototype.IsRelativeDate = function (value) {
        if (!isNaN(Number(value)) && parseInt(value) < 100000000)
            return true;
        value = value.toString();
        return value.indexOf('w') > 0 || value.indexOf('d') > 0 || value.indexOf('y') > 0;
    };
    return DatePickerProperty;
}(ElementPropertiesBase_1.ElementPropertiesBase));
exports.DatePickerProperty = DatePickerProperty;
//# sourceMappingURL=DatePickerProperty.js.map