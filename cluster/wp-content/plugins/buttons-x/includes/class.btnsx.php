<?php
/**
 * Button X
 *
 * This file is used to register main functionality of the plugin.
 *
 * @package Buttons X
 * @since 0.1
 */
// Make sure we don't expose any info if called directly
if ( !defined( 'ABSPATH' ) )
	exit;

if( !class_exists( 'Btnsx' ) ) {

	class Btnsx {

		private static $instance;

		/**
		 * Initiator
		 * @since 0.1
		 */
		public static function init(){
			return self::$instance;
		}

		/**
		 * Constructor
		 * @since 0.1
		 */
		public function __construct() {
			add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
			global $wp_version, $btnsx_settings;
			// execute code if minimum wp version is 4 n above else throw notice
			if ( $wp_version >= BTNSX__MIN_WP_VERSION ) {
				require_once( BTNSX__PLUGIN_DIR . 'includes/class.btnsx.form.design.php' );
				require_once( BTNSX__PLUGIN_DIR . 'includes/class.btnsx.form.php' );
				// admin functionality
				add_filter( 'post_row_actions', array( $this, 'row_actions' ), 10, 2 );
				add_filter( 'manage_buttons-x_posts_columns', array( $this, 'columns' ) );
				add_action( 'manage_buttons-x_posts_custom_column', array( $this, 'column_preview' ), 10, 2);
				add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) , 50 );
	            add_action( 'init', array( $this, 'register_cpt' ), 1 );
	            add_action( 'init', array( $this, 'register_taxonomies' ) );
	            add_action( 'add_meta_boxes', array( $this, 'register_meta_boxes' ) );
	            add_action( 'do_meta_boxes', array( $this, 'remove_extra_meta_boxes' ) );
	            add_filter( 'post_updated_messages', array( $this, 'updated_messages' ) );
	            add_action( 'save_post_buttons-x', array( $this, 'save_data' ) );
				add_action( 'admin_menu', array( $this, 'register_settings_page' ) );
				add_action( 'admin_init', array( $this, 'register_settings' ) );
				add_action( 'admin_head', array( $this, 'logo_style' ) );
				add_action( 'post_submitbox_misc_actions', array( $this, 'publishing_actions' ) );
	            // public functionality
	            add_action( 'wp_enqueue_scripts', array( $this, 'public_enqueue_scripts' ), 51 );
	            add_shortcode( 'btnsx' , array( $this, 'shortcode' ) );
				add_action( 'wp_head', array( $this, 'override_style_inline' ), 51 );
				add_filter( 'widget_text', 'do_shortcode' ); // short code in text widget
				add_filter( 'views_edit-buttons-x', array( $this, 'screen_meta_view' ) );
				add_action( 'admin_menu', array( $this, 'welcome_page' ), 9 );
			} else {
				add_action( 'admin_notices', array( $this, 'dependency_notice' ) );
			}
		}

		/**
		 * Comaprison between free and pro plugin
		 * @since  0.0.1
		 * @return string
		 */
		public function free_vs_pro(){
			ob_start();
			?>
				<table class="bordered btnsx-comparison">
		        	<thead>
		          		<tr>
		              		<th data-field="id"><?php _e('Options','buttons-x'); ?></th>
		              		<th data-field="name"><?php _e('Free','buttons-x'); ?></th>
		              		<th data-field="price"><?php _e('Pro','buttons-x'); ?></th>
		          		</tr>
		        	</thead>
		        	<tbody>
						<tr>
		            		<td><?php _e('Gradient','buttons-x'); ?></td>
		            		<td><i class="fa fa-check"></i> Limited</td>
		            		<td><i class="fa fa-check"></i> Unlimited</td>
		          		</tr>
		          		<tr>
		            		<td><?php _e('Shadow','buttons-x'); ?></td>
		           	 		<td><i class="fa fa-check"></i> Limited</td>
		            		<td><i class="fa fa-check"></i> Unlimited</td>
		          		</tr>
		          		<tr>
		            		<td><?php _e('Google Fonts','buttons-x'); ?></td>
		            		<td><i class="fa fa-check"></i></td>
		            		<td><i class="fa fa-check"></i></td>
		          		</tr>
		          		<tr>
		            		<td><?php _e('Primary Text','buttons-x'); ?></td>
		            		<td><i class="fa fa-check"></i></td>
		            		<td><i class="fa fa-check"></i></td>
		          		</tr>
		          		<tr>
		            		<td><?php _e('Border & Border Radius','buttons-x'); ?></td>
		            		<td><i class="fa fa-check"></i></td>
		            		<td><i class="fa fa-check"></i></td>
		          		</tr>
			          	<tr>
			            	<td><?php _e('Full Width Buttons','buttons-x'); ?></td>
			            	<td><i class="fa fa-check"></i></td>
			            	<td><i class="fa fa-check"></i></td>
			          	</tr>
		          		<tr>
		            		<td><?php _e('Centered Buttons','buttons-x'); ?></td>
		            		<td><i class="fa fa-check"></i></td>
		            		<td><i class="fa fa-check"></i></td>
		          		</tr>
				        <tr>
				            <td><?php _e('Import / Export','buttons-x'); ?></td>
				            <td><i class="fa fa-close"></i></td>
				            <td><i class="fa fa-check"></i></td>
				        </tr>
				        <tr>
				            <td><?php _e('Secondary Text','buttons-x'); ?></td>
				            <td><i class="fa fa-close"></i></td>
				            <td><i class="fa fa-check"></i></td>
				        </tr>
				        <tr>
				            <td><?php _e('Image as Background','buttons-x'); ?></td>
				            <td><i class="fa fa-close"></i></td>
				            <td><i class="fa fa-check"></i></td>
				        </tr>
				        <tr>
				            <td><?php _e('Button as Menu','buttons-x'); ?></td>
				            <td><i class="fa fa-close"></i></td>
				            <td><i class="fa fa-check"></i></td>
				        </tr>
				        <tr>
				            <td><?php _e('Icons','buttons-x'); ?></td>
				            <td><i class="fa fa-close"></i></td>
				            <td><i class="fa fa-check"></i></td>
				        </tr>
				        <tr>
				            <td><?php _e('Animations','buttons-x'); ?></td>
				            <td><i class="fa fa-close"></i></td>
				            <td><i class="fa fa-check"></i></td>
				        </tr>
				        <tr>
				            <td><?php _e('24 link types','buttons-x'); ?></td>
				            <td><i class="fa fa-close"></i></td>
				            <td><i class="fa fa-check"></i></td>
				        </tr>
				        <tr>
				            <td><?php _e('Custom CSS','buttons-x'); ?></td>
				            <td><i class="fa fa-close"></i></td>
				            <td><i class="fa fa-check"></i></td>
				        </tr>
				        <tr>
				            <td><?php _e('Custom JS','buttons-x'); ?></td>
				            <td><i class="fa fa-close"></i></td>
				            <td><i class="fa fa-check"></i></td>
				        </tr>
				        <tr>
				            <td><?php _e('Background Image for Preview','buttons-x'); ?></td>
				            <td><i class="fa fa-close"></i></td>
				            <td><i class="fa fa-check"></i></td>
				        </tr>
				        <tr>
				          	<td colspan="3"><span style="font-weight:700;"><?php _e('Features','buttons-x'); ?></span></td>
				        </tr>
				        <tr>
				            <td><?php _e('Demo Buttons','buttons-x'); ?></td>
				            <td><i class="fa fa-check"></i></td>
				            <td><i class="fa fa-check"></i></td>
				        </tr>
				        <tr>
				            <td><?php _e('Add-ons','buttons-x'); ?></td>
				            <td><i class="fa fa-check"></i></td>
				            <td><i class="fa fa-check"></i></td>
				        </tr>
		          		<tr>
		            		<td><?php _e('Dual Buttons','buttons-x'); ?></td>
				            <td><i class="fa fa-close"></i></td>
				            <td><i class="fa fa-check"></i></td>
				        </tr>
				        <tr>
				            <td><?php _e('Social Buttons','buttons-x'); ?></td>
				            <td><i class="fa fa-close"></i></td>
				            <td><i class="fa fa-check"></i></td>
				        </tr>
				        <tr>
				            <td><?php _e('Social Counters','buttons-x'); ?></td>
				            <td><i class="fa fa-close"></i></td>
				            <td><i class="fa fa-check"></i></td>
				        </tr>
				        <tr>
				            <td><?php _e('External CSS','buttons-x'); ?></td>
				            <td><i class="fa fa-close"></i></td>
				            <td><i class="fa fa-check"></i></td>
				        </tr>
				        <tr>
				            <td>Visual Composer</td>
				            <td><i class="fa fa-close"></i></td>
				            <td><i class="fa fa-check"></i></td>
				        </tr>
				        <tr>
				            <td>WooCommerce</td>
				            <td><i class="fa fa-close"></i></td>
				            <td><i class="fa fa-check"></i></td>
				        </tr>
				        <tr>
				            <td>Popup Maker</td>
				            <td><i class="fa fa-close"></i></td>
				            <td><i class="fa fa-check"></i></td>
				        </tr>
				        <tr>
				            <td>Gravity Forms</td>
				            <td><i class="fa fa-close"></i></td>
				            <td><i class="fa fa-check"></i></td>
				        </tr>
				        <tr>
				            <td>Ninja Forms</td>
				            <td><i class="fa fa-close"></i></td>
				            <td><i class="fa fa-check"></i></td>
				        </tr>
				        <tr>
				            <td>Caldera Forms</td>
				            <td><i class="fa fa-close"></i></td>
				            <td><i class="fa fa-check"></i></td>
				        </tr>
				        <tr>
				            <td><?php _e('Priority Support','buttons-x'); ?></td>
				            <td><i class="fa fa-close"></i></td>
				            <td><i class="fa fa-check"></i></td>
				        </tr>
				        <tr>
				            <td><?php _e('Button Packs','buttons-x'); ?></td>
				            <td><i class="fa fa-close"></i></td>
				            <td><i class="fa fa-check"></i></td>
				        </tr>
		        	</tbody>
		      	</table>
	      	<?php
	      	return ob_get_clean();
		}

		/**
		 * Register parent menu
		 * @since  1.7.3
		 * @return
		 */
		public function welcome_page() {
			if( function_exists('current_user_can') ){
				if( current_user_can( 'manage_options' ) ){
			    	add_menu_page( 'Buttons X', 'Buttons X', 'manage_options', 'btnsx', array( $this, 'welcome_page_callback' ), 'dashicons-btnsx-logo', 21);
			    	add_submenu_page( 'btnsx', 'Welcome', 'Welcome', 'manage_options', 'btnsx', array( $this, 'welcome_page_callback' ) );
			    }
			}
		}

		/**
		 * Welcome page markup
		 * @since  1.7.3
		 * @return string
		 */
		public function welcome_page_callback(){
		    ?>
		        <style type="text/css">
		            .btnsx-badge {
		                background-color: #005086;
		                color: #69B4E2;
		                background-image: none!important;
		            }
		            .btnsx-badge span {
		                position: absolute;
		                font-size: 64px;
		                top: 25px;
		                color: #fff;
		            }
		            .fb-like > span, .fb-follow > span {
		            	vertical-align: baseline!important;
		            }
		        </style>
		        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
		        <div id="fb-root"></div>
				<script>(function(d, s, id) {
				  var js, fjs = d.getElementsByTagName(s)[0];
				  if (d.getElementById(id)) return;
				  js = d.createElement(s); js.id = id;
				  js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.5";
				  fjs.parentNode.insertBefore(js, fjs);
				}(document, 'script', 'facebook-jssdk'));</script>
		        <!-- Place this tag in your head or just before your close body tag. -->
				<script src="https://apis.google.com/js/platform.js" async defer></script>
		        <div class="wrap about-wrap">
		            <h1><?php printf( __( 'Welcome to Buttons X %1$s', 'buttons-x' ), BTNSX__VERSION ); ?></h1>
		            <div class="about-text">
		            	<?php printf( __( 'Thank you for installing! You are awesome. This version fixes ACF PRO scripts conflict causing button edit screen to not load.', 'buttons-x' ), BTNSX__VERSION ); ?>
		            	<br><br>
		            	<!-- Social Buttons -->
			            	<a href="https://twitter.com/share" class="twitter-share-button"{count} data-url="https://www.button.sx" data-text="Build any kind of button imaginable right from your WordPress Dashboard with Buttons X!" data-via="btnsx" data-related="gautam_thapar" data-hashtags="ButtonsX">Tweet</a>
			            	<a href="https://twitter.com/btnsx" class="twitter-follow-button" data-show-count="false">Follow @btnsx</a>
			            	<!-- Facebook -->
			            	<div class="fb-like" data-href="https://www.button.sx" data-layout="button" data-action="like" data-show-faces="false" data-share="true"></div>
			            	<div class="fb-follow" data-href="https://www.facebook.com/btnsx" data-layout="button" data-show-faces="true"></div>
							<!-- Google -->
							<div class="g-follow" data-annotation="none" data-height="20" data-href="//plus.google.com/u/0/105722599123710552395" data-rel="publisher"></div>
							<div class="g-plusone" data-size="medium" data-annotation="inline" data-width="200" data-href="https://www.button.sx"></div>
		            </div>
		            <div class="wp-badge btnsx-badge"><span class="dashicons-before dashicons-btnsx-logo"></span><?php printf( __( 'Version %1$s', 'buttons-x' ), BTNSX__VERSION ); ?></div>
		            <hr>
		            <p><?php echo sprintf( wp_kses(__( 'If you like Buttons X, please give us a <a href="%s">*****</a> rating!', 'buttons-x'), array( 'a' => array( 'href' => array() ) ) ), 'https://wordpress.org/support/plugin/buttons-x/reviews/?rate=5#new-post' ); ?></p>
		            <hr>
		            <div class="one-col">
		            	<div class="col">
			            	<br>
			            	<h3><?php _e('Introduction','buttons-x'); ?></h3>
			            	<br>
			            	<p><?php _e( 'Hi there,', 'buttons-x' ); ?></p>
							<p><?php
								echo sprintf( wp_kses(__( 'I am <a href="%s">Gautam Thapar</a> and I am the author of this plugin. I am working hard to make sure you have pleasant experience while using this plugin. In case you face any issues or get stuck somehwere then kindly let me know using the <a href="%s">support forum</a>.', 'buttons-x'), array( 'a' => array( 'href' => array() ) ) ), 'http://codecanyon.net/user/gautamthapar/portfolio?ref=GautamThapar', 'https://wordpress.org/support/plugin/buttons-x/' );
								echo '&nbsp;';
								_e( 'I will do my best to solve your issues as soon as possible.', 'buttons-x' );
								?>
							</p>
							<p><?php _e( 'This is a <b>LITE version</b> of Buttons X and so it is limited in functionality. But it does contain enough to help you build beautiful CSS3 buttons.', 'buttons-x' ); ?></p>
							<p><?php echo sprintf( wp_kses( __( 'Please try it out and if you feel the plugin is useful then do buy the <a href="%s"><strong>PRO version</strong></a> to unlock all the features.', 'buttons-x' ), array(  'a' => array( 'href' => array() ), 'strong' => array() ) ), esc_url( 'https://codecanyon.net/item/buttons-x-powerful-button-builder-for-wordpress/12710619?ref=GautamThapar' ) ); ?></p>
							<!-- @TODO - Contact Link -->
							<p><?php _e( 'If you face any issue, please do'); ?> <a target="_blank" href="https://wordpress.org/support/plugin/buttons-x/"><?php _e( 'contact me', 'buttons-x' ); ?></a>. <?php _e( 'I will be more than happy to help you!', 'buttons-x' ); ?></p>
							<p><?php _e( 'Warm Regards' ); ?>, Gautam Thapar.</p>
							<br>
							<h4><?php echo sprintf( wp_kses( __( '<a href="%s"><strong>Try Pro for FREE!</strong></a>', 'buttons-x' ), array(  'a' => array( 'href' => array() ), 'strong' => array() ) ), esc_url( 'http://buttons.thewebsitedev.com/' ) ); ?></h4>
							<br>
							<!-- Begin MailChimp Signup Form -->
								<link href="//cdn-images.mailchimp.com/embedcode/classic-081711.css" rel="stylesheet" type="text/css">
								<style type="text/css">
									#mc_embed_signup{background:#fff; clear:left; font:14px Helvetica,Arial,sans-serif; }
								</style>
								<div id="mc_embed_signup">
								<form action="//button.us11.list-manage.com/subscribe/post?u=bedf4c5985b18b5ff8b07e766&amp;id=9bc07618db" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
								    <div id="mc_embed_signup_scroll">
									<h2><?php _e('Plugin Updates','buttons-x'); ?></h2>
								<div class="indicates-required"><span class="asterisk">*</span> <?php _e('indicates required','buttons-x'); ?></div>
								<div class="mc-field-group">
									<label for="mce-EMAIL"><?php _e('Email Address','buttons-x'); ?>  <span class="asterisk">*</span>
								</label>
									<input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL">
								</div>
									<div id="mce-responses" class="clear">
										<div class="response" id="mce-error-response" style="display:none"></div>
										<div class="response" id="mce-success-response" style="display:none"></div>
									</div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
								    <div style="position: absolute; left: -5000px;"><input type="text" name="b_bedf4c5985b18b5ff8b07e766_9bc07618db" tabindex="-1" value=""></div>
								    <div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
								    </div>
								</form>
								</div>
								<script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script><script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';}(jQuery));var $mcj = jQuery.noConflict(true);</script>
							<!--End mc_embed_signup-->
							<br>
							<div class="btnsx">
								<h3>Free Vs Pro <a href="https://codecanyon.net/item/buttons-x-powerful-button-builder-for-wordpress/12710619?ref=GautamThapar" style="font-size:14px;">[ Get Pro ]</a></h3>
								<?php echo $this->free_vs_pro(); ?>
						    </div>
						</div>
		            </div>
		            <div class="changelog">
						<h3><?php _e('Helpful Links','buttons-x'); ?></h3>
						<div class="feature-section under-the-hood three-col">
							<div class="col">
								<h4><a target="_blank" href="https://www.button.sx/product-category/add-ons/"><?php _e('Button Add-ons','buttons-x'); ?></a></h4>
							</div>
							<div class="col">
								<h4><a target="_blank" href="https://www.button.sx/product-category/packs/"><?php _e('Button Packs','buttons-x'); ?></a></h4>
							</div>
							<div class="col">
								<h4><a target="_blank" href="https://gautamthapar.atlassian.net/wiki/display/BX/"><?php _e('Documentation','buttons-x'); ?></a></h4>
							</div>
							<div class="col">
								<h4><a target="_blank" href="http://gautamthapar.ticksy.com/"><?php _e('Pro Support','buttons-x'); ?></a></h4>
							</div>
							<div class="col">
								<h4><a target="_blank" href="https://www.button.sx/"><?php _e('Official Website','buttons-x'); ?></a></h4>
							</div>
							<div class="col">
								<h4><a target="_blank" href="https://twitter.com/Gautam_Thapar"><?php _e('Twitter','buttons-x'); ?></a></h4>
							</div>
						</div>
					</div>
					<div class="changelog point-releases">
					</div>
		            <div class="changelog point-releases">
		                <h3><?php _e('Release Notes','buttons-x'); ?></h3>
		                <ol>
		                	<li>Fixed ACF Pro scripts conflict causing button edit screen to not load.</li>
		                </ol>
		            </div>
		        </div>
		    <?php
		}

		/**
		 * Add packs and tags links on buttons list page
		 * @since  1.7.3
		 * @param  array    $views
		 * @return array
		 */
		public function screen_meta_view( $views ){
		    $packs = count(get_terms('btnsx_pack'));
		    $tags = count(get_terms('btnsx_tag'));
		    $views['packs'] = "<a href='edit-tags.php?taxonomy=btnsx_pack&post_type=buttons-x'>".__('Packs','buttons-x')." <span class='count'>(".$packs.")</span></a>";
		    $views['tags'] = "<a href='edit-tags.php?taxonomy=btnsx_tag&post_type=buttons-x'>".__('Tags','buttons-x')." <span class='count'>(".$tags.")</span></a>";
		    return $views;
		}

		/**
		 * Adds a box to the side column on Buttons edit screens.
		 * @since  1.3
		 * @return string
		 */
		public function preview_settings_callback( $post ) {
			// Add a nonce field so we can check for it later.
			wp_nonce_field( 'btnsx_preview_settings_meta', 'btnsx_preview_settings_meta_nonce' );

			$meta_values = array();
	        $meta_values = get_post_meta( $post->ID, 'btnsx', true );

			$btnsx_form = new BtnsxFormElements();
			echo '<div style="min-height:150px;"><div class="btnsx btnsx-side">';
			echo $btnsx_form->input( array(
	        		'type'			=>	'color',
					'id'			=>	'btnsx_opt_preview_background',
					'name'			=>	'btnsx_opt_preview_background',
					'placeholder'	=>	' ',
					'label'			=>	__( 'Background Color', 'buttons-x' ),
					'value'			=>	$this->meta_values( $post->ID, array( 'field' => 'btnsx_preview_background' ) ),
					'tooltip'		=>	__( 'Changing the color will change the preview background. If you intend to use this button on a coloured background then changing the preview background will give you exact look of the button over a color.', 'buttons-x' )
				)
        	);
        	echo '<br>';
        	echo $btnsx_form->input( array(
					'type'		=>	'preview-bg-img-banner'
				)
        	);
        	echo '</div></div>';
		}

		/**
		 * Output override style in head
		 * @since  1.3
		 * @return string
		 */
		public function override_style_inline() {
			echo '<meta name="generator" content="Powered by Buttons X - Powerful Button Builder for WordPress."/>' . PHP_EOL;
		}

		/**
		 * Function to rest button options
		 * @since  0.1
		 * @return string
		 */
		public function publishing_actions() {
		    global $post;
		    if ( $post->post_type === 'buttons-x' ) {
		        echo '<div class="misc-pub-section misc-pub-btnsx-options-reset"><i id="btnsx_options_reset_icon" class="fa fa-refresh" style="font-size:16px;color:#82878c;margin-right:10px;"></i><a id="btnsx_options_reset" href="javascript:void(0)">' . __( 'Reset Options', 'buttons-x' ) . '</a></div>';
		        echo '<div class="misc-pub-section misc-pub-btnsx-options-reset"><i id="btnsx_options_clone_icon" class="fa fa-clone" style="font-size:16px;color:#82878c;margin-right:10px;"></i><a id="btnsx_options_clone" href="edit.php?post_type=buttons-x&btnsx-clone=' . $post->ID . '">' . __( 'Clone Button', 'buttons-x' ) . '</a></div>';
		    }
		}

		/**
		 * Function to load logo icon styles & font
		 * @since  0.1
		 * @return string
		 */
		public function logo_style() {
			?>
			<style type="text/css">
				@font-face {
					font-family: 'btnsx';
					src:url('<?php echo BTNSX__PLUGIN_URL; ?>assets/css/fonts/btnsx.eot?2w9zom');
					src:url('<?php echo BTNSX__PLUGIN_URL; ?>assets/css/fonts/btnsx.eot?#iefix2w9zom') format('embedded-opentype'),
						url('<?php echo BTNSX__PLUGIN_URL; ?>assets/css/fonts/btnsx.ttf?2w9zom') format('truetype'),
						url('<?php echo BTNSX__PLUGIN_URL; ?>assets/css/fonts/btnsx.woff?2w9zom') format('woff'),
						url('<?php echo BTNSX__PLUGIN_URL; ?>assets/css/fonts/btnsx.svg?2w9zom#btnsx') format('svg');
					font-weight: normal;
					font-style: normal;
				}
				[class^="dashicons-btnsx-"], [class*=" dashicons-btnsx-"] {
					font-family: 'btnsx';
					speak: none;
					font-style: normal;
					font-weight: normal;
					font-variant: normal;
					text-transform: none;
					line-height: 1;
					/* Better Font Rendering =========== */
					-webkit-font-smoothing: antialiased;
					-moz-osx-font-smoothing: grayscale;
				}
				.dashicons-btnsx-logo:before {
					font-family: btnsx !important;
					content: "\e600";
					font-size: 1.3em!important;
					font-weight: 900!important;
				}
			</style>
			<?php
		}

		/**
		 * Function to remove view link to row actions on buttons X post type
		 * @since  0.1
		 * @param  string    $actions default actions
		 * @param  WP_Post   $post post object
		 * @return string
		 */
		public function row_actions( $actions, WP_Post $post ) {
	        if ( $post->post_type != 'buttons-x' ) {
	            return $actions;
	        }
	        unset( $actions['view'] );
	        return $actions;
	    }

	    /**
	     * Add custom columns to buttons x page
	     * @since  0.1
	     * @param  array    $defaults
	     * @return array
	     */
	    public function columns( $defaults ) {
			$defaults['btnsx_shortcode'] 	= __( 'Shortcode', 'buttons-x' );
		    $defaults['btnsx_preview'] 	= __( 'Preview', 'buttons-x' );
		    unset( $defaults['date'] );
		    return $defaults;
		}

		/**
		 * Callback function for preview column
		 * @since  0.1
		 * @param  string    $column_name
		 * @param  int    $id
		 * @return string
		 */
		public function column_preview( $column_name, $id ) {
		    if ( $column_name === 'btnsx_preview' ) {
		        $meta = get_post_meta( $id, 'btnsx', true);
		        $background = ( isset( $meta['btnsx_preview_background'] ) && $meta['btnsx_preview_background'] != '' ? $meta['btnsx_preview_background'] : '#fff' );
		        $background_image = ( isset( $meta['btnsx_preview_background_image']['image'] ) && $meta['btnsx_preview_background_image']['image'] != '' ? 'background-image:url(' . $meta['btnsx_preview_background_image']['image'] . ');' : '' );
		       	$background_image_position = ( isset( $meta['btnsx_preview_background_image']['position'] ) && $meta['btnsx_preview_background_image']['position'] != '' ? 'background-position:' . $meta['btnsx_preview_background_image']['position'] . ';' : '' );
		       	$background_overlay = ( isset( $meta['btnsx_preview_background_overlay'] ) && $meta['btnsx_preview_background_overlay'] != '' ? 'opacity:' . $meta['btnsx_preview_background_overlay'] . ';' : 'opacity:0;' );
		        ?>
		        <div class="btnsx">
			        <!-- Modal Trigger -->
					<a class="waves-effect waves-light btn modal-trigger" href="#modal<?php echo esc_attr( $id ); ?>"><?php _e( 'Preview', 'buttons-x' ); ?></a>
					<!-- Modal Structure -->
					<style type="text/css">
						#modal<?php echo sanitize_text_field( $id ); ?> .modal-content, #modal<?php echo sanitize_text_field( $id ); ?> .modal-footer {
						  	background-color: <?php echo sanitize_text_field( $background ); ?>;
						  	<?php echo $background_image;echo $background_image_position; ?>
						  	background-repeat: repeat;
						}
					</style>
					<div id="modal<?php echo esc_attr( $id ); ?>" class="modal preview-modal">
						<div class="btnsx-preview-overlay" style="<?php echo sanitize_text_field( $background_overlay ); echo 'background-color:'.sanitize_text_field( $background ).';'; ?>"></div>
					    <div class="modal-content">
					    	<?php
					    		// Filter Short Code Attributes
					    		$default = array (
					    			'id' => $id,
					    			'css_inline' => '1'
					    		);
								$filter = apply_filters( 'btnsx_list_preview_filter', array(), $default );
								$filtered = array();
								// combine multiple arrays into one
								foreach ($filter as $key => $value) {
									foreach ($value as $k => $v) {
										$filtered[$k] = $v;
									}
								}
								$options = wp_parse_args( $filtered, $default );
					    		echo $this->shortcode( $options );
					    	?>
					    	<a href="#!" class="modal-action modal-close" style="position:absolute;bottom:10px;right:10px;"><i class="fa fa-close"></i></a>
					    </div>
					    <div class="modal-footer" style="display:none;">
					      	<a href="javascript:void(0)" class="modal-action modal-close"><i class="fa fa-close"></i></a>
					    </div>
					</div>
				</div>
				<?php
		    }
		    if ( $column_name === 'btnsx_shortcode' ) {
		        echo '[btnsx id="' . $id . '"]';
		    }
		}

		/**
		 * Function to re-order columns on Buttons X page
		 * @since  0.1
		 * @param  array   $columns
		 * @return array
		 */
		public function columns_order( $columns ) {
		    return array(
		        'cb' 					=> '<input type="checkbox" />',
		        'title' 				=> __( 'Title', 'buttons-x' ),
		        'taxonomy-btnsx_pack' 	=> __( 'Packs', 'buttons-x' ),
		        'taxonomy-btnsx_tag' 	=> __( 'Tags', 'buttons-x' ),
		        'btnsx_shortcode' 		=> __( 'Shortcode', 'buttons-x' ),
		        'btnsx_preview' 		=> __( 'Preview', 'buttons-x' ),
		    );
		}

		/**
		 * Load plugin textdomain
		 * @since 0.1
		 */
		public function load_textdomain() {
			load_plugin_textdomain( 'buttons-x', false, BTNSX__PLUGIN_URL . 'languages' );
		}

		/**
		 * Dependency notice
		 * @since  0.1
		 * @return html
		 */
		public function dependency_notice() {
			?>

			<div class="error">
				<p><?php printf( __( 'Buttons X require minimum WordPress version to be %1$s.', 'buttons-x' ), BTNSX__MIN_WP_VERSION ); ?></p>
			</div>

			<?php
		}

		/**
		 * Enqueue Admin Scripts
		 * @since 0.1
		 */
		public function admin_enqueue_scripts() {

			$screen = get_current_screen();

			// Admin Scripts
			if ( in_array( $screen->id, array( 'buttons-x', 'edit-buttons-x', 'buttons-x_page_buttons-x-settings', 'buttons-x_page_buttons-x-import' ) ) ) {
				wp_enqueue_script( 'jquery-ui-core' );
				wp_enqueue_script( 'wp-color-picker' );
				wp_register_script(
					'btnsx' . '-js',
					BTNSX__PLUGIN_URL . 'assets/js/admin/admin.min.js',
					array('jquery'),
					BTNSX__VERSION,
					false
				);
				wp_enqueue_script( 'btnsx' . '-js' );
				wp_deregister_script( 'select2' ); // WooCommerce Product Faq Manager
				wp_dequeue_script( 'select2' ); // WooCommerce Product Faq Manager
				wp_dequeue_script( 'acf-input' ); // ACF
				wp_dequeue_script( 'acf-pro-input' ); // ACF
				wp_dequeue_script( 'acf-field-group' ); // ACF
			}
			if ( in_array( $screen->id, array( 'edit-buttons-x-cs' ) ) ) {
				wp_enqueue_script('jquery-ui-sortable');
			}
			if ( in_array( $screen->id, array( 'buttons-x' ) ) ) {
				wp_register_script(
					'btnsx' . '-views-js',
					BTNSX__PLUGIN_URL . 'assets/js/admin/views.min.js',
					array('jquery','jquery-ui-core','backbone'),
					BTNSX__VERSION,
					false
				);
				$translations = array(
					'google_web_fonts' => BTNSX__PLUGIN_URL . 'assets/webfonts.json'
				);
				wp_localize_script( 'btnsx' . '-views-js', 'view_translations', $translations );
				wp_enqueue_script( 'btnsx' . '-views-js' );
			}

			// Admin Styles
			if ( in_array( $screen->id, array( 'buttons-x', 'edit-buttons-x', 'buttons-x_page_buttons-x-settings', 'buttons-x_page_buttons-x-import', 'toplevel_page_btnsx' ) ) ) {
				wp_enqueue_style( 'wp-color-picker' );
				wp_enqueue_style(
					'btnsx' . '-css',
					BTNSX__PLUGIN_URL . 'assets/css/admin/admin.min.css',
					array(),
					BTNSX__VERSION
				);
				wp_dequeue_style( 'wpe-common' ); // WPEngine styles
				wp_dequeue_style( 'jquery-ui-datepicker' );
				wp_dequeue_style( 'font-awesome' ); // Multi-X Bar plugin
				wp_dequeue_style( 'select2' ); // WooCommerce Product Faq Manager
			}
		}

		/**
		 *  Enqueue Public Scripts
		 *  @since 0.1
		 */
		public function public_enqueue_scripts() {
			// Public scripts
			wp_register_script(
				'btnsx',
				BTNSX__PLUGIN_URL . 'assets/js/public/btnsx.min.js',
				array('jquery'),
				BTNSX__VERSION,
				true
			);
			wp_enqueue_script( 'btnsx' );
			// Public styles
			wp_enqueue_style(
				'btnsx',
				BTNSX__PLUGIN_URL . 'assets/css/common/button.min.css',
				array(),
				BTNSX__VERSION
			);
		}

		/**
		 * Register settings page
		 * @since  0.1
		 * @return string
		 */
		public function register_settings_page() {
			add_submenu_page(
				'btnsx',
				'Buttons X',
				'Import',
				'manage_options',
				'buttons-x-import',
				array( $this, 'import_page_callback' )
			);
			add_submenu_page(
				'btnsx',
				'Buttons X',
				'Settings',
				'manage_options',
				'buttons-x-settings',
				array( $this, 'settings_page_callback' )
			);
		}

		/**
		 * Settings page callback
		 * @since  0.1
		 * @return string
		 */
		public function settings_page_callback() {
			$btnsx_settings = get_option( 'btnsx_settings' );
			include plugin_dir_path( __FILE__ ) . 'settings-page.php';
		}

		/**
		 * Register settings
		 * @since  0.1
		 * @return
		 */
		public function register_settings() {
			register_setting( 'btnsx_settings_group', 'btnsx_settings' );
		}

		/**
		 * Import page callback
		 * @since  0.1
		 * @return string
		 */
		public function import_page_callback() {
			include plugin_dir_path( __FILE__ ) . 'import-page.php';
		}

        /**
        * Register the menu for the Dashboard.
        * @since 0.1
        */
        public function register_cpt() {
    		global $btnsx_settings;

    		// White label options
            if( isset( $btnsx_settings['menu_name'] ) ){
                $button_menu_name = $btnsx_settings['menu_name'];
            } else {
                $button_menu_name = _x('Buttons', 'admin menu', 'buttons-x');
            }

            if( isset( $btnsx_settings['name_singular'] ) ){
                $button_name_singular = $btnsx_settings['name_singular'];
            } else {
                $button_name_singular = _x('Button', 'post type singular name', 'buttons-x');
            }

            if( isset( $btnsx_settings['name_plural'] ) ){
                $button_name_plural = $btnsx_settings['name_plural'];
            } else {
                $button_name_plural = _x('Buttons', 'post type general name', 'buttons-x');
            }

            if( isset( $btnsx_settings['admin_bar_name'] ) ){
                $button_admin_bar_name = $btnsx_settings['admin_bar_name'];
            } else {
                $button_admin_bar_name = _x('Button', 'add new on admin bar', 'buttons-x');
            }

            // Register buttons post
            register_post_type('buttons-x',
                array(
                    'labels' => array(
                        'name' => $button_name_plural,
                        'singular_name' => $button_name_singular,
                        'menu_name' => $button_menu_name,
                        'name_admin_bar' => $button_admin_bar_name,
                        'add_new' => _x('Add New', $button_name_singular, 'buttons-x'),
                        'add_new_item' => __('Add New ', 'buttons-x') . $button_name_singular,
                        'new_item' => __('New ', 'buttons-x') . $button_name_singular,
                        'edit_item' => __('Edit ', 'buttons-x') . $button_name_singular,
                        'view_item' => __('View ', 'buttons-x') . $button_name_singular,
                        'all_items' => $button_name_plural,
                        'search_items' => __('Search ', 'buttons-x') . $button_name_plural,
                        'parent_item_colon' => __('Parent ', 'buttons-x') . $button_name_plural . ':',
                        'not_found' => __('No ', 'buttons-x') . $button_name_plural . __(' found.', 'buttons-x'),
                        'not_found_in_trash' => __('No ', 'buttons-x') . $button_name_plural . __(' found in Trash.', 'buttons-x'),
                    ),
                    'public'                => false,
                    'show_ui'				=> true,
                    'exclude_from_search'   => true,
                    'publicly_queryable'    => false,
                    'has_archive'           => false,
                    'show_in_admin_bar'     => true,
                    'show_in_nav_menus'		=> false,
                    'show_in_menu'			=> 'btnsx',
                    'supports'              => array( 'title', 'editor' ),
                    'menu_icon'				=> 'dashicons-btnsx-logo',
                )
            );
        }

        /**
         * Button update messages.
         * @since  0.1
         * @param  array    $messages
         * @return array
         */
		public function updated_messages( $messages ) {
			$post             = get_post();
			$post_type        = get_post_type( $post );
			$post_type_object = get_post_type_object( $post_type );
			$messages['buttons-x'] = array(
				0  => '', // Unused. Messages start at index 1.
				1  => __( 'Button updated.', 'buttons-x' ),
				2  => __( 'Custom field updated.', 'buttons-x' ),
				3  => __( 'Custom field deleted.', 'buttons-x' ),
				4  => __( 'Button updated.', 'buttons-x' ),
				/* translators: %s: date and time of the revision */
				5  => isset( $_GET['revision'] ) ? sprintf( __( 'Button restored to revision from %s', 'buttons-x' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
				6  => __( 'Button published.', 'buttons-x' ),
				7  => __( 'Button saved.', 'buttons-x' ),
				8  => __( 'Button submitted.', 'buttons-x' ),
				9  => sprintf(
					__( 'Button scheduled for: <strong>%1$s</strong>.', 'buttons-x' ),
					// translators: Publish box date format, see http://php.net/date
					date_i18n( __( 'M j, Y @ G:i', 'buttons-x' ), strtotime( $post->post_date ) )
				),
				10 => __( 'Button draft updated.', 'buttons-x' )
			);

			// if ( $post_type_object->publicly_queryable ) {
			// 	$permalink = get_permalink( $post->ID );

			// 	$view_link = sprintf( ' <a href="%s">%s</a>', esc_url( $permalink ), __( 'View button', 'buttons-x' ) );
			// 	$messages[ $post_type ][1] .= $view_link;
			// 	$messages[ $post_type ][6] .= $view_link;
			// 	$messages[ $post_type ][9] .= $view_link;

			// 	$preview_permalink = add_query_arg( 'preview', 'true', $permalink );
			// 	$preview_link = sprintf( ' <a target="_blank" href="%s">%s</a>', esc_url( $preview_permalink ), __( 'Preview Button', 'buttons-x' ) );
			// 	$messages[ $post_type ][8]  .= $preview_link;
			// 	$messages[ $post_type ][10] .= $preview_link;
			// }
			return $messages;
		}

        /**
         * Register custom taxonomies for button post type
         * @since  0.1
         * @return
         */
        public function register_taxonomies() {
		    // Add new taxonomy, make it hierarchical (like categories)
		    $labels = array(
		        'name' 				=> _x( 'Packs', 'buttons-x' ),
		        'singular_name' 	=> _x( 'Pack', 'buttons-x' ),
		        'search_items' 		=> __( 'Search Pack', 'buttons-x' ),
		        'all_items' 		=> __( 'All', 'buttons-x' ),
		        'parent_item' 		=> __( 'Parent Pack', 'buttons-x' ),
		        'parent_item_colon' => __( 'Parent Pack:', 'buttons-x' ),
		        'edit_item' 		=> __( 'Edit Pack', 'buttons-x' ),
		        'update_item' 		=> __( 'Update Pack', 'buttons-x' ),
		        'add_new_item' 		=> __( 'Add New Pack', 'buttons-x' ),
		        'new_item_name' 	=> __( 'New Pack Name', 'buttons-x' ),
		        'menu_name' 		=> __( 'Packs', 'buttons-x' )
		    );
		    $args = array(
		    	'public' 			=> false,
		        'hierarchical' 		=> true,
		        'labels' 			=> $labels,
		        'show_ui' 			=> true,
		        'show_admin_column' => true,
		        'query_var' 		=> true,
		        'show_in_nav_menus' => false,
		        'rewrite' 			=> array( 'slug' => 'btnsx_pack' ),
		    );
		    register_taxonomy( 'btnsx_pack', array( 'buttons-x' ), $args );
		    // Add new taxonomy, not hierarchical (like tags)
		    $labels = array(
		        'name' 				=> _x( 'Tags', 'buttons-x' ),
		        'singular_name' 	=> _x( 'Tag', 'buttons-x' ),
		        'search_items' 		=> __( 'Search Tag', 'buttons-x' ),
		        'all_items' 		=> __( 'All', 'buttons-x' ),
		        'parent_item' 		=> __( 'Parent Tag', 'buttons-x' ),
		        'parent_item_colon' => __( 'Parent Tag:', 'buttons-x' ),
		        'edit_item' 		=> __( 'Edit Tag', 'buttons-x' ),
		        'update_item' 		=> __( 'Update Tag', 'buttons-x' ),
		        'add_new_item' 		=> __( 'Add New Tag', 'buttons-x' ),
		        'new_item_name' 	=> __( 'New Tag Name', 'buttons-x' ),
		        'menu_name' 		=> __( 'Tags', 'buttons-x' )
		    );
		    $args = array(
		    	'public' 			=> false,
		        'hierarchical' 		=> false,
		        'labels' 			=> $labels,
		        'show_ui' 			=> true,
		        'show_admin_column' => true,
		        'query_var' 		=> true,
		        'show_in_nav_menus' => false,
		        'rewrite' 			=> array( 'slug' => 'btnsx_tag' ),
		    );
		    register_taxonomy( 'btnsx_tag', array( 'buttons-x' ), $args );
		}

		/**
		* Adds the meta box container.
		* @since 0.1
		*/
		public function register_meta_boxes() {
			$screens = array('buttons-x');
			foreach ( $screens as $screen ) {
				add_meta_box(
			        'btnsx-pro',
			        __( 'Go PRO', 'buttons-x' ),
			        array( $this, 'pro_callback' ),
			        $screen,
			        'side',
			        'high'
			    );
			    add_meta_box(
			        'btnsx-preview',
			        __( 'Live Preview', 'buttons-x' ),
			        array( $this, 'preview_callback' ),
			        $screen,
			        'normal',
			        'high'
			    );
			    add_meta_box(
			        'btnsx-options',
			        'Buttons X - ' . __( 'Options Panel', 'buttons-x' ),
			        array( $this, 'options_callback' ),
			        $screen,
			        'normal',
			        'core'
			    );
			    add_meta_box(
			        'btnsx-preview-settings',
			        'Buttons X - ' . __( 'Preview Settings', 'buttons-x' ),
			        array( $this, 'preview_settings_callback' ),
			        $screen,
			        'side',
			        'high'
			    );
			    add_meta_box(
			        'btnsx-shortcode',
			        'Buttons X - ' . __( 'Short Code', 'buttons-x' ),
			        array( $this, 'shortcode_callback' ),
			        $screen,
			        'side',
			        'high'
			    );
			}
		}

		/**
	     * Prints the box content.
	     * @param WP_Post $post The object for the current post/page.
	     * @since 0.1
	     */
	    public function shortcode_callback( $post ) {
	    	$current_color = get_user_option( 'admin_color' );
	    	global $_wp_admin_css_colors;
	        echo '<p style="font-weight:bold;color:'.$_wp_admin_css_colors[$current_color]->colors[2].';">[btnsx id="'.$post->ID.'"]</p>';
	    }

		/**
		 * remove extra meta boxes on custom post page
		 * @since 0.1
		 */
		public function remove_extra_meta_boxes() {
		    if( get_post_type() == 'buttons-x' ){
		        $meta_boxes_advanced = $this->get_meta_boxes( 'buttons-x', 'advanced' );
		        $meta_boxes_normal = $this->get_meta_boxes( 'buttons-x', 'normal' );
		        $meta_boxes_side = $this->get_meta_boxes( 'buttons-x', 'side' );
		        $meta_advanced = apply_filters( 'btnsx_button_meta_boxes_advanced', array( 'btnsx-analytic' ) );
		        $meta_normal = apply_filters( 'btnsx_button_meta_boxes_normal', array( 'btnsx-preview', 'btnsx-options' ) );
		        $meta_side = apply_filters( 'btnsx_button_meta_boxes_side', array( 'btnsx-pro', 'submitdiv', 'btnsx_packdiv', 'tagsdiv-btnsx_tag', 'btnsx-override-style', 'btnsx-preview-settings', 'btnsx-shortcode', 'btnsx-gravity-forms' ) );
		        foreach ( $meta_boxes_advanced as $key => $value ) {
		            foreach ( $value as $k => $v ) {
		                if( !in_array( $v['id'], $meta_advanced )  ){
		                    remove_meta_box( $v['id'] , 'buttons-x' , 'advanced' );
		                }
		            }
		        }
		        foreach ($meta_boxes_normal as $key => $value) {
		            foreach ($value as $k => $v) {
		                if( !in_array($v['id'], $meta_normal)  ){
		                    remove_meta_box( $v['id'] , 'buttons-x' , 'normal' );
		                }
		            }
		        }
		        foreach ($meta_boxes_side as $key => $value) {
		            foreach ($value as $k => $v) {
		                if( !in_array($v['id'], $meta_side)  ){
		                    remove_meta_box( $v['id'] , 'buttons-x' , 'side' );
		                }
		            }
		        }
		    }
		}

		/**
		 * Extract meta boxes information
		 * @param  string $screen  target page
		 * @param  string $context part of page where meta box is shown
		 * @return array          list of meta boxes on the target page
		 * @since 0.1
		 */
		public function get_meta_boxes( $screen = null, $context = 'advanced' ) {
		    global $wp_meta_boxes;
		    if ( empty( $screen ) ) {
		        $screen = get_current_screen();
		    } elseif ( is_string( $screen ) ) {
		        $screen = convert_to_screen( $screen );
		    }
		    $page = $screen->id;
		    if( isset( $wp_meta_boxes[ $page ][ $context ] ) ) {
		    	return $wp_meta_boxes[ $page ][ $context ];
		    } else {
		    	return array();
		    }
		}

		/**
	     * PRO version.
	     * @param WP_Post $post The object for the current post/page.
	     * @since 0.8.6
	     */
	    public function pro_callback( $post ) {
	    	?>
	    		<p>Rated 4.90 / 5.00 based on 21 <a target="_blank" href="https://goo.gl/36uMVL">ratings</a> on CodeCanyon.</p>
	    		<p>One time fee. No yearly contracts.</p>
	    		<p>Regular updates. 24/7 support.</p>
	    		<p>No reason not to upgrade. Get <a target="_blank" href="https://goo.gl/W9g1IG">Pro</a> today!</p>
	    	<?php
	    }

		/**
	     * Prints the box content.
	     * @param WP_Post $post The object for the current post/page.
	     * @since 0.1
	     */
	    public function preview_callback( $post ) {
	    	$btnsx_settings = array(
	    		'material_admin_theme' => false,
	    		'wp_admin_theme' => true,
	    	);
	    	$current_color = get_user_option( 'admin_color' );
	    	global $_wp_admin_css_colors;
	    	if( $btnsx_settings['material_admin_theme'] == true ) :
	        ?>
	        <!-- Material Style For Admin Page -->
	        <style type="text/css">
	        	/*#poststuff h3 {
				  background-color: #ee6e73;
				  color: #fff;
				}*/
				.wp-core-ui .button-primary {
				  background: #26a69a;
				  border-color: #26a69a;
				  -webkit-box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12);
				  box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12);
				  color: #fff;
				  text-decoration: none;
				}
				.wp-core-ui .button-primary.focus, .wp-core-ui .button-primary.hover, .wp-core-ui .button-primary:focus, .wp-core-ui .button-primary:hover {
				  background: #2bbbad;
				  border-color: #2bbbad;
				  -webkit-box-shadow: 0 5px 11px 0 rgba(0, 0, 0, 0.18), 0 4px 15px 0 rgba(0, 0, 0, 0.15);
				  box-shadow: 0 5px 11px 0 rgba(0, 0, 0, 0.18), 0 4px 15px 0 rgba(0, 0, 0, 0.15);
				  color: #fff;
				}
				.btnsx-tabs nav li.tab-current a {
				  color: #ee6e73;
				}
				#all-plugins-table .plugins a.delete, #media-items a.delete, #media-items a.delete-permanently, #nav-menu-footer .menu-delete, #search-plugins-table .plugins a.delete, .plugins a.delete, .row-actions span.delete a, .row-actions span.spam a, .row-actions span.trash a, .submitbox .submitdelete {
				  color: #ee6e73;
				}
	        </style>
	        <?php
	        endif;
	        if( $btnsx_settings['wp_admin_theme'] == true ) :
	        ?>
	        <style type="text/css">
	        	/*#poststuff h3 {
				  background-color: <?php echo $_wp_admin_css_colors[$current_color]->colors[2]; ?>;
				  color: #fff;
				}*/
				.btnsx-tabs nav li.tab-current a {
				  color: <?php echo sanitize_text_field( $_wp_admin_css_colors[$current_color]->colors[3] ); ?>;
				}
				.ball-pulse>div {
				  background-color: <?php echo sanitize_text_field( $_wp_admin_css_colors[$current_color]->colors[3] ); ?>;
				}
				.btnsx .collapsible-header.active i {
					color: <?php echo sanitize_text_field( $_wp_admin_css_colors[$current_color]->colors[3] ); ?>;
				}
	        </style>
	        <?php
	        endif;
	        ?>
	        <!-- Overlay -->
	        <div id="btnsx-preview-overlay"></div>
	        <!-- Loader -->
	        <div id="btnsx-preview-container" class="btnsx">
	        	<div id="btnsx-preview-loader" class="loader-inner ball-pulse">
	        		<div></div>
	        		<div></div>
	        		<div></div>
	        	</div>
	        	<style type="text/css" id="btnsx-preview-btn-css"></style>
	        </div>

	    	<?php
	    }

	    /**
	     * Function to properly return gradient values
	     * @since  0.1
	     * @param  int    	 $post_id
	     * @param  string    $meta_key - post meta key
	     * @param  string    $id - form fields id prefix
	     * @param  string    $label - Collapsible header text
	     * @param  int    	 $key  -  count
	     * @param  array     $fields  -  gradient value keys
	     * @return array
	     */
		public function gradient_options( $post_id, $meta_key = 'btnsx_gradient_stop_normal', $id = 'btnsx_opt_gradient_stop_normal', $label = '', $key = 0, $fields = array()  ) {

			$defaults = array(
				'color'			=>	'',
				'location'		=>	'',
				'copy_text' 	=> '',
				'copy_ids' 		=>	array(),
				'copy_class'	=>	''
			);

			$result = array_merge( $defaults, $fields );

        	$step = $key + 1;
    		$gradient_options = array(
				'id'			=>	$id . $step,
				'text'			=>	__( $label, 'buttons-x' ),
				'multiple'		=>	true,
				'clone_class'	=>	$meta_key,
				'copy'			=>	true,
				'copy_text'		=>	$result['copy_text'],
				'copy_ids'		=>	$result['copy_ids'],
				'copy_class'	=>	$result['copy_class'],
				'elements'		=> array(
					array(
						'type'			=>	'gradient',
						'id'			=>	$id . $step,
						'name'			=>	$id,
						'placeholder'	=>	' ',
						'tooltip'		=>	array(
							'color' 		=> __( 'Set gradient stop color.', 'buttons-x' ),
					    	'location' 		=> __( 'Set gradient stop location. Must be between 0 and 100.', 'buttons-x' )
						),
					    'value' 		=> array(
					    	'color' 		=> $result['color'],
					    	'location' 		=> $result['location']
					    )
					)
				)
			);
			return $gradient_options;
		}

	    /**
	     * Function to properly return box shadow values
	     * @since  0.1
	     * @param  int    	 $post_id
	     * @param  string    $meta_key - post meta key
	     * @param  string    $id - form fields id prefix
	     * @param  string    $label - Collapsible header text
	     * @param  int    	 $key  -  count
	     * @param  array     $fields  -  box shadow keys
	     * @return array
	     */
		public function box_shadow_options( $post_id, $meta_key = 'btnsx_box_shadow_normal', $id = 'btnsx_opt_box_shadow_normal', $label = '', $key = 0, $fields = array()  ) {

			$defaults = array(
				'horizontal'	=>	'',
				'vertical'		=>	'',
				'blur'			=>	'',
				'spread'		=>	'',
				'position'		=>	'',
				'color'			=>	'',
				'copy_text'		=>	'',
				'copy_ids'		=>	array()
			);

			$result = array_merge( $defaults, $fields );

        	$step = $key + 1;
    		$box_shadow_options = array(
				'id'			=>	$id . $step,
				'text'			=>	__( $label, 'buttons-x' ),
				'multiple'		=>	true,
				'clone_class'	=>	$meta_key,
				'copy'			=>	true,
				'copy_text'		=>	$result['copy_text'],
				'copy_ids'		=>	$result['copy_ids'],
				'elements'		=> array(
					array(
						'type'			=>	'box-shadow',
						'id'			=>	$id . $step,
						'name'			=>	$id,
						'placeholder'	=>	' ',
						'tooltip'		=>	array(
							'horizontal' 	=> __( 'Set horizontal shadow in pixels.', 'buttons-x' ),
					    	'vertical' 		=> __( 'Set vertical shadow in pixels.', 'buttons-x' ),
					    	'blur' 			=> __( 'Set blur radius in pixels.', 'buttons-x' ),
					    	'spread' 		=> __( 'Set shadow spread in pixels.', 'buttons-x' ),
					    	'position' 		=> __( 'Select shadow postion.', 'buttons-x' ),
					    	'color' 		=> __( 'Set shadow color.', 'buttons-x' ),
						),
					    'value' 		=> array(
					    	'horizontal' 	=> $result['horizontal'],
					    	'vertical' 		=> $result['vertical'],
					    	'blur' 			=> $result['blur'],
					    	'spread' 		=> $result['spread'],
					    	'position' 		=> $result['position'],
					    	'color' 		=> $result['color']
					    )
					)
				)
			);
			return $box_shadow_options;
		}

		/**
		 * Fetch meta values
		 * @since  0.1
		 * @param  int   	$post_id ID for the current post/page.
		 * @param  array    $params
		 * @return string
		 */
		public function meta_values( $post_id, $params = array( 'field' => '', 'field2' => '', 'value' => '' ) ) {
			/*
	         * Use get_post_meta() to retrieve an existing value
	         * from the database and use the value for the form.
	         */
	        $meta_values = array();
	        $meta_values = get_post_meta( $post_id, 'btnsx', true );

	        // meta key value
	        $field = isset( $params['field'] ) ? $params['field'] : '';
	        // meta key value in case stored as an array
	        $field2 = isset( $params['field2'] ) ? $params['field2'] : '';
	        // value in case meta value is not defined
	        $value = isset( $params['value'] ) ? $params['value'] : '';

	        // Based on defined parameters set value
	        if ( $field != '' && $field2 == '' ){
	        	$meta_value = isset( $meta_values[$field] ) ? $meta_values[$field] : $value;
	        } elseif ( $field != '' && $field2 != '' ) {
	        	$meta_value = isset( $meta_values[$field][$field2] ) ? $meta_values[$field][$field2] : $value;
	        } else {
	        	$meta_value = '';
	        }

	        return $meta_value;
		}

	    /**
	     * Prints the box content.
	     * @param WP_Post $post The object for the current post/page.
	     * @since 0.1
	     */
	    public function options_callback( $post ) {

	        // Add an nonce field so we can check for it later.
	        wp_nonce_field( 'btnsx', 'btnsx_options_nonce' );

	        /*
	         * Use get_post_meta() to retrieve an existing value
	         * from the database and use the value for the form.
	         */

	        	// Custom Options
			        $meta_values = array();
			        $meta_values = get_post_meta( $post->ID, 'btnsx', true );

			        // Configure Box Shadow Options
			        $box_shadow_options = array();
			        $shadow_key = 'btnsx_box_shadow_normal';
			        $shadow_id  = 'btnsx_opt_box_shadow_normal';
			        if( isset( $meta_values[$shadow_key]['horizontal'] ) && $meta_values[$shadow_key]['horizontal'] != '' ){
			        	$shadow_horizontal 	= unserialize( $meta_values[$shadow_key]['horizontal'] );
			        	$shadow_vertical 	= unserialize( $meta_values[$shadow_key]['vertical'] );
			        	$shadow_blur 		= unserialize( $meta_values[$shadow_key]['blur'] );
			        	$shadow_spread 		= unserialize( $meta_values[$shadow_key]['spread'] );
			        	$shadow_position 	= unserialize( $meta_values[$shadow_key]['position'] );
			        	$shadow_color 		= unserialize( $meta_values[$shadow_key]['color'] );
			        	$bsStep = 1;
			        	foreach( $shadow_horizontal as $key => $label ){
			        		$fields = array(
			        			'horizontal'	=>	isset( $shadow_horizontal[$key] ) ? $shadow_horizontal[$key] : '',
								'vertical'		=>	isset( $shadow_vertical[$key] ) ? $shadow_vertical[$key] : '',
								'blur'			=>	isset( $shadow_blur[$key] ) ? $shadow_blur[$key] : '',
								'spread'		=>	isset( $shadow_spread[$key] ) ? $shadow_spread[$key] : '',
								'position'		=>	isset( $shadow_position[$key] ) ? $shadow_position[$key] : '',
								'color'			=>	isset( $shadow_color[$key] ) ? $shadow_color[$key] : '',
								'copy_text'		=>	'hover',
								'copy_ids'		=>	array(
									'highlight'		=>	'#btnsx_opt_box_shadow_hover'.$bsStep.'_header,#btnsx_opt_box_shadow_hover'.$bsStep.'_body',
									'old_input'		=>	'#btnsx_opt_box_shadow_normal'.$bsStep.'_horizontal,#btnsx_opt_box_shadow_normal'.$bsStep.'_vertical,#btnsx_opt_box_shadow_normal'.$bsStep.'_blur,#btnsx_opt_box_shadow_normal'.$bsStep.'_spread',
									'new_input'		=>	'#btnsx_opt_box_shadow_hover'.$bsStep.'_horizontal,#btnsx_opt_box_shadow_hover'.$bsStep.'_vertical,#btnsx_opt_box_shadow_hover'.$bsStep.'_blur,#btnsx_opt_box_shadow_hover'.$bsStep.'_spread',
									'old_select'	=>	'#btnsx_opt_box_shadow_normal'.$bsStep.'_position',
									'new_select'	=>	'#btnsx_opt_box_shadow_hover'.$bsStep.'_position',
									'old_color'		=>	'#btnsx_opt_box_shadow_normal'.$bsStep.'_color',
									'new_color'		=>	'#btnsx_opt_box_shadow_hover'.$bsStep.'_color'
								),
			        		);
			        		$box_shadow_options[] 	= $this->box_shadow_options( $post->ID, $shadow_key, $shadow_id, 'Normal', $key, $fields );
			        		$bsStep++;
			        	}
			        } else {
			        	$fields = array(
		        			'horizontal'	=>	'',
							'vertical'		=>	'',
							'blur'			=>	'',
							'spread'		=>	'',
							'position'		=>	'',
							'color'			=>	'',
							'copy_text'		=>	'hover',
							'copy_ids'		=>	array(
								'highlight'		=>	'#btnsx_opt_box_shadow_hover1_header,#btnsx_opt_box_shadow_hover1_body',
								'old_input'		=>	'#btnsx_opt_box_shadow_normal1_horizontal,#btnsx_opt_box_shadow_normal1_vertical,#btnsx_opt_box_shadow_normal1_blur,#btnsx_opt_box_shadow_normal1_spread',
								'new_input'		=>	'#btnsx_opt_box_shadow_hover1_horizontal,#btnsx_opt_box_shadow_hover1_vertical,#btnsx_opt_box_shadow_hover1_blur,#btnsx_opt_box_shadow_hover1_spread',
								'old_select'	=>	'#btnsx_opt_box_shadow_normal1_position',
								'new_select'	=>	'#btnsx_opt_box_shadow_hover1_position',
								'old_color'		=>	'#btnsx_opt_box_shadow_normal1_color',
								'new_color'		=>	'#btnsx_opt_box_shadow_hover1_color'
							),
		        		);
			        	$box_shadow_options[] = $this->box_shadow_options( $post->ID, $shadow_key, $shadow_id, __( 'Normal', 'buttons-x' ), 0, $fields );
			        }
			        $shadow_key_hover = 'btnsx_box_shadow_hover';
			        $shadow_id_hover  = 'btnsx_opt_box_shadow_hover';
			        if( isset( $meta_values[$shadow_key_hover]['horizontal'] ) && $meta_values[$shadow_key_hover]['horizontal'] != '' ){
			        	$shadow_horizontal 	= unserialize( $meta_values[$shadow_key_hover]['horizontal'] );
			        	$shadow_vertical 	= unserialize( $meta_values[$shadow_key_hover]['vertical'] );
			        	$shadow_blur 		= unserialize( $meta_values[$shadow_key_hover]['blur'] );
			        	$shadow_spread 		= unserialize( $meta_values[$shadow_key_hover]['spread'] );
			        	$shadow_position 	= unserialize( $meta_values[$shadow_key_hover]['position'] );
			        	$shadow_color 		= unserialize( $meta_values[$shadow_key_hover]['color'] );
			        	$bsStep = 1;
			        	foreach( $shadow_horizontal as $key => $label ){
			        		$fields = array(
			        			'horizontal'	=>	isset( $shadow_horizontal[$key] ) ? $shadow_horizontal[$key] : '',
								'vertical'		=>	isset( $shadow_vertical[$key] ) ? $shadow_vertical[$key] : '',
								'blur'			=>	isset( $shadow_blur[$key] ) ? $shadow_blur[$key] : '',
								'spread'		=>	isset( $shadow_spread[$key] ) ? $shadow_spread[$key] : '',
								'position'		=>	isset( $shadow_position[$key] ) ? $shadow_position[$key] : '',
								'color'			=>	isset( $shadow_color[$key] ) ? $shadow_color[$key] : '',
								'copy_text'		=>	'normal',
								'copy_ids'		=>	array(
									'highlight'		=>	'#btnsx_opt_box_shadow_normal'.$bsStep.'_header,#btnsx_opt_box_shadow_normal'.$bsStep.'_body',
									'old_input'		=>	'#btnsx_opt_box_shadow_hover'.$bsStep.'_horizontal,#btnsx_opt_box_shadow_hover'.$bsStep.'_vertical,#btnsx_opt_box_shadow_hover'.$bsStep.'_blur,#btnsx_opt_box_shadow_hover'.$bsStep.'_spread',
									'new_input'		=>	'#btnsx_opt_box_shadow_normal'.$bsStep.'_horizontal,#btnsx_opt_box_shadow_normal'.$bsStep.'_vertical,#btnsx_opt_box_shadow_normal'.$bsStep.'_blur,#btnsx_opt_box_shadow_normal'.$bsStep.'_spread',
									'old_select'	=>	'#btnsx_opt_box_shadow_hover'.$bsStep.'_position',
									'new_select'	=>	'#btnsx_opt_box_shadow_normal'.$bsStep.'_position',
									'old_color'		=>	'#btnsx_opt_box_shadow_hover'.$bsStep.'_color',
									'new_color'		=>	'#btnsx_opt_box_shadow_normal'.$bsStep.'_color'
								),
			        		);
			        		$box_shadow_options[] 	= $this->box_shadow_options( $post->ID, $shadow_key_hover, $shadow_id_hover, 'Hover', $key, $fields );
			        	}
			        } else {
			        	$fields = array(
		        			'horizontal'	=>	'',
							'vertical'		=>	'',
							'blur'			=>	'',
							'spread'		=>	'',
							'position'		=>	'',
							'color'			=>	'',
							'copy_text'		=>	'normal',
							'copy_ids'		=>	array(
								'highlight'		=>	'#btnsx_opt_box_shadow_normal1_header,#btnsx_opt_box_shadow_normal1_body',
								'old_input'		=>	'#btnsx_opt_box_shadow_hover1_horizontal,#btnsx_opt_box_shadow_hover1_vertical,#btnsx_opt_box_shadow_hover1_blur,#btnsx_opt_box_shadow_hover1_spread',
								'new_input'		=>	'#btnsx_opt_box_shadow_normal1_horizontal,#btnsx_opt_box_shadow_normal1_vertical,#btnsx_opt_box_shadow_normal1_blur,#btnsx_opt_box_shadow_normal1_spread',
								'old_select'	=>	'#btnsx_opt_box_shadow_hover1_position',
								'new_select'	=>	'#btnsx_opt_box_shadow_normal1_position',
								'old_color'		=>	'#btnsx_opt_box_shadow_hover1_color',
								'new_color'		=>	'#btnsx_opt_box_shadow_normal1_color'
							),
		        		);
			        	$box_shadow_options[] = $this->box_shadow_options( $post->ID, $shadow_key_hover, $shadow_id_hover, __( 'Hover', 'buttons-x' ), 0, $fields );
			        }
					$box_shadow_options[] = array(
						'text'		=>	__( 'CSS', 'buttons-x' ),
						'elements'		=> array(
							array(
								'type'			=>	'pro-banner'
		    				)
						)
					);

					// Configure Gradient Options
					$gradient_options = array();
					// type
					$gradient_options[] = array(
						'text'		=>	__( 'Type', 'buttons-x' ),
						'id'		=>	'btnsx_gradient_type_normal_collapsible',
						'elements'		=> array(
							array(
								'type'			=>	'select',
								'id'			=>	'btnsx_opt_gradient_type_normal',
								'name'			=>	'btnsx_opt_gradient_type_normal',
								'label'			=>	__( 'Normal', 'buttons-x' ),
								'tooltip'		=>	__( 'Select gradient type.', 'buttons-x' ),
								'options'		=>	array(
									'vertical'		=>	__( 'Vertical ↓', 'buttons-x' ),
									'none'			=>	__( '[ 4 more types are available in Pro version ]', 'buttons-x' )
								),
								'value'			=>	$this->meta_values( $post->ID, array( 'field' => 'btnsx_gradient_type_normal' ) ),
								'copy'			=>	true,
								'copy_text'		=>	'hover',
								'copy_ids'		=>	array(
									'highlight'		=>	'#btnsx_gradient_type_normal_collapsible_body',
									'old_select'	=>	'#btnsx_opt_gradient_type_normal',
									'new_select'	=>	'#btnsx_opt_gradient_type_hover'
								)
							),
							array(
								'type'			=>	'select',
								'id'			=>	'btnsx_opt_gradient_type_hover',
								'name'			=>	'btnsx_opt_gradient_type_hover',
								'label'			=>	__( 'Hover', 'buttons-x' ),
								'tooltip'		=>	__( 'Select gradient type for when button will be hovered.', 'buttons-x' ),
								'options'		=>	array(
									'vertical'			=>	__( 'Vertical ↓', 'buttons-x' ),
									'none'				=>	__( '[ 4 more types are available in Pro version ]', 'buttons-x' )
								),
								'value'			=>	$this->meta_values( $post->ID, array( 'field' => 'btnsx_gradient_type_hover' ) ),
								'copy'			=>	true,
								'copy_text'		=>	'normal',
								'copy_ids'		=>	array(
									'highlight'		=>	'#btnsx_gradient_type_normal_collapsible_body',
									'old_select'	=>	'#btnsx_opt_gradient_type_hover',
									'new_select'	=>	'#btnsx_opt_gradient_type_normal'
								)
							),
						)
					);
			        $gradient_key = 'btnsx_gradient_stop_normal';
			        $gradient_id  = 'btnsx_opt_gradient_stop_normal';
			        if( isset( $meta_values[$gradient_key]['color'] ) && $meta_values[$gradient_key]['color'] != '' ){
			        	$gradient_color 	= unserialize( $meta_values[$gradient_key]['color'] );
			        	$gradient_location 	= unserialize( $meta_values[$gradient_key]['location'] );
			        	$gStep = 1;
			        	foreach( $gradient_color as $key => $label ){
			        		$fields = array(
			        			'color'		=>	isset( $gradient_color[$key] ) ? $gradient_color[$key] : '',
								'location'	=>	isset( $gradient_location[$key] ) ? $gradient_location[$key] : '',
								'copy_text'		=>	'hover',
								'copy_ids'		=>	array(
									'highlight'		=>	'#btnsx_opt_gradient_stop_hover'.$gStep.'_header,#btnsx_opt_gradient_stop_hover'.$gStep.'_body',
									'old_input'		=>	'#btnsx_opt_gradient_stop_normal'.$gStep.'_location',
									'new_input'		=>	'#btnsx_opt_gradient_stop_hover'.$gStep.'_location',
									'old_color'		=>	'#btnsx_opt_gradient_stop_normal'.$gStep.'_color',
									'new_color'		=>	'#btnsx_opt_gradient_stop_hover'.$gStep.'_color'
								),
								'copy_class'		=>	'gradient-normal-copy',
			        		);
			        		$gradient_options[] = $this->gradient_options( $post->ID, $gradient_key, $gradient_id, 'Stop (Normal)', $key, $fields );
			        		$gStep++;
			        	}
			        } else {
			        	$fields = array(
		        			'color'		=>	'',
							'location'	=>	'',
							'copy_text'		=>	'hover',
							'copy_ids'		=>	array(
								'highlight'		=>	'#btnsx_opt_gradient_stop_hover1_header,#btnsx_opt_gradient_stop_hover1_body',
								'old_input'		=>	'#btnsx_opt_gradient_stop_normal1_location',
								'new_input'		=>	'#btnsx_opt_gradient_stop_hover1_location',
								'old_color'		=>	'#btnsx_opt_gradient_stop_normal1_color',
								'new_color'		=>	'#btnsx_opt_gradient_stop_hover1_color'
							),
							'copy_class'		=>	'gradient-normal-copy',
		        		);
			        	$gradient_options[] 	= $this->gradient_options( $post->ID, $gradient_key, $gradient_id, __( 'Stop (Normal)', 'buttons-x' ), 0, $fields );
			        }
			        $gradient_key_hover = 'btnsx_gradient_stop_hover';
			        $gradient_id_hover  = 'btnsx_opt_gradient_stop_hover';
			        if( isset( $meta_values[$gradient_key_hover]['color'] ) && $meta_values[$gradient_key_hover]['color'] != '' ){
			        	$gradient_color 	= unserialize( $meta_values[$gradient_key_hover]['color'] );
			        	$shadow_location 	= unserialize( $meta_values[$gradient_key_hover]['location'] );
			        	$gStep = 1;
			        	foreach( $gradient_color as $key => $label ){
			        		$fields = array(
			        			'color'		=>	isset( $gradient_color[$key] ) ? $gradient_color[$key] : '',
								'location'	=>	isset( $shadow_location[$key] ) ? $shadow_location[$key] : '',
								'copy_text'		=>	'normal',
								'copy_ids'		=>	array(
									'highlight'		=>	'#btnsx_opt_gradient_stop_normal'.$gStep.'_header,#btnsx_opt_gradient_stop_normal'.$gStep.'_body',
									'old_input'		=>	'#btnsx_opt_gradient_stop_hover'.$gStep.'_location',
									'new_input'		=>	'#btnsx_opt_gradient_stop_normal'.$gStep.'_location',
									'old_color'		=>	'#btnsx_opt_gradient_stop_hover'.$gStep.'_color',
									'new_color'		=>	'#btnsx_opt_gradient_stop_normal'.$gStep.'_color'
								),
								'copy_class'		=>	'gradient-hover-copy',
			        		);
			        		$gradient_options[] 	= $this->gradient_options( $post->ID, $gradient_key_hover, $gradient_id_hover, __( 'Stop (Hover)', 'buttons-x' ), $key, $fields );
			        		$gStep++;
			        	}
			        } else {
			        	$fields = array(
		        			'color'		=>	'',
							'location'	=>	'',
							'copy_text'		=>	'normal',
							'copy_ids'		=>	array(
								'highlight'		=>	'#btnsx_opt_gradient_stop_normal1_header,#btnsx_opt_gradient_stop_normal1_body',
								'old_input'		=>	'#btnsx_opt_gradient_stop_hover1_location',
								'new_input'		=>	'#btnsx_opt_gradient_stop_normal1_location',
								'old_color'		=>	'#btnsx_opt_gradient_stop_hover1_color',
								'new_color'		=>	'#btnsx_opt_gradient_stop_normal1_color'
							),
							'copy_class'		=>	'gradient-hover-copy',
		        		);
			        	$gradient_options[] 	= $this->gradient_options( $post->ID, $gradient_key_hover, $gradient_id_hover, __( 'Stop (Hover)', 'buttons-x' ), 0, $fields );
			        }
			        // css
					$gradient_options[] = array(
						'text'		=>	__( 'CSS', 'buttons-x' ),
						'elements'		=> array(
							array(
								'type'			=>	'pro-banner'
		    				)
						)
					);

				// tab
				echo '<input type="hidden" id="btnsx_opt_tab" name="btnsx_opt_tab" value="' . $this->meta_values( $post->ID, array( 'field' => 'btnsx_tab' ) ) . '">';
				// echo '<input type="hidden" id="btnsx_opt_tab_content" name="btnsx_opt_tab_content" value="' . $this->meta_values( $post->ID, array( 'field' => 'btnsx_tab_content' ) ) . '">';
				// tab groups
				echo '<input type="hidden" id="btnsx_opt_tab_group_content" name="btnsx_opt_tab_group_content" value="' . $this->meta_values( $post->ID, array( 'field' => 'btnsx_tab_group_content', 'value' => '0' ) ) . '">';
				echo '<input type="hidden" id="btnsx_opt_tab_group_style" name="btnsx_opt_tab_group_style" value="' . $this->meta_values( $post->ID, array( 'field' => 'btnsx_tab_group_style', 'value' => '0' ) ) . '">';
				echo '<input type="hidden" id="btnsx_opt_tab_group_advanced" name="btnsx_opt_tab_group_advanced" value="' . $this->meta_values( $post->ID, array( 'field' => 'btnsx_tab_group_advanced', 'value' => '0' ) ) . '">';
				echo '<input type="hidden" id="btnsx_opt_tab_group_expert" name="btnsx_opt_tab_group_expert" value="' . $this->meta_values( $post->ID, array( 'field' => 'btnsx_tab_group_expert', 'value' => '0' ) ) . '">';

				echo '<div class="btnsx"><div id="btnsx-options-loader" class="loader-inner ball-pulse"><div></div><div></div><div></div></div></div>';

        	$btnsx_form_design = new BtnsxFormDesign();
        	$btnsx_default_options = array(
				array(
					'icon_class'	=>	'fa fa-magic',
					'text'			=>	__( 'Animations', 'buttons-x' ),
					'group'			=>	'advanced',
					'elements'		=>	array(
						array(
							'type'			=>	'pro-banner'
	    				)
					),
					'inner_group'	=>	array(
						array(
							'text'			=>	__( 'Reveal Animations', 'buttons-x' ),
							'elements'		=> array(
								array(
									'type'			=>	'pro-banner'
			    				)
							)
						),
						array(
							'text'			=>	__( 'Hover Animations', 'buttons-x' ),
							'elements'		=> array(
								array(
									'type'			=>	'pro-banner'
			    				)
							)
						)
					)
				),
				array(
					'icon_class'	=>	'fa fa-image',
					'text'			=>	__( 'Background', 'buttons-x' ),
					'group'			=>	'style',
					'inner_group'	=>	array(
						array(
							'text'			=>	__( 'Color', 'buttons-x' ),
							'id'			=>	'btnsx_background_1',
							'elements'		=> array(
								array(
									'type'			=>	'color-states',
									'id'			=>	'btnsx_opt_background_color',
									'name'			=>	'btnsx_opt_background_color',
									'placeholder'	=>	' ',
									'tooltip'			=>	array(
										'normal'		=>	__( 'Set button background color.', 'buttons-x' ),
										'hover'			=>	__( 'Set button background color for when the button will be hovered.', 'buttons-x' )
									),
									'value'			=>	array(
										'normal'		=>	$this->meta_values( $post->ID, array( 'field' => 'btnsx_background_color', 'field2' => 'normal', 'value' => '#f4f4f4' ) ),
										'hover'			=>	$this->meta_values( $post->ID, array( 'field' => 'btnsx_background_color', 'field2' => 'hover', 'value' => '#e8e8e8' ) )
									),
									'copy'			=>	true,
									'copy_text'		=>	array(
										'normal'	=>	'hover',
										'hover'		=>	'normal'
									),
									'copy_ids'		=>	array(
										'normal'	=>	array(
											'highlight'		=>	'#btnsx_background_1_body',
											'old_color'		=>	'#btnsx_opt_background_color_normal',
											'new_color'		=>	'#btnsx_opt_background_color_hover'
										),
										'hover'	=>	array(
											'highlight'		=>	'#btnsx_background_1_body',
											'old_color'		=>	'#btnsx_opt_background_color_hover',
											'new_color'		=>	'#btnsx_opt_background_color_normal'
										)
									)
								),
							)
						),
						array(
							'text'			=>	__( 'Image', 'buttons-x' ),
							'id'			=>	'btnsx_background_image_collapsible',
							'elements'		=> array(
								array(
									'type'			=>	'pro-banner'
			    				)
							)
						)
					)
				),
				array(
					'icon_class'	=>	'fa fa-square-o',
					'text'			=>	__( 'Border', 'buttons-x' ),
					'group'			=>	'style',
					'inner_group'	=>	array(
						array(
							'text'			=>	__( 'Normal', 'buttons-x' ),
							'id'			=>	'btnsx_border_normal_collapsible',
							'copy'			=>	true, // copy enabled
							'copy_text'		=>	'hover', // copy to hover
							'copy_ids'		=>	array(
								'highlight'		=>	'#btnsx_border_hover_collapsible_header, #btnsx_border_hover_collapsible_body',
								'old_input'		=>	'#btnsx_opt_border_normal_size,#btnsx_opt_border_normal_top,#btnsx_opt_border_normal_bottom,#btnsx_opt_border_normal_left,#btnsx_opt_border_normal_right',
								'new_input'		=>	'#btnsx_opt_border_hover_size,#btnsx_opt_border_hover_top,#btnsx_opt_border_hover_bottom,#btnsx_opt_border_hover_left,#btnsx_opt_border_hover_right',
								'old_select'	=>	'#btnsx_opt_border_normal_style',
								'new_select'	=>	'#btnsx_opt_border_hover_style',
								'old_color'		=>	'#btnsx_opt_border_normal_color',
								'new_color'		=>	'#btnsx_opt_border_hover_color'
							), // store our fields, comma separated values
							'elements'		=>	array(
								array(
								    'type'			=>	'border',
								    'id'			=>	'btnsx_opt_border_normal',
								    'name'			=>	'btnsx_opt_border_normal',
								    'placeholder'	=>	' ',
								    'tooltip'		=>	array(
										'size'			=>	__( 'Set border size in pixels.', 'buttons-x' ),
										'style'			=>	__( 'Set border style.', 'buttons-x' ),
										'color'			=>	__( 'Set border color.', 'buttons-x' ),
										'top'			=>	__( 'Enable/Disable top border.', 'buttons-x' ),
										'bottom'		=>	__( 'Enable/Disable bottom border.', 'buttons-x' ),
										'left'			=>	__( 'Enable/Disable left border.', 'buttons-x' ),
										'right'			=>	__( 'Enable/Disable right border.', 'buttons-x' )
									),
								    'value' 		=> array(
								    	'size' 			=> $this->meta_values( $post->ID, array( 'field' => 'btnsx_border_normal', 'field2' => 'size', 'value' => '0' ) ),
								    	'style' 		=> $this->meta_values( $post->ID, array( 'field' => 'btnsx_border_normal', 'field2' => 'style', 'value' => 'none' ) ),
								    	'color' 		=> $this->meta_values( $post->ID, array( 'field' => 'btnsx_border_normal', 'field2' => 'color' ) ),
								    	'top' 			=> $this->meta_values( $post->ID, array( 'field' => 'btnsx_border_normal', 'field2' => 'top', 'value' => '1' ) ),
								    	'bottom' 		=> $this->meta_values( $post->ID, array( 'field' => 'btnsx_border_normal', 'field2' => 'bottom', 'value' => '1' ) ),
								    	'left' 			=> $this->meta_values( $post->ID, array( 'field' => 'btnsx_border_normal', 'field2' => 'left', 'value' => '1' ) ),
								    	'right' 		=> $this->meta_values( $post->ID, array( 'field' => 'btnsx_border_normal', 'field2' => 'right', 'value' => '1' ) )
								    )
							    ),
							)
						),
						array(
							'text'			=>	__( 'Hover', 'buttons-x' ),
							'id'			=>	'btnsx_border_hover_collapsible',
							'copy'			=>	true,
							'copy_text'		=>	'normal',
							'copy_ids'		=>	array(
								'highlight'		=>	'#btnsx_border_normal_collapsible_header, #btnsx_border_normal_collapsible_body',
								'old_input'		=>	'#btnsx_opt_border_hover_size,#btnsx_opt_border_hover_top,#btnsx_opt_border_hover_bottom,#btnsx_opt_border_hover_left,#btnsx_opt_border_hover_right',
								'new_input'		=>	'#btnsx_opt_border_normal_size,#btnsx_opt_border_normal_top,#btnsx_opt_border_normal_bottom,#btnsx_opt_border_normal_left,#btnsx_opt_border_normal_right',
								'old_select'	=>	'#btnsx_opt_border_hover_style',
								'new_select'	=>	'#btnsx_opt_border_normal_style',
								'old_color'		=>	'#btnsx_opt_border_hover_color',
								'new_color'		=>	'#btnsx_opt_border_normal_color'
							),
							'elements'		=>	array(
								array(
								    'type'			=>	'border',
								    'id'			=>	'btnsx_opt_border_hover',
								    'name'			=>	'btnsx_opt_border_hover',
								    'placeholder'	=>	' ',
								    'tooltip'		=>	array(
										'size'			=>	__( 'Set border size in pixels for when button is hovered.', 'buttons-x' ),
										'style'			=>	__( 'Set border style for when button is hovered.', 'buttons-x' ),
										'color'			=>	__( 'Set border colour for when button is hovered.', 'buttons-x' ),
										'top'			=>	__( 'Enable/Disable top border.', 'buttons-x' ),
										'bottom'		=>	__( 'Enable/Disable bottom border.', 'buttons-x' ),
										'left'			=>	__( 'Enable/Disable left border.', 'buttons-x' ),
										'right'			=>	__( 'Enable/Disable right border.', 'buttons-x' )
									),
								    'value' 		=> array(
								    	'size' 			=> $this->meta_values( $post->ID, array( 'field' => 'btnsx_border_hover', 'field2' => 'size', 'value' => '0' ) ),
								    	'style' 		=> $this->meta_values( $post->ID, array( 'field' => 'btnsx_border_hover', 'field2' => 'style', 'value' => 'none' ) ),
								    	'color' 		=> $this->meta_values( $post->ID, array( 'field' => 'btnsx_border_hover', 'field2' => 'color' ) ),
								    	'top' 			=> $this->meta_values( $post->ID, array( 'field' => 'btnsx_border_hover', 'field2' => 'top', 'value' => '1' ) ),
								    	'bottom' 		=> $this->meta_values( $post->ID, array( 'field' => 'btnsx_border_hover', 'field2' => 'bottom', 'value' => '1' ) ),
								    	'left' 			=> $this->meta_values( $post->ID, array( 'field' => 'btnsx_border_hover', 'field2' => 'left', 'value' => '1' ) ),
								    	'right' 		=> $this->meta_values( $post->ID, array( 'field' => 'btnsx_border_hover', 'field2' => 'right', 'value' => '1' ) )
								    )
							    ),
							)
						),
						array(
							'text'			=>	__( 'Radius (Normal) ', 'buttons-x' ),
							'id'			=>	'btnsx_border_normal_radius_collapsible',
							'copy'			=>	true,
							'copy_text'		=>	'hover',
							'copy_ids'		=>	array(
								'highlight'		=>	'#btnsx_border_hover_radius_collapsible_header, #btnsx_border_hover_radius_collapsible_body',
								'old_input'		=>	'#btnsx_opt_border_normal_radius_top_left,#btnsx_opt_border_normal_radius_top_right,#btnsx_opt_border_normal_radius_bottom_left,#btnsx_opt_border_normal_radius_bottom_right',
								'new_input'		=>	'#btnsx_opt_border_hover_radius_top_left,#btnsx_opt_border_hover_radius_top_right,#btnsx_opt_border_hover_radius_bottom_left,#btnsx_opt_border_hover_radius_bottom_right'
							),
							'elements'		=>	array(
								array(
								    'type'		=>	'radius',
								    'id'		=>	'btnsx_opt_border_normal_radius',
								    'name'		=>	'btnsx_opt_border_normal_radius',
								    'tooltip'		=>	array(
										'top_left'			=>	__( 'Set top left border radius in pixels.', 'buttons-x' ),
										'top_right'			=>	__( 'Set top right border radius in pixels.', 'buttons-x' ),
										'bottom_left'		=>	__( 'Set bottom left border radius in pixels.', 'buttons-x' ),
										'bottom_right'		=>	__( 'Set bottom right border radius in pixels.', 'buttons-x' )
									),
								    'value' 	=> array(
								    	'top_left' 		=> $this->meta_values( $post->ID, array( 'field' => 'btnsx_border_normal_radius', 'field2' => 'top_left', 'value' => '0' ) ),
								    	'top_right' 	=> $this->meta_values( $post->ID, array( 'field' => 'btnsx_border_normal_radius', 'field2' => 'top_right', 'value' => '0' ) ),
								    	'bottom_left' 	=> $this->meta_values( $post->ID, array( 'field' => 'btnsx_border_normal_radius', 'field2' => 'bottom_left', 'value' => '0' ) ),
								    	'bottom_right' 	=> $this->meta_values( $post->ID, array( 'field' => 'btnsx_border_normal_radius', 'field2' => 'bottom_right', 'value' => '0' ) )
								    )
							    ),
							)
						),
						array(
							'text'			=>	__( 'Radius (Hover) ', 'buttons-x' ),
							'id'			=>	'btnsx_border_hover_radius_collapsible',
							'copy'			=>	true,
							'copy_text'		=>	'normal',
							'copy_ids'		=>	array(
								'highlight'		=>	'#btnsx_border_normal_radius_collapsible_header, #btnsx_border_normal_radius_collapsible_body',
								'old_input'		=>	'#btnsx_opt_border_hover_radius_top_left,#btnsx_opt_border_hover_radius_top_right,#btnsx_opt_border_hover_radius_bottom_left,#btnsx_opt_border_hover_radius_bottom_right',
								'new_input'		=>	'#btnsx_opt_border_normal_radius_top_left,#btnsx_opt_border_normal_radius_top_right,#btnsx_opt_border_normal_radius_bottom_left,#btnsx_opt_border_normal_radius_bottom_right'
							),
							'elements'		=>	array(
								array(
								    'type'		=>	'radius',
								    'id'		=>	'btnsx_opt_border_hover_radius',
								    'name'		=>	'btnsx_opt_border_hover_radius',
								    'tooltip'		=>	array(
										'top_left'			=>	__( 'Set top left border radius in pixels for when button is hovered.', 'buttons-x' ),
										'top_right'			=>	__( 'Set top right border radius in pixels for when button is hovered.', 'buttons-x' ),
										'bottom_left'		=>	__( 'Set bottom left border radius in pixels for when button is hovered.', 'buttons-x' ),
										'bottom_right'		=>	__( 'Set bottom right border radius in pixels for when button is hovered.', 'buttons-x' )
									),
								    'value' 	=> array(
								    	'top_left' 		=> $this->meta_values( $post->ID, array( 'field' => 'btnsx_border_hover_radius', 'field2' => 'top_left', 'value' => '0' ) ),
								    	'top_right' 	=> $this->meta_values( $post->ID, array( 'field' => 'btnsx_border_hover_radius', 'field2' => 'top_right', 'value' => '0' ) ),
								    	'bottom_left' 	=> $this->meta_values( $post->ID, array( 'field' => 'btnsx_border_hover_radius', 'field2' => 'bottom_left', 'value' => '0' ) ),
								    	'bottom_right' 	=> $this->meta_values( $post->ID, array( 'field' => 'btnsx_border_hover_radius', 'field2' => 'bottom_right', 'value' => '0' ) )
								    )
							    ),
							)
						)
					)
				),
				array(
        			'icon_class'	=>	'fa fa-columns',
        			'text'			=>	__( 'Layout', 'buttons-x' ),
        			'group'			=>	'style',
        			'elements'		=> array(
						array(
							'type'			=>	'hidden',
							'id'			=>	'btnsx_opt_id',
							'name'			=>	'btnsx_opt_id',
							'label'			=>	__( 'ID', 'buttons-x' ),
							'value'			=>	$post->ID,
						),
						array(
							'type'			=>	'range',
							'id'			=>	'btnsx_opt_width',
							'name'			=>	'btnsx_opt_width',
							'placeholder'	=>	' ',
							'label'			=>	__( 'Width', 'buttons-x' ),
							'tooltip'		=>	__( 'Add button width in pixels. This gives the button a fixed width. Clearing the field removes fixed width.', 'buttons-x' ),
							'value'			=>	$this->meta_values( $post->ID, array( 'field' => 'btnsx_width' ) )
						),
						array(
							'type'			=>	'range',
							'id'			=>	'btnsx_opt_height',
							'name'			=>	'btnsx_opt_height',
							'placeholder'	=>	' ',
							'label'			=>	__( 'Height', 'buttons-x' ),
							'tooltip'		=>	__( 'Add button height in pixels. This gives the button a fixed height. Clearing the field removes fixed height.', 'buttons-x' ),
							'value'			=>	$this->meta_values( $post->ID, array( 'field' => 'btnsx_height' ) )
						),
						array(
							'type'			=>	'select',
							'id'			=>	'btnsx_opt_size',
							'name'			=>	'btnsx_opt_size',
							'placeholder'	=>	__( 'Choose size', 'buttons-x' ),
							'label'			=>	__( 'Size', 'buttons-x' ),
							'tooltip'		=>	__( 'These are some preset button sizes. Changing the size value automatically adjusts the font size and padding values of primary text.', 'buttons-x' ),
							'options'		=>	array(
								'huge'	=> __( 'Huge', 'buttons-x' ),
								'large'	=> __( 'Large', 'buttons-x' ),
								'wide'	=> __( 'Wide', 'buttons-x' ),
								'small'	=> __( 'Small', 'buttons-x' ),
								'mini'	=> __( 'Mini', 'buttons-x' )
							),
							'value'			=>	$this->meta_values( $post->ID, array( 'field' => 'btnsx_size' ) )
						),
						array(
							'type'			=>	'checkbox',
							'id'			=>	'btnsx_opt_disabled',
							'name'			=>	'btnsx_opt_disabled',
							'label'			=>	__( 'Disabled', 'buttons-x' ),
							'tooltip'		=>	__( 'Make the button disabled by checking this field. Clicking the button won\'t be possible once disabled.', 'buttons-x' ),
							'class'			=>	'btnsx-checkbox',
							'value'			=>	$this->meta_values( $post->ID, array( 'field' => 'btnsx_disabled', 'value' => '0' ) )
						),
						array(
							'type'			=>	'checkbox',
							'id'			=>	'btnsx_opt_embossed',
							'name'			=>	'btnsx_opt_embossed',
							'label'			=>	__( 'Embossed', 'buttons-x' ),
							'tooltip'		=>	__( 'Checking this field gives an embossed effect to button.', 'buttons-x' ),
							'class'			=>	'btnsx-checkbox',
							'value'			=>	$this->meta_values( $post->ID, array( 'field' => 'btnsx_embossed', 'value' => '0' ) )
						),
						array(
							'type'			=>	'checkbox',
							'id'			=>	'btnsx_opt_full_width',
							'name'			=>	'btnsx_opt_full_width',
							'label'			=>	__( 'Full Width', 'buttons-x' ),
							'tooltip'		=>	__( 'Make the button cover the entire width of the container.', 'buttons-x' ),
							'class'			=>	'btnsx-checkbox',
							'value'			=>	$this->meta_values( $post->ID, array( 'field' => 'btnsx_full_width', 'value' => '0' ) )
						),
						array(
							'type'			=>	'checkbox',
							'id'			=>	'btnsx_opt_container',
							'name'			=>	'btnsx_opt_container',
							'label'			=>	__( 'Container', 'buttons-x' ),
							'tooltip'		=>	__( 'Add a container div to button.', 'buttons-x' ),
							'class'			=>	'btnsx-checkbox',
							'value'			=>	$this->meta_values( $post->ID, array( 'field' => 'btnsx_container', 'value' => '0' ) )
						),
						array(
							'type'			=>	'checkbox',
							'id'			=>	'btnsx_opt_wrap_center',
							'name'			=>	'btnsx_opt_wrap_center',
							'label'			=>	__( 'Wrap Center', 'buttons-x' ),
							'tooltip'		=>	__( 'Makes the button centered. Very helpful when you want the button to be centered inside a big container.', 'buttons-x' ),
							'class'			=>	'btnsx-checkbox',
							'value'			=>	$this->meta_values( $post->ID, array( 'field' => 'btnsx_wrap_center', 'value' => '0' ) )
						)
					)
				),
				array(
        			'icon_class'	=>	'fa fa-link',
        			'text'			=>	__( 'Link', 'buttons-x' ),
        			'group'			=>	'content',
        			'elements'		=> array(
						array(
							'type'			=>	'select',
							'id'			=>	'btnsx_opt_link_type',
							'name'			=>	'btnsx_opt_link_type',
							'placeholder'	=>	__( 'Choose type', 'buttons-x' ),
							'label'			=>	__( 'Type', 'buttons-x' ),
							'tooltip'		=>	__( 'Choose the type of link required for the button.', 'buttons-x' ),
							'options'		=>	array(
								'none'				=> __( 'None', 'buttons-x' ),
								'url'				=> __( 'URL', 'buttons-x' ),
								'pro'				=> __( '[ 44 more link types supported in PRO version. ]', 'buttons-x' )
							),
							'value'			=>	$this->meta_values( $post->ID, array( 'field' => 'btnsx_link_type' ) )
						),
						array(
							'type'			=>	'select',
							'id'			=>	'btnsx_opt_link_target',
							'name'			=>	'btnsx_opt_link_target',
							'placeholder'	=>	__( 'Choose target', 'buttons-x' ),
							'label'			=>	__( 'Target', 'buttons-x' ),
							'tooltip'		=>	__( 'Where would you like to open the new link? New window or Same Window?', 'buttons-x' ),
							'options'		=>	array(
								'new_window'	=> __( 'New Window', 'buttons-x' ),
								'same_window'	=> __( 'Same Window', 'buttons-x' )
							),
							'value'			=>	$this->meta_values( $post->ID, array( 'field' => 'btnsx_link_target' ) )
						),
						array(
							'type'			=>	'text',
							'id'			=>	'btnsx_opt_link',
							'name'			=>	'btnsx_opt_link',
							'placeholder'	=>	' ',
							'label'			=>	__( 'URL', 'buttons-x' ),
							'tooltip'		=>	__( 'Add a custom URL.', 'buttons-x' ),
							'value'			=>	$this->meta_values( $post->ID, array( 'field' => 'btnsx_link' ) )
						)
					)
        		),
				array(
        			'icon_class'	=>	'fa fa-arrows',
        			'text'			=>	__( 'Margin', 'buttons-x' ),
        			'group'			=>	'style',
        			'elements'		=> array(
						array(
							'type'			=>	'trbl',
							'id'			=>	'btnsx_opt_margin',
							'name'			=>	'btnsx_opt_margin',
							'placeholder'	=>	'',
							'tooltip'		=>	array(
								'all'			=>	__( 'Set margin for all sides. This field is not saved. It should be used to apply same margin value to all sides.', 'buttons-x' ),
								'top'			=>	__( 'Set top margin for button.', 'buttons-x' ),
								'bottom'		=>	__( 'Set bottom margin for button.', 'buttons-x' ),
								'left'			=>	__( 'Set left margin for button.', 'buttons-x' ),
								'right'			=>	__( 'Set right margin for button.', 'buttons-x' ),
							),
							'value'			=>	array(
								'top'			=>	$this->meta_values( $post->ID, array( 'field' => 'btnsx_margin', 'field2' => 'top', 'value' => '0' ) ),
								'bottom'		=>	$this->meta_values( $post->ID, array( 'field' => 'btnsx_margin', 'field2' => 'bottom', 'value' => '0' ) ),
								'left'			=>	$this->meta_values( $post->ID, array( 'field' => 'btnsx_margin', 'field2' => 'left', 'value' => '0' ) ),
								'right'			=>	$this->meta_values( $post->ID, array( 'field' => 'btnsx_margin', 'field2' => 'right', 'value' => '0' ) )

							)
						)
					)
        		),
        		array(
        			'icon_class'	=>	'fa fa-arrows-alt',
        			'text'			=>	__( 'Padding', 'buttons-x' ),
        			'group'			=>	'style',
					'elements'		=> array(
						array(
							'type'			=>	'trbl',
							'id'			=>	'btnsx_opt_padding',
							'name'			=>	'btnsx_opt_padding',
							'placeholder'	=>	'',
							'tooltip'		=>	array(
								'all'			=>	__( 'Set padding for all sides. This field is not saved. It should be used to apply same padding value to all sides.', 'buttons-x' ),
								'top'			=>	__( 'Set top padding for button.', 'buttons-x' ),
								'bottom'		=>	__( 'Set bottom padding for button.', 'buttons-x' ),
								'left'			=>	__( 'Set left padding for button.', 'buttons-x' ),
								'right'			=>	__( 'Set right padding for button.', 'buttons-x' ),
							),
							'value'			=>	array(
								'top'			=>	$this->meta_values( $post->ID, array( 'field' => 'btnsx_padding', 'field2' => 'top', 'value' => '10' ) ),
								'bottom'		=>	$this->meta_values( $post->ID, array( 'field' => 'btnsx_padding', 'field2' => 'bottom', 'value' => '10' ) ),
								'left'			=>	$this->meta_values( $post->ID, array( 'field' => 'btnsx_padding', 'field2' => 'left', 'value' => '40' ) ),
								'right'			=>	$this->meta_values( $post->ID, array( 'field' => 'btnsx_padding', 'field2' => 'right', 'value' => '40' ) )
							)
						)
					)
        		),
				array(
        			'icon_class'	=>	'fa fa-css3',
        			'text'			=>	__( 'Custom CSS', 'buttons-x' ),
        			'group'			=>	'expert',
					'elements'		=> array(
						array(
							'type'		=>	'pro-banner'
						)
					)
        		),
        		array(
        			'icon_class'	=>	'fa fa-code',
        			'text'			=>	__( 'Custom JS', 'buttons-x' ),
        			'group'			=>	'expert',
					'elements'		=> array(
						array(
							'type'		=>	'pro-banner'
						)
					)
        		),
        		array(
					'icon_class'	=>	'fa fa-adjust',
					'text'			=>	__( 'Gradient', 'buttons-x' ),
					'group'			=>	'advanced',
					'elements'			=>	array(
        				array(
        					'type'		=>	'gradient-limit'
        				)
        			),
					'inner_group'	=>	$gradient_options
				),
        		array(
					'icon_class'	=>	'fa fa-rocket',
					'text'			=>	__( 'Icon', 'buttons-x' ),
					'group'			=>	'style',
					'elements'		=>	array(
						array(
							'type'			=>	'pro-banner'
	    				)
					),
					'inner_group'	=>	array(
						array(
							'text'			=>	__( 'Main', 'buttons-x' ),
							'elements'		=> array(
								array(
									'type'			=>	'pro-banner'
			    				)
							)
						),
						array(
							'text'			=>	__( 'Color', 'buttons-x' ),
							'id'			=>	'btnsx_icon_color_collapsible',
							'elements'		=>	array(
								array(
									'type'			=>	'pro-banner'
			    				)
							)
						),
						array(
							'text'			=>	__( 'Image', 'buttons-x' ),
							'id'			=>	'btnsx_icon_image_collapsible',
							'elements'		=>	array(
								array(
									'type'			=>	'pro-banner'
			    				)
							)
						),
						array(
							'text'			=>	__( 'Image Position', 'buttons-x' ),
							'id'			=>	'btnsx-icon-image-position-tab',
							'elements'		=> array(
								array(
									'type'			=>	'pro-banner'
			    				)
							)
						),
						array(
							'text'			=>	__( 'Shadow', 'buttons-x' ),
							'elements'		=> array(
								array(
									'type'			=>	'pro-banner'
			    				)
							)
						),
						array(
							'text'			=>	__( 'Divider', 'buttons-x' ),
							'elements'		=> array(
								array(
									'type'			=>	'pro-banner'
			    				)
							)
						),
						array(
							'text'			=>	__( 'Animation', 'buttons-x' ),
							'elements'		=>	array(
								array(
									'type'			=>	'pro-banner'
			    				)
							)
						),
						array(
							'text'			=>	__( 'Padding', 'buttons-x' ),
							'elements'		=> array(
								array(
									'type'			=>	'pro-banner'
			    				)
							)
						)
					)
				),
				array(
					'icon_class'	=>	'fa fa-pencil-square',
					'text'			=>	__( 'Primary Text', 'buttons-x' ),
					'group'			=>	'content',
					'inner_group'	=>	array(
						array(
							'text'			=>	__( 'Text', 'buttons-x' ),
							'id'			=>	'btnsx_primary_text_1',
							'elements'		=>	array(
								array(
									'type'			=>	'text',
									'id'			=>	'btnsx_opt_text',
									'name'			=>	'btnsx_opt_text',
									'label'			=>	__( 'Text', 'buttons-x' ),
									'tooltip'		=>	__( 'Add main button text.', 'buttons-x' ),
									'value'			=>	$this->meta_values( $post->ID, array( 'field' => 'btnsx_text', 'value' => 'Button' ) )
								),
								array(
									'type'			=>	'select',
									'id'			=>	'btnsx_opt_text_transform',
									'name'			=>	'btnsx_opt_text_transform',
									'placeholder'	=>	__( 'Choose option', 'buttons-x' ),
									'label'			=>	__( 'Transform', 'buttons-x' ),
									'tooltip'		=>	__( 'This field controls the capitalization of text.', 'buttons-x' ),
									'options'		=>	array(
										''				=> __( 'None', 'buttons-x' ),
										'capitalize'	=> __( 'Capitalize', 'buttons-x' ),
										'uppercase'		=> __( 'Uppercase', 'buttons-x' ),
										'lowercase'		=> __( 'Lowercase', 'buttons-x' ),
										'inherit'		=> __( 'Inherit', 'buttons-x' )
									),
									'value'			=>	$this->meta_values( $post->ID, array( 'field' => 'btnsx_text_transform' ) )
								)
							)
						),
						array(
							'text'			=>	__( 'Font', 'buttons-x' ),
							'elements'		=> array(
								array(
	    							'type'			=>	'font',
	    							'id'			=>	'btnsx_opt_text_font',
	    							'name'			=>	'btnsx_opt_text_font',
	    							'placeholder'	=>	' ',
	    							'tooltip'		=>	array(
	    								'size'			=>	__( 'Add font size in pixels. This will make your primary text bigger or smaller depending up on the value specified.', 'buttons-x' ),
	    								'style'			=>	__( 'Select font style. This will style your primary text. Normal means as it is, italic means the text will appear slanted, oblique makes the text slanted + bold and inherit will get the style from parent element.', 'buttons-x' ),
	    								'weight'		=>	__( 'Select font weight. This adds weight to text like bold makes the text stronger. 100 is lightest and 900 is boldest.', 'buttons-x' ),
	    								'family'		=>	__( 'Select font family. Choose from among 100\'s of Google Web Fonts to style primary text.', 'buttons-x' )
	    							),
	    							'value'			=>	array(
	    								'size'			=>	$this->meta_values( $post->ID, array( 'field' => 'btnsx_text_font', 'field2' => 'size', 'value' => '21' ) ),
	    								'style'			=>	$this->meta_values( $post->ID, array( 'field' => 'btnsx_text_font', 'field2' => 'style', 'value' => 'normal' ) ),
	    								'weight'		=>	$this->meta_values( $post->ID, array( 'field' => 'btnsx_text_font', 'field2' => 'weight', 'value' => 'normal' ) ),
	    								'family'		=>	$this->meta_values( $post->ID, array( 'field' => 'btnsx_text_font', 'field2' => 'family' ) )
	    							)
	    						)
							)
						),
						array(
							'text'			=>	__( 'Color', 'buttons-x' ),
							'id'			=>	'btnsx_text_color_collapsible',
							'elements'		=> array(
								array(
	    							'type'			=>	'color-states',
	    							'id'			=>	'btnsx_opt_text_color',
	    							'name'			=>	'btnsx_opt_text_color',
	    							'placeholder'	=>	' ',
	    							'tooltip'		=>	array(
	    								'normal'		=>	__( 'Select text color.', 'buttons-x' ),
	    								'hover'			=>	__( 'Select text color for when button is hovered.', 'buttons-x' )
	    							),
	    							'value'			=>	array(
	    								'normal'		=>	$this->meta_values( $post->ID, array( 'field' => 'btnsx_text_color', 'field2' => 'normal', 'value' => '#999' ) ),
	    								'hover'			=>	$this->meta_values( $post->ID, array( 'field' => 'btnsx_text_color', 'field2' => 'hover', 'value' => '#777' ) )
	    							),
	    							'copy'			=>	true,
									'copy_text'		=>	array(
										'normal'	=>	'hover',
										'hover'		=>	'normal'
									),
									'copy_ids'		=>	array(
										'normal' => array(
											'highlight'		=>	'#btnsx_text_color_collapsible_body',
											'old_color'		=>	'#btnsx_opt_text_color_normal',
											'new_color'		=>	'#btnsx_opt_text_color_hover'
										),
										'hover' => array(
											'highlight'		=>	'#btnsx_text_color_collapsible_body',
											'old_color'		=>	'#btnsx_opt_text_color_hover',
											'new_color'		=>	'#btnsx_opt_text_color_normal'
										)
									)
	    						)
							)
						),
						array(
							'text'			=>	__( 'Shadow', 'buttons-x' ),
							'elements'		=> array(
								array(
									'type'			=>	'pro-banner'
			    				)
							)
						),
						array(
							'text'			=>	__( 'Padding', 'buttons-x' ),
							'elements'		=> array(
								array(
        							'type'			=>	'trbl',
        							'id'			=>	'btnsx_opt_text_padding',
        							'name'			=>	'btnsx_opt_text_padding',
        							'placeholder'	=>	'',
        							'tooltip'		=>	array(
        								'all'			=>	__( 'Set padding for all sides. This field is not saved. It should be used to apply same padding value to all sides.', 'buttons-x' ),
        								'top'			=>	__( 'Set top padding for primary text.', 'buttons-x' ),
        								'bottom'		=>	__( 'Set bottom padding for primary text.', 'buttons-x' ),
        								'left'			=>	__( 'Set left padding for primary text.', 'buttons-x' ),
        								'right'			=>	__( 'Set right padding for primary text.', 'buttons-x' ),
        							),
        							'value'			=>	array(
	    								'top'			=>	$this->meta_values( $post->ID, array( 'field' => 'btnsx_text_padding', 'field2' => 'top', 'value' => '0' ) ),
	    								'bottom'		=>	$this->meta_values( $post->ID, array( 'field' => 'btnsx_text_padding', 'field2' => 'bottom', 'value' => '0' ) ),
	    								'left'			=>	$this->meta_values( $post->ID, array( 'field' => 'btnsx_text_padding', 'field2' => 'left', 'value' => '0' ) ),
	    								'right'			=>	$this->meta_values( $post->ID, array( 'field' => 'btnsx_text_padding', 'field2' => 'right', 'value' => '0' ) )
	    							),
        						)
							)
						)
					)
				),
				array(
					'icon_class'	=>	'fa fa-pencil-square-o',
					'text'			=>	__( 'Secondary Text', 'buttons-x' ),
					'group'			=>	'content',
					'elements'		=>	array(
						array(
							'type'			=>	'pro-banner'
	    				)
					),
					'inner_group'	=>	array(
						array(
							'text'			=>	__( 'Text', 'buttons-x' ),
							'elements'		=>	array(
								array(
									'type'			=>	'pro-banner'
			    				)
							)
						),
						array(
							'text'			=>	__( 'Font', 'buttons-x' ),
							'elements'		=> array(
								array(
									'type'			=>	'pro-banner'
			    				)
							)
						),
						array(
							'text'			=>	__( 'Color', 'buttons-x' ),
							'id'			=>	'btnsx_text_secondary_color_collapsible',
							'elements'		=> array(
								array(
									'type'			=>	'pro-banner'
			    				)
							)
						),
						array(
							'text'			=>	__( 'Shadow', 'buttons-x' ),
							'elements'		=> array(
								array(
									'type'			=>	'pro-banner'
			    				)
							)
						),
						array(
							'text'			=>	__( 'Padding', 'buttons-x' ),
							'elements'		=> array(
								array(
									'type'			=>	'pro-banner'
			    				)
							)
						)
					)
				),
				array(
					'icon_class'	=>	'fa fa-cube',
					'group'			=>	'advanced',
					'text'			=>	__( 'Shadow', 'buttons-x' ),
					'elements'			=>	array(
        				array(
        					'type'		=>	'shadow-limit'
        				)
        			),
					'inner_group'	=>	$box_shadow_options
				)
	        );

			// filter to add custom options
        	$btnsx_filtered_options = apply_filters( 'btnsx_options_filter', array(), $btnsx_default_options );
        	$btnsx_options = wp_parse_args( $btnsx_filtered_options, $btnsx_default_options );
        	$btnsx_form_design->tabs(
        		array(
        			'id'				=>	'btnsx-tabs',
        			'show_group'		=>	true,
        			'outer_group'		=>	$btnsx_options
        		)
        	);

        	echo '<p style="position:absolute;bottom:-30px;color:#9aa1a9;font-size:11px;"><em>' . sprintf( wp_kses(__( 'If you like Buttons X, please give us a <a href="%s">*****</a> rating!', 'buttons-x'), array( 'a' => array( 'href' => array() ) ) ), 'https://wordpress.org/support/plugin/buttons-x/reviews/?rate=5#new-post' ) . '</em></p>';
	    }

	    /**
	     * When the post is saved, saves our custom data.
	     * @param int $post->ID_id The ID of the post being saved.
	     * @since 0.1
	     */
	    public function save_data( $post_id ) {

	        /*
	         * We need to verify this came from our screen and with proper authorization,
	         * because the save_post action can be triggered at other times.
	         */

	        // Check if our nonce is set.
	        if ( !isset( $_POST[ 'btnsx_options_nonce' ] ) ) {
	            return;
	        }

	        // Verify that the nonce is valid.
	        if ( !wp_verify_nonce( $_POST[ 'btnsx_options_nonce' ], 'btnsx' ) ) {
	            return;
	        }

	        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
	        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
	            return;
	        }

	        // Check the user's permissions.
	        if ( isset( $_POST[ 'post_type' ] ) && 'page' == $_POST[ 'post_type' ] ) {
	            if ( !current_user_can( 'edit_page', $post_id ) ) {
	                return;
	            }
	        } else {
	            if ( !current_user_can( 'edit_post', $post_id ) ) {
	                return;
	            }
	        }

	        /* OK, its safe for us to save the data now. */

	        $btnsx_data = array();

	        // Sanitize user input.
	        $btnsx_data = array(
	        	// General
		        	'btnsx_preview_background' 			=> sanitize_text_field( isset( $_POST['btnsx_opt_preview_background'] ) ? $_POST['btnsx_opt_preview_background'] : '' ),
		            'btnsx_id' 							=> sanitize_text_field( isset( $_POST['btnsx_opt_id'] ) ? $_POST['btnsx_opt_id'] : '' ),
		            'btnsx_width' 						=> sanitize_text_field( isset( $_POST['btnsx_opt_width'] ) ? $_POST['btnsx_opt_width'] : '' ),
		            'btnsx_height' 						=> sanitize_text_field( isset( $_POST['btnsx_opt_height'] ) ? $_POST['btnsx_opt_height'] : '' ),
		            'btnsx_size' 						=> sanitize_text_field( isset( $_POST['btnsx_opt_size'] ) ? $_POST['btnsx_opt_size'] : '' ),
		            'btnsx_disabled' 					=> sanitize_text_field( isset( $_POST['btnsx_opt_disabled'] ) ? $_POST['btnsx_opt_disabled'] : '' ),
		            'btnsx_embossed' 					=> sanitize_text_field( isset( $_POST['btnsx_opt_embossed'] ) ? $_POST['btnsx_opt_embossed'] : '' ),
		            'btnsx_container' 					=> sanitize_text_field( isset( $_POST['btnsx_opt_container'] ) ? $_POST['btnsx_opt_container'] : '' ),
		            'btnsx_wrap_center' 				=> sanitize_text_field( isset( $_POST['btnsx_opt_wrap_center'] ) ? $_POST['btnsx_opt_wrap_center'] : '' ),
		            'btnsx_full_width' 					=> sanitize_text_field( isset( $_POST['btnsx_opt_full_width'] ) ? $_POST['btnsx_opt_full_width'] : '' ),
		            'btnsx_link_type' 					=> sanitize_text_field( isset( $_POST['btnsx_opt_link_type'] ) ? $_POST['btnsx_opt_link_type'] : '' ),
		            'btnsx_link_target' 				=> sanitize_text_field( isset( $_POST['btnsx_opt_link_target'] ) ? $_POST['btnsx_opt_link_target'] : '' ),
		            'btnsx_link_relationship' 			=> sanitize_text_field( isset( $_POST['btnsx_opt_link_relationship'] ) ? $_POST['btnsx_opt_link_relationship'] : '' ),
		            'btnsx_link' 						=> esc_url_raw( isset( $_POST['btnsx_opt_link'] ) ? $_POST['btnsx_opt_link'] : '' ),
		            'btnsx_margin' 						=> array(
		            	'top' 								=> sanitize_text_field( isset( $_POST['btnsx_opt_margin_top'] ) ? $_POST['btnsx_opt_margin_top'] : '' ),
		            	'right' 							=> sanitize_text_field( isset( $_POST['btnsx_opt_margin_right'] ) ? $_POST['btnsx_opt_margin_right'] : '' ),
		            	'bottom' 							=> sanitize_text_field( isset( $_POST['btnsx_opt_margin_bottom'] ) ? $_POST['btnsx_opt_margin_bottom'] : '' ),
		            	'left' 								=> sanitize_text_field( isset( $_POST['btnsx_opt_margin_left'] ) ? $_POST['btnsx_opt_margin_left'] : '' ),
		            ),
		            'btnsx_padding' 					=> array(
		            	'top' 								=> sanitize_text_field( isset( $_POST['btnsx_opt_padding_top'] ) ? $_POST['btnsx_opt_padding_top'] : '' ),
		            	'right' 							=> sanitize_text_field( isset( $_POST['btnsx_opt_padding_right'] ) ? $_POST['btnsx_opt_padding_right'] : '' ),
		            	'bottom' 							=> sanitize_text_field( isset( $_POST['btnsx_opt_padding_bottom'] ) ? $_POST['btnsx_opt_padding_bottom'] : '' ),
		            	'left' 								=> sanitize_text_field( isset( $_POST['btnsx_opt_padding_left'] ) ? $_POST['btnsx_opt_padding_left'] : '' ),
		            ),

	            // Primary Text
		            'btnsx_text' 						=> sanitize_text_field( isset( $_POST['btnsx_opt_text'] ) ? $_POST['btnsx_opt_text'] : '' ),
		            'btnsx_text_transform' 				=> sanitize_text_field( isset( $_POST['btnsx_opt_text_transform'] ) ? $_POST['btnsx_opt_text_transform'] : '' ),
		            'btnsx_text_font' 					=> array(
		            	'size' 								=> sanitize_text_field( isset( $_POST['btnsx_opt_text_font_size'] ) ? $_POST['btnsx_opt_text_font_size'] : '' ),
		            	'style' 							=> sanitize_text_field( isset( $_POST['btnsx_opt_text_font_style'] ) ? $_POST['btnsx_opt_text_font_style'] : '' ),
		            	'weight' 							=> sanitize_text_field( isset( $_POST['btnsx_opt_text_font_weight'] ) ? $_POST['btnsx_opt_text_font_weight'] : '' ),
		            	'family' 							=> sanitize_text_field( isset( $_POST['btnsx_opt_text_font_family'] ) ? $_POST['btnsx_opt_text_font_family'] : '' )
		            ),
		            'btnsx_text_color' 					=> array(
		            	'normal' 							=> sanitize_text_field( isset( $_POST['btnsx_opt_text_color_normal'] ) ? $_POST['btnsx_opt_text_color_normal'] : '' ),
		            	'hover' 							=> sanitize_text_field( isset( $_POST['btnsx_opt_text_color_hover'] ) ? $_POST['btnsx_opt_text_color_hover'] : '' )
		            ),
		            'btnsx_text_padding' 				=> array(
		            	'top' 								=> sanitize_text_field( isset( $_POST['btnsx_opt_text_padding_top'] ) ? $_POST['btnsx_opt_text_padding_top'] : '' ),
		            	'bottom' 							=> sanitize_text_field( isset( $_POST['btnsx_opt_text_padding_bottom'] ) ? $_POST['btnsx_opt_text_padding_bottom'] : '' ),
		            	'left' 								=> sanitize_text_field( isset( $_POST['btnsx_opt_text_padding_left'] ) ? $_POST['btnsx_opt_text_padding_left'] : '' ),
		            	'right' 							=> sanitize_text_field( isset( $_POST['btnsx_opt_text_padding_right'] ) ? $_POST['btnsx_opt_text_padding_right'] : '' )
		            ),

	            // Background
		            'btnsx_background_color' 			=> array(
		            	'normal' 							=> sanitize_text_field( isset( $_POST['btnsx_opt_background_color_normal'] ) ? $_POST['btnsx_opt_background_color_normal'] : '' ),
		            	'hover' 							=> sanitize_text_field( isset( $_POST['btnsx_opt_background_color_hover'] ) ? $_POST['btnsx_opt_background_color_hover'] : '' )
		            ),

	            // Gradient
	            	'btnsx_gradient_type_normal' 		=> sanitize_text_field( isset( $_POST['btnsx_opt_gradient_type_normal'] ) ? $_POST['btnsx_opt_gradient_type_normal'] : '' ),
		            'btnsx_gradient_type_hover' 		=> sanitize_text_field( isset( $_POST['btnsx_opt_gradient_type_hover'] ) ? $_POST['btnsx_opt_gradient_type_hover'] : '' ),
		            'btnsx_gradient_stop_normal' 		=> array(
		            	'color' 							=> sanitize_text_field( isset( $_POST['btnsx_opt_gradient_stop_normal_color'] ) ? serialize( $_POST['btnsx_opt_gradient_stop_normal_color'] ) : '' ),
		            	'location' 							=> sanitize_text_field( isset( $_POST['btnsx_opt_gradient_stop_normal_location'] ) ? serialize(  $_POST['btnsx_opt_gradient_stop_normal_location'] ) : '' )
		            ),
		            'btnsx_gradient_stop_hover' 		=> array(
		            	'color' 							=> sanitize_text_field( isset( $_POST['btnsx_opt_gradient_stop_hover_color'] ) ? serialize( $_POST['btnsx_opt_gradient_stop_hover_color'] ) : '' ),
		            	'location' 							=> sanitize_text_field( isset( $_POST['btnsx_opt_gradient_stop_hover_location'] ) ? serialize( $_POST['btnsx_opt_gradient_stop_hover_location'] ) : '' )
		            ),

	            // Border
		            'btnsx_border_normal' 				=> array(
		            	'size' 								=> sanitize_text_field( isset( $_POST['btnsx_opt_border_normal_size'] ) ? $_POST['btnsx_opt_border_normal_size'] : '' ),
		            	'style' 							=> sanitize_text_field( isset( $_POST['btnsx_opt_border_normal_style'] ) ? $_POST['btnsx_opt_border_normal_style'] : '' ),
		            	'color' 							=> sanitize_text_field( isset( $_POST['btnsx_opt_border_normal_color'] ) ? $_POST['btnsx_opt_border_normal_color'] : '' ),
		            	'top' 							=> sanitize_text_field( isset( $_POST['btnsx_opt_border_normal_top'] ) ? $_POST['btnsx_opt_border_normal_top'] : '0' ),
		            	'bottom' 							=> sanitize_text_field( isset( $_POST['btnsx_opt_border_normal_bottom'] ) ? $_POST['btnsx_opt_border_normal_bottom'] : '0' ),
		            	'left' 							=> sanitize_text_field( isset( $_POST['btnsx_opt_border_normal_left'] ) ? $_POST['btnsx_opt_border_normal_left'] : '0' ),
		            	'right' 							=> sanitize_text_field( isset( $_POST['btnsx_opt_border_normal_right'] ) ? $_POST['btnsx_opt_border_normal_right'] : '0' ),
		            ),
		            'btnsx_border_hover' 				=> array(
		            	'size' 								=> sanitize_text_field( isset( $_POST['btnsx_opt_border_hover_size'] ) ? $_POST['btnsx_opt_border_hover_size'] : '' ),
		            	'style' 							=> sanitize_text_field( isset( $_POST['btnsx_opt_border_hover_style'] ) ? $_POST['btnsx_opt_border_hover_style'] : '' ),
		            	'color' 							=> sanitize_text_field( isset( $_POST['btnsx_opt_border_hover_color'] ) ? $_POST['btnsx_opt_border_hover_color'] : '' ),
		            	'top' 							=> sanitize_text_field( isset( $_POST['btnsx_opt_border_hover_top'] ) ? $_POST['btnsx_opt_border_hover_top'] : '0' ),
		            	'bottom' 							=> sanitize_text_field( isset( $_POST['btnsx_opt_border_hover_bottom'] ) ? $_POST['btnsx_opt_border_hover_bottom'] : '0' ),
		            	'left' 							=> sanitize_text_field( isset( $_POST['btnsx_opt_border_hover_left'] ) ? $_POST['btnsx_opt_border_hover_left'] : '0' ),
		            	'right' 							=> sanitize_text_field( isset( $_POST['btnsx_opt_border_hover_right'] ) ? $_POST['btnsx_opt_border_hover_right'] : '0' ),
		            ),
		            'btnsx_border_normal_radius' 		=> array(
		            	'top_left' 							=> sanitize_text_field( isset( $_POST['btnsx_opt_border_normal_radius_top_left'] ) ? $_POST['btnsx_opt_border_normal_radius_top_left'] : '' ),
		            	'top_right' 						=> sanitize_text_field( isset( $_POST['btnsx_opt_border_normal_radius_top_right'] ) ? $_POST['btnsx_opt_border_normal_radius_top_right'] : '' ),
		            	'bottom_left' 						=> sanitize_text_field( isset( $_POST['btnsx_opt_border_normal_radius_bottom_left'] ) ? $_POST['btnsx_opt_border_normal_radius_bottom_left'] : '' ),
		            	'bottom_right' 						=> sanitize_text_field( isset( $_POST['btnsx_opt_border_normal_radius_bottom_right'] ) ? $_POST['btnsx_opt_border_normal_radius_bottom_right'] : '' )
		            ),
		            'btnsx_border_hover_radius' 		=> array(
		            	'top_left' 							=> sanitize_text_field( isset( $_POST['btnsx_opt_border_hover_radius_top_left'] ) ? $_POST['btnsx_opt_border_hover_radius_top_left'] : '' ),
		            	'top_right' 						=> sanitize_text_field( isset( $_POST['btnsx_opt_border_hover_radius_top_right'] ) ? $_POST['btnsx_opt_border_hover_radius_top_right'] : '' ),
		            	'bottom_left' 						=> sanitize_text_field( isset( $_POST['btnsx_opt_border_hover_radius_bottom_left'] ) ? $_POST['btnsx_opt_border_hover_radius_bottom_left'] : '' ),
		            	'bottom_right' 						=> sanitize_text_field( isset( $_POST['btnsx_opt_border_hover_radius_bottom_right'] ) ? $_POST['btnsx_opt_border_hover_radius_bottom_right'] : '' )
		            ),

	            // Shadow
		            'btnsx_box_shadow_normal' 			=> array(
		            	'horizontal' 						=> sanitize_text_field( isset( $_POST['btnsx_opt_box_shadow_normal_horizontal'] ) ? serialize( $_POST['btnsx_opt_box_shadow_normal_horizontal'] ) : '' ),
		            	'vertical' 							=> sanitize_text_field( isset( $_POST['btnsx_opt_box_shadow_normal_vertical'] ) ? serialize( $_POST['btnsx_opt_box_shadow_normal_vertical'] ) : '' ),
		            	'blur' 								=> sanitize_text_field( isset( $_POST['btnsx_opt_box_shadow_normal_blur'] ) ? serialize( $_POST['btnsx_opt_box_shadow_normal_blur'] ) : '' ),
		            	'spread' 							=> sanitize_text_field( isset( $_POST['btnsx_opt_box_shadow_normal_spread'] ) ? serialize( $_POST['btnsx_opt_box_shadow_normal_spread'] ) : '' ),
		            	'position' 							=> sanitize_text_field( isset( $_POST['btnsx_opt_box_shadow_normal_position'] ) ? serialize( $_POST['btnsx_opt_box_shadow_normal_position'] ) : '' ),
		            	'color' 							=> sanitize_text_field( isset( $_POST['btnsx_opt_box_shadow_normal_color'] ) ? serialize( $_POST['btnsx_opt_box_shadow_normal_color'] ) : '' )
		            ),
					'btnsx_box_shadow_hover' 			=> array(
		            	'horizontal' 						=> sanitize_text_field( isset( $_POST['btnsx_opt_box_shadow_hover_horizontal'] ) ? serialize( $_POST['btnsx_opt_box_shadow_hover_horizontal'] ) : '' ),
		            	'vertical' 							=> sanitize_text_field( isset( $_POST['btnsx_opt_box_shadow_hover_vertical'] ) ? serialize( $_POST['btnsx_opt_box_shadow_hover_vertical'] ) : '' ),
		            	'blur' 								=> sanitize_text_field( isset( $_POST['btnsx_opt_box_shadow_hover_blur'] ) ? serialize( $_POST['btnsx_opt_box_shadow_hover_blur'] ) : '' ),
		            	'spread' 							=> sanitize_text_field( isset( $_POST['btnsx_opt_box_shadow_hover_spread'] ) ? serialize( $_POST['btnsx_opt_box_shadow_hover_spread'] ) : '' ),
		            	'position' 							=> sanitize_text_field( isset( $_POST['btnsx_opt_box_shadow_hover_position'] ) ? serialize( $_POST['btnsx_opt_box_shadow_hover_position'] ) : '' ),
		            	'color' 							=> sanitize_text_field( isset( $_POST['btnsx_opt_box_shadow_hover_color'] ) ? serialize( $_POST['btnsx_opt_box_shadow_hover_color'] ) : '' )
		            ),

				// Tab
					'btnsx_tab'							=> sanitize_text_field( isset( $_POST['btnsx_opt_tab'] ) ? $_POST['btnsx_opt_tab'] : '' ),
					'btnsx_tab_content'					=> sanitize_text_field( isset( $_POST['btnsx_opt_tab_content'] ) ? $_POST['btnsx_opt_tab_content'] : '' ),
					'btnsx_tab_group_content'			=> sanitize_text_field( isset( $_POST['btnsx_opt_tab_group_content'] ) ? $_POST['btnsx_opt_tab_group_content'] : '' ),
					'btnsx_tab_group_style'				=> sanitize_text_field( isset( $_POST['btnsx_opt_tab_group_style'] ) ? $_POST['btnsx_opt_tab_group_style'] : '' ),
					'btnsx_tab_group_advanced'			=> sanitize_text_field( isset( $_POST['btnsx_opt_tab_group_advanced'] ) ? $_POST['btnsx_opt_tab_group_advanced'] : '' ),
					'btnsx_tab_group_expert'			=> sanitize_text_field( isset( $_POST['btnsx_opt_tab_group_expert'] ) ? $_POST['btnsx_opt_tab_group_expert'] : '' ),
	        );

			// filter to save custom options
			$btnsx_filtered_data = apply_filters( 'btnsx_save_data_filter', array(), $btnsx_data );
        	$btnsx_data_mixed = wp_parse_args( $btnsx_filtered_data, $btnsx_data );

        	//WMPL
		    /**
		     * register strings for translation
		     */
		    if( has_action('wpml_register_single_string') ) {
		    	do_action( 'wpml_register_single_string', 'btnsx-'.$post_id, 'Primary Text', $btnsx_data_mixed['btnsx_text'] );
		    }
		    //WMPL

        	// var_dump( $btnsx_data_mixed );
        	// wp_die();

	        // Update the meta field in the database.
	        update_post_meta( $post_id, 'btnsx', $btnsx_data_mixed );
	    }

	    /**
	     * Function to check whether the given value is serializable/unserializable without throwing error
	     * @since  0.1
	     * @param  mixed    $str
	     * @return boolean
	     */
	    public function is_serialized( $str ) {
	    	if( $str != '' ){
		    	return ( $str == serialize( false ) || @unserialize( $str ) !== false );
	    	} else {
		    	return false;
	    	}
		}

	    /**
	     * Function to check short code paramter values, if empty fetch values from meta data
	     * @since  0.1
	     * @param  string    $variable passed parameter value in short code
	     * @param  string    $id post ID
	     * @param  array     $field meta values function parameters
	     * @param  array     $type parameter use type. Used as css, class or attribute.
	     * @return string
	     */
	    public function empty_check_definition( $variable = '', $id = '', $field = array(), $type = array() ) {

	    	$defaults = array(
	    		'css' => false,
	    		'css_prefix' => '',
	    		'css_suffix' => '',
	    		'class' => false,
	    		'class_name' => '',
	    		'attribute' => false,
	    		'attribute_name' => ''
	    	);

	    	$type = array_merge( $defaults, $type );

	    	if( is_array( $field ) && $field != null ){
	    		// check if value is serialized
	    		$serializable = $this->is_serialized( $this->meta_values( $id, $field ) ); // @TODO - FIX - removed (string) before $this->meta..
	    		// if serialized, unserialize value
	    		$meta_value = $serializable != false ? unserialize( $this->meta_values( $id, $field ) ) : $this->meta_values( $id, $field );
	    		// store value
		    	$variable = $variable != '' ? $variable : $meta_value;

				if( $type['css'] === true && $type['class'] === false && $type['attribute'] === false ) {
					if( $variable != '' ){
						$variable = sanitize_text_field( $type['css_prefix'] ) . ':' . sanitize_text_field( $variable ) . sanitize_text_field( $type['css_suffix'] ) . ';';
					}
				}

				if( $type['css'] === false && $type['class'] === true && $type['attribute'] === false ) {
					if( $variable == '1' ){
						$variable = ' ' . esc_attr( $type['class_name'] );
					}
				}

				if( $type['css'] === false && $type['class'] === false && $type['attribute'] === true ) {
					if( $variable != '' ){
						$variable = ' ' . sanitize_text_field( $type['attribute_name'] ) . '="' . esc_attr( $variable ) . '"';
					}
				}

				return $variable;

			}
	    }

	    /**
	     * Function to generate general css styles
	     * @since  1.3
	     * @param  string    $normal Normal selector
	     * @param  boolean    $forced Force !important
	     * @param  boolean    $compact Don't use selectors
	     * @return string
	     */
	    public function general_css( $id, $normal, $forced, $compact ) {
	    	$css = '';$important = '';
	    	if( $forced === true ){
	    		$important = '!important';
	    	}
	    	if( $normal != '' ){
	    		$css .= ( $compact === true ) ? '' : $normal .'{';
	    		$css .= $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_width' ), array( 'css' => true, 'css_prefix' => 'width', 'css_suffix' => 'px' . $important ) );
	    		$css .= $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_height' ), array( 'css' => true, 'css_prefix' => 'height', 'css_suffix' => 'px' . $important ) );
	    		$css .= $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_margin', 'field2' => 'top' ), array( 'css' => true, 'css_prefix' => 'margin-top', 'css_suffix' => 'px' . $important ) );
	    		$css .= $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_margin', 'field2' => 'bottom' ), array( 'css' => true, 'css_prefix' => 'margin-bottom', 'css_suffix' => 'px' . $important ) );
	    		$css .= $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_margin', 'field2' => 'left' ), array( 'css' => true, 'css_prefix' => 'margin-left', 'css_suffix' => 'px' . $important ) );
	    		$css .= $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_margin', 'field2' => 'right' ), array( 'css' => true, 'css_prefix' => 'margin-right', 'css_suffix' => 'px' . $important ) );
				$css .= $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_padding', 'field2' => 'top' ), array( 'css' => true, 'css_prefix' => 'padding-top', 'css_suffix' => 'px' . $important ) );
	    		$css .= $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_padding', 'field2' => 'bottom' ), array( 'css' => true, 'css_prefix' => 'padding-bottom', 'css_suffix' => 'px' . $important ) );
	    		$css .= $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_padding', 'field2' => 'left' ), array( 'css' => true, 'css_prefix' => 'padding-left', 'css_suffix' => 'px' . $important ) );
	    		$css .= $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_padding', 'field2' => 'right' ), array( 'css' => true, 'css_prefix' => 'padding-right', 'css_suffix' => 'px' . $important ) );
	    		$css .= ( $compact === true ) ? '' : '}' . PHP_EOL;
	    	}
			return $css;
	    }

	    /**
	     * Function to generate primary text css styles
	     * @since  1.3
	     * @param  string    $normal Normal selector
	     * @param  boolean    $forced Force !important
	     * @param  boolean    $compact Don't use selectors
	     * @return string
	     */
	    public function primary_text_css( $id, $normal, $forced, $compact ) {
	    	$css = '';$important = '';
	    	if( $forced === true ){
	    		$important = '!important';
	    	}
	    	if( $normal != '' ){
	    		$css .= ( $compact === true ) ? '' : $normal .'{';
	    		$css .= $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_text_transform' ), array( 'css' => true, 'css_prefix' => 'text-transform', 'css_suffix' =>  $important ) );
	    		$css .= $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_text_font', 'field2' => 'size' ), array( 'css' => true, 'css_prefix' => 'font-size', 'css_suffix' => 'px' . $important ) );
	    		$css .= $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_text_font', 'field2' => 'size' ), array( 'css' => true, 'css_prefix' => 'line-height', 'css_suffix' => 'px' . $important ) );
	    		$css .= $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_text_font', 'field2' => 'style' ), array( 'css' => true, 'css_prefix' => 'font-style', 'css_suffix' => $important ) );
	    		$css .= $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_text_font', 'field2' => 'weight' ), array( 'css' => true, 'css_prefix' => 'font-weight', 'css_suffix' => $important ) );
	    		$css .= $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_text_font', 'field2' => 'family' ), array( 'css' => true, 'css_prefix' => 'font-family', 'css_suffix' => $important ) );
				$css .= $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_text_color', 'field2' => 'normal' ), array( 'css' => true, 'css_prefix' => 'color', 'css_suffix' => $important ) );
				$css .= $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_text_padding', 'field2' => 'top' ), array( 'css' => true, 'css_prefix' => 'padding-top', 'css_suffix' => 'px' . $important ) );
	    		$css .= $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_text_padding', 'field2' => 'bottom' ), array( 'css' => true, 'css_prefix' => 'padding-bottom', 'css_suffix' => 'px' . $important ) );
	    		$css .= $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_text_padding', 'field2' => 'left' ), array( 'css' => true, 'css_prefix' => 'padding-left', 'css_suffix' => 'px' . $important ) );
	    		$css .= $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_text_padding', 'field2' => 'right' ), array( 'css' => true, 'css_prefix' => 'padding-right', 'css_suffix' => 'px' . $important ) );
	    		$css .= ( $compact === true ) ? '' : '}' . PHP_EOL;
	    	}
			return $css;
	    }

	    /**
	     * Function to generate primary text hover css styles
	     * @since  1.3
	     * @param  string    $hover Hover selector
	     * @param  boolean    $forced Force !important
	     * @param  boolean    $compact Don't use selectors
	     * @return string
	     */
	    public function primary_text_hover_css( $id, $hover, $forced, $compact ) {
	    	$css = '';$important = '';
	    	if( $forced === true ){
	    		$important = '!important';
	    	}
	    	if( $hover != '' ){
	    		$text_primary_color_hover = $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_text_color', 'field2' => 'hover' ), array() );
				if( $text_primary_color_hover != '' ){
					$css .= ( $compact === true ) ? '' : $hover . '{';
					$css .= 'color:' . $text_primary_color_hover . $important . ';';
					$css .= ( $compact === true ) ? '' : '}' . PHP_EOL;
				}
	    	}
			return $css;
	    }

	    /**
	     * Function to generate background css styles
	     * @since  1.3
	     * @param  string    $normal Normal selector
	     * @param  boolean    $before Before selector
	     * @param  boolean    $forced Force !important
	     * @param  boolean    $compact Don't use selectors
	     * @return string
	     */
	    public function background_css( $id, $normal, $before, $forced, $compact ) {
	    	$css = '';$important = '';
	    	if( $forced === true ){
	    		$important = '!important';
	    	}
	    	if( $normal != '' ){
	    		$css .= ( $compact === true ) ? '' : $normal .'{';
				$css .= $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_background_color', 'field2' => 'normal' ), array( 'css' => true, 'css_prefix' => 'background-color', 'css_suffix' => $important ) );
				$css .= ( $compact === true ) ? '' : '}' . PHP_EOL;
	    	}
			return $css;
	    }

	    /**
	     * Function to generate background css styles
	     * @since  1.3
	     * @param  string    $hover Hover selector
	     * @param  boolean    $before Hover selector
	     * @param  boolean    $forced Force !important
	     * @param  boolean    $compact Don't use selectors
	     * @return string
	     */
	    public function background_hover_css( $id, $hover, $forced, $compact ) {
	    	$css = '';$important = '';
	    	if( $forced === true ){
	    		$important = '!important';
	    	}
	    	if( $hover != '' ){
	    		$css .= ( $compact === true ) ? '' : $hover . '{';
				$css .= $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_background_color', 'field2' => 'hover' ), array( 'css' => true, 'css_prefix' => 'background-color', 'css_suffix' => $important ) );
				$css .= ( $compact === true ) ? '' : '}' . PHP_EOL;
	    	}
			return $css;
	    }

	    /**
	     * Function to generate gradient css styles
	     * @since  1.3
	     * @param  string    $normal Normal selector
	     * @param  boolean    $forced Force !important
	     * @param  boolean    $compact Don't use selectors
	     * @return string
	     */
	    public function gradient_css( $id, $normal, $forced, $compact ) {
	    	$css = '';$important = '';
	    	if( $forced === true ){
	    		$important = '!important';
	    	}
	    	if( $normal != '' ){
	    		$gradient_type = $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_gradient_type_normal' ), array() );
				$gradient_stop_color = $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_gradient_stop_normal', 'field2' => 'color' ), array() );
				$gradient_stop_location = $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_gradient_stop_normal', 'field2' => 'location' ), array() );

				$prefixMozilla			= '-moz-linear-gradient';
	    		$prefixWebkit 			= '-webkit-gradient';
	    		$prefixWebkit2			= '-webkit-linear-gradient';
	    		$prefixOpera			= '-o-linear-gradient';
	    		$prefixMicrosoft		= '-ms-linear-gradient';
	    		$prefix 				= 'linear-gradient';
	    		$angle					= 'top,';
		    	$angle2					= 'linear, left top, left bottom,';
		    	$angle3					= 'to bottom,';

		        $gradient_stops = '';$gradient_stops2 = '';$start_color = '';$end_color = '';$val_check = '';
		        $gradient_count = count( $gradient_stop_color );
		        if( $gradient_count != '' ){
		        	for ( $i = 0; $i < $gradient_count; $i++ ) {
			        	if( isset( $gradient_stop_color[$i] ) ){
				        	if( $gradient_stop_color[$i] != '' ) {
			            		$gradient_stop_color[$i] = $gradient_stop_color[$i];
			            	}
			            	if( $gradient_stop_location[$i] != '' ) {
			            		$gradient_stop_location[$i] = $gradient_stop_location[$i];
			            	}
			            	if( $i === 0 ) {
			            		$start_color = $gradient_stop_color[$i];
			            		if( $gradient_stop_location[$i] != '' ) {
			            			$val_check = 1;
			            		}
			            	}
				            if ( $i == $gradient_count - 1 ) {
				                $gradient_stops .= $gradient_stop_color[$i] . ' ' . $gradient_stop_location[$i] . '%';
				                $gradient_stops2 .= 'color-stop(' . $gradient_stop_location[$i] . '%,' . $gradient_stop_color[$i] . ')';
				                $end_color = $gradient_stop_color[$i];
				            } else {
				                $gradient_stops .= $gradient_stop_color[$i] . ' ' . $gradient_stop_location[$i] . '%,';
				                $gradient_stops2 .= 'color-stop(' . $gradient_stop_location[$i] . '%,' . $gradient_stop_color[$i] . '),';
				            }
				        }
			        }
			        if( $val_check === 1 ) {
			        	$css .= ( $compact === true ) ? '' : $normal . '{';
		        		$css .= 'background: ' . $prefixMozilla . '(' . $angle . ' ' . $gradient_stops . ')' . $important . ';background: '.$prefixWebkit.'('.$angle2.' '.$gradient_stops2.')' . $important . ';background: '.$prefixWebkit2.'('.$angle.' '.$gradient_stops.')' . $important . ';background: '.$prefixOpera.'('.$angle.' '.$gradient_stops.')' . $important . ';background: '.$prefixMicrosoft.'('.$angle.' '.$gradient_stops.')' . $important . ';background: '.$prefix.'('.$angle3.' '.$gradient_stops.')' . $important . ';filter: progid:DXImageTransform.Microsoft.gradient( startColorstr="'.$start_color.'", endColorstr="'.$end_color.'",GradientType=0 )' . $important . ';';
		        		$css .= ( $compact === true ) ? '' : '}' . PHP_EOL;
			        }
		        }

				$gradient_css = $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_gradient_css_normal' ), array() );
				if( $gradient_css != '' ){
					$css .= ( $compact === true ) ? '' : $normal . '{';
					$css .= 'background: ' . $gradient_css . ';';
					$css .= ( $compact === true ) ? '' : '}' . PHP_EOL;
				}
	    	}
			return $css;
	    }

	    /**
	     * Function to generate gradient css styles
	     * @since  1.3
	     * @param  string    $hover Hover selector
	     * @param  boolean    $forced Force !important
	     * @param  boolean    $compact Don't use selectors
	     * @return string
	     */
	    public function gradient_hover_css( $id, $hover, $forced, $compact ) {
	    	$css = '';$important = '';
	    	if( $forced === true ){
	    		$important = '!important';
	    	}
	    	if( $hover != '' ){
	    		$gradient_type_hover = $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_gradient_type_hover' ), array() );
	    		$gradient_stop_color_hover = $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_gradient_stop_hover', 'field2' => 'color' ), array() );
				$gradient_stop_location_hover = $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_gradient_stop_hover', 'field2' => 'location' ), array() );

				$prefixMozilla			= '-moz-linear-gradient';
	    		$prefixWebkit 			= '-webkit-gradient';
	    		$prefixWebkit2			= '-webkit-linear-gradient';
	    		$prefixOpera			= '-o-linear-gradient';
	    		$prefixMicrosoft		= '-ms-linear-gradient';
	    		$prefix 				= 'linear-gradient';
	    		$angle					= 'top,';
		    	$angle2					= 'linear, left top, left bottom,';
		    	$angle3					= 'to bottom,';

				$gradient_stops_hover = '';$gradient_stops2_hover = '';$start_color_hover = '';$end_color_hover = '';$val_check_hover = '';
		        $gradient_count_hover = count( $gradient_stop_color_hover );
		        for ( $i = 0; $i < $gradient_count_hover; $i++ ) {
		        	if( isset( $gradient_stop_color_hover[$i] ) ){
			        	if( $gradient_stop_color_hover[$i] != '' ) {
		            		$gradient_stop_color_hover[$i] = $gradient_stop_color_hover[$i];
		            	}
		            	if( $gradient_stop_location_hover[$i] != '' ) {
		            		$gradient_stop_location_hover[$i] = $gradient_stop_location_hover[$i];
		            	}
		            	if( $i === 0 ) {
		            		$start_color_hover = $gradient_stop_color_hover[$i];
		            		if( $gradient_stop_location_hover[$i] != '' ) {
		            			$val_check_hover = 1;
		            		}
		            	}
			            if ( $i == $gradient_count_hover - 1 ) {
			                $gradient_stops_hover .= $gradient_stop_color_hover[$i] . ' ' . $gradient_stop_location_hover[$i] . '%';
			                $gradient_stops2_hover .= 'color-stop(' . $gradient_stop_location_hover[$i] . '%,' . $gradient_stop_color_hover[$i] . ')';
			                $end_color_hover = $gradient_stop_color_hover[$i];
			            } else {
			                $gradient_stops_hover .= $gradient_stop_color_hover[$i] . ' ' . $gradient_stop_location_hover[$i] . '%,';
			                $gradient_stops2_hover .= 'color-stop(' . $gradient_stop_location_hover[$i] . '%,' . $gradient_stop_color_hover[$i] . '),';
			            }
			        }
		        }
		        if( $gradient_count_hover != '' ){
		        	if( $val_check_hover === 1 ) {
		        		$css .= ( $compact === true ) ? '' : $hover . '{';
		        		$css .= 'background: ' . $prefixMozilla . '(' . $angle . ' ' . $gradient_stops_hover . ')' . $important . ';background: '.$prefixWebkit.'('.$angle2.' '.$gradient_stops2_hover.')' . $important . ';background: '.$prefixWebkit2.'('.$angle.' '.$gradient_stops_hover.')' . $important . ';background: '.$prefixOpera.'('.$angle.' '.$gradient_stops_hover.')' . $important . ';background: '.$prefixMicrosoft.'('.$angle.' '.$gradient_stops_hover.')' . $important . ';background: '.$prefix.'('.$angle3.' '.$gradient_stops_hover.')' . $important . ';filter: progid:DXImageTransform.Microsoft.gradient( startColorstr="'.$start_color_hover.'", endColorstr="'.$end_color_hover.'",GradientType=0 )' . $important . ';';
		        		$css .= ( $compact === true ) ? '' : '}' . PHP_EOL;
		        	}
		        }

            	$gradient_css_hover = $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_gradient_css_hover' ), array() );
            	if( $gradient_css_hover != '' ){
					$css .= ( $compact === true ) ? '' : $hover . '{';
					$css .= 'background: ' . $gradient_css_hover . ';';
					$css .= ( $compact === true ) ? '' : '}' . PHP_EOL;
				}
	    	}
			return $css;
	    }

	    /**
	     * Function to generate border css styles
	     * @since  1.3
	     * @param  string    $normal Normal selector
	     * @param  boolean    $forced Force !important
	     * @param  boolean    $compact Don't use selectors
	     * @return string
	     */
	    public function border_css( $id, $normal, $forced, $compact ) {
	    	$css = '';$important = '';
	    	if( $forced === true ){
	    		$important = '!important';
	    	}
	    	$border_normal_top = $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_border_normal', 'field2' => 'top' ), array() );
	    	$border_normal_bottom = $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_border_normal', 'field2' => 'bottom' ), array() );
	    	$border_normal_left = $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_border_normal', 'field2' => 'left' ), array() );
	    	$border_normal_right = $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_border_normal', 'field2' => 'right' ), array() );
	    	if( $normal != '' ){
	    		$css .= ( $compact === true ) ? '' : $normal . '{';
	    		if( $border_normal_top != '0' ){
					$css .= $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_border_normal', 'field2' => 'size', 'value' => 0 ), array( 'css' => true, 'css_prefix' => 'border-top-width', 'css_suffix' => 'px' . $important ) );
				}
				if( $border_normal_bottom != '0' ){
					$css .= $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_border_normal', 'field2' => 'size', 'value' => 0 ), array( 'css' => true, 'css_prefix' => 'border-bottom-width', 'css_suffix' => 'px' . $important ) );
				}
				if( $border_normal_left != '0' ){
					$css .= $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_border_normal', 'field2' => 'size', 'value' => 0 ), array( 'css' => true, 'css_prefix' => 'border-left-width', 'css_suffix' => 'px' . $important ) );
				}
				if( $border_normal_right != '0' ){
					$css .= $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_border_normal', 'field2' => 'size', 'value' => 0 ), array( 'css' => true, 'css_prefix' => 'border-right-width', 'css_suffix' => 'px' . $important ) );
				}
				$css .= $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_border_normal', 'field2' => 'style' ), array( 'css' => true, 'css_prefix' => 'border-style', 'css_suffix' => $important ) );
				$css .= $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_border_normal', 'field2' => 'color' ), array( 'css' => true, 'css_prefix' => 'border-color', 'css_suffix' => $important ) );
				$css .= $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_border_normal_radius', 'field2' => 'top_left' ), array( 'css' => true, 'css_prefix' => 'border-top-left-radius', 'css_suffix' => 'px' . $important ) );
				$css .= $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_border_normal_radius', 'field2' => 'top_right' ), array( 'css' => true, 'css_prefix' => 'border-top-right-radius', 'css_suffix' => 'px' . $important ) );
				$css .= $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_border_normal_radius', 'field2' => 'bottom_left' ), array( 'css' => true, 'css_prefix' => 'border-bottom-left-radius', 'css_suffix' => 'px' . $important ) );
				$css .= $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_border_normal_radius', 'field2' => 'bottom_right' ), array( 'css' => true, 'css_prefix' => 'border-bottom-right-radius', 'css_suffix' => 'px' . $important ) );
				$css .= ( $compact === true ) ? '' : '}' . PHP_EOL;
	    	}
			return $css;
	    }

	    /**
	     * Function to generate border hover css styles
	     * @since  1.3
	     * @param  string    $hover Hover selector
	     * @param  boolean    $forced Force !important
	     * @param  boolean    $compact Don't use selectors
	     * @return string
	     */
	    public function border_hover_css( $id, $hover, $forced, $compact ) {
	    	$css = '';$important = '';
	    	if( $forced === true ){
	    		$important = '!important';
	    	}
	    	$border_hover_top = $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_border_hover', 'field2' => 'top', 'value' => '0' ), array() );
	    	$border_hover_bottom = $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_border_hover', 'field2' => 'bottom', 'value' => '0' ), array() );
	    	$border_hover_left = $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_border_hover', 'field2' => 'left', 'value' => '0' ), array() );
	    	$border_hover_right = $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_border_hover', 'field2' => 'right', 'value' => '0' ), array() );
	    	if( $hover != '' ){
	    		$css .= ( $compact === true ) ? '' : $hover .'{';
	    		if( $border_hover_top != '0' ){
					$css .= $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_border_hover', 'field2' => 'size' ), array( 'css' => true, 'css_prefix' => 'border-top-width', 'css_suffix' => 'px' . $important ) );
				}
				if( $border_hover_bottom != '0' ){
					$css .= $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_border_hover', 'field2' => 'size' ), array( 'css' => true, 'css_prefix' => 'border-bottom-width', 'css_suffix' => 'px' . $important ) );
				}
				if( $border_hover_left != '0' ){
					$css .= $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_border_hover', 'field2' => 'size' ), array( 'css' => true, 'css_prefix' => 'border-left-width', 'css_suffix' => 'px' . $important ) );
				}
				if( $border_hover_right != '0' ){
					$css .= $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_border_hover', 'field2' => 'size' ), array( 'css' => true, 'css_prefix' => 'border-right-width', 'css_suffix' => 'px' . $important ) );
				}
				$css .= $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_border_hover', 'field2' => 'style' ), array( 'css' => true, 'css_prefix' => 'border-style', 'css_suffix' => $important ) );
				$css .= $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_border_hover', 'field2' => 'color' ), array( 'css' => true, 'css_prefix' => 'border-color', 'css_suffix' => $important ) );
				$css .= $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_border_hover_radius', 'field2' => 'top_left' ), array( 'css' => true, 'css_prefix' => 'border-top-left-radius', 'css_suffix' => 'px' . $important ) );
				$css .= $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_border_hover_radius', 'field2' => 'top_right' ), array( 'css' => true, 'css_prefix' => 'border-top-right-radius', 'css_suffix' => 'px' . $important ) );
				$css .= $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_border_hover_radius', 'field2' => 'bottom_left' ), array( 'css' => true, 'css_prefix' => 'border-bottom-left-radius', 'css_suffix' => 'px' . $important ) );
				$css .= $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_border_hover_radius', 'field2' => 'bottom_right' ), array( 'css' => true, 'css_prefix' => 'border-bottom-right-radius', 'css_suffix' => 'px' . $important ) );
				$css .= ( $compact === true ) ? '' : '}' . PHP_EOL;
	    	}
			return $css;
	    }

	    /**
	     * Function to generate box shadow css styles
	     * @since  1.3
	     * @param  int   	$id button id
	     * @param  string    $normal Normal selector
	     * @param  boolean    $forced Force !important
	     * @param  boolean    $compact Don't use selectors
	     * @return string
	     */
	    public function box_shadow_css( $id, $normal, $forced, $compact ) {
	    	$css = '';$important = '';
	    	if( $forced === true ){
	    		$important = '!important';
	    	}
	    	if( $normal != '' ){
	    		$shadow_horizontal = $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_box_shadow_normal', 'field2' => 'horizontal' ), array() );
				$shadow_vertical = $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_box_shadow_normal', 'field2' => 'vertical' ), array() );
				$shadow_blur = $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_box_shadow_normal', 'field2' => 'blur' ), array() );
				$shadow_spread = $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_box_shadow_normal', 'field2' => 'spread' ), array() );
				$shadow_position = $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_box_shadow_normal', 'field2' => 'position' ), array() );
				$shadow_color = $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_box_shadow_normal', 'field2' => 'color' ), array() );

		        $box_shadow = '';

		        if( is_array($shadow_horizontal) ){
		        	$shadow_horizontal = array_filter($shadow_horizontal,'is_numeric');
		        }
		        if( is_array($shadow_vertical) ){
		        	$shadow_vertical = array_filter($shadow_vertical,'is_numeric');
		        }

		        if( empty($shadow_horizontal) && empty($shadow_vertical)  ){
		        	$css .= ( $compact === true ) ? '' : $normal .'{';
		        	$css .= '-webkit-box-shadow:none;box-shadow:none;';
		        	$css .= ( $compact === true ) ? '' : '}' . PHP_EOL;
		        }

		        $shadow_count = count( $shadow_horizontal );
		        for ( $i = 0; $i < $shadow_count; $i++ ) {
		        	if( isset( $shadow_horizontal[$i] ) ){
			        	if( isset($shadow_horizontal[$i]) && $shadow_horizontal[$i] != '' ) {
		            		$shadow_horizontal[$i] = $shadow_horizontal[$i] . 'px ';
		            	}
		            	if( isset($shadow_vertical[$i]) && $shadow_vertical[$i] != '' ) {
		            		$shadow_vertical[$i] = $shadow_vertical[$i] . 'px ';
		            	}
		            	if( isset($shadow_blur[$i]) && $shadow_blur[$i] != '' ) {
		            		$shadow_blur[$i] = $shadow_blur[$i] . 'px ';
		            	}
		            	if( isset($shadow_spread[$i]) &&$shadow_spread[$i] != '' ) {
		            		$shadow_spread[$i] = $shadow_spread[$i] . 'px ';
		            	}
		            	if( isset($shadow_color[$i]) && $shadow_color[$i] != '' ) {
		            		$shadow_color[$i] = $shadow_color[$i] . ' ';
		            	}
			            if( !is_array( $shadow_horizontal ) ) {
			            	if( $shadow_horizontal != '' ) {
			            		$shadow_horizontal = $shadow_horizontal . 'px ';
			            	}
			            	if( $shadow_vertical != '' ) {
			            		$shadow_vertical = $shadow_vertical . 'px ';
			            	}
			            	if( $shadow_blur != '' ) {
			            		$shadow_blur = $shadow_blur . 'px ';
			            	}
			            	if( $shadow_spread != '' ) {
			            		$shadow_spread = $shadow_spread . 'px ';
			            	}
			            	if( $shadow_color != '' ) {
			            		$shadow_color = $shadow_color . ' ';
			            	}
			                $box_shadow .= $shadow_horizontal . $shadow_vertical . $shadow_blur . $shadow_spread . $shadow_color . $shadow_position;
			            } elseif ( $i == $shadow_count - 1 ){
			            	$shadow_horizontal[ $i ] = isset( $shadow_horizontal[ $i ] ) ? $shadow_horizontal[ $i ] : '';
			            	$shadow_vertical[ $i ] = isset( $shadow_vertical[ $i ] ) ? $shadow_vertical[ $i ] : '';
			            	$shadow_blur[ $i ] = isset( $shadow_blur[ $i ] ) ? $shadow_blur[ $i ] : '';
			            	$shadow_spread[ $i ] = isset( $shadow_spread[ $i ] ) ? $shadow_spread[ $i ] : '';
			            	$shadow_color[ $i ] = isset( $shadow_color[ $i ] ) ? $shadow_color[ $i ] : '';
			            	$shadow_position[ $i ] = isset( $shadow_position[ $i ] ) ? $shadow_position[ $i ] : '';
			                $box_shadow .= $shadow_horizontal[ $i ] . $shadow_vertical[ $i ] . $shadow_blur[ $i ] . $shadow_spread[ $i ] . $shadow_color[ $i ] . $shadow_position[ $i ];
			            } else {
			                $box_shadow .= $shadow_horizontal[ $i ] . $shadow_vertical[ $i ] . $shadow_blur[ $i ] . $shadow_spread[ $i ] . $shadow_color[ $i ] . $shadow_position[ $i ] . ',';
			            }
			        }
		        }
		        if( $shadow_count != '' ){
			        if( $box_shadow != '' ){
			        	$css .= ( $compact === true ) ? '' : $normal .'{';
			        	$css .= '-webkit-box-shadow:' . $box_shadow . $important . ';box-shadow:' . $box_shadow . $important . ';';
			        	$css .= ( $compact === true ) ? '' : '}' . PHP_EOL;
			        }
			    }
			    $shadow_css = $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_box_shadow_css_normal' ), array() );
		        if( $shadow_css != '' ) {
		        	$css .= ( $compact === true ) ? '' : $normal .'{';
		        	$css .= '-webkit-box-shadow:' . $shadow_css . $important . ';box-shadow:' . $shadow_css . $important . ';';
		        	$css .= ( $compact === true ) ? '' : '}' . PHP_EOL;
		        }
	    	}
			return $css;
	    }

	    /**
	     * Function to generate box shadow hover css styles
	     * @since  1.3
	     * @param  string    $hover Hover selector
	     * @param  boolean    $forced Force !important
	     * @param  boolean    $compact Don't use selectors
	     * @return string
	     */
	    public function box_shadow_hover_css( $id, $hover, $forced, $compact ) {
	    	$css = '';$important = '';
	    	if( $forced === true ){
	    		$important = '!important';
	    	}
	    	if( $hover != '' ){
	    		$shadow_horizontal_hover = $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_box_shadow_hover', 'field2' => 'horizontal' ), array() );
				$shadow_vertical_hover = $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_box_shadow_hover', 'field2' => 'vertical' ), array() );
				$shadow_blur_hover = $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_box_shadow_hover', 'field2' => 'blur' ), array() );
				$shadow_spread_hover = $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_box_shadow_hover', 'field2' => 'spread' ), array() );
				$shadow_position_hover = $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_box_shadow_hover', 'field2' => 'position' ), array() );
				$shadow_color_hover = $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_box_shadow_hover', 'field2' => 'color' ), array() );

				$box_shadow_hover = '';

				if( is_array($shadow_horizontal_hover) ){
		        	$shadow_horizontal_hover = array_filter($shadow_horizontal_hover,'is_numeric');
		        }
		        if( is_array($shadow_vertical_hover) ){
		        	$shadow_vertical_hover = array_filter($shadow_vertical_hover,'is_numeric');
		        }

		        if( empty($shadow_horizontal_hover) && empty($shadow_vertical_hover)  ){
		        	$css .= ( $compact === true ) ? '' : $hover .'{';
		        	$css .= '-webkit-box-shadow:none;box-shadow:none;';
		        	$css .= ( $compact === true ) ? '' : '}' . PHP_EOL;
		        }

		        $shadow_count_hover = count( $shadow_horizontal_hover );
		        for ( $i = 0; $i < $shadow_count_hover; $i++ ) {
		        	if( isset( $shadow_horizontal_hover[$i] ) ){
			        	if( isset($shadow_horizontal_hover[$i]) && $shadow_horizontal_hover[$i] != '' ) {
		            		$shadow_horizontal_hover[$i] = $shadow_horizontal_hover[$i] . 'px ';
		            	}
		            	if( isset($shadow_vertical_hover[$i]) && $shadow_vertical_hover[$i] != '' ) {
		            		$shadow_vertical_hover[$i] = $shadow_vertical_hover[$i] . 'px ';
		            	}
		            	if( isset($shadow_blur_hover[$i]) && $shadow_blur_hover[$i] != '' ) {
		            		$shadow_blur_hover[$i] = $shadow_blur_hover[$i] . 'px ';
		            	}
		            	if( isset($shadow_spread_hover[$i]) && $shadow_spread_hover[$i] != '' ) {
		            		$shadow_spread_hover[$i] = $shadow_spread_hover[$i] . 'px ';
		            	}
		            	if( isset($shadow_color_hover[$i]) && $shadow_color_hover[$i] != '' ) {
		            		$shadow_color_hover[$i] = $shadow_color_hover[$i] . ' ';
		            	}
			            if( !is_array( $shadow_horizontal_hover ) ) {
			            	if( $shadow_horizontal_hover != '' ) {
			            		$shadow_horizontal_hover = $shadow_horizontal_hover . 'px ';
			            	}
			            	if( $shadow_vertical_hover != '' ) {
			            		$shadow_vertical_hover = $shadow_vertical_hover . 'px ';
			            	}
			            	if( $shadow_blur_hover != '' ) {
			            		$shadow_blur_hover = $shadow_blur_hover . 'px ';
			            	}
			            	if( $shadow_spread_hover != '' ) {
			            		$shadow_spread_hover = $shadow_spread_hover . 'px ';
			            	}
			            	if( $shadow_color_hover != '' ) {
			            		$shadow_color_hover = $shadow_color_hover . ' ';
			            	}
			                $box_shadow_hover .= $shadow_horizontal_hover . $shadow_vertical_hover . $shadow_blur_hover . $shadow_spread_hover . $shadow_color_hover . $shadow_position_hover;
			            } elseif ( $i == $shadow_count_hover - 1 ){
			            	$shadow_horizontal_hover[ $i ] = isset( $shadow_horizontal_hover[ $i ] ) ? $shadow_horizontal_hover[ $i ] : '';
			            	$shadow_vertical_hover[ $i ] = isset( $shadow_vertical_hover[ $i ] ) ? $shadow_vertical_hover[ $i ] : '';
			            	$shadow_blur_hover[ $i ] = isset( $shadow_blur_hover[ $i ] ) ? $shadow_blur_hover[ $i ] : '';
			            	$shadow_spread_hover[ $i ] = isset( $shadow_spread_hover[ $i ] ) ? $shadow_spread_hover[ $i ] : '';
			            	$shadow_color_hover[ $i ] = isset( $shadow_color_hover[ $i ] ) ? $shadow_color_hover[ $i ] : '';
			            	$shadow_position_hover[ $i ] = isset( $shadow_position_hover[ $i ] ) ? $shadow_position_hover[ $i ] : '';
			                $box_shadow_hover .= $shadow_horizontal_hover[ $i ] . $shadow_vertical_hover[ $i ] . $shadow_blur_hover[ $i ] . $shadow_spread_hover[$i ] . $shadow_color_hover[ $i ] . $shadow_position_hover[ $i ];
			            } else {
			                $box_shadow_hover .= $shadow_horizontal_hover[ $i ] . $shadow_vertical_hover[ $i ] . $shadow_blur_hover[ $i ] . $shadow_spread_hover[ $i ] . $shadow_color_hover[ $i ] . $shadow_position_hover[ $i ] . ',';
			            }
			        }
		        }
		        if( $box_shadow_hover != '' ){
		        	$css .= ( $compact === true ) ? '' : $hover .'{';
		        	$css .= '-webkit-box-shadow:' . $box_shadow_hover . $important . ';box-shadow:' . $box_shadow_hover . $important . ';';
		        	$css .= ( $compact === true ) ? '' : '}' . PHP_EOL;
		        }
		        $shadow_css_hover = $this->empty_check_definition( '', $id, array( 'field' => 'btnsx_box_shadow_css_hover' ), array() );
		        if( $shadow_css_hover != '' ) {
		        	$css .= ( $compact === true ) ? '' : $hover .'{';
		        	$css .= '-webkit-box-shadow:' . $shadow_css_hover . $important . ';box-shadow:' . $shadow_css_hover . $important . ';';
		        	$css .= ( $compact === true ) ? '' : '}' . PHP_EOL;
		        }
	    	}
			return $css;
	    }

	    /**
	     * Function to generate css
	     * @since  0.1
	     * @param  int    	$id
	     * @param  boolean  $override
	     * @return string
	     */
	    public function generate_css( $id, $override ) {
	    	// Start
	    		$css = '/*' . $id . '-start*/' . PHP_EOL;

	    	// General
	    		$css .= $this->general_css( $id, '#btnsx-' . $id, false, false );

	    	// Primary Text
	    		$css .= $this->primary_text_css( $id, '#btnsx-' . $id . ' .btnsx-text-primary', false, false );
	    		$css .= $this->primary_text_hover_css( $id, '#btnsx-' . $id . ':hover .btnsx-text-primary', false, false );

			// Background
				$css .= $this->background_css( $id, '#btnsx-' . $id, true, true, false );
				$css .= $this->background_hover_css( $id, '#btnsx-' . $id . ':hover', true, false );

			// Gradient
				$css .= $this->gradient_css( $id, '#btnsx-' . $id, false, false );
				$css .= $this->gradient_hover_css( $id, '#btnsx-' . $id . ':hover', false, false );

			// Border
				$css .= $this->border_css( $id, '#btnsx-' . $id, false, false );
				$css .= $this->border_hover_css( $id, '#btnsx-' . $id . ':hover', false, false );

			// Box Shadow
				$css .= $this->box_shadow_css( $id, '#btnsx-' . $id, false, false );
				$css .= $this->box_shadow_hover_css( $id, '#btnsx-' . $id . ':hover', false, false );

		    // Filter - CSS
				$btn_css_filter = apply_filters( 'btnsx_css_filter', array(), $id );
				$btn_css_filter_as_string = '';
				foreach ( $btn_css_filter as $key => $value ) {
					$btn_css_filter_as_string .= implode( ' ', $value );
				}

	    	// End
	    		$css .= '/*' . $id . '-end*/' . PHP_EOL;

	    	return $css;
	    }

	    /**
	     * Button short code
	     * @param $atts
	     * @since 0.1
	     */
	    public function shortcode( $atts, $content = '' ) {

	    	$default = array(

				// General

					// main
					'id'								=>	'',
					'width'								=>	'',
					'height'							=>	'',
					'size'								=>	'',
					'disabled'							=>	'',
					'embossed'							=>	'',
					'full_width'						=>	'',
					'container'							=>	'',
					'wrap_center'						=>	'',

					// link
					'link_type'							=>	'',
					'link_target'						=>	'',
					'link_relationship'					=>	'',
					'link_post'							=>	'',
					'link_menu'							=>	'',
					'link_menu_display'					=>	'',
					'link'								=>	'',
					'link_edd_id'						=>	'',
					'link_woocommerce_id'				=>	'',
					'link_redirect_url'					=>	'',
					'link_popup_maker'					=>	'',

					// margin
					'margin_top'						=>	'',
					'margin_bottom'						=>	'',
					'margin_left'						=>	'',
					'margin_right'						=>	'',

					// padding
					'padding_top'						=>	'',
					'padding_right'						=>	'',
					'padding_bottom'					=>	'',
					'padding_left'						=>	'',

				// Primary Text

					// text
					'text'								=>	'',
					'text_transform'					=>	'',

					// font
					'text_font_size'					=>	'',
					'text_font_style'					=>	'',
					'text_font_weight'					=>	'',
					'text_font_family'					=>	'',

					// color
					'text_color'						=>	'',
					'text_color_hover'					=>	'',

					// padding
					'text_padding_top'					=>	'',
					'text_padding_right'				=>	'',
					'text_padding_bottom'				=>	'',
					'text_padding_left'					=>	'',

				// Background

					// color
					'background_color_normal'			=>	'',
					'background_color_hover'			=>	'',

				// Gradient

					// type
					'gradient_type_normal'				=>	'',
					'gradient_type_hover'				=>	'',

					// Stop (normal)
					'gradient_stop_color_normal'		=>	'',
					'gradient_stop_location_normal'		=>	'',

					// Stop (normal)
					'gradient_stop_color_hover'			=>	'',
					'gradient_stop_location_hover'		=>	'',

				// Border

					// main
					'border_size_normal'				=>	'',
					'border_style_normal'				=>	'',
					'border_color_normal'				=>	'',
					'border_size_hover'					=>	'',
					'border_style_hover'				=>	'',
					'border_color_hover'				=>	'',

					// radius
					'border_top_left_radius_normal'		=>	'',
					'border_top_right_radius_normal'	=>	'',
					'border_bottom_left_radius_normal'	=>	'',
					'border_bottom_right_radius_normal'	=>	'',
					'border_top_left_radius_hover'		=>	'',
					'border_top_right_radius_hover'		=>	'',
					'border_bottom_left_radius_hover'	=>	'',
					'border_bottom_right_radius_hover'	=>	'',

				// Box Shadow

					// Normal
					'shadow_horizontal'					=>	'',
					'shadow_vertical'					=>	'',
					'shadow_blur'						=>	'',
					'shadow_spread'						=>	'',
					'shadow_position'					=>	'',
					'shadow_color'						=>	'',

					// Hover
					'shadow_horizontal_hover'			=>	'',
					'shadow_vertical_hover'				=>	'',
					'shadow_blur_hover'					=>	'',
					'shadow_spread_hover'				=>	'',
					'shadow_position_hover'				=>	'',
					'shadow_color_hover'				=>	'',

				// Miscellaneous

					'container'							=>	'',
					'wrap_center'						=>	'',

				// Additional

					// 'link_override'					=>  '',
					'css_inline'						=> '',
					'on_click_content'					=> '',

			);

			// Filter Short Code Attributes
			$filter = apply_filters( 'btnsx_shortcode_attributes', array(), $default );
			$filtered = array();
			// combine multiple arrays into one
			foreach ($filter as $key => $value) {
				foreach ($value as $k => $v) {
					$filtered[$k] = $v;
				}
			}
			$options = wp_parse_args( $filtered, $default );

	    	extract( shortcode_atts( $options, $atts ) );

			// General

				// $id // will be defined (required)
				// $size // used only for modifying other parameters during button creation
				$disabled							= $disabled != '' ? $disabled : $this->meta_values( $id, array( 'field' => 'btnsx_disabled' ) );
				if( $disabled == '1' ){
					$disabled = ' btnsx-btn-disabled';
				}
				$embossed							= $embossed != '' ? $embossed : $this->meta_values( $id, array( 'field' => 'btnsx_embossed' ) );
				if( $embossed == '1' ){
					$embossed = ' btnsx-btn-embossed';
				}
				$full_width							= $full_width != '' ? $full_width : $this->meta_values( $id, array( 'field' => 'btnsx_full_width' ) );
				if( $full_width == '1' ){
					$full_width = ' btnsx-btn-block';
				}

				// link
				$link_type							= $link_type != '' ? $link_type : $this->meta_values( $id, array( 'field' => 'btnsx_link_type' ) );
				$link_target						= $link_target != '' ? $link_target : $this->meta_values( $id, array( 'field' => 'btnsx_link_target' ) );
				if( $link_target === 'new_window' ){
					$link_target = ' target="_blank"';
				}
				if( $link_target === 'same_window' ){
					$link_target = ' target="_self"';
				}
				$link_relationship					= $link_relationship != '' ? $link_relationship : $this->meta_values( $id, array( 'field' => 'btnsx_link_relationship' ) );
				if( $link_relationship != '' ){
					$link_relationship = ' rel="' . esc_attr( $link_relationship ) . '"';
				}
				$link_post							= $link_post != '' ? $link_post : $this->meta_values( $id, array( 'field' => 'btnsx_link_post' ) );
				$link_menu							= $link_menu != '' ? $link_menu : $this->meta_values( $id, array( 'field' => 'btnsx_link_menu' ) );
				$link_menu_display					= $link_menu_display != '' ? $link_menu_display : $this->meta_values( $id, array( 'field' => 'btnsx_link_menu_display' ) );
				$link								= $link != '' ? $link : $this->meta_values( $id, array( 'field' => 'btnsx_link' ) );
				$link_edd_id						= $link_edd_id != '' ? $link_edd_id : $this->meta_values( $id, array( 'field' => 'btnsx_link_edd_id' ) );
				$link_woocommerce_id				= $link_woocommerce_id != '' ? $link_woocommerce_id : $this->meta_values( $id, array( 'field' => 'btnsx_link_woocommerce_id' ) );
				$link_redirect_url					= $link_redirect_url != '' ? $link_redirect_url : $this->meta_values( $id, array( 'field' => 'btnsx_link_redirect_url' ) );

			// Primary Text

				// text
				$text = $text != '' ? $text : apply_filters('wpml_translate_single_string', $this->meta_values( $id, array( 'field' => 'btnsx_text' ) ), 'btnsx-'.$id, 'Primary Text' );
				$text_font_family = $text_font_family != '' ? $text_font_family : $this->meta_values( $id, array( 'field' => 'btnsx_text_font', 'field2' => 'family' ) );
				if( $text_font_family != '' ){
					$text_font_family = ' data-font-primary="' . esc_attr( $text_font_family ) . '"';
				}

			// Miscellaneous
				$container								= $container != '' ? $container : $this->meta_values( $id, array( 'field' => 'btnsx_container' ) );
				$wrap_center							= $wrap_center != '' ? $wrap_center : $this->meta_values( $id, array( 'field' => 'btnsx_wrap_center' ) );

				if( $container == '1' ){
					$container_id = 'btnsx-' . $id;
			   		$container_before = apply_filters( 'btnsx_container_before', '<div id="' . $container_id . '-container" class="btnsx-btn-container">', $container_id );
			   		$container_after = apply_filters( 'btnsx_container_after', '</div>', $container_id );
			   	} else {
			   		$container_id = '';
			   		$container_before = '';
			   		$container_after = '';
			   	}

			   	if( $wrap_center == '1' ){
			   		$wrap_id = 'btnsx-' . $id;
			   		$wrap_before = apply_filters( 'btnsx_wrap_before', '<div id="' . $wrap_id . '-wrap" class="btnsx-wrap-center" align="center">', $wrap_id );
			   		$wrap_after = apply_filters( 'btnsx_wrap_after', '</div>', $wrap_id );
			   	} else {
			   		$wrap_id = '';
			   		$wrap_before = '';
			   		$wrap_after = '';
			   	}

			// Inline CSS
				$css = '<style type="text/css" scoped>' . $this->generate_css( $id, false ) . '</style>';

			// Click Attribute
				$on_click_attr = '';
				// Filter - On Click
					$btn_on_click = apply_filters( 'btnsx_output_button_on_click_filter', array(), $options, $atts );
					foreach ( $btn_on_click as $key => $value ) {
						$on_click_content .= implode( ' ', $value );
					}
				if( $on_click_content != '' ){
					$on_click_attr = ' onClick="' . $on_click_content . '"';
				}

			// Filters
				// Filter - Before Button Start
					$btn_before_filter = apply_filters( 'btnsx_output_button_before_filter', array(), $options, $atts );
					$btn_before_filter_as_string = '';
					foreach ( $btn_before_filter as $key => $value ) {
						$btn_before_filter_as_string .= implode( ' ', $value );
					}

				// Filter - After Button End
					$btn_after_filter = apply_filters( 'btnsx_output_button_after_filter', array(), $options, $atts );
					$btn_after_filter_as_string = '';
					foreach ( $btn_after_filter as $key => $value ) {
						$btn_after_filter_as_string .= implode( ' ', $value );
					}

				// Filter - Button Attributes
					$btn_atts_filter = apply_filters( 'btnsx_output_button_attributes_filter', array(), $options, $atts );
					$btn_atts_filter_as_string = '';
					foreach ( $btn_atts_filter as $key => $value ) {
						$btn_atts_filter_as_string .= implode( ' ', $value );
					}

				// Filter - Button Class
					$btn_class_filter = apply_filters( 'btnsx_output_button_class_filter', array(), $options, $atts );
					$btn_class_filter_as_string = '';
					foreach ( $btn_class_filter as $key => $value ) {
						$btn_class_filter_as_string .= implode( ' ', $value );
					}

				// Filter - Button Primary Text Class
					$btn_text_class_filter = apply_filters( 'btnsx_output_button_text_class_filter', array(), $options, $atts );
					$btn_text_class_filter_as_string = '';
					foreach ( $btn_text_class_filter as $key => $value ) {
						$btn_text_class_filter_as_string .= implode( ' ', $value );
					}

return do_shortcode(
'
<!-- Buttons X - Start -->
' . $css . $wrap_before . $container_before . $btn_before_filter_as_string . '
<a href="' . esc_attr( $link ) . '" id="btnsx-' . $id . '"' . $link_target . $link_relationship . ' class="btnsx-btn' . esc_attr( $disabled ) . esc_attr( $embossed ) . esc_attr( $full_width ) . esc_attr( $btn_class_filter_as_string ) . '" ' . $text_font_family . $btn_atts_filter_as_string . '>
	' . '<span class="btnsx-text-primary ' . $btn_text_class_filter_as_string . '">' . $text . '</span>'
	. do_shortcode( $content ) .
'</a>
' . $btn_after_filter_as_string . $container_after . $wrap_after . '
<!-- Buttons X - End -->
');
	    }

	} // Main Class

}

/**
 *  Kicking this off
 */

$btn = new Btnsx();
$btn->init();
