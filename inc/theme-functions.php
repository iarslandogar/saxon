<?php
/**
 * Theme Functions
 */

/**
 * Header logo display
 */
if(!function_exists('saxon_logo_display')):
function saxon_logo_display() {

    $menu = wp_nav_menu(
        array (
            'theme_location'  => 'main',
            'echo' => false,
            'fallback_cb'    => true,
        )
    );

    if (!empty($menu)):
    ?>
    <div class="mainmenu-mobile-toggle" aria-label="<?php echo esc_attr__('Toggle menu', 'saxon'); ?>"><i class="fa fa-bars" aria-hidden="true"></i></div>
    <?php endif; ?>
    <?php
    // Header tagline style
    $tag_line_style = get_theme_mod('header_tagline_style', 'regular');

    // Text logo
    if ( get_theme_mod( 'logo_text', false ) && (get_theme_mod( 'logo_text_title', '' ) !== '') ) {
        ?>
        <div class="logo"><a class="logo-link logo-text" href="<?php echo esc_url(home_url()); ?>"><?php echo html_entity_decode(wp_kses_post(get_theme_mod( 'logo_text_title', true )));?></a>
        <?php
          if(get_bloginfo('description')!=='' && get_theme_mod( 'header_tagline', false ) ) {
            echo '<div class="header-blog-info header-blog-info-'.esc_attr($tag_line_style).'">';
            bloginfo('description');
            echo '</div>';
          }
        ?>
        </div>
        <?php
    // Image logo
    } else {
        ?>
        <div class="logo">
        <a class="logo-link" href="<?php echo esc_url(home_url('/')); ?>"><img src="<?php echo esc_url(get_header_image()); ?>" alt="<?php bloginfo('name'); ?>" class="regular-logo"><img src="<?php if ( get_theme_mod( 'saxon_header_transparent_logo' ) ) { echo esc_url( get_theme_mod( 'saxon_header_transparent_logo' )); } else { echo esc_url(get_header_image()); }  ?>" alt="<?php bloginfo('name'); ?>" class="light-logo"></a>
        <?php
          if(get_bloginfo('description') !== '' && get_theme_mod( 'header_tagline', false ) ) {
            echo '<div class="header-blog-info header-blog-info-'.esc_attr($tag_line_style).'">';
            bloginfo('description');
            echo '</div>';
          }
        ?>
        </div>
        <?php
    }

    ?>

    <?php
}
endif;

/**
 * Main Menu display
 */
if(!function_exists('saxon_mainmenu_display')):
function saxon_mainmenu_display() {

    // MainMenu styles
    $menu_add_class = '';

    $menu_add_class .= ' mainmenu-'.esc_html(get_theme_mod('mainmenu_font_decoration', 'none'));

    $menu_add_class .= ' mainmenu-'.esc_html(get_theme_mod('mainmenu_font_weight', 'regularfont'));

    $menu_add_class .= ' mainmenu-'.esc_html(get_theme_mod('mainmenu_arrow_style', 'noarrow'));

    ?>

    <?php
    // Main Menu

    $menu = wp_nav_menu(
        array (
            'theme_location'  => 'main',
            'echo' => false,
            'fallback_cb'    => true,
        )
    );

    if(get_theme_mod('module_mega_menu', true)) {
         $add_class = ' mgt-mega-menu';
         $menu_walker = new Saxon_Megamenu_Walker;
    } else {
         $add_class = '';
         $menu_walker = new Saxon_Mainmenu_Walker;
    }

    if (!empty($menu)):

    ?>
    <div class="mainmenu<?php echo esc_attr($menu_add_class); ?> clearfix" role="navigation">

        <div id="navbar" class="navbar navbar-default clearfix<?php echo esc_attr($add_class);?>">

          <div class="navbar-inner">
              <div class="container">

                  <div class="navbar-toggle btn btn-grey" data-toggle="collapse" data-target=".collapse">
                    <?php esc_html_e( 'Menu', 'saxon' ); ?>
                  </div>

                  <div class="navbar-center-wrapper">
                  <?php

                     wp_nav_menu(array(
                      'theme_location'  => 'main',
                      'container_class' => 'navbar-collapse collapse',
                      'menu_class'      => 'nav',
                      'fallback_cb'    => true,
                      'walker'          => $menu_walker
                      ));

                  ?>
                  </div>

              </div>
          </div>

        </div>

    </div>
    <?php endif; ?>

    <?php
    // Site Footer Banner
    saxon_banner_display('inside_header');
    ?>

    <?php
    // MainMenu Below header position END
}
endif;

/**
 * Social icons display
 */
