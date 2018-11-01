<?php 

/*===================================================*/
/*================*   SOCIAL LINK   *================*/
/*===================================================*/

function finance_social_profile() {

$options = get_option('finance_framework');


		$finance_twitter = $options['twitter_profile'];
		$finance_facebook = $options['facebook_profile'];
		$finance_linkedin = $options['linkedin_profile'];
		$finance_google = $options['google_profile'];
		$finance_pinterest = $options['pinterest_profile'];
		$finance_dribble = $options['dribble_profile'];
		$finance_flickr = $options['flickr_profile'];
		$finance_behance = $options['behance_profile'];
		$finance_youtube = $options['youtube_profile'];
		$finance_soundcloud = $options['soundcloud_profile'];
		$finance_codepen = $options['codepen_profile'];
		$finance_deviantart = $options['deviantart_profile'];
		$finance_digg = $options['digg_profile'];
		$finance_dropbox = $options['dropbox_profile'];
		$finance_github = $options['github_profile'];
		$finance_instagram = $options['instagram_profile'];
		$finance_skype = $options['skype_profile'];
		$finance_spotify = $options['spotify_profile'];
		$finance_steam = $options['steam_profile'];
		$finance_trello = $options['trello_profile'];
		$finance_tumblr = $options['tumblr_profile'];
		$finance_vimeo = $options['vimeo_profile'];
		$finance_wechat = $options['wechat_profile'];
		$finance_weibo = $options['weibo_profile'];
		$finance_wordpress = $options['wordpress_profile'];
		$finance_xing = $options['xing_profile'];
		$finance_yahoo = $options['yahoo_profile'];
		$finance_yelp = $options['yelp_profile'];

		if (!empty($finance_twitter)) { ?>
				<li class="twitter soc-icon"><a href="<?php echo esc_url( $finance_twitter ); ?>" class="fa fa-twitter"></a></li>
		<?php 
		} 

		if (!empty($finance_google)) { ?>
			<li class="google soc-icon"><a href="<?php echo esc_url( $finance_google ); ?>" class="fa fa-google-plus"></a></li>
		<?php 
		} 

		if (!empty($finance_facebook)) { ?>
			<li class="facebook soc-icon"><a href="<?php echo esc_url( $finance_facebook ); ?>" class="fa fa-facebook"></a></li>
		<?php 
		} 

		if (!empty($finance_linkedin)) { ?>
			<li class="linkedin soc-icon"><a href="<?php echo esc_url( $finance_linkedin ); ?>" class="fa fa-linkedin"></a></li>
		<?php 
		} 

		if (!empty($finance_pinterest)) { ?>
			<li class="pinterest soc-icon"><a href="<?php echo esc_url( $finance_pinterest ); ?>" class="fa fa-pinterest"></a></li>
		<?php 
		} 

		if (!empty($finance_dribble)) { ?>
			<li class="dribble soc-icon"><a href="<?php echo esc_url( $finance_dribble ); ?>" class="fa fa-dribbble"></a></li>
		<?php 
		}

		if (!empty($finance_flickr)) { ?>
			<li class="flickr soc-icon"><a href="<?php echo esc_url( $finance_flickr ); ?>" class="fa fa-flickr"></a></li>
		<?php 
		}

		if (!empty($finance_behance)) { ?>
			<li class="behance soc-icon"><a href="<?php echo esc_url( $finance_behance ); ?>" class="fa fa-behance"></a></li>
		<?php 
		}

		if (!empty($finance_youtube)) { ?>
			<li class="youtube soc-icon"><a href="<?php echo esc_url( $finance_youtube ); ?>" class="fa fa-youtube"></a></li>
		<?php 
		}

		if (!empty($finance_soundcloud)) { ?>
			<li class="soundcloud soc-icon"><a href="<?php echo esc_url( $finance_soundcloud ); ?>" class="fa fa-soundcloud"></a></li>
		<?php 
		}

		if (!empty($finance_codepen)) { ?>
			<li class="codepen soc-icon"><a href="<?php echo esc_url( $finance_codepen ); ?>" class="fa fa-codepen"></a></li>
		<?php 
		}

		if (!empty($finance_deviantart)) { ?>
			<li class="deviantart soc-icon"><a href="<?php echo esc_url( $finance_deviantart ); ?>" class="fa fa-deviantart"></a></li>
		<?php 
		}

		if (!empty($finance_digg)) { ?>
			<li class="digg soc-icon"><a href="<?php echo esc_url( $finance_digg ); ?>" class="fa fa-digg"></a></li>
		<?php 
		}

		if (!empty($finance_dropbox)) { ?>
			<li class="dropbox soc-icon"><a href="<?php echo esc_url( $finance_dropbox ); ?>" class="fa fa-dropbox"></a></li>
		<?php 
		}

		if (!empty($finance_github)) { ?>
			<li class="github soc-icon"><a href="<?php echo esc_url( $finance_github ); ?>" class="fa fa-github"></a></li>
		<?php 
		}

		if (!empty($finance_instagram)) { ?>
			<li class="instagram soc-icon"><a href="<?php echo esc_url( $finance_instagram ); ?>" class="fa fa-instagram"></a></li>
		<?php 
		}

		if (!empty($finance_skype)) { ?>
			<li class="skype soc-icon"><a href="<?php echo esc_url( $finance_skype ); ?>" class="fa fa-skype"></a></li>
		<?php 
		}

		if (!empty($finance_spotify)) { ?>
			<li class="spotify soc-icon"><a href="<?php echo esc_url( $finance_spotify ); ?>" class="fa fa-spotify"></a></li>
		<?php 
		}

		if (!empty($finance_steam)) { ?>
			<li class="steam soc-icon"><a href="<?php echo esc_url( $finance_steam ); ?>" class="fa fa-steam"></a></li>
		<?php 
		}

		if (!empty($finance_trello)) { ?>
			<li class="trello soc-icon"><a href="<?php echo esc_url( $finance_trello ); ?>" class="fa fa-trello"></a></li>
		<?php 
		}

		if (!empty($finance_tumblr)) { ?>
			<li class="tumblr soc-icon"><a href="<?php echo esc_url( $finance_tumblr ); ?>" class="fa fa-tumblr"></a></li>
		<?php 
		}

		if (!empty($finance_vimeo)) { ?>
			<li class="vimeo soc-icon"><a href="<?php echo esc_url( $finance_vimeo ); ?>" class="fa fa-vimeo-square"></a></li>
		<?php 
		}

		if (!empty($finance_wechat)) { ?>
			<li class="wechat soc-icon"><a href="<?php echo esc_url( $finance_wechat ); ?>" class="fa fa-weixin"></a></li>
		<?php 
		}

		if (!empty($finance_weibo)) { ?>
			<li class="weibo soc-icon"><a href="<?php echo esc_url( $finance_weibo ); ?>" class="fa fa-weibo"></a></li>
		<?php 
		}

		if (!empty($finance_wordpress)) { ?>
			<li class="wordpress soc-icon"><a href="<?php echo esc_url( $finance_wordpress ); ?>" class="fa fa-wordpress"></a></li>
		<?php 
		}

		if (!empty($finance_xing)) { ?>
			<li class="xing soc-icon"><a href="<?php echo esc_url( $finance_xing ); ?>" class="fa fa-xing"></a></li>
		<?php 
		}

		if (!empty($finance_yahoo)) { ?>
			<li class="yahoo soc-icon"><a href="<?php echo esc_url( $finance_yahoo ); ?>" class="fa fa-yahoo"></a></li>
		<?php 
		}

		if (!empty($finance_yelp)) { ?>
			<li class="yelp soc-icon"><a href="<?php echo esc_url( $finance_yelp ); ?>" class="fa fa-yelp"></a></li>
		<?php 
		} 

}

