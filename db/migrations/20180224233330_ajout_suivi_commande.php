<?php


use Phinx\Migration\AbstractMigration;

class AjoutSuiviCommande extends AbstractMigration {

    public function up() {
        $this->execute("ALTER TABLE `shop_commandes`ADD COLUMN `status` ENUM('UNPAID','WAITING','CONFIRMATION','ERROR','PAID','DELIVERED') NOT NULL DEFAULT 'UNPAID' AFTER `player_id`");
        $this->execute("ALTER TABLE `shop_commandes`ADD COLUMN `payer_id` VARCHAR(60) NULL DEFAULT NULL AFTER `status`");
        $this->execute("ALTER TABLE `shop_commandes`ADD COLUMN `payer_mail` VARCHAR(100) NULL DEFAULT NULL AFTER `payer_id`");
        $this->execute("ALTER TABLE `shop_commandes`ADD COLUMN `transaction_id` VARCHAR(40) NULL DEFAULT NULL AFTER `payer_mail`");
        $this->execute("ALTER TABLE `shop_commandes`ADD COLUMN `uid` VARCHAR(8) NOT NULL AFTER `id`");
        $this->execute("ALTER TABLE `shop_commandes`ADD UNIQUE INDEX `uid` (`uid`);");
        $this->execute("ALTER TABLE `shop_commandes_articles`ADD COLUMN `qty` INT NOT NULL AFTER `buyed_at`");
    }

    public function down() {
        $this->execute("ALTER TABLE `shop_commandes` DROP COLUMN `status`");
        $this->execute("ALTER TABLE `shop_commandes` DROP COLUMN `payer_id`");
        $this->execute("ALTER TABLE `shop_commandes` DROP COLUMN `payer_mail`");
        $this->execute("ALTER TABLE `shop_commandes` DROP COLUMN `transaction_id`");
        $this->execute("ALTER TABLE `shop_commandes` DROP COLUMN `uid`, DROP INDEX `uid`");
        $this->execute("ALTER TABLE `shop_commandes_articles` DROP COLUMN `qty`");
    }

}
