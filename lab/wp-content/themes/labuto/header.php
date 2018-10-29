<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<meta http-equiv="imagetoolbar" content="no">

<title><?php bloginfo('name'); ?> <?php wp_title(); ?></title>

<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/reset.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="alternate" type="text/xml" title="RSS .92" href="<?php bloginfo('rss_url'); ?>" />
<link rel="alternate" type="application/atom+xml" title="Atom 0.3" href="<?php bloginfo('atom_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<!--BEGIN NECESSARY WORDPRESS TAGS-->
<?php
	wp_enqueue_script('jquery');
	wp_head();
 ?>
<!--END NECESSARY WORDPRESS TAGS-->
</head>
    <body <?php body_class(); ?>><div class="bg_top"><div id="layout">
	
        <div id="header">
            <h1><a href="<?php bloginfo('url'); ?>"><span><?php bloginfo('name'); ?></span></a></h1>
            <div class="atendimento">
                <a href="#"><img src="<?php bloginfo('stylesheet_directory'); ?>/imagens/bnr_atendimento-online.png" alt="Atendimento Online" /></a>
            </div>
            <?php  if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Área Restrita') ) :  endif; ?>
            <div class="favoritos">
                <a class="link-favorito" href="#"><img src="<?php bloginfo('stylesheet_directory'); ?>/imagens/bnr_adicionar-favoritos.png" alt="Adicionar nos favoritos" /></a>
            </div>
            <div id="menu" class="menu">
                <?php 
                // MENU PRINCIPAL - Itens devem ser configurados no painel (Aparencia > Menus).
                    $config = array(
                        'theme_location'  => 'Principal',
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
                        'link_before'     => '',
                        'link_after'      => '',
                        'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                        'depth'           => 3,
                        //'walker'          => 
                    );
                    wp_nav_menu($config); 
                ?>
            </div>
        </div><!-- #header -->

        <div id="middle">
