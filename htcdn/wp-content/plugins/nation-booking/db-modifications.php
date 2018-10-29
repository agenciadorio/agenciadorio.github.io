<?php

// Calendar database modifications


function nation_booking_add_calendar_func() {
	global $wpdb;
	
	if ( !current_user_can( 'manage_options' ) ) {
      wp_die( __('You are not allowed to be on this page.','nation') );
	}
	
	// Check that nonce field
	check_admin_referer( 'nation_add_calendar_verify' );
	
	if ( isset($_POST["cal_name"]) ) {
		if ( isset( $_POST["cal_min_price"] ) ) { 
			$min_price = $_POST["cal_min_price"];
		} else {
			$min_price = '';
		}		
		$wpdb->insert($wpdb->prefix.'nation_booking_calendars', array('cal_name' => $_POST["cal_name"], 'min_price' => $min_price ));
	}
	
	wp_redirect(  admin_url( "admin.php?page=nation-booking" ) );
	exit;
}
add_action( 'admin_post_nation_booking_add_calendar', 'nation_booking_add_calendar_func' );


function nation_booking_edit_calendar_func() {
	global $wpdb;
	
	if ( !current_user_can( 'manage_options' ) ) {
      wp_die( __('You are not allowed to be on this page.','nation') );
	}
	
	// Check that nonce field
	check_admin_referer( 'nation_edit_calendar_verify' );
	
	if ( isset($_POST["cal_name"]) ) {
		if ( isset( $_POST["cal_min_price"] ) ) { 
			$min_price = $_POST["cal_min_price"];
		} else {
			$min_price = '';
		}		
		$wpdb->update( $wpdb->prefix.'nation_booking_calendars', array('cal_name' => $_POST["cal_name"], 'min_price' => $min_price ), array('id'=>$_POST["cal_id"]) );
	}
	
	wp_redirect(  admin_url( "admin.php?page=nation-booking" ) );
	exit;
}
add_action( 'admin_post_nation_booking_edit_calendar', 'nation_booking_edit_calendar_func' );


function nation_booking_delete_calendar_func() {
	global $wpdb;
	
	if ( !current_user_can( 'manage_options' ) ) {
      wp_die( __('You are not allowed to be on this page.','nation') );
	}
	
	// Check that nonce field
	check_admin_referer( 'nation_delete_calendar_verify' );
	
	if ( isset($_POST["cal_id"]) ) {		
		$wpdb->delete( $wpdb->prefix.'nation_booking_calendars', array( 'ID' => $_POST["cal_id"] ), array( '%d' ) );
	}
	
	wp_redirect(  admin_url( "admin.php?page=nation-booking" ) );
	exit;
}
add_action( 'admin_post_nation_booking_delete_calendar', 'nation_booking_delete_calendar_func' );


