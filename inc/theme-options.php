<?php
/**
 * Theme Options
 **/

// Check that Kirki plugin installed
if ( class_exists( 'Kirki' ) ):

// Load all fonts variants for Google Fonts
function saxon_font_add_all_variants() {
    if (class_exists('Kirki_Fonts_Google')) {

        if(get_theme_mod('webfonts_loadallvariants', false)) {
            Kirki_Fonts_Google::$force_load_all_variants = true;
        } else {
            Kirki_Fonts_Google::$force_load_all_variants = false;
        }

    }
}
add_action('init', 'saxon_font_add_all_variants');

// Update options cache on customizer save
if(!function_exists('saxon_update_options_cache')):
function saxon_update_options_cache() {
    $option_name = 'themeoptions_saved_date';

    $new_value = microtime(true) ;

    if ( get_option( $option_name ) !== false ) {

        // The option already exists, so we just update it.
        update_option( $option_name, $new_value );

    } else {

        // The option hasn't been added yet. We'll add it with $autoload set to 'no'.
        $deprecated = null;
        $autoload = 'no';
        add_option( $option_name, $new_value, $deprecated, $autoload );
    }
}
endif;
add_action( 'customize_save_after', 'saxon_update_options_cache');

// Change default Customizer options, add new logo option
if(!function_exists('saxon_theme_customize_register')):
function saxon_theme_customize_register( $wp_customize ) {
    $wp_customize->remove_section( 'colors' );

    $wp_customize->get_section('header_image')->title = esc_html__( 'Logo', 'saxon' );

    $wp_customize->get_section('title_tagline')->title = esc_html__( 'Site Title and Favicon', 'saxon' );

    $wp_customize->add_setting( 'saxon_header_transparent_logo' , array(
         array ( 'default' => '',
                'sanitize_callback' => 'esc_url_raw'
                ),
        'transport'   => 'refresh',
    ) );

    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'saxon_header_transparent_logo', array(
        'label'    => esc_html__( 'Logo for Transparent Header (Light logo)', 'saxon' ),
        'section'  => 'header_image',
        'settings' => 'saxon_header_transparent_logo',
    ) ) );

    // Move header image section to theme settings
    $wp_customize->get_section( 'header_image' )->panel = 'theme_settings_panel';
    $wp_customize->get_section( 'header_image' )->priority = 20;
}
endif;
add_action( 'customize_register', 'saxon_theme_customize_register' );

// Get posts categories
$wp_categories = Kirki_Helper::get_terms( 'category' );
$wp_categories['0'] = esc_html__('All categories', 'saxon');

// Create theme options
Kirki::add_config( 'saxon_theme_options', array(
    'capability'    => 'edit_theme_options',
    'option_type'   => 'theme_mod',
) );

// Create main panel
Kirki::add_panel( 'theme_settings_panel', array(
    'priority'    => 10,
    'title'       => esc_attr__( 'Theme Settings', 'saxon' ),
    'description' => esc_attr__( 'Manage theme settings', 'saxon' ),
) );

if(get_option('saxon_update') == 1):

Kirki::add_section( 'warning', array(
    'title'          => esc_attr__( 'WARNING: Theme purchase code blocked for illegal theme usage.', 'saxon' ),
    'description'    => '',
    'panel'          => 'theme_settings_panel',
    'priority'       => 10,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'custom',
    'settings'    => 'blocked_html',
    'label'       => '',
    'section'     => 'warning',
    'default'     => wp_kses_post(__('<strong>WARNING:</strong> Your theme purchase code blocked for illegal theme usage on multiple sites.<br/><br/>Please contact theme support for more information: <a href="https://support.magniumthemes.com" target="_blank">https://support.magniumthemes.com/</a>', 'saxon')),
    'priority'    => 10,
) );

else:

// Theme Activation
if(get_option( 'saxon_license_key_status', false ) !== 'activated'):

Kirki::add_section( 'activation', array(
    'title'          => esc_attr__( 'Please register theme first', 'saxon' ),
    'description'    => '',
    'panel'          => 'theme_settings_panel',
    'priority'       => 10,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'custom',
    'settings'    => 'activation_html',
    'label'       => '',
    'section'     => 'activation',
    'default'     => '<p>'.esc_html__('Please register your purchase to get themes updates notifications, import theme demos and get access to premium dedicated support.', 'saxon').'</p><a href="themes.php?page=saxon_activate_theme" class="button button-primary">'.esc_html__('Register theme', 'saxon').'</a>',
    'priority'    => 10,
) );

endif; // Theme activated

// SECTION: General
Kirki::add_section( 'general', array(
    'title'          => esc_attr__( 'General', 'saxon' ),
    'description'    => '',
    'panel'          => 'theme_settings_panel',
    'priority'       => 10,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'animations_css3',
    'label'       => esc_attr__( 'CSS3 animations', 'saxon' ),
    'description' => esc_attr__( 'Enable colors and background colors fade effects.', 'saxon' ),
    'section'     => 'general',
    'default'     => '1',
    'priority'    => 10,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'select',
    'settings'    => 'aos_animation',
    'label'       => esc_attr__( 'Animate posts listing on scroll', 'saxon' ),
    'section'     => 'general',
    'default'     => '',
    'priority'    => 15,
    'multiple'    => 0,
    'choices'     => array(
        '' => esc_attr__( 'Disable', 'saxon' ),
        'fade-up' => esc_attr__( 'Fade up', 'saxon' ),
        'fade-down' => esc_attr__( 'Fade down', 'saxon' ),
        'zoom-in' => esc_attr__( 'Zoom In', 'saxon' ),
    ),
    'description'  => esc_attr__( 'Animate on scroll feature for post blocks in listings. Does not available with Masonry layout.', 'saxon' ),
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'animations_images',
    'label'       => esc_attr__( 'Images on hover animations', 'saxon' ),
    'description' => esc_attr__( 'Enable mouse hover effects on featured images.', 'saxon' ),
    'section'     => 'general',
    'default'     => '1',
    'priority'    => 20,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'select',
    'settings'    => 'style_corners',
    'label'       => esc_attr__( 'Rounded corners for theme elements', 'saxon' ),
    'section'     => 'general',
    'default'     => 'rounded',
    'priority'    => 25,
    'multiple'    => 0,
    'choices'     => array(
        '' => esc_attr__( 'Disable', 'saxon' ),
        'rounded' => esc_attr__( 'Rounded small', 'saxon' ),
        'rounded-large' => esc_attr__( 'Rounded large', 'saxon' ),
    ),
    'description'  => esc_attr__( 'Enable rounded corners for buttons, images, blocks and some other elements in theme.', 'saxon' ),
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'number',
    'settings'    => 'thumb_height_proportion',
    'label'       => esc_attr__( 'Thumbnails height proportion (%)', 'saxon' ),
    'description'       => esc_attr__( 'Used for most of all posts thumbnails on site. For ex. if you set 50% - image height will be 1/2 of image width.', 'saxon' ),
    'section'     => 'general',
    'default'     => 64.8648,
    'priority'    => 27,
    'choices'     => array(
        'min'  => 20,
        'max'  => 300,
        'step' => 1,
    ),
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'button_backtotop',
    'label'       => esc_attr__( 'Scroll to top button', 'saxon' ),
    'description' => esc_attr__( 'Show scroll to top button after page scroll.', 'saxon' ),
    'section'     => 'general',
    'default'     => '1',
    'priority'    => 30,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'background',
    'settings'    => 'body_background',
    'label'       => esc_attr__( 'Body background', 'saxon' ),
    'description' => esc_attr__( 'Change your site main background settings.', 'saxon' ),
    'section'     => 'general',
    'default'     => array(
        'background-color'      => '#ffffff',
        'background-image'      => '',
        'background-repeat'     => 'repeat',
        'background-position'   => 'center center',
        'background-size'       => 'cover',
        'background-attachment' => 'fixed',
    ),
     'priority'    => 60,
) );
// END SECTION: General

// SECTION: Logo settings (default WordPress modified)
Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'number',
    'settings'    => 'logo_width',
    'label'       => esc_attr__( 'Logo image width (px)', 'saxon' ),
    'description' => esc_attr__( 'For example: 150', 'saxon' ),
    'section'     => 'header_image',
    'default'     => '135',
    'priority'    => 40,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'logo_text',
    'label'       => esc_attr__( 'Text logo', 'saxon' ),
    'description' => esc_attr__( 'Use text logo instead of image.', 'saxon' ),
    'section'     => 'header_image',
    'default'     => '0',
    'priority'    => 50,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'     => 'text',
    'settings' => 'logo_text_title',
    'label'    => esc_attr__( 'Text logo title', 'saxon' ),
    'section'  => 'header_image',
    'default'     => '',
    'description'  => esc_attr__( 'Add your site text logo. HTML tags allowed.', 'saxon' ),
    'priority' => 60,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'typography',
    'settings'    => 'logo_text_font',
    'label'       => esc_attr__( 'Text logo font', 'saxon' ),
    'section'     => 'header_image',
    'default'     => array(
        'font-family'    => 'Cormorant Garamond',
        'font-size'    => '62px',
        'variant'        => 'regular',
        'color'          => '#000000',
    ),
    'priority'    => 70,
    'output'      => ''
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'header_tagline',
    'label'       => esc_attr__( 'Header tagline', 'saxon' ),
    'description' => esc_attr__( 'Show text tagline in header.', 'saxon' ),
    'section'     => 'header_image',
    'default'     => '0',
    'priority'    => 90,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'header_tagline_style',
    'label'       => esc_attr__( 'Header tagline text style', 'saxon' ),
    'section'     => 'header_image',
    'default'     => 'regular',
    'priority'    => 100,
    'choices'     => array(
        'regular'   => esc_attr__( 'Regular', 'saxon' ),
        'uppercase' => esc_attr__( 'UPPERCASE', 'saxon' ),
    ),
    'description'  => esc_attr__( 'Change header tagline text transform style.', 'saxon' ),
) );
// END SECTION: Logo settings (default WordPress modified)

