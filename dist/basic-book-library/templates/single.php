<?php get_header(); ?>

			<div id="content" class="site-content" role="main">
			
			<?php while ( have_posts() ) : the_post();
			
			$meta_bookinfo = get_post_meta( $post->ID);
			?>

				
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="pure-g">
					<div class="pure-u-1-2">
						<?php if ( has_post_thumbnail() && !post_password_required() && empty( $single_featured_image )) : ?>
							<div class="entry-thumbnail"><?php the_post_thumbnail( 'bbl-book-cover-medium' ); ?></div>
						<?php endif; ?>
					</div>
					<div class="pure-u-1-2">
						<header class="entry-header">
							<h1 class="entry-title"><?php the_title(); ?></h1>				
							
							<!--
							<?php $single_metadata = flat_get_theme_option('single_metadata'); if ( empty( $single_metadata ) ) : ?>
							  <div class="entry-meta"><?php flat_entry_meta(); ?></div>
							<?php endif; ?>
							-->
						</header>
						
						
						<div class="meta">
							<ul>
								<li class="author">
									<Label>Author:</label> <?php echo $meta_bookinfo['_basicbooklibrary-meta-bookinfo-author'][0]; ?>
								</li>
								<li class="isbn">
									<label>ISBN:</label> <?php echo $meta_bookinfo['_basicbooklibrary-meta-bookinfo-isbn'][0]; ?>
								</li>
							</ul>
						</div>

					</div>
				</div>
				
				<div class="entry-content">
				
					<?php  echo apply_filters('the_content', $meta_bookinfo['_basicbooklibrary-meta-bookinfo-description'][0]); ?>
					
					
					<?php wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'flat' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) ); ?>
				</div>

			
			</article>
			
			<?php $single_author_box = flat_get_theme_option('single_author_box'); ?>
			<?php if ( get_the_author_meta( 'description' ) && empty( $single_author_box ) ) : ?>
				<?php get_template_part( 'author-bio' ); ?>
			<?php endif; ?>

				<?php flat_post_nav(); ?>

				<?php comments_template(); ?>
				
			<?php endwhile; ?>

			</div>

<?php get_footer(); ?>