// Reservation Database modifications 
function nation_booking_add_reservation_func() {
	global $wpdb;
	
	if ( !current_user_can( 'manage_options' ) ) {
      wp_die( 'You are not allowed to be on this page.' );
	}
	// Check nonce field
	check_admin_referer( 'nation_add_reservation_verify' );
	
	$dateformat = $wpdb->get_var( "SELECT date_format FROM ".$wpdb->prefix."nation_booking_settings");
	
	//Calculate price of reservation
	$datesArray = createDateRangeArray( $_POST["check-in"], $_POST["check-out"], $dateformat );
	
	$errorText = "";
	$error = 0;
	$price = 0;
	
	$timeNow=strtotime('today');
		
	if ( $datesArray ) { 
		for ( $i=0;$i<count($datesArray)-1;$i++ ) {
					
			//Process dates
			$day = date("d", strtotime($datesArray[$i]));
			$month = date("m", strtotime($datesArray[$i]));
			$year = date("Y", strtotime($datesArray[$i]));
			
			$time = strtotime($datesArray[$i]);
						
			$getPriceAvailability = $wpdb->get_row( $wpdb->prepare(
				"SELECT price,availability FROM ".$wpdb->prefix."nation_booking_availability WHERE day=%d AND month=%d AND year=%d AND calendar_id=%d", 
				$day, $month, $year, $_POST["cal_id"]
			));
								
			if ( $time < $timeNow ) { 
				$error = 1;
				$errorText = __("You can't made reservation on the date that already has passed.","nation");
				break;
			} else if ( !$getPriceAvailability ) { 
				$error = 1;
				$errorText = __("You can't create this reservation as room don't available on selected dates. To made this room available please visit Dashboard > Nation Booking System > Availability/Price.", "nation");
				break;
			} else if ( isset($getPriceAvailability->availability) && $getPriceAvailability->availability == 0 ) {
				$error = 1;
				$errorText = __("You can't create this reservation as the room was already booked on the selected dates.","nation");
				break;
			} else if ( isset($getPriceAvailability->availability) && $_POST["room-number"] > $getPriceAvailability->availability ) {
				$error = 1;
				$errorText = __("You can't create this reservation as the number of rooms available on this date less than requested number.","nation");
				break;
			} else {
				$price += $getPriceAvailability->price;
			}
		}
	} else {
		$error = 1;
		$errorText = __("Check out date should be larger than Check in date","nation");
	}
	
	if ( $error == 0 ) {
	
		if ( $_POST["room-number"] > 1 ) {
			$beAdults = '[';
			$beChild = '[';
			for ($j=0;$j<$_POST["room-number"];$j++) {
				if ( $j == 0 ) {
					$beAdults .= '"'.$_POST["adults"].'"';
					$beChild .= '"'.$_POST["children"].'"';
				} else {
					$beAdults .= ',"'.$_POST["adults"].'"';
					$beChild .= ',"'.$_POST["children"].'"';
				}
			}
			$beAdults .= ']';
			$beChild .= ']';
		} else {
			$beAdults = '["'.$_POST["adults"].'"]';
			$beChild = '["'.$_POST["children"].'"]';
		}
	
		$wpdb->insert($wpdb->prefix.'nation_booking_reservation', array(
			'calendar_id' => $_POST["cal_id"], 
			'check_in' => $_POST["check-in"], 
			'check_out' => $_POST["check-out"], 
			'no_items' => $_POST["room-number"], 
			'email' => $_POST["email"], 
			'no_adult' => $beAdults, 
			'no_children' => $beChild, 
			'status' => 'pending', 
			'price' => $price, 
			'name' => $_POST["name"], 
			'surname' => $_POST["surname"], 
			'card_type' => $_POST["cardtype"], 
			'cardholder_name' => $_POST["cardholder"], 
			'card_number' => $_POST["cardnumber"], 
			'expiration_year' => $_POST["expyear"], 
			'expiration_month' => $_POST["expmonth"], 
			'comments' => $_POST["comments"], 
			'date_created' => current_time('mysql', 1) 
		));
		
		//Send email to admin and user
		sendEmail ( 'without_approval', $reservation->calendar_id, $_POST["reservation_id"], $reservation->check_in, $reservation->check_out, $reservation->price, 
		$reservation->no_items, $reservation->no_adult, $reservation->no_children, $reservation->email, $reservation->name, $reservation->surname, 
		$reservation->comments, $reservation->card_type, $reservation->cardholder_name, $reservation->card_number, $reservation->expiration_month, $reservation->expiration_year );
		
		wp_redirect(  admin_url( "admin.php?page=nation-booking-reservation-show" ) );
		exit;
	} else {
		echo $errorText."<br>";
		printf(__("<a href='%s'>Return on previous page</a>","nation"), admin_url( "admin.php?page=nation-booking-reservation-show" ) );
	}
	
}
add_action( 'admin_post_nation_booking_add_reservation', 'nation_booking_add_reservation_func' );