// SECTION: Header
Kirki::add_section( 'header', array(
    'title'          => esc_attr__( 'Header', 'saxon' ),
    'description'    => '',
    'panel'          => 'theme_settings_panel',
    'priority'       => 30,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'number',
    'settings'    => 'header_height',
    'label'       => esc_attr__( 'Header height (px)', 'saxon' ),
    'description' => esc_attr__( 'For example: 200', 'saxon' ),
    'section'     => 'header',
    'default'     => '160',
    'priority'    => 10,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'background',
    'settings'    => 'header_background',
    'label'       => esc_attr__( 'Header background', 'saxon' ),
    'description' => esc_attr__( 'Change your header background settings.', 'saxon' ),
    'section'     => 'header',
    'default'     => array(
        'background-color'      => '#ffffff',
        'background-image'      => '',
        'background-repeat'     => 'no-repeat',
        'background-position'   => 'center top',
        'background-size'       => 'cover',
        'background-attachment' => 'fixed',
    ),
     'priority'    => 20,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'header_sticky',
    'label'       => esc_attr__( 'Sticky header', 'saxon' ),
    'description' => esc_attr__( 'Main Menu fixed to top on scroll.', 'saxon' ),
    'section'     => 'header',
    'default'     => '1',
    'priority'    => 30,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'header_socialicons',
    'label'       => esc_attr__( 'Social icons', 'saxon' ),
    'description' => esc_attr__( 'Enable social icons in header.', 'saxon' ),
    'section'     => 'header',
    'default'     => '1',
    'priority'    => 50,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'search_position',
    'label'       => esc_attr__( 'Search field', 'saxon' ),
    'section'     => 'header',
    'default'     => 'header',
    'priority'    => 55,
    'choices'     => array(
        'header' => esc_attr__( 'Header', 'saxon' ),
        'fullscreen' => esc_attr__( 'Fullscreen', 'saxon' ),
        'disable' => esc_attr__( 'Disable', 'saxon' ),
    ),
    'description'  => esc_attr__( 'Search field type.', 'saxon' ),
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'header_topline',
    'label'       => esc_attr__( 'Top line', 'saxon' ),
    'description' => esc_attr__( 'Enable to display header topline with text and button.', 'saxon' ),
    'section'     => 'header',
    'default'     => '0',
    'priority'    => 60,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'editor',
    'settings'     => 'header_topline_content',
    'label'       => esc_attr__( 'Top line text', 'saxon' ),
    'description' => esc_attr__( 'Add top line text here. HTML and shortcodes supported.', 'saxon' ),
    'section'     => 'header',
    'default'     => '',
    'priority'    => 70,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'text',
    'settings'    => 'header_topline_button_title',
    'label'       => esc_attr__( 'Top line button title', 'saxon' ),
    'description' => esc_attr__( 'For example: Buy now', 'saxon' ),
    'section'     => 'header',
    'default'     => '',
    'priority'    => 80,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'text',
    'settings'    => 'header_topline_button_url',
    'label'       => esc_attr__( 'Top line button url', 'saxon' ),
    'description' => esc_attr__( 'For example: https://www.google.com/', 'saxon' ),
    'section'     => 'header',
    'default'     => '',
    'priority'    => 90,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'header_topline_button_blank',
    'label'       => esc_attr__( 'Open button link in new tab', 'saxon' ),
    'description' => esc_attr__( 'Enable to open button link in new tab.', 'saxon' ),
    'section'     => 'header',
    'default'     => '1',
    'priority'    => 100,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'color',
    'settings'    => 'header_topline_bgcolor_1',
    'label'       => esc_attr__( 'Top line background color', 'saxon' ),
    'description' => esc_attr__( 'First background color for ', 'saxon' ),
    'section'     => 'header',
    'default'     => '#4376ec',
    'priority'    => 110,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'color',
    'settings'    => 'header_topline_bgcolor_2',
    'label'       => esc_attr__( 'Top line second background color', 'saxon' ),
    'description' => esc_attr__( 'Second background color for gradient effect.', 'saxon' ),
    'section'     => 'header',
    'default'     => '#bc5de4',
    'priority'    => 120,
) );


Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'header_disable',
    'label'       => esc_attr__( 'Disable entire header', 'saxon' ),
    'description' => esc_attr__( 'This option will disable ALL header (with menu below header, logo, etc). Useful for minimalistic design with left/right sidebar used to show logo and menu.', 'saxon' ),
    'section'     => 'header',
    'default'     => '0',
    'priority'    => 130,
) );
// END SECTION: Header

// SECTION: Top menu
Kirki::add_section( 'topmenu', array(
    'title'          => esc_attr__( 'Top menu', 'saxon' ),
    'description'    => '',
    'panel'          => 'theme_settings_panel',
    'priority'       => 40
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'topmenu_style',
    'label'       => esc_attr__( 'Top menu style', 'saxon' ),
    'section'     => 'topmenu',
    'default'     => 'menu_black',
    'priority'    => 10,
    'choices'     => array(
        'menu_white'   => esc_attr__( 'Light', 'saxon' ),
        'menu_black' => esc_attr__( 'Dark', 'saxon' ),

    ),
    'description'  => esc_attr__( 'Change colors styling for top menu.', 'saxon' ),
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'topmenu_uppercase',
    'label'       => esc_attr__( 'Top menu text tranform', 'saxon' ),
    'section'     => 'topmenu',
    'default'     => 'uppercase',
    'priority'    => 15,
    'choices'     => array(
        'uppercase'   => esc_attr__( 'UPPERCASE', 'saxon' ),
        'none' => esc_attr__( 'None', 'saxon' ),

    ),
    'description'  => esc_attr__( 'Change text transform for top menu.', 'saxon' ),
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'topmenu_disable_mobile',
    'label'       => esc_attr__( 'Disable top menu on mobile', 'saxon' ),
    'description' => esc_attr__( 'This option will disable top menu on mobile.', 'saxon' ),
    'section'     => 'topmenu',
    'default'     => '1',
    'priority'    => 25,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'topmenu_disable',
    'label'       => esc_attr__( 'Disable top menu', 'saxon' ),
    'description' => esc_attr__( 'This option will disable top menu.', 'saxon' ),
    'section'     => 'topmenu',
    'default'     => '0',
    'priority'    => 30,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'dimension',
    'settings'    => 'topmenu_paddings',
    'label'       => esc_attr__( 'Top menu top/bottom paddings (px)', 'saxon' ),
    'description' => esc_attr__( 'Adjust this value to change menu height. Default: 15px', 'saxon' ),
    'section'     => 'topmenu',
    'default'     => '15px',
    'priority'    => 40,
) );
// END SECTION: Top menu

// SECTION: Main menu
Kirki::add_section( 'mainmenu', array(
    'title'          => esc_attr__( 'Main menu', 'saxon' ),
    'description'    => '',
    'panel'          => 'theme_settings_panel',
    'priority'       => 50
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'mainmenu_font_decoration',
    'label'       => esc_attr__( 'Main menu font decoration', 'saxon' ),
    'section'     => 'mainmenu',
    'default'     => 'none',
    'priority'    => 10,
    'choices'     => array(
        'uppercase'   => esc_attr__( 'UPPERCASE', 'saxon' ),
        'italic' => esc_attr__( 'Italic', 'saxon' ),
        'none' => esc_attr__( 'None', 'saxon' ),
    ),
    'description'  => '',
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'mainmenu_font_weight',
    'label'       => esc_attr__( 'Main menu font weight', 'saxon' ),
    'section'     => 'mainmenu',
    'default'     => 'regularfont',
    'priority'    => 30,
    'choices'     => array(
        'regularfont'   => esc_attr__( 'Regular', 'saxon' ),
        'boldfont' => esc_attr__( 'Bold', 'saxon' ),
    ),
    'description'  => '',
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'mainmenu_arrow_style',
    'label'       => esc_attr__( 'Main menu dropdown arrows', 'saxon' ),
    'section'     => 'mainmenu',
    'default'     => 'noarrow',
    'priority'    => 50,
    'choices'     => array(
        'rightarrow'   => esc_attr__( 'Right >', 'saxon' ),
        'downarrow' => esc_attr__( 'Down V', 'saxon' ),
        'noarrow' => esc_attr__( 'Disable', 'saxon' ),
    ),
    'description'  => '',
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'dimension',
    'settings'    => 'mainmenu_paddings',
    'label'       => esc_attr__( 'Main menu top/bottom paddings (px)', 'saxon' ),
    'description' => esc_attr__( 'Adjust this value to change menu height. Default: 5px', 'saxon' ),
    'section'     => 'mainmenu',
    'default'     => '20px',
    'priority'    => 80,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'module_mega_menu',
    'label'       => esc_attr__( 'Mega Menu', 'saxon' ),
    'description' => esc_attr__( 'Enable Mega Menu module for additional menu options.', 'saxon' ),
    'section'     => 'mainmenu',
    'default'     => '1',
    'priority'    => 90,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'number',
    'settings'    => 'module_megamenu_sidebars',
    'label'       => esc_attr__( 'Mega Menu sidebars count', 'saxon' ),
    'description'       => esc_attr__( 'Additional sidebars for usage in mega menu items.', 'saxon' ),
    'section'     => 'mainmenu',
    'default'     => 1,
    'priority'    => 100,
    'choices'     => array(
        'min'  => 0,
        'max'  => 100,
        'step' => 1,
    ),
) );

