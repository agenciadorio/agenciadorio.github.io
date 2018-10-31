<?php
//***************************//
// afo_paginate version 3.1 //
//  with custom paged var   //
//*************************//

if (!class_exists('afo_paginate')) {
	class afo_paginate{
		
		public $per_page = 10;
				
		public $total_rec = 0;
		
		public $total_page = 0;
		
		public $current_page = '';
		
		public $paged_var = 'paged';
	
		public function __construct($per_page = '', $paged_var = ''){
			if( $per_page != '' ){
				$this->per_page	= $per_page;
			}
			if( $paged_var != '' ){
				$this->paged_var = $paged_var;
			}
		}
		
		public function afo_paginate_css(){ ?>
		<style>.page_list_cont{	clear:both;	} .page-numbers{ margin:2px; padding:0px 4px 2px 4px; text-decoration:none; background-color:#ccc; float:left;} .page-numbers.current{ background-color:#0073AA; color:#fff; } </style>
		<?php
		}
	
		public function initialize($query = '', $current_page = ''){
			global $wpdb;
			if(!$query){
				return;
			}
			$total = $wpdb->get_results($query,ARRAY_A);
			$this->total_rec = $wpdb->num_rows; 
			$this->total_page = ceil($this->total_rec/$this->per_page);
			$this->current_page = $current_page;
			$start = $this->start_list_from();
			$query .= " LIMIT ".$start.", ".$this->per_page."";
			$data = $wpdb->get_results($query,ARRAY_A);
			return $data;
		}
	
		public function start_list_from(){
			if(!$this->current_page){
				$page = 1;
				$this->current_page = 1;
			} else {
				$page = $this->current_page;
			}
	
			$start = ($page-1)*$this->per_page;
			return $start;
		}
	
		public function paginate(){
			$this->afo_paginate_css();
			
			echo '<div class="page_list_cont">';
			echo paginate_links( array(
				'base' => add_query_arg( $this->paged_var, '%#%' ),
				'format' => '',
				'prev_text' => __('Prev'),
				'next_text' => __('Next'),
				'total' => $this->total_page,
				'current' => $this->current_page
			));
			echo '</div>';
		}
	}
}