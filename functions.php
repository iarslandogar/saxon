<?php
/**
 * Saxon functions
 *
 * @package Saxon
 */

/**
 * WordPress content width configuration
 */
if (!isset($content_width))
	$content_width = 1140; /* pixels */

update_option('saxon_license_key_status', 'activated');

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
if(!function_exists('saxon_setup')):
function saxon_setup() {

	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on Saxon, use a find and replace
	 * to change 'saxon' to the name of your theme in all the template files
	 */
	load_theme_textdomain('saxon', get_template_directory() . '/languages');

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support('automatic-feed-links');

	/**
	 * Enable support for Post Thumbnails on posts and pages
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support('post-thumbnails');

  /**
   * Enable support Gutenberg features
   */
  add_theme_support( 'align-wide' );
  add_theme_support( 'editor-styles' );

	/**
	 * Enable support for JetPack Infinite Scroll
	 *
	 * @link https://jetpack.me/support/infinite-scroll/
	 */
	add_theme_support( 'infinite-scroll', array(
	    'container' => 'content',
	    'footer' => 'page',
	) );

	/**
	 * Enable support for Title Tag
	 *
	 */
	add_theme_support( 'title-tag' );

	/**
	 * Enable support for Logo
	 */
	add_theme_support( 'custom-header', array(
	    'default-image' =>  get_template_directory_uri() . '/img/logo.png',
            'width'         => 165,
            'flex-width'    => true,
            'flex-height'   => false,
            'header-text'   => false,
	));

	/**
	 *	Woocommerce support
	 */
	add_theme_support( 'woocommerce' );

	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	/**
	 * Enable support for Post Formats
	 */
	add_theme_support('post-formats', array('aside', 'image', 'gallery', 'video', 'audio', 'quote', 'link', 'status', 'chat'));

	/**
	 * Theme images sizes
	 */
	add_image_size( 'saxon-blog-thumb', 1140, 694, true);
	add_image_size( 'saxon-blog-thumb-grid', 555, 360, true);
	add_image_size( 'saxon-blog-thumb-widget', 110, 90, true);
  add_image_size( 'saxon-blog-thumb-masonry', 360, 0, false);

	/**
	 * Theme menus locations
	 */
	register_nav_menus( array(
        'main' => esc_html__('Main Menu', 'saxon'),
        'top' => esc_html__('Top Menu', 'saxon'),
        'footer' => esc_html__('Footer Menu', 'saxon'),
	) );

  // Filters the oEmbed process to run the responsive_embed() function
  add_filter('embed_oembed_html', 'saxon_responsive_embed', 10, 3);

}
endif;
add_action('after_setup_theme', 'saxon_setup');

/*
* Change posts excerpt length
*/
if(!function_exists('saxon_new_excerpt_length')):
function saxon_new_excerpt_length($length) {
	$post_excerpt_length = get_theme_mod('blog_posts_excerpt_limit', 35);

	return $post_excerpt_length;
}
endif;
add_filter('excerpt_length', 'saxon_new_excerpt_length');

/**
 * Enqueue scripts and styles
 */
if(!function_exists('saxon_scripts')):
function saxon_scripts() {

	wp_enqueue_style('bootstrap', get_template_directory_uri() . '/css/bootstrap.css');
	wp_enqueue_style('owl-main', get_template_directory_uri() . '/js/owl-carousel/owl.carousel.css');
	wp_enqueue_style('saxon-stylesheet', get_stylesheet_uri(), array(), '1.0.2', 'all');
	wp_enqueue_style('saxon-responsive', get_template_directory_uri() . '/responsive.css', '1.0.2', 'all');

	if ( true == get_theme_mod( 'animations_css3', true ) )  {
		wp_enqueue_style('saxon-animations', get_template_directory_uri() . '/css/animations.css');
	}

	wp_enqueue_style('font-awesome', get_template_directory_uri() . '/css/font-awesome.css');
	wp_enqueue_style('saxon-select2', get_template_directory_uri() . '/js/select2/select2.css'); // special version, must be prefixed with theme prefix
	wp_enqueue_style('swiper', get_template_directory_uri() . '/css/idangerous.swiper.css');

  // Animation on scroll
  wp_enqueue_style('aos', get_template_directory_uri() . '/js/aos/aos.css');
  wp_register_script('aos', get_template_directory_uri() . '/js/aos/aos.js', array(), '2.3.1', true);
  wp_enqueue_script('aos');

	add_thickbox();

	// Registering scripts to include it in correct order later
	wp_register_script('bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array(), '3.1.1', true);
	wp_register_script('easing', get_template_directory_uri() . '/js/easing.js', array(), '1.3', true);
	wp_register_script('saxon-select2', get_template_directory_uri() . '/js/select2/select2.min.js', array(), '3.5.1', true);  // special version, must be prefixed with theme prefix
	wp_register_script('owl-carousel', get_template_directory_uri() . '/js/owl-carousel/owl.carousel.min.js', array(), '2.0.0', true);


	// Enqueue scripts in correct order
	wp_enqueue_script('saxon-script', get_template_directory_uri() . '/js/template.js', array('jquery', 'bootstrap', 'easing', 'saxon-select2', 'owl-carousel'), '1.3', true);

	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}

}
endif;
add_action('wp_enqueue_scripts', 'saxon_scripts');

