
jQuery(document).ready(function($)
	{
		
		
		$(document).on('click', '.tabs-header', function()
			{	
				if($(this).parent().hasClass('active'))
					{
					$(this).parent().removeClass('active');
					}
				else
					{
						$(this).parent().addClass('active');
					}
				
			
			})	
		
		$(document).on('click', '#tabs_metabox .reset-active', function()
			{	
				$('input[name="tabs_active"]').prop('checked', false);
				
			
			})			
		
		
		
		
		
		
		
		
		$(document).on('click', '.tabsicon-custom', function()
			{
				var iconid = $(this).attr("iconid");
				var icon_url = prompt("Please insert icon url","");
				
				if(icon_url != null)
					{
	
						$(this).css("background-image",'url('+icon_url+')');
							
						$(".tabs_content_title_icon_custom"+iconid).val(icon_url);
					}
				
				
				})
				
				
		
		$(document).on('click', '.tabsicon', function()
			{

				$(".iconholder").fadeIn();
				
				var iconid = $(this).attr("iconid");

				$(".iconslist i").attr("iconid",iconid);

			})
		
		$(document).on('click', '.iconslist i', function()
			{

				var iconname = $(this).attr("iconname");
				var iconid = $(this).attr("iconid");
				
				
				$(".tabsicon"+iconid+" i").removeAttr('class');			
				$(".tabsicon"+iconid+" i").addClass("fa fa-"+iconname);
				$(".tabs_content_title_icon"+iconid).val(iconname);

				
				$(".iconholder").fadeOut();

			})		
		
		
		
		
		

		$(document).on('click', '.tabs-content-buttons .add-tabs', function()
			{	

				var row = $.now();
						
				//alert(row);

				
				$(".tabs-content").append('<div class="items" ><div class="tabs-header"><div class="removeTabs">X</div><div class="tabsicon tabsicon'+row+'" iconid="'+row+'"><i  class="fa fa-plane"></i><input  type="hidden" class="tabs_content_title_icon tabs_content_title_icon'+row+'" name="tabs_content_title_icon['+row+']" value="plane" /></div><div title="Custom Iocn." class="tabsicon-custom tabsicon-custom'+row+'" iconid="'+row+'"><input  type="hidden" class="tabs_content_title_icon_custom tabs_content_title_icon_custom'+row+'" name="tabs_content_title_icon_custom['+row+']" value="" /></div><input width="100%" placeholder="Tabs Header" type="text" name="tabs_content_title['+row+']" value="" /></div><div class="tabs-panel"><textarea placeholder="Tabs Content" name="tabs_content_body['+row+']" ></textarea></div></div>');
				
				
				
				setTimeout(function(){
					
					$(".tabs-content tr:last-child td").removeClass("tab-new");
					
					}, 300);
				
				
				
				
				
			})	
		
		
		
		$(document).on('click', '#tabs_metabox .removeTabs', function()
			{	
				if (confirm('Do you really want to delete this tab ?')) {
					
					$(this).parent().parent().remove();
				}
				
				
				
				
				
			})	
	
 		

	});	

