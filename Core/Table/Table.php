<?php
namespace Core\Table;

use core\Database\Query;

class Table {

	protected $table;
	protected $db;


	public function __construct($db) {
		$this->db = $db;

		// Détection automatique du nom de la table à utiliser
		if (is_null($this->table)) {
			$parts      = explode('\\', get_class($this));
			$class_name = end($parts);

			$this->table = strtolower(str_replace('Table', '', $class_name)) . 's';
		}
	}


	public function find($id) {
		return $this->db->prepare("
			SELECT * FROM {$this->table}
			WHERE id = ?
		", [$id], $this->getEntityClass(), true);
	}

	public function query($statement, $attributes = null, $one = false) {
		if ($statement instanceof Query)
			$statement = strval($statement);

		if ($attributes)
			return $this->db->prepare($statement, $attributes, $this->getEntityClass(), $one);
		else
			return $this->db->query($statement, $this->getEntityClass(), $one);
	}

	public function all() {
		return $this->db->query("
			SELECT * from {$this->table}
		", $this->getEntityClass());
	}


	public function update($fields, $conditions) {
		$sets       = array();
		$wheres     = array();
		$attributes = array();

		// Construction des updates
		foreach ($fields as $k => $v) {
			$sets[]       = "$k = ?";
			$attributes[] = $v;
		}
		// Construction des conditions
		foreach ($conditions as $k => $v) {
			$wheres[]     = "$k = ?";
			$attributes[] = $v;
		}

		return $this->db->execute("
			UPDATE {$this->table}
			SET " . implode(',', $sets) . "
			WHERE " . implode(' AND ', $wheres)
		, $attributes);
	}

	public function insert($datas) {
		$pi   = array_fill(0, count($datas), "?");
		$attr = array_values($datas);

		return $this->db->execute("
			INSERT INTO {$this->table} (" . implode(',', array_keys($datas)) . ")
			VALUES(" . implode(',', $pi) . ")"
		, $attr);
	}

	public function getLastInsertId() {
		return intval($this->db->getPDO()->lastInsertId());
	}

	protected function getEntityClass() {
		$class_name = get_class($this);
		$entity_class = str_replace('Table', 'Entity', $class_name);

		return $entity_class;
	}

}
?>