// Edit reservation
function nation_booking_edit_reservation_func() {
	global $wpdb;
	
	if ( !current_user_can( 'manage_options' ) ) {
      wp_die( __('You are not allowed to be on this page.','nation') );
	}
	
	
	//Form correct array for number of adults and children per rooms
	for ($i=1;$i<=$_POST["reservation-room-number-edit"];$i++) {
		$aroom[$i-1] = $_POST["reservation-edit-room{$i}-adult"];
		$croom[$i-1] = $_POST["reservation-edit-room{$i}-child"];
	}

	
	// Check that nonce field
	check_admin_referer( 'nation_edit_reservation_verify' );
	
	if ( $_POST["ispaypal"] == 0 ) {
	
		$wpdb->update( $wpdb->prefix.'nation_booking_reservation', 
		
		array( 'check_in' => $_POST["reservation-checkin-edit"], 
			'check_out' => $_POST["reservation-checkout-edit"],
			'no_items' => $_POST["reservation-room-number-edit"],
			'price' => $_POST["reservation-price-edit"],
			'email' => $_POST["reservation-email-edit"],
			'no_adult' => json_encode($aroom),
			'no_children' => json_encode($croom),
			'name' => $_POST["reservation-name-edit"],
			'surname' => $_POST["reservation-surname-edit"],
			'cardholder_name' => $_POST["reservation-cardholder-name-edit"],
			'card_type' => $_POST["reservation-cardtype-edit"],
			'card_number' => $_POST["reservation-cardnumber-edit"],
			'expiration_year' => $_POST["reservation-expyear-edit"],
			'expiration_month' => $_POST["reservation-expmonth-edit"]
		), 
		array( 'calendar_id' => $_POST["cal_id"], 'id' => $_POST["reservation-id-edit"] ) 
			
		); 
	
	} else if ( $_POST["ispaypal"] == 1 ) {
	
		$wpdb->update( $wpdb->prefix.'nation_booking_reservation', 
		
		array( 'check_in' => $_POST["reservation-checkin-edit"], 
			'check_out' => $_POST["reservation-checkout-edit"],
			'no_items' => $_POST["reservation-room-number-edit"],
			'price' => $_POST["reservation-price-edit"],
			'email' => $_POST["reservation-email-edit"],
			'no_adult' => json_encode($aroom),
			'no_children' => json_encode($croom),
			'name' => $_POST["reservation-name-edit"],
			'surname' => $_POST["reservation-surname-edit"]
		), 
		array( 'calendar_id' => $_POST["cal_id"], 'id' => $_POST["reservation-id-edit"] ) 
			
		); 
		
	}
	
	
	wp_redirect(  admin_url( "admin.php?page=nation-booking-reservation-show" ) );
	exit;
}
add_action( 'admin_post_nation_booking_edit_reservation', 'nation_booking_edit_reservation_func' );


// Reservation database modifications
function nation_booking_approve_reservation_func() {
	global $wpdb;
	
	$error=0;
	
	if ( !current_user_can( 'manage_options' ) ) {
      wp_die( __('You are not allowed to be on this page.','nation') );
	}
	// Check that nonce field
	check_admin_referer( 'nation_approve_reservation_verify' );
	
	$dateformat = $wpdb->get_var( "SELECT date_format FROM ".$wpdb->prefix."nation_booking_settings");
	
	if ( isset($_POST["reservation_id"]) ) {
		
		//Retrieve our reservation date from the table
		$reservation = $wpdb->get_row( $wpdb->prepare(
			'SELECT * FROM '.$wpdb->prefix.'nation_booking_reservation WHERE id=%d',$_POST["reservation_id"]
			)
		);
		
		//Create date range
		$datesArray = createDateRangeArray( $reservation->check_in, $reservation->check_out, $dateformat );
		
		//Automatically made reservation on selected date in availability table
		for ( $i=0;$i<count($datesArray)-1;$i++ ) {
			//Process dates
			$day = date("d", strtotime($datesArray[$i]));
			$month = date("m", strtotime($datesArray[$i]));
			$year = date("Y", strtotime($datesArray[$i]));
			
			$numberAvailable = $wpdb->get_var( $wpdb->prepare(
				"SELECT availability FROM ".$wpdb->prefix."nation_booking_availability WHERE day=%d AND month=%d AND year=%d AND calendar_id=%d", 
				$day, $month, $year, $reservation->calendar_id
			));
			
			$totalNumberAvailable = $numberAvailable - $reservation->no_items;
			
			$status = $wpdb->update( $wpdb->prefix.'nation_booking_availability', array( 'availability' => $totalNumberAvailable ), array( 'day' => $day, 'month' => $month, 'year' => $year, 'calendar_id' => $reservation->calendar_id ) );
		}
		
		$wpdb->update( $wpdb->prefix.'nation_booking_reservation', array( 'status' => "approved" ), array( 'id' => $_POST["reservation_id"] ) );
		
		//Send email to admin and user
		sendEmail ( 'approval', $reservation->calendar_id, $_POST["reservation_id"], $reservation->check_in, $reservation->check_out, $reservation->price, 
		$reservation->no_items, $reservation->no_adult, $reservation->no_children, $reservation->email, $reservation->name, $reservation->surname, 
		$reservation->comments, $reservation->card_type, $reservation->cardholder_name, $reservation->card_number, $reservation->expiration_month, $reservation->expiration_year );
		
		wp_redirect(  admin_url( "admin.php?page=nation-booking-reservation-show" ) );
		exit;
	}
}
add_action( 'admin_post_nation_booking_approve_reservation', 'nation_booking_approve_reservation_func' );


