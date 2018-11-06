<?php
	/*
	Plugin Name: My Clients Wordpress plugin
	Plugin URI: http://codecanyon.net/user/husamrayan
	Description: My Clients Wordpress plugin.
	Version: 1.5
	Author: husamrayan
	*/
	
	
	/* Enabling Support for Post Thumbnails
	function mc_clients_add_thumbnails() {
		add_theme_support( 'post-thumbnails');
	}
	add_action( 'after_setup_theme', 'mc_clients_add_thumbnails' );*/

	function mc_clients_prefix_stylesheet() {
		wp_register_style( 'mc-clients-style', plugins_url('style.css', __FILE__) );
		wp_enqueue_style( 'mc-clients-style' );
	}
	
	add_action( 'wp_enqueue_scripts', 'mc_clients_prefix_stylesheet' );
	add_action('admin_enqueue_scripts', 'mc_clients_prefix_stylesheet');


	function mc_clients_scripts_method() {
		//wp_register_script( 'mc_jquery', plugins_url('js/jquery.js', __FILE__) );
		//wp_enqueue_script( 'mc_jquery' );
		
		wp_register_script( 'mc_jquery_easing', plugins_url('js/jquery.easing.js', __FILE__) );
		wp_enqueue_script( 'mc_jquery_easing' );
		
		wp_register_script( 'mc_carouFredSel', plugins_url('js/jquery.carouFredSel-6.2.1.js', __FILE__) );
		wp_enqueue_script( 'mc_carouFredSel' );
		
		wp_register_script( 'mc_clients', plugins_url('js/mc_clients.js', __FILE__) );
		wp_enqueue_script( 'mc_clients' );
	}    
	 
	add_action('wp_enqueue_scripts', 'mc_clients_scripts_method');


	// Clients
	function mc_clients_shortcode($atts, $content=null) {  
		extract(shortcode_atts( array(  
			'columns' => '5',
			'backgroundcolor' => 'transparent',
			'style' => 'responsive',
			'num' => '-1',
			'category' => '0',
			'orderby' => 'date',
			'order' => 'DESC'
		), $atts));  
		
		// 	query posts
		
		$args =	array ( 'post_type' => 'myclients',
						'posts_per_page' => $num,
						'orderby' => $orderby,
						'order' => $order );
		
		if($category > 0) {
			$args['tax_query'] = array(array('taxonomy' => 'clientscategory','field' => 'id','terms' => $category ));
		}
		
		$clients_query = new WP_Query( $args );
		
		$html='';

		if ($clients_query->have_posts()) {
			
			$html='<ul class="mc_clientsList '.$style.'">';
			
			$i = 0;
			
			while ($i < $clients_query->post_count) {
			
				$post = $clients_query->posts;
				
				// if has post thumbnail		
				if ( has_post_thumbnail($post[$i]->ID)) {
				
					//$domsxe = simplexml_load_string(get_the_post_thumbnail($post[$i]->ID,'full'));
					//$thumbnailsrc = $domsxe->attributes()->src;	
					$thumbnailsrc = wp_get_attachment_url(get_post_meta($post[$i]->ID, '_thumbnail_id', true));
					
				}
				
				$href=''; 
				$target='';
				$bgSize='';
					
				if(get_post_meta($post[$i]->ID, 'husamrayan_clientWebsite', true)!='') { 
					$href='href="http://'.get_post_meta($post[$i]->ID, 'husamrayan_clientWebsite', true).'"';
					$target='_blank';
				}
				
				if(get_post_meta($post[$i]->ID, 'mc_imageSize', true) !='' ) {
					$bgSizeVal=get_post_meta($post[$i]->ID, 'mc_imageSize', true);
					$bgSize='-webkit-background-size: '.$bgSizeVal.'; -moz-background-size: '.$bgSizeVal.'; background-size: '.$bgSizeVal.';';
				}
				
				$html.='<li style="background-color:'.$backgroundcolor.';width:'.(100/$columns).'%;"><a style="'.$bgSize.'background-image:url('.$thumbnailsrc.');" '.$href.' target="'.$target.'"></a></li>';
				
				$i++;
			}
			$html.='</ul>';
		}
		return $html;  
	}  
	add_shortcode('myclients', 'mc_clients_shortcode');





	/*========================================================================================================================================================================
		Add Editor Buttons
	========================================================================================================================================================================*/
	
	add_action('init', 'add_mc_clients_shortcode_button');
	
	function add_mc_clients_shortcode_button() {  
	   if ( current_user_can('edit_posts') &&  current_user_can('edit_pages') )  
	   {  
		 add_filter('mce_external_plugins', 'add_mc_clients_shortcode_plugin');  
		 add_filter('mce_buttons', 'register_mc_clients_shortcode_button');  
	   }  
	}  
	
	function register_mc_clients_shortcode_button($buttons) {
		array_push($buttons, "myclients");
		return $buttons;  
	} 
	
	function add_mc_clients_shortcode_plugin($plugin_array) {  
	   $plugin_array['myclients'] = plugins_url('js/shortcode_buttons/mybuttons.js', __FILE__);
	   return $plugin_array;  
	}
	
	
	
	
	/*========================================================================================================================================================================
		Register client Post Type
	========================================================================================================================================================================*/
	
	add_action('init', 'mc_client_init');
	function mc_client_init() 
	{
		/*----------------------------------------------------------------------
			client Post Type Labels
		----------------------------------------------------------------------*/
		
		$labels = array(
			'name' => _x('My Clients', 'Post type general name'),
			'singular_name' => _x('My Clients', 'Post type singular name'),
			'add_new' => _x('Add new client', 'Client Item'),
			'add_new_item' => __('Add new client'),
			'edit_item' => __('Edit client'),
			'new_item' => __('New client'),
			'all_items' => __('All clients'),
			'view_item' => __('View'),
			'search_items' => __('Search'),
			'not_found' =>  __('No clients found.'),
			'not_found_in_trash' => __('No clients found.'), 
			'parent_item_colon' => '',
			'menu_name' => 'My Clients'
		);
		
		/*----------------------------------------------------------------------
			client Post Type Properties
		----------------------------------------------------------------------*/
		
		$args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true, 
			'show_in_menu' => true, 
			'query_var' => true,
			'rewrite' => true,
			'capability_type' => 'post',
			'has_archive' => true, 
			'hierarchical' => false,
			'menu_position' => null,
			'supports' => array('title', 'thumbnail')
		);
		
		
		/*----------------------------------------------------------------------
			myclients Post Type Categories Register
		----------------------------------------------------------------------*/
		
		register_taxonomy(
			'clientscategory',
			array('myclients'),
			array(
				'hierarchical' => true,
				'labels' => array( 'name'=>'Categories', 'add_new_item' => 'Add New Category', 'parent_item' => 'Parent Category'),
				'query_var' => true,
				'rewrite' => array( 'slug' => 'clientscategory' )
			)
		);
		
		/*----------------------------------------------------------------------
			Register client Post Type Function
		----------------------------------------------------------------------*/
		
		register_post_type('myclients',$args);
		
		//Enabling Support for Post Thumbnails
		add_theme_support( 'post-thumbnails');
	}
	
	
	/*========================================================================================================================================================================
		client Post Type All Themes Table Columns
	========================================================================================================================================================================*/
	
	/*----------------------------------------------------------------------
		Columns Declaration Function
	----------------------------------------------------------------------*/
	function mc_client_columns($mc_client_columns){

		$mc_client_columns = array(

			"cb" => "<input type=\"checkbox\" />",
			
			"thumbnail" => "Image",

			"title" => "Title",
			
			"mccategories" => "Categories",

			"author" => "Author",
			
			"date" => "Date",

		);

		return $mc_client_columns;

	}
	
	/*----------------------------------------------------------------------
		client Value Function
	----------------------------------------------------------------------*/
	function mc_client_columns_display($mc_client_columns, $post_id){
		
		global $post;
		
		$width = (int) 220;
		$height = (int) 144;
		
		if ( 'thumbnail' == $mc_client_columns ) {
			
			// thumbnail of WP 2.9
			$thumbnail_id = get_post_meta( $post_id, '_thumbnail_id', true );
			
			// image from gallery
			$attachments = get_children( array('post_parent' => $post_id, 'post_type' => 'attachment', 'post_mime_type' => 'image') );
			
			if ($thumbnail_id)
				
								
				$thumb = wp_get_attachment_image( $thumbnail_id, array($width, $height), true );
				
				elseif ($attachments) {
					foreach ( $attachments as $attachment_id => $attachment ) 
					{
						$thumb = wp_get_attachment_image( $attachment_id, array($width, $height), true );
					}
				}
				if ( isset($thumb) && $thumb ) 
				{
					echo $thumb;
				} 
				else 
				{
					echo __('None');
				}
		}
		
		if ( 'mccategories' == $mc_client_columns ) {
			
			$terms = get_the_terms( $post_id , 'clientscategory');
			$count = count($terms);
			
			if ( $terms ){
				
				$i = 0;
				
				foreach ( $terms as $term ) {
					echo '<a href="'.admin_url( 'edit.php?post_type=myclients&clientscategory='.$term->slug ).'">'.$term->name.'</a>';	
					
					if($i+1 != $count) {
						echo " , ";
					}
					$i++;
				}
				
			}
		}
		
	}
	
	/*----------------------------------------------------------------------
		Add manage_client_posts_columns Filter 
	----------------------------------------------------------------------*/
	add_filter("manage_myclients_posts_columns", "mc_client_columns");
	
	/*----------------------------------------------------------------------
		Add manage_client_posts_custom_column Action
	----------------------------------------------------------------------*/
	add_action("manage_myclients_posts_custom_column",  "mc_client_columns_display", 10, 2 );
	
	/*========================================================================================================================================================================
		Add Meta Box For client Post Type
	========================================================================================================================================================================*/
	
	/*----------------------------------------------------------------------
		add_meta_boxes Action For client Post Type
	----------------------------------------------------------------------*/
	
	add_action( 'add_meta_boxes', 'mc_client_add_custom_box' );
	
	/*----------------------------------------------------------------------
		Properties Of client Options Meta Box 
	----------------------------------------------------------------------*/
	
	function mc_client_add_custom_box() {
		add_meta_box( 
			'mc_client_sectionid',
			__( 'Options', 'mc_client_textdomain' ),
			'mc_client_inner_custom_box',
			'myclients'
		);
	}
	
	/*----------------------------------------------------------------------
		Content Of Clients Options Meta Box 
	----------------------------------------------------------------------*/
	
	function mc_client_inner_custom_box( $post ) {

		// Use nonce for verification
		wp_nonce_field( plugin_basename( __FILE__ ), 'mc_client_noncename' );
		
		?>
					
		<!-- Styles -->
		<link rel="stylesheet" href="<?php echo get_template_directory_uri().'/css/admin.css'; ?>">

		
		<!-- Client Website -->
							
		<p><label for="husamrayan_clientWebsite_input"><strong>Client Website</strong></label></p>
		
		http:// <input type="text" name="husamrayan_clientWebsite_input" id="husamrayan_clientWebsite_input" class="regular-text code" value="<?php echo get_post_meta($post->ID, 'husamrayan_clientWebsite', true); ?>" />
							
		<p><span class="description">Example: (www.example.com)</span></p>
		
		
		<!-- Image Size -->
		
		<p><label for="imageSize_list"><strong>Image Size</strong></label></p>
			
		<select id="imageSize_list" name="imageSize_list">
			<?php 
			
			for($i=100 ; $i>=10 ; $i-=5) { 
				echo '<option ';
				
				if(get_post_meta($post->ID, 'mc_imageSize', true) == '' && $i == 70)
				{
					echo 'selected ';
				}
				else if( get_post_meta($post->ID, 'mc_imageSize', true) == $i.'%' )
				{
					echo 'selected ';
				}
				
				echo 'value="'.$i.'%">'.$i.'%</option>';
			} ?>
        </select>
		
		<?php
	}
	
	/*========================================================================================================================================================================
		Save client Options Meta Box Function
	========================================================================================================================================================================*/
	
	function mc_save_clients_meta_box($post_id) 
	{
	
		/*----------------------------------------------------------------------
			Client Website
		----------------------------------------------------------------------*/
		if(isset($_POST['husamrayan_clientWebsite_input'])) {
			update_post_meta($post_id, 'husamrayan_clientWebsite', $_POST['husamrayan_clientWebsite_input']);
		}
		
		/*----------------------------------------------------------------------
			Image Size
		----------------------------------------------------------------------*/
		if(isset($_POST['imageSize_list'])) {
			update_post_meta($post_id, 'mc_imageSize', $_POST['imageSize_list']);
		}
		
	}
	
	/*----------------------------------------------------------------------
		Save client Options Meta Box Action
	----------------------------------------------------------------------*/
	add_action('save_post', 'mc_save_clients_meta_box');


?>