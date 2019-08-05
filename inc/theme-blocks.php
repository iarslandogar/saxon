<?php
/**
 * Theme homepage blocks
 **/

/**
 * Subscribe block display
 */
if(!function_exists('saxon_block_subscribe_display')):
function saxon_block_subscribe_display($settings = '') {
  ?>
  <div class="container saxon-subscribe-block-container saxon-block"<?php saxon_add_aos(); ?>>
    <?php if(!empty($settings['block_title'])): ?>
    <div class="row">
    <?php
      echo '<div class="col-md-12 saxon-block-title"><h3>'.esc_html($settings['block_title']).'</h3></div>';
    ?>
    </div>
    <?php endif; ?>
    <div class="saxon-subscribe-block">
    <?php echo do_shortcode(get_theme_mod('subscribeblock_html', '')); ?>
    </div>
  </div>
  <?php
}
endif;

/**
 * Homepage featured categories block display
 */
if(!function_exists('saxon_block_categories_display')):
function saxon_block_categories_display($settings = '') {

    $categories = get_theme_mod('featured_categories', array());
    $categories_count = count($categories);

    if(!empty($categories) && $categories_count > 0) {

        echo '<div class="saxon-featured-categories-wrapper saxon-block"'.saxon_add_aos(false).'>';
        echo '<div class="container">';
        echo '<div class="row">';

        if(!empty($settings['block_title'])) {
          echo '<div class="col-md-12 saxon-block-title"><h3>'.esc_html($settings['block_title']).'</h3></div>';
        }

        if($categories_count % 3 == 0) {
            $col_class = 'col-md-4';
        } else {
            $col_class = 'col-md-3';
        }

        foreach ($categories as $category) {

            $category_title = get_the_category_by_ID( $category );

            if(!empty($category_title)) {

              $category_link = get_category_link( $category );

              $category_image = get_term_meta ( $category, '_saxon_category_image', true );

              if(isset($category_image) && ($category_image !== '')) {
                  $category_style = 'background-image: url('.$category_image.');';
              } else {
                  $category_style = '';
              }

              $category_color = get_term_meta ( $category, '_saxon_category_color', true );

              if(isset($category_color) && ($category_color !== '')) {
                  $category_badge_style = 'background-color: '.$category_color.';';
              } else {
                  $category_badge_style = '';
              }

              echo '<div class="'.esc_attr($col_class).'">';
              echo '<div class="saxon-featured-category" data-style="'.esc_attr($category_style).'"'.saxon_add_aos(false).'>';
              echo '<a href="'.esc_url($category_link).'" class="saxon-featured-category-link btn" data-style="'.esc_attr($category_badge_style).'">'.esc_html($category_title).'</a>';
              echo '</div>';
              echo '</div>';
            }


        }

        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

}
endif;

/**
 * Footer Instagram block display
 */
if(!function_exists('saxon_block_instagram_display')):
function saxon_block_instagram_display($settings = '') {

    // Instagram feed
    echo '<div class="saxon-instagram-block-wrapper saxon-block"'.saxon_add_aos(false).'>';
    if(!empty($settings['block_title'])) {
      echo '<div class="container">';
      echo '<div class="row">';
      echo '<div class="col-md-12 saxon-block-title"><h3>'.esc_html($settings['block_title']).'</h3></div>';
      echo '</div>';
      echo '</div>';
    }

    echo do_shortcode('[instagram-feed]');
    echo '</div>';

}
endif;

/**
 * Postsline #1 block display
 */
if(!function_exists('saxon_block_postsline_display')):
function saxon_block_postsline_display($settings = '') {

  $args = saxon_get_wpquery_args($settings);

  $posts_query = new WP_Query;
  $posts = $posts_query->query($args);

  $total_posts = count($posts);

  if($posts_query->have_posts()) {

    $unique_block_id = rand(10000, 900000);
    ?>
    <div class="saxon-postline-block-wrapper"<?php saxon_add_aos(true);?>>
      <div class="container">
        <div class="row">
          <div class="saxon-postline-block saxon-postline-block-<?php echo esc_attr($unique_block_id); ?> saxon-block clearfix">
            <div class="owl-carousel">
            <?php
            while ($posts_query->have_posts()) {
                  $posts_query->the_post();

                  $post = get_post();

                  $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'saxon-blog-thumb-widget');

                  if(has_post_thumbnail( $post->ID )) {
                      $image_bg ='background-image: url('.$image[0].');';
                      $post_class = '';
                  }
                  else {
                      $image_bg = '';
                      $post_class = ' saxon-post-no-image';
                  }

                  $categories_list = saxon_get_the_category_list( $post->ID );
                  ?>

                  <div class="saxon-post<?php echo esc_attr($post_class); ?>">
                    <div class="col-md-8 saxon-postline-block-left">
                      <?php if(has_post_thumbnail( $post->ID )): ?>
                      <a href="<?php echo esc_url(get_permalink($post->ID)); ?>" class="saxon-post-image-link">
                        <div class="saxon-post-image" data-style="<?php echo esc_attr($image_bg); ?>"></div>
                      </a>
                      <?php endif; ?>
                      <div class="saxon-postline-block-title">
                        <div class="saxon-block-title"><h3><?php echo esc_html($settings['block_title']); ?></h3></div>
                        <h3 class="post-title"><a href="<?php echo esc_url(get_permalink($post->ID)); ?>"><?php echo esc_html($post->post_title); ?></a></h3>
                      </div>
                    </div>
                    <div class="col-md-4 saxon-postline-block-right">
                      <div class="post-categories"><?php echo wp_kses($categories_list, saxon_esc_data()); ?></div>
                      <?php if(get_theme_mod('blog_posts_author', false)):
                      ?>
                      <div class="post-author">
                          <?php echo esc_html__('By', 'saxon'); ?> <?php echo get_the_author_posts_link(); ?>
                      </div>
                      <?php
                      endif;
                      ?>
                      <div class="post-date"><?php echo get_the_time( get_option( 'date_format' ), $post->ID ); ?></div>
                    </div>
                  </div>
            <?php
            }
            ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php
      if($total_posts > 1) {
        wp_add_inline_script( 'saxon-script', '(function($){
        $(document).ready(function() {

            "use strict";

            var owlpostslider = $(".saxon-postline-block.saxon-postline-block-'.esc_js($unique_block_id).' .owl-carousel").owlCarousel({
                loop: true,
                items: 1,
                autoplay: true,
                autowidth: false,
                autoplaySpeed: 1000,
                navSpeed: 5000,
                nav: false,
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

               $(".saxon-postline-block.saxon-postline-block-'.esc_js($unique_block_id).' .owl-carousel").show();

               AOS.refresh();

            });})(jQuery);');
      }

    } // have_posts

}
endif;

/**
 * Postsline #2 block display
 */
if(!function_exists('saxon_block_postsline2_display')):
function saxon_block_postsline2_display($settings = '') {

  $args = saxon_get_wpquery_args($settings);

  $posts_query = new WP_Query;
  $posts = $posts_query->query($args);

  $total_posts = count($posts);

  if($posts_query->have_posts()) {

    $unique_block_id = rand(10000, 900000);
    ?>
    <div class="saxon-postline-block-wrapper"<?php saxon_add_aos(true);?>>
      <div class="container">
        <div class="row">
          <div class="saxon-postline-block saxon-postline2-block saxon-postline-block-<?php echo esc_attr($unique_block_id); ?> saxon-block clearfix">
            <div class="owl-carousel">
            <?php
            while ($posts_query->have_posts()) {
                  $posts_query->the_post();

                  $post = get_post();

                  $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'saxon-blog-thumb-widget');

                  if(has_post_thumbnail( $post->ID )) {
                      $image_bg ='background-image: url('.$image[0].');';
                      $post_class = '';
                  }
                  else {
                      $image_bg = '';
                      $post_class = ' saxon-post-no-image';
                  }

                  $categories_list = saxon_get_the_category_list( $post->ID );
                  ?>

                  <div class="saxon-post<?php echo esc_attr($post_class); ?>">
                    <div class="col-md-12 saxon-postline-block-left">
                      <?php if(has_post_thumbnail( $post->ID )): ?>
                      <a href="<?php echo esc_url(get_permalink($post->ID)); ?>" class="saxon-post-image-link">
                        <div class="saxon-post-image" data-style="<?php echo esc_attr($image_bg); ?>"></div>
                      </a>
                      <?php endif; ?>
                      <div class="saxon-postline-block-title">
                        <div class="post-categories"><?php echo wp_kses($categories_list, saxon_esc_data()); ?></div>
                        <h3 class="post-title"><a href="<?php echo esc_url(get_permalink($post->ID)); ?>"><?php echo esc_html($post->post_title); ?></a></h3>
                      </div>
                    </div>
                  </div>
            <?php
            }
            ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php
      if($total_posts > 1) {
        wp_add_inline_script( 'saxon-script', '(function($){
        $(document).ready(function() {

            "use strict";

            var owlpostslider = $(".saxon-postline-block.saxon-postline-block-'.esc_js($unique_block_id).' .owl-carousel").owlCarousel({
                loop: true,
                items: 3,
                autoplay: true,
                autowidth: false,
                autoplaySpeed: 1000,
                navSpeed: 5000,
                nav: false,
                dots: false,
                navText: false,
                responsive: {
                    1199:{
                        items:3
                    },
                    979:{
                        items:3
                    },
                    768:{
                        items:2
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

               $(".saxon-postline-block.saxon-postline-block-'.esc_js($unique_block_id).' .owl-carousel").show();

               AOS.refresh();

            });})(jQuery);');
      }

    } // have_posts

}
endif;

/**
 * Helper function to render posts blocks output
 */
if(!function_exists('saxon_posts_block_renderer')):
function saxon_posts_block_renderer($block_id = '', $settings = array()) {

        $args = saxon_get_wpquery_args($settings);

        $posts_query = new WP_Query;
        $posts = $posts_query->query($args);

        // Disable load more if specified offset
        if(!empty($settings['block_posts_offset'])) {
          $settings['block_posts_loadmore'] = 'no';
        }

        if($posts_query->have_posts()) {

              $unique_block_id = rand(10000, 900000);

              echo '<div class="saxon-'.esc_attr($block_id).'-block-wrapper saxon-'.esc_attr($block_id).'-block-wrapper-'.esc_html($unique_block_id).' saxon-block">';
              echo '<div class="container">';
              echo '<div class="row">';

              if(!empty($settings['block_title'])) {
                echo '<div class="col-md-12 saxon-block-title"><h3>'.esc_html($settings['block_title']).'</h3></div>';
              }

              $i = 0;
              $post_template = $block_id;

              while ($posts_query->have_posts()){
                  $posts_query->the_post();

                  $i++;

                  // Mixed templates
                  if($block_id == 'postsmasonry1') {

                    // Posts Masonry 1
                    if($i == 1) {

                      echo '<div class="col-md-7">';

                      get_template_part( 'inc/templates/block/content', $block_id.'-1' );

                      echo '</div>';

                    } else {

                      echo '<div class="col-md-5">';

                      get_template_part( 'inc/templates/block/content', $block_id.'-2' );

                      echo '</div>';
                    }

                  } elseif($block_id == 'postsmasonry2') {

                    // Posts Masonry 2
                    if($i == 1) {

                      echo '<div class="col-md-5">';

                      get_template_part( 'inc/templates/block/content', $block_id.'-1' );

                      echo '</div>';

                    } elseif($i > 3) {

                      if($i == 4) {
                        echo '<div class="col-md-3">';
                      }

                      get_template_part( 'inc/templates/block/content', $block_id.'-3' );

                      if($i == $posts_query->post_count) {
                        echo '</div>';
                      }

                    } else {

                      if($i == 2) {
                        echo '<div class="col-md-4">';
                      }

                      get_template_part( 'inc/templates/block/content', $block_id.'-2' );

                      if($i == 3) {
                        echo '</div>';
                      }

                    }

                  } elseif($block_id == 'postsmasonry3') {

                    // Posts Masonry 3
                    if($i == 1) {

                      echo '<div class="col-md-5">';

                      get_template_part( 'inc/templates/block/content', $block_id.'-1' );

                      echo '</div>';

                    } else {

                      if($i == 2) {
                        echo '<div class="col-md-7">';
                      }

                      get_template_part( 'inc/templates/post/content', 'list-medium' );

                      if($i == $posts_query->post_count) {
                        echo '</div>';
                      }

                    }

                  } elseif($block_id == 'showcase1') {

                    if(!isset($second_col)) {
                      $second_col = false;
                    }

                    // Showcase 1
                    if($i == 1) {

                      echo '<div class="col-md-5">';

                      get_template_part( 'inc/templates/post/content', 'overlay-alt' );

                      echo '</div>';

                    } elseif($i == 2) {

                      echo '<div class="col-md-7">';

                      get_template_part( 'inc/templates/post/content', 'overlay-alt' );

                      $second_col = true;

                    } else {

                      get_template_part( 'inc/templates/post/content', 'overlay-alt' );

                      if($i == $settings['block_posts_limit'] && $second_col) {
                        echo '</div>';
                      }
                    }

                    if(($posts_query->post_count < $settings['block_posts_limit']) && $second_col && ($i == $posts_query->post_count)) {
                      echo '</div>';
                    }

                  } elseif($block_id == 'showcase2') {

                    // Showcase 2
                    if($i == 1) {

                      echo '<div class="col-md-12">';

                      get_template_part( 'inc/templates/post/content', 'overlay-alt' );

                      echo '</div>';

                    } else {

                      echo '<div class="col-md-4">';

                      get_template_part( 'inc/templates/post/content', 'overlay-alt' );

                      echo '</div>';

                    }

                  } elseif($block_id == 'showcase3') {

                    // Showcase 3
                    if($i == 1) {

                      echo '<div class="col-md-6">';

                      get_template_part( 'inc/templates/post/content', 'overlay-alt' );

                      echo '</div>';

                    } else {

                      echo '<div class="col-md-3">';

                      get_template_part( 'inc/templates/post/content', 'overlay-alt' );

                      echo '</div>';

                    }
                  } elseif($block_id == 'showcase4') {

                     // Showcase 4
                    if($i == 1) {

                      echo '<div class="col-md-5">';

                      get_template_part( 'inc/templates/post/content', 'overlay-alt' );

                      echo '</div>';

                    } elseif($i == 2) {

                      echo '<div class="col-md-7">';

                      get_template_part( 'inc/templates/post/content', 'overlay-alt' );

                      $second_col = true;

                    } else {

                      get_template_part( 'inc/templates/post/content', 'overlay-alt' );

                      if($i == $settings['block_posts_limit'] && $second_col) {
                        echo '</div>';
                      }
                    }

                    if(($posts_query->post_count < $settings['block_posts_limit']) && $second_col && ($i == $posts_query->post_count)) {
                      echo '</div>';
                    }

                  // Grid templates
                  } elseif($block_id == 'postsgrid1') {

                    echo '<div class="'.esc_attr(saxon_get_postsgrid_col($block_id)).'">';

                    $post_template = 'grid-info';

                    get_template_part( 'inc/templates/post/content', $post_template );

                    echo '</div>';

                  } elseif($block_id == 'postsgrid2') {

                    echo '<div class="'.esc_attr(saxon_get_postsgrid_col($block_id)).'">';

                    $post_template = 'overlay';

                    get_template_part( 'inc/templates/post/content', $post_template );

                    echo '</div>';

                  } elseif($block_id == 'postsgrid3') {

                    echo '<div class="'.esc_attr(saxon_get_postsgrid_col($block_id)).'">';

                    $post_template = 'overlay-alt';

                    get_template_part( 'inc/templates/post/content', $post_template );

                    echo '</div>';

                  } elseif($block_id == 'postsgrid4') {

                    echo '<div class="'.esc_attr(saxon_get_postsgrid_col($block_id)).'">';

                    $post_template = 'grid-short';

                    get_template_part( 'inc/templates/post/content', $post_template );

                    echo '</div>';

                  } elseif($block_id == 'postsgrid5') {

                    echo '<div class="'.esc_attr(saxon_get_postsgrid_col($block_id)).'">';

                    $post_template = 'grid-text';

                    get_template_part( 'inc/templates/post/content', $post_template );

                    echo '</div>';

                  } elseif($block_id == 'postsgrid6') {

                    echo '<div class="'.esc_attr(saxon_get_postsgrid_col($block_id)).'">';

                    $post_template = 'overlay-alt';

                    get_template_part( 'inc/templates/post/content', $post_template );

                    echo '</div>';

                  } elseif($block_id == 'postsgrid7') {

                    echo '<div class="'.esc_attr(saxon_get_postsgrid_col($block_id)).'">';

                    $post_template = 'list-small';

                    get_template_part( 'inc/templates/post/content', $post_template );

                    echo '</div>';

                  } elseif($block_id == 'postsgrid8') {

                    echo '<div class="'.esc_attr(saxon_get_postsgrid_col($block_id)).'">';

                    $post_template = 'grid-info';

                    get_template_part( 'inc/templates/post/content', $post_template );

                    echo '</div>';

                  }
              }
            }

            if (  $posts_query->max_num_pages > 1 && $settings['block_posts_loadmore'] == 'yes' ) {
              echo '<div class="col-md-12 saxon-block-button"'.saxon_add_aos(false).'><a href="#" class="btn btn-grey btn-load-more">'.esc_html__('Load more', 'saxon').'</a></div>';
            }

            wp_reset_postdata();

            echo '</div>';
            echo '</div>';

            echo '</div>';

            // Load more JS script
            if (  $posts_query->max_num_pages > 1 && $settings['block_posts_loadmore'] == 'yes' ) {
              wp_add_inline_script( 'saxon-script', "(function($){
              $(document).ready(function() {
                  'use strict';

                  var current_page_".esc_js($unique_block_id)." = 1;

                  $('.saxon-".esc_js($block_id)."-block-wrapper-".esc_js($unique_block_id)." .btn-load-more').click(function(e){

                    e.preventDefault();
                    e.stopPropagation();

                    var button = $(this),
                        data = {
                        'action': 'saxon_loadmore',
                        'query': ".json_encode( $posts_query->query_vars , true).",
                        'page' : current_page_".esc_js($unique_block_id).",
                        'block' : '".esc_js($block_id)."',
                        'post_template' : '".esc_js($post_template)."'
                    };

                    var button_default_text = button.text();

                    $.ajax({
                        url : '".esc_url(site_url())."/wp-admin/admin-ajax.php', // AJAX handler
                        data : data,
                        type : 'POST',
                        beforeSend : function ( xhr ) {
                            button.text('".esc_html__('Loading...', 'saxon')."');
                            button.addClass('btn-loading');
                        },
                        success : function( data ){
                            if( data ) {
                                button.text( button_default_text );
                                button.removeClass('btn-loading');

                                // Insert new posts
                                $('.saxon-".esc_js($block_id)."-block-wrapper-".esc_js($unique_block_id)." .saxon-block-button').before(data);

                                // Show images
                                $('.saxon-".esc_js($block_id)."-block-wrapper-".esc_js($unique_block_id)." .saxon-post-image').each(function( index ) {
                                  $(this).attr('style', ($(this).attr('data-style')));
                                });

                                // Show categories colors
                                $('.saxon-".esc_js($block_id)."-block-wrapper-".esc_js($unique_block_id)." .saxon-post .post-categories a').each(function( index ) {
                                  $(this).attr('style', ($(this).attr('data-style')));
                                });

                                current_page_".esc_js($unique_block_id)."++;

                                if ( current_page_".esc_js($unique_block_id)." == ".esc_js($posts_query->max_num_pages)." )
                                    button.remove(); // if last page, remove the button

                            } else {
                                button.remove(); // if no data, remove the button
                            }
                        }
                    });
                  });

              });})(jQuery);");

          }
}
endif;

/**
 * Posts Grid 1 block display
 */
if(!function_exists('saxon_block_postsgrid1_display')):
function saxon_block_postsgrid1_display($settings = '') {

  saxon_posts_block_renderer('postsgrid1', $settings);

}
endif;

/**
 * Posts Grid 2 block display
 */
if(!function_exists('saxon_block_postsgrid2_display')):
function saxon_block_postsgrid2_display($settings = '') {

  saxon_posts_block_renderer('postsgrid2', $settings);

}
endif;

/**
 * Posts Grid 3 block display
 */
if(!function_exists('saxon_block_postsgrid3_display')):
function saxon_block_postsgrid3_display($settings = '') {

  saxon_posts_block_renderer('postsgrid3', $settings);

}
endif;

/**
 * Posts Grid 4 block display
 */
if(!function_exists('saxon_block_postsgrid4_display')):
function saxon_block_postsgrid4_display($settings = '') {

  saxon_posts_block_renderer('postsgrid4', $settings);

}
endif;

/**
 * Posts Grid 5 block display
 */
if(!function_exists('saxon_block_postsgrid5_display')):
function saxon_block_postsgrid5_display($settings = '') {

  saxon_posts_block_renderer('postsgrid5', $settings);

}
endif;

/**
 * Posts Grid 6 block display
 */
if(!function_exists('saxon_block_postsgrid6_display')):
function saxon_block_postsgrid6_display($settings = '') {

  saxon_posts_block_renderer('postsgrid6', $settings);

}
endif;

/**
 * Posts Grid 7 block display
 */
if(!function_exists('saxon_block_postsgrid7_display')):
function saxon_block_postsgrid7_display($settings = '') {

  saxon_posts_block_renderer('postsgrid7', $settings);

}
endif;

/**
 * Posts Grid 8 block display
 */
if(!function_exists('saxon_block_postsgrid8_display')):
function saxon_block_postsgrid8_display($settings = '') {

  saxon_posts_block_renderer('postsgrid8', $settings);

}
endif;

/**
 * Posts Masonry 1 block display
 */
if(!function_exists('saxon_block_postsmasonry1_display')):
function saxon_block_postsmasonry1_display($settings = '') {

  $settings['block_posts_limit'] = 3;
  $settings['block_posts_loadmore'] = false;

  saxon_posts_block_renderer('postsmasonry1', $settings);

}
endif;

/**
 * Posts Masonry 2 block display
 */
if(!function_exists('saxon_block_postsmasonry2_display')):
function saxon_block_postsmasonry2_display($settings = '') {

  $settings['block_posts_limit'] = 8;
  $settings['block_posts_loadmore'] = false;

  saxon_posts_block_renderer('postsmasonry2', $settings);

}
endif;

/**
 * Posts Masonry 3 block display
 */
if(!function_exists('saxon_block_postsmasonry3_display')):
function saxon_block_postsmasonry3_display($settings = '') {

  $settings['block_posts_limit'] = 4;
  $settings['block_posts_loadmore'] = false;

  saxon_posts_block_renderer('postsmasonry3', $settings);

}
endif;

/**
 * Showcase 1 block display
 */
if(!function_exists('saxon_block_showcase1_display')):
function saxon_block_showcase1_display($settings = '') {

  $settings['block_posts_limit'] = 5;
  $settings['block_posts_loadmore'] = false;

  saxon_posts_block_renderer('showcase1', $settings);

}
endif;

/**
 * Showcase 2 block display
 */
if(!function_exists('saxon_block_showcase2_display')):
function saxon_block_showcase2_display($settings = '') {

  $settings['block_posts_limit'] = 4;
  $settings['block_posts_loadmore'] = false;

  saxon_posts_block_renderer('showcase2', $settings);

}
endif;

/**
 * Showcase 3 block display
 */
if(!function_exists('saxon_block_showcase3_display')):
function saxon_block_showcase3_display($settings = '') {

  $settings['block_posts_limit'] = 3;
  $settings['block_posts_loadmore'] = false;

  saxon_posts_block_renderer('showcase3', $settings);

}
endif;

/**
 * Showcase 4 block display
 */
if(!function_exists('saxon_block_showcase4_display')):
function saxon_block_showcase4_display($settings = '') {

  $settings['block_posts_limit'] = 4;
  $settings['block_posts_loadmore'] = false;

  saxon_posts_block_renderer('showcase4', $settings);

}
endif;

/**
 * Large Posts Slider block display
 */
if(!function_exists('saxon_block_largepostsslider_display')):
function saxon_block_largepostsslider_display($settings = '') {

  $args = saxon_get_wpquery_args($settings);

  $posts_query = new WP_Query;
  $posts = $posts_query->query($args);

  $total_posts = count($posts);

  if($posts_query->have_posts()) {

    $unique_block_id = rand(10000, 900000);
    ?>
    <div class="saxon-largepostsslider-block-wrapper">
      <div class="container">
        <div class="row">
          <div class="saxon-largepostsslider-block saxon-largepostsslider-block-<?php echo esc_attr($unique_block_id); ?> saxon-block">
            <div class="owl-carousel">
            <?php
            while ($posts_query->have_posts()) {
              $posts_query->the_post();

              echo '<div class="col-md-12">';

              get_template_part( 'inc/templates/post/content', 'overlay-short' );

              echo '</div>';
            }
            ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php
      if($total_posts > 1) {
        wp_add_inline_script( 'saxon-script', '(function($){
        $(document).ready(function() {

            var owlpostslider = $(".saxon-largepostsslider-block.saxon-largepostsslider-block-'.esc_js($unique_block_id).' .owl-carousel").owlCarousel({
                loop: true,
                items: 1,
                autoplay: true,
                autowidth: false,
                autoplaySpeed: 1000,
                navSpeed: 5000,
                nav: false,
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

               $(".saxon-largepostsslider-block.saxon-largepostsslider-block-'.esc_js($unique_block_id).' .owl-carousel").show();

               AOS.refresh();

            });})(jQuery);');
      }

    } // have_posts

}
endif;

/**
 * Posts Carousel block display
 */
if(!function_exists('saxon_block_carousel_display')):
function saxon_block_carousel_display($settings = '') {

  $args = saxon_get_wpquery_args($settings);

  $posts_query = new WP_Query;
  $posts = $posts_query->query($args);

  $total_posts = count($posts);

  if($posts_query->have_posts()) {

    $unique_block_id = rand(10000, 900000);
    ?>
    <div class="saxon-carousel-block-wrapper">
      <div class="container">
        <div class="row">
          <div class="saxon-carousel-block saxon-carousel-block-<?php echo esc_attr($unique_block_id); ?> saxon-block">
            <?php
            if(!empty($settings['block_title'])) {
              echo '<div class="saxon-block-title"><h3>'.esc_html($settings['block_title']).'</h3></div>';
            }
            ?>
            <div class="owl-carousel">
            <?php
            while ($posts_query->have_posts()) {
              $posts_query->the_post();

              get_template_part( 'inc/templates/post/content', 'grid-short' );

            }
            ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php
      if($total_posts > 1) {
        wp_add_inline_script( 'saxon-script', '(function($){
        $(document).ready(function() {

            var owlpostslider = $(".saxon-carousel-block.saxon-carousel-block-'.esc_js($unique_block_id).' .owl-carousel").owlCarousel({
                loop: true,
                items: 4,
                autoplay: true,
                autowidth: false,
                autoplaySpeed: 500,
                navSpeed: 500,
                margin: 30,
                nav: false,
                dots: false,
                navText: false,
                slideBy: 4,
                responsive: {
                    1199:{
                        items:4,
                        slideBy: 4
                    },
                    979:{
                        items:4,
                        slideBy: 4
                    },
                    768:{
                        items:2,
                        slideBy: 1
                    },
                    479:{
                        items:1,
                        slideBy: 1
                    },
                    0:{
                        items:1,
                        slideBy: 1
                    }
                }
            });

            AOS.refresh();

        });})(jQuery);');
      } else {
        wp_add_inline_script( 'saxon-script', '(function($){
            $(document).ready(function() {

              "use strict";

               $(".saxon-carousel-block.saxon-carousel-block-'.esc_js($unique_block_id).' .owl-carousel").show();

               AOS.refresh();

            });})(jQuery);');
      }

    } // have_posts

}
endif;

/**
 * Fullwidth Posts Slider block display
 */
if(!function_exists('saxon_block_fullwidthpostsslider_display')):
function saxon_block_fullwidthpostsslider_display($settings = '') {

  $args = saxon_get_wpquery_args($settings);

  $posts_query = new WP_Query;
  $posts = $posts_query->query($args);

  $total_posts = count($posts);

  if($posts_query->have_posts()) {

    $unique_block_id = rand(10000, 900000);
    ?>
    <div class="saxon-fullwidthpostsslider-block-wrapper">
      <div class="container-fluid">
        <div class="row">
          <div class="saxon-fullwidthpostsslider-block saxon-fullwidthpostsslider-block-<?php echo esc_attr($unique_block_id); ?> saxon-block">
            <div class="owl-carousel">
            <?php
            while ($posts_query->have_posts()) {
              $posts_query->the_post();

              echo '<div class="col-md-12">';

              get_template_part( 'inc/templates/post/content', 'overlay-short' );

              echo '</div>';
            }
            ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php
      if($total_posts > 1) {
        wp_add_inline_script( 'saxon-script', '(function($){
        $(document).ready(function() {

            "use strict";

            var owlpostslider = $(".saxon-fullwidthpostsslider-block.saxon-fullwidthpostsslider-block-'.esc_js($unique_block_id).' .owl-carousel").owlCarousel({
                loop: true,
                items: 1,
                autoplay: true,
                autowidth: false,
                autoplaySpeed: 1000,
                navSpeed: 5000,
                nav: false,
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

               $(".saxon-fullwidthpostsslider-block.saxon-fullwidthpostsslider-block-'.esc_js($unique_block_id).' .owl-carousel").show();

               AOS.refresh();

            });})(jQuery);');
      }

    } // have_posts

}
endif;

/**
 * Post Highlight block display
 */
if(!function_exists('saxon_block_posthighlight_display')):
function saxon_block_posthighlight_display($settings = '') {

  $settings['block_posts_limit'] = 1;

  $args = saxon_get_wpquery_args($settings);

  $posts_query = new WP_Query;
  $posts = $posts_query->query($args);

  if($posts_query->have_posts()) {

    $unique_block_id = rand(10000, 900000);
    ?>
    <div class="saxon-posthighlight-block-wrapper saxon-block">
      <div class="container">
        <div class="row">
          <?php
          if(!empty($settings['block_title'])) {
            echo '<div class="col-md-12 saxon-block-title"><h3>'.esc_html($settings['block_title']).'</h3></div>';
          }
          ?>
          <div class="saxon-posthighlight-block saxon-posthighlight-block-<?php echo esc_attr($unique_block_id); ?> saxon-block">
            <?php
            while ($posts_query->have_posts()) {
              $posts_query->the_post();

              echo '<div class="col-md-12">';

              get_template_part( 'inc/templates/post/content', 'list-alt' );

              echo '</div>';
            }
            ?>
          </div>
        </div>
      </div>
    </div>
    <?php

    } // have_posts

}
endif;

/**
 * Content block display
 */
if(!function_exists('saxon_block_html_display')):
function saxon_block_html_display($settings = '') {
  ?>
  <div class="container html-block-container saxon-block"<?php saxon_add_aos(); ?>>
    <div class="row">
      <?php
      if(!empty($settings['block_title'])) {
        echo '<div class="col-md-12 saxon-block-title"><h3>'.esc_html($settings['block_title']).'</h3></div>';
      }
      ?>
      <div class="col-md-12 html-block"><?php echo do_shortcode($settings['block_html']); ?></div>
    </div>
  </div>
  <?php
}
endif;

/**
 * Blog listing display
 */
if(!function_exists('saxon_block_blog_display')):
function saxon_block_blog_display($settings = '') {

  global $wp_query;

  $blog_sidebarposition = get_theme_mod('sidebar_blog', 'right');

  // Demo settings
  if ( defined('DEMO_MODE') && isset($_GET['blog_sidebar_position']) ) {
    $blog_sidebarposition = $_GET['blog_sidebar_position'];
  }

  if(is_active_sidebar( 'main-sidebar' ) && ($blog_sidebarposition !== 'disable') ) {
    $span_class = 'col-md-8';
    $is_sidebar = true;
  }
  else {
    $span_class = 'col-md-12';
    $is_sidebar = false;
  }

  // Blog layout
  $blog_layout = get_theme_mod('blog_layout', 'standard');

  if ( defined('DEMO_MODE') && isset($_GET['blog_layout']) ) {
      $blog_layout = $_GET['blog_layout'];
  }

  // Load masonry layout script
  if($blog_layout == 'masonry') {

    wp_enqueue_script('masonry');
    wp_add_inline_script( 'masonry', '(function($){
  $(document).ready(function() {
    "use strict";
    $(window).load(function()
    {
      var $container = $(".blog-layout-masonry");

      $container.imagesLoaded(function(){
        $container.masonry({
          itemSelector : ".blog-layout-masonry .blog-post"
        });

      });

      AOS.refresh();
    });

  });})(jQuery);');

  }

  $temp_query = $wp_query;

  ?>
  <div class="saxon-blog-block-wrapper page-container container">
    <div class="row">
      <?php if ( is_active_sidebar( 'main-sidebar' ) && ( $blog_sidebarposition == 'left')) : ?>
      <div class="col-md-4 main-sidebar sidebar sidebar-left"<?php saxon_add_aos(true);?> role="complementary">
      <ul id="main-sidebar">
        <?php dynamic_sidebar( 'main-sidebar' ); ?>
      </ul>
      </div>
      <?php endif; ?>

      <div class="<?php echo esc_attr($span_class);?>">
      <div class="blog-posts-list blog-layout-<?php echo esc_attr($blog_layout);?><?php echo esc_attr(saxon_get_blog_col_class($blog_layout, $is_sidebar));?>" id="content">
      <?php

      $wp_query = $temp_query;

      ?>
      <?php if ( have_posts() ) : ?>

        <?php /* Start the Loop */
        $post_loop_id = 1;
        ?>
        <?php while ( have_posts() ) : the_post(); ?>

        <?php
          $post_loop_details['post_loop_id'] = $post_loop_id;
          $post_loop_details['span_class'] = $span_class;

          saxon_set_post_details($post_loop_details);

          get_template_part( 'content', get_post_format() );

          $post_loop_id++;
        ?>

        <?php endwhile; ?>

      <?php else : ?>

        <?php get_template_part( 'no-results', 'index' ); ?>

      <?php endif; ?>
      </div>

      <?php
      // Load more
      if ( $wp_query->max_num_pages > 1 && isset($settings['block_posts_loadmore']) && $settings['block_posts_loadmore'] == 'yes' && $blog_layout !== 'masonry' && !is_paged() ) {
        echo '<div class="col-md-12 saxon-block-button"'.saxon_add_aos(false).'><a href="#" class="btn btn-grey btn-load-more">'.esc_html__('Load more', 'saxon').'</a></div>';
      } else {
        saxon_content_nav( 'nav-below' );
      }
      ?>

      <?php
      // Post Loops Bottom Banner
      saxon_banner_display('posts_loop_bottom');
      ?>

      </div>

      <?php if ( is_active_sidebar( 'main-sidebar' ) && ( $blog_sidebarposition == 'right')) : ?>
      <div class="col-md-4 main-sidebar sidebar sidebar-right"<?php saxon_add_aos(true);?> role="complementary">
      <ul id="main-sidebar">
        <?php dynamic_sidebar( 'main-sidebar' ); ?>
      </ul>
      </div>
      <?php endif; ?>
    </div>
  </div>
  <?php

  // Load more JS script
  if ( $wp_query->max_num_pages > 1 && isset($settings['block_posts_loadmore']) && $settings['block_posts_loadmore'] == 'yes' ) {

    $post_template = 'blog';

    wp_add_inline_script( 'saxon-script', "(function($){
    $(document).ready(function() {
        'use strict';

        var current_page = 1;

        $('.blog-posts-list + .saxon-block-button .btn-load-more').click(function(e){

          e.preventDefault();
          e.stopPropagation();

          var button = $(this),
              data = {
              'action': 'saxon_loadmore',
              'query': ".json_encode( $wp_query->query_vars , true).",
              'page' : current_page,
              'block' : 'blog',
              'post_template' : '".esc_js($post_template)."'
          };

          var button_default_text = button.text();

          $.ajax({
              url : '".esc_url(site_url())."/wp-admin/admin-ajax.php', // AJAX handler
              data : data,
              type : 'POST',
              beforeSend : function ( xhr ) {
                  button.text('".esc_html__('Loading...', 'saxon')."');
                  button.addClass('btn-loading');
              },
              success : function( data ){
                  if( data ) {
                      button.text( button_default_text );
                      button.removeClass('btn-loading');

                      // Insert new posts
                      $('.blog-posts-list').append(data);

                      // Show images
                      $('.blog-posts-list .saxon-post-image').each(function( index ) {
                        $(this).attr('style', ($(this).attr('data-style')));
                      });

                      // Show categories colors
                      $('.blog-posts-list .saxon-post .post-categories a').each(function( index ) {
                        $(this).attr('style', ($(this).attr('data-style')));
                      });

                      current_page++;

                      if ( current_page == ".esc_js($wp_query->max_num_pages)." )
                          button.parent().remove(); // if last page, remove the button

                  } else {
                      button.parent().remove(); // if no data, remove the button
                  }
              }
          });
        });

    });})(jQuery);");

}
}
endif;
