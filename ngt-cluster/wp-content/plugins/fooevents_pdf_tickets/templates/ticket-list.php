<h1>Tickets</h1>
<table>
<?php foreach($tickets as $ticket) :?>
    <?php $productID = get_post_meta($ticket->ID, 'WooCommerceEventsProductID', true); ?>  
    <?php $WooCommerceEventsTicketID = get_post_meta($ticket->ID, 'WooCommerceEventsTicketID', true); ?>
    <?php $path = $this->Config->eventPluginURL.'pdftickets/'.$WooCommerceEventsTicketID.'-'.$WooCommerceEventsTicketID.'.pdf'; ?>
    <tr>
        <td><?php echo $ticket->post_title; ?></td>
        <td><?php echo get_the_title($productID); ?></td>
        <td><a href="<?php echo $path; ?>" target="_BLANK">Download</a></td>
    </tr>
<?php endforeach; ?>
</table>