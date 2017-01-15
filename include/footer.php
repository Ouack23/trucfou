<footer>
	
</footer>

<script type="text/javascript">
	$(document).ready(function() {
	    $("#connexion-link").click(function() {
	        $("#login-frame").fadeToggle('fast');
	    });

	    $("#login-frame").click(function(){
			$(this).fadeOut("fast");
		}).children().click(function(e) {
		 	e.stopPropagation();
		});
	});
</script>