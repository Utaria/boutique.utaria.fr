<?php
namespace Core\Routing;

class Router {

	private static $routes = array();


	public static function parse($url, $request) {
		$url    = trim($url, '/');

		// On regarde si une connexion doit être établie
		// grâce aux tableau des routes.
		foreach (self::$routes as $v) {
			if (preg_match($v["catcher"], $url, $match)) {
				$request->controller = $v['controller'];
				$request->action     = isset($match['action']) ? $match['action'] : $v['action'];
				
				$request->params = array();
				foreach ($v['params'] as $k => $v)
					$request->params[$k] = $match[$k];

				return;
			}
		}

		$params = explode('/', $url);

		$request->controller = !empty($params[0]) ? $params[0] : 'index';
		$request->action     =  isset($params[1]) ? $params[1] : 'index';
		$request->params     = array_slice($params, 2);
	}


	public static function connect($redirection, $url) {
		$origin = str_replace(':action', '(?P<action>([a-z0-9\-]+))', $url);
		$origin = preg_replace('/([a-z0-9]+):([^\/]+)/', '${1}:(?P<${1}>${2})', $origin);
		$origin = '/' . str_replace('/', '\/', $origin) . '/';

		$params    = array();
		$urlParams = explode('/', $url);
		$ctrl      = null;
		$action    = null;

		foreach ($urlParams as $k => $v) {
			if (strpos($v, ':')) {
				$p = explode(':', $v);
				$params[$p[0]] = $p[1];
			} else {
				if ($k == 0)
					$ctrl = $v;
				else if ($k == 1)
					$action = $v;
			}
		}

		$catcher = $redirection;
		$catcher = str_replace(':action', '(?P<action>([a-z0-9\-]+))', $catcher);

		foreach ($params as $k => $v)
			$catcher = str_replace(":$k", "(?P<$k>$v)", $catcher);

		$catcher = '/' . str_replace('/', '\/', $catcher) . '/';


		self::$routes[] = array(
			"redirection" => $redirection,
			"origin"      => $origin,
			"params"      => $params,
			"controller"  => $ctrl,
			"action"      => $action,

			"catcher"     => $catcher
		);
	}

}
?>