<?php
/**
 * Plugin Name: Simple Lightbox Gallery
 * Version: 1.5.12
 * Description: Simple Lightbox Gallery plugin is allow users to view larger versions of images, simple slide shows and Gallery view with grid layout.
 * Author: Weblizar
 * Author URI: http://www.weblizar.com
 * Plugin URI: http://weblizar.com/
 */
 
/**
 * Constant Variable
 */
define("WEBLIZAR_SLGF_TEXT_DOMAIN","weblizar_image_gallery" );
define("WEBLIZAR_SLGF_PLUGIN_URL", plugin_dir_url(__FILE__));

// Image Crop Size Function 
add_image_size( 'slgf_12_thumb', 500, 9999, array( 'center', 'top'));
add_image_size( 'slgf_346_thumb', 400, 9999, array( 'center', 'top'));
add_image_size( 'slgf_12_same_size_thumb', 500, 500, array( 'center', 'top'));
add_image_size( 'slgf_346_same_size_thumb', 400, 400, array( 'center', 'top'));

/**
 * Support and Our Products Page
 */
function admin_content_slb_144936() { 
	if(get_post_type()=="slgf_slider") { ?>
		<style>
		.wlTBlock{
			background:#F8504B;
			padding: 27px 0 23px 0;
			margin-left: -20px;
			font-family: Myriad Pro ;
			cursor: pointer;
			text-align: center;
		}
		.wlTBlock .wlTBig{
			color: white;
			font-size: 30px;
			font-weight: bolder;
			padding: 0 0 15px 0;
		}
		.wlTBlock .wlTBig .dashicons{
			font-size: 40px;
			position: absolute;
			margin-left: -45px;
			margin-top: -10px;
		}
		.wlTBlock .WlTSmall{
			font-weight: bolder;
			color: white;
			font-size: 18px;
			padding: 0 0 15px 15px;
		}

		.wlTBlock a{
		text-decoration: none;
		}
		@media screen and ( max-width: 600px ) {
			.wlTBlock{ padding-top: 60px; margin-bottom: -50px; }
			.wlTBlock .WlTSmall { display: none; }
			
		}
		</style>
		<div class="wlTBlock ">
			<a href="https://weblizar.com/plugins/lightbox-slider-pro/" target="_new">
				<div class="wlTBig"><span class="dashicons dashicons-cart"></span>Get Multiple lightbox with unlimited features only in 12$</div>
				<div class="WlTSmall">with PRO version you get more advanced functionality and even more flexibility in settings </div>
			</a>
		</div>
	<?php  
	} 
}
add_action('in_admin_header','admin_content_slb_144936'); 

/**
 * Support and Our Products Page
 */
add_action('admin_menu' , 'slgf_SettingsPage');
function slgf_SettingsPage() {
	add_submenu_page('edit.php?post_type=slgf_slider', __('Help and Support', WEBLIZAR_SLGF_TEXT_DOMAIN), __('Help and Support', WEBLIZAR_SLGF_TEXT_DOMAIN), 'administrator', 'SLGF-help-page', 'SLGF_Help_and_Support_page');
	add_submenu_page('edit.php?post_type=slgf_slider', __('Pro Screenshots', WEBLIZAR_SLGF_TEXT_DOMAIN), __('Pro Screenshots', WEBLIZAR_SLGF_TEXT_DOMAIN), 'administrator', 'SLGF-Pro-Plugin', 'SLGF_Pro_page_Function');
	add_submenu_page('edit.php?post_type=slgf_slider', __('Our Products', WEBLIZAR_SLGF_TEXT_DOMAIN), __('Our Products', WEBLIZAR_SLGF_TEXT_DOMAIN), 'administrator', 'SLGF-Our-Products-page', 'SLGF_Our_Products_page');
}

function SLGF_Help_and_Support_page() {
	wp_enqueue_style('bootstrap-admin.css', WEBLIZAR_SLGF_PLUGIN_URL.'css/bootstrap-admin.css');
    require_once("help_and_support.php");
}

/**
 * Get Responsive Gallery Pro Plugin Page
 */
function SLGF_Pro_page_Function() {
    //css
    wp_enqueue_style('wrgf-font-awesome', WEBLIZAR_SLGF_PLUGIN_URL.'css/font-awesome-latest/css/font-awesome.min.css');
    wp_enqueue_style('wrgf-pricing-table-css', WEBLIZAR_SLGF_PLUGIN_URL.'css/pricing-table.css');
    wp_enqueue_style('wrgf-boot-strap-admin', WEBLIZAR_SLGF_PLUGIN_URL.'css/bootstrap-admin.css');
    require_once("get-lightbox-slider-pro.php");
}

