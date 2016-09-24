$(document).ready(function () {

	/*$('#ajout_joueur').on('submit', function(e) {
		e.preventDefault();

		var $this = $(this);
		var prenom = encodeURIComponent($('#prenom').val());

		$.ajax({
			url: 'controleur/traitement.php',
			type: 'post',
			data: $this.serialize(),
			dataType: 'text',
			success: function (data) {
				if (data == 'Success') {
					$('<li style="color:#757575">' + prenom + '</li>').appendTo('.side #liste_joueurs');
					$('#prenom').val('');
				} else {
					$('#resultat').html('<p>Erreur</p>');
				}
			}
		});
	});*/

	$('.wrapper-jeu').on('click', '#jeu_jouer', function(e) {
		e.preventDefault();
		$.ajax({
			url: 'jeu/traitement',
			type: 'post',
			dataType: 'json',
			success: function (json) {
				$('#liste_joueurs').load('/picobeauf/web/jeu #liste_joueurs li');
				$('#tour-nombre').load('/picobeauf/web/jeu #tour-nombre');
				$('#tour-record').load('/picobeauf/web/jeu #tour-record');
				if (json['defi'] == null) {
					$('section#content #error').html('<div class="alert alert-danger">Y a plus de défis, je pense qu\'on est assez bourrés comme ça.</div>');
					$('.nom_joueur').html('');
					$('.nom_defi').html('');
				} else {
					$('.nom_joueur').html(json['joueur']);
					$('.nom_defi').html(json['defi']);
				}
			}
		});
	});

});
