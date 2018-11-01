<?php
include_once(SMART_FORMS_DIR.'string_renderer/rednao_string_builder.php');
include_once(SMART_FORMS_DIR.'smart-forms-ajax.php');


class php_entry_saver_base {
    private $FormId=null;
    private $FormEntryData=null;
    private $FormOptions=null;
    private $ElementOptions=null;
    private $Captcha=null;
    private $ReferenceId=null;
	private $EntryId="";
	private $FormString="";
	private $StringBuilder;
	private $InsertedValuesString=null;


    function __construct($formId,$formString,$captcha,$referenceId="",$formOptions=null,$elementOptions=null)
    {
        $this->FormId = $formId;
        $this->FormString=$formString;
        $this->FormEntryData = json_decode($this->FormString,true);
        $this->Captcha=$captcha;
        $this->ReferenceId=$referenceId;
		$this->FormOptions=$formOptions;
		$this->ElementOptions=$elementOptions;
		$this->StringBuilder=new rednao_string_builder();
    }

    public function ProcessEntry($validateCaptcha=true,$additionalData=array())
    {
		if($this->FormOptions==null||$this->ElementOptions==null)
			$this->GetFormOptions();

		if($this->FormEntryData==null)
		{
			echo '{"message":"'.__("An error occurred, please try again later.").'","refreshCaptcha":"n","success":"n"}';
			return;
		}

        if($this->FormOptions["UsesCaptcha"]=="y"&&$validateCaptcha==true)
        {
			if($this->FormOptions["CaptchaVersion"]=="1")
				if(!$this->CaptchaIsValid())
				{
					echo '{"message":"'.__("Invalid captcha.").'","refreshCaptcha":"y","success":"n"}';
					return;
				}

			$errorMessage="";
			if($this->FormOptions["CaptchaVersion"]=="2")
				if(!$this->CaptchaIsValid2($errorMessage))
				{
					echo '{"message":"'.$errorMessage.'","refreshCaptcha":"y","success":"n"}';
					return;
				}
        }


		if(isset($_FILES)&&count($_FILES)>0)
		{
			if(!$this->SaveFiles())
			{
				echo '{"message":"'.__("An error occurred, please try again later.").'","success":"n"}';
				return;
			}
		}

		require_once SMART_FORMS_DIR.'php_classes/save/insert_entry_base.php';
		require_once SMART_FORMS_DIR.'php_classes/save/pre_insert_entry.php';
		$preInsertedEntry=new PreInsertEntry($this->FormId,$this->FormEntryData,$this->FormOptions,$this->ElementOptions,$additionalData);
		do_action('sf_before_saving_form',$preInsertedEntry);



		if(has_action('sf_before_saving_form'))
		{
			$this->FormEntryData = $preInsertedEntry->FormEntryData;
			$this->FormString = json_encode($this->FormEntryData);
		}

		if($preInsertedEntry->ContinueInsertion==false)
		{
			echo json_encode(
				array(
					"success"=>"n",
					"AdditionalActions"=>$preInsertedEntry->GetSerializedActions()
				)
			);

			return false;
		}

		$this->InsertedValuesString=array();
       	$result=$this->ExecuteInsertions();
        if($this->FormOptions["SendNotificationEmail"]=="y")
		{
			foreach($this->FormOptions["Emails"] as $email)
            	$this->SendFormEmail($email,$this->FormEntryData,$this->ElementOptions,$preInsertedEntry, false);
		}


        if($result==true)
		{
			$postInsertEntry=new PreInsertEntry($this->FormId,$this->FormEntryData,$this->FormOptions,$this->ElementOptions,$additionalData);
			do_action('sf_after_saving_form',$postInsertEntry);


			echo json_encode(
				array(
					"message"=>__("Information submitted successfully."),
					"success"=>"y",
					"AdditionalActions"=>$postInsertEntry->GetSerializedActions(),
					"insertedValues"=>$this->InsertedValuesString,
				)
			);
			return true;
		}
        else
		{
            echo '{"message":"'.__("An error occurred, please try again later.").'","success":"n"}';
			return false;
		}


    }

    private function ExecuteInsertions()
    {
        return $this->InsertEntryData();
    }

