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
		<h1>404</h1>
		<a class="" href="https://porchstore.nextsitehosting.com/#porchcategories" tabindex="0">Get Porch Store Products</a>
	</div>
</section>
<div class="main-wrap">
	<main>
		<section>
			<div class="container" style="padding-block: 100px;">
				<h1>Oops! Page not found</h1>
				<p>Sorry, but the page you are looking for is not found. Please, make sure you have typed the current URL. </p>
				<a href="<?php echo home_url(); ?>" class="button medium">Go to home page</a>
			</div>
		</section>
	</main>

</div>

<?php
get_footer();
