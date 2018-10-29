<?php

class GFontsUI {

    static public function Success($txt) {
        print "<div style=\"min-height: 40px; font-weight: normal; border: 1px solid #ccc; background-image: url(" . GFONTS_PLUGIN_URL ."assets/40x40/successsq.png); background-size: 40px 40px; background-position: left center; background-position-x: 10px; background-repeat: no-repeat; background-color: #e4e4e4; margin: 10px; padding: 5px 5px 5px 60px; font-family: Verdana, HelveticaNeue-Light,Helvetica Neue Light,Helvetica Neue,sans-serif; border-radius: 0px; -moz-border-radius: 0px;\">";
        print $txt;
        print "</div>";
    }

    static public function Error($txt) {
        print "<div style=\"min-height: 40px; font-weight: normal; border: 1px solid #ccc; background-image: url(" . GFONTS_PLUGIN_URL . "assets/40x40/errorsq.png); background-size: 40px 40px; background-position: left center; background-position-x: 10px; background-repeat: no-repeat; background-color: #e4e4e4; margin: 10px; padding: 5px 5px 5px 60px; font-family: Verdana, HelveticaNeue-Light,Helvetica Neue Light,Helvetica Neue,sans-serif; border-radius: 5px; -moz-border-radius: 5px;\">";
        print $txt;
        print "</div>";
    }

    static public function Notice($txt) {
        print "<div style=\"min-height: 40px; font-weight: normal; border: 1px solid #ccc; background-image: url(" .  GFONTS_PLUGIN_URL ."assets/40x40/infosq.png); background-size: 40px 40px; background-position: left center; background-position-x: 10px; background-repeat: no-repeat; background-color: #e4e4e4; margin: 10px; padding: 5px 5px 5px 60px; font-family: Verdana, HelveticaNeue-Light,Helvetica Neue Light,Helvetica Neue,sans-serif; border-radius: 0px; -moz-border-radius: 0px;\">";
        print $txt;
        print "</div>";
    }

}