function SLGF_Our_Products_page() {
	wp_enqueue_style('bootstrap.min.css', WEBLIZAR_SLGF_PLUGIN_URL.'css/bootstrap-admin.css');
    require_once("our_product.php");
}

/**
 * Weblizar Lightbox Slider Pro Shortcode Detect Function
 */
function slgf_js_css_load_function() {
    global $wp_query;
    $Posts = $wp_query->posts;
    $Pattern = get_shortcode_regex();

    foreach ($Posts as $Post) {
		if ( strpos($Post->post_content, 'SLGF' ) ) {
            /**
             * js scripts
             */
            wp_enqueue_script('jquery');
            wp_enqueue_script('wl-slgf-hover-pack-js',WEBLIZAR_SLGF_PLUGIN_URL.'js/hover-pack.js', array('jquery'));
            wp_enqueue_script('wl-slgf-rpg-script', WEBLIZAR_SLGF_PLUGIN_URL.'js/reponsive_photo_gallery_script.js', array('jquery'));
	
	
			//swipe box js css
            wp_enqueue_style('wl-slgf-swipe-css', WEBLIZAR_SLGF_PLUGIN_URL.'lightbox/swipebox/swipebox.css');
			wp_enqueue_script('wl-slgf-swipe-js', WEBLIZAR_SLGF_PLUGIN_URL.'lightbox/swipebox/jquery.swipebox.js', array('jquery'));		
			
            /**
             * css scripts
             */
			wp_enqueue_style('wl-slgf-hover-pack-css', WEBLIZAR_SLGF_PLUGIN_URL.'css/hover-pack.css');
			wp_enqueue_style('wl-slgf-boot-strap-css', WEBLIZAR_SLGF_PLUGIN_URL.'css/bootstrap.css');
			wp_enqueue_style('wl-slgf-img-gallery-css', WEBLIZAR_SLGF_PLUGIN_URL.'css/img-gallery.css');

            /**
             * font awesome css
             */
			wp_enqueue_style('wl-slgf-font-awesome-4', WEBLIZAR_SLGF_PLUGIN_URL.'css/font-awesome-latest/css/font-awesome.min.css');

            /**
             * envira & isotope js
             */
			wp_enqueue_script( 'slgf_envira-js', WEBLIZAR_SLGF_PLUGIN_URL.'js/masonry.pkgd.min.js', array('jquery') );
			wp_enqueue_script( 'slgf_imagesloaded', WEBLIZAR_SLGF_PLUGIN_URL.'js/imagesloaded.pkgd.min.js', array('jquery') );

            break;
        } //end of if
    } //end of foreach
}

/** For the_title function **/
add_action( 'wp', 'slgf_js_css_load_function' );

add_filter('the_title', 'slgf_convac_lite_untitled');
function slgf_convac_lite_untitled($title) {
	if ($title == '') {
		return __('No Title','convac-lite');
	} else {
		return $title;
	}
}

function slgf_remove_image_box() {
	remove_meta_box('postimagediv','slgf_slider','side');
}
add_action('do_meta_boxes', 'slgf_remove_image_box');

/**
 * Class Defination For Lightbox Slider Pro
 */
class SLGF {

    private $admin_thumbnail_size = 150;
    private $thumbnail_size_w = 150;
    private $thumbnail_size_h = 150;
	
	public function __construct() {
		add_action('admin_print_scripts-post.php', array(&$this, 'slgf_admin_print_scripts'));
        add_action('admin_print_scripts-post-new.php', array(&$this, 'slgf_admin_print_scripts'));
		add_image_size('rpg_gallery_admin_thumb', $this->admin_thumbnail_size, $this->admin_thumbnail_size, true);
        add_image_size('rpg_gallery_thumb', $this->thumbnail_size_w, $this->thumbnail_size_h, true);
		add_shortcode('lightboxslider', array(&$this, 'shortcode'));
		
		if (is_admin()) {
            add_action('init', array(&$this, 'SLGF_CPT'), 1);
            add_action('add_meta_boxes', array(&$this, 'add_all_slgf_meta_boxes'));
            add_action('admin_init', array(&$this, 'add_all_slgf_meta_boxes'), 1);
            add_action('save_post', array(&$this, 'slgf_add_image_meta_box_save'), 9, 1);
			add_action('save_post', array(&$this, 'slgf_settings_meta_save'), 9, 1);
            add_action('wp_ajax_slgf_get_thumbnail', array(&$this, 'ajax_get_thumbnail_slgf'));
        }
	}
	
