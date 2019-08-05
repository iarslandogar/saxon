<?php
/**
 * Theme CSS cache generation
 */

	add_action( 'wp_enqueue_scripts', 'saxon_enqueue_dynamic_styles', '999' );

    if(!function_exists('saxon_enqueue_dynamic_styles')):
	function saxon_enqueue_dynamic_styles( ) {

        require_once(ABSPATH . 'wp-admin/includes/file.php'); // required to use WP_Filesystem();

        WP_Filesystem();

        global $wp_filesystem;

		if ( function_exists( 'is_multisite' ) && is_multisite() ){
            $cache_file_name = 'style-cache-'.wp_get_theme()->get('TextDomain').'-b' . get_current_blog_id();
        } else {
            $cache_file_name = 'style-cache-'.wp_get_theme()->get('TextDomain');
        }

        // Customizer preview
        if(is_customize_preview()) {
            if ( function_exists( 'is_multisite' ) && is_multisite() ){
                $cache_file_name = 'preview-cache-'.wp_get_theme()->get('TextDomain').'-b' . get_current_blog_id();
            } else {
                $cache_file_name = 'preview-cache-'.wp_get_theme()->get('TextDomain');
            }
        }

        $wp_upload_dir = wp_upload_dir();

        $css_cache_file = $wp_upload_dir['basedir'].'/'.$cache_file_name.'.css';

        $css_cache_file_url = $wp_upload_dir['baseurl'].'/'.$cache_file_name.'.css';

        $themeoptions_saved_date = get_option( 'themeoptions_saved_date', 1 );
        $cache_saved_date = get_option( 'cache_css_saved_date', 0 );

		if( file_exists( $css_cache_file ) ) {
			$cache_status = 'exist';

            if($themeoptions_saved_date > $cache_saved_date) {
                $cache_status = 'no-exist';
            }

		} else {
			$cache_status = 'no-exist';
		}

        if ( defined('DEMO_MODE') ) {
            $cache_status = 'no-exist';
        }

        if(is_customize_preview()) {
            $cache_status = 'no-exist';
        }

		if ( $cache_status == 'exist' ) {

			wp_register_style( $cache_file_name, $css_cache_file_url, array(), $cache_saved_date);
			wp_enqueue_style( $cache_file_name );

		} else {

			$out = '/* Cache file created at '.date('Y-m-d H:i:s').' */';

			$generated = microtime(true);

			$out .= saxon_get_css();

			$out = str_replace( array( "\t", "
", "\n", "  ", "   ", ), array( "", "", " ", " ", " ", ), $out );

			$out .= '/* CSS Generator Execution Time: ' . floatval( ( microtime(true) - $generated ) ) . ' seconds */';

            // FS_CHMOD_FILE required by WordPress guideliness - https://codex.wordpress.org/Filesystem_API#Using_the_WP_Filesystem_Base_Class
            if ( defined( 'FS_CHMOD_FILE' ) ) {
                $chmod_file = FS_CHMOD_FILE;
            } else {
                $chmod_file = ( 0644 & ~ umask() );
            }

			if ( $wp_filesystem->put_contents( $css_cache_file, $out, $chmod_file) ) {

				wp_register_style( $cache_file_name, $css_cache_file_url, array(), $cache_saved_date);
				wp_enqueue_style( $cache_file_name );

                // Update save options date
                $option_name = 'cache_css_saved_date';

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

		}
	}
    endif;

    if(!function_exists('saxon_get_css')):
	function saxon_get_css() {
		// ===
		ob_start();
    ?>
    <?php
    // THEME OPTIONS DEFAULTS FOR CSS

    // Header height
    $header_height = get_theme_mod('header_height', 160);

    // Logo width
    $logo_width = get_theme_mod( 'logo_width', 135 );

    // Slider height
    $slider_height = get_theme_mod('slider_height', 420);

    // Main Menu paddings
    $mainmenu_paddings = get_theme_mod('mainmenu_paddings', '20px');

    // Top Menu paddings
    $topmenu_paddings = get_theme_mod('topmenu_paddings', '15px');

    // Thumbs height proportion
    $thumb_height_proportion = get_theme_mod('thumb_height_proportion', 64.8648);

    ?>
    header .col-md-12 {
        height: <?php echo esc_attr($header_height); ?>px;
    }
    .navbar .nav > li {
        padding-top: <?php echo esc_attr($mainmenu_paddings); ?>;
        padding-bottom: <?php echo esc_attr($mainmenu_paddings); ?>;
    }
    .nav > li > .sub-menu {
        margin-top: <?php echo esc_attr($mainmenu_paddings); ?>;
    }
    .header-menu li a,
    .header-menu .menu-top-menu-container-toggle {
        padding-top: <?php echo esc_attr($topmenu_paddings); ?>;
        padding-bottom: <?php echo esc_attr($topmenu_paddings); ?>;
    }
    .header-menu .menu-top-menu-container-toggle + div {
        top: calc(<?php echo esc_attr($topmenu_paddings); ?> + <?php echo esc_attr($topmenu_paddings); ?> + 20px);
    }
    <?php
    // Hide post dates
    if(get_theme_mod('blog_posts_date_hide', false)): ?>
    .saxon-post .post-date,
    .saxon-post .post-author:after,
    .saxon-post .post-author + .post-date,
    .sidebar .widget .post-date,
    .sidebar .widget .post-author + .post-date {
        display: none;
    }
    <?php endif; ?>
    <?php
    // Retina logo
    ?>
    header .logo-link img {
        width: <?php echo esc_attr($logo_width); ?>px;
    }
    .saxon-blog-posts-slider .saxon-post {
        height: <?php echo esc_attr($slider_height); ?>px;
    }
    <?php
    // Transparent header adjustments
    ?>
    @media (min-width: 1024px)  {
        body.single-post.blog-post-header-with-bg.blog-post-transparent-header-enable .container-page-item-title.with-bg .page-item-title-single,
        body.page.blog-post-header-with-bg.blog-post-transparent-header-enable .container-page-item-title.with-bg .page-item-title-single {
            padding-top: <?php echo intval(35 + $header_height); ?>px;
        }
    }
    <?php
    // Thumb height
    ?>
    .saxon-post .saxon-post-image-wrapper {
        padding-bottom: <?php echo esc_attr($thumb_height_proportion); ?>%;
    }
    <?php
    // Header topline
    if(get_theme_mod('header_topline', false)):

    $header_topline_bgcolor_1 = get_theme_mod('header_topline_bgcolor_1', '#4376ec');
    $header_topline_bgcolor_2 = get_theme_mod('header_topline_bgcolor_2', '#bc5de4');

    ?>
    .header-topline-wrapper {
        background-color: <?php echo esc_attr($header_topline_bgcolor_1); ?>;
        background: -moz-linear-gradient(left,  <?php echo esc_attr($header_topline_bgcolor_1); ?> 0%, <?php echo esc_attr($header_topline_bgcolor_2); ?> 100%);
        background: -webkit-linear-gradient(left,  <?php echo esc_attr($header_topline_bgcolor_1); ?> 0%, <?php echo esc_attr($header_topline_bgcolor_2); ?> 100%);
        background: linear-gradient(to right,  <?php echo esc_attr($header_topline_bgcolor_1); ?> 0%, <?php echo esc_attr($header_topline_bgcolor_2); ?> 100%);
    }
    <?php endif;
    ?>
    /* Top menu */
    <?php if(get_theme_mod('topmenu_disable_mobile', true)): ?>
    @media (max-width: 991px) {
        .header-menu-bg {
            display: none;
        }
    }
    <?php endif; ?>
    /**
    * Theme Google Fonts
    **/
    <?php
    // Logo text font
    if ( get_theme_mod( 'logo_text', true ) == true && get_theme_mod( 'logo_text_title', '' ) !== '' ) {

    $logo_text_font = get_theme_mod( 'logo_text_font', array(
        'font-family'    => 'Cormorant Garamond',
        'font-size'    => '62px',
        'variant'        => 'regular',
        'color'          => '#000000',
    ));

    ?>
        header .logo-link.logo-text {
            font-family: '<?php echo esc_attr($logo_text_font['font-family']); ?>';
            <?php echo esc_attr(saxon_get_font_style_css($logo_text_font['variant'])); ?>
            font-size: <?php echo esc_attr($logo_text_font['font-size']); ?>;
            color: <?php echo esc_attr($logo_text_font['color']); ?>;
        }
        <?php
    }

    // Fonts and default fonts configuration

    // Headers font
    $headers_font = get_theme_mod('headers_font', array(
        'font-family'    => 'Barlow',
        'variant'        => 'regular',
    ));

    // Body font
    $body_font = get_theme_mod('body_font', array(
        'font-family'    => 'Roboto',
        'variant'        => 'regular',
        'font-size'      => '16px',
    ));

    // Additional font
    $additional_font = get_theme_mod('additional_font', array(
        'font-family'    => 'Barlow',
        'variant'        => '500',
    ));

    ?>
    h1, h2, h3, h4, h5, h6,
    .h1, .h2, .h3, .h4, .h5, .h6,
    .blog-post .format-quote .entry-content,
    .blog-post .format-quote .entry-content:before,
    blockquote,
    blockquote:before,
    .sidebar .widget .post-title,
    .comment-metadata .author,
    .author-bio strong,
    .navigation-post .nav-post-name,
    .sidebar .widgettitle,
    .post-worthreading-post-container .post-worthreading-post-title {
        font-family: '<?php echo esc_attr($headers_font['font-family']); ?>';
        <?php echo esc_attr(saxon_get_font_style_css($headers_font['variant'])); ?>
    }
    body {
        font-family: '<?php echo esc_attr($body_font['font-family']); ?>';
        <?php echo esc_attr(saxon_get_font_style_css($body_font['variant'])); ?>
        font-size: <?php echo esc_attr($body_font['font-size']); ?>;
    }
    .additional-font,
    .btn,
    input[type="submit"],
    .woocommerce #content input.button,
    .woocommerce #respond input#submit,
    .woocommerce a.button,
    .woocommerce button.button,
    .woocommerce input.button,
    .woocommerce-page #content input.button,
    .woocommerce-page #respond input#submit,
    .woocommerce-page a.button,
    .woocommerce-page button.button,
    .woocommerce-page input.button,
    .woocommerce a.added_to_cart,
    .woocommerce-page a.added_to_cart,
    .woocommerce span.onsale,
    .woocommerce ul.products li.product .onsale,
    .wp-block-button a.wp-block-button__link,
    .header-menu,
    .mainmenu li.menu-item > a,
    .footer-menu,
    .saxon-post .post-categories,
    .sidebar .widget .post-categories,
    .blog-post .post-categories,
    .page-item-title-archive p,
    .saxon-blog-posts-slider .saxon-post-details .saxon-post-info,
    .post-subtitle-container,
    .sidebar .widget .post-date,
    .sidebar .widget .post-author,
    .saxon-post .post-author,
    .saxon-post .post-date,
    .saxon-post .post-details-bottom,
    .blog-post .tags,
    .navigation-post .nav-post-title,
    .post-worthreading-post-wrapper .post-worthreading-post-button,
    .comment-metadata .date,
    header .header-blog-info {
        font-family: '<?php echo esc_attr($additional_font['font-family']); ?>';
        <?php echo esc_attr(saxon_get_font_style_css($additional_font['variant'])); ?>
    }

    /**
    * Colors and color skins
    */
    <?php

    // Dark theme option
    if(get_theme_mod('color_darktheme', false)) {

        // Dark theme Custom CSS
        ?>
        /* Borders */
        .saxon-postline-block,
        .saxon-post .post-details-bottom .post-info-wrapper,
        .saxon-post .post-details-bottom,
        .saxon-subscribe-block,
        .sidebar .widgettitle,
        header .social-icons-wrapper + .search-toggle-wrapper a.search-toggle-btn,
        .mainmenu-mobile-toggle i,
        .nav > li .sub-menu,
        .navigation-post:before,
        .comment-list li.comment,
        .navigation-paging .wp-pagenavi,
        .navigation-paging .nav-post-prev a, .navigation-paging .nav-post-next a,
        .comment-list .children li.comment,
        .wp-block-table,
        table,
        .woocommerce table.shop_attributes th,
        .woocommerce table.shop_attributes td,
        table td,
        table th,
        .woocommerce div.product .woocommerce-tabs ul.tabs:before,
        .woocommerce-cart .cart-collaterals .cart_totals table,
        #add_payment_method #payment ul.payment_methods, .woocommerce-cart #payment ul.payment_methods, .woocommerce-checkout #payment ul.payment_methods,
        .wp-block-separator {
            border-color: rgba(255,255,255,0.1);
        }
        /* White text */
        a:hover, a:focus,
        .saxon-post .post-title a,
        .saxon-post .post-title,
        .single-post .page-item-title-single .saxon-post .post-title:hover,
        h1, h2, h3, h4, h5, h6,
        .post-social-wrapper .post-social a,
        .saxon-post .post-details-bottom,
        .saxon-post .post-details-bottom a,
        .sidebar .widgettitle,
        .social-icons-wrapper a,
        header a.search-toggle-btn,
        .mainmenu-mobile-toggle,
        .author-bio h3 a,
        .author-bio .author-social-icons li a,
        .navigation-post a.nav-post-title-link,
        .navigation-post .nav-post-name,
        .blog-post-related-wrapper > h5,
        .comments-title,
        .comment-reply-title,
        .page-item-title-single .page-title,
        .sidebar .widget_calendar caption,
        .widget_recent_entries li a, .widget_recent_comments li a, .widget_categories li a, .widget_archive li a, .widget_meta li a, .widget_pages li a, .widget_rss li a, .widget_nav_menu li a,
        .sidebar .widget.widget_nav_menu a,
        .navigation-paging .wp-pagenavi a,
        .navigation-paging .nav-post-prev a, .navigation-paging .nav-post-next a,
        .wp-block-latest-posts a,
        .woocommerce ul.cart_list li a, .woocommerce ul.product_list_widget li a,
        .woocommerce ul.products li.product .woocommerce-loop-product__title,
        .woocommerce .woocommerce-breadcrumb a,
        .woocommerce .woocommerce-breadcrumb {
            color: #ffffff;
        }
        /* Grey text */
        .saxon-postline-block .saxon-block-title h3,
        blockquote cite,
        .author-bio h5,
        .navigation-post .nav-post-title,
        .woocommerce ul.products li.product .price,
        .woocommerce div.product p.price, .woocommerce div.product span.price {
            color: #868686;
        }
        /* Black text */
        .saxon-social-share-fixed .post-social-wrapper .post-social a {
            color: #000000;
        }
        /* Black background */
        .wp-block-table tr:nth-child(2n+1) td {
            background-color: #000000;
        }
        /* Grey background */
        .sidebar .widget.widget_saxon_social_icons a,
        .wp-block-latest-posts a {
            background-color: #868686;
        }
        /* Transparent background */
        .author-bio,
        .panel,
        table th,
        .woocommerce table.shop_table, #add_payment_method #payment, .woocommerce-checkout #payment,
        .woocommerce .order_details {
            background: transparent;
        }
        /* Author bio */
        .author-bio {
            border: 1px solid rgba(255,255,255,0.1);
        }
        /* Remove margin for dark header */
        @media (min-width: 992px) {
            .home header.main-header, .blog header.main-header {
                margin-bottom: 0!important;
            }
            .container-page-item-title.container {
                margin-top: 0!important;
            }
        }
        /* Remove menu background */
        .nav .sub-menu li.menu-item > a:hover {
            background: none;
        }
        /* Remove menu shadow */
        header.main-header.fixed {
            box-shadow: none;
        }
        /* Show light logo */
        header .light-logo {
            display: block!important;
        }
        header .regular-logo {
            display: none!important;
        }
        <?php
    }

    $color_skin = get_theme_mod('color_skin', 'none');

    // Demo settings
    if ( defined('DEMO_MODE') && isset($_GET['color_skin']) ) {
      $color_skin = $_GET['color_skin'];
    }

    // Use panel settings
    if($color_skin == 'none') {

        $color_text = get_theme_mod('color_text', '#333333');
        $color_main = get_theme_mod('color_main', '#1F5DEA');
        $color_button_hover = get_theme_mod('color_button_hover', '#000000');
        $color_topmenu_bg =  get_theme_mod('color_topmenu_bg', '#FFFFFF');
        $color_topmenu_dark_bg = get_theme_mod('color_topmenu_dark_bg', '#000000');
        $color_mainmenu_submenu_bg = get_theme_mod('color_mainmenu_submenu_bg', '#FFFFFF');
        $color_mainmenu_link = get_theme_mod('color_mainmenu_link', '#000000');
        $color_mainmenu_link_hover = get_theme_mod('color_mainmenu_link_hover', '#1F5DEA');
        $color_mainmenu_submenu_link = get_theme_mod('color_mainmenu_submenu_link', '#000000');
        $color_mainmenu_submenu_link_hover = get_theme_mod('color_mainmenu_submenu_link_hover', '#1F5DEA');
        $color_footer_bg = get_theme_mod('color_footer_bg', '#FFFFFF');
        $color_footer_dark_bg = get_theme_mod('color_footer_dark_bg', '#3C3D41');
        $color_footer_sidebar_bg = get_theme_mod('color_footer_sidebar_bg', '#FFFFFF');
        $color_slider_text = get_theme_mod('color_slider_text', '#000000');
        $color_post_reading_progressbar = get_theme_mod('color_post_reading_progressbar', '#000000');

    } else {

        // Same colors for all skins
        $color_text = '#000000';
        $color_button_hover = '#000000';
        $color_topmenu_bg = '#FFFFFF';
        $color_topmenu_dark_bg = '#000000';
        $color_mainmenu_submenu_bg = '#FFFFFF';
        $color_mainmenu_link = '#000000';
        $color_mainmenu_submenu_link = '#000000';
        $color_mainmenu_submenu_link_hover = '#1F5DEA';
        $color_footer_bg = '#FFFFFF';
        $color_footer_dark_bg = '#000000';
        $color_footer_sidebar_bg = '#FFFFFF';
        $color_slider_text = '#000000';
        $color_post_reading_progressbar = '#000000';
    }

    // Default skin
    if($color_skin == 'default') {

        $color_main = '#1F5DEA';
        $color_mainmenu_link_hover = $color_main;
        $color_mainmenu_submenu_link_hover = $color_main;

    }
    // Black skin
    if($color_skin == 'dark') {

        $color_text = '#8e8e8e';
        $color_main = '#1F5DEA';
        $color_button_hover = '#000000';
        $color_topmenu_bg =  '#FFFFFF';
        $color_topmenu_dark_bg = '#000000';
        $color_mainmenu_submenu_bg = '#0f0f0f';
        $color_mainmenu_link = '#ffffff';
        $color_mainmenu_link_hover = '#1F5DEA';
        $color_mainmenu_submenu_link = '#ffffff';
        $color_mainmenu_submenu_link_hover = '#868686';
        $color_footer_bg = '#FFFFFF';
        $color_footer_dark_bg = '#000000';
        $color_footer_sidebar_bg = '#000000';
        $color_slider_text = '#000000';
        $color_post_reading_progressbar = '#1F5DEA';

    }
    // Black skin
    if($color_skin == 'black') {

        $color_main = '#444444';
        $color_mainmenu_link_hover = $color_main;
        $color_mainmenu_submenu_link_hover = $color_main;

    }
    // Grey skin
    if($color_skin == 'grey') {

        $color_main = '#62aed1';
        $color_mainmenu_link_hover = $color_main;
        $color_mainmenu_submenu_link_hover = $color_main;

    }
    // Light blue skin
    if($color_skin == 'lightblue') {

        $color_main = '#62aed1';
        $color_mainmenu_link_hover = $color_main;
        $color_mainmenu_submenu_link_hover = $color_main;

    }
    // Blue skin
    if($color_skin == 'blue') {

        $color_main = '#6284d1';
        $color_mainmenu_link_hover = $color_main;
        $color_mainmenu_submenu_link_hover = $color_main;

    }
    // Red
    if($color_skin == 'red') {

        $color_main = '#e4393c';
        $color_mainmenu_link_hover = $color_main;
        $color_mainmenu_submenu_link_hover = $color_main;

    }
    // Green
    if($color_skin == 'green') {

        $color_main = '#6cc49a';
        $color_mainmenu_link_hover = $color_main;
        $color_mainmenu_submenu_link_hover = $color_main;

    }
    // Orange
    if($color_skin == 'orange') {

        $color_main = '#fab915';
        $color_mainmenu_link_hover = $color_main;
        $color_mainmenu_submenu_link_hover = $color_main;

    }
    // RedOrange
    if($color_skin == 'redorange') {

        $color_main = '#e4393c';
        $color_mainmenu_link_hover = $color_main;
        $color_mainmenu_submenu_link_hover = $color_main;

    }
    // Brown
    if($color_skin == 'brown') {

        $color_main = '#c6afa5';
        $color_mainmenu_link_hover = $color_main;
        $color_mainmenu_submenu_link_hover = $color_main;

    }
    ?>
    <?php
    // Body background
    $body_background = get_theme_mod( 'body_background', false );

    if(!empty($body_background['background-image'])): ?>
    body {
        <?php
        echo 'background-image: url('.esc_url($body_background['background-image']).');';
        echo 'background-repeat: '.$body_background['background-repeat'].';';
        echo 'background-position: '.$body_background['background-position'].';';
        echo 'background-size: '.$body_background['background-size'].';';
        echo 'background-attachment: '.$body_background['background-attachment'].';';
        ?>
    }
    <?php endif; ?>
    <?php
    // Header background image
    $header_background = get_theme_mod( 'header_background', false );
    ?>
    header {
        background-color: <?php echo esc_attr($header_background['background-color']); ?>;
    }
    <?php
    if(!empty($header_background['background-image'])): ?>
    header {
        <?php
        echo 'background-image: url('.esc_url($header_background['background-image']).');';
        echo 'background-repeat: '.$header_background['background-repeat'].';';
        echo 'background-position: '.$header_background['background-position'].';';
        echo 'background-size: '.$header_background['background-size'].';';
        echo 'background-attachment: '.$header_background['background-attachment'].';';
        ?>
    }
    <?php endif; ?>
    <?php
    if(!empty($header_background['background-image']) || (!empty($header_background['background-color']) && $header_background['background-color'] !== '#ffffff')): ?>
     @media (min-width: 992px) {
        .home header.main-header,
        .blog header.main-header {
            margin-bottom: 60px;
        }
    }
    .container-page-item-title.container {
        margin-top: 60px;
    }
    @media (max-width: 991px) {
        .container-page-item-title.container {
            margin-top: 40px;
        }
    }
    <?php endif; ?>

    body {
        color: <?php echo esc_html($color_text); ?>;
        background-color: <?php echo esc_html($body_background['background-color']); ?>;
    }

    .btn,
    .btn:focus,
    input[type="submit"],
    .woocommerce #content input.button,
    .woocommerce #respond input#submit,
    .woocommerce a.button,
    .woocommerce button.button,
    .woocommerce input.button,
    .woocommerce-page #content input.button,
    .woocommerce-page #respond input#submit,
    .woocommerce-page a.button,
    .woocommerce-page button.button,
    .woocommerce-page input.button,
    .woocommerce a.added_to_cart,
    .woocommerce-page a.added_to_cart,
    .woocommerce #content input.button.alt:hover,
    .woocommerce #respond input#submit.alt:hover,
    .woocommerce a.button.alt:hover,
    .woocommerce button.button.alt:hover,
    .woocommerce input.button.alt:hover,
    .woocommerce-page #content input.button.alt:hover,
    .woocommerce-page #respond input#submit.alt:hover,
    .woocommerce-page a.button.alt:hover,
    .woocommerce-page button.button.alt:hover,
    .woocommerce-page input.button.alt:hover,
    .btn:active,
    .btn-primary,
    .btn-primary:focus,
    .btn.alt:hover,
    .btn.btn-black:hover,
    .btn.btn-bordered:hover,
    .btn.btn-grey:hover,
    .blog-post .tags a:hover,
    .post-social-wrapper .post-social-title a:hover,
    .sidebar .widget_calendar th,
    .sidebar .widget_calendar tfoot td,
    .sidebar .widget_tag_cloud .tagcloud a:hover,
    .sidebar .widget_product_tag_cloud .tagcloud a:hover,
    .comment-meta .reply a:hover,
    body .owl-theme .owl-controls .owl-page.active span,
    body .owl-theme .owl-controls.clickable .owl-page:hover span,
    body .owl-theme .owl-controls .owl-nav div.owl-prev:hover,
    body .owl-theme .owl-controls .owl-nav div.owl-next:hover,
    body .owl-theme .owl-dots .owl-dot.active span,
    body .owl-theme .owl-dots .owl-dot:hover span,
    body .ig_popup.ig_inspire .ig_button,
    body .ig_popup.ig_inspire input[type="submit"],
    body .ig_popup.ig_inspire input[type="button"],
    .wp-block-button a.wp-block-button__link,
    .woocommerce .widget_price_filter .ui-slider .ui-slider-range,
    .woocommerce .widget_price_filter .ui-slider .ui-slider-handle,
    .sidebar .widget.widget_saxon_social_icons a:hover,
    .saxon-post-block .post-categories a,
    .saxon-post .post-categories a,
    .single-post .blog-post-single .tags a:hover,
    .sidebar .widget .post-categories a {
        background-color: <?php echo esc_html($color_main); ?>;
    }
    a,
    .container-page-item-title.with-bg .post-info-share .post-social a:hover,
    .header-menu li a:hover,
    .author-bio .author-social-icons li a:hover,
    .post-social-wrapper .post-social a:hover,
    .navigation-post .nav-post-prev:hover .nav-post-name,
    .navigation-post .nav-post-next:hover .nav-post-name,
    body .select2-results .select2-highlighted,
    .saxon-theme-block > h2,
    .btn.btn-text:hover,
    .post-worthreading-post-container .post-worthreading-post-title a:hover,
    .social-icons-wrapper a:hover,
    .saxon-post-block .post-title:hover,
    .saxon-post-block .post-title a:hover,
    .saxon-post .post-title:hover,
    .saxon-post .post-title a:hover,
    .navigation-paging.navigation-post a:hover,
    .navigation-paging .wp-pagenavi span.current,
    .navigation-paging .wp-pagenavi a:hover,
    .woocommerce ul.cart_list li a:hover,
    .woocommerce ul.product_list_widget li a:hover,
    .widget_recent_entries li a:hover,
    .widget_recent_comments li a:hover,
    .widget_categories li a:hover,
    .widget_archive li a:hover,
    .widget_meta li a:hover,
    .widget_pages li a:hover,
    .widget_rss li a:hover,
    .widget_nav_menu li a:hover,
    .comments-area .navigation-paging .nav-previous a:hover,
    .comments-area .navigation-paging .nav-next a:hover,
    .saxon-post .post-like-button:hover i.fa-heart-o {
        color: <?php echo esc_html($color_main); ?>;
    }
    a.btn,
    .btn,
    .btn:focus,
    input[type="submit"],
    .btn.btn-black:hover,
    .btn:active,
    .btn-primary,
    .btn-primary:focus,
    .btn.alt:hover,
    .btn.btn-bordered:hover,
    .btn.btn-grey:hover,
    .woocommerce #content input.button,
    .woocommerce #respond input#submit,
    .woocommerce a.button,
    .woocommerce button.button,
    .woocommerce input.button,
    .woocommerce-page #content input.button,
    .woocommerce-page #respond input#submit,
    .woocommerce-page a.button,
    .woocommerce-page button.button,
    .woocommerce-page input.button,
    .woocommerce a.added_to_cart,
    .woocommerce-page a.added_to_cart,
    .woocommerce #content input.button.alt:hover,
    .woocommerce #respond input#submit.alt:hover,
    .woocommerce a.button.alt:hover,
    .woocommerce button.button.alt:hover,
    .woocommerce input.button.alt:hover,
    .woocommerce-page #content input.button.alt:hover,
    .woocommerce-page #respond input#submit.alt:hover,
    .woocommerce-page a.button.alt:hover,
    .woocommerce-page button.button.alt:hover,
    .woocommerce-page input.button.alt:hover,
    .wp-block-button a.wp-block-button__link {
        border-color: <?php echo esc_html($color_main); ?>;
    }
    .btn:hover,
    .btn.btn-white:hover,
    input[type="submit"]:hover,
    .btn.alt,
    .btn.btn-black,
    .btn-primary:hover,
    .btn-primary:active,
    .wp-block-button a.wp-block-button__link:hover,
    .woocommerce #content input.button.alt,
    .woocommerce #respond input#submit.alt,
    .woocommerce a.button.alt,
    .woocommerce button.button.alt,
    .woocommerce input.button.alt,
    .woocommerce-page #content input.button.alt,
    .woocommerce-page #respond input#submit.alt,
    .woocommerce-page a.button.alt,
    .woocommerce-page button.button.alt,
    .woocommerce-page input.button.alt,
    .woocommerce #content input.button:hover,
    .woocommerce #respond input#submit:hover,
    .woocommerce a.button:hover,
    .woocommerce button.button:hover,
    .woocommerce input.button:hover,
    .woocommerce-page #content input.button:hover,
    .woocommerce-page #respond input#submit:hover,
    .woocommerce-page a.button:hover,
    .woocommerce-page button.button:hover,
    .woocommerce-page input.button:hover {
        background-color: <?php echo esc_html($color_button_hover); ?>;
    }
    .btn:hover,
    .btn.btn-white:hover,
    input[type="submit"]:hover,
    .btn.alt,
    .btn.btn-black,
    .btn-primary:hover,
    .btn-primary:active,
    .woocommerce #content input.button.alt,
    .woocommerce #respond input#submit.alt,
    .woocommerce a.button.alt,
    .woocommerce button.button.alt,
    .woocommerce input.button.alt,
    .woocommerce-page #content input.button.alt,
    .woocommerce-page #respond input#submit.alt,
    .woocommerce-page a.button.alt,
    .woocommerce-page button.button.alt,
    .woocommerce-page input.button.alt,
    .woocommerce #content input.button:hover,
    .woocommerce #respond input#submit:hover,
    .woocommerce a.button:hover,
    .woocommerce button.button:hover,
    .woocommerce input.button:hover,
    .woocommerce-page #content input.button:hover,
    .woocommerce-page #respond input#submit:hover,
    .woocommerce-page a.button:hover,
    .woocommerce-page button.button:hover,
    .woocommerce-page input.button:hover {
        border-color: <?php echo esc_html($color_button_hover); ?>;
    }
    .nav > li .sub-menu {
        background-color: <?php echo esc_html($color_mainmenu_submenu_bg); ?>;
    }
    .nav .sub-menu li.menu-item > a {
        color: <?php echo esc_html($color_mainmenu_submenu_link); ?>;
    }
    .nav .sub-menu li.menu-item > a:hover {
        color: <?php echo esc_html($color_mainmenu_submenu_link_hover); ?>;
    }
    .navbar .nav > li > a {
        color: <?php echo esc_html($color_mainmenu_link); ?>;
    }
    .navbar .nav > li > a:hover {
        color: <?php echo esc_html($color_mainmenu_link_hover); ?>;
    }
    footer {
        background-color: <?php echo esc_html($color_footer_bg); ?>;
    }
    footer.footer-black {
        background-color: <?php echo esc_html($color_footer_dark_bg); ?>;
    }
    .footer-sidebar-2-wrapper {
        background-color: <?php echo esc_html($color_footer_sidebar_bg); ?>;
    }
    .header-menu-bg,
    .header-menu-bg .header-menu li ul {
        background-color: <?php echo esc_html($color_topmenu_bg); ?>;
    }
    .header-menu-bg.menu_black,
    .header-menu-bg.menu_black .header-menu .menu-top-menu-container-toggle + div,
    .header-menu-bg.menu_black .header-menu li ul {
        background-color: <?php echo esc_html($color_topmenu_dark_bg); ?>;
    }
    .blog-post-reading-progress {
        border-color: <?php echo esc_html($color_post_reading_progressbar); ?>;
    }

    <?php
    	$out = ob_get_clean();

		$out .= ' /*' . date("Y-m-d H:i") . '*/';
		/* RETURN */
		return $out;
	}
    endif;
