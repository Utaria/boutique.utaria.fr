<?php


use Phinx\Seed\AbstractSeed;

class FillShopTables extends AbstractSeed {

    public function run() {
        $articles = [];

        $articles[] = ["name" => "Booster", "type" => "booster", "price" => 1.5, "img" => "img/1.jpg"];
        $articles[] = ["name" => "Booster 2", "type" => "booster", "price" => 2, "img" => "img/1.jpg"];
        $articles[] = ["name" => "Booster 3", "type" => "booster", "price" => 5, "img" => "img/1.jpg"];
        $articles[] = ["name" => "Grade Utarien", "type" => "grade", "price" => 2, "img" => "img/1.jpg"];
        $articles[] = ["name" => "Grade Légende", "type" => "grade", "price" => 5, "img" => "img/1.jpg"];

        $this->execute("DELETE FROM shop_articles");
        $this->execute("ALTER TABLE shop_articles AUTO_INCREMENT = 1");
        $this->insert("shop_articles", $articles);
    }

}
