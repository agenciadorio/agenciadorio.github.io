<?php
/*
Template Name: Notícias
*/

    get_header();
	
?>
<div class="pageconteudo">
	<?php the_breadcrumb(); ?>
    <h2 class="ntitle">Notícias</h2>
    
    
    <?php
        $noticias = new WP_Query( array( 'posts_per_page' => 10, 'paged' => get_query_var('paged'), 'cat' => 4 ) );
        while( $noticias->have_posts() ) : $noticias->the_post();
    ?>
    
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
    
    <?php
        endwhile;
        if(function_exists('wp_pagenavi')) { wp_pagenavi( array( 'query' => $noticias ) ); } 
        wp_reset_postdata(); 
    ?>
</div>
<?php get_footer(); ?>