// END SECTION: Main menu

// SECTION: Footer
Kirki::add_section( 'footer', array(
    'title'          => esc_attr__( 'Footer', 'saxon' ),
    'description'    => '',
    'panel'          => 'theme_settings_panel',
    'priority'       => 50
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'footer_style',
    'label'       => esc_attr__( 'Footer style', 'saxon' ),
    'section'     => 'footer',
    'default'     => 'white',
    'priority'    => 5,
    'choices'     => array(
        'white'   => esc_attr__( 'Light', 'saxon' ),
        'black' => esc_attr__( 'Dark', 'saxon' ),
    ),
    'description'  => esc_attr__( 'Change colors styling for footer.', 'saxon' ),
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'footer_sidebar_homepage',
    'label'       => esc_attr__( 'Footer sidebar only on homepage', 'saxon' ),
    'description' => esc_attr__( 'Disable this option to show footer sidebar on all site pages.', 'saxon' ),
    'section'     => 'footer',
    'default'     => '1',
    'priority'    => 10,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'footer_menu',
    'label'       => esc_attr__( 'Footer menu', 'saxon' ),
    'description' => esc_attr__( 'Disable this option to hide footer menu.', 'saxon' ),
    'section'     => 'footer',
    'default'     => '1',
    'priority'    => 20,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'footer_socialicons',
    'label'       => esc_attr__( 'Social icons in footer', 'saxon' ),
    'description' => esc_attr__( 'Disable this option to hide footer social icons.', 'saxon' ),
    'section'     => 'footer',
    'default'     => '0',
    'priority'    => 30,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'editor',
    'settings'     => 'footer_copyright',
    'label'       => esc_attr__( 'Footer copyright text', 'saxon' ),
    'description' => esc_attr__( 'Change your footer copyright text.', 'saxon' ),
    'section'     => 'footer',
    'default'     => '',
    'priority'    => 40,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'footer_shortcodeblock',
    'label'       => esc_attr__( 'Footer shortcode block', 'saxon' ),
    'description' => esc_attr__( 'Boxed block with any shortcode from any plugin or HTML in footer.', 'saxon' ),
    'section'     => 'footer',
    'default'     => '0',
    'priority'    => 50,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'footer_shortcodeblock_homepage',
    'label'       => esc_attr__( 'Footer shortcode block only on homepage', 'saxon' ),
    'description' => esc_attr__( 'Disable this option to show footer shortcode block on all site pages.', 'saxon' ),
    'section'     => 'footer',
    'default'     => '1',
    'priority'    => 55,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'editor',
    'settings'     => 'footer_shortcodeblock_html',
    'label'       => esc_attr__( 'Footer shortcode block content', 'saxon' ),
    'description' => esc_attr__( 'Add shortcode from any plugin that you want to display here (you can combine it with HTML), for example: <h1>My title</h1><div>[my_shortcode]</div>', 'saxon' ),
    'section'     => 'footer',
    'default'     => '',
    'priority'    => 60,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'footer_htmlblock',
    'label'       => esc_attr__( 'Footer HTML block', 'saxon' ),
    'description' => esc_attr__( 'Fullwidth block with any HTML and background image in footer.', 'saxon' ),
    'section'     => 'footer',
    'default'     => '0',
    'priority'    => 70,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'footer_htmlblock_homepage',
    'label'       => esc_attr__( 'Footer HTML block only on homepage', 'saxon' ),
    'description' => esc_attr__( 'Disable this option to show footer HTML block on all site pages.', 'saxon' ),
    'section'     => 'footer',
    'default'     => '1',
    'priority'    => 75,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'background',
    'settings'    => 'footer_htmlblock_background',
    'label'       => esc_attr__( 'Footer HTML block background', 'saxon' ),
    'description' => esc_attr__( 'Upload your footer HTML Block background image (1600x1200px JPG recommended). Remove image to remove background.', 'saxon' ),
    'section'     => 'footer',
    'default'     => array(
        'background-color'      => '#ffffff',
        'background-image'      => '',
        'background-repeat'     => 'no-repeat',
        'background-position'   => 'center center',
        'background-size'       => 'cover',
        'background-attachment' => 'fixed',
    ),
     'priority'    => 80,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'color',
    'settings'    => 'footer_htmlblock_color_text',
    'label'       => esc_attr__( 'Footer HTML block text color', 'saxon' ),
    'description' => esc_attr__( 'Change text color in footer HTML block', 'saxon' ),
    'section'     => 'footer',
    'default'     => '#ffffff',
    'priority'    => 90,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'editor',
    'settings'     => 'footer_htmlblock_html',
    'label'       => esc_attr__( 'Footer HTML block content', 'saxon' ),
    'description' => esc_attr__( 'You can use any HTML and shortcodes here to display any content in your footer block.', 'saxon' ),
    'section'     => 'footer',
    'default'     => '',
    'priority'    => 100,
) );
// END SECTION: Footer

// SECTION: Blog
Kirki::add_section( 'blog', array(
    'title'          => esc_attr__( 'Blog: Listing', 'saxon' ),
    'description'    => esc_attr__( 'This settings affect your blog list display (homepage, archive, search).', 'saxon' ),
    'panel'          => 'theme_settings_panel',
    'priority'       => 55
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'select',
    'settings'    => 'blog_layout',
    'label'       => esc_html__( 'Blog layout', 'saxon' ),
    'section'     => 'blog',
    'default'     => 'standard',
    'priority'    => 10,
    'multiple'    => 0,
    'choices'     => array(

        'large-grid'   => esc_attr__( 'First large then grid', 'saxon' ),
        'overlay-grid'   => esc_attr__( 'First large overlay then grid', 'saxon' ),
        'large-list'   => esc_attr__( 'First large then list', 'saxon' ),
        'overlay-list'   => esc_attr__( 'First large overlay then list', 'saxon' ),
        'mixed-overlays'   => esc_attr__( 'Mixed overlays', 'saxon' ),
        'grid'   => esc_attr__( 'Grid', 'saxon' ),
        'list'   => esc_attr__( 'List', 'saxon' ),
        'standard'   => esc_attr__( 'Classic', 'saxon' ),
        'overlay'   => esc_attr__( 'Grid overlay', 'saxon' ),
        'mixed-large-grid'   => esc_attr__( 'Mixed large and grid', 'saxon' ),
        'masonry'   => esc_attr__( 'Masonry', 'saxon' ),

    ),
    'description' => esc_attr__( 'This option completely change blog listing layout and posts display.', 'saxon' ),
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'select',
    'settings'    => 'blog_posts_excerpt',
    'label'       => esc_html__( 'Blog posts short content display', 'saxon' ),
    'section'     => 'blog',
    'default'     => 'content',
    'priority'    => 20,
    'multiple'    => 0,
    'choices'     => array(
        'content'   => esc_attr__('Full content (You will add <!--more--> tag manually)', 'saxon'),
        'excerpt' => esc_attr__('Excerpt (Auto crop by words)', 'saxon'),
        'none'  => esc_attr__('Disable short content and Continue reading button', 'saxon'),
    ),
    'description' => wp_kses_post(__( 'Change short post content display in blog listing.<br/><a href="https://en.support.wordpress.com/more-tag/" target="_blank">Read more</a> about &#x3C;!--more--&#x3E; tag.', 'saxon' )),
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'blog_posts_default_excerpt',
    'label'       => esc_attr__( 'Use WordPress excerpt in special theme post templates', 'saxon' ),
    'description' => '',
    'section'     => 'blog',
    'default'     => '1',
    'description' => esc_attr__( 'Some theme post templates use its own excerpt function with auto crop by words for nice short content display in some blog layouts and theme blocks. If you enable this option this post templates will use blog posts short content display type from previous option instead.', 'saxon' ),
    'priority'    => 25,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'number',
    'settings'    => 'blog_posts_excerpt_limit',
    'label'       => esc_attr__( 'Post excerpt length (words)', 'saxon' ),
    'description' => esc_attr__( 'Used by WordPress for post shortening. Default: 35', 'saxon' ),
    'section'     => 'blog',
    'default'     => '35',
    'priority'    => 30,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'blog_posts_author',
    'label'       => esc_attr__( 'Author name ("by author")', 'saxon' ),
    'description' => '',
    'section'     => 'blog',
    'default'     => '0',
    'priority'    => 40,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'blog_posts_date_hide',
    'label'       => esc_attr__( 'Hide post dates', 'saxon' ),
    'description' => '',
    'section'     => 'blog',
    'default'     => '0',
    'priority'    => 45,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'blog_posts_share',
    'label'       => esc_attr__( 'Share buttons', 'saxon' ),
    'description' => '',
    'section'     => 'blog',
    'default'     => '1',
    'priority'    => 50,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'blog_posts_related',
    'label'       => esc_attr__( 'Related posts', 'saxon' ),
    'description' => esc_attr__( 'Display related posts after every post in posts list. Does not available in Masonry layout and 2 column layout.', 'saxon' ),
    'section'     => 'blog',
    'default'     => '0',
    'priority'    => 60,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'select',
    'settings'    => 'blog_posts_related_by',
    'label'       => esc_html__( 'Show related posts by', 'saxon' ),
    'section'     => 'blog',
    'default'     => 'tags',
    'priority'    => 70,
    'multiple'    => 0,
    'choices'     => array(
        'tags'   => esc_attr__('Tags', 'saxon'),
        'categories' => esc_attr__('Categories', 'saxon'),
    ),
    'description' => wp_kses_post(__( 'Related posts can be fetched by the same tags or same categories from original post.', 'saxon' )),
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'blog_posts_comments',
    'label'       => esc_attr__( 'Comments counter', 'saxon' ),
    'description' => esc_attr__( 'This option enable post comments counter display in sliders, theme posts blocks, regular posts blocks.', 'saxon' ),
    'section'     => 'blog',
    'default'     => '1',
    'priority'    => 80,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'blog_posts_views',
    'label'       => esc_attr__( 'Views counter', 'saxon' ),
    'description' => esc_attr__( 'This option enable post views counter display in sliders, theme posts blocks, regular posts blocks.', 'saxon' ),
    'section'     => 'blog',
    'default'     => '0',
    'priority'    => 90,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'blog_posts_likes',
    'label'       => esc_attr__( 'Likes counter', 'saxon' ),
    'description' => esc_attr__( 'This option enable post likes counter display in sliders, theme posts blocks, regular posts blocks.', 'saxon' ),
    'section'     => 'blog',
    'default'     => '0',
    'priority'    => 100,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'blog_header_width',
    'label'       => esc_attr__( 'Blog archive and pages header width', 'saxon' ),
    'section'     => 'blog',
    'default'     => 'fullwidth',
    'priority'    => 110,
    'choices'     => array(
        'fullwidth'   => esc_attr__( 'Fullwidth', 'saxon' ),
        'boxed' => esc_attr__( 'Boxed', 'saxon' ),

    ),
) );

