<?php
/**
 * Category page template
 * Template Name: Category page layout
 */
get_header();

?>
<div class="solid-header">
	<?php get_template_part('partials/header-nav-solid'); ?>
</div>
<?php
?>
<div class="main-wrap">
	<main>
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
