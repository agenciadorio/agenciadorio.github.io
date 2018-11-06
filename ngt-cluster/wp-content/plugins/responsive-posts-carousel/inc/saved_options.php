<h3 class="tab-head"><?php echo ($data['carouselname'] != '') ? $data['carouselname'] : 'Posts Carousel' ; ?></h3>
	<div class="tab-content">
		<h3><?php _e( 'Basic Settings', 'wcp-carousel' ); ?></h3>
		<table class="form-table">
			<tr>
				<td><?php _e( 'Select Taxonomy', 'wcp-carousel' ); ?>
				<td>
					<select class="wcp-taxonomy widefat"> 
				 <option value=""><?php echo esc_attr(__('Select Taxonomy')); ?></option> 
				 <?php 
				  $taxonomies = get_taxonomies(array('public'   => true));
				  foreach ($taxonomies as $tax) { 
				  	$option = '<option value="'.$tax.'" '.selected( $data['taxonomy'], $tax ).'>';
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
					<?php if ($data['taxonomy'] != '') { ?>

						<select class="wcp-term widefat"> 
					 <option value=""><?php echo esc_attr(__('Select Term')); ?></option> 
					 <?php 
					  $terms = get_terms($data['taxonomy']); 
					  foreach ($terms as $term) { 
					  	$option = '<option value="'.$term->term_id.'" '.selected( $data['term'], $term->term_id ).'>';
						$option .= $term->name;
						$option .= ' ('.$term->count.')';
						$option .= '</option>';
						echo $option;
					  }
					 ?>
					</select>			  						
						
					<?php } else { ?>
						<p class="description"><?php _e( 'Please select any category first', 'wcp-carousel' ); ?>.</p>
					<?php } ?>
				</td>
				<td>
					<p class="description"><?php _e( 'Select Term which posts will be shown in Carousel', 'wcp-carousel' ); ?>.</p>
				</td>
			</tr>
			<tr>
				<td><?php _e( 'Exclude Posts', 'wcp-carousel' ); ?></td>
				<td>
					<input type="text" class="exclude-ids widefat" value="<?php echo $data['exclude_ids']; ?>">
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
								$selected = (isset($data['wcpposttype']) && $data['wcpposttype'] == $post_type) ? 'selected' : '' ;
							   echo '<option value="'.$post_type.'" '.$selected.'>' . $post_type . '</option>';
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
					<input type="text" class="colorpicker" value="<?php echo $data['bgcolor']; ?>">
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
			<td><input class="carouselname widefat" type="text" value="<?php echo $data['carouselname']; ?>"></td>
			<td><?php _e( 'Item Width', 'wcp-carousel' ); ?></td>
			<td><input class="itemwidth widefat" type="number" value="<?php echo $data['width']; ?>"></td>
		</tr>
		<tr>
			<td><?php _e( 'Speed of Slideshow Cycling', 'wcp-carousel' ); ?></td>
			<td><input class="slideshowSpeed widefat" type="number" value="<?php echo $data['slideshowSpeed']; ?>"></td>
			<td><?php _e( 'Speed of Animation', 'wcp-carousel' ); ?></td>
			<td><input class="animationSpeed widefat" type="number" value="<?php echo $data['animationSpeed']; ?>"></td>
		</tr>
		<tr>
			<td><?php _e( 'Show Time', 'wcp-carousel' ); ?></td>
			<td><label><input class="showtime widefat" type="checkbox" <?php checked( $data['showtime'], 'true' ); ?>><?php _e( 'Show', 'wcp-carousel' ); ?></label></td>
			<td><?php _e( 'Show Titles', 'wcp-carousel' ); ?></td>
			<td><label><input class="showtitles widefat" type="checkbox" <?php checked( $data['showtitles'], 'true' ); ?>><?php _e( 'Show', 'wcp-carousel' ); ?></label></td>
		</tr>
		<tr>
			<td><?php _e( 'Looping', 'wcp-carousel' ); ?></td>
			<td><label><input class="looping widefat" type="checkbox" <?php checked( $data['looping'], 'true' ); ?>><?php _e( 'Enable', 'wcp-carousel' ); ?></label></td>
			<td><?php _e( 'Pause/Play Button', 'wcp-carousel' ); ?></td>
			<td><label><input class="playpause widefat" type="checkbox" <?php checked( $data['playpause'], 'true' ); ?>><?php _e( 'Show', 'wcp-carousel' ); ?></label></td>
		</tr>
		<tr>
			<td><?php _e( 'Auto Slide', 'wcp-carousel' ); ?></td>
			<td><label><input class="slideshow widefat" type="checkbox" <?php checked( $data['slideshow'], 'true' ); ?>><?php _e( 'Enable', 'wcp-carousel' ); ?></label></td>
			<td><?php _e( 'Animate height of varying height items', 'wcp-carousel' ); ?></td>
			<td><label><input class="smoothHeight widefat" type="checkbox" <?php checked( $data['smoothHeight'], 'true' ); ?>><?php _e( 'Enable', 'wcp-carousel' ); ?></label></td>
		</tr>
		<tr>
			<td><?php _e( 'Paging Control', 'wcp-carousel' ); ?></td>
			<td><label><input class="controlnav widefat" type="checkbox" <?php checked( $data['controlnav'], 'true' ); ?>><?php _e( 'Show', 'wcp-carousel' ); ?></label></td>
			<td><?php _e( 'Previous/Next Arrows', 'wcp-carousel' ); ?></td>
			<td><label><input class="directionnav widefat" type="checkbox" <?php checked( $data['directionnav'], 'true' ); ?>><?php _e( 'Show', 'wcp-carousel' ); ?></label></td>
		</tr>
	</table>
	<div class="clearfix"></div>
	<hr style="margin-bottom: 10px;">
	<button class="button btndelete"><span class="dashicons dashicons-dismiss" title="Delete"></span><?php _e( 'Delete', 'wcp-carousel' ); ?></button>
	<button class="button btnadd"><span title="Add New" class="dashicons dashicons-plus-alt"></span><?php _e( 'Add New Carousel', 'wcp-carousel' ); ?></button>&nbsp;
	<p class="wcp-shortc"><button class="button-primary fullshortcode" id="<?php echo $data['counter']; ?>"><?php _e( 'Get Shortcode', 'wcp-carousel' ); ?></button></p>
	<div class="clearfix"></div>
</div>