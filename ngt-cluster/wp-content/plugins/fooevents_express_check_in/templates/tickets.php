    <?php if(!empty($tickets->posts)) : ?>
    <table class="fooevents-express-check-in-wrapper wp-list-table widefat fixed striped posts">
        <tbody id="the-list">
        <thead>
            <tr>
                <th><?php echo __( 'Ticket ID', 'fooevents-express-check-in' ); ?></th> 
                <th class="manage-column"><?php echo __( 'Purchaser', 'fooevents-express-check-in' ); ?></th> 
                <th class="manage-column"><?php echo __( 'Attendee', 'fooevents-express-check-in' ); ?></th> 
                <th class="manage-column"><?php echo __( 'Event', 'fooevents-express-check-in' ); ?></th> 
                <th class="manage-column" colspan="4"><?php echo __( 'Check-in Status', 'fooevents-express-check-in' ); ?></th> 
            </tr>
        </thead>
        <?php echo $tickets_data; ?>
        </tbody>
    </table>
    <?php else : ?>
        <div class="fooevents-express-check-in-notickets">
            <h2><?php echo __( 'No tickets found, please try again', 'fooevents-express-check-in' ); ?></h2>
        </div>
    <?php endif; ?>