	//Required JS & CSS
	public function slgf_admin_print_scripts() {
        wp_enqueue_script('media-upload');
        wp_enqueue_script('slgf-media-uploader-js', WEBLIZAR_SLGF_PLUGIN_URL . 'js/slgf-multiple-media-uploader.js', array('jquery'));
		wp_enqueue_media();
		//custom add image box css
		wp_enqueue_style('slgf-meta-css', WEBLIZAR_SLGF_PLUGIN_URL.'css/rpg-meta.css');
			
		//font awesome css
		wp_enqueue_style('slgf-font-awesome-4', WEBLIZAR_SLGF_PLUGIN_URL.'css/font-awesome-latest/css/font-awesome.min.css');
		
		//single media uploader js
		wp_enqueue_script('slgf-media-uploads',WEBLIZAR_SLGF_PLUGIN_URL.'js/slgf-media-upload-script.js',array('media-upload','thickbox','jquery'));
    }
	
	// Register Custom Post Type
	public function SLGF_CPT() {
		$labels = array(
        'name'                => _x( 'Lightbox Gallery', 'Lightbox Slider Pro', 'slgf_slider' ),
        'singular_name'       => _x( 'Lightbox Gallery', 'Lightbox Slider Pro', 'slgf_slider' ),
        'menu_name'           => __( 'Lightbox Gallery', 'slgf_slider' ),
        'parent_item_colon'   => __( 'Parent Item:', 'slgf_slider' ),
        'all_items'           => __( 'All Galleries', 'slgf_slider' ),
        'view_item'           => __( 'View Gallery', 'slgf_slider' ),
        'add_new_item'        => __( 'Add New Lightbox Gallery', 'slgf_slider' ),
        'add_new'             => __( 'Add Lightbox Gallery', 'slgf_slider' ),
        'edit_item'           => __( 'Edit Lightbox Gallery', 'slgf_slider' ),
		'new_item' 			  => __( 'New Gallery', 'slgf_slider' ),
        'update_item'         => __( 'Update Lightbox Gallery', 'slgf_slider' ),
        'search_items'        => __( 'Search Gallery', 'slgf_slider' ),
        'not_found'           => __( 'No Lightbox Gallery Found', 'slgf_slider' ),
        'not_found_in_trash'  => __( 'No Lightbox Gallery found in Trash', 'slgf_slider' ),
    );
    $args = array(
        'label'               => __( 'slgf_slider', WEBLIZAR_SLGF_TEXT_DOMAIN ),
        'description'         => __( 'Free Lightbox Gallery', WEBLIZAR_SLGF_TEXT_DOMAIN ),
        'labels'              => $labels,
        'supports'            => array( 'title', 'thumbnail', '', '', '', '', '', '', '', '', '', ),
        'hierarchical'        => false,
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 10,
        'menu_icon'           => 'dashicons-format-gallery',
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => false,
        'capability_type'     => 'page',
    );
		register_post_type( 'slgf_slider', $args );
        add_filter( 'manage_edit-slgf_gallery_columns', array(&$this, 'slgf_gallery_columns' )) ;
        add_action( 'manage_slgf_gallery_posts_custom_column', array(&$this, 'slgf_gallery_manage_columns' ), 10, 2 );
	}
	
