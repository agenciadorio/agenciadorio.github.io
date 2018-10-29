<?php
    /**
     * Load Saved Lightbox Slider Pro Settings
     */
	$PostId = $post->ID;
	$SLGF_Gallery_Settings = "SLGF_Gallery_Settings_".$PostId;
	$SLGF_Settings = unserialize(get_post_meta( $PostId, $SLGF_Gallery_Settings, true));
    if($SLGF_Settings['SLGF_Hover_Animation'] && $SLGF_Settings['SLGF_Gallery_Layout']) {
		
        $SLGF_Show_Gallery_Title  = $SLGF_Settings['SLGF_Show_Gallery_Title'];
        $SLGF_Show_Image_Label    = $SLGF_Settings['SLGF_Show_Image_Label'];
        $SLGF_Hover_Animation     = $SLGF_Settings['SLGF_Hover_Animation'];
        $SLGF_Gallery_Layout      = $SLGF_Settings['SLGF_Gallery_Layout'];
		$SLGF_Thumbnail_Layout    = $SLGF_Settings['SLGF_Thumbnail_Layout'];
        $SLGF_Hover_Color         = $SLGF_Settings['SLGF_Hover_Color'];
		$SLGF_Text_BG_Color       = $SLGF_Settings['SLGF_Text_BG_Color'];
		$SLGF_Text_Color          = $SLGF_Settings['SLGF_Text_Color'];
        $SLGF_Hover_Color_Opacity = $SLGF_Settings['SLGF_Hover_Color_Opacity'];
        $SLGF_Font_Style          = $SLGF_Settings['SLGF_Font_Style'];
		$SLGF_Box_Shadow          = $SLGF_Settings['SLGF_Box_Shadow'];
		$SLGF_Custom_CSS          = $SLGF_Settings['SLGF_Custom_CSS'];
    } else {
		$SLGF_Show_Gallery_Title  = "yes";
		$SLGF_Show_Image_Label    = "yes";
        $SLGF_Hover_Animation     = "stripe";
        $SLGF_Gallery_Layout      = "col-md-6";
		$SLGF_Thumbnail_Layout    = "same-size";
        $SLGF_Hover_Color         = "#0AC2D2";
		$SLGF_Text_BG_Color       = "#FFFFFF";
		$SLGF_Text_Color          = "#000000";
        $SLGF_Hover_Color_Opacity = "yes";
        $SLGF_Font_Style          = "font-name";
		$SLGF_Box_Shadow          = "yes";
		$SLGF_Custom_CSS          = "";
    }
?>
<script>
jQuery(document).ready(function(){
	slgf_icon_settings();
});

