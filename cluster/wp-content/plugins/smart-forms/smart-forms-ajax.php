<?php

function GetPostValue($parameterName)
{
    if(isset($_POST[$parameterName]))
        if(is_array(($_POST[$parameterName])))
            return $_POST[$parameterName];
        else
            return stripslashes($_POST[$parameterName]);

    return "";
}

function rednao_smart_forms_export(){

        require_once SMART_FORMS_DIR.'smart-forms-export-form.php';


    die();
}

function smart_forms_export(){
    require_once 'smart-forms-exporter.php';
}

function smart_forms_skip_tutorial(){
    update_site_option('smart_forms_show_tutorial','y');
}

function smart_forms_save_settings(){
    $options=GetPostValue('options');
    if($options=='')
        return;
    foreach($options as $value)
    {
        update_site_option($value['key'],$value['value']);
    }
}

function rednao_smart_forms_save()
{
    $form_id=GetPostValue("id");
    $element_options=GetPostValue("element_options");
    $form_options=GetPostValue("form_options");
    $client_form_options=GetPostValue("client_form_options");
    $donation_email=GetPostValue("donation_email");

    //$form_options=str_replace("\\\"","\"",$form_options);
    $formParsedValues=json_decode($form_options);


    if($formParsedValues->Name=="")
    {
        $message=__("Name is mandatory");
    }else{

            global $wpdb;
            if($form_id=="0")
            {
                $count= $wpdb->get_var($wpdb->prepare("SELECT count(*) FROM ".SMART_FORMS_TABLE_NAME." where form_name=%s",$formParsedValues->Name));

                if($count>0)
                {
                    $message=__("Form name already exists");

                }else
                {
                    $values=array('form_name'=>$formParsedValues->Name,
                        'element_options'=>$element_options,
                        'form_options'=>$form_options,
                        'client_form_options'=>$client_form_options,
                        'donation_email'=>$donation_email
                    );

                    $wpdb->insert(SMART_FORMS_TABLE_NAME,$values);
                    $form_id=$wpdb->insert_id;
                    $message="saved";
                    delete_transient("rednao_smart_forms_$form_id");
                }
            }else
            {
                $wpdb->update(SMART_FORMS_TABLE_NAME,array(
                    'form_name'=>$formParsedValues->Name,
                    'element_options'=>$element_options,
                    'form_options'=>$form_options,
                    'client_form_options'=>$client_form_options,
                    'donation_email'=>$donation_email
                ),array("form_id"=>$form_id));
                $message="saved";
                delete_transient("rednao_smart_forms_$form_id");

            }

        }


    echo "{\"FormId\":\"$form_id\",\"Message\":\"$message\"}";

    die();
}


function rednao_smart_form_short_code_setup()
{
    if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
        return;
    }

    global $wpdb;

	$shortCodeOptions=array();
	array_push($shortCodeOptions,array(
		"Name"=>"Forms",
		"ShortCode"=>"sform",
		"Elements"=>array()
	));

	$result=$wpdb->get_results("SELECT form_id,form_name FROM ".SMART_FORMS_TABLE_NAME);
    //echo "[{\"Id\":\"0\",\"Name\":\"Select a Form\"}";
    foreach($result as $key=>$row)
    {
		array_push($shortCodeOptions[0]["Elements"],array(
			"Id"=>$row->form_id,
			"Name"=>$row->form_name
		));

    }

	$shortCodeOptions=apply_filters("smart_forms_get_short_code_options",$shortCodeOptions);

	echo json_encode($shortCodeOptions);
    die();
}


function rednao_smart_forms_save_form_values()
{
    include_once(SMART_FORMS_DIR.'php_classes/save/php_entry_saver_base.php');

    $form_id=GetPostValue("form_id");
    $formString=GetPostValue("formString");
	$captcha="";
	if(isset($_POST["captcha"]))
		if(is_array($_POST["captcha"]))
			$captcha=$_POST["captcha"];
		else
			$captcha=stripslashes($_POST["captcha"]);

    $phpEntry=new php_entry_saver_base($form_id,$formString,$captcha);
    $phpEntry->ProcessEntry();
	die();
}

function rednao_smart_forms_get_form_element_info()
{
	$formId=GetPostValue("formId");
	if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
		return;
	}

	global $wpdb;
	$result=$wpdb->get_var($wpdb->prepare("SELECT element_options FROM ".SMART_FORMS_TABLE_NAME.' where form_id=%d',$formId));

	echo '{"elementsInfo":'.$result.'}';

	die();
}

