<?php

 // takes two dates formatted as YYYY-MM-DD and creates an
 // inclusive array of the dates between the from and to dates.

function createDateRangeArray( $strDateFrom,$strDateTo,$format ) {
   
    $aryRange=array();
    if ( $format == "european" ) {
		$iDateFrom=mktime(1,0,0,substr($strDateFrom,3,2),substr($strDateFrom,0,2),substr($strDateFrom,6,4));
		$iDateTo=mktime(1,0,0,substr($strDateTo,3,2),substr($strDateTo,0,2),substr($strDateTo,6,4));
	} else if ( $format == "american" ) {
		$iDateFrom=mktime(1,0,0,substr($strDateFrom,0,2),substr($strDateFrom,3,2),substr($strDateFrom,6,4));
		$iDateTo=mktime(1,0,0,substr($strDateTo,0,2),substr($strDateTo,3,2),substr($strDateTo,6,4));
	}

	
    if ($iDateTo>=$iDateFrom) {
        if ( $format == "european" ) {
			array_push($aryRange, date('d-m-Y',$iDateFrom)); 
		} else if ( $format == "american" ) {
			array_push($aryRange,date('m/d/Y',$iDateFrom)); 
		}
        while ($iDateFrom<$iDateTo) {
            $iDateFrom+=86400; // add 24 hours
            if ( $format == "european" ) {
				array_push($aryRange,date('d-m-Y',$iDateFrom)); 
			} else if ( $format == "american" ) {
				array_push($aryRange,date('m/d/Y',$iDateFrom)); 
			}
        }
    }
	
    return $aryRange;
}


function sendEmail ( $type, $cal_id, $reservation_id, $checkin, $checkout, $total, $items, $adult, 
	$child, $email, $name, $surname, $comments, $cardtype, $cardholder, $cardnumber, $expmonth, $expyear ) {
		
	global $wpdb;
	
	$bookingSettings = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}nation_booking_settings" );
	$calendar = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}nation_booking_calendars WHERE id='".$cal_id."'" );
	
	if ( $type == 'without_approval' ) {
		$subject = $bookingSettings->without_confirmation_email_header;
		$content = 	$bookingSettings->without_confirmation_email_content;
	} else if ( $type == 'approval' ) {
		$subject = $bookingSettings->confirmation_email_header;
		$content = $bookingSettings->confirmation_email_content;
	} else if ( $type == 'rejected' ) {
		$subject = $bookingSettings->rejected_email_header;
		$content = $bookingSettings->rejected_email_content;
	} else if ( $type == 'canceled' ) {
		$subject = $bookingSettings->cancelation_email_header;
		$content = $bookingSettings->cancelation_email_content;
	}
	
	$adult = json_decode($adult);
	$child = json_decode($child);

	$email_content = $content."<br><br>";
	
	//Booking Info
	$email_content .= "<strong>".__("Reservation ID","nation").":</strong> ".$reservation_id."<br>";
	$email_content .= "<strong>".__("Calendar ID","nation").":</strong> ".$cal_id."<br>";
	$email_content .= "<strong>".__("Room Type","nation").":</strong> ".$calendar->cal_name."<br>";
	$email_content .= "<strong>".__("Number of booked items","nation").":</strong> ".$items."<br>";
	
	for ($i=0;$i<$items;$i++) {
		$email_content .= "<strong>".sprintf(__("Room %d visitors","nation"),$i+1).":</strong> ".__("Adults:","nation")." ".$adult[$i]."; ".__("Children:","nation")." ".$child[$i]."<br>";
	}
	
	$email_content .= "<br>";
	
	//Check in and check out date
	$email_content .= "<strong>".__("Check In","nation").":</strong> ".$checkin."<br>";
	$email_content .= "<strong>".__("Check Out","nation").":</strong> ".$checkout."<br>";
	$email_content .= "<br>";
	
	//Personal Info
	$email_content .= "<strong>".__("Name","nation").":</strong> ".$name."<br>";
	$email_content .= "<strong>".__("Surname","nation").":</strong> ".$surname."<br>";
	$email_content .= "<strong>".__("Email","nation").":</strong> ".$email."<br>";
	$email_content .= "<strong>".__("Card type","nation").":</strong> ".$cardtype."<br>";
	$email_content .= "<strong>".__("Cardholder name","nation").":</strong> ".$cardholder."<br>";
	$email_content .= "<strong>".__("Card number","nation").":</strong> ".$cardnumber."<br>";
	$email_content .= "<strong>".__("Expiration date","nation").":</strong> ".$expmonth."/".$expyear."<br>";
	if ( isset($comments) && $comments != "" ) { $email_content .= "<strong>".__("Comments","nation").":</strong> ".$comments."<br>"; }
	$email_content .= "<br>";
	
	//Total Info
	$email_content .= "<strong>".__("Total","nation").":</strong> ".$total.$bookingSettings->currency_symbol;
	
	//Send email with reservation info to user
	nation_send($email, $bookingSettings->email, $subject, $email_content);
	
	//Send email with reservation info to admin
	nation_send($bookingSettings->email, $email , $subject, $email_content);
	
}

function days_in_month($month, $year) { 
	if (checkdate($month, 31, $year)) return 31; 
	if (checkdate($month, 30, $year)) return 30; 
	if (checkdate($month, 29, $year)) return 29; 
	if (checkdate($month, 28, $year)) return 28; 
	return 0;  
}



function nation_send($email, $emailfrom, $subject, $content){
    $header = "Content-type: text/html; charset=utf-8"."\r\n". "From: ".get_bloginfo('name')." <".$emailfrom.">\r\n". "Reply-To:".$emailfrom;
    wp_mail($email, $subject, $content, $header);
}

?>