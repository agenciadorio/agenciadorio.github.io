<?php 
/*
	Plugin Name: Nation Booking System
    Plugin URI: http://themeforest.net
    Description: The booking system plugin for the Nation Hotel theme
    Author: Ray Basil
    Version: 1.2
    Author URI: http://themeforest.net/user/raybreaker
*/

define("NATION_BOOKING_ACTIVE", true);

include_once 'db-modifications.php';
include_once 'utility-functions.php';
include_once 'import-export.php';
include_once 'calendar-shortcode.php';

load_plugin_textdomain('nation', false, basename( dirname( __FILE__ ) ) . '/languages' );


function nation_booking_install() {
	global $wpdb;
	
	$table_name = $wpdb->prefix . "nation_booking_calendars";
	$table_name2 = $wpdb->prefix . "nation_booking_availability";
	$table_name3 = $wpdb->prefix . "nation_booking_reservation";
	$table_name4 = $wpdb->prefix . "nation_booking_settings";
	
	$nation_calendars = "CREATE TABLE $table_name (
		id INT NOT NULL AUTO_INCREMENT,
		cal_name VARCHAR(128) DEFAULT '".__("New Calendar", "nation")."' COLLATE utf8_unicode_ci NOT NULL,
		min_price FLOAT DEFAULT 0 NOT NULL,
        UNIQUE KEY id (id)
    );";
	
	$nation_availability = "CREATE TABLE $table_name2 (
		id INT NOT NULL AUTO_INCREMENT,
		calendar_id INT DEFAULT '0' NOT NULL,
		day INT(2) DEFAULT '0' NOT NULL,
		month INT(2) DEFAULT '0' NOT NULL,
		year INT(4) DEFAULT '0' NOT NULL,
		availability INT DEFAULT '0' NOT NULL,
		price FLOAT DEFAULT '0' NOT NULL,
		UNIQUE KEY id (id)
    );";
	
	$nation_reservation = "CREATE TABLE $table_name3 (
		id INT NOT NULL AUTO_INCREMENT,
		calendar_id INT DEFAULT 0 NOT NULL,
        check_in VARCHAR(16) DEFAULT '' COLLATE utf8_unicode_ci NOT NULL,
        check_out VARCHAR(16) DEFAULT '' COLLATE utf8_unicode_ci NOT NULL,
        no_items INT DEFAULT '1' NOT NULL,
        price FLOAT DEFAULT '0' NOT NULL,
        email VARCHAR(128) DEFAULT '' COLLATE utf8_unicode_ci NOT NULL,
        no_adult TEXT COLLATE utf8_unicode_ci NOT NULL,
        no_children TEXT COLLATE utf8_unicode_ci NOT NULL,
        status VARCHAR(16) DEFAULT 'pending' COLLATE utf8_unicode_ci NOT NULL,
		name TEXT COLLATE utf8_unicode_ci NOT NULL,
		surname TEXT COLLATE utf8_unicode_ci NOT NULL,
		paypal_payment TINYINT(1) DEFAULT 0 COLLATE utf8_unicode_ci NOT NULL,
		paypal_payer_id TEXT COLLATE utf8_unicode_ci NOT NULL,
		paypal_transaction_id TEXT COLLATE utf8_unicode_ci NOT NULL,
		cardholder_name TEXT COLLATE utf8_unicode_ci NOT NULL,
		card_type TEXT COLLATE utf8_unicode_ci NOT NULL,
		card_number TEXT COLLATE utf8_unicode_ci NOT NULL,
		expiration_year INT DEFAULT 0 NOT NULL,
		expiration_month INT DEFAULT 0 NOT NULL,
		comments TEXT COLLATE utf8_unicode_ci NOT NULL,
		date_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
        UNIQUE KEY id (id)
    );";
	
	$nation_settings = "CREATE TABLE $table_name4 (
		id INT NOT NULL AUTO_INCREMENT,
		email VARCHAR(128) DEFAULT 'none' COLLATE utf8_unicode_ci NOT NULL,
		currency_symbol TEXT(10) COLLATE utf8_unicode_ci NOT NULL,
		date_format TEXT(10) COLLATE utf8_unicode_ci NOT NULL,
		hide_tax TINYINT(1) DEFAULT 0 COLLATE utf8_unicode_ci NOT NULL,
		add_tax TINYINT(1) DEFAULT 0 COLLATE utf8_unicode_ci NOT NULL,
		tax TEXT(10) COLLATE utf8_unicode_ci NOT NULL,
		enable_coupon TINYINT(1) DEFAULT 0 COLLATE utf8_unicode_ci NOT NULL,
		coupon_name TEXT COLLATE utf8_unicode_ci NOT NULL,
		coupon_discount TEXT COLLATE utf8_unicode_ci NOT NULL,
		coupon_name2 TEXT COLLATE utf8_unicode_ci NOT NULL,
		coupon_discount2 TEXT COLLATE utf8_unicode_ci NOT NULL,
		coupon_name3 TEXT COLLATE utf8_unicode_ci NOT NULL,
		coupon_discount3 TEXT COLLATE utf8_unicode_ci NOT NULL,
		confirmation_email_header TEXT COLLATE utf8_unicode_ci NOT NULL,
		confirmation_email_content TEXT COLLATE utf8_unicode_ci NOT NULL,
		cancelation_email_header TEXT COLLATE utf8_unicode_ci NOT NULL,
		cancelation_email_content TEXT COLLATE utf8_unicode_ci NOT NULL,
		without_confirmation_email_header TEXT COLLATE utf8_unicode_ci NOT NULL,
		without_confirmation_email_content TEXT COLLATE utf8_unicode_ci NOT NULL,
		rejected_email_header TEXT COLLATE utf8_unicode_ci NOT NULL,
		rejected_email_content TEXT COLLATE utf8_unicode_ci NOT NULL,
		paypal_enabled TINYINT(1) DEFAULT 0 COLLATE utf8_unicode_ci NOT NULL,
		paypal_api_username TEXT COLLATE utf8_unicode_ci NOT NULL,
		paypal_api_password TEXT COLLATE utf8_unicode_ci NOT NULL,
		paypal_api_signature TEXT COLLATE utf8_unicode_ci NOT NULL,
		paypal_currency_code TEXT COLLATE utf8_unicode_ci NOT NULL,
		sandbox_enabled TINYINT(1) DEFAULT 0 COLLATE utf8_unicode_ci NOT NULL,
		UNIQUE KEY id (id)
    );";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	
	dbDelta( $nation_calendars );
	dbDelta( $nation_availability );
	dbDelta( $nation_reservation );
	dbDelta( $nation_settings );

	dbDelta( $wpdb->insert( $table_name4, array(
		'id' => 1,
        'email' => 'test@email.com',
        'currency_symbol' => '$',
		'paypal_currency_code' => 'USD',
		'date_format' => 'american',
		'tax' => '0',
		'enable_coupon' => 0
	)));
}
register_activation_hook( __FILE__, 'nation_booking_install' );