	//column fields on all galleries page
	function slgf_gallery_columns( $columns ){
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'title' => __( 'Gallery' ),
            'shortcode' => __( 'Gallery Shortcode' ),
            'date' => __( 'Date' )
        );
        return $columns;
    }
	
	//column action fields on all galleries page
	function slgf_gallery_manage_columns( $column, $post_id ){
        global $post;
        switch( $column ) {
          case 'shortcode' :
            echo '<input type="text" value="[SLGF id='.$post_id.']" readonly="readonly" />';
            break;
          default :
            break;
        }
    }
	
	// all metabox generator function
	public function add_all_slgf_meta_boxes() {
		add_meta_box( __('Add Images', WEBLIZAR_SLGF_PLUGIN_URL), __('Add Images', WEBLIZAR_SLGF_PLUGIN_URL), array(&$this, 'slgf_generate_add_image_meta_box_function'), 'slgf_slider', 'normal', 'low' );
		add_meta_box( __('Apply Setting On Photo Gallery', WEBLIZAR_SLGF_PLUGIN_URL), __('Apply Setting On Photo Gallery', WEBLIZAR_SLGF_PLUGIN_URL), array(&$this, 'slgf_settings_meta_box_function'), 'slgf_slider', 'normal', 'low');
		add_meta_box ( __('Photo Gallery Shortcode', WEBLIZAR_SLGF_PLUGIN_URL), __('Photo Gallery Shortcode', WEBLIZAR_SLGF_PLUGIN_URL), array(&$this, 'slgf_shotcode_meta_box_function'), 'slgf_slider', 'side', 'low');
		add_meta_box(__('Get Lightbox Pro Only In $12', WEBLIZAR_SLGF_PLUGIN_URL) , __('Get Lightbox Pro Only In $12', WEBLIZAR_SLGF_PLUGIN_URL), array(&$this,'slgf_rget_to_pro_image_function'), 'slgf_slider', 'side', 'low');
		add_meta_box(__('Rate us on WordPress', WEBLIZAR_SLGF_PLUGIN_URL) , __('Rate us on WordPress', WEBLIZAR_SLGF_PLUGIN_URL), array(&$this,'slgf_rate_us_function'), 'slgf_slider', 'side', 'low');
		add_meta_box(__('Upgrade To Pro Version', WEBLIZAR_SLGF_PLUGIN_URL) , __('Upgrade To Pro Version', WEBLIZAR_SLGF_PLUGIN_URL), array(&$this,'slgf_upgrade_to_pro_function'), 'slgf_slider', 'side', 'low');
		add_meta_box(__('Pro Features', WEBLIZAR_SLGF_PLUGIN_URL) , __('Pro Features', WEBLIZAR_SLGF_PLUGIN_URL), array(&$this,'slgf_pro_features'), 'slgf_slider', 'side', 'low');
    

   }
	
	/*get pro */
	function slgf_rget_to_pro_image_function(){ ?>
	<div>
		<a href="https://weblizar.com/lightbox-slider-pro/" target="_blank" > <img src="<?php echo  WEBLIZAR_SLGF_PLUGIN_URL.'images/lightbox.jpg'; ?>" alt="" style="width:100%;height:auto"/></a>
			<div class="upgrade-to-pro" style="text-align:center;margin-bottom:10px;">
			<a href="https://weblizar.com/lightbox-slider-pro/" target="_new" class="button button-primary button-hero">Try To Pro Now</a>
		</div>	
		</div>
	<?php
	}
	
	/* Rate us */
	/**	Rate us **/
	function slgf_rate_us_function(){ ?>
		<div style="text-align:center">
		<h3>If you like our plugin then please show us some love </h3>
			<style>
			.wrg-rate-us span.dashicons{
			width: 30px;
			height: 30px;
			}
			.wrg-rate-us span.dashicons-star-filled:before {
			content: "\f155";
			font-size: 30px;
			}
			</style>

			<a class="wrg-rate-us" style="text-align:center; text-decoration: none;font:normal 30px/l;" href="http://wordpress.org/plugins/simple-lightbox-gallery/" target="_blank">
				<span class="dashicons dashicons-star-filled"></span>
				<span class="dashicons dashicons-star-filled"></span>
				<span class="dashicons dashicons-star-filled"></span>
				<span class="dashicons dashicons-star-filled"></span>
				<span class="dashicons dashicons-star-filled"></span>
			</a>
			<div class="upgrade-to-pro-demo" style="text-align:center;margin-bottom:10px;margin-top:10px;">
				<a href="http://wordpress.org/plugins/simple-lightbox-gallery/" target="_new" class="button button-primary button-hero">Click Here</a>
			</div>
		</div>
	<?php
	}
	
	/**	Upgarde to Pro **/
	function slgf_upgrade_to_pro_function(){
	?>
	<div class="upgrade-to-pro-demo" style="text-align:center;margin-bottom:10px;margin-top:10px;">
		<a href="http://demo.weblizar.com/lightbox-slider-pro-demo/"  target="_new" class="button button-primary button-hero">View Live Demo</a>
	</div>
	<div class="upgrade-to-pro-admin-demo" style="text-align:center;margin-bottom:10px;">
		<a href="http://demo.weblizar.com/lightbox-slider-pro-admin-demo/" target="_new" class="button button-primary button-hero">View Admin Demo</a>
	</div>
	<div class="upgrade-to-pro" style="text-align:center;margin-bottom:10px;">
		<a href="https://weblizar.com/lightbox-slider-pro/" target="_new" class="button button-primary button-hero">Upgarde To Pro</a>
	</div>
	<?php
	}
	
	/**	Pro Features **/
	function slgf_pro_features(){ ?>
		<ul style="">
			<li class="plan-feature">Responsive Design</li>
				<li class="plan-feature">Gallery Layout</li>
				<li class="plan-feature">Unlimited Hover Color</li>
				<li class="plan-feature">10 Types of Hover Color Opacity</li>
				<li class="plan-feature">All Gallery Shortcode</li>
				<li class="plan-feature">Each Gallery has Unique Shortcode</li>
				<li class="plan-feature">8 Types of Hover Animation</li>
				<li class="plan-feature">5 Types of Gallery Design Layout</li>
				<li class="plan-feature">500+ of Font Style</li>
				<li class="plan-feature">8 types Of Lightbox Integrated</li>
				<li class="plan-feature">Drag and Drop image Position</li>
				<li class="plan-feature">Multiple Image uploader</li>
				<li class="plan-feature">Shortcode Button on post or page</li>
				<li class="plan-feature">Unique settings for each gallery</li>
				<li class="plan-feature">Hide/Show gallery Title and label</li>
				<li class="plan-feature">Font icon Customization</li>
				<li class="plan-feature">Google Fonts</li>
				<li class="plan-feature">Isotope/Masonry Effects</li>
		</ul>
		<?php 
	} 
	
	
	/**
	 * This function display Add New Image interface
	 * Also loads all saved gallery photos into photo gallery
	 */
    public function slgf_generate_add_image_meta_box_function($post) { ?>
		<style>
		#titlediv #title {
			margin-bottom:15px;
		}
		</style>
		<div id="rpggallery_container">
            
			<ul id="slgf_gallery_thumbs" class="clearfix">
				<?php
				/* Load saved photos into gallery */
				$SLGF_AllPhotosDetails = unserialize(get_post_meta( $post->ID, 'slgf_all_photos_details', true));
				$TotalImages =  get_post_meta( $post->ID, 'slgf_total_images_count', true );
				if($TotalImages) {
					foreach($SLGF_AllPhotosDetails as $SLGF_SinglePhotoDetails) {
						$name = $SLGF_SinglePhotoDetails['slgf_image_label'];
						$UniqueString = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5);
						$url = $SLGF_SinglePhotoDetails['slgf_image_url'];
						$url1 = $SLGF_SinglePhotoDetails['slgf_12_thumb'];
						$url2 = $SLGF_SinglePhotoDetails['slgf_346_thumb'];
						$url3 = $SLGF_SinglePhotoDetails['slgf_12_same_size_thumb'];
						$url4 = $SLGF_SinglePhotoDetails['slgf_346_same_size_thumb'];
						?>
						<li class="rpg-image-entry" id="rpg_img">
							<a class="gallery_remove lbsremove_bt" href="#gallery_remove" id="lbs_remove_bt" ><img src="<?php echo  WEBLIZAR_SLGF_PLUGIN_URL.'images/Close-icon.png'; ?>" /></a>
							<img src="<?php echo $url; ?>" class="rpg-meta-image" alt=""  style="">
							<input type="button" id="upload-background-<?php echo $UniqueString; ?>" name="upload-background-<?php echo $UniqueString; ?>" value="Upload Image" class="button-primary " onClick="weblizar_image('<?php echo $UniqueString; ?>')" />
							<input type="text" id="slgf_image_label[]" name="slgf_image_label[]" value="<?php echo htmlentities($name); ?>" placeholder="Enter Image Label" class="rpg_label_text">
							
							<input type="text" id="slgf_image_url[]"  name="slgf_image_url[]"  class="rpg_label_text"  value="<?php echo  $url; ?>"   readonly="readonly" style="display:none;" />
							<input type="text" id="slgf_image_url1[]" name="slgf_image_url1[]" class="rpg_label_text"  value="<?php echo  $url1; ?>"  readonly="readonly" style="display:none;" />
							<input type="text" id="slgf_image_url2[]" name="slgf_image_url2[]" class="rpg_label_text"  value="<?php echo  $url2; ?>"  readonly="readonly" style="display:none;" />
							<input type="text" id="slgf_image_url3[]" name="slgf_image_url3[]" class="rpg_label_text"  value="<?php echo  $url3; ?>"  readonly="readonly" style="display:none;" />
							<input type="text" id="slgf_image_url4[]" name="slgf_image_url4[]" class="rpg_label_text"  value="<?php echo  $url4; ?>"  readonly="readonly" style="display:none;" />
						</li>
						<?php
						
					} // end of foreach
				} else {
					$TotalImages = 0;
				}
				?>
            </ul>
			
        </div>
		
		<!--Add New Image Button-->
		<div class="rpg-image-entry add_rpg_new_image" id="slgf_gallery_upload_button" data-uploader_title="Upload Image" data-uploader_button_text="Select" >
			<div class="dashicons dashicons-plus"></div>
			<p>
				<?php _e('Add New Images', WEBLIZAR_SLGF_PLUGIN_URL); ?>
			</p>
		</div>
		
		<div style="clear:left;"></div>
		<input id="slgf_delete_all_button" class="button" type="button" value="Delete All" rel="">
			
		<p><strong>Tips:</strong> Plugin crop images with same size thumbnails. So, please upload all gallery images using Add New Image button. Don't use/add pre-uploaded images which are uploaded previously using Media/Post/Page.</p>
        Show Us Some Love (Rate Us) &nbsp;
		<style>
			.wrg-rate-us span.dashicons{
			width: 30px;
			height: 30px;
			}
			.wrg-rate-us2 span.dashicons-star-filled:before {
			content: "\f155";
			font-size: 20px;
			color: #F8504B !important; 
			}
			</style>

			<a class="wrg-rate-us2" style="text-align:center; text-decoration: none;font:normal 30px/l;" href="http://wordpress.org/plugins/simple-lightbox-gallery/" target="_blank">
				<span class="dashicons dashicons-star-filled"></span>
				<span class="dashicons dashicons-star-filled"></span>
				<span class="dashicons dashicons-star-filled"></span>
				<span class="dashicons dashicons-star-filled"></span>
				<span class="dashicons dashicons-star-filled"></span>
			</a>
		<?php
	}
	
	/**
	 * This function display Add New Image interface
	 * Also loads all saved gallery photos into Lightbox gallery
	 */
    public function slgf_settings_meta_box_function($post) {
		require_once('simple-lightbox-slider-setting-metabox.php');
	}
	
	public function slgf_shotcode_meta_box_function() { ?>
		<p><?php _e("Use below shortcode in any Page/Post to publish your photo gallery", WEBLIZAR_SLGF_PLUGIN_URL);?></p>
		<input readonly="readonly" type="text" value="<?php echo "[SLGF id=".get_the_ID()."]"; ?>">
		<?php 
	}
	
	//
	public function admin_thumb($id) {
        $image  = wp_get_attachment_image_src($id, 'lightboxslider_admin_medium', true);
		$image1 = wp_get_attachment_image_src($id, 'slgf_12_thumb', true);
        $image2 = wp_get_attachment_image_src($id, 'slgf_346_thumb', true);
        $image3 = wp_get_attachment_image_src($id, 'slgf_12_same_size_thumb', true);
        $image4 = wp_get_attachment_image_src($id, 'slgf_346_same_size_thumb', true);
		
		$UniqueString = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5);
        ?>
		<li class="rpg-image-entry" id="rpg_img">
			<a class="gallery_remove lbsremove_bt" href="#gallery_remove" id="lbs_remove_bt" ><img src="<?php echo  WEBLIZAR_SLGF_PLUGIN_URL.'images/Close-icon.png'; ?>" /></a>
			<img src="<?php echo $image[0]; ?>" class="rpg-meta-image" alt=""  style="">
			<input type="button" id="upload-background-<?php echo $UniqueString; ?>" name="upload-background-<?php echo $UniqueString; ?>" value="Upload Image" class="button-primary " onClick="weblizar_image('<?php echo $UniqueString; ?>')" />
			<input type="text" id="slgf_image_label[]" name="slgf_image_label[]" placeholder="Enter Image Label" class="rpg_label_text">
			
			<input type="text" id="slgf_image_url[]"  name="slgf_image_url[]"  class="rpg_label_text"  value="<?php echo $image[0]; ?>"   readonly="readonly" style="display:none;" />
			<input type="text" id="slgf_image_url1[]" name="slgf_image_url1[]" class="rpg_label_text"  value="<?php echo $image1[0]; ?>"  readonly="readonly" style="display:none;" />
			<input type="text" id="slgf_image_url2[]" name="slgf_image_url2[]" class="rpg_label_text"  value="<?php echo $image2[0]; ?>"  readonly="readonly" style="display:none;" />
			<input type="text" id="slgf_image_url3[]" name="slgf_image_url3[]" class="rpg_label_text"  value="<?php echo $image3[0]; ?>"  readonly="readonly" style="display:none;" />
			<input type="text" id="slgf_image_url4[]" name="slgf_image_url4[]" class="rpg_label_text"  value="<?php echo $image4[0]; ?>"  readonly="readonly" style="display:none;" />
		</li>
        <?php
    }
	
	public function ajax_get_thumbnail_slgf() {
        echo $this->admin_thumb($_POST['imageid']);
        die;
    }
	
	public function slgf_add_image_meta_box_save($PostID) {
		if(isset($PostID) && isset($_POST['slgf_image_url'])) {
			$TotalImages = count($_POST['slgf_image_url']);
			$ImagesArray = array();
			if($TotalImages) {
				for($i=0; $i < $TotalImages; $i++) {
					$image_label = stripslashes($_POST['slgf_image_label'][$i]);
					$url = sanitize_text_field($_POST['slgf_image_url'][$i]);
					$url1 = sanitize_text_field($_POST['slgf_image_url1'][$i]);
					$url2 = sanitize_text_field($_POST['slgf_image_url2'][$i]);
					$url3 = sanitize_text_field($_POST['slgf_image_url3'][$i]);
					$url4 = sanitize_text_field($_POST['slgf_image_url4'][$i]);
					
					$ImagesArray[] = array(
						'slgf_image_label' => $image_label,
						'slgf_image_url' => $url,
						'slgf_12_thumb' => $url1,
						'slgf_346_thumb' => $url2,
						'slgf_12_same_size_thumb' => $url3,
						'slgf_346_same_size_thumb' => $url4
					);
				}
				update_post_meta($PostID, 'slgf_all_photos_details', serialize($ImagesArray));
				update_post_meta($PostID, 'slgf_total_images_count', $TotalImages);
			} else {
				$TotalImages = 0;
				update_post_meta($PostID, 'slgf_total_images_count', $TotalImages);
				$ImagesArray = array();
				update_post_meta($PostID, 'slgf_all_photos_details', serialize($ImagesArray));
			}
		}
		//die;
    }
	
	//save settings meta box values
	public function slgf_settings_meta_save($PostID) {
		if(isset($PostID) && isset($_POST['slgf_save_action'])) {
			$SLGF_Show_Gallery_Title  = sanitize_text_field($_POST['wl-show-gallery-title']);
			$SLGF_Show_Image_Label    = sanitize_text_field($_POST['wl-show-image-label']);
			$SLGF_Hover_Animation     = sanitize_text_field($_POST['wl-hover-animation']);
			$SLGF_Gallery_Layout      = sanitize_text_field($_POST['wl-gallery-layout']);
			$SLGF_Thumbnail_Layout    = sanitize_text_field($_POST['wl-thumbnail-layout']);
			$SLGF_Hover_Color         = sanitize_text_field($_POST['wl-hover-color']);
			$SLGF_Text_BG_Color       = sanitize_text_field($_POST['wl-text-bg-color']);
			$SLGF_Text_Color          = sanitize_text_field($_POST['wl-text-color']);
			$SLGF_Hover_Color_Opacity = sanitize_text_field($_POST['wl-hover-color-opacity']);
			$SLGF_Font_Style          = sanitize_text_field($_POST['wl-font-style']);
			$SLGF_Box_Shadow          = sanitize_text_field($_POST['wl-box-Shadow']);
			$SLGF_Custom_CSS          = sanitize_text_field($_POST['wl-custom-css']);

			$SLGF_DefaultSettingsArray = serialize( array(
				'SLGF_Show_Gallery_Title' => $SLGF_Show_Gallery_Title,
				'SLGF_Show_Image_Label'   => $SLGF_Show_Image_Label,
				'SLGF_Hover_Animation'    => $SLGF_Hover_Animation,
				'SLGF_Gallery_Layout'     => $SLGF_Gallery_Layout,
				'SLGF_Thumbnail_Layout'   => $SLGF_Thumbnail_Layout,
				'SLGF_Hover_Color'        => $SLGF_Hover_Color,
				'SLGF_Text_BG_Color'      => $SLGF_Text_BG_Color,
				'SLGF_Text_Color'         => $SLGF_Text_Color,
				'SLGF_Hover_Color_Opacity'=> $SLGF_Hover_Color_Opacity,
				'SLGF_Font_Style'         => $SLGF_Font_Style,
				'SLGF_Box_Shadow'         => $SLGF_Box_Shadow,
				'SLGF_Custom_CSS'         => $SLGF_Custom_CSS
			));

			$SLGF_Gallery_Settings = "SLGF_Gallery_Settings_".$PostID;
			update_post_meta($PostID, $SLGF_Gallery_Settings, $SLGF_DefaultSettingsArray);
		}
	}
}

