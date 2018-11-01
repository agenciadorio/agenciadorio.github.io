<?php	


/*
* @Author 		Jaed Mosharraf
* Copyright: 	2015 Jaed Mosharraf
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 



class class_wpp_settings_page  {
	
	
    public function __construct(){

		add_action( 'admin_menu', array( $this, 'admin_menu' ), 12 );
    }
	
	
	public function wpp_settings_options($options = array()){
		
		$options['Options'] = array(
					
			// 'wpp_redirection_url'=>array(
				// 'css_class'=>'wpp_redirection_url',					
				// 'title'=>__('Redirect after poll submission',WPP_TEXT_DOMAIN),
				// 'option_details'=>__('Enter the URL on where you want to redirect user after submitting a Poll.<br>Leave empty for no redirection.',WPP_TEXT_DOMAIN),						
				// 'input_type'=>'text', 
				// 'placeholder'=>'https://yoursite.com/thankyou',
			// ),
			
			// 'wpp_enable_capcha'=>array(
				// 'css_class'=>'wpp_enable_capcha',					
				// 'title'=>__('Enable Capcha validation',WPP_TEXT_DOMAIN),
				// 'option_details'=>__('Do you want to add capcha validation to restrict spam on Poll submission?<br><b>Default: No</b>',WPP_TEXT_DOMAIN),						
				// 'input_type'=>'select', 
				// 'input_args'=> array('no'=>__('No', WPP_TEXT_DOMAIN),'yes'=>__('Yes', WPP_TEXT_DOMAIN)),
			// ),
			
			'wpp_allow_comments'=>array(
				'css_class'=>'wpp_allow_comments',					
				'title'=>__('Allow Comments',WPP_TEXT_DOMAIN),
				'option_details'=>__('Do you allow comments on single Poll page?<br><b>Default: No</b>',WPP_TEXT_DOMAIN),						
				'input_type'=>'select', 
				'input_args'=> array('no'=>__('No', WPP_TEXT_DOMAIN),'yes'=>__('Yes', WPP_TEXT_DOMAIN)),
			),
			
			
		);
		
		
		$options['Button Text'] = array(
					
			'wpp_btn_text_new_option'=>array(
				'css_class'=>'wpp_btn_text_new_option',					
				'title'=>__('New Option Button Text',WPP_TEXT_DOMAIN),
				'option_details'=>'',						
				'input_type'=>'text', 
				'placeholder'=>__('New Option',WPP_TEXT_DOMAIN),
			),
			
			'wpp_btn_text_submit'=>array(
				'css_class'=>'wpp_btn_text_submit',					
				'title'=>__('Submit Button Text',WPP_TEXT_DOMAIN),
				'option_details'=>'',						
				'input_type'=>'text', 
				'placeholder'=>__('Submit',WPP_TEXT_DOMAIN),
			),
			
			'wpp_btn_text_results'=>array(
				'css_class'=>'wpp_btn_text_results',					
				'title'=>__('Results Button Text',WPP_TEXT_DOMAIN),
				'option_details'=>'',						
				'input_type'=>'text', 
				'placeholder'=>__('Results',WPP_TEXT_DOMAIN),
			),
			
			
		);
		
		$options['Poll List Options'] = array(

			'wpp_poll_page'=>array(
				'css_class'=>'wpp_poll_page',					
				'title'=>__('Select a Poll Page',WPP_TEXT_DOMAIN),
				'option_details'=>'',						
				'input_type'=>'select', // text, radio, checkbox, select, 
				'input_values'=>'', // could be array
				'input_args'=> fn_get_array_pages(),
			),
			
			'wpp_poll_page_content_show'=>array(
				'css_class'=>'wpp_poll_page_content_show',					
				'title'=>__('Do you want to show page Content?',WPP_TEXT_DOMAIN),
				'option_details'=>'',						
				'input_type'=>'radio',
				'input_values'=>'yes', 
				'input_args'=> array('yes'=>'Yes', 'no'=>'No'),
			),
			
			'wpp_list_per_page'=>array(
				'css_class'=>'wpp_list_per_page',					
				'title'=>__('List Per Page',WPP_TEXT_DOMAIN),
				'input_type'=>'text',
				'input_values'=>'', 
				'placeholder'=>'10',
			),
			
			'wpp_list_empty_text'=>array(
				'css_class'=>'wpp_list_empty_text',					
				'title'=>__('No Poll Found Text',WPP_TEXT_DOMAIN),
				'input_type'=>'text',
				'placeholder'=>__('No Poll Found',WPP_TEXT_DOMAIN),
				
			),
		);
		
		$options['Color'] = array(

			'wpp_color_new_option'=>array(
				'css_class'=>'wpp_color_new_option',					
				'title'=>__('New Button Color',WPP_TEXT_DOMAIN),
				'option_details'=>'',						
				'input_type'=>'text',
				'input_values'=> '#6C2EB9',
			),
			'wpp_color_submit'=>array(
				'css_class'=>'wpp_color_submit',					
				'title'=>__('Submit Button Color',WPP_TEXT_DOMAIN),
				'option_details'=>'',						
				'input_type'=>'text',
				'input_values'=> '#787878',
			),
			'wpp_color_results'=>array(
				'css_class'=>'wpp_color_results',					
				'title'=>__('Results Button Color',WPP_TEXT_DOMAIN),
				'option_details'=>'',						
				'input_type'=>'text',
				'input_values'=> '#009D91',
			),		
			'wpp_color_title'=>array(
				'css_class'=>'wpp_color_title',					
				'title'=>__('Title Text Color',WPP_TEXT_DOMAIN),
				'option_details'=>'',						
				'input_type'=>'text',
				'input_values'=> '#2D2D2D',
			),		
			'wpp_color_options'=>array(
				'css_class'=>'wpp_color_options',					
				'title'=>__('Options Text Color',WPP_TEXT_DOMAIN),
				'option_details'=>'',						
				'input_type'=>'text',
				'input_values'=> '#2D2D2D',
			),		
			'wpp_color_notice_text_success'=>array(
				'css_class'=>'wpp_color_notice_text_success',					
				'title'=>__('Notice Text Color - Success',WPP_TEXT_DOMAIN),
				'option_details'=>'',						
				'input_type'=>'text',
				'input_values'=> '#fff',
			),		
			'wpp_color_notice_background_success'=>array(
				'css_class'=>'wpp_color_notice_background_success',					
				'title'=>__('Notice Bankground Color - Success',WPP_TEXT_DOMAIN),
				'option_details'=>'',						
				'input_type'=>'text',
				'input_values'=> '#17A15E',
			),
			'wpp_color_notice_text_error'=>array(
				'css_class'=>'wpp_color_notice_text_error',					
				'title'=>__('Notice Text Color - Error',WPP_TEXT_DOMAIN),
				'option_details'=>'',						
				'input_type'=>'text',
				'input_values'=> '#fff',
			),		
			'wpp_color_notice_background_error'=>array(
				'css_class'=>'wpp_color_notice_background_error',					
				'title'=>__('Notice Bankground Color - Error',WPP_TEXT_DOMAIN),
				'option_details'=>'',						
				'input_type'=>'text',
				'input_values'=> '#DE746C',
			),
			'wpp_color_message_text_normal'=>array(
				'css_class'=>'wpp_color_message_text_normal',					
				'title'=>__('Message Text Color - Normal',WPP_TEXT_DOMAIN),
				'option_details'=>'',						
				'input_type'=>'text',
				'input_values'=> '#757575',
			),		
			'wpp_color_message_background_normal'=>array(
				'css_class'=>'wpp_color_message_background_normal',					
				'title'=>__('Message Bankground Color - Normal',WPP_TEXT_DOMAIN),
				'option_details'=>'',						
				'input_type'=>'text',
				'input_values'=> '#EEEEEE',
			),
			'wpp_color_message_text_error'=>array(
				'css_class'=>'wpp_color_message_text_error',					
				'title'=>__('Message Text Color - Error',WPP_TEXT_DOMAIN),
				'option_details'=>'',						
				'input_type'=>'text',
				'input_values'=> '#fff',
			),		
			'wpp_color_message_background_error'=>array(
				'css_class'=>'wpp_color_message_background_error',					
				'title'=>__('Message Bankground Color - Error',WPP_TEXT_DOMAIN),
				'option_details'=>'',						
				'input_type'=>'text',
				'input_values'=> '#DE746C',
			),
		);
		
		$options = apply_filters( 'wpp_filter_settings_options', $options );
		return $options;
	}
	
	
	public function wpp_settings_options_form(){
		global $post;
			
		$wpp_settings_options = $this->wpp_settings_options();
		$html = '';
		$html.= '<div class="back-settings post-grid-settings">';			
		$html_nav = '';
		$html_box = '';
		
		$i=1;
		foreach($wpp_settings_options as $key=>$options) {
			
			if( $i == 1 ) $html_nav.= '<li nav="'.$i.'" class="nav'.$i.' active">'.$key.'</li>';				
			else $html_nav.= '<li nav="'.$i.'" class="nav'.$i.'">'.$key.'</li>';
							
			if( $i == 1 ) $html_box.= '<li style="display: block;" class="box'.$i.' tab-box active">';				
			else $html_box.= '<li style="display: none;" class="box'.$i.' tab-box">';
				
			foreach($options as $option_key=>$option_info)
			{
				$option_value =  get_option( $option_key );				
				
				if(!isset($option_info['placeholder'])) $placeholder = '';
				else $placeholder = $option_info['placeholder'];
				
				if(!isset($option_info['input_values'])) $option_info['input_values'] = '';
				if(!isset($option_info['status'])) $option_info['status'] = '';
				if(!isset($option_info['option_details'])) $option_info['option_details'] = '';
				
				if(empty($option_value)) $option_value = $option_info['input_values'];
				
				$html_box.= '<div class="section-box '.$option_info['css_class'].'">';
				$html_box.= '<p class="section-title">'.$option_info['title'].'</p>';
				$html_box.= '<p class="section-info">'.$option_info['option_details'].'</p>';
				
				if($option_info['input_type'] == 'text') 
					$html_box.= '<input type="text" '.$option_info['status'].' placeholder="'.$placeholder.'" name="'.$option_key.'" id="'.$option_key.'" value="'.$option_value.'" /> ';					
				elseif($option_info['input_type'] == 'text-multi') {
					$input_args = $option_info['input_args'];
					foreach($input_args as $input_args_key=>$input_args_values) {
						if(empty($option_value[$input_args_key])) $option_value[$input_args_key] = $input_args[$input_args_key];
						$html_box.= '<label>'.$input_args_key.'<br/><input class="job-bm-color" type="text" placeholder="" name="'.$option_key.'['.$input_args_key.']" value="'.$option_value[$input_args_key].'" /></label><br/>';	
					}
				}					
				elseif($option_info['input_type'] == 'textarea') $html_box.= '<textarea placeholder="" name="'.$option_key.'" >'.$option_value.'</textarea> ';
				elseif($option_info['input_type'] == 'radio') {
					$input_args = $option_info['input_args'];
					foreach($input_args as $input_args_key=>$input_args_values)
					{
						if($input_args_key == $option_value) $checked = 'checked';
						else $checked = '';
						$html_box.= '<label><input class="'.$option_key.'" type="radio" '.$checked.' value="'.$input_args_key.'" name="'.$option_key.'"   >'.$input_args_values.'</label><br/>';
					}
				}
				elseif($option_info['input_type'] == 'select') {
					$input_args = $option_info['input_args'];
					$html_box.= '<select name="'.$option_key.'" >';
					foreach($input_args as $input_args_key=>$input_args_values)
					{
						if($input_args_key == $option_value) $selected = 'selected';
						else $selected = '';
						$html_box.= '<option '.$selected.' value="'.$input_args_key.'">'.$input_args_values.'</option>';
					}
					$html_box.= '</select>';
				}					
				elseif($option_info['input_type'] == 'checkbox') {
					$input_args = $option_info['input_args'];
					foreach($input_args as $input_args_key=>$input_args_values)
					{
						if(empty($option_value[$input_args_key])) $checked = '';
						else $checked = 'checked';
						$html_box.= '<label><input '.$checked.' value="'.$input_args_key.'" name="'.$option_key.'['.$input_args_key.']"  type="checkbox" >'.$input_args_values.'</label><br/>';
					}
				}
				elseif($option_info['input_type'] == 'file') {
					$html_box.= '<input type="text" id="file_'.$option_key.'" name="'.$option_key.'" value="'.$option_value.'" /><br />';
					$html_box.= '<input id="upload_button_'.$option_key.'" class="upload_button_'.$option_key.' button" type="button" value="Upload File" />';					
					$html_box.= '<br /><br /><div style="overflow:hidden;max-height:150px;max-width:150px;" class="logo-preview"><img width="100%" src="'.$option_value.'" /></div>';
					$html_box.= '
					<script>
						jQuery(document).ready(function($){
							var custom_uploader; 
							jQuery("#upload_button_'.$option_key.'").click(function(e) {
							e.preventDefault();
							if (custom_uploader) {
								custom_uploader.open();
								return;
							}
							custom_uploader = wp.media.frames.file_frame = wp.media({
								title: "Choose File",
								button: {
									text: "Choose File"
								},
								multiple: false
							});
							custom_uploader.on("select", function() {
								attachment = custom_uploader.state().get("selection").first().toJSON();
								jQuery("#file_'.$option_key.'").val(attachment.url);
								jQuery(".logo-preview img").attr("src",attachment.url);											
							});
							custom_uploader.open();
						});
					})
					</script>';					
				}		
				$html_box.= '</div>';
			}
			$html_box.= '</li>';
			$i++;
		}
		
		
		$WPP_Functions = new WPP_Functions();
		$template_sections = $WPP_Functions->wpp_poll_template_sections();
		
		$wpp_poll_template = get_option( 'wpp_poll_template' );
		if( empty( $wpp_poll_template ) ) $wpp_poll_template = array();
		
		$wpp_use_customized_template = get_option( 'wpp_use_customized_template' );
		if( empty( $wpp_use_customized_template ) ) $wpp_use_customized_template = 'no';
		
		
		// echo '<pre>'; print_r( $wpp_use_customized_template ); echo '</pre>';
		
		$input_args = array( 'no'=>__('No', WPP_TEXT_DOMAIN),'yes'=>__('Yes', WPP_TEXT_DOMAIN) );
		
		$html_nav.= '<li nav="'.$i.'" class="nav'.$i.'">'.__('Poll Template', WPP_TEXT_DOMAIN).'</li>';
		$html_box.= '<li style="display: none;" class="box'.$i.' tab-box">';
		
		$html_box.= '<div class="section-box">';
		$html_box.= '<p class="section-title">'.__('Use Customized Template?', WPP_TEXT_DOMAIN).'</p>';
		$html_box.= '<p class="section-info">'.__('Do you want to use customized template to display Single Poll?', WPP_TEXT_DOMAIN).'</p>';
		
		$html_box.= "<select name='wpp_use_customized_template'>";
		foreach($input_args as $input_args_key=>$input_args_values) {
			$selected = $input_args_key == $wpp_use_customized_template ? 'selected' : '';
			$html_box.= '<option '.$selected.' value="'.$input_args_key.'">'.$input_args_values.'</option>';
		}
		$html_box.= '</select>';
		$html_box.= '</div>';
		
		$html_box.= '<div class="section-box">';
		$html_box.= '<p class="section-title">'.__('Single Poll Template', WPP_TEXT_DOMAIN).'</p>';
		$html_box.= '<p class="section-info">'.
			__('Design your custom template how you want to display a Single Poll', WPP_TEXT_DOMAIN).
			'<br>'.
			__('Select your choices from left side and sort them from right side.', WPP_TEXT_DOMAIN).
			'<b>'.__('Use the Recommended section to make it nice.', WPP_TEXT_DOMAIN).'</b></p>';
		
		$html_box.= "<ul class='wpp_td'>";
		foreach( $template_sections as $section_key => $section ){
			
			$label = isset( $section['label'] ) ? $section['label'] : '';
			$priority = isset( $section['priority'] ) ? $section['priority'] : 0;
			
			if( $priority >= 50 ) $priority_text = ' - '.__('Recommended', WPP_TEXT_DOMAIN);
			else $priority_text = '';
			
			$html_box.= "
			<li class='wpp_td_single'>
				<span class='wpp_td_label'>$label</span>
				<span class='wpp_td_priority'>$priority_text</span>
				<div class='wpp_td_icon wpp_td_add_section' section_key='$section_key'><i class='fa fa-location-arrow'></i></div>
			</li>";
		}	
		$html_box.= '</ul>';
		
		
		$html_box.= "<ul class='wpp_td wpp_td_templates'>";
		foreach( $wpp_poll_template as $section_key ){
			
			$label = isset( $template_sections[$section_key]['label'] ) ? $template_sections[$section_key]['label'] : '';
			
			$html_box.= "
			<li class='wpp_td_single'>
				<span class='wpp_td_label'>$label</span>
				<div class='wpp_td_icon wpp_td_single_remove' step=f><i class='fa fa-times'></i></div>
				<div class='wpp_td_icon wpp_td_single_sorter'><i class='fa fa-sort'></i></div>
				<input type='hidden' name='wpp_poll_template[]' value='$section_key' />
			</li>";
		}
		$html_box.= '</ul>';
		
		
		$html_box.= '</div>';
		$html_box.= '</li>';
				
				
				
		$html.= '<ul class="tab-nav">';
		$html.= $html_nav;			
		$html.= '</ul>';
		$html.= '<ul class="box">';
		$html.= $html_box;
		$html.= '</ul>';		
		$html.= '</div>';			
		return $html;
	}
} new class_wpp_settings_page();

	if(empty($_POST['wpp_hidden'])):
		$class_wpp_settings_page = new class_wpp_settings_page();
		$wpp_settings_options = $class_wpp_settings_page->wpp_settings_options();
		foreach($wpp_settings_options as $options_tab=>$options)
		{
			foreach($options as $option_key=>$option_data) 
				${$option_key} = get_option( $option_key );
		}
	else:
		if($_POST['wpp_hidden'] == 'Y'):
			$class_wpp_settings_page = new class_wpp_settings_page();
			$wpp_settings_options = $class_wpp_settings_page->wpp_settings_options();
			foreach($wpp_settings_options as $options_tab=>$options)
			{
				foreach($options as $option_key=>$option_data)
				{
					if(!isset($_POST[$option_key])) $_POST[$option_key] = '';
					
					${$option_key} = stripslashes_deep($_POST[$option_key]);
					update_option($option_key, ${$option_key});
				}
			}
			
			$wpp_poll_template = stripslashes_deep( $_POST['wpp_poll_template'] );
			update_option( 'wpp_poll_template', $wpp_poll_template );
			
			$wpp_use_customized_template = sanitize_text_field( $_POST['wpp_use_customized_template'] );
			update_option( 'wpp_use_customized_template', $wpp_use_customized_template );
			
			?>
			<div class="updated"><p><strong><?php _e('Changes Saved.', WPP_TEXT_DOMAIN ); ?></strong></p></div>
			<?php
		endif;
	endif;
?>





	<div class="wrap">
		<div id="icon-tools" class="icon32"><br></div>
		<?php echo "<h2>WP Poll - ".__('Settings', WPP_TEXT_DOMAIN)."</h2>";?><br>
		
		<form  method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
			<input type="hidden" name="wpp_hidden" value="Y" />
			<?php 
				settings_fields( 'wpp_plugin_options' );
				do_settings_sections( 'wpp_plugin_options' );
					
				$class_wpp_settings_page = new class_wpp_settings_page();
				echo $class_wpp_settings_page->wpp_settings_options_form(); 
			?>
			<br>
			<input class="button button-primary" type="submit" name="Submit" value="<?php _e('Save Changes',WPP_TEXT_DOMAIN ); ?>" />
			<!--<div class="button button-primary" id="wpp_reset_settings" > <?php //_e('Reset Settings',WPP_TEXT_DOMAIN ); ?> </div> -->
		</form>
		
	</div>

<?php
	function fn_get_array_pages(){
		$pages = get_pages( );
		$array_pages = array();
		$array_pages[''] = 'None';
		
		foreach($pages as $page) {
			if ( $page->post_title )
				$array_pages[$page->ID] = $page->post_title;
		}
		
		return $array_pages;
	}
	
	
	
?>
				
				
				