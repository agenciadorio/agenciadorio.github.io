import {ElementPropertiesBase} from "./ElementPropertiesBase";
export class ArrayProperty extends ElementPropertiesBase {
    public ItemsList:JQuery;
    public csvToArray(text) {
        let p = '', row = [''], ret = [row], i = 0, r = 0, s = !0, l;
        for (l in text) {
            l = text[l];
            if ('"' === l) {
                if (s && l === p) row[i] += l;
                s = !s;
            } else if (',' === l && s) l = row[++i] = '';
            else if ('\n' === l && s) {
                if ('\r' === p) row[i] = row[i].slice(0, -1);
                row = ret[++r] = [l = '']; i = 0;
            } else row[i] += l;
            p = l;
        }
        return ret;
    };

    public GetFieldTemplate(): JQuery {

        let $field= rnJQuery(`<div class="col-sm-12" style="vertical-align: top;text-align: left;background-color: #fafafa;border: 1px solid #f0f0f0;">
                            <table style="width: 98%;position:relative;">
                                <tr>
                                    <td style="border-style: none;">
                                        <label class="checkbox control-group rednao-properties-control-label" style="display: block;vertical-align: top; text-align: left;margin:0;">
                                            ${this.PropertyTitle}
                                        </label>
                                        <input type="file" class="fileUpload" accept=".csv, .txt" style="display: none;"/>
                                        <a class="import" href="http://www.google.com" style="position: absolute;top:1px;right:5px;">Import</a>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td class="fieldContainer" style="text-align: left;border-style: none;">
                                   
                                    </td>
                                 </tr>
                            </table>
                          </div>`);



        $field.find('.fileUpload').change(()=>{
            let file = ($field.find('.fileUpload')[0] as any).files[0];
            let fr = new FileReader();

            fr.onload = (e)=>{
                let rows=this.csvToArray(fr.result);
                if(rows.length>0)
                    rows.shift();
                let firstElement=true;
                for(let columns of rows)
                {
                    let label:string='';
                    let amount:string='0';
                    if(columns.length==0)
                        continue;

                    label=columns[0];
                    if(columns.length>1)
                        amount=columns[1];
                    if(label.trim()=='')
                        continue;
                    if(firstElement)
                        this.ItemsList.find('tbody').empty();
                    this.AddItem({url:'',label:label,value:amount},firstElement);
                    firstElement=false;
                }
                ($field.find('.fileUpload')[0] as any).value='';

            };
            //fr.readAsText(file);
            fr.readAsText(file);
        });

        $field.find('.import').click((e)=>{
            $field.find('.fileUpload').click();
           e.preventDefault();
        });
        return $field;
    }

    public InternalGenerateHtml($fieldContainer) {
        let currentValues = this.GetPropertyCurrentValue();
        $fieldContainer.append(this.GetItemList(currentValues) );


        $fieldContainer.find('table.listOfItems').append("<tfoot><tr><td style='border-bottom-style: none;'><button class='redNaoPropertyClearButton' value='None'>Clear</button></td></tr></tfoot>");
        $fieldContainer.find('.redNaoPropertyClearButton').click( (event)=> {
            event.preventDefault();
            $fieldContainer.find('.itemSel').removeAttr('checked');
            this.UpdateProperty();
        });

        $fieldContainer.find('.cloneArrayItem').click( (e) =>{
            this.CloneItem(rnJQuery(e.currentTarget))
        });
        $fieldContainer.find('.deleteArrayItem').click((e)=> {
            this.DeleteItem(rnJQuery(e.currentTarget))
        });
        $fieldContainer.find('input[type=text],input[type=radio],input[type=checkbox]').change(() =>{
            this.UpdateProperty();
        });
        $fieldContainer.find('input[type=text]').keyup(()=> {
            this.UpdateProperty();
        });


        this.ItemsList = $fieldContainer.find('.listOfItems');
    };

    public GetItemList(items) {
        let allowImages = typeof this.AdditionalInformation.AllowImages != 'undefined' && this.AdditionalInformation.AllowImages == true;

        let list = '<table style="width: 100%;margin-left: 10px;" class="listOfItems"><tr><th style="text-align: center">Sel</th><th>Label</th>' + (allowImages ? '<th>Image Url</th>' : '') + '<th>Amount</th></tr>';

        let isFirst = true;
        for (let i = 0; i < items.length; i++) {
            list += this.CreateListRow(isFirst, items[i]);
            isFirst = false;
        }
        return list;

    };

    public DeleteItem(jQueryElement) {
        let array = this.GetPropertyCurrentValue();
        let index = jQueryElement.parent().parent().index();

        array.splice(index, 1);
        jQueryElement.parent().parent().remove();
        this.UpdateProperty();
    };