function nation_booking_cancel_reservation_func() {
	global $wpdb;
	
	if ( !current_user_can( 'manage_options' ) ) {
      wp_die( __('You are not allowed to be on this page.','nation') );
	}
	// Check that nonce field
	check_admin_referer( 'nation_cancel_reservation_verify' );
	
	$dateformat = $wpdb->get_var( "SELECT date_format FROM ".$wpdb->prefix."nation_booking_settings");
	
	//Retrieve our reservation date from the table
	$reservation = $wpdb->get_row( $wpdb->prepare(
		'SELECT * FROM '.$wpdb->prefix.'nation_booking_reservation WHERE id=%d',$_POST["reservation_id"]
	));
		
	//Create date range
	$datesArray = createDateRangeArray( $reservation->check_in, $reservation->check_out, $dateformat );
		
	for ( $i=0;$i<count($datesArray)-1;$i++ ) {
		//Process dates
		$day = date("d", strtotime($datesArray[$i]));
		$month = date("m", strtotime($datesArray[$i]));
		$year = date("Y", strtotime($datesArray[$i]));
			
		$numberAvailable = $wpdb->get_var( $wpdb->prepare(
			"SELECT availability FROM ".$wpdb->prefix."nation_booking_availability WHERE day=%d AND month=%d AND year=%d AND calendar_id=%d", 
			$day, $month, $year, $reservation->calendar_id
		));
			
		$totalNumberAvailable = $numberAvailable + $reservation->no_items;
		$status = $wpdb->update( $wpdb->prefix.'nation_booking_availability', array( 'availability' => $totalNumberAvailable ), array( 'day' => $day, 'month' => $month, 'year' => $year, 'calendar_id' => $reservation->calendar_id ) );
	}
		
	$wpdb->update( $wpdb->prefix.'nation_booking_reservation', array( 'status' => "canceled" ), array( 'id' => $_POST["reservation_id"] ) );
	
	//Send email to admin and user
	sendEmail ( 'canceled', $reservation->calendar_id, $_POST["reservation_id"], $reservation->check_in, $reservation->check_out, $reservation->price, 
	$reservation->no_items, $reservation->no_adult, $reservation->no_children, $reservation->email, $reservation->name, $reservation->surname, 
	$reservation->comments, $reservation->card_type, $reservation->cardholder_name, $reservation->card_number, $reservation->expiration_month, $reservation->expiration_year );
		
	wp_redirect(  admin_url( "admin.php?page=nation-booking-reservation-show" ) );
	exit;

}
add_action( 'admin_post_nation_booking_cancel_reservation', 'nation_booking_cancel_reservation_func' );


