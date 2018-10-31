<?php 

$price = get_post_meta( $ticket['WooCommerceEventsVariationID'], '_regular_price', true);

if(empty($price)) {

    $price = get_post_meta( $ticket['WooCommerceEventsVariationID'], '_sale_price', true);

}

$currencySymbol = get_woocommerce_currency_symbol();

if(!empty($price)) {

    $price = $currencySymbol.''.$price;

} else {

    $_product   = wc_get_product($ticket['WooCommerceEventsProductID']);
  }

?>
<style>
    
    @font-face {
        font-family: 'FreeSans';
        src: url(<?php echo $plugins_url.'/fooevents_pdf_tickets/fonts/FreeSans.ttf'; ?>);
    }
    
    @font-face {
        font-family: 'FreeSans';
        src: url(<?php echo $plugins_url.'/fooevents_pdf_tickets/fonts/FreeSansBold.ttf'; ?>);
        font-weight: bold;
    }
    
    body {
        font-family: 'FreeSans', 'Helvetica', 'sans-serif';
        font-size: 14px;
        line-height:18px;
        color:#000;
        padding:20px;
        margin:0;
    }

    h1, h2, h3, h4, h5, h6 {
        font-weight: normal;
        padding:0;
        margin:0 0 10px;
        font-size: 20px;
        line-height: 24px;
    } 

    hr {
        margin-bottom:40px;
        margin-top:40px;
    }

    img {
        margin:10px 0;
    } 
    
    table td {
        padding:0 20px 20px 0;
        border-bottom: solid 1px #eee;
    }
    
    table td.footer {
        padding:20px 0;
        border-bottom: 0;
        color:#777
    } 
            
    .logo {
        padding: 0 0 20px 20px;
        border-left: solid 1px #eee;
        padding-left:20px;
    }

</style>
<table width="100%">
    <tr>
        <td align="left">
            <h1>NIGHTZINHA</h1>
            <p>
                <?php if(!empty($ticket['WooCommerceEventsDate'])) : ?>
                    <strong><?php _e('Date:','fooevents-pdf-tickets') ?></strong> <?php echo $ticket['WooCommerceEventsDate']; ?><br />
                <?php endif; ?> 
                <?php if($ticket['WooCommerceEventsHour'] != '00') :?>
                    <strong><?php _e('Time:','fooevents-pdf-tickets') ?></strong> <?php echo $ticket['WooCommerceEventsHour']; ?>:<?php echo $ticket['WooCommerceEventsMinutes']; ?><?php echo (!empty($ticket['WooCommerceEventsPeriod']))? $ticket['WooCommerceEventsPeriod'] : '' ?>
                    <?php if($ticket['WooCommerceEventsHourEnd'] != '00') : ?>
                        - <?php echo $ticket['WooCommerceEventsHourEnd']; ?>:<?php echo $ticket['WooCommerceEventsMinutesEnd']; ?><?php echo (!empty($ticket['WooCommerceEventsEndPeriod']))? $ticket['WooCommerceEventsEndPeriod'] : '' ?>
                        <br />
                    <?php else : ?>
                        <br />
                    <?php endif; ?>
                <?php endif; ?> 
                <?php if(!empty($ticket['WooCommerceEventsLocation'])) :?>
                    <strong><?php _e('Location:','fooevents-pdf-tickets') ?></strong> <?php echo $ticket['WooCommerceEventsLocation'] ?><br />
                <?php endif; ?>
                    
                <strong><?php _e('N. do Ticket:','fooevents-pdf-tickets') ?></strong> <?php echo $ticket['WooCommerceEventsTicketID']; ?><br />
                <strong><?php _e('Nome:','fooevents-pdf-tickets') ?></strong> <?php echo $ticket['customerFirstName']; ?> <?php echo $ticket['customerLastName']; ?><br />
                
                <?php if(!empty($ticket['WooCommerceEventsAttendeeTelephone'])) :?>
                <strong><?php _e('Telephone Number:','fooevents-pdf-tickets') ?></strong> <?php echo $ticket['WooCommerceEventsAttendeeTelephone']; ?><br />
                <?php endif; ?>
                
                <?php if(!empty($ticket['WooCommerceEventsAttendeeCompany'])) :?>
                <strong><?php _e('Company:','fooevents-pdf-tickets') ?></strong> <?php echo $ticket['WooCommerceEventsAttendeeCompany']; ?><br />
                <?php endif; ?>
                
                <?php if(!empty($ticket['WooCommerceEventsAttendeeDesignation'])) :?>
                <strong><?php _e('Designation:','fooevents-pdf-tickets') ?></strong> <?php echo $ticket['WooCommerceEventsAttendeeDesignation']; ?><br />
                <?php endif; ?>
                
                <?php if(!empty($ticket['WooCommerceEventsVariations'])) :?>
                
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
                        <?php echo '<strong>'.$variationNameOutput.':</strong> '.$variationValueOutput.'<br />'; ?>
                    <?php endforeach; ?>
                
                <?php endif; ?>
                
                <?php if(!empty($ticket['fooevents_custom_attendee_fields_options'])) :?>
                    <?php echo $ticket['fooevents_custom_attendee_fields_options']; ?>
                <?php endif; ?>
                
                <?php if($ticket['WooCommerceEventsTicketDisplayPrice'] != 'off') :?>
                    <?php echo $price; ?>
                <?php endif; ?>  
            </p>
            
            <?php if(!empty($ticket['WooCommerceEventsTicketText'])) : ?>
                <p><?php echo nl2br($ticket['WooCommerceEventsTicketText']); ?></p>
            <?php endif; ?>   
            <?php if(!empty($ticket['WooCommerceEventsDirections'])) :?>
                <p><strong><?php _e('Directions:', 'woocommerce-events'); ?></strong> <?php echo $ticket['WooCommerceEventsDirections']; ?></p>
            <?php endif; ?>                  
                
        </td>
        <td align="center" class="logo">
            <?php if(!empty($ticket['WooCommerceEventsTicketLogoPath'])) :?>
                <img src="<?php echo $ticket['WooCommerceEventsTicketLogoPath']; ?>" alt="" width="150px"/><br />
            <?php endif; ?>
            <?php if($ticket['WooCommerceEventsTicketDisplayBarcode'] != 'off') :?>
                <img src="<?php echo $eventPluginURL.'barcodes/'; ?><?php echo $ticket['WooCommerceEventsTicketID']; ?>.jpg" alt="Barcode: <?php echo $ticket['WooCommerceEventsTicketID']; ?>" width="150px" />
            <?php endif; ?>  
            <br /><?php echo $ticket['WooCommerceEventsTicketID']; ?>
        </td>
    </tr>
    <?php if(!empty($ticket['FooEventsTicketFooterText'])) :?>
    <tr>
        <td colspan="2" class="footer">
            <small><?php echo $ticket['FooEventsTicketFooterText'];?></small>
        </td>
    </tr>
    <?php endif; ?>
</table> 