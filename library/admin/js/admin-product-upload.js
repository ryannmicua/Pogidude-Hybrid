jQuery(document).ready( function(){
	var pID = jQuery('#post_ID').val();
	
	jQuery('#bulldog-product-pdf-button').click(function(){
		formfield = jQuery('#bulldog-product-pdf').attr('name');
		tb_show('', 'media-upload.php?post_id=' + pID + '&TB_iframe=true');
		return false;
	});
	
	window.send_to_editor = function(html){
		//theurl = jQuery('img', html).attr('src');
		var htmlBits = html.split("'"); //jQuery seems to strip out XHTML when assigning the string to an object. Use alternate method
		theurl = htmlBits[1]; //use the URL to the file

		jQuery('#bulldog-product-pdf').val( theurl );
		tb_remove();
	}
});