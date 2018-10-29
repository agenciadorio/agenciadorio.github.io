<?php get_header(); ?>

<div class="pageconteudo">
	<?php the_breadcrumb(); ?>
    <h2 class="ntitle"><?php single_cat_title(); ?></h2>

    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>    
        <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <div class="cntt">
                <h3 class="title"><a href="<?php the_permalink() ?>" title="<?php the_title();?>"><?php the_title(); ?></a></h3>
                <div class="cnt">
                    <?php if ( has_post_thumbnail() ) { 
                        the_post_thumbnail( 'full' ); 
                    }?>
                    <?php the_content('<br /><br />...continue lendo.'); ?>
                    <br class="clear" />
                </div>
                <div class="edit"><?php edit_post_link('Editar not&iacute;cia', '[', ']'); ?></div>
            </div>
        </div>
     <?php endwhile; ?>    

     <?php else : ?>
     <h2 class="center"><strong>N&atilde;o foi encontrado nenhum conte&uacute;do para essa p&aacute;gina.</strong></h2>
    <?php endif; ?>     

    <div class="voltar" style="text-align:right;">
            <a href="<?php bloginfo('url'); ?>">Home</a> 
            <a href="javascript:history.go(-1)">Voltar</a>
            <?php edit_post_link('Editar', '  ', ''); ?>
    </div>
    
</div>
     
<?php get_footer(); ?>