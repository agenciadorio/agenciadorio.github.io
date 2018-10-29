<?php

add_filter('construction-condition-get-option', 'construction_condition_get_option');
// custom header type
add_filter('construction-header-type', 'construction_header_type', 5, 2);

add_filter('body_class', 'construction_body_class');

add_filter('construction-menu-icon-default', 'construction_custom_menu_icon_default');
function construction_social_link( $atts ){
    $atts = shortcode_atts( array(
        'icon' => 'icon-facebook',
        'link' => '#'
    ), $atts );

    return '<a href="'. esc_url( $atts['link'] ) .'"><i class="'. esc_attr( $atts['icon'] ) .'"></i></a> ';
}

function construction_rotated_text( $atts ){
    $atts = shortcode_atts( array(
        'text' => ''
    ), $atts );
    if( empty( $atts['text']) ) return;
    return '<span class="rotate">'.esc_attr( $atts['text'] ) .'</span>';
}



function construction_animation_tag( $atts, $content=null ){
    $atts = shortcode_atts( array(
        'tag_name' => 'p',
        'class' => 'fadeInDown',
        'duration' => '.8s',
        'delay' => '0.8s'
    ), $atts );
    $tag = $atts['tag_name'];
    if( empty( $tag ) ) return;
    unset( $atts['tag_name'] );

    $attrs = '';
    foreach( $atts as $att => $v ){
        if( ! empty( $v ) ){
            if( $att === 'class'){
                $attrs .= ' class="wow '. esc_attr( $v ) .'"';
            }else{
                $attrs .=' data-wow-'. esc_attr( $att ) .'="'. esc_attr( $v ) .'"';
            }
        }
    }
    return implode(array('<', esc_attr( $tag ), $attrs, '>', do_shortcode( $content ), '</', $tag,'>') );
}

function construction_counter_list( $atts=null, $content=null ){
    echo '<div class="m-counter-list">';
    echo str_replace(array('<p>','</p>'),'', $content);
    echo '</div>';
}

function construction_standar_list( $atts=null, $content=null ){
    echo '<div class="m-standard-list">';
    echo str_replace(array('<p>','</p>'),'', $content);
    echo '</div>';
}

function construction_get_image_size( $size ){
    if( is_array( $size ) ) return $size;
    $img_size = array();
    if ( is_string( $size ) ){
        if ( in_array( $size, array('thumbnail', 'medium', 'medium_large', 'large') ) ) {
            $img_size[]  = get_option( "{$size}_size_w" );
            $img_size[] = get_option( "{$size}_size_h" );
        }elseif ( isset( $_wp_additional_image_sizes[ $size ] ) ) {
            $img_size = array(
                $_wp_additional_image_sizes[ $size ]['width'],
                $_wp_additional_image_sizes[ $size ]['height']
            );
        }else{
            preg_match_all( '/\d+/', $size, $thumb_matches );
            if ( isset( $thumb_matches[0] ) ) {
                if ( count( $thumb_matches[0] ) > 1 ) {
                    $img_size[] = $thumb_matches[0][0]; // width
                    $img_size[] = $thumb_matches[0][1]; // height
                } elseif ( count( $thumb_matches[0] ) > 0 && count( $thumb_matches[0] ) < 2 ) {
                    $img_size[] = $thumb_matches[0][0]; // width
                    $img_size[] = $thumb_matches[0][0]; // height
                } else {
                    $img_size = false;
                }
            }
        }
    }

    return ! empty($img_size) ? $img_size : array(600,600);
}

function construction_get_no_image( $size ){
    $size = construction_get_image_size( $size );
    if( ! empty( $size ) ){
        $img = '<img class="img-responsive" src="'. apply_filters('construction-no-image-default', '//dummyimage.com/'. $size[0] .'x'. $size[1] .'/fa721c/ffffff&text=NO+IMAGE', $size ) .'" />';
        echo do_shortcode($img);
    }
}

