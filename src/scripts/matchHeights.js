/**
 * Match height of various elements using http://brm.io/jquery-match-height/
 */
jQuery(function($) {
	// store categories on /porch-store/ landing page
	$('.mw_store_categories .wpb_wrapper p:first-of-type').matchHeight();

	// woocommerce product listing
	$('.woocommerce ul.products li.product .inner-content').matchHeight();
});
