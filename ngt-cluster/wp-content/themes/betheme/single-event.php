<?php
/**
*
*   Theme: Sonik
*   File: single-event.php
*   Role: single event 
*   Note: the maps requires QT Maps plugin
*   @author : QantumThemes <info@qantumthemes.com>
*
*
**/
?>

<style>
.accordion {
    background-color: #000;
    color: #fff;
    cursor: pointer;
    padding: 18px;
    width: 100%;
    border: none;
    text-align: left;
    outline: none;
    font-size: 15px;
    transition: 0.4s;
}

.active, .accordion:hover {
    background-color: #ccc; 
}

.panel {
    padding: 0 18px;
    display: none;
    background-color: white;
    overflow: hidden;
}
</style>

<style id="mfn-dnmc-bg-css">
body:not(.template-slider) #Header_wrapper{background-image:url(https://www.nightzinha.com.br/portal/wp-content/uploads/2016/06/pexels-photo-342520.jpeg);background-repeat:no-repeat;background-position:center top}
</style>
<?php get_header(); ?> 
	<?php while ( have_posts() ) : the_post(); ?>

		<?php 

		/**
		 * 
		 *
		 * Featured image background
		 * 
		 */
		
		get_template_part("part","featuredimage");
		
		?>

		<?php
		/**
		 * 
		 *
		 * Events data
		 * 
		 */
		
		 $e = array(
		  'id' =>  $post->ID,
		  'date' =>  esc_attr(get_post_meta($post->ID,EVENT_PREFIX.'date',true)),
		  'location' =>  esc_attr(get_post_meta($post->ID, 'qt_location',true)),
		  'street' =>  esc_attr(get_post_meta($post->ID, 'qt_address',true)),
		  'city' =>  esc_attr(get_post_meta($post->ID, 'qt_city',true)),
		  'country' =>  esc_attr(get_post_meta($post->ID, 'qt_country',true)),
		  'permalink' =>  esc_url(get_permalink($post->ID)),
		  'title' =>  esc_attr($post->post_title),
		  'phone' => esc_attr(get_post_meta($id, 'qt_phone',true)),
		  'website' => esc_attr(get_post_meta($id, 'qt_link',true)),
		  'facebooklink' => esc_attr(get_post_meta($id,EVENT_PREFIX . 'facebooklink',true)),
		  'coord' => esc_attr(get_post_meta($id,  'qt_coord',true)),
		  'email' => esc_attr(get_post_meta($id,  'qt_email',true))
		  //'thumb' => $thumb
		);
		?>
		<br> 
		<div id="qwjquerycontent">
			<article  <?php post_class ( "qt-content qt-event-content container".( has_post_thumbnail()? "qt-fade-to-paper": "")); ?> id="post<?php the_ID(); ?>" >
				<header class="qt-header">
						<h4 class="container">
						
						</h4>
						<div class="container"> 
						<?php $qw_post_hide_title =  get_post_meta( get_the_ID(), 'qw_post_hide_title', true ); if("1" !==  $qw_post_hide_title):   ?>
							<h1 class="qt-titdeco-eq text-center center"></h1>
						<?php endif; ?>
						
						<?php 
						/**
						 *
						 *	Flipclock
						 *
						 * 
						 */
						if($e['date'] > date("Y-m-d")) {
						?>
						<div class="qtFlipClock" data-deadline="<?php echo esc_js($e['date']); ?>"></div>
						<?php  
						}
						?>
						</div>				
				</header>
				
				<table cellspacing="2" cellpadding="2" width="100%" border="0">
  <tbody>
    <tr>
      <td valign="top">
							<?php  
							$has_post_thumbnail = has_post_thumbnail();
							if($has_post_thumbnail){
								$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large' );
								$picture = $thumb['0'];
							}

							 if($has_post_thumbnail){ ?>
									<a href="<?php echo esc_attr($picture); ?>" class="qw-imgfx">
									   <?php  the_post_thumbnail("medium" , array("class" => "img-responsive")); ?>
									</a>
							<?php } ?><br>
      </td>
      <td valign="top">	<big><big> <b> <?php echo esc_attr(date_i18n( get_option("date_format", "d M Y"), strtotime( get_post_meta($post->ID, "eventdate", true) ))); ?>
							<?php 
				                if($e['location']  != ''){
				                    ?> [<?php
				                    echo esc_attr($e['location'] );
				                    ?>]<?php
				                }
				            ?></big></big></b>  <br>
      </td>
    </tr>
  </tbody>
