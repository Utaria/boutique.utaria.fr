<?php
namespace App\Controller;

use App;
use App\Helper\SessionCart;
use Core\Controller\Controller;

class CommandeController extends Controller {

	public function create() {
		$cart = SessionCart::getInstance();

		// On doit être connecté pour pouvoir créer une commande.
		if (!$cart->isUserConnected()) {
			$this->redirect("paiement/login");
			return;
		}

		// On ne peut pas créer une commande avec un panier vide...
		if ($cart->isEmpty()) {
			$this->redirect("panier");
			return;
		}

		// On créé la commande en base de données.
        $uid = null;

		// Récupération d'un identifiant de commande unique
		while ($uid == null) {
		    $uid = $this->generateRandomString(8);

		    if (!empty($this->getTable()->findByUID($uid)))
		        $uid = null;
        }

        $this->getTable()->insert(array(
            "uid"       => $uid,
			"total"     => $cart->getTotal(),
			"promocode" => $cart->getPromoCode(),
			"player_id" => $cart->getUser()->id
		));

		$orderId = $this->getTable()->getLastInsertId();

		// On enregistre les produits liés à la commande en base de données.
        foreach ($cart->getArticles() as $article)
            App::getInstance()->getTable("CommandeArticle")->insert(array(
                "buyed_at"    => $article->price,
                "qty"         => $article->qty,
                "article_id"  => $article->id,
                "commande_id" => $orderId
            ));

		// On supprime le panier courant.
        $cart->destroy();
        $cart->setLastCommandeId($orderId);

		// On redirige l'utilisateur vers la page de paiement :)
		$this->redirect('paiement');
	}

	private function generateRandomString($length = 10) {
        return substr(str_shuffle(str_repeat($x='0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
    }


}