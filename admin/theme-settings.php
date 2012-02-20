<?php
/* This file contains bootstrap for Options Framework Theme Settings */

//get the theme prefix
$prefix = hybrid_get_prefix();

define('OPTIONS_FRAMEWORK_URL', trailingslashit( THEME_ADMIN_DIR ) );
define('OPTIONS_FRAMEWORK_DIRECTORY', trailingslashit( THEME_ADMIN_URI ) );

require_once (OPTIONS_FRAMEWORK_URL . 'options-framework.php');

add_action( 'admin_menu', 'theme_admin_setup' );
add_action( 'admin_enqueue_scripts', 'theme_admin_register_scripts' );
add_action( 'admin_enqueue_scripts', 'theme_admin_register_styles' );
add_action( 'admin_enqueue_styles', 'theme_admin_print_styles' );
add_action( 'admin_enqueue_styles', 'theme_admin_print_scripts' );

function theme_admin_setup(){

	//get the theme prefix
	$prefix = hybrid_get_prefix();
	
	/* Create a settings meta box only on the theme settings page. */
	//add_action( "load-appearance_page_theme-settings", 'mobility21_theme_settings_meta_boxes' );

	/* Add a filter to validate/sanitize your settings. */
	//add_filter( "sanitize_option_{$prefix}_theme_settings", 'mobility21_theme_validate_settings' );
	
	//allows us to use media library with thickbox
	//add_thickbox(); // load thickbox
	//wp_enqueue_script('media-upload'); //displays media library in modal window. requires thickbox
}

/**
 * Register Scripts
 */
function theme_admin_register_scripts(){
	wp_register_script('jquery-datepicker', THEME_ADMIN_URI . '/js/jquery.ui.datepicker.js', array('jquery', 'jquery-ui-core') );
	wp_register_script('jquery-timepicker', THEME_ADMIN_URI . '/jquery.ui.timepicker.addon.js', array( 'jquery-datepicker' ) );
}

function theme_admin_print_scripts(){
}

/**
 * Register Styles
 */
function theme_admin_register_styles(){
	wp_enqueue_style('theme-admin-style', trailingslashit( THEME_ADMIN_URI ) . 'css/pogidude-admin-style.css' );
}

function theme_admin_print_styles(){
	wp_enqueue_style('theme-admin-style');
}

