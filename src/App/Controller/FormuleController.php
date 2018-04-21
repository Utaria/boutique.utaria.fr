<?php
namespace App\Controller;

use App\Helper\Cart\SessionCart;
use Core\Controller\Controller;

class FormuleController extends Controller {

    public function index() {
        $formules = $this->getTable()->all();
        $this->render("formules", compact("formules"));
    }

    public function creation() {
        $cart = SessionCart::getInstance();

        // On doit être connecté pour pouvoir créer une formule.
        if (!$cart->isUserConnected())
            $this->redirect("paiement/login/formule/creation");

        $articles = \App::getInstance()->getTable("ShopArticle")->all();
        $this->render("formule.creation", compact('articles'));
    }

    public function addtocart() {
        $cart = SessionCart::getInstance();
        $data = $_POST;

        // Vérifications avant procédure
        if (!$cart->isUserConnected())
            $this->redirect("paiement/login/formule/creation");
        if (empty($data))
            $this->redirect("formule/creation");

        // Récupération des articles concernés et détermination du prix total de la création
        $articles = \App::getInstance()->getTable("ShopArticle")->all();
        $mesArticles = array();

        $total = 0;

        foreach ($data as $item => $qty) {
            $articleId = intval(str_replace("article_", "", $item));

            foreach ($articles as $article) {
                if (intval($article->id) == $articleId) {
                    $total += $article->price * $qty;
                    $mesArticles[$article->id] = $qty;
                    break;
                }
            }
        }

        // Détermination de la formule choisie dans la création
        $formules = $this->getTable()->all();
        $maFormule = null;

        foreach ($formules as $formule)
            if ($formule->price <= $total)
                $maFormule = $formule;

        if (is_null($maFormule) || empty($mesArticles))
            $this->redirect("formule/creation");

        // Insertion de la création en base
        $creationTable = \App::getInstance()->getTable("FormuleCreation");

        $creationTable->insert(array("player_id" => $cart->getUserId(), "formule_id" => $maFormule->id));
        $insertId = $creationTable->getLastInsertId();

        // Insertion des articles liés en base
        foreach($mesArticles as $unArticle => $uneQty)
            \App::getInstance()->getTable("FormuleCreationArticle")->insert(array(
                "article_id" => $unArticle,
                "formule_creation_id" => $insertId,
                "qty" => $uneQty
            ));

        // Insertion de la création dans le panier
        $cart->addArticle($creationTable->find($insertId));

        // Redirection vers le panier
        $this->redirect("panier");
    }

}