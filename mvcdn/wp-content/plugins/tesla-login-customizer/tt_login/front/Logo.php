<?php
/**
 * Class for logo manipulations
 */

namespace tt_login\front;


class Logo extends Front
{

    function __construct($selector = '')
    {
        parent::__construct();
        $this->open_selector($selector);

        if ( ! get_option('tt_login_logo_hide')){
            if (get_option('tt_login_logo_link'))
                add_filter('login_headerurl', array($this, 'link'));
            if (get_option('tt_login_logo_title'))
                add_filter('login_headertitle', array($this, 'title'));
        }
        $this->close_selector();
    }

    /**
     * Changes link on the logo; filter added in __construct
     * @return mixed|void
     */
    function link()
    {
        return get_option( 'tt_login_logo_link' );
    }

    function title()
    {
        return get_option( 'tt_login_logo_title' );
    }

}