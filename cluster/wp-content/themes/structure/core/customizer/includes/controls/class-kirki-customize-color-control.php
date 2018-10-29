<?php

/**
 * Customize Color Control Class
 *
 * @package WordPress
 * @subpackage Customize
 * @since 3.4.0
 */
class Kirki_Customize_Color_Control extends WP_Customize_Color_Control {
  /**
   * @access public
   * @var string
   */
  public $type = 'color';

  /**
   * @access public
   * @var array
   */
  public $statuses;
  public $description = '';
  public $subtitle = '';
  public $separator = false;
  public $framework_var = '';

  /**
   * Constructor.
   *
   * @since 3.4.0
   * @uses WP_Customize_Control::__construct()
   *
   * @param WP_Customize_Manager $manager
   * @param string $id
   * @param array $args
   */
  public function __construct( $manager, $id, $args = array() ) {
    $this->statuses = array( '' => __( 'Default', 'kirki' ) );
    parent::__construct( $manager, $id, $args );
    $this->framework_var = ( isset( $args['framework_var'] ) && ! is_null( $args['framework_var'] ) ) ? $args['framework_var'] : null;
  }

  /**
   * Enqueue scripts/styles for the color picker.
   *
   * @since 3.4.0
   */
  public function enqueue() {
    wp_enqueue_script( 'wp-color-picker' );
    wp_enqueue_style( 'wp-color-picker' );
  }

  /**
   * Refresh the parameters passed to the JavaScript via JSON.
   *
   * @since 3.4.0
   * @uses WP_Customize_Control::to_json()
   */
  public function to_json() {
    parent::to_json();
    $this->json['statuses'] = $this->statuses;
  }

  /**
   * Render the control's content.
   *
   * @since 3.4.0
   */

  public function content_template() {
    ?>

  <?php
  }
}