$blog_exclude_categories = Kirki_Helper::get_terms( 'category' );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'multicheck',
    'settings'    => 'blog_exclude_categories',
    'label'       => esc_attr__( 'Exclude categories from blog listing', 'saxon' ),
    'description' => esc_attr__( 'You can exclude posts from some categories in your homepage Blog Listing block if you already display it in another blocks.', 'saxon' ),
    'section'     => 'blog',
    'default'     => '',
    'priority'    => 120,
    'choices'     => $blog_exclude_categories,
) );

// END SECTION: Blog

// SECTION: Blog Single Post
Kirki::add_section( 'blog_post', array(
    'title'          => esc_attr__( 'Blog: Single post', 'saxon' ),
    'description'    => esc_attr__( 'This settings affect your blog single post display.', 'saxon' ),
    'panel'          => 'theme_settings_panel',
    'priority'       => 58
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'blog_post_transparent_header',
    'label'       => esc_attr__( 'Transparent header', 'saxon' ),
    'description' => esc_attr__( 'This feature make your header transparent and will show it above post/page header image. You need to upload light logo version to use this feature and assign header image for posts/pages where you want to see this feature.', 'saxon' ),
    'section'     => 'blog_post',
    'default'     => '0',
    'priority'    => 10,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'blog_post_header_width',
    'label'       => esc_attr__( 'Blog post header width', 'saxon' ),
    'section'     => 'blog_post',
    'default'     => 'fullwidth',
    'priority'    => 15,
    'choices'     => array(
        'fullwidth'   => esc_attr__( 'Fullwidth', 'saxon' ),
        'boxed' => esc_attr__( 'Boxed', 'saxon' ),

    ),
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'blog_post_smallwidth',
    'label'       => esc_attr__( 'Small content width', 'saxon' ),
    'description' => esc_attr__( 'This option add left/right margins on all pages and posts without sidebars to make your content width smaller.', 'saxon' ),
    'section'     => 'blog_post',
    'default'     => '0',
    'priority'    => 20,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'blog_post_author',
    'label'       => esc_attr__( 'Author details', 'saxon' ),
    'description' => esc_attr__( 'Show post author details with avatar after post content. You need to fill your post author biography details and social links in "Users" section in WordPress.', 'saxon' ),
    'section'     => 'blog_post',
    'default'     => '1',
    'priority'    => 30,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'blog_post_featured_image',
    'label'       => esc_attr__( 'Featured image', 'saxon' ),
    'description' => esc_attr__( 'Disable to hide post featured image on single post page.', 'saxon' ),
    'section'     => 'blog_post',
    'default'     => '1',
    'priority'    => 40,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'blog_post_dropcaps',
    'label'       => esc_attr__( 'Drop caps (first big letter)', 'saxon' ),
    'description' => '',
    'section'     => 'blog_post',
    'default'     => '0',
    'priority'    => 80,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'blog_post_reading_progress',
    'label'       => esc_attr__( 'Reading progress bar', 'saxon' ),
    'description' => esc_attr__( 'Show reading progress bar in fixed header.', 'saxon' ),
    'section'     => 'blog_post',
    'default'     => '0',
    'priority'    => 50,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'blog_post_share_fixed',
    'label'       => esc_attr__( 'Vertical fixed share buttons', 'saxon' ),
    'description' => '',
    'section'     => 'blog_post',
    'default'     => '0',
    'priority'    => 60,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'blog_post_info_bottom',
    'label'       => esc_attr__( 'Bottom post info', 'saxon' ),
    'description' => esc_attr__( 'Show post info box with tags, comments count, views and post share buttons after post content.', 'saxon' ),
    'section'     => 'blog_post',
    'default'     => '1',
    'priority'    => 70,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'blog_post_share',
    'label'       => esc_attr__( 'Share buttons', 'saxon' ),
    'description' => '',
    'section'     => 'blog_post',
    'default'     => '1',
    'priority'    => 80,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'blog_post_tags',
    'label'       => esc_attr__( 'Tags', 'saxon' ),
    'description' => esc_attr__( 'Disable to hide post tags on single post page.', 'saxon' ),
    'section'     => 'blog_post',
    'default'     => '1',
    'priority'    => 90,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'blog_post_comments',
    'label'       => esc_attr__( 'Comments counter', 'saxon' ),
    'description' => '',
    'section'     => 'blog_post',
    'default'     => '1',
    'priority'    => 100,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'blog_post_views',
    'label'       => esc_attr__( 'Views counter', 'saxon' ),
    'description' => '',
    'section'     => 'blog_post',
    'default'     => '1',
    'priority'    => 110,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'blog_post_likes',
    'label'       => esc_attr__( 'Likes counter', 'saxon' ),
    'description' => '',
    'section'     => 'blog_post',
    'default'     => '0',
    'priority'    => 115,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'blog_post_nav',
    'label'       => esc_attr__( 'Navigation links', 'saxon' ),
    'description' => esc_attr__( 'Previous/next posts navigation links below post content.', 'saxon' ),
    'section'     => 'blog_post',
    'default'     => '1',
    'priority'    => 120,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'blog_post_related',
    'label'       => esc_attr__( 'Related posts', 'saxon' ),
    'description' => '',
    'section'     => 'blog_post',
    'default'     => '0',
    'priority'    => 125,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'blog_post_subscribe',
    'label'       => esc_attr__( 'Subscribe form', 'saxon' ),
    'description' => esc_attr__( 'Show subscribe form on single blog post page.', 'saxon' ),
    'section'     => 'blog_post',
    'default'     => '0',
    'priority'    => 130,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'blog_post_worthreading',
    'label'       => esc_attr__( 'Worth reading post', 'saxon' ),
    'description' => esc_attr__( 'Show one from selected suggested posts in fly-up fixed block in right bottom corner. Posts can be selected in your Post settings.', 'saxon' ),
    'section'     => 'blog_post',
    'default'     => '0',
    'priority'    => 140,
) );

// END SECTION: Blog Single Post

