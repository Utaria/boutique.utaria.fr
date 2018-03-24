<?php


use Phinx\Migration\AbstractMigration;

class AjoutPromoParJoueur extends AbstractMigration {

    public function change() {
        $this->table("shop_promos")->addColumn("usable_by", "integer", ["null" => true]);
    }

}
