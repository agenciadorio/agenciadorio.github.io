<?php
/**
 * Settings Page
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

<div class="wrap wpos-anylc-optin">

	<?php if( isset($_GET['error']) && $_GET['error'] == 'wpos_anylc_error' ) { ?>
	<div class="error">
		<p><strong>Sorry, Something happened wrong. Please contact us on <a href="mailto:support@wponlinesupport.com">support@wponlinesupport.com</a></strong></p>
	</div>
	<?php } ?>

	<form method="POST" action="http://analytics.wponlinesupport.com">
		<div class="wpos-anylc-optin-wrap">
			<div class="wpos-anylc-optin-icon-wrap">
				<div class="wpos-anylc-optin-icon wpos-anylc-wp-badge"><i class="dashicons dashicons-wordpress"></i></div>
				<div class="wpos-anylc-optin-plus"><i class="dashicons dashicons-plus"></i></div>
				<div class="wpos-anylc-optin-icon"><img src="<?php echo $analy_product['icon']; ?>" alt="Icon" /></div>
				<div class="wpos-anylc-optin-plus"><i class="dashicons dashicons-plus"></i></div>
				<div class="wpos-anylc-optin-icon"><img src="<?php echo $analy_product['brand_icon']; ?>" alt="Icon" /></div>
			</div>
			<div class="wpos-anylc-optin-cnt">
				Hey <?php echo ucfirst($user_name); ?>,<br/>
				Please help us improve <b><?php echo $product_name; ?></b>! <br/>
				If you opt in, some data about your usage of <b><?php echo $product_name; ?></b> will be sent to <a target="_blank" href="https://www.wponlinesupport.com">wponlinesupport.com</a>. If you skip this, that's okay! <b><?php echo $product_name; ?></b> will still work just fine.

				<?php if( !empty( $analy_product['promotion'] ) ) { ?>
				<div class="wpos-anylc-promotion-wrap">
					<?php foreach( $analy_product['promotion'] as $promotion_key => $promotion_data ) { ?>
					<div><label><input type="checkbox" value="<?php echo $promotion_key; ?>" name="promotion[]" checked="checked" /> <?php echo $promotion_data['name']; ?></label></div>
					<?php } ?>
				</div>
				<?php } ?>
			</div>
			<div class="wpos-anylc-optin-action">
				<button type="submit" name="wpos_anylc_optin" class="button button-primary button-large wpos-anylc-allow-btn" value="wpos_anylc_optin">Allow and Continue</button>

				<?php if( is_null( $opt_in ) ) { ?>
				<a href="<?php echo esc_url( $skip_url ); ?>" class="button button-secondary button-large right wpos-anylc-skip-btn">Skip</a>
				<?php }

				if( !empty( $optin_form_data ) ) {
					foreach ($optin_form_data as $data_key => $data_value) {
						echo '<input type="hidden" name="'.esc_attr( $data_key ).'" value="'.esc_attr( $data_value ).'" />';
					}
				}
				?>
			</div>
			<div class="wpos-anylc-optin-permission">
				<a class="wpos-anylc-permission-toggle" href="javascript:void(0);">What permissions are being granted?</a>

				<div class="wpos-anylc-permission-wrap wpos-anylc-hide">
					<div class="wpos-anylc-permission">
						<i class="dashicons dashicons-admin-users"></i>
						<div>
							<span class="wpos-anylc-permission-name">Your Profile Overview</span>
							<span class="wpos-anylc-permission-info">Name and email address</span>
						</div>
					</div>
					<div class="wpos-anylc-permission">
						<i class="dashicons dashicons-admin-settings"></i>
						<div>
							<span class="wpos-anylc-permission-name">Your Site Overview</span>
							<span class="wpos-anylc-permission-info">Site URL, WP version, PHP info & Theme</span>
						</div>
					</div>
					<div class="wpos-anylc-permission">
						<i class="dashicons dashicons-admin-plugins"></i>
						<div>
							<span class="wpos-anylc-permission-name">Current Plugin Events</span>
							<span class="wpos-anylc-permission-info">Activation, Deactivation and Uninstall</span>
						</div>
					</div>
				</div>
			</div>
			<div class="wpos-anylc-terms">
				<a href="https://www.wponlinesupport.com/privacy-policy/" target="_blank">Privacy Policy</a> - <a href="https://www.wponlinesupport.com/term-and-condition/" target="_blank">Terms of Service</a>
			</div>
		</div>
	</form>
</div><!-- end .wrap -->