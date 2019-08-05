<?php
/**
 * Theme Plugins installation
 */

/**
 * Plugin recomendations
 **/
require_once(get_template_directory().'/inc/tgmpa/class-tgm-plugin-activation.php');

if(!function_exists('saxon_register_required_plugins')):
function saxon_register_required_plugins() {

    /**
     * Array of plugin arrays. Required keys are name and slug.
     */
    $plugins = array(
        array(
            'name'                  => esc_html__('Saxon Custom Metaboxes', 'saxon'),
            'slug'                  => 'cmb2',
            'source'                => get_template_directory() . '/inc/plugins/cmb2.zip',
            'required'              => true,
            'version'               => '2.6.0',
        ),
        array(
            'name'                  => esc_html__('Saxon Theme Settings (Kirki Toolkit)', 'saxon'),
            'slug'                  => 'kirki',
            'source'                => get_template_directory() . '/inc/plugins/kirki.zip',
            'required'              => true,
        ),
        array(
            'name'                  => esc_html__('Saxon Theme Addons', 'saxon'),
            'slug'                  => 'saxon-theme-addons',
            'source'                => get_template_directory() . '/inc/plugins/saxon-theme-addons.zip',
            'required'              => true,
            'version'               => '1.6',
        ),
        array(
            'name'                  => esc_html__('Envato Market - Automatic theme updates', 'saxon'),
            'slug'                  => 'envato-market',
            'source'                => get_template_directory() . '/inc/plugins/envato-market.zip',
            'required'              => false,
            'version'               => '2.0.1',
        ),
        array(
            'name'                  => esc_html__('Saxon AMP - Accelerated Mobile Pages support', 'saxon'),
            'slug'                  => 'accelerated-mobile-pages',
            'required'              => false,
        ),
        array(
            'name'                  => esc_html__('Saxon Page Navigation', 'saxon'),
            'slug'                  => 'wp-pagenavi',
            'required'              => false,
        ),
        array(
            'name'                  => esc_html__('Saxon Translation Manager', 'saxon'),
            'slug'                  => 'loco-translate',
            'required'              => false,
        ),
        array(
            'name'                  => esc_html__('Instagram Feed', 'saxon'),
            'slug'                  => 'instagram-feed',
            'required'              => false,
        ),
        array(
            'name'                  => esc_html__('MailChimp for WordPress', 'saxon'),
            'slug'                  => 'mailchimp-for-wp',
            'required'              => false,
        ),
        array(
            'name'                  => esc_html__('WordPress LightBox', 'saxon'),
            'slug'                  => 'responsive-lightbox',
            'required'              => false
        ),
        array(
            'name'                  => esc_html__('Contact Form 7', 'saxon'),
            'slug'                  => 'contact-form-7',
            'required'              => false,
        ),
        array(
            'name'                  => esc_html__('Regenerate Thumbnails', 'saxon'),
            'slug'                  => 'regenerate-thumbnails',
            'required'              => false,
        )

    );

    // Add Gutenberg for old WordPress versions
    if(version_compare(get_bloginfo('version'), '5.0', "<")) {
        $plugins[] = array(
            'name'                  => esc_html__('Gutenberg - Advanced WordPress Content Editor', 'saxon'),
            'slug'                  => 'gutenberg',
            'required'              => false,
        );
    }

    /**
     * Array of configuration settings.
     */
    $config = array(
        'domain'            => 'saxon',
        'default_path'      => '',
        'menu'              => 'install-required-plugins',
        'has_notices'       => true,
        'dismissable'  => true,
        'is_automatic'      => false,
        'message'           => ''
    );

    tgmpa( $plugins, $config );

}
endif;
add_action('tgmpa_register', 'saxon_register_required_plugins');