if(!function_exists('saxon_social_display')):
function saxon_social_display($showtitles = false, $addwrapper = false) {

    $social_services_list = saxon_social_services_list();

    $s_count = 0;

    $social_services_html = '';

    $social_icons = get_theme_mod('social_icons', array());

    foreach( $social_icons as $social_icon ) {

        $social_type = $social_icon['social_type'];
        $social_url = $social_icon['social_url'];

        if($showtitles) {
            $social_title = '<span>'.$social_services_list[$social_type].'</span>';
        } else {
            $social_title = '';
        }

        $social_services_html .= '<a href="'.esc_url($social_url).'" target="_blank" class="a-'.esc_attr($social_type).'"><i class="fa fa-'.esc_attr($social_type).'"></i>'.wp_kses_post($social_title).'</a>';
    }

    if($social_services_html !== '') {
        if($addwrapper) {
            echo wp_kses_post('<div class="social-icons-wrapper">'.$social_services_html.'</div>');
        } else {
            echo wp_kses_post($social_services_html);
        }
    }
}
endif;

/**
 * Top menu display
 */
if(!function_exists('saxon_top_display')):
function saxon_top_display() {
    ?>
    <?php if(get_theme_mod('topmenu_disable', false) == false): ?>
    <?php
    $header_add_class = '';

    $header_top_menu_style = get_theme_mod('topmenu_style', 'menu_black');
    $header_add_class .= ' '.esc_attr($header_top_menu_style);

    $header_top_menu_uppercase = get_theme_mod('topmenu_uppercase', 'uppercase');
    $header_add_class .= ' header-menu-'.esc_attr($header_top_menu_uppercase);

    $topmenu = wp_nav_menu(
        array (
            'theme_location'  => 'top',
            'echo' => false,
            'fallback_cb'    => false,
        )
    );

    if (!empty($topmenu)):

    ?>
    <div class="header-menu-bg<?php echo esc_attr($header_add_class); ?>" role="navigation">
      <div class="header-menu">
        <div class="container">
          <div class="row">
            <div class="col-md-12">
              <div class="menu-top-menu-container-toggle" aria-label="<?php echo esc_attr__('Toggle menu', 'saxon'); ?>"></div>
              <?php
              wp_nav_menu(array(
                'theme_location'  => 'top',
                'menu_class'      => 'links',
                'fallback_cb'    => false,
                ));
              ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>
    <?php endif;
}
endif;

/**
 * Top line display
 */
if(!function_exists('saxon_topline_display')):
function saxon_topline_display() {

  if(get_theme_mod('header_topline', false)) {

    $header_topline_content = get_theme_mod('header_topline_content', '');

    if(get_theme_mod('header_topline_button_title', '') !== '') {
      if(get_theme_mod('header_topline_button_blank', true)) {
        $button_target = ' target="_blank"';
      } else {
        $button_target = '';
      }

      $header_topline_button_html = '<div class="header-topline-button"><a href="'.esc_url(get_theme_mod('header_topline_button_url', '')).'" class="btn btn-small btn-transparent"'.esc_html($button_target).'>'.esc_html(get_theme_mod('header_topline_button_title', '')).'</a></div>';
    } else {
      $header_topline_button_html = '';
    }

    echo '<div class="header-topline-wrapper">
      <div class="container"><div class="row"><div class="col-md-12"><div class="header-topline"><div class="header-topline-content">
      '.do_shortcode(wp_kses_post($header_topline_content)).'</div>'.wp_kses_post($header_topline_button_html).'
      </div></div></div></div>
    </div>';

  }

}
endif;

/**
 * Header left part display
 */
if(!function_exists('saxon_header_left_display')):
function saxon_header_left_display() {

    // Show header logo
    saxon_logo_display();
}
endif;

/**
 * Header center part display
 */
if(!function_exists('saxon_header_center_display')):
function saxon_header_center_display() {

    // Show main menu
    saxon_mainmenu_display();
}
endif;

/**
 * Header right part display
 */
if(!function_exists('saxon_header_right_display')):
function saxon_header_right_display() {

     // Show social
    if(get_theme_mod('header_socialicons', true) == true) {
        saxon_social_display(false, true);
    }

    // Header search
    if(get_theme_mod('search_position', 'header') !== 'disable'):
    ?>
    <div class="search-toggle-wrapper search-<?php echo esc_attr(get_theme_mod('search_position', 'header')); ?>">
      <?php get_template_part( 'searchform-block' ); ?>
      <a class="search-toggle-btn" aria-label="<?php echo esc_attr__('Search toggle', 'saxon'); ?>"><i class="fa fa-search" aria-hidden="true"></i></a>
    </div>

    <?php endif;
}
endif;

/**
 * Homepage featured posts slider display
 */
