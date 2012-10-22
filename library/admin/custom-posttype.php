<?php
/**
 * This file sets up custom post types used for this theme. 
 * 
 * TABLE OF CONTENTS
 * 	- SETUP CALLS
 *	- EVENT POST TYPE STUFF
 **/

/**
 * SETUP CALLS
 ************************************/
 
// include functions for printing form fields
require_once( MOBILITY_FUNCTIONS . '/print-field.php' );

//setup function for event post type
event_posttype_init();

/**
 * EVENT POST TYPE STUFF 
 ************************************/
/* Calls the different add_action() calls for the Event post type */
function event_posttype_init(){

	$prefix = hybrid_get_prefix();
	
	add_action('init','event_posttype');
	add_action('add_meta_boxes','event_metabox_init');
	add_action( 'save_post', 'event_save_metaboxes', 10, 2 );
	add_action('enter_title_here','mobility21_event_enter_title_here');
	
	add_action( "manage_event_posts_custom_column", "event_taxonomy_custom_column", 10, 2 );
	
	add_action( "init", 'event_register_scripts_styles' );
	//event_register_scripts_styles();
	add_action( "admin_print_scripts-post-new.php", 'mobility21_event_admin_enqueue_scripts_styles' );
	add_action( "admin_print_styles-post-new.php", 'mobility21_event_admin_enqueue_styles' );
	add_action( "admin_print_scripts-post.php", 'mobility21_event_admin_enqueue_scripts_styles' );
	add_action( "admin_print_styles-post.php", 'mobility21_event_admin_enqueue_styles' );
	
	/** Filters **/
	// Custom Edit Columns
	add_filter("manage_edit-event_columns", "event_taxonomy_columns");
	
}

/* Function to register the Event post type */
function event_posttype(){
	$prefix = hybrid_get_prefix();
	
	$labels = array(
					'name' => 'Events',
					'singular_label' => 'Event',
					'add_new' => 'New Event',
					'add_new_item' => 'New Event',
					'edit' => 'Edit',
					'edit_item' => 'Edit Event',
					'new_item' => __( 'New Event' ),
					'view' => __( 'View Event' ),
					'view_item' => __( 'View Event' ),
					'search_items' => __( 'Search Events' ),
					'not_found' => __( 'No Events found' ),
					'not_found_in_trash' => __( 'No Events found in Trash' ),
				);
	$supports = array(
					'title'
				);
	$args = array(
				'labels' => $labels,
				'description' => 'Upcoming events.',
				'public' => true,
				'show_ui' => true,
				'capability_type' => 'post',
				'hierarchical' => false,
				'rewrite' => array('slug'=>'event','with_front'=>true),
				//'rewrite' => true,
				'exclude_from_search' => true,
				'supports' => $supports,
				'menu_position' => 20,
				'has_archive' => true
			);
	register_post_type('event', $args);
	
	/* fix problem with permalinks */
	flush_rewrite_rules( false );
}

/**
 * Set up the metaboxes
 */
function event_metabox_init(){
	
	add_meta_box('event-metabox-links', 'Event Links', 'event_metabox_links', 'event', 'normal', 'low' );
	add_meta_box('event-metabox-date', 'Event Date', 'event_metabox_dates', 'event', 'normal', 'low' );
	
}

/**
 * Register scripts and styles here
 */
function event_register_scripts_styles(){
	
	/* scripts */
	wp_register_script('jquery-datepicker', THEME_URI . '/admin/js/jquery.ui.datepicker.js', array('jquery', 'jquery-ui-core') );
	
	wp_register_script('m21-event', THEME_URI . '/admin/js/event.js', array('jquery-datepicker') );
	
	/* styles */
	wp_register_style('jquery-datepicker-style', THEME_URI . '/admin/css/jquery-ui-1.8.11.custom.css' );
}

/**
 * Enqueues needed scripts and styles here
 */
function mobility21_event_admin_enqueue_scripts_styles(){
	global $typenow, $pagenow;

		wp_enqueue_script('jquery-datepicker');
		wp_enqueue_script('m21-event');
		
		wp_enqueue_style('jquery-datepicker-style');
				
}

function mobility21_event_admin_enqueue_styles(){
	wp_enqueue_style('jquery-datepicker-style');
}


/**
 * Creates the settings for the post meta box depending on some things in how the theme are set up.
 *
 * @return array
 */