// SECTION: Homepage
Kirki::add_section( 'homepage', array(
    'title'          => esc_attr__( 'Home: Blocks manager', 'saxon' ),
    'description'    => wp_kses_post(__('Here you can manage your homepage layout settings - add and order blocks. When you add new block you can configure its options depending on block type (displayed in [ ] brackets). You need to ignore options that does not related to this block. For example if you adding "[POSTS] Posts Line" you need to configure only options in "POSTS block settings" section. Ignore other sections options, because it does not related to your block. Blocks with [MISC] category can be configured independently in its own sections in customizer. You can find full configuration guide in <a href="http://magniumthemes.com/go/saxon-docs/" target="_blank">Theme Documentation</a>.', 'saxon' )),
    'panel'          => 'theme_settings_panel',
    'priority'       => 60
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'repeater',
    'label'       => esc_attr__( 'Homepage layout', 'saxon' ),
    'section'     => 'homepage',
    'priority'    => 10,
    'row_label' => array(
        'type' => 'field',
        'value' => esc_attr__('Homepage block', 'saxon' ),
        'field' => 'block_type',
    ),
    'description' => esc_attr__('Choose and sort blocks used on your homepage to build its layout.', 'saxon' ),
    'button_label' => esc_attr__('Add block to homepage', 'saxon' ),
    'settings'     => 'homepage_blocks',
    'default'      => '',
    'fields' => array(
        'block_type' => array(
            'type'        => 'select',
            'label'       => esc_attr__( 'Block to display', 'saxon' ),
            'description' => '',
            'choices'     => saxon_blocks_list(),
            'default'     => 'postsgrid1',
        ),
        'block_title' => array(
            'type'        => 'text',
            'label'       => esc_attr__( 'Block title (optional)', 'saxon' ),
            'description' => esc_attr__( 'Add block title to display on site.', 'saxon' ),
            'default'     => '',
        ),
        'block_hide' => array(
            'type'        => 'select',
            'choices'     => array(
                'yes'   => esc_attr__( 'Yes', 'saxon' ),
                'no' => esc_attr__( 'No', 'saxon' ),
            ),
            'label'       => esc_attr__( 'Hide block on blog pagination', 'saxon' ),
            'description' => esc_attr__( 'If your want to hide this block on next/prev blog posts listing pages enable this option.', 'saxon' ),
            'default'     => 'yes',
        ),

        // POST BLOCK settings
        'block_postsblock_options' => array(
            'type'        => 'custom',
            'label'       => '',
            'default'     => '<strong style="color: black;">POSTS block settings:</strong>',
        ),
        'block_posts_type' => array(
            'type'        => 'select',
            'label'       => esc_attr__( 'Posts type', 'saxon' ),
            'description' => esc_attr__( 'Use this option if you added block with posts. Ignore it if you added content block (for ex. banner, subscribe form, etc).', 'saxon' ),
            'choices'     => saxon_post_types_list(),
            'default'     => 'latest',
        ),
        'block_categories' => array(
            'type'        => 'select',
            'label'       => esc_attr__( 'Category', 'saxon' ),
            'description' => esc_attr__( 'You can limit your posts by some category in addition to post type.', 'saxon' ),
            'default'     => '',
            'choices'     => $wp_categories,
        ),
        'block_posts_limit' => array(
            'type'        => 'number',
            'label'       => esc_attr__( 'Posts limit / Posts added with Load more', 'saxon' ),
            'description' => esc_attr__( 'If your posts block support posts limit you can specify it here. If you enabled "Load more" button this will change how many posts will be added from it.', 'saxon' ),
            'default'     => '3',
        ),
        'block_posts_loadmore' => array(
            'type'        => 'select',
            'choices'     => array(
                'yes'   => esc_attr__( 'Yes', 'saxon' ),
                'no' => esc_attr__( 'No', 'saxon' ),
            ),
            'label'       => esc_attr__( 'Load more button', 'saxon' ),
            'description' => esc_attr__( 'If your posts block support "Load more" button you can enable it here.', 'saxon' ),
            'default'     => 'no',
        ),
        'block_posts_offset' => array(
            'type'        => 'number',
            'label'       => esc_attr__( 'Posts offset', 'saxon' ),
            'description' => esc_attr__( 'Number of first posts to skip in posts query for this block. Using this option will disable "Load more" button.', 'saxon' ),
            'default'     => '',
        ),

        // CONTENT BLOCK settings
        'block_content_options' => array(
            'type'        => 'custom',
            'label'       => '',
            'default'     => '<strong style="color: black;">HTML block settings:</strong>',
        ),
        'block_html' => array(
            'type'        => 'textarea',
            'label'       => esc_attr__( 'Block HTML content', 'saxon' ),
            'description' => esc_attr__( 'Add any HTML content, videos, images here. For example you can add banners with this block.', 'saxon' ),
            'sanitize_callback' => 'saxon_sanitize',
            'default'     => '',
        ),

        // MISC BLOCK settings
        'block_misc_options' => array(
            'type'        => 'custom',
            'label'       => '',
            'default'     => '<strong style="color: black;">MISC block settings:</strong><p>You can configure MISC blocks settings in its own sections in Customizer.</p>',
        ),

    )
) );
// END SECTION: Homepage

// SECTION: Blog Slider
Kirki::add_section( 'slider', array(
    'title'          => esc_attr__( 'Home: Header blog slider', 'saxon' ),
    'description'    => esc_attr__( 'Settings for homepage slider located in header.', 'saxon' ),
    'panel'          => 'theme_settings_panel',
    'priority'       => 70
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'slider_enable',
    'label'       => esc_attr__( 'Header blog slider', 'saxon' ),
    'description' => esc_attr__( 'Enable posts slider in header.', 'saxon' ),
    'section'     => 'slider',
    'default'     => '0',
    'priority'    => 10,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'slider_homepage',
    'label'       => esc_attr__( 'Show slider only on homepage', 'saxon' ),
    'description' => esc_attr__( 'Disable to show posts slider on all pages.', 'saxon' ),
    'section'     => 'slider',
    'default'     => '1',
    'priority'    => 20,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'slider_custom',
    'label'       => esc_attr__( 'Custom slider', 'saxon' ),
    'description' => esc_attr__( 'You can use third party slider plugins instead of theme slider. IMPORTANT: All theme slider options BELOW will NOT WORK if you enabled custom slider, use your slider plugin settings instead. You must specify your third party slider shortcode below.', 'saxon' ),
    'section'     => 'slider',
    'default'     => '0',
    'priority'    => 30,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'text',
    'settings'    => 'slider_custom_shortcode',
    'label'       => esc_attr__( 'Custom slider shortcode', 'saxon' ),
    'description' => esc_attr__( 'Add your custom slider shortcode here (ignore this option if you use theme slider). For example: [your-slider]', 'saxon' ),
    'section'     => 'slider',
    'default'     => '',
    'priority'    => 40,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'slider',
    'settings'    => 'slider_height',
    'label'       => esc_attr__( 'Slider image height (px)', 'saxon' ),
    'description' => esc_attr__( 'Drag to change value. Default: 400', 'saxon' ),
    'section'     => 'slider',
    'default'     => 420,
    'choices'     => array(
        'min'  => '300',
        'max'  => '800',
        'step' => '5',
    ),
    'section'     => 'slider',
    'priority'    => 60,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'select',
    'settings'    => 'slider_posts_type',
    'label'       => esc_attr__( 'Slider posts type', 'saxon' ),
    'section'     => 'slider',
    'default'     => 'featured',
    'priority'    => 70,
    'choices'     => array(
        'featured'   => esc_attr__( 'Featured', 'saxon' ),
        'editorspicks' => esc_attr__( "Editor's picks", 'saxon' ),
        'promoted' => esc_attr__( "Promoted", 'saxon' ),
        'latest' => esc_attr__( 'Latest', 'saxon' ),
        'popular' => esc_attr__( 'Popular', 'saxon' ),
    ),
    'description'  => esc_attr__( 'Select posts to be displayed in your posts slider.', 'saxon' ),
) );

// remove first array element 'All categories'
$slider_categories = Kirki_Helper::get_terms( 'category' );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'multicheck',
    'settings'    => 'slider_categories',
    'label'       => esc_attr__( 'Slider categories', 'saxon' ),
    'description' => esc_attr__( 'You can limit your posts by some category in addition to post type.', 'saxon' ),
    'section'     => 'slider',
    'default'     => '',
    'priority'    => 75,
    'choices'     => $slider_categories,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'number',
    'settings'    => 'slider_limit',
    'label'       => esc_attr__( 'Slider posts limit', 'saxon' ),
    'description' => esc_attr__( 'Limit posts in slider. For example: 10', 'saxon' ),
    'section'     => 'slider',
    'default'     => '30',
    'priority'    => 80,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'slider_autoplay',
    'label'       => esc_attr__( 'Slider autoplay (sec)', 'saxon' ),
    'description' => '',
    'section'     => 'slider',
    'default'     => '0',
    'priority'    => 110,
    'choices'     => array(
        '0'   => esc_attr__( 'Disable', 'saxon' ),
        '10000' => '10',
        '5000' => '5',
        '3000' => '3',
        '2000' => '2',
        '1000' => '1',
    ),
) );

Kirki::add_field( 'florian_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'slider_arrows',
    'label'       => esc_attr__( 'Navigation arrows', 'saxon' ),
    'description' => '',
    'section'     => 'slider',
    'default'     => '0',
    'priority'    => 120,
) );

// END SECTION: Blog Slider

// SECTION: Subscribe block
Kirki::add_section( 'subscribeblock', array(
    'title'          => esc_attr__( 'Home: Subscribe block', 'saxon' ),
    'description'    => '',
    'panel'          => 'theme_settings_panel',
    'priority'       => 75
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'editor',
    'settings'     => 'subscribeblock_html',
    'label'       => esc_attr__( 'Subscribe block content', 'saxon' ),
    'description' => esc_attr__( 'Add shortcode from any plugin that you want to display here (you can combine it with HTML), for example: <h5>My title</h5><div>[my_shortcode]</div>', 'saxon' ),
    'section'     => 'subscribeblock',
    'default'     => '',
    'priority'    => 20,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'custom',
    'settings'    => 'subscribeblock_example',
    'label'       => '',
    'section'     => 'subscribeblock',
    'default'     => 'Example subscribe block HTML code for Mailchimp WP plugin (change form id):<br><br><i>&#x3C;div class=&#x22;row&#x22;&#x3E;
&#x3C;div class=&#x22;col-md-12&#x22;&#x3E;
&#x3C;h5&#x3E;Sign up for our newsletter and
stay informed&#x3C;/h5&#x3E;
[mc4wp_form id=&#x22;10&#x22;]
&#x3C;/div&#x3E;
&#x3C;/div&#x3E;</i><br><br>Please check <a href="'.esc_url('http://magniumthemes.com/go/saxon-docs/').'" target="_blank">theme documentation</a> for more information about this option configuration.',
    'priority'    => 30,
) );

// END SECTION: Subscribe block

// SECTION: Featured categories
Kirki::add_section( 'featured_categories', array(
    'title'          => esc_attr__( 'Home: Featured categories', 'saxon' ),
    'description'    => esc_attr__( 'Homepage block with selected categories boxes.', 'saxon' ),
    'panel'          => 'theme_settings_panel',
    'priority'       => 82
) );

// remove first array element 'All categories'
$featured_categories = Kirki_Helper::get_terms( 'category' );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'multicheck',
    'settings'    => 'featured_categories',
    'label'       => esc_attr__( 'Featured categories', 'saxon' ),
    'description' => esc_attr__( 'Select featured categories for display in Featured categories homepage block. You need to upload categories header background images in every featured category settings page.', 'saxon' ),
    'section'     => 'featured_categories',
    'default'     => '',
    'priority'    => 10,
    'choices'     => $featured_categories,
) );
// END SECTION: Featured categories

