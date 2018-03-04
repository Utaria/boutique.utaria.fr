<?php
namespace App\Table;

use Core\Table\Table;

class CommandeArticleTable extends Table {

    protected $table = "shop_commandes_articles";

    public function findByCommandeId($commandeId, $fullDetails = false) {
        if (is_null($commandeId)) return null;

        $joins = "";

        if ($fullDetails)
            $joins = "JOIN shop_articles ON shop_articles.id = {$this->table}.article_id";

        $req = "SELECT * FROM {$this->table} $joins WHERE commande_id = ?";

        return $this->db->prepare($req, [$commandeId], $this->getEntityClass());
    }

}