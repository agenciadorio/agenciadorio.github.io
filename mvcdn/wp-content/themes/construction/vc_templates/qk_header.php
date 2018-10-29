<?php

extract(shortcode_atts(array(
	'title' => '',
	'sub_title' => '',
    'el_class' => '',
    'show_breadcrumb' => 0,
    'el_align' => '',
    'css' => ''

), $atts));

$class = array(
	vc_shortcode_custom_css_class( $css )
);
$class[] = empty($el_class) ? 'heading-page-section' : $el_class;
$class[] = $el_align;

?>

<!-- *********************
	INTRO PAGE TITLE
********************** -->
<section class="<?php echo esc_attr( implode(' ', $class ) );?>">
	<div class="container">
		<div class="col-md-12">
			<h1 class="heading-page-title">
				<?php
				if( ! empty( $title ) ){
					echo esc_attr( $title );
				}else{
					the_title();
				}
				?>
			</h1>
			<?php if( ! empty( $sub_title ) ):?>
			<span class="heading-page-subtitle"><?php echo esc_attr( $sub_title );?></span>
			<?php endif;?>
			<?php if( $show_breadcrumb ):?>
				<div class="woo-pagenation">
	                <?php echo construction_page_breadcrumb($delimiter); ?>
	            </div>
        	<?php endif;?>
		</div>
	</div>
</section>