/**
 * Enqueue scripts and styles for admin area
 */
if(!function_exists('saxon_admin_scripts')):
function saxon_admin_scripts() {
	wp_register_style( 'saxon-style-admin', get_template_directory_uri() .'/css/admin.css' );
	wp_enqueue_style( 'saxon-style-admin' );
	wp_register_style('saxon-font-awesome-admin', get_template_directory_uri() . '/css/font-awesome.css');
	wp_enqueue_style( 'saxon-font-awesome-admin' );

	wp_register_script('saxon-template-admin', get_template_directory_uri() . '/js/template-admin.js', array(), '1.0', true);
	wp_enqueue_script('saxon-template-admin');
}
endif;
add_action( 'admin_init', 'saxon_admin_scripts' );

if(!function_exists('saxon_load_wp_media_files')):
function saxon_load_wp_media_files() {
  wp_enqueue_media();
}
endif;
add_action( 'admin_enqueue_scripts', 'saxon_load_wp_media_files' );

/**
 * Disable built-in WordPress plugins
 */
if(!function_exists('saxon_disable_builtin_plugins')):
function saxon_disable_builtin_plugins() {

  // Deactivate Gutenberg to avoid conflicts, since it's already built in to WordPress 5.x
  if(version_compare(get_bloginfo('version'), '5.0', ">=")) {
      deactivate_plugins( '/gutenberg/gutenberg.php' );
  }

}
endif;
add_action( 'admin_init', 'saxon_disable_builtin_plugins' );

/**
 * Display navigation to next/previous pages when applicable
 */
