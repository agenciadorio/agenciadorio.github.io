<?php
class physical_file_uploader {
	function __construct()
	{
		require_once(ABSPATH . "wp-admin" . '/includes/file.php');
	}

	public function UploadFiles($entryData)
	{
		foreach($_FILES as $key=>$value)
		{
			$splittedFiles=explode("@",$key);
			$fieldName=$splittedFiles[1];
			$imageNumber=$splittedFiles[2];
			$fieldElement=&$entryData[$fieldName][$imageNumber-1];

			$fileName=uniqid("",true).".".pathinfo($value["name"], PATHINFO_EXTENSION);//$fileName.".".pathinfo($value["name"], PATHINFO_EXTENSION);
			$value["name"]=$fileName;
			$path= wp_handle_upload($value,array(
				'test_form' => false
			));
			if($path&&!isset($path["error"]))
			{
				$fieldElement["path"]=$path["url"];
				$fieldElement["type"]=$path["type"];
				$fieldElement["ppath"]=$path["file"];
			}
			else
				return array("success"=>false,
							 "entryData"=>"");

		}
		return array("success"=>true,
					"entryData"=>$entryData);

	}

} 