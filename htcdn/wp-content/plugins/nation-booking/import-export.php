<?php

function booking_export( $args = array() ) {
	GLOBAL $wpdb;
	
    /* Creating array of all plugin tables */	
	$pluginCalendars = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}nation_booking_calendars" );
	$pluginAvailabilities = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}nation_booking_availability" );
	$pluginReservations = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}nation_booking_reservation" );
	$pluginSettings = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}nation_booking_settings" );
	
    $filename = 'booking-calendar-' . date( 'Y-m-d' ) . '.xml';
 
    /* Print header */
    header( 'Content-Description: File Transfer' );
    header( 'Content-Disposition: attachment; filename=' . $filename );
    header( 'Content-Type: text/xml; charset=' . get_bloginfo( 'charset' ), true );
 
    /* Print comments */
    echo "<!-- This is a export of the reservation system plugin -->\n";
	
echo "<document>";
    /* Export of dopbsp_calendars table */
	if ($pluginCalendars) {
		echo "	<booking_calendars>";
		foreach ( $pluginCalendars as $pluginCalendar ) {
	?>		
		<item>
			<id><?php echo absint($pluginCalendar->id) ?></id>
			<cal_name><?php echo $pluginCalendar->cal_name ?></cal_name>
			<min_price><?php echo absint($pluginCalendar->min_price) ?></min_price>
		</item>
	<?php	
		}	
		echo "</booking_calendars>";
	}
	
	if ($pluginAvailabilities) {
		echo "	
	<booking_availability>";
		foreach ( $pluginAvailabilities as $pluginAvailability ) {
	?>	
		<item>
			<id><?php echo absint($pluginAvailability->id) ?></id>
			<calendar_id><?php echo absint($pluginAvailability->calendar_id) ?></calendar_id>
			<day><?php echo absint($pluginAvailability->day) ?></day>
			<month><?php echo absint($pluginAvailability->month) ?></month>
			<year><?php echo absint($pluginAvailability->year) ?></year>
			<availability><?php echo absint($pluginAvailability->availability) ?></availability>
			<price><?php echo absint($pluginAvailability->price) ?></price>
		</item>
	<?php
		}
		echo "</booking_availability>";
	}
	
	if ($pluginReservations) {
		echo "
	<booking_reservation>";
		foreach ( $pluginReservations as $pluginReservation) {
	?>
	
		<item>
			<id><?php echo absint($pluginReservation->id) ?></id>
			<calendar_id><?php echo absint($pluginReservation->calendar_id) ?></calendar_id>
			<check_in><?php echo $pluginReservation->check_in ?></check_in>
			<check_out><?php echo $pluginReservation->check_out ?></check_out>
			<no_items><?php echo absint($pluginReservation->no_items) ?></no_items>
			<price><?php echo absint($pluginReservation->price) ?></price>
			<email><?php echo $pluginReservation->email ?></email>
			<no_adult><?php echo $pluginReservation->no_adult ?></no_adult>
			<no_children><?php echo $pluginReservation->no_children ?></no_children>
			<status><?php echo $pluginReservation->status ?></status>
			<name><?php echo $pluginReservation->name ?></name>
			<surname><?php echo $pluginReservation->surname ?></surname>
			<paypal_payment><?php echo $pluginReservation->paypal_payment ?></paypal_payment>
			<paypal_payer_id><?php echo $pluginReservation->paypal_payer_id ?></paypal_payer_id>
			<paypal_transaction_id><?php echo $pluginReservation->paypal_transaction_id ?></paypal_transaction_id>
			<cardholder_name><?php echo $pluginReservation->cardholder_name ?></cardholder_name>
			<card_type><?php echo $pluginReservation->card_type ?></card_type>
			<card_number><?php echo $pluginReservation->card_number ?></card_number>
			<expiration_year><?php echo absint($pluginReservation->expiration_year) ?></expiration_year>
			<expiration_month><?php echo absint($pluginReservation->expiration_month) ?></expiration_month>
			<comments><?php echo $pluginReservation->comments ?></comments>
			<date_created><?php echo $pluginReservation->date_created ?></date_created>
		</item>
	
	<?php
		}
		echo "</booking_reservation>";
	}
	
	if ($pluginSettings) {
		echo "
	<booking_settings>";
		foreach ( $pluginSettings as $pluginSetting) {
	?>
	
		<item>
			<id><?php echo absint($pluginSetting->id) ?></id>
			<email><?php echo $pluginSetting->email ?></email>
			<currency_symbol><?php echo $pluginSetting->currency_symbol ?></currency_symbol>
			<date_format><?php echo $pluginSetting->date_format ?></date_format>
			<hide_tax><?php echo $pluginSetting->hide_tax ?></hide_tax>
			<add_tax><?php echo $pluginSetting->add_tax ?></add_tax>
			<tax><?php echo $pluginSetting->tax ?></tax>
			<enable_coupon><?php echo $pluginSetting->enable_coupon ?></enable_coupon>
			<coupon_name><?php echo $pluginSetting->coupon_name ?></coupon_name>
			<coupon_discount><?php echo $pluginSetting->coupon_discount ?></coupon_discount>
			<coupon_name2><?php echo $pluginSetting->coupon_name2 ?></coupon_name2>
			<coupon_discount2><?php echo $pluginSetting->coupon_discount2 ?></coupon_discount2>
			<coupon_name3><?php echo $pluginSetting->coupon_name3 ?></coupon_name3>
			<coupon_discount3><?php echo $pluginSetting->coupon_discount3 ?></coupon_discount3>
			<confirmation_email_header><?php echo $pluginSetting->confirmation_email_header ?></confirmation_email_header>
			<confirmation_email_content><?php echo $pluginSetting->confirmation_email_content ?></confirmation_email_content>
			<cancelation_email_header><?php echo $pluginSetting->cancelation_email_header ?></cancelation_email_header>
			<cancelation_email_content><?php echo $pluginSetting->cancelation_email_content ?></cancelation_email_content>
			<without_confirmation_email_header><?php echo $pluginSetting->without_confirmation_email_header ?></without_confirmation_email_header>
			<without_confirmation_email_content><?php echo $pluginSetting->without_confirmation_email_content ?></without_confirmation_email_content>
			<rejected_email_header><?php echo $pluginSetting->rejected_email_header ?></rejected_email_header>
			<rejected_email_content><?php echo $pluginSetting->rejected_email_content ?></rejected_email_content>
			<paypal_enabled><?php echo $pluginSetting->paypal_enabled ?></paypal_enabled>
			<paypal_api_username><?php echo $pluginSetting->paypal_api_username ?></paypal_api_username>
			<paypal_api_password><?php echo $pluginSetting->paypal_api_password ?></paypal_api_password>
			<paypal_api_signature><?php echo $pluginSetting->paypal_api_signature ?></paypal_api_signature>
			<paypal_currency_code><?php echo $pluginSetting->paypal_currency_code ?></paypal_currency_code>
			<sandbox_enabled><?php echo $pluginSetting->sandbox_enabled ?></sandbox_enabled>
		</item>
	
	<?php 
		}
		echo "</booking_settings>";
	}	
		
	echo "</document>";
	exit;
}

 
function nation_booking_export_func() {
	global $wpdb;
	if ( !current_user_can( 'manage_options' ) ) {
      wp_die( 'You are not allowed to be on this page.' );
	}
	check_admin_referer( 'nation_booking_export_verify' );
		
	/* Trigger download of .xml export file */
	booking_export();
		
}
add_action( 'admin_post_nation_booking_export', 'nation_booking_export_func' );
	
	
	
