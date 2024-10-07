<?php
/**
 * Customer invoice email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-invoice.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 3.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Executes the e-mail header.
 *
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<?php /* translators: %s: Customer first name */ ?>
	<p><?php printf( esc_html__( 'Hi %s,', 'woocommerce' ), esc_html( $order->get_billing_first_name() ) ); ?></p>
    <p>Thank you for your interest in our products!</p>
    <p>We have prepared this quote to the best of our ability based on the information we were given. Ultimately it is the buyer's responsibility to ensure that the sizes and quantities in this quote are appropriate for your project. All items (unless damaged upon arrival) are non-refundable. You agree to these terms by completing the order.</p>
    <p>***PLEASE HAVE YOUR BUILDER / CONTRACTOR / INSPECTOR REVIEW YOUR ORDER PRIOR TO PURCHASING. YOU ARE RESPONSIBLE FOR MAKING SURE YOUR PRODUCTS MEET YOUR LOCAL BUILDING CODES.[This quote is valid for 30 days]</p>
<?php if ( $order->needs_payment() || $order->get_status() === 'estimate' ) { ?>

	<p>
		<?php
		printf(
			wp_kses(
			/* translators: %1$s Site title, %2$s Order pay link */
				__( 'An estimate has been created for you on %1$s. Your invoice is below, with a link to make payment when you’re ready: %2$s', 'woocommerce' ),
				array(
					'a' => array(
						'href' => array(),
					),
				)
			),
			esc_html( get_bloginfo( 'name', 'display' ) ),
			'<a href="' . esc_url( $order->get_checkout_payment_url() ) . '">' . esc_html__( 'Pay for this order', 'woocommerce' ) . '</a>'
		);
		?>
	</p>

    <!--<p>If you are seeing something wrong with this estimate send us a message so we can fix any problem, click on the button below to start an inquiry about this estimate</p>-->
    <p style="text-align: center">
        <a style="background: #6B8779; padding: 8px 20px; text-decoration: none;color: white" href="<?php echo $inquiry_link; ?>">Send Inquiry</a>
        <a style="background: #6B8779; padding: 8px 20px; text-decoration: none;color: white" href="<?php echo $decline_link; ?>">Decline Estimate</a>
    </p>

<?php } else { ?>
	<p>
		<?php
		/* translators: %s Order date */
		printf( esc_html__( 'Here are the details of your order placed on %s:', 'woocommerce' ), esc_html( wc_format_datetime( $order->get_date_created() ) ) );
		?>
	</p>
	<?php
}

/**
 * Hook for the woocommerce_email_order_details.
 *
 * @hooked WC_Emails::order_details() Shows the order details table.
 * @hooked WC_Structured_Data::generate_order_data() Generates structured data.
 * @hooked WC_Structured_Data::output_structured_data() Outputs structured data.
 * @since 2.5.0
 */
do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

/**
 * Hook for the woocommerce_email_order_meta.
 *
 * @hooked WC_Emails::order_meta() Shows order meta data.
 */
do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

/**
 * Hook for woocommerce_email_customer_details.
 *
 * @hooked WC_Emails::customer_details() Shows customer details
 * @hooked WC_Emails::email_address() Shows email address
 */
do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

/**
 * Executes the email footer.
 *
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action( 'woocommerce_email_footer', $email );
