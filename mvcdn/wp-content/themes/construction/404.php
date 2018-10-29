<?php
/*
*Template Name: 404
*/
	get_header();
	$p_error = construction_get_option('404-page');
?>
<!-- Error_404 -->
<div class="error_404 p-t-60 p-b-60">
	<div class="container">
		<div class="error404">
			<?php if( $p_error ):?>
				<?php echo apply_filters( 'the_content', get_post_field( 'post_content', $p_error ) ); ?>
			<?php else:?>
				<h3><?php esc_html_e('PAGE NOT FOUND!','construction');?></h3>
				<p><?php esc_html_e('Page doesn\'t exist or some other error occured nd is not esixt.','construction');?><br/> <?php echo wp_kses( sprintf( esc_html__('Go to our <a href="%s">home page</a>','construction'),esc_url(home_url('/'))), array('a'=>array('href'=>array())) );?></p>
			<?php endif;?>
		</div>
	</div>
</div>
<?php get_footer(); ?>