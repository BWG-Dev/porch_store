<div class="transparent-header">
<header>
	<section id="main-header" class="bp-temp">

		<?php $message = get_field( 'site_message', 'option' );

		if ( $message ) {
			?> 
				<div class="site-message">
					<?php echo $message; ?>
				</div>
			<?php
		}
		?>

		<div class="white-block">
			<div id="logo-block">
				<a href="<?php echo esc_url( home_url() ); ?>"><img class="store-logo" src="<?php echo esc_url( get_template_directory_uri() . '/dist/images/logo-store-transparent.svg' ); ?>"></a>
			</div>

			<div id="right-block">
				<div id="button-block">
					<nav class="desktop-menu-links btest"> <?php my_main_menu(); ?></nav>
				</div>

				<div id="search-block">
					<i class="fa fa-search search-display"></i>
					<div id="cart-block">
					<?php $cart_count = WC()->cart->get_cart_contents_count(); ?>
						<a id="cart-buck" class="cart-link" href="<?php echo wc_get_cart_url(); ?>" title="<?php _e( 'View your shopping cart' ); ?>"><span><?php echo $cart_count; ?></span></a>
					</div>

					<div id="toggle-block">
						<a href="#" class="mytoggle"><i class="fa fa-bars"></i></a>
					</div>
				</div>


			</div>
		</div>

		<form role="search" method="get" id="searchform" class="searchform" action="<?php echo home_url( '/' ); ?>" >
			<label class="screen-reader-text" for="s">Search for:</label>
			<input type="text" value="<?php get_search_query(); ?>" name="s" id="s" placeholder="Begin searching for your perfect porch..."/>
			<input type="hidden" value="product" name="post_type" id="post_type" />
			<button type="submit" id="searchsubmit"><i class="fa fa-search"></i></button>
		</form>

		<nav class="menu-links"><?php my_mobile_menu(); ?></nav> 
	</section>

</header>
</div>
