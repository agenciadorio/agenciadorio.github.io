<?php 

$themeLayout = ot_get_option('theme_layout');
$background = ot_get_option('background');
$backgroundType = ot_get_option('background_type');
	
$primaryColor = ot_get_option('primary_color');
$secondaryColor = ot_get_option('secondary_color');	


 if ( $themeLayout == 'boxed' ) { ?>
		.header-shadow {
			box-shadow:none;
		}
		#wrapper {
			width:1320px;
			margin-left:auto;
			margin-right:auto;
			background:#fff;
			box-shadow:0px 0px 10px #444;
		}
		#back-to-top {
			display:none;
		}
		@media only screen and (max-width: 1289px) and (min-width: 1030px) {
			#wrapper {
				width:1060px;
			}
			#back-to-top {
				display:none;
			}
		}
		@media only screen and (max-width: 1029px) and (min-width: 769px) {
			#wrapper {
				width:100%;
			}
			#back-to-top {
				display:block;
			}
		}
		@media only screen and (max-width: 768px) and (min-width: 481px) {
			#wrapper {
				width:100%;
			}
			#back-to-top {
				display:block;
			}
		}
		@media only screen and (max-width: 480px) {
			#wrapper {
				width:100%;
			}
			#back-to-top {
				display:block;
			}
		}
		
		<?php } if ( $themeLayout == 'boxed' && isset($background) ) { ?>
		
		body {
			<?php if ($backgroundType == "image") { ?>
			background: url(<?php echo $background['background-image']; ?>) no-repeat center center fixed;
			-webkit-background-size:cover;
			-moz-background-size:cover;
			-o-background-size:cover;
			background-size:cover;
			<?php } else if ($backgroundType == "pattern") { ?>
			background: url(<?php echo $background['background-image']; ?>) repeat;
			<?php } ?>
		} 
		
		<?php } if ( isset($primaryColor) ) { ?>
		
		.room-overlay-readmore, .room-overlay-checkavail, .blog-overlay-readmore, .rooms-list-item-price, .modal-footer button,
		.blog-single #submit-button:hover, .contact-page #submit-button:hover, .reservation-page-wrap #submit-button:hover,
		.reservation-content #reservation-step1-button, .reservation-content #reservation-step3-button, .step4-return-home, 
		.reservation-content .ui-state-highlight, .room-reservation-select, table th, .button-alt, .color-highlight, .accordion-header.show span, #blog-page-navigation-wrap .current {
			background-color:<?php echo $primaryColor; ?>
		}
		#blog-page-navigation-wrap .current {
			border-color:<?php echo $primaryColor; ?>
		}
		#book-button {
			background-color:<?php echo $primaryColor; ?>;
			border-color:<?php echo $primaryColor; ?>
		}
		.reservation-page-wrap #room-price, .reservation-page-wrap #tabs li#current a, #room-features .icon-star, 
		ul.list.painted span, .accordion-header a:hover, .accordion-header.show a {
			color:<?php echo $primaryColor; ?>
		}
		.blog-single #submit-button, .contact-page #submit-button, .reservation-page-wrap #submit-button {
			border-color:<?php echo $primaryColor; ?>;
			color:<?php echo $primaryColor; ?>;
		}
		.sidebar-header {
			border-bottom-color:<?php echo $primaryColor; ?>;
			color:<?php echo $primaryColor; ?>;
		}
		.button-alt:hover {
			outline-color:<?php echo $primaryColor; ?>;
		}
		.button-standard {
			color:<?php echo $primaryColor; ?> !important;
			border-color:<?php echo $primaryColor; ?>
		}
		.button-standard:hover {
			background-color:<?php echo $primaryColor; ?>
		}
		blockquote {
			border-left-color:<?php echo $primaryColor; ?>
		}
		
		<?php } if ( isset($secondaryColor) ) { ?>
		
		#headcontainer {
			background-color:<?php echo $secondaryColor; ?>
		}
		#footer-wrap {
		<?php 
		//Convert hex color to rgba
		list($r, $g, $b) = sscanf($secondaryColor, "#%02x%02x%02x");
		?>
			border-top:0px;
			background-color:<?php echo $secondaryColor; ?> !important
		}
		footer:before {
			content:'\a0 ';
			display:block;
			height:8px;
			width:100%;
			background-color:rgba(<?php echo "$r, $g, $b, 0.55"; ?>) !important;
		}
		#top-navigation-menu .sub_menu li a:hover { color: <?php echo $secondaryColor; ?> !important }
		#footer-wrap { margin-top:0px; }
		#about-us-content a { background-color: <?php echo $secondaryColor; ?> }
		#top-navigation-menu .sub_menu { border-top-color: <?php echo $secondaryColor; ?> }
		#top-navigation-menu .submenu-arrow-wrap .top-submenu-arrow { border-bottom-color: <?php echo $secondaryColor; ?> }
		
		<?php } ?>
