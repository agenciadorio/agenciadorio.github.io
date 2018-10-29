<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author        WooThemes
 * @package    WooCommerce/Templates
 * @version     2.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$wc_thememove_page_layout_private = get_post_meta( wc_get_page_id( 'shop' ), "thememove_page_layout_private", true );
$wc_thememove_bread_crumb_enable  = get_post_meta( wc_get_page_id( 'shop' ), "thememove_bread_crumb_enable", true );
$wc_thememove_heading_image       = get_post_meta( wc_get_page_id( 'shop' ), "thememove_heading_image", true );
$wc_thememove_sticky_header       = get_post_meta( wc_get_page_id( 'shop' ), "thememove_sticky_header", true );
$wc_thememove_uncover_enable      = get_post_meta( wc_get_page_id( 'shop' ), "thememove_uncover_enable", true );
$wc_thememove_uncover_enable      = get_post_meta( wc_get_page_id( 'shop' ), "thememove_uncover_enable", true );
$wc_thememove_header_top          = get_post_meta( wc_get_page_id( 'shop' ), "thememove_header_top", true );
if ( $wc_thememove_page_layout_private != 'default' ) {
	$layout = $wc_thememove_page_layout_private;
} else {
	$layout = get_theme_mod( 'site_layout', site_layout );
}
get_header( 'shop' ); ?>
<div class="content-wrapper">
	<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
		<header data-stellar-background-ratio="0.5" class="entry-header has-bg"
		        <?php if ( $wc_thememove_heading_image ){ ?>style="background-image: url('<?php echo $wc_thememove_heading_image; ?>')" <?php } ?>>
			<div class="container">
				<?php if ( get_post_meta( wc_get_page_id( 'shop' ), "thememove_alt_title", true ) ) { ?>
					<h1 class="entry-title">
						<?php echo get_post_meta( wc_get_page_id( 'shop' ), "thememove_alt_title", true ); ?>
					</h1>
				<?php } else { ?>
					<h1 class="entry-title">
						<?php woocommerce_page_title(); ?>
					</h1>
				<?php } ?>
				<?php if ( function_exists( 'tm_bread_crumb' ) && $wc_thememove_bread_crumb_enable != 'disable' ) { ?>
					<div class="breadcrumb">
						<div class="container">
							<?php echo tm_bread_crumb(); ?>
						</div>
					</div>
				<?php } ?>
			</div>
		</header>
	<?php endif; ?>
	<div class="container">
		<div class="row">
			<?php if ( $layout == 'sidebar-content' || $layout == 2 ) { ?>
				<?php do_action( 'woocommerce_sidebar' ); ?>
			<?php } ?>
			<?php if ( $layout == 'sidebar-content' || $layout == 'content-sidebar' || $layout == 2 || $layout == 1 ) { ?>
				<?php $class = 'col-md-9'; ?>
			<?php } else { ?>
				<?php $class = 'col-md-12'; ?>
			<?php } ?>
			<div class="<?php echo esc_attr( $class ); ?>">
				<?php do_action( 'woocommerce_before_main_content' ); ?>

				<?php do_action( 'woocommerce_archive_description' ); ?>

				<?php if ( have_posts() ) : ?>

					<?php do_action( 'woocommerce_before_shop_loop' ); ?>

					<?php woocommerce_product_loop_start(); ?>

					<?php woocommerce_product_subcategories(); ?>

					<?php while ( have_posts() ) : the_post(); ?>

						<?php wc_get_template_part( 'content', 'product' ); ?>

					<?php endwhile; // end of the loop. ?>

					<?php woocommerce_product_loop_end(); ?>

					<?php do_action( 'woocommerce_after_shop_loop' ); ?>

				<?php elseif ( ! woocommerce_product_subcategories( array(
					'before' => woocommerce_product_loop_start( false ),
					'after'  => woocommerce_product_loop_end( false )
				) )
				) : ?>

					<?php wc_get_template( 'loop/no-products-found.php' ); ?>

				<?php endif; ?>

				<?php do_action( 'woocommerce_after_main_content' ); ?>
			</div>
			<?php if ( $layout == 'content-sidebar' || $layout == 1 ) { ?>
				<?php do_action( 'woocommerce_sidebar' ); ?>
			<?php } ?>
		</div>
	</div>
</div>

<?php get_footer( 'shop' ); ?>
