<?php
/**
 * Footer
 *
 * @package StructurePress
 */

$structurepress_footer_widgets_layout = StructurePressHelpers::footer_widgets_layout_array();
$structurepress_footer_allowed_html = array(
	'a'      => array(
		'href'   => array(),
		'target' => array(),
		'title'  => array(),
	),
	'em'     => array(),
	'strong' => array(),
	'img'    => array(
		'src'    => array(),
		'alt'    => array(),
		'width'  => array(),
		'height' => array(),
	),
	'span'   => array(
		'class'  => array(),
	),
	'i'      => array(
		'class'  => array(),
	),
);

?>

	<footer class="footer">
		<?php if ( ! empty( $structurepress_footer_widgets_layout ) && is_active_sidebar( 'footer-widgets' ) ) : ?>
		<div class="footer-top">
			<div class="container">
				<div class="row">
					<?php
					if ( is_active_sidebar( 'footer-widgets' ) ) {
						dynamic_sidebar( 'footer-widgets' );
					}
					?>
				</div>
			</div>
		</div>
		<?php endif; ?>
		<div class="footer-middle">
			<?php echo wp_kses( apply_filters( 'structurepress_footer_center_txt', get_theme_mod( 'footer_center_txt', '<i class="fa  fa-2x  fa-cc-visa"></i> &nbsp; <i class="fa  fa-2x  fa-cc-mastercard"></i> &nbsp; <i class="fa  fa-2x  fa-cc-amex"></i> &nbsp; <i class="fa  fa-2x  fa-cc-paypal"></i>' ) ), $structurepress_footer_allowed_html ); ?>
		</div>
		<div class="footer-bottom">
			<div class="container">
				<div class="footer-bottom__left">
					<?php echo wp_kses( apply_filters( 'structurepress_footer_left_txt', get_theme_mod( 'footer_left_txt', '<a href="https://www.proteusthemes.com/wordpress-themes/structurepress/">StructurePress Theme</a> Made by ProteusThemes.' ) ), $structurepress_footer_allowed_html ); ?>
				</div>
				<div class="footer-bottom__right">
					<?php echo wp_kses( apply_filters( 'structurepress_footer_right_txt', get_theme_mod( 'footer_right_txt', '&copy; 2009-2015 StructurePress. All rights reserved.' ) ), $structurepress_footer_allowed_html ); ?>
				</div>
			</div>
		</div>
	</footer>
	</div><!-- end of .boxed-container -->

	<?php wp_footer(); ?>
	</body>
</html>