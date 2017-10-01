<?php
namespace App\Controller;

use Core\Controller\Controller;
use App;

class PanierController extends Controller {

	protected $useTable = false;


	public function index() {
		session_start();

		// unset($_SESSION["shopcart"]);

		if (!isset($_SESSION["shopcart"]) && !isset($_SESSION["shopcart"]["products"]))
			$_SESSION["shopcart"]["products"] = array(
				"p_3" => 1,
				"p_4" => 1,
				"p_5" => 3,
				"p_1" => 1
			);

		$articles    = array();
		$table       = App::getInstance()->getTable("shopArticle");
		$sessionCart = $_SESSION["shopcart"];
		$cartTotal   = 0;

		foreach ($sessionCart["products"] as $pId => $nb) {
			$id      = str_replace("p_", "", $pId);
			$article = $table->find($id);
			
			$article->qty   = $nb;
			$article->price = number_format($article->price, 2);
			$article->cartPrice = number_format($article->price * $article->qty, 2);

			$articles[]  = $article;
			$cartTotal  += $article->cartPrice;
		}

		$cartTotal = number_format($cartTotal, 2);
		$promoCode = isset($_SESSION["shopcart"]["promocode"]) ? $_SESSION["shopcart"]["promocode"] : null;

		$this->render("panier", compact("articles", "cartTotal", "promoCode"));
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
			return;
		}

		$table = App::getInstance()->getTable("ShopPromo");
		$shopCode = $table->findByCode($code);

		$good     = true;
		$errorMsg = null;

		if ($shopCode == null) {
			$good = false;
			$errorMsg = "Code promotionnel inexistant";
		} else if (strtotime($shopCode->expiration_date) < time()) {
			$good = false;
			$errorMsg = "Code promotionnel expiré";
		}

		// Suppression des informations confidentielles
		unset($shopCode->id);
		unset($shopCode->expiration_date);
		unset($shopCode->date);
		unset($shopCode->min_price);

		// Mise à jour de la session
		session_start();

		if ($good && isset($_SESSION["shopcart"]))
			$_SESSION["shopcart"]["promocode"] = $shopCode->code;

		die(json_encode(array("good" => $good, "code" => $shopCode, "error_msg" => $errorMsg)));
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

		$_SESSION["shopcart"]["products"]["p_$pId"] = $qty;
		die("good");
	}

}