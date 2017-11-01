<?php
namespace App\Controller;

use Core\Controller\Controller;
use App\Helper\SessionCart;
use App;

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
			$this->redirect("panier");
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

		// Si le panier a déjà été sauvegardé tel quel,
		// on redirige l'utilisateur sur la page correspondante.
		if ($cart->wasSaved()) {
			$this->redirect("commande/" . $cart->getSavedOrder());
			return;
		}

		// On créé la commande en base de données.
		$this->getTable()->insert(array(
			"total"     => $cart->getTotal(),
			"promocode" => $cart->getPromoCode(),
			"player_id" => $cart->getUser()->id
		));

		$orderId = $this->getTable()->getLastInsertId();

		// Le panier en cours est maintenant sauvegardé en base.
		$cart->setSavedOrder($orderId);

		// On redirige l'utilisateur vers la page sa commande
		// (et son récapitulatif)
		$this->redirect('commande/' . $orderId);
	}

}
?>