<?php
/**
 * Single Project Meta
 *
 * @author      WooThemes
 * @package     Projects/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

global $post;
?>
<div class="project-meta col-md-6">
	<?php echo __( '<h3 class="heading-title">Project Description</h3>', 'thememove' ); ?>
	<div class="project-meta__content">
		<?php
		// Categories
		$terms_as_text = get_the_term_list( $post->ID, 'project-category', '<li>', ',</li><li>', '</li>' );

		// Meta
		$client         = esc_attr( get_post_meta( $post->ID, '_client', true ) );
		$url            = esc_url( get_post_meta( $post->ID, '_url', true ) );
		$location       = esc_attr( get_post_meta( $post->ID, '_location', true ) );
		$surface_area   = esc_attr( get_post_meta( $post->ID, '_surface_area', true ) );
		$year_completed = esc_attr( get_post_meta( $post->ID, '_year_completed', true ) );
		$value          = esc_attr( get_post_meta( $post->ID, '_value', true ) );
		$architect      = esc_attr( get_post_meta( $post->ID, '_architect', true ) );

		do_action( 'projects_before_meta' );

		/**
		 * Display categories if they're set
		 */
		//  if ($terms_as_text) {
		//    echo '<div class="categories">';
		//    echo '<span class="categories__title meta-title">' . __('Categories', 'thememove') . ':</span>';
		//    echo '<ul class="single-project-categories">';
		//    echo $terms_as_text;
		//    echo '</ul>';
		//    echo '</div>';
		//  }

		/**
		 * Display client if set
		 */
		if ( $client ) {
			echo '<div class="client">';
			echo '<span class="client__title meta-title">' . __( 'Client', 'thememove' ) . ':</span>';
			echo '<span class="client__name">' . $client . '</span>';
			echo '</div>';
		}

		if ( $location ) {
			echo '<div class="location">';
			echo '<span class="location__title meta-title">' . __( 'Location', 'thememove' ) . ':</span>';
			echo '<span class="location__name">' . $location . '</span>';
			echo '</div>';
		}

		if ( $surface_area ) {
			echo '<div class="surface-area">';
			echo '<span class="surface-area__title meta-title">' . __( 'Surface Area', 'thememove' ) . ':</span>';
			echo '<span class="surface-area__name">' . $surface_area . '</span>';
			echo '</div>';
		}

		if ( $year_completed ) {
			echo '<div class="year-complete">';
			echo '<span class="year-complete__title meta-title">' . __( 'Year Completed', 'thememove' ) . ':</span>';
			echo '<span class="year-complete__name">' . $year_completed . '</span>';
			echo '</div>';
		}

		if ( $value ) {
			echo '<div class="architect">';
			echo '<span class="architect__title meta-title">' . __( 'Value', 'thememove' ) . ':</span>';
			echo '<span class="architect__name">' . $value . '</span>';
			echo '</div>';
		}

		if ( $architect ) {
			echo '<div class="architect">';
			echo '<span class="architect__title meta-title">' . __( 'Architect', 'thememove' ) . ':</span>';
			echo '<span class="architect__name">' . $architect . '</span>';
			echo '</div>';
		}
		/**
		 * Display link if set
		 */
		if ( $url ) {
			echo '<div class="url">';
			echo '<span class="architect__title meta-title">' . __( 'Link:', 'thememove' ) . '</span>';
			echo '<span class="project-url"><a href="' . $url . '">' . apply_filters( 'projects_visit_project_link', __( 'Visit project', 'thememove' ) ) . '</a></span>';
			echo '</div>';
		}

		do_action( 'projects_after_meta' );
		?>
	</div>
</div>