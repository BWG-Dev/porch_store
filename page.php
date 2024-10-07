<?php
/**
 * Common template
 */

get_header(); ?>

<?php get_template_part('partials/header-nav-with-background'); ?>

<?php // resolve if show sidebar layout
$sidebar = my_get_sidebar(); ?>

<div class="main-wrap <?php if($sidebar) echo 'has-sidebar has-sidebar-' . $sidebar; ?>">

	<?php if($sidebar): ?>
		<aside>
			<?php dynamic_sidebar($sidebar); ?>
		</aside>
	<?php endif; ?>

	<main>
		<section id="page-content">
			<div class="container">

				<?php while(have_posts()):
					the_post(); ?>

					<?php if(is_archive() || is_home()): ?>
						<h2 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<?php the_excerpt(); ?>
					<?php else: ?>
						<?php the_content(); ?>

					<?php endif; ?>

				<?php endwhile; ?>

			</div>
		</section>
	</main>

</div>
<?php get_footer();
