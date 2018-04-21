<?php
namespace App\Entity;

use Core\Entity\Entity;

class CommandeArticleEntity extends Entity {

    private $article;

    public function getName() {
        return $this->getArticle()->name;
    }

    private function getArticle() {
        if (!is_null($this->article)) return $this->article;

        if (!is_null($this->article_id))
            return $this->article = \App::getInstance()->getTable("ShopArticle")->find($this->article_id);

        if (!is_null($this->formule_creation_id))
            return $this->article = \App::getInstance()->getTable("FormuleCreation")->find($this->formule_creation_id);

        return null;
    }

}