</table>
<br>


				<div class="container">
					<div class="row">
						<div class="wpb_column vc_column_container vc_col-sm-3"> 

							<div class="qt-tags">
								<?php  echo get_the_term_list( $post->ID, 'eventtype', '', ' ', '' );  ?>
							</div>
							
						</div>
						<div class="wpb_column vc_column_container vc_col-sm-9 qt-event-details">
							<!-- h3 class="qt-titdeco qt-border top thick">
								<?php echo esc_attr__("Event info", "sonik"); ?>
							</h3 -->
							<h3 class="qt-border top thick" style="margin: 0; padding: 0;" ></h3>
							<?php 
							/*
							*
							*
							*   Print the data out
							*
							*
							*/
							echo '
							<table class="table eventtable ">
								<tbody>
								'.(($e['date']!='') ? '<tr><th>'.esc_attr__("Data","sonik").':</th> <td>'. esc_attr( date( get_option("date_format", "d M Y"), strtotime( $e['date'] ))).'</td></tr>' : '').'
								'.(($e['location']!='') ? '<tr><th>'.esc_attr__("Local","sonik").':</th> <td>'.esc_attr($e['location']).'</td></tr>' : '').'
								'.(($e['street']!='' || $e['city'] !='') ? '<tr><th>'.esc_attr__("Endereço","sonik").':</th> <td> '.esc_attr($e['street']).' '.$e['city'].'</td></tr>' : '').'
								'.(($e['phone']!='') ? '<tr><th>'.esc_attr__("Telefone","sonik").':</th> <td>'.esc_attr($e['phone']).'</td></tr>' : '').'
								'.(($e['website']!='') ? '<tr><th>'.esc_attr__("Website","sonik").':</th> <td><a href="'.esc_attr($e['website']).'" target="_blank" rel="external nofollow">'.esc_attr($e['website']).'</a>'.'</td></tr>' : '').'
								'.(($e['facebooklink']!='') ? '<tr><th>'.esc_attr__("Evento","sonik").':</th> <td><a href="'.esc_attr($e['facebooklink']).'" target="_blank" rel="external nofollow">'.esc_attr($e['facebooklink']).'</a>'.'</td></tr>' : '').'
								</tbody>
							</table>';  
							?>

							<?php
							/*
							*
							*
							*   Print the BUY links
							*
							*
							*/
							?>
							<p class="qw-buylinks">
							<?php
								/*$eventslinks= qantumthemes_get_group(EVENT_PREFIX.'repeatablebuylinks',$id);   
								if(is_array($eventslinks)){
									if(count($eventslinks)>0){
										foreach($eventslinks as $b){ 
											if(isset($b['cbuylink_url'])){
												if($b['cbuylink_url']!=''){
													echo '
													<a href="'.esc_url($b['cbuylink_url']).'" target="_blank" rel="external nofollow" class="btn qt-btn-ghost">
													 <i class="fa fa-cart"></i> '.esc_attr($b['cbuylink_anchor']).'</a> 
													';
												}
											}
										}
									}
								}*/
							?>
				                <?php
				                  $vendaPeloSite = get_field("venda_pelo_site");
				                  if ($vendaPeloSite == 1) {
				                    $IdsProdutos = explode(',', trim(get_field("codigos")));
				                    for ($i=0; $i < count($IdsProdutos); $i++) {
				                    	if(get_post_status($IdsProdutos[$i]) == 'trash')
				                    		continue;
				                ?>

				                		<a href="<?php echo do_shortcode('[add_to_cart_url id="' . $IdsProdutos[$i] . '"]'); ?>" class="btn btn-primary"><?php echo esc_attr__("Comprar", "sonik"); ?></a>
				                <?php
				                		break;
				                    }
				                ?>
				                <?php
				                  } else {
				                    $Link = get_field("link_redirecionamento");
				                    if($Link != NULL) {
				                ?>
				                  <a href="<?php echo $Link; ?>" target="_blank" class="btn btn-primary"><?php echo esc_attr__("Comprar", "sonik"); ?></a>
				                <?php
				                    }
				                  }
				                ?>
							</p>

							<h3 class="qt-border top thick" style="margin: 0; padding: 0;"></h3>

							<div class="row">
							<?php
								$vendaPeloSite = get_field("venda_bebida");
								if ($vendaPeloSite == 1) {
									echo '<div class="col s12"><h4>VENDA DE BEBIDAS</h4></div>';
									$IdsProdutos = explode(',', trim(get_field("codigos_bebidas")));
									for ($i=0; $i < count($IdsProdutos); $i++) {
										if($IdsProdutos[$i] != null && $IdsProdutos[$i] > 0)
							?>
									<div class="col s6 m6 l4">
										<p style="line-height: 28px;" ><?php echo get_the_title($IdsProdutos[$i]); ?></p>
										<?php echo get_the_post_thumbnail($IdsProdutos[$i]); ?>
										<a style="width: 100%;" href="<?php echo do_shortcode('[add_to_cart_url id="' . $IdsProdutos[$i] . '"]'); ?>" class="btn btn-primary"><?php echo esc_attr__("Comprar", "sonik"); ?></a>
									</div>
							<?php
									}
								}
							?>
							</div>

							<h3 class="qt-border top thick" style="margin: 30px 0px 0px 0px; padding: 0;"></h3>

							
						
