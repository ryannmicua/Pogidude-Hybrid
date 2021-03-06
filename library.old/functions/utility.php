<?php
/**
 * Helper functions that the theme may use. Functions in this file are functions that just don't belong anywhere within other parts.
 *
 * @package PogiCore
 * @subpackage Functions
 */


/**
* Prints the 1st instance of the meta_key identified by $fieldname
* associated with the current post. See get_post_meta() for more details.
*
* @param string $fieldname Name of custom field from the wp_postmeta table.
* @param int $post_id ID of post where the custom field is associated with
* @return none No value returned; this function prints the value of the first field named $fieldname associated with the current post.
*/
function pogi_print_post_meta( $fieldname, $post_id = NULL ){
	
	if( $post_id == NULL ) $post_id = get_the_ID();
	
	print get_post_meta( $post_id, $fieldname, true );
}