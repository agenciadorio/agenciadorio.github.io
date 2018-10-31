<?php $Config = new FooEvents_Config(); 
if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
    require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
}
?>
<div class='woocommerce-events-help'>
    <h1>FooEvents for WooCommerce Getting Started</h1>

    <p>
        <strong>FooEvents Extensions</strong> | 
        <a href="http://support.fooevents.com/support/solutions/folders/3000010427" target="new">Getting Started</a> | 
        <a href="http://support.fooevents.com/support/solutions/folders/3000010369" target="new">Frequently Asked Questions</a> | 
        <a href="https://www.fooevents.com/submit-ticket/" target="new">Support Query</a>
    </p>

    <h3 class="woocommerce-events-intro">FooEvents adds seamless event and ticketing functionality to WooCommerce. The plugin adds additional event specific fields and options to the existing WooCommerce products which allows you to create branded event tickets that can be sold on your site.</h3>  

    <h3>FooEvents Extensions</h3>

    <div class="woocommerce-events-extension">
        <a href="https://www.fooevents.com/pricing/" target="_BLANK"><img src="https://www.fooevents.com/wp-content/uploads/2017/07/fooevents_product_covers_fullhouse-512x512.png" alt="FooEvents Full House Bundle" /></a>
        <h3>$159 <span>(All Plugins)</span></h3>
        <h4><a href="https://www.fooevents.com/pricing/" target="_BLANK">FooEvents Full House Bundle</a></h4>
    </div>
    
    <div class="woocommerce-events-extension">
        <a href="https://www.fooevents.com/pricing/" target="_BLANK"><img src="https://www.fooevents.com/wp-content/uploads/2017/07/fooevents_product_covers_fullhouse-512x512.png" alt="FooEvents Full House Bundle" /></a>
        <h3>$129 <span>(Pro)</span></h3>
        <h4><a href="https://www.fooevents.com/pricing/" target="_BLANK">FooEvents Pro Bundle</a></h4>
    </div>

    <div class="woocommerce-events-extension">
        <a href="https://www.fooevents.com/pricing/" target="_BLANK"><img src="https://www.fooevents.com/wp-content/uploads/2017/07/fooevents_product_covers_starterpack-512x512.png" alt="FooEvents Starter Bundle" /></a>
        <h3>$99 <span>(Essentials)</span></h3>
        <h4><a href="https://www.fooevents.com/pricing/" target="_BLANK">FooEvents Starter Bundle</a></h4>
    </div>

    <div class="woocommerce-events-extension">    
        <a href="https://www.fooevents.com/product/fooevents-custom-attendee-fields/" target="_BLANK"><img src="https://www.fooevents.com/wp-content/uploads/2017/07/fooevents_product_covers_custom_attendee_fields-512x512.png" alt="FooEvents Custom Attendee Fields" /></a>

        <?php
        
        if ( fooevents_check_plugin_active('fooevents_custom_attendee_fields/fooevents-custom-attendee-fields.php') || is_plugin_active_for_network('fooevents_custom_attendee_fields/fooevents-custom-attendee-fields.php')) {
            echo "<span class='install-status installed'>Installed</span>";
            echo '<h3>$29 <span>(Installed)</span></h3>';
        } else {
            echo "<span class='install-status notinstalled'>Not Installed</span>";
            echo '<h3>$29 <span>(Not Installed)</span></h3>';
        }
        ?>        
        
        <h4><a href="https://www.fooevents.com/product/fooevents-custom-attendee-fields/" target="_BLANK">FooEvents Custom Attendee Fields</a></h4>
    </div>
    
    <div class="woocommerce-events-extension">    
        <a href="https://www.fooevents.com/fooevents-multi-day/" target="_BLANK"><img src="https://www.fooevents.com/wp-content/uploads/2017/07/fooevents_product_covers_multiday-512x512.png" alt="FooEvents Custom Attendee Fields" /></a>

        <?php
        
        if ($this->is_plugin_active('fooevents_multi_day/fooevents-multi-day.php') || is_plugin_active_for_network('fooevents_multi_day/fooevents-multi-day.php')) {
            echo "<span class='install-status installed'>Installed</span>";
            echo '<h3>$29 <span>(Installed)</span></h3>';
        } else {
            echo "<span class='install-status notinstalled'>Not Installed</span>";
            echo '<h3>$29 <span>(Not Installed)</span></h3>';
        }
        ?>        
        
        <h4><a href="https://www.fooevents.com/fooevents-multi-day/" target="_BLANK">FooEvents Multi-day</a></h4>
    </div>
    
    <div class="woocommerce-events-extension">
        <a href="https://www.fooevents.com/product/fooevents-pdf-tickets/" target="_BLANK"><img src="https://www.fooevents.com/wp-content/uploads/2017/07/fooevents_product_covers_pdf-tickets-512x512.png" alt="FooEvents PDF Tickets Plugin" /></a>
        <?php
        if ( fooevents_check_plugin_active('fooevents_pdf_tickets/fooevents-pdf-tickets.php') || is_plugin_active_for_network('fooevents_pdf_tickets/fooevents-pdf-tickets.php')) {
            echo "<span class='install-status installed'>Installed</span>";
            echo '<h3>$29 <span>(Installed)</span></h3>';
        } else {
            echo "<span class='install-status notinstalled'>Not Installed</span>";
            echo '<h3>$29 <span>(Not Installed)</span></h3>';
        }
        ?>   
        <h4><a href="https://www.fooevents.com/product/fooevents-pdf-tickets/" target="_BLANK">FooEvents PDF Tickets Plugin</a></h4>
    </div>

    <div class="woocommerce-events-extension">
        <a href="https://www.fooevents.com/product/fooevents-calendar/" target="_BLANK"><img src="https://www.fooevents.com/wp-content/uploads/2017/07/fooevents_product_covers_calendar-512x512.png" alt="FooEvents Calendar" /></a>
        <?php
        if ( fooevents_check_plugin_active('fooevents_calendar/fooevents-calendar.php') || is_plugin_active_for_network('fooevents_calendar/fooevents-calendar.php')) {
            echo "<span class='install-status installed'>Installed</span>";
            echo '<h3>$29 <span>(Installed)</span></h3>';
        } else {
            echo "<span class='install-status notinstalled'>Not Installed</span>";
            echo '<h3>$29 <span>(Not Installed)</span></h3>';
        }
        ?> 
        <h4><a href="https://www.fooevents.com/product/fooevents-calendar/" target="_BLANK">FooEvents Calendar</a></h4>
    </div>

    <div class="woocommerce-events-extension">
        <a href="https://www.fooevents.com/product/fooevents-express-check-in/" target="_BLANK"><img src="https://www.fooevents.com/wp-content/uploads/2017/07/fooevents_product_covers_express_checkins-512x512.png" alt="FooEvents Express Check-in" /></a>
        <?php
        if ( fooevents_check_plugin_active('fooevents_express_check_in/fooevents-express-check_in.php') || is_plugin_active_for_network('fooevents_express_check_in/fooevents-express-check_in.php')) {
            echo "<span class='install-status installed'>Installed</span>";
            echo '<h3>$29 <span>(Installed)</span></h3>';
        } else {
            echo "<span class='install-status notinstalled'>Not Installed</span>";
            echo '<h3>$29 <span>(Not Installed)</span></h3>';
        }
        ?> 
        <h4><a href="https://www.fooevents.com/product/fooevents-express-check-in/" target="_BLANK">FooEvents Express Check-in</a></h4>
    </div>

    <div class="woocommerce-events-extension">
        <a href="https://itunes.apple.com/app/event-check-ins/id1129740503" target="_BLANK"><img src="https://www.fooevents.com/wp-content/uploads/2017/06/FooEvents-app-iphone-pro.png" alt="Events Check-ins Pro App" /></a>
        <h3>$9.99 <span>(iOS App)</span></h3>
        <h4><a href="https://itunes.apple.com/app/event-check-ins/id1129740503" target="_BLANK">Events Check-ins Pro App</a></h4>
    </div>


    <div class="clear"></div>

    <h3>Installing FooEvents</h3>    

    <ol>
            <li>Ensure that WordPress and WooCommerce are installed (See above requirements)</li>
            <li>Download the FooEvents for WooCommerce plugin</li>
            <li>Upload the FooEvents plugin to the following directory on your web server: /wp-content/plugins</li>
            <li>Login to your WordPress Admin Area</li>
            <li>Click on ‘Plugins’ in the main menu</li>
            <li>Find the FooEvents for WooCommerce plugin and activate it</li>
            <li>Congratulations! The plugin is now installed and ready to be configured for your event</li>
    </ol>  

    <h3>Setup and event type product</h3>

            <ol>
                    <li>Go to Products &gt; Add Product in the main menu</li>
                    <li>Complete the title, body, description, tags, categories, featured image and gallery as needed</li>
                    <li>Go to the Product Data tab set and select ‘Events’</li>
                    <li>To activate event functionality set the ‘Is this product an event?’ dropdown to ‘yes’. Doing so will reveal additional fields used to create your event</li>
                    <li>Complete the following fields:
                            <ol>
                                    <li>Date - The date that the event is scheduled to take place</li>
                                    <li>Start time - The time that the event is scheduled to start</li>
                                    <li>End time - The time that the event is scheduled to end</li>
                                    <li>Venue - The venue where the event will be held</li>
                                    <li>GPS Coordinates - The venue’s GPS coordinates</li>
                                    <li>Google Map Coordinates - The GPS coordinates used to determine the pin position on the Google map that is displayed on the event page. NB: Please ensure you use the following format:
                                    <ol>
                                            <li>Example: -26.137600, 28.008141</li>
                                            <li>If neccesary you can convert to this format using the following tool: <a href="http://www.gps-coordinates.net/gps-coordinates-converter">http://www.gps-coordinates.net/gps-coordinates-converter</a></li>
                                    </ol>
                                    </li>
                                    <li>Directions - Text directions explaining how to find the venue</li>
                                    <li>Phone - Event organizer’s landline or mobile phone number</li>
                                    <li>Email - Event organizer’s email address</li>
                                    <li>Ticket logo - The logo which will be displayed on the ticket in JPG or PNG format
                                    <ol>
                                            <li>Minimum width - 200px</li>
                                            <li>Minimum height - N/A</li>
                                    </ol>
                                    </li>
                                    <li>Ticket border color - The color of the ticket border</li>
                                    <li>Ticket buttons colour - The color of the ticket button</li>
                                    <li>Ticket button text colour - The color of the ticket button’s text</li>
                                    <li>Include purchaser / attendee details on ticket? - Selecting this will display the purchaser or attendee details on the ticket</li>
                                    <li>Display "Add to calendar" on ticket? - Selecting this will display an “Add to calendar” button on the ticket. Clicking this will generate a .ics file.</li>
                                    <li>Display date and time on ticket? - Selecting this will display the time and date of the event on the ticket</li>
                                    <li>Display barcode on ticket? - Selecting this will display the barcode on the ticket</li>
                                    <li>Display price on ticket? - Selecting this will display the ticket price on the ticket</li>
                                    <li>Capture individual attendee details? - Selecting this will add attendee capture fields on the checkout screen</li>
                                    <li>Capture attendee telephone? - Selecting this will add a telephone number field to the attendee capture fields on the checkout screen</li>
                                    <li>Capture attendee company? - Selecting this will add a company field to the attendee capture fields on the checkout screen</li>
                                    <li>Capture attendee designation? - Selecting this will add a designation field to the attendee capture fields on the checkout screen</li>
                                    <li>Email tickets? - Selecting this will email the tickets to the attendee once the order has been completed</li>
                            </ol>
                    </li>
                    <li>NB: Once you have completed these fields please make sure that you save your post before proceeding!</li>
                    <li>You can create various ticket types using WooCommerce attributes and variations. To do this please follow these instructions:
                    <ol>
                            <li>Go to the ‘Attributes’ tab in the Product Data panel</li>
                            <li>Create a new attribute called ‘Ticket Type’. It’s very important that the attribute is called this as this is the name that is used to reflect the ticket type on the actual ticket</li>
                            <li>Add the name of each ticket type under values and separate them with the pipe symbol ‘|’ e.g. VIP | General | Early Bird</li>
                            <li>Make sure that you select ‘Visible on the product page’ and ‘Used for variations’</li>
                            <li>Save the attributes</li>
                            <li>Click on the ‘Attributes’ tab in the Product Data panel</li>
                            <li>Add a variation for each ticket type and specify the relevant ticket criteria (price, in stock etc.)</li>
                            <li>Make sure that you select ‘Enabled’</li>
                            <li>We recommend that you select ‘Virtual’ if you do not want the shipping information displayed on the checkout screen</li>
                            <li>Save/update the post once all variations have been added</li>
                            <li>The ticket type variations will now display as ticket options when purchasing a ticket</li>
                    </ol>
                    </li>
                    <li>Once your product is published it will appear in your WooCommerce store and users will be able to purchase tickets for your event</li>
            </ol>                         

    <h3>Managing Tickets</h3>
    Every ticket that is attached to a completed WooCommerce order will appear in the ‘Tickets’ admin menu.
    <ol>
            <li>To resend a ticket:
                    <ol>
                            <li>Open the ticket and specify the email address that the ticket should be resent to in the resend option box</li>
                            <li>Click the resend button</li>
                    </ol>
    </li>
            <li>Tickets can have the following statuses:
                    <ol>
                            <li>“Not checked in”</li>
                            <li>“Checked in”</li>
                            <li>“Canceled”</li>
                    </ol>
    </li>
            <li>You can check someone in by changing their status in the Ticket Status box to “Checked in”</li>
            <li>The “Canceled” status can also be used to mark tickets that are no longer valid</li>
    </ol>

    <h3>Barcodes</h3>

    <p>Every ticket that is generated contains a unique barcode that is rendered using the barcode code 128 standard. If you own a scanner that can read ordinary 2D barcodes then you have the option of scanning tickets to find them quickly. The 'FooEvents Check-ins' app can also be used to check-in people at events on your tablet or smartphone device.</p>

    <h3>Global Settings</h3>
    <ol>
            <li>Go to WooCommerce -&gt; Settings</li>
            <li>Click on the “Events” tab</li>
            <li>Change the default event settings as required</li>
            <li>Save changes</li>
    </ol>

    <h3>Modifying theme templates</h3>

    <ol>
            <li>In your WordPress theme create the following directory structure: woocommerce_events/templates</li>
            <li>Copy the template files that you would like to modify from the wp-content/plugins/woocommerce_events/templates directory to the directory that you created in Step 1</li>
            <li>Modify the template files as required</li>
    </ol>

    <h3>Modifying the ticket template</h3>

    <ol>
            <li>In your WordPress theme create the following directory structure: woocommerce_events/templates/email</li>
            <li>Copy the template files in the wp-content/plugins/woocommerce_events/templates/email directory to the directory that you created in Step 1</li>
            <li>Modify the template files as required</li>
    </ol>
    
    <h3>Manually create tickets</h3>
    
    <p>There are two ways to manually create tickets:</p>
    
    <ol>
        <li>From the WordPress Admin Area
            <ol>
                <li>Click on the “Add new” button which you will find under "Tickets"</li>
                <li>Select the event name for the specified event </li>
                <li>Fill out the ticket form and click the “Publish” button to create the ticket</li>
                <li>Please Note: Tickets are NOT automatically sent out after creating the ticket. If you would like to email the ticket to the attendee, click on the “Resend” button on the ticket information screen. </li>
            </ol>
        </li>
        <li>
            From your website
            <ol>
                <li>Complete the purchase process on the front-end of your website</li>
                <li>Once you reach the payment screen, DO NOT make payment, instead go to the WooCommerce orders screen and mark the order as completed</li>
                <li>Tickets will be sent out using this method depending on your events settings</li>
            </ol>
        </li>
    </ol>
    
    <h3>Download CSV attendees list</h3>
    <p>Click on the button labelled “Download CSV of Attendee" which will appear when you edit your product in the “Event” settings tab in your WordPress/WooCommerce Admin Area. This will generate a list of attendees who have been allocated tickets for that particular event.</p>
    
    <h3>How to use the FooEvents Check-ins app</h3>
    <ol>
        <li>Download the 'FooEvents Check-ins' app from either the Play Store (Android) or iTunes (iOS) <a href="http://www.fooevents.com/apps/"target="BLANK">http://www.fooevents.com/apps/</a> </li>
        <li>Enter the following details on the login screen: 
            <ol>
                <li>URL - Your website URL (e.g. www.YOURWEBSITE.com)</li>
                <li>Username &amp; Password - The access details of one of your WordPress users (e.g. 'admin')</li>
            </ol>
        </li>
        <li>Please Note: Access to the app is restricted to the following user roles: 'administrator', 'editor', 'author', 'contributor' and 'shop_manager'.</li>
    </ol>
    
    <h3>Frequently Asked Questions (FAQs)</h3>
    <p>For further help and information on using FooEvents please refer to our <a href="http://support.fooevents.com/support/solutions/folders/3000010369" target="BLANK">FAQs</p>
</div>