if(!function_exists('saxon_content_nav')):
function saxon_content_nav( $nav_id ) {
  global $wp_query, $post;

  // Loading library to check active plugins
  include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

  // Don't print empty markup on single pages if there's nowhere to navigate.
  if ( is_single() ) {
    $previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
    $next = get_adjacent_post( false, '', false );

    if ( ! $next && ! $previous )
      return;
  }

  // Don't print empty markup in archives if there's only one page.
  if ( $wp_query->max_num_pages < 2 && ( is_home() || is_archive() || is_search() ) )
    return;

  $nav_class = ( is_single() ) ? 'navigation-post' : 'navigation-paging';

  ?>
  <nav id="<?php echo esc_attr( $nav_id ); ?>" class="<?php echo esc_attr($nav_class); ?>">

  <?php if ( is_single() ) : // navigation links for single posts ?>
  <div class="container-fluid">
  <div class="row">
    <?php $prev_post = get_previous_post(); ?>
    <div class="col-md-6 nav-post-prev saxon-post<?php if(is_a( $prev_post , 'WP_Post' ) && !has_post_thumbnail( $prev_post->ID )) { echo ' no-image'; } ?>">
      <?php
      if ( is_a( $prev_post , 'WP_Post' ) ) { ?>
      <?php
      $image = wp_get_attachment_image_src( get_post_thumbnail_id( $prev_post->ID ), 'saxon-blog-thumb-widget');

      if(has_post_thumbnail( $prev_post->ID )) {
          $image_bg ='background-image: url('.$image[0].');';

          $post_image_html = '<div class="saxon-post-image-wrapper"><a href="'.esc_url(get_permalink($prev_post->ID)).'"><div class="saxon-post-image" data-style="'.esc_attr($image_bg).'"></div></a></div>';
      } else {
          $image_bg = '';
          $post_image_html = '';
      }

      $prev_post_title = the_title_attribute(array('echo' => false, 'post' => $prev_post->ID));

      ?>
      <?php echo wp_kses($post_image_html, saxon_esc_data()); ?>
      <a href="<?php echo esc_url(get_permalink( $prev_post->ID )); ?>" class="nav-post-title-link"><div class="nav-post-title"><?php esc_html_e( 'Previous', 'saxon' ); ?></div><div class="nav-post-name"><?php echo esc_html($prev_post_title); ?></div></a>
    <?php }
    ?>
    </div>
    <?php $next_post = get_next_post(); ?>
    <div class="col-md-6 nav-post-next saxon-post<?php if(is_a( $next_post , 'WP_Post' ) && !has_post_thumbnail( $next_post->ID )) { echo ' no-image'; } ?>">
    <?php

    if ( is_a( $next_post , 'WP_Post' ) ) { ?>
    <?php
      $image = wp_get_attachment_image_src( get_post_thumbnail_id( $next_post->ID ), 'saxon-blog-thumb-widget');

      if(has_post_thumbnail( $next_post->ID )) {
          $image_bg ='background-image: url('.$image[0].');';

          $post_image_html = '<div class="saxon-post-image-wrapper"><a href="'.esc_url(get_permalink($next_post->ID)).'"><div class="saxon-post-image" data-style="'.esc_attr($image_bg).'"></div></a></div>';
      } else {
          $image_bg = '';
          $post_image_html = '';
      }

      $next_post_title = the_title_attribute(array('echo' => false, 'post' => $next_post->ID));
      ?>

      <a href="<?php echo esc_url(get_permalink( $next_post->ID )); ?>" class="nav-post-title-link"><div class="nav-post-title"><?php esc_html_e( 'Next', 'saxon' ); ?></div><div class="nav-post-name"><?php echo esc_html($next_post_title); ?></div></a>

      <?php echo wp_kses($post_image_html, saxon_esc_data()); ?>
    <?php }
    ?>
    </div>

  </div>
  </div>
  <?php elseif ( $wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() ) ) : // navigation links for home, archive, and search pages ?>
  <div class="clear"></div>
  <div class="container-fluid">
    <div class="row">
      <?php if ( function_exists( 'wp_pagenavi' ) ): ?>
        <div class="col-md-12 nav-pagenavi">
        <?php wp_pagenavi(); ?>
        </div>
      <?php else: ?>
        <div class="col-md-6 nav-post-prev">
        <?php if ( get_next_posts_link() ) : ?>
        <?php next_posts_link( esc_html__( 'Older posts', 'saxon' ) ); ?>
        <?php endif; ?>
        </div>

        <div class="col-md-6 nav-post-next">
        <?php if ( get_previous_posts_link() ) : ?>
        <?php previous_posts_link( esc_html__( 'Newer posts', 'saxon' ) ); ?>
        <?php endif; ?>
        </div>
      <?php endif; ?>

    </div>
  </div>
  <?php endif; ?>

  </nav>
  <?php
}
endif;

/**
 * Template for comments and pingbacks.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 */
