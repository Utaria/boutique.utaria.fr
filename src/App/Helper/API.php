<?php
namespace App\Helper;

use Core\Config;
use Exception;

class API {

	private static $BASE_URL;

	private static $token;

	private static $cache;

	public static function get($method, $params) {
		return API::request($method, "GET", $params);
	}

    public static function post($method, $params) {
        return API::request($method, "POST", $params);
    }

	private static function request($method, $type, $params) {
        // Mise en place des paramètres
        // Définition de l'URL de l'API
        if (empty(Api::$BASE_URL))
            Api::$BASE_URL = Config::getInstance()->getValue("api.endpoint");

        $method = str_replace(".", "/", $method);
        $getParams = "";

        if ($type == "GET")
            $getParams = "?" . http_build_query($params);

        $url = Api::$BASE_URL . $method . $getParams;

        // Récupération de la valeur en cache si elle existe
        if (isset(Api::$cache[$url])) return Api::$cache[$url];

        // Connexion à l'API si nécessaire
        if ($method != "authenticate")
            Api::$token = Api::authenticate();

        // Paramétrage de la requête ...
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);

        if ($type == "POST") {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
        }

        if (!is_null(API::$token))
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'x-access-token: ' . API::$token,
                'Accept: application/json',
                'cache-control: no-cache'
            ));

        // ... puis on l'exécute et on la ferme.
        $res = curl_exec($curl);
        $json = json_decode($res);
        curl_close($curl);

        // Et enfin on enregistre la réponse dans le cache.
        if (is_null(Api::$cache)) Api::$cache = array();
        if (!isset($json->error)) Api::$cache[$url] = $json;

        return $json;
    }

    private static function authenticate() {
	    if (session_status() == PHP_SESSION_NONE)
	        session_start();

	    if (isset($_SESSION["api_auth_token"])) {
	        $expir = $_SESSION["api_auth_expir"] / 1000;

	        if ($expir > time())
	            return $_SESSION["api_auth_token"];
        }

        $username = Config::getInstance()->getValue("api.username");
        $password = Config::getInstance()->getValue("api.password");

        $auth = Api::post("authenticate", array(
            "name" => $username,
            "password" => $password
        ));

        if ($auth == null || !$auth->success)
            throw new Exception("Impossible de se connecter à l'API !");

        $_SESSION["api_auth_token"] = $auth->token;
        $_SESSION["api_auth_expir"] = $auth->expiresAt;

        return $auth->token;
	}

}