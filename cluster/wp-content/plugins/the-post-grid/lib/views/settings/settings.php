<?php
global $rtTPG;
?>

<div class="wrap">
	<div class="width50">
		<div id="upf-icon-edit-pages" class="icon32 icon32-posts-page"><br/></div>
		<h2><?php _e( 'The Post Grid Settings', 'the-post-grid' ); ?></h2>
		<h3><?php _e( 'General settings', 'the-post-grid' ); ?>
			<a style="margin-left: 15px; font-size: 15px;"
			   href="http://demo.radiustheme.com/wordpress/plugins/the-post-grid/"
			   target="_blank"><?php _e( 'Documentation', 'the-post-grid' ) ?></a>
		</h3>

		<div class="rt-setting-wrapper">
			<div class="rt-response"></div>
			<form id="rt-settings-form" onsubmit="rtTPGSettings(this); return false;">
				<div class="rt-setting-holder">
					<?php echo $rtTPG->rtFieldGenerator( $rtTPG->rtTPGSettingFields(), true ); ?>
				</div>

				<p class="submit"><input type="submit" name="submit" class="button button-primary rtSaveButton"
				                         value="Save Changes"></p>

				<?php wp_nonce_field( $rtTPG->nonceText(), $rtTPG->nonceId() ); ?>
			</form>

			<div class="rt-response"></div>
		</div>
	</div>
	<div class="width50">
		<div class="pro-features">
			<h3>PRO Version Features</h3>
			<?php echo $rtTPG->get_pro_feature_list(); ?>
			<p><a href="https://www.radiustheme.com/the-post-grid-pro-for-wordpress/" class="button-link"
			      target="_blank">Get Pro Version</a></p>
		</div>
	</div>

</div>
