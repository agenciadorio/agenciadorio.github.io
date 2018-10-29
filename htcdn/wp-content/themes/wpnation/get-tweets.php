<?php
	session_start();
	require_once("twitteroauth/twitteroauth.php"); //Path to twitteroauth library
	
	$twitteruser = $_GET['twitterusername'];
	$notweets = $_GET['displaylimit'];
	$consumerkey = "enter_here_your_consumer_key";
	$consumersecret = "enter_here_your_consumer_secret_key";
	$accesstoken = "enter_here_your_access_token";
	$accesstokensecret = "enter_here_your_access_token_secret";
 
	function getConnectionWithAccessToken($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) {
		$connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);
		return $connection;
	}
  
	$connection = getConnectionWithAccessToken($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);
 
	$tweets = $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=".$twitteruser."&count=".$notweets);
	
	echo json_encode($tweets);
	
	
?>