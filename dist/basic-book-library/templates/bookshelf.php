				
					<section id="bookshelf">
						<div class="pure-g">
						<?php while ( $bookshelf->have_posts() ) : $bookshelf->the_post();
							$meta_bookinfo = get_post_meta( $post->ID);
						?>
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
					</section>
			