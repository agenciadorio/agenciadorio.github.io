<?php
extract(shortcode_atts(array(
  'title' => '',
  'job' => '',
  'img_url'=>'',
  'el_class' => ''
), $atts));
$class = array('team-item');
$class[] = $el_class;
$img_url = wp_get_attachment_url(intval($img_url));
?>
<div class="<?php echo construction_join( $class );?>">
  <?php if( $img_url ):?>
    <img src="<?php echo esc_url( $img_url );?>" alt="<?php echo esc_attr( $title );?>">
    <?php endif;?>
  <div class="team-caption">
    <?php if( ! empty( $title ) ):?>
    <h4 class="heading-4"><?php echo esc_attr( $title );?></h4>
    <?php endif; ?>
    <?php if( ! empty( $job ) ):?>
    <span><?php echo esc_attr( $job );?></span>
    <?php endif;?>
    <?php if( ! empty( $content ) ){
      echo apply_filters( 'the_content', $content );
    }?>
  </div>

</div><!-- End Team member -->