function rednao_smart_forms_get_form_options()
{
    $formId=GetPostValue("formId");
    if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
        return;
    }

    global $wpdb;
    $result=$wpdb->get_results($wpdb->prepare("SELECT form_options,element_options FROM ".SMART_FORMS_TABLE_NAME.' where form_id=%d',$formId));

    global $current_user;
    echo '{"formOptions":'.$result[0]->form_options;//.'}';
    echo ',"elementOptions":'.$result[0]->element_options.
        ',"CurrentEmail":"'.$current_user->user_email.'"'.
        '}';
    /*echo json_encode(array(
        "formOptions"=>,
        "element_options"=>$result[0]->element_options
        )
    );*/


    die();
}

function rednao_get_fixed_field_value($match,$entryData,$elementOptions,$useTestData)
{
	require_once(SMART_FORMS_DIR.'filter_listeners/fixed-field-listeners.php');
    $match=str_replace("'","\"",$match);
	$fixedFieldParameters=json_decode($match,true);
	if($fixedFieldParameters==null)
	{
		error_log('error parsing fixed field');
		return '';
	}

	try{
		$value=apply_filters("smart-forms-fixed-field-value-".$fixedFieldParameters["Op"],$fixedFieldParameters,$entryData,$elementOptions,$useTestData);
		if($value==$fixedFieldParameters)
			return "";
		if($value==null)
		    $value='';
		return strval($value);
	}catch(Exception $e)
	{
			error_log("Couldn't format date ".$e->getMessage());
	}

	return "";
}

function GetValueByField($stringBuilder,$match,$entryData,$elementOptions,$useTestData)
{
	if(strpos(trim($match),'{')===0)
	{
		return rednao_get_fixed_field_value($match,$entryData,$elementOptions,$useTestData);
	}

    foreach($entryData as $key=>$value)
    {
        $element=null;
        if($key!=$match)
            continue;

        $element=null;
        foreach($elementOptions as $item)
        {
            if($item["Id"]==$key)
            {
                $element=$item;
                break;
            }
        }
        if($element==null)
            continue;

        $value= $stringBuilder->GetStringFromColumn($element,$value);
        if($value==""&&$useTestData)
            $value="sample text";
        return $value;
    }

    if($useTestData)
        return "sample text";
}

function rednao_smart_forms_send_files(){
    require_once SMART_FORMS_DIR.'smart_forms_uploader.php';
    die();
}

function rednao_smart_forms_entries_list()
{
    $startDate=GetPostValue("startDate");
    $endDate=GetPostValue("endDate");
    $formId=GetPostValue("form_id");

    $startDate=date('Y-m-d H:i:s', strtotime($startDate));
    $endDate=date('Y-m-d H:i:s', strtotime($endDate .' +1 day'));

    $query="select concat(year(date),'-',month(date),'-' ,day(date)) date,date,entry_id,data from ".SMART_FORMS_ENTRY."
        where date between %s and %s and form_id=%d";


    global $wpdb;
    $result=$wpdb->get_results($wpdb->prepare($query,$startDate,$endDate,$formId));
    $isFirstRecord=true;

    echo '{"entries":[';
    foreach($result as $row)
    {
        if($isFirstRecord)
           $isFirstRecord=false;
        else
            echo ",";

        $data=$row->data;
        if($data===NULL||trim($data)===null)
            $data="{}";

        echo '{"date":"'.$row->date.'","entry_id":"'.$row->entry_id.'","data":'.$data."}";

    }
    echo '],"formOptions":';

    $query="select element_options from ".SMART_FORMS_TABLE_NAME." where form_id=%d";
    $elementOptions=$wpdb->get_var($wpdb->prepare($query,$formId));
    echo $elementOptions.'}';


    die();
}

