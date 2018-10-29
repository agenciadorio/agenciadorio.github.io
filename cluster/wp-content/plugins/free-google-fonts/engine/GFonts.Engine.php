<?php

class GFontsEngine {

	const PLUGIN_OPTION_VERSION					 = 'gfonts_version';
	const PLUGIN_OPTION_FONTLIST					 = 'gfonts_list';
	const PLUGIN_OPTION_FONT_UPDATE_STATE			 = 'gfonts_update_state';
	const PLUGIN_OPTION_FONT_UPDATE_STATE_MESSAGE	 = 'gfonts_update_state_msg';
	const PLUGIN_OPTION_FONT_UPDATE_DATE			 = 'gfonts_update_date';
	const PLUGIN_OPTION_FONT_DATABASE				 = 'gfonts_fontlist';
	const PLUGIN_OPTION_FONT_SIZE_ENABLED			 = 'gfonts_fontsizes';
	const PLUGIN_OPTION_FONT_SIZE_MINIMUM			 = 'gfonts_fontsizes_min';
	const PLUGIN_OPTION_FONT_SIZE_MAXIMUM			 = 'gfonts_fontsizes_max';
	const PLUGIN_ACTION_PREVIEW					 = 'gfonts_preview';
	const PLUGIN_ACTION_PREVIEW_FOR_ALL			 = 'gfonts_preview_for_all';
	const PLUGIN_ACTION_INSTALL_FONT				 = 'gfonts_install_font';
	const PLUGIN_ACTION_UNINSTALL_FONT			 = 'gfonts_uninstall_font';
	const PLUGIN_META_NO_FONT						 = 'gfonts_meta_no_font';
	const PLUGIN_VERSION							 = '1.2.6';
	const PLUGIN_MENU_NAME						 = 'Google Fonts';
	const PLUGIN_MENU_TITLE						 = 'Google Fonts';
	const PLUGIN_SLUG								 = 'gfonts';
	const PLUGIN_FONT_LIST						 = 'gfonts_list';
	const PLUGIN_FONT_STATS						 = 'gfonts_stats';
	const PLUGIN_FONT_SIZE						 = 'gfonts_sizes';
	const PLUGIN_FULL_VERSION						 = 'gfonts_fv';

	public function Run( $file ) {
		register_activation_hook( $file, array( 'GFontsEngine', 'InstallHook' ) );
		register_uninstall_hook( $file, array( 'GFontsEngine', 'UninstallHook' ) );
		add_action( 'admin_menu', array( 'GFontsEngine', 'AddMenuItem' ) );
		add_filter( 'mce_buttons', array( 'GFontsEngine', 'ManageFontSelection' ), 9000 );
		add_filter( 'tiny_mce_before_init', array( 'GFontsEngine', 'ManageTinyMceFonts' ), 9000 );
		add_action( 'admin_init', array( 'GFontsEngine', 'RegisterSettings' ) );
		add_action( 'wp_print_styles', array( 'GFontsEngine', 'IncludeCssForFonts' ) );
		add_filter( 'plugin_action_links_' . GFONTS_PLUGIN_BASENAME, array( 'GFontsEngine', 'AddSettingLinkToPlugin' ), 10, 2 );
		add_action( 'wp_ajax_' . GFontsEngine::PLUGIN_ACTION_PREVIEW, array( 'GFontsEngine', 'ShowPreview' ) );
		add_action( 'wp_ajax_' . GFontsEngine::PLUGIN_ACTION_PREVIEW_FOR_ALL, array( 'GFontsEngine', 'ShowPreviewForAll' ) );
		add_action( 'wp_ajax_' . GFontsEngine::PLUGIN_ACTION_INSTALL_FONT, array( 'GFontsEngine', 'InstallFont' ) );
		add_action( 'wp_ajax_' . GFontsEngine::PLUGIN_ACTION_UNINSTALL_FONT, array( 'GFontsEngine', 'UninstallFont' ) );
		add_filter( 'posts_where', array( 'GFontsEngine', 'FilterPostsByFont' ) );
		add_action( 'pre_get_posts', array( 'GFontsEngine', 'FilterPostsByNoFont' ), 10000 );
		add_action( 'restrict_manage_posts', array( 'GFontsEngine', 'AddFiltersToPostList' ) );
		add_action( 'load-edit.php', array( 'GFontsEngine', 'AddHelpToEditPhp' ), 20 );
		add_filter( 'content_save_pre', array( 'GFontsEngine', 'ContentSave' ), 10000, 1 );
		add_action( 'before_delete_post', array( 'GFontsEngine', 'BeforeDelete' ) );
		add_action( 'trashed_post', array( 'GFontsEngine', 'TrashedPost' ) );
		add_action( 'untrashed_post', array( 'GFontsEngine', 'UntrashedPost' ) );
		add_filter( 'cron_schedules', array( 'GFontsEngine', 'GSchedule' ) );
		add_action( 'gfonts_cron', array( 'GFontsEngine', 'CronHook' ) );
	}

	static public function RegisterSettings() {
		register_setting( GFontsEngine::PLUGIN_SLUG, GFontsEngine::PLUGIN_OPTION_FONT_SIZE_ENABLED );
		register_setting( GFontsEngine::PLUGIN_SLUG, GFontsEngine::PLUGIN_OPTION_FONT_SIZE_MINIMUM );
		register_setting( GFontsEngine::PLUGIN_SLUG, GFontsEngine::PLUGIN_OPTION_FONT_SIZE_MAXIMUM );
	}

	static public function AddSettingLinkToPlugin( $links, $file ) {
		$settings_link = '<a href="options-general.php?page=' . GFontsEngine::PLUGIN_SLUG . '">' . __( 'Settings' ) . '</a>';
		array_unshift( $links, $settings_link ); // before other links
		return $links;
	}

	static public function InstallHook() {
		$version = get_option( self::PLUGIN_OPTION_VERSION );
		GFontsDB::InstallDB();
		GFontsEngine::InstallFonts();
		if ( -1 === version_compare( $version, self::PLUGIN_VERSION ) ) {
			update_option( GFontsEngine::PLUGIN_OPTION_VERSION, GFontsEngine::PLUGIN_VERSION );
			GFontsDB::RecalculateStats();
		}
		GFontsEngine::InstallFonts();
		update_option( GFontsEngine::PLUGIN_OPTION_FONT_SIZE_ENABLED, true );
		update_option( GFontsEngine::PLUGIN_OPTION_FONT_SIZE_MINIMUM, 6 );
		update_option( GFontsEngine::PLUGIN_OPTION_FONT_SIZE_MAXIMUM, 48 );
		wp_schedule_event( time(), 'weekly', 'gfonts_cron' );
	}

	static public function UninstallHook() {
		delete_option( GFontsEngine::PLUGIN_OPTION_VERSION );
		GFontsDB::UninstallDB();
		wp_clear_scheduled_hook( 'gfonts_cron' );
	}

	static public function AddMenuItem() {
		$mp = add_menu_page(
			GFontsEngine::PLUGIN_MENU_TITLE, GFontsEngine::PLUGIN_MENU_NAME, 'manage_options', GFontsEngine::PLUGIN_FULL_VERSION, array( 'GFontsEngine', 'FullVersion' )
		);
		add_submenu_page(
			GFontsEngine::PLUGIN_FULL_VERSION, __( '11 Social Sliders', GFontsEngine::PLUGIN_SLUG ), __( '11 Social Sliders', GFontsEngine::PLUGIN_SLUG ), 'manage_options', GFontsEngine::PLUGIN_FULL_VERSION, array( 'GFontsEngine', 'FullVersion' )
		);
		add_submenu_page(
			GFontsEngine::PLUGIN_FULL_VERSION, __( 'Install / Uninstall', GFontsEngine::PLUGIN_SLUG ), __( 'Install / Uninstall', GFontsEngine::PLUGIN_SLUG ), 'manage_options', GFontsEngine::PLUGIN_SLUG, array( 'GFontsEngine', 'MainOptions' )
		);
		add_submenu_page(
			GFontsEngine::PLUGIN_FULL_VERSION, __( 'Your fonts', GFontsEngine::PLUGIN_SLUG ), __( 'Your fonts', GFontsEngine::PLUGIN_SLUG ), 'manage_options', GFontsEngine::PLUGIN_FONT_LIST, array( 'GFontsEngine', 'FontList' )
		);
		add_submenu_page(
			GFontsEngine::PLUGIN_FULL_VERSION, __( 'Statistics & Tools', GFontsEngine::PLUGIN_SLUG ), __( 'Statistics & Tools', GFontsEngine::PLUGIN_SLUG ), 'manage_options', GFontsEngine::PLUGIN_FONT_STATS, array( 'GFontsEngine', 'FontStats' )
		);
		add_submenu_page(
			GFontsEngine::PLUGIN_FULL_VERSION, __( 'Extra font sizes', GFontsEngine::PLUGIN_SLUG ), __( 'Extra font sizes', GFontsEngine::PLUGIN_SLUG ), 'manage_options', GFontsEngine::PLUGIN_FONT_SIZE, array( 'GFontsEngine', 'FontSize' )
		);
	}

