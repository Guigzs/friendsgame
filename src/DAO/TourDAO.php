<?php

namespace picobeauf\DAO;

use picobeauf\Domain\Tour;

class TourDAO extends DAO
{
	public function findAll() {
		$sql = "SELECT * FROM pico_tours";
		$result = $this->getDb()->fetchAll($sql);

		// Convert query result to an array of domain objects
		$tours = array();
		foreach ($result as $row) {
			$nombre = $row['tour_id'];
			$tours[$nombre] = $this->buildDomainObject($row);
		}
		return $tours;
	}
	
	public function save($nombre, $record) {
		$tourData = array(
			'tour_nombre' => $nombre,
			'tour_record' => $record,
		);
		$this->getDb()->update('pico_tours', $tourData, array('tour_id' => 1));
	}
	
	public function reinitialiserTours() {
		$sql = "UPDATE pico_tours SET tour_nombre = 0";
		$result = $this->getDb()->exec($sql);
	}

	protected function buildDomainObject($row) {
		$tour = new Tour();
		$tour->setNombre($row['tour_nombre']);
		$tour->setRecord($row['tour_record']);
		return $tour;
	}
}