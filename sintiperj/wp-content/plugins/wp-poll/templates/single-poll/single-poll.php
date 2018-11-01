<?php
/*
* @Author 		Jaed Mosharraf
* Copyright: 	2015 Jaed Mosharraf
*/

	get_header();
	do_action('wpp_action_before_single_poll');

	while ( have_posts() ) : the_post(); 
	$poll_ID = get_the_ID();
	?>
	<div itemscope itemtype="http://schema.org/Poll" id="poll-<?php the_ID(); ?>" <?php post_class("single-poll single-poll-$poll_ID entry-content"); ?>>
		
    <?php do_action('wpp_action_single_poll_main'); ?>

    </div>
	<?php
	endwhile;
		
    do_action('wpp_action_after_single_poll');

	// get_sidebar();
	get_footer();