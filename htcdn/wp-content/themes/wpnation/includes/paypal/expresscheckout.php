<?php 

include('ppfunctions.php');

function startExpressCheckout( $price, $returnLink, $tax ) {
	global $wpdb;

	//Booking plugin settings
	$ppSettings = $wpdb->get_row( "SELECT date_format,paypal_enabled,paypal_currency_code,paypal_api_username,paypal_api_password,paypal_api_signature,sandbox_enabled FROM {$wpdb->prefix}nation_booking_settings" );
	
	$dateFormat = $ppSettings->date_format;
	$paypalCurrency = $ppSettings->paypal_currency_code;
	
	if ( isset($ppSettings->paypal_enabled) && $ppSettings->paypal_enabled == 1 ) {
		if ( isset($ppSettings->sandbox_enabled) && $ppSettings->sandbox_enabled == 1 ) {
			$ppmode = '.sandbox';
		} else {
			$ppmode = '';
		}
		$version = urlencode('109.0');
	}
	
	//Booking plugin settings
	$roomName = $wpdb->get_row( $wpdb->prepare( "SELECT cal_name FROM {$wpdb->prefix}nation_booking_calendars WHERE id=%d", $_POST["room-id"] ) );
	
	if ( $dateFormat == "european" ) {
		$insFormat = "d-m-Y";
	} else if ( $dateFormat == "american" ) {
		$insFormat = "m/d/Y";
	}
	
	$checkIn = date($insFormat, strtotime($_POST["check-in"]));
	$checkOut = date($insFormat, strtotime($_POST["check-out"]));
	
	$result = SetExpressCheckout(
		$ppSettings->paypal_api_username,
		$ppSettings->paypal_api_password,
		$ppSettings->paypal_api_signature,
		$ppmode,
		$price,
		$currencyCode,
		$version,
		$returnLink,
		$returnLink,
		$roomName->cal_name,
		$tax,
		$checkIn,
		$checkOut,
		$_POST["room-number"],
		$paypalCurrency);
	
	
	for ($i=1;$i<=$_POST["room-number"];$i++) {
		$aroom[$i-1] = $_POST["adult-room".$i];
		$croom[$i-1] = $_POST["child-room".$i];
	}
	
	$dateFormat = $ppSettings->date_format;
	
	if ( $dateFormat == "european" ) {
		$insFormat = "d-m-Y";
	} else if ( $dateFormat == "american" ) {
		$insFormat = "m/d/Y";
	}
	
	if ( strtoupper($result['ACK']) == "SUCCESS" || strtoupper($result['ACK']) == "SUCCESSWITHWARNING" ) {	
		$_SESSION['room-id'] = $_POST["room-id"];
		$_SESSION['room-title'] = $roomName;
		$_SESSION['check_in'] = date($insFormat, strtotime($_POST["check-in"]));
		$_SESSION['check_out'] = date($insFormat, strtotime($_POST["check-out"]));
		$_SESSION['no_items'] = $_POST["room-number"];
		$_SESSION['email'] = $_POST["resform-email"];
		$_SESSION['no_adult'] = json_encode($aroom);
		$_SESSION['no_children'] = json_encode($croom);
		$_SESSION['price'] = $price;
		$_SESSION['name'] = $_POST["resform-firstname"];
		$_SESSION['surname'] = $_POST["resform-lastname"];
		$_SESSION['card_type'] = $_POST["resform-cardtype"];
		$_SESSION['cardholder_name'] = $_POST["resform-cardholdername"];
		$_SESSION['card_number'] = $_POST["resform-cardnumber"];
		$_SESSION['expiration_year'] = $_POST["resform-expirationyear"];
		$_SESSION['expiration_month'] = $_POST["resform-expirationmonth"];
		$_SESSION['comments'] = $_POST["resform-comments"];
		$_SESSION['currency_code'] = $paypalCurrency;
		$_SESSION['return_link'] = $returnLink;
		$_SESSION['tax'] = $tax;
		
		//Redirect user to PayPal store with Token received.
		$ppurl ='https://www'.$ppmode.'.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.$result["TOKEN"];
		header('Location: '.$ppurl);
	} else {
		//Show error message
		echo '<div style="color:red"><b>Error : </b>'.urldecode($result["L_LONGMESSAGE0"]).'</div>';
		echo '<pre>';
		print_r($result);
		echo '</pre>';
	}
	
	return $result;
}

?>