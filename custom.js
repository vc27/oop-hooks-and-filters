jQuery(document).ready(function($) {
	
	$('.transformers').click(function(event) {
		event.preventDefault();
		$.post( 
			wcObject.ajaxurl, {
				action : wcObject.action,
				switch_case : $(this).attr('data-case'),
				nonce : $(this).attr('data-nonce'),
				name : $(this).attr('data-name'),
				imgSrc : $(this).attr('href')
			}, function( response ) {
				if ( 'success' == response.status ) {
					$('body').prepend(response.image);
					$('.transformer-'+response.name).delay(4000).fadeOut();
				} else if ( 'error' == response.status ) {
					alert(response.message);
				}
			},
			'json' );
	});
	
});