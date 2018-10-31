<div id="woocommerce_events_data" class="panel woocommerce_options_panel">
    
    <div class="options_group">
            <p class="form-field">
                   <label><?php _e('Is this product an event?:', 'woocommerce-events'); ?></label>
                   <select name="WooCommerceEventsEvent" id="WooCommerceEventsEvent">
                        <option value="NotEvent" <?php echo ($WooCommerceEventsEvent == 'NotEvent')? 'SELECTED' : '' ?>>No</option>
                        <option value="Event" <?php echo ($WooCommerceEventsEvent == 'Event')? 'SELECTED' : '' ?>>Yes</option>
                   </select>
                   <img class="help_tip" data-tip="<?php _e('Enable this option to add event and ticketing features.', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
            </p>
    </div>
    <div id="WooCommerceEventsForm" style="display:none;">
        <div class="options_group">
                <p class="form-field">
                       <label><?php _e('Start Date:', 'woocommerce-events'); ?></label>
                       <input type="text" id="WooCommerceEventsDate" name="WooCommerceEventsDate" value="<?php echo $WooCommerceEventsDate; ?>"/>
                       <img class="help_tip" data-tip="<?php _e('The date that the event is scheduled to take place. This is used as a label on the frontend of the website. FooEvents Calendar uses this to display the event.', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
        </div>
        <?php echo $endDate; ?>
        <?php echo $numDays; ?>
        <div class="options_group">
                <p class="form-field">
                        <label><?php _e('Start time:', 'woocommerce-events'); ?></label>
                        <select name="WooCommerceEventsHour" id="WooCommerceEventsHour">
                            <?php for($x=0; $x<=23; $x++) :?>
                            <?php $x = sprintf("%02d", $x); ?>
                            <option value="<?php echo $x; ?>" <?php echo ($WooCommerceEventsHour == $x) ? 'SELECTED' : ''; ?>><?php echo $x; ?></option>
                            <?php endfor; ?>
                        </select>
                        <select name="WooCommerceEventsMinutes" id="WooCommerceEventsMinutes">
                            <?php for($x=0; $x<=59; $x++) :?>
                            <?php $x = sprintf("%02d", $x); ?>
                            <option value="<?php echo $x; ?>" <?php echo ($WooCommerceEventsMinutes == $x) ? 'SELECTED' : ''; ?>><?php echo $x; ?></option>
                            <?php endfor; ?>
                        </select>
                        <select name="WooCommerceEventsPeriod" id="WooCommerceEventsPeriod">
                            <option value="">-</option>
                            <option value="a.m." <?php echo ($WooCommerceEventsPeriod == 'a.m.') ? 'SELECTED' : ''; ?>>a.m.</option>
                            <option value="p.m." <?php echo ($WooCommerceEventsPeriod == 'p.m.') ? 'SELECTED' : ''; ?>>p.m.</option>
                        </select>
                        <img class="help_tip" data-tip="<?php _e('The time that the event is scheduled to start', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
        </div>
        <div class="options_group">
                <p class="form-field">
                        <label><?php _e('End time:', 'woocommerce-events'); ?></label>
                        <select name="WooCommerceEventsHourEnd" id="WooCommerceEventsHourEnd">
                            <?php for($x=0; $x<=23; $x++) :?>
                            <?php $x = sprintf("%02d", $x); ?>
                            <option value="<?php echo $x; ?>" <?php echo ($WooCommerceEventsHourEnd == $x) ? 'SELECTED' : ''; ?>><?php echo $x; ?></option>
                            <?php endfor; ?>
                        </select>
                        <select name="WooCommerceEventsMinutesEnd" id="WooCommerceEventsMinutesEnd">
                            <?php for($x=0; $x<=59; $x++) :?>
                            <?php $x = sprintf("%02d", $x); ?>
                            <option value="<?php echo $x; ?>" <?php echo ($WooCommerceEventsMinutesEnd == $x) ? 'SELECTED' : ''; ?>><?php echo $x; ?></option>
                            <?php endfor; ?>
                        </select>
                        <select name="WooCommerceEventsEndPeriod" id="WooCommerceEventsEndPeriod">
                            <option value="">-</option>
                            <option value="a.m." <?php echo ($WooCommerceEventsEndPeriod == 'a.m.') ? 'SELECTED' : ''; ?>>a.m.</option>
                            <option value="p.m." <?php echo ($WooCommerceEventsEndPeriod == 'p.m.') ? 'SELECTED' : ''; ?>>p.m.</option>
                        </select>
                        <img class="help_tip" data-tip="<?php _e('The time that the event is scheduled to end', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
        </div>
        <div class="options_group">
                <p class="form-field">
                       <label><?php _e('Venue:', 'woocommerce-events'); ?></label>
                       <input type="text" id="WooCommerceEventsLocation" name="WooCommerceEventsLocation" value="<?php echo $WooCommerceEventsLocation; ?>"/>
                       <img class="help_tip" data-tip="<?php _e('The venue where the event will be held', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
        </div>
        <div class="options_group">
                <p class="form-field">
                       <label><?php _e('GPS Coordinates:', 'woocommerce-events'); ?></label>
                       <input type="text" id="WooCommerceEventsGPS" name="WooCommerceEventsGPS" value="<?php echo $WooCommerceEventsGPS; ?>"/>
                       <img class="help_tip" data-tip="<?php _e("The venue's GPS coordinates ", 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
        </div>
        <div class="options_group">
                <p class="form-field">
                       <label><?php _e('Google Map Coordinates:', 'woocommerce-events'); ?></label>
                       <input type="text" id="WooCommerceEventsGoogleMaps" name="WooCommerceEventsGoogleMaps" value="<?php echo $WooCommerceEventsGoogleMaps; ?>"/>
                       <img class="help_tip" data-tip="<?php _e('The GPS coordinates used to determine the pin position on the Google map that is displayed on the event page.', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
        </div>
        <div class="options_group">
                <p class="form-field">
                       <label><?php _e('Directions:', 'woocommerce-events'); ?></label>
                       <textarea name="WooCommerceEventsDirections" id="WooCommerceEventsDirections"><?php echo $WooCommerceEventsDirections ?></textarea>
                </p>
        </div>
        <div class="options_group">
                <p class="form-field">
                       <label><?php _e('Phone:', 'woocommerce-events'); ?></label>
                       <input type="text" id="WooCommerceEventsSupportContact" name="WooCommerceEventsSupportContact" value="<?php echo $WooCommerceEventsSupportContact; ?>"/>
                       <img class="help_tip" data-tip="<?php _e("Event organizer's landline or mobile phone number", 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
        </div>
        <div class="options_group">
                <p class="form-field">
                       <label><?php _e('Email:', 'woocommerce-events'); ?></label>
                       <input type="text" id="WooCommerceEventsEmail" name="WooCommerceEventsEmail" value="<?php echo $WooCommerceEventsEmail; ?>"/>
                       <img class="help_tip" data-tip="<?php _e("Event organizer's email address", 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
        </div>
        <div class="options_group">
                <?php $WooCommerceEventsTicketLogo = (empty($WooCommerceEventsTicketLogo))? $globalWooCommerceEventsTicketLogo : $WooCommerceEventsTicketLogo; ?>
                <p class="form-field">
                        <label><?php _e('Ticket logo:', 'woocommerce-events'); ?></label>
                        <input id="WooCommerceEventsTicketLogo" class="text uploadfield" type="text" size="40" name="WooCommerceEventsTicketLogo" value="<?php echo $WooCommerceEventsTicketLogo; ?>" />				
                        <span class="uploadbox">
                                <input class="upload_image_button_woocommerce_events  button  " type="button" value="Upload file" />
                                <a href="#" class="upload_reset"><?php _e('Clear', 'woocommerce-events'); ?></a>
                        </span>
                        <img class="help_tip" data-tip="<?php _e('The logo which will be displayed on the ticket in JPG or PNG format', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
        </div>
        <div class="options_group">
                <p class="form-field">
                       <label><?php _e('Ticket subject:', 'woocommerce-events'); ?></label>
                       <input type="text" id="WooCommerceEventsEmailSubjectSingle" name="WooCommerceEventsEmailSubjectSingle" value="<?php echo $WooCommerceEventsEmailSubjectSingle; ?>"/>
                       <img class="help_tip" data-tip="<?php _e("Subject of ticket emails sent out. Insert {OrderNumber} to dispay order number.", 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
        </div>
        <div class="options_group">
            <div style="padding-left: 30px; padding-right: 30px;">
                <p class="form-field">
                    <label><?php _e('Ticket text:', 'woocommerce-events'); ?></label>
                    <?php wp_editor( $WooCommerceEventsTicketText, 'WooCommerceEventsTicketText' ); ?>
                </p>
            </div>
        </div>
        <div class="options_group">
            <div style="padding-left: 30px; padding-right: 30px;">
                <p class="form-field">
                    <label><?php _e('Thank you page text:', 'woocommerce-events'); ?></label>
                    <?php wp_editor( $WooCommerceEventsThankYouText, 'WooCommerceEventsThankYouText' ); ?>
                </p>
            </div>
        </div>
        <div class="options_group">
                <?php $globalWooCommerceEventsTicketBackgroundColor = (empty($globalWooCommerceEventsTicketBackgroundColor))? '' : $globalWooCommerceEventsTicketBackgroundColor; ?>
                <?php $WooCommerceEventsTicketBackgroundColor = (empty($WooCommerceEventsTicketBackgroundColor))? $globalWooCommerceEventsTicketBackgroundColor : $WooCommerceEventsTicketBackgroundColor; ?>
                <p class="form-field">
                       <label><?php _e('Ticket border:', 'woocommerce-events'); ?></label>
                       <input class="color-field" type="text" name="WooCommerceEventsTicketBackgroundColor" value="<?php echo ''.$WooCommerceEventsTicketBackgroundColor; ?>"/>
                       <img class="help_tip" data-tip="<?php _e('The color of the ticket border', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
        </div>
        <div class="options_group">
                <?php $globalWooCommerceEventsTicketButtonColor = (empty($globalWooCommerceEventsTicketButtonColor))? '' : $globalWooCommerceEventsTicketButtonColor; ?>
                <?php $WooCommerceEventsTicketButtonColor = (empty($WooCommerceEventsTicketButtonColor))? $globalWooCommerceEventsTicketButtonColor : $WooCommerceEventsTicketButtonColor; ?>
                <p class="form-field">
                       <label><?php _e('Ticket buttons:', 'woocommerce-events'); ?></label>
                       <input class="color-field" type="text" name="WooCommerceEventsTicketButtonColor" value="<?php echo ''.$WooCommerceEventsTicketButtonColor; ?>"/>
                       <img class="help_tip" data-tip="<?php _e('The color of the ticket button', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
        </div>
        <div class="options_group">
                <?php $globalWooCommerceEventsTicketTextColor = (empty($globalWooCommerceEventsTicketTextColor))? '' : $globalWooCommerceEventsTicketTextColor; ?>
                <?php $WooCommerceEventsTicketTextColor = (empty($WooCommerceEventsTicketTextColor))? $globalWooCommerceEventsTicketTextColor : $WooCommerceEventsTicketTextColor; ?>
                <p class="form-field">
                       <label><?php _e('Ticket button text:', 'woocommerce-events'); ?></label>
                       <input class="color-field" type="text" name="WooCommerceEventsTicketTextColor" value="<?php echo ''.$WooCommerceEventsTicketTextColor; ?>"/>
                       <img class="help_tip" data-tip="<?php _e('The color of the ticket buttons text', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
        </div>
        <div class="options_group">
                <p class="form-field">
                       <label><?php _e('Include purchaser or attendee details on ticket?:', 'woocommerce-events'); ?></label>
                       <input type="checkbox" name="WooCommerceEventsTicketPurchaserDetails" value="on" <?php echo (empty($WooCommerceEventsTicketPurchaserDetails) || $WooCommerceEventsTicketPurchaserDetails == 'on')? 'CHECKED' : ''; ?>>
                       <img class="help_tip" data-tip="<?php _e('Selecting this will display the purchaser or attendee details on the ticket', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
        </div>
        <div class="options_group">
                <p class="form-field">
                       <label><?php _e('Display "Add to calendar" on ticket?:', 'woocommerce-events'); ?></label>
                       <input type="checkbox" name="WooCommerceEventsTicketAddCalendar" value="on" <?php echo (empty($WooCommerceEventsTicketAddCalendar) || $WooCommerceEventsTicketAddCalendar == 'on')? 'CHECKED' : ''; ?>>
                       <img class="help_tip" data-tip="<?php _e('Selecting this will display an - Add to calendar - button on the ticket. Clicking this will generate a .ics file', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
        </div>
        <div class="options_group">
                <p class="form-field">
                       <label><?php _e('Display date and time on ticket?:', 'woocommerce-events'); ?></label>
                       <input type="checkbox" name="WooCommerceEventsTicketDisplayDateTime" value="on" <?php echo (empty($WooCommerceEventsTicketDisplayDateTime) || $WooCommerceEventsTicketDisplayDateTime == 'on')? 'CHECKED' : ''; ?>>
                       <img class="help_tip" data-tip="<?php _e('Selecting this will display the time and date of the event, on the ticket', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
        </div>
        <div class="options_group">
                <p class="form-field">
                       <label><?php _e('Display barcode on ticket?:', 'woocommerce-events'); ?></label>
                       <input type="checkbox" name="WooCommerceEventsTicketDisplayBarcode" value="on" <?php echo (empty($WooCommerceEventsTicketDisplayBarcode) || $WooCommerceEventsTicketDisplayBarcode == 'on')? 'CHECKED' : ''; ?>>
                       <img class="help_tip" data-tip="<?php _e('Selecting this will display the barcode on the ticket', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
        </div>
        <div class="options_group">
                <p class="form-field">
                       <label><?php _e('Display price on ticket?:', 'woocommerce-events'); ?></label>
                       <input type="checkbox" name="WooCommerceEventsTicketDisplayPrice" value="on" <?php echo ($WooCommerceEventsTicketDisplayPrice == 'on')? 'CHECKED' : ''; ?>>
                       <img class="help_tip" data-tip="<?php _e('Selecting this will display the ticket price, on the ticket', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
        </div>
        <div class="options_group">
                <p class="form-field">
                       <label><?php _e('Capture individual attendee details?:', 'woocommerce-events'); ?></label>
                       <input type="checkbox" name="WooCommerceEventsCaptureAttendeeDetails" value="on" <?php echo ($WooCommerceEventsCaptureAttendeeDetails == 'on')? 'CHECKED' : ''; ?>>
                       <img class="help_tip" data-tip="<?php _e('Selecting this will add attendee capture fields on the checkout screen', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
        </div>
        <div class="options_group">
                <p class="form-field">
                       <label><?php _e('Capture attendee telephone?:', 'woocommerce-events'); ?></label>
                       <input type="checkbox" name="WooCommerceEventsCaptureAttendeeTelephone" value="on" <?php echo ($WooCommerceEventsCaptureAttendeeTelephone == 'on')? 'CHECKED' : ''; ?>>
                       <img class="help_tip" data-tip="<?php _e('Selecting this will add a telephone number field to the attendee capture fields on the checkout screen', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
        </div>
        <div class="options_group">
                <p class="form-field">
                       <label><?php _e('Capture attendee company?:', 'woocommerce-events'); ?></label>
                       <input type="checkbox" name="WooCommerceEventsCaptureAttendeeCompany" value="on" <?php echo ($WooCommerceEventsCaptureAttendeeCompany == 'on')? 'CHECKED' : ''; ?>>
                       <img class="help_tip" data-tip="<?php _e('Selecting this will add a company field to the attendee capture fields on the checkout screen', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
        </div>
        <div class="options_group">
                <p class="form-field">
                       <label><?php _e('Capture attendee designation?:', 'woocommerce-events'); ?></label>
                       <input type="checkbox" name="WooCommerceEventsCaptureAttendeeDesignation" value="on" <?php echo ($WooCommerceEventsCaptureAttendeeDesignation == 'on')? 'CHECKED' : ''; ?>>
                       <img class="help_tip" data-tip="<?php _e('Selecting this will add a designation field to the attendee capture fields on the checkout screen', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
        </div>
        <div class="options_group">
                <p class="form-field">
                       <label><?php _e('Email tickets?:', 'woocommerce-events'); ?></label>
                       <input type="checkbox" name="WooCommerceEventsSendEmailTickets" value="on" <?php echo (empty($WooCommerceEventsSendEmailTickets) || $WooCommerceEventsSendEmailTickets == 'on')? 'CHECKED' : ''; ?>>
                       <img class="help_tip" data-tip="<?php _e('Selecting this will email out tickets once the order has been completed', 'woocommerce-events'); ?>" src="<?php echo plugins_url(); ?>/woocommerce/assets/images/help.png" height="16" width="16" />
                </p>
        </div>
        <?php if(!empty($post->ID)) :?>
        <div class="options_group">
            <p><b><?php _e('Export attendees', 'woocommerce-events'); ?></b></p>
            <div id="WooCommerceEventsExportMessage"></div>
            <p class="form-field">
                <label><?php _e('Include unpaid tickets:', 'woocommerce-events'); ?></label><input type="checkbox" id="WooCommerceEventsExportUnpaidTickets" name="WooCommerceEventsExportUnpaidTickets" value="on" <?php echo ($WooCommerceEventsExportUnpaidTickets == 'on')? 'CHECKED' : ''; ?>><br />
                <label><?php _e('Include billing details:', 'woocommerce-events'); ?></label><input type="checkbox" id="WooCommerceEventsExportBillingDetails" name="WooCommerceEventsExportBillingDetails" value="on" <?php echo ($WooCommerceEventsExportBillingDetails == 'on')? 'CHECKED' : ''; ?>><br /><br />
                <a href="<?php echo site_url(); ?>/wp-admin/admin-ajax.php?action=woocommerce_events_csv&event=<?php echo $post->ID; ?><?php echo ($WooCommerceEventsExportUnpaidTickets == 'on')? '&exportunpaidtickets=true' : ''; ?><?php echo ($WooCommerceEventsExportBillingDetails == 'on')? '&exportbillingdetails=true' : ''; ?>" class="button" target="_BLANK"><?php _e('Download CSV of attendees', 'woocommerce-events'); ?></a>
            </p>
        </div>
        <?php endif; ?>
    </div>
</div>