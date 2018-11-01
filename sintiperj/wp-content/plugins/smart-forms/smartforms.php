<?php
/**
 * Plugin Name: Smart Forms
 * Plugin URI: http://smartforms.rednao.com/getit
 * Description: Place diferent form of donations on your blog...
 * Author: RedNao
 * Author URI: http://rednao.com
 * Version: 2.5.6
 * Text Domain: Smart Forms
 * Domain Path: /languages/
 * Network: true
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl-3.0
 * Slug: smartforms
 */

/**
 *	Copyright (C) 2012-2013 RedNao (email: contactus@rednao.com)
 *
 *	This program is free software; you can redistribute it and/or
 *	modify it under the terms of the GNU General Public License
 *	as published by the Free Software Foundation; either version 2
 *	of the License, or (at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program; if not, write to the Free Software
 *	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * Thanks to:
 * Jakub Stacho (http://www.iconfinder.com/iconsets/checkout-icons#readme)
 * Eggib (http://openclipart.org/detail/174878/)
 * Aha-Soft (http://www.iconfinder.com/iconsets/24x24-free-pixel-icons#readme)
 * Kevin Liew (http://www.queness.com/post/106/jquery-tabbed-interfacetabbed-structure-menu-tutorial)
 * Marcis Gasuns (http://led24.de/iconset/)
 */

require_once('smart-forms-config.php');
require_once(SMART_FORMS_DIR.'integration/smart-donations-integration-ajax.php');
require_once('smart-forms-ajax.php');
require_once(SMART_FORMS_DIR.'widgets/smart-form-widget.php');
require_once (SMART_FORMS_DIR.'php_classes/api/SFApiActions.php');
require_once (SMART_FORMS_DIR.'php_classes/api/SFApiFilters.php');

add_shortcode('sform','rednao_smart_form_short_code');


add_action('init', 'rednao_smart_forms_init');
add_action( 'wp_ajax_rednao_smart_forms_save', 'rednao_smart_forms_save' );
add_action( 'wp_ajax_rednao_smart_forms_dont_show_again', 'rednao_smart_forms_dont_show_again' );
add_action( 'wp_ajax_rednao_smart_form_short_code_setup', 'rednao_smart_form_short_code_setup' );
add_action( 'wp_ajax_rednao_smart_forms_entries_list', 'rednao_smart_forms_entries_list' );
add_action( 'wp_ajax_rednao_smart_forms_save_form_values','rednao_smart_forms_save_form_values');
add_action( 'wp_ajax_rednao_smart_forms_edit_form_values','rednao_smart_forms_edit_form_values');
add_action( 'wp_ajax_nopriv_rednao_smart_forms_save_form_values','rednao_smart_forms_save_form_values');
add_action( 'wp_ajax_rednao_smart_form_send_test_email','rednao_smart_form_send_test_email');
add_action('wp_ajax_rednao_smart_forms_submit_license','rednao_smart_forms_submit_license');
add_action('wp_ajax_rednao_smart_forms_execute_op','rednao_smart_forms_execute_op');
add_action('wp_ajax_rednao_smart_forms_generate_detail','rednao_smart_forms_generate_detail');
add_action('wp_ajax_rednao_smart_forms_get_form_element_info','rednao_smart_forms_get_form_element_info');
add_action('wp_ajax_rednao_smart_forms_get_form_options','rednao_smart_forms_get_form_options');
add_action('wp_ajax_rednao_get_context_tutorials','rednao_get_context_tutorials');
add_action('wp_ajax_rednao_smart_forms_send_test','rednao_smart_forms_send_test');

//api
$apiActions=new SFApiActions();
$apiActions->register_hooks();
$apiFilters=new SFApiFilters();
$apiFilters->register_hooks();
//integration

add_action('wp_ajax_rednao_smart_forms_get_campaigns','rednao_smart_forms_get_campaigns');



add_action('admin_init','rednao_smart_forms_plugin_was_activated');
register_activation_hook(__FILE__,'rednao_smart_forms_plugin_was_activated');

add_action('admin_menu','rednao_smart_forms_create_menu');





function rednao_smart_forms_create_menu(){

    add_menu_page('Smart Forms','Smart Forms','manage_options',"smart_forms_menu",'rednao_forms',plugin_dir_url(__FILE__).'images/smartFormsIcon.png');
    add_submenu_page("smart_forms_menu",'Entries','Entries','manage_options',__FILE__.'entries', 'rednao_smart_forms_entries');
    add_submenu_page("smart_forms_menu",'Support/Wish List','Support/Wish List','manage_options',__FILE__.'wish_list', 'rednao_smart_forms_wish_list');
	add_submenu_page("smart_forms_menu",'Tutorials','Tutorials','manage_options',__FILE__.'tutorials', 'rednao_smart_forms_tutorials');
	add_submenu_page("smart_forms_menu",'Add-Ons','Add-Ons','manage_options',__FILE__.'addons', 'rednao_smart_forms_add_ons');

	do_action('add_smart_forms_menu_items');

}

function smart_forms_indexing_is_requred()
{
	global $wpdb;
	$entriesCount=$wpdb->get_var('select count(*) from '. SMART_FORMS_ENTRY);
	$entriesDetailCount=$wpdb->get_var('select count(*) from '. SMART_FORMS_ENTRY_DETAIL);

	return $entriesCount!=0&&$entriesDetailCount==0;
}

