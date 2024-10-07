/**
 * Mailchimp subscribtion form
 */

jQuery(function($) {
	var $form = $('#mailchimp-form'),
	$submit = $('button[type="submit"]', $form),
	$message = $('.message', $form);

	// submission
	$form.on('submit', function(e) {
		e.preventDefault();

		$submit.prop('disabled', true);

		var data = {
			'action': 'my_mailchimp_subscribe',
			'fields': {
				name: $('input[name="name"]', $form).val(),
				email: $('input[name="email"]', $form).val(),
			}
		};

		$.ajax({
			url : my_mailchimp_subscribe_params.ajaxurl,
			data : data,
			type : 'POST',
			beforeSend : function ( xhr ) {
				$submit.html('<span>~&nbsp;</span>Sending<span>&nbsp;~</span>');
				$message.hide().removeClass('success error');
			},
			success : function( data ){
				data = JSON.parse(data);

				if( data.status === 'success' ) {
					$submit.remove();
					$message.html('<p>' + data.msg + '</p>').addClass('success').slideDown();
				} else {
					$message.html('<p>' + data.msg + '</p>').addClass('error').slideDown();
					$submit.html('Download the Guide');
					$submit.prop('disabled', false);
				}
			}
		});

	});
});
