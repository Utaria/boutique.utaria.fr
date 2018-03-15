<?php
namespace App\Controller;

use Core\Controller\Controller;

class ArticleController extends Controller {

    protected $table_class = "ShopArticle";

    public function survie() {
        $articles = $this->getTable()->all();
        $this->render("article.survie", compact('articles'));
    }

}