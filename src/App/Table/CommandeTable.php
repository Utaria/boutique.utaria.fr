<?php
namespace App\Table;

use Core\Table\Table;

class CommandeTable extends Table {

	protected $table = "shop_commandes";

    public function findByUserId($playerId) {
        if (is_null($playerId)) return null;

        return $this->db->prepare("
			SELECT * FROM {$this->table}
			WHERE player_id = ?
		", [$playerId], $this->getEntityClass());
    }

}