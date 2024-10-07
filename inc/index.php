<?php

// Register the new order status
function register_estimate_order_status() {
	register_post_status('wc-estimate', array(
		'label'                     => _x('Estimate', 'Order status', 'textdomain'),
		'public'                    => true,
		'exclude_from_search'       => false,
		'show_in_admin_all_list'    => true,
		'show_in_admin_status_list' => true,
		'label_count'               => _n_noop('Estimate <span class="count">(%s)</span>', 'Estimate <span class="count">(%s)</span>', 'textdomain'),
	));

	register_post_status('wc-decline', array(
		'label'                     => _x('Declined Estimate', 'Order status', 'textdomain'),
		'public'                    => true,
		'exclude_from_search'       => false,
		'show_in_admin_all_list'    => true,
		'show_in_admin_status_list' => true,
		'label_count'               => _n_noop('Declined Estimate <span class="count">(%s)</span>', 'Declined Estimate <span class="count">(%s)</span>', 'textdomain'),
	));
}
add_action('init', 'register_estimate_order_status');

add_filter( 'wc_order_is_editable', 'bbloomer_custom_order_status_editable', 9999, 2 );

function bbloomer_custom_order_status_editable( $allow_edit, $order ) {
	if ( $order->get_status() === 'estimate' ) {
		$allow_edit = true;
	}
	return $allow_edit;
}

function add_custom_order_statuses($order_statuses) {
	$new_order_statuses = array();

	// Add the new status after 'processing'
	foreach ($order_statuses as $key => $status) {
		$new_order_statuses[$key] = $status;

		if ('wc-processing' === $key) {
			$new_order_statuses['wc-estimate'] = _x('Estimate', 'Order status', 'textdomain');
			$new_order_statuses['wc-decline'] = _x('Declined Estimate', 'Order status', 'textdomain');
		}
	}

	return $new_order_statuses;
}
add_filter('wc_order_statuses', 'add_custom_order_statuses');

// Add 'Estimate' status color in the orders list
function estimate_status_order_list_styles() {
	?>
	<style>
        .order-status.status-estimate {
            background: lightcoral; /* Light blue */
            color: white;
        }
	</style>

    <style>
        .order-status.status-decline {
            background: lightcoral; /* Light blue */
            color: white;
        }
    </style>
	<?php
}
add_action('admin_head', 'estimate_status_order_list_styles');

// Add 'Estimate' status to order list filters
function add_estimate_status_to_order_filters($statuses) {
	$statuses['wc-estimate'] = 'Estimate';
	$statuses['wc-decline'] = 'Declined Estimate';

	return $statuses;
}
add_filter('woocommerce_admin_order_statuses', 'add_estimate_status_to_order_filters');

// Add custom metabox to WooCommerce order edit page
function add_custom_product_metabox() {
	add_meta_box(
		'custom_product_metabox',
		__('Add Custom Product', 'textdomain'),
		'display_custom_product_metabox',
		'shop_order',
		'normal',
		'default'
	);
}
add_action('add_meta_boxes', 'add_custom_product_metabox');

function display_custom_product_metabox($post) {
    $order = wc_get_order($post->ID);
    $cont = 0;
	foreach ( $order->get_items() as $item_id => $item ) {
        if($cont == 0){
            $cont++;
            continue;
        }
        var_dump($item->get_product());
    }
	?>
	<div id="custom-product-metabox">
		<p>
			<label for="custom_product_name"><?php _e('Product Name:', 'textdomain'); ?></label><br>
			<input type="text" id="custom_product_name" name="custom_product_name" class="widefat" />
		</p>
		<p>
			<label for="custom_product_quantity"><?php _e('Quantity:', 'textdomain'); ?></label><br>
			<input type="number" id="custom_product_quantity" name="custom_product_quantity" class="widefat" min="1" value="1" />
		</p>
		<p>
			<label for="custom_product_price"><?php _e('Price:', 'textdomain'); ?></label><br>
			<input type="number" id="custom_product_price" name="custom_product_price" class="widefat" step="0.01" value="0.00" />
		</p>
		<p>
			<button type="button" class="button button-primary" id="add_custom_product_button"><?php _e('Add Product', 'textdomain'); ?></button>
		</p>
	</div>
	<?php
}

