<?php

/*
=====================================================================================================
 # Custom POST FIELDS SETUP TO HIDE THE TITLE OF THE PAGES IN SINGULAR POSTS IF CHECKED
======================================================================================================
*/

if (!class_exists('st_hide_titles')) {

	class st_hide_titles {

	public function __construct() {
		add_action('add_meta_boxes', array($this, 'st_hide_title_init'));
      	add_action('save_post', array($this, 'st_hide_title_save'));
      	add_action( 'wp_head', array( $this, 'st_hide_title_css' ), 3000 );

	}

    public function st_hide_title_init() {
      add_meta_box( 
        'hide-titles',
        'Hide Titles',
        array($this, 'st_hide_title_box'),
        'page',
        'side',
        'high'
      );
    }

    public function st_hide_title_box() {
    	global $post;
    	$checked = get_post_meta($post->ID, 'st_hide_title_check_option',true);
        $checked1 = get_post_meta($post->ID, 'st_hide_breadcrumb_check_option',true);
    	
    	$check_results = '';
    	if ($checked) {
    		$check_results = 'checked="checked"';
    	}
    	else {
    		$check_results = '';
    	}
    	echo '<input type="checkbox" name="st_hide_title_check_option" class="widefat" '.$check_results.'/>
    		 <label><strong>Hide Page Titles</strong></label><br/>';
    }

    public function st_hide_title_save($post_id) {
    	update_post_meta($post_id, 'st_hide_title_check_option', $_POST['st_hide_title_check_option']);
    }

    public function st_hide_title_css() {
        global $post;
        $class = get_theme_mod('shk_hide_title_class_name');
        $is_shown_to_be_hidden = get_post_meta($post->ID, 'st_hide_title_check_option', true);
        if (empty($is_shown_to_be_hidden)) {
            return;
        } 
        else {
            if (empty($class)) {
                ?>
                <style type="text/css">
                    #post-<?php echo $post->ID; ?> .entry-title,
                    #post-<?php echo $post->ID; ?> .entry-header h1
                    {display:none;}
                </style>
                <?php
            } else {
            ?>
            <style type="text/css">
            #post-<?php echo $post->ID; ?> .<?php echo $class; ?>
             {display:none;}
            </style>

        <?php   } 
    }
        
    }

    }

    $st_hide_titles = new st_hide_titles();
}

?>