<?php
add_filter('smart-forms-get-email-fixed-field-listener','smart_forms_email_current_date');
function smart_forms_email_current_date($array)
{
	array_push($array,
		array(
				"Label"=>__("Current Date"),
				"Op"=>"CurrentDate",
				"Parameters"=>array(
					"Format"=>"m/d/y"
				)
			)
	);

	return $array;
}
add_filter('smart-forms-fixed-field-value-CurrentDate','smart_forms_get_fixed_fields_CurrentDate',10,3);
function smart_forms_get_fixed_fields_CurrentDate($fieldParameters,$formData,$elementOptions)
{
	return date($fieldParameters["Format"]);
}




add_filter('smart-forms-get-email-fixed-field-listener','smart_forms_email_original_url');
function smart_forms_email_original_url($array)
{
	array_push($array,
		array(
			"Label"=>__("Original URL"),
			"Op"=>"OriginalUrl",
			"Parameters"=>array(
			)
		)
	);

	return $array;
}
add_filter('smart-forms-fixed-field-value-OriginalUrl','smart_forms_get_fixed_fields_OriginalUrl',10,3);
function smart_forms_get_fixed_fields_OriginalUrl($fieldParameters,$formData,$elementOptions)
{
	return $_POST['requestUrl'];
}







add_filter('smart-forms-get-email-fixed-field-listener','smart_forms_email_form_id');
function smart_forms_email_form_id($array)
{
    array_push($array,
        array(
            "Label"=>__("Form Id"),
            "Op"=>"FormId",
            "Parameters"=>array(
            )
        )
    );

    return $array;
}
add_filter('smart-forms-fixed-field-value-FormId','smart_forms_get_fixed_fields_FormId',10,3);
function smart_forms_get_fixed_fields_FormId($fieldParameters,$formData,$elementOptions)
{
    return $formData['_formid'];
}



add_filter('smart-forms-get-email-fixed-field-listener','smart_forms_email_ip');
function smart_forms_email_ip($array)
{
	array_push($array,
		array(
			"Label"=>__("IP"),
			"Op"=>"IP",
			"Parameters"=>array(
			)
		)
	);

	return $array;
}
add_filter('smart-forms-fixed-field-value-IP','smart_forms_get_fixed_fields_IP',10,3);
function smart_forms_get_fixed_fields_IP($fieldParameters,$formData,$elementOptions)
{
	return $_SERVER['REMOTE_ADDR'];
}



add_filter('smart-forms-get-email-fixed-field-listener','smart_forms_logged_user');
function smart_forms_logged_user($array)
{
	array_push($array,
		array(
			"Label"=>__("Username"),
			"Op"=>"USERNAME",
			"Parameters"=>array(
			)
		)
	);

	return $array;
}
add_filter('smart-forms-fixed-field-value-USERNAME','smart_forms_get_fixed_fields_username',10,3);
function smart_forms_get_fixed_fields_username($fieldParameters,$formData,$elementOptions)
{
	$current_user = wp_get_current_user();
	if(!$current_user)
		return '';

	return $current_user->user_login;
}



add_filter('smart-forms-get-email-fixed-field-listener','smart_forms_fieldsummary');
function smart_forms_fieldsummary($array)
{
	array_push($array,
		array(
			"Label"=>__("Field Summary"),
			"Op"=>"FIELDSUMMARY",
			"Parameters"=>array(
			)
		)
	);

	return $array;
}
add_filter('smart-forms-fixed-field-value-FIELDSUMMARY','smart_forms_get_fixed_fields_FIELDSUMMARY',10,3);
function smart_forms_get_fixed_fields_FIELDSUMMARY($fieldParameters,$formData,$elementOptions)
{
	include_once(SMART_FORMS_DIR.'string_renderer/rednao_string_builder.php');
	include_once(SMART_FORMS_DIR.'smart-forms-ajax.php');
	$stringBuilder=new rednao_string_builder();

	$summary="";
	foreach($elementOptions as $option){
		$fieldId=$option["Id"];
		$value=$stringBuilder->GetStringFromColumn($option,$formData[$fieldId]);
		if(trim($value)!==""){
			$summary.="<strong>".htmlspecialchars($option["Label"]).":</strong>".$value."<br/>";
		}
	}

	return $summary;
}
