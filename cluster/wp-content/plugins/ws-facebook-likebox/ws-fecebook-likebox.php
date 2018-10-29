<?php
/*   
 Plugin Name: WS Facebook Like Box Widget 
 Plugin URI: https://wordpress.org/plugins/ws-fecebook-likebox/
 Description: WS Facebook Like Box Widget provides easy and quick use in your blog. You can fully customize facebook like box in easy way.
 Version: 4.1                       
 Author: WebShouters                    
 Author URI: http://www.webshouters.com/      
 Text Domain: ws-facebook-likebox
 Domain Path: /languages/                                        
 License: GPL3                                                                                                                                     
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

define( 'WS_FB_LIKE_BOX_VERSION', '4.1' );
define( 'WS_FB_LIKE_BOX_PLUGIN', __FILE__ );
define( 'WS_FB_LIKE_BOX_PLUGIN_BASENAME', plugin_basename( WS_FB_LIKE_BOX_PLUGIN ) );
define( 'WS_FB_LIKE_BOX_PLUGIN_NAME', trim( dirname( WS_FB_LIKE_BOX_PLUGIN_BASENAME ), '/' ) );
define( 'WS_FB_LIKE_BOX_PLUGIN_DIR', untrailingslashit( dirname( WS_FB_LIKE_BOX_PLUGIN ) ) );
                                                                                                                                                                                                                                                                                                                                                                                                                                                                           
class WS_FACEBOOK_LIKEBOX extends WP_Widget {                                                                                                                     
	function __construct() {                                                                                                        
		parent::__construct(                                                                                                                                                                           
			'ws_fb_like_box', //Base ID                       
			__( 'WS Facebook Likebox', 'ws-facebook-likebox' ), //Name  
			array( 'description' => __( 'WS Likebox Widget!', 'ws-facebook-likebox' ), ) //Args 
		);  
	}                                                                 
	                             
	public function form( $instance ) {
		             
		$defaults = array(         
			'title' 		=> __( 'Facebook Like Box', 'ws-facebook-likebox' ),
			'fb_page_id' 	=> 'webshouters',
			'width' 		=> '250',
			'height' 		=> '500',
			'adapt_width' => 'no',
			'small_header' => 'no',
			'hide_cover_photo'=>'no',
			'show_faces'=>'yes',
			'tabs' => 'timeline',
			'locale_lang' => 'en_US',
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults );
		     
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'ws-facebook-likebox' ); ?>:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
                   name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
                   value="<?php echo esc_attr( $instance['title'] ); ?>">
        </p>
 
        <p>
            <label
                for="<?php echo $this->get_field_id( 'fb_page_id' ); ?>"><?php _e( 'Page Id', 'ws-facebook-likebox' ); ?>:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'fb_page_id' ); ?>"
                   name="<?php echo $this->get_field_name( 'fb_page_id' ); ?>" type="text"
                   value="<?php echo esc_attr( $instance['fb_page_id'] ); ?>">
        </p>
        
        <p>
            <label
                for="<?php echo $this->get_field_id( 'width' ); ?>"><?php _e( 'Width', 'ws-facebook-likebox' ); ?>:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'width' ); ?>"
                   name="<?php echo $this->get_field_name( 'width' ); ?>" type="text"
                   value="<?php echo esc_attr( $instance['width'] ); ?>">
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id( 'height' ); ?>"><?php _e( 'Height', 'ws-facebook-likebox' ); ?>:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'height' ); ?>"
                   name="<?php echo $this->get_field_name( 'height' ); ?>" type="text"
                   value="<?php echo esc_attr( $instance['height'] ); ?>">
        </p>
        
         <p>
            <label for="<?php echo $this->get_field_id( 'adapt_width' ); ?>"><?php _e( 'Adapt to plugin container width', 'ws-facebook-likebox' ); ?>:</label>
	        <select style="width:100%" id="<?php echo $this->get_field_id('adapt_width'); ?>" name="<?php echo $this->get_field_name('adapt_width'); ?>" >
	          	<option <?php  selected($instance['adapt_width'],'no')  ?> value="no"><?php _e( 'No', 'ws-facebook-likebox' ); ?></option>
	          	<option <?php  selected($instance['adapt_width'],'yes')  ?> value="yes"><?php _e( 'Yes', 'ws-facebook-likebox' ); ?></option>            
	        </select>
         </p>
 
         <p>
            <label for="<?php echo $this->get_field_id( 'small_header' ); ?>"><?php _e( 'Use Small Header', 'ws-facebook-likebox' ); ?>:</label>
	        <select style="width:100%" id="<?php echo $this->get_field_id('small_header'); ?>" name="<?php echo $this->get_field_name('small_header'); ?>" >
	          	<option <?php  selected($instance['small_header'],'no')  ?> value="no"><?php _e( 'No', 'ws-facebook-likebox' ); ?></option>
	          	<option <?php  selected($instance['small_header'],'yes')  ?> value="yes"><?php _e( 'Yes', 'ws-facebook-likebox' ); ?></option>            
	        </select>
         </p>
         
         <p>
            <label for="<?php echo $this->get_field_id( 'hide_cover_photo' ); ?>"><?php _e( 'Hide Cover Photo', 'ws-facebook-likebox' ); ?>:</label>
	        <select style="width:100%" id="<?php echo $this->get_field_id('hide_cover_photo'); ?>" name="<?php echo $this->get_field_name('hide_cover_photo'); ?>" >
	          	<option <?php  selected($instance['hide_cover_photo'],'no')  ?> value="no"><?php _e( 'No', 'ws-facebook-likebox' ); ?></option>
	          	<option <?php  selected($instance['hide_cover_photo'],'yes')  ?> value="yes"><?php _e( 'Yes', 'ws-facebook-likebox' ); ?></option>            
	        </select>
         </p>
         
         <p>
            <label for="<?php echo $this->get_field_id( 'show_faces' ); ?>"><?php _e( 'Show Friend\'s Faces', 'ws-facebook-likebox' ); ?>:</label>
	        <select style="width:100%" id="<?php echo $this->get_field_id('show_faces'); ?>" name="<?php echo $this->get_field_name('show_faces'); ?>" >
	          	<option <?php  selected($instance['show_faces'],'no')  ?> value="no"><?php _e( 'No', 'ws-facebook-likebox' ); ?></option>
	          	<option <?php  selected($instance['show_faces'],'yes')  ?> value="yes"><?php _e( 'Yes', 'ws-facebook-likebox' ); ?></option>            
	        </select>
         </p>
         
         <p>
            <label for="<?php echo $this->get_field_id( 'tabs' ); ?>"><?php _e( 'Tabs', 'ws-facebook-likebox' ); ?>:</label>
	        <select style="width:100%" id="<?php echo $this->get_field_id('tabs'); ?>" name="<?php echo $this->get_field_name('tabs'); ?>" >
	        	<option <?php  selected($instance['tabs'],'')  ?> value=""><?php _e( 'Hide Posts', 'ws-facebook-likebox' ); ?></option>
	          	<option <?php  selected($instance['tabs'],'timeline')  ?> value="timeline"><?php _e( 'Timeline', 'ws-facebook-likebox' ); ?></option>
	          	<option <?php  selected($instance['tabs'],'events')  ?> value="events"><?php _e( 'Events', 'ws-facebook-likebox' ); ?></option>
	          	<option <?php  selected($instance['tabs'],'messages')  ?> value="messages"><?php _e( 'Messages', 'ws-facebook-likebox' ); ?></option>           
	        </select>
         </p>
          
         <p>
            <label
                for="<?php echo $this->get_field_id( 'locale_lang' ); ?>"><?php _e( 'Language', 'ws-facebook-likebox' ); ?>:</label>
            <input id="<?php echo $this->get_field_id( 'locale_lang' ); ?>"
                   name="<?php echo $this->get_field_name( 'locale_lang' ); ?>" type="text" style="width: 70px;"
                   value="<?php echo esc_attr( $instance['locale_lang'] ); ?>">
             <small>(en_US, de_DE...)</small>
        </p>
        
    <?php
    }

public function update( $new_instance, $old_instance ) {
        $instance                       = array();
        $instance['title']              = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['fb_page_id']      = ( ! empty( $new_instance['fb_page_id'] ) ) ? strip_tags( $new_instance['fb_page_id'] ) : '';
	    $instance['width']        = ( ! empty( $new_instance['width'] ) ) ? strip_tags( $new_instance['width'] ) : '';
		$instance['height']        = ( ! empty( $new_instance['height'] ) ) ? strip_tags( $new_instance['height'] ) : '';
        $instance['adapt_width'] = ( ! empty( $new_instance['adapt_width'] ) ) ? strip_tags( $new_instance['adapt_width'] ) : '';
        $instance['small_header']        = ( ! empty( $new_instance['small_header'] ) ) ? strip_tags( $new_instance['small_header'] ) : '';
        $instance['hide_cover_photo']        = ( ! empty( $new_instance['hide_cover_photo'] ) ) ? strip_tags( $new_instance['hide_cover_photo'] ) : '';
		$instance['show_faces']        = ( ! empty( $new_instance['show_faces'] ) ) ? strip_tags( $new_instance['show_faces'] ) : '';
		$instance['tabs']        = ( ! empty( $new_instance['tabs'] ) ) ? strip_tags( $new_instance['tabs'] ) : '';
		$instance['locale_lang']        = ( ! empty( $new_instance['locale_lang'] ) ) ? strip_tags( $new_instance['locale_lang'] ) : '';
		
        return $instance;
    }

public function widget( $args, $instance ) {
	
	    foreach($instance as $key => $value){
			if($value=='yes')
				$instance[$key]='true';
			if($value=='no')
				$instance[$key]='false';
		}
		
        $title = apply_filters( 'widget_title', $instance['title'] );
		extract($instance);
       
        echo $args['before_widget'];
		
        if ( ! empty( $title ) ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
 
        if ( empty( $fb_page_id ) ) {
            echo "Facebook Page Id is missing in Widget settings.";
        }
		 else {
		 	
			$html = '';
			 
			$html .= '<div class="fb-page ws-fb-like-box" data-href="https://www.facebook.com/'.$fb_page_id.'" 
						data-tabs="'.$tabs.'" 
						data-width="'.$width.'" 
						data-height="'.$height.'"
						data-small-header="'.$small_header.'" 
						data-adapt-container-width="'.$adapt_width.'" 
						data-hide-cover="'.$hide_cover_photo.'"
						data-show-facepile="'.$show_faces.'">
						<div class="fb-xfbml-parse-ignore">
							<blockquote cite="https://www.facebook.com/'.$fb_page_id.'">
								<a href="https://www.facebook.com/'.$fb_page_id.'">Facebook</a>
							</blockquote>
						</div>
					 </div> ';
					 
		   $html .= '<div id="fb-root"></div>
					 <script>(function(d, s, id) {
					  var js, fjs = d.getElementsByTagName(s)[0];
					  if (d.getElementById(id)) return;
					  js = d.createElement(s); js.id = id;
					  js.src = "//connect.facebook.net/'. $locale_lang .'/sdk.js#xfbml=1&version=v2.6";
					  fjs.parentNode.insertBefore(js, fjs);
					}(document, \'script\', \'facebook-jssdk\'));</script>';		 
		  	 
		   echo $html;
		 	
        }
 
        echo $args['after_widget'];
    }		
}

function ws_fb_likebox_load_plugin_textdomain() {
  load_plugin_textdomain( 'ws-facebook-likebox', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}

/* register widget */
function register_ws_fb_likebox_widget() {
    register_widget( 'WS_FACEBOOK_LIKEBOX' );
}
add_action( 'widgets_init', 'register_ws_fb_likebox_widget' );
add_action( 'plugins_loaded', 'ws_fb_likebox_load_plugin_textdomain' );

require_once WS_FB_LIKE_BOX_PLUGIN_DIR . '/includes/shortcodes.php';
