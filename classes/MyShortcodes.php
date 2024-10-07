<?php

/**
 * Shorcodes for layout blocks
 */

class MyShortcodes
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		// register all methods as shortcodes
		$methods = get_class_methods($this);
		$skip = ['__construct'];
		foreach($methods as $method) {
			if(in_array($method, $skip)) continue;

			add_shortcode(str_replace('_', '-', $method), [$this, $method]);
		}
	}

	/**
	 * Hero
	 */
	/*
	public function hero_block($atts, $content = "") {
		$atts = shortcode_atts( array(
			'bg' => false,
			'bg2x' => false,
		), $atts, 'hero-block' );

		ob_start(); ?>
			<div class="hero-block">
				<?php if($atts['bg']): ?>
					<div class="bg" style="background-image: url('<?php echo $atts['bg']; ?>');"></div>
				<?php endif; ?>

				<?php if($atts['bg2x']): ?>
					<div class="bg2x" style="background-image: url('<?php echo $atts['bg2x']; ?>');"></div>
				<?php endif; ?>

				<div class="container">
					<div class="content">
						<?php echo do_shortcode($content); ?>
					</div>
				</div>
			</div>
		<?php return ob_get_clean();
	}
	*/

	/**
	 * Mailchimp subscribe form
	 */
	public function mailchimp_download_form()
	{
		ob_start();
		get_template_part('partials/download-pdf');
		return ob_get_clean();
	}


	/**
	 * Actual Year
	 */
	public function actual_year()
	{
		return date('Y');
	}

	/**
	 * Space and content cleanup
	 */
	public function space($atts, $content) {
		if(!$content || !is_numeric($content)) $content = 30;
		ob_start(); ?>
		<div class="my-spacer" style="height: <?php echo $content; ?>px; max-height: <?php echo round($content / 2); ?>px;"></div>
		<?php
		return ob_get_clean();
	}


	/**
	 * Sidebar categories list
	 */
	public function my_sidebar_categories()
	{
		ob_start(); ?>
		<div class="shortcode-categories colorize-category colorize-border">
			<ul>
				<?php wp_list_categories([
					'hide_empty' => 0,
					'title_li' => false,
				]); ?>
			</ul>
		</div>
		<?php return ob_get_clean();
	}


}
