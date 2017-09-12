<?php
namespace Core\Controller;

use Core\Config;
use Core\Exception\UnknownTemplateException;
use Core\Exception\UnknownViewException;
use Core\Helper\Html;

class Controller {

	protected $table_class;
	protected $template;
	protected $useTable;

	private   $table;
	private   $pageTitle;


	public function __construct() {
		/*     Détection automatique du template à utiliser     */
		if (is_null($this->template))
			$this->template = Config::getInstance()->getValue("template");

		/*     Détection automatique de la table à utiliser    */
		if (is_null($this->useTable) || $this->useTable) {
			if (is_null($this->table_class)) {
				$parts = explode('\\', get_class($this));
				$class_name = end($parts);

				$this->table_class = str_replace('sController', 'Table', $class_name);
                $this->table_class = str_replace('Controller', 'Table', $class_name);
			} else {
				$this->table_class = ucfirst($this->table_class) . 'Table';
			}

			$table_classpath = '\App\Table\\' . $this->table_class;
			$this->table = new $table_classpath(\App::getInstance()->getDb());
		}

		/*     Définition du titre par défaut    */
		$template        = Config::getInstance()->getValue("title.template");
		$defaultTitle    = Config::getInstance()->getValue("title.default");

		$this->pageTitle = str_replace('%s', $defaultTitle, $template);
	}

	public function getTable() {
		return $this->table;
	}

	public function getPageTitle() {
		return $this->pageTitle;
	}

	public function setPageTitle($title) {
		$template        = Config::getInstance()->getValue("title.template");
		
		if (strpos($template, '%s') === false)
			$this->pageTitle = $title;
		else
			$this->pageTitle = str_replace('%s', $title, $template);
	}



	public function render($view_name, $variables = null) {
		if ($this->template == null) return;

		// On extrait les variables s'il y en a
		if (!is_null($variables))
			extract($variables);

        /* -----------------------------
           - Préparations des helpeurs -
           ----------------------------- */
		$Html      = new Html();
		$pageTitle = $this->getPageTitle();

		/* -----------------------------
		   -    Affichage de la vue    -
		   ----------------------------- */
		$view_path = str_replace('.', '/', $view_name);
		$viewFile  = APP . DS . 'Views' . DS . $view_path . '.php';
		
		if (file_exists($viewFile)) {
			ob_start();
			require $viewFile;
			$content_for_layout = ob_get_clean();
		} else {
		    throw new UnknownViewException(["view" => $view_name]);
        }

		/* -----------------------------
		   -   Affichage du template   -
		   ----------------------------- */
		$templateFile = APP . DS . 'Views' . DS . 'templates' . DS . $this->template . '.php';

		if (file_exists($templateFile))
            require $templateFile;
        else
		    throw new UnknownTemplateException(["template" => $this->template]);
	}

}
?>