<?php
namespace App\Controller;

use Core\Controller\Controller;
use App\Helper\SessionCart;
use App\Helper\API;
use App;

class PaiementController extends Controller {

	protected $useTable = false;


	public function login() {
		$error = null;
		$cart = SessionCart::getInstance();

		if ($cart->isUserConnected())
			$this->redirect("commande/create");

		$errors     = array();
		$playername = null;

		if (!empty($_POST)) {
			$playername = $_POST["playername"];
			$password   = sha1($_POST["password"]);

			$authObj = API::get("player.checkauth", array(
				"name"     => $playername,
				"password" => $password
			), true);

			if ($authObj->auth) {
				$cart->logUser($authObj->player_id);
				$this->redirect("commande/create");
			} else {
				$passwd = $authObj->error_msg == "wrong_password";
				$prefix = "<i class='fa fa-arrow-right'></i>";

				if ($passwd)
					$errors["password"] = $prefix . "Mot de passe incorrect.";
				else {
					$playername = null;
					$errors["playername"] = $prefix . "Pseudo inconnu.";
				}
			}
		}

		$this->render("paiement.login", compact("playername", "errors"));
	}

}