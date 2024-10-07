<?php
/**
 * Hero Image Page Template
 * Template Name: Hero Image
 */

get_header();

get_template_part( 'partials/header-nav-transparent' );
?>
<div class="main-wrap">
	<main>
		<?php
		if ( have_posts() ) :
			the_post();
			$thumbnail_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
			if ( empty( $thumbnail_url ) ) {
				$thumbnail_url = get_template_directory_uri() . '/dist/images/home-banner.jpg';
			}
			?>
		<section class="hero-image" style="background-image:url(<?php echo esc_url( $thumbnail_url ); ?>)">
			<div class="hero-image__content">
				<h1><?php the_title(); ?></h1>
				<a class="porch-cta" href="https://porchstore.nextsitehosting.com/#porchcategories" tabindex="0">Get Porch Store Products</a>
			</div>
		</section>
		<section id="page-content">
			<div class="container">
				<?php the_content(); ?>
			</div>
			<?php endif; ?>
		</section>
	</main>
</div>
<?php
get_footer();