	static public function MainOptions() {
		if ( isset( $_GET['act'] ) && $_GET['act'] == 'update' ) {
			GFontsEngine::InstallFonts();
		}
		print "<div class='wrap'>";
		//screen_icon();
		print "<h2>" . __( 'Google Fonts Options', GFontsEngine::PLUGIN_SLUG ) . "</h2>";
		print "<form method=\"post\" action=\"options.php\"> ";
		settings_fields( GFontsEngine::PLUGIN_SLUG );
		do_settings_fields( GFontsEngine::PLUGIN_SLUG, '' );
		?>
		<?php
		$upstatus	 = ( bool ) get_option( GFontsEngine::PLUGIN_OPTION_FONT_UPDATE_STATE );
		$txt		 = __( 'Last font update' ) . "&nbsp; " . get_option( GFontsEngine::PLUGIN_OPTION_FONT_UPDATE_DATE ) . "&nbsp;finished with status: "
			. ($upstatus ? __( 'Success', GFontsEngine::PLUGIN_SLUG ) : __( 'Error', GFontsEngine::PLUGIN_SLUG ))
			. "<br/>" . sprintf( __( 'There is %s available fonts', GFontsEngine::PLUGIN_SLUG ), GFontsEngine::AvailableFontCount() )
			. "<br/>"
			. "<a href=\"admin.php?page=" . GFontsEngine::PLUGIN_SLUG . "&act=update\">" . __( 'Update now!', GFontsEngine::PLUGIN_SLUG ) . "</a>";
		GFontsUI::Notice( $txt );
		?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><b><?php
						print __( "Available Google Fonts", GFontsEngine::PLUGIN_SLUG );
						?></b></th>
				<!--<th scope="row"><b><?php
				_e( "Variants for selected font", GFontsEngine::PLUGIN_SLUG )
				?></b></th>
				<th scope="row"><b><?php
				_e( "Character sets for selected font", GFontsEngine::PLUGIN_SLUG )
				?></b></th>-->
			</tr>
			<tr valign="top">
				<td><select name="<?php echo GFontsEngine::PLUGIN_OPTION_FONTLIST; ?>" id="<?php echo GFontsEngine::PLUGIN_OPTION_FONTLIST; ?>" style="width: 350px;"><?php echo GFontsEngine::AvailableFontListAsHtmlOptions() ?></select></td>

																																																				<!--<td>
																																																				<select multiple size="8" name="variants" id="variants" style="width: 350px;">

																																																				</select>
																																																				</td>
																																																				<td>
																																																				<select multiple size="4" name="subsets" id="subsets" style="width: 350px;">

																																																				</select>
																																																				</td>-->
			</tr>

		</table>
		</form>
		<div style="margin-top: 20px;">
			<button class="button button-primary" onclick="return ShowPreview();"><?php
				_e( 'Show example', GFontsEngine::PLUGIN_SLUG );
				?></button>&nbsp;&nbsp;
			<button class="button button-primary" onclick="return ShowPreviewForAll(1);"><?php
				_e( 'Show all fonts', GFontsEngine::PLUGIN_SLUG );
				?></button>&nbsp;&nbsp;
			<?php _e( 'Preview font size', GFontsEngine::PLUGIN_SLUG ); ?>&nbsp;<input type="text" id="preview_fontsize" value="18" />&nbsp;px&nbsp;&nbsp;
			<?php _e( 'Preview text', GFontsEngine::PLUGIN_SLUG ); ?>&nbsp;<input type="text" id="preview_text" value="Grumpy wizards make toxic brew for the evil Queen and Jack" style="width: 500px;"/>
			<br/><br/>
			<input type="checkbox" name="gf_autopreview" id="gf_autopreview" checked="true"/><label for="gf_autopreview"><?php
				_e( 'Auto preview', GFontsEngine::PLUGIN_SLUG )
				?></label>
			<br/><br/>
			<div id="example_area" style="display: none; margin: 15px;">

			</div>
			<div id="loader_area" style="display: none;">
				<center>
					<img src="<?php print GFONTS_PLUGIN_URL . 'assets/loader.gif' ?>" />
					<br/>
					<?php _e( 'Please wait for preview...', GFontsEngine::PLUGIN_SLUG );
					?>
				</center>
			</div>
		</div>
		<?php
		//submit_button(__("Update settings"));

		GFontsEngine::PrintVariantsAndCharactersAsJavascript();
		print '<script type="text/javascript" src="' . GFONTS_PLUGIN_URL . 'js/backend.js"></script>';
	}

	static public function ManageFontSelection( $btns ) {
		array_push( $btns, 'fontselect' );
		array_push( $btns, 'fontsizeselect' );
		return $btns;
	}

	static public function ManageTinyMceFonts( $config ) {
		$extraFonts		 = GFontsDB::GetInstalledFonts();
		$additionalFonts = '';
		$contentCssArray = array();
		$contentCss		 = null;
		$families		 = array();
		$variants		 = array();
		if ( $extraFonts ) {
			foreach ( $extraFonts as $item ) {
				$fname = sprintf( "%s %s", $item->name, GFontsEngine::TranslateVariantName( $item->variant ) );
				//$additionalFonts .= sprintf('%s=%s, sans-serif;font-weight: %s; font-style: %s;', $fname, $item->name, GFontsEngine::TranslateVariantNameToWeight($item->variant), GFontsEngine::TranslateVariantToFontStyle($item->variant));
				if ( !in_array( $item->name, $families ) ) {
					$families[] = $item->name;
					$additionalFonts .= sprintf( '%s=\'%s\', sans-serif;', $item->name, $item->name ); //, GFontsEngine::TranslateVariantNameToWeight($item->variant), GFontsEngine::TranslateVariantToFontStyle($item->variant));
				}
				$variants[$item->name][] = $item->variant;
			}
			foreach ( $families as $family ) {
				foreach ( $variants[$family] as $variant ) {
					$css				 = sprintf( '//fonts.googleapis.com/css?family=%s:%s', str_replace( ' ', '+', $family ), rawurlencode( $variant ) );
					$css2				 = sprintf( '//fonts.googleapis.com/css?family=%s:%s', str_replace( ' ', '+', $family ), $variant );
					$contentCssArray[]	 = $css;
					wp_enqueue_style( 'gf-css-' . uniqid(), $css2 );
				}
			}
		}
		$contentCss = implode( ",", $contentCssArray );

		$additionalFonts .= 'Andale Mono=Andale Mono, Times;';
		$additionalFonts .= 'Arial=Arial, Helvetica, sans-serif;';
		$additionalFonts .= 'Arial Black=Arial Black, Avant Garde;';
		$additionalFonts .= 'Book Antiqua=Book Antiqua, Palatino;';
		$additionalFonts .= 'Calibri=Calibri, sans-serif;';
		$additionalFonts .= 'Comic Sans MS=Comic Sans MS, sans-serif;';
		$additionalFonts .= 'Courier New=Courier New, Courier;';
		$additionalFonts .= 'Georgia=Georgia, Palatino;';
		$additionalFonts .= 'Helvetica=Helvetica;';
		$additionalFonts .= 'Impact=Impact, Chicago;';
		$additionalFonts .= 'Symbol=Symbol;';
		$additionalFonts .= 'Tahoma=Tahoma, Arial, Helvetica, sans-serif;';
		$additionalFonts .= 'Terminal=Terminal, Monaco;';
		$additionalFonts .= 'Times New Roman=Times New Roman, Times;';
		$additionalFonts .= 'Trebuchet MS=Trebuchet MS, Geneva;';
		$additionalFonts .= 'Verdana=Verdana, Geneva;';
		$additionalFonts .= 'Webdings=Webdings;';
		$additionalFonts .= 'Wingdings=Wingdings, Zapf Dingbats';

		$config['editor_selector']		 = 'tinymce-textarea';
		$config['theme_advanced_fonts']	 = $additionalFonts;
		$config['font_formats']			 = $additionalFonts;
		$config['content_css'] .= $contentCss;

		if ( strpos( $config['theme_advanced_buttons1'], 'fontsizeselect' ) === false ) {
			$config['theme_advanced_buttons1'] .= ',fontsizeselect';
		}
		if ( strpos( $config['theme_advanced_buttons1'], 'fontselect' ) === false ) {
			$config['theme_advanced_buttons1'] .= ',fontselect';
		}

		if ( get_option( GFontsEngine::PLUGIN_OPTION_FONT_SIZE_ENABLED ) == true ) {
			$config['theme_advanced_buttons1']	 = $config['theme_advanced_buttons1'] . ',fontsizeselect';
			$config['theme_advanced_font_sizes'] = GFontsEngine::BuildFontSizes();
			$config['fontsize_formats']			 = str_replace(
				',', ' ', GFontsEngine::BuildFontSizes()
			);
		}
		return $config;
	}

