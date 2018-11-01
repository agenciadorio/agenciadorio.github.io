<?php
/*
* @Author 		Jaed Mosharraf
* Copyright: 	2015 Jaed Mosharraf
*/

	$polled_data = array();
	
	$poll_meta_options 	= get_post_meta( $poll_id, 'poll_meta_options', true );
	$polled_data		= get_post_meta( $poll_id, 'polled_data', true );
	
	
?>

<div id="wpp_chart_report_1"></div>

<script>
jQuery(document).ready(function($) {
	var wpp_chart_report_1 = new CanvasJS.Chart("wpp_chart_report_1", {
			
		theme: "theme2",//theme1
		zoomEnabled: true,
		title:{
			text: ''             
		},
		animationEnabled: true,   // change to true
		axisX:{    
			valueFormatString:  "#,#",
		},
		data: [              {
			type: "bar",
			dataPoints: [
			<?php 
			foreach( $poll_meta_options as $option_id => $option_value ) {
		
				if( empty( $option_value ) ) continue;
				$count = 0;
				foreach( $polled_data as $data ){
					if( in_array( $option_id, $data ) )$count++;
				}
				
				echo "{ label: '$option_value',  y: $count  },";
			}
			
			?>
			]
			
		}]
	});
	wpp_chart_report_1.render();
})
</script>
