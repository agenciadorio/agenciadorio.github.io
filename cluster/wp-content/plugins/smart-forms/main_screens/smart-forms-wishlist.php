<?php
/**
 * Created by JetBrains PhpStorm.
 * User: edseventeen
 * Date: 4/1/13
 * Time: 8:44 PM
 * To change this template use File | Settings | File Templates.
 */

    wp_enqueue_script("jquery");
?>

<p style="font-size:25px; font-weight: bold;color: red;">If you have an idea, or need support, let me know!!</p>

<br/>
<p style="font-size: 20px; font-weight: bold;">You can create a support ticket here: <a target="_blank" href="https://wordpress.org/support/plugin/smart-forms">https://wordpress.org/support/plugin/smart-forms</a></p>
<!--
<?php
$result=apply_filters("smart_forms_lc_is_valid_with_options",array());
if($result["is_valid"]){
?>
<p style="font-size: 20px; font-weight: bold;">You can create a support ticket either by sending an email at <a target="_blank" href="mailto:tickets@smartforms.uservoice.com">tickets@smartforms.uservoice.com</a>   or going to the <a href="https://smartforms.uservoice.com">Smart Forms Support Forum (click "Contact Support")</a></p>
<?php }else{?>
    <p style="font-size: 20px; font-weight: bold;">You can create a support ticket here: <a target="_blank" href="https://wordpress.org/support/plugin/smart-forms">https://wordpress.org/support/plugin/smart-forms</a></p>
<?php } ?>

