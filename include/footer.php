<footer>
	
</footer>

<script type="text/javascript">
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

		// Sticky menu (fixed after some scroll)
		$('.nav').sticky({topSpacing:0, zIndex:999});

		// Hide content of specified boxes
		$('.hide-by-default').addClass('hidden');

		if (window.matchMedia('(max-width: 480px)').matches) {
			$('.hide-by-default-mobile').addClass('hidden');
		}

		$('.box-header').click(function() {
			$(this).siblings('.box-content').toggleClass('hidden');
			$(this).siblings('.box-content').slideToggle();
		});

	});
</script>