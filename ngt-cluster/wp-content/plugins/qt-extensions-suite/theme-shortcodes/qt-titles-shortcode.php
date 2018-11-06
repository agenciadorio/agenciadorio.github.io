<?php  
/**
 * Special shortcode to output the equalizer titles
 */




if(!function_exists('qt_special_titles')){
	function qt_special_titles ($atts){
		extract( shortcode_atts( array(
			'class' => '',
			'tag' => 'h1',
			'title' => ''
		), $atts ) );
		if($tag != 'h1' && $tag != 'h2' && $tag != 'h3' && $tag != 'h4' ) {
			$tag = 'h1';
		}
		ob_start();
		?>
			<div class="qt-special-title" <?php if(!wp_is_mobile()){ ?>data-100p-top="opacity:0;" data-70p-top="opacity:0;" data-40p-top="opacity:1;"<?php } ?>>
				<<?php echo esc_attr($tag); ?> class="qt-titdeco-eq <?php echo esc_attr($class); ?> "><?php echo esc_attr($title); ?></<?php echo esc_attr($tag); ?>>
			</div>
		<?php
		return ob_get_clean();
	}
}
add_shortcode( 'qt-special-titles', 'qt_special_titles' );