function nation_booking_delete_reservation_func() {
	global $wpdb;
	
	if ( !current_user_can( 'manage_options' ) ) {
      wp_die( 'You are not allowed to be on this page.' );
	}
	// Check that nonce field
	check_admin_referer( 'nation_delete_reservation_verify' );
	
	if ( isset($_POST["reservation_id"]) ) {
		$wpdb->delete( $wpdb->prefix.'nation_booking_reservation', array( 'id' => $_POST["reservation_id"] ) );
	}
	wp_redirect( admin_url( "admin.php?page=nation-booking-reservation-show" ) );
	exit;
}
add_action( 'admin_post_nation_booking_delete_reservation', 'nation_booking_delete_reservation_func' );


function nation_booking_reject_reservation_func() {
	global $wpdb;
	
	if ( !current_user_can( 'manage_options' ) ) {
      wp_die( 'You are not allowed to be on this page.' );
	}
	// Check that nonce field
	check_admin_referer( 'nation_reject_reservation_verify' );
	
	if ( isset($_POST["reservation_id"]) ) {
		$wpdb->update( $wpdb->prefix.'nation_booking_reservation', array( 'status' => "rejected" ), array( 'id' => $_POST["reservation_id"] ) );
	
		//Retrieve our reservation date from the table
		$reservation = $wpdb->get_row( $wpdb->prepare(
			'SELECT * FROM '.$wpdb->prefix.'nation_booking_reservation WHERE id=%d',$_POST["reservation_id"]
		));
	
		//Send email to admin and user
		sendEmail ( 'rejected', $reservation->calendar_id, $_POST["reservation_id"], $reservation->check_in, $reservation->check_out, $reservation->price, 
		$reservation->no_items, $reservation->no_adult, $reservation->no_children, $reservation->email, $reservation->name, $reservation->surname, 
		$reservation->comments, $reservation->card_type, $reservation->cardholder_name, $reservation->card_number, $reservation->expiration_month, $reservation->expiration_year );
	
	}
	
	wp_redirect(  admin_url( "admin.php?page=nation-booking-reservation-show" ) );
	exit;
}
add_action( 'admin_post_nation_booking_reject_reservation', 'nation_booking_reject_reservation_func' );


// Dates database modifications
function nation_booking_date_modification_func() {
	global $wpdb;
	
	$dateformat = $wpdb->get_var( "SELECT date_format FROM ".$wpdb->prefix."nation_booking_settings");
		
	if ( !current_user_can( 'manage_options' ) ) {
      wp_die( 'You are not allowed to be on this page.' );
	}
		
	// Check that nonce field
	if ( !isset($_POST['nation_date_modification_verify']) || !wp_verify_nonce($_POST['nation_date_modification_verify'],'nation_date_modification_verify') ) {
        wp_die( 'Your nonce not valid.' );
    } else {
		
		$datesArray = createDateRangeArray( $_POST["check-in"], $_POST["check-out"], $dateformat );
		
		array_pop($datesArray);
					
		for ( $i=0;$i<count($datesArray);$i++ ) {
			//Process dates
			$day = date("d", strtotime($datesArray[$i]));
			$month = date("m", strtotime($datesArray[$i]));
			$year = date("Y", strtotime($datesArray[$i]));
			
			
			$status = $wpdb->get_results( $wpdb->prepare(
				"SELECT * FROM " .$wpdb->prefix."nation_booking_availability WHERE day = $day AND month = $month AND year = $year AND calendar_id = %d",
				$_POST["cal_id"]
			) );
								
			if (count($status) > 0 ) {
				$wpdb->update( $wpdb->prefix.'nation_booking_availability', array( 'availability' => $_POST["availability"], 'price' => $_POST["price"] ), array( 'day' => $day, 'month' => $month, 'year' => $year, 'calendar_id' => $_POST["cal_id"] ) );
			} else {
				$wpdb->insert($wpdb->prefix.'nation_booking_availability', array('calendar_id' => $_POST["cal_id"], 'day' => $day, 'month' => $month, 'year' => $year, 'availability' => $_POST["availability"], 'price' => $_POST["price"] ));
			}
			
		}
	
	
		$day = date("d"); $month = date("m"); $year = date("Y");

		$availabilities = $wpdb->get_results( $wpdb->prepare( 
			"SELECT price FROM ".$wpdb->prefix."nation_booking_availability WHERE calendar_id = %d AND day >= $day AND month >= $month AND year >= $year", 
			$_POST["cal_id"]
		));
	
		// Find min price
		if ($wpdb->num_rows != 0){
			if ($availabilities){
				foreach( $availabilities as $availability ) {
					if ( !isset($priceArray) ) {
						$priceArray[0]=$availability->price;
					} else {
						array_push($priceArray,$availability->price);
					}
				}
			}
		}
		
		if ( count($priceArray) > 1 ) {
			$minPrice = min($priceArray);
		} else {
			$minPrice = $priceArray[0];
		}
		
		$status = $wpdb->update( $wpdb->prefix.'nation_booking_calendars', array( 'min_price' => $minPrice ), array( 'id' => $_POST["cal_id"] ) );
	
		wp_redirect(  admin_url( "admin.php?page=availability-price-calendar" ) );
		exit;
	
	}
}
add_action( 'admin_post_nation_booking_date_modification', 'nation_booking_date_modification_func' );


