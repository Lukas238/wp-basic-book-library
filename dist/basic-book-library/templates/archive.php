<?php get_header(); ?>
		
			<h1 class="page-title">
				<?php
				if ( is_category() ) :
					printf( __( 'Category: %s', 'basicbooklibrary' ), single_cat_title( '', false ) );

				elseif ( is_tag() ) :
					printf( __( 'Tag: %s', 'basicbooklibrary' ), single_tag_title( '', false ) );

				elseif ( is_day() ) :
					printf( __( 'Day: %s', 'basicbooklibrary' ), '<span>' . get_the_date() . '</span>' );

				elseif ( is_month() ) :
					printf( __( 'Month: %s', 'basicbooklibrary' ), '<span>' . get_the_date( 'F Y' ) . '</span>' );

				elseif ( is_year() ) :
					printf( __( 'Year: %s', 'basicbooklibrary' ), '<span>' . get_the_date( 'Y' ) . '</span>' );

				elseif ( is_tax( 'post_format', 'post-format-aside' ) ) :
					_e( 'Asides', 'basicbooklibrary' );

				elseif ( is_tax( 'post_format', 'post-format-image' ) ) :
					_e( 'Images', 'basicbooklibrary');

				elseif ( is_tax( 'post_format', 'post-format-video' ) ) :
					_e( 'Videos', 'basicbooklibrary' );

				elseif ( is_tax( 'post_format', 'post-format-quote' ) ) :
					_e( 'Quotes', 'basicbooklibrary' );

				elseif ( is_tax( 'post_format', 'post-format-link' ) ) :
					_e( 'Links', 'basicbooklibrary' );

				else :
					_e( 'Bookshelf', 'basicbooklibrary' );

				endif;
				?>
			</h1>
			
			
			<?php
			$taxonomy = 'bbl_genres_taxonomy';
			$terms = get_terms( $taxonomy );
			
			$genres_var = get_query_var( 'genre' );
			$genres_arr = explode( ',', $genres_var);

			if( is_array( $terms ) ){
				
			?>
			<div id="term-list-genres" class="terms-list pure-menu pure-menu-open pure-menu-horizontal">
				<ul>
			<?php
				
				$archive_url = get_post_type_archive_link( 'bbl_post_book' );
				
				$class_menu_active = '';
				if( $genres_var == '' ){
					$class_menu_active = ' class="pure-menu-selected"';
				}
				$output = '<li'. $class_menu_active .'><a href="'. $archive_url .'">'. __('Todos', 'basicbooklibrary') .'</a></li>';
				
				foreach ( $terms as $term ) {
					
					$class_menu_active = '';
					if( in_array( $term->slug, $genres_arr ) ){
						$class_menu_active = ' class="pure-menu-selected"';
					} 
					
					$output .= '<li'. $class_menu_active .'><a href="'. $archive_url .'?genre='. $term->slug .'">'. $term->name .'</a></li>';
				}
				
				echo $output;
			?>
			</div><!-- #term-list-genres -->
			<?php
			}
			?>			
			
			<div id="content" class="site-content" role="main">
				
				<?php				
					$genres_id_arr = array();
					foreach( $genres_arr as $genre ){
						//Recupero el ID del genero
						$genre_term = get_term_by( 'slug', $genre, $taxonomy);
						$genres_id_arr[] = $genre_term->term_id;
					}
					
					$genres_id_list = implode( ',', $genres_id_arr );
					
				
					$atts = array(
						'genres' => $genres_id_list 
					);
					
					echo basicbooklibrary_bookshelf($atts);
				?>

			</div>
			
		
<?php get_footer(); ?>