function construction_body_class( $class ){
    if( is_page() ){
        $template = get_page_template_slug();
        if( $template === 'template-home.php'){
            $class[] = 'home body';
            if( construction_get_object_option('border_layout') ){
                $class[] = 'border-body';
            }else{
                $class[] = 'home-t-h';
            }
            add_filter('construction-header-type', 'construction_header_transparent');
        }else{
            add_filter('construction-custom-nav-link', 'construction_custom_subpage_link');
        }
    }else{
        add_filter('construction-custom-nav-link', 'construction_custom_subpage_link');
    }
    return $class;
}


function construction_filtercontent($variable){
	return wp_kses_post( $variable );
}

function construction_get_assets( $file, $parent='images/' ){
    return get_template_directory_uri() .'/'. $parent . $file;
}

function construction_get_class_column( $columns ){
    if($columns == 4){
        $class_columns = "ui-grid ui-four-column col-lg-3 col-md-4 col-sm-6 col-xs-12";
    }elseif($columns == 3){
        $class_columns = "ui-grid ui-three-column col-md-4 col-sm-6 col-xs-12";
    }elseif($columns == 2){
        $class_columns = "ui-grid ui-two-column col-sm-6 col-xs-12";
    }else{
        $class_columns = "ui-one-column col-xs-12";
    }

    return $class_columns;
}

/* Page title */
if (!function_exists('construction_page_title')) {
    function construction_page_title() { 
            if( is_home() ){
				esc_html_e('Home', 'construction');
			}elseif(is_search()){
                esc_html_e('Search Keyword: ', 'construction');
				echo '<span class="keywork">'. get_search_query(). '</span>';
            }elseif( is_404() ){
                esc_html_e('ERROR 404', 'construction');
            }elseif (is_single()) {
                the_title();
            }elseif (!is_archive()) {
                
                the_title();
            } else { 
                if (is_category()){
                    single_cat_title();
                }elseif(get_post_type() == 'recipe' || get_post_type() == 'portfolio' || get_post_type() == 'produce' || get_post_type() == 'team' || get_post_type() == 'testimonial' || get_post_type() == 'myclients'){
                    single_term_title();
                }elseif(get_post_type() == 'product'){
                    woocommerce_page_title();
                }elseif (is_tag()){
                    single_tag_title();
                }elseif (is_author()){
                    printf(__('Author: %s', 'construction'), '<span class="vcard">' . get_the_author() . '</span>');
                }elseif (is_day()){
                    printf(__('Day: %s', 'construction'), '<span>' . get_the_date() . '</span>');
                }elseif (is_month()){
                    printf(__('Month: %s', 'construction'), '<span>' . get_the_date('F Y') . '</span>');
                }elseif (is_year()){
                    printf(__('Year: %s', 'construction'), '<span>' . get_the_date('Y') . '</span>');
                }elseif (is_tax('post_format', 'post-format-aside')){
                    esc_html_e('Asides', 'construction');
                }elseif (is_tax('post_format', 'post-format-gallery')){
                    esc_html_e('Galleries', 'construction');
                }elseif (is_tax('post_format', 'post-format-image')){
                    esc_html_e('Images', 'construction');
                }elseif (is_tax('post_format', 'post-format-video')){
                    esc_html_e('Videos', 'construction');
                }elseif (is_tax('post_format', 'post-format-quote')){
                    esc_html_e('Quotes', 'construction');
                }elseif (is_tax('post_format', 'post-format-link')){
                    esc_html_e('Links', 'construction');
                }elseif (is_tax('post_format', 'post-format-status')){
                    esc_html_e('Statuses', 'construction');
                }elseif (is_tax('post_format', 'post-format-audio')){
                    esc_html_e('Audios', 'construction');
                }elseif (is_tax('post_format', 'post-format-chat')){
                    esc_html_e('Chats', 'construction');
                }else{
                    esc_html_e('Archives', 'construction');
                }
            }
                
            // return ob_get_clean();
    }
}


