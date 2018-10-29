<?php
if ( ! function_exists( 'add_action' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

class ThemeMove_Export {

	function ThemeMove_Export() {
		add_action( 'admin_menu', array( &$this, 'thememove_admin_export' ) );
	}

	function init_thememove_export() {
		if ( isset( $_REQUEST['export_option'] ) ) {
			$export_option = $_REQUEST['export_option'];
			if ( $export_option == 'widgets' ) {
				$this->export_widgets_sidebars();
			} elseif ( $export_option == 'customizer_options' ) {
				$this->export_customizer_options();
			} elseif ( $export_option == 'thememove_options' ) {
				$this->export_options();
			} elseif ( $export_option == 'thememove_menus' ) {
				$this->export_thememove_menus();
			} elseif ( $export_option == 'page_options' ) {
				$this->export_page_options();
			} elseif ( $export_option == 'essential_grid' ) {
				$this->export_essential_grid();
			}
		}
	}

	public function export_customizer_options() {
		$mods = get_theme_mods();
		unset( $mods['nav_menu_locations'] );

		$output = base64_encode( serialize( $mods ) );
		$this->save_as_txt_file( "customizer_options.txt", $output );
	}

	public function export_options() {
		$thememove_options = get_option( "thememove_options_proya" );
		$output            = base64_encode( serialize( $thememove_options ) );
		$this->save_as_txt_file( "options.txt", $output );
	}

	public function export_widgets_sidebars() {
		$this->data             = array();
		$this->data['sidebars'] = $this->export_sidebars();
		$this->data['widgets']  = $this->export_widgets();

		$output = base64_encode( serialize( $this->data ) );
		$this->save_as_txt_file( "widgets.txt", $output );
	}

	public function export_widgets() {

		global $wp_registered_widgets;
		$all_thememove_widgets = array();

		foreach ( $wp_registered_widgets as $thememove_widget_id => $widget_params ) {
			$all_thememove_widgets[] = $widget_params['callback'][0]->id_base;
		}

		foreach ( $all_thememove_widgets as $thememove_widget_id ) {
			$thememove_widget_data = get_option( 'widget_' . $thememove_widget_id );
			if ( ! empty( $thememove_widget_data ) ) {
				$widget_datas[ $thememove_widget_id ] = $thememove_widget_data;
			}
		}
		unset( $all_thememove_widgets );

		return $widget_datas;

	}

	public function export_sidebars() {
		$thememove_sidebars = get_option( "sidebars_widgets" );
		$thememove_sidebars = $this->exclude_sidebar_keys( $thememove_sidebars );

		return $thememove_sidebars;
	}

	private function exclude_sidebar_keys( $keys = array() ) {
		if ( ! is_array( $keys ) ) {
			return $keys;
		}

		unset( $keys['wp_inactive_widgets'] );
		unset( $keys['array_version'] );

		return $keys;
	}

	public function export_thememove_menus() {
		global $wpdb;

		$this->data = array();
		$locations  = get_nav_menu_locations();

		$terms_table = $wpdb->prefix . "terms";
		foreach ( (array) $locations as $location => $menu_id ) {
			$menu_slug = $wpdb->get_results( "SELECT * FROM $terms_table where term_id={$menu_id}", ARRAY_A );
			if ( ! empty( $menu_slug ) ) {
				$this->data[ $location ] = $menu_slug[0]['slug'];
			}
		}

		$output = base64_encode( serialize( $this->data ) );
		$this->save_as_txt_file( "menus.txt", $output );
	}

	public function export_page_options() {
		$thememove_static_page    = get_option( "page_on_front" );
		$thememove_post_page      = get_option( "page_for_posts" );
		$thememove_show_on_front  = get_option( "show_on_front" );
		$thememove_settings_pages = array(
			'show_on_front'  => $thememove_show_on_front,
			'page_on_front'  => $thememove_static_page,
			'page_for_posts' => $thememove_post_page
		);
		$output                   = base64_encode( serialize( $thememove_settings_pages ) );
		$this->save_as_txt_file( "page_options.txt", $output );
	}

	public function export_essential_grid() {
		require_once( ABSPATH . 'wp-content/plugins/essential-grid/essential-grid.php' );

		$c_grid = new Essential_Grid();

		$export_grids = array();
		$grids        = $c_grid->get_essential_grids();
		foreach ( $grids as $grid ) {
			$export_grids[] = $grid->id;
		}

		$export_skins = array();
		$item_skin    = new Essential_Grid_Item_Skin();
		$skins        = $item_skin->get_essential_item_skins( 'all', false );
		foreach ( $skins as $skin ) {
			$export_grids[] = $skin['id'];
		}

		$export_elements = array();
		$c_elements      = new Essential_Grid_Item_Element();
		$elements        = $c_elements->get_essential_item_elements();
		foreach ( $elements as $element ) {
			$export_elements[] = $element['id'];
		}

		$export_navigation_skins = array();
		$c_nav_skins             = new Essential_Grid_Navigation();
		$nav_skins               = $c_nav_skins->get_essential_navigation_skins();
		foreach ( $nav_skins as $nav_skin ) {
			$export_navigation_skins[] = $nav_skin['id'];
		}


		$export_custom_meta = array();
		$metas              = new Essential_Grid_Meta();
		$custom_metas       = $metas->get_all_meta();
		foreach ( $custom_metas as $custom_meta ) {
			$export_custom_meta[] = $custom_meta['handle'];
		}

		$export_punch_fonts = array();
		$fonts              = new ThemePunch_Fonts();
		$custom_fonts       = $fonts->get_all_fonts();
		foreach ( $custom_fonts as $custom_font ) {
			$export_punch_fonts[] = $custom_font['handle'];
		}

		$export = array();

		$ex = new Essential_Grid_Export();

		//export Grids
		if ( ! empty( $export_grids ) ) {
			$export['grids'] = $ex->export_grids( $export_grids );
		}

		//export Skins
		if ( ! empty( $export_skins ) ) {
			$export['skins'] = $ex->export_skins( $export_skins );
		}

		//export Elements
		if ( ! empty( $export_elements ) ) {
			$export['elements'] = $ex->export_elements( $export_elements );
		}

		//export Navigation Skins
		if ( ! empty( $export_navigation_skins ) ) {
			$export['navigation-skins'] = $ex->export_navigation_skins( $export_navigation_skins );
		}

		//export Custom Meta
		if ( ! empty( $export_custom_meta ) ) {
			$export['custom-meta'] = $ex->export_custom_meta( $export_custom_meta );
		}

		//export Punch Fonts
		if ( ! empty( $export_punch_fonts ) ) {
			$export['punch-fonts'] = $ex->export_punch_fonts( $export_punch_fonts );
		}

		//export Global Styles
		$export['global-css'] = $ex->export_global_styles( 'on' );

		$this->save_as_txt_file( 'essential_grid.txt', json_encode( $export ) );
	}

	function save_as_txt_file( $file_name, $output ) {
		header( "Content-type: application/text", true, 200 );
		header( "Content-Disposition: attachment; filename=$file_name" );
		header( "Pragma: no-cache" );
		header( "Expires: 0" );
		echo $output;
		exit;
	}

	function thememove_admin_export() {
		if ( isset( $_REQUEST['export'] ) ) {
			$this->init_thememove_export();
		}

		add_menu_page( 'ThemeMove Theme', esc_html__( 'ThemeMove Export', 'thememove' ), 'manage_options', 'thememove_options_export_page', array(
			&$this,
			'thememove_generate_export_page'
		) );
	}

	function thememove_generate_export_page() {

		?>
		<div class="wrapper">
			<div class="content">
				<table class="form-table">
					<tbody>
					<tr>
						<td scope="row" width="150"><h2><?php esc_html_e( 'Export', 'thememove' ); ?></h2></td>
					</tr>
					<tr valign="middle">

						<td>
							<form method="post" action="">
								<input type="hidden" name="export_option" value="widgets"/>
								<input type="submit" value="Export Widgets" name="export"/>
							</form>
							<br/>

							<form method="post" action="">
								<input type="hidden" name="export_option" value="thememove_menus"/>
								<input type="submit" value="Export Menus" name="export"/>
							</form>
							<br/>

							<form method="post" action="">
								<input type="hidden" name="export_option" value="page_options"/>
								<input type="submit" value="Export Page Options" name="export"/>
							</form>
							<br/>

							<form method="post" action="">
								<input type="hidden" name="export_option" value="customizer_options"/>
								<input type="submit" value="Export Customizer Options" name="export"/>
							</form>
							<br/>

							<p>
								Essential Grid Export: You have go to <a
									href="<?php echo admin_url( 'admin.php?page=essential-grid-import-export' ); ?>">Essential
									Grid Export page</a>, exports customized components and save to <b>{your_theme}/inc/import/files/{demo}/.
									NOTE: Change file extension to txt.</b>.
							</p>
							<br/>

							<p>
								Revolution Slider Export: You have go to <a
									href="<?php echo admin_url( 'admin.php?page=revslider&view=sliders' ); ?>">Revolution
									Sliders page</a>, exports each slider and save to <b>{your_theme}/inc/import/files/{demo}/rev_sliders/</b>.
							</p>
						</td>
					</tr>
					</tbody>
				</table>
			</div>
		</div>

	<?php }

}

$my_ThemeMove_Export = new ThemeMove_Export();