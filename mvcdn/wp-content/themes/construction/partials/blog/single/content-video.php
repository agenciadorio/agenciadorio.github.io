<div class="blog-item">
	<?php $video = construction_get_meta( get_the_ID(), 'video'); if( ! empty( $video ) ):
		$height = (int) construction_get_meta( get_the_ID(), 'height');	
	?>
		<?php echo wp_oembed_get( esc_url( $video ), array('height'=>$height) );?>
	<?php elseif( has_post_thumbnail()):?>
		<div class="hover-img">
			<?php the_post_thumbnail();
		?>
		</div>
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
			<li><a href="<?php comments_link();?>"><i class="fa fa-comment"></i><?php comments_number( '0 comments', 'one comment', '% comments' ); ?></a></a></li>
		</ul>
		
		<div class="clearfix main-blog-content">
			<?php the_content(); ?>
			<?php
            $defaults = array(
              'before'           => '<div id="page-links"><strong>Page: </strong>',
              'after'            => '</div>',
              'link_before'      => '<span>',
              'link_after'       => '</span>',
              'next_or_number'   => 'number',
              'separator'        => ' ',
              'nextpagelink'     => esc_html__( 'Next page','construction' ),
              'previouspagelink' => esc_html__( 'Previous page','construction' ),
              'pagelink'         => '%',
              'echo'             => 1
            );
           ?>
          <?php wp_link_pages($defaults); ?>
		</div>
	</div>
</div>