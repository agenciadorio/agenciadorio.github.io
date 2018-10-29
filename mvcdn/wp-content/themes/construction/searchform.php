<div id="site-searchform">
	<form action="<?php echo esc_url(home_url('/')); ?>" method="get">
	    <input type="text" name="s" id="s" class="search-txt form-item" placeholder="<?php esc_html_e('Search...', 'construction'); ?>" value="<?php the_search_query(); ?>" />
	    <button type="submit" class="search-btn btn-1"><i class="fa fa-search"></i></button>
	</form>
</div>