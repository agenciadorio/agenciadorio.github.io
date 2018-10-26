<?php
/**
 * Plugin Name: Smart Forms
 * Plugin URI: http://smartforms.rednao.com/getit
 * Description: Place diferent form of donations on your blog...
 * Author: RedNao
 * Author URI: http://rednao.com
 * Version: 2.6.10
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

add_action( 'admin_notices', 'smart_forms_review_request' );
add_action('init', 'rednao_smart_forms_init');
add_action( 'wp_ajax_rednao_smart_forms_export', 'rednao_smart_forms_export' );
add_action( 'wp_ajax_rednao_smart_forms_save', 'rednao_smart_forms_save' );
add_action( 'wp_ajax_rednao_smart_forms_send_files', 'rednao_smart_forms_send_files' );
add_action( 'wp_ajax_nopriv_rednao_smart_forms_send_files', 'rednao_smart_forms_send_files' );
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
add_action('wp_ajax_smart_forms_never_show_again','smart_forms_never_show_again');
add_action('wp_ajax_smart_forms_remind_me_later','smart_forms_remind_me_later');
add_action('wp_ajax_smart_forms_skip_tutorial','smart_forms_skip_tutorial');
add_action('wp_ajax_rednao_smartformsexport','smart_forms_export');
add_action('wp_ajax_rednao_smart_forms_save_settings','smart_forms_save_settings');
add_action( 'admin_menu', 'smart_forms_remove_menu_items' );
add_action('admin_enqueue_scripts','rednao_smart_forms_admin_header');
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
add_filter('smart_forms_add_form_elements_dependencies','rednao_add_form_elements_dependencies');

function rednao_add_form_elements_dependencies($dependencies)
{
    wp_enqueue_script('smart-forms-data-store',SMART_FORMS_DIR_URL.'js/bundle/datastores_bundle.js',array(),SMART_FORMS_FILE_VERSION);
    wp_enqueue_script('smart-forms-polyfill',SMART_FORMS_DIR_URL.'js/utilities/polyfill.js',array(),SMART_FORMS_FILE_VERSION);
    wp_enqueue_script('smart-forms-promise',SMART_FORMS_DIR_URL.'js/utilities/es6-promise/es6-promise.min.js',array(),SMART_FORMS_FILE_VERSION);
    $dependencies[]='smart-forms-data-store';
    $dependencies[]='smart-forms-promise';
    return $dependencies;
}

function rednao_smart_forms_admin_header($hook){
    if($hook=='toplevel_page_smart_forms_menu')
    {
        add_action('admin_print_styles','smart_forms_filter_not_needed_styles');
        add_action('admin_print_scripts','smart_forms_filter_not_needed_scripts');
    }
}

function smart_forms_filter_not_needed_styles(){

    global $wp_styles;
    $styles=$wp_styles->queue;
    $queuedStyles=$wp_styles->queue;
    $allowedStyles=array('admin-bar','colors','ie','wp-auth-check');
    foreach($queuedStyles as $queue)
    {
        if(isset($wp_styles->registered[$queue]))
        {
            if($wp_styles->registered[$queue]->src)
            {
                if(strpos($wp_styles->registered[$queue]->src,'wp-includes/')!==false||strpos($wp_styles->registered[$queue]->src,'wp-admin/')!==false||$wp_styles->registered[$queue]->src===true)
                    continue;
                if(in_array($queue,$allowedStyles))
                    continue;

                if(strpos($queue,'smart-forms')!==false)
                    continue;

                wp_dequeue_style($queue);

            }
        }

    }

}


function smart_forms_filter_not_needed_scripts(){

    global $wp_scripts;
    $queuedScripts=$wp_scripts->queue;
    $allowedScripts=array('jquery','common','jquery-ui-core','admin-bar','utils','svg-painter','wp-auth-check');
    foreach($queuedScripts as $queue)
    {
        if(isset($wp_scripts->registered[$queue]))
        {
            if($wp_scripts->registered[$queue]->src)
            {
                if(strpos($wp_scripts->registered[$queue]->src,'wp-includes/')!==false||strpos($wp_scripts->registered[$queue]->src,'wp-admin/')!==false||$wp_scripts->registered[$queue]->src===true)
                    continue;
                if(in_array($queue,$allowedScripts))
                    continue;

                if(strpos($queue,'smart-forms')!==false)
                    continue;

                wp_dequeue_script($queue);

            }
        }

    }

}

function smart_forms_remove_menu_items() {
    remove_menu_page( 'edit.php?post_type=smartforms_preview' );
}

function smart_forms_never_show_again(){
    update_site_option('smart_forms_review','-1');
}

function smart_forms_remind_me_later(){
    update_site_option('smart_forms_review',time());
}


function rednao_smart_forms_create_menu(){

    add_menu_page('Smart Forms','Smart Forms','manage_options',"smart_forms_menu",'rednao_forms',plugin_dir_url(__FILE__).'images/smartFormsIcon.png');
    add_submenu_page("smart_forms_menu",'Entries','Entries','manage_options',__FILE__.'entries', 'rednao_smart_forms_entries');
    add_submenu_page("smart_forms_menu",'Support/Wish List','Support/Wish List','manage_options',__FILE__.'wish_list', 'rednao_smart_forms_wish_list');
	add_submenu_page("smart_forms_menu",'Tutorials','Tutorials','manage_options',__FILE__.'tutorials', 'rednao_smart_forms_tutorials');
    add_submenu_page("smart_forms_menu",'Settings','Settings','manage_options',__FILE__.'settings', 'rednao_smart_forms_settings');
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
    $dbversion=get_site_option("SMART_FORMS_LATEST_DB_VERSION");
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
        uniq_id VARCHAR(50),
        user_id VARCHAR(50),
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
            update_site_option('SMART_FORMS_REQUIRE_DB_DETAIL_GENERATION','y');

        update_site_option("SMART_FORMS_LATEST_DB_VERSION",SMART_FORMS_LATEST_DB_VERSION);
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

    register_post_type('smartforms_preview',
        array(
            'labels' => array(
                'name' => __( 'SmartFormsPreview' ),
                'singular_name' => __( 'SmartFormsPreview' )
            ),
            'public' => true,
            'has_archive' => false,
        )
    );
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

function rednao_smart_forms_settings()
{
    include(SMART_FORMS_DIR.'main_screens/smart-forms-settings.php');
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
        update_site_option("SmartFormsContextJSON",$tutorials);
        update_site_option("SmartFormsLastContextCheck",$currentTime);
        echo $tutorials;
    }
    die();

}




add_filter('query_vars','smart_forms_add_trigger');
function smart_forms_add_trigger($vars) {
    $vars[] = 'smartformspreview';
    return $vars;
}

add_action('template_redirect', 'smart_forms_check_if_preview');
function smart_forms_check_if_preview()
{
    $val=get_query_var('smartformspreview')?get_query_var('smartformspreview'):"";
    if($val==""&&isset($_GET["smartformspreview"]))
        $val=$_GET["smartformspreview"];

    if($val!="")
    {
        require_once('smart-forms-helpers.php');
        rednao_smart_forms_load_preview();
    }

}

function smart_forms_review_request(){
    $reviewCheckDate=get_option('smart_forms_review');
    if($reviewCheckDate===false)
    {
        update_site_option('smart_forms_review',time());
        return;
    }
    
    if($reviewCheckDate==-1||$reviewCheckDate+604800>time())
        return;

    

    

    ?>
    <style type="text/css">
        .sfReviewButton{
            display: inline-block;
            padding: 6px 12px;
            margin-bottom: 0;
            font-size: 14px;
            font-weight: 400;
            line-height: 1.42857143;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            -ms-touch-action: manipulation;
            touch-action: manipulation;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            background-image: none;
            border: 1px solid transparent;
            border-radius: 4px;
            color: #fff;
            background-color: #5bc0de;
            border-color: #46b8da;
            text-decoration: none;
        }

        .sfReviewButton:hover{
            color: #fff;
            background-color: #31b0d5;
            border-color: #269abc;
        }
    </style>
        <div class="notice notice-info sfReviewNotice" style="clear:both; padding-bottom:0;">
            <div style="padding-top: 5px;">
                <img style="display: inline-block;width:128px;vertical-align: top;" src="<?php echo SMART_FORMS_DIR_URL?>images/sflogo.jpg">

                <table style="width:calc(100% - 135px);float:right;">
                    <tbody  style="width:calc(100% - 135px);">
                        <tr>
                            <td>
                                <div style="display: inline-block; vertical-align: top;"><span style="font-size: 24px;font-family: Verdana">Are you enjoying Smart Forms?</span></div>
                            </td>
                            <td style="text-align: right">
                                <a class="SmartFormsNeverShowReview" href="#">Never show again</a>
                                <span>|</span>
                                <a class="SmartFormsRemindMeLater" href="#">Remind me later</a>
                            </td>
                        </tr>
                        <tr>
                            <td style="border: 1px dashed #dddddd;height: 60px;vertical-align: top; width: 50%;">
                                <div style="text-align: left;margin-top:5px;padding:1px;">
                                    <span style="font-size: 15px;color:#666666">Yes I Am =)</span>
                                    <div style="text-align: center">
                                        <a target="_blank" class="sfReviewButton" href="https://wordpress.org/support/plugin/smart-forms/reviews/?filter=5">Great!! could you leave a review?</a>
                                    </div>

                                </div>
                            </td>
                            <td style="border: 1px dashed #dddddd;height: 60px;vertical-align: top;width: 50%;">
                                <div style="text-align: left;margin-top:5px;padding:1px;">
                                    <span style="font-size: 15px;color:#666666">No i am not =(</span>
                                    <div style="text-align: center">
                                        <a target="_blank" class="sfReviewButton" href="https://smartforms.rednao.com/contactme/">Sorry for that! what can we do to improve?</a>
                                    </div>

                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
		</div>

    <script type="text/javascript">
        jQuery(document).ready( function($) {
            jQuery('.SmartFormsNeverShowReview').click(function(){
                $.post( ajaxurl, {
                    action: 'smart_forms_never_show_again'
                });
                jQuery('.sfReviewNotice').remove();
            });

            jQuery('.SmartFormsRemindMeLater').click(function(){
                $.post( ajaxurl, {
                    action: 'smart_forms_remind_me_later'
                });
                jQuery('.sfReviewNotice').remove();
            });
        });
    </script>
    <?php
}