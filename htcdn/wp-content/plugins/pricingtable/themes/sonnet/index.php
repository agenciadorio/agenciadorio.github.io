<?php

/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins
*/

if ( ! defined('ABSPATH')) exit;  // if direct access	


function pricingtable_body_sonnet($post_id)
	{



		include pricingtable_plugin_dir.'/templates/variables.php';

		$pricingtable_body = "";
		$pricingtable_body.= '<div class="pricingtable-area" style="background:url('.$pricingtable_bg_img.') repeat scroll 0 0 rgba(0, 0, 0, 0);" >';
		$pricingtable_body.= "<div class='pricingtable-".$post_id." pricingtable-container pricingtable-themes-".$pricingtable_themes."' >";
		$j = 1;
		
		while($j<=$pricingtable_total_column)
			{
				
				if(!empty($pricingtable_column_featured[$j]))
					{
						$pricingtable_featured = "pricingtable_featured";
					}
				else
					{
					$pricingtable_featured = "";
					}
					

					
				if(empty($pricingtable_column_width[$j])){
						
						$column_width = $pricingtable_column_width[$j];
					}
				else{
						$column_width = $pricingtable_column_width[$j];
						$column_width = str_replace('px','',$column_width);
						
					}
					
				if(empty($pricingtable_cell_header_bg_color[$j]))
					{
						$pricingtable_cell_header_bg_color[$j] = "";
					}							
					
				if(empty($pricingtable_cell_header_image[$j]))
					{
						$pricingtable_cell_header_image[$j] = "";
					}								
					
				if(empty($pricingtable_cell_price_bg_color[$j]))
					{
						$pricingtable_cell_price_bg_color[$j] = "";
					}							
					
				if(empty($pricingtable_cell_signup_bg_color[$j]))
					{
						$pricingtable_cell_signup_bg_color[$j] = "";
					}							
					
				if(empty($pricingtable_cell_signup_name[$j]))
					{
						$pricingtable_cell_signup_name[$j] = '<span class="pt-cell-blank normal">&nbsp;</span>';
					}								
					
					
					
					
					
					
			
			  
			$pricingtable_body.=  '<div style="width:'.$column_width.'px;" class="pricingtable-columns-container '.$pricingtable_featured.' column-hover-effect">';
			
			
			$pricingtable_body.=  "<div class='pricingtable-columns' >";
			$pricingtable_body.=  "<div class='pricingtable-items-container'>";
			
			$i = 1;
			while($i<=$pricingtable_total_row)
				{
					
					
				if(empty($pricingtable_cell[$i.$j]))
					{
						$pricingtable_cell[$i.$j] = '<span class="pt-cell-blank normal">&nbsp;</span>';
					}
					
				if(empty($pricingtable_cell_header_text[$j]))
					{
						$pricingtable_cell_header_text[$j] = '<span class="pt-cell-blank normal">&nbsp;</span>';
					}							
					
					
					

					
					if($i == 1)
						{
							
							$pricingtable_body.=  '<div style=" background-color:'.$pricingtable_cell_header_bg_color[$j].'" class="pricingtable-items pricingtable-header">';
							
							if(empty($pricingtable_ribbons[$pricingtable_column_ribbon[$j]]))
								{

									$pricingtable_body.=  '<span class="pricingtable-ribbon ribbon-'.$pricingtable_column_ribbon[$j].'"></span>';
							
								}
								
							else
								{
									$pricingtable_body.=  '<span style="background:url('.$pricingtable_ribbons[$pricingtable_column_ribbon[$j]].') repeat scroll 0 0 rgba(0, 0, 0, 0)" class="pricingtable-ribbon ribbon-'.$pricingtable_column_ribbon[$j].'"></span>';	
								}
								
						
							

							
							
							$pricingtable_body.=  '<span style="font-size:'.$pricingtable_cell_header_text_font_size[$j].';" class="pricingtable-header-name">'.$pricingtable_cell_header_text[$j].'</span>';
							
							if(!empty($pricingtable_cell_header_description[$j]))
								{
								$pricingtable_body.=  "<span class='pt-hd'>".$pricingtable_cell_header_description[$j]."</span>";
								}		
							
							
						
							
							
							$video_ddomain = pricingtable_get_domain($pricingtable_cell_header_image[$j]);
							
							if($video_ddomain=="youtube.com")
							
								{
									$vid = pricingtable_get_youtube_id($pricingtable_cell_header_image[$j]);
									
									$pricingtable_body.=  '<iframe src="//www.youtube.com/embed/'.$vid.'?autoplay=0&showinfo=0&controls=0" frameborder="0" allowfullscreen></iframe>';
								}
							elseif($video_ddomain=="vimeo.com")
							
								{
									$vid = pricingtable_get_vimeo_id($pricingtable_cell_header_image[$j]);
									
									$pricingtable_body.=  '<iframe src="//player.vimeo.com/video/'.$vid.'?title=0&amp;byline=0" width="" height="" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
								}									elseif(empty($pricingtable_cell_header_image[$j])){$pricingtable_body.= '';
}

								
							else
								{
								$pricingtable_body.=  '<img src="'.$pricingtable_cell_header_image[$j].'" class="pricingtable-header-image" />';	
								
								}									
							
							
							

							
							
							$pricingtable_body.=  "</div>";
						}
						
					elseif($i == 2)
						{
							$pricingtable_body.=  '<div  style=" background-color:'.$pricingtable_cell_price_bg_color[$j].'" class="pricingtable-items pricingtable-price">';
							
							$pricingtable_body.=  "<span style='font-size:".$pricingtable_cell_price_font_size[$j].";line-height:".$pricingtable_cell_price_font_size[$j].";' class='pricingtable-price-value'>".$pricingtable_cell_price[$j]."</span>";
							
							if(!empty($pricingtable_cell_price_duration[$j]))
								{
								$pricingtable_body.=  "<span class='pt-pd'>".$pricingtable_cell_price_duration[$j]."</span>";
								}
							
							
							$pricingtable_body.=  "</div>";
						}
						
					elseif($i == $pricingtable_total_row)
						{
							
							
						if(empty($pricingtable_cell_signup_button_bg_color[$j]))
							{
							$pricingtable_cell_signup_button_bg_color[$j] =  pricingtable_dark_color($pricingtable_cell_signup_button_bg_color[$j]);
							}


							
							
							
							$pricingtable_body.=  '<div style=" background-color:'.$pricingtable_cell_signup_bg_color[$j].'" class="pricingtable-items pricingtable-signup">';
							$pricingtable_body.=  '<a style=" background-color:'.$pricingtable_cell_signup_button_bg_color[$j].'" class="pricingtable-signup-name" href="'.$pricingtable_cell_signup_url[$j].'">'.$pricingtable_cell_signup_name[$j].'</a>';
							$pricingtable_body.=  "</div>";
						}								
														
					else
						{
							
							if($i%2 == 0)
								{
							$pricingtable_body.=  "<div class='pricingtable-items pricingtable-items-even'>".$pricingtable_cell[$i.$j];
							$pricingtable_body.=  "</div>";
								}
							else
								{
							$pricingtable_body.=  "<div class='pricingtable-items pricingtable-items-odd'>".$pricingtable_cell[$i.$j];
							$pricingtable_body.=  "</div>";
								}

						}
					
					

				
				$i++;
				}
				
			$pricingtable_body.=  "</div>";
			$pricingtable_body.=  "</div>";
			$pricingtable_body.=  "</div>";
			
			$j++;
		  }

		
		
		
		
		$pricingtable_body.=  "</div>";
		$pricingtable_body.=  "</div>";
		
		
		$pricingtable_body.=  '<style type="text/css">';
		
		if(!empty($pricingtable_row_bg_even))
			{
				$pricingtable_body.=  '.pricingtable-'.$post_id.' div.pricingtable-items-even{background:'.$pricingtable_row_bg_even.'}';
		
		
			}
			
		if(!empty($pricingtable_row_bg_odd))
			{
				$pricingtable_body.=  '.pricingtable-'.$post_id.'  div.pricingtable-items-odd{background:'.$pricingtable_row_bg_odd.'}';
			}
		

		
		$pricingtable_body.=  '</style>';
		
		include pricingtable_plugin_dir.'/templates/scripts.php';
		
		
		return $pricingtable_body;
	
			
	}