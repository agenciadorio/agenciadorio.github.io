<?php
include_once(SMART_FORMS_DIR.'smart-forms-license.php');
require_once(SMART_FORMS_DIR.'smart-forms-bootstrap.php');




if(!defined('ABSPATH'))
    die('Forbidden');

if (isset($_GET['action'])) {
    $action=$_GET['action'];
}else
    $action="";

if($action=='debugemail')
{
    require_once SMART_FORMS_DIR.'main_screens/smart-forms-email-helper.php';
    return;
}

if($action==="add"||$action=="clone"){
    global $wpdb;
    $result=$wpdb->get_var("SELECT count(*) FROM ".SMART_FORMS_TABLE_NAME);
    $checkWithOptionsResult=smart_forms_check_license_with_options($error);
    if($result>=3&&!$checkWithOptionsResult["is_valid"])
    {
        smart_forms_load_license_manager("Sorry, this version support up to three forms only");
    }else{
        if($action==="add")
        {
            include(SMART_FORMS_DIR.'main_screens/smart-forms-add-new.php');
            return;
        }
    }

}

if($action=='upload')
{
    include SMART_FORMS_DIR.'smart-forms-import-form.php';
}

if($action!=="edit")
{

	echo "<div class='bootstrap-wrapper'><h1>".__("Forms")."</h1>";
	echo sprintf(' <a href="?page=%s&action=%s" class="btn btn-default btn-success" ><span class="glyphicon glyphicon-plus" ></span>Add New</a>',$_REQUEST['page'],'add');
    ?>

    <form style="display: inline; margin-left:10px;" id="sfFileUploadForm" method="post" enctype="multipart/form-data" target="_self" action="?page=<?php echo $_REQUEST['page'] ?>&action=upload">
        <button  class="btn btn-default btn-default" onclick="document.getElementById('sfFileUpload').click();return false;">
            <span class="glyphicon glyphicon-plus" ></span>
            Import Form
        </button>
        <input type="file" id="sfFileUpload" name="upload_file" style="display: none;" onchange="document.getElementById('sfFileUploadForm').submit();">
    </form>
</div>

<?php

}



if (isset($_GET['id'])) {
    $form_id=$_GET['id'];
}else
    $form_id="";



if($action!=null&&$form_id!=null)
{
    global $wpdb;

    if($action==="delete")
    {
        $wpdb->query($wpdb->prepare("delete from ".SMART_FORMS_TABLE_NAME." WHERE form_id=%d",$form_id));
        delete_transient("rednao_smart_forms_$form_id");
    }

    if($action==="edit"||$action=="clone")
    {
        $result=$wpdb->get_results($wpdb->prepare("SELECT * FROM ".SMART_FORMS_TABLE_NAME." WHERE form_id=%d",$form_id));

        if(count($result)>0)
        {
            $result=$result[0];

            $formOptions=$result->form_options;
            //$formOptions=str_replace("\\r","", str_replace("\\r","",$formOptions));
            $elementOptions=$result->element_options;
            //$elementOptions=str_replace("\\r","", str_replace("\\","\\\\",$elementOptions));

            $formClientOptions=$result->client_form_options;

            if($formClientOptions=="")
                $formClientOptions="{}";

            $formClientOptions=$formClientOptions;
           // $formClientOptions=str_replace("\\r","", str_replace("\\","\\\\",$formClientOptions));

            $script=<<<EOF
                        <script type="text/javascript" language="javascript">
                            var smartFormId="%s";
                            var smartFormsOptions=%s;
                            var smartFormsElementOptions=%s;
                            var smartFormClientOptions=%s
                        </script>
EOF;
            echo sprintf($script,($action=="clone"?"0":$result->form_id),$formOptions,$elementOptions,$formClientOptions);
            include(SMART_FORMS_DIR.'main_screens/smart-forms-add-new.php');
            return;

        }


    }
}


if(!class_exists('WP_LIST_TABLE'))
{
    require_once(ABSPATH.'wp-admin/includes/class-wp-list-table.php');
}


class RednaoForms extends WP_List_Table
{
    function get_columns()
    {
        return array(
          'form_name'=>__('Form Name'),
          'shortcode'=>__('Shortcode'),
        );
    }

    function prepare_items()
    {
        $this->_column_headers=array($this->get_columns(),array(),$this->get_sortable_columns());
        global $wpdb;
        $savedForms=$result=$wpdb->get_results("SELECT form_name,form_id,concat('[sform]',form_id,'[/sform]') shortcode FROM ".SMART_FORMS_TABLE_NAME);
        foreach($savedForms as $form)
        {
            $form->form_name=esc_html($form->form_name);
            $form->form_id=esc_html($form->form_id);
        }
        $this->items=$savedForms;
    }

    function get_sortable_columns()
    {

    }

    function column_default($item, $column_name)
    {
        return $item->$column_name;
    }

    function column_form_name($item) {
        $actions = array(
            __('edit')      => sprintf('<a href="?page=%s&id=%s&action=%s">Edit</a>',$_REQUEST['page'],$item->form_id,'edit'),
            __('delete')    => sprintf('<a href="javascript:(function(event){confirm(\'Are you sure you want to delete the form?\')?(window.location=\'?page=%s&id=%s&action=%s\'):\'\';event.returnValue=false; return false;})()">Delete</a>',$_REQUEST['page'],$item->form_id,'delete'),
            __('clone')    => sprintf('<a href="?page=%s&id=%s&action=%s">Clone</a>',$_REQUEST['page'],$item->form_id,'clone'),
            __('export')    => sprintf('<a target="_blank" href="'.admin_url('admin-ajax.php').'?action=rednao_smart_forms_export&formId=%s'.'">Export</a>',$item->form_id,'export')
        );

        return sprintf('%1$s %2$s', $item->form_name, $this->row_actions($actions) );
    }
}

$donationList=new RednaoForms();
$donationList->prepare_items();
$donationList->display();

?>




