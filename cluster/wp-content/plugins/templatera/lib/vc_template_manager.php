<?php
/**
 * Main object for controls
 *
 * @package vas_map
 */
if ( ! class_exists( 'VcTemplateManager' ) ) {
	class VcTemplateManager {
		protected $dir;
		protected static $post_type = "templatera";
		protected static $meta_data_name = "templatera";
		protected $settings_tab = 'templatera';
		protected $filename = 'templatera';
		protected $themes_dir = 'css/themes';
		protected $init = false;
		protected $current_post_type = false;
		protected $themes = array();
		protected $settings = array(
			'assets_dir' => 'assets',
			'templates_dir' => 'templates',
			'template_extension' => 'tpl.php'
		);

		function __construct( $dir ) {
			$this->dir = empty( $dir ) ? dirname( dirname( __FILE__ ) ) : $dir; // Set dir or find by current file path.
			$this->plugin_dir = basename( $this->dir ); // Plugin directory name required to append all required js/css files.
			add_filter( 'wpb_vc_js_status_filter', array( &$this, 'setJsStatusValue' ) );
		}

		/**
		 * @static
		 * Singleton
		 *
		 * @param string $dir
		 *
		 * @return VcTemplateManager
		 */
		public static function getInstance( $dir = '' ) {
			static $instance = null;
			if ( $instance === null ) {
				$instance = new VcTemplateManager( $dir );
			}

			return $instance;
		}

		/**
		 * @static
		 * Install plugins.
		 * Migrate default templates into templatera
		 * @return void
		 */
		public static function install() {
			$migrated = get_option( 'templatera_migrated_templates' ); // Check is migration already performed
			if ( $migrated !== 'yes' ) {
				$templates = (array) get_option( 'wpb_js_templates' );
				foreach ( $templates as $template ) {
					self::create( $template['name'], $template['template'] );
				}
				update_option( 'templatera_migrated_templates', 'yes' );
			}
		}

		/**
		 * @return string
		 */
		public static function postType() {
			return self::$post_type;
		}

		/**
		 * Initialize plugin data
		 * @return VcTemplateManager
		 */
		function init() {
			if ( $this->init ) {
				return $this;
			} // Disable double initialization.
			$this->init = true;

			if ( isset( $_GET['action'] ) && $_GET['action'] === 'export_templatera' ) {
				add_action( 'wp_loaded', array( &$this, 'export' ) );
			} elseif ( isset( $_GET['action'] ) && $_GET['action'] === 'import_templatera' ) {
				add_action( 'wp_loaded', array( &$this, 'import' ) );
			}
			$this->createPostType();
			$this->initPluginLoaded(); // init filters/actions and hooks
			// Add vc template post type into the list of allowed post types for visual composer.
			if ( ( isset( $_GET['post'] ) && get_post_type( $_GET['post'] ) === self::$post_type ) || ( isset( $_GET['post_type'] ) && $_GET['post_type'] == self::$post_type ) ) {
				$pt_array = get_option( 'wpb_js_content_types' );
				if ( ! is_array( $pt_array ) || empty( $pt_array ) ) {
					$pt_array = array( self::$post_type, 'page' );
					update_option( 'wpb_js_content_types', $pt_array );
				} elseif ( ! in_array( self::$post_type, $pt_array ) ) {
					$pt_array[] = self::$post_type;
					update_option( 'wpb_js_content_types', $pt_array );
				}
				add_action( 'admin_init', array( &$this, 'createMetaBox' ), 1 );
			} else {
				add_action( 'wp_loaded', array( $this, 'createShortcode' ) );

			}

			return $this; // chaining.
		}

		/**
		 * Create tab on VC settings page.
		 *
		 * @param $tabs
		 *
		 * @return array
		 */
		public function addTab( $tabs ) {
			$tabs[ $this->settings_tab ] = __( 'Templatera', "templatera" );

			return $tabs;
		}

		/**
		 * Create tab fields. in Visual composer settings page options-general.php?page=vc_settings
		 *
		 * @param Vc_Settings $settings
		 */
		public function buildTab( Vc_Settings $settings ) {
			$settings->addSection( $this->settings_tab );
			add_filter( 'vc_setting-tab-form-' . $this->settings_tab, array( &$this, 'settingsFormParams' ) );
			$settings->addField( $this->settings_tab, __( 'Export VC Templates', "templatera" ), 'export', array(
				&$this,
				'settingsFieldExportSanitize'
			), array( &$this, 'settingsFieldExport' ) );
			$settings->addField( $this->settings_tab, __( 'Import VC Templates', "templatera" ), 'import', array(
				&$this,
				'settingsFieldImportSanitize'
			), array( &$this, 'settingsFieldImport' ) );
		}

		/**
		 * Custom attributes for tab form.
		 * @see VcTemplateManager::buildTab
		 *
		 * @param $params
		 *
		 * @return string
		 */
		public function settingsFormParams( $params ) {
			$params .= ' enctype="multipart/form-data"';

			return $params;
		}

		/**
		 * Sanitize export field.
		 * @return bool
		 */
		public function settingsFieldExportSanitize() {
			return false;
		}

		/**
		 * Builds export link in settings tab.
		 */
		public function settingsFieldExport() {
			echo '<a href="export.php?page=wpb_vc_settings&action=export_templatera" class="button">' . __( 'Download Export File', "templatera" ) . '</a>';
		}

		/**
		 * Export existing template in XML format.
		 *
		 */
		public function export() {
			$templates = get_posts( array(
				'post_type' => self::$post_type,
				'numberposts' => - 1
			) );
			$xml = '<?xml version="1.0"?><templates>';
			foreach ( $templates as $template ) {
				$id = $template->ID;
				$meta_data = get_post_meta( $id, self::$meta_data_name, true );
				$post_types = isset( $meta_data['post_type'] ) ? $meta_data['post_type'] : false;
				$user_roles = isset( $meta_data['user_role'] ) ? $meta_data['user_role'] : false;
				$xml .= '<template>';
				$xml .= '<title>' . apply_filters( 'the_title_rss', $template->post_title ) . '</title>'
				        . '<content>' . $this->wxr_cdata( apply_filters( 'the_content_export', $template->post_content ) ) . '</content>';
				if ( $post_types !== false ) {
					$xml .= '<post_types>';
					foreach ( $post_types as $t ) {
						$xml .= '<post_type>' . $t . '</post_type>';
					}
					$xml .= '</post_types>';
				}
				if ( $user_roles !== false ) {
					$xml .= '<user_roles>';
					foreach ( $user_roles as $u ) {
						$xml .= '<user_role>' . $u . '</user_role>';
					}
					$xml .= '</user_roles>';
				}

				$xml .= '</template>';
			}
			$xml .= '</templates>';
			header( 'Content-Description: File Transfer' );
			header( 'Content-Disposition: attachment; filename=' . $this->filename . '_' . date( 'dMY' ) . '.xml' );
			header( 'Content-Type: text/xml; charset=' . get_option( 'blog_charset' ), true );
			echo $xml;
			die();
		}

		/**
		 * Import templates from file to the database by parsing xml file
		 * @return bool
		 */
		public function settingsFieldImportSanitize() {
			$file = isset( $_FILES['import'] ) ? $_FILES['import'] : false;
			if ( $file === false || ! file_exists( $file['tmp_name'] ) ) {
				return false;
			} else {
				$post_types = get_post_types( array( 'public' => true ) );
				$roles = get_editable_roles();
				$templateras = simplexml_load_file( $file['tmp_name'] );
				foreach ( $templateras as $t ) {
					$template_post_types = $template_user_roles = $meta_data = array();
					$content = (string) $t->content;
					$id = $this->create( (string) $t->title, $content );
					$this->contentMediaUpload( $id, $content );
					foreach ( $t->post_types as $type ) {
						$post_type = (string) $type->post_type;
						if ( in_array( $post_type, $post_types ) ) {
							$template_post_types[] = $post_type;
						}
					}
					if ( ! empty( $template_post_types ) ) {
						$meta_data['post_type'] = $template_post_types;
					}
					foreach ( $t->user_roles as $role ) {
						$user_role = (string) $role->user_role;
						if ( in_array( $user_role, $roles ) ) {
							$template_user_roles[] = $user_role;
						}
					}
					if ( ! empty( $template_user_roles ) ) {
						$meta_data['user_role'] = $template_user_roles;
					}
					update_post_meta( (int) $id, self::$meta_data_name, $meta_data );
				}
				@unlink( $file['tmp_name'] );
			}

			return false;
		}

		/**
		 * Build import file input.
		 */
		public function settingsFieldImport() {
			echo '<input type="file" name="import">';
		}

		/**
		 * Upload external media files in a post content to media library.
		 *
		 * @param $post_id
		 * @param $content
		 *
		 * @return bool
		 */
		protected function contentMediaUpload( $post_id, $content ) {
			preg_match_all( '/<img|a[^>]* src|href=[\'"]?([^>\'" ]+)/', $content, $matches );
			foreach ( $matches[1] as $match ) {
				if ( ! empty( $match ) ) {
					$file_array = array();
					$file_array['name'] = basename( $match );
					$tmp_file = download_url( $match );
					$file_array['tmp_name'] = $tmp_file;
					if ( is_wp_error( $tmp_file ) ) {
						@unlink( $file_array['tmp_name'] );
						$file_array['tmp_name'] = '';

						return false;
					}
					$desc = $file_array['name'];
					$id = media_handle_sideload( $file_array, $post_id, $desc );
					if ( is_wp_error( $id ) ) {
						@unlink( $file_array['tmp_name'] );

						return false;
					} else {
						$src = wp_get_attachment_url( $id );
					}
					$content = str_replace( $match, $src, $content );
				}
			}
			wp_update_post( array( 'ID' => $post_id, 'post_content' => $content ) );

			return true;
		}

		/**
		 * CDATA field type for XML
		 *
		 * @param $str
		 *
		 * @return string
		 */
		function wxr_cdata( $str ) {
			if ( seems_utf8( $str ) == false ) {
				$str = utf8_encode( $str );
			}

			// $str = ent2ncr(esc_html($str));
			$str = '<![CDATA[' . str_replace( ']]>', ']]]]><![CDATA[>', $str ) . ']]>';

			return $str;
		}

		/**
		 * Create post type "templatera" and item in the admin menu.
		 * @return void
		 */
		function createPostType() {
			register_post_type( self::$post_type,
				array(
					'labels' => self::getPostTypesLabels(),
					'public' => false,
					'has_archive' => false,
					'show_in_nav_menus' => true,
					'exclude_from_search' => true,
					'publicly_queryable' => true,
					'show_ui' => true,
					'query_var' => true,
					'capability_type' => 'post',
					'hierarchical' => false,
					'menu_position' => null,
					'menu_icon' => $this->assetUrl( 'images/icon.gif' ),
					'show_in_menu' => ! WPB_VC_NEW_MENU_VERSION,
				)
			);
		}
		public static function getPostTypesLabels() {
			return array(
				'add_new_item' => __( 'Add template', "templatera" ),
				'name' => __( 'Templates', "templatera" ),
				'singular_name' => __( 'Template', "templatera" ),
				'edit_item' => __( 'Edit Template', "templatera" ),
				'view_item' => __( 'View Template', "templatera" ),
				'search_items' => __( 'Search Templates', "templatera" ),
				'not_found' => __( 'No Templates found', "templatera" ),
				'not_found_in_trash' => __( 'No Templates found in Trash', "templatera" ),
			);
		}
		/**
		 * Init filters / actions hooks
		 */
		function initPluginLoaded() {
			load_plugin_textdomain( "templatera", false, basename( $this->dir ) . '/locale' );
			add_action( 'wp_enqueue_scripts', array( &$this, 'enqueueThemeFiles' ) );
			add_action( 'vc_frontend_editor_render', array( &$this, 'addEditorTemplates' ) );
			add_action( 'vc_backend_editor_render', array( &$this, 'addEditorTemplates' ) );

			// Check for nav controls
			if ( $this->isNewVcVersion() ) {
				add_filter( 'vc_nav_controls', array( &$this, 'createButtonFrontBack' ) );
			} else {
				add_filter( 'vc_nav_controls', array( &$this, 'createButton' ) );
			}
			add_filter( 'vc_nav_front_controls', array( &$this, 'createButtonFrontBack' ) );

			// add settings tab in visual composer settings
			add_filter( 'vc_settings_tabs', array( &$this, 'addTab' ) );
			// build settings tab @ER
			add_action( 'vc_settings_tab-' . $this->settings_tab, array( &$this, 'buildTab' ) );

			add_action( 'admin_print_scripts-post.php', array( &$this, 'assets' ) );
			add_action( 'admin_print_scripts-post-new.php', array( &$this, 'assets' ) );
			add_action( 'vc_frontend_editor_enqueue_js_css', array( &$this, 'assets' ) );
			if ( $this->isPanelVcVersion() ) {
				//@since 4.4 we use new panel window
				add_action( 'wp_ajax_vc_templatera_save_template', array( &$this, 'saveTemplate' ) );
				add_action( 'wp_ajax_vc_templatera_delete_template', array( &$this, 'delete' ) );
				add_filter( 'vc_templates_render_category', array(
					&$this,
					'renderTemplateBlock'
				), 10, 2 );
				add_filter( 'vc_templates_render_template', array(
					&$this,
					'renderTemplateWindow'
				), 10, 2 );

				if ( $this->getPostType() != 'vc_grid_item' ) {
					add_filter( 'vc_get_all_templates', array( &$this, 'replaceCustomWithTemplateraTemplates' ) );
				}
				add_filter( 'vc_templates_render_frontend_template', array(
					&$this,
					'renderFrontendTemplate'
				), 10, 2 );
				add_filter( 'vc_templates_render_backend_template', array(
					&$this,
					'renderBackendTemplate'
				), 10, 2 );
				add_filter( 'vc_templates_show_save', array( &$this, 'addTemplatesShowSave' ) );
			} else {
				// @deprecated since 4.4
				add_action( 'wp_ajax_templatera_plugin_save', array( &$this, 'save' ) );
				// @deprecated since 4.4
				add_action( 'wp_ajax_templatera_plugin_load', array( &$this, 'load' ) );
				// @deprecated since 4.4
				add_action( 'wp_ajax_templatera_plugin_load_inline', array( &$this, 'loadInline' ) );
				// actual even in 4.4 (same realization)
				add_action( 'wp_ajax_templatera_plugin_delete', array( &$this, 'delete' ) );
			}
			add_action( 'wp_ajax_wpb_templatera_load_html', array(
				&$this,
				'loadHtml'
			) ); // used in changeShortcodeParams in templates.js, todo make sure we need this?
			add_filter( 'body_class', array( &$this, 'addThemeBodyClass' ) );
			add_action( 'save_post', array( &$this, 'saveMetaBox' ) );

		}

		/**
		 * This used to detect what version of nav_controls use, and panels/modals js/template
		 *
		 * @param string $version
		 *
		 * @return bool
		 */
		function isNewVcVersion( $version = '4.2.3' ) {
			return defined( 'WPB_VC_VERSION' ) && version_compare( WPB_VC_VERSION, $version ) >=0;
		}

		/**
		 * Removes save block if we editing templatera page
		 * In add templates panel window
		 * @since 4.4
		 * @return bool
		 */
		public function addTemplatesPanelShowSave( $show_save ) {
			if ( get_post_type() == self::$post_type ) {
				$show_save = false; // we don't need "save" block if we editing templatera page.
			}

			return $show_save;
		}

		/**
		 * @since 4.4 we implemented new panel windows
		 * @return bool
		 */
		function isPanelVcVersion() {
			return $this->isNewVcVersion( '4.4' ); // todo change in production to 4.4
		}

		/**
		 * Used to render template for backend
		 * @since 4.4
		 *
		 * @param $template_id
		 * @param $template_type
		 *
		 * @return string|int
		 */
		public function renderBackendTemplate( $template_id, $template_type ) {
			if ( $template_type == 'templatera_templates' ) {
				// do something to return output of templatera template
				$post = get_post( $template_id );
				if ( $post->post_type == self::$post_type ) {
					echo $post->post_content;
					die();
				}
			}

			return $template_id;
		}

		/**
		 * Used to render template for frontend
		 * @since 4.4
		 *
		 * @param $template_id
		 * @param $template_type
		 *
		 * @return string|int
		 */
		public function renderFrontendTemplate( $template_id, $template_type ) {
			if ( $template_type == 'templatera_templates' ) {
				// do something to return output of templatera template
				$post = get_post( $template_id );
				if ( $post->post_type == self::$post_type ) {
					set_vc_is_inline(); // todo make sure we need this?
					vc_frontend_editor()->enqueueRequired();
					vc_frontend_editor()->setTemplateContent( $post->post_content );
					vc_frontend_editor()->render( 'template' );
					die();
				}
			}

			return $template_id;
		}
		public function renderTemplateBlock( $category ) {
			if ( 'templatera_templates' == $category['category'] ) {
				$category['output'] = '
				<div class="vc_column vc_col-sm-12">
					<div class="vc_element_label">' . esc_html( 'Save current layout as a template', 'js_composer' ) . '</div>
					<div class="vc_input-group">
						<input name="padding" class="vc_form-control wpb-textinput vc_panel-templates-name" type="text" value=""
						       placeholder="' . esc_attr( 'Template name', 'js_composer' ) . '">
						<span class="vc_input-group-btn"> <button class="vc_btn vc_btn-primary vc_btn-sm vc_template-save-btn">' . esc_html( 'Save template', 'js_composer' ) . '</button></span>
					</div>
					<span class="vc_description">' . esc_html( 'Save your layout and reuse it on different sections of your website', 'js_composer' ) . '</span>
				</div>';
				$category['output'] .= '<div class="vc_col-md-12">';
				if ( isset( $category['category_name'] ) ) {
					$category['output'] .= '<h3>' . esc_html( $category['category_name'] ) . '</h3>';
				}
				if ( isset( $category['category_description'] ) ) {
					$category['output'] .= '<p class="vc_description">' . esc_html( $category['category_description'] ) . '</p>';
				}
				$category['output'] .= '</div>';
				$category['output'] .= '
			<div class="vc_column vc_col-sm-12">
			<ul class="vc_templates-list-my_templates">';
				if ( ! empty( $category['templates'] ) ) {
					foreach ( $category['templates'] as $template ) {
						$name = isset( $template['name'] ) ? esc_html( $template['name'] ) : esc_html( __( 'No title', 'js_composer' ) );
						$type = isset( $template['type'] ) ? $template['type'] : 'custom';
						$custom_class = isset( $template['custom_class'] ) ? $template['custom_class'] : '';
						$unique_id = isset( $template['unique_id'] ) ? $template['unique_id'] : false; // You must provide unique_id otherwise it will be wrong in rendering
						// see hook filters in Vc_Templates_Panel_Editor::__construct
						$category['output'] .= '<li class="vc_col-sm-4 vc_template vc_templates-template-type-' . esc_attr( $type ) . ' ' . esc_attr( $custom_class ) . '"
									    data-category="' . esc_attr( $category['category'] ) . '"
									    data-template_unique_id="' . esc_attr( $unique_id ) . '"
									    data-template_type="' . esc_attr( $type ) . '">' . apply_filters( 'vc_templates_render_template', $name, $template ) . '</li>';
					}
				}
				$category['output'] .= '</ul></div>';
			}

			return $category;
		}
		/**
		 * Hook templates panel window rendering, if template type is templatera_templates render it
		 * @since 4.4
		 *
		 * @param $template_name
		 * @param $template_data
		 *
		 * @return string
		 */
		public function renderTemplateWindow( $template_name, $template_data ) {
			if ( $template_data['type'] == 'templatera_templates' ) {
				return $this->renderTemplateWindowTemplateraTemplates( $template_name, $template_data );
			}

			return $template_name;
		}

		/**
		 * Rendering templatera template for panel window
		 * @since 4.4
		 *
		 * @param $template_name
		 * @param $template_data
		 *
		 * @return string
		 */
		public function renderTemplateWindowTemplateraTemplates( $template_name, $template_data ) {
			ob_start();
			?>
			<div class="vc_template-wrapper vc_input-group" data-template_id="<?php echo esc_attr( $template_data['unique_id'] ); ?>">
				<a data-template-handler="true" class="vc_template-display-title vc_form-control" href="javascript:;"><?php echo esc_html( $template_name ); ?></a>
			<span class="vc_input-group-btn vc_template-icon vc_template-edit-icon" title="<?php esc_attr_e( 'Edit template', 'templatera' ); ?>"
			      data-template_id="<?php echo esc_attr( $template_data['unique_id'] ); ?>"><a
					href="<?php echo esc_attr( admin_url( 'post.php?post=' . $template_data['unique_id'] . '&action=edit' ) ); ?>"
			                                                                                   target="_blank" class="vc_icon"></i></a></span>
			<span class="vc_input-group-btn vc_template-icon vc_template-delete-icon" title="<?php esc_attr_e( 'Delete template', 'templatera' ); ?>"
			      data-template_id="<?php echo esc_attr( $template_data['unique_id'] ); ?>"><i
					class="vc_icon"></i></span>
			</div>
			<?php

			return ob_get_clean();
		}

		/**
		 * Function used to replace old my templates with new templatera templates
		 * @since 4.4
		 *
		 * @param array $data
		 *
		 * @return array
		 */
		public function replaceCustomWithTemplateraTemplates( array $data ) {
			$templatera_templates = $this->getTemplateList();
			foreach ( $templatera_templates as $template_name => $template_id ) {
				$templatera_arr[] = array(
					'unique_id' => $template_id,
					'name' => $template_name,
					'type' => 'templatera_templates', // for rendering in backend/frontend with ajax);
				);
			}

			if ( ! empty( $data ) ) {
				$found = false;
				foreach ( $data as $key => $category ) {
					if ( $category['category'] == 'my_templates' ) {
						$found = true;
						if ( empty( $templatera_arr ) ) {
							unset( $data[ $key ] );
						} else {
							$data[ $key ]['templates'] = $templatera_arr;
						}
					}
				}
				if ( ! $found && ! empty( $templatera_arr ) ) {
					$data[] = array(
						'templates' => $templatera_arr,
						'category' => 'my_templates',
						'category_name' => __( 'My Templates', 'js_composer' ),
						'category_description' => __( 'Append previously saved template to the current layout', 'js_composer' ),
						'category_weight' => 10,
					);
				}
			} else if ( ! empty( $templatera_arr ) ) {
				$data[] = array(
					'templates' => $templatera_arr,
					'category' => 'my_templates',
					'category_name' => __( 'My Templates', 'js_composer' ),
					'category_description' => __( 'Append previously saved template to the current layout', 'js_composer' ),
					'category_weight' => 10,
				);
			}

			return $data;
		}

		/**
		 * Maps Frozen row shortcode
		 */
		function createShortcode() {
			vc_map( array(
				"name" => __( "Templatera", "templatera" ),
				"base" => "templatera",
				"icon" => "icon-templatera",
				"category" => __( 'Content', "templatera" ),
				"params" => array(
					array(
						"type" => "dropdown",
						"heading" => __( "Select template", "templatera" ),
						"param_name" => "id",
						"value" => array(__('Choose template', 'js_composer') => '') + $this->getTemplateList(),
						"description" => __( "Choose which template to load for this location.", "templatera" )
					),
					array(
						"type" => "textfield",
						"heading" => __( "Extra class name", "templatera" ),
						"param_name" => "el_class",
						"description" => __( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "templatera" )
					)
				),
				"js_view" => 'VcTemplatera'
			) );
			add_shortcode( 'templatera', array( &$this, 'outputShortcode' ) );
		}

		/**
		 * Frozen row shortcode hook.
		 *
		 * @param $atts
		 * @param string $content
		 *
		 * @return string
		 */
		public function outputShortcode( $atts, $content = '' ) {
			$id = $el_class = $output = '';
			extract( shortcode_atts( array(
				'el_class' => '',
				'id' => ''
			), $atts ) );
			if ( empty( $id ) ) {
				return $output;
			}
			$post = get_post( $id );
			if ( $post ) {
				$output .= '<div class="templatera_shortcode' . ( $el_class ? ' ' . $el_class : '' ) . '">';
				if ( $post->post_type === self::$post_type ) {
					$output .= apply_filters( 'the_content', $post->post_content );
				}
				$output .= '</div>';
			}
			wp_enqueue_style( 'templatera_inline', $this->assetUrl( 'css/front_style.css' ), false, '2.1' );

			return $output;
		}

		/**
		 * Create meta box for self::$post_type, with template settings
		 */
		public function createMetaBox() {
			add_meta_box( 'vas_template_settings_metabox', __( 'Template Settings', "templatera" ), array(
				&$this,
				'sideOutput'
			), self::$post_type, 'side', 'high' );
		}

		/**
		 * Used in meta box VcTemplateManager::createMetaBox
		 */
		public function sideOutput() {
			$data = get_post_meta( get_the_ID(), self::$meta_data_name, true );
			$data_post_types = isset( $data['post_type'] ) ? $data['post_type'] : array();
			$post_types = get_post_types( array( 'public' => true ) );
			echo '<div class="misc-pub-section">
            <div class="templatera_title"><b>' . __( 'Post types', "templatera" ) . '</b></div>
            <div class="input-append">
                ';
			foreach ( $post_types as $t ) {
				if ( $t != 'attachment' && $t != self::$post_type ) {
					echo '<label><input type="checkbox" name="' . self::$meta_data_name . '[post_type][]" value="' . $t . '"' . ( in_array( $t, $data_post_types ) ? ' checked="true"' : '' ) . '> ' . ucfirst( $t ) . '</label><br/>';
				}
			}
			echo '</div><p>' . __( 'Select for which post types this template should be available. Default: Available for all post types.', "templatera" ) . '</p></div>';
			$groups = get_editable_roles();
			$data_user_role = isset( $data['user_role'] ) ? $data['user_role'] : array();
			echo '<div class="misc-pub-section vc_user_role">
            <div class="templatera_title"><b>' . __( 'Roles', "templatera" ) . '</b></div>
            <div class="input-append">
                ';
			foreach ( $groups as $key => $g ) {
				echo '<label><input type="checkbox" name="' . self::$meta_data_name . '[user_role][]" value="' . $key . '"' . ( in_array( $key, $data_user_role ) ? ' checked="true"' : '' ) . '> ' . $g['name'] . '</label><br/>';
			}
			echo '</div><p>' . __( 'Select for user roles this template should be available. Default: Available for all user roles.', "templatera" ) . '</p></div>';
		}

		/**
		 * Url to js/css or image assets of plugin
		 *
		 * @param $file
		 *
		 * @return string
		 */
		public function assetUrl( $file ) {
			return plugins_url( $this->plugin_dir . '/assets/' . $file );
		}

		/**
		 * Absolute path to assets files
		 *
		 * @param $file
		 *
		 * @return string
		 */
		public function assetPath( $file ) {
			return $this->dir . '/assets/' . $file;
		}
		public function isValidPostType() {
			return in_array( get_post_type(), vc_editor_post_types() + array( 'templatera' ) );
		}

		/**
		 * Load required js and css files
		 */
		public function assets() {
			if( $this->isValidPostType() ) {
				wp_register_script( 'vc_plugin_templates', $this->assetURL( 'js/templates.js' ), array(), time(), true );
				wp_localize_script( 'vc_plugin_templates', 'VcTemplateI18nLocale', array(
					'please_enter_templates_name' => __( 'Please enter template name', "templatera" )
				) );
				wp_register_style( 'vc_plugin_template_css', $this->assetURL( 'css/style.css' ), false, '1.1.0' );
				if ( ! $this->isPanelVcVersion() ) {
					wp_enqueue_style( 'vc_templatera_new_tabs_styles', $this->assetURL( 'css/vc_new_tabs' ), false, '1.2' );
				}
				wp_enqueue_style( 'vc_plugin_template_css' );
			}
		}

		/**
		 * Include theme files and css classes
		 */
		public function enqueueThemeFiles() {
			$field_prefix = vc_settings()->getFieldPrefix();
			$theme = ( $theme = get_option( $field_prefix . 'themes' ) ) ? $theme : '';
			if ( ! empty( $theme ) ) {
				wp_register_style( 'vc_plugin_template_theme_css', $this->assetURL( $this->themes_dir . '/' . $theme ), array( 'js_composer_front' ), 'templatera_2' );
				wp_enqueue_style( 'vc_plugin_template_theme_css' );
			}

		}

		/**
		 * Adds themes css class to body tag.
		 *
		 * @param $classes
		 *
		 * @return array
		 */
		public function addThemeBodyClass( $classes ) {
			//if(!class_exists('WPBakeryVisualComposerSettings')) return $classes;
			$field_prefix = vc_settings()->getFieldPrefix();
			$theme = ( $theme = get_option( $field_prefix . 'themes' ) ) ? $theme : '';
			if ( ! empty( $theme ) ) {
				$classes[] = 'vct_' . preg_replace( '/\.css$/', '', $theme );
			}

			return $classes;
		}

		public function getPostType() {
			if ( $this->current_post_type ) {
				return $this->current_post_type;
			}
			$post_type = false;
			if ( isset( $_GET['post'] ) ) {
				$post_type = get_post_type( $_GET['post'] );
			} else if ( isset( $_GET['post_type'] ) ) {
				$post_type = $_GET['post_type'];
			}
			$this->current_post_type = $post_type;

			return $this->current_post_type;
		}
		/**
		 * Create templates button on navigation bar of the Front/Backend editor.
		 *
		 * @param $buttons
		 *
		 * @return array
		 */
		public function createButtonFrontBack( $buttons ) {

			if ( $this->getPostType() == "vc_grid_item" ) {
				return $buttons;
			}

			$new_buttons = array();

			foreach ( $buttons as $button ) {
				if ( $button[0] != 'templates' ) {
					// disable custom css as well but only in templatera page
					if ( get_post_type() != self::$post_type || ( get_post_type() == self::$post_type && $button[0] != 'custom_css' ) ) {
						$new_buttons[] = $button;
					}
				} else {
					if ( $this->isPanelVcVersion() ) {
						// @since 4.4 button is available but "Save" Functionality in form is disabled in templatera post.
						$new_buttons[] = array(
							'custom_templates',
							'<li class="vc_navbar-border-right"><a href="#" class="vc_icon-btn vc_templatera_button"  id="vc-templatera-editor-button" title="' . __( 'Templates', 'js_composer' ) . '"></a></li>'
						);
					} else {
						if ( get_post_type() == self::$post_type ) {
							// in older version we doesn't need to display templates window in templatera post
						} else {
							$new_buttons[] = array(
								'custom_templates',
								'<li class="vc_navbar-border-right"><a href="#" class="vc_icon-btn vc_templatera_button"  id="vc-templatera-editor-button" title="' . __( 'Templates', 'js_composer' ) . '"></a></li>'
							);
						}
					}

				}
			}

			return $new_buttons;
		}

		/**
		 * Add javascript to extend functionality of templates editor panel or new panel(since 4.4)
		 */
		public function addEditorTemplates() {
			$dependency = vc_is_frontend_editor() ? array(
				'vc_inline_build_js',
				'vc_inline_js'
			) : array( 'wpb_js_composer_js_view' );
			if ( $this->isPanelVcVersion() ) {
				wp_enqueue_script( 'vc_plugin_inline_templates', $this->assetURL( 'js/templates_panels.js' ), $dependency, WPB_VC_VERSION, true );
			} else {
				//@deprecated since 4.4, we use new panel windows.
				wp_enqueue_script( 'vc_plugin_inline_templates', $this->assetURL( 'js/inline.js' ), $dependency, WPB_VC_VERSION, true );
				//@deprecated since 4.4, we use new pane windows and do not need to override it just extend
				add_action( 'admin_footer', array( &$this, 'renderEditorTemplate' ) );

			}
			add_action( 'admin_footer', array( &$this, 'addTemplateraJs' ) );
		}

		/**
		 * Used to add js in backend/frontend to init template UI functionality
		 */
		public function addTemplateraJs() {
			wp_enqueue_script( 'vc_plugin_templates' );
		}

		/**
		 * Used to save new template from ajax request in new panel window
		 * @since 4.4
		 *
		 */
		public function saveTemplate() {
			$title = vc_post_param( 'template_name' );
			$content = vc_post_param( 'template' );
			$template_id = $this->create( $title, $content );
			$template_title = get_the_title( $template_id );
			echo $this->renderTemplateWindowTemplateraTemplates( $template_title, array( 'unique_id' => $template_id ) );
			die();
		}

		/**
		 * Gets list of existing templates. Checks access rules defined by template author.
		 * @return array
		 */
		protected function getTemplateList() {
			global $current_user;
			get_currentuserinfo();
			$current_user_role = isset( $current_user->roles[0] ) ? $current_user->roles[0] : false;
			$list = array();
			$templates = get_posts( array(
				'post_type' => self::$post_type,
				'numberposts' => - 1
			) );
			$post = get_post( isset( $_POST['post_id'] ) ? $_POST['post_id'] : null );
			foreach ( $templates as $template ) {
				$id = $template->ID;
				$meta_data = get_post_meta( $id, self::$meta_data_name, true );
				$post_types = isset( $meta_data['post_type'] ) ? $meta_data['post_type'] : false;
				$user_roles = isset( $meta_data['user_role'] ) ? $meta_data['user_role'] : false;
				if (
					( ! $post || ! $post_types || in_array( $post->post_type, $post_types ) )
					&& ( ! $current_user_role || ! $user_roles || in_array( $current_user_role, $user_roles ) )
				) {
					$list[ $template->post_title ] = $id;
				}
			}

			return $list;
		}

		/**
		 * Creates new template.
		 * @static
		 *
		 * @param $title
		 * @param $content
		 *
		 * @return int|WP_Error
		 */
		protected static function create( $title, $content ) {
			return wp_insert_post( array(
				'post_title' => $title,
				'post_content' => $content,
				'post_status' => 'publish',
				'post_type' => self::$post_type
			) );
		}

		/**
		 * Used to delete template by template id
		 *
		 * @param int $template_id - if provided used, if not provided used vc_post_param('template_id')
		 */
		public function delete( $template_id = null ) {
			$post_id = $template_id ? $template_id : vc_post_param( 'template_id' );
			if ( ! is_null( $post_id ) ) {
				if ( wp_delete_post( $post_id ) ) {
					die( 'deleted' );
				}
			}
			die( 'failed to delete' );
		}

		/**
		 * Saves post data in databases after publishing or updating template's post.
		 *
		 * @param $post_id
		 *
		 * @return bool
		 */
		public function saveMetaBox( $post_id ) {
			if ( get_post_type( $post_id ) !== self::$post_type ) {
				return true;
			}
			if ( isset( $_POST[ self::$meta_data_name ] ) ) {
				$options = isset( $_POST[ self::$meta_data_name ] ) ? (array) $_POST[ self::$meta_data_name ] : Array();
				update_post_meta( (int) $post_id, self::$meta_data_name, $options );
			} else {
				delete_post_meta( (int) $post_id, self::$meta_data_name );
			}

			return true;
		}

		/**
		 * @param $value
		 *
		 * @todo make sure we need this?
		 * @return string
		 */
		public function setJsStatusValue( $value ) {
			$post_type = get_post_type();

			return $post_type === self::$post_type ? 'true' : $value;
		}

		/**
		 * Used in templates.js:changeShortcodeParams
		 * @todo make sure we need this
		 * Output some template content
		 * @todo make sure it is secure?
		 */
		public function loadHtml() {
			$id = vc_post_param( 'id' );
			$post = get_post( (int) $id );
			if ( $post->post_type == self::$post_type ) {
				echo $post->post_content;
			}
			die();
		}

		/**
		 * Sanitize theme value.
		 *
		 * @param $theme
		 *
		 * @deprecated not used anymore and will be removed
		 * @return string
		 */
		public function settingsFieldThemesSanitize( $theme ) {
			$this->getThemes();

			return in_array( $theme, array_keys( $this->themes ) ) ? $theme : ' ';
		}

		/**
		 * Build theme dropdown
		 * @deprecated not used anymore and will be removed
		 */
		public function settingsFieldThemes() {
			$this->getThemes();
			$field_prefix = vc_settings()->getFieldPrefix();
			$value = ( $value = get_option( $field_prefix . 'themes' ) ) ? $value : '';
			echo '<select name="' . $field_prefix . 'themes' . '">';
			echo '<option value=""></option>';
			foreach ( $this->themes as $key => $title ) {
				echo '<option value="' . $key . '"' . ( $value === $key ? ' selected="true"' : '' ) . '>' . __( $title, "templatera" ) . '</option>';
			}
			echo '</select>';
			echo '<p class="description indicator-hint">' . __( 'Select CSS Theme to change content elements visual appearance.', "templatera" ) . '</p>';

		}

		/**
		 * Create themes list. Checks filesystem for existing css files in theme directory.
		 * @deprecated not used anymore and will be removed
		 */
		public function getThemes() {
			$paths = glob( $this->assetPath( $this->themes_dir . '/*.css' ) );
			foreach ( $paths as $path ) {
				$filename = basename( $path );
				$this->themes[ $filename ] = ucwords( preg_replace( array( '/(\.css)$/', '/_/', '/\-/' ), array(
					'',
					' ',
					' '
				), $filename ) );
			}
		}

		/**
		 * List of existing templates
		 * @deprecated since 4.4, also you can use the same as VcTemplateManager::getTemplateList
		 * @return string
		 */
		public function getList() {
			global $current_user;
			get_currentuserinfo();
			$current_user_role = isset( $current_user->roles[0] ) ? $current_user->roles[0] : false;
			$output = '';
			$is_empty = true;
			$templates = get_posts( array(
				'post_type' => self::$post_type,
				'numberposts' => - 1
			) );
			$post = get_post( isset( $_POST['post_id'] ) ? $_POST['post_id'] : null );
			foreach ( $templates as $template ) {
				$id = $template->ID;
				$meta_data = get_post_meta( $id, self::$meta_data_name, true );
				$post_types = isset( $meta_data['post_type'] ) ? $meta_data['post_type'] : false;
				$user_roles = isset( $meta_data['user_role'] ) ? $meta_data['user_role'] : false;
				if (
					( ! $post_types || in_array( $post->post_type, $post_types ) )
					&& ( ! $user_roles || in_array( $current_user_role, $user_roles ) )
				) {
					$name = $template->post_title;
					$output .= $this->getRow( $id, $name );
					$is_empty = false;
				}
			}
			if ( $is_empty ) {
				$output .= '<li class="wpb_no_templates"><span>' . __( 'No custom templates yet.', "templatera" ) . '</span></li>';
			}

			return $output;
		}

		/**
		 * Builds templates menu on navigation bar of the Visual Composer
		 * @deprecated since 4.4 we use new panel window
		 *
		 * @param bool $list_only
		 *
		 * @return string
		 */
		public function getTemplateMenu( $list_only = false ) {
			wp_enqueue_script( 'vc_plugin_templates' );
			$output = '';
			if ( ! $list_only ) {
				$output .= '<li><ul><li class="nav-header">' . __( 'Save', "templatera" ) . '</li>
                        <li id="templatera_save_button"><a href="#">' . __( 'Save current page as a Template', "templatera" ) . '</a></li>
                        <li class="divider"></li>
                        <li class="nav-header">' . __( 'Load Template', "templatera" ) . '</li>
                        </ul></li>
                        <li>
                        <ul class="wpb_templates_list" data-vc-template="list">';
			}
			$output .= $this->getList();
			if ( ! $list_only ) {
				$output .= '</ul></li>';
			}

			return $output;

		}

		/**
		 * Get template path
		 * @deprecated since 4.4 we use new panel window and no more need for this
		 *
		 * @param $name - template name
		 *
		 * @return string
		 */
		function template( $name ) {
			return $this->dir . '/templates/' . $name . '.' . $this->settings['template_extension'];
		}

		/**
		 * Load template
		 * @deprecated since 4.4 we use new panel window and no more needed to override template
		 *
		 * @param $template - get template path.
		 * */
		function render( $template ) {
			$template = $this->template( $template );
			require $template;
		}

		/**
		 * @deprecated and will be removed, since 4.4 it is deprecated, because we updated panel to new panel window
		 */
		public function renderEditorTemplate() {
			$this->render( $this->isNewVcVersion() ? 'editor4.2' : 'editor' );
		}

		/**
		 * Get template content for backend.
		 * @deprecated since 4.4 we use VcTemplateManager::renderBackendTemplate and VcTemplateManager::renderFrontendTemplate
		 */
		public function load() {
			$post = ! empty( $_POST['template_id'] ) ? get_post( $_POST['template_id'] ) : false;
			if ( ! $post || $post->post_type !== self::$post_type ) {
				die( '' );
			}
			echo $post->post_content;
			die();
		}

		/**
		 * Get template content for frontend.
		 * @deprecated since 4.4 we use VcTemplateManager::renderBackendTemplate and VcTemplateManager::renderFrontendTemplate
		 */
		public function loadInline() {
			$post = ! empty( $_POST['template_id'] ) ? get_post( $_POST['template_id'] ) : false;
			if ( ! $post || $post->post_type !== self::$post_type ) {
				die();
			}
			set_vc_is_inline();
			vc_frontend_editor()->enqueueRequired();
			vc_frontend_editor()->setTemplateContent( $post->post_content );
			vc_frontend_editor()->render( 'template' );
			die();
		}

		/**
		 * Create templates button on navigation bar of the Visual Composer
		 * @deprecated since 4.4, this is old version of buttons, since 4.2 we use new to create it, see more in VcTemplateManager::createButtonFrontBack
		 *
		 * @param $buttons
		 *
		 * @return array
		 */
		public function createButton( $buttons ) {
			$new_buttons = array();
			foreach ( $buttons as $button ) {
				if ( $button[0] != 'templates' ) {
					$new_buttons[] = $button;
				} else {
					if ( get_post_type() == self::$post_type ) {

					} else {
						$new_buttons[] = array(
							'custom_templates',
							'<ul class="vc_nav">
                                <li class="vc_dropdown">
                                    <a class="wpb_templates button"><i class="icon"></i>' . __( 'Templates', "templatera" ) . ' <b class="caret"></b></a>
                                    <ul class="vc_dropdown-menu wpb_templates_ul">
                                        ' . $this->getTemplateMenu() . '
                                    </ul>
                                </li>
                            </ul>'
						);
					}

				}
			}

			return $new_buttons;
		}

		/**
		 * Returns one template representation row.
		 * @deprecated since 4.4 we use new panel window and no more needed this, for controls see VcTemplateManager::renderTemplatePanelWindowTemplateraTemplates
		 *
		 * @param $id
		 * @param $name
		 *
		 * @return string
		 */
		protected function getRow( $id, $name ) {
			return '<li class="wpb_template_li"><a class="vct_template-title" data-templatera_id="' . $id . '" href="#">' . $name . '</a>'
			       . '<span class="wpb_remove_template" rel="' . $id . '" title="' . __( 'Delete template', 'templatera' ) . '"><i class="icon wpb_template_delete_icon"> </i></span>'
			       . '<a href="' . htmlspecialchars( admin_url( 'post.php?post=' . $id . '&action=edit' ) ) . '" target="_blank" class="wpb_edit_template" title="' . __( 'Edit template', 'templatera' ) . '"><i class="icon wpb_template_edit_icon"> </i></a></li>';
		}

		/**
		 * Saves new template.
		 * @deprecated since 4.4 and will be removed, use savePanel
		 */
		public function save() {
			$title = vc_post_param( 'title' );
			$content = vc_post_param( 'content' );
			$this->create( $title, $content );
			echo $this->getTemplateMenu( true );
			die();
		}
	}
}