// Enqueue custom script for the metabox
function enqueue_custom_product_script() {
	global $post_type;

	if ('shop_order' == $post_type) {
		wp_enqueue_script('custom_product_script', get_stylesheet_directory_uri() . '/inc/main.js', array('jquery'), '1.0', true);

		// Pass nonce and AJAX URL to the script
		wp_localize_script('custom_product_script', 'customProductData', array(
			'ajax_url' => admin_url('admin-ajax.php'),
			'nonce'    => wp_create_nonce('add_custom_product_nonce')
		));
	}
}
add_action('admin_enqueue_scripts', 'enqueue_custom_product_script');

// Handle AJAX request to add custom product to order
function add_custom_product_to_order() {
	check_ajax_referer('add_custom_product_nonce', 'nonce');

	$order_id = intval($_POST['order_id']);
	$product_name = sanitize_text_field($_POST['product_name']);
	$product_quantity = intval($_POST['product_quantity']);
	$product_price = floatval($_POST['product_price']);

	if (!$order_id || !$product_name || !$product_quantity || !$product_price) {
		wp_send_json_error(__('Invalid product data.', 'textdomain'));
	}

	$order = wc_get_order($order_id);

	if (!$order) {
		wp_send_json_error(__('Invalid order ID.', 'textdomain'));
	}

	// Create a custom order item for the product
	$item = new WC_Order_Item_Product();
	$item->set_name($product_name);
	$item->set_quantity($product_quantity);
	$item->set_subtotal($product_price * $product_quantity);
	$item->set_total($product_price * $product_quantity);

	// Add the item to the order
	$order->add_item($item);

	// Save the order
	$order->save();
    $order->calculate_totals();

	wp_send_json_success(__('Product added to the order.', 'textdomain'));
}
add_action('wp_ajax_add_custom_product_to_order', 'add_custom_product_to_order');

// Add custom action to the order actions dropdown
function add_custom_order_action_button($actions, $order) {
    if( $order->get_status() === 'estimate' ){
		$actions['send_customer_estimate'] = __('Send Estimate', 'textdomain');
    }

	return $actions;
}
add_filter('woocommerce_order_actions', 'add_custom_order_action_button', 10, 2);

function handle_custom_order_action($order) {

	$mailer = WC()->mailer();
	$email = $mailer->emails['WC_Email_Custom_Notification'];
	$email->trigger($order->get_id());

	// Add a note to the order
	$order->add_order_note(__('Estimate email sent to customer.', 'textdomain'));
}
add_action('woocommerce_order_action_send_customer_estimate', 'handle_custom_order_action');

function add_custom_email_notification($email_classes) {

	require_once get_stylesheet_directory() . '/inc/estimate_email.php';

	$email_classes['WC_Email_Custom_Notification'] = new WC_Email_Custom_Notification();
	return $email_classes;
}
add_filter('woocommerce_email_classes', 'add_custom_email_notification');


// Add custom status 'estimate' to the list of statuses requiring payment
function custom_add_payable_status($statuses) {
	$statuses[] = 'estimate'; // Replace 'estimate' with your custom status slug if different
	return $statuses;
}
add_filter('woocommerce_valid_order_statuses_for_payment', 'custom_add_payable_status');
add_filter('woocommerce_order_is_paid_statuses', 'custom_add_payable_status');

add_action('gform_after_submission', 'custom_after_submission_action', 10, 2);

function custom_after_submission_action($entry, $form) {
	// Your custom action goes here

	// Example: Log the form submission
	$form_id = $form['id'];
	$estimate_id = $entry[6];

    if( $form['id'] == 63 && !empty( $estimate_id ) ) {
        $order = wc_get_order($estimate_id);
		$order->update_status( 'decline' );
		$order->add_order_note(__('The estimate was declined by the customer', 'textdomain') );
    }

}



