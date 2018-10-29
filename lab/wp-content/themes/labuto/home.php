    <?php get_header() ; ?>    
        <div id="slideshow">
        	<div id="nav"></div>
        <div id="slide">
         <?php echo do_shortcode('[nggallery id=2 template=banner]');?>
        </div>
        </div>
        
			<div id="menumeio">
				<ul>
					<li class="areadeatuacao"><a href="<?php bloginfo('url'); ?>/area-de-atuacao/"><span>Área de Atuação</span></a></li>
                    <li class="servicoscontabeis"><a href="<?php bloginfo('url'); ?>/servicos/servicos-contabeis/"><span>Serviços Contábeis></span></a></li>
                    <li class="admdecondominios"><a href="<?php bloginfo('url'); ?>/adm-de-condominios/"><span>Adm. de Condomímios</span></a></li>
                    <li class="advocacia"><a href="<?php bloginfo('url'); ?>/advocacia/"><span>Advocacia</span></a></li>
				</ul>
			</div>
            
          <div id="sidebaresq">
          		<div id="videos">
            		<h1>Galeria de Vídeos</h1>
                 </div>
                 	<br class="clear">
                    	<center>
							<?php  if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Vídeos') ) :  endif; ?>
                        </center>
            </div>
            
           
          <div id="meio">
          		  <?php
                        query_posts('posts_per_page=3&cat=4');
                        if (have_posts ()) :
                    ?>
              <div id="noticias">
                    <h1>Notícias</h1>
                    <div class="post">
                        <ul>
                        <?php while (have_posts()) : the_post(); ?>
                            <li>
                                <div class="data">
                                    <span class="dia"><?php the_time('j'); ?></span>
                                    <span class="mes"><?php the_time('M'); ?></span>
                                </div>
                                <div class="titulo">
                                    <a href="<?php the_permalink() ?>"><?php the_title(); ?></a>
                                </div>
                                <br class="clear" />
                            </li>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                   <div class="mais">
                   		<a href="<?php bloginfo('url'); ?>/noticias">Leia todas as notícias</a>
                   </div>
              </div>
              	<?php 
                        endif; 
                        wp_reset_query();
                    ?> 
          </div>
          
	<div id="sidebardir">
		<div id="links">
			<h3>Links úteis</h3>
				<div class="link">
                    <ul>
                        <?php 
                        // MENU PRINCIPAL - Itens devem ser configurados no painel (Aparencia > Menus).
                            $config = array(
                                'theme_location'  => 'Links',
                                'menu'            => '', 
                                'container'       => false, 
                                'container_class' => 'menu-{menu slug}-container', 
                                'container_id'    => 'nav-menu',
                                'menu_class'      => '', 
                                'menu_id'         => '',
                                'echo'            => true,
                                'fallback_cb'     => 'wp_page_menu',
                                'before'          => '',
                                'after'           => '',
                                'link_before'     => '- ',
                                'link_after'      => '',
                                'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                                'depth'           => 1,
                                //'walker'          => 
                            );
                            wp_nav_menu($config); 
                        ?>
                    </ul>
                    <div class="veja">
                        <a href="<?php bloginfo('url'); ?>/links-uteis/">Veja todos os links</a>
                    </div>
				</div>   
		</div>
        
	</div>

			<?php get_footer() ; ?> 		