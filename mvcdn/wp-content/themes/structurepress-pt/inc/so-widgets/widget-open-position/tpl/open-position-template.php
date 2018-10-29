<?php
	// Search for read-more tag
	$read_more = strpos( $instance['content'], '<!--more-->' );
?>

<?php echo $args['before_widget']; ?>
	<div class="open-position">
		<div class="open-position__content-container">
			<?php if ( ! empty( $instance['title'] ) ) : ?>
				<h3 class="open-position__title">
					<?php echo esc_html( apply_filters( 'widget_title', $instance['title'], $instance ) ); ?>
				</h3>
			<?php endif; ?>
			<div class="open-position__date">
				<?php echo esc_html( $instance['date'] ); ?>
			</div>
			<div class="open-position__content">
				<?php
				// Display the full content if the read-more tag was not found.
				if ( false === $read_more ) {
					echo wp_kses_post( $instance['content'] );
				}
				else {
					echo wp_kses_post( preg_replace( '/((?:<p>)?<!--more-->(?:<\/p>)?)/', '<div class="collapse" id="collapse-' . esc_attr( $args['widget_id'] ) . '">', $instance['content'], 1 ) );
				?>
					</div>
					<p>
					<a class="read-more" data-toggle="collapse" id="collapse-link-<?php echo esc_attr( $args['widget_id'] ); ?>" href="#collapse-<?php echo esc_attr( $args['widget_id'] ); ?>" aria-expanded="false" aria-controls="collapse-<?php echo esc_attr( $args['widget_id'] ); ?>"><i class="fa fa-plus"></i> <?php esc_html_e( 'Read more', 'structurepress-pt' ); ?></a>
					</p>
				<?php } ?>
			</div>
		</div>
		<div class="open-position__details">
			<h4 class="open-position__details-title"><?php echo esc_html( $instance['details_title'] ); ?></h4>
			<?php foreach ( $instance['detail_items'] as $item ) : ?>
				<div class="open-position__details-item">
					<span class="open-position__details-item-icon"><?php echo siteorigin_widget_get_icon( $item['icon'] ); ?></span><span class="open-position__details-item-text"><?php echo wp_kses_post( $item['text'] ); ?></span>
				</div>
			<?php endforeach; ?>
		</div>
	</div>

	<script type="text/javascript">
		jQuery( '#collapse-<?php echo esc_attr( $args["widget_id"] ); ?>' ).on( 'shown.bs.collapse' , function() {
			jQuery( '#collapse-link-<?php echo esc_attr( $args["widget_id"] ); ?>' ).html( '<i class="fa fa-minus"></i> <?php esc_html_e( "Close", "structurepress-pt" ); ?>' );
		});
		jQuery( '#collapse-<?php echo esc_attr( $args["widget_id"] ); ?>' ).on( 'hidden.bs.collapse', function() {
			jQuery( '#collapse-link-<?php echo esc_attr( $args["widget_id"] ); ?>' ).html( '<i class="fa fa-plus"></i> <?php esc_html_e( "Read more", "structurepress-pt" ); ?>' );
		});
	</script>

<?php echo $args['after_widget']; ?>