	static public function IncludeCssForFonts( $output = false ) {
		$extraFonts = GFontsDB::GetInstalledFonts();
		if ( $extraFonts ) {
			$list	 = $extraFonts;
			$fonts	 = array();
			$subsets = array();
			foreach ( $list as $item ) {
				if ( (trim( $item->name ) != '' ) ) {
					$fonts[] = str_replace( ' ', '+', trim( $item->name ) ) . ':' . str_replace( 'regular', '400', $item->variant );
					$subset	 = explode( ",", $item->subsets );
					foreach ( $subset as $sub ) {
						if ( trim( $sub ) != '' && !in_array( $sub, $subsets ) ) {
							$subsets[] = $sub;
						}
					}
				}
			}
			$lnk = '//fonts.googleapis.com/css?family=' . implode( "|", $fonts ) . '&subset=' . implode( ",", $subsets );
			if ( !$output ) {
				//wp_register_style('googleWebFonts', '//fonts.googleapis.com/css?family=' . implode("|", $fonts));
				add_filter( 'style_loader_tag', array( 'GFontsEngine', 'gf_url_filter' ), 1000, 2 );

				wp_enqueue_style( 'googleWebFonts', $lnk, null, '2' );
				remove_filter( 'style_loader_tag', array( 'GFontsEngine', 'gf_url_filter' ) );
			} else {
				print "<link href='" . $lnk . "' rel='stylesheet' type='text/css'>";
			}
		}
	}

	static public function gf_url_filter( $tag = null, $handle = null ) {
		if ( $handle == 'googleWebFonts' ) {
			$tag = str_replace( '#038;', '', $tag );
			$tag = str_replace( '%3A', ':', $tag );
			$tag = str_replace( '%2C', ',', $tag );
			$tag = str_replace( '%7C', '|', $tag );
		}
		return $tag;
	}

	static public function InstallFonts() {
		$content = wp_remote_get( 'http://cdn.pxe.pl/fonts/fonts.json' );
		$error	 = is_wp_error( $content );
		if ( $error ) {
			update_option( GFontsEngine::PLUGIN_OPTION_FONT_UPDATE_STATE, -1 );
			update_option( GFontsEngine::PLUGIN_OPTION_FONT_UPDATE_STATE_MESSAGE, curl_error( $curlClient ) );
			update_option( GFontsEngine::PLUGIN_OPTION_FONT_UPDATE_DATE, date( "Y-m-d H:i:s" ) );
		} else {
			$json = json_decode( $content['body'] );
			if ( isset( $json->kind ) && $json->kind == 'webfonts#webfontList' ) {
				$fontsArray = array();
				foreach ( $json->items as $item ) {
					if ( isset( $item->kind ) && $item->kind == 'webfonts#webfont' ) {
						$fontItem			 = array();
						$fontItem['name']	 = $item->family;
						foreach ( $item->variants as $variant ) {
							$fontItem['variants'][] = $variant;
						}
						foreach ( $item->subsets as $subset ) {
							$fontItem['subsets'][] = $subset;
						}
						$fontsArray[] = $fontItem;
					}
					foreach ( $fontItem['variants'] as $_variant ) {
						GFontsDB::InstallFont(
							$item->family, $_variant, implode( ',', $fontItem['subsets'] ), true
						);
					}
				}

				update_option( GFontsEngine::PLUGIN_OPTION_FONT_DATABASE, serialize( $fontsArray ) );
				update_option( GFontsEngine::PLUGIN_OPTION_FONT_UPDATE_STATE, 1 );
				update_option( GFontsEngine::PLUGIN_OPTION_FONT_UPDATE_STATE_MESSAGE, null );
				update_option( GFontsEngine::PLUGIN_OPTION_FONT_UPDATE_DATE, date( "Y-m-d H:i:s" ) );
			}
		}
	}

	static public function AvailableFontListAsHtmlOptions() {
		$serializedItems = get_option( GFontsEngine::PLUGIN_OPTION_FONT_DATABASE );
		if ( !$serializedItems ) {
			return null;
		} else {
			$items	 = unserialize( $serializedItems );
			$options = array();
			if ( is_array( $items ) ) {
				foreach ( $items as $item ) {
					$options[] = sprintf( '<option value="%s">%s</option>', $item['name'], $item['name'] );
				}
			}

			return implode( "\n", $options );
		}
	}

	static public function AvailableFontCount() {
		$serializedItems = get_option( GFontsEngine::PLUGIN_OPTION_FONT_DATABASE );
		if ( !$serializedItems ) {
			return 0;
		} else {
			return count( unserialize( $serializedItems ) );
		}
	}

	static public function PrintVariantsAndCharactersAsJavascript() {
		$serializedItems = get_option( GFontsEngine::PLUGIN_OPTION_FONT_DATABASE );
		if ( $serializedItems ) {
			print '<script type="text/javascript">' . "\n";
			print 'var gFontsVariants = new Array();' . "\n";
			print 'var gFontsSubsets = new Array();' . "\n";
			$items = unserialize( $serializedItems );
			foreach ( $items as $item ) {
				$variants	 = $item['variants'];
				$subsets	 = $item['subsets'];
				$v			 = array();
				$s			 = array();
				foreach ( $variants as $var ) {
					$v[] = "'" . $var . "'";
				}
				foreach ( $subsets as $subset ) {
					$s[] = "'" . $subset . "'";
				}
				print "gFontsVariants['" . $item['name'] . "'] = new Array(" . implode( ",", $v ) . ");\n";
				print "gFontsSubsets['" . $item['name'] . "'] = new Array(" . implode( ",", $s ) . ");\n";
			}
			print '</script>';
		}
	}

