jQuery(document).ready(function($) {
    $('#add_custom_product_button').on('click', function() {
        var productName = $('#custom_product_name').val();
        var productQuantity = $('#custom_product_quantity').val();
        var productPrice = $('#custom_product_price').val();
        var orderId = $('input#post_ID').val();

        if (productName && productQuantity && productPrice) {
            $.ajax({
                url: customProductData.ajax_url,
                method: 'POST',
                data: {
                    action: 'add_custom_product_to_order',
                    nonce: customProductData.nonce,
                    order_id: orderId,
                    product_name: productName,
                    product_quantity: productQuantity,
                    product_price: productPrice
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.data);
                    }
                }
            });
        } else {
            alert('Please fill in all fields.');
        }
    });
});
