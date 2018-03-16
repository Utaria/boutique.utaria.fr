<?php


use Phinx\Migration\AbstractMigration;

class ModificationArticles extends AbstractMigration {

    public function change() {
        $this->table("shop_articles")->removeColumn("img");
    }

}
