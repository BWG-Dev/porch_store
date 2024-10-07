<?php
/**
 * Header nav with image background wrapper
 */
?>

<div class="header-banner-wrap">
	<div class="header-inner">

	<?php if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) : ?>
		<?php get_template_part('partials/header-nav-solid'); ?>
	<?php else : ?>
		<?php get_template_part('partials/header-nav'); ?>
	<?php endif; ?>

		<?php
		if(is_home() || is_archive()):
			$page_id = get_post( get_option( 'page_for_posts' ) ); ?>
			<h1 class="title"><?php echo get_the_title($page_id); ?></h1>

		<?php else: ?>
			<h1 class="title"><?php the_title(); ?></h1>
		<?php endif; ?>
	</div>
</div>
