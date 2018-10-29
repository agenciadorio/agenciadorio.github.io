<?php
$output = $layout = $el_class = '';

extract( shortcode_atts( array(
	'layout'   => '',
	'el_class' => '',
), $atts ) );

global $post;

// Categories
$categories = get_the_term_list( $post->ID, 'project-category', '<li>', ',</li><li>', '</li>' );

// Meta
$client         = esc_attr( get_post_meta( $post->ID, '_client', true ) );
$url            = esc_url( get_post_meta( $post->ID, '_url', true ) );
$location       = esc_attr( get_post_meta( $post->ID, '_location', true ) );
$surface_area   = esc_attr( get_post_meta( $post->ID, '_surface_area', true ) );
$year_completed = esc_attr( get_post_meta( $post->ID, '_year_completed', true ) );
$value          = esc_attr( get_post_meta( $post->ID, '_value', true ) );
$architect      = esc_attr( get_post_meta( $post->ID, '_architect', true ) );
$el_class       = $this->getExtraClass( $el_class );

?>

<div class="tm_project_details">
	<?php if ( $post->post_type != 'project' ) { ?>
		<div class="tm_project_details__warning">
			Project Details Shortcode only available for "Projects" content type.
		</div>
	<?php } else { ?>
		<table class="tm_project_details--<?php echo $layout; ?>">
			<?php if ( $categories ) { ?>
				<tr>
					<td class="meta-title"><?php echo __( 'Categories', 'thememove' ); ?>:</td>
					<td>
						<ul class="single-project-categories"><?php echo $categories; ?></ul>
					</td>
				</tr>
			<?php } ?>
			<?php if ( $client ) { ?>
				<tr>
					<td class="meta-title"><?php echo __( 'Client', 'thememove' ); ?>:</td>
					<td class="client__name"><?php echo $client; ?></td>
				</tr>
			<?php } ?>
			<?php if ( $location ) { ?>
				<tr>
					<td class="meta-title"><?php echo __( 'Location', 'thememove' ); ?>:</td>
					<td class="location__name"><?php echo $location; ?></td>
				</tr>
			<?php } ?>
			<?php if ( $surface_area ) { ?>
				<tr>
					<td class="meta-title"><?php echo __( 'Surface Area', 'thememove' ); ?>:</td>
					<td class="surface-area__name"><?php echo $surface_area; ?></td>
				</tr>
			<?php } ?>
			<?php if ( $year_completed ) { ?>
				<tr>
					<td class="meta-title"><?php echo __( 'Completed', 'thememove' ); ?>:</td>
					<td class="year-complete__name"><?php echo $year_completed; ?></td>
				</tr>
			<?php } ?>
			<?php if ( $value ) { ?>
				<tr>
					<td class="meta-title"><?php echo __( 'Value', 'thememove' ); ?>:</td>
					<td class="project-value"><?php echo $value; ?></td>
				</tr>
			<?php } ?>
			<?php if ( $architect ) { ?>
				<tr>
					<td class="meta-title"><?php echo __( 'Architect', 'thememove' ); ?>:</td>
					<td class="architect__name"><?php echo $architect; ?></td>
				</tr>
			<?php } ?>
			<?php if ( $url ) { ?>
				<tr>
					<td class="meta-title"><?php echo __( 'Link', 'thememove' ); ?>:</td>
					<td class="project-url"><a
							href="<?php echo $url; ?>"><?php echo apply_filters( 'projects_visit_project_link', __( 'Visit project', 'thememove' ) ); ?></a>
					</td>
				</tr>
			<?php } ?>
		</table>
	<?php } ?>
</div>