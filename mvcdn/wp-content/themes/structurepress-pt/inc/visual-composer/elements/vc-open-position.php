<?php

/**
 * Open Position content element for the Visual Composer editor
 */

if ( ! class_exists( 'PT_VC_Open_Position' ) ) {
	class PT_VC_Open_Position extends PT_VC_Shortcode {

		// Basic shortcode settings
		function shortcode_name() { return 'pt_vc_open_position'; }

		// Initialize the shortcode by calling the parent constructor
		public function __construct() {
			parent::__construct();
		}

		// Overwrite the register_shortcode function from the parent class
		public function register_shortcode( $atts, $content = null ) {
			$atts = shortcode_atts( array(
				'title'         => '',
				'date'          => '',
				'details_title' => '',
				'detail_items'  => '',
				), $atts );

			// Prepare detail items for the Open Position widget
			$lines        = explode( PHP_EOL , $atts['detail_items'] );
			$detail_items = array();

			foreach ( $lines as $line ) {
				$split_line = explode( '|', $line );
				if ( isset( $split_line[1] ) ) {
					$tmp_array  = array(
						'text' => wp_strip_all_tags( $split_line[0] ),
						'icon' => wp_strip_all_tags( $split_line[1] ),
					);
					$detail_items[] = $tmp_array;
				}
			}

			$instance = array(
				'title'         => $atts['title'],
				'date'          => $atts['date'],
				'details_title' => $atts['details_title'],
				'detail_items'  => $detail_items,
				'content'       => $content,
			);

			$args['widget_id'] = uniqid( 'widget-' );

			// widget front-end template, because the original Open Position widget is built with the SiteOrigin widget bundle framework
			$read_more = strpos( $instance['content'], 'span id="more-' );
			if ( false === $read_more ) {
				$read_more = strpos( $instance['content'], '<!--more-->' );
			}
		?>

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
							echo wp_kses_post( preg_replace( '/((?:<p>)?<span id="more-(?:.+)"><\/span>(?:<\/p>)?)|((?:<p>)?<!--more-->(?:<\/p>)?)/', '<div class="collapse" id="collapse-' . esc_attr( $args['widget_id'] ) . '">', $instance['content'], 1 ) );
						?>
							</div>
							<p>
							<a class="read-more" data-toggle="collapse" id="collapse-link-<?php echo esc_attr( $args['widget_id'] ); ?>" href="#collapse-<?php echo $args['widget_id']; ?>" aria-expanded="false" aria-controls="collapse-<?php echo esc_attr( $args['widget_id'] ); ?>"><i class="fa fa-plus"></i> <?php esc_html_e( 'Read more', 'structurepress-pt' ); ?></a>
							</p>
						<?php } ?>
					</div>
				</div>
				<div class="open-position__details">
					<h4 class="open-position__details-title"><?php echo esc_html( $instance['details_title'] ); ?></h4>
					<?php foreach ( $instance['detail_items'] as $item ) : ?>
						<div class="open-position__details-item">
							<span class="open-position__details-item-icon"><i class="fa <?php echo esc_attr( $item['icon'] ); ?>"></i></span><span class="open-position__details-item-text"><?php echo esc_html( $item['text'] ); ?></span>
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

		<?php
		}

		// Overwrite the vc_map_shortcode function from the parent class
		public function vc_map_shortcode() {
			vc_map( array(
				'name'     => _x( 'Open Position', 'backend', 'structurepress-pt' ),
				'base'     => $this->shortcode_name(),
				'category' => _x( 'Content', 'backend', 'structurepress-pt' ),
				'icon'     => get_template_directory_uri() . '/vendor/proteusthemes/visual-composer-elements/assets/images/pt.svg',
				'params'   => array(
					array(
						'type'       => 'textfield',
						'holder'     => 'div',
						'heading'    => _x( 'Title', 'backend', 'structurepress-pt' ),
						'param_name' => 'title',
					),
					array(
						'type'       => 'textfield',
						'heading'    => _x( 'Date', 'backend', 'structurepress-pt' ),
						'param_name' => 'date',
					),
					array(
						'type'        => 'textarea_html',
						'class'       => '',
						'heading'     => _x( 'Content', 'backend', 'structurepress-pt' ),
						'param_name'  => 'content',
					),
					array(
						'type'       => 'textfield',
						'heading'    => _x( 'Details Title', 'backend', 'structurepress-pt' ),
						'param_name' => 'details_title',
					),
					array(
						'type'        => 'lined_textarea',
						'heading'     => _x( 'Detail Items', 'backend', 'structurepress-pt' ),
						'description' => _x( 'Enter values for detail items - <em>item text</em>|<em>font awesome icon class name</em>. Divide value sets with linebreak "Enter" (Example: 227 Marion Street Columbia SC 2921|fa-home).', 'backend', 'structurepress-pt' ),
						'param_name'  => 'detail_items',
						'rows'        => '5',
					),
				)
			) );
		}
	}

	// Initialize the class
	new PT_VC_Open_Position;
}