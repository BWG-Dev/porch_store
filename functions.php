<?php
// ===============================================================================
//     Basic setup
//

include_once get_stylesheet_directory() . '/inc/index.php';

add_filter( 'wcfm_is_allow_email_verification', '__return_false' );

// Disable email verification for guest orders
function disable_guest_order_email_verification($order_id) {
    $order = wc_get_order($order_id);

    // Check if the order was placed by a guest (no user account)
    if ($order && $order->get_user_id() === 0) {
        update_post_meta($order_id, '_customer_user', 0); // Reset customer user ID to 0
    }
}
add_action('woocommerce_new_order', 'disable_guest_order_email_verification', 10, 1);


// version suffix for assets for force cache busting
function my_get_assets_version() {
	return '1.0.9';
};

// add mains styles and scripts
add_action('wp_enqueue_scripts', function() {
	wp_enqueue_style( 'my-styles', get_template_directory_uri() . '/dist/css/app.min.css', array(), my_get_assets_version() );

	// font awesome always loaded
	wp_enqueue_style( 'font-awesome' );

	// scripts will be localized, so first register, then localize, after that enqueue
	wp_register_script( 'my-scripts', get_template_directory_uri() . '/dist/js/app.min.js', ['jquery'], my_get_assets_version(), true );

	// call localization
	do_action('my_localize_scripts');

	// enqueue
	wp_enqueue_script('my-scripts');
});


function enqueue_theme_style_at_end() {
    // Replace 'theme-style' with the actual handle of the style.
    wp_enqueue_style('hero-style', get_stylesheet_directory_uri().'/style-extended.css', array());
}
add_action('wp_enqueue_scripts', 'enqueue_theme_style_at_end', 100); // Use a priority of 100 to enqueue it at the end.





// ===============================================================================
//     Mailchimp class
//

require_once('classes/MyMailchimp.php');
new MyMailchimp();



// ===============================================================================
//     Custom layout shortcodes
//

require_once('classes/MyShortcodes.php');
new MyShortcodes();


// ===============================================================================
//     Navigation menus
//

add_action( 'after_setup_theme', function() {
	register_nav_menu( 'main', 'Main Menu');
	register_nav_menu( 'footer', 'Footer Menu');
});

// output main menu
function my_main_menu() {
	$main_menu = wp_nav_menu([
		'theme_location' => 'main',
		'container' => '',
		'menu_class' => 'main-menu',
		'echo' => true,
        'items_wrap' => '<ul class="%2$s" id="%2$s">%3$s</ul>',
        'menu_id' => 'my-main-menu',
		'depth' => 2,
	]);
}

function my_mobile_menu() {
	wp_nav_menu([
		'theme_location' => 'main',
		'container' => '',
		'menu_id' => 'my-mobile-menu',
		'menu_class' => 'mobile-menu',
		'echo' => true,
		'items_wrap' => '<ul class="%2$s" id="%2$s">%3$s</ul>',
		'depth' => 2,
	]);
	?>
	<div class="mobile-products-link"><a href="#">Products</a></div>
	<?php
	wp_nav_menu(
		array(
			'menu'      => 'subNav',
			'container' => 'ul',
			'menu_id'   => 'mobile-nav-categories',
		)
	);
}

// output main menu
function my_footer_menu() {
	$main_menu = wp_nav_menu([
		'theme_location' => 'footer',
		'container' => '',
		'menu_class' => 'footer-menu',
		'echo' => true,
		'items_wrap' => '<ul class="%2$s">%3$s</ul>',
		'depth' => 1,
	]);
}

// ===============================================================================
//     Options screen
//