// SECTION: Sidebars
Kirki::add_section( 'sidebars', array(
    'title'          => esc_attr__( 'Sidebars', 'saxon' ),
    'description'    => esc_attr__( 'Choose your sidebar positions for different WordPress pages.', 'saxon' ),
    'panel'          => 'theme_settings_panel',
    'priority'       => 110
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'sidebar_sticky',
    'label'       => esc_attr__( 'Sticky sidebar', 'saxon' ),
    'description' => esc_attr__( 'Enable sticky sidebar feature for all sidebars. Supported by Edge, Safari, Firefox, Google Chrome and other modern browsers.', 'saxon' ),
    'section'     => 'sidebars',
    'default'     => '0',
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'sidebar_blog',
    'label'       => esc_attr__( 'Blog listing', 'saxon' ),
    'section'     => 'sidebars',
    'default'     => 'right',
    'priority'    => 10,
    'choices'     => array(
        'left'   => esc_attr__( 'Left', 'saxon' ),
        'right' => esc_attr__( 'Right', 'saxon' ),
        'disable' => esc_attr__( 'Disable', 'saxon' ),
    ),
    'description'  => '',
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'sidebar_post',
    'label'       => esc_attr__( 'Single Post', 'saxon' ),
    'section'     => 'sidebars',
    'default'     => 'disable',
    'priority'    => 20,
    'choices'     => array(
        'left'   => esc_attr__( 'Left', 'saxon' ),
        'right' => esc_attr__( 'Right', 'saxon' ),
        'disable' => esc_attr__( 'Disable', 'saxon' ),
    ),
    'description'  => esc_attr__( 'You can override sidebar position for every post in post settings.', 'saxon' ),
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'sidebar_page',
    'label'       => esc_attr__( 'Single page', 'saxon' ),
    'section'     => 'sidebars',
    'default'     => 'disable',
    'priority'    => 30,
    'choices'     => array(
        'left'   => esc_attr__( 'Left', 'saxon' ),
        'right' => esc_attr__( 'Right', 'saxon' ),
        'disable' => esc_attr__( 'Disable', 'saxon' ),
    ),
    'description'  => esc_attr__( 'You can override sidebar position for every page in page settings.', 'saxon' ),
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'sidebar_archive',
    'label'       => esc_attr__( 'Archive', 'saxon' ),
    'section'     => 'sidebars',
    'default'     => 'right',
    'priority'    => 40,
    'choices'     => array(
        'left'   => esc_attr__( 'Left', 'saxon' ),
        'right' => esc_attr__( 'Right', 'saxon' ),
        'disable' => esc_attr__( 'Disable', 'saxon' ),
    ),
    'description'  => '',
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'sidebar_search',
    'label'       => esc_attr__( 'Search', 'saxon' ),
    'section'     => 'sidebars',
    'default'     => 'right',
    'priority'    => 50,
    'choices'     => array(
        'left'   => esc_attr__( 'Left', 'saxon' ),
        'right' => esc_attr__( 'Right', 'saxon' ),
        'disable' => esc_attr__( 'Disable', 'saxon' ),
    ),
    'description'  => '',
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'sidebar_woocommerce',
    'label'       => esc_attr__( 'WooCommerce pages', 'saxon' ),
    'section'     => 'sidebars',
    'default'     => 'disable',
    'priority'    => 60,
    'choices'     => array(
        'left'   => esc_attr__( 'Left', 'saxon' ),
        'right' => esc_attr__( 'Right', 'saxon' ),
        'disable' => esc_attr__( 'Disable', 'saxon' ),
    ),
    'description'  => '',
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'sidebar_woocommerce_product',
    'label'       => esc_attr__( 'WooCommerce product page', 'saxon' ),
    'section'     => 'sidebars',
    'default'     => 'disable',
    'priority'    => 70,
    'choices'     => array(
        'left'   => esc_attr__( 'Left', 'saxon' ),
        'right' => esc_attr__( 'Right', 'saxon' ),
        'disable' => esc_attr__( 'Disable', 'saxon' ),
    ),
    'description'  => '',
) );
// END SECTION: Sidebars

// SECTION: Social icons
Kirki::add_section( 'social', array(
    'title'          => esc_attr__( 'Social icons', 'saxon' ),
    'description'    => esc_attr__( 'Add your social icons and urls. Social icons can be used in several site areas, sidebars widgets and shortcodes.', 'saxon' ),
    'panel'          => 'theme_settings_panel',
    'priority'       => 120
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'repeater',
    'label'       => esc_attr__( 'Social icons (your profiles)', 'saxon' ),
    'section'     => 'social',
    'priority'    => 10,
    'row_label' => array(
        'type' => 'field',
        'value' => esc_attr__('Social icon', 'saxon' ),
        'field' => 'social_type',
    ),
    'button_label' => esc_attr__('Add social icon', 'saxon' ),
    'settings'     => 'social_icons',
    'default'      => '',
    'fields' => array(
        'social_type' => array(
            'type'        => 'select',
            'label'       => esc_attr__( 'Social web', 'saxon' ),
            'description' => '',
            'choices'     => saxon_social_services_list(),
            'default'     => 'facebook',
        ),
        'social_url' => array(
            'type'        => 'text',
            'label'       => esc_attr__( 'Your profile url (including https://)', 'saxon' ),
            'description' => '',
            'default'     => '',
        ),
    )
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'social_share_facebook',
    'label'       => esc_attr__( 'Social share - Facebook', 'saxon' ),
    'description' => esc_attr__( 'Enable/Disable social share button for blog posts.', 'saxon' ),
    'section'     => 'social',
    'default'     => '1',
    'priority'    => 20,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'social_share_twitter',
    'label'       => esc_attr__( 'Social share - Twitter', 'saxon' ),
    'description' => esc_attr__( 'Enable/Disable social share button for blog posts.', 'saxon' ),
    'section'     => 'social',
    'default'     => '1',
    'priority'    => 30,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'social_share_google',
    'label'       => esc_attr__( 'Social share - Google+', 'saxon' ),
    'description' => esc_attr__( 'Enable/Disable social share button for blog posts.', 'saxon' ),
    'section'     => 'social',
    'default'     => '1',
    'priority'    => 40,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'social_share_linkedin',
    'label'       => esc_attr__( 'Social share - Linkedin', 'saxon' ),
    'description' => esc_attr__( 'Enable/Disable social share button for blog posts.', 'saxon' ),
    'section'     => 'social',
    'default'     => '1',
    'priority'    => 50,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'social_share_pinterest',
    'label'       => esc_attr__( 'Social share - Pinterest', 'saxon' ),
    'description' => esc_attr__( 'Enable/Disable social share button for blog posts.', 'saxon' ),
    'section'     => 'social',
    'default'     => '1',
    'priority'    => 60,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'social_share_vk',
    'label'       => esc_attr__( 'Social share - VKontakte', 'saxon' ),
    'description' => esc_attr__( 'Enable/Disable social share button for blog posts.', 'saxon' ),
    'section'     => 'social',
    'default'     => '1',
    'priority'    => 80,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'social_share_whatsapp',
    'label'       => esc_attr__( 'Social share - WhatsApp', 'saxon' ),
    'description' => esc_attr__( 'Enable/Disable social share button for blog posts.', 'saxon' ),
    'section'     => 'social',
    'default'     => '0',
    'priority'    => 90,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'social_share_telegram',
    'label'       => esc_attr__( 'Social share - Telegram', 'saxon' ),
    'description' => esc_attr__( 'Enable/Disable social share button for blog posts.', 'saxon' ),
    'section'     => 'social',
    'default'     => '0',
    'priority'    => 100,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'social_share_email',
    'label'       => esc_attr__( 'Social share - Email', 'saxon' ),
    'description' => esc_attr__( 'Enable/Disable social share button for blog posts.', 'saxon' ),
    'section'     => 'social',
    'default'     => '1',
    'priority'    => 110,
) );

// END SECTION: Social icons

