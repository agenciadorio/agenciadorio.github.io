<?php
/*
2017 05 21
* line 214 and below changed post_type with the correct posttype

CUSTOMIZED LAST TIME 2016 003 13
* added icon modal with material icons and fontawesome icons

CUSTOMIZED LAST TIME 2016 003 10
* added page template attribute and control to display fields only when certain templates are selecte, like 'pagetemplate' => 'page-tripleview.php'
CUSTOMIZED LAST TIME 2016 01 24
* added category field with dropdown
* added gelolocation for coordinates field type 

*/

// metaboxes directory constant
if(!defined("CUSTOM_METABOXES_DIR")){
define( 'CUSTOM_METABOXES_DIR', plugins_url( '' , __FILE__ ) );
}

/**
 * recives data about a form field and spits out the proper html
 *
 * @param	array					$field			array with various bits of information about the field
 * @param	string|int|bool|array	$meta			the saved data for this field
 * @param	array					$repeatable		if is this for a repeatable field, contains parant id and the current integar
 *
 * @return	string									html for the field
 */

function custom_meta_box_field( $field, $meta = null, $repeatable = null ) {
	if ( ! ( $field || is_array( $field ) ) )
	return;
		
	$class = isset( $field['class'] ) ? $field['class'] : '';
	$template = isset( $field['template'] ) ? $field['template'] : null;
	$pagetemplate = isset( $field['pagetemplate'] ) ? $field['pagetemplate'] : null;
	$type = isset( $field['type'] ) ? $field['type'] : null;
	$label = isset( $field['label'] ) ? $field['label'] : null;
	$desc = isset( $field['desc'] ) ?  $field['desc'] : null;
	$place = isset( $field['place'] ) ? $field['place'] : null;
	$size = isset( $field['size'] ) ? $field['size'] : null;
	$posttype = isset( $field['posttype'] ) ? $field['posttype'] : null;
	$options = isset( $field['options'] ) ? $field['options'] : null;
	$repeatable_fields = isset( $field['repeatable_fields'] ) ? $field['repeatable_fields'] : null;
	
	// the id and name for each field
	$id = $name = isset( $field['id'] ) ? $field['id'] : null;

	if ( $repeatable ) {
		$name = $repeatable[0].'['.$repeatable[1].']['.$id .']';
		$id = $repeatable[0].'_'.$repeatable[1].'_'.$id;
	}


	switch( $type ) {
		// basic
		case 'text':
		case 'tel':
		case 'email':
		default:
			echo '<input type="'.esc_attr($type).'" name="'.esc_attr( $name ).'" id="'.esc_attr( $id ).'" value="'.esc_attr( $meta ).'" class="regular-text" size="30" />
					<span class="description">'.esc_attr($desc).'</span>';
		break;
		case 'chapter':
			//echo '<h3>'.esc_attr( $label ).'</h3>';
		break;
		case 'time':
			echo '<input type="'.esc_attr($type).'" name="'.esc_attr( $name ).'" id="'.esc_attr( $id ).'" value="'.esc_attr( $meta ).'" class="qwTimePicker" size="10" />
					<span class="description">'.esc_attr($desc).'</span>';
		break;
		case 'iconchoice':
			
			echo '<input type="text" name="'.esc_attr( $name ).'" id="'.esc_attr( $id ).'" value="'.esc_attr( $meta ).'" class="regular-text" size="30" />
					<span class="description">'.esc_attr($desc).'</span>';
			$icon = "qticon-empty";
			if( esc_attr( $meta ) != ''){
				$icon = esc_attr( $meta );
			}
			echo '<span class="'.esc_attr($icon).' bigicon" id="theIcon'.esc_attr( $id ).'"></span>';
			echo '<a href="#" class="qw-iconreference-open button button-primary" data-target="'.esc_attr( $id ).'">CHOSE ICON</a>';
		break;
		case 'url':
			echo '<input type="'.esc_attr($type).'" name="'.esc_attr( $name ).'" id="'.esc_attr( $id ).'" value="'.esc_attr( $meta ).'" class="regular-text" size="30" />
					<span class="description">'.esc_attr($desc).'</span>';
		break;
		case 'number':
			echo '<input type="'.esc_attr($type).'" name="'.esc_attr( $name ).'" id="'.esc_attr( $id ).'" value="'.intval( $meta ).'" class="regular-text" size="10" />
					<span class="description">'.esc_attr($desc).'</span>';
		break;
		// textarea
		case 'textarea':
			echo '<textarea name="'.esc_attr( $name ).'" id="'.esc_attr( $id ).'" cols="60" rows="4">'.esc_textarea( $meta ).'</textarea>
					<br /><span class="description">'.esc_attr($desc).'</span>';
		break;
		// editor
		case 'editor':
		 $settings = array();
			echo wp_editor( $meta, $id, $settings ).'<br />'. esc_attr($desc);
		break;
		// checkbox
		case 'checkbox':
			echo '<input type="checkbox" name="'.esc_attr( $name ).'" id="'.esc_attr( $id ).'" '.esc_attr(checked( $meta, true, false )).' value="1" />
					<span for="'.esc_attr( $id ).'">'. esc_attr($desc). '</span>';
		break;
		// select, chosen
		case 'select':
		case 'chosen':





			echo '<select name="'.esc_attr( $name ).'" id="'.esc_attr( $id ).'"' , $type == 'chosen' ? ' class="chosen"' : '' , isset( $multiple ) && $multiple == true ? ' multiple="multiple"' : '' , '>
					<option value="">Select One</option>'; // Select One
			foreach ( $options as $option ){
				$toreveal='';
				if(array_key_exists('revealfields', $option)){
					if(is_array($option['revealfields'])){
						$n=0;
						foreach ($option['revealfields'] as $t){
							
							$toreveal.=trim($t)."[+]";
						}
					}
				}

				$tohide='';
				if(array_key_exists('hidefields', $option)){
					if(is_array($option['hidefields'])){
						$n=0;
						foreach ($option['hidefields'] as $t){
							
							if($n<count($option['hidefields']) && $n > 0){
								$tohide .= "[+]";
							}

							$tohide.=trim($t);
							$n++;

						}
					}
				}
				echo '<option'.selected( $meta, $option['value'], false ).' value="'.esc_attr($option['value']).'" data-toreveal ="'.esc_attr($toreveal).'" data-tohide ="'.esc_attr($tohide).'" >'.esc_attr($option['label']).'</option>';

			}
			echo '</select><br />'.wp_kses_post($desc);






		break;
		// radio
		case 'radio':
			echo '<ul class="meta_box_items">';
			foreach ( $options as $option )
				echo '<li><input type="radio" name="'.esc_attr( $name ).'" id="'.esc_attr( $id ).'-'.esc_attr($option['value']).'" value="'.esc_attr($option['value']).'" '.esc_attr(checked( $meta, $option['value'], false )).' />
						<label for="'.esc_attr( $id ).'-'.esc_attr($option['value']).'">'.esc_attr($option['label']).'</label></li>';
			echo '</ul><span class="description">'.esc_attr($desc).'</span>';
		break;
		// checkbox_group
		case 'checkbox_group':
			echo '<ul class="meta_box_items">';
			foreach ( $options as $option )
				echo '<li><input type="checkbox" value="'.esc_attr($option['value']).'" name="'.esc_attr( $name ).'[]" id="'.esc_attr( $id ).'-'.esc_attr($option['value']).'"' , is_array( $meta ) && in_array( $option['value'], $meta ) ? ' checked="checked"' : '' , ' /> 
						<label for="'.esc_attr( $id ).'-'.esc_attr($option['value']).'">'.esc_attr($option['label']).'</label></li>';
			echo '</ul><span class="description">'.esc_attr($desc).'</span>';
		break;
		// color
		case 'color':
			$meta = $meta ? $meta : '#';
			echo '<input type="text" class="meta_box_color" name="'.esc_attr( $name ).'" id="'.esc_attr( $id ).'" value="'.esc_attr($meta).'" size="10" />
				<br /><span class="description">'.esc_attr($desc).'</span>';
			
		break;
		// post_select, post_chosen
		case 'post_select':
		case 'post_list':
		case 'post_chosen':
			echo '<select data-placeholder="Select One" name="'.esc_attr( $name ).'[]" id="'.esc_attr( $id ).'"' , $type == 'post_chosen' ? ' class="chosen"' : '' , isset( $multiple ) && $multiple == true ? ' multiple="multiple"' : '' , '>
					<option value=""></option>'; // Select One
			$posts = get_posts( array( 'post_type' => $posttype, 'posts_per_page' => -1, 'orderby' => 'name', 'order' => 'ASC' ) );
			foreach ( $posts as $item )
				echo '<option value="'.esc_attr($item->ID).'"'.esc_attr(selected( in_array( $item->ID, $meta ), true, false )).'>'.esc_attr($item->post_title).'</option>';
			$post_type_object = get_post_type_object( $post_type );
			echo '</select> ';
		break;


		case 'category':
			

			$wp_dropdown_categories = wp_dropdown_categories( array( 'post_type' => $posttype, 
				'posts_per_page' => -1, 
				'orderby' => 'name', 
				'order' => 'ASC' , 
				'echo' => 0 , 
				'name' => esc_attr( $name ),
				'id' => esc_attr( $id ),
				'hide_if_empty' => true,
				'value_field' => 'term_id',
				'selected' => esc_attr($meta)

				) );

			echo $wp_dropdown_categories;
			echo '<span class="description">'.esc_attr($desc).'</span>';
		break;


		// post_checkboxes
		case 'post_checkboxes':
			$posts = get_posts( array( 'post_type' => $posttype, 'posts_per_page' => -1 ) );
			echo '<ul class="meta_box_items">';
			foreach ( $posts as $item ) 
				echo '<li><input type="checkbox" value="'.esc_attr($item->ID).'" name="'.esc_attr( $name ).'[]" id="'.esc_attr( $id ).'-'.esc_attr($item->ID).'"' , is_array( $meta ) && in_array( $item->ID, $meta ) ? ' checked="checked"' : '' , ' />
						<label for="'.esc_attr( $id ).'-'.esc_attr($item->ID).'">'.esc_attr($item->post_title).'</label></li>';
			$post_type_object = get_post_type_object( $posttype );
			echo '</ul> <span class="description">'.esc_attr($desc).'</span> &nbsp;<span class="description"></span>';
		break;
		// post_drop_sort
		case 'post_drop_sort':
			//areas
			$post_type_object = get_post_type_object( $posttype );
			echo '<p><span class="description">'.esc_attr($desc).'</span> &nbsp;<span class="description"><a href="'.admin_url( 'edit.php?post_type='.esc_attr($posttype).'">Manage '.esc_attr($post_type_object->label) ).'</a></span></p><div class="post_drop_sort_areas">';
			foreach ( $areas as $area ) {
				echo '<ul id="area-'.esc_attr($area['id']) .'" class="sort_list">
						<li class="post_drop_sort_area_name">'.esc_attr($area['label']).'</li>';
						if ( is_array( $meta ) ) {
							$items = explode( ',', $meta[$area['id']] );
							foreach ( $items as $item ) {
								
								echo '<li id="'.esc_attr($item).'">'.esc_attr( get_the_title( $item )).'</li>';
							}
						}
				echo '</ul>
					<input type="hidden" name="'.esc_attr( $name ).'['.esc_attr($area['id']).']" 
					class="store-area-'.esc_attr($area['id']).'" 
					value="' , $meta ? esc_attr($meta[$area['id']]) : '' , '" />';
			}
			echo '</div>';
			// source
			$exclude = null;
			if ( !empty( $meta ) ) {
				$exclude = implode( ',', $meta ); // because each ID is in a unique key
				$exclude = explode( ',', $exclude ); // put all the ID's back into a single array
			}
			$posts = get_posts( array( 'post_type' => $posttype, 'posts_per_page' => -1, 'post__not_in' => $exclude ) );
			echo '<ul class="post_drop_sort_source sort_list">
					<li class="post_drop_sort_area_name">Available '.esc_attr($label).'</li>';
			foreach ( $posts as $item ) {
				$output = $display == 'thumbnail' ? get_the_post_thumbnail( $item->ID, array( 204, 30 ) ) : get_the_title( $item->ID ); 
				echo '<li id="'.esc_attr($item->ID).'">'.esc_attr(get_the_title( $item->ID )).'</li>';
			}
			echo '</ul>';
		break;
		// tax_select
		case 'tax_select':
			echo '<select name="'.esc_attr( $name ).'" id="'.esc_attr( $id ).'">
					<option value="">Select One</option>'; // Select One
			$terms = get_terms( $id, 'get=all' );
			$post_terms = wp_get_object_terms( get_the_ID(), $id );
			$selected= $post_terms ? $terms[0]->slug : null;
			foreach ( $terms as $term )
				echo '<option value="'.esc_attr($term->slug).'"'.esc_attr(selected( $selected, $term->slug, false )).'>'.esc_attr($term->name).'</option>'; 
			$taxonomy = get_taxonomy( $id );
			echo '</select> &nbsp;<span class="description"><a href="'. esc_url( home_url() ).'/wp-admin/edit-tags.php?taxonomy='.esc_attr($id).'">Manage '.esc_attr($taxonomy->label).'</a></span>
				<br /><span class="description">'.esc_attr($desc).'</span>';
		break;
		// tax_checkboxes
		case 'tax_checkboxes':
			$terms = get_terms( $id, 'get=all' );
			$post_terms = wp_get_object_terms( get_the_ID(), $id );
			$checked = $post_terms ? $terms[0]->slug : null;
			foreach ( $terms as $term)
				echo '<input type="checkbox" value="'.esc_attr($term->slug).'" name="'.esc_attr($id).'[]" id="'.esc_attr($term->slug).'"'.esc_attr(checked( $checked, $term->slug, false )).' /> <label for="'.esc_attr($term->slug).'">'.esc_attr($term->name).'</label><br />';
			$taxonomy = get_taxonomy( $id);
			echo '<span class="description">'.esc_attr($field['desc']).' <a href="'. esc_url( home_url() ).'/wp-admin/edit-tags.php?taxonomy='.esc_attr($id).'&post_type='.esc_attr($page).'">Manage '.esc_attr($taxonomy->label).'</a></span>';
		break;
		// date
		case 'date':
			echo '<input type="text" class="datepicker" name="'.esc_attr( $name ).'" id="'.esc_attr( $id ).'" value="'.esc_attr($meta).'" size="30" />
					<br /><span class="description">'.esc_attr($desc).'</span>';
		break;
		// slider
		case 'slider':
		$value = $meta != '' ? intval( $meta ) : '0';
			echo '<div id="'.esc_attr( $id ).'-slider"></div>
					<input type="text" name="'.esc_attr( $name ).'" id="'.esc_attr( $id ).'" value="'.esc_attr($value).'" size="5" />
					<br /><span class="description">'.esc_attr($desc).'</span>';
		break;
		// image
		case 'image':
			$image = CUSTOM_METABOXES_DIR.'/images/image.png';	
			echo '<span class="meta_box_default_image" style="display:none">'.esc_attr($image).'</span>';
			if ( $meta ) {
				$image = wp_get_attachment_image_src( intval( $meta ), 'medium' );
				$image = $image[0];
			}				
			echo	'<input name="'.esc_attr( $name ).'" type="hidden" class="meta_box_upload_image" value="'.intval( $meta ).'" />
						<img src="'.esc_url(esc_attr( $image )).'" class="meta_box_preview_image" alt="" />
							<a href="#" class="meta_box_upload_image_button button" rel="'.get_the_ID().'">Choose Image</a>
							<small>&nbsp;<a href="#" class="meta_box_clear_image_button">Remove Image</a></small>
							<br clear="all" /><span class="description">'.esc_attr($desc).'</span>';
		break;
		// file
		case 'file':		
			$iconClass = 'meta_box_file';
			if ( $meta ) $iconClass .= ' checked';
			echo	'<input name="'.esc_attr( $name ).'" type="text" class="meta_box_upload_file regular-text" value="'.esc_url( $meta ).'" />
						<span class="'.esc_attr($iconClass).'"></span>
						<span class="meta_box_filename">'.esc_url( $meta ).'</span>
							<input class="meta_box_upload_file_button button" type="button" rel="'.get_the_ID().'" value="Add" />
							<small>&nbsp;<a href="#" class="meta_box_clear_file_button">Remove File</a></small>
							<br clear="all" /><span class="description">'.esc_attr($desc).'</span>';
		break;






		/* By Qantumthemes 
		======================================================*/


		case 'pageselect':
			if($posttype == null){$posttype = 'page';}
			$posttype =  $posttype ;

			if ( !post_type_exists( $posttype ) ) {
				echo 'Error: this post type doesn\'t exists';
			}
			$args = array(
				'echo'             	=> 1,
				'post_type' 		=> esc_attr($posttype),
				'name'             	=> esc_attr( $name ),
				'show_option_none' 	=> 'Select'
			);
			if(isset( $meta )){
				$args['selected'] = esc_attr( $meta );
			}
			wp_dropdown_pages($args);
		break;


		/* Coordinates By Qantumthemes 
		======================================================*/


		case 'coordinates':
		?>
			<div class="qw-map-field">
				<div class="qw-mapform">
				    Address to geocode: <br>
				    <input class="qt-address" id="address-<?php echo esc_attr( $id ); ?>" type="textbox" value="">
				    <input class="submit btn button geocodefunction" data-target="<?php echo esc_attr( $id ); ?>" type="button" value="Geocode this address">
				    <div id="results-<?php echo esc_attr( $id ); ?>"></div>
			    </div>
				<?php
				echo '<input type="'.esc_attr($type).'" name="'.esc_attr( $name ).'" id="'.esc_attr( $id ).'" value="'.esc_attr( $meta ).'" class="regular-text" size="30" />
					<span class="description">'.esc_attr($desc).'</span>';
				?>
				<div class="qw-map-container" id="map-<?php echo esc_attr( $id ); ?>"></div>

			</div><?php  
			
		break;


		/* repeatable
		======================================================*/

		case 'repeatable':
			echo '<table id="'.esc_attr( $id ).'-repeatable" class="meta_box_repeatable open" cellspacing="0">
				<thead>
					<tr>
						<th><span class="sort_label"></span></th>
						<th>Fields</th>
						<th><a class="meta_box_repeatable_add" href="#"></a></th>
					</tr>
				</thead>
				<tbody>';
			$i = 0;
			// create an empty array
			if ( $meta == '' || $meta == array() ) {
				$keys = wp_list_pluck( $repeatable_fields, 'id' );
				$meta = array ( array_fill_keys( $keys, null ) );
			}
			$meta = array_values( $meta );
			foreach( $meta as $row ) {
				echo '<tr class="qw_hiddenable">
						<td>
							<span class="dashicons dashicons-sort hndle repeatable-button"></span>
							<span class="dashicons dashicons-visibility qw_hider repeatable-button"></span>
						</td>
						
						<td class="qw_tohide">';


				foreach ( $repeatable_fields as $repeatable_field ) {
					if ( ! array_key_exists( $repeatable_field['id'], $meta[$i] ) )
						$meta[$i][$repeatable_field['id']] = null;
					echo '<label>'.esc_attr($repeatable_field['label']).'</label><p>';


					custom_meta_box_field( $repeatable_field, $meta[$i][$repeatable_field['id']], array( $id, $i ) );

					echo '</p>';
				} // end each field
				echo '</td><td><a class="meta_box_repeatable_remove" href="#"></a></td></tr>';
				$i++;
			} // end each row
			echo '</tbody>';
			echo '
				<tfoot>
					<tr>
						<th><span class="sort_label"></span></th>
						<th>Fields</th>
						<th><a class="meta_box_repeatable_add" href="#"></a></th>
					</tr>
				</tfoot>';
			echo '</table>
				<span class="description">'.esc_attr($desc).'</span>';
		break;
	} //end switch
		
}