/**
 * Initialize Class with Object
 */
$SLGF = new SLGF();

/**
 * Lightbox Slider Pro Short Code [SLGF]
 */
require_once("simple-lightbox-slider-shortcode.php");

/**
 * Hex Color code to RGB Color Code converter function
 */
if(!function_exists('SLGF_RPGhex2rgb')) {
    function SLGF_RPGhex2rgb($hex) {
       $hex = str_replace("#", "", $hex);

       if(strlen($hex) == 3) {
          $r = hexdec(substr($hex,0,1).substr($hex,0,1));
          $g = hexdec(substr($hex,1,1).substr($hex,1,1));
          $b = hexdec(substr($hex,2,1).substr($hex,2,1));
       } else {
          $r = hexdec(substr($hex,0,2));
          $g = hexdec(substr($hex,2,2));
          $b = hexdec(substr($hex,4,2));
       }
       $rgb = array($r, $g, $b);
       return $rgb; // returns an array with the rgb values
    }
}

add_action('media_buttons_context', 'add_slgf_custom_button');
add_action('admin_footer', 'add_slgf_inline_popup_content');

function add_slgf_custom_button($context) {
	$img = plugins_url( '/images/Photos-icon.png' , __FILE__ );
	$container_id = 'SLGF';
	$title = 'Select Lightbox Gallery to insert into post';
	$context .= '<a class="button button-primary thickbox" title="Select Lightbox Gallery to insert into post" href="#TB_inline?width=400&inlineId='.$container_id.'">
	<span class="wp-media-buttons-icon" style="background: url('.$img.'); background-repeat: no-repeat; background-position: left bottom;"></span>
	Simple Lightbox Gallery Shortcode</a>';
  return $context;
}

