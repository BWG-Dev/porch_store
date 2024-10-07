<?php

class WC_Email_Custom_Notification extends \WC_Email {

	public function __construct() {
		// Set email ID and other settings
		$this->id = 'por_estimate';
		$this->title = __('Customer Estimate', 'textdomain');
		$this->description = __('This email is trigger when an estimate is sent to the customer.', 'textdomain');
		$this->heading = __('Send Estimate', 'textdomain');
		$this->subject = __('You have a new estimate', 'textdomain');

		// Template settings
		$this->template_base = get_stylesheet_directory() . '/inc/emails/';
		$this->template_html = 'estimate.php';
		$this->template_plain = 'emails/plain/custom-notification.php';

		// Trigger the email
		add_action('woocommerce_order_action_send_customer_estimate', array($this, 'trigger'), 10, 1);

		// Call parent constructor
		parent::__construct();
	}

	public function trigger($order_id) {
		if (!$order_id) {
			return;
		}

		$this->object = wc_get_order($order_id);

		// Add data to email
		$this->find[] = '{order_number}';
		$this->replace[] = $this->object->get_order_number();

		// Send the email
		$this->send($this->object->get_billing_email(), $this->get_subject(), $this->get_content(), $this->get_headers(),$this->get_attachments());
	}

	public function get_payment_link() {
		$pay_url = $this->object->get_checkout_payment_url(); // Generate payment URL
		return esc_url($pay_url);
	}

	public function get_content() {
		ob_start();

		$inquiry_link = site_url() . '/send-estimate-inquiry/?order_id=' . $this->object->get_id() . '&customer_email=' . $this->object->get_billing_email();
		$decline_link = site_url() . '/decline-estimate/?order_id=' . $this->object->get_id() . '&customer_email=' . $this->object->get_billing_email();

		wc_get_template($this->template_html, array('order' => $this->object, 'inquiry_link' => $inquiry_link, 'decline_link' => $decline_link ), '', $this->template_base);
		return ob_get_clean();
	}
}
