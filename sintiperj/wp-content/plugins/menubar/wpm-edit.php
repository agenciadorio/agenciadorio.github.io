<?php

function wpm_item_form ($action, $menuid, $item=null)
{
	global $wpm_options;
	global $wpm_type_list;

	if (is_null ($item))  $item = wpm_empty_item ();
	$menu = wpm_read_node ($menuid);
?>

<script type='text/javascript'>
jQuery(document).ready(function($) {
  $("#order").change(function() {
    if ($("#order option:selected").val() == 0)
      $("#orderid").css("display", "none");
    else
      $("#orderid").css("display", "");
    });
});
</script>

<script type='text/javascript'>
jQuery(document).ready (
function ($)
{
  $("#type").change (
  function ()
  {
	var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';	  
	var typeargs = $("#typeargs");
	var saveargs = $("#saveargs");
	var typespinner = $("#typespinner");
	var typevalue = $("#type option:selected").val();
	
    typeargs.css ("display", "none");
    saveargs.css ("display", "none");
    typespinner.css ("display", "");

    $.post (ajaxurl,
    {
      action: "menubar",
      command: "typeargs",
      type: typevalue
    }, 
    function (data)
    {
      if (typevalue == "<?php echo $item->type; ?>")
      {
        if (saveargs.html())  saveargs.css ("display", "");
      }
      else if (data)
      {
        typeargs.html (data);
        typeargs.css ("display", "");
      }
      typespinner.css ("display", "none");
    });
    
    return false;
  });
});
</script>

<?php 
	$item_list = wpm_item_list ($menuid, array(), 0); 

if ($action == 'edit') {
	$heading = __('Edit Menu Item', 'menubar');
	$submit_text = __('Update Menu Item', 'menubar');
	$form = '<form method="post" action="'. $wpm_options->form_action. '">';
	$action = 'update';
	$nonce_action = 'update_' . $item->id;
	$selected = key ($item_list); 
	$mincount = 1;
} else {
	$heading = __('Add Menu Item', 'menubar');
	$submit_text = __('Add Menu Item', 'menubar');
	$form = '<form method="post" action="'. $wpm_options->form_action. '">';
	$action = 'add';
	$nonce_action = 'add';
	end ($item_list); 
	$selected = key ($item_list); 
	$mincount = 0;
}
?>

<div class="wrap">
<h2><?php echo $heading; ?></h2>
<?php echo $form; ?>
<input type="hidden" name="action" value="<?php echo $action; ?>" />
<input type="hidden" name="menuid" value="<?php echo $menuid; ?>" />
<input type="hidden" name="itemid" value="<?php echo $item->id; ?>" />
<?php wp_nonce_field($nonce_action); ?>

<table class="editform">

<?php if (count($item_list) > $mincount)
		wpm_order (__('Order:', 'menubar'), 'orderid', '', $item_list, $selected, $action); ?>

<?php wpm_input (__('Name:', 'menubar'), 'name', $item->name, 40, __('(e.g. Home, News)', 'menubar')); ?>
<?php if ($menu->features['images'] == true)
		wpm_input (__('Image:', 'menubar'), 'imageurl', isset ($item->imageurl)? $item->imageurl: '', 50, __('(optional image URL)', 'menubar')); ?>

<?php wpm_select (__('Type:', 'menubar'), 'type', $wpm_type_list, $item->type); ?>

</table>

<table id="saveargs" class="editform">
<?php if ($action == 'update')  wpm_typeargs ($item->type, $item); ?>
</table>

<table id="typeargs" class="editform" style="display: none;">
</table>

<table class="editform">

<?php wpm_input  (__('CSS class:', 'menubar'), 'cssclass', $item->cssclass, 30, __('(optional CSS class of this menu item)', 'menubar')); ?>
<?php wpm_input  (__('Attributes:', 'menubar'), 'attributes', $item->attributes, 30, __('(e.g. target="_blank", title="click me!")', 'menubar')); ?>
	
</table>

<p class="submit"> <input type="submit" name="submit" value="<?php echo $submit_text ?>" /> </p>

</form>
</div>

<?php
}

