<?php 

require('../../../../../wp-load.php');
 
//global $wpdb;
?>
	<script type="text/javascript">
			jQuery(document).ready(function($) {
				$('#mc_insertBtn').click(function(event) {
					
					// Columns
					shortcode='[myclients columns="'+$('#mc_columnsNumberList').val()+'" ';
					
					// category
					if( $('#mc_categoriesList').val().length != 0 ) {
						shortcode += 'category="'+$('#mc_categoriesList').val()+'" ';
					}
					
					// Background Color
					if( $('#mc_bgColorInput').val().split(" ").join("").length != 0 ) {
						shortcode += 'backgroundColor="'+$('#mc_bgColorInput').val().split(" ").join("")+'" ';
					}
					
					// Number
					if( $('#mc_NumberInput').val().split(" ").join("").length != 0 ) {
						shortcode += 'num="'+$('#mc_NumberInput').val().split(" ").join("")+'" ';
					}
					
					// Style
					if( $('#mc_styleList').val() == 'slider' ) {
						shortcode += 'style="'+$('#mc_styleList').val()+' '+$('#mc_grayscaleList').val()+'" ';
					}
					else {
						shortcode += 'style="'+$('#mc_styleList').val()+' '+$('#mc_responsiveList').val()+' '+$('#mc_grayscaleList').val()+'" ';
					}
					
					// Order
					shortcode += 'orderby="'+$('#mc_orderByList').val()+'" order="'+$('#mc_orderList').val()+'" ]'
					
					window.send_to_editor(shortcode);
					
					$('#divMyClientsEditorOverlay').remove();
					return false;
				});
				
				$('#mc_closeBtn').click(function(event) {
					$('#divMyClientsEditorOverlay').remove();
					return false;
				});
			});
	</script>
	
	<h3>My Clients Plugin</h3>
	
	<div id="divMyClientsEditorPopupContent">
		
		<div class="row">
			<label for="mc_categoriesList">Category Name</label>
			<?php

			wp_dropdown_categories(array('taxonomy' =>'clientscategory',
										 'show_count' => 1, 
									     'pad_counts' => 1, 
										 'id' => 'mc_categoriesList',
										 'name' => 'mc_categoriesList',
										 'hide_empty' => 0,
										 'show_option_none' => 'All Categories',
										 'hierarchical'=>1));
				
			?>

		</div>
		
		<div class="row">
			<label for="mc_columnsNumberList">Columns Number</label>
			<select id="mc_columnsNumberList" name="mc_columnsNumberList">
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5" selected>5</option>
				<option value="6">6</option>
				<option value="7">7</option>
				<option value="8">8</option>
				<option value="9">9</option>
				<option value="10">10</option>
			</select>
		</div>
		
		<div class="row">
			<label for="mc_styleList">Style</label>
			<select id="mc_styleList" name="mc_styleList">
				<option value="withoutBorder" selected >Without Border</option>
				<option value="border">Border</option>
				<option value="shadowOnHover">Shadow on hover</option>
				<option value="slider">Slider</option>
			</select>
		</div>
		
		<div class="row">
			<label for="mc_responsiveList">Responsive</label>
			<select id="mc_responsiveList" name="mc_responsiveList">
				<option value="responsive" selected >True</option>
				<option value="notresponsive">False</option>
			</select>
		</div>
		
		<div class="row">
			<label for="mc_grayscaleList">Grayscale</label>
			<select id="mc_grayscaleList" name="mc_grayscaleList">
				<option value="grayscale">True</option>
				<option value="colorful" selected >False</option>
			</select>
		</div>
		
		<div class="row">
			<label for="mc_orderByList">Order By</label>
			<select id="mc_orderByList" name="mc_orderByList">
				<option value="date" selected >Publish Date</option>
				<option value="title">Title</option>
				<option value="rand">Random </option>
			</select>
		</div>
		
		<div class="row">
			<label for="mc_orderList">Order</label>
			<select id="mc_orderList" name="mc_orderList">
				<option value="DESC" selected >Descending</option>
				<option value="ASC">Ascending</option>
			</select>
		</div>
		
		<div class="row">
			<label for="mc_bgColorInput">Background Color</label>
			<input type="text" id="mc_bgColorInput" name="mc_bgColorInput" value="transparent" />
		</div>
		
		<div class="row">
			<label for="mc_NumberInput">Number</label>
			<input type="text" id="mc_NumberInput" name="mc_NumberInput" value="" />
		</div>
		
		<div id="divMyClientsEditorPopupButtons">
			<input id="mc_insertBtn" name="mc_insertBtn" class="button-primary" type="button" value="Insert" />
			<input id="mc_closeBtn" name="mc_closeBtn" class="button" type="button" value="Close" />
		</div>
	
	</div>