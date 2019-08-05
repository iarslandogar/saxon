<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package Saxon
 */
?>
<?php

// Footer sidebar option
$show_footer_sidebar_1 = true;

if(get_theme_mod('footer_sidebar_homepage', true) == true && is_front_page()) {
  $show_footer_sidebar_1 = true;
}
if(get_theme_mod('footer_sidebar_homepage', true) == true && !is_front_page()) {
  $show_footer_sidebar_1 = false;
}

// Footer shortcode block option
$show_footer_shortcodeblock = true;

if(get_theme_mod('footer_shortcodeblock_homepage', true) == true && is_front_page()) {
  $show_footer_shortcodeblock = true;
}
if(get_theme_mod('footer_shortcodeblock_homepage', true) == true && !is_front_page()) {
  $show_footer_shortcodeblock = false;
}

// Footer shortcode block option
$show_footer_htmlblock = true;

if(get_theme_mod('footer_htmlblock_homepage', true) == true && is_front_page()) {
  $show_footer_htmlblock = true;
}
if(get_theme_mod('footer_htmlblock_homepage', true) == true && !is_front_page()) {
  $show_footer_htmlblock = false;
}

?>

<?php if ( is_active_sidebar( 'footer-sidebar' ) ) : ?>
  <?php if($show_footer_sidebar_1): ?>
  <div class="footer-sidebar-wrapper">
    <div class="footer-sidebar sidebar container">
      <ul id="footer-sidebar">
        <?php dynamic_sidebar( 'footer-sidebar' ); ?>
      </ul>
    </div>
  </div>
  <?php endif; ?>
<?php endif; ?>
<?php
// Site Above Footer Banner
saxon_banner_display('above_footer');
?>

<?php if($show_footer_shortcodeblock): ?>

<?php saxon_footer_shortcode_display(); ?>

<?php endif; ?>

<?php if($show_footer_htmlblock): ?>

<?php saxon_footer_htmlblock_display(); ?>

<?php endif; ?>

<?php

$footer_add_class = 'footer-'.get_theme_mod('footer_style', 'white');

?>

<?php if ( is_active_sidebar( 'footer-sidebar-2' ) ) : ?>
<div class="footer-sidebar-2-wrapper <?php echo esc_attr($footer_add_class); ?>">
  <div class="footer-sidebar-2 sidebar container footer-sidebar-2-container">
    <ul id="footer-sidebar-2">
      <?php dynamic_sidebar( 'footer-sidebar-2' ); ?>
    </ul>
  </div>
</div>
<?php endif; ?>

<?php if ( get_theme_mod( 'button_backtotop', true ) == true ):?>
<a class="scroll-to-top btn alt" aria-label="<?php echo esc_attr__('Scroll to top', 'saxon'); ?>" href="#top"></a>
<?php endif; ?>

<?php if(get_theme_mod('footer_socialicons', false) || get_theme_mod('footer_menu', true) || get_theme_mod('footer_copyright','', 'saxon') !== ''): ?>
<div class="footer-wrapper">
  <footer class="<?php echo esc_attr($footer_add_class); ?>">
      <div class="container">
        <div class="row">
            <?php
            // Site Footer Banner
            saxon_banner_display('footer');
            ?>

            <?php if(get_theme_mod('footer_socialicons', false)): ?>
            <div class="col-md-12 footer-social col-sm-12">
            <?php saxon_social_display(false, true); ?>
            </div>
            <?php endif; ?>

            <?php if(get_theme_mod('footer_menu', true)): ?>
            <div class="col-md-12 footer-menu" role="navigation">
            <?php
              wp_nav_menu(array(
                'theme_location'  => 'footer',
                'menu_class'      => 'footer-links',
                'fallback_cb'    => false,
                ));
            ?>
            </div>
            <?php endif; ?>
            <div class="col-md-12 col-sm-12 footer-copyright">
                <?php
                  echo wp_kses_post(get_theme_mod('footer_copyright',''));
                ?>
            </div>

        </div>
      </div>
    </footer>
</div>
<?php endif; ?>

<?php if(get_theme_mod('search_position', 'header') == 'fullscreen'):?>
<div class="search-fullscreen-wrapper">
  <div class="search-fullscreen-form">
    <div class="search-close-btn" aria-label="<?php echo esc_attr__('Close', 'saxon'); ?>"><i class="fa fa-angle-double-up" aria-hidden="true"></i>
</div>
    <?php get_template_part( 'searchform-fullscreen' ); ?>
  </div>
</div>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
