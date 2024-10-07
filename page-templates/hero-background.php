<?php
/**
 * Hero Background template
 * Template Name: Hero Background
 */

get_header();

get_template_part( 'partials/header-nav-transparent' );
?>
<div class="main-wrap">
	<main>
		<section id="page-content">
			<div class="container">

				<?php if ( have_posts() ) : ?>

					<?php the_post(); ?>
					<?php the_content(); ?>

				<?php endif; ?>

			</div>
		</section>
	</main>
</div>
<?php
get_footer();
