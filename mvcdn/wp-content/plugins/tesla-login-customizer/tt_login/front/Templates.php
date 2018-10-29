<?php
/**
 * Class for Templates management
 */

namespace tt_login\front;


class Templates extends Front
{
    public $formTemplate;
    public $templateConfig;
    public $changedNow;
    public $restoreNow;
    public $previousTemplate;
    public $previousSettings;
    public $template_uri;

    function __construct($selector = '')
    {
        parent::__construct();
        $this->changedNow = isset( $_GET['enabled'] ) && !isset( $_GET['settings-updated'] ) ? substr( $_GET['enabled'], 9 ) : false;
        $this->restoreNow = isset( $_GET['disabled'] ) ;

        if($this->changedNow)
            $this->formTemplate = substr($_GET['enabled'], 9);
        elseif(get_option($this->prefix( 'template') ))
            $this->formTemplate = get_option($this->prefix( 'template') );
        else
            $this->formTemplate = 'default';

        $this->template_uri = $this->get_plugin_uri() . '/themes/' . $this->formTemplate ;

        if ($this->changedNow) {
            $this->get_template_config();
            if($this->restoreNow) {
                $this->get_prev_state();
                $this->restore_prev_settings();
            } else {
                $this->update_template_options();
                $this->get_prev_state();
            }
            update_option($this->prefix( 'template' ) , $this->changedNow);
            add_action($this->prefix( 'admin_notices' ) , array($this, 'template_enabled_notice'));
        }
        //if( isset( $_GET['settings-updated'] ) || $this->changedNow || isset($_GET['debug']) )
            $this->compile_template_less();

        if ( $this->formTemplate && $this->formTemplate !== 'default' ) {
            self::$body_classes[] = 'tt-login-form-template-' . $this->formTemplate;
        }
        self::$css .= get_option($this->prefix( 'css') );
    }

    function get_prev_state(){
        $this->previousSettings = get_option($this->prefix( 'previous_options') );
        if($this->previousSettings && !empty($this->previousSettings[$this->prefix( 'template' )]))
            $this->previousTemplate = $this->previousSettings[$this->prefix( 'template' )];
    }

    function get_template_config(){
        if( $this->changedNow !== 'default' && file_exists($this->get_plugin_dir() . '/static/themes/' . $this->formTemplate . '/config.json') )
            $this->templateConfig = json_decode(wp_remote_retrieve_body(wp_remote_get($this->get_plugin_uri() . '/themes/' . $this->formTemplate . '/config.json')),true);
    }

    function update_template_options(){
        $user_options = array(); //array to save user previous options
        $plugin_options = $this->get_plugin_options();
        if( $this->changedNow !== 'default' ) {
            if( empty($this->templateConfig ) )
                return;
            $options = $this->templateConfig['options'];
            //replace all options from config
            if(!empty($plugin_options))
                foreach ($plugin_options as $plugin_option) {
                    $user_options[$plugin_option] = get_option($plugin_option); //adding to previous options available to restore later
                    if(isset($options[$this->unprefix($plugin_option)])) {
                        $opt = $options[$this->unprefix($plugin_option)];
                        if (isset($opt)) {
                            if(is_array($opt) && !empty($opt['if'])) { //if is array option with second parameter true then check if user has option dont overwrite it
                                if(!get_option($plugin_option))
                                    update_option($plugin_option, $this->process_option($opt['if']));
                            } else {
                                update_option($plugin_option, $this->process_option($opt));
                            }
                        }
                    }
                }
        }else{
            if(!empty($plugin_options))
                foreach ($plugin_options as $plugin_option) {
                    $user_options[$plugin_option] = get_option($plugin_option); //adding to previous options available to restore later
                        update_option($plugin_option, '');
                }
        }
        update_option( $this->prefix( 'previous_options' ) ,$user_options ) ; //saving previous options available to restore later
    }

    function process_option($raw_option){
        $opt = str_replace('{theme}',$this->template_uri ,$raw_option);
        $opt = str_replace('{theme/img}',$this->template_uri . '/img',$raw_option);
        return $opt;
    }

    function restore_prev_settings(){
        $plugin_options = $this->get_plugin_options();
        $prev_settings = get_option($this->prefix( 'previous_options'));
        if(!empty($plugin_options) && !empty($prev_settings))
            foreach ($plugin_options as $plugin_option) {
                update_option($plugin_option, $prev_settings[$plugin_option]);
            }
    }

    function template_enabled_notice(){
        if( !$this->restoreNow ) {
            $uri = add_query_arg(array('disabled' => 'template-' . $this->formTemplate, 'enabled' => 'template-' . $this->previousTemplate));
            echo "<div class='update-nag notice is-dismissible'><p>" . __('Template enabled successfully. This may have overwritten your custom settings.', 'tt_login') . "
                    <a href='$uri'>" . __('Restore previous Settings.', 'tt_login') . "</a></p>
                </div>";
        }else{
            echo "<div class='updated'><p>" . __('Settings Restored','tt_login') . "</p></div>";
        }
    }

    function compile_template_less(){
        require $this->get_plugin_dir() . 'lib/less.php/Less.php';
        try{
            $parser = new \Less_Parser();
            $css = '';
            if(file_exists($this->get_plugin_dir() . 'static/themes/' . $this->formTemplate . '/template.less')) {
                $parser->parseFile($this->get_plugin_dir() . 'static/themes/templates-main.less', $this->get_plugin_uri() . '/themes/');
                $options_hash = $this->get_options_hash();
                if($options_hash)
                    $parser->parse($options_hash);
                $css = $parser->getCss();
            }
            //print "<pre>" . $css . "</pre>";
            update_option($this->prefix( 'css'),$css);
        }catch(\Exception $e){
            print $e->getMessage();
        }
    }

    function get_options_hash(){
        $plugin_options = $this->get_plugin_options();
        $options_hash = '';
        foreach ($plugin_options as $plugin_option) {
            $opt_val = get_option($plugin_option);
            $opt_short_name = $this->unprefix($plugin_option);
            if( !$opt_val || $opt_short_name === 'custom_css' || $opt_short_name === 'custom_js' ) {  //codemirror has weird chars less parser fails on them
                if( $opt_short_name === 'template' ) //set template option to 'default' if empty
                    $opt_val = 'default';
                else
                    $opt_val = 'undefined';
            } else {
                if (is_array($opt_val)) {   //if group of inputs
                    if(!array_filter($opt_val))
                        $opt_val = 'undefined';
                    else {
                        foreach ($opt_val as &$val) {
                            if (empty($val))
                                $val = '0';
                            else
                                $val = $val . "px";
                        }
                        $opt_val = implode(' ', $opt_val);
                    }
                } elseif (strpos($opt_val, 'http://') !== false || strpos($opt_val, 'https://') !== false) {
                    $opt_val = "'$opt_val'";
                }
            }
            $options_hash .= "@$opt_short_name : " . $opt_val . ";";
        }
        //print($options_hash);

        return $options_hash;
    }

}