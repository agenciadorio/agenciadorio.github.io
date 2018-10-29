<?php
if (!function_exists ('add_action')) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();
}
class ThemeMove_Import {

	public $message = "";
	public $attachments = false;

    public $theme;


	function __construct() {
		add_action('admin_menu', array(&$this, 'thememove_admin_import'));
		add_action('admin_init', array(&$this, 'register_thememove_theme_settings'));

        $this->theme = apply_filters( 'thememove_import_theme', 'ThemeMove' );
	}
	function register_thememove_theme_settings() {
		register_setting( 'thememove_options_import_page', 'thememove_options_import');
	}

	public function import_content($file){
		if (!class_exists('WP_Importer')) {
			ob_start();
            require_once('class.wordpress-importer.php');
			$thememove_import = new WP_Import();
			set_time_limit(0);
			$path = get_template_directory() . '/inc/import/files/' . $file;

			$thememove_import->fetch_attachments = $this->attachments;
			$returned_value = $thememove_import->import($path);

            echo $returned_value;
            die();

			if(is_wp_error($returned_value)){
				$this->message = __("An Error Occurred During Import", "thememove");
			}
			else {
				$this->message = __("Content imported successfully", "thememove");
			}
			ob_get_clean();
		} else {
			$this->message = __("Error loading files", "thememove");
		}
	}

	public function import_widgets($file){
		$options = $this->file_options($file);
		foreach ((array) $options['widgets'] as $thememove_widget_id => $thememove_widget_data) {
			update_option( 'widget_' . $thememove_widget_id, $thememove_widget_data );
		}
		$this->import_sidebars_widgets($file);
		$this->message = __("Widgets imported successfully", "thememove");
	}

	public function import_widget_logic($file) {
		$file_path = $this->getImportDataFolderPath() . '/' . $file;

		if (file_exists($file_path)) {
			global $wl_options;

			$import = split("\n", file_get_contents($file_path, false));

			if (trim(array_shift($import))=="[START=WIDGET LOGIC OPTIONS]" && trim(array_pop($import))=="[STOP=WIDGET LOGIC OPTIONS]")
			{	foreach ($import as $import_option)
				{	list($key, $value)=split("\t",$import_option);
					$wl_options[$key]=json_decode($value);
				}
			}

			update_option('widget_logic', $wl_options);
		}
	}

	public function import_sidebars_widgets($file){
		$thememove_sidebars = get_option("sidebars_widgets");
		unset($thememove_sidebars['array_version']);
		$data = $this->file_options($file);
		if ( is_array($data['sidebars']) ) {
			$thememove_sidebars = array_merge( (array) $thememove_sidebars, (array) $data['sidebars'] );
			unset($thememove_sidebars['wp_inactive_widgets']);
			$thememove_sidebars = array_merge(array('wp_inactive_widgets' => array()), $thememove_sidebars);
			$thememove_sidebars['array_version'] = 2;
			wp_set_sidebars_widgets($thememove_sidebars);
		}
	}

	public function import_customizer_options($file){
		$options = $this->file_options($file);

		if (is_array($options)) {
			foreach ($options as $key => $val) {
				set_theme_mod( $key, $val );
			}
		}

		$this->message = __("Customizer options imported successfully", "thememove");
	}

	public function import_menus($file){
		global $wpdb;
		$thememove_terms_table = $wpdb->prefix . "terms";
		$this->menus_data = $this->file_options($file);
		$menu_array = array();
		foreach ($this->menus_data as $registered_menu => $menu_slug) {
			$term_rows = $wpdb->get_results("SELECT * FROM $thememove_terms_table where slug='{$menu_slug}'", ARRAY_A);
			if(isset($term_rows[0]['term_id'])) {
				$term_id_by_slug = $term_rows[0]['term_id'];
			} else {
				$term_id_by_slug = null;
			}
			$menu_array[$registered_menu] = $term_id_by_slug;
		}
		set_theme_mod('nav_menu_locations', array_map('absint', $menu_array ) );

	}
	public function import_page_options($file){
		$pages = $this->file_options($file);

		foreach($pages as $thememove_page_option => $thememove_page_id){
			update_option( $thememove_page_option, $thememove_page_id);
		}
	}

