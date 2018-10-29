<?php

class Kirki_Style {

	public $fonts_script;

	function __construct() {

		$this->fonts_script = new Kirki_Fonts_Script();

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ), 150 );

	}

	function loop_controls() {

		$controls = apply_filters( 'kirki/controls', array() );
		$styles   = array();

		foreach ( $controls as $control ) {
			$element  = '';
			$property = '';
			$units    = '';

			// Only continue if $control['output'] is set
			if ( isset( $control['output'] ) ) {

				// Check if this is an array of style definitions
				$multiple_styles = isset( $control['output'][0]['element'] ) ? true : false;

				if ( ! $multiple_styles ) { // single style

					// If $control['output'] is not an array, then use the string as the target element
					if ( is_string( $control['output'] ) ) {
						$element = $control['output'];
					} else {
						$element  = isset( $control['output']['element'] )  ? $control['output']['element'] : '';
						$property = isset( $control['output']['property'] ) ? $control['output']['property'] : '';
						$units    = isset( $control['output']['units'] )    ? $control['output']['units']    : '';
					}

					$styles = $this->styles( $control, $styles, $element, $property, $units );

				} else { // Multiple styles set

					foreach ( $control['output'] as $style ) {

						if ( ! array( $style ) ) {
							$element = $style;
						} else {
							$element  = isset( $style['element'] )  ? $style['element'] : '';
							$property = isset( $style['property'] ) ? $style['property'] : '';
							$units    = isset( $style['units'] )    ? $style['units']    : '';
						}

						$styles = $this->styles( $control, $styles, $element, $property, $units );

					}

				}

			}

		}

		return $styles;

	}

	function styles( $control, $styles, $element, $property, $units ) {

    // The value of this control
    if (!isset($control['default'])) {
      $control['default'] = '';
    }

		$value = get_theme_mod( $control['setting'], $control['default'] );

		// Color controls
		if ( 'color' == $control['type'] ) {

			$color = Kirki_Color::sanitize_hex( $value );
			$styles[$element][$property] = $color;

		}

		// Background Controls
		elseif ( 'background' == $control['type'] ) {

			$bg_color    = Kirki_Color::sanitize_hex( get_theme_mod( $control['setting'] . '_color', $control['default']['color'] ) );
			$bg_image    = get_theme_mod( $control['setting'] . '_image', $control['default']['image'] );
			$bg_repeat   = get_theme_mod( $control['setting'] . '_repeat', $control['default']['repeat'] );
			$bg_size     = get_theme_mod( $control['setting'] . '_size', $control['default']['size'] );
			$bg_attach   = get_theme_mod( $control['setting'] . '_attach', $control['default']['attach'] );
			$bg_position = get_theme_mod( $control['setting'] . '_position', $control['default']['position'] );
			$bg_opacity  = get_theme_mod( $control['setting'] . '_opacity', $control['default']['opacity'] );

			if ( false != $control['default']['opacity'] ) {

				$bg_position = get_theme_mod( $control['setting'] . '_opacity', $control['default']['opacity'] );

				// If we're using an opacity other than 100, then convert the color to RGBA.
				if ( 100 != $bg_opacity ) {
					$bg_color = Kirki_Color::get_rgba( $bg_color, $bg_opacity );
				}

			}

			$styles[$element]['background-color'] = $bg_color;
			if ( '' != $bg_image ) {
				$styles[$element]['background-image']      = 'url("' . $bg_image . '")';
				$styles[$element]['background-repeat']     = $bg_repeat;
				$styles[$element]['background-size']       = $bg_size;
				$styles[$element]['background-attachment'] = $bg_attach;
				$styles[$element]['background-position']   = str_replace( '-', ' ', $bg_position );
			}

		}

		// Font controls
		elseif ( array( $control['output'] ) && isset( $control['output']['property'] ) && in_array( $control['output']['property'], array( 'font-family', 'font-size', 'font-weight' ) ) ) {

			$is_font_family = isset( $control['output']['property'] ) && 'font-family' == $control['output']['property'] ? true : false;
			$is_font_size   = isset( $control['output']['property'] ) && 'font-size'   == $control['output']['property'] ? true : false;
			$is_font_weight = isset( $control['output']['property'] ) && 'font-weight' == $control['output']['property'] ? true : false;

			if ( 'font-family' == $property ) {

				$styles[$control['output']['element']]['font-family'] = $value;

			} else if ( 'font-size' == $property ) {

				// Get the unit we're going to use for the font-size.
				$units = empty( $units ) ? 'px' : $units;
				$styles[$element]['font-size'] = $value . $units;

			} else if ( 'font-weight' == $property ) {

				$styles[$element]['font-weight'] = $value;

			}

		} else {

			$styles[$element][$property] = $value . $units;

		}

		return $styles;

	}

	function enqueue() {

		global $kirki;
		$config = $kirki->get_config();
		wp_add_inline_style( $config['stylesheet_id'], $this->parse() );

	}

	function parse() {

		$styles = $this->loop_controls();
		$css = '';

		// Early exit if styles are empty or not an array
		if ( empty( $styles ) || ! is_array( $styles ) ) {
			return;
		}

		foreach ( $styles as $style => $style_array ) {
			$css .= $style . '{';
			foreach ( $style_array as $property => $value ) {
				$css .= $property . ':' . $value . ';';
			}
			$css .= '}';
		}

		return $css;

	}

}