	static public function ShowPreview() {
		$serializedItems = get_option( GFontsEngine::PLUGIN_OPTION_FONT_DATABASE );
		if ( !$serializedItems ) {
			echo '<span style="color: red;">' . __( 'Could not find font list. Consider updating database?' ) . '</span>';
			die();
		} else {
			$items = unserialize( $serializedItems );
			foreach ( $items as $item ) {
				if ( $item['name'] == $_POST['font'] ) {
					// output of css
					$cssUrl		 = '//fonts.googleapis.com/css?family=' . str_replace( ' ', '+', $item['name'] );
					$cssUrl .= ':';
					$variants	 = array( 'regular' ); //$item['variants'];
					$cssUrl .= implode( ",", $variants );
					$subsets	 = $item['subsets'];
					if ( count( $subsets ) > 1 ) {
						$cssUrl .= '&subset=' . implode( ',', $subsets );
					}
					print '<style type="text/css">
/* <![CDATA[ */
@import url(' . $cssUrl . ');
/* ]]> */
</style>';
					$fontsize = ( int ) $_POST['size'];
					if ( $fontsize == 0 ) {
						$fontsize = 18;
					}
					$text			 = (isset( $_POST['text'] )) ? $_POST['text'] : 'Grumpy wizards make toxic brew for the evil Queen and Jack';
					$installedFonts	 = GFontsDB::GetInstalledFonts();
					foreach ( $variants as $variant ) {
						$class = GFontsEngine::TranslateVariantToCSS( $item['name'], $variant );
						printf( '<div style="padding-bottom: 5px; font-size: %spx; %s">%s - %s</div>%s<br/><br/>', $fontsize, $class, $variant, $text, GFontsEngine::CheckIsInstalled( $item['name'], $variant, $installedFonts ) );
						printf( '<div style="padding-bottom: 5px; font-size: %spx; %s"><b>%s - %s</b></div><br/>', $fontsize, $class, 'bold', $text );
						printf( '<div style="padding-bottom: 5px; font-size: %spx; %s"><em>%s - %s</em></div><br/>', $fontsize, $class, 'italic', $text );
					}
					die();
				}
			}

			echo '<span style="color: red;">' . __( 'Could not find specified font. Consider updating database?' ) . '</span>';
			die();
		}
	}

	static public function TranslateVariantToCSS( $fontname, $variant ) {
		$css = "font-family: " . $fontname . ';';
		if ( strpos( $variant, 'italic' ) !== false ) {
			$css .= ' font-style: italic;';
		}

		$v = ( int ) $variant;
		if ( $v == 0 ) {
			$v = 400;
		}
		$css .= ' font-weight: ' . $v . ';';
		return $css;
	}

	static public function TranslateVariantName( $variant ) {
		$name = $variant;
		switch ( $variant ) {
			case '100':
				$name	 = 'Thin 100';
				break;
			case '100italic':
				$name	 = 'Thin 100 Italic';
				break;
			case '200':
				$name	 = 'Extra-Light 200';
				break;
			case '200italic':
				$name	 = 'Extra-Light 200 Italic';
				break;
			case '300':
				$name	 = 'Light 300';
				break;
			case '300italic':
				$name	 = 'Light 300 Italic';
				break;
			case 'regular':
				$name	 = 'Normal 400';
				break;
			case 'italic':
				$name	 = 'Normal 400 Italic';
				break;
			case '500':
				$name	 = 'Medium 500';
				break;
			case '500italic':
				$name	 = 'Medium 500 Italic';
				break;
			case '600':
				$name	 = 'Semi-Bold 600';
				break;
			case '600italic':
				$name	 = 'Semi-Bold 600 Italic';
				break;
			case '700':
				$name	 = 'Bold 700';
				break;
			case '700italic':
				$name	 = 'Bold 700 Italic';
				break;
			case '800':
				$name	 = 'Extra-Bold 800';
				break;
			case '800italic':
				$name	 = 'Extra-Bold 800 Italic';
				break;
			case '900':
				$name	 = 'Ultra-Bold 900';
				break;
			case '900italic':
				$name	 = 'Ultra-Bold 900 Italic';
				break;
		}

		return $name;
	}

	static public function ShowPreviewForAll() {
		$serializedItems = get_option( GFontsEngine::PLUGIN_OPTION_FONT_DATABASE );
		if ( !$serializedItems ) {
			echo '<span style="color: red;">' . __( 'Could not find font list. Consider updating database?' ) . '</span>';
			die();
		} else {
			$items			 = unserialize( $serializedItems );
			$limit			 = 25;
			$page			 = isset( $_POST['page'] ) ? ( int ) $_POST['page'] : 1;
			$offset			 = ($page - 1) * $limit;
			$number			 = $page * $limit;
			$maxpages		 = ceil( ( float ) (count( $items ) / $limit) );
			$index			 = 0;
			print '<input type="hidden" id="gfpage" value="' . $page . '"/>';
			GFontsEngine::ShowPreviewForAllPaginator( $page, $maxpages );
			$installedFonts	 = GFontsDB::GetInstalledFonts();
			foreach ( $items as $item ) {
				// output of css
				if ( ($index < $number) && ($index >= $offset) ) {
					$cssUrl		 = '//fonts.googleapis.com/css?family=' . str_replace( ' ', '+', $item['name'] );
					$cssUrl .= ':';
					$variants	 = array( 'regular' );
					$item['variants'];
					$cssUrl .= implode( ",", $variants );
					$subsets	 = $item['subsets'];
					if ( count( $subsets ) > 1 ) {
						$cssUrl .= '&subset=' . implode( ',', $subsets );
					}
					print '<style type="text/css">
/* <![CDATA[ */
@import url(' . $cssUrl . ');
/* ]]> */
</style>';
					$fontsize = ( int ) $_POST['size'];
					if ( $fontsize == 0 ) {
						$fontsize = 18;
					}
					$text = (isset( $_POST['text'] )) ? $_POST['text'] : 'Grumpy wizards make toxic brew for the evil Queen and Jack';
					foreach ( $variants as $variant ) {
						$class = GFontsEngine::TranslateVariantToCSS( $item['name'], $variant );
						printf( '<div style="padding-bottom: 5px; font-size: %spx; %s">%s - %s</div>%s<br/><br/>', $fontsize, $class, $variant, $text, GFontsEngine::CheckIsInstalled( $item['name'], $variant, $installedFonts ) );
						printf( '<div style="padding-bottom: 5px; font-size: %spx; %s"><b>%s - %s</b></div><br/>', $fontsize, $class, 'bold', $text );
						printf( '<div style="padding-bottom: 5px; font-size: %spx; %s"><em>%s - %s</em></div><br/>', $fontsize, $class, 'italic', $text );
					}

					print '<hr style="margin-top: 10px; margin-bottom: 10px;"/>';
				}
				$index++;
			}
			GFontsEngine::ShowPreviewForAllPaginator( $page, $maxpages );
			die();
		}
	}

	static public function ShowPreviewForAllPaginator( $page, $maxpages ) {
		print '<div style="height: 100px;"><center>';
		printf( __( 'Page <b>%d</b> of <b>%d</b>', GFontsEngine::PLUGIN_SLUG ), $page, $maxpages );
		print '</center>';
		if ( $page > 1 ) {
			$prev = __( 'Previous', GFontsEngine::PLUGIN_SLUG );
			echo '<button class="button button-primary" onclick="return PreviousPage();" style="float: left;">' . $prev . '</button>';
		}

		if ( $page < $maxpages ) {
			$next = __( 'Next', GFontsEngine::PLUGIN_SLUG );
			echo '<button class="button button-primary" onclick="return NextPage();" style="float: right; margin-bottom: 50px;">' . $next . '</button>';
		}
		print '</div>';
	}

	static public function InstallFont() {
		GFontsEngine::InstallOrUninstallFont( true );
	}

	static public function UninstallFont() {
		GFontsEngine::InstallOrUninstallFont( false );
	}

	static public function InstallOrUninstallFont( $install ) {
		$serializedItems = get_option( GFontsEngine::PLUGIN_OPTION_FONT_DATABASE );
		if ( !$serializedItems ) {
			echo '<span style="color: red;">' . __( 'Could not find font list. Consider updating database?' ) . '</span>';
			die();
		} else {
			$fontname = isset( $_POST['name'] ) ? $_POST['name'] : false;
			if ( $fontname === false ) {
				echo '<span style="color: red;">' . __( 'Font name is not set.' ) . '</span>';
				die();
			}
			$items = unserialize( $serializedItems );
			foreach ( $items as $item ) {
				if ( $item['name'] == $fontname ) {
					$variant = isset( $_POST['variant'] ) ? $_POST['variant'] : false;
					if ( $variant === false ) {
						echo '<span style="color: red;">' . __( 'Font variant is not set.' ) . '</span>';
						die();
					} else {
						if ( $install ) {
							$usedin = GFontsDB::InstallFont( $fontname, implode( ",", $item['variants'] ), implode( ",", $item['subsets'] ) );
						} else {
							$usedin = GFontsDB::UninstallFont( $fontname, implode( ",", $item['variants'] ), implode( ",", $item['subsets'] ) );
						}
						$usedtext = "";
						if ( $usedin > 0 ) {
							$usedtext = ' (' . sprintf( _n( 'used in %d post', 'used in %d posts', $usedin, GFontsEngine::PLUGIN_SLUG ), $usedin ) . ')';
						}
						if ( $install ) {
							printf( '<a href="#" onclick="return GfUninstallFont(\'%s\', \'%s\', this);">%s %s %s%s</a>', $fontname, $variant, __( 'Uninstall', GFontsEngine::PLUGIN_SLUG ), $fontname, $variant, $usedtext );
						} else {
							printf( '<a href="#" onclick="return GfInstallFont(\'%s\', \'%s\', this);">%s %s %s%s</a>', $fontname, $variant, __( 'Install', GFontsEngine::PLUGIN_SLUG ), $fontname, $variant, $usedtext );
						}
						die();
					}
				}
			}

			echo '<span style="color: red;">' . __( 'Could not find specified font.' ) . '</span>';
			die();
		}
	}