/* Page breadcrumb */
if (!function_exists('construction_page_breadcrumb')) {
    function construction_page_breadcrumb($delimiter) {
            ob_start();
            $delimiter = strpos( $delimiter, 'fa' ) !== false ? '<i class="'. $delimiter .'"></i>' : esc_attr($delimiter);
            $is_woo = function_exists('is_woocommerce') ? is_woocommerce() : false;
            if( $is_woo ){
                $args = array(
                    'delimiter' => $delimiter
                );
                return woocommerce_breadcrumb( $args );
            }
            $home = '<i class="fa fa-home"></i>' .esc_html__('Home', 'construction');
            $before = '<span class="current">'; // tag before the current crumb
            $after = '</span>'; // tag after the current crumb

            global $post;
            $homeLink = home_url('/');
            if( is_home() ){
                esc_html_e('Home', 'construction');
            }else{
                echo '<a href="' . esc_url($homeLink) . '">' . $home . '</a> ' . $delimiter . ' ';
            }

            if ( is_category() ) {
                $thisCat = get_category(get_query_var('cat'), false);
                if ($thisCat->parent != 0) echo get_category_parents($thisCat->parent, TRUE, ' ' . $delimiter . ' ');
                echo wp_kses_post($before . esc_html__('Archive by category: ', 'construction') . single_cat_title('', false) . $after);

            } elseif ( is_search() ) {
                echo wp_kses_post($before . esc_html__('Search results for: ', 'construction') . get_search_query() . $after);

            } elseif ( is_day() ) {
                echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F').' '. get_the_time('Y') . '</a> ' . $delimiter . ' ';
                echo wp_kses_post($before . get_the_time('d') . $after);

            } elseif ( is_month() ) {
                echo wp_kses_post($before . get_the_time('F'). ' '. get_the_time('Y') . $after);

            } elseif ( is_single() && !is_attachment() ) {
            if ( get_post_type() != 'post' ) {
                if(get_post_type() == 'portfolio'){
                    $terms = get_the_terms(get_the_ID(), 'portfolio_category', '' , '' );
                    if($terms) {
                        the_terms(get_the_ID(), 'portfolio_category', '' , ', ' );
                        echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
                    }else{
                        echo wp_kses_post($before . get_the_title() . $after);
                    }
                }elseif(get_post_type() == 'recipe'){
                    $terms = get_the_terms(get_the_ID(), 'recipe_category', '' , '' );
                    if($terms) {
                        the_terms(get_the_ID(), 'recipe_category', '' , ', ' );
                        echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
                    }else{
                        echo wp_kses_post($before . get_the_title() . $after);
                    }
                }elseif(get_post_type() == 'produce'){
                    $terms = get_the_terms(get_the_ID(), 'produce_category', '' , '' );
                    if($terms) {
                        the_terms(get_the_ID(), 'produce_category', '' , ', ' );
                        echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
                    }else{
                        echo wp_kses_post($before . get_the_title() . $after);
                    }
                }elseif(get_post_type() == 'team'){
                    $terms = get_the_terms(get_the_ID(), 'team_category', '' , '' );
                    if($terms) {
                        the_terms(get_the_ID(), 'team_category', '' , ', ' );
                        echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
                    }else{
                        echo wp_kses_post($before . get_the_title() . $after);
                    }
                }elseif(get_post_type() == 'testimonial'){
                    $terms = get_the_terms(get_the_ID(), 'testimonial_category', '' , '' );
                    if($terms) {
                        the_terms(get_the_ID(), 'testimonial_category', '' , ', ' );
                        echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
                    }else{
                        echo wp_kses_post($before . get_the_title() . $after);
                    }
                }elseif(get_post_type() == 'myclients'){
                    $terms = get_the_terms(get_the_ID(), 'clientscategory', '' , '' );
                    if($terms) {
                        the_terms(get_the_ID(), 'clientscategory', '' , ', ' );
                        echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
                    }else{
                        echo wp_kses_post($before . get_the_title() . $after);
                    }
                }elseif(get_post_type() == 'product'){
                    $terms = get_the_terms(get_the_ID(), 'product_cat', '' , '' );
                    if($terms) {
                        the_terms(get_the_ID(), 'product_cat', '' , ', ' );
                        echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
                    }else{
                        if( function_exists('is_shop') && is_shop() ){
                            echo wp_kses_post($before . get_post_field( 'post_title', get_option( 'woocommerce_shop_page_id' ) ) . $after);
                        }else{
                            echo wp_kses_post($before . get_the_title() . $after);
                        }
                    }
                }else{
                    $post_type = get_post_type_object(get_post_type());
                    $slug = $post_type->rewrite;
                    echo '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a>';
                    echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
                }

            } else {
                $cat = get_the_category(); $cat = $cat[0];
                $cats = get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
                echo wp_kses_post($cats);
                echo wp_kses_post($before . get_the_title() . $after);
            }

            } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
                $post_type = get_post_type_object(get_post_type());
                if($post_type) echo wp_kses_post($before . $post_type->labels->singular_name . $after);
            } elseif ( is_attachment() ) {
                $parent = get_post($post->post_parent);
                $cat = get_the_category($parent->ID); $cat = $cat[0];
                echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
                echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a>';
                echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
            } elseif ( is_page() && !$post->post_parent ) {
                echo wp_kses_post($before . get_the_title() . $after);

            } elseif ( is_page() && $post->post_parent ) {
                $parent_id  = $post->post_parent;
                $breadcrumbs = array();
                while ($parent_id) {
                    $page = get_page($parent_id);
                    $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
                    $parent_id = $page->post_parent;
                }
                $breadcrumbs = array_reverse($breadcrumbs);
                for ($i = 0; $i < count($breadcrumbs); $i++) {
                    echo wp_kses_post($breadcrumbs[$i]);
                    if ($i != count($breadcrumbs) - 1)
                        echo ' ' . $delimiter . ' ';
                }
                echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;

            } elseif ( is_tag() ) {
                echo wp_kses_post($before . esc_html__( 'Posts tagged: ', 'construction' ) . single_tag_title('', false) . $after);
            } elseif ( is_author() ) {
                global $author;
                $userdata = get_userdata($author);
                echo wp_kses_post( $before . esc_html__( 'Articles posted by ', 'construction' ) . $userdata->display_name . $after );
            } elseif ( is_404() ) {
                echo wp_kses_post($before . esc_html__( 'Error 404', 'construction' ) . $after);
            }

            if ( get_query_var('paged') ) {
                if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
                    echo ' '.$delimiter.' '.__('Page', 'construction') . ' ' . get_query_var('paged');
                if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
            }
                
            return ob_get_clean();
    }
}