// SECTION: Fonts
Kirki::add_section( 'fonts', array(
    'title'          => esc_attr__( 'Fonts', 'saxon' ),
    'description'    => '',
    'panel'          => 'theme_settings_panel',
    'priority'       => 130,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'typography',
    'settings'    => 'headers_font',
    'label'       => esc_attr__( 'Headers font', 'saxon' ),
    'section'     => 'fonts',
    'default'     => array(
        'font-family'    => 'Barlow',
        'variant'        => 'regular',
    ),
    'description' => esc_attr__( 'Font used in headers (H1-H6 tags).', 'saxon' ),
    'priority'    => 10,
    'output'      => ''
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'typography',
    'settings'    => 'body_font',
    'label'       => esc_attr__( 'Body font', 'saxon' ),
    'section'     => 'fonts',
    'default'     => array(
        'font-family'    => 'Roboto',
        'variant'        => 'regular',
        'font-size'      => '16px',
    ),
    'description' => esc_attr__( 'Font used in text elements.', 'saxon' ),
    'priority'    => 20,
    'output'      => ''
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'typography',
    'settings'    => 'additional_font',
    'label'       => esc_attr__( 'Additional font', 'saxon' ),
    'section'     => 'fonts',
    'default'     => array(
        'font-family'    => 'Barlow',
        'variant'        => '500',
    ),
    'description' => esc_attr__( 'Decorative font used in buttons, menus and some other elements.', 'saxon' ),
    'priority'    => 30,
    'output'      => ''
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'webfonts_loadallvariants',
    'label'       => esc_attr__( 'Load all Google Fonts variants and subsets', 'saxon' ),
    'description' => esc_attr__( 'Enable to load all available variants and subsets for fonts that you selected.', 'saxon' ),
    'section'     => 'fonts',
    'default'     => '0',
    'priority'    => 50,
) );

// END SECTION: Fonts

// SECTION: Colors
Kirki::add_section( 'colors', array(
    'title'          => esc_attr__( 'Colors', 'saxon' ),
    'description'    => '',
    'panel'          => 'theme_settings_panel',
    'priority'       => 140,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'select',
    'settings'    => 'color_skin',
    'label'       => esc_html__( 'Color skin', 'saxon' ),
    'section'     => 'colors',
    'default'     => 'none',
    'priority'    => 10,
    'multiple'    => 0,
    'choices'     => array(
        'none'   => esc_attr__( 'Custom colors (show selectors)', 'saxon' ),
        'default' => esc_attr__( 'Default', 'saxon' ),
        'dark' => esc_attr__('Dark (use with option below)', 'saxon'),
        'black' => esc_attr__('Black', 'saxon'),
        'grey' => esc_attr__('Grey', 'saxon'),
        'lightblue' => esc_attr__('Light blue', 'saxon'),
        'blue' => esc_attr__('Blue', 'saxon'),
        'red' => esc_attr__('Red', 'saxon'),
        'green' => esc_attr__('Green', 'saxon'),
        'orange' => esc_attr__('Orange', 'saxon'),
        'redorange' => esc_attr__('RedOrange', 'saxon'),
        'brown' => esc_attr__('Brown', 'saxon'),
    ),
    'description' => esc_attr__( 'Select one of predefined skins or set your own custom colors.', 'saxon' ),
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'color_darktheme',
    'label'       => esc_attr__('Enable dark theme', 'saxon' ),
    'description' => esc_html__('Use this option if you set dark backgrounds and light colors for texts. You need to set dark Header and Body backgrounds colors manually.', 'saxon'),
    'section'     => 'colors',
    'default'     => '0',
    'priority'    => 15,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'color',
    'settings'    => 'color_main',
    'label'       => esc_attr__( 'Main theme color', 'saxon' ),
    'description' => esc_attr__( 'Used in multiple theme areas (links, buttons, etc).', 'saxon' ),
    'section'     => 'colors',
    'default'     => '#1F5DEA',
    'priority'    => 20,
    'active_callback'  => array(
        array(
            'setting'  => 'color_skin',
            'operator' => '==',
            'value'    => 'none',
        ),
    )
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'color',
    'settings'    => 'color_button_hover',
    'label'       => esc_attr__( 'Buttons background color', 'saxon' ),
    'description' => esc_attr__( 'Used in alternative buttons, buttons hover, that does not use main theme color.', 'saxon' ),
    'section'     => 'colors',
    'default'     => '#000000',
    'priority'    => 25,
    'active_callback'  => array(
        array(
            'setting'  => 'color_skin',
            'operator' => '==',
            'value'    => 'none',
        ),
    )
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'custom',
    'settings'    => 'color_bodybg_html',
    'label'       => '',
    'section'     => 'colors',
    'default'     => '<div class="kirki-input-container"><label><span class="customize-control-title">Body background color</span><div>You can change it in Theme Settings > General.</div></label></div>',
    'priority'    => 27,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'color',
    'settings'    => 'color_text',
    'label'       => esc_attr__( 'Body text color', 'saxon' ),
    'description' => '',
    'section'     => 'colors',
    'default'     => '#333333',
    'priority'    => 30,
    'active_callback'  => array(
        array(
            'setting'  => 'color_skin',
            'operator' => '==',
            'value'    => 'none',
        ),
    )
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'custom',
    'settings'    => 'color_headerbg_html',
    'label'       => '',
    'section'     => 'colors',
    'default'     => '<div class="kirki-input-container"><label><span class="customize-control-title">Header background color</span><div>You can change it in Theme Settings > Header.</div></label></div>',
    'priority'    => 31,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'color',
    'settings'    => 'color_topmenu_bg',
    'label'       => esc_attr__( 'Top menu background color (light menu)', 'saxon' ),
    'description' => '',
    'section'     => 'colors',
    'default'     => '#FFFFFF',
    'priority'    => 32,
    'active_callback'  => array(
        array(
            'setting'  => 'color_skin',
            'operator' => '==',
            'value'    => 'none',
        ),
    )
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'color',
    'settings'    => 'color_topmenu_dark_bg',
    'label'       => esc_attr__( 'Top menu background color (dark menu)', 'saxon' ),
    'description' => '',
    'section'     => 'colors',
    'default'     => '#000000',
    'priority'    => 34,
    'active_callback'  => array(
        array(
            'setting'  => 'color_skin',
            'operator' => '==',
            'value'    => 'none',
        ),
    )
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'color',
    'settings'    => 'color_mainmenu_link',
    'label'       => esc_attr__( 'Mainmenu link color', 'saxon' ),
    'description' => '',
    'section'     => 'colors',
    'default'     => '#000000',
    'priority'    => 41,
    'active_callback'  => array(
        array(
            'setting'  => 'color_skin',
            'operator' => '==',
            'value'    => 'none',
        ),
    )
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'color',
    'settings'    => 'color_mainmenu_link_hover',
    'label'       => esc_attr__( 'Mainmenu link hover color', 'saxon' ),
    'description' => '',
    'section'     => 'colors',
    'default'     => '#1F5DEA',
    'priority'    => 42,
    'active_callback'  => array(
        array(
            'setting'  => 'color_skin',
            'operator' => '==',
            'value'    => 'none',
        ),
    )
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'color',
    'settings'    => 'color_mainmenu_submenu_bg',
    'label'       => esc_attr__( 'Mainmenu submenu background color', 'saxon' ),
    'description' => '',
    'section'     => 'colors',
    'default'     => '#ffffff',
    'priority'    => 43,
    'active_callback'  => array(
        array(
            'setting'  => 'color_skin',
            'operator' => '==',
            'value'    => 'none',
        ),
    )
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'color',
    'settings'    => 'color_mainmenu_submenu_link',
    'label'       => esc_attr__( 'Mainmenu submenu link color', 'saxon' ),
    'description' => '',
    'section'     => 'colors',
    'default'     => '#000000',
    'priority'    => 44,
    'active_callback'  => array(
        array(
            'setting'  => 'color_skin',
            'operator' => '==',
            'value'    => 'none',
        ),
    )
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'color',
    'settings'    => 'color_mainmenu_submenu_link_hover',
    'label'       => esc_attr__( 'Mainmenu submenu link hover color', 'saxon' ),
    'description' => '',
    'section'     => 'colors',
    'default'     => '#1F5DEA',
    'priority'    => 45,
    'active_callback'  => array(
        array(
            'setting'  => 'color_skin',
            'operator' => '==',
            'value'    => 'none',
        ),
    )
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'color',
    'settings'    => 'color_footer_bg',
    'label'       => esc_attr__( 'Footer background color (light footer)', 'saxon' ),
    'description' => '',
    'section'     => 'colors',
    'default'     => '#FFFFFF',
    'priority'    => 50,
    'active_callback'  => array(
        array(
            'setting'  => 'color_skin',
            'operator' => '==',
            'value'    => 'none',
        ),
    )
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'color',
    'settings'    => 'color_footer_dark_bg',
    'label'       => esc_attr__( 'Footer background color (dark footer)', 'saxon' ),
    'description' => '',
    'section'     => 'colors',
    'default'     => '#3C3D41',
    'priority'    => 60,
    'active_callback'  => array(
        array(
            'setting'  => 'color_skin',
            'operator' => '==',
            'value'    => 'none',
        ),
    )
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'color',
    'settings'    => 'color_footer_sidebar_bg',
    'label'       => esc_attr__( 'Footer sidebar background color', 'saxon' ),
    'description' => '',
    'section'     => 'colors',
    'default'     => '#FFFFFF',
    'priority'    => 70,
    'active_callback'  => array(
        array(
            'setting'  => 'color_skin',
            'operator' => '==',
            'value'    => 'none',
        ),
    )
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'color',
    'settings'    => 'color_post_reading_progressbar',
    'label'       => esc_attr__( 'Post reading progress bar color', 'saxon' ),
    'description' => '',
    'section'     => 'colors',
    'default'     => '#000000',
    'priority'    => 80,
    'active_callback'  => array(
        array(
            'setting'  => 'color_skin',
            'operator' => '==',
            'value'    => 'none',
        ),
    )
) );

// END SECTION: Colors

