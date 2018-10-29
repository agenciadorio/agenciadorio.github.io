<?php
/**
 * Class for typography manipulations
 */

namespace tt_login\front;


class Typography extends Front
{
    protected $gfonts = array();
    protected $gfonts_subsets = array();

    function __construct($selector){
        if( get_option($this->prefix('font_family')) && get_option($this->prefix('font_family')) !== 'Open Sans' ) {
            $variation_string = '';
            $variations = get_option($this->prefix('font_variations'));
            if(!empty($variations))
                $variation_string = implode(',',$variations);
            $this->gfonts[] = get_option($this->prefix('font_family')) . ":" . $variation_string;
            if (get_option($this->prefix('font_subset')))
                $this->gfonts_subsets = get_option($this->prefix('font_subset'));
            self::$body_classes[] = 'tt-custom-font';
        }

        $this->load_gfonts();
    }

    function load_gfonts(){
        if(!empty($this->gfonts)) {
            $query_args = array(
                'family' => urlencode(implode('|', $this->gfonts))
            );

            if ( ! empty($this->gfonts_subsets))
                $query_args['subset'] = urlencode(implode(',', $this->gfonts_subsets));

            $fonts_url = add_query_arg($query_args, 'https://fonts.googleapis.com/css');

            $escaped_fonts_url = esc_url_raw($fonts_url);
            $this->add_style(array('tt-login-g-fonts', $escaped_fonts_url));
        }
    }

}