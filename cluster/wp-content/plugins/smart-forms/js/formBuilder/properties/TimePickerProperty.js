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
var TimePickerProperty = /** @class */ (function (_super) {
    __extends(TimePickerProperty, _super);
    function TimePickerProperty() {
        return _super !== null && _super.apply(this, arguments) || this;
    }
    TimePickerProperty.prototype.InternalGenerateHtml = function ($fieldContainer) {
        var _this = this;
        var currentValue = this.GetPropertyCurrentValue();
        var date = null;
        date = new Date(new Date().setHours(0, 0, 0, currentValue));
        var $input = rnJQuery("<input class=\"form-control\" type=\"text\" />");
        var options = {
            minuteStep: 1
        };
        $fieldContainer.append($input);
        $input.timepicker(options);
        $input.timepicker('setTime', date);
        $input.on('changeTime.timepicker', function (e) {
            _this.Manipulator.SetValue(_this.PropertiesObject, _this.PropertyName, _this.TimeToMilliseconds(e), _this.AdditionalInformation);
            _this.RefreshElement();
        });
    };
    TimePickerProperty.prototype.TimeToMilliseconds = function (e) {
        var milliseconds = 0;
        milliseconds += (e.time.hours + (e.time.meridian == 'PM' ? 12 : 0)) * 60 * 60 * 1000;
        milliseconds += e.time.minutes * 60 * 1000;
        milliseconds += e.time.seconds * 1000;
        return milliseconds;
    };
    return TimePickerProperty;
}(ElementPropertiesBase_1.ElementPropertiesBase));
exports.TimePickerProperty = TimePickerProperty;
//# sourceMappingURL=TimePickerProperty.js.map