	static public function CheckIsInstalled( $name, $variant, $installedFonts ) {
		foreach ( $installedFonts as $if ) {
			if ( ($if->name == $name) && ($if->variant == $variant) ) {
				return sprintf( '<span id="%s"><a href="#" onclick="return GfUninstallFont(\'%s\', \'%s\', this)">%s %s %s</a></span>', md5( $name . ':' . $variant ), $name, $variant, __( 'Uninstall', GFontsEngine::PLUGIN_SLUG ), $name, $variant );
			}
		}

		return sprintf( '<span id="%s"><a href="#" onclick="return GfInstallFont(\'%s\', \'%s\', this)">%s %s %s</a></span>', md5( $name . ':' . $variant ), $name, $variant, __( 'Install', GFontsEngine::PLUGIN_SLUG ), $name, $variant );
	}

	static public function FontList() {
		print "<div class='wrap'>";
		print "<h2>" . __( 'Google Fonts - List of installed fonts', GFontsEngine::PLUGIN_SLUG ) . "</h2>";

		if ( isset( $_GET['act'] ) && $_GET['act'] == 'uninstall' ) {
			$fid = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : false;
			if ( is_int( $fid ) ) {
				GFontsDB::UninstallFontById( $fid );
				GFontsUI::Success( __( 'Font uninstalled succesfully.' ) );
			}
		}

		if ( isset( $_GET['act'] ) && $_GET['act'] == 'bulk' ) {
			$t = $_POST;
			switch ( $t['action'] ) {
				case 'uninstall':
					$fontArray = $t['font'];
					GFontsDB::UninstallFontByIdCollection( $fontArray );
					GFontsUI::Success( __( 'Fonts uninstalled succesfully.' ) );
					break;
			}
		}

		if ( isset( $_GET['s'] ) && $_GET['s'] != '' ) {
			$nameFilter = strip_tags( trim( $_GET['s'] ) );
		} else {
			$nameFilter = '';
		}

		$orderby	 = '';
		$direction	 = '';

		if ( isset( $_GET['orderby'] ) ) {
			switch ( $_GET['orderby'] ) {
				case 'usedin':
					$orderby = 'used_in_posts';
					break;
				case 'fontname':
					$orderby = 'name';
					break;
			}

			switch ( $_GET['order'] ) {
				case 'asc':
					$direction	 = 'asc';
					break;
				case 'desc':
					$direction	 = 'desc';
					break;
			}
		}

		if ( !isset( $_GET['f'] ) ) {
			$installedFonts = GFontsDB::GetInstalledFonts( 0, $nameFilter, $orderby, $direction );
		} else {
			switch ( $_GET['f'] ) {
				case 'used': $installedFonts	 = GFontsDB::GetInstalledFonts( 1, $nameFilter, $orderby, $direction );
					break;
				case 'unused': $installedFonts	 = GFontsDB::GetInstalledFonts( 2, $nameFilter, $orderby, $direction );
					break;
			}
		}
		$stats = GFontsDB::GetInstalledFontsStats();
		print "<ul class='subsubsub'>";
		printf( "<li class='all'><a href='admin.php?%s' class=\"current\">%s <span class=\"count\">(%d)</span></a> |</li>", 'page=' . GFontsEngine::PLUGIN_FONT_LIST, __( 'All', GFontsEngine::PLUGIN_SLUG ), isset( $stats[2] )
					? $stats[2]->qty : 0  );
		printf( "<li class='used'><a href='admin.php?%s&f=used'>%s <span class=\"count\">(%d)</span></a> |</li>", 'page=' . GFontsEngine::PLUGIN_FONT_LIST, __( 'Used', GFontsEngine::PLUGIN_SLUG ), isset( $stats[0] )
					? $stats[0]->qty : 0  );
		printf( "<li class='used'><a href='admin.php?%s&f=unused'>%s <span class=\"count\">(%d)</span></a></li>", 'page=' . GFontsEngine::PLUGIN_FONT_LIST, __( 'Unused', GFontsEngine::PLUGIN_SLUG ), isset( $stats[1] )
					? $stats[1]->qty : 0  );
		print "</ul>";

		print "<form id=\"fonts-filter\" action=\"\" method=\"get\">";
		print "<p class=\"search-box\">";
		printf( "<label class=\"screen-reader-text\" for=\"font-search-input\">%s :</label>", __( "Search fonts", GFontsEngine::PLUGIN_SLUG ) );
		printf( "<input type=\"search\" id=\"font-search-input\" name=\"s\" value=\"%s\" />", $nameFilter );
		printf( "<input type=\"submit\" name=\"\" id=\"search-submit\" class=\"button\" value=\"%s\"  />", __( "Search fonts", GFontsEngine::PLUGIN_SLUG ) );
		print "</p>";
		printf( "<input type=\"hidden\" name=\"page\" value=\"%s\" />", GFontsEngine::PLUGIN_FONT_LIST );
		print "</form>";
		printf( "<form id=\"posts-filter\" action=\"admin.php?page=%s&act=bulk\" method=\"post\">", GFontsEngine::PLUGIN_FONT_LIST );
		print "<div class=\"tablenav top\">";
		print "<div class=\"alignleft actions\">";
		print "	<select name='action'>";
		printf( "		<option value='-1' selected='selected'>%s</option>", __( "Bulk actions", GFontsEngine::PLUGIN_SLUG ) );
		printf( "		<option value='uninstall'>%s</option>", __( "Uninstall", GFontsEngine::PLUGIN_SLUG ) );
		print "	</select>";
		print "<input type=\"submit\" name=\"\" id=\"doaction\" class=\"button action\" value=\"" . __( "Apply", GFontsEngine::PLUGIN_SLUG ) . "\"  />";
		print "</div>";
		$paginationcode = '';
		printf( "<div class='tablenav-pages'><span class=\"displaying-num\">%s</span>%s</div>", sprintf( _n( '%d font', '%d fonts', isset( $stats[2] )
							? $stats[2]->qty : 0  ), isset( $stats[2] ) ? $stats[2]->qty : 0  ), $paginationcode );
		print "</div>";
		GFontsEngine::IncludeCssForFonts( true );
		print '<table class="wp-list-table widefat fixed" cellspacing="0">';
		print '<thead>';
		print '<tr>';
		print '<th scope=\'col\' id=\'cb\' class=\'manage-column column-cb check-column\' style=""><label class="screen-reader-text" for="cb-select-all-1">' . __( 'Select all', GFontsEngine::PLUGIN_SLUG ) . '</label><input id="cb-select-all-1" type="checkbox" /></th>';
		print '<th scope=\'col\' id=\'fontname\' class=\'manage-column column-fontname sortable ' . GFontsEngine::GetCurrentOrder() . ' \' style=""><a href="' . admin_url( 'admin.php?page=' . GFontsEngine::PLUGIN_FONT_LIST . '&orderby=fontname&order=' . GFontsEngine::GetOrderBy() ) . '"><span>' . __( 'Font', GFontsEngine::PLUGIN_SLUG ) . '</span><span class="sorting-indicator"></span></a></th>';
		print '<th scope=\'col\' id=\'usedin\' class=\'manage-column column-fontname sortable ' . GFontsEngine::GetCurrentOrder() . ' \' style=""><a href="' . admin_url( 'admin.php?page=' . GFontsEngine::PLUGIN_FONT_LIST . '&orderby=usedin&order=' . GFontsEngine::GetOrderBy() ) . '"><span>' . __( 'Used in posts', GFontsEngine::PLUGIN_SLUG ) . '</span><span class="sorting-indicator"></span></a></th>';
		print '</tr>';
		print '</thead>';
		print '<tfoot>';
		print '<tr>';
		print '<th scope=\'col\' id=\'cb\' class=\'manage-column column-cb check-column\' style=""><label class="screen-reader-text" for="cb-select-all-1">' . __( 'Select all', GFontsEngine::PLUGIN_SLUG ) . '</label><input id="cb-select-all-1" type="checkbox" /></th>';
		print '<th scope=\'col\' id=\'fontname\' class=\'manage-column column-fontname sortable ' . GFontsEngine::GetCurrentOrder() . ' \' style=""><a href="' . admin_url( 'admin.php?page=' . GFontsEngine::PLUGIN_FONT_LIST . '&orderby=fontname&order=' . GFontsEngine::GetOrderBy() ) . '"><span>' . __( 'Font', GFontsEngine::PLUGIN_SLUG ) . '</span><span class="sorting-indicator"></span></a></th>';
		print '<th scope=\'col\' id=\'usedin\' class=\'manage-column column-fontname sortable ' . GFontsEngine::GetCurrentOrder() . ' \' style=""><a href="' . admin_url( 'admin.php?page=' . GFontsEngine::PLUGIN_FONT_LIST . '&orderby=usedin&order=' . GFontsEngine::GetOrderBy() ) . '"><span>' . __( 'Used in posts', GFontsEngine::PLUGIN_SLUG ) . '</span><span class="sorting-indicator"></span></a></th>';
		print '</tr>';
		print '</tfoot>';
		print '<tbody id="the-list">';

		foreach ( $installedFonts as $fnt ) {
			print '<tr id="fnt-' . $fnt->id . '" class="alternate iedit" valign="top" style="font-family: ' . $fnt->name . ';">';
			print '<th scope="row" class="check-column">';
			print '<label class="screen-reader-text" for="cb-select-' . $fnt->id . '">' . sprintf( '%s %s', __( 'Select', GFontsEngine::PLUGIN_SLUG ), $fnt->name ) . '</label>';
			print '<input id="cb-select-' . $fnt->id . '" type="checkbox" name="font[]" value="' . $fnt->id . '" />';
			print '<div class="locked-indicator"></div>';
			print '</th>';
			print '<td style="font-size: 17px;">';
			print '<strong>' . $fnt->name . '</strong>';
			print '<div class="locked-info"><span class="locked-avatar"></span> <span class="locked-text"></span></div>';
			print '<div class="row-actions">';
			if ( $fnt->total_used == 0 ) {
				$onclick = '';
			} else {
				$txt	 = sprintf( _n( 'This font is used in %d post. If you uninstall it this post will loose font styling.', 'This font is used in %d posts. If you uninstall it these posts will loose font styling.', $fnt->total_used ), $fnt->total_used );
				$onclick = sprintf( ' onclick="return confirm(\'%s\');"', $txt );
			}
			print '<span class=\'trash\'><a class=\'submitdelete\' title=\'' . __( 'Uninstall', GFontsEngine::PLUGIN_SLUG ) . '\' href=\'' . admin_url( 'admin.php?page=' . GFontsEngine::PLUGIN_FONT_LIST . '&id=' . $fnt->id . '&act=uninstall' ) . '\'' . $onclick . '>' . __( 'Uninstall', GFontsEngine::PLUGIN_SLUG ) . '</a></span>';
			print '</div>';
			print '</td>';
			print '<td style="font-size: 17px;">';
			print '<strong>' . sprintf( _n( '%d post', '%d posts', $fnt->used_in_posts, GFontsEngine::PLUGIN_SLUG ), $fnt->used_in_posts ) . '</strong>';
			if ( $fnt->in_trash > 0 ) {
				print '&nbsp; + <strong>' . sprintf( _n( '%d post in trash', '%d posts in trash', $fnt->in_trash, GFontsEngine::PLUGIN_SLUG ), $fnt->in_trash ) . '</strong>';
			}
			print '</td>';
			print '</tr>';
		}
		print '</tbody>';
		print '</table>';
		print '</form>';
	}

