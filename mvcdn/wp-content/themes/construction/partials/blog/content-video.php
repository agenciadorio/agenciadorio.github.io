<div <?php post_class('blog-item'); ?>>
	<?php $video = construction_get_meta( get_the_ID(), 'video'); if( ! empty( $video ) ):
		$height = (int) construction_get_meta( get_the_ID(), 'height');
		$size = construction_get_image_size( $thumb_size );
		if( $height ){
			$height = $height > $size[1] ? $size[1] : $height;
		}else{
			$height = $size[1];
		}
	?>
		<a class="video-media-type" data-origin='<?php echo json_encode( $size );?>' href="<?php the_permalink();?>">
			<?php echo wp_oembed_get( esc_url( $video ), array('height'=>$height) );?>
		</a>
	<?php elseif( has_post_thumbnail()):?>
		<a href="<?php the_permalink(); ?>" class="hover-img">
			<?php the_post_thumbnail();
		?>
		</a>
	<?php endif;?>
	<div class="blog-caption">
		<ul class="blog-date blog-date-left">
			<?php
				$archive_year  = get_the_time('Y');
				$archive_month = get_the_time('m');
				$archive_day = get_the_time('d');
				?>
			<li><a href="<?php echo get_day_link( $archive_year, $archive_month, $archive_day ); ?>"><i class="fa fa-calendar"></i><?php the_time(get_option( 'date_format' )); ?>
				</a></li>
			<li><a href="<?php comments_link();?>"><i class="fa fa-comment"></i><?php comments_number( '0 comments', 'one comment', '% comments' ); ?></a></li>
		</ul>
		<h2 class="blog-heading"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<p><?php echo construction_excerpt( $excerpt_length ); ?></p>
		<?php if( $show_readmore ):?>
			<a href="<?php the_permalink(); ?>" class="btn btn-1 btn-bg-1 btn-sm">
				<?php echo esc_attr( $readmore_text ); ?>
			</a>
		<?php endif; ?>
	</div>	
</div>