if(!function_exists('saxon_blog_slider_display')):
function saxon_blog_slider_display() {

    if(get_theme_mod('slider_enable', false) == true): ?>

    <div class="saxon-blog-posts-slider">
    <?php

    // Custom slider
    if(get_theme_mod('slider_custom', false) == true) {
        echo '<div class="saxon-custom-slider">'.do_shortcode(get_theme_mod('slider_custom_shortcode', '')).'</div>';
    } else {
    // Theme slider

        $settings['block_posts_limit'] = get_theme_mod('slider_limit', 30);
        $settings['block_posts_type'] = get_theme_mod('slider_posts_type', 'featured');
        $settings['block_categories'] = get_theme_mod('slider_categories', '');

        $args = saxon_get_wpquery_args($settings);

        $posts = get_posts( $args );

        $total_posts = count($posts);

        if($total_posts > 0) {

          $slider_autoplay = get_theme_mod('slider_autoplay', '0');

          if($slider_autoplay > 0) {
              $slider_autoplay_bool = 'true';
          } else {
              $slider_autoplay_bool = 'false';
          }

          $slider_arrows = get_theme_mod('slider_arrows', false);

          if($slider_arrows == true) {
              $slider_arrows = 'true';
          } else {
              $slider_arrows = 'false';
          }

          echo '<div class="owl-carousel">';

          $i = 0;

          foreach($posts as $post) {

              setup_postdata($post);

              $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'saxon-blog-thumb');

              if(has_post_thumbnail( $post->ID )) {
                  $image_bg = 'background-image: url('.$image[0].');';
              }
              else {
                  $image_bg = '';
              }

              $categories_list = saxon_get_the_category_list( $post->ID );

              echo '<div class="saxon-post saxon-post-invert">';
              echo '<div class="saxon-post-image" data-style="'.esc_attr($image_bg).'"></div>';
              echo '<div class="saxon-post-image-bg"></div>';

              // Post details
              echo '
              <div class="saxon-post-details-container container">
              <div class="row">
              <div class="col-md-12">';

              echo '<div class="saxon-post-details">

                   <div class="post-categories">'.wp_kses($categories_list, saxon_esc_data()).'</div>
                   <h3 class="post-title"><a href="'.esc_url(get_permalink($post->ID)).'">'.esc_html($post->post_title).'</a></h3>';

              echo '<div class="saxon-post-info">';

              if(get_theme_mod('blog_posts_author', false)):
              ?>
                <div class="post-author">
                  <div class="post-author-image">
                      <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ))); ?>"><?php echo get_avatar( get_the_author_meta('email'), '28', '28' ); ?></a>
                  </div><?php echo esc_html__('By', 'saxon'); ?> <?php echo get_the_author_posts_link(); ?>
                </div>
              <?php
              endif;

              echo '<div class="post-date">'.get_the_time( get_option( 'date_format' ), $post->ID ).'</div>';

              echo '</div>';//end saxon-post-info

              echo '</div>';//end saxon-post-details


              echo '</div>';
              echo '</div>';
              echo '</div>';//end - saxon-post-details-container
              // END - Post details

              echo '</div>';//end saxon-post

              $i++;

          }

          wp_reset_postdata();

          if($total_posts > 1) {

              wp_add_inline_script( 'saxon-script', '(function($){
              $(document).ready(function() {
                  "use strict";

                  var owlpostslider = $(".saxon-blog-posts-slider .owl-carousel").owlCarousel({
                      loop: true,
                      items: 1,
                      autoplay:'.esc_js($slider_autoplay_bool).',
                      autowidth: false,
                      autoplayTimeout:'.esc_js($slider_autoplay).',
                      autoplaySpeed: 1000,
                      navSpeed: 1000,
                      nav: '.esc_js($slider_arrows).',
                      dots: false,
                      navText: false,
                      responsive: {
                          1199:{
                              items:1
                          },
                          979:{
                              items:1
                          },
                          768:{
                              items:1
                          },
                          479:{
                              items:1
                          },
                          0:{
                              items:1
                          }
                      }
                  });

                  AOS.refresh();

              });})(jQuery);');

            } else {
                wp_add_inline_script( 'saxon-script', '(function($){
                  $(document).ready(function() {
                     "use strict";

                     $(".saxon-blog-posts-slider .owl-carousel").show();

                     AOS.refresh();

                  });})(jQuery);');
            }
        }
    }

    ?>

        </div>
    </div>
    <?php endif;

}
endif;

/**
 * Ads banner display
 */
