<?php
define('DS'      , DIRECTORY_SEPARATOR);
define('APP'     , dirname(__FILE__));
define('ROOT'    , dirname(APP));
define('CORE'    , ROOT.DS.'Core');
define('BASE_URL', rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), DS));

use Core\Config;
use Core\Database\MySQLDatabase;
use Core\Routing\Dispatcher;

class App {

	private static $_instance;

	private $db;


	public function __construct() {
		require CORE . DS . 'Autoloader.php';
		require  APP . DS . 'Autoloader.php';

		Core\Autoloader::register();
		App\Autoloader::register();
	}

	public function load() {
		new Dispatcher();
	}

	public function getDb() {
		if (is_null($this->db))
			$this->db = MySQLDatabase::newInstance(Config::getInstance()->getDbConfig());

		return $this->db;
	}

	public function notFound() {
		$customName = Config::getInstance()->getValue("customErrorController");
		$ctrlName   = (!empty($customName)) ? $customName : "ErrorController";
		$ctrlName   = "\App\Controller\\$ctrlName";

		header("HTTP/1.0 404 Not Found");
		$ctrl = new $ctrlName();
		$ctrl->error404();
		
		die();
	}

	public function redirect($url) {
		header('Location: ' . $this->getBaseURI() . $url);
		die();
	}

	public function getBaseURI() {
		return BASE_URL . '/' . ((Config::getInstance()->getValue("useURI")) ? 'public/?uri=' : '');
	}

    /**
     * Permet de récupérer un objet table depuis son nom.
     *
     * @param $name string Nom de la table
     * @return Core\Table\Table Objet table liée avec la table SQL
     */
	public function getTable($name) {
		$class_name = '\\App\\Table\\' . ucfirst($name) . 'Table';
		return new $class_name($this->getDb());
	}


	public static function getInstance() {
		if (is_null(self::$_instance))
			self::$_instance = new App();

		return self::$_instance;
	}

}