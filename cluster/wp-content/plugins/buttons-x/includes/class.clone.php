<?php
/**
 * Buttons X - Clone Class
 *
 * This file is used to clone buttons.
 * 
 *
 * @package Buttons X
 * @since 0.1
 */

// Make sure we don't expose any info if called directly
if ( !defined( 'ABSPATH' ) )
	exit;

if( !class_exists( 'BtnsxClone' ) ) {
	
	class BtnsxClone {

		private static $instance;

		/**
		 * Initiator
		 * @since 0.1
		 */
		public static function init() {
			return self::$instance;
		}

		/**
		 * Constructor
		 * @since 0.1
		 */
		public function __construct() {
			add_filter( 'post_row_actions', array( $this, 'row_actions' ), 10, 2 );
			add_action( 'admin_init', array( $this, 'initiate_clone' ) );
		}

		/**
		 * Function to add clone link to row actions on buttons X post type
		 * @since  0.1
		 * @param  string    $actions default actions
		 * @param  WP_Post   $post post object
		 * @return string
		 */
		public function row_actions( $actions, WP_Post $post ) {
	        if ( !in_array( $post->post_type, array( 'buttons-x', 'buttons-x-social', 'buttons-x-dual', 'buttons-x-cs' ) ) ) {
	            return $actions;
	        }
	        if ( $post->post_type == 'buttons-x' ) {
	        	$actions[ 'clone' ] = '<a href="edit.php?post_type=buttons-x&btnsx-clone=' . $post->ID . '">' . __( 'Clone', 'buttons-x' ) . '</a>';
	        }
	        return $actions;
	    }

	    /**
	     * Function to initiate cloning
	     * @since  0.1
	     * @return 
	     */
	    public function initiate_clone() {
	        // Listen for form submission
	        if( empty( $_GET[ 'btnsx-clone' ] ) ) {
	            return;
	        }
	        // Check permissions and nonces
	        if( !current_user_can( 'manage_options' ) ) {
	            wp_die();
	        }
	        // Get the original post
			$id = $_GET[ 'btnsx-clone' ];
			$post = get_post( $id );
			$status = 'publish';

			// Copy the post and insert it
			if ( isset( $post ) && $post != null ) {
				$new_id = $this->create_clone( $post, $status );
				if ( $status == '' ){
					// Redirect to the post list screen
					wp_redirect( admin_url( 'edit.php?post_type='.$post->post_type ) );
				} else {
					// Redirect to the edit screen for the new draft post
					wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_id ) );
				}
				exit;
			} else {
				$post_type_obj = get_post_type_object( $post->post_type );
				wp_die( esc_attr( __( 'Copy creation failed, could not find original:', 'buttons-x' ) ) . ' ' . htmlspecialchars( $id ) );
			}
	    }

	    /**
	     * Function to clone a button
	     * @since  0.1
	     * @param  object    $post
	     * @param  string    $status
	     * @return int 	new post id
	     */
		public function create_clone( $post, $status = 'publish' ) {

			// We don't want to clone revisions
			if ( $post->post_type == 'revision' ) return;

			$new_post_author = $this->get_current_user();

			$new_post = array(
				'post_author' => $new_post_author->ID,
				'post_status' => 'publish',
				'post_title' => $post->post_title . ' [ Clone ]',
				'post_type' => $post->post_type,
			);

			$new_post_id = wp_insert_post( $new_post );

			// post type
			$post_type = 'buttons-x';

			// set a proper slug
			$post_name = wp_unique_post_slug( $post->post_name . '-' . $new_post_id, $new_post_id, 'publish', $post_type, '' );
			$new_post = array();
			$new_post[ 'ID' ] = $new_post_id;
			$new_post[ 'post_name' ] = $post_name;

			// add post meta
			$meta_key = 'btnsx';
			$post_meta = get_post_meta( $post->ID, $meta_key, true );
			add_post_meta( $new_post_id, $meta_key, $post_meta );

			// update the post into the database
			wp_update_post( $new_post );

			// set taxonomies
			global $wpdb;
			if ( isset( $wpdb->terms ) ) {
				$taxonomies = get_object_taxonomies( $post->post_type );
				foreach ( $taxonomies as $taxonomy ) {
					$post_terms = wp_get_object_terms( $post->ID, $taxonomy, array( 'orderby' => 'term_order' ) );
					$terms = array();
					for ( $i = 0; $i < count( $post_terms ); $i++ ) {
						$terms[] = $post_terms[ $i ]->slug;
					}
					wp_set_object_terms( $new_post_id, $terms, $taxonomy );
				}
			}
			
			return $new_post_id;
		}

		/**
		 * Function to get current user id
		 * @since  0.1
		 * @return object 	contains current user information
		 */
		public function get_current_user() {
			if ( function_exists( 'wp_get_current_user' ) ) {
				return wp_get_current_user();
			} else if ( function_exists( 'get_currentuserinfo' ) ) {
				global $userdata;
				get_currentuserinfo();
				return $userdata;
			} else {
				$user_login = $_COOKIE[ USER_COOKIE ];
				$sql = $wpdb->prepare( "SELECT * FROM $wpdb->users WHERE user_login=%s", $user_login );
				$current_user = $wpdb->get_results( $sql );			
				return $current_user;
			}
		}


	} // Clone Class

}

/**
 *  Kicking this off
 */

$btn_options = new BtnsxClone();
$btn_options->init();