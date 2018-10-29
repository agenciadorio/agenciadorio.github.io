
</div><!-- #middle -->
         
		<br class="clear" />
	</div><!-- #layout -->
    
        <div id="footer">
        
        	<div id="endereco">
				<?php  if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Endereço') ) :  endif; ?>
			</div>
                    
                    	<div id="proposta">
            	<img src="<?php bloginfo('stylesheet_directory'); ?>/imagens/botaosoli.png"><a href="<?php bloginfo('url'); ?>/solicite-uma-proposta/">Solicite uma proposta</a>
             </div>       
          
          
          <div class="prod">
          	<a href="http://www.basesoft.com.br" target="_blank">
          		<img src="<?php bloginfo('stylesheet_directory'); ?>/imagens/footer.png">
            </a>
          </div>  
          
        </div><!-- #footer -->
        
	</div><!-- .bg_footer -->
	
        <script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/selector.js"></script>
        <?php wp_footer(); ?>
        <?php if(is_home()){ ?>
        <script type="text/javascript">
        jQuery(document).ready(function() { // inicio jquery
			jQuery('#slide').cycle({ 
   			 	fx:     'wipe', 
    			pager:  '#nav' 
			});

        }); // fim jquery
        </script>
        <?php } ?>
        <script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/slide.js"></script>
        <script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/hover.js"></script>
        <script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/util.js"></script>
		
    </body>
</html>

