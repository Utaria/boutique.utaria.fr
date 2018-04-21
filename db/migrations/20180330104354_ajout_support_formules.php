<?php


use Phinx\Migration\AbstractMigration;

class AjoutSupportFormules extends AbstractMigration {

    public function change() {
        // Création de la table des formules
        $this->table("shop_formules")
            ->addColumn("name", "string")
            ->addColumn("price", "float")
            ->addColumn("reduction", "integer", ["default" => 0])
            ->addColumn("title_unlock", "string", ["null" => true])
            ->create();

        // Création de la table de création de formules
        $this->table("shop_formules_creations")
            ->addColumn("date", "timestamp", ["default" => "CURRENT_TIMESTAMP"])
            ->addColumn("player_id", "integer")
            ->addColumn("formule_id", "integer")
            ->create();

        // Création de la table de stockage des articles d'une formule
        $this->table("shop_formules_creations_articles", ["id" => false, "primary_key" => ["article_id", "formule_creation_id"]])
            ->addColumn("article_id", "integer")
            ->addColumn("formule_creation_id", "integer")
            ->addColumn("qty", "integer")
            ->create();

        // Mise à jour de la table des articles
        $articleTable = $this->table("shop_articles");

        $articleTable
            ->addColumn("formule_creation_id", "integer", ["after" => "article_id", "null" => true])
            ->changeColumn("article_id", "integer", ["null" => true])
            ->addIndex(['formule_creation_id'], ['unique' => true])
            ->addForeignKey(
                'formule_creation_id', 'shop_formules_creations', 'id',
                ['update' => 'NO ACTION', 'delete' => 'NO ACTION']
            )
            ->save();
    }

}