	public function import_essential_grid($file) {
		require_once(ABSPATH .'wp-content/plugins/essential-grid/essential-grid.php');

		$es_data = $this->file_json($file);
		
		try{
			$im = new Essential_Grid_Import();

			$overwriteData = array(
				'global-styles-overwrite' => 'overwrite'
			);

			// Create Overwrite & Ids data
			$skins = @$es_data['skins'];
			$export_skins = array();
			if(!empty($skins) && is_array($skins)){
				foreach ($skins as $skin) {
					$export_skins[] = $skin['id'];
					$overwriteData['skin-overwrite-' . $skin['id']] = 'overwrite';
				}
			}

			$export_navigation_skins = array();
			$navigation_skins = @$es_data['navigation-skins'];

			foreach ((array)$navigation_skins as $nav_skin) {
				$export_navigation_skins[] = $nav_skin['id'];
				$overwriteData['nav-skin-overwrite-' . $nav_skin['id']] = 'overwrite';
			}

			$export_grids = array();
			$grids = @$es_data['grids'];
			if(!empty($grids) && is_array($grids)){
				foreach ($grids as $grid) {
					$export_grids[] = $grid['id'];
					$overwriteData['grid-overwrite-' . $grid['id']] = 'overwrite';
				}
			}

			$export_elements = array();
			$elements = @$es_data['elements'];
			if (!empty($elements) && is_array($elements))
			{foreach ($elements as $element) {
				$export_elements[] = $element['id'];
				$overwriteData['elements-overwrite-' . $element['id']] = 'overwrite';
			}}

			$export_custom_meta = array();
			$custom_metas = @$es_data['custom-meta'];
			if(!empty($custom_metas) && is_array($custom_metas)){
				foreach ($custom_metas as $custom_meta) {
					$export_custom_meta[] = $custom_meta['handle'];
					$overwriteData['custom-meta-overwrite-' .  $custom_meta['handle']] = 'overwrite';
				}
			}

			$export_punch_fonts = array();
			$custom_fonts = @$es_data['punch-fonts'];
			if(!empty($custom_fonts) && is_array($custom_fonts)){
				foreach ($custom_fonts as $custom_font) {
					$export_punch_fonts[] = $custom_font['handle'];
					$overwriteData['punch-fonts-overwrite-' . $custom_font['handle']] = 'overwrite';
				}
			}

			$im->set_overwrite_data($overwriteData); //set overwrite data global to class

			// Import data
			$skins = @$es_data['skins'];
			if(!empty($skins) && is_array($skins)){
				if(!empty($skins)){
					$skins_imported = $im->import_skins($skins, $export_skins);
				}
			}

			$navigation_skins = @$es_data['navigation-skins'];
			if(!empty($navigation_skins) && is_array($navigation_skins)){
				if(!empty($navigation_skins)){
					$navigation_skins_imported = $im->import_navigation_skins(@$navigation_skins, $export_navigation_skins);
				}
			}

			$grids = @$es_data['grids'];
			if(!empty($grids) && is_array($grids)){
				if(!empty($grids)){
					$grids_imported = $im->import_grids($grids, $export_grids);
				}
			}

			$elements = @$es_data['elements'];
			if(!empty($elements) && is_array($elements)){
				if(!empty($elements)){
					$elements_imported = $im->import_elements(@$elements, $export_elements);
				}
			}

			$custom_metas = @$es_data['custom-meta'];
			if(!empty($custom_metas) && is_array($custom_metas)){
				if(!empty($custom_metas)){
					$custom_metas_imported = $im->import_custom_meta($custom_metas, $export_custom_meta);
				}
			}

			$custom_fonts = @$es_data['punch-fonts'];
			if(!empty($custom_fonts) && is_array($custom_fonts)){
				if(!empty($custom_fonts)){
					$custom_fonts_imported = $im->import_punch_fonts($custom_fonts, $export_punch_fonts);
				}
			}

			if(true){
				$global_css = @$es_data['global-css'];

				$tglobal_css = stripslashes($global_css);
				if(empty($tglobal_css)) {$tglobal_css = $global_css;}

				$global_styles_imported = $im->import_global_styles($tglobal_css);
			}
		}catch(Exception $d){
		}
	}

