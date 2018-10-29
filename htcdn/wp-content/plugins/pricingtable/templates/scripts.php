<?php

/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins
*/

if ( ! defined('ABSPATH')) exit;  // if direct access	


		if($pricingtable_hide_empty_row=='yes')
			{
				$pricingtable_body .= '<script>

					jQuery(document).ready(function($)
						{
							 $(".pt-cell-blank").parent().fadeOut();
						})
	




				</script>';	
			}
			
			
			

/*



	
	
					jQuery(document).on("mouseenter", ".pricingtable-columns-container", function()
						{
							jQuery(this).addClass("'.$pricingtable_hover_effect.'");
						})
						
					jQuery(document).on("mouseleave", ".pricingtable-columns-container", function()
						{
							
							
							setTimeout(function(){ 
							jQuery(".pricingtable-columns-container").removeClass("'.$pricingtable_hover_effect.'"); }, 2000);
							
							
						
						
					});




*/