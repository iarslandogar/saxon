<?php
/**
 * Theme dashboard
 *
 * @package Saxon
 */

/**
 * Theme dashboard class.
 */
class Saxon_Dashboard {

	public function __construct() {

		require_once get_parent_theme_file_path('/inc/theme-dashboard/inc/theme-activation.php');
		require_once get_parent_theme_file_path('/inc/theme-dashboard/inc/theme-system.php');

		add_action( 'admin_menu', array( $this, 'add_menu_link' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_init', array( $this, 'redirect' ) );

	}

	/**
	 * Add theme dashboard page.
	 */
	public function add_menu_link() {

		$page_title = esc_html__('Saxon WordPress Theme Dashboard', 'saxon');
		add_theme_page( $page_title, 'Theme Dashboard', 'manage_options', 'saxon_dashboard', array( $this, 'dashboard_welcome' ), null, 3 );
	}

	/**
	 * Show dashboard page.
	 */
	public function dashboard_welcome() {
		?>
		<div class="wrap about-wrap theme-dashboard-wrapper welcome-wrapper">
		<?php include get_template_directory() . '/inc/theme-dashboard/inc/header.php'; ?>
		<?php include get_template_directory() . '/inc/theme-dashboard/inc/theme-welcome.php'; ?>
		</div>
		<?php
	}

	/**
	 * Enqueue scripts for dashboard page.
	 *
	 * @param string $hook Page hook.
	 */
	public function enqueue_scripts( $hook ) {

		wp_enqueue_media();
		wp_enqueue_style( "theme-dashboard-style", get_template_directory_uri() . '/inc/theme-dashboard/assets/style.css' );

	}

	/**
	 * Redirect to dashboard page after theme activation.
	 */
	public function redirect() {
		global $pagenow;
		if ( is_admin() && isset( $_GET['activated'] ) && 'themes.php' === $pagenow ) {
			wp_safe_redirect( admin_url( "admin.php?page=saxon_dashboard" ) );
			exit;
		}
	}
}

new Saxon_Dashboard();