	public function import_rev_slider($demo) {
		if ( ! class_exists( 'RevSliderAdmin' ) ) {
			require( RS_PLUGIN_PATH . '/admin/revslider-admin.class.php' );			
		}


		$rev_files = glob($this->getImportDataFolderPath() . $demo . '/rev_sliders/*.zip');

		if (!empty($rev_files)) {
			foreach ($rev_files as $rev_file) {
				$_FILES['import_file']['error'] = UPLOAD_ERR_OK;
				$_FILES['import_file']['tmp_name']= $rev_file;

				$slider = new RevSlider();
				$slider->importSliderFromPost( true, 'none' );
			}
		}
	}

	public function import_vc_templates($file){
		if (!class_exists('WP_Importer')) {
			ob_start();
            require_once('class.wordpress-importer.php');
			$thememove_import = new WP_Import();
			set_time_limit(0);
			$path = get_template_directory() . '/inc/import/files/' . $file;

			$thememove_import->fetch_attachments = $this->attachments;
			$returned_value = $thememove_import->import($path);

            echo $returned_value;
            die();

			if(is_wp_error($returned_value)){
				$this->message = __("An Error Occurred During Import", "thememove");
			}
			else {
				$this->message = __("Content imported successfully", "thememove");
			}
			ob_get_clean();
		} else {
			$this->message = __("Error loading files", "thememove");
		}
	}

	public function getImportDataFolderPath() {
		return get_template_directory() . '/inc/import/files/';
	}

	public function file_options($file){
		$file_content = "";
		$file_for_import = $this->getImportDataFolderPath() . $file;
		if ( file_exists($file_for_import) ) {
			$file_content = $this->thememove_file_contents($file_for_import);
		} else {
			$this->message = __("File doesn't exist", "thememove");
		}
		if ($file_content) {
			$unserialized_content = unserialize(base64_decode($file_content));
			if ($unserialized_content) {
				return $unserialized_content;
			}
		}
		return false;
	}

	public function file_json($file) {
		$file_content = "";
		$file_for_import = $this->getImportDataFolderPath() . $file;
		if ( file_exists($file_for_import) ) {
			$file_content = $this->thememove_file_contents($file_for_import);
		} else {
			$this->message = __("File doesn't exist", "thememove");
		}

		if ($file_content) {
			return json_decode($file_content, true);
		}

		return false;
	}

	function thememove_file_contents( $path ) {
		$thememove_content = '';
		if ( function_exists('realpath') )
			{$filepath = realpath($path);}
		if ( !$filepath || !@is_file($filepath) )
			{return '';}

		if( ini_get('allow_url_fopen') ) {
			$thememove_file_method = 'fopen';
		} else {
			$thememove_file_method = 'file_get_contents';
		}
		if ( $thememove_file_method == 'fopen' ) {
			$thememove_handle = fopen( $filepath, 'rb' );

			if( $thememove_handle !== false ) {
				while (!feof($thememove_handle)) {
					$thememove_content .= fread($thememove_handle, 8192);
				}
				fclose( $thememove_handle );
			}
			return $thememove_content;
		} else {
			return file_get_contents($filepath);
		}
	}

