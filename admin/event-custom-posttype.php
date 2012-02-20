<?php
/**
 * This file sets up the Event custom post types used for this theme. 
 **/

/**
 * SETUP CALLS
 ************************************/
 
// include functions for printing form fields
require_once( 'admin-print-field.php' );

$prefix = hybrid_get_prefix();

add_action( 'init','event_posttype' );
//add_action( 'add_meta_boxes','event_metabox_init' );
add_action( 'save_post', 'event_save_metaboxes', 10, 2 );
add_action( 'enter_title_here','event_enter_title_here' );

add_action( 'admin_enqueue_scripts', 'event_register_scripts_styles' );
add_action( "admin_print_scripts-post-new.php", 'event_admin_enqueue_scripts_styles' );
add_action( "admin_print_styles-post-new.php", 'event_admin_enqueue_styles' );
add_action( "admin_print_scripts-post.php", 'event_admin_enqueue_scripts_styles' );
add_action( "admin_print_styles-post.php", 'event_admin_enqueue_styles' );

add_action( "manage_event_posts_custom_column", "event_taxonomy_custom_column", 10, 2 );
add_filter('post_updated_messages', 'event_updated_messages');

/** Filters **/
// Custom Edit Columns
add_filter("manage_edit-event_columns", "event_taxonomy_columns");
add_filter( 'parse_query', 'event_pagetemplate_filter' );

/**
 * EVENT POST TYPE STUFF 
 ************************************/
 
/* Function to register the Event post type */
function event_posttype(){
	$prefix = hybrid_get_prefix();
	
	$labels = array(
				'name' => 'Events',
				'all_items' => 'Events',
				'menu_name' => 'Events',
				'singular_label' => 'Event',
				'add_new' => 'New Event',
				'add_new_item' => 'New Event',
				'edit' => 'Edit',
				'edit_item' => 'Edit Event',
				'new_item' => __( 'New Event' ),
				'view_item' => __( 'View Event' ),
				'search_items' => __( 'Search Events' ),
				'not_found' => __( 'No Events found' ),
				'not_found_in_trash' => __( 'No Events found in Trash' )
				);
	$supports = array(
					'title','editor','thumbnail'
				);
	$args = array(
				'labels' => $labels,
				'description' => 'Upcoming events.',
				'public' => false,
				'show_ui' => true,
				'show_in_menu' => true,
				'capability_type' => 'post',
				'hierarchical' => false,
				'rewrite' => array('slug'=>'event','with_front'=>false),
				//'rewrite' => true,
				'exclude_from_search' => true,
				'supports' => $supports,
				'menu_position' => 20,
				'register_meta_box_cb' => 'event_metabox_init',
				'menu_icon' => THEME_ADMIN_URI . '/images/calendar.png'
			);
	register_post_type('event', $args);
	
	/* fix problem with permalinks */
	flush_rewrite_rules( false );
}

