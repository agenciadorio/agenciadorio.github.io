<?php
extract(shortcode_atts(array(
    'icon' => '',
    'label' => '',
    'placeholder' => esc_html__('Enter Search Keywords...','construction'),
	'el_class' =>''
), $atts));
?>
<div class="site-searchform search wow fadeInRight <?php echo esc_attr( $el_class );?>">
	<form id="site-searchform" class="clearfix" action="<?php echo esc_url(home_url('/')); ?>" method="get">
	    <input type="text" name="s" id="s" placeholder="<?php echo esc_attr( $placeholder) ;?>" value="<?php the_search_query(); ?>" />
	    <button type="submit" id="searchsubmit">
	    	<?php if( ! empty( $icon ) )echo '<i class="'. esc_attr( $icon ) .'"></i>';
	    		if( ! empty( $label ) ){
	    			echo esc_attr( $label );
	    		}
	    	 ?>
	    </button>
	</form>
</div>