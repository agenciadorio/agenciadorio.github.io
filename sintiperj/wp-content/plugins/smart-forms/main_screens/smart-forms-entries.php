<?php
/**
 * Created by JetBrains PhpStorm.
 * User: edseventeen
 * Date: 6/13/13
 * Time: 8:04 PM
 * To change this template use File | Settings | File Templates.
 */

if(!defined('ABSPATH'))
    die('Forbidden');



require_once(SMART_FORMS_DIR.'smart-forms-bootstrap.php');
wp_enqueue_script('isolated-slider',SMART_FORMS_DIR_URL.'js/rednao-isolated-jq.js');
wp_enqueue_script('exCanvas',SMART_FORMS_DIR_URL.'js/grid_chart/excanvas.min.js',array('isolated-slider'));
wp_enqueue_script('jqPlot',SMART_FORMS_DIR_URL.'js/grid_chart/jquery.jqplot.min.js',array('exCanvas'));
wp_enqueue_script('jqHighlighter',SMART_FORMS_DIR_URL.'js/grid_chart/jqplot.highlighter.js',array('jqPlot'));
wp_enqueue_script('jqCursor',SMART_FORMS_DIR_URL.'js/grid_chart/jqplot.cursor.min.js',array('jqHighlighter'));
wp_enqueue_script('jqDateAxis',SMART_FORMS_DIR_URL.'js/grid_chart/jqplot.dateAxisRenderer.min.js',array('jqHighlighter'));
wp_enqueue_script('jqCanvasAxis',SMART_FORMS_DIR_URL.'js/grid_chart/jqplot.canvasAxisTickRenderer.min.js',array('jqCursor'));
wp_enqueue_script('jqPointLabels',SMART_FORMS_DIR_URL.'js/grid_chart/jqplot.pointLabels.min.js',array('jqCanvasAxis'));
wp_enqueue_script('smart-forms-entries',SMART_FORMS_DIR_URL.'js/main_screens/smart-forms-entries.js',array('jqPointLabels'));
wp_enqueue_script('smart-forms-form-elements',SMART_FORMS_DIR_URL.'js/formBuilder/formelements.js',array('isolated-slider'));
wp_enqueue_script('smart-forms-event-manager',SMART_FORMS_DIR_URL.'js/formBuilder/eventmanager.js',array('isolated-slider'));
do_action('smart_formsa_include_systemjs');
do_action('smart_forms_include_form_elemeents_scripts');

wp_enqueue_script('jqGridlocale',SMART_FORMS_DIR_URL.'js/grid_chart/grid.locale-en.js',array('isolated-slider'));
wp_enqueue_script('jqGrid',SMART_FORMS_DIR_URL.'js/grid_chart/jquery.jqGrid.min.js',array('jqGridlocale'));


wp_enqueue_style('form-builder-custom',SMART_FORMS_DIR_URL.'css/formBuilder/custom.css');
wp_enqueue_style('jqgrid',SMART_FORMS_DIR_URL.'css/grid_chart/ui.jqgrid.css');
wp_enqueue_style('jqplot',SMART_FORMS_DIR_URL.'css/grid_chart/jquery.jqplot.css');
wp_enqueue_style('smart-donations-Slider',SMART_FORMS_DIR_URL.'css/smartFormsSlider/jquery-ui-1.10.2.custom.min.css');
wp_enqueue_script('json2');
include_once(SMART_FORMS_DIR.'smart-forms-license.php');
smart_forms_load_license_manager("");




?>

<script type="text/javascript">
    var smartFormsPath="<?php echo SMART_FORMS_DIR_URL?>";
</script>

<style  type="text/css">
    .ui-jqgrid tr.jqgrow td {
        white-space: normal !important;
    }

    .editButton,.deleteButton{
        cursor:hand;
        cursor:pointer;
        padding:2px;
    }

    .bootstrap-wrapper .modal-body{
        float:left;
    }

    .editButton:hover,.deleteButton:hover{
        color:red;
    }

    .bootstrap-wrapper .form-control {
        color:black !important;
    }



    .bootstrap-wrapper .modal-content{
        overflow:auto;
        overflow-x:hidden;
    }
</style>

<h1 >Analytics</h1>

<hr/>


<div id="smartDonationRadio" class="smartFormsSlider" style="margin-bottom: 20px;">
    <strong >Start Date</strong>
    <input type="text" class="datePicker smartFormsSlider" id="dpStartDate"/>
    <strong style="margin-left: 15px">End Date</strong>
    <input type="text" class="datePicker smartFormsSlider" id="dpEndDate"/>
    <strong style="margin-left: 15px" >Form</strong>
    <select id="cbForm">
        <?php
        global $wpdb;
        $results=$wpdb->get_results("select form_id,form_name from ".SMART_FORMS_TABLE_NAME);

        foreach($results as $result)
        {
            echo "<option value='$result->form_id' >$result->form_name</option>";
        }

        ?>
    </select>

    <script type="text/javascript" language="javascript">
        var smartFormsRootPath="<?php echo SMART_FORMS_DIR_URL?>";
        var RedNaoCampaignList="";
        <?php
            echo "RedNaoCampaignList='";
            foreach($results as $result)
            {

                echo ";$result->form_id:$result->form_name";
            }
            echo "'";
        ?>
    </script>




    <Button style="margin-left:35px" id="btnExecute">
        Execute
    </Button>

</div>
<div style="width:80%;overflow-x: scroll;padding:25px;display: none">
    <div id="Chart"></div>
</div>

<div id="editDialog"></div>

<div>
    <div class="smartFormsSlider" style="margin-right:10px;">
        <table id='grid' class="ui-jqdialogasdf" style="width:100%;height:100%;"></table><div id='pager'></div>
    </div>

    <form method="post" action="<?php echo SMART_FORMS_DIR_URL?>smart-forms-exporter.php" id="exporterForm" target="_blank">
        <input type="hidden" id="smartFormsExportData" name="exportdata"/>
    </form>