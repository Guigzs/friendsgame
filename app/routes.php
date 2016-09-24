<?php
use Symfony\Component\HttpFoundation\Request;

use picobeauf\Domain\Joueur;
use picobeauf\Form\Type\JoueurType;
use picobeauf\Domain\Categorie;
use picobeauf\Form\Type\CategorieType;
use picobeauf\Domain\Defi;
use picobeauf\Form\Type\JeuType;
use picobeauf\Domain\Tour;
use picobeauf\Form\Type\DefiType;

// Home page
$app->match('/', function () use($app) {
	return $app['twig']->render('index.html.twig', array());
})->bind('home');

//game page
$app->match('/jeu',"picobeauf\Controller\GameController::gameAction")->bind('jeu');

// Challenge processing
$app->match('/jeu/traitement',"picobeauf\Controller\GameController::engineAction")->bind('jeu_traitement');

// Login page
$app->get('/connexion', "picobeauf\Controller\GameController::loginAction")->bind('connexion');

// Admin page
$app->match('/admin', "picobeauf\Controller\AdminController::getDefisAction")->bind('admin');
		
// Edit an existing challenge
$app->match('/admin/defi/{id}/edit', "picobeauf\Controller\AdminController::editDefiAction")->bind('admin_defi_edit');

// Delete an existing challenge
$app->get('/admin/defi/{id}/delete', "picobeauf\Controller\AdminController::deleteDefiAction")->bind('admin_defi_delete');

// Reset players
$app->get('/jeu/resetjoueurs', "picobeauf\Controller\GameController::resetJoueursAction")->bind('jeu_joueurs_reset');

// Reset challenges
$app->get('/jeu/resetdefis', "picobeauf\Controller\GameController::resetDefisAction")->bind('jeu_defis_reset');

// Reset rounds
$app->get('/jeu/resettours', "picobeauf\Controller\GameController::resetToursAction")->bind('jeu_tours_reset');

// Reset rounds
$app->get('/admin/categorie/{id}/delete', "picobeauf\Controller\AdminController::deleteCategorieAction")->bind('admin_categorie_delete');

// Add page
$app->match('/ajout', "picobeauf\Controller\GameController::addDefiAction")->bind('ajout');