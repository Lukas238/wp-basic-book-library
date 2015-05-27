jQuery(document).ready(function( $ ) {
	

	$('#btn-get-obl').click(function(e) {
		e.preventDefault();
	   
		var iframe_url = $(this).data('iframe-url');
		
		var isbn = $('#basicbooklibrary-meta-bookinfo-isbn').val();
		isbn = parseInt( isbn.replace(/[^0-9]/gi, '') );
		
		var post_ID = $('#book-post-id').val();
		
		if( !isbn || !post_ID ){
			return false;
		} 
		
		
		$.magnificPopup.open({
			items: {
				src: iframe_url + "?book_ISBN=" + isbn + "&post_ID=" + post_ID,
			},
			type: 'iframe',
			mainClass: 'popup-retive-data'
		});
		
		
	});

})