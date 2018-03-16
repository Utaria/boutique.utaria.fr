<?php
namespace App\Controller;

use App\Helper\Cart\SessionCart;
use Core\Controller\Controller;

class ArticleController extends Controller {

    protected $table_class = "ShopArticle";

    public function survie() {
        $articles = $this->getTable()->findByCategory("survie");
        $this->render("article.survie", compact('articles'));
    }

    public function addtocart($params) {
        $id = $params['id'];

        // Récupération de l'article et du panier
        $article = $this->getTable()->find($id);
        if (empty($article)) $this->redirect("/");

        $cart = SessionCart::getInstance();

        // Ajout au panier
        $added = $cart->addArticle($article);

        die (($added) ? 'success' : 'error');
    }

}