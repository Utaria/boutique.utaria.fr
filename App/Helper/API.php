<?php
namespace App\Helper;

use Core\Config;

class API {

	private static $URL = "https://utaria.fr/api/";

	public static function get($method, $params, $useToken = false) {
		$method      = str_replace(".", "/", $method);


		// Utilisation d'un token de requête
		if ($useToken)
			$params["token"] = Config::getInstance()->getValue("api_token");

		$queryParams = http_build_query($params);
		$endpoint    = $method . "?" . $queryParams;

		// Récupération de la valeur en cache si elle existe
		if (session_status() == PHP_SESSION_NONE)
			session_start();
		if (isset($_SESSION["api_cache"][$endpoint]))
			return $_SESSION["api_cache"][$endpoint];

		$ret         = file_get_contents(API::$URL . $endpoint);
		$json        = json_decode($ret);

		// Enregistrement de la valeur en cache
		if (!isset($_SESSION["api_cache"]))
			$_SESSION["api_cache"] = array();

		if (!isset($json->error))
			$_SESSION["api_cache"][$endpoint] = $json;

		return $json;
	}

}
?>