/* Title Blog Bar */
if ( ! function_exists( 'construction_title_bar' ) ) {
	function construction_title_bar() {
        if( apply_filters( 'construction-show-title-bar', construction_get_object_option('show-title-bar', true) ) ){
            $show_page_breadcrumb = construction_get_option('show_page_breadcrumb') ? construction_get_option('show_page_breadcrumb') : 0;

    		$class = 'page-name bg-s color-w';
            $bg = false;
            if( is_singular() ){
                $bg = construction_get_meta( get_queried_object_id(), 'title-bar-img' );
                if( empty( $bg ) ){
                    if(  has_post_thumbnail() ){
                        $bg = wp_get_attachment_url( get_post_thumbnail_id(get_queried_object_id() ) );
                    }else{
                        $bg = construction_get_option('title-bar-img',false, true);
                    }
                }
            }else{
                $bg = construction_get_object_option('title-bar-img', false, true);
            }

    		?>
                <section class="<?php echo esc_attr( $class );?>" <?php if( ! empty( $bg ) ) echo 'style="background-image: url('. esc_url($bg) .')"';?> >
                    <div class="container">
                        <!-- Page-name -->
                        <div class="heading animated fadeInDown">
                            <h1><?php construction_page_title();?></h1>
                            <?php if( $seo_description = construction_get_object_option('seo_description')  ){
                                echo apply_filters( 'the_content', $seo_description );
                            }; ?>
                        </div>
                    </div>
                </section>

                <!-- Breadku -->
                <?php
                if( apply_filters('construction-show-page-breadcrumb', construction_get_option('show_page_breadcrumb') ) ){
                    $delimiter = construction_get_option('page_breadcrumb_delimiter') ? ' '.construction_get_option('page_breadcrumb_delimiter') .' ' : ' / ';
                            ?>
                <div class="container hidden-xs">
                    <div class="breadc pull-left">
                        <?php echo construction_page_breadcrumb($delimiter); ?>
                    </div>
                </div>
                <?php };?>
                <!-- Project -->

    		<?php
        }
	}
}