function nation_booking_uninstall() {
	global $wpdb;

	$table_name = $wpdb->prefix . "nation_booking_calendars";
	$table_name2 = $wpdb->prefix . "nation_booking_availability";
	$table_name3 = $wpdb->prefix . "nation_booking_reservation";
	$table_name4 = $wpdb->prefix . "nation_booking_settings";

	$wpdb->query("DROP TABLE IF EXISTS $table_name");
	$wpdb->query("DROP TABLE IF EXISTS $table_name2");
	$wpdb->query("DROP TABLE IF EXISTS $table_name3");
	$wpdb->query("DROP TABLE IF EXISTS $table_name4");
}
register_uninstall_hook(__FILE__, 'nation_booking_uninstall');


function nation_booking_show_admin() {
	global $wpdb;
		
	echo "<h2>".__("Current Calendars:","nation")."</h2>";
	
	$calendars = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'nation_booking_calendars ORDER BY id ASC');
	
	if ($wpdb->num_rows != 0){
		if ($calendars){
			
			echo "<table class='calendar-table'><tr><th>".__("Id","nation")."</th><th>".__("Calendar Name","nation")."</th><th>".__("Min Price","nation")."</th></tr>";
			
			foreach( $calendars as $calendar ) {
				echo "<tr class='row-wrap header-row'><td class='content-row'>$calendar->id</td><td class='content-row'>$calendar->cal_name</td><td class='content-row'>$calendar->min_price</td><td class='content-row button-wrap'><div class='cal-edit button button-primary'>".__("Edit","nation")."</div>
				<form method='POST' action='admin-post.php' id='delete-form'>";
				wp_nonce_field( 'nation_delete_calendar_verify' ); 
				echo "<input type='submit' value=".__("Delete","nation")." class='cal-delete-button button button-secondary'>
					<input type='hidden' name='action' value='nation_booking_delete_calendar' />
					<input type='hidden' name='cal_id' value='$calendar->id' />
				</form>";
				echo "<td class='edit-row'>$calendar->id</td><td class='edit-row' colspan='3'>
					<form method='POST' action='admin-post.php' id='cal_edit_form'>";
					wp_nonce_field( 'nation_edit_calendar_verify' ); 
				echo "	<input type='text' name='cal_name' value='$calendar->cal_name'>
						<input type='text' name='cal_min_price' value='$calendar->min_price'>
						<input type='hidden' name='action' value='nation_booking_edit_calendar' />
						<input type='hidden' name='cal_id' value='$calendar->id' />
						<input type='submit' value=".__("Submit","nation")." class='button button-primary'>
						<input type='button' value=".__("Cancel","nation")." class='cal_edit_cancel button button-secondary'>
					</form>
				</td></tr>";
			}
		}
	} else {
		_e("There's no calendars found. You can create new calendars using form below.","nation");
		echo "<br>";
	}
	
	echo "</table>";
	echo "<br><hr><br><h2>".__("Create New Calendar:","nation")."</h2>";
	
	echo "<div class='wrap'><form method='POST' action='admin-post.php' id='add-calendar-form'>";
	wp_nonce_field( 'nation_add_calendar_verify' ); 
	echo "<table class='cal-table'><tr><td class='label'><b>".__("Calendar Name","nation")."</b></td><td><input type='text' name='cal_name' id='cal-name'></td></tr>
	<tr><td class='label'><b>".__("Min Price","nation")."</b></td><td><input type='text' name='cal_min_price' id='cal-min-price'></td></tr></table>
	<input type='hidden' name='action' value='nation_booking_add_calendar' />
	<br><input type='submit' value=".__("Submit","nation")." class='button button-primary'>
	</form></div>";
}


function nation_booking_show_reservations() {
	global $wpdb;
	
	$calendars = $wpdb->get_results('SELECT * FROM ' .$wpdb->prefix. 'nation_booking_calendars');
		
	echo "<h2>".__("Select Calendar:","nation")."</h2>";
	echo "<select id='select-calendar-reservation'><option>".__("Select Calendar","nation")."</option>";
		
	if ($wpdb->num_rows != 0) {
		if ($calendars){
			foreach( $calendars as $calendar ) {
				echo "<option value='$calendar->id'>$calendar->cal_name (ID: $calendar->id)</option>";
			}
		}
	}
			
	echo "</select>";
	echo "<div id='show-reservation-table'></div><br><hr><br>";
}


