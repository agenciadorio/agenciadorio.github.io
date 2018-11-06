<?php


function event_list($atts){
	
		extract( shortcode_atts( array(
				'eventtype' => false
		), $atts ) );
		
		if(isset($eventtype)){
			$term = get_term_by( 'slug', $eventtype, 'eventtype' );
			if(is_object($term)){
				$events = get_event_list($term->name);
			} else{
				$events = get_event_list();
			}
		}
		
		
		$return = '';
		if(count($events) > 0){
			
		
			$return .= '
			
			<table class="table eventtable eventarchive" cellpadding:"0" cellspacing="0" >
                <tbody>';
			
                    foreach($events as $e){
                        $d = explode('-',$e['date']);
                        // convert month to word
                        $time = mktime(0, 0, 0, $d[1]);
                        setlocale(LC_TIME, get_bloginfo('language'));
                        $mname = strftime("%b", $time);
                        $return .=  '<tr>
                        <td><span class="day">'.esc_attr($d[2]).'</span><span class="month">'.esc_attr($mname).'</span><span class="year">'.esc_attr($d[0]).'</span></td>
                        <td><a class="span2" href="'.esc_attr($e['permalink']).'"><img src="'.esc_attr($e['thumb']).'" /></a></td>
                        <td class="font-face">'.esc_attr($e['title']).'</td>
                        <td><a class="btn" href="'.esc_attr($e['permalink']).'">INFO</a></td>
                        </tr>';
                    }
                   
				$return .= '</tbody>
			</table>';
			
		}else{
			$return .= ' No events planned.';
		}
		return $return;
             
}   

?>