/**
 * Finds any item in any level of an array
 *
 * @param	string	$needle 	field type to look for
 * @param	array	$haystack	an array to search the type in
 *
 * @return	bool				whether or not the type is in the provided array
 */
function meta_box_find_field_type( $needle, $haystack ) {
	foreach ( $haystack as $h )
		if ( isset( $h['type'] ) && $h['type'] == 'repeatable' )
			return meta_box_find_field_type( $needle, $h['repeatable_fields'] );
		elseif ( ( isset( $h['type'] ) && $h['type'] == $needle ) || ( isset( $h['repeatable_type'] ) && $h['repeatable_type'] == $needle ) )
			return true;
	return false;
}

/**
 * Find repeatable
 *
 * This function does almost the same exact thing that the above function 
 * does, except we're exclusively looking for the repeatable field. The 
 * reason is that we need a way to look for other fields nested within a 
 * repeatable, but also need a way to stop at repeatable being true. 
 * Hopefully I'll find a better way to do this later.
 *
 * @param	string	$needle 	field type to look for
 * @param	array	$haystack	an array to search the type in
 *
 * @return	bool				whether or not the type is in the provided array
 */
function meta_box_find_repeatable( $needle, $haystack ) {
	foreach ( $haystack as $h )
		if ( isset( $h['type'] ) && $h['type'] == 'repeatable' )
			return true;
		else
			return false;
}

