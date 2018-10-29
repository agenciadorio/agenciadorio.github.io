<?php
/**
 * Created by PhpStorm.
 * User: edseventeen
 * Date: 11/28/13
 * Time: 8:52 PM
 */

class rednao_signature_renderer extends rednao_base_elements_renderer {


    public function GetString($formElement,$entry)
    {
        if($entry['image']!=''){
            return '<img alt="Signature could not be shown in this email provider" style="width:100%" src="data:image/png;base64,'.$entry["image"].'"/>';
        }
        if($entry['native']!='')
        {
            return '<img alt="Signature could not be shown in this email provider" style="width:100%" src="data:image/svg+xml;base64,'.$entry["value"].'"/>';
        }
        return '';

    }

	public function GetExValues($formElement, $entry)
	{
		return array(
			"exvalue1"=>$this->GetString($formElement,$entry),
            "exvalue2"=>"",
            "exvalue3"=>"",
            "exvalue4"=>"",
            "exvalue5"=>"",
            "exvalue6"=>""
		);
	}
}