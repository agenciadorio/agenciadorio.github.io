<?php
/**
 * Button X
 *
 * This file is used to register Addons and Packs pages.
 *
 * @package Buttons X
 * @since 1.7.4
 */

// Make sure we don't expose any info if called directly
if ( !defined( 'ABSPATH' ) )
	exit;

if( !class_exists( 'BtnsxWapi' ) ) {
	class BtnsxWapi {

		private static $url;
		private static $products;
		private static $instance;

		/**
		 * Initiator
		 * @since 0.1
		 */
		public static function init() {
			return self::$instance;
		}

		/**
		 * Fetch packs and addons data
		 * @since  1.7.6
		 * @return array
		 */
		public function fetch_data() {
			self::$url = 'https://www.button.sx/wp-content/uploads/data/products.json';
			// delete_transient( 'btnsx_remote_packs_addons' );
			if ( false === ( self::$products = get_transient( 'btnsx_remote_packs_addons' ) ) ) {
				$response = wp_remote_get( self::$url, array( 'timeout' => 120 ) );
				if( is_array($response) ) {
				  	self::$products = $response['body'];
				  	set_transient( 'btnsx_remote_packs_addons', self::$products, 24 * 60 * 60 );
				}
			}
			self::$products = json_decode( get_transient( 'btnsx_remote_packs_addons' ) );
			return self::$products;
		}

		/**
		 * Constructor
		 * @since 0.1
		 */
		public function __construct() {
			add_action( 'admin_menu', array( $this, 'pages' ), 99 );
		}

		/**
		 * Welcome page markup
		 * @since  1.7.3
		 * @return string
		 */
		public function addons_page_callback(){
			$screen = get_current_screen();
			if( $screen->id == 'buttons-x_page_add-ons' ){
				$addons = $this->fetch_data();
			    ?>
			        <style type="text/css">
			            .btnsx-column-right { float:right!important;text-align:right!important; }
			            .btnsx-column-left { float:left!important;clear:left!important;text-align:left!important; }
			            .btnsx-column-name, .btnsx-column-description { margin-left: 270px!important;margin-right: 0!important; }
			            .btnsx-plugin-icon, .btnsx-plugin-icon img { width:250px!important;height:250px!important; }
			            .btnsx-plugin-card-top { min-height: 260px!important; }
			            .plugin-card .column-compatibility, .plugin-card .column-updated{ width: calc(100% - 100px)!important; }
			        </style>
			        <div id="btnsx-addons" class="wrap">
			            <h1><?php printf( __( 'Add-ons', 'buttons-x' ), BTNSX__VERSION ); ?></h1>
			            <p></p>
			            <div class="error"> 
					        <p><?php _e( 'We take no gaurantee that an add-on will work with this lite version of Buttons X.', 'buttons-x' ); ?></p>
					    </div>
						<p></p>			    
			            <div class="wp-list-table widefat plugin-install">
			            	<div id="the-list" class="btnsx-addons-list">
			            		<?php
			            			$comp = array('<strong>Compatible</strong> with your version of Buttons X','<strong>Incompatible</strong> with your version of Buttons X','Untested with your version of Buttons X');
									$compClassArr = array('compatible','incompatible','untested');
									$compHTML;$compClass;
									// var_dump(self::$products);
									if( $addons != NULL ){
					            		foreach ($addons as $k => $v) {
				            				if( isset($v->categories) ){
					            				if( in_array( 'Add-ons', $v->categories ) ){
													$active = is_plugin_active($v->attributes[3]->options[0]);
					            					if( $active != 'true' ){
														$btn = '<a target="_blank" class="install-now button button-primary" href="'.$v->permalink.'">'.__("Buy Now","btnsx").'</a>';
													}else{
														$btn = '<a target="_blank" class="install-now button button-disabled" href="#">'.__("Installed","btnsx").'</a>';
													}
													if( $v->attributes[1]->options[0] <= BTNSX__VERSION ){
														$compHTML = $comp[2];
														$compClass = $compClassArr[2];
													}elseif( $v->attributes[1]->options[0] > BTNSX__VERSION ){
														$compHTML = $comp[2];
														$compClass = $compClassArr[2];
													}else{
														$compHTML = $comp[2];
														$compClass = $compClassArr[2];
													}
						            			?>
						            				<div class="plugin-card"><div class="plugin-card-top btnsx-plugin-card-top">
													<a target="_blank" href="<?php echo $v->permalink; ?>" class="thickbox plugin-icon btnsx-plugin-icon">
															<img src="<?php echo $v->featured_src; ?>">
														</a>
														<div class="name column-name btnsx-column-name">
															<h4><a href="<?php echo $v->permalink; ?>" class="thickbox"><?php echo $v->title; ?></a></h4>
														</div>
														<div class="desc column-description btnsx-column-description">
															<p><?php echo $v->short_description; ?></p>
															<p class="authors"> <cite>By <a target="_blank" href="<?php echo $v->attributes[5]->options[0]; ?>"><?php echo $v->attributes[4]->options[0]; ?></a></cite><!--  | <span class="btnsx-demo"><a target="_blank" href="<?php // echo $v->attributes[2]->options[0]; ?>">Demo</a></span> --></p>
														</div>
													</div>
													<div class="plugin-card-bottom">
														<div class="column-downloaded btnsx-column-right">
															<?php echo $btn; ?>
														</div>
														<div class="column-compatibility btnsx-column-left">
															<span class="compatibility-<?php echo $compClass; ?>">
																<?php echo $compHTML; ?>
															</span>
														</div>
													</div>
													</div>
						            			<?php
						            			}
						            		}
					            		}
					            	} else {
					            		echo '<div class="error"> 
									        <p>' . __( 'Something went wrong! Please try again later.', 'buttons-x' ) . '</p>
									    </div>';
									}
								?>
			            	</div>
			            </div>
			        </div>
			    <?php
			}
		}

		/**
		 * Welcome page markup
		 * @since  1.7.3
		 * @return string
		 */
		public function packs_page_callback(){
			$screen = get_current_screen();
			if( $screen->id == 'buttons-x_page_packs' ){
				$packs = $this->fetch_data();
			    ?>
			        <style type="text/css">
			        	.btnsx-pack { width: 300px!important; }
			            .btnsx-pack-actions { opacity:1!important; }
			            .btnsx-thumb, .btnsx-thumb img { width: 298px!important;height: 298px!important; }
			        </style>
			        <div id="btnsx-packs" class="wrap">
			            <h1><?php printf( __( 'Packs', 'buttons-x' ), BTNSX__VERSION ); ?></h1>
			            <p></p>
			            <div class="error"> 
					        <p><?php _e( 'We take no gaurantee that a pack will work with this lite version of Buttons X.', 'buttons-x' ); ?></p>
					    </div>
						<p></p>	
			            <div class="theme-browser rendered">
			            	<div class="themes">
			            	<?php
			            		if( $packs != NULL ){
				            		foreach ($packs as $k => $v) {
			            				if( isset($v->categories) ){
				            				if( in_array( 'Packs', $v->categories ) ){
					            			?>
					            				<div class="theme btnsx-pack" tabindex="0">
													<div class="theme-screenshot btnsx-thumb">
														<img src="<?php echo $v->featured_src; ?>" alt="">
													</div>
													<a target="_blank" href="<?php echo $v->permalink; ?>"><span class="more-details"><?php _e('Pack Details','buttons-x'); ?></span></a>
													<div class="theme-author">By Gautam Thapar</div>
													<h3 class="theme-name"><?php echo $v->title; ?></h3>
													<div class="theme-actions btnsx-pack-actions">
														<a class="button button-primary load-customize hide-if-no-customize" href="<?php echo $v->permalink; ?>"><?php _e('Download','buttons-x'); ?></a>
													</div>
												</div>
					            			<?php
					            			}
					            		}
				            		}
				            	} else {
				            		echo '<div class="error"> 
									        <p>' . __( 'Something went wrong! Please try again later.', 'buttons-x' ) . '</p>
									    </div>';
				            	}
			    			?>
			            	</div>
			            </div>
			        </div>
			    <?php
			}
		}

		/**
		 * Register addons page
		 * @since  1.7.3
		 * @return
		 */
		public function pages() {
		    add_submenu_page( 'btnsx', 'Add-ons', 'Add-ons', 'manage_options', 'add-ons', array( $this, 'addons_page_callback' ) );
		    add_submenu_page( 'btnsx', 'Packs', 'Packs', 'manage_options', 'packs', array( $this, 'packs_page_callback' ) );
		}

		
	} // Wapi Class
}

/**
 *  Kicking this off
 */

$btn_options = new BtnsxWapi();
$btn_options->init();