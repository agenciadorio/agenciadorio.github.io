<?php		
/**
 * Common functions
 *
 * @package   Go Portfolio - WordPress Responsive Portfolio 
 * @author    Granth <granthweb@gmail.com>
 * @link      http://granthweb.com
 * @copyright 2016 Granth
 */

/**
 * Clean input fields
 */
 
function go_portfolio_clean_input( $input_data=array(), $html_allowed_keys=array(), $trash_keys=array() ) {
	foreach( $input_data as $data_key=>$data_value ) {
		if ( is_array( $data_value ) ) {
			 go_portfolio_clean_input( $data_value, $html_allowed_keys, $trash_keys );
		} elseif ( in_array( $data_key, $trash_keys ) ) {
				unset( $input_data[$data_key] );
				continue;
		} else {
				$input_data[$data_key]=stripslashes( trim( $input_data[$data_key] ) );
			if ( empty( $html_allowed_keys ) || !in_array( $data_key, $html_allowed_keys ) ) { 
				$input_data[$data_key] = sanitize_text_field( $input_data[$data_key] );
			}
		}
	}
	return $input_data;
}

/**
 * Custom excerpt function
 */

function go_portfolio_wp_trim_excerpt( $text, $excerpt_word_count=25,  $excerpt_end = '...', $strip_shortcodes=true, $strip_html=true, $allowed_tags='' ) {
		
	/* Delete all shortcodes */
	if ( $strip_shortcodes ) { $text = strip_shortcodes( $text ); };
 
	$text = wpautop( $text );
	$text = do_shortcode( shortcode_unautop( $text ) );
	$text = str_replace( ']]>', ']]&gt;', $text );

	/* Strip tags */
	$text = preg_replace('@<script[^>]*?>.*?</script>@si', '', $text); 
	if ( $strip_html ) { $text = strip_tags( $text, $allowed_tags ); }
	$words = preg_split( "/[\n\r\t ]+/", $text, $excerpt_word_count + 1, PREG_SPLIT_NO_EMPTY );
	
	if ( count( $words ) > $excerpt_word_count ) {
		array_pop( $words );
		$text = implode( ' ', $words );
		$text = $text . $excerpt_end;
	} else {
		$text = implode( ' ', $words );
	}
	
	/* Fix broken HTML */
	if ( $strip_html === false && $text != '' ) {
		
		$charset = get_option( 'blog_charset', 'UTF-8' );
		$text = mb_convert_encoding( $text, 'HTML-ENTITIES', $charset ); 
		$doc = new DOMDocument();
		$doc->encoding = $charset;
		@$doc->loadHTML( $text );
		$text = $doc->saveHTML();

	}																				
	
	return $text;
}

?>