// Settings Database modifications
function nation_booking_settings_edit_func() {
	global $wpdb;
	
	if ( !current_user_can( 'manage_options' ) ) {
      wp_die( __('You are not allowed to be on this page.','nation') );
	}
	// Check that nonce field
	check_admin_referer( 'nation_date_settings_edit' );
		
	$check = $wpdb->update( $wpdb->prefix.'nation_booking_settings', array( 
		'email' => $_POST['email'],
		'currency_symbol' => $_POST['currency_symbol'], 
		'date_format' => $_POST['date_format'], 
		'hide_tax' => $_POST['hide_tax'],
		'add_tax' => $_POST['add_tax'],
		'tax' => $_POST['tax'], 
		'enable_coupon' => $_POST['enable_coupon'],
		'coupon_discount' => $_POST['coupon_discount'],
		'coupon_name' => $_POST['coupon_name'],
		'coupon_discount2' => $_POST['coupon_discount2'],
		'coupon_name2' => $_POST['coupon_name2'],
		'coupon_discount3' => $_POST['coupon_discount3'],
		'coupon_name3' => $_POST['coupon_name3'],
		'confirmation_email_header' => $_POST['confirmation_email_header'], 
		'confirmation_email_content' => $_POST['confirmation_email_content'], 
		'cancelation_email_header' => $_POST['cancelation_email_header'], 
		'cancelation_email_content' => $_POST['cancelation_email_content'], 
		'without_confirmation_email_header' => $_POST['without_confirmation_email_header'], 
		'without_confirmation_email_content' => $_POST['without_confirmation_email_content'], 
		'rejected_email_header' => $_POST['rejected_email_header'], 
		'rejected_email_content' => $_POST['rejected_email_content'] ), 
		array( 'id' => 1 ) );
		
	wp_redirect(  admin_url( "admin.php?page=settings" ) );
	exit;
}
add_action( 'admin_post_nation_booking_settings_edit', 'nation_booking_settings_edit_func' );


// Paypal Settings Database modifications
function nation_booking_paypal_settings_edit_func() {
	global $wpdb;
	
	if ( !current_user_can( 'manage_options' ) ) {
      wp_die( __('You are not allowed to be on this page.','nation') );
	}
	// Check that nonce field
	check_admin_referer( 'nation_date_paypal_settings_edit' );
	
	$wpdb->update( $wpdb->prefix.'nation_booking_settings', array( 
		'paypal_enabled' => $_POST['paypal_enabled'],
		'sandbox_enabled' => $_POST['paypal_sandbox_enabled'],
		'paypal_api_username' => $_POST['api_username'], 
		'paypal_api_password' => $_POST['api_password'], 
		'paypal_api_signature' => $_POST['api_signature'],
		'paypal_currency_code' => $_POST['paypal_currency_code']
	), array( 'id' => 1 ) );
	
	wp_redirect(  admin_url( "admin.php?page=paypal-settings" ) );
	exit;
}
add_action( 'admin_post_nation_booking_paypal_settings_edit', 'nation_booking_paypal_settings_edit_func' );


?>