	public function GetFormElementsDictionary()
	{
		$formElementsDictionary =array();
		foreach($this->ElementOptions as $element)
			$formElementsDictionary[$element["Id"]]=$element;

		return $formElementsDictionary;
	}

    private function InsertEntryData(){

		global $wpdb;
        $result= $wpdb->insert(SMART_FORMS_ENTRY,array(
            'form_id'=>$this->FormId,
            'date'=>date('Y-m-d H:i:s'),
            'data'=>$this->FormString,
            'ip'=>$_SERVER['REMOTE_ADDR'],
            'reference_id'=>$this->ReferenceId
        ));


		if($result==false)
			return false;
		$this->EntryId=$wpdb->insert_id;
        $this->FormEntryData["_formid"]=$this->EntryId;
		$this->InsertedValuesString["_formid"]=$this->EntryId;
		$result=$this->ParseAndInsertDetail($this->EntryId,$this->FormEntryData,$this->GetFormElementsDictionary());
		return $result;


    }

    public function SendFormEmail($formOptions,$entryData,$elementOptions,$preInsertEntry,$useTestData)
    {
        $EmailText=$formOptions["EmailText"];
        $FromName=$formOptions["FromName"];
        $FromEmail=$formOptions["FromEmail"];
        $ToEmail=$formOptions["ToEmail"];
        $EmailSubject=$formOptions["EmailSubject"];

		if($ToEmail=="")
			return true;

        if($FromName=="")
            $FromName="Wordpress";

        if($EmailSubject=="")
            $EmailSubject="Form Submitted";

        if($ToEmail=="")
            $ToEmail=get_option("admin_email");


		$fieldPattern='/\\[field ([^\\]]+)/';
        preg_match_all($fieldPattern,$EmailText, $matches, PREG_PATTERN_ORDER);

        foreach($matches[1] as $match)
        {
            $value=GetValueByField($this->StringBuilder,$match,$entryData,$elementOptions,$useTestData);
            $EmailText=str_replace("[field $match]",$value,$EmailText);
        }

        if(strpos($FromName,"[field")!==false)
        {
            preg_match_all($fieldPattern,$FromName, $matches, PREG_PATTERN_ORDER);
            foreach($matches[1] as $match)
            {
                $value=GetValueByField($this->StringBuilder,$match,$entryData,$elementOptions,$useTestData);
                $FromName=str_replace("[field $match]",$value,$FromName);
            }
        }

		if(strpos($EmailSubject,"[field")!==false)
		{
			preg_match_all($fieldPattern,$EmailSubject, $matches, PREG_PATTERN_ORDER);
			foreach($matches[1] as $match)
			{
				$value=GetValueByField($this->StringBuilder,$match,$entryData,$elementOptions,$useTestData);
				$EmailSubject=str_replace("[field $match]",$value,$EmailSubject);
			}
		}


		if(strpos($FromEmail,"[field")===0)
		{
			preg_match_all($fieldPattern,$FromEmail, $matches, PREG_PATTERN_ORDER);
			if(count($matches[1])>0)
			{
				$field=$matches[1][0];
				$value=GetValueByField($this->StringBuilder,$field,$entryData,$elementOptions,$useTestData);
				$FromEmail=$value;
			}else{
				$FromEmail="";
				error_log('the to email field was not found');
			}

		}

		if($FromEmail=="")
			$FromEmail=get_option( 'admin_email' );

		if($FromEmail=="")
			$FromEmail="wordpress@wordpress.com";


		$headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $headers.= "From: ".$this->SanitizeName($FromName)." <$FromEmail>". "\r\n";
		$headers.= "Reply-To: $FromName <$FromEmail>";
        if(trim($ToEmail)!="")
        {

			$toEmailArray=explode(",",$ToEmail);
            $finalToEmails=array();
			for($i=0;$i<count($toEmailArray);$i++)
			{
				if(strpos($toEmailArray[$i],"[field")===0)
				{
					preg_match_all($fieldPattern,$toEmailArray[$i], $matches, PREG_PATTERN_ORDER);
					if(count($matches[1])>0)
					{
						$field=$matches[1][0];

						//$value=GetValueByField($this->StringBuilder,$field,$entryData,$elementOptions,$useTestData);
                        $value=$this->GetEmailsFromField($formOptions, $field,$preInsertEntry);
						$toEmailArray=array_merge($toEmailArray,$value);
					}


				}else
                {
                    if(trim($toEmailArray[$i])!="")
                        array_push($finalToEmails,$toEmailArray[$i]);
                }

			}
			$toEmailArray=array_filter($toEmailArray);

            $emailParams=array(
                            "EmailContent"=>array(
                                "ToEmail"=>$finalToEmails,
                                "Subject"=>$EmailSubject,
                                "EmailText"=>$EmailText,
                                "Headers"=>$headers,
                                "Attachments"=>Array()
                            ),
                            "FormOptions"=>$formOptions,
							"InsertEntry"=>$preInsertEntry
                        );


            $emailParams=apply_filters('smart_forms_before_sending_email',$emailParams);
			$emailContent=$emailParams["EmailContent"];
            return wp_mail($emailContent["ToEmail"],$emailContent["Subject"],$emailContent["EmailText"],$emailContent["Headers"],$emailContent["Attachments"]);
        }

        return false;
    }

