$(document).ready(function(){
	function getRandomInt(min, max) {
		min = Math.ceil(min);
		max = Math.floor(max);
		return Math.floor(Math.random() * (max - min)) + min;
	}
	var breakpoint = true;
	$("#de").click(function(e){
		e.preventDefault();
		if (!breakpoint) return false;
		$(this).html(getRandomInt(1, 6));
		$(this).addClass('valeur-de');
		breakpoint = false;
	});

	$('#reload').click(function() {
		$('#de').removeClass('valeur-de').html('Cliquer pour lancer le dé');
		breakpoint = true;
	});

	/*$('header #menu .burger').click(function(){
		if ($('header #menu ul li').is(':hidden')) {
			$('header #menu ul li').show();
			$(this).addClass('active-burger');
		} else {
			$('header #menu ul li').hide();
			$(this).removeClass('active-burger');
		}
	});

	$(window).resize(function() {
		if($(window).width() > 750) {
			$('header #menu ul li').removeAttr('style');
		};
	});*/
});
