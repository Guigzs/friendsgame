<?php

namespace picobeauf\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use picobeauf\Domain\Defi;
use picobeauf\Form\Type\CategorieType;
use picobeauf\Domain\Categorie;

class AdminController {

	public function getDefisAction (Request $request, Application $app) {
		$defisModerated = $app['dao.defi']->findAllModerated(0);
		$defisNotModerated = $app['dao.defi']->findAllModerated(1);
		$categories = $app['dao.categorie']->findAll();
		$categorieFormView = null;

		if ($app['security.authorization_checker']->isGranted('ROLE_ADMIN')) {
			// A user is fully authenticated : he can add joueurs
			$categorie = new Categorie();
			$categorieForm = $app['form.factory']->create(new CategorieType(), $categorie);
			$categorieForm->handleRequest($request);
			if ($categorieForm->isSubmitted() && $categorieForm->isValid()) {
				$app['dao.categorie']->save($categorie);
				$app['session']->getFlashBag()->add('success', 'Bravo ma grosse, ta catégorie a été ajoutée. Pas trop dur ?');
			}
			$categorieFormView = $categorieForm->createView();
		}
		return $app['twig']->render('admin.html.twig', array(
			'categories' => $categories,
			'categorieForm' => $categorieFormView,
			'defisModerated' => $defisModerated,
			'defisNotModerated' => $defisNotModerated
		));
	}
	
	public function editDefiAction ($id, Request $request, Application $app) {
		$defi = $app['dao.defi']->find($id);
		$defiForm = $app['form.factory']->create(new DefiType(), $defi);
		$defiForm->handleRequest($request);
		$categories = $app['dao.categorie']->findAll();
		if ($defiForm->isSubmitted() && $defiForm->isValid()) {
			$app['dao.defi']->save($defi);
			$app['session']->getFlashBag()->add('success', 'Défi édité');
		}

		return $app['twig']->render('defi_form.html.twig', array(
			'defiForm' => $defiForm->createView(),
			'categories' => $categories,
		));
	}
	
	public function deleteDefiAction ($id, Request $request, Application $app) {
		$app['dao.defi']->delete($id);
		$app['session']->getFlashBag()->add('success', 'Défi supprimé');
		// Redirect to admin home page
		return $app->redirect($app['url_generator']->generate('admin'));
	}
		
	public function deleteCategorieAction ($id, Application $app) {
		$app['dao.categorie']->delete($id);
		$app['session']->getFlashBag()->add('success', 'Categorie supprimée');
		// Redirect to admin home page
		return $app->redirect($app['url_generator']->generate('admin'));
	}
}