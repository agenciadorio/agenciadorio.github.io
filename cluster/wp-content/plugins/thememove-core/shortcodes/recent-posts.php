<?php
/* Recent Posts shortcode */
if ( ! function_exists( 'recent_posts' ) ) {

	function recent_posts( $atts, $content = null ) {
		$default_args = array(
			"number"           => "-1",
			"feature_image"    => "",
			"show_description" => "",
		);

		extract( shortcode_atts( $default_args, $atts ) );

		$args = array(
			'posts_per_page' => $number
		);
		$html = "";
		$html .= '<div class="recent-posts">';
		query_posts( $args );
		if ( have_posts() ) :
			while ( have_posts() ) : the_post();
				$recent_post_feature_image = wp_get_attachment_image_src( get_post_thumbnail_id(), "small-thumb" );

				$html .= '<div class="recent-posts__item" id="recent-pots-' . get_the_ID() . '" >';

				if ( $feature_image == "yes" && $recent_post_feature_image ) {
					$html .= '<a class="recent-posts__thumb" href="' . get_permalink() . '"><img src="' . $recent_post_feature_image[0] . '" alt="" width="120" height="90" /></a>';
				}

				$html .= '<a href="' . get_permalink() . '">' . get_the_title() . '</a>';

				$html .= '<p class="date">' . get_the_date() . '</p>';

				if ( $show_description == "yes" ) {
					$html .= '<p class="recent_post__content"> ' . get_the_excerpt() . '</p>';
				}

				$html .= '</div>';

			endwhile;
		else:
			$html .= __( 'Sorry, no posts matched your criteria.', 'thememove' );
		endif;

		wp_reset_query();
		$html .= '</div>';

		return $html;
	}

}
add_shortcode( 'recent_posts', 'recent_posts' );
