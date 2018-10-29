<?php

// Make sure we don't expose any info if called directly
if ( !defined( 'ABSPATH' ) )
	exit;

	$btnsx_settings = array(
		'material_admin_theme' => false,
		'wp_admin_theme' => true,
	);
	$current_color = get_user_option( 'admin_color' );
	global $_wp_admin_css_colors;

	$btnsx_form_design = new BtnsxFormDesign();
	$btnsx = new Btnsx();

?>
<div class="btnsx">
	<div class="" style="margin-right: 20px;">
		<!-- Page Content goes here -->
		<div class="row" style="margin-bottom:0;">
	    	<div class="col s12" style="background-color: <?php echo $_wp_admin_css_colors[$current_color]->colors[1]; ?>; margin-top: 20px;">
	        	<h5 style="color: #fff;	"><?php echo get_admin_page_title(); ?> <span style="font-size:0.75rem;color:<?php echo $_wp_admin_css_colors[$current_color]->colors[2]; ?>;"><?php echo BTNSX__VERSION; ?></span></h5>
	      	</div>
	    </div>
	</div>