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