function add_slgf_inline_popup_content() { ?>
	<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('#slgfgalleryinsert').on('click', function() {
			var id = jQuery('#slgf-gallery-select option:selected').val();
			window.send_to_editor('<p>[SLGF id=' + id + ']</p>');
			tb_remove();
		})
	});
	</script>

	<div id="SLGF" style="display:none;">
		<h3>Select Lightbox Gallery To Insert Into Post</h3>
		<?php 
		$all_posts = wp_count_posts( 'slgf_slider')->publish;
		$args = array('post_type' => 'slgf_slider', 'posts_per_page' =>$all_posts);
		global $rpg_galleries;
		$rpg_galleries = new WP_Query( $args );		
		if( $rpg_galleries->have_posts() ) { ?>
			<select id="slgf-gallery-select">
			<?php
			while ( $rpg_galleries->have_posts() ) : $rpg_galleries->the_post(); ?>
				<option value="<?php echo get_the_ID(); ?>"><?php the_title(); ?></option>
			<?php
			endwhile;
			?>
			</select>
			<button class='button primary' id='slgfgalleryinsert'>Insert Gallery Shortcode</button>
			<?php
		} else {
			_e("No Gallery Found", WEBLIZAR_SLGF_TEXT_DOMAIN);
		}
		?>
	</div>
	<?php
}

