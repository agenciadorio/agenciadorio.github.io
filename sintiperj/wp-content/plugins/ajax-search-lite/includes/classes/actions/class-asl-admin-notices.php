<?php
if (!defined('ABSPATH')) die('-1');

if (!class_exists("WD_ASL_Compatibility_AdminNotices")) {
    /**
     * Class WD_ASL_Compatibility_AdminNotices
     *
     * Hide admin notices, they are so annoying
     *
     * @class         WD_ASL_Compatibility_Action
     * @version       1.0
     * @package       AjaxSearchLite/Classes/Actions
     * @category      Class
     * @author        Ernest Marcinko
     */
    class WD_ASL_Compatibility_AdminNotices extends WD_ASL_Action_Abstract {

        public function handle() {
            global $wp_filter;

            if(is_network_admin() && isset($wp_filter["network_admin_notices"])) {
                unset($wp_filter['network_admin_notices']);
            } elseif(is_user_admin() && isset($wp_filter["user_admin_notices"])) {
                unset($wp_filter['user_admin_notices']);
            } else {
                if(isset($wp_filter["admin_notices"])) {
                    unset($wp_filter['admin_notices']);
                }
            }

            if(isset($wp_filter["all_admin_notices"])) {
                unset($wp_filter['all_admin_notices']);
            }
        }

        // ------------------------------------------------------------
        //   ---------------- SINGLETON SPECIFIC --------------------
        // ------------------------------------------------------------
        public static function getInstance() {
            if ( ! ( self::$_instance instanceof self ) ) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }
    }
}