	function thememove_admin_import() {
		$this->pagehook = add_menu_page('ThemeMove Theme', esc_html__( $this->theme . ' Import', 'thememove'), 'manage_options', 'thememove_options_import_page', array(&$this, 'thememove_generate_import_page'),'dashicons-download');

	}

	function thememove_generate_import_page() {
        $demos = apply_filters( 'thememove_import_demos', array() );
        $types = apply_filters( 'thememove_import_types', array() );
		?>
        <div class="wrap">
            <h2 class="thememovef-page-title"><?php _e( $this->theme . ' - One-Click Import', 'thememove') ?></h2>
            <div class="update-nag">
                Please wait when the import is running! It will take time needed to download all attachments from demo web site.
			</div>
            <form method="post" action="" id="importContentForm">
                <table class="form-table">
                    <tr>
                        <th class="row"><?php _e( 'Demo Source', 'thememove') ?></th>
                        <td>
                            <select name="demo_source" id="demo_source">
                                <?php foreach ( $demos as $key => $name ) : ?>
                                <option value="<?php echo $key; ?>"><?php echo $name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th class="row"><?php _e( 'Import Type', 'thememove') ?></th>
                        <td>
                            <select name="import_type" id="import_type">
                                <option value="">Please Select</option>
                                <?php foreach ( $types as $key => $name ) : ?>
                                <option value="<?php echo $key; ?>"><?php echo $name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                    	<th class="row">&nbsp;</th>
                    	<td>
                    	    <p class="submit">
                    	        <input type="submit" name="submit" id="import" class="button button-primary" value="<?php _e( 'Import', 'thememove') ?>">
                    	        <span class="spinner" style="float: none;"></span>
                    	    </p>
						</td>
					</tr>
                </table>
            </form>
        </div>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                var attachmentImport = true,
                    $importBtn = $('#import');

				function alertImportSuccessful() {
					$importBtn
						.prop('disabled', false)
						.next('.spinner')
                            .removeClass('is-active');

					alert('Import is successful!');
				}

                $importBtn.on('click', function(e) {
                    e.preventDefault();

					var import_type = $( "#import_type" ).val(),
						demo_source = $( "#demo_source" ).val();

					if (!import_type) {
						alert('Please choose import type!');
						return false;
					}

                    if (confirm('Do you want to import demo now?')) {
						$importBtn
                            .prop('disabled', true)
	                        .next('.spinner')
	                            .addClass('is-active')

                        if (import_type == 'content'){
                            $.ajax({
                                type: 'POST',
                                url: ajaxurl,
                                data: {
                                    action: 'thememove_contentImport',
                                    xml: 'content.xml',
                                    demo_source: demo_source,
                                    import_attachments: (attachmentImport ? 1 : 0)
                                },
                                success: function (data, textStatus, XMLHttpRequest){
                                    alertImportSuccessful();
                                },
                                error: function (MLHttpRequest, textStatus, errorThrown){
                                }
                            });
                        } else if(import_type == 'widgets') {
                            jQuery.ajax({
                                type: 'POST',
                                url: ajaxurl,
                                data: {
                                    action: 'thememove_widgetsImport',
                                    demo_source: demo_source
                                },
                                success: function(data, textStatus, XMLHttpRequest){
                                    alertImportSuccessful();
                                },
                                error: function(MLHttpRequest, textStatus, errorThrown){
                                }
                            });
                        } else if(import_type == 'page_options'){
                            $.ajax({
                                type: 'POST',
                                url: ajaxurl,
                                data: {
                                    action: 'thememove_page_optionsImport',
                                    demo_source: demo_source
                                },
                                success: function(data, textStatus, XMLHttpRequest){
                                    alertImportSuccessful();
                                },
                                error: function(MLHttpRequest, textStatus, errorThrown){
                                }
                            });
						} else if (import_type == 'menus') {
							$.ajax({
                                type: 'POST',
                                url: ajaxurl,
                                data: {
                                    action: 'thememove_menusImport',
                                    demo_source: demo_source
                                },
                                success: function(data, textStatus, XMLHttpRequest){
                                    alertImportSuccessful();
                                },
                                error: function(MLHttpRequest, textStatus, errorThrown){
                                }
                            });
						} else if (import_type == 'customizer_options') {
							$.ajax({
                                type: 'POST',
                                url: ajaxurl,
                                data: {
                                    action: 'thememove_customizer_optionsImport',
                                    demo_source: demo_source
                                },
                                success: function(data, textStatus, XMLHttpRequest){
                                    alertImportSuccessful();
                                },
                                error: function(MLHttpRequest, textStatus, errorThrown){
                                }
							});
						} else if (import_type == 'essential_grid') {
							$.ajax({
                                type: 'POST',
                                url: ajaxurl,
                                data: {
                                    action: 'thememove_essential_gridImport',
                                    demo_source: demo_source
                                },
                                success: function(data, textStatus, XMLHttpRequest){
                                    alertImportSuccessful();
                                },
                                error: function(MLHttpRequest, textStatus, errorThrown){
                                }
							});
						} else if (import_type == 'rev_slider') {
							$.ajax({
                                type: 'POST',
                                url: ajaxurl,
                                data: {
                                    action: 'thememove_rev_sliderImport',
                                    demo_source: demo_source
                                },
                                success: function(data, textStatus, XMLHttpRequest){
                                    alertImportSuccessful();
                                },
                                error: function(MLHttpRequest, textStatus, errorThrown){
                                }
							});
						} else if (import_type == 'vc_templates') {
							$.ajax({
                                type: 'POST',
                                url: ajaxurl,
                                data: {
                                    action: 'thememove_vc_templatesImport',
                                    demo_source: demo_source
                                },
                                success: function(data, textStatus, XMLHttpRequest){
                                    alertImportSuccessful();
                                },
                                error: function(MLHttpRequest, textStatus, errorThrown){
                                }
							});
                        } else if (import_type == 'all'){
                            $.ajax({
                                type: 'POST',
                                url: ajaxurl,
                                data: {
                                    action: 'thememove_contentImport',
                                    xml: 'content.xml',
                                    demo_source: demo_source,
                                    import_attachments: (attachmentImport ? 1 : 0)
                                },
                                success: function (data, textStatus, XMLHttpRequest){
									$.ajax({
                                        type: 'POST',
                                        url: ajaxurl,
                                        data: {
                                            action: 'thememove_metaImport',
                                            demo_source: demo_source
                                        },
                                        success: function(data, textStatus, XMLHttpRequest){
                                            alertImportSuccessful();
                                        },
                                        error: function(MLHttpRequest, textStatus, errorThrown){
                                        }
                                    });
                                },
                                error: function (MLHttpRequest, textStatus, errorThrown){
                                }
                            });
                        }
                    }
                    return false;
                });
            });
        </script>
        </div>
    <?php	}
}
global $ThemeMove_Import;
$ThemeMove_Import = new ThemeMove_Import();

