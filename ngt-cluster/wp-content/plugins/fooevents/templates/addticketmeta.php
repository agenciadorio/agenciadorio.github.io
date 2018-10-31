<div id="woocommerce_events_data" class="panel woocommerce_options_panel">
    <div class="options_group">
            <p class="form-field">
                <label><?php _e('Event:', 'woocommerce-events'); ?></label><br/>
                <select name="WooCommerceEventsEvent" id="WooCommerceEventsEvent" class="required">
                    <option value="">Please select...</option>
                    <?php foreach($events as $event) :?>
                        <option value="<?php echo $event->ID; ?>"><?php echo $event->post_title; ?></option>
                    <?php endforeach; ?>
                </select>   
            </p>
            <div id="woocommerce_events_variations">
                
            </div>
            <h2>Purchaser</h2>
            <p class="form-field">
                <label><?php _e('Existing user:', 'woocommerce-events'); ?></label>
                <select name="WooCommerceEventsClientID" id="WooCommerceEventsClientID" class="required">
                    <option value="0">Select...</option>
                    <?php foreach($users as $user) :?>
                        <option value="<?php echo $user->ID; ?>"><?php echo $user->data->display_name; ?> - <?php echo $user->data->display_name; ?> [<?php echo $user->ID; ?>]</option>
                    <?php endforeach; ?>
                </select> 
            </p>
            <p class="form-field">
                <label><?php _e('Username:', 'woocommerce-events'); ?></label><br/>
                <input type="text" name="WooCommerceEventsPurchaserUserName" id="WooCommerceEventsPurchaserUserName" value="" class="required"/>
            </p>
            <p class="form-field">
                <label><?php _e('Display Name:', 'woocommerce-events'); ?></label><br/>
                <input type="text" name="WooCommerceEventsPurchaserFirstName" id="WooCommerceEventsPurchaserFirstName" value=""  class="required"/>
            </p>
            <p class="form-field">
                <label><?php _e('Email address:', 'woocommerce-events'); ?></label><br/>
                <input type="text" name="WooCommerceEventsPurchaserEmail" id="WooCommerceEventsPurchaserEmail" value="" class="required"/>
            </p>
            <h2>Attendee</h2>
            <p class="form-field">
                <label><?php _e('First name:', 'woocommerce-events'); ?></label><br/>
                <input type="text" name="WooCommerceEventsAttendeeName" id="WooCommerceEventsAttendeeName" value="" class="required"/>
            </p>
            <p class="form-field">
                <label><?php _e('Last name:', 'woocommerce-events'); ?></label><br/>
                <input type="text" name="WooCommerceEventsAttendeeLastName" id="WooCommerceEventsAttendeeLastName" value="" class="required"/>
            </p>
            <p class="form-field">
                <label><?php _e('Email address:', 'woocommerce-events'); ?></label><br/>
                <input type="text" name="WooCommerceEventsAttendeeEmail" id="WooCommerceEventsAttendeeEmail" value="" class="required"/>
            </p>
            <input type="hidden" value="true" name="add_ticket" id="add_ticket" />
    </div>
</div>