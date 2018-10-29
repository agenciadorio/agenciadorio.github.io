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
var PropertyBase = /** @class */ (function () {
    function PropertyBase(fieldToEdit, elemeentClass, scope, label, styleName) {
        this.fieldToEdit = fieldToEdit;
        this.elemeentClass = elemeentClass;
        this.scope = scope;
        this.label = label;
        this.styleName = styleName;
        this.$propertiesContainer = null;
        this.$property = null;
        this.containerSize = 12;
        this.labelSize = 3;
        this.controlSize = 9;
    }
    PropertyBase.prototype.Generate = function ($propertiesContainer) {
        this.$propertiesContainer = $propertiesContainer;
        this.$property = rnJQuery("<div class=\"row col-sm-" + this.containerSize + "\" style=\"padding:0;\">\n                                    <div class=\"col-sm-" + this.labelSize + "\"><label>" + this.label + "</label></div>                                         \n                                  </div>");
        this.$property.insertBefore(this.$propertiesContainer.find('.clearer'));
        //this.$propertiesContainer.find('.clearer')..append(this.$property);
        var $control = rnJQuery("<div class=\"col-sm-" + this.controlSize + "\"></div>");
        $control.append(this.InternalGenerate());
        this.$property.append($control);
        this.AppendCompleted();
    };
    PropertyBase.prototype.SetDimensions = function (containerSize, labelSize, controlSize) {
        this.containerSize = containerSize;
        this.labelSize = labelSize;
        this.controlSize = containerSize;
        return this;
    };
    PropertyBase.prototype.AppendCompleted = function () {
    };
    PropertyBase.prototype.PropertyChanged = function (value) {
        if (value == '')
            this.ClearValue();
        else
            this.SetValue(value);
        SmartFormsAddNewVar.ApplyCustomCSS();
    };
    PropertyBase.prototype.GetValue = function (styleName) {
        if (styleName === void 0) { styleName = null; }
        if (styleName == null)
            styleName = this.styleName;
        var id = this.GetId();
        var regexp = new RegExp(id + '\\{[\\s\\S]*' + styleName + '[^:]*:([^;|!]*)', 'i');
        var match = regexp.exec(rnJQuery('#smartFormsCSSText').val());
        if (match != null && match.length == 2)
            return rnJQuery.trim(match[1]);
        return '';
    };
    PropertyBase.prototype.GetId = function () {
        var id = '.bootstrap-wrapper.SfFormElementContainer';
        switch (this.scope) {
            case "af":
                id += " .rednao-control-group";
                break;
            case "afsttso":
                id += " ." + this.fieldToEdit.Options.ClassName;
                break;
            case "sfo":
                id += " #" + this.fieldToEdit.Id;
                break;
        }
        return id + " " + this.elemeentClass;
    };
    PropertyBase.prototype.ClearValue = function (styleName) {
        if (styleName === void 0) { styleName = null; }
        if (styleName == null)
            styleName = this.styleName;
        var id = this.GetId();
        var elementStylesRegexp = new RegExp(id + '\\{([^\\}]*)}', 'i');
        var match = elementStylesRegexp.exec(rnJQuery('#smartFormsCSSText').val());
        if (match.length < 2)
            return;
        var fullStyle = match[0];
        var elementsInside = match[1];
        var newText = elementsInside.replace(new RegExp(styleName + '[^;]*;', 'i'), '');
        if (rnJQuery.trim(newText) == '') {
            rnJQuery('#smartFormsCSSText').val(rnJQuery('#smartFormsCSSText').val().replace(fullStyle, ''));
        }
        else {
            rnJQuery('#smartFormsCSSText').val(rnJQuery('#smartFormsCSSText').val().replace(fullStyle, id + '{\r\n' + rnJQuery.trim(newText) + "\r\n}\r\n"));
        }
        console.log(rnJQuery('#smartFormsCSSText').val());
    };
    PropertyBase.prototype.SetValue = function (value, styleName) {
        if (styleName === void 0) { styleName = null; }
        if (styleName == null)
            styleName = this.styleName;
        var id = this.GetId();
        var elementStylesRegexp = new RegExp(id + '\\{([^\\}]*)}', 'i');
        var match = elementStylesRegexp.exec(rnJQuery('#smartFormsCSSText').val());
        if (match == null || match.length < 2) {
            var previousValue = rnJQuery.trim(rnJQuery('#smartFormsCSSText').val());
            if (previousValue.length != 0)
                previousValue += "\r\n";
            rnJQuery('#smartFormsCSSText').val(previousValue + id + '{\r\n' + styleName + ":" + value + " !important;\r\n}\r\n");
        }
        else {
            var styleValue = styleName + ":" + value + " !important;";
            var fullStyle = match[0];
            var selectedStyle = new RegExp(styleName + '[^;]*;', 'i').exec(fullStyle);
            var newStyle = "";
            if (selectedStyle == null)
                newStyle = fullStyle.replace('}', styleValue + "\r\n}");
            else
                newStyle = fullStyle.replace(selectedStyle[0], styleValue);
            rnJQuery('#smartFormsCSSText').val(rnJQuery('#smartFormsCSSText').val().replace(fullStyle, newStyle));
        }
    };
    return PropertyBase;
}());
exports.PropertyBase = PropertyBase;
var ColorPicker = /** @class */ (function (_super) {
    __extends(ColorPicker, _super);
    function ColorPicker(fieldToEdit, elemeentClass, scope, label, styleName) {
        var _this = _super.call(this, fieldToEdit, elemeentClass, scope, label, styleName) || this;
        _this.fieldToEdit = fieldToEdit;
        _this.elemeentClass = elemeentClass;
        _this.scope = scope;
        _this.label = label;
        _this.styleName = styleName;
        return _this;
    }
    ColorPicker.prototype.InternalGenerate = function () {
        var _this = this;
        this.$colorPicker = rnJQuery('<input placeholder="asdf"/>');
        this.$colorPicker.change(function () {
            _this.PropertyChanged(_this.$colorPicker.spectrum("get"));
        });
        return this.$colorPicker;
    };
    ColorPicker.prototype.AppendCompleted = function () {
        this.$colorPicker.spectrum({
            preferredFormat: "hex",
            showInput: true,
            allowEmpty: true,
            showAlpha: true,
            showInitial: true
        });
        rnJQuery('.sp-input').attr('placeholder', 'Default');
        var value = this.GetValue();
        if (value != '') {
            this.$colorPicker.spectrum('set', value);
        }
    };
    return ColorPicker;
}(PropertyBase));
exports.ColorPicker = ColorPicker;
var ButtonsProperty = /** @class */ (function (_super) {
    __extends(ButtonsProperty, _super);
    function ButtonsProperty(fieldToEdit, elemeentClass, scope, label, styleName) {
        var _this = _super.call(this, fieldToEdit, elemeentClass, scope, label, styleName) || this;
        _this.fieldToEdit = fieldToEdit;
        _this.elemeentClass = elemeentClass;
        _this.scope = scope;
        _this.label = label;
        _this.styleName = styleName;
        _this.options = [];
        _this.AddOption('Default', '');
        return _this;
    }
    ButtonsProperty.prototype.InternalGenerate = function () {
        var _this = this;
        this.$buttonToolbar = rnJQuery('<div class="btn-group" role="group" data-toggle="buttons" style="width: 100%"></div>');
        //this.$buttonToolbar.change(()=>this.PropertyChanged(this.$combo.val()));
        var value = this.GetValue();
        var _loop_1 = function (option) {
            var $button = rnJQuery("<button  data-toggle=\"button\" type=\"button\" class=\"btn btn-default " + (option.value == value ? 'active' : '') + "\" data-value=\"" + option.value + "\">" + option.label + "</button >");
            $button.click(function () {
                $button.parent().find('button').removeClass('active');
                $button.addClass('active');
                _this.PropertyChanged(option.value);
            });
            this_1.$buttonToolbar.append($button);
        };
        var this_1 = this;
        for (var _i = 0, _a = this.options; _i < _a.length; _i++) {
            var option = _a[_i];
            _loop_1(option);
        }
        return this.$buttonToolbar;
    };
    ButtonsProperty.prototype.AppendCompleted = function () {
        this.$buttonToolbar.find('button').button();
    };
    ButtonsProperty.prototype.AddOption = function (label, value) {
        this.options.push({ 'label': label, 'value': value });
        return this;
    };
    return ButtonsProperty;
}(PropertyBase));
exports.ButtonsProperty = ButtonsProperty;
var SliderProperty = /** @class */ (function (_super) {
    __extends(SliderProperty, _super);
    function SliderProperty(fieldToEdit, elemeentClass, scope, label, styleName) {
        var _this = _super.call(this, fieldToEdit, elemeentClass, scope, label, styleName) || this;
        _this.fieldToEdit = fieldToEdit;
        _this.elemeentClass = elemeentClass;
        _this.scope = scope;
        _this.label = label;
        _this.styleName = styleName;
        _this.options = [];
        _this.min = 9;
        _this.max = 30;
        return _this;
    }
    SliderProperty.prototype.InternalGenerate = function () {
        var $container = rnJQuery('<div style="width: 100%"></div>');
        this.$slider = rnJQuery("<input  style=\"width: 80%;\" id=\"ex1\" data-slider-id='ex1Slider' type=\"text\" data-slider-min=\"0\" data-slider-max=\"20\" data-slider-step=\"1\" data-slider-value=\"14\"/>");
        this.$textBox = rnJQuery('<input class="form-control" type="text" disabled="disabled" style="font-size: 13px;text-align: center; display: inline; width: calc(20% - 10px);margin-left: 8px;"/>');
        $container.append(this.$slider);
        $container.append(this.$textBox);
        var value = this.GetValue().replace('px', '');
        var label = value;
        if (label == '')
            label = 'Default';
        this.$textBox.val(label);
        return $container;
    };
    SliderProperty.prototype.AppendCompleted = function () {
        var _this = this;
        var value = this.GetValue().replace('px', '');
        ;
        if (value == '')
            value = this.min;
        this.$slider.bootstrapSlider({
            min: this.min,
            max: this.max,
            value: value,
            formatter: function (value) {
                return value == _this.min ? "Default" : value;
            }
        });
        this.$slider.on('slide', function (e) {
            var value = e.value;
            var label = e.value;
            if (value == _this.min) {
                value = '';
                label = 'Default';
            }
            _this.$textBox.val(label);
        });
        this.$slider.on('slideStop', function (e) {
            var value = e.value;
            if (value == _this.min)
                value = '';
            else
                value = value + 'px';
            _this.PropertyChanged(value);
        });
    };
    return SliderProperty;
}(PropertyBase));
exports.SliderProperty = SliderProperty;
/*--------------------------------------------------Combos---------------------------------------------------------*/
var ComboProperty = /** @class */ (function (_super) {
    __extends(ComboProperty, _super);
    function ComboProperty(fieldToEdit, elemeentClass, scope, label, styleName) {
        var _this = _super.call(this, fieldToEdit, elemeentClass, scope, label, styleName) || this;
        _this.fieldToEdit = fieldToEdit;
        _this.elemeentClass = elemeentClass;
        _this.scope = scope;
        _this.label = label;
        _this.styleName = styleName;
        _this.options = [];
        _this.AddOption('Default', '');
        return _this;
    }
    ComboProperty.prototype.InternalGenerate = function () {
        var _this = this;
        this.$combo = rnJQuery('<select style="width: 100%"></select>');
        this.$combo.change(function () { return _this.PropertyChanged(_this.$combo.val()); });
        var value = this.GetValue();
        for (var _i = 0, _a = this.options; _i < _a.length; _i++) {
            var option = _a[_i];
            var op = new Option(option.label, option.value);
            op.selected = op.value == value;
            this.$combo.append(op);
        }
        return this.$combo;
    };
    ComboProperty.prototype.AppendCompleted = function () {
        this.$combo.select2();
    };
    ComboProperty.prototype.AddOption = function (label, value) {
        this.options.push({ 'label': label, 'value': value });
        return this;
    };
    return ComboProperty;
}(PropertyBase));
exports.ComboProperty = ComboProperty;
var TextDecoration = /** @class */ (function (_super) {
    __extends(TextDecoration, _super);
    function TextDecoration(fieldToEdit, elemeentClass, scope) {
        var _this = _super.call(this, fieldToEdit, elemeentClass, scope, "Text Decoration", "text-decoration") || this;
        _this.fieldToEdit = fieldToEdit;
        _this.elemeentClass = elemeentClass;
        _this.scope = scope;
        _this.AddOption('Overline', 'overline');
        _this.AddOption('Line Through', 'line-through');
        _this.AddOption('Underline', 'underline');
        return _this;
    }
    return TextDecoration;
}(ComboProperty));
exports.TextDecoration = TextDecoration;
var Capitalization = /** @class */ (function (_super) {
    __extends(Capitalization, _super);
    function Capitalization(fieldToEdit, elemeentClass, scope) {
        var _this = _super.call(this, fieldToEdit, elemeentClass, scope, "Capitalization", "text-transform") || this;
        _this.fieldToEdit = fieldToEdit;
        _this.elemeentClass = elemeentClass;
        _this.scope = scope;
        _this.AddOption('Capitalize', 'capitalize');
        _this.AddOption('Uppercase', 'uppercase');
        _this.AddOption('Lowercase', 'lowercase');
        return _this;
    }
    return Capitalization;
}(ComboProperty));
exports.Capitalization = Capitalization;
var FontFamily = /** @class */ (function (_super) {
    __extends(FontFamily, _super);
    function FontFamily(fieldToEdit, elemeentClass, scope) {
        var _this = _super.call(this, fieldToEdit, elemeentClass, scope, "Font Family", "Font-Family") || this;
        _this.fieldToEdit = fieldToEdit;
        _this.elemeentClass = elemeentClass;
        _this.scope = scope;
        _this.AddOption('Arial', 'Arial');
        _this.AddOption('ArialBlack', 'ArialBlack');
        _this.AddOption('Comic Sans MS', 'Comic Sans MS');
        _this.AddOption('Courier New', 'Courier New');
        _this.AddOption('Georgia', 'Georgia');
        _this.AddOption('Impact', 'Impact');
        _this.AddOption('Lucida Console', 'Lucida Console');
        _this.AddOption('Lucida Sans Unicode', 'Lucida Sans Unicode');
        _this.AddOption('Platino Linotype', 'Platino Linotype');
        _this.AddOption('Tahoma', 'Tahoma');
        _this.AddOption('Times New Roman', 'Times New Roman');
        _this.AddOption('Trebuchet MS', 'Trebuchet MS');
        _this.AddOption('Verdana', 'Verdana');
        return _this;
    }
    FontFamily.prototype.AppendCompleted = function () {
        var formatResult = function (state) {
            return "<span style='font-family: " + state.text + "'>" + state.text + "</span>";
        };
        this.$combo.select2({
            'formatResult': formatResult,
            'formatSelection': formatResult
        });
    };
    return FontFamily;
}(ComboProperty));
exports.FontFamily = FontFamily;
/*-----------------------------Border-------------------------------*/
var BorderProperty = /** @class */ (function (_super) {
    __extends(BorderProperty, _super);
    function BorderProperty(fieldToEdit, elemeentClass, scope) {
        var _this = _super.call(this, fieldToEdit, elemeentClass, scope, "Borders", "") || this;
        _this.fieldToEdit = fieldToEdit;
        _this.elemeentClass = elemeentClass;
        _this.scope = scope;
        _this.options = [];
        _this.borderLeftEnabled = false;
        _this.borderRightEnabled = false;
        _this.borderTopEnabled = false;
        _this.borderBottomEnabled = false;
        _this.color = "#000000";
        _this.style = "solid";
        _this.width = "1px";
        return _this;
    }
    BorderProperty.prototype.InternalGenerate = function () {
        this.GetDefaultValues();
        this.$container = rnJQuery('<div style="width: 100%"></div>');
        this.CreateBorderButtons();
        this.CreateColor();
        this.CreateBorderStyle();
        this.CreateSlider();
        return this.$container;
    };
    BorderProperty.prototype.AppendCompleted = function () {
        var _this = this;
        this.$buttonToolbar.find('button').button();
        this.$colorPicker.spectrum({
            preferredFormat: "hex",
            showInput: true,
            allowEmpty: true,
            showAlpha: true,
            showInitial: true
        });
        var formatResult = function (state) {
            return "<div style='border-style: " + state.text + ";border-width: 5px;border-color:#c3c3c3;width:100%;height: 20px;margin-top:2px;'>&nbsp;</div>";
        };
        this.$style.select2({
            'formatResult': formatResult,
            'formatSelection': formatResult
        });
        var value = parseInt(this.width.replace('px', ''));
        if (isNaN(value))
            value = 1;
        this.$slider.bootstrapSlider({
            min: 1,
            max: 20,
            value: value
        });
        this.$slider.on('slideStop', function (e) {
            var value = e.value + 'px';
            _this.width = value;
            _this.RefreshStyles();
        });
        this.$slider.parent().find('.slider').css('margin-top', '5px');
        this.$colorPicker.spectrum('set', this.color);
    };
    BorderProperty.prototype.CreateBorderButtons = function () {
        var $container = rnJQuery('<div style="display: inline-block;width: 50%;margin-right: 5px;"></div>');
        this.$buttonToolbar = rnJQuery("<div class=\"btn-group\" role=\"group\" data-toggle=\"buttons\" style=\"display: inline-block;\">\n                                            <button  data-toggle=\"button\" type=\"button\" class=\"btn btn-default sfBorder " + (this.borderTopEnabled ? 'active' : '') + "\" data-value=\"top\"><img src=\"" + smartFormsRootPath + "images/border_top.png\"/></button >\n                                            <button  data-toggle=\"button\" type=\"button\" class=\"btn btn-default sfBorder " + (this.borderRightEnabled ? 'active' : '') + "\" data-value=\"right\"><img src=\"" + smartFormsRootPath + "images/border_right.png\"/></button >\n                                            <button  data-toggle=\"button\" type=\"button\" class=\"btn btn-default sfBorder " + (this.borderBottomEnabled ? 'active' : '') + "\" data-value=\"bottom\"><img src=\"" + smartFormsRootPath + "images/border_bottom.png\"/></button >\n                                            <button  data-toggle=\"button\" type=\"button\" class=\"btn btn-default sfBorder " + (this.borderLeftEnabled ? 'active' : '') + "\" data-value=\"left\"><img src=\"" + smartFormsRootPath + "images/border_left.png\"/></button >\n                                        </div>");
        var self = this;
        this.$buttonToolbar.find('button').click(function () {
            var $button = rnJQuery(this);
            var value = $button.data('value');
            if (!$button.hasClass('active')) {
                $button.addClass('active');
                self.AddBorder(value);
            }
            else {
                $button.removeClass('active');
                self.RemoveBorder(value);
            }
        });
        /*        let $button=rnJQuery();
                $button.click(()=>{
                    $button.parent().find('button').removeClass('active');
                    $button.addClass('active');
                    this.PropertyChanged(option.value);
                });
                this.$buttonToolbar.append($button);*/
        $container.append(this.$buttonToolbar);
        this.$container.append($container);
        this.AddLabel($container, 'Borders to show');
    };
    BorderProperty.prototype.CreateColor = function () {
        var _this = this;
        this.$colorPicker = rnJQuery('<input placeholder="" style=""/>');
        this.$colorPicker.change(function () {
            _this.color = _this.$colorPicker.spectrum("get");
            _this.RefreshStyles();
        });
        var $container = rnJQuery('<div style="width:70px;margin-top:10px;display: inline-block;text-align: center;"></div>');
        $container.append(this.$colorPicker);
        this.$container.append($container);
        this.AddLabel($container, 'Color');
    };
    BorderProperty.prototype.CreateBorderStyle = function () {
        var _this = this;
        var $container = rnJQuery('<div style="width:50%;display: inline-block;"></div>');
        this.$style = rnJQuery('<select style="width:100%; margin-top:10px;"></select>');
        this.$style.append(new Option('Dotted', 'dotted', false, this.style == 'dotted'));
        this.$style.append(new Option('Dashed', 'dashed', false, this.style == 'dashed'));
        this.$style.append(new Option('Solid', 'solid', true, this.style == 'solid'));
        this.$style.append(new Option('Double', 'double', false, this.style == 'double'));
        this.$style.append(new Option('Groove', 'groove', false, this.style == 'groove'));
        this.$style.append(new Option('Ridge', 'ridge', false, this.style == 'ridge'));
        this.$style.append(new Option('Inset', 'inset', false, this.style == 'inset'));
        this.$style.append(new Option('Outset', 'outset', false, this.style == 'outset'));
        this.$style.change(function () {
            _this.style = _this.$style.val();
            _this.RefreshStyles();
        });
        $container.append(this.$style);
        this.$container.append($container);
        this.AddLabel($container, "Style");
    };
    BorderProperty.prototype.CreateSlider = function () {
        this.$slider = rnJQuery("<input  style=\"width: 100%;\" id=\"ex1\" data-slider-id='ex1Slider' type=\"text\" data-slider-min=\"0\" data-slider-max=\"20\" data-slider-step=\"1\" data-slider-value=\"14\"/>");
        var $container = rnJQuery('<div style="width:calc(50% - 11px);display: inline-block;margin-left: 11px;"></div>');
        $container.append(this.$slider);
        this.AddLabel($container, 'Size');
        this.$container.append($container);
    };
    BorderProperty.prototype.AddLabel = function ($container, label) {
        $container.append("<div style=\"width:100%;text-align: center;\"><img style=\"width: 100%;height: 20px;\" src=\"" + smartFormsRootPath + "images/curlyBracket.png\"><span style=\"color:#ddd;\">" + label + "</span></div>");
    };
    BorderProperty.prototype.GetDefaultValues = function () {
        this.borderLeftEnabled = this.GetValue('border-left') != '';
        this.borderRightEnabled = this.GetValue('border-right') != '';
        this.borderTopEnabled = this.GetValue('border-up') != '';
        this.borderBottomEnabled = this.GetValue('border-down') != '';
        var borderToGetDefaults = '';
        if (this.borderLeftEnabled)
            borderToGetDefaults = 'border-left';
        if (this.borderBottomEnabled)
            borderToGetDefaults = 'border-bottom';
        if (this.borderRightEnabled)
            borderToGetDefaults = 'border-right';
        if (this.borderTopEnabled)
            borderToGetDefaults = 'border-top';
        if (borderToGetDefaults == '')
            return;
        var value = this.GetValue(borderToGetDefaults);
        var splittedValues = value.split(' ');
        if (splittedValues.length != 3)
            return;
        this.width = splittedValues[0];
        this.style = splittedValues[1];
        this.color = splittedValues[2];
    };
    BorderProperty.prototype.RefreshStyles = function () {
        var value = this.width + ' ' + this.style + ' ' + this.color;
        if (this.borderTopEnabled)
            this.SetValue(value, 'border-top');
        if (this.borderRightEnabled)
            this.SetValue(value, 'border-right');
        if (this.borderLeftEnabled)
            this.SetValue(value, 'border-left');
        if (this.borderBottomEnabled)
            this.SetValue(value, 'border-bottom');
        SmartFormsAddNewVar.ApplyCustomCSS();
    };
    BorderProperty.prototype.AddBorder = function (border) {
        var value = this.width + ' ' + this.style + ' ' + this.color;
        switch (border) {
            case 'top':
                this.borderTopEnabled = true;
                this.SetValue(value, 'border-top');
                break;
            case 'right':
                this.borderRightEnabled = true;
                this.SetValue(value, 'border-right');
                break;
            case 'bottom':
                this.borderBottomEnabled = true;
                this.SetValue(value, 'border-bottom');
                break;
            case 'left':
                this.borderLeftEnabled = true;
                this.SetValue(value, 'border-left');
                break;
        }
        SmartFormsAddNewVar.ApplyCustomCSS();
    };
    BorderProperty.prototype.RemoveBorder = function (border) {
        switch (border) {
            case 'top':
                this.ClearValue('border-top');
                break;
            case 'right':
                this.ClearValue('border-right');
                break;
            case 'bottom':
                this.ClearValue('border-bottom');
                break;
            case 'left':
                this.ClearValue('border-left');
                break;
        }
        SmartFormsAddNewVar.ApplyCustomCSS();
    };
    return BorderProperty;
}(PropertyBase));
exports.BorderProperty = BorderProperty;
//# sourceMappingURL=PropertyBase.js.map