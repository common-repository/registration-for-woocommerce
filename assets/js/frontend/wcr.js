jQuery(document).ready(function(){
   jQuery("#separate_shipping").change(function(){
	    if ( jQuery('#separate_shipping').is(':checked') ) {
	   		jQuery( '.wcr-column .wcr-shipping' ).show();
	   } else{
			jQuery( '.wcr-column .wcr-shipping' ).hide();
	   }
	});
});