<?php
/*
Plugin Name: MGT Mega Menu (based on Sweet Custom Menu)
Plugin URL: http://remicorson.com/sweet-custom-menu
Description: A little plugin to add attributes to WordPress menus
Version: 1.0
Author: Remi Corson
Author URI: http://remicorson.com
Contributors: corsonr
*/

class rc_sweet_custom_menu {

	/*--------------------------------------------*
	 * Constructor
	 *--------------------------------------------*/

	/**
	 * Initializes the plugin by setting localization, filters, and administration functions.
	 */
	function __construct() {

		// add custom menu fields to menu
		add_filter( 'wp_setup_nav_menu_item', array( $this, 'rc_scm_add_custom_nav_fields' ) );

		// save menu custom fields
		add_action( 'wp_update_nav_menu_item', array( $this, 'rc_scm_update_custom_nav_fields'), 10, 3 );

		// edit menu walker
		add_filter( 'wp_edit_nav_menu_walker', array( $this, 'rc_scm_edit_walker'), 10, 2 );

		// Load styles
		add_action('wp_enqueue_scripts', array( $this, 'rc_scm_load_media'));
		add_action('admin_init', array( $this, 'rc_scm_load_admin_media'));

	} // end constructor

	/**
	 * Load menu styles and scripts.
	 */
	function rc_scm_load_media() {
		wp_enqueue_style('saxon-mega-menu', get_template_directory_uri() . '/inc/modules/mega-menu/css/mega-menu.css');
		wp_enqueue_style('saxon-mega-menu-responsive', get_template_directory_uri() . '/inc/modules/mega-menu/css/mega-menu-responsive.css');

		wp_register_script('saxon-mega-menu', get_template_directory_uri() . '/inc/modules/mega-menu/js/mega-menu.js', array(), '1.0.0', true);
		wp_enqueue_script('saxon-mega-menu');

	}

	function rc_scm_load_admin_media() {
		wp_enqueue_style('saxon-mega-menu-admin', get_template_directory_uri() . '/inc/modules/mega-menu/css/mega-menu-admin.css');

		wp_register_script('saxon-mega-menu-admin', get_template_directory_uri() . '/inc/modules/mega-menu/js/mega-menu-admin.js', array(), '1.0.0', true);
		wp_enqueue_script('saxon-mega-menu-admin');
	}

	/**
	 * Add custom fields to $item nav object
	 * in order to be used in custom Walker
	 *
	 * @access      public
	 * @since       1.0
	 * @return      void
	*/
	function rc_scm_add_custom_nav_fields( $menu_item ) {

	    $menu_item->background_url = get_post_meta( $menu_item->ID, '_menu_item_background_url', true );
	    $menu_item->backgroundrepeat = get_post_meta( $menu_item->ID, '_menu_item_backgroundrepeat', true );
	    $menu_item->backgroundpositionx = get_post_meta( $menu_item->ID, '_menu_item_backgroundpositionx', true );
	    $menu_item->backgroundpositiony = get_post_meta( $menu_item->ID, '_menu_item_backgroundpositiony', true );

		$menu_item->badgetitle = get_post_meta( $menu_item->ID, '_menu_item_badgetitle', true );
		$menu_item->badgecolor = get_post_meta( $menu_item->ID, '_menu_item_badgecolor', true );

	    $menu_item->columns = get_post_meta( $menu_item->ID, '_menu_item_columns', true );
	    $menu_item->icon = get_post_meta( $menu_item->ID, '_menu_item_icon', true );

	    $menu_item->fullwidth = get_post_meta( $menu_item->ID, '_menu_item_fullwidth', true );
	    $menu_item->dropdownposition = get_post_meta( $menu_item->ID, '_menu_item_dropdownposition', true );

	    $menu_item->sidebar = get_post_meta( $menu_item->ID, '_menu_item_sidebar', true );

	    return $menu_item;

	}