	static public function GetOrderBy() {
		$order = 'asc';
		if ( isset( $_GET['order'] ) ) {
			$v = strtolower( $_GET['order'] );
			if ( $v == 'asc' ) {
				$order = 'desc';
			} else {
				$order = 'asc';
			}
		}

		return $order;
	}

	static public function GetCurrentOrder() {
		$order = '';
		if ( isset( $_GET['order'] ) ) {
			$order = $_GET['order'];
		}
		return $order;
	}

	static public function FilterPostsByFont( $where ) {
		if ( is_admin() ) {
			global $wpdb;

			if ( isset( $_GET['gfontfilter'] ) && !empty( $_GET['gfid'] ) && intval( $_GET['gfid'] ) != 0 ) {
				$fntId = intval( $_GET['gfid'] );
				$where .= " AND ID IN (SELECT wp_post_id FROM {$wpdb->prefix}gf_font_post WHERE gf_fontlist_id=$fntId )";
			}
		}
		return $where;
	}

	static public function FilterPostsByNoFont( $q ) {
		if ( isset( $_GET['gfontfilter'] ) && isset( $_GET['nofont'] ) && ($_GET['nofont'] == 1) ) {
			//$q->query_vars['meta_key'] = GFontsEngine::PLUGIN_META_NO_FONT;
			//$q->query['meta_value'] = 1;
			$q->set( 'meta_key', GFontsEngine::PLUGIN_META_NO_FONT );
			$q->set( 'meta_value', '1' );
		}
	}

	static public function AddFiltersToPostList() {
		print '<input type="hidden" name="gfontfilter" value="true" />';
		print '<select name="gfid" class="postform" style="width: 250px;">';
		print '<option value="0">' . __( 'Select font to filter', GFontsEngine::PLUGIN_SLUG ) . '</option>';
		$fnts = GFontsDB::GetFontsWithPosts();
		foreach ( $fnts as $fnt ) {
			$selected = "";
			if ( isset( $_GET['gfid'] ) ) {
				$gfid = intval( $_GET['gfid'] );
				if ( $gfid == $fnt->id ) {
					$selected = " selected";
				}
			}
			if ( isset( $_GET['post_status'] ) && ($_GET['post_status'] == 'trash') ) {
				if ( $fnt->in_trash > 0 ) {
					print sprintf( '<option value="' . $fnt->id . '"%s>', $selected );
					if ( $fnt->gfont ) {
						print '(g) ';
					}
					printf( '%s (%s)', $fnt->name, sprintf( _n( '%d post', '%d posts', $fnt->in_trash, GFontsEngine::PLUGIN_SLUG ), $fnt->in_trash ) );
					print '</option>';
				}
			} else {
				if ( $fnt->used_in_posts > 0 ) {
					print sprintf( '<option value="' . $fnt->id . '"%s>', $selected );
					if ( $fnt->gfont ) {
						print '(g) ';
					}
					printf( '%s (%s)', $fnt->name, sprintf( _n( '%d post', '%d posts', $fnt->used_in_posts, GFontsEngine::PLUGIN_SLUG ), $fnt->used_in_posts ) );
					print '</option>';
				}
			}
		}
		print '</select>';
		printf( "<input type=\"checkbox\" id=\"nofont\" name=\"nofont\" value=\"1\"%s />&nbsp;<label for=\"nofont\">No fonts posts</label>&nbsp;", (isset( $_GET['nofont'] ) && $_GET['nofont'] == 1)
					? " checked" : ""  );
		if ( isset( $_GET['nofont'] ) && $_GET['nofont'] == 1 ) {
			print "<script type=\"text/javascript\">";
			//print "	jQuery(function() {";
			print "		jQuery('#icon-edit').next().append('<br/>Filtered for posts without font.');";
			//print "	});";
			print "</script>";
		}
	}

