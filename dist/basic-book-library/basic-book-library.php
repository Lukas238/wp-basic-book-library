<?php
/**
 * Plugin Name: Basic Book Library
 * Plugin URI: http://c238.com.ar
 * Description: This plugin adds the kind of post Books, which allows the load of books. Each book can contain the following information: title, description, author, ISBN number, cover image, genres and series and number of book in the series.
 * Version: 1.0
 * Author: Lucas Dasso
 * Author URI: http://c238.com.ar
 */
 
 
/************
	SETUP
 ************/
 
 /* IMAGE SIZE */
add_image_size( 'bbl-book-cover', 9999, 800, false);
add_image_size( 'bbl-book-cover-medium', 9999, 393, false);
add_image_size( 'bbl-book-cover-thumb', 9999, 226, false);



 
// Register Custom Post Type BOOKS
function basicbooklibrary_custom_post_type_books() {

	$labels = array(
		'name'                => _x( 'Libros', 'Post Type General Name', 'basicbooklibrary' ),
		'singular_name'       => _x( 'Libro', 'Post Type Singular Name', 'basicbooklibrary' ),
		'menu_name'           => __( 'Libros', 'basicbooklibrary' ),
		'parent_item_colon'   => __( 'Libro padre:', 'basicbooklibrary' ),
		'all_items'           => __( 'Todos los Libros', 'basicbooklibrary' ),
		'view_item'           => __( 'Ver Libro', 'basicbooklibrary' ),
		'add_new_item'        => __( 'Agregar un nuevo Libro', 'basicbooklibrary' ),
		'add_new'             => __( 'Agregar Libro', 'basicbooklibrary' ),
		'edit_item'           => __( 'Editar Libro', 'basicbooklibrary' ),
		'update_item'         => __( 'Actualizar Libro', 'basicbooklibrary' ),
		'search_items'        => __( 'Buscar Libro', 'basicbooklibrary' ),
		'not_found'           => __( 'No se encontr&oacute; nung&uacute;n libro', 'basicbooklibrary' ),
		'not_found_in_trash'  => __( 'No se encontr&oacute; nung&uacute;n libro en la Papelera', 'basicbooklibrary' ),
	);
	$rewrite = array(
		'slug'                => 'books',
		'with_front'          => true,
		'pages'               => true,
		'feeds'               => true,
	);
	$args = array(
		'label'               => __( 'Libros', 'basicbooklibrary' ),
		'description'         => __( 'Posts de libros.', 'basicbooklibrary' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'thumbnail' ),
		'taxonomies'          => array( 'bbl_genres_taxonomy', 'bbl_series_taxonomy' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 20,
		'menu_icon'           => '',
		'can_export'          => true,
		'has_archive'         => 'bookshelf', //true,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'rewrite'             => $rewrite,
		'capability_type'     => 'page',
	);
	register_post_type( 'bbl_post_book', $args );

}

// Hook into the 'init' action
add_action( 'init', 'basicbooklibrary_custom_post_type_books', 0 );




// Register Custom Taxonomy
function basicbooklibrary_add_books_taxonomy() {

	$labels = array(
		'name'                       => _x( 'G&eacute;neros', 'Taxonomy General Name', 'basicbooklibrary' ),
		'singular_name'              => _x( 'G&eacute;nero', 'Taxonomy Singular Name', 'basicbooklibrary' ),
		'menu_name'                  => __( 'G&eacute;neros', 'basicbooklibrary' ),
		'all_items'                  => __( 'Todos los g&eacute;neros', 'basicbooklibrary' ),
		'parent_item'                => __( 'G&eacute;nero padre', 'basicbooklibrary' ),
		'parent_item_colon'          => __( 'G&eacute;nero padre:', 'basicbooklibrary' ),
		'new_item_name'              => __( 'Nuevo G&eacute;nero', 'basicbooklibrary' ),
		'add_new_item'               => __( 'Agregar G&eacute;nero', 'basicbooklibrary' ),
		'edit_item'                  => __( 'Editar G&eacute;nero', 'basicbooklibrary' ),
		'update_item'                => __( 'Actualizar G&eacute;nero', 'basicbooklibrary' ),
		'separate_items_with_commas' => __( 'Separa g&eacute;neros con comas', 'basicbooklibrary' ),
		'search_items'               => __( 'Buscar G&eacute;neros', 'basicbooklibrary' ),
		'add_or_remove_items'        => __( 'Agregar o quitar un G&eacute;nero', 'basicbooklibrary' ),
		'choose_from_most_used'      => __( 'Elegir de entre los g&eacute;nero m&aacute;s usados', 'basicbooklibrary' ),
		'not_found'                  => __( 'No se encontr&oacute; ning&uacute;n g&eacute;nero', 'basicbooklibrary' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'bbl_genres_taxonomy', array( 'bbl_post_book' ), $args );
	
	
	
	$labels = array(
		'name'                       => _x( 'Series', 'Taxonomy General Name', 'basicbooklibrary' ),
		'singular_name'              => _x( 'Serie', 'Taxonomy Singular Name', 'basicbooklibrary' ),
		'menu_name'                  => __( 'Series', 'basicbooklibrary' ),
		'all_items'                  => __( 'Todas las Series', 'basicbooklibrary' ),
		'parent_item'                => __( 'Serie padre', 'basicbooklibrary' ),
		'parent_item_colon'          => __( 'Serie padre:', 'basicbooklibrary' ),
		'new_item_name'              => __( 'Nombre de la nueva Serie', 'basicbooklibrary' ),
		'add_new_item'               => __( 'Agregar Serie', 'basicbooklibrary' ),
		'edit_item'                  => __( 'Editar Serie', 'basicbooklibrary' ),
		'update_item'                => __( 'Actulizar Serie', 'basicbooklibrary' ),
		'separate_items_with_commas' => __( 'Separar series con coma', 'basicbooklibrary' ),
		'search_items'               => __( 'Buscar Serie', 'basicbooklibrary' ),
		'add_or_remove_items'        => __( 'Agregar o quitar serie', 'basicbooklibrary' ),
		'choose_from_most_used'      => __( 'Elegir de entre las series m&aacute;s usadas', 'basicbooklibrary' ),
		'not_found'                  => __( 'No se encontr&oacute; ning&uacute;na serie', 'basicbooklibrary' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'bbl_series_taxonomy', array( 'bbl_post_book' ), $args );

}

// Hook into the 'init' action
add_action( 'init', 'basicbooklibrary_add_books_taxonomy', 0 );




/* AGREGO VARIABLES DE QUERY PARA LA PAGINA DE ARCHIVO */
function basicbooklibrary_add_query_vars( $vars ){
  $vars[] = "genre";
  return $vars;
}
add_filter( 'query_vars', 'basicbooklibrary_add_query_vars' );


/* CUSTOM TEMPLATES */

/* Archive template */
function basicbooklibrary_archive_template( $archive_template ) {
    global $post;

	wp_enqueue_style( 'basicbooklibrary_archive_styles', plugins_url( 'css/archive.css', __FILE__) );
	wp_enqueue_style( 'basicbooklibrary_bookshelf_styles', plugins_url( 'css/bookshelf.css', __FILE__) );
	
	
	/* Checks for archive template by post type */
	if ( is_post_type_archive( 'bbl_post_book') ){

		if(file_exists( TEMPLATEPATH  . '/archive-bbl_post_book.php')){
			return TEMPLATEPATH  . '/archive-bbl_post_book.php';
		}
		
		if(file_exists( dirname(__FILE__) . '/templates/archive.php')){
			return dirname(__FILE__) . '/templates/archive.php';
		}
		
	}
	
}
add_filter( 'archive_template', 'basicbooklibrary_archive_template' ) ;





/* TAXONOMY ARCHIVE */
function basicbooklibrary_taxonomy_template($template){
	
	wp_enqueue_style( 'basicbooklibrary_bookshelf_styles', plugins_url( 'css/bookshelf.css', __FILE__) );
	
	$taxonomy_array = array(
		'bbl_genres_taxonomy',
		'bbl_series_taxonomy'
	);
		
	foreach ( $taxonomy_array as $taxonomy_name ) {
			
		if ( is_tax($taxonomy_name) ) {
		
			if(file_exists(TEMPLATEPATH . '/taxonomy-' . $taxonomy_name . '.php' )) {
				return TEMPLATEPATH . '/taxonomy-' . $taxonomy_name . '.php' ;
			}
			
			
			if(file_exists(dirname(__FILE__) . '/templates/taxonomy-' . $taxonomy_name . '.php' )) {
				return dirname(__FILE__) . '/templates/taxonomy-' . $taxonomy_name . '.php' ;
			}
			
			
			if(file_exists(dirname(__FILE__) . '/templates/taxonomy.php' )) {
				return dirname(__FILE__) . '/templates/taxonomy.php' ;
			}
		
		}		
	}
	
	
	return $template;
}
add_filter( 'template_include', 'basicbooklibrary_taxonomy_template' ) ;


/* Single Template */
function basicbooklibrary_single_template($single) {
    global $wp_query, $post;

	/* Checks for single template by post type */
	if ($post->post_type == "bbl_post_book"){

		if(file_exists( TEMPLATEPATH  . '/single-bbl_post_book.php')){
			return TEMPLATEPATH  . '/single-bbl_post_book.php';
		}
		
		if(file_exists( dirname(__FILE__) . '/templates/single.php')){
			return dirname(__FILE__) . '/templates/single.php';
		}
		
	}
	
    return $single;
}
add_filter('single_template', 'basicbooklibrary_single_template');


/**
*	BOOKSHELF SHORTCODE
*
*	genres	string
*	series	string
*	books_per_page	integer	Default: 20
*
*/
add_shortcode('bbl-bookshelf', 'basicbooklibrary_shortcode_bookshelf');

function basicbooklibrary_shortcode_bookshelf( $atts ){
	
	$args = shortcode_atts(
		array(
			'genres' => '',
			'series' => '',
			'books_per_page' => '20'
		),
		$atts 
	);
	
	return basicbooklibrary_bookshelf($args);
}




function basicbooklibrary_bookshelf($args){
	
	wp_enqueue_style( 'basicbooklibrary_bookshelf_styles', plugins_url( 'css/bookshelf.css', __FILE__) );
	
	extract( $args ); 

	$genres = ( trim($genres) <> '' ) ? explode(',', $genres) : $genres;
	$series = ( trim($series) <> '' ) ? explode(',', $series) : $series;
	
	
	$filters = array();
	
	
	if( is_array( $genres ) ){
		array_push($filters, array(
			   'taxonomy' => 'bbl_genres_taxonomy',
			   'field' => 'id',
			   'terms' => $genres
			)
		);
	}
	
	if( is_array( $series ) ){
		array_push($filters, array(
			   'taxonomy' => 'bbl_series_taxonomy',
			   'field' => 'id',
			   'terms' => $series
			)
		);
	}
	
	$bookshelf = new WP_Query(
		array(
			'post_type' => 'bbl_post_book',
			'tax_query' => $filters,
			'posts_per_page' => $books_per_page
		)
	);
		
	ob_start(); 
	
	include( dirname(__FILE__) . '/templates/bookshelf.php');
	
	$output_string = ob_get_contents();  
    ob_end_clean();  
	
	return $output_string; 
}



function basicbooklibrary_remove_meta_boxes(){
    if ( ! is_admin() )
        return;

    remove_meta_box( 'tagsdiv-bbl_series_taxonomy', 'bbl_post_book', 'side' ); 
    remove_meta_box( 'postimagediv','post','side' );
}
add_action( 'admin_menu', 'basicbooklibrary_remove_meta_boxes' );





/* WP STYLES */
function basicbooklibrary_add_custom_styles() {
	wp_enqueue_style( 'pureCSS', '//cdnjs.cloudflare.com/ajax/libs/pure/0.5.0/pure-min.css' );
	wp_enqueue_style( 'fontAwosome', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.1.0/css/font-awesome.min.css' );
}
add_action( 'wp_enqueue_scripts', 'basicbooklibrary_add_custom_styles' );


/* WP ADMIN STYLES*/
function basicbooklibrary_add_custom_style_in_admin($hook) {
	global $post_type;
	
	/* ADMIN */
	wp_enqueue_style( 'fontAwosome', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.1.0/css/font-awesome.min.css' );
	wp_enqueue_style( 'basicbooklibrary_admin_Styles', plugins_url( 'css/admin.css', __FILE__) );
	
	/* BOOK POST PAGE */
    if( ($hook == 'post-new.php' || $hook == 'post.php') && 'bbl_post_book' == $post_type ){
		wp_enqueue_style( 'pureCSS', '//cdnjs.cloudflare.com/ajax/libs/pure/0.5.0/pure-min.css' );
		wp_enqueue_style( 'magnific_popup_styles', '//cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/0.9.9/magnific-popup.css' );
	}
}
add_action( 'admin_enqueue_scripts', 'basicbooklibrary_add_custom_style_in_admin' );


/* WP ADMIN SCRIPTS */
function basicbooklibrary_add_custom_scripts_in_admin($hook) {
    global $post_type;
	
	/* BOOK POST PAGE */
    if( ($hook == 'post-new.php' || $hook == 'post.php') && 'bbl_post_book' == $post_type ){
		wp_enqueue_script('magnific_popup_scripts', '//cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/0.9.9/jquery.magnific-popup.min.js');
		wp_enqueue_script( 'basicbooklibrary_edit', plugins_url( 'js/scripts_page_edit.js', __FILE__) );
	}
}
add_action( 'admin_enqueue_scripts', 'basicbooklibrary_add_custom_scripts_in_admin' );


/**
 * Adds a meta box to the post editing screen of Books
 */
function bbl_post_book_add_meta_box() {
    add_meta_box( 'basicbooklibrary_meta_box_bookinfo', 'Book Info', 'basicbooklibrary_meta_callback_bookinfo', 'bbl_post_book', 'normal' );
	
	add_meta_box( 'basicbooklibrary_meta_box_bookinfo_series', 'Series', 'basicbooklibrary_meta_callback_bookinfoserie', 'bbl_post_book', 'side' );
	
	add_meta_box('postimagediv', __('Book cover', 'basicbooklibrary'), 'post_thumbnail_meta_box', 'bbl_post_book', 'side', 'low');
}
add_action( 'add_meta_boxes', 'bbl_post_book_add_meta_box' );


/**
 * Outputs the content of the metabox
 */
function basicbooklibrary_meta_callback_bookinfo( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'bbl_bookinfo_nonce' );
	
	
	//Recupero los metadatos
    $meta_bookinfo = get_post_meta( $post->ID);
    ?>
	<div class="pure-form pure-form-aligned">
	
		<div class="pure-control-group">
			<label><?php _e('ISBN', 'basicbooklibrary'); ?>:</label>
			<input type="text" name="basicbooklibrary-meta-bookinfo-isbn" id="basicbooklibrary-meta-bookinfo-isbn" value="<?php echo $meta_bookinfo['_basicbooklibrary-meta-bookinfo-isbn'][0]; ?>" size="20" />
			<button type="button" id="btn-get-obl" class="pure-button" data-iframe-url="<?php echo plugins_url( 'retrive-book-data.php', __FILE__); ?>"><span><?php _e('Retrive info from Open Book Library', 'basicbooklibrary'); ?></span></button>
		</div>

		<hr />
		
		<div class="pure-control-group">
			<Label><?php _e('Author', 'basicbooklibrary'); ?>:</label>
			<input type="text" name="basicbooklibrary-meta-bookinfo-author" id="basicbooklibrary-meta-bookinfo-author" value="<?php echo $meta_bookinfo['_basicbooklibrary-meta-bookinfo-author'][0]; ?>" size="30" />
		</div>
		
		<div class="pure-control-group">
			<Label><?php _e('Author sort', 'basicbooklibrary'); ?>:</label>
			<input type="text" name="basicbooklibrary-meta-bookinfo-author-sort" id="basicbooklibrary-meta-bookinfo-author-sort" value="<?php echo $meta_bookinfo['_basicbooklibrary-meta-bookinfo-author-sort'][0]; ?>" size="30" />
			<button type="button" id="btn-update-author-sort" class="pure-button" ><span><?php _e('Update sort author text', 'basicbooklibrary'); ?></span></button>
		</div>
	
	
		<div class="pure-control-group pure-control-group-textarea">
			<Label><?php _e('Descripción', 'basicbooklibrary'); ?>:</label>
			<?php
			$settings = array(
				'textarea_name'	=> 'basicbooklibrary-meta-bookinfo-description',
				'media_buttons' => false,
				'teeny' => true,
				'quicktags' => false
			);
			wp_editor( $meta_bookinfo['_basicbooklibrary-meta-bookinfo-description'][0], 'basicbooklibrarymetabookinfodescription', $settings );
			?>
		</div>

	
		
		<h3><?php _e('Links de archivos', 'basicbooklibrary'); ?></h3>
	
		<div id="book-file-list">
		
			<div id="box-add-file" class="pure-control-group">
				<label><?php _e('URL del libro', 'basicbooklibrary'); ?>: </label>
				<span  class="file-item">
					<input type="text" size="60" />
					<button type="button" class="btn-add-file pure-button pure-button-primary"><span><?php _e('Add File', 'basicbooklibrary'); ?></span></button>
				</span>
			</div>
			
			<?php
				//Loopeo por cada archivo
				$files = $meta_bookinfo['_basicbooklibrary-file'];
				foreach( $files as $file_key => $file ){
				
					$pos = strrpos( $file,'.' );
					
					if( !$pos ){
						$ext = "link";
					}else{
						$ext = substr( $file, $pos + 1, 3 );
					}
					
			?>
			<div class="pure-control-group">
				<label><?php echo $ext; ?></label>
				<span class="file-item file-item-<?php echo $ext; ?>">
					<input type="text" name="basicbooklibrary-file[]" value="<?php echo $file; ?>" size="60" />
					<button type="button" class="btn-remove-file pure-button"><span><?php _e('Remove File', 'basicbooklibrary'); ?></span></button>
				</span>
			</div>
			<?php
				}
			?>
		</div>
		
	
	</div><!-- .pure-form -->
	
	<script>
		$j=jQuery.noConflict();
		$j(document).ready(function($){
			
			/* Ordeno el nombre del author */
			$('#btn-update-author-sort').on('click', function(){
				var author = $('#basicbooklibrary-meta-bookinfo-author').val();
				
				var firstName = author.split(' ').slice(0, -1).join(' ');
				var lastName = author.split(' ').slice(-1).join(' ');
				
				var author_sort = lastName + ', ' + firstName;
				
				$('#basicbooklibrary-meta-bookinfo-author-sort').val( author_sort );
				
			});
			
			
			/* AGrego un nuevo input de file-item */
			$('#book-file-list .btn-add-file').on('click', function(){
				var file_url = $(this).siblings('input').val();
				
				if( file_url == ''){
					return false;
				}
				
				var pos = file_url.lastIndexOf('.');
				
				if ( pos == -1 ){
					var ext = "link";
				}else{
					var ext = file_url.substr( pos + 1, 3);
				}
				
				$(this).siblings('input').val('');
				
				
				var item_html = '<div class="pure-control-group">';
				item_html +=	'	<label>'+ ext +'</label>';
				item_html +=	'	<span class="file-item file-item-'+ ext +'">';
				item_html +=	'		<input type="text" name="basicbooklibrary-file[]" value="'+ file_url +'" size="60" />';
				item_html +=	'		<button type="button" class="btn-remove-file pure-button"><span><?php _e('Remove File', 'basicbooklibrary'); ?></span></button>';
				item_html +=	'	</span>';
				item_html +=	'</div>';
			
				
				$('#book-file-list').append( item_html );
					
			});
			
			/* Quito el file-item actual */
			$('#book-file-list').on('click', '.btn-remove-file', function(){
				$(this).parents('.pure-control-group').fadeOut().slideUp(400, function(){
					$(this).remove();
				})
			});
			
		})
	</script>
	
	<?php	
	
	global $post;
	echo '<input type="hidden" id="book-post-id" value="'. $post->ID .'">';
	
}


function basicbooklibrary_meta_callback_bookinfoserie( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'bbl_bookinfo_nonce' );
	
	
	//Recupero los metadatos
    $meta_bookinfo = get_post_meta( $post->ID);
	
			
	global $post_ID;
	$post = get_post( $post_ID );
	$box = array(
		'args' => array(
			'taxonomy' => 'bbl_series_taxonomy'
		)
	);
	
	post_tags_meta_box($post, $box);
	
	?>
		
	<ul>
		<li class="serie-number">
			<label>Number: </label>
			<input type="text" name="basicbooklibrary-meta-bookinfo-serie-number" id="basicbooklibrary-meta-bookinfo-serie-number" value="<?php echo $meta_bookinfo['_basicbooklibrary-meta-bookinfo-serie-number'][0]; ?>" size="20" />
		</li>
	</ul>
	
	<?php
}

/**
 * Saves the custom meta input
 */
function basicbooklibrary_meta_save_bookinfo( $post_id ) {
 
    // Checks save status
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'bbl_bookinfo_nonce' ] ) && wp_verify_nonce( $_POST[ 'bbl_bookinfo_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
 
    // Exits script depending on save status
    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
        return;
    }
 
	//AUTHOR
	if( isset( $_POST[ 'basicbooklibrary-meta-bookinfo-author' ] ) ) {
        update_post_meta( $post_id, '_basicbooklibrary-meta-bookinfo-author', sanitize_text_field( $_POST[ 'basicbooklibrary-meta-bookinfo-author' ] ) );
    }
	
	//AUTHOR SORT
	if( isset( $_POST[ 'basicbooklibrary-meta-bookinfo-author-sort' ] ) ) {
        update_post_meta( $post_id, '_basicbooklibrary-meta-bookinfo-author-sort', sanitize_text_field( $_POST[ 'basicbooklibrary-meta-bookinfo-author-sort' ] ) );
    }
	
    //ISBN
	if( isset( $_POST[ 'basicbooklibrary-meta-bookinfo-isbn' ] ) ) {
        update_post_meta( $post_id, '_basicbooklibrary-meta-bookinfo-isbn', sanitize_text_field( $_POST[ 'basicbooklibrary-meta-bookinfo-isbn' ] ) );
    }
		
	//SERIE-NUMBER
	if( isset( $_POST[ 'basicbooklibrary-meta-bookinfo-serie-number' ] ) ) {
        update_post_meta( $post_id, '_basicbooklibrary-meta-bookinfo-serie-number', sanitize_text_field( $_POST[ 'basicbooklibrary-meta-bookinfo-serie-number' ] ) );
    }
	
	//Description
	if( isset( $_POST[ 'basicbooklibrary-meta-bookinfo-description' ] ) ) {
        update_post_meta( $post_id, '_basicbooklibrary-meta-bookinfo-description', sanitize_text_field( $_POST[ 'basicbooklibrary-meta-bookinfo-description' ] ) );
    }
	
	//Files
	delete_post_meta( $post_id, '_basicbooklibrary-file' );
	if( isset( $_POST['basicbooklibrary-file'] ) ){
		foreach( $_POST['basicbooklibrary-file'] as $file_key => $file ){
			if( $file <> '' ){
				add_post_meta( $post_id, '_basicbooklibrary-file', sanitize_text_field( $file ) );
			}
		}
	}
	
	delete_post_meta( $post_id, '_basicbooklibrary-file-epub');
	delete_post_meta( $post_id, '_basicbooklibrary-file-pdf');
	delete_post_meta( $post_id, '_basicbooklibrary-file-zip');
	
	
 
} // end dirvetbasicbooklibrary_meta_save_testimonios()
add_action( 'save_post', 'basicbooklibrary_meta_save_bookinfo' );



 
 
 ?>