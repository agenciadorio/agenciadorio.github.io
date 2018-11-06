<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>


<div class="wpmc-nav-wrapper">
  <div class="wpmc-footer-left">
    <?php if( $show_back_to_cart_button ) : ?>
      <button data-href="<?php echo wc_get_cart_url(); ?>" id="wpmc-back-to-cart" class="button alt" type="button"><?php echo $t_back_to_cart; ?></button>
    <?php endif; ?>
  </div>
		<div class="wpmc-footer-right wpmc-nav-buttons">
			<button id="wpmc-prev" class="button button-inactive alt" type="button"><?php echo $t_previous; ?></button>
            <?php if ( $show_login_step ) : ?>
                <button id="wpmc-next" class="button button-active alt" type="button"><?php echo $t_next; ?></button>
                <button id="wpmc-skip-login" class="button button-active current alt" type="button"><?php echo $t_skip_login; ?></button>
            <?php else : ?>
                <button id="wpmc-next" class="button button-active current alt" type="button"><?php echo $t_next; ?></button>
            <?php endif; ?>
		</div>
</div>
