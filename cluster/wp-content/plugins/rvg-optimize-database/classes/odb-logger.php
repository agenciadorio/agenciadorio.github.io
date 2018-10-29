<?php
/************************************************************************************************
 *
 *	LOGGER CLASS
 *
 ************************************************************************************************/
?>
<?php
class ODB_Logger {
	
	var $sql = '';
	var $res = array();
	
	/********************************************************************************************
	 *	CONSTRUCTOR
	 ********************************************************************************************/	
    function __construct() {
		// v4.6
		add_action('admin_init', array(&$this, 'odb_csv_download'));
	} // __construct()


	/********************************************************************************************
	 *	WRITE RESULTS TO LOG TABLE - v4.6
	 ********************************************************************************************/	
	function odb_add_log() {
		global $odb_class, $wpdb;

		// IS LOGGING ENABLED?
		if($odb_class->odb_rvg_options['logging_on'] == "Y") {
			
			// CONVERT A TIMESTAMP TO THE mm/dd/yyyy hh:mm:ss format
			$d = $odb_class->odb_utilities_obj->odb_parse_timestamp($odb_class->log_arr['timestamp']);

			$this->sql = "
			INSERT INTO `" . $odb_class->odb_logtable_name . "`
				(
					odb_timestamp,
					odb_revisions,
					odb_trash,
					odb_spam,
					odb_tags,
					odb_transients,
					odb_pingbacks,
					odb_oembeds,
					odb_orphans,
					odb_tables,
					odb_before,
					odb_after,
					odb_savings
				 )
				VALUES
				(
					'" . $d . "',
					". $odb_class->log_arr['revisions'] . ",
					". $odb_class->log_arr['trash'] . ",
					". $odb_class->log_arr['spam'] . ",
					". $odb_class->log_arr['tags'] . ",
					". $odb_class->log_arr['transients'] . ",
					". $odb_class->log_arr['pingbacks'] . ",
					". $odb_class->log_arr['oembeds'] . ",
					". $odb_class->log_arr['orphans'] . ",
					". $odb_class->log_arr['tables'] . ",
					'". $odb_class->log_arr['before'] . "',
					'". $odb_class->log_arr['after'] . "',
					'". $odb_class->log_arr['savings'] . "'
				)
			";
			$wpdb->get_results($this->sql);
		} // if($odb_class->odb_rvg_options['logging_on'] == "Y")
	} // add_log_db()
	

	/********************************************************************************************
	 *	TRUNCATE THE LOG TABLE - v4.6
	 ********************************************************************************************/	
	function odb_clear_log() {
		global $odb_class, $wpdb;
		
		$this->sql = "
		TRUNCATE TABLE `" . $odb_class->odb_logtable_name . "`
		";
		
		$wpdb->get_results($this->sql);
	} // clear_log()


	/********************************************************************************************
	 *	VIEW THE LOGS - v4.6.1
	 ********************************************************************************************/	
	function odb_view_log() {
		global $odb_class, $wpdb;
	
		$this->sql = "
		SELECT * FROM `" . $odb_class->odb_logtable_name . "` ORDER BY odb_id ASC
		";
		$this->res = $wpdb->get_results($this->sql, ARRAY_A);

		$odb_class->odb_displayer_obj->display_header();
?>
<div class="odb-title-bar">
  <h2><?php _e('Logs',$odb_class->odb_txt_domain)?></h2>
</div>
<br>
<br>
<div class="odb-log-table">
<table width="97%" border="0" cellspacing="6" cellpadding="0">
<?php		
	
		echo '
  <tr valign="top">		
		';
		
		echo '<th align="left">'.__('date','rvg-optimize-database').'</th>';
		echo '<th align="right">'.__('deleted<br>revisions','rvg-optimize-database').'</th>';
		echo '<th align="right">'.__('deleted<br>trash','rvg-optimize-database').'</th>';
		echo '<th align="right">'.__('deleted<br>spam','rvg-optimize-database').'</th>';
		echo '<th align="right">'.__('deleted<br>tags','rvg-optimize-database').'</th>';
		echo '<th align="right">'.__('deleted<br>transients','rvg-optimize-database').'</th>';
		echo '<th align="right">'.__('deleted<br>pingbacks<br>trackbacks','rvg-optimize-database').'</th>';
		echo '<th align="right">'.__('deleted<br>oEmbed<br>records','rvg-optimize-database').'</th>';
		echo '<th align="right">'.__('deleted<br>orphans','rvg-optimize-database').'</th>';
		echo '<th align="right">'.__('nr of<br>optimized<br>tables','rvg-optimize-database').'</th>';
		echo '<th align="right">'.__('database<br> size<br>BEFORE','rvg-optimize-database').'</th>';
		echo '<th align="right">'.__('database<br>size<br>AFTER','rvg-optimize-database').'</th>';
		echo '<th align="right">'.__('SAVINGS','rvg-optimize-database').'</th>';
		echo '
  </tr>		
		';
				
		for($i = 0; $i < count($this->res); $i++) {
			echo '
  <tr valign="top">			
			';
			echo '<td>' . $this->res[$i]['odb_timestamp'] .'</td>';
			echo '<td align="right">' . $this->res[$i]['odb_revisions'].'</td>';
			echo '<td align="right">' . $this->res[$i]['odb_trash'].'</td>';
			echo '<td align="right">' . $this->res[$i]['odb_spam'].'</td>';
			echo '<td align="right">' . $this->res[$i]['odb_tags'].'</td>';
			echo '<td align="right">' . $this->res[$i]['odb_transients'].'</td>';
			echo '<td align="right">' . $this->res[$i]['odb_pingbacks'].'</td>';
			echo '<td align="right">' . $this->res[$i]['odb_oembeds'].'</td>';
			echo '<td align="right">' . $this->res[$i]['odb_orphans'].'</td>';
			echo '<td align="right">' . $this->res[$i]['odb_tables'].'</td>';
			echo '<td align="right">' . $this->res[$i]['odb_before'].'</td>';
			echo '<td align="right">' . $this->res[$i]['odb_after'].'</td>';
			echo '<td align="right">' . $this->res[$i]['odb_savings'].'</td>';
			echo '
  </tr>			
			';
		}

		echo '
</table>
		';
		// v4.6.2
		$msg = str_replace("'", "\'", __('Clear the log?', $odb_class->odb_txt_domain));		
?>
<script>
function odb_confirm_delete() {
	if(confirm('<?php echo $msg?>')) {
		self.location = 'tools.php?page=rvg-optimize-database&action=clear_log'
		return;
	}
} // function odb_confirm_delete()
</script> 
<br>
<input class="button odb-normal" type="button" name="change_options" value="<?php _e('Change Settings', $odb_class->odb_txt_domain)?>" onclick="self.location='options-general.php?page=odb_settings_page'" />
&nbsp;
<input class="button odb-normal" type="button" name="clear_log" value="<?php _e('Clear Log', $odb_class->odb_txt_domain) ?>" onclick="return odb_confirm_delete();" />
&nbsp;
<input class="button odb-normal" type="button" name="odb_download_csv" value="<?php _e('Export to CSV', $odb_class->odb_txt_domain)?>" onclick="self.location='tools.php?page=rvg-optimize-database&action=odb_download_csv'" />
&nbsp;&nbsp;
<input class="button-primary button-large" type="button" name="start_optimization" value="<?php _e('Start Optimization', $odb_class->odb_txt_domain)?>" onclick="self.location='tools.php?page=rvg-optimize-database&action=run'" class="odb-bold" />
<?php
	} // odb_view_log()


