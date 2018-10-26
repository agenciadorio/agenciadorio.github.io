<?php
$rew_count = $product->get_review_count();
?>
<div class="dgwt-wcas-details-inner">
	<div class="dgwt-wcas-product-details">

		<a class="dgwt-wcas-pd-details" href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>" title="<?php echo esc_attr( $product->get_title() ); ?>">

			<div class="dgwt-wcas-pd-image">
				<?php echo $product->get_image(); ?>
			</div>

			<div class="dgwt-wcas-pd-rest">

				<span class="product-title"><?php echo $product->get_title(); ?></span>

				<?php if ( $rew_count > 0 ): ?>

					<div class="dgwt-wcas-pd-rating">
						<?php echo dgwt_wcas_get_rating_html( $product ) . ' <span class="dgwt-wcas-pd-review">(' . $rew_count . ')</span>'; ?>
					</div>

				<?php endif; ?>

				<div class="dgwt-wcas-pd-price">
					<?php echo $product->get_price_html(); ?>
				</div>
			</div>

		</a>

		<?php if ( !empty( $details[ 'desc' ] ) ): ?>
			<div class="dgwt-wcas-pd-desc">
				<?php echo wp_kses_post( $details[ 'desc' ] ); ?>
			</div>
		<?php endif; ?>

		<div class="dgwt-wcas-pd-addtc">
			<?php
			echo WC_Shortcodes::product_add_to_cart( array(
				'id'		 => $product->get_id(),
				'show_price' => false,
				'style'		 => '',
			) );
			?>
		</div>


	</div>
</div>