function construction_get_object_option( $id, $default=false, $media=false ){
    return construction_get_page_id( $id, $default, $media );    
}

function construction_get_page_id( $id, $default, $media ){

    if( is_archive() || is_search() ){
        $result = construction_get_option( $id, $default, $media );
        if( $result !== false ){
            return $result;
        }else{
            $post_id = get_option( 'page_on_front' );
        }
    }else{
        if( is_page() || apply_filters('construction-condition-get-option', false)){
            $post_id = get_queried_object_id();
        }elseif( is_404() ){
            $post_id = construction_get_option('404-page');
        }else{
            return construction_get_option( $id, $default, $media );
        }
    }
    return construction_get_value_option( $post_id, $id, $default, $media );
}

function construction_get_value_option( $obj_id, $id,  $default, $media ){
    $result = construction_get_meta( $obj_id , $id );
    
    if( $result === false || $result === "" || $result==='global' ){
        if( $result = construction_get_option( $id, $default, $media ) ){
            return $result;
        }
        return $default;
    }

    return $result;
}

function construction_join( $arr ){
    return esc_attr( implode(' ', $arr) );
}

function construction_get_style_color( $class, $color, $color_class="default-color", $style="color:" ){
    if( ! empty( $color ) ){
        if( $color === construction_get_option('maincolor') ){
            $class[] = $color_class;
        }else{
            $class[] = join( array('" style="', $style, esc_attr($color) ) );
        }
    }
    return $class;
}

function construction_get_animation( &$class, $animate='fadeInUp', $duration='0.8s', $delay='0.8s' ){
    $wrap_attrs = array();
    if( empty( $animate ) ) return $wrap_attrs;
    // added animated wow
    $class[] = 'wow';

    $class[] = $animate;

    if( ! empty( $duration ) ){
        $wrap_attrs[] = 'data-wow-duration="'. esc_attr( $duration ) .'"';
    }

    if( ! empty( $delay ) ){
        $wrap_attrs[] = 'data-wow-delay="'. esc_attr( $delay ) .'"';
    }

    return $wrap_attrs;
}


/* Header */
function construction_header() {
  $header_layout = construction_get_object_option('header_type', true);
  switch ($header_layout) {
    case 'v2':
      get_template_part('framework/headers/header', 'v2');
      break;
    default :
      get_template_part('framework/headers/header', 'v1');
      break;
  }
}

function construction_portfolio_style( $style ){
    global $post;
    if( get_post_format( $post->ID ) === 'video' ){
        $style = 'video';
    }else{
        $style = (int)construction_get_meta( $post->ID, 'style' );
        $style = ( $style < 8 && $style > 0) ? $style : 1;
    }
    return $style;
}

add_action('wp_ajax_construction_render_portfolio', 'construction_render_portfolio');

add_action('wp_ajax_nopriv_construction_render_portfolio', 'construction_render_portfolio');

