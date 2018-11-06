<h3 class="tab-head"><?php _e( 'Posts Carousel', 'wcp-carousel' ); ?></h3>
	<div class="tab-content">
		<h3><?php _e( 'Basic Settings', 'wcp-carousel' ); ?></h3>
		<table class="form-table">
			<tr class="tax-list-row">
				<td><?php _e( 'Select Taxonomy', 'wcp-carousel' ); ?>
				<td>
					<select class="wcp-taxonomy widefat"> 
				 <option value=""><?php echo esc_attr(__('Select Taxonomy')); ?></option> 
				 <?php 
				  $taxonomies = get_taxonomies(array('public'   => true));
				  foreach ($taxonomies as $tax) { 
				  	$option = '<option value="'.$tax.'">';
					$option .= $tax;
					$option .= '</option>';
					echo $option;
				  }
				 ?>
				</select>		  						
				</td>
				<td>
					<p class="description"><?php _e( 'Select Taxonomy', 'wcp-carousel' ); ?>.</p>
				</td>
			</tr>
			<tr>
				<td><?php _e( 'Select Term', 'wcp-carousel' ); ?></td>
				<td class="append-terms">
					<p class="description"><?php _e( 'Please select any taxonomy first', 'wcp-carousel' ); ?>.</p>
				</td>
				<td>
					<p class="description"><?php _e( 'Select Term which posts will be shown in Carousel', 'wcp-carousel' ); ?>.</p>
				</td>
			</tr>
			<tr>
				<td><?php _e( 'Exclude Posts', 'wcp-carousel' ); ?></td>
				<td>
					<input type="text" class="exclude-ids widefat" value="">
				</td>
				<td>
					<p class="description"><?php _e( 'Comma separated ids of posts that you do not want to display', 'wcp-carousel' ); ?>.</p>
				</td>
			</tr>
			<tr>
				<td colspan="3"><hr></td>
			</tr>
			<tr>
				<td><?php _e( 'Select Post Type', 'wcp-carousel' ); ?>
				<td>
					<select class="wcpposttype widefat">
						<?php
							foreach ( get_post_types( '', 'names' ) as $post_type ) {
							   echo '<option value="'.$post_type.'">' . $post_type . '</option>';
							}						
						?>
					</select>
					
				</td>
				<td>
					<p class="description"><?php _e( 'Select Post Type if you want to display carousel of specific posts', 'wcp-carousel' ); ?>.</p>
				</td>
			</tr>			
			<tr>
				<td><?php _e( 'Custom Post Types IDs', 'wcp-carousel' ); ?>
				<td>
					<textarea class="widefat posttypeids"><?php echo (isset($data['post_ids'])) ? $data['post_ids'] : '' ; ?></textarea>
				</td>
				<td>
					<p class="description"><?php _e( 'Insert Post IDs comma separated, Custom Post Types also supported. <br> Please note that filling this field will disable above options', 'wcp-carousel' ); ?>.</p>
				</td>
			</tr>	
			<tr>
				<td>
					<?php _e( 'Background Color', 'wcp-carousel' ); ?>
				</td>
				<td class="insert-picker">
					<input type="text" class="colorpicker" value="">
				</td>
				<td>
					<p class="description"><?php _e( 'It is background color for Carousel', 'wcp-carousel' ); ?>.</p>
				</td>
			</tr>
		</table>
		<h3><?php _e( 'Carousel Settings', 'wcp-carousel' ); ?></h3>
	<table class="form-table">
		<tr>
			<td><?php _e( 'Carousel Name (for your reference)', 'wcp-carousel' ); ?></td>
			<td><input class="carouselname widefat" type="text" value="<?php _e( 'My Carousel', 'wcp-carousel' ); ?>"></td>
			<td><?php _e( 'Item Width', 'wcp-carousel' ); ?></td>
			<td><input class="itemwidth widefat" type="number" value="200"></td>
		</tr>
		<tr>
			<td><?php _e( 'Speed of Slideshow Cycling', 'wcp-carousel' ); ?></td>
			<td><input class="slideshowSpeed widefat" type="number" value="3000"></td>
			<td><?php _e( 'Speed of Animation', 'wcp-carousel' ); ?></td>
			<td><input class="animationSpeed widefat" type="number" value="1000"></td>
		</tr>
		<tr>
			<td><?php _e( 'Show Time', 'wcp-carousel' ); ?></td>
			<td><label><input class="showtime widefat" type="checkbox"><?php _e( 'Show', 'wcp-carousel' ); ?></label></td>
			<td><?php _e( 'Show Titles', 'wcp-carousel' ); ?></td>
			<td><label><input class="showtitles widefat" type="checkbox" checked="checked"><?php _e( 'Show', 'wcp-carousel' ); ?></label></td>
		</tr>
		<tr>
			<td><?php _e( 'Looping', 'wcp-carousel' ); ?></td>
			<td><label><input class="looping widefat" type="checkbox"><?php _e( 'Enable', 'wcp-carousel' ); ?></label></td>
			<td><?php _e( 'Pause/Play Button', 'wcp-carousel' ); ?></td>
			<td><label><input class="playpause widefat" type="checkbox"><?php _e( 'Show', 'wcp-carousel' ); ?></label></td>
		</tr>
		<tr>
			<td><?php _e( 'Auto Slide', 'wcp-carousel' ); ?></td>
			<td><label><input class="slideshow widefat" type="checkbox"><?php _e( 'Enable', 'wcp-carousel' ); ?></label></td>
			<td><?php _e( 'Animate height of varying height items', 'wcp-carousel' ); ?></td>
			<td><label><input class="smoothHeight widefat" type="checkbox"><?php _e( 'Enable', 'wcp-carousel' ); ?></label></td>
		</tr>
		<tr>
			<td><?php _e( 'Paging Control', 'wcp-carousel' ); ?></td>
			<td><label><input class="controlnav widefat" type="checkbox" checked="checked"><?php _e( 'Show', 'wcp-carousel' ); ?></label></td>
			<td><?php _e( 'Previous/Next Arrows', 'wcp-carousel' ); ?></td>
			<td><label><input class="directionnav widefat" type="checkbox" checked="checked"><?php _e( 'Show', 'wcp-carousel' ); ?></label></td>
		</tr>
	</table>
	<div class="clearfix"></div>
	<hr style="margin-bottom: 10px;">
	<button class="button btndelete"><span class="dashicons dashicons-dismiss" title="Delete"></span><?php _e( 'Delete', 'wcp-carousel' ); ?></button>
	<button class="button btnadd"><span title="Add New" class="dashicons dashicons-plus-alt"></span><?php _e( 'Add New Carousel', 'wcp-carousel' ); ?></button>&nbsp;
	<p class="wcp-shortc"><button class="button-primary fullshortcode" id="1"><?php _e( 'Get Shortcode', 'wcp-carousel' ); ?></button></p>
	<div class="clearfix"></div>
</div>