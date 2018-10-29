<?php

// Register Shortcode
function ws_facebook_likebox_add_shortcode( $atts ) {
	
	extract( shortcode_atts(
		array( 
			'page_id' 	=> 'webshouters',
			'width' 		=> '250',
			'height' 		=> '500',
			'adapt_width' => 'no',
			'small_header' => 'no',
			'hide_cover_photo'=>'no',
			'show_faces'=> 'yes',
			'tabs' => 'timeline',
			'locale_lang' => 'en_US',
		  ), $atts )
	);
	
	$html = '';
	
	$html .= '<div class="fb-page ws-fb-like-box" data-href="https://www.facebook.com/'.$page_id.'" 
				data-tabs="'.$tabs.'" 
				data-width="'.$width.'" 
				data-height="'.$height.'"
				data-small-header="'.$small_header.'" 
				data-adapt-container-width="'.$adapt_width.'" 
				data-hide-cover="'.$hide_cover_photo.'"
				data-show-facepile="'.$show_faces.'">
				<div class="fb-xfbml-parse-ignore">
					<blockquote cite="https://www.facebook.com/'.$page_id.'">
						<a href="https://www.facebook.com/'.$page_id.'">Facebook</a>
					</blockquote>
				</div>
			 </div> ';
				 
	$html .= '<div id="fb-root"></div>
			<script>(function(d, s, id) {
			  var js, fjs = d.getElementsByTagName(s)[0];
			  if (d.getElementById(id)) return;
			  js = d.createElement(s); js.id = id;
			  js.src = "//connect.facebook.net/'. $locale_lang .'/sdk.js#xfbml=1&version=v2.6";
			  fjs.parentNode.insertBefore(js, fjs);
			}(document, \'script\', \'facebook-jssdk\'));</script>';	
	
	return $html;
}
add_shortcode( 'ws-facebook-likebox', 'ws_facebook_likebox_add_shortcode' );

?>