function rednao_smart_forms_plugin_was_activated()
{
    $dbversion=get_option("SMART_FORMS_LATEST_DB_VERSION");
    if($dbversion<SMART_FORMS_LATEST_DB_VERSION )
    {
        require_once(ABSPATH.'wp-admin/includes/upgrade.php');

        $sql="CREATE TABLE ".SMART_FORMS_TABLE_NAME." (
        form_id int AUTO_INCREMENT,
        form_name VARCHAR(200) NOT NULL,
        element_options MEDIUMTEXT NOT NULL,
        client_form_options MEDIUMTEXT NOT NULL,
        form_options MEDIUMTEXT NOT NULL,
        donation_email VARCHAR(200),
        PRIMARY KEY  (form_id)
        ) COLLATE utf8_general_ci;";
        dbDelta($sql);

        $sql="CREATE TABLE ".SMART_FORMS_ENTRY." (
        entry_id int AUTO_INCREMENT,
        form_id int,
        date datetime NOT NULL,
        data MEDIUMTEXT NOT NULL,
        ip VARCHAR(39),
        reference_id VARCHAR(200),
        PRIMARY KEY  (entry_id)
        ) COLLATE utf8_general_ci;";
        dbDelta($sql);

		$sql="CREATE TABLE ".SMART_FORMS_ENTRY_DETAIL." (
        entry_detail_id int AUTO_INCREMENT,
        entry_id int,
        field_id varchar(50) NOT NULL,
        json_value MEDIUMTEXT NOT NULL,
        value MEDIUMTEXT NOT NULL,
        exvalue1 MEDIUMTEXT NOT NULL,
        exvalue2 MEDIUMTEXT NOT NULL,
        exvalue3 MEDIUMTEXT NOT NULL,
        exvalue4 MEDIUMTEXT NOT NULL,
        exvalue5 MEDIUMTEXT NOT NULL,
        exvalue6 MEDIUMTEXT NOT NULL,
        datevalue DATETIME,
        PRIMARY KEY  (entry_detail_id)
        ) COLLATE utf8_general_ci;";
		dbDelta($sql);

		if(smart_forms_indexing_is_requred())
			update_option('SMART_FORMS_REQUIRE_DB_DETAIL_GENERATION','y');

        update_option("SMART_FORMS_LATEST_DB_VERSION",SMART_FORMS_LATEST_DB_VERSION);
    }
}




function rednao_forms()
{
    include(SMART_FORMS_DIR.'main_screens/smart-forms-list.php');
}

function rednao_smart_form_short_code(/** @noinspection PhpUnusedParameterInspection */ $attr,$content)
{
    require_once('smart-forms-helpers.php');
    return rednao_smart_forms_load_form(null,$content,true);
}

function rednao_smart_forms_init()
{
    if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
        return;
    }

    if ( get_user_option('rich_editing') == 'true') {
        add_filter( 'mce_external_plugins', 'rednao_smart_forms_add_plugin' );
        add_filter( 'mce_buttons', 'rednao_smart_forms_register_button' );
    }
}

function rednao_smart_forms_add_plugin($plugin_array)
{
    wp_enqueue_script('isolated-slider',plugin_dir_url(__FILE__).'js/rednao-isolated-jq.js');
    wp_enqueue_style('smart-forms-Slider',plugin_dir_url(__FILE__).'css/smartFormsSlider/jquery-ui-1.10.2.custom.min.css');
    $plugin_array['rednao_smart_forms_button']=plugin_dir_url(__FILE__).'js/shortcode/smartFormsShortCodeButton.js';
    return $plugin_array;
}

function rednao_smart_forms_register_button($buttons)
{
    $buttons[]="rednao_smart_forms_button";
    return $buttons;
}

function rednao_smart_forms_entries()
{
    include(SMART_FORMS_DIR.'main_screens/smart-forms-entries.php');
}
function rednao_smart_forms_wish_list()
{
    include(SMART_FORMS_DIR.'main_screens/smart-forms-wishlist.php');
}

function rednao_smart_forms_tutorials()
{
	include(SMART_FORMS_DIR.'main_screens/smart-forms-tutorials.php');
}

require_once(SMART_FORMS_DIR.'pr/smart-forms-pr.php');

function rednao_smart_forms_add_ons()
{
	include(SMART_FORMS_DIR.'main_screens/smart-forms-add-ons.php');
}

function rednao_smart_forms_generate_detail()
{
	include(SMART_FORMS_DIR.'utilities/smart-forms-detail-generator.php');
}

function rednao_get_context_tutorials()
{
    $lastContextCheck=get_option("SmartFormsLastContextCheck");
    $contextJSON=get_option("SmartFormsContextJSON");
    $currentTime=new DateTime();
    if($lastContextCheck!=null&&$contextJSON!=null&&$lastContextCheck->diff($currentTime)->days<=7&&false)
    {
        header("Content-Type: application/json");
        echo $contextJSON;
    }else
    {
        $response=wp_remote_get("http://smartforms.rednao.com/api/context_tutorials.php");
        if(is_wp_error($response))
            die();
        header("Content-Type: application/json");
        $tutorials= $response["body"];
        update_option("SmartFormsContextJSON",$tutorials);
        update_option("SmartFormsLastContextCheck",$currentTime);
        echo $tutorials;
    }
    die();

}