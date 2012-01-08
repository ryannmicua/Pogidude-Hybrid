/**
 * AJAX Scripts here
 */
 

/** Provide AJAX functionality to Newsletter widget **/
jQuery(document).ready( function(){
	jQuery( "form.newsletter-subscribe-wrap" ).submit( function(){
		
		//responseBox = jQuery( '#newsletter-subscribe-box-response' );
		responseBox = jQuery(this).siblings( ".newsletter-subscribe-box-response" );
		
		jQuery(this).ajaxSubmit({
			type : "POST",
			url : Bulldog.ajaxUrl,
			dataType : 'json',
			beforeSend : function(x){
				var content = jQuery( '.response', responseBox );
				var holdMsg = 'Please wait while we process your subscription.';
				content.html( holdMsg );
				responseBox.removeClass('error').removeClass('success').addClass('hold').fadeIn(300);
			},
			success : function( response, code ){
				var content = jQuery( '.response', responseBox );
				
				if( response.error == true ){ //ERROR
					responseBox.removeClass('hold').addClass('error');
				} else { //SUCCESS
					responseBox.removeClass('hold').addClass('success');
				}
				
				content.html( response.message );
				
				responseBox.delay( 8000 ).fadeOut( 500 );
			}
		});//ajaxSubmit()
		
		return false;
		
	});//submit()
});