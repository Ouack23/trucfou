//@prepros-prepend jquery-3.1.1.min.js
//@prepros-prepend sticky.js

$(document).ready(function() {

	// Login frame
	$('.connexion-link').click(function() {
	    $('#login-frame').fadeToggle('fast');
	});

	$('#login-frame').click(function(){
		$(this).fadeOut('fast');
	}).children().click(function(e) {
	 	e.stopPropagation();
	});
	
	// Details frame
	$('.details-link').click(function() {
	    $('#details-frame').fadeToggle('fast');
	});
	
	$('#details-frame').click(function(){
		$(this).fadeOut('fast');
	}).children().click(function(e) {
	 	e.stopPropagation();
	});

	// Sticky menu (fixed after some scroll)
	$('.nav').sticky({topSpacing:0, zIndex:999});

	// Hide content of specified boxes
	$('.hide-by-default').addClass('box-hidden');

	if (window.matchMedia('(max-width: 480px)').matches) {
		$('.hide-by-default-mobile').addClass('box-hidden');
	}

	$('.box-hidden').children('.box-content').slideUp();

	$('.box-header').click(function() {
		$(this).parent('.box').toggleClass('box-hidden');
		$(this).siblings('.box-content').slideToggle();
	});

});