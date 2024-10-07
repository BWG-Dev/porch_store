<?php
/**
 * Common template
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
<div class="main-wrap <?php if ( $sidebar ) { echo 'has-sidebar has-sidebar-' . $sidebar;} ?>">

	<?php if ( $sidebar ) : ?>
		<aside>
			<?php dynamic_sidebar( $sidebar ); ?>
		</aside>
	<?php endif; ?>

	<main>
		<section>
			<div class="container">

				<?php
				while ( have_posts() ) :
					the_post();
					?>

					<?php if ( is_archive() || is_home() ) : ?>
						<h2 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<?php the_excerpt(); ?>
					<?php else : ?>
						<?php the_content(); ?>

					<?php endif; ?>

				<?php endwhile; ?>

				<?php
				the_posts_pagination(
					array(
						'prev_text'          => '<span class="screen-reader-text">Previous page</span>',
						'next_text'          => '<span class="screen-reader-text">Next page</span>',
						'before_page_number' => '<span class="meta-nav screen-reader-text">Page</span>',
					)
				);
				?>

			</div>
		</section>
	</main>

</div>

<?php
get_footer();
