<?php 

function SetExpressCheckout($PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode, $amount, $currencyCode, $version, $returnURL, $cancelURL, $roomTitle, $tax, $checkIn, $checkOut, $noItems, $currency) {

	$API_UserName = urlencode($PayPalApiUsername);
	$API_Password = urlencode($PayPalApiPassword);
	$API_Signature = urlencode($PayPalApiSignature);
	$API_Endpoint = "https://api-3t".$PayPalMode.".paypal.com/nvp";
	
	$withoutTax = $amount - $tax;
	$itemCost = $withoutTax/$noItems;
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);
		
	// Turn off the server and peer verification (TrustManager Concept).
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
		
	// &PAYMENTREQUEST_0_CURRENCYCODE=$currencyCode
	$roomTitle = $roomTitle." (".$checkIn. " - ".$checkOut.")";
	
	// Set the API operation, version, and API signature in the request.
	$nvpreq = "METHOD=SetExpressCheckout"."&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature&PAYMENTREQUEST_0_PAYMENTACTION=Sale&L_PAYMENTREQUEST_0_NAME0=$roomTitle&L_PAYMENTREQUEST_0_AMT0=$itemCost&L_PAYMENTREQUEST_0_QTY0=$noItems&PAYMENTREQUEST_0_ITEMAMT=$withoutTax&PAYMENTREQUEST_0_CURRENCYCODE=$currency&PAYMENTREQUEST_0_AMT=$amount&PAYMENTREQUEST_0_TAXAMT=$tax&RETURNURL=$returnURL&CANCELURL=$cancelURL";
		
	// Set the request as a POST FIELD for curl.
	curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);
		
	// Get response from the server.
	$httpResponse = curl_exec($ch);
		
	if(!$httpResponse) {
		exit("$methodName_ failed: ".curl_error($ch).'('.curl_errno($ch).')');
	}
		
	// Extract the response details.
	$httpResponseAr = explode("&", $httpResponse);
		
	$httpParsedResponseAr = array();
	foreach ($httpResponseAr as $i => $value) {
		$tmpAr = explode("=", $value);
		if(sizeof($tmpAr) > 1) {
			$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
		}
	}
		
	if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
		exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
	}
		
	return $httpParsedResponseAr;
}

function DoExpressCheckout( $token, $PayerID, $roomID, $noItems, $sPrice, $currencyCode, $returnURL ) {
	
	global $wpdb;
	//Booking plugin settings
	$ppSettings = $wpdb->get_row( "SELECT paypal_enabled,paypal_api_username,paypal_api_password,paypal_api_signature,sandbox_enabled FROM {$wpdb->prefix}nation_booking_settings" );

	
	if ( isset($ppSettings->sandbox_enabled) && $ppSettings->sandbox_enabled == 1 ) {
		$ppmode = '.sandbox';
	} else {
		$ppmode = '';
	}
	$version = urlencode('72.0');
	
	
	$API_UserName = $ppSettings->paypal_api_username;
	$API_Password = $ppSettings->paypal_api_password;
	$API_Signature = $ppSettings->paypal_api_signature;	
	$API_Endpoint = "https://api-3t".$ppmode.".paypal.com/nvp";
			
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);
		
	// Turn off the server and peer verification (TrustManager Concept).
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
		
	
	
	
	$addData = '&TOKEN='.urlencode($token).
	'&PAYERID='.urlencode($PayerID).
	'&PAYMENTREQUEST_0_PAYMENTACTION='.urlencode("SALE").
	'&PAYMENTREQUEST_0_AMT='.urlencode($sPrice).
	'&RETURNURL='.urlencode($returnURL).
	'&PAYMENTREQUEST_0_CURRENCYCODE='.$currencyCode;
	
	
	// Set the API operation, version, and API signature in the request.
	$nvpreq = "METHOD=DoExpressCheckoutPayment&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature{$addData}";
		
	// Set the request as a POST FIELD for curl.
	curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);
		
	// Get response from the server.
	$httpResponse = curl_exec($ch);
		
	if(!$httpResponse) {
		exit("$methodName_ failed: ".curl_error($ch).'('.curl_errno($ch).')');
	}
		
	// Extract the response details.
	$httpResponseAr = explode("&", $httpResponse);
		
	$httpParsedResponseAr = array();
	foreach ($httpResponseAr as $i => $value) {
		$tmpAr = explode("=", $value);
		if(sizeof($tmpAr) > 1) {
			$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
		}
	}
		
	if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
		exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
	}
		
	return $httpParsedResponseAr;
}

?>