/**
 * sanitize boolean inputs
 */
function meta_box_santitize_boolean( $string ) {
	if ( ! isset( $string ) || $string != 1 || $string != true )
		return false;
	else
		return true;
}

/**
 * outputs properly sanitized data
 *
 * @param	string	$string		the string to run through a validation function
 * @param	string	$function	the validation function
 *
 * @return						a validated string
 */
function meta_box_sanitize( $string, $function = 'sanitize_text_field' ) {
	switch ( $function ) {
		case 'intval':
			return intval( $string );
		case 'absint':
			return absint( $string );
		case 'wp_kses_post':
			return wp_kses_post( $string );
		case 'wp_kses_data':
			return wp_kses_data( $string );
		case 'esc_url_raw':
			return esc_url_raw( $string );
		case 'is_email':
			return is_email( $string );
		case 'sanitize_title':
			return sanitize_title( $string );
		case 'santitize_boolean':
			return santitize_boolean( $string );
		case 'sanitize_text_field':
		default:
			return $string;
			//return sanitize_text_field( $string );
	}
}

/**
 * Map a multideminsional array
 *
 * @param	string	$func		the function to map
 * @param	array	$meta		a multidimensional array
 * @param	array	$sanitizer	a matching multidimensional array of sanitizers
 *
 * @return	array				new array, fully mapped with the provided arrays
 */
function meta_box_array_map_r( $func, $meta, $sanitizer ) {
		
	$newMeta = array();
	$meta = array_values( $meta );
	
	foreach( $meta as $key => $array ) {
		if ( $array == '' )
			continue;
		/**
		 * some values are stored as array, we only want multidimensional ones
		 */
		if ( ! is_array( $array ) ) {
			return array_map( $func, $meta, (array)$sanitizer );
			break;
		}
		/**
		 * the sanitizer will have all of the fields, but the item may only 
		 * have valeus for a few, remove the ones we don't have from the santizer
		 */
		$keys = array_keys( $array );
		$newSanitizer = $sanitizer;
		if ( is_array( $sanitizer ) ) {
			foreach( $newSanitizer as $sanitizerKey => $value )
				if ( ! in_array( $sanitizerKey, $keys ) )
					unset( $newSanitizer[$sanitizerKey] );
		}
		/**
		 * run the function as deep as the array goes
		 */
		foreach( $array as $arrayKey => $arrayValue )
			if ( is_array( $arrayValue ) ){
				if(array_key_exists($arrayKey, $array)){
				//	$array[$arrayKey] = meta_box_array_map_r( $func, $arrayValue, $newSanitizer[$arrayKey] );
				}
			}
		
		$array = array_map( $func, $array, $newSanitizer );
		$newMeta[$key] = array_combine( $keys, array_values( $array ) );
	}
	return $newMeta;
}

/**
 * takes in a few peices of data and creates a custom meta box
 *
 * @param	string			$id			meta box id
 * @param	string			$title		title
 * @param	array			$fields		array of each field the box should include
 * @param	string|array	$page		post type to add meta box to
 */
class Custom_Add_Meta_Box {
	
	var $id;
	var $title;
	var $fields;
	var $page;
	
