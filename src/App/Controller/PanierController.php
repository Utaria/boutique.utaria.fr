<?php
namespace App\Controller;

use App;
use App\Helper\Cart\SessionCart;
use Core\Controller\Controller;
use Core\Helper\Html;

class PanierController extends Controller {

	protected $useTable = false;

	public function index() {
		$cart = SessionCart::getInstance();

		$articles = $cart->getArticles();
		$total = $cart->getTotal();

		$cartTotal = number_format($total, 2);
		$promoCode = $cart->getPromoCode();

		$this->render("panier", compact("articles", "cartTotal", "promoCode"));
	}

	public function recap() {
        $cart = SessionCart::getInstance();

        $this->setResponseType("application/json");
        echo json_encode(array(
            "size"     => $cart->getSize(),
            "balance"  => $cart->getTotal(),
            "articles" => $cart->getArticles()
        ));
    }

	public function promotionalcode() {
		if (!isset($_POST["code"]) || empty($_POST["code"]))
			die(json_encode(array("error" => "empty_code")));

		$code = $_POST["code"];

		// Réinitialisation du code
		if ($code == "null") {
			session_start();
			
			if (isset($_SESSION["shopcart"]))
				unset($_SESSION["shopcart"]["promocode"]);

			die("good");
		}

		$cart     = SessionCart::getInstance();
        /**
         * @var $table App\Table\ShopPromoTable
         */
		$table    = App::getInstance()->getTable("ShopPromo");
		$shopCode = $table->findByCode($code);

		$good     = true;
		$errorMsg = null;

		if ($shopCode == null) {
			$good = false;
			$errorMsg = "Code promotionnel inexistant";
		} else if (strtotime($shopCode->expiration_date) < time()) {
			$good = false;
			$errorMsg = "Code promotionnel expiré";
		} else if ($cart->getTotal() < $shopCode->min_price) {
		    $good = false;
		    $errorMsg = "Commande min. de " . $shopCode->min_price . "€ requise";
        } else if (!is_null($shopCode->usable_by)
            && (is_null($cart->getUser()) || $shopCode->usable_by != $cart->getUser()->getId())) {
		    $good = false;
		    $link = "";

		    if (empty($cart->getUser())) {
		        $html = new Html();
		        $link = $html->link("paiement/login/panier", "(Connexion)");
            }

		    $errorMsg = "Code promotionnel réservé $link";
        }

		// Suppression des informations confidentielles
		unset($shopCode->id);
		unset($shopCode->expiration_date);
		unset($shopCode->date);

		// Mise à jour du panier avec le code promo
		if ($good)
			$cart->setPromoCode($shopCode->code);

		$this->setResponseType("application/json");
		echo json_encode(array(
		    "good" => $good,
            "code" => $shopCode,
            "error_msg" => $errorMsg
        ));
	}

	public function changeqty() {
		if (!isset($_POST["product_id"]) || !isset($_POST["qty"]))
			die(json_encode(array("error" => "bad_request")));

		$pId = $_POST["product_id"];
		$qty = $_POST["qty"];

		// Vérification de la session
		session_start();
		$cart  = $_SESSION["shopcart"];
		$prods = $cart["products"];

		if (empty($prods) || !isset($prods["p_$pId"]))
			die(json_encode(array("error" => "Produit inexistant")));

		// Si la quantité est positive, on met à jour sinon,
		// sinon on supprime le produit en session.
		if ($qty > 0)
			$_SESSION["shopcart"]["products"]["p_$pId"] = $qty;
		else
            unset($_SESSION["shopcart"]["products"]["p_$pId"]);

		die("good");
	}

}