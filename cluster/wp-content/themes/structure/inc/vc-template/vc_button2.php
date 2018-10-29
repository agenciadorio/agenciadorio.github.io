<?php
$wrapper_start = $wrapper_end = '';
extract( shortcode_atts( array(
	'link'                   => '',
	'title'                  => __( 'Text on the button', "js_composer" ),
	'color'                  => '',
	'icon'                   => '',
	'size'                   => '',
	'style'                  => '',
	'el_class'               => '',
	'align'                  => '',
	'background_color'       => '',
	'text_color'             => '',
	'border_color'           => '',
	'background_color_hover' => '',
	'text_color_hover'       => '',
	'border_color_hover'     => ''
), $atts ) );

$class = 'vc_btn';
//parse link
$link     = ( $link == '||' ) ? '' : $link;
$link     = vc_build_link( $link );
$a_href   = $link['url'];
$a_title  = $link['title'];
$a_target = $link['target'];

$class .= ( $color != '' ) ? ( ' vc_btn_' . $color . ' vc_btn-' . $color ) : '';
$class .= ( $size != '' ) ? ( ' vc_btn_' . $size . ' vc_btn-' . $size ) : '';
$class .= ( $style != '' ) ? ' vc_btn_' . $style : '';

$el_class          = $this->getExtraClass( $el_class );
$css_class         = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, ' ' . $class . $el_class, $this->settings['base'], $atts );
$wrapper_css_class = 'vc_button-2-wrapper';
if ( $align ) {
	$wrapper_css_class .= ' vc_button-2-align-' . $align;
}
?>
	<div class="<?php echo esc_attr( $wrapper_css_class ) ?>">
		<a class="<?php echo esc_attr( trim( $css_class ) ); ?>"
		   onMouseOver="this.style.backgroundColor='<?php echo esc_js( $background_color_hover ); ?>',this.style.borderColor='<?php echo esc_js( $border_color_hover ); ?>',this.style.color='<?php echo esc_js( $text_color_hover ); ?>'"
		   onMouseOut="this.style.backgroundColor='<?php echo esc_js( $background_color ); ?>',this.style.borderColor='<?php echo esc_js( $border_color ); ?>',this.style.color='<?php echo esc_js( $text_color ); ?>'"
		   style="<?php if ( $background_color ) {
			   echo 'background-color:' . $background_color . ';';
		   };
		   if ( $text_color ) {
			   echo 'color:' . $text_color . ';';
		   };
		   if ( $border_color ) {
			   echo 'border:2px solid ' . $border_color;
		   } ?>"
		   <?php if ( $a_href ) { ?>href="<?php echo esc_url( $a_href ); ?>" <?php } ?>
		   <?php if ( $a_title ) { ?>title="<?php echo esc_attr( $a_title ); ?>" <?php } ?>
		   <?php if ( $a_target ) { ?>target="<?php echo esc_attr( $a_target ); ?>" <?php } ?> >
			<?php echo $title; ?>
		</a>
	</div>
<?php echo $this->endBlockComment( 'vc_button' ) . "\n";