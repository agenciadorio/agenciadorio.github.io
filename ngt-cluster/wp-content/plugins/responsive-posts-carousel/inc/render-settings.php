<div class="rpc-tab">
	<?php foreach ($tabs as $tab_name => $tab_title) { ?>
  		<button class="rpc-tablinks" onclick="openCarouselTab(event, '<?php echo $tab_name; ?>')"><?php echo $tab_title; ?></button>
	<?php } ?>
</div>

<?php foreach ($tabs as $tab_name => $tab_title) { ?>
	<div class="rpc-tabcontent" id="<?php echo $tab_name; ?>">
		<table class="wp-list-table widefat fixed striped posts">
			<?php foreach ($fields as $field) {
				if ($field['tab'] == $tab_name) { ?>
					<tr id="wrap_<?php echo (is_array($field['key'])) ? $field['key'][0].'_'.$field['key'][1] : $field['key'] ; ?>">
						<td><?php echo $field['title']; ?></td>
						<td class="td_<?php echo (is_array($field['key'])) ? $field['key'][0].'_'.$field['key'][1] : $field['key'] ; ?>">
							<?php $this->render_input_field($field, $carousel_meta); ?>
						</td>
						<td><p class="description"><?php echo $field['help']; ?></p></td>
					</tr>
				<?php }
			} ?>		
		</table>
	</div>
<?php } ?>