function nation_booking_show_calendar_ajax() {
	global $wpdb;
	
	if ( isset( $_REQUEST['cal_id'] ) ) {
		$calID = $_REQUEST['cal_id'];
		
		if ( isset( $_REQUEST['calendar_month'] ) ) {
			$month = $_REQUEST['calendar_month'];
		} else {
			$month = date("n");
		}
	
		if ( isset( $_REQUEST['calendar_year'] ) ) {
			$year = $_REQUEST['calendar_year'];
		} else {
			$year = date("Y");
		}
		
		$cal_year = $year;
		
		switch($month){ 
			case "1": $title = __("January","nation"); break; 
			case "2": $title = __("February","nation"); break; 
			case "3": $title = __("March","nation"); break; 
			case "4": $title = __("April","nation"); break; 
			case "5": $title = __("May","nation"); break; 
			case "6": $title = __("June","nation"); break; 
			case "7": $title = __("July","nation"); break; 
			case "8": $title = __("August","nation"); break; 
			case "9": $title = __("September","nation"); break; 
			case "10": $title = __("October","nation"); break; 
			case "11": $title = __("November","nation"); break; 
			case "12": $title = __("December","nation"); break; 
		}
	
	?> <select id='calendar-month'>
		<option><?php _e("Select Month","nation") ?></option>
		<option <?php if ( $month == 1 ) { echo 'selected="selected"'; } ?> value='1'><?php _e("January","nation") ?></option>
		<option <?php if ( $month == 2 ) { echo 'selected="selected"'; } ?> value='2'><?php _e("February","nation") ?></option>
		<option <?php if ( $month == 3 ) { echo 'selected="selected"'; } ?> value='3'><?php _e("March","nation") ?></option>
		<option <?php if ( $month == 4 ) { echo 'selected="selected"'; } ?> value='4'><?php _e("April","nation") ?></option>
		<option <?php if ( $month == 5 ) { echo 'selected="selected"'; } ?> value='5'><?php _e("May","nation") ?></option>
		<option <?php if ( $month == 6 ) { echo 'selected="selected"'; } ?> value='6'><?php _e("June","nation") ?></option>
		<option <?php if ( $month == 7 ) { echo 'selected="selected"'; } ?> value='7'><?php _e("July","nation") ?></option>
		<option <?php if ( $month == 8 ) { echo 'selected="selected"'; } ?> value='8'><?php _e("August","nation") ?></option>
		<option <?php if ( $month == 9 ) { echo 'selected="selected"'; } ?> value='9'><?php _e("September","nation") ?></option>
		<option <?php if ( $month == 10 ) { echo 'selected="selected"'; } ?> value='10'><?php _e("October","nation") ?></option>
		<option <?php if ( $month == 11 ) { echo 'selected="selected"'; } ?> value='11'><?php _e("November","nation") ?></option>
		<option <?php if ( $month == 12 ) { echo 'selected="selected"'; } ?> value='12'><?php _e("December","nation") ?></option>
	</select><select id='calendar-year'>
		<option><?php _e("Select Year","nation") ?></option>
		<option <?php if ( $year == 2014 ) { echo 'selected="selected"'; } ?> value='2014'>2014</option>
		<option <?php if ( $year == 2015 ) { echo 'selected="selected"'; } ?> value='2015'>2015</option>
		<option <?php if ( $year == 2016 ) { echo 'selected="selected"'; } ?> value='2016'>2016</option>
		<option <?php if ( $year == 2017 ) { echo 'selected="selected"'; } ?> value='2017'>2017</option>
		<option <?php if ( $year == 2018 ) { echo 'selected="selected"'; } ?> value='2018'>2018</option>
		<option <?php if ( $year == 2019 ) { echo 'selected="selected"'; } ?> value='2019'>2019</option>
	</select>
	<?php 
		$availabilities = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'nation_booking_availability WHERE calendar_id='.$calID.' AND month='.$month.' AND year='.$year);		
		$currencySymbol = $wpdb->get_var( "SELECT currency_symbol FROM ".$wpdb->prefix."nation_booking_settings");
		
		//This gets today's date
		$date =time() ;

		//This puts the day, month, and year in seperate variables
		$day = date('d', $date) ;


		//Here we generate the first day of the month
		$first_day = mktime(0,0,0,$month, 1, $year) ;
	
		//Here we find out what day of the week the first day of the month falls on 
		$day_of_week = date('D', $first_day) ; 

		//Once we know what day of the week it falls on, we know how many blank days occure before it. If the first day of the week is a Sunday then it would be zero
		switch($day_of_week){ 
			case "Sun": $blank = 0; break; 
			case "Mon": $blank = 1; break; 
			case "Tue": $blank = 2; break; 
			case "Wed": $blank = 3; break; 
			case "Thu": $blank = 4; break; 
			case "Fri": $blank = 5; break; 
			case "Sat": $blank = 6; break; 
		}
		
		//We then determine how many days are in the current month
		$days_in_month = days_in_month($month, $year); 
		
		if ($wpdb->num_rows != 0) {
			if ($availabilities){				
		
					//Here we start building the table heads 
					echo "<table id='booking-calendar'>";

					echo "<tr class='month-year'><th colspan='7' class='datayear'> $title $cal_year </th></tr>";
					echo "<tr class='header'><td>".__("Sun","nation")."</td><td>".__("Mon","nation")."</td><td>".__("Tue","nation")."</td><td>".__("Wed","nation")."</td><td>".__("Thu","nation")."</td><td>".__("Fri","nation")."</td><td>".__("Sat","nation")."</td></tr>";
	
					//This counts the days in the week, up to 7
					$day_count = 1;
					echo "<tr class='day-rows'>";

					//first we take care of those blank days
					while ( $blank > 0 ) { 
						echo "<td></td>"; 
						$blank = $blank-1; 
						$day_count++;
					}

					//sets the first day of the month to 1 
					$day_num = 1;

					//count up the days, untill we've done all of them in the month
					while ( $day_num <= $days_in_month ) { 
						$price_availty_set = false;
						echo "<td ";
						for ($i=0;$i<$days_in_month;$i++) {
							if ( isset( $availabilities[$i]) ) {
								if ( $availabilities[$i]->day == $day_num ) {
									$price_availty_set = true;
									if ( isset($availabilities[$i]->availability) ) {
										if ( $availabilities[$i]->availability > 0 ) { 
											echo "class='cell-available'> <div class='available'>".$day_num." "."</div>";
											echo "<div class='avail-price'>".$currencySymbol.$availabilities[$i]->price."</div>";
											echo "<div class='status'>".$availabilities[$i]->availability." ".__('available','nation')."</div></td>";
										} else if ( $availabilities[$i]->availability == 0 ) {
											echo "class='cell-booked'><div class='booked'>".$day_num."</div>";
											echo "<div>&nbsp;</div>";
											echo "<div class='status'>".__('booked','nation')."</div></td>";
										}
									}
								}
							}		
							if ( $price_availty_set ) {
								break;
							}
						}
		
						if ( !$price_availty_set ) {
							echo "class='cell-notset'> <div class='notset'>$day_num</div></td>";
						}
						$day_num++; 
						$day_count++;

						//Make sure we start a new row every week

						if ($day_count > 7) {
							echo "</tr><tr class='day-rows'>";	
							$day_count = 1;
						}
					}
	
					//Finaly we finish out the table with some blank details if needed
					while ( $day_count >1 && $day_count <=7 ) { 
						echo "<td> </td>"; 
						$day_count++; 
					}	 

					echo "</tr></table><br><br><hr><br>"; 
						
				} else {
					
					//Here we start building the table heads 
					echo "<table id='booking-calendar'>";

					echo "<tr class='month-year'><th colspan='7' class='datayear'> $title $cal_year </th></tr>";
					echo "<tr class='header'><td>".__("Sun","nation")."</td><td>".__("Mon","nation")."</td><td>".__("Tue","nation")."</td><td>".__("Wed","nation")."</td><td>".__("Thu","nation")."</td><td>".__("Fri","nation")."</td><td>".__("Sat","nation")."</td></tr>";
	
					//This counts the days in the week, up to 7
					$day_count = 1;
					echo "<tr class='day-rows'>";
	
					//first we take care of those blank days
					while ( $blank > 0 ) { 
						echo "<td></td>"; 
						$blank = $blank-1; 
						$day_count++;
					}

					//sets the first day of the month to 1 
					$day_num = 1;

					//count up the days, untill we've done all of them in the month
					while ( $day_num <= $days_in_month ){ 
						echo "<td class='cell-notset'> <div class='notset'>$day_num</div> </td>"; 
						$day_num++; 
						$day_count++;

						//Make sure we start a new row every week
						if ($day_count > 7) {
							echo "</tr><tr class='day-rows'>";
							$day_count = 1;
						}

					}
					
					//Finaly we finish out the table with some blank details if needed
					while ( $day_count >1 && $day_count <=7 ) { 
						echo "<td> </td>"; 
						$day_count++; 
					} 
					echo "</tr></table><br><br><hr><br>"; 
										
				}
			}
			
			echo "<h2>".__("Set/Edit Calendar Data:","nation")."</h2>";
			echo "<form method='POST' action='admin-post.php'>
			<table class='set-data availty-price'><tr><td class='label'><strong>".__("Start Data","nation")."</strong></td><td><input type='text' name='check-in' class='check-in-date date-picker-field'></td></tr>
			<tr><td class='label'><strong>".__("End Data","nation")."</strong></td><td><input type='text' name='check-out' class='check-out-date date-picker-field'></td></tr>
			<tr><td class='label'><strong>".__("Availability","nation")."</strong></td><td><input type='text' name='availability' class='regular-text'></td></tr>";
			wp_nonce_field( 'nation_date_modification_verify', 'nation_date_modification_verify' ); 
			echo "<tr><td class='label'><strong>".__("Price","nation")."</strong></td><td><input type='text' name='price' class='regular-text'></td></tr></table>
			<input type='hidden' name='action' value='nation_booking_date_modification' />
			<input type='hidden' name='cal_id' value='{$_REQUEST['cal_id']}' />
			<br><input type='submit' value=".__("Submit","nation")." class='button button-primary'>
			</form>";			
		}

		exit;
}


