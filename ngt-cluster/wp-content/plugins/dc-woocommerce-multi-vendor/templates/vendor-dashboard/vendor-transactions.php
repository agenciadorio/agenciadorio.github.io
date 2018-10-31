<?php
/**
 * The template for displaying vendor transaction details
 *
 * Override this template by copying it to yourtheme/dc-product-vendor/vendor-dashboard/vendor-transaction_detail.php
 *
 * @author 		WC Marketplace
 * @package 	WCMp/Templates
 * @version   2.2.0
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
global $WCMp;
?>
<div class="col-md-12">
    
    <div class="panel panel-default">
        <div class="panel-body">
            <div id="vendor_transactions_date_filter" class="form-inline datatable-date-filder">
                <div class="form-group">
                    <span class="date-inp-wrap">
                        <input id="wcmp_from_date" class="form-control" name="from_date" class="pickdate gap1" placeholder="From" value ="<?php echo date('Y-m-01'); ?>"/>
                    </span>
                </div>
                <div class="form-group">
                    <span class="date-inp-wrap">
                        <input id="wcmp_to_date" class="form-control" name="to_date" class="pickdate" placeholder="To" value ="<?php echo   date('Y-m-t'); ?>"/>
                    </span>
                </div>
                <button type="button" name="order_export_submit" id="do_filter"  class="btn btn-default" ><?php _e('Show', 'dc-woocommerce-multi-vendor') ?></button>
            </div>  
            <form method="post" name="export_transaction">
                <div class="wcmp_table_holder">
                    <table id="vendor_transactions" class="get_wcmp_transactions table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center"><input class="select_all_transaction" type="checkbox" onchange="toggleAllCheckBox(this, 'vendor_transactions');"></th>
                                <th><?php _e('Date', 'dc-woocommerce-multi-vendor'); ?></th>
                                <th><?php _e('Transc.ID', 'dc-woocommerce-multi-vendor'); ?></td>
                                <th><?php _e('Commission IDs', 'dc-woocommerce-multi-vendor'); ?></th>
                                <th><?php _e('Fee', 'dc-woocommerce-multi-vendor'); ?></th>
                                <th><?php _e('Net Earnings', 'dc-woocommerce-multi-vendor'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
                <div id="export_transaction_wrap" class="wcmp-action-container wcmp_table_loader" style="display: none;">
                    <input type="hidden" id="export_transaction_start_date" name="from_date" value="<?php echo date('Y-m-01'); ?>" />
                    <input id="export_transaction_end_date" type="hidden" name="to_date" value="<?php echo date('Y-m-t'); ?>" />
                    <button type="submit" name="export_transaction" class="btn btn-default"><?php _e('Download CSV', 'dc-woocommerce-multi-vendor'); ?></button>
                    <div class="clear"></div>
                </div>
            </form>
        </div>
    </div>  
</div>
<script>
jQuery(document).ready(function($) {
    $( "#wcmp_from_date" ).datepicker({ 
        dateFormat: 'yy-mm-dd',
        onClose: function (selectedDate) {
            $("#wcmp_to_date").datepicker("option", "minDate", selectedDate);
        }
    });
    $( "#wcmp_to_date" ).datepicker({ 
        dateFormat: 'yy-mm-dd',
        onClose: function (selectedDate) {
            $("#wcmp_from_date").datepicker("option", "maxDate", selectedDate);
        }
    });
    var vendor_transactions;
    vendor_transactions = $('#vendor_transactions').DataTable({
        ordering  : false,
        searching  : false,
        processing: true,
        serverSide: true,
        language: {
            "emptyTable": "<?php echo trim(__('Sorry. No transactions are available.','dc-woocommerce-multi-vendor')); ?>",
            "processing": "<?php echo trim(__('Processing...', 'dc-woocommerce-multi-vendor')); ?>",
            "info": "<?php echo trim(__('Showing _START_ to _END_ of _TOTAL_ transactions','dc-woocommerce-multi-vendor')); ?>",
            "infoEmpty": "<?php echo trim(__('Showing 0 to 0 of 0 transactions','dc-woocommerce-multi-vendor')); ?>",
            "lengthMenu": "<?php echo trim(__('Number of rows _MENU_','dc-woocommerce-multi-vendor')); ?>",
            "zeroRecords": "<?php echo trim(__('No matching transactions found','dc-woocommerce-multi-vendor')); ?>",
            "search": "<?php echo trim(__('Search:','dc-woocommerce-multi-vendor')); ?>",
            "paginate": {
                "next":  "<?php echo trim(__('Next','dc-woocommerce-multi-vendor')); ?>",
                "previous":  "<?php echo trim(__('Previous','dc-woocommerce-multi-vendor')); ?>"
            }
        },
        initComplete: function (settings, json) {
            var info = this.api().page.info();
            if (info.recordsTotal > 0) {
                $('#export_transaction_wrap').show();
            }
            $('#display_trans_from_dt').text($('#wcmp_from_date').val());
            $('#export_transaction_start_date').val($('#wcmp_from_date').val());
            $('#display_trans_to_dt').text($('#wcmp_to_date').val());
            $('#export_transaction_end_date').val($('#wcmp_to_date').val());
        },
        drawCallback: function () {
            $('table.dataTable tr [type="checkbox"]').each(function(){
                if($(this).parent().is('span.checkbox-holder')) return;
                $(this).wrap('<span class="checkbox-holder"></span>').after('<i class="wcmp-font ico-uncheckbox-icon"></i>');
            })
        },
        ajax:{
            url : '<?php echo add_query_arg( 'action', 'wcmp_vendor_transactions_list', $WCMp->ajax_url() ); ?>', 
            type: "post",
            data: function (data) {
                data.from_date = $('#wcmp_from_date').val();
                data.to_date = $('#wcmp_to_date').val();
            },
            error: function(xhr, status, error) {
                $("#vendor_transactions tbody").append('<tr class="odd"><td valign="top" colspan="6" class="dataTables_empty" style="text-align:center;">'+error+' - <a href="javascript:window.location.reload();"><?php _e('Reload', 'dc-woocommerce-multi-vendor'); ?></a></td></tr>');
                $("#vendor_transactions_processing").css("display","none");
            }
        },
        columns: [
            { data: "select_transaction", className: "text-center" },
            { data: "date" },
            { data: "transaction_id" },
            { data: "commission_ids" },
            { data: "fees" },
            { data: "net_earning" }
        ]
    });
    $(document).on('click', '#vendor_transactions_date_filter #do_filter', function () {
        $('#display_trans_from_dt').text($('#wcmp_from_date').val());
        $('#export_transaction_start_date').val($('#wcmp_from_date').val());
        $('#display_trans_to_dt').text($('#wcmp_to_date').val());
        $('#export_transaction_end_date').val($('#wcmp_to_date').val());
        vendor_transactions.ajax.reload();
    });
});
</script>