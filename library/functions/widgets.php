<?php
/**
 * Sets up the theme's custom-made widgets 
 *
 * @package Mobility21_Library
 * @subpackage functions
 */
 
/* Register Mobility21 widgets */
add_action('widgets_init','mobility21_register_widgets');

function mobility21_register_widgets(){
	
	global $pagenow;
	
	/* Load the theme's widget files */
	require_once( MOBILITY_CLASSES . '/widget-image-button.php' );
	require_once( MOBILITY_CLASSES . '/widget-m21-single-ad.php' );
	require_once( MOBILITY_CLASSES . '/widget-m21-banner-item.php' );
	require_once( MOBILITY_CLASSES . '/widget-m21-random-ad.php' );
	
	/* Register the theme's widgets */
	register_widget( 'Image_Button_Widget' );
	register_widget( 'M21_Single_Ad_Widget' );	
	register_widget( 'M21_Banner_Item_Widget' );
	register_widget( 'M21_Random_Ad_Widget' );
	
	//load custom scripts 
	add_action( 'load-widgets.php', 'mobility21_widgets_add_scripts' );
}

function mobility21_widgets_add_scripts(){
	add_thickbox();
	wp_enqueue_script('media-upload');
	//wp_enqueue_script('mobility21-slider-item-widget-script', THEME_URI . '/admin/js/slider.widget.js', array('jquery'), "1.0" );
}

?>