<button class="accordion"> <i class="vc_tta-icon fa fa-map-marker"></i>    <b> VER MAPA </b></button>
<div class="panel">
  <p><?php

								$Mapa = get_field("gmaps_shortcode");
				                if($Mapa != NULL) {
									echo '<br>';
									echo do_shortcode($Mapa);
									echo '<br>';
								}
							?>
							</p>
</div>	


<script>
var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
    acc[i].addEventListener("click", function() {
        this.classList.toggle("active");
        var panel = this.nextElementSibling;
        if (panel.style.display === "block") {
            panel.style.display = "none";
        } else {
            panel.style.display = "block";
        }
    });
}
</script>

<hr/> 
							<?php
							/*
							*
							*	Print map if declared
							*
							*/
							//echo (($e['coord']!='') ? qt_do_eventmap($e['coord'],get_the_title()):'');
							?>
							<?php //if($e['location']){ ?>
							<!-- div class="qt_dynamicmaps" id="map<?php echo esc_attr($post->ID); ?>" data-colors="QT_map_dark" data-coord="<?php echo esc_attr($e['coord']); ?>" data-locationname="<?php echo esc_attr($e['location']); ?>"></div -->
							<?php //} ?>
							<?php the_content(); ?>
						</div>
					   
					</div>
				</div>
			</article>
		</div>
		<br><br>
		<?php //if ( comments_open() || '0' != get_comments_number() ){  ?>
		<!-- div class=" qt-comments-section">
			<div class="container">
				<div class="qt-reading-opt">
					<?php //comments_template(); ?>
				</div>
			</div>
		</div -->         
		<?php //} ?>

		<?php
		  wp_link_pages( array(
			'before' => '<div class="page-links">' . esc_attr__( 'Pages:', "sonik" ),
			'after'  => '</div>',
		  ) );
		?>

	<?php endwhile; // end of the loop. ?>
<?php get_footer(); ?>