// options page
if( function_exists('acf_add_options_page') ) {
	acf_add_options_page(array(
		'page_title' 	=> 'PorchCo Settings',
		'menu_title'	=> 'PorchCo Settings',
		'menu_slug' 	=> 'theme-general-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
};

// ===============================================================================
//     Fallback ACF fields functions in case ACF isn't active
//
add_action('wp_head', function() {
	if(!class_exists('acf')) {
		die('Advanced Custom Fields plugin must be installed and activated for this theme to work.');
	}
});

// ===============================================================================
//     Define widget positions
//

add_action( 'widgets_init', function() {
	$defaults = [
		'description'   => '',
		'class'         => '',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widgettitle">',
		'after_title'   => '</h4>',
	];

	register_sidebar( array_merge($defaults, [
		'name'          => 'Footer - Left',
		'id'            => 'footer-left',
	]));

	register_sidebar( array_merge($defaults, [
		'name'          => 'Footer - Right',
		'id'            => 'footer-right',
	]));

	register_sidebar( array_merge($defaults, [
		'name'          => 'WooCommerce left sidebar',
		'id'            => 'woocommerce-left',
	]));

	register_sidebar(array_merge($defaults, [
	    'name' => 'Blog Left Sidebar',
	    'id' => 'blog-left',
	]));
});

// ===============================================================================
//     WooCommerce sidebar - we don't use WooCommerce setup as that would require a lot of custom templates so just output all WooCommerce into standard page template
//

function my_get_sidebar() {
	if(is_product_category() || is_product_tag()) {
		return 'woocommerce-left';
	}

	elseif(is_home() || is_archive() || is_singular('post')) {
		return 'blog-left';
	}
}

// ===============================================================================
//     WooCommerce filters and hooks
//

// wrap category listing item in wrapper
add_action( 'woocommerce_before_shop_loop_item', function() {
	echo '<div class="inner-content">';
}, 5 );

add_action( 'woocommerce_after_shop_loop_item', function() {
	echo '</div>';
}, 8 );


// ===============================================================================
//     Header adjustments
//

// override header background image
function my_get_header_background_override() {
	if(is_home() || is_archive()) {
		$page_id = get_post( get_option( 'page_for_posts' ) );
	} else {
		$page_id = get_queried_object_id();
	}

	// page featured image
	if ($img_url = get_the_post_thumbnail_url( $page_id, 'full' )) {
		$override = $img_url;

	// default image set in theme options
	} elseif ($default_img = get_field('default_header_background_image', 'option')) {
		$override = $default_img;
	}

	if(is_product_category( '444' )){
 	$override = 'https://porchco.com/wp-content/uploads/2019/05/banner.jpg';
	}

	if(isset($override) && $override) {
		return ' style="background-image: linear-gradient(to right, rgba(255, 255, 255, 1) 10%, rgba(255, 255, 255, .1) 60%), url(\'' . $override . '\'); "';
	}
}


// body class for pages with absolute positioned header
add_filter('body_class', function($classes) {
	if(is_front_page() || is_page_template('page-templates/store-landing-page.php')) {
		$classes[] = 'absolute-header';
	}
    return $classes;
});

// subnavigation for specific pages
add_filter('my_header_subnav', function($subnav = false) {
	if(is_page_template('page-templates/store-landing-page.php') || is_page_template('page-templates/category-page-layout.php') || is_woocommerce() ) {
		ob_start(); ?>
			<span class="label">Categories:</span>
			<?php
				wp_nav_menu( array(
					'menu' => 'subNav',
					'container' => 'ul'
				) );
			?>
		<?php $subnav = ob_get_clean();
	}
	return $subnav;
});


// ===============================================================================
//     Legacy code
//

// old theme shortcodes definitions
define('ETHEME_DOMAIN', 'legacy-theme');
require_once('legacy/theme-functions.php');
require_once('legacy/shortcodes.php');


// ===============================================================================
//     Redirect /store/ to /porch-store/
//

// backlink from empty cart
add_filter( 'woocommerce_return_to_shop_redirect', function() {
   return home_url('/porch-store/');
});

// redirect from the /store/ page itself
add_action( 'template_redirect', function() {
    if( is_shop()) {
        wp_redirect( home_url( '/porch-store/' ), 301 );
        die;
    }
} );

add_filter( 'get_the_excerpt', 'do_shortcode', 5 );

add_filter( 'woocommerce_add_to_cart_fragments', 'wc_refresh_mini_cart_count');
function wc_refresh_mini_cart_count($fragments){
    ob_start();
    ?>
	<a id="cart-buck" class="cart-link" href="<?php echo wc_get_cart_url(); ?>" title="<?php _e( 'View your shopping cart' ); ?>"><i class="fa fa-shopping-cart"></i><span><?php echo WC()->cart->get_cart_contents_count(); ?></span></a>
    <?php
        $fragments['#cart-buck'] = ob_get_clean();
    return $fragments;
}

add_action('wp_head', 'porchco_facebook_tracking', 99);
function porchco_facebook_tracking() {
?>

<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','//connect.facebook.net/en_US/fbevents.js');

fbq('init', '856413991141065');
fbq('track', "PageView");
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=856413991141065&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->
<?php
}

register_post_type('porch_builders', array(
    'labels' => array(
        'name'     => 'Porch Builders',
        'singular_name' => 'Builder',
        'add_new' => __( 'Add New' ),
        'add_new_item' => __( 'Add new builder' ),
        'view_item' => 'View builder',
        'edit_item' => 'Edit builder',
        'new_item' => __('New builder'),
        'view_item' => __('View builder'),
        'search_items' => __('Search builders'),
        'not_found' =>  __('No builders found'),
        'not_found_in_trash' => __('No builders found in Trash'),
    ),
    'public' => true,
    'show_in_menu' => true,
    'show_ui' => true,
    'menu_position' => 21,
    'exclude_from_search' => false,
    'rewrite' => array("slug" => "builder"),
    'query_var' => 'builder',
    'supports' => array('title', 'editor', 'thumbnail', 'page-attributes'),
));

add_action('wp_head', 'porchco_call_tracking', 98);
function porchco_call_tracking() {
?>

<script>
  gtag('config', 'AW-997210808/Go5XCObAoIUBELj1wNsD', {
    'phone_conversion_number': '6156622886'
  });
</script>

<?php
}


/**
 * Append phone number to Qualified installer search
 */

// google map infowindow
add_filter('gmw_info_window_content', function($output, $location, $args, $gmw) {
    // qualified installer form
    if(strpos(strtolower($gmw['title']), 'qualified installer') !== false) {
        if(isset($location->location_meta) && isset($location->location_meta['phone']) && $location->location_meta['phone']) {
            $output['address'] .= '<span class="address gmw-icon-phone">' . $location->location_meta['phone'] . '</span>';
        }
    }

    return $output;
}, 10, 4);

// results listing under the map
add_action('gmw_search_results_loop_item_end', function($gmw, $member) {
    if(strpos(strtolower($gmw['title']), 'qualified installer') !== false) {
        if(isset($member->location_meta) && isset($member->location_meta['phone']) && $member->location_meta['phone']): ?>

            <div class="bottom-wrapper" style="padding-top: 0; min-height: 0;">
                <div class="address-wrapper">
                    <span class="address"><i class="gmw-icon-phone"></i><?php echo $member->location_meta['phone']; ?></span>
                </div>
            </div>

        <?php endif;
    }
}, 1,2);

/**
 * Remove AK and HI from US state dropdown
 * TODO removes from both shipping and billing; only remove shipping

add_filter( 'woocommerce_states', function( $states ) {
    unset($states['US']['AK']);
    unset($states['US']['HI']);

    return $states;
});
*/

/*
* Only ship CONUS
*
* @param array $available_methods
*/
function ship_conus( $available_methods ) {
    global $woocommerce;
    $excluded_states = array( 'AK','HI','GU','PR' );

    if( in_array( $woocommerce->customer->get_shipping_state(), $excluded_states ) ) {
        // Empty the $available_methods array
        $available_methods = array();
    }

    return $available_methods;
}

add_filter( 'woocommerce_package_rates', 'ship_conus', 10 );

//Remove WooCommerce Tabs - this code removes all 3 tabs - to be more specific just remove actual unset lines

add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );

function woo_remove_product_tabs( $tabs ) {
    unset( $tabs['description'] );
    //unset( $tabs['reviews'] );
    //unset( $tabs['additional_information'] );

    return $tabs;
}

function customer_support_tab( $tabs ) {
	$tabs['test_tab'] = array(
		'title'     => __( 'Customer Support', 'woocommerce' ),
        'priority'     => 50,
        'callback'     => 'customer_support_tab_content'
    );

	return $tabs;
}

add_filter( 'woocommerce_product_tabs', 'customer_support_tab' );

function customer_support_tab_content() {
     echo '<h2>Customer Support</h2>';
     echo '<p>For information about shipping, our terms and conditions, and returns or refunds, visit our <a href="/customer-support" style="text-decoration:underline;">customer support page</a>.</p>';
}


function porchco_only_show_most_expensive_shipping_rate( $rates, $package ) {
	//print_r( $rates); print_r( $package); die;
	$most_expensive_method = '';
	$hermitage = 'no';
	$railingclassarr = array('railing-kit-10', 'railing-kit-8', 'railing-kit-6');
	/** Checked product have shipping class */
	foreach( WC()->cart->get_cart() as $cart_item ){
        if( in_array( $cart_item['data']->get_shipping_class(), $railingclassarr ) ){
			$hermitage = 'yes';
            break;
        }
	}

	if ( is_array( $rates ) ) :
		foreach ( $rates as $key => $rate ) :

			$items = $rate->get_meta_data();
			$shipppingmethod = $rate->method_id;
			/** Checked product have Hermitage product */
			if(( strstr($items['Items'], 'PorchCo Hermitage') || strstr($items['Items'], 'Nelson Custom post cap and sleeve kits') || strstr($items['Items'], 'Springer Custom Panel') ) ){
				$hermitage = 'yes';
			}

			if ( empty( $most_expensive_method ) || $rate->cost > $most_expensive_method->cost ) :
				$most_expensive_method = $rate;
			endif;

		endforeach;

	endif;

	if ( ! empty( $most_expensive_method ) && $hermitage == 'yes' ) :
		if( $most_expensive_method->cost > 350 ){
			$most_expensive_method->cost = 350;
		}
		/** bound rate max to 350 */
		return array( $most_expensive_method->id => $most_expensive_method );
	endif;



	return $rates;

}

add_action('woocommerce_review_order_before_submit', 'custom_checkout_checkbox', 10);
function custom_checkout_checkbox() {
	woocommerce_form_field('custom_checkbox_terms', array(
		'type'      => 'checkbox',
		'class'     => array('form-row custom-checkbox'),
		'label'     => __('<strong>Terms:</strong> <p>This cart may have been prepared for you by The Porch Store staff. If so, we have prepared this quote to the best of our ability based on the information we were given. Either way, it is ultimately the buyers responsibility to ensure that the sizes and quantities in this cart are appropriate for your project. All items (unless damaged upon arrival) are non-refundable. </p>
<p>Please have your builder, contractor, or inspector review your order prior to purchasing. You are responsible for making sure your product meets your local building codes. </p>
Please have your builder, contractor, or inspector review your order prior to purchasing. You are responsible for making sure your product meets your local building codes.
Please select this box to indicate that you understand and agree to these terms.'),
		'required'  => true,
	));
}

add_action('woocommerce_checkout_process', 'custom_checkbox_validation');
function custom_checkbox_validation() {
	if (!isset($_POST['custom_checkbox_terms'])) {
		wc_add_notice(__('You must agree to the terms to proceed'), 'error');
	}
}

//add_action( 'woocommerce_package_rates', 'porchco_only_show_most_expensive_shipping_rate', 10, 2 );

/** Added function to add term & condition text on cart pgae */
/*add_action('woocommerce_after_cart_table', 'porcho_cart_terms_text_func');
function porcho_cart_terms_text_func(){

	$content =	"<div class='porchco-terms'>";
	$content .=	"<p> Terms: </p>";
	$content .=	'<input type="checkbox" id="termstext" name="termstext" value="yes" style="float: left;    margin-top: 5px;    margin-right: 10px;">';
	$content .=	"<p>This cart may have been prepared for you by The Porch Store staff. If so, we have prepared this quote to the best of our ability based on the information we were given. Either way, it is ultimately the buyer's responsibility to ensure that the sizes and quantities in this cart are appropriate for your project. All items (unless damaged upon arrival) are non-refundable. </p>";

	$content .=	"<p>Please have your builder, contractor, or inspector review your order prior to purchasing. You are responsible for making sure your product meets your local building codes. </p>";

	$content .=	"<p>Please select this box to indicate that you understand and agree to these terms. </p>";
	$content .=	"</div>";
	echo $content;
}*/

function wc_increase_variation_threshold( $product ) {
    return 500;
}
add_filter( 'woocommerce_ajax_variation_threshold', 'wc_increase_variation_threshold', 10, 2 );


add_action( 'wp_head' , 'porcho_conditional_css_styles' );
function porcho_conditional_css_styles(){

	if ( ! is_product_category( array( 'double-doors', 'single-doors' ) ) ) {
	?>
	<style>
		#screen-door-widget {
			display: none;
		}
	</style>
	<?php
	}

}

if( class_exists('ACF')){
	include_once( get_template_directory() . '/includes/acf-divider/acf-divider.php' );
}


/**
 * 	Hide Product Weight & Dimensions Details in Tab on Product Page
 *
 */
add_filter( 'wc_product_enable_dimensions_display', '__return_false' );

//add_filter('woocommerce_package_rates', 'custom_flat_rate_product', 11, 2);
function custom_flat_rate_product($rates, $package) {

    global $woocommerce;
    $stock_door_categories = array(121, 682, 120);
    // get cart contents
    $cart_items = $woocommerce->cart->get_cart();

    $flag_product = false;
    $flag_category = false;

    // Check all cart items
    foreach ($cart_items as $key => $item) {
        if (in_array($item['product_id'], array(27921, 28742, 28743))) {
            $flag_product = true;
        }
        if (has_term($stock_door_categories, 'product_cat', $item['product_id']) && !in_array($item['product_id'], array(27921, 28742, 28743)) ) {
            $flag_category = true;
        }
    }

    if($flag_category && $flag_product){
        foreach( $rates as $key => $rate ){
            $rates[$key]->cost = $rates[$key]->cost - 40;
        }
    }

    return $rates;
}

/**
 * Init WC()->session if it's null.
 */
function porchco_init_wc( $address ) {
	if ( empty( WC()->session ) && class_exists( 'WC_Session_Handler' ) ) {
		WC()->session = new WC_Session_Handler();
		WC()->session->init();
	}

	return $address;
}
add_filter( 'woocommerce_customer_taxable_address', 'porchco_init_wc', 1 );

function porchco_ajax_get_cart_items() {
	if ( function_exists( 'WC' ) ) {
		wp_send_json_success(
			array(
				'count' => WC()->cart->get_cart_contents_count(),
			)
		);
	}

	wp_send_json_error(
		array(
			'msg' => 'WooCommerce is not enabled',
		)
	);
}
add_action( 'wp_ajax_porchco_get_cart_items', 'porchco_ajax_get_cart_items' );
add_action( 'wp_ajax_nopriv_porchco_get_cart_items', 'porchco_ajax_get_cart_items' );

// add_filter( 'prowc_empty_cart_button', 'porchco_prowc_empty_cart_button', 10, 2 );
function porchco_prowc_empty_cart_button( $action, $sub_action ) {
	if ( $sub_action === 'value_position_cart' ) {
		return 'woocommerce_cart_actions';
	}

	return $action;
}

add_action( 'init', 'porchco_fallback_empty_cart_position' );
function porchco_fallback_empty_cart_position() {
	if ( function_exists( 'prowc_empty_cart_button' ) ) {
		$empty_cart_obj      = prowc_empty_cart_button();
		$empty_cart_core_obj = $empty_cart_obj->core;
		remove_action( 'woocommerce_after_cart', array( $empty_cart_core_obj, 'output_empty_cart_form' ), get_option( 'prowc_empty_cart_position_priority', 10 ) );
		add_action( 'woocommerce_cart_actions', array( $empty_cart_core_obj, 'output_empty_cart_form' ), get_option( 'prowc_empty_cart_position_priority', 10 ) );
	}
}

function porchco_return_to_shop_redirect( $url ) {
	return home_url() . '#porchcategories';
}
add_filter( 'woocommerce_return_to_shop_redirect', 'porchco_return_to_shop_redirect' );

add_action( 'woocommerce_thankyou', 'store_order_id_in_session', 10, 1 );

function store_order_id_in_session( $order_id ) {
    if ( ! session_id() ) {
        session_start();
    }
    $_SESSION['order_id'] = $order_id;
}

add_filter( 'gform_pre_submission_61', 'add_order_id_to_gravity_form' );

function add_order_id_to_gravity_form( $form ) {

    if ( ! session_id() ) {
        session_start();
    }
    if ( isset( $_SESSION['order_id'] ) ) {
        $order_id = intval( $_SESSION['order_id'] );
    }
    if ( isset( $order_id ) && ! empty( $order_id ) ) {
        $_POST['input_9'] = $order_id;
    }
}


//add_action('gform_after_submission', 'custom_redirect_after_gravity_form', 10, 2);
function custom_redirect_after_gravity_form($entry, $form) {
	// Check the form ID to target the specific form
	if ($form['id'] == 3) {
		// Get the current product page URL
		$product_url = rgar($entry, '5');



		// Redirect to the new URL
		wp_safe_redirect(esc_url($product_url . '#tab-product-enquiry?utm_source=google&utm_medium=cpc&utm_campaign=name'));

		exit; // Always exit after a wp_redirect to prevent further execution
	}
}

add_filter( 'gform_confirmation', 'custom_confirmation', 10, 4 );
function custom_confirmation( $confirmation, $form, $entry, $ajax ) {
    global $product;
	if( $form['id'] == '3' ) {

		$product_url = rgar($entry, '9');
		$campaign_name = rgar($entry, '8');
		$campaign_medium = rgar($entry, '7');
		$campaign_source = rgar($entry, '6');
		$confirmation = array( 'redirect' => $product_url . '?utm_source='.$campaign_source.'&utm_medium='.$campaign_medium.'&utm_campaign='.$campaign_name.'&x_flag=true#tab-title-product-enquiry');

	}
	return $confirmation;
}

add_action('wp_footer', 'custom_alert_gravity_form_based_on_url');
function custom_alert_gravity_form_based_on_url() {
	?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Function to get URL parameters
            function getUrlParameter(name) {
                name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
                var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
                var results = regex.exec(location.search);
                return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
            }

            // Check if the specific GET parameter is present in the URL
            var myParam = getUrlParameter('x_flag'); // Replace 'your_param_name' with your actual GET parameter

            if (myParam) {
                var gravityFormContainer = document.querySelector('#gform_3'); // Change this selector if needed
                if (gravityFormContainer) {
                    gravityFormContainer.style.display = 'none';
                }

                var messageContainer = document.querySelector('#gform_wrapper_3'); // Create this element in your HTML
                if (messageContainer) {
                    messageContainer.innerHTML = '<p>Thanks for contacting us! We will get in touch with you shortly.</p>';
                    messageContainer.style.display = 'block';
                }

            }
        });
    </script>
	<?php
}