	/**
	 * Save menu custom fields
	 *
	 * @access      public
	 * @since       1.0
	 * @return      void
	*/
	function rc_scm_update_custom_nav_fields( $menu_id, $menu_item_db_id, $args ) {

	    // Check if element is properly sent
	    if ( isset($_REQUEST['menu-item-background_url'][$menu_item_db_id]) && is_array( $_REQUEST['menu-item-background_url']) ) {
    		$background_url_value = $_REQUEST['menu-item-background_url'][$menu_item_db_id];
        	update_post_meta( $menu_item_db_id, '_menu_item_background_url', $background_url_value );
	    }

	    if ( isset($_REQUEST['menu-item-backgroundrepeat'][$menu_item_db_id]) && is_array( $_REQUEST['menu-item-backgroundrepeat']) ) {
	        $backgroundrepeat_value = $_REQUEST['menu-item-backgroundrepeat'][$menu_item_db_id];
	        update_post_meta( $menu_item_db_id, '_menu_item_backgroundrepeat', $backgroundrepeat_value );
	    }

	    if ( isset($_REQUEST['menu-item-backgroundpositionx'][$menu_item_db_id]) && is_array( $_REQUEST['menu-item-backgroundpositionx']) ) {
	        $backgroundpositionx_value = $_REQUEST['menu-item-backgroundpositionx'][$menu_item_db_id];
	        update_post_meta( $menu_item_db_id, '_menu_item_backgroundpositionx', $backgroundpositionx_value );
	    }

	    if ( isset($_REQUEST['menu-item-backgroundpositiony'][$menu_item_db_id]) && is_array( $_REQUEST['menu-item-backgroundpositiony']) ) {
	        $backgroundpositiony_value = $_REQUEST['menu-item-backgroundpositiony'][$menu_item_db_id];
	        update_post_meta( $menu_item_db_id, '_menu_item_backgroundpositiony', $backgroundpositiony_value );
	    }

	    if ( isset($_REQUEST['menu-item-columns']) && is_array( $_REQUEST['menu-item-columns']) ) {
	    	if(isset($_REQUEST['menu-item-columns'][$menu_item_db_id])) {
	    		$columns_value = $_REQUEST['menu-item-columns'][$menu_item_db_id];
	        	update_post_meta( $menu_item_db_id, '_menu_item_columns', $columns_value );
	    	}
	    }

	    if ( isset($_REQUEST['menu-item-icon']) && is_array( $_REQUEST['menu-item-icon']) ) {
	    	if(isset($_REQUEST['menu-item-icon'][$menu_item_db_id])) {
	    		$icon_value = $_REQUEST['menu-item-icon'][$menu_item_db_id];
	        	update_post_meta( $menu_item_db_id, '_menu_item_icon', $icon_value );
	    	}
	    }

	    if ( isset($_REQUEST['menu-item-badgetitle']) && is_array( $_REQUEST['menu-item-badgetitle']) ) {
	    	if(isset($_REQUEST['menu-item-badgetitle'][$menu_item_db_id])) {
	    		$badgetitle_value = $_REQUEST['menu-item-badgetitle'][$menu_item_db_id];
	        	update_post_meta( $menu_item_db_id, '_menu_item_badgetitle', $badgetitle_value );
	    	}
	    }

	    if ( isset($_REQUEST['menu-item-badgecolor']) && is_array( $_REQUEST['menu-item-badgecolor']) ) {
	    	if(isset($_REQUEST['menu-item-badgecolor'][$menu_item_db_id])) {
	    		$badgecolor_value = $_REQUEST['menu-item-badgecolor'][$menu_item_db_id];
	        	update_post_meta( $menu_item_db_id, '_menu_item_badgecolor', $badgecolor_value );
	    	}
	    }

	    if ( isset($_REQUEST['menu-item-fullwidth']) && is_array( $_REQUEST['menu-item-fullwidth']) ) {
	    	if(isset($_REQUEST['menu-item-fullwidth'][$menu_item_db_id])) {
	    		$fullwidth_value = $_REQUEST['menu-item-fullwidth'][$menu_item_db_id];
	        	update_post_meta( $menu_item_db_id, '_menu_item_fullwidth', $fullwidth_value );
	    	} else {
		    	$fullwidth_value = 'off';
		        update_post_meta( $menu_item_db_id, '_menu_item_fullwidth', $fullwidth_value );
		    }
	    }

	    if ( isset($_REQUEST['menu-item-dropdownposition']) && is_array( $_REQUEST['menu-item-dropdownposition']) ) {
	    	if(isset($_REQUEST['menu-item-dropdownposition'][$menu_item_db_id])) {
	    		$fullwidth_value = $_REQUEST['menu-item-dropdownposition'][$menu_item_db_id];
	        	update_post_meta( $menu_item_db_id, '_menu_item_dropdownposition', $fullwidth_value );
	    	} else {
		    	$fullwidth_value = 'right';
		        update_post_meta( $menu_item_db_id, '_menu_item_dropdownposition', $fullwidth_value );
		    }
	    }

	    if ( isset($_REQUEST['menu-item-sidebar']) && is_array( $_REQUEST['menu-item-sidebar']) ) {
	    	if(isset($_REQUEST['menu-item-sidebar'][$menu_item_db_id])) {
	    		$sidebar_value = $_REQUEST['menu-item-sidebar'][$menu_item_db_id];
	        	update_post_meta( $menu_item_db_id, '_menu_item_sidebar', $sidebar_value );
	    	}
	    }

	}

	/**
	 * Define new Walker edit
	 *
	 * @access      public
	 * @since       1.0
	 * @return      void
	*/
	function rc_scm_edit_walker($walker, $menu_id) {

		// Menus that support Mega Menu
		$theme_location = 'main';

		$locations = get_nav_menu_locations();

		if(isset($locations[$theme_location])) {
			$megamenu_id = $locations[$theme_location];
		} else {
			$megamenu_id = '';
		}

		if($megamenu_id == $menu_id) {
			return 'Walker_Nav_Menu_Edit_Custom';
		} else {
			return 'Walker_Nav_Menu_Edit';
		}

	}

}

// instantiate plugin's class
$GLOBALS['sweet_custom_menu'] = new rc_sweet_custom_menu();

include_once( 'edit_custom_walker.php' );
include_once( 'custom_walker.php' );
