<?php
function rednao_smart_forms_load_form($title,$form_id,$returnComponent)
{
    //$options=get_transient("rednao_smart_forms_$form_id");
    $options=false;
    if($options==false)
    {
        global $wpdb;
		/** @noinspection PhpUndefinedMethodInspection */
		$result=$wpdb->get_results($wpdb->prepare("select form_id,element_options,form_options,client_form_options from ".SMART_FORMS_TABLE_NAME." where form_id=%d",$form_id));
        if(count($result)>0)
        {
            $result=$result[0];
            $options=array( 'elements'=>$result->element_options,
                            'options'=>$result->form_options,
                            'form_id'=>$result->form_id,
                            'client_form_options'=>$result->client_form_options
            );

            set_transient("rednao_smart_forms_$form_id",$options);
        }

    }
    wp_enqueue_script('jquery');
    wp_enqueue_script('smart-forms-event-manager',SMART_FORMS_DIR_URL.'js/formBuilder/eventmanager.js',array('isolated-slider'));
    wp_enqueue_script('smart-forms-generator-interface',SMART_FORMS_DIR_URL.'js/subscriber_interfaces/ismart-forms-generator.js',array('isolated-slider'));
    wp_enqueue_script('isolated-slider',plugin_dir_url(__FILE__).'js/rednao-isolated-jq.js');
    wp_enqueue_script('smart-forms-generator',plugin_dir_url(__FILE__).'js/form-generator.js',array('isolated-slider','smart-forms-event-manager','smart-forms-generator-interface','smart-forms-form-elements'));
    wp_enqueue_script('smart-forms-form-elements',plugin_dir_url(__FILE__).'js/formBuilder/formelements.js',array('isolated-slider'));
    wp_enqueue_script('smart-forms-formula',SMART_FORMS_DIR_URL.'js/formBuilder/formula/formula.js',array('isolated-slider','smart-forms-event-manager'));
    wp_enqueue_script('smart-forms-formula-manager',SMART_FORMS_DIR_URL.'js/formBuilder/formula/formulamanager.js',array('isolated-slider','smart-forms-event-manager','smart-forms-formula'));
    wp_enqueue_script('smart-forms-elements-manipulators',SMART_FORMS_DIR_URL.'js/formBuilder/properties/manipulators.js',array('isolated-slider'));
	wp_enqueue_script('smart-forms-conditional-handlers',SMART_FORMS_DIR_URL.'js/conditional_manager/conditional-handlers.js',array('isolated-slider'));
	do_action('smart_forms_pr_add_form_elements_extensions');
	require_once(SMART_FORMS_DIR.'translations/form-elements-translation.php');
	require_once(SMART_FORMS_DIR.'smart-forms-bootstrap.php');

    wp_enqueue_style('smart-forms-Slider',SMART_FORMS_DIR_URL.'css/smartFormsSlider/jquery-ui-1.10.2.custom.min.css');
    wp_enqueue_style('smart-forms-custom-elements',plugin_dir_url(__FILE__).'css/formBuilder/custom.css');


    $random=rand();

	if(!defined ("SMART_DONATIONS_PLUGIN_URL"))
		define("SMART_DONATIONS_PLUGIN_URL","");

	if(!defined ("SMART_DONATIONS_SANDBOX"))
		define("SMART_DONATIONS_SANDBOX","");


    $userName=wp_get_current_user();
    $userName=$userName->user_login;
    if($userName==false)
        $userName='';
    if($returnComponent==false)
    {
        echo "<script type=\"text/javascript\">var ajaxurl = '".admin_url('admin-ajax.php')."';</script>";
		echo "<div class='widget'>";
        if($options==null)
            return null;

        if($title)
            echo "<div class='widget-wrapper'><h3 class='widgettitle widget-title'>$title</h3>"

        ?>

        <div id="formContainer<?php echo $random?>" class='rednaoFormContainer bootstrap-wrapper'></div>
        <script>
			var smartFormsCurrentTime=new Date(".date('D M d Y H:i:s O').");
            var smartFormsUserName="<?php echo $userName?>";
            var smartFormsPath="<?php echo plugin_dir_url(__FILE__)?>";
            var smartDonationsRootPath="<?php echo SMART_DONATIONS_PLUGIN_URL ?>";
            var smartDonationsSandbox="<?php echo SMART_DONATIONS_SANDBOX ?>";
			var smartFormsDesignMode=false;
            if(!window.smartFormsItemsToLoad)
                window.smartFormsItemsToLoad=[];

            window.smartFormsItemsToLoad.push({'form_id':<?php echo $options['form_id']?>, 'elements':<?php echo $options['elements']?>,'client_form_options':<?php echo $options['client_form_options']?>,'container':'formContainer<?php echo $random?>'});

        </script>
        <?php
        if($title)
            echo "</div>";

		echo "</div>";

    }else{
        if($options==null)
            return "";
        return "<script type='text/javascript'>var ajaxurl = '".admin_url('admin-ajax.php')."';</script><div id='formContainer$random' class='rednaoFormContainer bootstrap-wrapper'></div>
            <script>
            	var smartFormsCurrentTime=new Date('".date('D M d Y H:i:s O')."');
            	var smartFormsUserName=\"".$userName."\";
                var smartFormsPath=\"".plugin_dir_url(__FILE__)."\";
                var smartDonationsRootPath=\"".SMART_DONATIONS_PLUGIN_URL."\";
                var smartDonationsSandbox=\"".SMART_DONATIONS_SANDBOX."\";
                var smartFormsDesignMode=false;
                if(!window.smartFormsItemsToLoad)
                    window.smartFormsItemsToLoad=new Array();
                window.smartFormsItemsToLoad.push({ 'form_id':".$options['form_id'].",  'elements':".$options['elements'].",'client_form_options':".$options['client_form_options'].",'container':'formContainer$random'});
            </script>
           ";
    }

	return null;
}