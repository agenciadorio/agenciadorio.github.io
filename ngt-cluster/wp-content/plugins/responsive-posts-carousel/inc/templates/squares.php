<div class="ih-item <?php echo $style; ?> rpc-box">
    <a href="<?php the_permalink(); ?>" target="<?php echo (isset($carousel_settings['read_more_target'])) ? $carousel_settings['read_more_target'] : '' ; ?>">
        <div class="img">
            <?php do_action( 'rpc_carousel_thumbnail', $post_id, $carousel_settings ); ?>
        </div>
        <div class="info rpc-bg">
            <h3 class="rpc-title rpc-title-bg">
                <?php do_action( 'rpc_carousel_title', $post_id,  $carousel_settings ); ?>
            </h3>
            <p class="rpc-content">
                <?php do_action( 'rpc_carousel_desc', $post_id, $carousel_settings); ?>
            </p>
        </div>
    </a>
</div>