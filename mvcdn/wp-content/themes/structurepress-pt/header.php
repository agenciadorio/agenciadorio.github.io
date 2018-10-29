<?php
/**
 * The Header for StructurePress Theme
 *
 * @package StructurePress
 */

?>

<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
		<?php endif; ?>

		<?php wp_head(); ?>
	</head>

	<body <?php body_class( StructurePressHelpers::add_body_class() ); ?>>
	<div class="boxed-container">

	<header class="site-header<?php echo ( 'no' === get_theme_mod( 'top_bar_visibility', 'yes' ) ) ? '  site-header--no-top' : '' ?>">
		<?php get_template_part( 'template-parts/top-bar' ); ?>

		<div class="header">
			<div class="container">
				<!-- Logo and site name -->
				<div class="header__logo">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<?php
						$structurepress_logo   = get_theme_mod( 'logo_img', false );
						$structurepress_logo2x = get_theme_mod( 'logo2x_img', false );

						if ( ! empty( $structurepress_logo ) ) :
						?>
							<img src="<?php echo esc_url( $structurepress_logo ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" srcset="<?php echo esc_attr( $structurepress_logo ); ?><?php echo empty ( $structurepress_logo2x ) ? '' : ', ' . esc_url( $structurepress_logo2x ) . ' 2x'; ?>" class="img-fluid" <?php echo StructurePressHelpers::get_logo_dimensions(); ?> />
						<?php
						else :
						?>
							<h1><?php bloginfo( 'name' ); ?></h1>
						<?php
						endif;
						?>
					</a>
				</div>
				<!-- Toggle button for Main Navigation on mobile -->
				<button class="btn  btn-primary  header__navbar-toggler  hidden-lg-up" type="button" data-toggle="collapse" data-target="#structurepress-main-navigation"><i class="fa  fa-bars  hamburger"></i> <span><?php esc_html_e( 'MENU' , 'structurepress-pt' ); ?></span></button>
				<!-- Main Navigation -->
				<nav class="header__navigation  collapse  navbar-toggleable-md  js-sticky-offset" id="structurepress-main-navigation" aria-label="<?php esc_html_e( 'Main Menu', 'structurepress-pt' ); ?>">
					<!-- Home Icon in Navigation -->
					<?php if ( 'yes' === get_theme_mod( 'main_navigation_home_icon', 'yes' ) ) : ?>
					<a class="home-icon" href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<i class="fa  fa-home"></i>
					</a>
					<?php endif;

					if ( has_nav_menu( 'main-menu' ) ) {
						wp_nav_menu( array(
							'theme_location' => 'main-menu',
							'container'      => false,
							'menu_class'     => 'main-navigation  js-main-nav  js-dropdown',
							'walker'         => new Aria_Walker_Nav_Menu(),
							'items_wrap'     => '<ul id="%1$s" class="%2$s" role="menubar">%3$s</ul>',
						) );
					}

					?>
				</nav>
				<!-- Jumbotron widgets on mobile -->
				<div class="jumbotron__widgets  hidden-lg-up">
					<?php
						if ( is_active_sidebar( 'slider-widgets' ) ) {
							dynamic_sidebar( 'slider-widgets' );
						}
						?>
				</div>
				<?php
					// display the featured page button if the page is selected in customizer
					$selected_page = StructurePressHelpers::get_page_id( get_theme_mod( 'featured_page_select', 'none' ) );
					if ( 'none' !== $selected_page ) :
						$target = ( '1' == get_theme_mod( 'featured_page_open_in_new_window', '' ) ) ? '_blank' : '_self';
				?>
				<!-- Featured Link -->
				<div class="header__featured-link">
					<?php
						if ( 'custom-url' == $selected_page ) :
					?>
						<a class="btn  btn-primary" target="<?php echo esc_attr( $target ); ?>" href="<?php echo esc_url( get_theme_mod( 'featured_page_custom_url', '#' ) ); ?>">
							<?php echo esc_html( get_theme_mod( 'featured_page_custom_text', 'Featured Page' ) ); ?>
						</a>
					<?php
						else :
					?>
						<a class="btn  btn-primary" target="<?php echo esc_attr( $target ); ?>" href="<?php echo get_permalink( $selected_page ); ?>">
							<?php echo get_the_title( absint( $selected_page ) );?>
						</a>
					<?php
						endif;
					?>
				</div>
				<?php
					endif;
				?>
			</div>
		</div>
	</header>
