<?php get_header(); ?>

    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>    
            <div id="post-<?php the_ID(); ?>" <?php post_class('pageconteudo'); ?>>
                    <?php the_breadcrumb(); ?>
                    <h2 class="ntitle"><?php the_title(); ?> </h2>
                    <div class="cntt">
                            <?php the_content('<br /><br />...continue lendo.'); ?>
                    </div>

            </div>
            <br class="clear" />
     <?php endwhile; ?>    

     <?php else : ?>
     <h2 class="center"><strong>N&atilde;o foi encontrado nenhum conte&uacute;do para essa p&aacute;gina.</strong></h2>
    <?php endif; ?>     

    <div class="voltar" style="text-align:right;">
            <a href="<?php bloginfo('url'); ?>">Home</a> 
            <a href="javascript:history.go(-1)">Voltar</a>
            <?php edit_post_link('Editar', '  ', ''); ?>
    </div>
     
<?php get_footer(); ?>
     