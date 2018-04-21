<?php
namespace App\Table;

use Core\Table\Table;

class FormuleCreationArticleTable extends Table {

    protected $table = "shop_formules_creations_articles";

    public function for($formuleId) {
        if (is_null($formuleId)) return null;

        return $this->db->prepare("
			SELECT * FROM {$this->table}
			WHERE formule_creation_id = ?
		", [$formuleId], $this->getEntityClass());
    }

}