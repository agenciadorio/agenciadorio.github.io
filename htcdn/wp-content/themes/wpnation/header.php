<?php 
	//Extracting the values that user defined in OptionTree Plugin 
	$logoUrl = ot_get_option('logo_url');
	$smallLogoUrl = ot_get_option('small_logo_url');
	$headerTelephone = ot_get_option('telephone');
	$headerEmail = ot_get_option('email');
	$headerReservation = ot_get_option('header_reservation_link');	
	$generalAddress = ot_get_option('general_address');
	$countryFlag = ot_get_option('country_flag');
	$siteLanguage = ot_get_option('website_language');
	$languageSelector = ot_get_option('language_selector');
	$headerBar = ot_get_option('top_header_bar');
	
	// Home page's reservation widget 	
	$showResWidget = ot_get_option('show_res_widget');
	$bookingLink = ot_get_option('booking_link');
	$widgetHeaderText = ot_get_option('widget_header_text');
	
	$widgetMaxAdult = ot_get_option('widget_max_adult');
	$widgetMaxChildren = ot_get_option('widget_max_children');
	$widgetRoomNumber = ot_get_option('widget_room_number');
	
?>

<!DOCTYPE html>
<!--[if IE 8 ]>    <html class="ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 9 ]>    <html class="ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html <?php language_attributes(); ?>> <!--<![endif]-->
	<head>
		<title><?php bloginfo('name'); ?></title>
		<meta name="description" content="<?php bloginfo('description') ?>" />
		<link type="image/x-icon" rel="icon" href="<?php echo get_template_directory_uri() ?>/favicon.ico"  /> 
		<meta name="viewport" content="width=device-width, initial-scale=1.0">				
		<?php wp_head(); ?> 
	</head>
	<body id="to-top" <?php if ( is_404() ) { body_class('body-error-page'); } else { body_class(); } ?>>
						
		<div id="wrapper">
			<!-- BEGIN HEADER -->
			<header id="main-page-header-wrap" class='<?php 
					if ( is_page_template('home-page.php') ) { 
						echo "header-shadow "; 
					} if ( isset($headerBar[0]) && $headerBar[0] == 'off' ) {
						echo "main-page-extra-padding";
					} 
				?>'>
				
				
				
				<?php if ( !isset($headerBar[0]) || $headerBar[0] != 'off' ) { ?>
				 
				<!-- BEGIN TOP INFO BAR -->
				<div id="headcontainer">
					<div id="top-sticky-bar" class="container">
						<div id="sticky-top-bar">
							<div id="top-contact-wrap">
								<!-- BEGIN TOP CONTACT INFO -->
								<div id="top-street-address"><span class="icon-map-marker"></span><?php echo $generalAddress; ?></div>
								<div id="top-phone"><span class="icon-mobile-phone"></span><?php echo $headerTelephone; ?></div>
								<div id="top-email"><span class="icon-envelope"></span> <?php echo $headerEmail; ?></div>
								<!-- END TOP CONTACT INFO -->
								<a href="<?php echo $headerReservation;?>" id="header-reservation-button"><?php _e('Book Now','nation'); ?></a>
								
								<div id="top-search" <?php if ( isset($languageSelector[0]) && $languageSelector[0] == 'off' ) { echo "class='hide-border'"; } ?>><span class="icon-search"></span>
									<div id="top-search-window-wrap"><?php get_search_form(); ?></div>
								</div>
								
								<?php if ( !isset($languageSelector[0]) || $languageSelector[0] != 'off' ) { ?>
								
								<!-- BEGIN TOP LANGUAGES SELECTOR -->
								
								
								<div id="top-language-select">
									<ul class="dropdown">
										<li>
											<a href="#">
												<?php if ( !defined('ICL_LANGUAGE_CODE') ) { ?>
												<img src="<?php echo get_template_directory_uri().'/'.$countryFlag; ?>" class="country-flag"> <?php echo $siteLanguage; ?> <span class="icon-angle-down"></span>
												<?php } else { ?>
												<img src="<?php echo get_template_directory_uri().'/images/languages/'.ICL_LANGUAGE_CODE.'.png'; ?>" class="country-flag"> <?php echo ICL_LANGUAGE_NAME; ?> <span class="icon-angle-down"></span>
												<?php } ?>
											</a>
											
											<!-- BEGIN LANGUAGE MENU -->
											<?php 
											if (defined('ICL_LANGUAGE_CODE')) {
													
																						
											?> 
										
											<ul class="sub_menu">
											<?php 
											$langInfo = icl_get_languages('skip_missing=1'); 
											foreach ($langInfo as $lang) { 
											?>
												<li class="menu-item menu-item-type-custom menu-item-object-custom">
													<a href="<?php echo $lang['url']; ?>"> 
														<img src="<?php echo $lang['country_flag_url']; ?>" class="country-flag"><?php echo $lang['native_name']; ?>
													</a>
												</li>
											<?php } ?>
											</ul>
											
											<?php
											
											} else {
												if ( has_nav_menu( 'language_menu' ) ) {
													wp_nav_menu(array(
														'menu' => 'Language Menu',
														'theme_location' => 'language_menu',
														'container' => false,
														'echo' => true,
														'menu_class' => 'sub_menu',
														'items_wrap' => '<ul class="%2$s">%3$s</ul>',
														'depth' => 1,
														'walker' => new language_menu_walker()
													));
												}
											}
											
											?>	
											<!-- END LANGUAGE MENU -->
										</li>
									</ul>
								</div>
								
								<?php } ?>
								
								<!-- END TOP LANGUAGES SELECTOR -->
								
	
								
							</div>
						</div>
					</div>
										
				</div>
				<!-- END TOP INFO BAR -->
				
				<?php } ?>
			
				<!-- BEGIN LOGO AND NAVIGATION WRAP -->
				<div id="logocontainer" class="container">
					<div id="top-logo-menu-wrap" class="sixteen columns clearfix">
						<div class="three columns" id="top-logo-wrap">
					
							<!-- MAIN LOGO -->
							<a href="<?php echo home_url(); ?>"><img src="<?php if ( isset( $logoUrl['background-image'] ) ) echo $logoUrl['background-image']; ?>" id="main-logo" /><img src="<?php if ( isset( $smallLogoUrl['background-image'] ) ) echo $smallLogoUrl['background-image']; ?>" id="main-logo-min" /></a>
						</div>
					
						<div class="thirteen columns" id="top-navigation-menu-wrap">
					
							<!-- BEGIN MAIN MOBILE NAVIGATION -->
							<?php wp_nav_menu(array(
								'menu' => 'Top Menu',
								'theme_location' => 'top_menu',
								'container' => false,
								'echo' => true,
								'depth' => 3,
								'items_wrap' => '<ul id="mobile-navigation-menu"><li><span class="icon-reorder"></span><ul id="mobile-navigation-menu-list">%3$s</ul></li></ul>',
								'walker' => new mobile_walker()
							)) 
							?>	
							<!-- END MAIN MOBILE NAVIGATION -->
						
							<!-- BEGIN MAIN NAVIGATION -->
							<?php wp_nav_menu(array(
								'menu' => 'top_menu',
								'theme_location' => 'top_menu',
								'container' => false,
								'echo' => true,
								'depth' => 3,
								'items_wrap' => '<ul id="top-navigation-menu" class="dropdown %2$s">%3$s</ul>',
								'walker' => new main_menu_walker()
							)) 
							?>							
							<!-- END MAIN NAVIGATION -->
							
							<?php if ( is_page_template('home-page.php') && isset($showResWidget[0]) && $showResWidget[0] == "show" ) { 
							
								$dayNames =	__("['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday']","nation");
								$dayShort = __("['Sun','Mon','Tue','Wed','Thu','Fri','Sat']","nation");
								$dayMin = __("['Su','Mo','Tu','We','Th','Fr','Sa']","nation");
								$monthShort = __("['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec']","nation");
								$monthLong = __("['January','February','March','April','May','June','July','August','September','October','November','December']","nation");
							
							
								//Booking plugin settings
								$bookingSettings = $wpdb->get_row( "SELECT date_format FROM {$wpdb->prefix}nation_booking_settings" );
								$dateFormat = $bookingSettings->date_format;
							
								//Get and convert date that user selected to booking pro system format
								if ( $dateFormat == "european" ) {
									$rangeFormat = "dd-mm-yy";
								} else if ( $dateFormat == "american" ) {
									$rangeFormat = "mm/dd/yy";
								}
							
							
							?>
							
							<script>
								(function($) {
									var dateToday = new Date();
									
									$(document).ready(function() {
										$("#reservation-widget-checkin").datepicker({ 
											
											dayNames:<?php echo $dayNames ?>,
											dayNamesShort:<?php echo $dayShort ?>,
											dayNamesMin:<?php echo $dayMin ?>,
											monthNames:<?php echo $monthLong ?>,
											monthNamesShort:<?php echo $monthShort ?>,
											
											dateFormat: "<?php echo $rangeFormat ?>",
											
											nextText: '<span class="icon-chevron-sign-right"></span>',
											prevText: '<span class="icon-chevron-sign-left"></span>',
											minDate: dateToday,
											beforeShow: function() {
												$('#ui-datepicker-div').addClass('reservation-widget-datepicker');
											}
										});
										$("#reservation-widget-checkout").datepicker({
										
											dayNames:<?php echo $dayNames ?>,
											dayNamesShort:<?php echo $dayShort ?>,
											dayNamesMin:<?php echo $dayMin ?>,
											monthNames:<?php echo $monthLong ?>,
											monthNamesShort:<?php echo $monthShort ?>,
											
											dateFormat: "<?php echo $rangeFormat ?>",
											
											nextText: '<span class="icon-chevron-sign-right"></span>',
											prevText: '<span class="icon-chevron-sign-left"></span>',
											minDate: dateToday,
											beforeShow: function() {
												$('#ui-datepicker-div').addClass('reservation-widget-datepicker');
											}
										});
										
										$("#reservation-widget-wrap-form-element").submit(function() {
											var roomNumber = $("#reservation-widget-wrap #reservation-widget-rooms").val();
											var adultNumber = $("#reservation-widget-wrap #reservation-widget-adults").val();
											var childNumber = $("#reservation-widget-wrap #reservation-widget-children").val();
									
											for ( var i=1;i<=roomNumber;i++ ) {	
												$("#reservation-widget-wrap-form-element").append("<input type='hidden' name='room-adults"+i+"' value='"+adultNumber+"'>");
												$("#reservation-widget-wrap-form-element").append("<input type='hidden' name='room-children"+i+"' value='"+childNumber+"'>");
											}				
										});
							
									});
								})(window.jQuery);
							</script>
							<?php 
							$reservationPage = get_pages(array(
								'meta_key' => '_wp_page_template',
								'meta_value' => 'reservation.php'
							));
							$reservationID = $reservationPage[0]->ID;
							?>
							<div id="reservation-widget-wrap">
								<div id="reservation-widget-header"><?php echo $widgetHeaderText; ?></div>
								<div id="reservation-widget-content">
									<form target="_blank" action="<?php if ( isset($bookingLink) && $bookingLink != "" ) { echo $bookingLink; } else { echo get_permalink( $reservationID ); } ?>" method="post" id="reservation-widget-wrap-form-element">
										<div id="check-in-wrap">
											<div class="caption"><?php _e("Check-in date","nation"); ?></div>
											<?php
											
											if ( $dateFormat == "european" ) {
												$today = date("d-m-Y"); 
											} else if ( $dateFormat == "american" ) {
												$today = date("m/d/Y");
											}
											
											?>
											<input type="text" name="check-in" id="reservation-widget-checkin" value="<?php echo $today; ?>">
										</div><div id="rooms-wrap">
											<div class="caption"><?php _e("Rooms","nation"); ?></div>
											<select name="room-number" id="reservation-widget-rooms">
												<?php for ( $i=1; $i<=$widgetRoomNumber; $i++ ) { 
													echo "<option value='".$i."'>".$i."</option>";
												} ?>
											</select>
										</div>
										
										<div style="clear:both;"></div>
										<div id="check-out-wrap">
											<div class="caption"><?php _e("Check-out date","nation"); ?></div>
											<?php 
											
											$tomorrow = mktime(0,0,0,date("m"),date("d")+1,date("Y")); 
											
											if ( $dateFormat == "european" ) {
												$tommorrow = date("d-m-Y",$tomorrow);
											} else if ( $dateFormat == "american" ) {
												$tommorrow = date("m/d/Y",$tomorrow);
											}
											
											?>
											<input type="text" name="check-out" id="reservation-widget-checkout" value="<?php echo $tommorrow; ?>">
										</div>
										<div id="adults-wrap">
											<div class="caption"><?php _e("Adults","nation"); ?></div>
											<select name="adults" id="reservation-widget-adults">
											<?php for ( $i=1; $i<=$widgetMaxAdult; $i++ ) { 
												echo "<option value='".$i."'>".$i."</option>";
											 } ?>
											</select>
										</div>
										<div id="children-wrap">
											<div class="caption"><?php _e("Children","nation"); ?></div>
											<select name="children" id="reservation-widget-children">
											<?php for ( $i=0; $i<=$widgetMaxChildren; $i++ ) { 
												echo "<option value='".$i."'>".$i."</option>";
											 } ?>
											</select>
										</div>
										<div style="clear:both;"></div>
										<input type="submit" id="submit-button" value="<?php _e("Online Reservation","nation"); ?>">
									</form>
								</div>
							</div>
							<?php } ?>
						</div>
					</div>
				</div>
				<!-- END LOGO AND NAVIGATION WRAP -->
		
			</header>
			<!-- END HEADER -->