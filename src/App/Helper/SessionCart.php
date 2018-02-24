<?php
namespace App\Helper;

use App;

class SessionCart {

	private static $_instance;

	private $cache;

	private $articles;

	private $user;

	public function __construct() {
		// On démarre la session si besoin
		if (session_status() == PHP_SESSION_NONE)
    		session_start();

    	$this->loadCache();
    	$this->loadArticles();
    	$this->loadUser();
	}

	/* ----------------------------------------- */
	/*  GESTION DU PANIER                        */
	/* ----------------------------------------- */
	public function getArticles() {
		return $this->articles;
	}

	public function getSize() {
		$nb = 0;

		foreach ($this->cache["products"] as $pNb)
			$nb += $pNb;

		return $nb;
	}

	public function isEmpty() {
		return empty($this->articles);
	}

	public function getTotal() {
		$c = 0;

		foreach ($this->articles as $article)
			$c += $article->cartPrice;

		return $c;
	}

	public function getPromoCode() {
		return isset($this->cache["promocode"]) ? $this->cache["promocode"] : null;
	}

	public function setPromoCode($promocode) {
		$_SESSION["shopcart"]["promocode"] = $promocode;
		$this->cache["promocode"] = $promocode;
	}

	public function destroy() {
        unset($_SESSION["shopcart"]);
    }

	/* ----------------------------------------- */
	/*  GESTION DE L'UTILISATEUR                 */
	/* ----------------------------------------- */
	public function getUser() {
		return (object) $this->user;
	}

	public function getUserId() {
	    return !empty($this->user) ? $this->user->id : null;
    }

	public function isUserConnected() {
		return !empty($this->user);
	}

	public function logUser($player) {
		$_SESSION["shopauth"] = $player;
	}


	/* ----------------------------------------- */
	/*  CHARGEMENT DES DONNÉES                   */
	/* ----------------------------------------- */
	private function loadCache() {
		$this->cache = isset($_SESSION["shopcart"]) ? $_SESSION["shopcart"] : null;

		// Initialisation du cache si besoin
		if (empty($this->cache)) {
			$this->cache = array(
				"products"  => array(),
				"promocode" => null
			);
		}
	}

	private function loadArticles() {
		if (!is_null($this->articles))
			return $this->articles;

		$this->articles = array();
		$table          = App::getInstance()->getTable("shopArticle");

		foreach ($this->cache["products"] as $pId => $nb) {
			$id      = str_replace("p_", "", $pId);
			$article = $table->find($id);

			$article->qty   = $nb;
			$article->price = number_format($article->price, 2);
			$article->cartPrice = number_format($article->price * $article->qty, 2);

			$this->articles[] = $article;
		}

		return $this->articles;
	}

	private function loadUser() {
		if (!is_null($this->user))
			return $this->user;

		if (!isset($_SESSION["shopauth"])) {
			$this->user = false;
			return null;
		}

		return $this->user = $_SESSION["shopauth"];
	}

	public static function getInstance() {
		if (is_null(self::$_instance))
			self::$_instance = new SessionCart();

		return self::$_instance;
	}

}