<?php

class BunnyCDNFilter
{
	var $baseUrl = null;
	var $cdnUrl = null;
	var $excludedPhrases = null;
	var $directories = null;

	/**
		Create a new BunnyCDNFilter object.

		@param $baseUrl string 			- The base URL of the website that we will be looking to replace.
		@param $cdnUrl string 			- The CDN url that will be used to replace the base URL.
		@param $excludedPhrases array 	- An array of phrases that will be used to exclude specific URLs
	*/
	function __construct($baseUrl, $cdnUrl, $directories, $excludedPhrases) 
	{
		$this->baseUrl = $baseUrl;
		$this->cdnUrl = $cdnUrl;
		
		// Prepare the excludes
		if(trim($excludedPhrases) != '')
		{
			$this->excludedPhrases = explode(',', $excludedPhrases);
			$this->excludedPhrases = array_map('trim', $this->excludedPhrases);
		}

		// Validate the directories
		if (trim($directories) == '') 
		{
			$directories = BUNNYCDN_DEFAULT_DIRECTORIES;
		}
		// Create the array
		$directoryArray = explode(',', $directories);
		if(count($directoryArray) > 0)
		{
			$directoryArray = array_map('trim', $directoryArray);
			$directoryArray = array_map('quotemeta', $directoryArray);
		}
		$this->directories = $directoryArray;
	}

	/**
		The rewrite method called during the rewrite preg_replace_callback call.
		It validates and replaces the old base URL with the CDN url.
	*/
    protected function rewriteUrl($asset) 
	{
		$foundUrl = $asset[0];
		// If the URL contains an excluded phrase don't rewrite it
		foreach($this->excludedPhrases as $exclude)
		{
			if($exclude == '')
				continue;

			if(stristr($foundUrl, $exclude) != false)
				return $foundUrl;
		}
		
		// If this is NOT a relative URL
		if (strstr($foundUrl, $this->baseUrl)) 
		{
			return str_replace($this->baseUrl, $this->cdnUrl, $foundUrl);
		}

		return $this->cdnUrl . $foundUrl;
	}


	/**
		Performs the actual rewrite logic
	*/
	protected function rewrite($html) 
	{
		
		// Prepare the included directories regex
		$directoriesRegex = implode('|', $this->directories);
		$regex = '#(?<=[(\"\'])(?:'. quotemeta($this->baseUrl) .')?/(?:((?:'.$directoriesRegex.')[^\"\')]+)|([^/\"\']+\.[^/\"\')]+))(?=[\"\')])#';
		return preg_replace_callback($regex, array(&$this, "rewriteUrl"), $html);
	}
	
	/**
		Begins the rewrite process with the currently configured settings
	*/
	public function startRewrite()
	{
		ob_start(array($this,'rewrite'));
	}
}