function event_metabox_args(){
	
	$event_meta = array();
	
	$group_styles = 'background: #f5f5f5; padding: 6px 1px; margin-bottom: 8px;';
	$header_styles = '';
	$event_meta['link-settings'] = array(
									'linkheader1' => array(
												'name' => 'linkHeader1',
												'title' => 'Link 1',
												'type' => 'header',
												'style' => $header_styles
											),
									'linkgroupopen1' => array(
												'name' => 'linkGroup1',
												'type' => 'groupopen',
												'style' => $group_styles
											),
										
										'linktext1' => array(
													'name' => 'linkText1',
													'title' => 'Link Text 1:',
													'type' => 'text',
												),
										'linkurl1' => array(
													'name' => 'linkUrl1',
													'title' => 'Link URL 1:',
													'type' => 'text',
													'description' => 'Ex. http://www.mobility21.com/link-page'
												),
									'linkgroupclose1' => array(
												'type' => 'groupclose'
											),
									'linkheader2' => array(
												'name' => 'linkHeader2',
												'title' => 'Link 2',
												'type' => 'header',
												'style' => $header_styles
											),
									'linkgroupopen2' => array(
												'name' => 'linkGroup2',
												'type' => 'groupopen',
												'style' => $group_styles
											),
										'linktext2' => array(
													'name' => 'linkText2',
													'title' => 'Link Text 2:',
													'type' => 'text'
												),
										'linkurl2' => array(
													'name' => 'linkUrl2',
													'title' => 'Link URL 2:',
													'type' => 'text',
													'description' => 'Ex. http://www.mobility21.com/link-page'
												),
									'linkgroupclose2' => array(
												'type' => 'groupclose'
											),
									'linkheader3' => array(
												'name' => 'linkHeader3',
												'title' => 'Link 3',
												'type' => 'header',
												'style' => $header_styles
											),
									'linkgroupopen3' => array(
												'name' => 'linkGroup3',
												'type' => 'groupopen',
												'style' => $group_styles
												),
										'linktext3' => array(
													'name' => 'linkText3',
													'title' => 'Link Text 3',
													'type' => 'text'
												),
										'linkurl3' => array(
													'name' => 'linkUrl3',
													'title' => 'Link URL 3:',
													'type' => 'text',
													'description' => 'Ex. http://www.mobility21.com/link-page'
												),
									'linkgroupclose3' => array(
											'type' => 'groupclose'
										)
								);
	
	$event_meta['date-settings'] = array(
									'startdate'=> array(
											'name' => 'Date',
											'title' => 'Date:',
											'type' => 'text',
											'description' => 'Enter date for the start of the Event here in the following PHP time format: <strong>Y-m-d</strong>
											<br /><br/>
											<strong>Y</strong> - is the full numeric representation of a year, 4 digits. Ex. 2011<br/>
											<strong>m</strong> - is numeric representation of a month with leading zeros. Ex. 01-12<br />
											<strong>d</strong> - is the day of the month, 2 digits with leading zeros. Ex. 01-31<br />
											<strong>Example: 2011-05-22</strong>'
											
									)
								);
	
	return $event_meta;
}

/**
 * Displays the Event Links settings on the edit event page. The function gets the various metadata elements
 * from the event_metabox_args() function. It then loops through each item in the array and
 * displays a form element based on the type of setting it should be.
 *
 * @param object $object Post object that holds all the post information.
 * @param array $box The particular meta box being shown and its information.
 */
function event_metabox_links( $object, $box ){
	global $theme;

	$prefix = hybrid_get_prefix();

?>

	<input type="hidden" name="<?php echo "{$prefix}_{$object->post_type}_meta_box_links_nonce"; ?>" value="<?php echo wp_create_nonce( basename( __FILE__ ) ); ?>" />

	<div class="mobility21-event-links">

		
		<?php 
			$event_meta = event_metabox_args();
			$event_links_options = $event_meta['link-settings'];
		 	
		 	foreach ( $event_links_options as $option ) {
				if ( function_exists( "pogi_meta_box_{$option['type']}" ) )
					call_user_func( "pogi_meta_box_{$option['type']}", $option, get_post_meta( $object->ID, $option['name'], true ) );
			}  
		?>
		
	</div> 
	
	<?php
}

/**
 * Displays the Date settings on the edit event page. The function gets the various metadata elements
 * from the event_metabox_args() function. It then loops through each item in the array and
 * displays a form element based on the type of setting it should be.
 *
 * @param object $object Post object that holds all the post information.
 * @param array $box The particular meta box being shown and its information.
 */
function event_metabox_dates( $object, $box ){
	global $theme;

	$prefix = hybrid_get_prefix();

?>

	<input type="hidden" name="<?php echo "{$prefix}_{$object->post_type}_meta_box_date_nonce"; ?>" value="<?php echo wp_create_nonce( basename( __FILE__ ) ); ?>" />

	<div class="mobility21-event-dates">

		<?php 
			$event_meta = event_metabox_args();
			$event_date_options = $event_meta['date-settings'];
		 	
		 	foreach ( $event_date_options as $option ) {
				if ( function_exists( "pogi_meta_box_{$option['type']}" ) )
					call_user_func( "pogi_meta_box_{$option['type']}", $option, get_post_meta( $object->ID, $option['name'], true ) );
			}  
		?>
		
	</div> 
	
	<?php
}

