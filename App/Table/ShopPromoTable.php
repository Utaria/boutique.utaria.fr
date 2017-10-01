<?php
namespace App\Table;

use \Core\Table\Table;

class ShopPromoTable extends Table {

	protected $table = "shop_promos";


	public function findByCode($code) {
		return $this->db->prepare("
			SELECT * FROM {$this->table}
			WHERE code = ?
		", [$code], $this->getEntityClass(), true);
	}

}