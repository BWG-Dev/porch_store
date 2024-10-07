<?php
/**
 * Blog archive
 */

get_header();
get_template_part( 'partials/header-nav-transparent' );

$page_id       = get_option( 'page_for_posts' );
$thumbnail_url = get_the_post_thumbnail_url( $page_id, 'full' );
if ( empty( $thumbnail_url ) ) {
	$thumbnail_url = get_template_directory_uri() . '/dist/images/home-banner.jpg';
}
?>
<section class="hero-image" style="background-image:url(<?php echo esc_url( $thumbnail_url ); ?>)">
	<div class="hero-image__content">
		<h1><?php echo get_the_title( $page_id ); ?></h1>
		<a class="" href="https://porchstore.nextsitehosting.com/#porchcategories" tabindex="0">Get Porch Store Products</a>
	</div>
</section>

<?php $sidebar = my_get_sidebar(); ?>

<div class="main-wrap <?php if($sidebar) echo 'has-sidebar has-sidebar-' . $sidebar; ?>">

	<?php if($sidebar): ?>
		<aside>
			<?php dynamic_sidebar($sidebar); ?>
		</aside>
	<?php endif; ?>

	<main>
		<section id="blog-archive">
			<div class="container">

				<?php while(have_posts()):
					the_post(); ?>

					<div class="post-block">
						<?php if(has_post_thumbnail()): ?>
							<div class="img-wrap">
								<a href="<?php the_permalink(); ?>">
									<?php the_post_thumbnail( 'full', [
										'sizes' => '(max-width: 509px) calc(100vw - 95px), (max-width: 767px) calc(100vw - 165px), (max-width: 1304px) calc(40vw - 96px), 426px'
									] ); ?>
								</a>
							</div>
						<?php endif; ?>

						<h2 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<p class="date-category-wrap">
							<i class="fa fa-clock-o"></i> <span class="date"><?php the_time( get_option( 'date_format' ) ); ?></span> <i class="fa fa-folder-o"></i>
							<?php the_category(', '); ?>
						</p>

						<div class="excerpt">
							<?php the_excerpt(); ?>
						</div>

						<p>
							<a href="<?php the_permalink(); ?>" class="btn-green">Read More</a>
						</p>
					</div>

				<?php endwhile; ?>

				<?php
				the_posts_pagination( array(
					'prev_text' => '<span>&laquo;&nbsp;Prev</span>',
					'next_text' => '<span>Next&nbsp;&raquo;</span>',
					'before_page_number' => '<span></span>',
				) );
				?>

			</div>
		</section>
	</main>

</div>

<?php get_footer();