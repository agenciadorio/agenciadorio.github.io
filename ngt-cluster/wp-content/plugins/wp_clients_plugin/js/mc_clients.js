(function($){
$(document).ready(function(){
	
	/*======================== Clients List ========================*/
	
	mc_clientsList = $('.mc_clientsList');
	mc_clientsList_slider = $('.mc_clientsList.slider');
	il_height_percentage= 0.66;
	
	if (mc_clientsList.length )
	{
		
		mc_clientsList.each(function(){
			$(this).children('li').height($(this).children('li').width()*il_height_percentage);
		});
		
		if (mc_clientsList_slider.length )
		{
			mc_clientsList_slider.carouFredSel({
				responsive: true,
				width:'100%',
				prev: {
					button: function() {
						$(this).parent().append('<a class="mcprev" href="#"></a>');
						return $(this).parents().children(".mcprev");
					}
				},
				next: {
					button: function() {
						$(this).parent().append('<a class="mcnext" href="#"></a>');
						return $(this).parents().children(".mcnext");
					}
				},
				scroll: {
					easing:'easeInOutExpo',
					duration: 1900
				},
				items: {
					visible: {
						min: 1,
						max: 10
					}
				}
			});
			
			mc_clientsList_slider.each(function(){
				
				mcprev=$(this).parents().children(".mcprev");
				mcprev.css('top',$(this).parents().height()/2 - mcprev.height()/2);
				mcprev.css('display','none');
				
				mcnext=$(this).parents().children(".mcnext");
				mcnext.css('top',$(this).parents().height()/2 - mcnext.height()/2);
				mcnext.css('display','none');
						
			});
			
			mc_clientsList_slider.parents('.caroufredsel_wrapper').mouseenter(function(){
				$(this).children(".mcprev").css('display','block');
				$(this).children(".mcnext").css('display','block');
			});
			
			mc_clientsList_slider.parents('.caroufredsel_wrapper').mouseleave(function(){
				$(this).children(".mcprev").css('display','none');
				$(this).children(".mcnext").css('display','none');
			});
			
		}

		
		$(window).resize(function() {

			setTimeout(function() {
				mc_clientsList.each(function(){
					$(this).children('li').height($(this).children('li').width()*il_height_percentage);
				});
				
				if (mc_clientsList_slider.length )
				{
					mc_clientsList_slider.each(function(){
						$(this).height($(this).children('li').height());
						$(this).parent().height($(this).children('li').height());
					
						mcprev=$(this).parents().children(".mcprev");
						mcprev.css('top',$(this).parents().height()/2 - mcprev.height()/2);
						mcprev.css('display','none');
						
						mcnext=$(this).parents().children(".mcnext");
						mcnext.css('top',$(this).parents().height()/2 - mcnext.height()/2);
						mcnext.css('display','none');
					});
				}
				
			}, 500);	

		});
	
	}

});

})(jQuery);