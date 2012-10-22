jQuery(document).ready( function(){
	
	jQuery("#slider-html-elements").change(function(){
		
		var el = jQuery(this);
		
		if( el.val() == 'button'){
			jQuery("#slider-element-options-button").show();
		}
		switch( el.val() ){
			case 'button':
				jQuery("#slider-element-options-button").show();
				break;
			case 'text':
				jQuery("#slider-element-options-text").show();
				break;
			case 'image':
				jQuery("#slider-element-options-image").show();
				break;
		}
	});
	
}

/**
 * Hide clear everything
 */
function clearAll(){
	
}

/**
 * This functions updates an Ad Format
 */
function adFormatUpdate( e ){

	//callback function from successful ajax operation
	var success = function(data){
				
				if( data.status == "error" ){
					jQuery( ".message-container" ).addClass( "error" );
				} else {
					jQuery( ".message-container" ).attr( "class", "updated fade message-container" );
				}
				
				//show message
				jQuery( ".message-container" ).html( "<p><strong>" + data.message + "</strong></p>" ).show();
				
			};
	
	var clickHandler = function(){ 
			
			var adFormatId = jQuery( "#ad-format-id" ).attr("value");
			var adFormatName = jQuery( "#ad-format-name").attr("value");
			var adFormatWidth = jQuery( "#ad-format-width" ).attr("value");
			var adFormatHeight = jQuery( "#ad-format-height" ).attr("value");
			
			//prepare data in json format
			var data = { 	action : "update-ad-format", 
							adFormatId : adFormatId, 
							updateAdFormatNonce : M21.updateadformatnonce, 
							adFormatName : adFormatName, 
							adFormatWidth : adFormatWidth, 
							adFormatHeight : adFormatHeight
						}
			
			//do ajax
			jQuery.post( ajaxurl, data, success, "json" );
			
			//make sure the page doesn't reload
			return false;
		}
	
	e.click( clickHandler );
	
}

function clearFields(){
	//enable Format ID field
	jQuery( "#ad-format-id" ).removeAttr("disabled" );
	//clear fields
	jQuery( "#ad-format-id" ).attr("value", "" );
	jQuery( "#ad-format-name" ).attr("value", "" );
	jQuery( "#ad-format-width" ).attr("value", "" );
	jQuery( "#ad-format-height" ).attr("value", "" );
}

function restoreDefaults(){

	clearFields();
	
	//unbind click event from .update-ad-format-submit button
	jQuery( ".update-ad-format-submit").unbind( "click" );
	
	//restore label of submit button to default
	jQuery( "#ad-format-submit" ).attr("value", "Create Ad Format");
	jQuery( "#ad-format-submit" ).removeClass( "update-ad-format-submit");
	
	//hide Delete button
	jQuery( "#ad-format-delete" ).hide();
}