<?php
/**
 * Class to handle tabs in admin settings pages
 */

namespace tt_login\admin;


use tt_login\PLUGIN_FW;

class Tab extends PLUGIN_FW{

    private $name;
    private $icon;
    public $slug;
    public $parent_page;
    private $tab_active = false;
    public $hide_submit = false;


    /**
     * @param string $name
     * @param string $description
     * @param string $icon
     * @param Page $parent_page
     * @param bool|false $main_tab
     */
    function __construct($name, $description, $icon = '', Page $parent_page, $main_tab = false){
        $this->parent_page = $parent_page;
        $this->name = $name;
        $this->description = $description;
        $this->icon = $icon;
        $this->slug = sanitize_title('tt_login' . $name, 'Default Tab Name');
        $this->main_tab = $main_tab;
        add_action( 'admin_init' , array( $this, 'register_tab_settings') );
        //Hooking to action in the view to display tab links
        add_action( 'tt_login_admin_tabs_links_' . $this->parent_page->get_slug() , array( $this , 'render_tab_link' ) );
        //Hooking to action in the view to display tab contents
        if( ( !isset( $_GET[ 'tab' ] ) && $main_tab ) || ( isset( $_GET[ 'tab' ] ) && $_GET['tab'] == $this->slug ) ) {
            $this->tab_active = true;
            add_action('tt_login_admin_tabs_contents_' . $this->parent_page->get_slug(), array($this, 'render_tab_content'));
        }

    }

    public function register_tab_settings(){
        add_settings_section( $this->slug, false, array($this, 'tab_heading'), $this->slug );
    }

    public function tab_heading(){
        echo esc_html($this->description);
    }

    /**
     * @param array $args Option parameters for creating an option in admin page/tab {
     *      @var string 0 Id of option (used later for get_option(id))
     *      @var string 1 Title of Option
     *      @var string 2 Type of option
     * }
     * @return Option
     */
    public function add_option(array $args, array $custom_vars = array(), array $dependencies = array()){
        return new Option($args, $this, $custom_vars, $dependencies);
    }

    public function render_tab_link(){
        $tab_active = $this->tab_active ? ' nav-tab-active' : '';
        echo "<a href='?page={$this->parent_page->slug}&tab=$this->slug' class='wp-ui-text-primary nav-tab$tab_active'>";
        if(!empty($this->icon)) echo "<span class='dashicons {$this->icon}'></span> ";
        echo esc_html($this->name) . "</a>";
    }

    public function render_tab_content(){
        echo "<div class='tab-content tt-tab-" . esc_attr($this->slug) . "'><form action='options.php' method='post'>";
        settings_fields($this->slug);
        do_settings_sections($this->slug);
        if(!$this->hide_submit)
            submit_button(__('Save Settings','tt_login'));
        echo "</form></div>";
    }



}