<?php 


$options = get_option('finance_framework');
$finance_footer_widget = $options['footer-layout'];

if($finance_footer_widget === '1widget-footer') {
    register_sidebar( array(
    'name'          => esc_html__( 'Footer 1', 'finance' ),
    'id'            => 'footer-1',
    'description'   => '',
    'class'         => '',
    'before_widget' => '<div id="%1$s" class="widget-footer %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h2 class="title">',
    'after_title'   => '</h2>' ));  

}


if($finance_footer_widget === '2widget-footer') {
    register_sidebar( array(
    'name'          => esc_html__( 'Footer 1', 'finance' ),
    'id'            => 'footer-1',
    'description'   => '',
        'class'         => '',
    'before_widget' => '<div id="%1$s" class="widget-footer %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h2 class="title">',
    'after_title'   => '</h2>' ));

    register_sidebar( array(
    'name'          => esc_html__( 'Footer 2', 'finance' ),
    'id'            => 'footer-2',
    'description'   => '',
        'class'         => '',
    'before_widget' => '<div id="%1$s" class="widget-footer %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h2 class="title">',
    'after_title'   => '</h2>' ));

}

if($finance_footer_widget === '3widget-footer') {
    register_sidebar( array(
    'name'          => esc_html__( 'Footer 1', 'finance' ),
    'id'            => 'footer-1',
    'description'   => '',
        'class'         => '',
    'before_widget' => '<div id="%1$s" class="widget-footer %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h2 class="title">',
    'after_title'   => '</h2>' ));

    register_sidebar( array(
    'name'          => esc_html__( 'Footer 2', 'finance' ),
    'id'            => 'footer-2',
    'description'   => '',
        'class'         => '',
    'before_widget' => '<div id="%1$s" class="widget-footer %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h2 class="title">',
    'after_title'   => '</h2>' ));

     register_sidebar( array(
    'name'          => esc_html__( 'Footer 3', 'finance' ),
    'id'            => 'footer-3',
    'description'   => '',
        'class'         => '',
    'before_widget' => '<div id="%1$s" class="widget-footer %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h2 class="title">',
    'after_title'   => '</h2>' ));

}

if($finance_footer_widget === '4widget-footer') {
    register_sidebar( array(
    'name'          => esc_html__( 'Footer 1', 'finance' ),
    'id'            => 'footer-1',
    'description'   => '',
        'class'         => '',
    'before_widget' => '<div id="%1$s" class="widget-footer %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h2 class="title">',
    'after_title'   => '</h2>' ));

    register_sidebar( array(
    'name'          => esc_html__( 'Footer 2', 'finance' ),
    'id'            => 'footer-2',
    'description'   => '',
        'class'         => '',
    'before_widget' => '<div id="%1$s" class="widget-footer %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h2 class="title">',
    'after_title'   => '</h2>' ));

     register_sidebar( array(
    'name'          => esc_html__( 'Footer 3', 'finance' ),
    'id'            => 'footer-3',
    'description'   => '',
        'class'         => '',
    'before_widget' => '<div id="%1$s" class="widget-footer %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h2 class="title">',
    'after_title'   => '</h2>' ));

      register_sidebar( array(
    'name'          => esc_html__( 'Footer 4', 'finance' ),
    'id'            => 'footer-4',
    'description'   => '',
        'class'         => '',
    'before_widget' => '<div id="%1$s" class="widget-footer %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h2 class="title">',
    'after_title'   => '</h2>' ));

}


function finance_footer_widget() {
   $options = get_option('finance_framework');
$finance_footer_widget = $options['footer-layout'];

    switch ($finance_footer_widget){
  case '1widget-footer' : ?>
  
        <div class="footer-widget col-md-12">
            <?php dynamic_sidebar( 'footer-1' ); ?>
        </div>
    
  <?php break;
  
  case '2widget-footer' : ?>
  
        <div class="footer-widget col-md-6">
            <?php dynamic_sidebar( 'footer-1' ); ?>
        </div>
        
        <div class="footer-widget col-md-6">
            <?php dynamic_sidebar( 'footer-2' ); ?>
        </div>
    
  <?php break;
  
  case '3widget-footer' : ?>
  
        <div class="footer-widget col-md-4">
            <?php dynamic_sidebar( 'footer-1' ); ?>
        </div>
        
        <div class="footer-widget col-md-4">
            <?php dynamic_sidebar( 'footer-2' ); ?>
        </div>
        
        <div class="footer-widget col-md-4">
            <?php dynamic_sidebar( 'footer-3' ); ?>
        </div>
    
  <?php break;
  
  case '4widget-footer' : ?>
  
        <div class="footer-widget col-md-3">
            <?php dynamic_sidebar( 'footer-1' ); ?>
        </div>
        
        <div class="footer-widget col-md-3">
            <?php dynamic_sidebar( 'footer-2' ); ?>
        </div>
        
        <div class="footer-widget col-md-3">
            <?php dynamic_sidebar( 'footer-3' ); ?>
        </div>
        
        <div class="footer-widget col-md-3">
            <?php dynamic_sidebar( 'footer-4' ); ?>
        </div>
    
  <?php break;
    
  }
}