<?php
/**
 * The Template for displaying all single products.
 *
 * Override this template by copying it to yourtheme/woocommerce/single-product.php
 *
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' ); ?>
<div class="content-wrapper">
	<div data-stellar-background-ratio="0.5" class="entry-header has-bg">
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
			<?php if ( function_exists( 'tm_bread_crumb' ) ) { ?>
				<div class="breadcrumb">
					<div class="container">
						<?php echo tm_bread_crumb(); ?>
					</div>
				</div><!-- .breadcrumb -->
			<?php } ?>
		</div>
	</div>
	<!-- .entry-header -->

	<div class="container">
		<div class="row">
			<div class="col-md-9">
				<?php do_action( 'woocommerce_before_main_content' ); ?>
				<?php while ( have_posts() ) : the_post(); ?>
					<?php wc_get_template_part( 'content', 'single-product' ); ?>
				<?php endwhile; // end of the loop. ?>
				<?php do_action( 'woocommerce_after_main_content' ); ?>
			</div>
			<?php do_action( 'woocommerce_sidebar' ); ?>
		</div>
	</div>
</div>

<?php get_footer( 'shop' ); ?>