if(!function_exists('saxon_comment')):
function saxon_comment( $comment, $args, $depth ) {
  $GLOBALS['comment'] = $comment;

  if ( 'pingback' == $comment->comment_type || 'trackback' == $comment->comment_type ) : ?>

  <li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
    <div class="comment-body">
      <?php esc_html_e( 'Pingback:', 'saxon' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( esc_html__( 'Edit', 'saxon' ), '<span class="edit-link">', '</span>' ); ?>
    </div>

  <?php else : ?>

  <li id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?>>
    <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">

      <div class="comment-meta clearfix">
        <div class="reply">
          <?php edit_comment_link( esc_html__( 'Edit', 'saxon' ), '', '' ); ?>
          <?php comment_reply_link( array_merge( $args, array( 'add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
        </div><!-- .reply -->
        <div class="comment-author vcard">

          <?php if ( 0 != $args['avatar_size'] ) echo get_avatar( $comment, 100 ); ?>

        </div><!-- .comment-author -->

        <div class="comment-metadata">
          <div class="author">
          <?php printf('%s', sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
          </div>
          <div class="date"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><time datetime="<?php comment_time( 'c' ); ?>"><?php printf( _x( '%1$s at %2$s', '1: date, 2: time', 'saxon' ), get_comment_date(), get_comment_time() ); ?></time></a></div>

          <?php if ( '0' == $comment->comment_approved ) : ?>
          <p class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'saxon' ); ?></p>
          <?php endif; ?>
          <div class="comment-content">
            <?php comment_text(); ?>
          </div>
        </div><!-- .comment-metadata -->

      </div><!-- .comment-meta -->

    </article><!-- .comment-body -->

  <?php
  endif;
}
endif;

// Set/Get current post details for global usage in templates (post position in loop, etc)
if(!function_exists('saxon_set_post_details')):
function saxon_set_post_details($details) {
	global $saxon_post_details;

	$saxon_post_details = $details;
}
endif;

if(!function_exists('saxon_get_post_details')):
function saxon_get_post_details() {
	global $saxon_post_details;

	return $saxon_post_details;
}
endif;

/**
 * Registers an editor stylesheet
 */
if(!function_exists('saxon_add_editor_styles')):
function saxon_add_editor_styles() {
    add_editor_style( 'custom-editor-style.css' );
}
add_action('admin_init', 'saxon_add_editor_styles');
endif;

/**
 * Social services list
 */
if(!function_exists('saxon_social_services_list')):
function saxon_social_services_list() {
  // You can add more social services here, array keys must beequal to Font Awesome icons names, without 'fa-' prefix
  // Available icons list: https://fontawesome.com/v4.7.0/icons/
  $social_services_array = array(
      'facebook' => esc_attr__( 'Facebook', 'saxon' ),
      'vk' => esc_attr__( 'VKontakte', 'saxon' ),
      'google-plus' => esc_attr__( 'Google+', 'saxon' ),
      'twitter' => esc_attr__( 'Twitter', 'saxon' ),
      'linkedin' => esc_attr__( 'LinkedIn', 'saxon' ),
      'dribbble' => esc_attr__( 'Dribbble', 'saxon' ),
      'behance' => esc_attr__( 'Behance', 'saxon' ),
      'instagram' => esc_attr__( 'Instagram', 'saxon' ),
      'tumblr' => esc_attr__( 'Tumblr', 'saxon' ),
      'pinterest' => esc_attr__( 'Pinterest', 'saxon' ),
      'vimeo-square' => esc_attr__( 'Vimeo', 'saxon' ),
      'youtube' => esc_attr__( 'Youtube', 'saxon' ),
      'twitch' => esc_attr__( 'Twitch', 'saxon' ),
      'skype' => esc_attr__( 'Skype', 'saxon' ),
      'flickr' => esc_attr__( 'Flickr', 'saxon' ),
      'deviantart' => esc_attr__( 'Deviantart', 'saxon' ),
      '500px' => esc_attr__( '500px', 'saxon' ),
      'etsy' => esc_attr__( 'Etsy', 'saxon' ),
      'telegram' => esc_attr__( 'Telegram', 'saxon' ),
      'odnoklassniki' => esc_attr__( 'Odnoklassniki', 'saxon' ),
      'houzz' => esc_attr__( 'Houzz', 'saxon' ),
      'slack' => esc_attr__( 'Slack', 'saxon' ),
      'qq' => esc_attr__( 'QQ', 'saxon' ),
      'github' => esc_attr__( 'Github', 'saxon' ),
      'whatsapp' => esc_attr__( 'WhatsApp', 'saxon' ),
      'telegram' => esc_attr__( 'Telegram', 'saxon' ),
      'rss' => esc_attr__( 'RSS', 'saxon' ),
      'envelope-o' => esc_attr__( 'Email', 'saxon' ),
      'address-card-o' => esc_attr__( 'Other', 'saxon' ),
      'medium' => esc_attr__( 'Medium', 'saxon' ),
  );

  return $social_services_array;
}
endif;


/**
 * Theme homepage blocks list
 */
if(!function_exists('saxon_blocks_list')):
function saxon_blocks_list() {

  $saxon_blocks_array = array(
      'postsline' => esc_html__( '[POSTS] Posts Line #1', 'saxon' ),
      'postsline2' => esc_html__( '[POSTS] Posts Line #2', 'saxon' ),
      'largepostsslider' => esc_html__( '[POSTS] Large Posts Slider', 'saxon' ),
      'fullwidthpostsslider' => esc_html__( '[POSTS] Fullwidth Posts Slider', 'saxon' ),
      'carousel' => esc_html__( '[POSTS] Posts Carousel', 'saxon' ),
      'posthighlight' => esc_html__( '[POSTS] Post Highlight', 'saxon' ),
      'postsgrid1' => esc_html__( '[POSTS] Posts Grid #1', 'saxon' ),
      'postsgrid2' => esc_html__( '[POSTS] Posts Grid #2', 'saxon' ),
      'postsgrid3' => esc_html__( '[POSTS] Posts Grid #3', 'saxon' ),
      'postsgrid4' => esc_html__( '[POSTS] Posts Grid #4', 'saxon' ),
      'postsgrid5' => esc_html__( '[POSTS] Posts Grid #5', 'saxon' ),
      'postsgrid6' => esc_html__( '[POSTS] Posts Grid #6', 'saxon' ),
      'postsgrid7' => esc_html__( '[POSTS] Posts Grid #7', 'saxon' ),
      'postsgrid8' => esc_html__( '[POSTS] Posts Grid #8', 'saxon' ),
      'postsmasonry1' => esc_html__( '[POSTS] Posts Masonry #1', 'saxon' ),
      'postsmasonry2' => esc_html__( '[POSTS] Posts Masonry #2', 'saxon' ),
      'postsmasonry3' => esc_html__( '[POSTS] Posts Masonry #3', 'saxon' ),
      'showcase1' => esc_html__( '[POSTS] Showcase #1', 'saxon' ),
      'showcase2' => esc_html__( '[POSTS] Showcase #2', 'saxon' ),
      'showcase3' => esc_html__( '[POSTS] Showcase #3', 'saxon' ),
      'showcase4' => esc_html__( '[POSTS] Showcase #4', 'saxon' ),
      'html' => esc_html__( '[HTML] HTML Block', 'saxon' ),
      'blog' => esc_html__( '[MISC] Blog Listing', 'saxon' ),
      'subscribe' => esc_html__( '[MISC] Subscribe Block', 'saxon' ),
      'categories' => esc_html__( '[MISC] Categories Block', 'saxon' ),
      'instagram' => esc_html__( '[MISC] Instagram Block', 'saxon' ),
  );

  return $saxon_blocks_array;
}
endif;

/**
 * Theme posts types list for homepage blocks
 */
if(!function_exists('saxon_post_types_list')):
function saxon_post_types_list() {

  $saxon_post_types_array = array(
      'latest' => esc_html__( 'Latest', 'saxon' ),
      'featured' => esc_html__( 'Featured', 'saxon' ),
      'editorspicks' => esc_html__( "Editors picks", 'saxon' ),
      'promoted' => esc_html__( 'Promoted', 'saxon' ),
      'popular' => esc_html__( 'Popular', 'saxon' ),
      'liked' => esc_html__( 'Most liked', 'saxon' ),
      'random' => esc_html__( 'Random', 'saxon' ),
  );

  return $saxon_post_types_array;
}
endif;

/**
 * Author social profiles list
 */
if(!function_exists('saxon_author_social_services_list')):
function saxon_author_social_services_list() {

  $social_array = array(
    'facebook' => 'Facebook',
    'twitter' => 'Twitter',
    'vk' => 'Vkontakte',
    'google-plus' => 'Google Plus',
    'behance' => 'Behance',
    'linkedin' => 'LinkedIn',
    'pinterest' => 'Pinterest',
    'deviantart' => 'DeviantArt',
    'dribbble' => 'Dribbble',
    'flickr' => 'Flickr',
    'instagram' => 'Instagram',
    'skype' => 'Skype',
    'tumblr' => 'Tumblr',
    'twitch' => 'Twitch',
    'vimeo-square' => 'Vimeo',
    'youtube' => 'Youtube',
    'medium' => 'Medium');

  return $social_array;

}
endif;

/**
 * Set content width
 */
if(!function_exists('saxon_set_content_width')):
function saxon_set_content_width($width) {
    global $content_width;// Global here used to define new content width for global WordPress system variable

    $content_width = $width;
}
endif;

/**
 * Adds a responsive embed wrapper around oEmbed content
 */
if(!function_exists('saxon_responsive_embed')):
function saxon_responsive_embed($html, $url, $attr) {
    return $html!=='' ? '<div class="embed-container">'.$html.'</div>' : '';
}
endif;

/**
 * Load theme plugins installation.
 */
require get_template_directory() . '/inc/theme-plugins.php';

/**
 * Load theme options.
 */
require get_template_directory() . '/inc/theme-options.php';

/**
 * Load theme functions.
 */
require get_template_directory() . '/inc/theme-functions.php';

/**
 * Load theme homepage blocks.
 */
require get_template_directory() . '/inc/theme-blocks.php';

/**
 * Load theme sidebars
 */
require get_template_directory() . '/inc/theme-sidebars.php';

/**
 * Load theme AMP functions.
 */
require get_template_directory() . '/inc/theme-amp.php';

/**
 * Load theme dynamic CSS.
 */
require get_template_directory() . '/inc/theme-css.php';

/**
 * Load theme dynamic JS.
 */
require get_template_directory() . '/inc/theme-js.php';

/**
 * Theme dashboard.
 */
require get_template_directory() . '/inc/theme-dashboard/class-theme-dashboard.php';

/**
 * Load additional theme modules.
 */

# Module - Category settings
require get_template_directory() . '/inc/modules/category/category-settings.php';

# Module - Mega Menu
if(get_theme_mod('module_mega_menu', true)) {
  require get_template_directory() . '/inc/modules/mega-menu/custom-menu.php';
}
