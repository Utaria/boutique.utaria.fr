<?php
namespace App\Table;

use Core\Table\Table;

class ShopArticleTable extends Table {

	protected $table = "shop_articles";

	public function findByCategory($category) {
        if (is_null($category)) return null;

        return $this->db->prepare("
			SELECT * FROM {$this->table}
			WHERE type = ?
		", [$category], $this->getEntityClass());
    }

}