function event_updated_messages( $messages ) {
	global $post, $post_ID;
	
	$messages['event'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => __('Event updated.' ),
		2 => __('Custom field updated.'),
		3 => __('Custom field deleted.'),
		4 => __('Event updated.'),
		/* translators: %s: date and time of the revision */
		5 => isset($_GET['revision']) ? sprintf( __('Event restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => __('Event saved.' ),
		7 => __('Event saved.'),
		8 => __('Event saved.'),
		9 => sprintf( __('Event scheduled for: <strong>%1$s</strong>.'),
		// translators: Publish box date format, see http://php.net/date
		date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
		10 => __('Event draft updated.')
	);
	
	return $messages;
}

/**
 * Set up the metaboxes
 */
function event_metabox_init(){
	add_meta_box('event-metabox-details', 'Event Details', 'event_metabox_details', 'event', 'normal', 'low' );
}

/**
 * Register scripts and styles here
 */
function event_register_scripts_styles(){
	/** Scripts **/
	wp_register_script('jquery-datepicker', THEME_ADMIN_URI . '/js/jquery.ui.datepicker.js', array('jquery', 'jquery-ui-core') );
	wp_register_script('event-datepicker', THEME_ADMIN_URI . '/js/event.js', array( 'jquery-datepicker' ) );
	
	/** Styles **/
	wp_register_style( 'jquery-datepicker-style', THEME_ADMIN_URI . '/css/jquery-ui-1.8.11.custom.css' );
}

/**
 * Enqueues needed scripts and styles here
 */
function event_admin_enqueue_scripts_styles(){
	//stop if not on "Event" custom post type
	if( 'event' !== get_post_type() ) return;
	
	wp_enqueue_script('event-datepicker');
}

function event_admin_enqueue_styles(){
	//stop if not on "Event" custom post type
	//if( 'event' !== get_post_type() ) return;
	
	wp_enqueue_style('jquery-datepicker-style' );
}


/**
 * Creates the settings for the post meta box depending on some things in how the theme are set up.
 *
 * @return array
 */
function event_metabox_args( $field_group = ''){
	
	$fields = array();
	
	$fields['details'] = array(
		array(	'title' => 'Starting Date of the Event',
				'type' => 'text',
				'id' => 'tm-event-date',
				'description' => 'Enter date for the start of the Event here in the following PHP time format: <strong>Y-m-d</strong>
				<br /><br/>
				<strong>Y</strong> - is the full numeric representation of a year, 4 digits. Ex. 2011<br/>
				<strong>m</strong> - is numeric representation of a month with leading zeros. Ex. 01-12<br />
				<strong>d</strong> - is the day of the month, 2 digits with leading zeros. Ex. 01-31<br />
				<strong>Example: 2011-05-22</strong>'
		),
		array(	'title' => 'Text that will be displayed for the date (optional)',
				'type' => 'text',
				'id' => 'tm-event-display-date',
				'description' => 'Enter date in free-form. If left blank, then the Starting Date will be used. Example: <code>Coming Soon, December 1-3, 2011 or Monday, Next Week</code>'
		),
		array(	'title' => 'Location (optional)',
				'type' => 'text',
				'id' => 'tm-event-location',
				'description' => 'Example: <code>Fountain Valley, CA</code>'
		),
		array(	'title' => 'Primary Event URL',
				'description' => 'Enter primary URL to event page here. You can set additional links below.',
				'id' => 'tm-event-url',
				'type' => 'text'
		),
		array(	'title' => 'Additional Link Text 1',
				'type' => 'text',
				'id' => 'tm-event-link-text1',
				'description' => 'Example: <code>More Info</code>. Additional Link URL 1 must be specified below for this field to appear.'
		),
		array(	'title' => 'Additional Link URL 1',
				'type' => 'text',
				'id' => 'tm-event-link-url1',
				'description' => 'Example: <code>http://firstgiving.com/thomashouse/walkathon-2012</code>'
		),
		array(	'title' => 'Additional Link Text 2',
				'type' => 'text',
				'id' => 'tm-event-link-text2',
				'description' => 'Example: <code>Register</code>. Additional Link URL 2 must be specified below for this field to appear.'
		),
		array(	'title' => 'Additional Link URL 2',
				'type' => 'text',
				'id' => 'tm-event-link-url2',
				'description' => 'Example: <code>http://firstgiving.com/thomashouse/walkathon-2012</code>'
		),
	);
	
	
	if( empty( $field_group ) ){
		return $fields;
	} else {
		if( isset( $fields[ $field_group ] ) ){
			return $fields[ $field_group ];
		} else {
			return array();
		}
	}
}

/**
 * Displays the Date settings on the edit event page. The function gets the various metadata elements
 * from the event_metabox_args() function. It then loops through each item in the array and
 * displays a form element based on the type of setting it should be.
 *
 * @param object $object Post object that holds all the post information.
 * @param array $box The particular meta box being shown and its information.
 */
function event_metabox_details( $object, $box ){
	global $theme;

	$prefix = hybrid_get_prefix();

?>

	<input type="hidden" name="<?php echo "{$prefix}_{$object->post_type}_metabox_details_nonce"; ?>" value="<?php echo wp_create_nonce( basename( __FILE__ ) ); ?>" />

	<?php 
		$fields = event_metabox_args('details');
		
		pogi_print_fields( $fields );
	?>
	
	<?php
}

function event_save_metaboxes( $post_id, $post ){
	$prefix = hybrid_get_prefix();
	
	/* Ignore autosaves */
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){ 
		//watch out for autosave. set unix event date post meta on autosave.
		$tmp_unix = get_post_meta( $post_id, 'tm-event-unix', true );
		if( empty( $tmp_unix ) ){
			update_post_meta( $post_id, 'tm-event-unix', 0 );
		}
		return $post_id;
	}
	
	/* Verify nonce before preceding. */
	if (  !isset( $_POST["{$prefix}_{$post->post_type}_metabox_details_nonce"] ) || !wp_verify_nonce( $_POST["{$prefix}_{$post->post_type}_metabox_details_nonce"], basename( __FILE__ ) ))
		return $post_id;
		
	/* Check if the current user has permission to edit the post. */ 
	if ( !current_user_can( 'edit_post', $post_id ) )
		return $post_id;
	
	/* Get the event links metabox arguments */
	$fields = event_metabox_args('details');
	
	/* Loop through all of event links meta box arguments. */
	pogi_save_meta_array( $fields );
	

	//set unix time. if event date is blank, then set $unix_time = 0
	$unix_time = 0;
	$event_date = get_post_meta( $post_id, 'tm-event-date', true );
	if( !empty( $event_date ) ){
		$unix_time = strtotime( $event_date );
	}
	update_post_meta( $post_id, 'tm-event-unix', $unix_time );
}

/**
 * This function is a custom template tag that is used for getting event dates.
 *
 * @param int $post_id
 * @return string html
 */
function event_get_date( $post_id = '' ){
	
	global $post;
	
	if( empty( $post_id ) )
		$post_id = $post->ID;
		
	$out = get_post_meta( $post_id, 'tm-event-date', true );
	
	if( empty( $out ) )
		return 'Date not set.';
	else
		return esc_attr( $out );
}

/**
 * Change the title label on Event edit pages
 */
function event_enter_title_here( $content ){
	if( 'event' == get_post_type() ){
		return 'Enter title of event here';
	} else {
		return $content;
	}
}

/**
 * Reorders and adds the 'Event Date' column to the Edit Ads page 
 */
function event_taxonomy_custom_column( $column, $post_id ){

	global $post;
	
	if ($column == 'event_date'){
		
		$date_unix = get_post_meta( $post_id, 'tm-event-unix', true );
		
		if( !empty( $date_unix ) ) {
			
			//format the date
			$date = date( 'D, M d/Y', $date_unix );
			
			//highlight dates that is more than 1.5 days old
			if( (time() - 60*60*36) > $date_unix ){
				$date =  '<span style="color: #c09000;">' . $date . '</span>';
			}
		} else {
			//no date set. Highlight it
			$date = '<span style="color: #FF1D00;">No date set</span>';
		}
		
		echo $date;
		
	}
	
}

/**
 * Reorders and adds an 'Event Date' column to the Edit Ads page 
 */
function event_taxonomy_columns( $defaults ){
	
	//preserve the default date column
	$date = $defaults['date'];
	
	//remove default date
	unset( $defaults['date'] );
	
	//rename title
	$defaults['title'] = __('Event Name');
	
	//insert Event Date column
	$defaults['event_date'] = __('Event Date');
	
	//insert Date
	//$defaults['date'] = $date = __('Date Published');

	return $defaults;
}

//add_filter( 'parse_query', 'event_pagetemplate_filter' );
function event_pagetemplate_filter( $query ) {
    global $pagenow, $typenow;
	
    if( is_admin() && $typenow == 'event' ){
		set_query_var( 'meta_key', 'tm-event-unix' );
		set_query_var( 'orderby','meta_value_num ID' );
		set_query_var( 'order', 'ASC' );
	}
}
