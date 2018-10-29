<?php 
ob_start();


/*
Template Name: Reservation page
*/

if ( session_id() == '' ) {
	session_start();
}

include('includes/paypal/expresscheckout.php');

get_header(); 

//Extracting the values that user defined in OptionTree Plugin 
$headerTelephone = ot_get_option('telephone');
$headerEmail = ot_get_option('email');

$maxRoom = ot_get_option('max_room_rent');
$adultRoom = ot_get_option('max_adult_room');
$childrenRoom = ot_get_option('max_children_room');
$availableRoomHeader = ot_get_option('available_room_header');

$personalInfoHeader = ot_get_option('personal_info_header');
$personalInfoDescription = ot_get_option('personal_info_description');
$paymentInfoHeader = ot_get_option('payment_info_header');
$paymentInfoDescription = ot_get_option('payment_info_description');
$beforeConfirmInfo = ot_get_option('before_confirm_info');

$completedHeader = ot_get_option('reservation_complete_header');
$completedDescription = ot_get_option('reservation_complete_description');
$completedHeaderPayPal = ot_get_option('reservation_complete_header_paypal');
$completedDescriptionPaypal = ot_get_option('reservation_complete_description_paypal');

$taxNote = ot_get_option('tax_note');


//Calculating total booking price
function dateRange($first, $last, $format = 'm/d/Y' ) { 
	$dates = array();
	$current = strtotime($first);
	$last = strtotime($last);

	while( $current <= $last ) { 
		$dates[] = date($format, $current);
		$current = strtotime('+1 day', $current);
	}
	
	return $dates;
}

function full_url($s){
	$ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true:false;
	$sp = strtolower($s['SERVER_PROTOCOL']);
	$protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
	$port = $s['SERVER_PORT'];
	$port = ((!$ssl && $port=='80') || ($ssl && $port=='443')) ? '' : ':'.$port;
	$host = isset($s['HTTP_X_FORWARDED_HOST']) ? $s['HTTP_X_FORWARDED_HOST'] : isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : $s['SERVER_NAME'];
	return $protocol . '://' . $host . $port . $s['REQUEST_URI'];
}


//Booking plugin settings
$bookingSettings = $wpdb->get_row( "SELECT enable_coupon,coupon_name,coupon_discount,coupon_name2,coupon_discount2,coupon_name3,coupon_discount3,paypal_enabled,date_format,currency_symbol,tax,add_tax,hide_tax FROM {$wpdb->prefix}nation_booking_settings" );

$dateFormat = $bookingSettings->date_format;
$currencySymbol = $bookingSettings->currency_symbol;
$tax = $bookingSettings->tax;
$addTax = $bookingSettings->add_tax;
$hideTax = $bookingSettings->hide_tax;
$paypalEnabled = $bookingSettings->paypal_enabled;

$enable_coupon = $bookingSettings->enable_coupon;
$coupon_name = $bookingSettings->coupon_name;
$coupon_discount = $bookingSettings->coupon_discount;
$coupon_name2 = $bookingSettings->coupon_name2;
$coupon_discount2 = $bookingSettings->coupon_discount2;
$coupon_name3 = $bookingSettings->coupon_name3;
$coupon_discount3 = $bookingSettings->coupon_discount3;

