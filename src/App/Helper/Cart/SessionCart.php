<?php
namespace App\Helper\Cart;

use App;

class SessionCart {

	private static $_instance;

	private $cache;

    /**
     * @var App\Entity\ShopArticleEntity[] Tableau des articles du panier
     */
	private $articles;

    /**
     * @var SessionUser Utilisateur en session
     */
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
    /**
     * @param $article App\Entity\ShopArticleEntity Article à ajouter
     * @return boolean Vrai si l'article a bien été ajouté au panier
     */
	public function addArticle($article) {
	    // Récupérations nécessaires ...
        if (empty($_SESSION["shopcart"]))
            $_SESSION["shopcart"] = array("products" => array(), "formules" => array(), "promocode" => null);

        $products = $_SESSION["shopcart"]["products"];

        $key = "p_" . $article->id;
        $baseQty = isset($products[$key]) ? $products[$key] : 0;

	    // Vérifications ...
        if ($article->single && $baseQty == 1)
            return false;

        // ... puis ajout !
        $this->articles[] = $article;
        $_SESSION["shopcart"]["products"][$key] = $baseQty + 1;

        // Cas spécifique d'une formule
        if ($article instanceof App\Entity\FormuleCreationEntity)
            $_SESSION["shopcart"]["formules"][] = $article->id;

        return true;
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

	public function isEmpty() {
		return empty($this->articles);
	}

    /**
     * @param $article App\Entity\ShopArticleEntity
     * @return bool Vrai si l'article est une formule, faux sinon.
     */
	public function isFormule($article) {
	    return in_array($article->id, $this->cache["formules"]);
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

	public function getLastCommandeId() {
	    return $_SESSION["last_commande_id"];
    }

	public function setLastCommandeId($commandeId) {
	    $_SESSION["last_commande_id"] = $commandeId;
    }

    public function getLastPaymentMean() {
	    return $_SESSION["last_payment_mean"];
    }

    public function setLastpaymentMean($paymentMean) {
	    $_SESSION["last_payment_mean"] = $paymentMean;
    }

	public function destroy() {
        unset($_SESSION["shopcart"]);
    }

	/* ----------------------------------------- */
	/*  GESTION DE L'UTILISATEUR                 */
	/* ----------------------------------------- */
    /**
     * @return SessionUser Session de l'utilisateur
     */
	public function getUser() {
		return $this->user;
	}

    /**
     * @return int|null
     */
	public function getUserId() {
	    return !is_null($this->user) ? $this->user->getId() : null;
    }

	public function isUserConnected() {
		return !is_null($this->user);
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
				"formules"  => array(),
				"promocode" => null
			);
		}
	}

	private function loadArticles() {
		if (!is_null($this->articles))
			return $this->articles;

		$this->articles = array();
		$table          = App::getInstance()->getTable("shopArticle");
		$formuleTable   = App::getInstance()->getTable("FormuleCreation");

		foreach ($this->cache["products"] as $pId => $nb) {
			$id = str_replace("p_", "", $pId);

			if (in_array($id, $this->cache["formules"]))
			    $article = $formuleTable->find($id);
			else
			    $article = $table->find($id);

			if (empty($article)) continue;

			$article->qty   = $nb;
			$article->price = number_format($article->price, 2);
			$article->cartPrice = number_format($article->price * $article->qty, 2);

			$this->articles[] = $article;
		}

		return $this->articles;
	}

    /**
     * @return SessionUser|null
     */
	private function loadUser() {
		if (!is_null($this->user))
			return $this->user;

		if (!isset($_SESSION["shopauth"]))
			return $this->user = null;

		$authSes = $_SESSION["shopauth"];

		return $this->user = new SessionUser(
		    $authSes->id, $authSes->playername, $authSes->uuid
        );
	}

	public static function getInstance() {
		if (is_null(self::$_instance))
			self::$_instance = new SessionCart();

		return self::$_instance;
	}

}