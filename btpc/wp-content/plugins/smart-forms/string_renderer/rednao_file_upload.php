<?php
/**
 * Created by PhpStorm.
 * User: edseventeen
 * Date: 11/28/13
 * Time: 8:58 PM
 */

class rednao_file_upload extends  rednao_base_elements_renderer{

    public function GetString($formElement,$entry)
    {
		$html="<p>";
		$firstElement=true;
		foreach($entry as $value)
		{
			if(!isset($value["path"]))
				continue;
			if($firstElement)
				$firstElement=false;
			else
				$html.="<br/>";
			$html.='<a href="'.$value["path"].'">'.$value["path"].'</a>';
		}

		$html.="</p>";
		return $html;
    }

	public function GetExValues($formElement, $entry)
	{
		$html="";
		$firstElement=true;
		foreach($entry as $value)
		{
			if($firstElement)
				$firstElement=false;


			$html.= $value["path"].';;;';
		}

		return array(
			"exvalue1"=>htmlspecialchars($html),
            "exvalue2"=>$value["path"],
            "exvalue3"=>$value["ppath"],
            "exvalue4"=>"",
            "exvalue5"=>"",
            "exvalue6"=>""
		);
	}
}