<?php
/**
 * Store landing page template
 * Template Name: Store Landing Page
 */

get_header();

// header nav without any wrapper - just absolute positioned over hero image
get_template_part('partials/header-nav');
?>

<div class="main-wrap">
	<main>
		<section id="store-landing-hero">
			<div class="content">
				<h2 class="size-h1">Your porch should be a beautiful place to relax.</h2>
				<h1 class="size-h4">We offer unique porch products designed to fit your lifestyle.</h1>

				<div class="buttons">
					<a class="btn-white" href="#store">Get Porch Products</a>
				</div>
			</div>
		</section>

		<section id="page-content">
			<div class="container">

				<?php if(have_posts()): ?>

					<?php the_post(); ?>
					<?php the_content(); ?>

				<?php endif; ?>

			</div>
		</section>
	</main>
</div>
<?php get_footer();
