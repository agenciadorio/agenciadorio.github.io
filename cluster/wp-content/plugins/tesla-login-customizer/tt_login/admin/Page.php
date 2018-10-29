<?php
/**
 * Page/Menu Object ( subPage/subMenu)
 */
namespace tt_login\admin;
use tt_login\PLUGIN_FW;
use tt_login\View;

class Page extends PLUGIN_FW{
    public $slug;
    public $parent_page;
    public $parent_slug;
    public $args;
    public $hook_suffix;
    public $view;
    public $page_view;
    private $styles = array();
    private $scripts = array();
    private $main_tab;
    public $dependencies;

    /**
     * @param array $args add_menu_page parameters
     * @param null|Page $parent_page Page object or page slug
     * @param array $data array with keys extracted and sent to the template
     */
    function __construct(array $args, Page $parent_page = null, $data = array(), $icon = false){
        $defaults = array(
            $this->get_plugin_name() . ' settings',                   //$page_title The text to be displayed in the title tags of the page when the menu is selected
            $this->get_plugin_name(),                                 //$menu_title The on-screen name text for the menu
            'manage_options',                                         //capabilities
            $this->get_plugin_slug() . '_options',                    //Slug for url
            array($this, 'plugin_options_page'),                   //Function to render The actual page
            $icon,   //menu icon
            //NULL,                                                     //position
            //'load_func'     => '',                                    //Function called when menu page loaded (used for admin_enqueue_scripts)
            //'enqueue_func'  => array($this,'settings_page_scripts')     //Function called at admin_enqueue_scripts hook (called from default load_func)
        );

        $this->args = $args + $defaults;

        if($parent_page)
            $this->parent_page = $parent_page;
        $this->slug = $this->args[3];
        $this->view = $this->args[4];
        $data['page_id'] = $this->slug;
        $data['page_title'] = $this->args[0];

        $this->page_view = new View($this->slug, $data, true);

        add_action('admin_menu', array($this, 'create_page'));

    }

    public function create_page(){
        $args = $this->args;

        //adding first required parameter for add_submenu_page if is a subpage
        if(isset($this->parent_page)) {
            if (is_object($this->parent_page))
                $this->parent_slug = $this->parent_page->get_slug();
            elseif(is_string($this->parent_slug))
                $this->parent_slug = $this->parent_page;
            array_unshift($args, $this->parent_slug);
        }

        $this->hook_suffix = call_user_func_array( $this->parent_slug ? 'add_submenu_page' : 'add_menu_page', $args);
        if(!empty($this->styles) || !empty($this->scripts))
            add_action( 'load-' . $this->hook_suffix, array($this, 'load_menu_page') );
    }

    function get_slug(){
        return $this->slug;
    }

    public function plugin_options_page(){
        if( !$this->page_view->load() )
            echo '<p class="notice update-nag">Default options page. Override by creating view file with same name as <code>
            page_slug</code> , 4th parameter passed to <code>add_page</code> or <code>add_subpage</code> methods, or
            call the <code>set_page_view($view_name)</code> on the page object</p>';
    }

    /**
     * @param string | $name Name of the template file to render on load
     */
    public function set_page_view($name){
        $this->page_view->set_view($name);
    }

    /**
     * @param array|$args wp_enqueue_style args written in an array style
     */
    public function add_style(array $args){
        $this->styles[] = $args;
    }

    /**
     * @param array $args wp_enqueue_script args written in an array form
     */
    public function add_script(array $args){
        $this->scripts[] = $args;
    }

    public function load_menu_page(){
        add_action( 'admin_enqueue_scripts' , array($this, 'page_scripts') );
        add_action( 'admin_enqueue_scripts' , array($this, 'actions') , 11 );
    }

    public function page_scripts(){
        foreach($this->styles as $style_args){
            call_user_func_array('wp_enqueue_style', $style_args);
        }
        foreach($this->scripts as $script_args){
            if($script_args[0] == 'wp_enqueue_media')
                wp_enqueue_media();
            call_user_func_array('wp_enqueue_script', $script_args);
        }
    }

    public function actions(){
        wp_localize_script('tt-admin-deps', 'tt_deps' , $this->dependencies );
    }

    /**
     * @param string $name name of the tab
     * @param string $description Description of the tab usually above the options
     * @param string $icon
     * @return Tab
     */
    public function add_tab($name, $description = '', $icon = ''){
        //Make first called tab main ( show by default when user clicks on menu )
        if ( $this->main_tab )
            return new Tab($name, $description, $icon, $this);
        else
            return $this->main_tab = new Tab( $name, $description, $icon, $this, true );
    }

}