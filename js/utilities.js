/**
 * Utility Functions
 *
 * This file contains utility functions that other scripts can use
 */

/**
 * Clear Textfield
 *
 * clears default value on textfield when user clicks in the field
 * and restore default value if field is blank.
 * @param field jQuery selected elements
 */
function clearTextField( field ){
	
	field.focus( function(){
		jQuery(this).addClass('active');

		if ( this.value == this.defaultValue ){
			this.value = '';
		}
	});
	
	field.blur( function(){
		jQuery(this).removeClass('active');
		if( this.value == '' ){
			this.value = this.defaultValue;
		}
	});
}