    private function SanitizeName($name)
    {
        $rule = array("\r" => '',
            "\n" => '',
            "\t" => '',
            '"'  => "'",
            '<'  => '[',
            '>'  => ']',
        );

        return trim(strtr($name, $rule));
    }

    private function GetEmailsFromField($emailOptions,$fieldId,$preInsertEntry)
    {
        $fieldData=$preInsertEntry->GetField($fieldId);
        if($fieldData["ClassName"]=="rednaomultipleradios"||$fieldData["ClassName"]=="rednaomultiplecheckboxes"
            ||$fieldData["ClassName"]=="rednaoselectbasic"||$fieldData["ClassName"]=="rednaosearchablelist")
        {
            $selectedValues= $preInsertEntry->GetFieldValue($fieldId, SFSerializationType::ARRAYLIST);
            if(!isset($emailOptions["MultipleOptionsToEmails"][$fieldId]))
                return array();
            $emailsToReturn=array();
            $optionsToEmail=$emailOptions["MultipleOptionsToEmails"][$fieldId];
            foreach($selectedValues as $selectedValue)
                foreach($optionsToEmail as $optionToEmail)
                    if($selectedValue["label"]==$optionToEmail["Label"])
                    {
                        $emails=$optionToEmail["EmailTo"];
                        if(trim($emails)=="")
                            continue;
                        $emailsToReturn=array_merge($emailsToReturn,explode(",",$emails));
                    }

            return $emailsToReturn;


        }
        else
        {
            $text=$preInsertEntry->GetFieldValue($fieldId, SFSerializationType::TEXT);
            if(trim($text)=="")
                return array();
            else
                return array($text);

        }


    }


    private function CaptchaIsValid()
    {
        $Message="";
        if($this->Captcha==""||$this->Captcha==null||$this->Captcha["response"]=="")
            return false;

        $captchaPost=$this->Captcha;
        $captcha=ARRAY();
        $captcha["challenge"]=stripslashes($captchaPost["challenge"]);
        $captcha["response"]=stripslashes($captchaPost["response"]);
        $captcha["remoteip"]=$_SERVER['REMOTE_ADDR'];
        $captcha["privatekey"]="6Lf2J-wSAAAAAOH6uSmSdx75ZLRpDIfvSeAdx9ST";

        $args=Array();

        $args['headers']=Array
        (
            'Content-Type'=>'application/x-www-form-urlencoded;',
            'Method'=>'Post'
        );
        $args['body']=$captcha;
        $res=wp_remote_post('http://www.google.com/recaptcha/api/verify',$args);
        if(strpos($res["body"],"true")!==0)
            return false;

        return true;
    }

	private function CaptchaIsValid2(&$errorMessage)
	{
		$errorMessage="Invalid captcha";
		$Message="";
		if($this->Captcha==""||$this->Captcha==null||$this->Captcha["response"]=="")
			return false;

		$captchaPost=$this->Captcha;
		$captcha=ARRAY();
		$captcha["response"]=stripslashes($captchaPost["response"]);
		$captcha["remoteip"]=$_SERVER['REMOTE_ADDR'];
		$captcha["secret"]=$this->FormOptions['FieldServerOptions']['captcha2']['SecretKey'];
		$args=Array();
		$args['headers']=Array
		(
			'Content-Type'=>'application/x-www-form-urlencoded;',
			'Method'=>'Post'
		);
		$args['body']=$captcha;
		$res=wp_remote_post('https://www.google.com/recaptcha/api/siteverify',$args);
		$response=json_decode($res["body"],true);
		if($response["success"]===true)
			return true;
		if($response["error-codes"][0]=="invalid-input-secret")
			$errorMessage="Invalid secret key";

		return false;
	}

