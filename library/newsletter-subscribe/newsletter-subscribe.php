<?php
/**
 * Newsletter Subscribe AJAX Processor
 *
 * This file contains things for making the newsletter
 * subscription work through AJAX
 */

define( 'BULLDOG_NEWSLETTER_URI', trailingslashit( THEMELIB_URI ) . 'newsletter-subscribe' );
define( 'BULLDOG_NEWSLETTER_DIR', trailingslashit( THEMELIB_DIR ) . 'newsletter-subscribe' );

//setup action to handle ajax call
add_action( 'wp_ajax_newsletter-ajax-subscribe', 'bulldog_newsletter_subscribe_ajax_processor' );
add_action( 'wp_ajax_nopriv_newsletter-ajax-subscribe', 'bulldog_newsletter_subscribe_ajax_processor' );

//load javascript
add_action( 'wp_print_scripts', 'bulldog_newsletter_subscribe_scripts' );

//register newsletter subscribe widget
add_action( 'widgets_init', 'bulldog_newsletter_subscribe_widget' );

//add our own newsletter options to the Options Framework "options" array
add_filter( 'pogidude_of_option_array', 'bulldog_newsletter_add_options' );

/**
 * Main AJAX Processor
 */
function bulldog_newsletter_subscribe_ajax_processor(){
	
	$return = array();
	
	//nonce security check
	if( empty( $_POST['nonce'] ) || !wp_verify_nonce( $_POST['nonce'], 'newsletter-nonce' ) ){
		$return['error'] = true;
		$return['message'] = 'Error: Please contact the site administrator.';
		echo json_encode( $return );
		exit;
	}
	
	//load MailChimp's MCAPI wrapper
	require_once( 'MCAPI.class.php' );

	//process email address input
	$email = $_POST['email'];
	
	//check if email is valid
	if( !is_email( $email ) ){
		//email is invalid, stop processing and return an error message
		$return['error'] = true;
		$return['message'] = 'Please enter a valid e-mail address.';
		echo json_encode( $return );
		exit;
	}
	
	//get API Key stored in options
	//grab an API Key from http://admin.mailchimp.com/account/api/
	$api_key = of_get_option( 'newsletter-mc-api-key' );
	
	//get Unique List ID stored in options
	// grab your List's Unique Id by going to http://admin.mailchimp.com/lists/
	// Click the "settings" link for the list - the Unique Id is at the bottom of that page. 
	$list_id = of_get_option( 'newsletter-mc-list-id' );
	
	$mc = new MCAPI( $api_key );

	$status = $mc->listSubscribe($list_id, $email, '');
	
	if( $status === true ){
		$return['error'] = false;
		$return['message'] = 'Thank you for subscribing to our newsletter! You will receive a confirmation email shortly.';
	} else {
		$return['error'] = true;
		$return['message'] = 'There was a problem signing you up. Please try again or contact the site administrator.';
	}
	
	echo json_encode( $return );
	exit;
}

/**
 * Load js scripts for AJAX operation
 */
function bulldog_newsletter_subscribe_scripts(){
	
	if( is_admin() ) return;
	
	wp_enqueue_script( 'bulldog-newsletter-ajax', trailingslashit( BULLDOG_NEWSLETTER_URI ) . 'js/ajax.js', array( 'jquery', 'jquery-form' ), "1.0" );
	
	$js_vars = array( 'ajaxUrl' => admin_url( 'admin-ajax.php' ) );
	
	wp_localize_script( 'bulldog-newsletter-ajax', 'BulldogNewsletter', $js_vars );
}

/**
 * Register the newsletter subscribe widget
 */
function bulldog_newsletter_subscribe_widget(){
	
	require_once( trailingslashit( BULLDOG_NEWSLETTER_DIR ) . 'widget-subscribe-box.php' );
	register_widget( 'Widget_Subscribe_Box' );
}

/**
 * Add subscribe options into Options Framework "option" array
 */
function bulldog_newsletter_add_options( $options ){

	require_once( 'MCAPI.class.php' );

	$options[] = array( "name" => "Newsletter Settings",
						"type" => "heading");

	$options[] = array( "name" => "MailChimp Newsletter Settings",
						"desc" => "This theme uses MailChimp to power the email newsletter service.",
						"type" => "info");
						
	$options[] = array( "name" => "Enter MailChimp API Key",
						"desc" => 'Grab an API Key from <a href="http://admin.mailchimp.com/account/api/">http://admin.mailchimp.com/account/api/</a>. <em>Example: 7389heaed87kf4c7ed929u65af459466z-us4</em>',
						"id" => "newsletter-mc-api-key",
						"type" => "text");
						
	$options[] = array( "name" => "Enter MailChimp List Unique ID",
						"desc" => 'Grabe your List\'s Unique ID by going to <a href="http://admin.mailchimp.com/lists/">http://admin.mailchimp.com/lists/</a>. Click the <strong>settings</strong> link for the list - the Unique ID is at the bottom of that page. <em>Example: pm07z9a536</em>',
						"id" => "newsletter-mc-list-id",
						'class' => 'mini',
						"type" => "text");
						
	return $options;
	
}
