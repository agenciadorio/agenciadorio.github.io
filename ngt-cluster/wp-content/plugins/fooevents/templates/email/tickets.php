<?php
if(empty($ticket['WooCommerceEventsTicketBackgroundColor'])) {

    $ticket['WooCommerceEventsTicketBackgroundColor'] = '#55AF71';

}

if(empty($ticket['WooCommerceEventsTicketButtonColor'])) {

    $ticket['WooCommerceEventsTicketButtonColor'] = '#55AF71';

}

if(empty($ticket['WooCommerceEventsTicketTextColor'])) {

    $ticket['WooCommerceEventsTicketTextColor'] = '#FFFFFF';

}

$price = get_post_meta( $ticket['WooCommerceEventsVariationID'], '_regular_price', true);

if(empty($price)) {

    $price = get_post_meta( $ticket['WooCommerceEventsVariationID'], '_sale_price', true);

}

$currencySymbol = get_woocommerce_currency_symbol();

if(!empty($price)) {

    $price = $currencySymbol.''.$price;

} else {

    $_product   = wc_get_product($ticket['WooCommerceEventsProductID']);
    $price      = $_product->get_price_html();

}
    
?><table cellpadding="0" cellspacing="0" style="border:10px solid <?php echo $ticket['WooCommerceEventsTicketBackgroundColor']; ?>;font-family:Arial,sans-serif;max-width:621px;border-collapse:collapse" class="contenttable">
	<tr>
		<td>
                    <table  border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse" class="contenttable">
                        <tr>
                                <td style="background-color:#ffffff" align="left" valign="middle">
                                        <img border="0" hspace="0" vspace="0" style="display:block;background-color:<?php echo $ticket['WooCommerceEventsTicketBackgroundColor']; ?>;margin:-1px" src="<?php echo $eventPluginURL.'/images/'; ?>circle-left.png" />
                                </td>
                                <td style="background-color:#ffffff">
                                        <table width="260" border="0" cellspacing="20" cellpadding="0" align="left" style="background-color:#ffffff;border-collapse:separate" class="contenttable">
                                                <?php if(!empty($ticket['WooCommerceEventsTicketLogo'])) :?>
                                                <tr>
                                                        <td align="center"><img style="display:block;" src="<?php echo $ticket['WooCommerceEventsTicketLogo']; ?>" width="200" style="width: 200px;" /></td>
                                                </tr>
                                                <?php endif; ?>
                                                <?php if($ticket['WooCommerceEventsTicketDisplayBarcode'] != 'off') :?>
                                                <tr>
                                                        <td align="center" style="width:220px;" width="220"><img style="display:block;" src="<?php echo $eventPluginURL.'barcodes/'; ?><?php echo $ticket['WooCommerceEventsTicketID']; ?>.png"  style="width:220px;" width="220"/></td>
                                                </tr>
                                                <?php endif; ?>
                                                <?php if($this->Config->clientMode) :?>
                                                    <tr><td><a href="<?php echo $ticket['cancelLink']; ?>" style="color: #000; font-size: 10px;">Cancel my ticket</a></td></tr>
                                                <?php endif; ?>
                                        </table>
                                        <table width="290" border="0" cellspacing="20" cellpadding="0" align="left" style="background-color:#ffffff;border-collapse:separate" class="contenttable contenttable_text">
                                                <tr>
                                                        <td>
                                                                <table width="260" border="0" cellspacing="0" cellpadding="0" align="center" style="background-color:#ffffff;border-collapse:collapse" class="contenttable contenttable_text_inner">
                                                                        <?php if($ticket['WooCommerceEventsTicketDisplayDateTime'] != 'off') :?>
                                                                            <tr><td style="font-weight:bold;font-size:16px"><?php echo $ticket['WooCommerceEventsDate']; ?></td></tr>
                                                                            <tr><td style="font-size:14px;color:#777777">
                                                                                <?php echo $ticket['WooCommerceEventsHour']; ?>:<?php echo $ticket['WooCommerceEventsMinutes']; ?><?php echo (!empty($ticket['WooCommerceEventsPeriod']))? $ticket['WooCommerceEventsPeriod'] : '' ?>
                                                                                <?php if($ticket['WooCommerceEventsHourEnd'] != '00') : ?>
                                                                                - <?php echo $ticket['WooCommerceEventsHourEnd']; ?>:<?php echo $ticket['WooCommerceEventsMinutesEnd']; ?><?php echo (!empty($ticket['WooCommerceEventsEndPeriod']))? $ticket['WooCommerceEventsEndPeriod'] : '' ?>
                                                                                <?php endif; ?>
                                                                            </td></tr>
                                                                        <?php endif; ?>
                                                                        <tr><td><img style="display:block;" src="<?php echo $eventPluginURL.'/images/'; ?>spacer_ver_20px.jpg" /></td></tr>
                                                                        <tr><td style="font-weight:bold;font-size:16px"><?php echo $ticket['name'] ?></td></tr>
                                                                        <tr><td style="font-size:14px;color:#777777"><?php _e('Ticket Number:', 'woocommerce-events'); ?> <?php echo $ticket['WooCommerceEventsTicketID']; ?></td></tr>
                                                                        <?php if($ticket['WooCommerceEventsTicketPurchaserDetails'] != 'off') :?>
                                                                            <?php if(!empty($ticket['customerFirstName'])) :?>
                                                                            <tr><td style="font-size:14px;color:#777777"><?php _e('Ticket Holder:', 'woocommerce-events'); ?> <?php echo $ticket['customerFirstName']; ?> <?php echo $ticket['customerLastName']; ?></td></tr>
                                                                            <?php endif; ?>
                                                                            <?php if(!empty($ticket['WooCommerceEventsAttendeeTelephone'])) :?>
                                                                            <tr><td style="font-size:14px;color:#777777"><?php _e('Telephone Number:', 'woocommerce-events'); ?> <?php echo $ticket['WooCommerceEventsAttendeeTelephone']; ?></td></tr>
                                                                            <?php endif; ?>
                                                                            <?php if(!empty($ticket['WooCommerceEventsAttendeeCompany'])) :?>
                                                                            <tr><td style="font-size:14px;color:#777777"><?php _e('Company:', 'woocommerce-events'); ?> <?php echo $ticket['WooCommerceEventsAttendeeCompany']; ?></td></tr>
                                                                            <?php endif; ?>
                                                                            <?php if(!empty($ticket['WooCommerceEventsAttendeeDesignation'])) :?>
                                                                            <tr><td style="font-size:14px;color:#777777"><?php _e('Designation:', 'woocommerce-events'); ?> <?php echo $ticket['WooCommerceEventsAttendeeDesignation']; ?></td></tr>
                                                                            <?php endif; ?>
                                                                        <?php endif; ?> 
                                                                        <?php if(!empty($ticket['WooCommerceEventsTicketType'])) :?>
                                                                            <tr><td style="font-size:14px;color:#777777"><?php _e('Ticket Type:', 'woocommerce-events'); ?> <?php echo $ticket['WooCommerceEventsTicketType']; ?></td></tr>
                                                                        <?php endif; ?>
                                                                        <?php if($ticket['WooCommerceEventsTicketDisplayPrice'] != 'off') :?>
                                                                            <tr><td style="font-size:14px;color:#777777"><?php _e('Price:', 'woocommerce-events'); ?> <?php echo $price; ?></td></tr>
                                                                        <?php endif; ?>    
                                                                        <?php if(!empty($ticket['WooCommerceEventsVariations'])) :?>
                                                                            <tr><td style="font-size:14px;color:#777777">
                                                                            <?php foreach($ticket['WooCommerceEventsVariations'] as $variationName => $variationValue) :?>
                                                                                    <?php 
                                                                                        
                                                                                        $variationNameOutput = str_replace('attribute_', '', $variationName);
                                                                                        $variationNameOutput = str_replace('pa_', '', $variationNameOutput);
                                                                                        $variationNameOutput = str_replace('_', ' ', $variationNameOutput);
                                                                                        $variationNameOutput = str_replace('-', ' ', $variationNameOutput);
                                                                                        $variationNameOutput = str_replace('Pa_', '', $variationNameOutput);
                                                                                        $variationNameOutput = ucwords($variationNameOutput);
                                                                                        
                                                                                        $variationValueOutput = str_replace('_', ' ', $variationValue);
                                                                                        $variationValueOutput = str_replace('-', ' ', $variationValueOutput);
                                                                                        $variationValueOutput = ucwords($variationValueOutput);
                                                                                    ?>
                                                                                    <?php if($variationNameOutput != 'Ticket Type') :?>
                                                                                    <?php echo $variationNameOutput.': '.$variationValueOutput.'<br />'; ?>
                                                                                    <?php endif; ?>
                                                                            <?php endforeach; ?>        
                                                                            </td></tr>
                                                                        <?php endif; ?>
                                                                        <?php if(!empty($ticket['fooevents_custom_attendee_fields_options'])) :?>
                                                                            <tr>
                                                                                <td style="font-size:14px;color:#777777">
                                                                                    <?php echo $ticket['fooevents_custom_attendee_fields_options']; ?>
                                                                                </td>
                                                                            </tr>
                                                                        <?php endif; ?>    
                                                                        <tr><td><img style="display:block;" src="<?php echo $eventPluginURL.'/images/'; ?>spacer_ver_20px.jpg" /></td></tr>
                                                                        <?php if(!empty($ticket['WooCommerceEventsLocation'])) :?>
                                                                            <tr><td style="font-weight:bold;font-size:16px"><?php _e('Location:', 'woocommerce-events'); ?></td></tr>
                                                                            <tr><td style="font-size:14px;color:#777777"><?php echo $ticket['WooCommerceEventsLocation']; ?></td></tr>
                                                                        <?php endif; ?>
                                                                        <?php if(!empty($ticket['WooCommerceEventsDirections'])) :?>
                                                                            <tr><td style="font-weight:bold;font-size:16px"><?php _e('Directions:', 'woocommerce-events'); ?></td></tr>
                                                                            <tr><td style="font-size:14px;color:#777777"><?php echo $ticket['WooCommerceEventsDirections']; ?></td></tr>
                                                                        <?php endif; ?>    
                                                                        <tr><td><img style="display:block;" src="<?php echo $eventPluginURL.'/images/'; ?>spacer_ver_20px.jpg" /></td></tr>
                                                                        <?php if(!empty($ticket['WooCommerceEventsSupportContact'])) :?>
                                                                            <tr><td style="font-size:14px;color:#777777"><?php _e('Contact us for questions and concerns:', 'woocommerce-events'); ?> <?php echo $ticket['WooCommerceEventsSupportContact'] ?></td></tr>
                                                                        <?php endif; ?>
                                                                        <tr><td><img style="display:block;" src="<?php echo $eventPluginURL.'/images/'; ?>spacer_ver_20px.jpg" /></td></tr>
                                                                        <tr>
                                                                                <td>
                                                                                        <table width="150" border="0" cellspacing="0" cellpadding="9" align="left" style="background-color:<?php echo $ticket['WooCommerceEventsTicketButtonColor']; ?>;border-collapse:collapse">
                                                                                                <?php if($ticket['WooCommerceEventsTicketAddCalendar'] != 'off') :?>
                                                                                                <tr>
                                                                                                        <td align="center">
                                                                                                                <a href="<?php echo site_url(); ?>/wp-admin/admin-ajax.php?action=fooevents_ics&event=<?php echo $ticket['WooCommerceEventsProductID']; ?>" style="color:<?php echo $ticket['WooCommerceEventsTicketTextColor']; ?>;text-decoration:none;font-size:16px"><?php _e('Add to calendar', 'woocommerce-events'); ?></a>
                                                                                                        </td>
                                                                                                </tr>
                                                                                                <?php endif; ?>
                                                                                        </table>
                                                                                </td>
                                                                        </tr>
                                                                        <?php if(!empty($ticket['WooCommerceEventsTicketText'])) : ?>
                                                                        <tr><td><?php echo nl2br($ticket['WooCommerceEventsTicketText']); ?></td></tr>
                                                                        <?php endif; ?>
                                                                </table>
                                                        </td>
                                                </tr>
                                        </table>
                                </td>
                                <td style="background-color:#ffffff" align="right" valign="middle"><img border="0" hspace="0" vspace="0" style="display:block;background-color:<?php echo $ticket['WooCommerceEventsTicketBackgroundColor']; ?>;margin:-1px" src="<?php echo $eventPluginURL.'/images/'; ?>circle-right.png" /></td>
                           </tr>
                    </table>
		</td>
	</tr>
</table>
<br /><br />