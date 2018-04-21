<?php
namespace App\Table;

use Core\Table\Table;

class CommandeArticleTable extends Table {

    protected $table = "shop_commandes_articles";

    public function findByCommandeId($commandeId) {
        if (is_null($commandeId)) return null;
        return $this->db->prepare(
            "SELECT * FROM {$this->table} WHERE commande_id = ?",
            [$commandeId], $this->getEntityClass()
        );
    }

}