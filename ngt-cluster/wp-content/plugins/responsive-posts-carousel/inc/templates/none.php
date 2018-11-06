<div class="rpc-box rpc-bg">
    <?php do_action( 'rpc_carousel_thumbnail', $post_id, $carousel_settings ); ?>
    <div class="car-caption">
        <h3 class="rpc-title">
            <a href="<?php echo get_permalink($post_id); ?>" target="<?php echo $carousel_settings['read_more_target']; ?>" class="rpc-title">
                <?php do_action( 'rpc_carousel_title', $post_id,  $carousel_settings ); ?>
            </a>
        </h3>
        <p class="rpc-content">
            <?php do_action( 'rpc_carousel_desc', $post_id, $carousel_settings); ?>
            <?php do_action( 'rpc_read_more_btn', $post_id, $carousel_settings); ?>
        </p>
    </div>
</div>