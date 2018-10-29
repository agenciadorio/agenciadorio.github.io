<div class="jumbotron  jumbotron--<?php echo 'caption' === get_field( 'slider_content' ) ? 'with-captions' : 'no-catption'; ?>">
	<div class="carousel  slide  js-jumbotron-slider" id="headerCarousel" data-ride="carousel" <?php printf( 'data-interval="%s"', get_field( 'auto_cycle' ) ? get_field( 'cycle_interval' ) : 'false' ); ?>>

		<!-- Wrapper for slides -->
		<div class="carousel-inner">
			<?php
			$i = -1;
			while ( have_rows( 'slides' ) ) :
				the_row();
				$i++;

				$structurepress_slider_sizes = array( 'structurepress-jumbotron-slider-l', 'structurepress-jumbotron-slider-m', 'structurepress-jumbotron-slider-s' );

				$structurepress_slide_image_srcset = StructurePressHelpers::get_slide_sizes( get_sub_field( 'slide_image' ), $structurepress_slider_sizes );
				$structurepress_slide_link         = get_sub_field( 'slide_link' );

				$structurepress_slider_src_img = wp_get_attachment_image_src( absint( get_sub_field( 'slide_image' ) ), 'structurepress-jumbotron-slider-s' );
			?>

			<div class="carousel-item <?php echo 0 === $i ? ' active' : ''; ?>">
				<?php if ( ! empty( $structurepress_slide_link ) && 'link' === get_field( 'slider_content' ) ) :?>
					<a href="<?php echo esc_url( $structurepress_slide_link ); ?>" target="<?php echo ( get_sub_field( 'slide_open_link_in_new_window' ) ) ?  '_blank' : '_self' ?>">
				<?php endif; ?>
				<img src="<?php echo esc_url( $structurepress_slider_src_img[0] ); ?>" srcset="<?php echo esc_attr( $structurepress_slide_image_srcset ); ?>" sizes="100vw" alt="<?php echo esc_attr( get_sub_field( 'slide_title' ) ); ?>">
				<?php if ( ! empty( $structurepress_slide_link ) && 'link' === get_field( 'slider_content' ) ) :?>
					</a>
				<?php endif; ?>
				<?php if ( 'caption' === get_field( 'slider_content' ) ) : ?>
				<div class="container">
					<div class="jumbotron-content">
						<h1 class="jumbotron-content__title"><?php the_sub_field( 'slide_title' ); ?></h1>
						<div class="jumbotron-content__description">
							<?php the_sub_field( 'slide_text' ); ?>
						</div>
					</div>
				</div>
				<?php endif; ?>
			</div>

		<?php
			endwhile;
		?>
		</div>

		<div class="jumbotron__extras">
			<div class="container">
				<!-- Controls -->
				<a class="left  jumbotron__control" href="#headerCarousel" role="button" data-slide="prev">
					<i class="fa  fa-caret-left"></i>
				</a>
				<a class="right  jumbotron__control" href="#headerCarousel" role="button" data-slide="next">
					<i class="fa  fa-caret-right"></i>
				</a>
				<!-- Widget area -->
				<div class="jumbotron__widgets  hidden-md-down  pull-right">
					<?php
					if ( is_active_sidebar( 'slider-widgets' ) ) {
						dynamic_sidebar( 'slider-widgets' );
					}
					?>
				</div>
			</div>
		</div>

	</div>
</div>