if(!function_exists('saxon_banner_display')):
function saxon_banner_display($banner_id) {

    $banner_option_name = 'banner_'.$banner_id;
    $banner_content_name = 'banner_'.$banner_id.'_content';

    if(get_theme_mod($banner_option_name, false) == true) {

        echo '<div class="saxon-bb-block saxon-bb-block-'.$banner_id.' clearfix">';
        echo do_shortcode(get_theme_mod($banner_content_name, '')); // This is safe place and we can't use wp_kses_post or other esc_ functions here because this is ads area where user may use Google Adsense and other Java Script banners code with <script> inside.
        echo '</div>';
    }

}
endif;

/**
 * Footer shortcode block display
 */
if(!function_exists('saxon_footer_shortcode_display')):
function saxon_footer_shortcode_display() {

  if(get_theme_mod('footer_shortcodeblock', false) == true):
  ?>
  <div class="container">
    <div class="footer-shortcode-block">
    <?php echo do_shortcode(get_theme_mod('footer_shortcodeblock_html', '')); ?>
    </div>
  </div>
  <?php
  endif;
}
endif;

/**
 * Footer HTML block display
 */
if(!function_exists('saxon_footer_htmlblock_display')):
function saxon_footer_htmlblock_display() {

  if(get_theme_mod('footer_htmlblock', false) == true) {

    $footer_htmlblock_background = get_theme_mod('footer_htmlblock_background', false);

    $style = 'background-color: '.esc_html($footer_htmlblock_background['background-color']).';';
    $style .= 'color: '.esc_html(get_theme_mod('footer_htmlblock_color_text', '#ffffff')).';';

    if($footer_htmlblock_background['background-image'] !== '') {
      $style .= 'background-image: url('.esc_url($footer_htmlblock_background['background-image']).');';
      $style .= 'background-repeat: '.esc_html($footer_htmlblock_background['background-repeat']).';';
      $style .= 'background-position: '.esc_html($footer_htmlblock_background['background-position']).';';
      $style .= 'background-size: '.esc_html($footer_htmlblock_background['background-size']).';';
      $style .= 'background-attachement: '.esc_html($footer_htmlblock_background['background-size']).';';
    }

    ?>
    <div class="footer-html-block" data-style="<?php echo esc_attr($style); ?>">
    <?php echo do_shortcode(get_theme_mod('footer_htmlblock_html', '')); ?>
    </div>
    <?php

  }
}
endif;

/**
 *  Blog post excerpt display override
 */
if(!function_exists('saxon_excerpt_more')):
function saxon_excerpt_more( $more ) {
    return '...';
}
endif;
add_filter('excerpt_more', 'saxon_excerpt_more');

/**
 *  Blog post read more display override
 */
if(!function_exists('saxon_modify_read_more_link')):
function saxon_modify_read_more_link() {
    return '<a class="more-link btn btn-grey" href="' . esc_url(get_permalink()) . '">'.esc_html__('Read more', 'saxon').'</a>';
}
endif;
add_filter( 'the_content_more_link', 'saxon_modify_read_more_link' );

/**
 *  Custom BODY CSS classes
 */
if(!function_exists('saxon_body_classes')):
function saxon_body_classes($classes) {

  // Single Post page related classes
  $blog_post_transparent_header = get_theme_mod('blog_post_transparent_header', false);

  // Demo settings
  if ( defined('DEMO_MODE') && isset($_GET['blog_post_transparent_header']) ) {
    if($_GET['blog_post_transparent_header'] == 0) {
      $blog_post_transparent_header = false;
    }
    if($_GET['blog_post_transparent_header'] == 1) {
      $blog_post_transparent_header = true;
    }
  }

  if($blog_post_transparent_header) {
    $classes[] = 'blog-post-transparent-header-enable';
  } else {
    $classes[] = 'blog-post-transparent-header-disable';
  }

  $blog_post_smallwidth = get_theme_mod('blog_post_smallwidth', false);

  // Demo settings
  if ( defined('DEMO_MODE') && isset($_GET['blog_post_smallwidth']) ) {
    if($_GET['blog_post_smallwidth'] == 0) {
      $blog_post_smallwidth = false;
    }
    if($_GET['blog_post_smallwidth'] == 1) {
      $blog_post_smallwidth = true;
    }
  }

  if($blog_post_smallwidth) {
    $classes[] = 'blog-small-page-width';
  }

  // Slider related classes
  $blog_enable_homepage_slider = get_theme_mod('slider_enable', true);

  if($blog_enable_homepage_slider) {

    $classes[] = 'blog-slider-enable';

  } else {
    $classes[] = 'blog-slider-disable';
  }

  if(get_theme_mod('blog_post_dropcaps', false)) {
    $classes[] = 'blog-enable-dropcaps';
  }

  if(get_theme_mod('animations_images', true)) {
    $classes[] = 'blog-enable-images-animations';
  }

  if(get_theme_mod('sidebar_sticky', false)) {
    $classes[] = 'blog-enable-sticky-sidebar';
  }

  if(get_theme_mod('header_sticky', true)) {
    $classes[] = 'blog-enable-sticky-header';
  }

  $style_corners = get_theme_mod('style_corners', 'rounded');

  if($style_corners !== '') {
    $classes[] = 'blog-style-corners-'.esc_attr($style_corners);
  }

  return $classes;
}
endif;
add_filter('body_class', 'saxon_body_classes');

