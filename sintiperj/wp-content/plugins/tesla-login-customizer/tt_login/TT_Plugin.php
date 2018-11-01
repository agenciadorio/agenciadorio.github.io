<?php
/*
 * One Class to rule them all
 */
namespace tt_login;

use tt_login\admin\Page;
use tt_login\front\Background;
use tt_login\front\Front;
use tt_login\front\Logo;
use tt_login\front\Form;
use tt_login\front\Templates;
use tt_login\front\Typography;

class TT_Plugin extends PLUGIN_FW {

    /**
     * @param string $name Name of the plugin
     * @param string $slug Slug of the plugin
     */
    function __construct($name,$slug){
        $this->set_plugin_name($name);
        $this->set_plugin_slug($slug);
        $this->set_plugin_dir(plugin_dir_path( __DIR__ ) );
        $this->set_plugin_uri(plugins_url('static',__DIR__));
        add_action( 'tt_login_admin_notices', array( $this , 'admin_notices' ) );
        add_action( 'init' , array( $this , 'frontend' ) );
        $this->tt_load_textdomain();
        add_filter( 'plugin_action_links_tesla-login-customizer/tt_login.php', array( $this, 'plugin_settings_link' ) );
    }

	/**
	 * @param array $args
	 *
	 * @param array $data
	 * @param string $icon
	 * @return Page
	 */
    function add_page(array $args, $data = array(), $icon = 'dashicons-admin-generic'){
        return new Page($args, null, $data, $icon);
    }


	/**
	 * @param array $args
	 * @param Page|null $parent_page_obj
	 * @param array $data
	 * @return Page
	 */
    function add_subpage(array $args, Page $parent_page_obj = null, $data = array()){
        try {
            if (is_null($parent_page_obj))
                throw new \Exception('Parent Page object not passed to add_subpage method.');
        } catch (\Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }

        return new Page($args, $parent_page_obj, $data);
    }

    function admin_notices(){
        if(!empty($_GET['settings-updated']) && $_GET['settings-updated'] == true)
            echo "<div class='updated fade'><p>" . __('Plugin options updated successfully','tt_login') . "</p></div>";
    }


    function plugin_settings_link($links){
        $settings_link = '<a href="' . admin_url( 'admin.php?page=tt_login_options' ) . '">' . __('Settings','tt_login') . '</a>';
        array_unshift($links, $settings_link);
        return $links;
    }

    function frontend(){
        if( tt_is_login_page() ) {
            Front::init();
            new Logo('.tt-login #login > h1 a');
            new Typography('.tt-login #login');
            new Form('.tt-login #login');
        }

        new Templates('body');
    }


    /**
     * Load plugin textdomain.
     *
     * @since 1.0.0
     */
    function tt_load_textdomain() {
        load_plugin_textdomain( 'tt_login', false, plugin_basename( $this->get_plugin_dir() ) . '/languages' );
    }

}