function wpm_order ($label, $name, $attr, $list, $selected, $action)
{
		echo "<tr><th width=\"100px\" scope=\"row\" align=\"left\" valign=\"top\">\n";
			echo "<label for=\"$name\"> $label </label>\n";
		echo "</th>\n";
		echo "<td>\n";		
			echo "<select id=\"order\" name=\"order\">\n";
			if ($action == 'update') 
				echo "<option value=\"0\" selected=\"selected\">". __('select', 'menubar'). "&nbsp; </option>\n";
			echo "<option value=\"1\">". __('Before...', 'menubar'). "&nbsp; </option>\n";
			echo "<option value=\"2\">". __('Child of...', 'menubar'). "&nbsp; </option>\n";
			echo "<option value=\"3\"";
			if ($action != 'update') echo " selected=\"selected\"";
			echo ">". __('After...', 'menubar'). "&nbsp; </option>\n";
			echo "</select>\n";
			echo "<select id=\"$name\" name=\"$name\"";
			if ($action == 'update') echo "style=\"display: none;\"";
			echo ">\n";
			foreach ($list as $value => $caption)
			{
				echo "<option value=\"$value\"";
				if ($value == $selected) echo " selected=\"selected\"";
 				echo "> $caption &nbsp; </option>\n";
			}
			echo "</select>\n";
		echo "</td></tr>\n";

	return true;
}

function wpm_select ($label, $name, $list, $selected, $attr='', $comment='')
{
	global $wpm_options;
	$url = $wpm_options->menubar_url;

		echo "<tr><th width=\"100px\" scope=\"row\" align=\"left\" valign=\"top\">\n";
			echo "<label for=\"$name\"> $label </label>\n";
		echo "</th>\n";
		echo "<td>\n";		
			echo "<select id=\"$name\" name=\"$name\" $attr>\n";
			foreach ($list as $value => $caption)
			{
				echo "<option value=\"$value\"";
				if ($value == $selected) echo " selected=\"selected\"";
 				echo "> $caption &nbsp; </option>\n";
			}
			echo "</select>\n";
			
		echo "<img id=\"{$name}spinner\" style=\"display: none;\" src=\"$url/spinner.gif\" />";
		echo "$comment\n";
		echo "</td></tr>\n";

	return true;
}

function wpm_multiselect ($label, $name, $list, $selected, $attr='', $comment='')
{
	global $wpm_options;
	$url = $wpm_options->menubar_url;

		echo "<tr><th width=\"100px\" scope=\"row\" align=\"left\" valign=\"top\">\n";
			echo "<label for=\"$name\"> $label </label>\n";
		echo "</th>\n";
		echo "<td>\n";		
			echo "<select id=\"$name\" name=\"{$name}[]\" multiple=\"multiple\" $attr>\n";
			foreach ($list as $value => $caption)
			{
				echo "<option value=\"$value\"";
				if (in_array ($value, (array)$selected)) echo " selected=\"selected\"";
 				echo "> $caption &nbsp; </option>\n";
			}
			echo "</select>\n";
			
		echo "<img id=\"{$name}spinner\" style=\"display: none;\" src=\"$url/spinner.gif\" />";
		echo "$comment\n";
		echo "</td></tr>\n";

	return true;
}

function wpm_input ($label, $name, $value, $size, $comment='')
{
	$value = esc_attr ($value);

		echo "<tr><th width=\"100px\" scope=\"row\" align=\"left\" valign=\"top\">\n";
			echo "<label for=\"$name\"> $label </label>\n";
		echo "</th>\n";
		echo "<td>\n";
			echo "<input id=\"$name\" name=\"$name\" type=\"text\" value=\"$value\" size=\"$size\" />\n";
			echo "$comment\n";
		echo "</td></tr>\n";
		
	return true;
}

function wpm_textarea ($label, $name, $value, $size, $comment='')
{
	$value = esc_textarea ($value);

	echo "<tr><th width='100px' scope='row' align='left' valign='top'>\n";
	echo "<label for='$name'>$label</label>\n";
	echo "</th>\n";
	echo "<td>\n";
	echo "<textarea id='$name' name='$name' rows='5' cols='$size' style='resize: both;' placeholder='$comment'>$value</textarea>\n";
	echo "</td></tr>\n";

	return true;
}