	static public function AddHelpToEditPhp() {
		get_current_screen()->add_help_tab( array(
			'id'		 => 'fontfilter',
			'title'		 => __( 'Font Filter' ),
			'content'	 =>
			'<p>' . __( 'You can filter post by used fonts.' ) .
			'<p><strong><u>' . __( 'Font list is filtered only to items which are used not for all fonts.' ) . '</u></strong></p>' .
			'<p>' . __( 'No fonts posts - filter only for posts without any font style.' ) . '</p>'
		) );
	}

	static public function TranslateVariantNameToWeight( $variant ) {
		$name = $variant;
		switch ( $variant ) {
			case 'regular':
				$name	 = '400';
				break;
			case 'italic':
				$name	 = '400';
				break;
		}

		$r = intval( $name );
		return $r;
	}

	static public function TranslateVariantToFontStyle( $variant ) {
		return (strpos( $variant, 'italic' ) !== false) ? 'italic' : 'normal';
	}

	static public function FontStats() {
		if ( isset( $_POST['act'] ) && $_POST['act'] == 'update' ) {
			GFontsDB::RecalculateStats();
			GFontsUI::Success( __( 'Stats recalculated successfully.' ) );
		}

		if ( isset( $_GET['act'] ) && $_GET['act'] == 'replace' ) {
			$p = $_POST;
			if ( isset( $p['srcfont'] ) && isset( $p['dstfont'] ) ) {
				GFontsDB::ReplaceFont( $p['srcfont'], $p['dstfont'] );
				GFontsUI::Success( __( 'Fonts replaced successfully.' ) );
			}
		}

		$count				 = count( GFontsDB::GetInstalledFonts() );
		$allFonts			 = GFontsEngine::AvailableFontCount();
		$postsWithoutFonts	 = GFontsEngine::CountPostsWithoutFonts();

		print "<div class='wrap'>";
		print "<h2>" . __( 'Google Fonts - Wordpress Font Statistics & Tools', GFontsEngine::PLUGIN_SLUG ) . "</h2>";
		print "<h3 class=\"title\">" . __( 'General information', GFontsEngine::PLUGIN_SLUG ) . "</h3>";
		print "<table border=\"0\">";
		print "<tr>";
		print "<td style=\"width: 200px;\">" . __( 'Google Fonts available', GFontsEngine::PLUGIN_SLUG ) . "</td>";
		print "<th style=\"width: 100px;\" align=\"left\">" . $allFonts . "</th>";
		print "</tr>";
		print "<tr>";
		print "<td style=\"width: 200px;\">" . __( 'Google Fonts installed', GFontsEngine::PLUGIN_SLUG ) . "</td>";
		print "<th style=\"width: 100px;\" align=\"left\">" . $count . "</th>";
		print "</tr>";
		print "<tr>";
		print "<td style=\"width: 200px;\">" . __( 'Posts without any font', GFontsEngine::PLUGIN_SLUG ) . "</td>";
		print "<th style=\"width: 100px;\" align=\"left\">" . $postsWithoutFonts . "&nbsp;(<a href=\"edit.php?gfontfilter=true&nofont=1\">" . __( 'view' ) . "</a>)</th>";
		print "</tr>";
		print "</table>";
		print "<div style=\"height: 20px;\"></div>";
		print "<form method=\"post\" action=\"admin.php?page=" . GFontsEngine::PLUGIN_FONT_STATS . "\"><input type=\"hidden\" name=\"act\" value=\"update\"><button class=\"button button-primary\">" . __( 'Recalculate stats and fix missing fonts', GFontsEngine::PLUGIN_SLUG ) . "</button></form>";

		if ( isset( $_GET['s'] ) && $_GET['s'] != '' ) {
			$nameFilter = strip_tags( trim( $_GET['s'] ) );
		} else {
			$nameFilter = '';
		}

		$orderby	 = '';
		$direction	 = '';

		if ( isset( $_GET['orderby'] ) ) {
			switch ( $_GET['orderby'] ) {
				case 'usedin':
					$orderby = 'used_in_posts';
					break;
				case 'fontname':
					$orderby = 'name';
					break;
			}

			switch ( $_GET['order'] ) {
				case 'asc':
					$direction	 = 'asc';
					break;
				case 'desc':
					$direction	 = 'desc';
					break;
			}
		}

		$allFonts = GFontsDB::GetFontsWithPosts( $nameFilter, $orderby, $direction );

		print "<form id=\"fonts-filter\" action=\"\" method=\"get\">";
		print "<p class=\"search-box\">";
		printf( "<label class=\"screen-reader-text\" for=\"font-search-input\">%s :</label>", __( "Search fonts", GFontsEngine::PLUGIN_SLUG ) );
		printf( "<input type=\"search\" id=\"font-search-input\" name=\"s\" value=\"%s\" />", $nameFilter );
		printf( "<input type=\"submit\" name=\"\" id=\"search-submit\" class=\"button\" value=\"%s\"  />", __( "Search fonts", GFontsEngine::PLUGIN_SLUG ) );
		print "</p>";
		printf( "<input type=\"hidden\" name=\"page\" value=\"%s\" />", GFontsEngine::PLUGIN_FONT_STATS );
		print "</form>";
		print "<div class=\"tablenav top\">";

		$paginationcode = '';
		printf( "<div class='tablenav-pages'><span class=\"displaying-num\">%s</span>%s</div>", sprintf( _n( '%d font', '%d fonts', count( $allFonts ) ), count( $allFonts ) ), $paginationcode );
		print "</div>";
		GFontsEngine::IncludeCssForFonts( true );
		print '<table class="wp-list-table widefat fixed" cellspacing="0">';
		print '<thead>';
		print '<tr>';
		print '<th width=\'70%\' scope=\'col\' id=\'fontname\' class=\'manage-column column-fontname sortable ' . GFontsEngine::GetCurrentOrder() . ' \' style=""><a href="' . admin_url( 'admin.php?page=' . GFontsEngine::PLUGIN_FONT_STATS . '&orderby=fontname&order=' . GFontsEngine::GetOrderBy() ) . '"><span>' . __( 'Font', GFontsEngine::PLUGIN_SLUG ) . '</span><span class="sorting-indicator"></span></a></th>';
		print '<th width=\'30%\' scope=\'col\' id=\'usedin\' class=\'manage-column column-fontname sortable ' . GFontsEngine::GetCurrentOrder() . ' \' style=""><a href="' . admin_url( 'admin.php?page=' . GFontsEngine::PLUGIN_FONT_STATS . '&orderby=usedin&order=' . GFontsEngine::GetOrderBy() ) . '"><span>' . __( 'Used in posts', GFontsEngine::PLUGIN_SLUG ) . '</span><span class="sorting-indicator"></span></a></th>';
		print '</tr>';
		print '</thead>';
		print '<tfoot>';
		print '<tr>';
		print '<th scope=\'col\' id=\'fontname\' class=\'manage-column column-fontname sortable ' . GFontsEngine::GetCurrentOrder() . ' \' style=""><a href="' . admin_url( 'admin.php?page=' . GFontsEngine::PLUGIN_FONT_STATS . '&orderby=fontname&order=' . GFontsEngine::GetOrderBy() ) . '"><span>' . __( 'Font', GFontsEngine::PLUGIN_SLUG ) . '</span><span class="sorting-indicator"></span></a></th>';
		print '<th scope=\'col\' id=\'usedin\' class=\'manage-column column-fontname sortable ' . GFontsEngine::GetCurrentOrder() . ' \' style=""><a href="' . admin_url( 'admin.php?page=' . GFontsEngine::PLUGIN_FONT_STATS . '&orderby=usedin&order=' . GFontsEngine::GetOrderBy() ) . '"><span>' . __( 'Used in posts', GFontsEngine::PLUGIN_SLUG ) . '</span><span class="sorting-indicator"></span></a></th>';
		print '</tr>';
		print '</tfoot>';
		print '<tbody id="the-list">';

		foreach ( $allFonts as $fnt ) {
			print '<tr id="fnt-' . $fnt->id . '" class="alternate iedit" valign="top" style="font-family: ' . $fnt->name . ';">';
			print '<td style="font-size: 17px;">';
			print '<strong>' . $fnt->name . '</strong>';
			print '<div class="locked-info"><span class="locked-avatar"></span> <span class="locked-text"></span></div>';
			print '<div>'; // class="row-actions">';
			print "<form action=\"admin.php?page=" . GFontsEngine::PLUGIN_FONT_STATS . "&act=replace\" method=\"post\" onsubmit=\"return confirm('Are you sure you want replace " . $fnt->name . " with selected font?');\">";
			print "<span style=\"font-size: 12px; font-family: Verdana;\">Replace this font with&nbsp;</span>";
			print "<select name=\"dstfont\" style=\"width: 200px; font-size: 12px; font-family: Verdana;\">";
			$avFonts = GFontsDB::GetFontsToReplace( $fnt->name );
			foreach ( $avFonts as $font ) {
				printf( "<option value=\"%s\" >%s</option>", $font, $font );
			}
			print "</select>";
			print "<input type=\"hidden\" name=\"srcfont\" value=\"" . $fnt->id . "\" />";
			print "<input type=\"submit\" value=\"Change!\" class=\"button button-primary\" style=\"font-size: 12px; font-family: Verdana;\"/>";
			print "</form>";
			print '</div>';
			print "<br/>";
			print '</td>';
			print '<td style="font-size: 17px;">';
			print '<strong>' . sprintf( _n( '%d post', '%d posts', $fnt->used_in_posts, GFontsEngine::PLUGIN_SLUG ), $fnt->used_in_posts ) . '</strong>';
			if ( $fnt->in_trash > 0 ) {
				print '&nbsp; + <strong>' . sprintf( _n( '%d post in trash', '%d posts in trash', $fnt->in_trash, GFontsEngine::PLUGIN_SLUG ), $fnt->in_trash ) . '</strong>';
			}
			if ( $fnt->total_used > 0 ) {
				print '<div class="locked-info"><span class="locked-avatar"></span> <span class="locked-text"></span></div>';
				print '<div class="row-actions">';
				print '<span class=\'view\'><a title=\'' . __( 'View', GFontsEngine::PLUGIN_SLUG ) . '\' href=\'' . admin_url( 'edit.php?gfontfilter=1&gfid=' . $fnt->id ) . '\' target="_blank">' . __( 'View', GFontsEngine::PLUGIN_SLUG ) . '</a></span>';
				if ( $fnt->in_trash > 0 ) {
					print '&nbsp;|&nbsp;<span class=\'view\'><a title=\'' . __( 'View in trash', GFontsEngine::PLUGIN_SLUG ) . '\' href=\'' . admin_url( 'edit.php?post_status=trash&gfontfilter=1&gfid=' . $fnt->id ) . '\' target="_blank">' . __( 'View in trash', GFontsEngine::PLUGIN_SLUG ) . '</a></span>';
				}
				print '</div>';
			}
			print '</td>';
			print '</tr>';
		}
		print '</tbody>';
		print '</table>';
	}

