<?php
$url = esc_url(get_permalink());
$title = esc_attr(get_the_title());
?>
<ul class="list-inline">
	<li><a href="http://www.facebook.com/share.php?u=<?php echo esc_attr($url);?>&title=<?php echo esc_attr($title);?>"><i class="social-icon fa fa-facebook"></i></a></li>
	<li><a href="http://twitter.com/home?status=<?php echo esc_attr($title);?>+<?php echo esc_attr($url);?>"><i class="social-icon fa fa-twitter"></i></a></li>
	<li><a href="https://plus.google.com/share?url=<?php echo esc_attr($url);?>"><i class="social-icon fa fa-google-plus"></i></a></li>
	<li><a href="http://pinterest.com/pin/create/button/?url=<?php echo esc_attr($url);?>&description=<?php echo esc_attr($title);?>"><i class="social-icon fa fa-youtube"></i></a></li>
</ul>