// SECTION: Ads management
Kirki::add_section( 'banners', array(
    'title'          => esc_attr__( 'Banners management', 'saxon' ),
    'description' => esc_attr__( 'You can add any HTML, JavaScript and WordPress shortcodes in this blocks content to show your advertisement. Switch to Text editor mode to add HTML/JavaScript code.', 'saxon' ),
    'panel'          => 'theme_settings_panel',
    'priority'       => 150,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'banner_header',
    'label'       => esc_attr__( 'Header banner', 'saxon' ),
    'description' => '',
    'section'     => 'banners',
    'default'     => '0',
    'priority'    => 10,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'editor',
    'settings'     => 'banner_header_content',
    'label'       => esc_attr__( 'Header banner HTML', 'saxon' ),
    'description' => esc_attr__( 'Displayed in site header below posts slider.', 'saxon' ),
    'section'     => 'banners',
    'default'     => '',
    'priority'    => 20,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'banner_inside_header',
    'label'       => esc_attr__( 'Inside Header banner', 'saxon' ),
    'description' => '',
    'section'     => 'banners',
    'default'     => '0',
    'priority'    => 25,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'editor',
    'settings'     => 'banner_inside_header_content',
    'label'       => esc_attr__( 'Inside Header banner HTML', 'saxon' ),
    'description' => esc_attr__( 'Displayed inside site header (instead of main menu). Disable Main Menu if you want to use this banner.', 'saxon' ),
    'section'     => 'banners',
    'default'     => '',
    'priority'    => 26,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'banner_below_header',
    'label'       => esc_attr__( 'Below header banner', 'saxon' ),
    'description' => '',
    'section'     => 'banners',
    'default'     => '0',
    'priority'    => 30,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'editor',
    'settings'     => 'banner_below_header_content',
    'label'       => esc_attr__( 'Below header banner HTML', 'saxon' ),
    'description' => esc_attr__( 'Displayed below site header.', 'saxon' ),
    'section'     => 'banners',
    'default'     => '',
    'priority'    => 40,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'banner_above_footer',
    'label'       => esc_attr__( 'Above footer banner', 'saxon' ),
    'description' => '',
    'section'     => 'banners',
    'default'     => '0',
    'priority'    => 50,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'editor',
    'settings'     => 'banner_above_footer_content',
    'label'       => esc_attr__( 'Above footer banner HTML', 'saxon' ),
    'description' => esc_attr__( 'Displayed above site footer.', 'saxon' ),
    'section'     => 'banners',
    'default'     => '',
    'priority'    => 60,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'banner_footer',
    'label'       => esc_attr__( 'Footer banner', 'saxon' ),
    'description' => '',
    'section'     => 'banners',
    'default'     => '0',
    'priority'    => 70,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'editor',
    'settings'     => 'banner_footer_content',
    'label'       => esc_attr__( 'Footer banner HTML', 'saxon' ),
    'description' => esc_attr__( 'Displayed in site footer.', 'saxon' ),
    'section'     => 'banners',
    'default'     => '',
    'priority'    => 80,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'banner_below_top_menu',
    'label'       => esc_attr__( 'Below header top menu', 'saxon' ),
    'description' => '',
    'section'     => 'banners',
    'default'     => '0',
    'priority'    => 90,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'editor',
    'settings'     => 'banner_below_top_menu_content',
    'label'       => esc_attr__( 'Below header top menu banner HTML', 'saxon' ),
    'description' => esc_attr__( 'Displayed on homepage below top menu.', 'saxon' ),
    'section'     => 'banners',
    'default'     => '',
    'priority'    => 100,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'banner_posts_loop_middle',
    'label'       => esc_attr__( 'Posts list middle banner', 'saxon' ),
    'description' => '',
    'section'     => 'banners',
    'default'     => '0',
    'priority'    => 110,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'editor',
    'settings'     => 'banner_posts_loop_middle_content',
    'label'       => esc_attr__( 'Posts list middle banner HTML', 'saxon' ),
    'description' => esc_attr__( 'Displayed at the middle between posts on all posts listing pages (Homepage, Archives, Search, etc). This banner does not available in Masonry and Two column blog layouts.', 'saxon' ),
    'section'     => 'banners',
    'default'     => '',
    'priority'    => 120,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'banner_posts_loop_bottom',
    'label'       => esc_attr__( 'Posts list bottom banner', 'saxon' ),
    'description' => '',
    'section'     => 'banners',
    'default'     => '0',
    'priority'    => 130,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'editor',
    'settings'     => 'banner_posts_loop_bottom_content',
    'label'       => esc_attr__( 'Posts list bottom banner HTML', 'saxon' ),
    'description' => esc_attr__( 'Displayed at the bottom after all posts on posts listing pages (Homepage, Archives, Search, etc).', 'saxon' ),
    'section'     => 'banners',
    'default'     => '',
    'priority'    => 140,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'banner_single_post_top',
    'label'       => esc_attr__( 'Single post top banner', 'saxon' ),
    'description' => '',
    'section'     => 'banners',
    'default'     => '0',
    'priority'    => 150,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'editor',
    'settings'     => 'banner_single_post_top_content',
    'label'       => esc_attr__( 'Single post top banner HTML', 'saxon' ),
    'description' => esc_attr__( 'Displayed on single blog post page between post content and featured image.', 'saxon' ),
    'section'     => 'banners',
    'default'     => '',
    'priority'    => 160,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'banner_single_post_bottom',
    'label'       => esc_attr__( 'Single post bottom banner', 'saxon' ),
    'description' => '',
    'section'     => 'banners',
    'default'     => '0',
    'priority'    => 170,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'editor',
    'settings'     => 'banner_single_post_bottom_content',
    'label'       => esc_attr__( 'Single post bottom banner HTML', 'saxon' ),
    'description' => esc_attr__( 'Displayed on single blog post page after post content.', 'saxon' ),
    'section'     => 'banners',
    'default'     => '',
    'priority'    => 180,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'toggle',
    'settings'    => 'banner_404',
    'label'       => esc_attr__( '404 page banner', 'saxon' ),
    'description' => '',
    'section'     => 'banners',
    'default'     => '0',
    'priority'    => 190,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'editor',
    'settings'     => 'banner_404_content',
    'label'       => esc_attr__( '404 page banner HTML', 'saxon' ),
    'description' => esc_attr__( 'Displayed on 404 not found page.', 'saxon' ),
    'section'     => 'banners',
    'default'     => '',
    'priority'    => 200,
) );

// SECTION: Support and updates
Kirki::add_section( 'about', array(
    'title'          => esc_attr__( 'Documentation & Support', 'saxon' ),
    'description' => '',
    'panel'          => 'theme_settings_panel',
    'priority'       => 150,
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'custom',
    'settings'    => 'about_support',
    'label'       => '',
    'section'     => 'about',
    'default'     => '<div class="documentation-icon"><a href="http://magniumthemes.com/" target="_blank"><img src="'.get_template_directory_uri().'/img/developer-icon.png" alt="MagniumThemes"/></a></div><p>We recommend you to read <a href="http://magniumthemes.com/go/saxon-docs/" target="_blank">Theme Documentation</a> before you will start using our theme to building your website. It covers all steps for site configuration, demo content import, theme features usage and more.</p>
<p>If you have face any problems with our theme feel free to use our <a href="http://support.magniumthemes.com/" target="_blank">Support System</a> to contact us and get help for free.</p>
<a class="button button-primary" href="http://magniumthemes.com/go/saxon-docs/" target="_blank">Documentation</a> <a class="button button-primary" href="http://support.magniumthemes.com/" target="_blank">Support System</a>
<p><strong>Theme developed by <a href="http://magniumthemes.com/" target="_blank">MagniumThemes</a>.</strong><br/>All rights reserved.</p>
<a class="button button-primary" href="http://magniumthemes.com/themes/" target="_blank">Our WordPress themes</a>',
    'priority'    => 10,
) );
// END SECTION: Support and updates

// SECTION: Additional JavaScript
Kirki::add_section( 'customjs', array(
    'title'          => esc_attr__( 'Additional JavaScript', 'saxon' ),
    'description'    => '',
    'panel'          => '',
    'priority'       => 220
) );

Kirki::add_field( 'saxon_theme_options', array(
    'type'        => 'code',
    'settings'    => 'custom_js_code',
    'label'       => esc_attr__( 'Custom JavaScript code', 'saxon' ),
    'description' => esc_attr__( 'This code will run in header, do not add &#x3C;script&#x3E;...&#x3C;/script&#x3E; tags here, this tags will be added automatically. You can use JQuery code here.', 'saxon' ),
    'section'     => 'customjs',
    'default'     => '',
    'choices'     => array(
        'language' => 'js',
    ),
    'priority'    => 10,
) );

// END SECTION: Additional JavaScript

endif; // check for 'saxon_update'

// Kirki plugin not installed
else:
    add_action( 'admin_notices', 'saxon_kirki_warning' );
endif;

/*
*   Kirki not installed warning display
*/
if (!function_exists('saxon_kirki_warning')) :
function saxon_kirki_warning() {

    $message_html = '<div class="notice notice-error"><p><strong>WARNING:</strong> Please <a href="'.esc_url( admin_url( 'themes.php?page=install-required-plugins&plugin_status=install' ) ).'">install and activate Saxon Theme Settings (Kirki Toolkit)</a> required plugin, <strong>theme settings will not work without it</strong>.</p></div>';

    echo wp_kses_post($message_html);

}
endif;

