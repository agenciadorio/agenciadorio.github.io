<?php

$structurepress_blog_id      = absint( get_option( 'page_for_posts' ) );
$structurepress_shop_id      = absint( get_option( 'woocommerce_shop_page_id', 0 ) );
$structurepress_portfolio_id = absint( get_theme_mod( 'portfolio_parent_page', 0 ) );

// custom bg
$structurepress_page_id = get_the_ID();

if ( is_home() || is_singular( 'post' ) ) {
	$structurepress_page_id = $structurepress_blog_id;
}

// woocommerce
if ( StructurePressHelpers::is_woocommerce_active() && is_woocommerce() ) {
	$structurepress_page_id = $structurepress_shop_id;
}

// portfolio
if ( StructurePressHelpers::is_portfolio_plugin_active() && is_singular( 'portfolio' ) ) {
	$structurepress_page_id = $structurepress_portfolio_id;
}

$show_breadcrumbs = get_field( 'show_breadcrumbs', $structurepress_page_id );

if ( ! $show_breadcrumbs ) {
	$show_breadcrumbs = 'yes';
}

?>

<?php if ( function_exists( 'bcn_display' ) && ( 'yes' === $show_breadcrumbs ) ) : ?>
	<div class="breadcrumbs">
		<div class="container">
			<?php bcn_display(); ?>
		</div>
	</div>
<?php endif; ?>