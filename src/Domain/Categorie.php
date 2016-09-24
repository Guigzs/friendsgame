<?php

namespace picobeauf\Domain;

class Categorie
{
	private $id;
	private $nom;

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
}