	static public function CountPostsWithoutFonts() {
		$query	 = array(
			'posts_per_page'	 => 20000000,
			'offset'			 => 0,
			'category'			 => '',
			'orderby'			 => 'post_date',
			'order'				 => 'DESC',
			'include'			 => '',
			'exclude'			 => '',
			'meta_key'			 => GFontsEngine::PLUGIN_META_NO_FONT,
			'meta_value'		 => '1',
			'post_type'			 => 'any',
			'post_mime_type'	 => '',
			'post_parent'		 => '',
			'post_status'		 => 'any',
			'suppress_filters'	 => true );
		$posts	 = get_posts( $query );
		return count( $posts );
	}

	static public function ContentSave( $content ) {
		$r = $_REQUEST;
		if ( isset( $r['post_ID'] ) && isset( $r['action'] ) ) {
			if ( $r['action'] == 'editpost' ) {
				$old = get_post( $r['post_ID'] );
			} elseif ( $r['action'] == 'autosave' ) {
				$old = get_post( $r['post_id'] );
			}
			if ( $old instanceof WP_Post ) {
				$oldContent = $old->post_content;
				GFontsDB::ContentSave( $old->ID, $oldContent, $content );
			}
		}
		return $content;
	}

	static public function BeforeDelete( $postid ) {
		GFontsDB::PostDeleted( $postid );
	}

	static public function TrashedPost( $postid ) {
		GFontsDB::TrashedPost( $postid );
	}

	static public function UntrashedPost( $postid ) {
		GFontsDB::UntrashedPost( $postid );
	}

	static public function FontSize() {
		print "<div class='wrap'>";
		print "<h2>" . __( 'Fonts - enable extra font sizing', GFontsEngine::PLUGIN_SLUG ) . "</h2>";
		print "<h3 class=\"title\">" . __( 'By enabling this function you will be able to change font sizes in pixels', GFontsEngine::PLUGIN_SLUG ) . "</h3>";
		if ( @$_GET['settings-updated'] == 'true' ) {
			GFontsUI::Success( __( "Changes has been saved.", GFontsEngine::PLUGIN_SLUG ) );
		}
		print "<form method=\"post\" action=\"options.php\"> ";
		settings_fields( GFontsEngine::PLUGIN_SLUG );
		do_settings_fields( GFontsEngine::PLUGIN_SLUG, '' );
		?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><?php
					print __( "Enabled Extra Font Sizes", GFontsEngine::PLUGIN_SLUG );
					?></th>
				<td><input type="checkbox" name="<?php echo GFontsEngine::PLUGIN_OPTION_FONT_SIZE_ENABLED ?>" <?php
					echo (get_option( GFontsEngine::PLUGIN_OPTION_FONT_SIZE_ENABLED, true )) ? "checked"
							: "";
					?> /></td>
			</tr>

			<tr valign="top">
				<th scope="row"><?php
					print __( "Min size (6px min)", GFontsEngine::PLUGIN_SLUG );
					?></th>
				<td><input type="text" name="<?php echo GFontsEngine::PLUGIN_OPTION_FONT_SIZE_MINIMUM ?>" value="<?php
					echo get_option( GFontsEngine::PLUGIN_OPTION_FONT_SIZE_MINIMUM, 6 );
					?>" /></td>
			</tr>

			<tr valign="top">
				<th scope="row"><?php
					print __( "Max size (48px max)", GFontsEngine::PLUGIN_SLUG );
					?></th>
				<td><input type="text" name="<?php echo GFontsEngine::PLUGIN_OPTION_FONT_SIZE_MAXIMUM ?>" value="<?php
					echo get_option( GFontsEngine::PLUGIN_OPTION_FONT_SIZE_MAXIMUM, 48 );
					?>" /></td>
			</tr>

		</table>
		<?php
		submit_button( __( "Update options", GFontsEngine::PLUGIN_SLUG ) );
		print "</form></div>";
	}

	static public function BuildFontSizes() {
		$min = intval( get_option( GFontsEngine::PLUGIN_OPTION_FONT_SIZE_MINIMUM ) );
		if ( ($min == 0) || ($min < 6) ) {
			$min = 6;
		}
		$max = intval( get_option( GFontsEngine::PLUGIN_OPTION_FONT_SIZE_MAXIMUM ) );
		if ( ($max == 0) || ($max > 48) ) {
			$max = 48;
		}

		$fs = array();
		for ( $i = $min; $i <= $max; $i++ ) {
			$fs[] = $i . "px";
		}

		return implode( ",", $fs );
	}

	static public function FullVersion() {
		print "<div class='wrap'>";
		print "<center><h2>" .
			__( 'Check out my Social Sliders plugin', GFontsEngine::PLUGIN_SLUG );
        print "</h2></center>";
		include_once GFONTS_ABS_PATH . '/adv/adv.php';
	}

	static public function GSchedule( $schedules ) {
		if ( isset( $schedules['weekly'] ) ) {
			return $schedules;
		}
		$schedules['weekly'] = array(
			'interval'	 => 604800,
			'display'	 => __( 'Once Weekly' )
		);
		return $schedules;
	}

	static public function CronHook() {
		self::InstallFonts();
	}

}
