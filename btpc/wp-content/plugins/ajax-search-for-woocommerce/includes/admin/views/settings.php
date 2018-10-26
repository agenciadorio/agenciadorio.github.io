<?php
// Zakoncz, jeżeli plik jest załadowany bezpośrednio
if ( !defined( 'ABSPATH' ) )
	exit;
?>

<div class="wrap">

	<h2><?php _e( 'Ajax Search for WooCommerce', 'ajax-search-for-woocommerce' ); ?></h2>

<?php $settings->show_navigation(); ?>
	<?php $settings->show_forms(); ?>

</div>