    private function GetFormOptions()
    {
        global $wpdb;
        $result=$wpdb->get_results($wpdb->prepare("select form_options,element_options from ".SMART_FORMS_TABLE_NAME." where form_id=%d",$this->FormId));

        if(count($result)>0){
            $this->FormOptions=json_decode($result[0]->form_options,true);
            $this->ElementOptions=json_decode($result[0]->element_options,true);
        }
    }

	private function SaveFiles()
	{
		require_once(SMART_FORMS_DIR . "php_classes/file_upload/physical_file_uploader.php");
		$fileUploader=new physical_file_uploader();
		$result= $fileUploader->UploadFiles($this->FormEntryData);
		if($result["success"]==true)
		{
			$this->FormEntryData=$result["entryData"];
			$this->FormString=json_encode($result["entryData"]);
			return true;
		}
		return false;
	}

	private function DeleteInsertedEntry()
	{
		global $wpdb;
		$wpdb->query($wpdb->prepare("delete from ".SMART_FORMS_ENTRY." WHERE entry_id=%d",$this->EntryId));
	}

	public function ParseAndInsertDetail($entryId,$entryData,$formElementsDictionary)
	{

		foreach($entryData as $key=>$unprocessedValue)
		{
			if(!isset($formElementsDictionary[$key]))
				continue;

			$fieldConfiguration=$formElementsDictionary[$key];
			$value=$this->StringBuilder->GetStringFromColumn($fieldConfiguration,$unprocessedValue);
			if($fieldConfiguration["ClassName"]!="sfFileUpload")
				$this->InsertedValuesString[$key]=$value;
			$exValue=$this->StringBuilder->GetExValue($fieldConfiguration,$unprocessedValue);
			$dateValue=$this->StringBuilder->GetDateValue($fieldConfiguration,$unprocessedValue);
			if($dateValue!=null)
				$dateValue=date('Y-m-d',$dateValue);
			$jsonValue=json_encode($unprocessedValue);
			if(!$this->InsertDetailRecord($entryId,$key,$value,$jsonValue,$exValue,$dateValue))
				return true;
		}

		return true;
	}

	private function InsertDetailRecord($entry_id, $fieldId, $value, $jsonValue,$exValue,$dateValue)
	{
		global $wpdb;
		$arrayToInsert=array_merge(array(
			"entry_id"=>$entry_id,
			"field_id"=>$fieldId,
			"json_value"=>$jsonValue,
			"value"=>$value
		),$exValue);

		if($dateValue!=null)
			$arrayToInsert["datevalue"]=$dateValue;
		return $wpdb->insert(SMART_FORMS_ENTRY_DETAIL,$arrayToInsert);
	}


}

function SmartFormsOverwriteHandleUpload($upload)
{

}

function GetPHPFormula($formula)
{
	$fieldPattern='/\\[field ([^\\]]+)/';
	preg_match_all($fieldPattern,$formula, $matches, PREG_PATTERN_ORDER);

	foreach($matches[1] as $match)
	{
		$formula=str_replace("[field $match]","formData[$match]['amount']",$formula);
	}

	return $formula;
}

function SmartFormsGetFormattedFormValues($formValues,$elementOptions)
{
	include_once(SMART_FORMS_DIR.'string_renderer/rednao_string_builder.php');
	include_once(SMART_FORMS_DIR.'smart-forms-ajax.php');
	$stringBuilder=new rednao_string_builder();
	$processedData=array();
	foreach($formValues as $key=>$value)
	{
		$element=null;
		foreach($elementOptions as $item)
		{
			if($item["Id"]==$key)
			{
				$element=$item;
				break;
			}
		}

		if($element!=null)
		{
			 $processedData[]=array("name"=>$element["Label"],
			 						"value"=>$stringBuilder->GetStringFromColumn($element,$value));
		}
	}

	return $processedData;

}