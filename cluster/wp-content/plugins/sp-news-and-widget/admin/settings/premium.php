<?php
/**
 * Plugin Premium Offer Page
 *
 * @package WP News and Scrolling Widgets
 * @since 1.0.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="wrap">

	<h2><?php _e( 'WP News and Scrolling Widgets - Join the Membership', 'sp-news-and-widget' ); ?></h2><br />

	<style>
		.clearfix:before, .clearfix:after{content:" ";display:table;}
		.clearfix:after{clear:both;}
		.wpos-pricing-page *{webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;}
		.wpos-pricing-page .columns {position: relative; padding-left: 0.9375rem;  padding-right: 0.9375rem; float: left;}
		.wpos-pricing-page .text-center{text-align:center;}		
		.wpos-pricing-page{text-align:center;}
		.wpos-pricing-page .medium-3 { width: 25%;}
		.wpos-pricing-page .medium-6{width: 50%; }
		.wpos-pricing-page .medium-6 .inner-features{background:#fff; padding:20px; margin-bottom:15px;}
		.wpos-pricing-page {margin:100px 0 50px 0; }
		.wpos-pricing-features{margin:20px 0 20px 0; text-align:left;}
		.wpos-pricing-features h2{font-size:35px;}	
		.wpos-pricing-page h4{font-size:22px;}	
		.wpos-pricing-page .table-option { background: #fff;  border: 2px solid #f1f1f1;    width: 100%;   position: relative;}
		.table-option h3 {  margin-bottom: 0; font-size:36px; display:block;}
		.table-option ul.table-option-ul { margin: 15px 0 !important;  padding:0px !important; list-style:none;}
		.table-option li { border-bottom: 1px solid #f1f1f1; padding: .5rem 0; margin-bottom: 0;  font-size: 1rem; list-style-type: none;}
		.table-option li.pricing { color: #222; font-size: 3rem;  line-height: 1; padding: .5rem 0 2rem 0;}
		.table-option > span { background: #00a69d; color: #fff; bottom: 100%;  left: 0;  right: 0;  position: absolute; margin-left: -4px;  margin-right: -4px;    padding: 1rem;   display: block;}
		.table-option .plus{font-weight:bold;}	
		.best-value .table-option { border: 4px solid #00a69d;}	
		.table-option .price {color:#1a4562;}
		.table-option strong{color:#e34f43;}
		.table-option sup {top: -0.3em;}
		.table-option{padding-top: 1rem;padding-bottom: 1rem;}
		.table-option li.pricing {  color: #222; font-size: 3rem; line-height: 1;  padding: .5rem 0 2rem 0;}
		.pricing .price {  position: relative;}
		.length { display: block; font-size: 1rem; margin-top: .25rem;  color: #717f86;}
		.table-option .button{margin-bottom:0px;}
		.wpos-pricing-faq-page{max-width:64rem; margin:0 auto; margin-bottom:40px;  }
		.wpos-pricing-faq-page .faq-inner { background: #f1f1f1;  margin:20px 0 20px 0;  border-radius: 4px; padding: 20px;}
		.wpos-pricing-faq-page .faq-inner p{margin-bottom:0px !important;}
		.pricing-overview{max-width:500px; margin:0 auto; padding:0; list-style-type: none;font-size: 17px; border: 1px solid #d0d0d0;}
		.pricing-overview li{padding:15px;}
		.pricing-overview li:nth-child(2n+1){background: #f2f2f2;}
	</style>	
	
	<div class="wpos-pricing-page clearfix">		
			<div class="medium-3 columns">
				<div class="table-option clearfix">
					<h3>Ultimate</h3>
						<ul class="table-option-ul clearfix">
							<li class="pricing">
								<span class="price"><sup>$</sup>299</span>	
										<span class="length">one-time payment</span>
								</li>
								<li class="feature"><a href="https://www.wponlinesupport.com/plugins/" target="_blank">41 Pro Plugins</a></li>
								<li class="feature"><a href="https://www.wponlinesupport.com/plugins-addon/" target="_blank">4 Pro Plugins Addons</a></li>
								<li class="feature"><span class="plus">Plus</span> 10 Themes</li>
	                            <li class="feature"><a href="http://powerpack.wponlinesupport.com/" target="_blank">PowerPack</a></li>
								<li class="feature"><strong>Lifetime product updates</strong></li>
								<li class="feature"><strong>Lifetime Ticket support</strong></li>
								<li class="feature"><strong>Unlimited sites</strong></li>								
	                   </ul>
					   <div class="footer clearfix">
							<a class="button button-primary button-large" target="_blank" href="https://www.wponlinesupport.com/pricing/">Purchase</a>
                        </div>
				</div>			
			</div>
			<div class="medium-3 columns best-value">
				<div class="table-option clearfix">
					<span>Most popular</span>
					<h3>Professional</h3>
					<ul class="table-option-ul clearfix">
							<li class="pricing">
								<span class="price"><sup>$</sup>99</span>	
										<span class="length">per year</span>
								</li>
								<li class="feature"><a href="https://www.wponlinesupport.com/plugins/" target="_blank">41 Pro Plugins</a></li>
								<li class="feature"><a href="https://www.wponlinesupport.com/plugins-addon/" target="_blank">4 Pro Plugins Addons</a></li>
								<li class="feature"><span class="plus">Plus</span> 10 Themes</li>
	                            <li class="feature"><a href="http://powerpack.wponlinesupport.com/" target="_blank">PowerPack</a></li>
								<li class="feature">1 Year product updates</li>
								<li class="feature">1 Year Ticket support</li>
								<li class="feature"><strong>Unlimited sites</strong></li>								
	                   </ul>
					   <div class="footer clearfix">
							<a class="button button-primary button-large" target="_blank" href="https://www.wponlinesupport.com/pricing/">Purchase</a>
                        </div>
				</div>			
			</div>
			<div class="medium-3 columns">
				<div class="table-option clearfix">
					<h3>Plus</h3>
					<ul class="table-option-ul clearfix">
							<li class="pricing">
								<span class="price"><sup>$</sup>79</span>	
										<span class="length">per year</span>
								</li>
								<li class="feature"><a href="https://www.wponlinesupport.com/plugins/" target="_blank">41 Pro Plugins</a></li>
								<li class="feature"><a href="https://www.wponlinesupport.com/plugins-addon/" target="_blank">4 Pro Plugins Addons</a></li>
								<li class="feature"><span class="plus">Plus</span> 10 Themes</li>
	                            <li class="feature"><a href="http://powerpack.wponlinesupport.com/" target="_blank">PowerPack</a></li>
								<li class="feature">1 Year product updates</li>
								<li class="feature">1 Year Ticket support</li>
								<li class="feature"><strong>5 sites</strong></li>								
	                   </ul>
					   <div class="footer clearfix">
							<a class="button button-primary button-large" target="_blank" href="https://www.wponlinesupport.com/pricing/">Purchase</a>
                        </div>
				
				</div>			
			</div>
			<div class="medium-3 columns">
				<div class="table-option clearfix">
					<h3>Personal</h3>
					<ul class="table-option-ul clearfix">
							<li class="pricing">
								<span class="price"><sup>$</sup>49</span>	
										<span class="length">per year</span>
								</li>
								<li class="feature"><a href="https://www.wponlinesupport.com/plugins/" target="_blank">41 Pro Plugins</a></li>
								<li class="feature"><a href="https://www.wponlinesupport.com/plugins-addon/" target="_blank">4 Pro Plugins Addons</a></li>
								<li class="feature"><span class="plus">Plus</span> 10 Themes</li>
	                            <li class="feature"><a href="http://powerpack.wponlinesupport.com/" target="_blank">PowerPack</a></li>
								<li class="feature">1 Year product updates</li>
								<li class="feature">1 Year Ticket support</li>
								<li class="feature"><strong>1 site</strong></li>								
	                   </ul>
					   <div class="footer clearfix">
							<a class="button button-primary button-large" target="_blank" href="https://www.wponlinesupport.com/pricing/">Purchase</a>
                        </div>				
				</div>			
			</div>		
		</div>
		<div class="wpos-pricing-page wpos-pricing-features clearfix">					
			<h2 class="text-center" style="margin-bottom:30px;">Here are 8 good reasons to join the membership</h2>
			<div class="medium-6 columns">
				<div class="inner-features">
					<h4>1. High quality support</h4>
					<p>All of our team of senior developers spend time every day handling the requests coming from our support platform, offering all of our customers years of experience in the field. </p>			
				</div>
			</div>
			<div class="medium-6 columns">
				<div class="inner-features">
					<h4>2. Quick answers</h4>
					<p>We try to answer each request for help within the same day we receive them (except for weekends) and most of the times we manage to do so in just a few hours after we received a ticket. </p>			
				</div>
			</div>
			<div class="medium-6 columns">
				<div class="inner-features">
					<h4>3. Manage support tickets from account</h4>
					<p>The panel that allows opening and managing tickets withing your account area is simple and easy to use and it's been designed to fix your issue in just one answer, whenever possible. </p>			
				</div>
			</div>
			<div class="medium-6 columns">
				<div class="inner-features">
					<h4>4. Regularly updated products</h4>
					<p>All of our products (Plugins and Themes) are regularly updated and made compatible with the latest releases of WordPress and WooCommerce. </p>			
				</div>
			</div>
			<div class="medium-6 columns" style="clear:both;">
				<div class="inner-features">
					<h4>5. Guaranteed customer satisfaction objective</h4>
					<p>Satisfying our customers is the objective we keep in our minds, which motivates us to keep doing our best: in the past six months the average percentage of customers satisfaction kept a stable 97%.  </p>			
				</div>	
			</div>
			<div class="medium-6 columns">
				<div class="inner-features">
					<h4>6. A continued service guaranteed in the years</h4>
					<p>Dealing with a company rather than a single author allows you to feel secured about a continued service that will last through the years. A single developer may, as it often happens, suddenly disappear. We have been online since 2014 and we keep improving year after year.   </p>			
				</div>	
			</div>
			<div class="medium-6 columns">
				<div class="inner-features">
					<h4>7. A constant improvement of our existing products</h4>
					<p>All of our products are constantly improved by fixing any bug our customers find and adding new features, most of which suggested by our customers.   </p>			
				</div>	
			</div>
			<div class="medium-6 columns">
				<div class="inner-features">
					<h4>8. Be part of the communiry and suggest the product you want :)</h4>
					<p>Are you looking for a plugin we don't have? Tell us how it is and we will take it into account for a possible future development. Are you currently using one of our plugin? Would you like a specific new feature? Tell us how we can improve and we will do our best to make you happy.   </p>			
				</div>	
			</div>			
		</div>
</div>