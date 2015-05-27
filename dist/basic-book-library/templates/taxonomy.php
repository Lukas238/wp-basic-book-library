<?php get_header(); ?>

<?php
	$taxonomy = get_query_var( 'taxonomy' );
	$term_slug = get_query_var( 'term' );
	
	
?>
		
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

				elseif ( is_tax() ) :
					_e( 'Bookshelf ', 'basicbooklibrary' );

				//get_queried_object()->term_slug;
				
				$term = get_term_by( 'slug', $term_slug, $taxonomy );
				echo $term->name;

				else :
					_e( 'Bookshelf', 'basicbooklibrary' );

				endif;
				?>
			</h1>
			
			
			<?php
			
			
			$term_objects = get_terms( $taxonomy );

			foreach ( $term_objects as $term_object ) {
				$term_object->url = get_term_link( $term_object, $taxonomy );
				$term_object->permalink = '<a href="' . $term_object->url . '">' . $term_object->name . '</a>';
			}
			
			$output = '<ul>';
			foreach ( $term_objects as $term_object ) {
				
				$current_class = '';
				if( $term_object->slug == $term_slug ){
					$current_class = 'current';
				}
				
				$output .= '<li class="'. $current_class .'">' . $term_object->permalink . '</li>';
			}
			$output .= '</ul>';

			// Now return the output
			echo $output;
			?>
			
			<?php
				// Show an optional term description.
				$term_description = term_description();
				if ( ! empty( $term_description ) ) :
					printf( '<div class="taxonomy-description">%s</div>', $term_description );
				endif;
			?>
			
			
			<div id="content" class="site-content" role="main">
			
				<section id="bookshelf">
					<?php if ( have_posts() ) : ?>
					<div class="pure-g">

					<?php while ( have_posts() ) : the_post(); ?>
						<div class="pure-u-1-3">
							<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
								
								<header>
									<h1 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
									<!--
									<span class="author"><?php echo $meta_bookinfo['_basicbooklibrary-meta-bookinfo-author'][0]; ?></span>
									-->
								</header>
								
								<div class="entry-thumbnail">
								<?php
								if ( has_post_thumbnail() ){
									the_post_thumbnail( 'bbl-book-cover-thumb' );
								};
								?>
								</div>
								
							</article>
						</div>
										
					<?php endwhile; ?>
					
					</div>
					
					<?php flat_paging_nav(); ?>

				<?php else : ?>
					<?php get_template_part( 'content', 'none' ); ?>
				<?php endif; ?>
				
				</section>

			</div>
			
		
<?php get_footer(); ?>