function event_save_metaboxes( $post_id, $post ){
	$prefix = hybrid_get_prefix();
	
	/* Ignore autosaves */
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
	
	/* Verify nonce before preceding. */
	if (  !isset( $_POST["{$prefix}_{$post->post_type}_meta_box_links_nonce"] ) || !isset( $_POST["{$prefix}_{$post->post_type}_meta_box_date_nonce"] ) || !wp_verify_nonce( $_POST["{$prefix}_{$post->post_type}_meta_box_links_nonce"], basename( __FILE__ ) ) || !wp_verify_nonce( $_POST["{$prefix}_{$post->post_type}_meta_box_date_nonce"], basename( __FILE__ ) ))
		return $post_id;
		
	/* Check if the current user has permission to edit the post. */ 
	if ( !current_user_can( 'edit_post', $post_id ) )
		return $post_id;
	
	/* Get the event links metabox arguments */
	$event_meta = event_metabox_args();
	$event_links_options = $event_meta['link-settings'];
	$event_date_options = $event_meta['date-settings'];
	
	/* Loop through all of event links meta box arguments. */
	pogi_save_meta_array( $event_links_options );


	pogi_save_meta_array( $event_date_options );

}

/**
 * @param string $type 'normal' | 'button'
 * @param string $sep
 * @param int $post_id
 * @return string 
 */
function event_get_links( $type = 'normal', $sep = ' | ', $post_id = null ){
	
	if( $post_id == null )
		$post_id = get_the_ID();
	
	$temp = array();
	$out = '';
	$sepcount = 0;
	
	$linktext1 = get_post_meta( $post_id, 'linkText1', true );
	if( !empty( $linktext1 ) ){
		$linkurl1 = get_post_meta( $post_id, 'linkUrl1', true );
		$temp[] = array( 'linktext' => $linktext1, 'linkurl' => $linkurl1 );
	}
	
	$linktext2 = get_post_meta( $post_id, 'linkText2', true );
	if( !empty( $linktext2 ) ){
		$linkurl2 = get_post_meta( $post_id, 'linkUrl2', true );
		$temp[] = array( 'linktext' => $linktext2, 'linkurl' => $linkurl2 );
	}
	
	$linktext3 = get_post_meta( $post_id, 'linkText3', true );
	if( !empty( $linktext3 ) ){
		$linkurl3 = get_post_meta( $post_id, 'linkUrl3', true );
		$temp[] = array( 'linktext' => $linktext3, 'linkurl' => $linkurl3 );
	}
	
	foreach( $temp as $link ){
		
		if( $type == 'normal' ) {
			if( $sepcount > 0 ) { 
				$out .= '<span class="sep">' . $sep . '</span>';
				$sepcount = 0;
			}
			
			$out .= sprintf( '<a href="%s" target="_blank">%s</a>', esc_attr( $link['linkurl'] ), esc_attr( $link['linktext'] ) );
			$sepcount++;
			
		} elseif ( $type == 'button' ){
			
			$out .= sprintf( '<a href="%s" class="button" target="_blank">%s</a>', esc_attr( $link['linkurl'] ), esc_attr( $link['linktext'] ) );
			
		} else {
		
			$out .= sprintf( '<a href="%s" target="_blank">%s</a>', esc_attr( $link['linkurl'] ), esc_attr( $link['linktext'] ) );
		}
		
		
	}
	
	return $out;
	
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
		
	$out = get_post_meta( $post_id, 'Date', true );
	
	if( empty( $out ) )
		return 'Date not set.';
	else
		return esc_attr( $out );
}

/**
 * Change the title label on Event edit pages
 */
function mobility21_event_enter_title_here( $content ){
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
	
		$date = event_get_date();
		
		if( !empty( $date )) {
			echo date( 'l, M d, Y', strtotime( $date ) );
		}
		
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

/*
add_filter('posts_orderby', 'ahs_orderby');
function ahs_orderby($sql) {
global $wpdb, $wp_query;
if ( is_admin() && is_event() ) {
return $wpdb->prefix . "posts.post_title ASC";
}
return $sql;
}
*/

add_filter( 'parse_query', 'my_pagetemplate_filter' );
function my_pagetemplate_filter( $query ) {
    global $pagenow, $typenow;

    if( is_admin() && $typenow == 'event' ){
		set_query_var( 'meta_key', 'Date' );
		set_query_var( 'orderby','meta_value' );
		set_query_var( 'order', 'ASC' );
	}
}