function construction_render_portfolio()
{
    $data = $_POST['data'];
    if( empty( $data['params'] ) || empty( $data['next-page']) ){
      wp_send_json_error();
    }

    $params = wp_parse_args( $data['params'], array(
        'order' => '',
        'orderby' => '',
        'posts_per_page' => -1
    ) );

    extract( $params );
    $paged = ($data['next-page']) > 0 ? (int) $data['next-page'] : 2;
    $args = array(
        'post_type'      => 'portfolio',
        'paged'          => $paged,
        'posts_per_page' => $posts_per_page,
        'post_status'    => 'publish',
    );
    $p = new WP_Query($args);

    ob_start();
    while ($p->have_posts()) {
        $p->the_post();
        $terms = get_the_terms(get_the_ID(), 'portfolio_type');
        $cats = array();
        if (!empty($terms) && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                $cats[] = $term->name;
            }
        }
        if (has_post_thumbnail()){
        ?>
        <div class="portfolio-item appended <?php echo esc_attr( strtolower(implode(' ', $cats ) ) );?>">
            <a href="<?php the_permalink(); ?>">
            <?php $img = wpb_getImageBySize( array(
              'attach_id' => get_post_thumbnail_id( get_the_ID() ),
              'thumb_size' => $thumb_size,
              'class' => 'img-responsive',
            ) );
              if( $img ) echo do_shortcode($img['thumbnail']);
            ?>
            </a>
            <div class="portfolio-item-caption">
            <h2 class="portfolio-item-title"><a href="<?php the_permalink();?>"><?php the_title(); ?></a></h2>
            <?php if( has_term('', 'portfolio_type', get_the_ID()) ):?>
                  <div class="portfolio-item-cats">
              <?php the_terms( get_the_ID(), 'portfolio_type', '<span>', '</span> <span>', '</span>');?>
            </div>
                  <?php endif;?>

            </div>
        </div>
    <?php }
    }
    wp_send_json(ob_get_clean());

}

function construction_project_taxonomy( $query ) {
    if ( is_admin() || ! $query->is_main_query() )
        return;
    if( is_post_type_archive( 'project' ) || is_tax('project_type') ){
        $query->set( 'posts_per_page', construction_get_option('pj-no-items', 6) );
    }
    if( is_post_type_archive( 'service' ) || is_tax('service-type') ){
        $query->set( 'posts_per_page', construction_get_option('sv-no-items', 6) );
    }
}
add_action( 'pre_get_posts', 'construction_project_taxonomy' );


add_action('wp_ajax_construction_render_blog', 'construction_render_blog');

add_action('wp_ajax_nopriv_construction_render_blog', 'construction_render_blog');

function construction_render_blog()
{
    $data = $_POST['data'];
    if( empty( $data['params'] ) || empty( $data['next-page']) ){
      wp_send_json_error();
    }

    $params = wp_parse_args( $data['params'], array(
        'order' => '',
        'orderby' => '',
        'posts_per_page' => -1
    ) );

    extract( $params );
    $paged = ($data['next-page']) > 0 ? (int) $data['next-page'] : 2;
    $args = array(
        'paged'          => $paged,
        'posts_per_page' => $posts_per_page,
        'post_status'    => 'publish',
        'order' => $order,
        'orderby' => $orderby,
        'tpl' => ''
    );
    $p = new WP_Query($args);

    $tpl = empty( $tpl ) ? '' : '-'. $tpl;

    ob_start();
    while ($p->have_posts()) {
        $p->the_post();
        $post_format = get_post_format();
        $post_format = empty( $post_frmat ) ? '' : '-'. $post_format;
        require CONSTRUCTION_ABS_PATH.'/partials/blog/content'. $tpl . $post_format .'.php';
    }
    wp_reset_postdata();
    wp_send_json(ob_get_clean());

}

function construction_get_button_class( $class, $el_class ){
    if( strpos( $el_class, 'm-btn-border-black') !== false ){
        $class[] = str_replace('m-btn-border-black','m-btn-border', $el_class );
    }else{
        if( strpos( $el_class, 'm-btn-white') === false ){
            $class[] = 'm-btn-default';
        }
        $class[] = $el_class;
    }
    return $class;
}