/*===================================================*/
/*===================*   SHARE   *===================*/
/*===================================================*/

function finance_social_share() { 
global $post;
	?>
	<div class="social-share-wrapper">
	<ul class="social-share">
		<li class="facebook"><a href="http://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>" class="product_share_facebook" onclick="javascript:window.open(this.href,
							'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=220,width=600');return false;"><i class="fa fa-facebook"></i></a></li>
		<li class="twitter"><a href="https://twitter.com/share?url=<?php the_permalink(); ?>&text=<?php echo urlencode(get_the_title()); ?>" onclick="javascript:window.open(this.href,
							'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=260,width=600');return false;" class="product_share_twitter"><i class="fa fa-twitter"></i></a></li>   
		<li class="google"><a href="https://plus.google.com/share?url=<?php the_permalink(); ?>" onclick="javascript:window.open(this.href,
							'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="fa fa-google-plus"></i></a></li>
	</ul>
<div class="border-social"></div>
</div><!-- Social Share Wrapper -->
<?php
}


/*===================================================*/
/*==================*   EXCERPT   *==================*/
/*===================================================*/

function finance_excerpt($limit) {
	$excerpt = explode(' ', get_the_excerpt(), $limit);
	if (count($excerpt)>=$limit) {
		array_pop($excerpt);
		$excerpt = implode(" ",$excerpt).'...';
	} else {
		$excerpt = implode(" ",$excerpt);
	} 
	$excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);
	return $excerpt;
}
 