/**
 * CMB2 images file list display
 *
 * @param  string  $file_list_meta_key The field meta key. ('wiki_test_file_list')
 * @param  string  $img_size           Size of image to show
 */
if(!function_exists('saxon_cmb2_get_images_src')):
function saxon_cmb2_get_images_src( $post_id, $file_list_meta_key, $img_size = 'medium' ) {

    // Get the list of files
    $files = get_post_meta( $post_id, $file_list_meta_key, 1 );

    $attachments_image_urls_array = Array();

    foreach ( (array) $files as $attachment_id => $attachment_url ) {

        $current_attach = wp_get_attachment_image_src( $attachment_id, $img_size );

        $attachments_image_urls_array[] = $current_attach[0];

    }

    if($attachments_image_urls_array[0] == '') {
        $attachments_image_urls_array = array();
    }

    return $attachments_image_urls_array;

}
endif;


/**
 * Add on scroll animations to elements
 */
if(!function_exists('saxon_add_aos')):
function saxon_add_aos($echo = true) {

    $aos_animation = get_theme_mod('aos_animation', '');

    if($aos_animation !== '') {

        $blog_layout = get_theme_mod('blog_layout', 'standard');

        if ( defined('DEMO_MODE') && isset($_GET['blog_layout']) ) {
            $blog_layout = $_GET['blog_layout'];
        }

        // Masonry layout does not supported
        if($blog_layout == 'masonry') {
            $data_params = '';
        } else {
            $data_params = ' data-aos="'.esc_attr($aos_animation).'"';
        }

        if($echo) {
            echo wp_kses_post($data_params);
        } else {
            return wp_kses_post($data_params);
        }

    }
}
endif;

/**
 *  Get correct CSS styles for Google fonts variant
 */
if(!function_exists('saxon_get_font_style_css')):
function saxon_get_font_style_css($variant) {
    $font_style_css = '';

    if(strpos($variant, 'italic')) {
        $font_style_css .= 'font-style: italic;';
        $variant = str_replace('italic', '', $variant);
    }

    if($variant !== 'regular' && $variant !== '') {
        $font_style_css .= 'font-weight: '.$variant.';';
    }

    return $font_style_css;
}
endif;

/**
 * Menu Links Customization
 */
if ( !class_exists( 'Saxon_Mainmenu_Walker' ) ):
class Saxon_Mainmenu_Walker extends Walker_Nav_Menu{
      function start_el(&$output, $item, $depth = 0, $args = Array(), $current_object_id = 0 ){
           global $wp_query;
           $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
           $class_names = $value = '';
           $classes = empty( $item->classes ) ? array() : (array) $item->classes;
           $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );

           $add_class = '';

           $post = get_post($item->object_id);

               $class_names = ' class="'.$add_class.' '. esc_attr( $class_names ) . '"';
               $output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';
               $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
               $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
               $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';

                    $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

                if (is_object($args)) {
                    $item_output = $args->before;
                    $item_output .= '<a'. $attributes .'>';
                    $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID );
                    $item_output .= $args->link_after;
                    if($item->description !== '') {
                        $item_output .= '<span>'.$item->description.'</span>';
                    }

                    $item_output .= '</a>';
                    $item_output .= $args->after;
                    $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );

                }
     }
}
endif;

/**
 * Get categories list with colors
 */
if(!function_exists('saxon_get_the_category_list')):
function saxon_get_the_category_list($post_id = false) {

  $thelist = '';

  $categories = apply_filters( 'the_category_list', get_the_category( $post_id ), $post_id );

  if ( empty( $categories ) ) {
    return '<a>'.esc_html__( 'Uncategorized', 'saxon' ).'</a>';
  }

  foreach ( $categories as $category ) {

    $category_color = get_term_meta ( $category->cat_ID, '_saxon_category_color', true );

    if(isset($category_color) && ($category_color !== '')) {
        $data_style = 'background-color: '.$category_color.';';
        $thelist .= '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" data-style="'.esc_attr($data_style).'">' . esc_html($category->name).'</a>';
    } else {
        $thelist .= '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '">' . esc_html($category->name).'</a>';
    }

  }

  return $thelist;

}
endif;

/**
 * Build WP_Query args based on post type
 */
