<?php

/**
 * Part of Woo Mercado Pago Module
 * Author - Mercado Pago
 * Developer - Marcelo Mita / marcelo.mita@mercadolivre.com
 * Copyright - Copyright(c) MercadoPago [https://www.mercadopago.com]
 * License - https://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="mp-box-inputs mp-line" >
	<label>
		<span class="mensagem-ticket">
			<div class="tooltip">
				<?php echo esc_html__( 'Note: Confirmation under payment approval.', 'woocommerce-mercadopago' ); ?>
				<span class="tooltiptext">
					<?php
						echo esc_html__( 'Click [Place order] button. The bank transfer will be setup and you will be redirected to pay it.', 'woocommerce-mercadopago' );
						echo ' ';
						echo esc_html__( 'Important: The order will be confirmed only after the payment approval.', 'woocommerce-mercadopago' );
					?>
				</span>
			</div>
		</span>
	</label>
</div>

<fieldset id="pse_checkout_fieldset" style="margin:-1px; background:white; display: none;">

	<!-- coupom -->
<!-- 	<div class="mp-box-inputs mp-line form-row" id="mercadopago-form-coupon-pse" >
		<div class="form-col-8">
			<label for="couponCodeLabel"><?php echo esc_html__( 'Discount Coupon', 'woocommerce-mercadopago' ); ?></label>
			<input type="text" id="couponCodePSE" name="mercadopago_pse[coupon_code]"
				autocomplete="off" maxlength="24" style="margin-bottom: 8px;"/>
			<span class="mp-discount" id="mpCouponApplyedPSE" ></span>
			<span class="mp-error" id="mpCouponErrorPSE" ></span>
		</div>
		<div class="form-col-4">
			<label >&nbsp;</label>
			<input type="button" class="button" id="applyCouponPSE" value="<?php echo esc_html__( 'Apply', 'woocommerce-mercadopago' ); ?>">
		</div>
	</div> -->

	<!-- payment method -->
	<div id="mercadopago-form-pse" class="mp-box-inputs mp-line" >
		<div id="form-pse">
       <div class="form-row">
         <div class="form-col-6">
					<label for="docType"><?php echo esc_html__( 'DOCUMENT TYPE', 'woocommerce-mercadopago' ); ?><em class="obrigatorio"> *</em></label>
          <select name="mercadopago_pse[docType]" id="pse-doc-type" class="form-control-mine" style="width: 100%">
            <option value="CC">C.C.</option>
            <option value="CE">C.E.</option>
            <option value="NIT">NIT</option>
            <option value="Otro">Otro</option>
          </select>
					<span class="erro_pse" data-main="#pse-doc-type" id="error-docType"><?php echo esc_html__( 'You must inform your DOCUMENT TYPE', 'woocommerce-mercadopago' ); ?></span>
				</div>
				<div class="form-col-6">
					<label for="docNumber"><?php echo esc_html__( 'DOCUMENT', 'woocommerce-mercadopago' ); ?><em class="obrigatorio"> *</em></label>
					<input type="text" value="<?php echo $pse_data['docNumber']; ?>" id="pse-doc-number" class="form-control-mine" name="mercadopago_pse[docNumber]" style="width: 100%">
					<span class="erro_pse" data-main="#pse-doc-number" id="error-docNumber"><?php echo esc_html__( 'You must inform your DOCUMENT', 'woocommerce-mercadopago' ); ?></span>
				</div>
			</div>
      
      <div class="form-row">
				<div class="form-col-6">
					<label for="bank"><?php echo esc_html__( 'BANK', 'woocommerce-mercadopago' ); ?><em class="obrigatorio"> *</em></label>
					<select name="mercadopago_pse[bank]" id="pse-bank" class="form-control-mine" style="width: 100%">
            <?php foreach($financial_institutions as $bank) : ?>
              <option value="<?php echo $bank["id"] ?>"><?php echo $bank["description"] ?></option>
            <?php endforeach ?>
          </select>
					<span class="erro_pse" data-main="#pse-bank" id="error-bank"><?php echo esc_html__( 'You must inform the FINANCIAL INSTITUTION', 'woocommerce-mercadopago' ); ?></span>
				</div>
				<div class="form-col-6">
					<label for="personType"><?php echo esc_html__( 'PERSON TYPE', 'woocommerce-mercadopago' ); ?><em class="obrigatorio"> *</em></label>
					<select id="pse-person-type" class="form-control-mine" name="mercadopago_pse[personType]" style="width: 100%">
            <option value="individual">Individual</option>
            <option value="association">Association</option>
          </select>
					<span class="erro_pse" data-main="#pse-person-type" id="error-person-type"><?php echo esc_html__( 'You must inform your PERSON TYPE', 'woocommerce-mercadopago' ); ?></span>
				</div>
      </div>
      
			<!-- utilities -->
			<div class="mp-box-inputs mp-col-100" id="mercadopago-utilities">
				<input type="hidden" id="site_id" value="<?php echo $site_id; ?>" name="mercadopago_pse[site_id]"/>
				<input type="hidden" id="amountPSE" value="<?php echo $amount; ?>" name="mercadopago_pse[amount]"/>
				<input type="hidden" id="currency_ratioPSE" value="<?php echo $currency_ratio; ?>" name="mercadopago_pse[currency_ratio]"/>
				<input type="hidden" id="campaign_idPSE" name="mercadopago_pse[campaign_id]"/>
				<input type="hidden" id="campaignPSE" name="mercadopago_pse[campaign]"/>
				<input type="hidden" id="discountPSE" name="mercadopago_pse[discount]"/>
				<input type="hidden" id="paymentMethodIdPSE" value="pse" name="mercadopago_pse[paymentMethodId]"/>
			</div>

		</div>
	</div>
</fieldset>

<script type="text/javascript">
	( function() {

		var MPv1PSE = {
			site_id: "",
// 			coupon_of_discounts: {
// 				discount_action_url: "",
// 				payer_email: "",
// 				default: true,
// 				status: false
// 			},
// 			inputs_to_create_discount: [
// 				"couponCodePSE",
// 				"applyCouponPSE"
// 			],
			inputs_to_validate_pse: [
				"personType",
				"docType",
				"docNumber",
				"bank"
			],
			selectors: {
				// currency
				currency_ratio: "#currency_ratioPSE",
				// coupom
// 				couponCode: "#couponCodePSE",
// 				applyCoupon: "#applyCouponPSE",
				mpCouponApplyed: "#mpCouponApplyedPSE",
				mpCouponError: "#mpCouponErrorPSE",
				campaign_id: "#campaign_idPSE",
				campaign: "#campaignPSE",
				discount: "#discountPSE",
				// payment method and checkout
				paymentMethodId: "#paymentMethodIdPSE",
				amount: "#amountPSE",
				// pse fields
				personType: "#pse-person-type",
				docType: "#pse-doc-type",
				docNumber: "#pse-doc-number",
				bank: "#pse-bank",
				// form
				formCoupon: "#mercadopago-form-coupon-pse",
				formPSE: "#form-pse",
				box_loading: "#mp-box-loading",
				submit: "#btnSubmit",
				form: "#mercadopago-form-pse"
			},
			text: {
				discount_info1: "You will save",
				discount_info2: "with discount from",
				discount_info3: "Total of your purchase:",
				discount_info4: "Total of your purchase with discount:",
				discount_info5: "*Uppon payment approval",
				discount_info6: "Terms and Conditions of Use",
				coupon_empty: "Please, inform your coupon code",
				apply: "Apply",
				remove: "Remove"
			},
			paths: {
				loading: "images/loading.gif",
				check: "images/check.png",
				error: "images/error.png"
			}
		}

		// === Coupon of Discounts

		MPv1PSE.currencyIdToCurrency = function ( currency_id ) {
			if ( currency_id == "ARS" ) {
				return "$";
			} else if ( currency_id == "BRL" ) {
				return "R$";
			} else if ( currency_id == "COP" ) {
				return "$";
			} else if ( currency_id == "CLP" ) {
				return "$";
			} else if ( currency_id == "MXN" ) {
				return "$";
			} else if ( currency_id == "VEF" ) {
				return "Bs";
			} else if ( currency_id == "PEN" ) {
				return "S/";
			} else if ( currency_id == "UYU" ) {
				return "$U";
			} else {
				return "$";
			}
		}

		MPv1PSE.checkCouponEligibility = function () {
			if ( document.querySelector( MPv1PSE.selectors.couponCode ).value == "" ) {
				// Coupon code is empty.
	  	  document.querySelector( MPv1PSE.selectors.mpCouponApplyed ).style.display = "none";
				document.querySelector( MPv1PSE.selectors.mpCouponError ).style.display = "block";
				document.querySelector( MPv1PSE.selectors.mpCouponError ).innerHTML = MPv1PSE.text.coupon_empty;
				MPv1PSE.coupon_of_discounts.status = false;
				document.querySelector( MPv1PSE.selectors.couponCode ).style.background = null;
				document.querySelector( MPv1PSE.selectors.applyCoupon ).value = MPv1PSE.text.apply;
				document.querySelector( MPv1PSE.selectors.discount ).value = 0;
				// --- No cards handler ---
			} else if ( MPv1PSE.coupon_of_discounts.status ) {
				// We already have a coupon set, so we remove it.
				document.querySelector( MPv1PSE.selectors.mpCouponApplyed ).style.display = "none";
				document.querySelector( MPv1PSE.selectors.mpCouponError ).style.display = "none";
				MPv1PSE.coupon_of_discounts.status = false;
				document.querySelector( MPv1PSE.selectors.applyCoupon ).style.background = null;
				document.querySelector( MPv1PSE.selectors.applyCoupon ).value = MPv1PSE.text.apply;
				document.querySelector( MPv1PSE.selectors.couponCode ).value = "";
				document.querySelector( MPv1PSE.selectors.couponCode ).style.background = null;
				document.querySelector( MPv1PSE.selectors.discount ).value = 0;
				// --- No cards handler ---
			} else {
				// Set loading.
				document.querySelector( MPv1PSE.selectors.mpCouponApplyed ).style.display = "none";
				document.querySelector( MPv1PSE.selectors.mpCouponError ).style.display = "none";
				document.querySelector( MPv1PSE.selectors.couponCode ).style.background = "url(" + MPv1PSE.paths.loading + ") 98% 50% no-repeat #fff";
				document.querySelector( MPv1PSE.selectors.applyCoupon ).disabled = true;

				// Check if there are params in the url.
				var url = MPv1PSE.coupon_of_discounts.discount_action_url;
				var sp = "?";
				if ( url.indexOf( "?" ) >= 0 ) {
					sp = "&";
				}
				url += sp + "site_id=" + MPv1PSE.site_id;
				url += "&coupon_id=" + document.querySelector( MPv1PSE.selectors.couponCode ).value;
				url += "&amount=" + document.querySelector( MPv1PSE.selectors.amount ).value;
				url += "&payer=" + MPv1PSE.coupon_of_discounts.payer_email;

				MPv1PSE.AJAX({
					url: url,
					method : "GET",
					timeout : 5000,
					error: function() {
						// Request failed.
						document.querySelector( MPv1PSE.selectors.mpCouponApplyed ).style.display = "none";
						document.querySelector( MPv1PSE.selectors.mpCouponError ).style.display = "none";
						MPv1PSE.coupon_of_discounts.status = false;
						document.querySelector( MPv1PSE.selectors.applyCoupon ).style.background = null;
						document.querySelector( MPv1PSE.selectors.applyCoupon ).value = MPv1PSE.text.apply;
						document.querySelector( MPv1PSE.selectors.couponCode ).value = "";
						document.querySelector( MPv1PSE.selectors.couponCode ).style.background = null;
						document.querySelector( MPv1PSE.selectors.discount ).value = 0;
						// --- No cards handler ---
					},
					success : function ( status, response ) {
						if ( response.status == 200 ) {
							document.querySelector( MPv1PSE.selectors.mpCouponApplyed ).style.display =
								"block";
							document.querySelector( MPv1PSE.selectors.discount ).value =
								response.response.coupon_amount;
							document.querySelector( MPv1PSE.selectors.mpCouponApplyed ).innerHTML =
								MPv1PSE.text.discount_info1 + " <strong>" +
								MPv1PSE.currencyIdToCurrency( response.response.currency_id ) + " " +
								Math.round( response.response.coupon_amount * 100 ) / 100 +
								"</strong> " + MPv1PSE.text.discount_info2 + " " +
								response.response.name + ".<br>" + MPv1PSE.text.discount_info3 + " <strong>" +
								MPv1PSE.currencyIdToCurrency( response.response.currency_id ) + " " +
								Math.round( MPv1PSE.getAmountWithoutDiscount() * 100 ) / 100 +
								"</strong><br>" + MPv1PSE.text.discount_info4 + " <strong>" +
								MPv1PSE.currencyIdToCurrency( response.response.currency_id ) + " " +
								Math.round( MPv1PSE.getAmount() * 100 ) / 100 + "*</strong><br>" +
								"<i>" + MPv1PSE.text.discount_info5 + "</i><br>" +
								"<a href='https://api.mercadolibre.com/campaigns/" +
								response.response.id +
								"/terms_and_conditions?format_type=html' target='_blank'>" +
								MPv1PSE.text.discount_info6 + "</a>";
							document.querySelector( MPv1PSE.selectors.mpCouponError ).style.display =
								"none";
							MPv1PSE.coupon_of_discounts.status = true;
							document.querySelector( MPv1PSE.selectors.couponCode ).style.background =
								null;
							document.querySelector( MPv1PSE.selectors.couponCode ).style.background =
								"url(" + MPv1PSE.paths.check + ") 98% 50% no-repeat #fff";
							document.querySelector( MPv1PSE.selectors.applyCoupon ).value =
								MPv1PSE.text.remove;
							// --- No cards handler ---
							document.querySelector( MPv1PSE.selectors.campaign_id ).value =
								response.response.id;
							document.querySelector( MPv1PSE.selectors.campaign ).value =
								response.response.name;
						} else {
							document.querySelector( MPv1PSE.selectors.mpCouponApplyed ).style.display = "none";
							document.querySelector( MPv1PSE.selectors.mpCouponError ).style.display = "block";
							document.querySelector( MPv1PSE.selectors.mpCouponError ).innerHTML = response.response.message;
							MPv1PSE.coupon_of_discounts.status = false;
							document.querySelector(MPv1PSE.selectors.couponCode).style.background = null;
							document.querySelector( MPv1PSE.selectors.couponCode ).style.background = "url(" + MPv1PSE.paths.error + ") 98% 50% no-repeat #fff";
							document.querySelector( MPv1PSE.selectors.applyCoupon ).value = MPv1PSE.text.apply;
							document.querySelector( MPv1PSE.selectors.discount ).value = 0;
							// --- No cards handler ---
						}
						document.querySelector( MPv1PSE.selectors.applyCoupon ).disabled = false;
					}
				});
			}
		}

		// === Initialization function

		MPv1PSE.addListenerEvent = function( el, eventName, handler ) {
			if ( el.addEventListener ) {
				el.addEventListener( eventName, handler );
			} else {
				el.attachEvent( "on" + eventName, function() {
					handler.call( el );
				} );
			}
		};

		MPv1PSE.referer = (function () {
			var referer = window.location.protocol + "//" +
				window.location.hostname + ( window.location.port ? ":" + window.location.port: "" );
			return referer;
		})();

		MPv1PSE.AJAX = function( options ) {
			var useXDomain = !!window.XDomainRequest;
			var req = useXDomain ? new XDomainRequest() : new XMLHttpRequest()
			var data;
			options.url += ( options.url.indexOf( "?" ) >= 0 ? "&" : "?" ) + "referer=" + escape( MPv1PSE.referer );
			options.requestedMethod = options.method;
			if ( useXDomain && options.method == "PUT" ) {
				options.method = "POST";
				options.url += "&_method=PUT";
			}
			req.open( options.method, options.url, true );
			req.timeout = options.timeout || 1000;
			if ( window.XDomainRequest ) {
				req.onload = function() {
					data = JSON.parse( req.responseText );
					if ( typeof options.success === "function" ) {
						options.success( options.requestedMethod === "POST" ? 201 : 200, data );
					}
				};
				req.onerror = req.ontimeout = function() {
					if ( typeof options.error === "function" ) {
						options.error( 400, {
							user_agent:window.navigator.userAgent, error : "bad_request", cause:[]
						});
					}
				};
				req.onprogress = function() {};
			} else {
				req.setRequestHeader( "Accept", "application/json" );
				if ( options.contentType ) {
					req.setRequestHeader( "Content-Type", options.contentType );
				} else {
					req.setRequestHeader( "Content-Type", "application/json" );
				}
				req.onreadystatechange = function() {
					if ( this.readyState === 4 ) {
						try {
							if ( this.status >= 200 && this.status < 400 ) {
								// Success!
								data = JSON.parse( this.responseText );
								if ( typeof options.success === "function" ) {
									options.success( this.status, data );
								}
							} else if ( this.status >= 400 ) {
								data = JSON.parse( this.responseText );
								if ( typeof options.error === "function" ) {
									options.error( this.status, data );
								}
							} else if ( typeof options.error === "function" ) {
								options.error( 503, {} );
							}
						} catch (e) {
							options.error( 503, {} );
						}
					}
				};
			}
			if ( options.method === "GET" || options.data == null || options.data == undefined ) {
				req.send();
			} else {
				req.send( JSON.stringify( options.data ) );
			}
		}

		// Form validation

		var doSubmitPSE = false;

		MPv1PSE.doPay = function() {
			if(!doSubmitPSE){
				doSubmitPSE=true;
				document.querySelector(MPv1PSE.selectors.box_loading).style.background = "url("+MPv1PSE.paths.loading+") 0 50% no-repeat #fff";
				btn = document.querySelector(MPv1PSE.selectors.form);
				btn.submit();
			}
		}

		MPv1PSE.validateInputsPSE = function(event) {
			event.preventDefault();
			MPv1PSE.hideErrors();
			var valid_to_pse = true;
			var $inputs = MPv1PSE.getForm().querySelectorAll("[data-checkout]");
			var $inputs_to_validate_pse = MPv1PSE.inputs_to_validate_pse;
			var arr = [];
			for (var x = 0; x < $inputs.length; x++) {
				var element = $inputs[x];
				if($inputs_to_validate_pse.indexOf(element.getAttribute("data-checkout")) > -1){
					if (element.value == -1 || element.value == "") {
						arr.push(element.id);
						valid_to_pse = false;
					}
				}
			}
			if (!valid_to_pse) {
				MPv1PSE.showErrors(arr);
			} 
		}

		MPv1PSE.getForm = function(){
			return document.querySelector(MPv1PSE.selectors.form);
		}

		// Show/hide errors.

		MPv1PSE.showErrors = function(fields){
			var $form = MPv1PSE.getForm();
			for(var x = 0; x < fields.length; x++){
				var f = fields[x];
				var $span = $form.querySelector("#error-" + f);
				var $input = $form.querySelector($span.getAttribute("data-main"));
				$span.style.display = "inline-block";
				$input.classList.add("mp-error-input");
			}
			return;
		}

		MPv1PSE.hideErrors = function(){
			for(var x = 0; x < document.querySelectorAll("[data-checkout]").length; x++){
				var $field = document.querySelectorAll("[data-checkout]")[x];
				$field.classList.remove("mp-error-input");
			} //end for
			for(var x = 0; x < document.querySelectorAll(".erro_pse").length; x++){
				var $span = document.querySelectorAll(".erro_pse")[x];
				$span.style.display = "none";
			}
			return;
		}

		MPv1PSE.Initialize = function( site_id, coupon_mode, discount_action_url, payer_email ) {

			// Sets.
			MPv1PSE.site_id = site_id;
// 			MPv1PSE.coupon_of_discounts.default = coupon_mode;
// 			MPv1PSE.coupon_of_discounts.discount_action_url = discount_action_url;
// 			MPv1PSE.coupon_of_discounts.payer_email = payer_email;
      
      // Flow coupon of discounts.
// 			if ( MPv1PSE.coupon_of_discounts.default ) {
// 				MPv1PSE.addListenerEvent(
// 					document.querySelector( MPv1PSE.selectors.applyCoupon ),
// 					"click",
// 					MPv1PSE.checkCouponEligibility
// 				);
// 			} else {
// 				document.querySelector( MPv1PSE.selectors.formCoupon ).style.display = "none";
// 			}
  
      MPv1PSE.hideErrors();
			return;
		}

		this.MPv1PSE = MPv1PSE;

    MPv1PSE.getAmount = function() {
      return document.querySelector( MPv1PSE.selectors.amount )
      .value - document.querySelector( MPv1PSE.selectors.discount ).value;
    }

    MPv1PSE.getAmountWithoutDiscount = function() {
      return document.querySelector( MPv1PSE.selectors.amount ).value;
    }

    MPv1PSE.text.apply = "<?php echo __( 'Apply', 'woocommerce-mercadopago' ); ?>";
    MPv1PSE.text.remove = "<?php echo __( 'Remove', 'woocommerce-mercadopago' ); ?>";
    MPv1PSE.text.coupon_empty = "<?php echo __( 'Please, inform your coupon code', 'woocommerce-mercadopago' ); ?>";
    MPv1PSE.text.discount_info1 = "<?php echo __( 'You will save', 'woocommerce-mercadopago' ); ?>";
    MPv1PSE.text.discount_info2 = "<?php echo __( 'with discount from', 'woocommerce-mercadopago' ); ?>";
    MPv1PSE.text.discount_info3 = "<?php echo __( 'Total of your purchase:', 'woocommerce-mercadopago' ); ?>";
    MPv1PSE.text.discount_info4 = "<?php echo __( 'Total of your purchase with discount:', 'woocommerce-mercadopago' ); ?>";
    MPv1PSE.text.discount_info5 = "<?php echo __( '*Uppon payment approval', 'woocommerce-mercadopago' ); ?>";
    MPv1PSE.text.discount_info6 = "<?php echo __( 'Terms and Conditions of Use', 'woocommerce-mercadopago' ); ?>";

    MPv1PSE.paths.loading = "<?php echo ( $images_path . 'loading.gif' ); ?>";
    MPv1PSE.paths.check = "<?php echo ( $images_path . 'check.png' ); ?>";
    MPv1PSE.paths.error = "<?php echo ( $images_path . 'error.png' ); ?>";

    MPv1PSE.Initialize(
      "<?php echo $site_id; ?>",
      "<?php echo $coupon_mode; ?>" == "no",
      "<?php echo $discount_action_url; ?>",
      "<?php echo $payer_email; ?>"
    );
  } ).call();
	
  document.querySelector( "#pse_checkout_fieldset" ).style.display = "block";
</script>
