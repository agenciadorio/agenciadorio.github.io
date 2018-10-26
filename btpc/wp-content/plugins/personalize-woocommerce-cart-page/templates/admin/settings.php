<?php 
/*
 * this file rendering admin options for the plugin
* options are defined in admin/admin-options.php
*/

global $nmwoostore;
$this -> load_template('admin/options.php');
//$this -> pa($this -> the_options);
//$this -> pa($nmwoostore -> plugin_settings);

$sendUpdate = '';

?>


<div class="wrap rm_wrap">
<h2>
	WooCommerce Store Customizer Settings
</h2>

<div id="filemanager-tabs" class="tab-container" style="width:70%;float:left;">
	<ul class='etabs'>
		<?php foreach($this -> the_options as $id => $option){
			
			?>

		<li class='tab'><a href="#<?php echo $id?>"><?php echo $option['name']?>
		</a></li>

		<?php }?>
	</ul>


	<?php foreach($this -> the_options as $id => $options){
		
		// reseting the update data array
		
		?>

	<div id="<?php echo $id?>" class="general-settings">
		<p>
			<?php echo $options['desc']?>
		</p>


		<ul>
			<?php foreach($options['meat'] as $key => $data){
			
				$sendUpdate[$data['id']] = array('type'	=> $data['type']);
				
				//echo 'option key '.$data['id'];
				$default_value = (isset($data['default']) ? $data['default'] : '');
				$the_value = ( isset($nmwoostore -> plugin_settings[ $data['id'] ]) ? $nmwoostore -> plugin_settings[ $data['id'] ] : $default_value);
				?>

			<li id="<?php echo $key?>" class="plugin-field-set">			
			<?php switch($data['type']){
					
				case 'text':
					if ($data['id'] == 'nm_filemanager_public_user' && $the_value == ''){
						$text_val = get_current_user_id();
					} else {
						$text_val = stripcslashes($the_value);
					}
					?>
				<ul>
					<li><h4><?php echo $data['desc']?> </h4>
					<label for="<?php echo $data['id']?>"><?php echo $data['label']?> <br />
					<input type="text" name="<?php echo $data['id']?>" id="<?php echo $data['id']?>" value="<?php echo $text_val ?>" class="regular-text" >
					</label><br />
					<em class="help"><?php echo $data['help']?> </em> 
					</li>
				</ul> <?php 
				break;
				
				
				case 'textarea':
					?>
								<ul>
									<label for="<?php echo $data['id']?>"><?php echo $data['label']?></label><br /> 
									<textarea cols="45" rows="6" name="<?php echo $data['id']?>" id="<?php echo $data['id']?>"><?php echo stripcslashes($the_value)?></textarea>
									<li><h4><?php echo $data['desc']?> </h4>
									<br />
									<em><?php echo $data['help']?> </em>
									</li>
								</ul> 
				<?php 
				break;

				case 'checkbox':?>
				<ul>
					<li>
					<h4><?php echo $data['desc']?> </h4>
					
					<?php foreach($data['options'] as $k => $label){?>
					
						<label for="<?php echo $data['id'].'-'.$k?>"> <input type="checkbox" name="<?php echo $data['id']?>" id="<?php echo $data['id'].'-'.$k?>" value="<?php echo $k?>"> <?php echo $label?>
						</label>
					<?php }?>
					
					<br />
					<em><?php echo $data['help']?> </em> 
					</li>
					<!-- setting value -->
					<script>
					setChecked('<?php echo $data['id']?>', '<?php echo json_encode($nmwoostore -> plugin_settings[ $data['id'] ])?>');
					</script>
				</ul>
				
								
				<?php break;
				
				
				case 'radio':?>
								<ul>
									<li>
									<h4><?php echo $data['desc']?> </h4>
									
									<?php foreach($data['options'] as $k => $label){?>
									
										<label for="<?php echo $data['id'].'-'.$k?>"> <input type="radio" name="<?php echo $data['id']?>" id="<?php echo $data['id'].'-'.$k?>" value="<?php echo $k?>"> <?php echo $label?>
										</label>
									<?php }?>
									
									<br />
									<em><?php echo $data['help']?> </em> 
									</li>
									<script>
									setCheckedRadio('<?php echo $data['id']?>', '<?php echo $nmwoostore -> plugin_settings[ $data['id'] ]?>');
									</script>
								</ul>
								
												
				<?php break;
				
				case 'select':?>
								<ul>
									<li>
									<h4><?php echo $data['desc']?> </h4>
									
										<label for="<?php echo $data['id']?>"><?php echo $data['label']?> 										 
										<select name="<?php echo $data['id']?>" id="<?php echo $data['id']?>">
											<option value=""><?php echo $data['default']?></option>
											
											<?php foreach($data['options'] as $k => $label){
												
													$selected = ($k == $nmwoostore -> plugin_settings[ $data['id'] ]) ? 'selected = "selected"' : '';
													
													echo '<option value="'.$k.'" '.$selected.'>'.$label.'</option>';
											}
												?>
											
										</select> 
										</label>
									
									<br />
									<em><?php echo $data['help']?> </em>
									</li>
								</ul>
								
								<?php break;
								
			case 'para':?>
											<ul>
												<li>
												<h4><?php echo $data['desc']?> </h4>
												
												<br />
												<em><?php echo $data['help']?> </em>
												</li>
											</ul>
											
											<?php break;
			
		case 'file':?>
													<ul>
														<li>
														<?php 
														$file = $this->plugin_meta['path'] .'/templates/admin/'.$data['id'];
														if(file_exists($file))
															include $file;
														else 	
															echo 'file not exists '.$file;
														?> 
														</li>
													</ul>
													
													<?php break;

			} ?></li>
			<?php }
			
			?>
		</ul>

		<p><button class="button button-primary" onclick=update_options('<?php echo json_encode($sendUpdate)?>')><?php _e('Save settings', 'nm-filemanager')?></button>
			<span id="filemanager-settigns-saving"></span>
		</p>
	
	</div>

	<?php 
	}
	?>
	
</div>

<div id="woostore-ad" style="width:25%; float:right;margin:30px 5px 0 0">
	<a href="https://jetpack.com/pricing/?aff=8683"><img src="<?php echo esc_url($this->plugin_meta['url'].'/images/inline-rectangle.png');?>" alt="Jetpack" title="Jetpack" /></a>
</div>


</div>