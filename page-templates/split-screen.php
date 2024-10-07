<?php
/**
 * Split Screen page template
 * Template Name: Split Screen Page
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<!-- Google tag (gtag.js) -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-M9S5G6Z7H2"></script>
	<script>
		window.dataLayer = window.dataLayer || [];

		function gtag() {
			dataLayer.push(arguments);
		}
		gtag('js', new Date());

		gtag('config', 'G-M9S5G6Z7H2');
	</script>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<title><?php wp_title(); ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">

	<!-- fonts -->
	<link href="https://fonts.googleapis.com/css?family=Lato:400,700|Nanum+Myeongjo:400,700|Playfair+Display:400,700" rel="stylesheet">

	<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/style.css?v=1.0.1" type="text/css" media="all" />

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php $base_img_path = get_template_directory_uri() . '/dist/images/'; ?>

<div class="main-wrap split-screen-wrap">
	<main>
		<div class="site-list">
			<section class="single-site porch-co">
				<div class="single-site__wrapper">
					<div class="single-site__logo">
						<img src="<?php echo esc_url( $base_img_path . 'logo.png' ); ?>" alt="">
					</div>
					<div class="single-site__description">
						<h2>The Porch Company</h2>
						<p>A Nashville area design/build construction company focusing on creating screen porches, open-air porches and outdoor structures that fit your lifestyle.</p>
						<a href="">Explore Construction Services</a>
					</div>
					<div class="single-site__images">
						<img src="<?php echo esc_url( $base_img_path . 'porch-screened-open-air-two-story-southern-cross-railing-gate-pvc-sapele-doors-pool-exterior-gate-abe.png' ); ?>" alt="">
					</div>
				</div>
				<a class="site-link" href="https://porchcompany.com/"></a>
			</section>
			<section class="single-site porch-store">
				<div class="single-site__wrapper">
					<div class="single-site__logo">
						<img src="<?php echo esc_url( $base_img_path . 'logo-store.png' ); ?>" alt="">
					</div>
					<div class="single-site__description">
						<h2>The Porch Store</h2>
						<p>An E-commerce store for unique porch products such as porch & deck railings, wooden screen doors, gates, swing beds and more!</p>
						<a href="">Browse Porch Store Products</a>
					</div>
					<div class="single-site__images">
						<img src="<?php echo esc_url( $base_img_path . 'double-cathedral-picket-railing-porchco-white-deck-lattice-pvc-sleeves-caps-stairs-dav.png' ); ?>" alt="">
						<img src="<?php echo esc_url( $base_img_path . 'porch-screened-double-doors-sapele-picket-wood-spindle-stained-porchco-bra.png' ); ?>" alt="">
						<img src="<?php echo esc_url( $base_img_path . 'gate-custom-painted-laminated-pvc-jambs-hardware-abbey-trading-porchco-viv.png' ); ?>" alt="">
					</div>
					<div class="single-site__verbiage">We ship anywhere in the U.S.!</div>
				</div>
				<a class="site-link" href="http://porchstore.com/"></a>
			</section>
		</div>
	</main>
</div>

<?php wp_footer(); ?>
</body>
</html>