    public function __construct( $id, $title, $fields, $page, $js ) {
		$this->id = $id;
		$this->title = $title;
		$this->fields = $fields;
		$this->page = $page;
		$this->js = $js;

		if( ! is_array( $this->page ) )
			$this->page = array( $this->page );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );		
		add_action( 'admin_footer',  array( $this, 'admin_head' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_box' ) );
		add_action( 'save_post',  array( $this, 'save_box' ));
    }





    /**
	 * adds scripts to the head for special fields with extra js requirements
	 */
	function admin_head() {
		if ( in_array( get_post_type(), $this->page ) && ( meta_box_find_field_type( 'date', $this->fields ) || meta_box_find_field_type( 'slider', $this->fields ) ) ) {
		
			echo '<script type="text/javascript">
						jQuery(function($) {';
			
			foreach ( $this->fields as $field ) {
				switch( $field['type'] ) {
					// date
					case 'date' :
						echo '
							if(jQuery("#'.esc_js(esc_attr($field['id'])).'").length > 0){
							jQuery("#'.esc_js(esc_attr($field['id'])).'").datepicker({
								dateFormat: \'yy-mm-dd\'
							});}';
					break;
					// slider
					case 'slider' :
					$value = get_post_meta( get_the_ID(), $field['id'], true );
					if ( $value == '' )
						$value = $field['min'];
					echo '
							$( "#'.esc_attr($field['id']).'-slider" ).slider({
								value: '.esc_js(esc_attr($value)).',
								min: '.esc_js(esc_attr($field['min'])).',
								max: '.esc_js(esc_attr($field['max'])).',
								step: '.esc_js(esc_attr($field['step'])).',
								slide: function( event, ui ) {
									$( "#'.esc_js(esc_attr($field['id'])).'" ).val( ui.value );
								}
							});';
					break;
				}
			}
			
			echo '});
				</script>';
		
		}
	}



	
	/**
	 * enqueue necessary scripts and styles
	 */
	function admin_enqueue_scripts() {
		global $pagenow;
		if ( in_array( $pagenow, array( 'post-new.php', 'post.php' ) ) && in_array( get_post_type(), $this->page ) ) {
			// js
			$deps = array( 'jquery' );

			


			if ( meta_box_find_field_type( 'date', $this->fields ) )
				$deps[] = 'jquery-ui-datepicker';
			if ( meta_box_find_field_type( 'slider', $this->fields ) )
				$deps[] = 'jquery-ui-slider';
			if ( meta_box_find_field_type( 'color', $this->fields ) )
				$deps[] = 'farbtastic';
				$deps[] = 'wp-color-picker';
			if ( in_array( true, array(
				meta_box_find_field_type( 'chosen', $this->fields ),
				meta_box_find_field_type( 'post_chosen', $this->fields )
			) ) ) {
				wp_register_script( 'chosen', CUSTOM_METABOXES_DIR.'/js/chosen.js', array( 'jquery' ) );
				$deps[] = 'chosen';
				wp_enqueue_style( 'chosen', CUSTOM_METABOXES_DIR.'/css/chosen.css' );
			}
			

			$deps[] = 'jquery';
				wp_enqueue_script( 'meta_box', CUSTOM_METABOXES_DIR.'/js/metaboxes-scripts.js', $deps );

			if ( in_array( true, array(  meta_box_find_field_type( 'coordinates', $this->fields ) ) ) ) {
		
				$mapsurl = 'https://maps.googleapis.com/maps/api/js';
				$key = get_theme_mod("qt_maps_api", false);
				if($key != '') {
					$mapsurl = add_query_arg("key", esc_attr(trim($key)), $mapsurl);
				}
				wp_enqueue_script('qt-google-maps',$mapsurl, $deps, false, true);
			}


			
			// Creates the footer things like icon menu
			//qw_admin_footer_function();

			
			// css
			$deps = array();
			wp_register_style( 'jqueryui', CUSTOM_METABOXES_DIR.'/css/jqueryui.css' );
			if ( meta_box_find_field_type( 'date', $this->fields ) || meta_box_find_field_type( 'slider', $this->fields ) )
				$deps[] = 'jqueryui';
			if ( meta_box_find_field_type( 'color', $this->fields ) )
				$deps[] = 'farbtastic';
			wp_enqueue_style( 'meta_box', CUSTOM_METABOXES_DIR.'/css/meta_box.css', $deps );
			 wp_enqueue_style( 'wp-color-picker' );
		}
	}




	
	


	
	/**
	 * adds the meta box for every post type in $page
	 */
	function add_box() {


		/*________________________________________________
		*
		*	Aggiunto 11 Ottobre 2014: permette di aggiungere campi extra solo su determinate pagine con un certo template
		*  	Il template deve essere specificato nell'array degli arcomenti
		*
		*/
		$fields = $this->fields;
		$fields = $fields[0];
		$template = isset( $fields['template'] ) ? $fields['template'] : null;
		$currentTemplate =  basename( get_page_template() );
		if($template != null){
			if($currentTemplate != $template){
				return ;
			}
		}
		/*_____________________ fine_____________________*/




		foreach ( $this->page as $page ) {
			$fields = $this->fields;

			add_meta_box( $this->id, $this->title, array( $this, 'meta_box_callback' ), $page, 'normal', 'high' );
		}
	}







	
	/**
	 * outputs the meta box
	 */
	function meta_box_callback() {
		// Use nonce for verification
		wp_nonce_field( 'custom_meta_box_nonce_action', 'custom_meta_box_nonce_field' );
		
		// Begin the field table and loop



		echo '<table class="form-table meta_box">';
		foreach ( $this->fields as $field) {

			if(!array_key_exists('pagetemplate', $field)) {
					$field['pagetemplate']= '';
			}


			$template = get_page_template_slug( get_the_ID() );
			
			if($field['pagetemplate'] == $template || $field['pagetemplate'] == '') {
				if ( $field['type'] == 'section' ) {
					echo '<tr>
							<td colspan="2" class="qt-admin_sectiontitle">
								<h2 class="qt-admin_sectiontitle">'.esc_attr($field['label']).'</h2>
							</td>
						</tr>';
				}
				else {
					$class='';
					$boxid='';
					if(!array_key_exists('class', $field)){
						$field['class'] = '';
					}
					if(!array_key_exists('containerid', $field)){
						$field['containerid'] = '';
					}
					echo '<tr class="'.esc_attr($field['class']).'" id="'.esc_attr($field['containerid']).'">';
					if($field['type'] == 'chapter'){
						echo '<td colspan="2"><h2>'.esc_attr($field['label']).'</h2><td>';
					} else {
						
						echo '<th style="width:20%">';
							echo '<label for="'.esc_attr($field['id']).'">'.esc_attr($field['label']).'</label>';
						echo'</th>
								<td>';
								$meta = get_post_meta( get_the_ID(), $field['id'], true);
								echo custom_meta_box_field( $field, $meta );
						echo    '<td>';
					}
					echo '</tr>';
					
				}
			}





		} // end foreach
		echo '</table>'; // end table
	}
	
	/**
	 * saves the captured data
	 */
	function save_box( $post_id ) {
		$post_type = get_post_type();
		
		// verify nonce
		if ( ! isset( $_POST['custom_meta_box_nonce_field'] ) )
			return $post_id;
		if ( ! ( in_array( $post_type, $this->page ) || wp_verify_nonce( $_POST['custom_meta_box_nonce_field'],  'custom_meta_box_nonce_action' ) ) ) 
			return $post_id;
		// check autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return $post_id;
		// check permissions
		if ( ! current_user_can( 'edit_page', $post_id ) )
			return $post_id;
		
		// loop through fields and save the data
		foreach ( $this->fields as $field ) {
			if( $field['type'] == 'section' ) {
				$sanitizer = null;
				continue;
			}
			if( in_array( $field['type'], array( 'tax_select', 'tax_checkboxes' ) ) ) {
				// save taxonomies
				if ( isset( $_POST[$field['id']] ) )
					$term = $_POST[$field['id']];
				wp_set_object_terms( $post_id, $term, $field['id'] );
			}
			else {
				// save the rest
				$new = false;
				$old = get_post_meta( $post_id, $field['id'], true );
				if ( isset( $_POST[$field['id']] ) )
					$new = $_POST[$field['id']];

				if($field['type'] == 'repeatable' && is_array($new)) $new = array_values($new);
				
				if ( isset( $new ) && $new != $old ) {
					$sanitizer = isset( $field['sanitizer'] ) ? $field['sanitizer'] : 'sanitize_text_field';
					if ( is_array( $new ) )
						$new = meta_box_array_map_r( 'meta_box_sanitize', $new, $sanitizer );
					else
						$new = meta_box_sanitize( $new, $sanitizer );
					update_post_meta( $post_id, $field['id'], $new );
				} elseif ( isset( $new ) && '' == $new && $old ) {
					delete_post_meta( $post_id, $field['id'], $old );
				}
			}
		} // end foreach
	}
	
}




/**
 *
 *
 *
 *	Icon Choice Window
 *
 * 
 */
if(is_admin()){
	function qw_admin_footer_function() {
		ob_start();
		?>
		<div id="qwModalForm">
			<div class="container" id="qwiconsMarket">
				<div class="qt-tabs">
					<p class="qt-tabnav">
						<a href="#materialicons">Google Material Design Icons</a> | 
						<a href="#faicons">FontAwesome Icons</a>
					<p>
					<div class="qt-tab active" id="materialicons">
						<?php
						$mdi_icons = array( 'mdi-action-3d-rotation','mdi-action-accessibility','mdi-action-account-balance-wallet','mdi-action-account-balance','mdi-action-account-box','mdi-action-account-child','mdi-action-account-circle','mdi-action-add-shopping-cart','mdi-action-alarm-add','mdi-action-alarm-off','mdi-action-alarm-on','mdi-action-alarm','mdi-action-android','mdi-action-announcement','mdi-action-aspect-ratio','mdi-action-assessment','mdi-action-assignment-ind','mdi-action-assignment-late','mdi-action-assignment-return','mdi-action-assignment-returned','mdi-action-assignment-turned-in','mdi-action-assignment','mdi-action-autorenew','mdi-action-backup','mdi-action-book','mdi-action-bookmark-outline','mdi-action-bookmark','mdi-action-bug-report','mdi-action-cached','mdi-action-check-circle','mdi-action-class','mdi-action-credit-card','mdi-action-dashboard','mdi-action-delete','mdi-action-description','mdi-action-dns','mdi-action-done-all','mdi-action-done','mdi-action-event','mdi-action-exit-to-app','mdi-action-explore','mdi-action-extension','mdi-action-face-unlock','mdi-action-favorite-outline','mdi-action-favorite','mdi-action-find-in-page','mdi-action-find-replace','mdi-action-flip-to-back','mdi-action-flip-to-front','mdi-action-get-app','mdi-action-grade','mdi-action-group-work','mdi-action-help','mdi-action-highlight-remove','mdi-action-history','mdi-action-home','mdi-action-https','mdi-action-info-outline','mdi-action-info','mdi-action-input','mdi-action-invert-colors','mdi-action-label-outline','mdi-action-label','mdi-action-language','mdi-action-launch','mdi-action-list','mdi-action-lock-open','mdi-action-lock-outline','mdi-action-lock','mdi-action-loyalty','mdi-action-markunread-mailbox','mdi-action-note-add','mdi-action-open-in-browser','mdi-action-open-in-new','mdi-action-open-with','mdi-action-pageview','mdi-action-payment','mdi-action-perm-camera-mic','mdi-action-perm-contact-cal','mdi-action-perm-data-setting','mdi-action-perm-device-info','mdi-action-perm-identity','mdi-action-perm-media','mdi-action-perm-phone-msg','mdi-action-perm-scan-wifi','mdi-action-picture-in-picture','mdi-action-polymer','mdi-action-print','mdi-action-query-builder','mdi-action-question-answer','mdi-action-receipt','mdi-action-redeem','mdi-action-reorder','mdi-action-report-problem','mdi-action-restore','mdi-action-room','mdi-action-schedule','mdi-action-search','mdi-action-settings-applications','mdi-action-settings-backup-restore','mdi-action-settings-bluetooth','mdi-action-settings-cell','mdi-action-settings-display','mdi-action-settings-ethernet','mdi-action-settings-input-antenna','mdi-action-settings-input-component','mdi-action-settings-input-composite','mdi-action-settings-input-hdmi','mdi-action-settings-input-svideo','mdi-action-settings-overscan','mdi-action-settings-phone','mdi-action-settings-power','mdi-action-settings-remote','mdi-action-settings-voice','mdi-action-settings','mdi-action-shop-two','mdi-action-shop','mdi-action-shopping-basket','mdi-action-shopping-cart','mdi-action-speaker-notes','mdi-action-spellcheck','mdi-action-star-rate','mdi-action-stars','mdi-action-store','mdi-action-subject','mdi-action-supervisor-account','mdi-action-swap-horiz','mdi-action-swap-vert-circle','mdi-action-swap-vert','mdi-action-system-update-tv','mdi-action-tab-unselected','mdi-action-tab','mdi-action-theaters','mdi-action-thumb-down','mdi-action-thumb-up','mdi-action-thumbs-up-down','mdi-action-toc','mdi-action-today','mdi-action-track-changes','mdi-action-translate','mdi-action-trending-down','mdi-action-trending-neutral','mdi-action-trending-up','mdi-action-turned-in-not','mdi-action-turned-in','mdi-action-verified-user','mdi-action-view-agenda','mdi-action-view-array','mdi-action-view-carousel','mdi-action-view-column','mdi-action-view-day','mdi-action-view-headline','mdi-action-view-list','mdi-action-view-module','mdi-action-view-quilt','mdi-action-view-stream','mdi-action-view-week','mdi-action-visibility-off','mdi-action-visibility','mdi-action-wallet-giftcard','mdi-action-wallet-membership','mdi-action-wallet-travel','mdi-action-work','mdi-alert-error','mdi-alert-warning','mdi-av-album','mdi-av-closed-caption','mdi-av-equalizer','mdi-av-explicit','mdi-av-fast-forward','mdi-av-fast-rewind','mdi-av-games','mdi-av-hearing','mdi-av-high-quality','mdi-av-loop','mdi-av-mic-none','mdi-av-mic-off','mdi-av-mic','mdi-av-movie','mdi-av-my-library-add','mdi-av-my-library-books','mdi-av-my-library-music','mdi-av-new-releases','mdi-av-not-interested','mdi-av-pause-circle-fill','mdi-av-pause-circle-outline','mdi-av-pause','mdi-av-play-arrow','mdi-av-play-circle-fill','mdi-av-play-circle-outline','mdi-av-play-shopping-bag','mdi-av-playlist-add','mdi-av-queue-music','mdi-av-queue','mdi-av-radio','mdi-av-recent-actors','mdi-av-repeat-one','mdi-av-repeat','mdi-av-replay','mdi-av-shuffle','mdi-av-skip-next','mdi-av-skip-previous','mdi-av-snooze','mdi-av-stop','mdi-av-subtitles','mdi-av-surround-sound','mdi-av-timer','mdi-av-video-collection','mdi-av-videocam-off','mdi-av-videocam','mdi-av-volume-down','mdi-av-volume-mute','mdi-av-volume-off','mdi-av-volume-up','mdi-av-web','mdi-communication-business','mdi-communication-call-end','mdi-communication-call-made','mdi-communication-call-merge','mdi-communication-call-missed','mdi-communication-call-received','mdi-communication-call-split','mdi-communication-call','mdi-communication-chat','mdi-communication-clear-all','mdi-communication-comment','mdi-communication-contacts','mdi-communication-dialer-sip','mdi-communication-dialpad','mdi-communication-dnd-on','mdi-communication-email','mdi-communication-forum','mdi-communication-import-export','mdi-communication-invert-colors-off','mdi-communication-invert-colors-on','mdi-communication-live-help','mdi-communication-location-off','mdi-communication-location-on','mdi-communication-message','mdi-communication-messenger','mdi-communication-no-sim','mdi-communication-phone','mdi-communication-portable-wifi-off','mdi-communication-quick-contacts-dialer','mdi-communication-quick-contacts-mail','mdi-communication-ring-volume','mdi-communication-stay-current-landscape','mdi-communication-stay-current-portrait','mdi-communication-stay-primary-landscape','mdi-communication-stay-primary-portrait','mdi-communication-swap-calls','mdi-communication-textsms','mdi-communication-voicemail','mdi-communication-vpn-key','mdi-content-add-box','mdi-content-add-circle-outline','mdi-content-add-circle','mdi-content-add','mdi-content-archive','mdi-content-backspace','mdi-content-block','mdi-content-clear','mdi-content-content-copy','mdi-content-content-cut','mdi-content-content-paste','mdi-content-create','mdi-content-drafts','mdi-content-filter-list','mdi-content-flag','mdi-content-forward','mdi-content-gesture','mdi-content-inbox','mdi-content-link','mdi-content-mail','mdi-content-markunread','mdi-content-redo','mdi-content-remove-circle-outline','mdi-content-remove-circle','mdi-content-remove','mdi-content-reply-all','mdi-content-reply','mdi-content-report','mdi-content-save','mdi-content-select-all','mdi-content-send','mdi-content-sort','mdi-content-text-format','mdi-content-undo','mdi-editor-attach-file','mdi-editor-attach-money','mdi-editor-border-all','mdi-editor-border-bottom','mdi-editor-border-clear','mdi-editor-border-color','mdi-editor-border-horizontal','mdi-editor-border-inner','mdi-editor-border-left','mdi-editor-border-outer','mdi-editor-border-right','mdi-editor-border-style','mdi-editor-border-top','mdi-editor-border-vertical','mdi-editor-format-align-center','mdi-editor-format-align-justify','mdi-editor-format-align-left','mdi-editor-format-align-right','mdi-editor-format-bold','mdi-editor-format-clear','mdi-editor-format-color-fill','mdi-editor-format-color-reset','mdi-editor-format-color-text','mdi-editor-format-indent-decrease','mdi-editor-format-indent-increase','mdi-editor-format-italic','mdi-editor-format-line-spacing','mdi-editor-format-list-bulleted','mdi-editor-format-list-numbered','mdi-editor-format-paint','mdi-editor-format-quote','mdi-editor-format-size','mdi-editor-format-strikethrough','mdi-editor-format-textdirection-l-to-r','mdi-editor-format-textdirection-r-to-l','mdi-editor-format-underline','mdi-editor-functions','mdi-editor-insert-chart','mdi-editor-insert-comment','mdi-editor-insert-drive-file','mdi-editor-insert-emoticon','mdi-editor-insert-invitation','mdi-editor-insert-link','mdi-editor-insert-photo','mdi-editor-merge-type','mdi-editor-mode-comment','mdi-editor-mode-edit','mdi-editor-publish','mdi-editor-vertical-align-bottom','mdi-editor-vertical-align-center','mdi-editor-vertical-align-top','mdi-editor-wrap-text','mdi-file-attachment','mdi-file-cloud-circle','mdi-file-cloud-done','mdi-file-cloud-download','mdi-file-cloud-off','mdi-file-cloud-queue','mdi-file-cloud-upload','mdi-file-cloud','mdi-file-file-download','mdi-file-file-upload','mdi-file-folder-open','mdi-file-folder-shared','mdi-file-folder','mdi-device-access-alarm','mdi-device-access-alarms','mdi-device-access-time','mdi-device-add-alarm','mdi-device-airplanemode-off','mdi-device-airplanemode-on','mdi-device-battery-20','mdi-device-battery-30','mdi-device-battery-50','mdi-device-battery-60','mdi-device-battery-80','mdi-device-battery-90','mdi-device-battery-alert','mdi-device-battery-charging-20','mdi-device-battery-charging-30','mdi-device-battery-charging-50','mdi-device-battery-charging-60','mdi-device-battery-charging-80','mdi-device-battery-charging-90','mdi-device-battery-charging-full','mdi-device-battery-full','mdi-device-battery-std','mdi-device-battery-unknown','mdi-device-bluetooth-connected','mdi-device-bluetooth-disabled','mdi-device-bluetooth-searching','mdi-device-bluetooth','mdi-device-brightness-auto','mdi-device-brightness-high','mdi-device-brightness-low','mdi-device-brightness-medium','mdi-device-data-usage','mdi-device-developer-mode','mdi-device-devices','mdi-device-dvr','mdi-device-gps-fixed','mdi-device-gps-not-fixed','mdi-device-gps-off','mdi-device-location-disabled','mdi-device-location-searching','mdi-device-multitrack-audio','mdi-device-network-cell','mdi-device-network-wifi','mdi-device-nfc','mdi-device-now-wallpaper','mdi-device-now-widgets','mdi-device-screen-lock-landscape','mdi-device-screen-lock-portrait','mdi-device-screen-lock-rotation','mdi-device-screen-rotation','mdi-device-sd-storage','mdi-device-settings-system-daydream','mdi-device-signal-cellular-0-bar','mdi-device-signal-cellular-1-bar','mdi-device-signal-cellular-2-bar','mdi-device-signal-cellular-3-bar','mdi-device-signal-cellular-4-bar','mdi-signal-wifi-statusbar-connected-no-internet-after','mdi-device-signal-cellular-connected-no-internet-0-bar','mdi-device-signal-cellular-connected-no-internet-1-bar','mdi-device-signal-cellular-connected-no-internet-2-bar','mdi-device-signal-cellular-connected-no-internet-3-bar','mdi-device-signal-cellular-connected-no-internet-4-bar','mdi-device-signal-cellular-no-sim','mdi-device-signal-cellular-null','mdi-device-signal-cellular-off','mdi-device-signal-wifi-0-bar','mdi-device-signal-wifi-1-bar','mdi-device-signal-wifi-2-bar','mdi-device-signal-wifi-3-bar','mdi-device-signal-wifi-4-bar','mdi-device-signal-wifi-off','mdi-device-signal-wifi-statusbar-1-bar','mdi-device-signal-wifi-statusbar-2-bar','mdi-device-signal-wifi-statusbar-3-bar','mdi-device-signal-wifi-statusbar-4-bar','mdi-device-signal-wifi-statusbar-connected-no-internet-','mdi-device-signal-wifi-statusbar-connected-no-internet','mdi-device-signal-wifi-statusbar-connected-no-internet-2','mdi-device-signal-wifi-statusbar-connected-no-internet-3','mdi-device-signal-wifi-statusbar-connected-no-internet-4','mdi-signal-wifi-statusbar-not-connected-after','mdi-device-signal-wifi-statusbar-not-connected','mdi-device-signal-wifi-statusbar-null','mdi-device-storage','mdi-device-usb','mdi-device-wifi-lock','mdi-device-wifi-tethering','mdi-hardware-cast-connected','mdi-hardware-cast','mdi-hardware-computer','mdi-hardware-desktop-mac','mdi-hardware-desktop-windows','mdi-hardware-dock','mdi-hardware-gamepad','mdi-hardware-headset-mic','mdi-hardware-headset','mdi-hardware-keyboard-alt','mdi-hardware-keyboard-arrow-down','mdi-hardware-keyboard-arrow-left','mdi-hardware-keyboard-arrow-right','mdi-hardware-keyboard-arrow-up','mdi-hardware-keyboard-backspace','mdi-hardware-keyboard-capslock','mdi-hardware-keyboard-control','mdi-hardware-keyboard-hide','mdi-hardware-keyboard-return','mdi-hardware-keyboard-tab','mdi-hardware-keyboard-voice','mdi-hardware-keyboard','mdi-hardware-laptop-chromebook','mdi-hardware-laptop-mac','mdi-hardware-laptop-windows','mdi-hardware-laptop','mdi-hardware-memory','mdi-hardware-mouse','mdi-hardware-phone-android','mdi-hardware-phone-iphone','mdi-hardware-phonelink-off','mdi-hardware-phonelink','mdi-hardware-security','mdi-hardware-sim-card','mdi-hardware-smartphone','mdi-hardware-speaker','mdi-hardware-tablet-android','mdi-hardware-tablet-mac','mdi-hardware-tablet','mdi-hardware-tv','mdi-hardware-watch','mdi-image-add-to-photos','mdi-image-adjust','mdi-image-assistant-photo','mdi-image-audiotrack','mdi-image-blur-circular','mdi-image-blur-linear','mdi-image-blur-off','mdi-image-blur-on','mdi-image-brightness-1','mdi-image-brightness-2','mdi-image-brightness-3','mdi-image-brightness-4','mdi-image-brightness-5','mdi-image-brightness-6','mdi-image-brightness-7','mdi-image-brush','mdi-image-camera-alt','mdi-image-camera-front','mdi-image-camera-rear','mdi-image-camera-roll','mdi-image-camera','mdi-image-center-focus-strong','mdi-image-center-focus-weak','mdi-image-collections','mdi-image-color-lens','mdi-image-colorize','mdi-image-compare','mdi-image-control-point-duplicate','mdi-image-control-point','mdi-image-crop-3-2','mdi-image-crop-5-4','mdi-image-crop-7-5','mdi-image-crop-16-9','mdi-image-crop-din','mdi-image-crop-free','mdi-image-crop-landscape','mdi-image-crop-original','mdi-image-crop-portrait','mdi-image-crop-square','mdi-image-crop','mdi-image-dehaze','mdi-image-details','mdi-image-edit','mdi-image-exposure-minus-1','mdi-image-exposure-minus-2','mdi-image-exposure-plus-1','mdi-image-exposure-plus-2','mdi-image-exposure-zero','mdi-image-exposure','mdi-image-filter-1','mdi-image-filter-2','mdi-image-filter-3','mdi-image-filter-4','mdi-image-filter-5','mdi-image-filter-6','mdi-image-filter-7','mdi-image-filter-8','mdi-image-filter-9-plus','mdi-image-filter-9','mdi-image-filter-b-and-w','mdi-image-filter-center-focus','mdi-image-filter-drama','mdi-image-filter-frames','mdi-image-filter-hdr','mdi-image-filter-none','mdi-image-filter-tilt-shift','mdi-image-filter-vintage','mdi-image-filter','mdi-image-flare','mdi-image-flash-auto','mdi-image-flash-off','mdi-image-flash-on','mdi-image-flip','mdi-image-gradient','mdi-image-grain','mdi-image-grid-off','mdi-image-grid-on','mdi-image-hdr-off','mdi-image-hdr-on','mdi-image-hdr-strong','mdi-image-hdr-weak','mdi-image-healing','mdi-image-image-aspect-ratio','mdi-image-image','mdi-image-iso','mdi-image-landscape','mdi-image-leak-add','mdi-image-leak-remove','mdi-image-lens','mdi-image-looks-3','mdi-image-looks-4','mdi-image-looks-5','mdi-image-looks-6','mdi-image-looks-one','mdi-image-looks-two','mdi-image-looks','mdi-image-loupe','mdi-image-movie-creation','mdi-image-nature-people','mdi-image-nature','mdi-image-navigate-before','mdi-image-navigate-next','mdi-image-palette','mdi-image-panorama-fisheye','mdi-image-panorama-horizontal','mdi-image-panorama-vertical','mdi-image-panorama-wide-angle','mdi-image-panorama','mdi-image-photo-album','mdi-image-photo-camera','mdi-image-photo-library','mdi-image-photo','mdi-image-portrait','mdi-image-remove-red-eye','mdi-image-rotate-left','mdi-image-rotate-right','mdi-image-slideshow','mdi-image-straighten','mdi-image-style','mdi-image-switch-camera','mdi-image-switch-video','mdi-image-tag-faces','mdi-image-texture','mdi-image-timelapse','mdi-image-timer-3','mdi-image-timer-10','mdi-image-timer-auto','mdi-image-timer-off','mdi-image-timer','mdi-image-tonality','mdi-image-transform','mdi-image-tune','mdi-image-wb-auto','mdi-image-wb-cloudy','mdi-image-wb-incandescent','mdi-image-wb-irradescent','mdi-image-wb-sunny','mdi-maps-beenhere','mdi-maps-directions-bike','mdi-maps-directions-bus','mdi-maps-directions-car','mdi-maps-directions-ferry','mdi-maps-directions-subway','mdi-maps-directions-train','mdi-maps-directions-transit','mdi-maps-directions-walk','mdi-maps-directions','mdi-maps-flight','mdi-maps-hotel','mdi-maps-layers-clear','mdi-maps-layers','mdi-maps-local-airport','mdi-maps-local-atm','mdi-maps-local-attraction','mdi-maps-local-bar','mdi-maps-local-cafe','mdi-maps-local-car-wash','mdi-maps-local-convenience-store','mdi-maps-local-drink','mdi-maps-local-florist','mdi-maps-local-gas-station','mdi-maps-local-grocery-store','mdi-maps-local-hospital','mdi-maps-local-hotel','mdi-maps-local-laundry-service','mdi-maps-local-library','mdi-maps-local-mall','mdi-maps-local-movies','mdi-maps-local-offer','mdi-maps-local-parking','mdi-maps-local-pharmacy','mdi-maps-local-phone','mdi-maps-local-pizza','mdi-maps-local-play','mdi-maps-local-post-office','mdi-maps-local-print-shop','mdi-maps-local-restaurant','mdi-maps-local-see','mdi-maps-local-shipping','mdi-maps-local-taxi','mdi-maps-location-history','mdi-maps-map','mdi-maps-my-location','mdi-maps-navigation','mdi-maps-pin-drop','mdi-maps-place','mdi-maps-rate-review','mdi-maps-restaurant-menu','mdi-maps-satellite','mdi-maps-store-mall-directory','mdi-maps-terrain','mdi-maps-traffic','mdi-navigation-apps','mdi-navigation-arrow-back','mdi-navigation-arrow-drop-down-circle','mdi-navigation-arrow-drop-down','mdi-navigation-arrow-drop-up','mdi-navigation-arrow-forward','mdi-navigation-cancel','mdi-navigation-check','mdi-navigation-chevron-left','mdi-navigation-chevron-right','mdi-navigation-close','mdi-navigation-expand-less','mdi-navigation-expand-more','mdi-navigation-fullscreen-exit','mdi-navigation-fullscreen','mdi-navigation-menu','mdi-navigation-more-horiz','mdi-navigation-more-vert','mdi-navigation-refresh','mdi-navigation-unfold-less','mdi-navigation-unfold-more','mdi-notification-adb','mdi-notification-bluetooth-audio','mdi-notification-disc-full','mdi-notification-dnd-forwardslash','mdi-notification-do-not-disturb','mdi-notification-drive-eta','mdi-notification-event-available','mdi-notification-event-busy','mdi-notification-event-note','mdi-notification-folder-special','mdi-notification-mms','mdi-notification-more','mdi-notification-network-locked','mdi-notification-phone-bluetooth-speaker','mdi-notification-phone-forwarded','mdi-notification-phone-in-talk','mdi-notification-phone-locked','mdi-notification-phone-missed','mdi-notification-phone-paused','mdi-notification-play-download','mdi-notification-play-install','mdi-notification-sd-card','mdi-notification-sim-card-alert','mdi-notification-sms-failed','mdi-notification-sms','mdi-notification-sync-disabled','mdi-notification-sync-problem','mdi-notification-sync','mdi-notification-system-update','mdi-notification-tap-and-play','mdi-notification-time-to-leave','mdi-notification-vibration','mdi-notification-voice-chat','mdi-notification-vpn-lock','mdi-social-cake','mdi-social-domain','mdi-social-group-add','mdi-social-group','mdi-social-location-city','mdi-social-mood','mdi-social-notifications-none','mdi-social-notifications-off','mdi-social-notifications-on','mdi-social-notifications-paused','mdi-social-notifications','mdi-social-pages','mdi-social-party-mode','mdi-social-people-outline','mdi-social-people','mdi-social-person-add','mdi-social-person-outline','mdi-social-person','mdi-social-plus-one','mdi-social-poll','mdi-social-public','mdi-social-school','mdi-social-share','mdi-social-whatshot','mdi-toggle-check-box-outline-blank','mdi-toggle-check-box','mdi-toggle-radio-button-off','mdi-toggle-radio-button-on','mdi-toggle-star-half','mdi-toggle-star-outline','mdi-toggle-star');
						?>
						<h1>Google Material Design Icons (<?php echo esc_attr(count($mdi_icons)); ?>)</h1>
						<?php foreach ($mdi_icons as $v => $i){?><a class="btn button" href="#" data-icon="<?php echo esc_attr($i); ?>"><i class="<?php echo esc_attr($i); ?>"></i><?php echo esc_attr($i); ?></a><?php } ?>
					</div>
					<div class="qt-tab" id="faicons">
						<?php 
						$fontawesome_icons = array('fa-glass','fa-music','fa-search','fa-envelope-o','fa-heart','fa-star','fa-star-o','fa-user','fa-film','fa-th-large','fa-th','fa-th-list','fa-check','fa-remove','fa-close','fa-times','fa-search-plus','fa-search-minus','fa-power-off','fa-signal','fa-gear','fa-cog','fa-trash-o','fa-home','fa-file-o','fa-clock-o','fa-road','fa-download','fa-arrow-circle-o-down','fa-arrow-circle-o-up','fa-inbox','fa-play-circle-o','fa-rotate-right','fa-repeat','fa-refresh','fa-list-alt','fa-lock','fa-flag','fa-headphones','fa-volume-off','fa-volume-down','fa-volume-up','fa-qrcode','fa-barcode','fa-tag','fa-tags','fa-book','fa-bookmark','fa-print','fa-camera','fa-font','fa-bold','fa-italic','fa-text-height','fa-text-width','fa-align-left','fa-align-center','fa-align-right','fa-align-justify','fa-list','fa-dedent','fa-outdent','fa-indent','fa-video-camera','fa-photo','fa-image','fa-picture-o','fa-pencil','fa-map-marker','fa-adjust','fa-tint','fa-edit','fa-pencil-square-o','fa-share-square-o','fa-check-square-o','fa-arrows','fa-step-backward','fa-fast-backward','fa-backward','fa-play','fa-pause','fa-stop','fa-forward','fa-fast-forward','fa-step-forward','fa-eject','fa-chevron-left','fa-chevron-right','fa-plus-circle','fa-minus-circle','fa-times-circle','fa-check-circle','fa-question-circle','fa-info-circle','fa-crosshairs','fa-times-circle-o','fa-check-circle-o','fa-ban','fa-arrow-left','fa-arrow-right','fa-arrow-up','fa-arrow-down','fa-mail-forward','fa-share','fa-expand','fa-compress','fa-plus','fa-minus','fa-asterisk','fa-exclamation-circle','fa-gift','fa-leaf','fa-fire','fa-eye','fa-eye-slash','fa-warning','fa-exclamation-triangle','fa-plane','fa-calendar','fa-random','fa-comment','fa-magnet','fa-chevron-up','fa-chevron-down','fa-retweet','fa-shopping-cart','fa-folder','fa-folder-open','fa-arrows-v','fa-arrows-h','fa-bar-chart-o','fa-bar-chart','fa-twitter-square','fa-facebook-square','fa-camera-retro','fa-key','fa-gears','fa-cogs','fa-comments','fa-thumbs-o-up','fa-thumbs-o-down','fa-star-half','fa-heart-o','fa-sign-out','fa-linkedin-square','fa-thumb-tack','fa-external-link','fa-sign-in','fa-trophy','fa-github-square','fa-upload','fa-lemon-o','fa-phone','fa-square-o','fa-bookmark-o','fa-phone-square','fa-twitter','fa-facebook-f','fa-facebook','fa-github','fa-unlock','fa-credit-card','fa-rss','fa-hdd-o','fa-bullhorn','fa-bell','fa-certificate','fa-hand-o-right','fa-hand-o-left','fa-hand-o-up','fa-hand-o-down','fa-arrow-circle-left','fa-arrow-circle-right','fa-arrow-circle-up','fa-arrow-circle-down','fa-globe','fa-wrench','fa-tasks','fa-filter','fa-briefcase','fa-arrows-alt','fa-group','fa-users','fa-chain','fa-link','fa-cloud','fa-flask','fa-cut','fa-scissors','fa-copy','fa-files-o','fa-paperclip','fa-save','fa-floppy-o','fa-square','fa-navicon','fa-reorder','fa-bars','fa-list-ul','fa-list-ol','fa-strikethrough','fa-underline','fa-table','fa-magic','fa-truck','fa-pinterest','fa-pinterest-square','fa-google-plus-square','fa-google-plus','fa-money','fa-caret-down','fa-caret-up','fa-caret-left','fa-caret-right','fa-columns','fa-unsorted','fa-sort','fa-sort-down','fa-sort-desc','fa-sort-up','fa-sort-asc','fa-envelope','fa-linkedin','fa-rotate-left','fa-undo','fa-legal','fa-gavel','fa-dashboard','fa-tachometer','fa-comment-o','fa-comments-o','fa-flash','fa-bolt','fa-sitemap','fa-umbrella','fa-paste','fa-clipboard','fa-lightbulb-o','fa-exchange','fa-cloud-download','fa-cloud-upload','fa-user-md','fa-stethoscope','fa-suitcase','fa-bell-o','fa-coffee','fa-cutlery','fa-file-text-o','fa-building-o','fa-hospital-o','fa-ambulance','fa-medkit','fa-fighter-jet','fa-beer','fa-h-square','fa-plus-square','fa-angle-double-left','fa-angle-double-right','fa-angle-double-up','fa-angle-double-down','fa-angle-left','fa-angle-right','fa-angle-up','fa-angle-down','fa-desktop','fa-laptop','fa-tablet','fa-mobile-phone','fa-mobile','fa-circle-o','fa-quote-left','fa-quote-right','fa-spinner','fa-circle','fa-mail-reply','fa-reply','fa-github-alt','fa-folder-o','fa-folder-open-o','fa-smile-o','fa-frown-o','fa-meh-o','fa-gamepad','fa-keyboard-o','fa-flag-o','fa-flag-checkered','fa-terminal','fa-code','fa-mail-reply-all','fa-reply-all','fa-star-half-empty','fa-star-half-full','fa-star-half-o','fa-location-arrow','fa-crop','fa-code-fork','fa-unlink','fa-chain-broken','fa-question','fa-info','fa-exclamation','fa-superscript','fa-subscript','fa-eraser','fa-puzzle-piece','fa-microphone','fa-microphone-slash','fa-shield','fa-calendar-o','fa-fire-extinguisher','fa-rocket','fa-maxcdn','fa-chevron-circle-left','fa-chevron-circle-right','fa-chevron-circle-up','fa-chevron-circle-down','fa-html5','fa-css3','fa-anchor','fa-unlock-alt','fa-bullseye','fa-ellipsis-h','fa-ellipsis-v','fa-rss-square','fa-play-circle','fa-ticket','fa-minus-square','fa-minus-square-o','fa-level-up','fa-level-down','fa-check-square','fa-pencil-square','fa-external-link-square','fa-share-square','fa-compass','fa-toggle-down','fa-caret-square-o-down','fa-toggle-up','fa-caret-square-o-up','fa-toggle-right','fa-caret-square-o-right','fa-euro','fa-eur','fa-gbp','fa-dollar','fa-usd','fa-rupee','fa-inr','fa-cny','fa-rmb','fa-yen','fa-jpy','fa-ruble','fa-rouble','fa-rub','fa-won','fa-krw','fa-bitcoin','fa-btc','fa-file','fa-file-text','fa-sort-alpha-asc','fa-sort-alpha-desc','fa-sort-amount-asc','fa-sort-amount-desc','fa-sort-numeric-asc','fa-sort-numeric-desc','fa-thumbs-up','fa-thumbs-down','fa-youtube-square','fa-youtube','fa-xing','fa-xing-square','fa-youtube-play','fa-dropbox','fa-stack-overflow','fa-instagram','fa-flickr','fa-adn','fa-bitbucket','fa-bitbucket-square','fa-tumblr','fa-tumblr-square','fa-long-arrow-down','fa-long-arrow-up','fa-long-arrow-left','fa-long-arrow-right','fa-apple','fa-windows','fa-android','fa-linux','fa-dribbble','fa-skype','fa-foursquare','fa-trello','fa-female','fa-male','fa-gittip','fa-gratipay','fa-sun-o','fa-moon-o','fa-archive','fa-bug','fa-vk','fa-weibo','fa-renren','fa-pagelines','fa-stack-exchange','fa-arrow-circle-o-right','fa-arrow-circle-o-left','fa-toggle-left','fa-caret-square-o-left','fa-dot-circle-o','fa-wheelchair','fa-vimeo-square','fa-turkish-lira','fa-try','fa-plus-square-o','fa-space-shuttle','fa-slack','fa-envelope-square','fa-wordpress','fa-openid','fa-institution','fa-bank','fa-university','fa-mortar-board','fa-graduation-cap','fa-yahoo','fa-google','fa-reddit','fa-reddit-square','fa-stumbleupon-circle','fa-stumbleupon','fa-delicious','fa-digg','fa-pied-piper','fa-pied-piper-alt','fa-drupal','fa-joomla','fa-language','fa-fax','fa-building','fa-child','fa-paw','fa-spoon','fa-cube','fa-cubes','fa-behance','fa-behance-square','fa-steam','fa-steam-square','fa-recycle','fa-automobile','fa-car','fa-cab','fa-taxi','fa-tree','fa-spotify','fa-deviantart','fa-soundcloud','fa-database','fa-file-pdf-o','fa-file-word-o','fa-file-excel-o','fa-file-powerpoint-o','fa-file-photo-o','fa-file-picture-o','fa-file-image-o','fa-file-zip-o','fa-file-archive-o','fa-file-sound-o','fa-file-audio-o','fa-file-movie-o','fa-file-video-o','fa-file-code-o','fa-vine','fa-codepen','fa-jsfiddle','fa-life-bouy','fa-life-buoy','fa-life-saver','fa-support','fa-life-ring','fa-circle-o-notch','fa-ra','fa-rebel','fa-ge','fa-empire','fa-git-square','fa-git','fa-hacker-news','fa-tencent-weibo','fa-qq','fa-wechat','fa-weixin','fa-send','fa-paper-plane','fa-send-o','fa-paper-plane-o','fa-history','fa-genderless','fa-circle-thin','fa-header','fa-paragraph','fa-sliders','fa-share-alt','fa-share-alt-square','fa-bomb','fa-soccer-ball-o','fa-futbol-o','fa-tty','fa-binoculars','fa-plug','fa-slideshare','fa-twitch','fa-yelp','fa-newspaper-o','fa-wifi','fa-calculator','fa-paypal','fa-google-wallet','fa-cc-visa','fa-cc-mastercard','fa-cc-discover','fa-cc-amex','fa-cc-paypal','fa-cc-stripe','fa-bell-slash','fa-bell-slash-o','fa-trash','fa-copyright','fa-at','fa-eyedropper','fa-paint-brush','fa-birthday-cake','fa-area-chart','fa-pie-chart','fa-line-chart','fa-lastfm','fa-lastfm-square','fa-toggle-off','fa-toggle-on','fa-bicycle','fa-bus','fa-ioxhost','fa-angellist','fa-cc','fa-shekel','fa-sheqel','fa-ils','fa-meanpath','fa-buysellads','fa-connectdevelop','fa-dashcube','fa-forumbee','fa-leanpub','fa-sellsy','fa-shirtsinbulk','fa-simplybuilt','fa-skyatlas','fa-cart-plus','fa-cart-arrow-down','fa-diamond','fa-ship','fa-user-secret','fa-motorcycle','fa-street-view','fa-heartbeat','fa-venus','fa-mars','fa-mercury','fa-transgender','fa-transgender-alt','fa-venus-double','fa-mars-double','fa-venus-mars','fa-mars-stroke','fa-mars-stroke-v','fa-mars-stroke-h','fa-neuter','fa-facebook-official','fa-pinterest-p','fa-whatsapp','fa-server','fa-user-plus','fa-user-times','fa-hotel','fa-bed','fa-viacoin','fa-train','fa-subway','fa-medium');?>
						<h1>FontAwesome (<?php echo esc_attr(count($fontawesome_icons)); ?>)</h1>
						<?php foreach ($fontawesome_icons as $i){?><a class="btn button" href="#" data-icon="fa <?php echo esc_attr($i); ?>"><i class="fa <?php echo esc_attr($i); ?>"></i>fa <?php echo esc_attr($i); ?></a><?php } ?>
					</div>
				</div>
				<a href="#close" id="qw-closemodal"><span class="fa fa-close"></span></a>
			</div>
		</div>
		<a href="#" class="qw-iconreference-open button button-primary" id="qtaddicons" data-target="tinymce">CHOOSE ICON</a>		
		<?php
		echo ob_get_clean();
	}
	add_action('admin_footer', 'qw_admin_footer_function', 999999);
}
if(!function_exists('qw_admin_icons_list')){
	function qw_admin_icons_list(){
		wp_register_style( 'qw-qticons', CUSTOM_METABOXES_DIR . '/font-awesome/css/font-awesome.min.css', false, '1.0.0' );
		wp_enqueue_style( 'qw-qticons' );		
	}
}
add_action( 'admin_enqueue_scripts', 'qw_admin_icons_list' );

?>