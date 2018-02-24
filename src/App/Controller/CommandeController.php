<?php
namespace App\Controller;

use App;
use App\Helper\SessionCart;
use Core\Controller\Controller;

class CommandeController extends Controller {

	public function index() {
		$this->render("commande");
	}

	public function view($param) {
		$id   = intval($param["id"]);
		$cart = SessionCart::getInstance();

		// On doit être connecté pour pouvoir créer une commande.
		if (!$cart->isUserConnected()) {
			$this->redirect("paiement/login");
			return;
		}

		// On récupère la commande en base
		$order = $this->getTable()->find($id);

		// La commande doit être au joueur...
		if ($order->player_id != $cart->getUser()->id) {
			$this->redirect("commande");
			return;
		}

		var_dump($order);
	}

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
        $this->getTable()->insert(array(
			"total"     => $cart->getTotal(),
			"promocode" => $cart->getPromoCode(),
			"player_id" => $cart->getUser()->id
		));

		$orderId = $this->getTable()->getLastInsertId();

		// On supprime le panier courant.
        $cart->destroy();

		// On redirige l'utilisateur vers la page sa commande
		// (et son récapitulatif)
		$this->redirect('commande/' . $orderId);
	}

}