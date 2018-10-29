<?php
/**
 * Single portfolio item
 */

get_header();

get_template_part( 'template-parts/page-header' );
get_template_part( 'template-parts/breadcrumbs' );

?>

	<div id="primary" class="content-area  container">
		<div class="row">
			<main class="col-md-6" role="main" id="portfolio-navigation-anchor">

				<?php
					while ( have_posts() ) :
						the_post();
				?>

				<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-inner' ); ?>>
					<h2 class="portfolio__title"><?php echo get_the_title(); ?></h2>

					<div class="portfolio--left">
						<div class="portfolio__meta">
							<?php /* translators: Read %s as word 'project' in English */ ?>
							<h4 class="portfolio__meta-title"><?php printf( esc_html_x( '%s Info', '%s represents Project by default, must be included', 'structurepress-pt' ), get_theme_mod( 'portfolio_name_signular', 'Project' ) ); ?></h4>
							<?php
								$portfolio_meta_fields = array(
									'construction_date' => array(
										'icon'  => 'fa-clock-o',
										'label' => esc_html__( 'Construction Date', 'structurepress-pt' ),
									),
								);

								if ( get_field( 'category' ) ) {
									$portfolio_meta_fields['category'] = array(
										'icon'  => 'fa-ellipsis-v',
										'label' => esc_html__( 'Category', 'structurepress-pt' ),
										'value' => implode( ', ', StructurePressHelpers::get_custom_categories( get_the_ID(), 'portfolio_category' ) ),
									);
								}

								foreach ( $portfolio_meta_fields as $field => $data ) {
									if ( array_key_exists( 'value', $data ) && trim( $data['value'] ) ) {
										// leave as it is, but this has to be here
									}
									else if ( trim( get_field( $field ) ) ) {
										$portfolio_meta_fields[ $field ]['value'] = trim( get_field( $field ) );
										}
									else {
										unset( $portfolio_meta_fields[ $field ] );
									}
								}

								// add additional fields
								while ( have_rows( 'additional_meta_fields' ) ) {
									the_row();
									$portfolio_meta_fields[] = array(
										'icon'  => get_sub_field( 'icon', null ),
										'label' => get_sub_field( 'name' ),
										'value' => get_sub_field( 'value' ),
									);
								}
							?>
							<ul class="list-unstyled">
							<?php
								foreach ( $portfolio_meta_fields as $data ) {
									if ( isset( $data['icon'] ) && ! empty( $data['icon'] ) ) {
										printf( '<li class="portfolio__meta-item"><span class="portfolio__meta-icon"><i class="fa  %1$s"></i></span> <h5 class="portfolio__meta-key">%2$s</h5><p class="portfolio__meta-value">%3$s</p></li>', $data['icon'], $data['label'], $data['value'] );
									}
									else {
										printf( '<li class="portfolio__meta-item"><h5 class="portfolio__meta-key">%1$s</h5><p class="portfolio__meta-value">%2$s</p></li>', $data['label'], $data['value'] );
									}
								}
							?>
							</ul>
						</div>
						<div class="hentry__content  portfolio__content">
							<?php the_content(); ?>
						</div>
					</div>
				</article>

				<?php
					endwhile;
				?>
			</main>
			<aside class="col-md-6  portfolio--right">
				<nav class="portfolio__navigation  hidden-sm-down">
					<?php
						/* translators: Read %s as word 'project' in English */
						StructurePressHelpers::get_next_prev_portfolio_link( true, sprintf( _x( 'Previous %s', '% must be included', 'structurepress-pt' ), get_theme_mod( 'portfolio_name_signular', 'Project' ) ) );
						StructurePressHelpers::get_next_prev_portfolio_link( false, sprintf( _x( 'Next %s', '% must be included', 'structurepress-pt' ), get_theme_mod( 'portfolio_name_signular', 'Project' ) ) );
					?>
				</nav>
				<?php if ( 'editor' === get_field( 'project_right' ) ) : ?>
					<div class="portfolio__right-content">
					<?php the_field( 'project_content' ); ?>
					</div>
				<?php else : ?>
					<?php $portfolio_gallery_columns = get_theme_mod( 'portfolio_gallery_columns', 1 ); ?>
					<div class="portfolio__gallery  portfolio__gallery--col-<?php echo absint( $portfolio_gallery_columns ); ?>" data-featherlight-gallery data-featherlight-filter="a">
					<?php
						while ( have_rows( 'project_gallery' ) ) {
							the_row();

							$image = get_sub_field( 'project_image' );

							printf(
								'<a class="portfolio__gallery-link" href="%1$s"><img class="img-responsive  portfolio__gallery-image" src="%2$s" alt="%3$s" width="%4$d" height="%5$d" /></a> ',
								esc_url( $image['url'] ),
								esc_attr( $image['sizes']['portfolio-gallery'] ),
								esc_attr( $image['title'] ),
								absint( $image['sizes']['portfolio-gallery-width'] ),
								absint( $image['sizes']['portfolio-gallery-height'] )
							);
						}
					?>
					</div>
				<?php endif; ?>
				<nav class="portfolio__navigation  hidden-md-up">
					<?php
						StructurePressHelpers::get_next_prev_portfolio_link( true, sprintf( _x( 'Previous %s', '% must be included', 'structurepress-pt' ), get_theme_mod( 'portfolio_name_signular', 'Project' ) ) );
						StructurePressHelpers::get_next_prev_portfolio_link( false, sprintf( _x( 'Next %s', '% must be included', 'structurepress-pt' ), get_theme_mod( 'portfolio_name_signular', 'Project' ) ) );
					?>
				</nav>
			</aside>

		</div>
	</div><!-- #primary -->

<?php get_footer(); ?>