?>
		<!-- BEGIN PAGE TITLE -->
		<?php	
		$showTitle = get_post_meta($post->ID, "page_title",true);
		
		if ($showTitle == "Yes") {
			$breadcrumb = get_post_meta($post->ID, "breadcrumb",true);
			$pageIcon = get_post_meta($post->ID, "page_icon",true);
			$pageClass = get_post_meta($post->ID, "page_class",true);
			$pageDescription = get_post_meta($post->ID, "page_description",true); 
			$pageTitleAlign = get_post_meta($post->ID, "page_align",true); 
		?>
		
		<style>
		<?php if (!$pageDescription && $breadcrumb=="Yes") { ?>
		#crumbs {
			margin-top:2px;
		}
		<?php } 
		if ($pageTitleAlign =="Center") { ?>
		#main-title-wrap, #main-title-undertext {
			text-align:center;
		}
		<?php } ?>
		</style>
		
		<div id="top-content-divider">
			<div class="container">
				<div id="main-title-wrap"><?php if ($pageIcon) { ?><span class="<?php echo $pageIcon; ?>"></span><?php } ?> <?php the_title(); ?> <?php if ($breadcrumb == "Yes") { nation_breadcrumbs(); } ?></div>
				<?php if ($pageDescription) { ?>
				<div id="main-title-undertext"><?php echo $pageDescription; ?></div>
				<?php } ?>
			</div>
		</div>
		
		<?php 
		} else {
			echo "<div id='top-divider' class='container'></div>";
		}
		?>
		<!-- END PAGE TITLE -->

		<div class="container reservation-page-main-wrap">
			<?php 
				$step1 = false;
				$step2 = false;
				$step3 = false;
				$step4 = false;
				//Determine on what reservation step we are now	
				if ( isset($_POST["check-in"]) && isset($_POST["check-out"]) && isset($_POST["room-number"]) || isset($_GET["token"]) && isset($_GET["PayerID"]) ) {
					$step2=true;
					if ( isset($_POST["room-title"]) || isset($_GET["token"]) && isset($_GET["PayerID"]) ) {
						$step2=false;
						$step3=true;
						if ( isset($_POST["step3-send"]) || isset($_GET["token"]) && isset($_GET["PayerID"]) ) {
							$step3=false;
							$step4=true;
						}
					}
				} else {
					$step1=true;
				}
			?>
	
			<div id="reservation-breadcrumb-wrap" class="container">
				<div <?php if ( $step1 && !isset( $_GET["reservation"]) ) echo "id='active'"; ?>><?php _e('1. Choose Date','nation'); ?> <span class="icon-angle-right"></span></div>
				<div <?php if ( $step2 ) echo "id='active'"; ?>><?php _e('2. Choose Room','nation'); ?> <span class="icon-angle-right"></span></div>
				<div <?php if ( $step3 ) echo "id='active'"; ?>><?php _e('3. Checkout','nation'); ?> <span class="icon-angle-right"></span></div>
				<div <?php if ( $step4 || isset( $_GET["reservation"]) ) echo "id='active'"; ?>><?php _e('4. Confirmation','nation'); ?></div>
			</div>
		
			<?php if ( $step1 && !isset( $_GET["reservation"] ) ) { ?>
			<!-- BEGIN STEP 1 RESERVATION FORM -->
			<form method="POST" id="reservation-step1-form">		
				<div class="five columns step1 alpha">
					<div id="reservation-info">
						<div id="reservation-info-header"><span class="icon-shopping-cart"></span><?php _e('Your Booking','nation'); ?></div>
						
						<?php
						$checkinDate = ( isset($_POST["check-in"]) ) ? $_POST["check-in"] : __('check-in-date','nation'); 
						$checkoutDate = ( isset($_POST["check-out"]) ) ? $_POST["check-out"] : __('check-out-date','nation'); 
						?>
						<div id="reservation-info-content">
							<div id="reservation-check-in">
								<div><?php _e('Check-in','nation'); ?></div>
								<input type="text" placeholder="<?php echo $checkinDate ?>" id="check-in-date" name="check-in" class="reservation-form-field" readonly="true">
							</div>
							<div id="reservation-check-out">
								<div><?php _e('Check-out','nation'); ?></div>
								<input type="text" placeholder="<?php echo $checkoutDate ?>" id="check-out-date" name="check-out" class="reservation-form-field" readonly="true">
							</div>
							<div class="clear"></div>
							<div id="reservation-room">
								<div><?php _e('Rooms','nation'); ?></div>
								<select id="room-number-selection" name="room-number">
									<?php for ($j=1;$j<=$maxRoom;$j++) { ?>
									<option value="<?php echo $j; ?>"><?php echo $j; ?></option>
									<?php } ?>
								</select>
							</div>
						
							<?php for( $i=1;$i<=$maxRoom;$i++ ) { ?>
							<div id="room-guest<?php echo $i; ?>" <?php if ($i>1) { ?>class="room-guest-wrap" <?php } ?>>
								<div class="room-number-for-guest"><?php _e('Room ','nation'); echo $i; ?></div>
								<div class="reservation-room-adults" name="adults-number">
									<?php if ($i==1) { ?><div><?php _e('Adults','nation'); ?></div><?php } ?>
									<select name="room-adults<?php echo $i; ?>">
										<?php for ($j=1;$j<=$adultRoom;$j++) { ?>
										<option value="<?php echo $j; ?>"><?php echo $j; ?></option>
										<?php } ?>
									</select>
								</div>
								<div class="reservation-room-children" name="children-number">
									<?php if ($i==1) { ?><div><?php _e('Children','nation'); ?></div><?php } ?>
									<select name="room-children<?php echo $i; ?>">
										<?php for ($j=0;$j<=$childrenRoom;$j++) { ?>
										<option value="<?php echo $j; ?>"><?php echo $j; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<?php } ?>
							<div class="clear"></div>
						</div>
					</div>
				</div>
				
				<div class="eleven columns reservation-content page-left-sidebar">
					<div id="reservation-data-selection"></div>
					<input type="hidden" value="" name="Date" id="Date" />
					<button type="submit" id="reservation-step1-button"><?php _e('Check Availability','nation'); ?></button>	
				</div>
				
			</form>
			<!-- END STEP 1 RESERVATION FORM -->
			<?php } ?>
			
			
			<?php if ( $step2 ) { ?>
				<!-- BEGIN STEP 2 RESERVATION FORM -->
				<div class="five columns step2 alpha">
					<div id="reservation-info" class="step2-reservation-info">						
						<div id="reservation-info-header"><span class="icon-shopping-cart"></span><?php _e('Your Booking','nation'); ?></div>
						<div id="reservation-info-content">
							<div id="reservation-check-in">
								<div><?php _e('Check-in','nation'); ?></div>
								<div class="reservation-date-value"><?php echo $_POST["check-in"] ?></div>
							</div>
							<div id="reservation-check-out">
								<div><?php _e('Check-out','nation'); ?></div>
								<div class="reservation-date-value"><?php echo $_POST["check-out"] ?></div>
							</div>
							<div class="clear"></div>
							<div id="reservation-guests">
								<div><?php _e('Guests','nation'); ?></div>
								<div id="room-rent-number" style="display:none"><?php echo $_POST["room-number"]; ?></div>
								<?php 
								if ( !isset($_POST['from-single-room'] ) ) {
									for ($i=1;$i<=$_POST["room-number"];$i++) { 
								
								?>
								<div class="room-guests-wrap" id="room-guests-wrap<?php echo $i; ?>">
									<?php printf(__("Room %d: Adults: <span class='adult'>%s</span>, Children: <span class='children'>%s</span>",'nation'), $i, $_POST["room-adults{$i}"], $_POST["room-children{$i}"] ); ?> 
								</div>
								<?php } } else if ( isset($_POST['from-single-room']) && $_POST['from-single-room'] == "true" ) { ?>
								
								<div class="room-guests-wrap" id="room-guests-wrap1">
									<?php printf(__("Room %d: Adults: <span class='adult'>%s</span>, Children: <span class='children'>%s</span>",'nation'), 1, $_POST["room-adults"], $_POST["room-children"] ); ?> 
								</div>
								
								<?php } ?>
							</div>
							<div id="edit-reservation"><?php _e('Edit Reservation','nation'); ?></div>
						</div>
					</div>
				
					<form id="resend-step2" method="POST">
						<div id="reservation-info">
							<div id="reservation-info-header"><span class="icon-shopping-cart"></span><?php _e('Your Booking','nation'); ?></div>
						
							<div id="reservation-info-content">
								<div id="reservation-check-in">
									<div><?php _e('Check-in','nation'); ?></div>
									<input type="text" placeholder="<?php _e("check-in date","nation"); ?>" id="check-in-date" name="check-in" class="reservation-form-field" readonly="true">
								</div>
								<div id="reservation-check-out">
									<div><?php _e('Check-out','nation'); ?></div>
									<input type="text" placeholder="<?php _e("check-out date","nation"); ?>" id="check-out-date" name="check-out" class="reservation-form-field" readonly="true">
								</div>
								<div class="clear"></div>
						
								<div id="reservation-room">
									<div><?php _e('Rooms','nation'); ?></div>
									<select id="room-number-selection" name="room-number">
										<?php for ($j=1;$j<=$maxRoom;$j++) { ?>
										<option value="<?php echo $j; ?>"><?php echo $j; ?></option>
										<?php } ?>
									</select>
								</div>
						
								<?php for($i=1;$i<=$maxRoom;$i++) { ?>
								<div id="room-guest<?php echo $i; ?>" <?php if ($i>1) { ?>class="room-guest-wrap" <?php } ?>>
									<div class="room-number-for-guest"><?php _e('Room ','nation'); echo $i; ?></div>
									<div class="reservation-room-adults" name="adults-number">
										<?php if ($i==1) { ?><div><?php _e('Adults','nation'); ?></div><?php } ?>
										<select name="room-adults<?php echo $i; ?>">
											<?php for ($j=1;$j<=$adultRoom;$j++) { ?>
											<option value="<?php echo $j; ?>"><?php echo $j; ?></option>
											<?php } ?>
										</select>
									</div>
									<div class="reservation-room-children" name="children-number">
										<?php if ($i==1) { ?><div><?php _e('Children','nation'); ?></div><?php } ?>
										<select name="room-children<?php echo $i; ?>">
											<?php for ($j=0;$j<=$childrenRoom;$j++) { ?>
											<option value="<?php echo $j; ?>"><?php echo $j; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<?php } ?>
								<button id="resend-submit" type="submit"><?php _e('Edit Reservation','nation'); ?></button>
								<div id="cancel-resend-button"><?php _e('Cancel','nation'); ?></div>
								<div class="clear"></div>
							</div>
						</div>
					</form>
				</div>

				<div class="eleven columns reservation-content page-left-sidebar step2">
					<?php if ( !isset($_POST['selected-room']) ) { 
						echo "<h4>".$availableRoomHeader."</h4>"; 
					} else { 
						echo "<h4>".__("The room that you selected:","nation")."</h4>"; 
					} 
					
					global $wpdb;
							
					//Get and convert date that user selected to booking pro system format
					if ( $dateFormat == "european" ) {
						$rangeFormat = "d-m-Y";
					} else if ( $dateFormat == "american" ) {
						$rangeFormat = "m/d/Y";
					}
					
					$dateRange = dateRange( $_POST["check-in"], $_POST["check-out"], $rangeFormat );	
					array_pop($dateRange);
					$roomNumber = 0;
					
					$the_query = new WP_Query( array( 'post_type' => 'rooms', 'showposts' => -1 ) );
					
					echo "<div class='reservation-room-wrap'>";
					
					if ( $the_query->have_posts() ) {
						while ( $the_query->have_posts() ) {
							$the_query->the_post();

							$roomAvailable = false;
							$calID = get_post_meta(get_the_ID(),'calendar',true);
														
							if ( isset($_POST['selected-room']) && $_POST['selected-room'] != $calID ) {
								continue;
							}
							
							$price = $wpdb->get_row( $wpdb->prepare( 
								"SELECT min_price FROM {$wpdb->prefix}nation_booking_calendars WHERE id='%d'",
								$calID
							) );
								
							$minPrice = (isset($price->min_price)) ? $price->min_price : 0;
							
							
							for ($ra=0;$ra<count($dateRange);$ra++) {
								$dayCurrent = date("d",strtotime($dateRange[$ra]));							
								$monthCurrent = date("m",strtotime($dateRange[$ra]));
								$yearCurrent = date("Y",strtotime($dateRange[$ra]));
								
								
								$roomAvailty[$ra] = $wpdb->get_row( $wpdb->prepare( 
									"SELECT * FROM {$wpdb->prefix}nation_booking_availability WHERE calendar_id='%d' 
									AND day = $dayCurrent AND month = $monthCurrent AND year = $yearCurrent AND availability >= %d ",
									$calID,$_POST["room-number"]
								) );
								
								if (!$roomAvailty[$ra]) {
									break;
								}					
							}
							
							$resPeople = 0;
							
							if ( !isset($_POST['from-single-room'] ) ) {
								for ($j=1;$j<=$_POST["room-number"];$j++) {
									$curPeople = $_POST["room-adults{$j}"] + $_POST["room-children{$j}"];
									if ( $curPeople > $resPeople ) {
										$resPeople = $curPeople;
									}
								}
							} else if ( isset($_POST['from-single-room']) && $_POST['from-single-room'] == "true" ) {
								$resPeople = $_POST["room-adults"] + $_POST["room-children"];
							}
							
							$roomPeople = get_post_meta(get_the_ID(),'max_person',true);
							
							if ( $ra == count( $dateRange ) && $roomPeople >= $resPeople ) {
								$roomAvailable = true;
							} 
							
							
							// Display rooms that available on selected date
							if ($roomAvailable) {
								$roomNumber++;
						?>
						
							<form class="room-reservation-wrap" method="post">
								<a target="_blank" href="<?php the_permalink(); ?>" class='room-wrap-link'><?php if ( has_post_thumbnail() ) { the_post_thumbnail('room-normal',array("class"=>"reservation-list-image")); } ?></a>
								<a target="_blank" href="<?php the_permalink(); ?>" class='room-wrap-link'><div class="room-reservation-title"><?php the_title() ?></div></a>
								<input name="room-title" type="hidden" value="<?php the_title() ?>">
								<?php if ( get_post_meta(get_the_ID(),'max_person',true) ) { ?>
								<div id="room-person">
									<?php 
									_e('Max person:','nation'); 
									$person = get_post_meta(get_the_ID(),'max_person',true); 
									echo " "; for ($i=1;$i <= $person;$i++) { 
										echo "<span class='icon-male'></span>"; 
									} 
									?>
								</div>
								<?php } ?>
								<input name="room-id" type="hidden" value="<?php echo get_post_meta(get_the_ID(),'calendar',true); ?>">
								<div class="room-reservation-description"><?php nation_excerpt(25) ?></div>
								<button type="submit" class="room-reservation-select"><?php _e('Select Room','nation'); ?></button>
								<div class="room-reservation-price">
									<?php if ($minPrice != 0) { ?><div>
									<?php printf(__('<span>from</span> %s%d<span>/night</span>','nation'), $currencySymbol, $minPrice); ?>
									</div><?php } ?>
									<div class="room-reservation-pricebreakdown"><span class="icon-info-sign"></span><?php _e('view price breakdown','nation') ?></div>
									
									<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">							
										<div class="modal-body">
											<table>
												<tr>
													<th><?php _e('Date','nation'); ?></th>
													<th><?php _e('Price','nation'); ?></th>
												</tr>
												<?php 
												$total = 0;
												$breakdown = "";
												
												for ($i=0;$i<count($roomAvailty);$i++) { 
													$fullDate = $roomAvailty[$i]->day."-".$roomAvailty[$i]->month."-".$roomAvailty[$i]->year;
													echo "<tr class='breakdown-content'>";
													$multiple = ($_POST["room-number"] > 1) ? " (x".$_POST["room-number"].")" : "";
													echo "	<td>".date_i18n("l, F d, Y", strtotime($fullDate)).$multiple."</td>";
													echo "	<td>".$roomAvailty[$i]->price*$_POST["room-number"].$currencySymbol."</td>";
													echo "</tr>";
													$total += $roomAvailty[$i]->price;
													$breakdown[$i] = $roomAvailty[$i]->id;
												} 
												
												$incTax = 0;
												
												if ( isset($addTax) && $addTax == 1 && isset($hideTax) && $hideTax == 0 ) {
												?>
												<tr>
													<td><?php printf ( __('Tax (%d%%):','nation'), $tax ) ?></td>
													<td><?php $incTax = $total * $_POST["room-number"] * ($tax/100);
													echo "+".$incTax.$currencySymbol; ?></td>
												</tr>
												<?php } ?>
												<tr>
													<td id="modal-total"><?php _e('Total:','nation'); ?> </td>
													<td id="modal-price-total"><?php echo $total * $_POST["room-number"] + $incTax.$currencySymbol; ?></td>
												</tr>
											</table>						
										</div>
										<div class="modal-footer">
											<button class="btn" data-dismiss="modal" aria-hidden="true"><?php _e('Close','nation') ?> <span class="icon-remove"></span></button>
										</div>
									</div>
								</div>
								<?php
								
								$dayID = array();
								
								for ( $i=0; $i<count($roomAvailty); $i++ ) {
									$dayID[$i] = $roomAvailty[$i]->id;
								}
								
								$dayID = implode( ",", $dayID );
								
								?>
								<input name="day-ids" type="hidden" value="<?php echo $dayID; ?>">
								<input name="total" type="hidden" value="<?php echo $total; ?>">
								<div class="clear"></div>
							</form>
							
						<?php
							} 
						}
					} else {
					?>				
					<p><?php _e("There's no rooms to show!",'nation'); ?></p>
					<?php
					} 
					
					echo "</div>";
					
					if ($roomNumber == 0 ) { 
						echo "<div>".__("We're sorry but there's no rooms available on the selected date!", "nation")."</div>";
					}
					/* Restore original Post Data */
					wp_reset_postdata();
					?>
					
				</div>
			
			<!-- END STEP 2 RESERVATION FORM -->
			<?php } 

			if ( $step3 ) { 
				$incTax = 0;
				if ( isset($addTax) && $addTax == 1 && isset($hideTax) && $hideTax == 0 ) {
					$incTax = round($_POST["total"]*$_POST["room-number"]*($tax/100),2);  
				}
			
			?>
			<!-- BEGIN STEP 3 RESERVATION FORM -->
			<div class="five columns step3 alpha">
				<div id="reservation-info" class="step3-reservation-info">						
					<div id="reservation-info-header"><span class="icon-shopping-cart"></span><?php _e('Your Booking','nation'); ?></div>
					<div id="reservation-info-content">
						<div id="reservation-check-in">
							<div><?php _e('Check-in','nation'); ?></div>
							<div class="reservation-date-value"><?php echo $_POST["check-in"] ?></div>
						</div>
						<div id="reservation-check-out">
							<div><?php _e('Check-out','nation'); ?></div>
							<div class="reservation-date-value"><?php echo $_POST["check-out"] ?></div>
						</div>
						<div class="clear"></div>
						<div id="reservation-room-type">
							<div><?php _e('Selected Room','nation'); ?></div>
							<div class="reservation-room-value"><?php echo $_POST["room-title"] ?></div>
						</div>
						<div id="reservation-guests">
							<div><?php _e('Guests','nation'); ?></div>
							<div id="room-rent-number" style="display:none"><?php echo $_POST["room-number"]; ?></div>
							<?php for ($i=1;$i<=$_POST["room-number"];$i++) { ?>
							<div class="room-guests-wrap" id="room-guests-wrap<?php echo $i; ?>">
								<?php echo stripcslashes($_POST["guests-info{$i}"]); ?>
							</div>
							<?php } ?>
						</div>
						<?php 
							
							$dayIDs = explode( ",", $_POST["day-ids"] );
							
							$dayResult = "";
							
							for( $i=0; $i<count($dayIDs); $i++ ) {
								if ( $i==0 ) { 
									$dayResult = "id="; 
								} else {
									$dayResult .= " OR id=";
								} 
								$dayResult .= $dayIDs[$i];
							}
							
							$roomAvailty = $wpdb->get_results( 
								"SELECT * FROM {$wpdb->prefix}nation_booking_availability WHERE ".$dayResult
							);

						?>				
						<div id="total-price-wrap">
							<div id="total-price"><?php _e('Total price:','nation'); ?> 
								<span id="price"><?php echo round($_POST["total"]*$_POST["room-number"]+$incTax,2).$currencySymbol; ?></span>
							</div>
							<div id="tax-notification">
							<?php 
							if ( isset( $hideTax ) && $hideTax == 0 ) {
								if ( isset( $addTax ) && $addTax == 1 ) {
									printf( __("Tax (%d%%) included","nation"), $tax );
								} else if ( isset( $addTax ) && $addTax == 0 ) {
									printf( __("Tax (%d%%) not included","nation"), $tax );
								}
							}
							?>
							</div>
							<div id="price-breakdown"><span class="icon-info-sign"></span><?php _e('View Price Breakdown','nation') ?></div>
							<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">							
								<div class="modal-body">
									<table>
										<tr>
											<th><?php _e('Date','nation'); ?></th>
											<th><?php _e('Price','nation'); ?></th>
										</tr>
										<?php 										
										for ($i=0;$i<count($roomAvailty);$i++) { 
										
											if ( $dateFormat == "european" ) {
												$date = $roomAvailty[$i]->day."-".$roomAvailty[$i]->month."-".$roomAvailty[$i]->year;
											} else if ( $dateFormat == "american" ) {
												$date = $roomAvailty[$i]->month."/".$roomAvailty[$i]->day."/".$roomAvailty[$i]->year;
											}
										
											echo "<tr class='breakdown-content'>";
											$multiple = ($_POST["room-number"] > 1) ? " (x".$_POST["room-number"].")" : "";
											echo "	<td>".date_i18n("l, F d, Y", strtotime($date)).$multiple."</td>";
											echo "	<td>".$roomAvailty[$i]->price*$_POST["room-number"].$currencySymbol."</td>";
											echo "</tr>";
										} 
										if ( isset($addTax) && $addTax == 1 && isset($hideTax) && $hideTax == 0 ) {
										?>
										<tr>
											<td><?php printf (__('Tax (%d%%):','nation'),$tax) ?></td>
											<td><?php echo "+".$incTax.$currencySymbol; ?></td>
										</tr>
										
										<?php } ?>
										<tr>
											<td id="modal-total"><?php _e('Total:','nation'); ?> </td>
											<td id="modal-price-total"><?php echo round($_POST["total"]*$_POST["room-number"]+$incTax,2).$currencySymbol; ?></td>
										</tr>
									</table>
								</div>
								<div class="modal-footer">
									<button class="btn" data-dismiss="modal" aria-hidden="true"><?php _e('Close','nation') ?> <span class="icon-remove"></span></button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
				
			<div class="eleven columns reservation-content page-left-sidebar step3">
				<form id="step3-form" method="post">
					<div id="personal-reservation-form-wrap">
						<h5><?php echo $personalInfoHeader;  ?></h5>
						<div id="required-desc"><?php echo $personalInfoDescription; ?></div>
						
						<div id="resform-firstname" class="form-field-wrap">
							<div class="resform-header"><?php _e("First Name","nation") ?> <span class="main-reservation-form-asterisk">*</span></div>
							<input type="text" name="resform-firstname" class="form-field-wrap required-field">
						</div>
						
						<div id="resform-lastname" class="form-field-wrap">
							<div class="resform-header"><?php _e("Last Name","nation") ?> <span class="main-reservation-form-asterisk">*</span></div>
							<input type="text" name="resform-lastname" class="form-field-wrap required-field">
						</div>
						
						<div id="resform-email" class="form-field-wrap">
							<div class="resform-header"><?php _e("Email","nation") ?> <span class="main-reservation-form-asterisk">*</span></div>
							<input type="text" name="resform-email" class="form-field-wrap required-field email-field">
						</div>
						
						<div id="resform-retypeyouremail" class="form-field-wrap">
							<div class="resform-header"><?php _e("Retype your email","nation") ?> <span class="main-reservation-form-asterisk">*</span></div>
							<input type="text" name="resform-retypeyouremail" class="form-field-wrap required-field email-field">
						</div>
						
						<div id="resform-comments" class="form-field-wrap">
							<div class="resform-header"><?php _e("Comments","nation") ?></div>
							<textarea type="text" name="resform-comments"></textarea>
						</div>
						
						<?php if ($enable_coupon) { ?>
						<div id="resform-firstname" class="form-field-wrap">
							<div class="resform-header"><?php _e("Enter Coupon Name","nation") ?></div>
							<input type="text" name="resform-coupon" class="form-field-wrap">
						</div>
						<?php } ?>
						
						<input name="room-id" type="hidden" value="<?php echo $_POST["room-id"]; ?>">
						<input name="price" type="hidden" value="<?php echo round($_POST["total"]*$_POST["room-number"],2) ?>">
						
						<input name="step3-send" type="hidden" value="true">
					</div>
					
					<?php if ( isset($paypalEnabled) && $paypalEnabled == 0 ) { ?>
					<style>
					#payment-reservation-form {
						display:block;
					}
					</style>
					<input name="payments_method" type="hidden" value="creditcard">
					<?php } else if ( isset($paypalEnabled) && $paypalEnabled == 1 ) { ?>
					
					<div id="payment-method-selections">
						<div class="resform-header"><?php _e("Select payment method","nation") ?> <span class="main-reservation-form-asterisk">*</span></div>
						<div id="radio-buttons-wrap">
							<div><input type="radio" name="payments_method" value="paypal"><img src="https://www.paypal.com/en_US/i/logo/PayPal_mark_37x23.gif" style="margin-left:6px;margin-right:6px;"><span style="font-size:12px; font-family: Arial, Verdana;"><?php _e("The safer, easier way to pay.","nation"); ?></span></div>
							<div><input type="radio" name="payments_method" value="creditcard"><span id='credit-card-text'><?php _e("Pay with","nation"); ?> &nbsp; <img class="creditcard-images" src="<?php echo get_template_directory_uri()."/images/american-express.png"; ?>"> <img class="creditcard-images" src="<?php echo get_template_directory_uri()."/images/mastercard.png"; ?>"> <img class="creditcard-images" src="<?php echo get_template_directory_uri()."/images/visa.png"; ?>"> &nbsp; <?php _e("on arrival","nation"); ?></span></div>
						</div>
					</div>
					<?php } ?>
					<div id="payment-reservation-form">
						<h5><?php echo $paymentInfoHeader; ?></h5>
						<div id="payment-desc"><?php echo $paymentInfoDescription; ?></div>
						
						<div id="resform-cardtype" class="form-field-wrap">
							<div class="resform-header"><?php _e("Card type","nation") ?> <span class="main-reservation-form-asterisk">*</span></div>
							<select name="resform-cardtype">
								<option value="americanexpress">American Express</option>
								<option value="mastercard">Master Card</option>
								<option value="visa">Visa</option>
							</select>
						</div>
						
						<div id="resform-cardholdername" class="form-field-wrap">
							<div class="resform-header"><?php _e("Cardholder name","nation") ?> <span class="main-reservation-form-asterisk">*</span></div>
							<input type="text" name="resform-cardholdername" class="form-field-wrap required-field">
						</div>
						
						<div id="resform-cardnumber" class="form-field-wrap">
							<div class="resform-header"><?php _e("Card number","nation") ?> <span class="main-reservation-form-asterisk">*</span></div>
							<input type="text" name="resform-cardnumber" class="form-field-wrap required-field">
						</div>
						
						<div id="resform-expirationmonth" class="form-field-wrap">
							<div class="resform-header"><?php _e("Expiration Month","nation") ?> <span class="main-reservation-form-asterisk">*</span></div>
							<select name="resform-expirationmonth">
								<option value="01">01</option>
								<option value="02">02</option>
								<option value="03">03</option>
								<option value="04">04</option>
								<option value="05">05</option>
								<option value="06">06</option>
								<option value="07">07</option>
								<option value="08">08</option>
								<option value="09">09</option>
								<option value="10">10</option>
								<option value="11">11</option>
								<option value="12">12</option>
							</select>
						</div>
						
						<div id="resform-expirationyear" class="form-field-wrap">
							<div class="resform-header"><?php _e("Expiration Year","nation") ?> <span class="main-reservation-form-asterisk">*</span></div>
							<select name="resform-expirationyear">
								<option value="2014">2014</option>
								<option value="2015">2015</option>
								<option value="2016">2016</option>
								<option value="2017">2017</option>
								<option value="2018">2018</option>
								<option value="2019">2019</option>
								<option value="2020">2020</option>
								<option value="2021">2021</option>
							</select>
						</div>
						
						<div id="resform-confirmation" class="form-field-wrap">
							<?php echo $beforeConfirmInfo; ?>
							<input type="submit" value="<?php _e('Confirm Reservation','nation'); ?>" id="reservation-step3-button">
						</div>
					</div>
					
					<div id="paypal-payment-reservation-form">
						<br><button type="submit"><img src="https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif" align="left" style="margin-right:7px;"></button>
					</div>
					
					<?php if ( isset( $incTax ) ) { ?><input type="hidden" name="tax-amount" value="<?php echo $incTax;?>"><?php } ?>
					
					<?php for ($i=1;$i<=$_POST["room-number"];$i++) { ?>
					<input type="hidden" name="adult-room<?php echo $i; ?>" value="<?php echo $_POST["adult-room".$i] ?>">
					<input type="hidden" name="child-room<?php echo $i; ?>" value="<?php echo $_POST["child-room".$i] ?>">
					<?php } ?>
				
				</form>
			</div>
			
			<!-- END STEP 3 RESERVATION FORM -->
			<?php }

			$returnLink = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			
			if ( $step4 && isset( $_POST["payments_method"] ) && $_POST["payments_method"] == "paypal" ) { 
				$taxC = 0;
				$_SESSION['coupon-apply'] = false;
				
				if ( isset($_POST["resform-coupon"]) && $_POST["resform-coupon"] == $coupon_name ) {
					$coupon = $coupon_discount/100;						
					$discount = $_POST["price"]*(float)$coupon;
					$total = $_POST["price"]-$discount;
					
					if ( isset($addTax) && $addTax != 0) { 
						$taxC = $total*($tax/100); 
					}
					
					$_SESSION['coupon-apply'] = true;
					$_SESSION['coupon-number'] = 1;
				} else if ( isset($_POST["resform-coupon"]) && $_POST["resform-coupon"] == $coupon_name2 ) {
					$coupon = $coupon_discount2/100;						
					$discount = $_POST["price"]*(float)$coupon;
					$total = $_POST["price"]-$discount;
					
					if ( isset($addTax) && $addTax != 0) { 
						$taxC = $total*($tax/100); 
					}
					
					$_SESSION['coupon-apply'] = true;
					$_SESSION['coupon-number'] = 2;
				} else if ( isset($_POST["resform-coupon"]) && $_POST["resform-coupon"] == $coupon_name3 ) {
					$coupon = $coupon_discount3/100;						
					$discount = $_POST["price"]*(float)$coupon;
					$total = $_POST["price"]-$discount;
					
					if ( isset($addTax) && $addTax != 0) { 
						$taxC = $total*($tax/100); 
					}
					
					$_SESSION['coupon-apply'] = true;
					$_SESSION['coupon-number'] = 3;
				} else {
					$total = $_POST["price"];

					if ( isset($addTax) && $addTax != 0) { 
						$taxC = $total*($tax/100); 
					}
					
					$taxC = $_POST["tax-amount"]; 
				}
				
				$total = $total + $taxC;
				
				startExpressCheckout( round($total,2), $returnLink, $taxC );
			}		

			//Paypal redirects back to this page using ReturnURL, We should receive TOKEN and Payer ID
			if ( $step4 && isset($_GET["token"]) && isset($_GET["PayerID"]) && !isset($_GET["reservation"]) ) {
				$token = $_GET["token"];
				$payer_id = $_GET["PayerID"];
				
				$roomID = $_SESSION['room-id'];
				$checkIn = $_SESSION['check_in'];
				$checkOut = $_SESSION['check_out'];
				$noItems = $_SESSION['no_items'];
				$resformEmail = $_SESSION['email'];
				$noAdult = $_SESSION['no_adult'];
				$noChildren = $_SESSION['no_children'];
				$sPrice = $_SESSION['price'];
				$sName = $_SESSION['name'];
				$sSurname = $_SESSION['surname'];
				$cardType = $_SESSION['card_type'];
				$cardholderName = $_SESSION['cardholder_name'];
				$cardNumber = $_SESSION['card_number'];
				$expYear = $_SESSION['expiration_year'];
				$expMonth = $_SESSION['expiration_month'];
				$comments = $_SESSION['comments'];
				$currencyCode = $_SESSION['currency_code'];
				$email = $_SESSION['email'];
				$rTax = $_SESSION['tax'];		
				
				//We need to execute the "DoExpressCheckoutPayment" at this point to Receive payment from user.
				$httpParsedResponseAr = DoExpressCheckout( $_GET["token"], $_GET["PayerID"], $roomID, $noItems, $sPrice, $currencyCode, $returnLink );
				
				//Check if everything went ok..
				if ("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])){
					global $wpdb; 
					
					$wpdb->insert($wpdb->prefix.'nation_booking_reservation', array(
						'calendar_id' => $roomID, 
						'check_in' => $checkIn,
						'check_out' => $checkOut,
						'no_items' => $noItems, 
						'email' => $email, 
						'no_adult' => $noAdult, 
						'no_children' => $noChildren, 
						'status' => 'pending', 
						'price' => $sPrice, 
						'name' => $sName, 
						'surname' => $sSurname, 
						'paypal_payment' => 1, 
						'paypal_payer_id' => $_GET["PayerID"], 
						'paypal_transaction_id' => $httpParsedResponseAr["PAYMENTINFO_0_TRANSACTIONID"],
						'card_type' => 0, 
						'cardholder_name' => 0, 
						'card_number' => 0, 
						'expiration_year' => 0, 
						'expiration_month' => 0, 
						'comments' => $comments, 
						'date_created' => current_time('mysql', 1) 
					)); 
		
					$lastID = $wpdb->insert_id;
					
					sendEmail('without_approval', $roomID, $lastID, date($insFormat, strtotime($checkIn)),
						date($insFormat, strtotime($checkOut)), $sPrice, $noItems, $noAdult, $noChildren,
						$email, $sName, $sSurname, $comments, 0, 0, 0, 0, 0
					);
					
					$absolute_url = full_url($_SERVER);
						
					if ( get_option('permalink_structure') ) {
						header("Location: ". $absolute_url. "&reservation=complete&inid=".$lastID);
					} else {
						header("Location: ". $absolute_url. "&reservation=complete&inid=".$lastID);
					}
					
				} else {
					echo '<div style="color:red"><b>Error : </b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
					echo '<pre>';
					print_r($httpParsedResponseAr);
					echo '</pre>';
				}
			}
			
			
			if ( $step4 && isset( $_POST["payments_method"] ) && $_POST["payments_method"] == "creditcard" ) {
				global $wpdb; 			
					
				if ( $dateFormat == "european" ) {
					$insFormat = "d-m-Y";
				} else if ( $dateFormat == "american" ) {
					$insFormat = "m/d/Y";
				}
					
				//Add new reservation in reservation database
				for ($i=1;$i<=$_POST["room-number"];$i++) {
					$aroom[$i-1] = $_POST["adult-room".$i];
					$croom[$i-1] = $_POST["child-room".$i];
				}
				
				$taxC = 0;
				$_SESSION['coupon-apply'] = false;
				
				if ( isset($_POST["resform-coupon"]) && $_POST["resform-coupon"] == $coupon_name ) {
					$coupon = $coupon_discount/100;						
					$discount = $_POST["price"]*(float)$coupon;
					$total = $_POST["price"]-$discount;
					
					if ( isset($addTax) && $addTax != 0) { 
						$taxC = $total*($tax/100); 
					}
					
					$_SESSION['coupon-apply'] = true;
					$_SESSION['coupon-number'] = 1;
				} else if ( isset($_POST["resform-coupon"]) && $_POST["resform-coupon"] == $coupon_name2 ) {
					$coupon = $coupon_discount2/100;						
					$discount = $_POST["price"]*(float)$coupon;
					$total = $_POST["price"]-$discount;
					
					if ( isset($addTax) && $addTax != 0) { 
						$taxC = $total*($tax/100); 
					}
					
					$_SESSION['coupon-apply'] = true;
					$_SESSION['coupon-number'] = 2;
				} else if ( isset($_POST["resform-coupon"]) && $_POST["resform-coupon"] == $coupon_name3 ) {
					$coupon = $coupon_discount3/100;						
					$discount = $_POST["price"]*(float)$coupon;
					$total = $_POST["price"]-$discount;
					
					if ( isset($addTax) && $addTax != 0) { 
						$taxC = $total*($tax/100); 
					}
					
					$_SESSION['coupon-apply'] = true;
					$_SESSION['coupon-number'] = 3;
				} else {
					$total = $_POST["price"];

					if ( isset($addTax) && $addTax != 0) { 
						$taxC = $total*($tax/100); 
					}
					
					$taxC = $_POST["tax-amount"]; 
				}
				
				$total = $total + $taxC;
				
				$wpdb->insert($wpdb->prefix.'nation_booking_reservation', array(
					'calendar_id' => $_POST["room-id"], 
					'check_in' => date($insFormat, strtotime($_POST["check-in"])),
					'check_out' => date($insFormat, strtotime($_POST["check-out"])),
					'no_items' => $_POST["room-number"], 
					'email' => $_POST["resform-email"], 
					'no_adult' => json_encode($aroom), 
					'no_children' => json_encode($croom), 
					'status' => 'pending', 
					'price' => round($total,2), 
					'name' => $_POST["resform-firstname"], 
					'surname' => $_POST["resform-lastname"], 
					'paypal_payment' => 0, 
					'paypal_payer_id' => 0, 
					'paypal_transaction_id' => 0,
					'card_type' => $_POST["resform-cardtype"], 
					'cardholder_name' => $_POST["resform-cardholdername"], 
					'card_number' => $_POST["resform-cardnumber"], 
					'expiration_year' => $_POST["resform-expirationyear"], 
					'expiration_month' => $_POST["resform-expirationmonth"], 
					'comments' => $_POST["resform-comments"], 
					'date_created' => current_time('mysql', 1) 
				)); 
		
				$lastID = $wpdb->insert_id;
					
				sendEmail('without_approval', $_POST["room-id"], $lastID, date($insFormat, strtotime($_POST["check-in"])),
                    date($insFormat, strtotime($_POST["check-out"])), $total, $_POST["room-number"], json_encode($aroom), json_encode($croom),
                    $_POST["resform-email"], $_POST["resform-firstname"], $_POST["resform-lastname"], $_POST["resform-comments"],
                    $_POST["resform-cardtype"], $_POST["resform-cardholdername"], $_POST["resform-cardnumber"], 
					$_POST["resform-expirationmonth"], $_POST["resform-expirationyear"]
				);
					
				$absolute_url = full_url($_SERVER);
										
				if ( get_option('permalink_structure') ) {
					header("Location: ". $absolute_url. "?reservation=complete&inid=".$lastID);
				} else {
					header("Location: ". $absolute_url. "&reservation=complete&inid=".$lastID);
				} 
			} ?>	
				
			<?php if ( $step1 && isset($_GET["reservation"]) || isset($_GET["reservation"]) && isset($_GET["token"]) && isset($_GET["inid"]) ) { ?>
			<!-- BEGIN STEP 4 RESERVATION FORM -->
			<?php 
				$field = $wpdb->get_row( $wpdb->prepare(
					"SELECT * FROM ".$wpdb->prefix.'nation_booking_reservation'." WHERE id=%d ",
					$_GET["inid"]
					)
				);
				
				$roomTitle = $wpdb->get_row( $wpdb->prepare(
					"SELECT * FROM ".$wpdb->prefix.'nation_booking_calendars'." WHERE id=%d ",
					$field->calendar_id
					)
				);
			?>
			
			<div class="five columns step4 alpha">
				<div id="reservation-info" class="step4-reservation-info">						
					<div id="reservation-info-header"><span class="icon-shopping-cart"></span><?php _e('Your Booking','nation'); ?></div>
					<div id="reservation-info-content">
						<div id="reservation-check-in">
							<div><?php _e('Check-in','nation'); ?></div>
							<div class="reservation-date-value"><?php echo $field->check_in ?></div>
						</div>
						<div id="reservation-check-out">
							<div><?php _e('Check-out','nation'); ?></div>
							<div class="reservation-date-value"><?php echo $field->check_out ?></div>
						</div>
						<div class="clear"></div>
						<div id="reservation-room-type">
							<div><?php _e('Selected Room','nation'); ?></div>
							<div class="reservation-room-value"><?php echo $roomTitle->cal_name ?></div>
						</div>
						<div id="reservation-guests">
							<div><?php _e('Guests','nation'); ?></div>
							<?php 
							$roomAdult = json_decode($field->no_adult,true);
							$roomChild = json_decode($field->no_children,true);
							
							for ($i=0;$i<$field->no_items;$i++) {
										
							?>	
							<div class="room-guests-wrap" id="room-guests-wrap<?php echo $i; ?>">
								<?php printf(__("Room %d: Adults: %d, Children: %d",'nation'), ($i+1), $roomAdult[$i], $roomChild[$i]); ?>
							</div>
							<?php } ?>
						</div>	
						<?php 
							if ( $dateFormat == "european" ) {
								$rangeFormat = "d-m-Y";
							} else if ( $dateFormat == "american" ) {
								$rangeFormat = "m/d/Y";
							}
						
						
							$dateRange = dateRange( $field->check_in, $field->check_out, $rangeFormat );
							array_pop($dateRange);
							
							for ($i=0;$i<count($dateRange);$i++) {
								$dayCurrent = date("d",strtotime($dateRange[$i]));								
								$monthCurrent = date("m",strtotime($dateRange[$i]));
								$yearCurrent = date("Y",strtotime($dateRange[$i]));
																
								$roomAvailty[$i] = $wpdb->get_row( $wpdb->prepare( 
									"SELECT * FROM {$wpdb->prefix}nation_booking_availability WHERE calendar_id='%d' 
									AND day = $dayCurrent AND month = $monthCurrent AND year = $yearCurrent",
									$field->calendar_id
								) );
																
							}
							
																					
						?>
						<div id="total-price-wrap">
							<div id="total-price"><?php _e('Total price:','nation'); ?> <span id="price"><?php echo $field->price.$currencySymbol; ?></span></div>
							<div id="tax-notification">
							<?php 
							if ( isset( $hideTax ) && $hideTax == 0 ) {
								if ( isset( $addTax ) && $addTax == 1 ) {
									printf( __("Tax (%d%%) included","nation"), $tax );
								} else if ( isset( $addTax ) && $addTax == 0 ) {
									printf( __("Tax (%d%%) not included","nation"), $tax );
								}
							}
							?>
							</div>
							<div id="price-breakdown"><span class="icon-info-sign"></span><?php _e('View Price Breakdown','nation') ?></div>
							<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">							
								<div class="modal-body">
									<table>
										<tr>
											<th><?php _e('Date','nation'); ?></th>
											<th><?php _e('Price','nation'); ?></th>
										</tr>
										<?php 
										$totalRoomPrice = 0;
										for ($i=0;$i<count($roomAvailty);$i++) { 
											
											if ( $dateFormat == "european" ) {
												$date = $roomAvailty[$i]->day."-".$roomAvailty[$i]->month."-".$roomAvailty[$i]->year;
											} else if ( $dateFormat == "american" ) {
												$date = $roomAvailty[$i]->month."/".$roomAvailty[$i]->day."/".$roomAvailty[$i]->year;
											}
										
											echo "<tr class='breakdown-content'>";
											$multiple = ($field->no_items > 1) ? " (x".$field->no_items.")" : "";
											echo "	<td>".date_i18n("l, F d, Y", strtotime($date)).$multiple."</td>";
											echo "	<td>".$roomAvailty[$i]->price*$field->no_items.$currencySymbol."</td>";
											echo "</tr>";
											
											$totalRoomPrice += $roomAvailty[$i]->price*$field->no_items;
										} 
										$incTax = 0;
										$total = 0;
										if ( isset( $_SESSION['coupon-apply'] ) && $_SESSION['coupon-apply'] == true ) {
										
											if ( $_SESSION['coupon-number'] == 1 ) {
												$coupon_discount = $coupon_discount;
											} else if ( $_SESSION['coupon-number'] == 2 ) {
												$coupon_discount = $coupon_discount2;
											} else if ( $_SESSION['coupon-number'] == 3 ) {
												$coupon_discount = $coupon_discount3;
											}
											
											$coupon = $coupon_discount/100;
											$total = $totalRoomPrice*(float)$coupon;
										?>
										<tr>
											<td><?php printf (__('Coupon Discount (%d%%):','nation'),$coupon_discount) ?></td>
											<td><?php echo "-".$total.$currencySymbol ?></td>
										</tr>
										<?php 
										} if ( isset($addTax) && $addTax == 1 && isset($hideTax) && $hideTax == 0 ) {
										?>
										<tr>
											<td><?php printf (__('Tax (%d%%):','nation'),$tax) ?></td>
											<td><?php echo "+".($totalRoomPrice-$total)*($tax/100).$currencySymbol; ?></td>
										</tr>
										
										<?php } ?>
										<tr>
											<td id="modal-total"><?php _e('Total:','nation'); ?> </td>
											<td id="modal-price-total"><?php echo $field->price.$currencySymbol; ?></td>
										</tr>
									</table>
								</div>
								<div class="modal-footer">
									<button class="btn" data-dismiss="modal" aria-hidden="true"><?php _e('Close','nation') ?> <span class="icon-remove"></span></button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="eleven columns reservation-content page-left-sidebar step4">
				<?php if ( !isset($_GET["token"]) ) { ?>
				<h4><?php echo $completedHeader; ?></h4>
				<p><?php echo $completedDescription; ?></p>
				<?php } else { ?>
				<h4><?php echo $completedHeaderPayPal; ?></h4>
				<p><?php echo $completedDescriptionPaypal; ?></p>
				<?php } ?>
				<ul id="complete-contact">
					<li id="by-phone"><span class='icon-mobile-phone'></span><div class='contact-info-content'><div class='contact-info-method-name'><?php _e('telephone:','nation') ?></div> <div class="contact-info-value"><?php echo $headerTelephone ?></div></div><div style='clear:both'></div></li>
					<li id="contact-email"><span class='icon-envelope-alt'></span><div class='contact-info-content'><div class='contact-info-method-name'><?php _e('email:','nation') ?></div> <?php echo $headerEmail; ?></div><div style='clear:both'></div></li>
				</ul>
				<a href="<?php echo home_url() ?>" class="step4-return-home"><?php _e('Return to Homepage','nation') ?></a>
			</div>
			<!-- END STEP 4 RESERVATION FORM -->
			
			<?php } ?>
			
		</div>
					
<?php get_footer(); 
ob_end_flush();

?>