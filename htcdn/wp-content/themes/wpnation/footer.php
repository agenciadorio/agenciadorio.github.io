<?php 

	//Extracting the values that user defined in OptionTree Plugin 
	$footerCopyright = ot_get_option('copyright_text');

?>
	
	<!-- BEGIN FOOTER -->
	<footer>
		<div id="footer-wrap">
			<div class="container">
				<div id="prefooter-wrap">
					
					<!-- BEGIN FIRST FOOTER COLUMN -->
					<div class="five columns">
						<?php
						if ( !dynamic_sidebar( 'footer_section1' ) ) echo "&nbsp;"				
						?>	
					</div>
					<!-- END FIRST FOOTER COLUMN -->
					
					<!-- BEGIN SECOND FOOTER COLUMN -->
					<div class="five columns">
						<?php
						if ( !dynamic_sidebar( 'footer_section2' ) ) echo "&nbsp;"	
						?>	
					</div>
					<!-- END SECOND FOOTER COLUMN -->
					
					<!-- BEGIN THIRD FOOTER COLUMN -->
					<div class="five columns offset-by-one">
						<?php  
						if ( !dynamic_sidebar( 'footer_section3' ) ) echo "&nbsp;"	
						?>
					</div>
					<!-- END THIRD FOOTER COLUMN -->
					
					<div style="clear:both"></div>
					
					<!-- BEFIN COPYRIGHT INFO -->
					<div id="copyright-wrap">
						<div id="copyright-text"><?php echo $footerCopyright; ?></div>
						<div id="copyright-links"><?php wp_nav_menu( array(
							'menu' => 'Footer menu',
							'theme_location' => 'footer_menu',
							'container' => false,
							'container'       => false,
							'items_wrap'      => '%3$s',
							'depth'           => 0,
							'after' => '<span class="footer_menu_divider">&nbsp;&nbsp;&#47;&nbsp;&nbsp;</span>',
							'walker' => new footer_walker()
					)) ?></div>
					</div>
					<!-- END COPYRIGHT INFO -->
				
				</div>
			</div>
		</div>
	</footer>
	<!-- END FOOTER -->
	
	</div>
	<a href="#to-top" id="back-to-top"><span class="icon-chevron-up"></span></a>
	
	<?php wp_footer(); ?>
	
  </body>
</html>