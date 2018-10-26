<?php
namespace Woocommerce\Moip\View;

if ( ! function_exists( 'add_action' ) ) {
	exit( 0 );
}

use Woocommerce\Moip\Core;
use Woocommerce\Moip\Helper\Utils;
use Woocommerce\Moip\Model\Setting;
use Woocommerce\Moip\Model\Moip_Connect;

class Moip_Connects
{
	public static function render_form_authorize() {
		$model = new Moip_Connect();
	?>
		<div id="app-overlay">
			<div id="form-authorize"
				 <?php echo Utils::get_component( 'authorize' ); ?>>

				<span class="close"
					  data-action="close"
					  title="<?php _e( 'Close', Core::TEXTDOMAIN ); ?>"></span>

				<p class="description">
					<ul>
						<li>
							Modo sandbox é usado para fazer pagamentos de testes.
						</li>
					</ul>
				</p>

				<form method="post"
					  data-action="form"
					  data-element="form">

					<p>
						<label for="mode-production">
							<input type="radio"
								   id="mode-production"
								   name="mode"
								   data-element="mode"
								   value="production">

							<span class="label-text">
								<?php _e( 'Production', Core::TEXTDOMAIN ); ?>
							</span>
						</label>

						<label for="mode-sandbox">
							<input type="radio"
								   id="mode-sandbox"
								   name="mode"
								   data-element="mode"
								   value="sandbox">

							<span class="label-text">
								<?php _e( 'Sandbox', Core::TEXTDOMAIN ); ?>
							</span>
						</label>
					</p>
					<p class="response-message"
					   data-element="message">

						<span class="alert"></span>
					</p>
					<p>
						<strong data-action="close"
								class="close-after"
								data-element="close-after">

							<?php _e( 'Close', Core::TEXTDOMAIN ); ?>
						</strong>
					</p>

					<p class="btn-content">
						<button type="submit"
								data-element="button"
							    class="button button-primary"
							    data-text-waiting="<?php _e( 'Loading...', Core::TEXTDOMAIN ); ?>">

							<?php _e( 'Submit' ); ?>
						</button>
					</p>
				</form>
			</div>
		</div>
	<?php
	}
}