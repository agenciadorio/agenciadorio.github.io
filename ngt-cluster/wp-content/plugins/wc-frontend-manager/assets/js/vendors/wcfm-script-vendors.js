$wcfm_vendors_table = '';
$report_vendor = '';
$report_for = '';	
	
jQuery(document).ready(function($) {
	
	$wcfm_vendors_table = $('#wcfm-vendors').DataTable( {
		"processing": true,
		"serverSide": true,
		"pageLength": dataTables_config.pageLength,
		"bFilter"   : false,
		"responsive": true,
		"dom"       : 'Bfrtip',
		"language"  : $.parseJSON(dataTables_language),
		"buttons"   : $wcfm_datatable_button_args,
		"columns"   : [
										{ responsivePriority: 1 },
										{ responsivePriority: 1 },
										{ responsivePriority: 1 },
										{ responsivePriority: 4 },
										{ responsivePriority: 4 },
										{ responsivePriority: 3 },
										{ responsivePriority: 2 },
										{ responsivePriority: 1 },
										{ responsivePriority: 2 },
										{ responsivePriority: 3 },
										{ responsivePriority: 4 },
										{ responsivePriority: 2 },
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
										{ "targets": 11, "orderable" : false },
									],
		'ajax': {
			"type"   : "POST",
			"url"    : wcfm_params.ajax_url,
			"data"   : function( d ) {
				d.action         = 'wcfm_ajax_controller',
				d.controller     = 'wcfm-vendors',
				d.report_vendor  = $report_vendor,
				d.report_for     = $report_for
			},
			"complete" : function () {
				initiateTip();
				
				// Fire wcfm-vendors table refresh complete
				$( document.body ).trigger( 'updated_wcfm-vendors' );
			}
		}
	} );
	
	if( $('#dropdown_report_filter').length > 0 ) {
		$('#dropdown_report_filter').on('change', function() {
		  $report_for = $('#dropdown_report_filter').val();
		  $wcfm_vendors_table.ajax.reload();
		});
	}
	
	if( $('#dropdown_vendor').length > 0 ) {
		$('#dropdown_vendor').on('change', function() {
			$report_vendor = $('#dropdown_vendor').val();
			$wcfm_vendors_table.ajax.reload();
		}).select2( $wcfm_vendor_select_args );
	}
	
	// Action Manager
	$( document.body ).on( 'updated_wcfm-vendors', function() {
		$('.wcfm_vendor_enable_button').each(function() {
			$(this).click(function( event ) {
				event.preventDefault();
				$('#wcfm_vendors_listing_expander').block({
					message: null,
					overlayCSS: {
						background: '#fff',
						opacity: 0.6
					}
				});
				var data = {
					action       : 'wcfm_vendor_enable',
					memberid     : $(this).data('memberid'),
				}	
				$.post(wcfm_params.ajax_url, data, function(response) {
					if(response) {
						$wcfm_vendors_table.ajax.reload();
						$('#wcfm_vendors_listing_expander').unblock();
					}
				});
			});
		});
		
		$('.wcfm_vendor_disable_button').each(function() {
			$(this).click(function( event ) {
				event.preventDefault();
				$('#wcfm_vendors_listing_expander').block({
					message: null,
					overlayCSS: {
						background: '#fff',
						opacity: 0.6
					}
				});
				var data = {
					action       : 'wcfm_vendor_disable',
					memberid     : $(this).data('memberid'),
				}	
				$.post(wcfm_params.ajax_url, data, function(response) {
					if(response) {
						$wcfm_vendors_table.ajax.reload();
						$('#wcfm_vendors_listing_expander').unblock();
					}
				});
			});
		});
	});
	
	// Screen Manager
	$( document.body ).on( 'updated_wcfm-vendors', function() {
		$.each(wcfm_vendors_screen_manage, function( column, column_val ) {
		  $wcfm_vendors_table.column(column).visible( false );
		} );
	});
	
	// Dashboard FIlter
	if( $('.wcfm_filters_wrap').length > 0 ) {
		$('.dataTable').before( $('.wcfm_filters_wrap') );
		$('.wcfm_filters_wrap').css( 'display', 'inline-block' );
	}
	
} );