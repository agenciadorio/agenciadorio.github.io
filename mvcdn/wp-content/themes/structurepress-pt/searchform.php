<?php
/**
 * Search form
 *
 * @package StructurePress
 */
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<span class="screen-reader-text"><?php esc_html_e( 'Search for:', 'structurepress-pt' ); ?></span>
		<input type="search" class="form-control  search-field" placeholder="<?php esc_html_e( 'Search', 'structurepress-pt' ); ?>" value="" name="s">
	</label>
	<button type="submit" class="btn  btn-primary  search-submit"><i class="fa  fa-lg  fa-search"></i></button>
</form>