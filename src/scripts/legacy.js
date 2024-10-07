/**
 * Legacy scripts for backward compatibility with old theme.
 * Mostly come from /legenda/js/etheme.js
 */

jQuery(function($) {

	// **********************************************************************//
	// ! Categories Accordion
	// **********************************************************************//

	var plusIcon = '+';
	var minusIcon = '&ndash;';
	if($('aside .woocommerce.widget_product_categories').length > 0) {
	    var etCats = $('.product-categories');
	    var openerHTML = '<div class="open-this">'+plusIcon+'</div>';

	    etCats.find('> li').has('.children').has('li').addClass('parent-level0');
	    etCats.find('li').has('.children').prepend(openerHTML);

	    if( $('.current-cat.parent-level0, .current-cat, .current-cat-parent').length > 0 ) {
	        etCats.find('.current-cat.parent-level0, .current-cat-parent').find('> .open-this').html(minusIcon).parent().addClass('opened').find('> ul.children').show();
	    }

	    etCats.find('.open-this').click(function() {
	        if($(this).parent().hasClass('opened')) {
	            $(this).html(plusIcon).parent().removeClass('opened').find('> ul, > div.nav-sublist-dropdown').slideUp(200);
	        }else {
	            $(this).html(minusIcon).parent().addClass('opened').find('> ul, > div.nav-sublist-dropdown').slideDown(200);
	        }
	    });
	}

	// **********************************************************************//
	// ! Tabs
	// **********************************************************************//

	var tabs = $('.tabs');
	$('.tabs > p > a').unwrap('p');

	var leftTabs = $('.left-bar, .right-bar');
	var newTitles;

	leftTabs.each(function(){
	    var currTab = $(this);
	    //currTab.find('> a.tab-title').each(function(){
	        newTitles = currTab.find('> a.tab-title').clone().removeClass('tab-title').addClass('tab-title-left');
	    //});

	    newTitles.first().addClass('opened');


	    var tabNewTitles = $('<div class="left-titles"></div>').prependTo(currTab);
	    tabNewTitles.html(newTitles);

	    currTab.find('.tab-content').css({
	        'minHeight' : tabNewTitles.height()
	    });
	});


	tabs.each(function(){
	    var currTab = $(this);

	    if(!currTab.hasClass('closed-tabs')) {
	        currTab.find('.tab-title').first().addClass('opened').next().show();
	    }

	    currTab.find('.tab-title, .tab-title-left').click(function(e){

	        e.preventDefault();

	        var tabId = $(this).attr('id');

	        if($(this).hasClass('opened')){
	            if(currTab.hasClass('accordion') || $(window).width() < 767){
	                $(this).removeClass('opened');
	                $('#content_'+tabId).hide();
	            }
	        }else{
	            currTab.find('.tab-title, .tab-title-left').each(function(){
	                var tabId = $(this).attr('id');
	                $(this).removeClass('opened');
	                $('#content_'+tabId).hide();
	            });


	            if(currTab.hasClass('accordion') || $(window).width() < 767){
	                $('#content_'+tabId).removeClass('tab-content').show();
	                setTimeout(function(){
	                    $('#content_'+tabId).addClass('tab-content').show(); // Fix it
	                },1);
	            } else {
	                $('#content_'+tabId).show();
	            }
	            $(this).addClass('opened');
	        }
	    });
	});


	// **********************************************************************//
	// ! Toggle elements
	// **********************************************************************//
	var plusIcon = '+';
	var minusIcon = '&ndash;';

	var etoggle = $('.toggle-block');
	var etoggleEl = etoggle.find('.toggle-element');


	//etoggleEl.first().addClass('opened').find('.open-this').html(minusIcon).parent().parent().find('.toggle-content').show();

	etoggleEl.find('.toggle-title').click(function(e) {
	    e.preventDefault();
	    if($(this).hasClass('opened')) {
	        $(this).removeClass('opened').find('.open-this').html(plusIcon).parent().parent().find('.toggle-content').slideUp(200);
	    }else {
	        if($(this).parent().hasClass('noMultiple')){
	            $(this).parent().find('.toggle-element').removeClass('opened').find('.open-this').html(plusIcon).parent().parent().find('.toggle-content').slideUp(200);
	        }
	        $(this).addClass('opened').find('.open-this').html(minusIcon).parent().parent().find('.toggle-content').slideDown(200);
	    }
	});
});
