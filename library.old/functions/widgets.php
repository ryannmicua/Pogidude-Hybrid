<?php
/**
 * Sets up the theme's custom-made widgets 
 *
 * @package Pogidude_Library
 * @subpackage functions
 */
 
/* Register Mobility21 widgets */
add_action('widgets_init','pogidude_register_widgets');

function pogidude_register_widgets(){
	
	global $pagenow;
	
	/* Load the theme's widget files */
	//require_once( THEMELIB_CLASSES . '/widget-image-button.php' );
	
	/* Register the theme's widgets */
	//register_widget( 'Image_Button_Widget' );
	
	//load custom scripts 
	//add_action( 'load-widgets.php', 'pogidude_widgets_add_scripts' );
}

function pogidude_widgets_add_scripts(){
	add_thickbox();
	wp_enqueue_script('media-upload');
	//wp_enqueue_script('mobility21-slider-item-widget-script', THEME_URI . '/admin/js/slider.widget.js', array('jquery'), "1.0" );
}

?>