if(!function_exists('saxon_get_wpquery_args')):
function saxon_get_wpquery_args($settings = array()) {

  $posts_per_page = $settings['block_posts_limit'];
  $posts_type = $settings['block_posts_type'];

  if(!isset($settings['block_posts_offset']) || $settings['block_posts_offset'] == 0) {
    $offset = '';
  } else {
    $offset = $settings['block_posts_offset'];
  }

  if(!isset($settings['block_categories']) || $settings['block_categories'] == 0) {
    $category = '';
  } else {
    $category = $settings['block_categories'];
  }

  $args = array();

  if($posts_type == 'featured') {
      $args = array(
        'posts_per_page'   => $posts_per_page,
        'offset'           => $offset,
        'orderby'          => 'date',
        'order'            => 'DESC',
        'meta_key'         => '_saxon_post_featured',
        'meta_value'       => 'on',
        'cat'              => $category,
        'post_type'        => 'post',
        'post_status'      => 'publish',
        'ignore_sticky_posts' => true,
        'suppress_filters' => 0
      );
  }

  if($posts_type == 'latest') {
      $args = array(
        'posts_per_page'   => $posts_per_page,
        'offset'           => $offset,
        'orderby'          => 'date',
        'order'            => 'DESC',
        'cat'              => $category,
        'post_type'        => 'post',
        'post_status'      => 'publish',
        'ignore_sticky_posts' => true,
        'suppress_filters' => 0
      );
  }

  if($posts_type == 'editorspicks') {
      $args = array(
        'posts_per_page'   => $posts_per_page,
        'offset'           => $offset,
        'orderby'          => 'date',
        'order'            => 'DESC',
        'meta_key'         => '_saxon_post_editorspicks',
        'meta_value'       => 'on',
        'cat'              => $category,
        'post_type'        => 'post',
        'post_status'      => 'publish',
        'ignore_sticky_posts' => true,
        'suppress_filters' => 0
      );
  }

  if($posts_type == 'promoted') {
      $args = array(
        'posts_per_page'   => $posts_per_page,
        'offset'           => $offset,
        'orderby'          => 'date',
        'order'            => 'DESC',
        'meta_key'         => '_saxon_post_promoted',
        'meta_value'       => 'on',
        'cat'              => $category,
        'post_type'        => 'post',
        'post_status'      => 'publish',
        'ignore_sticky_posts' => true,
        'suppress_filters' => 0
      );
  }

  if($posts_type == 'popular') {
      $args = array(
        'posts_per_page'   => $posts_per_page,
        'offset'           => $offset,
        'order'            => 'DESC',
        'orderby'          => 'meta_value_num',
        'meta_key'         => '_saxon_post_views_count',
        'cat'              => $category,
        'post_type'        => 'post',
        'post_status'      => 'publish',
        'ignore_sticky_posts' => true,
        'suppress_filters' => 0
    );
  }

  if($posts_type == 'liked') {
      $args = array(
        'posts_per_page'   => $posts_per_page,
        'offset'           => $offset,
        'order'            => 'DESC',
        'orderby'          => 'meta_value_num',
        'meta_key'         => '_saxon_post_likes_count',
        'cat'              => $category,
        'post_type'        => 'post',
        'post_status'      => 'publish',
        'ignore_sticky_posts' => true,
        'suppress_filters' => 0
    );
  }

  if($posts_type == 'random') {
      $args = array(
        'posts_per_page'   => $posts_per_page,
        'offset'           => $offset,
        'order'            => 'DESC',
        'orderby'          => 'rand',
        'cat'              => $category,
        'post_type'        => 'post',
        'post_status'      => 'publish',
        'ignore_sticky_posts' => true,
        'suppress_filters' => 0
    );
  }

  return $args;

}
endif;

/*
 * Get exrcept by chars without breaked words
 */
if(!function_exists('saxon_get_the_excerpt')):
function saxon_get_the_excerpt($chars = 100, $postid = '') {

  $excerpt = '';

  if(get_theme_mod('blog_posts_default_excerpt', true)) {

    if(get_theme_mod('blog_posts_excerpt', 'content') == 'content') {

        ob_start();
        the_content('');
        $excerpt = ob_get_contents();
        ob_end_clean();

    } elseif(get_theme_mod('blog_posts_excerpt', 'content') == 'excerpt') {

        ob_start();
        the_excerpt('');
        $excerpt = strip_tags(ob_get_contents());
        ob_end_clean();

    }

  } else {
    if($postid !== '') {
      $content = apply_filters('the_content', get_post_field('post_content', $postid));
      $content = str_replace(']]>', ']]&gt;', $content);
    } else {
      $content = get_the_content();
    }

    if($content !== '') {

      $content = wp_strip_all_tags($content);

      $excerpt = substr($content, 0, strpos(wordwrap($content, $chars, '♧'), '♧'));
      $excerpt = str_replace('&hellip;', '', $excerpt);
      $excerpt = str_replace('&nbsp;', '', $excerpt);

      $excerpt = rtrim($excerpt, ',:;_– ');

      $last_char = substr($excerpt, -1);

      $last_symbols = array('.', '?', '!', '…');

      if(!in_array($last_char, $last_symbols)) {
        $excerpt = $excerpt.'…';
      }
    }

  }

  return $excerpt;

}
endif;

