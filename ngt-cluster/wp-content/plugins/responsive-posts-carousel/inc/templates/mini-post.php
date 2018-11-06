<div class="post-style-1 rpc-box rpc-bg">
    <div class="wcp-img-wrap">
        <a href="<?php the_permalink(); ?>">
            <?php do_action( 'rpc_carousel_thumbnail', $post_id, $carousel_settings ); ?>
        </a>
    </div>
    <div class="wcp-content-wrap">
        <h3>
            <a class="rpc-title" href="<?php echo get_permalink($post_id); ?>" target="<?php echo $carousel_settings['read_more_target']; ?>" class="rpc-title">
                <?php do_action( 'rpc_carousel_title', $post_id,  $carousel_settings ); ?>
            </a>
        </h3>
        <?php if (!isset($carousel_settings['hidemeta'])) { ?>
            <span class="wcp-post-meta wcp-disable-post-meta" style="color: <?php echo (isset($carousel_settings['desc_color'])) ? $carousel_settings['desc_color'] : ''; ?>;">
                <span class="wcp-post-author"><i class="fa fa-user"></i>
                    <?php the_author_posts_link(); ?>
                </span>
                &nbsp;
                <span class="wcp-post-date"><i class="fa fa-clock-o"></i> <?php echo get_the_date(); ?></span>
            </span>
        <?php } ?>
    </div>
    <p style="clear:both;margin:0;padding:0;"></p>
</div>