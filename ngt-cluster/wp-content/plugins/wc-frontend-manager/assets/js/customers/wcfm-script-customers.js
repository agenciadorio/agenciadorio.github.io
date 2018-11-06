$wcfm_shop_customers_table = '';

jQuery(document).ready(function($) {
	
	$wcfm_shop_customers_table = $('#wcfm-shop-customers').DataTable( {
		"processing": true,
		"serverSide": true,
		"responsive": true,
		"pageLength": dataTables_config.pageLength,
		"language"  : $.parseJSON(dataTables_language),
		"columns"   : [
										{ responsivePriority: 1 },
										{ responsivePriority: 6 },
										{ responsivePriority: 4 },
										{ responsivePriority: 5 },
										{ responsivePriority: 4 },
										{ responsivePriority: 3 },
										{ responsivePriority: 4 },
										{ responsivePriority: 4 },
										{ responsivePriority: 2 },
										{ responsivePriority: 7 },
										{ responsivePriority: 1 }
								],
		"columnDefs": [ { "targets": 0, "orderable" : false }, 
									  { "targets": 1, "orderable" : false }, 
										{ "targets": 2, "orderable" : false },
										{ "targets": 3, "orderable" : false },
										{ "targets": 4, "orderable" : false }, 
										{ "targets": 5, "orderable" : false },
										{ "targets": 6, "orderable" : false },
										{ "targets": 7, "orderable" : false }, 
										{ "targets": 8, "orderable" : false },
										{ "targets": 9, "orderable" : false },
										{ "targets": 10, "orderable" : false },
									],
		'ajax': {
			"type"   : "POST",
			"url"    : wcfm_params.ajax_url,
			"data"   : function( d ) {
				d.action       = 'wcfm_ajax_controller',
				d.controller   = 'wcfm-customers'
			},
			"complete" : function () {
				initiateTip();
				
				// Fire wcfm-appointments table refresh complete
				$( document.body ).trigger( 'updated_wcfm_shop_customers' );
			}
		}
	} );
	
	// Delete Customer
	$( document.body ).on( 'updated_wcfm_shop_customers', function() {
		$('.wcfm_customer_delete').each(function() {
			$(this).click(function(event) {
				event.preventDefault();
				var rconfirm = confirm("Are you sure and want to delete this 'Customer'?\nYou can't undo this action ...");
				if(rconfirm) deleteWCFMCustomer($(this));
				return false;
			});
		});
	});
	
	function deleteWCFMCustomer(item) {
		jQuery('#wcfm-shop-customers_wrapper').block({
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
		});
		var data = {
			action  : 'delete_wcfm_customer',
			customerid : item.data('customerid')
		}	
		jQuery.ajax({
			type:		'POST',
			url: wcfm_params.ajax_url,
			data: data,
			success:	function(response) {
				if($wcfm_shop_customers_table) $wcfm_shop_customers_table.ajax.reload();
				jQuery('#wcfm-shop-customers_wrapper').unblock();
			}
		});
	}
	
	// Screen Manager
	$( document.body ).on( 'updated_wcfm_shop_customers', function() {
		$.each(wcfm_customers_screen_manage, function( column, column_val ) {
		  $wcfm_shop_customers_table.column(column).visible( false );
		} );
	});
} );