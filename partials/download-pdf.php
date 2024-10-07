<section id="download-pdf">
	<div class="cols">
		<div class="left">
			<img
				src="<?php echo get_stylesheet_directory_uri(); ?>/dist/images/porch-book.png"
				<?php /*
				// don't use this, as the @2x image is just not sharp enough
				srcset="<?php echo get_stylesheet_directory_uri(); ?>/dist/images/porch-book.png 709w, <?php echo get_stylesheet_directory_uri(); ?>/dist/images/porch-book@2x.png 1418w"
				sizes="(min-width: 1420px) 680px, (min-width: 768px) calc(100vw - 470px), calc(100vw - 40px)"
				*/ ?>
			>
		</div>

		<div class="right">
			<form id="mailchimp-form">
				<h3><?php the_field('download_title', 'option'); ?></h3>
				<p class="disclaimer"><?php the_field('download_content', 'option'); ?></p>

				<div class="row">
			    	<input type="text" name="name" placeholder="Full Name" required>
				</div>

				<div class="row">
			    	<input type="email" name="email" placeholder="Email" required>
				</div>

				<div class="message"></div>

				<div class="row">
					<button type="submit" class="btn-green">Download the Guide</button>
				</div>
			</form>
		</div>
	</div>
</section>
