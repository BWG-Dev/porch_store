<?php
/**
 * The template for displaying search results pages
 *
 */

get_header(); 

get_template_part('partials/header-nav');

?>

<div class="main-wrap">
	<main>
 
		<section id="page-content">
			<div class="container">
			
			 	<header class="page-header">
					<?php if ( have_posts() ) : ?>
						<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'twentyseventeen' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
					<?php else : ?>
						<h1 class="page-title"><?php _e( 'Nothing Found', 'twentyseventeen' ); ?></h1>
					<?php endif; ?>
				</header><!-- .page-header -->
			
			
			<?php
		if ( have_posts() ) :
			/* Start the Loop */
			while ( have_posts() ) :
			
			 the_post();

				/**
				 * Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called content-search.php and that will be used instead.
				 */
				 the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
  				   the_excerpt(); 
 
			endwhile; // End of the loop.

			the_posts_pagination( array(
				'prev_text' =>  '<span class="screen-reader-text">' . __( 'Previous page', 'twentyseventeen' ) . '</span>',
				'next_text' => '<span class="screen-reader-text">' . __( 'Next page', 'twentyseventeen' ) . '</span>' ,
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'twentyseventeen' ) . ' </span>',
			) );

		else : ?>

			<p><?php _e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'twentyseventeen' ); ?></p>
			<?php
				get_search_form();

		endif;
		?>
			
			
 

			</div>
		</section>
	</main>
</div>
<?php get_footer();

?>