function wpm_typeargs ($type, $item=null)
{
	if (is_null ($item))  $item = wpm_empty_item ();

	switch ($type)
	{
	case 'Home':
	case 'FrontPage':
	case 'Heading':
		break;

	case 'Tag':
		$tag_list  = wpm_tag_list (__('No tags found', 'menubar'));
		wpm_select (__('Tag:', 'menubar'), 'selection', $tag_list, $item->selection);
		break;

	case 'TagList':
		$tag_list  = wpm_tag_list (__('No tags found', 'menubar'));
		$sort_list  = array ('id' => 'id', 'name' => 'name', 'count' => 'count');
		wpm_multiselect (__('Exclude:', 'menubar'), 'exclude', $tag_list, $item->exclude, 
			'size="4" style="height: auto;"', __('(use Ctrl+click to select/deselect multiple tags)', 'menubar')); 
	//	wpm_select (__('Sort by:', 'menubar'), 'sortby', $sort_list, $item->sortby);
		break;

	case 'Category':
		$cat_list  = wpm_cat_list (0, array(), 0);
		wpm_select (__('Category:', 'menubar'), 'selection', $cat_list, $item->selection);
		break;

	case 'CategoryTree':
		$cat_list[0] = __('All Categories', 'menubar');
		$cat_list  = wpm_cat_list (0, $cat_list, 0);
		wpm_select (__('Category:', 'menubar'), 'selection', $cat_list, $item->selection);
		wpm_input  (__('Depth:', 'menubar'), 'depth', $item->depth, 10, __('(max number of category levels to display)', 'menubar'));
		unset ($cat_list[0]);
		wpm_multiselect (__('Exclude:', 'menubar'), 'exclude', $cat_list, $item->exclude, 
			'size="4" style="height: auto;"', __('(use Ctrl+click to select/deselect multiple categories)', 'menubar')); 
		wpm_multiselect (__('Headings:', 'menubar'), 'headings', $cat_list, $item->headings, 
			'size="4" style="height: auto;"', __('(selected category names will be made unclickable)', 'menubar')); 
		break;

	case 'Page':
		$page_list = wpm_page_list (0, array(), 0); 
		wpm_select (__('Page:', 'menubar'), 'selection', $page_list, $item->selection);
		break;

	case 'PageTree':
		$page_list[0] = __('All Pages', 'menubar');
		$page_list = wpm_page_list (0, $page_list, 0); 
		wpm_select (__('Page:', 'menubar'), 'selection', $page_list, $item->selection);
		wpm_input  (__('Depth:', 'menubar'), 'depth', $item->depth, 10, __('(max number of page levels to display)', 'menubar'));
		unset ($page_list[0]);
		wpm_multiselect (__('Exclude:', 'menubar'), 'exclude', $page_list, $item->exclude, 
			'size="4" style="height: auto;"', __('(use Ctrl+click to select/deselect multiple pages)', 'menubar')); 
		wpm_multiselect (__('Headings:', 'menubar'), 'headings', $page_list, $item->headings, 
			'size="4" style="height: auto;"', __('(selected page names will be made unclickable)', 'menubar')); 
		break;

	case 'Post':
		wpm_input  (__('Post ID:', 'menubar'), 'selection', $item->selection, 10);
		break;

	case 'SearchBox':
		wpm_input  (__('Button:', 'menubar'), 'selection', $item->selection, 30, __('(text for the optional Submit button)', 'menubar'));
		break;

	case 'External':
		wpm_input  (__('URL:', 'menubar'), 'selection', $item->selection, 60);
		break;

	case 'PHP':
		wpm_textarea  (__('PHP code:', 'menubar'), 'selection', $item->selection, 60, __("must end with:\nreturn array (\$label, \$url);  //display a menu item\nor:\nreturn false;  //display nothing", 'menubar'));
		break;

	case 'Custom':
		wpm_textarea  (__('HTML:', 'menubar'), 'selection', $item->selection, 60);
		break;
	}
}

function wpm_item_list ($item_id, $list, $level)
{
	$item = wpm_read_node ($item_id);
	
	if ($level > 0)
	{
		$name = wpm_display_name ($item);
		$list[$item->id] = str_repeat("&#8212; ", $level-1) . $name;
	}
		
	if ($item->down)  $list = wpm_item_list ($item->down, $list, $level+1);
	if ($item->side)  $list = wpm_item_list ($item->side, $list, $level);
		
	return $list;
}

function wpm_tag_list ($emptylist)
{
	$items = wpm_get_tags();

	$list = array ();
	foreach ($items as $item)
		$list[$item->term_id] = $item->name;
		
	if (count ($list) == 0)  $list[0] = $emptylist;
	return $list;
}

function wpm_cat_list ($parent_id, $list, $level)
{
	global $wpdb;
	
	$sql = "SELECT t.term_id, t.name 
			FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy AS tt 
			ON t.term_id = tt.term_id
			WHERE tt.taxonomy = 'category' AND tt.parent = $parent_id";			

	$items = $wpdb->get_results ($sql);
	
	foreach ($items as $item)
	{
		$list[$item->term_id] = str_repeat("&#8212; ", $level) . $item->name;
		$list = wpm_cat_list ($item->term_id, $list, $level+1);
	}

	return $list;
}

function wpm_page_list ($parent_id, $list, $level)
{
	global $wpdb;
	
	$sql = "SELECT ID, post_title FROM $wpdb->posts 
			WHERE post_parent = $parent_id AND post_type = 'page' AND (post_status = 'publish' OR post_status = 'private') 
			ORDER BY menu_order, post_title ASC";

	$items = $wpdb->get_results ($sql);
	
	foreach ($items as $item)
	{
		$list[$item->ID] = str_repeat("&#8212; ", $level) . $item->post_title;
		$list = wpm_page_list ($item->ID, $list, $level+1);
	}
		
	return $list;
}
