<?php
/**
*
*   Theme: Sonik
*   File: qt-release-shortcode.php
*   Role: Embed a single release with tracklist with a shortcode
*   @author : QantumThemes <info@qantumthemes.com>
*
*
**/

$featuredplayer_used = false;
global $featuredplayer_used;
if(!function_exists('qt_release_shortcode')){
function qt_release_shortcode($atts){
	extract( shortcode_atts( array(
		'id' => '',
		'featuredplayer' => false,
		'hidefullcontent' => false
	), $atts ) );


	if(is_numeric($id)) {
		$args = array(
			'p' => $id, // id of a page, post, or custom type
			'post_type' => 'any'
		);
	} else {
		$args = array(
			'post_type' => 'release',
			'posts_per_page' => 1,
			'post_status' => 'publish',
			'orderby' => 'menu_order',
			'order'   => 'ASC',
			'suppress_filters' => false,
			'paged' => 1
	    );
	}

	ob_start();
	$wp_query = new WP_Query( $args );
	if ( $wp_query->have_posts() ) : while ( $wp_query->have_posts() ) : $wp_query->the_post();
		global $post;
		global $featuredplayer_used;
		setup_postdata( $post ); 
		$post->blockFeaturedPlayer = true; // negative condition to make a simple check in the tracklist for single release pages
		$post->isInShortcode = true;
		?>
		<article  <?php post_class ( "qt-content qt-release-content"); ?> id="page<?php the_ID(); ?>">
		<header class="qt-header">
			<?php 
			if( ( $featuredplayer ) &&  (1 != $featuredplayer_used) ){
				$featuredplayer_used = 1; // we can make it only once per page otherwise with the playlist it messes up
				$post->blockFeaturedPlayer = false;
				get_template_part('part','360player' ); 
			} else { ?>
				<hr class="qt-spacer-150">
			<?php } ?>
			<h1 class="qt-titdeco-eq text-center"><?php the_title(); ?></h1>
			<?php  if($hidefullcontent){ ?>
				<p class="text-center"><a class="btn qt-btn-ghost" data-expandable="#releaseplaylist<?php echo esc_attr($post->ID); ?>" href="#" ><?php echo esc_attr__("Info and tracklist", "qt-extensions-suite"); ?></a></p>
			<?php }  ?>
		</header>
		
		<?php 
		/**
		 * 
		 * 
		 * 	Release contents
		 * 
		 * 
		 * 
		 * */
		?>

		<?php  if($hidefullcontent){ ?>
			
			<div class="qt-contents-expandable" id="releaseplaylist<?php echo esc_attr($post->ID); ?>">
				<div class="qt-expandable-inner">
				<?php }  ?>
					<?php get_template_part('part','release-contents' ); ?>
				<?php if($hidefullcontent){ ?>
				</div>
			</div>
		<?php } ?>

		</article>
	<?php endwhile; else: ?>
   	 	<h3><?php echo esc_attr__("There is no release with the selected parameters","qt-extensions-suite")?></h3>
    <?php endif;
    wp_reset_postdata();
    return ob_get_clean();

}}

add_shortcode( 'qt-release', 'qt_release_shortcode' );

