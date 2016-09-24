<?php

namespace picobeauf\DAO;

use picobeauf\Domain\Defi;

class DefiDAO extends DAO
{
	private $categorieDAO;

	public function setCategorieDAO(CategorieDAO $categorieDAO) {
		$this->categorieDAO = $categorieDAO;
	}
	
	public function find($id) {
		$sql = "SELECT * FROM pico_defis WHERE defi_id = ?";
		$row = $this->getDb()->fetchAssoc($sql, array($id));

		if ($row)
			return $this->buildDomainObject($row);
		else
			throw new \Exception("Il n'y a pas de défi avec l'identifiant " . $id);
	}
	
	/**
     * Return a list of all defis for a categorie, sorted by date (most recent last).
     *
     * @param integer $categorieId The categorie id.
     *
     * @return array A list of all defis for the categorie.
     */
	public function findAllByCategorie($categorieId) {
		// The associated categorie is retrieved only once
		$categorie = $this->categorieDAO->find($categorieId);

		// art_id is not selected by the SQL query
		// The categorie won't be retrieved during domain objet construction
		$sql = "SELECT * FROM pico_defis WHERE defi_id = ? ORDER BY defi_date DESC";
		$result = $this->getDb()->fetchAll($sql, array($categorieId));

		// Convert query result to an array of domain objects
		$defis = array();
		foreach ($result as $row) {
			$defiId = $row['categorie_id'];
			$defi = $this->buildDomainObject($row);
			// The associated categorie is defined for the constructed defi
			$defi->setCategorie($categorie);
			$defis[$defiId] = $defi;
		}
		return $defis;
	}


	public function findAllModerated($modere) {
		$sql = "SELECT * FROM pico_defis WHERE defi_modere = ? ORDER BY defi_date DESC";
		$result = $this->getDb()->fetchAll($sql, array($modere));

		// Convert query result to an array of domain objects
		$defis = array();
		foreach ($result as $row) {
			$id = intval($row['defi_id']);
			$defis[$id] = $this->buildDomainObject($row);
		}
		return $defis;
	}
	
	public function findAllNotDone($effectue, $niveau) {
		$sql = "SELECT * FROM pico_defis WHERE defi_modere = 1 AND defi_effectue = ? AND defi_niveau = ?";
		$result = $this->getDb()->fetchAll($sql, array($effectue, $niveau));

		// Convert query result to an array of domain objects
		$defis = array();
		foreach ($result as $row) {
			$id = intval($row['defi_id']);
			$defis[$id] = $this->buildDomainObject($row);
		}
		return $defis;
	}
	
	public function save(Defi $defi) {
		if ($defi->getId()) {
			$defiData = array(
				'defi_contenu' => $defi->getContenu(),
				'defi_categorie' => $_POST['categorie'],
				'defi_modere' => 1,
				'defi_niveau' => $defi->getNiveau(),
			);
			// The comment has already been saved : update it
			$this->getDb()->update('pico_defis', $defiData, array('defi_id' => $defi->getId()));
		} else {
			$defiData = array(
				'defi_contenu' => $defi->getContenu(),
				'defi_date' => date('Y-m-d H:i:s', time()),
			);
			$this->getDb()->insert('pico_defis', $defiData);
		}
	}
	
	public function done(Defi $defi) {
		$defiData = array(
			'defi_effectue' => 0,
		);
			$this->getDb()->update('pico_defis', $defiData, array('defi_id' => $defi->getId()));
	}
	
	public function reinitialiserDefis() {
		$sql = "UPDATE pico_defis SET defi_effectue = 0";
		$result = $this->getDb()->exec($sql);
	}
	
	public function delete($id) {
		// Delete the article
		$this->getDb()->delete('pico_defis', array('defi_id' => $id));
	}

	/**
     * Creates an Defi object based on a DB row.
     *
     * @param array $row The DB row containing Defi data.
     * @return \MicroCMS\Domain\Defi
     */
	protected function buildDomainObject($row) {
		$defi = new Defi();
		$defi->setId($row['defi_id']);
		$defi->setContenu($row['defi_contenu']);
		$defi->setEffectue($row['defi_effectue']);
		$defi->setModere($row['defi_modere']);
		$defi->setNiveau($row['defi_niveau']);
		$defi->setDate($row['defi_date']);

		if (array_key_exists('defi_categorie', $row)) {
			// Find and set the associated categories
			$categorieId = $row['defi_categorie'];
			$categorie = $this->categorieDAO->find($categorieId);
			$defi->setCategorie($categorie);
		}
		return $defi;
	}
}