	/********************************************************************************************
	 *	GET THE NUMBER OF LOG RECORDS - v4.6
	 ********************************************************************************************/		
	function odb_log_count() {
		global $odb_class, $wpdb;
		
		$this->sql = "
		SELECT COUNT(*) AS logcnt FROM `" . $odb_class->odb_logtable_name . "`
		";
		$this->res = $wpdb->get_results($this->sql, ARRAY_A);
		return $this->res[0]['logcnt'];
	} // odb_log_count()


	/********************************************************************************************
	 *	WRITE THE CSV FILE - v4.6
	 ********************************************************************************************/	
    private function odb_output_csv($export_keys, $items) {
		global $odb_class;
		
		$filename = 'odb_log_'. Date('Ymd') . '.csv';
		
        header("Content-Type: text/csv; charset=utf-8");
        header("Content-Disposition: attachment; filename=".$filename);
        header("Pragma: no-cache");
        header("Expires: 0");
		
		// OUTPUT STREAM
        $output = fopen('php://output', 'w');
		
		$headers = '';
		foreach ($export_keys as $key) {
			if($headers) $headers .= ';';
			$headers .= '"' . $key . '"';
		} // foreach ($export_keys as $key)
		
		// WRITE THE HEADER LINE
		fwrite($output, $headers . "\r\n");

		// WRITE THE DATA LINES
        foreach ($items as $item) {
            unset($csv_line);
            foreach ($export_keys as $key => $value) {
                if (isset($item[$key])) {
                    $csv_line[] = $item[$key];
                } // if (isset($item[$key]))
            } // foreach ($export_keys as $key => $value)
            fputcsv($output, $csv_line, ";", '"');
        } // foreach ($items as $item)

		fclose($output);
    } // odb_output_csv


	/********************************************************************************************
	 *	CREATE THE DATA TO DOWNLOAD - v4.6
	 ********************************************************************************************/		
	function odb_csv_download() {
		global $odb_class, $wpdb;
		
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'odb_download_csv') {
			$export_keys = array(
				'odb_id'  => __('id', $odb_class->odb_txt_domain),
				'odb_timestamp' => __('date', $odb_class->odb_txt_domain),
				'odb_revisions' => __('deleted revisions', $odb_class->odb_txt_domain),
				'odb_trash' => __('deleted trash', $odb_class->odb_txt_domain),
				'odb_spam' => __('deleted spam', $odb_class->odb_txt_domain),
				'odb_tags' => __('deleted tags', $odb_class->odb_txt_domain),
				'odb_transients' => __('deleted transients', $odb_class->odb_txt_domain),
				'odb_pingbacks' => __('deleted pingbacks', $odb_class->odb_txt_domain),
				'odb_orphans' => __('deleted orphans', $odb_class->odb_txt_domain),
				'odb_tables' => __('nr of optimized tables', $odb_class->odb_txt_domain),
				'odb_before' => __('database size BEFORE', $odb_class->odb_txt_domain),
				'odb_after' => __('database size AFTER', $odb_class->odb_txt_domain),
				'odb_savings' => __('SAVINGS', $odb_class->odb_txt_domain)
			);
			
			$this->sql  = 'SELECT * FROM ' . $odb_class->odb_logtable_name . ' ORDER BY odb_id';
			$items = $wpdb->get_results($this->sql, ARRAY_A);
			$this->odb_output_csv($export_keys, $items);
			exit();
		} // if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'odb_download_csv') {
	} // odb_csv_download()
	
} // ODB_Logger