function nation_booking_import_func() {
	global $wpdb;
	if ( !current_user_can( 'manage_options' ) ) {
      wp_die( __('You are not allowed to be on this page.','nation') );
	}
	check_admin_referer( 'nation_booking_import_verify' );
 
	/* Perform checks on file: */
	// Sanity check
	if ( empty( $_FILES["booking_import"] ) )
		wp_die( __('No file found','nation') );
 		
	$file = $_FILES["booking_import"];
		
	// Is it of the expected type?
	if ( $file["type"] != "text/xml" )
		wp_die( sprintf( __( "There was an error importing the logs. File type detected: '%s'. 'text/xml' expected", 'nation'), $file['type'] ) );
 
	// Impose a limit on the size of the uploaded file. Max 2097152 bytes = 2MB
	if ( $file["size"] > 2097152 ) {
		$size = size_format( $file['size'], 2 );
		wp_die( sprintf( __( 'File size too large (%s). Maximum 2MB', 'import-logs', 'nation' ), $size ) );
	}
 
	if( $file["error"] > 0 )
		wp_die( sprintf( __( "Error encountered: %d" , 'nation' ), $file["error"] ) );
		
	/* If we've made it this far then we can import the data */
	$imported = import( $file['tmp_name'] );
		
	/* Everything is complete, now redirect back to the page */
	wp_redirect(  admin_url( "admin.php?page=import-export&imported=1" ) );
	exit();

}
add_action( 'admin_post_nation_booking_import', 'nation_booking_import_func' );


