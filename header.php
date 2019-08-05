<?php
/**
 * WP Theme Header
 *
 * Displays all of the <head> section
 *
 * @package Saxon
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php saxon_topline_display(); ?>

<?php saxon_top_display(); ?>

<?php
// Site Header Banner
saxon_banner_display('below_top_menu');
?>

<?php
// Popular posts
$show_slider = true;

if(get_theme_mod('slider_homepage', true) && is_front_page()) {
  $show_slider = true;
}
if(get_theme_mod('slider_homepage', true) && !is_front_page()) {
  $show_slider = false;
}

if($show_slider) {
  saxon_blog_slider_display();
}

// Disable header
if(get_theme_mod('header_disable', false) == false):
?>
<?php
// Header Banner
saxon_banner_display('header');

// Fixed menu
$header_add_class = '';

if(get_theme_mod('header_sticky', true)) {

  $header_add_class = ' sticky-header';

}
?>
<header class="main-header clearfix<?php echo esc_attr($header_add_class); ?>">
<?php if(get_theme_mod('blog_post_reading_progress', false)): ?>
<div class="blog-post-reading-progress"></div>
<?php endif; ?>
<div class="container">
  <div class="row">
    <div class="col-md-12">

      <div class="header-left">
        <?php saxon_header_left_display(); ?>
      </div>

      <div class="header-center">
        <?php saxon_header_center_display(); ?>
      </div>

      <div class="header-right">
        <?php saxon_header_right_display(); ?>
      </div>
    </div>
  </div>

</div>
</header>
<?php endif; ?>
<?php
// Site Header Banner
saxon_banner_display('below_header');
?>