if(!function_exists('thememove_contentImport'))
{
	function thememove_contentImport()
	{
		global $ThemeMove_Import;

		if ($_POST['import_attachments'] == 1)
			{$ThemeMove_Import->attachments = true;}
		else
			{$ThemeMove_Import->attachments = false;}

		$folder = $_POST['demo_source']."/";

		$ThemeMove_Import->import_content($folder.$_POST['xml']);

		die();
	}

	add_action('wp_ajax_thememove_contentImport', 'thememove_contentImport');
}

if(!function_exists('thememove_widgetsImport'))
{
	function thememove_widgetsImport()
	{
		global $ThemeMove_Import;

		$folder = $_POST['demo_source']."/";

		$ThemeMove_Import->import_widgets($folder.'widgets.txt');

		// Import widget logic
		$ThemeMove_Import->import_widget_logic($folder.'widget_logic_options.txt');

		die();
	}

	add_action('wp_ajax_thememove_widgetsImport', 'thememove_widgetsImport');
}

if(!function_exists('thememove_menusImport'))
{
	function thememove_menusImport()
	{
		global $ThemeMove_Import;

		$ThemeMove_Import->import_menus($_POST['demo_source'] . '/menus.txt');

		die();
	}

	add_action('wp_ajax_thememove_menusImport', 'thememove_menusImport');
}

