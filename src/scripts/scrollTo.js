jQuery(function($) {
	// get vertical offset of element by selector
	var getElOffset = function(selector) {
		var offset;
		if(selector === '#top') {
			return 0;
		} else {
			offset = $(selector).offset();
			if(typeof offset === 'undefined') return false;

			// offset_top = offset.top - $('header').outerHeight();
			offset_top = offset.top;

			if($('#wpadminbar').length) {
				offset_top -= $('#wpadminbar').outerHeight();
			}

			return offset_top;
		}
	},

	scrollToEl = function(selector) {
		var offset = getElOffset(selector);

		$('html, body').animate({
        	scrollTop: offset,
    	}, 400);
	};

	// bind scrolling links sitewide
	$('a[data-scrollto]').on('click', function(e) {
		var selector = $(this).data('scrollto');

		e.preventDefault();
		if($(selector).length > 0 || selector === '#top') {
			scrollToEl(selector);
		}
	});

	// on Store Landing Page, replace #store links by scrolling
	$('body.page-template-store-landing-page a[href="#store"]').each(function() {
		$(this).attr('href', '#').on('click', function(e) {
			e.preventDefault();
			scrollToEl('#store');
		});
	});


	/*
	// if URL contains scrolling hash, scroll there
	if(window.location.hash !== '' && window.location.hash.substring(0, 8) === '#scroll-') {
		var location = '#' + window.location.hash.substring(8);
		setTimeout(scrollToEl, 500, location);
	}
	*/
});
