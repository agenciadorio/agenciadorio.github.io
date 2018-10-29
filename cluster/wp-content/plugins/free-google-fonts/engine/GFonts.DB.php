<?php

class GFontsDB {

	static public function InstallDB() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'gf_fontlist';
		$sql1 = "CREATE TABLE $table_name (
            id int NOT NULL AUTO_INCREMENT,
            name VARCHAR(255) not null,
            variant VARCHAR(255) not null,
            subsets VARCHAR(255) not null,
            used_in_posts int not null default 0,
            in_trash int not null default 0,
            total_used int not null default 0,
            gfont int not null default 1,
            installed int not null default 1,
            PRIMARY KEY  (id),
            KEY ix_gfl_installed (installed),
            KEY ix_gfl_gf (gfont),
            KEY ix_gfl_uip (used_in_posts)
        );";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql1 );

		$table_name = $wpdb->prefix . 'gf_font_post';
		$sql2 = "CREATE TABLE $table_name (
            id int NOT NULL AUTO_INCREMENT,
            gf_fontlist_id int not null,
            wp_post_id int not null,
            PRIMARY KEY  (id),
            KEY ix_gfp_gfid (gf_fontlist_id)
        );";

		dbDelta( $sql2 );
	}

	static public function UninstallDB() {
		global $wpdb;
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		$table_name = $wpdb->prefix . 'gf_fontlist';
		$sql = "DROP TABLE " . $table_name . ";";
		$wpdb->query( $sql );
		$table_name = $wpdb->prefix . 'gf_font_post';
		$sql = "DROP TABLE " . $table_name . ";";
		$wpdb->query( $sql );
	}

	static public function InstallFont($fontname, $variant, $subsets, $noinstall = false) {
		global $wpdb;
		$sql = "SELECT COUNT(*) FROM " . $wpdb->prefix . "gf_fontlist WHERE name = %s AND variant = %s";
		$sqlPrepared = $wpdb->prepare($sql, $fontname, $variant);
		$i = $wpdb->get_var($sqlPrepared);
		if ( $i == 0 && ! $noinstall ) {
				$sql = "INSERT INTO " . $wpdb->prefix . "gf_fontlist(name, variant, subsets, used_in_posts, gfont) VALUES(%s, %s, %s, 0, 1)";
				$sqlPrepared = $wpdb->prepare($sql, $fontname, $variant, $subsets);
				$wpdb->query($sqlPrepared);
			return 0;
		} else if ( 1 == $i ) {
			$sql = "UPDATE " . $wpdb->prefix . "gf_fontlist SET installed = 1, subsets = %s WHERE name = %s AND variant = %s";
			$sqlPrepared = $wpdb->prepare($sql, $subsets, $fontname, $variant);
			$wpdb->query($sqlPrepared);
			$sql = "SELECT used_in_posts FROM " . $wpdb->prefix . "gf_fontlist WHERE name = %s AND variant = %s";
			$sqlPrepared = $wpdb->prepare($sql, $fontname, $variant);
			$i = (int) $wpdb->get_var($sqlPrepared);
			return $i;
		}
	}

	static public function UninstallFont( $fontname, $variant ) {
		global $wpdb;
		$sql = "UPDATE " . $wpdb->prefix . "gf_fontlist SET installed = 0 WHERE name = %s";
		$sqlPrepared = $wpdb->prepare( $sql, $fontname, $variant );
		$wpdb->query( $sqlPrepared );
		$sql = "SELECT used_in_posts FROM " . $wpdb->prefix . "gf_fontlist WHERE name = %s";
		$sqlPrepared = $wpdb->prepare( $sql, $fontname, $variant );
		$i = ( int ) $wpdb->get_var( $sqlPrepared );
		return $i;
	}

	static public function GetInstalledFonts(
		$useParameter = 0,
		$nameFilter = '',
		$orderby = '',
		$direction = ''
	) {
		global $wpdb;
		$nameFilter = '%' . $nameFilter . '%';

		if ( $useParameter == 0 ) {
			$sql = "SELECT id, name, variant, used_in_posts, total_used, in_trash, subsets FROM " . $wpdb->prefix . "gf_fontlist WHERE installed = 1 AND gfont = 1 AND name like %s";
		} elseif ( $useParameter == 1 ) {
			$sql = "SELECT id, name, variant, used_in_posts, total_used, in_trash, subsets FROM " . $wpdb->prefix . "gf_fontlist WHERE installed = 1 AND gfont = 1 AND used_in_posts > 0 AND name like %s";
		} elseif ( $useParameter == 2 ) {
			$sql = "SELECT id, name, variant, used_in_posts, total_used, in_trash, subsets FROM " . $wpdb->prefix . "gf_fontlist WHERE installed = 1 AND gfont = 1 AND used_in_posts = 0 AND name like %s";
		}
		if ( $orderby == '' ) {
			$orderby = 'name';
			$direction = 'ASC';
		}
		if ( $orderby != '' ) {
			$sql .= ' ORDER BY ' . $orderby;
			if ( $direction != '' ) {
				$sql .= ' ' . $direction;
			}
		}

		$sqlPrepared = $wpdb->prepare( $sql, $nameFilter );
		return $wpdb->get_results( $sqlPrepared );
	}

	static public function GetAllFonts() {
		global $wpdb;
		$sql = "SELECT id, name, gfont FROM " . $wpdb->prefix . "gf_fontlist ORDER BY name ASC";
		return $wpdb->get_results( $sql );
	}

	static public function GetInstalledFontsStats() {
		global $wpdb;
		$sql = "SELECT 'used', count(*) as qty FROM " . $wpdb->prefix . "gf_fontlist where installed = 1 and used_in_posts > 0 and gfont = 1
        UNION ALL
        SELECT 'unused', count(*) as qty FROM " . $wpdb->prefix . "gf_fontlist where installed = 1 and used_in_posts = 0 and gfont = 1
        UNION ALL
        SELECT 'all', count(*) as qty FROM " . $wpdb->prefix . "gf_fontlist where installed = 1 and gfont = 1";
		return $wpdb->get_results( $sql );
	}

	static public function UninstallFontById( $id ) {
		global $wpdb;
		$sql = "UPDATE " . $wpdb->prefix . "gf_fontlist SET installed = 0 WHERE installed = 1 AND id = %d";
		$sqlPrepared = $wpdb->prepare( $sql, intval( $id ) );
		$wpdb->query( $sqlPrepared );
	}

	static public function GetFontsWithPosts(
		$nameFilter = '',
		$orderby = '',
		$direction = ''
	) {
		global $wpdb;
		$nameFilter = '%' . $nameFilter . '%';
		$sql = "SELECT id, name, variant, used_in_posts, in_trash, total_used, gfont FROM " . $wpdb->prefix . "gf_fontlist WHERE total_used > 0 AND name LIKE %s";
		if ( $orderby == '' ) {
			$orderby = 'name';
			$direction = 'ASC';
		}
		if ( $orderby != '' ) {
			$sql .= ' ORDER BY ' . $orderby;
			if ( $direction != '' ) {
				$sql .= ' ' . $direction;
			}
		}
		$sqlPrepared = $wpdb->prepare( $sql, $nameFilter );
		return $wpdb->get_results( $sqlPrepared );
	}

	static public function UninstallFontByIdCollection( $arr ) {
		global $wpdb;
		$ids = array();
		foreach ( $arr as $itm ) {
			if ( intval( $itm ) > 0 ) {
				$ids[] = intval( $itm );
			}
		}

		if ( count( $ids ) > 0 ) {
			$sql = sprintf(
				"UPDATE " . $wpdb->prefix . "gf_fontlist SET installed = 0 WHERE installed = 1 AND id IN (%s)",
				implode( ',', $ids )
			);
			$wpdb->query( $sql );
		}
	}

	static public function UpdateFontUsedIn( $id, $value, $gfont ) {
		global $wpdb;
		$gfontVal = $gfont ? 1 : 0;
		$sql = "UPDATE {$wpdb->prefix}gf_fontlist SET used_in_posts = %d, gfont = %d, installed = %d WHERE id = %d";
		$installed = ($value > 0) ? 1 : 0;
		$sqlPrepared = $wpdb->prepare(
			$sql, $value, $gfontVal, $installed, $id
		);
		$wpdb->query( $sqlPrepared );
	}

	static public function InstallFontUsedIn( $name, $value, $gfont ) {
		global $wpdb;
		$gfontVal = $gfont ? 1 : 0;
		$sql = "INSERT INTO {$wpdb->prefix}gf_fontlist(name, used_in_posts, variant, gfont, installed, subsets) VALUES(%s, %d, 'regular', %d, 1, '')";
		$sqlPrepared = $wpdb->prepare( $sql, $name, $value, $gfontVal );
		$wpdb->query( $sqlPrepared );
		return $wpdb->get_var(
			$wpdb->prepare(
				"SELECT id FROM {$wpdb->prefix}gf_fontlist WHERE name = %s",
				$name
			)
		);
	}

	static public function UpdateFontUsedInTrash( $id, $value, $gfont ) {
		global $wpdb;
		$gfontVal = $gfont ? 1 : 0;
		$sql = "UPDATE {$wpdb->prefix}gf_fontlist SET in_trash = %d, gfont = %d, installed = %d WHERE id = %d";
		$installed = ($value > 0) ? 1 : 0;
		$sqlPrepared = $wpdb->prepare(
			$sql,
			$value,
			$gfontVal,
			$installed,
			$id
		);
		$wpdb->query( $sqlPrepared );
	}

	static public function InstallFontUsedInTrash( $name, $value, $gfont ) {
		global $wpdb;
		$gfontVal = $gfont ? 1 : 0;
		$sql = "INSERT INTO {$wpdb->prefix}gf_fontlist(name, in_trash, variant, gfont, installed, subsets) VALUES(%s, %d, 'regular', %d, 1, '')";
		$sqlPrepared = $wpdb->prepare( $sql, $name, $value, $gfontVal );
		$wpdb->query( $sqlPrepared );
		return $wpdb->get_var(
			$wpdb->prepare(
				"SELECT id FROM {$wpdb->prefix}gf_fontlist WHERE name = %s",
				$name
			)
		);
	}

	static public function CalculateTotalUsed() {
		global $wpdb;
		$gfontVal = $gfont ? 1 : 0;
		$sql = "UPDATE {$wpdb->prefix}gf_fontlist SET total_used = used_in_posts + in_trash";
		$wpdb->query( $sql );
	}

	static public function FontPostRelation( $idpost, $idfont ) {
		global $wpdb;
		$sql = "INSERT INTO {$wpdb->prefix}gf_font_post(wp_post_id, gf_fontlist_id) VALUES(%d, %d)";
		$sqlPrepared = $wpdb->prepare( $sql, $idpost, $idfont );
		$wpdb->query( $sqlPrepared );
	}

	static public function RecalculateStats() {
		global $wpdb;
		$wpdb->query( "UPDATE {$wpdb->prefix}gf_fontlist SET used_in_posts = 0, in_trash = 0, total_used = 0" );
		$wpdb->query( "TRUNCATE TABLE {$wpdb->prefix}gf_font_post" );
		$serializedItems = get_option(
			GFontsEngine::PLUGIN_OPTION_FONT_DATABASE
		);
		$gfonts = array();
		$items = unserialize( $serializedItems );
		foreach ( $items as $itm ) {
			$gfonts[] = $itm['name'];
		}
		$finish = false;
		$offset = 0;
		$usedInPosts = array();
		$usedInPostsInTrash = array();
		while ( !$finish ) {
			$args = array(
				'posts_per_page' => 200,
				'offset' => $offset * 200,
				'category' => '',
				'orderby' => 'post_date',
				'order' => 'DESC',
				'include' => '',
				'exclude' => '',
				'meta_key' => '',
				'meta_value' => '',
				'post_type' => 'post',
				'post_mime_type' => '',
				'post_parent' => '',
				'post_status' => array(
					'publish',
					'pending',
					'draft',
					'future',
					'private',
					'trash',
				),
				'suppress_filters' => true
			);
			$offset++;
			$posts = get_posts( $args );
			if ( count( $posts ) != 200 ) {
				$finish = true;
			}

			$regex = '/font-family:\s?\'?(.+?)\'?[;|\'|"]/i';
			$stats = array();

			foreach ( $posts as $post ) {
				$content = $post->post_content;
				$id = $post->ID;
				if ( $id == 1 ) {
					$v = 'x';
				}
				if ( preg_match_all( $regex, $content, $matches ) ) {
					update_post_meta(
						$post->ID,
						GFontsEngine::PLUGIN_META_NO_FONT,
						0
					);
					$fonts = $matches[1];
					$usedFonts = array();
					foreach ( $fonts as $font ) {
						$font = str_replace( "'", "", $font );
						$font = str_replace( '"', "", $font );
						$fArray = explode( ",", $font );
						$fname = ucwords( trim( $fArray[0] ) );
						if ( !in_array( $fname, $usedFonts ) ) {

							$usedFonts[] = $fname;
							if ( isset( $usedInPosts[$fname] ) ) {
								if ( $post->post_status != 'trash' ) {
									$usedInPosts[$fname] ++;
								} else {
									$usedInPostsInTrash[$fname] ++;
								}
							} else {
								if ( $post->post_status != 'trash' ) {
									$usedInPosts[$fname] = 1;
								} else {
									$usedInPostsInTrash[$fname] = 1;
								}
							}
						}
					}

					$stats[$post->ID] = $usedFonts;
				} else {
					update_post_meta(
						$post->ID, GFontsEngine::PLUGIN_META_NO_FONT, 1
					);
				}
			}

			$allFonts = GFontsDB::GetAllFonts();
			$fontIdByName = array();
			foreach ( $allFonts as $font ) {
				$fontIdByName[$font->name] = $font->id;
			}

			foreach ( $usedInPosts as $name => $value ) {
				if ( isset( $fontIdByName[$name] ) ) {
					GFontsDB::UpdateFontUsedIn(
						$fontIdByName[$name],
						$value,
						in_array( $name, $gfonts )
					);
				} else {
					GFontsDB::InstallFontUsedIn(
						$name,
						$value,
						in_array( $name, $gfonts )
					);
				}
			}

			foreach ( $usedInPostsInTrash as $name => $value ) {
				if ( isset( $fontIdByName[$name] ) ) {
					GFontsDB::UpdateFontUsedInTrash(
						$fontIdByName[$name], $value, in_array( $name, $gfonts )
					);
				} else {
					GFontsDB::InstallFontUsedInTrash(
						$name, $value, in_array( $name, $gfonts )
					);
				}
			}

			$allFonts = GFontsDB::GetAllFonts();
			$fontIdByName = array();
			foreach ( $allFonts as $font ) {
				$fontIdByName[$font->name] = $font->id;
			}

			foreach ( $stats as $idpost => $fonts ) {
				foreach ( $fonts as $fntName ) {
					$fntId = isset( $fontIdByName[$fntName] ) ? $fontIdByName[$fntName] : false;
					if ( $fntId !== false ) {
						GFontsDB::FontPostRelation( $idpost, $fntId );
					}
				}
			}

			GFontsDB::CalculateTotalUsed();
		}
		GFontsEngine::InstallFonts();
	}

	static public function GetFontsToReplace( $exclude = '' ) {
		$fnts = GFontsDB::GetInstalledFonts();
		$avFonts = array();
		foreach ( $fnts as $fnt ) {
			$avFonts[] = $fnt->name;
		}

		$avFonts[] = 'Andale Mono';
		$avFonts[] = 'Arial';
		$avFonts[] = 'Arial Black';
		$avFonts[] = 'Book Antiqua';
		$avFonts[] = 'Comic Sans MS';
		$avFonts[] = 'Courier New';
		$avFonts[] = 'Georgia';
		$avFonts[] = 'Helvetica';
		$avFonts[] = 'Impact';
		$avFonts[] = 'Symbol';
		$avFonts[] = 'Tahoma';
		$avFonts[] = 'Terminal';
		$avFonts[] = 'Times New Roman';
		$avFonts[] = 'Trebuchet MS';
		$avFonts[] = 'Verdana';
		$avFonts[] = 'Webdings';
		$avFonts[] = 'Wingdings';

		if ( $exclude != '' ) {
			$nAvFonts = array();
			foreach ( $avFonts as $font ) {
				if ( ucwords( $font ) != ucwords( $exclude ) ) {
					$nAvFonts[] = ucwords( $font );
				}
			}
			$avFonts = $nAvFonts;
		}

		return $avFonts;
	}

	static public function ReplaceFont( $srcFontId, $dstFontname ) {
		global $wpdb;
		$sql = "SELECT wp_post_id FROM {$wpdb->prefix}gf_font_post WHERE gf_fontlist_id = %d";
		$sqlPrepared = $wpdb->prepare( $sql, $srcFontId );
		$ids = $wpdb->get_col( $sqlPrepared );

		$sql = $wpdb->prepare(
			"SELECT id FROM {$wpdb->prefix}gf_fontlist WHERE name = %s",
			$dstFontname
		);
		$dstFontId = $wpdb->get_var( $sql );

		if ( $dstFontId === null ) {
			$dstFontId = GFontsDB::InstallFontUsedIn( $dstFontname, 0, 0 );
		}

		$srcName = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT name FROM {$wpdb->prefix}gf_fontlist WHERE id = %d",
				$srcFontId
			)
		);

		$query = array(
			'posts_per_page' => 0,
			'offset' => 0,
			'category' => '',
			'orderby' => 'post_date',
			'order' => 'DESC',
			'include' => '',
			'exclude' => '',
			'meta_key' => '',
			'meta_value' => '',
			'post_type' => 'post',
			'post_mime_type' => '',
			'post_parent' => '',
			'post_status' => array(
				'publish',
				'pending',
				'draft',
				'future',
				'private',
				'trash',
			),
			'suppress_filters' => true,
			'include' => implode( ',', $ids ),
		);
		$posts = get_posts( $query );
		$postCount = 0;
		$trashCount = 0;
		foreach ( $posts as $post ) {
			$content = $post->post_content;
			$q = preg_replace(
				'/font-family\s?:\s+\'?(' . $srcName . ')\'?([,|;|\'|"])/i',
				'font-family: ' . $dstFontname . '$2',
				$content
			);
			$npost = array(
				'ID' => $post->ID,
				'post_content' => $q
			);

			wp_update_post( $npost );
			$wpdb->query( $wpdb->prepare(
					"DELETE FROM {$wpdb->prefix}gf_font_post WHERE wp_post_id = %d AND gf_fontlist_id = %d",
					$post->ID,
					$srcFontId
				)
			);
			GFontsDB::FontPostRelation( $post->ID, $dstFontId );
			if ( $post->post_status != 'trash' ) {
				$postCount++;
			} else {
				$trashCount++;
			}
		}

		$sql = "UPDATE {$wpdb->prefix}gf_fontlist SET used_in_posts = 0, total_used = 0, in_trash = 0 WHERE id = %d";
		$sqlPrepared = $wpdb->prepare( $sql, $srcFontId );
		$wpdb->query( $sqlPrepared );
		$sql = "UPDATE {$wpdb->prefix}gf_fontlist SET used_in_posts = used_in_posts + %d, total_used = total_used + %d WHERE name = %s";
		$sqlPrepared = $wpdb->prepare(
			$sql,
			$postCount,
			$postCount,
			$dstFontname
		);
		$wpdb->query( $sqlPrepared );
		$sql = "UPDATE {$wpdb->prefix}gf_fontlist SET in_trash = in_trash + %d, total_used = total_used + %d WHERE name = %s";
		$sqlPrepared = $wpdb->prepare(
			$sql,
			$trashCount,
			$trashCount,
			$dstFontname
		);
		$wpdb->query( $sqlPrepared );
	}

	static public function GetFontsFromContent( $content ) {
		$regex = '/font-family:\s?(.+?)[;|\'|"]/i';
		$regex = '/font-family:\s?([\w\\\\\'\s\-]+)[;|\'|"|,]/i';
		$matches = array();
		if ( preg_match_all( $regex, $content, $matches ) ) {
			$fonts = $matches[1];
			$usedFonts = array();
			foreach ( $fonts as $font ) {
				$font = str_replace( "'", "", $font );
				$font = str_replace( '"', "", $font );
				$font = str_replace( '\\', "", $font );
				$fArray = explode( ",", $font );
				$fname = ucwords( trim( $fArray[0] ) );
				if ( !in_array( $fname, $usedFonts ) ) {
					$usedFonts[] = $fname;
				}
			}
			return $usedFonts;
		} else {
			return null;
		}
	}

	static public function ContentSave( $id, $oldContent, $newContent ) {
		$oldFonts = GFontsDB::GetFontsFromContent( $oldContent );
		$newFonts = GFontsDB::GetFontsFromContent( $newContent );
		$df = GFontsDB::DetectFontChanges( $oldFonts, $newFonts );
		foreach ( $df['removed'] as $font ) {
			GFontsDB::RemoveFontFromPost( $font, $id );
		}

		foreach ( $df['added'] as $font ) {
			GFontsDB::AddFontToPost( $font, $id );
		}

		if ( count( $newFonts ) > 0 ) {
			update_post_meta( $id, GFontsEngine::PLUGIN_META_NO_FONT, 0 );
		} else {
			update_post_meta( $id, GFontsEngine::PLUGIN_META_NO_FONT, 1 );
		}
	}

	static public function DetectFontChanges( $font1, $font2 ) {
		if ( $font1 === null ) {
			return array(
				'removed' => array(),
				'added' => is_array( $font2 ) ? $font2 : array()
			);
		}
		if ( $font2 === null ) {
			return array(
				'removed' => is_array( $font1 ) ? $font1 : array(),
				'added' => array()
			);
		}
		$d1 = array_diff( $font1, $font2 );
		$d2 = array_diff( $font2, $font1 );
		return array(
			'removed' => $d1,
			'added' => $d2
		);
	}

	static public function RemoveFontFromPost( $font, $id ) {
		global $wpdb;
		$fontId = GFontsDB::GetOrInstallFontByName( $font, 0 );
		$sql = $wpdb->prepare( "DELETE FROM {$wpdb->prefix}gf_font_post WHERE wp_post_id = %d and gf_fontlist_id = %d",
						 $id, $fontId );
		$wpdb->query( $sql );
		$sql = $wpdb->prepare( "UPDATE {$wpdb->prefix}gf_fontlist SET used_in_posts = used_in_posts - 1 WHERE id = %d",
						 $fontId );
		$wpdb->query( $sql );
		$wpdb->query( "UPDATE {$wpdb->prefix}gf_fontlist SET used_in_posts = 0 WHERE used_in_posts < 0" );
	}

	static public function AddFontToPost( $font, $id ) {
		global $wpdb;
		$fontId = GFontsDB::GetOrInstallFontByName( $font, 0 );
		$sql = $wpdb->prepare( "INSERT INTO {$wpdb->prefix}gf_font_post(wp_post_id, gf_fontlist_id) VALUES(%d, %d)",
						 $id, $fontId );
		$wpdb->query( $sql );
		$sql = $wpdb->prepare( "UPDATE {$wpdb->prefix}gf_fontlist SET used_in_posts = used_in_posts + 1 WHERE id = %d",
						 $fontId );
		$wpdb->query( $sql );
	}

	static public function GetOrInstallFontByName( $name, $gfont ) {
		global $wpdb;
		$sql = $wpdb->prepare( "SELECT id FROM {$wpdb->prefix}gf_fontlist WHERE name = %s",
						 $name );
		$id = $wpdb->get_var( $sql );
		if ( $id == null ) {
			$sql = $wpdb->prepare( "INSERT INTO {$wpdb->prefix}gf_fontlist(name, used_in_posts, gfont, installed) VALUES(%s, 0, %d, 1)",
						  $name, $gfont );
			$wpdb->query( $sql );
			$id = $wpdb->get_var( "SELECT LAST_INSERT_ID()" );
			return $id;
		} else {
			return $id;
		}
	}

	static public function PostDeleted( $postid ) {
		global $wpdb;
		$sql = $wpdb->prepare( "SELECT gf_fontlist_id FROM {$wpdb->prefix}gf_font_post WHERE wp_post_id = %d",
						 $postid );
		$ids = $wpdb->get_col( $sql );
		foreach ( $ids as $id ) {
			$sql = $wpdb->prepare( "UPDATE {$wpdb->prefix}gf_fontlist SET in_trash = in_trash - 1, total_used = total_used - 1 WHERE id = %d",
						  $id );
			$wpdb->query( $sql );
		}
		$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}gf_font_post WHERE wp_post_id = %d",
								$postid ) );
		$wpdb->query( "UPDATE {$wpdb->prefix}gf_fontlist SET in_trash = 0 WHERE in_trash < 0" );
		$wpdb->query( "UPDATE {$wpdb->prefix}gf_fontlist SET total_used = 0 WHERE total_used < 0" );
	}

	static public function TrashedPost( $postid ) {
		global $wpdb;
		$sql = $wpdb->prepare(
			"SELECT gf_fontlist_id FROM {$wpdb->prefix}gf_font_post WHERE wp_post_id = %d",
			$postid
		);
		$ids = $wpdb->get_col( $sql );
		foreach ( $ids as $id ) {
			$sql = $wpdb->prepare( "UPDATE {$wpdb->prefix}gf_fontlist SET used_in_posts = used_in_posts - 1, in_trash = in_trash + 1 WHERE id = %d",
						  $id );
			$wpdb->query( $sql );
		}
		$wpdb->query( "UPDATE {$wpdb->prefix}gf_fontlist SET used_in_posts = 0 WHERE used_in_posts < 0" );
	}

	static public function UnTrashedPost( $postid ) {
		global $wpdb;
		$sql = $wpdb->prepare( "SELECT gf_fontlist_id FROM {$wpdb->prefix}gf_font_post WHERE wp_post_id = %d",
						 $postid );
		$ids = $wpdb->get_col( $sql );
		foreach ( $ids as $id ) {
			$sql = $wpdb->prepare( "UPDATE {$wpdb->prefix}gf_fontlist SET used_in_posts = used_in_posts + 1, in_trash = in_trash - 1 WHERE id = %d",
						  $id );
			$wpdb->query( $sql );
		}
		$wpdb->query( "UPDATE {$wpdb->prefix}gf_fontlist SET in_trash = 0 WHERE in_trash < 0" );
	}

}

?>
