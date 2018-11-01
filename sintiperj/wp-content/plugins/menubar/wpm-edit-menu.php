<?php

if ($action == 'editmenu') {
	$heading = __('Edit Menu', 'menubar');
	$submit_text = __('Update Menu', 'menubar');
	$form = '<form name="update" id="update" method="post" action="'. $wpm_options->form_action. '">';
	$action = 'updatemenu';
	$nonce_action = 'updatemenu_' . $wpm_menu->id;
} else {
	$heading = __('Add New Menu', 'menubar');
	$submit_text = __('Add Menu', 'menubar');
	$form = '<form name="add" id="add" method="post" action="'. $wpm_options->form_action. '">';
	$action = 'addmenu';
	$nonce_action = 'addmenu';
	$wpm_menu = wpm_empty_item ();
}
?>

<div class="wrap">
<h2><?php echo $heading ?></h2>
<div id="ajax-response"></div>
<?php echo $form ?>
<input type="hidden" name="action" value="<?php echo $action; ?>" />
<input type="hidden" name="menuid" value="<?php echo $wpm_menu->id; ?>" />
<?php wp_nonce_field ($nonce_action); ?>

<table class="editform" width="100%" cellspacing="2" cellpadding="5">
	<tr>
		<th scope="row" valign="top">	<label for="name"><?php _e('Name:', 'menubar'); ?></label> </th>
		<td> <input name="menuname" id="menuname" type="text" value="<?php echo esc_attr ($wpm_menu->name); ?>" size="10" />
		</td>
	</tr>
	<tr id="select6" >
		<th scope="row" valign="top"> <label for="template">  <?php _e('Template:', 'menubar'); ?></label> </th>
		<td>
		<?php wpm_template_dropdown (wpm_2to1 ($wpm_menu->selection, $wpm_menu->cssclass)); ?>
		<?php _e('(select a menu template and stylesheet)', 'menubar'); ?>
		</td>
	</tr>
</table>

<p class="submit"> <input type="submit" name="submit" value="<?php echo $submit_text ?>" /> </p>

</form>
</div>
