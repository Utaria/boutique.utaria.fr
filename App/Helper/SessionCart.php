<?php
namespace App\Helper;

use \App;

class SessionCart {

	private static $_instance;

	private $cache;
	private $articles;


	public function __construct() {
		// On démarre la session si besoin
		if (session_status() == PHP_SESSION_NONE)
    		session_start();

    	$this->loadCache();
    	$this->loadArticles();
	}

	public function getArticles() {
		return $this->articles;
	}
	public function getSize() {
		$nb = 0;

		foreach ($this->cache["products"] as $pNb)
			$nb += $pNb;

		return $nb;
	}
	public function getTotal() {
		$c = 0;

		foreach ($this->articles as $article)
			$c += $article->cartPrice;

		return $c;
	}


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

	public function getInstance() {
		if (is_null(self::$_instance))
			self::$_instance = new SessionCart();

		return self::$_instance;
	}

}