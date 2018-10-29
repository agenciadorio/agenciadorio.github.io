<?php	
/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins
*/

if ( ! defined('ABSPATH')) exit;  // if direct access	



	if(empty($_POST['pricingtable_hidden']))
		{
			$pricingtable_ribbons = get_option( 'pricingtable_ribbons' );
			
			
		}
	else
		{
					
				
		if($_POST['pricingtable_hidden'] == 'Y') {
			//Form data sent

			$pricingtable_ribbons = stripslashes_deep($_POST['pricingtable_ribbons']);
			update_option('pricingtable_ribbons', $pricingtable_ribbons);
			
		
			
					

			?>
			<div class="updated"><p><strong><?php _e('Changes Saved.' ); ?></strong></p></div>

			<?php
		} 
	}
	
	
	
    $pricingtable_customer_type = get_option('pricingtable_customer_type');
    $pricingtable_version = get_option('pricingtable_version');
	
	
	
	
	
?>





<div class="wrap">

	<div id="icon-tools" class="icon32"><br></div><?php echo "<h2>".__(pricingtable_plugin_name.' Settings')."</h2>";?>
		<form  method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
	<input type="hidden" name="pricingtable_hidden" value="Y">
        <?php settings_fields( 'pricingtable_plugin_options' );
				do_settings_sections( 'pricingtable_plugin_options' );
			
		?>
        
        
	<div class="para-settings">
        <ul class="tab-nav"> 
            <li nav="1" class="nav1 active">Options</li>
            <li nav="2" class="nav2">Help</li>
        </ul> <!-- tab-nav end -->
    
		<ul class="box">
            <li style="display: block;" class="box1 tab-box active">
				<div class="option-box">
                    <p class="option-title">Ribbons</p>
                    <p class="option-info">You can use your own ribbons icon by inserting ribbon url to text field, image size for ribbons must be same as 90px × 24px</p>
                 	<table>
                    
                    <?php 
					
					
					
								$pricingtable_ribbons_des = array(	
					
											
											
														'dis-10'=>'Discount 10%',
														'dis-20'=>'Discount 20%',												
														'dis-30'=>'Discount 30%',													
														'dis-40'=>'Discount 40%',
														'dis-50'=>'Discount 50%',
														'dis-60'=>'Discount 60%',											
														'dis-70'=>'Discount 70%',													
														'dis-80'=>'Discount 80%',
														'dis-90'=>'Discount 90%',
														'dis-100'=>'Discount 100%',	
														'free'=>'Free',
														'fresh'=>'Fresh',						
														'gift'=>'Gift',												
														'hot'=>'Hot',													
														'new'=>'New',
														'top'=>'Top',						
														'save'=>'Save',												
														'sale'=>'Sale',	
														'pro'=>'Pro',
														'best'=>'Best',	
											
											
											
											
											
											
											
														);	
					
					
					
					
					
					
					
					
						if(empty($pricingtable_ribbons))
							{
								$pricingtable_ribbons = array(	
														'dis-10'=>'dis-10',
														'dis-20'=>'dis-20',												
														'dis-30'=>'dis-30',													
														'dis-40'=>'dis-40',
														'dis-50'=>'dis-50',
														'dis-60'=>'dis-60',											
														'dis-70'=>'dis-70',													
														'dis-80'=>'dis-80',
														'dis-90'=>'dis-90',
														'dis-100'=>'dis-100',	
														'free'=>'free',
														'fresh'=>'fresh',						
														'gift'=>'gift',												
														'hot'=>'hot',													
														'new'=>'new',
														'top'=>'top',						
														'save'=>'save',												
														'sale'=>'sale',	
														'pro'=>'pro',
														'best'=>'best',						
											
														);
														
														
														
														
							foreach($pricingtable_ribbons as $key =>$value)
								{
									echo '<tr>';
									echo '<td width="120">'.$pricingtable_ribbons_des[$key].'</td>';
									echo '<td width="120"><img src="'.pricingtable_plugin_url.'css/ribbons/'.$value.'.png" /></td>';															
									echo '<td width="320"><input class="pricingtable_ribbons" name="pricingtable_ribbons['.$key.']" type="text" value="'.pricingtable_plugin_url.'css/ribbons/'.$value.'.png"</td>';
									echo '</tr>';
									
								}
														
														
							}
						else
							{
						foreach($pricingtable_ribbons as $key =>$value)
							{
								echo '<tr>';
								echo '<td width="120">'.$pricingtable_ribbons_des[$key].'</td>';
								echo '<td width="120"><img src="'.$value.'" /></td>';															
								echo '<td width="320"><input size="30" class="pricingtable_ribbons" name="pricingtable_ribbons['.$key.']" type="text" value="'.$value.'"</td>';
								echo '</tr>';
								
							}
							}

							
							

						
						
					?>
                    	
                        
                        
                        
                    </table>
                </div>
                
				<div class="option-box">
                    <p class="option-title"></p>
                    <p class="option-info"></p>
                 
                </div>

            </li>
            <li style="display: none;" class="box2 tab-box">
            
				<div class="option-box">
                    <p class="option-title">Need Help ?</p>
                    <p class="option-info">Feel free to contact with any issue for this plugin, Ask any question via forum <a href="<?php echo pricingtable_qa_url; ?>"><?php echo pricingtable_qa_url; ?></a> <strong style="color:#139b50;">(free)</strong><br />

    <?php

    if($pricingtable_customer_type=="free")
        {
    
            echo 'You are using <strong> '.$pricingtable_customer_type.' version  '.$pricingtable_version.'</strong> of <strong>'.pricingtable_plugin_name.'</strong>, To get more feature you could try our premium version. ';
            
            echo '<br /><a href="'.pricingtable_pro_url.'">'.pricingtable_pro_url.'</a>';
            
        }
    else
        {
    
            echo 'Thanks for using <strong> premium version  '.$pricingtable_version.'</strong> of <strong>'.pricingtable_plugin_name.'</strong> ';	
            
            
        }
    
     ?>       

                    
                    </p>

                </div>
				<div class="option-box">
                    <p class="option-title">Submit Reviews...</p>
                    <p class="option-info">We are working hard to build some awesome plugins for you and spend thousand hour for plugins. we wish your three(3) minute by submitting five star reviews at wordpress.org. if you have any issue please submit at forum.</p>
                	<img class="pricingtable-pro-pricing" src="<?php echo pricingtable_plugin_url."css/five-star.png";?>" /><br />
                    <a target="_blank" href="<?php echo pricingtable_wp_reviews; ?>">
                		<?php echo pricingtable_wp_reviews; ?>
               		</a>
                    
                    
                    
                </div>
				<div class="option-box">
                    <p class="option-title">Please Share</p>
                    <p class="option-info">If you like this plugin please share with your social share network.</p>
                    <?php
                    
						echo pricingtable_share_plugin();
					?>
                </div>
				<div class="option-box">
                    <p class="option-title">Video Tutorial</p>
                    <p class="option-info">Please watch this video tutorial.</p>
                	<iframe width="640" height="480" src="<?php echo pricingtable_tutorial_video_url; ?>" frameborder="0" allowfullscreen></iframe>
                </div>
            
            
            </li>  

        </ul>
    
    
    
    </div>    




<p class="submit">
                    <input class="button button-primary" type="submit" name="Submit" value="<?php _e('Save Changes' ) ?>" />
                </p>
		</form>


</div>
