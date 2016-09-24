<?php

namespace picobeauf\Domain;

class Defi
{

	private $id;
	private $contenu;
	private $categorie;
	private $effectue;
	private $modere;
	private $niveau;
	private $date;

	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function getContenu() {
		return $this->contenu;
	}

	public function setContenu($contenu) {
		$this->contenu = $contenu;
	}
	
	public function getCategorie() {
		return $this->categorie;
	}

	public function setCategorie(Categorie $categorie) {
		$this->categorie = $categorie;
	}

	public function getEffectue() {
		return $this->effectue;
	}

	public function setEffectue($effectue) {
		$this->effectue = $effectue;
	}

	public function getModere() {
		return $this->modere;
	}

	public function setModere($modere) {
		$this->modere = $modere;
	}

	public function getNiveau() {
		return $this->niveau;
	}

	public function setNiveau($niveau) {
		$this->niveau = $niveau;
	}
	
	public function getDate() {
		return $this->date;
	}

	public function setDate($date) {
		$this->date = $date;
	}
}