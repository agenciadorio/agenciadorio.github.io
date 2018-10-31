<?php
/**
 * Template Name: Archives
 *
 * @package Betheme
 * @author Muffin Group
 */

get_header(); 
?>


	<article  <?php post_class ( "qt-content ".( has_post_thumbnail()? "qt-fade-to-paper qt-animatedheader": "")); ?> id="page<?php the_ID(); ?>" >
		<?php 
		/**
		 *	
		 * Featured image background
		 * 
		 */
		get_template_part("part","featuredimage");
		?>
		<header class="qt-header">
			<div class="container">
				<?php if($paged > 1){ ?>
					<h2><?php get_template_part('part','archivetitle' ); ?></h2>
				<?php } else { ?>
					<h1 class="qt-titdeco-eq text-center center"><?php get_template_part('part','archivetitle' ); ?></h1>
				<?php } ?>
			</div>
		</header>
		<main class="container" role="main">

			<!-- div class="qt-tags" >
				<?php
					$url = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
					if (strpos($url, 'festas') === false) {
					    echo '<a href="' . get_site_url() . '/festas" rel="tag" >Todas</a>';
					}
				?>
				<?php
					$terms = get_terms('eventtype');
					foreach ( $terms as $term ) {
				?>
					<a href="<?php echo get_site_url(); ?>/eventtype/<?php echo $term->slug; ?>" ><?php echo $term->name; ?></a>
				<?php
					}
				?><?php
					$terms = get_terms('eventtype');
					foreach ( $terms as $term ) {
				?>
					<a href="<?php echo get_site_url(); ?>/eventtype/<?php echo $term->slug; ?>" ><?php echo $term->name; ?></a>
				<?php
					}
				?>
			</div -->
			<?php
				$EstiloSelecionado = $_GET["estilo"] ? $_GET["estilo"] : '';
				$BairroSelecionado = $_GET["bairro"] ? $_GET["bairro"] : '';
				$BoateSelecionado =  $_GET["boate"]  ? $_GET["boate"]  : '';
				$PrecoSelecionado =  $_GET["preco"]  ? $_GET["preco"]  : '';
			?>
			<form id="EventFilters" class="row" action=""  >
				<div class="col s12 m3 l3" >
					<label>Estilo</label>
					<select name="estilo" >
						<option <?php echo ($EstiloSelecionado == '' ? "selected='selected'" : ""); ?> value >Todos</option>
						<?php
							$EstilosObj = get_field_object('field_59bda5120f042');
							$Estilos = $EstilosObj['choices'];

							foreach ($Estilos as $key => $value) {
						?>
							<option <?php echo ($EstiloSelecionado == $value ? "selected='selected'" : ""); ?> value="<?php echo $value; ?>" ><?php echo $value; ?></option>
						<?php
							}
						?>
					</select>					
				</div>
				<div class="col s12 m3 l3" >
					<label>Bairro</label>
					<select name="bairro" >
						<option <?php echo ($BairroSelecionado == '' ? "selected='selected'" : ""); ?> value >Todos</option>
						<?php
							$BairrosObj = get_field_object('field_59bda4af0f041');
							$Bairros = $BairrosObj['choices'];
							foreach ($Bairros as $key => $value) {
						?>
							<option <?php echo ($BairroSelecionado == $value ? "selected='selected'" : ""); ?> value="<?php echo $value; ?>" ><?php echo $value; ?></option>
						<?php
							}
						?>
					</select>					
				</div>
				<div class="col s12 m3 l3" >
					<label>Local</label>
					<select name="boate" >
						<option <?php echo ($BoateSelecionado == '' ? "selected='selected'" : ""); ?> value >Todos</option>
						<?php
							$BoatesObj = get_field_object('field_59bda5370f043');
							$Boates = $BoatesObj['choices'];
							foreach ($Boates as $key => $value) {
						?>
							<option <?php echo ($BoateSelecionado == $value ? "selected='selected'" : ""); ?> value="<?php echo $value; ?>" ><?php echo $value; ?></option>
						<?php
							}
						?>
					</select>					
				</div>
				<div class="col s12 m3 l3" >
					<label>Preços</label>
					<select name="preco" >
						<option <?php echo ($PrecoSelecionado == '' ? "selected='selected'" : ""); ?> value >Todos</option>
						<option <?php echo ($PrecoSelecionado == '0..20' ? "selected='selected'" : ""); ?> value="0..20" >$0 até $20</option>
						<option <?php echo ($PrecoSelecionado == '20..50' ? "selected='selected'" : ""); ?> value="20..50" >$20 até $50</option>
						<option <?php echo ($PrecoSelecionado == '50..80' ? "selected='selected'" : ""); ?> value="50..80" >$50 até $80</option>
						<option <?php echo ($PrecoSelecionado == '80' ? "selected='selected'" : ""); ?> value="80" >+$80</option>
					</select>					
				</div>
				<div class="col s12" >
					<p>
	                    <button type="submit" class="btn btn-primary">Filtrar</button>
	                </p>
                </div>		
			</form>

			<?php 
			if(is_page() && $paged <= 1){
				the_content();
			}
			?>

			<ul class="collapsible popout qt-archiveevent-list" data-collapsible="accordion" id="itemslist">
			<?php
			if(is_page()){
				$args = array(
					 'post_type' => 'event',
                        'posts_per_page' => 6,
                        'post_status' => 'publish',
                        'orderby' => 'meta_value',
                        'order'   => 'ASC',
                        'meta_key' => 'eventdate',
                        'suppress_filters' => false,
                        'paged' => $paged
			    );

				//if(get_theme_mod( 'qt_events_hideold', 0 ) == '1'){
				    $args['meta_query'] = array(
				    	'relation'		=> 'AND',
			            array(
			                'key' => 'eventdate',
			                'value' => date('Y-m-d'),
			                'compare' => '>=',
			                'type' => 'date'
			                 )
		           	);
				//}

				if($EstiloSelecionado != null && $EstiloSelecionado != '') {
					$FiltroEstilo = array(
		                'key' => 'estilo',
		                'value' => $EstiloSelecionado,
		                'compare' => 'LIKE'
					);
					array_push($args['meta_query'], $FiltroEstilo);
				}
				if($BairroSelecionado != null && $BairroSelecionado != '') {
					$FiltroBairro = array(
		                'key' => 'bairro',
		                'value' => $BairroSelecionado,
		                'compare' => 'LIKE'
					);
					array_push($args['meta_query'], $FiltroBairro);
				}
				if($BoateSelecionado != null && $BoateSelecionado != '') {
					$FiltroBoate = array(
		                'key' => 'boate',
		                'value' => $BoateSelecionado,
		                'compare' => 'LIKE'
					);
					array_push($args['meta_query'], $FiltroBoate);
				}
				if($PrecoSelecionado != null && $PrecoSelecionado != '') {
					$Precos = explode("..", $PrecoSelecionado);
					$FiltroPrecoMin = array(
		                'key' => 'preco',
		                'value' => $Precos[0],
		                'compare' => '>='
					);
					if(array_key_exists(1, $Precos)) {
						$FiltroPrecoMax = array(
			                'key' => 'preco',
			                'value' => $Precos[1],
			                'compare' => '<='
						);
					}
					array_push($args['meta_query'], $FiltroPrecoMin, $FiltroPrecoMax);
				}

				$wp_query = new WP_Query( $args );
				if ( $wp_query->have_posts() ) : while ( $wp_query->have_posts() ) : $wp_query->the_post();
					get_template_part('part','archiveevent' ); 
				endwhile; else: ?>
			   	 	<h3><?php echo esc_attr__("Não encontramos nenhum resultado... Tente uma nova busca!","sonik")?></h3>
			   	 	<br><br>
			    <?php endif;
				wp_reset_postdata();
			} else {
				if ( have_posts() ) : while ( have_posts() ) : the_post();
					setup_postdata( $post );
			  		get_template_part('part','archiveevent' );
				endwhile; else: ?>
			   	 	<h3><?php echo esc_attr__("Não encontramos nenhum resultado... Tente uma nova busca!","sonik")?></h3>
			   	 	<br><br>
			    <?php endif;
			}

			?>
			</ul>
			<?php 
			/**
			 * 
			 * Variables for the "Load more"
			 */
			
			$container = "#itemslist";
			$idbutton = "qtEventsLoadmore";
			$callback = "fn.qtCollapsible";
			include_once(locate_template('includes/frontend/qt-'.get_theme_mod("qt_event_pagination", "loadmore").'.php'));
		    
		    wp_reset_postdata();
			?>
			
		</main>
	</article>

<?php get_footer();

// Omit Closing PHP Tags