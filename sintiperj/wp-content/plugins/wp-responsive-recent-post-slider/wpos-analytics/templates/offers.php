<?php
/**
 * Offers Page
 *
 * @package Wpos Analytic
 * @since 1.0.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<style type="text/css">
	.notice, .error, div.fs-notice.updated, div.fs-notice.success, div.fs-notice.promotion{display:none !important;}
</style>

<div class="wrap wpos-anylc-offers">

	<?php foreach ($analy_product['offers'] as $offer_key => $offer_data) { 

		// If status wise offer is there
		if( wpos_anylc_is_multi_arr( $offer_data ) ) {
			$offer_data = isset( $offer_data[ $opt_in ] ) ? $offer_data[ $opt_in ] : current( $offer_data );
		}

		if( empty( $offer_data ) ) {
			continue;
		}

		$link 	= isset( $offer_data['link'] )		? $offer_data['link'] : '#';
		$image 	= !empty( $offer_data['image'] ) 	? add_query_arg( array('v' => time()), $offer_data['image'] ) : '';
	?>

		<div class="wpos-anylc-offer-wrap">
			<?php if( !empty( $offer_data['name'] ) ) { ?>
			<div class="wpos-anylc-offer-title wpos-anylc-center"><?php echo $offer_data['name']; ?></div>
			<?php } ?>

			<?php if( $image ) { ?>
			<div class="wpos-anylc-offer-body wpos-anylc-center">
				<a href="<?php echo esc_url( $link ); ?>" target="_blank">
					<img src="<?php echo esc_url( $image ); ?>" alt="" />
				</a>
			</div>
			<?php } ?>

			<?php if( !empty( $offer_data['desc'] ) ) { ?>
			<div class="wpos-anylc-offer-desc wpos-anylc-center"><?php echo wpautop( $offer_data['desc'] ); ?></div>
			<?php } ?>

			<?php if( !empty( $offer_data['button'] ) ) { ?>
			<div class="wpos-anylc-offer-footer wpos-anylc-center"><a href="<?php echo esc_url( $link ); ?>" class="button button-primary button-large wpos-anylc-btn" target="_blank"><?php echo $offer_data['button']; ?></a></div>
			<?php } ?>
		</div>

	<?php } ?>

</div><!-- end .wrap -->