if(!function_exists('thememove_page_optionsImport'))
{
	function thememove_page_optionsImport()
	{
		global $ThemeMove_Import;

		$ThemeMove_Import->import_page_options($_POST['demo_source'] . '/page_options.txt');

		die();
	}

	add_action('wp_ajax_thememove_page_optionsImport', 'thememove_page_optionsImport');
}

if(!function_exists('thememove_customizer_optionsImport'))
{
	function thememove_customizer_optionsImport()
	{
		global $ThemeMove_Import;

		$ThemeMove_Import->import_customizer_options($_POST['demo_source'] . '/skins/Skin 1.txt');

		die();
	}

	add_action('wp_ajax_thememove_customizer_optionsImport', 'thememove_customizer_optionsImport');
}

if(!function_exists('thememove_essential_gridImport'))
{
	function thememove_essential_gridImport()
	{
		global $ThemeMove_Import;

		$ThemeMove_Import->import_essential_grid($_POST['demo_source'] . '/essential_grid.txt');

		die();
	}

	add_action('wp_ajax_thememove_essential_gridImport', 'thememove_essential_gridImport');
}

if(!function_exists('thememove_rev_sliderImport'))
{
	function thememove_rev_sliderImport()
	{
		global $ThemeMove_Import;

		$ThemeMove_Import->import_rev_slider($_POST['demo_source']);

		die();
	}

	add_action('wp_ajax_thememove_rev_sliderImport', 'thememove_rev_sliderImport');
}

if (!function_exists('thememove_vc_templatesImport'))
{
	function thememove_vc_templatesImport()
	{
		global $ThemeMove_Import;

		$ThemeMove_Import->attachments = true;

		$ThemeMove_Import->import_content($_POST['demo_source'] . "/vc_templates.xml");

		die();
	}

	add_action('wp_ajax_thememove_vc_templatesImport', 'thememove_vc_templatesImport');
}

if(!function_exists('thememove_metaImport'))
{
	function thememove_metaImport()
	{
		global $ThemeMove_Import;

		$folder = $_POST['demo_source'] . "/";

		$import_types = apply_filters( 'thememove_import_types', array() );

		if (!empty($import_types['menus'])) {
			$ThemeMove_Import->import_menus($folder.'menus.txt');
		}

		if (!empty($import_types['widgets'])) {
			$ThemeMove_Import->import_widgets($folder.'widgets.txt');
			
			// Import widget logic
			$ThemeMove_Import->import_widget_logic($folder.'widget_logic_options.txt');
		}

		if (!empty($import_types['page_options'])) {
			$ThemeMove_Import->import_page_options($folder.'page_options.txt');
		}

		if (!empty($import_types['customizer_options'])) {
			$ThemeMove_Import->import_customizer_options($folder . 'skins/Skin 1.txt');
		}

		if (!empty($import_types['essential_grid']) && in_array( 'essential-grid/essential-grid.php', apply_filters( 'active_plugins', get_option('active_plugins')))) {
			$ThemeMove_Import->import_essential_grid($folder . 'essential_grid.txt');
		}

		if (!empty($import_types['rev_slider']) && in_array( 'revslider/revslider.php', apply_filters( 'active_plugins', get_option('active_plugins')))) {
			$ThemeMove_Import->import_rev_slider($_POST['demo_source']);
		}

		die();
	}

	add_action('wp_ajax_thememove_metaImport', 'thememove_metaImport');
}