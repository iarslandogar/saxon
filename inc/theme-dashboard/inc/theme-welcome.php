<?php
/**
 * Theme dashboard welcome section
 *
 * @package Saxon
 */

?>
<?php
// Theme Activation
if(isset($_REQUEST['act']) && $_REQUEST['act'] == 'registration_complete') {
	update_option('saxon_license_key_status', 'activated');
	delete_option('saxon_update');
	delete_option('saxon_update_cache_date');
}

if(isset($_REQUEST['act']) && $_REQUEST['act'] == 'registration_reset') {
	delete_option('saxon_license_key_status');
}

if(isset($_REQUEST['act']) && $_REQUEST['act'] == 'update_reset') {
	delete_option('saxon_update');
	delete_option('saxon_update_cache_date');
}
?>
<h2 class="nav-tab-wrapper">
	<a href="<?php echo esc_url(admin_url( 'themes.php?page=saxon_dashboard' )); ?>" class="nav-tab nav-tab-active"><?php esc_html_e( 'Getting started', 'saxon' ); ?></a>
	<a href="<?php echo esc_url(admin_url( 'customize.php?autofocus[panel]=theme_settings_panel' )); ?>" class="nav-tab"><?php esc_html_e( 'Theme options', 'saxon' ); ?></a>
	<?php if(get_option( 'saxon_license_key_status', false ) !== 'activated'):?>
	<a href="<?php echo esc_url(admin_url( 'themes.php?page=saxon_activate_theme' )); ?>" class="nav-tab"><?php esc_html_e( 'Theme activation', 'saxon' ); ?></a>
	<?php endif; ?>
	<a href="<?php echo esc_url(admin_url( 'themes.php?page=saxon_system_information' )); ?>" class="nav-tab"><?php esc_html_e( 'System information', 'saxon' ); ?></a>

</h2>

<div class="theme-welcome-wrapper">
	<div class="feature-section two-col">
		<div class="col">
			<?php if(get_option( 'saxon_license_key_status', false ) !== 'activated'): ?>
			<h3 class="text-color-highlight"><?php esc_html_e( 'First of all - Activate your theme', 'saxon' ); ?></h3>
			<p>
				<?php
				esc_html_e( 'Register your purchase to get themes updates notifications, import theme demos and get access to premium dedicated support.', 'saxon' );
				?>
			</p>
			<a class="button button-primary" href="<?php echo esc_url( admin_url( 'themes.php?page=saxon_activate_theme' ) ) ?>" target="_blank"><?php esc_html_e( 'Activate Theme', 'saxon' ); ?></a>
			<?php endif; ?>

			<h3><?php esc_html_e( 'Step 1 - Install Required Plugins', 'saxon' ); ?></h3>
			<p>
				<?php
				esc_html_e( 'Our theme has some required and optional plugins to function properly. Please install theme required plugins.', 'saxon' );
				?>
			</p>
			<?php if(TGM_Plugin_Activation::get_instance()->is_tgmpa_complete()): ?>
			<a class="button button-secondary" disabled href="#" target="_blank"><?php esc_html_e( 'Plugins installed', 'saxon' ); ?></a>
			<?php else: ?>
			<a class="button button-primary" href="<?php echo esc_url( admin_url( 'themes.php?page=install-required-plugins&plugin_status=install' ) ) ?>" target="_blank"><?php esc_html_e( 'Install Plugins', 'saxon' ); ?></a>
			<?php endif; ?>
			<h3><?php esc_html_e( 'Step 2 - Import Demo Data (Optional)', 'saxon' ); ?></h3>
			<p><?php _e( 'We prepared several demos for you to start with. Demo contain sample content, widgets, sliders and theme settings. You can import it with 1 click in our famouse 1-Click Demo Data installer.', 'saxon' );
			?></p>
			<a class="button button-primary" href="<?php echo esc_url( admin_url( 'themes.php?page=radium_demo_installer' ) ) ?>" target="_blank"><?php esc_html_e( 'Import Demo Data', 'saxon' ); ?></a>
			<h3><?php esc_html_e( 'Got questions? We\'re here to help you!', 'saxon' ); ?></h3>
			<p class="about"><?php esc_html_e( 'Check our Help Center articles. If you can\'t find solution for your problem feel free to contact your dedicated support manager.', 'saxon' ); ?></p>

			<a href="<?php echo esc_url( 'http://support.magniumthemes.com/' ); ?>" target="_blank" class="button button-secondary"><?php esc_html_e( 'Visit Help Center', 'saxon' ); ?></a>

		</div>
		<div class="col">
			<h3><?php esc_html_e( 'Customize Your Site', 'saxon' ); ?></h3>
			<p><?php esc_html_e( 'You can easy manage all theme options in WordPress Customizer, that allows you to preview any changes that you make on the fly.', 'saxon' ); ?></p>
			<p>
				<a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[panel]=theme_settings_panel' ) ); ?>" class="button button-primary"><?php esc_html_e( 'Manage Theme Options', 'saxon' ); ?></a>
			</p>
			<h3><?php esc_html_e( 'Read Theme Documentation', 'saxon' ); ?></h3>
			<p class="about"><?php esc_html_e( 'Please read our detailed step by step theme documentation first to understand how to use the theme and all its features.', 'saxon' ); ?></p>

			<a href="<?php echo esc_url( 'http://magniumthemes.com/go/saxon-docs/' ); ?>" target="_blank" class="button button-secondary"><?php esc_html_e( 'Read Documentation', 'saxon' ); ?></a>

			<h3><?php esc_html_e( 'Speed Up, Optimize and Secure your website', 'saxon' ); ?></h3>
			<p class="about"><?php esc_html_e( 'Get maximum from your website with our professional Premium "All in one" WordPress Security, Speed and SEO optimization services.', 'saxon' ); ?></p>

			<a href="<?php echo esc_url( 'http://magniumthemes.com/go/website-boost/' ); ?>" target="_blank" class="button button-secondary"><?php esc_html_e( 'Learn more', 'saxon' ); ?></a>


		</div>
	</div>
	<hr>

</div>
