<?php

require_once 'WhereClause.php';
class SmartFormsQuery
{
    private $formId;
    private $selectColumns;
    private $fieldDictionary;

    /**
     * @var WhereClause[]
     */
    private $conditions;
    private $orderByColumns;

    public function __construct($formId)
    {
        $this->formId=$formId;
        $this->selectColumns=array();
        $this->conditions=array();
        $this->fieldDictionary=$this->GetFormDictionary();
    }

    public function AddField($fieldId)
    {
        if(!isset($this->fieldDictionary[$fieldId]))
        {
            echo "Invalid field ".$fieldId;
            return;
        }
        array_push($this->selectColumns,$fieldId);
        return $this;
    }

    /**
     * @param $fieldList array
     */
    public function AddFields($fieldList){

        foreach($fieldList as $field)
            $this->AddField($field);
    }

    public function AddCondition($field,$comparison,$value,$join='and'){
        $whereCondition='';
        if(isset($this->fieldDictionary[$field]))
        {
            $whereCondition=new WhereClause($this->fieldDictionary[$field],$comparison,$value,$join);

        }else

        if($field=='_FormId'||$field=='_SFTimeStamp'||$field=='_UserId')
        {
            $whereCondition=new WhereClause(array('ClassName'=>'','Id'=>$field),$comparison,$value,$join);
        }else{
            echo "Invalid field ".$field;
            return null;
        }

        $this->conditions[]=$whereCondition;
        return $whereCondition;


    }

    public function AddConditions($conditionList){
        for($i=0;$i<count($conditionList);$i++)
        {
            $whereClause=$this->AddCondition($conditionList[$i]['field'],$conditionList[$i]['comparison'],$conditionList[$i]['value']);
            if($i==0)
                $whereClause->openParentheses=true;
            if($i==count($conditionList)-1)
                $whereClause->closeParentheses=true;
            $this->conditions[]=$whereClause;
        }
    }

    public function GetResults(){
        return $this->Execute();
    }

    public function GetCount(){
        $result=$this->Execute("count(*)");
        if(count($result)==0)
            return null;
        return array_pop($result[0]);
    }



    public function GetScalar(){
        $result=$this->Execute();
        if(count($result)==0)
            return null;
        return array_pop($result[0]);
    }

    private function Execute($columns=''){
        $useDefault=false;
        if($columns=='')
        {
            $useDefault=true;
            $columns='data,date,entry_id,form_id';
        }
        global $wpdb;
        $params=array();
        $params[]=$this->formId;
        $conditionText="SELECT $columns FROM ".SMART_FORMS_ENTRY." parent where form_id=%d ";


        if(count($this->conditions)>0)
        {

            foreach ($this->conditions as $condition)
            {
                $conditionText .=$condition->GetText();
            }
        }

        $conditionText=$wpdb->prepare($conditionText,$params);
        $result=$wpdb->get_results($conditionText,'ARRAY_A');

        if($useDefault)
        {
            return $this->ProcessResult($result);
        }else{
            return $result;
        }



    }

    protected  function GetFormDictionary()
    {
        global $wpdb;

        /** @noinspection PhpUndefinedMethodInspection */
        $result=$wpdb->get_results($wpdb->prepare("select element_options from ".SMART_FORMS_TABLE_NAME." where form_id=%d",$this->formId));
        if($result===false||count($result)==0)
            throw new Exception("Couldn't get form information");

        $formInfo=json_decode($result[0]->element_options,true);

        if($formInfo==null)
            throw new Exception("Couldn't get form information");

        /** @noinspection PhpIncludeInspection */
        include_once(SMART_FORMS_DIR.'string_renderer/rednao_string_builder.php');
        $StringBuilder=new rednao_string_builder();

        $formElementsDictionary =array();
        foreach($formInfo as $element)
        {
            $element["__renderer"]=$StringBuilder->GetElementRenderer($element);
            $formElementsDictionary[$element["Id"]]=$element;
        }

        return $formElementsDictionary;
    }

    private function ProcessResult($rows)
    {
        $result=array();
        foreach($rows as $row)
        {
            $entryData=array();

            $values = json_decode($row['data'],true);
            foreach ($this->selectColumns as $fieldId)
            {
                include_once(SMART_FORMS_DIR . 'string_renderer/rednao_string_builder.php');
                $stringBuilder = new rednao_string_builder();
                $entryData[$fieldId] = $stringBuilder->GetStringFromColumn($this->fieldDictionary[$fieldId], $values[$fieldId]);
            }
            $result[]=$entryData;
        }

        return $result;
    }


}


