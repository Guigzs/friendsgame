<?php

namespace picobeauf\Domain;

class Joueur
{

	private $id;
	private $nom;
	private $tour;
	private $sexe;

	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function getNom() {
		return $this->nom;
	}

	public function setNom($nom) {
		$this->nom = $nom;
	}

	public function getTour() {
		return $this->tour;
	}

	public function setTour($tour) {
		$this->tour = $tour;
	}
	
	public function getSexe() {
		return $this->sexe;
	}

	public function setSexe($sexe) {
		$this->sexe = $sexe;
	}
}