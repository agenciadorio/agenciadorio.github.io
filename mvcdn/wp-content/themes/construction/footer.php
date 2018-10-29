	    <!-- ================================= -->
	    <!-- ========== END FOOTER  ========== -->
	    <!-- ================================ --> 
	    <!-- Newsletter -->
	    <?php if( construction_get_object_option('show-newsletter', true) ):?>
		<div class="block-fv">
			<div class="container">
				<div class="fv newsletter">
					<div class="row">
							<?php
						$phone_widget = construction_get_prefix('phone-sidebar');
						if( is_active_sidebar( $phone_widget ) ){
						?>
						<div class="col-md-4">
							<?php dynamic_sidebar( $phone_widget );?>
						</div>
						<div class="col-md-8">
							<?php }else{ ?>
							<div class="col-xs-12">
								<?php }
								$newsletter_widget = construction_get_prefix('newsletter-sidebar');
								if( is_active_sidebar( $newsletter_widget ) ){ 
								?>
									<div class="form-inline">
										<div class="row">
											<?php dynamic_sidebar( $newsletter_widget );?>	
										</div>
									</div>
								<?php } ?>
							</div>
						
					</div>
				</div>
			</div>
		</div>
		<?php endif;?>
		<footer class="wrap-footer color-w">
				<?php if( construction_get_option('footer-widgets')): ?>
					<div class="footer-top">
						<div class="container">
							<div class="row">
								<?php
								// About Widget
								$ftwd1 = construction_get_prefix('footer-widget1' );
								if( is_active_sidebar( $ftwd1 ) ):?>
									<div class="footer-widget footer-widget-1 col-xs-12 col-sm-6 col-md-4">
										<?php dynamic_sidebar( $ftwd1 ); ?>
									</div>
								<?php endif; ?>
								<?php
								// Tags Widget
								$ftwd2 = construction_get_prefix('footer-widget2' );
								if( is_active_sidebar( $ftwd2 ) ):?>
									<div class="footer-widget footer-widget-2 col-xs-12 col-sm-6 col-md-3">
										<?php dynamic_sidebar( $ftwd2 ); ?>
									</div>
								<?php endif; ?>
								<?php
								// Quicklink Widget
								$ftwd3 = construction_get_prefix('footer-widget3' );
								if( is_active_sidebar( $ftwd3 ) ):?>
									<div class="footer-widget footer-widget-3 col-xs-12 col-sm-6 col-md-3">
										<?php dynamic_sidebar( $ftwd3 ); ?>
									</div>
								<?php endif; ?>
								<?php
								// Quicklink Widget
								$ftwd4 = construction_get_prefix('footer-widget4' );
								if( is_active_sidebar( $ftwd4 ) ):?>
									<div class="footer-widget footer-widget-4 col-xs-12 col-sm-6 col-md-2">
										<?php dynamic_sidebar( $ftwd4 ); ?>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				<?php endif; ?>
				<div class="footer-bt">
					<div class="container">
						<?php $footer_text = construction_get_option('copy_right');  ?>
						<?php echo wp_kses_post($footer_text); ?>
					</div>
				</div>
			</footer>
		</div>
		<?php
		wp_footer(); ?>
	
</body>
</html>