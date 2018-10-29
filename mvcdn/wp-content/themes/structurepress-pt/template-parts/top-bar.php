<?php if ( 'no' !== get_theme_mod( 'top_bar_visibility', 'yes' ) ) : ?>
<div class="top<?php echo 'hide_mobile' === get_theme_mod( 'top_bar_visibility', 'yes' ) ? '  hidden-md-down' : ''; ?>">
	<div class="container  top__container">
		<div class="top__tagline">
			<?php bloginfo( 'description' ); ?>
		</div>
		<!-- Top Menu -->
		<nav class="top__menu" aria-label="<?php esc_html_e( 'Top Menu', 'structurepress-pt' ); ?>">
			<?php
			if ( has_nav_menu( 'top-bar-menu' ) ) {
				wp_nav_menu( array(
					'theme_location' => 'top-bar-menu',
					'container'      => false,
					'menu_class'     => 'top-navigation  js-dropdown',
					'walker'         => new Aria_Walker_Nav_Menu(),
					'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
				) );
			}
			?>
		</nav>
	</div>
</div>
<?php endif; ?>