    public CloneItem(jQueryElement) {
        let jQueryToClone = jQueryElement.parent().parent();
        let data = this.GetRowData(jQueryToClone);

        if (this.AdditionalInformation.SelectorType == 'radio')
            data.sel = 'n';

        let jQueryNewRow = rnJQuery(this.CreateListRow(false, data));
        jQueryToClone.after(jQueryNewRow);

        let self = this;
        jQueryNewRow.find('.cloneArrayItem').click(function () {
            self.CloneItem(rnJQuery(this))
        });
        jQueryNewRow.find('.deleteArrayItem').click(function () {
            self.DeleteItem(rnJQuery(this))
        });
        jQueryNewRow.find('input[type=text],input[type=radio],input[type=checkbox]').change(function () {
            self.UpdateProperty();
        });

        this.UpdateProperty();

    };


    public AddItem(item:{url:string,label:string,value:string},firstElement:boolean) {



        let jQueryNewRow = rnJQuery(this.CreateListRow(firstElement, item));
        this.ItemsList.find('tbody').append(jQueryNewRow);


        let self = this;
        jQueryNewRow.find('.cloneArrayItem').click(function () {
            self.CloneItem(rnJQuery(this))
        });
        jQueryNewRow.find('.deleteArrayItem').click(function () {
            self.DeleteItem(rnJQuery(this))
        });
        jQueryNewRow.find('input[type=text],input[type=radio],input[type=checkbox]').change(function () {
            self.UpdateProperty();
        });

        this.UpdateProperty();

    };


    public CreateListRow(isFirst, item:{url:string,label:string,value:string}) {

        let allowImages = typeof this.AdditionalInformation.AllowImages != 'undefined' && this.AdditionalInformation.AllowImages == true;
        if (allowImages && typeof item.url == 'undefined')
            item.url = '';
        let row = '<tr class="redNaoRowOption">' +
            '       <td style="border-style: none; text-align: center;">' + this.GetSelector(item) + '</td>' +
            '       <td style="border-style: none;width: ' + (allowImages ? "50%" : "100%") + ';"><input style="width: 100%" type="text" class="itemText" value="' + RedNaoEscapeHtml(item.label) + '"/></td>' +
            (allowImages ? '<td style="border-style: none;width:50%;"><input type="text" class="itemUrl" style="text-align: left; width: 100%;" value="' + RedNaoEscapeHtml(item.url) + '"/></td>' : '') +
            '       <td style="border-style: none;"><input type="text" class="itemValue" style="text-align: left; width: 50px;" value="' + RedNaoEscapeHtml(item.value) + '"/></td>' +
            '       <td style="border-style: none; text-align: center;vertical-align: middle;"><img style="cursor: pointer; width:15px;height:15px;" class="cloneArrayItem" src="' + smartFormsRootPath + 'images/clone.png" title="Clone"></td>';
        if (!isFirst)
            row += ' <td style="border-style: none !important;text-align: center;vertical-align: middle;"><img style="cursor: pointer;width:15px;height:15px;" class="deleteArrayItem" src="' + smartFormsRootPath + 'images/delete.png" title="Delete"></td>';
        row += '</tr>';
        return row;
    };

    public GetSelector(item) {
        let selected = '';
        if (RedNaoGetValueOrEmpty(item.sel) == 'y')
            selected = 'checked="checked"';
        if (this.AdditionalInformation.SelectorType == 'radio')
            return '<input class="itemSel" type="radio" ' + selected + ' name="propertySelector"/>';
        else
            return '<input class="itemSel" type="checkbox" ' + selected + '/>';
    };

    public UpdateProperty() {
        let processedValueArray = [];
        let self = this;
        let rows = this.ItemsList.find('tr.redNaoRowOption').each(
            function () {
                let jQueryRow = rnJQuery(this);
                let row = self.GetRowData(jQueryRow);
                processedValueArray.push(row);
            }
        );
        this.Manipulator.SetValue(this.PropertiesObject, this.PropertyName, processedValueArray, this.AdditionalInformation);
        this.RefreshElement();
    };


    public GetRowData(jQueryRow) {
        let objectToReturn = {
            label: jQueryRow.find('.itemText').val(),
            value: jQueryRow.find('.itemValue').val(),
            sel: (jQueryRow.find('.itemSel').is(':checked') ? 'y' : 'n'),
            url:''
        };

        if (typeof this.AdditionalInformation.AllowImages != 'undefined' && this.AdditionalInformation.AllowImages == true)
            objectToReturn.url = jQueryRow.find('.itemUrl').val();
        return objectToReturn;
    }
}