function import( $file ) {
	GLOBAL $wpdb;
		
	// Parse file
	$booking = parse( $file );
 
	// Parse returned nothing? - then aborted.
	if  ( ! $booking )
		return 0;
	
	
	if ( isset( $booking["calendars"] ) ) {
		// //Clear Current Table
		$wpdb->query( "TRUNCATE TABLE ".$wpdb->prefix."nation_booking_calendars" );
			
		// Load extracted from XML data to coresponding table in database
		for ( $i=0;$i<count($booking["calendars"]);$i++ ){
			$calendarGood = $wpdb->insert(
				$wpdb->prefix."nation_booking_calendars",
				array(
					'id' => $booking["calendars"][$i]["id"],
					'cal_name' => $booking["calendars"][$i]["cal_name"],
					'min_price' => $booking["calendars"][$i]["min_price"],
				),
				array ( "%d", "%s", "%d" )
			);
		}	 
	}

	if ( isset( $booking["availability"] ) ) {
		// //Clear Current Table
		$wpdb->query( "TRUNCATE TABLE ".$wpdb->prefix."nation_booking_availability" );
			
		// Load extracted from XML data to coresponding table in database
		for ( $i=0;$i<count($booking["availability"]);$i++ ){
			$availabilityGood = $wpdb->insert(
				$wpdb->prefix."nation_booking_availability",
				array(
					'id' => $booking["availability"][$i]["id"],
					'calendar_id' => $booking["availability"][$i]["calendar_id"],
					'day' => $booking["availability"][$i]["day"],
					'month' => $booking["availability"][$i]["month"],
					'year' => $booking["availability"][$i]["year"],
					'availability' => $booking["availability"][$i]["availability"],
					'price' => $booking["availability"][$i]["price"]
				),
				array ( "%d", "%d", "%s", "%s", "%s", "%d", "%d" )
			);
		}	 
	}

	if ( isset( $booking["reservation"] ) ) {
		// //Clear Current Table
		$wpdb->query( "TRUNCATE TABLE ".$wpdb->prefix."nation_booking_reservation" );
		
		// Load extracted from XML data to coresponding table in database
		for ( $i=0;$i<count($booking["reservation"]);$i++ ){
			$reservationGood = $wpdb->insert(
				$wpdb->prefix."nation_booking_reservation",
				array(
					'id' => $booking["reservation"][$i]["id"],
					'calendar_id' => $booking["reservation"][$i]["calendar_id"],
					'check_in' => $booking["reservation"][$i]["check_in"],
					'check_out' => $booking["reservation"][$i]["check_out"],
					'no_items' => $booking["reservation"][$i]["no_items"],
					'price' => $booking["reservation"][$i]["price"],
					'email' => $booking["reservation"][$i]["email"],
					'no_adult' => $booking["reservation"][$i]["no_adult"],
					'no_children' => $booking["reservation"][$i]["no_children"],
					'status' => $booking["reservation"][$i]["status"],
					'name' => $booking["reservation"][$i]["name"],
					'surname' => $booking["reservation"][$i]["surname"],
					'paypal_payment' => $booking["reservation"][$i]["paypal_payment"],
					'paypal_payer_id' => $booking["reservation"][$i]["paypal_payer_id"],
					'paypal_transaction_id' => $booking["reservation"][$i]["paypal_transaction_id"],
					'cardholder_name' => $booking["reservation"][$i]["cardholder_name"],
					'card_type' => $booking["reservation"][$i]["card_type"],
					'card_number' => $booking["reservation"][$i]["card_number"],
					'expiration_year' => $booking["reservation"][$i]["expiration_year"],
					'expiration_month' => $booking["reservation"][$i]["expiration_month"],
					'comments' => $booking["reservation"][$i]["comments"],
					'date_created' => $booking["reservation"][$i]["date_created"]
				),
				array ( "%d", "%d", "%s", "%s", "%d", "%d", "%s", "%s", "%s", "%s", "%s", "%s", 
				"%d", "%s", "%s", "%s", "%s", "%s", "%d", "%d", "%s", "%s" )
			);
		}	 
	}
	
	if ( isset( $booking["settings"] ) ) {
		// //Clear Current Table
		$wpdb->query( "TRUNCATE TABLE ".$wpdb->prefix."nation_booking_settings" );
			
		// Load extracted from XML data to coresponding table in database
		for ( $i=0;$i<count($booking["settings"]);$i++ ){
			$settingsGood = $wpdb->insert(
				$wpdb->prefix."nation_booking_settings",
				array(
					'id' => $booking["settings"][$i]["id"],
					'email' => $booking["settings"][$i]["email"],
					'currency_symbol' => $booking["settings"][$i]["currency_symbol"],
					'date_format' => $booking["settings"][$i]["date_format"],
					'hide_tax' => $booking["settings"][$i]["hide_tax"],
					'add_tax' => $booking["settings"][$i]["add_tax"],	
					'tax' => $booking["settings"][$i]["tax"],
					'enable_coupon' => $booking["settings"][$i]["enable_coupon"],
					'coupon_name' => $booking["settings"][$i]["coupon_name"],
					'coupon_discount' => $booking["settings"][$i]["coupon_discount"],
					'coupon_name2' => $booking["settings"][$i]["coupon_name2"],
					'coupon_discount2' => $booking["settings"][$i]["coupon_discount2"],
					'coupon_name3' => $booking["settings"][$i]["coupon_name3"],
					'coupon_discount3' => $booking["settings"][$i]["coupon_discount3"],
					'confirmation_email_header' => $booking["settings"][$i]["confirmation_email_header"],
					'confirmation_email_content' => $booking["settings"][$i]["confirmation_email_content"],
					'cancelation_email_header' => $booking["settings"][$i]["cancelation_email_header"],
					'cancelation_email_content' => $booking["settings"][$i]["cancelation_email_content"],
					'without_confirmation_email_header' => $booking["settings"][$i]["without_confirmation_email_header"],
					'without_confirmation_email_content' => $booking["settings"][$i]["without_confirmation_email_content"],
					'rejected_email_header' => $booking["settings"][$i]["rejected_email_header"],
					'rejected_email_content' => $booking["settings"][$i]["rejected_email_content"],
					'paypal_enabled' => $booking["settings"][$i]["paypal_enabled"],
					'paypal_api_username' => $booking["settings"][$i]["paypal_api_username"],
					'paypal_api_password' => $booking["settings"][$i]["paypal_api_password"],
					'paypal_api_signature' => $booking["settings"][$i]["paypal_api_signature"],
					'paypal_currency_code' => $booking["settings"][$i]["paypal_currency_code"],
					'sandbox_enabled' => $booking["settings"][$i]["sandbox_enabled"]
				),
				array ( "%d", "%s", "%s", "%s", "%d", "%d", "%s", "%d", "%s", "%d", "%s", "%d", "%s", "%d", "%s", "%s", "%s", 
				"%s", "%s", "%s", "%s", "%s", "%d", "%s", "%s", "%s", "%s", "%d" )
			);
		}	 
	}

	return $booking;
}

	
function parse( $file ) {
		
	// Load the xml file
	$xml = simplexml_load_file( $file );
 
	// halt if loading produces an error
	if ( ! $xml )
		return false;
 
	// Parse all XML data and save it to array
	$booking = array();
		
	// Parse booking_calendars element
	$i=0;
	if ( isset( $xml->booking_calendars->item ) ) {
		foreach ( $xml->booking_calendars->item as $item ) {
			$booking["calendars"][$i] = array (
				'id' => (int) $item->id,
				'cal_name' => (string) $item->cal_name,
				'min_price' => (int) $item->min_price
			); 
			$i++;
		}
	}
	
	// Parse booking_days element
	$i=0;
	if ( isset( $xml->booking_availability->item ) ) {
		foreach ( $xml->booking_availability->item as $item ) {
			$booking["availability"][$i] = array (
				'id' => (int) $item->id,
				'calendar_id' => (int) $item->calendar_id,
				'day' => (int) $item->day,
				'month' => (int) $item->month,
				'year' => (int) $item->year,
				'availability' => (int) $item->availability,
				'price' => (int) $item->price
			); 
			$i++;
		}
	}
		
	// Parse booking_forms element
	$i=0;
	if ( isset( $xml->booking_reservation->item ) ) {
		foreach ( $xml->booking_reservation->item as $item ) {
			$booking["reservation"][$i] = array (
				'id' => (int) $item->id,
				'calendar_id' => (int) $item->calendar_id,
				'check_in' => (string) $item->check_in,
				'check_out' => (string) $item->check_out,
				'no_items' => (int) $item->no_items,
				'price' => (int) $item->price,
				'email' => (string) $item->email,
				'no_adult' => (string) $item->no_adult,
				'no_children' => (string) $item->no_children,
				'status' => (string) $item->status,
				'name' => (string) $item->name,
				'surname' => (string) $item->surname,
				'paypal_payment' => (int) $item->paypal_payment,
				'paypal_payer_id' => (string) $item->paypal_payer_id,
				'paypal_transaction_id' => (string) $item->paypal_transaction_id,
				'cardholder_name' => (string) $item->cardholder_name,
				'card_type' => (string) $item->card_type,
				'card_number' => (string) $item->card_number,
				'expiration_year' => (int) $item->expiration_year,
				'expiration_month' => (int) $item->expiration_month,
				'comments' => (string) $item->comments,
				'date_created' => (string) $item->date_created
			); 
			$i++;
		}
	}

	// Parse booking_forms_fields element
	$i=0;
	if ( isset( $xml->booking_settings->item ) ) {
		foreach ( $xml->booking_settings->item as $item ) {
			$booking["settings"][$i] = array (
				'id' => (int) $item->id,
				'email' => (string) $item->email,
				'currency_symbol' => (string) $item->currency_symbol,
				'date_format' => (string) $item->date_format,
				'hide_tax' => (int) $item->hide_tax,
				'add_tax' => (int) $item->add_tax,
				'tax' => (string) $item->tax,
				'enable_coupon' => (int) $item->enable_coupon,
				'coupon_name' => (string) $item->coupon_name,
				'coupon_discount' => (int) $item->coupon_discount,
				'coupon_name2' => (string) $item->coupon_name2,
				'coupon_discount2' => (int) $item->coupon_discount2,
				'coupon_name3' => (string) $item->coupon_name3,
				'coupon_discount3' => (int) $item->coupon_discount3,
				'confirmation_email_header' => (string) $item->confirmation_email_header,
				'confirmation_email_content' => (string) $item->confirmation_email_content,
				'cancelation_email_header' => (string) $item->cancelation_email_header,
				'cancelation_email_content' => (string) $item->cancelation_email_content,
				'without_confirmation_email_header' => (string) $item->without_confirmation_email_header,
				'without_confirmation_email_content' => (string) $item->without_confirmation_email_content,
				'rejected_email_header' => (string) $item->rejected_email_header,
				'rejected_email_content' => (string) $item->rejected_email_content,
				'paypal_enabled' => (int) $item->paypal_enabled,
				'paypal_api_username' => (string) $item->paypal_api_username,
				'paypal_api_password' => (string) $item->paypal_api_password,
				'paypal_api_signature' => (string) $item->paypal_api_signature,
				'paypal_currency_code' => (string) $item->paypal_currency_code,
				'sandbox_enabled' => (int) $item->sandbox_enabled
			); 
			$i++;
		}
	}
		
		
	return $booking;
}
	