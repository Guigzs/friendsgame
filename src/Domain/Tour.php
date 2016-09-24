<?php

namespace picobeauf\Domain;

class Tour
{
	private $id;
	private $nombre;
	private $record;
	
	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
	}
	
	public function getNombre() {
		return $this->nombre;
	}

	public function setNombre($nombre) {
		$this->nombre = $nombre;
	}

	public function getRecord() {
		return $this->record;
	}

	public function setRecord($record) {
		$this->record = $record;
	}

}