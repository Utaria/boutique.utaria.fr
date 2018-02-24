<?php
namespace App\Controller;

use App;
use App\Helper\API;
use App\Helper\SessionCart;
use Core\Controller\Controller;

class PaiementController extends Controller {

	protected $useTable = false;


	public function login() {
		$error = null;
		$cart = SessionCart::getInstance();

		if ($cart->isUserConnected())
			$this->redirect("commande/create");

		$error = null;

		if (!empty($_POST)) {
			$playername = $_POST["playername"];
			$password   = sha1($_POST["password"]);

			$authObj = API::post("players/authenticate", array(
                "name"     => $playername,
                "password" => $password
            ));

			if ($authObj != null && !$authObj->error) {
				$cart->logUser($authObj);
				$this->redirect("commande/create");
			} else {
				$prefix = "<i class='fa fa-arrow-right'></i>";
				$error = $prefix . "Pseudo ou mot de passe incorrect.";
			}
		}

		$this->render("paiement.login", compact("error"));
	}

}