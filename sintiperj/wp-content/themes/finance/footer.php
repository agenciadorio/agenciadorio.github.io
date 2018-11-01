</div>
<!-- site-main -->

<?php 
	$options = get_option('finance_framework');
    $finance_copyright_footer = $options['footer-text'];
?>
<!-- FOOTER -->
<footer id="footer" class="site-footer wrapper clearfix">
	<div class="footer-widget-areas clearfix">
		<div class="container">
			<div class="row">
				<!-- WIDGET FOOTER START
				============================================= -->

				<?php finance_footer_widget(); ?>

				<!-- WIDGET FOOTER END -->
			</div>
		</div>
	</div>

	<!-- COPYRIGHT -->
	<div id="copyright" class="foot-copyright clearfix">
		<div class="container">
			<div class="copyright-text row">
				<?php echo balancetags( $finance_copyright_footer ); ?>
			</div>

		</div>
	</div>
</footer>

</div>
<!-- MAIN WRAPPER END -->

<?php wp_footer(); ?>

</body>
</html>