<?php 

get_header(); 

//Extracting the values that user defined in OptionTree plugin 
$errorCode = ot_get_option('error_code');
$errorCodeExplanation = ot_get_option('error_code_explanation');
$errorText = ot_get_option('error_text');
$bg404 = ot_get_option('error_text_background');

?> 
		<!-- BEGIN PAGE TITLE -->
		<div id='top-divider' class='container'></div>
		
		<style>
		.body-error-page {
			background: url(<?php echo $bg404['background-image']; ?>) !important;
			background-position: center bottom !important;
			background-attachment: fixed !important;
			background-repeat: no-repeat !important;
		}
		</style>
		<!-- BEGIN 404 PAGE CONTENT -->
		<div class="container">
			<div class="sixteen columns error-page-wrap">
				<div id="error-code"><?php echo $errorCode; ?></div>
				<div id="error-undercode"><?php echo $errorCodeExplanation; ?></div>
				<div id="error-message"><?php echo $errorText; ?></div>
			</div>
		</div>
		<!-- END 404 PAGE CONTENT -->
		
<?php get_footer(); ?> 