function nation_booking_get_calendar_data_ajax() {
	global $wpdb;
	
	if ( isset( $_REQUEST['cal_id'] ) && isset( $_REQUEST['year'] ) && isset( $_REQUEST['month'] ) ) {
		$availabilities = $wpdb->get_results('SELECT day,availability,price FROM '.$wpdb->prefix.'nation_booking_availability WHERE calendar_id='.$_REQUEST['cal_id'].' AND month='.$_REQUEST['month'].' AND year='.$_REQUEST['year']);
	}
	
	echo json_encode($availabilities);
	
	exit;
}
// Ajax Actions Defined
add_action('wp_ajax_nation_booking_get_calendar_data', 'nation_booking_get_calendar_data_ajax' );
add_action( 'wp_ajax_nopriv_nation_booking_get_calendar_data', 'nation_booking_get_calendar_data_ajax' );


// Ajax Actions Defined
add_action('wp_ajax_nation_show_calendar', 'nation_booking_show_calendar_ajax' );


function nation_booking_add_edit_price() {
	global $wpdb;
	
	$calendars = $wpdb->get_results('SELECT * FROM ' .$wpdb->prefix. 'nation_booking_calendars');
		
	echo "<h2>".__("Select Calendar:","nation")."</h2>";
	echo "<select id='select-calendar-price'><option>".__("Select Calendar","nation")."</option>";
		
	if ($wpdb->num_rows != 0) {
		if ($calendars){
			foreach( $calendars as $calendar ) {
				echo "<option value='$calendar->id'>$calendar->cal_name (ID: $calendar->id)</option>";
			}
		}
	}
		
	echo "</select><br><br><hr><br>";
	echo "<div id='show-price-calendar'></div>";
} 


function nation_booking_settings() {
	global $wpdb;
		
	$settings = $wpdb->get_row('SELECT * FROM '.$wpdb->prefix.'nation_booking_settings');
	echo "<h2>".__("Booking System Settings","nation")."</h2>";
	
	if ($settings) {
		echo "<form method='POST' action='admin-post.php'>";
		wp_nonce_field( 'nation_date_settings_edit' ); 
		echo "<table class='option-table'><tr><td class='label'><strong>".__("Email:","nation")." </strong></td><td><input type='type' name='email' value='$settings->email' class='regular-text' /></td></tr>
		<tr><td class='label'><strong>".__("Currency symbol:","nation")." </strong></td><td><input type='type' name='currency_symbol' value='$settings->currency_symbol' class='regular-text' /></td></tr>
		<tr><td class='label'><strong>".__("Date format:","nation")." </strong></td><td>
			<select name='date_format'>
				<option ";
				
		if ( $settings->date_format == "american" ) { echo "selected='selected'"; }
		echo " value='american'>American (MM/DD/YYYY)</option>
				<option ";
		if ( $settings->date_format == "european" ) { echo "selected='selected'"; }
		echo " value='european'>European (DD/MM/YYYY)</option>
			</select>
		</td></tr>
		<tr><td class='label'><strong>".__("Hide Tax Info:","nation")." </strong></td><td><fieldset><label for='hide_tax'><input type='checkbox' id='hide_tax' name='hide_tax' ";
		if ( $settings->hide_tax == 1 ) {
			echo "checked ";
		}
		echo "value='1'>Yes </label></fieldset></td></tr>
		<tr><td class='label'><strong>".__("Add Tax to Price Calculation:","nation")." </strong></td><td><fieldset><label for='add_tax'><input type='checkbox' id='add_tax' name='add_tax' ";
		if ( $settings->add_tax == 1 ) {
			echo "checked ";
		}
		echo "value='1'>Yes </label></fieldset></td></tr>
		<tr><td class='label'><strong>".__("Tax:","nation")." </strong></td><td><input type='type' name='tax' value='$settings->tax' class='regular-text' /><br><span style='color:#999'>(".__("please enter without the percent sign","nation").")</span></td></tr><br>
		
		<tr><td class='label'><strong>".__("Enable coupon:","nation")." </strong></td>
			<td><fieldset><label for='enable_coupon'>
					<input type='checkbox' id='enable_coupon' name='enable_coupon' ".( ( $settings->enable_coupon == 1 ) ? "checked " : "" )."value='1'>Yes </label>
				</fieldset>
			</td>
		</tr>
	
		<tr><td class='label'><strong>".__("Coupon Name:","nation")." </strong></td><td><input type='type' name='coupon_name' value='$settings->coupon_name' class='regular-text' /></td></tr>
		<tr><td class='label'><strong>".__("Coupon Discount:","nation")." </strong></td><td><input type='type' name='coupon_discount' value='$settings->coupon_discount' class='regular-text' /><br><span style='color:#999'>(".__("please enter without the percent sign","nation").")</span></td></tr>
		<tr><td class='label'><strong>".__("Coupon Name 2:","nation")." </strong></td><td><input type='type' name='coupon_name2' value='$settings->coupon_name2' class='regular-text' /></td></tr>
		<tr><td class='label'><strong>".__("Coupon Discount 2:","nation")." </strong></td><td><input type='type' name='coupon_discount2' value='$settings->coupon_discount2' class='regular-text' /><br><span style='color:#999'>(".__("please enter without the percent sign","nation").")</span></td></tr>
		<tr><td class='label'><strong>".__("Coupon Name 3:","nation")." </strong></td><td><input type='type' name='coupon_name3' value='$settings->coupon_name3' class='regular-text' /></td></tr>
		<tr><td class='label'><strong>".__("Coupon Discount 3:","nation")." </strong></td><td><input type='type' name='coupon_discount3' value='$settings->coupon_discount3' class='regular-text' /><br><span style='color:#999'>(".__("please enter without the percent sign","nation").")</span></td></tr>
		
		<tr><td></td><td></td></tr>
		<tr><td class='label'><strong>".__("Not confirmed email header:","nation")." </strong></td><td><input type='type' name='without_confirmation_email_header' value='$settings->without_confirmation_email_header' class='regular-text' /></td></tr>
		<tr class='extra-height'><td class='label'><strong>".__("Not confirmed email content:","nation")." </strong></td><td><textarea name='without_confirmation_email_content' class='large-text'>$settings->without_confirmation_email_content</textarea></td></tr>
		<tr><td class='label'><strong>".__("Confirmed email header:","nation")." </strong></td><td><input type='type' name='confirmation_email_header' value='$settings->confirmation_email_header' class='regular-text' /></td></tr>
		<tr class='extra-height'><td class='label'><strong>".__("Confirmed email content:","nation")." </strong></td><td><textarea name='confirmation_email_content' class='large-text'>$settings->confirmation_email_content</textarea></td></tr>
		<tr><td class='label'><strong>".__("Canceled email header:","nation")." </strong></td><td><input type='type' name='cancelation_email_header' value='$settings->cancelation_email_header' class='regular-text' /></td></tr>
		<tr class='extra-height'><td class='label'><strong>".__("Canceled email content:","nation")." </strong></td><td><textarea name='cancelation_email_content' class='large-text'>$settings->cancelation_email_content</textarea></td></tr>
		<tr><td class='label'><strong>".__("Rejected email header:","nation")." </strong></td><td><input type='type' name='rejected_email_header' value='$settings->rejected_email_header' class='regular-text' /></td></tr>
		<tr class='extra-height'><td class='label'><strong>".__("Rejected email content:","nation")." </strong></td><td><textarea name='rejected_email_content' class='large-text'>$settings->rejected_email_content</textarea></td></tr>		
		<input type='hidden' name='action' value='nation_booking_settings_edit' /></table>
		<br><input type='submit' value='".__("Save changes","nation")."' class='button button-primary' class='button button-primary'>
		</form>";
	}
}