function slgf_icon_settings() {
	if (jQuery('#wl-view-lightbox').is(":checked")) {
	  jQuery('.slgf-icon-settings').show();
	} else {
		jQuery('.slgf-icon-settings').hide();
	}
}
</script>
	<h2>Lightbox Slider Pro Settings</h2>
    <input type="hidden" id="slgf_save_action" name="slgf_save_action" value="slgf-save-settings">
    <table class="form-table">
        <tbody>
		
			<tr>
				<th scope="row"><label><?php _e("Show Gallery Title", WEBLIZAR_SLGF_TEXT_DOMAIN ); ?></label></th>
				<td>
					<?php if($SLGF_Show_Gallery_Title == "") $SLGF_Show_Gallery_Title = "yes"; ?>
					<input type="radio" name="wl-show-gallery-title" id="wl-show-gallery-title" value="yes" <?php if($SLGF_Show_Gallery_Title == 'yes' ) { echo "checked"; } ?>> <i class="fa fa-check fa-2x"></i> 
					<input type="radio" name="wl-show-gallery-title" id="wl-show-gallery-title" value="no" <?php if($SLGF_Show_Gallery_Title == 'no' ) { echo "checked"; } ?>> <i class="fa fa-times fa-2x"></i>
					<p class="description"><?php _e("Select Yes/No option to hide or show gallery title", WEBLIZAR_SLGF_TEXT_DOMAIN ); ?>. </p>
				</td>
			</tr>
			
			<tr>
				<th scope="row"><label><?php _e("Show Image Label", WEBLIZAR_SLGF_TEXT_DOMAIN ); ?></label></th>
				<td>
					<?php if($SLGF_Show_Image_Label == "") $SLGF_Show_Image_Label = "yes"; ?>
					<input type="radio" name="wl-show-image-label" id="wl-show-image-label" value="yes" <?php if($SLGF_Show_Image_Label == 'yes' ) { echo "checked"; } ?>> <i class="fa fa-check fa-2x"></i> 
					<input type="radio" name="wl-show-image-label" id="wl-show-image-label" value="no" <?php if($SLGF_Show_Image_Label == 'no' ) { echo "checked"; } ?>> <i class="fa fa-times fa-2x"></i>
					<p class="description"><?php _e("Select Yes/No option to hide or show label on image", WEBLIZAR_SLGF_TEXT_DOMAIN ); ?>.</p>
				</td>
			</tr>
		
            <tr>
                <th scope="row"><label><?php _e("Image Hover Animation", WEBLIZAR_SLGF_TEXT_DOMAIN ); ?></label></th>
                <td>
					<?php if($SLGF_Hover_Animation == "") $SLGF_Hover_Animation = "fade"; ?>
                    <select name="wl-hover-animation" id="wl-hover-animation">
                        <optgroup label="Select Animation">
                            <option value="stroke" <?php if($SLGF_Hover_Animation == 'stroke') echo "selected=selected"; ?>>Stroke</option>
                        </optgroup>
                    </select>
                    <p class="description"><?php _e("Choose an animation effect apply on images after mouse hover.", WEBLIZAR_SLGF_TEXT_DOMAIN ); ?>  <a href="https://weblizar.com/lightbox-slider-pro/" target="_new">Get More Hover Animation</a></p>
                </td>
            </tr>

            <tr>
                <th scope="row"><label><?php _e("Gallery Layout", WEBLIZAR_SLGF_TEXT_DOMAIN ); ?></label></th>
                <td>
					<?php if($SLGF_Gallery_Layout == "") $SLGF_Gallery_Layout = "col-md-6"; ?>
                    <select name="wl-gallery-layout" id="wl-gallery-layout">
                        <optgroup label="Select Gallery Layout">
                            <option value="col-md-6" <?php if($SLGF_Gallery_Layout == 'col-md-6') echo "selected=selected"; ?>><?php _e("Two Column", WEBLIZAR_SLGF_TEXT_DOMAIN ); ?></option>
                            <option value="col-md-4" <?php if($SLGF_Gallery_Layout == 'col-md-4') echo "selected=selected"; ?>><?php _e("Three Column", WEBLIZAR_SLGF_TEXT_DOMAIN ); ?></option>
                        </optgroup>
                    </select>
                    <p class="description"><?php _e("Choose a column layout for image gallery", WEBLIZAR_SLGF_TEXT_DOMAIN ); ?>.  <a href="https://weblizar.com/lightbox-slider-pro/" target="_new">Get More Gallery Layout</a></p>
                </td>
            </tr>
			
			<tr>
				<th scope="row"><label><?php _e("Thumbnail Layout", WEBLIZAR_SLGF_TEXT_DOMAIN ); ?></label></th>
				<td>
					<?php if(!isset($SLGF_Thumbnail_Layout)) $SLGF_Thumbnail_Layout = "same-size"; ?>
					<input type="radio" name="wl-thumbnail-layout" id="wl-thumbnail-layout" value="same-size" <?php if($SLGF_Thumbnail_Layout == 'same-size' ) { echo "checked"; } ?>> Show Same Size Thumbnails
					<input type="radio" name="wl-thumbnail-layout" id="wl-thumbnail-layout" value="masonry" <?php if($SLGF_Thumbnail_Layout == 'masonry' ) { echo "checked"; } ?>> Show Masonry Style Thumbnails
					<input type="radio" name="wl-thumbnail-layout" id="wl-thumbnail-layout" value="original" <?php if($SLGF_Thumbnail_Layout == 'original' ) { echo "checked"; } ?>> <?php _e("Show Original Image As Thumbnails", WEBLIZAR_SLGF_TEXT_DOMAIN ); ?>
					<p class="description"><?php _e("Select an option for thumbnail layout setting", WEBLIZAR_SLGF_TEXT_DOMAIN ); ?>.</p>
					<p class="description"><?php _e("If Same Size Thumbnail option selected than minimum image size required in following layouts", WEBLIZAR_SLGF_TEXT_DOMAIN ); ?>:</p>
					<p class="description"><?php _e("Minimum image size required in 2 Column Gallery Layout", WEBLIZAR_SLGF_TEXT_DOMAIN ); ?>: 500x500px</p>
					<p class="description"><?php _e("Minimum image size required in 3 Column Gallery Layout", WEBLIZAR_SLGF_TEXT_DOMAIN ); ?>: 400x400px</p>
				</td>
			</tr>
			
			<tr>
				<th scope="row"><label><?php _e("Hover Color", WEBLIZAR_SLGF_TEXT_DOMAIN ); ?></label></th>
				<td>
					<?php if($SLGF_Hover_Color == "") $SLGF_Hover_Color = "#0AC2D2"; ?>
					<input type="radio" name="wl-hover-color" id="wl-hover-color" value="#0AC2D2" <?php if($SLGF_Hover_Color == '#0AC2D2' ) { echo "checked"; } ?>><label style="color:#0AC2D2;">Color1</label>&nbsp;&nbsp;&nbsp;
					<input type="radio" name="wl-hover-color" id="wl-hover-color" value="#000000" <?php if($SLGF_Hover_Color == '#000000' ) { echo "checked"; } ?>><label style="color:#000000;">Color2</label>&nbsp;&nbsp;&nbsp;
					<input type="radio" name="wl-hover-color" id="wl-hover-color" value="#dd4242" <?php if($SLGF_Hover_Color == '#dd4242' ) { echo "checked"; } ?>><label style="color:#dd4242;">Color3</label>
					<p class="description"><?php _e("Select Image Hover Color", WEBLIZAR_SLGF_TEXT_DOMAIN ); ?>.  <a href="https://weblizar.com/lightbox-slider-pro/" target="_new">Get Unlimited Hover Colour Scheme</a></p>
				</td>
			</tr>
		
			<tr>
				<th scope="row"><label><?php _e("Text Background Color", WEBLIZAR_SLGF_TEXT_DOMAIN ); ?></label></th>
				<td>
					<?php if($SLGF_Text_BG_Color == "") $SLGF_Text_BG_Color = "#FFFFFF"; ?>
					<input type="radio" name="wl-text-bg-color" id="wl-text-bg-color" value="#FFFFFF" <?php if($SLGF_Text_BG_Color == '#FFFFFF' ) { echo "checked"; } ?>><label>White</label>&nbsp;&nbsp;&nbsp;
					<input type="radio" name="wl-text-bg-color" id="wl-text-bg-color" value="#000000" <?php if($SLGF_Text_BG_Color == '#000000' ) { echo "checked"; } ?>><label>Black</label>&nbsp;&nbsp;&nbsp;
					<input type="radio" name="wl-text-bg-color" id="wl-text-bg-color" value="#dd4242" <?php if($SLGF_Text_BG_Color == '#dd4242' ) { echo "checked"; } ?>><label>Red</label>
					<p class="description"><?php _e("Select Text Background Color", WEBLIZAR_SLGF_TEXT_DOMAIN ); ?>.  </p>
				</td>
			</tr>
			
			<tr>
				<th scope="row"><label><?php _e("Text Color", WEBLIZAR_SLGF_TEXT_DOMAIN ); ?></label></th>
				<td>
					<?php if($SLGF_Text_Color == "") $SLGF_Text_Color = "#000000"; ?>
					<input type="radio" name="wl-text-color" id="wl-text-color" value="#FFFFFF" <?php if($SLGF_Text_Color == '#FFFFFF' ) { echo "checked"; } ?>><label>White</label>&nbsp;&nbsp;&nbsp;
					<input type="radio" name="wl-text-color" id="wl-text-color" value="#000000" <?php if($SLGF_Text_Color == '#000000' ) { echo "checked"; } ?>><label>Black</label>&nbsp;&nbsp;&nbsp;
					<p class="description"><?php _e("Select Text Color", WEBLIZAR_SLGF_TEXT_DOMAIN ); ?>.  </p>
				</td>
			</tr>

            <tr>
				<th scope="row"><label><?php _e("Hover Color Opacity", WEBLIZAR_SLGF_TEXT_DOMAIN ); ?></label></th>
				<td>
					<?php if(!isset($SLGF_Hover_Color_Opacity)) $SLGF_Hover_Color_Opacity = "yes"; ?>
					<input type="radio" name="wl-hover-color-opacity" id="wl-hover-color-opacity" value="yes" <?php if($SLGF_Hover_Color_Opacity == 'yes' ) { echo "checked"; } ?>> <i class="fa fa-check fa-2x"></i> 
					<input type="radio" name="wl-hover-color-opacity" id="wl-hover-color-opacity" value="no" <?php if($SLGF_Hover_Color_Opacity == 'no' ) { echo "checked"; } ?>> <i class="fa fa-times fa-2x"></i>
					<p class="description"><?php _e("Select Yes/No option for hover color opacity on images", WEBLIZAR_SLGF_TEXT_DOMAIN ); ?>.</p>
				</td>
			</tr>

            <tr>
                <th scope="row"><label><?php _e("Caption Font Style", WEBLIZAR_SLGF_TEXT_DOMAIN ); ?></label></th>
                <td>
                    <select  name="wl-font-style" class="standard-dropdown" id="wl-font-style">
                        <optgroup label="Default Fonts">
                            <option value="Arial" <?php selected($SLGF_Font_Style, 'Arial' ); ?>>Arial</option>
                            <option value="_arial_black" <?php selected($SLGF_Font_Style, 'Arial Black' ); ?>>Arial Black</option>
                            <option value="Courier New" <?php selected($SLGF_Font_Style, 'Courier New' ); ?>>Courier New</option>
                            <option value="georgia" <?php selected($SLGF_Font_Style, 'Georgia' ); ?>>Georgia</option>
                            <option value="grande"<?php selected($SLGF_Font_Style, 'Grande' ); ?>>Grande</option>
                            <option value="_helvetica_neue" <?php selected($SLGF_Font_Style, 'Helvetica Neue' ); ?>>Helvetica Neue</option>
                            <option value="_impact" <?php selected($SLGF_Font_Style, 'Impact' ); ?>>Impact</option>
                            <option value="_lucida" <?php selected($SLGF_Font_Style, 'Lucida' ); ?>>Lucida</option>
                            <option value="_lucida"<?php selected($SLGF_Font_Style, 'Lucida Grande' ); ?>>Lucida Grande</option>
                            <option value="_OpenSansBold" <?php selected($SLGF_Font_Style, 'OpenSansBold' ); ?>>OpenSansBold</option>
                            <option value="_palatino" <?php selected($SLGF_Font_Style, 'Palatino' ); ?>>Palatino</option>
                            <option value="_sans" <?php selected($SLGF_Font_Style, 'Sans' ); ?>>Sans</option>
                            <option value="_sans" <?php selected($SLGF_Font_Style, 'Sans-Serif' ); ?>>Sans-Serif</option>
                            <option value="_tahoma" <?php selected($SLGF_Font_Style, 'Tahoma' ); ?>>Tahoma</option>
                            <option value="_times"<?php selected($SLGF_Font_Style, 'Times New Roman' ); ?>>Times New Roman</option>
                            <option value="_trebuchet" <?php selected($SLGF_Font_Style, 'Trebuchet' ); ?>>Trebuchet</option>
                            <option value="_verdana" <?php selected($SLGF_Font_Style, 'Verdana' ); ?>>Verdana</option>
                        </optgroup>
                    </select>
                    <p class="description"><?php _e("Choose a caption font style", WEBLIZAR_SLGF_TEXT_DOMAIN ); ?>.  <a href="https://weblizar.com/lightbox-slider-pro/" target="_new">Get 500+ Google Fonts</a></p>
                </td>
            </tr>
			
			<tr>
				<th scope="row"><label><?php _e("Image Box Shadow", WEBLIZAR_SLGF_TEXT_DOMAIN ); ?></label></th>
				<td>
					<?php if($SLGF_Box_Shadow == "") $SLGF_Box_Shadow = "yes"; ?>
					<input type="radio" name="wl-box-Shadow" id="wl-box-Shadow" value="yes" <?php if($SLGF_Box_Shadow == 'yes' ) { echo "checked"; } ?>>  <i class="fa fa-check fa-2x"></i>
					<input type="radio" name="wl-box-Shadow" id="wl-box-Shadow" value="no" <?php if($SLGF_Box_Shadow == 'no' ) { echo "checked"; } ?>> <i class="fa fa-times fa-2x"></i>
					<p class="description"><?php _e("Select Yes/No option to hide or show Image Box Shadow", WEBLIZAR_SLGF_TEXT_DOMAIN ); ?>.</p>
				</td>
			</tr>
			
			<tr>
				<th scope="row"><label><?php _e('Custom CSS','WEBLIZAR_SLGF_TEXT_DOMAIN')?></label></th>
				<td>
					<?php if(!isset($SLGF_Custom_CSS)) $SLGF_Custom_CSS = ""; ?>
					<textarea id="wl-custom-css" name="wl-custom-css" type="text" class="" style="width:80%"><?php echo $SLGF_Custom_CSS; ?></textarea>
					<p class="description">
						<?php _e('Enter any custom css you want to apply on this gallery.','WEBLIZAR_SLGF_TEXT_DOMAIN')?><br>
						<?php _e('Note: Please Do Not Use <b>Style</b> Tag With Custom CSS.','WEBLIZAR_SLGF_TEXT_DOMAIN')?>
					</p>
				</td>
			</tr>
			
        </tbody>
    </table>

<?php
wp_enqueue_style('wl-slgf-font-awesome-4', WEBLIZAR_SLGF_PLUGIN_URL.'css/font-awesome-latest/css/font-awesome.min.css');