// Add settings link on plugin page
$slgf_plugin_name = plugin_basename(__FILE__);
add_filter("plugin_action_links_$slgf_plugin_name", 'as_settings_link_slgf' );
function as_settings_link_slgf($links) {
    $as_settings_link1 = '<a href="https://weblizar.com/" target="_blank">Get More</a>';
    $as_settings_link2= '<a href="edit.php?post_type=slgf_slider">Settings</a>';
    array_unshift($links, $as_settings_link1, $as_settings_link2);
    return $links;
}

// Review Notice Box
add_action("admin_notices","review_admin_notice_slgf");
function review_admin_notice_slgf(){
    $screen = get_current_screen();
    if($screen->post_type == "slgf_slider"){
        echo '<div class="notice notice-success is-dismissible review-notice">
		<p>Thanks for installing and using SIMPLE LIGHT BOX Gallery plugin. If you love our plugin and plugin is really work for you.
		Then please share your feedback about this plugin. Your feedback will be helpful to make plugin more error free.</p>
		<p><a href="https://wordpress.org/support/plugin/simple-lightbox-gallery/reviews/?filter=5" target="_blank" name="review" id="review" class="button button-primary">Review & Rate</a></p>
		</div>';
		?>
		<style>
		.review-notice{
			background-color: #019697 !important;   
			color: #fff;
		}
		.review-notice p{
			font-size: 16px !important;
			font-weight: 300 !important;            
			font-style: normal !important; 
			text-shadow: 2px 2px 2px rgba(150, 150, 150, 1);
		}
		.review-notice #review{
			background-color: #b23831 !important;
			/*border-color: rgb(0, 115, 170) rgb(0, 103, 153) rgb(0, 103, 153);*/
			border-color: #eee #eee #eee;
			-webkit-box-shadow: 0 1px 0 #b23831;
			box-shadow: 0 1px 0 #b23831;
			color: rgb(255, 255, 255);
			text-decoration: none;
			text-shadow: 0 -1px 1px #b23831, 1px 0 1px #b23831, 0 1px 1px #b23831, -1px 0 1px #b23831;
		}
		.notice-dismiss:before{
			color: #fff !important;
		}
		.review-notice #review:hover{
			background: rgb(238, 238, 238) !important;
			border-color: rgb(238, 238, 238) !important;
			color: rgb(255, 255, 255) !important;
		}
		.review-notice #review:focus{
			-webkit-box-shadow: 0 1px 0 #b13830, 0 0 2px 1px #b23831 !important;
			box-shadow: 0 1px 0 #b23831, 0 0 2px 1px #b23831 !important;
		}
		.notice-dismiss:before{
			color: #fff !important;
		}
		.notice-dismiss span{
			display: inline-block;
			background-color: #fff !important;
			color: #019697 !important;
		}
		</style>
		<?php
    }    
}
?>