function finance_content($limit) {
	$content = explode(' ', get_the_content(), $limit);
	if (count($content)>=$limit) {
		array_pop($content);
		$content = implode(" ",$content).'...';
	} else {
		$content = implode(" ",$content);
	} 
	$content = preg_replace('/\[.+\]/','', $content);
	$content = apply_filters('the_content', $content); 
	$content = str_replace(']]>', ']]&gt;', $content);
	return $content;
}


function finance_custom_excerpt_length( $length ) {
	return 40;
}
add_filter( 'excerpt_length', 'finance_custom_excerpt_length', 999 );

function finance_new_excerpt_more( $more ) {
	return '...';
}
add_filter('excerpt_more', 'finance_new_excerpt_more');

/*=========*   BREADCRUMB   *=========*/
function finance_breadcrumb() {
    echo '<div class="breadcrumbs"><p>';
    esc_html_e("You Are Here : ", 'finance');

    if (!is_home()) {
            echo '<span><a href="';
            echo esc_url( home_url( '/' ) );
            echo '">'; ?>
            <?php esc_html_e( 'Home', 'finance' ); ?>
            <?php echo '</a></span><span class="arrow"><i class="icon-finance-arrow-right"></i></span>';
        if (is_category() || is_singular('post')) {
                echo '<span>';
                the_category(' </span><span> ');
            if (is_single()) {
                    echo '</span><span class="arrow"><i class="icon-finance-arrow-right"></i></span><span>';
                    the_title();
                        echo '</span>';
            }
        } elseif (is_page()) {
                echo '<span>';
                echo the_title();
                echo '</span>';
        }
        elseif ('finance-service' == get_post_type()) {
                echo '<span>'; ?>
                <?php esc_html_e( 'Service', 'finance' ); ?>
                <?php 
                echo '</span><span class="arrow"><i class="icon-finance-arrow-right"></i></span><span>';
                the_title();
                echo '</span>';                
        }
    }
    elseif (is_tag()) {single_tag_title();
    }
    elseif (is_author()) { ?><span><?php esc_html_e( 'Author Archive"', 'finance' ); echo'</span>';
    }
    elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?><span><?php esc_html_e( 'Blog Archives', 'finance' ); echo'</span>';
    }
    elseif (is_search()) { ?><span><?php esc_html_e( 'Search Results', 'finance' ); echo'</span>';
    }
        echo '</p></div>';
}