/*
* Modify main blog listing query
*/
if(!function_exists('saxon_modify_main_query')):
function saxon_modify_main_query( $query ) {
  if ( $query->is_home() && $query->is_main_query() ) { // Run only on the homepage

    $cat_array = get_theme_mod('blog_exclude_categories','');

    if($cat_array !== '') {
      $exclude_cats = '-'.implode(',-', get_theme_mod('blog_exclude_categories',''));

      $query->query_vars['cat'] = $exclude_cats;
    }

  }
}
endif;
add_action( 'pre_get_posts', 'saxon_modify_main_query' );

/*
 * Get columns layout class based on blog layout
 */
if(!function_exists('saxon_get_blog_col_class')):
function saxon_get_blog_col_class($blog_layout = '', $is_sidebar = true) {

  /*
  * Columns layout structure based on blog layout:
  * " saxon-blog-col-2" - 2 cols
  * " saxon-blog-col-3" - 3 cols
  * " saxon-blog-col-1-2" - first 1 then 2 cols
  * " saxon-blog-col-1-3" - first 1 then 3 cols
  * " saxon-blog-col-mixed-1" - first 1 then 2 cols with repeat after 4 elements
  * " saxon-blog-col-mixed-2" - first 1 then 2 cols with repeat after 2 elements
  */

  switch ($blog_layout) {

    // Layout: First large then grid
    case 'large-grid':
      $column_layout = $is_sidebar ? ' saxon-blog-col-1-2' : ' saxon-blog-col-1-3';
      break;

    // Layout: First overlay then grid
    case 'overlay-grid':
      $column_layout = $is_sidebar ? ' saxon-blog-col-1-2' : ' saxon-blog-col-1-3';
      break;

    // Layout: Grid
    case 'grid':
      $column_layout = $is_sidebar ? ' saxon-blog-col-2' : ' saxon-blog-col-3';
      break;

    // Layout: Overlay full
    case 'overlayfull':
      $column_layout = $is_sidebar ? ' saxon-blog-col-2' : ' saxon-blog-col-3';
      break;

    // Layout: Mixed overlays
    case 'mixed-overlays':
      $column_layout = ' saxon-blog-col-mixed-1';
      break;

    // Layout: Mixed large then grid
    case 'mixed-large-grid':
      $column_layout = ' saxon-blog-col-mixed-2';
      break;

    default:
      $column_layout = '';
      break;
  }

  return $column_layout;
}
endif;

/*
* Get posts media formats that support format icons
*/
if(!function_exists('saxon_get_mediaformats')):
function saxon_get_mediaformats() {

  $media_formats[] = 'video';
  $media_formats[] = 'image';
  $media_formats[] = 'gallery';
  $media_formats[] = 'audio';
  $media_formats[] = 'quote';
  $media_formats[] = 'link';

  return $media_formats;
}
endif;

/*
* Get wp_kses allowed tags and attributes list for data-style support
*/
if(!function_exists('saxon_esc_data')):
function saxon_esc_data() {
  $allowed_tags =
      array(
        'a' => array(
          'href' => array(),
          'title' => array(),
          'class' => array(),
          'data' => array(),
          'data-style' => array(),
          'rel'   => array()
        ),
        'div' => array(
          'class' => array(),
          'data' => array(),
          'data-style' => array()
        ),
        'span' => array(
          'class' => array(),
          'data' => array(),
          'data-style' => array()
        ),
        'iframe' => array(
          'src' => array(),
          'class' => array(),
          'data' => array(),
          'data-style' => array(),
          'allow' => array(),
          'allowfullscreen' => array(),
          'width' => array(),
          'height' => array(),
          'frameborder' => array()
        )
  );

  return $allowed_tags;
}
endif;

/*
* Sanitize function for theme options with HTML
*/
if(!function_exists('saxon_sanitize')):
function saxon_sanitize($data) {
  return $data;
}
endif;

/*
 *  Get COL-MD CSS class for 'postsgrid' homepage block
 */
