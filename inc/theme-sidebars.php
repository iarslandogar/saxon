<?php
/**
 * Theme Sidebars
 */

if(!function_exists('saxon_sidebars_init')):
function saxon_sidebars_init() {

    register_sidebar(
      array(
        'name' => esc_html__( 'Default Blog sidebar', 'saxon' ),
        'id' => 'main-sidebar',
        'description' => esc_html__( 'Widgets in this area will be shown in the left or right site column on: Main Blog page, Archives, Search.', 'saxon' )
      )
    );

    register_sidebar(
      array(
        'name' => esc_html__( 'Single Blog Post sidebar', 'saxon' ),
        'id' => 'post-sidebar',
        'description' => esc_html__( 'Widgets in this area will be shown in the left or right site column on: Single Blog Post.', 'saxon' )
      )
    );

    register_sidebar(
      array(
        'name' => esc_html__( 'Page sidebar', 'saxon' ),
        'id' => 'page-sidebar',
        'description' => esc_html__( 'Widgets in this area will be shown in the left or right site column on: Page.', 'saxon' )
      )
    );

    register_sidebar(
      array(
        'name' => esc_html__( 'WooCommerce sidebar', 'saxon' ),
        'id' => 'woocommerce-sidebar',
        'description' => esc_html__( 'Widgets in this area will be shown in the left or right site column for woocommerce pages.', 'saxon' )
      )
    );

    register_sidebar(
      array(
        'name' => esc_html__( 'Footer sidebar #1', 'saxon' ),
        'id' => 'footer-sidebar',
        'description' => esc_html__( 'Widgets in this area will be shown in site footer in 4 columns.', 'saxon' )
      )
    );

    register_sidebar(
      array(
        'name' => esc_html__( 'Footer sidebar #2', 'saxon' ),
        'id' => 'footer-sidebar-2',
        'description' => esc_html__( 'Widgets in this area will be shown in site footer in 4 column after Footer sidebar #1.', 'saxon' )
      )
    );

    // Mega Menu sidebars
    if(get_theme_mod('module_megamenu_sidebars', 1) > 0) {
        for ($i = 1; $i <= get_theme_mod('module_megamenu_sidebars', 1); $i++) {
            register_sidebar(
              array(
                'name' => esc_html__( 'Mega Menu sidebar #', 'saxon' ).$i,
                'id' => 'megamenu_sidebar_'.$i,
                'description' => esc_html__( 'You can use this sidebar to display widgets inside megamenu items in menus.', 'saxon' )
              )
            );
        }
    }

}
endif;
add_action( 'widgets_init', 'saxon_sidebars_init' );