function nation_booking_paypal_settings() {
	global $wpdb;
		
	$settings = $wpdb->get_row('SELECT * FROM '.$wpdb->prefix.'nation_booking_settings');
	echo "<h2>".__("PayPal Settings","nation")."</h2>";
	if ($settings) {
		echo "<form method='POST' action='admin-post.php'>";
		wp_nonce_field( 'nation_date_paypal_settings_edit' ); 
		echo "<table class='option-table'><tr><td class='label'><strong>".__("Enable PayPal payment:","nation")." </strong></td><td><input type='checkbox' name='paypal_enabled' ";
		if ( isset($settings->paypal_enabled) && $settings->paypal_enabled ) { echo "checked "; } 
		echo "value='1'> Yes</td></tr>
		<tr><td class='label'><strong>".__("Enable Sandbox mode","nation")."</strong></td><td><input type='checkbox' name='paypal_sandbox_enabled' ";
		if ( isset($settings->sandbox_enabled) && $settings->sandbox_enabled ) { echo "checked "; } 
		echo "value='1'> Yes</td></tr>
		<tr><td class='label'><strong>".__("API username:","nation")." </strong></td><td><input type='type' name='api_username' value='$settings->paypal_api_username' class='regular-text' /></td></tr>
		<tr><td class='label'><strong>".__("API password:","nation")." </strong></td><td><input type='type' name='api_password' value='$settings->paypal_api_password' class='regular-text' /></td></tr>
		<tr><td class='label'><strong>".__("API signature:","nation")." </strong></td><td><input type='type' name='api_signature' value='$settings->paypal_api_signature' class='regular-text' /></td></tr><br>
		<tr><td class='label'><strong>".__("PayPal currency code:","nation")." </strong></td><td><input type='type' name='paypal_currency_code' value='$settings->paypal_currency_code' class='regular-text' /><br>".__("(currency code list: https://developer.paypal.com/docs/classic/api/currency_codes/)","nation")."</td></tr><br>
		
		<input type='hidden' name='action' value='nation_booking_paypal_settings_edit' /></table>
		<br><input type='submit' value='".__("Save changes","nation")."' class='button button-primary' class='button button-primary'>
		</form>";
	}
}


function nation_booking_ajax_show_reservations() {
	global $wpdb;
		
	if ( isset ($_REQUEST['cal_id']) ) {	
	
		$currencySymbol = $wpdb->get_var( "SELECT currency_symbol FROM ".$wpdb->prefix."nation_booking_settings");
		$reservations = $wpdb->get_results( $wpdb->prepare(
			'SELECT * FROM '.$wpdb->prefix.'nation_booking_reservation WHERE calendar_id=%d ORDER BY id ASC',$_REQUEST['cal_id']
			)
		);
		
		echo "<br><h2>".__("Active Reservations:","nation")."</h2>";
		
		if ($wpdb->num_rows != 0) {
			if ($reservations){
				
				echo "<table class='reservation-table'>
				<tr>
					<th>".__("No.","nation")."</th>
					<th>".__("Status","nation")."</th>
					<th>".__("Check In","nation")."</th>
					<th>".__("Check Out","nation")."</th>
					<th>".__("Visitors","nation")."</th>
					<th>".__("Email","nation")."</th>
					<th>".__("Info","nation")."</th>
					<th>".__("Price","nation")."</th>
				</tr>";
			
				foreach( $reservations as $reservation ) {
					echo "<tr class='row-wrap ";
					
					if ( $reservation->status == "approved" ) {
						echo "approved";
					} else if ( $reservation->status == "pending" ) {
						echo "pending";
					} else if ( $reservation->status == "rejected" || $reservation->status == "canceled" ) {
						echo "rejected";
					}
					
					echo "'>
						<td class='content-row'>$reservation->id</td>
						<td class='content-row'>";
						
					if ( $reservation->status == "approved" ) {
						_e("Approved","nation");
					} else if ( $reservation->status == "pending" ) {
						_e("Pending","nation");
					} else if ( $reservation->status == "rejected" || $reservation->status == "canceled" ) {
						_e("Rejected","nation");
					}
						
					echo "</td>
						<td class='content-row'>$reservation->check_in</td>
						<td class='content-row'>$reservation->check_out</td>
						<td class='content-row'>";
					
					$noAdult = json_decode($reservation->no_adult);
					$noChild = json_decode($reservation->no_children);
					
					for ($i=0;$i<$reservation->no_items;$i++) {
						printf(__("Room %d: Adults: %d, Children: %d","nation"),$i+1,$noAdult[$i],$noChild[$i]);
						echo "<br>";
					}
					
					echo "</td>
						<td class='content-row'>$reservation->email</td>";
						
					if ( $reservation->paypal_payment == 0 ) {
						echo "<td class='content-row'>
							".__("name:","nation")." &nbsp;<b>$reservation->name $reservation->surname</b> <br>
							".__("card type:","nation")." &nbsp;<b>$reservation->card_type</b> <br>
							".__("cardholder name:","nation")." &nbsp;<b>$reservation->cardholder_name</b> <br>	
							".__("card number:","nation")." &nbsp;<b>$reservation->card_number</b> <br>	
							".__("expiration date","nation")." &nbsp;<b>{$reservation->expiration_month}/{$reservation->expiration_year}</b> <br>
						</td>";
					} else if ( $reservation->paypal_payment == 1 ) {
						echo "<td class='content-row'>
							".__("Reservation paid through PayPal.","nation")."<br>
							".__("PayPal Transaction ID:","nation")." &nbsp;<b>$reservation->paypal_transaction_id</b> <br>
							".__("PayPal Payer ID:","nation")." &nbsp;<b>$reservation->paypal_payer_id</b> <br>
							".__("name:","nation")." &nbsp;<b>$reservation->name $reservation->surname</b> <br>
						</td>"; 
					}	
					echo "<td class='content-row'><div class='reservation-price-wrap'>$reservation->price{$currencySymbol}</div></td>";
					echo "<td class='content-row reservation-button-row'>&nbsp;<div class='edit-reservation-button button button-secondary'>".__("Edit","nation")."</div></td>";
						
					if ( $reservation->status == "pending" ) {
						echo "<td class='content-row reservation-button-row'><form method='POST' action='admin-post.php' id='approve-form'>";
						wp_nonce_field( 'nation_approve_reservation_verify' );
						
						echo "<input id='approve-button' type='submit' value=".__("Approve","nation")." class='approve-button button button-primary'>&nbsp;
						<input type='hidden' name='action' value='nation_booking_approve_reservation' />
						<input type='hidden' name='reservation_id' value='$reservation->id' />
						</form></td>
						
						<td class='content-row reservation-button-row'><form method='POST' action='admin-post.php' id='reject-form'>";
						wp_nonce_field( 'nation_reject_reservation_verify' );
						
						echo "<input id='reject-button' type='submit' value=".__("Reject","nation")." class='reject-button button button-secondary'>
						<input type='hidden' name='action' value='nation_booking_reject_reservation' />
						<input type='hidden' name='reservation_id' value='$reservation->id' />
						</form></td>";
					} else if ( $reservation->status == "approved" ) {
						echo "<td class='content-row reservation-button-row'><form method='POST' action='admin-post.php' id='cancel-form'>";
						wp_nonce_field( 'nation_cancel_reservation_verify' );
						
						echo "<input id='cancel-button' type='submit' value=".__("Cancel","nation")." class='cancel-button button button-secondary'>
						<input type='hidden' name='action' value='nation_booking_cancel_reservation' />
						<input type='hidden' name='reservation_id' value='$reservation->id' />
						</form></td>";
					} else if ( $reservation->status == "canceled" ) {
						echo "<td class='content-row reservation-button-row'><form method='POST' action='admin-post.php' id='approve-form'>";
						wp_nonce_field( 'nation_approve_reservation_verify' );
						
						echo "<input id='approve-button' type='submit' value=".__("Approve","nation")." class='approve-button button button-primary'>
						<input type='hidden' name='action' value='nation_booking_approve_reservation' />
						<input type='hidden' name='reservation_id' value='$reservation->id' />
						</form></td>
						
						<td class='content-row reservation-button-row'><form method='POST' action='admin-post.php'>";
						wp_nonce_field( 'nation_delete_reservation_verify' );
						
						echo "<input type='submit' value=".__("Delete","nation")." class='delete-button button button-secondary'>
						<input type='hidden' name='action' value='nation_booking_delete_reservation' />
						<input type='hidden' name='reservation_id' value='$reservation->id' />
						</form></td>";
					} else if ( $reservation->status == "rejected" ) {
						echo "<td class='content-row reservation-button-row'><form method='POST' action='admin-post.php' id='approve-form'>";
						wp_nonce_field( 'nation_approve_reservation_verify' );
						
						echo "<input id='approve-button' type='submit' value=".__("Approve","nation")." class='approve-button button button-primary'>
						<input type='hidden' name='action' value='nation_booking_approve_reservation' />
						<input type='hidden' name='reservation_id' value='$reservation->id' />
						</form></td>
						
						<td class='content-row reservation-button-row'><form method='POST' action='admin-post.php'>";
						wp_nonce_field( 'nation_delete_reservation_verify' );
							
						echo "<input type='submit' value=".__("Delete","nation")." class='delete-button button button-secondary'>
						<input type='hidden' name='action' value='nation_booking_delete_reservation' />
						<input type='hidden' name='reservation_id' value='$reservation->id' />
						</form></td>";
					}
					
					echo "<td class='edit-row const-row'>$reservation->id </td>
					<td class='edit-row const-row'>$reservation->status </td>
					
					<td class='edit-row edit-row-reservation-content' colspan='8'>";
					
					echo "<form method='POST' action='admin-post.php'>";
										
					wp_nonce_field( 'nation_edit_reservation_verify' ); 
					echo "<input type='hidden' name='action' value='nation_booking_edit_reservation' />
					<input type='hidden' name='reservation-id-edit' value='$reservation->id'>
					<input type='hidden' name='cal_id' value='$reservation->calendar_id' />
					<input type='hidden' name='ispaypal' value='$reservation->paypal_payment' />
					<div class='imitate-row'><input type='text' id='reservation-checkin-edit{$reservation->id}' class='add-datepicker-indication' name='reservation-checkin-edit' value='$reservation->check_in'></div>
					<div class='imitate-row'><input type='text' id='reservation-checkout-edit{$reservation->id}' class='add-datepicker-indication' name='reservation-checkout-edit' value='$reservation->check_out'></div>
					<div class='imitate-row'>
					room number: <select name='reservation-room-number-edit' id='reservation-room-number-edit'>
						<option ".( ( $reservation->no_items == 1 ) ? "selected " : "" )."value='1'>1</option>
						<option ".( ( $reservation->no_items == 2 ) ? "selected " : "" )."value='2'>2</option>
						<option ".( ( $reservation->no_items == 3 ) ? "selected " : "" )."value='3'>3</option>
						<option ".( ( $reservation->no_items == 4 ) ? "selected " : "" )."value='4'>4</option>
						<option ".( ( $reservation->no_items == 5 ) ? "selected " : "" )."value='5'>5</option>
						<option ".( ( $reservation->no_items == 6 ) ? "selected " : "" )."value='6'>6</option>
						<option ".( ( $reservation->no_items == 7 ) ? "selected " : "" )."value='7'>7</option>
						<option ".( ( $reservation->no_items == 8 ) ? "selected " : "" )."value='8'>8</option>
						<option ".( ( $reservation->no_items == 9 ) ? "selected " : "" )."value='9'>9</option>
						<option ".( ( $reservation->no_items == 10 ) ? "selected " : "" )."value='10'>10</option>
					</select><br>";
					
					
					for ($i=0;$i<10;$i++ ) {
						if ( isset($noAdult[$i]) && isset($noChild[$i] ) ) {
							echo "<div class='hide-reservation-edit-room reservation-edit-room-".($i+1)."'>
								room ".($i+1).": <select name='reservation-edit-room".($i+1)."-adult'>
									<option ".( ( $noAdult[$i] == 1 ) ? "selected " : "" )."value='1'>1</option>
									<option ".( ( $noAdult[$i] == 2 ) ? "selected " : "" )."value='2'>2</option>
									<option ".( ( $noAdult[$i] == 3 ) ? "selected " : "" )."value='3'>3</option>
									<option ".( ( $noAdult[$i] == 4 ) ? "selected " : "" )."value='4'>4</option>
									<option ".( ( $noAdult[$i] == 5 ) ? "selected " : "" )."value='5'>5</option>
									<option ".( ( $noAdult[$i] == 6 ) ? "selected " : "" )."value='6'>6</option>
									<option ".( ( $noAdult[$i] == 7 ) ? "selected " : "" )."value='7'>7</option>
									<option ".( ( $noAdult[$i] == 8 ) ? "selected " : "" )."value='8'>8</option>
								</select><select name='reservation-edit-room".($i+1)."-child'>
									<option ".( ( $noChild[$i] == 0 ) ? "selected " : "" )."value='0'>0</option>
									<option ".( ( $noChild[$i] == 1 ) ? "selected " : "" )."value='1'>1</option>
									<option ".( ( $noChild[$i] == 2 ) ? "selected " : "" )."value='2'>2</option>
									<option ".( ( $noChild[$i] == 3 ) ? "selected " : "" )."value='3'>3</option>
									<option ".( ( $noChild[$i] == 4 ) ? "selected " : "" )."value='4'>4</option>
									<option ".( ( $noChild[$i] == 5 ) ? "selected " : "" )."value='5'>5</option>
									<option ".( ( $noChild[$i] == 6 ) ? "selected " : "" )."value='6'>6</option>
									<option ".( ( $noChild[$i] == 7 ) ? "selected " : "" )."value='7'>7</option>
									<option ".( ( $noChild[$i] == 8 ) ? "selected " : "" )."value='8'>8</option>
								</select><br></div>";
						} else {
							echo "<div class='hide-reservation-edit-room reservation-edit-room-".($i+1)."'>
							room ".($i+1).": <select name='reservation-edit-room".($i+1)."-adult'>
								<option value='1'>1</option>
								<option value='2'>2</option>
								<option value='3'>3</option>
								<option value='4'>4</option>
								<option value='5'>5</option>
								<option value='6'>6</option>
								<option value='7'>7</option>
								<option value='8'>8</option>
							</select>
							<select name='reservation-edit-room".($i+1)."-child'>
								<option value='0'>0</option>
								<option value='1'>1</option>
								<option value='2'>2</option>
								<option value='3'>3</option>
								<option value='4'>4</option>
								<option value='5'>5</option>
								<option value='6'>6</option>
								<option value='7'>7</option>
								<option value='8'>8</option>
							</select><br></div>";
						}
					}
				
					echo "</div>
					
					<div class='imitate-row'><input type='text' name='reservation-email-edit' class='reservation-edit-email' value='$reservation->email'></div>";
					
					
					if ( $reservation->paypal_payment == 0 ) {
					
						echo "<div class='imitate-row'>name: <input name='reservation-name-edit' value='$reservation->name'><br>
							surname: <input name='reservation-surname-edit' value='$reservation->surname'><br>
							card type: <select name='reservation-cardtype-edit'>
								<option".( ($reservation->card_type == "americanexpress") ? " selected" : "")." value='americanexpress'>American Express</option>
								<option".( ($reservation->card_type == "mastercard") ? " selected" : "" )." value='mastercard'>Master Card</option>
								<option".( ($reservation->card_type == "visa") ? " selected" : "" )." value='visa'>Visa</option>
							</select><br>
							cardholder name: <input name='reservation-cardholder-name-edit' value='$reservation->cardholder_name'><br>
							card number: <input name='reservation-cardnumber-edit' value='$reservation->card_number'><br>
							expiration month <input name='reservation-expmonth-edit' value='$reservation->expiration_month'><br>
							expiration year <input name='reservation-expyear-edit' value='$reservation->expiration_year'><br>
						</div>";
					} else if ( $reservation->paypal_payment == 1 ) {
						echo "<div class='imitate-row'>name: <input name='reservation-name-edit' value='$reservation->name'><br>
						surname: <input name='reservation-surname-edit' value='$reservation->surname'></div>";
					}
					echo "<div class='imitate-row'><input name='reservation-price-edit' class='reservation-edit-price' value='$reservation->price'></div>
					<div class='imitate-row reservation-button-row'><input type='submit' value='".__("Submit","nation")."' class='reservation-apply-editing-button button button-primary'>&nbsp;</div>
					<div class='imitate-row reservation-button-row'><div class='cancel-edit-reservation button button-secondary'>".__("Cancel","nation")."</div></div>
					</form>
					</tr>";
				}
	
				echo "</table>"; 
		
			}
		} else {
			_e("There's no reservation found for this calendar.","nation");
			echo "<br>";
		}
		
		echo "<br><hr><br><h2>".__("Create a New Reservation:","nation")."</h2>";
	
		echo "<div class='wrap'><form method='POST' action='admin-post.php' id='reservation-add'>";
		wp_nonce_field( 'nation_add_reservation_verify' ); 
		echo "<table id='create-reservation-table'><tr><td class='label'><strong>".__("Check-in date","nation")."</strong></td><td><input type='text' name='check-in' class='check-in-date date-picker-field'></td></tr>
		<tr><td class='label'><strong>".__("Check-out date","nation")."</strong></td><td><input type='text' name='check-out' class='check-out-date date-picker-field'></td></tr>
		<tr><td class='label'><strong>".__("Number of room","nation")."</strong></td><td><input type='text' name='room-number' id='room-number' class='regular-text'></td></tr>
		<tr><td class='label'><strong>".__("Email","nation")."</strong></td><td><input type='text' name='email' id='email' class='regular-text'></td></tr>
		<tr><td class='label'><strong>".__("Adults","nation")."</strong></td><td><input type='text' name='adults' id='adults' class='regular-text'></td></tr>
		<tr><td class='label'><strong>".__("Children","nation")."</strong></td><td><input type='text' name='children' id='children' class='regular-text'></td></tr>
		<tr><td class='label'><strong>".__("Name","nation")."</strong></td><td><input type='text' name='name' id='name' class='regular-text'></td></tr>
		<tr><td class='label'><strong>".__("Surname","nation")."</strong></td><td><input type='text' name='surname' id='surname' class='regular-text'></td></tr>
		<tr><td class='label'><strong>".__("Card type","nation")."</strong></td><td>
			<select name='cardtype'>
				<option value='americanexpress'>American Express</option>
				<option value='mastercard'>Master Card</option>
				<option value='visa'>Visa</option>
			</select>
		</td></tr>		
		<tr><td class='label'><strong>".__("Cardholder name","nation")."</strong></td><td><input type='text' name='cardholder' id='cardholder' class='regular-text'></td></tr>
		<tr><td class='label'><strong>".__("Card number","nation")."</strong></td><td><input type='text' name='cardnumber' id='cardnumber' class='regular-text'></td></tr>
		<tr><td class='label'><strong>".__("Expiration month","nation")."</strong></td><td>
			<select name='expmonth'>
				<option value='01'>01</option>
				<option value='02'>02</option>
				<option value='03'>03</option>
				<option value='04'>04</option>
				<option value='05'>05</option>
				<option value='06'>06</option>
				<option value='07'>07</option>
				<option value='08'>08</option>
				<option value='09'>09</option>
				<option value='10'>10</option>
				<option value='11'>11</option>
				<option value='12'>12</option>
			</select>
		</td></tr>
		<tr><td class='label'><strong>".__("Expiration year","nation")."</strong></td><td>
			<select name='expyear'>
				<option value='2014'>2014</option>
				<option value='2015'>2015</option>
				<option value='2016'>2016</option>
				<option value='2017'>2017</option>
				<option value='2018'>2018</option>
				<option value='2019'>2019</option>
				<option value='2020'>2020</option>
				<option value='2021'>2021</option>
				<option value='2022'>2022</option>
				<option value='2023'>2023</option>
				<option value='2024'>2024</option>
				<option value='2025'>2025</option>
			</select>
		</td></tr>
		<tr><td class='label'><strong>".__("Comments","nation")."</strong></td><td><textarea name='comments' class='large-text'></textarea></td></tr>
		</table>
		<input type='hidden' name='action' value='nation_booking_add_reservation' />
		<input type='hidden' name='cal_id' value='{$_REQUEST['cal_id']}' />
		<br><input type='submit' value=".__("Submit","nation")." class='button button-primary'>
		</form></div>";
	}
	exit;	
}
add_action('wp_ajax_nation_show_reservations', 'nation_booking_ajax_show_reservations' );


// Import and export page
function nation_booking_import_export() {
	echo '<div class="wrap">';
    screen_icon();
    echo '<h2>' . __( 'Export/Import Booking System Data','nation') . '</h2>';
    ?>
 
    <form id="export-log-form" method="post" action='admin-post.php'>
        <p><label><?php _e( 'Click to export all booking system data','nation'); ?></label>
		<input type="hidden" name="action" value="nation_booking_export" /></p>
        <?php wp_nonce_field('nation_booking_export_verify') ;?>
        <?php submit_button( __('Download the Booking System Data','nation'), 'button button-secondary' ); ?>
    </form>
	<hr><br>
	<?php // Was an import attempted and are we on the correct admin page?
	if ( isset( $_GET['imported'] ) ) {
		$imported = intval( $_GET['imported'] );
		if ( 1 == $imported ) {
			printf( '<div class="updated"><p>%s</p></div>', __( 'Data was successfully imported', 'nation' ) );
		} else {
			printf( '<div class="error"><p>%s</p></div>', __( ' No data were imported', 'nation' ) );
		}
	}
	?>
	<form method="post" action="admin-post.php" enctype="multipart/form-data">
		<p><label for="import_logs"><?php _e( 'Import an .xml file.','nation' ); ?></label>
		<input type="file" id="import_logs" name="booking_import" /></p>
		<input type="hidden" name="action" value="nation_booking_import" />
		<?php wp_nonce_field( 'nation_booking_import_verify' ); ?>
		<?php submit_button( __( 'Import the Booking System Data','nation' ), 'button button-secondary' ); ?>
	</form><?php
}


function nation_booking_add_style() {
	wp_register_style('nation-admin-style', plugins_url('includes/css/admin-style.css', __FILE__));
	wp_register_style('datepicker-style', plugins_url('includes/css/datepicker-style.css', __FILE__));
	wp_enqueue_style('nation-admin-style');
	wp_enqueue_style('datepicker-style');
}
add_action('admin_enqueue_scripts', 'nation_booking_add_style');


function nation_booking_add_script() {
	global $wpdb; 
	
	wp_register_script('nation-admin-script', plugins_url('includes/js/admin-script.js', __FILE__), array('jquery'), false, true);
	wp_register_script('cookie', plugins_url('includes/js/jquery.cookie.js', __FILE__), array('jquery', 'nation-admin-script'), false, true);
	$dateformat = $wpdb->get_var( "SELECT date_format FROM ".$wpdb->prefix."nation_booking_settings");
	wp_enqueue_script('jquery');
	wp_enqueue_script('cookie');
	wp_enqueue_script('jquery-ui-datepicker');
	
	//Pass some variables to allscript files 
	$passVar = array( 
		'ajaxurl' => admin_url('admin-ajax.php'), 
		'dateformat' => $dateformat,
		'monthShortNames' =>  __("Jan,Feb,Mar,Apr,May,Jun,Jul,Aug,Sep,Oct,Nov,Dec", "nation"),
		'monthLongNames' => __("January,February,March,April,May,June,July,August,September,October,November,December", "nation"),
		'dayShortNames' => __("Sun,Mon,Tue,Wed,Thu,Fri,Sat", "nation"),
		'dayLongNames' => __("Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday", "nation"),
		'dayMicroNames' => __("Su,Mo,Tu,We,Th,Fr,Sa", "nation"),
		'deleteCalText' => __("Are you sure you want to delete this calendar?", "nation"),
		'approveReservationText' => __("Are you sure you want to approve this reservation?", "nation"),
		'rejectReservationText' => __("Are you sure you want to reject this reservation?", "nation"),
		'cancelReservationText' => __("Are you sure you want to cancel this reservation?", "nation"),
		'deleteReservationText' => __("Are you sure you want to delete this reservation?", "nation"),
		'resCheckInText' => __("Check in date always should be less than check out date.", "nation"),
		'resCheckOutText' => __("Check out date always should be larger than check in date.", "nation")
	);
    wp_localize_script( 'nation-admin-script', 'bookingOption', $passVar );
	
	wp_enqueue_script('nation-admin-script');
}
add_action('admin_enqueue_scripts', 'nation_booking_add_script');


function nation_booking_admin_actions() {
	add_menu_page(__('Nation Booking System','nation'), __('Nation Booking System','nation'), 'manage_options', 'nation-booking', 'nation_booking_show_admin');
	add_submenu_page('nation-booking', __('Reservations','nation'), __('Reservations','nation'), 'manage_options', 'nation-booking-reservation-show', 'nation_booking_show_reservations');
	add_submenu_page('nation-booking', __('Availability/Price','nation'), __('Availability/Price','nation'), 'manage_options', 'availability-price-calendar', 'nation_booking_add_edit_price');
	add_submenu_page('nation-booking', __('Settings','nation'), __('Settings','nation'), 'manage_options', 'settings', 'nation_booking_settings');
	add_submenu_page('nation-booking', __('PayPal Settings','nation'), __('PayPal Settings','nation'), 'manage_options', 'paypal-settings', 'nation_booking_paypal_settings');
	add_submenu_page('nation-booking', __('Import/Export','nation'), __('Import/Export','nation'), 'manage_options', 'import-export', 'nation_booking_import_export');
}
add_action('admin_menu', 'nation_booking_admin_actions');