if(!function_exists('saxon_get_postsgrid_col')):
function saxon_get_postsgrid_col($blockid){

  switch ($blockid) {
    case 'postsgrid1':
      $col = 'col-md-4';
      break;

    case 'postsgrid2':
      $col = 'col-md-6';
      break;

    case 'postsgrid3':
      $col = 'col-md-3';
      break;

    case 'postsgrid4':
      $col = 'col-md-4';
      break;

    case 'postsgrid5':
      $col = 'col-md-3';
      break;

    case 'postsgrid6':
      $col = 'col-md-3';
      break;

    case 'postsgrid7':
      $col = 'col-md-4';
      break;

    case 'postsgrid8':
      $col = 'col-md-6';
      break;

    default:
      $col = 'col-md-12';
      break;
  }

  return $col;

}
endif;

/*
 *  Load more posts ajax handler
 */
if(!function_exists('saxon_loadmore_ajax_handler')):
function saxon_loadmore_ajax_handler(){

  global $wp_query;

  // prepare our arguments for the query
  $args = $_POST['query'];

  $q_args['paged'] = $_POST['page'] + 1;
  $q_args['posts_per_page'] = $args['posts_per_page'];
  $q_args['orderby'] = $args['orderby'];
  $q_args['order'] = $args['order'];
  $q_args['cat'] = $args['cat'];
  $q_args['post_type'] = $args['post_type'];
  $q_args['post_status'] = $args['post_status'];
  $q_args['category_name'] = $args['category_name'];
  $q_args['meta_key'] = $args['meta_key'];
  $q_args['meta_value'] = $args['meta_value'];
  $q_args['suppress_filters'] = $args['suppress_filters'];

  $block_id = esc_html($_POST['block']);
  $post_template = esc_html($_POST['post_template']);

  query_posts( $q_args );

  if( have_posts() ) :

    // run the loop
    while( have_posts() ): the_post();

      // Posts grid layouts
      $col_class = saxon_get_postsgrid_col($block_id);

      if($block_id == 'blog') {

        // Set correct post loop id for ajax requests
        if(is_paged()) {

          $post_loop_id = get_query_var('paged') * get_option( 'posts_per_page' ) - get_option( 'posts_per_page' ) + $wp_query->current_post + 1;

          $post_loop_details['post_loop_id'] = $post_loop_id;
          saxon_set_post_details($post_loop_details);

        }

        get_template_part( 'content', get_post_format() );

      } else {

        echo '<div class="'.esc_attr($col_class).'">';
        get_template_part( 'inc/templates/post/content', $post_template );
        echo '</div>';

      }

    endwhile;

  endif;
  die; // here we exit the script and even no wp_reset_query() required!
}
endif;
add_action('wp_ajax_saxon_loadmore', 'saxon_loadmore_ajax_handler'); // wp_ajax_{action}
add_action('wp_ajax_nopriv_saxon_loadmore', 'saxon_loadmore_ajax_handler'); // wp_ajax_nopriv_{action}

/**
 * Ajax likes PHP
 */
if (!function_exists('saxon_likes_callback')) :
function saxon_likes_callback() {

  $postid = esc_html($_POST['postid']);

  $count_key = '_saxon_post_likes_count';

  $count = get_post_meta($postid, $count_key, true);
  if($count==''){
    $count = 0;
    delete_post_meta($postid, $count_key);
    add_post_meta($postid, $count_key, '0');
  } else {
    $count++;
    update_post_meta($postid, $count_key, $count);
  }

  wp_die();
}
add_action('wp_ajax_saxon_likes', 'saxon_likes_callback');
add_action('wp_ajax_nopriv_saxon_likes', 'saxon_likes_callback');
endif;

/**
 * Ajax likes JS
 */
if (!function_exists('saxon_likes_javascript')) :
function saxon_likes_javascript() {

  wp_add_inline_script('saxon-script', "(function($){
  $(document).ready(function($) {

    'use strict';

    $('.content-block ').on('click', '.saxon-post .post-like-button', function(e){

      e.preventDefault();
      e.stopPropagation();

      var postlikes = $(this).next('.post-like-counter').text();
      var postid = $(this).data('id');

      if(getCookie('saxon-likes-for-post-'+postid) == 1) {
        // Already liked
      } else {

        setCookie('saxon-likes-for-post-'+postid, '1', 365);

        $(this).children('i').attr('class', 'fa fa-heart');

        $(this).next('.post-like-counter').text(parseInt(postlikes) + 1);

        var data = {
            action: 'saxon_likes',
            postid: postid,
        };

        var ajaxurl = '".esc_url(admin_url( 'admin-ajax.php' ))."';

        $.post( ajaxurl, data, function(response) {

            var wpdata = response;

        });
      }

    });

  });
  })(jQuery);");
  ?>
  <?php
}
add_action('wp_enqueue_scripts', 'saxon_likes_javascript', 99);
endif;
