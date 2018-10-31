<?php if(empty($WooCommerceEventsMultidayStatus) || $WooCommerceEventsStatus == 'Unpaid' || $WooCommerceEventsStatus == 'Canceled' || $WooCommerceEventsStatus == 'Cancelled') :?>
<table class="form-table">
	<tbody>     
            <tr valign="top">  
                <td>
                    <label><?php _e('Set Status:', 'woocommerce-events'); ?></label><Br />
                </td>
                <td>
                    <select name="WooCommerceEventsStatus">
                        <option value="Not Checked In" <?php echo ($WooCommerceEventsStatus == 'Not Checked In')? 'SELECTED' : ''; ?>>Not Checked In</option>
                        <option value="Checked In" <?php echo ($WooCommerceEventsStatus == 'Checked In')? 'SELECTED' : ''; ?>>Checked In</option>
                        <option value="Canceled" <?php echo ($WooCommerceEventsStatus == 'Canceled' || $WooCommerceEventsStatus == 'Cancelled')? 'SELECTED' : ''; ?>>Canceled</option>
                        <option value="Unpaid" <?php echo ($WooCommerceEventsStatus == 'Unpaid')? 'SELECTED' : ''; ?>>Unpaid</option>
                    </select>
                    <input type="hidden" value="true" name="ticket_status" />
                </td>
            </tr>
	</tbody>
</table>
<?php else: ?>
<?php echo $WooCommerceEventsMultidayStatus; ?>
<?php endif; ?>
