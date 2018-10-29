<?php

$structurepress_blog_id      = absint( get_option( 'page_for_posts' ) );
$structurepress_style_attr   = '';
$structurepress_shop_id      = absint( get_option( 'woocommerce_shop_page_id', 0 ) );
$structurepress_portfolio_id = absint( get_theme_mod( 'portfolio_parent_page', 0 ) );

// custom bg
$structurepress_bg_id = get_the_ID();

if ( is_home() || is_singular( 'post' ) ) {
	$structurepress_bg_id = $structurepress_blog_id;
}

// woocommerce
if ( StructurePressHelpers::is_woocommerce_active() && is_woocommerce() ) {
	$structurepress_bg_id = $structurepress_shop_id;
}

// portfolio
if ( StructurePressHelpers::is_portfolio_plugin_active() && is_singular( 'portfolio' ) ) {
	$structurepress_bg_id = $structurepress_portfolio_id;
}

$show_title_area = get_field( 'show_title_area', $structurepress_bg_id );
if ( ! $show_title_area ) {
	$show_title_area = 'yes';
}

// show/hide page title area (ACF control - single page && customizer control - all pages)
if ( 'yes' === $show_title_area && 'yes' === get_theme_mod( 'show_page_title_area', 'yes' ) ) :

	$structurepress_style_array = array();

	if ( get_field( 'background_image', $structurepress_bg_id ) ) {
		$structurepress_style_array = array(
			'background-image'      => get_field( 'background_image', $structurepress_bg_id ),
			'background-position'   => get_field( 'background_image_horizontal_position', $structurepress_bg_id ) . ' ' . get_field( 'background_image_vertical_position', $structurepress_bg_id ),
			'background-repeat'     => get_field( 'background_image_repeat', $structurepress_bg_id ),
			'background-attachment' => get_field( 'background_image_attachment', $structurepress_bg_id ),
		);
	}

	$structurepress_style_array['background-color'] = get_field( 'background_color', $structurepress_bg_id );

	$structurepress_style_attr = StructurePressHelpers::create_background_style_attr( $structurepress_style_array );

	?>

	<div class="page-header" style="<?php echo esc_attr( $structurepress_style_attr ); ?>">
		<div class="container">
			<?php
			$structurepress_main_tag = 'h1';
			$structurepress_subtitle = false;

			if ( is_home() || ( is_single() && 'post' === get_post_type() ) ) {
				$structurepress_title    = get_the_title( $structurepress_blog_id );
				$structurepress_subtitle = get_field( 'subtitle', $structurepress_blog_id );

				if ( is_single() ) {
					$structurepress_main_tag = 'h2';
				}
			}
			elseif ( StructurePressHelpers::is_woocommerce_active() && is_woocommerce() ) {
				ob_start();
				woocommerce_page_title();
				$structurepress_title    = ob_get_clean();
				$structurepress_subtitle = get_field( 'subtitle', $structurepress_shop_id );

				if ( is_product() ) {
					$structurepress_main_tag = 'h2';
				}
			}
			elseif ( StructurePressHelpers::is_portfolio_plugin_active() && is_singular( 'portfolio' ) ) {
				$structurepress_title    = get_the_title( $structurepress_portfolio_id );
				$structurepress_subtitle = get_field( 'subtitle', $structurepress_portfolio_id );
			}
			elseif ( is_category() || is_tag() || is_author() || is_post_type_archive() || is_tax() || is_day() || is_month() || is_year() ) {
				$structurepress_title = get_the_archive_title();
			}
			elseif ( is_search() ) {
				$structurepress_title = esc_html__( 'Search Results For' , 'structurepress-pt' ) . ' &quot;' . get_search_query() . '&quot;';
			}
			elseif ( is_404() ) {
				$structurepress_title = esc_html__( 'Error 404' , 'structurepress-pt' );
			}
			else {
				$structurepress_title    = get_the_title();
				$structurepress_subtitle = get_field( 'subtitle' );
			}

			?>

			<?php printf( '<%1$s class="page-header__title  display-1">%2$s</%1$s>', tag_escape( $structurepress_main_tag ), esc_html( $structurepress_title ) ); ?>

			<?php if ( $structurepress_subtitle ) : ?>
				<p class="page-header__subtitle"><?php echo esc_html( $structurepress_subtitle ); ?></p>
			<?php endif; ?>
		</div>
	</div>
<?php endif; ?>