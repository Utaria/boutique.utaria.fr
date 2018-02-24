<?php


use Phinx\Seed\AbstractSeed;

class FillPromosTable extends AbstractSeed {

    public function run() {
        $promos = [];

        $now = date('Y-m-d H:i:s');

        $promos[] = ["code" => "20PERC", "value" => 20, "type" => "percent", "date" => $now, "expiration_date" => date('Y-m-d H:i:s', time() + 1728000)];
        $promos[] = ["code" => "70PERC", "value" => 70, "type" => "percent", "date" => $now, "expiration_date" => date('Y-m-d H:i:s', time() + 1728000), "min_price" => 50];
        $promos[] = ["code" => "15EUR", "value" => 15, "type" => "euro", "date" => $now, "expiration_date" => date('Y-m-d H:i:s', time() + 1728000), "min_price" => 30];
        $promos[] = ["code" => "5EUR", "value" => 5, "type" => "euro", "date" => $now, "expiration_date" => $now];

        $this->execute("DELETE FROM shop_promos");
        $this->execute("ALTER TABLE shop_promos AUTO_INCREMENT = 1");
        $this->insert("shop_promos", $promos);
    }

}
