<?php $base_img_path = get_template_directory_uri() . '/dist/images/'; ?>
	<footer>
		<div class="container">
			<div class="cols">
				<div class="left">
					<div class="sidebar">
						<?php dynamic_sidebar( 'footer-left' ); ?>
					</div>
				</div>

				<div class="right">
					<div class="wrap">

						<?php dynamic_sidebar( 'footer-right' ); ?>
						<div class="social-links">
<a href="https://www.instagram.com/porch.store/" target="_blank" ><img src="<?php echo esc_url( $base_img_path . 'instagram.png' ); ?>" alt="instagram-logo"></a>
<a href="https://www.facebook.com/Porch.Store/" target="_blank"><img src="<?php echo esc_url( $base_img_path . 'facebook.png' ); ?>" alt="facebook-logo"></a>
<a href="https://www.pinterest.com/porchstore" target="_blank"><img src="<?php echo esc_url( $base_img_path . 'pinterest.png' ); ?>" alt="pinterest-logo"></a>
						</div>

						<p class="copyright">
							&copy <?php echo date( 'Y' ); ?> The Porch Store, Inc.
							<span class="separator">|</span>
							All Rights Reserved
							<span class="separator">|</span>
							<a href="/privacy-policy/">Privacy Policy</a>
						</p>
					</div>
				</div>
			</div>
		</div>
		<?php
		if ( is_front_page() ) :
			?>
			<div class="bottom-bar" style="background: rgba(0,0,0,.3); color: #ccc6be; padding: 8px; margin: 30px 0 -54px; text-align: center; font-size: 82%;"><a href="https://thomasgbennett.com?utm_source=client&utm_medium=referral&utm_campaign=porch_co" target="_blank">Website by Bennett Web Group</a></div>
		<?php endif; ?>
	</footer>

	<?php wp_footer(); ?>

	<script>
		jQuery('.search-display').on('click', function() {
			jQuery('#searchform').slideDown();
		});
	</script>

	<script>
		jQuery(document).ready(function() {
			/*jQuery('a.checkout-button.wc-forward').click(function(event) {
				event.preventDefault();
				if (jQuery('input#termstext').is(":checked")) {
					jQuery('a.checkout-button.wc-forward').unbind(event);
				} else if (jQuery(this).is(":not(:checked)")) {
					alert('Please accept terms');
				}
			});*/
			// add stickey class to header
			jQuery(window).scroll(function() {
				if (jQuery(this).scrollTop() > 40) {
					jQuery('header').addClass("sticky-header");
				} else {
					jQuery('header').removeClass("sticky-header");
				}
			});

		});
	</script>

	</body>

</html>