function rednao_smart_form_send_test_email()
{
    $FromEmail=GetPostValue("FromEmail");
    $FromName=GetPostValue("FromName");
    $ToEmail=GetPostValue("ToEmail");
    $EmailSubject=GetPostValue("EmailSubject");
    $EmailText=GetPostValue("EmailText");
    $ReplyTo=GetPostValue("ReplyTo");
    $Bcc=GetPostValue("Bcc");
    $elementOptions=GetPostValue("element_options");
    $pdfs=GetPostValue("PDFS");


    if(!is_array($elementOptions))
        $elementOptions=json_decode($elementOptions,true);


    $valueArray=Array(
        "FromEmail"=>$FromEmail,
        "FromName"=>$FromName,
        "ToEmail"=>$ToEmail,
        "EmailSubject"=>$EmailSubject,
        "EmailText"=>$EmailText,
        "ReplyTo"=>$ReplyTo,
        "PDFS"=>$pdfs,
        "Bcc"=>$Bcc
    );
    $entryData=Array();


    if($EmailText=="")
    {
        echo '{"Message":"'.__("Email text can't be empty").'"}';
        die();
    }

	include_once(SMART_FORMS_DIR.'php_classes/save/php_entry_saver_base.php');
	$entrySaver=new php_entry_saver_base("","","");
    if($entrySaver->SendFormEmail($valueArray,$entryData,$elementOptions,null,true))
        echo '{"Message":"'.__("Email sent successfully").'"}';
    else
        echo '{"Message":"'.__("There was an error sending the email, please check the configuration").'"}';
    die();
}


function rednao_smart_forms_submit_license()
{
    include_once(SMART_FORMS_DIR.'smart-forms-license.php');

    $email=GetPostValue("email");
    $key=GetPostValue("key");

    $license=smart_forms_check_license($email,$key,$error);
    if($license["is_valid"])
    {
        echo '{"IsValid":"y","Message":"'.__("License submitted successfully, thank you!!").'","licenseType":"'.$license["licenseType"].'"}';
    }else
    {
        if($error==null)
        {
            echo '{"IsValid":"n",  "Message":"'.__("Invalid user or license").'"}';
        }else
            echo '{"IsValid":"n","Message":"'.__("An error occurred $error").'"}';
    }

    die();
}

function rednao_smart_forms_execute_op()
{
	$id=$_POST["TransactionId"];
	$oper=$_POST["oper"];

    if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
        return;
    }
	if($oper=="del")
	{
		global $wpdb;
		if($wpdb->query($wpdb->prepare("delete from ".SMART_FORMS_ENTRY." WHERE entry_id=%d",$id))>0)
        {
            $wpdb->query($wpdb->prepare("delete from ".SMART_FORMS_ENTRY_DETAIL." WHERE entry_id=%d",$id));
            echo '{"success":"1"}';
        }
		else
			echo '{"success":"0","message":"'.__("Could not delete row").'"}';
		die();
	}

	if($oper=='massDelete')
    {
        global $wpdb;
        $ids=GetPostValue('entriesToDelete');
        $ids=json_decode($ids);
        $idwhere='';
        foreach($ids as $id)
        {
            if($idwhere!='')
                $idwhere.=',';
            $idwhere.=$wpdb->prepare('%d',$id);
        }

        $wpdb->query('delete from '.SMART_FORMS_ENTRY.' where entry_id in('.$idwhere.')');
        $wpdb->query('delete from '.SMART_FORMS_ENTRY_DETAIL.' where entry_id in('.$idwhere.')');
        echo '{"success":"1"}';
        die();

    }

}

function rednao_smart_forms_dont_show_again()
{
    update_site_option('sf_dont_show_again',true);
}

function rednao_smart_forms_send_test()
{
    if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
        return;
    }

    require_once SMART_FORMS_DIR.'php_classes/smart_forms_troubleshoot/smart_forms_email.php';
    switch($_POST["Id"])
    {
        case "basic":
            $smartFormsEmail=new smart_forms_email_troubleshoot_basic();
            break;
        case "custom":
            $smartFormsEmail=new smart_forms_email_troubleshoot_custom_smtp();
            break;
    }
    if($smartFormsEmail->Start())
        echo json_encode(
            array(
                "Passed"=>'y'
        ));
    else
        echo json_encode(
            array(
                "Passed"=>'n',
                "Message"=>$smartFormsEmail->LatestError
            ));
    die();
}


function rednao_smart_forms_edit_form_values()
{
    $entryId=GetPostValue("entryId");
    $entryString=GetPostValue("entryString");
    $elementOptions=GetPostValue("elementOptions");


    include_once(SMART_FORMS_DIR.'php_classes/save/php_entry_editor.php');
    $phpEditor=new php_entry_editor();
    echo json_encode(array(result=>$phpEditor->execute_editor($entryId,$entryString,$elementOptions)));
    die();
}