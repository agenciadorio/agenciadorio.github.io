<?php

class Kirki_Customize_Slider_Control extends Kirki_Customize_Control {

	public function __construct( $manager, $id, $args = array() ) {
		$this->type = 'slider';
    $this->separator = false;
		parent::__construct( $manager, $id, $args );
	}

	public function enqueue() {

		wp_enqueue_script( 'jquery-ui-slider' );

	}

	public function render_content() { ?>
		<label>

			<span class="customize-control-title">
				<?php echo esc_html( $this->label ); ?>
				<?php $this->description(); ?>
			</span>
			<?php $this->subtitle(); ?>

			<input type="text" class="kirki-slider" id="input_<?php echo $this->id; ?>" disabled value="<?php echo $this->value(); ?>" <?php $this->link(); ?>/>

		</label>
		<div id="slider_<?php echo $this->id; ?>" class="ss-slider"></div>
    <?php if ( $this->separator ) echo '<hr class="customizer-separator">'; ?>
		<script>
		jQuery(document).ready(function($) {
			$( '[id="slider_<?php echo $this->id; ?>"]' ).slider({
					value : <?php echo $this->value(); ?>,
					min   : <?php echo $this->choices['min']; ?>,
					max   : <?php echo $this->choices['max']; ?>,
					step  : <?php echo $this->choices['step']; ?>,
					slide : function( event, ui ) { $( '[id="input_<?php echo $this->id; ?>"]' ).val(ui.value).keyup(); }
			});
			$( '[id="input_<?php echo $this->id; ?>"]' ).val( $( '[id="slider_<?php echo $this->id; ?>"]' ).slider( "value" ) );
		});
		</script>
		<?php

	}
}
