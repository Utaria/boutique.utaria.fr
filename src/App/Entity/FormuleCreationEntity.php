<?php
namespace App\Entity;

class FormuleCreationEntity extends ShopArticleEntity {

    public function getName() {
        $formule = \App::getInstance()->getTable("Formule")->find($this->formule_id);
        return "Formule " . strtoupper($formule->name);
    }

    public function getType() {
        return "Formule";
    }

    public function getSingle() {
        return true;
    }

    public function getSubArticles() {
        $articles = \App::getInstance()->getTable("ShopArticle")->all();
        $formuleArts = \App::getInstance()->getTable("FormuleCreationArticle")->for($this->id);
        $subArticles = array();

        foreach ($formuleArts as $formuleArt) {
            $artId = $formuleArt->article_id;
            $qty = $formuleArt->qty;

            foreach ($articles as $article) {
                if ($article->id == $artId) {
                    $subArticles[] = (object) array(
                        "article" => $article,
                        "qty" => $qty
                    );

                    break;
                }
            }
        }

        return $subArticles;
    }

    public function getPrice() {
        $sub = $this->getSubArticles();
        $total = 0;

        foreach ($sub as $subArt)
            $total += $subArt->article->price * $subArt->qty;

        // Application de la réduction
        $formule = \App::getInstance()->getTable("Formule")->find($this->formule_id);

        if ($formule->reduction)
            $total -= $total * ($formule->reduction / 100);

        return $total;
    }

}