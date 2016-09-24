<?php

namespace picobeauf\DAO;

use picobeauf\Domain\Joueur;

class JoueurDAO extends DAO
{
	
	public function findAll() {
		$sql = "SELECT * FROM pico_joueurs ORDER BY joueur_id";
		$result = $this->getDb()->fetchAll($sql);

		// Convert query result to an array of domain objects
		$joueurs = array();
		foreach ($result as $row) {
			$id = $row['joueur_id'];
			$joueurs[$id] = $this->buildDomainObject($row);
		}
		return $joueurs;
	}
	
	public function findAllByTour($tour) {
		$sql = "SELECT * FROM pico_joueurs WHERE joueur_tour = ?";
		$result = $this->getDb()->fetchAll($sql, array($tour));

		// Convert query result to an array of domain objects
		$joueursByTour = array();
		foreach ($result as $row) {
			$id = $row['joueur_id'];
			$joueursByTour[$id] = $this->buildDomainObject($row);
		}
		return $joueursByTour;
	}
	
	public function findAllBySexe($sexe) {
		$sql = "SELECT * FROM pico_joueurs WHERE joueur_sexe = ?";
		$result = $this->getDb()->fetchAll($sql, array($sexe));

		// Convert query result to an array of domain objects
		$joueursBySexe = array();
		foreach ($result as $row) {
			$id = $row['joueur_id'];
			$joueursBySexe[$id] = $this->buildDomainObject($row);
		}
		return $joueursBySexe;
	}
	
	public function save(Joueur $joueur) {
		if ($joueur->getId()) {
			$joueurData = array(
				'joueur_tour' => 1,
			);
			$this->getDb()->update('pico_joueurs', $joueurData, array('joueur_id' => $joueur->getId()));
		} else {
			$joueurData = array(
				'joueur_nom' => $joueur->getNom(),
				'joueur_tour' => 0,
				'joueur_sexe' => $joueur->getSexe(),
			);
			$this->getDb()->insert('pico_joueurs', $joueurData);
		}
	}
	
	public function reinitialiserJoueurs() {
		$sql = "TRUNCATE TABLE pico_joueurs";
		$this->getDb()->exec($sql);
	}
	
	public function reinitialiserToursJoueurs() {
		$sql = "UPDATE pico_joueurs SET joueur_tour = 0";
		$this->getDb()->exec($sql);
	}

	protected function buildDomainObject($row) {
		$joueur = new Joueur();
		$joueur->setId($row['joueur_id']);
		$joueur->setNom($row['joueur_nom']);
		$joueur->setTour($row['joueur_tour']);
		$joueur->setSexe(intval($row['joueur_sexe']));
		return $joueur;
	}
}