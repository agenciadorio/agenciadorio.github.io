<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package ThemeMove
 */

if ( ! is_active_sidebar( 'sidebar' ) ) {
	return;
}
?>
<div class="col-md-3">
	<aside class="sidebar" itemscope="itemscope" itemtype="http://schema.org/WPSideBar">
		<?php dynamic_sidebar( 'sidebar' ); ?>
	</aside>
</div>
