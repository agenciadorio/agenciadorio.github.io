<?php
/**
 * Created by JetBrains PhpStorm.
 * User: edseventeen
 * Date: 3/29/13
 * Time: 9:29 AM
 * To change this template use File | Settings | File Templates.
 * Thanks to:
 * Liam McKay (http://wefunction.com/contact/)
 */

global $wpdb;
if(!defined('ABSPATH'))
    die('Forbidden');

define('SMART_FORMS_PLUGIN_NAME',dirname(plugin_basename(__FILE__)));
define('SMART_FORMS_DIR',WP_PLUGIN_DIR.'/'.SMART_FORMS_PLUGIN_NAME.'/');
define('SMART_FORMS_DIR_URL',plugin_dir_url(__FILE__));
define('SMART_FORMS_TABLE_NAME',$wpdb->prefix . "rednao_smart_forms_table_name");
define('SMART_FORMS_ENTRY',$wpdb->prefix . "rednao_smart_forms_entry");
define('SMART_FORMS_ENTRY_DETAIL',$wpdb->prefix . "rednao_smart_forms_entry_detail");
define('SMART_FORMS_LATEST_DB_VERSION',12);
define('SMART_FORMS_REDNAO_URL',"http://smartforms.rednao.com/");
define('SECURE_SMART_FORMS_REDNAO_URL',"http://smartforms.rednao.com/");
define('SMART_FORMS_SANDBOX','n')


?>