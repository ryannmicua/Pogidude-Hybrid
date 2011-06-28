jQuery(document).ready( function(){
	
	/* add class to the last or first list or column item */
	jQuery("#call-to-action .padder > :last-child").addClass('last');
	jQuery("#showcase .padder > :last-child").addClass('last');
	jQuery("#menu-footer-items > :last-child").addClass('last-menu-item');
	
	/* get the height of parent element and sets all children divs to the same height */
	ctaHeight = jQuery( "#call-to-action .padder" ).height();
	jQuery("#call-to-action .column").css( { "min-height" : ctaHeight } );
	
	/* Run clearTextField function on all selected text fields */
	clearTextField( jQuery(".use-cleartext") );
});

/**
 * Adds the class "last" to the last direct child of a parent element - ul > :last-child
 */
function addClassLast( parentEl, className ){
	className = (typeof className == 'undefined') ? 'last' : className;
	
	parentEl.addClass(className);
	
}

/**
 * Clear Textfield - clears default value on textfield when user clicks in the field and restore default value if field is blank.
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
	
	/*
	
	if( field.value == field.defaultValue){
		field.value='';
	} else if ( field.value==''){
		field.value = field.defaultValue;
	}
	*/
}