function construction_get_inline_css(){
    $main_color = construction_get_option('main-color','#ffe000');
    $custom_css ="
        .btn-bg-1,.btn-bg-2:hover,.btn-bg-2:focus,.btn-bg-2:active,.service-tabs li.active a,.service-tabs li.active a:hover,.service-tabs li.active a:focus,.service-tabs li a:hover,a.view-more-project:hover,.block-fv,.fv .phone i,.newsletter-1,.atv .down_brc:hover,.project-tabs li.active a,.project-tabs li.active a:hover,.project-tabs li.active a:focus,.project-tabs li a:hover,.bg-yellow,.widget_search form button{
           background-color: {$main_color};
        }
        .ui-nav-tabs.vc_tta-color-grey.vc_tta-style-classic .vc_tta-tab.vc_active>a{
            background-color: {$main_color}!important;
        }
        .project-widget .menu li.current-menu-item a,.project-widget .menu li.current-menu-item a > i,.top-menu ul li a:hover,ul.nav li.dropdown:hover ul.dropdown-menuli a:hover,.navbar-default .navbar-nav > li > a:hover,.navbar-default .navbar-nav>.open>a,.navbar-default .navbar-nav>.open>a:hover,.navbar-default .navbar-navli a.active,.navbar-default .navbar-nav .open .dropdown-menu>li>a:hover,.navbar-default .navbar-nav .open .dropdown-menu>li>a:active,.navbar-default .navbar-nav .open .dropdown-menu>li>a:focus,.btn-bg-3:hover,.btn-bg-3:focus,.btn-bg-3:active,.btn-bg-4:hover,.btn-bg-4:focus,.btn-bg-4:active,.block-slideshow .caption-item h1 strong,.ser-category h3 a:hover,.ser-category h3 a:active,.ser-category h3 a:focus,.owl-theme .owl-controls .owl-buttonsdiv:hover,.counterup h3,.wcu-item i,.testimonials .testtim-item .heading-4,.about .panel-title a,.about .panel-title a:hover,.atv .cons_free,.project-widget .menu li a:hover,.project-widget .menu li a:hover > i,.blog-item .blog-caption .blog-heading a:hover,.share-post ul li a:hover,.pag-nav li span:hover,.pag-nav li .current,.pag-nav li a:hover,.pag-nav li a:active,.pag-nav li a:focus,.pag-nav lii:hover,.list-comment .comment-item .auth .comment-heading h5 a:hover,.list-comment .comment-item .auth .comment-heading h5 a:focus,.list-comment .comment-item .auth .comment-heading h5 a:active,.contact-info ul.list-default li i,.footer-bt p a,.flexslider .flex-direction-nav a:hover{
            color: {$main_color};
        }
        .footer-top .tagcloud a:hover,.footer-top .list-default li a:hover,.footer-top.social_link a:hover{
            color:{$main_color} !important;
        }

        @media(max-width:767px){
            .main-menu .navbar-default .navbar-nav .open > a:hover{
                color: {$main_color};
            }
        }
        .block-slideshow.caption-item .heading::after,.page-name .heading::after,.wcu-item .wcu-item-inner::before,.wcu-item .wcu-item-inner::after,.about .panel-default>.panel-heading .panel-title,.wrap-footer .footer-heading h3,.custom-accordion.vc_tta.vc_general .vc_tta-panel-title,.custom-accordion.vc_tta-color-grey.vc_tta-style-flat .vc_tta-panel .vc_tta-panel-title .vc_tta-title-text:before{
            border-color: {$main_color};
        }
        .service-tabs li.active a::after, .service-tabs li a:hover::after{
            border-left-color: {$main_color};
        }
        .fv .phone::after{
            border-right-color: {$main_color};
            border-bottom-color: {$main_color};
        }
    ";
    $border_color = construction_get_object_option('border_box_color');
    if( ! empty( $border_color ) ){
        $custom_css .="
            .border-body .border-box-style{
                background-color: $border_color;
            }
        ";
    }
    return $custom_css;
}

function construction_header_transparent( $header_class ){
    $header_class[] = 'transparent';
    return $header_class;
}

function construction_condition_get_option(){
    if( is_singular('portfolio') ){
        return true;
    }
}

function construction_custom_subpage_link( $link ){
    return home_url("/$link");
}

function construction_header_type( $header_class ){
    $header_class[] = construction_get_object_option('header-color');
    return $header_class;
}

function construction_custom_menu_icon_default(){
    return '';
}

add_action( 'template_redirect', 'construction_is_404page', 20 );
function construction_is_404page(){
    if( ! is_404() ) return;
    $args = [
        'post_type' => 'page',
        'fields' => 'ids',
        'nopaging' => true,
        'meta_key' => '_wp_page_template',
        'meta_value' => '404.php',
        'posts_per_page' => 1
    ];  
    $pages = get_posts( $args );
    if( isset( $pages[0] ) ){
        construction_update_option('404-page', $pages[0] );
    }
}



?>