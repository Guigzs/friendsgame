<?php

namespace picobeauf\DAO;

use picobeauf\Domain\Categorie;

class CategorieDAO extends DAO
{

	/**
    * Return a list of all articles, sorted by date (most recent first).
    *
    * @return array A list of all articles.
    */
	public function findAll() {
		$sql = "SELECT * FROM pico_categories WHERE categorie_id > 0 ORDER BY categorie_id";
		$result = $this->getDb()->fetchAll($sql);

		// Convert query result to an array of domain objects
		$categories = array();
		foreach ($result as $row) {
			$id = $row['categorie_id'];
			$categories[$id] = $this->buildDomainObject($row);
		}
		return $categories;
	}
	
	/**
     * Saves a category into the database.
     *
     * @param \MicroCMS\Domain\Comment $comment The category to save
     */
	public function save(Categorie $categorie) {
		$categorieData = array(
			'categorie_nom' => $categorie->getNom(),
		);

		if ($categorie->getId()) {
			// The comment has already been saved : update it
			$categorie->getDb()->update('pico_categories', $defiData, array('categorie_id' => $categorie->getId()));
		} else {
			// The comment has never been saved : insert it
			$this->getDb()->insert('pico_categories', $categorieData);
		}
	}

	/**
     * Returns an article matching the supplied id.
     *
     * @param integer $id
     *
     * @return \MicroCMS\Domain\Article|throws an exception if no matching article is found
     */
	public function find($id) {
		$sql = "SELECT * FROM pico_categories WHERE categorie_id = ?";
		$row = $this->getDb()->fetchAssoc($sql, array($id));

		if ($row)
			return $this->buildDomainObject($row);
		else
			throw new \Exception("No category matching id " . $id);
	}
	
	public function delete($id) {
		$this->getDb()->delete('pico_categories', array('categorie_id' => $id));
	}
	/**
    * Creates an Article object based on a DB row.
    *
    * @param array $row The DB row containing Article data.
    * @return \picobeauf\Domain\Article
    */
	protected function buildDomainObject($row) {
		$categorie = new Categorie();
		$categorie->setId($row['categorie_id']);
		$categorie->setNom($row['categorie_nom']);
		return $categorie;
	}
}