/*=========*   MENU TOP OPTION   *=========*/
function finance_top_menu_option() {

	$options = get_option('finance_framework');

	$finance_header_time = $options['header_time'];
	$finance_header_phone = $options['header_phone'];
	$finance_header_email = $options['header_email']; ?>
	
	<?php if(!empty($finance_header_time)){ ?>
	<li>
		<i class="icon-finance-timer"></i>
		<p>
			<span class="heading"><?php esc_html_e( 'Contact Time', 'finance' ); ?></span>
			<span><?php echo sanitize_text_field( $finance_header_time ); ?></span>
		</p>
	</li>
	<?php }

	if(!empty($finance_header_phone)){ ?>
	<li>
		<i class="icon-finance-call-in"></i>
		<p>
			<span class="heading"><?php esc_html_e( 'Nosso telefone', 'finance' ); ?></span>
			<span><?php echo sanitize_text_field( $finance_header_phone ); ?></span>
		</p>
	</li>
	<?php }

	if(!empty($finance_header_email)){ ?>
	<li class="last">
		<i class="icon-finance-mail"></i>
		<p>
			<span class="heading"><?php esc_html_e( 'E-mail', 'finance' ); ?></span>
			<span><?php echo sanitize_text_field( $finance_header_email ); ?></span>
		</p>
	</li>
	<?php }

}


/*=========*   HEADER BUTTON   *=========*/

function finance_header_address() {

$options = get_option('finance_framework');
$finance_header_address = $options['header_address'];

if(!empty( $finance_header_address)) { ?>
<div class="address-bar">
	<i class="icon-finance-map-marker"></i>
	<p><?php echo sanitize_text_field( $finance_header_address ); ?></p>
</div>
<?php }
}

function finance_header_button() {

$options = get_option('finance_framework');
$finance_header_btn = $options['header_btn'];
$finance_header_link = $options['header_link'];

if(!empty( $finance_header_btn)) { ?>
<div class="quote-link">
	<a href="<?php echo esc_url( $finance_header_link ); ?>"><?php echo sanitize_text_field( $finance_header_btn ); ?></a>
</div>
<?php }
}

/*=====================================================*/
/*============*   PAGE TITLE BACKGROUND   *============*/
/*=====================================================*/

function finance_page_title_standard() { ?>

	<div class="page-title standard">
		<div class="title-wrap container">
			<h3 class="wow fadeInUp" data-wow-delay="0.5s"><?php the_title(); ?></h3>
		</div>
	</div>

<?php }

function finance_page_title() {
	if ( class_exists( 'acf' ) ) {

		$finance_use_page_subtitle	= get_field('use_page_subtitle');
		$finance_use_breadcrumb	= get_field('use_breadcrumb');
		$finance_subtitle_default_page		= get_field('subtitle_default_page'); ?>
		
		<?php if($finance_use_page_subtitle == true ) { ?>
		<div class="page-title">
			<div class="title-wrap container">
				<div class="left-side">
					<h3 class="wow fadeInUp" data-wow-delay="0.5s"><?php the_title(); ?></h3>
					
					<?php if(!empty($finance_subtitle_default_page)) { ?>
					<p class="wow fadeIn" data-wow-duration="1s" data-wow-delay="0.7s"><?php echo balancetags( $finance_subtitle_default_page ); ?></p>
					<?php } ?>
				</div>

				<div class="right-side">
					<?php 
					if($finance_use_breadcrumb == true) {
					finance_breadcrumb(); } ?>
				</div>
			</div>
		</div>
		<?php } 
		else {

		/*if use_page_subtitle == false*/
		finance_page_title_standard();

		} ?>

	<?php } 
	else {

	/*if acf doesn't exist*/
	finance_page_title_standard();

	} 
}

/*=====================================================*/
/*============*   BLOG TITLE BACKGROUND   *============*/
/*=====================================================*/

function finance_blog_title() {
	if ( class_exists( 'Redux' ) ) {

		$options = get_option('finance_framework');
		$finance_blog_title = $options['blog_title'];
		$finance_blog_subtitle = $options['blog_subtitle']; ?>
			
		<?php if(!empty($finance_blog_title)) {
		echo '<div class="page-title clearfix">'; ?>
			<div class="title-wrap container">
				
				<h3 class="wow fadeInUp" data-wow-delay="0.5s"><?php echo sanitize_text_field( $finance_blog_title ); ?></h3>
				<p class="wow fadeIn" data-wow-duration="1s" data-wow-delay="0.7s"><?php echo sanitize_text_field( $finance_blog_subtitle ); ?></p>
			</div>
		</div>
		<?php } 
	} 
}