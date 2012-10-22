<?php

/* Load the hybrid core theme framework. */
require_once( trailingslashit(TEMPLATEPATH) . 'hybrid-core/hybrid.php' );
$theme = new Hybrid();

/* Do theme setup on the 'after_setup_theme' hook. */
add_action( 'after_setup_theme', 'theme_name_setup', 10 );

/**
 * Theme setup function.  This function adds support for theme features and defines the default theme
 * actions and filters.
 *
 * @since 1.0
 */
function theme_name_setup() {
	/* Get action/filter hook prefix. */
	$prefix = hybrid_get_prefix();

	//initialize library
	require_once THEME_DIR . '/library/init.php';

	//LOAD THEME SETTINGS
	require_once( trailingslashit( THEME_ADMIN_DIR ) . 'theme-settings.php' );
	
	/* ADD THEME SUPPORT FOR CORE FRAMEWORK FEATURES */
	add_theme_support( 'hybrid-core-menus', array( 'primary', 'secondary' ) );
	add_theme_support( 'hybrid-core-sidebars', array( 'primary' ) );
	add_theme_support( 'hybrid-core-widgets' );
	add_theme_support( 'hybrid-core-shortcodes' );
	//add_theme_support( 'hybrid-core-post-meta-box' );
	//add_theme_support( 'hybrid-core-theme-settings' );
	//add_theme_support( 'hybrid-core-meta-box-footer' );
	//add_theme_support( 'hybrid-core-drop-downs' );
	//add_theme_support( 'hybrid-core-seo' );
	add_theme_support( 'hybrid-core-template-hierarchy' );
	
	/* ADD THEME SUPPORT FOR FRAMEWORK EXTENSIONS */
	//add_theme_support( 'breadcrumb-trail' );
	add_theme_support( 'loop-pagination' );
	add_theme_support( 'get-the-image' );
	
	/* Setup Theme Options Page */
	
	/* ADD THEME SUPPORT FOR WP FEATURES */
	add_theme_support( 'post-thumbnails' );
	//add_theme_support( 'automatic-feed-links' );
	//add_theme_support( 'post-formats', array( 'aside', 'gallery' ) );
	
	/* ADD CUSTOM POST TYPES */

	/* SETUP ACTIONS */
	remove_action('wp_head', 'wp_generator'); //hide wordpress version

	/* SETUP FILTERS */
	
	/* SHORTCODES */
	
	/* REGISTER/QUEUE ASSETS */
	add_action( 'init', 'theme_register_assets' );
	add_action( 'wp_enqueue_scripts', 'theme_queue_assets' );
	
	/* REGISTER SIDEBARS */
	//add_action( 'widgets_init', 'theme_widgets_init' );
	
	/* REGISTER WIDGETS */
	//include_once( trailingslashit( THEMELIB_FUNCTIONS ) . 'widgets.php' );
	
	/* REGISTER ADDITIONAL MENUS */
	//add_action( 'init', 'register_footer_menu' );
	
	/* COMMON MODIFICATIONS */
	//add_filter( 'excerpt_length', 'theme_excerpt_length' );
	//Replaces "[...]" 
	//add_filter( 'excerpt_more', 'theme_auto_excerpt_more' );
	//adds pretty continue reading link to custom excerpts
	//add_filter( 'get_the_excerpt', 'theme_custom_excerpt_more' );
	
	/* ADMIN STUFF */
	if(is_admin()){
		//allows us to use media library with thickbox for our use. Usage: 
		//<a href="http://wp.dev/squarmilner/wp-admin/media-upload.php?type=image&TB_iframe=1">Media Lib</a>
		add_thickbox(); // load thickbox
		wp_enqueue_script('media-upload'); //displays media library in modal window. requires thickbox
	}

}

function theme_register_assets(){
	/* Scripts */
	wp_register_script( 'modernizr', trailingslashit( THEME_JS ) . 'modernizr.js' );
	wp_register_script( 'cis-utilities', trailingslashit(THEME_JS) . 'utilities.js', array( 'jquery' ), "1.0" );
	wp_register_script( 'cis-js', trailingslashit( THEME_JS ) . 'custom.js', array( 'theme-utilities' ), "1.0" );
	wp_register_script( 'placeholder-fix', trailingslashit( THEME_JS ) . 'placeholderfix.js', array( 'jquery', 'modernizr' ), "1.0" );
	
	/* Styles */
}

function theme_queue_assets(){
	if( is_admin() ) return;
	
	/* Scripts */
	wp_enqueue_script( 'cis-utilities' );
	wp_enqueue_script( 'cis-js' );
	wp_enqueue_script( 'placeholder-fix' );
	
	//build array of parameters that we want to be made available to JS Scripts
	$js_global_vars = theme_js_vars();
	//use wp_localize_script() to add a global variable for all scripts to use.
	wp_localize_script( 'script-id', 'MyGlobal', $js_global_vars );
	
	/* Styles */
	wp_enqueue_style( 'theme-print-style' );
}

/**
 * Return global js vars
 */
function theme_js_vars(){
	$vars = array( 
				'ajaxUrl' => admin_url('admin-ajax.php'),
				'templateUrl' => THEME_URI
			);
	return $vars;
}

/**
 * Register Sidebar Widget Areas
 */
function theme_widgets_init(){

	$sidebar = array(
		'id' => 'sidebar-id',
		'name' => __( 'Sidebar Name', $domain ),
		'description' => __( 'Sidebar Description', $domain ),
		'before_widget' => '<div id="%1$s" class="widget %2$s widget-%2$s"><div class="widget-wrap widget-inside">',
		'after_widget' => '</div></div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	);
	
	register_sidebar($sidebar);
	
}

/**
 * Sets the posts excerpt length
 */
function theme_excerpt_length( $length ) {
	return 40;
}

/**
 * Returns a "Continue Reading" link for excerpts
 */
function theme_continue_reading_link() {
	return ' <a href="'. get_permalink() . '">Continue reading <span class="meta-nav">&rarr;</span></a>';
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and theme_continue_reading_link().
 */
function theme_auto_excerpt_more( $more ) {
	return ' &hellip;' . theme_continue_reading_link();
}

/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 */
function theme_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= theme_continue_reading_link();
	}
	return $output;
}

/**
 * Register Footer Menu
 */
function register_footer_menu() {
	register_nav_menu('footer', 'Footer Menu');
}
