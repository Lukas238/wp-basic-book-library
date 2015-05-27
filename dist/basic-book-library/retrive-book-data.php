<?php
	require_once("../../../wp-load.php");
	
	
	$post_id = ($_GET['post_ID']) ? $_GET['post_ID'] : $_POST['post_ID'];
	
	$book_ISBN = ($_GET['book_ISBN']) ? $_GET['book_ISBN'] : $_POST['book_ISBN'];
	$book_authors = ($_GET['book_authors']) ? $_GET['book_authors'] : $_POST['book_authors'];
	$book_title = ($_GET['book_title']) ? $_GET['book_title'] : $_POST['book_title'];
	$book_image_url = ($_GET['image_url']) ? $_GET['image_url'] : $_POST['image_url'];
	
	$book_post_status = '';
	
	
	//SUBMIT	
	if( isset($_POST['submit']) ){
	
		if( isset($_POST['image_url']) && $_POST['image_url'] != '' ){			
			require_once(ABSPATH . 'wp-admin/includes/image.php');
			$upload_dir = wp_upload_dir();
			$image_data = file_get_contents($book_image_url);
			$filename = basename($book_image_url);
			if(wp_mkdir_p($upload_dir['path']))
				$file = $upload_dir['path'] . '/' . $filename;
			else
				$file = $upload_dir['basedir'] . '/' . $filename;
			file_put_contents($file, $image_data);

			$wp_filetype = wp_check_filetype($filename, null );
			$attachment = array(
				'post_mime_type' => $wp_filetype['type'],
				'post_title' => sanitize_file_name($filename),
				'post_content' => '',
				'post_status' => 'inherit'
			);
			$attach_id = wp_insert_attachment( $attachment, $file, $post_id );
			
			$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
			wp_update_attachment_metadata( $attach_id, $attach_data );

			set_post_thumbnail( $post_id, $attach_id );
		}
		
		
		//Preparo la actualizacon del post
		$book_post = get_post( $post_id );
		$book_post_status =  ( $book_post->post_status == 'auto-draft' ) ? 'draft' : $book_post->post_status;
		
		
		if( !$book_title  ){
			if( $book_post->post_status == 'auto-draft' ){
				$book_title = __('-Libro sin publicar-', basicBookLibrary);
			}else{
				$book_title = $book_post->post_title;
			}
		}
		
		$args = array(
			'ID'			=> $post_id,
			'post_status' => $book_post_status,
			'post_title' 	=> $book_title
		);
		
		wp_update_post( $args );
		
		//UPDATE POST_META
		//Author
		if( $book_authors ) {
			update_post_meta( $post_id, '_basicbooklibrary-meta-bookinfo-author', sanitize_text_field( $book_authors ) );
		}
		
		//ISBN
		if( $book_ISBN ) {
			update_post_meta( $post_id, '_basicbooklibrary-meta-bookinfo-isbn', sanitize_text_field( $book_ISBN ) );
		}
	
		
		/* JSON RESPONSE ALL GOOD */
		header('Content-Type: application/json');
		
		$data = array(
			'response' => 1 //All good!
		);
		echo json_encode($data);
		
	}else{
	
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Retrive Open Book Library Data | Basic Book Library Plugin</title>
	
	<!-- STYLES -->
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/pure/0.5.0/pure-min.css">
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.1.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?php echo plugins_url( 'css/book-results.css', __FILE__); ?>">
	
</head>
<body class="bbl-page-retrive loading">
	
	<div class="loader"></div>
	
	<div id="content">
		<section id="books-results"></section>
		
		<div class="box-controls-main box-controls">
			<button type="button" class="pure-button btn-popup-close"><?php _e('Cancelar', basicBookLibrary); ?></button>
		</div>
	</div>
	
	<!-- SCRIPTS -->
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<script>
		
		function init(){
			
			$.ajax({
				type : "GET",
				url: "https://openlibrary.org/api/books?bibkeys=ISBN:<?php echo $book_ISBN; ?>&jscmd=data",
				jsonp: "callback",
				dataType : "jsonp",
				success: function( data ) {
				
					if( $.isEmptyObject(data) ){
					
						$('body').append('<div class="feedback"><div class="msg msg-info">No se encontro ningun libro con ese ISBN.</div></div>');
						$('body').removeClass('loading');
				
					}else{
				
						var books_list_HTML = $('<div />');
						
						var i = 1;
						$.each(data, function(key, book){
						
							var data_book_title = '';
							var title = '';
							var data_book_authors = '';
							var authors = '';
							var data_book_cover = '';
							var cover_medium = '';
							
							// TITULO
							if( book.title ){
								data_book_title = ' data-book-title="'+ book.title +'"';
								title = book.title;
							}
								
							//AUTORES
							if( book.authors ){
								$.each(book.authors, function(author_key, author){
									if(authors){
										authors += ', ';
									}
								   
									authors += author.name;
								})
								data_book_authors = ' data-book-authors="'+ authors +'"';
							}
							
							//COVERS    
							if( typeof book.cover != "undefined" ){
								if( typeof book.cover.large != "undefined" && typeof book.cover.medium != "undefined"){
									var data_book_cover = ' data-book-cover="'+ book.cover.large +'"';
									if( book.cover.medium ){
										cover_medium = book.cover.medium;
									}
								}
							}
									
							var book_HTML =	 '<article class="book book-result-'+ ( i % 4)  +'" data-book-isbn="<?php echo $book_ISBN; ?>"'+ data_book_title + data_book_cover + data_book_authors +'>';
						
							book_HTML  +=    '	<header class="group">';
							
							book_HTML +=     '		<div class="thumb">';
							if(cover_medium){
								book_HTML += '			<img src="'+ cover_medium +'" />';
							}
							book_HTML +=     '		</div>';
							
							if(title){
								book_HTML += '		<h1>'+ title +'</h1>';
							}
							
							book_HTML +=     '		<div class="meta">';
							book_HTML +=     '			<ul>';
							
							if(authors){
								book_HTML += '				<li class="author"><label><?php _e('Autores', basicBookLibrary); ?>:</label> '+ authors +'</li>';
							}
							
							book_HTML +=     '			</ul>';
							book_HTML +=     '		</div>';
							book_HTML +=	 '	</header>';
							
							book_HTML +=     '	<footer>';
							book_HTML +=     '		<div class="pure-g">';
							book_HTML +=     '			<div class="pure-u-3-4">';
							book_HTML +=     '				<ul class="choose-data">';
							
							book_HTML +=     '					<li>';
							if(cover_medium){
								book_HTML += '						<input type="checkbox" id="chk-cover-'+ i +'" checked="checked" class="data-cover" />';
								book_HTML += '						<label for="chk-cover-'+ i +'" class="pure-button btn-checkbox"><?php _e('Cover', basicBookLibrary); ?></label>';
							}else{
								book_HTML += '						<label class="pure-button btn-checkbox " disabled><?php _e('Cover', basicBookLibrary); ?></label>';
							}
							book_HTML +=     '					</li>';
							
							book_HTML +=     '					<li>';
							if(title){
								book_HTML += '						<input type="checkbox" id="chk-title-'+ i +'" checked="checked" class="data-title" />';
								book_HTML += '						<label for="chk-title-'+ i +'" class="pure-button btn-checkbox "><?php _e('Título', basicBookLibrary); ?></label>';
							}else{
								book_HTML += '						<label class="pure-button btn-checkbox " disabled><?php _e('Título', basicBookLibrary); ?></label>';
							}
							book_HTML +=     '					</li>';
							
							book_HTML +=     '					<li>';
							if(authors){
								book_HTML += '						<input type="checkbox" id="chk-author-'+ i +'" checked="checked" class="data-authors" />';
								book_HTML += '						<label for="chk-author-'+ i +'" class="pure-button btn-checkbox "><?php _e('Autor', basicBookLibrary); ?></label>';
							}else{
								book_HTML += '						<label class="pure-button btn-checkbox " disabled><?php _e('Autor', basicBookLibrary); ?></label>';
							}
							book_HTML +=     '					</li>';
							
							book_HTML +=     '				</ul>';
							book_HTML +=     '			</div>';
							book_HTML +=     '			<div class="pure-u-1-4">';
							book_HTML +=     '				<button type="submit" name="submit" class="button btn-save-data pure-button pure-button-secondary"><span><i class="fa fa-arrow-circle-down"></i> <?php _e('Usar', basicBookLibrary); ?></span></button>';
							book_HTML +=     '			</div>';
							book_HTML +=     '		</div>';
							book_HTML +=     '	</footer>';
							book_HTML +=     '</article>';
							
							
							//Agrego el libro a la lista
							$(books_list_HTML).append( $(book_HTML) );

							i++;
						
						});
						

						$('body').removeClass('loading');
						
						//Imprimo la lista en la página
						$('#books-results').append( $(books_list_HTML).html() );		
					
					
					}
				}
			});
			
		}
		
		
		$('body').on('click', '#books-results .choose-data input[type="checkbox"]', function(){
			var box_data = $(this).parents('footer');
			var data_count = $(box_data).find('input:checked').length;
			
			if( data_count <= 0 ){
				$(box_data).find('.btn-save-data').attr("disabled", true);
			}else{
				$(box_data).find('.btn-save-data').attr("disabled", false);
			}
			
		});

		
		/* POST DATA */
		$('body').on('click', '#books-results .btn-save-data', function(){
			
			var book = $(this).parents('.book');
			
			
			var post_id = <?php echo $post_id; ?>;
			
			/* Recupero los datos del libro */
			var book_isbn = $(book).data('book-isbn');
			var book_cover = ( $(book).find('.data-cover:checked').length ) ? $(book).data('book-cover') : '';
			var book_title = ( $(book).find('.data-title:checked').length ) ? $(book).data('book-title') : '';
			var book_authors = ( $(book).find('.data-authors:checked').length ) ? $(book).data('book-authors') : '';
			
			
			$.ajax({
				type : "POST",
				data: { submit: "submit", post_ID: post_id, image_url: book_cover, book_title: book_title,  book_authors: book_authors, book_ISBN: book_isbn },
				//data: '?submit=submit' + '&post_ID='+ post_id +'&image_url='+ book_cover +'&book_title='+ book_title +'&book_authors='+ book_authors +'&book_ISBN='+ book_isbn,
				url: "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>",
				//dataType : "json",
				beforeSend:function(){
					$('body').addClass('loading');
				},
				success: function( data ) {
				
					if( data.response == 1 ){
						
						parent.location.href = "<?php  echo get_edit_post_link( $post_id , ' '); ?>";
						
					}else{
						alert('Se produjo un error al cargar el libro');
						$('body').removeClass('loading');
					}
				
				},
				error:function(){
					alert('Error inesperado');
					$('body').removeClass('loading');
				}
			});
				
			
			
			
			
			
		});
		
		$('body').on('click', '.btn-popup-close', function(){
			$('.mfp-close').trigger('click');
		});
		
		
		
		init();

	</script>
	
</body>
</html>
<?php } ?>	