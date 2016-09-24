<?php

namespace picobeauf\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use picobeauf\Domain\Joueur;
use picobeauf\Form\Type\JoueurType;
use picobeauf\Domain\Defi;
use picobeauf\Form\Type\DefiType;
use picobeauf\Form\Type\JeuType;
use picobeauf\Domain\Tour;

class GameController {

	public function engineAction (Application $app) {
		$joueurList = $app['dao.joueur']->findAll();
		$joueurs = $app['dao.joueur']->findAllByTour(0);
		$tours = $app['dao.tour']->findAll();
		$breakpoint = false;
		$tour_nombre = intval($tours[1]->getNombre());
		$tour_record = intval($tours[1]->getRecord());
		$tour = $tours[1];
		$joueur = new Joueur();
		$defi = new Defi();
		
		if     ($tour_nombre < 2) {$niveau = 1;}
		elseif ($tour_nombre < 5) {$niveau = 2;}
		elseif ($tour_nombre < 8) {$niveau = 3;}
		else					  {$niveau = 4;}
		$defis = $app['dao.defi']->findAllNotDone(0, 5);
		
		if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY')) {
			if(empty($defis)){
				$app['session']->getFlashBag()->add('error', 'Il n\'y a plus de défi.');
				$breakpoint = true;
			} else {

				if(empty($joueurs)) {
					$tour_nombre ++;
					$app['dao.tour']->save($tour_nombre, $tour_record);
					$app['dao.joueur']->reinitialiserToursJoueurs();
					$joueurs = $app['dao.joueur']->findAllByTour(0);

					if ($tour_nombre > $tour_record) {
						$tour_record = $tour_nombre;
						$app['dao.tour']->save($tour_nombre, $tour_record);
					}
				}

				$joueur_ligne = array_rand($joueurs, 1);
				$joueur->setId(intval($joueurs[$joueur_ligne]->getId()));
				$joueur->setNom($joueurs[$joueur_ligne]->getNom());
				$joueur->setTour(1);
				$joueur->setSexe(intval($joueurs[$joueur_ligne]->getSexe()));
				$app['dao.joueur']->save($joueur);

				$defi_ligne = array_rand($defis, 1);
				$defi->setId(intval($defis[$defi_ligne]->getId()));
				$defi->setContenu($defis[$defi_ligne]->getContenu());
				$defi->setNiveau(intval($defis[$defi_ligne]->getNiveau()));
				$app['dao.defi']->done($defi);
				
				if ($joueurList > 1) {
					do {
						$joueurRand = array_rand($joueurList, 1);
					} while ($joueurRand == $joueur->getId());
				}
				$defiJoueurRand = str_ireplace('[all]', $joueurList[$joueurRand]->getNom(), $defi->getcontenu());
				$defi->setContenu($defiJoueurRand);
				
				if ($joueur->getSexe() == 0) {
					$joueursOpposes = $app['dao.joueur']->findAllBySexe(1);
					$joueurOppose = array_rand($joueursOpposes, 1);
					$oppose = str_ireplace('[opposite]', $joueursOpposes[$joueurOppose]->getNom(), $defi->getcontenu());
					$defi->setContenu($oppose);
				} else {
					$joueursOpposes = $app['dao.joueur']->findAllBySexe(0);
					$joueurOppose = array_rand($joueursOpposes, 1);
					$oppose = str_ireplace('[opposite]', $joueursOpposes[$joueurOppose]->getNom(), $defi->getcontenu());
					$defi->setContenu($oppose);
				}
			}
		}
		
		$responseData = array (
			'joueur' => $joueur->getNom(),
			'defi' => $defi->getContenu(),
			'tourNombre' => $tour->getNombre(),
			'tourRecord' => $tour->getRecord(),
			'allDefiDone' => $breakpoint,
			'test' => $joueurRand,
			'test2' => $joueur->getId(),
		);

		return $app->json($responseData);
	}
	
	public function gameAction (request $request, Application $app) {
		$joueurList = $app['dao.joueur']->findAll();
		$joueurs = $app['dao.joueur']->findAllByTour(0);
		$tours = $app['dao.tour']->findAll();
		$tour_nombre = intval($tours[1]->getNombre());
		$tour_record = intval($tours[1]->getRecord());
		$tour = $tours[1];
		$joueur = new Joueur();
		$defi = new Defi();
		if     ($tour_nombre < 2) {$niveau = 1;}
		elseif ($tour_nombre < 5) {$niveau = 2;}
		elseif ($tour_nombre < 8) {$niveau = 3;}
		else					  {$niveau = 4;}

		$defis = $app['dao.defi']->findAllNotDone(0, 5);

		$joueurFormView = null;
		if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY')) {
			// A user is fully authenticated : he can add joueurs
			$joueurAdd = new Joueur();
			$joueurForm = $app['form.factory']->create(new JoueurType(), $joueurAdd);
			$joueurForm->handleRequest($request);
			if ($joueurForm->isSubmitted() && $joueurForm->isValid()) {
				$app['dao.joueur']->save($joueurAdd);
				$app['session']->getFlashBag()->add('success', 'Le joueur a été ajouté.');
			}
			$joueurFormView = $joueurForm->createView();
		}

		return $app['twig']->render('jeu.html.twig', array(
			'joueursList' => $joueurList,
			'tours' => $tours,
			'joueur' => $joueur,
			'defi' => $defi,
			'tour' => $tour,
			'joueurForm' => $joueurFormView,
		));
	}
	
	public function addDefiAction (Request $request, Application $app) {
		$defiFormView = null;
		if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY')) {
			// A user is fully authenticated : he can add defis
			$defi = new Defi();
			$defiForm = $app['form.factory']->create(new DefiType(), $defi);
			$defiForm->handleRequest($request);
			if ($defiForm->isSubmitted() && $defiForm->isValid()) {
				$app['dao.defi']->save($defi);
				$app['session']->getFlashBag()->add('success', 'Défi ajouté');
			}
			$defiFormView = $defiForm->createView();
		}
		return $app['twig']->render('ajout.html.twig', array(
			'defiForm' => $defiFormView
		));
	}
	
	public function loginAction (Request $request, Application $app) {
		return $app['twig']->render('connexion.html.twig', array(
			'error'         => $app['security.last_error']($request),
			'last_username' => $app['session']->get('_security.last_username'),
			'password'		=> $app['session']->get('_security.password_hint')
		));
	}
	
	public function resetJoueursAction (Application $app) {
		$app['dao.joueur']->reinitialiserJoueurs();
		$app['session']->getFlashBag()->add('success', 'Joueurs réinitialisés');
		// Redirect to admin home page
		return $app->redirect($app['url_generator']->generate('jeu'));
	}
	
	public function resetDefisAction (Application $app) {
		$app['dao.defi']->reinitialiserDefis();
		$app['session']->getFlashBag()->add('success', 'Défis réinitialisés');
		// Redirect to admin home page
		return $app->redirect($app['url_generator']->generate('jeu'));
	}
	
	public function resetToursAction (Application $app) {
		$app['dao.tour']->reinitialiserTours();
		$app['session']->getFlashBag()->add('success', 'Tours réinitialisés');
		// Redirect to admin home page
		return $app->redirect($app['url_generator']->generate('jeu'));
	}
}