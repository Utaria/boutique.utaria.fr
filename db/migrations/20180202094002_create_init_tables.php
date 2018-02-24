<?php


use Phinx\Migration\AbstractMigration;

class CreateInitTables extends AbstractMigration {

    public function change() {
        $this->table("shop_articles")
            ->addColumn("name", "string")
            ->addColumn("type", "string", ["limit" => 60])
            ->addColumn("price", "float")
            ->addColumn("img", "string")
            ->addColumn("single", "boolean", ["default" => 0])
            ->create();

        $this->table("shop_commandes")
            ->addColumn("date", "timestamp", ["default" => "CURRENT_TIMESTAMP"])
            ->addColumn("total", "decimal", ["null" => true])
            ->addColumn("promocode", "string", ["null" => true, "limit" => 60])
            ->addColumn("player_id", "integer")
            ->create();

        $this->table("shop_commandes_articles")
            ->addColumn("buyed_at", "float")
            ->addColumn("article_id", "integer")
            ->addColumn("commande_id", "integer")
            ->addForeignKey("article_id", "shop_articles", "id", ["delete" => "CASCADE", "update" => "NO_ACTION"])
            ->addForeignKey("commande_id", "shop_commandes", "id", ["delete" => "CASCADE", "update" => "NO_ACTION"])
            ->create();

        $this->table("shop_promos")
            ->addColumn("code", "string")
            ->addColumn("value", "integer")
            ->addColumn("type", "string")
            ->addColumn("date", "datetime")
            ->addColumn("expiration_date", "datetime", ["null" => true])
            ->addColumn("min_price", "integer", ["default" => 0])
            ->create();
    }

}
